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
#  contact                                                                                  #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Contact functions to send and save messages                                              #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  CHANGES                                                                                  #
#  =============================                                                            #
#                                                                                           #
#  17.02.2007:                                                                              #
#  -----------                                                                              #
#  Divided message listing and settings to diferent tabs                                    #
#                                                                                           #
#############################################################################################
# $Id: ext.contact.be.php 68 2007-05-31 20:28:17Z kaisven $                                 #
#############################################################################################

	dbhcms_p_add_string('ext_name', $ext_title);
	dbhcms_p_add_template_ext('ext_content', 'contact.tpl', DBHCMS_C_EXT_CONTACT);

#############################################################################################
#  BE IMPLEMENTATION                                                                        #
#############################################################################################

	$settings_class = 'tab_no';
	$messages_class = 'tab_no';

	if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['contactBePart'])) {
		if ($GLOBALS['DBHCMS']['TEMP']['PARAMS']['contactBePart'] == 'settings') {
			$settings_class = 'tab_act';
			include($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].DBHCMS_C_EXT_CONTACT.'/ext.'.DBHCMS_C_EXT_CONTACT.'.be.conf.php');
		} else {
			$messages_class = 'tab_act';
			include($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].DBHCMS_C_EXT_CONTACT.'/ext.'.DBHCMS_C_EXT_CONTACT.'.be.msg.php');
		}
	} else {
		$messages_class = 'tab_act';
		include($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].DBHCMS_C_EXT_CONTACT.'/ext.'.DBHCMS_C_EXT_CONTACT.'.be.msg.php');
	}

	$contact_tabs = '	<td>
								<div class="'.$messages_class.'"> 
									&nbsp;&nbsp; <a href="'.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_CONTACT, 'contactBePart' => 'messages')).'"> '.$GLOBALS['DBHCMS']['DICT']['BE']['messages'].' </a> &nbsp;&nbsp;
								</div>
							</td>
							<td width="5"></td>
							<td>
								<div class="'.$settings_class.'"> 
									&nbsp;&nbsp; <a href="'.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_CONTACT, 'contactBePart' => 'settings')).'"> '.$GLOBALS['DBHCMS']['DICT']['BE']['settings'].' </a> &nbsp;&nbsp;
								</div>
							</td>
							<td width="5"></td>
							';

	dbhcms_p_add_string('contactTabs', $contact_tabs);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################


