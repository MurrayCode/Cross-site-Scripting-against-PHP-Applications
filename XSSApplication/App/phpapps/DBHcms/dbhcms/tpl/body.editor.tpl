<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

	<title>
		DBHcms Editor
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
		# $Id: body.editor.tpl 60 2007-02-01 13:34:54Z kaisven $                                    #
		#############################################################################################

	-->
	
	<script language="JavaScript">
		var sessionLifeTime = {str_sessionLifeTime_s};
		var beLoginUrl = "{str_beLoginUrl}";
	</script>
	
	<!-- 
		Javascripts placeholders.
	-->
	{js_nr0}{js_nr1}{js_nr2}
	
	<!-- 
		Stylesheets placeholders.
	-->
	{css_nr0}{css_nr1}{css_nr2}

	<script language="javascript" type="text/javascript" src="{str_coreJavaDirectory}tinymce/tiny_mce.js"></script>
	<script language="javascript" type="text/javascript">
		
		tinyMCE.init({
			language : "{str_beLang}",
			mode : "exact",
			elements : "tinymce_content",
			theme : "advanced",
			plugins : "table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,print,contextmenu,paste,directionality,fullscreen",
			theme_advanced_buttons1 : "save,newdocument,separator,search,replace,separator,cut,copy,paste,pastetext,pasteword,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,bullist,numlist,separator,outdent,indent,separator,undo,redo,separator,insertdate,inserttime,preview,zoom,separator,link,unlink,anchor,image",
			theme_advanced_buttons2: "bold,italic,underline,strikethrough,separator,fontselect,fontsizeselect,styleselect,formatselect,forecolor,backcolor,separator,cleanup,code",
			theme_advanced_buttons3_add_before : "tablecontrols,separator",
			theme_advanced_buttons3_add : "emotions,iespell,media,advhr,separator,print,separator,ltr,rtl",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			content_css : "{str_tinymce_css}",
	    plugi2n_insertdate_dateFormat : "%Y-%m-%d",
	    plugi2n_insertdate_timeFormat : "%H:%M:%S",
			external_link_list_url : "example_link_list.js",
			external_image_list_url : "example_image_list.js",
			media_external_list_url : "example_media_list.js",
			file_browser_callback : "fileBrowserCallBack",
			paste_use_dialog : false,
			theme_advanced_resizing : true,
			theme_advanced_resize_horizontal : false,
			theme_advanced_link_targets : "_something=My somthing;_something2=My somthing2;_something3=My somthing3;",
			paste_auto_cleanup_on_paste : true,
			paste_convert_headers_to_strong : false,
			paste_strip_class_attributes : "all",
			paste_remove_spans : false,
			paste_remove_styles : false
		});
		
		function fileBrowserCallBack(field_name, url, type, win) {
			// This is where you insert your custom filebrowser logic
			alert("Example of filebrowser callback: field_name: " + field_name + ", url: " + url + ", type: " + type);
			// Insert new URL, this would normaly be done in a popup
			win.document.forms[0].elements[field_name].value = "someurl.htm";
		}
		
	</script>
	<!-- /TinyMCE -->
	<!-- Self registrering external plugin, load the plugin and tell TinyMCE where it's base URL are -->
	<script language="javascript" type="text/javascript" src="{str_coreJavaDirectory}tinymce/plugins/emotions/editor_plugin.js"></script>
	<script language="javascript" type="text/javascript">tinyMCE.setPluginBaseURL('emotions', '{str_coreJavaDirectory}tinymce/plugins/emotions');</script>
	
</head>

<body {str_tinymce_close} >
	
	<form name="tinymce_editor" method="post">
		<input type="hidden" name="updatefile" value="{str_tinymce_file}">
		<input type="hidden" name="submitform" value="{str_tinymce_form}">
		<table border="0" style="width:750px; height:650px;">
			<tr>
				<td>
					<textarea  name="tinymce_content" id="tinymce_content" style="width:750px; height:650px;">{str_tinymce_content}</textarea>
				</td>
			</tr>
		</table>	
	</form>

</body>

</html>
