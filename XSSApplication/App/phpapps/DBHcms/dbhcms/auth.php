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
#  auth.php                                                                                 #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Authentication                                                                           #
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
# $Id: auth.php 68 2007-05-31 20:28:17Z kaisven $                                           #
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

	dbhcms_p_register_file(realpath(__FILE__), 'auth', 0.1);

#############################################################################################
#  LOG OUT                                                                                  #
#############################################################################################

	if (($GLOBALS['DBHCMS']['PID'] == $GLOBALS['DBHCMS']['DOMAIN']['logoutPageId'])||($GLOBALS['DBHCMS']['PID'] == $GLOBALS['DBHCMS']['CONFIG']['CORE']['logoutPageId'])||isset($_POST['dbhcms_logout'])) {
		# log access - login
		mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_ACCESSLOG." (`aclg_sessionid` , `aclg_user` , `aclg_action` , `aclg_datetime` ) VALUES ( '".$_SESSION['DBHCMSDATA']['SID']."', '".$_SESSION['DBHCMSDATA']['AUTH']['userName']."', 'LOGOUT', NOW( ) ); ");
		# handle session
		mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_SESSIONS." SET `sess_user` = '', `sess_update` = NOW(), `sess_stop` = '1111-11-11 11:11:11' WHERE `sess_id` = '".$_SESSION['DBHCMSDATA']['SID']."'");
		# reset authentication
		dbhcms_p_reset_authentication();
	}

