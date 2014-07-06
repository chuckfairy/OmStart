<?php

class DatabaseObject {

	private $connection; //Set outside class
	private static $last_query; //Set in various methods
	public static $tables=array(); 
	public static $link; //Set in the construct
	protected static $PDO;
	private $server, $user, $password, $db_name;

/*************************************************************GENERAL  METHODS******************************************************/
	
	function __construct() {
/*
		$this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		if(!$this->connection) {
			die("Database connection failed: " . mysql_error());
		} else {
			self::$PDO = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS);
			self::$link = $this->connection;
			$this->data(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
			$this->get_tables();
			mysqli_set_charset(self::$link, "utf8");
			return true;		
		}
*/
	}
	
	public function get_tables() {
		self::$tables = array();
		$query_table = "SHOW TABLES;";
		$query_data = self::query($query_table);
		$table_index = "Tables_in_".DB_NAME;
		foreach($query_data as $table){array_push(self::$tables, $table[$table_index]);}
		return self::$tables;
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
	
	private static function check_query_data($result) {
		if(self::assoc($result)) {return false;}
	}
	
	//public static function assoc($assoc) {return mysqli_fetch_assoc($assoc);}
	
	private function insert_id() {return mysqli_insert_id(self::$link);}
  
    private static function affected_rows() {return mysqli_affected_rows(self::$link);}

	
/*************************************************************DATABASE METHODS******************************************************/
	//Main query function using PDO	
	public static function query($sql, $execute=null) {  //String must be sanitzed		
		self::$last_query = self::$PDO->prepare($sql);
		self::$last_query->execute($execute);
		$result = self::$last_query->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
	//FIND methods
	public static function find_all() {
		$query = "SELECT * FROM ".static::$table_name." ";
		return static::specify($query);
	}
	
	public static function find_by_id($id=0) {
		global $session;
		$query = "SELECT * FROM ".static::$table_name." WHERE id = :id LIMIT 1";
		return self::query($query, [":id"=>$id]);
	}
	
	public static function find_by($field, $subject, $limit=1) {
		global $database;
		$query = "SELECT * FROM ".static::$table_name;
		$query.= " WHERE {$field} = :{$field} LIMIT {$limit}";
		$result = self::query($query, [":{$field}" => $subject]);
		return $result;
	}
	
	
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
		return $results = self::query($sql, $execute);
	}
	
	public static function update($attributes) {
		global $database;		
		$attribute_pairs = array();
		if(!isset($attributes["id"])) {return false;}
		$id = $attributes["id"];unset($attributes["id"]);
		foreach($attributes as $key => $value) {
			$attribute_pairs[] = "{$key}='{$value}'";
		}
		$sql  = "UPDATE ".static::$table_name." SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE id=".$id;
		$database->query($sql);
		$affected_rows = self::affected_rows();
		return true;
	}
	
	protected static function delete($id) {
		$sql  = "DELETE FROM ".static::$table_name;
		$sql .= " WHERE id= :id";
		$sql .= " LIMIT 1";
		self::query($sq, $id);
		return (self::affected_rows() == 1) ? true : false;
	}
	
	public function last_query() {
		echo $this->last_query;
	}
	
/*************************************************************VALIDATIONS  METHODS******************************************************/
	
	public static function validate_length($subject, $field) {
		global $session;
		$description = self::query("DESCRIBE ".static::$table_name);
		foreach($description as $data) {
		//Find the desired field.
			if($data["Field"] == $field) { 
				//Check what to data type to validate
				$data_type = $data["Type"];
				
				//*VARCHAR*
				if(preg_match("/varchar/i", $data_type)) {
					$type_length = preg_replace("/[^0-9,.]/", "", $data_type);
					$length_number = intval($type_length);
					if($length_number >= strlen($subject)) {
						// The string is good to go.
						return true;
					} else {
						// The string is too long.
						return false;
					}
					
				} else {
					//Not Varchar
					return true;
				}				
				
				//*INT*, *TINYINT*, 
				// NOT FUNCTIONING
				if(preg_match("/int/i", $data_type)) {
					return true;					
				}						
			}
		}
	}
	
	public static function get_fields() {
		$descriptions = self::query("DESCRIBE ".static::$table_name);
		$fields = array();
		foreach($descriptions as $description) {
			array_push($fields, $description["Field"]);
		}
		return $fields;
	}
		
	public function validate_table($table_name) {
		foreach(self::$tables as $table) {
			if($table == $table_name) {
				return true;
			}
		}
		return false;
	}		
	
	protected static function is_field($field) {
		global $database;
		$descriptions = $database->query("DESCRIBE ".static::$table_name);		
		foreach($descriptions as $description) {
			if($description["Field"] == $field) {return true;}
		}
		return false;	
	}
	
/*************************************************************QUERY SPECIFIERS******************************************************/	
	
	public static function filter_out($attribute, $value=null) {
		//Check  if filter is set.
		if(isset(static::$filter[$attribute])) {
			return false;
		}
		
		if($value == null) {
			$push = $attribute." != '' ";
			array_push(static::$filter, $push);
			return true;
		} else {
			if(static::is_field($attribute)) {
				static::$filter[$attribute] = $value;
				return true;
			}
		}
		return false;
	}
	
	protected static function specify($query, $use_offset=true) {
		$addition = null;
		$i=0;
		if(!empty(static::$filter)) {
			foreach(static::$filter as $filterkey => $value) {
				if($i > 0) {
					$addition.= " AND {$filter}= :{$filter} ";
				} else {
					$addition.= "WHERE {$filterkey} = :{$filterkey} ";
					$i++;
				}
			}
		}
		
		if(!empty(static::$order)) {
			$addition.= " ORDER BY ".static::$order." ASC ";
		}
		if(!empty(static::$limit)) {
			$addition.= " LIMIT ".static::$limit;
		}
		if(!empty(static::$offset) && $use_offset == true) {
			$addition.= " OFFSET ".static::$offset;
		}
		return self::query($query.$addition, static::$filter);;
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