<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.calendar
 * @subpackage i18n
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
if (!isset ($dictionary))
{
	$dictionary=array();
}


$dictionary['day0short']='Sun';
$dictionary['day0']='Sunday';
$dictionary['day1']='Monday';
$dictionary['day1short']='Mon';
$dictionary['day2short']='Tue';
$dictionary['day2']='Tuesday';
$dictionary['day3short']='Wed';
$dictionary['day3']='Wednesday';
$dictionary['day4short']='Thu';
$dictionary['day4']='Thursday';
$dictionary['day5']='Friday';
$dictionary['day5short']='Fri';
$dictionary['day6']='Saturday';
$dictionary['day6short']='Sat';
$dictionary['dayView']='Day';
$dictionary['days']='Days';
$dictionary['day']='Day';
$dictionary['default_view']='Default view';
$dictionary['duration']='Duration';
$dictionary['end_date']='End date';
$dictionary['endDateMissing']='End date missing';
$dictionary['end_time']='Time';
$dictionary['endTimeMissing']='End time missing';
$dictionary['event']='Event';
$dictionary['firstDayOfWeek']='First weekday';
$dictionary['frequency']='Frequency';
$dictionary['hour']='Hour';
$dictionary['hours']='Hours';
$dictionary['hours_minutes']='(Hours:Minutes)';
$dictionary['item_title']='Calendar';
$dictionary['location']='Location';
$dictionary['minute']='Minute';
$dictionary['minutes']='Minutes';
$dictionary['modifyCalendarPreferences']='Modify calendar preferences';
$dictionary['monthView']='Month';
$dictionary['month']='Month';
$dictionary['months']='Months';
$dictionary['next']='Next';
$dictionary['organizer']='Organizer';
$dictionary['previous']='Previous';
$dictionary['repeat_day_weekly']='Repeat day for weekly repeats';
$dictionary['repeat_end_date']='Repeat end date';
$dictionary['repeat_type']='Repeat type';
$dictionary['repeat_type_daily']='Daily';
$dictionary['repeat_type_weekly']='Weekly';
$dictionary['repeat_type_monthly']='Monthly';
$dictionary['repeat_type_yearly']='Yearly';
$dictionary['start_date']='Start date';
$dictionary['startDateMissing']='Start date missing';
$dictionary['start_time']='Time';
$dictionary['startTimeMissing']='Start time missing';
$dictionary['today']='Today';
$dictionary['week']='Week';
$dictionary['weeks']='Weeks';
$dictionary['weekView']='Week';
$dictionary['yearView']='Year';
$dictionary['year']='Year';
$dictionary['years']='Years';

$dictionary['noDayWeeklyRepeat']='
You set a weekly repeating event without
specifying a day';
$dictionary['startDateAfterEndDate']='Start date must be BEFORE i
the end date';
$dictionary['startDateAfterRecurringEndDate']='Start date is after
recurring end date';
$dictionary['endDateAfterRecurringEndDate']='End date is after
recurring end date';
$dictionary['invalidRepeatType']='Invalid repeat type';
$dictionary['recurrenceNoRepeatType']='You set recurrence but no
recurrence type';
$dictionary['item_help']='
<p>
	The calendar plugin helps you to store all
	your appointments online.
</p>
<p>
	Click on the name of an event to modify the
	contents like date/time etc.
</p>
<p>
	The following parameters of an event can be set:
</p>
<ul>
	<li><em>Name</em>:
		The name of the event. This name will show
		up in the calendar.
	</li>
	<li><em>Location</em>:
		The location where this event takes place.
	</li>
	<li><em>Start date</em>:
		The start date and optional time for this
		event.
	</li>
	<li><em>End date</em>:
		The end date and optional time for this
		event.
	</li>
	<li><em>Description</em>:
		A description of the event.
	</li>
	<li><em>Repeat type</em>:
		Either none, daily, weekly, monthly or
		yearly
	</li>
	<li><em>Repeat day for weekly events</em>:
		If this is a weekly event, on which
		day(s) does it repeat?
	</li>
</ul>
<p>
	The submenus that are available for the
	calendar plugin are: Actions, View,
	Preferences and Help
</p>
<h3>Actions</h3>
<p>
	There are two actions available. The add
	action allows you to add an event. The today
	action takes you to the current day and display
	that day in your prefered layout.
<h3>View</h3>
<p>
	Four types are available:
</p>
<ul>
	<li><em>Year</em>:
		This overview shows a small clickable
		overview of the requested year. No events
		are shown.
	</li>
	<li><em>Month</em>:
		This overview shows the requested month
		as well as smaller overviews of the previous
		and the next month.
		<br />
		Using this overview, you can click on the
		weeknumber to go to a specific week, you can
		click on a daynumber to go to a specific day
		overview or you can click on an event to go
		to that specific event
	</li>
	<li><em>Week</em>:
		This overview shows the requested week.
		Clikcing on the dayheaders will get you to
		that specific day, clicking on an event will
		show you that events\' details.
		<br />
		Links to the next and previous week are shown
		at the top of the overview.
	</li>
	<li><em>Day</em>:
		This shows one specific day. Besides that,
		a small overview of this month is shown.
		<br />
		Links to the next and previous day are shown
		at the top of the overview.
	</li>
</ul>
<h3>Preferences</h3>
<p>
	You can use the preferences to set your start
	of the week (either sunday or monday), whether
	you would like to have javascript popups and
	what your default view is (day, week, month or
	year)
<h3>Help</h3>
<p>
	This submenu contains a link to the information
	that you are currently reading :-)
</p>
';
$dictionary['dontUseStartTime']='Don\'t use start time';
$dictionary['dontUseEndDate']='Don\'t use end date';
$dictionary['duration']='Duration';
$dictionary['recurrence']='Recurrence';
$dictionary['enableRecurring']='Enable recurring';
$dictionary['recurrenceRange']='Recurrence range';
$dictionary['noEndingDate']='No ending date';
$dictionary['endBy']='End by';
$dictionary['repeat_type_none']='No repeat type';
$dictionary['colour']='Colour';
$dictionary['noReminderTime']='No reminder time was set';
$dictionary['reminder']='Reminder';
$dictionary['reminders']='Reminders';
$dictionary['time']='Time';
$dictionary['addAndContinue']='Add and continue';
$dictionary['minute_s']='Minute(s)';
$dictionary['hour_s']='Hour(s)';
$dictionary['day_s']='Day(s)';
$dictionary['whenToSend']='To send approximately at&nbsp;';
$dictionary['whenSent']='Sent at&nbsp;';
$dictionary['notYetSent']='Not yet sent';
$dictionary['clickEventLink']='Click the following link to open the event in your browser: ';
?>
