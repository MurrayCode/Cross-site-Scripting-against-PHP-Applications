<?php
/******************************************************************************
* Calendar.php                                                                *
*******************************************************************************
* SMF: Simple Machines Forum                                                  *
* Open-Source Project Inspired by Zef Hemel (zef@zefhemel.com)                *
* =========================================================================== *
* Software Version:           SMF 1.0                                         *
* Software by:                Simple Machines (http://www.simplemachines.org) *
* Copyright 2001-2004 by:     Lewis Media (http://www.lewismedia.com)         *
* Support, News, Updates at:  http://www.simplemachines.org                   *
*******************************************************************************
* This program is free software; you may redistribute it and/or modify it     *
* under the terms of the provided license as published by Lewis Media.        *
*                                                                             *
* This program is distributed in the hope that it is and will be useful,      *
* but WITHOUT ANY WARRANTIES; without even any implied warranty of            *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                        *
*                                                                             *
* See the "license.txt" file for details of the Simple Machines license.      *
* The latest version can always be found at http://www.simplemachines.org.    *
******************************************************************************/
/* Original module by Aaron O'Neil - aaron@mud-master.com                     *
******************************************************************************/
if (!defined('SMF'))
	die('Hacking attempt...');

/*	This file has only one real task... showing the calendar.  Posting is done
	in Post.php - this just has the following functions:

	void CalendarMain()
		- loads the specified month's events, holidays, and birthdays.
		- requires the calendar_view permission.
		- depends on the cal_enabled setting, and many of the other cal_
		  settings.
		- uses the calendar_start_day theme option. (Monday/Sunday)
		- uses the main sub template in the Calendar template.
		- goes to the month and year passed in 'month' and 'year' by
		  get or post.
		- accessed through ?action=calendar.

	array calendarBirthdayArray(string earliest_date, string latest_date)
		- finds all the birthdays in the specified range of days.
		- earliest_date and latest_date are inclusive, and should both be in
		  the YYYY-MM-DD format.
		- works with birthdays set for no year, or any other year, and
		  respects month and year boundaries.
		- returns an array of days, each of which an array of birthday
		  information for the context.

	array calendarEventArray(string earliest_date, string latest_date,
			bool use_permissions = true)
		- finds all the posted calendar events within a date range.
		- both the earliest_date and latest_date should be in the standard
		  YYYY-MM-DD format.
		- censors the posted event titles.
		- uses the current user's permissions if use_permissions is true,
		  otherwise it does nothing "permission specific".
		- returns an array of contextual information if use_permissions is
		  true, and an array of the data needed to build that otherwise.

	array calendarHolidayArray(string earliest_date, string latest_date)
		- finds all the applicable holidays for the specified date range.
		- earliest_date and latest_date should be YYYY-MM-DD.
		- returns an array of days, which are all arrays of holiday names.

	void calendarInsertEvent(int id_board, int id_topic, string title,
			int id_member, int month, int day, int year, int span)
		- inserts the passed event information into the calendar table.
		- recaches the calendar information after doing so.
		- expects the passed title not to have html characters.
		- handles spanned events by inserting them multiple times.
		- does not check any permissions of any sort.

	bool calendarCanLink()
		- checks if the current user can link the current topic to the
		  calendar, permissions et al.
		- this requires the calendar_post permission, a forum moderator, or a
		  topic starter.
		- expects the $topic and $board variables to be set.
		- returns true or false corresponding to whether they can or cannot
		  link this topic to the calendar.
*/

