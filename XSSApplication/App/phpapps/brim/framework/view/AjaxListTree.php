<?php

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2006
 * @package org.brim-project.framework
 * @subpackage view
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class AjaxListTree
{
	/**
	 * The root item
	 *
	 * @private
	 * @var object root
	 */
	var $root;

	/**
	 * The delegate that knows how to render folders and nodes
	 *
	 * @private
	 * @var object delegate
	 */
	var $delegate;

	/**
	 * Current level of indentation
	 *
	 * @private
	 * @var integer indent
	 */
	var $indent;

	/**
	 * The string that will contain the fully rendered tree as HTML
	 *
	 * @private
	 * @var string resultString
	 */
	var $resultString;

	/**
	 * The id's of the items that are currently expanded
	 *
	 * @private
	 * @var array an array of item id's that are expanded
	 */
	var $expanded;

	/**
	 * The configuration used for rendering this tree.
	 *
	 * @private
	 * @var array configuration
	 */
	var $configuration;

	/**
	 * Default constructor. The callback object must implement the
	 * function 'getChildren (userId, itemId)',
	 * 'getItem (userId, itemId)'
	 *
	 * @param array theConfiguration
	 * @param object theDelegate
	 */
	function AjaxListTree ($theDelegate, $theConfiguration)
	{
		$this->delegate=$theDelegate;
		$this->indent=0;
		$plus = 'framework/view/pics/tree/shaded_plus.gif';
		$minus = 'framework/view/pics/tree/shaded_minus.gif';
		$space = 'framework/view/pics/tree/blank.gif';
		$blank = 'framework/view/pics/tree/blank.gif';
		$closedFolder = $theConfiguration['icons']['closedFolder']['location'];
		$openFolder = $theConfiguration['icons']['openFolder']['location'];
		$item = $theConfiguration['icons']['item']['location'];
		//$folder_open = 'framework/view/pics/tree/gnome_folder_open.gif';
		echo '
<script type="text/javascript">
/*
function move (itemId, parentId)
{
	alert ("Moving item ["+itemId+"] to parent ["+parentId+"]");
}
*/

function expandFolder (itemId)
{
	var theData = "plugin=bookmarks&ajax=true";
	theData += "&function=getFlatChildrenStructure";
	theData += "&itemId="+itemId;
	theData += "&PHPSESSID='.session_id().'";
	//
	// Call the backend
	//
	$.ajax ({
		type:"POST",
		url:"index.php",
		data:theData,
		success: function(msg)
		{
			childrenFor (itemId, msg);
		}
	});
}

function childrenFor (itemId, msg)
{
	var branch = $("#item_"+itemId+" > ul");
	var result = eval (\'(\'+msg+\')\');
	var items = result["result"];
	var html="";
	if (result["result"] != null)
	{
	for (var i=0; i<items.length; i++)
	{
		html += "<li class=\"treeItem\" id=\"item_"+items[i]["itemId"]+"\">";
		if (items[i]["isParent"]==1)
		{
			//html += "<a href=\"javascript:expandFolder(\'"+items[i]["itemId"]+"\')\"><img src=\"'.$plus.'\" class=\"expandImage\"></a>";
		}
		html += "<a href=\"index.php?plugin=bookmarks&amp;action=modify&amp;itemId="+items[i]["itemId"]+"\">";
		if (items[i]["isParent"]==1)
		{
			html += "<img src=\"'.$closedFolder.'\"></a>";
			html += "<a href=\"index.php?plugin=bookmarks&amp;action=showBookmarks&amp;parentId="+items[i]["itemId"]+"\">";
		}
		else
		{
			html += "<img src=\"'.$item.'\"></a>&nbsp;";
			if (items[i]["visibility"]=="public")
			{
				html += "'.str_replace ('"', '\\"', $theConfiguration['icons']['unlocked']).'"
			}
			else
			{
				html += "'.str_replace ('"', '\\"', $theConfiguration['icons']['locked']).'"
			}
			html += "&nbsp;";
			html += "<a href=\"index.php?plugin=bookmarks&amp;action=showBookmark&amp;itemId="+items[i]["itemId"]+"\">";
		}
		html += items[i]["name"];
		html += "</a>";
		if (items[i]["isParent"]==1)
		{
			html += "<ul style=\"display:none\"></ul>";
		}
	
		html += "</li>";
	}
	}
	branch.append (html);
}
</script>
<style>
#myTree li
{
	list-style-type:none;
}
/*
.treeItem
{
	background-image:url("'.$item.'");
	background-repeat:no-repeat;
	margin: 0px 0px 0px 20px;
}
.treeFolder
{
	background-image: url(pics/tree/gnome_folder_closed.gif);
	margin: 0px 0px 0px 20px;
	background-color:#f00;
}
*/
</style>
';
		echo "<!-- Brim - Tree -->";

		$this->configuration = $theConfiguration;
	}

	/**
	 * Generates the HTML code to display the tree
	 *
	 * @param string userId the user id
	 * @param object root the item that is the root
	 * @param array items the items to display
	 *
	 * @return string the tree rendered as html code
	 */
	function toHtml ($root, $items, $return=false)
	{
		$this->root = $root;
		if ($return)
		{
			$this->resultString .= '<ul id="myTree">';
			$this->resultString .= $this->delegate->showRoot ($root, $this);
			$this->resultString .= '<table cellpadding="0" cellspacing="0">';
			$this->showItems ($items, true);
			$this->resultString .= '</table>';
			$this->resultString .= '</ul>';
			return $this->resultString;
		}
		else
		{
			echo '<ul id="myTree">';
			echo $this->delegate->showRoot ($root, $this);
			echo '<table cellpadding="0" cellspacing="0">';
			$this->showItems ($items);
			echo '</table>';
			echo '</ul>';
		}
	}


	/**
	 * Builds up html code to display the specified items
	 *
	 * @private
	 * @uses indent
	 * @param array items the items to show
	 */
	function showItems ($items, $return=false)
	{
		if ($return)
		{
			$resultString = '';
		}
		for ($i=0; $i<count($items); $i++)
		{
			if ($items[$i]->isParent ())
			{
				//
				// Display parents (folders)
				//
				if ($this->isExpanded ($items[$i]->itemId))
				{
					if ($return)
					{
						$this->resultString .= 
							$this->delegate->drawFolder
								($items[$i], true, $this, $this->indent,
								$this->resultString);
						$this->indent++;
						$this->resultString .= 
							$this->showItems 	
								($items[$i]->getChildren (), true);
						$this->indent--;
						$this->resultString .= 
							$this->delegate->closeFolder
								($items[$i], true, $this, $this->indent,
								$this->resultString);
					}
					else
					{
						echo $this->delegate->drawFolder
							($items[$i], true, $this, $this->indent,
							$this->resultString);
						$this->indent++;
						$this->showItems ($items[$i]->getChildren ());
						$this->indent--;
						echo $this->delegate->closeFolder
								($items[$i], true, $this, $this->indent,
								$this->resultString);
					}
				}
				else
				{
					if ($return)
					{
						$this->resultString .= $this->delegate->drawFolder
							($items[$i], false, $this, $this->indent,
							$this->resultString);
						$this->resultString .= 
							$this->delegate->closeFolder
								($items[$i], true, $this, $this->indent,
								$this->resultString);
					}
					else
					{
						echo $this->delegate->drawFolder
							($items[$i], false, $this, $this->indent,
							$this->resultString);
						echo $this->delegate->closeFolder
								($items[$i], true, $this, $this->indent,
								$this->resultString);
					}
				}
			}
			else
			{
				//
				// Display children (nodes)
				//
				if ($i == count($items)-1)
				{
					// last node is rendered differently
					if ($return)
					{
						$this->resultString .= $this->delegate->drawNode
							($items[$i], true, $this, $this->indent);
					}
					else
					{
						echo $this->delegate->drawNode
							($items[$i], true, $this, $this->indent);
					}
				}
				else
				{
					if ($return)
					{
						$this->resultString .= $this->delegate->drawNode
							($items[$i], false, $this, $this->indent);
					}
					else
					{
						echo $this->delegate->drawNode
							($items[$i], false, $this, $this->indent);
					}
				}
			}
		}
	}



	/**
	 * Sets the icons that will be used to display the tree.
	 * The icons must have the following keys:
	 * 'bar', 'up', 'minus', 'folder_open', 'corner', 'plus', 'tee',
	 * 'folder_closed', 'node', 'before_display', 'after_display'.
	 * If the icons are images, the html code for the images must
	 * be provided as well...
	 *
	 * @param hashtable theIcons
	 */
	 function setIcons ($theIcons)
	 {
	 	$this->delegate->setIcons ($theIcons);
	 }

	/**
	 * returns whether the specified itemId is expanded
	 *
	 * @private
	 * @param integer itemId the identifier which needs to be checked
	 * @return boolean true if this id is in the expanded list,
	 * false otherwise
	 */
	function isExpanded ($itemId)
	{
		if ($this->expanded == null || count ($this->expanded) == 0)
		{
			return false;
		}
		if (count ($this->expanded == 1) && $this->expanded[0] == "*")
		{
			return true;
		}
		reset($this->expanded);
		while (list ($key, $val) = each($this->expanded))
		{
			//
			// yep, in the expanded list
			//
			if ($val == $itemId)
			{
				return true;
			}
		}
	    return false;
	}

	/**
	 * Returns the list of expanded item id's without the
	 * provided itemId
	 *
	 * @param integer itemId the identifier that needs to be
	 * removed from the expanded list
	 * @return array the expanded items
	 */
	function createExpandedListWithout ($itemId)
	{
        reset($this->expanded);
        $newExpanded = array ();
		while (list ($key, $val) = each($this->expanded))
		{
			if (isset ($val) && $val != $itemId && $val != '*')
			{
				$newExpanded [] = $val;
			}
		}
		return $newExpanded;
	}

	/**
	 * Sets the items that are currently expanded.
	 * The string contains a comma seperated value list of ID's
	 *
	 * @param string expandedString
	 */
	function setExpanded ($expandedString)
	{
		$this->expanded=explode(',', $expandedString);
	}
}
?>
