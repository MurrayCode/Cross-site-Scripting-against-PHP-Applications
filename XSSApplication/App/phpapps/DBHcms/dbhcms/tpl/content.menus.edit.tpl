
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
		# $Id: content.menus.edit.tpl 60 2007-02-01 13:34:54Z kaisven $                             #
		#############################################################################################

	-->
		
	<h1>{str_dbhcms_editmenu_title}</h1>
	<form name="dbhcms_edit_menu" method="post" action="index.php?dbhcms_pid=-80">
		<input type="hidden" name="dbhcms_save_menu" value="{str_dbhcms_editmenu_id}">
		<div class="box">
			<table cellpadding="2" cellspacing="1" border="0" width="100%">
				<tr>
					<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18" width="200">{bedict_parameter}</td>
					<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18" width="300">{bedict_value}</td>
					<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18">{bedict_description}</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcl}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcl}'">
					<td align="right" valign="top"><strong>{bedict_name} : </strong></td>
					<td>{str_dbhcms_editmenu_name}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcd}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcd}'">
					<td align="right" valign="top"><strong>{bedict_type} : </strong></td>
					<td>{str_dbhcms_editmenu_type}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcl}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcl}'">
					<td align="right" valign="top"><strong>{bedict_layer} : </strong></td>
					<td>{str_dbhcms_editmenu_layer}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcd}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcd}'">
					<td align="right" valign="top"><strong>{bedict_depth} : </strong></td>
					<td>{str_dbhcms_editmenu_depth}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcl}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcl}'">
					<td align="right" valign="top"><strong>{bedict_showrestrictedpages} : </strong></td>
					<td>{str_dbhcms_editmenu_showrestricted}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcl}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcd}'">
					<td align="right" valign="top"><strong>Wrap All : </strong></td>
					<td>{str_dbhcms_editmenu_wrapall}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcd}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcl}'">
					<td align="right" valign="top"><strong>Wrap Normal : </strong></td>
					<td>{str_dbhcms_editmenu_wrapnormal}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcl}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcd}'">
					<td align="right" valign="top"><strong>Wrap Active : </strong></td>
					<td>{str_dbhcms_editmenu_wrapactive}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcd}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcl}'">
					<td align="right" valign="top"><strong>Wrap Selected : </strong></td>
					<td>{str_dbhcms_editmenu_wrapselected}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcd}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcd}'">
					<td align="right" valign="top"><strong>Link Normal : </strong></td>
					<td>{str_dbhcms_editmenu_linknormal}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcl}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcl}'">
					<td align="right" valign="top"><strong>Link Active : </strong></td>
					<td>{str_dbhcms_editmenu_linkactive}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcd}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcd}'">
					<td align="right" valign="top"><strong>Link Selected : </strong></td>
					<td>{str_dbhcms_editmenu_linkselected}</td>
					<td>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcl}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcl}'">
					<td align="right" valign="top"><strong>{bedict_description} : </strong></td>
					<td>{str_dbhcms_editmenu_description}</td>
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
					<input type="button" value=" {bedict_cancel} " onclick="window.location.href='index.php?dbhcms_pid=-80'">
				</td>
			</tr>
		</table>