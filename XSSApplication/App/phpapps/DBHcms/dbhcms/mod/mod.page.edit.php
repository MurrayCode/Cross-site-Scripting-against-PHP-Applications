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
# $Id: mod.page.edit.php 68 2007-05-31 20:28:17Z kaisven $                                  #
#############################################################################################

#############################################################################################
#	MODULE MOD.EDITPAGE.PHP                                                                   #
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
#  ACTIONS                                                                                  #
#############################################################################################

	if (isset($_GET['editpage'])) {
		
		if (isset($_POST['todo'])) {
			
			if ($_POST['todo'] == 'save_page') {
				
				#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
				# SAVE OVERALL PAGE SETTINGS                                               #
				#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
				
				$action_result = '<div style="color: #076619; font-weight: bold;">Overall settings have been saved.</div>';
				
				mysql_query ("
								UPDATE 
									".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." 
								SET 
									page_parent_id 		=	'".dbhcms_f_input_to_dbvalue('page_parent_id', DBHCMS_C_DT_PAGE)."',  
									page_domn_id 		=	'".dbhcms_f_input_to_dbvalue('page_domn_id', DBHCMS_C_DT_INTEGER)."', 
									page_posnr 			=	'".dbhcms_f_input_to_dbvalue('page_posnr', DBHCMS_C_DT_INTEGER)."', 
									page_hierarchy 		=	'".dbhcms_f_input_to_dbvalue('page_hierarchy', DBHCMS_C_DT_HIERARCHY)."', 
									page_hide			=	'".dbhcms_f_input_to_dbvalue('page_hide', DBHCMS_C_DT_BOOLEAN)."', 
									page_cache	 		=	'".dbhcms_f_input_to_dbvalue('page_cache', DBHCMS_C_DT_BOOLEAN)."', 
									page_schedule	 	=	'".dbhcms_f_input_to_dbvalue('page_schedule', DBHCMS_C_DT_BOOLEAN)."', 
									page_start 			=	'".dbhcms_f_input_to_dbvalue('page_start', DBHCMS_C_DT_DATETIME)."', 
									page_stop 			=	'".dbhcms_f_input_to_dbvalue('page_stop', DBHCMS_C_DT_DATETIME)."', 
									page_inmenu 		=	'".dbhcms_f_input_to_dbvalue('page_inmenu', DBHCMS_C_DT_BOOLEAN)."', 
									page_stylesheets 	=	'".dbhcms_f_input_to_dbvalue('page_stylesheets', DBHCMS_C_DT_CSSARRAY)."', 
									page_javascripts 	=	'".dbhcms_f_input_to_dbvalue('page_javascripts', DBHCMS_C_DT_JSARRAY)."', 
									page_templates 		=	'".dbhcms_f_input_to_dbvalue('page_templates', DBHCMS_C_DT_TPLARRAY)."', 
									page_php_modules 	=	'".dbhcms_f_input_to_dbvalue('page_php_modules', DBHCMS_C_DT_MODARRAY)."', 
									page_extensions 	=	'".dbhcms_f_input_to_dbvalue('page_extensions', DBHCMS_C_DT_EXTARRAY)."', 
									page_shortcut 		=	'".dbhcms_f_input_to_dbvalue('page_shortcut', DBHCMS_C_DT_PAGE)."', 
									page_link 			=	'".dbhcms_f_input_to_dbvalue('page_link', DBHCMS_C_DT_STRING)."', 
									page_target 		=	'".dbhcms_f_input_to_dbvalue('page_target', DBHCMS_C_DT_STRING)."', 
									page_userlevel 		=	'".dbhcms_f_input_to_dbvalue('page_userlevel', DBHCMS_C_DT_USERLEVEL)."', 
									page_description 	=	'".dbhcms_f_input_to_dbvalue('page_description', DBHCMS_C_DT_TEXT)."',
									page_last_edited 	= NOW() 
								WHERE 
									page_id = ".$_GET['editpage']. " LIMIT 1 
							
							") or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Overall settings could not be saved.</div>';
				
					dbhcms_p_del_cache($_GET['editpage']);
				
			} elseif ($_POST['todo'] == 'save_pagelang') {
				
				#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
				# SAVE LANGUAGE PAGE SETTINGS                                              #
				#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
				
				$action_result = '<div style="color: #076619; font-weight: bold;">Settings for "'.strtoupper($_GET['pagepart']).'" have been saved.</div>';
				
				$result_pagevals = mysql_query("	SELECT * 
													FROM 
														".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEVALS." 
													WHERE 
														pava_page_id = ".$_GET['editpage']." AND 
														pava_lang LIKE '".$_GET['pagepart']."'
												");
				
				while ($row_pagevals = mysql_fetch_array($result_pagevals)) {
					
					$result_type = mysql_query("	SELECT 
														papa_type 
													FROM 
														".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEPARAMS." 
													WHERE 
														papa_name LIKE '".$row_pagevals['pava_name']."' AND 
														(papa_page_id = 0 OR papa_page_id = ".$_GET['editpage']." ) 
												");
					
					$row_type = mysql_fetch_array($result_type);
					$parameter_type = $row_type['papa_type'];
					$parameter_value = dbhcms_f_input_to_dbvalue($row_pagevals['pava_name'], $parameter_type);
					
					mysql_query("
									UPDATE 
										".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEVALS." 
									SET 
										pava_value = '".$parameter_value."' 
									WHERE 
										pava_name LIKE '".$row_pagevals['pava_name']."' AND 
										pava_page_id = ".$_GET['editpage']." AND 
										pava_lang LIKE '".$_GET['pagepart']."'
								 	
								") or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Settings for "'.strtoupper($_GET['pagepart']).'" could not be saved.</div>';
					
					mysql_query("	UPDATE 
										".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." 
									SET 
										page_last_edited = NOW() 
									WHERE 
										page_id = ".$_GET['editpage']."
								");
				}
				
				dbhcms_p_del_cache($_GET['editpage']);
				
			} elseif ($_POST['todo'] == 'insert_page') {
				
				#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
				# INSERT NEW PAGE                                                          #
				#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
				
				$action_result = '<div style="color: #076619; font-weight: bold;">New page has been saved.</div>';
				
				mysql_query ("	INSERT INTO 
									".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." 
										(
											page_parent_id,
											page_domn_id,
											page_posnr,
											page_hierarchy,
											page_hide,
											page_cache,
											page_schedule,
											page_start,
											page_stop,
											page_inmenu,
											page_stylesheets,
											page_javascripts,
											page_templates,
											page_php_modules,
											page_extensions,
											page_shortcut,
											page_link,
											page_target,
											page_userlevel,
											page_description,
											page_last_edited
										)
								VALUES 
										(
											".dbhcms_f_input_to_dbvalue('page_parent_id', DBHCMS_C_DT_PAGE).", 
											".dbhcms_f_input_to_dbvalue('page_domn_id', DBHCMS_C_DT_STRING).", 
											'".dbhcms_f_input_to_dbvalue('page_posnr', DBHCMS_C_DT_STRING)."', 
											'".dbhcms_f_input_to_dbvalue('page_hierarchy', DBHCMS_C_DT_HIERARCHY)."', 
											'".dbhcms_f_input_to_dbvalue('page_hide', DBHCMS_C_DT_BOOLEAN)."', 
											'".dbhcms_f_input_to_dbvalue('page_cache', DBHCMS_C_DT_BOOLEAN)."', 
											'".dbhcms_f_input_to_dbvalue('page_schedule', DBHCMS_C_DT_BOOLEAN)."', 
											'".dbhcms_f_input_to_dbvalue('page_start', DBHCMS_C_DT_DATETIME)."', 
											'".dbhcms_f_input_to_dbvalue('page_stop', DBHCMS_C_DT_DATETIME)."', 
											'".dbhcms_f_input_to_dbvalue('page_inmenu', DBHCMS_C_DT_BOOLEAN)."', 
											'".dbhcms_f_input_to_dbvalue('page_stylesheets', DBHCMS_C_DT_CSSARRAY)."', 
											'".dbhcms_f_input_to_dbvalue('page_javascripts', DBHCMS_C_DT_JSARRAY)."', 
											'".dbhcms_f_input_to_dbvalue('page_templates', DBHCMS_C_DT_TPLARRAY)."', 
											'".dbhcms_f_input_to_dbvalue('page_php_modules', DBHCMS_C_DT_MODARRAY)."', 
											'".dbhcms_f_input_to_dbvalue('page_extensions', DBHCMS_C_DT_EXTARRAY)."', 
											'".dbhcms_f_input_to_dbvalue('page_shortcut', DBHCMS_C_DT_PAGE)."', 
											'".dbhcms_f_input_to_dbvalue('page_link', DBHCMS_C_DT_STRING)."', 
											'".dbhcms_f_input_to_dbvalue('page_target', DBHCMS_C_DT_STRING)."', 
											'".dbhcms_f_input_to_dbvalue('page_userlevel', DBHCMS_C_DT_USERLEVEL)."', 
											'".dbhcms_f_input_to_dbvalue('page_description', DBHCMS_C_DT_TEXT)."',
											NOW()
										);
							
							") or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Page could not be saved.</div>';
				
				$_GET['editpage'] = mysql_insert_id();
				
				if (dbhcms_f_input_to_value('page_domn_id', DBHCMS_C_DT_INTEGER) == 0) {
					mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." SET page_id = -".$_GET['editpage']." WHERE page_id = ".$_GET['editpage']);
					$_GET['editpage'] = ($_GET['editpage'] * -1);
				}
				
				dbhcms_p_add_missing_pagevals();
				
			} elseif ($_POST['todo'] == 'insert_pageparam') {
				
				#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
				# INSERT NEW PAGE PARAMETER                                                #
				#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
				
				$action_result = '<div style="color: #076619; font-weight: bold;">Page-Parameter has been added.</div>';
				if (mysql_num_rows(mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEPARAMS." WHERE upper(papa_name) LIKE upper('".trim($_POST['pageparam_insert_name'])."') AND ( papa_page_id = ".$_GET['editpage']." OR papa_page_id = 0 )")) > 0 ) {
					$action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Parameter allready exists.</div>';
				} else {
					mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEPARAMS." (`papa_page_id`, `papa_type`, `papa_name`, `papa_description`) VALUES (".$_GET['editpage'].", '".$_POST['pageparam_insert_type']."', '".$_POST['pageparam_insert_name']."', '".$_POST['pageparam_insert_description']."')")
						or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Page-Parameter could not be added.</div>';
					dbhcms_p_add_missing_pagevals();
				}
				
			} elseif ($_POST['todo'] == 'save_pageparam') {
				
				#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
				# SAVE PAGE PARAMETER                                                      #
				#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
				
				$action_result = '<div style="color: #076619; font-weight: bold;">Page-Parameter has been saved.</div>';
				mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEPARAMS." SET `papa_type` = '".$_POST['papa_type']."', `papa_description` = '".$_POST['papa_description']."' WHERE papa_name LIKE '".$_POST['pageparam_save']."' AND papa_page_id = ".$_GET['editpage'])
					or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Page-Parameter could not be saved.</div>';
				
			}
			
		} elseif (isset($_GET['todo'])) {
			
			if ($_GET['todo'] == 'delete_pageparam') {
				
				#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
				# DELETE PAGE PARAMETER                                                    #
				#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
				
				$action_result = '<div style="color: #076619; font-weight: bold;">Page-Parameter has been deleted.</div>';
				mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEPARAMS." WHERE papa_name LIKE '".$_GET['pageparam_delete']."' AND papa_page_id = ".$_GET['editpage'])
					or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Page-Parameter could not be deleted.</div>';
				mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEVALS." WHERE pava_name LIKE '".$_GET['pageparam_delete']."' AND pava_page_id = ".$_GET['editpage'])
					or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Page-Parameter could not be deleted.</div>';
				
			}
			
		}
	}

