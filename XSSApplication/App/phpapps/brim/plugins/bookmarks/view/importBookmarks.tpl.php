<?php
/**
 * The template file that draws the layout to import bookmarks
 * 
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.bookmarks
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
?>
<form enctype="multipart/form-data" 
	action="index.php" 
	method="post" 
	name="importBookmarks"
>
<input type="hidden" name="plugin" value="bookmarks" />
<table>
<tr>
	<td class="formParameterName">
		<?php echo $dictionary ['importTxt'] ?>:
	</td>
	<td>
		<select name="importType">
			<option value="Opera">Opera</option>
			<option value="Netscape">Netscape/Mozilla/FireFox</option>
<?php
		if (function_exists('xml_parser_create'))
		{
?>
			<option value="XBEL">XBEL</option>
<?php
		}
?>
		</select>
	</td>
</tr>
<tr>
	<td class="formParameterName">
		<?php echo $dictionary ['visibility'] ?>:
	</td>
	<td>
		<select name="visibility">
			<option value="private"><?php 
					echo $dictionary['private'] 
			?></option>
			<option value="public"><?php 
					echo $dictionary['public'] 
			?></option>
		</select>
	</td>
</tr>
<tr>
	<td class="formParameterName">
		<?php echo $dictionary['file'] ?>: 
	</td>
	<td>
		<input type="file" 
			name="importFile"
			class="fileButton">
		<input type="hidden" 
			name="parentId" 
			value="<?php echo $parentId ?>" />
		<input type="hidden" 
			name="action" 
			value="importBookmarks" />
		<input type="hidden" 
			name="MAX_FILE_SIZE" 
			value="1000000" />
	</td>
</tr>
</table>
<input type="submit" 
	class="button"
	name="importSubmit" 
	value="<?php echo $dictionary['submit'] ?>">
</form>
