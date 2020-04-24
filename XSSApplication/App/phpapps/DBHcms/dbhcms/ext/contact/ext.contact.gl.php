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
#  28.10.2005:                                                                              #
#  -----------                                                                              #
#  File created                                                                             #
#                                                                                           #
#############################################################################################
# $Id: ext.contact.gl.php 71 2007-10-15 10:07:42Z kaisven $                                 #
#############################################################################################

	define('DBHCMS_C_EXT_CONTACT', 'contact');

#############################################################################################
#  SETTINGS                                                                                 #
#############################################################################################

	$ext_name 		= DBHCMS_C_EXT_CONTACT;
	
	$ext_title 		= 'Contact';
	$ext_descr 		= 'A small contact form that sends e-mails and saves messages.';
	$ext_inmenu		= true;
	$ext_version	= '1.1';
	$ext_icon 		= 'x-office-address-book';

	dbhcms_p_configure_extension($ext_name, $ext_title, $ext_descr, $ext_inmenu, $ext_version, $ext_icon);

#############################################################################################
#  LOAD CONFIGURATION                                                                       #
#############################################################################################

	if (in_array(DBHCMS_C_EXT_CONTACT, $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['availableExtensions'])) {
	
		$result = mysql_query("SELECT cocg_id, cocg_value, cocg_type FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_contact_config");
		while ($row = mysql_fetch_assoc($result)) {
			dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_CONTACT], $row['cocg_id']);
			$GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_CONTACT][$row['cocg_id']] = dbhcms_f_dbvalue_to_value(dbhcms_f_str_replace_all_vars(strval($row['cocg_value'])), $row['cocg_type']);
		}
	
	}

#############################################################################################
#  GLOBAL IMPLEMENTATION                                                                    #
#############################################################################################

	function contact_f_get_config_param($aparam) {
		if (isset($GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_CONTACT][$aparam])) {
			return $GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_CONTACT][$aparam];
		} else {
			return false;
		}
	}

	function contact_p_insert_contact($aname, $acompany, $alocation, $aemail, $awebsite, $atext) {
		if (trim($aname) == '') { $aname = 'Anonymous'; }
		
		$banned = false;
		foreach (contact_f_get_config_param('wordFilter') as $word) {
			if (substr_count($atext, $word) > 0) {
				$banned = true;
				break;
			}
		}
		
		if (!$banned) {
		
			mysql_query("	INSERT INTO 
								".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_contact_messages 
									( 
										comg_name,
										comg_company,
										comg_location,
										comg_email,
										comg_website,
										comg_text,
										comg_date
									) 
							VALUES 
									( 
										'".$aname."',
										'".$acompany."',
										'".$alocation."',
										'".$aemail."',
										'".$awebsite."',
										'".$atext."',
										now()
									)
						");
		}
	}

	function contact_p_send_contact($aname, $acompany, $alocation, $aemail, $awebsite, $atext) {
		if (trim($aname) == '') { 
			$aname = 'Anonymous'; 
		}
		# get settings
		$mailto	 = contact_f_get_config_param('mailTo');
		$mail_cc = contact_f_get_config_param('mailCc');
		$subject = contact_f_get_config_param('mailSubject');
		# get text for the mail
		$mts = contact_f_get_config_param('mailText');
		# replace values
		$mts	 = str_replace("[contactName]", 	$aname, $mts);
		$mts	 = str_replace("[contactCompany]", 	$acompany, $mts);
		$mts	 = str_replace("[contactLocation]", $alocation, $mts);
		$mts	 = str_replace("[contactEmail]", 	$aemail, $mts);
		$mts	 = str_replace("[contactWebsite]", 	$awebsite, $mts);
		$mts	 = str_replace("[contactText]", 	$atext, $mts);
		$mts	 = str_replace("[contactDate]", 	date($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['dateFormatOutput']), $mts);
		$mts	 = str_replace("[contactTime]", 	date($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['timeFormatOutput']), $mts);
		# replace chars for html
		$mts = str_replace("<br>", "\n", $mts);
		$mts = strip_tags($mts);
		$mts = stripslashes($mts);
		$header  = "Content-Type: text/plain; charset=\"iso-8859-1\" \n";
		$header .= "Content-Transfer-Encoding: 8bit \n";
		$header .= "From: ".$aemail." \n";
		if ($mail_cc != "") { 
			$header .= "Cc: $mail_cc \n"; 
		}
	    mail($mailto, $subject, $mts, $header);
	}

	function contact_p_reply($aname, $acompany, $alocation, $aemail, $awebsite, $atext) {
		if (trim($aemail) != '') {
			$mailto	 = $aemail;
			$subject = contact_f_get_config_param('replySubject');
			# get text for the mail
			$mts  	 = contact_f_get_config_param('replyText');
			# replace values
			$mts	 = str_replace("[contactName]", 	$aname, $mts);
			$mts	 = str_replace("[contactCompany]", 	$acompany, $mts);
			$mts	 = str_replace("[contactLocation]", $alocation, $mts);
			$mts	 = str_replace("[contactEmail]", 	$aemail, $mts);
			$mts	 = str_replace("[contactWebsite]", 	$awebsite, $mts);
			$mts	 = str_replace("[contactText]", 	$atext, $mts);
			$mts	 = str_replace("[contactDate]", 	date($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['dateFormatOutput']), $mts);
			$mts	 = str_replace("[contactTime]", 	date($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['timeFormatOutput']), $mts);
			# replace chars for html
			$mts 	 = str_replace("<br>", "\n", $mts);
			$mts 	 = strip_tags($mts);
			$mts 	 = stripslashes($mts);
			$header  = "Content-Type: text/plain; charset=\"iso-8859-1\" \n";
			$header .= "Content-Transfer-Encoding: 8bit \n";
			$header .= "From: ".contact_f_get_config_param('mailTo')." \n";
		    mail($mailto, $subject, $mts, $header);
		}
	}

	function contact_p_send($aname, $acompany, $alocation, $aemail, $awebsite, $atext) {
		if (contact_f_get_config_param('sendMail')) {
			contact_p_send_contact($aname, $acompany, $alocation, $aemail, $awebsite, $atext);
		}
		if (contact_f_get_config_param('saveToDb')) {
			contact_p_insert_contact($aname, $acompany, $alocation, $aemail, $awebsite, $atext);
		}
		if (contact_f_get_config_param('reply')) {
			contact_p_reply($aname, $acompany, $alocation, $aemail, $awebsite, $atext);
		}
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>
