<?php
/**
 * The template file that draws the layout to search for a bookmark.
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
<h2><?php echo $dictionary['search'].' '.$dictionary['bookmarks'] ?></h2>

<table>
<!--
	Search for a bookmarks name
-->
<tr>
	<td>
		<?php echo $dictionary['name'] ?>:
	</td>
	<td>
		<form method="POST" action="index.php">
		<input type="hidden" name="plugin" value="bookmarks" />
		<input type="hidden" name="field" value="name">
		<input type="text" name="value">
		<input type="hidden" name="action" value="searchBookmarks">
		<input type="submit" value="<?php echo $dictionary['search'] ?>">
		</form>
	</td>
</tr>
<!--
	Search for a bookmarks URL/locator
-->
<tr>
	<td>
		<?php echo $dictionary['locator'] ?>:
	</td>
	<td>
		<form method="POST" action="index.php">
		<input type="hidden" name="plugin" value="bookmarks" />
		<input type="hidden" name="field" value="locator">
		<input type="text" name="value">
		<input type="hidden" name="action" value="searchBookmarks">
		<input type="submit" value="<?php echo $dictionary['search'] ?>">
		</form>
	</td>
</tr>
<!--
	Search on a bookmarks description
-->
<tr>
	<td>
		<?php echo $dictionary['description'] ?>:
	</td>
	<td>
		<form method="POST" action="index.php">
		<input type="hidden" name="plugin" value="bookmarks" />
		<input type="hidden" name="field" value="description">
		<input type="text" name="value">
		<input type="hidden" name="action" value="searchBookmarks">
		<input type="submit" value="<?php echo $dictionary['search'] ?>">
		</form>
	</td>
</tr>
</table>