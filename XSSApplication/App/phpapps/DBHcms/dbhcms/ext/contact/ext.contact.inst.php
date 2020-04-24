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
#  contact                                                                                  #
#                                                                                           #
#  FILENAME                                                                                 #
#  =============================                                                            #
#  ext.contact.inst.php                                                                     #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Contact functions to send and save messages.                                             #
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
			
			dbhcms_f_array_push_assoc($dbhcms_database_sql['EXT'], 'contact');
			$dbhcms_database_sql['EXT']['contact'] = array();
			
			array_push($dbhcms_database_sql['EXT']['contact'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_contact_config` (
																  `cocg_id` varchar(150) NOT NULL default '',
																  `cocg_value` text NOT NULL,
																  `cocg_type` varchar(150) NOT NULL,
																  `cocg_description` text,
																  PRIMARY KEY  (`cocg_id`)
																);");
			array_push($dbhcms_database_sql['EXT']['contact'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."ext_contact_config` (`cocg_id`, `cocg_value`, `cocg_type`, `cocg_description`) VALUES 
																	('mailCc', 'archive@drbenhur.com', 'DT_STRING', 'Copy E-Mail Adress.'),
																	('mailSubject', '[domainHostName] - Contact message', 'DT_STRING', 'Subject of the contact message e-mail.'),
																	('mailText', 'Contact: \r\n=========================\r\nName : [contactName] \r\nCompany : [contactCompany] \r\nLocation : [contactLocation] \r\nE-Mail : [contactEmail] \r\nWebsite : [contactWebsite] \r\n\r\nContact Text: \r\n=========================\r\n[contactText] \r\n=========================\r\n:: [contactDate] [contactTime] ::', 'DT_TEXT', 'Content of the e-mail with the contact information.'),
																	('mailTo', 'contact@drbenhur.com', 'DT_STRING', 'Target E-Mail Adress.'),
																	('reply', '1', 'DT_BOOLEAN', 'Send reply e-mail automatically?'),
																	('replySubject', '[domainHostName] - Contact confirmation', 'DT_STRING', 'Subject of the reply e-mail.'),
																	('replyText', 'Hello [contactName]! \r\n\r\nI recieved your contact message you send me at [domainHostName]!\r\n\r\nThank you!', 'DT_TEXT', 'Content of the reply e-mail.'),
																	('saveToDb', '1', 'DT_BOOLEAN', 'Save the contact message to the database?'),
																	('sendMail', '1', 'DT_BOOLEAN', 'Send the contact message as an e-mail?'),
																	('wordFilter', 'poker;viagra;gambling', 'DT_STRARRAY', 'Messages with these words will not be sent.');
																");
			array_push($dbhcms_database_sql['EXT']['contact'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_contact_messages` (
																  `comg_id` int(11) NOT NULL auto_increment,
																  `comg_name` varchar(250) default NULL,
																  `comg_company` varchar(250) default NULL,
																  `comg_location` varchar(250) default NULL,
																  `comg_email` varchar(250) default NULL,
																  `comg_website` varchar(250) default NULL,
																  `comg_text` text,
																  `comg_read` char(1) NOT NULL default 'N',
																  `comg_date` datetime NOT NULL default '0000-00-00 00:00:00',
																  PRIMARY KEY  (`comg_id`)
																);");

#############################################################################################
#  DEINSTALLATION                                                                           #
#############################################################################################

		} else if  (DBHCMS_C_EXT_SETUP == 'DEINST') {
			
			dbhcms_f_array_push_assoc($dbhcms_database_sql['EXT'], 'contact');
			$dbhcms_database_sql['EXT']['contact'] = array();
			
			array_push($dbhcms_database_sql['EXT']['contact'], "DROP TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_contact_config`;");
			array_push($dbhcms_database_sql['EXT']['contact'], "DROP TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_contact_messages`;");
			
		}
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>