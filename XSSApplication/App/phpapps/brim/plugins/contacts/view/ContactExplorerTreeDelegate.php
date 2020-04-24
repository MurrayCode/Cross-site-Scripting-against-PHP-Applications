<?php

require_once ("framework/util/StringUtils.php");
require_once ("framework/view/TreeDelegate.php");

include_once 'plugins/contacts/view/contactOverlib.php';

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.plugins.contacts
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class ContactExplorerTreeDelegate extends TreeDelegate
{
	/**
	 * Default constructor
	 */
	function ContactExplorerTreeDelegate ($theConfiguration)
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

		if (isset($item) && $item->itemId != 0)
		{
			$resultString = '<h2>'.$item->name.'</h2>';
			$resultString .= '<a href="'.$this->configuration['callback'];
			$resultString .= '&amp;action=show&amp;parentId='.$item->parentId.'">';
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
			$resultString .= '&amp;expand=' . implode (',', $res);
            if (isset($tree->root))
			{
				$resultString .= '&amp;parentId='.$tree->root->itemId;
			}
			$resultString .= '">';
			$resultString .= $this->configuration['icons']['minus'];
			$resultString.= '</a>';

			if ($item->owner == $_SESSION['brimUsername'])
			{
				$resultString.= '<a href="';
				$resultString .= $this->configuration['callback'];
				$resultString .= '&amp;action=modify&amp;itemId='.$item->itemId;
	            if (isset($tree->root))
				{
					$resultString .= '&amp;parentId='.$tree->root->itemId;
				}
				$resultString .= '">';
				$resultString .= $this->configuration['icons']['folder_open'];
				$resultString .= '</a>';
			}
			else 
			{
				$resultString .= $this->configuration['icons']['folder_open'];
			}
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
			$resultString .= '<a href="';
			$resultString .= $this->configuration['callback'];
			$resultString .= '&amp;expand=' . $linkExpand;
			if (isset($tree->root))
			{
				$resultString .= '&amp;parentId='.$tree->root->itemId;
			}
			$resultString .= '">';
			$resultString .= $this->configuration['icons'] ['plus'];
			$resultString .= '</a>';
			if ($item->owner == $_SESSION['brimUsername'])
			{
				$resultString .= '<a href="';
				$resultString .= $this->configuration['callback'];
	            $resultString .= '&amp;action=modify&amp;itemId='.$item->itemId;
	            if (isset($tree->root))
				{
					$resultString .= '&amp;parentId='.$tree->root->itemId;
				}
				$resultString .= '">';
				$resultString .= $this->configuration['icons'] ['folder_closed'];
				$resultString .= '</a>';
			}
			else 
			{
				$resultString .= $this->configuration['icons'] ['folder_closed'];
			}
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
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '<a href="';
			$resultString .= $this->configuration['callback'];
	        $resultString .= '&amp;action=modify&amp;itemId='.$item->itemId;
	        if (isset($tree->root))
			{
				$resultString .= '&amp;parentId='.$tree->root->itemId;
			}
			$resultString .= '">';
			$resultString .= $this->configuration['icons']['node'];
			$resultString .= '</a>';
		}
		else 	
		{
			$resultString .= $this->configuration['icons']['node'];
		}

		$resultString .= "<a href='";
		$resultString .= $this->configuration['callback'];
        $resultString .= '&amp;action=showItem&amp;itemId='.$item->itemId;
        if (isset($tree->root))
		{
			$resultString .= '&amp;parentId='.$tree->root->itemId;
		}
		$resultString .= "'";
		if ($this->configuration['overlib'])
		{
			$popUp = overlibPopup ($item, $this->configuration['dictionary']);
			$resultString .= $this->overLib (str_replace("&", "&amp;", $item->name), $popUp, 'STICKY');
		}
        $resultString .= 'alt="'. $this->stringUtils->urlEncodeQuotes ($this->stringUtils->gpcAddSlashes (str_replace("&", "&amp;", $item->name))) . '">';
		$resultString .= (str_replace("&", "&amp;", $item->name);
		$resultString .= '</a>';

		$resultString .= '</td></tr>';
		return $resultString;
	}
}

?>