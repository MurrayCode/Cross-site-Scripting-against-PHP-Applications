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
#  photoalbum                                                                               #
#                                                                                           #
#  FILENAME                                                                                 #
#  =============================                                                            #
#  ext.photoalbum.inst.php                                                                  #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  A photoalbum with userlevel, picture comments, album rating and picture rating           #
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
		
			dbhcms_f_array_push_assoc($dbhcms_database_sql['EXT'], 'photoalbum');
			$dbhcms_database_sql['EXT']['photoalbum'] = array();
			
			array_push($dbhcms_database_sql['EXT']['photoalbum'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_photoalbum_albs` (
																	  `paal_id` int(11) NOT NULL auto_increment,
																	  `paal_domn_id` int(11) NOT NULL default '0',
																	  `paal_page_id` int(11) NOT NULL default '0',
																	  `paal_folder` varchar(250) NOT NULL default '',
																	  `paal_thumbnail_img` varchar(250) NOT NULL default '',
																	  `paal_userlevel` char(1) NOT NULL default '',
																	  `paal_date` date NOT NULL default '0000-00-00',
																	  `paal_rate_1` int(11) NOT NULL default '0',
																	  `paal_rate_2` int(11) NOT NULL default '0',
																	  `paal_rate_3` int(11) NOT NULL default '0',
																	  `paal_rate_4` int(11) NOT NULL default '0',
																	  `paal_rate_5` int(11) NOT NULL default '0',
																	  PRIMARY KEY  (`paal_id`)
																	);");
			array_push($dbhcms_database_sql['EXT']['photoalbum'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_photoalbum_albsprms` (
																	  `paap_id` int(11) NOT NULL auto_increment,
																	  `paap_type` varchar(150) NOT NULL,
																	  `paap_name` varchar(150) NOT NULL default '',
																	  `paap_description` text NOT NULL,
																	  PRIMARY KEY  (`paap_id`)
																	);");
			array_push($dbhcms_database_sql['EXT']['photoalbum'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."ext_photoalbum_albsprms` (`paap_id`, `paap_type`, `paap_name`, `paap_description`) VALUES 
																		(1, 'DT_STRING', 'title', 'The title of the album'),
																		(2, 'DT_STRING', 'presence', 'Who was there?'),
																		(3, 'DT_STRING', 'activities', 'What was done?'),
																		(4, 'DT_STRING', 'location', 'Where was it?');
																	");
			array_push($dbhcms_database_sql['EXT']['photoalbum'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_photoalbum_albsvals` (
																	  `paav_id` int(11) NOT NULL auto_increment,
																	  `paav_paal_id` int(11) NOT NULL default '0',
																	  `paav_name` varchar(150) NOT NULL default '',
																	  `paav_value` text NOT NULL,
																	  `paav_lang` varchar(4) NOT NULL default '',
																	  PRIMARY KEY  (`paav_id`),
																	  KEY `paav_paal_id` (`paav_paal_id`),
																	  KEY `paav_name` (`paav_name`)
																	);");
			array_push($dbhcms_database_sql['EXT']['photoalbum'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_photoalbum_config` (
																	  `pacg_id` varchar(150) NOT NULL default '',
																	  `pacg_value` text NOT NULL,
																	  `pacg_type` varchar(150) NOT NULL,
																	  `pacg_description` text NOT NULL,
																	  PRIMARY KEY  (`pacg_id`)
																	);");
			array_push($dbhcms_database_sql['EXT']['photoalbum'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."ext_photoalbum_config` VALUES 
																		('enableComments', '1', 'DT_BOOLEAN', 'Allow users to write comments for each picture'),
																		('enableRating', '1', 'DT_BOOLEAN', 'Enable users to rate each picture'),
																		('formatImages', 'JPG;JPEG;PNG;GIF', 'DT_STRARRAY', 'Formats of supported image files'),
																		('formatVideos', 'WMV;MPG;MPEG', 'DT_STRARRAY', 'Formats of video image files'),
																		('jumplinkMax', '25', 'DT_INTEGER', 'Maximum number of pages'),
																		('jumplinkMore', '4', 'DT_INTEGER', 'Number of images in each page'),
																		('languages', 'en;es;de', 'DT_LANGARRAY', 'Supported Languages'),
																		('newDays', '10', 'DT_INTEGER', 'How many days the new-tag is shown'),
																		('newTag', '<img alt=\"[dict_new]\" align=\"absmiddle\" src=\"[imageDirectory]other/new.gif\">', 'DT_TEXT', 'Tag for a new element'),
																		('specificDomain', '1', 'DT_BOOLEAN', 'Show only albums that correspond to the current domain'),
																		('specificPage', '0', 'DT_BOOLEAN', 'Show only albums that correspond to the current page'),
																		('videoclipThumbnail', 'images/other/videoclip.gif', 'DT_FILE', 'Thumbnail to show if the object is a video'),
																		('viewColCount', '2', 'DT_INTEGER', 'Number of columns of images in each page'),
																		('wordFilter', 'poker;viagra;gambling', 'DT_STRARRAY', 'Comments with these words will not be inserted');
																	");
			array_push($dbhcms_database_sql['EXT']['photoalbum'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_photoalbum_piccomments` (
																	  `papc_id` int(11) NOT NULL auto_increment,
																	  `papc_paal_id` int(11) NOT NULL default '0',
																	  `papc_user_id` int(11) default '0',
																	  `papc_filename` varchar(250) NOT NULL default '',
																	  `papc_username` varchar(250) default NULL,
																	  `papc_sex` varchar(30) default NULL,
																	  `papc_email` varchar(250) default NULL,
																	  `papc_homepage` varchar(250) default NULL,
																	  `papc_location` varchar(250) default NULL,
																	  `papc_entrytext` text,
																	  `papc_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
																	  PRIMARY KEY  (`papc_id`)
																	);");
			array_push($dbhcms_database_sql['EXT']['photoalbum'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_photoalbum_pics` (
																	  `papi_id` int(11) NOT NULL auto_increment,
																	  `papi_paal_id` int(11) NOT NULL default '0',
																	  `papi_filename` varchar(250) NOT NULL default '',
																	  `papi_userlevel` char(1) NOT NULL default '',
																	  `papi_rate_1` int(11) NOT NULL default '0',
																	  `papi_rate_2` int(11) NOT NULL default '0',
																	  `papi_rate_3` int(11) NOT NULL default '0',
																	  `papi_rate_4` int(11) NOT NULL default '0',
																	  `papi_rate_5` int(11) NOT NULL default '0',
																	  PRIMARY KEY  (`papi_id`),
																	  KEY `papi_paal_id` (`papi_paal_id`),
																	  KEY `papi_filename` (`papi_filename`)
																	);");

#############################################################################################
#  DEINSTALLATION                                                                           #
#############################################################################################

		} else if  (DBHCMS_C_EXT_SETUP == 'DEINST') {
			
			dbhcms_f_array_push_assoc($dbhcms_database_sql['EXT'], 'photoalbum');
			$dbhcms_database_sql['EXT']['photoalbum'] = array();
			
			array_push($dbhcms_database_sql['EXT']['photoalbum'], "DROP TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_photoalbum_albs`;");
			array_push($dbhcms_database_sql['EXT']['photoalbum'], "DROP TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_photoalbum_albsprms`;");
			array_push($dbhcms_database_sql['EXT']['photoalbum'], "DROP TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_photoalbum_albsvals`;");
			array_push($dbhcms_database_sql['EXT']['photoalbum'], "DROP TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_photoalbum_config`;");
			array_push($dbhcms_database_sql['EXT']['photoalbum'], "DROP TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_photoalbum_piccomments`;");
			array_push($dbhcms_database_sql['EXT']['photoalbum'], "DROP TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_photoalbum_pics`;");
			
		}
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>