// Show the calendar.
function CalendarMain()
{
	global $txt, $context, $modSettings, $scripturl, $months, $options;

	// This is gonna be needed...
	loadTemplate('Calendar');

	// Permissions, permissions, permissions.
	isAllowedTo('calendar_view');

	// You can't do anything if the calendar is off.
	if (empty($modSettings['cal_enabled']))
		fatal_lang_error('calendar_off', false);

	// Set the page title to mention the calendar ;).
	$context['page_title'] = $context['forum_name'] . ': ' . $txt['calendar24'];

	// Get the current day of month...
	$today = array(
		'day' => (int) strftime('%d', forum_time()),
		'month' => (int) strftime('%m', forum_time()),
		'year' => (int) strftime('%Y', forum_time())
	);

	// If the month and year are not passed in, use today's date as a starting point.
	$curPage = array(
		'month' => isset($_REQUEST['month']) ? (int) $_REQUEST['month'] : $today['month'],
		'year' => isset($_REQUEST['year']) ? (int) $_REQUEST['year'] : $today['year']
	);

	// Make sure the year and month are in valid ranges.
	if ($curPage['month'] < 1 || $curPage['month'] > 12)
		fatal_lang_error('calendar1', false);
	if ($curPage['year'] < $modSettings['cal_minyear'] || $curPage['year'] > $modSettings['cal_maxyear'])
		fatal_lang_error('calendar2', false);

	// Get information about the first day of this month.
	$firstDayOfMonth = array(
		'dayOfWeek' => (int) strftime('%w', mktime(0, 0, 0, $curPage['month'], 1, $curPage['year'])),
		'weekNum' => (int) strftime('%U', mktime(0, 0, 0, $curPage['month'], 1, $curPage['year']))
	);

	// Find the last day of the month.
	$nLastDay = (int) strftime('%d', mktime(0, 0, 0, $curPage['month'] == 12 ? 1 : $curPage['month'] + 1, 0, $curPage['month'] == 12 ? $curPage['year'] + 1 : $curPage['year']));

	// The number of days the first row is shifted to the right for the starting day.
	$nShift = $firstDayOfMonth['dayOfWeek'];

	// Start on Monday or Sunday?
	$bStartMonday = !empty($options['calendar_start_day']) && $options['calendar_start_day'] == 1;

	// Starting on monday?  Move the shift around.
	if ($bStartMonday)
		$nShift = ($nShift == 0 ? 6 : $nShift - 1);

	// Number of rows required to fit the month.
	$nRows = floor(($nLastDay + $nShift) / 7);
	if (($nLastDay + $nShift) % 7)
		$nRows++;

	// Get the lowest and highest days of this month, in YYYY-MM-DD format. ($nLastDay is always 2 digits.)
	$low = $curPage['year'] . '-' . str_pad($curPage['month'], 2, '0', STR_PAD_LEFT) . '-01';
	$high = $curPage['year'] . '-' . str_pad($curPage['month'], 2, '0', STR_PAD_LEFT) . '-' . $nLastDay;

	// Fetch the arrays for birthdays, posted events, and holidays.
	$bday = calendarBirthdayArray($low, $high);
	$events = calendarEventArray($low, $high);
	$holidays = calendarHolidayArray($low, $high);

	// Days of the week taking into consideration that they may want it to start on a monday.
	$context['week_days'] = $bStartMonday ? array(1, 2, 3, 4, 5, 6, 0) : array(0, 1, 2, 3, 4, 5, 6);

	// An adjustment value to apply to all calculated week numbers.
	if (!empty($modSettings['cal_showweeknum']))
	{
		// Need to know what day the first of the year was on.
		$foy = (int) strftime('%w', mktime(0, 0, 0, 1, 1, $curPage['year']));

		// If the first day of the year is on the start day of a week, then there is no adjustment
		// to be made. However, if the first day of the year is not a start day, then there is a partial
		// week at the start of the year that needs to be accounted for.
		$nWeekAdjust = $foy == 0 ? 0 : 1;
	}
	else
		$nWeekAdjust = 0;

	// Basic template stuff.
	$context['can_post'] = allowedTo('calendar_post');
	$context['last_day'] = $nLastDay;
	$context['current_month'] = $curPage['month'];
	$context['current_year'] = $curPage['year'];

	// Load up the linktree!
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=calendar;year=' . $context['current_year'] . ';month=' . $context['current_month'],
		'name' => $months[$context['current_month']] . ' ' . $context['current_year']
	);

	// Iterate through each week.
	for ($nRow = 0; $nRow < $nRows; $nRow++)
	{
		// Start off the week - and don't let it go above 52, since that's the number of weeks in a year.
		$context['weeks'][$nRow] = array(
			'days' => array(),
			'number' => $firstDayOfMonth['weekNum'] + $nRow + $nWeekAdjust
		);
		if ($context['weeks'][$nRow]['number'] == 53)
			$context['weeks'][$nRow]['number'] = 1;

		// And figure out all the days.
		for ($nCol = 0; $nCol < 7; $nCol++)
		{
			$nDay = ($nRow * 7) + $nCol - $nShift + 1;

			if ($nDay < 1 || $nDay > $context['last_day'])
				$nDay = 0;

			$context['weeks'][$nRow]['days'][$nCol] = array(
				'day' => $nDay,
				'is_today' => $today['day'] == $nDay && $today['month'] == $curPage['month'] && $today['year'] == $curPage['year'],
				'is_first_day' => !empty($modSettings['cal_showweeknum']) && ((!$bStartMonday && ($firstDayOfMonth['dayOfWeek'] + $nDay - 1) % 7 == 0) || ($bStartMonday && ($firstDayOfMonth['dayOfWeek'] + $nDay - 1) % 7 == 1)),
				'holidays' => !empty($holidays[$nDay]) ? $holidays[$nDay] : array(),
				'events' => !empty($events[$nDay]) ? $events[$nDay] : array(),
				'birthdays' => !empty($bday[$nDay]) ? $bday[$nDay] : array()
			);
		}
	}

	// Find the previous month. (if we can go back that far.)
	if ($curPage['month'] > 1 || ($curPage['month'] == 1 && $curPage['year'] > $modSettings['cal_minyear']))
	{
		// Need to roll the year back one?
		$context['previous_calendar'] = array(
			'year' => $curPage['month'] == 1 ? $curPage['year'] - 1 : $curPage['year'],
			'month' => $curPage['month'] == 1 ? 12 : $curPage['month'] - 1,
		);
		$context['previous_calendar']['href'] = $scripturl . '?action=calendar;year=' . $context['previous_calendar']['year'] . ';month=' . $context['previous_calendar']['month'];
	}

	// The next month... (or can we go that far?)
	if ($curPage['month'] < 12 || ($curPage['month'] == 12 && $curPage['year'] < $modSettings['cal_maxyear']))
	{
		$context['next_calendar'] = array(
			'year' => $curPage['month'] == 12 ? $curPage['year'] + 1 : $curPage['year'],
			'month' => $curPage['month'] == 12 ? 1 : $curPage['month'] + 1
		);
		$context['next_calendar']['href'] = $scripturl . '?action=calendar;year=' . $context['next_calendar']['year'] . ';month=' . $context['next_calendar']['month'];
	}
}

