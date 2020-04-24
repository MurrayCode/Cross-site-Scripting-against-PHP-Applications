<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<title>
		DBHcms Standard Page
	</title>

	<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
	
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
		# $Id: body.standard.tpl 68 2007-05-31 20:28:17Z kaisven $                                  #
		#############################################################################################

	-->
			
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

<body style="margin: 8px;">
	
	<div id="login_wrapper">
		<div id="login_banner">
			<div id="login_title"><h1 style="color: #872626; font-size: 18pt;">ERROR !</h1></div>
		</div>
		<div id="login_form">
			<table width="400" cellpadding="8" cellspacing="0" border="0">
				<tr>
					<td bgcolor="#FFFFFF" align="left">
						<br />
						<div style="color: #872626; font-weight: bold;">
							There is no index page defined for this domain !
						</div>
						<br />
						This is the standard page of the DBHcms. Please change your settings for this domain under the administration panel to define a index page for this domain.<br />
						<br />
					</td>
				</tr>
			</table>
		</div>
		<div align="center">
			<a target="_blank" href="http://www.drbenhur.com/" style="font-size: 10px; color:#444DFE;">powered by DBHcms</a>
		</div>
	</div>

</body>

</html>
