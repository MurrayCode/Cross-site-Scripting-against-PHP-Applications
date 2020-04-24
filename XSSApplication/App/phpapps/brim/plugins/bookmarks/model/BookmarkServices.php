<?php

require_once ('plugins/bookmarks/model/Bookmark.php');
require_once ('plugins/bookmarks/model/BookmarkFactory.php');
require_once ('framework/model/Services.php');

/**
 * Bookmark operations. The BusinessLogic operations that can be
 * performed on Bookmarks
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - February 2003
 * @package org.brim-project.plugins.bookmarks
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class BookmarkServices extends Services
{
	/**
	 * Default constructor
	 */
	function BookmarkServices ()
	{
		parent :: Services ();
		$this->itemFactory = new BookmarkFactory ();
		$queries = array ();
		include ('plugins/bookmarks/sql/bookmarkQueries.php');
		$this->queries = $queries;
	}

	/**
	 * Adds a bookmark for a user
	 *
	 * @param integer userId the identifier for the user
	 * @param Bookmark item the bookmark to be added
	 * @return integer the id under which the item is stored in the db
	 */
	function addItem ($userId, $item)
	{
		$now = date ("Y-m-d H:i:s");
		$query = sprintf ($this->queries['addItem'],
			$userId,
			addslashes ($item->parentId),
			addslashes ($item->isParent),
			addslashes ($item->name),
			addslashes ($item->description),
			addslashes ($item->visibility),
			addslashes ($item->category),
			addslashes ($now),
			addslashes ($item->locator),
			addslashes ($item->favicon));
		$this->db->Execute ($query) or die ('AddBookmark: not able to add
			bookmark ' . $query . " " . $this->db->ErrorMsg());
		$query = $this->queries['lastItemInsertId'];
		$result=$this->db->Execute($query)
			or die ("addBookmark: " .$this->db->ErrorMsg () . " " . $query);
		return $result->fields[0];
	}

	/**
	 * Modifies a bookmark
	 * @param integer userId the identifier of the user who modifies
	 * a bookmark
	 *
	 * @param Bookmark item the modified bookmark
	 * @todo Do we really need the userId?
	 */
	function modifyItem ($userId, $item)
	{
		$now = date ("Y-m-d H:i:s");
		$query = sprintf ($this->queries['modifyItem'],
			$now,
			addslashes ($item->name),
			addslashes ($item->parentId),
			addslashes (trim($item->description)),
			$item->isDeleted,
			addslashes ($item->locator),
			$item->visibility,
			addslashes ($item->favicon),
			$item->itemId);
			//addslashes ($item->favicon));
//die (print_r ($query));
		$result = $this->db->Execute ($query) or die
			("ModifyItem Error: " .
				$this->db->ErrorMsg () . " " . $query);
	}

	/**
	 * Updates the visite count for a specific user and its itemId
	 *
	 * @param integer userId the identifier for the user
	 * @param itemId integer itemId the identifier for the item for
	 * which we would like to update its visit count
	 * @todo Do we really need the userId?
	 */
	function updateVisiteCount ($userId, $itemId)
	{
		parent::updateVisiteCount($userId, $itemId);
		$now = date ("Y-m-d H:i:s");
		//
		// now add last visit information
		//
		$query = sprintf (
			$this->queries['updateVisitedInformation'],
			$now, $itemId);
		$result = $this->db->Execute ($query) or die
			("UpdateVisiteCOunt Error: " .  $this->db->ErrorMsg () .
				" " . $query);
	}
}
?>