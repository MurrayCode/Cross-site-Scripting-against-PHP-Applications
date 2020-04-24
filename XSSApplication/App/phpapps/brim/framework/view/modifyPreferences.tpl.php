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

$dateFormats = array ();
$now = strtotime (date ('Y-m-d H:i:s'));
$dateFormats [] = 'd/m/Y';
$dateFormats [] = 'm/d/Y';
$dateFormats [] = 'Y-m-d';

 ?>
<h1><?php echo $userSettings->loginName ?></h1>
<h2><?php echo $dictionary['preferences'] ?></h2>

<script type="text/javascript">
<!--
function datePopup (format)
{
	result  = '<?php echo $dictionary['today'] ?>: '+format;
	//alert (result);
}
// -->
</script>


<!--
	Language settings
-->
<form method="POST" action="PreferenceController.php">
<table>
<tr>
	<td><?php echo $dictionary['language'] ?>:</td>
	<td>
		<select name="theValue">
		<?php
			foreach ($languages as $language)
			{
				if ($language[2])
				{
					echo ('<option value="'.$language[0].'"');
					if (isset ($renderObjects['brimLanguage']) &&
						$renderObjects['brimLanguage'] == $language[0])
					{
						echo (' selected="selected"');
					}
					echo ('>'.$language[1].'</option>');
				}
			}
		?>
		</select>
	</td>
	<td>
		<input type="hidden"
			name="name"
			value="brimLanguage" />
		<input type="hidden"
			name="loginName"
			value="<?php echo $renderObjects['owner'] ?>" />
		<input type="hidden"
			value="modifyPreferences"
			name="action" />
		<input type="submit"
			class="button"
			name="submit"
			value="<?php echo $dictionary['modify'] ?>" />
	</td>
</tr>
</form>

<!--
	The theme settings
-->
<form method="POST" action="PreferenceController.php" name="dateFormat">
<tr>
	<td><?php echo $dictionary['theme'] ?>:</td>
	<td>
		<select name="theValue">
			<?php
			foreach ($templates as $template)
			{
				echo ('<option value="'.$template.'"');
				if (isset ($renderObjects['brimTemplate']) &&
					$renderObjects['brimTemplate'] == $template)
				{
					echo (' selected="selected"');
				}
				echo ('>'.$template.'</option>');
			}
			?>
		</select>
	</td>

	<td>
		<input type="hidden"
			name="name"
			value="brimTemplate" />
		<input type="hidden"
			name="loginName"
			value="<?php echo $renderObjects['owner'] ?>" />
		<input type="hidden"
			name="action"
			value="modifyPreferences" />
		<input type="submit"
			class="button"
			name="submit"
			value="<?php echo $dictionary['modify'] ?>" />
	</td>
</tr>
</form>
<!--
	Date formatting
-->
<form method="POST" action="PreferenceController.php" name="dateFormat">
<tr>
	<td><?php echo $dictionary['dateFormat'] ?>:</td>
	<td>
		<select name="theValue"
			onChange="javascript:datePopup(document.forms['dateFormat'].theValue.item('selected').value);"
		>
		<?php
			foreach ($dateFormats as $dateFormat)
			{
					echo ('<option value="'.$dateFormat.'"');
					if (isset ($renderObjects ['brimDateFormat'])
						&& $renderObjects['brimDateFormat'] == $dateFormat)
					{
						echo (' selected="selected" ');
					}
					echo ' name="'.$dateFormat.'" ';
					echo ('>'.$dateFormat.'</option>');
			}
		?>
		</select>
	</td>
	<td>
		<input type="hidden"
			name="name"
			value="brimDateFormat" />
		<input type="hidden"
			name="loginName"
			value="<?php echo $renderObjects['owner'] ?>" />
		<input type="hidden"
			value="modifyPreferences"
			name="action" />
		<input type="submit"
			class="button"
			name="submit"
			value="<?php echo $dictionary['modify'] ?>" />
	</td>
</tr>
</form>
<!--
	Icon size preferences
