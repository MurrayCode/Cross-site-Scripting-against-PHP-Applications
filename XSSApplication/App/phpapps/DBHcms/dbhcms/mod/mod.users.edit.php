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
# $Id: mod.users.edit.php 60 2007-02-01 13:34:54Z kaisven $                                 #
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
#	MODULE MOD.USERS.EDIT.PHP                                                                 #
#############################################################################################

	if (isset($_GET['edituser'])) {
		
		dbhcms_p_add_string('dbhcms_edituser_title', 'Edit User');
		dbhcms_p_add_string('dbhcms_edituser_id', $_GET['edituser']);
		
		$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_USERS." WHERE user_id = ". $_GET['edituser']);
		if ($user_row = mysql_fetch_assoc($result)) {
		
			dbhcms_p_add_string('dbhcms_edituser_login', '<strong>'.$user_row['user_login'].'</strong>');
			dbhcms_p_add_string('dbhcms_edituser_login_hidden', $user_row['user_login']);
			dbhcms_p_add_string('dbhcms_edituser_passwd', dbhcms_f_dbvalue_to_input('user_passwd', $user_row['user_passwd'], DBHCMS_C_DT_PASSWORD, 'dbhcms_edit_user', 'width:200px'));
			dbhcms_p_add_string('dbhcms_edituser_name', dbhcms_f_dbvalue_to_input('user_name', $user_row['user_name'], DBHCMS_C_DT_STRING, 'dbhcms_edit_user', 'width:200px'));
			dbhcms_p_add_string('dbhcms_edituser_sex', dbhcms_f_dbvalue_to_input('user_sex', $user_row['user_sex'], DBHCMS_C_DT_SEX, 'dbhcms_edit_user', 'width:200px'));
			dbhcms_p_add_string('dbhcms_edituser_company', dbhcms_f_dbvalue_to_input('user_company', $user_row['user_company'], DBHCMS_C_DT_STRING, 'dbhcms_edit_user', 'width:200px'));
			dbhcms_p_add_string('dbhcms_edituser_location', dbhcms_f_dbvalue_to_input('user_location', $user_row['user_location'], DBHCMS_C_DT_STRING, 'dbhcms_edit_user', 'width:200px'));
			dbhcms_p_add_string('dbhcms_edituser_email', dbhcms_f_dbvalue_to_input('user_email', $user_row['user_email'], DBHCMS_C_DT_STRING, 'dbhcms_edit_user', 'width:200px'));
			dbhcms_p_add_string('dbhcms_edituser_website', dbhcms_f_dbvalue_to_input('user_website', $user_row['user_website'], DBHCMS_C_DT_STRING, 'dbhcms_edit_user', 'width:200px'));
			dbhcms_p_add_string('dbhcms_edituser_lang', dbhcms_f_dbvalue_to_input('user_lang', $user_row['user_lang'], DBHCMS_C_DT_LANGUAGE, 'dbhcms_edit_user', 'width:200px'));
			dbhcms_p_add_string('dbhcms_edituser_domains', dbhcms_f_dbvalue_to_input('user_domains', $user_row['user_domains'], DBHCMS_C_DT_DOMAINARRAY, 'dbhcms_edit_user', 'width:200px'));
			dbhcms_p_add_string('dbhcms_edituser_level', dbhcms_f_dbvalue_to_input('user_level', $user_row['user_level'], DBHCMS_C_DT_ULARRAY, 'dbhcms_edit_user', 'width:200px'));
		
		}
		
	} else {
		
		dbhcms_p_add_string('dbhcms_edituser_title', 'New User');
		dbhcms_p_add_string('dbhcms_edituser_id', 'new');
		
		dbhcms_p_add_string('dbhcms_edituser_login', dbhcms_f_value_to_input('user_login', '', DBHCMS_C_DT_STRING, 'dbhcms_edit_user', 'width:200px'));
		dbhcms_p_add_string('dbhcms_edituser_passwd', dbhcms_f_value_to_input('user_passwd', '', DBHCMS_C_DT_PASSWORD, 'dbhcms_edit_user', 'width:200px'));
		dbhcms_p_add_string('dbhcms_edituser_name', dbhcms_f_value_to_input('user_name', '', DBHCMS_C_DT_STRING, 'dbhcms_edit_user', 'width:200px'));
		dbhcms_p_add_string('dbhcms_edituser_sex', dbhcms_f_value_to_input('user_sex', 'female', DBHCMS_C_DT_SEX, 'dbhcms_edit_user', 'width:200px'));
		dbhcms_p_add_string('dbhcms_edituser_company', dbhcms_f_value_to_input('user_company', '', DBHCMS_C_DT_STRING, 'dbhcms_edit_user', 'width:200px'));
		dbhcms_p_add_string('dbhcms_edituser_location', dbhcms_f_value_to_input('user_location', '', DBHCMS_C_DT_STRING, 'dbhcms_edit_user', 'width:200px'));
		dbhcms_p_add_string('dbhcms_edituser_email', dbhcms_f_value_to_input('user_email', '', DBHCMS_C_DT_STRING, 'dbhcms_edit_user', 'width:200px'));
		dbhcms_p_add_string('dbhcms_edituser_website', dbhcms_f_value_to_input('user_website', '', DBHCMS_C_DT_STRING, 'dbhcms_edit_user', 'width:200px'));
		dbhcms_p_add_string('dbhcms_edituser_lang', dbhcms_f_value_to_input('user_lang', $_SESSION['DBHCMSDATA']['LANG']['useLanguage'], DBHCMS_C_DT_LANGUAGE, 'dbhcms_edit_user', 'width:200px'));
		dbhcms_p_add_string('dbhcms_edituser_domains', dbhcms_f_value_to_input('user_domains', array($GLOBALS['DBHCMS']['DID']), DBHCMS_C_DT_DOMAINARRAY, 'dbhcms_edit_user', 'width:200px'));
		dbhcms_p_add_string('dbhcms_edituser_level', dbhcms_f_value_to_input('user_level', array('A'), DBHCMS_C_DT_ULARRAY, 'dbhcms_edit_user', 'width:200px'));
		
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>

