<?php

require_once ("framework/util/StringUtils.php");
require_once ('framework/view/TreeDelegate.php');

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2004
 * @package org.brim-project.plugins.passwords
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class PasswordYahooTreeDelegate extends TreeDelegate
{
	/**
	 * Default constructor
	 */
	function PasswordYahooTreeDelegate ($theConfiguration)
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
		if (isset ($item) && ($item != null) && $item->itemId != 0)
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
		$resultString = '<a href="';
		$resultString .= $this->configuration['callback'];
        $resultString .= '&amp;action=modify&amp;itemId='.$item->itemId;
        $resultString .= '&amp;parentId='.$item->parentId.'">';
		$resultString .= $this->configuration['icons'] ['folder_closed'];
		$resultString .= '</a>';

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
		$resultString  = '
						 <table border="0" valign="top" width="200"  ';
		$resultString .= 'bgcolor="#ffffff"	cellpadding="0" cellspacing="5">';


		//
		//	header: title with link, icons (delete+modify)
		//	and popup
		//
		$resultString .= '<tr><td class="menu">';
		$resultString .= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
		$resultString .= '<tr bgcolor="yellow">';
		$resultString .= '<td>';
		//$resultString .= '<a href="javascript:passPhrase (\'showItem\', \''.$item->name.'\', '.$item->itemId.')">';
		$resultString .= '<a href="'.$this->configuration['callback'];
		$resultString .= '&amp;action=showAskPassphrase';
		$resultString .= '&amp;itemId='.$item->itemId;
		$resultString .= '">';
		$resultString .= '<b>'.$this->stringUtils->truncate ($item->name, 30).'</b>';
		$resultString .= '</a>';

		$resultString .= '</td><td width="20" align="right">';
		//$resultString .= '<a href="javascript:passPhrase (\'modify\', \''.$item->name.'\', '.$item->itemId.')">';
		$resultString .= '<a href="'.$this->configuration['callback'];
		$resultString .= '&amp;action=modifyAskPassphrase';
		$resultString .= '&amp;itemId='.$item->itemId;
		$resultString .= '">';
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
		$resultString .= '</td></tr></table></td></tr><tr><td bgcolor="f3f3f3">';
		//$resultString .= $this->stringUtils->truncate ($item->description, 60);
		$resultString .= $this->stringUtils->newlinesToHtml ($item->description);
		$resultString .= '</td></tr></table>';

		$resultString .= '<br />';
		return $resultString;
	}
}
?>