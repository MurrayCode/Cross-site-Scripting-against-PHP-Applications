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
# $Id: mod.start.php 60 2007-02-01 13:34:54Z kaisven $                                      #
#############################################################################################

#############################################################################################
#                                                                                           #
#  SECURITY                                                                                 #
#                                                                                           #
#############################################################################################

	if (!defined('DBHCMS')) {
		dbhcms_p_error('Access denied!', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
	}

#############################################################################################
#	MODULE MOD.START.PHP																	#
#############################################################################################
	
	$ae = '';
	
	if (count($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['availableExtensions']) > 0) {
		foreach ($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['availableExtensions'] as $ext) {
			if ($GLOBALS['DBHCMS']['CONFIG']['EXT'][$ext]['inMenu']) {
				$icon = dbhcms_f_get_icon($GLOBALS['DBHCMS']['CONFIG']['EXT'][$ext]['icon'], $GLOBALS['DBHCMS']['CONFIG']['EXT'][$ext]['title'], 3, 'style="border: 1px solid #444DFE; padding: 8px;"');
				if ($icon == '') {
					$icon = dbhcms_f_get_icon('application-x-executable', $GLOBALS['DBHCMS']['CONFIG']['EXT'][$ext]['title'], 3, 'style="border: 1px solid #444DFE; padding: 8px;"');
				}
				$ae .= '
								<td></td>
								<td><a href="index.php?dbhcms_pid=-100&ext='.$ext.'">'.$icon.'</a></a></td>
								<td><a href="index.php?dbhcms_pid=-100&ext='.$ext.'">'.$GLOBALS['DBHCMS']['CONFIG']['EXT'][$ext]['title'].'</a></td>
						 	';			
			}
		}
	} else {
		$ae = '
						<td></td>
						<td><strong>No extensions available.</strong></td>
						<td></td>
					';
	}

#############################################################################################
#  MODULE RESULTS                                                                           #
#############################################################################################

	dbhcms_p_add_string('menuAvailableExtensions', $ae);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>
