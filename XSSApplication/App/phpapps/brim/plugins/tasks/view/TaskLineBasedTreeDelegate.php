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
class TaskLineBasedTreeDelegate extends TreeDelegate
{
	/**
	 * Have we already drawn a header?
	 */
	var $headersDrawn;

	function TaskLineBasedTreeDelegate ($theConfiguration)
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
		$resultString = '';
		if (isset($item) && $item->itemId != 0)
		{
			$resultString .= '<h2>'.$item->name.'</h2>';
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
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '<a href="';
			$resultString .= $this->configuration['callback'];
	        $resultString .= '&amp;action=modify&amp;itemId='.$item->itemId;
	        $resultString .= '&amp;parentId='.$item->parentId.'">';
			$resultString .= $this->configuration['icons']['folder_closed'];
			$resultString .= '</a>';
		}
		else 
		{
			$resultString .= $this->configuration['icons']['folder_closed'];
		}
		$resultString .= '<a href="';
		$resultString .= $this->configuration['callback'];
		$resultString .= '&amp;action=show&amp;parentId=';
		$resultString .= $item->itemId . '">';
		$resultString .= $item->name;
		$resultString .= '</a>';

		$resultString .= '<br />';
		return $resultString;
	}

	function drawLinesHeader ($parentId)
	{
//		$resultString = '<td colspan="8" /></tr><tr>';
		$resultString = '<tr>';
		$resultString .= '<td>&nbsp;</td>';
		$resultString .= '<td>&nbsp;</td>';
		
		$resultString .= '<td><span style="white-space: nowrap;"><b>'.$this->configuration['dictionary']['name'];
		$resultString .= $this->sortArrows ('name', $parentId);
		$resultString .= '</b></span></td>';

		$resultString .= '<td><span style="white-space: nowrap;"><b>'.$this->configuration['dictionary']['priority'];
		$resultString .= $this->sortArrows ('priority', $parentId);
		$resultString .= '</b></span></td>';

		$resultString .= '<td><span style="white-space: nowrap;"><b>'.$this->configuration['dictionary']['status'];
		$resultString .= $this->sortArrows ('status', $parentId);
		$resultString .= '</b></span></td>';

		$resultString .= '<td><span style="white-space: nowrap;"><b>'.$this->configuration['dictionary']['complete'];
		$resultString .= $this->sortArrows ('percent_complete', $parentId);
		$resultString .= '</b></span></td>';

		$resultString .= '<td><span style="white-space: nowrap;"><b>'.$this->configuration['dictionary']['start_date'];
		$resultString .= $this->sortArrows ('start_date', $parentId);
		$resultString .= '</b></span></td>';
		//$resultString .= '<td>&nbsp;</td>';

		$resultString .= '<td><span style="white-space: nowrap;"><b>'.$this->configuration['dictionary']['due_date'];
		$resultString .= $this->sortArrows ('end_date', $parentId);
		$resultString .= '</b></span></td>';
		//$resultString .= '<td>&nbsp;</td>';
		$resultString .= '</tr>';

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
		// header with two icons and overlib popup
		$resultString = '<td width="20" align="right">';
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '<a href="'.$this->configuration['callback'];
	        $resultString .= '&amp;action=modify&amp;itemId='.$item->itemId;
	        $resultString .= '&amp;parentId='.$item->parentId.'">';
			$resultString .= $this->configuration['icons']['edit'].'</a>';
		}
		else 
		{
			$resultString .= '&nbsp;';
		}
		$resultString .= '</td>';
		$resultString .= '<td width="20" align="right">';
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
		$resultString .= '</td>';

		$resultString .= '<td>';
		$resultString .= '<a href="'.$this->configuration['callback'];
        $resultString .= '&amp;action=showItem&amp;itemId='.$item->itemId;
        $resultString .= '&amp;parentId='.$item->parentId.'" ';
		if ($this->configuration['overlib'])
		{
			$resultString .= $this->overLib ($item->name, $item->description);
		}
		$resultString .= '>';
		$resultString .= '<b>'.$this->stringUtils->truncate ($item->name, 30). '</b></a>';
		$resultString .= '</td>';

		$resultString .= '<td align="left">';
		$resultString .= '<div id="priorityFor'.$item->itemId.'"><span style="white-space: nowrap;">';
		$resultString .= $this->configuration['dictionary']['priority'.$item->priority];
/*
		$resultString .= '&nbsp;';
		if (($item->priority < 5) && ($item->owner == $_SESSION['brimUsername']))
		{
			$resultString .= '<a href="javascript:decreasePriorityFor (\''.$item->itemId.'\')">';
			$resultString .= '<img border="0" src="framework/view/pics/tree/shaded_minus_2.gif"></a>';
		}
		else
		{
			$resultString .= '<img border="0" src="framework/view/pics/tree/shaded_dot_2.gif">';
		}
		$resultString .= '&nbsp;';

		if (($item->priority > 1) && ($item->owner == $_SESSION['brimUsername']))
		{
			$resultString .= '<a href="javascript:increasePriorityFor (\''.$item->itemId.'\')">';
			$resultString .= '<img border="0" src="framework/view/pics/tree/shaded_plus_2.gif"></a>';
		}
		else
		{
			$resultString .= '<img border="0" src="framework/view/pics/tree/shaded_dot_2.gif">';
		}
*/
		$resultString .= '&nbsp;</span>';
		$resultString .= '</div>';
		$resultString .= '</td>';
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
		if ($_SESSION['brimUsername'] == $item->owner)
		{
			//$percentCompleted ['increaseCallback']='javascript:increaseCompletedFor (\''.$item->itemId.'\');';
			//$percentCompleted ['decreaseCallback']='javascript:decreaseCompletedFor (\''.$item->itemId.'\');';
		}
		$resultString .= Widget::percentBar ($percentCompleted).'</td>';
		
		$resultString .= '<td><div id="startDateTextFor'.$item->itemId.'"><span style="white-space: nowrap;">';
		if (isset ($item->startDate))
		{
			$resultString .= 
				date ('Y-m-d', strtotime ($item->startDate)).'';
		}
		else
		{
			$resultString .='&nbsp;';
		}
		$resultString .= '</span></div></td>';
		
/*
		$resultString .= '<td><input type="hidden" name="startDateFor'.$item->itemId.'" ';
		$resultString .= 'value="'.$item->endDate.'" />';
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
		$resultString .= '</td>';
*/
			
		$resultString .= '<td><div id="dueDateTextFor'.$item->itemId.'"><span style="white-space: nowrap;">';
		if (isset ($item->endDate) &&
			($item->endDate < date("Y-m-d H:i:s")) && !$item->percentComplete == 100)
		{
			$resultString .= '<font color="#ff0000">';
			$resultString .= date ('Y-m-d', strtotime ($item->endDate));
			$resultString .= '</font>';
		}
		else
		{
			if (isset ($item->endDate))
			{
				$resultString .= date ('Y-m-d',
					strtotime ($item->endDate));
			}
			else
			{
				$resultString .= '&nbsp;';
			}
		}
		$resultString .= '</span></div></td>';
		
/*
		$resultString .= '<td><input type="hidden" name="dueDateFor'.$item->itemId.'" ';
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
		$resultString .= '</td>';
*/
		return $resultString;
	}

}
?>
