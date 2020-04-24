
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
	# $Id: contact.tpl 68 2007-05-31 20:28:17Z kaisven $                                        #
	#############################################################################################
		
	-->

	<table align="center" width="100%" cellpadding="10">
		<tr>
			<td align="center">
				<br />	
				
				<!--
					This is the contact form
				-->
				<!-- BEGIN contactForm -->
				<form method="post" name="contactSendForm" action="{str_pageUrl}" onSubmit="return check_entry();">
					<table border="0" width="500" align="center" cellspacing="0">
						<tr>
							<td align="right"><strong>{dict_name}:</strong>&nbsp;</td>
							<td align="left">
								<input type="text" name="contactName" maxlength="100" tabindex="1"  value="{str_userRealName}" />
							</td>
							<td align="right"><strong>{dict_email}:</strong>&nbsp;</td>
							<td align="left"><input type="text" name="contactEmail" maxlength="100" tabindex="2" value="{str_userEmail}" /></td>
						</tr>
						<tr>
					    	<td align="right"><strong>{dict_location}:</strong>&nbsp;</td>
				    		<td align="left"><input type="text" name="contactLocation" maxlength="100" tabindex="3" value="{str_userLocation}" /></td>
							<td align="right"><strong>{dict_homepage}:</strong>&nbsp;</td>
							<td align="left"><input type="text" name="contactWebsite" maxlength="100" tabindex="4" value="{str_userWebsite}" /></td>
						</tr>
						<tr>
					    	<td align="right"><strong>{dict_company}:</strong>&nbsp;</td>
				    		<td align="left"><input type="text" name="contactCompany" maxlength="100" tabindex="5" value="{str_userCompany}" /></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr>
							<td align="right" valign="top"><strong>{dict_message}:</strong>&nbsp;</td>
							<td colspan="3" align="left">
					    		<textarea name="contactText" cols="46" rows="7" lang="de" tabindex="6"></textarea>
						    </td>
						</tr>
						<tr><td colspan="4"><br /></td></tr>
						<tr>
							<td align="right"><strong>{dict_typecaptcha}:</strong></td>
							<td align="left" colspan="3">
								<table>
									<tr>
										<td>
											<input type="text" name="contactCaptcha">
										</td>
										<td>
											{str_contactCaptcha}
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td height="30"></td>
							<td colspan="3" valign="bottom" align="left">
								<br />
								<input type="hidden" name="dbhcmsCache" value="CT_OFF" />
								<input type="hidden" name="todo" value="contactSendMessage" />
								<input class="buttonsend"  type="submit" value=" &nbsp;&nbsp; {dict_send} &nbsp;&nbsp; >> &nbsp;&nbsp; " />
			   			 	</td>
						</tr>
					</table>
				</form>
				<!-- END contactForm -->
				
				
				<!--
					This block is shown when the message was sent
				-->
				<!-- BEGIN contactSent -->
				<font size="3">
					<strong>{dict_msg_msgsent}</strong>
				</font>
				<!-- END contactSent -->
				
				
				<!--
					This block is shown when an error occurs
				-->
				<!-- BEGIN contactError -->
				<font size="3" color="#FF0000">
					<strong>{contactError.contactMessage}</strong>
				</font>
				<!-- END contactError -->
				
				
				<br />
			</td>
		</tr>
	</table>


