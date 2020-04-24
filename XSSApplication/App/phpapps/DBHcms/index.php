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
#  index.php                                                                                #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Index document. All requests to the DBHcms come up here. This file should be named as    #
#  the DirectoryIndex of your webserver. This file includes the basic configuration and     #
#  initializes the DBHcms.                                                                  #
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
# $Id: index.php 72 2007-10-15 10:15:23Z kaisven $                                          #
#############################################################################################

#############################################################################################
#  INITIALIZATION OF THE THE DBHCMS CORE                                                    #
#############################################################################################

	function dbhcms_init($core) {
		$init  = $core.'init.php';
		$page  = $core.'page.php';
		if ((is_file($init))&&(is_file($page))) {
			require_once($init);
			require_once($page);
		} else {
			die('<div style="color: #872626; font-weight: bold;">
						FATAL ERROR - Could not find the initialzation files. 
						Please check the "$dbhcms_core_dir" parameter in the "config.php" and make 
						shure the directory is correct.
					</div>');
		}
	}

#############################################################################################
#  LOAD CONFIGURATION FILE                                                                  #
#############################################################################################

	require_once('config.php');

#############################################################################################
#  SECURITY                                                                                 #
#############################################################################################

	define('DBHCMS', '1.1.4');

#############################################################################################
#  INITIALIZE THE DBHCMS CORE                                                               #
#############################################################################################
	
	dbhcms_init($GLOBALS['dbhcms_core_dir']);
	
### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>
