<?php

//Omstart Initialization and installation file//
//Made with love Chuck//
defined("DS") ? null : define("DS", DIRECTORY_SEPARATOR);



//Local Root
defined("LOCAL_ROOT") ? NULL : define("LOCAL_ROOT", "/Users/charlesabeling/Sites/omstart-v1/public/");
//Url


chdir(LOCAL_ROOT.DS."../");
$site_src = getcwd();
defined("SITE_SRC") ? NULL : define("SITE_SRC", $site_src.DS);


//print_r($_SERVER);
$_source_location = "http://lolita.local/~charlesabeling/omstart-v1";

//FILE SYSTEM PATH
defined("SITE_ROOT") ? NULL: define("SITE_ROOT", $_source_location.DS."public");
//defined("SRC") ?   NULL: define("SRC", $_source_location.DS."private");
//defined("LIB_PATH") ?  NULL: define("LIB_PATH", SRC.DS."omstart".DS);	

defined("LAYOUT") ? NULL : define("LAYOUT", SITE_ROOT.DS."_layout".DS);
defined("ASSETS") ? NULL : define("ASSETS", SITE_ROOT.DS."_assets".DS);
defined("BLOG") ? NULL : define("BLOG", SITE_ROOT.DS."blog".DS);
defined("OMADMIN")? NULL : define("OMADMIN", LOCAL_ROOT.DS."om_admin".DS);

//OMSTART CORE

require_once("core/config.php");
require_once("core/databaseobject.php");
require_once("core/functions.php");
require_once("core/session.php");
require_once("core/logger.php");
require_once("core/tableobject.php");
require_once("core/user.php");
require_once("core/cart.php");
require_once("core/admin/admin.php");

//require_once("comment.php");

//MAILER
require_once("libs".DS."PHPMailer".DS."class.phpmailer.php");
require_once("libs".DS."PHPMailer".DS."class.smtp.php");

//PDF
require_once("libs".DS."fpdf.php");
require_once("libs".DS."fpdi".DS."fpdi.php");
//require_once("libs".DS."watermark".DS."watermark.php");

//ComposerExcel
require_once("vendor/autoload.php");



/*
$smtp = new PHPMailer();
$smtp->isSMTP();
$smtp->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
$smtp->SMTPAuth = true;  // authentication enabled
$smtp->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
$smtp->Host = "smtp.gmail.com";
$smtp->Port = 465;
$smtp->Username = GUSER;
$smtp->Password = GPASS;
*/




?>