// This is used by the board index to only find members of the current day. (month PLUS one!)
function calendarBirthdayArray($low_date, $high_date)
{
	global $db_prefix, $scripturl, $modSettings;

	// Birthdays people set without specifying a year (no age, see?) are the easiest ;).
	if (substr($low_date, 0, 4) != substr($high_date, 0, 4))
		$allyear_part = "birthdate BETWEEN '0000-" . substr($low_date, 4) . "' AND '0000-12-31'
			OR birthdate BETWEEN '0000-01-01' AND '0000-" . substr($high_date, 4) . "'";
	else
		$allyear_part = "birthdate BETWEEN '0000-" . substr($low_date, 4) . "' AND '0000-" . substr($high_date, 4) . "'";

	// We need to search for any birthday in this range, and whatever year that birthday is on.
	$year_low = (int) substr($low_date, 0, 4);
	$year_high = (int) substr($high_date, 0, 4);

	$result = db_query("
		SELECT DAYOFMONTH(birthdate) AS dom, ID_MEMBER, realName, YEAR(birthdate) AS birthYear, birthdate
		FROM {$db_prefix}members
		WHERE DAYOFYEAR(birthdate) IS NOT NULL
			AND	($allyear_part
				OR DATE_FORMAT(birthdate, '{$year_low}-%m-%d') BETWEEN '$low_date' AND '$high_date'" . ($year_low == $year_high ? '' : "
				OR DATE_FORMAT(birthdate, '{$year_high}-%m-%d') BETWEEN '$low_date' AND '$high_date'") . ")", __FILE__, __LINE__);
	$bday = array();
	while ($row = mysql_fetch_assoc($result))
	{
		if ($year_low != $year_high)
			$age_year = substr($row['birthdate'], 5) <= '12-31' ? $year_low : $year_high;
		else
			$age_year = $year_low;

		$bday[$row['dom']][] = array(
			'id' => $row['ID_MEMBER'],
			'name' => $row['realName'],
			'age' => $row['birthYear'] > 0 && $row['birthYear'] <= $age_year ? $age_year - $row['birthYear'] : null,
			'is_last' => false
		);
	}
	mysql_free_result($result);

	// Set is_last, so the themes know when to stop placing separators.
	foreach ($bday as $mday => $array)
		$bday[$mday][count($array) - 1]['is_last'] = true;

	return $bday;
}

// Create an array of events occurring in this day/month.
function calendarEventArray($low_date, $high_date, $use_permissions = true)
{
	global $db_prefix, $ID_MEMBER, $scripturl, $modSettings, $user_info, $sc;

	// Find all the calendar info...
	$result = db_query("
		SELECT
			cal.ID_EVENT, DAYOFMONTH(cal.eventDate) AS day, cal.title, cal.ID_MEMBER, cal.ID_TOPIC,
			cal.ID_BOARD, b.memberGroups, t.ID_FIRST_MSG
		FROM {$db_prefix}calendar AS cal, {$db_prefix}boards AS b, {$db_prefix}topics AS t
		WHERE cal.eventDate BETWEEN '$low_date' AND '$high_date'
			AND cal.ID_TOPIC = t.ID_TOPIC
			AND cal.ID_BOARD = b.ID_BOARD" . ($use_permissions ? "
			AND $user_info[query_see_board]" : ''), __FILE__, __LINE__);
	$events = array();
	while ($row = mysql_fetch_assoc($result))
	{
		// Censor the title.
		censorText($row['title']);

		// If we're using permissions (calendar pages?) then just ouput normal contextual style information.
		if ($use_permissions)
			$events[$row['day']][] = array(
				'id' => $row['ID_EVENT'],
				'title' => $row['title'],
				'can_edit' => allowedTo('calendar_edit_any') || ($row['ID_MEMBER'] == $ID_MEMBER && allowedTo('calendar_edit_own')),
				'modify_href' => $scripturl . '?action=post;msg=' . $row['ID_FIRST_MSG'] . ';topic=' . $row['ID_TOPIC'] . '.0;calendar;eventid=' . $row['ID_EVENT'] . ';sesc=' . $sc,
				'href' => $scripturl . '?topic=' . $row['ID_TOPIC'] . '.0',
				'link' => '<a href="' . $scripturl . '?topic=' . $row['ID_TOPIC'] . '.0">' . $row['title'] . '</a>',
				'is_last' => false
			);
		// Otherwise, this is going to be cached and the VIEWER'S permissions should apply... just put together some info.
		else
			$events[$row['day']][] = array(
				'id' => $row['ID_EVENT'],
				'title' => $row['title'],
				'topic' => $row['ID_TOPIC'],
				'msg' => $row['ID_FIRST_MSG'],
				'poster' => $row['ID_MEMBER'],
				'is_last' => false,
				'allowed_groups' => explode(',', $row['memberGroups'])
			);
	}
	mysql_free_result($result);

	// If we're doing normal contextual data, go through and make things clear to the templates ;).
	if ($use_permissions)
	{
		foreach ($events as $mday => $array)
			$events[$mday][count($array) - 1]['is_last'] = true;
	}

	return $events;
}

// Builds an array of holiday strings for a particular month.  Note... month PLUS 1 not just month.
function calendarHolidayArray($low_date, $high_date)
{
	global $db_prefix;

	// Get the lowest and highest dates for "all years".
	if (substr($low_date, 0, 4) != substr($high_date, 0, 4))
		$allyear_part = "eventDate BETWEEN '0000-" . substr($low_date, 4) . "' AND '0000-12-31'
			OR eventDate BETWEEN '0000-01-01' AND '0000-" . substr($high_date, 4) . "'";
	else
		$allyear_part = "eventDate BETWEEN '0000-" . substr($low_date, 4) . "' AND '0000-" . substr($high_date, 4) . "'";

	// Find some holidays... ;).
	$result = db_query("
		SELECT DAYOFMONTH(eventDate) AS day, YEAR(eventDate) AS year, title
		FROM {$db_prefix}calendar_holidays
		WHERE eventDate BETWEEN '$low_date' AND '$high_date'
			OR $allyear_part", __FILE__, __LINE__);
	$holidays = array();
	while ($row = mysql_fetch_assoc($result))
		$holidays[$row['day']][] = $row['title'];
	mysql_free_result($result);

	return $holidays;
}

// Consolidating the various INSERT statements into this function.
function calendarInsertEvent($id_board, $id_topic, $title, $id_member, $month, $day, $year, $span)
{
	global $db_prefix;

	// Add special chars to the title.
	$title = htmlspecialchars($title, ENT_QUOTES);

	// Span multiple days?
	if (empty($span) || trim($span) == '')
		$insertString = "
				($id_board, $id_topic, '$title', $id_member, '$year-$month-$day'),";
	else
	{
		// Calculate the time...
		$tVal = mktime(0, 0, 0, $month, $day, $year);

		// I went for the simplest way I could think of for making the events span multiple days.
		$insertString = '';

		// For each day a row is added to the calendar table.
		for ($i = 0; $i < $span; $i++)
		{
			$eventTime = array(
				'day' => (int) strftime('%d', $tVal),
				'month' => (int) strftime('%m', $tVal),
				'year' => (int) strftime('%Y', $tVal)
			);

			$insertString .= "
					($id_board, $id_topic, '$title', $id_member, '$eventTime[year]-$eventTime[month]-$eventTime[day]'),";

			// Add a day...
			$tVal = strtotime('tomorrow', $tVal);
		}
	}

	// Insert the day(s)!
	if (strlen($insertString) > 0)
	{
		$insertString = substr($insertString, 0, -1);

		db_query("
			INSERT INTO {$db_prefix}calendar
				(ID_BOARD, ID_TOPIC, title, ID_MEMBER, eventDate)
			VALUES $insertString", __FILE__, __LINE__);
	}

	updateStats('calendar');
}

// Returns true if this user is allowed to link the topic in question.
function calendarCanLink()
{
	global $ID_MEMBER, $db_prefix, $user_info, $topic, $board;

	// If you can't post, you can't link.
	isAllowedTo('calendar_post');

	// No board?  No topic?!?
	if (!isset($board))
		fatal_lang_error('calendar38', false);
	if (!isset($topic))
		fatal_lang_error('calendar39', false);

	// Administrator, Moderator, or owner.  Period.
	if (!allowedTo('admin_forum') && !allowedTo('moderate_board'))
	{
		// Not admin or a moderator of this board. You better be the owner - or else.
		$result = db_query("
			SELECT ID_MEMBER_STARTED
			FROM {$db_prefix}topics
			WHERE ID_TOPIC = $topic", __FILE__, __LINE__);
		if ($row = mysql_fetch_assoc($result))
		{
			// Not the owner of the topic.
			if ($row['ID_MEMBER_STARTED'] != $ID_MEMBER)
				fatal_lang_error('calendar41');
		}
		// Topic/Board doesn't exist.....
		else
			fatal_lang_error('calendar40');
		mysql_free_result($result);
	}

	// If you got this far, it's okay.
	return true;
}

?>