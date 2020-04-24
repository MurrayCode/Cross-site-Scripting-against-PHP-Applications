
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
	# $Id: photoalbum.albums.edit.tpl 61 2007-02-01 14:17:59Z kaisven $                         #
	#############################################################################################

	-->
	
	<table cellpadding="8" cellspacing="0" border="0" width="100%">
		<tr>
			<td>
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						{str_photoalbumAlbumTabs}
					</tr>
				</table>
				<div class="box" style="padding: 8px;">
					<table cellpadding="2" cellspacing="1" border="0" width="100%" align="center" style="border-color: #28538F; border-style: solid; border-width: 1px;">
						<tr>
							<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" width="100">{bedict_parameter}</td>
							<td background="{str_coreImageDirectory}tab_cap.gif" class="cap" width="200">{bedict_value}</td>
							<td background="{str_coreImageDirectory}tab_cap.gif" class="cap">{bedict_description}</td>
						</tr>
						{str_photoalbumAlbumParams}
					</table>
				</div>
			</td>
		</tr>
	</table>