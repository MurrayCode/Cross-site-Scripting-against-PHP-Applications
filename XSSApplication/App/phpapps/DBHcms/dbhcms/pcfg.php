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
#  FILENAME                                                                                 #
#  =============================                                                            #
#  pcfg.php                                                                                 #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Loads page configuration for DBHcms                                                      #
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
# $Id: pcfg.php 59 2007-02-01 13:05:33Z kaisven $                                           #
#############################################################################################

#############################################################################################
#  SECURITY                                                                                 #
#############################################################################################

	if ((realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))||(!defined('DBHCMS'))) {
		die('	<div style="color: #872626; font-weight: bold;">
						DBHCMS FATAL ERROR - Access denied!
					</div>');
	}

#############################################################################################
#  REGISTER FILE                                                                            #
#############################################################################################

	dbhcms_p_register_file(realpath(__FILE__), 'pcfg', 0.1);
	
#############################################################################################
#  GET PAGE CONFIGURATION                                                                   #
#############################################################################################

	if ($GLOBALS['DBHCMS']['PID'] > 0) {
		# page_hide = 0 AND (page_schedule = 0 OR (UNIX_TIMESTAMP(NOW()) > UNIX_TIMESTAMP(page_start) AND  UNIX_TIMESTAMP(NOW()) < UNIX_TIMESTAMP(page_stop))) AND
		$result = mysql_query("SELECT page_id FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." WHERE page_domn_id = ".$GLOBALS['DBHCMS']['DID']." ORDER BY page_id");
		while ($row = mysql_fetch_assoc($result)) {
			$GLOBALS['DBHCMS']['PAGES'][$row['page_id']] = dbhcms_f_get_page($row['page_id'], $_SESSION['DBHCMSDATA']['LANG']['useLanguage']);
		}
	} else {
		$result = mysql_query("SELECT page_id FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." ORDER BY page_id");
		while ($row = mysql_fetch_assoc($result)) {
			$GLOBALS['DBHCMS']['PAGES'][$row['page_id']] = dbhcms_f_get_page($row['page_id'], 'auto');
		}
	}

#############################################################################################
#  REDIRECT IF SHORTCUT OR LINK                                                             #
#############################################################################################

	if ($GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['shortcut'] != 0) {
		header("Location: ".dbhcms_f_get_url_from_pid($GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['shortcut']));
		exit;
	} else if (trim($GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['link']) != '') {
		header("Location: ".$GLOBALS['DBHCMS']['PAGES'][$GLOBALS['DBHCMS']['PID']]['link']); 
		exit;
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

	?>