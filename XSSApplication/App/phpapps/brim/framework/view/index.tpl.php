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
//echo $renderObjects;
?>

<!--
	Display a short overview of each plugin
-->
<h1><?php echo $dictionary['dashboard'] ?></h1>
<?php
	//
	// Do not remove this message, it will lead to trouble for those with all
	// plugins enabled, but have a low screen resolution
	//
	if ($_SESSION['brimTemplate'] == 'oerdec' || $_SESSION['brimTemplate'] == 'barry')
	{
		echo ('<p class="tip"><b>'.$dictionary['attentionTemplate'].'</b></p>');
	}
?>
<?php
	if (isset ($tip))
	{
		echo ('<p class="tip"><b>'.$dictionary['tip'].':</b><br />'.$tip.'</p>');
	}
?>

<table border="0" cellspacing="10" cellpadding="10">
<?php
	$i=0;
	// Show three TDs per TR
	$rows = array_chunk ($dashboard, 3, true);
	// Render one row
	foreach ($rows as $row)
	{
		echo ('<tr valign="top">');
		foreach ($row as $current)
		{
			echo ('<td width="300">');

			// Thr dashboards parameters
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
				if (isset ($contents))
				{
					foreach ($contents as $content)
					{
						echo ('<tr><td>');
						echo ('<a href="'.$controller.'&amp;action='.$action.'&amp;itemId='.$content->itemId).'" ';
						if (isset ($current ['dashboardAdditionalLinkParameters']))
						{
							echo ' '.$current ['dashboardAdditionalLinkParameters'];
						}
						echo ('>'.$content->name.'</a>');
						echo ('</td></tr>');
					}
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
