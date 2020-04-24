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
#  init.php                                                                                 #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Initialization of the DBHcms core                                                        #
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
# $Id: init.php 70 2007-09-20 05:24:27Z drbenhur $                                           #
#############################################################################################

	#error_reporting(E_ALL & ~E_NOTICE);

#############################################################################################
#  SECURITY                                                                                 #
#############################################################################################

	if ((realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))||(!defined('DBHCMS'))) {
		die('	<div style="color: #872626; font-weight: bold;">
						DBHCMS FATAL ERROR - Access denied!
					</div>');
	}

#############################################################################################
#  CHECK CONFIGURATION FOR INITIALIZATION                                                   #
#############################################################################################

	if (
		   (!isset($GLOBALS['dbhcms_core_dir']))
		|| (!isset($GLOBALS['dbhcms_installed']))
		|| (!isset($GLOBALS['dbhcms_db_server']))
		|| (!isset($GLOBALS['dbhcms_db_database']))
		|| (!isset($GLOBALS['dbhcms_db_user']))
		|| (!isset($GLOBALS['dbhcms_db_pass']))
		|| (!isset($GLOBALS['dbhcms_db_prefix']))
	   )
	{
		die('	<div style="color: #872626; font-weight: bold;">
						FATAL ERROR - Configuration to initialize the DBHcms is not complete. 
						Please verify if the "config.php" is loaded and contains all necesary 
						parameters.
			 		</div>');
	}


#############################################################################################
#  SET NEW PATH IF EXTERNAL                                                                 #
#############################################################################################

	if (defined('DBHCMS_EXTERNAL')) {
		$GLOBALS['dbhcms_core_dir'] = DBHCMS_EXTERNAL_DTR;
	}

#############################################################################################
#  INIT VARIABLES AND CONSTANTS                                                             #
#############################################################################################

	$inc_file = $GLOBALS['dbhcms_core_dir'].'vars.php';
	if (is_file($inc_file)) {
		require_once($inc_file);
	} else {
		die('<div style="color: #872626; font-weight: bold;">
					FATAL ERROR - Could not find the initialization file "vars.php". Please check the 
					"$GLOBALS[\'dbhcms_core_dir\']" parameter in the "config.php" and make shure the directory 
					is correct.
				</div>');
	}

	# Set database configuration
	$GLOBALS['DBHCMS']['CONFIG']['DB']['server'] 		= $GLOBALS['dbhcms_db_server'];
	$GLOBALS['DBHCMS']['CONFIG']['DB']['user'] 			= $GLOBALS['dbhcms_db_user'];
	$GLOBALS['DBHCMS']['CONFIG']['DB']['passwd'] 		= $GLOBALS['dbhcms_db_pass'];
	$GLOBALS['DBHCMS']['CONFIG']['DB']['database'] 		= $GLOBALS['dbhcms_db_database'];
	$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'] 		= $GLOBALS['dbhcms_db_prefix'];

	# Set core directory
	$GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'] = $GLOBALS['dbhcms_core_dir'];

#############################################################################################
#  LOAD FUNCTIONS                                                                           #
#############################################################################################

	$inc_file = $GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'].'func.php';
	if (is_file($inc_file)) {
		require_once($inc_file);
	} else {
		die('<div style="color: #872626; font-weight: bold;">
					FATAL ERROR - Could not find the file "func.php". Please check the 
					"$GLOBALS[\'dbhcms_core_dir\']" parameter in the "config.php" and make shure the directory 
					is correct.
				</div>');
	}

#############################################################################################
#  DBHCMS INSTALLATION                                                                      #
#############################################################################################

	if (!$GLOBALS['dbhcms_installed']) {
		# installation procedure
		dbhcms_p_require_file($GLOBALS['dbhcms_core_dir'].'inst.php', 'inst', 0.1);
	}

#############################################################################################
#  DELETE UNKNOWN GLOBALS                                                                   #
#############################################################################################

	dbhcms_p_del_globals();

#############################################################################################
#  START SCRIPT DURATION                                                                    #
#############################################################################################

	$GLOBALS['DBHCMS']['TEMP']['scriptStartTime'] = dbhcms_f_getmicrotime();

#############################################################################################
#	ESTANBLISH DATABASE CONNECTION                                                            #
#############################################################################################

	if (mysql_connect($GLOBALS['DBHCMS']['CONFIG']['DB']['server'], $GLOBALS['DBHCMS']['CONFIG']['DB']['user'], $GLOBALS['DBHCMS']['CONFIG']['DB']['passwd']) == false) {
		dbhcms_p_error('Could not connect to server "'.$GLOBALS['DBHCMS']['CONFIG']['DB']['server'].'"', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
	} else {
		if (mysql_select_db($GLOBALS['DBHCMS']['CONFIG']['DB']['database']) == false ) {
			dbhcms_p_error('Could not select database "'.$GLOBALS['DBHCMS']['CONFIG']['DB']['database'].'"', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		} else {
			# define mysql as connected
			define('DBHCMS_MYSQL_CONNECTED', true);
		}
	}

#############################################################################################
#  LOAD TYPES                                                                               #
#############################################################################################

	dbhcms_p_require_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'].'types.php', 'types', 0.1);

#############################################################################################
#  LOAD CORE CONFIGURATION                                                                  #
#############################################################################################

	dbhcms_p_require_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'].'ccfg.php', 'ccfg', 0.1);

#############################################################################################
#  LOAD GLOBAL SETTING                                                                      #
#############################################################################################

	dbhcms_p_require_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'].'gcfg.php', 'gcfg', 0.1);

#############################################################################################
#  LOAD DOMAIN                                                                              #
#############################################################################################

	dbhcms_p_require_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'].'dcfg.php', 'dcfg', 0.1);

#############################################################################################
#  SESSION                                                                                  #
#############################################################################################

	dbhcms_p_require_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'].'sess.php', 'sess', 0.1);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>