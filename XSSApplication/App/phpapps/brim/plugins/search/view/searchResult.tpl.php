<?php
/**
 * The template file to search for strings in all plugins
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - August 2006
 * @package org.brim-project.plugins.search
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

$pluginNames = array_keys ($searchResult);
$oddEven = 0;
//
// SearchFields are defined in the confirguration/hookup.php file and are
// different for each plugin
//
$searchFields = array ();
$foundResults = false;
foreach ($pluginNames as $pluginName)
{
	$searchFields = $plugins[$pluginName]['searchFields'];

	// Get the right dictionary
	$controllerName = $plugins[$pluginName]['controllerName'];
	require_once 'plugins/'.$pluginName.'/'.$plugins[$pluginName]['controller'];
	$controller = new $controllerName();
	$dictionary = $controller->getDictionary();

	if (count ($searchResult[$pluginName]) > 0)
	{
		$foundResults = true;
		echo '<h1>'.$dictionary[$pluginName].'</h1>';
		echo '<table width="100%">';
		
		// print the headers
		echo '<tr><td>&nbsp;</td>';
		foreach ($searchFields as $searchField)
		{
			//
			// Display all the fields that we were searching for (as defined in the
			// hookup-configuration file
			//
			echo '<td>';
			if (isset ($dictionary[$searchField]))
			{
				echo $dictionary[$searchField];
			}
			else
			{
				//
				// Failsave. This shouldn't really be here...
				// On the other hand... how should we handle this properly?
				//
				echo $searchField;
			}
			echo '</td>';
		}
		echo '</tr>';
		
		foreach ($searchResult[$pluginName] as $item)
		{
			echo '<tr class="';
			if ($oddEven++%2==0) { echo "odd"; } else { echo "even"; }
			echo '">';
			//
			// Callback to the controller for item modification
			//
			echo '<td><a href="index.php?plugin='.$pluginName;
			echo '&amp;action=modify';
			echo '&amp;itemId='.$item->itemId.'">';
			//
			// Show the icon (folder or item)
			//
			if ($item->isParent)
			{
				echo $icons['folder_closed'];
			}
			else
			{
				echo $icons['node'];
			}
			//close the anchor tag
			echo '</a>';
			foreach ($searchFields as $searchField)
			{
				//
				// Display all the fields that we were searching for (as defined in the
				// hookup-configuration file
				//
				echo '<td><a href="index.php?plugin='.$pluginName;
				echo '&amp;action=showItem';
				if ($item->isParent)
				{
					echo '&amp;parentId='.$item->itemId.'">';
				}
				else
				{
					echo '&amp;itemId='.$item->itemId.'">';
				}
				echo $item->$searchField;
				echo '</a>';
				echo '</td>';
			}
			echo '</tr>';
		}
		echo '</table>';
	}
}
// Should have a preferences bit to display plugins with 0 results
/*if (!$foundResults)
{
	echo '<h1>'.$dictionary['noSearchResult'].'</h1>';
}*/
?>
