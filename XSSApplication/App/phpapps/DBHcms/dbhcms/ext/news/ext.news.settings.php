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
# $Id: ext.news.settings.php 61 2007-02-01 14:17:59Z kaisven $                              #
#############################################################################################

#############################################################################################
#  BE IMPLEMENTATION - SETTINGS                                                             #
#############################################################################################

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# ACTIONS                                                                  #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	if (isset($_POST['todo'])) {
		if ($_POST['todo'] == 'newsSaveSettings') {
			$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_config");
			$action_result = '<div style="color: #076619; font-weight: bold;">'.dbhcms_f_dict('dbhcms_msg_settingssaved', true).'</div>';
			while ($row = mysql_fetch_array($result)) {
				mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_config SET nwcg_value = '".dbhcms_f_input_to_dbvalue($row['nwcg_id'], $row['nwcg_type'])."' WHERE nwcg_id like '".$row['nwcg_id']."'") 
					or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - '.dbhcms_f_dict('dbhcms_msg_settingsnotsaved', true).'</div>';
			}
			news_p_add_missing_entry_vals ();
		}
	}

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# SETTINGS                                                                 #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	$news_settings = '<form method="post" name="news_settings"><input type="hidden" name="todo" value="newsSaveSettings">';
	$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_config");
	$i = 0;
	while ($row = mysql_fetch_array($result)) {
		
		if ($i & 1) { 
			$news_settings .= "<tr bgcolor=\"#F0F0F0\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '#F0F0F0'\">"; 
		} else { 
			$news_settings .= "<tr bgcolor=\"#DEDEDE\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '#DEDEDE'\">"; 
		}
		
		$news_settings .= "	<td align=\"right\" valign=\"top\" width=\"200\">
								<strong>".$row['nwcg_id']." :</strong>
							</td>
							<td align=\"center\" valign=\"top\" width=\"202\">
								".dbhcms_f_dbvalue_to_input($row['nwcg_id'], $row['nwcg_value'], $row['nwcg_type'], 'news_settings', 'width:204px;')."
							</td>
							<td valign=\"top\">
								".$row['nwcg_description']."
							</td></tr>";
		$i++;
	}
	$news_settings .= '</table></div><br><input type="submit" value=" '.$GLOBALS['DBHCMS']['DICT']['BE']['save'].' "></form>';

	dbhcms_p_add_string('newsSettings', $news_settings);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>