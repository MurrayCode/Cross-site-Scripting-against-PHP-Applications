<?php
/**
 * The template file to export contacts
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
<form action="index.php" method="post" name="exportContacts">
<input type="hidden" name="plugin" value="contacts" />
	<select name="exportType">
		<option value="Opera">Opera</option>
		<option value="VCard">VCard(s)</option>
	</select>
	<br />

	<input type="hidden" name="action" value="exportContacts" />
	<input type="hidden" name="parentId" value="<?php echo $parentId ?>" />
	<input type="submit" name="exportSubmit" value="<?php echo $dictionary['submit'] ?>">
</form>