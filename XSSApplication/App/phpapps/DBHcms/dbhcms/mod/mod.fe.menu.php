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
# $Id: mod.fe.menu.php 60 2007-02-01 13:34:54Z kaisven $                                    #
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
#	MODULE MOD.FE.PHP                                                                         #
#############################################################################################

	if (isset($_GET['fe_domain_id'])) {
		$fe_domain_id = $_GET['fe_domain_id'];
	} else { $fe_domain_id = $GLOBALS['DBHCMS']['DID']; }

	$domain_tabs = '';

	$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS);
	while ($row = mysql_fetch_assoc($result)) {
		if ($row['domn_id'] == $fe_domain_id ) {
			$domain_tabs .= '<td><div class="tab_act" style="border-bottom: 1px solid #444DFE;">&nbsp;&nbsp;&nbsp;&nbsp; <a target="dbhcms_admin_content" class="blacklink" href="index.php?dbhcms_pid=-40&fe_domain_id='.$row['domn_id'].'"> '.$row['domn_name'].' </a> &nbsp;&nbsp;&nbsp;&nbsp; </div></td><td width="5"></td>';
		} else {
			$domain_tabs .= '<td><div class="tab_no" style="border-bottom: 1px solid #444DFE;">&nbsp;&nbsp;&nbsp;&nbsp; <a target="dbhcms_admin_content" class="blacklink" href="index.php?dbhcms_pid=-40&fe_domain_id='.$row['domn_id'].'"> '.$row['domn_name'].' </a> &nbsp;&nbsp;&nbsp;&nbsp; </div></td><td width="5"></td>';
		}
	
	}

#############################################################################################
#	MODULE RESULT PARAMETERS                                                                  #
#############################################################################################

	dbhcms_p_add_string('dbhcms_fe_tabs', $domain_tabs);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>