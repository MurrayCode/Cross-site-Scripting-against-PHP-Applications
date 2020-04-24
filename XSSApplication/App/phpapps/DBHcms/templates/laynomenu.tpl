
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
	# $Id: laynomenu.tpl 70 2007-09-20 05:24:27Z drbenhur $                                      #
	#############################################################################################
		
	-->

	<div class="wrapper">
		
		<!--
			This is the content block in the right.
			The first block for the search engines to like us :)
		-->
	    <div class="content_nm">
			<div class="content_title_nm">{str_pageName}</div>
			<div class="content_text_nm">
				<br />
				<h1>&nbsp;&nbsp;{str_pageName}</h1>
				<hr noshade="noshade" />
				<div style="padding-left: 18px; padding-right: 18px;" align="left">
					{str_pageContent}
				</div>
				{tpl_nr2}
				<br />
				<div align="right" style="font-size:8pt; color:#999999; ">Last update: {str_pageLastEdited} &nbsp; </div>
			</div>
						
			<!--
				This is the debug block below the content block. It is only shown when you have ther debug-modus 
				on in your DBHcms system settings.
			-->
			<!-- BEGIN dbhcmsDebug -->
			<br />
			<div class="content_title_nm" style="color: #FF0000;"> <strong>!!! DEBUG MODUS ON !!!</strong> </div>
			<div class="content_text_nm" align="center">
				<br />
				<div class="simplebox" align="left">
					<div class="simplebox_caption"> &nbsp; Sessions </div>
					<div style="padding:8px;">
						{dbhcmsDebug.dbhcmsSessions}
					</div>
				</div>
				<div class="simplebox" align="left">
					<div class="simplebox_caption"> &nbsp;  DBHcms Globals </div>
					<div style="padding:8px;">
						{dbhcmsDebug.dbhcmsGlobalParams}
					</div>
				</div>
				<div class="simplebox" align="left">
					<div class="simplebox_caption"> &nbsp; DBHcms Session </div>
					<div style="padding:8px;">
						{dbhcmsDebug.dbhcmsSessionParams}
					</div>
				</div>
				<div class="simplebox" align="left">
					<div class="simplebox_caption"> &nbsp; Script Duration </div>
					<div style="padding:8px;">
						{str_scriptDuration}
					</div>
				</div>
			</div>
			<!-- END dbhcmsDebug -->
			
			<!--
			    Change the copyright but please leave a link "powered by DBHcms" to http://www.drbenhur.com 
				This will help a lot for the DBHcms to get well-known. You may change color and size, you 
				can even remove it if you wish but then at least let me know about your website so I can add 
				you to my references. Thank you very much for your cooperation! Greets, Kai.
			 -->
			<div class="content_footer">
				<br />
				{menu_footer}
				<br />
				<br />
				&copy; 2007 Your Name <br /> <a target="_blank" href="http://www.drbenhur.com/" class="copyright"> powered by DBHcms </a>
			</div>
			
		</div>
		
		<!-- 
			The banner. Please change this so not all the websites look the equal ;) 
		-->
		<div class="banner"></div>
		
		<!-- 
			The menu at the right top in the banner
		-->
		<div class="menu_main_top"> {menu_top} </div>
		
		<!-- 
			The headline menu below the banner
		-->
		<div class="menu_headline"> &nbsp; &bull; <a href="{str_domainAbsoluteUrl}" target="{str_indexPageTarget}"> {str_indexPageName} </a> {menu_headline} </div>
		
	</div>