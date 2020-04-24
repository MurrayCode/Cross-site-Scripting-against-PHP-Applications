<?php

require_once('../ext/adodb/adodb.inc.php');
include('../framework/configuration/databaseConfiguration.php');

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - March 2006
 * @package org.brim-project.framework
 * @subpackage tools
 *
 * @copyright Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

//
// Setup the database connection
//
$db = NewADOConnection ($engine);
$db->Connect ($host, $user, $password, $database)
	or die ($db->ErrorMsg());

$tableNames = array ();
$tableNames [] = 'bk_banks';
$tableNames [] = 'bk_cancellists';
$tableNames [] = 'bk_secrets';
$tableNames [] = 'bookmarks';
$tableNames [] = 'calendar_event';
$tableNames [] = 'contacts';
$tableNames [] = 'news';
$tableNames [] = 'notes';
$tableNames [] = 'passwords';
$tableNames [] = 'plugin_settings';
$tableNames [] = 'user_preferences';
$tableNames [] = 'todos';

function tableExists ($db, $tableName)
{
	$tables = $db->MetaTables ();
	for ($i=0; $i<count ($tables); $i++)
	{
		if ($tables[$i] == $tableName)
		{
			return true;
		}
	}
	return false;
}

function columnExists ($db, $tableName, $columnName)
{
	$columnNames = $db->MetaColumnNames ($tableName, true);
	return (in_array ($columnName, $columnNames));
}
//
// Very simple script. It just tries to rename a bunch of tables,
// add some columns and stops on failure (which also makes it more
// or less save for consecutive executions)
//
echo '
<h1>Booby2Brim</h1>
<p>
This script will perform an upgrade from Booby 2 Brim.
This script only works from the latest Booby version, if you do not
have Booby version 1.0.1, upgrade to this version first before running this script.
</p>
<p>
Make sure that your database credentials (like database name,
username, password and databasetype) are the same as the ones
for Booby. This script will convert all table names, but does not
change the name of the database.
</p>
<p>
Make a backup of your database, use at your own risk!
</p>
<p>
This script will show lots of output. At the end you should see
DONE, if not, check for errors
</p>
';
removeObsolete ($db);
prepareOldBoobyBanking ($db);
changeCommonAttributes ($db, $tableNames);
changeBoobyAdmin ($db);
changeBoobyUsers ($db);
changeBrimBanking ($db);
changeBrimTodos ($db);
changeBrimPasswords ($db);
changeBrimBookmarks ($db);
changeBrimUserPreferences ($db);
changeBrimCalendarEvent ($db);
echo '
<h1>DONE!!!!!</h1>
Please execute the installscript in the application root to install
the new plugins.
';

/**
 * Remove obsolete tables
 */
function removeObsolete ($db)
{
	if (tableExists ($db, 'booby_access_control_list'))
	{
		$query  = 'DROP TABLE booby_access_control_list ';
		$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
		echo 'Dropped obsolete table \'booby_access_control_list<br />';
	}
}
/**
 * Rename the booby_admin table
 */
function changeBoobyAdmin ($db)
{
	if (tableExists ($db, 'booby_admin'))
	{
		$query  = 'ALTER TABLE booby_admin ';
		$query .= 'RENAME TO brim_admin';
		$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
		echo 'Renamed table \'booby_admin\' to \'brim_admin\'<br />';
	}
	else
	{
		echo 'Table booby_admin does not exist<br />';
	}
}
/**
 * Rename the booby_users table
 */
function changeBoobyUsers ($db)
{
	if (tableExists ($db, 'booby_users'))
	{
		$query  = 'ALTER TABLE booby_users ';
		$query .= 'RENAME TO brim_users';
		$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
		echo 'Renamed table \'booby_users\' to \'brim_users\'<br />';

		$query  = 'ALTER TABLE brim_users ';
		$query .= ' CHANGE userId user_id INT NOT NULL AUTO_INCREMENT';
		$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
		echo 'Changed column \'userId\' to user_id<br />';

		$query  = 'ALTER TABLE brim_users ';
		$query .= ' CHANGE lastLogin last_login DATETIME DEFAULT null';
		$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
		echo 'Changed column \'lastLogin\' to last_login<br />';
	}
	else
	{
		echo 'Table booby_users does not exist<br />';
	}
}

/**
 * Rename brim_todos to brim_tasks (booby_todos is already renamed
 * to brim_todos in the function 'changeCommonAttributes)
 */
