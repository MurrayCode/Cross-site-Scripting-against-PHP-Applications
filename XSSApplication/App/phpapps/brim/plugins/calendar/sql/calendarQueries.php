<?php
/**
 * Calendar queries
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.calendar
 * @subpackage sql
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 * 
 * @todo TODO BARRY FIXME CLEAN UP THIS MESS!!!!
 */
$tableName = "brim_calendar_event";
include ("framework/sql/itemQueries.php");
include 'framework/configuration/databaseConfiguration.php';
$queries['addItem']=
		"INSERT INTO ".$tableName.
		" (owner, parent_id, is_parent, name, description, visibility, category, when_created, ".
		" location, organizer, priority, frequency, event_interval, by_what, by_what_value, ".
		" event_start_date, event_end_date, event_recurring_end_date, event_colour) ".
		" VALUES (".
		"'%s', %d, %d, '%s', '%s', '%s', '%s', '%s', ".
		"'%s', '%s', %d, '%s', %d, '%s', '%s', ".
		"'%s', '%s', '%s', '%s')";
$queries['addReminder']=
		"INSERT INTO brim_calendar_event_reminder ".
		"(owner, parent_id, is_parent, name, description, visibility, category, is_deleted, when_created, when_modified, ".
		" event_id, timespan, reminder_time, when_sent) ".
		" VALUES ".
		" ('%s', %d, %d, '%s', '%s', '%s', '%s', %d, '%s', null, ".
		" %d, '%s', %d, null)";
if ($engine == 'postgres')
{
        $queries['lastReminderInsertId']=
                "SELECT currval('brim_calendar_event_reminder_item_id_seq')";
}
else
{
        $queries['lastReminderInsertId']=
                "SELECT last_insert_id() FROM brim_calendar_event_reminder";
}

$queries['getReminder']=
		"SELECT reminder.*, event.event_start_date ".
		"FROM brim_calendar_event_reminder reminder, brim_calendar_event event ".
		"WHERE reminder.item_id=%d AND ".
		"reminder.event_id=event.item_id";
$queries['deleteReminder']=
		"DELETE FROM brim_calendar_event_reminder ".
		"WHERE item_id=%d";
$queries['deleteRemindersForEventId']=
		"DELETE FROM brim_calendar_event_reminder ".
		"WHERE event_id=%d AND owner='%s'";
$queries['getReminders']=
		"SELECT reminder.*, event.event_start_date ".
		"FROM brim_calendar_event_reminder reminder, brim_calendar_event event ".
		"WHERE reminder.event_id=%d AND ".
		"event.item_id = reminder.event_id";
$queries['getAllReminders']=
		"SELECT * FROM brim_calendar_event_reminder";
$queries['reminderSent']=
		"UPDATE brim_calendar_event_reminder ".
		"SET when_sent='%s' ".
		"WHERE item_id=%d";
$queries['getEventForId']=
		"SELECT * from brim_calendar_event ".
		"WHERE item_id=%d";
		
