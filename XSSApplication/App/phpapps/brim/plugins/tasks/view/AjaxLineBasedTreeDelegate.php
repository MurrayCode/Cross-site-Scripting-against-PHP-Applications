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
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class AjaxLineBasedTreeDelegate extends TreeDelegate
{
	/**
	 * Defau7ot constructor
	 *
	 * @param array this delegates' configuration
	 */
	function AjaxLineBasedTreeDelegate ($theConfiguration)
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
		$resultString .= '<script type="text/javascript" src="plugins/tasks/view/javascript/tasks.js"></script>';
		$jQueryCallback  = 'index.php?plugin=tasks&ajax=true';
		$jQueryCallback .= '&function=change';
		$jQueryCallback .= "&PHPSESSID=".session_id ();
		
		$resultString .= '
			<script type="text/javascript">
		';
		$showCompleted = (isset ($_GET['action']) && ($_GET['action'] == 'showCompletedOnly'));
		if ($showCompleted)
		{
			$resultString .= 'var showCompleted=true;';
		}
		else
		{	
			$resultString .= 'var showCompleted=false;';
		}
			
		$resultString .= '
			$(document).ready(function() 
			{
				//var currentDrag=null;
				//var currentlyDragging=false;
    				$(".editable").editable("'.$jQueryCallback.'", 
    					{ 
        					indicator : "'.ereg_replace ('"', "'", $this->configuration ['icons']['busy']).'",
        					tooltip: "Click to edit"
    					}).mouseover (function ()
					{
						$(this).addClass("editableOver");
					}).mouseout (function ()
					{
						$(this).removeClass("editableOver");
					});
/*
.bind ("click", function ()
					{
						//$(".draggable").DraggableDestroy ();
						//alert ("Click");
					});
				$(".dndFolder").Draggable (
					{
						autoSize:true,
						ghosting:true,
						revert:true,
						fx: 200
					});
				$(".dndFolder").Droppable (
					{
						accept:"draggable",
						hoverclass:"dropzonehover",
						activeclass:"dropzonehover",
						onDrop:function(drag)
						{
							if (this.id != drag.id)
							{
								//moveItem ("tasks", this.id.split ("_")[1], drag.id.split ("_")[1],\''.session_id().'\');	
								moveItem ("tasks", this, drag,\''.session_id().'\');	
							}
						}
					});
				$(".draggable").Draggable (
					{
						autoSize:true,
						ghosting:true,
						revert:true,
						fx: 200,
						onStart:function()
						{
							currentDrag = $(this).parent().parent();
							currentDrag.removeClass().addClass ("selected");
						}
					});
				$("#trash").Droppable (
					{
						accept:"draggable",
						hoverclass:"dropzonehover",
						activeclass:"dropzonehover",
						onDrop:function(drag)
						{
							var what = drag.id.split ("_");
							var theType = (what[0]=="folder"?"folder":"item");
							//animateDeleteItem ((drag.id).substring (5));
							//deleteItem ("tasks", (drag.id).substring (5));
							deleteItem ("tasks", theType, what[1]);
						}
					});
				$("#completedTasks").Droppable (
					{
						accept:"draggable",
						hoverclass:"dropzonehover",
						activeclass:"dropzonehover",
						onDrop:function(drag)
						{
							if (showCompleted)
							{
								uncompleteItem (drag.id.split ("_")[1], \''.session_id().'\');
							}
							else
							{
								completeItem (drag.id.split ("_")[1], \''.session_id().'\');
							}
						}
					});
				$(document).bind("mouseup", function ()
					{
						if (currentDrag) 
						{
							zebraItems ();
							currentDrag = null;
						}
					});
*/
				setupSortableTables ([0,1,7,9]);
    			});

			</script>
		';
		
		if (isset($item) && $item->itemId != 0)
		{
			$resultString .= '<h2>'.$item->name.'</h2>';
			$resultString .= '<a href="'.$this->configuration['callback'];
			$resultString .= '&amp;action=show&amp;parentId='.$item->parentId.'">';
			$resultString .= $this->configuration['icons']['up'];
			$resultString .= '</a><br />';
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
			$resultString .= '<div class="draggable dndFolder" id="folder_'.$item->itemId.'">';
			//
			// Folder icon
			//
			$resultString .= '<a href="';
			$resultString .= $this->configuration['callback'];
	        	$resultString .= '&amp;action=modify&amp;itemId='.$item->itemId;
	        	$resultString .= '&amp;parentId='.$item->parentId.'">';
			$resultString .= $this->configuration['icons']['folder_closed'];
			$resultString .= '</a>';
			//
			// Folder name
			//
			$resultString .= '<a href="';
			$resultString .= $this->configuration['callback'];
			$resultString .= '&amp;action=show&amp;parentId=';
			$resultString .= $item->itemId . '">';
			$resultString .= $item->name;
			$resultString .= '</a>';
			$resultString .= '</div>';
		}
		else 
		{
			$resultString .= $this->configuration['icons']['folder_closed'];
			$resultString .= $item->name;
		}

		$resultString .= '<br />';
		return $resultString;
	}

	/**
 	 * Draws the table heeader with sorting options
	 * 
	 * @param integer parentId the id of the parent which we are 
	 * currently viewing (0 for root)
	 * @return string the html code to render the header
	 */
	function drawLinesHeader ($parentId)
	{
		$resultString  = '
		<tr id="sortableTableHeader">
			<th></th>
			<th></th>
			<th><span style="white-space: nowrap;"
				>'.$this->configuration['dictionary']['name'].'</span></th>
			<th><span style="white-space: nowrap;"
				>'.$this->configuration['dictionary']['priority'].'</span></th>
			<th><span style="white-space: nowrap;"
				>'.$this->configuration['dictionary']['status'].'</span></th>
			<th><span style="white-space: nowrap;"
				>'.$this->configuration['dictionary']['complete'].'</span></th>
			<th><span style="white-space: nowrap;"
				>'.$this->configuration['dictionary']['start_date'].'</span></th>
			<th></th>
			<th><span style="white-space: nowrap;"
				>'.$this->configuration['dictionary']['due_date'].'</span></th>
			<th></th>
		</tr>';
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
		$resultString  = '';
		$resultString .= '<tr id="item_'.$item->itemId.'">';
		$resultString .= '<td width="20" align="right">';
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
			if (true)
			{
				$resultString .= '<a href="javascript:deleteItem(\'tasks\', \'item\', \''.$item->itemId.'\',\''.session_id().'\');" ';
			}
			else
			{
				$resultString .= '<a href="'.$this->configuration['callback'];
				$resultString .= '&amp;itemId='.$item->itemId;
				$resultString .= '&amp;parentId='.$item->parentId;
				$resultString .= '&amp;action=deleteItemPost" ';
				//
				// delete confirmation
				//
				$resultString .= 'onclick="javascript:return confirm(\'';
				$resultString .= $this->configuration['dictionary']['confirm_delete'];
				$resultString .= '\');" ';
			}	
			$resultString .= '>'.$this->configuration['icons']['delete'].'</a>';
		}
		else 
		{
			$resultString .= '&nbsp;';
		}
		$resultString .= '</td>';

		$resultString .= '<td>';
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '<div id="name_'.$item->itemId.'" class="editable draggable">';
		}
		//$resultString .= $this->stringUtils->truncate ($item->name, 30);
		$resultString .= $item->name;
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '</div>';
		}
		$resultString .= '</td>';

		$resultString .= '<td align="left">';
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '<div id="priorityFor'.$item->itemId.'" class="draggable">';
		}
		$resultString .= '<span style="white-space: nowrap;">';
		$resultString .= $this->configuration['dictionary']['priority'.$item->priority];
		$resultString .= '&nbsp;';
		if (($item->priority < 5) && ($item->owner == $_SESSION['brimUsername']))
		{
			$resultString .= '
				<a href="javascript:decreasePriorityFor(\''.$item->itemId.'\',\''.session_id().'\')"><img 
					border="0" alt="" src="framework/view/pics/tree/shaded_minus_2.gif"></a>';
		}
		else
		{
			$resultString .= '<img border="0" alt="" src="framework/view/pics/tree/shaded_dot_2.gif">';
		}
		$resultString .= '&nbsp;';
		if (($item->priority > 1) && ($item->owner == $_SESSION['brimUsername']))
		{
			$resultString .= '
				<a href="javascript:increasePriorityFor(\''.$item->itemId.'\',\''.session_id().'\')"><img 
					border="0" alt="" src="framework/view/pics/tree/shaded_plus_2.gif"></a>';
		}
		else
		{
			$resultString .= '<img border="0" alt="" src="framework/view/pics/tree/shaded_dot_2.gif">';
		}
		$resultString .= '&nbsp;</span>';
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '</div>';
		}
		$resultString .= '</td>';
		$resultString .= '<td>';
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '<div id="status_'.$item->itemId.'" class="editable draggable">';
		}
		if (isset ($item->status) && $item->status != '')
		{
			$resultString .= $item->status;
		}
		else
		{
			$resultString .= '&nbsp;';
		}
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '</div>';
		}
		$resultString .= '</td>';
		$resultString .= '<td>';
		$percentCompleted = array ();
		$percentCompleted ['total'] = 100;
		$percentCompleted ['completed'] = $item->percentComplete;
		$percentCompleted ['completedDivId'] ='percentCompletedFor'.$item->itemId;
		if ($_SESSION['brimUsername'] == $item->owner)
		{
			$percentCompleted ['increaseCallback']='javascript:increaseCompletedFor(\''.$item->itemId.'\',\''.session_id().'\');';
			$percentCompleted ['decreaseCallback']='javascript:decreaseCompletedFor(\''.$item->itemId.'\',\''.session_id().'\');';
		}
		$resultString .= Widget::percentBar ($percentCompleted).'</td>';
		
		$resultString .= '<td>';
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '<div id="startDateTextFor_'.$item->itemId.'" class="draggable">';
		}
		$resultString .= '<span style="white-space: nowrap;">';
		if (isset ($item->startDate))
		{
			$resultString .= 
				date ('Y-m-d', strtotime ($item->startDate)).'';
		}
		else
		{
			$resultString .='&nbsp;';
		}
		$resultString .= '</span>';
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '</div>';
		}
		$resultString .= '</td>';
		
		$resultString .= '<td><input type="hidden" name="startDateFor'.$item->itemId.'" ';
		$resultString .= 'value="'.$item->endDate.'" />';
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '<a href="javascript:changeStartDateFor(\'imgStartDateFor'.$item->itemId.'\',\''.$item->itemId.'\');">';
			$resultString .= '<img src="plugins/tasks/view/pics/datepicker.gif" alt="*" border="0" ';
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
			
		$resultString .= '<td>';
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '<div id="dueDateTextFor_'.$item->itemId.'" class="draggable">';
		}	
		$resultString .= '<span style="white-space: nowrap;">';

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
		$resultString .= '</span>';
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '</div>';
		}
		$resultString .= '</td>';
		
		$resultString .= '<td><input type="hidden" name="dueDateFor'.$item->itemId.'" ';
		$resultString .= 'value="'.$item->endDate.'" />';
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '<a href="javascript:changeDueDateFor(\'imgDueDateFor'.$item->itemId.'\',\''.$item->itemId.'\');">';
			$resultString .= '<img src="plugins/tasks/view/pics/datepicker.gif" border="0" alt="*" ';
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
		$resultString .= '</tr>';
		return $resultString;
	}


	/**
 	 * Shows this plugins' specific icons (a show/hide completed tasks icon if requested
	 * and a trash icon in this case)
	 *
	 * @param array configuration the configuration conraining the icon defs, dictionary etc
	 * @return the html code to render the plugin icons
	 */
	function showPluginIcons ($configuration)
	{

		$showCompletedTasksOnly
        		= (isset ($_GET['action']) && ($_GET['action'] == "showCompletedOnly"));
		$hideCompletedTasks
        		= (isset ($_SESSION['taskHideCompleted']) && 
					($_SESSION['taskHideCompleted']==1));
		$resultString = '';
		$trashcan = ($configuration['trashCount']>0)?
					$configuration['icons']['fullTrash']:
					$configuration['icons']['emptyTrash'];
		//
		// The trash image
		//
		$resultString .= '
			<div id="pluginIcons">
				<div id="trash">
					<a href="'.$configuration['callback'].'&amp;action=showTrash"><img 
						id="trashImage"
						src="'.$trashcan.'" border="0"	alt="[trash]"
						onmouseover="return overlib (\''.$configuration['dictionary']['trash'].'\', 
							BUBBLE, 
							BUBBLETYPE, \'roundcorners\');"
 						onmouseout="return nd ();"
					></a>
				</div>';
		// 
		// Only use this part if we want to hide completed tasks (can be
		// configured via the preferences)
		//
		// This part renders the 'showCompletedTasks' icon; we are currently 
		// viewing the uncompleted icons (default view)
		//
		if ($hideCompletedTasks && !$showCompletedTasksOnly)
		{
			$resultString .= '
				<div id="completedTasks">
					<a href="'.$configuration['callback'].'&amp;action=showCompletedOnly"><img 
						src="'.$configuration['icons']['completedTasks'].'" border="0" alt="[completed]"
						onmouseover="return overlib (\''.$configuration['dictionary']['completedTasks'].'\', 
							BUBBLE, 
							BUBBLETYPE, \'roundcorners\');"
 						onmouseout="return nd ();"
					></a>
				</div>';
		}
		// 
		// Only use this part if we want to hide completed tasks (can be
		// configured via the preferences)
		//
		// This part renders the 'showUncompletedTasks' icon; we are currently 
		// viewing the completed icons
		//
		if ($hideCompletedTasks && $showCompletedTasksOnly)
		{
			$resultString .= '
				<div id="completedTasks">
					<a href="'.$configuration['callback'].'"><img 
						src="'.$configuration['icons']['uncompletedTasks'].'" border="0"
						alt="[uncompleted]"
						onmouseover="return overlib (\''.$configuration['dictionary']['uncompletedTasks'].'\', 
							BUBBLE, 
							BUBBLETYPE, \'roundcorners\');"
 						onmouseout="return nd ();"
					></a>
				</div>';
		}
		$resultString .= '</div>';
		//
		// A bit of CSS for item transfer
		//
		$resultString .= '
			<style type="text/css">
			.itemTransfer
			{
				border-top: 2px solid #6CAF00;
				border-bottom: 2px solid #6CAF00;
				border-left: 2px dashed #6CAF00;
				border-right: 2px dashed #6CAF00;
			}
			</style>
		';

		return $resultString;
	}
}
?>
