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
		include 'framework/configuration/languages.php';
		include 'framework/configuration/plugins.php';
		require_once 'framework/view/Widget.php';

		$resultString = '';
		$baseCount = 0;
		$resultString .= '<h1>'.$dictionary['stats'].'</h1>';

		for ($i=0; $i<count($languages); $i++)
		{
			$language =& $languages[$i];
			if ($language[2])
			{
				$language[3] = calculateNumberOfItems
					($language, $plugins);
				if ($language[0] == 'en')
				{
					$baseCount = $language[3];
				}
			}
		}

		$resultString .= '<table cellpadding="3" cellspacing="3">';
		for ($i=0; $i<count($languages); $i++)
		{
			$language =& $languages[$i];
			if ($language[2])
			{
				$stats = array ();
				$stats ['total'] = $baseCount;
				$stats ['completed'] = $language[3];
				$resultString .= '<tr>';
				$resultString .= '<td>';
				$resultString .= $language[0];
				$resultString .= '</td>';
				$resultString .= '<td>';
				$resultString .= '<img src="framework/view/pics/flags/flag-'.$language[0].'.png">';
				$resultString .= '</td>';
				$resultString .= '<td>';
				$resultString .= $language[1];
				$resultString .= '</td>';
				$resultString .= '<td>';
				$resultString .= Widget::percentBar($stats);
				$resultString .= '</td>';
				$resultString .= '</tr>';
			}
		}
		$resultString .= '</table>';
		echo $resultString;

		function calculateNumberOfItems ($language, $plugins)
		{
			$number = 0;
			foreach ($plugins as $plugin)
			{
		//		unset ($dictionary);
				$dictionary = array ();
				$name = 'plugins/'.$plugin['name'].
					'/i18n/dictionary_'.$language[0].'.php';
				if (file_exists ($name))
				{
					include ($name);
					$number += count ($dictionary);
				}
			}
			unset ($dictionary);
			include 'framework/i18n/dictionary_'.$language[0].'.php';
			$number += count ($dictionary);
			$fileName = 'plugins/translate/i18n/dictionary_'.$language[0].'.php';
			if (file_exists ($fileName))
			{
				unset ($dictionary);
				include ($fileName);
				$number += count ($dictionary);
			}
			$fileName = 'plugins/sysinfo/i18n/dictionary_'.$language[0].'.php';
			if (file_exists ($fileName))
			{
				unset ($dictionary);
				include ($fileName);
				$number += count ($dictionary);
			}
			return $number;
		}

		/*
		function prettyPrint ($max, $effective)
		{
			$translated = floor(($effective/$max)*100);
			// Bug hiding... what happens if there are extra
			// translations that are not part of the framework?
			if ($translated > 100)
			{
				$translated = 100;
			}
			return '<img src="plugins/translate/view/pics/translated.gif" height="16" width="'.$translated.'"><img src="plugins/translate/view/pics/untranslated.gif" height="16" width="'.(100-$translated).'">&nbsp;&nbsp;'.$translated.'%';
		}
		*/
?>
