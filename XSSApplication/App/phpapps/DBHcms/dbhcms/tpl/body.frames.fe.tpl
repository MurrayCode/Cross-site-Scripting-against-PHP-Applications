
	<!--
	
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
		# $Id: body.frames.fe.tpl 60 2007-02-01 13:34:54Z kaisven $                                 #
		#############################################################################################

	-->
	
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
		<head>
			<title>DBHcms Administration - {str_pageName}</title>
			<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
		</head>
		<frameset rows="80,*" border="0" frameborder="0" framespacing="0" >
		    <frame name="treeview" src="{str_dbhcms_femenu_url}" scrolling="no" frameborder="0" noresize>
		    <frame name="basefrm" src="{str_dbhcms_fe_url}" scrolling="auto" frameborder="1">
		</frameset>
		<body>
		</body>
	</html>
	
	