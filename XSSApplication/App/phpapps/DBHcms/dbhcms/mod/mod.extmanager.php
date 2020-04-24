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
#  mod.extmanager.php                                                                       #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Module to manage the extension. It lists the available extensions, installs them         #
#  and deinstalls them.                                                                     #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  CHANGES                                                                                  #
#  =============================                                                            #
#                                                                                           #
#  03.06.2007:                                                                              #
#  -----------                                                                              #
#  File created                                                                             #
#                                                                                           #
#############################################################################################
# $Id$                                                                                      #
#############################################################################################

#############################################################################################
#  INSTALL EXTENSION                                                                        #
#############################################################################################

	if (isset($_POST['extmanager_install'])) {
		if (!in_array($_POST['extmanager_install'], $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['availableExtensions'])) {
			# Get SQL
			$dbhcms_database_sql = array('EXT' => array());
			define('DBHCMS_C_EXT_SETUP', 'INST');
			define('DBHCMS_C_INST_DB_PREFIX', $GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']);
			require_once($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].$_POST['extmanager_install'].'/ext.'.$_POST['extmanager_install'].'.inst.php');
			# Execute SQL
			$iserror = false;
			foreach ($dbhcms_database_sql['EXT'] as $ext => $tables) {
				foreach ($tables as $sql) {
					if (!mysql_query($sql)) {
						$action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Could not install extension "'.strtoupper($ext).'".</div><strong>SQL Error: </strong>'.mysql_error();
						$iserror = true;
						break;
					}
				}
			}
			# Set extension as installed
			if (!$iserror) {
				# Get actual extensions
				$inst_xtensions = '';
				foreach ($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['availableExtensions'] as $ext) {
					$inst_xtensions .= $ext.';';
				}
				# Add new extension
				$inst_xtensions .= $_POST['extmanager_install'];
				# Register extension
				mysql_query("	UPDATE 
									".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_CONFIG." 
								SET 
									cnfg_value = '".$inst_xtensions."' 
								WHERE 
									cnfg_id like 'availableExtensions';") or dbhcms_p_error('Could not register extension "'.strtoupper($_POST['extmanager_install']).'". SQL-Error: '.mysql_error(), true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
				
				# Reload page
				header("Location: ".dbhcms_f_get_url_from_pid($GLOBALS['DBHCMS']['PID']));
				exit;
			}
		}
	}

#############################################################################################
#  UNINSTALL EXTENSION                                                                      #
#############################################################################################

	if (isset($_POST['extmanager_uninstall'])) {
		if (in_array($_POST['extmanager_uninstall'], $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['availableExtensions'])) {
			# Get SQL
			$dbhcms_database_sql = array('EXT' => array());
			define('DBHCMS_C_EXT_SETUP', 'DEINST');
			define('DBHCMS_C_INST_DB_PREFIX', $GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']);
			require_once($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].$_POST['extmanager_uninstall'].'/ext.'.$_POST['extmanager_uninstall'].'.inst.php');
			# Execute SQL
			$iserror = false;
			foreach ($dbhcms_database_sql['EXT'] as $ext => $tables) {
				foreach ($tables as $sql) {
					if (!mysql_query($sql)) {
						$action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Could not deinstall extension "'.strtoupper($ext).'".</div><strong>SQL Error: </strong>'.mysql_error();
						$iserror = true;
						break;
					}
				}
			}
			# Set extension as uninstalled
			if (!$iserror) {
				# Get actual extensions and extract deinstalled extension
				$inst_xtensions = '';
				foreach ($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['availableExtensions'] as $ext) {
					if ($ext != $_POST['extmanager_uninstall']) {
						$inst_xtensions .= $ext.';';
					}
				}
				# Unregister extension
				mysql_query("	UPDATE 
									".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_CONFIG." 
								SET 
									cnfg_value = '".$inst_xtensions."' 
								WHERE 
									cnfg_id like 'availableExtensions';") or dbhcms_p_error('Could not unregister extension "'.strtoupper($_POST['extmanager_uninstall']).'". SQL-Error: '.mysql_error(), true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
				
				# Reload page
				header("Location: ".dbhcms_f_get_url_from_pid($GLOBALS['DBHCMS']['PID']));
				exit;
			}
		}
	}

#############################################################################################
#  AVALIABLE EXTENSIONS                                                                     #
#############################################################################################

	$avaliable_extensions = array();
	$objects = dbhcms_f_get_dirobj($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'], true);
	
	foreach ($objects as $item) {
 		$res = substr(substr($item['path'], strlen($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory']), strlen($item['path'])), 2);
		if ($item['kind'] == 'dir') {
			if (is_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].$item['name'].'/ext.'.$item['name'].'.gl.php')) {
				if (is_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].$item['name'].'/ext.'.$item['name'].'.fe.php')) {
					if (is_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].$item['name'].'/ext.'.$item['name'].'.be.php')) {
						# Found avaliable extension in directory
						array_push($avaliable_extensions, $item['name']);
						# If extension not yet installed then load global module
						if (!in_array($item['name'], $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['availableExtensions'])) {
							array_push($GLOBALS['DBHCMS']['STRUCT']['EXT'], $item['name']);
							dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['CONFIG']['EXT'], $item['name']);
							require_once($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].$item['name'].'/ext.'.$item['name'].'.gl.php');
						}
					}
				}
			}
		}
	}

	$dbhcms_extensions = '';
	$i = 0;

	foreach ($avaliable_extensions as $extension) {
		
		if ($i & 1) { 
			$dbhcms_extensions .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCD."\" onmouseover=\"this.bgColor = '".DBHCMS_ADMIN_C_RCH."'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCD."'\">"; 
		} else { 
			$dbhcms_extensions .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCL."\" onmouseover=\"this.bgColor = '".DBHCMS_ADMIN_C_RCH."'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCL."'\">"; 
		}
		
		$icon = dbhcms_f_get_icon($GLOBALS['DBHCMS']['CONFIG']['EXT'][$extension]['icon'], $GLOBALS['DBHCMS']['CONFIG']['EXT'][$extension]['title'], 1);
		if ($icon == '') {
			$icon = dbhcms_f_get_icon('application-x-executable', $GLOBALS['DBHCMS']['CONFIG']['EXT'][$extension]['title'], 1);
		}
		
		$dbhcms_extensions .= '<td align="center" width="20">'.$icon.'</td>';
		
		$dbhcms_extensions .= "<td align=\"left\" valign=\"top\"><b>".$GLOBALS['DBHCMS']['CONFIG']['EXT'][$extension]['title']."</b></td>";
		$dbhcms_extensions .= "<td align=\"left\" valign=\"top\">".$GLOBALS['DBHCMS']['CONFIG']['EXT'][$extension]['version']."</td>";
		$dbhcms_extensions .= "<td valign=\"top\">".$GLOBALS['DBHCMS']['CONFIG']['EXT'][$extension]['description']."</td>";
		
		if (in_array($extension, $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['availableExtensions'])) {
			$dbhcms_extensions .= '<td align="left" width="60">&nbsp;'.dbhcms_f_get_icon('applications-system', $GLOBALS['DBHCMS']['DICT']['BE']['yes'], 1).' <strong><font color="#076619">'.$GLOBALS['DBHCMS']['DICT']['BE']['yes'].'</font></strong></td>';
			$dbhcms_extensions .= '	<form onsubmit="return confirm(\'Uninstalling an extension will delete all its related data tables.\nYou will loose all configurations for the extension >>'.$GLOBALS['DBHCMS']['CONFIG']['EXT'][$extension]['title'].'<<.\nAre you sure you want to continue?\');" method="post"><td width="110" align="center">
									  <input type="hidden" name="extmanager_uninstall" value="'.$extension.'" />
									  <input type="submit" style="width:100px;" value="'.$GLOBALS['DBHCMS']['DICT']['BE']['uninstall'].'" />
								    </td></form></tr>';
		} else {
			$dbhcms_extensions .= '<td align="left" width="60">&nbsp;'.dbhcms_f_get_icon('emblem-system', $GLOBALS['DBHCMS']['DICT']['BE']['not'], 1).' <strong><font color="#FF0000">'.$GLOBALS['DBHCMS']['DICT']['BE']['not'].'</font></strong></td>';
			$dbhcms_extensions .= '	<form method="post"><td width="110" align="center">
									  <input type="hidden" name="extmanager_install" value="'.$extension.'" />
									  <input type="submit" style="width:100px;" value="'.$GLOBALS['DBHCMS']['DICT']['BE']['install'].'" />
								    </td></form></tr>';
		}
		
		$dbhcms_extensions .= '</tr>';
		
		$i = ($i + 1);
		
	}

	dbhcms_p_add_string('dbhcms_extensions', $dbhcms_extensions);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>