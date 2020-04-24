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
#  contact                                                                                  #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Contact functions to send and save messages                                              #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  CHANGES                                                                                  #
#  =============================                                                            #
#                                                                                           #
#  17.02.2007:                                                                              #
#  -----------                                                                              #
#  Added error reporting with block "contactError"                                          #
#                                                                                           #
#############################################################################################
# $Id: ext.contact.fe.php 68 2007-05-31 20:28:17Z kaisven $                                 #
#############################################################################################

#############################################################################################
#  FE IMPLEMENTATION                                                                        #
#############################################################################################

	dbhcms_p_hide_block('contactError');

	if (isset($_POST['todo'])) {
		if ($_POST['todo'] == 'contactSendMessage') {
			
			if (isset($_SESSION['DBHCMSDATA']['TEMP']['contactCaptchaNumber'])&&(isset($_POST['contactCaptcha']))&&($_SESSION['DBHCMSDATA']['TEMP']['contactCaptchaNumber'] == dbhcms_f_input_to_value('contactCaptcha', DBHCMS_C_DT_INTEGER))) {
			
				contact_p_send	(
									dbhcms_f_input_to_value('contactName', DBHCMS_C_DT_STRING),
									dbhcms_f_input_to_value('contactCompany', DBHCMS_C_DT_STRING),
									dbhcms_f_input_to_value('contactLocation', DBHCMS_C_DT_STRING),
									dbhcms_f_input_to_value('contactEmail', DBHCMS_C_DT_STRING),
									dbhcms_f_input_to_value('contactWebsite', DBHCMS_C_DT_STRING),
									dbhcms_f_input_to_value('contactText', DBHCMS_C_DT_TEXT)
								);
			
				dbhcms_p_hide_block('contactForm'); # hide contact form
			
			} else {
				# Show wrong captcha error
				dbhcms_p_add_block('contactError', array('contactMessage')); # show response message
				dbhcms_p_add_block_values('contactError', array(dbhcms_f_dict('wrongcaptcha')));
				# Hide other elements
				dbhcms_p_hide_block('contactSent'); # hide sent message
				dbhcms_p_hide_block('contactForm'); # hide contact form
			}
			
		} else {
			dbhcms_p_hide_block('contactSent'); # hide sent message
		}
	} else {
		dbhcms_p_hide_block('contactSent'); # hide sent message
	}

	$captcha = new captchaNumber( rand(10000000,99999999) );

	$_SESSION['DBHCMSDATA']['TEMP']['contactCaptchaHtml'] = $captcha->htmlNumber();
	$_SESSION['DBHCMSDATA']['TEMP']['contactCaptchaNumber'] = $captcha->getNum();

	dbhcms_p_add_string('contactCaptcha', $_SESSION['DBHCMSDATA']['TEMP']['contactCaptchaHtml']);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>