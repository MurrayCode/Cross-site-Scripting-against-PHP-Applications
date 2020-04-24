<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<title>
		DBHcms Administration - {str_pageName}
	</title>

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	
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
		# $Id: body.menu.tpl 68 2007-05-31 20:28:17Z kaisven $                                      #
		#############################################################################################

	-->
	
	<script language="JavaScript">
		var sessionLifeTime = {str_sessionLifeTime_s};
		var beLoginUrl = "{str_beLoginUrl}";
	</script>
	
	<!-- 
		BE javascripts placeholders.
	-->
	{js_selectEdit}{js_dateSelector}{js_validate}
	
	<!-- 
		Standard javascripts placeholders.
	-->
	{js_nr0}{js_nr1}{js_nr2}
	
	<!-- 
		Stylesheets placeholders.
	-->
	{css_nr0}{css_nr1}{css_nr2}
	
</head>

<body style="background: #CCD5F8;">

	<table cellpadding="4" cellspacing="0" border="0" align="left">
		<tr>
			<td align="center">
			
				<div class="menu_box"><img src="{str_coreImageDirectory}frame/admin_banner.jpg" width="167" height="34" alt="" border="0"></div>
				
				<div class="menu_box">
					<div class="menu_box_caption" style="margin-bottom: 2px;">{bedict_language}</div>
					<table width="70%" height="24" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td align="center" valign="middle">
								<a style="cursor: pointer;" onclick="parent.location.href='index.php?dbhcms_pid=-1&dbhcms_changecorelang=en'"><img src="{str_coreImageDirectory}langs/uk.gif" width="18" height="12" alt="{bedict_en}" style="border-width: 1px; border-style: solid; border-color: #000000;"></a>
							</td>
							<td align="center" valign="middle">
								<a style="cursor: pointer;" onclick="parent.location.href='index.php?dbhcms_pid=-1&dbhcms_changecorelang=de'"><img src="{str_coreImageDirectory}langs/de.gif" width="18" height="12" alt="{bedict_de}" style="border-width: 1px; border-style: solid; border-color: #000000;"></a>
							</td>
							<td align="center" valign="middle">
								<a style="cursor: pointer;" onclick="parent.location.href='index.php?dbhcms_pid=-1&dbhcms_changecorelang=es'"><img src="{str_coreImageDirectory}langs/es.gif" width="18" height="12" alt="{bedict_es}" style="border-width: 1px; border-style: solid; border-color: #000000;"></a>
							</td>
						</tr>
					</table>
				</div>
				{tpl_nr1}
			</td>
		</tr>
	</table>
</body>

</html>
