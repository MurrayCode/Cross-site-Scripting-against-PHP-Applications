
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
		# $Id: content.menu.tpl 69 2007-06-03 16:50:53Z kaisven $                                   #
		#############################################################################################

	-->
	
	<div class="menu_box">
		<div id="1c" class="menu_box_caption" onclick="change_hide('1');"><a onclick="change_hide('1');" style="cursor : pointer;">[-] {bedict_home}</a></div>
		<div id="1b" style="display: inline;">
			<div class="menu_box_item"><a href="index.php?dbhcms_pid=-3" target="dbhcms_admin_content" >BE {bedict_home}</a></div>
			<div class="menu_box_item"><a href="index.php?dbhcms_pid=-40" target="dbhcms_admin_content" >FE {bedict_home}</a></div>
			<div class="menu_box_item"><a href="{str_beLoginUrl}" target="_parent" >{bedict_logout}</a></div>
		</div>
	</div>
	
	<div class="menu_box">
		<div id="2c" class="menu_box_caption" onclick="change_hide('2');"><a onclick="change_hide('2');" style="cursor : pointer;">[-] {bedict_system}</a></div>
		<div id="2b" style="display: inline;">
			<div class="menu_box_item"><a href="index.php?dbhcms_pid=-30"  target="dbhcms_admin_content" >{bedict_settings}</a></div>
			<div class="menu_box_item"><a href="index.php?dbhcms_pid=-70"  target="dbhcms_admin_content" >{bedict_users}</a></div>
			<div class="menu_box_item"><a href="index.php?dbhcms_pid=-120" target="dbhcms_admin_content" >{bedict_extmanager}</a></div>
			<div class="menu_box_item"><a href="index.php?dbhcms_pid=-60"  target="dbhcms_admin_content" >{bedict_instanceinfo}</a></div>
		</div>
	</div>
	
	<div class="menu_box">
		<div id="3c" class="menu_box_caption" onclick="change_hide('3');"><a onclick="change_hide('3');" style="cursor : pointer;">[-] Web</a></div>
		<div id="3b" style="display: inline;">
			<div class="menu_box_item"><a href="index.php?dbhcms_pid=-50" target="dbhcms_admin_content" >{bedict_dictionary}</a></div>
			<div class="menu_box_item"><a href="index.php?dbhcms_pid=-20" target="dbhcms_admin_content" >{bedict_domains}</a></div>
			<div class="menu_box_item"><a href="index.php?dbhcms_pid=-10" target="dbhcms_admin_content" >{bedict_pages}</a></div>
			<div class="menu_box_item"><a href="index.php?dbhcms_pid=-80" target="dbhcms_admin_content" >{bedict_menus}</a></div>
		</div>
	</div>
	
	<div class="menu_box">
		<div id="5c" class="menu_box_caption" onclick="change_hide('5');"><a onclick="change_hide('5');" style="cursor : pointer;">[+] {bedict_extensions}</a></div>
		<div id="5b" style="display: none;">
			{str_dbhcms_admin_ext_menu}
		</div>
	</div>
	
	<div class="menu_box">
		<div id="4c" class="menu_box_caption" onclick="change_hide('4');"><a onclick="change_hide('4');" style="cursor : pointer;">[+] {bedict_applications}</a></div>
		<div id="4b" style="display: none;">
			<div class="menu_box_item"><a href="{str_coreAppsDirectory}phpmyadmin/index.php" target="_blank" >phpMyAdmin</a></div>
			<div class="menu_box_item"><a href="{str_coreAppsDirectory}quixplorer/index.php" target="_blank" >QuiXplorer</a></div>
		</div>
	</div>
	
	<div class="menu_box">
		<div id="6c" class="menu_box_caption" onclick="change_hide('6');"><a onclick="change_hide('6');" style="cursor : pointer;">[+] {bedict_actions}</a></div>
		<div id="6b" style="display: none;">
			<div class="menu_box_item"><a href="index.php?dbhcms_pid=-5&action=genhtaccess" target="dbhcms_admin_content" >Generate .htaccess</a></div>
			<!--
				Debuging tool
				<div class="menu_box_item"><a href="index.php?dbhcms_pid=-5&action=genhtaccess" target="dbhcms_admin_content" >Generate .htaccess</a></div>
			-->
			<div class="menu_box_item"><a href="index.php?dbhcms_pid=-5&action=emptytemp" target="dbhcms_admin_content" >Empty Temp</a></div>
			<div class="menu_box_item"><a href="index.php?dbhcms_pid=-5&action=emptycache" target="dbhcms_admin_content" >Empty Cache</a></div>
		</div>
	</div>
	
	<div class="menu_box">
		<div id="7c" class="menu_box_caption" onclick="change_hide('7');"><a onclick="change_hide('7');" style="cursor : pointer;">[+] Logs</a></div>
		<div id="7b" style="display: none;">
			<div class="menu_box_item"><a href="index.php?dbhcms_pid=-90" target="dbhcms_admin_content" >Access Log</a></div>
			<div class="menu_box_item"><a href="index.php?dbhcms_pid=-110" target="dbhcms_admin_content" >Error Log</a></div>
		</div>
	</div>

	<div class="content_footer">
		<a target="_blank" href="http://www.drbenhur.com/" style="font-size: 10px; color:#444DFE;"> DBHcms {str_dbhcmsVersion} Core {str_coreVersion} <br /> &copy; 2005-2007 Kai-Sven Bunk </a>
	</div>