if ($engine == 'postgres')
{
	$queries['getEvents']=
		"SELECT * FROM ".$tableName." WHERE ".
			"owner='%s' AND ".
			"event_start_date<='%s' AND ((event_end_date >'%s' ".
			"OR event_end_date='1970-01-01 00:00:00'  ".
			//"OR event_end_date='0000-00-00 00:00:00'  ".
			"OR event_end_date='1970-01-01 01:00:00') OR ".
			"(event_start_date<='%s' AND event_end_date >='%s' AND ".
			"event_start_date = event_end_date)) ".
			"AND frequency='repeat_type_none' ".
			"ORDER BY event_start_date ";
	$queries['getEventsWithParticipation']=
		"SELECT event.* FROM brim_calendar_event event, brim_item_participation particip WHERE ".
			"event.event_start_date<='%s' AND ((event.event_end_date >'%s' ".
			"OR event.event_end_date='1970-01-01 00:00:00'  ".
			//"OR event_end_date='0000-00-00 00:00:00'  ".
			"OR event.event_end_date='1970-01-01 01:00:00') OR ".
			"(event.event_start_date<='%s' AND event.event_end_date >='%s' AND ".
			"event.event_start_date = event.event_end_date)) ".
			"AND event.frequency='repeat_type_none' ".
			"AND event.item_id = particip.item_id ".
			"AND particip.plugin = 'calendar' ".
			"AND particip.participator = '%s' ".
			"ORDER BY event_start_date ";
	$queries['getDailyEvents']=
		"SELECT * FROM ".$tableName." WHERE ".
			"owner='%s' AND ".
			"event_start_date<='%s' AND (event_recurring_end_date >='%s' ".
			"OR event_end_date='1970-01-01 00:00:00' ".
			//"OR event_end_date='0000-00-00 00:00:00'  ".
			"OR event_end_date='1970-01-01 01:00:00') ".
			"AND frequency='repeat_type_daily' ".
			"ORDER BY event_start_date ";
	$queries['getDailyEventsWithParticipation']=
		"SELECT event.* FROM brim_calendar_event event, brim_item_participation particip WHERE ".
			"event.owner='%s' AND ".
			"event.event_start_date<='%s' AND (event.event_recurring_end_date >='%s' ".
			"OR event.event_end_date='1970-01-01 00:00:00' ".
			//"OR event.event_end_date='0000-00-00 00:00:00'  ".
			"OR event.event_end_date='1970-01-01 01:00:00') ".
			"AND event.frequency='repeat_type_daily' ".
			"AND event.item_id = particip.item_id ".
			"AND particip.plugin = 'calendar' ".
			"AND particip.participator = '%s' ".
			"ORDER BY event_start_date ";
	$queries['getYearlyEvents']=
		"SELECT * FROM ".$tableName." WHERE ".
			"owner='%s' ".
			"AND event_start_date<='%s' ".
			"AND event_start_date LIKE '%%-%s-%s %%' ".
			"AND frequency='repeat_type_yearly' ".
			"AND ".
			"(event_recurring_end_date >= '%s' OR ".
			"event_recurring_end_date = '1970-01-01 00:00:00' OR ".
			//"event_recurring_end_date='0000-00-00 00:00:00' OR ".
			"event_recurring_end_date = '1970-01-01 01:00:00') ".
			"ORDER BY event_start_date ";
	$queries['getYearlyEventsWithParticipation']=
		"SELECT event.* FROM brim_calendar_event event, brim_item_participation particip WHERE ".
			"event.owner='%s' ".
			"AND event.event_start_date<='%s' ".
			"AND event.event_start_date LIKE '%%-%s-%s %%' ".
			"AND event.frequency='repeat_type_yearly' ".
			"AND ".
			"(event.event_recurring_end_date >= '%s' OR ".
			"event.event_recurring_end_date = '1970-01-01 00:00:00' OR ".
			//"event.event_recurring_end_date = '0000-00-00 00:00:00' OR ".
			"event.event_recurring_end_date = '1970-01-01 01:00:00') ".
			"AND event.item_id = particip.item_id ".
			"AND particip.plugin = 'calendar' ".
			"AND particip.participator = '%s' ".
			"ORDER BY event_start_date ";
	$queries['getMonthlyEvents']=
		"SELECT * FROM ".$tableName." WHERE ".
			"owner='%s' AND ".
			"event_start_date<='%s' AND ".
			"(event_recurring_end_date >='%s' OR ".
			"event_recurring_end_date = '1970-01-01 00:00:00' OR ".
			//"event_recurring_end_date = '0000-00-00 00:00:00' OR ".
			"event_recurring_end_date = '1970-01-01 01:00:00') ".
			"AND event_start_date LIKE '%%-%%-%s %%' ".
			"AND frequency='repeat_type_monthly' ".
			"ORDER BY event_start_date ";
	$queries['getMonthlyEventsWithParticipation']=
		"SELECT event.* FROM brim_calendar_event event, brim_item_participation particip WHERE ".
			"event.owner='%s' AND ".
			"event.event_start_date<='%s' AND ".
			"(event.event_recurring_end_date >='%s' OR ".
			"event.event_recurring_end_date = '1970-01-01 00:00:00' OR ".
			//"event.event_recurring_end_date = '0000-00-00 00:00:00' OR ".
			"event.event_recurring_end_date = '1970-01-01 01:00:00') ".
			"AND event.event_start_date LIKE '%%-%%-%s %%' ".
			"AND event.frequency='repeat_type_monthly' ".
			"AND event.item_id = particip.item_id ".
			"AND particip.plugin = 'calendar' ".
			"AND particip.participator = '%s' ".
			"ORDER BY event_start_date ";
	$queries['getWeeklyEvents']=
		"SELECT * FROM ".$tableName." WHERE ".
			"owner='%s' AND ".
			"event_start_date<='%s' AND ".
			"(event_recurring_end_date >='%s' OR ".
			"event_recurring_end_date = '1970-01-01 00:00:00' OR ".
			//"event_recurring_end_date = '0000-00-00 00:00:00' OR ".
			"event_recurring_end_date = '1970-01-01 01:00:00') ".
			"AND frequency='repeat_type_weekly' ".
			"AND by_what_value LIKE '%s' ".
			"ORDER BY event_start_date ";
	$queries['getWeeklyEventsWithParticipation']=
		"SELECT event.* FROM brim_calendar_event event, brim_item_participation particip WHERE ".
			"event.owner='%s' AND ".
			"event.event_start_date<='%s' AND ".
			"(event.event_recurring_end_date >='%s' OR ".
			"event.event_recurring_end_date = '1970-01-01 00:00:00' OR ".
			//"event.event_recurring_end_date = '0000-00-00 00:00:00' OR ".
			"event.event_recurring_end_date = '1970-01-01 01:00:00') ".
			"AND event.frequency='repeat_type_weekly' ".
			"AND event.by_what_value LIKE '%s' ".
			"AND event.item_id = particip.item_id ".
			"AND particip.plugin = 'calendar' ".
			"AND particip.participator = '%s' ".
			"ORDER BY event_start_date ";
}
else
{
	$queries['getEvents']=
		"SELECT * FROM ".$tableName." WHERE ".
			"owner='%s' AND ".
			"event_start_date<='%s' AND ((event_end_date >'%s' ".
			"OR event_end_date='1970-01-01 00:00:00'  ".
			"OR event_end_date='0000-00-00 00:00:00'  ".
			"OR event_end_date='1970-01-01 01:00:00') OR ".
			"(event_start_date<='%s' AND event_end_date >='%s' AND ".
			"event_start_date = event_end_date)) ".
			"AND frequency='repeat_type_none' ".
			"ORDER BY event_start_date ";
	$queries['getEventsWithParticipation']=
		"SELECT event.* FROM brim_calendar_event event, brim_item_participation particip WHERE ".
			"event.event_start_date<='%s' AND ((event.event_end_date >'%s' ".
			"OR event.event_end_date='1970-01-01 00:00:00'  ".
			"OR event_end_date='0000-00-00 00:00:00'  ".
			"OR event.event_end_date='1970-01-01 01:00:00') OR ".
			"(event.event_start_date<='%s' AND event.event_end_date >='%s' AND ".
			"event.event_start_date = event.event_end_date)) ".
			"AND event.frequency='repeat_type_none' ".
			"AND event.item_id = particip.item_id ".
			"AND particip.plugin = 'calendar' ".
			"AND particip.participator = '%s' ".
			"ORDER BY event_start_date ";
	$queries['getDailyEvents']=
		"SELECT * FROM ".$tableName." WHERE ".
			"owner='%s' AND ".
			"event_start_date<='%s' AND (event_recurring_end_date >='%s' ".
			"OR event_end_date='1970-01-01 00:00:00' ".
			"OR event_end_date='0000-00-00 00:00:00'  ".
			"OR event_end_date='1970-01-01 01:00:00') ".
			"AND frequency='repeat_type_daily' ".
			"ORDER BY event_start_date ";
	$queries['getDailyEventsWithParticipation']=
		"SELECT event.* FROM brim_calendar_event event, brim_item_participation particip WHERE ".
			"event.owner='%s' AND ".
			"event.event_start_date<='%s' AND (event.event_recurring_end_date >='%s' ".
			"OR event.event_end_date='1970-01-01 00:00:00' ".
			"OR event.event_end_date='0000-00-00 00:00:00'  ".
			"OR event.event_end_date='1970-01-01 01:00:00') ".
			"AND event.frequency='repeat_type_daily' ".
			"AND event.item_id = particip.item_id ".
			"AND particip.plugin = 'calendar' ".
			"AND particip.participator = '%s' ".
			"ORDER BY event_start_date ";
	$queries['getYearlyEvents']=
		"SELECT * FROM ".$tableName." WHERE ".
			"owner='%s' ".
			"AND event_start_date<='%s' ".
			"AND event_start_date LIKE '%%-%s-%s %%' ".
			"AND frequency='repeat_type_yearly' ".
			"AND ".
			"(event_recurring_end_date >= '%s' OR ".
			"event_recurring_end_date = '1970-01-01 00:00:00' OR ".
			"event_recurring_end_date='0000-00-00 00:00:00' OR ".
			"event_recurring_end_date = '1970-01-01 01:00:00') ".
			"ORDER BY event_start_date ";
	$queries['getYearlyEventsWithParticipation']=
		"SELECT event.* FROM brim_calendar_event event, brim_item_participation particip WHERE ".
			"event.owner='%s' ".
			"AND event.event_start_date<='%s' ".
			"AND event.event_start_date LIKE '%%-%s-%s %%' ".
			"AND event.frequency='repeat_type_yearly' ".
			"AND ".
			"(event.event_recurring_end_date >= '%s' OR ".
			"event.event_recurring_end_date = '1970-01-01 00:00:00' OR ".
			"event.event_recurring_end_date = '0000-00-00 00:00:00' OR ".
			"event.event_recurring_end_date = '1970-01-01 01:00:00') ".
			"AND event.item_id = particip.item_id ".
			"AND particip.plugin = 'calendar' ".
			"AND particip.participator = '%s' ".
			"ORDER BY event_start_date ";
	$queries['getMonthlyEvents']=
		"SELECT * FROM ".$tableName." WHERE ".
			"owner='%s' AND ".
			"event_start_date<='%s' AND ".
			"(event_recurring_end_date >='%s' OR ".
			"event_recurring_end_date = '1970-01-01 00:00:00' OR ".
			"event_recurring_end_date = '0000-00-00 00:00:00' OR ".
			"event_recurring_end_date = '1970-01-01 01:00:00') ".
			"AND event_start_date LIKE '%%-%%-%s %%' ".
			"AND frequency='repeat_type_monthly' ".
			"ORDER BY event_start_date ";
	$queries['getMonthlyEventsWithParticipation']=
		"SELECT event.* FROM brim_calendar_event event, brim_item_participation particip WHERE ".
			"event.owner='%s' AND ".
			"event.event_start_date<='%s' AND ".
			"(event.event_recurring_end_date >='%s' OR ".
			"event.event_recurring_end_date = '1970-01-01 00:00:00' OR ".
			"event.event_recurring_end_date = '0000-00-00 00:00:00' OR ".
			"event.event_recurring_end_date = '1970-01-01 01:00:00') ".
			"AND event.event_start_date LIKE '%%-%%-%s %%' ".
			"AND event.frequency='repeat_type_monthly' ".
			"AND event.item_id = particip.item_id ".
			"AND particip.plugin = 'calendar' ".
			"AND particip.participator = '%s' ".
			"ORDER BY event_start_date ";
	$queries['getWeeklyEvents']=
		"SELECT * FROM ".$tableName." WHERE ".
			"owner='%s' AND ".
			"event_start_date<='%s' AND ".
			"(event_recurring_end_date >='%s' OR ".
			"event_recurring_end_date = '1970-01-01 00:00:00' OR ".
			"event_recurring_end_date = '0000-00-00 00:00:00' OR ".
			"event_recurring_end_date = '1970-01-01 01:00:00') ".
			"AND frequency='repeat_type_weekly' ".
			"AND by_what_value LIKE '%s' ".
			"ORDER BY event_start_date ";
	$queries['getWeeklyEventsWithParticipation']=
		"SELECT event.* FROM brim_calendar_event event, brim_item_participation particip WHERE ".
			"event.owner='%s' AND ".
			"event.event_start_date<='%s' AND ".
			"(event.event_recurring_end_date >='%s' OR ".
			"event.event_recurring_end_date = '1970-01-01 00:00:00' OR ".
			"event.event_recurring_end_date = '0000-00-00 00:00:00' OR ".
			"event.event_recurring_end_date = '1970-01-01 01:00:00') ".
			"AND event.frequency='repeat_type_weekly' ".
			"AND event.by_what_value LIKE '%s' ".
			"AND event.item_id = particip.item_id ".
			"AND particip.plugin = 'calendar' ".
			"AND particip.participator = '%s' ".
			"ORDER BY event_start_date ";
}
			
