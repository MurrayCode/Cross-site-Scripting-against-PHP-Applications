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
# $Id: mod.global.php 60 2007-02-01 13:34:54Z kaisven $                                     #
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
#	MODULE MOD.GLOBAL.PHP                                                                     #
#############################################################################################

#############################################################################################
#	DEFINE SOME LAYOUT STUFF                                                                  #
#############################################################################################

	define('DBHCMS_ADMIN_C_RCL', '#F0F0F0');
	define('DBHCMS_ADMIN_C_RCD', '#DEDEDE');
	define('DBHCMS_ADMIN_C_RCH', '#D2D4FF');

#############################################################################################
#	MODULE RESULTS                                                                            #
#############################################################################################

	dbhcms_p_add_string('dbhcms_admin_rcl', DBHCMS_ADMIN_C_RCL);
	dbhcms_p_add_string('dbhcms_admin_rcd', DBHCMS_ADMIN_C_RCD);
	dbhcms_p_add_string('dbhcms_admin_rch', DBHCMS_ADMIN_C_RCH);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>
