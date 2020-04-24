<?php

require_once ("framework/util/StringUtils.php");
require_once ('framework/view/TreeDelegate.php');

/**
 * Used in combination with the Tree render classes
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2004
 * @package org.brim-project.framework
 * @subpackage view
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class PublicExplorerTreeDelegate extends TreeDelegate
{
	/**
	 * The id of the root
	 * @var integer rootId
	 */
	var $rootId;

	/**
	 * Default constructor
	 *
	 * @param array theConfiguration the configuration
	 */
	function PublicExplorerTreeDelegate ($theConfiguration)
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
		$resultString = '';
		if (!isset ($item))
		{
			return $resultString;
		}
		$this->rootId = $item->itemId;
		if ($item->itemId != 0)
		{
			$resultString .= '<h2>'.$item->name.'</h2>';
			$resultString .= '<a href="'.$this->configuration['callback'];
			$resultString .= '?parentId='.$item->parentId;
			$resultString .= '&username='.$this->configuration['username'];
			$resultString .= '&amp;plugin='.$this->configuration['plugin'];
			if (isset ($tree->expanded))
			{
				$resultString .= '&amp;expand='.implode(',', $tree->expanded);
			}
			$resultString .= '">';
			$resultString .= $this->configuration['icons']['up'];
			$resultString .= '</a>';
		}
		return $resultString;
	}

	/**
	 * If the specified item is a folder, draw it
	 *
	 * @private
	 * @param object item the item to draw
	 * @param boolean isExpanded whether this folder is expanded
	 * (i.e. do the
	 * children of this folder need to be displayed as well?)
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

		if ($isExpanded)
		{
			$res = $tree->createExpandedListWithout ($item->itemId);
			$resultString .= '<a href="';
			$resultString .= $this->configuration['callback'];
			$resultString .= '?expand=' . implode (',', $res);
			$resultString .= '&amp;parentId='.$tree->root->itemId;
			$resultString .= '&username='.$this->configuration['username'];
			$resultString .= '&amp;plugin='.$this->configuration['plugin'];
			$resultString .= '">';
			$resultString .= $this->configuration['icons']['minus'];
			$resultString.= '</a>';

			$resultString .= $this->configuration['icons']['folder_open'];
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
				$resultString .= '?expand=' . $linkExpand;
				$resultString .= '&amp;parentId='.$this->rootId;
				$resultString .= '&username='.$this->configuration['username'];
				$resultString .= '&amp;plugin='.$this->configuration['plugin'];
				$resultString .= '">';
			}
			else
			{
				$resultString .= '<a href="';
				$resultString .= $this->configuration['callback'];
				$resultString .= '?expand='.$linkExpand;
				$resultString .= '&amp;parentId='.$this->rootId;
				$resultString .= '&username='.$this->configuration['username'];
				$resultString .= '&amp;plugin='.$this->configuration['plugin'];
				$resultString .= '">';
			}
			$resultString .= $this->configuration['icons'] ['plus'];
			$resultString .= '</a>';
			$resultString .= $this->configuration['icons'] ['folder_closed'];
		}
		$resultString .= '<a href="';
		$resultString .= $this->configuration['callback'];
		$resultString .= '?action=show&amp;parentId=';
		$resultString .= $item->itemId;
		$resultString .= '&username='.$this->configuration['username'];
		$resultString .= '&amp;plugin='.$this->configuration['plugin'];
		if (isset ($tree->expanded))
		{
			$resultString .= '&amp;expand='.implode (',', $tree->expanded);
		}
		$resultString .= '">';
		$resultString .= $item->name;
		$resultString .= '</a>';
		if (isset ($item->description))
		{
			$resultString .= '<br />'.$item->description;
		}
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
		$resultString .= $this->configuration['icons']['node'];

		$resultString .= '<a href="';
		$resultString .= $item->locator;
		$resultString .= '" alt="'. $this->stringUtils->gpcAddSlashes ($item->name) . '">';
		$resultString .= $this->stringUtils->gpcAddSlashes ($item->name);
		$resultString .= '</a>';
		$resultString .= '</td></tr>';
		return $resultString;
	}
}
?>
