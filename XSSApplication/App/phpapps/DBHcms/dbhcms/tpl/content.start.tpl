
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
		# $Id: content.start.tpl 69 2007-06-03 16:50:53Z kaisven $                                  #
		#############################################################################################

	-->
	
	{str_action_result}
	
	<h1>{bedict_dbhcms_adminwelcome}</h1>
	
	<div class="box">
		<div class="box_caption"> &nbsp; {bedict_system} </div>
		<table border="0" cellpadding="2" style="padding-top: 5px; padding-bottom:5px;">
			<tr>
				<td></td>
				<td><a href="index.php?dbhcms_pid=-30"><img src="{str_coreImageDirectory}icons/large/preferences-system.png" width="32" height="32" alt="{bedict_settings}" style="border: 1px solid #444DFE; padding: 8px;"></a></td>
				<td><a href="index.php?dbhcms_pid=-30">{bedict_settings}</a></td>
				<td></td>
				<td><a href="index.php?dbhcms_pid=-70"><img src="{str_coreImageDirectory}icons/large/users.png" width="32" height="32" alt="{bedict_users}" style="border: 1px solid #444DFE; padding: 8px;"></a></td>
				<td><a href="index.php?dbhcms_pid=-70">{bedict_users}</a></td>
				<td></td>
				<td><a href="index.php?dbhcms_pid=-120"><img src="{str_coreImageDirectory}icons/large/application-x-executable.png" width="32" height="32" alt="{bedict_extmanager}" style="border: 1px solid #444DFE; padding: 8px;"></a></td>
				<td><a href="index.php?dbhcms_pid=-120">{bedict_extmanager}</a></td>
				<td></td>
				<td><a href="index.php?dbhcms_pid=-60"><img src="{str_coreImageDirectory}icons/large/dialog-information.png" width="32" height="32" alt="{bedict_instanceinfo}" style="border: 1px solid #444DFE; padding: 8px;"></a></td>
				<td><a href="index.php?dbhcms_pid=-60">{bedict_instanceinfo}</a></td>
			</tr>
		</table>
	</div>
	
	<div class="box">
		<div class="box_caption"> &nbsp; Web </div>
		<table border="0" cellpadding="2" style="padding-top: 5px; padding-bottom:5px;">
			<tr>
				<td></td>
				<td><a href="index.php?dbhcms_pid=-50"><img src="{str_coreImageDirectory}icons/large/preferences-desktop-font.png" width="32" height="32" alt="{bedict_dictionary}" style="border: 1px solid #444DFE; padding: 8px;"></a></td>
				<td><a href="index.php?dbhcms_pid=-50">{bedict_dictionary}</a></td>
				<td></td>
				<td><a href="index.php?dbhcms_pid=-20"><img src="{str_coreImageDirectory}icons/large/applications-internet.png" width="32" height="32" alt="{bedict_domains}" style="border: 1px solid #444DFE; padding: 8px;"></a></td>
				<td><a href="index.php?dbhcms_pid=-20">{bedict_domains}</a></td>
				<td></td>
				<td><a href="index.php?dbhcms_pid=-10"><img src="{str_coreImageDirectory}icons/large/x-office-document.png" width="32" height="32" alt="{bedict_pages}" style="border: 1px solid #444DFE; padding: 8px;"></a></td>
				<td><a href="index.php?dbhcms_pid=-10">{bedict_pages}</a></td>
				<td></td>
				<td><a href="index.php?dbhcms_pid=-80"><img src="{str_coreImageDirectory}icons/large/view-fullscreen.png" width="32" height="32" alt="{bedict_menus}" style="border: 1px solid #444DFE; padding: 8px;"></a></td>
				<td><a href="index.php?dbhcms_pid=-80">{bedict_menus}</a></td>
			</tr>
		</table>
	</div>

	<div class="box">
		<div class="box_caption"> &nbsp; {bedict_extensions} </div>
		<table border="0" cellpadding="2" style="padding-top: 5px; padding-bottom:5px;">
			<tr>
				{str_menuAvailableExtensions}
			</tr>
		</table>
	</div>

	<div class="box">
		<div class="box_caption"> &nbsp; {bedict_applications} </div>
		<table border="0" cellpadding="2" style="padding-top: 5px; padding-bottom:5px;">
			<tr>
				<td></td>
				<td><a href="{str_coreAppsDirectory}phpmyadmin/index.php" target="_blank"><img src="{str_coreImageDirectory}icons/large/applications-system.png" width="32" height="32" alt="phpMyAdmin" style="border: 1px solid #444DFE; padding: 8px;"></a></td>
				<td><a href="{str_coreAppsDirectory}phpmyadmin/index.php" target="_blank">phpMyAdmin</a></td>
				<td></td>
				<td><a href="{str_coreAppsDirectory}quixplorer/index.php" target="_blank"><img src="{str_coreImageDirectory}icons/large/system-file-manager.png" width="32" height="32" alt="QuiXplorer" style="border: 1px solid #444DFE; padding: 8px;"></a></a></td>
				<td><a href="{str_coreAppsDirectory}quixplorer/index.php" target="_blank">QuiXplorer</a></td>
			</tr>
		</table>
	</div>
