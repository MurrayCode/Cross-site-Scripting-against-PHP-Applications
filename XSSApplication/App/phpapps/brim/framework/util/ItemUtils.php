<?php

require_once ('framework/util/StringUtils.php');

/**
 * Item utilities
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - December 2003
 * @package org.brim-project.framework
 * @subpackage util
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class ItemUtils
{
	/**
	 * Empty default constructor
	 */
	function ItemUtils ()
	{
	}

	/**
	 * Export users items (starting from a certain Id)
	 *
	 * @param string id the identifier for the user
	 * @param integer parent the identifier for the parent id of the items
	 * (to enable recursive functioncall)
	 */
	function exportItemsToHTML ($id, $parent, $callback)
	{
	  	$items = $callback->getChildren ($id, $parent);

	  	for ($i=0;$i<count($items); $i++)
	  	{
			$item = $items[$i];
			// Folder. Perform a recursive callback
    		if ($item->isParent == '1')
			{
	      			$this->exportItemsToHTML
					($id, $item->itemId, $callback);
    		}
    		else
    		{
				echo ($item->toHTML());
			}
    	}
	}

	/**
	 * Export users items (starting from a certain Id) to
	 * comma-seperated-value (CSV) format.
	 * This function does not work yet....
	 *
	 * @param string id the identifier for the user
	 * @param integer parent the identifier for the parent id of the items
	 * (to enable recursive functioncall)
	 */
	function exportItemsToCSV ($id, $parent, $callback)
	{
	  	$items = $callback->getChildren ($id, $parent);

	  	for ($i=0;$i<count($items); $i++)
	  	{
			$item = $items[$i];
			// Folder. Perform a recursive callback
    		if ($item->isParent == '1')
			{
	      			$this->exportItemsToHTML
					($id, $item->itemId, $callback);
    		}
    		else
    		{
				echo (implode(",", get_object_vars ($item)));
				echo ('
');
			}
    	}
	}

}
?>