
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
	# $Id: search.tpl 58 2007-02-01 12:45:58Z kaisven $                                         #
	#############################################################################################
		
	-->
	
	<br />
	<div align="center">
		
		<div class="simplebox" align="left">
			<div class="simplebox_caption"> &nbsp; {dict_searchstring}</div>
			<div style="padding:8px;">
				<form action="{str_pageUrl}" method="post" name="search">
					<input type="hidden" name="dbhcmsCache" value="CT_OFF" />
					<input type="hidden" name="todo" value="searchExecute" />
					<input type="text" name="searchString" value="{str_searchString}" style="width:200px;" />
					<input type="submit" value=" {dict_search} " />
				</form>
			</div>
		</div>

		<div class="simplebox" align="left">
			<div class="simplebox_caption"> &nbsp; {dict_results}</div>
			<div style="padding:8px;">
			<!-- BEGIN searchResults -->
				<font size="2"><strong><a href="{searchResults.searchPageUrl}">{searchResults.searchPageName}</a></strong></font><br />
				{searchResults.searchPageContent}<br />
				<br />
			<!-- END searchResults -->
			</div>
		</div>
	</div>