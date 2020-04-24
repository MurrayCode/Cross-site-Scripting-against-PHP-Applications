<?php

#############################################################################################
#                                                                                           #
#  DBHCMS - Web Content Management System                                                   #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  COPYRIGHT NOTICE                                                                         #
#  =============================                                                            #
#                                                                                           #
#  Copyright (C) 2005-2007 Kai-Sven Bunk (kaisven@drbenhur.com)                             #
#  All rights reserved                                                                      #
#                                                                                           #
#  This file is part of DBHcms.                                                             #
#                                                                                           #
#  DBHcms is free software; you can redistribute it and/or modify it under the terms of     #
#  the GNU General Public License as published by the Free Software Foundation; either      #
#  version 2 of the License, or (at your option) any later version.                         #
#                                                                                           #
#  The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html      #
#  A copy is found in the textfile GPL.TXT                                                  #
#                                                                                           #
#  DBHcms is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;      #
#  without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR         #
#  PURPOSE. See the GNU General Public License for more details.                            #
#                                                                                           #
#  This copyright notice MUST APPEAR in ALL copies of the script!                           #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  FILENAME                                                                                 #
#  =============================                                                            #
#  apauth.php                                                                               #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Handles the authentication for applications embedded in DBHcms such as phpMyAdmin.       #
#  This file is included in the configuration file of each application to handle the        #
#  authentication by DBHcms. Before the include a variable "$dbhcms_app_authlevel" must be  #
#  declared to define the type of authentication.                                           #
#                                                                                           #
#  The options for $dbhcms_app_authlevel are:                                               #
#                                                                                           #
#  trust          :  Anyone can access the application. No login required.                  #
#  superuser      :  Just the administrator of DBHcms can acess the application             #
#  A to Z, 0 to 9 :  Defines the level which the user must have to access the application.  #
#                    Example: 'F' All users with level 'F' can access                       #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  CHANGES                                                                                  #
#  =============================                                                            #
#                                                                                           #
#  28.10.2005:                                                                              #
#  -----------                                                                              #
#  File created                                                                             #
#                                                                                           #
#############################################################################################
# $Id: apps.php 59 2007-02-01 13:05:33Z kaisven $                                           #
#############################################################################################

#############################################################################################
#  LOAD CONFIG                                                                              #
#############################################################################################

	$bckdir = '';
	$bckcnt = 0;
	while (!is_file($bckdir.'dbhcms.dat')) {
		if ($bckcnt > 4) {
			die('<div style="color: #872626; font-weight: bold;">
					FATAL ERROR - Could find root directory of the DBHcms. Access denied.!
				 </div>');
		} else {
			$bckdir .= '../'; 
			$bckcnt++;
		}
	}
	if (is_file($bckdir.'config.php')) {
		include($bckdir.'config.php');
	} else {
		die('<div style="color: #872626; font-weight: bold;">
				FATAL ERROR - Could find main configuration of the DBHcms. Access denied.!
			 </div>');
	}

#############################################################################################
#  SECURITY                                                                                 #
#############################################################################################

	if ((realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))||(!defined('DBHCMS'))) {
		die('	<div style="color: #872626; font-weight: bold;">
						DBHCMS FATAL ERROR - Access denied!
					</div>');
	}

#############################################################################################
#  INITIALIZE DBHCMS                                                                        #
#############################################################################################

	define('DBHCMS_EXTERNAL', true);
	define('DBHCMS_EXTERNAL_DTR', $bckdir.$dbhcms_core_dir);

	include($bckdir.$dbhcms_core_dir.'init.php');

#############################################################################################
#  PROGRAMM AUTHENTICATION                                                                  #
#############################################################################################

	if (isset($dbhcms_app_authlevel)) {
		if ($dbhcms_app_authlevel != 'trust') {
			if ($dbhcms_app_authlevel == 'superuser') {
				if (!dbhcms_f_superuser_auth()) {  
					dbhcms_p_error('Access denied!', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
				}
				$result = mysql_query("SELECT * FROM `".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."cms_users` WHERE `user_login` LIKE '".$_SESSION['DBHCMSDATA']['AUTH']['userName']."'");
				if ($row = mysql_fetch_array($result)) {
					if ($_SESSION['DBHCMSDATA']['AUTH']['password'] != $row['user_passwd']) { dbhcms_p_error('Access denied!', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__); }
				} else { dbhcms_p_error('Access denied!', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__); }
			} else {
				$result = mysql_query("SELECT * FROM `".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."cms_users` WHERE `user_login` LIKE '".$_SESSION['DBHCMSDATA']['AUTH']['userName']."'");
				if ($row = mysql_fetch_array($result)) {
					if ($_SESSION['DBHCMSDATA']['AUTH']['password'] != $row['user_passwd']) { dbhcms_p_error('Access denied!', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__); }
					if (substr_count($row['user_level'] , $dbhcms_app_authlevel) == 0) { dbhcms_p_error('Access denied!', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__); }
				} else { dbhcms_p_error('Access denied!', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__); }
				mysql_close();
			}
		}
		unset($dbhcms_app_authlevel);
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>