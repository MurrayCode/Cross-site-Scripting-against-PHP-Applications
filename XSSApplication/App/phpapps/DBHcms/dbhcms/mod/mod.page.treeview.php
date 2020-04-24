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
# $Id: mod.page.treeview.php 60 2007-02-01 13:34:54Z kaisven $                              #
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
#	MODULE MOD.PAGE.TREEVIEW.PHP                                                              #
#############################################################################################

	function construct_tree($ajava, $atree, $alevel, $adomain) {
		
		foreach($atree as $pageid => $pageparentid) {
			
			if ($alevel == 1) {
				$ajava .= " aux".strval($alevel+1)." = insFld(domain".$adomain.", gFld(\"<small>".$GLOBALS['DBHCMS']['PAGES'][$pageid]['params']['name'].' ('.$pageid.')'."</small>\", \"index.php?dbhcms_pid=-12&editpage=".$pageid."\")) \n ";
			} else { 
				$ajava .= " aux".strval($alevel+1)." = insFld(aux".$alevel.", gFld(\"<small>".$GLOBALS['DBHCMS']['PAGES'][$pageid]['params']['name'].' ('.$pageid.')'."</small>\", \"index.php?dbhcms_pid=-12&editpage=".$pageid."\")) \n ";
			}
			
			$page_icon = 'page.gif';
			if ($GLOBALS['DBHCMS']['PAGES'][$pageid]['hide'] == 1) {
				$page_icon = 'page_hide.gif';
			} else if ($GLOBALS['DBHCMS']['PAGES'][$pageid]['userLevel'] != 'A') {
				if ($GLOBALS['DBHCMS']['PAGES'][$pageid]['inMenu'] == 0) {
					$page_icon = 'page_user_nim.gif';
				} else { $page_icon = 'page_user.gif'; }
			
			} else if ($GLOBALS['DBHCMS']['PAGES'][$pageid]['shortcut'] != 0) {
			
				if ($GLOBALS['DBHCMS']['PAGES'][$pageid]['inMenu'] == 0) {
					$page_icon = 'shortcut_nim.gif';
				} else { $page_icon = 'shortcut.gif'; }
			
			} else if (trim($GLOBALS['DBHCMS']['PAGES'][$pageid]['link']) != '') {
			
				if ($GLOBALS['DBHCMS']['PAGES'][$pageid]['inMenu'] == 0) {
					$page_icon = 'link_nim.gif';
				} else { $page_icon = 'link.gif'; }
			
			} else if ($GLOBALS['DBHCMS']['PAGES'][$pageid]['schedule']) {
				if ($GLOBALS['DBHCMS']['PAGES'][$pageid]['inMenu'] == 0) {
					$page_icon = 'page_ss_nim.gif';
				} else { $page_icon = 'page_ss.gif'; }
			} else {
				if ($GLOBALS['DBHCMS']['PAGES'][$pageid]['inMenu'] == 0) {
					$page_icon = 'page_nim.gif';
				} else { $page_icon = 'page.gif'; }
			}
			
			$ajava .= " aux".strval($alevel+1).".iconSrc = ICONPATH + \"".$page_icon."\" \n ";
			$ajava .= " aux".strval($alevel+1).".iconSrcClosed = ICONPATH + \"".$page_icon."\" \n ";
			$ajava = construct_tree($ajava, $atree[$pageid], ($alevel + 1), $adomain, $GLOBALS['DBHCMS']['PAGES']);
			
		}
		return $ajava;
	}

	$page_tree_view  = "  <script src=\"".$GLOBALS['DBHCMS']['CONFIG']['CORE']['javaDirectory']."treeview/ua.js\"></script> \n\n <script src=\"".$GLOBALS['DBHCMS']['CONFIG']['CORE']['javaDirectory']."treeview/ftiens4.js\"></script> \n\n <script> \n\n ";
	$page_tree_view .= " USETEXTLINKS = 1 \n STARTALLOPEN = 1 \n ICONPATH = '".$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory']."folders/' \n ";
	$page_tree_view .= " foldersTree = gFld(\" &nbsp; <strong>DBHcms</strong>\", \"\") \n ";
	$page_tree_view .= " foldersTree.iconSrc = ICONPATH + \"home.gif\" \n ";
	$page_tree_view .= " foldersTree.iconSrcClosed = ICONPATH + \"home.gif\" \n ";

	if ($GLOBALS['DBHCMS']['CONFIG']['CORE']['debug']) {
		
		$page_tree_view .= " domain0 = insFld(foldersTree, gFld(\" &nbsp; <strong>DBHcms Admin</strong>\", \"\")) \n ";
		$page_tree_view .= " domain0.iconSrc = ICONPATH + \"bomb.gif\" \n ";
		$page_tree_view .= " domain0.iconSrcClosed = ICONPATH + \"bomb.gif\" \n ";
		
		$page_tree_view .= " domain0_new_page = insFld(domain0, gFld(\" &nbsp; <strong>New Page</strong>\", \"index.php?dbhcms_pid=-12&np_domn_id=0&editpage=new\")) \n ";
		$page_tree_view .= " domain0_new_page.iconSrc = ICONPATH + \"page-new.gif\" \n ";
		$page_tree_view .= " domain0_new_page.iconSrcClosed = ICONPATH + \"page-new.gif\" \n ";
		
		$page_tree_view .= construct_tree('', dbhcms_f_create_page_tree(0, $GLOBALS['DBHCMS']['CONFIG']['CORE']['defaultLang']), 1, 0);
	}

	$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS);
	while ($row = mysql_fetch_assoc($result)) {
		
		$page_tree_view .= " domain".$row['domn_id']." = insFld(foldersTree, gFld(\" &nbsp; <strong>".$row['domn_name']."</strong>\", \"index.php?dbhcms_pid=-21&editdomain=".$row['domn_id']."\")) \n ";
		$page_tree_view .= " domain".$row['domn_id'].".iconSrc = ICONPATH + \"domain.gif\" \n ";
		$page_tree_view .= " domain".$row['domn_id'].".iconSrcClosed = ICONPATH + \"domain.gif\" \n ";
		
		$page_tree_view .= " domain".$row['domn_id']."_new_page = insFld(domain".$row['domn_id'].", gFld(\" &nbsp; <strong>New Page</strong>\", \"index.php?dbhcms_pid=-12&np_domn_id=".$row['domn_id']."&editpage=new\")) \n ";
		$page_tree_view .= " domain".$row['domn_id']."_new_page.iconSrc = ICONPATH + \"page-new.gif\" \n ";
		$page_tree_view .= " domain".$row['domn_id']."_new_page.iconSrcClosed = ICONPATH + \"page-new.gif\" \n ";
		
		$page_tree_view .= construct_tree('', dbhcms_f_create_page_tree($row['domn_id'], $row['domn_default_lang']), 1, $row['domn_id']);
	}

	$page_tree_view  .= " \n\n </script> \n\n ";

	dbhcms_p_add_string('treeview', $page_tree_view);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>