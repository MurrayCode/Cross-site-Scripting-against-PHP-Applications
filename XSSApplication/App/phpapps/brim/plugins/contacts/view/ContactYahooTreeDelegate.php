<?php

require_once ("framework/util/StringUtils.php");
require_once ("framework/view/TreeDelegate.php");

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.plugins.contacts
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class ContactYahooTreeDelegate extends TreeDelegate
{
	/**
	 * Default constructor
	 */
	function ContactYahooTreeDelegate ($theConfiguration)
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
	 * @param boolean isExpanded whether this folder is expanded
	 * (i.e. do the children of this folder need to be displayed as
	 * well?)
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
		$resultString = '<table border="0" valign="top" width="300" bgcolor="#bbbbbb" class="contact">';

		$resultString .= '<tr>';
		$resultString .= '<td class="menu" align="left">';
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '<a ';
			$resultString .= 'href="index.php?plugin=contacts';
	        $resultString .= '&amp;action=modify&amp;itemId='.$item->itemId;
	        $resultString .= '&amp;parentId='.$item->parentId.'">';
			$resultString .=  $this->configuration['icons']['edit'].'</a>';
			$resultString .= '<a ';
			$resultString .= 'href="index.php?plugin=contacts';
			$resultString .= '&amp;itemId='.$item->itemId;
			$resultString .= '&amp;parentId='.$item->parentId;
			$resultString .= '&amp;action=deleteItemPost"';
			// delete confirmation
			$resultString .= 'onclick="javascript:return confirm (\'';
			$resultString .= $this->configuration['dictionary']['confirm_delete'];
			$resultString .= '\');" ';
	
			$resultString .= '>'.$this->configuration['icons']['delete'].'</a>';
		}
		else 
		{
			$resultString .=  $this->configuration['icons']['edit'];
			$resultString .= '>'.$this->configuration['icons']['delete'];	
		}
		$resultString .= '</td>';
		$resultString .= '<td class="menu"><b>';
		$resultString .= '<a ';
		$resultString .= 'href="index.php?plugin=contacts';
        $resultString .= '&amp;action=showItem&amp;itemId='.$item->itemId;
        $resultString .= '&amp;parentId='.$item->parentId.'">';
		$resultString .= $this->stringUtils->truncate($item->name, 20);
		$resultString .= '</a></b>';
		$resultString .= '</td>';
		$resultString .= '</tr>';

		if (isset ($item->alias))
		{
			$resultString .= '<tr>';
			$resultString .= '<td class="contact">';
			$resultString .= $this->configuration['dictionary']['alias'];
			$resultString .= ':</td>';
			$resultString .= '<td class="contact">';
			$resultString .= $this->stringUtils->truncate ($item->alias, 20).'</a>';
			$resultString .= '</td>';
			$resultString .= '</tr>';
		}
		if (isset ($item->email1))
		{
			$resultString .= '<tr>';
			$resultString .= '<td class="contact">';
			$resultString .= $this->configuration['dictionary']['email_home'].':';
			$resultString .= '</td>';
			$resultString .= '<td class="contact">';
			$resultString .= '<a ';
			$resultString .= 'href="mailto:'.$item->email1.'">';
			$resultString .= $this->stringUtils->truncate ($item->email1, 20).'</a>';
			$resultString .= '</td>';
			$resultString .= '</tr>';
		}
		if (isset ($item->email2))
		{
			$resultString .= '<tr>';
			$resultString .= '<td class="contact">';
			$resultString .= $this->configuration['dictionary']['email_work'].':';
			$resultString .= '</td>';
			$resultString .= '<td class="contact">';
			$resultString .= '<a ';
			$resultString .= 'href="mailto:'.$item->email2.'">';
			$resultString .= $this->stringUtils->truncate ($item->email2, 20).'</a>';
			$resultString .= '</td>';
			$resultString .= '</tr>';
		}
		if (isset ($item->email3))
		{
			$resultString .= '<tr>';
			$resultString .= '<td class="contact">';
			$resultString .= $this->configuration['dictionary']['email_other'].':';
			$resultString .= '</td>';
			$resultString .= '<td class="contact">';
			$resultString .= '<a ';
			$resultString .= 'href="mailto:'.$item->email3.'">';
			$resultString .= $this->stringUtils->truncate ($item->email3, 20).'</a>';
			$resultString .= '</td>';
			$resultString .= '</tr>';
		}
		if (isset ($item->webaddress1))
		{
			$resultString .= '<tr>';
			$resultString .= '<td class="contact">';
			$resultString .= $this->configuration['dictionary']['webaddress_homepage'].':';
			$resultString .= '</td>';
			$resultString .= '<td class="contact">';
			$resultString .= '<a ';
			$resultString .= 'href="'.$item->webaddress1.'">';
			$resultString .= $this->stringUtils->truncate ($item->webaddress1, 20).'</a>';
			$resultString .= '</td>';
			$resultString .= '</tr>';
		}
		if (isset ($item->webaddress2))
		{
			$resultString .= '<tr>';
			$resultString .= '<td class="contact">';
			$resultString .= $this->configuration['dictionary']['webaddress_work'].':';
			$resultString .= '</td>';
			$resultString .= '<td class="contact">';
			$resultString .= '<a ';
			$resultString .= 'href="'.$item->webaddress2.'">';
			$resultString .= $this->stringUtils->truncate ($item->webaddress2, 20).'</a>';
			$resultString .= '</td>';
			$resultString .= '</tr>';
		}
		if (isset ($item->webaddress3))
		{
			$resultString .= '<tr>';
			$resultString .= '<td class="contact">';
			$resultString .= $this->configuration['dictionary']['webaddress_home'].':';
			$resultString .= '</td>';
			$resultString .= '<td class="contact">';
			$resultString .= '<a ';
			$resultString .= 'href="'.$item->webaddress3.'">';
			$resultString .= $this->stringUtils->truncate ($item->webaddress3, 20).'</a>';
			$resultString .= '</td>';
			$resultString .= '</tr>';
		}
		if (isset ($item->tel_work))
		{
			$resultString .= '<tr>';
			$resultString .= '<td class="contact">';
			$resultString .= $this->configuration['dictionary']['tel_work'];
			$resultString .= ':</td>';
			$resultString .= '<td class="contact">';
			$resultString .= $item->tel_work;
			$resultString .= '</td>';
			$resultString .= '</tr>';
		}
		if (isset ($item->tel_home))
		{
			$resultString .= '<tr>';
			$resultString .= '<td class="contact">';
			$resultString .= $this->configuration['dictionary']['tel_home'];
			$resultString .= ':</td>';
			$resultString .= '<td class="contact">';
			$resultString .= $item->tel_home;
			$resultString .= '</td>';
			$resultString .= '</tr>';
		}
		if (isset ($item->mobile))
		{
			$resultString .= '<tr>';
			$resultString .= '<td class="contact">';
			$resultString .= $this->configuration['dictionary']['mobile'];
			$resultString .= ':</td>';
			$resultString .= '<td class="contact">';
			$resultString .= $item->mobile;
			$resultString .= '</td>';
			$resultString .= '</tr>';
		}
		if (isset ($item->faximile))
		{
			$resultString .= '<tr>';
			$resultString .= '<td class="contact">';
			$resultString .= $this->configuration['dictionary']['faximile'];
			$resultString .= ':</td>';
			$resultString .= '<td class="contact">';
			$resultString .= $item->faximile;
			$resultString .= '</td>';
			$resultString .= '</tr>';
		}
		if (isset ($item->description))
		{
			$resultString .= '<tr>';
			$resultString .= '<td colspan="2" bgcolor="#ffffff">';
			$resultString .= $this->stringUtils->truncate ($item->description, 30);
			$resultString .= '</td>';
			$resultString .= '</tr>';
		}
		$resultString .= "<tr><td></td><td></td></tr>";
		$resultString .= '</table>';

		return $resultString;
	}
}
?>