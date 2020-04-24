<?php
/******************************************************************************
* ManageMembers.php                                                           *
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

function ModifyMembergroups()
{
	isAllowedTo('manage_membergroups');

	adminIndex('edit_groups');

	loadTemplate('ManageMembers');
	loadLanguage('ManageMembers');

	$subActions = array(
		'add' => 'AddMembergroup',
		'delete' => 'DeleteMembergroup',
		'edit' => 'EditMembergroup',
		'members' => 'MembergroupMembers'
	);

	if (isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]))
	{
		$sa = $subActions[$_REQUEST['sa']];
		unset($subActions);

		$sa();
	}
	else
		MembergroupIndex();
}

function MembergroupIndex()
{
	global $db_prefix, $txt, $scripturl, $context, $settings;

	$context['page_title'] = $txt['membergroups_title'];

	$query = db_query("
		SELECT mg.ID_GROUP, mg.groupName, mg.minPosts, mg.onlineColor, mg.stars, COUNT(mem.ID_MEMBER) AS num_members
		FROM {$db_prefix}membergroups AS mg
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_GROUP = mg.ID_GROUP OR FIND_IN_SET(mg.ID_GROUP, mem.additionalGroups) OR mg.ID_GROUP = mem.ID_POST_GROUP)
		GROUP BY mg.ID_GROUP
		ORDER BY mg.minPosts, IF(mg.ID_GROUP < 4, mg.ID_GROUP, 4), mg.groupName", __FILE__, __LINE__);
	$context['groups'] = array(
		'regular' => array(),
		'post' => array()
	);
	while ($row = mysql_fetch_assoc($query))
	{
		$row['stars'] = explode('#', $row['stars']);
		$context['groups'][$row['minPosts'] == -1 ? 'regular' : 'post'][$row['ID_GROUP']] = array(
			'id' => $row['ID_GROUP'],
			'name' => $row['groupName'],
			'num_members' => $row['ID_GROUP'] != 3 ? $row['num_members'] : $txt['membergroups_guests_na'],
			'allow_delete' => $row['ID_GROUP'] > 4,
			'can_search' => $row['ID_GROUP'] != 3,
			'href' => $scripturl . '?action=membergroups;sa=members;id=' . $row['ID_GROUP'],
			'link' => '<a href="' . $scripturl . '?action=membergroups;sa=members;id=' . $row['ID_GROUP'] . '">' . $row['num_members'] . '</a>',
			'is_post_group' => $row['minPosts'] != -1,
			'min_posts' => $row['minPosts'] == -1 ? '-' : $row['minPosts'],
			'color' => empty($row['onlineColor']) ? '' : $row['onlineColor'],
			'stars' => !empty($row['stars'][0]) && !empty($row['stars'][1]) ? str_repeat('<img src="' . $settings['images_url'] . '/' . $row['stars'][1] . '" alt="*" border="0" />', $row['stars'][0]) : '',
			'last_group' => false
		);
	}
	mysql_free_result($query);

	$request = db_query("
		SELECT COUNT(ID_MEMBER)
		FROM {$db_prefix}members
		WHERE ID_GROUP = 0", __FILE__, __LINE__);
	list ($num_members) = mysql_fetch_row($request);
	mysql_free_result($request);

	$context['groups'][count($context['groups']) - 1]['last_group'] = true;
}

function AddMembergroup()
{
	global $db_prefix, $context, $txt, $sourcedir;

	if (empty($_POST['group_name']))
	{
		$context['page_title'] = $txt['membergroups_new_group'];
		$context['sub_template'] = 'new_group';
		$context['postgroup'] = !empty($_POST['postgroup']);

		$result = db_query("
			SELECT ID_GROUP, groupName
			FROM {$db_prefix}membergroups
			WHERE ID_GROUP > 3 OR ID_GROUP = 2
			ORDER BY minPosts, ID_GROUP != 2, groupName", __FILE__, __LINE__);
		$context['groups'] = array();
		while ($row = mysql_fetch_assoc($result))
			$context['groups'][] = array(
				'id' => $row['ID_GROUP'],
				'name' => $row['groupName']
			);
		mysql_free_result($result);

		$result = db_query("
			SELECT ID_BOARD, name, childLevel
			FROM {$db_prefix}boards
			ORDER BY boardOrder", __FILE__, __LINE__);
		$context['boards'] = array();
		while ($row = mysql_fetch_assoc($result))
			$context['boards'][] = array(
				'id' => $row['ID_BOARD'],
				'name' => $row['name'],
				'child_level' => $row['childLevel'],
				'selected' => false
			);
		mysql_free_result($result);

		return;
	}

	checkSession();

	$request = db_query("
		SELECT groupName
		FROM {$db_prefix}membergroups
		WHERE groupName = '$_POST[group_name]'
		LIMIT 1", __FILE__, __LINE__);
	if (mysql_num_rows($request) != 0)
		redirectexit('action=membergroups');
	mysql_free_result($request);

	$request = db_query("
		SELECT MAX(ID_GROUP)
		FROM {$db_prefix}membergroups", __FILE__, __LINE__);
	list ($ID_GROUP) = mysql_fetch_row($request);
	mysql_free_result($request);
	$ID_GROUP++;

	db_query("
		INSERT INTO {$db_prefix}membergroups
			(ID_GROUP, groupName, minPosts, stars)
		VALUES ($ID_GROUP, '$_POST[group_name]', " . (isset($_POST['min_posts']) ? (int) $_POST['min_posts'] : '-1') . ", '1#star.gif')", __FILE__, __LINE__);

	// Update the post groups now, if this is a post group!
	if (isset($_POST['min_posts']))
		updateStats('postgroups');

	if (!isset($_POST['copyperm']) || $_POST['copyperm'] == 1)
	{
		// Set default permission level.
		require_once($sourcedir . '/ManagePermissions.php');
		setPermissionLevel($_POST['level'], $ID_GROUP, 'null');
	}
	// Copy the permissions!
	else
	{
		$_POST['copyperm'] = (int) $_POST['copyperm'];

		$request = db_query("
			SELECT permission, addDeny
			FROM {$db_prefix}permissions
			WHERE ID_GROUP = $_POST[copyperm]", __FILE__, __LINE__);
		$setString = '';
		while ($row = mysql_fetch_assoc($request))
			$setString .= "
				($ID_GROUP, '$row[permission]', $row[addDeny]),";
		mysql_free_result($request);

		if (!empty($setString))
			db_query("
				INSERT INTO {$db_prefix}permissions
					(ID_GROUP, permission, addDeny)
				VALUES" . substr($setString, 0, -1), __FILE__, __LINE__);

		$request = db_query("
			SELECT ID_BOARD, permission, addDeny
			FROM {$db_prefix}board_permissions
			WHERE ID_GROUP = $_POST[copyperm]", __FILE__, __LINE__);
		$setString = '';
		while ($row = mysql_fetch_assoc($request))
			$setString .= "
				($ID_GROUP, $row[ID_BOARD], '$row[permission]', $row[addDeny]),";
		mysql_free_result($request);

		if (!empty($setString))
			db_query("
				INSERT INTO {$db_prefix}board_permissions
					(ID_GROUP, ID_BOARD, permission, addDeny)
				VALUES" . substr($setString, 0, -1), __FILE__, __LINE__);

		// Also get some membergroup information if we're not copying from guests...
		if ($_POST['copyperm'] > 0)
		{
			$request = db_query("
				SELECT onlineColor, maxMessages, stars
				FROM {$db_prefix}membergroups
				WHERE ID_GROUP = $_POST[copyperm]
				LIMIT 1", __FILE__, __LINE__);
			$group_info = mysql_fetch_assoc($request);
			mysql_free_result($request);

			// ...and update the new membergroup with it.
			db_query("
				UPDATE {$db_prefix}membergroups
				SET
					onlineColor = '$group_info[onlineColor]',
					maxMessages = $group_info[maxMessages],
					stars = '$group_info[stars]'
				WHERE ID_GROUP = $ID_GROUP
				LIMIT 1", __FILE__, __LINE__);
		}
	}

	if (empty($_POST['boardaccess']))
		$_POST['boardaccess'] = array();

	$boards = array();
	foreach ($_POST['boardaccess'] as $id => $dummy)
		$boards[] = (int) $id;

	// If they have no special access requirements then skip the rest of this.
	if (count($boards) == 0)
		redirectexit('action=membergroups');

	// Now it's the time to sort out which boards this new group has access to.
	$result = db_query("
		SELECT ID_BOARD, memberGroups
		FROM {$db_prefix}boards
		WHERE ID_BOARD IN (" . implode(', ', $boards) . ")
		LIMIT " . count($boards), __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($result))
	{
		// They should have access... but there is a list of VIPs.
		$memberGroups = explode(',', $row['memberGroups']);
		$memberGroups[] = $ID_GROUP;

		db_query("
			UPDATE {$db_prefix}boards
			SET memberGroups = '" . implode(',', $memberGroups) . "'
			WHERE ID_BOARD = $row[ID_BOARD]
			LIMIT 1", __FILE__, __LINE__);
	}
	mysql_free_result($result);

	redirectexit('action=membergroups');
}

function DeleteMembergroup()
{
	global $db_prefix;

	checkSession('request');

	$_REQUEST['id'] = (int) $_REQUEST['id'];

	if ($_REQUEST['id'] <= 4)
		redirectexit('action=membergroups');

	db_query("
		DELETE FROM {$db_prefix}membergroups
		WHERE ID_GROUP = $_REQUEST[id]
		LIMIT 1", __FILE__, __LINE__);

	db_query("
		DELETE FROM {$db_prefix}permissions
		WHERE ID_GROUP = $_REQUEST[id]", __FILE__, __LINE__);

	db_query("
		DELETE FROM {$db_prefix}board_permissions
		WHERE ID_GROUP = $_REQUEST[id]", __FILE__, __LINE__);

	db_query("
		UPDATE {$db_prefix}members
		SET ID_GROUP = 0
		WHERE ID_GROUP = $_REQUEST[id]", __FILE__, __LINE__);

	$request = db_query("
		SELECT ID_MEMBER, additionalGroups
		FROM {$db_prefix}members
		WHERE FIND_IN_SET($_REQUEST[id], additionalGroups)", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
	{
		$row['additionalGroups'] = array_flip(explode(',', $row['additionalGroups']));
		unset($row['additionalGroups'][$_REQUEST['id']]);
		$row['additionalGroups'] = implode(',', array_keys($row['additionalGroups']));

		updateMemberData($row['ID_MEMBER'], array('additionalGroups' => '\'' . $row['additionalGroups'] . '\''));
	}
	mysql_free_result($request);

	$request = db_query("
		SELECT ID_BOARD, memberGroups
		FROM {$db_prefix}boards
		WHERE FIND_IN_SET($_REQUEST[id], memberGroups)", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
	{
		$row['memberGroups'] = array_flip(explode(',', $row['memberGroups']));
		unset($row['memberGroups'][$_REQUEST['id']]);
		$row['memberGroups'] = implode(',', array_keys($row['memberGroups']));

		db_query("
			UPDATE {$db_prefix}boards
			SET memberGroups = '$row[memberGroups]'
			WHERE ID_BOARD = $row[ID_BOARD]
			LIMIT 1", __FILE__, __LINE__);
	}

	// Recalculate the post groups, as they likely changed.
	updateStats('postgroups');

	redirectexit('action=membergroups');
}

function EditMembergroup()
{
	global $db_prefix, $context, $txt;

	$_GET['id'] = empty($_GET['id']) || $_GET['id'] < 0 ? 1 : (int) $_GET['id'];

	if (isset($_POST['delete']))
		DeleteMembergroup();
	elseif (isset($_POST['submit']))
	{
		checkSession();

		$_POST['max_messages'] = (int) $_POST['max_messages'];
		$_POST['min_posts'] = isset($_POST['min_posts']) && $_POST['post_group'] == '1' && $_GET['id'] > 3 ? abs($_POST['min_posts']) : ($_GET['id'] == 4 ? 0 : -1);
		$_POST['stars'] = (empty($_POST['star_count']) || $_POST['star_count'] < 0) ? '' : min((int) $_POST['star_count'], 99) . '#' . $_POST['star_image'];

		db_query("
			UPDATE {$db_prefix}membergroups
			SET groupName = '$_POST[group_name]', onlineColor = '$_POST[online_color]',
				maxMessages = $_POST[max_messages], minPosts = $_POST[min_posts], stars = '$_POST[stars]'
			WHERE ID_GROUP = $_GET[id]
			LIMIT 1", __FILE__, __LINE__);

		// There might have been some post group changes.
		updateStats('postgroups');

		redirectexit('action=membergroups');
	}

	$result = db_query("
		SELECT groupName, minPosts, onlineColor, maxMessages, stars
		FROM {$db_prefix}membergroups
		WHERE ID_GROUP = $_GET[id]
		LIMIT 1", __FILE__, __LINE__);
	$row = mysql_fetch_assoc($result);
	mysql_free_result($result);

	$row['stars'] = explode('#', $row['stars']);

	$context['group'] = array(
		'id' => $_GET['id'],
		'name' => $row['groupName'],
		'editable_name' => htmlspecialchars($row['groupName']),
		'color' => $row['onlineColor'],
		'min_posts' => $row['minPosts'],
		'max_messages' => $row['maxMessages'],
		'star_count' => (int) $row['stars'][0],
		'star_image' => isset($row['stars'][1]) ? $row['stars'][1] : '',
		'is_post_group' => $row['minPosts'] != -1,
		'allow_post_group' => $_GET['id'] > 4,
		'allow_delete' => $_GET['id'] > 4
	);

	$context['sub_template'] = 'edit_group';
	$context['page_title'] = $txt['membergroups_edit_group'];
}

// Display members of a group, and allow adding of members to a group. Silly function name though ;)
function MembergroupMembers()
{
	global $txt, $scripturl, $db_prefix, $context, $modSettings;

	// Start!
	$context['start'] = isset($_REQUEST['start']) ? (int) $_REQUEST['start'] : 0;

	// Load up the group details - and ensure this ISN'T a post group ;)
	$request = db_query("
		SELECT ID_GROUP AS id, groupName AS name, minPosts = -1 AS assignable
		FROM {$db_prefix}membergroups
		WHERE ID_GROUP = $_REQUEST[id]", __FILE__, __LINE__);
	// Not really possible...
	if (mysql_num_rows($request) == 0)
		fatal_lang_error(1);
	$context['group'] = mysql_fetch_assoc($request);
	mysql_free_result($request);

	if ($context['group']['id'] == 1 && !allowedTo('admin_forum'))
		$context['group']['assignable'] = 0;

	// Changing members in this group?
	if (isset($_POST['sc']) && $context['group']['assignable'] && $_REQUEST['id'] != 3)
	{
		checkSession();

		// Removing member from group?
		if (isset($_POST['remove']) && isset($_REQUEST['rem']))
		{
			$members = array();
			foreach ($_REQUEST['rem'] AS $remove => $dummy)
				$members[] = (int) $remove;

			// First, reset those who have this as their primary group - this is the easy one.
			db_query("
				UPDATE {$db_prefix}members
				SET ID_GROUP = 0
				WHERE ID_GROUP = $_REQUEST[id]
					AND ID_MEMBER IN (" . implode(', ', $members) . ")
				LIMIT " . count($members), __FILE__, __LINE__);

			// Those who have it as part of their additional group must be updated the long way... sadly.
			$request = db_query("
				SELECT ID_MEMBER, additionalGroups
				FROM {$db_prefix}members
				WHERE FIND_IN_SET($_REQUEST[id], additionalGroups)
					AND ID_MEMBER IN (" . implode(', ', $members) . ")
				LIMIT " . count($members), __FILE__, __LINE__);
			while ($row = mysql_fetch_assoc($request))
			{
				$tempGroup = array_flip(explode(',', $row['additionalGroups']));
				unset($tempGroup[$_REQUEST['id']]);
				$tempGroup = implode(',', array_flip($tempGroup));

				// Do the update for this member - this may be slow for lots of people... but how many you really do at once?
				db_query("
					UPDATE {$db_prefix}members
					SET additionalGroups = '$tempGroup'
					WHERE ID_MEMBER = $row[ID_MEMBER]
					LIMIT 1", __FILE__, __LINE__);
			}
			mysql_free_result($request);
		}
		// Must be adding...
		elseif (isset($_REQUEST['add']) && !empty($_REQUEST['toAdd']))
		{
			// Get all the members to be added... taking into account names can be quoted ;)
			$_REQUEST['toAdd'] = strtr(un_htmlspecialchars($_REQUEST['toAdd']), array('\\"' => '"'));

			preg_match_all('~"([^"]+)"~', $_REQUEST['toAdd'], $matches);
			$memberQuery = array_unique(array_merge($matches[1], explode(',', preg_replace('~"([^"]+)"~', '', $_REQUEST['toAdd']))));

			foreach ($memberQuery as $index => $member)
			{
				if (strlen(trim($member)) > 0)
					$memberQuery[$index] = strtolower(trim($member));
				else
					unset($memberQuery[$index]);
			}

			$request = db_query("
				SELECT ID_MEMBER, ID_GROUP, additionalGroups
				FROM {$db_prefix}members
				WHERE memberName IN ('" . implode("', '", $memberQuery) . "')", __FILE__, __LINE__);

			// Reset the query array and we'll use it to update the members.
			$memberQuery = array(
				'main_group' => array(),
				'additional' => array()
			);

			while ($row = mysql_fetch_assoc($request))
			{
				// Verify that they are not already a member - and add them to our array.
				if ($row['ID_GROUP'] != $_REQUEST['id'] && !in_array($_REQUEST['id'], explode(',', $row['additionalGroups'])))
					$memberQuery[$row['ID_GROUP'] == 0 ? 'main_group' : 'additional'][] = $row['ID_MEMBER'];
			}
			mysql_free_result($request);

			// Do the updates...
			if (!empty($memberQuery['main_group']))
				db_query("
					UPDATE {$db_prefix}members
					SET ID_GROUP = $_REQUEST[id]
					WHERE ID_MEMBER IN (" . implode(', ', $memberQuery['main_group']) . ")
					LIMIT " . count($memberQuery['main_group']), __FILE__, __LINE__);

			// This one is more complicated!
			if (!empty($memberQuery['additional']))
			{
				db_query("
					UPDATE {$db_prefix}members
					SET additionalGroups = IF(additionalGroups = '', '$_REQUEST[id]', CONCAT(additionalGroups, ',$_REQUEST[id]'))
					WHERE ID_MEMBER IN (" . implode(', ', $memberQuery['additional']) . ")
					LIMIT " . count($memberQuery['additional']), __FILE__, __LINE__);
			}
		}
	}
	// Sort out the sorting!
	$sort_methods = array(
		'name' => 'realName',
		'email' => 'emailAddress',
		'active' => 'lastLogin',
		'registered' => 'dateRegistered',
		'posts' => 'posts',
	);

	// They didn't pick one, default to by name..
	if (!isset($_REQUEST['sort']) || !isset($sort_methods[$_REQUEST['sort']]))
	{
		$context['sort_by'] = 'name';
		$querySort = 'realName';
	}
	// Otherwise default to ascending.
	else
	{
		$context['sort_by'] = $_REQUEST['sort'];
		$querySort = $sort_methods[$_REQUEST['sort']];
	}

	$context['sort_direction'] = isset($_REQUEST['desc']) ? 'down' : 'up';

	// Count members of the group.
	$request = db_query("
		SELECT COUNT(ID_MEMBER)
		FROM {$db_prefix}members
		WHERE " . ($context['group']['assignable'] ? "ID_GROUP = $_REQUEST[id] OR FIND_IN_SET($_REQUEST[id], additionalGroups)" : "ID_POST_GROUP = $_REQUEST[id]"), __FILE__, __LINE__);
	list ($context['total_members']) = mysql_fetch_row($request);
	mysql_free_result($request);

	// Create the page index.
	$context['page_index'] = constructPageIndex($scripturl . '?action=membergroups;sa=members;id=' . $_REQUEST['id'] . ';sort=' . $context['sort_by'] . (isset($_REQUEST['desc']) ? ';desc' : ''), $context['start'], $context['total_members'], $modSettings['defaultMaxMembers']);

	// Load up all members of this group.
	$request = db_query("
		SELECT ID_MEMBER, realName, memberName, emailAddress, memberIP, dateRegistered, lastLogin, posts
		FROM {$db_prefix}members
		WHERE " . ($context['group']['assignable'] ? "ID_GROUP = $_REQUEST[id] OR FIND_IN_SET($_REQUEST[id], additionalGroups)" : "ID_POST_GROUP = $_REQUEST[id]") . "
		ORDER BY $querySort " . ($context['sort_direction'] == 'down' ? 'DESC' : 'ASC') . "
		LIMIT $context[start], $modSettings[defaultMaxMembers]", __FILE__, __LINE__);
	$context['members'] = array();
	while ($row = mysql_fetch_assoc($request))
		$context['members'][] = array(
			'id' => $row['ID_MEMBER'],
			'name' => '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['realName'] . '</a>',
			'email' => '<a href="mailto:' . $row['emailAddress'] . '">' . $row['emailAddress'] . '</a>',
			'ip' => '<a href="' . $scripturl . '?action=trackip;searchip=' . $row['memberIP'] . '">' . $row['memberIP'] . '</a>',
			'registered' => timeformat($row['dateRegistered']),
			'last_online' => empty($row['lastLogin']) ? $txt['never'] : timeformat($row['lastLogin']),
			'posts' => $row['posts'],
		);
	mysql_free_result($request);

	// Select the template.
	$context['sub_template'] = 'group_members';
	$context['page_title'] = $txt['membergroups_members_title'] . ': ' . $context['group']['name'];
}

// View all members.
function ViewMembers()
{
	global $txt, $scripturl, $db_prefix, $context, $modSettings;

	isAllowedTo('moderate_forum');

	// Administration bar, I choose you!
	adminIndex('view_members');
	loadTemplate('ManageMembers');
	loadLanguage('ManageMembers');

	$allowed_sub_actions = array('all', 'search', 'query', 'delete');

	// Set default sub action.
	$context['sub_action'] = empty($_REQUEST['sa']) || !in_array($_REQUEST['sa'], $allowed_sub_actions) ? 'all' : $_REQUEST['sa'];

	if ($context['sub_action'] == 'delete' && allowedTo('profile_remove_any'))
	{
		checkSession();

		// Delete all the selected members.
		deleteMembers(array_keys($_POST['delete']));

		// Update the latest member...
		updateStats('member');

		// Switch to 'view all members'.
		$context['sub_action'] = 'all';
	}

	// Retrieve the membergroups and postgroups.
	if (in_array($context['sub_action'], array('search', 'query')))
	{
		$context['membergroups'] = array(
			array(
				'id' => 0,
				'name' => $txt['membergroups_members'],
				'can_be_additional' => false
			)
		);
		$context['postgroups'] = array();
		$request = db_query("
			SELECT ID_GROUP, groupName, minPosts
			FROM {$db_prefix}membergroups
			WHERE ID_GROUP != 3
			ORDER BY minPosts, IF(ID_GROUP < 4, ID_GROUP, 4), groupName", __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
		{
			if ($row['minPosts'] == -1)
				$context['membergroups'][] = array(
					'id' => $row['ID_GROUP'],
					'name' => $row['groupName'],
					'can_be_additional' => true
				);
			else
				$context['postgroups'][] = array(
					'id' => $row['ID_GROUP'],
					'name' => $row['groupName']
				);
		}
	}

	// Check input after a member search has been submitted.
	if ($context['sub_action'] == 'query' && empty($_REQUEST['params']))
	{
		// Some data about the form fields and how they are linked to the database.
		$params = array(
			'mem_id' => array(
				'db_fields' => array('ID_MEMBER'),
				'type' => 'int',
				'range' => true
			),
			'age' => array(
				'db_fields' => array('birthdate'),
				'type' => 'age',
				'range' => true
			),
			'posts' => array(
				'db_fields' => array('posts'),
				'type' => 'int',
				'range' => true
			),
			'reg_date' => array(
				'db_fields' => array('dateRegistered'),
				'type' => 'date',
				'range' => true
			),
			'last_online' => array(
				'db_fields' => array('lastLogin'),
				'type' => 'date',
				'range' => true
			),
			'gender' => array(
				'db_fields' => array('gender'),
				'type' => 'checkbox',
				'values' => array('0', '1', '2'),
			),
			'activated' => array(
				'db_fields' => array('is_activated'),
				'type' => 'checkbox',
				'values' => array('0', '1'),
			),
			'membername' => array(
				'db_fields' => array('memberName', 'realName'),
				'type' => 'string'
			),
			'email' => array(
				'db_fields' => array('emailAddress'),
				'type' => 'string'
			),
			'website' => array(
				'db_fields' => array('websiteTitle', 'websiteUrl'),
				'type' => 'string'
			),
			'location' => array(
				'db_fields' => array('location'),
				'type' => 'string'
			),
			'ip' => array(
				'db_fields' => array('memberIP'),
				'type' => 'string'
			),
			'messenger' => array(
				'db_fields' => array('ICQ', 'AIM', 'YIM', 'MSN'),
				'type' => 'string'
			)
		);
		$range_trans = array(
			'--' => '<',
			'-' => '<=',
			'=' => '=',
			'+' => '>=',
			'++' => '>'
		);

		// Loop through every field of the form.
		$query_parts = array();
		foreach ($params as $param_name => $param_info)
		{
			// Not filled in?
			if (!isset($_POST[$param_name]) || $_POST[$param_name] == '')
				continue;

			// Make sure numeric values are really numeric.
			if (in_array($param_info['type'], array('int', 'age')))
				$_POST[$param_name] = (int) $_POST[$param_name];
			// Date values have to match the specified format.
			elseif ($param_info['type'] == 'date')
			{
				// Check if this date format is valid.
				if (!preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/', $_POST[$param_name]))
					continue;

				// Add quotes for the database.
				$_POST[$param_name] = strtotime($_POST[$param_name]);
			}

			// Those values that are in some kind of range (<, <=, =, >=, >).
			if (!empty($param_info['range']))
			{
				// Default to '=', just in case...
				if (empty($range_trans[$_POST['types'][$param_name]]))
					$_POST['types'][$param_name] = '=';

				// Handle special case 'age'.
				if ($param_info['type'] == 'age')
				{
					// All people that were born between $lowerlimit and $upperlimit are currently the specified age.
					$datearray = getdate(forum_time());
					$upperlimit = str_pad($datearray['year'] - $_POST[$param_name], 4, '0') . '-' . str_pad($datearray['mon'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($datearray['mday'], 2, '0', STR_PAD_LEFT);
					$lowerlimit = str_pad($datearray['year'] - $_POST[$param_name] - 1, 4, '0') . '-' . str_pad($datearray['mon'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($datearray['mday'], 2, '0', STR_PAD_LEFT);
					if (in_array($_POST['types'][$param_name], array('-', '--', '=')))
						$query_parts[] = "{$param_info['db_fields'][0]} > '" . ($_POST['types'][$param_name] == '--' ? $upperlimit : $lowerlimit) . "'";
					if (in_array($_POST['types'][$param_name], array('+', '++', '=')))
					{
						$query_parts[] = "{$param_info['db_fields'][0]} <= '" . ($_POST['types'][$param_name] == '++' ? $lowerlimit : $upperlimit) . "'";

						// Make sure that members that didn't set their birth year are not queried.
						$query_parts[] = "{$param_info['db_fields'][0]} > '0000-12-31'";
					}
				}
				else
					$query_parts[] = $param_info['db_fields'][0] . ' ' . $range_trans[$_POST['types'][$param_name]] . ' ' . $_POST[$param_name];
			}
			// Checkboxes.
			elseif ($param_info['type'] == 'checkbox')
			{
				// Each checkbox or no checkbox at all is checked -> ignore.
				if (!is_array($_POST[$param_name]) || count($_POST[$param_name]) == 0 || count($_POST[$param_name]) == count($param_info['values']))
					continue;

				$query_parts[] = "{$param_info['db_fields'][0]} IN ('" . implode("', '", $_POST[$param_name]) . "')";
			}
			else
			{
				// Replace the wildcard characters ('*' and '?') into MySQL ones.
				$_POST[$param_name] = strtolower(addslashes(strtr($_POST[$param_name], array('%' => '\%', '_' => '\_', '*' => '%', '?' => '_'))));

				$query_parts[] = '(' . implode(" LIKE '%{$_POST[$param_name]}%' OR ", $param_info['db_fields']) . " LIKE '%{$_POST[$param_name]}%')";
			}
		}

		// Set up the membergroup query part.
		$mg_query_parts = array();

		// Primary membergroups, but only if at least was was not selected.
		if (!empty($_POST['membergroups'][1]) && count($context['membergroups']) != count($_POST['membergroups'][1]))
			$mg_query_parts[] = "ID_GROUP IN (" . implode(", ", $_POST['membergroups'][1]) . ")";

		// Additional membergroups (these are only relevant if not all primary groups where selected!).
		if (!empty($_POST['membergroups'][2]) && (empty($_POST['membergroups'][1]) || count($context['membergroups']) != count($_POST['membergroups'][1])))
			foreach ($_POST['membergroups'][2] as $mg)
				$mg_query_parts[] = "FIND_IN_SET(" . (int) $mg . ", additionalGroups)";

		// Combine the one or two membergroup parts into one query part linked with an OR.
		if (!empty($mg_query_parts))
			$query_parts[] = '(' . implode(' OR ', $mg_query_parts) . ')';

		// Get all selected post count related membergroups.
		if (!empty($_POST['postgroups']) && count($_POST['postgroups']) != count($context['postgroups']))
			$query_parts[] = "ID_POST_GROUP IN (" . implode(", ", $_POST['postgroups']) . ")";

		// Construct the where part of the query.
		$where = empty($query_parts) ? '1' : implode('
			AND ', $query_parts);
	}
	// If the query information was already packed in the URL, decode it.
	elseif ($context['sub_action'] == 'query')
		$where = base64_decode($_REQUEST['params']);

	// Construct the additional URL part with the query info in it.
	$context['params_url'] = $context['sub_action'] == 'query' ? ';sa=query;params=' . base64_encode($where) : '';

	// Get the title and sub template ready..
	$context['page_title'] = $txt[9];
	$context['sub_template'] = 'view_members';

	// Determine whether to show the 'delete members' checkboxes.
	$context['can_delete_members'] = allowedTo('profile_remove_any');

	// All the columns they have to pick from...
	$context['columns'] = array(
		'ID_MEMBER' => array('label' => $txt['member_id']),
		'memberName' => array('label' => $txt[35]),
		'realName' => array('label' => $txt['display_name']),
		'emailAddress' => array('label' => $txt['email_address']),
		'memberIP' => array('label' => $txt['ip_address']),
		'lastLogin' => array('label' => $txt['viewmembers_online']),
		'posts' => array('label' => $txt[26])
	);

	// Default sort column to 'memberName' if the current one is unknown or not set.
	if (!isset($_REQUEST['sort']) || !array_key_exists($_REQUEST['sort'], $context['columns']))
		$_REQUEST['sort'] = 'memberName';

	// Provide extra information about each column - the link, whether it's selected, etc.
	foreach ($context['columns'] as $col => $dummy)
	{
		$context['columns'][$col]['href'] = $scripturl . '?action=viewmembers' . $context['params_url'] . ';sort=' . $col . ';start=0';
		if (!isset($_REQUEST['desc']) && $col == $_REQUEST['sort'])
			$context['columns'][$col]['href'] .= ';desc';

		$context['columns'][$col]['link'] = '<a href="' . $context['columns'][$col]['href'] . '">' . $context['columns'][$col]['label'] . '</a>';
		$context['columns'][$col]['selected'] = $_REQUEST['sort'] == $col;
	}

	$context['sort_by'] = $_REQUEST['sort'];
	$context['sort_direction'] = !isset($_REQUEST['desc']) ? 'down' : 'up';

	// Calculate the number of results.
	if (empty($where) or $where == '1')
		$num_members = $modSettings['memberCount'];
	else
	{
		$request = db_query("
			SELECT COUNT(ID_MEMBER)
			FROM {$db_prefix}members
			WHERE $where", __FILE__, __LINE__);
		list ($num_members) = mysql_fetch_row($request);
	}

	// Construct the page links.
	$context['page_index'] = constructPageIndex($scripturl . '?action=viewmembers' . $context['params_url'] . ';sort=' . $_REQUEST['sort'] . (isset($_REQUEST['desc']) ? ';desc' : ''), $_REQUEST['start'], $num_members, $modSettings['defaultMaxMembers']);
	$context['start'] = $_REQUEST['start'];

	$request = db_query("
		SELECT
			ID_MEMBER, memberName, realName, emailAddress, memberIP, IFNULL(lastLogin, 0) AS lastLogin, posts
		FROM {$db_prefix}members" . ($context['sub_action'] == 'query' && !empty($where) ? "
		WHERE $where" : '') . "
		ORDER BY $_REQUEST[sort]" . (!isset($_REQUEST['desc']) ? '' : ' DESC') . "
		LIMIT $_REQUEST[start], $modSettings[defaultMaxMembers]", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
	{
		// Calculate number of days since last online.
		if (empty($row['lastLogin']))
			$difference = $txt['never'];
		else
		{
			// Today or some time ago?
			$difference = jeffsdatediff($row['lastLogin']);
			if (empty($difference))
				$difference = $txt['viewmembers_today'];
			elseif ($difference == 1)
				$difference .= ' ' . $txt['viewmembers_day_ago'];
			else
				$difference .= ' ' . $txt['viewmembers_days_ago'];
		}

		$context['members'][] = array(
			'id' => $row['ID_MEMBER'],
			'username' => $row['memberName'],
			'name' => $row['realName'],
			'email' => $row['emailAddress'],
			'ip' => $row['memberIP'],
			'last_active' => $difference,
			'posts' => $row['posts'],
			'href' => $scripturl . '?action=profile;u=' . $row['ID_MEMBER'],
			'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['realName'] . '</a>'
		);
	}
	mysql_free_result($request);
}

function jeffsdatediff($old)
{
	// Get the current time as the user would see it.
	$forumTime = forum_time();

	// Calculate the seconds that have passed since midnight.
	$sinceMidnight = date('H', $forumTime) * 60 * 60 + date('i', $forumTime) * 60 + date('s', $forumTime);

	// Take the difference between the two times.
	$dis = time() - $old;

	// Before midnight?
	if ($dis < $sinceMidnight)
		return 0;
	else
		$dis -= $sinceMidnight;

	// Divide out the seconds in a day to get the number of days.
	return ceil($dis / (24 * 60 * 60));
}

// Delete a group of/single member.
function deleteMembers($users)
{
	global $db_prefix, $sourcedir, $modSettings, $ID_MEMBER;

	// If it's not an array, make it so!
	if (!is_array($users))
		$users = array($users);
	else
		$users = array_unique($users);

	// How many are they deleting?
	if (empty($users))
		return;
	elseif (count($users) == 1)
	{
		list ($user) = $users;
		$condition = '= ' . $user;

		if ($user == $ID_MEMBER)
			isAllowedTo('profile_remove_own');
		else
			isAllowedTo('profile_remove_any');
	}
	else
	{
		$condition = 'IN (' . implode(',', $users) . ')';

		// Deleting more than one?  You can't have more than once account...
		isAllowedTo('profile_remove_any');

		// Log the action while we are here.
		foreach ($users as $user)
			logAction('delete_member', array('member' => $user));
	}

	// Make these peoples' posts guest posts.
	db_query("
		UPDATE {$db_prefix}messages
		SET ID_MEMBER = 0" . (!empty($modSettings['allow_hideEmail']) ? ", posterEmail = ''" : '') . "
		WHERE ID_MEMBER $condition", __FILE__, __LINE__);

	// Delete the member.
	db_query("
		DELETE FROM {$db_prefix}members
		WHERE ID_MEMBER $condition
		LIMIT " . count($users), __FILE__, __LINE__);

	// Delete the logs...
	db_query("
		DELETE FROM {$db_prefix}log_topics
		WHERE ID_MEMBER $condition", __FILE__, __LINE__);
	db_query("
		DELETE FROM {$db_prefix}log_boards
		WHERE ID_MEMBER $condition", __FILE__, __LINE__);
	db_query("
		DELETE FROM {$db_prefix}log_mark_read
		WHERE ID_MEMBER $condition", __FILE__, __LINE__);
	db_query("
		DELETE FROM {$db_prefix}log_notify
		WHERE ID_MEMBER $condition", __FILE__, __LINE__);
	db_query("
		DELETE FROM {$db_prefix}log_online
		WHERE ID_MEMBER $condition", __FILE__, __LINE__);
	db_query("
		DELETE FROM {$db_prefix}collapsed_categories
		WHERE ID_MEMBER $condition", __FILE__, __LINE__);
	db_query("
		DELETE FROM {$db_prefix}themes
		WHERE ID_MEMBER $condition", __FILE__, __LINE__);

	// Delete personal messages.
	require_once($sourcedir . '/InstantMessage.php');
	deleteMessages(null, null, $users);

	db_query("
		UPDATE {$db_prefix}instant_messages
		SET ID_MEMBER_FROM = 0
		WHERE ID_MEMBER_FROM $condition", __FILE__, __LINE__);

	// Delete the moderator positions.
	db_query("
		DELETE FROM {$db_prefix}moderators
		WHERE ID_MEMBER $condition", __FILE__, __LINE__);

	// Make sure no member's birthday is still sticking in the calendar...
	updateStats('calendar');
	updateStats('member');
}

// Email your members...
function MailingList()
{
	global $txt, $db_prefix, $sourcedir, $context;
	global $scripturl, $modSettings, $user_info;

	isAllowedTo('send_mail');

	// Load the admin bar, select 'Email Your Members'..
	adminIndex('email_members');

	// Just came here....
	if (!isset($_REQUEST['sa']))
	{
		loadTemplate('ManageMembers');
		$context['page_title'] = $txt[6];

		$context['sub_template'] = 'email_members';

		$context['groups'] = array();

		// Get all the extra groups as well as Administrator and Global Moderator.
		$request = db_query("
			SELECT mg.ID_GROUP, mg.groupName, COUNT(mem.ID_MEMBER) AS num_members
			FROM {$db_prefix}membergroups AS mg
				LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_GROUP = mg.ID_GROUP OR FIND_IN_SET(mg.ID_GROUP, mem.additionalGroups) OR mg.ID_GROUP = mem.ID_POST_GROUP)
			GROUP BY mg.ID_GROUP
			ORDER BY mg.minPosts, IF(mg.ID_GROUP < 4, mg.ID_GROUP, 4), mg.groupName", __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
		{
			$context['groups'][$row['ID_GROUP']] = array(
				'id' => $row['ID_GROUP'],
				'name' => $row['groupName'],
				'member_count' => $row['num_members'],
			);
		}
		mysql_free_result($request);

		// Any moderators?
		$request = db_query("
			SELECT COUNT(DISTINCT ID_MEMBER) AS num_distinct_mods
			FROM {$db_prefix}moderators
			LIMIT 1", __FILE__, __LINE__);
		list ($context['groups'][3]['member_count']) = mysql_fetch_row($request);
		mysql_free_result($request);

		$context['can_send_pm'] = allowedTo('pm_send');

		return;
	}
	// Sending!
	elseif ($_REQUEST['sa'] == 'send2')
	{
		checkSession();

		require_once($sourcedir . '/Subs-Post.php');

		// Get all the receivers.
		$addressed = explode(';', $_POST['emails']);
		$cleanlist = array();
		foreach ($addressed as $curmem)
		{
			$curmem = trim($curmem);
			if ($curmem != '')
				$cleanlist[$curmem] = $curmem;
		}

		// Prepare the message for HTML.
		if (isset($_POST['send_html']) && isset($_POST['parse_html']))
			$_POST['message'] = str_replace(array("\n", '  '), array("<br />\n", '&nbsp; '), stripslashes($_POST['message']));
		elseif (!isset($_POST['send_html']))
			$_POST['message'] = stripslashes($_POST['message']);

		// Use the default time format.
		$user_info['time_format'] = $modSettings['time_format'];

		$variables = array(
			'{$board_url}',
			'{$current_time}',
			'{$latest_member.link}',
			'{$latest_member.id}',
			'{$latest_member.name}'
		);

		// Replace in all the standard things.
		$_POST['message'] = str_replace($variables,
			array(
				isset($_POST['send_html']) ? '<a href="' . $scripturl . '">' . $scripturl . '</a>' : $scripturl,
				timeformat(forum_time(), false),
				isset($_POST['send_html']) ? '<a href="' . $scripturl . '?action=profile;u=' . $modSettings['latestMember'] . '">' . $modSettings['latestRealName'] . '</a>' : $modSettings['latestRealName'],
				$modSettings['latestMember'],
				$modSettings['latestRealName']
			), $_POST['message']);
		$_POST['subject'] = str_replace($variables,
			array(
				$scripturl,
				timeformat(forum_time(), false),
				$modSettings['latestRealName'],
				$modSettings['latestMember'],
				$modSettings['latestRealName']
			), stripslashes($_POST['subject']));

		$from_member = array(
			'{$member.email}',
			'{$member.link}',
			'{$member.id}',
			'{$member.name}'
		);

		// This is here to prevent spam filters from tagging this as spam.
		if (isset($_POST['send_html']) && preg_match('~\<html~i', $_POST['message']) == 0)
		{
			if (preg_match('~\<body~i', $_POST['message']) == 0)
				$_POST['message'] = '<html><head><title>' . $_POST['subject'] . '</title></head><body>' . $_POST['message'] . '</body></html>';
			else
				$_POST['message'] = '<html>' . $_POST['message'] . '</html>';
		}

		$result = db_query("
			SELECT realName, memberName, ID_MEMBER, emailAddress
			FROM {$db_prefix}members
			WHERE emailAddress IN ('" . implode("', '", $cleanlist) . "')", __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($result))
		{
			unset($cleanlist[$row['emailAddress']]);

			$to_member = array(
				$row['emailAddress'],
				isset($_POST['send_html']) ? '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['realName'] . '</a>' : $scripturl . '?action=profile;u=' . $row['ID_MEMBER'],
				$row['ID_MEMBER'],
				$row['realName']
			);

			// Send the actual email off, replacing the member dependent variables.
			sendmail($row['emailAddress'], str_replace($from_member, $to_member, $_POST['subject']), str_replace($from_member, $to_member, $_POST['message']), null, isset($_POST['send_html']));
		}
		mysql_free_result($result);

		// Send the emails to people who weren't members....
		if (!empty($cleanlist))
			foreach ($cleanlist as $email)
			{
				$to_member = array(
					$email,
					!empty($_POST['send_html']) ? '<a href="mailto:' . $email . '">' . $email . '</a>' : $email,
					'??',
					$email
				);

				sendmail($email, str_replace($from_member, $to_member, $_POST['subject']), str_replace($from_member, $to_member, $_POST['message']), null, !empty($_POST['send_html']));
			}

		redirectexit('action=admin');
	}

	checkSession();

	$list = array();
	$do_pm = !empty($_POST['sendPM']);

	// Opt-out?
	$condition = isset($_POST['email_force']) ? '' : '
				AND mem.notifyAnnouncements = 1';

	// Did they select moderators too?
	if (!empty($_POST['who']) && in_array(3, $_POST['who']))
	{
		$request = db_query("
			SELECT DISTINCT " . ($do_pm ? 'mem.memberName' : 'mem.emailAddress') . " AS identifier
			FROM {$db_prefix}members AS mem, {$db_prefix}moderators AS mods
			WHERE mem.ID_MEMBER = mods.ID_MEMBER$condition", __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
			$list[] = $row['identifier'];
		mysql_free_result($request);

		unset($_POST['who'][3]);
	}

	// Load all the other groups.
	if (!empty($_POST['who']))
	{
		$request = db_query("
			SELECT " . ($do_pm ? 'mem.memberName' : 'mem.emailAddress') . " AS identifier
			FROM {$db_prefix}members AS mem, {$db_prefix}membergroups AS mg
			WHERE (mg.ID_GROUP = mem.ID_GROUP OR FIND_IN_SET(mg.ID_GROUP, mem.additionalGroups) OR mg.ID_GROUP = mem.ID_POST_GROUP)
				AND mg.ID_GROUP IN (" . implode(',', $_POST['who']) . ")$condition", __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
			$list[] = $row['identifier'];
		mysql_free_result($request);
	}

	// Tear out duplicates....
	$list = array_unique($list);

	// Sending as a personal message?
	if ($do_pm)
	{
		require_once($sourcedir . '/InstantMessage.php');
		require_once($sourcedir . '/Subs-Post.php');
		$_REQUEST['bcc'] = implode(',', $list);
		MessagePost();
	}
	else
	{
		loadTemplate('ManageMembers');

		$context['page_title'] = $txt[6];

		// Just send the to list to the template.
		$context['addresses'] = implode('; ', $list);
		$context['default_subject'] = $context['forum_name'] . ': ' . $txt[70];
		$context['default_message'] = $txt[72] . "\n\n" . $txt[130] . "\n\n{\$board_url}";

		$context['sub_template'] = 'email_members_compose';
	}
}

// Ban center.
function Ban()
{
	global $context, $txt;

	isAllowedTo('manage_bans');

	// Boldify "Ban Members" on the admin bar.
	adminIndex('ban_members');

	loadTemplate('ManageMembers');

	$subActions = array(
		'add' => 'BanEdit',
		'edit' => 'BanEdit',
		'list' => 'BanList',
		'log' => 'BanLog',
		'save' => 'BanSave',
	);

	// Default the sub-action to 'view ban list'.
	$_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'list';

	$context['page_title'] = &$txt['ban_title'];
	$context['sub_action'] = $_REQUEST['sa'];

	// Call the right function for this sub-acton.
	$subActions[$_REQUEST['sa']]();
}

// Ban list
function BanList()
{
	global $txt, $db_prefix, $context, $ban_request, $scripturl;

	// Delete expired bans, the're useless!
	db_query("
		DELETE FROM {$db_prefix}banned
		WHERE expire_time < " . time(), __FILE__, __LINE__);

	// User pressed the 'remove selection button'.
	if (!empty($_POST['removeBans']) && !empty($_POST['remove']) && is_array($_POST['remove']))
	{
		checkSession();

		// Make sure every entry is a proper integer.
		foreach ($_POST['remove'] as $index => $ban_id)
			$_POST['remove'][(int) $index] = (int) $ban_id;
		$_POST['remove'] = array_unique($_POST['remove']);

		db_query("
			DELETE FROM {$db_prefix}banned
			WHERE ID_BAN IN (" . implode(', ', $_POST['remove']) . ')
			LIMIT ' . count($_POST['remove']), __FILE__, __LINE__);
	}

	$sort_methods = array(
		'type' => array(
			'down' => 'ban.ban_type ASC, ban.hostname ASC, ban.email_address ASC, mem.realName ASC, ban.ip_low1 ASC, ban.ip_low2 ASC, ban.ip_low3 ASC, ban.ip_low4 ASC',
			'up' => 'ban.ban_type DESC, ban.hostname DESC, ban.email_address DESC, mem.realName DESC, ban.ip_low1 DESC, ban.ip_low2 DESC, ban.ip_low3 DESC, ban.ip_low4 DESC'
		),
		'reason' => array(
			'down' => 'LENGTH(ban.reason) > 0 DESC, ban.reason ASC',
			'up' => 'LENGTH(ban.reason) > 0 ASC, ban.reason DESC'
		),
		'notes' => array(
			'down' => 'LENGTH(ban.notes) > 0 DESC, ban.notes ASC',
			'up' => 'LENGTH(ban.notes) > 0 ASC, ban.notes DESC'
		),
		'restriction' => array(
			'down' => 'ban.restriction_type ASC',
			'up' => 'ban.restriction_type DESC'
		),
		'expires' => array(
			'down' => 'ISNULL(ban.expire_time) DESC, ban.expire_time DESC',
			'up' => 'ISNULL(ban.expire_time) ASC, ban.expire_time ASC'
		)
	);

	$context['columns'] = array(
		'type' => array(
			'width' => '10%',
			'label' => &$txt['ban_type'],
			'sortable' => true
		),
		'reason' => array(
			'width' => '25%',
			'label' => &$txt['ban_reason'],
			'sortable' => true
		),
		'notes' => array(
			'width' => '25%',
			'label' => &$txt['ban_notes'],
			'sortable' => true
		),
		'restriction' => array(
			'label' => &$txt['ban_restriction'],
			'sortable' => true
		),
		'expires' => array(
			'label' => &$txt['ban_expires'],
			'sortable' => true
		),
		'actions' => array(
			'label' => &$txt['ban_actions'],
			'sortable' => false
		)
	);

	if (!isset($_REQUEST['sort']) || !array_key_exists($_REQUEST['sort'], $sort_methods))
		$_REQUEST['sort'] = 'type';

	foreach ($context['columns'] as $col => $dummy)
	{
		$context['columns'][$col]['selected'] = $col == $_REQUEST['sort'];
		$context['columns'][$col]['href'] = $scripturl . '?action=ban;sort=' . $col;

		if (!isset($_REQUEST['desc']) && $col == $_REQUEST['sort'])
			$context['columns'][$col]['href'] .= ';desc';

		$context['columns'][$col]['link'] = '<a href="' . $context['columns'][$col]['href'] . '">' . $context['columns'][$col]['label'] . '</a>';
	}

	$context['sort_by'] = $_REQUEST['sort'];
	$context['sort_direction'] = !isset($_REQUEST['desc']) ? 'down' : 'up';

	// Get the total amount of entries.
	$request = db_query("
		SELECT COUNT(ID_BAN)
		FROM {$db_prefix}banned", __FILE__, __LINE__);
	list ($totalBans) = mysql_fetch_row($request);
	mysql_free_result($request);

	// Create the page index.
	$context['start'] = $_REQUEST['start'];
	$context['page_index'] = constructPageIndex($scripturl . '?action=ban;sort=' . $_REQUEST['sort'] . (isset($_REQUEST['desc']) ? ';desc' : ''), $context['start'], $totalBans, 20);

	// Get the banned values.
	$ban_request = db_query("
		SELECT
			ban.ID_BAN, ban.ban_type,
			ban.ip_low1, ban.ip_high1, ban.ip_low2, ban.ip_high2, ban.ip_low3, ban.ip_high3, ban.ip_low4, ban.ip_high4,
			ban.hostname, ban.email_address, ban.ID_MEMBER, ban.ban_time, ban.expire_time, ban.restriction_type,
			ban.reason, ban.notes,
			IFNULL(mem.memberName, ban.ID_MEMBER) AS memberName, IFNULL(mem.realName, ban.ID_MEMBER) AS realName
		FROM {$db_prefix}banned AS ban
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER=ban.ID_MEMBER)
		ORDER BY " . $sort_methods[$_REQUEST['sort']][$context['sort_direction']] . "
		LIMIT $context[start], 20", __FILE__, __LINE__);

	// Set the correct sub-template.
	$context['sub_template'] = 'ban_list';

	// Set the value of the callback function.
	$context['get_ban'] = 'getBanEntry';
}

// Call-back function for the template to retrieve a row of ban data.
function getBanEntry($reset = false)
{
	global $scripturl, $ban_request, $txt, $context;

	if ($ban_request == false)
		return false;

	if (!($row = mysql_fetch_assoc($ban_request)))
		return false;

	$output = array(
		'id' => $row['ID_BAN'],
		'type' => $row['ban_type'],
		'reason' => $row['reason'],
		'notes' => $row['notes'],
		'restriction' => $txt['ban_' . $row['restriction_type']],
		'expires' => $row['expire_time'] === null ? $txt['never'] : ceil(($row['expire_time'] - time()) / (60 * 60 * 24)) . '&nbsp;' . $txt['ban_days'],
	);

	if ($row['ban_type'] == 'ip_ban')
	{
		$low_ip = array($row['ip_low1'], $row['ip_low2'], $row['ip_low3'], $row['ip_low4']);
		$high_ip = array($row['ip_high1'], $row['ip_high2'], $row['ip_high3'], $row['ip_high4']);
		$output['ip'] = range2ip($low_ip, $high_ip);
	}
	elseif ($row['ban_type'] == 'hostname_ban')
		$output['hostname'] = str_replace('%', '*', $row['hostname']);
	elseif ($row['ban_type'] == 'email_ban')
		$output['email'] = str_replace('%', '*', $row['email_address']);
	elseif ($row['ban_type'] == 'user_ban')
		$output['user'] = array(
			'name' => $row['memberName'],
			'href' => $scripturl . '?action=profile;u=' . $row['ID_MEMBER'],
			'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['realName'] . '</a>'
		);

	return $output;
}

function BanEdit()
{
	global $txt, $db_prefix, $modSettings, $context, $ban_request, $scripturl;

	// If we're editing an existing ban, get it from the database.
	if (!empty($_REQUEST['bid']))
	{
		$request = db_query("
			SELECT
				ban.ID_BAN, ban.ban_type,
				ban.ip_low1, ban.ip_high1, ban.ip_low2, ban.ip_high2, ban.ip_low3, ban.ip_high3, ban.ip_low4, ban.ip_high4,
				ban.hostname, ban.email_address, ban.ID_MEMBER, ban.ban_time, ban.expire_time, ban.restriction_type,
				ban.reason, ban.notes,
				IFNULL(mem.memberName, ban.ID_MEMBER) AS memberName, IFNULL(mem.realName, ban.ID_MEMBER) AS realName
			FROM {$db_prefix}banned AS ban
				LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER=ban.ID_MEMBER)
			WHERE ID_BAN = " . (int) $_REQUEST['bid'] . "
			LIMIT 1", __FILE__, __LINE__);
		if (mysql_num_rows($request) == 0)
			fatal_lang_error('ban_not_found');
		$row = mysql_fetch_assoc($request);
		mysql_free_result($request);

		$context['ban'] = array(
			'id' => $row['ID_BAN'],
			'ip' => array(
				'value' => $row['ban_type'] == 'ip_ban' ? range2ip(array($row['ip_low1'], $row['ip_low2'], $row['ip_low3'], $row['ip_low4']), array($row['ip_high1'], $row['ip_high2'], $row['ip_high3'], $row['ip_high4'])) : '',
				'selected' => $row['ban_type'] == 'ip_ban'
			),
			'hostname' => array(
				'value' => $row['ban_type'] == 'hostname_ban' ? str_replace('%', '*', $row['hostname']) : '',
				'selected' => $row['ban_type'] == 'hostname_ban'
			),
			'email' => array(
				'value' => $row['ban_type'] == 'email_ban' ? str_replace('%', '*', $row['email_address']) : '',
				'selected' => $row['ban_type'] == 'email_ban'
			),
			'banneduser' => array(
				'value' => $row['ban_type'] == 'user_ban' ? $row['memberName'] : '',
				'selected' => $row['ban_type'] == 'user_ban'
			),
			'expiration' => array(
				'never' => $row['expire_time'] === null,
				'days' => $row['expire_time'] > time() ? floor($row['expire_time'] / 24 * 60 * 60) : 0
			),
			'reason' => $row['reason'],
			'notes' => $row['notes'],
			'restriction' => $row['restriction_type'],
			'ban_days' => $row['expire_time'] === null ? 0 : ceil(($row['expire_time'] - time()) / (60 * 60 * 24)),
		);
	}
	// Not an existing one, then it's probably a new one.
	else
	{
		$context['ban'] = array(
			'id' => 0,
			'ip' => array(
				'value' => '',
				'selected' => true
			),
			'hostname' => array(
				'value' => '',
				'selected' => false
			),
			'email' => array(
				'value' => '',
				'selected' => false
			),
			'banneduser' => array(
				'value' => '',
				'selected' => false
			),
			'expiration' => array(
				'never' => true,
				'days' => 1
			),
			'reason' => '',
			'notes' => '',
			'restriction' => 'full_ban',
			'ban_days' => 1
		);

		// Overwrite some of the default form values if a user ID was given.
		if (!empty($_REQUEST['u']))
		{
			$request = db_query("
				SELECT memberName, memberIP, emailAddress
				FROM {$db_prefix}members
				WHERE ID_MEMBER = " . (int) $_REQUEST['u'] . "
				LIMIT 1", __FILE__, __LINE__);
			if (mysql_num_rows($request) > 0)
			{
				list ($context['ban']['banneduser']['value'], $context['ban']['ip']['value'], $context['ban']['email']['value']) = mysql_fetch_row($request);
				if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $context['ban']['ip']['value']) && empty($modSettings['disableHostnameLookup']))
					$context['ban']['hostname']['value'] = @gethostbyaddr($context['ban']['ip']['value']);
			}
		}
	}

	// Edit or add, they use the same template.
	$context['sub_template'] = 'ban_edit';
}

function BanSave()
{
	global $db_prefix;

	// Make sure they CAN update the banning.
	checkSession();

	$newBan = empty($_POST['bid']);

	// Array for holding moderation log data.
	$modlogInfo = array();

	// Check ban type data and start the query to adjust/add the ban.
	if ($_POST['bantype'] == 'ip_ban')
	{
		$ip_parts = ip2range($_POST['ip']);
		if (count($ip_parts) != 4)
			fatal_lang_error('invalid_ip');

		if ($newBan)
		{
			$insert1 = 'ip_low1, ip_high1, ip_low2, ip_high2, ip_low3, ip_high3, ip_low4, ip_high4';
			$insert2 = $ip_parts[0]['low'] . ', ' . $ip_parts[0]['high'] . ',
				' . $ip_parts[1]['low'] . ', ' . $ip_parts[1]['high'] . ',
				' . $ip_parts[2]['low'] . ', ' . $ip_parts[2]['high'] . ',
				' . $ip_parts[3]['low'] . ', ' . $ip_parts[3]['high'];
		}
		else
			$update = '
				ip_low1 = ' . $ip_parts[0]['low'] . ', ip_high1 = ' . $ip_parts[0]['high'] . ',
				ip_low2 = ' . $ip_parts[1]['low'] . ', ip_high2 = ' . $ip_parts[1]['high'] . ',
				ip_low3 = ' . $ip_parts[2]['low'] . ', ip_high3 = ' . $ip_parts[2]['high'] . ',
				ip_low4 = ' . $ip_parts[3]['low'] . ', ip_high4 = ' . $ip_parts[3]['high'] . ',
				hostname = NULL, email_address = NULL, ID_MEMBER = NULL';
		$modlogInfo['ip_range'] = $_POST['ip'];
	}
	elseif ($_POST['bantype'] == 'hostname_ban')
	{
		if (preg_match("/[^\w.\-*]/", $_POST['hostname']))
			fatal_lang_error('invalid_hostname');

		// Replace the * wildcard by a MySQL compatible wildcard %.
		$_POST['hostname'] = str_replace('*', '%', $_POST['hostname']);
		if ($newBan)
		{
			$insert1 = 'hostname';
			$insert2 = "'$_POST[hostname]'";
		}
		else
			$update = "
				ip_low1 = NULL, ip_high1 = NULL,
				ip_low2 = NULL, ip_high2 = NULL,
				ip_low3 = NULL, ip_high3 = NULL,
				ip_low4 = NULL, ip_high4 = NULL,
				hostname = '$_POST[hostname]', email_address = NULL, ID_MEMBER = NULL";
		$modlogInfo['hostname'] = $_POST['hostname'];
	}
	elseif ($_POST['bantype'] == 'email_ban')
	{
		if (preg_match("/[^\w.\-*@]/", $_POST['email']))
			fatal_lang_error('invalid_email');
		$_POST['email'] = strtolower(str_replace('*', '%', $_POST['email']));

		// Check the user is not banning an admin.
		$request = db_query("
			SELECT ID_MEMBER
			FROM {$db_prefix}members
			WHERE (ID_GROUP = 1 OR FIND_IN_SET(1, additionalGroups))
				AND emailAddress = '$_POST[email]'
			LIMIT 1", __FILE__, __LINE__);
		if (mysql_num_rows($request) != 0)
			fatal_lang_error('no_ban_admin');
		mysql_free_result($request);

		if ($newBan)
		{
			$insert1 = 'email_address';
			$insert2 = "'$_POST[email]'";
		}
		else
			$update = "
				ip_low1 = NULL, ip_high1 = NULL,
				ip_low2 = NULL, ip_high2 = NULL,
				ip_low3 = NULL, ip_high3 = NULL,
				ip_low4 = NULL, ip_high4 = NULL,
				hostname = NULL, email_address = '$_POST[email]', ID_MEMBER = NULL";
		$modlogInfo['email'] = $_POST['email'];
	}
	elseif ($_POST['bantype'] == 'user_ban')
	{
		$request = db_query("
			SELECT ID_MEMBER, (ID_GROUP = 1 OR FIND_IN_SET(1, additionalGroups))
			FROM {$db_prefix}members
			WHERE memberName = '$_POST[banneduser]'
			LIMIT 1", __FILE__, __LINE__);
		if (mysql_num_rows($request) == 0)
			fatal_lang_error('invalid_username');
		list ($memberid, $isAdmin) = mysql_fetch_row($request);
		mysql_free_result($request);

		if ($isAdmin)
			fatal_lang_error('no_ban_admin');

		if ($newBan)
		{
			$insert1 = 'ID_MEMBER';
			$insert2 = $memberid;
		}
		else
			$update = "
				ip_low1 = NULL, ip_high1 = NULL,
				ip_low2 = NULL, ip_high2 = NULL,
				ip_low3 = NULL, ip_high3 = NULL,
				ip_low4 = NULL, ip_high4 = NULL,
				hostname = NULL, email_address = NULL, ID_MEMBER = $memberid";
		$modlogInfo['member'] = $memberid;
	}
	else
		fatal_lang_error('no_bantype_selected');

	$_POST['reason'] = htmlspecialchars($_POST['reason'], ENT_QUOTES);
	$_POST['notes'] = htmlspecialchars($_POST['notes'], ENT_QUOTES);
	$_POST['notes'] = str_replace(array("\r", "\n", '  '), array('', '<br />', '&nbsp; '), $_POST['notes']);

	if (!in_array($_POST['restriction'], array('full_ban', 'cannot_post', 'cannot_register')))
		fatal_lang_error('ban_unknown_restriction_type');

	if (empty($_POST['expiration']) || ($_POST['expiration'] != 'never' && (int) $_POST['expire_date'] == 0))
		fatal_lang_error('invalid_expiration_date');

	if ($newBan)
		$result = db_query("
			INSERT INTO {$db_prefix}banned
				($insert1, ban_type, reason, notes, restriction_type, ban_time, expire_time)
			VALUES ($insert2, '$_POST[bantype]', '$_POST[reason]', '$_POST[notes]', '$_POST[restriction]', " . time() . ", " . ($_POST['expiration'] == 'never' ? 'NULL' : time() + 24 * 60 * 60 * (int) $_POST['expire_date']) . ")", __FILE__, __LINE__);
	else
		$result = db_query("
			UPDATE {$db_prefix}banned
			SET $update, ban_type = '$_POST[bantype]', reason = '$_POST[reason]', notes = '$_POST[notes]', restriction_type = '$_POST[restriction]', expire_time = " . ($_POST['expiration'] == 'never' ? 'NULL' : ($_POST['expire_date'] != $_POST['old_expire'] ? time() + 24 * 60 * 60 * (int) $_POST['expire_date'] : 'expire_time')) . "
			WHERE ID_BAN = $_POST[bid]
			LIMIT 1", __FILE__, __LINE__);

	// Log the ban into the moderation log.
	logAction('ban', $modlogInfo + array(
		'new' => $newBan,
		'type' => $_POST['bantype'],
		'reason' => $_POST['reason']
	));

	// Register the last modified date.
	updateSettings(array('banLastUpdated' => time()));

	redirectexit('action=ban');
}

function BanLog()
{
	global $db_prefix, $scripturl, $context, $db_connection;

	$sort_columns = array(
		'name' => 'mem.realName',
		'ip' => 'lb.ip',
		'email' => 'lb.email',
		'date' => 'lb.logTime',
	);

	// The number of entries to show per page of the ban log.
	$entries_per_page = 30;

	// Delete one or more entries.
	if (!empty($_POST['removeAll']) || (!empty($_POST['removeSelected']) && !empty($_POST['remove'])))
	{
		checkSession();

		// 'Delete all entries' button was pressed.
		if (!empty($_POST['removeAll']))
		{
			mysql_query("
				TRUNCATE {$db_prefix}log_banned", $db_connection);

			db_query("
				DELETE FROM {$db_prefix}log_banned", __FILE__, __LINE__);
		}

		// 'Delte selection' button was pressed.
		else
		{
			// Make sure every entry is integer.
			foreach ($_POST['remove'] as $index => $log_id)
				$_POST['remove'][$index] = (int) $log_id;

			db_query("
				DELETE FROM {$db_prefix}log_banned
				WHERE ID_BAN_LOG IN (" . implode(', ', $_POST['remove']) . ')', __FILE__, __LINE__);
		}
	}

	// Count the total number of log entries.
	$request = db_query("
		SELECT COUNT(ID_BAN_LOG)
		FROM {$db_prefix}log_banned", __FILE__, __LINE__);
	list ($num_ban_log_entries) = mysql_fetch_row($request);
	mysql_free_result($request);

	// Set start if not already set.
	$_REQUEST['start'] = empty($_REQUEST['start']) || $_REQUEST['start'] < 0 ? 0 : (int) $_REQUEST['start'];

	// Default to newest entries first.
	if (empty($_REQUEST['sort']) || !isset($sort_columns[$_REQUEST['sort']]))
	{
		$_REQUEST['sort'] = 'date';
		$_REQUEST['desc'] = true;
	}

	$context['sort_direction'] = isset($_REQUEST['desc']) ? 'down' : 'up';
	$context['sort'] = $_REQUEST['sort'];
	$context['page_index'] = constructPageIndex($scripturl . '?action=ban;sa=log;sort=' . $context['sort'] . ($context['sort_direction'] == 'down' ? ';desc' : ''), $_REQUEST['start'], $num_ban_log_entries, $entries_per_page);
	$context['start'] = $_REQUEST['start'];

	$request = db_query("
		SELECT lb.ID_BAN_LOG, lb.ID_MEMBER, IFNULL(lb.ip, '-') AS ip, IFNULL(lb.email, '-') AS email, lb.logTime, IFNULL(mem.realName, '') AS realName
		FROM {$db_prefix}log_banned AS lb
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = lb.ID_MEMBER)
		ORDER BY " . $sort_columns[$context['sort']] . (isset($_REQUEST['desc']) ? ' DESC' : '') . "
		LIMIT $_REQUEST[start], $entries_per_page", __FILE__, __LINE__);

	$context['log_entries'] = array();

	while ($row = mysql_fetch_assoc($request))
		$context['log_entries'][] = array(
			'id' => $row['ID_BAN_LOG'],
			'member' => array(
				'id' => $row['ID_MEMBER'],
				'name' => $row['realName'],
				'href' => $scripturl . '?action=profile;u=' . $row['ID_MEMBER'],
				'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['realName'] . '</a>',
			),
			'ip' => $row['ip'],
			'email' => $row['email'],
			'date' => timeformat($row['logTime']),
		);

	$context['sub_template'] = 'ban_log';
}

function range2ip($low, $high)
{
	if (count($low) != 4 || count($high) != 4)
		return '';

	$ip = array();
	for ($i = 0; $i < 4; $i++)
	{
		if ($low[$i] == $high[$i])
			$ip[$i] = $low[$i];
		elseif ($low[$i] == '0' && $high[$i] == '255')
			$ip[$i] = '*';
		else
			$ip[$i] = $low[$i] . '-' . $high[$i];
	}

	// Pretending is fun... the IP can't be this, so use it for 'unknown'.
	if ($ip == array(255, 255, 255, 255))
		return 'unknown';

	return implode('.', $ip);
}

// Convert a single IP to a ranged IP.
function ip2range($fullip)
{
	// Pretend that 'unknown' is 255.255.255.255. (since that can't be an IP anyway.)
	if ($fullip == 'unknown')
		$fullip = '255.255.255.255';

	$ip_parts = explode('.', $fullip);
	$ip_array = array();

	if (count($ip_parts) != 4)
		return array();

	for ($i = 0; $i < 4; $i++)
	{
		if ($ip_parts[$i] == '*')
			$ip_array[$i] = array('low' => '0', 'high' => '255');
		elseif (preg_match('/^(\d{1,3})\-(\d{1,3})$/', $ip_parts[$i], $range))
			$ip_array[$i] = array('low' => $range[1], 'high' => $range[2]);
		elseif (is_numeric($ip_parts[$i]))
			$ip_array[$i] = array('low' => $ip_parts[$i], 'high' => $ip_parts[$i]);
	}

	return $ip_array;
}

// Set reserved names/words....
function SetReserve()
{
	global $txt, $db_prefix, $context, $modSettings;

	isAllowedTo('moderate_forum');

	// Select it on the admin bar.
	adminIndex('edit_reserved_names');

	loadTemplate('ManageMembers');

	// Get the reserved word options and words.
	$context['reserved_words'] = explode("\n", $modSettings['reserveNames']);
	$context['reserved_word_options'] = array();
	$context['reserved_word_options']['match_word'] = $modSettings['reserveWord'] == '1';
	$context['reserved_word_options']['match_case'] = $modSettings['reserveCase'] == '1';
	$context['reserved_word_options']['match_user'] = $modSettings['reserveUser'] == '1';
	$context['reserved_word_options']['match_name'] = $modSettings['reserveName'] == '1';

	// Ready the template......
	$context['sub_template'] = 'edit_reserved_words';
	$context['page_title'] = $txt[341];
}

function SetReserve2()
{
	global $db_prefix;

	// Only if you have proper permissions!
	isAllowedTo('moderate_forum');
	checkSession();

	// Set all the options....
	updateSettings(array(
		'reserveWord' => (isset($_POST['matchword']) ? '1' : '0'),
		'reserveCase' => (isset($_POST['matchcase']) ? '1' : '0'),
		'reserveUser' => (isset($_POST['matchuser']) ? '1' : '0'),
		'reserveName' => (isset($_POST['matchname']) ? '1' : '0'),
		'reserveNames' => str_replace("\r", '', $_POST['reserved'])
	));

	redirectexit('action=admin');
}

function trackUser($memID)
{
	global $scripturl, $txt, $db_prefix;
	global $user_profile, $context;

	// Verify if the user has sufficient permissions.
	isAllowedTo('moderate_forum');

	loadTemplate('ManageMembers');

	$context['page_title'] = $txt['trackUser'] . ' - ' . $user_profile[$memID]['realName'];

	$context['last_ip'] = $user_profile[$memID]['memberIP'];
	$context['member']['name'] = $user_profile[$memID]['realName'];

	$ips = array();

	// Get all IP addresses this user has used for his messages.
	$request = db_query("
		SELECT posterIP
		FROM {$db_prefix}messages
		WHERE ID_MEMBER = $memID
		GROUP BY posterIP", __FILE__, __LINE__);
	$context['ips'] = array();
	while ($row = mysql_fetch_assoc($request))
	{
		$context['ips'][] = '<a href="' . $scripturl . '?action=trackip;searchip=' . $row['posterIP'] . '">' . $row['posterIP'] . '</a>';
		$ips[] = $row['posterIP'];
	}
	mysql_free_result($request);

	// Now also get the IP addresses from the error messages.
	$request = db_query("
		SELECT COUNT(ID_MEMBER) AS errorCount, IP
		FROM {$db_prefix}log_errors
		WHERE ID_MEMBER = $memID
		GROUP BY IP", __FILE__, __LINE__);
	$context['error_ips'] = array();
	$totalErrors = 0;
	while ($row = mysql_fetch_assoc($request))
	{
		$context['error_ips'][] = '<a href="' . $scripturl . '?action=trackip;searchip=' . $row['IP'] . '">' . $row['IP'] . '</a>';
		$ips[] = $row['IP'];
		$totalErrors += $row['errorCount'];
	}
	mysql_free_result($request);

	// Create the page indexes.
	$context['start'] = $_REQUEST['start'];
	$context['page_index'] = constructPageIndex($scripturl . '?action=profile;u=' . $memID . ';sa=trackUser', $_REQUEST['start'], $totalErrors, 20);

	// Get a list of error messages from this ip (range).
	$request = db_query("
		SELECT
			le.logTime, le.IP, le.url, le.message, IFNULL(mem.ID_MEMBER, 0) AS ID_MEMBER,
			IFNULL(mem.realName, '$txt[28]') AS display_name, mem.memberName
		FROM {$db_prefix}log_errors AS le
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = le.ID_MEMBER)
		WHERE le.ID_MEMBER = $memID
		ORDER BY le.ID_ERROR DESC
		LIMIT $context[start], 20", __FILE__, __LINE__);
	$context['error_messages'] = array();
	while ($row = mysql_fetch_assoc($request))
		$context['error_messages'][] = array(
			'ip' => $row['IP'],
			'message' => $row['message'],
			'url' => $row['url'],
			'time' => timeformat($row['logTime']),
			'timestamp' => $row['logTime']
		);
	mysql_free_result($request);

	// Find other users that might use the same IP.
	$ips = array_unique($ips);
	$context['members_in_range'] = array();
	if (!empty($ips))
	{
		$request = db_query("
			SELECT ID_MEMBER, realName
			FROM {$db_prefix}members
			WHERE ID_MEMBER != $memID
				AND memberIP IN ('" . implode("', '", $ips) . "')", __FILE__, __LINE__);
		if (mysql_num_rows($request) > 0)
			while ($row = mysql_fetch_assoc($request))
				$context['members_in_range'][$row['ID_MEMBER']] = '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['realName'] . '</a>';

		$request = db_query("
			SELECT mem.ID_MEMBER, mem.realName
			FROM {$db_prefix}messages AS m, {$db_prefix}members AS mem
			WHERE mem.ID_MEMBER = m.ID_MEMBER
				AND mem.ID_MEMBER != $memID
				AND m.posterIP IN ('" . implode("', '", $ips) . "')", __FILE__, __LINE__);
		if (mysql_num_rows($request) > 0)
			while ($row = mysql_fetch_assoc($request))
				$context['members_in_range'][$row['ID_MEMBER']] = '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['realName'] . '</a>';
	}
}

function trackIP($memID = 0)
{
	global $user_profile, $scripturl, $txt;
	global $db_prefix, $context;

	// Can the user do this?
	isAllowedTo('moderate_forum');

	loadTemplate('ManageMembers');

	if ($memID == 0)
	{
		$searchip = $_REQUEST['searchip'];
		loadLanguage('Profile');
		$context['sub_template'] = 'trackIP';
		$context['page_title'] = $txt[79];
	}
	else
		$searchip = $user_profile[$memID]['memberIP'];

	$context['ip'] = $searchip;

	if (!preg_match('/^\d{1,3}\.(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)$/', $searchip))
		fatal_error($txt['invalid_ip'], false);

	$dbip = str_replace('*', '%', $searchip);

	$context['page_title'] = $txt['trackIP'] . ' - ' . $searchip;

	// Get some totals for pagination.
	$request = db_query("
		SELECT COUNT(ID_MSG)
		FROM {$db_prefix}messages
		WHERE posterIP LIKE '$dbip'", __FILE__, __LINE__);
	list ($totalMessages) = mysql_fetch_row($request);
	mysql_free_result($request);
	$request = db_query("
		SELECT COUNT(ID_MEMBER)
		FROM {$db_prefix}log_errors
		WHERE IP LIKE '$dbip'", __FILE__, __LINE__);
	list ($totalErrors) = mysql_fetch_row($request);
	mysql_free_result($request);

	$context['message_start'] = isset($_GET['mes']) ? $_REQUEST['start'] : 0;
	$context['error_start'] = isset($_GET['err']) ? $_REQUEST['start'] : 0;
	$context['message_page_index'] = constructPageIndex($scripturl . '?action=profile;' . ($memID == 0 ? 'searchip=' . $context['ip'] : 'u=' . $memID) . ';sa=trackIP;mes', $context['message_start'], $totalMessages, 20);
	$context['error_page_index'] = constructPageIndex($scripturl . '?action=profile;' . ($memID == 0 ? 'searchip=' . $context['ip'] : 'u=' . $memID) . ';sa=trackIP;err', $context['error_start'], $totalErrors, 20);

	$request = db_query("
		SELECT ID_MEMBER, IFNULL(realName, memberName) AS display_name, memberIP
		FROM {$db_prefix}members
		WHERE memberIP LIKE '$dbip'", __FILE__, __LINE__);
	$context['ips'] = array();
	while ($row = mysql_fetch_assoc($request))
		$context['ips'][$row['memberIP']][] = '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['display_name'] . '</a>';
	mysql_free_result($request);

	ksort($context['ips']);

	$request = db_query("
		SELECT
			m.ID_MSG, m.posterIP, IFNULL(mem.realName, m.posterName) AS display_name, mem.ID_MEMBER,
			m.subject, m.posterTime, m.ID_TOPIC, m.ID_BOARD
		FROM {$db_prefix}messages AS m
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = m.ID_MEMBER)
		WHERE m.posterIP LIKE '$dbip'
		ORDER BY m.posterIP
		LIMIT $context[message_start], 20", __FILE__, __LINE__);
	$context['messages'] = array();
	while ($row = mysql_fetch_assoc($request))
		$context['messages'][] = array(
			'ip' => $row['posterIP'],
			'member' => array(
				'id' => $row['ID_MEMBER'],
				'name' => $row['display_name'],
				'href' => $scripturl . '?action=profile;u=' . $row['ID_MEMBER'],
				'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['display_name'] . '</a>'
			),
			'board' => array(
				'id' => $row['ID_BOARD'],
				'href' => $scripturl . '?board=' . $row['ID_BOARD']
			),
			'topic' => $row['ID_TOPIC'],
			'id' => $row['ID_MSG'],
			'subject' => $row['subject'],
			'time' => timeformat($row['posterTime']),
			'timestamp' => $row['posterTime']
		);
	mysql_free_result($request);

	$request = db_query("
		SELECT
			le.logTime, le.IP, le.url, le.message, IFNULL(mem.ID_MEMBER, 0) AS ID_MEMBER,
			IFNULL(mem.realName, '$txt[28]') AS display_name, mem.memberName
		FROM {$db_prefix}log_errors AS le
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = le.ID_MEMBER)
		WHERE le.IP LIKE '$dbip'
		ORDER BY le.IP
		LIMIT $context[error_start], 20", __FILE__, __LINE__);
	$context['error_messages'] = array();
	while ($row = mysql_fetch_assoc($request))
		$context['error_messages'][] = array(
			'ip' => $row['IP'],
			'member' => array(
				'id' => $row['ID_MEMBER'],
				'name' => $row['display_name'],
				'href' => $row['ID_MEMBER'] > 0 ? $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] : '',
				'link' => $row['ID_MEMBER'] > 0 ? '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['display_name'] . '</a>' : $row['display_name']
			),
			'message' => $row['message'],
			'url' => $row['url'],
			'error_time' => timeformat($row['logTime'])
		);
	mysql_free_result($request);

	$context['single_ip'] = strpos($searchip, '*') === false;
	if ($context['single_ip'])
	{
		// In the future, AfriNIC will be added to this list.
		$context['whois_servers'] = array(
			'apnic' => array(
				'name' => &$txt['whois_apnic'],
				'url' => 'http://www.apnic.net/apnic-bin/whois2.pl?searchtext=' . $searchip
			),
			'arin' => array(
				'name' => &$txt['whois_arin'],
				'url' => 'http://ws.arin.net/cgi-bin/whois.pl?queryinput=' . $searchip
			),
			'lacnic' => array(
				'name' => &$txt['whois_lacnic'],
				'url' => 'http://lacnic.net/cgi-bin/lacnic/whois?lg=EN&qr=' . $searchip
			),
			'ripe' => array(
				'name' => &$txt['whois_ripe'],
				'url' => 'http://www.ripe.net/perl/whois?searchtext=' . $searchip
			),
		);
	}
}

function showPermissions($memID)
{
	global $scripturl, $txt, $db_prefix, $board;
	global $user_profile, $context, $user_info;

	// Verify if the user has sufficient permissions.
	isAllowedTo('manage_permissions');

	loadTemplate('ManageMembers');
	loadLanguage('ManagePermissions');
	loadLanguage('Admin');

	$context['member']['id'] = $memID;
	$context['member']['name'] = $user_profile[$memID]['realName'];

	$context['page_title'] = $txt['showPermissions'];
	$board = empty($board) ? 0 : (int) $board;
	$context['board'] = $board;

	// Load a list of boards for the jump box (but only those that have separate local permissions).
	$result = db_query("
		SELECT b.ID_BOARD, b.name
		FROM {$db_prefix}boards AS b
		WHERE $user_info[query_see_board]
			AND use_local_permissions = 1", __FILE__, __LINE__);
	$context['boards'] = array();
	while ($row = mysql_fetch_assoc($result))
		$context['boards'][$row['ID_BOARD']] = array(
			'id' => $row['ID_BOARD'],
			'name' => $row['name'],
			'selected' => $board == $row['ID_BOARD']
		);
	mysql_free_result($result);

	// Determine which groups this user is in.
	if (empty($user_profile[$memID]['additionalGroups']))
		$curGroups = array();
	else
		$curGroups = explode(',', $user_profile[$memID]['additionalGroups']);
	$curGroups[] = $user_profile[$memID]['ID_GROUP'];
	$curGroups[] = $user_profile[$memID]['ID_POST_GROUP'];

	$context['member']['permissions'] = array(
		'general' => array(),
		'board' => array()
	);

	// If you're an admin we know you can do everything, we might as well leave.
	$context['member']['has_all_permissions'] = in_array(1, $curGroups);
	if ($context['member']['has_all_permissions'])
		return;

	$denied = array();

	// Get all general permissions.
	$result = db_query("
		SELECT p.permission, p.addDeny, mg.groupName, p.ID_GROUP
		FROM {$db_prefix}permissions AS p
			LEFT JOIN {$db_prefix}membergroups AS mg ON (mg.ID_GROUP = p.ID_GROUP)
		WHERE p.ID_GROUP IN (" . implode(', ', $curGroups) . ")
		ORDER BY p.permission, mg.minPosts, IF(mg.ID_GROUP < 4, mg.ID_GROUP, 4), mg.groupName", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($result))
	{
		// We don't know about this permission, it doesn't exist :P.
		if (!isset($txt['permissionname_' . $row['permission']]))
			continue;

		if (empty($row['addDeny']))
			$denied[] = $row['permission'];

		// Permissions that end with _own or _any consist of two parts.
		if (in_array(substr($row['permission'], -4), array('_own', '_any')) && isset($txt['permissionname_' . substr($row['permission'], 0, -4)]))
			$name = $txt['permissionname_' . substr($row['permission'], 0, -4)] . ' - ' . $txt['permissionname_' . $row['permission']];
		else
			$name = $txt['permissionname_' . $row['permission']];

		// Add this permission if it doesn't exist yet.
		if (!isset($context['member']['permissions']['general'][$row['permission']]))
			$context['member']['permissions']['general'][$row['permission']] = array(
				'id' => $row['permission'],
				'groups' => array(
					'allowed' => array(),
					'denied' => array()
				),
				'name' => $name,
				'is_denied' => false,
				'is_global' => true,
			);

		// Add the membergroup to either the denied or the allowed groups.
		$context['member']['permissions']['general'][$row['permission']]['groups'][empty($row['addDeny']) ? 'denied' : 'allowed'][] = $row['ID_GROUP'] == 0 ? $txt['membergroups_members'] : $row['groupName'];

		// Once denied is always denied.
		$context['member']['permissions']['general'][$row['permission']]['is_denied'] = in_array($row['permission'], $denied);
	}
	mysql_free_result($result);

	// Retrieve the board specific permissions.
	$result = db_query("
		SELECT bp.ID_BOARD, bp.permission, bp.addDeny, mg.groupName, bp.ID_GROUP, b.use_local_permissions, b.name
		FROM {$db_prefix}board_permissions AS bp
			LEFT JOIN {$db_prefix}boards AS b ON (b.ID_BOARD = bp.ID_BOARD)
			LEFT JOIN {$db_prefix}membergroups AS mg ON (mg.ID_GROUP = bp.ID_GROUP)
		WHERE bp.ID_GROUP IN (" . implode(', ', $curGroups) . ")" . (empty($board) ? '' : "
			AND bp.ID_BOARD = $board") . "
		ORDER BY bp.permission, b.boardOrder, mg.minPosts, IF(mg.ID_GROUP < 4, mg.ID_GROUP, 4), mg.groupName", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($result))
	{
		// We don't know about this permission, it doesn't exist :P.
		if (!isset($txt['permissionname_' . $row['permission']]))
			continue;

		// The name of the permission using the format 'permission name' - 'own/any topic/event/etc.'.
		if (in_array(substr($row['permission'], -4), array('_own', '_any')) && isset($txt['permissionname_' . substr($row['permission'], 0, -4)]))
			$name = $txt['permissionname_' . substr($row['permission'], 0, -4)] . ' - ' . $txt['permissionname_' . $row['permission']];
		else
			$name = $txt['permissionname_' . $row['permission']];

		// Create the structure for this permission.
		if (!isset($context['member']['permissions']['board'][$row['permission']]))
			$context['member']['permissions']['board'][$row['permission']] = array(
				'id' => $row['permission'],
				'groups' => array(
					'allowed' => array(),
					'denied' => array()
				),
				'name' => $name,
				'boards' => array(
					'allowed' => array(),
					'denied' => array(),
				),
				'is_denied' => false,
				'is_global' => false,
			);

		// Either we're dealing with global permissions, or we've selected a board to be shown.
		if (empty($row['ID_BOARD']) || !empty($board))
		{
			$context['member']['permissions']['board'][$row['permission']]['is_global'] = true;

			$context['member']['permissions']['board'][$row['permission']]['groups'][empty($row['addDeny']) ? 'denied' : 'allowed'][$row['ID_GROUP']] = $row['ID_GROUP'] == 0 ? $txt['membergroups_members'] : $row['groupName'];

			$context['member']['permissions']['board'][$row['permission']]['is_denied'] = $context['member']['permissions']['board'][$row['permission']]['is_denied'] || empty($row['addDeny']);
		}
		// This is a local permission, make sure this board actually uses local permissions.
		elseif (!empty($row['use_local_permissions']))
		{
			// Deny this permission.
			if (empty($row['addDeny']))
			{
				$context['member']['permissions']['board'][$row['permission']]['boards']['denied'][$row['ID_BOARD']] = $row['name'];

				// Remove the board from the allowed array.
				if (isset($context['member']['permissions']['board'][$row['permission']]['boards']['allowed'][$row['ID_BOARD']]))
					unset($context['member']['permissions']['board'][$row['permission']]['boards']['allowed'][$row['ID_BOARD']]);
			}
			// Allow only if it's not denied.
			elseif (!in_array($row['ID_BOARD'], array_keys($context['member']['permissions']['board'][$row['permission']]['boards']['denied'])))
				$context['member']['permissions']['board'][$row['permission']]['boards']['allowed'][$row['ID_BOARD']] = $row['name'];
		}
	}
	mysql_free_result($result);

	// Get rid of denied permissions if you're looking at a specific board.
	if (!empty($board))
	{
		foreach ($context['member']['permissions']['board'] as $ID_PERM => $permission)
			if ($permission['is_denied'])
				unset($context['member']['permissions']['board'][$ID_PERM]);
	}
	else
	{
		foreach ($context['member']['permissions']['board'] as $ID_PERM => $permission)
		{
			// Get rid of permissions that are only locally set and on deny.
			if (!$permission['is_global'] && empty($permission['boards']['allowed']))
				unset($context['member']['permissions']['board'][$ID_PERM]);
			// Get rid of permissions that are globally on deny and nowhere locally set.
			elseif ($permission['is_global'] && $permission['is_denied'] && empty($permission['boards']['allowed']))
				unset($context['member']['permissions']['board'][$ID_PERM]);
		}
	}
}

// This function is used to reassociate members with relevant posts.
function reattributePosts($memID, $email = false, $post_count = false)
{
	global $db_prefix;

	// Firstly, if $email isn't passed find out the members email address.
	if ($email === false)
	{
		$request = db_query("
			SELECT emailAddress
			FROM {$db_prefix}members
			WHERE ID_MEMBER = $memID
			LIMIT 1", __FILE__, __LINE__);
		list ($email) = mysql_fetch_row($request);
		mysql_free_result($request);
	}

	// If they want the post count restored then we need to do some research.
	if ($post_count)
	{
		$request = db_query("
			SELECT COUNT(m.ID_MSG)
			FROM {$db_prefix}messages AS m, {$db_prefix}boards AS b
			WHERE m.ID_MEMBER = 0
				AND m.posterEmail = '$email'
				AND b.ID_BOARD = m.ID_MEMBER
				AND b.countPosts = 1", __FILE__, __LINE__);
		list ($messageCount) = mysql_fetch_row($request);
		mysql_free_result($request);

		db_query("
			UPDATE {$db_prefix}members
			SET posts = posts + $messageCount
			WHERE ID_MEMBER = $memID
			LIMIT 1", __FILE__, __LINE__);
	}

	// Finally, update the posts themselves...
	db_query("
		UPDATE {$db_prefix}messages
		SET ID_MEMBER = $memID
		WHERE posterEmail = '$email'", __FILE__, __LINE__);

	return db_affected_rows();
}

?>