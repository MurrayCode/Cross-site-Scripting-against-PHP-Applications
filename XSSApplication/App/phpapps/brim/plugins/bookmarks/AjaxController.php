<?php

require_once ('plugins/bookmarks/model/BookmarkServices.php');
require_once ('plugins/bookmarks/util/BookmarkUtils.php');
require_once ('ext/JSON.php');

/**
 * The Bookmark Ajax Controller. This class is some sort of guardian, since
 * only the functions in this class can be called...
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - December 2006
 * @package org.brim-project.plugins.bookmarks
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class AjaxController
{
	/**
	 * The bookmark services/business logic
	 *
	 * @object
	 */
	var $services;
	/**
 	 * The JSON encoding library
	 * 
	 * @object
	 */
	var $json;

	/**
 	 * Default constructor. Instantiates the services and JSON lib
	 */
	function AjaxController ()
	{
		$this->services = new BookmarkServices ();
		$this->json = new Services_JSON();
	}

	/**
	 * Returns the favicon based on an ip-address
	 * @param array args, arguments need to contain a key with the name 'ip' 
	 * 	and a valid ip-address as value
	 * @return string uuencoded favicon
	 * @see plugins/bookmarks/util/BookmarkUtils#getFavicon
	 */
	function getFavicon ($args)
	{
		$bookmarkUtils = new BookmarkUtils ();
		return $bookmarkUtils->getFavicon ($args['ip']);
	}

	/**
 	 * Retrieves the children of the current item, but only one 
	 * level deep (no recursion).
	 * @param array args, arguments. The following information
	 * 	must be present: itemId 
	 * @return string a JSON encoded array of items that are the currents item
	 * its children
	 */
	function getFlatChildrenStructure ($args)
	{
		$status = array ();
		$itemId = $args ['itemId'];
		$item = $this->services->getItem ($_SESSION['brimUsername'], $itemId);
		//
		// First check: if this item has private visibility, we need to be the owner
		//
		if ($item->visibility == 'private' && $item->owner != $_SESSION['brimUsername'])
		{
			$status ['error']='Invalid access';
		}
		else
		{
			// 
			// Ok, not private or we are the owner. Check for public, not owned
			//
			if ($_SESSION['navigationMode'] == 'public')
			{
				$status ['result'] = $this->services->getPublicChildren 
					($_SESSION['brimUsername'], $itemId);
			}
			//
			// Ok, we own this item
			//
			else 
			{
				$status ['result'] = $this->services->getChildren 
					($_SESSION['brimUsername'], $itemId);
			}
		}
		return ($this->json->encode ($status));
	}
}
?>
