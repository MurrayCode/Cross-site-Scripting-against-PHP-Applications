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
 * @package org.brim-project.plugins.contacts
 * @subpackage install
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class ContactInstaller extends Installer
{
		function ContactInstaller ($engine, $db)
		{
			parent::Installer ($engine, $db);
			$this->tableName = 'brim_contacts';
		}

		function install ()
		{
			if (!$this->findTable ())
			{
				$dbFile = 'plugins/contacts/sql/create.';
				$dbFile .= $this->engine;
				$dbFile .= '.brim_contacts.sql';
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
				$this->db->MetaColumnNames ('brim_contacts', true);
			return (
				count ($columnNames) == 27 &&
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
				$columnNames[11] == 'alias' &&
				$columnNames[12] == 'address' &&
				$columnNames[13] == 'birthday' &&
				$columnNames[14] == 'mobile' &&
				$columnNames[15] == 'faximile' &&
				$columnNames[16] == 'tel_home' &&
				$columnNames[17] == 'tel_work' &&
				$columnNames[18] == 'organization' &&
				$columnNames[19] == 'org_address' &&
				$columnNames[20] == 'job' &&
				$columnNames[21] == 'email1' &&
				$columnNames[22] == 'email2' &&
				$columnNames[23] == 'email3' &&
				$columnNames[24] == 'webaddress1' &&
				$columnNames[25] == 'webaddress2' &&
				$columnNames[26] == 'webaddress3'
			);
		}

        function updateTable ()
        {
            $metaColumns = $this->db->MetaColumns ('brim_contacts', true);
            if (!$this->isDeletedHasProperDefault ())
            {
                echo 'Updating contacts plugin. Setting default value of "is_deleted" to 0<br />';
                $query = 'ALTER TABLE brim_contacts ALTER COLUMN is_deleted SET DEFAULT 0';
                $this->db->Execute ($query) or die ($this->db->ErrorMsg ().$query);

                echo 'Updating contacts plugin. Modifying existing null values<br />';
                $query = 'UPDATE brim_contacts SET is_deleted=0 WHERE is_deleted IS NULL';
                $this->db->Execute ($query) or die ($this->db->ErrorMsg ().$query);
            }
            else
            {
                die ('Want to update, but no update procedure found');
            }
        }

        function isDeletedHasProperDefault ()
        {
            $metaColumns = $this->db->MetaColumns ('brim_contacts', true);
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
