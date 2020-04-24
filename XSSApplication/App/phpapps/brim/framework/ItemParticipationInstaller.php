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
 * @author Barry Nauta - 29 May 2006
 * @package org.brim-project.framework
 * @subpackage install
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class ItemParticipationInstaller extends Installer
{
	var $tempTableName;

		/**
		 * Constructor with engine (i.e. postgres or mysql) and database
		 * connection as parameters
		 *
		 * @param string engine the engine that is used
		 * @param object db the database connection
		 */
		function ItemParticipationInstaller ($engine, $db)
		{
			parent::Installer ($engine, $db);
			$this->tableName = 'brim_item_participation';
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
				count ($columnNames) == 6 &&
				$columnNames[0] == 'item_id' &&
				$columnNames[1] == 'owner' &&
				$columnNames[2] == 'participator' &&
				$columnNames[3] == 'plugin' &&
				$columnNames[4] == 'participation_rights' &&
				$columnNames[5] == 'activation_code'
			);
		}
}
?>
