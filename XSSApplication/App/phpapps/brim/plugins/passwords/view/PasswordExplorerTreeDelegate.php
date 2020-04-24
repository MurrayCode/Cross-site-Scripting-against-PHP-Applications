<?php

require_once ("framework/util/StringUtils.php");
require_once ("framework/view/TreeDelegate.php");

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2004
 * @package org.brim-project.plugins.passwords
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class PasswordExplorerTreeDelegate extends TreeDelegate
{
	/**
	 * Default constructor
	 */
	function PasswordExplorerTreeDelegate ($theConfiguration)
	{
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
		if (isset ($item) && $item->itemId == 0)
		{
			$resultString = $this->configuration['icons']['root'];
		}
		else
		{
			$resultString = '<h2>'.$item->name.'</h2>';
			$resultString .= '<a href="'.$this->configuration['callback'];
			$resultString .= '&amp;action=show&amp;parentId='.$item->parentId.'">';
			$resultString .= $this->configuration['icons']['up'];
			$resultString .= '</a>';
		}
		return $resultString;
	}

	/**
	 * If the specified item is a folder, draw it
	 * @private
	 * @param object item the item to draw
	 * @param boolean isExpanded whether this folder is expanded
	 * (i.e. do the children of this folder need to be displayed as
	 * well?)
	 * @param object tree the tree that uses this class as delegate
	 * @return string the string for this folder
	 */
	function drawFolder ($item, $isExpanded, $tree, $indentLevel)
	{
		$resultString = '
								<tr><td>';

		for ($i=0; $i<$indentLevel; $i++)
		{
			$resultString .= $this->configuration['icons']['bar'];
		}

		if ($item->isParent)
		{
			//die ("Is parent. Expanded?" . $isExpanded);
		}
		if ($isExpanded)
		{
			$res = $tree->createExpandedListWithout ($item->itemId);

			$resultString .= '<a href="';
			$resultString .= $this->configuration['callback'];
			$resultString .= '&amp;expand=' . implode (',', $res);
			$resultString .= '&amp;parentId='.$tree->root->itemId;
			$resultString .= '">';
			$resultString .= $this->configuration['icons']['minus'];
			$resultString.= '</a>';

			$resultString.= '<a href="';
			$resultString .= $this->configuration['callback'];
            $resultString .= '&amp;action=modify&amp;itemId='.$item->itemId;
        	$resultString .= '&amp;parentId='.$tree->root->itemId.'">';
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
			if (isset ($item->parentId))
			{
				$resultString .= '<a href="';
				$resultString .= $this->configuration['callback'];
				$resultString .= '&amp;expand=' . $linkExpand;
				//$resultString .= '&amp;parentId='.$item->parentId;
				$resultString .= '">';
			}
			else
			{
				$resultString .= '<a href="';
				$resultString .= $this->configuration['callback'];
				$resultString .= '&amp;expand='.$linkExpand.'">';
			}
			$resultString .= $this->configuration['icons']['plus'];
			$resultString .= '</a>';
			$resultString .= '<a href="';
			$resultString .= $this->configuration['callback'];
            $resultString .= '&amp;action=modify&amp;itemId='.$item->itemId;
        	$resultString .= '&amp;parentId='.$tree->root->itemId.'">';
			$resultString .= $this->configuration['icons']['folder_closed'];
			$resultString .= '</a>';
		}
		$resultString .= '<a href="';
		$resultString .= $this->configuration['callback'];
		$resultString .= '&amp;action=show&amp;parentId=';
		$resultString .= $item->itemId . '">';
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
								<tr><td>';
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
/*
		$resultString .= '<a href="';
		$resultString .= $this->configuration['callback'];
		$resultString .= '&amp;action=modify&amp;itemId=';
		$resultString .= $item->itemId . '">';
		$resultString .= $this->configuration['icons']['node'];
		$resultString .= '</a>';
*/

		//$resultString .= '<a href="javascript:passPhrase (\'modify\', \''.$item->name.'\', '.$item->itemId.')">';
		$resultString .= '<a href="';
		$resultString .= $this->configuration['callback'];
		$resultString .= '&amp;action=modifyAskPassphrase&amp;itemId='.$item->itemId;
		$resultString .= '">';
		$resultString .= $this->configuration['icons']['node'];
		$resultString .= '</a>';
		//$resultString .= '<a href="javascript:passPhrase (\'showItem\', \''.$item->name.'\', '.$item->itemId.')">';
		$resultString .= '<a href="';
		$resultString .= $this->configuration['callback'];
		$resultString .= '&amp;action=showAskPassphrase&amp;itemId='.$item->itemId;
		$resultString .= '">';
		$resultString .= $item->name;
		$resultString .= '</a>';
		$resultString .= '</td></tr>';
		return $resultString;
	}
}
?>