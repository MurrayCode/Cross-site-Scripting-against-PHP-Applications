<?php

require_once ("framework/util/StringUtils.php");
require_once ("framework/view/TreeDelegate.php");
require_once ("framework/view/Widget.php");

/**
 * Used in combination with the Tree class
 *
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
class TaskOverviewTreeDelegate extends TreeDelegate
{
	/**
	 * Have we already drawn a header?
	 */
	var $headersDrawn;

	function TaskOverviewTreeDelegate ($theConfiguration)
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
		if (isset($item) && $item->itemId != 0)
		{
			$resultString = '<h2>'.$item->name.'</h2>';
			$resultString .= '<a href="'.$this->configuration['callback'];
			$resultString .= '&amp;action=show&amp;parentId='.$item->parentId.'">';
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
		$resultString = '<table border="0" class="'.$class.'" width="100%">
			<tr class="'.$class.'">
				<td width="50">';
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
			$resultString .= $this->configuration['icons']['overviewcollapse'];
			$resultString.= '</a>&nbsp;';

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
			$resultString .= '&amp;expand='.$linkExpand;
            if (isset($tree->root))
            {
				$resultString .= '&amp;parentId='.$tree->root->itemId;
			}
			$resultString .= '">';
			$resultString .= $this->configuration['icons'] ['overviewexpand'];
                        $resultString .= '</a>&nbsp;';
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
		$resultString .= '
				</td>
				<td align="left">
					<a href="'.$this->configuration['callback'].
						'&amp;action=show&amp;parentId='.$item->itemId.
						'"><b>'.$item->name.'</b></a>
				</td>
			</tr>
		</table>';
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
		$resultString = '<table border="0" class="'.$class.'" width="100%">
			<tr class="'.$class.'">
				<td width="22" align="left">';
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '<a href="'.$this->configuration['callback'];
	        $resultString .= '&amp;action=modify&amp;itemId='.$item->itemId;
	        if (isset($tree->root))
	        {
				$resultString .= '&amp;parentId='.$tree->root->itemId;
			}
			$resultString .= '">';
			$resultString .= $this->configuration['icons']['edit'].'</a>';
		}
		else 
		{
			$resultString .= '&nbsp;';
		}
		$resultString .= '
				</td>
				<td width="22" align="left">';
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '<a href="'.$this->configuration['callback'];
			$resultString .= '&amp;itemId='.$item->itemId;
			$resultString .= '&amp;parentId='.$item->parentId;
			$resultString .= '&amp;action=deleteItemPost" ';
			// delete confirmation
			$resultString .= 'onclick="javascript:return confirm (\'';
			$resultString .= $this->configuration['dictionary']['confirm_delete'];
			$resultString .= '\');" ';
	
			$resultString .= '>'.$this->configuration['icons']['delete'].'</a>';
		}
		else 
		{
			$resultString .= '&nbsp;';
		}
		$resultString .= '
				</td>
				<td colspan="5">';
		$resultString .= '<a href="'.$this->configuration['callback'];
        $resultString .= '&amp;action=showItem&amp;itemId='.$item->itemId;
        if (isset($tree->root))
        {
			$resultString .= '&amp;parentId='.$tree->root->itemId;
		}
		$resultString .= '" ';
		if ($this->configuration['overlib'])
		{
			$resultString .= $this->overLib ($item->name, $item->description);
		}
		$resultString .= '>';
		$resultString .= $this->stringUtils->truncate ($item->name, 30). '</a>';
		$resultString .= '</td>';

		$resultString .= '</tr><tr class="'.$class.'">';

		$resultString .= '<td colspan="2">&nbsp;</td>';
		$resultString .= '<td colspan="2" width="20%">'.$this->configuration['dictionary']['priority'.$item->priority].'</td>';
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
		$resultString .= '<td>';
		//$resultString .= '<td>'.$item->percentComplete.'</td>';
		$percentCompleted = array ();
		$percentCompleted ['total'] = 100;
		$percentCompleted ['completed'] = $item->percentComplete;
		$percentCompleted ['completedDivId'] ='percentCompletedFor'.$item->itemId;
		$percentCompleted ['increaseCallback']='javascript:increaseCompletedFor (\''.$item->itemId.'\');';
		$percentCompleted ['decreaseCallback']='javascript:decreaseCompletedFor (\''.$item->itemId.'\');';
		$resultString .= Widget::percentBar ($percentCompleted).'</td>';
		//$resultString .= '<td width="20%">'.$this->configuration['dictionary']['complete'].': '.$item->percentComplete.'%</td>';
		//$resultString .= '<td>'.$item->startDate.'</td>';
		$resultString .= '<td><span style="white-space: nowrap;"><div id="startDateTextFor'.$item->itemId.'">'.date ('Y-m-d', strtotime ($item->startDate)).'</div>';
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '<a href="javascript:changeStartDateFor(\'imgStartDateFor'.$item->itemId.'\', \''.$item->itemId.'\');">';
			$resultString .= '<img src="plugins/tasks/view/pics/datepicker.gif" border="0" ';
			$resultString .= 'name="imgStartDateFor'.$item->itemId.'" ';
			$resultString .= 'id="imgStartDateFor'.$item->itemId.'" ';
			$resultString .= 'align="left" ';
			$resultString .= '></a>';
		}
		else 
		{
			$resultString .= '&nbsp;';
		}
		$resultString .= '<input type="hidden" name="startDateFor'.$item->itemId.'" ';
		$resultString .= 'value="'.$item->endDate.'" />';
		$resultString .= '</span></td>';

		
		$resultString .= '<td><div id="dueDateTextFor'.$item->itemId.'"><span style="white-space: nowrap;">';
		if ($item->endDate < date("Y-m-d H:i:s") && !$item->percentComplete == 100)
		{
			$resultString .= '<font color="#ff0000">';
			$resultString .= date ('Y-m-d', strtotime ($item->endDate));
			$resultString .= '</font>';
		}
		else
		{
			$resultString .= date ('Y-m-d', strtotime ($item->endDate));
		}
		$resultString .= '</span></div><input type="hidden" name="dueDateFor'.$item->itemId.'" ';
		$resultString .= 'value="'.$item->endDate.'" />';
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '<a href="javascript:changeDueDateFor(\'imgDueDateFor'.$item->itemId.'\', \''.$item->itemId.'\');">';
			$resultString .= '<img src="plugins/tasks/view/pics/datepicker.gif" border="0" ';
			$resultString .= 'name="imgDueDateFor'.$item->itemId.'" ';
			$resultString .= 'id="imgDueDateFor'.$item->itemId.'" ';
			$resultString .= 'align="left" ';
			$resultString .= '></a>';
		}
		else 
		{
			$resultString .= '&nbsp;';
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