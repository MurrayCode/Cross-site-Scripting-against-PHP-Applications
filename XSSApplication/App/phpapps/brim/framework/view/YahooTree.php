<?php

/**
 * A generic tree that knows (using a callback and a delegate) how to
 * render a yahoo-tree-based layout. This tree is generic in the way
 * that it knows how to display items, which is not that generic :-)
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.framework
 * @subpackage view
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class YahooTree
{
	/**
	 * The root item
	 *
	 * @private
	 * @var object root
	 */
	var $root;

	/**
	 * The delegate that knows how to render folders and nodes
	 *
	 * @private
	 * @var object delegate
	 */
	var $delegate;

	/**
	 * The string that will contain the fully rendered tree as HTML
	 *
	 * @private
	 * @var string resultString
	 */
	var $resultString;

	/**
	 * The configuration used for rendering this tree.
	 *
	 * @private
	 * @var array configuration
	 */
	var $configuration;

	/**
	 * Default constructor. The callback object must implement the
	 * function 'getChildren (userId, itemId)', 'getItem (userId, itemId)'
	 *
	 * @param object theConfiguration
	 * @param object theDelegate
	 */
	function YahooTree ($theDelegate, $theConfiguration)
	{
		$this->delegate=$theDelegate;
		$this->resultString = "<!-- Brim - YahooTree -->";
		$this->configuration = $theConfiguration;
	}

	/**
	 * Generates the HTML code to display the tree
	 *
	 * @param string userId the user id
	 * @param object root the item that is the root
	 * @param array items the items to display
	 *
	 * @return string the tree rendered as html code
	 */
	function toHtml ($root, $items)
	{
		$this->root = $root;

		$this->resultString .= $this->delegate->showRoot ($root, $this);

		$this->resultString .= '<table cellpadding="0" valign="top" cellspacing="3" width="100%" border="0">';
		$this->showItems ($items, $this->configuration ['numberOfColumns']);
		$this->resultString .= '</table>';

		return $this->resultString;
	}


	/**
	 * Builds up html code to display the specified items
	 *
	 * @private
	 * @uses indent
	 * @param array items the items to show
	 */
	function showItems ($items, $numberOfColumns)
	{
		$previousIsFolder=true;
		$seperatorIsDrawn = false;

		$parents = array ();
		$children = array ();
		//
		// copy the array containing items in two seperate arrays.
		// One for the children and one for the parents. Treat them
		// seperately
		//
		for ($i=0; $i<count($items); $i++)
		{
			if (method_exists ($items[$i], 'isParent') && $items[$i]->isParent ())
			{
				$parents[] = $items[$i];
			}
			else
			{
				$children[] = $items[$i];
			}
		}
		for ($j=0; $j<count($parents); $j++)
		{
			if ($j % $numberOfColumns == 0)
			{
				$this->resultString .= '<tr valign="top">';
			}
			$this->resultString .= "<td>";
			$this->resultString .= $this->delegate->drawFolder
				($parents[$j], false, $this, 0, $this->resultString);
			$this->resultString .= "</td>";
			if (($j % $numberOfColumns)+1 == 0)
			{
				$this->resultString .= '</tr>';
			}
		}
		//
		// and fill up empty cell if
		//
		if (($j % $numberOfColumns) != 0)
		{
			$this->resultString .= '<td></td></tr>';
		}
		//
		// draw a seperator, but only if we have parents
		//
		if (count ($parents) > 0)
		{
			$this->resultString .= '<tr valign="top"><td colspan="'.$numberOfColumns.'"><hr /></td></tr>';
		}

		//
		// draw the children
		//
		for ($k=0; $k<count($children); $k++)
		{
			if ($k % $numberOfColumns == 0)
			{
				$this->resultString .= '<tr valign="top">';
			}
			$this->resultString .= "<td>";
			$this->resultString .= $this->delegate->drawNode
				($children[$k], false, $this, 0, $this->resultString);
			$this->resultString .= "</td>";
			if (($k % $numberOfColumns) +1 == 0)
			{
				$this->resultString .= '</tr>';
			}
		}
		//
		// and fill up empty cell if
		//
		if (($k % $numberOfColumns) != 0)
		{
			$this->resultString .= '<td></td></tr>';
		}
	}

	/**
	 * Sets the icons that will be used to display the tree.
	 * The icons must have the following keys:
	 * 'bar', 'up', 'minus', 'folder_open', 'corner', 'plus', 'tee',
	 * 'folder_closed', 'node', 'before_display', 'after_display'.
	 * If the icons are images, the html code for the images must be provided
	 * as well...
	 *
	 * @param hashtable theIcons
	 */
	 function setIcons ($theIcons)
	 {
	 	$this->delegate->setIcons ($theIcons);
	 }

	/**
	 * Sets the items that are currently expanded.
	 * The string contains a comma seperated value list of ID's
	 *
	 * @param string expandedString
	 */
	function setExpanded ($expandedString)
	{
	}
}
?>
