<?php 
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

define ('GEKKO_VERSION','1.1.5a');

global $gekko_current_page, $gekko_db, $gekko_current_user, $gekko_current_admin_user,$gekko_config;
$gekko_current_page = $_SERVER['PHP_SELF'];
$gekko_db	= new Database(DB_HOST,DB_DATABASE, DB_USERNAME,DB_PASSWORD);
$gekko_config = new DynamicConfiguration('gk_config');
$gekko_errorlog = new ErrorLog('gk_error_items');

$gekko_db->query("SET SQL_MODE=''"); // fix most errors reported by microsoft tester
/*$gekko_db->query("SET CHARACTER_SET_CLIENT='utf8';");
$gekko_db->query("SET CHARACTER_SET_RESULTS='utf8';");
$gekko_db->query("SET CHARACTER_SET_CONNECTION='utf8';"); // fix most errors reported by microsoft tester*/
if (defined (SITE_OFFLINE) && SITE_OFFLINE==true )
{
	include('errors/site_offline.php');	
	die;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
 
?>