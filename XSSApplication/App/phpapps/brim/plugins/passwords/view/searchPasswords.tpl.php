<?php
/**
 * The template file to seach for passwords
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2004
 * @package org.brim-project.plugins.passwords
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
 ?>
<h2><?php echo $dictionary['search'].' '.$dictionary['passwords'] ?></h2>

<table>
<tr>
	<td>
		<?php echo $dictionary['name'] ?>:
	</td>
	<td>
		<form method="POST" action="index.php">
		<input type="hidden" name="plugin" value="passwords">
		<input type="hidden" name="field" value="name">
		<input type="text" name="value">
		<input type="hidden" name="action" value="searchPasswords">
		<input type="submit" value="<?php echo $dictionary['search'] ?>">
		</form>
	</td>
</tr>
</table>