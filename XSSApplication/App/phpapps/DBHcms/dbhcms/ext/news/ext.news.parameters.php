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
#  EXTENSION                                                                                #
#  =============================                                                            #
#  news                                                                                     #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  A tool to publish your news                                                              #
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
# $Id: ext.news.parameters.php 61 2007-02-01 14:17:59Z kaisven $                            #
#############################################################################################

#############################################################################################
#  BE IMPLEMENTATION - PARAMETERS                                                           #
#############################################################################################

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# ACTIONS                                                                  #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	if (isset($_POST['todo'])) {
		### NEW ENTRY PARAMETER ###
		if ($_POST['todo'] == 'newsNewParam') {
			$action_result = '<div style="color: #076619; font-weight: bold;">Parameter has been inserted.</div>';
			if (mysql_num_rows(mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entriesprms WHERE upper(nwep_name) LIKE upper('".trim($_POST['newsparam_insert_name'])."')")) > 0 ) {
				$action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Parameter allready exists.</div>';
			} else {
				mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entriesprms (`nwep_type`, `nwep_name`, `nwep_description`) VALUES ('".$_POST['newsparam_insert_type']."', '".$_POST['newsparam_insert_name']."', '".$_POST['newsparam_insert_description']."')")
					or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Parameter could not be saved.</div>';
				news_p_add_missing_entry_vals();
			}
		### SAVE ENTRY PARAMETER ###
		} else if ($_POST['todo'] == 'newsSaveParam') {
			$action_result = '<div style="color: #076619; font-weight: bold;">Parameter has been saved.</div>';
			mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entriesprms SET `nwep_type` = '".$_POST['nwep_type']."', `nwep_description` = '".$_POST['nwep_description']."' WHERE nwep_name LIKE '".$_POST['nwep_name']."'")
				or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Parameter could not be saved.</div>';
		}
	} elseif (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsDeleteParam'])) {
		$action_result = '<div style="color: #076619; font-weight: bold;">Parameter has been deleted.</div>';
		mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entriesprms WHERE nwep_name LIKE '".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsDeleteParam']."'")
			or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Parameter could not be deleted.</div>';
		mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entriesvals WHERE nwev_name LIKE '".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsDeleteParam']."'")
			or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Parameter could not be deleted.</div>';
	}

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# PARAMETERS                                                               #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	$news_params = '';
	$i = 0;
	$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entriesprms");
	while ($row = mysql_fetch_assoc($result)) {
		
		if ($row['nwep_name'] != 'title') {
			
			if ($i & 1) { 
				$news_params .= "<tr bgcolor=\"#F0F0F0\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '#F0F0F0'\">"; 
			} else { 
				$news_params .= "<tr bgcolor=\"#DEDEDE\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '#DEDEDE'\">"; 
			}
			
			$news_params .= '<form method="post" name="newsparamparam_edit"><input type="hidden" name="todo" value="newsSaveParam"><input type="hidden" name="nwep_name" value="'.$row['nwep_name'].'">';
			$news_params .= '<td><strong>'.$row['nwep_name'].'</strong></td>';
			$news_params .= '<td>'.dbhcms_f_dbvalue_to_input('nwep_type', $row['nwep_type'], DBHCMS_C_DT_DATATYPE, 'newsparamparam_edit','width:100%;').'</td><td align=\"center\"><input type="text" name="nwep_description" value="'.$row['nwep_description'].'" style="width:99%;"></td>';
			$news_params .= "<td align=\"center\" width=\"20\"><input type=\"image\" style=\"cursor: pointer; border-width: 0px;\" src=\"".$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory']."icons/small/media-floppy.png\" width=\"16\" height=\"16\" title=\"".dbhcms_f_dict('save', true)."\" border=\"0\"></td></form>";
			$news_params .= "<td align=\"center\" width=\"20\"><a href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_NEWS, 'newsBePart' => 'parameters', 'newsDeleteParam' => $row['nwep_name']))."\" onclick=\" return confirm('".dbhcms_f_dict('dbhcms_msg_askdeleteitem', true)."'); \">".dbhcms_f_get_icon('edit-delete', dbhcms_f_dict('delete', true), 1)."</a></td></tr>";
			$i++;
			
		}
	}

	dbhcms_p_add_string('newsParameters', $news_params);
	dbhcms_p_add_string('newsParamTypes', dbhcms_f_value_to_input('newsparam_insert_type', '', DBHCMS_C_DT_DATATYPE, 'newsparam_new'));

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>