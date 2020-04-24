<?php

require_once ("framework/util/StringUtils.php");
require_once ('framework/view/TreeDelegate.php');

/**
 * Used in combination with the Tree abstract base classes
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - February 2004
 * @package org.brim-project.framework
 * @subpackage view
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class MultipleSelectExplorerTreeDelegate extends TreeDelegate
{
	/**
	 * Default constructor
	 */
	function MultipleSelectExplorerTreeDelegate ($theConfiguration)
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
		$resultString = '<h3>'.$this->configuration['icons']['root'].'</h3>';
		return $resultString;
	}

	/**
	 * If the specified item is a folder, draw it
	 *
	 * @private
	 * @param object item the item to draw
	 * @param boolean isExpanded whether this folder is expanded (i.e. do the
	 * children of this folder need to be displayed as well?)
	 * @param object tree the tree that uses this class as delegate
	 * @return string the string for this folder
	 */
	function drawFolder ($item, $isExpanded, $tree, $indentLevel)
	{
		$resultString = '
								<tr>';
		$resultString .= '<td>&nbsp;</td><td>';
		for ($i=0; $i<$indentLevel; $i++)
		{
			$resultString .= $this->configuration['icons']['bar'];
		}

		$resultString .= $this->configuration['icons']['minus'];
		$resultString .= $this->configuration['icons']['folder_open'];
		$resultString .= $item->name;
		$resultString .= '</td></tr>';

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
		$resultString  = '<tr>';
		$resultString .= '<td><input type="checkbox" name="'.$item->itemId.'" value="itemId"></td>';
		$resultString .= '<td>';
		for ($i=0; $i<$indentLevel; $i++)
		{
			$resultString .= $this->configuration['icons']['bar'];
		}
		if ($lastNode)
		{
			$resultString .= $this->configuration['icons']['corner'];
		}
		else
		{
			$resultString .= $this->configuration['icons']['tee'];
		}
		$resultString .= $this->configuration['icons']['node'];

		$resultString .= $this->stringUtils->gpcAddSlashes ($item->name);

		$resultString .= '</td></tr>';
		return $resultString;
	}
}

function writeSelectJavascript ($dictionary)
{
	$result  = '
		<script type="text/javascript">
			<!-- // Hide from old browsers
				function selectAll ()
				{
					var theForm = document.getElementById ("multipleSelectForm");
					for (var i=0; i<theForm.length; i++)
					{
						currentElement = theForm.elements[i];
						if (currentElement.type == \'checkbox\')
						{
							currentElement.checked = true;
						}
					}
				}

				function deselectAll ()
				{
					var theForm = document.getElementById ("multipleSelectForm");
					for (var i=0; i<theForm.length; i++)
					{
						currentElement = theForm.elements[i];
						if (currentElement.type == \'checkbox\')
						{
							currentElement.checked = false;
						}
					}
				}

				function inverseAll ()
				{
					var theForm = document.getElementById ("multipleSelectForm");
					for (var i=0; i<theForm.length; i++)
					{
						currentElement = theForm.elements[i];
						if (currentElement.type == \'checkbox\')
						{
							currentElement.checked =
								(currentElement.checked) ? false : true;
						}
					}
				}
			//-->
		</script>
	';
	$result .= '<table><tr>';
	$result .= '
		<td><input type="submit"
			value="'.$dictionary['selectAll'].'"
			onClick="javascript:selectAll();" /></td>';
	$result .= '
		<td><input type="submit"
			value="'.$dictionary['deselectAll'].'"
			onClick="javascript:deselectAll();" /></td>';
	$result .= '</tr></table>';
	return $result;
}
?>
