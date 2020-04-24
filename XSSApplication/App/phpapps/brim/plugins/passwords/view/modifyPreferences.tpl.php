<?php
/**
 * The template file to modify a users preference on how to
 * display passwords
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
<h2><?php echo $dictionary['modifyPasswordPreferences'] ?></h2>

<table>
<form method="POST" action="index.php">
<input type="hidden" name="plugin" value="passwords" />
<tr>
	<td>
		<?php echo $dictionary['view'] ?>
	</td>
	<td>
		<select name="value">
		<?php
			$options = array ("Yahoo"=>$dictionary['yahooTree'],
				"Explorer"=>$dictionary['explorerTree']);
			$this->plugin ('options', $options,
				$renderObjects['passwordTree']);
		?>
		</select>
	</td>
	<td>
		<input type="hidden"
			name="name"
			value="passwordTree" />
		<input type="hidden"
			name="action"
			value="modifyPreferencesPost" />
		<input type="submit"
			class="button"
			name="submit"
			value="<?php echo $dictionary['modify'] ?>"  />
	</td>
</tr>
</form>
<form method="POST" action="index.php">
<input type="hidden" name="plugin" value="passwords" />
<tr>
	<td>
		<?php echo $dictionary['yahoo_column_count'] ?>
	</td>
	<td>
			<select name="value">
			<?php
				$options = array (1=>1, 2=>2, 3=>3, 4=>4, 5=>5, 6=>6);
				$this->plugin ('options', $options,
					$renderObjects['passwordYahooTreeColumnCount']);
			?>
			</select>
	</td>
	<td>
			<input type="hidden"
				name="name"
				value="passwordYahooTreeColumnCount" />
			<input type="hidden"
				name="action"
				value="modifyPreferencesPost" />
			<input type="submit"
				class="button"
				name="submit"
				value="<?php echo $dictionary['modify'] ?>"  />
	</td>
</tr>
</form>
</table>