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
# $Id: ext.photoalbum.gl.php 72 2007-10-15 10:15:23Z kaisven $                              #
#############################################################################################

	define('DBHCMS_C_EXT_PHOTOALBUM', 'photoalbum');

#############################################################################################
#  SETTINGS                                                                                 #
#############################################################################################

	$ext_name 		= DBHCMS_C_EXT_PHOTOALBUM;
	
	$ext_title 		= 'Photo Album';
	$ext_descr 		= 'A full featured photo album with user authentication, comments and rating functions.';
	$ext_inmenu		= true;
	$ext_version	= '1.0';
	$ext_icon 		= 'camera-photo';

	dbhcms_p_configure_extension($ext_name, $ext_title, $ext_descr, $ext_inmenu, $ext_version, $ext_icon);

#############################################################################################
#  LOAD CONFIGURATION                                                                       #
#############################################################################################

	if (in_array(DBHCMS_C_EXT_PHOTOALBUM, $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['availableExtensions'])) {
	
		$result = mysql_query("SELECT pacg_id, pacg_value, pacg_type FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_config ORDER BY pacg_id");
		while ($row = mysql_fetch_assoc($result)) {
			dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_PHOTOALBUM], $row['pacg_id']);
			$GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_PHOTOALBUM][$row['pacg_id']] = dbhcms_f_dbvalue_to_value(dbhcms_f_str_replace_all_vars(strval($row['pacg_value'])), $row['pacg_type']);
		}
	
	}

