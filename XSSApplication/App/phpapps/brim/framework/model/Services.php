<?php

require_once ('framework/util/databaseConnection.php');
require_once ('framework/util/StringUtils.php');

/**
 * Services abstract base class.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.framework
 * @subpackage model
 *
 * @abstract
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class Services
{
	/**
	 * Common string utilities
	 * @var object
	 */
	var $stringUtils;

	/**
	 * The specific queries for this sevice
	 * @var array
	 */
	var $queries;

	/**
	 * The factory used to construct dedicated items
	 * @var object
	 */
	var $itemFactory;

	/**
	 * Default constructor
	 */
 	function Services ()
 	{
 		$db = null;
		$this->stringUtils = new StringUtils ();
		include ('framework/util/databaseConnection.php');
		$this->db = $db;
 	}

	/**
	 * Retrieves all items for a user
	 * @param integer userId the identifier for the user
	 * @return array an array of the items for this user
	 */
	function getItems ($userId)
	{
		$query = sprintf ($this->queries['getItems'], $userId);
		$result = $this->db->Execute($query) or
			die("GetItems: " . $this->db->ErrorMsg()." ".$query);
		return $this->itemFactory->resultsetToItems ($result);
	}

	/**
	 * Retrieves all trashed items for a user (trashed items have
	 * a 'is_deleted' flag set.
	 *
	 * @param integer userId the identifier for the user
	 * @return array an array of the trashed items for this user
	 */
	function getTrashedItems ($userId)
	{
		$query = sprintf ($this->queries['getTrashedItems'], $userId);
		$result = $this->db->Execute($query) or
			die("GetTrashedItems: " . $this->db->ErrorMsg()." ".$query);
		return $this->itemFactory->resultsetToItems ($result);
	}

	function trash ($userId, $itemId)
	{
		$query = sprintf ($this->queries['handleTrash'], 1, $userId, $itemId);
		$result = $this->db->Execute($query) or
			die("HandleTrash: " . $this->db->ErrorMsg()." ".$query);
		return true;	
	}

	function unTrash ($userId, $itemId)
	{
		$query = sprintf ($this->queries['handleTrash'], 0, $userId, $itemId);
		$result = $this->db->Execute($query) or
			die("HandleTrash: " . $this->db->ErrorMsg()." ".$query);
		return true;	
	}

	/**
	 * Retrieves all items for a user, sorted by the indicated value
	 * @param integer userId the identifier for the user
	 * @param integer parentId the identifier of the parent folder in
	 * 		which we would like to sort
	 * @param string field the field on which we would like to sort
	 * @return array an array of the sorted items
	 */
	function getSortedItems ($userId, $parentId, $field, $sortOrder)
	{
		$query = sprintf
			($this->queries['getSortedItems'], $userId, $parentId,
				$field, $sortOrder);
		$result = $this->db->Execute($query) or
			die("GetSortedItems: " . $this->db->ErrorMsg().' '.$query);
		return $this->itemFactory->resultsetToItems ($result);
	}

	/**
	 * Retrieves a limited number of items for a user,
	 * sorted by the indicated value
	 *
	 * @param integer userId the identifier for the user
	 * @param string field the field on which we would like to sort
	 * @param integer number the number on which we would like to limit
	 * @return array an array of the sorted items
	 */
	function getLimitedSortedItems ($userId, $field, $sortOrder, $number)
	{
		$query = sprintf ($this->queries['getDashboard'],
			$userId, $field, $sortOrder);
		$result = $this->db->SelectLimit($query, $number) or
			die("GetLimitedSortedItems: " . $this->db->ErrorMsg().' '.$query);
		return $this->itemFactory->resultsetToItems ($result);
	}

	/**
	 * Retrieves all items for a user, sorted by the indicated value
	 *
	 * @param integer userId the identifier for the user
	 * @param string field the field on which we would like to sort
	 * @return array an array of the sorted items
	 */
	function getAllSortedItems ($userId, $field, $sortOrder)
	{
		$query = sprintf
			($this->queries['getAllSortedItems'],
				$userId, $field, $sortOrder);
		$result = $this->db->Execute($query) or
			die("GetAllSortedItems: " . $this->db->ErrorMsg().' '.$query);
		return $this->itemFactory->resultsetToItems ($result);
	}

	/**
	 * Retrieves all items for a user, sorted by the indicated value
	 *
	 * @param integer userId the identifier for the user
	 * @param string field the field on which we would like to sort
	 * @return array an array of the sorted items
	 */
	function getPublicSortedItems ($userId, $parentId, $field, $sortOrder)
	{
		$query = sprintf
			($this->queries['getPublicSortedItems'], $parentId,
				$userId, $field, $sortOrder);
		$result = $this->db->Execute($query) or
			die("GetSortedItems: " . $this->db->ErrorMsg());
		return $this->itemFactory->resultsetToItems ($result);
	}

	/**
	 * Retrieves all public items for a user, sorted by the indicated value
	 *
	 * @param integer userId the identifier for the user
	 * @param string field the field on which we would like to sort
	 * @return array an array of the sorted items
	 * @author Michael Haussmann
	 */
	function getAllPublicSortedItems ($userId, $parentId, $field, $sortOrder)
	{
		$query = sprintf
			($this->queries['getAllPublicSortedItems'], $userId,
				$parentId, $field, $sortOrder);
		$result = $this->db->Execute($query) or
			die("GetAllPublicSortedItems: " . $this->db->ErrorMsg().' '.$query);
		return $this->itemFactory->resultsetToItems ($result);
	}


	/**
	 * Returns the owner of the item
	 *
	 * @param integer itemId the identifier of the item for which
	 *	we would like to know the owner
	 * @return string the owner of the item
	 *
	 * @todo make sure that not everyone can simply execute this
	 *	function.
	 */
	function getItemOwner ($itemId)
	{
		$query = sprintf ($this->queries['getItemOwner'], $itemId);
		$result = $this->db->Execute($query) or
			die("GetItemOwner: " .  $this->db->ErrorMsg() . " " . $query);
		return trim ($result->fields[0]);
	}

	/**
	 * Delete a item (with a specified ID) for a specific
	 * user. This function will recurively delete all children
	 * of this item if this item is a parent/folder!
	 *
	 * @param string userId the identifier for the user
	 * @param string itemId the identifier for the item that will
	 *        be deleted
	 */
	function deleteItem ($userId, $itemId)
	{
		$item = $this->getItem ($userId, $itemId);
		//
		// if we are a parent, delete the children as well....
		//
		if ($item->isParent==1)
		{
			$children = $this->getChildren ($userId, $itemId);
			for ($i=0; $i<count($children); $i++)
			{
				$this->deleteItem ($userId, $children[$i]->itemId);
			}
		}
		//
		// now delete the specific item (children are already
		// deleted if this is a parent item)
		//
		$query = sprintf ($this->queries['deleteItem'], $itemId);
		$result = $this->db->Execute($query) or
			die("DeleteItem: " .
				$this->db->ErrorMsg() . " " . $query);
	}

	/**
	 * Adds an item for a user.
	 *
	 * @abstract
	 * @param integer userId the identifier for the user
	 * @param object item the item to be added
	 * @todo remove this empty function?
	 */
	function addItem ($userId, $item)
	{
	}


	/**
	 * Gets a specific item for a user
	 *
	 * @param string userId the identifier for the user
	 * @param string itemId the identifier for the item
	 * @return object the item for specified user with specified itemId
	 */
	function getItem ($userId, $itemId)
	{
		$query = sprintf ($this->queries['getItem'], $userId, $itemId);

		$result = $this->db->Execute($query) or
			die("GetItem: ". $this->db->ErrorMsg()." query: -". $query . "-");

		$items = $this->itemFactory->resultsetToItems ($result);
		if (count ($items) == 0)
		{
			return null;
		}
		else
		{
			return $items[0];
		}
	}


	/**
	 * Gets a specific item for a user
	 *
	 * @param string userId the identifier for the user
	 * @param string itemId the identifier for the item
	 * @return object the item for specified user with specified itemId
	 */
	function getPublicItem ($userId, $itemId)
	{
		$query = sprintf ($this->queries['getPublicItem'], $userId, $itemId);
		//die ($query);
		$result = $this->db->Execute($query) or
			die("GetItem: ".$this->db->ErrorMsg()." query: -".$query."-");
		$items = $this->itemFactory->resultsetToItems ($result);
		if (count ($items) == 0)
		{
			return null;
		}
		else
		{
			//
			// Return one item only
			//
			return $items[0];
		}
	}


	/**
 	 * Checks the owner of the Item
	 *
	 * @param string userId the identifier of the user
	 * @param string itemId the identifier of the item
     	 */
	function checkOwner ($userId, $itemId)
	{
		if ($userId == null || $userId = 0)
		{
			return;
		}
		if ($this->getItemOwner ($itemId) != $userId)
		{
			die ("Not owner -" . $itemId . "- -" . $userId .  "-");
		}
	}

	/**
	 * Retrieves the children of a specified item
	 *
	 * @param string userId the identifier of the user that issues the
	 * request
	 * @param integer itemId the identifier of the item for which we would
	 * like to have its children
	 * @return array all children for specified user and itemId
	 */
	function getChildren ($userId, $itemId)
	{
		$query = sprintf ($this->queries ['getItemChildren'], $itemId, $userId);
		$result = $this->db->Execute ($query) or die
			("Operations. GetChildren " . $this->db->ErrorMsg () .
				" Error getting children: " . $query);
		$items = $this->itemFactory->resultsetToItems($result);
		if ($items == null)
		{
			return null;
		}
		else if ($items[0]->owner == $userId)
		{
			return $items;
		}
		else
		{
			die ("Not owner?");
		}
	}

	/**
	 * Retrieves the children of a specified item. It starts with the
	 * root and retrieves ALL items for this user (including the
	 * private ones) AND ALL PUBLIC items for other users
	 *
	 * @param string userId the identifier of the user that issues the
	 * request
	 * @param integer itemId the identifier of the item for which we would
	 * like to have its children
	 * @return array all children for specified user and itemId
	 */
	function getPublicChildren ($userId, $itemId)
	{
		$query = sprintf
			($this->queries ['getPublicItemChildren'], $itemId, $userId);
		$result = $this->db->Execute ($query) or die
			("Operations. GetPublicChildren " . $this->db->ErrorMsg () .
				" Error getting children: " . $query);
		return $this->itemFactory->resultsetToItems($result);
	}

	/**
	 * Retrieves the children of a specified item
	 *
	 * @param string userId the identifier of the user that issues the
	 * request
	 * @param integer itemId the identifier of the item for which we would
	 * like to have its children
	 * @return array all children for specified user and itemId
	 */
	function getPublicChildrenForUser ($userId, $itemId)
	{
		$query = sprintf
			($this->queries ['getPublicItemChildrenForUser'], $itemId, $userId);
			$result = $this->db->Execute ($query) or die
			("Operations. GetPublicChildrenForUser " . $this->db->ErrorMsg () .
				" Error getting children: " . $query);
		return $this->itemFactory->resultsetToItems($result);
	}

	/**
	 * Retrieves the children of a specified item, but only the children that
	 * are parents themselves
	 *
	 * @param string userId the identifier of the user that issues the
	 * request
	 * @param integer itemId the identifier of the item for which we would
	 * like to have its children
	 * @return array all children for specified user and itemId
	 */
	function getChildrenThatAreParent ($userId, $itemId)
	{
		$query = sprintf
			($this->queries ['getItemChildrenThatAreParent'], $itemId, $userId);
		$result = $this->db->Execute ($query) or die
			("Operations. GetChildrenThatAreParent " . $this->db->ErrorMsg () .
				" Error getting children: " . $query);
		$items = $this->itemFactory->resultsetToItems($result);
		if ($items == null)
		{
			return null;
		}
		else if ($items[0]->owner == $userId)
		{
			return $items;
		}
		else
		{
			die ("Not owner?");
		}
	}


	/**
	 * Modifies an item.
	 *
	 * @abstract
	 * @param string userId the identifier for the user
	 * @param object item the (modified) item (which will still be identified
	 * by its original itemId)
	 */
	function modifyItem ($userId, $item)
	{
	}

	/**
	 * Moves an item for a user to a new parent
	 *
	 * @param string userId the identifier for the user who issues
	 *		the request
	 * @param integer itemId the identifier for the item that is going to
	 * 		be moved
	 * @param integer parentId the new parentId for the item
	 */
	function moveItem ($userId, $itemId, $parentId)
	{
		$query = sprintf
			($this->queries ['moveItem'], $parentId, $userId, $itemId);
		$result = $this->db->Execute ($query) or die
			("Services:moveItem " . $this->db->ErrorMsg () ." " . $query);
	}

	/**
 	 * Search for items for a user
 	 *
 	 * @param string userId the identifier for the user for which we
 	 *  	would like to search for items
 	 * @param string field the field on which we would like to search
 	 * @param string value the value for which we would like to search
 	 *
 	 * @return array all items that match the given search criteria
	 */
	function searchItems ($userId, $field, $value)
	{
		$query = sprintf
			($this->queries ['searchItems'], $field, $value, $userId);
		$result = $this->db->Execute ($query) or die
			("Operations. Search " . $this->db->ErrorMsg () ." " . $query);
		return $this->itemFactory->resultsetToItems($result);
	}

	/**
	 * Search for items for a user
	 *
	 * @param string userId the identifier for the user for which we
	 *  	would like to search for items
	 * @param string field the field on which we would like to search
	 * @param string value the value for which we would like to search
	 *
	 * @return array all items that match the given search criteria
	 */
	function searchPublicItems ($userId, $field, $value)
	{
		$query = sprintf
			($this->queries ['searchPublicItems'], $field, $value, $userId);
		$result = $this->db->Execute ($query) or die
				("Operations. Search " . $this->db->ErrorMsg () ." " . $query);
		return $this->itemFactory->resultsetToItems($result);
	}

	/**
	 * Updates the count of the number of times this item
	 * has been visited
	 *
	 * @param string userId the user that requests the update
	 * @param integer itemId the item for which the count needs to be
	 * increased
	 */
	function updateVisiteCount ($userId, $itemId)
	{
		$query = sprintf ($this->queries['updateItemVisitCount'], $itemId);
		$this->db->Execute($query) or die ("Update visit count failed ".
				$this->db->ErrorMsg () . " " . $query);
	}

	/**
	 * Returns the global username (session variable)
	 * @return string the username (aka loginname)
	 */
	function getUserName ()
	{
		return $_SESSION['brimUsername'];
	}

	/**
	 * Retrieves the public children of a specified item,
	 * but only the children that are parents themselves
	 *
	 * @param string userId the identifier of the user that issues the
	 * request
	 * @param integer itemId the identifier of the item for which we would
	 * like to have its children
	 * @return array all children for specified user and itemId
	 * @author Michael Haussmann
	 */
	function getPublicChildrenThatAreParent ($userId, $itemId)
	{
		$query = sprintf ($this->queries ['getPublicItemChildrenThatAreParent'],
			$itemId, $userId);
		$result = $this->db->Execute ($query) or die
			("Operations. GetPublicChildrenThatAreParent ".$this->db->ErrorMsg () .
				" Error getting children: " . $query);
		$items = $this->itemFactory->resultsetToItems($result);
		if ($items == null)
		{
			return null;
		}
		else if ($items[0]->owner == $userId)
		{
			return $items;
		}
		else
		{
			die ("Not owner?");
		}
	}

	/**
	 * Returns the parent item of an item.
	 * To get all the parents, see getAncestors
	 *
	 * @param object item the item for which we lookup the parent
	 * @return object item the item which is the parent, or null if it has
	 * 		no other parent than root.
	 *
	 * @see getAncestors
	 * @author Michael Haussmann
	 */
	function getParent ($item)
	{
		$query = sprintf ($this->queries['getParent'], $item->itemId);
		$result = $this->db->Execute($query)
			or die ("getParent failed ".
				$this->db->ErrorMsg () . " " . $query);
		$items = $this->itemFactory->resultsetToItems ($result);
		if (count ($items) == 0)
		{
			return null;
		}
		else
		{
			return $items[0];
		}
	}

	/**
	 * Returns an array containing all the ancestors of the given item,
	 * starting with the item itself and upwards.
	 * (the items are returned by value).
	 *
	 * Example result :
	 * Array
	 * (
	 * 	[0] => me
	 * 	[1] => father
	 *	[2] => grandfather
	 * )
	 *
	 * @author Michael, inspired by tim at correctclick dot com (PHP Manual)
	 *		05-Apr-2003 07:48
	 *
	 * Modified by barry: ancestors of me should not contain me myself and I,
	 * 		I guess...? :-) removed the 'me' from the array
	 * @todo check double function
	 */
	function getAncestors ($item)
	{
		$items = array ();
		for ($items[] = $item; $item = $this->getParent ($item); $items[] = $item);
		return $items;
	}

	/**
	 * Retrieves the number of non public children of a specified item.
	 * Only the children that are not parents are counted.
	 * The count is not recursive.
	 *
	 * @param string userId the identifier of the user that issues the
	 * 		request
	 * @param integer itemId the identifier of the item for which we would
	 * 		like to have its children
	 * @return integer the result
	 * @author Michael Haussmann
	 * @static
	 */
	function getChildrenCount ($item)
	{
		static $queries = null; // avoid unecessary multiple inclusions
		if($queries == null)
		{
			$type = strtolower($item->type);
			include ("plugins/".$type."s/sql/".$type."Queries.php");
		}
		$query = sprintf ($queries ['getChildrenCount'],
			$item->itemId, $item->owner);
		$result = $this->db->GetOne ($query);
		if (!$result)
		{
			return 0;
		}
		else
		{
			return $result;
		}
	}

	/**
	 * Retrieves the number of public children of a specified item.
	 * Only the children that are not parents are counted.
	 * The count is not recursive.
	 *
	 * @param string userId the identifier of the user that issues the
	 * request
	 * @param integer itemId the identifier of the item for which we would
	 * like to have its children
	 * @return integer the result
	 * @author Michael Haussmann
	 * @static
	 */
	function getPublicChildrenCount ($item)
	{
		static $queries = null; // avoid unecessary multiple inclusions
		if($queries == null)
		{
			$type = strtolower($item->type);
			include ("plugins/".$type."s/sql/".$type."Queries.php");
		}
		$query = sprintf
			($queries ['getPublicChildrenCount'], $item->itemId, $item->owner);
		$result = $this->db->getOne($query);

		if(!$result) return 0;
		else return $result;
	}

	/**
	 * Returns the number of items for a user
	 *
	 * @param string userId the users identifier
	 * @return integer the number of items
	 * @todo test this function, I don't think it'll work..
	 */
	function getItemCount ($userId)
	{
		static $queries = null; // avoid unecessary multiple inclusions
		if($queries == null)
		{
			$type = strtolower($item->type);
			include ("plugins/".$type."s/sql/".$type."Queries.php");
		}
		$query = sprintf ($queries ['getItemCount'], $userId);
		$result = $this->db->getone($query);
		return $result;
	}

	/**
	 * Delete ALL items for the specified user
	 * @param string userId the name for the user of which all
	 * 	items needs to be deleted
	 */
	function deleteAllItemsForUser ($userId)
	{
		$query = sprintf ($this->queries ['deleteAllForUser'], $userId);
		$this->db->Execute ($query);
	}

	/**
	 * Find doubles in the list of provided items.
	 * The list must be sorted in a way like:
	 * <code>usort ($all, array ('YOUR_ITEM_NAME', 'equals'));</code>
	 * where <code>YOUR_ITEM_NAME</code> point to the class that contains
	 * the equals function which takes two objects and compares them
	 *
	 * @param array items a <em>sorted</em> list of <code>Item</code>s.
	 * @return array the double items found in the input array
	 *
	 */
	function findDoubles ($items)
	{
		reset ($items);
		$result = array ();
		$pivot = null;
		$previousAdded = null;
		//
		// Loop over all items, compare and optionally add to result array
		//
		for ($i=0; $i<count($items); $i++)
		{
			$item = $items[$i];
			//
			// The very first time, the pivor will be null
			//
			if (isset ($pivot))
			{
				//
				// Compare but don't take parents into account
				//
				if (!$pivot->isParent () && $pivot->equals($pivot, $item) == 0)
				{
					if ($previousAdded != $pivot)
					{
						//
						// Only add the pivot if it was not added during the
						// previous run
						//
						$result[] = $pivot;
					}
					$result[] = $item;
					$previousAdded = $item;
				}
			}
			$pivot = $item;
		}
		return $result;

	}

	function getTrashCount ($userId)
	{
		$query = sprintf ($this->queries['getTrashCount'], $userId);
		$result = $this->db->Execute($query)
			or die ("getParent failed ".
				$this->db->ErrorMsg () . " " . $query);
		return $result->fields[0];
	}
}
?>
