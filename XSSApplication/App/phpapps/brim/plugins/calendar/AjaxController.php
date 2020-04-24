<?php

require_once ('framework/model/ItemParticipationServices.php');
require_once ('plugins/calendar/model/CalendarServices.php');
require_once ('plugins/calendar/model/CalendarFactory.php');
require_once ('ext/JSON.php');
//require_once ('framework/util/Logger.php');
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
 * @author Barry Nauta - 29 May 2006
 * @package org.brim-project.plugins.calendar
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class AjaxController
{
	var $itemParticipationServices;
	var $calendarServices;
	var $json;
	//var $logger;
	
	function AjaxController ()
	{
		// nothing
		$this->calendarServices = new CalendarServices();
		$this->itemParticipationServices = new ItemParticipationServices();
		$this->json = new Services_JSON();
//		$this->logger = new Logger ('/tmp/x1');
	}

	
	function deleteParticipator ($args)
	{
		$participator = $args ['participator'];
		$itemId = $args ['itemId'];
		
		if ($this->itemParticipationServices->getItemOwner ($itemId, 'calendar') 
			!= $_SESSION['brimUsername'])
		{
			die (print_r ("Invalid access"));
		}
		$this->itemParticipationServices->deleteItemParticipation 
			($itemId, $_SESSION['brimUsername'], $participator, 'calendar');
		$participantsStatus = $this->itemParticipationServices->getParticipantsStatus
			($itemId, 'calendar');
		return ($this->json->encode ($participantsStatus));
	}
	
	function addParticipator ($args)
	{
		//$this->logger->log ('add '.$args['participator']);
		$participator = $args ['participator'];
		$itemId = $args ['itemId'];
		//return (var_export ($args, true));
		$this->itemParticipationServices->addItemParticipation 
			($itemId, $_SESSION['brimUsername'], $participator, 'calendar');
		$participantsStatus = $this->itemParticipationServices->getParticipantsStatus
			($itemId, 'calendar');
		return ($this->json->encode ($participantsStatus));
	}
	
	function deleteReminder ($args)
	{
		$reminderId = $args['reminderId'];
		$reminder = $this->calendarServices->getReminder ($reminderId);
		//$this->logger->log (var_export ($reminder, true));
		if ($reminder->owner != $_SESSION['brimUsername'])
		{
			die (print_r ("Invalid access"));
		}
		//$reminder = $this->calendarServices->getReminder ($reminderId);
		$eventId = $reminder->eventId;
		$this->calendarServices->deleteReminder ($reminderId);
		$reminderStatus = $this->calendarServices->getReminders ($eventId);
		//$this->logger->log (var_export ($reminderStatus, true));
		return ($this->json->encode ($reminderStatus));
	}
	
	function addReminder ($args)
	{
//		$this->logger->log ("addReminder".var_export ($args, true));
		$eventId = $args['eventId'];
		$calendarFactory = new CalendarFactory ();
		$reminder = $calendarFactory->nullReminder();
		$reminder->owner = $_SESSION['brimUsername'];
		$reminder->eventId = $eventId;
		$now = date ("Y-m-d H:i:s");		
		$reminder->whenCreated = $now;
		
		$reminder->setReminderTime ($args['timespan'], $args['reminderTime']);
//		$this->logger->log ($reminder);
		$this->calendarServices->addReminder ($reminder);
		$reminderStatus = $this->calendarServices->getReminders ($eventId);
		//$this->logger->log (var_export ($reminderStatus, true));
		return ($this->json->encode ($reminderStatus));
	}
}
?>