/*

































	dbhcms_p_add_string('ext_name', $ext_title);

#############################################################################################
#  BE IMPLEMENTATION                                                                        #
#############################################################################################

	if (isset($_POST['todo'])) {
		if ($_POST['todo'] == 'contact_save_config') {
			$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_contact_config");
			$action_result = '<div style="color: #076619; font-weight: bold;">Settings have been saved.</div>';
			while ($row = mysql_fetch_array($result)) {
				mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_contact_config SET cocg_value = '".dbhcms_f_input_to_dbvalue($row['cocg_id'], $row['cocg_type'])."' WHERE cocg_id like '".$row['cocg_id']."'") 
					or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Settings could not be saved.</div><strong>SQL Error: </strong>'.mysql_error();
			}
		}
	} else if (isset($_GET['contact_deletemessage'])) {
		
		$action_result = '<div style="color: #076619; font-weight: bold;">Message has been deleted.</div>';
		mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_contact_messages WHERE comg_id = ".$_GET['contact_deletemessage']) 
			or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Message could not be deleted.</div><strong>SQL Error: </strong>'.mysql_error();
		
	}

	if (isset($_GET['contact_viewmessage'])) {
		
		mysql_query ("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_contact_messages SET comg_read = '1' WHERE comg_id = ".$_GET['contact_viewmessage']);
		
		$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_contact_messages WHERE comg_id = ".$_GET['contact_viewmessage']);
		if (!($row = mysql_fetch_assoc($result))) {
			$action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Message not found.</div>';
		} else {
			$contact_message_view = '<h2>'.$GLOBALS['DBHCMS']['DICT']['BE']['message'].':</h2><div class="box">
			<table cellpadding="2" cellspacing="1" border="0" width="100%">
				<tr bgcolor="#DEDEDE" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#DEDEDE\'">
					<td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap">'.$GLOBALS['DBHCMS']['DICT']['BE']['parameter'].'</td>
					<td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap">'.$GLOBALS['DBHCMS']['DICT']['BE']['value'].'</td>
				</tr>
				<tr bgcolor="#DEDEDE" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#DEDEDE\'">
					<td align="right" width="200"><strong> '.$GLOBALS['DBHCMS']['DICT']['BE']['name'].' : </strong></td>
					<td>'.dbhcms_f_dbvalue_to_value($row['comg_name'], DBHCMS_C_DT_STRING).'</td>
				</tr>
				<tr bgcolor="#F0F0F0" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#F0F0F0\'">
					<td align="right" width="200"><strong> '.$GLOBALS['DBHCMS']['DICT']['BE']['company'].' : </strong></td>
					<td>'.dbhcms_f_dbvalue_to_value($row['comg_company'], DBHCMS_C_DT_STRING).'</td>
				</tr>
				<tr bgcolor="#DEDEDE" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#DEDEDE\'">
					<td align="right" width="200"><strong> '.$GLOBALS['DBHCMS']['DICT']['BE']['location'].' : </strong></td>
					<td>'.dbhcms_f_dbvalue_to_value($row['comg_location'], DBHCMS_C_DT_STRING).'</td>
				</tr>
				<tr bgcolor="#F0F0F0" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#F0F0F0\'">
					<td align="right" width="200"><strong> '.$GLOBALS['DBHCMS']['DICT']['BE']['email'].' : </strong></td>
					<td>'.dbhcms_f_dbvalue_to_value($row['comg_email'], DBHCMS_C_DT_STRING).'</td>
				</tr>
				<tr bgcolor="#DEDEDE" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#DEDEDE\'">
					<td align="right" width="200"><strong> '.$GLOBALS['DBHCMS']['DICT']['BE']['website'].' : </strong></td>
					<td>'.dbhcms_f_dbvalue_to_value($row['comg_website'], DBHCMS_C_DT_STRING).'</td>
				</tr>
				<tr bgcolor="#F0F0F0" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#F0F0F0\'">
					<td valign="top" align="right" width="200"><strong> '.$GLOBALS['DBHCMS']['DICT']['BE']['text'].' : </strong></td>
					<td>
						'.str_replace("\n", "<br>", dbhcms_f_dbvalue_to_value($row['comg_text'], DBHCMS_C_DT_TEXT)).'
					</td>
				</tr>
			</table>
			
			</div><br><input type="submit" value=" << '.$GLOBALS['DBHCMS']['DICT']['BE']['back'].' " onclick=" window.location.href=\'index.php?dbhcms_pid='.$GLOBALS['DBHCMS']['PID'].'&ext='.$_GET['ext'].'\'; ">';
		}
		
		dbhcms_p_add_string('ext_content', $contact_message_view);
		
	} else {
	
		$contact_settings = '<h2>'.$GLOBALS['DBHCMS']['DICT']['BE']['settings'].':</h2><form method="post" name="dbhcms_ext_contact_settings"><input type="hidden" name="todo" value="contact_save_config"><div class="box"><table cellpadding="2" cellspacing="1" border="0" width="100%">';
		$contact_settings .= '<tr><td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap">'.$GLOBALS['DBHCMS']['DICT']['BE']['parameter'].'</td><td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap">'.$GLOBALS['DBHCMS']['DICT']['BE']['value'].'</td><td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap">'.$GLOBALS['DBHCMS']['DICT']['BE']['description'].'</td></tr>';
		$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_contact_config");
		$i = 0;
		while ($row = mysql_fetch_array($result)) {
			
			if ($i & 1) { 
				$contact_settings .= "<tr bgcolor=\"#DEDEDE\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '#DEDEDE'\">"; 
			} else { 
				$contact_settings .= "<tr bgcolor=\"#F0F0F0\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '#F0F0F0'\">"; 
			}
			
			$contact_settings .= "<td align=\"right\" width=\"200\"><strong>".$row['cocg_id']." : </strong></td><td align=\"left\" valign=\"top\" width=\"250\">
			
			".dbhcms_f_dbvalue_to_input($row['cocg_id'], $row['cocg_value'], $row['cocg_type'], 'dbhcms_ext_contact_settings', 'width: 250px;')."
			
			</td><td>".$row['cocg_description']."</td></tr>";
			$i++;
		}
		$contact_settings .= '</table></div><br><input type="submit" value=" '.$GLOBALS['DBHCMS']['DICT']['BE']['save'].' "></form>';
		
		$contact_messages = '<h2>'.$GLOBALS['DBHCMS']['DICT']['BE']['messages'].':</h2><div class="box"><table cellpadding="2" cellspacing="1" border="0" width="100%">';
		$contact_messages .= '<tr><td align="center" background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap" width="20">'.dbhcms_f_get_icon('email').'</td><td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap">'.$GLOBALS['DBHCMS']['DICT']['BE']['name'].'</td><td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap">'.$GLOBALS['DBHCMS']['DICT']['BE']['email'].'</td><td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap">'.$GLOBALS['DBHCMS']['DICT']['BE']['date'].'</td><td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap" width="60" colspan="2">'.$GLOBALS['DBHCMS']['DICT']['BE']['actions'].'</td></tr>';
		$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_contact_messages ORDER BY comg_date DESC");
		$i = 0;
		while ($row = mysql_fetch_array($result)) {
			
			if ($i & 1) { 
				$contact_messages .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#DEDEDE\'" bgcolor="#DEDEDE">'; 
			} else { 
				$contact_messages .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#F0F0F0\'" bgcolor="#F0F0F0">'; 
			} 
			
			if ($row['comg_read'] == '1') { 
				$contact_messages .= "<td align=\"center\"><a href=\"index.php?dbhcms_pid=".$GLOBALS['DBHCMS']['PID']."&ext=".$_GET['ext']."&contact_viewmessage=".$row['comg_id']."\">".dbhcms_f_get_icon('mailopen').'</a></td>'; 
			} else { 
				$contact_messages .= "<td align=\"center\"><a href=\"index.php?dbhcms_pid=".$GLOBALS['DBHCMS']['PID']."&ext=".$_GET['ext']."&contact_viewmessage=".$row['comg_id']."\">".dbhcms_f_get_icon('mailclose').'</a></td>'; 
			} 
			
			$contact_messages .= "<td align=\"left\" valign=\"top\" width=\"200\"><a href=\"index.php?dbhcms_pid=".$GLOBALS['DBHCMS']['PID']."&ext=".$_GET['ext']."&contact_viewmessage=".$row['comg_id']."\"><strong>".htmlspecialchars($row['comg_name'])."</strong></a></td><td align=\"left\" valign=\"top\">".htmlspecialchars($row['comg_email'])."</td><td valign=\"top\">".htmlspecialchars($row['comg_date'])."</td>";
			$contact_messages .= "<td align=\"center\" valign=\"top\" width=\"30\"><a href=\"index.php?dbhcms_pid=".$GLOBALS['DBHCMS']['PID']."&ext=".$_GET['ext']."&contact_viewmessage=".$row['comg_id']."\">".dbhcms_f_get_icon('view')."</a></td>";
			$contact_messages .= "<td align=\"center\" valign=\"top\" width=\"30\"><a onclick=\" return confirm('".dbhcms_f_dict('dbhcms_msg_askdeleteitem', true)."'); \" href=\"index.php?dbhcms_pid=".$GLOBALS['DBHCMS']['PID']."&ext=".$_GET['ext']."&contact_deletemessage=".$row['comg_id']."\">".dbhcms_f_get_icon('edit-delete', dbhcms_f_dict('delete', true), 1)."</a></td></tr>";
			
			$i++; 
			
		}
		$contact_messages .= '</table></div><br>';
		
		dbhcms_p_add_string('ext_content', $contact_settings."<br>".$contact_messages);
		
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################
*/
?>