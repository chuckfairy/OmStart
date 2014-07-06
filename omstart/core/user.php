<?php
//require("databaseobject.php");

/*************************************************************USER******************************************************/

class User extends TableObject {
	
	protected static $salt_length=30;
	private $user_type="user";
	
	/*Functions*/
	
	private function id_check() {return $this->id;}
	public function set_id($id) {$this->id = $id;}

/*************************************************************USER CLASS******************************************************/

	public function usercheck() {	
		global $session;
		$username = $this->username;
		$found_user = self::find_by("username", $username);
		if(!empty($found_user)) {
			$session->set_message("This username ".$this->username." has been taken");
			return false;
		}	
		return true;
	}

/*************************************************************LOG-IN********************************************************/

	
	public function authenticate($username="", $password="") {
		global $session;
		$attr = array();
		
	    $attr["username"] = self::escape($username);
	    $attr["password"] = self::password_encrypt($password);
	    if($found_user = User::find_by_attributes($attr)) {
		    return $found_user;
	    } else {
		    $session->set_message("Username or password not found.");
	    }
	    
	}
	
	public function attempt_login($username="", $password="") {
		global $session;
		$username = self::escape($username);
		$password = self::escape($password);
		
		$found_user = self::find_by("username", $username);
		if(!empty($found_user)) {
			// Username exists check password
			$found_user = self::assoc($found_user);
			if(self::password_check($password, $found_user["password"])) {
				$session->login($found_user);
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
		

} 



?>