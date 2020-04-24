<?php

require_once ("framework/util/StringUtils.php");
//require_once ("framework/view/TreeDelegate.php");
require_once ("framework/view/Widget.php");
include 'plugins/contacts/view/contactOverlib.php';
/**
 * Used in combination with the Tree class
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2006
 * @package org.brim-project.plugins.contacts
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class AjaxLineBasedTreeDelegate extends TreeDelegate
{
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
		$jQueryCallback  = 'index.php?plugin=contacts&ajax=true';
		$jQueryCallback .= '&function=change';
		$jQueryCallback .= "&PHPSESSID=".session_id ();
		
		$resultString .= '
			<script type="text/javascript">
			var busyIcon = "'.ereg_replace ('"', "'", $this->configuration ['icons']['busy']).'";
			$(document).ready(function() 
			{
				//var currentDrag=null;
				//var currentlyDragging=false;
    				$("td > .editable").editable("'.$jQueryCallback.'", 
    					{ 
        					indicator : busyIcon,
        					tooltip: "Click to edit"
    					}).hover (function ()
							{
								$(this).addClass("editableOver");
							}, function ()
							{
								$(this).removeClass("editableOver");
							});
/*
				$(".dndFolder").Draggable (
					{
						autoSize:true,
						ghosting:true,
						revert:true,
						fx: 200
					}).Droppable (
					{
						accept:"draggable",
						hoverclass:"dropzonehover",
						activeclass:"dropzonehover",
						onDrop:function(drag)
						{
							if (this.id != drag.id)
							{
								moveItem ("contacts", this, drag, \''.session_id().'\');
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
							deleteItem ("contacts", theType, what[1]);
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
				setupSortableTables ([0,1]);
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

	function drawLinesHeader ($parentId)
	{
		$resultString  = '
		<tr id="sortableTableHeader">
			<th></th>
			<th></th>
			<th><span style="white-space: nowrap;"
				>'.$this->configuration['dictionary']['name'].'</span></th>
			<th><span style="white-space: nowrap;"
				>'.$this->configuration['dictionary']['email'].'</span></th>
			<th><span style="white-space: nowrap;"
				>'.$this->configuration['dictionary']['mobile'].'</span></th>
			<th><span style="white-space: nowrap;"
				>'.$this->configuration['dictionary']['tel_work'].'</span></th>
			<th><span style="white-space: nowrap;"
				>'.$this->configuration['dictionary']['tel_home'].'</span></th>
			<th><span style="white-space: nowrap;"
				>'.$this->configuration['dictionary']['webaddress'].'</span></th>
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
			$resultString .= '<a href="javascript:deleteItem(\'contacts\', 
				\'item\', \''.$item->itemId.'\',\''.session_id().'\');" ';
			$resultString .= '>'.$this->configuration['icons']['delete'].'</a>';
		}
		else 
		{
			$resultString .= '&nbsp;';
		}
		$resultString .= '</td>';

		$resultString .= '<td>';
		$resultString .= '<div id="name_'.$item->itemId.'" class="editable draggable"';
        if ($this->configuration['overlib'])
        {
            $popUp = overlibPopup ($item, $this->configuration['dictionary']);
            $resultString .= $this->overLib ($item->name, $popUp, 'STICKY');
        }
		$resultString .= '>';
		$resultString .= $this->stringUtils->truncate ($item->name, 30);
		$resultString .= '</div>';
		//$resultString .= '</a>';
		$resultString .= '</td>';

		$resultString .= '<td align="right">';
		$resultString .= '<div id="mail_'.$item->itemId.'" class="draggable"><span style="white-space: nowrap;">';
		$resultString .= '<a href="mailto:'.$item->email1.'">';
		$resultString .= $this->stringUtils->truncate ($item->email1, 15);
		//$resultString .= 'Click here';
		$resultString .= '</a>';
		$resultString .= '</span>';
		$resultString .= '</div>';
		$resultString .= '</td>';

		$resultString .= '<td>';
		$resultString .= '<div id="mobile_'.$item->itemId.'" class="editable draggable">';
        if (isset ($item->mobile) && ($item->mobile != ""))
        {
            $resultString .= $item->mobile;
        }
        else
        {
            $resultString .= '&nbsp;';
        }
		$resultString .= '</div>';
		$resultString .= '</td>';


		$resultString .= '<td>';
		if ($_SESSION['brimUsername'] == $item->owner)
		{
			$resultString .= '<div id="telwork_'.$item->itemId.'" class="editable draggable">';
		}
		if (isset ($item->tel_work) && ($item->tel_work != ""))
        {
            $resultString .= $item->tel_work;
        }
        else
        {
            $resultString .= '&nbsp;';
        }
		if ($_SESSION['brimUsername'] == $item->owner)
		{
			$resultString .= '</div>';
		}
		$resultString .= '</td>';

		$resultString .= '<td>';
		if ($_SESSION['brimUsername'] == $item->owner)
		{
			$resultString .= '<div id="telhome_'.$item->itemId.'" class="editable draggable">';
		}
		if (isset ($item->tel_home) && ($item->tel_home != ""))
        {
            $resultString .= $item->tel_home;
        }
        else
        {
            $resultString .= '&nbsp;';
        }
		if ($_SESSION['brimUsername'] == $item->owner)
		{
			$resultString .= '</div>';
		}
		$resultString .= '</td>';

		$resultString .= '<td>';
		if (isset ($item->webaddress1) && ($item->webaddress1 != ""))
        {
            $resultString .= '<a href="'.$item->webaddress1.'">';
        }
		if ($_SESSION['brimUsername'] == $item->owner && 
			(!isset ($item->webaddress1) || ($item->webaddress1 == "")))
		{
			// owner, but no webaddress yet. Make the field editable
			$resultString .= '<div id="webaddress_'.$item->itemId.'" class="editable draggable">';
		}
		else if ($_SESSION['brimUsername'] == $item->owner)
		{
			$resultString .= '<div id="webaddress_'.$item->itemId.'" class="draggable">';
		}
		if (isset ($item->webaddress1) && ($item->webaddress1 != ""))
        {
            $resultString .= 'Click here';
        }
        else
        {
            $resultString .= '&nbsp;';
        }
		if ($_SESSION['brimUsername'] == $item->owner)
		{
			$resultString .= '</div>';
		}
		if (isset ($item->webaddress1) && ($item->webaddress1 != ""))
		{
			$resultString .= '</a>';
		}
		$resultString .= '</td>';

		$resultString .= '</tr>';
		return $resultString;
	}


	function showPluginIcons ($configuration)
	{
		$resultString = '';
		$trashcan = ($configuration['trashCount']>0)?
					$configuration['icons']['fullTrash']:
					$configuration['icons']['emptyTrash'];
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
				</div>
			</div>';
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
