<?php

require_once ('framework/model/ItemFactory.php');
require_once ('plugins/bookmarks/model/Bookmark.php');
require_class('RequestCast',
	'framework/util/request/RequestCast.class.php'); // Michael
require_class('ClassHelper',
	'framework/util/ClassHelper.class.php'); // Michael

/**
 * BookmarkFactory
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.plugins.bookmarks
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class BookmarkFactory extends ItemFactory
{
	/**
	 * Default constructor
	 */
	function BookmarkFactory ()
	{
		parent::ItemFactory ();
	}

	/**
	 * Returns the type of this specific item
	 *
	 * @return string the type of this specific item:
	 * <code>Bookmark</code>
	 */
	function getType ()
	{
		return "Bookmark";
	}


	function requestToItem ()
	{
		$itemId = $this->getFromPost ('itemId', 0);
		$owner = $_SESSION['brimUsername'];
		$parentId = $this->getFromPost ('parentId', 0);
		$isParent = $this->getFromPost ('isParent', 0);
		$name =
			$this->stringUtils->gpcStripSlashes ($_POST['name']);
		$description = $this->getFromPost ('description', null);
		$visibility = $this->getFromPost ('visibility', 'private');
		$category = $this->getFromPost ('category', null);
		$isDeleted = $this->getFromPost ('isDeleted', 0);
		$when_created = $this->getFromPost ('when_created', null);
		$when_modified = $this->getFromPost ('when_modified', null);
		$when_visited = $this->getFromPost ('when_visited', null);
		$locator = $this->getFromPost ('locator', null);
		$visitCount = $this->getFromPost ('visitCount', 0);
		$favicon = $this->getFromPost ('favicon', null);
		if (isset ($_SESSION['bookmarkAutoPrependProtocol']) && 
				$_SESSION['bookmarkAutoPrependProtocol'] == 1 &&
				!strstr ($locator, '://'))
		{
			$locator = 'http://'.$locator;
		}
		$item = new Bookmark
			(
				$itemId,
				$owner,
				$parentId,
				$isParent,
				$name,
				$description,
				$visibility,
				$category,
				$isDeleted,
				$when_created,
				$when_modified,
				$when_visited,
				$locator,
				$visitCount,
				$favicon
			);
		return $item;
	}

	/**
	 * Factory method: Returns a database result into an item
	 *
	 * @param object result the result retrieved from the database
	 * @return array the items constructed from the database resultset
	 */
	function resultsetToItems ($result)
	{
		$items = array ();
		while (!$result->EOF)
		{
			$item = new Bookmark (
				$result->fields['item_id'],
				trim ($result->fields['owner']),
				$result->fields['parent_id'],
				$result->fields['is_parent'],
				trim ($this->stringUtils->gpcStripSlashes
					($result->fields['name'])),
				trim ($this->stringUtils->gpcStripSlashes
					($result->fields['description'])),
				trim ($result->fields['visibility']),
				trim ($result->fields['category']),
				$result->fields['is_deleted'],
				$result->fields['when_created'],
				$result->fields['when_modified'],
				$result->fields['when_visited'],
				trim ($this->stringUtils->gpcStripSlashes
					($result->fields['locator'])),
				$result->fields['visit_count'],
				trim ($this->stringUtils->gpcStripSlashes
					($result->fields['favicon'])));
			$items [] = $item;
			$result->MoveNext();
		}
		return $items;
	}

	/**
	 * Returns an empty item
	 * @return object an empty item, all values
	 * set to -null-
	 */
	function getEmptyItem ()
	{
		$item = new Bookmark
			(null, null, null, null,
			 null, null, null, null,
			 null, null, null, null,
			 null, null, null
		);
		return $item;
	}

}
?>