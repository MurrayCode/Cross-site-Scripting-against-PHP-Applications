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
#  photoalbum                                                                               #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  A photoalbum with userlevel, picture comments, album rating and picture rating           #
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
# $Id: ext.photoalbum.parameters.php 61 2007-02-01 14:17:59Z kaisven $                      #
#############################################################################################

#############################################################################################
#  BE IMPLEMENTATION - PARAMETERS                                                           #
#############################################################################################

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# ACTIONS                                                                  #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	if (isset($_POST['todo'])) {
		### NEW ALBUM PARAMETER ###
		if ($_POST['todo'] == 'photoalbumNewParam') {
			$action_result = '<div style="color: #076619; font-weight: bold;">Album parameter has been inserted.</div>';
			if (mysql_num_rows(mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsprms WHERE upper(paap_name) LIKE upper('".trim($_POST['albumparam_insert_name'])."')")) > 0 ) {
				$action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Parameter allready exists.</div>';
			} else {
				mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsprms (`paap_type`, `paap_name`, `paap_description`) VALUES ('".$_POST['albumparam_insert_type']."', '".$_POST['albumparam_insert_name']."', '".$_POST['albumparam_insert_description']."')")
					or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Album parameter could not be saved.</div>';
				photoalbum_p_add_missing_album_vals();
			}
		### SAVE ALBUM PARAMETER ###
		} else if ($_POST['todo'] == 'photoalbumSaveParam') {
			$action_result = '<div style="color: #076619; font-weight: bold;">Album parameter has been saved.</div>';
			mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsprms SET `paap_type` = '".$_POST['paap_type']."', `paap_description` = '".$_POST['paap_description']."' WHERE paap_name LIKE '".$_POST['paap_name']."'")
				or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Album parameter could not be saved.</div>';
		}
	} elseif (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumDeleteParam'])) {
		$action_result = '<div style="color: #076619; font-weight: bold;">Album parameter has been deleted.</div>';
		mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsprms WHERE paap_name LIKE '".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumDeleteParam']."'")
			or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Album parameter could not be deleted.</div>';
		mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsvals WHERE paav_name LIKE '".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumDeleteParam']."'")
			or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Album parameter could not be deleted.</div>';
	}

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# PARAMETERS                                                               #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	$photoalbum_params = '';
	$i = 0;
	$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsprms");
	while ($row = mysql_fetch_assoc($result)) {
		
		if ($row['paap_name'] != 'title') {
			
			if ($i & 1) { 
				$photoalbum_params .= "<tr bgcolor=\"#F0F0F0\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '#F0F0F0'\">"; 
			} else { 
				$photoalbum_params .= "<tr bgcolor=\"#DEDEDE\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '#DEDEDE'\">"; 
			}
			
			$photoalbum_params .= '<form method="post" name="photoalbumparam_edit"><input type="hidden" name="todo" value="photoalbumSaveParam"><input type="hidden" name="paap_name" value="'.$row['paap_name'].'">';
			$photoalbum_params .= '<td><strong>'.$row['paap_name'].'</strong></td>';
			$photoalbum_params .= '<td>'.dbhcms_f_dbvalue_to_input('paap_type', $row['paap_type'], DBHCMS_C_DT_DATATYPE, 'photoalbumparam_edit','width:100%;').'</td><td align=\"center\"><input type="text" name="paap_description" value="'.$row['paap_description'].'" style="width:99%;"></td>';
			$photoalbum_params .= "<td align=\"center\" width=\"20\"><input type=\"image\" style=\"cursor: pointer; border-width: 0px;\" src=\"".$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory']."icons/small/media-floppy.png\" width=\"16\" height=\"16\" title=\"".dbhcms_f_dict('save', true)."\" border=\"0\"></td></form>";
			$photoalbum_params .= "<td align=\"center\" width=\"20\"><a href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_PHOTOALBUM, 'photoalbumBePart' => 'parameters', 'photoalbumDeleteParam' => $row['paap_name']))."\" onclick=\" return confirm('".dbhcms_f_dict('dbhcms_msg_askdeleteitem', true)."'); \">".dbhcms_f_get_icon('edit-delete', dbhcms_f_dict('delete', true), 1)."</a></td></tr>";
			$i++;
			
		}
	}

	dbhcms_p_add_string('photoalbumParameters', $photoalbum_params);
	dbhcms_p_add_string('photoalbumParamTypes', dbhcms_f_value_to_input('albumparam_insert_type', '', DBHCMS_C_DT_DATATYPE, 'photoalbumparam_new'));

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>