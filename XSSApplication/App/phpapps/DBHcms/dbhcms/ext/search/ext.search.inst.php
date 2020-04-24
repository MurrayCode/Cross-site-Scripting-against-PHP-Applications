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
#  FILENAME                                                                                 #
#  =============================                                                            #
#  ext.search.inst.php                                                                      #
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
#  26.05.2007:                                                                              #
#  -----------                                                                              #
#  File created                                                                             #
#                                                                                           #
#############################################################################################
# $Id$                                                                                      #
#############################################################################################

	if (defined('DBHCMS_C_EXT_SETUP')) {

#############################################################################################
#  INSTALLATION                                                                             #
#############################################################################################

		if  (DBHCMS_C_EXT_SETUP == 'INST') {
		
			dbhcms_f_array_push_assoc($dbhcms_database_sql['EXT'], 'search');
			$dbhcms_database_sql['EXT']['search'] = array();
			
			array_push($dbhcms_database_sql['EXT']['search'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_search_queries` (
																  `sequ_id` int(11) NOT NULL auto_increment,
																  `sequ_sessionid` varchar(250) NOT NULL default '',
																  `sequ_query` varchar(250) NOT NULL default '',
																  `sequ_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
																  PRIMARY KEY  (`sequ_id`)
																);");

#############################################################################################
#  DEINSTALLATION                                                                           #
#############################################################################################

		} else if  (DBHCMS_C_EXT_SETUP == 'DEINST') {
			
			dbhcms_f_array_push_assoc($dbhcms_database_sql['EXT'], 'search');
			$dbhcms_database_sql['EXT']['search'] = array();
			
			array_push($dbhcms_database_sql['EXT']['search'], "DROP TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_search_queries`;");
			
		}
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>