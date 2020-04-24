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
# $Id: ext.photoalbum.settings.php 61 2007-02-01 14:17:59Z kaisven $                        #
#############################################################################################

#############################################################################################																							#
#  BE IMPLEMENTATION - SETTINGS                                                             #
#############################################################################################

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# ACTIONS                                                                  #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	if (isset($_POST['todo'])) {
		if ($_POST['todo'] == 'photoalbumSaveSettings') {
			$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_config");
			$action_result = '<div style="color: #076619; font-weight: bold;">'.dbhcms_f_dict('dbhcms_msg_settingssaved', true).'</div>';
			while ($row = mysql_fetch_array($result)) {
				mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_config SET pacg_value = '".dbhcms_f_input_to_dbvalue($row['pacg_id'], $row['pacg_type'])."' WHERE pacg_id like '".$row['pacg_id']."'") 
					or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - '.dbhcms_f_dict('dbhcms_msg_settingsnotsaved', true).'</div>';
			}
			photoalbum_p_add_missing_album_vals();
		}
	}

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# SETTINGS                                                                 #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	$photoalbum_settings = '<form method="post"><input type="hidden" name="todo" value="photoalbumSaveSettings">';
	$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_config");
	$i = 0;
	while ($row = mysql_fetch_array($result)) {
		
		if ($i & 1) { 
			$photoalbum_settings .= "<tr bgcolor=\"#F0F0F0\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '#F0F0F0'\">"; 
		} else { 
			$photoalbum_settings .= "<tr bgcolor=\"#DEDEDE\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '#DEDEDE'\">"; 
		}
		
		$photoalbum_settings .= "	<td align=\"right\" valign=\"top\" width=\"200\">
										<strong>".$row['pacg_id']." :</strong>
									</td>
									<td align=\"center\" valign=\"top\" width=\"202\">
										".dbhcms_f_dbvalue_to_input($row['pacg_id'], $row['pacg_value'], $row['pacg_type'], 'photoalbum_lang', 'width:204px;')."
									</td>
									<td valign=\"top\">
										".$row['pacg_description']."
									</td></tr>";
		$i++;
	}
	$photoalbum_settings .= '</table></div><br><input type="submit" value=" '.$GLOBALS['DBHCMS']['DICT']['BE']['save'].' "></form>';

	dbhcms_p_add_string('photoalbumSettings', $photoalbum_settings);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>