<?php

require_once ('framework/ItemController.php');
require_once ('framework/model/AdminServices.php');
require_once ('framework/util/StringUtils.php');

require_once ('plugins/bookmarks/util/BookmarkUtils.php');
require_once ('plugins/bookmarks/model/BookmarkPreferences.php');
require_once ('plugins/bookmarks/model/BookmarkServices.php');
require_once ('plugins/bookmarks/model/BookmarkFactory.php');

/**
 * The Bookmark Controller
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
class BookmarkController extends ItemController
{
	/**
	 * String utilities. Used to translate a GET['name'] (used in
	 * the quickmarks) into a non-escaped (based on gpc settings)
	 * itemname
	 */
	var $stringUtils;

	/**
	 * Constructor.
	 * Makes sure that the appropriate operations are instantiated.
	 */
	function BookmarkController ()
	{
		parent::ItemController ();
		$this->operations = new BookmarkServices ();
		$this->preferences = new BookmarkPreferences ();
		$this->itemFactory = new BookmarkFactory ();
		$this->pluginName = 'bookmarks';
		$this->title = 'Brim - Bookmarks';
		$this->itemName = 'Bookmark';
		$this->stringUtils = new StringUtils ();
		$this->expandName = 'bookmarkExpand';

		if (isset ($_SESSION['bookmarkTree'])
			&& $_SESSION['bookmarkTree']=='Javascript')
		{
			$_GET['expand']='*';
		}
	}

	/**
 	 * Returns the actions defined for this item only
 	 * Modified by Michael : added navigationMode and rightsmanagement
 	 *
 	 * @return array an array of item specific actions (like search,
	 * import etc.)
 	 */
	function getActions ()
	{
		//
	 	// Actions
		//
		$actions[0]['name'] = 'actions';
		$actions[0]['contents'][] = array(
			'href' => 'index.php?plugin=bookmarks&amp;action=add&amp;parentId='.$this->getParentId (),
			'name' => 'add'
			);
		$actions[0]['contents'][] = array(
			'href' => 'index.php?plugin=bookmarks&amp;action=multipleSelectPre&amp;parentId='.$this->getParentId(),
			'name' => 'multipleSelect'
			);
		$actions[0]['contents'][] = array(
			'href' => 'index.php?plugin=bookmarks&amp;action=import&amp;parentId='.$this->getParentId(),
			'name' => 'importTxt'
			);
		$actions[0]['contents'][] = array(
				'href' => 'index.php?plugin=bookmarks&amp;action=export&amp;parentId='.$this->getParentId(),
				'name' => 'exportTxt'
			);
		$actions[0]['contents'][] = array(
			'href' => 'index.php?plugin=bookmarks&amp;action=search',
			'name' => 'search'
			);
		$actions[0]['contents'][] = array(
			'href' => 'index.php?plugin=bookmarks&amp;action=findDoubles',
			'name' => 'findDoubles'
			);
		//
		// Views
		//
		$actions[1]['name'] = 'view';
		if (!isset ($_SESSION['brimEnableAjax']) || ($_SESSION['brimEnableAjax'] == 'false'))
		{
			$actions[1]['contents'][] = array(
				'href' => 'index.php?plugin=bookmarks&amp;expand=*',
				'name' => 'expand');
			$actions[1]['contents'][] = array(
				'href' => 'index.php?plugin=bookmarks&amp;expand=0',
				'name' => 'collapse');
			$actions[1]['contents'][] = array(
				'href' => 'index.php?plugin=bookmarks&amp;action=setYahooTree&amp;parentId='.$this->getParentId (),
				'name' => 'yahooTree');
			$actions[1]['contents'][] = array(
				'href' => 'index.php?plugin=bookmarks&amp;action=setExplorerTree&amp;parentId='.$this->getParentId (),
				'name' => 'explorerTree');
		}
		$actions[1]['contents'][] = array(
			'href' => 'index.php?plugin=bookmarks&amp;action=setModePublic&amp;parentId='.$this->getParentId (),
			'name' => 'setModePublic');
		$actions[1]['contents'][] = array(
			'href' => 'index.php?plugin=bookmarks&amp;action=setModePrivate&amp;parentId='.$this->getParentId (),
			'name' => 'setModePrivate');

		//
		// Sort
		//
		$actions[2]['name'] = 'sort';
		$actions[2]['contents'][] = array(
			'href'=> 'index.php?plugin=bookmarks&amp;action=sort&amp;order=DESC&amp;field=when_visited',
			'name' => 'last_visited');
		$actions[2]['contents'][] = array(
			'href'=> 'index.php?plugin=bookmarks&amp;action=sort&amp;order=DESC&amp;field=visit_count',
			'name' => 'most_visited');
		$actions[2]['contents'][] = array(
			'href'=> 'index.php?plugin=bookmarks&amp;action=sort&amp;order=DESC&amp;field=when_created',
			'name' => 'last_created');
		$actions[2]['contents'][] = array(
			'href'=> 'index.php?plugin=bookmarks&amp;action=sort&amp;order=DESC&amp;field=when_modified',
			'name' => 'last_modified');


		//
		// Preferences
		//
		$actions[3]['name'] = 'preferences';
		$actions[3]['contents'][] = array(
			'href'=> 'index.php?plugin=bookmarks&amp;action=modifyPreferencesPre',
			'name' => 'modify');
		$actions[3]['contents'][] = array (
			'href'=>'brim.php?username='.$this->getUserName ().'&amp;plugin=bookmarks',
			'name'=>'yourPublicBookmarks');
		$actions[3]['contents'][] = array (
			'href'=>'index.php?plugin=bookmarks&amp;action=sidebar',
			'name'=>'sidebar');
		$actions[3]['contents'][] = array (
			'href'=>'index.php?plugin=bookmarks&amp;action=favicon&amp;parentId='.$this->parentId,
			'name'=>'favicon');

		$actions[4]['name']='help';
		$actions[4]['contents'][]= array (
			'href'=>'index.php?plugin=bookmarks&amp;action=help',
			'name'=>'help');
		$actions[4]['contents'][] = array (
			'href'=>'index.php?plugin=bookmarks&amp;action=showQuickmarkOverview',
			'name'=>'quickmark');

		return $actions;
	}


	/**
	 * Activate. Basically this means that the appropriate actions are
	 * executed and an optional result is returned to be
	 * processed/displayed
	 */
	function activate ()
	{
		$dictionary = $this->getDictionary ();
		switch ($this->getAction ())
		{
			case "help":
				$this->helpAction ();
				break;
			//
			// Modified by Michael. I wonder if this "directAction"
			// I added is not nonsens...
			//
			case "add":
				$this->addAction ();
				break;
			//
			// this is to show an Item in a special showItem Page
			//
			// TODO BARRY FIXME. ShowItem is now placed just above
			// showBookmark
			//case "showItem":
				//$this->directAction ($this->getAction (), true);
				//break;
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
			case "addItemPost":
				$this->addItemAction ();
				break;
			case "addAndAddAnother":
				$this->addItemAction ();
				unset ($this->renderObjects);
				$this->addAction ();
				break;
			case "modify":
				$this->modifyAction ();
				$this->renderEngine->assign ('pageTitle',
					$dictionary['modify']);
				break;
			case "modifyItemPost":
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
			case "deleteAllFavicons":
				$this->deleteFavicons ($this->parentId);
				$this->getShowItemsParameters ();
				break;
			case "fetchAllFavicons":
				$this->fetchAllFavicons ($this->parentId);
				$this->getShowItemsParameters ();
				break;
			case "favicon":
				$this->renderer = $this->getAction ();
				$this->renderEngine->assign ('parentId', $this->parentId);
				break;
			case "deleteFavicon":
				$item = $this->operations->getItem ($this->getUsername(), $this->itemId);
				$item->favicon = null;
				$this->operations->modifyItem ($this->getUsername (), $item);
				$this->getShowItemsParameters ();
				break;
			case "getFavicon":
				$item = $this->operations->getItem ($this->getUsername(), $this->itemId);
				$bookmarkUtils = new BookmarkUtils ();
				$item->favicon = $bookmarkUtils->getFavicon ($item->locator);
				$this->operations->modifyItem ($this->getUsername (), $item);
				$this->getShowItemsParameters ();
				break;
			case "import":
			case "export":
			case "search":
				$this->renderer = $this->getAction ().'Bookmarks';
				break;
			case "searchBookmarks":
				$this->searchItemAction ();
				break;
			case "deleteItemPost":
				$this->deleteItemAction ();
				$this->getShowItemsParameters ();
				break;
			case "sidebar":
				require_once 'framework/model/AdminServices.php';
			    	$adminServices = new AdminServices ();
				$this->renderEngine->assign ('installationPath',
					$adminServices->getAdminConfig('installation_path'));
				$this->renderer = 'sidebar';
				break;
			case "showQuickmarkOverview":
				require_once 'framework/model/AdminServices.php';
			    	$adminServices = new AdminServices ();
				$this->renderEngine->assign ('installationPath',
					$adminServices->getAdminConfig('installation_path'));
				$this->renderer = 'quickmark';
				break;
			case "findDoubles":
				$all = $this->operations->getItems ($this->getUserName());
				usort ($all, array ('Bookmark', 'equals'));
				$this->renderObjects = $this->operations->findDoubles ($all);
				$this->renderer = 'showBookmarks';
				break;
			case "quickmark":
				$bookmark = new Bookmark (null, $this->getUserName (),
					$this->parentId, false,
					$this->stringUtils->gpcStripSlashes
						($this->stringUtils->utf8_to_unicode($_GET['name'])),
					null, 'private', null, null, null, null,null,
					$_GET['locator'], 0, null);
				$this->itemId = $this->operations->addItem
					($this->getUserName (), $bookmark);
				$this->modifyAction ();
				// hide the window automatically
				// echo '<html><head><title>Exit quickmark</title><script type="text/javascript">window.close();</script></head>Exit quickmark</html>';
				break;
			case "showItem":
				if (isset ($_GET['itemId']))
				{
					// TODO Duplicate of showBookmark.
					// But... what about difference between showBookmark
					// and showItem wrt folders?
					$this->operations->updateVisiteCount
						($this->getUserName (), $this->itemId);
					$bookmark = $this->operations->getItem
						($this->getUserName (), $this->itemId);
					header ("Location: " . $bookmark->locator);
					exit;
					break;
				}
				else
				{
					$this->getShowItemsParameters ();
					break;
				}
			case "showBookmark":
				$this->operations->updateVisiteCount
					($this->getUserName (), $this->itemId);
				$bookmark = $this->operations->getItem
					($this->getUserName (), $this->itemId);
				header ("Location: " . $bookmark->locator);
   				exit;
				break;
			case "importBookmarks":
				$bookmarkUtils = new BookmarkUtils ();
				//
				// first set the execution time to zero: unlimited.
				// Not that this has no effect if safe_mode is on
				//
				if (!ini_get ('safe_mode'))
				{	
					set_time_limit (0);
				}

	        	$importType=$_POST['importType'];
	        	$parentId = $_POST['parentId'];
	   			$importFile = $_FILES['importFile'];
				$visibility = $_POST['visibility'];
	   			if ($importType == 'Opera')
	        	{
	               	$bookmarkUtils->importOperaBookmarks
	               		($this->getUserName (),
							$importFile['tmp_name'], $this,
								$parentId, $visibility);
	        	}
				else if ($importType == 'Netscape')
				{
                	$bookmarkUtils->importNetscapeBookmarks
                		($this->getUserName (),
							$importFile['tmp_name'], $this,
								$parentId, $visibility);
				}
				else if ($importType == 'XBEL')
				{
	               	$bookmarkUtils->importXBEL
	               		($this->getUserName (),
							$importFile['tmp_name'], $this,
								$parentId, $visibility);
				}
	        	else
	        	{
	               	die ($importType . " is not yet supported");
	        	}
	        	$this->getShowItemsParameters ();
				//
	        	// We need to force a redirection to this page,
				// otherwise a reload means resending the POST
				// data and your items will be imported twice
				//
	        	header ("Location: index.php?plugin=bookmarks");
	        	exit ();
				break;
			case "exportBookmarks":
				$bookmarkUtils = new BookmarkUtils ();
			    	$exportType=$_POST['exportType'];
			    	$parentId = $_POST['parentId'];
			    	if ($exportType == 'Opera')
				{
					Header ("Content-type: text/plain");
					header('Content-Disposition: attachment; filename="brim-opera-bookmarks.adr"');

					echo ("Opera Hotlist version 2.0\n\n");
					$bookmarkUtils->exportOperaBookmarks
						($this->getUserName (), $parentId, $this);
					exit ();
				}
				else if ($exportType == 'XBEL')
				{
					Header ("Content-type: text/plain");
					header('Content-Disposition: attachment; filename="brim-xbel-bookmarks.xml"');
					$bookmarkUtils->exportXBEL
						($this->getUserName (), $parentId, $this);
					exit ();
				}
				else if ($exportType == 'Netscape')
				{
					Header ("Content-type: text/plain");
					header('Content-Disposition: attachment; filename="brim-mozilla-bookmarks.html"');
					$bookmarkUtils->exportNetscapeBookmarks
						($this->getUserName (), $parentId, $this);
					exit ();
				}
				else
				{
			    		die ($exportType . " is not yet supported");
			    	}
				break;
			case "setYahooTree":
				$_SESSION['bookmarkTree']='Yahoo';
				$this->getShowItemsParameters ();
				break;
			case "setExplorerTree":
				$_SESSION['bookmarkTree']='Explorer';
				//unset ($_GET['expand']);
				////unset ($_SESSION['bookmarkExpand']);
				$this->getShowItemsParameters ();
				break;
			case "sort":
				$field = $_GET['field'];
				$order = $_GET['order'];
				$this->sortAllAction ($field, $order);
				break;
			case "modifyPreferencesPre":
				$this->renderObjects =
					$this->preferences->getAllPreferences
						($this->getUserName ());
				$this->renderer = 'modifyPreferences';
				break;
			case "modifyPreferencesPost":
				$this->preferences->setPreference
					($this->getUserName (), $_POST['name'],
						$_POST['value']);
				$_SESSION['bookmarkTree']=
					$this->preferences->getPreferenceValue
						($this->getUserName (), 'bookmarkTree');
				$_SESSION['bookmarkOverlib']=
						$this->preferences->getPreferenceValue
							($this->getUserName (),
								'bookmarkOverlib');
				$_SESSION['bookmarkYahooTreeColumnCount']=
						$this->preferences->getPreferenceValue
							($this->getUserName (),
								'bookmarkYahooTreeColumnCount');
				$_SESSION['bookmarkNewWindowTarget']=
						$this->preferences->getPreferenceValue
							($this->getUserName (),
								'bookmarkNewWindowTarget');
				$_SESSION['bookmarkFavicon']=
						$this->preferences->getPreferenceValue
							($this->getUserName (),
								'bookmarkFavicon');
				$_SESSION['bookmarkAutoPrependProtocol']=
						$this->preferences->getPreferenceValue
							($this->getUserName (),
								'bookmarkAutoPrependProtocol');
				$this->renderer = 'modifyPreferences';
				$this->renderObjects = $_SESSION;
				break;
			case "multipleSelectPre":
				//
				// Hmmm... one downside: we will show the expanded
				// list afterwards...
				//
				$_SESSION['bookmarkExpand']= '*';
				$this->multipleSelectAction ();
				break;
			case "multipleSelectPost":
				$itemIds = array_keys ($_POST, "itemId");
				if (isset ($_POST['move']))
				{
					$this->moveMultipleAction ($itemIds);
				}
				else if (isset ($_POST['delete']))
				{
					foreach ($itemIds as $itemId)
					{
						$this->operations->deleteItem
							($this->getUserName (), $itemId);
					}
					$this->getShowItemsParameters ();
				}
				break;
			case "moveMultipleItemsPost":
				$itemIds = explode (",", $_GET['itemIds']);
				$parentId = $_GET['parentId'];
				foreach ($itemIds as $itemId)
				{
					$this->operations->moveItem
						($this->getUserName (), $itemId, $parentId);
				}
				$this->getShowItemsParameters ();
				break;
			default:
				if (!isset ($_SESSION['bookmarkTree']))
				{
					$_SESSION['bookmarkTree']=
						$this->preferences->getPreferenceValue
							($this->getUserName (),
								'bookmarkTree');
				}
				if (!isset ($_SESSION['bookmarkOverlib']))
				{
					$_SESSION['bookmarkOverlib']=
						$this->preferences->getPreferenceValue
							($this->getUserName (),
								'bookmarkOverlib');
				}
				if (!isset ($_SESSION['bookmarkYahooTreeColumnCount']))
				{
					$_SESSION['bookmarkYahooTreeColumnCount']=
						$this->preferences->getPreferenceValue
							($this->getUserName (),
								'bookmarkYahooTreeColumnCount');
				}
				if (!isset ($_SESSION['bookmarkNewWindowTarget']))
				{
					$_SESSION['bookmarkNewWindowTarget']=
						$this->preferences->getPreferenceValue
							($this->getUserName (),
								'bookmarkNewWindowTarget');
				}
				if (!isset ($_SESSION['bookmarkFavicon']))
				{
					$_SESSION['bookmarkFavicon']=
						$this->preferences->getPreferenceValue
							($this->getUserName (),
								'bookmarkFavicon');
				}
				$_SESSION['bookmarkAutoPrependProtocol']=
						$this->preferences->getPreferenceValue
							($this->getUserName (),
								'bookmarkAutoPrependProtocol');
				$this->getShowItemsParameters ();
				break;
		}
	}

	function deleteFavicons ($parentId)
	{
		$items = $this->operations->getChildren ($this->getUserName(), $parentId);
		foreach ($items as $item)
		{
			$item->favicon = null;
			$this->operations->modifyItem ($this->getUserName(), $item);
			if ($item->isParent())
			{
				$this->deleteFavicons ($item->itemId);
			}
		}
	}
	function fetchAllFavicons ($parentId)
	{
		$bookmarkUtils = new BookmarkUtils ();
		$items = $this->operations->getChildren ($this->getUserName(), $parentId);
		foreach ($items as $item)
		{
			//
			// Skip items that already have a favicon
			//
			if (!isset ($item->favicon) || ($item->favicon == ''))
			{
				$favicon = $bookmarkUtils->getFavicon ($item->locator);
				if (isset ($favicon) && $favicon != null)
				{
					$item->favicon = $favicon;
					$this->operations->modifyItem ($_SESSION['brimUsername'], $item);
				}
			}
			if ($item->isParent ())
			{
				$this->fetchAllFavicons ($item->itemId);
			}
		}
	}
}
?>
