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
# $Id: mod.menu.php 60 2007-02-01 13:34:54Z kaisven $                                       #
#############################################################################################

#############################################################################################
#  SECURITY                                                                                 #
#############################################################################################

	if ((realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))||(!defined('DBHCMS'))||(!dbhcms_f_superuser_auth())) {
		die('	<div style="color: #872626; font-weight: bold;">
						DBHCMS FATAL ERROR - Access denied!
					</div>');
	}

#############################################################################################
#	MODULE MOD.ADMIN.MENU.PHP                                                                 #
#############################################################################################

	$dbhcms_extension_menu = '';

	if (count($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['availableExtensions']) > 0) {
		foreach ($GLOBALS['DBHCMS']['CONFIG']['EXT'] as $tmkey => $tmvalue ){
			if ($tmvalue['inMenu']) {
				$dbhcms_extension_menu .=	'<div class="menu_box_item"><a href="index.php?dbhcms_pid=-100&ext='.$tmkey.'" target="dbhcms_admin_content">'.$tmvalue['title'].'</a></div>';
			}
		}
	} else {
		$dbhcms_extension_menu = '<div style="padding:5px;">No extensions available.</div>';
	}

#############################################################################################
#	MODULE RESULT PARAMETERS                                                                  #
#############################################################################################

	dbhcms_p_add_string('dbhcms_admin_ext_menu', $dbhcms_extension_menu);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>