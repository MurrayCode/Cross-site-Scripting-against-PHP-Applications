<?php

require_once ('framework/ItemController.php');
require_once ('plugins/notes/model/NoteServices.php');
require_once ('plugins/notes/model/NotePreferences.php');
require_once ('plugins/notes/model/NoteFactory.php');

/**
 * The Note Controller
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.plugins.notes
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class NoteController extends ItemController
{
	/**
	 * Constructor.
	 * Makes sure that the appropriate operations are instantiated.
	 */
	function NoteController ()
	{
		parent::ItemController ();
		$this->operations = new NoteServices ();
		$this->preferences = new NotePreferences ();
		$this->itemFactory = new NoteFactory ();

		$this->pluginName = 'notes';
		$this->title = 'Brim - Notes';
		$this->itemName = 'Note';

		$this->expandName = 'noteExpand';
	}

	/**
 	 * Returns the actions defined for this item only
 	 *
 	 * @return array an array of item specific actions (like search, import etc.)
 	 */
	function getActions ()
	{
		$actions=array();

		// Actions
		$actions[0]['name']= 'actions';
		$actions[0]['contents'][]=
			array('href' => 'index.php?plugin=notes&amp;action=add&amp;parentId='.$this->getParentId (),
			'name' => 'add');
		$actions[0]['contents'][]=
			array ('href'=>'index.php?plugin=notes&amp;action=multipleSelectPre&amp;parentId='.$this->getParentId(),
			'name'=>'multipleSelect');
		$actions[0]['contents'][]=
			array('href' => 'index.php?plugin=notes&amp;action=search',
			'name' => 'search');

		//
		// If we are simply 'viewing' an item, add 'modify' as action
		// to the action list
		//
		if ($this->renderEngine->getTokens ('viewAction') == 'show')
		{
			$actions[0]['contents'][]= array (
			'href'=>'index.php?plugin=notes&amp;action=modify&amp;itemId='.$this->itemId,
			'name'=>'modify');
		}

		// View
		$actions[1]['name']= 'view';
/*
		$actions[1]['contents'][]=
			array('href' => 'index.php?plugin=notes&amp;expand=*',
			'name' => 'expand');
		$actions[1]['contents'][]=
			array('href' => 'index.php?plugin=notes&amp;expand=0',
			'name' => 'collapse');
		$actions[1]['contents'][]=
			array('href' => 'index.php?plugin=notes&amp;action=setYahooTree&amp;parentId='.$this->getParentId (),
			'name' => 'yahooTree');
		$actions[1]['contents'][]=
			array('href' => 'index.php?plugin=notes&amp;action=setExplorerTree&amp;parentId='.$this->getParentId (),
			'name' => 'explorerTree');
*/
		$actions[1]['contents'][] =
			array('href' => 'index.php?plugin=notes&amp;action=setModePublic&amp;parentId='.$this->getParentId (),
			'name' => 'setModePublic');
		$actions[1]['contents'][] =
			array('href' => 'index.php?plugin=notes&amp;action=setModePrivate&amp;parentId='.$this->getParentId (),
			'name' => 'setModePrivate');

		// Preferences
		$actions[2]['name']= 'preferences';
		$actions[2]['contents'][]=
			array ('href'=>'index.php?plugin=notes&amp;action=modifyPreferencesPre',
			'name'=>'modify');

		$actions[3]['name']='help';
		$actions[3]['contents'][]=
				array ('href'=>'index.php?plugin=notes&amp;action=help',
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
				$this->deleteItemAction ();
				$this->getShowItemsParameters ();
				break;
            case "showTrash":
                $this->renderObjects=$this->operations->getTrashedItems
                    ($this->getUserName ());
                $this->renderer = 'trash';
                break;
            case "undelete":
                foreach ($_POST as $submitted=>$value)
                {
                    if (substr ($submitted, 0, strlen ('itemid_')) == 'itemid_')
                    {
                        if ($value=='on')
                        {
                            $this->operations->unTrash
                                ($this->getUsername (), substr ($submitted, strlen ('itemid_')));
                        }
                    }
                }
                $this->renderEngine->assign ('trashCount',
                    $this->operations->getTrashCount ($this->getUserName ()));
                $this->getShowItemsParameters ();
                break;

			case "move":
				$this->moveAction ();
				break;
			case "moveItem":
				$this->moveItemAction ();
				$this->getShowItemsParameters ();
				break;
			// Added by Michael. Default navigation mode.
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
			case "multipleSelectPre":
				$_SESSION['noteExpand']='*';
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
						$this->operations->deleteItem ($this->getUserName (), $itemId);
					}
					$this->getShowItemsParameters ();
				}
				break;
			case "moveMultipleItemsPost":
				$itemIds = explode (",", $_GET['itemIds']);
				$parentId = $_GET['parentId'];
				foreach ($itemIds as $itemId)
				{
					$this->operations->moveItem ($this->getUserName (), $itemId, $parentId);
				}
				$this->getShowItemsParameters ();
				break;
			case "searchNotes":
				$this->searchItemAction();
				break;
			case "search":
				$this->renderer = $this->getAction().'Notes';
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
				$this->getShowItemsParameters ();
				break;
			case "setYahooTree":
				$_SESSION['noteTree']='Yahoo';
				$this->getShowItemsParameters ();
				break;
			case "setExplorerTree":
				$_SESSION['noteTree']='Explorer';
				$this->getShowItemsParameters ();
				break;
			case "modifyPreferencesPre":
				$this->renderObjects =
					$this->preferences->getAllPreferences ($this->getUserName ());
				$this->renderer = 'modifyPreferences';
				break;
			case "modifyPreferencesPost":
				$this->preferences->setPreference ($this->getUserName (), $_POST['name'], $_POST['value']);
				$_SESSION['noteTree']=
					$this->preferences->getPreferenceValue ($this->getUserName (), 'noteTree');
				$_SESSION['noteOverlib']=
						$this->preferences->getPreferenceValue ($this->getUserName (), 'noteOverlib');
				$_SESSION['noteYahooTreeColumnCount']=
						$this->preferences->getPreferenceValue ($this->getUserName (), 'noteYahooTreeColumnCount');
				$this->renderer = 'modifyPreferences';
				$this->renderObjects=$_SESSION;
				break;
			default:
				if (!isset ($_SESSION['noteTree']))
				{
					$_SESSION['noteTree']=
						$this->preferences->getPreferenceValue ($this->getUserName (), 'noteTree');
				}
				if (!isset ($_SESSION['noteOverlib']))
				{
					$_SESSION['noteOverlib']=
						$this->preferences->getPreferenceValue ($this->getUserName (), 'noteOverlib');
				}
				if (!isset ($_SESSION['noteYahooTreeColumnCount']))
				{
					$_SESSION['noteYahooTreeColumnCount']=
						$this->preferences->getPreferenceValue ($this->getUserName (), 'noteYahooTreeColumnCount');
				}
				$this->getShowItemsParameters ();
				break;
			}
	}
}
?>
