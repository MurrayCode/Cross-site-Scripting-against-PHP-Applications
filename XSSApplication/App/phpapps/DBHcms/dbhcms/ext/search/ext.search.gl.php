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
#  EXTENSION                                                                                #
#  =============================                                                            #
#  search                                                                                   #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Content search engine                                                                    #
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
# $Id: ext.search.gl.php 60 2007-02-01 13:34:54Z kaisven $                                  #
#############################################################################################

	define('DBHCMS_C_EXT_SEARCH', 'search');

#############################################################################################
#  SETTINGS                                                                                 #
#############################################################################################

	$ext_name 		= DBHCMS_C_EXT_SEARCH;
	
	$ext_title 		= 'Search';
	$ext_descr		= 'A small search engine';
	$ext_inmenu		= false;
	$ext_version	= '1.0';

	dbhcms_p_configure_extension($ext_name, $ext_title, $ext_descr, $ext_inmenu, $ext_version);

#############################################################################################
#  GLOBAL IMPLEMENTATION                                                                    #
#############################################################################################

	function search_f_get_pages($astring) {
		
		$searchtypes  = "'".DBHCMS_C_DT_STRING."', ";
		$searchtypes .= "'".DBHCMS_C_DT_STRARRAY."', ";
		$searchtypes .= "'".DBHCMS_C_DT_INTEGER."', ";
		$searchtypes .= "'".DBHCMS_C_DT_INTARRAY."', ";
		$searchtypes .= "'".DBHCMS_C_DT_TEXT."', ";
		$searchtypes .= "'".DBHCMS_C_DT_HTML."', ";
		$searchtypes .= "'".DBHCMS_C_DT_CONTENT."'";
		
		$result_pages = array();
		$insert = mysql_query("INSERT INTO ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_search_queries (sequ_sessionid, sequ_query, sequ_datetime) VALUES ('".$_SESSION['DBHCMSDATA']['SID']."', '".$astring."', NOW()); ");
		$result = mysql_query("	SELECT 
															pava_page_id, 
															pava_value,
															page_userlevel 
														FROM 
															".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEVALS.", 
															".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGES.", 
															".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_PAGEPARAMS." 
														WHERE 
															papa_name = pava_name AND 
															pava_page_id = page_id AND 
															page_hide = 0 AND 
															(
																page_schedule = 0 OR 
																(
																	UNIX_TIMESTAMP(NOW()) > UNIX_TIMESTAMP(page_start) AND  
																	UNIX_TIMESTAMP(NOW()) < UNIX_TIMESTAMP(page_stop)
																)
															) AND 
															papa_type IN (".$searchtypes.") AND 
															UPPER(pava_value) LIKE UPPER('%".$astring."%') AND 
															pava_page_id > 0 AND 
															page_domn_id = ".$GLOBALS['DBHCMS']['DID']." AND 
															pava_lang LIKE '".$_SESSION['DBHCMSDATA']['LANG']['useLanguage']."'
													");
		
		while ($row = mysql_fetch_assoc($result)) {
			if (in_array($row['page_userlevel'], $_SESSION['DBHCMSDATA']['AUTH']['userLevels'])) {
				$result_pages[$row['pava_page_id']]['name'] 	= dbhcms_f_get_page_value($row['pava_page_id'], DBHCMS_C_PAGEVAL_NAME, $_SESSION['DBHCMSDATA']['LANG']['useLanguage']);
				$result_pages[$row['pava_page_id']]['url'] 		= dbhcms_f_get_url_from_pid($row['pava_page_id']);
				$result_pages[$row['pava_page_id']]['content'] 	= trim(dbhcms_f_str_replace_all_vars(substr(strip_tags($row['pava_value']), 0, 200)));
			}
		}
		return $result_pages;
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>