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
# $Id: ext.contact.be.msg.php 61 2007-02-01 14:17:59Z kaisven $                             #
#############################################################################################

#############################################################################################
#  BE IMPLEMENTATION - MESSAGES                                                             #
#############################################################################################

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# ACTIONS                                                                  #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['contactDeleteMessage'])) {
		$action_result = '<div style="color: #076619; font-weight: bold;">Message has been deleted.</div>';
		mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_contact_messages WHERE comg_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['contactDeleteMessage']) 
			or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Message could not be deleted.</div><strong>SQL Error: </strong>'.mysql_error();
	}

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# VIEW MESSAGE                                                             #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	
	if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['contactViewMessage'])) {
		
		$query = "UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_contact_messages SET comg_read = '1' WHERE comg_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['contactViewMessage'];
		mysql_query($query) or dbhcms_p_error('Error executing query', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		
		$query = "SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_contact_messages WHERE comg_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['contactViewMessage'];
		$result = mysql_query($query) or dbhcms_p_error('Error executing query', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		
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
			
			</div><br><input type="submit" value=" << '.$GLOBALS['DBHCMS']['DICT']['BE']['back'].' " onclick=" window.location.href=\''.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_CONTACT, 'contactBePart' => 'messages')).'\'; ">';
		}
		
		dbhcms_p_add_string('contactContent', $contact_message_view);
		
		
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# MESSAGE LISTING                                                          #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
	} else {
		
		$contact_messages = '<h2>'.$GLOBALS['DBHCMS']['DICT']['BE']['messages'].':</h2><div class="box"><table cellpadding="2" cellspacing="1" border="0" width="100%">';
		$contact_messages .= '<tr><td align="center" background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap" width="20">'.dbhcms_f_get_icon('email').'</td><td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap">'.$GLOBALS['DBHCMS']['DICT']['BE']['name'].'</td><td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap">'.$GLOBALS['DBHCMS']['DICT']['BE']['email'].'</td><td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap">'.$GLOBALS['DBHCMS']['DICT']['BE']['date'].'</td><td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap" width="60" colspan="2">'.$GLOBALS['DBHCMS']['DICT']['BE']['actions'].'</td></tr>';
		$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_contact_messages ORDER BY comg_date DESC");
		$i = 0;
		while ($row = mysql_fetch_array($result)) {
			
			$msg_view_url = dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_CONTACT, 'contactBePart' => 'messages', 'contactViewMessage' => $row['comg_id']));
			$msg_del_url = dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_CONTACT, 'contactBePart' => 'messages', 'contactDeleteMessage' => $row['comg_id']));
			
			if ($i & 1) { 
				$contact_messages .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#DEDEDE\'" bgcolor="#DEDEDE">'; 
			} else { 
				$contact_messages .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#F0F0F0\'" bgcolor="#F0F0F0">'; 
			} 
			
			if ($row['comg_read'] == '1') { 
				$contact_messages .= "<td align=\"center\"><a href=\"".$msg_view_url."\">".dbhcms_f_get_icon('mailopen').'</a></td>';
			} else { 
				$contact_messages .= "<td align=\"center\"><a href=\"".$msg_view_url."\">".dbhcms_f_get_icon('mailclose').'</a></td>';
			} 
			
			$contact_messages .= "<td align=\"left\" valign=\"top\" width=\"200\"><a href=\"".$msg_view_url."\"><strong>".htmlspecialchars($row['comg_name'])."</strong></a></td><td align=\"left\" valign=\"top\">".htmlspecialchars($row['comg_email'])."</td><td valign=\"top\">".htmlspecialchars($row['comg_date'])."</td>";
			$contact_messages .= "<td align=\"center\" valign=\"top\" width=\"30\"><a href=\"".$msg_view_url."\">".dbhcms_f_get_icon('view')."</a></td>";
			$contact_messages .= "<td align=\"center\" valign=\"top\" width=\"30\"><a onclick=\" return confirm('".dbhcms_f_dict('dbhcms_msg_askdeleteitem', true)."'); \" href=\"".$msg_del_url."\">".dbhcms_f_get_icon('edit-delete', dbhcms_f_dict('delete', true), 1)."</a></td></tr>";
			
			$i++; 
			
		}
		$contact_messages .= '</table></div><br>';
		
		dbhcms_p_add_string('contactContent', $contact_messages);
		
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>