
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
	# $Id: layout.tpl 70 2007-09-20 05:24:27Z drbenhur $                                         #
	#############################################################################################
		
	-->

	<div class="wrapper">
		
		<!--
			This is the content block in the right.
			The first block for the search engines to like us :)
		-->
	    <div class="content">
			<div class="content_title">{str_pageName}</div>
			<div class="content_text">
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
			<div class="content_title" style="color: #FF0000;"> <strong>!!! DEBUG MODUS ON !!!</strong> </div>
			<div class="content_text" align="center">
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
			
			<br />
			
		</div>
		
		<!-- 
			The left menu block
		-->
		<div class="menu_left">
			
			<div class="menu_box">
				<div class="menu_box_caption"> {dict_menu} </div>
				{menu_left}
			</div>
			
			<!-- 
				This part is only shown if the DBHcms superuser
				is logged in
			-->
			<!-- BEGIN adminLogout -->
			<div class="menu_box">
				<div class="menu_box_caption"> DBHcms </div>
				<div class="menu_box_item_no_1"><a href="{str_beUrl}"> Administration Area </a> </div>
			</div>
			<!-- END adminLogout -->
			
			<!-- 
				The login block
			-->
			<div class="menu_box">
				<div class="menu_box_caption"> {dict_login} </div>
				<div align="center" style="color: #872626; font-weight: bold;">{str_resLoginErr}{str_resSessionExpired}</div>
				
				
				<!-- 
					This part is only shown if the user is NOT logged in
				-->
				<!--  BEGIN login -->
					<form action="" method="post">
						<div style="margin-left: 8px;"> {dict_user} : </div>
						<input name="dbhcms_user" type="text" value="" style="width:180px; margin-bottom: 0px; margin-left: 8px;" />
						<div style="margin-left: 8px;"> {dict_password} : </div>
						<input name="dbhcms_passwd" type="password" value="" style="width:180px; margin-bottom: 6px; margin-left: 8px;" />
						<input type="submit" value=" {dict_login} " style="width:180px; margin-bottom: 6px; margin-left: 8px;" />
					</form>
				<!--  END login -->
				
				
				<!-- 
					This part is only shown if the user is logged in
				-->
				<!--  BEGIN logout -->
					<div style="margin-left: 8px;">
						{dict_hello} {str_userRealName}!<br />
						{dict_logedinas} <u>{str_userName}</u>.
					</div>
					<br />
					<form action="" method="post">
						<input type="hidden" name="dbhcms_logout" value="logout" />
						<input type="submit" value=" {dict_logout} " style="width:180px; margin-bottom: 6px; margin-left: 8px;" />
					</form>
				<!--  END logout -->
				
			</div>
			
			
			<!-- 
				The search block
			-->
			<div class="menu_box">
				<div class="menu_box_caption"> {dict_search} </div>
				<form action="{str_pid11PageUrl}" method="post" name="search">
					<input type="hidden" name="dbhcmsCache" value="CT_OFF" />
					<input type="hidden" name="todo" value="searchExecute" />
					<input name="searchString" type="text" value="{str_searchString}" style="width:115px; margin-bottom: 6px; margin-left: 8px;" /> 
					<input name="submitbtn" type="submit" value=" {dict_search} " style="margin-bottom: 6px;" />
				</form>
			</div>
			
			<!-- 
				The language block with the little flags
			-->
			<div class="menu_box">
				<div class="menu_box_caption"> {dict_language} </div>
				<table width="150" align="center">
					<tr>
						<td align="center">
							<a href="{str_pageUrl_en}" title="{dict_en}"><img style="border: 1px solid #000000;" src="{str_imageDirectory}langicons/uk.gif" width="18" height="12" alt="{dict_en}" title="{dict_en}" border="0" /></a>
						</td>
						<td align="center">
							<a href="{str_pageUrl_de}" title="{dict_de}"><img style="border: 1px solid #000000;" src="{str_imageDirectory}langicons/de.gif" width="18" height="12" alt="{dict_de}" title="{dict_de}" border="0" /></a>
						</td>
						<td align="center">
							<a href="{str_pageUrl_es}" title="{dict_es}"><img style="border: 1px solid #000000;" src="{str_imageDirectory}langicons/es.gif" width="18" height="12" alt="{dict_es}" title="{dict_es}" border="0" /></a>
						</td>
					</tr>
				</table>
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
		<div class="menu_headline"> &nbsp; {menu_headline} </div>
		
	</div>