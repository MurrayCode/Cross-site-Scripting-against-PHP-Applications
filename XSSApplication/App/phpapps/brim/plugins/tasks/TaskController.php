<?php

require_once ("framework/ItemController.php");
require_once ("plugins/tasks/model/Task.php");
require_once ("plugins/tasks/model/TaskFactory.php");
require_once ("plugins/tasks/model/TaskServices.php");
require_once ('plugins/tasks/model/TaskPreferences.php');

/**
 * The Task Controller
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.plugins.tasks
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class TaskController extends ItemController
{
	/**
	 * Constructor.
	 * Makes sure that the appropriate operations are instantiated.
	 */
	function TaskController ()
	{
		parent::ItemController ();
		$this->operations = new TaskServices ();
		$this->preferences = new TaskPreferences ();
		$this->itemFactory = new TaskFactory ();

		$this->pluginName = 'tasks';
		$this->title = 'Brim - Tasks';
		$this->itemName = 'Task';

		$this->expandName = 'taskExpand';
	}

	/**
 	 * Returns the actions defined for this item only
 	 *
 	 * @return array an array of item specific actions (like search, import etc.)
 	 */
	function getActions ()
	{
		$actions=array();

		/*
		 * Actions
		 */
		$actions[0]['name']='actions';
		$actions[0]['contents'][]=
			array('href' => 'index.php?plugin=tasks&amp;action=add&amp;parentId='.$this->getParentId(),
			'name' => 'add');
		$actions[0]['contents'][]=
			array('href' => 'index.php?plugin=tasks&amp;action=multipleSelectPre&amp;parentId='.$this->getParentId(),
			'name'=> 'multipleSelect');
		$actions[0]['contents'][]=
			array('href' => 'index.php?plugin=tasks&amp;action=search',
			'name' => 'search');
		if (isset ($_SESSION['taskHideCompleted']) && ($_SESSION['taskHideCompleted']))
		{
			$actions[0]['contents'][]=
				array('href' => 'index.php?plugin=tasks&amp;action=showCompleted',
					'name' => 'showCompleted');
		}
		else
		{
			$actions[0]['contents'][]=
				array('href' => 'index.php?plugin=tasks&amp;action=hideCompleted',
					'name' => 'hideCompleted');
		}

		//
		// If we are simply 'viewing' an item, add 'modify' as action
		// to the action list
		//
		if ($this->renderEngine->getTokens ('viewAction') == 'show')
		{
			$actions[0]['contents'][]= array (
				'href'=>'index.php?plugin=tasks&amp;action=modify&amp;itemId='.$this->itemId,
				'name'=>'modify');
		}

		/*
		 * View
		 */
		$actions[1]['name']='view';
		/*
		$actions[1]['contents'][]=
					array('href' => 'index.php?plugin=tasks&amp;expand=*',
						'name' => 'expand');
		$actions[1]['contents'][]=
					array('href' => 'index.php?plugin=tasks&amp;expand=0',
						'name' => 'collapse');
		$actions[1]['contents'][]=
					array('href' => 'index.php?plugin=tasks&amp;action=setYahooTree&amp;parentId='.$this->getParentId (),
						'name' => 'yahooTree');
		$actions[1]['contents'][]=
					array('href' => 'index.php?plugin=tasks&amp;action=setOverviewTree&amp;parentId='.$this->getParentId (),
						'name' => 'overviewTree');
		$actions[1]['contents'][]=
					array('href' => 'index.php?plugin=tasks&amp;action=setLineBasedTree&amp;parentId='.$this->getParentId (),
						'name' => 'lineBasedTree');
		$actions[1]['contents'][]=
					array('href' => 'index.php?plugin=tasks&amp;action=setExplorerTree&amp;parentId='.$this->getParentId (),
						'name' => 'explorerTree');
		*/
		$actions[1]['contents'][] =
					array('href' => 'index.php?plugin=tasks&amp;action=setModePublic&amp;parentId='.$this->getParentId (),
						'name' => 'setModePublic');
		$actions[1]['contents'][] =
					array('href' => 'index.php?plugin=tasks&amp;action=setModePrivate&amp;parentId='.$this->getParentId (),
						'name' => 'setModePrivate');

		/*
		 * Sort
		 */
		 /*
		$actions[2]['name']='sort';
		$actions[2]['contents'][] =
					array('href'=> 'index.php?plugin=tasks&amp;action=sort&amp;order=ASC&amp;field=priority&amp;parentId='.$this->getParentId (),
						'name' => 'priority');
		$actions[2]['contents'][] =
					array('href'=> 'index.php?plugin=tasks&amp;action=sort&amp;order=DESC&amp;field=percentComplete&amp;parentId='.$this->getParentId (),
						'name' => 'complete');
		$actions[2]['contents'][] =
					array('href'=> 'index.php?plugin=tasks&amp;action=sort&amp;order=ASC&amp;field=startDate&amp;parentId='.$this->getParentId (),
						'name' => 'start_date');
		$actions[2]['contents'][] =
					array('href'=> 'index.php?plugin=tasks&amp;action=sort&amp;order=ASC&amp;field=endDate&amp;parentId='.$this->getParentId (),
						'name' => 'due_date');
		*/
		/*
		 * Preferences
		 */
		$actions[3]['name']='preferences';
		$actions[3]['contents'][] =
					array ('href'=>'index.php?plugin=tasks&amp;action=modifyPreferencesPre',
						'name'=>'preferences');

		$actions[4]['name']='help';
		$actions[4]['contents'][]=
			array ('href'=>'index.php?plugin=tasks&amp;action=help',
				'name'=>'help'
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
//die (print_r ($this->getAction ()));
		$this->renderEngine->assign ('action', $this->getAction ());
		$this->renderEngine->assign ('trashCount', 
			$this->operations->getTrashCount ($this->getUserName ()));
		switch ($this->getAction ())
		{
			case "hideCompleted":
				$_SESSION['taskHideCompleted']=1;
				$this->getShowItemsParameters ();
				break;
			case "showCompleted":
				$_SESSION['taskHideCompleted']=0;
				$this->getShowItemsParameters ();
				break;
			case "showCompletedOnly":
				$this->renderObjects = $this->operations->getCompletedTasks ($this->getUserName ());
				$this->renderer = 'show'.$this->getItemName().'s';
				break;
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
				//$this->operations->deleteItem
					//($this->getUserName(), $this->itemId);
				$this->getShowItemsParameters ();
				break;
			// Added by Michael. Default navigation mode.
			// If used, change in other plugins.
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
			case "deleteForever":
				foreach ($_POST as $submitted=>$value)
				{
					if (substr ($submitted, 0, strlen ('itemid_')) == 'itemid_')
					{
						if ($value=='on')
						{
							//die (print_r ("Deleting ".substr ($submitted, strlen ('itemid_'))));
							$this->operations->deleteItem 
								($this->getUsername (), substr ($submitted, strlen ('itemid_')));
						}
					}
				}
				$this->getShowItemsParameters ();
				break;
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
			case "searchTasks":
				    $this->renderObjects =
						$this->operations->searchItems
         					($this->getUserName(),
								$_POST['field'], $_POST['value']);
					$this->renderer = 'showTasks';
				break;
			case "search":
				$this->renderer = $this->getAction().'Tasks';
				break;
			case "sort":
				$parentId = $_GET['parentId'];
				$field = $_GET['field'];
				$order = $_GET['order'];
				$this->sortAction ($parentId, $field, $order);
				break;
			case "modifyItemPost":
				$this->operations->modifyItem($this->getUserName(),
					$this->itemFactory->requestToItem());
				// and show all items
				$this->getShowItemsParameters ();
				break;
                        case "addAndAddAnother":
                                $this->addItemAction ();
                                unset ($this->renderObjects);
                                $this->addAction ();
                                break;
			case "addItemPost":
				$this->operations->addItem($this->getUserName(),
					$this->itemFactory->requestToItem ());
				// and show all items
				$this->getShowItemsParameters ();
				break;
			case "multipleSelectPre":
				$_SESSION['taskExpand']='*';
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
				$parentId = $_GET['parentId'];
				$itemIds = explode (",", $_GET['itemIds']);
				foreach ($itemIds as $itemId)
				{
					$this->operations->moveItem ($this->getUserName (),
						$itemId, $parentId);
				}
				$this->getShowItemsParameters ();
				break;
/*
			case "setYahooTree":
				$_SESSION['taskTree']='Yahoo';
				$this->getShowItemsParameters ();
				break;
			case "setOverviewTree":
				$_SESSION['taskTree']='Overview';
				$this->getShowItemsParameters ();
				break;
			case "setLineBasedTree":
				$_SESSION['taskTree']='LineBased';
				$this->getShowItemsParameters ();
				break;
			case "setExplorerTree":
				$_SESSION['taskTree']='Explorer';
				$this->getShowItemsParameters ();
				break;
*/
			case "move":
				$this->moveAction ();
				break;
			case "moveItem":
				$this->moveItemAction ();
				$this->getShowItemsParameters ();
				break;
			case "modifyPreferencesPre":
				$this->renderObjects =
					$this->preferences->getAllPreferences ($this->getUserName ());
				$this->renderer = 'modifyPreferences';
				break;
			case "modifyPreferencesPost":
				$this->preferences->setPreference ($this->getUserName (), $_POST['name'], $_POST['value']);
				$_SESSION['taskTree']=
					$this->preferences->getPreferenceValue
						($this->getUserName (),
							'taskTree');
				$_SESSION['taskOverlib']=
					$this->preferences->getPreferenceValue
						($this->getUserName (),
							'taskOverlib');
				$_SESSION['taskYahooTreeColumnCount']=
					$this->preferences->getPreferenceValue
						($this->getUserName (),
							'taskYahooTreeColumnCount');
				$_SESSION['taskHideCompleted']=
					$this->preferences->getPreferenceValue
						($this->getUserName (),
							'taskHideCompleted');
				//$this->getShowItemsParameters ();
				$this->renderer = 'modifyPreferences';
				$this->renderObjects=$_SESSION;
				break;
			case "showTrash":
				$this->renderObjects=$this->operations->getTrashedItems 
					($this->getUserName ());
				$this->renderer = 'trash';
				break;
			default:
				if (!isset ($_SESSION['taskTree']))
				{
					$_SESSION['taskTree']=
						$this->preferences->getPreferenceValue
							($this->getUserName (),
								'taskTree');
				}
				if (!isset ($_SESSION['taskOverlib']))
				{
					$_SESSION['taskOverlib']=
						$this->preferences->getPreferenceValue
							($this->getUserName (),
								'taskOverlib');
				}
				if (!isset ($_SESSION['taskYahooTreeColumnCount']))
				{
					$_SESSION['taskYahooTreeColumnCount']=
						$this->preferences->getPreferenceValue
							($this->getUserName (),
								'taskYahooTreeColumnCount');
				}
				if (!isset ($_SESSION['taskHideCompleted']))
				{
					$_SESSION['taskHideCompleted']=
						$this->preferences->getPreferenceValue ($this->getUserName (), 'taskHideCompleted');
				}
				$this->getShowItemsParameters ();
				break;
			}
	}
}
?>
