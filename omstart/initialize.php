<?php

//Omstart Initialization and installation file//
//Made with love Chuck//

defined("DS") ? null : define("DS", DIRECTORY_SEPARATOR);

//Get local root
$path = explode("/", dirname(__FILE__));
array_pop($path);
$_src = implode("/", $path);

//Url
defined("SRC") ? NULL : define("SRC", $_src.DS);
defined("LOCAL_ROOT") ? NULL : define("LOCAL_ROOT", SRC."public".DS);

//SERVER SYSTEM PATH
defined("LAYOUT") ? NULL : define("LAYOUT", LOCAL_ROOT."_layouts".DS);
defined("L_ASSETS") ? NULL : define("L_ASSETS", LOCAL_ROOT."_assets".DS);
defined("CONTROLLER") ? NULL : define("CONTROLLER", LOCAL_ROOT.DS."omstart".DS."controllers".DS);
defined("TRASH") ? NULL : define("TRASH", SRC.DS."db".DS."trash".DS);

//OMSTART CORE
require_once("core/config.php");
require_once("core/databaseobject.php");
require_once("core/functions.php");
require_once("core/session.php");
require_once("core/logger.php");
require_once("core/tableobject.php");
require_once("core/user.php");
require_once("core/cart.php");
require_once("core/admin.php");

//MAILER
require_once("libs".DS."PHPMailer".DS."class.phpmailer.php");
require_once("libs".DS."PHPMailer".DS."class.smtp.php");