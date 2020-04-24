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
# $Id: mod.instinfo.php 60 2007-02-01 13:34:54Z kaisven $                                   #
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
#	MODULE MOD.INSTINFO.PHP                                                                   #
#############################################################################################

	# hide db password
	$arr_global_dbhcms = $GLOBALS['DBHCMS'];
	$arr_global_dbhcms['CONFIG']['DB']['passwd'] = '****';

	# hide user password
	$arr_session_dbhcms = $_SESSION['DBHCMSDATA'];
	$arr_session_dbhcms['AUTH']['password'] = '****';

	$instance_info =	'<div class="box">
							<div class="box_caption"> &nbsp; Sessions </div>
							<div style="padding: 8px;">'.dbhcms_f_get_arr_html(dbhcms_f_get_sessions()).'</div>
						</div>
						<div class="box">
							<div class="box_caption"> &nbsp; DBHcms Globals </div>
							<div style="padding: 8px;">'.dbhcms_f_get_arr_html($arr_global_dbhcms).'</div>
						</div>
							<div class="box"><div class="box_caption"> &nbsp; DBHcms Session </div>
							<div style="padding: 8px;">'.dbhcms_f_get_arr_html($arr_session_dbhcms).'</div>
						</div>';

	dbhcms_p_add_string('dbhcms_inst_info', $instance_info);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>