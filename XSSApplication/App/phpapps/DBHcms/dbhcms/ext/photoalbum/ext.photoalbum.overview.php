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
# $Id: ext.photoalbum.overview.php 61 2007-02-01 14:17:59Z kaisven $                        #
#############################################################################################

#############################################################################################
#  FE IMPLEMENTATION - ALBUM OVERVIEW                                                       #
#############################################################################################

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# DEFINE BLOCKS                                                            #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	dbhcms_p_hide_block('photoalbumAlbumOverview');
	dbhcms_p_hide_block('photoalbumShowPic');

	dbhcms_p_add_block('photoalbumAlbum', $photoalbum_alb_blkprms);
	dbhcms_p_add_block('photoalbumNewest', $photoalbum_alb_blkprms);
	dbhcms_p_add_block('photoalbumOverview', array('albumJumplinks'));

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

	$photoalbum_query = "SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albs WHERE INSTR('".implode('', $_SESSION['DBHCMSDATA']['AUTH']['userLevels'])."', paal_userlevel) > 0 ".$photoalbum_restrict." ORDER BY paal_date DESC";

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
	    $photoalbum_jumplink .= "[<a class=\"jumplink\" href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('paFrom' => ($photoalbum_from - $photoalbum_more))) . "\">«</a>]";
	}
	for ($i = 1; ($i * $photoalbum_more) < $photoalbum_jumplinktotal; $i++) {
		$j = $i - 1;
		if (($j * $photoalbum_more) != $photoalbum_from) {
	    	$photoalbum_jumplink .= " [<a class=\"jumplink\" href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('paFrom' => ($j * $photoalbum_more)))  . "\">" . $i . '</a>] ';
		} else {
	    	$photoalbum_jumplink .= ' <strong>' . $i . '</strong> ';
		}
	}
	$j = $i - 1;
	if (($j * $photoalbum_more) < $photoalbum_jumplinktotal) {
		if (($j * $photoalbum_more) != $photoalbum_from) {
		    $photoalbum_jumplink .= " [<a class=\"jumplink\" href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('paFrom' => ($j * $photoalbum_more))) . "\">" . $i . '</a>] ';
		} else {
	    	$photoalbum_jumplink .= ' <strong>' . $i . '</strong> ';
		}
	}
	if ($photoalbum_jumplinktotal > ($photoalbum_from + $photoalbum_more)) {
	    $photoalbum_jumplink .= "[<a class=\"jumplink\" href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('paFrom' => ($photoalbum_from + $photoalbum_more))) . "\">»</a>]";
	}

	dbhcms_p_add_block_values('photoalbumOverview', array($photoalbum_jumplink));

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# ALBUMS                                                                   #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	$first_album = true;
	$result_albs = mysql_query($photoalbum_query);
	while ($row_albs = mysql_fetch_array($result_albs)) {
		
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
											'', # album jumplinks not necesary here, just for the album overview
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
		dbhcms_p_add_block_values('photoalbumAlbum', $photoalbum_albs_values);
		
		# add newest album only 
		if ($first_album) {
			dbhcms_p_add_block_values('photoalbumNewest', $photoalbum_albs_values);
			$first_album = false;
		}
		
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>