
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
		# $Id: content.domain.edit.tpl 68 2007-05-31 20:28:17Z kaisven $                            #
		#############################################################################################

	-->
		
	<h1>Edit Domain {str_dbhcms_domain_name}</h1>
	<form  onsubmit="if (!isValueSet('domn_supported_langs', document.getElementById('domn_default_lang').value)) { alert('The default language must also be in the supported languages list.'); return false; } " name="dbhcms_edit_domain" method="post" action="index.php?dbhcms_pid=-20">
		<input type="hidden" name="dbhcms_save_domain" value="{str_dbhcms_edit_domain_id}">
		<div class="box">
			<table cellpadding="2" cellspacing="1" border="0" width="100%">
				<tr>
					<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18" width="200">Parameter</td>
					<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18" width="205">Value</td>
					<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18">Description</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcl}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcl}'">
					<td><strong>Domain name</strong></td>
					<td>{str_dbhcms_edit_domain_name}</td>
					<td>
						Domain name or host<br>
						Example: <strong>www.drbenhur.de</strong> or <strong>127.0.0.1</strong>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcd}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcd}'">
					<td><strong>Subfolders</strong></td>
					<td>{str_dbhcms_edit_domain_subfolders}</td>
					<td>
						Subfolder relative to the domain<br>
						Example: <strong>/</strong> for no subfolders or <strong>/asubfolder/anothersubfolder/</strong>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcl}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcl}'">
					<td><strong>Absolute URL</strong></td>
					<td>{str_dbhcms_edit_domain_absolute_url}</td>
					<td>
						Complete URL of the Website<br>
						Example: <strong>http://www.drbenhur.com/asubfolder/anothersubfolder/</strong>
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcd}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcd}'">
					<td><strong>Default Language</strong></td>
					<td>{str_dbhcms_sdlod}</td>
					<td>
						Default language to load if the browsers language is not supported
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcl}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcl}'">
					<td><strong>Supported Languages</strong></td>
					<td>{str_dbhcms_sslod}</td>
					<td>
						Languages supported on the page
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcd}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcd}'">
					<td><strong>Stylesheets</strong></td>
					<td>{str_dbhcms_edit_domain_stylesheets}</td>
					<td>
						Stylesheets for all pages in the domain
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcl}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcl}'">
					<td><strong>Javascripts</strong></td>
					<td>{str_dbhcms_edit_domain_javascripts}</td>
					<td>
						Javascripts for all pages in the domain
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcd}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcd}'">
					<td><strong>Templates</strong></td>
					<td>{str_dbhcms_edit_domain_templates}</td>
					<td>
						Templates for all pages in the domain
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcl}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcl}'">
					<td><strong>PHP Modules</strong></td>
					<td>{str_dbhcms_edit_domain_php_modules}</td>
					<td>
						PHP Modules for all pages in the domain
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcd}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcd}'">
					<td><strong>Extensions</strong></td>
					<td>{str_dbhcms_edit_domain_extensions}</td>
					<td>
						Extensions for all pages in the domain
					</td>
				</tr>
				<tr bgcolor="{str_dbhcms_admin_rcl}" onmouseover="this.bgColor = '{str_dbhcms_admin_rch}'" onmouseout="this.bgColor = '{str_dbhcms_admin_rcl}'">
					<td><strong>Description</strong></td>
					<td>{str_dbhcms_edit_domain_decription}</td>
					<td>
						Description of the domain
					</td>
				</tr>
				{str_dbhcms_spod_index}
				{str_dbhcms_spod_intro}
				{str_dbhcms_spod_login}
				{str_dbhcms_spod_logout}
				{str_dbhcms_spod_ad}
				{str_dbhcms_spod_err401}
				{str_dbhcms_spod_err403}
				{str_dbhcms_spod_err404}
			</table>
		</div>
		<table>
			<tr>
				<td>
					<input type="submit" value=" {bedict_save} ">
				</td>
			</form>
				<td>
					<input type="button" value=" {bedict_cancel} " onclick="window.location.href='index.php?dbhcms_pid=-20'">
				</td>
			</tr>
		</table>
		
	 