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
#  page.php                                                                                 #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Creates the page                                                                         #
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
# $Id: page.php 68 2007-05-31 20:28:17Z kaisven $                                           #
#############################################################################################

#############################################################################################
#  SECURITY                                                                                 #
#############################################################################################

	if ((realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))||(!defined('DBHCMS'))) {
		die('	<div style="color: #872626; font-weight: bold;">
						DBHCMS FATAL ERROR - Access denied!
					</div>');
	}

#############################################################################################
#  REGISTER FILE                                                                            #
#############################################################################################

	dbhcms_p_register_file(realpath(__FILE__), 'page', 0.3);

#############################################################################################
#  GET PARAMS                                                                               #
#############################################################################################

	if (isset($_GET['dbhcms_params'])) {
		$GLOBALS['DBHCMS']['TEMP']['PARAMS'] = dbhcms_f_decode_url_params($_GET['dbhcms_params']);
	}

#############################################################################################
#  GET PAGE ID                                                                              #
#############################################################################################

	if (isset($_GET['document_error'])) {
		switch (intval($_GET['document_error'])) {
			case 401:
			   $dbhcms_page_id = $GLOBALS['DBHCMS']['DOMAIN']['err401PageId'];
			   break;
			case 403:
			   $dbhcms_page_id = $GLOBALS['DBHCMS']['DOMAIN']['err403PageId'];
			   break;
			case 404:
			   $dbhcms_page_id = $GLOBALS['DBHCMS']['DOMAIN']['err404PageId'];
			   break;
		}
	} else {
		$dbhcms_page_id = $GLOBALS['DBHCMS']['DOMAIN']['indexPageId'];
		if (isset($_GET['dbhcms_pid'])) {
			$result = mysql_query("SELECT page_id FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." WHERE page_id = ".intval($_GET['dbhcms_pid']));
			if ($row = mysql_fetch_assoc($result)) {
				$dbhcms_page_id = $row['page_id'];
			} else {
				header("HTTP/1.0 404 Not Found");
				dbhcms_p_error('Page ID "'.$_GET['dbhcms_pid'].'" does not exists.', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
				$dbhcms_page_id = $GLOBALS['DBHCMS']['DOMAIN']['err404PageId'];
			}
		}
		if ((!isset($_SESSION['DBHCMSDATA']))&&($dbhcms_page_id == $GLOBALS['DBHCMS']['DOMAIN']['indexPageId'])) {
			$dbhcms_page_id = $GLOBALS['DBHCMS']['DOMAIN']['introPageId'];
		}
	}

	$GLOBALS['DBHCMS']['PID'] = intval($dbhcms_page_id); unset($dbhcms_page_id);

#############################################################################################
#  WRITE HISTORY                                                                            #
#############################################################################################

	array_push($_SESSION['DBHCMSDATA']['STAT']['navHistory'], $GLOBALS['DBHCMS']['PID']);
	$result = mysql_query("SELECT visit_id FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_VISITS." WHERE visit_sessionid = '".$_SESSION['DBHCMSDATA']['SID']."'");
	if (mysql_num_rows($result) > 0) {
		# Save history
		mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_VISITS." SET `visit_history` = CONCAT_WS(';', `visit_history`, '".$GLOBALS['DBHCMS']['PID']."') WHERE `visit_sessionid` = '".$_SESSION['DBHCMSDATA']['SID']."'");
	} else {
		# Save visit
		mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_VISITS." (`visit_sessionid`, `visit_domn_id`, `visit_httpuseragent`, `visit_remoteaddr`, `visit_requesturi`, `visit_requestmethod`, `visit_visitdatetime`, `visit_origin`, `visit_history` , `visit_search_phrase`, `visit_search_engine`, `visit_browser_langs`) VALUES ('".$_SESSION['DBHCMSDATA']['SID']."', '".$GLOBALS['DBHCMS']['DID']."', '".$_SERVER['HTTP_USER_AGENT']."', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['REQUEST_URI']."', '".$_SERVER['REQUEST_METHOD']."', NOW(), '".$_SESSION['DBHCMSDATA']['STAT']['origin']."', '".$GLOBALS['DBHCMS']['PID']."', '".$_SESSION['DBHCMSDATA']['STAT']['searchPhrase']."', '".$_SESSION['DBHCMSDATA']['STAT']['searchEngine']."', '".$_SESSION['DBHCMSDATA']['LANG']['all']."' )");
	}

#############################################################################################
#  AUTHENTICATION                                                                           #
#############################################################################################

	dbhcms_p_require_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'].'auth.php', 'auth', 0.1);

#############################################################################################
#  CHANGE/GET LANGUAGE                                                                      #
#############################################################################################

	# change language
	if (!isset($GLOBALS['DBHCMS']['RESULTS']['login'])) { # do not language change at login
		if (isset($_GET['dbhcms_lang'])) {
			if (in_array($_GET['dbhcms_lang'], $GLOBALS['DBHCMS']['DOMAIN']['supportedLangs'])) {
				$_SESSION['DBHCMSDATA']['LANG']['useLanguage'] = $_GET['dbhcms_lang'];
			}
		}
	}

	# Check selected language, if not supported set to default language
	if (!(in_array($_SESSION['DBHCMSDATA']['LANG']['useLanguage'], $GLOBALS['DBHCMS']['DOMAIN']['supportedLangs']))) {
		$_SESSION['DBHCMSDATA']['LANG']['useLanguage'] = $GLOBALS['DBHCMS']['DOMAIN']['defaultLang'];
	}

#############################################################################################
#  LOAD PAGE FROM CACHE                                                                     #
#############################################################################################

	if (($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['cacheEnabled']) && ($GLOBALS['DBHCMS']['PID'] > 0)) {

		if (isset($_POST['dbhcmsCache'])) {
			if (in_array(strtoupper(trim($_POST['dbhcmsCache'])), array(DBHCMS_C_CT_ON, DBHCMS_C_CT_OFF, DBHCMS_C_CT_REFRESH, DBHCMS_C_CT_EMPTYPAGE, DBHCMS_C_CT_EMPTYALL))) {
				$GLOBALS['DBHCMS']['TEMP']['dbhcmsCache'] = strtoupper(trim($_POST['dbhcmsCache']));
			} else {
				$GLOBALS['DBHCMS']['TEMP']['dbhcmsCache'] = DBHCMS_C_CT_ON;
			}
		} else {
			$GLOBALS['DBHCMS']['TEMP']['dbhcmsCache'] = DBHCMS_C_CT_ON;
		}

		if ($GLOBALS['DBHCMS']['TEMP']['dbhcmsCache'] == DBHCMS_C_CT_EMPTYPAGE) {
			dbhcms_p_del_cache($GLOBALS['DBHCMS']['PID']);
		} else if ($GLOBALS['DBHCMS']['TEMP']['dbhcmsCache'] == DBHCMS_C_CT_EMPTYALL) {
			dbhcms_p_del_cache();
		}

		if ((count($GLOBALS['DBHCMS']['RESULTS']) == 0) && ($GLOBALS['DBHCMS']['TEMP']['dbhcmsCache'] == DBHCMS_C_CT_ON)) {
			
			$result = mysql_query("SELECT page_id, page_cache FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." WHERE page_id = ".$GLOBALS['DBHCMS']['PID']);
			if ($row = mysql_fetch_assoc($result)) {
				$page_cache =  dbhcms_f_dbvalue_to_value($row['page_cache'], DBHCMS_C_DT_BOOLEAN);
			} else {
				dbhcms_p_error('Could not load cache. Page with the ID "'.$GLOBALS['DBHCMS']['PID'].'" does not exist.', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			}
			
			if ($page_cache) { 
				
				if ($_SESSION['DBHCMSDATA']['AUTH']['authenticated']) {
					$cache_userid = $_SESSION['DBHCMSDATA']['AUTH']['userId'];
					$cache_session_sql = " AND cach_sessionid LIKE '".$_SESSION['DBHCMSDATA']['SID']."' ";
				} else { 
					$cache_userid = "x";
					$cache_session_sql = '';
				}
				
				$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_CACHE." WHERE cach_page_id = ".$GLOBALS['DBHCMS']['PID']." AND cach_user_id LIKE '".$cache_userid."' AND cach_lang LIKE '".$_SESSION['DBHCMSDATA']['LANG']['useLanguage']."' AND cach_requesturi LIKE '".trim($_SERVER['REQUEST_URI'])."' ".$cache_session_sql." ORDER BY cach_timestamp DESC ");
				if ($row = mysql_fetch_assoc($result)) {
					$cache_file = $GLOBALS['DBHCMS']['CONFIG']['CORE']['tempDirectory']."tmp.cache.".$row['cach_id'].".".$GLOBALS['DBHCMS']['PID'].".".$cache_userid.".".$_SESSION['DBHCMSDATA']['LANG']['useLanguage'].".".$row['cach_sessionid'].".html";
					$cache_time = time() - ($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['cacheTime'] * 60);
					if (is_file($cache_file) && (filesize($cache_file) > 0)) {
						if (filemtime($cache_file) > $cache_time) {
							$contentfile = fopen($cache_file, "r");
							$result = fread($contentfile, filesize($cache_file));
							fclose($contentfile);
							print $result;
							exit;
						}
					}
				}
			}
		
		}

	}

#############################################################################################
#  LOAD DICTIONARY                                                                          #
#############################################################################################

	dbhcms_p_require_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'].'dict.php', 'dict', 0.1);

#############################################################################################
#  LOAD PAGES                                                                               #
#############################################################################################

	dbhcms_p_require_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'].'pcfg.php', 'pcfg', 0.1);

#############################################################################################
#  LIBRARIES                                                                                #
#############################################################################################

	dbhcms_p_require_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['libDirectory'].'lib.template.php', 'template', 0.1);
	dbhcms_p_require_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['libDirectory'].'lib.babelfish.php', 'babelfish', 0.1);
	dbhcms_p_require_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['libDirectory'].'lib.captcha.php', 'captcha', 0.1);

#############################################################################################
#  PAGE TREES                                                                               #
#############################################################################################

	$GLOBALS['DBHCMS']['PTREE']['complete'] = dbhcms_f_create_page_tree($GLOBALS['DBHCMS']['DID'], $_SESSION['DBHCMSDATA']['LANG']['useLanguage'], 0);
	$GLOBALS['DBHCMS']['PTREE']['single']	  = dbhcms_f_get_subtree_from_page_tree($GLOBALS['DBHCMS']['PTREE']['complete'], dbhcms_f_get_root_parent($GLOBALS['DBHCMS']['PID']));
	$GLOBALS['DBHCMS']['PTREE']['location'] = dbhcms_f_create_page_tree($GLOBALS['DBHCMS']['DID'], $_SESSION['DBHCMSDATA']['LANG']['useLanguage'], $GLOBALS['DBHCMS']['PID'], true);

#############################################################################################
#  STANDARD BLOCKS                                                                          #
#############################################################################################

	# show or hide login and logout blocks
	if ($_SESSION['DBHCMSDATA']['AUTH']['authenticated']) {
		dbhcms_p_show_block('logout');
		dbhcms_p_hide_block('login');
	} else { 
	  	dbhcms_p_show_block('login'); 
		dbhcms_p_hide_block('logout');
	}

	# show or hide admin login and logout blocks
	if (dbhcms_f_superuser_auth()) {
		dbhcms_p_show_block('adminLogout');
		dbhcms_p_hide_block('adminLogin');
	} else { 
		dbhcms_p_show_block('adminLogin');
		dbhcms_p_hide_block('adminLogout'); 
	}

#############################################################################################
#  SHOW RESULTS OF STANDARD PROCEDURES                                                      #
#############################################################################################

	if (isset($GLOBALS['DBHCMS']['RESULTS']['login'])) {
		if ($GLOBALS['DBHCMS']['RESULTS']['login']) {
			dbhcms_p_add_string('resLogin', $GLOBALS['DBHCMS']['DICT']['FE']['msg_login_ok']);
			dbhcms_p_add_string('resLoginOk', $GLOBALS['DBHCMS']['DICT']['FE']['msg_login_ok']);
		} else {
			dbhcms_p_add_string('resLogin', $GLOBALS['DBHCMS']['DICT']['FE']['msg_login_wrong']);
			dbhcms_p_add_string('resLoginErr', $GLOBALS['DBHCMS']['DICT']['FE']['msg_login_wrong']);
		}
	}

	if (isset($GLOBALS['DBHCMS']['RESULTS']['sessionExpired'])) {
		dbhcms_p_show_block('sessionExpired');
		dbhcms_p_add_string('resSessionExpired', $GLOBALS['DBHCMS']['DICT']['FE']['msg_session_expired']);
	} else { 
		dbhcms_p_hide_block('sessionExpired'); 
	}

#############################################################################################
#  BUILD CONTSRUCT FOR THE PAGE                                                             #
#############################################################################################

	### LOAD MENUS ###
	$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_MENUS);
	while ($row = mysql_fetch_assoc($result)) {
		dbhcms_p_add_menu(	dbhcms_f_dbvalue_to_value($row['menu_name'], DBHCMS_C_DT_STRING),
							dbhcms_f_dbvalue_to_value($row['menu_type'], DBHCMS_C_DT_STRING),
							dbhcms_f_dbvalue_to_value($row['menu_layer'], DBHCMS_C_DT_INTEGER),
							dbhcms_f_dbvalue_to_value($row['menu_depth'], DBHCMS_C_DT_INTEGER),
							dbhcms_f_dbvalue_to_value($row['menu_show_restricted'], DBHCMS_C_DT_BOOLEAN),
							dbhcms_f_dbvalue_to_value($row['menu_wrap_all'], DBHCMS_C_DT_TEXT),
							dbhcms_f_dbvalue_to_value($row['menu_wrap_normal'], DBHCMS_C_DT_TEXT),
							dbhcms_f_dbvalue_to_value($row['menu_wrap_active'], DBHCMS_C_DT_TEXT),
							dbhcms_f_dbvalue_to_value($row['menu_wrap_selected'], DBHCMS_C_DT_TEXT),
							dbhcms_f_dbvalue_to_value($row['menu_link_normal'], DBHCMS_C_DT_TEXT),
							dbhcms_f_dbvalue_to_value($row['menu_link_active'], DBHCMS_C_DT_TEXT),
							dbhcms_f_dbvalue_to_value($row['menu_link_selected'], DBHCMS_C_DT_TEXT)
						);
	}

	### GET DOMAIN OBJECTS IF FE ###
	if ($GLOBALS['DBHCMS']['PID'] > 0) {
		### GET DOMAIN MODULES ###
		foreach ($GLOBALS['DBHCMS']['DOMAIN']['modules'] as $tmkey => $tmvalue) { 
			if ( trim($tmvalue) != "") { 
				dbhcms_p_add_php_module('nr'.count($GLOBALS['DBHCMS']['STRUCT']['PHP']), $tmvalue); 
			}
		}
		### GET DOMAIN TEMPLATES ###
		foreach ($GLOBALS['DBHCMS']['DOMAIN']['templates'] as $tmkey => $tmvalue) { 
			if ( trim($tmvalue) != "") { 
				dbhcms_p_add_template('nr'.count($GLOBALS['DBHCMS']['STRUCT']['TPL']), $tmvalue); 
			}
		}
		### GET DOMAIN STYLESHEETS ###
		foreach ($GLOBALS['DBHCMS']['DOMAIN']['stylesheets'] as $tmkey => $tmvalue) { 
			if ( trim($tmvalue) != "") { 
				dbhcms_p_add_stylesheet('nr'.count($GLOBALS['DBHCMS']['STRUCT']['CSS']), $tmvalue); 
			}
		}
		### GET DOMAIN JAVASCRIPTS ###
		foreach ($GLOBALS['DBHCMS']['DOMAIN']['javascripts'] as $tmkey => $tmvalue) { 
			if ( trim($tmvalue) != "") { 
				dbhcms_p_add_javascript('nr'.count($GLOBALS['DBHCMS']['STRUCT']['JS']), $tmvalue); 
			}
		}
		### GET DOMAIN EXTENSIONS ###
		foreach ($GLOBALS['DBHCMS']['DOMAIN']['extensions'] as $tmkey => $tmvalue) { 
			if ( trim($tmvalue) != "") { 
				dbhcms_p_add_extension($tmvalue);
			}
		}
	} else if ($GLOBALS['DBHCMS']['PID'] != 0)  {
		foreach ($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['availableExtensions'] as $tmvalue) {
			if ( trim($tmvalue) != "") { 
				dbhcms_p_add_extension($tmvalue);
			}
		}
	}

	### GET PARENT PAGE OBJECTS IF HEREDITARY ###
	if ($GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['hierarchy'] == DBHCMS_C_HT_HEREDITARY) {
		# $page_obj = array('mods'=>array(), 'tpls'=>array(), 'css'=>array(), 'js'=>array())
		$page_obj = dbhcms_f_get_page_obj_hereditary($GLOBALS['DBHCMS']['PID'], $GLOBALS['DBHCMS']['PTREE']['location']);
		foreach ($page_obj['mods'] as $tmvalue) {
			if ( trim($tmvalue) != "") { 
				dbhcms_p_add_php_module('nr'.count($GLOBALS['DBHCMS']['STRUCT']['PHP']), $tmvalue); 
			}
		}
		foreach ($page_obj['tpls'] as $tmvalue) {
			if ( trim($tmvalue) != "") { 
				dbhcms_p_add_template('nr'.count($GLOBALS['DBHCMS']['STRUCT']['TPL']), $tmvalue); 
			}
		}
		foreach ($page_obj['css'] as $tmvalue) {
			if ( trim($tmvalue) != "") { 
				dbhcms_p_add_stylesheet('nr'.count($GLOBALS['DBHCMS']['STRUCT']['CSS']), $tmvalue); 
			}
		}
		foreach ($page_obj['js'] as $tmvalue) {
			if ( trim($tmvalue) != "") { 
				dbhcms_p_add_javascript('nr'.count($GLOBALS['DBHCMS']['STRUCT']['JS']), $tmvalue); 
			}
		}
	}

	### GET PAGE MODULES ### 
	foreach ($GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['modules'] as $tmkey => $tmvalue) { 
		if ( trim($tmvalue) != "") { 
			dbhcms_p_add_php_module('nr'.count($GLOBALS['DBHCMS']['STRUCT']['PHP']), $tmvalue); 
		}
	}
	foreach ($GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['params']['modules'] as $tmkey => $tmvalue) { 
		if ( trim($tmvalue) != "") { 
			dbhcms_p_add_php_module('nr'.count($GLOBALS['DBHCMS']['STRUCT']['PHP']), $tmvalue);
		}
	}
	### GET PAGE TEMPLATES ### 
	foreach ($GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['templates'] as $tmkey => $tmvalue) { 
		if ( trim($tmvalue) != "") { 
			dbhcms_p_add_template('nr'.count($GLOBALS['DBHCMS']['STRUCT']['TPL']), $tmvalue); 
		}
	}
	foreach ($GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['params']['templates'] as $tmkey => $tmvalue) { 
		if ( trim($tmvalue) != "") { 
			dbhcms_p_add_template('nr'.count($GLOBALS['DBHCMS']['STRUCT']['TPL']), $tmvalue); 
		}
	}
	### GET PAGE STYLESHEETS ### 
	foreach ($GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['stylesheets'] as $tmkey => $tmvalue) { 
		if ( trim($tmvalue) != "") { 
			dbhcms_p_add_stylesheet('nr'.count($GLOBALS['DBHCMS']['STRUCT']['CSS']), $tmvalue); 
		}
	}
	foreach ($GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['params']['stylesheets'] as $tmkey => $tmvalue) { 
		if ( trim($tmvalue) != "") { 
			dbhcms_p_add_stylesheet('nr'.count($GLOBALS['DBHCMS']['STRUCT']['CSS']), $tmvalue);
		}
	}
	### GET PAGE JAVASCRIPTS ### 
	foreach ($GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['javascripts'] as $tmkey => $tmvalue) { 
		if ( trim($tmvalue) != "") {
			dbhcms_p_add_javascript('nr'.count($GLOBALS['DBHCMS']['STRUCT']['JS']), $tmvalue);
		}
	}
	foreach ($GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['params']['javascripts'] as $tmkey => $tmvalue) { 
		if ( trim($tmvalue) != "") {
			dbhcms_p_add_javascript('nr'.count($GLOBALS['DBHCMS']['STRUCT']['JS']), $tmvalue); 
		}
	}
	### GET PAGE EXTENSIONS ### 
	foreach ($GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['extensions'] as $tmkey => $tmvalue) { 
		if ( trim($tmvalue) != "") {
			dbhcms_p_add_extension($tmvalue); 
		}
	}

