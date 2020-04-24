<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.translate
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU License
 */
if (isset ($message))
{
	echo '<h1>'.$dictionary[$message].'</h1>';
}

echo '<form method="POST" action="'.$controller.'">';
echo '<table cellpadding="5" cellspacing="5" valign="left">';
echo '<tr valign="top">';
echo '<th>';
echo $dictionary['pluginToTranslate'];
echo '</th>';
echo '<th>';
echo $dictionary['languageToTranslate'];
echo '</th>';
echo '</tr>';
echo '<tr valign="top">';
echo '<td>';
foreach ($plugins as $plugin)
{
	echo '<input type="radio" name="translationPlugin" value="';
	echo $plugin;
	echo '">';
	echo $plugin;
	echo '</input>';
	echo '<br />';
}
echo '</td>';
echo '<td>';
echo '<table>';
foreach ($languages as $language)
{
	if ($language[0] != 'en' && $language[2])
	{
		echo '<tr>';
		echo '<td>';
		echo '<input type="radio" name="translationLanguage" value="';
		echo $language[0];
		echo '">';
		echo $language[0];
		echo '</input>';
		echo '</td>';
		echo '<td>';
		echo $language[1];
		echo '</td>';
		echo '</tr>';
	}
}
echo '<tr><td><input type="radio" name="translationLanguage" value="XX"></td><td><font size="+1" color="red">New</font></td></tr>';
echo '</table>';
echo '</td>';
echo '</tr></table>';
echo '<input type="submit" value="'.$dictionary['submit'].'" />';
echo '<input type="hidden" name="action" value="translate" />';
echo '</form>';
?>
