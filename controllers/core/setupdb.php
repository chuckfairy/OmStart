<?php require_once("../../omstart/initialize.php");
require_once("../../omstart/core/setup.php");

//Set up om_admin, om_config and om_media
$SETUP = new OM_Setup();
$SETUP->init();
echo "Database setup complete";