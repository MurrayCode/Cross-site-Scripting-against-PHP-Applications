<?php

/**
 * A generic tree that knows (using a callback and a delegate) how to
 * render a tree-based layout. This tree is generic in the way that it
 * knows how to display items, which is not that generic :-)
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - June 2004
 * @package org.brim-project.framework
 * @subpackage view
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class OverviewTree
{
	/**
	 * The root item
	 * @private
	 * @var object root
	 */
	var $root;

	/**
	 * The delegate that knows how to render folders and nodes
	 * @private
	 * @var object delegate
	 */
	var $delegate;

	/**
	 * The string that will contain the fully rendered tree as HTML
	 * @private
	 * @var string resultString
	 */
	var $resultString;

	/**
	 * The id's of the items that are currently expanded
	 * @private
	 * @var array an array of item id's that are expanded
	 */
	var $expanded;

	/**
	 * The configuration used for rendering this tree.
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
	function OverviewTree ($theDelegate, $theConfiguration)
	{
		$this->delegate=$theDelegate;
		$this->identLevel = 0;
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
		$this->resultString = "<!-- Brim - OverviewTree -->";
		$this->resultString .= $this->delegate->showRoot ($root, $this);

		$this->resultString .= '<table border="0" width="100%" class="overviewTree">';
		$this->resultString .='<tr><td>';
		$this->showItems ($items, 0);
		$this->resultString .='</td></tr>';
		$this->resultString .= '</table>';
		$this->resultString .= "<!-- END Brim - OverviewTree -->";

		return $this->resultString;
	}


	/**
	 * Builds up html code to display the specified items
	 * @private
	 * @uses indent
	 *
	 * @param array items the items to show
	 */
	function showItems ($items, $identLevel)
	{
		if ($identLevel % 2 == 0)
		{
			$class="overviewTreeEven";
		}
		else
		{
			$class="overviewTreeOdd";
		}
		for ($i=0; $i<count($items); $i++)
		{
			if ($items[$i]->isParent ())
			{
				// Display parents (folders)
				if ($this->isExpanded ($items[$i]->itemId))
				{
					$this->resultString .= '
					<table border="0" cellpadding="0" cellspacing="0" width="100%" class="'.$class.'">
						<tr class="'.$class.'">
							<td class="'.$class.'">';
					$this->resultString .= $this->delegate->drawFolder
						($items[$i], true, $this, $identLevel);
					$identLevel++;
					$this->showItems ($items[$i]->getChildren (),
						$identLevel);
					$this->resultString .= '
							</td>
						</tr>
					</table>';
					$identLevel--;
				}
				else
				{
					$this->resultString .= $this->delegate->drawFolder
						($items[$i], false, $this, $identLevel,
						$this->resultString);
				}
			}
			else
			{
				$this->resultString .= $this->delegate->drawNode
					($items[$i], false, $this, $identLevel);
			}
		}
	}



	/**
	 * Sets the icons that will be used to display the tree.
	 * The icons must have the following keys:
	 * 'bar', 'up', 'minus', 'folder_open', 'corner', 'plus', 'tee',
	 * 'folder_closed', 'node', 'before_display', 'after_display'.
	 * If the icons are images, the html code for the images must be
	 * provided as well...
	 *
	 * @param hashtable theIcons
	 */
	 function setIcons ($theIcons)
	 {
	 	$this->delegate->setIcons ($theIcons);
	 }

	/**
	 * returns whether the specified itemId is expanded
	 * @private
	 *
	 * @param integer itemId the identifier which needs to be checked
	 * @return boolean <code>true</code> if this id is in the expanded
	 * list, <code>false</code> otherwise
	 */
	function isExpanded ($itemId)
	{
		if ($this->expanded == null
			|| count ($this->expanded) == 0
		)
		{
			return false;
		}
		if (count ($this->expanded == 1) && $this->expanded[0] == "*")
		{
			return true;
		}
		reset($this->expanded);
		while (list ($key, $val) = each($this->expanded))
		{
			// yep, in the expanded list
			if ($val == $itemId)
			{
				// we might want to remove it from
				// the expanded list and put it in
				// the parsedExpanded list
				return true;
			}
		}
	    return false;
	}

	/**
	 * Returns the list of expanded item id's without the provided
	 * itemId
	 * @param integer itemId the identifier that needs to be removed
	 * from the expanded list
	 * @return array the expanded items
	 */
	function createExpandedListWithout ($itemId)
	{
        reset($this->expanded);
        $newExpanded = array ();
		while (list ($key, $val) = each($this->expanded))
		{
			if (isset ($val) && $val != $itemId && $val != '*')
			{
				$newExpanded [] = $val;
			}
		}
		return $newExpanded;
	}

	/**
	 * Sets the items that are currently expanded.
	 * The string contains a comma seperated value list of ID's
	 * @param string expandedString
	 */
	function setExpanded ($expandedString)
	{
		$this->expanded=explode(',', $expandedString);
	}
}
?>