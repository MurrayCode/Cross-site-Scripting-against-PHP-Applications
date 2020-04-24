<?php

require_once ('framework/ItemController.php');
require_once ('plugins/passwords/model/PasswordServices.php');
require_once ('plugins/passwords/model/PasswordPreferences.php');

/**
 * The Password Controller
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2004
 * @package org.brim-project.plugins.passwords
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class PasswordController extends ItemController
{
	/**
	 * Constructor.
	 * Makes sure that the appropriate operations are instantiated.
	 */
	function PasswordController ()
	{
		parent::ItemController ();
		$this->operations = new PasswordServices ();
		$this->preferences = new PasswordPreferences ();
		$this->itemFactory = new PasswordFactory ();

		$this->pluginName = 'passwords';
		$this->title = 'Brim - Passwords';
		$this->itemName = 'Password';

		$this->expandName = 'passwordExpand';
	}

	/**
 	 * Returns the actions defined for this item only
 	 *
 	 * @return array an array of item specific actions (like search,
	 * import etc.)
 	 */
	function getActions ()
	{
		$actions=array();

		//
		// Actions
		//
		$actions[0]['name']= 'actions';
		$actions[0]['contents'][]=
			array('href' => 'index.php?plugin=passwords&amp;action=add&amp;parentId='.$this->getParentId (),
			'name' => 'add');
		$actions[0]['contents'][]=
			array ('href'=>'index.php?plugin=passwords&amp;action=multipleSelectPre&amp;parentId='.$this->getParentId(),
			'name'=>'multipleSelect');
		$actions[0]['contents'][]=
			array('href' => 'index.php?plugin=passwords&amp;action=search',
			'name' => 'search');
		$actions[0]['contents'][]=
			array('href' => 'index.php?plugin=passwords&amp;action=generate',
			'name' => 'generate');

		//
		// View
		//
		$actions[1]['name']= 'view';
		$actions[1]['contents'][]=
			array('href' => 'index.php?plugin=passwords&amp;expand=*',
			'name' => 'expand');
		$actions[1]['contents'][]=
			array('href' => 'index.php?plugin=passwords&amp;expand=0',
			'name' => 'collapse');
		$actions[1]['contents'][]=
			array('href' => 'index.php?plugin=passwords&amp;action=setYahooTree&amp;parentId='.$this->getParentId (),
			'name' => 'yahooTree');
		$actions[1]['contents'][]=
			array('href' => 'index.php?plugin=passwords&amp;action=setExplorerTree&amp;parentId='.$this->getParentId (),
			'name' => 'explorerTree');

		//
		// Preferences
		//
		$actions[2]['name']= 'preferences';
		$actions[2]['contents'][]=
			array ('href'=>'index.php?plugin=passwords&amp;action=modifyPreferencesPre',
			'name'=>'modify');

		$actions[3]['name']='help';
		$actions[3]['contents'][]=
				array ('href'=>'index.php?plugin=passwords&amp;action=help',
						'name'=>'help'
													               );
		return $actions;
	}

	/**
	 * Activate. Basically this means that the appropriate actions
	 * are executed and an optional result is returned
	 * to be processed/displayed
	 */
	function activate ()
	{
		//if (!stristr($_SERVER["SERVER_PROTOCOL"],'https'))
		if (!stristr($_SERVER["HTTPS"],'on'))
		{
			$this->renderEngine->assign ('message',
				'insecureConnection');
		}
		//die ($this->getAction ());
		switch ($this->getAction ())
		{
			case "help":
				$this->helpAction ();
				break;
			case "add":
				$this->addAction ();
				break;
			case "modify":
				$this->modifyAction ();
				break;
			case "showItem":
				$this->showAction ();
				break;
			case "deleteItemPost":
				$this->operations->deleteItem
					($this->getUserName(), $this->itemId);
				$this->getShowItemsParameters ();
				break;
			case "move":
				$this->moveAction ();
				break;
			case "moveItem":
				$this->moveItemAction ();
				$this->getShowItemsParameters ();
				break;
			case "multipleSelectPre":
				$_SESSION['passwordExpand']='*';
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
			case "searchPasswords":
				$this->searchItemAction();
				break;
			case "search":
				$this->renderer = $this->getAction().'Passwords';
				break;
                        case "addAndAddAnother":
                                $this->addItemAction ();
                                unset ($this->renderObjects);
                                $this->addAction ();
                                break;
			case "addItemPost":
				$this->addItemAction();
				break;
			case "modifyItemPost":
				$this->modifyItemAction();
				break;
			case "setYahooTree":
				$_SESSION['passwordTree']='Yahoo';
				$this->getShowItemsParameters ();
				break;
			case "setExplorerTree":
				$_SESSION['passwordTree']='Explorer';
				$this->getShowItemsParameters ();
				break;
			case "modifyPreferencesPre":
				$this->renderObjects =
					$this->preferences->getAllPreferences
						($this->getUserName ());
				$this->renderer = 'modifyPreferences';
				break;
			case "modifyPreferencesPost":
				$this->preferences->setPreference
					($this->getUserName (),
						$_POST['name'], $_POST['value']);
				$_SESSION['passwordTree']=
					$this->preferences->getPreferenceValue
						($this->getUserName (), 'passwordTree');
				$_SESSION['passwordYahooTreeColumnCount']=
						$this->preferences->getPreferenceValue
							($this->getUserName (),
								'passwordYahooTreeColumnCount');
				$this->getShowItemsParameters ();
				break;
			case "generate":
				$this->renderer = 'generate';
				break;
			case "modifyAskPassphrase":
				$this->renderEngine->assign ('itemId', $this->itemId);
				$this->renderEngine->assign ('requestedAction',
					'modify');
				$this->renderer = 'askPassphrase';
				break;
			case "showAskPassphrase":
				$this->renderEngine->assign ('itemId', $this->itemId);
				$this->renderEngine->assign ('requestedAction',
					'showItem');
				$this->renderer = 'askPassphrase';
				break;
			default:
				if (!isset ($_SESSION['passwordTree']))
				{
					$_SESSION['passwordTree']=
						$this->preferences->getPreferenceValue
							($this->getUserName (), 'passwordTree');
				}
				if (!isset ($_SESSION['passwordYahooTreeColumnCount']))
				{
					$_SESSION['passwordYahooTreeColumnCount']=
						$this->preferences->getPreferenceValue
							($this->getUserName (),
								'passwordYahooTreeColumnCount');
				}
				$this->getShowItemsParameters ();
				break;
			}
	}

    function showAction ()
    {
        $this->renderer = strtolower ($this->getItemName ());
        $this->renderEngine->assign ('viewAction', 'show');
        $this->renderEngine->assign ('pageTitle', $this->getItemName());
        $this->renderObjects =  $this->operations->
            getItem ($this->getUserName (),
				$this->itemId, $_POST['passPhrase']);
    }

    function modifyAction ()
    {
        $dictionary = $this->getDictionary ();
        $this->renderer = strtolower ($this->getItemName ());
        $this->renderEngine->assign ('viewAction', 'modify');
        $this->renderEngine->assign ('pageTitle', $dictionary['modify']);
        $this->renderObjects =  $this->operations->
            getItem ($this->getUserName (),
				$this->itemId, $_POST['passPhrase']);
    }

	/**
	 * Items parameters has been provided, now really add it
	 *
	 * @protected
	 * @modified Michael Haussmann
	 */
	function addItemAction ()
	{
		$item = $this->itemFactory->requestToItem ();
		$errors = $this->itemFactory->requestToItemErrors ();
		if (empty($errors))
		{
			$this->operations->addItem ($this->getUserName (),
				$item);
			$this->getShowItemsParameters ();
		}
		else
		{
			$this->addParameter("errors", $errors);
			$this->addAction ();
			$this->renderObjects = $this->itemFactory->decode($item);
		}
	}


	/**
	 * Items parameters has been provided, no really modify it
	 *
	 * @return boolean true if the item was succesfully added,
	 * false in case of failure
	 * @protected
	 * @modified Michael Haussmann
	 */
	function modifyItemAction ()
	{
		$dictionary = $this->getDictionary ();
		$baseItem = $this->operations->getItem
			($this->getUserName (),	$this->itemId);
		$item = $this->itemFactory->requestToItem($baseItem);
		$itemOwner = $this->operations->getItemOwner ($item->itemId);

		//
		// We are only able to modify an item which we own...
		//
		if ($itemOwner != $this->getUserName ())
		{
			$this->renderEngine->assign ("message",
				$dictionary['modify_not_owner']);
		}
		else
		{
			//
			// Check for errors
			//
			$errors = $this->itemFactory->requestToItemErrors ();
			if (empty($errors))
			{
				$this->operations->modifyItem($this->getUserName (),
					$item);
				$this->getShowItemsParameters ();
				return true;
			}
			else
			{
				$this->addParameter("errors", $errors);
				$this->modifyAction ();
				$this->renderObjects =
					$this->itemFactory->decode($item);
				$this->renderEngine->assign ('renderObjects',
					$this->renderObjects);
			}
		}
	}
}
?>
