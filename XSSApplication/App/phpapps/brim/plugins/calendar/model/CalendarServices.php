<?php

require_once ('framework/util/ArrayUtils.php');
require_once ('framework/model/Services.php');
require_once ('plugins/calendar/model/CalendarFactory.php');

/**
 * Calendar class
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - December 2003
 * @package org.brim-project.plugins.calendar
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class CalendarServices extends Services
{

	var $arrayUtils;
	
	/**
	 * Default constructot
	 */
    function CalendarServices()
    {
	    parent::Services();
		$this->itemFactory = new CalendarFactory ();
		$this->arrayUtils = new ArrayUtils();
		$queries = array ();
		include ('plugins/calendar/sql/calendarQueries.php');
		$this->queries = $queries;
    }


	/**
	 * Get the days for the requested month in an array
	 *
	 * @param integer month the requested month, 1 for Jan.
	 * @param integer year the requested year
	 * @return integer 0 (for Sunday) through 6 (for Saturday)
	 */
	function getFirstDayInMonth ($date)
	{
    	$date = getdate(mktime(12, 0, 0,
			date ('m', $date), 1, date ('y', $date)));
    	$first = $date["wday"];
    	return $first;
	}

	/**
	 * Returns the number of days in the specified month/year
	 * combination. The year parameter is used for leap-year
	 * calculation
	 *
	 * @param integer month the requested month, 1 for Jan.
	 * @param integer year the requested year
	 * @return integer the number of days in the requested month
	 */
	function getNumberOfDaysInMonth ($date)
	{
		return date ('t', $date);
	}


	/**
	 * @param startDayOfWeek 0 for sunday 1 for monday
	 */
	function getMonthsAsArray ($userId, $date, $startDayOfWeek)
	{
		if (gettype ($date) == 'string')
		{
			die ('Unexpected input in getMonthAsArray');
		}
		$year = date ('Y', $date);
		$result = array ();
		for ($i=1; $i<=12; $i++)
		{
			$date= mktime (0, 0, 0, $i, 1, $year);

			$result [] = $this->getDaysInMonthAsArrayPerWeek
				($userId, $date, true, $startDayOfWeek);
		}
		return $result;
	}

	/**
	 * @param userId string the identifier (loginname) for the user
	 * @param date integer the internal php date representation
	 * @param requestValues boolean whether we would like to have the
	 * values for the specific dates retrieved from the database
	 * @param startDayOfWeek 0 for sunday 1 for monday
	 * @return array TBD FIXME
	 */
	function getDaysInMonthAsArrayPerWeek 
			($userId, $date, $requestValues, $startDayOfWeek)
	{
		if (gettype ($date) == 'string')
		{
			die('Unexpected input getDaysInMonthAsArrayPerWeek:'.$date);
		}

		//
		// 0 for sunday, 6 for saturday
		//
		$firstDay = $this->getFirstDayInMonth ($date);
		$numberOfDaysInMonth = $this->getNumberOfDaysInMonth ($date);
		$m = date ('m', $date);
		$y = date ('y', $date);
		// max. 6 (incomplete) weeks in one month
		$weeks = array ();
		for ($dayCount=1, $weekCount=0;
			$dayCount<=$numberOfDaysInMonth;
			$dayCount++)
		{
			//
			// Shortcut. Fool the date'W' function by taking one day
			// further if the startDay of the week is sunday
			//
			//die (print_r ($startDayOfWeek));
			if ($startDayOfWeek == 0)
			{
				$currentDay = mktime (0, 0, 0, $m,
					$dayCount + 1, $y);
			}
			else
			{
				$currentDay = mktime (0, 0, 0, $m,
					$dayCount, $y);
			}
			//
			// The number of the actual week (between 1 and 53)
			//
			$weekCount2 = date ('W', $currentDay);
			//
			// Reset the day to its actual value so we can
			// get its events
			//
			$currentDay = mktime (0, 0, 0, $m, $dayCount, $y);
			//
			// Do we want the events for each day as well?
			//
			if ($requestValues)
			{
				$day = array ('day'=>$dayCount, 'events'=>
					$this->getEvents ($userId, $currentDay));
			}
			else
			{
				$day = array ('day'=>$dayCount, 'events'=>null);
			}
			$weeks[$weekCount2][]=$day;
		}
    	return $weeks;
	}

	/**
	 * Adds an event to the calendar plugins
	 *
	 * @param string userId the identifier for the user
	 * @param object item the event itself
	 */
	function addItem ($userId, $item)
	{
			$recurringEndDate = null;
			$eventStartDate = ($item->eventStartDate)?
				date ("Y-m-d H:i", $item->eventStartDate):
					date ('Y-m-d 00:00:00');
			$eventEndDate = ($item->eventEndDate) ?
				date ("Y-m-d H:i", $item->eventEndDate):
					"1970-01-01 00:00:00";
			if ($item->eventRecurringEndDate != -1)
			{
				 $recurringEndDate =
				 	($item->eventRecurringEndDate) ?
						date ("Y-m-d H:i", $item->eventRecurringEndDate):
							"1970-01-01 00:00:00";

			}
			$now = date  ("Y-m-d H:i:s");
			$query = sprintf ($this->queries['addItem'],
				$userId,
				addslashes ($item->parentId),
				addslashes ($item->isParent),
				addslashes ($item->name),
				addslashes ($item->description),
				addslashes ($item->visibility),
				addslashes ($item->category),
				addslashes ($now),
				addslashes ($item->location),
				addslashes ($item->organizer),
				addslashes ($item->priority),
				addslashes ($item->frequency),
				addslashes ($item->eventInterval),
				addslashes ($item->byWhat),
				addslashes ($item->byWhatValue),
				$eventStartDate,
				$eventEndDate,
				$recurringEndDate,
				$item->eventColour);
			$this->db -> Execute ($query) or die ($this->db->ErrorMsg ().$query);

			$query = $this->queries['lastItemInsertId'];
			$result=$this->db->Execute($query)
				or die ('AddEvent, could not execute lastItemId: '.
					$query.'->'.$this->db->ErrorMsg ());
			return $result->fields[0];
	}

	/**
	 * Modifies an item
	 *
	 * @param string $userId
	 * @param object $item
	 */
	function modifyItem ($userId, $item)
	{
			$eventStartDate = ($item->eventStartDate)?
				date ("Y-m-d H:i", $item->eventStartDate):
					date ('Y-m-d 00:00:00');
			$eventEndDate = ($item->eventEndDate) ?
				date ("Y-m-d H:i", $item->eventEndDate):
					"1970-01-01 00:00:00";
			if ($item->eventRecurringEndDate != -1)
			{
				 $recurringEndDate =
				 	($item->eventRecurringEndDate) ?
						date ("Y-m-d H:i", $item->eventRecurringEndDate):
							"1970-01-01 00:00:00";

			}
			$now = date  ("Y-m-d H:i:s");
			$now = date  ("Y-m-d H:i:s");
			$query = sprintf ($this->queries['modifyItem'],
				addslashes ($item->parentId),
				addslashes ($item->name),
				addslashes ($item->description),
				addslashes ($item->visibility),
				addslashes ($item->category),
				addslashes ($item->isDeleted),
				addslashes ($now),
				addslashes ($item->location),
				addslashes ($item->organizer),
				$item->priority,
				addslashes ($item->frequency),
				$item->eventInterval,
				addslashes ($item->byWhat),
				addslashes ($item->byWhatValue),
				$eventStartDate,
				$eventEndDate,
				$recurringEndDate,
				$item->eventColour,
				$item->itemId);
			$this->db -> Execute ($query) or die ($this->db->ErrorMsg ());
	}


	/**
	 * @param date string the date in string representation
	 * TODO BARRY FIXME
	 * 
	 * This function is called for each date that is being displayed
	 * which may lead to quite some queries to the database when
	 * viewing a month..... We could imagine some serious optimization
	 * here....
	 */
	function getEvents ($userId, $date)
	{
		if (!is_integer($date))
		{
			die ('getEvents did not receive the date as integer!');
		}
		$result = array ();

		$participation = isset ($_SESSION['calendarParticipation']);
		
		$result = array_merge ($result,
			$this->getYearlyEvents ($userId, $date, $participation));
		$result = array_merge ($result,
			$this->getMonthlyEvents ($userId, $date, $participation));
		$result = array_merge ($result,
			$this->getWeeklyEvents ($userId, $date, $participation));
		$result = array_merge ($result,
			$this->getDailyEvents ($userId, $date, $participation));
		$result = array_merge ($result,
			$this->getDayEvents ($userId, $date, $participation));
		//
		// We might find doubles (events that show up in the weekly
		// but also in the monthly)
		//
		$result = $this->arrayUtils->uniqueArray ($result);
		return $result;
	}



	/**
	 * @param string userid
	 * @param date date
	 * @param boolean participation; flag that indicates whether we should
	 * retrieve the events in which we participate as well...
	 * @return array
	 */
	function getYearlyEvents ($userId, $date, $participation)
	{
		$day = date ('d', $date);
		$month = date ('m', $date);
		$startOfDay = mktime (0, 0, 0,
			date ('m', $date),
			date ('d', $date),
			date ('y', $date));
		if ($participation)
		{
			$query = sprintf ($this->queries['getYearlyEventsWithParticipation'],
				$userId, date ("Y-m-d H:i:s", $startOfDay), $month, $day,
					date ('Y-m-d H:i:s', $startOfDay), $userId);
			$rs = $this->db->Execute ($query) or die ($this->db->ErrorMsg().'---> '.$query);
			$events =  $this->itemFactory->resultSetToItems($rs);
		}
		else 
		{
			$events = array ();
		}
	
		$query = sprintf ($this->queries['getYearlyEvents'],
			$userId, date ("Y-m-d H:i:s", $startOfDay), $month, $day,
				date ('Y-m-d H:i:s', $startOfDay));
		$rs = $this->db->Execute ($query) or die ($this->db->ErrorMsg().'---> '.$query);
		$events =  $events + $this->itemFactory->resultSetToItems($rs);
		for ($i=0; $i<count ($events); $i++)
		{
			$event = $events[$i];
			$reminders = $this->getReminders($event->itemId);
			$event->setReminders ($reminders);
			$events[$i]=$event;
		}
		return $events;
	}

	/**
	 * Enter description here...
	 *
	 * @param string $userId
	 * @param date $date
	 * @param boolean participation; flag that indicates whether we should
	 * retrieve the events in which we participate as well...
	 * @return array
	 */
	function getMonthlyEvents ($userId, $date, $participation)
	{
		// What about monthly events that span multiple days?
		// This function doesn't take that into account!!
		// On the other hand... Should it?
		$day = date ('d', $date);
		$startOfDay = mktime (0, 0, 0,
			date ('m', $date),
			date ('d', $date),
			date ('y', $date));
		$endOfDay = $startOfDay + (60*60*24) -1;
		if ($participation)
		{		
			$query = sprintf ($this->queries['getMonthlyEventsWithParticipation'],
				$userId,
				date ("Y-m-d H:i:s", $endOfDay),
				date ("Y-m-d H:i:s", $startOfDay),
				$day,
				$userId);
			$rs = $this->db->Execute ($query) or die ($this->db->ErrorMsg().' '.$query);
			$events =  $this->itemFactory->resultSetToItems($rs);
		}
		else 
		{
			$events = array ();
		}
		$query = sprintf ($this->queries['getMonthlyEvents'],
			$userId,
			date ("Y-m-d H:i:s", $endOfDay),
			date ("Y-m-d H:i:s", $startOfDay),
			$day);
		$rs = $this->db->Execute ($query) or die ($this->db->ErrorMsg().' '.$query);
		$events =  $events + $this->itemFactory->resultSetToItems($rs);
		for ($i=0; $i<count ($events); $i++)
		{
			$event = $events[$i];
			$reminders = $this->getReminders($event->itemId);
			$event->setReminders ($reminders);
			$events[$i]=$event;
		}
		return $events;
	}

	
	function getWeeklyEvents ($userId, $date, $participation)
	{
		$currentWeekDay = date ('w', $date);
		$day = '';
		for ($i=0; $i<$currentWeekDay; $i++)
		{
			$day .= '_';
		}
		$day .= '1';
		for ($j=6; $j>$currentWeekDay; $j--)
		{
			$day .= '_';
		}
		$startOfDay = mktime (0, 0, 0,
			date ('m', $date),
			date ('d', $date),
			date ('y', $date));
		$endOfDay = $startOfDay + (60*60*24) -1;
		//
		// For the weekly events, take the endOfDay twice (instead
		// of startOfDay). Otherwise, events starting on that specific
		// day, but at a later time than 00:00 will not be counted
		//
		if ($participation)
		{
			$query = sprintf ($this->queries['getWeeklyEventsWithParticipation'],
				$userId,
				date ("Y-m-d H:i:s", $endOfDay),
				date ("Y-m-d H:i:s", $endOfDay),
				$day,
				$userId);
			$rs = $this->db->Execute ($query) or die ($this->db->ErrorMsg().' '.$query);
			$events =  $this->itemFactory->resultSetToItems($rs);
		}
		else 
		{
			$events = array ();
		}
		$query = sprintf ($this->queries['getWeeklyEvents'],
			$userId,
			date ("Y-m-d H:i:s", $endOfDay),
			date ("Y-m-d H:i:s", $endOfDay),
			$day);
		$rs = $this->db->Execute ($query) or die ($this->db->ErrorMsg().' '.$query);
		$events = $events + $this->itemFactory->resultSetToItems($rs);
		for ($i=0; $i<count ($events); $i++)
		{
			$event = $events[$i];
			$reminders = $this->getReminders($event->itemId);
			$event->setReminders ($reminders);
			$events[$i]=$event;
		}
		return $events;
	}


	/**
	 * Enter description here...
	 *
	 * @param string $userId
	 * @param date date
	 * @param boolean participation; flag that indicates whether we should
	 * retrieve the events in which we participate as well...
 	 * @return array
	 */
	function getDailyEvents ($userId, $date, $participation)
	{
		$yesterday = date ("U", ($date - (60*60*24)));
		$startOfDay = mktime (0, 0, 0,
			date ('m', $date),
			date ('d', $date),
			date ('y', $date));
		//$tomorrow = $date + (60*60*24);
		$endOfDay = $startOfDay + (60*60*24) -1;
		if ($participation)
		{
			$query = sprintf ($this->queries['getDailyEventsWithParticipation'],
				$userId,
				date ("Y-m-d H:i:s", $endOfDay),
				date ("Y-m-d H:i:s", $startOfDay),
				$userId);
			$rs = $this->db->Execute ($query) or die ($this->db->ErrorMsg().' '.$query);
			$events =  $this->itemFactory->resultSetToItems($rs);
		}
		else 
		{
			$events = array ();
		}
		$query = sprintf ($this->queries['getDailyEvents'],
			$userId,
			date ("Y-m-d H:i:s", $endOfDay),
			date ("Y-m-d H:i:s", $startOfDay));
		$rs = $this->db->Execute ($query) or die ($this->db->ErrorMsg().' '.$query);
		$events =  $events + $this->itemFactory->resultSetToItems($rs);
		for ($i=0; $i<count ($events); $i++)
		{
			$event = $events[$i];
			$reminders = $this->getReminders($event->itemId);
			$event->setReminders ($reminders);
			$events[$i]=$event;
		}
		return $events;
	}

	/**
	 * Enter description here...
	 *
	 * @param string $userId
	 * @param date date
	 * @param boolean participation; flag that indicates whether we should
	 * retrieve the events in which we participate as well...
 	 * @return array
	 */
	function getDayEvents ($userId, $date, $participation)
	{
		$yesterday = date ("U", ($date - (60*60*24)));
		$startOfDay = mktime (0, 0, 0,
			date ('m', $date),
			date ('d', $date),
			date ('y', $date));
		$endOfDay = $startOfDay + (60*60*24) -1;
		$particip = array ();
		if ($participation)
		{
			$particip = $this->getDayEventsWithParticipation($userId, $date);
		}
		$query = sprintf ($this->queries['getEvents'],
			$userId,
			date ("Y-m-d H:i:s", $endOfDay),
			date ("Y-m-d H:i:s", $startOfDay),
			date ("Y-m-d H:i:s", $endOfDay),
			date ("Y-m-d H:i:s", $startOfDay));
		$rs = $this->db->Execute ($query) or die ($this->db->ErrorMsg().' '.$query);
		$events =  $this->itemFactory->resultSetToItems($rs);
		for ($i=0; $i<count ($events); $i++)
		{
			$event = $events[$i];
			$reminders = $this->getReminders($event->itemId);
			$event->setReminders ($reminders);
			$events[$i]=$event;
		}
		$events = array_merge($particip, $events);
		return $events;
	}

	function getDayEventsWithParticipation ($userId, $date)
	{
		$yesterday = date ("U", ($date - (60*60*24)));
		$startOfDay = mktime (0, 0, 0,
			date ('m', $date),
			date ('d', $date),
			date ('y', $date));
		$endOfDay = $startOfDay + (60*60*24) -1;
		$query = sprintf ($this->queries['getEventsWithParticipation'],
			date ("Y-m-d H:i:s", $endOfDay),
			date ("Y-m-d H:i:s", $startOfDay),
			date ("Y-m-d H:i:s", $endOfDay),
			date ("Y-m-d H:i:s", $startOfDay),
			$userId);
		$rs = $this->db->Execute ($query) or die ($this->db->ErrorMsg().' '.$query);
//		die (print_r ($query));
		$events =  $this->itemFactory->resultSetToItems($rs);
		for ($i=0; $i<count ($events); $i++)
		{
			$event = $events[$i];
			$reminders = $this->getReminders($event->itemId);
			$event->setReminders ($reminders);
			$events[$i]=$event;
		}
		return $events;
	}
	
	function getFirstDayOfWeek ($date, $startDayOfWeek)
	{
		if ($startDayOfWeek == 1)
		{
			$weekday =  (date('w', $date) + 6) % 7;
		}
		else
		{
			$weekday = date ('w', $date);
		}
		return ($date - ($weekday * 24 * 60 * 60));
	}

	function getWeekForDay ($userId, $date, $startDayOfWeek)
	{
			$days = array ();
			$result = array ();

			if ($startDayOfWeek == 1)
			{
				$weekday =  (date('w', $date) + 6) % 7;
			}
			else
			{
				$weekday = date ('w', $date);
			}
			$before = $weekday;
			$after = 6-$weekday;

			for ($i=$before; $i>0; $i--)
			{
					$days [] = mktime(0,0,0, 
						date('m', $date),(date('d', $date)-$i),date('Y', $date));
			}
			$days [] = $date;
			for ($j=1; $j<=$after; $j++)
			{
					$days [] = mktime(0,0,0, 
						date('m', $date),(date('d', $date)+$j),date('Y', $date));
			}
			for ($i=0; $i<count($days); $i++)
			{
				$result[] = $this->getEvents ($userId, $days[$i]);
			}
			return $result;
	}

	function addReminder ($reminder)
	{
		if (!$reminder->isValid ())
		{
			return;
		}
		$reminderAsArray = $reminder->toArray ();
		unset ($reminderAsArray ['itemId']);
		unset ($reminderAsArray ['when_modified']);
		unset ($reminderAsArray ['eventStartDate']);
		unset ($reminderAsArray ['parameters']);
		$reminderAsArray ['when_created'] = date ('Y-m-d H:i:s');
		$query = vsprintf ($this->queries['addReminder'],
			$reminderAsArray);
			//require_once ('framework/util/Logger.php');
			//$logger = new Logger ('/tmp/x1');
			//$logger->log ($query);
		$this->db -> Execute ($query) or die ($this->db->ErrorMsg ().$query);

		$query = $this->queries['lastReminderInsertId'];
		$result=$this->db->Execute($query)
				or die ('AddReminder, could not execute lastItemId: '.$query.'->'.$this->db->ErrorMsg ());
		return $result->fields[0];
	}

	function getReminders ($eventId)
	{
		$result = array ();
		$query = sprintf ($this->queries['getReminders'], $eventId);
		$resultSet = $this->db -> Execute ($query) or die ($this->db->ErrorMsg ().$query);
		return $this->itemFactory->resultSetToReminders ($resultSet);
	}

	function getAllReminders ()
	{
		$result = array ();
		$query = sprintf ($this->queries['getAllReminders']);
		$resultSet = $this->db -> Execute ($query) or die ($this->db->ErrorMsg ().$query);
		return $this->itemFactory->resultSetToReminders ($resultSet);
	}

	function deleteReminder ($itemId)
	{
		$query = sprintf ($this->queries['getReminder'], $itemId);
		$resultSet = $this->db -> Execute ($query) or die ($this->db->ErrorMsg ().$query);
		$reminder = $this->itemFactory->resultSetToReminder ($resultSet);
		if ($reminder->owner != $_SESSION['brimUsername'])
		{
			die ('You are not allowed to delete items you do not own');
		}
		$query = sprintf ($this->queries['deleteReminder'], $itemId);
		$this->db -> Execute ($query) or die ($this->db->ErrorMsg ().$query);
	}

	function reminderSent ($reminderId)
	{
		$now = date ('Y-m-d H:i:s');
		$query = sprintf ($this->queries['reminderSent'], $now, $reminderId);
		$this->db -> Execute ($query) or die ($this->db->ErrorMsg ().$query);
	}

	function getEventForId ($eventId)
	{
		$query = sprintf ($this->queries['getEventForId'], $eventId);
		$resultSet = $this->db -> Execute ($query) or die ($this->db->ErrorMsg ().$query);
		$events = $this->itemFactory->resultSetToItems ($resultSet);
		return $events[0];
	}


	function sendReminder ($reminder, $userSettings)
	{
		//die (print_r ($userSettings));
		$event = $this->getEventForId ($reminder->eventId);
		if (isset ($userSettings->name))
		{
			$to = $userSettings->name;
		}
		else
		{
			$to = $userSettings->loginName;
		}
		$header .= "To: ".$to." <".$userSettings->email.">\r\n";
		$header .= "X-Mailer: Brim";
		$start = date('m-d H:i', $event->eventStartDate);
		$end = date('m-d H:i', $event->eventEndDate);
		$timeIndication = '['.$start.' - '.$end.'] ';
		$subject .= $timeIndication.$event->name;
		if (isset ($event->location) && ($event->location != ''))
		{
			$subject .= '@'.$event->location;
		}
		
		$link = 'http://'.$_SERVER['SERVER_NAME'];
		if (isset ($_SERVER['PORT']) && ($_SERVER['PORT'] != '80'))
		{
			//
			// Remove trailing slash
			//
			$link = substr ($link, 0, strlen ($link) -1);
			$link .= ':'.$_SERVER['PORT'].'/';
		}
		$link .= substr ($_SERVER['PHP_SELF'], 0, 
			(strlen ($_SERVER['PHP_SELF'])-strlen ('brim.php')));
		$link .= 'index.php?plugin=calendar';
		$link .= '&amp;action=modify&amp;itemId='.$event->itemId;
		

		$message = $subject.'
'.$event->description.'

'.$link;

		return (mail ($userSettings->email, $subject, $message, $header));
	}

	function shouldSendReminder ($event, $reminder)
	{
		$now = date ('Y-m-d H:i:s');
		//
		// No need to send if a reminder has already been sent.
		// This does not work for non-recurring events yet
		//
		if (isset ($reminder->whenSent))
		{
			return false;
		}
		//
		// We assume a 5 minute check interval.
		// So check if the reminder time + 5 minutes is after now
		//
		$rem = ($reminder->reminderTime) + (5 * 60);
		$whenToSend = $event->eventStartDate - $rem;
		return $whenToSend < strtotime ($now);
	}

	function deleteItem ($userId, $itemId)
	{
		parent::deleteItem($userId, $itemId);
		$this->deleteRemindersForEventId ($userId, $itemId);
	}

	function deleteRemindersForEventId ($userId, $eventId)
	{
		$query = sprintf ($this->queries['deleteRemindersForEventId'],
				$eventId, $userId);
		$result = $this->db->Execute($query) or
			die("DeleteItem: " .
				$this->db->ErrorMsg() . " " . $query);
	}

	function getReminder ($reminderId)
	{
		$query = sprintf ($this->queries['getReminder'], $reminderId);
		$resultSet = $this->db -> Execute ($query) or die ($this->db->ErrorMsg ().$query);
		$reminder = $this->itemFactory->resultSetToReminder ($resultSet);
		if ($reminder->owner != $_SESSION['brimUsername'])
		{
			die ('You are not allowed to delete items you do not own');
		}
		return $reminder;
	}
}
?>
