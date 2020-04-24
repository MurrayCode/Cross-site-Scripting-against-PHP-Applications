<?php
/**
 * The template file to import contacts
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
<form enctype="multipart/form-data" action="index.php"
	method="post" name="importContacts">
<input type="hidden" name="plugin" value="contacts" />
<table>
<tr>
	<td class="formParameterName">
		<?php echo $dictionary ['import'] ?>:
	</td>
	<td>
		<select name="importType">
			<option value="VCard">VCard(s)</option>
			<option value="Opera">Opera</option>
			<option value="LDIF">LDIF (experimental)</option>
		</select>
	</td>
</tr>
<tr>
	<td class="formParameterName">
		<?php echo $dictionary ['visibility'] ?>:
	</td>
	<td>
		<!--
			TODO FIXME BARRY
			Can't this be done with a Savant plugin?
		-->
		<select name="visibility">
		    <option value="private"><?php echo $dictionary['private'] ?></option>
			<option value="public"><?php echo $dictionary['public'] ?></option>
		</select>
	</td>
</tr>
</table>
<input type="hidden" name="action" value="importContacts" />
<input type="hidden" name="parentId" value="<?php echo $parentId ?>" />
<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
<?php echo $dictionary['file'] ?>: <input name="importFile" type="file"><br />
<input type="submit" name="importSubmit" value="<?php echo $dictionary['submit'] ?>">
</form>