function changeBrimTodos ($db)
{
	if (tableExists ($db, 'brim_todos'))
	{
		$query = 'ALTER TABLE brim_todos ';
		$query .= 'RENAME TO brim_tasks';
		$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
		echo 'Renamed table \'brim_todos\' to \'brim_tasks\'<br />';

		$query  = 'ALTER TABLE brim_tasks ';
		$query .= ' CHANGE startDate start_date DATETIME DEFAULT null';
		$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
		echo 'Changed column \'startDate\' to start_date<br />';

		$query  = 'ALTER TABLE brim_tasks ';
		$query .= ' CHANGE endDate end_date DATETIME DEFAULT null';
		$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
		echo 'Changed column \'endDate\' to end_date<br />';

		$query  = 'ALTER TABLE brim_tasks ';
		$query .= ' CHANGE percentComplete percent_complete INT(11) DEFAULT 0';
		$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
		echo 'Changed column \'percentComplete\' to percent_complete<br />';

		$query  = 'ALTER TABLE brim_tasks ';
		$query .= ' CHANGE isFinished is_finished tinyint(1) DEFAULT 0';
		$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
		echo 'Changed column \'isFinished\' to is_finished<br />';
	}
	else
	{
		echo 'Table brim_todos does not exist<br />';
	}
}

function changeBrimPasswords ($db)
{
	if (tableExists ($db, 'brim_passwords'))
	{
		$query  = 'ALTER TABLE brim_passwords ';
		$query .= ' ADD COLUMN login TEXT DEFAULT null AFTER when_modified';
		$db->Execute ($query) or die ($db->ErrorMsg ());
		echo 'Added column \'login\' to brim_passwords<br />';

		$query  = 'ALTER TABLE brim_passwords ';
		$query .= ' ADD COLUMN url TEXT DEFAULT null AFTER login';
		$db->Execute ($query) or die ($db->ErrorMsg ());
		echo 'Added column \'login\' to brim_passwords<br />';
	}
	else
	{
		echo 'Table brim_passwords does not exist<br />';
	}
}

/**
 * Rename the columns visitCount in bookmarks to visit_count
 */
function changeBrimBookmarks ($db)
{
	if (columnExists ($db, 'brim_bookmarks', 'visitCount'))
	{
		$query  = 'ALTER TABLE brim_bookmarks ';
		$query .= ' CHANGE visitCount visit_count int(11) DEFAULT 0';
		$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
		echo 'Changed column \'visitCount\' to visit_count<br />';
	}
	else
	{
		echo 'Column visitCount does not exists for brim_bookmarks<br />';
	}
}

/**
 *
 */
function changeBrimCalendarEvent ($db)
{
	if (columnExists ($db, 'brim_calendar_event', 'eventInterval'))
	{
		$query  = 'ALTER TABLE brim_calendar_event ';
		$query .= ' CHANGE eventInterval event_interval tinyint(1) DEFAULT 0';
		$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
		echo 'Changed column \'eventInterval\' to event_interval<br />';
	}
	else
	{
		echo 'Column eventInterval does not exists for brim_calendar_event<br />';
	}

	if (columnExists ($db, 'brim_calendar_event', 'byWhat'))
	{
		$query  = 'ALTER TABLE brim_calendar_event ';
		$query .= ' CHANGE byWhat by_what TEXT DEFAULT null';
		$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
		echo 'Changed column \'byWhat\' to by_what<br />';
	}
	else
	{
		echo 'Column byWhat does not exists for brim_calendar_event<br />';
	}

	if (columnExists ($db, 'brim_calendar_event', 'byWhatValue'))
	{
		$query  = 'ALTER TABLE brim_calendar_event ';
		$query .= ' CHANGE byWhatValue by_what_value TEXT DEFAULT null';
		$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
		echo 'Changed column \'byWhatValue\' to by_what_value<br />';
	}
	else
	{
		echo 'Column byWhatValue does not exists for brim_calendar_event<br />';
	}

	if (columnExists ($db, 'brim_calendar_event', 'eventStartDate'))
	{
		$query  = 'ALTER TABLE brim_calendar_event ';
		$query .= ' CHANGE eventStartDate event_start_date DATETIME DEFAULT null';
		$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
		echo 'Changed column \'eventStartDate\' to event_start_date<br />';
	}
	else
	{
		echo 'Column eventStartDate does not exists for brim_calendar_event<br />';
	}

	if (columnExists ($db, 'brim_calendar_event', 'eventEndDate'))
	{
		$query  = 'ALTER TABLE brim_calendar_event ';
		$query .= ' CHANGE eventEndDate event_end_date DATETIME DEFAULT null';
		$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
		echo 'Changed column \'eventEndDate\' to event_end_date<br />';
	}
	else
	{
		echo 'Column eventEndDate does not exists for brim_calendar_event<br />';
	}

	if (columnExists ($db, 'brim_calendar_event', 'eventRecurringEndDate'))
	{
		$query  = 'ALTER TABLE brim_calendar_event ';
		$query .= ' CHANGE eventRecurringEndDate event_recurring_end_date DATETIME DEFAULT null';
		$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
		echo 'Changed column \'eventRecurringEndDate\' to event_recurring_end_date<br />';
	}
	else
	{
		echo 'Column eventRecurringEndDate does not exists for brim_calendar_event<br />';
	}
}

