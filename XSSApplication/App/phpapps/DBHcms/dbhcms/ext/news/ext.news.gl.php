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
#  news                                                                                     #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  A tool to publish your news                                                              #
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
# $Id: ext.news.gl.php 69 2007-06-03 16:50:53Z kaisven $                                    #
#############################################################################################

	define('DBHCMS_C_EXT_NEWS', 'news');

#############################################################################################
#  SETTINGS                                                                                 #
#############################################################################################

	$ext_name 		= DBHCMS_C_EXT_NEWS;
	$ext_title 		= 'News';
	$ext_descr 		= 'A full featured news system with authentication, comments and newsletter functions.';
	$ext_inmenu		= true;
	$ext_version	= '1.0';
	$ext_icon			= 'emblem-important';

	dbhcms_p_configure_extension($ext_name, $ext_title, $ext_descr, $ext_inmenu, $ext_version, $ext_icon);

#############################################################################################
#  LOAD CONFIGURATION                                                                       #
#############################################################################################

	if (in_array(DBHCMS_C_EXT_NEWS, $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['availableExtensions'])) {
	
		$result = mysql_query("SELECT nwcg_id, nwcg_value, nwcg_type FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_config ORDER BY nwcg_id");
		while ($row = mysql_fetch_assoc($result)) {
			dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_NEWS], $row['nwcg_id']);
			$GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_NEWS][$row['nwcg_id']] = dbhcms_f_dbvalue_to_value(dbhcms_f_str_replace_all_vars(strval($row['nwcg_value'])), $row['nwcg_type']);
		}
	
	}

