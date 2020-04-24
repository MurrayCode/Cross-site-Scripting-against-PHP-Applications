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
# $Id: ext.contact.be.conf.php 61 2007-02-01 14:17:59Z kaisven $                            #
#############################################################################################

#############################################################################################
#  BE IMPLEMENTATION                                                                        #
#############################################################################################

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# ACTIONS                                                                  #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	# Save settings
	if (isset($_POST['todo'])) {
		if ($_POST['todo'] == 'contact_save_config') {
			$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_contact_config");
			$action_result = '<div style="color: #076619; font-weight: bold;">Settings have been saved.</div>';
			while ($row = mysql_fetch_array($result)) {
				mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_contact_config SET cocg_value = '".dbhcms_f_input_to_dbvalue($row['cocg_id'], $row['cocg_type'])."' WHERE cocg_id like '".$row['cocg_id']."'") 
					or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Settings could not be saved.</div><strong>SQL Error: </strong>'.mysql_error();
			}
		}
	}

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# EDIT SETTINGS                                                            #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	# Show settings
	$contact_settings = '<h2>'.$GLOBALS['DBHCMS']['DICT']['BE']['settings'].':</h2><form method="post" name="dbhcms_ext_contact_settings"><input type="hidden" name="todo" value="contact_save_config"><div class="box"><table cellpadding="2" cellspacing="1" border="0" width="100%">';
	$contact_settings .= '<tr><td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap" width="100">'.$GLOBALS['DBHCMS']['DICT']['BE']['parameter'].'</td><td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap">'.$GLOBALS['DBHCMS']['DICT']['BE']['value'].'</td><td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap">'.$GLOBALS['DBHCMS']['DICT']['BE']['description'].'</td></tr>';
	$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_contact_config");
	$i = 0;
	while ($row = mysql_fetch_array($result)) {
		
		if ($i & 1) { 
			$contact_settings .= "<tr bgcolor=\"#DEDEDE\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '#DEDEDE'\">"; 
		} else { 
			$contact_settings .= "<tr bgcolor=\"#F0F0F0\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '#F0F0F0'\">"; 
		}
		
		$contact_settings .= "<td align=\"right\" width=\"100\"><strong>".$row['cocg_id']." : </strong></td><td align=\"left\" valign=\"top\" width=\"350\">
		
		".dbhcms_f_dbvalue_to_input($row['cocg_id'], $row['cocg_value'], $row['cocg_type'], 'dbhcms_ext_contact_settings', 'width: 350px;')."
		
		</td><td>".$row['cocg_description']."</td></tr>";
		$i++;
	}
	$contact_settings .= '</table></div><br><input type="submit" value=" '.$GLOBALS['DBHCMS']['DICT']['BE']['save'].' "></form>';
	
	dbhcms_p_add_string('contactContent', $contact_settings);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>