<?php 
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

	require ("config.inc.php");
	require ("../includes/common.inc.php");
	require ("../includes/db.inc.php");
	require ("includes/auth.inc.php");
	require ("../includes/init.inc.php");

	global $gekko_auth;
	
	$gekko_auth->logout();
	
	header("Location:/admin/index.php");
?>