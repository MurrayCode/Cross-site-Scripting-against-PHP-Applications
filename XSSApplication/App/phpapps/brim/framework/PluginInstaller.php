<?php

require_once 'framework/Installer.php';

/**
 * This installer installs/upgrades all tables related to the plugin activation
 * (not the tables concerning the plugins themselves!) as well as activates
 * certain plugins. This can be useful if a new plugin is added, so you can
 * let the installation script activate this plugin for all existing users.
 *
 * This file is part of the Brim project.
 *
 * This installer DOES NOT install the plugins, it installs
 * the table which will be used for the plugins!!!!
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
class PluginInstaller extends Installer
{
	/**
	 * Constructor with engine (i.e. postgres or mysql) and database
	 * connection as parameters
	 *
	 * @param string engine the engine that is used
	 * @param object db the database connection
	 */
	function PluginInstaller ($engine, $db)
	{
		parent::Installer ($engine, $db);
		$this->tableName = 'brim_plugin_settings';
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
		if (!$this->findTable ())
		{
			return false;
		}
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
	 * Returns whether a specific plugin for a user is
	 * set. This can be useful when adding new plugins
	 * during the installation and you wish the activate
	 * this plugin for all existing users
	 *
	 * @param string username the name of the user for which
	 * we would like to check whether the plugin is set
	 * @param string plugin the plugin name
	 * @return boolean <code>true</code> if the plugin is
	 * set for this user, <code>false</code> otherwise
	 * @todo perhaps move the queries to the sql directory?
	 */
	function isPluginSet ($username, $plugin)
	{
		$query  = "SELECT * FROM ".$this->tableName.' ';
		$query .= "WHERE owner='".$username."' ";
		$query .= "AND name='".$plugin."'";
		$rs = $this->db->execute ($query) or
			die ($this->db->ErrorMsg ()." ".$query);
		return isset ($rs->fields[0]);
	}

	/**
	 * Activate a plugin for a user
	 *
	 * @param string username the name of the user for which
	 * we would like to activate the plugin
	 * @param string plugin the plugin name
	 * @todo perhaps move the queries to the sql directory?
	 */
	function activatePlugin ($username, $plugin)
	{
		$query  = "INSERT INTO ".$this->tableName;
		$query .= " (owner, parent_id, is_parent, name, description, visibility, category, is_deleted, when_created, when_modified, value) ";
		$query .= "VALUES ('".$username."', 0, 0, '".$plugin."', null, ".
		"'private', null, 0, null, null, 'true')";
		$this->db->execute ($query) or die ($this->db->ErrorMsg ()." ".$query);
	}
}
?>
