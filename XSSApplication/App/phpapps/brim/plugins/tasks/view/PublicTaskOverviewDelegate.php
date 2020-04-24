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
 * @author Barry Nauta - January 2004
 * @package org.brim-project.plugins.tasks
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class PublicTaskOverviewTreeDelegate extends TreeDelegate
{
	/**
	 * Have we already drawn a header?
	 */
	var $headersDrawn;

	function PublicTaskOverviewTreeDelegate ($theConfiguration)
	{
		parent::TreeDelegate ($theConfiguration);
		$this->headersDrawn = false;
	}
	
	/**
	 * Builds up html code to display the root of the tree
	 * @private
	 *
	 * @param object item the item that is the root of the tree
	 */
	function showRoot ($item, $tree)
	{
		if ($item->itemId != 0)
		{
			$resultString = '<h2>'.$item->name.'</h2>';
			$resultString .= '<a href="'.$this->configuration['callback'];
			$resultString .= '&amp;action=show';
			$resultString .= '&amp;parentId='.$item->parentId;
			$resultString .= '&username='.$this->configuration['username'];
			$resultString .= '&amp;plugin='.$this->configuration['plugin'];
			$resultString .= '">';
			$resultString .= $this->configuration['icons']['up'];
			$resultString .= '</a><br />';
		}
		else
		{
			$resultString = '<h2>Root (id: 0)</h2>';
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
	function drawFolder ($item, $isExpanded, $tree, $identLevel)
	{
		if ($identLevel % 2 == 0)
		{
			$class = 'overviewTreeEven';
		}
		else
		{
			$class = 'overviewTreeOdd';
		}
		$resultString = '<table border="0" class="'.$class.'" width="100%">';
		$resultString .= '<tr class="'.$class.'"><td width="50">';
		if ($isExpanded)
		{
			$res = $tree->createExpandedListWithout ($item->itemId);
			$resultString .= '<a href="';
			$resultString .= $this->configuration['callback'];
			$resultString .= '&amp;expand=' . implode (',', $res);
			$resultString .= '&amp;parentId='.$tree->root->itemId;
			$resultString .= '&username='.$this->configuration['username'];
			$resultString .= '&amp;plugin='.$this->configuration['plugin'];
			$resultString .= '">';
			$resultString .= $this->configuration['icons']['overviewcollapse'];
			$resultString.= '</a>';
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
				$resultString .= '&username='.$this->configuration['username'];
				$resultString .= '&amp;plugin='.$this->configuration['plugin'];
				//$resultString .= '&amp;parentId='.$item->parentId;
				$resultString .= '">';
			}
			else
			{
				$resultString .= '<a href="';
				$resultString .= $this->configuration['callback'];
				$resultString .= '&amp;expand='.$linkExpand;
				$resultString .= '&username='.$this->configuration['username'];
				$resultString .= '&amp;plugin='.$this->configuration['plugin'];
				$resultString .= '">';
			}
			$resultString .= $this->configuration['icons'] ['overviewexpand'];
            $resultString .= '</a>';
		}
		$resultString .= '</td>';
		$resultString .= '<td align="left">';
		$resultString .= '<a href="';
		$resultString .= $this->configuration['callback'];
		$resultString .= '&amp;action=show&amp;parentId=';
		$resultString .= $item->itemId;
		$resultString .= '&username='.$this->configuration['username'];
		$resultString .= '&amp;plugin='.$this->configuration['plugin'];
		$resultString .= '">';
		$resultString .= '<b>'.$item->name.'</b>';
		$resultString .= '</a>';
		$resultString .= '</td>';
		$resultString .= '<td align="right">ID:'.$item->itemId.'</td>';
		$resultString .= '</tr></table>';

		return $resultString;
		
	}
	
	/**
	 * If the specified item is a node, draw it
	 * @private
	 * @param object item the item to draw
	 * @param boolean lastNode whether this node is the last one
	 * @return string the string for this node
	 */
	function drawNode ($item, $lastNode, $tree, $identLevel)
	{
		if ($identLevel % 2 == 0)
		{
			$class = 'overviewTreeEven';
		}
		else
		{
			$class = 'overviewTreeOdd';
		}
		// header with two icons and overlib popup
		$resultString = '<table border="0" cellpadding="2" class="'.$class.'" width="100%"><tr class="'.$class.'">';
		$resultString .= '<td colspan="5">';
		$resultString .= '<a href="'.$this->configuration['callback'].'&amp;itemId='.$item->itemId.'&amp;action=show" ';
		$resultString .= '&username='.$this->configuration['username'];
		$resultString .= '&amp;plugin='.$this->configuration['plugin'];
		if ($this->configuration['overlib'])
		{
			$resultString .= $this->overLib ($item->name, $item->description);
		}
		$resultString .= '>';
		$resultString .= $this->stringUtils->truncate ($item->name, 30). '</a>';
		$resultString .= '</td>';
		$resultString .= '<td align="right">ID:'.$item->itemId.'</td>';
		
		$resultString .= '</tr><tr class="'.$class.'">';

		$resultString .= '<td colspan="2">&nbsp;</td>';
		$resultString .= '<td colspan="2">'.$this->configuration['dictionary']['priority'.$item->priority].'</td>';
		$resultString .= '<td>';
		if (isset ($item->status))
		{
			$resultString .= $item->status;
		}
		else
		{
			$resultString .= '&nbsp;';
		}
		$resultString .= '</td>';
		$resultString .= '<td>'.$this->configuration['dictionary']['complete'].': '.$item->percentComplete.'%</td>';
		//$resultString .= '<td>'.$item->startDate.'</td>';
		$resultString .= '<td>'.date ('Y-m-d', strtotime ($item->startDate)).'</td>';
		$resultString .= '<td>';
		if ($item->endDate < date("Y-m-d H:i:s"))
		{
			$resultString .= '<font color="ff0000">';
			$resultString .= date ('Y-m-d', strtotime ($item->endDate));
			$resultString .= '</font>';
		}
		else
		{
			$resultString .= date ('Y-m-d', strtotime ($item->endDate));
		}
		$resultString .= '</td></tr>';
		if (isset ($item->description))
		{
			$resultString .= '<tr class="'.$class.'">';
			$resultString .= '<td colspan="2">&nbsp;</td>';
			$resultString .= '<td colspan="6">';
			$resultString .= $item->description;
			$resultString .= '</td></tr>';
		}

		$resultString .= '</table>';
		return $resultString;
	}
}
?>