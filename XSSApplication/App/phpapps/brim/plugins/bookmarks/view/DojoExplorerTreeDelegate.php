<?php

require_once ('framework/util/StringUtils.php');
require_once ('framework/view/TreeDelegate.php');
require_once ('framework/util/BrowserUtils.php');
require_once ('plugins/bookmarks/util/BookmarkUtils.php');

/**
 * Used in combination with the Tree render classes
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.plugins.bookmarks
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class DojoExplorerTreeDelegate extends TreeDelegate
{
	var $bookmarkUtils;
	var $browserUtils;

	/**
	 * Default constructor
	 *
	 * @param array theConfiguration the configuration
	 * @param string theCallbackURL
	 */
	function DojoExplorerTreeDelegate ($theConfiguration)
	{
		parent::TreeDelegate ($theConfiguration);
		$this->bookmarkUtils = new BookmarkUtils ();
		$this->browserUtils = new BrowserUtils ();
	}

	/**
	 * Builds up html code to display the root of the tree
	 * @private
	 *
	 * @param object item the item that is the root of the tree
	 */
	function showRoot ($item, $tree)
	{
		if (isset($item) && $item->itemId != 0)
		{
			$resultString = '<h2>'.$item->name.'</h2>';
			$resultString .= '<a href="'.$this->configuration['callback'];
			$resultString .= '?action=show&amp;parentId='.$item->parentId.'">';
			$resultString .= $this->configuration['icons']['up'];
			$resultString .= '</a>';
		}
		else
        {
			$resultString = $this->configuration['icons']['root'];
		}
		return $resultString;
	}

	/**
	 * If the specified item is a folder, draw it
	 *
	 * @private
	 * @param object item the item to draw
	 * @return string the string for this folder
	 */
	function drawFolder ($item, $tree)
	{
			/*
		$resultString  = '<a href="'.$this->configuration['callback'];
		$resultString .= '?plugin=bookmarks';
		$resultString .= '&amp;action=modify';
		$resultString .= '&amp;itemId='.$item->itemId;
		$resultString .= '">';
		$resultString .= $this->configuration['icons']['folder_closed'];
		$resultString .= '</a>';
		$resultString .= '<a href="'.$this->configuration['callback'];
		$resultString .= '?plugin=bookmarks';
		$resultString .= '&amp;action=show';
		$resultString .= '&amp;parentId='.$item->itemId;
		$resultString .= '">';
		$resultString .= $item->name;
		$resultString .= '</a>';
		return $resultString;
		*/
	}


	/**
	 * If the specified item is a node, draw it
	 *
	 * @private
	 * @param object item the item to draw
	 * @param boolean lastNode whether this node is the last one
	 * @return string the string for this node
	 */
	function drawNode ($item, $tree)
	{
			/*
		$resultString = '<a href="';
		$resultString .= $this->configuration['callback'];
		$resultString .= '?action=modify&amp;itemId=';
		$resultString .= $item->itemId;
        if (isset($tree->root))
		{
			$resultString .= '&amp;parentId='.$tree->root->itemId;
		}
        $resultString .= '">';
		if (isset ($item->favicon) && $item->favicon != '' &&
			$_SESSION['bookmarkFavicon']=='1' &&
			!$this->browserUtils->browserIsExplorer())
		{
			$resultString .= '<img src="data:image/x-icon;base64,'.$item->favicon.'" ';
			$resultString .= 'height="16" width="16" border="0">';
		}
		else
		{
			$resultString .= $this->configuration['icons']['node'];
		}
		$resultString .= '</a>';
		//
		// Visibility icon
		//
		$resultString .= "&nbsp;";
		if ($item->visibility=='public')
		{
			$resultString .= $this->configuration['icons']['unlocked'];
		}
		else
		{
			$resultString .= $this->configuration['icons']['locked'];
		}
		$resultString .= "&nbsp;";
		//
		// End visibility icon
		//
		$resultString .= "<a href='";
		$resultString .= $this->configuration['callback'];
		$resultString .= '?action=showBookmark&amp;itemId=';
		$resultString .= $item->itemId . "' ";
		//
		// Overlib
		//
		if ($this->configuration['overlib'])
		{
			$resultString .= $this->overLib ($item->name,
				$this->stringUtils->truncate ($item->locator, 20).
					'<br />'.$item->description);
		}
		if (
			isset ($this->configuration['bookmarkNewWindowTarget']) &&
			($this->configuration['bookmarkNewWindowTarget'] == '_blank')
			)
		{
			$resultString .= ' target="'.$this->configuration['bookmarkNewWindowTarget'].'" ';
		}
		$resultString .= 'alt="'. $this->stringUtils->gpcAddSlashes ($this->stringUtils->urlEncodeQuotes($item->name)) . '">';
		$resultString .= $item->name;
		$resultString .= '</a>';
		return $resultString;
		*/
	}
}
?>