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
# $Id: ext.news.fe.php 61 2007-02-01 14:17:59Z kaisven $                                    #
#############################################################################################

#############################################################################################
#  FE IMPLEMENTATION                                                                        #
#############################################################################################

	### UNSUBSCRIBE NEWSLETTER ###
	if (isset($_GET['newsUnsubscribeUewsletter'])) {
		news_p_unsubscribe_newsletter($_GET['newsUnsubscribeUewsletter']);
	} elseif (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsUnsubscribeUewsletter'])) {
		news_p_unsubscribe_newsletter($GLOBALS['DBHCMS']['TEMP']['PARAMS']['newsUnsubscribeUewsletter']);
	} else {
		dbhcms_p_hide_block('newsUnsubscribeMessage');
	}

	### SUBSCRIBE NEWSLETTER ###
	if (isset($_POST['todo'])) {
		if ($_POST['todo'] == 'newsSubscribeNewsletter') {
			news_p_subscribe_newsletter($_POST['newsSignFullname'], $_POST['newsSignEmail']);
		}
	}

	### ALLOW DISALLOW SUBSCRIBE NEWSLETTER ###
	if (news_f_get_config_param('enableSubscNewsletter')) {
		dbhcms_p_show_block('newsSignNewsletter');
	} else {
		dbhcms_p_hide_block('newsSignNewsletter');
	}

	### ARTICLE STANDARD PARAMETERS ###
	$news_entry_blkprms = array(	'articleId',
									'articleDate', 
									'articleNewTag', 
									'articleUrl',
									'articleCommentCount'
								);

	### ARTICLE USER DEFINED PARAMETERS ###
	$result_parameters = mysql_query("SELECT nwep_name from ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_news_entriesprms ORDER BY nwep_name");
	while ($row_parameters = mysql_fetch_array($result_parameters)) {
		array_push($news_entry_blkprms, 'articleParam'.ucfirst($row_parameters['nwep_name']));
	}

	if (isset($GLOBALS['DBHCMS']['TEMP']['PARAMS']['showEntry'])) {
		include($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].DBHCMS_C_EXT_NEWS.'/ext.'.DBHCMS_C_EXT_NEWS.'.entry.php');
	} else {
		include($GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory'].DBHCMS_C_EXT_NEWS.'/ext.'.DBHCMS_C_EXT_NEWS.'.overview.php');
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>
