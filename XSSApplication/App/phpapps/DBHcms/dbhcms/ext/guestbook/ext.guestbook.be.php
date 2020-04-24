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
#  guestbook                                                                                #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  A guestbook                                                                              #
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
# $Id: ext.guestbook.be.php 61 2007-02-01 14:17:59Z kaisven $                               #
#############################################################################################

	dbhcms_p_add_string('ext_name', $ext_title);

#############################################################################################
#  BE IMPLEMENTATION                                                                        #
#############################################################################################

	if (isset($_POST['todo'])) {
		if ($_POST['todo'] == 'guestbook_save_config') {
			$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_guestbook_config");
			$action_result = '<div style="color: #076619; font-weight: bold;">Settings have been saved.</div>';
			while ($row = mysql_fetch_array($result)) {
				mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_guestbook_config SET gbcg_value = '".dbhcms_f_input_to_dbvalue($row['gbcg_id'], $row['gbcg_type'])."' WHERE gbcg_id like '".$row['gbcg_id']."'") 
					or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Settings could not be saved.</div>';
			}
		}
	}

	$guestbook_settings = '<h2>'.$GLOBALS['DBHCMS']['DICT']['BE']['settings'].'</h2><form method="post" name="guestbook_config"><input type="hidden" name="todo" value="guestbook_save_config"><div class="box"><table cellpadding="2" cellspacing="1" border="0" width="100%">';
	$guestbook_settings .= '<tr><td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap">'.$GLOBALS['DBHCMS']['DICT']['BE']['parameter'].'</td><td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap">'.$GLOBALS['DBHCMS']['DICT']['BE']['value'].'</td><td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap" height="18">'.$GLOBALS['DBHCMS']['DICT']['BE']['description'].'</td></tr>';
	$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_guestbook_config");
	$i = 0;
	while ($row = mysql_fetch_array($result)) {
		
		if ($i & 1) { 
			$guestbook_settings .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#DEDEDE\'" bgcolor="#DEDEDE">'; 
		} else { 
			$guestbook_settings .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#F0F0F0\'" bgcolor="#F0F0F0">'; 
		} 
		
		$guestbook_settings .= "<td align=\"right\" valign=\"top\" width=\"200\"><strong>".$row['gbcg_id'].":</strong></td><td align=\"left\" valign=\"top\" width=\"200\">".dbhcms_f_dbvalue_to_input($row['gbcg_id'], $row['gbcg_value'], $row['gbcg_type'], 'guestbook_config', 'width: 200px;')."</td><td valign=\"top\">".$row['gbcg_description']."</td></tr>";
		$i++;
	}
	$guestbook_settings .= '</table></div><br><input type="submit" value=" '.$GLOBALS['DBHCMS']['DICT']['BE']['save'].' "></form>';

	dbhcms_p_add_string('ext_content', $guestbook_settings);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>