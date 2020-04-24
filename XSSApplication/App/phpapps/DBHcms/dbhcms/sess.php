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
#  sess.php                                                                                 #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Session handling                                                                         #
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
# $Id: sess.php 68 2007-05-31 20:28:17Z kaisven $                                           #
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

	dbhcms_p_register_file(realpath(__FILE__), 'sess', 0.1);

#############################################################################################
#  START SESSION                                                                            #
#############################################################################################

	if (!session_id()) session_start();

#############################################################################################
#  DEACTIVATE AND STOP OLD SESSIONS                                                         #
#############################################################################################

	# Kill expired sessions
	mysql_query("	UPDATE 
						".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_SESSIONS." 
					SET 
						`sess_user` 	= '', 
						`sess_stop` 	= NOW(), 
						`sess_active` 	= 'N', 
						`sess_dead` 	= 'Y'
					WHERE 
							((UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(`sess_update`))/60) > ".$GLOBALS['DBHCMS']['CONFIG']['PARAMS']['sessionLifeTime']." 
						AND 
							`sess_dead` LIKE 'N'
				");

	# Deactivate idle session
	mysql_query("	UPDATE 
						".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_SESSIONS." 
					SET 
						`sess_active` 	= 'N'
					WHERE 
							((UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(`sess_update`))/60) > ".$GLOBALS['DBHCMS']['CONFIG']['PARAMS']['sessionActiveTime']." 
						AND 
							`sess_active` LIKE 'Y'
				");

	# Delete old sessions
	mysql_query("	DELETE FROM 
						".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_SESSIONS." 
					WHERE 
							(UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(`sess_update`)) > 10
						AND 
							`sess_dead` LIKE 'Y'
				");

#############################################################################################
#	START SESSION AND REGISTER VARIABLES                                                      #
#############################################################################################

	if (!isset($_SESSION['DBHCMSDATA'])) {
		
		# memorize session_id
		$_SESSION['DBHCMSDATA']['SID'] = session_id();
		
		# get origin and languages
		$origin = dbhcms_f_get_origin();
		$language = dbhcms_f_get_language();
		
		# memorize origin
		$_SESSION['DBHCMSDATA']['STAT']['origin']		= $origin['origin'];
		$_SESSION['DBHCMSDATA']['STAT']['searchEngine']	= $origin['searchEngine'];
		$_SESSION['DBHCMSDATA']['STAT']['searchPhrase']	= $origin['searchPhrase'];
		$_SESSION['DBHCMSDATA']['STAT']['navHistory'] 	= array();
		
		# memorize initial languages
		$_SESSION['DBHCMSDATA']['LANG']['coreUseLanguage'] 	= $language['coreUseLanguage'];
		$_SESSION['DBHCMSDATA']['LANG']['useLanguage'] 		= $language['useLanguage'];
		$_SESSION['DBHCMSDATA']['LANG']['all'] 				= $language['all'];
		
		unset($origin, $language);
		
		# some directories
		$_SESSION['DBHCMSDATA']['CFG']['absoluteUrl'] 	=  $GLOBALS['DBHCMS']['DOMAIN']['absoluteUrl'];
		$_SESSION['DBHCMSDATA']['CFG']['appsDirectory']	=  $GLOBALS['DBHCMS']['CONFIG']['CORE']['appsDirectory'];
		$_SESSION['DBHCMSDATA']['CFG']['coreDirectory']	=  $GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'];
		
		# reset authentication
		dbhcms_p_reset_authentication();
		
		# create session
		mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_SESSIONS." (`sess_id`, `sess_start`, `sess_update`) VALUES ('".$_SESSION['DBHCMSDATA']['SID']."', NOW(), NOW() )");
		
	} else { 
		# refresh session lifetime
		mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_SESSIONS." SET `sess_update` = NOW(), `sess_stop` = '0000-00-00 00:00:00', `sess_active` = 'Y', `sess_dead` = 'N' WHERE `sess_id` = '".$_SESSION['DBHCMSDATA']['SID']."'");
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################