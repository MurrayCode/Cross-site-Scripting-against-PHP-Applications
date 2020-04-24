<?php

require_once ("framework/util/StringUtils.php");
require_once ('framework/view/TreeDelegate.php');

/**
 * Used in combination with the PHPItemTree class
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
class MoveMultipleItemsExplorerTreeDelegate extends TreeDelegate
{
	/**
	 * Default constructor
	 */
	function MoveMultipleItemsExplorerTreeDelegate ($theConfiguration)
	{
		parent::TreeDelegate ($theConfiguration);
	}

	/**
	 * Builds up html code to display the root of the tree
	 *
	 * @private
	 * @param object item the item that is the root of the tree
	 */
	function showRoot ($item, $tree)
	{
		$this->itemToMove = $item;
		$resultString  = '<h3><a href="'.$this->configuration['callback'];
		$resultString .= '&amp;action=moveMultipleItemsPost&amp;parentId=0';
		$resultString .= '&itemIds='.$this->configuration['multipleItems'].'">'.$this->configuration['icons']['root'].'</h3>';
		return $resultString;
	}

	/**
	 * If the specified item is a folder, draw it
	 *
	 * @private
	 * @param object item the item to draw
	 * @param boolean isExpanded whether this folder is expanded (i.e. do the
	 * children of this folder need to be displayed as well?)
	 * @param object tree the tree that uses this class as delegate
	 * @return string the string for this folder
	 */
	function drawFolder ($item, $isExpanded, $tree, $indentLevel)
	{
		//
		// do not include the item itself incase the item is a folder
		// we do not want to have a folder which is its own parent
		//
		if (isset($this->itemToMove) && ($item->itemId == $this->itemToMove->itemId))
		{
			return '';
		}
		$resultString = '
								<tr><td>';

		for ($i=0; $i<$indentLevel; $i++)
		{
			$resultString .= $this->configuration['icons']['bar'];
		}

		$resultString .= $this->configuration['icons'] ['minus'];
		$resultString .= $this->configuration['icons'] ['folder_open'];
		$resultString .= '<a href="'.$this->configuration['callback'];
		$resultString .= '&amp;action=moveMultipleItemsPost&amp;parentId='.$item->itemId;
		$resultString .= '&itemIds='.$this->configuration['multipleItems'].'">'.$item->name;
		$resultString .= '</td></tr>';

		return $resultString;
	}


	/**
	 * If the specified item is a node, draw it
	 *
	 * @private
	 * @param object item the item to draw
	 * @param boolean lastNode whether this node is the last one
	 * @return string the string for this node
	 */
	function drawNode ($item, $lastNode, $tree, $indentLevel)
	{
		//
		// no nodes need to be drawn when moving an item, we can only move
		// to folders
		//
		return '';
	}
}
?>