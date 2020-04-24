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
# $Id: mod.dictionary.php 68 2007-05-31 20:28:17Z kaisven $                                 #
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
#	MODULE MOD.DICTIONARY.PHP                                                                 #
#############################################################################################

	$dict_langs = $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['dictionaryLanguages'];

	if (isset($_POST['dict_export'])) {
		$export_value = '';
		$result_name = mysql_query("SELECT dict_name FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DICTIONARY." GROUP BY dict_name ORDER BY dict_name ");
		while ($row_name = mysql_fetch_assoc($result_name)) {
			foreach ($dict_langs as $dict_lang) {
				$row = mysql_fetch_assoc(mysql_query("SELECT dict_value FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DICTIONARY." WHERE dict_name = '".$row_name['dict_name']."' AND dict_lang = '".$dict_lang."'"));
				$export_value .= '&nbsp;&nbsp;&nbsp;&nbsp;'.htmlspecialchars('<dictEntry>').'<br>';
				$export_value .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.htmlspecialchars('<entrySid>'.$row_name['dict_name'].'</entrySid>').'<br>';
				$export_value .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.htmlspecialchars('<entryLang>'.$dict_lang.'</entryLang>').'<br>';
				$export_value .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.htmlspecialchars('<entryValue>'.htmlspecialchars($row['dict_value']).'</entryValue>').'<br>';
				$export_value .= '&nbsp;&nbsp;&nbsp;&nbsp;'.htmlspecialchars('</dictEntry>').'<br>';
			}
		}
		$action_result = htmlspecialchars('<?xml version="1.0" encoding="ISO-8859-1"?>').'<br>&nbsp;&nbsp;'.htmlspecialchars('<dbhcmsDict>').'<br>'.$export_value.'&nbsp;&nbsp;'.htmlspecialchars('</dbhcmsDict>');
	} else if (isset($_POST['dict_import'])) {
		$dict_vals = dbhcms_f_fetch_dict_xml($_POST['dict_import']);
		$value_count = count($dict_vals);
		$insert_count = 0;
		$error_count = 0;
		foreach ($dict_vals as $dict_sid => $dict_lang_vals) {
			foreach ($dict_lang_vals as $dict_lang => $dict_value) {
				if (mysql_num_rows(mysql_query("SELECT dict_value FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DICTIONARY." WHERE dict_name = '".$dict_sid."' AND dict_lang = '".$dict_lang."' ")) == 0) {
					if (mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DICTIONARY." (`dict_name` , `dict_value` , `dict_lang` ) VALUES ( '".$dict_sid."', '".$dict_value."', '".$dict_lang."' ) ")) {
						$insert_count++;
					} else {
						$error_count++;
					}
				}
			}
		}
		$action_result = '<div style="color: #076619; font-weight: bold;">Dictionary imported:</div><br /> &nbsp; - '.$value_count.' values found <br /> &nbsp; - '.$insert_count.' values inserted <br /> &nbsp; - '.$error_count.' errors <br /><br />';
	}

	if (isset($_POST['dict_search'])) {
		$dict_search = 'WHERE (upper(dict_value) LIKE upper("%'.$_POST['dict_search'].'%") OR upper(dict_name) LIKE upper("%'.$_POST['dict_search'].'%")) ';
		$dict_search_params = '&dict_search='.urlencode($_POST['dict_search']);
	} else if (isset($_GET['dict_search'])) {
		$dict_search = 'WHERE (upper(dict_value) LIKE upper("%'.$_GET['dict_search'].'%") OR upper(dict_name) LIKE upper("%'.$_GET['dict_search'].'%")) ';
		$dict_search_params = '&dict_search='.urlencode($_GET['dict_search']);
	} else { 
		$dict_search = ''; 
		$dict_search_params = '';
	}

	if (!$GLOBALS['DBHCMS']['CONFIG']['CORE']['debug']) {
		if ($dict_search == '') {
			$sql_restrict_dbhcms = " WHERE dict_name NOT LIKE 'dbhcms_%' ";
		} else {
			$sql_restrict_dbhcms = " AND dict_name NOT LIKE 'dbhcms_%' ";
		}
	} else {
		$sql_restrict_dbhcms = " ";
	}

	if (isset($_POST['dict_insert'])) {
		$action_result = '<div style="color: #076619; font-weight: bold;">New definition "'.$_POST['dict_insert'].'" was inserted.</div>';
		if (mysql_num_rows(mysql_query("SELECT dict_value FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DICTIONARY." WHERE dict_name = '".$_POST['dict_insert']."' ")) == 0) {
			
			mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DICTIONARY." (`dict_name` , `dict_value` , `dict_lang` ) VALUES ( '".$_POST['dict_insert']."', '".$_POST['dict_insert_value']."', 'en' ) ")
				or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Definition "'.$_POST['dict_insert'].'" could no be inserted.</div>';
			
			if ($_POST['dict_insert_translate'] != '0') {
				$babelfish = new babelfish();
				foreach ($dict_langs as $dict_lang) {
					if ($dict_lang != 'en') {
						mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DICTIONARY." (`dict_name` , `dict_value` , `dict_lang` ) VALUES ( '".$_POST['dict_insert']."', '".$babelfish->translate($_POST['dict_insert_value'], $babelfish->languages[$_POST['dict_insert_translate']], $babelfish->languages[$dict_lang])."', '".$dict_lang."' ) ")
							or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Definition "'.$_POST['dict_insert'].'" could no be inserted.</div>';
					}
				}
			} else {
				foreach ($dict_langs as $dict_lang) {
					if ($dict_lang != 'en') {
						mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DICTIONARY." (`dict_name` , `dict_value` , `dict_lang` ) VALUES ( '".$_POST['dict_insert']."', '".$_POST['dict_insert_value']."', '".$dict_lang."' ) ")
							or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Definition "'.$_POST['dict_insert'].'" could no be inserted.</div>';
					}
				}
			}
		} else {
			$action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Definition "'.$_POST['dict_insert'].'" already exists.</div>';
		}
	}

	if (isset($_POST['dict_save'])) {
		$action_result = '<div style="color: #076619; font-weight: bold;">Definition "'.$_POST['dict_save'].'" was saved.</div>';
		dbhcms_p_dict_add_missing_vals();
		foreach ($dict_langs as $dict_lang) {
			mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DICTIONARY." SET `dict_value` = '".$_POST[$_POST['dict_save'].'_'.$dict_lang]."' WHERE dict_name LIKE '".$_POST['dict_save']."' AND dict_lang = '".$dict_lang."'")
				or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Definition "'.$_POST['dict_save'].'" could not be saved.</div>';
		}
	}

	if (isset($_GET['dict_delete'])) { 
		$action_result = '<div style="color: #076619; font-weight: bold;">Definition "'.$_GET['dict_delete'].'" was deleted.</div>';
		mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DICTIONARY." WHERE dict_name LIKE '".$_GET['dict_delete']."'")
			or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Definition "'.$_GET['dict_delete'].'" could not be deleted.</div>';
	}

	$dict_show_values = '<td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap" height="18">'.$GLOBALS['DBHCMS']['DICT']['BE']['name'].'</td>';
	foreach ($dict_langs as $dict_lang) { $dict_show_values .= '<td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap" height="18">'.$dict_lang.'</td>'; }
	$dict_show_values = '<tr>'.$dict_show_values.'<td colspan="2" width="40" background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap" height="18">'.$GLOBALS['DBHCMS']['DICT']['BE']['actions'].'</td></tr>';
	
	$more = 30;
	
	if (isset($_GET['from'])) {
		$from = $_GET['from'];
	} else {
		$from = 0;
	}
	
	$jumplinktotal = mysql_num_rows(mysql_query("SELECT dict_name FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DICTIONARY." ".$dict_search.$sql_restrict_dbhcms." GROUP BY dict_name ORDER BY dict_name "));
	$query = "SELECT dict_name FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DICTIONARY." ".$dict_search.$sql_restrict_dbhcms." GROUP BY dict_name ORDER BY dict_name LIMIT ".$from." , ".$more;
	
	$jumplink = "";
	
	if ($from >= $more) {
	    $jumplink .= "[<a class=\"jumplink\" href=\"index.php?dbhcms_pid=".$GLOBALS['DBHCMS']['PID'].$dict_search_params."&from=" . ($from - $more) . "\">«</a>]";
	}
	for ($i = 1; ($i * $more) < $jumplinktotal; $i++) {
		$j = $i - 1;
		if (($j * $more) != $from) {
	    	$jumplink .= " [<a class=\"jumplink\" href=\"index.php?dbhcms_pid=".$GLOBALS['DBHCMS']['PID'].$dict_search_params."&from=" . ($j * $more)  . "\">" . $i . '</a>] ';
		} else {
	    	$jumplink .= ' <strong>' . $i . '</strong> ';
		}
	}
	$j = $i - 1;
	if (($j * $more) < $jumplinktotal) {
		if (($j * $more) != $from) {
		    $jumplink .= " [<a class=\"jumplink\" href=\"index.php?dbhcms_pid=".$GLOBALS['DBHCMS']['PID'].$dict_search_params."&from=" . ($j * $more) . "\">" . $i . '</a>] ';
		} else {
	    	$jumplink .= ' <strong>' . $i . '</strong> ';
		}
	}
	if ($jumplinktotal >= ($from + $more)) {
	    $jumplink .= "[<a class=\"jumplink\" href=\"index.php?dbhcms_pid=".$GLOBALS['DBHCMS']['PID'].$dict_search_params."&from=" . ($from + $more) . "\">»</a>]";
	}
	
	dbhcms_p_add_string('jumplinks', $jumplink);
	
	$i=0;
	$result_name = mysql_query($query); #mysql_query("SELECT dict_name FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DICTIONARY." ".$dict_search.$sql_restrict_dbhcms." GROUP BY dict_name ORDER BY dict_name ");
	while ($row_name = mysql_fetch_assoc($result_name)) {
		
		if ($i & 1) { 
			$dict_show_values .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCD."\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCD."'\">"; 
		} else { 
			$dict_show_values .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCL."\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCL."'\">"; 
		}
		
		$dict_show_values .= '<form method="post">';
		if (isset($_POST['dict_search'])) { $dict_show_values .= '<input type="hidden" name="dict_search" value="'.$_POST['dict_search'].'">'; }
		$dict_show_values .= '<input type="hidden" name="dict_save" value="'.$row_name['dict_name'].'"><td align="right"><strong>'.$row_name['dict_name'].':&nbsp;</strong></td>';
		foreach ($dict_langs as $dict_lang) {
			$row = mysql_fetch_assoc(mysql_query("SELECT dict_value FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DICTIONARY." WHERE dict_name = '".$row_name['dict_name']."' AND dict_lang = '".$dict_lang."'"));
			$dict_show_values .= '<td width=\"130\">
			
			
			
			'.dbhcms_f_dbvalue_to_input($row_name['dict_name'].'_'.$dict_lang, $row['dict_value'], DBHCMS_C_DT_TEXT, 'dbhcms_edit_settings', 'width: 200px;').'
			
			</td>';
		}
		$dict_show_values .= "<td align=\"center\" width=\"20\"><input type=\"image\" style=\"cursor: pointer;border-width: 0px\" src=\"".$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory']."icons/small/media-floppy.png\" width=\"16\" height=\"16\" title=\"".dbhcms_f_dict('save', true)."\"></td></form>";
		$dict_show_values .= "<td align=\"center\" width=\"20\"><a href=\"index.php?dbhcms_pid=".$GLOBALS['DBHCMS']['PID']."&dict_delete=".$row_name['dict_name']."\" onclick=\" return confirm('".dbhcms_f_dict('dbhcms_msg_askdeleteitem', true)."');\" >".dbhcms_f_get_icon('edit-delete', dbhcms_f_dict('delete', true), 1)."</a></td></tr>";
		$i++;
	}

	if (isset($_POST['dict_search'])) {
		dbhcms_p_add_string('dict_search_str', $_POST['dict_search']);
	} elseif (isset($_GET['dict_search'])) {
		dbhcms_p_add_string('dict_search_str', $_GET['dict_search']);
	}

	dbhcms_p_add_string('dict_values', $dict_show_values);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>