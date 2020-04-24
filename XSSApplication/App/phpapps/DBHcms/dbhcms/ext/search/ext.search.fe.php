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
#  search                                                                                   #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Content search engine                                                                    #
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
# $Id: ext.search.fe.php 60 2007-02-01 13:34:54Z kaisven $                                  #
#############################################################################################

#############################################################################################
#  FE IMPLEMENTATION                                                                        #
#############################################################################################

	if (isset($_POST['todo'])) {
		if ($_POST['todo'] == 'searchExecute') {
			dbhcms_p_add_block('searchResults', array('searchPageName', 'searchPageContent', 'searchPageUrl'));
			$search_results = search_f_get_pages($_POST['searchString']);
			foreach ($search_results as $pageid => $page_vals) {
				dbhcms_p_add_block_values('searchResults', array($page_vals['name'], $page_vals['content'], $page_vals['url']));
			}
			dbhcms_p_add_string('searchString', $_POST['searchString']);
		} else { 
			dbhcms_p_hide_block('searchResults');
		}
	} else { 
		dbhcms_p_hide_block('searchResults');
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>