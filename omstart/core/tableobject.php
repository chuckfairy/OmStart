<?php
/*****************************TABLEOBJECT*****************************/

class TableObject extends DatabaseObject {
	
	protected static $table_name;
	protected static $validations;
	protected static $filter = array();
	public static    $offset;
	public static    $limit=30;
	public static    $select=" * ";
	public static    $order;
	public static    $join = null;
	public static    $join_by = null;
	public $id;
	public $page;
	public $total_count;
	public $per_page = 30;
	public $page_attr="instrumentation";
	private $upload_errors = array(
		UPLOAD_ERR_OK => "No errors.",
		UPLOAD_ERR_INI_SIZE   => "Larger than upload_max_filesize.",
		UPLOAD_ERR_FORM_SIZE  => "Larger then form MAX_FILE_SIZE.",
		UPLOAD_ERR_FORM_SIZE  => "Partial upload",
		UPLOAD_ERR_NO_FILE    => "No file.",
		UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
		UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
		UPLOAD_ERR_EXTENSION  => "File upload stopped by extension"
	);
	

	function TableObject($table_name, $validations=NULL) {
		global $database;
		$database->validate_table($table_name);
		self::$table_name = $table_name;
		self::$validations = $validations;
	} 
	
	public static function new_self($table_data, $table) {
		$new_object = new TableObject($table);
		$fields = $new_object::get_fields();
		foreach($fields as $field) {
			if(isset($table_data[$field])) {
				$new_object->$field = $table_data[$field];
			}
		}
		return $new_object;
		
	}
	
	public function get_attributes() {
		$fields = self::get_fields();
		$attributes = array();
		foreach($fields as $attribute) {
			if(isset($this->$attribute)) {
				$attributes[$attribute] = self::escape($this->$attribute);
			}
		}
		return $attributes;
	}
	
	private function set_attributes($assoc) {
		foreach($assoc as $attribute => $value) {
			if(self::is_field($attribute)) {
				$this->$attribute = $value;
			}
		}
	}
	
	public function save($attributes=null) {
		global $session;
		
		$attributes = $this->get_attributes();
		if(isset($this->id)) {
			if(!empty(static::find_by("id", $this->id)[0])) {
				//Id is already set let us update!
				static::update($attributes);
			}
		}
		
		//Id not set, or id not found let us create!
		if(self::create($attributes)) {return true;} 
		else {return false;}
	}
	
	public function delete_by_id($id) {
		global $session;
		if($this->find_by("id", $id)) {
			if(static::delete($id)) {
				$session->set_message("Page id:{$id} has been deleted.");
				return true;
			}
		} else {
			//This shouldn't happen.
			$session->set_error("Delete Failed from TableObject.");
			return false;
		}
	}
	
	
	
/*****************************PAGINATION*****************************/

	
	public function offset() {
		return ($this->page - 1) * self::$limit;
	}
	
	public function total_pages() {
		global $database;
		$attr = $this->page_attr;
		$sql = "SELECT COUNT(*) FROM music ";
		$sql.= "WHERE ".static::specify(false);
		
		$result = self::query($sql);
		$count = mysqli_fetch_assoc($result);
		$this->total_count = $count["COUNT(*)"];
		return ceil($this->total_count/$this::$limit);
	}
	
	public function previous_page() {return $this->page - 1;}
	
	public function next_page() {return $this->page + 1;}
	
	public function has_previous_page() {return $this->previous_page() >= 1 ? true : false;}
	
	public function has_next_page() {
		return $this->next_page() <= $this->total_pages() ? true : false;
	}
	
	public function set_offset($number) {
		if(is_int($number)) {
			self::$offset = $this->offset($number);
			return true;
		}
		return false;
	}
	
	public function paginate($current_page) {
		$this->page = (int) $current_page;
		$this->set_offset($this->page);
	}
	
	public function paginate_output($link, $g="p", $selected_tag="selected") {
		
		$g = $g."="; //get value
		
		if($this->has_previous_page()) {
			$previous = $g.$this->previous_page();
			echo "<a href='{$link}{$previous}'>Previous Page</a>";
		}
		
		for($i=1;$i <= $this->total_pages($this->page);$i++) {
			if($i == $this->page) {
				echo "<a class='{$selected_tag}'>{$i}</a>";
			} else {
				echo "<a href='{$link}{$g}{$i}'>{$i}</a>";
			}
		}
		
		if($this->has_next_page()) {
			$next = $g.$this->next_page();
			echo "<a href='{$link}{$next}'>Next Page</a>";
		}
	}
	
	/****Cleansing****/
	
	private function sanitize() {
		$attributes = $this->get_attributes();
		foreach($attributes as $attribute => $value) {
			$this->$attribute = self::escape($value);
		}
	}
	
	private function validate() {
		global $session;
		$attributes = $this->get_attributes();
		foreach($attributes as $attribute => $value) {
			if(!self::validate_length($value, $attribute)) {
				$session->set_error("{$attribute} was too long.");
			}
		}
	}
/*****************************UPLOADING*****************************/
	

		
}	

?>