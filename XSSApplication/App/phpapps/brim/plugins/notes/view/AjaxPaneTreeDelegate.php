<?php

require_once ("framework/util/StringUtils.php");
require_once ('framework/view/TreeDelegate.php');

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
 * @package org.brim-project.plugins.notes
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class AjaxPaneTreeDelegate extends TreeDelegate
{
	/**
	 * Default constructor
	 */
	function AjaxPaneTreeDelegate ($theConfiguration)
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
		$jQueryCallback  = 'index.php?plugin=notes&ajax=true';
		$jQueryCallback .= '&function=change';
		$jQueryCallback .= "&PHPSESSID=".session_id ();
		$resultString = '
				<style type="text/css">
				.note
				{
					position:relative;
					min-width:5px;
					max-width:300px;
					height:auto;
					width:300px;
					overflow:visible;
					background:yellow;
					border:3px solid orange;
					padding:3px 3px 3px 3px;
					cursor: move;
				}
				.editableText
				{
					cursor:text
				}
				.editableTextarea
				{
					cursor:text
				}
				.noteHeader
				{
					font-weight:bold;
				}
				</style>
				<script type="text/javascript">
					/*var busyIcon = "'.ereg_replace ('"', "'", $this->configuration ['icons']['busy']).'";*/
					function setNotePositions (data)
					{
						var result = eval ("("+data+")");
						var minHeight = 0;
						var currentHeight = 0;
						var id = 0;
						var top = 0;
						var left = 0;
						var zIndex = 0;
						var width = 0;
						var height = 0;
						for (var i=0; i<result.length; i++)
						{
							id = "#item_"+result[i]["itemId"];
							top = result[i]["top"];
							
							left = result[i]["left"];
							zIndex = result[i]["zIndex"];
							width = result[i]["width"];
							height = result[i]["height"];
							$(id).css ("top", top);
							$(id).css ("left", left);
							$(id).css ("zIndex", zIndex);
							//$(id).css ("width", 300);
						
							if (width != 0 && width != "0pt" && width != "0px")
							{
								//$(id).css ("width", width);
							}
							if (height != 0 && height != "0pt" && height != "0px")
							{
								//$(id).css ("height", height);
							}
						}
					}
					function loadNotePositions ()
					{
    					var theData = "plugin=notes&ajax=true";
    					theData += "&function=loadNotePositions";
    					theData += "&PHPSESSID='.session_id().'";
    					//
    					// Call the backend
    					//
    					$.ajax ({
        					type:"POST",
        					url:"index.php",
        					data:theData,
							success:function(data)
							{
								setNotePositions (data);
							}
    					});
					}
					function setPosition (itemId, top, left, zIndex, width, height)
					{
    					var theData = "plugin=notes&ajax=true";
    					theData += "&function=setPosition";
    					theData += "&itemId="+itemId;
						theData += "&top="+top+"&left="+left+"&zIndex="+zIndex+"&width="+width+"&height="+height;
    					theData += "&PHPSESSID='.session_id().'";
    					//
    					// Call the backend
    					//
    					$.ajax ({
        					type:"POST",
        					url:"index.php",
        					data:theData
    					});
					}
					var theZIndex = 10;
					var x = 0;
					var y = 0;
					$(document).ready (function() {
						//$(".note").width("auto");
						$(".draggable").Draggable ({
							/*
							containment: \'parent\',
							ghosting:true,
							opacity:0.8,
							*/
							zIndex:10000,
							onStart: function ()
							{
								$(this).css ("zIndex", theZIndex++);
							},
							onStop: function ()
							{
								setPosition (
									$(this).id().split("_")[1],
									$(this).css ("top"),
									$(this).css ("left"),
									$(this).css ("zIndex"),
									$(this).css ("width"),
									$(this).css ("height") 
								);
							}
						});
						$(".editableText").editable ("'.$jQueryCallback.'",{
        					/*indicator : busyIcon,*/
        					tooltip: "Click to edit",
							type:\'text\'
						}).mouseover (function ()
						{
							$(this).addClass("editableOver");
						}).mouseout (function ()
						{
							$(this).removeClass("editableOver");
						}).bind ("click", function () {
							theZIndex++;
							$(this).parent ().css ("zIndex", theZIndex);
						});
						$(".editableTextarea").editable ("'.$jQueryCallback.'",{
        					/*indicator : busyIcon,*/
        					tooltip: "Click to edit",
							type:\'textarea\'
						}).mouseover (function ()
						{
							$(this).addClass("editableOver");
						}).mouseout (function ()
						{
							$(this).removeClass("editableOver");
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
										moveItem ("notes", this.id.split ("_")[1], drag.id.split ("_")[1],\''.session_id().'\');	
									}
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
									deleteItem ("notes", theType, what[1]);
								}
							});
    					});
						loadNotePositions ();
					</script>
		';
		
		if (isset($item) && $item->itemId != 0)
		{
			$resultString .= '<h2>'.$item->name.'</h2>
			';
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
			$resultString .= '<div id="item_'.$item->itemId.'" class="dndFolder">';	
			$resultString .= '<a href="';
			$resultString .= $this->configuration['callback'];
	        $resultString .= '&amp;action=modify&amp;itemId='.$item->itemId;
	        $resultString .= '&amp;parentId='.$item->parentId.'">';
			$resultString .= $this->configuration['icons'] ['folder_closed'];
			$resultString .= '</a>';
       	}
       	else 
       	{
			$resultString .= $this->configuration['icons'] ['folder_closed'];
       	}
		$resultString .= '<a href="';
		$resultString .= $this->configuration['callback'];
		$resultString .= '&amp;action=show&amp;parentId=';
		$resultString .= $item->itemId . '">';
		$resultString .= $item->name;
		$resultString .= '</a>';
		if ($item->owner == $_SESSION['brimUsername'])
       	{
			$resultString .= '</div>';
		}
		else
		{
			$resultString .= '<br />';
		}
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
		if ($item->description == "")
		{
			$item->description = '    ';
		}
		$theDesc = wordwrap($item->description, 40, ' ', true);
		$resultString  = '';
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '<div id="item_'.$item->itemId.'" 
				class="note draggable">
				<div></div>
				<div id="name_'.$item->itemId.'" class="noteHeader editableText">'.$item->name.'</div>
				<br />
				<div id="description_'.$item->itemId.'" class="noteDescription editableTextarea">'.$theDesc.'</div>
			</div>';
		}
		else
		{
			//
			// Hmmm.... this means that you cannot reposition items of other users..
			// TBD BARRY FIXME
			//
			$resultString .= '<div id="item_'.$item->itemId.'" 
				class="note">
				<div></div>
				<div id="name_'.$item->itemId.'" class="noteHeader">'.$item->name.'</div>
				<br />
				<div id="description_'.$item->itemId.'" class="noteDescription">'.$theDesc.'</div>
			</div>';
		}
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
