<?php

require_once ('framework/model/Item.php');

/**
 * The Bookmark item.
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
class Bookmark extends Item
{
	/**
	 * When this bookmark was last visited
	 *
	 * @access private
	 * @var string
	 */
	var $when_visited;

	/**
	 * The locator (URI) of this bookmark
	 *
	 * @access private
	 * @var string
	 */
	var $locator;

	/**
	 * The number of times this bookmark has been visited
	 *
	 * @access private
	 * @var int
	 */
	var $visitCount;

	/**
	 * The favicon
	 *
	 * @access private
	 * @var string
	 */
	var $favicon;

	/**
	 * Full-blown Constructor.
	 *
	 * @param integer theItemId the id of the item
	 * @param string theOwner who owns this item?
	 * @param integer theParentId the id of the parent of this item
	 * @param boolean parent is this a parent (true) or child (false)
	 * @param string theName the name of this item
	 * @param string theDescription the description of this item
	 * @param string theVisibility the visibility (private or public)
	 * @param string theCategory what is the category of this item?
	 * @param string created When was this item created?
	 * @param string modified When was this item modified?
	 * @param string visited When was this item last visited?
  	 * @param string theLocator what is the locator (URL)?
	 * @param integer theVisitCount how many times has this item been
	 * 		visisted?
	 */
	function Bookmark (
		$theItemId, $theOwner, $theParentId, $parent, $theName,
		$theDescription, $theVisibility, $theCategory, $deleted,
		$created, $modified, $visited,
		$theLocator, $theVisitCount, $theFavicon)
	{
		parent :: Item (
			$theItemId,
			$theOwner,
			$theParentId,
			$parent,
			$theName,
			$theDescription,
			$theVisibility,
			$theCategory,
			$deleted,
			$created,
			$modified);
		$this->type = "Bookmark";
		$this->when_visited = $visited;
		$this->locator = $theLocator;
		$this->visitCount = $theVisitCount;
		$this->favicon = $theFavicon;
	}

	/**
	 * Checks whether the constructed item is a valid item (has all the
	 * required fields)
	 *
	 * @return boolean <code>true</code> if the item is valid,
	 * <code>false</code> otherwise
	 */
	function isValid ()
	{
		if (!$this->isParent ())
		{
			return parent::isValid () && isset ($this->locator);
		}
		return parent::isValid ();
	}

	function equals ($thisOne, $other)
	{
		if ($thisOne->name == $other->name && $thisOne->locator == $other->locator)
		{
			return 0;
		}
		return($thisOne->name < $other->name) ? -1 : 1;
	}
}
?>