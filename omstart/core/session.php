<?php

/*************************************************************SESSION******************************************************/

class Session {
	
	private $logged_in=false;
	private $login_validations = array("username", "password");
	private $user_id;
	private $admin;
	public $username;
	public $errors;
	public $last_page;
	//public static $message = [];
	public $messages = array();
	public $time;
	
	function __construct() {
		session_start();
		$this->check_messages();
		$this->check_page();
		$this->time = date("M-d-Y/g:i:s");
		$this->messages = array();
		$this->check_login();
	}
	
	public function output_messages($tag) {
		if(empty($this->messages)) {return false;}
		$tag = htmlentities($tag).">";
		foreach($this->messages as $key => $value) {
			echo "<".$tag.$value."</".$tag;
		}
		unset($this->messages, $_SESSION["messages"]);
	}
	
	public function set_message($message) {
		//We are going to append the messages array and join them into a string
		//So we can use it as one string that we can then split. 
		array_push($this->messages, $message);
		$new_messages = join("|", $this->messages);
		$_SESSION["messages"] = $new_messages;
	}
	
	private function check_messages() {
		if(!empty($_SESSION["messages"])) {
			preg_match("/|/i", $_SESSION["messages"]) ? 
				$this->messages = preg_split("/|/i", $_SESSION["messages"]): 
				$this->messages = $_SESSION["messages"];
				
			unset($_SESSION["messages"]);
			return true;
		} else {
			return false;
		}
	}
	
	
/*************************************************************SESSION INFO**************************************************/
	
	public function login($user, $type="user") {
		// $user is an assoc array with id, username.
		if(isset($user["id"], $user["username"])) {
			$_SESSION["user_id"] =	$user["id"];
			$_SESSION["username"] = $user["username"];
			if($this->check_login()) {
				$this->set_message("{$_SESSION['username']} is now logged in!");
				return true;
			} else {
				$this->set_message("Failed logging in.");
				$this->log_out();
				return false;
			}
			
		} else { $this->set_message("Data is empty."); }
	}
	
	public function check_login() {
	    if(isset($_SESSION["user_id"], $_SESSION["username"])) {
			$this->user_id = $_SESSION["user_id"];
			$this->username = $_SESSION["username"];
			return $this->logged_in = true;
		} else {
			unset($this->user_id);
			return $this->logged_in = false;
		}
	}
	
	public function log_out($page_redirect=SITE_HOME) {
		if($this->check_login()) {
			unset($_SESSION["user_id"]);
			unset($_SESSION["username"]);
			if(valid_page($page_redirect)) {
				redirect_to($page_redirect);
			}
		}
	}
	
	public function set_last_page() {
		$_SESSION["last_page"] = $_SERVER["REQUEST_URI"];
		$this->last_page = $_SESSION["last_page"];
		return $_SESSION["last_page"];
	}
	
	public function check_page() {
		isset($_SESSION["last_page"])? $this->last_page = $_SESSION["last_page"] : null;
	}
	
	private function admin_login() {
		$session->admin = true;
	}
	

/*************************************************************ERROR METHODS**************************************************/

	public function set_error($message) {
		$message = strval($message);
		array_pusher($this->errors, $message);
		$this->set_message($message);
		$_SESSION["errors"] = $this->errors;
	}
	
}

$session = new Session();
$session->login_validation = ["username", "password", "email"];

?>














