<?php

/**
 * The template file that allows a user to fetch favicons for all 
 * his bookmarks.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2006
 * @package org.brim-project.plugins.bookmarks
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
?>
<h1><?php echo $dictionary['favicon']; ?></h1>

<p>
<?php echo $dictionary['loadAllFaviconsWarning'] ?>
</p>
<form action="index.php" method="POST">
	<input type="hidden" name="plugin" value="bookmarks" />
	<input type="hidden" name="parentId" value="<?php echo $parentId; ?>" />
	<input type="hidden" name="action" value="fetchAllFavicons" />
	<input type="submit" class="button" value="<?php echo $dictionary['submit'] ?>" />
</form>

<form action="index.php" method="POST">
	<input type="hidden" name="plugin" value="bookmarks" />
	<input type="hidden" name="parentId" value="<?php echo $parentId; ?>" />
	<input type="hidden" name="action" value="deleteAllFavicons" />
	<input type="submit" class="button" value="<?php echo $dictionary['delete'] ?>" />
</form>
