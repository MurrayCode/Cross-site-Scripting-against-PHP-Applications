<?php

require_once 'framework/Installer.php';

/**
 * This file is part of the Brim project.
 *
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - February 2006
 * @package org.brim-project.framework
 * @subpackage install
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class TempUserInstaller extends Installer
{

	/**
	 * Constructor with engine (i.e. postgres or mysql) and database
	 * connection as parameters
	 *
	 * @param string engine the engine that is used
	 * @param object db the database connection
	 */
	function TempUserInstaller ($engine, $db)
	{
		parent::Installer ($engine, $db);
		$this->tableName = 'brim_temp_users';
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
			$dbFile .= '.brim_temp_users.sql';
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
		if (!$this->findTable ())
		{
			return false;
		}
		$columnNames =
			$this->db->MetaColumnNames ($this->tableName,
				true);
		return (
			count ($columnNames) == 9 &&
			$columnNames[0] == 'user_id' &&
			$columnNames[1] == 'loginname' &&
			$columnNames[2] == 'password' &&
			$columnNames[3] == 'name' &&
			$columnNames[4] == 'email' &&
			$columnNames[5] == 'description' &&
			$columnNames[6] == 'when_created' &&
			$columnNames[7] == 'last_login' &&
			$columnNames[8] == 'temp_password'
		);
	}
}
?>
