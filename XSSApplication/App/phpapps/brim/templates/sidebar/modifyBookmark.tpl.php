<?php

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.templates
 * @subpackage sidebar
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
 ?>

<table border="0" width="100%">
<tr>
	<td><h2><?php echo $dictionary['modify']; ?>&nbsp;<?php echo $dictionary['bookmark']; ?></h2></td>
</tr>
</table>
<form method="POST" action="BookmarkController.php">
	<table width="80%" border="0">
	<tr>
		<td>
			<?php echo $dictionary['title']; ?>:
		</td>
		<td>
			<input type="text" name="name"
			size="60" value="<?php echo $renderObjects->name; ?>" />
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $dictionary['url']; ?>:
		</td>
		<td>
			<input type="text" name="locator"
			size="60" value="<?php echo $renderObjects->locator; ?>" />
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $dictionary['description']; ?>:
		</td>
		<td>
			<textarea rows="4" cols="40"
			name="description"><?php echo $renderObjects->description; ?></textarea>
		</td>
	</tr>
	</table>
	<h2><?php echo $dictionary['modify'] ?> <?php echo $dictionary['bookmark']; ?></h2>
	<input type="hidden" value=<?php echo $renderObjects->itemId; ?>
		name="itemId" />
	<input type="hidden" name="action" value="modifyBookmark" />
	<input type="hidden" name="parentId"
		value=<?php echo $renderObjects->parentId; ?> />
	<input type="hidden" name="isParent"
		value=<?php echo $renderObjects->isParent; ?> />
	<input type="submit" value="<?php echo $dictionary['modify']; ?>" name="modifyBookmark" />
</form>

<h2><?php echo $dictionary['move']; ?> <?php echo $dictionary['bookmark']; ?></h2>
<form method="POST" action="BookmarkSidebarController.php">
	<input type="hidden" value=<?php echo $renderObjects->itemId; ?>
		name="itemId" />
	<input type="hidden" name="action" value="move" />
	<input type="submit" value="<?php echo $dictionary['move']; ?>"
		name="move" />
</form>

<h2><?php echo $dictionary['delete']; ?> <?php echo $dictionary['bookmark']; ?></h2>
<form method="POST" action="BookmarkController.php">
	<input type="hidden" value=<?php echo $renderObjects->itemId; ?>
		name="itemId" />
	<input type="hidden" name="action" value="deleteBookmark" />
	<input type="submit" value="<?php echo $dictionary['delete']; ?>"
		name="deleteBookmark" />
</form>