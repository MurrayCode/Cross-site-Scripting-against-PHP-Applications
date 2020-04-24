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
#  dict.php                                                                                 #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Loads the DBHcms Dictionary                                                              #
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
# $Id: dict.php 68 2007-05-31 20:28:17Z kaisven $                                           #
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

	dbhcms_p_register_file(realpath(__FILE__), 'dict', 0.1);

#############################################################################################
#  LOAD DBHCMS DICTIONARY                                                                   #
#############################################################################################

	$result = mysql_query("SELECT dict_name, dict_value FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DICTIONARY." WHERE dict_lang LIKE '".$_SESSION['DBHCMSDATA']['LANG']['useLanguage']."'");
	while ($row = mysql_fetch_assoc($result)) {
		dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['DICT']['FE'], $row['dict_name']);
		$GLOBALS['DBHCMS']['DICT']['FE'][$row['dict_name']] = $row['dict_value'];
	}

	if ($GLOBALS['DBHCMS']['PID'] < 0 ) {
		if (isset($_GET['dbhcms_changecorelang'])) {
			if (in_array($_GET['dbhcms_changecorelang'], $GLOBALS['DBHCMS']['CONFIG']['CORE']['supportedLangs'])) {
				$_SESSION['DBHCMSDATA']['LANG']['coreUseLanguage'] = $_GET['dbhcms_changecorelang'];
			}
		}
		$result = mysql_query("SELECT dict_name, dict_value FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DICTIONARY." WHERE dict_lang LIKE '".$_SESSION['DBHCMSDATA']['LANG']['coreUseLanguage']."'");
		while ($row = mysql_fetch_assoc($result)) {
			dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['DICT']['BE'], $row['dict_name']);
			$GLOBALS['DBHCMS']['DICT']['BE'][$row['dict_name']] = $row['dict_value'];
		}
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>
