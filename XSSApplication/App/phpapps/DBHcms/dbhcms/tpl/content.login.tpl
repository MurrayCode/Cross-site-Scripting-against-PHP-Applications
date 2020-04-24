
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
		# $Id: content.login.tpl 71 2007-10-15 10:07:42Z kaisven $                                  #
		#############################################################################################

	-->
	
	<div id="login_wrapper">
		<div id="login_banner">
			<div id="login_title"><h1 style="font-size: 18pt;">{bedict_login}</h1></div>
		</div>
		<div id="login_form">
			<table width="400" cellpadding="6" cellspacing="0" border="0">
				<tr>
					<td bgcolor="#FFFFFF" align="center">
						<br>
						<div style="color: #872626; font-weight: bold;">{str_resLoginErr}{str_resSessionExpired}</div>
						<form action="{str_beUrl}" method="post" target="_top"> 
							<table cellpadding="0" cellsapcing="0" width="300" border="0">
								<tr>
									<td align="right"><strong>{bedict_user}:</strong> </td>
									<td align="center"><input type="text" name="dbhcms_user" style="width:170px;"></td>
								</tr>
								<tr>
									<td align="right"><strong>{bedict_password}:</strong> </td>
									<td align="center"><input type="password" name="dbhcms_passwd" style="width:170px;"></td>
								</tr>
								<tr>
									<td></td>
									<td align="center">
										<br />
										<input type="submit" value=" {bedict_login} " style="width:100px;">&nbsp;&nbsp;<input type="button" value=" {bedict_cancel} " style="width:100px;" onclick="window.location= '{str_indexPageUrl}';" >
									</td>
								</tr>
							</table>
						</form>
					</td>
				</tr>
			</table>
		</div>
		<div align="center">
			<br />
			<a target="_blank" href="http://www.drbenhur.com/" style="font-size: 10px; color:#444DFE;">powered by DBHcms</a>
		</div>
	</div>
