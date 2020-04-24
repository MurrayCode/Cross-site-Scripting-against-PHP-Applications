<?php

require_once ('framework/model/Item.php');

/**
 * A Task item.
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
class Task extends Item
{
	/**
	 * The priority of this item
	 * @access private
	 * @var integer
	 */
	var $priority;

	/**
	 * The startdate of this item
	 * @access private
	 * @var string
	 */
	var $startDate;

	/**
	 * The enddate of this item
	 * @access private
	 * @var string
	 */
	var $endDate;

	/**
	 * The status of this item
	 * @access private
	 * @var string
	 */
	var $status;

	/**
	 * The percentage completed of this item
	 * @access private
	 * @var integer
	 */
	var $percentComplete;

	/**
	 * Indicator whether this task item is finished
	 * @access private
	 * @var bool
	 */
	var $isFinished;

	/**
	 * Full blown constructor with all parameters
	 *
	 * @param integer theItemId the id of the item
	 * @param string theOwner who owns this item?
	 * @param integer theParentId what is the id of the parent of this item?
	 * @param boolean parent is this a parent (true) or child (false)
	 * @param string theName the name of this item
	 * @param string theDescription the description of this item
	 * @param string theVisibility the visibility (private or public)
	 * @param string theCategory what is the category of this item?
	 * @param boolean deleted is this item marked as being deleted?
	 * @param string theCreation When was this item created?
	 * @param string theModified When was this item modified?
	 * @param integer thePriority The priority of this item
	 * @param string theStartDate the start date of this task item
	 * @param string theEndDate the end date of this task item
	 * @param string theStatus the status of this item
	 * @param integer percCompleted percentage completed
	 * @param boolean finished is this task item finished yet?
  	 */
	function Task ($theID, $theOwner, $theParentId, $parent, $theName,
		$theDescription, $theVisibility, $theCategory, $deleted,
		$theCreation, $theModified,
		$thePriority, $theStartDate,$theEndDate,
		$theStatus, $percCompleted, $finished)
	{
		parent :: Item ($theID, $theOwner, $theParentId, $parent,
				$theName, $theDescription, $theVisibility,
				$theCategory, $deleted, $theCreation, $theModified);

		$this->type="Task";
		$this->priority = $thePriority;
		$this->startDate = $theStartDate;
		$this->endDate = $theEndDate;
		$this->status=$theStatus;
		$this->percentComplete=$percCompleted;
		$this->isFinished = $finished;
	}
}
?>