<?php

require_once ("framework/util/StringUtils.php");
require_once ("framework/view/TreeDelegate.php");

/**
 * Used in combination with the Tree class
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
class TaskYahooTreeDelegate extends TreeDelegate
{
	/**
	 * Default constructor
	 * @param array theIcons the icons used for display
	 * @param string theCallbackURL
	 */
	function TaskYahooTreeDelegate ($theConfiguration)
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


	/**
	 * If the specified item is a node, draw it
	 * @private
	 * @param object item the item to draw
	 * @param boolean lastNode whether this node is the last one
	 * @return string the string for this node
	 */
	function drawNode ($item, $lastNode, $tree, $indentLevel)
	{
		$resultString  = '<div id="yahooTreeTask">
						 <table class="yahooTreeTask">';


		//	Note header: title with link, icons (delete+modify)
		//	and note popup
		$resultString .= '<tr><td class="yahooTreeTaskTitle" colspan="2">';

		// header with two icons and overlib popup
		$resultString .= '
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>
					<a href="'.$this->configuration['callback'].
						'&amp;action=showItem&amp;itemId='.$item->itemId.'
        				&amp;parentId='.$item->parentId.'" ';
						if ($this->configuration['overlib'])
						{
							$resultString .= $this->overLib 
								($item->name, $item->description);
						}
						$resultString .= '
					>
						<b>'.$this->stringUtils->truncate 
							($item->name, 30).'</b></a>
				</td>
				<td width="20" align="right">';
				if ($item->owner == $_SESSION['brimUsername'])
				{
					$resultString .= '			
					<a href="'.$this->configuration['callback'].
						'&amp;action=modify&amp;itemId='.$item->itemId.'
        				&amp;parentId='.$item->parentId.'"
        				>'.$this->configuration['icons']['edit'].'</a>';
				}
				else 
				{
					$resultString .= $this->configuration['icons']['edit'];
				}		
				$resultString .= '
				</td>
				<td width="20" align="right">';
				if ($item->owner == $_SESSION['brimUsername'])
				{
					$resultString .= '
					<a href="'.$this->configuration['callback'].
						'&amp;itemId='.$item->itemId.'
						&amp;parentId='.$item->parentId.'
						&amp;action=deleteItemPost" 
						javascript:return confirm (\'
						'.$this->configuration['dictionary']['confirm_delete'].'\');" 
					>'.$this->configuration['icons']['delete'].'</a>';
				}
				else 
				{
					$this->configuration['icons']['delete'];
				}
				$resultString .= '
				</td>
			</tr>
		</table>';

		$resultString .= '</td></tr>';
		

		$resultString .= '<tr class="yahooTreeTaskBody"><td>';
		$resultString .= $this->configuration['dictionary']['priority'];
		$resultString .= '</td><td>';
		$resultString .= $this->configuration['dictionary']['priority'.$item->priority];
		$resultString .= '</td></tr>';

		$resultString .= '<tr class="yahooTreeTaskBody"><td>';
		$resultString .= $this->configuration['dictionary']['status'];
		$resultString .= '</td><td>';
		if (isset ($item->status))
		{
			$resultString .= $item->status;
		}
		else
		{
			$resultString .= '&nbsp;';
		}
		$resultString .= '</td></tr>';

		$resultString .= '<tr class="yahooTreeTaskBody"><td>';
		$resultString .= $this->configuration['dictionary']['complete'];
		$resultString .= '</td><td>';
		$resultString .= $item->percentComplete;
		$resultString .= '</td></tr>';

		$resultString .= '<tr class="yahooTreeTaskBody"><td>';
		$resultString .= $this->configuration['dictionary']['start_date'];
		$resultString .= '</td><td>';
		$resultString .= date ('Y-m-d', strtotime ($item->startDate));
		$resultString .= '</td></tr>';

		$resultString .= '<tr class="yahooTreeTaskBody"><td>';
		if ($item->endDate < date("Y-m-d H:i:s"))
		{
			$resultString .= '<font color="ff0000">';
			$resultString .= $this->configuration['dictionary']['due_date'];
			$resultString .= '</font>';
			$resultString .= '</td><td>';
			$resultString .= '<font color="ff0000">';
			$resultString .= date ('Y-m-d', strtotime ($item->endDate));
			$resultString .= '</font>';
		}
		else
		{
			$resultString .= $this->configuration['dictionary']['due_date'];
			$resultString .= '</td><td>';
			$resultString .= date ('Y-m-d', strtotime ($item->endDate));
		}
		$resultString .= '</td></tr>';

		if (isset ($item->description) && strlen (trim ($item->description)) > 0)
		{
			$resultString .= '<tr class="yahooTreeTaskBody"><td colspan="2">';
			$resultString .= $this->stringUtils->truncate ($item->description, 60);
			$resultString .= '</td></tr>';
		}
		$resultString .= '</table></div>';

		return $resultString;
	}
}
?>