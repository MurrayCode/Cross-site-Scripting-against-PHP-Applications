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
class UserInstaller extends Installer
{
		/**
		 * Constructor with engine (i.e. postgres or mysql) and database
		 * connection as parameters
		 *
		 * @param string engine the engine that is used
		 * @param object db the database connection
		 */
		function UserInstaller ($engine, $db)
		{
			parent::Installer ($engine, $db);
			$this->tableName = 'brim_users';
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
				$dbFile .= '.brim_users.sql';
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
				count ($columnNames) == 8 &&
				$columnNames[0] == 'user_id' &&
				$columnNames[1] == 'loginname' &&
				$columnNames[2] == 'password' &&
				$columnNames[3] == 'name' &&
				$columnNames[4] == 'email' &&
				$columnNames[5] == 'description' &&
				$columnNames[6] == 'when_created' &&
				$columnNames[7] == 'last_login'
			);
		}

		/**
		 * Adds a user to the system. This function will hash the password
		 * and store the hashed version in the db
		 *
		 * @param string username the username
		 * @param string password the unencrypted password
		 * @param string description a description
		 */
		function addUser ($username, $password, $description)
		{
			$query  = "INSERT INTO ".$this->tableName;
			$query .= " (loginname, password, name, email, description, when_created, last_login) ";
			$query .= " VALUES ";
			$query .= "('".$username."', MD5('".$password."'), ";
			$query .= "'".$description."',null, null, null, null)";
			$this->db->Execute ($query) or
				die ($this->db->ErrorMsg ().$query);
		}

		/**
		 * Returns the userId based on the username
		 *
		 * @param string username the username for which we would like
		 * to retrieve the userId
		 * @return integer the userId that matches the username
		 */
		function getUserId ($username)
		{
			$query = "SELECT * from ".$this->tableName." WHERE loginname='";
			$query .= $username."'";
			$rs = $this->db->Execute ($query) or
				die ($this->db->ErrorMsg().$query);
			return $rs->fields['user_id'];
		}

		/**
		 * Retrieve all usernames
		 *
		 * @return array an array of strings (usernames)
		 */
		function getAllUserNames ()
		{
			$result = array ();
			$query = "SELECT loginname FROM ".$this->tableName;
			$rs = $this->db->Execute ($query) or
				die ($this->db->ErrorMsg().$query);
			while (!$rs->EOF)
			{
				$result [] = $rs->fields[0];
				$rs->MoveNext ();
			}
			return $result;
		}
}
?>
