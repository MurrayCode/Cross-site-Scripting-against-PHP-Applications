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
# $Id: mod.selector.php 60 2007-02-01 13:34:54Z kaisven $                                   #
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
#	MODULE MOD.SELECTOR.PHP                                                                   #
#############################################################################################

	if (!isset($_GET['return_name'])) {
		dbhcms_p_error('GET parameter "return_name" must be set for selector.', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
	}

	if (isset($_GET['data_type'])) {
		if ($_GET['data_type'] == DBHCMS_C_DT_USER) {
			
			dbhcms_p_add_template('nr'.count($GLOBALS['DBHCMS']['STRUCT']['TPL']), 'body.main.tpl'); 
			dbhcms_p_add_template('nr'.count($GLOBALS['DBHCMS']['STRUCT']['TPL']), 'content.selector.tpl');
			
			$dbhcms_users = '';
			$i = 0;
			$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_USERS);
			while ($row = mysql_fetch_assoc($result)){
				if ($i & 1) { 
					$dbhcms_users .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'" bgcolor="'.DBHCMS_ADMIN_C_RCD.'">'; 
				} else { 
					$dbhcms_users .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'" bgcolor="'.DBHCMS_ADMIN_C_RCL.'">'; 
				}
				if ($row['user_sex'] == DBHCMS_C_ST_MALE) {
					$dbhcms_users .= '<td align="center" width="20"><a href="#" onclick="opener.setNewValue(\''.$_GET['return_name'].'\', \''.dbhcms_f_dbvalue_to_value($row['user_login'], DBHCMS_C_DT_STRING).'\', \''.dbhcms_f_dbvalue_to_value($row['user_login'], DBHCMS_C_DT_STRING).'\'); window.close(); return false; ">'.dbhcms_f_get_icon('male').'</a></td>';
				} else {
					$dbhcms_users .= '<td align="center" width="20"><a href="#" onclick="opener.setNewValue(\''.$_GET['return_name'].'\', \''.dbhcms_f_dbvalue_to_value($row['user_login'], DBHCMS_C_DT_STRING).'\', \''.dbhcms_f_dbvalue_to_value($row['user_login'], DBHCMS_C_DT_STRING).'\'); window.close(); return false; ">'.dbhcms_f_get_icon('female').'</a></td>';
				}
				$dbhcms_users .= '<td><a href="#" onclick="opener.setNewValue(\''.$_GET['return_name'].'\', \''.dbhcms_f_dbvalue_to_value($row['user_login'], DBHCMS_C_DT_STRING).'\', \''.dbhcms_f_dbvalue_to_value($row['user_login'], DBHCMS_C_DT_STRING).'\'); window.close(); return false; "><strong>'.dbhcms_f_dbvalue_to_value($row['user_login'], DBHCMS_C_DT_STRING).'</strong></a></td>';
				$dbhcms_users .= '<td>'.dbhcms_f_dbvalue_to_value($row['user_name'], DBHCMS_C_DT_STRING).'</td>';
				$i++;
			}
			
			$dbhcms_users = '	<div class="box" style="width:95%">
									<table cellpadding="2" cellspacing="1" border="0" width="100%">
										<tr>
											<td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap" colspan="2">'.$GLOBALS['DBHCMS']['DICT']['BE']['user'].'</td>
											<td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap">'.$GLOBALS['DBHCMS']['DICT']['BE']['name'].'</td>
										</tr>
										'.$dbhcms_users.'
									</table>
								</div>';
			
			dbhcms_p_add_string('selectorValues', $dbhcms_users);
			
		} else if ($_GET['data_type'] == DBHCMS_C_DT_LANGUAGE) {
			
			dbhcms_p_add_template('nr'.count($GLOBALS['DBHCMS']['STRUCT']['TPL']), 'body.main.tpl'); 
			dbhcms_p_add_template('nr'.count($GLOBALS['DBHCMS']['STRUCT']['TPL']), 'content.selector.tpl'); 
			
			$dbhcms_lang_array = array();
			$input_html = '';
			foreach ($GLOBALS['DBHCMS']['LANGS'] as $tmkey => $tmvalue) { 
				array_push($dbhcms_lang_array, $tmvalue); 
			}
			$dbhcms_lang_array = array_unique($dbhcms_lang_array);
			sort($dbhcms_lang_array);
			
			$dbhcms_languages = '';
			$i = 0;
			foreach($dbhcms_lang_array as $lang) {
				if ($i & 1) { 
					$dbhcms_languages .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'" bgcolor="'.DBHCMS_ADMIN_C_RCD.'">'; 
				} else { 
					$dbhcms_languages .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'" bgcolor="'.DBHCMS_ADMIN_C_RCL.'">'; 
				}
				$dbhcms_languages .= '<td align="center" width="25"><a href="#" onclick="opener.setNewValue(\''.$_GET['return_name'].'\', \''.$lang.'\', \''.$lang.'\'); window.close(); return false; ">'.dbhcms_f_get_icon($lang).'</a></td>';
				$dbhcms_languages .= '<td align="center" width="20"><a href="#" onclick="opener.setNewValue(\''.$_GET['return_name'].'\', \''.$lang.'\', \''.$lang.'\'); window.close(); return false; "><strong>'.$lang.'</strong></a></td>';
				if (isset($GLOBALS['DBHCMS']['DICT']['BE'][$lang])) {
					$dbhcms_languages .= '<td><a href="#" onclick="opener.setNewValue(\''.$_GET['return_name'].'\', \''.$lang.'\', \''.$lang.'\'); window.close(); return false; ">'.$GLOBALS['DBHCMS']['DICT']['BE'][$lang].'</a></td>';
				} else {
					$dbhcms_languages .= '<td>-</td>';
				}
				$i++;
			}
			
			$dbhcms_languages = '	<div class="box" style="width:95%">
									<table cellpadding="2" cellspacing="1" border="0" width="100%">
										<tr>
											<td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap" colspan="2" width="45">'.$GLOBALS['DBHCMS']['DICT']['BE']['language'].'</td>
											<td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap" width="55">'.$GLOBALS['DBHCMS']['DICT']['BE']['name'].'</td>
										</tr>
										'.$dbhcms_languages.'
									</table>
								</div>';
			
			dbhcms_p_add_string('selectorValues', $dbhcms_languages);
			
		} else if ($_GET['data_type'] == DBHCMS_C_DT_DOMAIN) {
			
			dbhcms_p_add_template('nr'.count($GLOBALS['DBHCMS']['STRUCT']['TPL']), 'body.main.tpl'); 
			dbhcms_p_add_template('nr'.count($GLOBALS['DBHCMS']['STRUCT']['TPL']), 'content.selector.tpl'); 
			
			$dbhcms_domains = '';
			$i = 0;
			$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS);
			while ($row = mysql_fetch_array($result)) {
				if ($i & 1) { 
					$dbhcms_domains .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'" bgcolor="'.DBHCMS_ADMIN_C_RCD.'">'; 
				} else { 
					$dbhcms_domains .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'" bgcolor="'.DBHCMS_ADMIN_C_RCL.'">'; 
				}
				$dbhcms_domains .= '<td align="center" width="20"><a href="#" onclick="opener.setNewValue(\''.$_GET['return_name'].'\', \''.$row['domn_id'].'\', \''.$row['domn_name'].'\'); window.close(); return false; ">'.dbhcms_f_get_icon('domain').'</a></td>';
				$dbhcms_domains .= '<td align="left" valign="top"><a href="#" onclick="opener.setNewValue(\''.$_GET['return_name'].'\', \''.$row['domn_id'].'\', \''.$row['domn_name'].'\'); window.close(); return false; "><strong>'.$row['domn_name'].'</strong></a></td>';
				$dbhcms_domains .= '<td valign="top">'.$row['domn_description'].'</td>';
				$dbhcms_domains .= '</tr>';
				$i++;
			}
			
			$dbhcms_domains = '	<div class="box" style="width:95%">
									<table cellpadding="2" cellspacing="1" border="0" width="100%">
										<tr>
											<td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap" colspan="2" width="45">'.$GLOBALS['DBHCMS']['DICT']['BE']['domain'].'</td>
											<td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap" width="55">'.$GLOBALS['DBHCMS']['DICT']['BE']['description'].'</td>
										</tr>
										'.$dbhcms_domains.'
									</table>
								</div>';
			
			dbhcms_p_add_string('selectorValues', $dbhcms_domains);
			
		} else if (($_GET['data_type'] == DBHCMS_C_DT_DIRECTORY) || ($_GET['data_type'] == DBHCMS_C_DT_FILE)) {
			
			dbhcms_p_add_template('nr'.count($GLOBALS['DBHCMS']['STRUCT']['TPL']), 'body.frames.sel.tpl'); 
			dbhcms_p_add_string('selectorLeftFrame', 'index.php?dbhcms_pid='.$GLOBALS['DBHCMS']['PID'].'&return_name='.$_GET['return_name'].'&data_type='.$_GET['data_type'].'_SEL_LEFTFRAME&root_dir='.urlencode($_GET['root_dir']));
			dbhcms_p_add_string('selectorRightFrame', 'index.php?dbhcms_pid='.$GLOBALS['DBHCMS']['PID'].'&return_name='.$_GET['return_name'].'&data_type='.$_GET['data_type'].'_SEL_RIGHTFRAME&root_dir='.urlencode($_GET['root_dir']).'&show_dir=.'.urlencode($_GET['root_dir']));
			
		} else if (($_GET['data_type'] == DBHCMS_C_DT_DIRECTORY.'_SEL_LEFTFRAME') || ($_GET['data_type'] == DBHCMS_C_DT_FILE.'_SEL_LEFTFRAME')) {
			
			dbhcms_p_add_template('nr'.count($GLOBALS['DBHCMS']['STRUCT']['TPL']), 'body.main.tpl'); 
			dbhcms_p_add_template('nr'.count($GLOBALS['DBHCMS']['STRUCT']['TPL']), 'content.treeview.tpl');
			
			function construct_tree($ajava, $atree, $alevel, $dt, $root) {
				foreach($atree as $dir) {
					if ($alevel == 1) {
						$ajava .= " aux".strval($alevel+1)." = insFld(foldersTree, gFld(\"<small>".$dir['name']."</small>\", \"".'index.php?dbhcms_pid='.$GLOBALS['DBHCMS']['PID'].'&return_name='.$_GET['return_name'].'&data_type='.$dt.'_SEL_RIGHTFRAME&root_dir='.urlencode($root).'&show_dir='.urlencode($dir['path'])."\")) \n ";
					} else { 
						$ajava .= " aux".strval($alevel+1)." = insFld(aux".$alevel.", gFld(\"<small>".$dir['name']."</small>\", \"".'index.php?dbhcms_pid='.$GLOBALS['DBHCMS']['PID'].'&return_name='.$_GET['return_name'].'&data_type='.$dt.'_SEL_RIGHTFRAME&root_dir='.urlencode($root).'&show_dir='.urlencode($dir['path'])."\")) \n ";
					}
					$ajava = construct_tree($ajava, $dir['content'], ($alevel + 1), $dt, $root);
				}
				return $ajava;
			}
			
			if ($_GET['data_type'] == DBHCMS_C_DT_DIRECTORY.'_SEL_LEFTFRAME') {
				$dt = DBHCMS_C_DT_DIRECTORY;
			} else {
				$dt = DBHCMS_C_DT_FILE;
			}
			
			$dirdata = dbhcms_f_get_dirs('.'.$_GET['root_dir']);
			
			$tree_view  = " <script src=\"".$GLOBALS['DBHCMS']['CONFIG']['CORE']['javaDirectory']."treeview/ua.js\"></script> \n\n <script src=\"".$GLOBALS['DBHCMS']['CONFIG']['CORE']['javaDirectory']."treeview/ftiens4.js\"></script> \n\n <script> \n\n ";
			$tree_view .= " USETEXTLINKS = 1 \n STARTALLOPEN = 0 \n ICONPATH = '".$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory']."folders/' \n ";
			$tree_view .= " foldersTree = gFld(\"<strong>".htmlspecialchars($_GET['root_dir'])."</strong>\", \"".'index.php?dbhcms_pid='.$GLOBALS['DBHCMS']['PID'].'&return_name='.$_GET['return_name'].'&data_type='.$dt.'_SEL_RIGHTFRAME&root_dir='.urlencode($_GET['root_dir']).'&show_dir='.'.'.urlencode($_GET['root_dir'])."\") \n ";
			$tree_view .= construct_tree('', $dirdata, 1, $dt, $_GET['root_dir']);
			$tree_view .= " \n\n </script> \n\n ";
			
			dbhcms_p_add_string('treeview', $tree_view);
			
		} else if (($_GET['data_type'] == DBHCMS_C_DT_DIRECTORY.'_SEL_RIGHTFRAME') || ($_GET['data_type'] == DBHCMS_C_DT_FILE.'_SEL_RIGHTFRAME')) {
			
			dbhcms_p_add_template('nr'.count($GLOBALS['DBHCMS']['STRUCT']['TPL']), 'body.main.tpl'); 
			dbhcms_p_add_template('nr'.count($GLOBALS['DBHCMS']['STRUCT']['TPL']), 'content.selector.tpl'); 
			
			$files = '';
			
			if ($_GET['data_type'] == DBHCMS_C_DT_DIRECTORY.'_SEL_RIGHTFRAME') {
				$objects = dbhcms_f_get_dirobj($_GET['show_dir'], true);
			} else {
				$objects = dbhcms_f_get_dirobj($_GET['show_dir'], false);
			}
			
			$i = 0;
			
			foreach ($objects as $item) {
				if ($i & 1) { 
					$files .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'" bgcolor="'.DBHCMS_ADMIN_C_RCD.'">'; 
				} else { 
					$files .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'" bgcolor="'.DBHCMS_ADMIN_C_RCL.'">'; 
				}
				$res = substr(substr($item['path'], strlen($_GET['root_dir']), strlen($item['path'])), 2);
				if ($item['kind'] == 'dir') {
					$res = $res.'/';
				}
				if ($item['kind'] == 'dir') {
					$files .= '<td align="center" width="20"><a href="#" onclick="parent.opener.setNewValue(\''.$_GET['return_name'].'\', \''.$res.'\', \''.$res.'\'); top.close(); return false; ">'.dbhcms_f_get_icon('folder').'</a></td>';
				} else {
					$files .= '<td align="center" width="20"><a href="#" onclick="parent.opener.setNewValue(\''.$_GET['return_name'].'\', \''.$res.'\', \''.$res.'\'); top.close(); return false; ">'.dbhcms_f_get_icon('file').'</a></td>';
				}
				$files .= '<td align="left" valign="top"><a href="#" onclick="parent.opener.setNewValue(\''.$_GET['return_name'].'\', \''.$res.'\', \''.$res.'\'); top.close(); return false; "><strong>'.$item['name'].'</strong></a></td>';
				$files .= '<td valign="top">'.date ("m.d.Y H:i:s", $item['time']).'</td>';
				$files .= '</tr>';
				$i++;
			}
			
			$files = '	<div class="box" style="width:95%">
									<table cellpadding="2" cellspacing="1" border="0" width="100%">
										<tr>
											<td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap" colspan="2" width="45">'.$GLOBALS['DBHCMS']['DICT']['BE']['name'].'</td>
											<td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap" width="55">'.$GLOBALS['DBHCMS']['DICT']['BE']['date'].'</td>
										</tr>
										'.$files.'
									</table>
								</div>';
			
			dbhcms_p_add_string('selectorValues', $files);
			
		} else if ($_GET['data_type'] == DBHCMS_C_DT_EXTENSION) {
		
			dbhcms_p_add_template('nr'.count($GLOBALS['DBHCMS']['STRUCT']['TPL']), 'body.main.tpl'); 
			dbhcms_p_add_template('nr'.count($GLOBALS['DBHCMS']['STRUCT']['TPL']), 'content.selector.tpl'); 
			
			$dbhcms_extensions = '';
			
			$i = 0;
			foreach ($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['availableExtensions'] as $ext) {
				if ($i & 1) { 
					$dbhcms_extensions .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'" bgcolor="'.DBHCMS_ADMIN_C_RCD.'">'; 
				} else { 
					$dbhcms_extensions .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'" bgcolor="'.DBHCMS_ADMIN_C_RCL.'">'; 
				}
				
				$icon = dbhcms_f_get_icon($GLOBALS['DBHCMS']['CONFIG']['EXT'][$ext]['icon'], $GLOBALS['DBHCMS']['CONFIG']['EXT'][$ext]['title'], 1);
				if ($icon == '') {
					$icon = dbhcms_f_get_icon('application-x-executable', $GLOBALS['DBHCMS']['CONFIG']['EXT'][$ext]['title'], 1);
				}
				
				$dbhcms_extensions .= '<td align="center" width="20"><a href="#" onclick="opener.setNewValue(\''.$_GET['return_name'].'\', \''.$ext.'\', \''.$ext.'\'); window.close(); return false; ">'.$icon.'</a></td>';
				$dbhcms_extensions .= '<td align="left" valign="top"><a href="#" onclick="opener.setNewValue(\''.$_GET['return_name'].'\', \''.$ext.'\', \''.$ext.'\'); window.close(); return false; "><strong>'.$GLOBALS['DBHCMS']['CONFIG']['EXT'][$ext]['title'].'</strong></a></td>';
				$dbhcms_extensions .= '<td valign="top">'.$GLOBALS['DBHCMS']['CONFIG']['EXT'][$ext]['description'].'</td>';
				$dbhcms_extensions .= '</tr>';
				$i++;
			}
			
			$dbhcms_extensions = '	<div class="box" style="width:95%">
									<table cellpadding="2" cellspacing="1" border="0" width="100%">
										<tr>
											<td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap" colspan="2" width="45">'.$GLOBALS['DBHCMS']['DICT']['BE']['name'].'</td>
											<td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap" width="55">'.$GLOBALS['DBHCMS']['DICT']['BE']['description'].'</td>
										</tr>
										'.$dbhcms_extensions.'
									</table>
								</div>';
			
			dbhcms_p_add_string('selectorValues', $dbhcms_extensions);
		
		} else if ($_GET['data_type'] == DBHCMS_C_DT_USERLEVEL) {
		
		
		
		
			dbhcms_p_add_template('nr'.count($GLOBALS['DBHCMS']['STRUCT']['TPL']), 'body.main.tpl'); 
			dbhcms_p_add_template('nr'.count($GLOBALS['DBHCMS']['STRUCT']['TPL']), 'content.selector.tpl'); 
			
			$dbhcms_uls = '';
			
			$i = 0;
			foreach ($GLOBALS['DBHCMS']['TYPES']['FL'] as $ul) {
				if ($i & 1) { 
					$dbhcms_uls .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'" bgcolor="'.DBHCMS_ADMIN_C_RCD.'">'; 
				} else { 
					$dbhcms_uls .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'" bgcolor="'.DBHCMS_ADMIN_C_RCL.'">'; 
				}
				if ($ul == 'A') {
					$area = 'Standard';
				} else {
					$area = 'Front End';
				}
				$dbhcms_uls .= '<td align="center" width="20"><a href="#" onclick="opener.setNewValue(\''.$_GET['return_name'].'\', \''.$ul.'\', \''.$ul.' ('.$area.')\'); window.close(); return false; ">'.dbhcms_f_get_icon('key').'</a></td>';
				$dbhcms_uls .= '<td align="left" valign="top"><a href="#" onclick="opener.setNewValue(\''.$_GET['return_name'].'\', \''.$ul.'\', \''.$ul.' ('.$area.')\'); window.close(); return false; "><strong>'.$ul.' ('.$area.')</strong></a></td>';
				$dbhcms_uls .= '</tr>';
				$i++;
			}
			
			foreach ($GLOBALS['DBHCMS']['TYPES']['BL'] as $ul) {
				if ($i & 1) { 
					$dbhcms_uls .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'" bgcolor="'.DBHCMS_ADMIN_C_RCD.'">'; 
				} else { 
					$dbhcms_uls .= '<tr onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'" bgcolor="'.DBHCMS_ADMIN_C_RCL.'">'; 
				}
				
				$area = 'Back End';
				
				$dbhcms_uls .= '<td align="center" width="20"><a href="#" onclick="opener.setNewValue(\''.$_GET['return_name'].'\', \''.$ul.'\', \''.$ul.' ('.$area.')\'); window.close(); return false; ">'.dbhcms_f_get_icon('key').'</a></td>';
				$dbhcms_uls .= '<td align="left" valign="top"><a href="#" onclick="opener.setNewValue(\''.$_GET['return_name'].'\', \''.$ul.'\', \''.$ul.' ('.$area.')\'); window.close(); return false; "><strong>'.$ul.' ('.$area.')</strong></a></td>';
				$dbhcms_uls .= '</tr>';
				$i++;
			}
			
			$dbhcms_uls = '	<div class="box" style="width:95%">
									<table cellpadding="2" cellspacing="1" border="0" width="100%">
										<tr>
											<td background="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'tab_cap.gif" class="cap" colspan="2" width="45"> Userlevel </td>
										</tr>
										'.$dbhcms_uls.'
									</table>
								</div>';
			
			dbhcms_p_add_string('selectorValues', $dbhcms_uls);
		
		
		
		
		}
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>