<?php
/******************************************************************************
* ManageSmileys.php                                                           *
*******************************************************************************
* SMF: Simple Machines Forum                                                  *
* Open-Source Project Inspired by Zef Hemel (zef@zefhemel.com)                *
* =========================================================================== *
* Software Version:           SMF 1.0                                         *
* Software by:                Simple Machines (http://www.simplemachines.org) *
* Copyright 2001-2004 by:     Lewis Media (http://www.lewismedia.com)         *
* Support, News, Updates at:  http://www.simplemachines.org                   *
*******************************************************************************
* This program is free software; you may redistribute it and/or modify it     *
* under the terms of the provided license as published by Lewis Media.        *
*                                                                             *
* This program is distributed in the hope that it is and will be useful,      *
* but WITHOUT ANY WARRANTIES; without even any implied warranty of            *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                        *
*                                                                             *
* See the "license.txt" file for details of the Simple Machines license.      *
* The latest version can always be found at http://www.simplemachines.org.    *
******************************************************************************/
if (!defined('SMF'))
	die('Hacking attempt...');

function ManageSmileys()
{
	global $context, $txt;

	isAllowedTo('manage_smileys');
	adminIndex('manage_smileys');

	loadLanguage('ManageSmileys');
	loadTemplate('ManageSmileys');

	$subActions = array(
		'addsmiley' => 'AddSmiley',
		'editsets' => 'EditSmileySets',
		'editsmileys' => 'EditSmileys',
		'import' => 'EditSmileySets',
		'modifyset' => 'EditSmileySets',
		'modifysmiley' => 'EditSmileys',
		'setorder' => 'EditSmileyOrder',
		'settings' => 'EditSmileySettings',
		'install' => 'InstallSmileySet'
	);

	// Default the sub-action to 'edit smiley settings'.
	$_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'settings';

	$context['page_title'] = &$txt['smileys_manage'];
	$context['sub_action'] = $_REQUEST['sa'];
	$context['sub_template'] = &$context['sub_action'];

	// Call the right function for this sub-acton.
	$subActions[$_REQUEST['sa']]();
}

function EditSmileySettings()
{
	global $modSettings, $context, $settings, $db_prefix, $txt, $boarddir;

	$context['explain_text'] = &$txt['smiley_settings_explain'];

	// A form was submitted.
	if (isset($_POST['sc']))
	{
		checkSession();
		$context['smiley_sets'] = explode(',', $modSettings['smiley_sets_known']);
		updateSettings(array(
			'smiley_sets_default' => empty($context['smiley_sets'][$_POST['default_smiley_set']]) ? 'default' : $context['smiley_sets'][$_POST['default_smiley_set']],
			'smiley_sets_enable' => isset($_POST['smiley_sets_enable']) ? '1' : '0',
			'smiley_enable' => isset($_POST['smiley_enable']) ? '1' : '0',
			'smileys_url' => $_POST['smiley_sets_url'],
			'smileys_dir' => $_POST['smiley_sets_dir'],
		));
	}
	$context['smileys_dir'] = empty($modSettings['smileys_dir']) ? $boarddir . '/Smileys' : $modSettings['smileys_dir'];
	$context['smileys_dir_found'] = is_dir($context['smileys_dir']);

	$context['smiley_sets'] = explode(',', $modSettings['smiley_sets_known']);
	$set_names = explode("\n", $modSettings['smiley_sets_names']);
	foreach ($context['smiley_sets'] as $i => $set)
		$context['smiley_sets'][$i] = array(
			'id' => $i,
			'path' => $set,
			'name' => $set_names[$i],
			'selected' => $set == $modSettings['smiley_sets_default']
		);
}