-->
<form method="POST" action="PreferenceController.php" name="brimPreferedIconSize">
<tr>
	<td valign="top"><?php echo $dictionary['preferedIconSize'] ?>:</td>
	<td valign="top">
		<input type="radio" name="theValue" value=""
			<?php if (!isset ($renderObjects['brimPreferedIconSize'])
					|| ($renderObjects['brimPreferedIconSize'] == '')
				) { ?>
				checked="checked"
			<?php } ?>
		><?php echo $dictionary['defaultTxt'] ?><br />
		<input type="radio" name="theValue" value="48x48"
			<?php if (isset ($renderObjects['brimPreferedIconSize']) &&
					$renderObjects['brimPreferedIconSize'] == '48x48') { ?>
				checked="checked"
			<?php } ?>
		>48x48</input><br />
		<input type="radio" name="theValue" value="32x32"
			<?php if (isset ($renderObjects['brimPreferedIconSize']) &&
					$renderObjects['brimPreferedIconSize'] == '32x32') { ?>
				checked="checked"
			<?php } ?>
		>32x32</input><br />
		<input type="radio" name="theValue" value="24x24"
			<?php if (isset ($renderObjects['brimPreferedIconSize']) &&
					$renderObjects['brimPreferedIconSize'] == '24x24') { ?>
				checked="checked"
			<?php } ?>
		>24x24</input><br />
		<input type="radio" name="theValue" value="16x16"
			<?php if (isset ($renderObjects['brimPreferedIconSize']) &&
					$renderObjects['brimPreferedIconSize'] == '16x16') { ?>
				checked="checked"
			<?php } ?>
		>16x16</input><br />
	</td>
	<td valign="top">
		<input type="hidden"
			name="name"
			value="brimPreferedIconSize" />
		<input type="hidden"
			name="loginName"
			value="<?php echo $renderObjects['owner'] ?>" />
		<input type="hidden"
			value="modifyPreferences"
			name="action" />
		<input type="submit"
			class="button"
			name="submit"
			value="<?php echo $dictionary['modify'] ?>" />
	</td>
</tr>
</form>

<form method="POST" action="PreferenceController.php" name="brimDefaultExpandMenu">
<tr>
	<td valign="top">
		<?php echo $dictionary['defaultExpandMenu']; ?>:
	</td>
	<td valign="top">
		<input type="radio" name="theValue" value="1"
			<?php if (!isset ($renderObjects['brimDefaultExpandMenu'])
					|| ($renderObjects['brimDefaultExpandMenu'] == '1'))
				{
			?> checked="checked" <?php } ?>
		><?php echo $dictionary['yes'] ?></input>
		<input type="radio" name="theValue" value="0"
			<?php if (isset ($renderObjects['brimDefaultExpandMenu'])
					&& ($renderObjects['brimDefaultExpandMenu'] == '0'))
				{
			?> checked="checked" <?php } ?>
		><?php echo $dictionary['no'] ?></input>
	</td>
	<td valign="top">
		<input type="hidden"
			name="name"
			value="brimDefaultExpandMenu" />
		<input type="hidden"
			name="loginName"
			value="<?php echo $renderObjects['owner'] ?>" />
		<input type="hidden"
			value="modifyPreferences"
			name="action" />
		<input type="submit"
			class="button"
			name="submit"
			value="<?php echo $dictionary['modify'] ?>" />
	</td>
</tr>
</form>



<form method="POST" action="PreferenceController.php" name="brimDefaultShowShared">
<tr>
	<td valign="top">
		<?php echo $dictionary['defaultShowShared']; ?>:
	</td>
	<td valign="top">
		<input type="radio" name="theValue" value="1"
			<?php if (!isset ($renderObjects['brimDefaultShowShared'])
					|| ($renderObjects['brimDefaultShowShared'] == '1'))
				{
			?> checked="checked" <?php } ?>
		><?php echo $dictionary['yes'] ?></input>
		<input type="radio" name="theValue" value="0"
			<?php if (isset ($renderObjects['brimDefaultShowShared'])
					&& ($renderObjects['brimDefaultShowShared'] == '0'))
				{
			?> checked="checked" <?php } ?>
		><?php echo $dictionary['no'] ?></input>
	</td>
	<td valign="top">
		<input type="hidden"
			name="name"
			value="brimDefaultShowShared" />
		<input type="hidden"
			name="loginName"
			value="<?php echo $renderObjects['owner'] ?>" />
		<input type="hidden"
			value="modifyPreferences"
			name="action" />
		<input type="submit"
			class="button"
			name="submit"
			value="<?php echo $dictionary['modify'] ?>" />
	</td>
</tr>
</form>



