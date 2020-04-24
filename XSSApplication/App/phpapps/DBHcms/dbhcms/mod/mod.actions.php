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
# $Id: mod.actions.php 60 2007-02-01 13:34:54Z kaisven $                                    #
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
#	MODULE MOD.GENHTACCESS.PHP                                                                #
#############################################################################################

	if ($_GET['action'] == 'genhtaccess') {
		$dbhcms_htaccess_script = " \n ";
		$dbhcms_htaccess_file = fopen(".htaccess", "w");
		if(!$dbhcms_htaccess_file) {
			$action_result = '<div style="color: #FF0000"><strong>ERROR! .htaccess could not be generated</strong></div>';
		} else {
			$dbhcms_htaccess_script .= "ErrorDocument 401 /".$GLOBALS['DBHCMS']['CONFIG']['PARAMS']['rootDirectory']."index.php?document_error=401 \n";
			$dbhcms_htaccess_script .= "ErrorDocument 403 /".$GLOBALS['DBHCMS']['CONFIG']['PARAMS']['rootDirectory']."index.php?document_error=403 \n";
			$dbhcms_htaccess_script .= "ErrorDocument 404 /".$GLOBALS['DBHCMS']['CONFIG']['PARAMS']['rootDirectory']."index.php?document_error=404 \n\n";
			if ($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['simulateStaticUrls'] == 1) {
				$dbhcms_htaccess_script .= "RewriteEngine on \n\n";
				$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS);
				while($row = mysql_fetch_array($result)){
					$dbhcms_htaccess_script .= "RewriteCond %{REQUEST_URI} ^".strval($row['domn_subfolders'])."$ [NC] \n";
					$dbhcms_htaccess_script .= "RewriteCond %{HTTP_HOST} ^".strval($row['domn_name'])."$ [NC] \n";
					$dbhcms_htaccess_script .= "RewriteRule ^$ ./index.php?dbhcms_did=".strval($row['domn_id'])." [QSA]  \n\n";
				}
				$dbhcms_htaccess_script .= "RewriteRule ^admin.html$ ./index.php?dbhcms_pid=".$GLOBALS['DBHCMS']['CONFIG']['CORE']['indexPageId']." [QSA] \n";
				$dbhcms_htaccess_script .= "RewriteRule ^belogin.html$ ./index.php?dbhcms_pid=".$GLOBALS['DBHCMS']['CONFIG']['CORE']['loginPageId']." [QSA] \n";
				$dbhcms_htaccess_script .= "RewriteRule .*-([0-9]*)-([0-9]*)-([a-z]*).html ./index.php?dbhcms_did=$1&dbhcms_pid=$2&dbhcms_lang=$3 [QSA] \n";
				$dbhcms_htaccess_script .= "RewriteRule .*-([0-9]*)-([0-9]*)-([a-z]*).(.*).html ./index.php?dbhcms_did=$1&dbhcms_pid=$2&dbhcms_lang=$3&dbhcms_params=$4 [QSA] \n\n";
			}
			fwrite($dbhcms_htaccess_file, $dbhcms_htaccess_script);
			fclose($dbhcms_htaccess_file);
			$action_result = '<div style="color: #076619"><strong>.htaccess generated succesfully</strong></div>';
		}
		dbhcms_p_add_string('action_content', str_replace("ErrorDocument", "<strong>ErrorDocument</strong>", str_replace("}", "&#125;</strong>", str_replace("%{", "<strong style=\"color:#0000FF\">%&#123;", str_replace("]", "]</strong>", str_replace("[", "<strong>[", str_replace("RewriteRule", "<strong>RewriteRule</strong>", str_replace("RewriteCond", "<strong>RewriteCond</strong>", str_replace("RewriteEngine", "<strong>RewriteEngine</strong>", str_replace("\n", "<br>", $dbhcms_htaccess_script))))))))));
		dbhcms_p_add_string('action_title', '.htaccess');
	} else if ($_GET['action'] == 'addmissingpagevals') {
		if (dbhcms_f_superuser_auth()) {
			$missing_vals = dbhcms_p_add_missing_pagevals();
			$action_result = '<div style="color: #076619; font-weight: bold;">'.count($missing_vals).' missing Page-Values where added.</div>';
			if (count($missing_vals) > 0) {
				$added_vals = '<strong>Values added:</strong><br><br>';
				foreach ($missing_vals as $val) {
					$added_vals .= $val.'<br>';
				}
			} else { $added_vals = '<strong>No values added</strong><br>'; }
		} else $action_result = '<div style="color: #076619; font-weight: bold;">ERROR - Access Denied!</div>';
		dbhcms_p_add_string('action_content', $added_vals);
		dbhcms_p_add_string('action_title', 'Added Page Values');
	} else if ($_GET['action'] == 'emptytemp') {
		$deleted_files = '';
		$verz = opendir($GLOBALS['DBHCMS']['CONFIG']['CORE']['tempDirectory']);
		$i=0;
		while ($file = readdir($verz)){
			if ($file != ".." && $file != ".") {
				unlink($GLOBALS['DBHCMS']['CONFIG']['CORE']['tempDirectory'].$file);
				$deleted_files .= $file.'<br>';
				$i++;
			}
		}
		$action_result = '<div style="color: #076619"><strong>'.$i.' files deleted. Temporary folder was emptied.</strong></div>';
		if ($i == 0) {
			dbhcms_p_add_string('action_content', '<strong>No files deleted.</strong>');
		} else { dbhcms_p_add_string('action_content', $deleted_files); }
		dbhcms_p_add_string('action_title', 'Deleted Files');
	} else if ($_GET['action'] == 'emptycache') {
		dbhcms_p_del_cache();
		$action_result = '<div style="color: #076619; font-weight: bold;">Cache was emptied.</div>';
		dbhcms_p_add_string('action_content', '<strong>The cache for all pages was deleted.</strong>');
		dbhcms_p_add_string('action_title', 'Deleted Cache');
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>