$queries['modifyItem']=
		"UPDATE ".$tableName." SET ".
		"parent_id=%d, ".
		"name='%s', ".
		"description='%s', ".
		"visibility='%s',  ".
		"category='%s',  ".
		"is_deleted=%d, ".
		"when_modified='%s', ".
		"location='%s', ".
		"organizer='%s', ".
		"priority =%d, ".
		"frequency='%s', ".
		"event_interval=%d, ".
		"by_what='%s', ".
		"by_what_value='%s', ".
		"event_start_date='%s', ".
		"event_end_date='%s', ".
		"event_recurring_end_date='%s', ".
		"event_colour='%s' ".
		"WHERE item_id=%d";
/*
$queries['getEventsWithParticipationOther']=
		"SELECT event.* FROM brim_calendar_event event, brim_item_participation particip WHERE ".
			"event.event_start_date<='%s' AND ((event.event_end_date >'%s' ".
			"OR event.event_end_date='1970-01-01 00:00:00'  ".
			//"OR event.event_end_date='0000-00-00 00:00:00'  ".
			"OR event.event_end_date='1970-01-01 01:00:00') OR ".
			"(event.event_start_date<='%s' AND event.event_end_date >='%s' AND ".
			"event.event_start_date = event.event_end_date)) ".
			"AND event.frequency='repeat_type_none' ".
		"JOIN brim_item_participation p ON (".
			"event.item_id = particip.item_id AND ".
			"particip.participator='%s' AND ".
			"particip.plugin='calendar' ) ".
			"ORDER BY event_start_date ";
*/

?>