function EditSmileySets()
{
	global $modSettings, $context, $settings, $db_prefix, $txt, $boarddir;

	$context['explain_text'] = &$txt['smiley_editsets_explain'];

	// They must've been submitted a form.
	if (isset($_POST['sc']))
	{
		checkSession();

		// Delete selected smiley sets.
		if (!empty($_POST['delete']))
		{
			$set_paths = explode(',', $modSettings['smiley_sets_known']);
			$set_names = explode("\n", $modSettings['smiley_sets_names']);
			foreach ($_POST['smiley_set'] as $id => $val)
				if (isset($set_paths[$id]) && isset($set_names[$id]) && !empty($id))
					unset($set_paths[$id], $set_names[$id]);

			updateSettings(array(
				'smiley_sets_known' => implode(',', $set_paths),
				'smiley_sets_names' => implode("\n", $set_names),
				'smiley_sets_default' => in_array($modSettings['smiley_sets_default'], $set_paths) ? $modSettings['smiley_sets_default'] : $set_paths[0],
			));
		}

		// Add a new smiley set.
		elseif (!empty($_POST['add']))
			$context['sub_action'] = 'modifyset';

		// Create or modify a smiley set.
		elseif (isset($_POST['id']))
		{
			$set_paths = explode(',', $modSettings['smiley_sets_known']);
			$set_names = explode("\n", $modSettings['smiley_sets_names']);

			// Create a new smiley set.
			if ($_POST['id'] == -1)
			{
				if (in_array($_POST['smiley_sets_path'], $set_paths))
					fatal_lang_error('smiley_set_already_exists');

				updateSettings(array(
					'smiley_sets_known' => $modSettings['smiley_sets_known'] . ',' . $_POST['smiley_sets_path'],
					'smiley_sets_names' => $modSettings['smiley_sets_names'] . "\n" . $_POST['smiley_sets_name'],
					'smiley_sets_default' => empty($_POST['smiley_sets_default']) ? $modSettings['smiley_sets_default'] : $_POST['smiley_sets_path'],
				));
			}

			// Modify an existing smiley set.
			else
			{
				// Make sure the smiley set exists.
				if (!isset($set_paths[$_POST['id']]) || !isset($set_names[$_POST['id']]))
					fatal_lang_error('smiley_set_not_found');

				// Make sure the path is not yet used by another smileyset.
				if (in_array($_POST['smiley_sets_path'], $set_paths) && $_POST['smiley_sets_path'] != $set_paths[$_POST['id']])
					fatal_lang_error('smiley_set_path_already_used');

				$set_paths[$_POST['id']] = $_POST['smiley_sets_path'];
				$set_names[$_POST['id']] = $_POST['smiley_sets_name'];
				updateSettings(array(
					'smiley_sets_known' => implode(',', $set_paths),
					'smiley_sets_names' => implode("\n", $set_names),
					'smiley_sets_default' => empty($_POST['smiley_sets_default']) ? $modSettings['smiley_sets_default'] : $_POST['smiley_sets_path']
				));
			}

			// The user might have checked to also import smileys.
			if (!empty($_POST['smiley_sets_import']))
				ImportSmileys($_POST['smiley_sets_path']);
		}
	}

	// Load all available smileysets...
	$context['smiley_sets'] = explode(',', $modSettings['smiley_sets_known']);
	$set_names = explode("\n", $modSettings['smiley_sets_names']);
	foreach ($context['smiley_sets'] as $i => $set)
		$context['smiley_sets'][$i] = array(
			'id' => $i,
			'path' => $set,
			'name' => $set_names[$i],
			'selected' => $set == $modSettings['smiley_sets_default']
		);

	// Importing any smileys from an existing set?
	if ($context['sub_action'] == 'import')
	{
		checkSession('get');
		$_GET['id'] = (int) $_GET['id'];

		// Sanity check - then import.
		if (isset($context['smiley_sets'][$_GET['id']]))
			ImportSmileys($context['smiley_sets'][$_GET['id']]['path']);

		// Force the process to continue.
		$context['sub_action'] = 'modifyset';
	}
	// If we're modifying or adding a smileyset, some context info needs to be set.
	if ($context['sub_action'] == 'modifyset')
	{
		$_GET['id'] = !isset($_GET['id']) ? -1 : (int) $_GET['id'];
		if ($_GET['id'] == -1 || !isset($context['smiley_sets'][$_GET['id']]))
			$context['current_set'] = array(
				'id' => '-1',
				'path' => '',
				'name' => '',
				'selected' => false,
				'is_new' => true,
			);
		else
		{
			$context['current_set'] = &$context['smiley_sets'][$_GET['id']];
			$context['current_set']['is_new'] = false;

			// Calculate whether there are any smileys in the directory that can be imported.
			if (!empty($modSettings['smiley_enable']) && !empty($modSettings['smileys_dir']) && is_dir($modSettings['smileys_dir'] . '/' . $context['current_set']['path']))
			{
				$smileys = array();
				$dir = dir($modSettings['smileys_dir'] . '/' . $context['current_set']['path']);
				while ($entry = $dir->read())
				{
					if (in_array(strrchr($entry, '.'), array('.jpg', '.gif', '.jpeg', '.png')))
						$smileys[strtolower($entry)] = $entry;
				}
				$dir->close();

				// Exclude the smileys that are already in the database.
				$request = db_query("
					SELECT filename
					FROM {$db_prefix}smileys
					WHERE filename IN ('" . implode("', '", $smileys) . "')", __FILE__, __LINE__);
				while ($row = mysql_fetch_assoc($request))
					if (isset($smileys[strtolower($row['filename'])]))
						unset($smileys[strtolower($row['filename'])]);
				mysql_free_result($request);

				$context['current_set']['can_import'] = count($smileys);
				// Setup this string to look nice.
				$txt['smiley_set_import_multiple'] = sprintf($txt['smiley_set_import_multiple'], $context['current_set']['can_import']);
			}
		}

		// Retrieve all potential smiley set directories.
		$context['smiley_set_dirs'] = array();
		if (!empty($modSettings['smileys_dir']) && is_dir($modSettings['smileys_dir']))
		{
			$dir = dir($modSettings['smileys_dir']);
			while ($entry = $dir->read())
			{
				if (!in_array($entry, array('.', '..')) && is_dir($modSettings['smileys_dir'] . '/' . $entry))
					$context['smiley_set_dirs'][] = array(
						'id' => $entry,
						'path' => $modSettings['smileys_dir'] . '/' . $entry,
						'selectable' => $entry == $context['current_set']['path'] || !in_array($entry, explode(',', $modSettings['smiley_sets_known'])),
						'current' => $entry == $context['current_set']['path'],
					);
			}
			$dir->close();
		}
	}
}

function AddSmiley()
{
	global $modSettings, $context, $settings, $db_prefix, $txt, $boarddir;

	$context['explain_text'] = &$txt['smiley_addsmiley_explain'];

	// Get a list of all known smiley sets.
	$context['smileys_dir'] = empty($modSettings['smileys_dir']) ? $boarddir . '/Smileys' : $modSettings['smileys_dir'];
	$context['smileys_dir_found'] = is_dir($context['smileys_dir']);
	$context['smiley_sets'] = explode(',', $modSettings['smiley_sets_known']);
	$set_names = explode("\n", $modSettings['smiley_sets_names']);
	foreach ($context['smiley_sets'] as $i => $set)
		$context['smiley_sets'][$i] = array(
			'id' => $i,
			'path' => $set,
			'name' => $set_names[$i],
			'selected' => $set == $modSettings['smiley_sets_default']
		);

	// Submitting a form?
	if (isset($_POST['sc']))
	{
		checkSession();

		// Some useful arrays... types we allow - and ports we don't!
		$allowedTypes = array('jpeg', 'jpg', 'gif', 'png', 'bmp');
		$disabledFiles = array('con', 'com1', 'com2', 'com3', 'com4', 'prn', 'aux', 'lpt1', '.htaccess', 'index.php');

		$_POST['smiley_code'] = htmltrim__recursive($_POST['smiley_code']);
		$_POST['smiley_location'] = empty($_POST['smiley_location']) || $_POST['smiley_location'] > 2 || $_POST['smiley_location'] < 0 ? 0 : (int) $_POST['smiley_location'];
		$_POST['smiley_filename'] = htmltrim__recursive($_POST['smiley_filename']);

		// Make sure some code was entered.
		if (empty($_POST['smiley_code']))
			fatal_lang_error('smiley_has_no_code');

		// Check whether the new code has duplicates. It should be unique.
		$request = db_query("
			SELECT ID_SMILEY
			FROM {$db_prefix}smileys
			WHERE code = BINARY '$_POST[smiley_code]'", __FILE__, __LINE__);
		if (mysql_num_rows($request) > 0)
			fatal_lang_error('smiley_not_unique');
		mysql_free_result($request);

		// If we are uploading - check all the smiley sets are writable!
		if ($_POST['method'] != 'existing')
		{
			$writeErrors = array();
			foreach ($context['smiley_sets'] as $set)
			{
				if (!is_writable($context['smileys_dir'] . '/' . $set['path']))
					$writeErrors[] = $set['path'];
			}
			if (!empty($writeErrors))
				fatal_error($txt['smileys_upload_error_notwritable'] . ' ' . implode(', ', $writeErrors));
		}

		// Uploading just one smiley for all of them?
		if (isset($_POST['sameall']) && isset($_FILES['uploadSmiley']['name']) && $_FILES['uploadSmiley']['name'] != '')
		{
			if (!is_uploaded_file($_FILES['uploadSmiley']['tmp_name']))
				fatal_lang_error('smileys_upload_error');

			// Sorry, no spaces, dots, or anything else but letters allowed.
			$_FILES['uploadSmiley']['name'] = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $_FILES['uploadSmiley']['name']);

			// We only allow image files - it's THAT simple - no messing around here...
			if (!in_array(strtolower(substr(strrchr($_FILES['uploadSmiley']['name'], '.'), 1)), $allowedTypes))
				fatal_error($txt['smileys_upload_error_types'] . ' ' . implode(', ', $allowedTypes) . '.', false);

			// We only need the filename...
			$destName = basename($_FILES['uploadSmiley']['name']);

			// Make sure they aren't trying to upload a nasty file - for their own good here!
			if (in_array(strtolower($destName), $disabledFiles))
				fatal_lang_error('smileys_upload_error_illegal');

			// Check if the file already exists... and if not move it to EVERY smiley set directory.
			$i = 0;
			// Keep going until we find a set the file doesn't exist in. (or maybe it exists in all of them?)
			while (isset($context['smiley_sets'][$i]) && file_exists($context['smileys_dir'] . '/' . $context['smiley_sets'][$i]['path'] . '/' . $destName))
				$i++;

			// Okay, we're going to put the smiley right here, since it's not there yet!
			if (isset($context['smiley_sets'][$i]['path']))
			{
				$smileyLocation = $context['smileys_dir'] . '/' . $context['smiley_sets'][$i]['path'] . '/' . $destName;
				move_uploaded_file($_FILES['uploadSmiley']['tmp_name'], $smileyLocation);
				@chmod($currentPath, 0644);

				// Now, we want to move it from there to all the other sets.
				for ($n = count($context['smiley_sets']); $i < $n; $i++)
				{
					$currentPath = $context['smileys_dir'] . '/' . $context['smiley_sets'][$i]['path'] . '/' . $destName;

					// The file is already there!  Don't overwrite it!
					if (file_exists($currentPath))
						continue;

					// Okay, so copy the first one we made to here.
					copy($smileyLocation, $currentPath);
					@chmod($currentPath, 0644);
				}
			}

			// Finally make sure it's saved correctly!
			$_POST['smiley_filename'] = $destName;
		}
		// What about uploading several files?
		elseif ($_POST['method'] != 'existing')
		{
			foreach ($_FILES as $name => $data)
			{
				if ($_FILES[$name]['name'] == '')
					fatal_lang_error('smileys_upload_error_blank');

				if (empty($newName))
					$newName = basename($_FILES[$name]['name']);
				elseif (basename($_FILES[$name]['name']) != $newName)
					fatal_lang_error('smileys_upload_error_name');
			}

			foreach ($context['smiley_sets'] as $i => $set)
			{
				if (!isset($_FILES['individual_' . $set['name']]['name']) || $_FILES['individual_' . $set['name']]['name'] == '')
					continue;

				// Got one...
				if (!is_uploaded_file($_FILES['individual_' . $set['name']]['tmp_name']))
						fatal_lang_error('smileys_upload_error');

				// Sorry, no spaces, dots, or anything else but letters allowed.
				$_FILES['individual_' . $set['name']]['name'] = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $_FILES['individual_' . $set['name']]['name']);

				// We only allow image files - it's THAT simple - no messing around here...
				if (!in_array(strtolower(substr(strrchr($_FILES['individual_' . $set['name']]['name'], '.'), 1)), $allowedTypes))
					fatal_error($txt['smileys_upload_error_types'] . ' ' . implode(', ', $allowedTypes) . '.', false);

				// We only need the filename...
				$destName = basename($_FILES['individual_' . $set['name']]['name']);

				// Make sure they aren't trying to upload a nasty file - for their own good here!
				if (in_array(strtolower($destName), $disabledFiles))
					fatal_lang_error('smileys_upload_error_illegal');

				// If the file exists - ignore it.
				$smileyLocation = $context['smileys_dir'] . '/' . $set['path'] . '/' . $destName;
				if (file_exists($smileyLocation))
					continue;

				// Finally - move the image!
				move_uploaded_file($_FILES['individual_' . $set['name']]['tmp_name'], $smileyLocation);
				@chmod($smileyLocation, 0644);

				// Should always be saved correctly!
				$_POST['smiley_filename'] = $destName;
			}
		}

		// Also make sure a filename was given.
		if (empty($_POST['smiley_filename']))
			fatal_lang_error('smiley_has_no_filename');

		// Find the position on the right.
		$smileyOrder = '0';
		if ($_POST['smiley_location'] != 1)
		{
			$request = db_query("
				SELECT MAX(smileyOrder) + 1
				FROM {$db_prefix}smileys
				WHERE hidden = $_POST[smiley_location]
					AND smileyRow = 0", __FILE__, __LINE__);
			list ($smileyOrder) = mysql_fetch_row($request);
			mysql_free_result($request);

			if (empty($smileyOrder))
				$smileyOrder = '0';
		}
		db_query("
			INSERT INTO {$db_prefix}smileys
				(code, filename, description, hidden, smileyOrder)
			VALUES ('$_POST[smiley_code]', '$_POST[smiley_filename]', '$_POST[smiley_description]', $_POST[smiley_location], $smileyOrder)", __FILE__, __LINE__);

		// No errors? Out of here!
		redirectexit('action=smileys;sa=editsmileys');
	}

	$context['selected_set'] = $modSettings['smiley_sets_default'];

	// Get all possible filenames for the smileys.
	$context['filenames'] = array();
	if ($context['smileys_dir_found'])
	{
		foreach ($context['smiley_sets'] as $smiley_set)
		{
			if (!file_exists($context['smileys_dir'] . '/' . $smiley_set['path']))
				continue;

			$dir = dir($context['smileys_dir'] . '/' . $smiley_set['path']);
			while ($entry = $dir->read())
			{
				if (!in_array($entry, $context['filenames']) && in_array(strrchr($entry, '.'), array('.jpg', '.gif', '.jpeg', '.png')))
					$context['filenames'][strtolower($entry)] = array(
						'id' => htmlspecialchars($entry),
						'selected' => false,
					);
			}
			$dir->close();
		}
		ksort($context['filenames']);
	}

	// Create a new smiley from scratch.
	$context['filenames'] = array_values($context['filenames']);
	$context['current_smiley'] = array(
		'id' => 0,
		'code' => '',
		'filename' => $context['filenames'][0]['id'],
		'description' => &$txt['smileys_default_description'],
		'location' => 0,
		'is_new' => true,
	);
}

function EditSmileys()
{
	global $modSettings, $context, $settings, $db_prefix, $txt, $boarddir;

	$context['explain_text'] = &$txt['smiley_editsmileys_explain'];

	// Submitting a form?
	if (isset($_POST['sc']))
	{
		checkSession();

		// Delete selected smileys.
		if (!empty($_POST['delete']) && !empty($_POST['checked_smileys']))
		{
			foreach ($_POST['checked_smileys'] as $id => $smiley_id)
				$_POST['checked_smileys'][$id] = (int) $smiley_id;

			db_query("
				DELETE FROM {$db_prefix}smileys
				WHERE ID_SMILEY IN (" . implode(', ', $_POST['checked_smileys']) . ')', __FILE__, __LINE__);
		}
		// Create/modify a smiley.
		elseif (isset($_POST['id']))
		{
			$_POST['id'] = (int) $_POST['id'];
			$_POST['smiley_code'] = htmltrim__recursive($_POST['smiley_code']);
			$_POST['smiley_filename'] = htmltrim__recursive($_POST['smiley_filename']);
			$_POST['smiley_location'] = empty($_POST['smiley_location']) || $_POST['smiley_location'] > 2 || $_POST['smiley_location'] < 0 ? 0 : (int) $_POST['smiley_location'];

			// Make sure some code was entered.
			if (empty($_POST['smiley_code']))
				fatal_lang_error('smiley_has_no_code');

			// Also make sure a filename was given.
			if (empty($_POST['smiley_filename']))
				fatal_lang_error('smiley_has_no_filename');

			// Check whether the new code has duplicates. It should be unique.
			$request = db_query("
				SELECT ID_SMILEY
				FROM {$db_prefix}smileys
				WHERE code = BINARY '$_POST[smiley_code]'" . (empty($_POST['id']) ? '' : "
					AND ID_SMILEY != $_POST[id]"), __FILE__, __LINE__);
			if (mysql_num_rows($request) > 0)
				fatal_lang_error('smiley_not_unique');
			mysql_free_result($request);

			db_query("
				UPDATE {$db_prefix}smileys
				SET
					code = '$_POST[smiley_code]',
					filename = '$_POST[smiley_filename]',
					description = '$_POST[smiley_description]',
					hidden = $_POST[smiley_location]
				WHERE ID_SMILEY = $_POST[id]", __FILE__, __LINE__);
		}
	}

	// Load all known smiley sets.
	$context['smiley_sets'] = explode(',', $modSettings['smiley_sets_known']);
	$set_names = explode("\n", $modSettings['smiley_sets_names']);
	foreach ($context['smiley_sets'] as $i => $set)
		$context['smiley_sets'][$i] = array(
			'id' => $i,
			'path' => $set,
			'name' => $set_names[$i],
			'selected' => $set == $modSettings['smiley_sets_default']
		);

	// Prepare overview of all (custom) smileys.
	if ($context['sub_action'] == 'editsmileys')
	{
		$sortColumns = array(
			'code',
			'filename',
			'description',
			'hidden',
		);

		// Default to 'order by filename'.
		$context['sort'] = empty($_REQUEST['sort']) || !in_array($_REQUEST['sort'], $sortColumns) ? 'filename' : $_REQUEST['sort'];

		$request = db_query("
			SELECT ID_SMILEY, code, filename, description, smileyRow, smileyOrder, hidden
			FROM {$db_prefix}smileys
			ORDER BY $context[sort]", __FILE__, __LINE__);
		$context['smileys'] = array();
		while ($row = mysql_fetch_assoc($request))
			$context['smileys'][] = array(
				'id' => $row['ID_SMILEY'],
				'code' => htmlspecialchars($row['code']),
				'filename' => htmlspecialchars($row['filename']),
				'description' => htmlspecialchars($row['description']),
				'row' => $row['smileyRow'],
				'order' => $row['smileyOrder'],
				'location' => empty($row['hidden']) ? $txt['smileys_location_form'] : ($row['hidden'] == 1 ? $txt['smileys_location_hidden'] : $txt['smileys_location_popup']),
				'sets_not_found' => array(),
			);
		mysql_free_result($request);

		if (!empty($modSettings['smileys_dir']) && is_dir($modSettings['smileys_dir']))
		{
			foreach ($context['smiley_sets'] as $smiley_set)
			{
				foreach ($context['smileys'] as $smiley_id => $smiley)
					if (!file_exists($modSettings['smileys_dir'] . '/' . $smiley_set['path'] . '/' . $smiley['filename']))
						$context['smileys'][$smiley_id]['sets_not_found'][] = $smiley_set['path'];
			}
		}

		$context['selected_set'] = $modSettings['smiley_sets_default'];
	}

	// Modifying smileys.
	elseif ($context['sub_action'] == 'modifysmiley')
	{
		// Get a list of all known smiley sets.
		$context['smileys_dir'] = empty($modSettings['smileys_dir']) ? $boarddir . '/Smileys' : $modSettings['smileys_dir'];
		$context['smileys_dir_found'] = is_dir($context['smileys_dir']);
		$context['smiley_sets'] = explode(',', $modSettings['smiley_sets_known']);
		$set_names = explode("\n", $modSettings['smiley_sets_names']);
		foreach ($context['smiley_sets'] as $i => $set)
			$context['smiley_sets'][$i] = array(
				'id' => $i,
				'path' => $set,
				'name' => $set_names[$i],
				'selected' => $set == $modSettings['smiley_sets_default']
			);

		$context['selected_set'] = $modSettings['smiley_sets_default'];

		// Get all possible filenames for the smileys.
		$context['filenames'] = array();
		if ($context['smileys_dir_found'])
		{
			foreach ($context['smiley_sets'] as $smiley_set)
			{
				if (!file_exists($context['smileys_dir'] . '/' . $smiley_set['path']))
					continue;

				$dir = dir($context['smileys_dir'] . '/' . $smiley_set['path']);
				while ($entry = $dir->read())
				{
					if (!in_array($entry, $context['filenames']) && in_array(strrchr($entry, '.'), array('.jpg', '.gif', '.jpeg', '.png')))
						$context['filenames'][strtolower($entry)] = array(
							'id' => htmlspecialchars($entry),
							'selected' => false,
						);
				}
				$dir->close();
			}
			ksort($context['filenames']);
		}

		$request = db_query("
			SELECT ID_SMILEY AS id, code, filename, description, hidden AS location, 0 AS is_new
			FROM {$db_prefix}smileys
			WHERE ID_SMILEY = " . (int) $_REQUEST['id'], __FILE__, __LINE__);
		if (mysql_num_rows($request) != 1)
			fatal_lang_error('smiley_not_found');
		$context['current_smiley'] = mysql_fetch_assoc($request);
		mysql_free_result($request);
		if (isset($context['filenames'][strtolower($context['current_smiley']['filename'])]))
			$context['filenames'][strtolower($context['current_smiley']['filename'])]['selected'] = true;
	}
}

function EditSmileyOrder()
{
	global $modSettings, $context, $settings, $db_prefix, $txt, $boarddir;

	$context['explain_text'] = &$txt['smiley_setorder_explain'];

	// Move smileys to another position.
	if (isset($_GET['sesc']))
	{
		checkSession('get');

		$_GET['location'] = empty($_GET['location']) || $_GET['location'] != 'popup' ? 0 : 2;
		$_GET['source'] = empty($_GET['source']) ? 0 : (int) $_GET['source'];

		if (empty($_GET['source']))
			fatal_lang_error('smiley_not_found');

		if (!empty($_GET['after']))
		{
			$_GET['after'] = (int) $_GET['after'];

			$request = db_query("
				SELECT smileyRow, smileyOrder, hidden
				FROM {$db_prefix}smileys
				WHERE hidden = $_GET[location]
					AND ID_SMILEY = $_GET[after]", __FILE__, __LINE__);
			if (mysql_num_rows($request) != 1)
				fatal_lang_error('smiley_not_found');
			list ($smileyRow, $smileyOrder, $smileyLocation) = mysql_fetch_row($request);
			mysql_free_result($request);
		}
		else
		{
			$smileyRow = (int) $_GET['row'];
			$smileyOrder = -1;
			$smileyLocation = (int) $_GET['location'];
		}

		db_query("
			UPDATE {$db_prefix}smileys
			SET smileyOrder = smileyOrder + 1
			WHERE hidden = $_GET[location]
				AND smileyRow = $smileyRow
				AND smileyOrder > $smileyOrder", __FILE__, __LINE__);

		db_query("
			UPDATE {$db_prefix}smileys
			SET
				smileyOrder = $smileyOrder + 1,
				smileyRow = $smileyRow,
				hidden = $smileyLocation
			WHERE ID_SMILEY = $_GET[source]", __FILE__, __LINE__);
	}

	$request = db_query("
		SELECT ID_SMILEY, code, filename, description, smileyRow, smileyOrder, hidden
		FROM {$db_prefix}smileys
		WHERE hidden != 1
		ORDER BY smileyOrder, smileyRow", __FILE__, __LINE__);
	$context['smileys'] = array(
		'postform' => array(
			'rows' => array(),
		),
		'popup' => array(
			'rows' => array(),
		),
	);
	while ($row = mysql_fetch_assoc($request))
	{
		$location = empty($row['hidden']) ? 'postform' : 'popup';
		$context['smileys'][$location]['rows'][$row['smileyRow']][] = array(
			'id' => $row['ID_SMILEY'],
			'code' => htmlspecialchars($row['code']),
			'filename' => htmlspecialchars($row['filename']),
			'description' => htmlspecialchars($row['description']),
			'row' => $row['smileyRow'],
			'order' => $row['smileyOrder'],
			'selected' => !empty($_REQUEST['move']) && $_REQUEST['move'] == $row['ID_SMILEY'],
		);
	}
	mysql_free_result($request);

	$context['move_smiley'] = empty($_REQUEST['move']) ? 0 : (int) $_REQUEST['move'];

	// Make sure all rows are sequential.
	foreach (array_keys($context['smileys']) as $location)
		$context['smileys'][$location] = array(
			'id' => $location,
			'title' => $location == 'postform' ? $txt['smileys_location_form'] : $txt['smileys_location_popup'],
			'description' => $location == 'postform' ? $txt['smileys_location_form_description'] : $txt['smileys_location_popup_description'],
			'last_row' => count($context['smileys'][$location]['rows']),
			'rows' => array_values($context['smileys'][$location]['rows']),
		);

	// Check & fix smileys that are not ordered properly in the database.
	foreach (array_keys($context['smileys']) as $location)
	{
		foreach ($context['smileys'][$location]['rows'] as $id => $smiley_row)
		{
			// Fix empty rows if any.
			if ($id != $smiley_row[0]['row'])
			{
				db_query("
					UPDATE {$db_prefix}smileys
					SET smileyRow = $id
					WHERE smileyRow = {$smiley_row[0]['row']}
						AND hidden = " . ($location == 'postform' ? '0' : '2'), __FILE__, __LINE__);
				// Only change the first row value of the first smiley (we don't need the others :P).
				$context['smileys'][$location]['rows'][$id][0]['row'] = $id;
			}
			// Make sure the smiley order is always sequential.
			foreach ($smiley_row as $order_id => $smiley)
				if ($order_id != $smiley['order'])
					db_query("
						UPDATE {$db_prefix}smileys
						SET smileyOrder = $order_id
						WHERE ID_SMILEY = $smiley[id]", __FILE__, __LINE__);
		}
	}
}

function InstallSmileySet()
{
	global $sourcedir, $boarddir, $modSettings;

	isAllowedTo('manage_smileys');
	checkSession('request');

	require_once($sourcedir . '/Subs-Package.php');

	$name = strtok(basename(isset($_FILES['set_gz']) ? $_FILES['set_gz']['name'] : $_REQUEST['set_gz']), '.');

	if (isset($_FILES['set_gz']) && is_uploaded_file($_FILES['set_gz']['tmp_name']))
		$extracted = read_tgz_file($_FILES['set_gz']['tmp_name'], $boarddir . '/Smileys/' . $name);
	elseif (isset($_REQUEST['set_gz']))
	{
		checkSession('request');

		// Check that the theme is from simplemachines.org, for now... maybe add mirroring later.
		if (preg_match('~^http://[\w_\-]+\.simplemachines\.org/~', $_REQUEST['set_gz']) == 0)
			fatal_lang_error('not_on_simplemachines');

		$extracted = read_tgz_file($_REQUEST['set_gz'], $boarddir . '/Smileys/' . $name);
	}
	else
		redirectexit('action=smileys');

	updateSettings(array(
		'smiley_sets_known' => $modSettings['smiley_sets_known'] . ',' . $name,
		'smiley_sets_names' => $modSettings['smiley_sets_names'] . "\n" . strtok(basename(isset($_FILES['set_gz']) ? $_FILES['set_gz']['name'] : $_REQUEST['set_gz']), '.')
	));

	redirectexit('action=smileys');
}

// A function to import new smileys from an existing directory into the database.
function ImportSmileys($smileyPath)
{
	global $db_prefix, $modSettings;

	if (empty($modSettings['smileys_dir']) || !is_dir($modSettings['smileys_dir'] . '/' . $smileyPath))
		fatal_lang_error('smiley_set_unable_to_import');

	$smileys = array();
	$dir = dir($modSettings['smileys_dir'] . '/' . $smileyPath);
	while ($entry = $dir->read())
	{
		if (in_array(strrchr($entry, '.'), array('.jpg', '.gif', '.jpeg', '.png')))
			$smileys[strtolower($entry)] = $entry;
	}
	$dir->close();

	// Exclude the smileys that are already in the database.
	$request = db_query("
		SELECT filename
		FROM {$db_prefix}smileys
		WHERE filename IN ('" . implode("', '", $smileys) . "')", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
		if (isset($smileys[strtolower($row['filename'])]))
			unset($smileys[strtolower($row['filename'])]);
	mysql_free_result($request);

	$request = db_query("
		SELECT MAX(smileyOrder)
		FROM {$db_prefix}smileys
		WHERE hidden = 0
			AND smileyRow = 0", __FILE__, __LINE__);
	list ($smileyOrder) = mysql_fetch_row($request);
	mysql_free_result($request);

	$new_smileys = array();
	foreach ($smileys as $smiley)
		$new_smileys[] = "(':" . strtok($smiley, '.') . ":', '$smiley', '" . strtok($smiley, '.') . "', 0, " . ++$smileyOrder . ')';

	if (!empty($new_smileys))
		db_query("
			INSERT INTO {$db_prefix}smileys
				(code, filename, description, smileyRow, smileyOrder)
			VALUES" . implode(',
				', $new_smileys), __FILE__, __LINE__);
}

?>