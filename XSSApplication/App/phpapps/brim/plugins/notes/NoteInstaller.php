<?php

require_once 'framework/Installer.php';

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.plugins.notes
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class NoteInstaller extends Installer
{
		function NoteInstaller ($engine, $db)
		{
			parent::Installer ($engine, $db);
			$this->tableName = 'brim_notes';
		}

		function install ()
		{
			if (!$this->findTable ())
			{
				$dbFile = 'plugins/notes/sql/create.';
				$dbFile .= $this->engine;
				$dbFile .= '.'.$this->tableName.'.sql';
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
				$this->db->MetaColumnNames ($this->tableName, true);
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
				$columnNames[11] == 'position' 
			);
		}

        function updateTable ()
        {
            $metaColumns = $this->db->MetaColumns ('brim_notes', true);
            if (!$this->isDeletedHasProperDefault ())
            {
                echo 'Updating notes plugin. Setting default value of "is_deleted" to 0<br />';
                $query = 'ALTER TABLE brim_notes ALTER COLUMN is_deleted SET DEFAULT 0';
                $this->db->Execute ($query) or die ($this->db->ErrorMsg ().$query);

                echo 'Updating notes plugin. Modifying existing null values<br />';
                $query = 'UPDATE brim_notes SET is_deleted=0 WHERE is_deleted IS NULL';
                $this->db->Execute ($query) or die ($this->db->ErrorMsg ().$query);
            }
			if (count ($metaColumns) == 11)
			{
				echo 'Adding position column to notes plugin<br />';
				if ($this->engine == 'postgres')
				{
					$query = 'ALTER TABLE brim_notes ADD COLUMN position CHAR(70) NOT NULL';
				}
				else
				{
					$query = 'ALTER TABLE brim_notes ADD COLUMN position VARCHAR(70) NOT NULL default \'\'';
				}
                $this->db->Execute ($query) or die ($this->db->ErrorMsg ().$query);
			}
            else
            {
                die ('Want to update, but no update procedure found');
            }
        }

        function isDeletedHasProperDefault ()
        {
            $metaColumns = $this->db->MetaColumns ('brim_notes', true);
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
