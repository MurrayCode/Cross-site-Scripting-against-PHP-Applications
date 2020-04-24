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
# $Id: mod.accesslog.php 68 2007-05-31 20:28:17Z kaisven $                                  #
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
#	MODULE MOD.ACCESSLOG.PHP                                                                  #
#############################################################################################

	$acces_values = '';

	if (isset($_POST['access_log_empty'])) {
	  if (mysql_query("TRUNCATE TABLE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_ACCESSLOG)) {
	    $action_result = '<div style="color: #076619; font-weight: bold;">The access log has been emptied!</div>';
	  } else {
	    $action_result = '<div style="color: #872626; font-weight: bold;">The access log could not be emptied!</div>';
	  }
	}

	if (isset($_POST['ul_search_user'])) {
		$search_sql = " WHERE upper(aclg_user) LIKE upper('".$_POST['ul_search_user']."') ";
		$search_params = '&ul_search_user='.$_POST['ul_search_user'];
	} else if (isset($_POST['ul_search_action'])) {
		$search_sql = " WHERE aclg_action LIKE '".$_POST['ul_search_action']."' ";
		$search_params = '&ul_search_action='.$_POST['ul_search_action'];
		dbhcms_p_add_string('ul_search_action_'.strtolower($_POST['ul_search_action']).'_sel', 'selected');
	} elseif (isset($_GET['ul_search_user'])) {
		$search_sql = " WHERE upper(aclg_user) LIKE upper('".$_GET['ul_search_user']."') ";
		$search_params = '&ul_search_user='.$_GET['ul_search_user'];
	} else if (isset($_GET['ul_search_action'])) {
		$search_sql = " WHERE aclg_action LIKE '".$_GET['ul_search_action']."' ";
		$search_params = '&ul_search_action='.$_GET['ul_search_action'];
		dbhcms_p_add_string('ul_search_action_'.strtolower($_GET['ul_search_action']).'_sel', 'selected');
	} else {
		$search_sql = '';
		$search_params = '';
	}

	$more = 30;
	
	if (isset($_GET['from'])) {
		$from = $_GET['from'];
	} else {
		$from = 0;
	}
	
	$jumplinktotal = mysql_num_rows(mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_ACCESSLOG." ".$search_sql." ORDER BY aclg_datetime DESC"));
	$query = "SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_ACCESSLOG." ".$search_sql." ORDER BY aclg_datetime DESC LIMIT ".$from." , ".$more;
	
	$jumplink = "";
	
	if ($from >= $more) {
	    $jumplink .= "[<a class=\"jumplink\" href=\"index.php?dbhcms_pid=".$GLOBALS['DBHCMS']['PID'].$search_params."&from=" . ($from - $more) . "\">«</a>]";
	}
	for ($i = 1; ($i * $more) < $jumplinktotal; $i++) {
		$j = $i - 1;
		if (($j * $more) != $from) {
	    	$jumplink .= " [<a class=\"jumplink\" href=\"index.php?dbhcms_pid=".$GLOBALS['DBHCMS']['PID'].$search_params."&from=" . ($j * $more)  . "\">" . $i . '</a>] ';
		} else {
	    	$jumplink .= ' <strong>' . $i . '</strong> ';
		}
	}
	$j = $i - 1;
	if (($j * $more) < $jumplinktotal) {
		if (($j * $more) != $from) {
		    $jumplink .= " [<a class=\"jumplink\" href=\"index.php?dbhcms_pid=".$GLOBALS['DBHCMS']['PID'].$search_params."&from=" . ($j * $more) . "\">" . $i . '</a>] ';
		} else {
	    	$jumplink .= ' <strong>' . $i . '</strong> ';
		}
	}
	if ($jumplinktotal >= ($from + $more)) {
	    $jumplink .= "[<a class=\"jumplink\" href=\"index.php?dbhcms_pid=".$GLOBALS['DBHCMS']['PID'].$search_params."&from=" . ($from + $more) . "\">»</a>]";
	}

	dbhcms_p_add_string('jumplinks', $jumplink);

	$i=0;
	$result = mysql_query($query);
	while ($row = mysql_fetch_assoc($result)) {
		
		if ($i & 1) { 
			$acces_values .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCD."\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCD."'\">"; 
		} else { 
			$acces_values .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCL."\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCL."'\">"; 
		}
		
		$acces_values .= "<td width=\"20\" align=\"center\">".dbhcms_f_get_icon(strtolower($row['aclg_action']), $row['aclg_action'])."</td>";
		
		if (($row['aclg_action'] == 'LOGIN')||($row['aclg_action'] == 'LOGOUT')) {
			$acces_values .= "<td style=\"color: #076619;\"><strong>".$row['aclg_user']."</strong></td>";
		} else {
			$acces_values .= "<td style=\"color: #FF0000;\"><strong>".$row['aclg_user']."</strong></td>";
		}
		
		$acces_values .= "<td>".$row['aclg_action']."</td>";
		
		$acces_values .= "<td>".$row['aclg_datetime']."</td>";
		$acces_values .= "<td>".$row['aclg_sessionid']."</td></tr>";
		
		$i++;
	}

	dbhcms_p_add_string('dbhcms_accesslog', $acces_values);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>