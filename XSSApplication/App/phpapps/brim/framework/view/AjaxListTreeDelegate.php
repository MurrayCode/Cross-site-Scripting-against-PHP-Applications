<?php

require_once ('framework/util/StringUtils.php');
require_once ('framework/view/TreeDelegate.php');
require_once ('framework/util/BrowserUtils.php');

/**
 * Used in combination with the Tree render classes
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - October 2006
 * @package org.brim-project.framework
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class AjaxListTreeDelegate extends TreeDelegate
{
	var $browserUtils;

	/**
	 * Default constructor
	 *
	 * @param array theConfiguration the configuration
	 * @param string theCallbackURL
	 */
	function AjaxListTreeDelegate ($theConfiguration)
	{
		parent::TreeDelegate ($theConfiguration);
		$this->browserUtils = new BrowserUtils ();
	}

	/**
	 * Builds up html code to display the root of the tree
	 * @private
	 *
	 * @param object item the item that is the root of the tree
	 */
	function showRoot ($item, $tree)
	{
		$resultString = '
		<script type="text/javascript">

		/**
		 * From the interface drag-and-drop tree
		 * http://interface.eyecon.ro/
		 */
		function setupSubFolders (folder)
		{
			$("li", folder.get(0)).each( function()
			{
				subbranch = $("ul", this);
				if (subbranch.size() > 0) {
					if (subbranch.eq(0).css("display") == "none") {
						$(this).prepend("<img src=\"framework/view/pics/tree/shaded_plus.gif\" class=\"expandImage\" />");
					} else {
						$(this).prepend("<img src=\"framework/view/pics/tree/shaded_minus.gif\" class=\"expandImage\" />");
					}
				} else {
					$(this).prepend("<img src=\"framework/view/pics/tree/spacer.gif\" class=\"expandImage\" />");
				}
			});
		$("img.expandImage", folder.get(0)).click(
			function()
			{
				var itemId = this.parentNode.id.split("_")[1];
				if (this.src.indexOf("spacer") == -1) {
					subbranch = $("ul", this.parentNode).eq(0);
					if (subbranch.css("display") == "none") {
						if ($("li", subbranch).length > 0)
						{
							// Already expanded
						}
						else
						{
							// Not yet expanded
							expandFolder (itemId);
							setTimeout ("setupSubFolders (subbranch)", 500);
						}
						subbranch.show();
						this.src = "framework/view/pics/tree/shaded_minus.gif";
					} else {
						subbranch.hide();
						this.src = "framework/view/pics/tree/shaded_plus.gif";
					}
				}
			}
		);
		}
		$(document).ready(
			function()
			{
				tree = $("#myTree");
				setupSubFolders (tree);
			});
		</script>';

		if (isset($item) && $item->itemId != 0)
		{
			$resultString .= '<h2>'.$item->name.'</h2>';
			$resultString .= '<a href="'.$this->configuration['callback'];
			$resultString .= '&amp;action=show&amp;parentId='.$item->parentId.'">';
			$resultString .= $this->configuration['icons']['up'];
			$resultString .= '</a>';
		}
		return $resultString;
	}

	/**
	 * If the specified item is a folder, draw it
	 *
	 * @private
	 * @param object item the item to draw
	 * @param boolean isExpanded whether this folder is expanded
	 * (i.e. do the children of this folder need to be displayed
	 * as well?)
	 * @param object tree the tree that uses this class as delegate
	 * @return string the string for this folder
	 */
	function drawFolder ($item, $isExpanded, $tree, $indentLevel)
	{
		$resultString  = '
		<li class="treeFolder" id="item_'.$item->itemId.'">';
			/*
			if ($isExpanded)
			{
            	$resultString .= '<a href="expandFolder('.$item->itemId.')">';
            	$resultString .= $this->configuration['icons']['minus'];
            	$resultString.= '</a>';
			}
			else
			{
            	$resultString .= '<a href="javascript:expandFolder('.$item->itemId.')">';
            	$resultString .= $this->configuration['icons']['plus'];
            	$resultString.= '</a>';
			}
			*/
            if ($item->owner == $_SESSION['brimUsername'])
            {
                $resultString.= '<a href="';
                $resultString .= $this->configuration['callback'];
                $resultString .= '&amp;action=modify&amp;itemId=';
                $resultString .= $item->itemId;
                if (isset($tree->root))
                {
                    $resultString .= '&amp;parentId='.$tree->root->itemId;
                }
                $resultString .= '">';
			}
			if ($isExpanded)
			{
               	$resultString .= $this->configuration['icons']['folder_open'];
			}
			else
			{
               	$resultString .= $this->configuration['icons']['folder_closed'];
			}
            if ($item->owner == $_SESSION['brimUsername'])
            {
            	$resultString .= '</a>';
			}

			$resultString .= '
			<a href="'.$this->configuration['callback'].
				'&amp;action=show&amp;parentId='.$item->itemId.'">';
					$resultString .= $item->name;
					$resultString .= '
			</a>
			<ul style="display:none">
		';
		return $resultString;
	}


	function closeFolder ($item, $isExpanded, $tree, $indentLevel)
	{
		$resultString = '
			</ul>
		</li>';
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
		$resultString  = '
			<li class="treeItem" id="item_'.$item->itemId.'">';
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '<a href="';
			$resultString .= $this->configuration['callback'];
			$resultString .= '&amp;action=modify&amp;itemId=';
			$resultString .= $item->itemId;
	        if (isset($tree->root))
			{
				$resultString .= '&amp;parentId='.$tree->root->itemId;
			}
	        $resultString .= '">';
        }
        if (isset ($item->favicon) && $item->favicon != '' &&
            $_SESSION['bookmarkFavicon']=='1' &&
            !$this->browserUtils->browserIsExplorer())
        {
            $resultString .= '<img src="data:image/x-icon;base64,'.$item->favicon.'" ';
            $resultString .= 'height="16" width="16" border="0">';
        }
        else
        {
            $resultString .= $this->configuration['icons']['node'];
        }
        if ($item->owner == $_SESSION['brimUsername'])
        {
            $resultString .= '</a>';
        }

		//
		// Visibility icon
		//
		$resultString .= "&nbsp;";
		if ($item->visibility=='public')
		{
			$resultString .= $this->configuration['icons']['unlocked'];
		}
		else
		{
			$resultString .= $this->configuration['icons']['locked'];
		}
		$resultString .= "&nbsp;";
		//
		// End visibility icon
		//
		$resultString .= "<a href='";
		$resultString .= $this->configuration['callback'];
		$resultString .= '&amp;action=showBookmark&amp;itemId=';
		$resultString .= $item->itemId . "' ";
		$resultString .= 'alt="'. $this->stringUtils->gpcAddSlashes 
			($this->stringUtils->urlEncodeQuotes($item->name)) . '">';
		$resultString .= $item->name;
		$resultString .= '
					</a>';
		$resultString .= '</li>';
		return $resultString;
	}
}
?>
