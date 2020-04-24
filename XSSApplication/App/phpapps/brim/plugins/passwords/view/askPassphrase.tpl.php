<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - May 2006
 * @package org.brim-project.plugins.passwords
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

unset ($_POST);
?>
<form method="POST" method="index.php">
	<input type="hidden" name="plugin" value="passwords" />
	<input type="hidden" name="itemId" value="<?php echo $itemId ?>" />
	<input type="hidden" name="action" value="<?php echo $requestedAction ?>" />
<table>
<tr>
	<td>
		<?php echo $dictionary['passPhrase']; ?>
	</td>
	<td>
		<input type="password" name="passPhrase" />
	</td>
</tr>
</table>
<input type="submit" value="Submit" name="<?php echo $dictionary['sbumit'] ?>" />
</form>
