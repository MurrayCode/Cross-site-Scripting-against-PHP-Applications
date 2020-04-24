
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
		# $Id: content.dictionary.tpl 60 2007-02-01 13:34:54Z kaisven $                             #
		#############################################################################################

	-->
	
	<h1>{bedict_dictionary}</h1>
	{str_action_result}
	<div class="box">
		<div class="box_caption"> &nbsp; {bedict_search} </div>
		<div style="padding: 4px;">
			<table>
				<form method="post" action="index.php?dbhcms_pid=-50">
					<tr><td><strong>{bedict_searchstring}:</strong></td><td></td></tr>
					<tr><td><input type="text" name="dict_search" value="{str_dict_search_str}"></td><td><input type="submit" value=" {bedict_search} "></td></tr>
				</form>
			</table>
		</div>
	</div>
	<div class="box">
		<div class="box_caption"> &nbsp; {bedict_insert} </div>
		<div style="padding: 4px;">
			<table>
				<form method="post" action="index.php?dbhcms_pid=-50">
					<tr>
						<td><strong>{bedict_name}:</strong></td>
						<td><strong>{bedict_value}:</strong></td>
						<td><strong>{bedict_translatefrom}:</strong></td>
						<td></td>
					</tr>
					<tr>
						<td><input type="text" name="dict_insert"></td>
						<td><input type="text" name="dict_insert_value"></td>
						<td>
							<select name="dict_insert_translate">
								<option value="0">{bedict_donttranslate}</option>
								<option value="en">{bedict_en}</option>
								<option value="zh">{bedict_zh}</option>
								<option value="zt">{bedict_zt}</option>
								<option value="nl">{bedict_nl}</option>
								<option value="fr">{bedict_fr}</option>
								<option value="de">{bedict_de}</option>
								<option value="el">{bedict_el}</option>
								<option value="it">{bedict_it}</option>
								<option value="ja">{bedict_ja}</option>
								<option value="ko">{bedict_ko}</option>
								<option value="pt">{bedict_pt}</option>
								<option value="ru">{bedict_ru}</option>
								<option value="es">{bedict_es}</option>
							</select>
						</td>
						<td><input type="submit" value=" {bedict_insert} "></td>
					</tr>
				</form>
			</table>
		</div>
	</div>
	
	<div class="box">
		<div class="box_caption"> &nbsp; Export / Import </div>
		<div style="padding: 4px;">
			<table>
				<form method="post" action="index.php?dbhcms_pid=-50">
					<tr>
						<td>
							<strong>Export:</strong>
						</td>
						<td></td>
						<td> &nbsp;&nbsp;&nbsp; </td>
						<td>
							<strong>Import:</strong>
						</td>
						<td></td>
					</tr>
					<tr>
						<td>
							<select name="dict_export">
								<option value="xml">XML</option>
							</select>
						</td>
						<td>
							<input type="submit" value=" Export ">
						</td>
				</form>
				<form method="post" action="index.php?dbhcms_pid=-50">
						<td> &nbsp;&nbsp;&nbsp; </td>
						<td>
							<input type="text" name="dict_import" value="http://www.drbenhur.com/dbhcms_dictionary.xml" style="width:250px;">
						</td>
						<td>
							<input type="submit" value=" Import ">
						</td>
						
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
			{str_dict_values}
		</table>
	</div>
	
	<div class="box">
		<div class="simplebox_caption" style="text-align: center; border-bottom-width: 0px;"> {bedict_page}: {str_jumplinks} </div>
	</div>