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
# $Id: ext.photoalbum.fe.php 61 2007-02-01 14:17:59Z kaisven $                              #
#############################################################################################

#############################################################################################
#  FE IMPLEMENTATION                                                                        #
#############################################################################################

	if (dbhcms_f_superuser_auth()) {
		if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumDeletePicComment'])) {
			# Delete picture comment 
			if (mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_piccomments WHERE papc_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumDeletePicComment'])) {
				if (($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['cacheEnabled'])&&($GLOBALS['DBHCMS']['PID'] > 0)) {
					if ($GLOBALS['DBHCMS']['CONFIG']['CORE']['debug']) {
						echo "Message: Cache deleted by picture comment deletion in ext.photoalbum.fe.php";
					}
					dbhcms_p_del_cache($GLOBALS['DBHCMS']['PID']);
				}
			} else {
				if ($GLOBALS['DBHCMS']['CONFIG']['CORE']['debug']) {
					echo "SQL Error: ".mysql_error();
				}
			}
		} elseif (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumDeletePic'])) {
			# Delete picture
			if (mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_pics WHERE papi_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['photoalbumDeletePic'])) {
				if (($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['cacheEnabled'])&&($GLOBALS['DBHCMS']['PID'] > 0)) {
					if ($GLOBALS['DBHCMS']['CONFIG']['CORE']['debug']) {
						echo "Message: Cache deleted by picture deletion in ext.photoalbum.fe.php";
					}
					dbhcms_p_del_cache($GLOBALS['DBHCMS']['PID']);
				}
			} else {
				if ($GLOBALS['DBHCMS']['CONFIG']['CORE']['debug']) {
					echo "SQL Error: ".mysql_error();
				}
			}
		} elseif (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['userlevel'])) {
			# Change picture userlevel
			if (mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_pics SET papi_userlevel = '".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['userlevel']."' WHERE papi_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['picid'])) {
				if (($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['cacheEnabled'])&&($GLOBALS['DBHCMS']['PID'] > 0)) {
					if ($GLOBALS['DBHCMS']['CONFIG']['CORE']['debug']) {
						echo "Message: Cache deleted by userlevel change in ext.photoalbum.fe.php";
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

	### ALBUM STANDARD PARAMETERS ###
	$photoalbum_alb_blkprms = array	( 
										  'albumThumbnail'
										, 'albumDate'
										, 'albumNewTag'
										, 'albumPicsUrl'
										, 'albumJumplinks'
										, 'albumCommentCount'
										, 'albumPictureCount'
										, 'albumVideoCount'
									);

	### ALBUM USER DEFINED PARAMETERS ###
	$result_parameters = mysql_query("SELECT paap_name from ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_photoalbum_albsprms ORDER BY paap_name");
	while ($row_parameters = mysql_fetch_array($result_parameters)) {
		array_push($photoalbum_alb_blkprms, 'albumParam'.ucfirst($row_parameters['paap_name']));
	}

	if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['showpic'])) {
		include($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].DBHCMS_C_EXT_PHOTOALBUM.'/ext.'.DBHCMS_C_EXT_PHOTOALBUM.'.picture.php');
	} else if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['showalb'])) {
		include($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].DBHCMS_C_EXT_PHOTOALBUM.'/ext.'.DBHCMS_C_EXT_PHOTOALBUM.'.album.php');
	} else {
		include($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].DBHCMS_C_EXT_PHOTOALBUM.'/ext.'.DBHCMS_C_EXT_PHOTOALBUM.'.overview.php');
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>
