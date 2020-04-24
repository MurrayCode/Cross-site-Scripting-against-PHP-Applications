<?php

/**
 * The basic installer, an abstract base class for the other installers
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class Installer
{
	/**
	 * Mysql or postgres or...
	 * @var string
	 */
	var $engine;

	/**
	 * The handle to the database
	 * @var object
	 */
	var $db;

	/**
	 * The name of the installation table.
	 * Must be set by each subclass in order to set the standard
	 * (on owner and parentId) afterwards)
	 * @var string
	 */
	var $tableName;

	/**
	 * Constructor with engine (i.e. postgres or mysql) and database
	 * connection as parameters
	 *
	 * @param string engine the engine that is used
	 * @param object db the database connection
	 */
	function Installer ($dbEngine, $database)
	{
		$this->engine = $dbEngine;
		$this->db = $database;
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
		die ('Find table is an abstract function!!');
	}

	/**
	 * This function returns whether its main table can be found
	 *
	 * @return boolean <code>true</code> if it can find its table,
	 * <code>false</code> otherwise
	 */
	function findTable ()
	{
			return $this->tableExist ($this->tableName);
	}

	/**
	 * Returns whether the table with the specified name
	 * exists.
	 *
	 * @return boolean <code>true</code> if the table exists,
	 * <code>false</code> otherwise
	 */
	function tableExist ($tableName)
	{
		$tables = $this->db->MetaTables ();
		for ($i=0; $i<count ($tables); $i++)
		{
			if ($tables[$i] == $tableName)
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Read the contents of a file, also compatible
	 * with older versions of PHP
	 *
	 * @param string filename the name of the file to read
	 * @return string the contents of the file
	 */
	function read_file ($filename)
	{
		$result = '';
		$handle = fopen ($filename, "rb");
		$result = fread ($handle, filesize ($filename));
		fclose ($handle);
		return $result;
	}

	/**
	 * Create a table based on the definitions as found
	 * in the specified filename
	 *
	 * @param string queryFileName the filename of the
	 * file that contains the table definitions
	 */
	function createTable ($queryFileName)
	{
		$query = $this->read_file ($queryFileName);
		$this->db->Execute (stripslashes ($query)) or
			die ($query.' '.$this->db->ErrorMsg ());
		$this->tableFound = true;
	}

	/**
	 * Create a standard index (owner, parentId) on this
	 * installers' main table
	 *
	 * @todo should we include the name in the index?
	 * the primary key (itemId) is obviously not needed
	 * @todo should we move the query to the sql directory?
	 */
	function createStandardIndex ()
	{
		$query = 'CREATE INDEX '.$this->tableName.'_index ';
		$query .= 'ON '.$this->tableName.' ';
		$query .= '(owner, parent_id)';
		//
		// Ignore a duplicate key...
		// TODO FIXME BARRY TBD Add a check
		//
		if (!$this->db->Execute ($query))
		{
			echo ('Error creating index. The database reported the following problem: ');
			echo ('<code>'.$this->db->ErrorMsg().'</code><br />');
			echo ('Indexes are not required so I will just continue....<br />');
		}
	}
}
?>