function changeCommonAttributes ($db, $tableNames)
{
	foreach ($tableNames as $tableName)
	{
		$oldName = 'booby_'.$tableName;
		$newName = 'brim_'.$tableName;

		$metaColumns = $db->MetaColumns ($oldName, true);
		//
		// The IS_DELETED indicates a new installation. Booby
		// does not have this field, all items in Brim will
		// have it. If this field already exist, this is a
		// succesative run and should not be executed
		//
		if (!isset ($metaColumns['IS_DELETED']) && tableExists ($db, $oldName))
		{
			//
			// Drop the existing index
			//
			$query = 'DROP INDEX '.$oldName.'_index ON '.$oldName;
			$db->Execute ($query) or print_r ($db->ErrorMsg ().' '.$query);
			echo 'Dropped index '.$oldName.'.'.$oldName.'_index<br /> ';
			//
			// Add the column is_deleted
			//
			$query  = 'ALTER TABLE '.$oldName.' ';
			$query .= ' ADD COLUMN is_deleted bool DEFAULT 0 AFTER category';
			$db->Execute ($query) or die ($db->ErrorMsg ());
			echo 'Added column \'is_deleted\' to '.$oldName.'<br />';
			//
			// Rename itemId to item_id
			//
			$query  = 'ALTER TABLE '.$oldName.' ';
			$query .= ' CHANGE itemId item_id INT NOT NULL AUTO_INCREMENT ';
			$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
			echo 'Changed column \'itemId\' to item_id<br />';
			//
			// Rename parentId to parent_id
			//
			$query  = 'ALTER TABLE '.$oldName.' ';
			$query .= ' CHANGE parentId parent_id int DEFAULT 0';
			$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
			echo 'Changed column \'parentId\' to parent_id<br />';
			//
			// Rename isParent to is_parent
			//
			$query  = 'ALTER TABLE '.$oldName.' ';
			$query .= ' CHANGE isParent is_parent int DEFAULT 0';
			$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
			echo 'Changed column \'isParent\' to is_parent<br />';
			//
			// Change type of name from VARCHAR(70) to TEXT
			//
			$query  = 'ALTER TABLE '.$oldName.' ';
			$query .= ' CHANGE name name TEXT DEFAULT null';
			$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
			echo 'Changed type of name field from VARCHAR(70) to TEXT<br />';
			//
			// Rename the tableName from booby_xyz to brim_xyz
			//
			$query  = 'ALTER TABLE '.$oldName.' ';
			$query .= 'RENAME TO '.$newName;
			$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
			echo 'Renamed table \''.$oldName.'\' to \''.$newName.'\'<br />';
			//
			// Re-add the index
			//
			$query = 'CREATE INDEX '.$newName.'_index ON '.$newName.' (owner, parent_id, name, item_id)';
			//$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
			echo 'Added index '.$newName.'_index ON '.$newName.'<br />';

		}
		else
		{
			echo 'Table '.$oldName.' already has column \'is_deleted\' or table doesnot exist<br /> ';
		}
	}
}

function changeBrimBanking ($db)
{
	$query = "ALTER TABLE `brim_bk_cancellists` " .
		 "CHANGE `bankId` `bank_id` INT NOT NULL DEFAULT '0' ";
	$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
	echo 'Changed bankId to bank_id<br />';

	$query = "ALTER TABLE `brim_bk_secrets` " .
		 "CHANGE `cancellistId` `cancellist_id` INT NOT NULL DEFAULT '0' ";
	$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
	echo 'Changed cancellistId to cancellist_id<br />';
}

