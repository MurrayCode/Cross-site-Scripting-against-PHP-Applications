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
# $Id: mod.page.frames.php 60 2007-02-01 13:34:54Z kaisven $                                #
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
#	MODULE MOD.PAGES.PHP                                                                      #
#############################################################################################

	if (isset($_GET['deletepage'])) {
		$action_result = '<div style="color: #076619; font-weight: bold;">Page has been deleted.</div>';
		mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEPARAMS." WHERE papa_page_id = ".$_GET['deletepage'])
			or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Page could be deleted.</div><br><br>'.mysql_error();
		mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEVALS." WHERE pava_page_id = ".$_GET['deletepage'])
			or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Page could be deleted.</div><br><br>'.mysql_error();
		mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." WHERE page_id = ".$_GET['deletepage'])
			or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Page could be deleted.</div><br><br>'.mysql_error();
		mysql_query("UPDATE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES." SET page_parent_id = 0 WHERE page_parent_id = ".$_GET['deletepage'])
			or $action_result = '<div style="color: #FF0000; font-weight: bold;">ERROR! - Page could be deleted.</div><br><br>'.mysql_error();
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>