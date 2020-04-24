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
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Small implementation for smilies                                                         #
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
# $Id: ext.smilies.be.php 61 2007-02-01 14:17:59Z kaisven $                                 #
#############################################################################################

	if (isset($_POST['smilies_dummy_entrytext'])) {
		$dummy_text = $_POST['smilies_dummy_entrytext'];
	} else { $dummy_text = 'Enter here some test text ... :D '; }

	dbhcms_p_add_string('ext_name', $ext_title);
	dbhcms_p_add_string('ext_content', '<form name="smilies_dummy_form" method="post"><textarea name="smilies_dummy_entrytext" cols="69" rows="7" lang="de" tabindex="5">'.$dummy_text.'</textarea><br><br>'.smilies_f_create_smilies_bar('smilies_dummy_form','smilies_dummy_entrytext').'<br><input type="Submit" value=" TEST "></form>');

#############################################################################################
#  ADMIN IMPLEMENTATION                                                                     #
#############################################################################################

	if (isset($_POST['smilies_dummy_entrytext'])) {
		$action_result = '<strong>You have entered following text:</strong><br>'.smilies_f_replace_smilies($_POST['smilies_dummy_entrytext']);
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>