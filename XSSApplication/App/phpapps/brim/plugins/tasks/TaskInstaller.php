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
 * @package org.brim-project.plugins.tasks
 * @subpackage install
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class TaskInstaller extends Installer
{
		function TaskInstaller ($engine, $db)
		{
			parent::Installer ($engine, $db);
			$this->tableName = 'brim_tasks';
		}

		function install ()
		{
			if (!$this->findTable ())
			{
				$dbFile = 'plugins/tasks/sql/create.';
				$dbFile .= $this->engine;
				$dbFile .= '.brim_tasks.sql';
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
		}

		function isTableUpToDate ()
		{
			if (!$this->findTable ())
			{
				return false;
			}
			$columnNames =
				$this->db->MetaColumnNames ('brim_tasks', true);
			$metaColumns = $this->db->MetaColumns ('brim_tasks', true);
			if (!$this->isDeletedHasProperDefault ())
			{
				return false;
			}
			return (
				count ($columnNames) == 17 &&
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
				$columnNames[11] == 'priority' &&
				$columnNames[12] == 'start_date' &&
				$columnNames[13] == 'end_date' &&
				$columnNames[14] == 'status' &&
				$columnNames[15] == 'percent_complete' &&
				$columnNames[16] == 'is_finished' 
			);
		}

		function updateTable ()
		{
			$metaColumns = $this->db->MetaColumns ('brim_tasks', true);
			if (!$this->isDeletedHasProperDefault ())
			{
				echo 'Updating tasks plugin. Setting default value of "is_deleted" to 0<br />';
				$query = 'ALTER TABLE brim_tasks ALTER COLUMN is_deleted SET DEFAULT 0';
				$this->db->Execute ($query) or die ($this->db->ErrorMsg ().$query);

				echo 'Updating tasks plugin. Modifying existing null values<br />';
				$query = 'UPDATE brim_tasks SET is_deleted=0 WHERE is_deleted IS NULL';
				$this->db->Execute ($query) or die ($this->db->ErrorMsg ().$query);
			}
			else
			{
				die ('Want to update, but no update procedure found');
			}
		}

		function isDeletedHasProperDefault ()
		{
			$metaColumns = $this->db->MetaColumns ('brim_tasks', true);
			return !(
					$metaColumns ['IS_DELETED']->has_default == ''
					||
					!isset ($metaColumns ['IS_DELETED']->default_value)
					||
					$metaColumns ['IS_DELETED']->default_value == ''
				);
		}
}
?>
