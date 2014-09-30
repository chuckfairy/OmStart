<?php


/****************************om_admin session and functions****************************/
class Admin extends DatabaseObject {
	
	protected static $table_name="om_admin";
	public static $filter = array();
	private $logged_in = false;
	public static    $select = " * ";
	public static    $join;
	public static    $join_by;
	
	/*Functions*/
	
	function __construct() {
		global $_SESSION;
		if(isset($_SESSION["admin_id"])) {
			$this->username = $_SESSION["admin_username"];
			$this->logged_in = true;
		} else {
			$this->logged_in = false;
		}
	}
	
	public function create_admin($username, $password, $email) {
		$query = array();
		$query["username"] = $username;
		$query["hash"] = $this->password_encrypt($password);
		$query["email"] = $email;
		static::create($query); 
		if(isset(static::find_by("username", $username)[0])) {
			global $session;
			$session->set_message("Admin created successfully");
			return true;
		}
		return false;
	}
	
	
	/****************************Login + password Functions****************************/
	public function attempt_login($username="", $password="") {
		global $session;
		
		$found_user = static::find_by("username", $username);
		if(!empty($found_user)) {
			// Username exists check password
			if($this->hash_verify($password, $found_user[0]["hash"])) {
				$this->login($found_user[0]);
				return true;
			} else {
				return false;
			}
		} else {
			$session->set_message("Username not found.");
			return false;
		}
	}
	
	private function login($user) {
		global $session;
		if(!isset($user["id"], $user["username"])) {$this->set_message("Data is empty."); }
		$_SESSION["admin_id"] =	$user["id"];
		$_SESSION["admin_username"] = $user["username"];
		$this->logged_in = true;
		$session->set_message("{$user['username']} is now logged in!");
		return true;
	}
	
	public function is_logged_in() {
		return $this->logged_in;
	}
	
	public function password_encrypt($password) {
		$options = ['cost' => 12,];
		$hash = password_hash($password, PASSWORD_BCRYPT, $options);
		return $hash;
	}
	
	private function hash_verify($password, $hash) {
		if(password_verify($password, $hash)) {
			return true;
		} else {
			global $session;
			$session->set_message("Password is incorrect");
			return false;
		}
	}
	
	public function logout() {
		unset($_SESSION["admin_id"],$_SESSION["admin_username"]);
	}
	
	
	/****************************Change Admin Data****************************/
	public function change_username($new_username='', $password='') {
		global $session;
		
		if(!$this->is_logged_in()) {return false;}
		
		if(strlen($new_username) < 3) {
			$session->set_message("String length < 3");
			return false;		
		}

		
		//Clean username to acceptable characters
		$new_username = clean_file(trim($new_username));
		
		//Get current admin and all admin data
		$om_admin = new TableObject("om_admin");
		$om_admin->clear_filters();
		$admins = $om_admin->find_all();
		$current_admin = $om_admin->find_by("id", $_SESSION["admin_id"])[0];
		
		//Check if admin name is taken
		foreach($admins as $admin) {
			if($admin["username"] === $new_username) {
				$session->set_message($admin["username"]." has been taken");
				redirect_to(SITE_ROOT);		
			}
		}
		
		//Check password
		if(!$this->hash_verify($password, $current_admin["hash"])) {
			return false;
		}
		
		//Save new username
		$om_admin->id = $current_admin["id"];
		$om_admin->username = $new_username;
		$om_admin->save();
		$session->set_message("Username changed to ".$new_username);
		
		$this->logout();
		$this->attempt_login($new_username, $password);
		if($this->is_logged_in()) {
			return true;
		}
		return false;
	}
	
	public function change_password($new_password, $old_password) {
		global $session;
		
		if(!$this->is_logged_in()) {return false;}
		
		if(strlen($new_password) < 5) {
			$session->set_message("New Password < 5");
			return false;
		}
		
		//Get current admin and all admin data
		$om_admin = new TableObject("om_admin");
		$om_admin->clear_filters();
		$admins = $om_admin->find_all();
		$current_admin = $om_admin->find_by("id", $_SESSION["admin_id"])[0];
		
		if(!$this->hash_verify($old_password, $current_admin["hash"])) {
			return false;
		}
		
		$om_admin->id = $current_admin["id"];
		$om_admin->hash = $this->password_encrypt($new_password);
		$om_admin->save();
		$session->set_message("Password Changed");
		
	}
} 


$Admin = new Admin();

?>