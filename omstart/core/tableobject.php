<?php
/*****************************TABLEOBJECT*****************************/

class TableObject extends DatabaseObject {
	
	protected static $table_name;
	protected static $validations;
	protected static $filter = array();
	public static    $offset;
	public static    $limit=30;
	public static    $order;
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
		global $admin;
		//$admin->check_login();
		
		//!is_null($attributes)? $this->set_attributes($attributes): null;
		$this->validate();
		$this->sanitize();
		if(!empty($session->errors)) {return false;}
		
		
		$attributes = $this->get_attributes();
		if(isset($this->id)) {
			//Id is already set let us update!
			if(self::update($attributes)) {
				$session->set_message("Update Successful.");
				return true;
			} else {
				$session->set_message("Update failure.");
				return false;
			}	
		}
		
		else {
			//Id not set, let us create!
			if(self::create($attributes)) {return true;} 
			else {return false;}
		}
	}
	
	public function delete_by_id($id) {
		global $session;
		$id = self::escape($id);
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
	
	public function file_upload($table, $tmp_file, $path, $file_name) {

		//Change the instrumentation too the dir name. 
		if(isset($this->instrumentation)) {
			$instrumentation = i_dir($this->instrumentation);
		} else {
			$session->set_message("instrumentation not set.");
			return false;
		}
		$file_name = clean_file($file_name); //Remove unwanted characters
		$path = g_dir($path);
		global $session;
		
		
		//Picture Upload
		if($table == "picture_path"  && is_picture($file_name)) {
			//basename makes sure it just gets the basename without extension
			$upload_dir = ASSETS."pictures".DS.$path;
			echo $tmp_file."->".$upload_dir.DS.$file_name."<br/>";
			
			//move_uploaded_file will return false if $tmp_file is not valid
			if(move_uploaded_file($tmp_file, $upload_dir.DS.$file_name)) {
				$this->picture_path = $path.DS.$file_name;
				$session->set_message("Picture file uploaded successfully.");
				return true;
			} else {
				$error = $_FILES["picture_upload"]["error"];
				$session->set_message($this->upload_errors[$error]);
				return false;
			}
		}
			
		//First Page Upload	
		if($table == "first_page") {
			$upload_dir = ASSETS."first_page".DS.$instrumentation.DS.$path;
			echo $tmp_file."->".$upload_dir.DS.$file_name."<br/>";
			
			if(move_uploaded_file($tmp_file, $upload_dir.DS.$file_name)) {
				$this->first_page = $instrumentation.DS.$path.DS.$file_name;
				$session->set_message("Page file uploaded successfully.");
				return true;
			} else {
				$error = $_FILES["page_upload"]["error"];
				$session->set_message($this->upload_errors[$error]);
				return false;
			}
		}
		
		//Zip Upload
		if($table == "zip") {
			$upload_dir = SRC.DS."zips".DS.$instrumentation.DS.$path;
			echo $tmp_file."->".$upload_dir.DS.$file_name."<br/>";
			
			if(move_uploaded_file($tmp_file, $upload_dir.DS.$file_name)) {
				$this->zip = $instrumentation.DS.$path.DS.$file_name;
				$session->set_message("Zip file uploaded successfully.");
				return true;
			} else {
				$error = $_FILES["zip_upload"]["error"];
				$session->set_message($this->upload_errors[$error]);
				return false;
			}
		}
		
		//Mp3 Upload
		if($table == "mp3") {
			$upload_dir = SITE_ROOT.DS."_mp3".DS.$instrumentation.DS.$path;
			echo $tmp_file."->".$upload_dir.DS.$file_name."<br/>";
			
			if(move_uploaded_file($tmp_file, $upload_dir.DS.$file_name)) {
				$this->mp3 = $instrumentation.DS.$path.DS.$file_name;
				$session->set_message("Mp3 file uploaded successfully.");
				return true;
			} else {
				$error = $_FILES["mp3_upload"]["error"];
				$session->set_message($this->upload_errors[$error]);
				return false;
			}
		}						
	}
		
/*************************************************************DATA FOR LMP******************************************************/

		
}	

?>