<?php

class DatabaseObject {

	private $connection; //Set outside class
	private static $last_query; //Set in various methods
	public static $tables=array(); 
	public static $table_data = array();
	public static $link; //Set in the construct
	protected static $PDO;
	private $server, $user, $password, $db_name;

/*************************************************************GENERAL  METHODS******************************************************/
	
	function __construct() {

	}
		
	public function connect($server=null, $db_name=null, $user=null, $pass=null ) {
		$this->server = $server;
		$this->db_name = $db_name;
		$this->user = $user;
		$this->password = $pass;
		try {
			self::$PDO = new PDO("mysql:host=".$server.";dbname=".$db_name, $user, $pass, 
				array(PDO::ATTR_PERSISTENT => true)
			);
			self::$PDO->exec("set names utf8");
		} catch(PDOException $e) {
			echo $e->getMessage();
			exit;	
		}
		$this->get_tables();
		$this->extract_tables();
		return true;
	}
	
	public function close_connection() {
		if(isset(self::$PDO)) {self::$PDO = null;}
	}
	
	public static function confirm_query($result_set) {
		global $database;
		if (!$result_set) {
			$last_query = self::$last_query;
			print_r($last_query);
			die("Database query failed.");
		}
	}
	
	public static function escape($string) {
		$safe_string = filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
		//$safe_string = mysql_real_escape_string($safe_string);
		return $safe_string;
	}
		
	private function insert_id() {return mysqli_insert_id(self::$link);}
  
    private static function affected_rows() {return mysqli_affected_rows(self::$link);}

	
/*************************************************************DATABASE METHODS******************************************************/
	//Main query function using PDO	
	public static function query($sql, $execute=null) {  //String must be sanitzed
		//try {		
			self::$last_query = self::$PDO->prepare($sql);
			self::$last_query->execute($execute);
			$result = self::$last_query->fetchAll(PDO::FETCH_ASSOC);
			//echo $sql."<br/>";
		//} catch(PDOException $e) {
		//	print_r($e);
		//}
		return $result;
	}
/****************************Read Methods****************************/
	//FIND methods
	public static function find_all($all=true) {
		if($all) {$select = " * ";}
		else {$select = static::$select;}
		$query = "SELECT ".$select." FROM ".static::$table_name.static::$join." ";
		return static::specify($query);
	}
	
	public static function find_by_id($id=0) {
		global $session;
		$query = "SELECT * FROM ".static::$table_name." WHERE id = :id LIMIT 1";
		return self::query($query, [":id"=>$id]);
	}
	
	public static function find_by($field, $subject, $debug=false) {
		global $database;
		$query = "SELECT ".static::$select." FROM ".static::$table_name.static::$join;
		static::filter_out($field, $subject);
		return static::specify($query);
	}
	
	public static function select($select_array) {
		static::$select = implode(" , ",$select_array);
	}
	
	public static function join($jointable, $tablefield, $joinfield) {
		if(static::validate_table($jointable) && self::is_field($tablefield)) {
			static::$join = ", {$jointable} ";
			static::$join_by = " ".static::$table_name.
				".{$tablefield}={$jointable}.{$joinfield} ";
		}
	}

	public static function join_clear() {
		static::$join = null;
		static::$join_by = null;
	}
	
	
/****************************CUD Methods****************************/
	
	//CUD Actions
	protected static function create($attributes) {
		$sql = "INSERT INTO ".static::$table_name." (";
		$sql.= join(", ", array_keys($attributes));
		$sql.= ") VALUES (:";
		$sql.= join(", :", array_keys($attributes));
		$sql.= ");";
		$execute = array();
		foreach($attributes as $field => $value) {
			$execute[":{$field}"] = $value;
		}
		echo $sql;
		print_r($execute);
		return $results = self::query($sql, $execute);
	}
	
	public static function update($attributes) {
		global $database;		
		$attribute_pairs = array();
		if(!isset($attributes["id"])) {return false;}
		$id = $attributes["id"];unset($attributes["id"]);
		
		$execute = array();
		foreach($attributes as $key => $value) {
			$attribute_pairs[] = "{$key}= :{$key}";
			$execute[":{$key}"] = $value;
		}
		$sql  = "UPDATE ".static::$table_name." SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE id=".$id;
		return $result = self::query($sql, $execute);
	}
	
	protected static function delete($id) {
		$sql  = "DELETE FROM ".static::$table_name;
		$sql .= " WHERE id= :id";
		$sql .= " LIMIT 1";
		return self::query($sql, [":id"=>$id]);
	}
	
	public function last_query() {
		echo $this->last_query;
	}
	
/*************************************************************VALIDATIONS  METHODS******************************************************/
	
	private function get_tables() {
		self::$tables = array();
		$query_table = "SHOW TABLES;";
		$query_data = self::query($query_table);
		$table_index = "Tables_in_".DB_NAME;
		foreach($query_data as $table){array_push(self::$tables, $table[$table_index]);}
		return self::$tables;
	}
	
	//Loops through each table and extracts table data in 
	//table_data by their table name.
	private function extract_tables() {
		foreach(self::$tables as $table) {
			self::$table_data[$table] = $this->table_data($table);
		}
		//var_dump(self::$table_data);
		
	}
	