#############################################################################################
#  LOG IN                                                                                   #
#############################################################################################

	if (isset($_POST['dbhcms_user']) && isset($_POST['dbhcms_passwd'])) {
		$GLOBALS['DBHCMS']['RESULTS']['login'] = false;
		$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_USERS." WHERE `user_login` LIKE '".$_POST['dbhcms_user']."'");
		if ($row = mysql_fetch_array($result)) {
			
			if (in_array($GLOBALS['DBHCMS']['DID'], dbhcms_f_dbvalue_to_value($row['user_domains'], DBHCMS_C_DT_DOMAINARRAY))) {
			
				if (md5($_POST['dbhcms_passwd']) == dbhcms_f_dbvalue_to_value($row['user_passwd'], DBHCMS_C_DT_PASSWORD)) {
					
					# Result ok
					$GLOBALS['DBHCMS']['RESULTS']['login'] = true;
					
					# Register login
					$_SESSION['DBHCMSDATA']['AUTH']['userName'] 		=  dbhcms_f_dbvalue_to_value($row['user_login'], DBHCMS_C_DT_STRING);
					$_SESSION['DBHCMSDATA']['AUTH']['userId'] 			=  dbhcms_f_dbvalue_to_value($row['user_id'], DBHCMS_C_DT_INTEGER);
					$_SESSION['DBHCMSDATA']['AUTH']['password'] 		=  dbhcms_f_dbvalue_to_value($row['user_passwd'], DBHCMS_C_DT_PASSWORD);
					$_SESSION['DBHCMSDATA']['AUTH']['userRealName'] 	=  dbhcms_f_dbvalue_to_value($row['user_name'], DBHCMS_C_DT_STRING);
					$_SESSION['DBHCMSDATA']['AUTH']['userSex'] 			=  dbhcms_f_dbvalue_to_value($row['user_sex'], DBHCMS_C_DT_SEX);
					$_SESSION['DBHCMSDATA']['AUTH']['userCompany'] 		=  dbhcms_f_dbvalue_to_value($row['user_company'], DBHCMS_C_DT_STRING);
					$_SESSION['DBHCMSDATA']['AUTH']['userLocation'] 	=  dbhcms_f_dbvalue_to_value($row['user_location'], DBHCMS_C_DT_STRING);
					$_SESSION['DBHCMSDATA']['AUTH']['userEmail'] 		=  dbhcms_f_dbvalue_to_value($row['user_email'], DBHCMS_C_DT_STRING);
					$_SESSION['DBHCMSDATA']['AUTH']['userWebsite'] 		=  dbhcms_f_dbvalue_to_value($row['user_website'], DBHCMS_C_DT_STRING);
					$_SESSION['DBHCMSDATA']['AUTH']['userLang'] 		=  dbhcms_f_dbvalue_to_value($row['user_lang'], DBHCMS_C_DT_LANGUAGE);
					$_SESSION['DBHCMSDATA']['AUTH']['domains']			=  dbhcms_f_dbvalue_to_value($row['user_domains'], DBHCMS_C_DT_DOMAINARRAY);
					$_SESSION['DBHCMSDATA']['AUTH']['userLevels'] 		=  dbhcms_f_dbvalue_to_value($row['user_level'], DBHCMS_C_DT_ULARRAY);
					
					# Is authenticated
					$_SESSION['DBHCMSDATA']['AUTH']['authenticated']	=  true;
					
					# Log access - login
					mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_ACCESSLOG." (`aclg_sessionid` , `aclg_user` , `aclg_action` , `aclg_datetime` ) VALUES ( '".$_SESSION['DBHCMSDATA']['SID']."', '".$_SESSION['DBHCMSDATA']['AUTH']['userName']."', 'LOGIN', NOW( ) ); ");
					# Handle session
					mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_SESSIONS." SET `sess_user` = '".$_SESSION['DBHCMSDATA']['AUTH']['userName']."', `sess_update` = NOW(), `sess_stop` = '1111-11-11 11:11:11' WHERE `sess_id` = '".$_SESSION['DBHCMSDATA']['SID']."'");
					
					# Change language for user if suported by domain
					if (in_array($_SESSION['DBHCMSDATA']['AUTH']['userLang'], $GLOBALS['DBHCMS']['DOMAIN']['supportedLangs'])) {
						$_SESSION['DBHCMSDATA']['LANG']['useLanguage'] = $_SESSION['DBHCMSDATA']['AUTH']['userLang'];
					}
					# Change language for BE if is superuser and if suported by BE
					if (dbhcms_f_superuser_auth()) {
						if (in_array($_SESSION['DBHCMSDATA']['AUTH']['userLang'], $GLOBALS['DBHCMS']['CONFIG']['CORE']['supportedLangs'])) {
							$_SESSION['DBHCMSDATA']['LANG']['coreUseLanguage'] = $_SESSION['DBHCMSDATA']['AUTH']['userLang'];
						}
					}
				} else {
					# Log access - Wrong password
					mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_ACCESSLOG." (`aclg_sessionid` , `aclg_user` , `aclg_action` , `aclg_datetime` ) VALUES ( '".$_SESSION['DBHCMSDATA']['SID']."', '".$_POST['dbhcms_user']."', 'WPWD', NOW( ) ); ");
				}
				
			} else {
				# Log access - Wrong domain
				mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_ACCESSLOG." (`aclg_sessionid` , `aclg_user` , `aclg_action` , `aclg_datetime` ) VALUES ( '".$_SESSION['DBHCMSDATA']['SID']."', '".$_POST['dbhcms_user']."', 'WDOMN', NOW( ) ); ");
			}
		} else {
			# Log access - Wrong user
			mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_ACCESSLOG." (`aclg_sessionid` , `aclg_user` , `aclg_action` , `aclg_datetime` ) VALUES ( '".$_SESSION['DBHCMSDATA']['SID']."', '".$_POST['dbhcms_user']."', 'WUSER', NOW( ) ); ");
		}
	}

