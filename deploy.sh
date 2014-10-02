#!/bin/bash

#Get database Info
read -p "Database Type [mysql, sqlite3] " dbtype
read -p "Database name " dbname
read -p "Database Username " dbuser
read -p "Database password " -s dbpass
echo ""
read -p "Project Server [http://google.com] " pserver

echo ""

omconfig="
<?php
\ndefined('DB_TYPE')   ? null : define('DB_TYPE',   '$dbtype');
\ndefined('DB_SERVER') ? null : define('DB_SERVER', 'localhost');
\ndefined('DB_USER')   ? null : define('DB_USER',   '$dbuser');
\ndefined('DB_PASS')   ? null : define('DB_PASS',   '$dbpass');
\ndefined('DB_NAME')   ? null : define('DB_NAME',   '$dbname');
\ndefined('SITE_ROOT') ? NULL : define('SITE_ROOT', '$pserver'.DS);"

echo -e $omconfig > omstart/core/config.php

echo "Database config complete. Please visit public/om_admin/index.php to setup the OmStart admin CMS"