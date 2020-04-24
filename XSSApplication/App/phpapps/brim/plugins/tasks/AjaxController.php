<?php

require_once ('plugins/tasks/model/TaskServices.php');
require_once ('ext/JSON.php');
require_once ('framework/util/StringUtils.php');


/**
 * The  Ajax Controller. This class is some sort of guardian, since
 * only the functions in this class can be called...
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - 29 June 2006
 * @package org.brim-project.plugins.tasks
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class AjaxController
{
	/**
 	 * StringUtilities
	 *
	 * @var object
	 */
	var $stringUtils;

	/**
	 * The services
	 *
	 * @var object
	 */
	var $taskServices;
	
	
	/**
	 * The JSON libary
	 *
	 * @var object
	 */
	var $json;

	/**
	 * Default constructor, instantiates the services and the JSON library
	 *
	 * @return AjaxController
	 */
	function AjaxController ()
	{
		$this->taskServices = new TaskServices();
		$this->json = new Services_JSON();
		$this->stringUtils = new StringUtils ();
	}

	
	/**
	 * Increases the completedPercentage for a task by 10%. The provided
	 * array must contain an itemId. If the tasks percentage augments 100, it will be
	 * out to 100
	 *
	 * @param array $args input parameters, must contain the itemId
	 * @return string a json-encoded array with itemId and current percentCompleted for the itemId
	 */
	function increaseCompleted ($args)
	{
		$itemId = $args ['itemId'];
		
		$task = $this->taskServices->getItem ($_SESSION['brimUsername'], $itemId);
		if ($task->owner != $_SESSION['brimUsername'])
		{
			$status ['error']='Invalid access';
		}
		else 
		{
			$task->percentComplete = $task->percentComplete + 10;
			if ($task->percentComplete > 100) 
			{
				$task->percentComplete = 100;
			}
			$this->taskServices->modifyItem ($_SESSION['brimUsername'], $task);
			$status = array ();
			$status['itemId'] = $itemId;
			$status['percentComplete'] = $task->percentComplete;
		}
		return ($this->json->encode ($status));
	}
	
	/**
	 * Decreases the completedPercentage for a task by 10%. The provided
	 * array must contain an itemId. If the tasks percentage drops below
	 * zero (0), it will be put to zero.
	 *
	 * @param array $args input parameters, must contain the itemId
	 * @return string a json-encoded array with itemId and current percentCompleted for the itemId
	 */
	function decreaseCompleted ($args)
	{
		$itemId = $args ['itemId'];
		
		$task = $this->taskServices->getItem ($_SESSION['brimUsername'], $itemId);
		if ($task->owner != $_SESSION['brimUsername'])
		{
			$status ['error']='Invalid access';
		}
		else 
		{
			$task->percentComplete = $task->percentComplete - 10;
			if ($task->percentComplete < 0) 
			{
				$task->percentComplete = 0;
			}
			$this->taskServices->modifyItem ($_SESSION['brimUsername'], $task);
			$status = array ();
			$status['itemId'] = $itemId;
			$status['percentComplete'] = $task->percentComplete;
		}
		return ($this->json->encode ($status));
	}
	
	/**
	 * Decreases the priority of a task by 1 (i.e. urgent becomes high). The provided
	 * array must contain an itemId. If the tasks priority drops below 1, if will default
	 * to one (nice to have)
	 *
	 * @param array $args input parameters, must contain the itemId
	 * @return string a json-encoded array with itemId and current percentCompleted for the itemId
	 */
	function decreasePriority ($args)
	{
		$itemId = $args ['itemId'];
		
		$task = $this->taskServices->getItem ($_SESSION['brimUsername'], $itemId);
		if ($task->owner != $_SESSION['brimUsername'])
		{
			$status ['error']='Invalid access';
		}
		else 
		{
			$task->priority = $task->priority +1;
			if ($task->priority > 5) 
			{
				$task->priority = 5;
			}
			$this->taskServices->modifyItem ($_SESSION['brimUsername'], $task);
			$status = array ();
			$status['itemId'] = $itemId;
			$status['priority'] = $task->priority;
		}
		return ($this->json->encode ($status));
	}

	/**
	 * Increases the priority of a task by 1 (i.e. high becomes urgent). The provided
	 * array must contain an itemId. If the tasks priority augments 5 (urgent), if will default
	 * to 5.
	 *
	 * @param array $args input parameters, must contain the itemId
	 * @return string a json-encoded array with itemId and current percentCompleted for the itemId
	 */
	function increasePriority ($args)
	{
		$status = array ();
		$itemId = $args ['itemId'];
		$task = $this->taskServices->getItem ($_SESSION['brimUsername'], $itemId);
		if ($task->owner != $_SESSION['brimUsername'])
		{
			$status ['error']='Invalid access';
		}
		else 
		{
			$task->priority = $task->priority -1;
			if ($task->priority < 1) 
			{
				$task->priority = 1;
			}
			$this->taskServices->modifyItem ($_SESSION['brimUsername'], $task);
			$status['itemId'] = $itemId;
			$status['priority'] = $task->priority;
		}
		return ($this->json->encode ($status));
	}
	
	/**
	 * Sets a new duedate on the task and returns the new value after
	 * modifying it. 
	 *
	 * @param array $args input parameters, must contain an itemId and a dueDate
	 * @return string a json-encoded array with the itemId and the new dueDate
	 */
	function newDueDate ($args)
	{
		$itemId = $args['itemId'];
		$task = $this->taskServices->getItem ($_SESSION['brimUsername'], $itemId);
		if ($task->owner != $_SESSION['brimUsername'])
		{
			$status ['error']='Invalid access';
		}
		else 
		{
			$task->endDate = $args['dueDate'];
			$this->taskServices->modifyItem ($_SESSION['brimUsername'], $task);
			$status = array ();
			$status['itemId'] = $itemId;
			$status['dueDate'] = $task->dueDate;
		}
		return ($this->json->encode ($status));
	}

	/**
	 * Sets a new startdate on the task and returns the new value after
	 * modifying it. 
	 *
	 * @param array $args input parameters, must contain an itemId and a startDate
	 * @return string a json-encoded array with the itemId and the new startDate
	 */
	function newStartDate ($args)
	{
		$itemId = $args['itemId'];
		$task = $this->taskServices->getItem ($_SESSION['brimUsername'], $itemId);
		if ($task->owner != $_SESSION['brimUsername'])
		{
			$status ['error']='Invalid access';
		}
		else 
		{
			$task->startDate = $args['startDate'];
			$this->taskServices->modifyItem ($_SESSION['brimUsername'], $task);
			$status = array ();
			$status['itemId'] = $itemId;
			$status['startDate'] = $task->startDate;
		}
		return ($this->json->encode ($status));
	}


    /**
     * Change an item.
     *
     * @param array args, an attay containing a key called 'xxx_id', where
     * 'xxx' indicates the field to change (i.e. 'name_12' indicates that we
     * would like to change the name for item with id 12) and 'value', with the new
     * value.
     *
     * @return  string the value that has changed
     * @todo check the result (JSON is not possible?(
     * @todo at present the values are retrieved from the request and not from the arguments
     */
	function change ($args)
	{
		$toChange = split ("_", $_REQUEST ['id']);
		$itemId = $toChange [1];
        $task = $this->taskServices->getItem ($_SESSION['brimUsername'], $itemId);
        if ($task->owner != $_SESSION['brimUsername'])
        {
            $status ['error']='Invalid access';
        }
		else
		{
			$value = $this->stringUtils->gpcStripSlashes ($_REQUEST['value']);
			switch ($toChange [0])
			{
				//
				// Currently we can only change the name and status
				//
				case 'name':
					$task->name = $value;
					$status['itemId'] = $itemId;
					$status['result'] = $task->name;
					break;
				case 'status':
					$task->status = $value;
					$status['itemId'] = $itemId;
					$status['result'] = $task->status;
					break;
				default:
            		$status ['error']='Invalid access';
			}
			$this->taskServices->modifyItem ($_SESSION['brimUsername'], $task);
		}
		//return ($this->json->encode ($status));
		return $value;
	}

    /**
     * Trash an item.
     *
     * @param array args, the arguments that must contain an itemId
     * @return string a JSON encoded status message (either contains
     * an error or a message)
     */
	function trash ($args)
	{
		$itemId = $args['itemId'];
        	$task = $this->taskServices->getItem ($_SESSION['brimUsername'], $itemId);
        	if ($task->owner != $_SESSION['brimUsername'])
        	{
            		$status ['error']='Invalid access';
        	}
		else
		{
			$this->taskServices->trash ($_SESSION['brimUsername'], $itemId);
            		$status ['msg']='Item trashed saved';
		}
		return ($this->json->encode ($status));
	}

	/**
	 * Completes an item. Basically its percentage completed is set to 100
	 * @param array args an array the must contain an itemId
	 * @return string a JSON encoded status string
	 */
	function completeItem ($args)
	{
		$itemId = $args['itemId'];
		$status = $this->changeCompletePercentage ($itemId, 100);
		return ($this->json->encode ($status));
	}
	/**
	 * Specific behaviour for folders
	 * Uncompletes an item. Basically its percentage completed is set to 0
	 * @return string a JSON encoded status string
	 */
	function uncompleteItem ($args)
	{
		$itemId = $args['itemId'];
		$status = $this->changeCompletePercentage ($itemId, 0);
		return ($this->json->encode ($status));
	}

	/**
	 * Changes the completed percentage for a specific  item
	 * @param integer itemId the items id
	 * @param integer newPercentage the new completed percentage for this item
	 * @return string a JSON encoded status message
	 */
	function changeCompletePercentage ($itemId, $newPercentage)
	{
        $task = $this->taskServices->getItem ($_SESSION['brimUsername'], $itemId);
        if ($task->owner != $_SESSION['brimUsername'])
        {
            $status ['error']='Invalid access';
        }
		else
		{
			$task->percentComplete=$newPercentage;
			$this->taskServices->modifyItem ($_SESSION['brimUsername'], $task);
			$status ['message']='Ok';
		}
		return $status;
	}


    /**
     * Move an item to a new parent
     *
     * @param array args, arguments containing at least an itemId  and a parentId
     *
     * @return string a JSON encoded status message (either contains
     * an error or a message)
     */
	function moveItem ($args)
	{
		$itemId = $args['itemId'];
		$parentId = $args['parentId'];
        $task = $this->taskServices->getItem ($_SESSION['brimUsername'], $itemId);
        if ($task->owner != $_SESSION['brimUsername'])
        {
            $status ['error']='Invalid access';
        }
		else
		{
			$task->parentId = $parentId;
			$this->taskServices->modifyItem ($_SESSION['brimUsername'], $task);
			$status ['message']='Ok';
		}
		return ($this->json->encode ($status));
	}
}
?>
