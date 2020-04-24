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
# $Id: mod.editor.php 60 2007-02-01 13:34:54Z kaisven $                                     #
#############################################################################################

#############################################################################################
#  SECURITY                                                                                 #
#############################################################################################

	if ((realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))||(!defined('DBHCMS'))||(!dbhcms_f_superuser_auth())) {
		die('	<div style="color: #872626; font-weight: bold;">
						DBHCMS FATAL ERROR - Access denied!
					</div>');
	}

#############################################################################################
#	MODULE MOD.EDITOR.PHP                                                                     #
#############################################################################################

	if (isset($_POST['updatefile'])) {
		
		$contentfile = fopen($_POST['updatefile'], "w");
		fwrite($contentfile, $_POST['tinymce_content']);
		fclose($contentfile);
		
		if (trim($_POST['submitform']) != "") {
			dbhcms_p_add_string('tinymce_close', 'onload="window.close(); opener.document.'.$_POST['submitform'].'.submit(); "');
		} else {
			dbhcms_p_add_string('tinymce_close', 'onload="window.close(); "');
		}
		
	} else {
		
		dbhcms_p_add_string('tinymce_close', 'onload="tinyMCE.execInstanceCommand(\'mce_editor_0\',\'mceFullScreen\');"');
		
	}

	dbhcms_p_add_string('tinymce_css', $_GET['style']);

	if (filesize($_GET['file']) > 0) {
		$contentfile = fopen($_GET['file'], "r");
		$content = fread($contentfile, filesize($_GET['file']));
		fclose($contentfile);
	} else $content = '';

	dbhcms_p_add_string('tinymce_form', $_GET['form']);

	dbhcms_p_add_string('tinymce_content', htmlspecialchars($content));
	dbhcms_p_add_string('tinymce_file', $_GET['file']);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>