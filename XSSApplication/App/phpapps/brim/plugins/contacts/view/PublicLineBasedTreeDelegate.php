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
 * @author Barry Nauta - January 2004
 * @package org.brim-project.plugins.contacts
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class PublicLineBasedTreeDelegate extends TreeDelegate
{
	/**
	 * Have we already drawn a header?
	 */
	var $headersDrawn;

	function PublicLineBasedTreeDelegate ($theConfiguration)
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

		if ($item->itemId != 0)
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
		$resultString .= $this->configuration['icons']['folder_closed'];

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
		$resultString = '<td colspan="8"></td></tr><tr>';
		$resultString .= '<td>&nbsp;</td>';
		$resultString .= '<td>&nbsp;</td>';

		$resultString .= '<td><b>'.$this->configuration['dictionary']['name'];
		$resultString .= $this->sortArrows ('name', $parentId);
		$resultString .= '</b></td>';

		$resultString .= '<td><b>'.$this->configuration['dictionary']['email'];
		$resultString .= $this->sortArrows ('email1', $parentId);
		$resultString .= '</b></td>';

		$resultString .= '<td><b>'.$this->configuration['dictionary']['mobile'];
		$resultString .= $this->sortArrows ('mobile', $parentId);
		$resultString .= '</b></td>';

		$resultString .= '<td><b>'.$this->configuration['dictionary']['tel_work'];
		$resultString .= $this->sortArrows ('tel_work', $parentId);
		$resultString .= '</b></td>';

		$resultString .= '<td><b>'.$this->configuration['dictionary']['tel_home'];
		$resultString .= $this->sortArrows ('tel_home', $parentId);
		$resultString .= '</b></td>';

		$resultString .= '<td><b>'.$this->configuration['dictionary']['webaddress'];
		$resultString .= $this->sortArrows ('webaddress1', $parentId);
		$resultString .= '</b></td>';

		$resultString .= '</tr><tr class="odd">';
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
		if ($this->headersDrawn)
		{
			$resultString = '';
		}
		else
		{
			$resultString = $this->drawLinesHeader ($item->parentId);
			$this->headersDrawn = true;
		}
		// header with two icons and overlib popup
		$resultString .= '<td width="20" align="right">';
		if ($item->owner == $_SESSION['brimUsername'])
		{
			$resultString .= '<a href="'.$this->configuration['callback'].'&amp;itemId=';
			$resultString .= $item->itemId.'&amp;action=modify" ';
			$resultString .= '>'.$this->configuration['icons']['edit'].'</a>';
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
			$resultString .= '&amp;action=deleteContact" ';
			// delete confirmation
			$resultString .= 'onclick="javascript:return confirm (\'';
			$resultString .= $this->configuration['dictionary']['confirm_delete'];
			$resultString .= '\');" ';
			$resultString .= '>'.$this->configuration['icons']['delete'].'</a>';
		}
		else 
		{
			$resultString .= '>'.$this->configuration['icons']['delete'].'</a>';		
		}
		$resultString .= '</td>';

		$resultString .= '<td>';
		$resultString .= '<a href="'.$this->configuration['callback'].'&amp;itemId='.$item->itemId.'&amp;action=showItem" ';
		if ($this->configuration['overlib'])
		{
			$popUp = $this->overlibPopup ($item);
			$resultString .= $this->overLib (str_replace("&", "&amp;", ($item->name)), $popUp);
		}
		$resultString .= '>';
		$resultString .= '<b>'.$this->stringUtils->truncate (str_replace("&", "&amp;", ($item->name, 30))). '</b></a>';
		$resultString .= '</td>';

		$resultString .= '<td><a href="mailto:'.$item->email1.'">'.$item->email1.'</a></td>';
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
		if (isset ($item->webaddress1))
		{
			$resultString .= '<td><a href="'.$item->webaddress1.'">'.$item->webaddress1.'</td>';
		}
		else
		{
			$resultString .= '<td>&nbsp;</td>';
		}
		return $resultString;
	}

	function overlibPopup ($item)
	{
		if ($this->configuration['overlib'])
		{
			$popUp  = '<table cellspacing=2>';
			if (isset ($item->alias))
			{
				$popUp .= "<tr>";
				$popUp .= "<td><b>".$this->configuration['dictionary']['alias'].":</b></td>";
				$popUp .= "<td>".$item->alias."</td>";
				$popUp .= "</tr>";
			}
			if (isset ($item->email1))
			{
				$popUp .= "<tr>";
				$popUp .= "<td><b>".$this->configuration['dictionary']['email']."&nbsp;1:</b></td>";
				$popUp .= "<td>".$item->email1."</td>";
				$popUp .= "</tr>";
			}
			if (isset ($item->email2))
			{
				$popUp .= "<tr>";
				$popUp .= "<td><b>".$this->configuration['dictionary']['email']."&nbsp;2:</b></td>";
				$popUp .= "<td>".$item->email2."</td>";
				$popUp .= "</tr>";
			}
			if (isset ($item->email3))
			{
				$popUp .= "<tr>";
				$popUp .= "<td><b>".$this->configuration['dictionary']['email']."&nbsp;3:</b></td>";
				$popUp .= "<td>".$item->email3."</td>";
				$popUp .= "</tr>";
			}
			if (isset ($item->webaddress1))
			{
				$popUp .= "<tr>";
				$popUp .= "<td><b>".$this->configuration['dictionary']['webaddress']."&nbsp;1:</b></td>";
				$popUp .= "<td>".$item->webaddress1."</td>";
				$popUp .= "</tr>";
			}
			if (isset ($item->webaddress2))
			{
				$popUp .= "<tr>";
				$popUp .= "<td><b>".$this->configuration['dictionary']['webaddress']."&nbsp;2:</b></td>";
				$popUp .= "<td>".$item->webaddress2."</td>";
				$popUp .= "</tr>";
			}
			if (isset ($item->webaddress3))
			{
				$popUp .= "<tr>";
				$popUp .= "<td><b>".$this->configuration['dictionary']['webaddress']."&nbsp;3:</b></td>";
				$popUp .= "<td>".$item->webaddress3."</td>";
				$popUp .= "</tr>";
			}
			if (isset ($item->tel_home))
			{
				$popUp .= "<tr>";
				$popUp .= "<td><b>".$this->configuration['dictionary']['tel_home'].":</b></td>";
				$popUp .= "<td>".$item->tel_home."</td>";
				$popUp .= "</tr>";
			}
			if (isset ($item->tel_work))
			{
				$popUp .= "<tr>";
				$popUp .= "<td><b>".$this->configuration['dictionary']['tel_work'].":</b></td>";
				$popUp .= "<td>".$item->tel_work."</td>";
				$popUp .= "</tr>";
			}
			if (isset ($item->mobile))
			{
				$popUp .= "<tr>";
				$popUp .= "<td><b>G.S.M.:</b></td>";
				$popUp .= "<td>".$item->mobile."</td>";
				$popUp .= "</tr>";
			}
			if (isset ($item->faximile))
			{
				$popUp .= "<tr>";
				$popUp .= "<td><b>".$this->configuration['dictionary']['faximile'].":</b></td>";
				$popUp .= "<td>".$item->faximile."</td>";
				$popUp .= "</tr>";
			}
			if (isset ($item->description))
			{
				$popUp .= "<tr>";
				$popUp .= '<td colspan="2">'.$item->description."</td>";
				$popUp .= "</tr>";
			}
			$popUp .= "</table>";
		}
		return $popUp;
	}
}
?>