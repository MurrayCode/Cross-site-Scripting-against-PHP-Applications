<?php
require_once 'framework/util/StringUtils.php';
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

// Version is only (manually) set in the base directory
unset ($baseDictionary['version']);
echo '<h1>';
echo $dictionary['percentComplete'].': ';
echo calculatePercentComplete ($baseDictionary, $translatedDictionary);
echo '</h1>';

echo '<h2>';
echo $dictionary['saveTranslationToLocation'].':&nbsp;';
echo $translationFileName;
echo '</h2>';

echo '<form method="POST" action="'.$controller.'">';
echo $dictionary['pluginTranslatorIndicator'].': ';
echo '<input name="pluginTranslator" type="text">';
echo '<table border="0" valign="top" cellspacing="3" cellpadding="3">';
/*
echo '<tr>';
echo '<th>'.$dictionary['translationKey'].'</th>';
//echo '<th>'.$dictionary['baseTranslation'].'</th>';
echo '<th>'.$dictionary['baseTranslation'].'</th>';
echo '<th>'.$dictionary['currentTranslation'].'</th>';
echo '<th>&nbsp;</th>';
echo '</tr>';
*/
foreach ($baseDictionary as $key => $item)
{
	echo '
		<tr valign="top">
			<td><b>'.$key.'</b></td>
			<td valign="top">'.$dictionary['baseTranslation'].'</td>
			<td><pre>'.
			htmlspecialchars($item).'</pre></td>
		</tr>
		<!--
		<tr>
			<td>&nbsp;</td>
			<td valign="top">'.$dictionary['currentTranslation'].'</td>
			<td>';
				if (isset ($translatedDictionary[$key])
					&& trim ($translatedDictionary[$key]) != '')
				{
					echo $translatedDictionary[$key];
				}
				else
				{
					echo '<font color="red"><b>NOT SET!!!</b></font>';
				}
			echo '</td>
		</tr>
		-->	
		<tr>
			<td colspan="2">&nbsp;</td>
			<td>
			<textarea ';
				echo 'name="'.$key.'" ';
				echo ' cols="40" ';
			if (isset ($translatedDictionary[$key]))
			{
				$height = strlen ($translatedDictionary[$key])/40;
				if ($height < 1)
				{
					$height=1;
				}
			}
			else
			{
				$height=1;
			}
			echo ' rows="'.$height.'" ';
			echo '>';
			if (isset ($translatedDictionary[$key]))
			{
				echo ($translatedDictionary[$key]);
			}
			echo '</textarea>';
		echo '</td>';
	echo '</tr>';
}
echo '</table>
<input type="hidden" name="translationLanguage" value="'.$translationLanguage.'" />
<input type="hidden" name="translationPlugin" value="'.$translationPlugin.'" />
<input type="submit" value="Download" name="translateDownload" />
<!--
<input type="submit" value="Preview" name="translatePreview" />
-->
</form>';

function calculatePercentComplete ($base, $reference)
{
	$baseCount = count ($base);
	$refCount = count ($reference);
	return (($refCount / $baseCount) * 100);
}
?>
