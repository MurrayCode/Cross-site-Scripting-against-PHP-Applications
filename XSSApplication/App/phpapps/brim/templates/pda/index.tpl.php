<?php

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.templates
 * @subpackage pda
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

echo $renderObjects;
?>

<!--
	Display a short overview of each plugin
-->
<h1><?php echo $dictionary['dashboard'] ?></h1>
<table border="0" width="*" cellspacing="10" cellpadding="10">
<?php
	$i=0;
	// Show one TDs per TR
	$rows = array_chunk ($dashboard, 1, true);
	// Render one row
	foreach ($rows as $row)
	{
		echo ('<tr valign="top">');
		foreach ($row as $current)
		{
			echo ('<td width="100">');
			// The dashboards parameters
			$name = $current['name'];
			$content = $current['content'];
			$contents = $current['contents'];
			$controller = $current['controller'];
			$title = $current['title'];
			$action = $current['action'];

			// Do not show if no action is attached
			if (isset ($action))
			{
				echo ('<table>');
				echo ('<tr><td>');
				echo ('<h2>');
				echo ('<a href="'.$controller.'">');
				echo $dictionary[$name];
				echo ('</a></h2>');
				echo ('</td></tr><tr><td>');
				echo ('<b>'.$dictionary[$title].'</b>');
				echo ('</td></tr>');
				foreach ($contents as $content)
				{
					echo '<tr><td>';
					echo '<a href="'.$controller;
					echo '?action='.$action;
					echo '&amp;itemId='.$content->itemId;
					echo '">'.$content->name.'</a>';
					echo '</td></tr>';
				}
				echo ('</table>');
			}
			echo ('</td>');
		}
		echo ('</tr>');
	}
?>
</table>
<?php
//
// The about notice
//
echo $about;
?>