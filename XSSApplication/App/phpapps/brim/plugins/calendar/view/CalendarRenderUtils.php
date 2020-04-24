<?php

require_once ('framework/util/StringUtils.php');

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.calendar
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class CalendarRenderUtils
{
	/**
	 * The dictionary
	 */
	var $dictionary;

	/**
	 * The configuration
	 */
	var $configuration;

	/**
	 * String utilities
	 */
	var $stringUtils;

	/**
	 * Standard constructor
	 * @param dictionary array the dictionary
	 */
	function CalendarRenderUtils ($theConfiguration)
	{
		$this->configuration = $theConfiguration;
		$this->dictionary = $theConfiguration['dictionary'];
		$this->stringUtils = new StringUtils ();
	}

	function currentDayHeader ($day, $month, $year)
	{
		$resultString = ($this->dictionary['month'.$month]);
		$resultString .= '&nbsp;'.$year;
		$resultString .= ',&nbsp;'.$day;
		return $resultString;
	}

	function currentMonthHeader ($month, $year)
	{

		$resultString = '';
		$resultString .= '<span class="month_header">';
		$resultString .= '<a href="'.$this->configuration['callback'];
		$resultString .= '&amp;action=showMonth';
		$resultString .= '&month='.$month;
		$resultString .= '&year='.$year;
		$resultString .= '" ';
		$resultString .= '>';
		$resultString .= $this->dictionary['month'.$month];
		$resultString .= '<br />'.$year;
		$resultString .= '</a>';
		$resultString .= '</span>
		';
		return $resultString;
	}

	/**
	 * Displays a 'big' table showing a month, with the weeknumbers
	 * indicated before the weeks and the name of the events
	 * per day. This is the main view in the 'month' view
	 *
	 * @param month integer the month we are displaying. A number
	 * 		 between one and 12
	 * @param year integer the year we are displaying
	 * @param data array the data, this is an array of events
	 * @param startDayOfWeek integer 0 for sunday, 1 for monday
	 *
	 * @return string a string containing the html output for the
	 *         table
	 * @todo this function contains quite some copy-and-pasted code
	 * 		 and these should be embedded in a generic function
	 */
	function displayBigTable ($month, $year, $data ,$startDayOfWeek)
	{
		$today = date ('Y-m-d');
		$resultString = '
		<table class="calendar_view_month">';

		//
		// First show the names of the days as table headers
		//
		$resultString .= '<tr>';
		$resultString .= '<td>&nbsp;</td>';
		for ($i=$startDayOfWeek; $i<7+$startDayOfWeek; $i++)
		{
			$name = 'day'.(($i+7)%7);
			$resultString .= '
				<th  class="calendar_view_month_weekdayname">';
			$resultString .= $this->dictionary[$name];
			$resultString .= '</th>';
		}
		$resultString .= '</tr>';

		$w=0;
		foreach ($data as $week)
		{
			$weekNumbers = array_keys ($data);
			$numOfDaysInWeek = count($week);
			//
			// First week
			//
			$firstWeekInMonth = $weekNumbers[0];

			$resultString .= '<tr>';
			$resultString .= '
				<td class="calendar_view_month_weeknumber">
				<a href="'.$this->configuration['callback'];
			$resultString .= '&amp;action=showWeek';
			$resultString .= '&day='.$week[count($week)-1]['day'];
			$resultString .= '&month='.$month;
			$resultString .= '&year='.$year;
			$resultString .= '">'.$weekNumbers[$w++].'</a></td>';
			//
			// First week
			//
			if ($week == $data[$firstWeekInMonth])
			{
				//
				// Empty... the first days of this week are in the
				// previous month
				//
				for ($i=0; $i<(7-$numOfDaysInWeek); $i++)
				{
					$resultString .= '
					<td class="calendar_view_month_daynumber">';
					$resultString .= '&nbsp;';
					$resultString .= '</td>';
				}
				//
				// Now display the rest of the days (the ones that are
				// in this week)
				//
				for ($j=0; $j<$numOfDaysInWeek; $j++)
				{
					$currentDate = mktime (0, 0, 0,
						$month, $week[$j]['day'], $year);
					if (date ('Y-m-d', $currentDate) == $today)
					{
							$resultString .= '
							<td class="calendar_view_month_daynumber_today">';
					}
					else
					{
						$resultString .= '
							<td class="calendar_view_month_daynumber">';
					}
					$resultString .= '<table><tr><td align="left">';
					$resultString .= '<a href="'.$this->configuration['callback'];
					$resultString .= '&amp;action=showDay';
					$resultString .= '&day='.$week[$j]['day'];
					$resultString .= '&month='.$month;
					$resultString .= '&year='.$year;
					$resultString .= '">';
					$resultString .= $week[$j]['day'];
					$resultString .= '</a>';
					$resultString .= '</td><td align="right">';
					$resultString .= '<a href="'.$this->configuration['callback'].'&amp;action=add';
					$resultString .= '&day='.$week[$j]['day'];
					$resultString .= '&month='.$month;
					$resultString .= '&year='.$year;
					$resultString .= '">';
					// TODO TBD BARRY FIXME
					$resultString .= '<img border="0" src="plugins/calendar/view/pics/new.gif">';
					$resultString .= '</a>';
					$resultString .= '</td></tr></table>';
					//
					// If this specific day has events, display
					// them as well
					//
					if (count ($week[$j]['events']) > 0)
					{
						//
						// Loop over all events
						//
						foreach ($week[$j]['events'] as $event)
						{
							$resultString .= '<li>';
							// TBD TODO FIXME BARRY CHANGE TO show
							$resultString .= '
								<a href="'.$this->configuration['callback'];
							$resultString .= '&amp;action=modify';
							$resultString .= '&amp;itemId='.$event->itemId;
							$resultString .= '" ';
							if ($this->configuration['overlib'])
							{
								$resultString .= $this->overlib
									($event,
									mktime (0, 0, 0, $month, $week[$j]['day'], $year));
							}
							$resultString .= '>';
							//$resultString .= $this->stringUtils->gpcAddSlashes ($event->name);
							$resultString .= $this->renderEventName ($event);
							$resultString .= '</a>';
							$resultString .= '</li>';
						}
					}
					$resultString .= '</td>';
				}
			}
			else
			{
				//
				// Full week...
				//
				for ($j=0; $j<$numOfDaysInWeek; $j++)
				{
					$currentDate = mktime (0, 0, 0,
						$month, $week[$j]['day'], $year);
					if (date ('Y-m-d', $currentDate) == $today)
					{
							$resultString .= '
							<td class="calendar_view_month_daynumber_today">';
					}
					else
					{
						$resultString .= '
							<td class="calendar_view_month_daynumber">';
					}
					$resultString .= '<table><tr><td>';
					$resultString .= '<a href="'.$this->configuration['callback'];
					$resultString .= '&amp;action=showDay';
					$resultString .= '&day='.$week[$j]['day'];
					$resultString .= '&month='.$month;
					$resultString .= '&year='.$year;
					$resultString .= '">';
					$resultString .= $week[$j]['day'];
					$resultString .= '</a>';
					$resultString .= '</td><td align="right">';
					$resultString .= '<a href="'.$this->configuration['callback'].'&amp;action=add';
					$resultString .= '&day='.$week[$j]['day'];
					$resultString .= '&month='.$month;
					$resultString .= '&year='.$year;
					$resultString .= '">';
					// TODO TBD BARRY FIXME
					$resultString .= '<img border="0" src="plugins/calendar/view/pics/new.gif">';
					$resultString .= '</a>';
					$resultString .= '</td></tr></table>';
					if (isset ($week[$j]['events']))
					{
						//
						// Loop over all events
						//
						foreach ($week[$j]['events'] as $event)
						{
							$resultString .= '<li>';
							// TBD TODO FIXME BARRY CHANGE TO show
							$resultString .= '
								<a href="'.$this->configuration['callback'];
							$resultString .= '&amp;action=modify';
							$resultString .= '&amp;itemId='.$event->itemId;
							$resultString .= '" ';
							if ($this->configuration['overlib'])
							{
								$resultString .= $this->overlib ($event,
									mktime (0, 0, 0, $month, $week[$j]['day'], $year));
							}
							$resultString .= '>';
							$resultString .= $this->renderEventName ($event);
							$resultString .= '</a>';
							if (count ($event->reminders) > 0)
							{
								$resultString .= '&nbsp;<a href="index.php';
								$resultString .= '?plugin=calendar';
								$resultString .= '&amp;action=modify';
								$resultString .= '&amp;itemId='.$event->itemId;
								$resultString .= '&editReminder=true">';
								$resultString .= '<img src="plugins/calendar/view/pics/reminder.gif" ';
								$resultString .= 'border="0"></a>';
							}
							//$resultString .= $this->stringUtils->gpcAddSlashes ($event->name);
							$resultString .= '</li>';
						}
					}
					$resultString .= '</td>';
				}
				//
				// If we still have 'days left' (i.e. last week
				// of the month), fill them with empty content
				//
				for ($j; $j<7; $j++)
				{
					$resultString .= '
					<td class="calendar_view_month_daynumber">';
					$resultString .= '&nbsp;';
					$resultString .= '</td>';
				}
			}
			$resultString .= '</tr>';
		}
		$resultString .= '</table>';
		return $resultString;
	}

	/**
	 * Prepares the events on a day for layout. This means
	 * that the events will be filtered first (events spanning
	 * mulitple days are treated differently accoring to
	 * duration on a specific day) after which they are
	 * prepared (put at the right place in a container array)
	 * and finally they will be packed.
	 *
	 * @param requestedDate integer the day we would like to see
	 * @param data array the events that need to be prepared
	 *
	 * @return array the packed events
	 *
	 * @see filterSpecialEvents
	 * @see prepareEvents
	 * @see packEvents
	 */
	function prepareDayForLayout ($requestedDate, $data)
	{
		$events = $this->buildEmptyDayArray ();
		$events ['global'] = $this->getGlobalEvents
			(&$data, $requestedDate);

		$this->filterSpecialEvents (&$events, &$data, $requestedDate);
		$this->prepareEvents (&$events, $data);
		$packedEvents = $this->packEvents ($events, $data);
		//
		// Global events (for the entire day)
		//
		$packedEvents['global'] = $events['global'];
		return $packedEvents;
	}

	/**
	 * Show a specific day with its events. Note that this function
	 * only returns a string containing table rows. The actual table
	 * tag is not given and needs to be issued in the calling function.
	 * (this enables us to use the function for day display, but
	 * also for week display)
	 *
	 * @param requestedDate integer the day we would like to see
	 * @param data array an array of events
	 *
	 * @return string day overview in a html string
	 * @see prepareDayForLayout
	 */
	function getDayOverview ($requestedDate, $data)
	{
		//
		// Pack the events. This means putting events together wwho
		// will be displayed in one cell. These events do not
		// necessarily start at the same time, but one event might
		// start before the previous has ended and thus they will
		// be displayed together
		//
		$packedEvents = $this->prepareDayForLayout
			($requestedDate, $data);
		//
		// Display the global events (no start/end time)
		//
		$resultString = '<tr class="odd"><td>&nbsp;</td>';
		$resultString .= '<td>';
		$resultString .= $this->drawGlobalEvents
			($packedEvents['global'], $requestedDate);
		$resultString .= '</td></tr>';
		//
		// Now draw the events per hour
		//
		$rowsLeft = 0;
		for ($i=0; $i<24; $i++)
		{
			$rowsLeft = $this->renderCell ($requestedDate, $i, $packedEvents[$i],
				&$resultString, $rowsLeft);
		}
		return $resultString;
	}

	/**
	 * Prepare events: examine the starthour of the data and place
	 * it in the appropriate index in the event array
	 *
	 * @param events array the array the will contain the result
	 * @param data aray the input array
	 */
	function prepareEvents (&$events, $data)
	{
		for ($i=0; $i<count($data); $i++)
		{
			$currentEvent = $data[$i];
			if (isset ($currentEvent))
			{
				$eventStartHour =
					date ("G", $currentEvent->eventStartDate);
				$eventEndHour =
					date ("G", $currentEvent->eventEndDate);
				//
				// Add it to the array of events for this hour
				//
				$events[$eventStartHour]['event'][] = $currentEvent;
				$events[$eventStartHour]['start'] = $eventStartHour;
				$events[$eventStartHour]['duration'] =
					max($events[$eventStartHour]['duration'],
						($eventEndHour - $eventStartHour));
			}
		}
	}

	/**
	 * Filter special events. Events that span multiple days
	 * are treated differently with respect to duration on a specific
	 * day
	 *
	 * @param events array the events; the container in which the
	 *        special events will be placed (they will be removed from
	 * 	      the data array)
	 * @param data array the data the needs to be treated
	 * @param requestedDate integer the date for the data
	 */
	function filterSpecialEvents (&$events, &$data, $requestedDate)
	{
		for ($i=0;$i<count($data); $i++)
		{
			$currentEvent = $data[$i];
			if (isset ($currentEvent))
			{
				if ($currentEvent->eventEndDate == null)
				{
					$currentEvent->eventEndDate =
						$currentEvent->eventStartDate;
				}
				//
				// Check whether we have an event that started
				// before today but ends somewhere during this
				// day. This event is indicated at this day as
				// starting at 00h00
				//
				// Another condition: we are not dealing with a
				// repeating event
				//
				if (date ('Y-m-d', $currentEvent->eventStartDate) <
					date ('Y-m-d', $requestedDate)
					&& $currentEvent->frequency == 'repeat_type_none')
				{
					$currentStart = 0;
				}
				else
				{
					$currentStart =
						date ("G", $currentEvent->eventStartDate);
				}
				//
				// If we have and event that starts somewhere today
				// but ends AFTER today, we let it display until
				// 24h00
				//
				// Another condition: we are not dealing with a
				// repeating event
				//
				if (date ('Y-m-d', $currentEvent->eventEndDate) >
					date ('Y-m-d', $requestedDate)
					&& ($currentEvent->frequency == 'repeat_type_daily' ||
						$currentEvent->frequency == 'repeat_type_none')
				)
				{
					$currentEnd = 24;
				}
				else
				{
					$currentEnd =
						date ("G", $currentEvent->eventEndDate);
				}
				//
				// Calculate the duration of this specific event ON
				// THIS DAY
				//
				$diff = $currentEnd - $currentStart;
				//
				// If we start in the same hour as we end (i.e.
				// from 08:00 to 08:30), calculate it as one hour
				// anyway
				//
				if ($diff == 0)
				{
					$diff = 1;
				}
				$events[$currentStart]['event'][] = $currentEvent;
				$events[$currentStart]['duration']=$diff;
				$events[$currentStart]['start']=$currentStart;
				//
				// Now remove it from the data array
				//
				$data[$i] = null;
			}
		}
	}

	/**
	 * Pack the events. This means putting events together wwho
	 * will be displayed in one cell. These events do not
     * necessarily start at the same time, but one event might
	 * start before the previous has ended and thus they will
	 *  be displayed together
	 *
	 * @param events array the events; the container in which the
	 *        special events will be placed
	 * @param data array the data the needs to be treated
	 *
	 * @return array a new array containing the packed events
	 *         The array will have the following layout:
 	 * <pre>
	 * packedEvents [hour] = Array (
	 * 		start
     *		duration
	 *		arrayOfEvents
	 * )
	 * </pre>
	 */
	function packEvents (&$events, $data)
	{
		//
		// Events sorted per hour
		// packedEvents will have the following layout
		//
		// packedEvents [hour] = Array (
		// 		start (equals hour I guess)
		//		duration
		// 		arrayOfEvents
		// )
		//
		$packedEvents = array_fill (0, 24, null);
		$previousEvent = 0;
		for ($i=0; $i<24; $i++)
		{
			if (isset ($events[$i]['event']))
			{
				$event = $events[$i];
				//
				// currentEvent will be an array
				// start, duration, event (of type array)
				//
				if (isset ($packedEvents[$previousEvent])
					&&
					(
						//
						// If the previous event's starttime
						// added with its duration (=> end time)
						// is bigger than the current event's start
						// time, add the current event to that list
						// and recalculate the duration
						//
						// AND we are not talking about a repeat event
						//
						($packedEvents[$previousEvent]['start'] +
				 		$packedEvents[$previousEvent]['duration'])
					   		>= $event['start']
					)
				)
				{
					$prevEvent =& $packedEvents[$previousEvent];
					$prevEvent['events'][] =&
						$event['event'];
					//
					// Calculate the new duration
					//
					$newDuration = $event['start'] -
								   $prevEvent['start'] +
								   $event['duration'];
					//
					// And set it if it is longer than the previous
					// duration
					//
					$prevEvent['duration']=
						max ($prevEvent['duration'], $newDuration);
				}
				else
				{
					$packedEvents[$i] = array ();
					$packedEvents[$i]['events']=array ();
					$packedEvents[$i]['events'][] =& $event['event'];
					$packedEvents[$i]['start']=$i;
					$packedEvents[$i]['duration']=$event['duration'];
					$previousEvent = $i;
				}
			}
		}
		return $packedEvents;
	}


	/**
	 * Displays a 'small' table showing a month with daysnumbers
	 *
	 * @param month integer the month we are displaying. A number
	 * 		 between one and 12
	 * @param year integer the year we are displaying
	 * @param data array the data, this is an array of events
	 * @param startDayOfWeek integer 0 for sunday, 1 for monday
	 *
	 * @return string a string containing the html output for the
	 *         table
	 * @todo this function contains quite some copy-and-pasted code
	 * 		 and these should be embedded in a generic function
	 */
	function displaySmallTable ($month, $year, $data, $startDayOfWeek,
		$requestedDate)
	{
		$today = date ('Y-m-d');
		$requested = date ('Y-m-d', $requestedDate);
		$resultString = '<table class="calendar_month_overview">';
		//
		// Show the abbreviated daynames as headers
		//
		$resultString .= '
			<tr class="calendar_month_overview_header">';
		for ($i=$startDayOfWeek; $i<7+$startDayOfWeek; $i++)
		{
			$name = 'day'.(($i+7)%7). 'short';

			$resultString .= '
				<th class="calendar_month_overview_dayname">';
			$resultString .= $this->dictionary[$name];
			$resultString .= '</th>';
		}
		$resultString .= '</tr>';
		//
		// Now show the datenumbers in the table
		//
		foreach ($data as $week)
		{
			$weekNumbers = array_keys ($data);
			$numOfDaysInWeek = count($week);

			$resultString .= '
				<tr class="calendar_month_overview_week">';
			$firstWeekInMonth = $weekNumbers[0];
			//
			// First week
			//
			if ($week == $data[$firstWeekInMonth])
			{
 		        	if (isset ($currentEvent))
					{
						if ($currentEvent->eventEndDate == null)
                		{
                    			$currentEvent->eventEndDate =
                        		$currentEvent->eventStartDate;
                		}
					}
				//
				// Empty... The first days of this week belong to the
				// previous month
				//
				for ($i=0; $i<(7-$numOfDaysInWeek); $i++)
				{
					$resultString .= '
					<td class="calendar_month_overview_daynumber">';
					$resultString .= '&nbsp;';
					$resultString .= '</td>';
				}
				//
				// The rest of the week
				//
				for ($j=0; $j<$numOfDaysInWeek; $j++)
				{
					$resultString .= '<td class="';
					$currentDate = mktime (0, 0, 0,
						$month, $week[$j]['day'], $year);
					if (date ('Y-m-d', $currentDate) == $today
						&&
						$today == $requested
					)
					{
							$resultString .= 'calendar_month_overview_daynumber_today_requested';
					}
					else if (date ('Y-m-d', $currentDate) == $today)
					{
							$resultString .= 'calendar_month_overview_daynumber_today';
					}
					else if (date ('Y-m-d', $currentDate) == $requested)
					{
							$resultString .= 'calendar_month_overview_daynumber_requested';
					}
					else
					{
							$resultString .= 'calendar_month_overview_daynumber';
					}
					if (isset ($week[$j]['events']))
					{
							$resultString .= ' yearlyViewDayHasEvents';
					}
					$resultString .= '">';
					$resultString .= '<a href="'.$this->configuration['callback'];
					$resultString .= '&amp;action=showDay&day=';
					$resultString .= $week[$j]['day'];
					$resultString .= '&month='.$month;
					$resultString .= '&year='.$year;
					$resultString .= '">';
					$resultString .= $week[$j]['day'];
					$resultString .= '</a></td>';
				}
			}
			else
			{
				//
				// Full week
				//
				for ($j=0; $j<$numOfDaysInWeek; $j++)
				{
					$resultString .= '<td class="';
					$currentDate = mktime (0, 0, 0,
						$month, $week[$j]['day'], $year);
					if (date ('Y-m-d', $currentDate) == $today
						&&
						$today == $requested
					)
					{
							$resultString .= 'calendar_month_overview_daynumber_today_requested';
					}
					else if (date ('Y-m-d', $currentDate) == $today)
					{
							$resultString .= 'calendar_month_overview_daynumber_today';
					}
					else if (date ('Y-m-d', $currentDate) == $requested)
					{
							$resultString .= 'calendar_month_overview_daynumber_requested';
					}
					else
					{
							$resultString .= 'calendar_month_overview_daynumber';
					}
					if (isset ($week[$j]['events']))
					{
							$resultString .= ' yearlyViewDayHasEvents';
					}
					$resultString .= '">';
					$resultString .= '<a href="'.$this->configuration['callback'];
					$resultString .= '&amp;action=showDay&day=';
					$resultString .= $week[$j]['day'];
					$resultString .= '&month='.$month;
					$resultString .= '&year='.$year;
					$resultString .= '">';
					$resultString .= $week[$j]['day'];
					$resultString .= '</a></td>';
				}
				//
				// If we still have 'days left' (i.e. last week
				// of the month), fill them with empty content
				//
				for ($j; $j<7; $j++)
				{
					$resultString .= '
					<td class="calendar_month_overview_daynumber">';
					$resultString .= '&nbsp;';
					$resultString .= '</td>';
				}
			}
			$resultString .= '</tr>';
		}
		$resultString .= '</table>';
		return $resultString;
	}


	/**
	 * Render a cell. This is the function that returns a table
	 * row (one td with the hour, another one with the events)
	 * for the day view
	 *
	 * @param hour integer the hour that needs to be rendered
	 * @param eventArray array the events for this hour
	 * @param resultString string this is the string to which the
	 *        result will be appended
	 * @param rowsLeft integer indicator for the duration of an event.
	 *        This indicator will be used to calculate a rowspan
	 * @return integer the rowspan left
	 * @see getDayOverview
	 */
	function renderCell ($requestedDate, $hour, $eventArray, &$resultString, $rowsLeft)
	{
		//
		// Odd and even hours are displayed differently
		//
		if ($hour % 2 == 0)
		{
			$resultString .= '<tr class="calendar_view_day_hour_even">';
		}
		else
		{
			$resultString .= '
				<tr class="calendar_view_day_hour_odd">';
		}
		//
		// Display the hour
		//
		$resultString .= '<td class="calendar_view_day_hour">';
		if ($hour < 10)
		{
			$hour = '0'.$hour;
		}
		$resultString .= $hour.':00';
		$resultString .='</td>';

		//
		// Now display the events for this hour
		//
		if (count($eventArray) > 0)
		{
			$rowsLeft = $eventArray['duration'];
			$resultString .= '
				<td class="calendar_view_day_event" ';
			$resultString .= 'rowspan="'.$eventArray['duration'].'">';
			$resultString .= '<table class="calendar_view_day_event">';
			foreach ($eventArray['events'] as $packedEvents)
			{
				$events = $packedEvents;
				if (isset ($events))
				{
					for ($k=0; $k<count($events); $k++)
					{
						$currentEvent = $events[$k];
						$resultString .= '<tr>';
						$resultString .= '
							<td class="calendar_view_day_event_text">';
						//
						// Display the start and end time
						//
						$resultString .= '
						<div class="calendar_view_day_event_start">';

						if (date ('Y-m-d', $currentEvent->eventStartDate)
						   <date ('Y-m-d', $requestedDate)
						    && $currentEvent->frequency == 'repeat_type_none'
				   			)
						{
							$resultString .= '00:00';
						}
						else
						{
							$resultString .= date ('H:i',
								$currentEvent->eventStartDate);
						}
						$resultString .= '&nbsp;-&nbsp;';
						if (date ('Y-m-d', $currentEvent->eventEndDate)
						   >date ('Y-m-d', $requestedDate))
						{
							$resultString .= '24:00';
						}
						else
						{
							$resultString .= date ('H:i',
								$currentEvent->eventEndDate);
						}
						$resultString .= '</div>';

						//
						// And now the name of the item and a link
						// back to the controller
						//
						$resultString .= '<div class="calendar_view_day_event_name">';
						$resultString .= '
							<a href="'.$this->configuration['callback'];
						$resultString .= '&amp;action=modify';
						$resultString .= '&amp;itemId=';
						$resultString .= $currentEvent->itemId;
						$resultString .= '" ';
						if ($this->configuration['overlib'])
						{
							$resultString .= $this->overlib
								($currentEvent, $requestedDate);
						}
						$resultString .= '>';
						//$resultString .= $this->stringUtils->gpcAddSlashes ($currentEvent->name);
						$resultString .= $this->renderEventName ($currentEvent);
						$resultString .= '</a>';
						if (count ($currentEvent->reminders) > 0)
						{
							$resultString .= '&nbsp;<a href="index.php';
							$resultString .= '?plugin=calendar';
							$resultString .= '&amp;action=modify';
							$resultString .= '&amp;itemId='.$currentEvent->itemId;
							$resultString .= '&editReminder=true">';
							$resultString .= '<img src="plugins/calendar/view/pics/reminder.gif" ';
							$resultString .= 'border="0"></a>';
						}
						$resultString .= '</div>';
						$resultString .= '</td></tr>';
					}
				}
			}
			$resultString .= '</table>';
			$resultString .= '</td>';
			$rowsLeft--;
		}
		else
		{
			if ($rowsLeft == 0)
			{
				$resultString .= '<td>&nbsp;</td>';
			}
			else
			{
				//
				// Don't display table data, it is being filled by
				// previous events->rowspan
				//
				$rowsLeft--;
			}
		}
		$resultString .= '</tr>';
		return $rowsLeft;
	}


	/**
	 * Render the cell for week based display
	 *
	 * @param eventArray array the event to be rendered
	 * @apram resultString string the output will be appended
	 *        to this string
	 * @param rowsLeft integer value used in combination with the
	 *        duration of the event to determine its rowspan
	 * @param requestedDate integer the date we are renderering
	 * @return integer the remaining rows left
	 */
	function renderWeekCell ($eventArray, &$resultString, $rowsLeft,
		$requestedDate)
	{
		//$this->logger->log ('RenderWeekCell');
		//$this->logger->log (var_export($eventArray, true));
		//$this->logger->log ('RowsLeft: '.$rowsLeft);
		//$this->logger->log ('RequestedDate: '.date ('Y-m-d', $requestedDate));
		if (count($eventArray) > 0)
		{
			$rowsLeft = $eventArray['duration'];
			$resultString .= '<td class="calendar_view_week_day_event"
				rowspan="'.$eventArray['duration'].'">';
			//
			// A table to display all events
			//
			$resultString .= '
				<table class="calendar_view_week_day_event">';
			for ($i=0;$i<count($eventArray['events']); $i++)
			{
				$events = $eventArray['events'][$i];
				if (isset ($events))
				{
					for ($k=0; $k<count($events); $k++)
					{
						$currentEvent = $events[$k];
						$resultString .= '<tr>
							<td class="calendar_view_week_day_event">';
						$resultString .= '<div class="calendar_view_week_day_event_start">';
						if (date ('Y-m-d',
							$currentEvent->eventStartDate) <
							date ('Y-m-d', $requestedDate)
						    && $currentEvent->frequency == 'repeat_type_none')
						{
							$resultString .= '00:00';
						}
						else
						{
							$resultString .= date ('H:i',
								$currentEvent->eventStartDate);
						}
						$resultString .= '&nbsp;-&nbsp;';
						if (date ('Y-m-d',
							$currentEvent->eventEndDate) >
							date ('Y-m-d', $requestedDate))
						{
							$resultString .= '24:00';
						}
						else
						{
							$resultString .= date ('H:i',
								$currentEvent->eventEndDate);
						}
						$resultString .= '</div>';
						//$resultString .= '<br />';
						$resultString .= '<div class="calendar_view_week_day_event_name">';
						$resultString .= '<a href="'.$this->configuration['callback'];
						$resultString .= '&amp;action=modify';
						$resultString .= '&amp;itemId='.$currentEvent->itemId.'" ';
						if ($this->configuration['overlib'])
						{
							$resultString .= $this->overlib
								($currentEvent, $requestedDate);
						}
						$resultString .= '>';
						$resultString .= $this->renderEventName ($currentEvent);
						$resultString .= '</a>';
						if (count ($currentEvent->reminders) > 0)
						{
							$resultString .= '&nbsp;<a href="index.php';
							$resultString .= '?plugin=calendar';
							$resultString .= '&amp;action=modify';
							$resultString .= '&amp;itemId='.$currentEvent->itemId;
							$resultString .= '&editReminder=true">';
							$resultString .= '<img src="plugins/calendar/view/pics/reminder.gif" ';
							$resultString .= 'border="0"></a>';
						}
						//$resultString .= $this->stringUtils->gpcAddSlashes ($currentEvent->name);
						$resultString .= '</div>';
						$resultString .= '</td></tr>';
					}
				}
			}
			$resultString .= '</table>';
			$resultString .= '</td>';
			$rowsLeft--;
		}
		else
		{
			if ($rowsLeft == 0)
			{
				$resultString .= '<td>&nbsp;</td>';
			}
			else
			{
				//
				// Don't display table data, it is being filled by
				// previous events->rowspan
				//
				$rowsLeft--;
			}
		}
		return $rowsLeft;
	}

	/**
	 * @param requestedDate integer the date from which we issued
	 *        the request
	 * @param data array the data (events) to display. This is an array
	 *        [0..6] containing events.
	 * @param firstDayOfWeek the date of the first day of this week
	 * @param startDayOfWeek integer 0 for sunday, 1 for monday
	 */
	function getWeekOverview ($requestedDate, $data,
		$firstDayOfWeek, $startDayOfWeek)
	{
		//$this->logger->log ('GetWeekOverview');
		//$this->logger->log (var_export ($data, true));
		//$this->logger->log ('Firstdayofweek: '.$firstDayOfWeek);
		//$this->logger->log ('StartDayOfWeek: '.$startDayOfWeek);
		$today = date ('Y-m-d');
		$weekEvents = $this->buildEmptyWeekArray ();
		for ($i=0; $i<7; $i++)
		{
			//$currentDate = $firstDayOfWeek + $i*24*60*60;
			$currentDate = strtotime ('+'.$i.' days', $firstDayOfWeek);
			$weekEvents[$i] = $this->prepareDayForLayout
				($currentDate, $data[$i]);
		}
		$resultString = '
		<table class="calendar_show_week">';
		//
		// Show the table header: the week day with underneath it
		// the date.
		//
		//$resultString .= ('<tr class="calendar_show_week_header">');
		$resultString .= ('<tr>');
		$resultString .= '<th>&nbsp;</th>';
		for ($l=0; $l<7; $l++)
		{
			// TBD BARRY TODO FIXME
			// Bug in PHP??? First doesn't work, 2nd does...
			//
			//$currentDate = $firstDayOfWeek + $l*24*60*60;
			$currentDate = strtotime ('+'.$l.' days', $firstDayOfWeek);
			//echo (date ('Y-m-d',$currentDate).'-'.$currentDate.'-'.$l.'<br />');
			if (date ('Y-m-d', $currentDate) == $today)
			{
				$resultString .=
					'<th class="calendar_show_week_header_today">';
			}
			else
			{
				$resultString .=
					'<th class="calendar_show_week_header">';
			}
			$resultString .= '<a href="'.$this->configuration['callback'];
			$resultString .= '&amp;action=showDay';
			$resultString .= '&date='.$currentDate;
			$resultString .= '">';
			$resultString .= $this->dictionary['day'.date('w', $currentDate)];
			$resultString .= '</a><a href="'.$this->configuration['callback'].'&amp;action=add';
			$resultString .= '&date='.$currentDate;
			$resultString .= '">';
			// TODO TBD BARRY FIXME
			$resultString .= '<img border="0" ';
			$resultString .= 'src="plugins/calendar/view/pics/new.gif">';
			$resultString .= '<br />';
			$resultString .= date ('Y-m-d', $currentDate);
			$resultString .= '</a>';
			$resultString .= '</th>';
		}
		$resultString .= ('</tr>
		');

		//
		// The global events
		//
		$resultString .= '<tr class="odd">';
		$resultString .= '<td>&nbsp;</td>';
		for ($m=0; $m<7; $m++)
		{
			//$tmpDate = $firstDayOfWeek + $m*24*60*60;
			$tmpDate = strtotime ('+'.$m.' days', $firstDayOfWeek);
			$globEvents = $this->getGlobalEvents
				(&$data[$m], $tmpDate);
			$resultString .= '
				<td class="calendar_view_week_global_event">';
			if (isset ($globEvents))
			{
				$resultString .=
					$this->drawGlobalEvents ($globEvents, $tmpDate);
			}
			else
			{
				$resultString .= '&nbsp;';
			}
			$resultString .= '</td>';
		}
		$resultString .= '</tr>';
		//
		// The array containing which day has still rows left
		//
		$rowsLeft = array_fill (0, 7, 0);
		//
		// Now render the events
		//
		for ($j=0; $j<24; $j++)
		{
			if ($j % 2 == 0)
			{
				$resultString .= '
				<tr class="even">';
			}
			else
			{
				$resultString .= '
				<tr class="odd">';
			}
			$hour = $j;
			if ($j<10)
			{
					$hour = '0'.$j;
			}
			$resultString .= '<td class="calendar_show_week_hour">';
			$resultString .= $hour.':00</td>';
			for ($k=0; $k<7; $k++)
			{
				$currentDate = strtotime ('+'.$k.' days', $firstDayOfWeek);
				//$currentDate = $firstDayOfWeek + $k*24*60*60;
				$rowsLeft[$k] =
					$this->renderWeekCell ($weekEvents[$k][$j],
						&$resultString, $rowsLeft[$k], $currentDate);
			}
			$resultString .= '</tr>';
		}
		$resultString .= '
		</table>';
		return $resultString;
	}

	function buildEmptyWeekArray ()
	{
		$week = array ();
		for ($i=0; $i<7; $i++)
		{
			$week [$i] = $this->buildEmptyDayArray ();
		}
		return $week;
	}

	function buildEmptyDayArray ()
	{
		$day = array ();
		$day['global']='';
		for ($j=0; $j<24; $j++)
		{
			$day [$j] =
				array ('event'=>null, 'duration'=>0, 'start'=>0);
		}
		return $day;
	}

	function getGlobalEvents (&$data, $requestedDate)
	{
		$globalEvents = array ();
		//
		// Loop over all events
		//
		for ($i=0;$i<count ($data); $i++)
		{
			$currentEvent = $data [$i];
			//
			// a global event is an event that has both start and end
			// time at 00h00
			// OR it is an event that spans multiple days (starting
			// BEFORE today and ending AFTER today
			//
			if (
				(date ('H:i', $currentEvent->eventStartDate) == '00:00'
					&&
					($currentEvent->eventEndDate == null || date ('H:i', $currentEvent->eventEndDate) == '00:00'))
				)
			{
				//
				// Add the event as a global event
				//
				$globalEvents [] = $currentEvent;
				//
				// And remove the event from the original array
				// so we do not need to process it anymore
				//
				$data[$i] = null;
			}
		}
		return $globalEvents;
	}

	function drawGlobalEvents ($data, $requestedDate)
	{
		$resultString = '';
		if (count($data) > 0)
		{
			foreach ($data as $globalEvent)
			{
				$resultString .= '<a href="'.$this ->configuration['callback'];
				$resultString .= '&amp;action=modify';
				$resultString .= '&amp;itemId='.$globalEvent->itemId;
				$resultString .= '" ';
				if ($this->configuration['overlib'])
				{
					$resultString .= $this->overlib ($globalEvent, $requestedDate);
				}
				$resultString .= '>';
				$resultString .= $this->renderEventName ($globalEvent);

				$resultString .= '</a>';
				if (count ($globalEvent->reminders) > 0)
				{
					$resultString .= '&nbsp;<a href="index.php';
					$resultString .= '?plugin=calendar';
					$resultString .= '&amp;action=modify';
					$resultString .= '&amp;itemId='.$globalEvent->itemId;
					$resultString .= '&editReminder=true">';
					$resultString .= '<img src="plugins/calendar/view/pics/reminder.gif" ';
					$resultString .= 'border="0"></a>';
				}
				$resultString .= '<br />';
			}
		}
		else
		{
			$resultString .= '&nbsp;';
		}
		return $resultString;
	}
	/**
	 * Returns the overlib javascript
	 *
	 * @param string title the title of the popup
	 * @param string text the text of the popup
	 */
	function overLib ($event, $requestedDate)
	{
		$description = '';
		$start = date ('H:i', $event->eventStartDate);
		$end = date ('H:i', $event->eventEndDate);
		$startDay = date ('Y-m-d', $event->eventStartDate);
		$endDay = date ('Y-m-d', $event->eventEndDate);
		$today = date ('Y-m-d', $requestedDate);
		//
		// Only show the times when we do NOT have a global event
		//
		if ($start != '00:00' || $end != '00:00')
		{
			if ($startDay < $today && $event->frequency=='repeat_type_none')
			{
				$description .= '00:00';
			}
			else
			{
				$description .= $start;
			}
			$description .= '&nbsp;-&nbsp;';
			if ($endDay > $today)
			{
				$description .= '24:00';
			}
			else
			{
				$description .= $end;
			}
			$description .= '<br />';
		}
		//
		// Only show the location if it is available
		//
		if (isset ($event->location) && $event->location != '')
		{
			$description .= $event->location;
			$description .= '<br />';
		}
		if (isset ($event->description) && $event->description != '')
		{
			$description .= $event->description;
		}

		$string = addslashes ($description);
		$string = $this->stringUtils->urlEncodeQuotes ($string);
		$string = $this->stringUtils->newlinesToHtml ($string);

		$resultString  = 'onmouseover="return overlib(\'';
		$resultString .= $string;
		$resultString .= "', CAPTION, '";
		$resultString .= $this->stringUtils->urlEncodeQuotes(addslashes ($event->name)) . '\');" ';
		$resultString .= 'onmouseout="return nd();" ';
		return $resultString;
	}

	function renderEventName ($event)
	{
		if (isset ($event->eventColour) && $event->eventColour != '')
		{
				/*
			$resultString  = '<table><tr><td bgcolor="'.$event->eventColour.'">';
			$resultString .= $this->stringUtils->gpcAddSlashes ($event->name);
			$resultString .= '</td></tr></table>';
			*/
			$resultString = '<font color="'.$event->eventColour.'">';
			$resultString .= $this->stringUtils->gpcAddSlashes ($event->name);
			$resultString .= '</font>';
		}
		else
		{
			$resultString = $this->stringUtils->gpcAddSlashes ($event->name);
		}
		return $resultString;
	}
}
?>
