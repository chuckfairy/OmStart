<?php
//require("databaseobject.php");

/*********************************USER******************************************/

class User extends DatabaseObject {
	
	protected static $salt_length=30;
	protected static $table_name="users";
	protected static $filter = array();
	public static $limit = 30;
	public static $select = " * ";
	public static    $join;
	public static    $join_by;
	public $id;
	public $username;
	public $email;
	public $badcharacters = [" ", "%", "$", "#", "'", '"', "."];
		

/****************************************USER CLASS***********************/

	public function usercheck($username) {	
		$found_user = static::find_by("username", $username);
		
		if(!empty($found_user)) {
			global $session;
			$session->set_message("This username {$username} has been taken");
			return false;
		}	
		return true;
	}
	
	private function validate($data = ["username" => "", "password" => "", "email" => ""]) {
		
		global $session;
		//Username originality check
		if(!$this->usercheck($data["username"])) {
			$session->set_message($username. " is taken.");
			return false;
		}
		//Username bad characters
		foreach($this->badcharacters as $character) {
			if(strpos($data["username"], $character)) {
				if($character === " ") {$character = "Spaces";}
				$session->set_message($character. " not allowed in username");
				return false;
			}
		}
		
		//shortness test
		if(strlen($data["username"]) < 3){
			$session->set_message("username must be > 3");
			return false;
		}
		if(strlen($data["password"]) < 7) {
			$session->set_message("Password must be > 7");
			return false;
		} 
		
		//Email validation
		if(!validate_email($data["email"])) {return false;}
		
		return true;
	}

/*********************************LOG-IN********************************/
	
	public function attempt_login($username="", $password="") {
		global $session;
		
		$found_user = static::find_by("username", $username);
		if(!empty($found_user)) {
			// Username exists check password
			if($this->hash_verify($password, $found_user[0]["hashed_password"])) {
				$session->login($found_user[0]);
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
	
	public function create_user($username, $password, $email) {
		if($this->validate([
			"username"=>$username, 
			"password" => $password, 
			"email" => $email
		])) {
			$query = array();
			
			$query["username"] = $username;
			$query["hashed_password"] = $this->password_encrypt($password);
			$query["email"] = $email;
			self::create($query); 
			if(!empty(static::find_by("username", $username))) {
				global $session;
				$session->set_message("User created successfully");
				return true;
			}
		}
		return false;
	}		

/****************************Joins****************************/

	public function bio($user_id) {
		$user_bio = new TableObject("user_bio");
		$user_bio->clear_filters();
		$user_bio->join_clear();
		$user_bio::$order = null;
		$found_bio = $user_bio::find_by("id", $user_id);
		
		if(!isset($found_bio[0]["bio"])) {
			$found_bio[0]["bio"] = "N/A";
		}

		return htmlspecialchars_decode($found_bio[0]["bio"]);
	}
	
	public function username($user_id) {
		$this->clear_filters();
		$this->select(["username"]);		
		$username = static::find_by("id", $user_id);
		if(isset($username[0], $username[0]["username"])) {
			return $username[0]["username"];
		}
	}
	
/****************************Password****************************/

	private function password_encrypt($password) {
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
	
/****************************Delete****************************/

	public function delete_account($password=null) {
		global $session;
		$this->clear_filters();
		$this->join_clear();
		$current_user = $this::find_by("id", $_SESSION["user_id"])[0];
		if($this->hash_verify($password, $current_user["hashed_password"])) {
			
			$dirHandle = opendir(L_ASSETS."usr".DS.$current_user["username"].DS);
			if(!$dirHandle) {return false;}
			while(false !== ($filename = readdir($dirHandle))) {
				if($filename !== ".." &&
				   $filename !== ".DS_Store" &&
				   $filename !== "."
				 ) {
					echo $filename."<br/>";
					unlink(L_ASSETS."usr".DS.$current_user["username"].DS.$filename);
				}
			}
			rmdir(L_ASSETS."usr".DS.$current_user["username"].DS);
			static::delete($_SESSION["user_id"]);
			$session->set_message("User Deleted. Good-bye");
		} 
	}

} 

