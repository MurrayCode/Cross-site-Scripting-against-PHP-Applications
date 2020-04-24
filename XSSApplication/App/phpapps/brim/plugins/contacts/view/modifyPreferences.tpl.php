<?php
/**
 * The template file to modify a users contact preferences
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
<h2><?php echo $dictionary['modifyContactPreferences'] ?></h2>

<table>
<!--
<form method="POST" action="index.php">
<input type="hidden" name="plugin" value="contacts" />
<tr>
	<td>
		<?php echo $dictionary['view'] ?>
	</td>
	<td>
			<select name="value">
			<?php
				$options = array (
					"Yahoo"=>$dictionary['yahooTree'],
					"Explorer"=>$dictionary['explorerTree'],
					"Line based"=>$dictionary['lineBasedTree']);
				$this->plugin ('options', $options,
					$renderObjects['contactTree']);
			?>
			</select>
	</td>
	<td>
			<input type="hidden"
				name="name"
				value="contactTree" />
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
-->

<!--
	Javascript popup selection
-->
<form method="POST" action="index.php">
<input type="hidden" name="plugin" value="contacts" />
<tr>
	<td>
		<?php echo $dictionary['javascript_popups']; ?>
	</td>
	<td>
			<?php
				$options = array ($dictionary['no'],
					$dictionary['yes']);
				$this->plugin ('radios',
					'value',
					$options,
					$renderObjects['contactOverlib'],
					null, '&nbsp;',
					'class="radio"'
			);
			?>
	</td>
	<td>
			<input type="hidden" name="name" value="contactOverlib" />
			<input type="hidden" name="action" value="modifyPreferencesPost" />
			<input type="submit"
				class="button"
				name="submit"
				value="<?php echo $dictionary['modify'] ?>"  />
	</td>
</tr>
</form>

<!--
<form method="POST" action="index.php">
<input type="hidden" name="plugin" value="contacts" />
<tr>
	<td>
		<?php echo $dictionary['yahoo_column_count'] ?>
	</td>
	<td>
			<select name="value">
			<?php
				$options = array (1=>1, 2=>2, 3=>3, 4=>4, 5=>5, 6=>6);
				$this->plugin ('options', $options, $renderObjects['contactYahooTreeColumnCount']);
			?>
			</select>
	</td>
	<td>
			<input type="hidden" name="name" value="contactYahooTreeColumnCount" />
			<input type="hidden" name="action" value="modifyPreferencesPost" />
			<input type="submit"
				class="button"
				name="submit"
				value="<?php echo $dictionary['modify'] ?>"  />
	</td>
</tr>
</form>
-->
</table>
