<?php
/******************************************************************************
* Recent.php                                                                  *
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

/*	This file had one very clear purpose.  It is here expressly to find and
	retrieve information about recently posted topics, messages, and the like.
*/

// Get the latest post.
function getLastPost()
{
	global $db_prefix, $user_info, $scripturl, $modSettings;

	// Find it by the board - better to order by board than sort the entire messages table.
	$request = db_query("
		SELECT ml.posterTime, ml.subject, ml.ID_TOPIC, ml.posterName, ml.body, ml.smileysEnabled
		FROM {$db_prefix}boards AS b, {$db_prefix}messages AS ml
		WHERE ml.ID_MSG = b.ID_LAST_MSG" . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? "
			AND b.ID_BOARD != $modSettings[recycle_board]" : '') . "
			AND $user_info[query_see_board]
		ORDER BY b.lastUpdated DESC
		LIMIT 1", __FILE__, __LINE__);
	if (mysql_num_rows($request) == 0)
		return array();
	$row = mysql_fetch_assoc($request);
	mysql_free_result($request);

	// Censor the subject and post...
	censorText($row['subject']);
	censorText($row['body']);

	$row['body'] = strip_tags(strtr(doUBBC($row['body'], $row['smileysEnabled']), array('<br />' => '&#10;')));
	if (strlen($row['body']) > 128)
		$row['body'] = substr($row['body'], 0, 128) . '...';

	// Send the data.
	return array(
		'topic' => $row['ID_TOPIC'],
		'subject' => $row['subject'],
		'short_subject' => strlen(un_htmlspecialchars($row['subject'])) > 24 ? strtr(substr(strtr($row['subject'], array('&lt;' => '<', '&gt;' => '>', '&quot;' => '"')), 0, 24) . '...', array('<' => '&lt;', '>' => '&gt;', '"' => '&quot;')) : $row['subject'],
		'preview' => $row['body'],
		'time' => timeformat($row['posterTime']),
		'timestamp' => $row['posterTime'],
		'href' => $scripturl . '?topic=' . $row['ID_TOPIC'] . '.new;topicseen#new',
		'link' => '<a href="' . $scripturl . '?topic=' . $row['ID_TOPIC'] . '.new;topicseen#new">' . $row['subject'] . '</a>'
	);
}

function getLastPosts($showlatestcount)
{
	global $scripturl, $txt, $db_prefix, $user_info, $modSettings;

	// Find all the posts.  Newer ones will have higher IDs.  (assuming the last 4 * number are accessable...)
	$request = db_query("
		SELECT
			m.posterTime, m.subject, m.ID_TOPIC, m.ID_MEMBER, m.ID_MSG,
			IFNULL(mem.realName, m.posterName) AS posterName, t.ID_BOARD, b.name AS bName,
			m.body, m.smileysEnabled
		FROM {$db_prefix}messages AS m, {$db_prefix}topics AS t, {$db_prefix}boards AS b
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = m.ID_MEMBER)
		WHERE m.ID_MSG >= " . ($modSettings['maxMsgID'] - 4 * $showlatestcount) . "
			AND t.ID_TOPIC = m.ID_TOPIC
			AND b.ID_BOARD = t.ID_BOARD" . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? "
			AND b.ID_BOARD != $modSettings[recycle_board]" : '') . "
			AND $user_info[query_see_board]
		ORDER BY m.ID_MSG DESC
		LIMIT $showlatestcount", __FILE__, __LINE__);
	$posts = array();
	while ($row = mysql_fetch_assoc($request))
	{
		// Censor the subject and post for the preview ;).
		censorText($row['subject']);
		censorText($row['body']);

		$row['body'] = strip_tags(strtr(doUBBC($row['body'], $row['smileysEnabled']), array('<br />' => '&#10;')));
		if (strlen($row['body']) > 128)
			$row['body'] = substr($row['body'], 0, 128) . '...';

		// Build the array.
		$posts[] = array(
			'board' => array(
				'id' => $row['ID_BOARD'],
				'name' => $row['bName'],
				'href' => $scripturl . '?board=' . $row['ID_BOARD'] . '.0',
				'link' => '<a href="' . $scripturl . '?board=' . $row['ID_BOARD'] . '.0">' . $row['bName'] . '</a>'
			),
			'topic' => $row['ID_TOPIC'],
			'poster' => array(
				'id' => $row['ID_MEMBER'],
				'name' => $row['posterName'],
				'href' => empty($row['ID_MEMBER']) ? '' : $scripturl . '?action=profile;u=' . $row['ID_MEMBER'],
				'link' => empty($row['ID_MEMBER']) ? $row['posterName'] : '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['posterName'] . '</a>'
			),
			'subject' => $row['subject'],
			'short_subject' => strlen(un_htmlspecialchars($row['subject'])) > 24 ? strtr(substr(strtr($row['subject'], array('&lt;' => '<', '&gt;' => '>', '&quot;' => '"')), 0, 24) . '...', array('<' => '&lt;', '>' => '&gt;', '"' => '&quot;')) : $row['subject'],
			'preview' => $row['body'],
			'time' => timeformat($row['posterTime']),
			'timestamp' => $row['posterTime'],
			'href' => $scripturl . '?topic=' . $row['ID_TOPIC'] . '.new;topicseen#new',
			'link' => '<a href="' . $scripturl . '?topic=' . $row['ID_TOPIC'] . '.from' . $row['posterTime'] . ';topicseen#msg' . $row['ID_MSG'] . '">' . $row['subject'] . '</a>'
		);
	}
	mysql_free_result($request);

	return $posts;
}

// Find the ten most recent posts.
function RecentPosts()
{
	global $txt, $scripturl, $db_prefix, $user_info, $context, $ID_MEMBER, $modSettings, $sourcedir;

	// They're deleting something... just skip back to it.
	if (isset($_GET['delete']))
	{
		// Luckily, removeMessage() checks permissions for us.
		require_once($sourcedir . '/RemoveTopic.php');
		removeMessage((int) $_GET['delete']);

		redirectexit('action=recent');
	}

	loadTemplate('Recent');
	$context['page_title'] = $txt[214];

	// Find the 10 most recent messages they can *view*.
	$request = db_query("
		SELECT m.ID_MSG
		FROM {$db_prefix}messages AS m, {$db_prefix}boards AS b
		WHERE b.ID_BOARD = m.ID_BOARD" . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? "
			AND b.ID_BOARD != $modSettings[recycle_board]" : '') . "
			AND $user_info[query_see_board]
		ORDER BY m.ID_MSG DESC
		LIMIT 10", __FILE__, __LINE__);
	$messages = array();
	while ($row = mysql_fetch_assoc($request))
		$messages[] = $row['ID_MSG'];
	mysql_free_result($request);

	if (empty($messages))
	{
		$context['posts'] = array();
		return;
	}

	// Get all the most recent posts.
	$request = db_query("
		SELECT
			m.ID_MSG, m.subject, m.smileysEnabled, m.posterTime, m.body, m.ID_TOPIC, t.ID_BOARD, b.ID_CAT,
			b.name AS bname, c.name AS cname, t.numReplies, m.ID_MEMBER, m2.ID_MEMBER AS ID_FIRST_MEMBER,
			IFNULL(mem2.realName, m2.posterName) AS firstPosterName, t.ID_FIRST_MSG,
			IFNULL(mem.realName, m.posterName) AS posterName, t.ID_LAST_MSG
		FROM {$db_prefix}messages AS m, {$db_prefix}messages AS m2, {$db_prefix}topics AS t, {$db_prefix}boards AS b, {$db_prefix}categories AS c
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = m.ID_MEMBER)
			LEFT JOIN {$db_prefix}members AS mem2 ON (mem2.ID_MEMBER = m2.ID_MEMBER)
		WHERE m2.ID_MSG = t.ID_FIRST_MSG
			AND t.ID_TOPIC = m.ID_TOPIC
			AND b.ID_BOARD = t.ID_BOARD
			AND c.ID_CAT = b.ID_CAT
			AND m.ID_MSG IN (" . implode(', ', $messages) . ")
		ORDER BY m.ID_MSG DESC
		LIMIT 0, 10", __FILE__, __LINE__);
	$counter = 1;
	$context['posts'] = array();
	$board_ids = array('own' => array(), 'any' => array());
	while ($row = mysql_fetch_assoc($request))
	{
		// Censor everything.
		censorText($row['body']);
		censorText($row['subject']);

		// BBC-atize the message.
		$row['body'] = doUBBC($row['body'], $row['smileysEnabled']);

		// And build the array.
		$context['posts'][$row['ID_MSG']] = array(
			'id' => $row['ID_MSG'],
			'counter' => $counter++,
			'category' => array(
				'id' => $row['ID_CAT'],
				'name' => $row['cname'],
				'href' => $scripturl . '#' . $row['ID_CAT'],
				'link' => '<a href="' . $scripturl . '#' . $row['ID_CAT'] . '">' . $row['cname'] . '</a>'
			),
			'board' => array(
				'id' => $row['ID_BOARD'],
				'name' => $row['bname'],
				'href' => $scripturl . '?board=' . $row['ID_BOARD'] . '.0',
				'link' => '<a href="' . $scripturl . '?board=' . $row['ID_BOARD'] . '.0">' . $row['bname'] . '</a>'
			),
			'topic' => $row['ID_TOPIC'],
			'href' => $scripturl . '?topic=' . $row['ID_TOPIC'] . '.msg' . $row['ID_MSG'] . '#msg' . $row['ID_MSG'],
			'link' => '<a href="' . $scripturl . '?topic=' . $row['ID_TOPIC'] . '.msg' . $row['ID_MSG'] . '#msg' . $row['ID_MSG'] . '">' . $row['subject'] . '</a>',
			'start' => $row['numReplies'],
			'subject' => $row['subject'],
			'time' => timeformat($row['posterTime']),
			'timestamp' => $row['posterTime'],
			'first_poster' => array(
				'id' => $row['ID_FIRST_MEMBER'],
				'name' => $row['firstPosterName'],
				'href' => empty($row['ID_FIRST_MEMBER']) ? '' : $scripturl . '?action=profile;u=' . $row['ID_FIRST_MEMBER'],
				'link' => empty($row['ID_FIRST_MEMBER']) ? $row['firstPosterName'] : '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_FIRST_MEMBER'] . '">' . $row['firstPosterName'] . '</a>'
			),
			'poster' => array(
				'id' => $row['ID_MEMBER'],
				'name' => $row['posterName'],
				'href' => empty($row['ID_MEMBER']) ? '' : $scripturl . '?action=profile;u=' . $row['ID_MEMBER'],
				'link' => empty($row['ID_MEMBER']) ? $row['posterName'] : '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['posterName'] . '</a>'
			),
			'message' => $row['body'],
			'can_reply' => false,
			'can_mark_notify' => false,
			'can_delete' => false,
			'delete_possible' => $row['ID_FIRST_MSG'] != $row['ID_MSG'] || $row['ID_LAST_MSG'] == $row['ID_MSG']
		);

		if ($ID_MEMBER == $row['ID_FIRST_MEMBER'])
			$board_ids['own'][$row['ID_BOARD']][] = $row['ID_MSG'];
		$board_ids['any'][$row['ID_BOARD']][] = $row['ID_MSG'];
	}
	mysql_free_result($request);

	// There might be - and are - different permissions between any and own.
	$permissions = array(
		'own' => array(
			'post_reply_own' => 'can_reply',
			'remove_own' => 'can_delete',
		),
		'any' => array(
			'post_reply_any' => 'can_reply',
			'mark_any_notify' => 'can_mark_notify',
			'remove_any' => 'can_delete',
		)
	);

	// Now go through all the permissions, looking for boards they can do it on.
	foreach ($permissions as $type => $list)
		foreach ($list as $permission => $allowed)
		{
			// They can do it on these boards...
			$boards = boardsAllowedTo($permission);

			// If 0 is the only thing in the array, they can do it everywhere!
			if (!empty($boards) && $boards[0] == 0)
				$boards = array_keys($board_ids[$type]);

			// Go through the boards, and look for posts they can do this on.
			foreach ($boards as $board_id)
			{
				// Hmm, they have permission, but there are no topics from that board on this page.
				if (!isset($board_ids[$type][$board_id]))
					continue;

				// Okay, looks like they can do it for these posts.
				foreach ($board_ids[$type][$board_id] as $counter)
					$context['posts'][$counter][$allowed] = true;
			}
		}

	// Some posts - the first posts - can't just be deleted.
	foreach ($context['posts'] as $counter => $dummy)
		$context['posts'][$counter]['can_delete'] &= $context['posts'][$counter]['delete_possible'];
}

// Find unread topics and replies.
function UnreadTopics()
{
	global $board, $txt, $scripturl, $db_prefix, $sourcedir;
	global $ID_MEMBER, $user_info, $context, $modSettings;

	// Guests can't have unread things, we don't know anything about them.
	is_not_guest();

	$context['sub_template'] = $_REQUEST['action'] == 'unread' ? 'unread' : 'replies';
	$context['showing_all_topics'] = isset($_GET['all']);
	if ($_REQUEST['action'] == 'unread')
		$context['page_title'] = $context['showing_all_topics'] ? $txt['unread_topics_all'] : $txt['unread_topics_visit'];
	else
		$context['page_title'] = $txt['unread_replies'];

	$context['linktree'][] = array(
		'url' => $scripturl . '?action=' . $_REQUEST['action'] . ($context['showing_all_topics'] ? ';all' : ''),
		'name' => $context['page_title']
	);

	loadTemplate('Recent');

	$is_topics = $_REQUEST['action'] == 'unread';

	// Are we specifying any specific board?
	if (!empty($board))
		$query_this_board = 'b.ID_BOARD = ' . $board;
	else
	{
		$query_this_board = $user_info['query_see_board'];

		// Don't bother to show deleted posts!
		if (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0)
			$query_this_board .= '
				AND b.ID_BOARD != ' . $modSettings['recycle_board'];
	}

	// This part is the same for each query.
	$select_clause = '
				ms.subject AS firstSubject, ms.posterTime AS firstPosterTime, ms.ID_TOPIC, t.ID_BOARD, b.name AS bname,
				t.numReplies, t.numViews, ms.ID_MEMBER AS ID_FIRST_MEMBER, ml.ID_MEMBER AS ID_LAST_MEMBER,
				ml.posterTime AS lastPosterTime, IFNULL(mems.realName, ms.posterName) AS firstPosterName,
				IFNULL(meml.realName, ml.posterName) AS lastPosterName, ml.subject AS lastSubject,
				ml.icon AS lastIcon, ms.icon AS firstIcon, t.ID_POLL, t.isSticky, t.locked, ml.modifiedTime AS lastModifiedTime,
				IFNULL(lt.logTime, IFNULL(lmr.logTime, 0)) AS isRead, LEFT(ml.body, 384) AS lastBody, LEFT(ms.body, 384) AS firstBody,
				ml.smileysEnabled AS lastSmileys, ms.smileysEnabled AS firstSmileys, t.ID_FIRST_MSG, t.ID_LAST_MSG';

	if ($context['showing_all_topics'] || !$is_topics)
	{
		if (!empty($board))
		{
			$request = db_query("
				SELECT MIN(logTime)
				FROM {$db_prefix}log_mark_read
				WHERE ID_MEMBER = $ID_MEMBER
					AND ID_BOARD = $board", __FILE__, __LINE__);
			list ($earliest_time) = mysql_fetch_row($request);
			mysql_free_result($request);
		}
		else
		{
			$request = db_query("
				SELECT MIN(lmr.logTime)
				FROM {$db_prefix}boards AS b
					LEFT JOIN {$db_prefix}log_mark_read AS lmr ON (lmr.ID_MEMBER = $ID_MEMBER AND lmr.ID_BOARD = b.ID_BOARD)
				WHERE $user_info[query_see_board]", __FILE__, __LINE__);
			list ($earliest_time) = mysql_fetch_row($request);
			mysql_free_result($request);
		}

		$request = db_query("
			SELECT MIN(logTime)
			FROM {$db_prefix}log_topics
			WHERE ID_MEMBER = $ID_MEMBER", __FILE__, __LINE__);
		list ($earliest_time2) = mysql_fetch_row($request);
		mysql_free_result($request);

		if ($earliest_time2 < $earliest_time)
			$earliest_time = (int) $earliest_time2;
		else
			$earliest_time = (int) $earliest_time;
	}

	if ($is_topics)
	{
		$request = db_query("
			SELECT COUNT(DISTINCT t.ID_TOPIC), MIN(t.ID_LAST_MSG)
			FROM {$db_prefix}messages AS ml, {$db_prefix}topics AS t, {$db_prefix}boards AS b
				LEFT JOIN {$db_prefix}log_topics AS lt ON (lt.ID_TOPIC = t.ID_TOPIC AND lt.ID_MEMBER = $ID_MEMBER)
				LEFT JOIN {$db_prefix}log_mark_read AS lmr ON (lmr.ID_BOARD = t.ID_BOARD AND lmr.ID_MEMBER = $ID_MEMBER)
			WHERE b.ID_BOARD = t.ID_BOARD
				AND $query_this_board" . ($context['showing_all_topics'] ? "
				AND ml.posterTime >= $earliest_time" : "
				AND t.ID_LAST_MSG > $_SESSION[ID_MSG_LAST_VISIT]") . "
				AND ml.ID_MSG = t.ID_LAST_MSG
				AND IFNULL(lt.logTime, IFNULL(lmr.logTime, 0)) < ml.posterTime", __FILE__, __LINE__);
		list ($num_topics, $min_message) = mysql_fetch_row($request);
		mysql_free_result($request);

		// Make sure the starting place makes sense and construct the page index.
		$context['page_index'] = constructPageIndex($scripturl . '?action=' . $_REQUEST['action'] . ($context['showing_all_topics'] ? ';all' : ''), $_REQUEST['start'], $num_topics, $modSettings['defaultMaxTopics']);
		$context['current_page'] = (int) $_REQUEST['start'] / $modSettings['defaultMaxTopics'];

		if ($num_topics == 0)
		{
			$context['topics'] = array();
			return;
		}
		else
			$min_message = (int) $min_message;

		$request = db_query("
			SELECT $select_clause
			FROM {$db_prefix}messages AS ms, {$db_prefix}messages AS ml, {$db_prefix}topics AS t, {$db_prefix}boards AS b
				LEFT JOIN {$db_prefix}members AS mems ON (mems.ID_MEMBER = ms.ID_MEMBER)
				LEFT JOIN {$db_prefix}members AS meml ON (meml.ID_MEMBER = ml.ID_MEMBER)
				LEFT JOIN {$db_prefix}log_topics AS lt ON (lt.ID_TOPIC = t.ID_TOPIC AND lt.ID_MEMBER = $ID_MEMBER)
				LEFT JOIN {$db_prefix}log_mark_read AS lmr ON (lmr.ID_BOARD = t.ID_BOARD AND lmr.ID_MEMBER = $ID_MEMBER)
			WHERE t.ID_TOPIC = ms.ID_TOPIC
				AND b.ID_BOARD = t.ID_BOARD
				AND $query_this_board
				AND ms.ID_MSG = t.ID_FIRST_MSG
				AND ml.ID_MSG = t.ID_LAST_MSG
				AND t.ID_LAST_MSG >= $min_message
				AND IFNULL(lt.logTime, IFNULL(lmr.logTime, 0)) < ml.posterTime
			ORDER BY ml.ID_MSG DESC
			LIMIT $_REQUEST[start], $modSettings[defaultMaxTopics]", __FILE__, __LINE__);
	}
	else
	{
		$request = db_query("
			SELECT COUNT(DISTINCT t.ID_TOPIC), MIN(t.ID_LAST_MSG)
			FROM {$db_prefix}topics AS t, {$db_prefix}boards AS b, {$db_prefix}messages AS ml, {$db_prefix}messages AS m
				LEFT JOIN {$db_prefix}log_topics AS lt ON (lt.ID_TOPIC = t.ID_TOPIC AND lt.ID_MEMBER = $ID_MEMBER)
				LEFT JOIN {$db_prefix}log_mark_read AS lmr ON (lmr.ID_BOARD = t.ID_BOARD AND lmr.ID_MEMBER = $ID_MEMBER)
			WHERE t.ID_MEMBER_UPDATED != $ID_MEMBER
				AND m.ID_TOPIC = t.ID_TOPIC
				AND m.ID_MEMBER = $ID_MEMBER
				AND ml.ID_MSG = t.ID_LAST_MSG
				AND b.ID_BOARD = t.ID_BOARD
				AND $query_this_board
				AND ml.posterTime >= $earliest_time
				AND IFNULL(lt.logTime, IFNULL(lmr.logTime, 0)) < ml.posterTime", __FILE__, __LINE__);
		list ($num_topics, $min_message) = mysql_fetch_row($request);
		mysql_free_result($request);

		// Make sure the starting place makes sense and construct the page index.
		$context['page_index'] = constructPageIndex($scripturl . '?action=' . $_REQUEST['action'], $_REQUEST['start'], $num_topics, $modSettings['defaultMaxTopics']);
		$context['current_page'] = (int) $_REQUEST['start'] / $modSettings['defaultMaxTopics'];

		if ($num_topics == 0)
		{
			$context['topics'] = array();
			return;
		}
		else
			$min_message = (int) $min_message;

		$request = db_query("
			SELECT DISTINCT t.ID_TOPIC
			FROM {$db_prefix}topics AS t, {$db_prefix}boards AS b, {$db_prefix}messages AS ml, {$db_prefix}messages AS m
				LEFT JOIN {$db_prefix}log_topics AS lt ON (lt.ID_TOPIC = t.ID_TOPIC AND lt.ID_MEMBER = $ID_MEMBER)
				LEFT JOIN {$db_prefix}log_mark_read AS lmr ON (lmr.ID_BOARD = b.ID_BOARD AND lmr.ID_MEMBER = $ID_MEMBER)
			WHERE ml.ID_MEMBER != $ID_MEMBER
				AND m.ID_TOPIC = t.ID_TOPIC
				AND m.ID_MEMBER = $ID_MEMBER
				AND ml.ID_MSG = t.ID_LAST_MSG
				AND b.ID_BOARD = t.ID_BOARD
				AND $query_this_board
				AND t.ID_LAST_MSG >= $min_message
				AND IFNULL(lt.logTime, IFNULL(lmr.logTime, 0)) < ml.posterTime
			ORDER BY ml.ID_MSG DESC
			LIMIT $_REQUEST[start], $modSettings[defaultMaxTopics]", __FILE__, __LINE__);
		$topics = array();
		while ($row = mysql_fetch_assoc($request))
			$topics[] = $row['ID_TOPIC'];
		mysql_free_result($request);

		// Sanity... where have you gone?
		if (empty($topics))
		{
			$context['topics'] = array();
			return;
		}

		$request = db_query("
			SELECT $select_clause
			FROM {$db_prefix}messages AS ms, {$db_prefix}messages AS ml, {$db_prefix}topics AS t, {$db_prefix}boards AS b
				LEFT JOIN {$db_prefix}members AS mems ON (mems.ID_MEMBER = ms.ID_MEMBER)
				LEFT JOIN {$db_prefix}members AS meml ON (meml.ID_MEMBER = ml.ID_MEMBER)
				LEFT JOIN {$db_prefix}log_topics AS lt ON (lt.ID_TOPIC = t.ID_TOPIC AND lt.ID_MEMBER = $ID_MEMBER)
				LEFT JOIN {$db_prefix}log_mark_read AS lmr ON (lmr.ID_BOARD = t.ID_BOARD AND lmr.ID_MEMBER = $ID_MEMBER)
			WHERE t.ID_TOPIC IN (" . implode(', ', $topics) . ")
				AND t.ID_TOPIC = ms.ID_TOPIC
				AND b.ID_BOARD = t.ID_BOARD
				AND ms.ID_MSG = t.ID_FIRST_MSG
				AND ml.ID_MSG = t.ID_LAST_MSG
			ORDER BY ml.ID_MSG DESC
			LIMIT " . count($topics), __FILE__, __LINE__);
	}

	$context['topics'] = array();
	$topic_ids = array();
	while ($row = mysql_fetch_assoc($request))
	{
		if ($row['ID_POLL'] > 0 && $modSettings['pollMode'] == '0')
			continue;

		$topic_ids[] = $row['ID_TOPIC'];

		// Clip the strings first because censoring is slow :/. (for some reason?)
		$row['firstBody'] = strip_tags(strtr(doUBBC($row['firstBody'], $row['firstSmileys']), array('<br />' => '&#10;')));
		if (strlen($row['firstBody']) > 128)
			$row['firstBody'] = substr($row['firstBody'], 0, 128) . '...';
		$row['lastBody'] = strip_tags(strtr(doUBBC($row['lastBody'], $row['lastSmileys']), array('<br />' => '&#10;')));
		if (strlen($row['lastBody']) > 128)
			$row['lastBody'] = substr($row['lastBody'], 0, 128) . '...';

		// Do a bit of censoring...
		censorText($row['firstSubject']);
		censorText($row['firstBody']);

		// But don't do it twice, it can be a slow ordeal!
		if ($row['ID_FIRST_MSG'] == $row['ID_LAST_MSG'])
		{
			$row['lastSubject'] = $row['firstSubject'];
			$row['lastBody'] = $row['firstBody'];
		}
		else
		{
			censorText($row['lastSubject']);
			censorText($row['lastBody']);
		}

		// Decide how many pages the topic should have.
		$topic_length = $row['numReplies'] + 1;
		if ($topic_length > $modSettings['defaultMaxMessages'])
		{
			$tmppages = array();
			$tmpa = 1;
			for ($tmpb = 0; $tmpb < $topic_length; $tmpb += $modSettings['defaultMaxMessages'])
			{
				$tmppages[] = '<a href="' . $scripturl . '?topic=' . $row['ID_TOPIC'] . '.' . $tmpb . ';topicseen">' . $tmpa . '</a>';
				$tmpa++;
			}
			// Show links to all the pages?
			if (count($tmppages) <= 5)
				$pages = '&#171; ' . implode(' ', $tmppages);
			// Or skip a few?
			else
				$pages = '&#171; ' . $tmppages[0] . ' ' . $tmppages[1] . ' ... ' . $tmppages[count($tmppages) - 2] . ' ' . $tmppages[count($tmppages) - 1];

			if (!empty($modSettings['enableAllMessages']) && $topic_length < $modSettings['enableAllMessages'])
				$pages .= ' &nbsp;<a href="' . $scripturl . '?topic=' . $row['ID_TOPIC'] . '.0;all">' . $txt[190] . '</a>';
			$pages .= ' &#187;';
		}
		else
			$pages = '';

		// And build the array.
		$context['topics'][$row['ID_TOPIC']] = array(
			'id' => $row['ID_TOPIC'],
			'first_post' => array(
				'member' => array(
					'name' => $row['firstPosterName'],
					'id' => $row['ID_FIRST_MEMBER'],
					'href' => $scripturl . '?action=profile;u=' . $row['ID_FIRST_MEMBER'],
					'link' => !empty($row['ID_FIRST_MEMBER']) ? '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_FIRST_MEMBER'] . '" title="' . $txt[92] . ' ' . $row['firstPosterName'] . '">' . $row['firstPosterName'] . '</a>' : $row['firstPosterName']
				),
				'time' => timeformat($row['firstPosterTime']),
				'timestamp' => $row['firstPosterTime'],
				'subject' => $row['firstSubject'],
				'preview' => $row['firstBody'],
				'icon' => $row['firstIcon'],
				'href' => $scripturl . '?topic=' . $row['ID_TOPIC'] . '.0;topicseen',
				'link' => '<a href="' . $scripturl . '?topic=' . $row['ID_TOPIC'] . '.0;topicseen">' . $row['firstSubject'] . '</a>'
			),
			'last_post' => array(
				'member' => array(
					'name' => $row['lastPosterName'],
					'id' => $row['ID_LAST_MEMBER'],
					'href' => $scripturl . '?action=profile;u=' . $row['ID_LAST_MEMBER'],
					'link' => !empty($row['ID_LAST_MEMBER']) ? '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_LAST_MEMBER'] . '">' . $row['lastPosterName'] . '</a>' : $row['lastPosterName']
				),
				'time' => timeformat($row['lastPosterTime']),
				'timestamp' => $row['lastPosterTime'],
				'subject' => $row['lastSubject'],
				'preview' => $row['lastBody'],
				'icon' => $row['lastIcon'],
				'href' => $scripturl . '?topic=' . $row['ID_TOPIC'] . ($row['numReplies'] == 0 ? '.0' : '.msg' . $row['ID_LAST_MSG']) . ';topicseen#msg' . $row['ID_LAST_MSG'],
				'link' => '<a href="' . $scripturl . '?topic=' . $row['ID_TOPIC'] . ($row['numReplies'] == 0 ? '.0' : '.msg' . $row['ID_LAST_MSG']) . ';topicseen#msg' . $row['ID_LAST_MSG'] . '">' . $row['lastSubject'] . '</a>'
			),
			'newtime' => $row['isRead'],
			'new_href' => $scripturl . '?topic=' . $row['ID_TOPIC'] . '.from' . $row['isRead'] . ';topicseen#new',
			'href' => $scripturl . '?topic=' . $row['ID_TOPIC'] . ($row['numReplies'] == 0 ? '.0' : '.from' . $row['isRead']) . ';topicseen#new',
			'link' => '<a href="' . $scripturl . '?topic=' . $row['ID_TOPIC'] . ($row['numReplies'] == 0 ? '.0' : '.from' . $row['isRead']) . ';topicseen#new">' . $row['firstSubject'] . '</a>',
			'is_sticky' => !empty($modSettings['enableStickyTopics']) && !empty($row['isSticky']),
			'is_locked' => !empty($row['locked']),
			'is_poll' => $modSettings['pollMode'] == '1' && $row['ID_POLL'] > 0,
			'is_hot' => $row['numReplies'] >= $modSettings['hotTopicPosts'],
			'is_very_hot' => $row['numReplies'] >= $modSettings['hotTopicVeryPosts'],
			'is_posted_in' => false,
			'icon' => $row['firstIcon'],
			'subject' => $row['firstSubject'],
			'pages' => $pages,
			'replies' => $row['numReplies'],
			'views' => $row['numViews'],
			'board' => array(
				'id' => $row['ID_BOARD'],
				'name' => $row['bname'],
				'href' => $scripturl . '?board=' . $row['ID_BOARD'] . '.0',
				'link' => '<a href="' . $scripturl . '?board=' . $row['ID_BOARD'] . '.0">' . $row['bname'] . '</a>'
			)
		);

		determineTopicClass($context['topics'][$row['ID_TOPIC']]);
	}
	mysql_free_result($request);

	if ($is_topics && !empty($modSettings['enableParticipation']) && !empty($topic_ids))
	{
		$result = db_query("
			SELECT ID_TOPIC
			FROM {$db_prefix}messages
			WHERE ID_TOPIC IN (" . implode(', ', $topic_ids) . ")
				AND ID_MEMBER = $ID_MEMBER", __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($result))
		{
			if (empty($context['topics'][$row['ID_TOPIC']]['is_posted_in']))
			{
				$context['topics'][$row['ID_TOPIC']]['is_posted_in'] = true;
				$context['topics'][$row['ID_TOPIC']]['class'] = 'my_' . $context['topics'][$row['ID_TOPIC']]['class'];
			}
		}
		mysql_free_result($result);
	}

	$context['topics_to_mark'] = implode('-', $topic_ids);
}

?>