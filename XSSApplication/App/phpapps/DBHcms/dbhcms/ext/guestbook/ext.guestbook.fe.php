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
#  guestbook                                                                                #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  A guestbook                                                                              #
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
# $Id: ext.guestbook.fe.php 68 2007-05-31 20:28:17Z kaisven $                               #
#############################################################################################

#############################################################################################
#  FE IMPLEMENTATION                                                                        #
#############################################################################################

	dbhcms_p_hide_block('guestbookError');

	if (isset($_POST['todo'])) {
		if ($_POST['todo'] == 'guestbookSignBook') {
			# Add guestbook entry
			if ((isset($_SESSION['DBHCMSDATA']['TEMP']['gbCaptchaNumber']))&&(isset($_POST['guestbookCaptcha']))&&($_SESSION['DBHCMSDATA']['TEMP']['gbCaptchaNumber'] == dbhcms_f_input_to_value('guestbookCaptcha', DBHCMS_C_DT_INTEGER))) {
				if (isset($_POST['guestbookSex'])) {
					$guestbook_sex = dbhcms_f_input_to_dbvalue('guestbookSex', DBHCMS_C_DT_SEX);
				} else {
					$guestbook_sex = DBHCMS_C_ST_NONE;
				}
				guestbook_p_add_entry	(
											dbhcms_f_input_to_dbvalue('guestbookName', DBHCMS_C_DT_STRING),
											$guestbook_sex,
											dbhcms_f_input_to_dbvalue('guestbookCompany', DBHCMS_C_DT_STRING),
											dbhcms_f_input_to_dbvalue('guestbookLocation', DBHCMS_C_DT_STRING),
											dbhcms_f_input_to_dbvalue('guestbookEmail', DBHCMS_C_DT_STRING),
											dbhcms_f_input_to_dbvalue('guestbookWebsite', DBHCMS_C_DT_STRING),
											dbhcms_f_input_to_dbvalue('guestbookText', DBHCMS_C_DT_TEXT)
										);
			} else {
				# Show wrong captcha error
				dbhcms_p_add_block('guestbookError', array('guestbookMessage')); # show response message
				dbhcms_p_add_block_values('guestbookError', array(dbhcms_f_dict('wrongcaptcha')));
			}
		}
	} else if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['guestbookDeleteEntry'])) {
		# Delete guestbook entry
		if (dbhcms_f_superuser_auth() == true) {
			if (mysql_query("DELETE FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_guestbook_entries WHERE gben_id = ".$GLOBALS['DBHCMS']['TEMP']['PARAMS']['guestbookDeleteEntry'])) {
				if (($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['cacheEnabled'])&&($GLOBALS['DBHCMS']['PID'] > 0)) {
					if ($GLOBALS['DBHCMS']['CONFIG']['CORE']['debug']) {
						echo "Message: Cache deleted by guestbook entry deletion in ext.guestbook.fe.php";
					}
					dbhcms_p_del_cache($GLOBALS['DBHCMS']['PID']);
				}
			} else {
				if ($GLOBALS['DBHCMS']['CONFIG']['CORE']['debug']) {
					echo "SQL Error: ".mysql_error();
				}
			}
		}
	}

	$captcha = new captchaNumber( rand(10000000,99999999) );
	
	$_SESSION['DBHCMSDATA']['TEMP']['gbCaptchaHtml'] = $captcha->htmlNumber();
	$_SESSION['DBHCMSDATA']['TEMP']['gbCaptchaNumber'] = $captcha->getNum();

	dbhcms_p_add_string('guestbookCaptcha', $_SESSION['DBHCMSDATA']['TEMP']['gbCaptchaHtml']);
	
	$guestbook_restrict = '';
	if (guestbook_f_get_config_param('specificDomain')) {
		$guestbook_restrict = ' WHERE gben_domn_id = '.$GLOBALS['DBHCMS']['DID'];
	}
	if (guestbook_f_get_config_param('specificPage')) {
		if (guestbook_f_get_config_param('specificDomain')) {
			$guestbook_restrict .= ' AND gben_page_id = '.$GLOBALS['DBHCMS']['PID'];
		} else {
			$guestbook_restrict = ' WHERE gben_page_id = '.$GLOBALS['DBHCMS']['PID'];
		}
	}

	$guestbook_query = " SELECT * from ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_guestbook_entries ".$guestbook_restrict." ORDER BY gben_date DESC ";

	$gb_jumplinkmax = guestbook_f_get_config_param('jumplinkMax');
	$gb_more = guestbook_f_get_config_param('jumplinkMore');

	if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['gbFrom'])) {
		$guestbook_from = $GLOBALS['DBHCMS']['TEMP']['PARAMS']['gbFrom']; 
	} else { 
		$guestbook_from = 0;
	}

	$gb_jumplinktotal = mysql_num_rows(mysql_query($guestbook_query));
	if ($gb_jumplinktotal > ($gb_more * $gb_jumplinkmax)) {
		$gb_more = ceil($gb_jumplinktotal / $gb_jumplinkmax);
	}
	$query = $guestbook_query." LIMIT ".$guestbook_from." , ".$gb_more;

	$show = mysql_query($query);
	$gb_jumplink = "";

	if ($guestbook_from >= $gb_more) {
	    $gb_jumplink .= "[<a class=\"jumplink\" href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('gbFrom' => ($guestbook_from - $gb_more))) . "\">«</a>]";
	}
	for ($i = 1; ($i * $gb_more) < $gb_jumplinktotal; $i++) {
		$j = $i - 1;
		if (($j * $gb_more) != $guestbook_from) {
	    	$gb_jumplink .= " [<a class=\"jumplink\" href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('gbFrom' => ($j * $gb_more)))  . "\">" . $i . '</a>] ';
		} else {
	    	$gb_jumplink .= ' <strong>' . $i . '</strong> ';
		}
	}
	$j = $i - 1;
	if (($j * $gb_more) < $gb_jumplinktotal) {
		if (($j * $gb_more) != $guestbook_from) {
		    $gb_jumplink .= " [<a class=\"jumplink\" href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('gbFrom' => ($j * $gb_more))) . "\">" . $i . '</a>] ';
		} else {
	    	$gb_jumplink .= ' <strong>' . $i . '</strong> ';
		}
	}
	if ($gb_jumplinktotal >= ($guestbook_from + $gb_more)) {
	    $gb_jumplink .= "[<a class=\"jumplink\" href=\"".dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('gbFrom' => ($guestbook_from + $gb_more))) . "\">»</a>]";
	}

	dbhcms_p_add_string('guestbookJumplinks', $gb_jumplink);