<form method="POST" action="PreferenceController.php" name="brimShowTips">
<tr>
	<td valign="top">
		<?php echo $dictionary['showTips']; ?>:
	</td>
	<td valign="top">
		<input type="radio" name="theValue" value="1"
			<?php if (!isset ($renderObjects['brimShowTips'])
					|| ($renderObjects['brimShowTips'] == '1'))
				{
			?> checked="checked" <?php } ?>
		><?php echo $dictionary['yes'] ?></input>
		<input type="radio" name="theValue" value="0"
			<?php if (isset ($renderObjects['brimShowTips'])
					&& ($renderObjects['brimShowTips'] == '0'))
				{
			?> checked="checked" <?php } ?>
		><?php echo $dictionary['no'] ?></input>
	</td>
	<td valign="top">
		<input type="hidden"
			name="name"
			value="brimShowTips" />
		<input type="hidden"
			name="loginName"
			value="<?php echo $renderObjects['owner'] ?>" />
		<input type="hidden"
			value="modifyPreferences"
			name="action" />
		<input type="submit"
			class="button"
			name="submit"
			value="<?php echo $dictionary['modify'] ?>" />
	</td>
</tr>
</form>


<form method="POST" action="PreferenceController.php" name="brimEnableAjax">
<tr>
	<td valign="top">
		<?php echo $dictionary['enableAjax']; ?>:
	</td>
	<td valign="top">
		<input type="radio" name="theValue" value="1"
			<?php if (!isset ($renderObjects['brimEnableAjax'])
					|| ($renderObjects['brimEnableAjax'] == '1'))
				{
			?> checked="checked" <?php } ?>
		><?php echo $dictionary['yes'] ?></input>
		<input type="radio" name="theValue" value="0"
			<?php if (isset ($renderObjects['brimEnableAjax'])
					&& ($renderObjects['brimEnableAjax'] == '0'))
				{
			?> checked="checked" <?php } ?>
		><?php echo $dictionary['no'] ?></input>
	</td>
	<td valign="top">
		<input type="hidden"
			name="name"
			value="brimEnableAjax" />
		<input type="hidden"
			name="loginName"
			value="<?php echo $renderObjects['owner'] ?>" />
		<input type="hidden"
			value="modifyPreferences"
			name="action" />
		<input type="submit"
			class="button"
			name="submit"
			value="<?php echo $dictionary['modify'] ?>" />
	</td>
</tr>
</form>
</table>

<!--
	Display the flags of all configured languages
-->
<table border="0">
	<tr>
	<?php
		foreach ($languages as $currentLanguage)
		{
			if ($currentLanguage[2])
			{
	?>
		<td>
			<form method="POST" action="PreferenceController.php" />
				<input type="hidden" name="loginName"
					value="<?php echo $_SESSION['brimUsername']; ?>" />
				<input type="hidden" value="modifyPreferences"
					name="action" />
				<input type="hidden" name="name"
					value="brimLanguage" />
				<input type="hidden" name="theValue"
					value="<?php echo $currentLanguage[0]; ?>" />
				<input type="image"
					src="framework/view/pics/flags/flag-<?php
							echo $currentLanguage[0]; ?>.png" />
			</form>
		</td>
	<?php
			}
		}
	?>
	</tr>
</table>


<h2><?php echo $dictionary['user'] ?></h2>
<form method="POST" action="UserController.php">
<table>
<tr>
	<td><?php echo $dictionary['loginName'] ?>:
	</td>
	<td><?php echo $userSettings->loginName ?>
	</td>
</tr>
<tr>
	<td><?php echo $dictionary['password'] ?>:
	</td>
	<td><input class="input" type="password" name="password" />
	</td>
</tr>
<tr>
	<td><?php echo $dictionary['confirm'] ?>:
	</td>
	<td><input class="input" type="password" name="password2" />
	</td>
</tr>
<tr>
	<td><?php echo $dictionary['name'] ?>:
	</td>
	<td><input class="input" type="text" name="name"
		value="<?php echo $userSettings->name ?>" />
	</td>
</tr>
<tr>
	<td><?php echo $dictionary['email'] ?>:
	</td>
	<td><input class="input" type="text" name="email"
		value="<?php echo $userSettings->email ?>" />
	</td>
</tr>
<tr>
	<td valign="top"><?php echo $dictionary['description'] ?>:
	</td>
	<td><textarea class="input" name="description"
		><?php echo $userSettings->description ?></textarea>
	</td>
</tr>
</table>


<input type="hidden"
	name="userId"
	value="<?php echo $userSettings->userId ?>" />
<input type="hidden"
	name="loginName"
	value="<?php echo $userSettings->loginName ?>" />
<input type="hidden"
	name="action"
	value="modifyUser" />
<input type="submit"
	class="button"
	name="submit"
	value="<?php echo $dictionary['modify'] ?>" />
</form>
<hr />
