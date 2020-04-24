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
#  smilies                                                                                  #
#                                                                                           #
#  FILENAME                                                                                 #
#  =============================                                                            #
#  ext.smilies.inst.php                                                                     #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Small implementation for smilies                                                         #
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
			
			dbhcms_f_array_push_assoc($dbhcms_database_sql['EXT'], 'smilies');
			$dbhcms_database_sql['EXT']['smilies'] = array();
			
			array_push($dbhcms_database_sql['EXT']['smilies'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_smilies` (
																  `smilie_id` int(11) NOT NULL auto_increment,
																  `smilie_kz` varchar(12) NOT NULL default '',
																  `smilie_image` varchar(250) NOT NULL default '',
																  PRIMARY KEY  (`smilie_id`)
																);");
			array_push($dbhcms_database_sql['EXT']['smilies'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."ext_smilies` (`smilie_id`, `smilie_kz`, `smilie_image`) VALUES 
																	(1, ':)', 'smile.gif'),
																	(2, ':-)', 'smile.gif'),
																	(3, ':(', 'sad.gif'),
																	(4, ':-(', 'sad.gif'),
																	(5, ';-)', 'wink.gif'),
																	(6, ';)', 'wink.gif'),
																	(7, ':D', 'biggrin.gif'),
																	(8, ':-D', 'biggrin.gif'),
																	(9, ':P', 'tongue.gif'),
																	(10, ':-P', 'tongue.gif'),
																	(11, ':p', 'tongue.gif'),
																	(12, ':-p', 'tongue.gif'),
																	(13, ':o', 'shocked.gif'),
																	(14, ':-o', 'shocked.gif'),
																	(15, ':''(', 'cry.gif'),
																	(16, ':''-(', 'cry.gif'),
																	(17, ':S', 'sick.gif'),
																	(18, ':-S', 'sick.gif'),
																	(19, ':s', 'sick.gif'),
																	(20, ':-s', 'sick.gif'),
																	(21, '8o', 'dizzy.gif'),
																	(22, '8-o', 'dizzy.gif'),
																	(23, ':@', 'mad.gif'),
																	(24, ':-@', 'mad.gif'),
																	(26, ':-/', 'nono.gif'),
																	(27, ':|', 'uhh.gif'),
																	(28, ':-|', 'uhh.gif'),
																	(29, 'x)', 'wacko.gif'),
																	(30, 'x-)', 'wacko.gif'),
																	(31, 'X)', 'wacko.gif'),
																	(32, 'X-)', 'wacko.gif');");

#############################################################################################
#  DEINSTALLATION                                                                           #
#############################################################################################

		} else if  (DBHCMS_C_EXT_SETUP == 'DEINST') {
			
			dbhcms_f_array_push_assoc($dbhcms_database_sql['EXT'], 'smilies');
			$dbhcms_database_sql['EXT']['smilies'] = array();
			
			array_push($dbhcms_database_sql['EXT']['smilies'], "DROP TABLE `".DBHCMS_C_INST_DB_PREFIX."ext_smilies`;");
			
		}
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>