#############################################################################################
#  GUESTBOOK MESSAGES                                                                       #
#############################################################################################

	$gb_entries = '';

	dbhcms_p_add_block('guestbookEntry', array	(	'guestbookName',
													'guestbookCompany',
													'guestbookLocation',
													'guestbookEmail',
													'guestbookWebsite',
													'guestbookText',
													'guestbookDate',
													'guestbookNewTag',
													'guestbookDelEntry',
													'guestbookSex',
													'guestbookSexIcon',
													'guestbookEmailIcon',
													'guestbookWebsiteIcon',
													'guestbookEntryTitle'
												));

	if (defined('DBHCMS_C_EXT_SMILIES')) {
		dbhcms_p_add_string('guestbookSmiliesBar', smilies_f_create_smilies_bar('guestbookSignForm', 'guestbookText'));
	}

	while ($row = mysql_fetch_array($show)) {
		
		if (dbhcms_f_superuser_auth() == true) {
			$delete_btn = '<a onclick="return confirm(\''.dbhcms_f_dict('dbhcms_msg_askdeleteitem').'\');" href="'.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('guestbookDeleteEntry' => $row['gben_id'])).'">'.dbhcms_f_get_icon('edit-delete', dbhcms_f_dict('delete'), 1)."</a>&nbsp;";
		} else { $delete_btn = ''; }
		
		$differenz = strtotime($row['gben_date']) - mktime(date('h'),date('i'),date('s'),date('m'),date('d'),date('Y'));
		$tage = $differenz/(60*60*24);
		
		if (abs($tage) < guestbook_f_get_config_param('newDays')) {
			$guestbook_newtag = guestbook_f_get_config_param('newTag');
		} else { $guestbook_newtag = ''; }
		
		if (defined('DBHCMS_C_EXT_SMILIES')) {
			$guestbook_entry_text = str_replace("\n", "<br>", smilies_f_replace_smilies(htmlspecialchars($row['gben_text'])));
		} else { $guestbook_entry_text = str_replace("\n", "<br>", htmlspecialchars($row['gben_text'])); }
		
		if (trim($row['gben_sex']) == DBHCMS_C_ST_MALE) {
			$guestbook_sex_icon = dbhcms_f_get_icon('male', $GLOBALS['DBHCMS']['DICT']['FE']['male']);
		} else if (trim($row['gben_sex']) == DBHCMS_C_ST_FEMALE) {
			$guestbook_sex_icon = dbhcms_f_get_icon('female', $GLOBALS['DBHCMS']['DICT']['FE']['female']);
		} else {
			$guestbook_sex_icon = '';
		}
		
		if (trim($row['gben_email']) != '') {
			$guestbook_email_icon = '<a href="mailto:'.$row['gben_email'].'">'.dbhcms_f_get_icon('email', $row['gben_email']).'</a>';
		} else { $guestbook_email_icon = ''; }
		
		if (trim($row['gben_website']) != '') {
			$guestbook_website_icon = '<a href="'.$row['gben_website'].'" target="_blank">'.dbhcms_f_get_icon('website', $row['gben_website']).'</a>';
		} else { $guestbook_website_icon = ''; }
		
		if (trim($row['gben_name']) == '') {
			$guestbook_entry_title = 'Guest';
		} else { $guestbook_entry_title = $row['gben_name']; }
		
		if ($row['gben_location'] != '') {
			$guestbook_entry_title .= ' ('.$row['gben_location'].')';
		}
		
		dbhcms_p_add_block_values('guestbookEntry', array(	htmlspecialchars($row['gben_name']),			# guestbook_name
															htmlspecialchars($row['gben_company']),			# guestbook_company
															htmlspecialchars($row['gben_location']),		# guestbook_location
															htmlspecialchars($row['gben_email']), 			# guestbook_email
															htmlspecialchars($row['gben_website']), 		# guestbook_website
															$guestbook_entry_text,							# guestbook_text
															date($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['dateFormatOutput'].' '.$GLOBALS['DBHCMS']['CONFIG']['PARAMS']['timeFormatOutput'], dbhcms_f_dbvalue_to_value($row['gben_date'], DBHCMS_C_DT_DATETIME)),
															$guestbook_newtag, 								# guestbook_newtag
															$delete_btn,									# guestbook_delentry
															htmlspecialchars($row['gben_sex']),				# guestbook_sex
															$guestbook_sex_icon,							# guestbook_sex_icon
															$guestbook_email_icon,							# guestbook_email_icon
															$guestbook_website_icon,						# guestbook_website_icon
															$guestbook_entry_title							# guestbook_entry_title
														   ));
		
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>