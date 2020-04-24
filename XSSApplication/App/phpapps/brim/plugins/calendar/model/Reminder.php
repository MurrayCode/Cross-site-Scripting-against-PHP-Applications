<?php

require_once ('framework/model/Item.php');

/**
 * The Reminder item.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - February 2006
 * @package org.brim-project.plugins.calendar
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class Reminder extends Item
{
	/**
 	 * The id of the event
	 * @param integer
	 */
	var $eventId;
	/**
 	 * Timespan. Minutues (m), Hours (h) or days (d)
	 * @param char
	 */
	var $timespan;

	/**
	 * reminderTime
	 * @param integer in seconds
	 */
	var $reminderTime;

	/**
	 * When is a reminder sent (null if not yet sent)
	 * This parameter will not be set by the application, but by
	 * an external script.
	 * @param datetime
	 */
	var $whenSent;

	/**
	 * eventStartTime
	 */
	var $eventStartDate;
	
	/**
	 * Javascript has some difficulties dealing with the date 
	 * representation from php, so date calculations become difficult.
	 * Lets bypass and do it here...
	 *
	 * @var date
	 */
	var $whenToSend;
	
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
  	 * @param theEventId
	 * @param theTimespan
	 * @param theReminderTime
	 * @param theWhenSent when this reminder was sent
	 * @param theEventStartDate the starttime of the event to which this reminder refers
	 */
	function Reminder (
		$theItemId, $theOwner, $theParentId, $parent,$theName,
		$theDescription, $theVisibility, $theCategory, $deleted,
		$created, $modified, $theEventId, $theTimespan, $theReminderTime, $theWhenSent,
		$theEventStartDate)
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

		$this->type = "Reminder";
		$this->eventId = $theEventId;
		$this->timespan = $theTimespan;
		$this->reminderTime = $theReminderTime;
		$this->whenSent = $theWhenSent;
		$this->eventStartDate=$theEventStartDate;
		$this->whenToSend = date ('Y-m-d H:i:s', strtotime ($this->eventStartDate) - $this->reminderTime);
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
			return (
				($this->eventId != 0) &&
				($this->reminderTime != '-1'));
		}
		return parent::isValid ();
	}

	function toArray ()
	{
		$result = array ();
		$result ['itemId'] = $this->itemId;
		$result ['owner'] = $this->owner;
		$result ['parentId'] = $this->parentId;
		$result ['isParent'] = $this->isParent;
		$result ['name'] = $this->name;
		$result ['description'] = ($this->description==null?'':$this->description);
		$result ['visibility'] = ($this->visibility == null?'':$this->visibility);
		$result ['category'] = ($this->category == null?'':$this->category);
		$result ['is_deleted'] = $this->isDeleted;
		$result ['when_created'] = (($this->whenCreated == null || $this->whenCreated == '')?null:$this->whenCreated);
		$result ['when_modified'] = ($this->whenModified == null?null:$this->whenModified);

		$result ['eventId'] = $this->eventId;
		$result ['timespan'] = $this->timespan;
		$result ['reminderTime'] = $this->reminderTime;
		$result ['eventStartDate'] = $this->eventStartDate;
//die (print_r ($result));
		return $result;
	}

	function setReminderTime ($timespan, $number)
	{
		$time = $number;
		switch ($timespan)
		{
			case "d":
				$time = $time * 24;
			case "h":
				$time = $time * 60;
			case "m":
				$time = $time * 60;
		}
		$this->reminderTime = $time;
		$this->timespan = $timespan;
		$this->whenToSend = date ('Y-m-d H:i:s', strtotime ($this->eventStartDate) - $this->reminderTime);
	}
}
?>