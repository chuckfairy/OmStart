<?php 
//CART
class Cart {
	
	public $items = array();
	public $total_price=0;
	public $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	private $checkout = false;
	
	function __construct() {
		global $session;
		if(isset($_SESSION["cart"])) {
			$this->items = $_SESSION["cart"]; 
			$this->add_prices();
		} else {
			$_SESSION["cart"] = array();
		}
	}
	
	public function add_item($id) {
		global $music_table;
		global $session;
		$id = escape($id);
		if($this->has_item($id)) {
			$session->set_message("Item is already in cart.");
			return false;
		}
		
		if($music_table->find_by_id($id)) {
			array_push($_SESSION["cart"], $id);
			return true; 
		} else {
			return false;
		}
	}
	
	public function remove_item($id) {
		global $session;
		print_r($_SESSION["cart"]);
		foreach($_SESSION["cart"] as $offset => $item) {
			if($item == $id) {
				unset($_SESSION["cart"][$offset]);
				return true;
			}
		}
		return false;
	}
	
	private function add_prices() {
		$music_object = new TableObject("music", ["title", "composer", "arranger", "publisher"]);
		global $database;
		
		foreach($this->items as $cart_id) {
			$music_data = $music_object->find_by_id($cart_id);
			while($cart_item = $database::assoc($music_data)) {
				$this->total_price = $this->total_price + $cart_item["price"];
			}
		}
		return true;
	}
	
	public function list_items($field="title") {
		global $music_table;
		$list=array();
		foreach($this->items as $cart_id) {
			$music_data = $music_table->find_by_id($cart_id);
			while($cart_item = $music_table::assoc($music_data)) {
				array_push($list, $cart_item[$field]);
			}
		}
		return $list;
	}
	
	public function total_items() {
		$i = 0;
		foreach($_SESSION["cart"] as $item) {$i++;}
		return $i;
	}
	
	public function has_item($id) {
		foreach($_SESSION["cart"] as $key => $value) {
			if($value == $id) {return true;}
		}
		return false;
	}
	
	public function empty_cart() {
		unset($this->items, $_SESSION["cart"]);
		return true;
	}
	
	public function save_purchases() {
		foreach($this->items as $item) {
			$this->item_id = $item;
			$this->save();
		}
		return true;
	}
	
/*************************************************************PAYPAL******************************************************/
	
	public function paypal_buy() {
		global $music_table;
		$i = 1;
		foreach($this->items as $cart_id) {
			$music_data = $music_table->find_by_id($cart_id);
			while($cart_item = $music_table::assoc($music_data)) {
				echo "<input type='hidden' name='item_name_{$i}' value='{$cart_item['title']}'>";
				echo "<input type='hidden' name='amount_{$i}' value='{$cart_item['price']}'>";
				$i++;
			}
		}
	}
	
	public function ipn() {
	
		$this->post_fields = "cmd=_notify-validate";
	
		foreach($_POST as $key => $value) {
			$this->post_fields .="&{$key}=".urlencode($value);
		}
		
		echo !isset($_POST["payer_status"])? "Payer gotta pay<br/>": null;
	
		$ch = curl_init();
		
		curl_setopt_array($ch, array(
			CURLOPT_URL => $this->paypal_url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $this->post_fields
		));
		
		$result = curl_exec($ch);
		curl_close($ch);
		
		/*
		if($_POST["payer_status"] == "verified") {
			echo "HE IS GOOD";
			$this->checkout = true;
			return true;
		}
		*/		
		
		return $result;
	}
}

$user_cart = new Cart();


?>