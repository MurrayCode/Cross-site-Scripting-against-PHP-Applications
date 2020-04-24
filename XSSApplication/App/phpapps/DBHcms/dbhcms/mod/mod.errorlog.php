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
# $Id: mod.errorlog.php 68 2007-05-31 20:28:17Z kaisven $                                   #
#############################################################################################

	$dbhcms_report_error_email = 'dbhcmserr@drbenhur.com';

#############################################################################################
#  SECURITY                                                                                 #
#############################################################################################

	if ((realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))||(!defined('DBHCMS'))||(!dbhcms_f_superuser_auth())) {
		die('	<div style="color: #872626; font-weight: bold;">
						DBHCMS FATAL ERROR - Access denied!
					</div>');
	}

#############################################################################################
#	MODULE MOD.ERRORLOG.PHP                                                                   #
#############################################################################################

	$error_values = '';

	if (isset($_POST['err_log_empty'])) {
	  if (mysql_query("TRUNCATE TABLE ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_ERRORLOG)) {
	    $action_result = '<div style="color: #076619; font-weight: bold;">The error log has been emptied!</div>';
	  } else {
	    $action_result = '<div style="color: #872626; font-weight: bold;">The error log could not be emptied!</div>';
	  }
	}

	if (isset($_POST['al_search_error'])) {
		$search_sql = " WHERE upper(erlg_error) LIKE upper('%".$_POST['al_search_error']."%') ";
		$search_params = '&al_search_error='.$_POST['al_search_error'];
		dbhcms_p_add_string('al_search_error', $_POST['al_search_error']);
	} else if (isset($_POST['al_search_type'])) {
		$search_sql = " WHERE erlg_isfatal LIKE '".$_POST['al_search_type']."' ";
		$search_params = '&al_search_type='.$_POST['al_search_type'];
		dbhcms_p_add_string('al_search_type_'.$_POST['al_search_type'], 'selected');
	} elseif (isset($_GET['al_search_error'])) {
		$search_sql = " WHERE upper(erlg_error) LIKE upper('%".$_GET['al_search_error']."%') ";
		$search_params = '&al_search_error='.$_GET['al_search_error'];
		dbhcms_p_add_string('al_search_error', $_GET['al_search_error']);
	} else if (isset($_GET['al_search_type'])) {
		$search_sql = " WHERE erlg_isfatal LIKE '".$_GET['al_search_type']."' ";
		$search_params = '&al_search_type='.$_GET['al_search_type'];
		dbhcms_p_add_string('al_search_type_'.$_GET['al_search_type'], 'selected');
	} else { 
		$search_sql = ''; 
		$search_params = '';
	}

	if (isset($_POST['report_error'])) {
		$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_ERRORLOG." WHERE erlg_id = ".$_POST['report_error']);
		if ($row = mysql_fetch_assoc($result)) {
			
			$mailto	 = $dbhcms_report_error_email;
			$subject = $GLOBALS['DBHCMS']['DOMAIN']['hostName'].' - DBHcms error report';
			
			$mts =	'	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//DE">
						<html>
							<head>
								<title>DBHcms error report</title>
							</head>
							<body>
								<h3>DBHcms error report</h3>
								<table border="1" width="100%">
									<tr>
										<td width="100"><strong>Error : </strong></td>
										<td>'.$row['erlg_error'].'</td>
									</tr>
									<tr>
										<td width="100"><strong>Fatal : </strong></td>
										<td>'.$row['erlg_isfatal'].'</td>
									</tr>
									<tr>
										<td width="100"><strong>File : </strong></td>
										<td>'.$row['erlg_file'].'</td>
									</tr>
									<tr>
										<td width="100"><strong>Class : </strong></td>
										<td>'.$row['erlg_class'].'</td>
									</tr>
									<tr>
										<td width="100"><strong>Function : </strong></td>
										<td>'.$row['erlg_function'].'</td>
									</tr>
									<tr>
										<td width="100"><strong>Line : </strong></td>
										<td>'.$row['erlg_line'].'</td>
									</tr>
									<tr>
										<td width="100"><strong>Date : </strong></td>
										<td>'.$row['erlg_datetime'].'</td>
									</tr>
								</table>
								<h3>Instance Info</h3>
								'.str_replace("\n", "<br>",$row['erlg_instinfo']).'
							</body>
						</html>';
			
			$header  = "Content-Type: text/html; charset=\"iso-8859-1\" \n";
			$header .= "Content-Transfer-Encoding: quoted-printable \n";
			$header .= "From: ".$dbhcms_report_error_email." \n";
			
  		  	mail($mailto, $subject, $mts, $header);
			
			$action_result = '<div style="color: #076619; font-weight: bold;">Thank you! Your error report has been sent to '.$dbhcms_report_error_email.'.</div>';
			
		}
	}

	$more = 30;
	
	if (isset($_GET['from'])) {
		$from = $_GET['from'];
	} else {
		$from = 0;
	}
	
	$jumplinktotal = mysql_num_rows(mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_ERRORLOG." ".$search_sql." ORDER BY erlg_datetime DESC"));
	$query = "SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_ERRORLOG." ".$search_sql." ORDER BY erlg_datetime DESC LIMIT ".$from." , ".$more;
	
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
			$error_values .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCD."\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCD."'\">"; 
		} else { 
			$error_values .= "<tr bgcolor=\"".DBHCMS_ADMIN_C_RCL."\" onmouseover=\"this.bgColor = '#D2D4FF'\" onmouseout=\"this.bgColor = '".DBHCMS_ADMIN_C_RCL."'\">"; 
		}
		
		if ($row['erlg_isfatal'] == 1) {
			$error_values .= "<td width=\"20\" align=\"center\">".dbhcms_f_get_icon('error')."</td>";
		} else { $error_values .= "<td width=\"20\" align=\"center\">".dbhcms_f_get_icon('warning')."</td>"; }
		
		$error_values .= "<td>".$row['erlg_error']."</td>";
		
		$error_values .= "<td>".$row['erlg_datetime']."</td>";
		
		$error_values .= '	<form method="post"><td width="180" align="center">
								<input type="hidden" name="report_error" value="'.$row['erlg_id'].'" />
								<input type="submit" value="Report this error >>" />
							</td></form></tr>';
		
		$i++;
	}

	dbhcms_p_add_string('dbhcms_errorlog', $error_values);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>