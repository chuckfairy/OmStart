<?php
//require("../databaseobject.php");

/*************************************************************ADMIN******************************************************/

class Admin extends DatabaseObject {
	
	protected static $table_name="admins";
	protected static $salt_length=30;
	public static $validations = array("username", "password");
	public static $filter = array();
	private $id;
	private $logged_in = false;
	public $username;
	public $password;
	
	/*Functions*/
	
	function __construct() {
		global $_SESSION;
		if(isset($_SESSION["admin_id"])) {
			$this->username = $_SESSION["username"];
			$this->logged_in = true;
		} else {
			$this->logged_in = false;
		}
	}

	private static function has_attribute($attribute) {
		//Does the key exist.
		$user_object = new User();
		$object_vars = get_object_vars($user_object);
		return array_key_exists($attribute, $object_vars); 
	}
	
	private function get_attributes($which_array="") {
		$attributes = NULL;
		if(!empty($which_array)) {
			foreach($which_array as $field) {
				if(isset($this->$field)) {
					$attributes[$field] = $this->$field;	
				} else {return false;}
			}
			return $attributes;
			
		} else {
			return false;	
		}	
	}

/*************************************************************LOG-IN********************************************************/

	public static function password_encrypt($password) {
	  	$hash_format = "$2y$10$";   // Blowfish with a "cost" of 10
		$salt = self::generate_salt();
		$format_and_salt = $hash_format.$salt;
		$hash = crypt($password, $format_and_salt);
		return $hash;
	}

	private static function generate_salt() {
		// MD5 returns 32 characters
		$unique_random_string = md5(uniqid(mt_rand(), true));		
		$base64_string = base64_encode($unique_random_string);
		$modified_base64_string = str_replace('+', '.', $base64_string);
		// Truncate string to the correct length
		$salt = substr($modified_base64_string, 0, self::$salt_length);
		return $salt;
	}

	private function password_check($password, $existing_hash=null) {
		// existing hash contains format and salt at start
		if($this->check_login()) {
			$existing_hash = $this->get_hash();
		}
		$hash = crypt($password, $existing_hash);
		if ($hash === $existing_hash) {return true;} 
		else {return false;}
	}		

	public function attempt_login($username="", $password="") {
		global $session;
		$username = self::escape($username);
		$password = self::escape($password);
		$found_user = self::assoc(static::find_by("username", $username));
		if($found_user) {
			// Username exists check password
			if(self::password_check($password, $found_user["hashed_password"])) {
				$session->set_message($found_user["username"]);
				$_SESSION["admin_id"] =	$found_user["id"];
				$_SESSION["username"] = $found_user["username"];
				$this->logged_in = true;
				$session->set_message("Login a success");
				//redirect_to("command.php");
				return true;
			} else {
				$session->set_message("Password is incorrect.");
				return false;
			}
		} else {
			$session->set_message("Username not found.");
			return false;
		}
	}
	
	private function get_hash() {
		$admin_data = self::find_by("id", $_SESSION["admin_id"]);
		$admin = self::assoc($admin_data);
		return isset($admin["hashed_password"])? $admin["hashed_password"]:null;
	}
	
	public function check_login() {
		global $session;
	    if($this->logged_in == true) {
			return true;
		} elseif(!isset($_SESSION["admin_id"])) {
			$this->logout();
			return false;
		}
	}
	
	public function logout() {
		unset($_SESSION["admin_id"],$_SESSION["username"]);
	}
		
	public function delete_page($id, $password) {
		$this->check_login();
		global $music_table;
		global $session;
		$found_admin = self::find_by("username", $_SESSION["username"]);
		$found_admin = self::assoc($found_admin);
		$id = self::escape($id);
		//Check id of page
		if($music_table->find_by("id", intval($id))) {
			//Password check
			if($this->password_check($password)) {
				if($music_table->delete($id)) {
					$session->set_message("Page deleted.");
					return true;
				} else {
					die("Failed to delete");
					exit;
				}
			} else {
				$session->set_error("Password did not match.");
			}
			return false;
		}
	}
	
	
} 


$admin = new Admin();

?>