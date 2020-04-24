<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<title>
		DBHcms Extension - {str_ext_name}
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
		# $Id: body.ext.tpl 68 2007-05-31 20:28:17Z kaisven $                                       #
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

<body style="margin: 8px;">
	
	<h1>ext: {str_ext_name}</h1>
	<br>
	{str_action_result}
	<br>
	{str_ext_content}{tpl_ext_content}
	<br>
	
</body>

</html>