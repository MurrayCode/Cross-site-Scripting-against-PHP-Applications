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
# $Id: ext.photoalbum.album.php 61 2007-02-01 14:17:59Z kaisven $                           #
#############################################################################################

#############################################################################################
#  FE IMPLEMENTATION - ALBUM PICTURE OVERVIEW                                               #
#############################################################################################

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# DEFINE BLOCKS                                                            #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	dbhcms_p_hide_block('photoalbumOverview');
	dbhcms_p_hide_block('photoalbumShowPic');

	dbhcms_p_add_block('photoalbumAlbumPics', array	( 
														  'picImg'
														, 'picRating'
														, 'picRatingCount'
														, 'picCommentCount'
														, 'picId'
														, 'picUrl'
														, 'picNewRow'
														, 'picUserLevelChange'
														, 'picDelete'
													));
	
	dbhcms_p_add_block('photoalbumAlbumOverview', $photoalbum_alb_blkprms);

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

	$photoalbum_query = "	SELECT 
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
							) AS rating_cnt 
						FROM 
							".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_pics, 
							".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albs 
						WHERE 
							paal_id = papi_paal_id AND
							papi_paal_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['showalb']." AND 
							INSTR('".implode('', $_SESSION['DBHCMSDATA']['AUTH']['userLevels'])."', papi_userlevel ) > 0 
							".$photoalbum_restrict."
						ORDER BY 
							papi_filename ";

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# JUMPLINKS                                                                #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	$photoalbum_jumplinkmax = photoalbum_f_get_config_param('jumplinkMax');
	$photoalbum_more = photoalbum_f_get_config_param('jumplinkMore');

	if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['paFrom'])) {
		$photoalbum_from = $GLOBALS['DBHCMS']['TEMP']['PARAMS']['paFrom']; 
	} else { 
		$photoalbum_from = 0;
	}

	$photoalbum_jumplinktotal = mysql_num_rows(mysql_query($photoalbum_query));
	if ($photoalbum_jumplinktotal > ($photoalbum_more * $photoalbum_jumplinkmax)) {
		$photoalbum_more = ceil($photoalbum_jumplinktotal / $photoalbum_jumplinkmax);
	}
	$photoalbum_query = $photoalbum_query." LIMIT ".$photoalbum_from." , ".$photoalbum_more;

	$photoalbum_jumplink = "";

	if ($photoalbum_from >= $photoalbum_more) {
	    $photoalbum_jumplink .= "[<a class=\"jumplink\" href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('showalb' => $GLOBALS['DBHCMS']['TEMP']['PARAMS']['showalb'], 'paFrom' => ($photoalbum_from - $photoalbum_more))) . "\">«</a>]";
	}
	for ($i = 1; ($i * $photoalbum_more) < $photoalbum_jumplinktotal; $i++) {
		$j = $i - 1;
		if (($j * $photoalbum_more) != $photoalbum_from) {
	    	$photoalbum_jumplink .= " [<a class=\"jumplink\" href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('showalb' => $GLOBALS['DBHCMS']['TEMP']['PARAMS']['showalb'], 'paFrom' => ($j * $photoalbum_more)))  . "\">" . $i . '</a>] ';
		} else {
	    	$photoalbum_jumplink .= ' <strong>' . $i . '</strong> ';
		}
	}
	$j = $i - 1;
	if (($j * $photoalbum_more) < $photoalbum_jumplinktotal) {
		if (($j * $photoalbum_more) != $photoalbum_from) {
		    $photoalbum_jumplink .= " [<a class=\"jumplink\" href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('showalb' => $GLOBALS['DBHCMS']['TEMP']['PARAMS']['showalb'], 'paFrom' => ($j * $photoalbum_more))) . "\">" . $i . '</a>] ';
		} else {
	    	$photoalbum_jumplink .= ' <strong>' . $i . '</strong> ';
		}
	}
	if ($photoalbum_jumplinktotal > ($photoalbum_from + $photoalbum_more)) {
	    $photoalbum_jumplink .= "[<a class=\"jumplink\" href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('showalb' => $GLOBALS['DBHCMS']['TEMP']['PARAMS']['showalb'], 'paFrom' => ($photoalbum_from + $photoalbum_more))) . "\">»</a>]";
	}

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# ALBUM                                                                    #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	$result_albs = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albs WHERE paal_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['showalb']);
	if ($row_albs = mysql_fetch_array($result_albs)) {
		
		# new tag
		$differenz = strtotime($row_albs['paal_date']) - mktime(date('h'),date('i'),date('s'),date('m'),date('d'),date('Y'));
		$tage = $differenz/(60*60*24);
		if (abs($tage) < photoalbum_f_get_config_param('newDays')) { 
			$new_tag = photoalbum_f_get_config_param('newTag');
		} else { 
			$new_tag = ''; 
		}
		
		# page id for link
		if ($row_albs['paal_page_id'] == 0) {
			$page_id = $GLOBALS['DBHCMS']['PID'];
		} else {
			$page_id = $row_albs['paal_page_id'];
		}
		
		# set standard album values
		$photoalbum_albs_values = array	(
											$row_albs['paal_thumbnail_img'], # image file as jpg or gif
											date($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['dateFormatOutput'], strtotime($row_albs['paal_date'])), # date and time
											$new_tag, # new tag if new
											dbhcms_f_get_url_from_pid_wp($page_id, array('showalb' => $row_albs['paal_id'])), # url to the pics
											$photoalbum_jumplink, # album jumplinks not necesary here, just for the album overview
											photoalbum_f_count_comments($row_albs['paal_id'], '%'), # comment count
											photoalbum_f_count_images($row_albs['paal_id']), # image count
											photoalbum_f_count_videos($row_albs['paal_id']) # video count
										);
		
		# set user defined parameters
		$result_albs_pvals = mysql_query("SELECT paav_value, paap_type from ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsvals, ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsprms WHERE paav_name = paap_name AND paav_paal_id = ".$row_albs['paal_id']." AND paav_lang LIKE '".$_SESSION['DBHCMSDATA']['LANG']['useLanguage']."' ORDER BY paav_name");
		while ($row_albs_pvals = mysql_fetch_array($result_albs_pvals)) {
			if (defined('DBHCMS_C_EXT_SMILIES')) {
				array_push($photoalbum_albs_values, smilies_f_replace_smilies(dbhcms_f_value_to_output($row_albs_pvals['paav_value'], $row_albs_pvals['paap_type'])));
			} else {
				array_push($photoalbum_albs_values, dbhcms_f_value_to_output($row_albs_pvals['paav_value'], $row_albs_pvals['paap_type']));
			}
		}
		
		# add album 
		dbhcms_p_add_block_values('photoalbumAlbumOverview', $photoalbum_albs_values);
		
	}

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# PICTURES                                                                 #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	$rwc = 0;
	$i = 0;
	$result = mysql_query($photoalbum_query);
	$rcc 	= mysql_num_rows($result);
	while ($row = mysql_fetch_array($result)) {
		
		if (dbhcms_f_superuser_auth() == true) {
			$photoalbum_pic_change_ul = "<table cellpadding=\"0\" cellspacing=\"0\"><tr><td><font style=\"font-size:7pt;\" face=\"Small Fonts\"><strong>".photoalbum_f_create_userlevel_changestr($row['papi_userlevel'], $GLOBALS['DBHCMS']['PID'], $row_albs['paal_id'], $row['papi_id'], $photoalbum_from)."</strong></font></td></tr></table>";
			$photoalbum_pic_delete = '<a onclick="return confirm(\' '.dbhcms_f_dict('dbhcms_msg_askdeleteitem').' \');" href="'.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('showalb' => $GLOBALS['DBHCMS']['TEMP']['PARAMS']['showalb'], 'paFrom' => $photoalbum_from, 'photoalbumDeletePic' => $row['papi_id'])).'">'.dbhcms_f_get_icon('edit-delete', dbhcms_f_dict('delete'), 1)."</a>";
		} else { 
			$photoalbum_pic_change_ul = ''; 
			$photoalbum_pic_delete = '';
		}
		
		$rwc++;
		$i++;
		
		if (($rwc == photoalbum_f_get_config_param('viewColCount'))&&($i < $rcc)) {
			$new_row = '</tr><tr>';
			$rwc = 0;
		} else {
			$new_row = '';
		}
		
		if (in_array(strtoupper(substr($row['papi_filename'], (strlen($row['papi_filename']) - 3))), photoalbum_f_get_config_param('formatImages'))) {
			$image_file = $row_albs['paal_folder'].$row['papi_filename'];
		} else if (in_array(strtoupper(substr($row['papi_filename'], (strlen($row['papi_filename']) - 3))), photoalbum_f_get_config_param('formatVideos'))) {
			$image_file = photoalbum_f_get_config_param('videoclipThumbnail');
		} else {
			$image_file = '';
		}
		
		
		dbhcms_p_add_block_values('photoalbumAlbumPics', array	(
																	$image_file, 				# image file as gif or jpg
																	round($row['rating'], 0), 	# the rating of this picture
																	$row['rating_cnt'], 		# count of ratings
																	photoalbum_f_count_comments($row['papi_paal_id'], $row['papi_filename']), # comment count of this picture
																	$row['papi_id'], 			# picture id
																	dbhcms_f_get_url_from_pid_wp($page_id, array('showpic' => $row['papi_id'])), # url to the picture
																	$new_row,					# <tr>'s if new row
																	$photoalbum_pic_change_ul,  # userlevel change bar (only admin)
																	$photoalbum_pic_delete 		# picture delete button (only admin)
																));
		
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>