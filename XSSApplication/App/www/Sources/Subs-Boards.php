<?php
/******************************************************************************
* Subs-Boards.php                                                             *
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

/*	This file is mainly concerned with minor tasks relating to boards, such as
	marking them read, collapsing categories, or quick moderation.
*/

// Mark a board or multiple boards read.
function markBoardsRead($boards, $unread = false)
{
	global $db_prefix, $ID_MEMBER;

	// Force $boards to be an array.
	if (!is_array($boards))
		$boards = array($boards);
	else
		$boards = array_unique($boards);

	// No boards, nothing to mark as read.
	if (empty($boards))
		return;

	// Allow the user to mark a board as unread.
	if ($unread)
	{
		// Clear out all the places where this lovely info is stored.
		db_query("
			DELETE FROM {$db_prefix}log_mark_read
			WHERE ID_BOARD IN (" . implode(', ', $boards) . ")
				AND ID_MEMBER = $ID_MEMBER", __FILE__, __LINE__);
		db_query("
			DELETE FROM {$db_prefix}log_boards
			WHERE ID_BOARD IN (" . implode(', ', $boards) . ")
				AND ID_MEMBER = $ID_MEMBER", __FILE__, __LINE__);
	}
	// Otherwise mark the board as read.
	else
	{
		$setString = '';
		foreach ($boards as $board)
			$setString .= '
				(' . time() . ', ' . $ID_MEMBER . ', ' . $board . '),';
		$setString = substr($setString, 0, -1);

		// Update log_mark_read and log_boards.
		db_query("
			REPLACE INTO {$db_prefix}log_mark_read
				(logTime, ID_MEMBER, ID_BOARD)
			VALUES$setString", __FILE__, __LINE__);
		db_query("
			REPLACE INTO {$db_prefix}log_boards
				(logTime, ID_MEMBER, ID_BOARD)
			VALUES$setString", __FILE__, __LINE__);
	}

	// Get rid of useless log_topics data, because log_mark_read is better for it - even if marking unread - I think so...
	$result = db_query("
		SELECT lt.ID_TOPIC
		FROM {$db_prefix}log_topics AS lt, {$db_prefix}topics AS t
		WHERE t.ID_TOPIC = lt.ID_TOPIC
			AND t.ID_BOARD IN (" . implode(', ', $boards) . ")
			AND lt.ID_MEMBER = $ID_MEMBER", __FILE__, __LINE__);
	$topics = array();
	while ($row = mysql_fetch_assoc($result))
		$topics[] = $row['ID_TOPIC'];
	mysql_free_result($result);

	if (!empty($topics))
		db_query("
			DELETE FROM {$db_prefix}log_topics
			WHERE ID_MEMBER = $ID_MEMBER
				AND ID_TOPIC IN (" . implode(',', $topics) . ")
			LIMIT " . count($topics), __FILE__, __LINE__);
}

// Mark one or more boards as read.
function MarkRead()
{
	global $board, $topic, $user_info, $board_info, $ID_MEMBER, $db_prefix, $modSettings;

	// No Guests!
	is_not_guest();

	if (isset($_REQUEST['sa']) && $_REQUEST['sa'] == 'all')
	{
		// Find all the boards this user can see.
		$result = db_query("
			SELECT b.ID_BOARD
			FROM {$db_prefix}boards AS b
			WHERE $user_info[query_see_board]", __FILE__, __LINE__);
		if (mysql_num_rows($result) > 0)
		{
			$boards = array();
			while ($row = mysql_fetch_assoc($result))
				$boards[] = $row['ID_BOARD'];

			markBoardsRead($boards, isset($_REQUEST['unread']));
		}
		mysql_free_result($result);

		$_SESSION['ID_MSG_LAST_VISIT'] = $modSettings['maxMsgID'];
		if (!empty($_SESSION['old_url']) && strpos($_SESSION['old_url'], 'action=unread') !== false)
			redirectexit('action=unread');

		redirectexit();
	}
	elseif (isset($_REQUEST['sa']) && $_REQUEST['sa'] == 'unreadreplies')
	{
		// Make sure all the boards are integers!
		$topics = explode('-', $_REQUEST['topics']);

		$setString = '';
		foreach ($topics as $ID_TOPIC)
		{
			$ID_TOPIC = (int) $ID_TOPIC;
			$setString .= "
				(" . time() . ", $ID_MEMBER, $ID_TOPIC),";
		}

		db_query("
			REPLACE INTO {$db_prefix}log_topics
				(logTime, ID_MEMBER, ID_TOPIC)
			VALUES" . substr($setString, 0, -1), __FILE__, __LINE__);

		redirectexit('action=unreadreplies');
	}
	// Special case: mark a topic unread!
	elseif (isset($_REQUEST['sa']) && $_REQUEST['sa'] == 'topic')
	{
		$result = db_query("
			SELECT posterTime
			FROM {$db_prefix}messages
			WHERE ID_TOPIC = $topic
			ORDER BY ID_MSG
			LIMIT " . (int) $_REQUEST['start'] . ", 1", __FILE__, __LINE__);
		list ($earlyTime) = mysql_fetch_row($result);
		mysql_free_result($result);

		// Use a time one second earlier than the first time: blam, unread!
		db_query("
			REPLACE INTO {$db_prefix}log_topics
				(logTime, ID_MEMBER, ID_TOPIC)
			VALUES ($earlyTime - 1, $ID_MEMBER, $topic)", __FILE__, __LINE__);

		redirectexit('board=' . $board . '.0');
	}
	else
	{
		if (empty($board))
			redirectexit();

		markBoardsRead(array($board), isset($_REQUEST['unread']));

		if (!isset($_REQUEST['unread']))
		{
			// Find all the boards this user can see.
			$result = db_query("
				SELECT b.ID_BOARD
				FROM {$db_prefix}boards AS b
				WHERE b.ID_PARENT = $board
					AND childLevel = " . ($board_info['child_level'] + 1) . "
					AND $user_info[query_see_board]", __FILE__, __LINE__);
			if (mysql_num_rows($result) > 0)
			{
				$setString = '';
				while ($row = mysql_fetch_assoc($result))
					$setString .= '
						(' . time() . ', ' . $ID_MEMBER . ', ' . $row['ID_BOARD'] . '),';

				db_query("
					REPLACE INTO {$db_prefix}log_boards
						(logTime, ID_MEMBER, ID_BOARD)
					VALUES" . substr($setString, 0, -1), __FILE__, __LINE__);
			}
			mysql_free_result($result);

			redirectexit('board=' . $board . '.0');
		}
		else
		{
			if (empty($board_info['parent']))
				redirectexit();
			else
				redirectexit('board=' . $board_info['parent'] . '.0');
		}
	}
}

// Get the ID_MEMBER associated with the specified message.
function getMsgMemberID($messageID)
{
	global $db_prefix;

	// Find the topic and make sure the member still exists.
	$result = db_query("
		SELECT IFNULL(mem.ID_MEMBER, 0)
		FROM {$db_prefix}messages AS m
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = m.ID_MEMBER)
		WHERE m.ID_MSG = " . (int) $messageID . "
		LIMIT 1", __FILE__, __LINE__);
	if (mysql_num_rows($result) > 0)
		list ($memberID) = mysql_fetch_row($result);
	// The message doesn't even exist.
	else
		$memberID = 0;
	mysql_free_result($result);

	return $memberID;
}

// Collapse or expand a category
function CollapseCategory()
{
	global $ID_MEMBER, $db_prefix, $sourcedir;

	$_REQUEST['c'] = (int) $_REQUEST['c'];

	// Not very complicated... just make sure the value is there.
	if ($_REQUEST['sa'] == 'collapse')
	{
		db_query("
			INSERT IGNORE INTO {$db_prefix}collapsed_categories
				(ID_CAT, ID_MEMBER)
			VALUES ($_REQUEST[c], $ID_MEMBER)", __FILE__, __LINE__);
	}
	// Now just make sure it's not there.
	elseif ($_REQUEST['sa'] == 'expand')
	{
		db_query("
			DELETE FROM {$db_prefix}collapsed_categories
			WHERE ID_MEMBER = $ID_MEMBER
				AND ID_CAT = $_REQUEST[c]
			LIMIT 1", __FILE__, __LINE__);
	}

	// And go back to the back to board index.
	require_once($sourcedir . '/BoardIndex.php');
	BoardIndex();
}

// Allows for moderation from the message index.
function QuickModeration()
{
	global $db_prefix, $sourcedir, $board, $ID_MEMBER, $modSettings, $sourcedir;

	// Check the session = get or post.
	checkSession('request');

	// This is going to be needed to send off the notifications.
	require_once($sourcedir . '/Subs-Post.php');

	// Remember the last board they moved things to.
	if (isset($_REQUEST['move_to']))
		$_SESSION['move_to_topic'] = $_REQUEST['move_to'];

	// Only a few possible actions.
	$possibleActions = array();

	if (allowedTo('make_sticky') && !empty($modSettings['enableStickyTopics']))
		$possibleActions[] = 'sticky';
	if (allowedTo('move_any') || allowedTo('move_own'))
		$possibleActions[] = 'move';
	if (allowedTo('delete_any') | allowedTo('delete_own'))
		$possibleActions[] = 'remove';
	if (allowedTo('lock_any') | allowedTo('lock_own'))
		$possibleActions[] = 'lock';
	if (allowedTo('merge_any'))
		$possibleActions[] = 'merge';

	// Two methods: $_REQUEST['actions'] (ID_TOPIC => action), and $_REQUEST['topics'] and $_REQUEST['qaction'].
	// (if action is 'move', $_REQUEST['move_to'] or $_REQUEST['move_tos'][$topic] is used.)
	if (!empty($_REQUEST['topics']))
	{
		// If the action isn't valid, just quit now.
		if (empty($_REQUEST['qaction']) || !in_array($_REQUEST['qaction'], $possibleActions))
			redirectexit('board=' . $board . '.' . $_REQUEST['start']);

		// Merge requires all topics as one parameter and can be done at once.
		if ($_REQUEST['qaction'] == 'merge')
		{
			// Merge requires at least two topics.
			if (empty($_REQUEST['topics']) || count($_REQUEST['topics']) < 2)
				redirectexit('board=' . $board . '.' . $_REQUEST['start']);

			require_once($sourcedir . '/SplitTopics.php');
			return MergeTopics2($_REQUEST['topics']);
		}

		// Just convert to the other method, to make it easier.
		foreach ($_REQUEST['topics'] as $topic)
			$_REQUEST['actions'][$topic] = $_REQUEST['qaction'];
	}
	else
	{
		// Weird... how'd you get here?
		if (empty($_REQUEST['actions']))
			redirectexit('board=' . $board . '.' . $_REQUEST['start']);

		// Validate each action.
		foreach ($_REQUEST['actions'] as $topic => $action)
		{
			if (!in_array($action, $possibleActions))
				unset($_REQUEST['actions'][$topic]);
		}
	}

	if (!empty($_REQUEST['actions']))
	{
		// Find all topics that *aren't* on this board.
		$request = db_query("
			SELECT ID_TOPIC
			FROM {$db_prefix}topics
			WHERE ID_TOPIC IN (" . implode(', ', array_keys($_REQUEST['actions'])) . ")
				AND ID_BOARD != $board
			LIMIT " . count($_REQUEST['actions']), __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
			unset($_REQUEST['actions'][$row['ID_TOPIC']]);
		mysql_free_result($request);
	}

	$stickyCache = array();
	$moveCache = array(0 => array(), 1 => array());
	$removeCache = array();
	$lockCache = array();

	// Separate the actions.
	foreach ($_REQUEST['actions'] as $topic => $action)
	{
		$topic = (int) $topic;

		if ($action == 'sticky')
			$stickyCache[] = $topic;
		elseif ($action == 'move')
		{
			// $moveCache[0] is the topic, $moveCache[1] is the board to move to.
			$moveCache[1][$topic] = (int) (isset($_REQUEST['move_tos'][$topic]) ? $_REQUEST['move_tos'][$topic] : $_REQUEST['move_to']);

			if (empty($moveCache[1][$topic]))
				continue;

			$moveCache[0][] = $topic;
		}
		elseif ($action == 'remove')
			$removeCache[] = $topic;
		elseif ($action == 'lock')
			$lockCache[] = $topic;

		logAction($action, array('topic' => $topic));

		// Notify people that this topic has been locked/stickied/moved/removed?
		sendNotifications($topic, $action);
	}

	$affectedBoards = array($board => array(0, 0));

	// Do all the stickies...
	if (!empty($stickyCache))
	{
		db_query("
			UPDATE {$db_prefix}topics
			SET isSticky = IF(isSticky = 1, 0, 1)
			WHERE ID_TOPIC IN (" . implode(', ', $stickyCache) . ")
			LIMIT " . count($stickyCache), __FILE__, __LINE__);
	}

	// Move sucka! (this is, by the by, probably the most complicated part....)
	if (!empty($moveCache[0]))
	{
		// I know - I just KNOW you're trying to beat the system.  Too bad for you... we CHECK :P.
		$request = db_query("
			SELECT numReplies, ID_TOPIC
			FROM {$db_prefix}topics
			WHERE ID_TOPIC IN (" . implode(', ', $moveCache[0]) . ")" . (!allowedTo('move_any') ? "
				AND ID_MEMBER_STARTED = $ID_MEMBER" : '') . "
			LIMIT " . count($moveCache[0]), __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
		{
			$to = $moveCache[1][$row['ID_TOPIC']];
			$row['numReplies']++;

			if (empty($to))
				continue;

			if (!isset($affectedBoards[$to]))
				$affectedBoards[$to] = array(0, 0);

			$affectedBoards[$board][0]--;
			$affectedBoards[$board][1] -= $row['numReplies'];

			$affectedBoards[$to][0]++;
			$affectedBoards[$to][1] += $row['numReplies'];

			// Move the actual topic.
			db_query("
				UPDATE {$db_prefix}topics
				SET ID_BOARD = $to
				WHERE ID_TOPIC = $row[ID_TOPIC]
				LIMIT 1", __FILE__, __LINE__);

			db_query("
				UPDATE {$db_prefix}messages
				SET ID_BOARD = $to
				WHERE ID_TOPIC = $row[ID_TOPIC]", __FILE__, __LINE__);
			db_query("
				UPDATE {$db_prefix}calendar
				SET ID_BOARD = $to
				WHERE ID_TOPIC = $row[ID_TOPIC]", __FILE__, __LINE__);
		}
		mysql_free_result($request);

		foreach ($affectedBoards as $ID_BOARD => $topicsPosts)
		{
			db_query("
				UPDATE {$db_prefix}boards
				SET numPosts = numPosts + $topicsPosts[1], numTopics = numTopics + $topicsPosts[0]
				WHERE ID_BOARD = $ID_BOARD
				LIMIT 1", __FILE__, __LINE__);
		}
	}

	// Now delete the topics...
	if (!empty($removeCache))
	{
		// They can only delete their own topics. (we wouldn't be here if they couldn't do that..)
		if (!allowedTo('delete_any'))
		{
			$result = db_query("
				SELECT ID_TOPIC
				FROM {$db_prefix}topics
				WHERE ID_TOPIC IN (" . implode(', ', $removeCache) . ")
					AND ID_MEMBER_STARTED = $ID_MEMBER
				LIMIT " . count($removeCache), __FILE__, __LINE__);
			$removeCache = array();
			while ($row = mysql_fetch_assoc($result))
				$removeCache[] = $row['ID_TOPIC'];
			mysql_free_result($result);
		}

		// Maybe *none* were their own topics.
		if (!empty($removeCache))
		{
			require_once($sourcedir . '/RemoveTopic.php');
			removeTopics($removeCache);
		}
	}

	// And lastly, lock the topics...
	if (!empty($lockCache))
	{
		// Gotta make sure they CAN lock/unlock these topics...
		if (!allowedTo('lock_any'))
		{
			// Make sure they started the topic AND it isn't already locked by someone with higher priv's.
			$result = db_query("
				SELECT ID_TOPIC
				FROM {$db_prefix}topics
				WHERE ID_TOPIC IN (" . implode(', ', $lockCache) . ")
					AND ID_MEMBER_STARTED = $ID_MEMBER
					AND locked IN (2, 0)
				LIMIT " . count($lockCache), __FILE__, __LINE__);
			$lockCache = array();
			while ($row = mysql_fetch_assoc($result))
				$lockCache[] = $row['ID_TOPIC'];
			mysql_free_result($result);
		}

		// It could just be that *none* were their own topics...
		if (!empty($lockCache))
		{
			// Alternate the locked value.
			db_query("
				UPDATE {$db_prefix}topics
				SET locked = IF(locked = 0, " . (allowedTo('lock_any') ? '1' : '2') . ", 0)
				WHERE ID_TOPIC IN (" . implode(', ', $lockCache) . ")
				LIMIT " . count($lockCache), __FILE__, __LINE__);
		}
	}

	updateStats('topic');
	updateStats('message');
	updateStats('calendar');

	updateLastMessages(array_keys($affectedBoards));

	redirectexit('board=' . $board . '.' . $_REQUEST['start']);
}

// In-topic quick moderation.
function QuickModeration2()
{
	global $sourcedir, $db_prefix, $topic, $board, $ID_MEMBER;

	// Check the session = get or post.
	checkSession('request');

	require_once($sourcedir . '/RemoveTopic.php');

	if (empty($_REQUEST['msgs']))
		redirectexit('topic=' . $topic . '.' . $_REQUEST['start']);

	$messages = array();
	foreach ($_REQUEST['msgs'] as $dummy)
		$messages[] = (int) $dummy;

	// Allowed to delete any message?
	if (allowedTo('remove_any'))
		$allowed_all = true;
	// Allowed to delete replies to their messages?
	elseif (allowedTo('remove_replies'))
	{
		$request = db_query("
			SELECT ID_MEMBER_STARTED
			FROM {$db_prefix}topics
			WHERE ID_TOPIC = $topic
			LIMIT 1", __FILE__, __LINE__);
		list ($starter) = mysql_fetch_row($request);
		mysql_free_result($request);

		$allowed_all = $starter == $ID_MEMBER;
	}
	else
		$allowed_all = false;

	if (!$allowed_all)
		isAllowedTo('remove_own');

	// Allowed to remove which messages?
	$request = db_query("
		SELECT ID_MSG, subject, ID_MEMBER
		FROM {$db_prefix}messages
		WHERE ID_MSG IN (" . implode(', ', $messages) . ")
			AND ID_TOPIC = $topic" . (!$allowed_all ? "
			AND ID_MEMBER = $ID_MEMBER" : '') . "
		LIMIT " . count($messages), __FILE__, __LINE__);
	$messages = array();
	while ($row = mysql_fetch_assoc($request))
		$messages[$row['ID_MSG']] = array($row['subject'], $row['ID_MEMBER']);
	mysql_free_result($request);

	// Get the first message in the topic - because you can't delete that!
	$request = db_query("
		SELECT ID_FIRST_MSG
		FROM {$db_prefix}topics
		WHERE ID_TOPIC = $topic
		LIMIT 1", __FILE__, __LINE__);
	list ($first_message) = mysql_fetch_row($request);
	mysql_free_result($request);

	// Delete all the messages we know they can delete. ($messages)
	foreach ($messages as $message => $info)
	{
		// Just skip the first message.
		if ($message == $first_message)
			continue;

		removeMessage($message);

		// Log this moderation action ;).
		if (allowedTo('remove_any'))
			logAction('delete', array('topic' => $topic, 'subject' => $info[0], 'member' => $info[1]));
	}

	redirectexit('topic=' . $topic . '.' . $_REQUEST['start']);
}

?>