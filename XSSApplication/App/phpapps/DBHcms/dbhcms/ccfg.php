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
#  ccfg.php                                                                                 #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Core configuration for DBHcms                                                            #
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
# $Id: ccfg.php 68 2007-05-31 20:28:17Z kaisven $                                           #
#############################################################################################

#############################################################################################
#  SECURITY                                                                                 #
#############################################################################################

	if ((realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))||(!defined('DBHCMS'))) {
		die('	<div style="color: #872626; font-weight: bold;">
						DBHCMS FATAL ERROR - Access denied!
					</div>');
	}

#############################################################################################
#  REGISTER FILE                                                                            #
#############################################################################################

	dbhcms_p_register_file(realpath(__FILE__), 'ccfg', 0.1);

#############################################################################################
#  CORE VERSION                                                                             #
#############################################################################################

	# DBHCMS core version
	$GLOBALS['DBHCMS']['CONFIG']['CORE']['version'] = '1.3';
	# Debug the DBHcms Core
	$GLOBALS['DBHCMS']['CONFIG']['CORE']['debug'] = false;

#############################################################################################
#  CORE DIRECTORIES                                                                         #
#############################################################################################

	# tool applications directory
	$GLOBALS['DBHCMS']['CONFIG']['CORE']['appsDirectory'] = $GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'].'apps/';
	# css style directory
	$GLOBALS['DBHCMS']['CONFIG']['CORE']['cssDirectory'] = $GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'].'css/';
	# images directory
	$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'] = $GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'].'img/';
	# includes directory
	$GLOBALS['DBHCMS']['CONFIG']['CORE']['incDirectory'] = $GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'].'inc/';
	# java directory
	$GLOBALS['DBHCMS']['CONFIG']['CORE']['javaDirectory'] = $GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'].'js/';
	# libraries directory
	$GLOBALS['DBHCMS']['CONFIG']['CORE']['libDirectory'] = $GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'].'lib/';
	# modules directory
	$GLOBALS['DBHCMS']['CONFIG']['CORE']['moduleDirectory'] = $GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'].'mod/';
	# templates directory
	$GLOBALS['DBHCMS']['CONFIG']['CORE']['templateDirectory'] = $GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'].'tpl/';
	# extension directory
	$GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'] = $GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'].'ext/';
	# temp directory
	$GLOBALS['DBHCMS']['CONFIG']['CORE']['tempDirectory'] = $GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'].'temp/';

#############################################################################################
#  CORE LANGUAGE SETTINGS                                                                   #
#############################################################################################

	# Supported languges
	$GLOBALS['DBHCMS']['CONFIG']['CORE']['supportedLangs'] = array('en', 'es', 'de');

	# default language
	$GLOBALS['DBHCMS']['CONFIG']['CORE']['defaultLang'] = 'en';

#############################################################################################
#  CORE STANDARD PAGE_ID'S SETTINGS                                                         #
#############################################################################################

	# Page-ID to go for Log-In
	$GLOBALS['DBHCMS']['CONFIG']['CORE']['indexPageId'] 	= -1;
	$GLOBALS['DBHCMS']['CONFIG']['CORE']['loginPageId'] 	= -4;
	$GLOBALS['DBHCMS']['CONFIG']['CORE']['logoutPageId'] 	= -4;
	$GLOBALS['DBHCMS']['CONFIG']['CORE']['extPageId'] 		= -100;

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>