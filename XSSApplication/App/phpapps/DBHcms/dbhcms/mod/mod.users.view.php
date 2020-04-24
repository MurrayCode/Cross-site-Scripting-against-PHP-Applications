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
# $Id: mod.users.view.php 60 2007-02-01 13:34:54Z kaisven $                                 #
#############################################################################################

#############################################################################################
#  SECURITY                                                                                 #
#############################################################################################

	if ((realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))||(!defined('DBHCMS'))||(!dbhcms_f_superuser_auth())) {
		die('	<div style="color: #872626; font-weight: bold;">
						DBHCMS FATAL ERROR - Access denied!
					</div>');
	}

#############################################################################################
#	MODULE MOD.USERS.VIEW.PHP                                                                 #
#############################################################################################


	if (isset($_POST['dbhcms_save_user'])) {
		if ($_POST['dbhcms_save_user'] == 'new') {
			
			$action_result = '<div style="color: #076619; font-weight: bold;">'.dbhcms_f_dict('dbhcms_msg_settingssaved', true).'</div>';
			
			if (mysql_num_rows(mysql_query("SELECT user_login FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_USERS." WHERE UPPER(user_login) LIKE UPPER('".dbhcms_f_input_to_dbvalue('user_login', DBHCMS_C_DT_STRING)."')")) == 0) {
				
				mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_USERS." 
								(user_login, user_passwd, user_name, user_sex, user_company, user_location, user_email, user_website, user_lang, user_domains, user_level) VALUES
								(
									'".dbhcms_f_input_to_dbvalue('user_login', 		DBHCMS_C_DT_STRING)."',
									'".dbhcms_f_input_to_dbvalue('user_passwd', 	DBHCMS_C_DT_PASSWORD)."',
									'".dbhcms_f_input_to_dbvalue('user_name', 		DBHCMS_C_DT_STRING)."',
									'".dbhcms_f_input_to_dbvalue('user_sex', 		DBHCMS_C_DT_SEX)."',
									'".dbhcms_f_input_to_dbvalue('user_company', 	DBHCMS_C_DT_STRING)."',
									'".dbhcms_f_input_to_dbvalue('user_location', 	DBHCMS_C_DT_STRING)."',
									'".dbhcms_f_input_to_dbvalue('user_email', 		DBHCMS_C_DT_STRING)."',
									'".dbhcms_f_input_to_dbvalue('user_website', 	DBHCMS_C_DT_STRING)."',
									'".dbhcms_f_input_to_dbvalue('user_lang', 		DBHCMS_C_DT_LANGUAGE)."',
									'".dbhcms_f_input_to_dbvalue('user_domains', 	DBHCMS_C_DT_DOMAINARRAY)."',
									'".dbhcms_f_input_to_dbvalue('user_level', 		DBHCMS_C_DT_ULARRAY)."'
								)
							") or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - '.dbhcms_f_dict('dbhcms_msg_settingsnotsaved', true).'</div><strong>SQL Error: </strong>'.mysql_error();
				
			} else {
				$action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - User "'.dbhcms_f_input_to_dbvalue('user_login', DBHCMS_C_DT_STRING).'" allready exists.</div>';
			}
			
		} else {
			
			$action_result = '<div style="color: #076619; font-weight: bold;">'.dbhcms_f_dict('dbhcms_msg_settingssaved', true).'</div>';
			
			mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_USERS." SET
							
							user_passwd 	= '".dbhcms_f_input_to_dbvalue('user_passwd', 	DBHCMS_C_DT_PASSWORD)."',
							user_name 		= '".dbhcms_f_input_to_dbvalue('user_name', 	DBHCMS_C_DT_STRING)."',
							user_sex 		= '".dbhcms_f_input_to_dbvalue('user_sex', 		DBHCMS_C_DT_SEX)."',
							user_company 	= '".dbhcms_f_input_to_dbvalue('user_company', 	DBHCMS_C_DT_STRING)."',
							user_location 	= '".dbhcms_f_input_to_dbvalue('user_location', DBHCMS_C_DT_STRING)."',
							user_email 		= '".dbhcms_f_input_to_dbvalue('user_email', 	DBHCMS_C_DT_STRING)."',
							user_website 	= '".dbhcms_f_input_to_dbvalue('user_website', 	DBHCMS_C_DT_STRING)."',
							user_lang 		= '".dbhcms_f_input_to_dbvalue('user_lang', 	DBHCMS_C_DT_LANGUAGE)."',
							user_domains 	= '".dbhcms_f_input_to_dbvalue('user_domains', 	DBHCMS_C_DT_DOMAINARRAY)."',
							user_level 		= '".dbhcms_f_input_to_dbvalue('user_level', 	DBHCMS_C_DT_ULARRAY)."'
							
							WHERE user_id = ".dbhcms_f_input_to_dbvalue('dbhcms_save_user', DBHCMS_C_DT_INTEGER)) or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - '.dbhcms_f_dict('dbhcms_msg_settingsnotsaved', true).'</div><strong>SQL Error: </strong>'.mysql_error();
			
		}
	}

	if (isset($_GET['deleteuser'])){
		$action_result = '<div style="color: #076619; font-weight: bold;">User has been deleted.</div>';
		$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_USERS." WHERE user_id = ".$_GET['deleteuser']);
		$user_row = mysql_fetch_assoc($result);
		if (in_array($user_row['user_login'], $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['superUsers'])) {
			$action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - User could not be deleted. User "'.strtoupper($user_row['user_login']).'" is a DBHcms Superuser.</div>';
		} else {
			mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_USERS." WHERE user_id = ".$_GET['deleteuser']) or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - User could not be deleted.</div><strong>SQL Error: </strong>'.mysql_error();
		}
	}

	$dbhcms_users = '';
	$i = 0;
	$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_USERS);
	while ($row = mysql_fetch_assoc($result)){
		
		if ($i & 1) { 
			$dbhcms_users .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCD."\" onmouseover=\"this.bgColor = '".DBHCMS_ADMIN_C_RCH."'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCD."'\">"; 
		} else { 
			$dbhcms_users .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCL."\" onmouseover=\"this.bgColor = '".DBHCMS_ADMIN_C_RCH."'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCL."'\">"; 
		}
		
		if ($row['user_sex'] == DBHCMS_C_ST_FEMALE) {
			$dbhcms_users .= '<td align="center" width="20">'.dbhcms_f_get_icon('female').'</td>';
		} else {
			$dbhcms_users .= '<td align="center" width="20">'.dbhcms_f_get_icon('male').'</td>';
		}
		
		$dbhcms_users .= '<td><strong>'.dbhcms_f_dbvalue_to_value($row['user_login'], DBHCMS_C_DT_STRING).'</strong></td>';
		$dbhcms_users .= '<td>'.dbhcms_f_dbvalue_to_value($row['user_name'], DBHCMS_C_DT_STRING).'</td>';
		$dbhcms_users .= '<td>'.dbhcms_f_dbvalue_to_value($row['user_location'], DBHCMS_C_DT_STRING).'</td>';
		$dbhcms_users .= '<td>'.dbhcms_f_dbvalue_to_value($row['user_lang'], DBHCMS_C_DT_LANGUAGE).'</td>';
		$dbhcms_users .= '<td>'.str_replace(';', '', dbhcms_f_dbvalue_to_value($row['user_level'], DBHCMS_C_DT_STRING)).'</td>';
		$dbhcms_users .= '<td align="center" width="20"><a title="'.$GLOBALS['DBHCMS']['DICT']['BE']['edit'].'" href="index.php?dbhcms_pid=-71&edituser='.dbhcms_f_dbvalue_to_value($row['user_id'], DBHCMS_C_DT_INTEGER).'">'.dbhcms_f_get_icon('document-properties', dbhcms_f_dict('edit', true), 1).'</a></td>';
		$dbhcms_users .= '<td align="center" width="20"><a title="'.$GLOBALS['DBHCMS']['DICT']['BE']['delete'].'" href="index.php?dbhcms_pid=-70&deleteuser='.dbhcms_f_dbvalue_to_value($row['user_id'], DBHCMS_C_DT_INTEGER).'" onclick=" return confirm(\'Delete the user &raquo;'.strtoupper(dbhcms_f_dbvalue_to_value($row['user_login'], DBHCMS_C_DT_STRING)).'&laquo; ? \'); ">'.dbhcms_f_get_icon('edit-delete', dbhcms_f_dict('delete', true), 1).'</a></td></tr>';
		$i++;
	}
	
	dbhcms_p_add_string('dbhcms_users', $dbhcms_users);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>