<?php

#############################################################################################
#                                                                                           #
#  COPYRIGHT NOTICE                                                                         #
#  =============================                                                            #
#                                                                                           #
#  (C) 2005-2007 Kai-Sven Bunk (kaisven@drbenhur.com)                                       #
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
#  This copyright notice MUST APPEAR in all copies of the script!                           #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  FILENAME                                                                                 #
#  =============================                                                            #
#  config.php                                                                               #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Basic configuration to initialize the DBHcms core                                        #
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
# $Id: config.php 57 2007-02-01 12:17:09Z kaisven $                                         #
#############################################################################################

#############################################################################################
#  SYSTEM SETTINGS                                                                          #
#############################################################################################

	# Core directory. Set this directory to the DBHcms installation (folder which 
	# contains the file init.php) relative to the index file.
	$dbhcms_core_dir = 'dbhcms/'; 

	# If dbhcms is not jet installed, set this to false to startup the installation
	# procedure. if the value is true, the dbhcms asumes that everithing is properly
	# installed and configured and skips the installation procedure
	$dbhcms_installed = true; 

#############################################################################################
#  DATABASE SETTINGS                                                                        #
#############################################################################################

	# Name or IP adress of the host in which the MySql database is
	# Examples: localhost or mysql.drbenhur.com or 127.0.0.1
	$dbhcms_db_server = 'localhost'; 

	# Name of the MySql database
	$dbhcms_db_database = 'DBcontent'; 

	# Username to access the MySql database
	$dbhcms_db_user = 'root'; 

	# Password for the user to access the MySql database
	$dbhcms_db_pass = 'hacklab2019'; 

	# Table prefix you wish to use for the DBHcms. This way you can install more than 
	# one DBHcms instances in one database. Just make shure you use for each instance
	# a diferent prefix. If you wish you can also leave it blank.
	$dbhcms_db_prefix = 'dbhcms_'; 

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>