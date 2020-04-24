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
# $Id: mod.menus.edit.php 60 2007-02-01 13:34:54Z kaisven $                                 #
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
#	MODULE MOD.MENUS.EDIT.PHP                                                                 #
#############################################################################################

	if (isset($_GET['editmenu'])) {
		dbhcms_p_add_string('dbhcms_editmenu_title', 'Edit Menu');
		dbhcms_p_add_string('dbhcms_editmenu_id', $_GET['editmenu']);
		
		$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_MENUS." WHERE menu_id = ". $_GET['editmenu']);
		if ($user_row = mysql_fetch_assoc($result)) {
			dbhcms_p_add_string('dbhcms_editmenu_name', dbhcms_f_dbvalue_to_input('menu_name', $user_row['menu_name'], DBHCMS_C_DT_STRING, 'dbhcms_edit_menu', 'width:290px;'));
			dbhcms_p_add_string('dbhcms_editmenu_type', dbhcms_f_dbvalue_to_input('menu_type', $user_row['menu_type'], DBHCMS_C_DT_MENUTYPE, 'dbhcms_edit_menu', 'width:290px;'));
			dbhcms_p_add_string('dbhcms_editmenu_layer', dbhcms_f_dbvalue_to_input('menu_layer', $user_row['menu_layer'], DBHCMS_C_DT_INTEGER, 'dbhcms_edit_menu', 'width:290px;'));
			dbhcms_p_add_string('dbhcms_editmenu_depth', dbhcms_f_dbvalue_to_input('menu_depth', $user_row['menu_depth'], DBHCMS_C_DT_INTEGER, 'dbhcms_edit_menu', 'width:290px;'));
			dbhcms_p_add_string('dbhcms_editmenu_showrestricted', dbhcms_f_dbvalue_to_input('menu_show_restricted', $user_row['menu_show_restricted'], DBHCMS_C_DT_BOOLEAN, 'dbhcms_edit_menu', 'width:290px;'));
			dbhcms_p_add_string('dbhcms_editmenu_wrapall', dbhcms_f_dbvalue_to_input('menu_wrap_all', $user_row['menu_wrap_all'], DBHCMS_C_DT_HTML, 'dbhcms_edit_menu', 'width:290px; height:80px; font-size: 10px;'));
			dbhcms_p_add_string('dbhcms_editmenu_wrapnormal', dbhcms_f_dbvalue_to_input('menu_wrap_normal', $user_row['menu_wrap_normal'], DBHCMS_C_DT_HTML, 'dbhcms_edit_menu', 'width:290px; height:80px; font-size: 10px;'));
			dbhcms_p_add_string('dbhcms_editmenu_wrapactive', dbhcms_f_dbvalue_to_input('menu_wrap_active', $user_row['menu_wrap_active'], DBHCMS_C_DT_HTML, 'dbhcms_edit_menu', 'width:290px; height:80px; font-size: 10px;'));
			dbhcms_p_add_string('dbhcms_editmenu_wrapselected', dbhcms_f_dbvalue_to_input('menu_wrap_selected', $user_row['menu_wrap_selected'], DBHCMS_C_DT_HTML, 'dbhcms_edit_menu', 'width:290px; height:80px; font-size: 10px;'));
			dbhcms_p_add_string('dbhcms_editmenu_linknormal', dbhcms_f_dbvalue_to_input('menu_link_normal', $user_row['menu_link_normal'], DBHCMS_C_DT_HTML, 'dbhcms_edit_menu', 'width:290px; height:80px; font-size: 10px;'));
			dbhcms_p_add_string('dbhcms_editmenu_linkactive', dbhcms_f_dbvalue_to_input('menu_link_active', $user_row['menu_link_active'], DBHCMS_C_DT_HTML, 'dbhcms_edit_menu', 'width:290px; height:80px; font-size: 10px;'));
			dbhcms_p_add_string('dbhcms_editmenu_linkselected', dbhcms_f_dbvalue_to_input('menu_link_selected', $user_row['menu_link_selected'], DBHCMS_C_DT_HTML, 'dbhcms_edit_menu', 'width:290px; height:80px; font-size: 10px;'));
			dbhcms_p_add_string('dbhcms_editmenu_description', dbhcms_f_dbvalue_to_input('menu_description', $user_row['menu_description'], DBHCMS_C_DT_TEXT, 'dbhcms_edit_menu', 'width:290px; height:80px; font-size: 10px;'));
		}
		
	} else {
		dbhcms_p_add_string('dbhcms_editmenu_title', 'New Menu');
		dbhcms_p_add_string('dbhcms_editmenu_id', 'new');
		
		dbhcms_p_add_string('dbhcms_editmenu_name', dbhcms_f_dbvalue_to_input('menu_name', '', DBHCMS_C_DT_STRING, 'dbhcms_edit_menu', 'width:290px;'));
		dbhcms_p_add_string('dbhcms_editmenu_type', dbhcms_f_dbvalue_to_input('menu_type', '', DBHCMS_C_DT_MENUTYPE, 'dbhcms_edit_menu', 'width:290px;'));
		dbhcms_p_add_string('dbhcms_editmenu_layer', dbhcms_f_dbvalue_to_input('menu_layer', 0, DBHCMS_C_DT_INTEGER, 'dbhcms_edit_menu', 'width:290px;'));
		dbhcms_p_add_string('dbhcms_editmenu_depth', dbhcms_f_dbvalue_to_input('menu_depth', 0, DBHCMS_C_DT_INTEGER, 'dbhcms_edit_menu', 'width:290px;'));
		dbhcms_p_add_string('dbhcms_editmenu_showrestricted', dbhcms_f_dbvalue_to_input('menu_show_restricted', 1, DBHCMS_C_DT_BOOLEAN, 'dbhcms_edit_menu', 'width:290px;'));
		dbhcms_p_add_string('dbhcms_editmenu_wrapall', dbhcms_f_dbvalue_to_input('menu_wrap_all', '|', DBHCMS_C_DT_HTML, 'dbhcms_edit_menu', 'width:290px; height:80px; font-size: 10px;'));
		dbhcms_p_add_string('dbhcms_editmenu_wrapnormal', dbhcms_f_dbvalue_to_input('menu_wrap_normal', '|', DBHCMS_C_DT_HTML, 'dbhcms_edit_menu', 'width:290px; height:80px; font-size: 10px;'));
		dbhcms_p_add_string('dbhcms_editmenu_wrapactive', dbhcms_f_dbvalue_to_input('menu_wrap_active', '|', DBHCMS_C_DT_HTML, 'dbhcms_edit_menu', 'width:290px; height:80px; font-size: 10px;'));
		dbhcms_p_add_string('dbhcms_editmenu_wrapselected', dbhcms_f_dbvalue_to_input('menu_wrap_selected', '|', DBHCMS_C_DT_HTML, 'dbhcms_edit_menu', 'width:290px; height:80px; font-size: 10px;'));
		dbhcms_p_add_string('dbhcms_editmenu_linknormal', dbhcms_f_dbvalue_to_input('menu_link_normal', '[pageParamName]', DBHCMS_C_DT_HTML, 'dbhcms_edit_menu', 'width:290px; height:80px; font-size: 10px;'));
		dbhcms_p_add_string('dbhcms_editmenu_linkactive', dbhcms_f_dbvalue_to_input('menu_link_active', '[pageParamName]', DBHCMS_C_DT_HTML, 'dbhcms_edit_menu', 'width:290px; height:80px; font-size: 10px;'));
		dbhcms_p_add_string('dbhcms_editmenu_linkselected', dbhcms_f_dbvalue_to_input('menu_link_selected', '[pageParamName]', DBHCMS_C_DT_HTML, 'dbhcms_edit_menu', 'width:290px; height:80px; font-size: 10px;'));
		dbhcms_p_add_string('dbhcms_editmenu_description', dbhcms_f_dbvalue_to_input('menu_description', '', DBHCMS_C_DT_TEXT, 'dbhcms_edit_menu', 'width:290px; height:80px; font-size: 10px;'));
		
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>
