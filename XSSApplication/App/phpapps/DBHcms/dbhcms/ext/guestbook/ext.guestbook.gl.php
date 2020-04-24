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
#  guestbook                                                                                #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  A guestbook                                                                              #
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
# $Id: ext.guestbook.gl.php 71 2007-10-15 10:07:42Z kaisven $                               #
#############################################################################################

	define('DBHCMS_C_EXT_GUESTBOOK', 'guestbook');

#############################################################################################
#  SETTINGS                                                                                 #
#############################################################################################

	$ext_name 		= DBHCMS_C_EXT_GUESTBOOK;
	
	$ext_title 		= 'Guestbook';
	$ext_descr 		= 'A small guestbook.';
	$ext_inmenu		= true;
	$ext_version	= '1.1';
	$ext_icon			= 'accessories-text-editor';
	
	dbhcms_p_configure_extension($ext_name, $ext_title, $ext_descr, $ext_inmenu, $ext_version, $ext_icon);

#############################################################################################
#  LOAD CONFIGURATION                                                                       #
#############################################################################################

	if (in_array(DBHCMS_C_EXT_GUESTBOOK, $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['availableExtensions'])) {
	
		$result = mysql_query("SELECT gbcg_id, gbcg_value, gbcg_type FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_guestbook_config");
		while ($row = mysql_fetch_assoc($result)) {
			dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_GUESTBOOK], $row['gbcg_id']);
			$GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_GUESTBOOK][$row['gbcg_id']] = dbhcms_f_dbvalue_to_value(dbhcms_f_str_replace_all_vars(strval($row['gbcg_value'])), $row['gbcg_type']);
		}
	
	}

#############################################################################################
#  GLOBAL IMPLEMENTATION                                                                    #
#############################################################################################

	if (in_array(DBHCMS_C_EXT_GUESTBOOK, $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['availableExtensions'])) {
	
		if (!(isset($_SESSION['DBHCMSDATA']['GB']['signed']))) {
			$_SESSION['DBHCMSDATA']['GB']['signed'] = false;
		}
	
	}

	function guestbook_f_get_config_param($aparam) {
		if (isset($GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_GUESTBOOK][$aparam])) {
			return $GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_GUESTBOOK][$aparam];
		} else {
			return false;
		}
	}

	function guestbook_p_add_entry($afullname, $asex, $acompany, $alocation, $aemail, $ahomepage, $acomment) {
		if (trim($ahomepage) == 'http://') {
			$guestbook_homepage = '';
		} else { $guestbook_homepage = $ahomepage; }
		
		$banned = false;
		foreach (guestbook_f_get_config_param('wordFilter') as $word) {
			if (substr_count($acomment, $word) > 0) {
				$banned = true;
				break;
			}
		}
		
		if (!$banned) {
			
			mysql_query	("	INSERT INTO 
								".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_guestbook_entries 
									(
										gben_domn_id,
										gben_page_id,
										gben_name,
										gben_sex,
										gben_company,
										gben_location,
										gben_email,
										gben_website,
										gben_text,
										gben_date
									) 
								VALUES 
									(
										'".$GLOBALS['DBHCMS']['DID']."',
										'".$GLOBALS['DBHCMS']['PID']."', 
										'".$afullname."', 
										'".$asex."', 
										'".$acompany."',  
										'".$alocation."', 
										'".$aemail."', 
										'".$guestbook_homepage."', 
										'".$acomment."',  
										NOW()
									) 
								");
			
			$_SESSION['DBHCMSDATA']['GB']['signed'] = true;
			
			return true;
			
		} else {
		
			return false;
		
		}
		
		
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>
