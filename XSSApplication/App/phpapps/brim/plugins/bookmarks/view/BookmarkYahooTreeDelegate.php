<?php

require_once ('framework/util/StringUtils.php');
require_once ('framework/view/TreeDelegate.php');
require_once ('framework/util/BrowserUtils.php');
/**
 * Used in combination with the Tree renderer class
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
class BookmarkYahooTreeDelegate extends TreeDelegate
{
	var $browserUtils;
	/**
	 * Default constructor
	 */
	function BookmarkYahooTreeDelegate ($theConfiguration)
	{
		parent::TreeDelegate ($theConfiguration);
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
			$resultString  = '<h2>'.$item->name.'</h2>';
			$resultString .= '<a href="'.$this->configuration['callback'];
			$resultString .= '&amp;action=show&amp;parentId='.$item->parentId.'">';
			$resultString .= $this->configuration['icons']['up'];
			$resultString .= '</a><br />';
		}
		else
		{
			$resultString = '';
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
		$resultString = '';
		//
		// Folder icon
		//
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '<a href="';
			$resultString .= $this->configuration['callback'];
			$resultString .= '&amp;action=modify&amp;itemId=';
			$resultString .= $item->itemId;
	        $resultString .= '&amp;parentId=';
			$resultString .= $item->parentId . '">';
			$resultString .= $this->configuration['icons'] ['folder_closed'];
			$resultString .= '</a>';
		}
		else 
		{
			$resultString .= $this->configuration['icons'] ['folder_closed'];
		}
		//
		// Name
		//
		$resultString .= '<a href="';
		$resultString .= $this->configuration['callback'];
		$resultString .= '&amp;action=show&amp;parentId=';
		$resultString .= $item->itemId . '">';
		$resultString .= $item->name;
		$resultString .= '</a>';
		$resultString .= '&nbsp;';
		$resultString .= '&nbsp;';
		$resultString .= '<br />';
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
		$resultString = '';
		//
		// Icon
		//
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString  = '<a href="';
			$resultString .= $this->configuration['callback'];
			$resultString .= '&amp;action=modify&amp;itemId=';
		    $resultString .= $item->itemId;
		    $resultString .= '&amp;parentId=';
			$resultString .= $item->parentId . '">';
		}
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
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '</a>';
		}
		//
		// Link with overlib, alt tag and name
		//
		$resultString .= "<a target='_new' href='";
		$resultString .= $this->configuration['callback'];
		$resultString .= '&amp;action=showBookmark&amp;itemId=';
		$resultString .= $item->itemId . "' ";
		//
		// Optional Overlib
		//
		if ($this->configuration['overlib'])
		{
			$resultString .= $this->overLib ($item->name, $item->locator.'<br />'.$item->description);
		}
		if (
			isset ($this->configuration['bookmarkNewWindowTarget']) &&
			($this->configuration['bookmarkNewWindowTarget'] == '_blank')
			)
		{
			$resultString .= ' target="'.$this->configuration['bookmarkNewWindowTarget'].'" ';
		}
		//
		// ALT tag
		//
		
		// remove the alt tag because it fails html validation
		//$resultString .= 'alt="'. $this->stringUtils->urlEncodeQuotes
		//	($this->stringUtils->gpcAddSlashes ($item->name)) . '">';
		$resultString .= '>';
		$resultString .= $item->name;
		$resultString .= '</a>';
		$resultString .= '<br />';
		return $resultString;
	}
}
?>
