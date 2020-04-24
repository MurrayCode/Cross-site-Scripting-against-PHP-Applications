<?php

require_once ('framework/model/Information.php');
require_once ('framework/model/InformationFactory.php');
require_once ('framework/model/Services.php');

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - January 2004
 * @package org.brim-project.framework
 * @subpackage model
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class InformationServices extends Services
{

	/**
	 * Default constructor
	 */
	function InformationServices ()
	{
		parent :: Services ();
		$this->itemFactory = new InformationFactory ();
		$queries = array ();
		include ('plugins/genealogy/sql/informationQueries.php');
		$this->queries = $queries;
	}

	/**
	 * Adds an item
	 *
	 * @param integer userId the identifier for the user
	 * @param object item the item to be added
	 *
     	 * @return integer last know id. This is the id of the contact
	 * that was newly inserted and for which an id has automatically
	 * been assigned.
	 */
	function addItem ($userId, $item)
	{
		$now = date ("Y-m-d H:i:s");
		$query  = sprintf ($this->queries['addItem'],
			$userId,
			addslashes ($item->parentId),
			addslashes ($item->isParent),
			addslashes ($item->name),
			addslashes ($item->description),
			addslashes ($item->visibility),
			addslashes ($item->category),
			addslashes ($now),
			addslashes ($item->referingId),
			addslashes ($item->referingType),
			addslashes ($item->reliability),
			addslashes ($item->complete),
			addslashes ($item->informationURL),
			addslashes ($item->imageURL),
			addslashes ($item->comments));
		$result = $this->db->Execute($query)
			or die("Add: " . $this->db->ErrorMsg() . " " . $query);
	}

	/**
	 * Modifies an item.
	 *
 	 * @param integer userId the identifier for the user who modifies a note
	 * @param object item the modified note
	 */
	function modifyItem ($userId, $item)
	{
		$this->checkOwner ($userId, $item->itemId);
		$now = date ("Y-m-d H:i:s");
		$query  = sprintf ($this->queries['modifyItem'],
			$now,
			addslashes ($item->name),
			addslashes ($item->description),
			addslashes ($item->parentId),
			addslashes ($item->referingId),
			addslashes ($item->referingType),
			addslashes ($item->reliability),
			addslashes ($item->complete),
			addslashes ($item->informationURL),
			addslashes ($item->imageURL),
			addslashes ($item->comments),
			$item->itemId) ;
		$result = $this->db->Execute($query)
			or die("Modify: " . $this->db->ErrorMsg() . " " . $query);
	}

	/**
	 * Gets a specific item for a user for the specific type
	 *
	 * @param string userId the identifier for the user
	 * @param string itemId the identifier for the item
	 * @param string type the type of information (person etc)
	 * @return object the item for specified user with specified itemId
	 */
	function getItems ($userId, $itemId, $type)
	{
		$query = sprintf ($this->queries['getItemsForType'], $userId, $itemId, $type);
		$result = $this->db->Execute($query) or
			die("GetItem: " .  $this->db->ErrorMsg() . " query: -" .  $query . "-");
		$items = $this->itemFactory->resultsetToItems ($result);
		if (count ($items) == 0)
		{
			return null;
		}
		else
		{
			if ($items[0]->owner == $userId)
			{
				return $items;
			}
			die ("Not owner?");
		}
	}
}
?>