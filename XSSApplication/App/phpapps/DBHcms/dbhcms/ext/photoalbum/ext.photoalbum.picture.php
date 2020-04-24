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
#  photoalbum                                                                               #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  A photoalbum with userlevel, picture comments, album rating and picture rating           #
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
# $Id: ext.photoalbum.picture.php 61 2007-02-01 14:17:59Z kaisven $                         #
#############################################################################################

#############################################################################################
#  FE IMPLEMENTATION - PICTURE                                                              #
#############################################################################################

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# ACTIONS                                                                  #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	if (isset($_POST['todo'])) {
		if ($_POST['todo'] == 'photoalbumAddPicRating') {
			if (photoalbum_f_get_config_param('enableRating')) {
				photoalbum_p_add_picrating($_POST['picId'], $_POST['ratingNr']);
			}
		} else if ($_POST['todo'] == 'photoalbumAddPicComment') {
			if (photoalbum_f_get_config_param('enableComments')) {
				if (isset($_POST['photoalbumSex'])) { 
					$gb_sex = dbhcms_f_input_to_dbvalue('photoalbumSex', DBHCMS_C_DT_SEX); 
				} else { $gb_sex = DBHCMS_C_ST_NONE; }
				photoalbum_p_add_piccmnt(
											dbhcms_f_input_to_dbvalue('picId', DBHCMS_C_DT_INTEGER),
											dbhcms_f_input_to_dbvalue('photoalbumName', DBHCMS_C_DT_STRING), $gb_sex, 
											dbhcms_f_input_to_dbvalue('photoalbumEmail', DBHCMS_C_DT_STRING), 
											dbhcms_f_input_to_dbvalue('photoalbumHomepage', DBHCMS_C_DT_STRING), 
											dbhcms_f_input_to_dbvalue('photoalbumLocation', DBHCMS_C_DT_STRING), 
											dbhcms_f_input_to_dbvalue('photoalbumText', DBHCMS_C_DT_TEXT)
										);
			}
		}
	}

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# DEFINE BLOCKS                                                            #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	dbhcms_p_hide_block('photoalbumOverview');
	dbhcms_p_hide_block('photoalbumAlbumOverview');
	dbhcms_p_hide_block('photoalbumPicCommentsNone');
	
	dbhcms_p_add_block('photoalbumPicComment', array	(	'commentName', 
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
	
	if (photoalbum_f_get_config_param('enableComments')) {
		dbhcms_p_show_block('photoalbumPicComments');
	} else {
		dbhcms_p_hide_block('photoalbumPicComments');
	}
	
	if (photoalbum_f_get_config_param('enableRating')) {
		dbhcms_p_show_block('photoalbumPicRating');
	} else {
		dbhcms_p_hide_block('photoalbumPicRating');
	}
	
	dbhcms_p_add_block('photoalbumShowPic', array_merge(	array	( 
																		  'picObject'
																		, 'picRating'
																		, 'picRatingCount'
																		, 'picCommentCount'
																		, 'picId'
																		, 'picUrl'
																		, 'picUserLevelChange'
																		, 'picDelete'
																		, 'picFirstUrl'
																		, 'picPreviousUrl'
																		, 'picNextUrl'
																		, 'picLastUrl'
																	),
															$photoalbum_alb_blkprms
														));



	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# QUERY                                                                    #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	$photoalbum_restrict = '';
	if (photoalbum_f_get_config_param('specificDomain')) {
		$photoalbum_restrict = ' AND paal_domn_id = '.$GLOBALS['DBHCMS']['DID'].' ';
	}
	if (photoalbum_f_get_config_param('specificPage')) {
		$photoalbum_restrict .= ' AND paal_page_id = '.$GLOBALS['DBHCMS']['PID'].' ';
	}

	$result = mysql_query("SELECT papi_paal_id FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_pics WHERE papi_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['showpic']);
	if ($row = mysql_fetch_assoc($result)) {
		$photoalbum_papi_paal_id = $row['papi_paal_id'];
	} else {
		dbhcms_p_error('Picture with ID "'.$GLOBALS['DBHCMS']['TEMP']['PARAMS']['showpic'].'" does not exist.', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
	}

	$photoalbum_nav_query =  "	SELECT 
									`papi_id`,
									`paal_page_id`
								FROM 
									".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_pics, 
									".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albs 
								WHERE 
									paal_id = papi_paal_id AND
									paal_id = '".$photoalbum_papi_paal_id."' AND
									INSTR('".implode('', $_SESSION['DBHCMSDATA']['AUTH']['userLevels'])."', papi_userlevel ) > 0 
									".$photoalbum_restrict."
								ORDER BY 
									papi_filename ";

	$photoalbum_query = "	SELECT 
							`paal_id`,
							`paal_date`, 
							`paal_thumbnail_img`, 
							`papi_id`, 
							`papi_paal_id`, 
							`papi_filename`, 
							`papi_userlevel`, 
							(
								(`papi_rate_1`*1) + 
								(`papi_rate_2`*2) + 
								(`papi_rate_3`*3) + 
								(`papi_rate_4`*4) + 
								(`papi_rate_5`*5)
							) 
							/ 
							( 
								`papi_rate_1` + 
								`papi_rate_2` + 
								`papi_rate_3` + 
								`papi_rate_4` + 
								`papi_rate_5`
							) AS rating, 
							(
								`papi_rate_1` + 
								`papi_rate_2` + 
								`papi_rate_3` + 
								`papi_rate_4` + 
								`papi_rate_5`
							) AS rating_cnt,
							
							`paal_id`,
							`paal_folder`
							
						FROM 
							".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_pics, 
							".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albs 
						WHERE 
							paal_id = papi_paal_id AND
							papi_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['showpic']." AND 
							INSTR('".implode('', $_SESSION['DBHCMSDATA']['AUTH']['userLevels'])."', papi_userlevel ) > 0 
							".$photoalbum_restrict."
						ORDER BY 
							papi_filename ";

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# PICTURE                                                                  #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	$previous_pic = 0;
	$next_pic = 0;
	$next_ok = false;
	
	$first_pic = 0;
	$last_pic = 0;
	
	$result = mysql_query($photoalbum_nav_query);
	while ($row = mysql_fetch_assoc($result)) {
		
		# page id for link
		if ($row['paal_page_id'] == 0) {
			$page_id = $GLOBALS['DBHCMS']['PID'];
		} else {
			$page_id = $row['paal_page_id'];
		}
		
		$last_pic = $row['papi_id'];
		if ($first_pic == 0) {
			$first_pic = $row['papi_id'];
		}
		if (!$next_ok) {
			if ($next_pic != 0) {
				$next_pic = $row['papi_id'];
				$next_ok = true;
			} elseif ($row['papi_id'] == $GLOBALS['DBHCMS']['TEMP']['PARAMS']['showpic']) {
				$next_pic = $row['papi_id'];
			} else {
				$previous_pic = $row['papi_id'];
			}
		}
	}

	$last_pic_url = dbhcms_f_get_url_from_pid_wp($page_id, array('showpic' => $last_pic));
	$first_pic_url = dbhcms_f_get_url_from_pid_wp($page_id, array('showpic' => $first_pic));

	if (($previous_pic != $GLOBALS['DBHCMS']['TEMP']['PARAMS']['showpic'])&&($previous_pic != 0)) {
		$previous_pic_url = dbhcms_f_get_url_from_pid_wp($page_id, array('showpic' => $previous_pic));
	} else {
		$previous_pic_url = dbhcms_f_get_url_from_pid_wp($page_id, array('showpic' => $last_pic));
	}
	if (($next_pic != $GLOBALS['DBHCMS']['TEMP']['PARAMS']['showpic'])&&($next_pic != 0)) {
		$next_pic_url = dbhcms_f_get_url_from_pid_wp($page_id, array('showpic' => $next_pic));
	} else {
		$next_pic_url = dbhcms_f_get_url_from_pid_wp($page_id, array('showpic' => $first_pic));
	}

	$result = mysql_query($photoalbum_query);
	if ($row = mysql_fetch_array($result)) {
		
		if (dbhcms_f_superuser_auth() == true) {
			$photoalbum_pic_change_ul = "<table cellpadding=\"0\" cellspacing=\"0\"><tr><td><font style=\"font-size:7pt;\" face=\"Small Fonts\"><strong>".photoalbum_f_create_userlevel_changestr($row['papi_userlevel'], $GLOBALS['DBHCMS']['PID'], $row['paal_id'], $row['papi_id'], 0)."</strong></font></td></tr></table>";
			$photoalbum_pic_delete = '<a onclick="return confirm(\' '.dbhcms_f_dict('dbhcms_msg_askdeleteitem').' \');" href="'.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('showalb' => $row['papi_paal_id'], 'photoalbumDeletePic' => $row['papi_id'])).'">'.dbhcms_f_get_icon('edit-delete', dbhcms_f_dict('delete'), 1)."</a>";
		} else { 
			$photoalbum_pic_change_ul = ''; 
			$photoalbum_pic_delete = '';
		}
		
		if (in_array(strtoupper(substr($row['papi_filename'], (strlen($row['papi_filename']) - 3))), photoalbum_f_get_config_param('formatImages'))) {
			$image_file = '<img src="'.$row['paal_folder'].$row['papi_filename'].'" />';
		} else if (in_array(strtoupper(substr($row['papi_filename'], (strlen($row['papi_filename']) - 3))), photoalbum_f_get_config_param('formatVideos'))) {
			$image_file =  '<embed src="'.$row['paal_folder'].$row['papi_filename'].'" />';
		} else {
			$image_file = '';
		}
		
		# new tag
		$differenz = strtotime($row['paal_date']) - mktime(date('h'),date('i'),date('s'),date('m'),date('d'),date('Y'));
		$tage = $differenz/(60*60*24);
		if (abs($tage) < photoalbum_f_get_config_param('newDays')) { 
			$new_tag = photoalbum_f_get_config_param('newTag');
		} else { 
			$new_tag = ''; 
		}
		
		# set standard album values
		$photoalbum_pic_values = array	(
											# picture data
											$image_file, 				# image file as gif or jpg
											round($row['rating'], 0), 	# the rating of this picture
											$row['rating_cnt'], 		# count of ratings
											photoalbum_f_count_comments($row['papi_paal_id'], $row['papi_filename']), # comment count of this picture
											$row['papi_id'], 			# picture id
											dbhcms_f_get_url_from_pid_wp($page_id, array('showpic' => $row['papi_id'])), # url to the picture
											$photoalbum_pic_change_ul,  # userlevel change bar (only admin)
											$photoalbum_pic_delete, 	# picture delete button (only admin)
											$first_pic_url,
											$previous_pic_url,
											$next_pic_url,
											$last_pic_url,
											
											# album data
											$row['paal_thumbnail_img'], # image file as jpg or gif
											date($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['dateFormatOutput'], strtotime($row['paal_date'])), # date and time
											$new_tag, # new tag if new
											dbhcms_f_get_url_from_pid_wp($page_id, array('showalb' => $row['paal_id'])), # url to the pics
											'', # album jumplinks not necesary here, just for the album overview
											photoalbum_f_count_comments($row['paal_id'], '%'), # comment count
											photoalbum_f_count_images($row['paal_id']), # image count
											photoalbum_f_count_videos($row['paal_id']) # video count
										);
		
		# set user defined parameters
		$result_albs_pvals = mysql_query("SELECT paav_value, paap_type from ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsvals, ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsprms WHERE paav_name = paap_name AND paav_paal_id = ".$row['paal_id']." AND paav_lang LIKE '".$_SESSION['DBHCMSDATA']['LANG']['useLanguage']."' ORDER BY paav_name");
		while ($row_albs_pvals = mysql_fetch_array($result_albs_pvals)) {
			if (defined('DBHCMS_C_EXT_SMILIES')) {
				array_push($photoalbum_pic_values, smilies_f_replace_smilies(dbhcms_f_value_to_output($row_albs_pvals['paav_value'], $row_albs_pvals['paap_type'])));
			} else {
				array_push($photoalbum_pic_values, dbhcms_f_value_to_output($row_albs_pvals['paav_value'], $row_albs_pvals['paap_type']));
			}
		}
		
		dbhcms_p_add_block_values('photoalbumShowPic', $photoalbum_pic_values);
		
	}

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# PICTURE COMMENTS                                                         #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	if (photoalbum_f_get_config_param('enableComments')) {
		
		$result_comments = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_piccomments WHERE papc_paal_id  = ".$row['papi_paal_id']." AND UPPER(papc_filename) LIKE UPPER('".$row['papi_filename']."') ORDER BY papc_datetime ASC ");
		if (mysql_num_rows($result_comments) > 0) {
		
			while ($row_comments = mysql_fetch_assoc($result_comments)) {
				
				if (dbhcms_f_superuser_auth() == true) {
					$delete_btn = '<a onclick=" return confirm(\' '.dbhcms_f_dict('dbhcms_msg_askdeleteitem').' \'); " href="'.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('showpic' => $GLOBALS['DBHCMS']['TEMP']['PARAMS']['showpic'], 'photoalbumDeletePicComment' => $row_comments['papc_id']))."\">".dbhcms_f_get_icon('edit-delete', dbhcms_f_dict('delete'), 1)."</a>";
				} else { 
					$delete_btn = ''; 
				}
				
				$differenz = strtotime($row_comments['papc_datetime']) - mktime(date('h'),date('i'),date('s'),date('m'),date('d'),date('Y'));
				$tage = $differenz/(60*60*24);
				
				if (abs($tage) < photoalbum_f_get_config_param('newDays')) {
					$new_tag = photoalbum_f_get_config_param('newTag');
				} else { 
					$new_tag = ''; 
				}
				
				if (defined('DBHCMS_C_EXT_SMILIES')) {
					$photoalbum_entry_text = smilies_f_replace_smilies(dbhcms_f_value_to_output($row_comments['papc_entrytext'], DBHCMS_C_DT_TEXT));
				} else { 
					$photoalbum_entry_text = dbhcms_f_value_to_output($row_comments['papc_entrytext'], DBHCMS_C_DT_TEXT); 
				}
				
				if (trim($row_comments['papc_sex']) == DBHCMS_C_ST_MALE) {
					$photoalbum_sex_icon = dbhcms_f_get_icon('male', $GLOBALS['DBHCMS']['DICT']['FE']['male']);
				} else if (trim($row_comments['papc_sex']) == DBHCMS_C_ST_FEMALE) {
					$photoalbum_sex_icon = dbhcms_f_get_icon('female', $GLOBALS['DBHCMS']['DICT']['FE']['female']);
				} else {
					$photoalbum_sex_icon = '';
				}
				
				if (trim($row_comments['papc_email']) != '') {
					$photoalbum_email_icon = '<a href="mailto:'.$row_comments['papc_email'].'">'.dbhcms_f_get_icon('email', $row_comments['papc_email']).'</a>';
				} else { $photoalbum_email_icon = ''; }
				
				if (trim($row_comments['papc_homepage']) != '') {
					$photoalbum_website_icon = '<a href="'.$row_comments['papc_homepage'].'" target="_blank">'.dbhcms_f_get_icon('website', $row_comments['papc_homepage']).'</a>';
				} else { $photoalbum_website_icon = ''; }
					
				if (trim($row_comments['papc_username']) == '') {
					$photoalbum_entry_title = 'Guest';
				} else { $photoalbum_entry_title = $row_comments['papc_username']; }
				
				if ($row_comments['papc_location'] != '') {
					$photoalbum_entry_title .= ' ('.$row_comments['papc_location'].')';
				}
				
				dbhcms_p_add_block_values('photoalbumPicComment', array(	dbhcms_f_value_to_output($row_comments['papc_username'], DBHCMS_C_DT_STRING), 
																			dbhcms_f_value_to_output($row_comments['papc_location'], DBHCMS_C_DT_STRING), 
																			dbhcms_f_value_to_output(strtotime($row_comments['papc_datetime']), DBHCMS_C_DT_DATETIME),
																			$photoalbum_entry_text, 
																			dbhcms_f_value_to_output($delete_btn, DBHCMS_C_DT_HTML), 
																			dbhcms_f_value_to_output($row_comments['papc_email'], DBHCMS_C_DT_STRING),
																			dbhcms_f_value_to_output($row_comments['papc_homepage'], DBHCMS_C_DT_STRING),
																			dbhcms_f_value_to_output($new_tag, DBHCMS_C_DT_HTML),
																			dbhcms_f_value_to_output($row_comments['papc_sex'], DBHCMS_C_DT_SEX),
																			dbhcms_f_value_to_output($photoalbum_sex_icon, DBHCMS_C_DT_HTML),
																			dbhcms_f_value_to_output($photoalbum_email_icon, DBHCMS_C_DT_HTML),
																			dbhcms_f_value_to_output($photoalbum_website_icon, DBHCMS_C_DT_HTML),
																			dbhcms_f_value_to_output($photoalbum_entry_title, DBHCMS_C_DT_STRING)
																		
																		));
			}
		} else {
			dbhcms_p_show_block('photoalbumPicCommentsNone');
		}
	}

	if (defined('DBHCMS_C_EXT_SMILIES')) {
		dbhcms_p_add_string('photoalbumSmiliesBar', smilies_f_create_smilies_bar('photoalbumAddPicComment', 'photoalbumText'));
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>