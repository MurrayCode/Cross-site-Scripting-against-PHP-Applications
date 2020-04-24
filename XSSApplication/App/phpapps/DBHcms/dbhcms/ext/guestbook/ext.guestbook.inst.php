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
#  guestbook                                                                                #
#                                                                                           #
#  FILENAME                                                                                 #
#  =============================                                                            #
#  ext.guestbook.inst.php                                                                   #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  A guestbook                                                                              #
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
			
			dbhcms_f_array_push_assoc($dbhcms_database_sql['EXT'], 'guestbook');
			$dbhcms_database_sql['EXT']['guestbook'] = array();
			
			array_push($dbhcms_database_sql['EXT']['guestbook'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_guestbook_config` (
																  `gbcg_id` varchar(150) NOT NULL default '',
																  `gbcg_value` text NOT NULL,
																  `gbcg_type` varchar(150) NOT NULL,
																  `gbcg_description` text NOT NULL,
																  PRIMARY KEY  (`gbcg_id`)
																);");
			array_push($dbhcms_database_sql['EXT']['guestbook'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."ext_guestbook_config` (`gbcg_id`, `gbcg_value`, `gbcg_type`, `gbcg_description`) VALUES ('jumplinkMax', '25', 'DT_INTEGER', 'Maximum Jump Links'),
																	('jumplinkMore', '10', 'DT_INTEGER', 'Entries per page'),
																	('newDays', '20', 'DT_INTEGER', 'How many days the new-tag is shown'),
																	('newTag', '<img alt=\"[dict_new]\" align=\"absmiddle\" src=\"[imageDirectory]other/new.gif\">', 'DT_TEXT', 'Tag for a new element'),
																	('specificDomain', '1', 'DT_BOOLEAN', 'Show only entries that where entered in the same domain'),
																	('specificPage', '1', 'DT_BOOLEAN', 'Show only entries that where entered in the same page'),
																	('wordFilter', 'poker;viagra;gambling', 'DT_STRARRAY', 'Entries with these words will not be inserted.');
																");
			array_push($dbhcms_database_sql['EXT']['guestbook'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_guestbook_entries` (
																  `gben_id` int(11) NOT NULL auto_increment,
																  `gben_domn_id` int(11) NOT NULL default '0',
																  `gben_page_id` int(11) NOT NULL default '0',
																  `gben_name` varchar(250) NOT NULL default '',
																  `gben_sex` varchar(30) default NULL,
																  `gben_company` varchar(250) default NULL,
																  `gben_location` varchar(250) default NULL,
																  `gben_email` varchar(250) default NULL,
																  `gben_website` varchar(250) default NULL,
																  `gben_text` text,
																  `gben_date` datetime NOT NULL default '0000-00-00 00:00:00',
																  PRIMARY KEY  (`gben_id`)
																);");

#############################################################################################
#  DEINSTALLATION                                                                           #
#############################################################################################

		} else if  (DBHCMS_C_EXT_SETUP == 'DEINST') {
			
			dbhcms_f_array_push_assoc($dbhcms_database_sql['EXT'], 'guestbook');
			$dbhcms_database_sql['EXT']['guestbook'] = array();
			
			array_push($dbhcms_database_sql['EXT']['guestbook'], "DROP TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_guestbook_config`;");
			array_push($dbhcms_database_sql['EXT']['guestbook'], "DROP TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_guestbook_entries`;");
			
		}
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>