<?php

class OM_Setup extends DatabaseObject {
	protected static $table_name;
	protected static $validations;
	protected static $filter = array();
	public static    $offset;
	public static    $limit=30;
	public static    $select=" * ";
	public static    $om_tables = [
		"om_config" => [
			"id" => [
				"type"  => "INT",
				"max"   => 11,
				"extra" => "NOT NULL PRIMARY KEY AUTO_INCREMENT"
			],
			
			"type" => [
				"type"  => "VARCHAR",
				"max"   => 255,
				"extra" => "NOT NULL"
			],
			
			"om_table_name" => [
				"type"  => "VARCHAR",
				"max"   => 255,
				"extra" => ""
			],
			
			"data" => [
				"type"  => "TEXT",
				"max"   => "",
				"extra" => ""
			],
		], 
		
		"om_admin" => [
			"id" => [
				"type"  => "INT",
				"max"   => 11,
				"extra" => "NOT NULL PRIMARY KEY AUTO_INCREMENT"
			],
			
			"username" => [
				"type"  => "VARCHAR",
				"max"   => 180,
				"extra" => "NOT NULL"
			],
			
			"hash" => [
				"type"  => "VARCHAR",
				"max"   => 180,
				"extra" => "NOT NULL"
			],
			
			"email" => [
				"type"  => "VARCHAR",
				"max"   => 180,
				"extra" => ""
			],
		],
		
		"om_media" => [
			"id" => [
				"type"  => "INT",
				"max"   => 11,
				"extra" => "NOT NULL PRIMARY KEY AUTO_INCREMENT"
			],
			
			"img_url" => [
				"type"  => "VARCHAR",
				"max"   => 255,
				"extra" => "NOT NULL"
			],
			
			"title" => [
				"type"  => "VARCHAR",
				"max"   => 50,
				"extra" => ""
			],
			
			"description" => [
				"type"  => "VARCHAR",
				"max"   => 255,
				"extra" => ""
			],
		]
	];


	public function init() {
		$this->setup_tables();
		if($this->check_tables()) {
			$this->om_media_setup();
		}
	}
	
	private function setup_tables() {
		global $database;
		foreach($this::$om_tables as $table_name => $table_data) {
			if($this->validate_table($table_name)) {
				self::drop_table($table_name);
				$database->reload();
			}
			$ct = self::create_table($table_name, $table_data);
		}
		
		global $database;
		$database->reload();	
	}
	
	private function check_tables() {
		foreach($this::$om_tables as $table_name => $table_data) {
			if(!$this->validate_table($table_name)) {
				$session->set_message($table_name." Failed to create");
				return false;
			}	
		}
		return true;
	}
	
	private function om_media_setup() {
		$om_config = new TableObject("om_config");
		$om_config->type = "media";
		$om_config->om_table_name = "om_media";
		$om_config->data = "om_admin/_assets/";
		$om_config->save();
		
		$om_media = new TableObject("om_media");
		$om_media->clear_filters();
		$dh = opendir(LOCAL_ROOT."om_admin".DS."_assets");
		while(false !== ($filename = readdir($dh))) {
			if(preg_match("/^\.*$/i", trim($filename))) {continue;}
			
			$om_media->img_url = $filename;
			$om_media->title = substr($filename, 0, (strlen($filename)-4));
			$om_media->save();
		}
	}
}