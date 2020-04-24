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
# $Id: ext.photoalbum.editalbum.php 61 2007-02-01 14:17:59Z kaisven $                       #
#############################################################################################

#############################################################################################
#  BE IMPLEMENTATION - EDIT ALBUMS                                                          #
#############################################################################################

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# ACTIONS                                                                  #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	if (isset($_POST['todo'])) {
		### NEW ALBUM ###
		if ($_POST['todo'] == 'photoalbumNewAlbum') {
			
			$action_result = '<div style="color: #076619; font-weight: bold;">New album has been saved.</div>';
			mysql_query("	INSERT INTO 
								".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albs 
									(
										paal_domn_id,
										paal_page_id,
										paal_folder,
										paal_thumbnail_img,
										paal_userlevel,
										paal_date
									) 
							VALUES 
									(
										'".dbhcms_f_input_to_dbvalue('paal_domn_id', DBHCMS_C_DT_DOMAIN)."', 
										'".dbhcms_f_input_to_dbvalue('paal_page_id', DBHCMS_C_DT_PAGE)."', 
										'".dbhcms_f_input_to_dbvalue('paal_folder', DBHCMS_C_DT_DIRECTORY)."', 
										'".dbhcms_f_input_to_dbvalue('paal_thumbnail_img', DBHCMS_C_DT_FILE)."', 
										'".dbhcms_f_input_to_dbvalue('paal_userlevel', DBHCMS_C_DT_USERLEVEL)."', 
										'".dbhcms_f_input_to_dbvalue('paal_date', DBHCMS_C_DT_DATE)."'
									);
						
						") or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Album could not be saved.</div>';
			
			$GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumEditAlbum'] = mysql_insert_id();
			
			photoalbum_p_add_missing_album_vals();
			
		### SAVE ALPBUM ###
		} else if ($_POST['todo'] == 'photoalbumSaveAlbum') {
			
			$action_result = '<div style="color: #076619; font-weight: bold;">Album has been saved.</div>';
			mysql_query("	
							UPDATE 
								".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albs 
							SET 
								paal_domn_id = '".dbhcms_f_input_to_dbvalue('paal_domn_id', DBHCMS_C_DT_DOMAIN)."', 
								paal_page_id = '".dbhcms_f_input_to_dbvalue('paal_page_id', DBHCMS_C_DT_PAGE)."', 
								paal_folder = '".dbhcms_f_input_to_dbvalue('paal_folder', DBHCMS_C_DT_DIRECTORY)."', 
								paal_thumbnail_img = '".dbhcms_f_input_to_dbvalue('paal_thumbnail_img', DBHCMS_C_DT_FILE)."', 
								paal_userlevel = '".dbhcms_f_input_to_dbvalue('paal_userlevel', DBHCMS_C_DT_USERLEVEL)."', 
								paal_date = '".dbhcms_f_input_to_dbvalue('paal_date', DBHCMS_C_DT_DATE)."' 
							WHERE 
								paal_id = ".dbhcms_f_input_to_dbvalue('paal_id', DBHCMS_C_DT_INTEGER)."
							
						") or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Album could not be saved.</div>';
			
		### SAVE LANGUAGE ###
		} else if ($_POST['todo'] == 'photoalbumSaveAlbumLang') {
			
			$action_result = '<div style="color: #076619; font-weight: bold;">Settings for "'.strtoupper($GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumAlbumPart']).'" have been saved.</div>';
			$result_albvals = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsvals, ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsprms WHERE paav_name = paap_name AND paav_paal_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumEditAlbum']." AND paav_lang LIKE '".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumAlbumPart']."'");
			while ($row_albvals = mysql_fetch_array($result_albvals)) {
				mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsvals SET paav_value = '".dbhcms_f_input_to_dbvalue($row_albvals['paav_name'], $row_albvals['paap_type'])."' WHERE paav_name LIKE '".$row_albvals['paav_name']."' AND paav_paal_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumEditAlbum']." AND paav_lang LIKE '".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumAlbumPart']."'")
					or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Settings for "'.strtoupper($_GET['part']).'" could not be saved.</div>';
			}
			
		}
	} else {
		### ADD PICS TO ALBUM ###
		if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumAddPicToAlbum'])) {
			$action_result = photoalbum_f_add_pics_to_album($GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumAddPicToAlbum']);
		### DELETE ALBUM ###
		} else if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumDeleteAlbum'])) {
		 	$action_result = photoalbum_f_delete_album($GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumDeleteAlbum']);
		}
	}

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# NEW ALBUM                                                                #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumNewAlbum'])) {
		
		dbhcms_p_add_template_ext('photoalbumContent', 'photoalbum.albums.edit.tpl', 'photoalbum');
		
		$album_form = '<form method="post" action="'.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_PHOTOALBUM, 'photoalbumBePart' => 'albums')).'">
							<input type="hidden" name="todo" value="photoalbumNewAlbum">
									<tr bgcolor="#DEDEDE" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#DEDEDE\'">
										<td align="right" width="150"><strong>Domain : </strong></td>
										<td align="center" width="202">'.dbhcms_f_value_to_input('paal_domn_id', $GLOBALS['DBHCMS']['DID'], DBHCMS_C_DT_DOMAIN, 'photoalbum_overall', 'width:204px;').'</td>
										<td></td>
									</tr>
									<tr bgcolor="#F0F0F0" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#F0F0F0\'">
										<td align="right" width="150"><strong>Page : </strong></td>
										<td align="center" width="202">'.dbhcms_f_value_to_input('paal_page_id', 0, DBHCMS_C_DT_PAGE, 'photoalbum_overall', 'width:204px;').'</td>
										<td></td>
									</tr>
								<tr bgcolor="#DEDEDE" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#DEDEDE\'">
									<td align="right" width="150"><strong>Folder : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('paal_folder', '', DBHCMS_C_DT_DIRECTORY, 'photoalbum_overall', 'width:204px;').'</td>
									<td></td>
								</tr>
								<tr bgcolor="#F0F0F0" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#F0F0F0\'">
									<td align="right" width="150"><strong>Thumbnail Image : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('paal_thumbnail_img', '', DBHCMS_C_DT_FILE, 'photoalbum_overall', 'width:204px;').'</td>
									<td></td>
								</tr>
								<tr bgcolor="#DEDEDE" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#DEDEDE\'">
									<td align="right" width="150"><strong>User Level : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('paal_userlevel', 'A', DBHCMS_C_DT_USERLEVEL, 'photoalbum_overall', 'width:206px;').'</td>
									<td></td>
								</tr>
								<tr bgcolor="#F0F0F0" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#F0F0F0\'">
									<td align="right" width="150"><strong>Date : </strong></td>
									<td align="center" width="202">'.dbhcms_f_value_to_input('paal_date', mktime(), DBHCMS_C_DT_DATE, 'photoalbum_overall', 'width:204px;').'</td>
									<td></td>
								</tr>
							</table>
							<table cellpadding="2" cellspacing="1" border="0" width="100%" align="center">
								<tr>
									<td>  
										<br>
										<input type="submit" value=" '.$GLOBALS['DBHCMS']['DICT']['BE']['save'].' "> 
									</td>
								</tr>
						  </form>';
		
		dbhcms_p_add_string('photoalbumAlbumParams', $album_form);

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# EDIT ALBUM                                                               #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	} elseif (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumEditAlbum'])) {
		
		dbhcms_p_add_template_ext('photoalbumContent', 'photoalbum.albums.edit.tpl', 'photoalbum');
		
		$photoalbum_langs = array('overall');
		foreach (photoalbum_f_get_config_param('languages') as $tmvalue) {
			array_push($photoalbum_langs, $tmvalue);
		}
		
		if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumAlbumPart'])) { 
			$album_part = $GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumAlbumPart']; 
		} else { 
			$album_part = 'overall'; 
		}
		
		$album_tabs = '';
		foreach ($photoalbum_langs as $tmvalue) {
			
			if ($tmvalue == 'overall') {
				$cap = 'Album';
			} else {
				$cap = $tmvalue.' ('.dbhcms_f_dict($tmvalue).')';
			}
			
			if ($album_part == $tmvalue) {
				$album_tabs .= '	<td>
										<div class="tab_act">
											&nbsp;&nbsp; <a href="'.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_PHOTOALBUM, 'photoalbumBePart' => 'albums', 'photoalbumEditAlbum' => $GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumEditAlbum'], 'photoalbumAlbumPart' => $tmvalue)).'"> '.$cap.' </a> &nbsp;&nbsp;
										</div>
									</td><td width="5"></td>';
			} else {
				$album_tabs .= '	<td>
										<div class="tab_no"> 
											&nbsp;&nbsp; <a href="'.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_PHOTOALBUM, 'photoalbumBePart' => 'albums', 'photoalbumEditAlbum' => $GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumEditAlbum'], 'photoalbumAlbumPart' => $tmvalue)).'"> '.$cap.' </a> &nbsp;&nbsp;
										</div>
									</td><td width="5"></td>';
			}
		}
		
		dbhcms_p_add_string('photoalbumAlbumTabs', $album_tabs);
		
		if ($album_part == 'overall') {
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# EDIT ALBUM OVERALL SETTINGS                                              #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
				$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albs WHERE paal_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumEditAlbum']);
				$row = mysql_fetch_array($result);
				
				$album_form = '<form action="'.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_PHOTOALBUM, 'photoalbumBePart' => 'albums', 'photoalbumEditAlbum' => $GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumEditAlbum'], 'photoalbumAlbumPart' => $album_part)).'" method="post" name="photoalbum_overall">
									<input type="hidden" name="todo" value="photoalbumSaveAlbum">
									<input type="hidden" name="paal_id" value="'.$GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumEditAlbum'].'">
										<tr bgcolor="#DEDEDE" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#DEDEDE\'">
											<td align="right" width="150"><strong>Domain : </strong></td>
											<td align="center" width="202">'.dbhcms_f_dbvalue_to_input('paal_domn_id', $row['paal_domn_id'], DBHCMS_C_DT_DOMAIN, 'photoalbum_overall', 'width:204px;').'</td>
											<td></td>
										</tr>
										<tr bgcolor="#F0F0F0" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#F0F0F0\'">
											<td align="right" width="150"><strong>Page : </strong></td>
											<td align="center" width="202">'.dbhcms_f_dbvalue_to_input('paal_page_id', $row['paal_page_id'], DBHCMS_C_DT_PAGE, 'photoalbum_overall', 'width:204px;').'</td>
											<td></td>
										</tr>
										<tr bgcolor="#DEDEDE" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#DEDEDE\'">
											<td align="right" width="150"><strong>Folder : </strong></td>
											<td align="center" width="202">'.dbhcms_f_dbvalue_to_input('paal_folder', $row['paal_folder'], DBHCMS_C_DT_DIRECTORY, 'photoalbum_overall', 'width:204px;').'</td>
											<td></td>
										</tr>
										<tr bgcolor="#F0F0F0" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#F0F0F0\'">
											<td align="right" width="150"><strong>Thumbnail Image : </strong></td>
											<td align="center" width="202">'.dbhcms_f_dbvalue_to_input('paal_thumbnail_img', $row['paal_thumbnail_img'], DBHCMS_C_DT_FILE, 'photoalbum_overall', 'width:204px;').'</td>
											<td></td>
										</tr>
										<tr bgcolor="#DEDEDE" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#DEDEDE\'">
											<td align="right" width="150"><strong>User Level : </strong></td>
											<td align="center" width="202">'.dbhcms_f_dbvalue_to_input('paal_userlevel', $row['paal_userlevel'], DBHCMS_C_DT_USERLEVEL, 'photoalbum_overall', 'width:206px;').'</td>
											<td></td>
										</tr>
										<tr bgcolor="#F0F0F0" onmouseover="this.bgColor = \'#D2D4FF\'" onmouseout="this.bgColor = \'#F0F0F0\'">
											<td align="right" width="150"><strong>Date : </strong></td>
											<td align="center" width="202">'.dbhcms_f_dbvalue_to_input('paal_date', $row['paal_date'], DBHCMS_C_DT_DATE, 'photoalbum_overall', 'width:204px;').'</td>
											<td></td>
										</tr>
									</table>
									<table cellpadding="2" cellspacing="1" border="0" width="100%" align="center">
										<tr>
											<td>
												<br>
												<input type="submit" value=" '.$GLOBALS['DBHCMS']['DICT']['BE']['save'].' ">
											</td>
										</tr>
								  </form>';
		
		} else {
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# EDIT ALBUM USER DEFINED SETTINGS                                         #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
				$album_form = '';
				
				$result_albvals = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsvals, ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsprms WHERE paav_name = paap_name AND paav_paal_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumEditAlbum']." AND paav_lang LIKE '".$album_part."'");
				$i = 0;
				while ($row_albvals = mysql_fetch_array($result_albvals)) {
					
					if ($i & 1) { 
						$album_form .= "<tr bgcolor=\"#F0F0F0\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '#F0F0F0'\">"; 
					} else { 
						$album_form .= "<tr bgcolor=\"#DEDEDE\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '#DEDEDE'\">"; 
					}
					
					$album_form .= "<td align=\"right\" valign=\"top\" width=\"200\"><strong>".$row_albvals['paav_name']." :</strong></td>";
					$album_form .= "<td align=\"center\" valign=\"top\" width=\"202\">".dbhcms_f_dbvalue_to_input($row_albvals['paav_name'], $row_albvals['paav_value'], $row_albvals['paap_type'], 'photoalbum_lang', 'width:204px;')."</td><td>".$row_albvals['paap_description']."</td></tr>";
					$i++;
					
				}
				$album_form = '	<form action="'.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_PHOTOALBUM, 'photoalbumBePart' => 'albums', 'photoalbumEditAlbum' => $GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumEditAlbum'], 'photoalbumAlbumPart' => $album_part)).'" method="post" name="photoalbum_lang">
										<input type="hidden" name="todo" value="photoalbumSaveAlbumLang">
										<input type="hidden" name="paal_id" value="'.$GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumEditAlbum'].'">
										'.$album_form.'
									</table>
									<table cellpadding="2" cellspacing="1" border="0" width="100%" align="center">
										<tr>
											<td>  
												<br>
												<input type="submit" value=" '.$GLOBALS['DBHCMS']['DICT']['BE']['save'].' "> 
											</td>
										</tr>
								</form>'; 
		
		}
		
		dbhcms_p_add_string('photoalbumAlbumParams', $album_form);

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# SHOW ALBUMS                                                              #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#

	} else {
		
		dbhcms_p_add_template_ext('photoalbumContent', 'photoalbum.albums.tpl', 'photoalbum');
		
		$i = 0;
		$photoalbum_albums = '';
		$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albs ORDER BY paal_date DESC");
		while ($row = mysql_fetch_array($result)) {
			
			if ($i & 1) { 
				$photoalbum_albums .= "<tr bgcolor=\"#F0F0F0\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '#F0F0F0'\">"; 
			} else { 
				$photoalbum_albums .= "<tr bgcolor=\"#DEDEDE\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '#DEDEDE'\">"; 
			}
			
			$photoalbum_albums .= "<td align=\"center\" valign=\"top\"><strong>".$row['paal_id']."</strong></td>";
			$photoalbum_albums .= "<td align=\"left\" valign=\"top\"><strong>".photoalbum_f_get_album_param($row['paal_id'], 'title', $_SESSION['DBHCMSDATA']['LANG']['useLanguage'])."</strong></td>";
			$photoalbum_albums .= "<td align=\"left\" valign=\"top\">".dbhcms_f_dbvalue_to_output($row['paal_date'], DBHCMS_C_DT_DATE)."</td>";
			$photoalbum_albums .= "<td align=\"left\" valign=\"top\">".$row['paal_folder']."</td>";
			$photoalbum_albums .= "<td align=\"center\" valign=\"top\">".'<img src="'.$row['paal_thumbnail_img'].'">'."</td>";
			$photoalbum_albums .= "<td align=\"center\" valign=\"top\" width=\"50\"><a href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_PHOTOALBUM, 'photoalbumBePart' => 'albums', 'photoalbumAddPicToAlbum' => $row['paal_id'])).'">'.dbhcms_f_get_icon('list-add', dbhcms_f_dict('add', true), 1).'</a></td>';
			$photoalbum_albums .= "<td align=\"center\" valign=\"top\" width=\"50\"><a href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_PHOTOALBUM, 'photoalbumBePart' => 'albums', 'photoalbumEditAlbum' => $row['paal_id']))."\">".dbhcms_f_get_icon('document-properties', dbhcms_f_dict('edit', true), 1)."</a></td>";
			$photoalbum_albums .= "<td align=\"center\" valign=\"top\" width=\"50\"><a onclick=\" return confirm('".dbhcms_f_dict('dbhcms_msg_askdeleteitem', true)."'); \" href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_PHOTOALBUM, 'photoalbumBePart' => 'albums', 'photoalbumDeleteAlbum' => $row['paal_id']))."\">".dbhcms_f_get_icon('edit-delete', dbhcms_f_dict('delete', true), 1)."</a></td>";
			$i++;
		}
		dbhcms_p_add_string('photoalbumAlbums', $photoalbum_albums);
		dbhcms_p_add_string('photoalbumNewAlbumUrl', dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_PHOTOALBUM, 'photoalbumBePart' => 'albums', 'photoalbumNewAlbum' => 'new')));
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>