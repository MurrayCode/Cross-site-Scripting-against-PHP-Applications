<?php

require_once 'framework/Installer.php';

/**
 * The preferenceInstaller takes care of installing/upgrading the preference
 * related database tables.
 *
 * This file is part of the Brim project.
 *
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 * @subpackage install
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class PreferenceInstaller extends Installer
{
		/**
		 * Constructor with engine (i.e. postgres or mysql) and database
		 * connection as parameters
		 *
		 * @param string engine the engine that is used
		 * @param object db the database connection
		 */
		function PreferenceInstaller ($engine, $db)
		{
			parent::Installer ($engine, $db);
			$this->tableName = 'brim_user_preferences';
		}

		/**
		 * The actual install routine. Calls the appropriate sql scripts
		 * if the table is not yet found or upgrades the table if found
		 * out-of-date
		 */
		function install ()
		{
			if (!$this->findTable ())
			{
				$dbFile = 'framework/sql/create.';
				$dbFile .= $this->engine;
				$dbFile .= '.'.$this->tableName.'.sql';
				$this->createTable ($dbFile);
			}
			else
			{
				if (!$this->isTableUpToDate ())
				{
					die ('Table '.$this->tableName.' found but does not appear to be up-to-date and no upgrade procedure is found!');
				}
			}
		}

		/**
		 * Function to check whether this table is up to date.
		 *
		 * @return boolean <code>true</code> if the table is
		 * up-to-date or <code>false</code> if the table is not
		 * up-to-date or not found.
		 */
		function isTableUpToDate ()
		{
			$columnNames =
				$this->db->MetaColumnNames ($this->tableName,
					true);
			return (
				count ($columnNames) == 12 &&
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
				$columnNames[11] == 'value'
			);
		}

		/**
		 * Insert a name/value pair (a preference) for a user
		 *
		 * @param string username the username
		 * @param string name the name of the preference
		 * @param string value the value of the preference
		 */
		function insertPreference ($username, $name, $value)
		{
			$query  = "INSERT INTO ".$this->tableName;
			$query .= " (owner, parent_id, is_parent, name, description, visibility, category, is_deleted, when_created, when_modified, value) ";
			$query .= "VALUES ('".$username."', 0, 0, '".$name."'";
			$query .= ", null, 'private', ";
		   	$query .= "null, 0, null, null, '".$value."')";
			$this->db->Execute ($query)
				or die ($this->db->ErrorMsg ().$query);
		}

		/**
		 * Get the value of a preference for a specific user
		 *
		 * @param string username the username
		 * @param string name the name of the preference
		 * @return string the value of the preference
		 */
		function getPreference ($username, $name)
		{
			$query  = "SELECT value FROM ".$this->tableName.' ';
			$query .= "WHERE name='".$name."' ";
			$query .= "AND owner='".$username."'";
			$rs = $this->db->Execute ($query) or
				die ($this->db->ErrorMsg ().$query);
			return ($rs->fields[0]);
		}
}
?>
