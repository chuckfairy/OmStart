<?php 
//require("intialize.php");

class Logger {
	private $file;
	private $file_path;

	public function set_file($file_path) {
	
		//Create new log file if non-existant
		if(!file_exists($file_path)) {
			$log_handle = fopen($file_path, "w");
			$timestamp = "Log started:".date("d-m-Y")."\n"; 
			fwrite($log_handle, $timestamp);
			$this->file_path = $file_path;
			$this->file = basename($this->file_path); 
			fclose($log_handle);
			
		//Set data of set log file
		} else {
			$this->file_path = $file_path;
			$this->file = basename($this->file_path);
		}
	}
	
	private function check_file($permission="r") {
		return file_exists($this->file_path) ? true : false;
	}
	
	public function file_log($log_message) {
		
		global $session;
		if($this->check_file("w")) {
			$log_handle = fopen($this->file_path, "a");
			if(!empty($log_message) && is_string($log_message)) {
				fwrite($log_handle, "\n****\n".$log_message);
				fclose($log_handle);
				$session->set_message("New log {$log_message} has been created");
				return true;
			} else {
				$session->set_message("Log Message empty or is not a string.");
				return false;
			}	
			
		} else {
			$session->set_message("You don't have permissions for this file.");
		}	
	}

	public function output_html($tag_name) {
		if(file_exists($this->file_path)) {
			$file_handle = fopen($this->file_path, "r");
			$tag = htmlentities($tag_name).">";
			$line_number = 0;
			
			while(!feof($file_handle)) { //end of file
				$content = fgets($file_handle);
				$delete_url = SITE_HOME."?logdelete=".$line_number;
				echo "<".$tag;
				echo "<a href='{$delete_url}' class='deleteButton'>X</a>";
				echo $content;
				echo "</".$tag;
				$line_number++;
			}
		}
	}
	
	public function delete_log_line($line=NULL) {
		
		global $session;
		echo "line number {$line}<br/>";
		if($this->check_file("r") && $line!=NULL) {
			$file_handle = fopen($this->file_path, "r");
			$line_number = 0;
			if(!feof($file_handle)) {
				if($line_number == $line) {
					$log_info = fgets($file_handle);
					$session->set_message($log_info);
					fclose($file_handle);
					return $log_info;
				}
				fgets($file_handle);
				echo $line_number;
				$line_number++;
			} else {
				$session->set_message("Line number not in file.");
			}
		}
	}
	

}


//$admin_logs = new Logger();
//$admin_logs->set_file(SITE_ROOT.DS."_logs".DS."admin_logs.txt");

?>