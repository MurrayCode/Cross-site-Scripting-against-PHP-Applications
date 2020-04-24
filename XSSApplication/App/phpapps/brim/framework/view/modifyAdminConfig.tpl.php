<?php

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 * @subpackage view
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
 ?>
<h2><?php echo $dictionary['adminConfig'] ?></h2>

<?php
	require_once 'framework/model/AdminServices.php';
	$adminServices = new AdminServices ();
?>

<table border="0" width="100%">
<tr>
<td width="600" valign="top">
	<form method="POST" action="AdminController.php">
	<table border="0" width="400">
		<tr>
			<td><?php echo $dictionary['allow_account_creation'] ?>:</td>
			<td width="90" align="right">
					<?php
						$options = array (0=>$dictionary['no'], 1=>$dictionary['yes']);
						$this->plugin ('radios',
							'value',
							$options,
							$allow_account_creation,
							null,
							'&nbsp;',
							'class="radio"');
					?>
			</td>
			<td width="65" align="right">
				<input type="hidden" name="name" value="allow_account_creation" />
				<input type="hidden" value="modifyAdminConfigPost" name="action" />
				<input type="submit" value="<?php echo $dictionary['modify'] ?>" />
			</td>
		</tr>
	</table></form>
	<form method="POST" action="AdminController.php">
	<table border="0" width="400">
		<tr>
			<td><?php echo $dictionary['installation_path'] ?>:</td>
			<td width="90" align="right">
					<input type="text" name="value" value="<?php echo $installation_path ?>">
			</td>
			<td width="65" align="right">
				<input type="hidden" name="name" value="installation_path">
				<input type="hidden" value="modifyAdminConfigPost" name="action" />
				<input type="submit" value="<?php echo $dictionary['modify'] ?>" />
			</td>
		</tr>
	</table></form>
	<form method="POST" action="AdminController.php">
	<table border="0" width="400">
		<tr>
			<td><?php echo $dictionary['admin_email'] ?>:</td>
			<td width="90" align="right">
					<input type="text" name="value" value="<?php echo $admin_email ?>">
			</td>
			<td width="65" align="right">
				<input type="hidden" name="name" value="admin_email" />
				<input type="hidden" value="modifyAdminConfigPost" name="action" />
				<input type="submit" value="<?php echo $dictionary['modify'] ?>" />
			</td>
		</tr>
	</table></form>
	<form method="POST" action="AdminController.php">
	<table border="0" width="400">
		<tr>
			<td><?php echo $dictionary['calendarEmailReminder'] ?>:</td>
			<td width="90" align="right">
					<?php
						$options = array (0=>$dictionary['no'], 1=>$dictionary['yes']);
						$this->plugin ('radios',
							'value',
							$options,
							$calendarEmailReminder,
							null,
							'&nbsp;',
							'class="radio"');
					?>
			</td>
			<td width="65" align="right">
				<input type="hidden" name="name" value="calendarEmailReminder" />
				<input type="hidden" value="modifyAdminConfigPost" name="action" />
				<input type="submit" value="<?php echo $dictionary['modify'] ?>" />
			</td>
		</tr>
	</table></form>
	<form method="POST" action="AdminController.php">
	<table border="0" width="400">
		<tr>
			<td><?php echo $dictionary['calendarParticipation'] ?>:</td>
			<td width="90" align="right">
					<?php
						$options = array (0=>$dictionary['no'], 1=>$dictionary['yes']);
						$this->plugin ('radios',
							'value',
							$options,
							$calendarParticipation,
							null,
							'&nbsp;',
							'class="radio"');
					?>
			</td>
			<td width="65" align="right">
				<input type="hidden" name="name" value="calendarParticipation" />
				<input type="hidden" value="modifyAdminConfigPost" name="action" />
				<input type="submit" value="<?php echo $dictionary['modify'] ?>" />
			</td>
		</tr>
	</table>
	</form>
</td>
</tr>
</table>
