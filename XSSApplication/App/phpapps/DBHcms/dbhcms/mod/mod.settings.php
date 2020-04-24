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
#  FILENAME                                                                                 #
#  =============================                                                            #
#  mod.settings.php                                                                         #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Module to edit the main system configuration parameters.                                 #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  CHANGES                                                                                  #
#  =============================                                                            #
#                                                                                           #
#  05.06.2007:                                                                              #
#  -----------                                                                              #
#  Paramater "availableExtensions" hidden. Extension manager sets now this parameter.       #
#                                                                                           #
#  28.10.2005:                                                                              #
#  -----------                                                                              #
#  File created                                                                             #
#                                                                                           #
#############################################################################################
# $Id: mod.settings.php 70 2007-09-20 05:24:27Z drbenhur $                                   #
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
#  SAVE SETTINGS                                                                            #
#############################################################################################

	if (isset($_POST['dbhcms_settings'])) {
		if ($_POST['dbhcms_settings'] == 'save' ) {
			$result = mysql_query("	SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_CONFIG);
			$action_result = '<div style="color: #076619; font-weight: bold;">'.dbhcms_f_dict('dbhcms_msg_settingssaved', true).'</div>';
			while ($row = mysql_fetch_array($result)) {
				# "availableExtensions" is set by the extension manager
				if (($row['cnfg_id'] != 'availableExtensions') || ($GLOBALS['DBHCMS']['CONFIG']['CORE']['debug'])) {
					mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_CONFIG." SET cnfg_value = '".dbhcms_f_input_to_dbvalue($row['cnfg_id'], $row['cnfg_type'])."' WHERE cnfg_id like '".$row['cnfg_id']."'") 
						or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - '.dbhcms_f_dict('dbhcms_msg_settingsnotsaved', true).'</div>';
				}
			}
			dbhcms_p_dict_add_missing_vals();
		}
	}

#############################################################################################
#  LOAD AND SHOW SETTINGS                                                                   #
#############################################################################################

	$i = 0;
	$dbhcms_settings = '';
	$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_CONFIG);
	while ($row = mysql_fetch_array($result)) {
		
		 # "availableExtensions" is set by the extension manager
		if (($row['cnfg_id'] != 'availableExtensions') || ($GLOBALS['DBHCMS']['CONFIG']['CORE']['debug'])) {
			if ($i & 1) { 
				$dbhcms_settings .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCD."\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCD."'\">"; 
			} else { 
				$dbhcms_settings .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCL."\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCL."'\">"; 
			}
			$dbhcms_settings .= "<td align=\"right\" width=\"200\"><strong>".$row['cnfg_id']." : </strong></td>";
			$dbhcms_settings .= "<td align=\"left\" width=\"250\">".dbhcms_f_dbvalue_to_input($row['cnfg_id'], $row['cnfg_value'], $row['cnfg_type'], 'dbhcms_edit_settings', 'width: 250px;')."</td>";
			if (isset($GLOBALS['DBHCMS']['DICT']['BE'][$row['cnfg_decription']])) {
				$dbhcms_settings .= "<td align=\"left\">".$GLOBALS['DBHCMS']['DICT']['BE'][$row['cnfg_decription']]."</td></tr>";
			} else {
				$dbhcms_settings .= "<td align=\"left\">".$row['cnfg_decription']."</td></tr>";
			}
			$i++;
		}
	}

	dbhcms_p_add_string('dbhcms_settings', $dbhcms_settings);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>