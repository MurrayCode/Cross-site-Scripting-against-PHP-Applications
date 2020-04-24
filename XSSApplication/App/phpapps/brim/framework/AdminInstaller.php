<?php

require_once 'framework/Installer.php';

/**
 * This file takes care of the installation/upgrades w.r.t. the admin related
 * tables in brim.
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
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class AdminInstaller extends Installer
{
	/**
	 * Constructor with engine (i.e. postgres or mysql) and database
	 * connection as parameters
	 *
	 * @param string engine the engine that is used
	 * @param object db the database connection
	 */
	function AdminInstaller ($engine, $db)
	{
		parent::Installer ($engine, $db);
		$this->tableName = 'brim_admin';
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
				die ('Table '.$this->tableName.
					' found but does not appear to be up-to-date and no upgrade procedure is found!');
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
		if (!$this->findTable ())
		{
			return false;
		}
		$columnNames =
		$this->db->MetaColumnNames ($this->tableName,
				true);
		return (
			count ($columnNames) == 2 &&
			$columnNames[0] == 'name' &&
			$columnNames[1] == 'value'
		);
	}

	/**
	 * Set a name/value pair. This implies an update if there is already
	 * a pair with the same name, it is an add otherwise
	 *
	 * @param string name the name
	 * @param string value the value
	 */
	function set ($name, $value)
	{
		if ($this->get ($name) != null)
		{
			$this->update ($name, $value);
		}
		else
		{
			$this->add ($name, $value);
		}
	}


	/**
	 * Update a name/value pair
	 *
	 * @param string name the name
	 * @param string value the value
	 * @private
	 */
	function update ($name, $value)
	{
		$query = "UPDATE ".$this->tableName." SET value='";
		$query .= $value."' WHERE name='".$name."'";
		$this->db->execute ($query) or die ($this->db->ErrorMsg ().' '.$query);
	}

	/**
	 * Add a name/value pair
	 *
	 * @param string name the name
	 * @param string value the value
	 * @private
	 */
	function add ($name, $value)
	{
		$query = "INSERT INTO ".$this->tableName." VALUES ('";
		$query .= $name;
		$query .= "', '";
		$query .= $value;
		$query .= "')";
		$this->db->execute ($query) or die ($this->db->ErrorMsg ().' '.$query);
	}

	/**
	 * Get the value associated with the given name
	 *
	 * @param string name the name of the name/value pair
	 * @return string the value of the name/value pair
	 */
	function get ($name)
	{
		$query = "SELECT * FROM ".$this->tableName." WHERE name='".$name."'";
		$rs = $this->db->Execute ($query)
			or die ($this->db->ErrorMsg ().' '.$query);
		return $rs->fields['value'];
	}

	/**
	 * INSERT the necessary values first, otherwise an UPDATE
	 * afterwards fails
	 *
	 * @param string dbFile the name of the database file
	 */
	function createTable ($dbFile)
	{
		parent::createTable ($dbFile);
		$query = "INSERT INTO ".$this->tableName." VALUES ('allow_account_creation', 0);";
		$this->db->Execute ($query) or
			die ($this->db->ErrorMsg ().$query);
				$query = "INSERT INTO ".$this->tableName." VALUES ('installation_path', '');";
		$this->db->Execute ($query) or
			die ($this->db->ErrorMsg ().$query);
				$query = "INSERT INTO ".$this->tableName." VALUES ('admin_email', '');";
		$this->db->Execute ($query) or
			die ($this->db->ErrorMsg ().$query);
	}
}
?>
