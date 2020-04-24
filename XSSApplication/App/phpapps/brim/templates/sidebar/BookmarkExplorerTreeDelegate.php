<?php

include_once ("framework/util/StringUtils.php");
require_once ('framework/view/TreeDelegate.php');

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.templates
 * @subpackage sidebar
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class BookmarkExplorerTreeDelegate extends TreeDelegate
{
	/**
	 * String utilities
	 * @private
	 * @var object stringUtils
	 */
	var $stringUtils;

	/**
	 * Icons in an hashtable. The icons must have the following keys:
	 * 'bar', 'up', 'minus', 'folder_open', 'corner', 'plus', 'tee',
	 * 'folder_closed', 'node', 'before_display', 'after_display'
	 * @private
	 * @var hashtable icons
	 */
	//var $icons;

	/**
	 * The URL to the callback, typically the controller
	 * @private
	 * @var string callbackURL
	 */
	//var $callbackURL;

	/**
	 * The dictionary that is used
	 * @private
	 * @var array dictionary
	 */
	//var $dictionary;

	/**
	 * Default constructor
	 * @param array theIcons the icons used for display
	 * @param string theCallbackURL
	 */

	//function BookmarkExplorerTreeDelegate ($theIcons, $theCallbackURL, $theDictionary)
	function BookmarkExplorerTreeDelegate ($theConfiguration)
	{
		/*
		$this->configuration['icons'] = $theIcons;
		$this->stringUtils = new StringUtils ();
		$this->configuration['callback'] = $theCallbackURL;
		$this->dictionary = $theDictionary;
		*/
		parent::TreeDelegate ($theConfiguration);
	}

	/**
	 * Builds up html code to display the root of the tree
	 * @private
	 *
	 * @param object item the item that is the root of the tree
	 */
	function showRoot ($item, $tree)
	{
		$resultString = '';
		if (isset($item))
		{
			if ($item->itemId == 0)
			{
				$resultString .= $this->configuration['icons']['root'];
			}
			else
			{
				$resultString .= '<h2>'.$item->name.'</h2>';
				$resultString .= '<a target="_self" href="'.$this->configuration['callback'];
				$resultString .= '?action=show&amp;parentId='.$item->parentId.'">';
				$resultString .= $this->configuration['icons']['up'];
				$resultString .= '</a>';
			}
		}
		return $resultString;
	}

	/**
	 * If the specified item is a folder, draw it
	 * @private
	 * @param object item the item to draw
	 * @param boolean isExpanded whether this folder is expanded (i.e. do the
	 * children of this folder need to be displayed as well?)
	 * @param object tree the tree that uses this class as delegate
	 * @return string the string for this folder
	 */
	function drawFolder ($item, $isExpanded, $tree, $indentLevel)
	{
		$resultString = '
								<tr><td nowrap="nowrap">';

		for ($i=0; $i<$indentLevel; $i++)
		{
			$resultString .= $this->configuration['icons']['bar'];
		}

		if ($isExpanded)
		{
			$res = $tree->createExpandedListWithout ($item->itemId);
			$resultString .= '<a target="_self" href="';
			$resultString .= $this->configuration['callback'];
			$resultString .= '?expand=' . implode (',', $res);
			if (isset($tree->root))
			{
				$resultString .= '&amp;parentId='.$tree->root->itemId;
			}
			$resultString .= '">';
			$resultString .= $this->configuration['icons']['minus'];
			$resultString.= '</a>';

			$resultString.= '<a href="';
			$resultString .= $this->configuration['callback'];
			$resultString .= '?action=modify&amp;itemId=';
			$resultString .= $item->itemId . '"';
			$resultString .= ' target="_content">';
			$resultString .= $this->configuration['icons']['folder_open'];
			$resultString .= '</a>';
		}
		else
		{
			// ???
			$linkExpand = "";
			// ???
			if (!$tree->isExpanded ($item->itemId) && isset ($tree->expanded))
			{
				$linkExpand .= implode(",", $tree->expanded).",".$item->itemId;
			}
			else
			{
				$linkExpand = $item->itemId;
			}
			$resultString .= '<a target="_self" href="';
			$resultString .= $this->configuration['callback'];
			$resultString .= '?expand=' . $linkExpand;
            if (isset($tree->root))
			{
				$resultString .= '&amp;parentId='.$tree->root->itemId;
			}
			$resultString .= '">';
			$resultString .= $this->configuration['icons'] ['plus'];
			$resultString .= '</a>';
			$resultString .= '<a href="';
			$resultString .= $this->configuration['callback'];
			$resultString .= '?action=modify&amp;itemId=';
			$resultString .= $item->itemId.'"';
			$resultString .= ' target=_content>';
			$resultString .= $this->configuration['icons'] ['folder_closed'];
			$resultString .= '</a>';
		}
		$resultString .= '&nbsp;<a href="';
		$resultString .= $this->configuration['callback'];
		$resultString .= '?action=show&amp;parentId=';
		$resultString .= $item->itemId;
	        $resultString .= '" target="_self">';
		$resultString .= $item->name;
		$resultString .= '</a>';

		$resultString .= '</td></tr>';
		return $resultString;
	}


	/**
	 * If the specified item is a node, draw it
	 * @private
	 * @param object item the item to draw
	 * @param boolean lastNode whether this node is the last one
	 * @return string the string for this node
	 */
	function drawNode ($item, $lastNode, $tree, $indentLevel)
	{
		$resultString = '
								<tr><td><span style="white-space: nowrap;">';
		for ($i=0; $i<$indentLevel; $i++)
		{
			$resultString .= $this->configuration['icons']['bar'];
		}
		if ($lastNode)
		{
			$resultString .= $this->configuration['icons']['corner'];
		}
		else
		{
			$resultString .= $this->configuration['icons']['tee'];
		}
		$resultString .= '<a href="';
		$resultString .= $this->configuration['callback'];
		$resultString .= '?action=modify&amp;itemId=';
		$resultString .= $item->itemId.'"';
		$resultString .= ' target=_content>';
		$resultString .= $this->configuration['icons']['node'];
		$resultString .= '</a>';

		$resultString .= "&nbsp;<a href='";
		$resultString .= $this->configuration['callback'];
		$resultString .= '?action=showBookmark&amp;itemId=';
		$resultString .= $item->itemId . "' ";


		if ($this->configuration['overlib'])
		{
			$resultString .= $this->overlib ($item->name,
				$item->locator.'<br />'.$item->description);
		}

		$resultString .= ' alt="'. $item->name;
		if (isset ($this->configuration['bookmarkNewWindowTarget']) &&
			($this->configuration['bookmarkNewWindowTarget'] == '_blank'))
		{
			$resultString .= '" target="_blank">';
		}
		else
		{
			$resultString .= '" target="_blank">';
		}
		$resultString .= $item->name;
		$resultString .= '</a>';

		$resultString .= '</span></td></tr>';
		return $resultString;
	}
}
?>