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
#  gcfg.php                                                                                 #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Loads global configuration for DBHcms                                                    #
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
# $Id: gcfg.php 68 2007-05-31 20:28:17Z kaisven $                                           #
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

	dbhcms_p_register_file(realpath(__FILE__), 'gcfg', 0.1);

#############################################################################################
#  LOAD DBHCMS PARAMETERS                                                                   #
#############################################################################################

	$result = mysql_query("SELECT cnfg_id, cnfg_value, cnfg_type FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_CONFIG);
	while ($row = mysql_fetch_array($result)) {
		dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['CONFIG']['PARAMS'], $row['cnfg_id']);
		dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['paramDataTypes'], $row['cnfg_id']);
		$GLOBALS['DBHCMS']['CONFIG']['PARAMS'][$row['cnfg_id']] = dbhcms_f_dbvalue_to_value($row['cnfg_value'], $row['cnfg_type']);
		$GLOBALS['DBHCMS']['CONFIG']['PARAMS']['paramDataTypes'][$row['cnfg_id']] = $row['cnfg_type'];
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>