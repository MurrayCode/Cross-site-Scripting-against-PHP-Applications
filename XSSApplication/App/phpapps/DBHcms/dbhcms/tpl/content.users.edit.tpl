
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
		# $Id: content.users.edit.tpl 60 2007-02-01 13:34:54Z kaisven $                             #
		#############################################################################################

	-->
		
	<h1>{str_dbhcms_edituser_title}</h1>
	<form name="dbhcms_edit_user" method="post" action="index.php?dbhcms_pid=-70">
		<input type="hidden" name="dbhcms_save_user" value="{str_dbhcms_edituser_id}">
		<input type="hidden" name="user_login_hidden" value="{str_dbhcms_edituser_login_hidden}">
		<div class="box">
			<table cellpadding="2" cellspacing="1" border="0" width="100%">
				<tr>
					<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18" width="100">{bedict_parameter}</td>
					<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18" width="200">{bedict_value}</td>
					<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18">{bedict_description}</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcl}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcl}'">
					<td align="right"><strong>{bedict_user} : </strong></td>
					<td>{str_dbhcms_edituser_login}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcd}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcd}'">
					<td align="right"><strong>{bedict_password} : </strong></td>
					<td>{str_dbhcms_edituser_passwd}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcl}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcl}'">
					<td align="right"><strong>{bedict_name} : </strong></td>
					<td>{str_dbhcms_edituser_name}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcd}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcd}'">
					<td align="right"><strong>{bedict_sex} : </strong></td>
					<td>{str_dbhcms_edituser_sex}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcl}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcl}'">
					<td align="right"><strong>{bedict_company} : </strong></td>
					<td>{str_dbhcms_edituser_company}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcd}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcd}'">
					<td align="right"><strong>{bedict_location} : </strong></td>
					<td>{str_dbhcms_edituser_location}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcl}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcl}'">
					<td align="right"><strong>{bedict_email} : </strong></td>
					<td>{str_dbhcms_edituser_email}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcd}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcd}'">
					<td align="right"><strong>{bedict_website} : </strong></td>
					<td>{str_dbhcms_edituser_website}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcl}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcl}'">
					<td align="right"><strong>{bedict_language} : </strong></td>
					<td>{str_dbhcms_edituser_lang}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcd}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcd}'">
					<td align="right"><strong>{bedict_domains} : </strong></td>
					<td>{str_dbhcms_edituser_domains}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcl}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcl}'">
					<td align="right"><strong>{bedict_levels} : </strong></td>
					<td>{str_dbhcms_edituser_level}</td>
					<td>
					</td>
				</tr>
			</table>
		</div>
		<table>
			<tr>
				<td>
					<input type="submit" value=" {bedict_save} ">
				</td>
			</form>
				<td>
					<input type="button" value=" {bedict_cancel} " onclick="window.location.href='index.php?dbhcms_pid=-70'">
				</td>
			</tr>
		</table>