
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
	# $Id: guestbook.tpl 68 2007-05-31 20:28:17Z kaisven $                                      #
	#############################################################################################
		
	-->	
				
	<!--
		This block is shown when an error occurs
	-->
	<!-- BEGIN guestbookError -->
	<br />
	<div align="center">
		<font size="3" color="#FF0000">
			<strong>{guestbookError.guestbookMessage}</strong>
		</font>
	</div>
	<br />
	<!-- END guestbookError -->
		
	<!-- 
		The page-links at the top 
	-->
	<br /><br /><div align="center">{dict_page}:&nbsp;{str_guestbookJumplinks}</div><br />
		
	<!-- 
		This is the block for an entry of the guestbook
	-->
	<!-- BEGIN guestbookEntry -->
	<div align="center">
		<div class="simplebox" align="left">
			<div class="simplebox_caption"> &nbsp; 
				{guestbookEntry.guestbookDelEntry}
				{guestbookEntry.guestbookNewTag}
				{guestbookEntry.guestbookEntryTitle}
				{guestbookEntry.guestbookSexIcon}
				{guestbookEntry.guestbookEmailIcon}
				{guestbookEntry.guestbookWebsiteIcon}
			</div>
			<div style="padding:8px;">
				{guestbookEntry.guestbookText}<div align="right" style="font-size:8pt; color:#999999; ">{guestbookEntry.guestbookDate}</div>
			</div>
		</div>
	</div>
	<!-- END guestbookEntry -->
	
	
	<!-- 
		The page-links at the botomm 
	-->
	<br /><div align="center">{dict_page}:&nbsp;{str_guestbookJumplinks}</div><br />
	
	
	<!-- 
		This is the form to sign the guestbook. It is hidden as soon
		as someone has signed it to avoid spaming
	-->
	<!-- BEGIN guestbookSignForm -->
	<div align="center">
		<div class="simplebox" align="left">
			<div class="simplebox_caption"> &nbsp; {dict_guestbook_sign} </div>
			<div style="padding:8px;">
				<form action="{str_pageUrl}" method="post" name="guestbookSignForm" onSubmit="return check_entry();">
					<table border="0" width="500" align="center" cellspacing="0">
						<tr>
							<td align="right"><strong>{dict_name}:</strong>&nbsp;</td>
							<td align="left">
								<input type="text" name="guestbookName" maxlength="40" tabindex="1"  value="{str_userRealName}">
							</td>
							<td align="right"><strong>{dict_email}:</strong>&nbsp;</td>
							<td align="left"><input type="text" name="guestbookEmail" maxlength="60" tabindex="3" value="{str_userEmail}"></td>
						</tr>
						<tr>
					    	<td align="right"><strong>{dict_location}:</strong>&nbsp;</td>
				    		<td align="left"><input type="text" name="guestbookLocation" maxlength="40" tabindex="2" value="{str_userLocation}"></td>
							<td align="right"><strong>{dict_homepage}:</strong>&nbsp;</td>
							<td align="left"><input type="text" name="guestbookWebsite" maxlength="100" tabindex="4" value="{str_userWebsite}"></td>
						</tr>
						<tr>
					    	<td align="right"><strong>{dict_company}:</strong>&nbsp;</td>
				    		<td align="left"><input type="text" name="guestbookCompany" maxlength="40" tabindex="2" value="{str_userCompany}"></td>
							<td colspan="2" align="center">
								<strong>{dict_female}:</strong> <input style="border:0px;" type="radio" name="guestbookSex" value="ST_FEMALE"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>{dict_male}:</strong><input style="border:0px;" type="radio" name="guestbookSex" value="ST_MALE"> 
							</td>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr>
							<td align="right" valign="top"><strong>{dict_message}:</strong>&nbsp;</td>
							<td colspan="3" align="left">
					    		<textarea name="guestbookText" cols="46" rows="7" lang="de" tabindex="5"></textarea>
						    </td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3">
								{str_guestbookSmiliesBar}
			   			 	</td>
						</tr>
						<tr><td colspan="4"><br /></td></tr>
						<tr>
							<td align="right"><strong>{dict_typecaptcha}:</strong></td>
							<td align="left" colspan="3">
								<table>
									<tr>
										<td>
											<input type="text" name="guestbookCaptcha">
										</td>
										<td>
											{str_guestbookCaptcha}
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td height="30"></td>
							<td colspan="3" valign="bottom" align="left">
								<br />
								<input type="hidden" name="dbhcmsCache" value="CT_EMPTYPAGE" />
								<input type="hidden" name="todo" value="guestbookSignBook" />
								<input class="buttonsend"  type="submit" value=" &nbsp;&nbsp; {dict_send} &nbsp;&nbsp; >> &nbsp;&nbsp; " />
			   			 	</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
	<br />
	<!-- END guestbookSignForm -->