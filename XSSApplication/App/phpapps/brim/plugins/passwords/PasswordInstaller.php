<?php

require_once 'framework/Installer.php';

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2004
 * @package org.brim-project.plugins.passwords
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class PasswordInstaller extends Installer
{
		function PasswordInstaller ($engine, $db)
		{
			parent::Installer ($engine, $db);
			$this->tableName = 'brim_passwords';
		}

		function install ()
		{
			if (function_exists ('mcrypt_encrypt') &&
				function_exists ('mcrypt_decrypt'))
			{
				if (!$this->findTable ())
				{
					$dbFile = 'plugins/passwords/sql/create.';
					$dbFile .= $this->engine;
					$dbFile .= '.brim_passwords.sql';
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
			else
			{
				echo ('Encryption (libmcrypt) extensions not found. Password plugin will not be installed');
			}
		}

		function isTableUpToDate ()
		{
			if (!$this->findTable ())
			{
				return false;
			}
			$columnNames =
				$this->db->MetaColumnNames ($this->tableName, true);
			return (
				count ($columnNames) == 14 &&
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
				$columnNames[11] == 'login' &&
				$columnNames[12] == 'password' &&
				$columnNames[13] == 'url'
			);
		}

    function updateTable ()
    {
        if (!$this->findTable ())
        {
            die ('Cannot update an non-existing table');
        }
        $columnNames =
            $this->db->MetaColumnNames ($this->tableName, true);
        if (count ($columnName == 13))
        {
            //
            // Upgrading from Brim 1.x.x to Brim 1.1.2
            //
			// Add a field password
            //
            echo 'Updating table brim_passwords. Adding column';
            $query = 'ALTER TABLE brim_passwords ADD COLUMN password TEXT AFTER login';
            $this->db->Execute ($query) or die ($this->db->ErrorMsg ());
        }
        else
        {
            die ('Cannot update. Unexpected columncount');
        }
    }

}
?>