	//Get description and load data in new array
	private function table_data($table) {
		
		$fields = self::query("DESCRIBE ".$table);
		$table_data = [];
		
		foreach($fields as $d) {		
			 
			if($d["Key"] === "PRI") {$key="primary";}
			else {$key = "";} 
			$type = $this->get_field_type($d["Type"]);
			
			$field_data = [
				"null"    => $d["Null"],
				"key"     => $key,
				"type"    => $type["type"],
				"max"     => (int) $type["length"],
				"default" => $d["Default"],
				"extra"   => $d["Extra"],
			
			];
			
			$table_data[$d["Field"]] = $field_data;
		}
		return $table_data;
	}
	
	private function get_field_type($type_data) {
		$return_data = [];
		//VARCHAR
		if(preg_match("/varchar/i", trim($type_data))) {
			$return_data["length"] = preg_replace("/[^0-9,.]/", "", $type_data);
			$return_data["type"]   = "VARCHAR";
			return $return_data;
		}
		//INT
		elseif(preg_match("/int/i", $type_data)) {
			$return_data["length"] = preg_replace("/[^0-9,.]/", "", $type_data);
			$return_data["type"]   = "INT";
			return $return_data;					
		} 
		//TEXT
		elseif(preg_match("/text/i", trim($type_data))) {
			$return_data["length"] = 2500;
			$return_data["type"]   = "text";
			return $return_data;		
		}	
	}
	
	
	public static function validate_length($subject=null, $field) {
		if(static::is_field($field)) {
			
		}
	}
	
	public static function get_fields() {
		$table_data = self::$table_data[static::$table_name];
		return array_keys($table_data);
	}
		
	public static function validate_table($table_name) {
		foreach(self::$tables as $table) {
			if($table == $table_name) {
				return true;
			}
		}
		return false;
	}		
	
	public static function is_field($field) {
		$table_fields = static::get_fields();
		foreach($table_fields as $table_field) {
			if($table_field === $field) {return true;}
		}
		return false;
	}
	
/*************************************************************QUERY SPECIFIERS******************************************************/	
	
	public static function filter_out($attribute, $value=null, $OR=false) {
		//Check  if filter is set.
		if($value == null) {
			if ($OR){$delim = " IS NULL ";}
			else {$delim = " != '' ";}
			$push = $attribute.$delim;
			static::$filter["NULL"] = $push;
			return true;
		} else {
			if($attribute) {
				$delim="";
				if($OR == true) {
					$delim = "||";
				}
				static::$filter["{$delim}{$attribute}"] = $value;
				return true;
			}
		}
		return false;
	}
	
	public static function set_order($field, $ASC=true) {
		if(static::is_field($field)) {
			if($ASC) {
				static::$order = $field." ASC ";
			} else {
				static::$order = $field." DESC ";
			}
		}
	}
	
	protected static function specify($query, $use_offset=true) {
		$addition = null;
		$i=0;
		$execute = array();
		
		if(!empty(static::$filter)) {		
			foreach(static::$filter as $filterkey => $filtervalue) {
				//Delim set to AND by default changed by || before keyvalue
				$delim = " AND ";
				
				if($filterkey == "NULL") {
					$addition.= $delim.$filtervalue;
					$i++;continue;
				}
				
				//Get OR statements
				if(preg_match("/\|/", $filterkey)) {
					$delim = " || ";
					$filterkey = str_replace("||", "", $filterkey);
				}
				
				$addition.= ($i != 0)? $delim : " WHERE ";
				
				if(is_array($filtervalue)) {
					$t = 0;
					foreach($filtervalue as $value ) {
						if($t > 0 ) {
							$addition.= " || :{$filterkey}{$t}";	
						} else {						
							$addition.= " {$filterkey} = :{$filterkey}{$t} ";
						}
						$execute[":{$filterkey}{$t}"] = $value;
						$t++;
					}
					$i++;
					continue;
				}
				
				$addition.= " ".static::$table_name.".{$filterkey} = :{$filterkey} ";
				$execute[":{$filterkey}"] = $filtervalue;
				$i++;
			}
		}
		
		
		if(!empty(static::$join_by)) {
			if(!empty(static::$filter)) {$addition.= " AND ".static::$join_by;}
			else {$addition.= " WHERE ".static::$join_by;}	
		}	
		
		if(!empty(static::$order)) {
			$addition.= " ORDER BY ".static::$order;
		}
		if(!empty(static::$limit)) {
			$addition.= " LIMIT ".static::$limit;
		}
		if(!empty(static::$offset) && $use_offset == true) {
			$addition.= " OFFSET ".static::$offset;
		}
		return self::query($query.$addition, $execute);;
	}
	
	public function clear_filters() {
		$filter_vars = ["limit", "offset", "order"];
		foreach($filter_vars as $var) {
			if(isset($this->$var)) {unset($this->$var);}
		}
		static::$filter = array();
	}
	
}

$database = new DatabaseObject(); //This must be set!!
$database->connect(DB_SERVER, DB_NAME, DB_USER, DB_PASS);
?>