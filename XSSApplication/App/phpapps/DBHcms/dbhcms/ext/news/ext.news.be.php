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
#  news                                                                                     #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  A tool to publish your news                                                              #
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
# $Id: ext.news.be.php 61 2007-02-01 14:17:59Z kaisven $                                    #
#############################################################################################

	dbhcms_p_add_string('ext_name', $ext_title);
	dbhcms_p_add_template_ext('ext_content', 'news.tpl', 'news');

#############################################################################################
#  ADMIN IMPLEMENTATION                                                                     #
#############################################################################################

	$settings_class = 'tab_no';
	$params_class 	= 'tab_no';
	$entries_class 	= 'tab_no';

	if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsBePart'])) {
		if ($GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsBePart'] == 'parameters') {
			$params_class = 'tab_act';
			dbhcms_p_add_template_ext('newsContent', 'news.parameters.tpl', 'news');
			include($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].DBHCMS_C_EXT_NEWS.'/ext.'.DBHCMS_C_EXT_NEWS.'.parameters.php');
		} else if ($GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsBePart'] == 'settings') {
			$settings_class = 'tab_act';
			dbhcms_p_add_template_ext('newsContent', 'news.settings.tpl', 'news');
			include($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].DBHCMS_C_EXT_NEWS.'/ext.'.DBHCMS_C_EXT_NEWS.'.settings.php');
		} else {
			$entries_class = 'tab_act';
			include($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].DBHCMS_C_EXT_NEWS.'/ext.'.DBHCMS_C_EXT_NEWS.'.editentry.php');
		}
	} else {
		$entries_class = 'tab_act';
		include($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].DBHCMS_C_EXT_NEWS.'/ext.'.DBHCMS_C_EXT_NEWS.'.editentry.php');
	}

	$news_tabs = '	<td>
						<div class="'.$entries_class.'"> 
							&nbsp;&nbsp; <a href="'.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_NEWS, 'newsBePart' => 'entries')).'"> '.$GLOBALS['DBHCMS']['DICT']['BE']['articles'].' </a> &nbsp;&nbsp;
						</div>
					</td>
					<td width="5"></td>
					<td>
						<div class="'.$settings_class.'"> 
							&nbsp;&nbsp; <a href="'.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_NEWS, 'newsBePart' => 'settings')).'"> '.$GLOBALS['DBHCMS']['DICT']['BE']['settings'].' </a> &nbsp;&nbsp;
						</div>
					</td>
					<td width="5"></td>
					<td>
						<div class="'.$params_class.'"> 
							&nbsp;&nbsp; <a href="'.dbhcms_f_get_url_from_pid_wp($GLOBALS['DBHCMS']['PID'], array('ext' => DBHCMS_C_EXT_NEWS, 'newsBePart' => 'parameters')).'"> '.$GLOBALS['DBHCMS']['DICT']['BE']['parameters'].' </a> &nbsp;&nbsp;
						</div>
					</td>
					<td width="5"></td>';

	dbhcms_p_add_string('newsTabs', $news_tabs);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>