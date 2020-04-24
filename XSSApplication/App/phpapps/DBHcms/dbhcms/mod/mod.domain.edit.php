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
# $Id: mod.domain.edit.php 60 2007-02-01 13:34:54Z kaisven $                                #
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
#	MODULE MOD.EDITDOMAINS.PHP                                                                #
#############################################################################################

	$dbhcms_spod_index = '';
	$dbhcms_spod_intro = '';
	$dbhcms_spod_login = '';
	$dbhcms_spod_logout = '';
	$dbhcms_spod_ad = '';
	
	$dbhcms_spod_err401 = '';
	$dbhcms_spod_err403 = '';
	$dbhcms_spod_err404 = '';
	
	$dbhcms_lang_array = array();
	
	$dbhcms_sslod = '';
	$dbhcms_sdlod = '';

	if (isset($_GET['editdomain'])) {
	
		$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS." WHERE domn_id = ".$_GET['editdomain']);
		if ($row_domain = mysql_fetch_array($result)) {
			
			$dbhcms_editdomainid = $_GET['editdomain'];
			
			$dbhcms_sslod = dbhcms_f_dbvalue_to_input('domn_supported_langs', $row_domain['domn_supported_langs'], DBHCMS_C_DT_LANGARRAY, 'dbhcms_edit_domain', 'width: 200px;');
			$dbhcms_sdlod = dbhcms_f_dbvalue_to_input('domn_default_lang', $row_domain['domn_default_lang'], DBHCMS_C_DT_LANGUAGE, 'dbhcms_edit_domain', 'width: 200px;');
			
			$dbhcms_spod_index = dbhcms_f_dbvalue_to_input('domn_index_pid', $row_domain['domn_index_pid'], DBHCMS_C_DT_PAGE, 'dbhcms_edit_domain', 'width: 200px;', $_GET['editdomain']);
			$dbhcms_spod_intro = dbhcms_f_dbvalue_to_input('domn_intro_pid', $row_domain['domn_intro_pid'], DBHCMS_C_DT_PAGE, 'dbhcms_edit_domain', 'width: 200px;', $_GET['editdomain']);
			$dbhcms_spod_login = dbhcms_f_dbvalue_to_input('domn_login_pid', $row_domain['domn_login_pid'], DBHCMS_C_DT_PAGE, 'dbhcms_edit_domain', 'width: 200px;', $_GET['editdomain']);
			$dbhcms_spod_logout = dbhcms_f_dbvalue_to_input('domn_logout_pid', $row_domain['domn_logout_pid'], DBHCMS_C_DT_PAGE, 'dbhcms_edit_domain', 'width: 200px;', $_GET['editdomain']);
			$dbhcms_spod_ad = dbhcms_f_dbvalue_to_input('domn_ad_pid', $row_domain['domn_ad_pid'], DBHCMS_C_DT_PAGE, 'dbhcms_edit_domain', 'width: 200px;', $_GET['editdomain']);
			
			$dbhcms_spod_err401 = dbhcms_f_dbvalue_to_input('domn_err401_pid', $row_domain['domn_err401_pid'], DBHCMS_C_DT_PAGE, 'dbhcms_edit_domain', 'width: 200px;', $_GET['editdomain']);
			$dbhcms_spod_err403 = dbhcms_f_dbvalue_to_input('domn_err403_pid', $row_domain['domn_err403_pid'], DBHCMS_C_DT_PAGE, 'dbhcms_edit_domain', 'width: 200px;', $_GET['editdomain']);
			$dbhcms_spod_err404 = dbhcms_f_dbvalue_to_input('domn_err404_pid', $row_domain['domn_err404_pid'], DBHCMS_C_DT_PAGE, 'dbhcms_edit_domain', 'width: 200px;', $_GET['editdomain']);
			
		} else {
			die('<div style="color: #FF0000; font-weight: bold;">ERROR! - Domain not found.</div>');
		}
		
		$dbhcms_spod_index 	= '<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \''.DBHCMS_ADMIN_C_RCH.'\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'"><td><strong>Index Page</strong></td><td>'.$dbhcms_spod_index.'</td><td>Index page</td></tr>';
		$dbhcms_spod_intro 	= '<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \''.DBHCMS_ADMIN_C_RCH.'\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'"><td><strong>Intro Page</strong></td><td>'.$dbhcms_spod_intro.'</td><td>Intro page</td></tr>';
		$dbhcms_spod_login 	= '<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \''.DBHCMS_ADMIN_C_RCH.'\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'"><td><strong>Login Page</strong></td><td>'.$dbhcms_spod_login.'</td><td>Login page</td></tr>';
		$dbhcms_spod_logout = '<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \''.DBHCMS_ADMIN_C_RCH.'\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'"><td><strong>Logout Page</strong></td><td>'.$dbhcms_spod_logout.'</td><td>Logout page</td></tr>';
		$dbhcms_spod_ad 	= '<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \''.DBHCMS_ADMIN_C_RCH.'\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'"><td><strong>Access-Denied Page</strong></td><td>'.$dbhcms_spod_ad.'</td><td>Access-Denied page</td></tr>';
		
		$dbhcms_spod_err401 = '<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \''.DBHCMS_ADMIN_C_RCH.'\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'"><td><strong>Error 401 Page</strong></td><td>'.$dbhcms_spod_err401.'</td><td>Error 401 custom page (Unauthorized) </td></tr>';
		$dbhcms_spod_err403 = '<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \''.DBHCMS_ADMIN_C_RCH.'\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'"><td><strong>Error 403 Page</strong></td><td>'.$dbhcms_spod_err403.'</td><td>Error 403 custom page (Forbidden) </td></tr>';
		$dbhcms_spod_err404 = '<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \''.DBHCMS_ADMIN_C_RCH.'\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'"><td><strong>Error 404 Page</strong></td><td>'.$dbhcms_spod_err404.'</td><td>Error 404 custom page (Not Found) </td></tr>';
		
		$dbhcms_domn_name 			= dbhcms_f_dbvalue_to_input('domn_name', $row_domain['domn_name'], DBHCMS_C_DT_STRING, 'dbhcms_edit_domain', 'width: 200px;');
		$dbhcms_domn_subfolders 	= dbhcms_f_dbvalue_to_input('domn_subfolders', $row_domain['domn_subfolders'], DBHCMS_C_DT_STRING, 'dbhcms_edit_domain', 'width: 200px;');
		$dbhcms_domn_absolute_url 	= dbhcms_f_dbvalue_to_input('domn_absolute_url', $row_domain['domn_absolute_url'], DBHCMS_C_DT_STRING, 'dbhcms_edit_domain', 'width: 200px;');
		$dbhcms_domn_stylesheets 	= dbhcms_f_dbvalue_to_input('domn_stylesheets', $row_domain['domn_stylesheets'], DBHCMS_C_DT_CSSARRAY, 'dbhcms_edit_domain', 'width: 200px;');
		$dbhcms_domn_javascripts 	= dbhcms_f_dbvalue_to_input('domn_javascripts', $row_domain['domn_javascripts'], DBHCMS_C_DT_JSARRAY, 'dbhcms_edit_domain', 'width: 200px;');
		$dbhcms_domn_templates 		= dbhcms_f_dbvalue_to_input('domn_templates', $row_domain['domn_templates'], DBHCMS_C_DT_TPLARRAY, 'dbhcms_edit_domain', 'width: 200px;');
		$dbhcms_domn_php_modules 	= dbhcms_f_dbvalue_to_input('domn_php_modules', $row_domain['domn_php_modules'], DBHCMS_C_DT_MODARRAY, 'dbhcms_edit_domain', 'width: 200px');
		$dbhcms_domn_extensions 	= dbhcms_f_dbvalue_to_input('domn_extensions', $row_domain['domn_extensions'], DBHCMS_C_DT_EXTARRAY, 'dbhcms_edit_domain', 'width: 200px;');
		$dbhcms_domn_description 	= dbhcms_f_dbvalue_to_input('domn_description', $row_domain['domn_description'], DBHCMS_C_DT_TEXT, 'dbhcms_edit_domain', 'width: 200px;');
		
	} else {
		
		$dbhcms_editdomainid = 'new';
		
		$dbhcms_sslod 		   		= dbhcms_f_value_to_input('domn_supported_langs', array($_SESSION['DBHCMSDATA']['LANG']['coreUseLanguage']), DBHCMS_C_DT_LANGARRAY, 'dbhcms_edit_domain', 'width: 200px;');
		$dbhcms_sdlod 				= dbhcms_f_value_to_input('domn_default_lang', $_SESSION['DBHCMSDATA']['LANG']['coreUseLanguage'], DBHCMS_C_DT_LANGUAGE, 'dbhcms_edit_domain', 'width: 200px;');
		$dbhcms_domn_name 			= dbhcms_f_value_to_input('domn_name', '', DBHCMS_C_DT_STRING, 'dbhcms_edit_domain', 'width: 200px;');
		$dbhcms_domn_subfolders 	= dbhcms_f_value_to_input('domn_subfolders', '', DBHCMS_C_DT_STRING, 'dbhcms_edit_domain', 'width: 200px;');
		$dbhcms_domn_absolute_url 	= dbhcms_f_value_to_input('domn_absolute_url', '', DBHCMS_C_DT_STRING, 'dbhcms_edit_domain', 'width: 200px;');
		$dbhcms_domn_stylesheets 	= dbhcms_f_value_to_input('domn_stylesheets', array(), DBHCMS_C_DT_CSSARRAY, 'dbhcms_edit_domain', 'width: 200px;');
		$dbhcms_domn_javascripts 	= dbhcms_f_value_to_input('domn_javascripts', array(), DBHCMS_C_DT_JSARRAY, 'dbhcms_edit_domain', 'width: 200px;');
		$dbhcms_domn_templates 		= dbhcms_f_value_to_input('domn_templates', array(), DBHCMS_C_DT_TPLARRAY, 'dbhcms_edit_domain', 'width: 200px;');
		$dbhcms_domn_php_modules 	= dbhcms_f_value_to_input('domn_php_modules', array(), DBHCMS_C_DT_MODARRAY, 'dbhcms_edit_domain', 'width: 200px;');
		$dbhcms_domn_extensions 	= dbhcms_f_value_to_input('domn_extensions', array(), DBHCMS_C_DT_EXTARRAY, 'dbhcms_edit_domain', 'width: 200px;');
		$dbhcms_domn_description 	= dbhcms_f_value_to_input('domn_description', '', DBHCMS_C_DT_TEXT, 'dbhcms_edit_domain', 'width: 200px;');
		
	}

