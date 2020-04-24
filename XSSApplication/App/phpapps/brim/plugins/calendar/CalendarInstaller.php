<?php

require_once 'framework/Installer.php';

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.calendar
 * @subpackage install
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class CalendarInstaller extends Installer
{
		function CalendarInstaller ($engine, $db)
		{
			parent::Installer ($engine, $db);
			$this->tableName = 'brim_calendar_event';
		}

		function install ()
		{
			if (!$this->findTable ())
			{
				$dbFile = 'plugins/calendar/sql/create.';
				$dbFile .= $this->engine;
				$dbFile .= '.brim_calendar_event.sql';
				$this->createTable ($dbFile);
				$this->createStandardIndex ();
			}
			else
			{
				if (!$this->isTableUpToDate ())
				{
					$this->updateTable ();
				}
			}
			$this->tableName = 'brim_calendar_event_reminder';
			if (!$this->findTable ())
			{
				$dbFile = 'plugins/calendar/sql/create.';
				$dbFile .= $this->engine;
				$dbFile .= '.brim_calendar_event_reminder.sql';
				$this->createTable ($dbFile);
				$this->createStandardIndex ();
			}
			else
			{
				if (!$this->isTableUpToDate ())
				{
					die ('Table is not up to date, but no update procedure is found!!!');
				}
			}
		}

		function isTableUpToDate ()
		{
			if (!$this->findTable ())
			{
				return false;
			}
			if ($this->tableName == 'brim_calendar_event')
			{
				$columnNames =
					$this->db->MetaColumnNames ('brim_calendar_event',
					true);
				return (
					count ($columnNames) == 22 &&
					$columnNames[0] == 'item_id' &&
					$columnNames[1] == 'owner' &&
					$columnNames[2] == 'parent_id' &&
					$columnNames[3] == 'is_parent' &&
					$columnNames[4] == 'name' &&
					$columnNames[5] == 'description' &&
					$columnNames[6] == 'visibility' &&
					$columnNames[7] == 'category' &&
					$columnNames[8] == 'is_deleted' &&
					$columnNames[9] == 'when_created' &&
					$columnNames[10] == 'when_modified' &&
					$columnNames[11] == 'location' &&
					$columnNames[12] == 'organizer' &&
					$columnNames[13] == 'priority' &&
					$columnNames[14] == 'frequency' &&
					$columnNames[15] == 'event_interval' &&
					$columnNames[16] == 'by_what' &&
					$columnNames[17] == 'by_what_value' &&
					$columnNames[18] == 'event_start_date' &&
					$columnNames[19] == 'event_end_date' &&
					$columnNames[20] == 'event_recurring_end_date' &&
					$columnNames[21] == 'event_colour'
				);
			}
			else if ($this->tableName == 'brim_calendar_event_reminder')
			{
				$columnNames =
					$this->db->MetaColumnNames ('brim_calendar_event_reminder',
					true);
				return (
					count ($columnNames) == 15 &&
					$columnNames[0] == 'item_id' &&
					$columnNames[1] == 'owner' &&
					$columnNames[2] == 'parent_id' &&
					$columnNames[3] == 'is_parent' &&
					$columnNames[4] == 'name' &&
					$columnNames[5] == 'description' &&
					$columnNames[6] == 'visibility' &&
					$columnNames[7] == 'category' &&
					$columnNames[8] == 'is_deleted' &&
					$columnNames[9] == 'when_created' &&
					$columnNames[10] == 'when_modified' &&
					$columnNames[11] == 'event_id' &&
					$columnNames[12] == 'timespan' &&
					$columnNames[13] == 'reminder_time' &&
					$columnNames[14] == 'when_sent'
				);
			}
		}

		function updateTable ()
		{
			if (!$this->findTable ())
			{
				return false;
			}
			$columnNames =
				$this->db->MetaColumnNames ('brim_calendar_event',
				true);
			if (count ($columnNames) == 21)
			{
				echo 'Updating table brim_calendar_event. Adding column "event_colour"';
				$query = 'ALTER TABLE brim_calendar_event ADD COLUMN event_colour TEXT';
				$this->db->Execute ($query) or die ($this->db->ErrorMsg ());
			}
			else
			{
				die ('CalendarInstaller. Want to update, but no update procedure found');
			}
		}
}
?>