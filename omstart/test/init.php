<?php 

print_r(__FILE__);

require_once("../core/config.php");

try {
	$PDO = new PDO(DB_TYPE.":host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS,
		array(PDO::ATTR_PERSISTENT => true)
	);
} catch(PDOException $e) {
	echo $e->getMessage();
	exit;	
}

