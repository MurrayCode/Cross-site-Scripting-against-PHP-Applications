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
# $Id: ext.news.editentry.php 61 2007-02-01 14:17:59Z kaisven $                             #
#############################################################################################

#############################################################################################
#  BE IMPLEMENTATION - EDIT ARTICLES                                                        #
#############################################################################################

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# ACTIONS                                                                  #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	if (isset($_POST['todo'])) {
		### NEW ARTICLE ###
		if ($_POST['todo'] == 'newsNewEntry') {
			
			$action_result = '<div style="color: #076619; font-weight: bold;">New entry has been saved.</div>';
			
			mysql_query("	INSERT INTO 
								".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entries 
									(
										nwen_domn_id, 
										nwen_page_id, 
										nwen_userlevel, 
										nwen_date
									) 
							VALUES 
									(
										'".dbhcms_f_input_to_dbvalue('nwen_domn_id', DBHCMS_C_DT_DOMAIN)."', 
										'".dbhcms_f_input_to_dbvalue('nwen_page_id', DBHCMS_C_DT_PAGE)."', 
										'".dbhcms_f_input_to_dbvalue('nwen_userlevel', DBHCMS_C_DT_USERLEVEL)."', 
										'".dbhcms_f_input_to_dbvalue('nwen_date', DBHCMS_C_DT_DATETIME)."'
									);
						
						") or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - New entry could not be saved.</div>';
			
			$GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsEditEntry'] = mysql_insert_id();
			
			news_p_add_missing_entry_vals();
			
		### SAVE ARTICLE ###
		} else if ($_POST['todo'] == 'newsSaveEntry') {
			
			$action_result = '<div style="color: #076619; font-weight: bold;">Overall settings have been saved.</div>';
			mysql_query("	UPDATE 
								".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entries 
							SET 
								`nwen_domn_id` = '".dbhcms_f_input_to_dbvalue('nwen_domn_id', DBHCMS_C_DT_DOMAIN)."', 
								`nwen_page_id` = '".dbhcms_f_input_to_dbvalue('nwen_page_id', DBHCMS_C_DT_PAGE)."', 
								`nwen_userlevel` = '".dbhcms_f_input_to_dbvalue('nwen_userlevel', DBHCMS_C_DT_USERLEVEL)."', 
								`nwen_date` = '".dbhcms_f_input_to_dbvalue('nwen_date', DBHCMS_C_DT_DATETIME)."' 
							WHERE 
								nwen_id = ".dbhcms_f_input_to_dbvalue('nwen_id', DBHCMS_C_DT_INTEGER)
						
						) or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Overall settings could not be saved.</div>';
			
		### SAVE ARTICLE LANGUAGE ###
		} else if ($_POST['todo'] == 'newsSaveEntryLang') {
			
			$action_result = '<div style="color: #076619; font-weight: bold;">Settings for "'.strtoupper($GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsEntryPart']).'" have been saved.</div>';
			$result_entryvals = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entriesvals, ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entriesprms WHERE nwev_name = nwep_name AND nwev_nwen_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsEditEntry']." AND nwev_lang LIKE '".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsEntryPart']."'");
			while ($row_entryvals = mysql_fetch_array($result_entryvals)) {
				mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entriesvals SET nwev_value = '".dbhcms_f_input_to_dbvalue($row_entryvals['nwev_name'], $row_entryvals['nwep_type'])."' WHERE nwev_name LIKE '".$row_entryvals['nwev_name']."' AND nwev_nwen_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsEditEntry']." AND nwev_lang LIKE '".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsEntryPart']."'")
					or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Settings for "'.strtoupper($GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsEntryPart']).'" could not be saved.</div>';
			}
			
		}
	} else {
		### DELETE ARTICLE ###
		if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsDeleteEntry'])) {
			$action_result = news_f_delete_entry($GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsDeleteEntry']);
		}
	}

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# NEW ARTICLE                                                              #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsNewEntry'])) {
		
		dbhcms_p_add_template_ext('newsContent', 'news.entries.edit.tpl', 'news');
		
		$entry_form = '<form method="post" action="'.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_NEWS, 'newsBePart' => 'entries')).'">
							<input type="hidden" name="todo" value="newsNewEntry">
									<tr bgcolor="#DEDEDE">
										<td align="right" width="150"><strong>Domain : </strong></td>
										<td align="center" width="202">'.dbhcms_f_value_to_input('nwen_domn_id', $GLOBALS['DBHCMS']['DID'], DBHCMS_C_DT_DOMAIN, '', 'width:204px;').'</td>
										<td></td>
									</tr>
									<tr bgcolor="#F0F0F0">
										<td align="right" width="150"><strong>Page : </strong></td>
										<td align="center" width="202">'.dbhcms_f_value_to_input('nwen_page_id', 0, DBHCMS_C_DT_PAGE, '', 'width:204px;').'</td>
										<td></td>
									</tr>
								<tr bgcolor="#DEDEDE">
									<td align="right" width="150"><strong>User Level : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('nwen_userlevel', '', DBHCMS_C_DT_USERLEVEL, '', 'width:206px;').'</td>
									<td></td>
								</tr>
								<tr bgcolor="#F0F0F0">
									<td align="right" width="150"><strong>Date : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('nwen_date', mktime(), DBHCMS_C_DT_DATETIME, '', 'width:204px;').'</td>
									<td></td>
								</tr>
							</table>
							<table cellpadding="2" cellspacing="1" border="0" width="100%" align="center">
								<tr>
									<td>
										<br>
										<input type="submit" value=" '.$GLOBALS['DBHCMS']['DICT']['BE']['save'].' "> 
									</td>
								</tr>
						  </form>';
		
		dbhcms_p_add_string('newsEntryParams', $entry_form);

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# EDIT ARTICLE                                                             #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	} elseif (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsEditEntry'])) {
		
		dbhcms_p_add_template_ext('newsContent', 'news.entries.edit.tpl', 'news');
		
		$news_langs = array('overall');
		foreach (news_f_get_config_param('languages') as $tmvalue) {
			array_push($news_langs, $tmvalue);
		}
		
		if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsEntryPart'])) { 
			$entry_part = $GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsEntryPart']; 
		} else { 
			$entry_part = 'overall'; 
		}
		
		$entry_tabs = '';
		foreach ($news_langs as $tmvalue) {
			
			if ($tmvalue == 'overall') {
				$cap = dbhcms_f_dict('article', true);
			} else {
				$cap = $tmvalue.' ('.dbhcms_f_dict($tmvalue, true).')';
			}
			
			if ($entry_part == $tmvalue) {
				$entry_tabs .= '	<td>
										<div class="tab_act">
											&nbsp;&nbsp; <a href="'.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_NEWS, 'newsBePart' => 'entries', 'newsEditEntry' => $GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsEditEntry'], 'newsEntryPart' => $tmvalue)).'"> '.$cap.' </a> &nbsp;&nbsp;
										</div>
									</td><td width="5"></td>';
			} else {
				$entry_tabs .= '	<td>
										<div class="tab_no"> 
											&nbsp;&nbsp; <a href="'.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_NEWS, 'newsBePart' => 'entries', 'newsEditEntry' => $GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsEditEntry'], 'newsEntryPart' => $tmvalue)).'"> '.$cap.' </a> &nbsp;&nbsp;
										</div>
									</td><td width="5"></td>';
			}
		}
		
		dbhcms_p_add_string('newsEntryTabs', $entry_tabs);
		
		
		
		if ($entry_part == 'overall') {
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# EDIT ARTICLE OVERALL SETTINGS                                            #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
			$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entries WHERE nwen_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsEditEntry']);
			$row = mysql_fetch_array($result);
			
			$entry_form = '<form method="post" action="'.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_NEWS, 'newsBePart' => 'entries', 'newsEditEntry' => $GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsEditEntry'], 'newsEntryPart' => $entry_part)).'" method="post" name="news_overall">
								<input type="hidden" name="nwen_id" value="'.$GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsEditEntry'].'">
								<input type="hidden" name="todo" value="newsSaveEntry">
										<tr bgcolor="#DEDEDE">
											<td align="right" width="150"><strong>Domain : </strong></td>
											<td align="center" width="202">'.dbhcms_f_dbvalue_to_input('nwen_domn_id', $row['nwen_domn_id'], DBHCMS_C_DT_DOMAIN, 'news_overall', 'width:204px;').'</td>
											<td></td>
										</tr>
										<tr bgcolor="#F0F0F0">
											<td align="right" width="150"><strong>Page : </strong></td>
											<td align="center" width="202">'.dbhcms_f_dbvalue_to_input('nwen_page_id', $row['nwen_page_id'], DBHCMS_C_DT_PAGE, 'news_overall', 'width:204px;').'</td>
											<td></td>
										</tr>
									<tr bgcolor="#DEDEDE">
										<td align="right" width="150"><strong>User Level : </strong></td>
										<td align="center" width="202">'.dbhcms_f_dbvalue_to_input('nwen_userlevel', $row['nwen_userlevel'], DBHCMS_C_DT_USERLEVEL, 'news_overall', 'width:206px;').'</td>
										<td></td>
									</tr>
									<tr bgcolor="#F0F0F0">
										<td align="right" width="150"><strong>Date : </strong></td>
										<td align="center" width="202">'.dbhcms_f_dbvalue_to_input('nwen_date', $row['nwen_date'], DBHCMS_C_DT_DATETIME, 'news_overall', 'width:204px;').'</td>
										<td></td>
									</tr>
								</table>
								<table cellpadding="2" cellspacing="1" border="0" width="100%" align="center">
									<tr>
										<td>  
											<br>
											<input type="submit" value=" '.$GLOBALS['DBHCMS']['DICT']['BE']['save'].' "> 
										</td>
									</tr>
							  </form>';
		
		} else {
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# EDIT ARTICLE USER DEFINED SETTINGS                                       #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
				$entry_form = '';
				
				$result_entryvals = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entriesvals, ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entriesprms WHERE nwev_name = nwep_name AND nwev_nwen_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsEditEntry']." AND nwev_lang LIKE '".$entry_part."'");
				$i = 0;
				while ($row_entryvals = mysql_fetch_array($result_entryvals)) {
					if ($i & 1) { $entry_form .= "<tr bgcolor=\"#F0F0F0\">"; } else { $entry_form .= "<tr bgcolor=\"#DEDEDE\">"; }
					$entry_form .= "<td align=\"right\" valign=\"top\" width=\"200\"><strong>".$row_entryvals['nwev_name']." :</strong></td>";
					$entry_form .= "<td align=\"left\" valign=\"top\" width=\"202\">".dbhcms_f_dbvalue_to_input($row_entryvals['nwev_name'], $row_entryvals['nwev_value'], $row_entryvals['nwep_type'], 'news_lang', 'width:204px;')."</td><td>".$row_entryvals['nwep_description']."</td></tr>";
					$i++;
				}
				$entry_form = '<form action="'.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_NEWS, 'newsBePart' => 'entries', 'newsEditEntry' => $GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsEditEntry'], 'newsEntryPart' => $entry_part)).'" method="post" name="news_lang">
								<input type="hidden" name="todo" value="newsSaveEntryLang">
								<input type="hidden" name="nwen_id" value="'.$GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsEditEntry'].'">
								'.$entry_form.'
							</table>
							<table cellpadding="2" cellspacing="1" border="0" width="100%" align="center">
								<tr>
									<td>  
										<br>
										<input type="submit" value=" '.$GLOBALS['DBHCMS']['DICT']['BE']['save'].' "> 
									</td>
								</tr>
							  </form>';
		
		}
		
		dbhcms_p_add_string('newsEntryParams', $entry_form);

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# SHOW NEWS ARTICLES                                                       #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	} else {
		
		dbhcms_p_add_template_ext('newsContent', 'news.entries.tpl', 'news');
		
		$i = 0;
		$news_entries = '';
		$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entries ORDER BY nwen_date DESC");
		while ($row = mysql_fetch_array($result)) {
			
			if ($i & 1) { 
				$news_entries .= "<tr bgcolor=\"#F0F0F0\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '#F0F0F0'\">"; 
			} else { 
				$news_entries .= "<tr bgcolor=\"#DEDEDE\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '#DEDEDE'\">"; 
			}
			
			$news_entries .= "<td align=\"center\" valign=\"top\">".$row['nwen_id']."</td>";
			$news_entries .= "<td align=\"left\" valign=\"top\"><strong>".news_f_get_entry_param($row['nwen_id'], 'title', $_SESSION['DBHCMSDATA']['LANG']['useLanguage'])."</strong></td>";
			$news_entries .= "<td align=\"center\" valign=\"top\" width=\"50\"><a href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_NEWS, 'newsBePart' => 'entries', 'newsEditEntry' => $row['nwen_id']))."\">".dbhcms_f_get_icon('document-properties', dbhcms_f_dict('edit', true), 1)."</a></td>";
			$news_entries .= "<td align=\"center\" valign=\"top\" width=\"50\"><a onclick=\" return confirm('".dbhcms_f_dict('dbhcms_msg_askdeleteitem', true)."'); \" href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_NEWS, 'newsBePart' => 'entries', 'newsDeleteEntry' => $row['nwen_id']))."\">".dbhcms_f_get_icon('edit-delete', dbhcms_f_dict('delete', true), 1)."</a></td>";
			$i++;
		}
		dbhcms_p_add_string('newsEntries', $news_entries);
		dbhcms_p_add_string('newsNewEntryUrl', dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_NEWS, 'newsBePart' => 'entries', 'newsNewEntry' => 'new')));
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>