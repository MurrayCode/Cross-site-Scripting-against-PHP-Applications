<?php

require_once ('framework/model/ItemFactory.php');
require_once ('plugins/tasks/model/Task.php');

/**
 * TaskFactory
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.tasks
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class TaskFactory extends ItemFactory
{
		/**
		 * Default constructor
		 */
		function TaskFactory ()
		{
			parent::ItemFactory ();
		}

		/**
		 * Returns the type of this specific item
		 * @return string the type of this specific item:
		 * <code>Task</code>
		 */
		function getType ()
		{
			return "Task";
		}

		/**
		 * Factory method. Return an HTTP request into an item by
		 * fecthing the appropriate parameters from the POST request
		 *
		 * @return object the item constructed from the POST request
		 * @uses the POST request
		 */
		function requestToItem ()
		{
			$visibility = $this->getFromPost ('visibility', 'private');
			$parentId = $this->getFromPost ('parentId', 0);
			$isParent = $this->getFromPost ('isParent', 0);
			$when_created = $this->getFromPost ('when_created', null);
			$when_modified = $this->getFromPost ('when_modified', null);
			$deleted = $this->getFromPost ('isDeleted', 0);
			$itemId = $this->getFromPost ('itemId', 0);
			$category = $this->getFromPost ('category', null);
			$description = $this->getFromPost ('description', null);
			$isFinished = $this->getFromPost('isFinished', 'false');
			$percComplete = $this->getFromPost ('percentComplete', 0);
			$priority = $this->getFromPost ('priority', 0);
			$status = $this->getFromPost ('status', null);

			$startyear = $_POST['StartDate_Year'];
			$startmonth = $_POST['StartDate_Month'];
			$startday = $_POST['StartDate_Day'];
			$startDate = $startyear.'-'.$startmonth.'-'.$startday;

			$endyear = $_POST['DueDate_Year'];
			$endmonth = $_POST['DueDate_Month'];
			$endday = $_POST['DueDate_Day'];
			$endDate = $endyear.'-'.$endmonth.'-'.$endday;

			$name = $this->stringUtils->gpcStripSlashes ($_POST['name']);

			$item = new Task (
				$itemId,
				$_SESSION['brimUsername'],
				$parentId,
				$isParent,
				$name,
				$description,
				$visibility,
				$category,
				$deleted,
				$when_created,
				$when_modified,
				$priority,
				$startDate,
				$endDate,
				$status,
				$percComplete,
				$isFinished
			);
			return $item;
		}

		/**
	 	* Factory method: Returns a database result into an item
	 	*
	 	* @param object result the result retrieved from the database
	 	* @return array the items constructed from the database resultset
	 	*/
		function resultsetToItems ($result)
		{
			$items = array ();
			while (!$result->EOF)
			{
				$item = new Task (
					$result->fields['item_id'],
					trim ($result->fields['owner']),
					$result->fields['parent_id'],
					$result->fields['is_parent'],
					trim ($this->stringUtils->gpcStripSlashes
						($result->fields['name'])),
					trim ($this->stringUtils->gpcStripSlashes
						($result->fields['description'])),
					trim ($result->fields['visibility']),
					trim ($result->fields['category']),
					$result->fields['is_deleted'],
					$result->fields['when_created'],
					$result->fields['when_modified'],
					trim ($result->fields['priority']),
					$result->fields['start_date'],
					$result->fields['end_date'],
					trim ($result->fields['status']),
					trim ($result->fields['percent_complete']),
					$result->fields['is_finished']
				);
				$items [] = $item;
				$result->MoveNext();
			}
			return $items;
		}
	}
?>