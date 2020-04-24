<?php
@session_start ();

require_once ('framework/Controller.php');

/**
 * The ItemController (abstract base class), this class extends
 * the Controller by providing functions for items
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.framework
 *
 * @abstract
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class ItemController extends Controller
{
	/**
	 * The identifier of the current item
	 *
	 * @var integer itemId
	 */
	var $itemId;

	/**
	 * The identifier of the current item's parent
	 *
	 * @var integer parentId
	 */
	var $parentId;

	/**
	 * The objects that are the result of the action. The renderer
	 * typically renders these objects
	 *
	 * @var array renderObjects
	 */
	var $renderObjects;

	/**
	 * The factory used to construct dedicated items
	 *
	 * @var object itemFactory
	 */
	var $itemFactory;

	/**
	 * The navigation Mode : can be "private" (only items owned by the
	 * visitor are shown) or "public" (all public items are shown).
	 *
	 * @var string navigationMode
	 */
	var $navigationMode;

	/**
	 * The name used for this specific expansion parameters
	 *
	 * @var string expandName
	 */
	var $expandName;

	/**
	 * Constructor. Fetches the action, the itemId and the parentId from
	 * the request and assigns these parameters to the controller
	 */
	function ItemController ()
	{
		parent::Controller ();
		if (isset($_SESSION['navigationMode']))
		{
			$this->navigationMode = $_SESSION['navigationMode'];
		}
		else
		{
			//
			// default. Put in config file or db?
			//
			$this->navigationMode = 'private';
		}
		$this->itemId = 0;
		$this->parentId = 0;
		//
 		// Fetch the itemId parameter
		//
		if (isset ($_GET['itemId']))
		{
			$this->itemId = $_GET['itemId'];
		}
		else if (isset ($_POST['itemId']))
		{
			$this->itemId = $_POST['itemId'];
		}
		//
 		// Fetch the parentId parameter
		//
		if (isset ($_GET['parentId']))
		{
			$this->parentId = $_GET['parentId'];
		}
		else if (isset ($_POST['parentId']))
		{
			$this->parentId = $_POST['parentId'];
		}
	}

	/**
	 * Retrieves the parentId of the item that is currently in use
	 *
	 * @return integer the parentId that is currently in use
	 */
	function getParentId ()
	{
		return $this->parentId;
	}

	/**
	 * Retrieves the itemId that is currently in use
	 *
	 * @return integer the itemId that is currently in use
	 */
	function getItemId ()
	{
		return $this->itemId;
	}

	/**
	 * Display which basically means that the template will be invoked
	 */
	function display ()
	{
		$menuItems = array ();
		include('framework/configuration/menuItems.php');
		global $menu;

		$this->renderEngine->assign('title', $this->getTitle ());
		$this->renderEngine->assign('menuItems', $menuItems);
		$this->renderEngine->assign('menu', $menu);
		$this->renderEngine->assign('dictionary', $this->getDictionary ());
        $this->renderEngine->assign ('trashCount',
            $this->operations->getTrashCount ($this->getUserName ()));

		$this->renderEngine->assign('itemId', $this->getItemId ());
		$this->renderEngine->assign('parentId', $this->getParentId ());
		$this->renderEngine->assign('item',
			$this->getItem ($this->getUserName(),
				$this->getItemId ()));
		$this->renderEngine->assign('parent',
			$this->getItem ($this->getUserName(),
			$this->getParentId ()));
		$this->renderEngine->assign('parameters', $this->getParameters ());
		$this->renderEngine->assign('action', $this->getAction ());
		$this->renderEngine->assign('renderObjects', $this->getRenderObjects ());
		$this->renderEngine->assign('renderActions', $this->getActions ());

		$this->renderEngine->assign ('pluginName', $this->pluginName);
		$this->renderEngine->assign ('renderer', $this->getTemplateFile ());
		if (isset ($_GET['debug'])
			|| (isset ($_SESSION['debug']) && $_SESSION['debug']==true)
		)
		{
			error_reporting(E_ALL);
			$this->renderEngine->display('templates/'.
				$this->getTemplate().'/template.tpl.php');
		}
		else
		{
			error_reporting(E_ERROR);
			@$this->renderEngine->display('templates/'.
				$this->getTemplate().'/template.tpl.php');
		}
	}

	/**
	 * Returns the factory that knows how to create dedicated items
	 *
	 * @return object the item factory
	 */
	function getItemFactory ()
	{
		return $this->itemFactory;
	}

	/**
	 * Gets a specific item for a user (request is forwarded to the
	 * operations class)
	 *
	 * @param string userId the identifier for the user
	 * @param integer itemId the identifier for the item
	 * @return object the item for specified user with specified itemId
	 */
	function getItem ($userId, $itemId)
	{
		return $this->operations->getItem ($userId, $itemId);
	}

	/**
	 * Adds an item for a user (request is forwarded to the operations
	 * class)
	 *
	 * @param integer (string?) userId the identifier for the user
	 * @param object item the item to be added
	 */
	function addItem ($userId, $item)
	{
		return $this->operations->addItem ($userId, $item);
	}

	/**
	 * Retrieves the children of a specified item (request is forwarded
	 * to the operations class)
	 *
	 * @param string userId the identifier of the user that issues the request
	 * @param integer itemId the identifier of the item for which we
	 * would like to have its children
	 * @return array all children for specified user and itemId
	 */
	function getChildren ($userId, $itemId)
	{
		return $this->operations->getChildren ($userId, $itemId);
	}

	/**
	 * Default action to add an item
	 */
	function addAction ()
	{
		$dictionary = $this->getDictionary ();
		$this->renderer = strtolower ($this->getItemName());
		$this->renderEngine->assign ('viewAction', 'add');
		$this->renderEngine->assign ('pageTitle', $dictionary['add']);
		$this->renderEngine->assign ('parentId', $this->getParentId ());
	}

	/**
	 * Items parameters has been provided, now really add it
	 *
	 * @return integer the item id of the newly added item, or '-1'
	 * of the item was not added (due to an error)
	 * @protected
	 * @modified Michael Haussmann
	 */
	function addItemAction ()
	{
		$errors = array ();
		$item = $this->itemFactory->requestToItem ();
		$errors = $this->itemFactory->requestToItemErrors ();
		$ownerOfParent = $this->operations->getItemOwner ($item->parentId);
		if (($item->owner != $ownerOfParent) && $item->parentId != 0)
		{
			$errors [] = 'addToFolderNotOwned';
		}
		if (empty($errors))
		{
			$newId = $this->operations->addItem ($this->getUserName (),
				$item);
			$this->getShowItemsParameters ();
			return $newId;
		}
		else
		{
			$this->addParameter("errors", $errors);
			$this->addAction ();
			$this->renderObjects = $item;
			return -1;
		}
	}

	/**
	 * Default modify Action
	 */
	function modifyAction ()
	{
		$dictionary = $this->getDictionary ();
		$this->renderer = strtolower ($this->getItemName ());
		$this->renderEngine->assign ('viewAction', 'modify');
		$this->renderEngine->assign ('pageTitle', $dictionary['modify']);
		$this->renderObjects =	$this->operations->
			getItem ($this->getUserName (),	$this->itemId);
		$this->getCurrentAncestors ();
	}

	/**
	 * Default show Action
	 */
	function showAction ()
	{
		$dictionary = $this->getDictionary ();
		$this->renderer = strtolower ($this->getItemName ());
		$this->renderEngine->assign ('viewAction', 'show');
		$this->renderEngine->assign ('pageTitle', $this->getItemName());
		$this->renderObjects =	$this->operations->
			getItem ($this->getUserName (),	$this->itemId);
		$this->getCurrentAncestors ();
	}

	/**
	 * Items parameters has been provided, now really modify it
	 *
	 * @return boolean <code>true</code> if the item was succesfully
	 * added, <code>false</code> in case of failure
	 * @protected
	 * @modified Michael Haussmann
	 */
	function modifyItemAction ()
	{
		$dictionary = $this->getDictionary ();
		$baseItem = $this->operations->getItem ($this->getUserName (),
				$this->itemId);
		$item = $this->itemFactory->requestToItem($baseItem);
		$itemOwner = $this->operations->getItemOwner ($item->itemId);
		//
		// We are only able to modify an item which we own...
		//
		if ($itemOwner != $this->getUserName ())
		{
			$this->renderEngine->assign ("message",
				'modify_not_owner');
		}
		else
		{
			//
			// Check for errors
			//
			$errors = $this->itemFactory->requestToItemErrors ();
			if ($errors == null)
			{
				$this->operations->modifyItem($this->getUserName (),
					$item);
				return true;
			}
			else
			{
				$this->addParameter("errors", $errors);
				$this->renderObjects = $item;
				$this->modifyAction ();
			}
		}
	}

	/**
	 * Search for items
	 *
	 * @protected
	 * @modifed Michael Haussmann
	 */
	function searchItemAction ()
	{
	   if($this->navigationMode == 'public')
	   {
	    	$this->renderObjects = $this->operations->searchPublicItems
   				($this->getUserName (), $_POST['field'], $_POST['value']);
	   }
	   else
	   {
			$this->renderObjects = $this->operations->searchItems
   				($this->getUserName (), $_POST['field'], $_POST['value']);
	   }
	   $this->renderer = 'show'.$this->getItemName ().'s';
	}

	/**
	 * Prepare to move (an) item(s). This function does common things
	 * used by both the moveAction and moveMultipleAction
	 *
	 * @private
	 */
	function prepareMoveAction ()
	{
		//
		// this is a shortcut. Give the first descendants of the root
		// as renderObjects. These will be evaluated afterwards...
		// Of course, the tree needs to be totally expanded
		//
		$root = $this->getItem ($this->getUserName(), 0);
		$rootItems = $this->operations->getChildrenThatAreParent
			($this->getUserName(), $root->itemId);

		for ($i=0; $i<count($rootItems); $i++)
		{
			$this->addChildrenThatAreParent ($rootItems[$i]);
		}
		$this->renderObjects = $rootItems;
	}

	/**
	 * Default move action
	 *
	 * @protected
	 */
	function moveAction ()
	{
		$this->prepareMoveAction ();
		$this->item = $this->operations->getItem ($this->getUserName (),
				$this->itemId);
		$this->renderer = 'move'.$this->getItemName ();
	}

	/**
	 * Default move action
	 *
	 * @param array multipleItemIds (i.e. 1, 5, 7)
	 * @protected
	 */
	function moveMultipleAction ($multipleItemIds)
	{
		$this->prepareMoveAction ();
		$this->renderer = 'moveMultiple'.$this->getItemName ();
		$this->renderEngine->assign ('itemIds', $multipleItemIds);
	}

	/**
	 * Allows the user to select multiple items by presenting a tree
	 * with checkboxes in front of each item (folders excluded). The
	 * user can use these to delete/move multiple at the same time
	 * as example
	 *
	 * @protected
	 */
	function multipleSelectAction ()
	{
		$this->getShowItemsParameters ();
		$this->renderer = 'multipleSelect'.$this->getItemName ();
	}

	/**
	 * Items parameters have been provided, now really move it
	 *
	 * @protected
	 */
	function moveItemAction ()
	{
		$itemOwner = $this->operations->getItemOwner
			($this->getItemId());
		if ($itemOwner != $this->getUserName ())
		{
			//
			// You can only move items for which you are owner
			//
			$dictionary = $this->getDictionary ();
			$this->renderEngine->assign ("message",
				'modify_not_owner');
		}
		else
		{
			$this->operations->moveItem ($this->getUserName (),
				$this->itemId,	$this->parentId);
		}
	}

	/**
	 * Items parameters has been provided, no really delete
	 *
	 * @protected
	 */
	function deleteItemAction ()
	{
		$dictionary = $this->getDictionary ();
		$itemOwner = $this->operations->getItemOwner ($this->itemId);
		if ($itemOwner != $this->getUserName ())
		{
			//
			// You can only delete items for which you are owner
			//
			$this->renderEngine->assign
				("message", 'delete_not_owner');
		}
		else
		{
			$this->operations->deleteItem ($this->getUserName (),
				$this->itemId);
		}
	}

	/**
	 * Retrieves the parameters for a normal 'show' action which is
	 * often requested after another action (like delete/modify)
	 *
	 * @modified Michael Haussmann
	 */
	function getShowItemsParameters ()
	{
		//
		// These 3 lines are used to provide the complete path of
		// items, from current up to root
		//
		$this->getCurrentAncestors ();
		if($this->navigationMode == 'public')
		{
			$result = $this->getShowPublicItemsParameters();
		}
		else
		{
			$result = $this->getShowPrivateItemsParameters();
		}
		return $result;
	}

	/**
	 * Gets the ancestors for a specific item
	 * and adds them to the controller as parameter
	 *
	 * @todo perhaps rename this function, since a getXXX function should
	 * normally return something...
	 */
	function getCurrentAncestors ()
	{
		$currentRoot = $this->operations->getItem
			($this->getUserName (),$this->parentId);
		if (isset ($currentRoot))
		{
			$ancestors = $this->operations->getAncestors ($currentRoot);
			$this->addParameter ("ancestors", array_reverse($ancestors));
		}
		else if ($this->parentId == 0)
		{
			$this->addParameter ('ancestors', 'root');
		}
	}

	/**
	 * Retrieves the parameters for a normal 'show' action,
	 * for the public navigationMode.
	 *
	 * @todo perhaps rename this function, since a getXXX function should
	 * normally return something...
	 * @author Michael Haussmann
	 */
	function getShowPublicItemsParameters ()
	{
		$this->renderer = 'show'.$this->getItemName().'s';
		$rootItems = $this->operations->getPublicChildren
			($this->getUserName (), $this->getParentId ());
		$expand = $this->getExpanded ();
		if (isset ($expand))
		{
			$this->addParameter ("expand", $expand);
		}

		for ($i=0; $i<count($rootItems); $i++)
		{
			$this->addExpandedPublicChildren ($rootItems[$i]);
		}
		$this->renderObjects = $rootItems;
	}

	/**
	 * Retrieves the parameters for a normal 'show' action,
	 * for the private navigationMode.
	 *
	 * @todo perhaps rename this function, since a getXXX function should
	 * normally return something...
	 * @modified by MICHAEL
	 */
	function getShowPrivateItemsParameters ()
	{
		$this->renderer = 'show'.$this->getItemName().'s';
		$rootItems = $this->operations->getChildren
			($this->getUserName (), $this->getParentId ());
		$expand = $this->getExpanded ();
		if (isset ($expand))
		{
			$this->addParameter ("expand", $expand);
		}
		for ($i=0; $i<count($rootItems); $i++)
		{
			$this->addExpandedChildren ($rootItems[$i]);
		}
		$this->renderObjects = $rootItems;
	}


	/**
	 * Sorts the items based on specified criteria
	 *
	 * @param integer parentId the id of the parent of which we
	 * 		would like to sort items
	 * @param string field the field on which we would like to sort
	 * @param string sortOrder either <code>ASC</code> or
	 * 		<code>DESC</code>
	 */
	function sortAction ($parentId, $field, $sortOrder)
	{
		$this->renderer = 'show'.$this->getItemName().'s';

		if($this->navigationMode == 'public')
		{
			$this->renderObjects =
				$this->operations->getPublicSortedItems (
					$this->getUserName(), $parentId,$field, $sortOrder);
		}
		else
		{
			$this->renderObjects = $this->operations->getSortedItems (
				$this->getUserName(), $parentId, $field, $sortOrder);
		}
		$this->getCurrentAncestors ();
	}


	/**
	 * Sorts the items based on specified criteria
	 *
	 * @param integer parentId the itemId of the folder in which we sort
	 * @param string field the field on which we would like to sort
	 * @param string sortOrder either <code>ASC</code> or
	 * <code>DESC</code>
	 */
	function sortAllAction ($field, $sortOrder)
	{
		$this->renderer = 'show'.$this->getItemName().'s';
		if($this->navigationMode == 'public')
		{
			$this->renderObjects =
				$this->operations->getAllPublicSortedItems (
					$this->getUserName(), $field, $sortOrder);
		}
		else
		{
			$this->renderObjects = $this->operations->getAllSortedItems (
				$this->getUserName(), $field, $sortOrder);
		}
		$this->getCurrentAncestors ();
	}


	/**
	 * Adds the children of this item in the array, but only if this
	 * item is expanded itself
	 *
	 * @param object item the item
	 * @private
	 */
	function addExpandedChildren (&$item)
	{
		if ($item->isParent () && $this->isExpanded ($item->itemId))
		{
			$items = $this->operations->getChildren
				($this->getUserName (),$item->itemId);
			for ($i=0; $i<count($items); $i++)
			{
				$this->addExpandedChildren ($items[$i]);
				$item->addChild ($items[$i]);
			}
		}
	}

	/**
	 * Adds the public children of this item in the array, but only
	 * if this item is expanded itself
	 *
	 * @private
	 * @param object item the item
	 * @author Michael Haussmann
	 */
	function addExpandedPublicChildren (&$item)
	{
		if ($item->isParent () && $this->isExpanded ($item->itemId))
		{
			$items = $this->operations->getPublicChildren
				($this->getUserName (),$item->itemId);
			for ($i=0; $i<count($items); $i++)
			{
				$this->addExpandedPublicChildren ($items[$i]);
				$item->addChild ($items[$i]);
			}
		}
	}

	/**
	 * Adds (recursively) the children of this item in the array
	 *
	 * @private
	 * @param object item the item
	 */
	function addChildrenThatAreParent (&$item)
	{
		if ($item->isParent ())
		{
			$items = $this->operations->getChildrenThatAreParent
				($this->getUserName (),$item->itemId);
			for ($i=0; $i<count($items); $i++)
			{
				$this->addChildrenThatAreParent ($items[$i]);
				$item->addChild ($items[$i]);
			}
		}
	}

	/**
	 * Adds (recursively) the public children of this item in the array
	 *
	 * @private
	 * @param object item the item
	 * @author Michael Haussmann
	 */
	function addPublicChildrenThatAreParent (&$item)
	{
		if ($item->isParent ())
		{
			$items = $this->operations->getPublicChildrenThatAreParent
				($this->getUserName (),$item->itemId);
			for ($i=0; $i<count($items); $i++)
			{
				$this->addPublicChildrenThatAreParent ($items[$i]);
				$item->addChild ($items[$i]);
			}
		}
	}

	/**
	 * returns whether the specified itemId is expanded
	 *
	 * @private
	 * @param integer itemId the identifier which needs to be checked
	 * @return boolean <code>true</code> if this id is in the expanded
	 * list, <code>false</code> otherwise
	 */
	function isExpanded ($itemId)
	{
		$expanded = explode (",", $this->getExpanded ());
		if ($this->getExpanded () == null)
		{
			return false;
		}
		//
		// wildcard implementation
		//
		if (count ($expanded == 1) && $expanded[0] == "*")
		{
			$this->addExpanded ($itemId);
			return true;
		}
		while (list ($key, $val) = each($expanded))
		{
			//
			// yep, in the expanded list
			//
			if ($val == $itemId)
			{
				return true;
			}
		}
       	return false;
	}

	/**
	 * Returns the name of the item that is controller by this
	 * controller
	 *
	 * @return string the name of the item that is controlled by this
	 * controller
	 */
	function getItemName ()
	{
		return $this->itemName;
	}


	/**
	 * Direct action,  useful to simply implement simple but different
	 * actions.
	 *
	 * Simply calls a renderer named by the first parameter.
	 * Optionnaly instanciates the current Item, as renderObjects
	 * attribut. Default is false.
	 *
	 * @param $actionName string the name of the renderer to be called
	 * @param $doRenderItem boolean if the current Item has to be
	 * instanciated. Optional. Default is false.
	 * @author Michael Haussmann
	 */
	function directAction ($actionName, $doRenderItem=false)
	{
		$this->renderer = $actionName.$this->getItemName();
		if ($doRenderItem)
		{
			$this->renderObjects = $this->operations->
				getItem ($this->getUserName (),	$this->itemId);
		}
	}

	/**
	 * Returns the list of current expanded items for the controller
	 *
	 * @return string list of commanseperated item numbers
	 */
	function getExpanded ()
	{
		if (isset ($_GET['expand']))
		{
			$expand = $_GET['expand'];
			$_SESSION[$this->expandName]=$expand;
		}
		else if (isset ($_SESSION[$this->expandName]))
		{
			$expand = $_SESSION[$this->expandName];
		}
		else
		{
			$expand = 0;
		}
		return $expand;
	}

	/**
	 * Add a number to the list of expanded items
	 *
	 * @param integer number the number (itemId) to add
	 * to the list of expanded items
	 */
	function addExpanded ($number)
	{
		//
		// If we already have a list of expanded items, we need to
		// add it, but also make sure that it is not yet in the list
		//
		if (isset ($_SESSION[$this->expandName]))
		{
			//
			// Wildcard implementation
			//
			if ($_SESSION[$this->expandName] != '*')
			{
				$items = explode (',', $_SESSION[$this->expandName]);
				$items [] = $number;
				$_SESSION[$this->expandName]=implode (',',array_unique($items));
			}
			else
			{
				$_SESSION[$this->expandName]=$_SESSION[$this->expandName].','.$number;
			}
		}
		//
		// Otherwise we can just use this number as expanded list
		//
		else
		{
			$_SESSION[$this->expandName]=$number;
		}
		$_GET['expand'] = $_SESSION[$this->expandName];
	}


	/**
	 * Gets the dashboard information for this item. This basically
	 * means retrieving a limited number (<code>$number</code>) of items,
	 * sorted on the specified field (<code>$field</code> using the
	 * specified sortorder (<code>$sort</code>).
	 *
	 * @param string field the name of the field on which we would like
	 * 		to sort
	 * @param integer number the number of items we would like to have
	 * 		returned
	 * @param string sort the sortorder (either <code>ASC</code> or
	 *		<code>DESC</code>
	 */
	function getDashboard ($field, $number, $sort)
	{
		$result=$this->operations->getLimitedSortedItems (
			$this->getUserName(), $field, $sort, $number);
		return $result;
	}
}
?>
