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
class NoteYahooTreeDelegate extends TreeDelegate
{
	/**
	 * Default constructor
	 */
	function NoteYahooTreeDelegate ($theConfiguration)
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
		$resultString = '';
		//$resultString .= '<div id="nifty">';
		$resultString .= '
						 <table border="1" valign="top" width="200"  ';
		$resultString .= 'bgcolor="#ffffff"	cellpadding="0" cellspacing="5">';

		//
		//	Note header: title with link, icons (delete+modify)
		//	and note popup
		//
		$resultString .= '<tr><td class="menu">';
		$resultString .= '<table width="200" cellpadding="0" cellspacing="0" border="0">';
		$resultString .= '<tr bgcolor="yellow">';
		$resultString .= '<td>';
		$resultString .= '<a href="'.$this->configuration['callback'];
        $resultString .= '&amp;action=showItem&amp;itemId='.$item->itemId;
        $resultString .= '&amp;parentId='.$item->parentId.'" ';
		if ($this->configuration['overlib'])
		{
			$resultString .= $this->overLib ($item->name, $item->description);
		}
		$resultString .= '<b>'.$this->stringUtils->truncate ($item->name, 30). '</b></a>';
		$resultString .= '</td><td width="20" align="right">';
		if ($item->owner == $_SESSION['brimUsername'])
       	{
			$resultString .= '<a href="'.$this->configuration['callback'];
	        $resultString .= '&amp;action=modify&amp;itemId='.$item->itemId;
	        $resultString .= '&amp;parentId='.$item->parentId.'">';
			$resultString .= $this->configuration['icons']['edit'].'</a>';
			$resultString .= '</td><td width="20" align="right">';
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
			$resultString .= $this->configuration['icons']['edit'];
			$resultString .= $this->configuration['icons']['delete'];
       	}
		$resultString .= '</td></tr></table></td></tr><tr><td bgcolor="f3f3f3">';
		$desc = $this->stringUtils->newlinesToHtml ($item->description);
		// tricky: split the description into an array of words and check the length
		// of each word, truncate if necessary
		$theDesc = explode (' ', $desc);
		for ($i=0; $i<count($theDesc); $i++)
		{
			$word = $theDesc[$i];
			if (strlen($word) > 30)
			{
				$theDesc[$i] = $this->stringUtils->truncate ($word, 30);
			}
		}
		$resultString .= implode (' ',$theDesc);
		$resultString .= '</td></tr></table>';

		//$resultString .= '<br />';
		//$resultString .= '</div>';
		return $resultString;
	}
}
?>