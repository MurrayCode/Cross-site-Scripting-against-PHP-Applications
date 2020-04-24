<?php
/**
 * The template file to search for contacts
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.contacts
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
 ?>
<h2><?php echo $dictionary['search'].' '.$dictionary['contacts'] ?></h2>

<table>
<tr>
	<td>
		<?php echo $dictionary['name'] ?>:
	</td>
	<td>
		<form method="POST" action="index.php">
		<input type="hidden" name="plugin" value="contacts">
		<input type="hidden" name="field" value="name">
		<input type="text" name="value">
		<input type="hidden" name="action" value="searchContacts">
		<input type="submit" value="<?php echo $dictionary['search'] ?>">
		</form>
	</td>
</tr>
<tr>
	<td>
		<?php echo $dictionary['alias'] ?>:
	</td>
	<td>
		<form method="POST" action="index.php">
		<input type="hidden" name="plugin" value="contacts">
		<input type="hidden" name="field" value="alias">
		<input type="text" name="value">
		<input type="hidden" name="action" value="searchContacts">
		<input type="submit" value="<?php echo $dictionary['search'] ?>">
		</form>
	</td>
</tr>
<tr>
	<td>
		<?php echo $dictionary['description'] ?>:
	</td>
	<td>
		<form method="POST" action="index.php">
		<input type="hidden" name="plugin" value="contacts">
		<input type="hidden" name="field" value="description">
		<input type="text" name="value">
		<input type="hidden" name="action" value="searchContacts">
		<input type="submit" value="<?php echo $dictionary['search'] ?>">
		</form>
	</td>
</tr>
<tr>
	<td>
		<?php echo $dictionary['address'] ?>:
	</td>
	<td>
		<form method="POST" action="index.php">
		<input type="hidden" name="plugin" value="contacts">
		<input type="hidden" name="field" value="address">
		<input type="text" name="value">
		<input type="hidden" name="action" value="searchContacts">
		<input type="submit" value="<?php echo $dictionary['search'] ?>">
		</form>
	</td>
</tr>
</table>