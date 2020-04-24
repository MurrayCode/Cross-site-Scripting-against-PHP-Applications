<?php

require_once ('framework/model/Item.php');

/**
 * The Event item.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - June 2004
 * @package org.brim-project.plugins.calendar
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class Event extends Item
{
	var $location;
	var $organizer;
	var $priority;
	var $frequency;
	var $eventInterval;
	var $byWhat;
	var $byWhatValue;
	var $eventStartDate;
	var $eventEndDate;
	var $eventRecurringEndDate;
	var $eventColour;

	/**
	 * An array of reminders
	 */
	var $reminders;

	/**
	 * Full-blown Constructor.
	 *
	 * @param integer theItemId the id of the item
	 * @param string theOwner who owns this item?
	 * @param integer theParentId what is the id of the parent of
	 *		this item?
	 * @param boolean parent is this a parent (true) or child (false)
	 * @param string theName the name of this item
	 * @param string theDescription the description of this item
	 * @param string theVisibility the visibility (private or public)
	 * @param string theCategory what is the category of this item?
	 * @param boolean deleted is this item marked as deleted?
	 * @param string created When was this item created?
	 * @param string modified When was this item modified?
	 * @param string theLocation
	 * @param string theOrganizer
	 * @param integer thePriority
	 * @param string theFrequency (monthly, daily or yearly)
	 * @param xxx theEventInterval
	 * @param string theByWhat
	 * @param string theByWhatValue
	 * @param date eventStartDate
	 * @param date eventEndDate
	 * @param date eventRecurringEndDate
	 */
	function Event (
		$theItemId, $theOwner, $theParentId, $parent,$theName,
		$theDescription, $theVisibility, $theCategory, $deleted,
		$created, $modified, $theLocation,
		$theOrganizer, $thePriority, $theFrequency,
		$theEventInterval, $theByWhat, $theByWhatValue,
		$theStartDate, $theEndDate, $theRecurringEndDate, $theColour)
	{
		parent :: Item (
				$theItemId,
				$theOwner,
				$theParentId,
				$parent,
				$theName,
				$theDescription,
				$theVisibility,
				$theCategory,
				$deleted,
				$created,
				$modified);

		$this->type = "Event";
		$this->location = $theLocation;
		$this->organizer = $theOrganizer;
		$this->priority = $thePriority;
		$this->frequency = $theFrequency;
		$this->eventInterval = $theEventInterval;
		$this->byWhat = $theByWhat;
		$this->byWhatValue = $theByWhatValue;
		$this->eventStartDate = $theStartDate;
		$this->eventEndDate = $theEndDate;
		$this->eventRecurringEndDate = $theRecurringEndDate;
		$this->eventColour = $theColour;

		$this->reminders = array ();
	}

	/**
	 * Checks whether the constructed item is a valid item (has all the
	 * required fields)
	 *
	 * @return boolean <code>true</code>
	 * if the item is valid, <code>false</code> otherwise
	 */
	function isValid ()
	{
		if (!$this->isParent ())
		{
			return (parent::isValid () &&
				isset ($this->startDate) &&
				isset ($this->endDate));
		}
		return parent::isValid ();
	}

	function addReminder ($reminder)
	{
		if ($reminder->eventId != $this->itemId)
		{
			die ('ReminderID is not the eventId, this is a bug');
		}
		$this->reminders[]=$reminder;
	}

	function setReminders ($theReminders)
	{
		if (isset ($theReminders))
		{
			foreach ($theReminders as $reminder)
			{
				if ($reminder->eventId != $this->itemId)
				{
					die ('ReminderID is not the eventId, this is a bug');
				}
			}
			$this->reminders = $theReminders;
		}
	}
}
?>