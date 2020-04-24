<?php

require_once ('framework/model/ItemFactory.php');
require_once ('plugins/calendar/model/Event.php');
require_once ('plugins/calendar/model/Reminder.php');

/**
 * CalendarFactory
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
class CalendarFactory extends ItemFactory
{
		/**
		 * Default constructor
		 */
		function CalendarFactory ()
		{
			parent::ItemFactory ();
		}

		/**
		 * Returns the type of this specific item
		 * @return string the type of this specific item:
		 * <code>Event</code>
		 */
		function getType ()
		{
			return "Event";
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
				//die (print_r ($_POST));
			$itemId = $this->getFromPost ('itemId', 0);
			$parentId = $this->getFromPost ('parentId', 0);
			$isParent = $this->getFromPost ('isParent', 0);
			$name = $this->getFromPost ('name', null);
			$when_created = $this->getFromPost ('when_created', null);
			$when_modified = $this->getFromPost ('when_modified', null);
			$visibility = $this->getFromPost ('visibility', 'private');
			$deleted = $this->getFromPost ('is_deleted', 0);
			$category = $this->getFromPost ('category', null);
			$description = $this->getFromPost ('description', null);
			$location = $this->getFromPost ('location', null);
			$organizer = $this->getFromPost ('organizer', null);
			$priority = $this->getFromPost ('priority', 3);
			$frequency = $this->getFromPost ('frequency', 'repeat_type_none');
			$eventInterval = $this->getFromPost ('eventInterval', 1);
			$byWhat = $this->getFromPost ('byWhat', null);
			$byWhatValue = $this->getWeeklyRepeatsFromPost ($_POST);
			$eventStartDate = $this->getEventStartDate ();
			if (isset ($_POST['useEndDate']) && $_POST['useEndDate'] == 'false')
			{
			if (isset ($_POST['recurrence']) && $_POST['recurrence'] == 'on'
				 && $_POST['recurringEnding'] == 'noRecurrenceEnding')
				 {
				 	// dont set end date
//	TODO BARRY FIXME. The line below wasn't here before but this
// resolved in a bug wrt recurring weekly events with a duration
// but no end date...
//
// Ok, removed the line again. Including this line has a bug with daily recurring events
// without end-date. They simply didn't show up, since the enddate was the same as startdate
//
// Tested for weekly recurring with duration but no end-date and it seems to work...
//
					//$eventEndDate = $this->getEventEndDate ($eventStartDate);
					$tmpEventEndDate = $this->getEventEndDate ($eventStartDate);
					if ($tmpEventEndDate != $eventStartDate)
					{
						$eventEndDate = $this->getEventEndDate ($eventStartDate);
					}
					
				 }
				 else
				 {
				 	//die (print_r ($_POST));
					$eventEndDate = $this->getEventEndDate ($eventStartDate);
				 }
			}
			else
			{
				$eventEndDate = $this->getEventEndDate ($eventStartDate);
			}

			if ($frequency != 'repeat_type_none')
			{
				if (isset ($_POST['recurringEnding'])
					&& ($_POST['recurringEnding'] == 'noRecurrenceEnding'))
				{
					$eventRecurrenceEndDate = null;
				}
				else
				{
					$eventRecurrenceEndDateYear =
						$_POST['recurringEndYear'];
					$eventRecurrenceEndDateMonth =
						$_POST['recurringEndMonth'];
					$eventRecurrenceEndDateDay =
						$_POST['recurringEndDay'];
					$eventRecurrenceEndDate = mktime (
						0, 0, 0,
						$eventRecurrenceEndDateMonth,
						$eventRecurrenceEndDateDay,
						$eventRecurrenceEndDateYear);
				}
			}
			else
			{
				$eventRecurrenceEndDate = null;
			}
			$eventColour = $this->getFromPost ('eventColour', null);
			$item = new Event
			(
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
					$location,
					$organizer,
					$priority,
					$frequency,
					$eventInterval,
					$byWhat,
					$byWhatValue,
					$eventStartDate,
					$eventEndDate,
					$eventRecurrenceEndDate,
					$eventColour
			);
			//die (print_r ($item));
			return $item;
		}

		function resultsetToItems ($result)
		{
			$items = array ();
			//die (print_r ($result));
			while (!$result->EOF)
			{
				$tmp = $result->fields['event_start_date'];
				if ($tmp != '0000-00-00 00:00:00' &&
					$tmp != '1970-01-01 01:00:00' &&
					$tmp != '1970-01-01 00:00:00')
				{
					$eventStartDate =
						strtotime ($result->fields['event_start_date']);
				}
				else
				{
					$eventStartDate = null;
				}
				$tmp = $result->fields['event_end_date'];
				if ($tmp != '0000-00-00 00:00:00' &&
					$tmp != '1970-01-01 01:00:00' &&
					$tmp != '1970-01-01 00:00:00')
				{
					$eventEndDate =
						strtotime ($result->fields['event_end_date']);
				}
				else
				{
					$eventEndDate = null;
				}
				$tmp = $result->fields['event_recurring_end_date'];
				if ($tmp != '0000-00-00 00:00:00' &&
					$tmp != '1970-01-01 01:00:00' &&
					$tmp != '1970-01-01 00:00:00')
				{
					$recurringEndDate =
						strtotime (
							$result->fields['event_recurring_end_date']);
				}
				else
				{
					$recurringEndDate = null;
				}
				$item = new Event
				(
					$result->fields['item_id'],
					trim ($result->fields['owner']),
					$result->fields['parent_id'],
					$result->fields['is_parent'],
					trim ($result->fields['name']),
					trim ($result->fields['description']),
					trim ($result->fields['visibility']),
					trim ($result->fields['category']),
					$result->fields['is_deleted'],
					$result->fields['when_created'],
					$result->fields['when_modified'],
					trim ($result->fields['location']),
					trim ($result->fields['organizer']),
					trim ($result->fields['priority']),
					trim ($result->fields['frequency']),
					trim ($result->fields['event_interval']),
					trim ($result->fields['by_what']),
					trim ($result->fields['by_what_value']),
					$eventStartDate,
					$eventEndDate,
					$recurringEndDate,
					trim ($result->fields['event_colour'])
				);
				$items [] = $item;
				$result->MoveNext();
			}
			return $items;
		}

		function getWeeklyRepeatsFromPost ($_POST)
		{
				$result = '';
				for ($i=0; $i<7; $i++)
				{
					if (isset ($_POST['repeat_day_weekly_'.$i]))
					{
						$result .= '1';
					}
					else
					{
						$result .= '0';
					}
				}
				return $result;
		}

		function requestToItemErrors ()
		{
			$errors = array ();

			$eventStartDate = $this->getEventStartDate ();
			$eventRecurringEndDate = $this->getRecurringEndDate ();
			$eventEndDate = $this->getEventEndDate ($eventStartDate);

			if (isset ($_POST['frequency']) && $_POST['frequency'] == 'repeat_type_unknown')
			{
				$errors [] =  "invalidRepeatType";
			}
			if (!isset ($_POST['name']) || ($_POST['name'] == ''))
			{
				$errors [] =  "nameMissing";
			}
			if (strtotime($eventStartDate) > strtotime($eventRecurringEndDate))
			{
				$errors [] =  "startDateAfterRecurringEndDate";
			}
			if (strtotime($eventEndDate) > strtotime($eventRecurringEndDate) && date ('Y-m-d', $eventEndDate) != '1970-01-01')
			{
				$errors [] =  "endDateAfterRecurringEndDate";
			}
			if (isset ($_POST['recurrence']) && ($_POST['recurrence'] == 'on'))
			{
				if ($_POST['frequency']=='repeat_type_none')
				{
					$errors[]='recurrenceNoRepeatType';
				}
			}
			$frequency = $this->getFromPost ('frequency', null);
			$eventInterval =
				$this->getWeeklyRepeatsFromPost ($_POST);
			if ($frequency == 'repeat_type_weekly' &&
				$eventInterval == '0000000')
			{
				$errors [] =  "noDayWeeklyRepeat";
			}
			return $errors;
		}

		function getEventStartDate ()
		{
			$eventStartDateYear = $_POST['start_year'];
			$eventStartDateMonth = $_POST['start_month'];
			$eventStartDateDay = $_POST['start_day'];
			$eventStartTimeHour = $_POST['start_time_hours'];
			$eventStartTimeMinutes = $_POST['start_time_minutes'];
			return mktime (
				$eventStartTimeHour,
				$eventStartTimeMinutes,
				0,
				$eventStartDateMonth,
				$eventStartDateDay,
				$eventStartDateYear);
		}

		function getRecurringEndDate ()
		{
			$eventRecurringEndDateYear = $_POST['recurringEndYear'];
			$eventRecurringEndDateMonth = $_POST['recurringEndMonth'];
			$eventRecurringEndDateDay = $_POST['recurringEndDay'];
			return mktime (
				0, 0, 0,
				$eventRecurringEndDateMonth,
				$eventRecurringEndDateDay,
				$eventRecurringEndDateYear);
		}

		function getEventEndDate ($eventStartDate)
		{
			//
			// Check if duration is set
			if (isset ($_POST['durationHours']) ||
				isset ($_POST['durationMinutes']))
			{
				if (
					$_POST['durationHours'] != '0' ||
					$_POST['durationMinutes'] != '0' ||
					$_POST['durationHours'] != '00' ||
					$_POST['durationMinutes'] != '00'
				)
				{
					$eventEndDate = strtotime
						('+'.$_POST['durationHours'].' hours',
							$eventStartDate);
					$eventEndDate = strtotime
						('+'.$_POST['durationMinutes'].' minutes',
						$eventEndDate);
				}
				else
				{
					if (isset ($_POST['useEndDate'])
						&& ($_POST['useEndDate'] == 'false'))
					{
						$eventEndDate = $eventStartDate;
					}
					else
					{
				$eventEndDateYear = $_POST['end_year'];
				$eventEndDateMonth = $_POST['end_month'];
				$eventEndDateDay = $_POST['end_day'];
				$eventEndTimeHour = $_POST['end_time_hours'];
				$eventEndTimeMinutes = $_POST['end_time_minutes'];
				$eventEndDate = mktime (
					$eventEndTimeHour,
					$eventEndTimeMinutes,
					0,
					$eventEndDateMonth,
					$eventEndDateDay,
					$eventEndDateYear);
					}
				}
			}
			else if (isset ($_POST['useEndDate']) && !$_POST['useEndDate'])
			{
				$eventEndDate = $eventStartDate;
			}
			else
			{
				$eventEndDateYear = $_POST['end_year'];
				$eventEndDateMonth = $_POST['end_month'];
				$eventEndDateDay = $_POST['end_day'];
				$eventEndTimeHour = $_POST['end_time_hours'];
				$eventEndTimeMinutes = $_POST['end_time_minutes'];
				$eventEndDate = mktime (
					$eventEndTimeHour,
					$eventEndTimeMinutes,
					0,
					$eventEndDateMonth,
					$eventEndDateDay,
					$eventEndDateYear);
			}
			//die (print_r (date('Y-m-d H:i', $eventEndDate)));
			return $eventEndDate;
		}

		function requestToReminder ()
		{
			$itemId = $this->getFromPost ('itemId', 0);
			$parentId = $this->getFromPost ('parentId', 0);
			$isParent = $this->getFromPost ('isParent', 0);
			$name = $this->getFromPost ('name', null);
			$when_created = $this->getFromPost ('when_created', null);
			$when_modified = $this->getFromPost ('when_modified', null);
			$visibility = $this->getFromPost ('visibility', 'private');
			$deleted = $this->getFromPost ('is_deleted', 0);
			$category = $this->getFromPost ('category', null);
			$description = $this->getFromPost ('description', null);
			$eventId = $this->getFromPost ('eventId', 0);
			$timespan = $this->getFromPost ('timespan', 'm');
			$whenSent = $this->getFromPost ('whenSent', null);
			$eventStartTime = $this->getFromPost ('eventStartTime', null);
//die (print_r ($_POST));
			switch ($timespan)
			{
				case"d":
					$reminderTime = $this->getFromPost ('reminderDays', '-1');
					$reminderTime = $reminderTime * 24 * 60 * 60;
					break;
				case"h":
					$reminderTime = $this->getFromPost ('reminderHours', '-1');
					$reminderTime = $reminderTime * 60 * 60;
					break;
				case "m":
					$reminderTime = $this->getFromPost ('reminderMinutes', '-1');
					$reminderTime = $reminderTime * 60;
					break;

			}
			$reminder = new Reminder
				(
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
					$eventId,
					$timespan,
					$reminderTime,
					$whenSent,
					$eventStartTime
				);
			return $reminder;
		}

		function resultSetToReminders ($resultSet)
		{
			$result = array ();
			while (!$resultSet->EOF)
			{
				$reminder = new Reminder
				(
					$resultSet->fields['item_id'],
					$resultSet->fields['owner'],
					$resultSet->fields['parent_id'],
					$resultSet->fields['is_parent'],
					$resultSet->fields['name'],
					$resultSet->fields['description'],
					$resultSet->fields['visibility'],
					$resultSet->fields['category'],
					$resultSet->fields['is_deleted'],
					$resultSet->fields['when_created'],
					$resultSet->fields['when_modified'],
					$resultSet->fields['event_id'],
					$resultSet->fields['timespan'],
					$resultSet->fields['reminder_time'],
					$resultSet->fields['when_sent'],
					$resultSet->fields['event_start_date']
					);
				$result [] = $reminder;
				$resultSet->MoveNext ();
			}
			//die (print_r ($resultSet));
			return $result;
		}

		function resultSetToReminder ($resultSet)
		{
			$reminders = $this->resultSetToReminders($resultSet);
			return $reminders[0];
		}
		
		function nullReminder ()
		{
			return new Reminder (0, null, 0, false, '', null, 'private', null, false, null, null, 0, 0, 0, null, null);
		}
		
}
?>