#############################################################################################
#																							#
#	MODULE RESULT PARAMETERS																#
#																							#
#############################################################################################

	dbhcms_p_add_string('dbhcms_edit_domain_id', $dbhcms_editdomainid);
	
	dbhcms_p_add_string('dbhcms_spod_index', $dbhcms_spod_index);
	dbhcms_p_add_string('dbhcms_spod_intro', $dbhcms_spod_intro);
	dbhcms_p_add_string('dbhcms_spod_login', $dbhcms_spod_login);
	dbhcms_p_add_string('dbhcms_spod_logout', $dbhcms_spod_logout);
	dbhcms_p_add_string('dbhcms_spod_ad', $dbhcms_spod_ad);
	
	dbhcms_p_add_string('dbhcms_spod_err401', $dbhcms_spod_err401);
	dbhcms_p_add_string('dbhcms_spod_err403', $dbhcms_spod_err403);
	dbhcms_p_add_string('dbhcms_spod_err404', $dbhcms_spod_err404);
	
	dbhcms_p_add_string('dbhcms_sslod', $dbhcms_sslod);
	dbhcms_p_add_string('dbhcms_sdlod', $dbhcms_sdlod);
	
	dbhcms_p_add_string('dbhcms_edit_domain_name', $dbhcms_domn_name);
	dbhcms_p_add_string('dbhcms_edit_domain_subfolders', $dbhcms_domn_subfolders);
	dbhcms_p_add_string('dbhcms_edit_domain_absolute_url', $dbhcms_domn_absolute_url);
	dbhcms_p_add_string('dbhcms_edit_domain_stylesheets', $dbhcms_domn_stylesheets);
	dbhcms_p_add_string('dbhcms_edit_domain_javascripts', $dbhcms_domn_javascripts);
	dbhcms_p_add_string('dbhcms_edit_domain_templates', $dbhcms_domn_templates);
	dbhcms_p_add_string('dbhcms_edit_domain_php_modules', $dbhcms_domn_php_modules);
	dbhcms_p_add_string('dbhcms_edit_domain_extensions', $dbhcms_domn_extensions);
	dbhcms_p_add_string('dbhcms_edit_domain_decription', $dbhcms_domn_description);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>