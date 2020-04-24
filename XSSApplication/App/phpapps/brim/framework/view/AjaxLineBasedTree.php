<?php

/**
 * A generic tree that knows (using a callback and a delegate) how to
 * render a linebased-tree-based layout. This tree is generic in the
 * way that it knows  how to display items, which is not that generic
 * :-)
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2006
 * @package org.brim-project.framework
 * @subpackage view
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class AjaxLineBasedTree
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
	 */
	function AjaxLineBasedTree ($theDelegate, $theConfiguration)
	{
		$this->delegate=$theDelegate;
		$this->resultString = "<!-- Brim - AjaxLineBasedTree -->";
		$this->configuration = $theConfiguration;
	}

	/**
	 * Generates the HTML code to display the tree
	 *
	 * @param string userId the user id
	 * @param object root the item that is the root
	 * @param array items the items to display
	 * @return string the tree rendered as html code
	 */
	function toHtml ($root, $items)
	{
		$this->root = $root;
		$this->resultString .= '
			<script type="text/javascript" src="ext/jQuery/jquery.tablesorter.js"></script>
			<script type="text/javascript">
			<!--
				var trashFullImage = "'.$this->configuration['icons']['fullTrash'].'";
				var trashCount = '.$this->configuration['trashCount'].';
				$(document).ready (
					function ()
					{
						zebraItems ();
					});
			// -->
			</script>
		';
		$this->resultString .= $this->delegate->showPluginIcons ($this->configuration);
		$this->resultString .= $this->delegate->showRoot ($root, $this);
		$this->showItems ($items);
		return $this->resultString;
	}


	/**
	 * Builds up html code to display the specified items
	 *
	 * @private
	 * @uses indent
	 * @param array items the items to show
	 */
	function showItems ($items)
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
			if (isset ($items[$i]))
			{
				if ($items[$i]->isParent ())
				{
					$parents[] = $items[$i];
				}
				else
				{
					$children[] = $items[$i];
				}
			}
		}

		$this->resultString .= '<div id="folderSection">';
		if (count ($parents) > 0)
		{
			for ($j=0; $j<count($parents); $j++)
			{
				$this->resultString .= $this->delegate->drawFolder
					($parents[$j], false, $this, 0, $this->resultString);
			}
		}
		$this->resultString .= '</div>';

		$this->resultString .= '<div id="nodeSection">';
		$numChildren = count($children);
		if ($numChildren > 0)
		{
			$this->resultString .= '
				 <table border="0" 
				 	width="100%" 
					cellspacing="0" cellpadding="2" 
					class="zebraStripe sortableTable">';
			$this->resultString .= '<thead>';
			$this->resultString .= $this->delegate->drawLinesHeader ($children[0]->parentId);
			$this->resultString .= '</thead>';
			// draw the children
			$this->resultString .= '<tbody>';
			for ($k=0; $k<$numChildren; $k++)
			{
				$currentChild = $children[$k];
				$this->resultString .= $this->delegate->drawNode
					($currentChild, ($k+1==$numChildren), $this, 0);
			}
			$this->resultString .= '</tbody>';
			$this->resultString .= '
				</table>';
		}
		$this->resultString .= '</div>';
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
