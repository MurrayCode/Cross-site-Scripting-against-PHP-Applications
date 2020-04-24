
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
		# $Id: content.accesslog.tpl 68 2007-05-31 20:28:17Z kaisven $                              #
		#############################################################################################

	-->
		
	<h1>Access Log</h1>
	{str_action_result}
	<div class="box">
		<div class="box_caption"> &nbsp; {bedict_search} </div>
		<div style="padding: 4px;">
			<table>
				<form method="post">
					<tr>
						<td align="right"><strong>{bedict_user} : </strong></td>
						<td><input type="text" name="ul_search_user" value="{str_dict_search_str}" style="width:200px;"></td>
						<td><input type="submit" value=" {bedict_search} "></td>
					</tr>
				</form>
				<form method="post">
					<tr>
						<td align="right"><strong>{bedict_action} : </strong></td>
						<td>
							<select name="ul_search_action" style="width:204px;">
								<option value="WPWD" {str_ul_search_action_wpwd_sel}>Wrong password (WPWD) </option>
								<option value="WUSER" {str_ul_search_action_wuser_sel}>Wrong user (WUSER) </option>
								<option value="WDOMN" {str_ul_search_action_wdomn_sel}>Wrong domain (WDOMN) </option>
								<option value="LOGIN" {str_ul_search_action_login_sel}>Login (LOGIN)</option>
								<option value="LOGOUT" {str_ul_search_action_logout_sel}>Logout (LOGOUT)</option>
							</select>
						</td>
						<td><input type="submit" value=" {bedict_search} "></td>
					</tr>
				</form>
			</table>
		</div>
	</div>
	
	<div class="box">
		<div class="simplebox_caption" style="text-align: center; border-bottom-width: 0px;"> {bedict_page}: {str_jumplinks} </div>
	</div>
	
	<div class="box">
		<table cellpadding="2" cellspacing="1" border="0" width="100%">
			<tr>
				<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18" colspan="2">{bedict_user}</td>
				<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18">{bedict_action}</td>
				<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18">Timestamp</td>
				<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18">Session-ID</td>
			</tr>
			{str_dbhcms_accesslog}
		</table>
	</div>
	
	<div class="box">
		<div class="simplebox_caption" style="text-align: center; border-bottom-width: 0px;"> {bedict_page}: {str_jumplinks} </div>
	</div>
	
	<form method="post" onsubmit="return confirm('Are you sure you want to delete all entries in the access log?');"><input type="hidden" name="access_log_empty" value="ALL"><input type="submit" value=" {bedict_empty} "></form>