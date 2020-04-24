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
#  FILE NAME                                                                                #
#  =============================                                                            #
#  dbhcms/func.php [func]                                                                   #
#                                                                                           #
#  FILE VERSION                                                                             #
#  =============================                                                            #
#  V 0.2 - 01.01.2007                                                                       #
#                                                                                           #
#  FILE DESCRIPTION                                                                         #
#  =============================                                                            #
#  Standard functions and procedures of the DBHcms.                                         #
#  Format for functions: dbhcms_f_xxxx                                                      #
#  Format for procedures: dbhcms_p_xxxx                                                     #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  CHANGES                                                                                  #
#  =============================                                                            #
#                                                                                           #
#  13.12.2006 - [KSB]:                                                                      #
#  -------------------                                                                      #
#  Added extended file-registering procedures                                               #
#                                                                                           #
#  28.10.2005 - [KSB]:                                                                      #
#  -------------------                                                                      #
#  File created.                                                                            #
#                                                                                           #
#############################################################################################
# $Id: func.php 68 2007-05-31 20:28:17Z kaisven $                                           #
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

	dbhcms_p_register_file(realpath(__FILE__), 'func', 0.2);

#############################################################################################
#  IMPLEMENTATION                                                                           #
#############################################################################################

	#--------------------------------------------------------------------------#
	# DBHCMS_P_DEL_GLOBALS                                                     #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Deletes all global variables except those admited by the DBHcms          #
	#--------------------------------------------------------------------------#
	function dbhcms_p_del_globals() {
		$del = array();
		$ag = array(	'GLOBALS', 
									'_ENV', 
									'HTTP_ENV_VARS', 
									'_POST', 
									'HTTP_POST_VARS', 
									'_GET', 
									'HTTP_GET_VARS', 
									'_COOKIE', 
									'HTTP_COOKIE_VARS', 
									'_SERVER', 
									'HTTP_SERVER_VARS', 
									'_FILES', 
									'HTTP_POST_FILES', 
									'_REQUEST',
									'HTTP_SESSION_VARS',
									'_SESSION',
									'DBHCMS'
								);
		if (!defined('DBHCMS_EXTERNAL')) {
			foreach($GLOBALS as $vname => $vvalue) {
				if (!in_array($vname, $ag)) {
					$del[$vname] = $vvalue;
					unset($GLOBALS[$vname]);
				}
			}
		}
		return $del;
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_ERROR                                                           #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Logs an error in the database. If the error is fatal executes the        #
	# function "die()".                                                        #
	#                                                                          #
	# PARAMETERS:                                                              #
	# ==============                                                           #
	# @aerror (string) : Error name                                            #
	# @die (boolean) : If TRUE then die will be called with fatal error msg    #
	# @file (string) : File name, usually filled with __FILE__                 #
	# @class (string) : Class name, usually filled with __CLASS__              #
	# @function (string) : Function name, usually filled with __FUNCTION__     #
	# @line (string) : Line number, usually filled with __LINE__               #
	#--------------------------------------------------------------------------#
 	function dbhcms_p_error($aerror, $die, $file, $class, $function, $line) {
		# if connected to database then log error
		if (defined('DBHCMS_MYSQL_CONNECTED')) {
			if ($die) $die_int = '1'; else $die_int = '0';
			# get variables and hide db password
			if (isset($GLOBALS['DBHCMS'])) {
				$arr_global_dbhcms = $GLOBALS['DBHCMS'];
				$arr_global_dbhcms['CONFIG']['DB']['passwd'] = '****';
				$arr_global_dbhcms['CONFIG']['DB']['user'] = '****';
			} else {
				$arr_global_dbhcms = array();
			}
			# get session and hide user password
			if (isset($_SESSION['DBHCMSDATA'])) {
				$arr_session_dbhcms = $_SESSION['DBHCMSDATA'];
				$arr_session_dbhcms['AUTH']['password'] = '****';
				$arr_session_dbhcms['AUTH']['userName'] = '****';
			} else {
				$arr_session_dbhcms = array();
			}
			# log error
			mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_ERRORLOG." ( `erlg_sessionid`, `erlg_file` , `erlg_class` , `erlg_function` , `erlg_line` , `erlg_error`, `erlg_isfatal` , `erlg_instinfo` , `erlg_datetime` ) 
							VALUES ( '".$_SESSION['DBHCMSDATA']['SID']."', '".addslashes($file)."', '".$class."', '".$function."', '".$line."', '".$aerror."', '".$die_int."', '".str_replace("'", "''", print_r($arr_global_dbhcms, true)."<br /><br />".print_r($arr_session_dbhcms, true))."', NOW( ) );");
			# delete variables and session-data
			unset($arr_session_dbhcms, $arr_global_dbhcms);
		}
		# call function die() if fatal error
		if ($die) {
			# this is not a valid page so we send a 404 error for the SE
			header("HTTP/1.0 404 Not Found");
			# get directory for image ;)
			if (!isset($GLOBALS['dbhcms_core_dir'])) {
				if (isset($GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'])) {
					$GLOBALS['dbhcms_core_dir'] = $GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'];
				} else {
					if (defined('DBHCMS_EXTERNAL')) {
						$GLOBALS['dbhcms_core_dir'] = DBHCMS_EXTERNAL_DTR;
					} else {
						$GLOBALS['dbhcms_core_dir'] = '';
					}
				}
			}
			# die error
			die('	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
						<head>
							<title>
								DBHcms - Fatal Error !
							</title>
							<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
							<style type="text/css">
								#wrapper { position: relative; top: 30px; width: 400px; height: auto; margin: 0px auto; }
								#banner  { position: relative; width: 400px; height: 112px; margin: 0px; padding: 0px; border: 1px solid #444DFE; background: url("'.$GLOBALS['dbhcms_core_dir'].'errbn.jpg") no-repeat; }
								#title 	 { position: absolute; left: 100px; top: 40px; width: 400px; height: auto; margin: 0px; padding: 0px; text-align: center; }
								#message { position: relative; width: 400px; margin: 0px; padding: 0px; border: 1px solid #444DFE; border-top: 0px; background: #FFFFFF; }
							</style>
						</head>
						<body style="margin: 3px; background: #E2E6F9; font-family: Verdana, Arial, Helvetica; font-size: 8pt;">
							<div id="wrapper">
								<div id="banner">
									<div id="title"><h1 style="color: #872626; font-size: 18pt;">ERROR</h1></div>
								</div>
								<div id="message">
									<table width="400" cellpadding="8" cellspacing="0" border="0">
										<tr>
											<td bgcolor="#FFFFFF" align="left">
												<br />
												<div style="color: #872626; font-weight: bold;">
													FATAL ERROR - '.$aerror.'
												</div>
												<br />
											</td>
										</tr>
									</table>
								</div>
								<div align="center">
									<a target="_blank" href="http://www.drbenhur.com/" style="font-size: 10px; color:#444DFE;">powered by DBHcms</a>
								</div>
							</div>
						</body>
					</html>'
			);
		}
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_REGISTER_FILE                                                   #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Registers a file in the current DBHcms instance.                         #
	#                                                                          #
	# PARAMETERS:                                                              #
	# ==============                                                           #
	# @file (string) : File name, usually filled with "realpath(__FILE__)"     #
	# @alias (string) : File alias                                             #
	# @version (integer) : Version of the file                                 #
	#--------------------------------------------------------------------------#
	function dbhcms_p_register_file($file, $alias, $version) {
		$alias = strtoupper($alias);
		if (!defined('DBHCMS_C_FL_'.$alias)) {
			define('DBHCMS_C_FL_'.$alias, $file);
			define('DBHCMS_C_FL_V_'.$alias, $version);
		}
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_REQUIRE_FILE                                                    #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Requests a file with "require_once($file)" and checks version from the   #
	# registered file in the current DBHcms instance. When included, all       #
	# global variables not admited by the DBHcms are deleted.                  #
	#                                                                          #
	# PARAMETERS:                                                              #
	# ==============                                                           #
	# @file (string) : File name, usually filled with "realpath(__FILE__)"     #
	# @alias (string) : File alias                                             #
	# @version (integer) : Version of the file                                 #
	#--------------------------------------------------------------------------#
	function dbhcms_p_require_file($file, $alias, $version) {
		$alias = strtoupper($alias);
		if (!defined('DBHCMS_C_FL_'.$alias)) {
			if (is_file($file)) {
				# Include file
				require_once($file);
				# Delete all globals
				dbhcms_p_del_globals();
				# Define constants and check version
				if (defined('DBHCMS_C_FL_V_'.$alias)) { 
					$file_version = constant('DBHCMS_C_FL_V_'.$alias);
					if ($file_version < $version) {
						dbhcms_p_error('File "'.$file.'" is too old. Found V'.$file_version.' but required at least V'.$version, true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
					}
				} else {
						dbhcms_p_error('File "'.$file.'" was not correctly registered.', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
				}
			} else {
				dbhcms_p_error('File "'.$file.'" does not exist.', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			}
		}
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_DICT                                                            #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns the value in the dictionary for the given item name @adictname   #
	# of the current language in the FE. If @be is TRUE then it will use the   #
	# current language of the BE.                                              #
	#                                                                          #
	# PARAMETERS:                                                              #
	# ==============                                                           #
	# @adictname : Name or string-id of the requested dictionary item.         #
	# @be : TRUE uses the BE language. FALSE uses the FE language.             #
	#--------------------------------------------------------------------------#
	function dbhcms_f_getmicrotime(){ 
		list($usec, $sec) = explode(" ",microtime()); 
		return ((float)$usec + (float)$sec); 
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GENERATE_RANDOM_STR                                             #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns a random string. Can be used as password or blowfish generator.  #
	#                                                                          #
	# PARAMETERS:                                                              #
	# ==============                                                           #
	# @length (integer) : Length of the string to be generated.                #
	# @vocal (boolean) : If TRUE the string generated string can               #
	#                    be pronunciated and @chars will be ignored.           #
	# @chars (string) : Chars allowed to be in the string.                     #
	#--------------------------------------------------------------------------#
	function dbhcms_f_generate_random_str($length, $vocal = false, $chars = '0123456789abcdefghijklmnopqrstuvwxyz'){
		$str = '';
		$sfv = true;
		while (strlen($str) < $length) { 
			if ($vocal) {
				if ($sfv) {
					$str .= substr('aeiou', mt_rand(0, 4), 1);
					$sfv = false;
				} else {
					$str .= substr('bcdfghjklmnpqrstvwxyz', mt_rand(0, 20), 1);
					$sfv = true;
				}
			} else {
				$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
			}
		}
		return $str;
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_DICT                                                            #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns the value in the dictionary for the given item name @adictname   #
	# of the current language in the FE. If @be is TRUE then it will use the   #
	# current language of the BE.                                              #
	#                                                                          #
	# PARAMETERS:                                                              #
	# ==============                                                           #
	# @adictname : Name or string-id of the requested dictionary item.         #
	# @be : TRUE uses the BE language. FALSE uses the FE language.             #
	#--------------------------------------------------------------------------#
	function dbhcms_f_dict ($adictname, $be = false) {
		$returnval = '';
		if ($be) {
			if (isset($GLOBALS['DBHCMS']['DICT']['BE'][$adictname])) {
				$returnval = $GLOBALS['DBHCMS']['DICT']['BE'][$adictname];
			}
		} else {
			if (isset($GLOBALS['DBHCMS']['DICT']['FE'][$adictname])) {
				$returnval = $GLOBALS['DBHCMS']['DICT']['FE'][$adictname];
			}
		}
		return $returnval;
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_DICT_ADD_MISSING_VALS                                           #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Adds missing values in the dictionary table depending of the specified   #
	# languages in the "dictionaryLanguages" parameter of the main system      #
	# settings.                                                                #
	#--------------------------------------------------------------------------#
	function dbhcms_p_dict_add_missing_vals() {
		$dict_langs = $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['dictionaryLanguages'];
		$result_name = mysql_query("SELECT dict_name FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DICTIONARY." GROUP BY dict_name ");
		while ($row_name = mysql_fetch_assoc($result_name)) {
			foreach ($dict_langs as $dict_lang) {
				if (mysql_num_rows(mysql_query("SELECT dict_value FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DICTIONARY." WHERE dict_name = '".$row_name['dict_name']."' AND dict_lang = '".$dict_lang."' ")) == 0) {
					mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DICTIONARY." (`dict_name` , `dict_value` , `dict_lang` ) VALUES ( '".$row_name['dict_name']."', '', '".$dict_lang."' ) ");
				}
			}
		}
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GET_LANGUAGE                                                    #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns an array with the initial languages for the FE and BE specific   #
	# for the domain. Gets the language-code of the visitor's country. If the  #
	# language of	the visitor's country is supported by the domain, then it  #
	# sets the language of the	country. If the language of the visitor's      #
	# country is NOT supported the it sets as language the default language    #
	# of the domain.                                                           #
	#--------------------------------------------------------------------------#
	function dbhcms_f_get_language () {
		# Get list of languajes selected in the browser
		$lfb = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
		# Get position of ";" in languajes (in case value is like "en,es;q=0.5")
		$pos = strpos($lfb, ";");  
		# Eliminate from "en,es;q=0.5" the last past (";q=0.5"), which starts in $pos 
		if ($pos > 0) { $lfb = substr($lfb, 0, $pos); }
		# Get an array of languajes and print the corresponding value from $dbhcms_lang_array if
		# language is supported in $dbhcms_supported_langs
		$core_use_lang = '';
		$use_lang = '';
		$all_langs = '';
		$lang_codes = explode(",", $lfb);
		foreach ($lang_codes as $code) {
			if (isset($GLOBALS['DBHCMS']['LANGS'][$code])){
				# Front-End language settings
				if (in_array($GLOBALS['DBHCMS']['LANGS'][$code], $GLOBALS['DBHCMS']['DOMAIN']['supportedLangs'])){
					$use_lang = $GLOBALS['DBHCMS']['LANGS'][$code];
				} else {
					$use_lang = $GLOBALS['DBHCMS']['DOMAIN']['defaultLang'];
				}
				# Back-End language settings
				if (in_array ($GLOBALS['DBHCMS']['LANGS'][$code], $GLOBALS['DBHCMS']['CONFIG']['CORE']['supportedLangs'])){
					$core_use_lang = $GLOBALS['DBHCMS']['LANGS'][$code];
				} else {
					$core_use_lang = $GLOBALS['DBHCMS']['CONFIG']['CORE']['defaultLang'];
				}
			}
			$all_langs .= $code.';';
		}
		if (trim($use_lang) == '') {
			$use_lang = $GLOBALS['DBHCMS']['DOMAIN']['defaultLang'];
		}
		if (trim($core_use_lang) == '') {
			$core_use_lang = $GLOBALS['DBHCMS']['CONFIG']['CORE']['defaultLang'];
		}
		return array('useLanguage' => $use_lang, 'coreUseLanguage' => $core_use_lang, 'all' => $all_langs);
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GET_ORIGIN                                                      #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns an array with data about the origin of the current visit.        #
	#--------------------------------------------------------------------------#
	function dbhcms_f_get_origin () {
		
		$origin_filters = array	(
									# GOOGLE
									array	(
												'name'		=> 'GOOGLE',
												'filter'	=> 'www.google.',
												'param'		=> 'q'
											),
									# DMOZ
									array	(
												'name'		=> 'DMOZ',
												'filter'	=> 'search.dmoz.org',
												'param'		=> 'search'
											),
									# MSN
									array	(
												'name'		=> 'MSN',
												'filter'	=> 'search.msn.',
												'param'		=> 'q'
											),
									# YAHOO
									array	(
												'name'		=> 'YAHOO',
												'filter'	=> 'search.yahoo.',
												'param'		=> 'p'
											),
									# WEB.DE
									array	(
												'name'		=> 'WEB.DE',
												'filter'	=> 'suche.web.de',
												'param'		=> 'su'
											),
									# FIREBALL.DE
									array	(
												'name'		=> 'FIREBALL.DE',
												'filter'	=> 'suche.fireball.de',
												'param'		=> 'query'
											),
									# LYCOS.DE
									array	(
												'name'		=> 'LYCOS.DE',
												'filter'	=> 'suche.lycos.de',
												'param'		=> 'query'
											),
									# LYCOS.COM
									array	(
												'name'		=> 'LYCOS.COM',
												'filter'	=> 'search.lycos.com',
												'param'		=> 'query'
											),
									# ALTAVISTA
									array	(
												'name'		=> 'ALTAVISTA',
												'filter'	=> 'altavista.com',
												'param'		=> 'q'
											),
									# ABACHO
									array	(
												'name'		=> 'ABACHO',
												'filter'	=> 'search.abacho.com',
												'param'		=> 'q'
											),
									# ASK
									array	(
												'name'		=> 'ASK',
												'filter'	=> 'ask.com',
												'param'		=> 'q'
											)
								);
		
		$origin = '';
		$origin_search_engine = '';
		$origin_search_phrase = '';
		
		if (isset($_SERVER['HTTP_REFERER'])) {
			$origin = $_SERVER['HTTP_REFERER'];
		}
		if ($origin == '') {
			$origin = $_SERVER['HTTP_HOST'];
		}
		
		$origin_low = strtolower($origin);
		
		foreach ($origin_filters as $filter) {
			if (substr_count($origin_low, $filter['filter']) > 0) {
				$params = explode('&', substr($origin, (strpos($origin, '?')+1)));
				$count = 0;
				$query = '';
				while ($count < count($params)) {
					$param = split('=', $params[$count]);
					if (strtolower(urldecode($param[0])) == $filter['param']) {
						$origin_search_engine = $filter['name'];
						$origin_search_phrase = urldecode(dbhcms_f_url_replace_symbols($param[1]));
						break 2;
					}
					$count++;
				}
			}
		}
		
		return array('origin' => $origin, 'searchEngine' => $origin_search_engine, 'searchPhrase' => $origin_search_phrase);
		
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GET_DIRS                                                        #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns an array with all the directories and subdirectories from the    #
	# given directory "$adir".                                                 #
	#--------------------------------------------------------------------------#
	function dbhcms_f_get_dirs($adir) {
		$result = array();
		$dirlist = opendir($adir); 
		while ($file = readdir($dirlist)) {
			if ($file != '.' && $file != '..') {
				$newpath = $adir.'/'.$file; 
				$level = explode('/',$newpath); 
				if (is_dir($newpath)) {
					$result[] = array( 
										'level'=>count($level)-1, 
										'path'=>$newpath, 
										'name'=>end($level), 
										'kind'=>'dir', 
										'time'=>filemtime($newpath), 
										'content'=>dbhcms_f_get_dirs($newpath)
									  ); 
				}
			}
		}
		closedir($dirlist); 
		return $result; 
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GET_DIROBJ                                                      #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns an array with all files and directories from the given directory #
	# [$adir]. If $dirs is true then the array will only contain directories   #
	# else it will contain only files.                                         #
	#--------------------------------------------------------------------------#
	function dbhcms_f_get_dirobj($adir, $dirs = false) {
		$result = array();
		$dirlist = opendir($adir); 
		while ($file = readdir($dirlist)) {
			if ($file != '.' && $file != '..') {
				$newpath = $adir.'/'.$file; 
				$level = explode('/',$newpath); 
				if (is_dir($newpath)) {
					if ($dirs) {
						$result[] = array( 
											'level'=>count($level)-1, 
											'path'=>$newpath, 
											'name'=>end($level), 
											'kind'=>'dir', 
											'time'=>filemtime($newpath)
										  ); 
					}
				} else {
					if (!$dirs) {
						$result[] = array( 
											'level'=>count($level)-1, 
											'path'=>$newpath, 
											'name'=>end($level), 
											'kind'=>'file', 
											'time'=>filemtime($newpath),
											'size'=>filesize($newpath)
										  ); 
					}
				}
			}
		}
		closedir($dirlist); 
		return $result; 
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GET_PAGE_SELECT_OPTIONS                                         #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Gets the option tags for the select box to choose a page.                #
	#--------------------------------------------------------------------------#
	function dbhcms_f_get_page_select_options($apids, $apagetree, $aoptions = array(), $alayer = 0, $laylast = array(false) ) {
		
		if ($alayer == 0) {
			if (in_array('0', $apids)) {
				array_push($aoptions, '<option value="0" selected="selected" > |-ROOT (0) </option>');
			} else { 
				array_push($aoptions, '<option value="0" > |-ROOT (0) </option>'); 
			}
		}
		
		$cap_prefix = '';
		for ($i=0; $i<$alayer; $i++) { 
			if (!$laylast[$i])  {
				$cap_prefix .= '|&nbsp;&nbsp;&nbsp;';
			} else {
				$cap_prefix .= '&nbsp;&nbsp;&nbsp;';
			}
		}
		
		$cap_prefix .= '|-';
		
		$i = 1;
		foreach ($apagetree as $pid => $childs) {
			
			$page =& dbhcms_f_get_page_ref($pid);
			
			if ($i == count($apagetree)) {
				$laylast[$alayer] = true;
			} else {
				$laylast[$alayer] = false;
			}
			
			if (in_array($pid, $apids)) {
				array_push($aoptions, '<option value="'.$pid.'" selected="selected" > '.$cap_prefix.$page['params']['name'].' ('.$pid.') </option>');
			} else {
				array_push($aoptions, '<option value="'.$pid.'" > '.$cap_prefix.$page['params']['name'].' ('.$pid.') </option>');
			}
			if ((is_array($childs)) && (count($childs) > 0)) {
				$aoptions = dbhcms_f_get_page_select_options($apids, $childs, $aoptions, ($alayer+1), $laylast);
			}
			$i++;
		}
		return $aoptions;
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_ARRAY_TO_STR                                                    #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns all values of an array "$array" separated by the separator.      #
	#--------------------------------------------------------------------------#
	function dbhcms_f_array_to_str ($array, $separator) { 
		$astr = '';
	    foreach ($array as $value) {
	        if (is_array($value)) {
	            $astr .= $separator.dbhcms_f_array_to_str($value, $separator); 
	        } else {
			  	if (trim($value) != '') {
					if ($astr == '') {
		               $astr .= $value; 
					} else {
		               $astr .= $separator.$value; 
					}
				}
	        }
	    } 
		return $astr;
	} 

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GET_PAGE_OBJ_HEREDITARY                                         #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns an array with all modules, templates, stylesheets and            #
	# javascripts of a page with a hereditary hererachy.                       #
	#--------------------------------------------------------------------------#
	function dbhcms_f_get_page_obj_hereditary($apid, $aptree, $page_obj = array('mods'=>array(), 'tpls'=>array(), 'css'=>array(), 'js'=>array())) {
		foreach ($aptree as $pid => $pchilds) {
			# empty if a root page is defined
			if ($GLOBALS['DBHCMS']['PAGES'][$pid]['hierarchy'] == DBHCMS_C_HT_ROOT) {
				$page_obj = array('mods'=>array(), 'tpls'=>array(), 'css'=>array(), 'js'=>array());
			}
			# only add if hiererchy is "root" or "hereditary" - "single" will be ignored
			if (($apid != $pid)&&($GLOBALS['DBHCMS']['PAGES'][$pid]['hierarchy'] != DBHCMS_C_HT_SINGLE)) {
				# php modules
				foreach ($GLOBALS['DBHCMS']['PAGES'][$pid]['modules'] as $val) {
					array_push($page_obj['mods'], $val);
				}
				foreach ($GLOBALS['DBHCMS']['PAGES'][$pid]['params']['modules'] as $val) {
					array_push($page_obj['mods'], $val);
				}
				# templates
				foreach ($GLOBALS['DBHCMS']['PAGES'][$pid]['templates'] as $val) {
					array_push($page_obj['tpls'], $val);
				}
				foreach ($GLOBALS['DBHCMS']['PAGES'][$pid]['params']['templates'] as $val) {
					array_push($page_obj['tpls'], $val);
				}
				# stylesheets
				foreach ($GLOBALS['DBHCMS']['PAGES'][$pid]['stylesheets'] as $val) {
					array_push($page_obj['css'], $val);
				}
				foreach ($GLOBALS['DBHCMS']['PAGES'][$pid]['params']['stylesheets'] as $val) {
					array_push($page_obj['css'], $val);
				}
				# javascripts
				foreach ($GLOBALS['DBHCMS']['PAGES'][$pid]['javascripts'] as $val) {
					array_push($page_obj['js'], $val);
				}
				foreach ($GLOBALS['DBHCMS']['PAGES'][$pid]['params']['javascripts'] as $val) {
					array_push($page_obj['js'], $val);
				}
			}
			# continue with childs
			if (count($pchilds) > 0) {
				$page_obj = dbhcms_f_get_page_obj_hereditary($apid, $pchilds, $page_obj);
			}
		}
		return $page_obj;
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_STR_TO_HEX                                                      #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns the hexadecimal value of a string.                               #
	#--------------------------------------------------------------------------#
	function dbhcms_f_str_to_hex($str) { 
		if (trim($str) != '') {
			$hex = ''; 
			$length = strlen($str); 
			for ($i = 0; $i < $length; $i++) { 
				$hex .= str_pad(dechex(ord($str[$i])), 2, 0, STR_PAD_LEFT);
			}
			return $hex;
		}
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_HEX_TO_STR                                                      #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns a string from a hexadecimal value.                               #
	#--------------------------------------------------------------------------#
	function dbhcms_f_hex_to_str($hex) {
		$str = '';
		for ($i = 0; $i < strlen($hex); $i += 2 ) { 
			$str.=chr(hexdec(substr($hex,$i,2)));
		}
		return $str;
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_DEL_CACHE                                                       #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Deletes the cache. If a page ID is given "$apid" then only the cache of  #
	# this page will be deleted. If a page ID  "$apid" and a user ID "$auid"   #
	# is given then only the cache of this page of this user will be deleted.  #
	#--------------------------------------------------------------------------#
	function dbhcms_p_del_cache($apid = 'x', $auid = 'x') {
		if ($apid == 'x') {
			mysql_query("TRUNCATE TABLE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_CACHE) or dbhcms_p_error('Could not delete cache. SQL Error: '.mysql_error(), False, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		} else {
			if ($auid != 'x') {
				$user_sql = " AND cach_user_id LIKE '".$auid."' ";
			} else { $user_sql = ""; }
			mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_CACHE." WHERE cach_page_id LIKE '".$apid."' ".$user_sql) or dbhcms_p_error('Could not delete cache for page ID "'.$apid.'". SQL Error: '.mysql_error(), False, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		}
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GET_PAGE                                                        #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns an array with all the data of a page.                            #
	#--------------------------------------------------------------------------#
	function dbhcms_f_get_page($apid, $alang = 'x') {
		if (!isset($GLOBALS['DBHCMS']['PAGES'][$apid])) {
			$page = array('params'=>array('paramDataTypes'=>array()));
			$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." WHERE page_id = ".$apid);
			if ($row = mysql_fetch_array($result)) {
				$page['parentId'] = dbhcms_f_dbvalue_to_value($row['page_parent_id'], DBHCMS_C_DT_INTEGER);
				$page['domainId'] = dbhcms_f_dbvalue_to_value($row['page_domn_id'], DBHCMS_C_DT_INTEGER);
				$page['posNr'] = dbhcms_f_dbvalue_to_value($row['page_posnr'], DBHCMS_C_DT_INTEGER);
				$page['hierarchy'] = dbhcms_f_dbvalue_to_value($row['page_hierarchy'], DBHCMS_C_DT_STRING);
				$page['hide']	= dbhcms_f_dbvalue_to_value($row['page_hide'], DBHCMS_C_DT_BOOLEAN);
				$page['cache']	= dbhcms_f_dbvalue_to_value($row['page_cache'], DBHCMS_C_DT_BOOLEAN);
				$page['schedule']	= dbhcms_f_dbvalue_to_value($row['page_schedule'], DBHCMS_C_DT_BOOLEAN);
				if ($page['schedule']) {
					$page['start'] = dbhcms_f_dbvalue_to_value($row['page_start'], DBHCMS_C_DT_DATETIME);
					$page['stop'] = dbhcms_f_dbvalue_to_value($row['page_stop'], DBHCMS_C_DT_DATETIME);
				} else {
					$page['start'] = mktime();
					$page['stop'] = mktime();
				}
				$page['inMenu'] 		= dbhcms_f_dbvalue_to_value($row['page_inmenu'], DBHCMS_C_DT_BOOLEAN);
				$page['stylesheets']	= dbhcms_f_dbvalue_to_value($row['page_stylesheets'], DBHCMS_C_DT_STRARRAY);
				$page['javascripts']	= dbhcms_f_dbvalue_to_value($row['page_javascripts'], DBHCMS_C_DT_STRARRAY);
				$page['templates']		= dbhcms_f_dbvalue_to_value($row['page_templates'], DBHCMS_C_DT_STRARRAY);
				$page['modules']		= dbhcms_f_dbvalue_to_value($row['page_php_modules'], DBHCMS_C_DT_STRARRAY);
				$page['extensions']		= dbhcms_f_dbvalue_to_value($row['page_extensions'], DBHCMS_C_DT_STRARRAY);
				$page['shortcut']		= dbhcms_f_dbvalue_to_value($row['page_shortcut'], DBHCMS_C_DT_PAGE);
				$page['link']			= dbhcms_f_dbvalue_to_value($row['page_link'], DBHCMS_C_DT_STRING);
				$page['target']			= dbhcms_f_dbvalue_to_value($row['page_target'], DBHCMS_C_DT_STRING);
				$page['userLevel']		= dbhcms_f_dbvalue_to_value($row['page_userlevel'], DBHCMS_C_DT_USERLEVEL);
				$page['lastEdited']		= dbhcms_f_dbvalue_to_value($row['page_last_edited'], DBHCMS_C_DT_DATETIME);
				$page['description']	= dbhcms_f_dbvalue_to_value($row['page_description'], DBHCMS_C_DT_TEXT);
			} else {
				dbhcms_p_error('Could not load page. Page with ID "'.$apid.'" does not exist.', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			}
			if ($alang == 'x') {
				if ($apid > 0) { 
					$alang = $_SESSION['DBHCMSDATA']['LANG']['useLanguage']; 
				} else { 
					$alang = $_SESSION['DBHCMSDATA']['LANG']['coreUseLanguage']; 
				}
			} else if ($alang == 'auto') {
				$alang = dbhcms_f_get_domain_default_lang($page['domainId']);
			} else {
				$alang = $alang;
			}
			$result_pava = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEVALS.", ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEPARAMS." WHERE pava_name = papa_name AND (papa_page_id = 0 OR papa_page_id = pava_page_id) AND pava_page_id = ".$apid." AND pava_lang = '".$alang."' ");
			while ($row_pava = mysql_fetch_array($result_pava)) {
				# add type of data
				dbhcms_f_array_push_assoc($page['params']['paramDataTypes'], $row_pava['pava_name']);
				$page['params']['paramDataTypes'][$row_pava['pava_name']] = dbhcms_f_dbvalue_to_value($row_pava['papa_type'], DBHCMS_C_DT_DATATYPE);
				# add parameter
				dbhcms_f_array_push_assoc($page['params'], $row_pava['pava_name']);
				$page['params'][$row_pava['pava_name']] = dbhcms_f_dbvalue_to_value($row_pava['pava_value'], $row_pava['papa_type']);
			}
			foreach($page['params'] as $pname => $pvalue) {
				if (isset($page['params']['paramDataTypes'][$pname])) {
					if (
							($page['params']['paramDataTypes'][$pname] == DBHCMS_C_DT_STRING) ||
					   		($page['params']['paramDataTypes'][$pname] == DBHCMS_C_DT_TEXT) ||
					   		($page['params']['paramDataTypes'][$pname] == DBHCMS_C_DT_HTML) ||
					   		($page['params']['paramDataTypes'][$pname] == DBHCMS_C_DT_CONTENT)
						)
					{
						$page['params'][$pname] = dbhcms_f_str_replace_all_vars($page['params'][$pname]);
					}
				}
			}
			return $page;
		} else {
			return $GLOBALS['DBHCMS']['PAGES'][$apid];
		}
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GET_PAGE_REF                                                    #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns the reference of a page item of the page array.                  #
	#--------------------------------------------------------------------------#
	function &dbhcms_f_get_page_ref($apid) {
		if (isset($GLOBALS['DBHCMS']['PAGES'][$apid])) {
			return $GLOBALS['DBHCMS']['PAGES'][$apid];
		} else {
			return false;
		}
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GET_PAGE_PARAM_DT                                               #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns the data type of a parameter "$adt" of a page "$apid".           #
	#--------------------------------------------------------------------------#
	function dbhcms_f_get_page_param_dt($apid, $adt) {
		if (isset($GLOBALS['DBHCMS']['PAGES'][$apid]['params']['paramDataTypes'][$adt])) {
			return $GLOBALS['DBHCMS']['PAGES'][$apid]['params']['paramDataTypes'][$adt];
		} else {
			return 'ERROR: DBHCMS_F_GET_PAGE_PARAM_DT';
		}
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_FETCH_DICT_XML                                                  #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Fetches the XML data of the dictionary for import.                       #
	#--------------------------------------------------------------------------#
	function dbhcms_f_fetch_dict_xml($aurl) {
		$durl = parse_url ($aurl);
		$url = $durl['path'];
		$host = $durl['host'];
		$dict_values = array();
		$resp = '';
		$fp = fsockopen($host, 80, $errno, $errstr, 30);
		if ($fp) {
			$out = "GET ".$url." HTTP/1.1\r\n";
			$out .= "Host: ".$host."\r\n";
			$out .= "Connection: Close\r\n\r\n";
			fwrite($fp, $out);
			while (!feof($fp)) { $resp .= fgets($fp, 128); }
			fclose($fp);
			$resp = explode("\r\n\r\n",$resp);
			unset($resp[0]);
			$resp = implode("",$resp);
			$begin = strpos($resp, '<dbhcmsDict>');
			$end = strpos($resp, '</dbhcmsDict>') + 13;
			$content = substr($resp, $begin,  $end);
			$b = preg_match_all("|<dictEntry>(.*)</dictEntry>|Uism", $content, $items, PREG_PATTERN_ORDER);
			if ($b) {
			 	$number = count($items[1]);
				for ($i = 0; $i < $number; $i++) {
					$b = preg_match_all("|<entrySid>(.*)</entrySid>(.*)<entryLang>(.*)</entryLang>(.*)<entryValue>(.*)</entryValue>|Uism", $items[1][$i], $regs, PREG_PATTERN_ORDER);
					if (!isset($dict_values[$regs[1][0]])) {
						dbhcms_f_array_push_assoc($dict_values, $regs[1][0]);
						$dict_values[$regs[1][0]] = array();
					}
					if (!isset($dict_values[$regs[1][0]][$regs[3][0]])) {
						dbhcms_f_array_push_assoc($dict_values[$regs[1][0]], $regs[3][0]);
						$dict_values[$regs[1][0]][$regs[3][0]] = $regs[5][0];
					}
				}
			}
		}
		return $dict_values;
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_RESET_AUTHENTICATION                                            #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Deletes the authentication and sets all session variables to the         #
	# initial values.                                                          #
	#--------------------------------------------------------------------------#
	function dbhcms_p_reset_authentication () {
		$_SESSION['DBHCMSDATA']['AUTH']['userId'] 				=  '';
		$_SESSION['DBHCMSDATA']['AUTH']['userName'] 			=  '';
		$_SESSION['DBHCMSDATA']['AUTH']['password'] 			=  '';
		$_SESSION['DBHCMSDATA']['AUTH']['userRealName'] 	=  '';
		$_SESSION['DBHCMSDATA']['AUTH']['userSex'] 				=  '';
		$_SESSION['DBHCMSDATA']['AUTH']['userCompany'] 		=  '';
		$_SESSION['DBHCMSDATA']['AUTH']['userLocation'] 	=  '';
		$_SESSION['DBHCMSDATA']['AUTH']['userEmail'] 			=  '';
		$_SESSION['DBHCMSDATA']['AUTH']['userWebsite'] 		=  '';
		$_SESSION['DBHCMSDATA']['AUTH']['userLang'] 			=  '';
		$_SESSION['DBHCMSDATA']['AUTH']['authenticated']	=  false;
		$_SESSION['DBHCMSDATA']['AUTH']['domains']				=  array();
		$_SESSION['DBHCMSDATA']['AUTH']['userLevels'] 		=  array('A');
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GET_SESSIONS                                                    #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Delivers an array with the actual active session with username if        #
    # authenticated.                                                           #
	# EXAMPLE:                                                                 #
	# ==============                                                           #
	# array( 3976f6ff48a8c495dbbffa527e5a1328 =>                               #
	#                    array( user => webmaster                              #
	#                           start => 2006-07-10 10:16:13                   #
	#                           update => 2006-07-10 10:17:47 ),               #
	#         58ec49b29656bb3019975ff3631e4ed7 =>                              #
	#                    array( user =>                                        #
	#                           start => 2006-07-10 10:17:45                   #
	#                           update => 2006-07-10 10:17:45 )                #
	#       )                                                                  #
	#--------------------------------------------------------------------------#
	function dbhcms_f_get_sessions () {
		$sessions = array();
		$result =  mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_SESSIONS." WHERE sess_dead LIKE 'N'");
		while ($row = mysql_fetch_assoc($result)) {
			$sessions[$row['sess_id']] = array('user'=>$row['sess_user'], 'start'=>$row['sess_start'], 'update'=>$row['sess_update'], 'active'=>$row['sess_active']);
		}
		return $sessions;
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GET_DOMAIN_DEFAULT_LANG                                         #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns the default language of the given domain id "$adomain".          #
	#--------------------------------------------------------------------------#
	function dbhcms_f_get_domain_default_lang($adomain) {
		if (intval($adomain) == 0) {
			return $GLOBALS['DBHCMS']['CONFIG']['CORE']['defaultLang'];
		} else {
			$result = mysql_query("SELECT domn_default_lang FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS." WHERE domn_id = ".$adomain);
			$row = mysql_fetch_assoc($result);
			return $row['domn_default_lang'];
		}
	}

#############################################################################################
#                                                                                           #
#  FUNCTION: DBHCMS_F_STR_REPLACE_DB_VARS                                                   #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#                                                                                           #
#  Replaces markers such as "[dbhcmsDbServer]" found in "$astr" with values of the database #
#  configuration in "$GLOBALS['DBHCMS']['CONFIG']['DB']". The format for the markers is                #
#  "[dbhcmsDbXxxx]" where "Xxxx" the name of the parameter represents. First character of   #
#  the parameter will allways be upper case in the marker.                                  #
#                                                                                           #
#  EXAMPLE                                                                                  #
#  =============================                                                            #
#                                                                                           #
#  The marker of "$GLOBALS['DBHCMS']['CONFIG']['DB']['server']" is "[dbhcmsDbServer]". So if the value #
#  of "$GLOBALS['DBHCMS']['CONFIG']['DB']['server']" is "localhost" following will apply:              #
#                                                                                           #
#  -> $mystring = dbhcms_f_str_replace_db_vars('My database server is [dbhcmsDbServer]');   #
#                                                                                           #
#  The value of "$mystring" would be "My database server is localhost"                      #
#                                                                                           #
#############################################################################################

	function dbhcms_f_str_replace_db_vars($astr) {
		# System Database Settings
		if (isset($GLOBALS['DBHCMS']['CONFIG']['DB'])) {
			foreach($GLOBALS['DBHCMS']['CONFIG']['DB'] as $pname => $pvalue){
				if ($pname != 'passwd') {
					if (is_array($pvalue)) {
						$astr = str_replace('[dbhcmsDb'.ucfirst($pname).']', dbhcms_f_array_to_str($pvalue, ';'), $astr);
					} else {
						$astr = str_replace('[dbhcmsDb'.ucfirst($pname).']', strval($pvalue), $astr);
					}
				}
			}
		}
		return $astr;
	}

#############################################################################################
#                                                                                           #
#  FUNCTION: DBHCMS_F_STR_REPLACE_CORE_VARS                                                 #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#                                                                                           #
#  Replaces markers such as "[dbhcmsCoreVersion]" found in "$astr" with values of the core  #
#  configuration in "$GLOBALS['DBHCMS']['CONFIG']['CORE']". The format for the markers is              #
#  "[dbhcmsCoreXxxx]" where "Xxxx" the name of the parameter represents. First character of #
#  the parameter will allways be upper case in the marker.                                  #
#                                                                                           #
#  EXAMPLE                                                                                  #
#  =============================                                                            #
#                                                                                           #
#  The marker of "$GLOBALS['DBHCMS']['CONFIG']['CORE']['version']" is "[dbhcmsCoreVersion]". So if the #
#  value of "$GLOBALS['DBHCMS']['CONFIG']['CORE']['version']" is "1.1.3" following will apply:         #
#                                                                                           #
#  -> $mystring = dbhcms_f_str_replace_core_vars('My core version is [dbhcmsCoreVersion]'); #
#                                                                                           #
#  The value of "$mystring" would be "My core version is 1.1.3"                             #
#                                                                                           #
#############################################################################################

	function dbhcms_f_str_replace_core_vars($astr) {
		
		### GENERAL ###
		$astr = str_replace("[coreVersion]", 				dbhcms_f_value_to_output($GLOBALS['DBHCMS']['CONFIG']['CORE']['version'], DBHCMS_C_DT_STRING), $astr);
		$astr = str_replace("[coreDebug]", 					dbhcms_f_value_to_output($GLOBALS['DBHCMS']['CONFIG']['CORE']['debug'], DBHCMS_C_DT_STRING), $astr);
		$astr = str_replace("[coreSupportedLangs]",			dbhcms_f_value_to_output($GLOBALS['DBHCMS']['CONFIG']['CORE']['supportedLangs'], DBHCMS_C_DT_LANGARRAY), $astr);
		$astr = str_replace("[coreDefaultLang]", 			dbhcms_f_value_to_output($GLOBALS['DBHCMS']['CONFIG']['CORE']['defaultLang'], DBHCMS_C_DT_LANGUAGE), $astr);
		
		### DIRECTORIES ###
		$astr = str_replace("[coreDirectory]", 				dbhcms_f_value_to_output($GLOBALS['DBHCMS']['CONFIG']['CORE']['coreDirectory'], DBHCMS_C_DT_DIRECTORY), $astr);
		$astr = str_replace("[coreImageDirectory]", 		dbhcms_f_value_to_output($GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'], DBHCMS_C_DT_DIRECTORY), $astr);
		$astr = str_replace("[coreAppsDirectory]", 			dbhcms_f_value_to_output($GLOBALS['DBHCMS']['CONFIG']['CORE']['appsDirectory'], DBHCMS_C_DT_DIRECTORY), $astr);
		$astr = str_replace("[coreJavaDirectory]", 			dbhcms_f_value_to_output($GLOBALS['DBHCMS']['CONFIG']['CORE']['javaDirectory'], DBHCMS_C_DT_DIRECTORY), $astr);
		$astr = str_replace("[coreCssDirectory]", 			dbhcms_f_value_to_output($GLOBALS['DBHCMS']['CONFIG']['CORE']['cssDirectory'], DBHCMS_C_DT_DIRECTORY), $astr);
		$astr = str_replace("[coreIncDirectory]", 			dbhcms_f_value_to_output($GLOBALS['DBHCMS']['CONFIG']['CORE']['incDirectory'], DBHCMS_C_DT_DIRECTORY), $astr);
		$astr = str_replace("[coreLibDirectory]", 			dbhcms_f_value_to_output($GLOBALS['DBHCMS']['CONFIG']['CORE']['libDirectory'], DBHCMS_C_DT_DIRECTORY), $astr);
		$astr = str_replace("[coreModuleDirectory]", 		dbhcms_f_value_to_output($GLOBALS['DBHCMS']['CONFIG']['CORE']['moduleDirectory'], DBHCMS_C_DT_DIRECTORY), $astr);
		$astr = str_replace("[coreTemplateDirectory]", 		dbhcms_f_value_to_output($GLOBALS['DBHCMS']['CONFIG']['CORE']['templateDirectory'], DBHCMS_C_DT_DIRECTORY), $astr);
		$astr = str_replace("[coreExtensionDirectory]", 	dbhcms_f_value_to_output($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'], DBHCMS_C_DT_DIRECTORY), $astr);
		$astr = str_replace("[coreTempDirectory]", 			dbhcms_f_value_to_output($GLOBALS['DBHCMS']['CONFIG']['CORE']['tempDirectory'], DBHCMS_C_DT_DIRECTORY), $astr);
		
		### APPLICATION URLS ###
		$astr = str_replace("[appUrlPhpmyadmin]", 			dbhcms_f_value_to_output($GLOBALS['DBHCMS']['CONFIG']['CORE']['appsDirectory']."phpmyadmin/index.php", DBHCMS_C_DT_FILE), $astr);
		$astr = str_replace("[appUrlQuixplorer]", 			dbhcms_f_value_to_output($GLOBALS['DBHCMS']['CONFIG']['CORE']['appsDirectory']."quixplorer/index.php", DBHCMS_C_DT_FILE), $astr);
		
		return $astr;
		
	}

#############################################################################################
#                                                                                           #
#  FUNCTION: DBHCMS_F_STR_REPLACE_SYSPARAMS_VARS                                            #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#                                                                                           #
#  Replaces markers such as "[dbhcmsParamSuperUser]" found in "$astr" with values of the    #
#  configuration parameters in "$GLOBALS['DBHCMS']['CONFIG']['PARAMS']". The format for the markers is #
#  "[dbhcmsParamXxx]" where "Xxx" the name of the parameter represents. First character of  #
#  the parameter will allways be upper case in the marker.                                  #
#                                                                                           #
#  EXAMPLE                                                                                  #
#  =============================                                                            #
#                                                                                           #
#  The marker of "$GLOBALS['DBHCMS']['CONFIG']['PARAMS']['superUser']" is "[dbhcmsParamSuperUser]". So #
#  if the value of "$GLOBALS['DBHCMS']['CONFIG']['PARAMS']['superUser']" is "webmaster" following will #
#  apply:                                                                                   #
#                                                                                           #
#  -> $mystring = dbhcms_f_str_replace_sysparams_vars('My admin is [dbhcmsParamSuperUser]');#
#                                                                                           #
#  The value of "$mystring" would be "My admin is webmaster"                                #
#                                                                                           #
#############################################################################################

	function dbhcms_f_str_replace_sysparams_vars($astr) {
		
		foreach ($GLOBALS['DBHCMS']['CONFIG']['PARAMS'] as $pname => $pvalue) {
			if ($pname != 'paramDataTypes') {
				$astr = str_replace('['.$pname.']', dbhcms_f_value_to_output($pvalue, $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['paramDataTypes'][$pname]), $astr);
			}
		}
		
		$astr = str_replace("[sessionLifeTime_s]", intval($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['sessionLifeTime'])*60, $astr);
		$astr = str_replace("[sessionLifeTime_h]", round(intval($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['sessionLifeTime'])/60), $astr);
		
		return $astr;
	}

#############################################################################################
#                                                                                           #
#  FUNCTION: DBHCMS_F_STR_REPLACE_DOMAIN_VARS                                               #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#                                                                                           #
#  Replaces markers such as "[domainHostName]" found in "$astr" with values of the domain   #
#  configuration parameters in "$GLOBALS['DBHCMS']['DOMAIN']". The format for the markers is           #
#  "[domainXxx]" where "Xxx" the name of the parameter represents. First character of       #
#  the parameter will allways be upper case in the marker.                                  #
#                                                                                           #
#  EXAMPLE                                                                                  #
#  =============================                                                            #
#                                                                                           #
#  The marker of "$GLOBALS['DBHCMS']['DOMAIN']['hostName']" is "[domainHostName]". So if the           #
#  value of "$GLOBALS['DBHCMS']['DOMAIN']['hostName']" is "www.drbenhur.de" following will             #
#  apply:                                                                                   #
#                                                                                           #
#  -> $mystring = dbhcms_f_str_replace_domain_vars('My domain is [domainHostName]');        #
#                                                                                           #
#  The value of "$mystring" would be "My domain is www.drbenhur.de"                         #
#                                                                                           #
#############################################################################################

	function dbhcms_f_str_replace_domain_vars($astr) {
		
		### GENERAL ###
		$astr = str_replace("[domainAbsoluteUrl]", 		dbhcms_f_value_to_output($GLOBALS['DBHCMS']['DOMAIN']['absoluteUrl'], DBHCMS_C_DT_STRING), $astr);
		$astr = str_replace("[domainSubFolderCount]", 	dbhcms_f_value_to_output($GLOBALS['DBHCMS']['DOMAIN']['subFolderCount'], DBHCMS_C_DT_STRING), $astr);
		$astr = str_replace("[domainHostName]", 		dbhcms_f_value_to_output($GLOBALS['DBHCMS']['DOMAIN']['hostName'], DBHCMS_C_DT_STRING), $astr);
		$astr = str_replace("[domainSubFolders]", 		dbhcms_f_value_to_output($GLOBALS['DBHCMS']['DOMAIN']['subFolders'], DBHCMS_C_DT_STRING), $astr);
		$astr = str_replace("[domainSupportedLangs]", 	dbhcms_f_value_to_output($GLOBALS['DBHCMS']['DOMAIN']['supportedLangs'], DBHCMS_C_DT_LANGARRAY), $astr);
		$astr = str_replace("[domainDefaultLang]", 		dbhcms_f_value_to_output($GLOBALS['DBHCMS']['DOMAIN']['defaultLang'], DBHCMS_C_DT_LANGUAGE), $astr);
		
		return $astr;
	}

#############################################################################################
#                                                                                           #
#  FUNCTION: DBHCMS_F_STR_REPLACE_DICT_VARS                                                 #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#                                                                                           #
#  Replaces markers such as "[dictInsert]" found in "$astr" with values of the dictionary   #
#  in "$GLOBALS['DBHCMS']['DICT']". The format for the markers is "[dictXxx]" where "Xxx" the name of  #
#  the parameter represents. First character of the parameter will allways be upper case    #
#  in the marker.                                                                           #
#                                                                                           #
#  Notice !!!                                                                               #
#  The replaced values depend on the used language and if the page is in the back end or    #
#  in the front end.                                                                        #
#                                                                                           #
#  EXAMPLE                                                                                  #
#  =============================                                                            #
#                                                                                           #
#  The marker of "$GLOBALS['DBHCMS']['DICT']['FE']['insert']" is "[dictInsert]". So if the value of    #
#  "$GLOBALS['DBHCMS']['DICT']['FE']['insert']" is "Insert New" following will apply:                  #
#                                                                                           #
#  -> $mystring = dbhcms_f_str_replace_dict_vars('My action is [dictInsert]');              #
#                                                                                           #
#  The value of "$mystring" would be "My action is Insert New"                              #
#                                                                                           #
#############################################################################################

	function dbhcms_f_str_replace_dict_vars($astr) {
		if ($GLOBALS['DBHCMS']['PID'] > 0) {
			# Front end dictionary values
			if (isset($GLOBALS['DBHCMS']['DICT']['FE'])) {
				foreach($GLOBALS['DBHCMS']['DICT']['FE'] as $pname => $pvalue){
					if ($pname != 'PARAMS') {
						$astr = str_replace('[dict_'.$pname.']', strval($pvalue), $astr);
					}
				}
			}
		} else {
			# Back end dictionary values
			if (isset($GLOBALS['DBHCMS']['DICT']['BE'])) {
				foreach($GLOBALS['DBHCMS']['DICT']['BE'] as $pname => $pvalue){
					if ($pname != 'params') {
						$astr = str_replace('[dict_'.$pname.']', strval($pvalue), $astr);
					}
				}
			}
		}
		return $astr;
	}

#############################################################################################
#                                                                                           #
#  FUNCTION: DBHCMS_F_STR_REPLACE_PAGE_VARS                                                 #
#                                                                                           #
#############################################################################################

	function dbhcms_f_str_replace_page_vars($astr, $apid = 'x') {
		if ($apid == 'x') $apid = $GLOBALS['DBHCMS']['PID'];
		### PAGE SETTINGS ###
		if (isset($GLOBALS['DBHCMS']['PAGES'][$apid])) {
			
			$astr = str_replace('[pageId]', 			strval($apid), $astr);
			$astr = str_replace('[pageUrl]', 			dbhcms_f_generate_url($GLOBALS['DBHCMS']['PAGES'][$apid]['domainId'], $apid, $GLOBALS['DBHCMS']['PAGES'][$apid]['params'][DBHCMS_C_PAGEVAL_URL]), $astr);
			$astr = str_replace('[pageName]', 			dbhcms_f_value_to_output($GLOBALS['DBHCMS']['PAGES'][$apid]['params'][DBHCMS_C_PAGEVAL_NAME], DBHCMS_C_DT_STRING), $astr);
			$astr = str_replace('[pageContent]', 		dbhcms_f_value_to_output($GLOBALS['DBHCMS']['PAGES'][$apid]['params'][DBHCMS_C_PAGEVAL_CONTENT], DBHCMS_C_DT_CONTENT), $astr);
			$astr = str_replace('[pageParentId]', 		dbhcms_f_value_to_output($GLOBALS['DBHCMS']['PAGES'][$apid]['parentId'], DBHCMS_C_DT_INTEGER), $astr);
			$astr = str_replace('[pageDomainId]', 		dbhcms_f_value_to_output($GLOBALS['DBHCMS']['PAGES'][$apid]['domainId'], DBHCMS_C_DT_INTEGER), $astr);
			$astr = str_replace('[pagePosNr]', 			dbhcms_f_value_to_output($GLOBALS['DBHCMS']['PAGES'][$apid]['posNr'], DBHCMS_C_DT_INTEGER), $astr);
			$astr = str_replace('[pageHierarchy]', 		dbhcms_f_value_to_output($GLOBALS['DBHCMS']['PAGES'][$apid]['hierarchy'], DBHCMS_C_DT_HIERARCHY), $astr);
			$astr = str_replace('[pageHide]', 			dbhcms_f_value_to_output($GLOBALS['DBHCMS']['PAGES'][$apid]['hide'], DBHCMS_C_DT_BOOLEAN), $astr);
			$astr = str_replace('[pageSchedule]', 		dbhcms_f_value_to_output($GLOBALS['DBHCMS']['PAGES'][$apid]['schedule'], DBHCMS_C_DT_BOOLEAN), $astr);
			$astr = str_replace('[pageStart]', 			dbhcms_f_value_to_output($GLOBALS['DBHCMS']['PAGES'][$apid]['start'], DBHCMS_C_DT_DATETIME), $astr);
			$astr = str_replace('[pageStop]', 			dbhcms_f_value_to_output($GLOBALS['DBHCMS']['PAGES'][$apid]['stop'], DBHCMS_C_DT_DATETIME), $astr);
			$astr = str_replace('[pageInMenu]', 		dbhcms_f_value_to_output($GLOBALS['DBHCMS']['PAGES'][$apid]['inMenu'], DBHCMS_C_DT_BOOLEAN), $astr);
			$astr = str_replace('[pageLink]', 			dbhcms_f_value_to_output($GLOBALS['DBHCMS']['PAGES'][$apid]['link'], DBHCMS_C_DT_STRING), $astr);
			$astr = str_replace('[pageTarget]', 		dbhcms_f_value_to_output($GLOBALS['DBHCMS']['PAGES'][$apid]['target'], DBHCMS_C_DT_STRING), $astr);
			$astr = str_replace('[pageUserLevel]', 		dbhcms_f_value_to_output($GLOBALS['DBHCMS']['PAGES'][$apid]['userLevel'], DBHCMS_C_DT_USERLEVEL), $astr);
			$astr = str_replace('[pageLastEdited]', 	dbhcms_f_value_to_output($GLOBALS['DBHCMS']['PAGES'][$apid]['lastEdited'], DBHCMS_C_DT_DATETIME), $astr);
			$astr = str_replace('[pageDescription]', 	dbhcms_f_value_to_output($GLOBALS['DBHCMS']['PAGES'][$apid]['description'], DBHCMS_C_DT_STRING), $astr);
			
			foreach ($GLOBALS['DBHCMS']['PAGES'][$apid]['params'] as $pname => $pvalue) {
				if ($pname != 'paramDataTypes') {
					if (!in_array($pname, array(DBHCMS_C_PAGEVAL_TEMPLATES, DBHCMS_C_PAGEVAL_STYLESHEETS, DBHCMS_C_PAGEVAL_JAVASCRIPTS, DBHCMS_C_PAGEVAL_PHPMODULES))) {
						$astr = str_replace('[pageParam'.ucfirst($pname).']', dbhcms_f_value_to_output($pvalue, dbhcms_f_get_page_param_dt($apid, $pname)), $astr);
					}
				}
			}
			
		}
		return $astr;
	}

#############################################################################################
#                                                                                           #
#  FUNCTION: DBHCMS_F_STR_REPLACE_SESSTATS_VARS                                             #
#                                                                                           #
#############################################################################################

	function dbhcms_f_str_replace_sesstats_vars($astr) {
		# Session Statistics
		if (isset($_SESSION['DBHCMSDATA']['STAT'])) {
			foreach($_SESSION['DBHCMSDATA']['STAT'] as $pname => $pvalue){
				if (is_array($pvalue)) {
					$astr = str_replace('[sessStat'.ucfirst($pname).']', dbhcms_f_array_to_str($pvalue, ';'), $astr);
				} else {
					$astr = str_replace('[sessStat'.ucfirst($pname).']', strval($pvalue), $astr);
				}
			}
		}
		return $astr;
	}

#############################################################################################
#                                                                                           #
#  FUNCTION: DBHCMS_F_STR_REPLACE_SESLANG_VARS                                              #
#                                                                                           #
#############################################################################################

	function dbhcms_f_str_replace_seslang_vars($astr) {
		# Session Language Settings
		if (isset($_SESSION['DBHCMSDATA']['LANG'])) {
			foreach($_SESSION['DBHCMSDATA']['LANG'] as $pname => $pvalue){
				if (is_array($pvalue)) {
					$astr = str_replace('[sessLang'.ucfirst($pname).']', dbhcms_f_array_to_str($pvalue, ';'), $astr);
				} else {
					$astr = str_replace('[sessLang'.ucfirst($pname).']', strval($pvalue), $astr);
				}
			}
		}
		return $astr;
	}

#############################################################################################
#                                                                                           #
#  FUNCTION: DBHCMS_F_STR_REPLACE_SESAUTH_VARS                                              #
#                                                                                           #
#############################################################################################

	function dbhcms_f_str_replace_sesauth_vars($astr) {
		# Session User Settings
		if (isset($_SESSION['DBHCMSDATA']['AUTH'])) {
			foreach($_SESSION['DBHCMSDATA']['AUTH'] as $pname => $pvalue){
				if (is_array($pvalue)) {
					$astr = str_replace('[sessAuth'.ucfirst($pname).']', dbhcms_f_array_to_str($pvalue, ';'), $astr);
				} else {
					$astr = str_replace('[sessAuth'.ucfirst($pname).']', strval($pvalue), $astr);
				}
			}
		}
		return $astr;
	}

#############################################################################################
#                                                                                           #
#  FUNCTION: DBHCMS_F_STR_REPLACE_SOME_VARS                                                 #
#                                                                                           #
#############################################################################################

	function dbhcms_f_str_replace_some_vars($astr, $adb, $acore, $asysparams, $adomain, $adict, $apage, $asesstats, $aseslang, $asesauth) {
		# System Database Settings
		if ($adb) $astr = dbhcms_f_str_replace_db_vars($astr);
		# System Core Settings
		if ($acore) $astr = dbhcms_f_str_replace_core_vars($astr);
		# System Parameters
		if ($asysparams) $astr = dbhcms_f_str_replace_sysparams_vars($astr);
		# Domain Settings
		if ($adomain) $astr = dbhcms_f_str_replace_domain_vars($astr);
		# Dictionary values BE or FE
		if ($adict) $astr = dbhcms_f_str_replace_dict_vars($astr);
		# Page Settings
		if ($apage) $astr = dbhcms_f_str_replace_page_vars($astr);
		# Session Statistics
		if ($asesstats) $astr = dbhcms_f_str_replace_sesstats_vars($astr);
		# Session Language Settings
		if ($aseslang) $astr = dbhcms_f_str_replace_seslang_vars($astr);
		# Session User Settings
		if ($asesauth) $astr = dbhcms_f_str_replace_sesauth_vars($astr);
		return $astr;
	}

#############################################################################################
#                                                                                           #
#  FUNCTION: DBHCMS_F_STR_REPLACE_ALL_VARS                                                  #
#                                                                                           #
#############################################################################################

	function dbhcms_f_str_replace_all_vars($astr) {
		# System Database Settings
		$astr = dbhcms_f_str_replace_db_vars($astr);
		# System Core Settings
		$astr = dbhcms_f_str_replace_core_vars($astr);
		# System Parameters
		$astr = dbhcms_f_str_replace_sysparams_vars($astr);
		# Domain Settings
		$astr = dbhcms_f_str_replace_domain_vars($astr);
		# Dictionary values BE or FE
		$astr = dbhcms_f_str_replace_dict_vars($astr);
		# Page Settings
		$astr = dbhcms_f_str_replace_page_vars($astr);
		# Session Statistics
		$astr = dbhcms_f_str_replace_sesstats_vars($astr);
		# Session Language Settings
		$astr = dbhcms_f_str_replace_seslang_vars($astr);
		# Session User Settings
		$astr = dbhcms_f_str_replace_sesauth_vars($astr);
		return $astr;
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_ARRAY_PUSH_ASSOC                                                #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Inserts a new values in an associative array.                            #
	#--------------------------------------------------------------------------#
	function dbhcms_f_array_push_assoc (&$arr) {
		$ret = 0;
		$args = func_get_args();
		foreach ($args as $arg) {
			if (is_array($arg)) {
				foreach ($arg as $key => $value) {
					$arr[$key] = $value;
					$ret++;
				}
			} else { $arr[$arg] = ""; }
	   }
	   return $ret;
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_ADD_PHP_MODULE                                                  #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Inserts a new php module in struct.                                      #
	#--------------------------------------------------------------------------#
	function dbhcms_p_add_php_module ($asid, $afilename) {
		### GET FE PATHS ###
		if ($GLOBALS['DBHCMS']['PID'] > 0) {
			$afilename = $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['moduleDirectory'].$afilename;
		### GET BE PATHS ###
		} else {
			$afilename = $GLOBALS['DBHCMS']['CONFIG']['CORE']['moduleDirectory'].$afilename;
		}
		if (is_file($afilename)) {
			dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['STRUCT']['PHP'], $asid);
			$GLOBALS['DBHCMS']['STRUCT']['PHP'][$asid] = $afilename;
		} else {
			dbhcms_p_error('Could not load PHP-Module. File "'.$afilename.'" does not exist.', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		}
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_ADD_TEMPLATE                                                    #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Inserts a new template in struct.                                        #
	#--------------------------------------------------------------------------#
	function dbhcms_p_add_template ($asid, $afilename) {
		### GET FE PATHS ###
		if ($GLOBALS['DBHCMS']['PID'] > 0) {
			$afilename = $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['templateDirectory'].$afilename;
		### GET BE PATHS ###
		} else {
			$afilename = $GLOBALS['DBHCMS']['CONFIG']['CORE']['templateDirectory'].$afilename;
		}
		if (is_file($afilename)) {
			dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['STRUCT']['TPL'], $asid);
			$GLOBALS['DBHCMS']['STRUCT']['TPL'][$asid] = $afilename;
		} else {
			dbhcms_p_error('Could not load template. File "'.$afilename.'" does not exist.', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		}
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_ADD_TEMPLATE_EXT                                                #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Inserts a new template for an extension in struct.                       #
	#--------------------------------------------------------------------------#
	function dbhcms_p_add_template_ext ($asid, $afilename, $aextname) {
		### GET EXT PATHS ###
		$afilename = $GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].$aextname.'/ext.templates/'.$afilename;
		if (is_file($afilename)) {
			dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['STRUCT']['TPL'], $asid);
			$GLOBALS['DBHCMS']['STRUCT']['TPL'][$asid] = $afilename;
		} else {
			dbhcms_p_error('Could not load template. File "'.$afilename.'" does not exist.', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		}
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_ADD_STYLESHEET                                                  #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Inserts a new stylesheet in struct.                                      #
	#--------------------------------------------------------------------------#
	function dbhcms_p_add_stylesheet ($asid, $afilename) {
		### GET FE PATHS ###
		if ($GLOBALS['DBHCMS']['PID'] > 0) {
			$afilename = $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['cssDirectory'].$afilename;
		### GET BE PATHS ###
		} else {
			$afilename = $GLOBALS['DBHCMS']['CONFIG']['CORE']['cssDirectory'].$afilename;
		}
		if (is_file($afilename)) {
			dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['STRUCT']['CSS'], $asid);
			$GLOBALS['DBHCMS']['STRUCT']['CSS'][$asid] = $afilename;
		} else {
			dbhcms_p_error('Could not load stylesheet. File "'.$afilename.'" does not exist.', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		}
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_ADD_JAVASCRIPT                                                  #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Inserts a new javascript in struct.                                      #
	#--------------------------------------------------------------------------#
	function dbhcms_p_add_javascript ($asid, $afilename) {
		### GET FE PATHS ###
		if ($GLOBALS['DBHCMS']['PID'] > 0) {
			$afilename = $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['javaDirectory'].$afilename;
		### GET BE PATHS ###
		} else {
			$afilename = $GLOBALS['DBHCMS']['CONFIG']['CORE']['javaDirectory'].$afilename;
		}
		if (is_file($afilename)) {
			dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['STRUCT']['JS'], $asid);
			$GLOBALS['DBHCMS']['STRUCT']['JS'][$asid] = $afilename;
		} else {
			dbhcms_p_error('Could not load javascript. File "'.$afilename.'" does not exist.', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		}
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_ADD_STRING                                                      #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Inserts a new string in struct.                                          #
	#--------------------------------------------------------------------------#
	function dbhcms_p_add_string ($asid, $astring) {
		dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['STRUCT']['STR'], $asid);
		$GLOBALS['DBHCMS']['STRUCT']['STR'][$asid] = $astring;
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_ADD_BLOCK                                                       #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Inserts a new block in struct.                                           #
	#--------------------------------------------------------------------------#
	function dbhcms_p_add_block ($asid, $aparams) {
		dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['STRUCT']['BLK'], $asid);
		$GLOBALS['DBHCMS']['STRUCT']['BLK'][$asid] = array($aparams);
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_HIDE_BLOCK                                                      #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Hides a block in the struct.                                             #
	#--------------------------------------------------------------------------#
	function dbhcms_p_hide_block ($asid) {
		if (!isset($GLOBALS['DBHCMS']['STRUCT']['BLK'][$asid])) {
			dbhcms_p_add_block($asid, array());
		}
		$GLOBALS['DBHCMS']['STRUCT']['BLK'][$asid] = array(array());
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_SHOW_BLOCK                                                      #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Shows a block in the struct.                                             #
	#--------------------------------------------------------------------------#
	function dbhcms_p_show_block ($asid) {
		if (!isset($GLOBALS['DBHCMS']['STRUCT']['BLK'][$asid])) {
			dbhcms_p_add_block($asid, array());
		}
		$GLOBALS['DBHCMS']['STRUCT']['BLK'][$asid] = array(array(),array());
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_ADD_BLOCK_VALUES                                                #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Inserts a new value in a block in struct.                                #
	#--------------------------------------------------------------------------#
	function dbhcms_p_add_block_values ($ablockname, $avalueset) {
		array_push($GLOBALS['DBHCMS']['STRUCT']['BLK'][$ablockname], $avalueset);
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_ADD_EXTENSION                                                   #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Inserts a new extension in struct.                                       #
	#--------------------------------------------------------------------------#
	function dbhcms_p_add_extension ($asid) {
		$asid = strtolower(trim($asid));
		if (in_array($asid, $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['availableExtensions'])) {
			if (is_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].$asid.'/ext.'.$asid.'.gl.php')) {
				if (is_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].$asid.'/ext.'.$asid.'.fe.php')) {
					if (is_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].$asid.'/ext.'.$asid.'.be.php')) {
						array_push($GLOBALS['DBHCMS']['STRUCT']['EXT'], $asid);
						dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['CONFIG']['EXT'], $asid);
						$GLOBALS['DBHCMS']['CONFIG']['EXT'][$asid] = array('title'=>'', 'inMenu'=>'0', 'version'=> '0.0');
					} else {
						dbhcms_p_error('Could not load extension "'.strtoupper($asid).'". File "'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].$asid.'/ext.'.$asid.'.be.php'.'" does not exist.', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
					}
				} else {
					dbhcms_p_error('Could not load extension "'.strtoupper($asid).'". File "'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].$asid.'/ext.'.$asid.'.fe.php'.'" does not exist.', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
				}
			} else {
				dbhcms_p_error('Could not load extension "'.strtoupper($asid).'". File "'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].$asid.'/ext.'.$asid.'.gl.php'.'" does not exist.', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			}
		} else {
			dbhcms_p_error('Could not load extension "'.strtoupper($asid).'". Extension is not available.', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		}
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_CONFIGURE_EXTENSION                                             #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Sets configuration parameters for the extension.                         #
	#--------------------------------------------------------------------------#
	function dbhcms_p_configure_extension ($aextname, $aexttitle, $adescr, $ainmenu, $version, $icon = '') {
		if (isset($GLOBALS['DBHCMS']['CONFIG']['EXT'][$aextname])) {
			$GLOBALS['DBHCMS']['CONFIG']['EXT'][$aextname]['title'] = $aexttitle;
			$GLOBALS['DBHCMS']['CONFIG']['EXT'][$aextname]['description'] = $adescr;
			$GLOBALS['DBHCMS']['CONFIG']['EXT'][$aextname]['inMenu'] = $ainmenu;
			$GLOBALS['DBHCMS']['CONFIG']['EXT'][$aextname]['version'] = $version;
			$GLOBALS['DBHCMS']['CONFIG']['EXT'][$aextname]['icon'] = $icon;
		}
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_ADD_MENU                                                        #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Inserts a new menu in struct.                                            #
	#--------------------------------------------------------------------------#
	function dbhcms_p_add_menu($aname, $atype, $alayer, $adepth, $ashowrestricted, $awall, $awno, $awact, $awsel, $alno, $alact, $alsel) {
		dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['STRUCT']['MEN'], $aname);
		$GLOBALS['DBHCMS']['STRUCT']['MEN'][$aname] = array(
																'menuType'			 =>	$atype, 
																'menuLayer'			 =>	$alayer, 
																'menuDepth'			 =>	$adepth, 
																'menuShowRestricted' =>	$ashowrestricted, 
																'menuWrapAll'	 	 =>	$awall,
																'menuWrapNormal'	 =>	$awno, 
																'menuWrapActive'	 =>	$awact,
																'menuWrapSelected'	 =>	$awsel,
																'menuLinkNormal'	 =>	$alno,
																'menuLinkActive'	 =>	$alact,
																'menuLinkSelected'	 =>	$alsel
															);
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GET_PAGE_VALUE                                                  #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns a value of a parameter of a page.                                #
	#--------------------------------------------------------------------------#
	function dbhcms_f_get_page_value($apageid, $avarname, $alang) {
		$sql = "SELECT pava_value FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEVALS." WHERE pava_name LIKE '".$avarname."' AND pava_page_id = ".$apageid." AND pava_lang = '".$alang."'";
		$result = mysql_query($sql);
		if ($row = mysql_fetch_array($result)) {
			return $row['pava_value'];
		} else {
			dbhcms_p_error('Page parameter "'.$avarname.'" not found.', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			return '';
		}
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_DECODE_URL_PARAMS                                               #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns an array with the decoded parameters generated in the url.       #
	#--------------------------------------------------------------------------#
	function dbhcms_f_decode_url_params($aurl)	{
		$result_params = array();
		$params = explode('&', dbhcms_f_hex_to_str($aurl));
		$count = 0;
		foreach ($params as $param) {
			$param_vals = split('=', $param);
			dbhcms_f_array_push_assoc($result_params, $param_vals[0]);
			$result_params[$param_vals[0]] = $param_vals[1];
			$count++;
		}
		return $result_params;
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_ENCODE_URL_PARAMS                                               #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Generates coded string with the given parameters for binding in the url. #
	#--------------------------------------------------------------------------#
	function dbhcms_f_encode_url_params($aparams){
		$str = '';
		$i = 1;
		$count = count($aparams);
		foreach ($aparams as $pname => $pvalue) {
			$str .= urlencode($pname).'='.urlencode($pvalue);
			if ($i < $count) {
				$str .= '&';
			}
			$i++;
		}
		return dbhcms_f_str_to_hex($str);
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GENERATE_URL                                                    #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Generates the url for a page.                                            #
	#--------------------------------------------------------------------------#
	function dbhcms_f_generate_url($adid, $apid, $aurl = '', $alang = '', $aparams = array()) {
		if ($alang == '') {
			if ($apid > 0) { 
				$alang = $_SESSION['DBHCMSDATA']['LANG']['useLanguage']; 
			} else { 
				$alang = $_SESSION['DBHCMSDATA']['LANG']['coreUseLanguage']; 
			}
		}
		if (count($aparams) > 0) {
			if (($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['simulateStaticUrls'])&&($apid > 0)) {
				return $aurl."-".$adid."-".$apid."-".$alang.".".dbhcms_f_encode_url_params($aparams).".html";
			} else {
				if ($apid > 0) {
					return "index.php?dbhcms_did=".$adid."&dbhcms_pid=".$apid."&dbhcms_lang=".$alang."&dbhcms_params=".dbhcms_f_encode_url_params($aparams);
				} else {
					return "index.php?dbhcms_pid=".$apid."&dbhcms_params=".dbhcms_f_encode_url_params($aparams);
				}
			}
		} else {
			if (($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['simulateStaticUrls'])&&($apid > 0)) {
				return $aurl."-".$adid."-".$apid."-".$alang.".html";
			} else {
				if ($apid > 0) {
					return "index.php?dbhcms_did=".$adid."&dbhcms_pid=".$apid."&dbhcms_lang=".$alang;
				} else {
					return "index.php?dbhcms_pid=".$apid;
				}
			}
		}
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GET_URL_FROM_PID                                                #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns the URL of the page of the page-id "$apid".                      #
	#--------------------------------------------------------------------------#
	function dbhcms_f_get_url_from_pid($apid, $adomnid = 'x', $aurl = 'x', $ashortcut = 'x', $alink = 'x', $aparams = array()) {
		
		if (($adomnid == 'x')||($aurl == 'x')||($ashortcut == 'x')||($alink == 'x')) {
			
			if ($apid > 0) { 
				$lang = $_SESSION['DBHCMSDATA']['LANG']['useLanguage']; 
			} else { 
				$lang = $_SESSION['DBHCMSDATA']['LANG']['coreUseLanguage']; 
			}
			
			if (isset($GLOBALS['DBHCMS']['PAGES'][$apid])) {
				$adomnid 	= 	$GLOBALS['DBHCMS']['PAGES'][$apid]['domainId'];
				$aurl 		= 	$GLOBALS['DBHCMS']['PAGES'][$apid]['params']['urlPrefix'];
				$ashortcut 	= 	$GLOBALS['DBHCMS']['PAGES'][$apid]['shortcut'];
				$alink 		= 	$GLOBALS['DBHCMS']['PAGES'][$apid]['link'];
			} else {
				$result = mysql_query("SELECT page_id, page_domn_id, page_shortcut, page_link, pava_value FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES.", ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEVALS." WHERE pava_page_id = page_id AND pava_name LIKE '".DBHCMS_C_PAGEVAL_URL."' AND pava_lang LIKE '".$lang."' AND page_id = ".$apid);
				if ($row = mysql_fetch_array($result)) {
					$adomnid 	= 	$row['page_domn_id'];
					$aurl 		= 	$row['pava_value'];
					$ashortcut 	= 	$row['page_shortcut'];
					$alink 		= 	$row['page_link'];
				} else {
					dbhcms_p_error('URL of page id "'.$apid.'" not found!', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
				}
			}
		}
		
		if (intval($ashortcut) != 0) {
			return dbhcms_f_get_url_from_pid($ashortcut);
		} else if (trim($alink) != '') {
			return $alink;
		} else {
			return dbhcms_f_generate_url($adomnid, $apid, $aurl, $lang, $aparams);
		}
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GET_URL_FROM_PID_WP                                             #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns the URL of the page of the page-id "$apid" with aditional        #
	# parameters.                                                              #
	#--------------------------------------------------------------------------#
	function dbhcms_f_get_url_from_pid_wp ($apid, $aparams){
		return dbhcms_f_get_url_from_pid($apid, $adomnid = 'x', $aurl = 'x', $ashortcut = 'x', $alink = 'x', $aparams);
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_KEY_IN_ARRAY_RECURSIVE                                          #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Searches recursively an array "$aarray" for a given key "$key" and       #
	# returns TRUE if found and FALSE if not found.                            #
	#--------------------------------------------------------------------------#
	function dbhcms_f_key_in_array_recursive($akey, $aarray) {
		$result = false;
		foreach ($aarray as $aarrkey => $aarrvalue) {
			if ($aarrkey == $akey) {
				$result = true;
				break 1;
			}
			if (is_array($aarrvalue)) {
				if (dbhcms_f_key_in_array_recursive($akey, $aarrvalue)) {
					$result = true;
					break 1;
				}
			}
		}
		return $result;
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GET_MENU_ARRAY_FROM_PAGETREE                                    #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns an array with html values of the items for the menu.             #
	#--------------------------------------------------------------------------#
	function dbhcms_f_get_menu_array_from_pagetree($apid, $amenu, $apagetree, $onlyactive = false, $alayer = 1, $hidden = true) {
		
		if ($amenu['menuLayer'] == 0) { $minval = -9999; } else { $minval = $amenu['menuLayer']; }
		if ($amenu['menuDepth'] == 0) { $maxval = 9999; } else { $maxval = $amenu['menuDepth']; }
		
		$result = array();
		
		foreach($apagetree as $page_id => $page_value) {
			
				if (!$GLOBALS['DBHCMS']['PAGES'][$page_id]['hide']) {
					# page not startet ?
					if ((!$GLOBALS['DBHCMS']['PAGES'][$page_id]['schedule'])||($GLOBALS['DBHCMS']['PAGES'][$page_id]['start'] < mktime())) {
						# page stoped ?
						if ((!$GLOBALS['DBHCMS']['PAGES'][$page_id]['schedule'])||($GLOBALS['DBHCMS']['PAGES'][$page_id]['stop'] > mktime())) {
							# page not in menu ?
							if (!((!$hidden)&&(!$GLOBALS['DBHCMS']['PAGES'][$page_id]['inMenu']))) {
								# page in layer ?
								if ((($alayer >= $minval)&&($alayer <= $maxval))&&(($amenu['menuShowRestricted'])||(in_array($GLOBALS['DBHCMS']['PAGES'][$page_id]['userLevel'], $_SESSION['DBHCMSDATA']['AUTH']['userLevels'])))) {
								
									if ($page_id == $apid) {
										
										# selected
										
										# get link url
										$item_link_url = dbhcms_f_get_url_from_pid($page_id, $GLOBALS['DBHCMS']['PAGES'][$page_id]['domainId'], $GLOBALS['DBHCMS']['PAGES'][$page_id]['params']['urlPrefix'], $GLOBALS['DBHCMS']['PAGES'][$page_id]['shortcut'], $GLOBALS['DBHCMS']['PAGES'][$page_id]['link']);
										
										# prepare link object
										$item_link = $amenu['menuLinkSelected'];
										$item_link = str_replace('[layer]', $alayer, $item_link);
										$item_link = str_replace('[pageURL]', $item_link_url, $item_link);
										$item_link = dbhcms_f_str_replace_some_vars($item_link, true, true, true, true, true, false, true, true, true);
										$item_link = dbhcms_f_str_replace_page_vars($item_link, $page_id);
										
										# prepare link wrap
										$item_link_wrap = $amenu['menuWrapSelected'];
										$item_link_wrap = str_replace('[layer]', $alayer, $item_link_wrap);
										$item_link_wrap = str_replace('[pageURL]', $item_link_url, $item_link_wrap);
										$item_link_wrap = dbhcms_f_str_replace_some_vars($item_link_wrap, true, true, true, true, true, false, true, true, true);
										$item_link_wrap = dbhcms_f_str_replace_page_vars($item_link_wrap, $page_id);
										
										# get target
										if (trim($GLOBALS['DBHCMS']['PAGES'][$page_id]['target']) != '') { 
											$item_link_target = ' target="'.$GLOBALS['DBHCMS']['PAGES'][$page_id]['target'].'" ';
										} else { $item_link_target = ''; }
										
										array_push($result, str_replace('|', '<a href="'.$item_link_url.'" '.$item_link_target.' >'.$item_link.'</a>', $item_link_wrap));
										
									} else if (dbhcms_f_key_in_array_recursive($apid, $page_value)) {
										
										# active
										
										# get link url
										$item_link_url = dbhcms_f_get_url_from_pid($page_id, $GLOBALS['DBHCMS']['PAGES'][$page_id]['domainId'], $GLOBALS['DBHCMS']['PAGES'][$page_id]['params']['urlPrefix'], $GLOBALS['DBHCMS']['PAGES'][$page_id]['shortcut'], $GLOBALS['DBHCMS']['PAGES'][$page_id]['link']); 
										
										# prepare link object
										$item_link = $amenu['menuLinkActive'];
										$item_link = str_replace('[layer]', $alayer, $item_link);
										$item_link = str_replace('[pageURL]', $item_link_url, $item_link);
										$item_link = dbhcms_f_str_replace_some_vars($item_link, true, true, true, true, true, false, true, true, true);
										$item_link = dbhcms_f_str_replace_page_vars($item_link, $page_id);
										
										# prepare link wrap
										$item_link_wrap = $amenu['menuWrapActive'];
										$item_link_wrap = str_replace('[layer]', $alayer, $item_link_wrap);
										$item_link_wrap = str_replace('[pageURL]', $item_link_url, $item_link_wrap);
										$item_link_wrap = dbhcms_f_str_replace_some_vars($item_link_wrap, true, true, true, true, true, false, true, true, true);
										$item_link_wrap = dbhcms_f_str_replace_page_vars($item_link_wrap, $page_id);
										
										# get target
										if (trim($GLOBALS['DBHCMS']['PAGES'][$page_id]['target']) != '') { 
											$item_link_target = ' target="'.$GLOBALS['DBHCMS']['PAGES'][$page_id]['target'].'" ';
										} else { $item_link_target = ''; }
										
										array_push($result, str_replace('|', '<a href="'.$item_link_url.'" '.$item_link_target.' >'.$item_link.'</a>', $item_link_wrap));
										
									} else {
										
										# normal
										
										# get link url
										$item_link_url = dbhcms_f_get_url_from_pid($page_id, $GLOBALS['DBHCMS']['PAGES'][$page_id]['domainId'], $GLOBALS['DBHCMS']['PAGES'][$page_id]['params']['urlPrefix'], $GLOBALS['DBHCMS']['PAGES'][$page_id]['shortcut'], $GLOBALS['DBHCMS']['PAGES'][$page_id]['link']);
										
										# prepare link object
										$item_link = $amenu['menuLinkNormal'];
										$item_link = str_replace('[layer]', $alayer, $item_link);
										$item_link = str_replace('[pageURL]', $item_link_url, $item_link);
										$item_link = dbhcms_f_str_replace_some_vars($item_link, true, true, true, true, true, false, true, true, true);
										$item_link = dbhcms_f_str_replace_page_vars($item_link, $page_id);
										
										# prepare link wrap
										$item_link_wrap = $amenu['menuWrapNormal'];
										$item_link_wrap = str_replace('[layer]', $alayer, $item_link_wrap);
										$item_link_wrap = str_replace('[pageURL]', $item_link_url, $item_link_wrap);
										$item_link_wrap = dbhcms_f_str_replace_some_vars($item_link_wrap, true, true, true, true, true, false, true, true, true);
										$item_link_wrap = dbhcms_f_str_replace_page_vars($item_link_wrap, $page_id);
										
										# get target
										if (trim($GLOBALS['DBHCMS']['PAGES'][$page_id]['target']) != '') { 
											$item_link_target = ' target="'.$GLOBALS['DBHCMS']['PAGES'][$page_id]['target'].'" ';
										} else { $item_link_target = ''; }
										
										array_push($result, str_replace('|', '<a href="'.$item_link_url.'" '.$item_link_target.' >'.$item_link.'</a>', $item_link_wrap));
										
									}
								
								}
							} # page in layer ?
							
							if ((!$onlyactive)||($page_id == $apid)||dbhcms_f_key_in_array_recursive($apid, $page_value)) {
								$result = array_merge($result, dbhcms_f_get_menu_array_from_pagetree($apid, $amenu, $page_value, $onlyactive, ($alayer + 1), $hidden));
							}
							
						} # page stoped ?
					} # page not startet ?
				} # page hiden ?

		}
		return $result;
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_ADD_MISSING_PAGEVALS                                            #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Inserts missing standard page values of the table PAGEVALS after a       #
	# supported language is added                                              #
	#--------------------------------------------------------------------------#
	function dbhcms_p_add_missing_pagevals () {
		$result_pages = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES);
		$missing_values = array();
		while ($row_pages = mysql_fetch_array($result_pages)) {
			# Get the domains supported languages -- diferent for admin
			if ($row_pages['page_id'] <= 0) { # admin
				$domain_langs = $GLOBALS['DBHCMS']['CONFIG']['CORE']['supportedLangs'];
			} else { # other domains
				$result_domain = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS." WHERE domn_id = ".$row_pages['page_domn_id']);
				$row_domain = mysql_fetch_array($result_domain);
				$domain_langs = explode(';', strval($row_domain['domn_supported_langs']));
			}
			# check if exists and if not insert
			foreach ($domain_langs as $tmvalue) {
				$result_pageparams = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEPARAMS." WHERE papa_page_id = 0 OR papa_page_id = ".$row_pages['page_id']);
				$result_pagevals = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEVALS." WHERE pava_page_id = ".$row_pages['page_id']." AND pava_lang LIKE '".$tmvalue."'");
				if (mysql_num_rows($result_pagevals) < mysql_num_rows($result_pageparams) ) {
					while ($row_pageparams = mysql_fetch_array($result_pageparams)) {
						$result_pagevals_param = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEVALS." WHERE pava_page_id = ".$row_pages['page_id']." AND pava_lang LIKE '".$tmvalue."' AND pava_name LIKE '".$row_pageparams['papa_name']."' ");
						if (mysql_num_rows($result_pagevals_param) == 0 ) {
							mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEVALS." (`pava_page_id` , `pava_name` , `pava_value` , `pava_lang` ) VALUES ( ".$row_pages['page_id'].", '".$row_pageparams['papa_name']."', '', '".$tmvalue."');");
							array_push($missing_values, 'added parameter "'.$row_pageparams['papa_name'].'" for page '.$row_pages['page_id']);
						}
					}
				}
			}
		}
		return $missing_values;
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_URL_REPLACE_SYMBOLS                                             #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Replaces character codes (found in URL's) with real characters.          #
	# Example: replaces "%e4" into	"ä"                                        #
	#--------------------------------------------------------------------------#
	function dbhcms_f_url_replace_symbols ($astr) {
		return str_replace('%22', '"', 
				str_replace('+', ' ', 
				str_replace('+%2b', ' ',
				str_replace('%26', '&', 
				str_replace('%2c', ',', 
				str_replace('%c3%9f', 'ß',
				str_replace('%c3%b6', 'ö',
				str_replace('%c3%96', 'Ö',
				str_replace('%c3%bc', 'ü',
				str_replace('%c3%9c', 'Ü', 
				str_replace('%c3%84', 'Ä', 
				str_replace('%c3%a4', 'ä', 
				str_replace('%e2%82%ac', '€',
				str_replace('%e4', 'ä',
				str_replace('%f6', 'ö',
				str_replace('%fc', 'ü',
				str_replace('%c4', 'Ä',
				str_replace('%d6', 'Ö',
				str_replace('%dc', 'Ü',
				$astr)))))))))))))))))));
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_SUPERUSER_AUTH                                                  #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns TRUE if the authenticated user an admin is else it returns FALSE #
	#--------------------------------------------------------------------------#
	function dbhcms_f_superuser_auth () {
		$auth = false;
		if ($_SESSION['DBHCMSDATA']['AUTH']['authenticated']) {
			if (in_array($_SESSION['DBHCMSDATA']['AUTH']['userName'], $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['superUsers'])) {
				$auth = true;
			}
		}
		return $auth;
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GET_ARR_HTML                                                    #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns html code representing values of the the given variable          #
	#                                                                          #
	# PARAMETERS:                                                              #
	# ==============                                                           #
	# @var : Variable from witch you want to extract and see the data          #
	#--------------------------------------------------------------------------#
	function dbhcms_f_get_arr_html($var, $style = "display: none; margin-left: 10px;") {
		static $i = 0; $i++;
		$htmlreturn = "\n<div id=\"array_tree_".$i."\" class=\"array_tree\">\n";
		switch (gettype($var)) {
			# output for array
			case "array":
				foreach($var as $key => $val) { 
					switch (gettype($val)) {
						# output for array in array
						case "array":
							$htmlreturn .= "<a onclick=\"document.getElementById('";
							$htmlreturn .= "array_tree_element_".$i."').style.display = ";
    					$htmlreturn .= "document.getElementById('array_tree_element_".$i;
							$htmlreturn .= "').style.display == 'block' ?";
							$htmlreturn .= "'none' : 'block';\"\n";
							$htmlreturn .= "name=\"array_tree_link_".$i."\" href=\"#array_tree_link_".$i."\">".htmlspecialchars($key)."</a><br />\n";
							$htmlreturn .= "<div class=\"array_tree_element_".$i."\" id=\"array_tree_element_".$i."\" style=\"".$style."\">";
							$htmlreturn .= dbhcms_f_get_arr_html($val, $style);
							$htmlreturn .= "</div>";
							break;
						# output for integer in array
						case "integer":
							$htmlreturn .= "<b>".htmlspecialchars($key)."</b> => <i>".htmlspecialchars($val)."</i><br />";
							break;
						# output for double in array
						case "double":
							$htmlreturn .= "<b>".htmlspecialchars($key)."</b> => <i>".htmlspecialchars($val)."</i><br />";
							break;
						# output for boolean in array
						case "boolean":
							$htmlreturn .= "<b>".htmlspecialchars($key)."</b> => ";
							if ($val) { 
								$htmlreturn .= "TRUE"; 
							} else { 
								$htmlreturn .= "FALSE"; 
							}
	     					$htmlreturn .=  "<br />\n";
    						break;
    					# output for string in array
    					case "string":
							$htmlreturn .= "<b>".htmlspecialchars($key)."</b> => <code>".htmlspecialchars($val)."</code><br />";
							break;
						# output for objects in array
						case "object":
							$htmlreturn .= "<b>".htmlspecialchars($key)."</b> => ".str_replace("\n", "<br />\n",str_replace(" ", "&nbsp;" ,htmlspecialchars(print_r($val, true))))."<br />";
							break;
						# output for everything else in array
						default:
							$htmlreturn .= "<b>".htmlspecialchars($key)."</b> => ".gettype($val)."<br />";
							break;
					}
				}
				break;
			# output for integer
			case "integer":
				$htmlreturn .= "<i>".htmlspecialchars($var)."</i><br />";
				break;
			# output for double
			case "double":
				$htmlreturn .= "<i>".htmlspecialchars($var)."</i><br />";
				break;
			# output for boolean
			case "boolean":
				if ($var) { 
					$htmlreturn .= "TRUE"; 
				} else { 
					$htmlreturn .= "FALSE"; 
				}
  				$htmlreturn .=  "<br />\n";
  				break;
 				# output for string
 				case "string":
				$htmlreturn .= "<code>".htmlspecialchars($var)."</code><br />";
				break;
			# output for objects
			case "object":
				$htmlreturn .= str_replace("\n", "<br />\n",str_replace(" ", "&nbsp;" ,htmlspecialchars(print_r($var, true))))."<br />";
				break;
			# output for everything else
			default:
				$htmlreturn .= gettype($var)."<br />";
				break;
		}
			return $htmlreturn."</div>";
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_P_CREATE_TEMP_CSS_FOR_TINYMCE                                     #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Creates temporary CSS file including all CSS files in the domain and     #
	# parent pages. This temporary CSS file is used to edit th page content    #
	# with TinyMce.                                                            #
	#--------------------------------------------------------------------------#
	function dbhcms_p_create_temp_css_for_tinymce($apid, $alang) {
		# page admin or fe ???
		if ($apid > 0 ) {
			$stylefolder = $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['cssDirectory'];
		} else { $stylefolder = $GLOBALS['DBHCMS']['CONFIG']['CORE']['cssDirectory']; }
		# load page stylesheets
		$result = mysql_query("SELECT page_stylesheets, page_domn_id, page_id FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." WHERE page_id = ".$apid);
		$row = mysql_fetch_assoc($result);
		$page_styles = explode(';', $row['page_stylesheets']);
		# load domain stylesheets
		$result = mysql_query("SELECT domn_stylesheets FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS." WHERE domn_id = ".$row['page_domn_id']);
		$row = mysql_fetch_assoc($result);
		$domain_styles = explode(';', $row['domn_stylesheets']);
		# load page language-specific stylesheets
		$page_lang_styles = explode(';', dbhcms_f_get_page_value($apid, DBHCMS_C_PAGEVAL_STYLESHEETS, $alang));
			# create temporary template file
		$tmp_css_file = fopen($GLOBALS['DBHCMS']['CONFIG']['CORE']['tempDirectory']."tmp.tinymce.".$apid.".".$_SESSION['DBHCMSDATA']['SID'].".css", "w");
		foreach ($domain_styles as $template_file) {
			if ($template_file != "") {
				if (filesize($stylefolder.$template_file) > 0) {
					$cssfile = fopen($stylefolder.$template_file, "r");
					fwrite($tmp_css_file, " \n\n /* ====== FILE ".$template_file." ====== */ \n\n ");
					fwrite($tmp_css_file, fread($cssfile, filesize($stylefolder.$template_file)));
					fclose($cssfile);
				}
			}
		}
		foreach ($page_styles as $template_file) {
			if ($template_file != "") {
				if (filesize($stylefolder.$template_file) > 0) {
					$cssfile = fopen($stylefolder.$template_file, "r");
					fwrite($tmp_css_file, " \n\n /* ====== FILE ".$template_file." ====== */ \n\n ");
					fwrite($tmp_css_file, fread($cssfile, filesize($stylefolder.$template_file)));
					fclose($cssfile);
				}
			}
		}
		foreach ($page_lang_styles as $template_file) {
			if ($template_file != "") {
				if (filesize($stylefolder.$template_file) > 0) {
					$cssfile = fopen($stylefolder.$template_file, "r");
					fwrite($tmp_css_file, " \n\n /* ====== FILE ".$template_file." ====== */ \n\n ");
					fwrite($tmp_css_file, fread($cssfile, filesize($stylefolder.$template_file)));
					fclose($cssfile);
				}
			}
		}
		fclose($tmp_css_file);
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GET_ROOT_PARENT                                                 #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns the Id of the page that is the parent root of the page @apage.   #
	#--------------------------------------------------------------------------#
	function dbhcms_f_get_root_parent($apage) {
		$page_id = $apage;
		$result = mysql_query("SELECT page_id, page_parent_id FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." WHERE page_id = ".$page_id);
		$row = mysql_fetch_assoc($result);
		$page_id = intval($row['page_id']);
		$page_parent_id = intval($row['page_parent_id']);
		while ( $page_parent_id != 0 ) {
			$result = mysql_query("SELECT page_id, page_parent_id FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." WHERE page_id = ".$page_parent_id);
			$row = mysql_fetch_assoc($result);
			$page_id = intval($row['page_id']);
			$page_parent_id = intval($row['page_parent_id']);
		}
		return $page_id;
	}

	#--------------------------------------------------------------------------#
	# DBHCMS_F_GET_SUBTREE_FROM_PAGE_TREE                                      #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns the piece of tree in @atree that begins with the given page      #
	# @apage.                                                                  #
	#--------------------------------------------------------------------------#
	function dbhcms_f_get_subtree_from_page_tree($atree, $apage) {
		$return_val = array();
		foreach ($atree as $tid => $tvalue) {
			if ($tid == $apage) {
				$return_val = array($tid => $tvalue);
				break 1;
			} else {
				$return_val = dbhcms_f_get_subtree_from_page_tree($tvalue, $apage);
				if ($return_val != array()) {
					break 1;
				}
			}
		}
		return $return_val;
	}

	function dbhcms_f_create_page_tree_crnt($aremaining, $atree) {
		$isok = true;
		foreach($aremaining as $rid => $rvalue) {
			$valok = false;
			foreach($atree as $tid => $tvalue) {
				if ($rid == $tid) {
					$valok = true;
					break 1;
				}
				if (dbhcms_f_create_page_tree_crnt(array($rid => $rvalue), $tvalue)) {
					$valok = true;
					break 1;
				}
			}
			if ($valok == false) {
				$isok = false;
			}
		}
		return $isok;
	}

	function dbhcms_f_create_page_tree_add_page($apagetree, $apageid, $apageparentid) {
		foreach ($apagetree as $pt_pageid => $pt_values) {
			if ($pt_pageid == $apageparentid) {
				if (!isset($apagetree[$pt_pageid][$apageid])) {
					dbhcms_f_array_push_assoc($apagetree[$pt_pageid], $apageid);
					$apagetree[$pt_pageid][$apageid] = array();
				}
			} else {
			 	$apagetree[$pt_pageid] = dbhcms_f_create_page_tree_add_page($apagetree[$pt_pageid], $apageid, $apageparentid);
			}
		}
		return $apagetree;
	}

	function dbhcms_f_sort_page_tree_cmp($a, $b) {
		if (isset($GLOBALS['DBHCMS']['PAGES'][$a])) {
			$pos_a = $GLOBALS['DBHCMS']['PAGES'][$a]['posNr'];
			$pos_b = $GLOBALS['DBHCMS']['PAGES'][$b]['posNr'];
			if ($pos_a == $pos_b) {
				return 0;
			} else {
				return ($pos_a < $pos_b) ? -1 : 1;
			}
		} else {
			if ($GLOBALS['DBHCMS']['CONFIG']['CORE']['debug']) {
				dbhcms_p_error('Could not get position number of page with ID "'.$a.'". It seems that this page is not been loaded yet.', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__); 
			}
			return 0;
		}
	}

	function dbhcms_p_sort_page_tree(&$atree) {
		uksort($atree, "dbhcms_f_sort_page_tree_cmp");
		foreach ($atree as $pid => $childs) {
			if (is_array($childs)&&(count($childs) > 1)) {
				dbhcms_p_sort_page_tree($atree[$pid]);
			}
		}
	}

	function dbhcms_f_create_page_tree($adomain, $alang, $apage = 0, $onlypath = false) {
		# init page tree and temporary array
		$page_tree = array();
		$page_remaining = array();
		# this is for menu type LOCATION
		if ($onlypath) {
			# add page root parent to page tree
			$page_root_parent = dbhcms_f_get_root_parent($apage);
			dbhcms_f_array_push_assoc($page_tree, $page_root_parent);
			$page_tree[$page_root_parent] = array();
			# search last page
			$result = mysql_query("SELECT page_id, page_domn_id, page_parent_id, page_posnr, page_hide, page_start, page_stop, page_inmenu, page_userlevel, page_last_edited, page_description, page_shortcut, page_link, page_target FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." WHERE page_id = ".$apage);
			$row = mysql_fetch_assoc($result);
			# look for parents
			while (intval($row['page_parent_id']) != 0) {
				# add parent to remaining array
				if ($row['page_id'] != $page_root_parent) {
					dbhcms_f_array_push_assoc($page_remaining, $row['page_id']);
					$page_remaining[$row['page_id']] = $row['page_parent_id'];
				}
				# lookup next parent
				$result = mysql_query("SELECT page_id, page_domn_id, page_parent_id, page_posnr, page_hide, page_start, page_stop, page_inmenu, page_userlevel, page_last_edited, page_description, page_shortcut, page_link, page_target FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." WHERE page_id = ".$row['page_parent_id']);
				$row = mysql_fetch_assoc($result);
			}
			# add pages to page tree
			while (!dbhcms_f_create_page_tree_crnt($page_remaining, $page_tree)) {
				foreach($page_remaining as $apageid => $apageparentid) {
					$page_tree = dbhcms_f_create_page_tree_add_page($page_tree, $apageid, $apageparentid);
				}
			}
		# this is for menu type TREE and ACTIVETREE
		} else { # end only path
			# get root pages
			$result = mysql_query("SELECT page_id, page_domn_id, page_parent_id, page_posnr, page_hide, page_start, page_stop, page_inmenu, page_userlevel, page_last_edited, page_description, page_shortcut, page_link, page_target FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." WHERE page_parent_id = 0 AND page_domn_id = ".$adomain." ORDER BY page_id ");
			while ($row = mysql_fetch_assoc($result)) {
				# add root pages to page tree
				dbhcms_f_array_push_assoc($page_tree, $row['page_id']);
				$page_tree[$row['page_id']] = array();
			}
			# get other pages
			($adomain == 0) ? $srt = 'ASC' : $srt = 'DESC';
			$result = mysql_query("SELECT page_id, page_domn_id, page_parent_id, page_posnr, page_hide, page_start, page_stop, page_inmenu, page_userlevel, page_last_edited, page_description, page_shortcut, page_link, page_target FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." WHERE page_parent_id != 0 AND page_domn_id = ".$adomain." ORDER BY page_parent_id ".$srt);
			while ($row = mysql_fetch_assoc($result)) {
				# add pages to temporary array
				dbhcms_f_array_push_assoc($page_remaining, $row['page_id']);
				$page_remaining[$row['page_id']] = $row['page_parent_id'];
			}
			# add pages from the temporary array to the page tree
			while (!dbhcms_f_create_page_tree_crnt($page_remaining, $page_tree)) {
				foreach($page_remaining as $apageid => $apageparentid) {
					$page_tree = dbhcms_f_create_page_tree_add_page($page_tree, $apageid, $apageparentid);
				}
			}
			# if only a part of the menu, the cut out sub-part and rewrite result
			if ($apage != 0) {
				$page_tree = dbhcms_f_get_subtree_from_page_tree($page_tree, $apage);
			}
			# sort
			dbhcms_p_sort_page_tree($page_tree);
		} # end full tree
		# return page tree
		return $page_tree;
		
	}


	#--------------------------------------------------------------------------#
	# DBHCMS_F_GET_ICON                                                        #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns image tag for an icon                                            #
	#                                                                          #
	# PARAMETERS:                                                              #
	# ==============                                                           #
	# @aicon : Name of the icon file without extension                         #
	# @alt : Alt text for the image                                            #
	# @size : Size of the image: 0 -> not defined                              #
	#                            1 -> small  (16x16)                           #
	#                            2 -> middle (22x22)                           #
	#                            3 -> large  (32x32)                           #
	# @tags : Other tags for the image. Example: style="border:1px;"           #
	#--------------------------------------------------------------------------#
	function dbhcms_f_get_icon($aicon, $aalt = '', $size = 0, $tags = '') {
		if ($size == 0) {
			if (is_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'icons/'.$aicon.'.png')) {
				return '<img '.$tags.' align="absmiddle" border="0" src="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'icons/'.$aicon.'.png" alt="'.$aalt.'" title="'.$aalt.'" />';
			} else if (is_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'icons/'.$aicon.'.gif')) {
				return '<img '.$tags.' align="absmiddle" border="0" src="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'icons/'.$aicon.'.gif" alt="'.$aalt.'" title="'.$aalt.'" />';
			} else if (is_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'icons/'.$aicon.'.jpg')) {
				return '<img '.$tags.' align="absmiddle" border="0" src="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'icons/'.$aicon.'.jpg" alt="'.$aalt.'" title="'.$aalt.'" />';
			} else { 
				return ''; 
			}
		} else {
			switch ($size) {
				case 1:
					if (is_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'icons/small/'.$aicon.'.png')) {
						return '<img width="16" height="16" '.$tags.' align="absmiddle" border="0" src="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'icons/small/'.$aicon.'.png" alt="'.$aalt.'" title="'.$aalt.'" />';
					} else { 
						return ''; 
					}
					break;
				case 2:
					if (is_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'icons/middle/'.$aicon.'.png')) {
						return '<img width="22" height="22" '.$tags.' align="absmiddle" border="0" src="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'icons/middle/'.$aicon.'.png" alt="'.$aalt.'" title="'.$aalt.'" />';
					} else { 
						return ''; 
					}
					break;
				case 3:
					if (is_file($GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'icons/large/'.$aicon.'.png')) {
						return '<img width="32" height="32" '.$tags.' align="absmiddle" border="0" src="'.$GLOBALS['DBHCMS']['CONFIG']['CORE']['imageDirectory'].'icons/large/'.$aicon.'.png" alt="'.$aalt.'" title="'.$aalt.'" />';
					} else { 
						return ''; 
					}
					break;
			}
		}
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>