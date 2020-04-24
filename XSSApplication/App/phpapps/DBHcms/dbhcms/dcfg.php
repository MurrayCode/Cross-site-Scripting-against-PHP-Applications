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
#  dcfg.php                                                                                 #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Domain configuration                                                                     #
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
# $Id: dcfg.php 59 2007-02-01 13:05:33Z kaisven $                                           #
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

	dbhcms_p_register_file(realpath(__FILE__), 'dcfg', 0.1);

#############################################################################################
#  DEFINE DOMAIN ID                                                                         #
#############################################################################################

	### DEFINE DOMAIN ###
	if (isset($_GET['dbhcms_did'])) {
		$dbhcms_domain_id = intval($_GET['dbhcms_did']);
		$result = mysql_query("SELECT domn_id FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS." WHERE domn_id = ".$dbhcms_domain_id);
		if (mysql_num_rows($result) == 0) { 
			dbhcms_p_error('Domain with ID "'.$dbhcms_domain_id.'" does not exist.', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			$result = mysql_query("SELECT domn_id FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS);
			if ($row = mysql_fetch_assoc($result)) { 
				$dbhcms_domain_id = intval($row['domn_id']);
			} else {
				dbhcms_p_error('No domain defined!', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			}
		}
	} else {
		$result = mysql_query("SELECT domn_id FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS." WHERE upper(domn_name) LIKE upper('".$_SERVER['HTTP_HOST']."') ");
		if (mysql_num_rows($result) == 0) { 
			$result = mysql_query("SELECT domn_id FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS); 
		}
		if ($row = mysql_fetch_assoc($result)) { 
			$dbhcms_domain_id = intval($row['domn_id']); 
		} else { 
			dbhcms_p_error('No domain defined!', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		}
	}
	$GLOBALS['DBHCMS']['DID'] = intval($dbhcms_domain_id); unset($dbhcms_domain_id);

#############################################################################################
#  LOAD DOMAIN SETTINGS                                                                     #
#############################################################################################

	$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS." WHERE domn_id = ".$GLOBALS['DBHCMS']['DID']);
	if ($row = mysql_fetch_array($result)) {
		
		$GLOBALS['DBHCMS']['DOMAIN']['absoluteUrl'] = dbhcms_f_dbvalue_to_value($row['domn_absolute_url'], DBHCMS_C_DT_STRING);
		$GLOBALS['DBHCMS']['DOMAIN']['subFolderCount'] = intval(substr_count(dbhcms_f_dbvalue_to_value($row['domn_subfolders'], DBHCMS_C_DT_STRING), '/') - 1);
		$GLOBALS['DBHCMS']['DOMAIN']['hostName'] = dbhcms_f_dbvalue_to_value($row['domn_name'], DBHCMS_C_DT_STRING);
		$GLOBALS['DBHCMS']['DOMAIN']['subFolders'] = dbhcms_f_dbvalue_to_value($row['domn_subfolders'], DBHCMS_C_DT_STRING);
		$GLOBALS['DBHCMS']['DOMAIN']['supportedLangs'] = dbhcms_f_dbvalue_to_value($row['domn_supported_langs'], DBHCMS_C_DT_LANGARRAY);
		$GLOBALS['DBHCMS']['DOMAIN']['defaultLang'] = dbhcms_f_dbvalue_to_value($row['domn_default_lang'], DBHCMS_C_DT_LANGUAGE);
		
		### STANDARD PAGE ID'S ###
		# Page-ID of the Intro-Page
		$GLOBALS['DBHCMS']['DOMAIN']['introPageId'] = dbhcms_f_dbvalue_to_value($row['domn_intro_pid'], DBHCMS_C_DT_INTEGER);
		# Page-ID of the Index-Page
		$GLOBALS['DBHCMS']['DOMAIN']['indexPageId'] = dbhcms_f_dbvalue_to_value($row['domn_index_pid'], DBHCMS_C_DT_INTEGER);
		# Page-ID to go after Log-Out
		$GLOBALS['DBHCMS']['DOMAIN']['logoutPageId'] = dbhcms_f_dbvalue_to_value($row['domn_logout_pid'], DBHCMS_C_DT_INTEGER);
		# Page-ID to go for Log-In
		$GLOBALS['DBHCMS']['DOMAIN']['loginPageId'] = dbhcms_f_dbvalue_to_value($row['domn_login_pid'], DBHCMS_C_DT_INTEGER);
		# Page-ID to go at acces denied
		$GLOBALS['DBHCMS']['DOMAIN']['accessDeniedPageId'] = dbhcms_f_dbvalue_to_value($row['domn_ad_pid'], DBHCMS_C_DT_INTEGER);
		# Page-ID to go at error 401 (Unauthorized)
		$GLOBALS['DBHCMS']['DOMAIN']['err401PageId'] = dbhcms_f_dbvalue_to_value($row['domn_err401_pid'], DBHCMS_C_DT_INTEGER);
		# Page-ID to go at error 403 (Forbidden)
		$GLOBALS['DBHCMS']['DOMAIN']['err403PageId'] = dbhcms_f_dbvalue_to_value($row['domn_err403_pid'], DBHCMS_C_DT_INTEGER);
		# Page-ID to go at error 404 (File Not Found)
		$GLOBALS['DBHCMS']['DOMAIN']['err404PageId'] = dbhcms_f_dbvalue_to_value($row['domn_err404_pid'], DBHCMS_C_DT_INTEGER);
		
		# Page setting for all pages in the domain
		$GLOBALS['DBHCMS']['DOMAIN']['stylesheets'] = dbhcms_f_dbvalue_to_value($row['domn_stylesheets'], DBHCMS_C_DT_STRARRAY);
		$GLOBALS['DBHCMS']['DOMAIN']['javascripts'] = dbhcms_f_dbvalue_to_value($row['domn_javascripts'], DBHCMS_C_DT_STRARRAY);
		$GLOBALS['DBHCMS']['DOMAIN']['templates'] = dbhcms_f_dbvalue_to_value($row['domn_templates'], DBHCMS_C_DT_STRARRAY);
		$GLOBALS['DBHCMS']['DOMAIN']['modules'] = dbhcms_f_dbvalue_to_value($row['domn_php_modules'], DBHCMS_C_DT_STRARRAY);
		$GLOBALS['DBHCMS']['DOMAIN']['extensions']	= dbhcms_f_dbvalue_to_value($row['domn_extensions'], DBHCMS_C_DT_STRARRAY);
		
	} else {
		dbhcms_p_error('Could not load domain. Domain with ID "'.$GLOBALS['DBHCMS']['DID'].'" does not exist.', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>