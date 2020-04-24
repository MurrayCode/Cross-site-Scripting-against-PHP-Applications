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
# $Id: mod.domain.view.php 60 2007-02-01 13:34:54Z kaisven $                                #
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
#	MODULE MOD.DOMAINS.PHP                                                                    #
#############################################################################################

	if (isset($_POST['dbhcms_save_domain'])) {
		if ($_POST['dbhcms_save_domain'] == 'new') {
			$action_result = '<div style="color: #076619; font-weight: bold;">Domain has been created.</div>';
			mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS." (`domn_name`, `domn_subfolders`, `domn_absolute_url`, `domn_default_lang`, `domn_supported_langs`, `domn_stylesheets`, `domn_javascripts`, `domn_templates`, `domn_php_modules`, `domn_extensions`, `domn_description`) 
								VALUES ( 
									   	 	'".dbhcms_f_input_to_dbvalue('domn_name', DBHCMS_C_DT_STRING)."', 
											'".dbhcms_f_input_to_dbvalue('domn_subfolders', DBHCMS_C_DT_STRING)."', 
											'".dbhcms_f_input_to_dbvalue('domn_absolute_url', DBHCMS_C_DT_STRING)."', 
											'".dbhcms_f_input_to_dbvalue('domn_default_lang', DBHCMS_C_DT_LANGUAGE)."', 
											'".dbhcms_f_input_to_dbvalue('domn_supported_langs', DBHCMS_C_DT_LANGARRAY)."', 
											'".dbhcms_f_input_to_dbvalue('domn_stylesheets', DBHCMS_C_DT_CSSARRAY)."', 
											'".dbhcms_f_input_to_dbvalue('domn_javascripts', DBHCMS_C_DT_JSARRAY)."', 
											'".dbhcms_f_input_to_dbvalue('domn_templates', DBHCMS_C_DT_TPLARRAY)."', 
											'".dbhcms_f_input_to_dbvalue('domn_php_modules', DBHCMS_C_DT_MODARRAY)."', 
											'".dbhcms_f_input_to_dbvalue('domn_extensions', DBHCMS_C_DT_EXTARRAY)."', 
											'".dbhcms_f_input_to_dbvalue('domn_description', DBHCMS_C_DT_TEXT)."');
										") or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Domain could not be created.</div><strong>SQL Error: </strong>'.mysql_error();
		
		} else {
			$action_result = '<div style="color: #076619; font-weight: bold;">Domain has been saved.</div>';
			mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS." SET 
				
				`domn_index_pid` 		= '".dbhcms_f_input_to_dbvalue('domn_index_pid', DBHCMS_C_DT_PAGE)."', 
				`domn_intro_pid`   		= '".dbhcms_f_input_to_dbvalue('domn_intro_pid', DBHCMS_C_DT_PAGE)."', 
				`domn_login_pid` 		= '".dbhcms_f_input_to_dbvalue('domn_login_pid', DBHCMS_C_DT_PAGE)."', 
				`domn_logout_pid` 		= '".dbhcms_f_input_to_dbvalue('domn_logout_pid', DBHCMS_C_DT_PAGE)."', 
				`domn_ad_pid` 			= '".dbhcms_f_input_to_dbvalue('domn_ad_pid', DBHCMS_C_DT_PAGE)."', 
				`domn_err401_pid` 		= '".dbhcms_f_input_to_dbvalue('domn_err401_pid', DBHCMS_C_DT_PAGE)."', 
				`domn_err403_pid` 		= '".dbhcms_f_input_to_dbvalue('domn_err403_pid', DBHCMS_C_DT_PAGE)."', 
				`domn_err404_pid` 		= '".dbhcms_f_input_to_dbvalue('domn_err404_pid', DBHCMS_C_DT_PAGE)."',  
				`domn_name` 			= '".dbhcms_f_input_to_dbvalue('domn_name', DBHCMS_C_DT_STRING)."', 
				`domn_subfolders` 		= '".dbhcms_f_input_to_dbvalue('domn_subfolders', DBHCMS_C_DT_STRING)."', 
				`domn_absolute_url` 	= '".dbhcms_f_input_to_dbvalue('domn_absolute_url', DBHCMS_C_DT_STRING)."', 
				`domn_default_lang` 	= '".dbhcms_f_input_to_dbvalue('domn_default_lang', DBHCMS_C_DT_LANGUAGE)."', 
				`domn_supported_langs` 	= '".dbhcms_f_input_to_dbvalue('domn_supported_langs', DBHCMS_C_DT_LANGARRAY)."', 
				`domn_stylesheets` 		= '".dbhcms_f_input_to_dbvalue('domn_stylesheets', DBHCMS_C_DT_CSSARRAY)."', 
				`domn_javascripts` 		= '".dbhcms_f_input_to_dbvalue('domn_javascripts', DBHCMS_C_DT_JSARRAY)."', 
				`domn_templates` 		= '".dbhcms_f_input_to_dbvalue('domn_templates', DBHCMS_C_DT_TPLARRAY)."', 
				`domn_php_modules` 		= '".dbhcms_f_input_to_dbvalue('domn_php_modules', DBHCMS_C_DT_MODARRAY)."', 
				`domn_extensions` 		= '".dbhcms_f_input_to_dbvalue('domn_extensions', DBHCMS_C_DT_EXTARRAY)."', 
				`domn_description` 		= '".dbhcms_f_input_to_dbvalue('domn_description', DBHCMS_C_DT_TEXT)."' 
				
				WHERE `domn_id` = ".$_POST['dbhcms_save_domain']." LIMIT 1 ;") or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Domain could not be saved.</div><strong>SQL Error: </strong>'.mysql_error();
		}
		dbhcms_p_add_missing_pagevals();
	}

	if (isset($_GET['deletedomain'])) {
		$action_result = '<div style="color: #076619; font-weight: bold;">Domain hass been deleted.</div>';
		if (mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS." WHERE domn_id = ".$_GET['deletedomain'])) {
			$result = mysql_query("SELECT page_id FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." WHERE page_domn_id = ".$_GET['deletedomain']);
			while ($row = mysql_fetch_assoc($result)) {
				mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEPARAMS." WHERE papa_page_id = ".$row['page_id'])
					or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Some page parameters could not be deleted.</div>';
				mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEVALS." WHERE pava_page_id = ".$row['page_id'])
					or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Some page values could not be deleted.</div>';
				mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." WHERE page_id = ".$row['page_id'])
					or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Some pages could not be deleted.</div>';
			}
		} else {
			$action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Domain could not be deleted.</div>';
		}
	}

	$dbhcms_domains = '';

	$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS);
	$i = 0;

	while ($row = mysql_fetch_array($result)) {
		
		if ($i & 1) { 
			$dbhcms_domains .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCD."\" onmouseover=\"this.bgColor = '".DBHCMS_ADMIN_C_RCH."'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCD."'\">"; 
		} else { 
			$dbhcms_domains .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCL."\" onmouseover=\"this.bgColor = '".DBHCMS_ADMIN_C_RCH."'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCL."'\">"; 
		}

		$dbhcms_domains .= '<td align="center" width="20">'.dbhcms_f_get_icon('domain').'</td>';
		
		$dbhcms_domains .= "<td align=\"left\" valign=\"top\"><b>".$row['domn_name']."</b></td>";
		$dbhcms_domains .= "<td align=\"left\" valign=\"top\">".$row['domn_absolute_url']."</td>";
		$dbhcms_domains .= "<td valign=\"top\">".$row['domn_description']."</td>";

		$dbhcms_domains .= "<td align=\"center\" valign=\"top\"><a href=\"index.php?dbhcms_pid=-40&fe_domain_id=".$row['domn_id']."\">".dbhcms_f_get_icon('view')."</a></td>";
		$dbhcms_domains .= "<td align=\"center\" valign=\"top\"><a href=\"index.php?dbhcms_pid=-21&editdomain=".$row['domn_id']."\">".dbhcms_f_get_icon('document-properties', dbhcms_f_dict('edit', true), 1)."</a></td>";
		$dbhcms_domains .= "<td align=\"center\" valign=\"top\"><a href=\"index.php?dbhcms_pid=-20&deletedomain=".$row['domn_id']."\" onclick=\" return confirm('Are you shure you want to delete the domain &raquo;".$row['domn_name']."&laquo;? This will delete all pages atached to this domain.'); \" >".dbhcms_f_get_icon('edit-delete', dbhcms_f_dict('delete', true), 1)."</a></td>";
		
		$dbhcms_domains .= '</tr>';

		$i = ($i + 1);

	}

#############################################################################################
#																							#
#	MODULE RESULT PARAMETERS																#
#																							#
#############################################################################################

	dbhcms_p_add_string('dbhcms_domains', $dbhcms_domains);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>
