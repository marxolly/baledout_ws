<?php
/*
--------------------------------------------------------------------------
 Define Application Configuration Constants
--------------------------------------------------------------------------
*/

define('BASE_DIR', str_replace("\\", "/", dirname(dirname(__DIR__))));
define('APP',  BASE_DIR . "/app/");
define('DOC_ROOT', BASE_DIR . "/public_html/");
define('IMAGES',   DOC_ROOT . "/images/");
define('STYLES',   DOC_ROOT . "/styles/");
define('UPLOADS',  DOC_ROOT. "/client_uploads/");

/*************************************************************************
* Is Site Live?
**************************************************************************/
define('SITE_LIVE', false);
/*************************************************************************
* Database Configuration
**************************************************************************/
define('DB_HOST', "localhost");
define('DB_NAME', "baledout_appdatabase");
define('DB_USER', "baledout_website");
define('DB_PASS', "]&Z[aukSAlBs");
define('DB_CHARSET', "utf8");
?>
