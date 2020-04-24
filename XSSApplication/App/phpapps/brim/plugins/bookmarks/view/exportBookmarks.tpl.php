<?php

/**
 * The template file that draws the layout to export bookmarks
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
<form action="index.php" method="post"
		name="exportBookmarks">
<input type="hidden" name="plugin" value="bookmarks" />
<table>
<tr>
	<td>
		<?php echo $dictionary['export']; ?>:
	</td>
	<td>
		<select name="exportType">
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
</table>
<input type="hidden"
	name="action"
	value="exportBookmarks" />
<input type="hidden"
	name="parentId"
	value="<?php echo $parentId ?>" />
<input type="submit"
	class="button"
	name="exportSubmit"
	value="<?php echo $dictionary['submit'] ?>">
</form>