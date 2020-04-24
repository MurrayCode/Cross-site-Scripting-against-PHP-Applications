
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
		# $Id: content.errorlog.tpl 68 2007-05-31 20:28:17Z kaisven $                               #
		#############################################################################################

	-->
		
	<h1>Error Log</h1>
	{str_action_result}
	<div class="box">
		<div class="box_caption"> &nbsp; {bedict_search} </div>
		<div style="padding: 4px;">
			<table>
				<form method="post">
					<tr>
						<td align="right"><strong>Error : </strong></td>
						<td><input type="text" name="al_search_error" value="{str_al_search_error}" style="width:200px;"></td>
						<td><input type="submit" value=" {bedict_search} "></td>
					</tr>
				</form>
				<form method="post">
					<tr>
						<td align="right"><strong>{bedict_action} : </strong></td>
						<td>
							<select name="al_search_type" style="width:204px;">
								<option value="1" {str_al_search_type_1}>ERRORS</option>
								<option value="0" {str_al_search_type_0}>WARNINGS</option>
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
				<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18" colspan="2">Error</td>
				<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18">Timestamp</td>
				<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18">{bedict_actions}</td>
			</tr>
			{str_dbhcms_errorlog}
		</table>
	</div>
	
	<div class="box">
		<div class="simplebox_caption" style="text-align: center; border-bottom-width: 0px;"> {bedict_page}: {str_jumplinks} </div>
	</div>
	
	<form method="post" onsubmit="return confirm('Are you sure you want to delete all entries in the error log?');"><input type="hidden" name="err_log_empty" value="ALL"><input type="submit" value=" {bedict_empty} "></form>