#############################################################################################
#	GLOBAL IMPLEMENTATION                                                                     #
#############################################################################################

	function news_f_get_config_param($aparam, $update = false) {
		if ((!$update) && (isset($GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_NEWS][$aparam]))) {
			return $GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_NEWS][$aparam];
		} else {
			$result = mysql_query("SELECT nwcg_id, nwcg_value, nwcg_type FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_config WHERE nwcg_id LIKE '".$aparam."'");
			if ($row = mysql_fetch_assoc($result)) {
				return dbhcms_f_dbvalue_to_value(dbhcms_f_str_replace_all_vars(strval($row['nwcg_value'])), $row['nwcg_type']);
			} else {
				return false;
			}
		}
	}

	function news_f_get_entry_param($entryid, $aparam, $alang) {
		$result = mysql_fetch_assoc(mysql_query("SELECT nwev_value FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entriesvals WHERE nwev_name LIKE '".$aparam."' AND nwev_nwen_id = ".$entryid." AND nwev_lang LIKE '".$alang."' "));
		return dbhcms_f_str_replace_all_vars($result['nwev_value']);
	}

	function news_p_add_missing_entry_vals () {
		
		$news_langs = news_f_get_config_param('languages', true);
		
		$news_entries_result = mysql_query("SELECT *  FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entries");
		$count_missing_values = 0;
		while ($news_entries_row = mysql_fetch_array($news_entries_result)) {
			# check if exists and if not insert
			foreach ($news_langs as $tmvalue) {
				$result_entry_params = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entriesprms");
				$result_entry_vals = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entriesvals WHERE nwev_nwen_id = ".$news_entries_row['nwen_id']." AND nwev_lang LIKE '".$tmvalue."'");
				if (mysql_num_rows($result_entry_vals) < mysql_num_rows($result_entry_params)) {
					while ($row_entry_params = mysql_fetch_array($result_entry_params)) {
						$result_entry_vals_param = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entriesvals WHERE nwev_nwen_id = ".$news_entries_row['nwen_id']." AND nwev_lang LIKE '".$tmvalue."' AND nwev_name LIKE '".$row_entry_params['nwep_name']."' ");
						if (mysql_num_rows($result_entry_vals_param) == 0 ) {
							mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entriesvals (`nwev_nwen_id` , `nwev_name` , `nwev_value` , `nwev_lang` ) VALUES ( ".$news_entries_row['nwen_id'].", '".$row_entry_params['nwep_name']."', '', '".$tmvalue."');");
							$count_missing_values++;
						}
					}
				}
			}
		}
		return $count_missing_values;
	}

	function news_f_delete_entry($entryid) {
		$res = '<div style="color: #076619; font-weight: bold;">Entry has been deleted.</div>';
		mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_comments WHERE nwcm_nwen_id = ".$entryid)
			or $res = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Entry comments could not be deleted.</div>';
		mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entriesvals WHERE nwev_nwen_id = ".$entryid)
			or $res = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Entry values could not be deleted.</div>';
		mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entries WHERE nwen_id = ".$entryid)
			or $res = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Entry could not be deleted.</div>';
		return $res;
	}

	function news_f_count_comments($entryid) {
		return mysql_num_rows(mysql_query("SELECT nwcm_id FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_comments WHERE nwcm_nwen_id = ".$entryid));
	}

	function news_p_add_comment($entryid, $afullname, $asex, $aemail, $ahomepage, $alocation, $acomment) {
		if (news_f_get_config_param('enableComments')) {
			# check user-id
			if ($_SESSION['DBHCMSDATA']['AUTH']['userId'] != '') {
				$userid = $_SESSION['DBHCMSDATA']['AUTH']['userId'];
			} else { $userid = '0'; }
			mysql_query("	INSERT INTO 
											".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_comments 
												(
														`nwcm_nwen_id` , 
														`nwcm_user_id`, 
														`nwcm_username` , 
														`nwcm_sex` , 
														`nwcm_email` , 
														`nwcm_homepage` , 
														`nwcm_location` , 
														`nwcm_entrytext` , 
														`nwcm_datetime`
													) 
										VALUES 
													(
														'".$entryid."', 
														'".$userid."', 
														'".$afullname."', 
														'".$asex."', 
														'".$aemail."', 
														'".$ahomepage."', 
														'".$alocation."', 
														\"". $acomment."\", 
														NOW()
													)
								");
		}
	}

	function news_f_get_unsubscribe_url($anlid, $adomain) {
		if ($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['simulateStaticUrls'] == 1) { $url_symbol = '?'; } else { $url_symbol = '&'; }
		return dbhcms_f_get_domain_absolute_url($adomain).dbhcms_f_get_url_from_pid(news_f_get_config_param('nwcg_unsubsc_page')).$url_symbol.'news_unsubscribe_newsletter='.$anlid;
	}

	function news_p_subscribe_newsletter($afullname, $aemail) {
		if (news_f_get_config_param('enableSubscNewsletter')) {
			mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_newsletters (`nwnl_domn_id`, `nwnl_page_id`, `nwnl_name`, `nwnl_email`,  `nwnl_active`, `nwnl_subsc_date`) VALUES ('".$GLOBALS['DBHCMS']['DID']."', '".$GLOBALS['DBHCMS']['PID']."', '".$afullname."', '".$aemail."', '1', NOW())");
		}
	}

	function news_p_unsubscribe_newsletter($aid) {
		mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_newsletters SET `nwnl_active` = '0', `nwnl_unsubsc_date` = NOW() WHERE `nwnl_id` = ".$aid);
	}

	function news_p_send_newsletter($asubject, $atext, $adomain, $apage) {
		if ($apage != 0) {
			$restrict_page = ' AND `nwnl_page_id` = '.$apage;
		} else { $restrict_page = ''; }
		$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_newsletters WHERE `nwnl_active` LIKE '1' AND `nwnl_domn_id` = ".$adomain.$restrict_page);
		while ($row = mysql_fetch_assoc($result)) {
			$mailto	 = $row['nwnl_email'];
			$subject = $asubject;
			$mts = str_replace('[nlUnsubscribeUrl]', news_f_get_unsubscribe_url($row['nwnl_id'], $adomain), str_replace('[nlId]', $row['nwnl_id'], str_replace('[nlEmail]', $row['nwnl_email'], str_replace('[nlName]', $row['nwnl_name'], $atext))));
			$header  = "Content-Type: text/plain; charset=\"iso-8859-1\" \n";
			$header .= "Content-Transfer-Encoding: 8bit \n";
			$header .= "From: ".news_f_get_config_param('nwcg_sender_email', $adomain)." \n";
		    mail($mailto, $subject, $mts, $header);
		}
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>