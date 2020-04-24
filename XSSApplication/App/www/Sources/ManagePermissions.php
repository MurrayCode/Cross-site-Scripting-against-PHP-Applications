<?php
/******************************************************************************
* ManagePermissions.php                                                       *
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

function ModifyPermissions()
{
	isAllowedTo('manage_permissions');

	adminIndex('edit_permissions');

	loadTemplate('ManagePermissions');
	loadLanguage('ManagePermissions');

	$subActions = array(
		'quick' => 'SetQuickGroups',
		'quickboard' => 'SetQuickBoards',
		'modify' => 'ModifyMembergroup',
		'modify2' => 'ModifyMembergroup2',
		'switch' => 'SwitchBoard',
	);

	if (isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]))
	{
		$sa = $subActions[$_REQUEST['sa']];
		unset($subActions);

		$sa();
	}
	else
		PermissionIndex();
}

function PermissionIndex()
{
	global $db_prefix, $txt, $scripturl, $context, $settings;

	$context['page_title'] = $txt['permissions_title'];

	// Load all the permissions. We'll need them in the template.
	loadAllPermissions();

	// Determine the number of ungrouped members.
	$request = db_query("
		SELECT COUNT(ID_MEMBER)
		FROM {$db_prefix}members
		WHERE ID_GROUP = 0", __FILE__, __LINE__);
	list ($num_members) = mysql_fetch_row($request);
	mysql_free_result($request);

	// Fill the context variable with 'Guests' and 'Ungrouped Members'.
	$context['groups'] = array(
		-1 => array(
			'id' => -1,
			'name' => $txt['membergroups_guests'],
			'num_members' => $txt['membergroups_guests_na'],
			'allow_delete' => false,
			'allow_modify' => true,
			'can_search' => false,
			'href' => '',
			'link' => '',
			'is_post_group' => false,
			'color' => '',
			'stars' => '',
			'num_permissions' => array(
				'allowed' => 0,
				'denied' => 0
			),
			'access' => false
		),
		0 => array(
			'id' => 0,
			'name' => $txt['membergroups_members'],
			'num_members' => $num_members,
			'allow_delete' => false,
			'allow_modify' => true,
			'can_search' => true,
			'href' => $scripturl . '?action=viewmembers;sa=query;params=' . base64_encode('ID_GROUP = 0'),
			'link' => '<a href="' . $scripturl . '?action=viewmembers;sa=query;params=' . base64_encode('ID_GROUP = 0') . '">' . $num_members . '</a>',
			'is_post_group' => false,
			'color' => '',
			'stars' => '',
			'num_permissions' => array(
				'allowed' => 0,
				'denied' => 0
			),
			'access' => false
		),
	);

	// Query the database defined membergroups.
	$query = db_query("
		SELECT mg.ID_GROUP, mg.groupName, mg.minPosts, mg.onlineColor, mg.stars, COUNT(mem.ID_MEMBER) AS num_members
		FROM {$db_prefix}membergroups AS mg
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_GROUP = mg.ID_GROUP OR FIND_IN_SET(mg.ID_GROUP, mem.additionalGroups) OR mg.ID_GROUP = mem.ID_POST_GROUP)
		GROUP BY mg.ID_GROUP
		ORDER BY mg.minPosts, IF(mg.ID_GROUP < 4, mg.ID_GROUP, 4), mg.groupName", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($query))
	{
		$row['stars'] = explode('#', $row['stars']);
		$context['groups'][$row['ID_GROUP']] = array(
			'id' => $row['ID_GROUP'],
			'name' => $row['groupName'],
			'num_members' => $row['ID_GROUP'] != 3 ? $row['num_members'] : $txt['membergroups_guests_na'],
			'allow_delete' => $row['ID_GROUP'] > 4,
			'allow_modify' => $row['ID_GROUP'] > 1,
			'can_search' => $row['ID_GROUP'] != 3,
			'href' => $scripturl . '?action=viewmembers;sa=query;params=' . base64_encode($row['minPosts'] == -1 ? "ID_GROUP = $row[ID_GROUP] OR FIND_IN_SET($row[ID_GROUP], additionalGroups)" : "ID_POST_GROUP = $row[ID_GROUP]"),
			'link' => '<a href="' . $scripturl . '?action=viewmembers;sa=query;params=' . base64_encode($row['minPosts'] == -1 ? "ID_GROUP = $row[ID_GROUP] OR FIND_IN_SET($row[ID_GROUP], additionalGroups)" : "ID_POST_GROUP = $row[ID_GROUP]") . '">' . $row['num_members'] . '</a>',
			'is_post_group' => $row['minPosts'] != -1,
			'color' => empty($row['onlineColor']) ? '' : $row['onlineColor'],
			'stars' => !empty($row['stars'][0]) && !empty($row['stars'][1]) ? str_repeat('<img src="' . $settings['images_url'] . '/' . $row['stars'][1] . '" alt="*" border="0" />', $row['stars'][0]) : '',
			'num_permissions' => array(
				'allowed' => $row['ID_GROUP'] == 1 ? '(' . $txt['permissions_all'] . ')' : 0,
				'denied' => $row['ID_GROUP'] == 1 ? '(' . $txt['permissions_none'] . ')' : 0
			),
			'access' => false
		);
	}
	mysql_free_result($query);

	$board_groups = array();
	foreach ($context['groups'] as $group)
		if ($group['allow_modify'])
			$board_groups[$group['id']] = array(
				'id' => &$group['id'],
				'name' => &$group['name'],
				'num_permissions' => array(
					'allowed' => 0,
					'denied' => 0
				),
			);

	if (empty($_REQUEST['boardid']))
	{
		$request = db_query("
			SELECT b.ID_BOARD, b.name, COUNT(mods.ID_MEMBER) AS moderators, b.memberGroups, b.use_local_permissions, b.childLevel
			FROM {$db_prefix}boards AS b, {$db_prefix}categories AS c
				LEFT JOIN {$db_prefix}moderators AS mods ON (mods.ID_BOARD = b.ID_BOARD)
			WHERE c.ID_CAT = b.ID_CAT
			GROUP BY b.ID_BOARD
			ORDER BY c.catOrder, b.boardOrder", __FILE__, __LINE__);
		$context['boards'] = array();
		while ($row = mysql_fetch_assoc($request))
		{
			$row['memberGroups'] = explode(',', $row['memberGroups']);
			$context['boards'][$row['ID_BOARD']] = array(
				'id' => $row['ID_BOARD'],
				'child_level' => $row['childLevel'],
				'name' => $row['name'],
				'num_moderators' => $row['moderators'],
				'public' => in_array(0, $row['memberGroups']) || in_array(-1, $row['memberGroups']),
				'membergroups' => $row['memberGroups'],
				'use_local_permissions' => $row['use_local_permissions'] == 1,
				'groups' => $board_groups
			);
		}
		mysql_free_result($request);

		$request = db_query("
			SELECT ID_GROUP, COUNT(permission) AS numPermissions, addDeny
			FROM {$db_prefix}permissions
			GROUP BY ID_GROUP, addDeny", __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
			if (isset($context['groups'][(int) $row['ID_GROUP']]))
				$context['groups'][(int) $row['ID_GROUP']]['num_permissions'][empty($row['addDeny']) ? 'denied' : 'allowed'] = $row['numPermissions'];
		mysql_free_result($request);

		$request = db_query("
			SELECT ID_BOARD, ID_GROUP, COUNT(permission) AS numPermissions, addDeny
			FROM {$db_prefix}board_permissions
			GROUP BY ID_BOARD, ID_GROUP, addDeny", __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
		{
			if ($row['ID_BOARD'] == 0)
			{
				if (isset($context['groups'][(int) $row['ID_GROUP']]))
					$context['groups'][(int) $row['ID_GROUP']]['num_permissions'][empty($row['addDeny']) ? 'denied' : 'allowed'] += $row['numPermissions'];
			}
			elseif (isset($context['boards'][$row['ID_BOARD']]) && isset($context['boards'][$row['ID_BOARD']]['groups'][(int) $row['ID_GROUP']]))
				$context['boards'][$row['ID_BOARD']]['groups'][(int) $row['ID_GROUP']]['num_permissions'][empty($row['addDeny']) ? 'denied' : 'allowed'] = $row['numPermissions'];
		}
		mysql_free_result($request);
	}
	else
	{
		$_REQUEST['boardid'] = (int) $_REQUEST['boardid'];

		$request = db_query("
			SELECT ID_BOARD, ID_GROUP, COUNT(permission) AS numPermissions, addDeny
			FROM {$db_prefix}board_permissions
			WHERE ID_BOARD = $_REQUEST[boardid]
			GROUP BY ID_BOARD, ID_GROUP, addDeny", __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
		{
			if (isset($context['groups'][(int) $row['ID_GROUP']]))
				$context['groups'][(int) $row['ID_GROUP']]['num_permissions'][empty($row['addDeny']) ? 'denied' : 'allowed'] += $row['numPermissions'];
		}
		mysql_free_result($request);

		$context['board'] = array(
			'id' => $_REQUEST['boardid']
		);

		// Load all the boards that we can set permissions off...
		$request = db_query("
			SELECT ID_BOARD, name
			FROM {$db_prefix}boards
			WHERE ID_BOARD != $_REQUEST[boardid]
				AND use_local_permissions = 1", __FILE__, __LINE__);
		$context['boards'] = array();
		while ($row = mysql_fetch_assoc($request))
			$context['copy_boards'][] = array(
				'id' => $row['ID_BOARD'],
				'name' => $row['name']
			);
		mysql_free_result($request);

		$request = db_query("
			SELECT name, memberGroups
			FROM {$db_prefix}boards
			WHERE ID_BOARD = $_REQUEST[boardid]
			LIMIT 1", __FILE__, __LINE__);
		list ($context['board']['name'], $groups) = mysql_fetch_row($request);
		mysql_free_result($request);

		$groups = explode(',', $groups);
		foreach ($groups as $group)
		{
			if ($group !== '' && isset($context['groups'][(int) $group]))
				$context['groups'][(int) $group]['access'] = true;
		}
	}
}

function SetQuickGroups()
{
	global $db_prefix;

	checkSession();

	// Make sure only one of the quick options was selected.
	if ((!empty($_POST['predefined']) && ((isset($_POST['copy_from']) && $_POST['copy_from'] != 'empty') || !empty($_POST['permissions']))) || (!empty($_POST['copy_from']) && $_POST['copy_from'] != 'empty' && !empty($_POST['permissions'])))
		fatal_lang_error('permissions_only_one_option');

	if (empty($_POST['group']) || !is_array($_POST['group']))
		$_POST['group'] = array();

	// Only accept numeric values for selected membergroups.
	foreach ($_POST['group'] as $id => $group_id)
		$_POST['group'][$id] = (int) $group_id;
	$_POST['group'] = array_unique($_POST['group']);

	if (empty($_REQUEST['boardid']))
		$_REQUEST['boardid'] = 0;
	else
		$_REQUEST['boardid'] = (int) $_REQUEST['boardid'];

	if (isset($_POST['access']))
	{
		foreach ($_POST['access'] as $k => $v)
			$_POST['access'][$k] = (int) $v;
		$access = implode(',', $_POST['access']);
	}
	else
		$access = '';

	db_query("
		UPDATE {$db_prefix}boards
		SET memberGroups = '$access'
		WHERE ID_BOARD = $_REQUEST[boardid]
		LIMIT 1", __FILE__, __LINE__);

	// No groups where selected.
	if (empty($_POST['group']))
		redirectexit('action=permissions;boardid=' . $_REQUEST['boardid']);

	// Set a predefined permission profile.
	if (!empty($_POST['predefined']))
	{
		// Make sure it's a predefined permission set we expect.
		if (!in_array($_POST['predefined'], array('restrict', 'standard', 'moderator', 'maintenance')))
			redirectexit('action=permissions;boardid=' . $_REQUEST['boardid']);

		foreach ($_POST['group'] as $group_id)
		{
			if (!empty($_REQUEST['boardid']))
				setPermissionLevel($_POST['predefined'], $group_id, $_REQUEST['boardid']);
			else
				setPermissionLevel($_POST['predefined'], $group_id);
		}
	}
	// Set the permissions of the selected groups to that of their permissions in a different board.
	elseif (isset($_POST['from_board']) && $_POST['from_board'] != 'empty')
	{
		// Just checking the input.
		if (!is_numeric($_POST['from_board']))
			redirectexit('action=permissions;boardid=' . $_REQUEST['boardid']);

		// Fetch all the board permissions for these groups.
		$request = db_query("
			SELECT ID_GROUP, permission, addDeny
			FROM {$db_prefix}board_permissions
			WHERE ID_BOARD = $_POST[from_board]
				AND ID_GROUP IN (" . implode(',', $_POST['group']) . ")", __FILE__, __LINE__);

		$target_perms = array();
		while ($row = mysql_fetch_assoc($request))
			$target_perms[] = "('$row[permission]', $row[ID_GROUP], $_REQUEST[boardid], $row[addDeny])";
		mysql_free_result($request);

		// Delete the previous global board permissions...
		db_query("
			DELETE FROM {$db_prefix}board_permissions
			WHERE ID_GROUP IN (" . implode(', ', $_POST['group']) . ")
				AND ID_BOARD = $_REQUEST[boardid]", __FILE__, __LINE__);

		// And insert the copied permissions.
		if (!empty($target_perms))
		{
			db_query("
				INSERT IGNORE INTO {$db_prefix}board_permissions
					(permission, ID_GROUP, ID_BOARD, addDeny)
				VALUES " . implode(',', $target_perms), __FILE__, __LINE__);
		}
	}
	// Set a permission profile based on the permissions of a selected group.
	elseif ($_POST['copy_from'] != 'empty')
	{
		// Just checking the input.
		if (!is_numeric($_POST['copy_from']))
			redirectexit('action=permissions;boardid=' . $_REQUEST['boardid']);

		// Make sure the group we're copying to is never included.
		$_POST['group'] = array_diff($_POST['group'], array($_POST['copy_from']));

		// No groups left? Too bad.
		if (empty($_POST['group']))
			redirectexit('action=permissions;boardid=' . $_REQUEST['boardid']);

		if (empty($_REQUEST['boardid']))
		{
			// Retrieve current permissions of group.
			$request = db_query("
				SELECT permission, addDeny
				FROM {$db_prefix}permissions
				WHERE ID_GROUP = $_POST[copy_from]", __FILE__, __LINE__);
			$target_perm = array();
			while ($row = mysql_fetch_assoc($request))
				$target_perm[$row['permission']] = $row['addDeny'];
			mysql_free_result($request);

			$insert_string = '';
			foreach ($_POST['group'] as $group_id)
				foreach ($target_perm as $perm => $addDeny)
					$insert_string .= "('$perm', $group_id, $addDeny),";

			// Delete the previous permissions...
			db_query("
				DELETE FROM {$db_prefix}permissions
				WHERE ID_GROUP IN (" . implode(', ', $_POST['group']) . ")", __FILE__, __LINE__);

			if (!empty($insert_string))
			{
				// Cut off the last comma.
				$insert_string = substr($insert_string, 0, -1);

				// ..and insert the new ones.
				db_query("
					INSERT IGNORE INTO {$db_prefix}permissions
						(permission, ID_GROUP, addDeny)
					VALUES $insert_string", __FILE__, __LINE__);
			}
		}

		// Now do the same for the board permissions.
		$request = db_query("
			SELECT permission, addDeny
			FROM {$db_prefix}board_permissions
			WHERE ID_GROUP = $_POST[copy_from]
				AND ID_BOARD = $_REQUEST[boardid]", __FILE__, __LINE__);
		$target_perm = array();
		while ($row = mysql_fetch_assoc($request))
			$target_perm[$row['permission']] = $row['addDeny'];
		mysql_free_result($request);

		$insert_string = '';
		foreach ($_POST['group'] as $group_id)
			foreach ($target_perm as $perm => $addDeny)
				$insert_string .= "('$perm', $group_id, $_REQUEST[boardid], $addDeny),";

		// Delete the previous global board permissions...
		db_query("
			DELETE FROM {$db_prefix}board_permissions
			WHERE ID_GROUP IN (" . implode(', ', $_POST['group']) . ")
				AND ID_BOARD = $_REQUEST[boardid]", __FILE__, __LINE__);

		// And insert the copied permissions.
		if (!empty($insert_string))
		{
			$insert_string = substr($insert_string, 0, -1);

			db_query("
				INSERT IGNORE INTO {$db_prefix}board_permissions
					(permission, ID_GROUP, ID_BOARD, addDeny)
				VALUES $insert_string", __FILE__, __LINE__);
		}
	}
	// Set or unset a certain permission for the selected groups.
	elseif (!empty($_POST['permissions']))
	{
		// Unpack two variables that were transported.
		list ($permissionType, $permission) = explode('/', $_POST['permissions']);

		// Check whether our input is within expected range.
		if (!in_array($_POST['add_remove'], array('add', 'clear', 'deny')) || !in_array($permissionType, array('membergroup', 'board')))
			redirectexit('action=permissions;boardid=' . $_REQUEST['boardid']);

		if ($_POST['add_remove'] == 'clear')
		{
			if ($permissionType == 'membergroup')
				db_query("
					DELETE FROM {$db_prefix}permissions
					WHERE ID_GROUP IN (" . implode(', ', $_POST['group']) . ")
						AND permission = '$permission'", __FILE__, __LINE__);
			else
				db_query("
					DELETE FROM {$db_prefix}board_permissions
					WHERE ID_GROUP IN (" . implode(', ', $_POST['group']) . ")
						AND ID_BOARD = $_REQUEST[boardid]
						AND permission = '$permission'", __FILE__, __LINE__);
		}
		// Add a permission (either 'set' or 'deny').
		else
		{
			$addDeny = $_POST['add_remove'] == 'add' ? '1' : '0';
			if ($permissionType == 'membergroup')
				db_query("
					REPLACE INTO {$db_prefix}permissions
						(permission, ID_GROUP, addDeny)
					VALUES
						('$permission', " . implode(", $addDeny),
						('$permission', ", $_POST['group']) . ", $addDeny)", __FILE__, __LINE__);
			// Board permissions go into the other table.
			else
				db_query("
					REPLACE INTO {$db_prefix}board_permissions
						(permission, ID_GROUP, ID_BOARD, addDeny)
					VALUES
						('$permission', " . implode(", $_REQUEST[boardid], $addDeny),
						('$permission', ", $_POST['group']) . ", $_REQUEST[boardid], $addDeny)", __FILE__, __LINE__);
		}
	}

	redirectexit('action=permissions;boardid=' . $_REQUEST['boardid']);
}

// Switch a board from local to global permissions or v.v.
function SwitchBoard()
{
	global $db_prefix;

	// Security above all.
	checkSession('get');
	validateSession();
	$_GET['boardid'] = (int) $_GET['boardid'];

	// Make sure the board exists and can be switched to $_GET['to'].
	$request = db_query("
		SELECT ID_BOARD
		FROM {$db_prefix}boards
		WHERE ID_BOARD = $_GET[boardid]
			AND use_local_permissions = " . ($_GET['to'] == 'local' ? '0' : '1') . "
		LIMIT 1", __FILE__, __LINE__);
	if (mysql_num_rows($request) != 1)
	{
		if ($_GET['to'] == 'local')
			PermissionIndex();
		else
			redirectexit('action=permissions');
		return;
	}
	mysql_free_result($request);

	// Copy the global permissions to the specific board.
	if ($_GET['to'] == 'local')
	{
		$request = db_query("
			SELECT ID_GROUP, permission, addDeny
			FROM {$db_prefix}board_permissions
			WHERE ID_BOARD = 0", __FILE__, __LINE__);
		$insertRows = array();
		while ($row = mysql_fetch_assoc($request))
			$insertRows[] = "($row[ID_GROUP], $_GET[boardid], '$row[permission]', $row[addDeny])";
		mysql_free_result($request);

		// Reset the current local permissions.
		db_query("
			DELETE FROM {$db_prefix}board_permissions
			WHERE ID_BOARD = $_GET[boardid]", __FILE__, __LINE__);

		if (!empty($insertRows))
			db_query("
				INSERT INTO {$db_prefix}board_permissions
					(ID_GROUP, ID_BOARD, permission, addDeny)
				VALUES " . implode(",
					", $insertRows), __FILE__, __LINE__);
	}

	// Switch back to inherited permissions (delete all local permissions).
	else
		db_query("
			DELETE FROM {$db_prefix}board_permissions
			WHERE ID_BOARD = $_GET[boardid]", __FILE__, __LINE__);

	// Update the board setting.
	db_query("
		UPDATE {$db_prefix}boards
		SET use_local_permissions = " . ($_GET['to'] == 'local' ? '1' : '0') . "
		WHERE ID_BOARD = $_GET[boardid]", __FILE__, __LINE__);

	if ($_GET['to'] == 'local')
		PermissionIndex();
	else
		redirectexit('action=permissions');
}

function ModifyMembergroup()
{
	global $db_prefix, $context, $txt;

	$context['group']['id'] = (int) $_GET['id'];

	loadAllPermissions();

	if ($context['group']['id'] > 0)
	{
		$result = db_query("
			SELECT groupName
			FROM {$db_prefix}membergroups
			WHERE ID_GROUP = {$context['group']['id']}
			LIMIT 1", __FILE__, __LINE__);
		list ($context['group']['name']) = mysql_fetch_row($result);
		mysql_free_result($result);
	}
	elseif ($context['group']['id'] == -1)
		$context['group']['name'] = &$txt['membergroups_guests'];
	else
		$context['group']['name'] = &$txt['membergroups_members'];

	$context['board']['id'] = empty($_GET['boardid']) ? 0 : (int) $_GET['boardid'];
	$context['local'] = !empty($_GET['boardid']);

	if ($context['local'])
	{
		$request = db_query("
			SELECT name
			FROM {$db_prefix}boards
			WHERE ID_BOARD = {$context['board']['id']}
				AND use_local_permissions = 1", __FILE__, __LINE__);
		// Either the board was not found or the permissions are set to global.
		if (mysql_num_rows($request) == 0)
			fatal_lang_error('smf232');
		list ($context['board']['name']) = mysql_fetch_row($request);
		mysql_free_result($request);
	}

	// Fetch the current permissions.
	$permissions = array(
		'membergroup' => array('allowed' => array(), 'denied' => array()),
		'board' => array('allowed' => array(), 'denied' => array())
	);
	if ($context['group']['id'] != 3 && !$context['local'])
	{
		$result = db_query("
			SELECT permission, addDeny
			FROM {$db_prefix}permissions
			WHERE ID_GROUP = $_GET[id]", __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($result))
			$permissions['membergroup'][empty($row['addDeny']) ? 'denied' : 'allowed'][] = $row['permission'];
		mysql_free_result($result);
		$context['permissions']['membergroup']['show'] = true;
	}
	else
		$context['permissions']['membergroup']['show'] = false;

	// Fetch current board permissions.
	$result = db_query("
		SELECT permission, addDeny
		FROM {$db_prefix}board_permissions
		WHERE ID_GROUP = {$context['group']['id']}
			AND ID_BOARD = {$context['board']['id']}", __FILE__, __LINE__);

	while ($row = mysql_fetch_assoc($result))
		$permissions['board'][empty($row['addDeny']) ? 'denied' : 'allowed'][] = $row['permission'];
	mysql_free_result($result);
	$context['permissions']['board']['show'] = true;

	// Loop through each permission and set whether it's checked.
	foreach ($context['permissions'] as $permissionType => $tmp)
	{
		foreach ($tmp['columns'] as $position => $permissionGroups)
		{
			foreach ($permissionGroups as $permissionGroup => $permissionArray)
			{
				foreach ($permissionArray['permissions'] as $perm)
				{
					// Create a shortcut for the current permission.
					$curPerm = &$context['permissions'][$permissionType]['columns'][$position][$permissionGroup]['permissions'][$perm['id']];
					if ($perm['has_own_any'])
					{
						$curPerm['any']['select'] = in_array($perm['id'] . '_any', $permissions[$permissionType]['allowed']) ? 'on' : (in_array($perm['id'] . '_any', $permissions[$permissionType]['denied']) ? 'denied' : 'off');
						$curPerm['own']['select'] = in_array($perm['id'] . '_own', $permissions[$permissionType]['allowed']) ? 'on' : (in_array($perm['id'] . '_own', $permissions[$permissionType]['denied']) ? 'denied' : 'off');
					}
					else
						$curPerm['select'] = in_array($perm['id'], $permissions[$permissionType]['denied']) ? 'denied' : (in_array($perm['id'], $permissions[$permissionType]['allowed']) ? 'on' : 'off');
				}
			}
		}
	}
	$context['sub_template'] = 'modify_group';
	$context['page_title'] = $txt['permissions_modify_group'];
}

function ModifyMembergroup2()
{
	global $db_prefix;

	checkSession();

	$_GET['id'] = (int) $_GET['id'];
	$_GET['boardid'] = (int) $_GET['boardid'];

	$givePerms = array('membergroup' => array(), 'board' => array());

	// Prepare all permissions that were set or denied for addition to the DB.
	foreach ($_POST['perm'] as $perm_type => $perm_array)
		foreach ($perm_array as $permission => $value)
			if ($value == 'on' || $value == 'deny')
				$givePerms[$perm_type][] = "$permission', " . ($value == 'on' ? '1' : '0');

	// Insert the general permissions.
	if ($_GET['id'] != 3 && empty($_GET['boardid']))
	{
		db_query("
			DELETE FROM {$db_prefix}permissions
			WHERE ID_GROUP = $_GET[id]", __FILE__, __LINE__);
		if (!empty($givePerms['membergroup']))
			db_query("
				INSERT IGNORE INTO {$db_prefix}permissions
					(ID_GROUP, permission, addDeny)
				VALUES ($_GET[id], '" . implode("),
					($_GET[id], '", $givePerms['membergroup']) . ")", __FILE__, __LINE__);
	}

	// Insert the boardpermissions.
	db_query("
		DELETE FROM {$db_prefix}board_permissions
		WHERE ID_GROUP = $_GET[id]
			AND ID_BOARD = $_GET[boardid]", __FILE__, __LINE__);
	if (!empty($givePerms['board']))
		db_query("
			INSERT IGNORE INTO {$db_prefix}board_permissions
				(ID_GROUP, ID_BOARD, permission, addDeny)
			VALUES ($_GET[id], $_GET[boardid], '" . implode("),
				($_GET[id], $_GET[boardid], '", $givePerms['board']) . ")", __FILE__, __LINE__);

	redirectexit('action=permissions;boardid=' . $_GET['boardid']);
}

// Set the permission level for a specific board, group, or group for a board.
function setPermissionLevel($level, $group, $board = 'null')
{
	global $db_prefix;

	// Levels by group... restrict, standard, moderator, maintenance.
	$groupLevels = array(
		'board' => array('inherit' => array()),
		'group' => array('inherit' => array())
	);
	// Levels by board... standard, publish, free.
	$boardLevels = array('inherit' => array());

	// Restrictive - ie. guests.
	$groupLevels['global']['restrict'] = array(
		'search_posts',
		'calendar_view',
		'view_stats',
		'who_view',
		'profile_view_own',
		'profile_identity_own',
	);
	$groupLevels['board']['restrict'] = array(
		'poll_view',
		'post_new',
		'post_reply_own',
		'post_reply_any',
		'remove_own',
		'modify_own',
		'mark_any_notify',
		'mark_notify',
		'report_any',
		'send_topic',
	);

	// Standard - ie. members.  They can do anything Restrictive can.
	$groupLevels['global']['standard'] = array_merge($groupLevels['global']['restrict'], array(
		'view_mlist',
		'karma_edit',
		'pm_read',
		'pm_send',
		'profile_view_any',
		'profile_extra_own',
		'profile_remote_avatar',
		'profile_remove_own',
	));
	$groupLevels['board']['standard'] = array_merge($groupLevels['board']['restrict'], array(
		'poll_vote',
		'poll_edit_own',
		'poll_post',
		'poll_add_own',
		'post_attachment',
		'lock_own',
		'delete_own',
		'view_attachments',
	));

	// Moderator - ie. moderators :P.  They can do what standard can, and more.
	$groupLevels['global']['moderator'] = array_merge($groupLevels['global']['standard'], array(
		'calendar_post',
		'calendar_edit_own',
	));
	$groupLevels['board']['moderator'] = array_merge($groupLevels['board']['standard'], array(
		'make_sticky',
		'poll_edit_any',
		'remove_any',
		'modify_any',
		'lock_any',
		'delete_any',
		'move_any',
		'merge_any',
		'split_any',
		'poll_lock_any',
		'poll_remove_any',
		'poll_add_any',
	));

	// Maintenance - wannabe admins.  They can do almost everything.
	$groupLevels['global']['maintenance'] = array_merge($groupLevels['global']['moderator'], array(
		'manage_attachments',
		'manage_smileys',
		'manage_boards',
		'moderate_forum',
		'manage_membergroups',
		'manage_bans',
		'admin_forum',
		'manage_permissions',
		'edit_news',
		'calendar_edit_any',
		'profile_identity_any',
		'profile_extra_any',
		'profile_title_any',
	));
	$groupLevels['board']['maintenance'] = array_merge($groupLevels['board']['moderator'], array(
	));

	// Standard - nothing above the group permissions. (this SHOULD be empty.)
	$boardLevels['standard'] = array(
	);

	// Locked - just that, you can't post here.
	$boardLevels['locked'] = array(
		'poll_view',
		'mark_notify',
		'report_any',
		'send_topic',
		'view_attachments',
	);

	// Publisher - just a little more...
	$boardLevels['publish'] = array_merge($boardLevels['locked'], array(
		'post_new',
		'post_reply_own',
		'post_reply_any',
		'remove_own',
		'modify_own',
		'mark_any_notify',
		'remove_replies',
		'modify_replies',
		'poll_vote',
		'poll_edit_own',
		'poll_post',
		'poll_add_own',
		'poll_remove_own',
		'post_attachment',
		'lock_own',
		'delete_own',
	));

	// Free for All - Scary.  Just scary.
	$boardLevels['free'] = array_merge($boardLevels['publish'], array(
		'poll_lock_any',
		'poll_edit_any',
		'poll_add_any',
		'poll_remove_any',
		'make_sticky',
		'lock_any',
		'delete_any',
		'remove_any',
		'split_any',
		'merge_any',
		'modify_any',
	));

	// Setting group permissions.
	if ($board === 'null' && $group !== 'null')
	{
		$group = (int) $group;

		if (empty($groupLevels['global'][$level]))
			return;

		db_query("
			DELETE FROM {$db_prefix}permissions
			WHERE ID_GROUP = $group", __FILE__, __LINE__);
		db_query("
			DELETE FROM {$db_prefix}board_permissions
			WHERE ID_GROUP = $group
				AND ID_BOARD = 0", __FILE__, __LINE__);

		db_query("
			INSERT INTO {$db_prefix}permissions
				(ID_GROUP, permission)
			VALUES ($group, '" . implode("'),
				($group, '", $groupLevels['global'][$level]) . "')", __FILE__, __LINE__);
		db_query("
			INSERT INTO {$db_prefix}board_permissions
				(ID_BOARD, ID_GROUP, permission)
			VALUES (0, $group, '" . implode("'),
				(0, $group, '", $groupLevels['board'][$level]) . "')", __FILE__, __LINE__);
	}
	// Setting board permissions for a specific group.
	elseif ($board !== 'null' && $group !== 'null')
	{
		$group = (int) $group;
		$board = (int) $board;

		if (!empty($groupLevels['global'][$level]))
		{
			db_query("
				DELETE FROM {$db_prefix}board_permissions
				WHERE ID_GROUP = $group
					AND ID_BOARD = $board", __FILE__, __LINE__);
		}

		if (!empty($groupLevels['board'][$level]))
		{
			db_query("
				INSERT INTO {$db_prefix}board_permissions
					(ID_BOARD, ID_GROUP, permission)
				VALUES ($board, $group, '" . implode("'),
					($board, $group, '", $groupLevels['board'][$level]) . "')", __FILE__, __LINE__);
		}
	}
	// Setting board permissions for all groups.
	elseif ($board !== 'null' && $group === 'null')
	{
		$board = (int) $board;

		db_query("
			DELETE FROM {$db_prefix}board_permissions
			WHERE ID_BOARD = $board", __FILE__, __LINE__);

		if (empty($boardLevels[$level]))
			return;

		// Get all the groups...
		$query = db_query("
			SELECT ID_GROUP
			FROM {$db_prefix}membergroups
			WHERE ID_GROUP > 3
			ORDER BY minPosts, IF(ID_GROUP < 4, ID_GROUP, 4), groupName", __FILE__, __LINE__);
		while ($row = mysql_fetch_row($query))
		{
			$group = $row[0];

			db_query("
				INSERT INTO {$db_prefix}board_permissions
					(ID_BOARD, ID_GROUP, permission)
				VALUES ($board, $group, '" . implode("'),
					($board, $group, '", $boardLevels[$level]) . "')", __FILE__, __LINE__);
		}
		mysql_free_result($query);

		// Add permissions for ungrouped users.
		db_query("
			INSERT INTO {$db_prefix}board_permissions
				(ID_BOARD, ID_GROUP, permission)
			VALUES ($board, 0, '" . implode("'),
				($board, 0, '", $boardLevels[$level]) . "')", __FILE__, __LINE__);
	}
	// $board and $group are both null!
	else
		fatal_lang_error(1, false);
}

function loadAllPermissions()
{
	global $context, $txt;

/*	 The format of this list is as follows:
		'permission_group' => array(
			'permissions_inside' => has_multiple_options,
		),

	   It should be noted that if the permission_group starts with $ it is not treated as a permission.
	   However, if it does not start with $, it is treated as a normal permission.
		$txt['permissionname_' . $permission] is used for the names of permissions.
		$txt['permissiongroup_' . $group] is used for names of groups that start with $.
		$txt['permissionhelp_' . $permission] is used for extended information.
		$txt['permissionicon_' . $permission_or_group] is used for the icons, if it exists.
*/

	$permissionList = array(
		'membergroup' => array(
			'general' => array(
				'view_stats' => false,
				'view_mlist' => false,
				'who_view' => false,
				'search_posts' => false,
				'karma_edit' => false,
			),
			'pm' => array(
				'pm_read' => false,
				'pm_send' => false,
			),
			'calendar' => array(
				'calendar_view' => false,
				'calendar_post' => false,
				'calendar_edit' => true,
			),
			'maintenance' => array(
				'admin_forum' => false,
				'manage_boards' => false,
				'manage_attachments' => false,
				'manage_smileys' => false,
				'edit_news' => false,
			),
			'member_admin' => array(
				'moderate_forum' => false,
				'manage_membergroups' => false,
				'manage_permissions' => false,
				'manage_bans' => false,
				'send_mail' => false,
			),
			'profile' => array(
				'profile_view' => true,
				'profile_identity' => true,
				'profile_extra' => true,
				'profile_title' => true,
				'profile_remove' => true,
				'profile_remote_avatar' => false,
			)
		),
		'board' => array(
			'general_board' => array(
				'moderate_board' => false,
			),
			'topic' => array(
				'post_new' => false,
				'merge_any' => false,
				'split_any' => false,
				'send_topic' => false,
				'make_sticky' => false,
				'move' => true,
				'lock' => true,
				'delete' => true,
				'post_reply' => true,
				'modify_replies' => false,
				'remove_replies' => false,
				'announce_topic' => false,
			),
			'post' => array(
				'remove' => true,
				'modify' => true,
				'report_any' => false,
			),
			'poll' => array(
				'poll_view' => false,
				'poll_vote' => false,
				'poll_post' => false,
				'poll_add' => true,
				'poll_edit' => true,
				'poll_lock' => true,
				'poll_remove' => true,
			),
			'notification' => array(
				'mark_any_notify' => false,
				'mark_notify' => false,
			),
			'attachment' => array(
				'view_attachments' => false,
				'post_attachment' => false,
			)
		)
	);

	// This is just a helpful array of permissions guests... cannot have.
	$non_guest_permissions = array(
		'karma_edit',
		'pm_read',
		'pm_send',
		'profile_identity',
		'profile_extra',
		'profile_title',
		'profile_remove',
		'profile_remote_avatar',
		'poll_vote',
		'mark_any_notify',
		'mark_notify',
	);

	// All permission groups that will be shown in the left column.
	$leftPermissionGroups = array(
		'general',
		'calendar',
		'maintenance',
		'member_admin',
		'general_board',
		'topic',
		'post',
	);

	$context['permissions'] = array();
	foreach ($permissionList as $permissionType => $permissionGroups)
	{
		$context['permissions'][$permissionType] = array(
			'id' => $permissionType,
			'columns' => array(
				'left' => array(),
				'right' => array()
			)
		);
		foreach ($permissionGroups as $permissionGroup => $permissionArray)
		{
			$position = in_array($permissionGroup, $leftPermissionGroups) ? 'left' : 'right';
			$context['permissions'][$permissionType]['columns'][$position][$permissionGroup] = array(
				'type' => $permissionType,
				'id' => $permissionGroup,
				'name' => &$txt['permissiongroup_' . $permissionGroup],
				'icon' => isset($txt['permissionicon_' . $permissionGroup]) ? $txt['permissionicon_' . $permissionGroup] : $txt['permissionicon'],
				'help' => isset($txt['permissionhelp_' . $permissionGroup]) ? $txt['permissionhelp_' . $permissionGroup] : '',
				'permissions' => array()
			);

			foreach ($permissionArray as $perm => $has_own_any)
			{
				if (isset($context['group']['id']) && $context['group']['id'] == -1 && in_array($perm, $non_guest_permissions))
					continue;

				$context['permissions'][$permissionType]['columns'][$position][$permissionGroup]['permissions'][$perm] = array(
					'id' => $perm,
					'name' => &$txt['permissionname_' . $perm],
					'show_help' => isset($txt['permissionhelp_' . $perm]),
					'has_own_any' => $has_own_any,
					'own' => array(
						'id' => $perm . '_own',
						'name' => $has_own_any ? $txt['permissionname_' . $perm . '_own'] : ''
					),
					'any' => array(
						'id' => $perm . '_any',
						'name' => $has_own_any ? $txt['permissionname_' . $perm . '_any'] : ''
					)
				);
			}

			if (empty($context['permissions'][$permissionType]['columns'][$position][$permissionGroup]['permissions']))
				unset($context['permissions'][$permissionType]['columns'][$position][$permissionGroup]);
		}
	}
}

?>