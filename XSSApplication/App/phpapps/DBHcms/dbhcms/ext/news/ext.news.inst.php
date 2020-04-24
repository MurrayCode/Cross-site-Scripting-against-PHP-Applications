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
#  news                                                                                     #
#                                                                                           #
#  FILENAME                                                                                 #
#  =============================                                                            #
#  ext.news.inst.php                                                                        #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  A tool to publish your news                                                              #
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
			
			dbhcms_f_array_push_assoc($dbhcms_database_sql['EXT'], 'news');
			$dbhcms_database_sql['EXT']['news'] = array();
			
			array_push($dbhcms_database_sql['EXT']['news'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_news_comments` (
															  `nwcm_id` int(11) NOT NULL auto_increment,
															  `nwcm_nwen_id` int(11) NOT NULL default '0',
															  `nwcm_user_id` int(11) default '0',
															  `nwcm_username` varchar(250) default NULL,
															  `nwcm_sex` varchar(30) default NULL,
															  `nwcm_email` varchar(250) default NULL,
															  `nwcm_homepage` varchar(250) default NULL,
															  `nwcm_location` varchar(250) default NULL,
															  `nwcm_entrytext` text,
															  `nwcm_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
															  PRIMARY KEY  (`nwcm_id`)
															);");

			array_push($dbhcms_database_sql['EXT']['news'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_news_config` (
															  `nwcg_id` varchar(150) NOT NULL default '',
															  `nwcg_value` text NOT NULL,
															  `nwcg_type` varchar(150) NOT NULL default '',
															  `nwcg_description` text NOT NULL,
															  PRIMARY KEY  (`nwcg_id`)
															);");
			array_push($dbhcms_database_sql['EXT']['news'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."ext_news_config` (`nwcg_id`, `nwcg_value`, `nwcg_type`, `nwcg_description`) VALUES 
																('enableComments', '1', 'DT_BOOLEAN', 'Enable users to write comments for each news article'),
																('enableSubscNewsletter', '1', 'DT_BOOLEAN', 'Enable users to subscribe to newsletters'),
																('jumplinkMax', '25', 'DT_INTEGER', ''),
																('jumplinkMore', '10', 'DT_INTEGER', ''),
																('languages', 'de;en;es', 'DT_LANGARRAY', 'Supported Languages'),
																('newDays', '10', 'DT_INTEGER', 'How many days the new-tag is shown'),
																('newTag', '<img alt=\"[dict_new]\" align=\"absmiddle\" src=\"[imageDirectory]other/new.gif\">', 'DT_TEXT', 'Tag for a new element'),
																('specificDomain', '1', 'DT_BOOLEAN', 'Show only news that correspond to the current domain'),
																('specificPage', '0', 'DT_BOOLEAN', 'Show only news that correspond to the current page');
															");
			array_push($dbhcms_database_sql['EXT']['news'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_news_entries` (
															  `nwen_id` int(11) NOT NULL auto_increment,
															  `nwen_domn_id` int(11) NOT NULL default '0',
															  `nwen_page_id` int(11) NOT NULL default '0',
															  `nwen_userlevel` char(1) NOT NULL default '',
															  `nwen_date` datetime NOT NULL default '0000-00-00 00:00:00',
															  PRIMARY KEY  (`nwen_id`)
															);");
			array_push($dbhcms_database_sql['EXT']['news'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_news_entriesprms` (
															  `nwep_id` int(11) NOT NULL auto_increment,
															  `nwep_type` varchar(150) NOT NULL default '',
															  `nwep_name` varchar(150) NOT NULL default '',
															  `nwep_description` text NOT NULL,
															  PRIMARY KEY  (`nwep_id`)
															);");
			array_push($dbhcms_database_sql['EXT']['news'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."ext_news_entriesprms` (`nwep_id`, `nwep_type`, `nwep_name`, `nwep_description`) VALUES 
																(1, 'DT_STRING', 'title', 'News title'),
																(2, 'DT_STRING', 'subtitle', 'News subtitle'),
																(3, 'DT_TEXT', 'teaser', 'Text for the teaser'),
																(4, 'DT_CONTENT', 'content', 'Content of the news article');
															");
			array_push($dbhcms_database_sql['EXT']['news'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_news_entriesvals` (
															  `nwev_id` int(11) NOT NULL auto_increment,
															  `nwev_nwen_id` int(11) NOT NULL default '0',
															  `nwev_name` varchar(150) NOT NULL default '',
															  `nwev_value` text NOT NULL,
															  `nwev_lang` varchar(4) NOT NULL default '',
															  PRIMARY KEY  (`nwev_id`),
															  KEY `nwev_nwen_id` (`nwev_nwen_id`),
															  KEY `nwev_name` (`nwev_name`)
															);");
			array_push($dbhcms_database_sql['EXT']['news'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_news_newsletters` (
															  `nwnl_id` int(11) NOT NULL auto_increment,
															  `nwnl_domn_id` int(11) NOT NULL default '0',
															  `nwnl_page_id` int(11) NOT NULL default '0',
															  `nwnl_name` varchar(250) NOT NULL default '',
															  `nwnl_email` varchar(250) NOT NULL default '',
															  `nwnl_active` char(1) NOT NULL default '1',
															  `nwnl_subsc_date` datetime NOT NULL default '0000-00-00 00:00:00',
															  `nwnl_unsubsc_date` datetime NOT NULL default '0000-00-00 00:00:00',
															  PRIMARY KEY  (`nwnl_id`)
															);");

#############################################################################################
#  DEINSTALLATION                                                                           #
#############################################################################################

		} else if  (DBHCMS_C_EXT_SETUP == 'DEINST') {
			
			dbhcms_f_array_push_assoc($dbhcms_database_sql['EXT'], 'news');
			$dbhcms_database_sql['EXT']['news'] = array();
			
			array_push($dbhcms_database_sql['EXT']['news'], "DROP TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_news_comments`;");
			array_push($dbhcms_database_sql['EXT']['news'], "DROP TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_news_config`;");
			array_push($dbhcms_database_sql['EXT']['news'], "DROP TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_news_entries`;");
			array_push($dbhcms_database_sql['EXT']['news'], "DROP TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_news_entriesprms`;");
			array_push($dbhcms_database_sql['EXT']['news'], "DROP TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_news_entriesvals`;");
			array_push($dbhcms_database_sql['EXT']['news'], "DROP TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_news_newsletters`;");
			
		}
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>