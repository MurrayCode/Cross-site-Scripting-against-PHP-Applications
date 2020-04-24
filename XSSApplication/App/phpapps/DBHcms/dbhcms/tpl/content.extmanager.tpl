
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
		# $Id: content.extmanager.tpl 60 2007-02-01 13:34:54Z kaisven $                             #
		#############################################################################################

	-->
		
	<h1>{bedict_extmanager}</h1>
	{str_action_result}
	<div class="box">
		<table cellpadding="2" cellspacing="1" border="0" width="100%">
			<tr>
				<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18" colspan="2">{bedict_name}</td>
				<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18">{bedict_version}</td>
				<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18">{bedict_description}</td>
				<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18">{bedict_installed}</td>
				<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" height="18">{bedict_actions}</td>
			</tr>
			{str_dbhcms_extensions}
		</table>
	</div>