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
 * @package org.brim-project.plugins.bookmarks
 * @subpackage install
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class BookmarkInstaller extends Installer
{
	function BookmarkInstaller ($engine, $db)
	{
		parent::Installer ($engine, $db);
		$this->tableName = 'brim_bookmarks';
	}

	function install ()
	{
		if (!$this->findTable ())
		{
			$dbFile = 'plugins/bookmarks/sql/create.';
			$dbFile .= $this->engine;
			$dbFile .= '.brim_bookmarks.sql';
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
		if (!$this->isDeletedHasProperDefault ())
		{
			return false;
		}
		$columnNames =
			$this->db->MetaColumnNames ('brim_bookmarks', true);
		$metaColumns = $this->db->MetaColumns ('brim_bookmarks', true);
		$locatorColumn = $metaColumns['LOCATOR'];
		$type = $locatorColumn->type;

		return (
			$type != 'varchar' &&
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
			$columnNames[11] == 'when_visited' &&
			$columnNames[12] == 'locator' &&
			$columnNames[13] == 'visit_count' &&
			$columnNames[14] == 'favicon'
		);
	}

	function updateTable ()
	{
		if (!$this->findTable ())
		{
			die ('Cannot update an non-existing table');
		}
		$columnNames =
			$this->db->MetaColumnNames ('brim_bookmarks', true);
		//die (print_r (count ($columnNames)));
		if (count ($columnNames) == 14)
		{
			//
			// Upgrading from Brim 1.0.x to Brim 1.1
			//
			// TEXT is unlimited on Postgres and can store up to 65000 bytes in MySQL
			//
			echo 'Updating table brim_bookmarks. Adding column "favicon"';
			$query = 'ALTER TABLE brim_bookmarks ADD COLUMN favicon TEXT';
			$this->db->Execute ($query) or die ($this->db->ErrorMsg ());
		}
		else if (!$this->isDeletedHasProperDefault ())
		{
			echo 'Updating bookmarks plugin. Setting default value of "is_deleted" to 0<br />';
			$query = 'ALTER TABLE brim_bookmarks ALTER COLUMN is_deleted SET DEFAULT 0';
			$this->db->Execute ($query) or die ($this->db->ErrorMsg ().$query);

			echo 'Updating bookmarks plugin. Modifying existing null values<br />';
			$query = 'UPDATE brim_bookmarks SET is_deleted=0 WHERE is_deleted IS NULL';
			$this->db->Execute ($query) or die ($this->db->ErrorMsg ().$query);
		}
		else
		{
			die ('Cannot update. Unexpected columncount');
		}
	}

	function isDeletedHasProperDefault ()
	{
		$metaColumns = $this->db->MetaColumns ('brim_bookmarks', true);
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