function changeBrimUserPreferences ($db)
{
	$langtrans = array ();
	$langtrans['EN']='en';
	$langtrans['CS']='cs';
	$langtrans['DE']='de';
	$langtrans['DK']='da';
	$langtrans['EO']='eo';
	$langtrans['ES']='es';
	$langtrans['ES_COL']='es_CO';
	$langtrans['FR']='fr';
	$langtrans['HE']='he';
	$langtrans['IT']='it';
	$langtrans['NL']='nl';
	$langtrans['NO']='no';
	$langtrans['PL']='pl';
	$langtrans['PT_BR']='pt_BR';
	$langtrans['PT']='pt';
	$langtrans['RO']='ro';
	$langtrans['RU']='ru';
	$langtrans['SE']='sv';
	$langtrans['TC']='zh_TW';

	$query  = 'SELECT item_id FROM brim_user_preferences ';
	$query .= 'WHERE name=\'booby_template\'';
	$result = $db->Execute ($query) or die ($db->ErrorMsg () .$query);
	while (!$result->EOF)
	{
		$itemId = $result->fields['item_id'];
		$query  = 'UPDATE brim_user_preferences SET ';
		$query .= 'name=\'brimTemplate\' WHERE item_id='.$itemId;
		$db->Execute ($query) or die ($db->ErrorMsg () .$query);
		$result->MoveNext ();
	}

	$query  = 'SELECT * FROM brim_user_preferences ';
	$query .= 'WHERE name=\'language\'';
	$result = $db->Execute ($query) or die ($db->ErrorMsg () .$query);
	while (!$result->EOF)
	{
		$itemId = $result->fields['item_id'];
		$oldValue = $result->fields['value'];

		$query  = 'UPDATE brim_user_preferences SET ';
		$query .= 'name=\'brimLanguage\',value=\''.$langtrans[$oldValue].'\' ';
		//$query .= 'name=\'brimLanguage\' WHERE item_id='.$itemId;
		$query .= ' WHERE item_id='.$itemId;
		$db->Execute ($query) or die ($db->ErrorMsg () .$query);

		$result->MoveNext ();
	}
}

/**
 * Checks for banking tables, which where created out of the first
 * version of the banking plugin. Typically those tables do not have
 * the column 'parentId', 'isParent', etc.
 * This problem should only appear if you try to convert a database
 * made with booby0.4.0rc3 to brim.
 */