#############################################################################################
#  GLOBAL IMPLEMENTATION                                                                    #
#############################################################################################

	function photoalbum_f_get_config_param($aparam, $update = false) {
		if ((!$update) && (isset($GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_PHOTOALBUM][$aparam]))) {
			return $GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_PHOTOALBUM][$aparam];
		} else {
			$result = mysql_query("SELECT pacg_id, pacg_value, pacg_type FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_config WHERE pacg_id LIKE '".$aparam."'");
			if ($row = mysql_fetch_assoc($result)) {
				return dbhcms_f_dbvalue_to_value(dbhcms_f_str_replace_all_vars(strval($row['pacg_value'])), $row['pacg_type']);
			} else {
				return false;
			}
		}
	}

	function photoalbum_f_get_album_param($albumid, $aparam, $alang) {
		$result = mysql_fetch_assoc(mysql_query("SELECT paav_value FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsvals WHERE paav_name LIKE '".$aparam."' AND paav_paal_id = ".$albumid." AND paav_lang LIKE '".$alang."' "));
		return dbhcms_f_str_replace_all_vars($result['paav_value']);
	}

	function photoalbum_p_add_missing_album_vals () {
		$photoalbum_langs = photoalbum_f_get_config_param('languages', true);
		$photoalbum_albs_result = mysql_query("SELECT *  FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albs");
		$count_missing_values = 0;
		while ($photoalbum_albs_row = mysql_fetch_array($photoalbum_albs_result)) {
			# check if exists and if not insert
			foreach ($photoalbum_langs as $tmvalue) {
				$result_alb_params = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsprms");
				$result_alb_vals = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsvals WHERE paav_paal_id = ".$photoalbum_albs_row['paal_id']." AND paav_lang LIKE '".$tmvalue."'");
				if (mysql_num_rows($result_alb_vals) < mysql_num_rows($result_alb_params) ) {
					while ($row_alb_params = mysql_fetch_array($result_alb_params)) {
						$result_alb_vals_param = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsvals WHERE paav_paal_id = ".$photoalbum_albs_row['paal_id']." AND paav_lang LIKE '".$tmvalue."' AND paav_name LIKE '".$row_alb_params['paap_name']."' ");
						if (mysql_num_rows($result_alb_vals_param) == 0 ) {
							mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsvals (`paav_paal_id` , `paav_name` , `paav_value` , `paav_lang` ) VALUES ( ".$photoalbum_albs_row['paal_id'].", '".$row_alb_params['paap_name']."', '', '".$tmvalue."');");
							$count_missing_values++;
						}
					}
				}
			}
		}
		return $count_missing_values;
	}

	function photoalbum_f_delete_album($aalbumid) {
		$res = '<div style="color: #076619; font-weight: bold;">Album has been deleted.</div>';
		mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_piccomments WHERE papc_paal_id = ".$aalbumid)
			or $res = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Album photos could not be deleted.</div>';
		mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_pics WHERE papi_paal_id = ".$aalbumid)
			or $res = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Album photos could not be deleted.</div>';
		mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsvals WHERE paav_paal_id = ".$aalbumid)
			or $res = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Album values could not be deleted.</div>';
		mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albs WHERE paal_id = ".$aalbumid)
			or $res = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Album could not be deleted.</div>';
		return $res;
	}

	function photoalbum_f_add_pics_to_album($aalbumid) {
		$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albs WHERE paal_id = ".$aalbumid);
		if ($row = mysql_fetch_assoc($result)) {
			$verz = opendir($row["paal_folder"]);
			$i=0;
			while ($file = readdir($verz)) {
				if ($file != ".." && $file != ".") {
					if ((in_array(strtoupper(substr($file, (strlen($file) - 3))), photoalbum_f_get_config_param('formatImages')))||(in_array(strtoupper(substr($file, (strlen($file) - 3))), photoalbum_f_get_config_param('formatVideos')))) {
						if (mysql_num_rows(mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_pics WHERE papi_paal_id = ".$aalbumid." AND papi_filename like '".$file."'")) == 0){
							mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_pics (`papi_paal_id`, `papi_filename`, `papi_userlevel`) VALUES (".$aalbumid.", '".$file."', '".$row["paal_userlevel"]."');");
							$i++;
						}
					}
				}
			}
			$res = '<div style="color: #076619; font-weight: bold;">'.$i.' new pictures have been added to the album</div>';
		} else { 
			$res = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Album not found.</div>'; 
		}
		return $res;
	}

	function photoalbum_f_count_comments($aalbumid, $afilename) {
		return mysql_num_rows(mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_piccomments WHERE papc_paal_id  = ".$aalbumid." AND UPPER(papc_filename) LIKE UPPER('".$afilename."');"));
	}

	function photoalbum_f_count_videos($aalbumid) {
		$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_pics WHERE papi_paal_id  = ".$aalbumid);
		$video_count = 0;
		while ($row = mysql_fetch_assoc($result)) {
			if (in_array(strtoupper(substr($row['papi_filename'], (strlen($row['papi_filename']) - 3))), photoalbum_f_get_config_param('formatVideos'))) {
				$video_count++;
			}
		}
		return $video_count;
	}

	function photoalbum_f_count_images($aalbumid) {
		$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_pics WHERE papi_paal_id  = ".$aalbumid);
		$image_count = 0;
		while ($row = mysql_fetch_assoc($result)) {
			if (in_array(strtoupper(substr($row['papi_filename'], (strlen($row['papi_filename']) - 3))), photoalbum_f_get_config_param('formatImages'))) {
				$image_count++;
			}
		}
		return $image_count;
	}

	function photoalbum_p_add_picrating($apicid, $aratingnr) {
		mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_pics SET papi_rate_".$aratingnr." = (papi_rate_".$aratingnr." + 1) WHERE papi_id = ".$apicid);
	}

	function photoalbum_p_add_piccmnt($apicid, $afullname, $asex, $aemail, $ahomepage, $alocation, $acomment) {
		$banned = false;
		foreach (photoalbum_f_get_config_param('wordFilter') as $word) {
			if (substr_count($acomment, $word) > 0) {
				$banned = true;
				break;
			}
		}
		if (!$banned) {
			$result = mysql_query("SELECT papi_paal_id, papi_filename FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_pics WHERE papi_id = ".$apicid);
			$row = mysql_fetch_assoc($result);
			if ($ahomepage == 'http://') {
				$photoalbum_homepage = '';
			} else { 
				$photoalbum_homepage = $ahomepage; 
			}
			if (!mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_piccomments (`papc_paal_id` , `papc_user_id` , `papc_filename` , `papc_username` , `papc_sex` , `papc_email` , `papc_homepage` , `papc_location` , `papc_entrytext` , `papc_datetime` ) VALUES ('".$row['papi_paal_id']."', '".$_SESSION['DBHCMSDATA']['AUTH']['userId']."', '".$row['papi_filename']."', '".$afullname."', '".$asex."', '".$aemail."', '".$ahomepage."', '".$alocation."', \"". $acomment."\", NOW()) ")) {
				if ($GLOBALS['DBHCMS']['CONFIG']['CORE']['debug']) {
					echo "SQL Error: ".mysql_error();
				}
			}
		}
	}

	function photoalbum_f_create_userlevel_changestr($auserlevel, $atargetpage, $aalbid, $apicid, $afrom){
		$userlevel_text = "";
		foreach ($GLOBALS['DBHCMS']['TYPES']['FL'] as $ul) {
			if (in_array($ul, $_SESSION['DBHCMSDATA']['AUTH']['userLevels'])) {
				if ($auserlevel == $ul){
					$userlevel_text .= "<font color=\"#FF0000\">".$ul."</font>";
				} else { $userlevel_text .= "<a href=\"".dbhcms_f_get_url_from_pid_wp($atargetpage, array('showalb' => $aalbid, 'picid' => $apicid, 'paFrom' => $afrom, 'userlevel' => $ul))."\">".$ul."</a>"; }
			}
		}
		return $userlevel_text;
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>
