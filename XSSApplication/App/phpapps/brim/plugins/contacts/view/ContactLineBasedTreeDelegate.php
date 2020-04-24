<?php

require_once ("framework/util/StringUtils.php");
require_once ("framework/view/TreeDelegate.php");
include 'plugins/contacts/view/contactOverlib.php';

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - January 2004
 * @package org.brim-project.plugins.contacts
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class ContactLineBasedTreeDelegate extends TreeDelegate
{
	/**
	 * Have we already drawn a header?
	 */
	var $headersDrawn;

	function ContactLineBasedTreeDelegate ($theConfiguration)
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
//		$resultString = '<td colspan="8"></td></tr><tr>';
		$resultString = '<tr>';
		$resultString .= '<td>&nbsp;</td>';
		$resultString .= '<td>&nbsp;</td>';

		$resultString .= '<td><span style="white-space: nowrap;"><b>'.$this->configuration['dictionary']['name'];
		$resultString .= $this->sortArrows ('name', $parentId);
		$resultString .= '</b></span></td>';

		$resultString .= '<td><span style="white-space: nowrap;"><b>'.$this->configuration['dictionary']['email'];
		$resultString .= $this->sortArrows ('email1', $parentId);
		$resultString .= '</b></span></td>';

		$resultString .= '<td><span style="white-space: nowrap;"><b>'.$this->configuration['dictionary']['mobile'];
		$resultString .= $this->sortArrows ('mobile', $parentId);
		$resultString .= '</b></span></td>';

		$resultString .= '<td><span style="white-space: nowrap;"><b>'.$this->configuration['dictionary']['tel_work'];
		$resultString .= $this->sortArrows ('tel_work', $parentId);
		$resultString .= '</b></span></td>';

		$resultString .= '<td><span style="white-space: nowrap;"><b>'.$this->configuration['dictionary']['tel_home'];
		$resultString .= $this->sortArrows ('tel_home', $parentId);
		$resultString .= '</b></span></td>';

		$resultString .= '<td><span style="white-space: nowrap;"><b>'.$this->configuration['dictionary']['webaddress'];
		$resultString .= $this->sortArrows ('webaddress1', $parentId);
		$resultString .= '</b></span></td>';
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
	        $resultString .= $this->configuration['icons']['edit'];
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
			$resultString .= $this->configuration['icons']['delete'];
		}
		$resultString .= '</td>';

		$resultString .= '<td>';
		$resultString .= '<a href="'.$this->configuration['callback'];
        $resultString .= '&amp;action=showItem&amp;itemId='.$item->itemId;
        $resultString .= '&amp;parentId='.$item->parentId.'" ';
		if ($this->configuration['overlib'])
		{
			$popUp = overlibPopup ($item, $this->configuration['dictionary']);
			$resultString .= $this->overLib ((str_replace("&", "&amp;", $item->name)), $popUp, 'STICKY');
		}
		$resultString .= '>';
		$resultString .= '<b>'.$this->stringUtils->truncate ((str_replace("&", "&amp;", $item->name)), 30). '</b></a>';
		$resultString .= '</td>';

		$resultString .= '<td><a href="mailto:';
		if (isset ($item->email1) && $item->email1 != "")
		{
			$resultString .= $item->email1.'">'.$item->email1;
		}
		else if (isset ($item->email2) && $item->email2 != "")
		{
			$resultString .= $item->email2.'">'.$item->email2;
		}
		else
		{
			$resultString .= $item->email3.'">'.$item->email3;
		}
		$resultString .= '</a></td>';
		$resultString .= '<td>';

		if (isset ($item->mobile))
		{
			$resultString .= $item->mobile;
		}
		else
		{
			$resultString .= '&nbsp;';
		}
		$resultString .= '</td>';
		$resultString .= '<td>';
		if (isset ($item->tel_work))
		{
			$resultString .= $item->tel_work;
		}
		else
		{
			$resultString .= '&nbsp;';
		}
		$resultString .= '</td>';
		$resultString .= '<td>'.$item->tel_home.'</td>';
		if (isset ($item->webaddress1) && $item->webaddress1 != '')
		{
			$resultString .= '<td><a href="'.$item->webaddress1.'">'.$this->configuration['dictionary']['clickHere'].'</a></td>';
		}
		else
		{
			$resultString .= '<td>&nbsp;</td>';
		}
		return $resultString;
	}
}

?>