function prepareOldBoobyBanking ($db)
{
	$currentTable = 'booby_bk_banks';

	if (tableExists ($db, $currentTable))
	{
		if (!columnExists($db, $currentTable, 'IS_DELETED'))
		{
			if (!columnExists($db, $currentTable, 'parentId'))
			{
				echo 'Converting old table structure of \''.$currentTable.'\' to a more recent one...<br />';

				$query = "ALTER TABLE `".$currentTable."` CHANGE `owner` `owner` VARCHAR(70) NOT NULL DEFAULT '' AFTER `itemId` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Moved field \'owner\' after \'itemId\'<br />';

				$query = "ALTER TABLE `".$currentTable."` CHANGE `guid` `guid` VARCHAR(32) NOT NULL AFTER `when_modified` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Moved field \'guid\' after \'when_modified\'<br />';

				$query = "ALTER TABLE `".$currentTable."` ADD `parentId` INT DEFAULT '0' NOT NULL AFTER `owner` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Added field \'parentId\'<br />';

				$query = "ALTER TABLE `".$currentTable."` ADD `isParent` TINYINT(1) DEFAULT NULL AFTER `parentId` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Added field \'isParent\'<br />';

				$query = "ALTER TABLE `".$currentTable."` CHANGE `name` `name` VARCHAR(70) NOT NULL DEFAULT '' AFTER `isParent` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Moved field \'name\' after \'isParent\'<br />';

				$query = "ALTER TABLE `".$currentTable."` ADD `description` TEXT AFTER `name` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Added field \'description\'<br />';

				$query = "ALTER TABLE `".$currentTable."` ADD `visibility` VARCHAR(15) DEFAULT NULL AFTER `description` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Added field \'visibility\'<br />';

				$query = "ALTER TABLE `".$currentTable."` ADD `category` VARCHAR(50) DEFAULT NULL AFTER `visibility` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Added field \'category\'<br />';


				echo 'Conversion done<br />';
			}
			else
			{
				//echo 'Table '.$currentTable.' is ready to convert to brim<br />';
			}
		}
		else
		{
			echo 'Table '.$currentTable.' contains already changes for brim. Doing nothing.<br />';
		}
	}
	else
	{
		echo 'Table '.$currentTable.' has already converted or is not there<br />';
	}

	$currentTable = 'booby_bk_cancellists';

	if (tableExists ($db, $currentTable))
	{
		if (!columnExists($db, $currentTable, 'IS_DELETED'))
		{
			if (!columnExists($db, $currentTable, 'parentId'))
			{
				echo 'Converting old table structure of \''.$currentTable.'\' to a more recent one...<br />';

				$query = "ALTER TABLE `" . $currentTable . "` " .
						 "CHANGE `bankId` `bankId` INT NOT NULL DEFAULT '0' AFTER `when_modified` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Moved field \'bankId\' after \'when_modified\'<br />';

				$query = "ALTER TABLE `" . $currentTable . "` " .
						 "CHANGE `guid` `guid` VARCHAR(32) NOT NULL AFTER `bankId` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Moved field guid after bankId<br />';

				$query = "ALTER TABLE `" . $currentTable . "` " .
						 "ADD `parentId` INT DEFAULT '0' NOT NULL AFTER `owner` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Added field parentId after owner<br />';

				$query = "ALTER TABLE `" . $currentTable . "` " .
						 "ADD `isParent` TINYINT(1) DEFAULT NULL AFTER `parentId` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Added field isParent after parentId<br />';

				$query = "ALTER TABLE `" . $currentTable . "` " .
						 "CHANGE `cancellistName` `name` VARCHAR(70) NOT NULL DEFAULT ''" .
						 " AFTER `isParent` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Renamed the field cancellistName to name and move it after isParent<br />';

				$query = "ALTER TABLE `" . $currentTable . "` " .
						 "ADD `description` TEXT AFTER `name` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Added field description<br />';

				$query = "ALTER TABLE `" . $currentTable . "` " .
						 "ADD `visibility` VARCHAR(15) DEFAULT NULL AFTER `description` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Added field visibility<br />';

				$query = "ALTER TABLE `" . $currentTable . "` " .
						 "ADD `category` VARCHAR(50) DEFAULT NULL AFTER `visibility` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Added field category<br />';

				echo 'Conversion done<br />';
			}
			else
			{
				//echo 'Table '.$currentTable.' is ready to convert to brim<br />';
			}
		}
		else
		{
			echo 'Table '.$currentTable.' contains already changes for brim. Doing nothing.<br />';
		}
	}
	else
	{
		echo 'Table '.$currentTable.' has already converted or is not there<br />';
	}

	$currentTable = 'booby_bk_secrets';

	if (tableExists ($db, $currentTable))
	{
		if (!columnExists($db, $currentTable, 'IS_DELETED'))
		{
			if (!columnExists($db, $currentTable, 'parentId'))
			{
				echo 'Converting old table structure of \''.$currentTable.'\' to a more recent one...<br />';

				$query = "ALTER TABLE `" . $currentTable . "` " .
						 "CHANGE `cancellistId` `cancellistId` INT NOT NULL DEFAULT '0' AFTER `when_modified` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Moved field cancellistId after when_modified<br />';

				$query = "ALTER TABLE `" . $currentTable . "` " .
						 "CHANGE `guid` `guid` VARCHAR(32) NOT NULL AFTER `cancellistId` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Moved field guid after bankId<br />';

				$query = "ALTER TABLE `" . $currentTable . "` " .
						 "ADD `parentId` INT DEFAULT '0' NOT NULL AFTER `owner` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Added field parentId after owner<br />';

				$query = "ALTER TABLE `" . $currentTable . "` " .
						 "ADD `isParent` TINYINT(1) DEFAULT NULL AFTER `parentId` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Added field isParent after parentId<br />';

				$query = "ALTER TABLE `" . $currentTable . "` " .
						 "ADD `name` VARCHAR(70) NOT NULL DEFAULT ''" .
						 " AFTER `isParent` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Added field name after isParent<br />';

				$query = "ALTER TABLE `" . $currentTable . "` " .
						 "ADD `description` TEXT AFTER `name` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Added field description<br />';

				$query = "ALTER TABLE `" . $currentTable . "` " .
						 "ADD `visibility` VARCHAR(15) DEFAULT NULL AFTER `description` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Added field visibility<br />';

				$query = "ALTER TABLE `" . $currentTable . "` " .
						 "ADD `category` VARCHAR(50) DEFAULT NULL AFTER `visibility` ";
				$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
				echo 'Added field category<br />';

				echo 'Conversion done<br />';
			}
			else
			{
				//echo 'Table '.$currentTable.' is ready to convert to brim<br />';
			}
		}
		else
		{
			echo 'Table '.$currentTable.' contains already changes for brim. Doing nothing.<br />';
		}
	}
	else
	{
		echo 'Table '.$currentTable.' has already converted or is not there<br />';
	}
}

?>