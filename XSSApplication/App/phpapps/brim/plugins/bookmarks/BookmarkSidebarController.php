<?php

require_once ('framework/ItemController.php');
require_once ('plugins/bookmarks/util/BookmarkUtils.php');
require_once ('plugins/bookmarks/model/BookmarkServices.php');
require_once ('plugins/bookmarks/model/BookmarkFactory.php');

/**
 * The BookmarkSideBar Controller
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.plugins.bookmarks
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class BookmarkSidebarController extends ItemController
{
	/**
	 * Constructor. Makes sure that the appropriate operations are
	 * instantiated.
	 */
	function BookmarkSidebarController ()
	{
		parent::ItemController ();
		$this->operations = new BookmarkServices ();
		$this->itemFactory = new BookmarkFactory ();

		$this->pluginName = 'bookmarks';
		$this->title = 'Brim - Bookmarks';
		$this->itemName = 'Bookmark';
		$this->expandName = 'bookmarkExpand';
	}

	/**
 	 * Returns the actions defined for this item only
 	 *
 	 * @return array an array of item specific actions (like search,
	 * import etc.)
 	 */
	function getActions ()
	{
		$dictionary = $this->getDictionary ();
		$actions=array(
			array (
				'name'=> $dictionary['actions'],
				'contents'=>
				array (
					array('href' =>
						'BookmarkController.php',
						'img' =>
						'<img
						src="framework/view/pics/tree/everaldo3_home.png"
						border="0">',
						'name' =>
						$dictionary['home']),
					array('href' => 'BookmarkSidebarController.php?action=add&amp;parentId='.$this->getParentId (),
						'img' =>
						'<img
						src="framework/view/pics/add.gif"
						border="0">',
						'name' =>
						$dictionary['add']),
					array('href' =>
					'BookmarkSidebarController.php?parentId='.$this->getParentId (),
						'img' =>
						'<img
						src="framework/view/pics/everaldo_refresh.png"
						border="0">',
						'name' =>
						$dictionary['refresh'])
					)
				),
			array (
				'name'=> $dictionary['view'],
				'contents' =>
				array (
					array('href' => 'BookmarkSidebarController.php?expand=*',
						'img' =>
						'<img
						src="framework/view/pics/expand.gif"
						border="0">',
						'name' => $dictionary['expand']),
					array('href' => 'BookmarkSidebarController.php?expand=0',
						'img' =>
						'<img
						src="framework/view/pics/collapse.gif"
						border="0">',
						'name' => $dictionary['collapse'])
				)
			)
		);
		return $actions;
	}

	/**
	 * Activate. Basically this means that the appropriate actions are
	 * executed and an optional result is returned
	 * to be processed/displayed
	 */
	function activate ()
	{
        	switch ($this->getAction ())
		{
			case "add":
				$this->addAction ();
				break;
			case "addBookmark":
				$this->addItemAction ();
				break;
			case "modify":
				$this->modifyAction ();
				break;
			case "modifyBookmark":
				$this->modifyItemAction ();
				$this->getShowItemsParameters ();
				break;
			case "move":
				$this->moveAction ();
				break;
			case "moveItem":
				$this->moveItemAction ();
				$this->getShowItemsParameters ();
				break;
			//
			// Added by Michael. Default navigation mode. If used,
			// change in other plugins.
			//
			case "setModePublic":
				$_SESSION['navigationMode']='public';
				$this->navigationMode ='public';
				$this->getShowItemsParameters ();
				break;
			case "setModePrivate":
				$_SESSION['navigationMode']='private';
				$this->navigationMode ='private';
				$this->getShowItemsParameters ();
				break;
			case "searchBookmarks":
				$this->searchItemAction ();
				break;
			case "deleteBookmark":
				$this->deleteItemAction ();
				$this->getShowItemsParameters ();
				break;
			case "showBookmark":
				$this->operations->updateVisiteCount
					($this->getUserName (), $this->itemId);
				$bookmark = $this->operations->getItem
					($this->getUserName (), $this->itemId);
				header ("Location: " . $bookmark->locator);
   				exit;
				break;
			case "sort":
				$field = $_GET['field'];
				$this->sortAction ($field);
				$this->getShowItemsParameters ();
				break;
			default:
				$this->getShowItemsParameters ();
				break;
		}
	}

	function getTemplate ()
	{
			return "sidebar";
	}
}
?>