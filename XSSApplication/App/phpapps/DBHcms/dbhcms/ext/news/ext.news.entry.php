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
# $Id: ext.news.entry.php 61 2007-02-01 14:17:59Z kaisven $                                 #
#############################################################################################

#############################################################################################
#	FE IMPLEMENTATION - ARTICLE OVERVIEW                                                      #
#############################################################################################

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# ACTIONS                                                                  #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	if (dbhcms_f_superuser_auth() == true) {
		if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsDeleteEntryComment'])) {
			# Delete news comment
			if (mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_comments WHERE nwcm_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsDeleteEntryComment'])) {
				if (($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['cacheEnabled'])&&($GLOBALS['DBHCMS']['PID'] > 0)) {
					if ($GLOBALS['DBHCMS']['CONFIG']['CORE']['debug']) {
						echo "Message: Cache deleted by news comment deletion in ext.news.entry.php";
					}
					dbhcms_p_del_cache($GLOBALS['DBHCMS']['PID']);
				}
			} else {
				if ($GLOBALS['DBHCMS']['CONFIG']['CORE']['debug']) {
					echo "SQL Error: ".mysql_error();
				}
			}
		}
	}

	if (isset($_POST['todo'])) {
		if ($_POST['todo'] == 'newsAddComment') {
			if (isset($_POST['newsSex'])) { 
				$gb_sex = dbhcms_f_input_to_dbvalue('newsSex', DBHCMS_C_DT_SEX); 
			} else { 
				$gb_sex = DBHCMS_C_ST_NONE; 
			}
			news_p_add_comment(
									$GLOBALS['DBHCMS']['TEMP']['PARAMS']['showEntry'],
									dbhcms_f_input_to_dbvalue('newsName', DBHCMS_C_DT_STRING), $gb_sex, 
									dbhcms_f_input_to_dbvalue('newsEmail', DBHCMS_C_DT_STRING), 
									dbhcms_f_input_to_dbvalue('newsHomepage', DBHCMS_C_DT_STRING), 
									dbhcms_f_input_to_dbvalue('newsLocation', DBHCMS_C_DT_STRING), 
									dbhcms_f_input_to_dbvalue('newsText', DBHCMS_C_DT_TEXT)
								);
		}
	}

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# DEFINE BLOCKS                                                            #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	dbhcms_p_hide_block('newsOverview');
	
	if (news_f_get_config_param('enableComments')) {
		# hide the no comments bar
		dbhcms_p_hide_block('newsArticleCommentNone');
		# add comments block
		dbhcms_p_add_block('newsArticleComment', array	(	'commentName', 
														'commentLocation', 
														'commentDate', 
														'commentText', 
														'commentDelete',
														'commentEmail',
														'commentWebsite',
														'commentNewTag',
														'commentSex',
														'commentSexIcon',
														'commentEmailIcon',
														'commentWebsiteIcon',
														'commentEntryTitle' 
													));
	} else {
		# hide the whole comments block if coments are disabled
		dbhcms_p_hide_block('newsArticleComments');
	}

	dbhcms_p_add_block('newsArticle', $news_entry_blkprms);

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

	$news_query = "SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entries WHERE INSTR('".implode('', $_SESSION['DBHCMSDATA']['AUTH']['userLevels'])."', nwen_userlevel) > 0 AND nwen_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['showEntry']." ".$news_restrict." ORDER BY nwen_date DESC";

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# ARTICLE                                                                  #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	$result_entries = mysql_query($news_query);
	if ($row_entries = mysql_fetch_array($result_entries)) {
		
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
		dbhcms_p_add_block_values('newsArticle', $news_entry_values);
		
	}

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# ARTICLE COMMENTS                                                         #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	if (news_f_get_config_param('enableComments')) {

		$result_comments = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_comments WHERE nwcm_nwen_id  = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['showEntry']." ORDER BY nwcm_datetime ASC ");
		if (mysql_num_rows($result_comments) > 0) {
	
			while ($row_comments = mysql_fetch_assoc($result_comments)) {
			
				if (dbhcms_f_superuser_auth() == true) {
					$delete_btn = '<a onclick=" return confirm(\' '.dbhcms_f_dict('dbhcms_msg_askdeleteitem').' \'); " href="'.dbhcms_f_get_url_from_pid_wp($page_id, array('showEntry' => $GLOBALS['DBHCMS']['TEMP']['PARAMS']['showEntry'], 'newsDeleteEntryComment' => $row_comments['nwcm_id']))."\">".dbhcms_f_get_icon('edit-delete', dbhcms_f_dict('delete'), 1)."</a>";
				} else { 
					$delete_btn = ''; 
				}
			
				$differenz = strtotime($row_comments['nwcm_datetime']) - mktime(date('h'),date('i'),date('s'),date('m'),date('d'),date('Y'));
				$tage = $differenz/(60*60*24);
			
				if (abs($tage) < news_f_get_config_param('newDays')) {
					$new_tag = news_f_get_config_param('newTag');
				} else { 
					$new_tag = ''; 
				}
				
				if (defined('DBHCMS_C_EXT_SMILIES')) {
					$news_entry_text = smilies_f_replace_smilies(dbhcms_f_value_to_output($row_comments['nwcm_entrytext'], DBHCMS_C_DT_TEXT));
				} else { 
					$news_entry_text = dbhcms_f_value_to_output($row_comments['nwcm_entrytext'], DBHCMS_C_DT_TEXT); 
				}
				
				if (trim($row_comments['nwcm_sex']) == DBHCMS_C_ST_MALE) {
					$news_sex_icon = dbhcms_f_get_icon('male', $GLOBALS['DBHCMS']['DICT']['FE']['male']);
				} else if (trim($row_comments['nwcm_sex']) == DBHCMS_C_ST_FEMALE) {
					$news_sex_icon = dbhcms_f_get_icon('female', $GLOBALS['DBHCMS']['DICT']['FE']['female']);
				} else {
					$news_sex_icon = '';
				}
				
				if (trim($row_comments['nwcm_email']) != '') {
					$news_email_icon = '<a href="mailto:'.$row_comments['nwcm_email'].'">'.dbhcms_f_get_icon('email', $row_comments['nwcm_email']).'</a>';
				} else { $news_email_icon = ''; }
				
				if (trim($row_comments['nwcm_homepage']) != '') {
					$news_website_icon = '<a href="'.$row_comments['nwcm_homepage'].'" target="_blank">'.dbhcms_f_get_icon('website', $row_comments['nwcm_homepage']).'</a>';
				} else { $news_website_icon = ''; }
				
				if (trim($row_comments['nwcm_username']) == '') {
					$news_entry_title = 'Guest';
				} else { $news_entry_title = $row_comments['nwcm_username']; }
				
				if ($row_comments['nwcm_location'] != '') {
					$news_entry_title .= ' ('.$row_comments['nwcm_location'].')';
				}
			
				dbhcms_p_add_block_values('newsArticleComment', array(	dbhcms_f_value_to_output($row_comments['nwcm_username'], DBHCMS_C_DT_STRING), 
																		dbhcms_f_value_to_output($row_comments['nwcm_location'], DBHCMS_C_DT_STRING), 
																		dbhcms_f_value_to_output(strtotime($row_comments['nwcm_datetime']), DBHCMS_C_DT_DATETIME),
																		$news_entry_text, 
																		dbhcms_f_value_to_output($delete_btn, DBHCMS_C_DT_HTML), 
																		dbhcms_f_value_to_output($row_comments['nwcm_email'], DBHCMS_C_DT_STRING),
																		dbhcms_f_value_to_output($row_comments['nwcm_homepage'], DBHCMS_C_DT_STRING),
																		dbhcms_f_value_to_output($new_tag, DBHCMS_C_DT_HTML),
																		dbhcms_f_value_to_output($row_comments['nwcm_sex'], DBHCMS_C_DT_SEX),
																		dbhcms_f_value_to_output($news_sex_icon, DBHCMS_C_DT_HTML),
																		dbhcms_f_value_to_output($news_email_icon, DBHCMS_C_DT_HTML),
																		dbhcms_f_value_to_output($news_website_icon, DBHCMS_C_DT_HTML),
																		dbhcms_f_value_to_output($news_entry_title, DBHCMS_C_DT_STRING)
																		
																	));
			}
		} else {
			dbhcms_p_show_block('newsArticleCommentNone');
		}

		if (defined('DBHCMS_C_EXT_SMILIES')) {
			dbhcms_p_add_string('newsSmiliesBar', smilies_f_create_smilies_bar('newsCommentForm', 'newsText'));
		}

	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>