#############################################################################################
#  FORMS                                                                                    #
#############################################################################################

	if (isset($_GET['editpage'])) {
		
		if ($_GET['editpage'] == 'new') {
			
			#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
			# NEW PAGE                                                                 #
			#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
			
			dbhcms_p_add_string('pageTitle', 'NEW PAGE ');
			dbhcms_p_hide_block('pageParameters'); # hide parameters block
			
			if ($_GET['np_domn_id'] != 0) {
				$result_domains = mysql_query("SELECT domn_id, domn_name FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS." WHERE domn_id = ".$_GET['np_domn_id']);
				if (!($row_domains = mysql_fetch_assoc($result_domains))) { 
					dbhcms_p_error('Domain ID "'.$_GET['np_domn_id'].'" not found.', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
				}
			} else {
				$row_domains['domn_name'] = 'DBHcms Admin';
			}
			
			$domain_inputs = '<input type="hidden" name="page_domn_id" value="'.$_GET['np_domn_id'].'" />
							  <input type="text" name="page_domn_name" value="'.$row_domains['domn_name'].'" style="width:200px;" readonly="readonly" />';
			
			$page_tabs = '	<td>
								<table cellpadding="0" cellspacing="0" style="border-color: #28538F; border-style: solid; border-top-width : 1px; border-bottom-width : 0px; border-left-width : 1px; border-right-width : 1px;">
									<tr>
										<td bgcolor="#ACC5EF" height="20" class="tab" style="color:#000000;">
											&nbsp;&nbsp;&nbsp;&nbsp; New Page &nbsp;&nbsp;&nbsp;&nbsp; 
										</td>
									</tr>
								</table>
							</td>
							<td width="5"></td>';
			
			$page_form = '<form name="dbhcms_new_page" action="index.php?dbhcms_pid='.$GLOBALS['DBHCMS']['PID'].'&editpage=new" method="post" onsubmit=" parent.frames[0].location.reload(); ">
							<input type="hidden" name="todo" value="insert_page">
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
									<td align="right" width="100"><strong>domainId : </strong></td>
									<td align="center" width="202">'.$domain_inputs.'</td>
									<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagedomain'].'</td>
								</tr>
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
									<td align="right" width="100"><strong>parentId : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('page_parent_id', '', DBHCMS_C_DT_PAGE, 'dbhcms_new_page', 'width:204px;', $_GET['np_domn_id']).'</td>
									<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagepapage'].'</td>
								</tr>
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
									<td align="right" width="100"><strong>posNr : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('page_posnr', '', DBHCMS_C_DT_INTEGER, 'dbhcms_new_page', 'width:200px;').'</td>
									<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pageposnr'].'</td>
								</tr>
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
									<td align="right" width="100"><strong>hierarchy : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('page_hierarchy', DBHCMS_C_HT_HEREDITARY, DBHCMS_C_DT_HIERARCHY, 'dbhcms_edit_page', 'width:200px;').'</td>
									<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagehierarchy'].'</td>
								</tr>
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
									<td align="right" width="100"><strong>hide : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('page_hide', false, DBHCMS_C_DT_BOOLEAN, 'dbhcms_edit_page', 'width:204px;').'</td>
									<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagehide'].'</td>
								</tr>
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
									<td align="right" width="100"><strong>cache : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('page_cache', true, DBHCMS_C_DT_BOOLEAN, 'dbhcms_edit_page', 'width:204px;').'</td>
									<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagecache'].'</td>
								</tr>
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
									<td align="right" width="100"><strong>schedule : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('page_schedule', false, DBHCMS_C_DT_BOOLEAN, 'dbhcms_edit_page', 'width:204px;').'</td>
									<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pageschedule'].'</td>
								</tr>
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
									<td align="right" width="100"><strong>start : </strong></td>
									<td align="center" width="202"><div id="schedule_start_no" style="display: inline;"> - No schedule - </div><div id="schedule_start" style="display: none;">'.dbhcms_f_value_to_input('page_start', mktime(), DBHCMS_C_DT_DATETIME, 'dbhcms_edit_page', 'width:200px;').'</div></td>
										<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagestart'].'</td>
								</tr>
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
									<td align="right" width="100"><strong>stop : </strong></td>
									<td align="center" width="202"><div id="schedule_stop_no" style="display: inline;"> - No schedule - </div><div id="schedule_stop" style="display: none;">'.dbhcms_f_value_to_input('page_stop', mktime(), DBHCMS_C_DT_DATETIME, 'dbhcms_edit_page', 'width:200px;').'</div></td>
									<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagestop'].'<br /></td>
								</tr>
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
									<td align="right" width="100"><strong>inMenu : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('page_inmenu', true, DBHCMS_C_DT_BOOLEAN, 'dbhcms_new_page', 'width:204px;').'</td>
									<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pageinmenu'].'</td>
								</tr>
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
									<td align="right" width="100"><strong>stylesheets : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('page_stylesheets', array(), DBHCMS_C_DT_CSSARRAY, 'dbhcms_new_page', 'width:200px;').'</td>
									<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagestylesheets'].'</td>
								</tr>
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
									<td align="right" width="100"><strong>javascripts : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('page_javascripts', array(), DBHCMS_C_DT_JSARRAY, 'dbhcms_new_page', 'width:200px;').'</td>
									<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagejavascripts'].'</td>
								</tr>
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
									<td align="right" width="100"><strong>templates : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('page_templates', array(), DBHCMS_C_DT_TPLARRAY, 'dbhcms_new_page', 'width:200px;').'</td>
									<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagetemplates'].'</td>
								</tr>
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
									<td align="right" width="100"><strong>modules : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('page_php_modules', array(), DBHCMS_C_DT_MODARRAY, 'dbhcms_new_page', 'width:200px;').'</td>
									<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagephpmodules'].'</td>
								</tr>
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
									<td align="right" width="100"><strong>extensions : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('page_extensions', array(), DBHCMS_C_DT_EXTARRAY, 'dbhcms_new_page', 'width:200px;').'</td>
									<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pageext'].'</td>
								</tr>
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
									<td align="right" width="100"><strong>shortcut : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('page_shortcut', 0, DBHCMS_C_DT_PAGE, 'dbhcms_edit_page', 'width:200px;', $_GET['np_domn_id']).'</td>
									<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pageshortcut'].'</td>
								</tr>
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
									<td align="right" width="100"><strong>link : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('page_link', '', DBHCMS_C_DT_STRING, 'dbhcms_edit_page', 'width:200px;').'</td>
									<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagelink'].'</td>
								</tr>
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
									<td align="right" width="100"><strong>target : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('page_target', '_self', DBHCMS_C_DT_STRING, 'dbhcms_edit_page', 'width:200px;').'</td>
									<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagetarget'].'</td>
								</tr>
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
									<td align="right" width="100"><strong>userLevel : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('page_userlevel', 'A', DBHCMS_C_DT_USERLEVEL, 'dbhcms_new_page', 'width:204px;').'</td>
									<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pageul'].'</td>
								</tr>
								<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
									<td align="right" width="100"><strong>description : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('page_description', '', DBHCMS_C_DT_TEXT, 'dbhcms_new_page', 'width:200px;').'</td>
									<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagedesc'].'</td>
								</tr>
							</table>
							<table cellpadding="2" cellspacing="1" border="0" width="100%" align="center">
								<tr>
									<td colspan="3">
										<br>
										<input type="submit" value=" '.$GLOBALS['DBHCMS']['DICT']['BE']['save'].' "> 
									</td>
								</tr>
						  </form>';
			
		} else {
			
			$editpageid = $_GET['editpage'];
			
			if (isset($_GET['pagepart'])) {
				$page_part = $_GET['pagepart'];
			} else {
				$page_part = 'page';
			}
			$page_langs = array('page');
			
			#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
			# PAGE PARAMETERS                                                          #
			#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
			
			$page_parameters = '';
			$i = 0;
			$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEPARAMS." WHERE papa_page_id = ".$editpageid);
			while ($row = mysql_fetch_assoc($result)) {
				
				if ($i & 1) { 
					$page_parameters .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCD."\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCD."'\">"; 
				} else { 
					$page_parameters .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCL."\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCL."'\">"; 
				}
				
				$page_parameters .= '<form action="index.php?dbhcms_pid='.$GLOBALS['DBHCMS']['PID'].'&editpage='.$editpageid.'&pagepart='.$page_part.'" method="post" name="pageparam_edit"><input type="hidden" name="todo" value="save_pageparam" /><input type="hidden" name="pageparam_save" value="'.$row['papa_name'].'">';
				$page_parameters .= '<td><strong>'.$row['papa_name'].'</strong></td>';
				$page_parameters .= '<td>'.dbhcms_f_value_to_input('papa_type', $row['papa_type'], DBHCMS_C_DT_DATATYPE, 'pageparam_edit','width:100%;').'</td><td><input type="text" name="papa_description" value="'.$row['papa_description'].'" style="width:99%;"></td>';
				$page_parameters .= "<td align=\"center\" width=\"20\"><input type=\"image\" style=\"cursor: pointer; border-width: 0px;\" src=\"".$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory']."icons/small/media-floppy.png\" width=\"16\" height=\"16\" title=\"".dbhcms_f_dict('save', true)."\" border=\"0\"></td></form>";
				$page_parameters .= "<td align=\"center\" width=\"20\"><a href=\"index.php?dbhcms_pid=".$GLOBALS['DBHCMS']['PID']."&editpage=".$editpageid."&pagepart=".$page_part."&pageparam_delete=".$row['papa_name']."&todo=delete_pageparam\" onclick=\" return confirm('".dbhcms_f_dict('dbhcms_msg_askdeleteitem', true)."'); \">".dbhcms_f_get_icon('edit-delete', dbhcms_f_dict('delete', true), 1)."</a></td></tr>";
				$i++;
			}
			dbhcms_p_add_string('pageParameters', $page_parameters);
			
			#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
			# EDIT PAGE                                                                #
			#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
			
			$result_page = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." WHERE page_id = ".$_GET['editpage']);
			if ($row_page = mysql_fetch_array($result_page)) {
				
				if ($row_page['page_id'] > 0) {
					$url_domain = '&dbhcms_did='.$row_page['page_domn_id'];
				} else $url_domain = '';
				
				dbhcms_p_add_string('pageTitle', strtoupper($GLOBALS['DBHCMS']['DICT']['BE']['page']).': '.dbhcms_f_get_page_value($row_page['page_id'], DBHCMS_C_PAGEVAL_NAME, dbhcms_f_get_domain_default_lang($row_page['page_domn_id'])).' ('.$editpageid.')'.'&nbsp;<a target="_blank" title="View" href="index.php?dbhcms_pid='.$row_page['page_id'].$url_domain.'">'.dbhcms_f_get_icon('view').'</a>'); #.'&dbhcms_did='.$row_page['page_domn_id'].'
				dbhcms_p_add_string('dataTypes', dbhcms_f_value_to_input('pageparam_insert_type', '', DBHCMS_C_DT_DATATYPE, 'pageparam_new'));
				
				#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
				# PAGE TABS                                                                #
				#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
				
				if ($row_page['page_id'] > 0) {
					$result_pagevals = mysql_query("SELECT domn_supported_langs FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS." WHERE domn_id = ".$row_page['page_domn_id']);
					if ($row_pagevals = mysql_fetch_array($result_pagevals)) {
						$langs = dbhcms_f_dbvalue_to_value($row_pagevals['domn_supported_langs'], DBHCMS_C_DT_LANGARRAY);
						foreach ($langs as $lang) {
							array_push($page_langs, $lang);
						}
					}
				} else {
					foreach ($GLOBALS['DBHCMS']['CONFIG']['CORE']['supportedLangs'] as $tmkey => $tmvalue) {
						array_push($page_langs, $tmvalue);
					}
				}
				
				$page_tabs = '';
				foreach ($page_langs as $tmvalue) {
					if ($tmvalue == 'page') {
						$lang_cap = $GLOBALS['DBHCMS']['DICT']['BE']['page'];
					} else if (isset($GLOBALS['DBHCMS']['DICT']['BE'][$tmvalue])) {
						$lang_cap = strtoupper($tmvalue).' ('.$GLOBALS['DBHCMS']['DICT']['BE'][$tmvalue].')';
					} else {
						$lang_cap = $tmvalue;
					}
					if ($page_part == $tmvalue) {
						$page_tabs .= '<td><div class="tab_act">&nbsp;&nbsp; <a  class="blacklink" href="index.php?dbhcms_pid='.$GLOBALS['DBHCMS']['PID'].'&editpage='.$_GET['editpage'].'&pagepart='.$tmvalue.'"> '.$lang_cap.' </a> &nbsp;&nbsp; </div></td><td width="5"></td>';
					} else {
						$page_tabs .= '<td><div class="tab_no">&nbsp;&nbsp; <a  class="blacklink" href="index.php?dbhcms_pid='.$GLOBALS['DBHCMS']['PID'].'&editpage='.$_GET['editpage'].'&pagepart='.$tmvalue.'"> '.$lang_cap.' </a> &nbsp;&nbsp; </div></td><td width="5"></td>';
					}
				}
				
				#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
				# PAGE FORM                                                                #
				#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
				
				$page_form = '';
				
				if ($page_part == 'page') {
					
					dbhcms_p_hide_block('pageParameters'); # hide parameters block
					
					if ($row_page['page_domn_id'] != 0) {
						$result_domains = mysql_query("SELECT domn_id, domn_name FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS." WHERE domn_id = ".$row_page['page_domn_id']);
						if (!($row_domains = mysql_fetch_assoc($result_domains))) { 
							die('<div style="color: #FF0000; font-weight: bold;">ERROR! - Domain not found.</div>'); 
						}
					} else {
						$row_domains['domn_name'] = 'DBHcms Admin';
					}
					
					if (dbhcms_f_dbvalue_to_value($row_page['page_schedule'], DBHCMS_C_DT_BOOLEAN)) {
						$schedule_display = 'inline';
						$schedule_display_no = 'none';
					} else {
						$schedule_display = 'none';
						$schedule_display_no = 'inline';
					}
					
					$domain_inputs = '<input type="hidden" name="page_domn_id" value="'.$row_page['page_domn_id'].'" />
									  <input type="text" name="page_domn_name" value="'.$row_domains['domn_name'].'" style="width:200px;" readonly="readonly" />';
					
					$page_form = '<form name="dbhcms_edit_page" action="index.php?dbhcms_pid='.$GLOBALS['DBHCMS']['PID'].'&editpage='.$_GET['editpage'].'&pagepart='.$page_part.'" method="post" onsubmit=" parent.frames[0].location.reload(); ">
									<input type="hidden" name="todo" value="save_page">
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
											<td align="right" width="100"><strong>domainId : </strong></td>
											<td align="center" width="202">'.$domain_inputs.'</td>
											<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagedomain'].'</td>
										</tr>
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
											<td align="right" width="100"><strong>parentId : </strong></td>
											<td align="center" width="202">'.dbhcms_f_value_to_input('page_parent_id', dbhcms_f_dbvalue_to_value($row_page['page_parent_id'], DBHCMS_C_DT_PAGE), DBHCMS_C_DT_PAGE, 'dbhcms_edit_page', 'width:204px;', $row_page['page_domn_id']).'</td>
											<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagepapage'].'</td>
										</tr>
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
											<td align="right" width="100"><strong>posNr : </strong></td>
											<td align="center" width="202">'.dbhcms_f_value_to_input('page_posnr', dbhcms_f_dbvalue_to_value($row_page['page_posnr'], DBHCMS_C_DT_INTEGER), DBHCMS_C_DT_INTEGER, 'dbhcms_edit_page', 'width:200px;').'</td>
											<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pageposnr'].'</td>
										</tr>
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
											<td align="right" width="100"><strong>hierarchy : </strong></td>
											<td align="center" width="202">'.dbhcms_f_value_to_input('page_hierarchy', dbhcms_f_dbvalue_to_value($row_page['page_hierarchy'], DBHCMS_C_DT_HIERARCHY), DBHCMS_C_DT_HIERARCHY, 'dbhcms_edit_page', 'width:200px;').'</td>
											<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagehierarchy'].'</td>
										</tr>
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
											<td align="right" width="100"><strong>hide : </strong></td>
											<td align="center" width="202">'.dbhcms_f_value_to_input('page_hide', dbhcms_f_dbvalue_to_value($row_page['page_hide'], DBHCMS_C_DT_BOOLEAN), DBHCMS_C_DT_BOOLEAN, 'dbhcms_edit_page', 'width:204px;').'</td>
											<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagehide'].'</td>
										</tr>
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
											<td align="right" width="100"><strong>cache : </strong></td>
											<td align="center" width="202">'.dbhcms_f_value_to_input('page_cache', dbhcms_f_dbvalue_to_value($row_page['page_cache'], DBHCMS_C_DT_BOOLEAN), DBHCMS_C_DT_BOOLEAN, 'dbhcms_edit_page', 'width:204px;').'</td>
											<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagecache'].'</td>
										</tr>
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
											<td align="right" width="100"><strong>schedule : </strong></td>
											<td align="center" width="202">'.dbhcms_f_value_to_input('page_schedule', dbhcms_f_dbvalue_to_value($row_page['page_schedule'], DBHCMS_C_DT_BOOLEAN), DBHCMS_C_DT_BOOLEAN, 'dbhcms_edit_page', 'width:204px;').'</td>
											<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pageschedule'].'</td>
										</tr>
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
											<td align="right" width="100"><strong>start : </strong></td>
											<td align="center" width="202"><div id="schedule_start_no" style="display: '.$schedule_display_no.';"> - No schedule - </div><div id="schedule_start" style="display: '.$schedule_display.';">'.dbhcms_f_value_to_input('page_start', dbhcms_f_dbvalue_to_value($row_page['page_start'], DBHCMS_C_DT_DATETIME), DBHCMS_C_DT_DATETIME, 'dbhcms_edit_page', 'width:200px;').'</div></td>
												<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagestart'].'</td>
										</tr>
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
											<td align="right" width="100"><strong>stop : </strong></td>
											<td align="center" width="202"><div id="schedule_stop_no" style="display: '.$schedule_display_no.';"> - No schedule - </div><div id="schedule_stop" style="display: '.$schedule_display.';">'.dbhcms_f_value_to_input('page_stop', dbhcms_f_dbvalue_to_value($row_page['page_stop'], DBHCMS_C_DT_DATETIME), DBHCMS_C_DT_DATETIME, 'dbhcms_edit_page', 'width:200px;').'</div></td>
											<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagestop'].'<br /></td>
										</tr>
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
											<td align="right" width="100"><strong>inMenu : </strong></td>
											<td align="center" width="202">'.dbhcms_f_value_to_input('page_inmenu', dbhcms_f_dbvalue_to_value($row_page['page_inmenu'], DBHCMS_C_DT_BOOLEAN), DBHCMS_C_DT_BOOLEAN, 'dbhcms_edit_page', 'width:204px;').'</td>
											<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pageinmenu'].'</td>
										</tr>
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
											<td align="right" width="100"><strong>stylesheets : </strong></td>
											<td align="center" width="202">'.dbhcms_f_value_to_input('page_stylesheets', dbhcms_f_dbvalue_to_value($row_page['page_stylesheets'], DBHCMS_C_DT_STRARRAY), DBHCMS_C_DT_CSSARRAY, 'dbhcms_edit_page', 'width:200px;').'</td>
											<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagestylesheets'].'</td>
										</tr>
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
											<td align="right" width="100"><strong>javascripts : </strong></td>
											<td align="center" width="202">'.dbhcms_f_value_to_input('page_javascripts', dbhcms_f_dbvalue_to_value($row_page['page_javascripts'], DBHCMS_C_DT_STRARRAY), DBHCMS_C_DT_JSARRAY, 'dbhcms_edit_page', 'width:200px;').'</td>
											<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagejavascripts'].'</td>
										</tr>
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
											<td align="right" width="100"><strong>templates : </strong></td>
											<td align="center" width="202">'.dbhcms_f_value_to_input('page_templates', dbhcms_f_dbvalue_to_value($row_page['page_templates'], DBHCMS_C_DT_STRARRAY), DBHCMS_C_DT_TPLARRAY, 'dbhcms_edit_page', 'width:200px;').'</td>
											<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagetemplates'].'</td>
										</tr>
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
											<td align="right" width="100"><strong>modules : </strong></td>
											<td align="center" width="202">'.dbhcms_f_value_to_input('page_php_modules', dbhcms_f_dbvalue_to_value($row_page['page_php_modules'], DBHCMS_C_DT_STRARRAY), DBHCMS_C_DT_MODARRAY, 'dbhcms_edit_page', 'width:200px;').'</td>
											<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagephpmodules'].'</td>
										</tr>
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
											<td align="right" width="100"><strong>extensions : </strong></td>
											<td align="center" width="202">'.dbhcms_f_value_to_input('page_extensions', dbhcms_f_dbvalue_to_value($row_page['page_extensions'], DBHCMS_C_DT_EXTARRAY), DBHCMS_C_DT_EXTARRAY, 'dbhcms_edit_page', 'width:200px;').'</td>
											<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pageext'].'</td>
										</tr>
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
											<td align="right" width="100"><strong>shortcut : </strong></td>
											<td align="center" width="202">'.dbhcms_f_value_to_input('page_shortcut', $row_page['page_shortcut'], DBHCMS_C_DT_PAGE, 'dbhcms_edit_page', 'width:200px;', $row_page['page_domn_id']).'</td>
											<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pageshortcut'].'</td>
										</tr>
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
											<td align="right" width="100"><strong>link : </strong></td>
											<td align="center" width="202">'.dbhcms_f_value_to_input('page_link', $row_page['page_link'], DBHCMS_C_DT_STRING, 'dbhcms_edit_page', 'width:200px;').'</td>
											<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagelink'].'</td>
										</tr>
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
											<td align="right" width="100"><strong>target : </strong></td>
											<td align="center" width="202">'.dbhcms_f_value_to_input('page_target', $row_page['page_target'], DBHCMS_C_DT_STRING, 'dbhcms_edit_page', 'width:200px;').'</td>
											<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagetarget'].'</td>
										</tr>
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCL.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCL.'\'">
											<td align="right" width="100"><strong>userLevel : </strong></td>
											<td align="center" width="202">'.dbhcms_f_value_to_input('page_userlevel', $row_page['page_userlevel'], DBHCMS_C_DT_USERLEVEL, 'dbhcms_edit_page', 'width:204px;').'</td>
											<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pageul'].'</td>
										</tr>
										<tr bgcolor="'.DBHCMS_ADMIN_C_RCD.'" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \''.DBHCMS_ADMIN_C_RCD.'\'">
											<td align="right" width="100"><strong>description : </strong></td>
											<td align="center" width="202">'.dbhcms_f_value_to_input('page_description', $row_page['page_description'], DBHCMS_C_DT_TEXT, 'dbhcms_edit_page', 'width:200px;').'</td>
											<td>'.$GLOBALS['DBHCMS']['DICT']['BE']['dbhcms_desc_pagedesc'].'</td>
										</tr>
									</table>
									<br>
									<table cellpadding="2" cellspacing="1" border="0" width="100%" align="center">
										<tr>
											<td width="100">
												<input type="submit" value=" '.$GLOBALS['DBHCMS']['DICT']['BE']['save'].' "> 
											</td>
											<td>
												<input type="button" value=" '.$GLOBALS['DBHCMS']['DICT']['BE']['delete'].' " onclick=" if (confirm(\' Delete page? \') == true ) { parent.parent.dbhcms_admin_content.location = \'index.php?dbhcms_pid=-10&deletepage='.$_GET['editpage'].'\' } " > 
											</td>
										</tr>
								  </form>';
				} else {
					
					dbhcms_p_create_temp_css_for_tinymce($_GET['editpage'], $page_part);
					
					$result_pagevals = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES.", ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEVALS.", ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEPARAMS." WHERE page_id = pava_page_id AND pava_name = papa_name AND pava_page_id = ".$_GET['editpage']." AND (papa_page_id = 0 OR papa_page_id = ".$_GET['editpage'].") AND pava_lang LIKE '".$page_part."'");
					$i = 0;
					while ($row_pagevals = mysql_fetch_array($result_pagevals)) {
						
						if ($i & 1) { 
							$page_form .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCD."\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCD."'\">"; 
						} else { 
							$page_form .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCL."\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCL."'\">"; 
						}
						
						$page_form .= "<td align=\"right\" valign=\"top\"><strong>".$row_pagevals['pava_name']." :</strong></td>";
						
						if (isset($GLOBALS['DBHCMS']['DICT']['BE'][$row_pagevals['papa_description']])) {
							$description = $GLOBALS['DBHCMS']['DICT']['BE'][$row_pagevals['papa_description']];
						} else {
							$description = $row_pagevals['papa_description'];
						}
						
						if ($row_pagevals['papa_type'] == DBHCMS_C_DT_CONTENT) {
							$page_form .= "<td align=\"left\" valign=\"top\" width=\"200\">".dbhcms_f_value_to_input($row_pagevals['pava_name'], dbhcms_f_dbvalue_to_value($row_pagevals['pava_value'], $row_pagevals['papa_type']), $row_pagevals['papa_type'], 'dbhcms_edit_page_lang', $GLOBALS['DBHCMS']['CONFIG']['CORE']['tempDirectory']."tmp.tinymce.".$_GET['editpage'].".".$_SESSION['DBHCMSDATA']['SID'].".css")."</td><td>".$description."</td></tr>";
						} else {
							$page_form .= "<td align=\"left\" valign=\"top\" width=\"200\">".dbhcms_f_value_to_input($row_pagevals['pava_name'], dbhcms_f_dbvalue_to_value($row_pagevals['pava_value'], $row_pagevals['papa_type']), $row_pagevals['papa_type'], 'dbhcms_edit_page_lang', 'width:200px;', $row_pagevals['page_domn_id'])."</td><td>".$description."</td></tr>";
						}
						
						$i++;
					}
					$page_form = '<form name="dbhcms_edit_page_lang" action="index.php?dbhcms_pid='.$GLOBALS['DBHCMS']['PID'].'&editpage='.$_GET['editpage'].'&pagepart='.$page_part.'" method="post" onsubmit=" parent.frames[0].location.reload(); ">
									<input type="hidden" name="todo" value="save_pagelang">
									'.$page_form.'
								</table>
								<br>
								<table cellpadding="2" cellspacing="1" border="0" width="100%" align="center">
									<tr>
										<td width="100">
											<input type="submit" value=" '.$GLOBALS['DBHCMS']['DICT']['BE']['save'].' "> 
										</td>
										<td>
											<input type="button" value=" '.$GLOBALS['DBHCMS']['DICT']['BE']['delete'].' " onclick=" if (confirm(\' Delete page? \') == true ) { parent.parent.dbhcms_admin_content.location = \'index.php?dbhcms_pid=-10&deletepage='.$_GET['editpage'].'\' } " > 
										</td>
									</tr>
								  </form>';
				}
			} else {
				die('<div style="color: #FF0000; font-weight: bold;">ERROR! - Page not found.</div>');
			}
		}
		
	}

#############################################################################################
#	MODULE RESULT PARAMETERS                                                                  #
#############################################################################################

	dbhcms_p_add_string('pageTabs', $page_tabs);
	dbhcms_p_add_string('pageForm', $page_form);
	
	if (isset($_GET['editpage'])) {
		dbhcms_p_add_string('editPageId', $_GET['editpage']);
	}
	if (isset($_GET['pagepart'])) {
		dbhcms_p_add_string('editPagePart', $_GET['pagepart']);
	} else if (isset($page_part)) {
		dbhcms_p_add_string('editPagePart', $page_part);
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>