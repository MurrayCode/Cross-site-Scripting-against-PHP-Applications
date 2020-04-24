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
# $Id: ext.news.overview.php 61 2007-02-01 14:17:59Z kaisven $                              #
#############################################################################################

#############################################################################################
#  FE IMPLEMENTATION - NEWS OVERVIEW                                                        #
#############################################################################################

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# DEFINE BLOCKS                                                            #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	dbhcms_p_hide_block('newsArticle');
	
	dbhcms_p_add_block('newsArticleTeaser', $news_entry_blkprms);
	dbhcms_p_add_block('newsNewest', $news_entry_blkprms);
	dbhcms_p_add_block('newsOverview', 	array('newsJumplinks'));

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# QUERY                                                                    #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	$news_restrict = '';
	if (news_f_get_config_param('specificDomain') == 1) {
		$news_restrict = ' AND nwen_domn_id = '.$GLOBALS['DBHCMS']['DID'].' ';
	}
	if (news_f_get_config_param('specificPage') == 1) {
		$news_restrict .= ' AND nwen_page_id = '.$GLOBALS['DBHCMS']['PID'].' ';
	}

	$news_query = "SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entries WHERE INSTR('".implode('', $_SESSION['DBHCMSDATA']['AUTH']['userLevels'])."', nwen_userlevel) > 0 ".$news_restrict." ORDER BY nwen_date DESC";

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# JUMPLINKS                                                                #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	$news_jumplinkmax = news_f_get_config_param('jumplinkMax');
	$news_more = news_f_get_config_param('jumplinkMore');

	if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['nwFrom'])) {
		$news_from = $GLOBALS['DBHCMS']['TEMP']['PARAMS']['nwFrom']; 
	} else { 
		$news_from = 0;
	}

	$news_jumplinktotal = mysql_num_rows(mysql_query($news_query));
	if ($news_jumplinktotal > ($news_more * $news_jumplinkmax)) {
		$news_more = ceil($news_jumplinktotal / $news_jumplinkmax);
	}
	$news_query = $news_query." LIMIT ".$news_from." , ".$news_more;

	$news_jumplink = "";

	if ($news_from >= $news_more) {
	    $news_jumplink .= "[<a class=\"jumplink\" href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('nwFrom' => ($news_from - $news_more))) . "\">«</a>]";
	}
	for ($i = 1; ($i * $news_more) < $news_jumplinktotal; $i++) {
		$j = $i - 1;
		if (($j * $news_more) != $news_from) {
	    	$news_jumplink .= " [<a class=\"jumplink\" href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('nwFrom' => ($j * $news_more)))  . "\">" . $i . '</a>] ';
		} else {
	    	$news_jumplink .= ' <strong>' . $i . '</strong> ';
		}
	}
	$j = $i - 1;
	if (($j * $news_more) < $news_jumplinktotal) {
		if (($j * $news_more) != $news_from) {
		    $news_jumplink .= " [<a class=\"jumplink\" href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('nwFrom' => ($j * $news_more))) . "\">" . $i . '</a>] ';
		} else {
	    	$news_jumplink .= ' <strong>' . $i . '</strong> ';
		}
	}
	if ($news_jumplinktotal > ($news_from + $news_more)) {
	    $news_jumplink .= "[<a class=\"jumplink\" href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('nwFrom' => ($news_from + $news_more))) . "\">»</a>]";
	}

	dbhcms_p_add_block_values('newsOverview', array($news_jumplink));

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# ARTICLES                                                                 #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	$first_news = true;
	$result_entries = mysql_query($news_query);
	while ($row_entries = mysql_fetch_array($result_entries)) {
		
		# new tag
		$differenz = strtotime($row_entries['nwen_date']) - mktime();
		$tage = $differenz/(60*60*24);
		if (abs($tage) < intval(news_f_get_config_param('newDays'))) { 
			$new_tag = news_f_get_config_param('newTag');
		} else { 
			$new_tag = '';
		}
		
		# page id for link
		if ($row_entries['nwen_page_id'] == 0) {
			$page_id = $GLOBALS['DBHCMS']['PID'];
		} else {
			$page_id = $row_entries['nwen_page_id'];
		}
		
		# set standard album values
		$news_entry_values = array	(
										$row_entries['nwen_id'], 
										dbhcms_f_dbvalue_to_output($row_entries['nwen_date'], DBHCMS_C_DT_DATETIME),
										$new_tag, 
										dbhcms_f_get_url_from_pid_wp($page_id, array('showEntry' => $row_entries['nwen_id'])),
										news_f_count_comments($row_entries['nwen_id'])
									);
		
		# set user defined parameters
		$result_entry_pvals = mysql_query("SELECT nwev_value from ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entriesvals WHERE nwev_nwen_id = ".$row_entries['nwen_id']." AND nwev_lang LIKE '".$_SESSION['DBHCMSDATA']['LANG']['useLanguage']."' ORDER BY nwev_name");
		while ($row_entry_pvals = mysql_fetch_array($result_entry_pvals)) {
			if (defined('DBHCMS_C_EXT_SMILIES')) {
				array_push($news_entry_values, smilies_f_replace_smilies($row_entry_pvals['nwev_value']));
			} else {
				array_push($news_entry_values, $row_entry_pvals['nwev_value']);
			}
		}
		
		# add article
		dbhcms_p_add_block_values('newsArticleTeaser', $news_entry_values);
		
		# add newest article only
		if ($first_news) {
			dbhcms_p_add_block_values('newsNewest', $news_entry_values);
			$first_news = false;
		}
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>