#############################################################################################
#  CHECK AUTH WITH SESSIONS                                                                 #
#############################################################################################

	if ($_SESSION['DBHCMSDATA']['AUTH']['authenticated']) {
		$result = mysql_query("SELECT sess_user FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_SESSIONS." WHERE sess_id LIKE '".$_SESSION['DBHCMSDATA']['SID']."' ");
		if ($row = mysql_fetch_assoc($result)) {
			if ($row['sess_user'] == '') {
				# Reset authentication
				dbhcms_p_reset_authentication();
				if (!isset($GLOBALS['DBHCMS']['RESULTS']['login'])) {
					$GLOBALS['DBHCMS']['RESULTS']['sessionExpired'] = true;
				}
			}
		} else {
			# Reset authentication
			dbhcms_p_reset_authentication();
			$GLOBALS['DBHCMS']['RESULTS']['sessionExpired'] = true;
			# Create session
			mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_SESSIONS." (`sess_id`, `sess_start`, `sess_update`) VALUES ('".$_SESSION['DBHCMSDATA']['SID']."', NOW(), NOW() )");
		}
	}

#############################################################################################
#  CHECK ACCESS TO DOMAIN                                                                   #
#############################################################################################

	if ($_SESSION['DBHCMSDATA']['AUTH']['authenticated']) {
		if (!dbhcms_f_superuser_auth()) {
			if (!(in_array($GLOBALS['DBHCMS']['DID'], $_SESSION['DBHCMSDATA']['AUTH']['domains']))) {
				dbhcms_p_reset_authentication();
			}
		}
	}

#############################################################################################
#  CHECK ACCESS AND HIDE PAGES                                                              #
#############################################################################################

	# Superuser authentiction
	if ($GLOBALS['DBHCMS']['PID'] < 0 ) { # Admin-Pages
		if (!dbhcms_f_superuser_auth()) {
			$GLOBALS['DBHCMS']['PID'] = $GLOBALS['DBHCMS']['CONFIG']['CORE']['loginPageId'];
		}
	} else {
		# Authentication for restricted pages
		$row_userlevel = mysql_fetch_assoc(mysql_query("SELECT page_hide, page_schedule, page_start, page_stop, page_userlevel FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." WHERE `page_id` = ".$GLOBALS['DBHCMS']['PID'] ));
		# page hidden
		if (dbhcms_f_dbvalue_to_value($row_userlevel['page_hide'], DBHCMS_C_DT_BOOLEAN)) {
			header("HTTP/1.0 404 Not Found");
			dbhcms_p_error('Page ID "'.$GLOBALS['DBHCMS']['PID'].'" is hidden. Make shure there are no links to this page.', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			$GLOBALS['DBHCMS']['PID'] = $GLOBALS['DBHCMS']['DOMAIN']['err404PageId'];
		}
		if (dbhcms_f_dbvalue_to_value($row_userlevel['page_schedule'], DBHCMS_C_DT_BOOLEAN)) {
			# page start
			if (dbhcms_f_dbvalue_to_value($row_userlevel['page_start'], DBHCMS_C_DT_DATETIME) > mktime()) {
				header("HTTP/1.0 404 Not Found");
				dbhcms_p_error('Page ID "'.$GLOBALS['DBHCMS']['PID'].'" out of schedule. Make shure there are no links to this page.', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
				$GLOBALS['DBHCMS']['PID'] = $GLOBALS['DBHCMS']['DOMAIN']['err404PageId'];
			}
			# page stop
			if (dbhcms_f_dbvalue_to_value($row_userlevel['page_stop'], DBHCMS_C_DT_DATETIME) < mktime()) {
				header("HTTP/1.0 404 Not Found");
				dbhcms_p_error('Page ID "'.$GLOBALS['DBHCMS']['PID'].'" out of schedule. Make shure there are no links to this page.', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
				$GLOBALS['DBHCMS']['PID'] = $GLOBALS['DBHCMS']['DOMAIN']['err404PageId'];
			}
		}
		# page userlevel
		if (!in_array($row_userlevel['page_userlevel'], $_SESSION['DBHCMSDATA']['AUTH']['userLevels'])) {
			$GLOBALS['DBHCMS']['PID'] = $GLOBALS['DBHCMS']['DOMAIN']['accessDeniedPageId'];
		}
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>