#############################################################################################
#  INCLUDE EXTENSIONS                                                                       #
#############################################################################################

	$GLOBALS['DBHCMS']['STRUCT']['EXT'] = array_unique($GLOBALS['DBHCMS']['STRUCT']['EXT']);

	foreach($GLOBALS['DBHCMS']['STRUCT']['EXT'] as $ext) { 
		# global file
		include_once($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].$ext.'/ext.'.$ext.'.gl.php');
		if ($GLOBALS['DBHCMS']['PID'] > 0) {
			# front end file
			include_once($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].$ext.'/ext.'.$ext.'.fe.php');
		} else if (dbhcms_f_superuser_auth()) {
			if ($GLOBALS['DBHCMS']['PID'] == $GLOBALS['DBHCMS']['CONFIG']['CORE']['extPageId']) {
				if (isset($_GET['ext'])) {
					if ($_GET['ext'] == $ext) {
						# back end file
						include_once($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].$ext.'/ext.'.$ext.'.be.php');
					}
				} elseif (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['ext'])) {
					if ($GLOBALS['DBHCMS']['TEMP']['PARAMS']['ext'] == $ext) {
						# back end file
						include_once($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].$ext.'/ext.'.$ext.'.be.php');
					}
				}
			}
		}
	}

#############################################################################################
#  INCLUDE MODULES                                                                          #
#############################################################################################

	foreach($GLOBALS['DBHCMS']['STRUCT']['PHP'] as $tmkey => $tmvalue) { 
		include($tmvalue); 
	}

