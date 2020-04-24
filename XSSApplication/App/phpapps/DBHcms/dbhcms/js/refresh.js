	
	/*
	
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
	# $Id: refresh.js 61 2007-02-01 14:17:59Z kaisven $                                         #
	#############################################################################################
	
	*/
	
	var busyloadTime = 0;
	
	function sessionRefreshed()	{	//
		var date = new Date();
		busyloadTime = Math.floor(date.getTime()/1000);
	}
	
	function openRefreshWindow()	{
		var date = new Date();
		var theTime = Math.floor(date.getTime()/1000);
		if (theTime > busyloadTime+sessionLifeTime) {
			parent.location = beLoginUrl;
		} else {
			window.open("index.php?dbhcms_pid=-7","relogin","height=50,width=50,status=0,menubar=0,location=0");
			sessionRefreshed();
		}
	}
	
	function checkSession()	{
		var date = new Date();
		var theTime = Math.floor(date.getTime()/1000);
		if (theTime > busyloadTime+sessionLifeTime) {
			parent.location = beLoginUrl;
		} else {
			if (theTime > busyloadTime+sessionLifeTime-32) {
				var diff = busyloadTime+sessionLifeTime-theTime;
				if (confirm("Your session will expire in " + diff + " seconds. Do you want to refresh now?"))	{
					openRefreshWindow();
				}
			}
			window.setTimeout("checkSession();",2*1000);
		}
	}

	sessionRefreshed();
	checkSession();