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
# $Id: mod.menus.view.php 60 2007-02-01 13:34:54Z kaisven $                                 #
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
#	MODULE MOD.MENUS.VIEW.PHP                                                                 #
#############################################################################################

	if (isset($_POST['dbhcms_save_menu'])) {
		if ($_POST['dbhcms_save_menu'] == 'new' ) {
			$action_result = '<div style="color: #076619; font-weight: bold;">Menu has been created.</div>';
			mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_MENUS."
							(menu_name, menu_type, menu_layer, menu_depth, menu_show_restricted, menu_wrap_all, menu_wrap_normal, menu_wrap_active, menu_wrap_selected, menu_link_normal, menu_link_active, menu_link_selected, menu_description) VALUES
							(
								'".dbhcms_f_input_to_dbvalue('menu_name', DBHCMS_C_DT_STRING)."', 
								'".dbhcms_f_input_to_dbvalue('menu_type', DBHCMS_C_DT_MENUTYPE)."', 
								'".dbhcms_f_input_to_dbvalue('menu_layer', DBHCMS_C_DT_INTEGER)."', 
								'".dbhcms_f_input_to_dbvalue('menu_depth', DBHCMS_C_DT_INTEGER)."', 
								'".dbhcms_f_input_to_dbvalue('menu_show_restricted', DBHCMS_C_DT_BOOLEAN)."', 
								'".dbhcms_f_input_to_dbvalue('menu_wrap_all', DBHCMS_C_DT_HTML)."', 
								'".dbhcms_f_input_to_dbvalue('menu_wrap_normal', DBHCMS_C_DT_HTML)."', 
								'".dbhcms_f_input_to_dbvalue('menu_wrap_active', DBHCMS_C_DT_HTML)."', 
								'".dbhcms_f_input_to_dbvalue('menu_wrap_selected', DBHCMS_C_DT_HTML)."', 
								'".dbhcms_f_input_to_dbvalue('menu_link_normal', DBHCMS_C_DT_HTML)."', 
								'".dbhcms_f_input_to_dbvalue('menu_link_active', DBHCMS_C_DT_HTML)."', 
								'".dbhcms_f_input_to_dbvalue('menu_link_selected', DBHCMS_C_DT_HTML)."', 
								'".dbhcms_f_input_to_dbvalue('menu_description', DBHCMS_C_DT_TEXT)."'
							)
					") or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Menu could not be created.</div><strong>SQL Error: </strong>'.mysql_error();
		} else {
			$action_result = '<div style="color: #076619; font-weight: bold;">Menu has been saved.</div>';
			mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_MENUS." SET 
				
				`menu_name` 			= '".dbhcms_f_input_to_dbvalue('menu_name', DBHCMS_C_DT_STRING)."', 
				`menu_type` 			= '".dbhcms_f_input_to_dbvalue('menu_type', DBHCMS_C_DT_MENUTYPE)."', 
				`menu_layer` 			= '".dbhcms_f_input_to_dbvalue('menu_layer', DBHCMS_C_DT_INTEGER)."', 
				`menu_depth` 			= '".dbhcms_f_input_to_dbvalue('menu_depth', DBHCMS_C_DT_INTEGER)."', 
				`menu_show_restricted` 	= '".dbhcms_f_input_to_dbvalue('menu_show_restricted', DBHCMS_C_DT_BOOLEAN)."', 
				`menu_wrap_all` 		= '".dbhcms_f_input_to_dbvalue('menu_wrap_all', DBHCMS_C_DT_HTML)."', 
				`menu_wrap_normal` 		= '".dbhcms_f_input_to_dbvalue('menu_wrap_normal', DBHCMS_C_DT_HTML)."', 
				`menu_wrap_active` 		= '".dbhcms_f_input_to_dbvalue('menu_wrap_active', DBHCMS_C_DT_HTML)."', 
				`menu_wrap_selected` 	= '".dbhcms_f_input_to_dbvalue('menu_wrap_selected', DBHCMS_C_DT_HTML)."', 
				`menu_link_normal` 		= '".dbhcms_f_input_to_dbvalue('menu_link_normal', DBHCMS_C_DT_HTML)."', 
				`menu_link_active` 		= '".dbhcms_f_input_to_dbvalue('menu_link_active', DBHCMS_C_DT_HTML)."', 
				`menu_link_selected` 	= '".dbhcms_f_input_to_dbvalue('menu_link_selected', DBHCMS_C_DT_HTML)."', 
				`menu_description` 		= '".dbhcms_f_input_to_dbvalue('menu_description', DBHCMS_C_DT_TEXT)."'
				
				WHERE `menu_id` = ".$_POST['dbhcms_save_menu']." LIMIT 1 ;") or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Menu could not be saved.</div><strong>SQL Error: </strong>'.mysql_error();
		}
	}

	if (isset($_GET['deletemenu'])){
		$action_result = '<div style="color: #076619; font-weight: bold;">Menu has been deleted.</div>';
		mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_MENUS." WHERE menu_id = ".$_GET['deletemenu']) 
			or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Menu could not be deleted.</div><strong>SQL Error: </strong>'.mysql_error();
	}

	$dbhcms_menus = '';

	$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_MENUS);
	$i = 0;

	while ($row = mysql_fetch_array($result)) {
	
		if ($i & 1) { 
			$dbhcms_menus .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCD."\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCD."'\">"; 
		} else { 
			$dbhcms_menus .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCL."\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCL."'\">"; 
		}

		$dbhcms_menus .= '<td align="center" width="20">'.dbhcms_f_get_icon('menu').'</td>';
		
		$dbhcms_menus .= "<td align=\"left\" valign=\"top\"><b>".$row['menu_name']."</b></td>";
		$dbhcms_menus .= "<td align=\"left\" valign=\"top\">".$row['menu_type']."</td>";
		$dbhcms_menus .= "<td valign=\"top\">".$row['menu_description']."</td>";

		$dbhcms_menus .= "<td align=\"center\" valign=\"top\" width=\"20\"><a href=\"index.php?dbhcms_pid=-81&editmenu=".$row['menu_id']."\">".dbhcms_f_get_icon('document-properties', dbhcms_f_dict('edit', true), 1)."</a></td>";
		$dbhcms_menus .= "<td align=\"center\" valign=\"top\" width=\"20\"><a href=\"index.php?dbhcms_pid=-80&deletemenu=".$row['menu_id']."\" onclick=\" return confirm('".dbhcms_f_dict('dbhcms_msg_askdeleteitem', true)."'); \" >".dbhcms_f_get_icon('edit-delete', dbhcms_f_dict('delete', true), 1)."</a></td>";
		
		$dbhcms_menus .= '</tr>';

		$i = ($i + 1);

	}

#############################################################################################
#	MODULE RESULT PARAMETERS                                                                  #
#############################################################################################

	dbhcms_p_add_string('dbhcms_menus', $dbhcms_menus);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>