#############################################################################################
#  CORE MARKS                                                                               #
#############################################################################################

	### GENERAL ###
	dbhcms_p_add_value("coreVersion", $GLOBALS['DBHCMS']['CONFIG']['CORE']['version'], DBHCMS_C_DT_STRING);
	dbhcms_p_add_value("coreDebug", $GLOBALS['DBHCMS']['CONFIG']['CORE']['debug'], DBHCMS_C_DT_BOOLEAN);
	dbhcms_p_add_value("coreSupportedLangs", $GLOBALS['DBHCMS']['CONFIG']['CORE']['supportedLangs'], DBHCMS_C_DT_LANGARRAY);
	dbhcms_p_add_value("coreDefaultLang", $GLOBALS['DBHCMS']['CONFIG']['CORE']['defaultLang'], DBHCMS_C_DT_LANGUAGE);
	
	### DIRECTORIES ###
	dbhcms_p_add_value("coreDirectory", $GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'], DBHCMS_C_DT_DIRECTORY);
	dbhcms_p_add_value("coreImageDirectory", $GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'], DBHCMS_C_DT_DIRECTORY);
	dbhcms_p_add_value("coreAppsDirectory", $GLOBALS['DBHCMS']['CONFIG']['CORE']['appsDirectory'], DBHCMS_C_DT_DIRECTORY);
	dbhcms_p_add_value("coreJavaDirectory", $GLOBALS['DBHCMS']['CONFIG']['CORE']['javaDirectory'], DBHCMS_C_DT_DIRECTORY);
	dbhcms_p_add_value("coreCssDirectory", $GLOBALS['DBHCMS']['CONFIG']['CORE']['cssDirectory'], DBHCMS_C_DT_DIRECTORY);
	dbhcms_p_add_value("coreIncDirectory", $GLOBALS['DBHCMS']['CONFIG']['CORE']['incDirectory'], DBHCMS_C_DT_DIRECTORY);
	dbhcms_p_add_value("coreLibDirectory", $GLOBALS['DBHCMS']['CONFIG']['CORE']['libDirectory'], DBHCMS_C_DT_DIRECTORY);
	dbhcms_p_add_value("coreModuleDirectory", $GLOBALS['DBHCMS']['CONFIG']['CORE']['moduleDirectory'], DBHCMS_C_DT_DIRECTORY);
	dbhcms_p_add_value("coreTemplateDirectory", $GLOBALS['DBHCMS']['CONFIG']['CORE']['templateDirectory'], DBHCMS_C_DT_DIRECTORY);
	dbhcms_p_add_value("coreExtensionDirectory", $GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'], DBHCMS_C_DT_DIRECTORY);
	dbhcms_p_add_value("coreTempDirectory", $GLOBALS['DBHCMS']['CONFIG']['CORE']['tempDirectory'], DBHCMS_C_DT_DIRECTORY);
	
	### APPLICATION URLS ###
	dbhcms_p_add_value("appUrlPhpmyadmin", $GLOBALS['DBHCMS']['CONFIG']['CORE']['appsDirectory']."phpmyadmin/index.php", DBHCMS_C_DT_FILE);
	dbhcms_p_add_value("appUrlQuixplorer", $GLOBALS['DBHCMS']['CONFIG']['CORE']['appsDirectory']."quixplorer/index.php", DBHCMS_C_DT_FILE);

#############################################################################################
#  CONFIG MARKS                                                                             #
#############################################################################################

	foreach ($GLOBALS['DBHCMS']['CONFIG']['PARAMS'] as $pname => $pvalue) {
		if ($pname != 'paramDataTypes') {
			dbhcms_p_add_value($pname, $pvalue, $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['paramDataTypes'][$pname]);
		}
	}

	### ADITIONAL MARKS ###
	dbhcms_p_add_value("sessionLifeTime_s", intval($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['sessionLifeTime'])*60, DBHCMS_C_DT_INTEGER);
	dbhcms_p_add_value("sessionLifeTime_h", round(intval($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['sessionLifeTime'])/60), DBHCMS_C_DT_INTEGER);

#############################################################################################
#  DOMAIN MARKS                                                                             #
#############################################################################################

	### GENERAL ###
	dbhcms_p_add_value("domainAbsoluteUrl", $GLOBALS['DBHCMS']['DOMAIN']['absoluteUrl'], DBHCMS_C_DT_DIRECTORY);
	dbhcms_p_add_value("domainSubFolderCount", $GLOBALS['DBHCMS']['DOMAIN']['subFolderCount'], DBHCMS_C_DT_INTEGER);
	dbhcms_p_add_value("domainHostName", $GLOBALS['DBHCMS']['DOMAIN']['hostName'], DBHCMS_C_DT_STRING);
	dbhcms_p_add_value("domainSubFolders", $GLOBALS['DBHCMS']['DOMAIN']['subFolders'], DBHCMS_C_DT_DIRECTORY);
	dbhcms_p_add_value("domainSupportedLangs", $GLOBALS['DBHCMS']['DOMAIN']['supportedLangs'], DBHCMS_C_DT_LANGARRAY);
	dbhcms_p_add_value("domainDefaultLang", $GLOBALS['DBHCMS']['DOMAIN']['defaultLang'], DBHCMS_C_DT_LANGUAGE);
	
	### PAGES ###
	dbhcms_p_add_value('index', $GLOBALS['DBHCMS']['DOMAIN']['indexPageId'], DBHCMS_C_DT_PAGE);
	dbhcms_p_add_value('intro', $GLOBALS['DBHCMS']['DOMAIN']['introPageId'], DBHCMS_C_DT_PAGE);
	dbhcms_p_add_value('logout', $GLOBALS['DBHCMS']['DOMAIN']['logoutPageId'], DBHCMS_C_DT_PAGE);
	dbhcms_p_add_value('login', $GLOBALS['DBHCMS']['DOMAIN']['loginPageId'], DBHCMS_C_DT_PAGE);
	dbhcms_p_add_value('accessDenied', $GLOBALS['DBHCMS']['DOMAIN']['accessDeniedPageId'], DBHCMS_C_DT_PAGE);
	dbhcms_p_add_value('err401', $GLOBALS['DBHCMS']['DOMAIN']['err401PageId'], DBHCMS_C_DT_PAGE);
	dbhcms_p_add_value('err403', $GLOBALS['DBHCMS']['DOMAIN']['err403PageId'], DBHCMS_C_DT_PAGE);
	dbhcms_p_add_value('err404', $GLOBALS['DBHCMS']['DOMAIN']['err404PageId'], DBHCMS_C_DT_PAGE);

#############################################################################################
#  PAGE MARKS                                                                               #
#############################################################################################

	### ACTUAL PAGE VALS ### 
	dbhcms_p_add_value('pageId', $GLOBALS['DBHCMS']['PID'], DBHCMS_C_DT_INTEGER);
	dbhcms_p_add_value('pageUrl', dbhcms_f_get_url_from_pid($GLOBALS['DBHCMS']['PID']), DBHCMS_C_DT_STRING);
	dbhcms_p_add_value('pageUrlWp', dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], $GLOBALS['DBHCMS']['TEMP']['PARAMS']), DBHCMS_C_DT_STRING);
	dbhcms_p_add_value('pageName', $GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['params'][DBHCMS_C_PAGEVAL_NAME], DBHCMS_C_DT_STRING);
	dbhcms_p_add_value('pageContent', $GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['params'][DBHCMS_C_PAGEVAL_CONTENT], DBHCMS_C_DT_CONTENT);
	dbhcms_p_add_value('pageParentId', $GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['parentId'], DBHCMS_C_DT_PAGE);
	dbhcms_p_add_value('pageDomainId', $GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['domainId'], DBHCMS_C_DT_DOMAIN);
	dbhcms_p_add_value('pagePosNr', $GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['posNr'], DBHCMS_C_DT_INTEGER);
	dbhcms_p_add_value('pageHierarchy', $GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['hierarchy'], DBHCMS_C_DT_HIERARCHY);
	dbhcms_p_add_value('pageHide', $GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['hide'], DBHCMS_C_DT_BOOLEAN);
	dbhcms_p_add_value('pageSchedule', $GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['schedule'], DBHCMS_C_DT_BOOLEAN);
	dbhcms_p_add_value('pageStart', $GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['start'], DBHCMS_C_DT_DATETIME);
	dbhcms_p_add_value('pageStop', $GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['stop'], DBHCMS_C_DT_DATETIME);
	dbhcms_p_add_value('pageInMenu', $GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['inMenu'], DBHCMS_C_DT_BOOLEAN);
	dbhcms_p_add_value('pageLink', $GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['link'], DBHCMS_C_DT_STRING);
	dbhcms_p_add_value('pageTarget', $GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['target'], DBHCMS_C_DT_STRING);
	dbhcms_p_add_value('pageUserLevel', $GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['userLevel'], DBHCMS_C_DT_USERLEVEL);
	dbhcms_p_add_value('pageLastEdited', $GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['lastEdited'], DBHCMS_C_DT_DATETIME);
	dbhcms_p_add_value('pageDescription', $GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['description'], DBHCMS_C_DT_TEXT);
	
	### ACTUAL PAGE PARAMS ###
	foreach ($GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['params'] as $pname => $pvalue) {
		if ($pname != 'paramDataTypes') {
			if (!in_array($pname, array(DBHCMS_C_PAGEVAL_TEMPLATES, DBHCMS_C_PAGEVAL_STYLESHEETS, DBHCMS_C_PAGEVAL_JAVASCRIPTS, DBHCMS_C_PAGEVAL_PHPMODULES))) {
				dbhcms_p_add_value('pageParam'.ucfirst($pname), $pvalue, dbhcms_f_get_page_param_dt($GLOBALS['DBHCMS']['PID'], $pname));
			}
		}
	}
	
	### IMPORTANT VALUES OF OTHER PAGES IN THE DOMAIN ###
	foreach ($GLOBALS['DBHCMS']['PAGES'] as $pid => $pdata) {
		dbhcms_p_add_value('pid'.$pid, $pid, DBHCMS_C_DT_PAGE);
	}

#############################################################################################
#  LANGUAGE MARKS                                                                           #
#############################################################################################

	### CURRENT LANGUAGE ###
	dbhcms_p_add_string("feLang", $_SESSION['DBHCMSDATA']['LANG']['useLanguage']);
	dbhcms_p_add_string("beLang", $_SESSION['DBHCMSDATA']['LANG']['coreUseLanguage']);
	
	### URLS TO CHANGE LANGUAGE ###
	foreach ($GLOBALS['DBHCMS']['DOMAIN']['supportedLangs'] as $lang) {
		dbhcms_p_add_string("pageUrl_".$lang, dbhcms_f_generate_url($GLOBALS['DBHCMS']['DID'], $GLOBALS['DBHCMS']['PID'], dbhcms_f_get_page_value($GLOBALS['DBHCMS']['PID'], DBHCMS_C_PAGEVAL_URL, $lang), $lang, $GLOBALS['DBHCMS']['TEMP']['PARAMS']));
	}

#############################################################################################
#  USER MARKS                                                                               #
#############################################################################################

	dbhcms_p_add_string("userName", $_SESSION['DBHCMSDATA']['AUTH']['userName']);
	dbhcms_p_add_string("userId", $_SESSION['DBHCMSDATA']['AUTH']['userId']);
	dbhcms_p_add_string("userRealName", $_SESSION['DBHCMSDATA']['AUTH']['userRealName']);
	dbhcms_p_add_string("userSex", $_SESSION['DBHCMSDATA']['AUTH']['userSex']);
	dbhcms_p_add_string("userCompany", $_SESSION['DBHCMSDATA']['AUTH']['userCompany']);
	dbhcms_p_add_string("userLocation", $_SESSION['DBHCMSDATA']['AUTH']['userLocation']);
	dbhcms_p_add_string("userEmail", $_SESSION['DBHCMSDATA']['AUTH']['userEmail']);
	dbhcms_p_add_string("userWebsite", $_SESSION['DBHCMSDATA']['AUTH']['userWebsite']);
	dbhcms_p_add_string("userLang", $_SESSION['DBHCMSDATA']['AUTH']['userLang']);

#############################################################################################
#  OTHER MARKS                                                                              #
#############################################################################################

	if ($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['simulateStaticUrls'] == 1) {
		dbhcms_p_add_string("firstUrlParamSymbol", '?');
		dbhcms_p_add_string('beUrl', 'admin.html');
		dbhcms_p_add_string('beLoginUrl', 'belogin.html');
	} else {
		dbhcms_p_add_string("firstUrlParamSymbol", '&');
		dbhcms_p_add_string('beUrl', 'index.php?dbhcms_pid='.$GLOBALS['DBHCMS']['CONFIG']['CORE']['indexPageId']);
		dbhcms_p_add_string('beLoginUrl', 'index.php?dbhcms_pid='.$GLOBALS['DBHCMS']['CONFIG']['CORE']['loginPageId']);
	}

	dbhcms_p_add_string('dbhcmsVersion', DBHCMS);

#############################################################################################
#  DEBUG MODUS                                                                              #
#############################################################################################

	if ($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['debugModus'] == 1) {
		
		dbhcms_p_add_block('dbhcmsDebug', array('dbhcmsSessions', 'dbhcmsGlobalParams', 'dbhcmsSessionParams'));
		
		# hide db password
		$arr_global_dbhcms = $GLOBALS['DBHCMS'];
		$arr_global_dbhcms['CONFIG']['DB']['passwd'] = '****';
		# hide user password
		$arr_session_dbhcms = $_SESSION['DBHCMSDATA'];
		$arr_session_dbhcms['AUTH']['password'] = '****';
		
		dbhcms_p_add_block_values('dbhcmsDebug', array(dbhcms_f_get_arr_html(dbhcms_f_get_sessions()), dbhcms_f_get_arr_html($arr_global_dbhcms), dbhcms_f_get_arr_html($arr_session_dbhcms)));
		
		unset($arr_global_dbhcms, $arr_session_dbhcms, $instance_info);
		
	} else {
		# hide the debug modus block
		dbhcms_p_hide_block('dbhcmsDebug');
	}

#############################################################################################
#  CREATE PAGE                                                                              #
#############################################################################################

	$dbhcms_tpl = new dbhcms_template();
	$dbhcms_body_tpl_name = '';

	### TEMPLATES ###
	foreach($GLOBALS['DBHCMS']['STRUCT']['TPL'] as $dbhcms_t_tpl_key => $dbhcms_t_tpl_value) { 
		$dbhcms_tpl -> set_file($dbhcms_t_tpl_key, $dbhcms_t_tpl_value);
		foreach($GLOBALS['DBHCMS']['STRUCT']['BLK'] as $dbhcms_t_block_key => $dbhcms_t_block_value) {
			$dbhcms_tpl -> set_block($dbhcms_t_tpl_key, $dbhcms_t_block_key, $dbhcms_t_block_key.'_handle');
			$dbhcms_t_block_params = $dbhcms_t_block_value[0];
			$dbhcms_t_block_values = array();
			foreach ($dbhcms_t_block_value as $dbhcms_t_block_value_key => $dbhcms_t_block_value_value) {
				if ($dbhcms_t_block_value_key > 0) {
					foreach ($dbhcms_t_block_value_value as $tmkey => $tmvalue) {
						dbhcms_f_array_push_assoc($dbhcms_t_block_values, $dbhcms_t_block_key.'.'.$dbhcms_t_block_params[$tmkey]);
						$dbhcms_t_block_values[$dbhcms_t_block_key.'.'.$dbhcms_t_block_params[$tmkey]]= $tmvalue;
					}
					$dbhcms_tpl -> set_var($dbhcms_t_block_values);
					$dbhcms_tpl -> parse($dbhcms_t_block_key.'_handle', $dbhcms_t_block_key, true);
				}
			}
		}
		$dbhcms_tpl -> parse('tpl_'.$dbhcms_t_tpl_key, $dbhcms_t_tpl_key);
		if ($dbhcms_body_tpl_name == "" ) { $dbhcms_body_tpl_name = $dbhcms_t_tpl_key; }
	}

	### CSS ###
	foreach($GLOBALS['DBHCMS']['STRUCT']['CSS'] as $tmkey => $tmvalue) { 
		$dbhcms_tpl -> set_var('css_'.$tmkey, '<link href="'.$tmvalue.'" rel="stylesheet" />');
	}

	### JAVA ###
	foreach($GLOBALS['DBHCMS']['STRUCT']['JS'] as $tmkey => $tmvalue) { 
		$dbhcms_tpl -> set_var('js_'.$tmkey, '<script src="'.$tmvalue.'" type="text/javascript"></script>');
	}

	### STRINGS ###
	foreach($GLOBALS['DBHCMS']['STRUCT']['STR'] as $tmkey => $tmvalue) { 
		$dbhcms_tpl -> set_var('str_'.$tmkey, $tmvalue);
	}

	### DICTIONARY ###
	foreach ($GLOBALS['DBHCMS']['DICT']['FE'] as $tmkey => $tmvalue) {
		$dbhcms_tpl -> set_var('dict_'.$tmkey, $tmvalue);
	}
	if ($GLOBALS['DBHCMS']['PID'] <= 0 ) {
		foreach ($GLOBALS['DBHCMS']['DICT']['BE'] as $tmkey => $tmvalue) {
			$dbhcms_tpl -> set_var('bedict_'.$tmkey, $tmvalue);
		}
	}

#############################################################################################
#  CREATE MENUS                                                                             #
#############################################################################################

	if ($GLOBALS['DBHCMS']['PID'] > 0 ) {
		foreach ($GLOBALS['DBHCMS']['STRUCT']['MEN'] as $tmkey => $tmvalue) { 
			if ($GLOBALS['DBHCMS']['STRUCT']['MEN'][$tmkey]['menuType'] == DBHCMS_C_MT_LOCATION) {
				# get page tree
				$page_tree = $GLOBALS['DBHCMS']['PTREE']['location'];
				# get values for menu
				$menu_values = dbhcms_f_get_menu_array_from_pagetree($GLOBALS['DBHCMS']['PID'], $GLOBALS['DBHCMS']['STRUCT']['MEN'][$tmkey], $page_tree, false, 1, false);
				$menu_html = '';
				foreach ($menu_values as $menu_entry) {
					$menu_html .=  $menu_entry;
				}
				# wrap all
				$menu_html = str_replace('|', $menu_html, dbhcms_f_str_replace_some_vars($GLOBALS['DBHCMS']['STRUCT']['MEN'][$tmkey]['menuWrapAll'],  true, true, true, true, true, false, true, true, true));
				# export menu
				$dbhcms_tpl -> set_var('menu_'.$tmkey, $menu_html);
			} else if (($GLOBALS['DBHCMS']['STRUCT']['MEN'][$tmkey]['menuType'] == DBHCMS_C_MT_ACTIVETREE)||($GLOBALS['DBHCMS']['STRUCT']['MEN'][$tmkey]['menuType'] == DBHCMS_C_MT_TREE)) {
				# active or static ?
				if ($GLOBALS['DBHCMS']['STRUCT']['MEN'][$tmkey]['menuType'] == DBHCMS_C_MT_ACTIVETREE) { 
					$onlyactive = true; 
				} else { $onlyactive = false; }
				# create page tree
				if ($GLOBALS['DBHCMS']['STRUCT']['MEN'][$tmkey]['menuLayer'] > 1) {
					$page_tree = $GLOBALS['DBHCMS']['PTREE']['single'];
				} else { $page_tree = $GLOBALS['DBHCMS']['PTREE']['complete']; }
				# get values for menu
				$menu_values = dbhcms_f_get_menu_array_from_pagetree($GLOBALS['DBHCMS']['PID'], $GLOBALS['DBHCMS']['STRUCT']['MEN'][$tmkey], $page_tree, $onlyactive, 1, false);
				$menu_html = '';
				foreach ($menu_values as $menu_entry) {
					$menu_html .=  $menu_entry;
				}
				$menu_html = str_replace('|', $menu_html, dbhcms_f_str_replace_some_vars($GLOBALS['DBHCMS']['STRUCT']['MEN'][$tmkey]['menuWrapAll'],  true, true, true, true, true, false, true, true, true));
				# export menu
				$dbhcms_tpl -> set_var('menu_'.$tmkey, $menu_html);
			}
		}
	}

#############################################################################################
#  SCRIPTDURATION                                                                           #
#############################################################################################

	$dbhcms_tpl -> set_var('str_scriptDuration', (dbhcms_f_getmicrotime() - $GLOBALS['DBHCMS']['TEMP']['scriptStartTime']));

#############################################################################################
#  FINAL PARSE                                                                              #
#############################################################################################

	$cache = $dbhcms_tpl -> pparse("output", $dbhcms_body_tpl_name);

#############################################################################################
#  CACHE FILE                                                                               #
#############################################################################################

	if (($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['cacheEnabled']) && ($GLOBALS['DBHCMS']['PID'] > 0)) {

		if ((count($GLOBALS['DBHCMS']['RESULTS']) == 0) && ($GLOBALS['DBHCMS']['TEMP']['dbhcmsCache'] != DBHCMS_C_CT_OFF) && $GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['cache']) {
			
			$path_parts = pathinfo($_SERVER['REQUEST_URI']);
			
			if ($_SESSION['DBHCMSDATA']['AUTH']['authenticated']) {
				$cache_userid = $_SESSION['DBHCMSDATA']['AUTH']['userId'];
			} else { 
				$cache_userid = "x"; 
			}
			
			mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_CACHE." (`cach_sessionid` , `cach_page_id` , `cach_user_id` , `cach_lang` , `cach_requesturi` , `cach_timestamp` ) VALUES ( '".$_SESSION['DBHCMSDATA']['SID']."', '".$GLOBALS['DBHCMS']['PID']."', '".$cache_userid."', '".$_SESSION['DBHCMSDATA']['LANG']['useLanguage']."', '".trim($_SERVER['REQUEST_URI'])."', NOW( ) ); ");
			$cache_id = mysql_insert_id();
			
			$temp_dir = $GLOBALS['DBHCMS']['CONFIG']['CORE']['tempDirectory'];
			$temp_file = "tmp.cache.".$cache_id.".".$GLOBALS['DBHCMS']['PID'].".".$cache_userid.".".$_SESSION['DBHCMSDATA']['LANG']['useLanguage'].".".$_SESSION['DBHCMSDATA']['SID'].".html";
			
			$content_file = fopen($temp_dir.$temp_file, "w");
			fwrite($content_file, $cache);
			fclose($content_file);
			
		}

	}

#############################################################################################
#  !!! THATS IT FOLKS !!!                                                                   #
#############################################################################################

	print($cache);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>