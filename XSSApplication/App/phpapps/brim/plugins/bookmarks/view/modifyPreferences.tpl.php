<?php
/**
 * The template file that enables a user to modify the preferences
 * (i.e. default layout, javascript popups etc) for bookmarks
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.bookmarks
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
?>
<h2><?php echo $dictionary['modifyBookmarkPreferences'] ?></h2>

<table>
<!--
	Option box for default layout (i.e. Directory structure
	or Explorer view
-->
<!--
<form method="POST" action="index.php">
<input type="hidden" name="plugin" value="bookmarks" />
<tr>
	<td>
		<?php echo $dictionary['view'] ?>
	</td>
	<td>
		<select name="value">
		<?php
			$options = array (
				"Javascript"=>$dictionary['javascriptTree'],
				"Yahoo"=>$dictionary['yahooTree'],
				"Explorer"=>$dictionary['explorerTree']);
			$this->plugin ('options', $options,
				$renderObjects['bookmarkTree']);
		?>
		</select>
	</td>
	<td>
		<input type="hidden"
			name="name"
			value="bookmarkTree" />
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
<input type="hidden" name="plugin" value="bookmarks" />
<tr>
	<td class="formParameterName">
		<?php echo $dictionary['javascript_popups']; ?>
	</td>
	<td>
		<?php
			$options = array ($dictionary['no'],
				$dictionary['yes']);
			$this->plugin ('radios',
				'value',
				$options,
				$renderObjects['bookmarkOverlib'],
				null,
				'&nbsp;',
				'class="radio"'
				);
		?>
	</td>
	<td>
		<input type="hidden"
			name="name"
			value="bookmarkOverlib" />
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
<!--
	Column count for the directory structure
-->
<!--
<form method="POST" action="index.php">
<input type="hidden" name="plugin" value="bookmarks" />
<tr>
	<td class="formParameterName">
		<?php echo $dictionary['yahoo_column_count'] ?>
	</td>
	<td>
		<select name="value" class="radio">
		<?php
			$options = array (1=>1, 2=>2, 3=>3, 4=>4, 5=>5, 6=>6);
			$this->plugin ('options', $options,
				$renderObjects['bookmarkYahooTreeColumnCount']);
		?>
		</select>
	</td>
	<td>
		<input type="hidden"
			name="name"
			value="bookmarkYahooTreeColumnCount" />
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
	Where does the bookmark open when we have clicked it.
	In a new window or in the same (this) window
-->
<form method="POST" action="index.php">
<input type="hidden" name="plugin" value="bookmarks" />
<tr>
	<td class="formParameterName">
		<?php echo $dictionary['new_window_target'] ?>
	</td>
	<td>
			<select name="value" class="radio">
			<?php
				$options = array ("_blank" => "New window",
					"_main"=>"Same window");
				$this->plugin ('options', $options,
					$renderObjects['bookmarkNewWindowTarget']);
			?>
			</select>
	</td>
	<td>
			<input type="hidden"
				name="name"
				value="bookmarkNewWindowTarget" />
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
<input type="hidden" name="plugin" value="bookmarks" />
<tr>
	<td class="formParameterName">
		<?php echo $dictionary['showFavicons'] ?>
	</td>
	<td>
		<?php
			$options = array ($dictionary['no'],
				$dictionary['yes']);
			$this->plugin ('radios',
				'value',
				$options,
				$renderObjects['bookmarkFavicon'],
				null,
				'&nbsp;',
				'class="radio"'
				);
		?>
	</td>
	<td>
		<input type="hidden"
			name="name"
			value="bookmarkFavicon" />
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
<input type="hidden" name="plugin" value="bookmarks" />
<tr>
	<td class="formParameterName">
		<?php echo $dictionary['autoAppendProtocol'] ?>
	</td>
	<td>
		<?php
			$options = array ($dictionary['no'],
				$dictionary['yes']);
			$this->plugin ('radios',
				'value',
				$options,
				$renderObjects['bookmarkAutoPrependProtocol'],
				null,
				'&nbsp;',
				'class="radio"'
				);
		?>
	</td>
	<td>
		<input type="hidden"
			name="name"
			value="bookmarkAutoPrependProtocol" />
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
