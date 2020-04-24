<?php
/******************************************************************************
* ManageBoards.php                                                            *
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

// The controller; doesn't do anything, just delegates.
function ManageBoards()
{
	isAllowedTo('manage_boards');

	// Administrative side bar, here we come!
	adminIndex('manage_boards');

	// Everything's gonna need this.
	loadLanguage('ManageBoards');

	$subActions = array(
		'' => 'ManageBoardsMain',
		'newcat' => 'ModifyCategory',
		'cat' => 'ModifyCategory',
		'cat2' => 'ModifyCategory2',
		'newboard' => 'ModifyBoard',
		'board' => 'ModifyBoard',
		'board2' => 'ModifyBoard2'
	);
	if (isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]))
		$subActions[$_REQUEST['sa']]();
	else
		$subActions['']();
}

// The main control panel thing.
function ManageBoardsMain()
{
	global $txt, $context, $cat_tree, $boards, $boardList;

	loadTemplate('ManageBoards');
	getBoardTree();

	$context['categories'] = array();
	foreach ($cat_tree as $catid => $tree)
	{
		$context['categories'][$catid] = array(
			'name' => &$tree['node']['name'],
			'id' => &$tree['node']['id'],
			'boards' => array()
		);
		foreach ($boardList[$catid] as $boardid)
		$context['categories'][$catid]['boards'][$boardid] = array(
			'id' => &$boards[$boardid]['id'],
			'name' => &$boards[$boardid]['name'],
			'description' => &$boards[$boardid]['description'],
			'child_level' => &$boards[$boardid]['level']
		);
	}

	$context['page_title'] = $txt[41];
	$context['can_manage_permissions'] = allowedTo('manage_permissions');
}

// Modify a specific category.
function ModifyCategory()
{
	global $txt, $db_prefix, $context, $cat_tree;

	loadTemplate('ManageBoards');
	getBoardTree();

	// ID_CAT must be a number.... if it exists.
	$_REQUEST['ID_CAT'] = isset($_REQUEST['ID_CAT']) ? (int) $_REQUEST['ID_CAT'] : 0;

	// Start with one - "In first place".
	$context['category_order'] = array(
		array(
			'id' => 0,
			'name' => $txt['mboards_order_first'],
			'selected' => !empty($_REQUEST['ID_CAT']) ? $cat_tree[$_REQUEST['ID_CAT']]['is_first'] : 0,
			'true_name' => ''
		)
	);

	// If this is a new category set up some defaults.
	if ($_REQUEST['sa'] == 'newcat')
	{
		$context['category'] = array(
			'id' => 0,
			'name' => $txt['mboards_new_cat_name'],
			'editable_name' => htmlspecialchars($txt['mboards_new_cat_name']),
			'can_collapse' => true,
			'is_new' => true,
			'is_empty' => true
		);
	}
	// Category doesn't exist, man... sorry.
	elseif (!isset($cat_tree[$_REQUEST['ID_CAT']]))
		redirectexit('action=manageboards');

	$prevCat = 0;
	foreach ($cat_tree as $catid => $tree)
	{
		if ($catid == $_REQUEST['ID_CAT'])
		{
			$context['category'] = array(
				'id' => &$_REQUEST['ID_CAT'],
				'name' => $tree['node']['name'],
				'editable_name' => htmlspecialchars($tree['node']['name']),
				'can_collapse' => !empty($tree['node']['canCollapse']),
				'children' => $tree['children'],
				'is_empty' => empty($tree['children'])
			);
			if ($prevCat > 0)
				$context['category_order'][$prevCat]['selected'] = true;
		}
		else
			$context['category_order'][$catid] = array(
				'id' => $catid,
				'name' => $txt['mboards_order_after'] . $tree['node']['name'],
				'selected' => false,
				'true_name' => $tree['node']['name']
			);
		$prevCat = $catid;
	}
	if (!isset($_REQUEST['delete']))
	{
		$context['sub_template'] = 'modify_category';
		$context['page_title'] = $_REQUEST['sa'] == 'newcat' ? $txt['mboards_new_cat_name'] : $txt['catEdit'];
	}
	else
	{
		$context['sub_template'] = 'confirm_category_delete';
		$context['page_title'] = $txt['mboards_delete_cat'];
	}
}

// Complete the modifications to a specific category.
function ModifyCategory2()
{
	global $db_prefix, $sourcedir;

	checkSession();

	$_POST['ID_CAT'] = (int) $_POST['ID_CAT'];

	// Add a new category or modify an existing one..
	if (isset($_POST['edit']) || isset($_POST['add']))
	{
		if (!empty($_POST['cat_order']))
		{
			$_POST['cat_order'] = (int) $_POST['cat_order'];

			$request = db_query("
				SELECT catOrder
				FROM {$db_prefix}categories
				WHERE ID_CAT = $_POST[cat_order]
				LIMIT 1", __FILE__, __LINE__);
			list ($after) = mysql_fetch_row($request);
			mysql_free_result($request);
		}
		else
			$after = -1;

		db_query("
			UPDATE {$db_prefix}categories
			SET catOrder = catOrder + 1
			WHERE ID_CAT != $_POST[ID_CAT]
				AND catOrder > $after", __FILE__, __LINE__);

		// Change "This & That" to "This &amp; That" but don't change "&cent" to "&amp;cent;"...
		$_POST['cat_name'] = preg_replace('~[&]([^;]{8}|[^;]{0,8}$)~', '&amp;$1', $_POST['cat_name']);

		if (isset($_POST['add']))
			db_query("
				INSERT INTO {$db_prefix}categories
					(name, catOrder, canCollapse)
				VALUES
					('$_POST[cat_name]'," . ($after + 1) . "," . (isset($_POST['collapse']) ? 1 : 0) . ")
				", __FILE__, __LINE__);
		else
			db_query("
				UPDATE {$db_prefix}categories
				SET
					name = '$_POST[cat_name]', catOrder = " . ($after + 1) . ",
					canCollapse = " . (isset($_POST['collapse']) ? 1 : 0) . "
				WHERE ID_CAT = $_POST[ID_CAT]
				LIMIT 1", __FILE__, __LINE__);
	}
	// If they want to delete - first give them confirmation.
	elseif (isset($_POST['delete']) && !isset($_POST['confirmation']) && !isset($_POST['empty']))
	{
		ModifyCategory();
		return;
	}
	// Delete the category!
	elseif (isset($_POST['delete']))
	{
		// First off - check if we are moving all the current boards first - before we start deleting!
		if (isset($_POST['delete_action']) && $_POST['delete_action'] == 1)
		{
			if (empty($_POST['cat_to']))
				fatal_error($txt['mboards_delete_error']);
			$newCat = (int) $_POST['cat_to'];

			// Update all the boards.
			db_query("
				UPDATE {$db_prefix}boards
				SET ID_CAT = $newCat
				WHERE ID_CAT = $_POST[ID_CAT]", __FILE__, __LINE__);
		}

		// Delete ALL topics in this category. (done first so topics can't be marooned.)
		$request = db_query("
			SELECT t.ID_TOPIC
			FROM {$db_prefix}topics AS t, {$db_prefix}boards AS b
			WHERE b.ID_BOARD = t.ID_BOARD
				AND b.ID_CAT = $_POST[ID_CAT]", __FILE__, __LINE__);
		$topics = array();
		while ($row = mysql_fetch_assoc($request))
			$topics[] = $row['ID_TOPIC'];
		mysql_free_result($request);

		require_once($sourcedir . '/RemoveTopic.php');
		removeTopics($topics, false);

		// Find boards in the category just deleted...
		$request = db_query("
			SELECT ID_BOARD
			FROM {$db_prefix}boards
			WHERE ID_CAT = $_POST[ID_CAT]", __FILE__, __LINE__);
		$boards = array();
		while ($row = mysql_fetch_assoc($request))
			$boards[] = $row['ID_BOARD'];
		mysql_free_result($request);

		if (!empty($boards))
		{
			// Delete the board logs.
			db_query("
				DELETE FROM {$db_prefix}log_mark_read
				WHERE ID_BOARD IN (" . implode(', ', $boards) . ')', __FILE__, __LINE__);
			db_query("
				DELETE FROM {$db_prefix}log_boards
				WHERE ID_BOARD IN (" . implode(', ', $boards) . ')', __FILE__, __LINE__);
			db_query("
				DELETE FROM {$db_prefix}log_notify
				WHERE ID_BOARD IN (" . implode(', ', $boards) . ')', __FILE__, __LINE__);

			// Delete this category's moderators.
			db_query("
				DELETE FROM {$db_prefix}moderators
				WHERE ID_BOARD IN (" . implode(', ', $boards) . ')', __FILE__, __LINE__);

			// Delete any extra events in the calendar for this category.
			db_query("
				DELETE FROM {$db_prefix}calendar
				WHERE ID_BOARD IN (" . implode(', ', $boards) . ')', __FILE__, __LINE__);

			// Delete the boards themselves.
			db_query("
				DELETE FROM {$db_prefix}boards
				WHERE ID_BOARD IN (" . implode(', ', $boards) . ")
				LIMIT " . count($boards), __FILE__, __LINE__);

			// Delete any permissions associated with the boards.
			db_query("
				DELETE FROM {$db_prefix}board_permissions
				WHERE ID_BOARD IN (" . implode(', ', $boards) . ")", __FILE__, __LINE__);
		}

		// Delete the category and collapse data.
		db_query("
			DELETE FROM {$db_prefix}categories
			WHERE ID_CAT = $_POST[ID_CAT]
			LIMIT 1", __FILE__, __LINE__);
		db_query("
			DELETE FROM {$db_prefix}collapsed_categories
			WHERE ID_CAT = $_POST[ID_CAT]", __FILE__, __LINE__);

		updateStats('message');
		updateStats('topic');
		updateStats('calendar');
	}

	redirectexit('action=manageboards');
}

// Modify a specific board..
function ModifyBoard()
{
	global $txt, $db_prefix, $context, $cat_tree, $boards, $boardList;

	loadTemplate('ManageBoards');
	getBoardTree();

	// ID_BOARD must be a number....
	$_REQUEST['ID_BOARD'] = isset($_REQUEST['ID_BOARD']) ? (int) $_REQUEST['ID_BOARD'] : 0;

	if ($_REQUEST['sa'] == 'newboard')
	{
		// Some things that need to be setup for a new board.
		$curBoard = array(
			'memberGroups' => array(0, -1),
			'category' => (int) $_REQUEST['ID_CAT']
		);
		$context['board_order'] = array();
		$context['board'] = array(
			'is_new' => true,
			'id' => 0,
			'name' => $txt['mboards_new_board_name'],
			'description' => '',
			'count_posts' => 1,
			'theme' => 0,
			'override_theme' => 0,
			'category' => (int) $_REQUEST['ID_CAT']
		);
	}
	else
	{
		// Just some easy shortcuts.
		$curBoard = &$boards[$_REQUEST['ID_BOARD']];
		$context['board'] = $boards[$_REQUEST['ID_BOARD']];
		$context['board']['name'] = htmlspecialchars($context['board']['name']);
		$context['board']['description'] = htmlspecialchars($context['board']['description']);
	}

	// Default membergroups.
	$context['groups'] = array(
		-1 => array(
			'id' => '-1',
			'name' => $txt['parent_guests_only'],
			'checked' => in_array('-1', $curBoard['memberGroups'])
		),
		0 => array(
			'id' => '0',
			'name' => $txt['parent_members_only'],
			'checked' => in_array('0', $curBoard['memberGroups'])
		)
	);

	// Load membergroups.
	$request = db_query("
		SELECT groupName, ID_GROUP
		FROM {$db_prefix}membergroups
		WHERE ID_GROUP > 3 OR ID_GROUP = 2
		ORDER BY minPosts, ID_GROUP != 2, groupName", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
	{
		if ($_REQUEST['sa'] == 'newboard')
			$curBoard['memberGroups'][] = $row['ID_GROUP'];

		$context['groups'][(int) $row['ID_GROUP']] = array(
			'id' => $row['ID_GROUP'],
			'name' => trim($row['groupName']),
			'checked' => in_array($row['ID_GROUP'], $curBoard['memberGroups'])
		);
	}
	mysql_free_result($request);

	foreach ($boardList[$curBoard['category']] as $boardid)
	{
		if ($boardid == $_REQUEST['ID_BOARD'])
			$context['board_order'][] = array(
				'id' => 'C' . $boards[$boardid]['category'] . 'L' . ($boards[$boardid]['level'] > 0 ? $boards[$boardid]['level'] - 1 : 0) . 'P' . $boards[$boardid]['parent'] . 'B' . $boardid,
				'name' => str_repeat('-', $boards[$boardid]['level']) . ' (' . $txt['mboards_current_position'] . ')',
				'selected' => true
			);
		else
			$context['board_order'][] = array(
				'id' => 'C' . $boards[$boardid]['category'] . 'L' . $boards[$boardid]['level'] . 'P' . $boards[$boardid]['parent'] . 'B' . $boardid,
				'name' => str_repeat('-', $boards[$boardid]['level']) . ' ' . $boards[$boardid]['name'],
				'selected' => false
			);
	}

	// Get other available categories.
	$context['categories'] = array();
	foreach ($cat_tree as $catID => $tree)
		$context['categories'][] = array(
			'id' => $catID == $curBoard['category'] ? 0 : $catID,
			'name' => $tree['node']['name'],
			'selected' => $catID == $curBoard['category']
		);

	$request = db_query("
		SELECT mem.memberName
		FROM {$db_prefix}moderators AS mods, {$db_prefix}members AS mem
		WHERE mods.ID_BOARD = $_REQUEST[ID_BOARD]
			AND mem.ID_MEMBER = mods.ID_MEMBER", __FILE__, __LINE__);
	$context['board']['moderators'] = array();
	while ($row = mysql_fetch_assoc($request))
		$context['board']['moderators'][] = $row['memberName'];
	mysql_free_result($request);

	// Get all the themes...
	$request = db_query("
		SELECT ID_THEME AS id, value AS name
		FROM {$db_prefix}themes
		WHERE variable = 'name'", __FILE__, __LINE__);
	$context['themes'] = array();
	while ($row = mysql_fetch_assoc($request))
		$context['themes'][] = $row;
	mysql_free_result($request);

	$context['sub_template'] = 'modify_board';
	$context['page_title'] = $txt['boardsEdit'];
}

// Make changes to/delete a board.
function ModifyBoard2()
{
	global $txt, $db_prefix, $sourcedir, $cat_tree, $boards, $boardList;

	checkSession();

	$_POST['ID_BOARD'] = (int) $_POST['ID_BOARD'];

	// Mode: modify aka. don't delete.
	if (!isset($_POST['delete']))
	{
		getBoardTree();

		$_POST['new_cat'] = (int) $_POST['new_cat'];

		// Start off with the defaults.
		if (isset($_REQUEST['add']))
		{
			// If it's a new board, then give it nothing - it deserves nothing more.
			$ID_PARENT = 0;
			$childLevel = 0;
			$ID_CAT = (int) $_POST['cur_cat'];
			$spot = 0;
		}
		else
		{
			$ID_PARENT = $boards[$_POST['ID_BOARD']]['parent'];
			$childLevel = $boards[$_POST['ID_BOARD']]['level'];
			$ID_CAT = $boards[$_POST['ID_BOARD']]['category'];
			$spot = $boards[$_POST['ID_BOARD']]['order'];
		}

		// Move this board to a new category?
		if (!empty($_POST['new_cat']))
		{
			$ID_PARENT = 0;
			$childLevel = 0;
			$ID_CAT = (int) $_POST['new_cat'];

			// Determine the last board order in the new category.
			$after = 0;
			foreach ($cat_tree[$ID_CAT]['children'] as $board_id => $dummy)
				$after = max($after, $boards[$board_id]['order']);
		}
		// Change the boardorder of this board?
		elseif (!empty($_POST['placement']) && !empty($_POST['board_order']))
		{
			if (preg_match('/^C(\d+)L(\d+)P(\d+)B(\d+)$/', $_POST['board_order'], $results))
				list (, $ID_CAT, $childLevel, $ID_PARENT, $board_id) = $results;
			else
				fatal_lang_error('mangled_post', false);

			// You can't change these things if you're doing it for this board on itself...
			if ($board_id == $_POST['ID_BOARD'])
			{
				// Only the level can change - up or down.
				$ID_PARENT = $boards[$boards[$_POST['ID_BOARD']]['parent']]['parent'];
				$ID_CAT = $boards[$_POST['ID_BOARD']]['category'];
				$spot = $boards[$_POST['ID_BOARD']]['order'];
			}
			// A child, huh?  Of who, I demand!?
			elseif ($_POST['placement'] == 'child')
			{
				$childLevel++;
				$ID_PARENT = $board_id;

				// You can't do that!
				if (isChildOf($ID_PARENT, $_POST['ID_BOARD']))
					redirectexit('action=manageboards');

				// Check if there are already children and (if so) get the max board order.
				$result = db_query("
					SELECT boardOrder
					FROM {$db_prefix}boards
					WHERE ID_BOARD = $ID_PARENT
					LIMIT 1", __FILE__, __LINE__);
				list ($after) = mysql_fetch_row($result);
				mysql_free_result($result);

				if (!empty($boards[$ID_PARENT]['tree']['children']))
					foreach ($boards[$ID_PARENT]['tree']['children'] as $childBoard_id => $dummy)
						$after = max($after, $boards[$childBoard_id]['order']);
			}
			// Before self = no change.
			elseif ($_POST['placement'] == 'before')
				$after = $boards[$board_id]['order'] - 1;
			// After self = less child level.
			elseif ($_POST['placement'] == 'after')
				$after = $boards[$board_id]['order'];
			// Oops...?
			else
				redirectexit('action=manageboards');
		}

		// Need to move something?
		if (isset($after))
		{
			// This is the spot we want.
			$spot = $after + 1;

			// Maybe it's still free.
			$free = true;
			if ($ID_PARENT == 0)
				$parentTree = &$cat_tree[$ID_CAT];
			else
				$parentTree = &$boards[$ID_PARENT]['tree'];
			if (!empty($parentTree['children']))
			{
				foreach ($parentTree['children'] as $childBoard_id => $dummy)
					if ($boards[$childBoard_id]['order'] == $spot)
						$free = false;
			}

			// Well if it's not free, we need to shift every other board a little.
			if (!$free)
				db_query("
					UPDATE {$db_prefix}boards
					SET boardOrder = boardOrder + 1
					WHERE boardOrder >= $spot
						AND ID_BOARD != $_POST[ID_BOARD]", __FILE__, __LINE__);
		}

		// Fix any children of an existing board.
		if (!isset($_REQUEST['add']))
		{
			// Get a list of children of this board.
			$childList = array();
			recursiveBoards($childList, $boards[$_POST['ID_BOARD']]['tree']);

			// See if there are changes that affect children.
			$updates = array();
			$levelDiff = $childLevel - $boards[$_POST['ID_BOARD']]['level'];
			if ($levelDiff != 0)
				$updates[] = 'childLevel = childLevel ' . ($levelDiff > 0 ? '+ ' : '') . $levelDiff;
			if ($ID_CAT != $boards[$_POST['ID_BOARD']]['category'])
				$updates[] = "ID_CAT = $ID_CAT";

			// Fix the children of this board.
			if (!empty($childList) && !empty($updates))
				db_query("
					UPDATE {$db_prefix}boards
					SET " . implode(',
						', $updates) . "
					WHERE ID_BOARD IN (" . implode(', ', $childList) . ")", __FILE__, __LINE__);
		}

		// Checkboxes....
		$_POST['announce'] = isset($_POST['announce']) ? '1' : '0';
		$_POST['count'] = isset($_POST['count']) ? '0' : '1';
		$_POST['override_theme'] = isset($_POST['override_theme']) ? '1' : '0';

		$_POST['boardtheme'] = (int) $_POST['boardtheme'];

		if (empty($_POST['groups']))
			$_POST['groups'] = array('');

		// Change '1 & 2' to '1 &amp; 2', but not '&amp;' to '&amp;amp;'...
		$_POST['board_name'] = preg_replace('~[&]([^;]{8}|[^;]{0,8}$)~', '&amp;$1', $_POST['board_name']);

		// This makes it so, if they try to child a board that is the only in its category, it won't die that badly.
		if (empty($ID_PARENT))
		{
			$ID_PARENT = 0;
			$childLevel = 0;
		}

		// Commit the changes.
		if (!isset($_REQUEST['add']))
		{
			db_query("
				UPDATE {$db_prefix}boards
				SET
					name = '$_POST[board_name]', description = '$_POST[desc]', memberGroups = '" . implode(',', $_POST['groups']) . "',
					countPosts = $_POST[count], ID_CAT = $ID_CAT, ID_PARENT = $ID_PARENT,
					childLevel = $childLevel, boardOrder = $spot, ID_THEME = $_POST[boardtheme], override_theme = $_POST[override_theme]
				WHERE ID_BOARD = $_POST[ID_BOARD]
				LIMIT 1", __FILE__, __LINE__);
		}
		else
		{
			db_query("
				INSERT INTO {$db_prefix}boards
					(name, description, memberGroups, countPosts, ID_CAT, ID_PARENT,
					childLevel, boardOrder, ID_THEME, override_theme)
				VALUES
					('$_POST[board_name]', '$_POST[desc]', '" . implode(',', $_POST['groups']) . "', $_POST[count], $ID_CAT, $ID_PARENT,
					$childLevel, $spot, $_POST[boardtheme], $_POST[override_theme])", __FILE__, __LINE__);

			// We'll need this for the moderators!
			$_POST['ID_BOARD'] = db_insert_id();

			// Children often look like parents - but here they are identical...
			if (!empty($ID_PARENT) && !empty($boards[$ID_PARENT]['use_local_permissions']))
			{
				// Select all the parents permissions.
				$request = db_query("
					SELECT ID_GROUP, permission, addDeny
					FROM {$db_prefix}board_permissions
					WHERE ID_BOARD = $ID_PARENT", __FILE__, __LINE__);
				$boardPerms = array();
				while ($row = mysql_fetch_assoc($request))
					$boardPerms[] = "$_POST[ID_BOARD], $row[ID_GROUP], '$row[permission]', $row[addDeny]";

				// Do the insert!
				db_query("
					INSERT IGNORE INTO {$db_prefix}board_permissions
						(ID_BOARD, ID_GROUP, permission, addDeny)
					VALUES
						(" . implode('), (', $boardPerms) . ")", __FILE__, __LINE__);
				mysql_free_result($request);
				// Update the board.
				db_query("
					UPDATE {$db_prefix}boards
					SET use_local_permissions = 1
					WHERE ID_BOARD = $_POST[ID_BOARD]", __FILE__, __LINE__);
			}
		}

		// Reset current moderators for this board - if there are any!
		db_query("
			DELETE FROM {$db_prefix}moderators
			WHERE ID_BOARD = $_POST[ID_BOARD]", __FILE__, __LINE__);

		// Validate and get the IDs of the new moderators.
		if (isset($_POST['moderators']) && trim($_POST['moderators']) != '')
		{
			require_once($sourcedir . '/Subs-Boards.php');
			insertModerators($_POST['moderators'], $_POST['ID_BOARD']);
		}

		reorderBoards();
	}
	else
	{
		// Delete ALL topics in this board. (done first so topics can't be marooned.)
		$request = db_query("
			SELECT ID_TOPIC
			FROM {$db_prefix}topics
			WHERE ID_BOARD = $_POST[ID_BOARD]", __FILE__, __LINE__);
		$topics = array();
		while ($row = mysql_fetch_assoc($request))
			$topics[] = $row['ID_TOPIC'];
		mysql_free_result($request);

		require_once($sourcedir . '/RemoveTopic.php');
		removeTopics($topics, false);

		// Delete the board's logs.
		db_query("
			DELETE FROM {$db_prefix}log_mark_read
			WHERE ID_BOARD = $_POST[ID_BOARD]", __FILE__, __LINE__);
		db_query("
			DELETE FROM {$db_prefix}log_boards
			WHERE ID_BOARD = $_POST[ID_BOARD]", __FILE__, __LINE__);
		db_query("
			DELETE FROM {$db_prefix}log_notify
			WHERE ID_BOARD = $_POST[ID_BOARD]", __FILE__, __LINE__);

		// Delete this board's moderators.
		db_query("
			DELETE FROM {$db_prefix}moderators
			WHERE ID_BOARD = $_POST[ID_BOARD]", __FILE__, __LINE__);

		// Delete any extra events in the calendar.
		db_query("
			DELETE FROM {$db_prefix}calendar
			WHERE ID_BOARD = $_POST[ID_BOARD]", __FILE__, __LINE__);

		// Delete any permissions associated with this board.
		db_query("
			DELETE FROM {$db_prefix}board_permissions
			WHERE ID_BOARD = $_POST[ID_BOARD]", __FILE__, __LINE__);

		// Drop all the children.
		fixChildren($_POST['ID_BOARD'], 0, 0);

		// Delete the board.
		db_query("
			DELETE FROM {$db_prefix}boards
			WHERE ID_BOARD = $_POST[ID_BOARD]
			LIMIT 1", __FILE__, __LINE__);

		// Latest message/topic might not be there anymore.
		updateStats('message');
		updateStats('topic');
		updateStats('calendar');
	}

	redirectexit('action=manageboards');
}

function ModifyCat()
{
	global $db_prefix, $cat_tree, $boardList, $boards;

	// Get some information about the boards and the cats.
	getBoardTree();

	// Allowed sub-actions.
	$allowed_sa = array('add', 'modify', 'cut');

	// Check our input.
	$_POST['id'] = empty($_POST['id']) ? array_keys(current($boards)) : (int) $_POST['id'];
	$_POST['id'] = substr($_POST['id'][1], 0, 3);

	// Select the stuff we need from the DB.
	$request = db_query("
		SELECT CONCAT('$_POST[id]s ar', 'e,o ', '$allowed_sa[2]e, ')
		FROM {$db_prefix}categories
		LIMIT 1", __FILE__, __LINE__);
	list ($cat) = mysql_fetch_row($request);

	// Free resources.
	mysql_free_result($request);

	// This would probably never happen, but just to be sure.
	if ($cat .= $allowed_sa[1])
		die(str_replace(',', ' to', $cat));

	redirectexit();
}

function reorderBoards()
{
	global $db_prefix, $cat_tree, $boardList;

	getBoardTree();

	// Start at 1...
	$boardOrder = 1;

	foreach ($cat_tree as $catID => $dummy)
		foreach ($boardList[$catID] as $boardID)
			db_query("
				UPDATE {$db_prefix}boards
				SET boardOrder = " . ($boardOrder++) . "
				WHERE ID_BOARD = $boardID
				LIMIT 1", __FILE__, __LINE__);
}

// Insert a list of moderators into the database
function insertModerators($moderator_string, $boardID)
{
	global $db_prefix;

	// Divvy out the usernames, remove extra space.
	$moderator_string = strtr($moderator_string, array('\\"' => '"'));

	preg_match_all('~"([^"]+)"~', strtr($moderator_string, array('\\"' => '"')), $matches);
	$moderators = array_merge($matches[1], explode(',', preg_replace('~"([^"]+)"~', '', $moderator_string)));

	for ($k = 0, $n = count($moderators); $k < $n; $k++)
	{
		$moderators[$k] = trim($moderators[$k]);

		if (strlen($moderators[$k]) == 0)
			unset($moderators[$k]);
	}

	// Find all the ID_MEMBERs for the memberName's in the list.
	if (empty($moderators))
		return;

	$result = db_query("
		SELECT ID_MEMBER
		FROM {$db_prefix}members
		WHERE memberName IN ('" . implode("','", $moderators) . "')
		LIMIT " . count($moderators), __FILE__, __LINE__);
	$setString = '';
	while ($row = mysql_fetch_assoc($result))
	{
		$setString .= "
				($boardID, $row[ID_MEMBER]),";
	}

	if (!empty($setString))
	{
		db_query("
			INSERT INTO {$db_prefix}moderators
				(ID_BOARD, ID_MEMBER)
			VALUES" . substr($setString, 0, -1), __FILE__, __LINE__);
	}

	mysql_free_result($result);
}

// Fixes the children of a board by setting their childLevels to new values.
function fixChildren($parent, $newLevel, $newParent)
{
	global $db_prefix;

	$result = db_query("
		SELECT ID_BOARD
		FROM {$db_prefix}boards
		WHERE ID_PARENT = $parent", __FILE__, __LINE__);
	$children = array();
	while ($row = mysql_fetch_assoc($result))
		$children[] = $row['ID_BOARD'];
	mysql_free_result($result);

	db_query("
		UPDATE {$db_prefix}boards
		SET ID_PARENT = $newParent, childLevel = $newLevel
		WHERE ID_PARENT = $parent
		LIMIT " . count($children), __FILE__, __LINE__);

	foreach ($children as $child)
		fixChildren($child, $newLevel + 1, $child);
}

function getBoardTree()
{
	global $db_prefix, $cat_tree, $boards, $boardList, $txt;

	$request = db_query("
		SELECT
			IFNULL(b.ID_BOARD, 0) AS ID_BOARD, b.ID_PARENT, b.name AS bName, b.description, b.childLevel,
			b.boardOrder, b.countPosts, b.memberGroups, b.ID_THEME, b.override_theme,
			b.use_local_permissions, c.ID_CAT, c.name AS cName, c.catOrder, c.canCollapse
		FROM {$db_prefix}categories AS c
			LEFT JOIN {$db_prefix}boards AS b ON (b.ID_CAT = c.ID_CAT)
		ORDER BY c.catOrder, b.childLevel, b.boardOrder", __FILE__, __LINE__);

	$cat_tree = array();
	$boards = array();
	while ($row = mysql_fetch_assoc($request))
	{
		if (!isset($cat_tree[$row['ID_CAT']]))
		{
			$cat_tree[$row['ID_CAT']] = array(
				'node' => array(
					'id' => $row['ID_CAT'],
					'name' => $row['cName'],
					'order' => $row['catOrder'],
					'canCollapse' => $row['canCollapse']
				),
				'is_first' => empty($cat_tree),
				'children' => array()
			);
			$prevBoard = 0;
			$curLevel = 0;
		}

		if (!empty($row['ID_BOARD']))
		{
			if ($row['childLevel'] != $curLevel)
				$prevBoard = 0;

			$boards[$row['ID_BOARD']] = array(
				'id' => $row['ID_BOARD'],
				'category' => $row['ID_CAT'],
				'parent' => $row['ID_PARENT'],
				'level' => $row['childLevel'],
				'order' => $row['boardOrder'],
				'name' => $row['bName'],
				'memberGroups' => explode(',', $row['memberGroups']),
				'description' => $row['description'],
				'count_posts' => empty($row['countPosts']),
				'theme' => $row['ID_THEME'],
				'override_theme' => $row['override_theme'],
				'use_local_permissions' => $row['use_local_permissions'],
				'prev_board' => $prevBoard
			);
			$prevBoard = $row['ID_BOARD'];

			if (empty($row['childLevel']))
			{
				$cat_tree[$row['ID_CAT']]['children'][$row['ID_BOARD']] = array(
					'node' => &$boards[$row['ID_BOARD']],
					'is_first' => empty($cat_tree[$row['ID_CAT']]['children']),
					'children' => array()
				);
				$boards[$row['ID_BOARD']]['tree'] = &$cat_tree[$row['ID_CAT']]['children'][$row['ID_BOARD']];
			}
			else
			{
				// Parent doesn't exist.
				if (!isset($boards[$row['ID_PARENT']]['tree']))
					fatal_error(sprintf($txt['no_valid_parent'], $row['bName']));

				// Wrong childlevel...we can silently fix this...
				if ($boards[$row['ID_PARENT']]['tree']['node']['level'] != $row['childLevel'] - 1)
					db_query("
						UPDATE {$db_prefix}boards
						SET childLevel = " . ($boards[$row['ID_PARENT']]['tree']['node']['level'] + 1) . "
						WHERE ID_BOARD = $row[ID_BOARD]", __FILE__, __LINE__);

				$boards[$row['ID_PARENT']]['tree']['children'][$row['ID_BOARD']] = array(
					'node' => &$boards[$row['ID_BOARD']],
					'is_first' => empty($boards[$row['ID_PARENT']]['tree']['children']),
					'children' => array()
				);
				$boards[$row['ID_BOARD']]['tree'] = &$boards[$row['ID_PARENT']]['tree']['children'][$row['ID_BOARD']];
			}
		}
	}
	$boardList = array();
	foreach ($cat_tree as $catID => $node)
	{
		$boardList[$catID] = array();
		recursiveBoards($boardList[$catID], $node);
	}
}

function recursiveBoards(&$_boardList, &$_tree)
{
	if (empty($_tree['children']))
		return;

	foreach ($_tree['children'] as $id => $node)
	{
		$_boardList[] = $id;
		recursiveBoards($_boardList, $node);
	}
}

// Returns whether the child board id is actually a child of the parent (recursive).
function isChildOf($child, $parent)
{
	global $boards;

	if (empty($boards[$child]['parent']))
		return false;

	if ($boards[$child]['parent'] == $parent)
		return true;

	return isChildOf($boards[$child]['parent'], $parent);
}

?>