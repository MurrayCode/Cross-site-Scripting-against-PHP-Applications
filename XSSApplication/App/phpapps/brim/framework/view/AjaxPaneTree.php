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
class AjaxPaneTree
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
	 * Current level of indentation
	 *
	 * @private
	 * @var integer indent
	 */
	var $indent;

	/**
	 * The string that will contain the fully rendered tree as HTML
	 *
	 * @private
	 * @var string resultString
	 */
	var $resultString;

	/**
	 * The id's of the items that are currently expanded
	 *
	 * @private
	 * @var array an array of item id's that are expanded
	 */
	var $expanded;

	/**
	 * The configuration used for rendering this tree.
	 *
	 * @private
	 * @var array configuration
	 */
	var $configuration;

	/**
	 * Default constructor. The callback object must implement the
	 * function 'getChildren (userId, itemId)',
	 * 'getItem (userId, itemId)'
	 *
	 * @param array theConfiguration
	 * @param object theDelegate
	 */
	function AjaxPaneTree ($theDelegate, $theConfiguration)
	{
		$this->delegate=$theDelegate;
		$this->indent=0;
		$plus = 'framework/view/pics/tree/shaded_plus.gif';
		$minus = 'framework/view/pics/tree/shaded_minus.gif';
		$space = 'framework/view/pics/tree/blank.gif';
		$blank = 'framework/view/pics/tree/blank.gif';
		$folder_closed = 'framework/view/pics/tree/gnome_folder_closed.gif';
		$folder_open = 'framework/view/pics/tree/gnome_folder_open.gif';
		echo '
<style type="text/css" media="all">
.myTree,
.myTree ul
{
    list-style: none;
    padding-left: 22px;
}
.expandImage
{
    margin-right: 4px;
}
.folderImage
{
}
.textHolder
{
    height: 16px;
    line-height: 16px;
    padding-left: 6px;
}
a
{
	text-decoration:none;
	color:#333;
}
span.dropOver
{
    background-color: #ddd;
    font-size: 16px;
    height: 16px;
    color: #fff;
    line-height: 16px;
    padding-left: 6px;
}
.treeItem
{
    list-style: none;
    width: auto;
}
</style>';

		

		echo "<!-- Brim - Tree -->";

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
	function toHtml ($root, $items, $return=false)
	{
		$this->root = $root;
		echo '
			<script type="text/javascript">
			<!--
				var trashFullImage = "'.$this->configuration['icons']['fullTrash'].'";
				var trashCount = '.$this->configuration['trashCount'].';
			// -->
			</script>
		';
		if ($return)
		{
			$this->resultString .= $this->delegate->showPluginIcons ($this->configuration);
			$this->resultString = $this->delegate->showRoot ($root, $this);
			$this->resultString .= $this->showItems ($items, true);
			return $this->resultString;
		}
		else
		{
			echo $this->delegate->showPluginIcons ($this->configuration);
			echo $this->delegate->showRoot ($root, $this);
			$this->showItems ($items);
		}
	}


	/**
	 * Builds up html code to display the specified items
	 *
	 * @private
	 * @uses indent
	 * @param array items the items to show
	 */
	function showItems ($items, $return=false)
	{
		$resultString = '';
		$folders = array ();
		$nodes = array ();
		for ($i=0; $i<count($items); $i++)
		{
			if ($items[$i]->isParent ())
			{
				$folders [] = $items[$i];
			}
			else
			{
				$nodes [] = $items [$i];
			}
		}
		$resultString .= '<div id="folderSection">';
		foreach ($folders as $folder)
		{
			$resultString .= 
				$this->delegate->drawFolder
					($folder, false, $this, $this->indent,
						$this->resultString);
		}
		$resultString .= '</div>';
		$resultString .= '<div id="nodeSection">';
		foreach ($nodes as $node)
		{
			$resultString .= $this->delegate->drawNode
				($node, true, $this, $this->indent);
		}
		$resultString .= '</div>';
		if ($return)
		{
			return $resultString;
		}
		else
		{
			echo $resultString;
		}
	}



	/**
	 * Sets the icons that will be used to display the tree.
	 * The icons must have the following keys:
	 * 'bar', 'up', 'minus', 'folder_open', 'corner', 'plus', 'tee',
	 * 'folder_closed', 'node', 'before_display', 'after_display'.
	 * If the icons are images, the html code for the images must
	 * be provided as well...
	 *
	 * @param hashtable theIcons
	 */
	 function setIcons ($theIcons)
	 {
	 	$this->delegate->setIcons ($theIcons);
	 }

	/**
	 * returns whether the specified itemId is expanded
	 *
	 * @private
	 * @param integer itemId the identifier which needs to be checked
	 * @return boolean true if this id is in the expanded list,
	 * false otherwise
	 */
	function isExpanded ($itemId)
	{
		if ($this->expanded == null || count ($this->expanded) == 0)
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
			//
			// yep, in the expanded list
			//
			if ($val == $itemId)
			{
				return true;
			}
		}
	    return false;
	}

	/**
	 * Returns the list of expanded item id's without the
	 * provided itemId
	 *
	 * @param integer itemId the identifier that needs to be
	 * removed from the expanded list
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
	 *
	 * @param string expandedString
	 */
	function setExpanded ($expandedString)
	{
		$this->expanded=explode(',', $expandedString);
	}
}
?>
