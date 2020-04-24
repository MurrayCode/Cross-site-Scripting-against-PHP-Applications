<?php

require_once ('plugins/tasks/model/Task.php');
require_once ('plugins/tasks/model/TaskFactory.php');
require_once ('framework/model/Services.php');

/**
 * Operations on tasks
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - March 2003
 * @package org.brim-project.plugins.tasks
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class TaskServices extends Services
{
	/**
 	 * Default constructor
	 */
	function TaskServices ()
	{
		parent::Services();
		$this->itemFactory = new TaskFactory ();

		$queries = array ();
		include ('plugins/tasks/sql/taskQueries.php');
		$this->queries = $queries;
	}

	/**
	 * Adds a task item for a specific user
	 *
	 * @param string userId the identifier for the user
	 * @param object item the task item to be added
	 */
	function addItem ($userId, $item)
	{
 		if (!$item->isValid ()){ return null; }
		$now = date ("Y-m-d H:i:s");

		$query = sprintf ($this->queries['addItem'],
			$userId,
			addslashes ($item->parentId),
			addslashes ($item->isParent),
			addslashes ($item->name),
			addslashes ($item->description),
			addslashes ($item->visibility),
			addslashes ($now),
			addslashes ($item->priority),
			addslashes ($item->startDate),
			addslashes ($item->endDate),
			addslashes ($item->status),
			addslashes ($item->percentComplete),
			'false');

		$result = $this->db->Execute($query)
			or die("Add task: " . $this->db->ErrorMsg() . " " . $query);
	}

	/**
	 * Modifies a task
	 *
 	 * @param string userId the identifier for the user
	 * @param object item the modified item
	 */
	function modifyItem ($userId, $item)
	{
		$now = date ("Y-m-d H:i:s");

		if ($item->parentId != 0)
		{
			$parent = $this->getItem ($userId, $item->parentId);
			if (!$parent->isParent ())
			{
				die ('Can only move items to folders');
			}
		}
		$query = sprintf ($this->queries['modifyItem'],
			$now,
			addslashes ($item->name),
			addslashes ($item->visibility),
			addslashes ($item->isDeleted),
			addslashes ($item->description),
			addslashes ($item->priority),
			addslashes ($item->status),
			addslashes ($item->startDate),
			addslashes ($item->endDate),
			addslashes ($item->percentComplete),
			addslashes ($item->parentId),
			addslashes ($item->itemId));
		$result = $this->db->Execute($query)
			or die("ModifyTask: " . $this->db->ErrorMsg() . " " . $query);
	}

	/**
	 * Retrieves all tasks with a completed status
	 * @param string userId the identifier for the user
	 * @return array an array of the completed items for this user
	 */
	function getCompletedTasks ($userId)
	{
		$query = sprintf ($this->queries['getCompletedItems'], $userId);
		$result = $this->db->Execute($query) or
			die("GetItems: " . $this->db->ErrorMsg()." ".$query);
		return $this->itemFactory->resultsetToItems ($result);
	}
}
?>
