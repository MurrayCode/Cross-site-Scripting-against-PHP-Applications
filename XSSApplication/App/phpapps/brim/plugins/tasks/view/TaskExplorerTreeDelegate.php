<?php

require_once ("framework/util/StringUtils.php");
require_once ("framework/view/TreeDelegate.php");

/**
 * Used in combination with the Tree classes
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.plugins.tasks
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class TaskExplorerTreeDelegate extends TreeDelegate
{

	/**
	 * Default constructor
	 * @param array theConfiguration. This configuration must contain the following items:
	 * <ul>
	 * <li>icons. Icons in an hashtable. The icons must have the following keys:
	 * 	<ul>
	 			<li>'bar'</li>
	 			<li>'up'</li>
	 			<li>'minus'</li>
	 			<li>'folder_open'</li>
	 			<li>'corner'</li>
	 			<li>'plus'</li>
	 			<li>'tee'</li>
	 			<li>'folder_closed'</li>
	 			<li>'node'</li>
	 			<li>'before_display'</li>
	 			<li>'after_display'</li>
	 			<li>'add'</li>
	 			<li>'delete'</li>
	 			<li>'edit'</li>
	 			<li>'up_arrow_shaded'</li>
	 			<li>'up_arraw'</li>
	 			<li>'down_arrow'</li>
	 			<li>'down_arrow_shaded'</li>
	 		</ul>
	 * </li>
	 * <li>callback. The URL to the callback, typically the controller</li>
	 * <li>dictionairy. The dictionary that is used</li>
	 * </ul>
	 */
	function TaskExplorerTreeDelegate ($theConfiguration)
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
	 *
	 * @private
	 * @param object item the item to draw
	 * @param boolean isExpanded whether this folder is
	 * 		expanded (i.e. do the children of this folder
	 *		need to be displayed as well?)
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
			$resultString .= $this->configuration['icons']['plus'];
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
				$resultString .= $this->configuration['icons']['folder_closed'];
				$resultString .= '</a>';
			}
			else 
			{
				$resultString .= $this->configuration['icons']['folder_closed'];			
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
		$resultString .= "' ";

		//
		// overlib popup
		//
		if ($this->configuration['overlib'])
		{
			$overlibBody = $this->configuration['dictionary']['start_date'];
			$overlibBody .= '&nbsp;';
			$overlibBody .= date ('Y-m-d', strtotime ($item->startDate));
			$overlibBody .= '<br />';
			$overlibBody .= $this->configuration['dictionary']['due_date'];
			$overlibBody .= '&nbsp;';
			$overlibBody .= date ('Y-m-d', strtotime ($item->endDate));
			$overlibBody .= '<br />';
			$overlibBody .= $this->configuration['dictionary']['priority'];
			$overlibBody .= '&nbsp;';
			$overlibBody .= $item->priority;
			$overlibBody .= '<br />';
			if (isset ($item->description) && $item->description != '')
			{
				$overlibBody .= '<hr width="80%" />';
				$overlibBody .= $item->description;
			}
			$resultString .= $this->overLib
				($item->name, $overlibBody);
		}

		$resultString .= 'alt="'. $item->name . '">';
		$resultString .= $item->name;
		$resultString .= '</a>';

		$resultString .= '</td></tr>';
		return $resultString;
	}
}
?>