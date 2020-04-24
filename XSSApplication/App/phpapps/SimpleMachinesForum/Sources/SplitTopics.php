<?php
/******************************************************************************
* SplitTopics.php                                                             *
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
/* Original module by Mach8 - We'll never forget you.                        */
/*****************************************************************************/
if (!defined('SMF'))
	die('Hacking attempt...');

/*	This file handles merging and splitting topics...
*/

// Split a topic into two separate topics... in case it got offtopic, etc.
function SplitTopics()
{
	global $topic, $sourcedir;

	// And... which topic were you splitting, again?
	if (empty($topic))
		fatal_lang_error(337, false);

	// Are you allowed to split topics?
	isAllowedTo('split_any');

	// Load up the "dependencies" - the template, getMsgMemberID(), and sendNotifications().
	loadTemplate('SplitTopics');
	require_once($sourcedir . '/Subs-Boards.php');
	require_once($sourcedir . '/Subs-Post.php');

	// ?action=splittopics;sa=LETSBREAKIT won't work, sorry.
	if (!isset($_REQUEST['sa']) || !is_numeric($_REQUEST['sa']) || $_REQUEST['sa'] < 1 || $_REQUEST['sa'] > 4)
		$subAction = 'SplitTopics1';
	else
		$subAction = 'SplitTopics' . (int) $_REQUEST['sa'];

	$subAction();
}

// Part 1: General stuff.
function SplitTopics1()
{
	global $txt, $topic, $db_prefix, $context;

	// Validate "at".
	if (empty($_GET['at']))
		fatal_lang_error(337, false);
	$_GET['at'] = (int) $_GET['at'];

	// Retrieve the subject and stuff of the specific topic/message.
	$request = db_query("
		SELECT m.subject, t.numReplies, t.ID_FIRST_MSG
		FROM {$db_prefix}messages AS m, {$db_prefix}topics AS t
		WHERE m.ID_MSG = $_GET[at]
			AND m.ID_TOPIC = $topic
			AND t.ID_TOPIC = $topic
		LIMIT 1", __FILE__, __LINE__);
	if (mysql_num_rows($request) == 0)
		fatal_lang_error('smf272');
	list ($_REQUEST['subname'], $numReplies, $ID_FIRST_MSG) = mysql_fetch_row($request);
	mysql_free_result($request);

	// Check if there is more than one message in the topic.  (there should be.)
	if ($numReplies < 1)
		fatal_lang_error('smf270', false);

	// Check if this is the first message in the topic (if so, the first and second option won't be available)
	if ($ID_FIRST_MSG == $_GET['at'])
		return SplitTopics3();

	// Basic template information....
	$context['message'] = array(
		'id' => $_GET['at'],
		'subject' => $_REQUEST['subname']
	);
	$context['sub_template'] = 'ask';
	$context['page_title'] = $txt['smf251'];
}

// Alright, you've decided what you want to do with it.... now to do it.
function SplitTopics2()
{
	global $txt, $board, $topic, $db_prefix, $context, $ID_MEMBER;

	// Check the session to make sure they meant to do this.
	checkSession();

	// They blanked the subject name.
	if (!isset($_POST['subname']) || $_POST['subname'] == '')
		$_POST['subname'] = $txt['smf258'];

	// Redirect to the selector if they chose selective.
	if ($_POST['step2'] == 'selective')
	{
		$_REQUEST['subname'] = $_POST['subname'];
		return SplitTopics3();
	}

	// The old topic...
	$split1_ID_TOPIC = $topic;

	/// The new first message and member.
	$split2_firstMsg = $_POST['at'];
	$split2_firstMem = getMsgMemberID($split2_firstMsg);

	// Find the old first, original last, and original number of replies.
	$tresult = db_query("
		SELECT MIN(m.ID_MSG) AS myID_FIRST_MSG, MAX(m.ID_MSG) AS myID_LAST_MSG, COUNT(m.ID_MSG) - 1 AS myNumReplies, t.isSticky
		FROM {$db_prefix}topics AS t, {$db_prefix}messages AS m
		WHERE m.ID_TOPIC = $split1_ID_TOPIC
			AND t.ID_TOPIC = $split1_ID_TOPIC
		GROUP BY t.ID_TOPIC", __FILE__, __LINE__);
	list ($split1_firstMsg, $orig_lastMsg, $orig_replies, $isSticky) = mysql_fetch_row($tresult);
	mysql_free_result($tresult);
	$split1_firstMem = getMsgMemberID($split1_firstMsg);
	$orig_lastMem = getMsgMemberID($orig_lastMsg);

	// If you're not trying to do a selective on the first message.. what are you doing?
	if ($_POST['at'] == $split1_firstMsg)
		fatal_lang_error('smf268');

	// If you are splitting all those after, or "onlythis" and this is the last post...
	if ($_POST['step2'] == 'afterthis' || $orig_lastMsg == $split2_firstMsg)
	{
		// By logic, the new last message/member is the original last message/member.
		$split2_lastMsg = $orig_lastMsg;
		$split2_lastMem = $orig_lastMem;

		// Find the last message and member of the old topic.
		$request = db_query("
			SELECT m.ID_MSG, IFNULL(mem.ID_MEMBER, 0) AS myID_MEMBER
			FROM {$db_prefix}messages AS m
				LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = m.ID_MEMBER)
			WHERE ID_TOPIC = $split1_ID_TOPIC
				AND ID_MSG < $_POST[at]
			ORDER BY ID_MSG DESC
			LIMIT 1", __FILE__, __LINE__);
		list ($split1_lastMsg, $split1_lastMem) = mysql_fetch_row($request);
		mysql_free_result($request);

		// Find the number of replies for the new topic - thus finding the old as well.
		$request = db_query("
			SELECT COUNT(ID_MSG) - 1 AS numReplies
			FROM {$db_prefix}messages
			WHERE ID_TOPIC = $split1_ID_TOPIC
				AND ID_MSG >= $_POST[at]", __FILE__, __LINE__);
		list ($split2_replies) = mysql_fetch_row($request);
		mysql_free_result($request);
		$split1_replies = $orig_replies - $split2_replies - 1;
	}
	elseif ($_POST['step2'] == 'onlythis')
	{
		// If you're only splitting off this message, things are simple.
		$split1_lastMsg = $orig_lastMsg;
		$split1_lastMem = $orig_lastMem;
		// Only one message - no replies.
		$split2_lastMsg = $split2_firstMsg;
		$split2_lastMem = $split2_firstMem;
		$split2_replies = 0;
		$split1_replies = $orig_replies - 1;
	}
	// There's another action?!
	else
		fatal_lang_error(1, false);

	// No db changes yet, so let's double check to see if something got messed up.
	if ($split1_firstMsg <= 0 || $split1_lastMsg <= 0 || $split2_firstMsg <= 0 || $split2_lastMsg <= 0 || $split1_replies < 0 || $split2_replies < 0)
		fatal_lang_error('smf272');

	// Make the new topic, but use 0 for the last and first ID_MSG to avoid UNIQUE errors. (fix 'em at the end!)
	db_query("
		INSERT INTO {$db_prefix}topics
			(ID_BOARD, ID_MEMBER_STARTED, ID_MEMBER_UPDATED, ID_FIRST_MSG, ID_LAST_MSG, numReplies, isSticky)
		VALUES ($board, $split2_firstMem, $split2_firstMem, 0, 0, $split2_replies, $isSticky)", __FILE__, __LINE__);
	$split2_ID_TOPIC = db_insert_id();
	if ($split2_ID_TOPIC <= 0)
		fatal_lang_error('smf273');

	// 'Move' the messages.
	db_query("
		UPDATE {$db_prefix}messages
		SET ID_TOPIC = $split2_ID_TOPIC
		WHERE ID_TOPIC = $split1_ID_TOPIC
			AND (ID_MSG BETWEEN $split2_firstMsg AND $split2_lastMsg)
		LIMIT " . ($split2_lastMsg - $split2_firstMsg + 1), __FILE__, __LINE__);
	// Set the subject of the first message of the new topic.
	db_query("
		UPDATE {$db_prefix}messages
		SET subject = '" . preg_replace('~&amp;#(\d{4,5}|[3-9]\d{2,4}|2[6-9]\d);~', '&#$1;', htmlspecialchars($_POST['subname'])) . "'
		WHERE ID_MSG = $_POST[at]
		LIMIT 1", __FILE__, __LINE__);

	// Mess with the new first message, etc.
	db_query("
		UPDATE {$db_prefix}topics
		SET ID_FIRST_MSG = $split1_firstMsg, ID_LAST_MSG = $split1_lastMsg, numReplies = $split1_replies,
			ID_MEMBER_STARTED = $split1_firstMem, ID_MEMBER_UPDATED = $split1_lastMem
		WHERE ID_TOPIC = $split1_ID_TOPIC
		LIMIT 1", __FILE__, __LINE__);

	// Now, put the first/last message back to what they should be.
	db_query("
		UPDATE {$db_prefix}topics
		SET ID_FIRST_MSG = $split2_firstMsg, ID_LAST_MSG = $split2_lastMsg
		WHERE ID_TOPIC = $split2_ID_TOPIC
		LIMIT 1", __FILE__, __LINE__);

	// The board has more topics now.
	db_query("
		UPDATE {$db_prefix}boards
		SET numTopics = numTopics + 1
		WHERE ID_BOARD = $board
		LIMIT 1", __FILE__, __LINE__);

	// The user has to have read it, hopefully, because they split it!
	db_query("
		REPLACE INTO {$db_prefix}log_topics
			(logTime, ID_MEMBER, ID_TOPIC)
		VALUES (" . time() . ", $ID_MEMBER, $split2_ID_TOPIC)", __FILE__, __LINE__);

	// Clean up.
	updateStats('topic');
	updateLastMessages($board);

	$context['old_topic'] = $split1_ID_TOPIC;
	$context['new_topic'] = $split2_ID_TOPIC;
	$context['page_title'] = $txt['smf251'];

	logAction('split', array('topic' => $topic, 'new_topic' => $split2_ID_TOPIC));
	// Notify people that this topic has been split?
	sendNotifications($topic, 'split');
}

// Get a selective list of topics...
function SplitTopics3()
{
	global $txt, $scripturl, $topic, $db_prefix, $context, $modSettings;

	$context['page_title'] = $txt['smf251'] . ' - ' . $txt['smf257'];

	// Some stuff for our favorite template.
	$context['new_subject'] = stripslashes($_REQUEST['subname']);

	// Get the maximum number of messages.
	$result = db_query("
		SELECT COUNT(ID_MSG)
		FROM {$db_prefix}messages
		WHERE ID_TOPIC = $topic", __FILE__, __LINE__);
	list ($maxmessages) = mysql_fetch_row($result);
	mysql_free_result($result);

	// Build a page list.
	$context['page_index'] = constructPageIndex($scripturl . '?action=splittopics;sa=3;subname=' . urlencode($_REQUEST['subname']) . ';topic=' . $topic, $_REQUEST['start'], $maxmessages, $modSettings['defaultMaxMessages'], true);

	// Get the messages and stick them into an array.
	$tresult = db_query("
		SELECT m.subject, IFNULL(mem.realName, m.posterName) AS realName, m.body, m.ID_MSG
		FROM {$db_prefix}messages AS m
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = m.ID_MEMBER)
		WHERE m.ID_TOPIC = $topic
		ORDER BY m.ID_MSG DESC
		LIMIT $_REQUEST[start], $modSettings[defaultMaxMessages]", __FILE__, __LINE__);
	$context['messages'] = array();
	while ($row = mysql_fetch_assoc($tresult))
	{
		$row['body'] = doUBBC($row['body']);

		$context['messages'][] = array(
			'subject' => $row['subject'],
			'body' => $row['body'],
			'id' => $row['ID_MSG'],
			'poster' => $row['realName']
		);
	}
	mysql_free_result($tresult);

	// Using the "select" sub template.
	$context['sub_template'] = 'select';
}

// Actually and selectively split the topics out.
function SplitTopics4()
{
	global $txt, $board, $topic, $db_prefix, $context, $ID_MEMBER;

	// Make sure the session id was passed with post.
	checkSession();

	// Default the subject in case it's blank.
	if (!isset($_POST['subname']) || $_POST['subname'] == '')
		$_POST['subname'] = $txt['smf258'];

	// The old topic's ID is the current one.
	$split1_ID_TOPIC = $topic;

	// You must've selected some messages!  Can't split out none!
	if (empty($_POST['selpost']))
		fatal_lang_error('smf271', false);

	// No sense in imploding it over and over again.
	$postList = implode(',', array_keys($_POST['selpost']));

	// Find the new first and last not in the list. (old topic)
	$result = db_query("
		SELECT MIN(m.ID_MSG) AS myID_FIRST_MSG, MAX(m.ID_MSG) AS myID_LAST_MSG, COUNT(m.ID_MSG) - 1 AS myNumReplies, t.isSticky
		FROM {$db_prefix}messages AS m, {$db_prefix}topics AS t
		WHERE m.ID_MSG NOT IN ($postList)
			AND m.ID_TOPIC = $split1_ID_TOPIC
			AND t.ID_TOPIC = $split1_ID_TOPIC
		GROUP BY m.ID_TOPIC
		LIMIT 1", __FILE__, __LINE__);
	// You can't select ALL the messages!
	if (mysql_num_rows($result) == 0)
		fatal_lang_error('smf271b', false);
	list ($split1_firstMsg, $split1_lastMsg, $split1_replies, $isSticky) = mysql_fetch_row($result);
	mysql_free_result($result);
	$split1_firstMem = getMsgMemberID($split1_firstMsg);
	$split1_lastMem = getMsgMemberID($split1_lastMsg);

	// Find the first and last in the list. (new topic)
	$result = db_query("
		SELECT MIN(ID_MSG) AS myID_FIRST_MSG, MAX(ID_MSG) AS myID_LAST_MSG, COUNT(ID_MSG) - 1 AS myNumReplies
		FROM {$db_prefix}messages
		WHERE ID_MSG IN ($postList)
			AND ID_TOPIC = $split1_ID_TOPIC
		GROUP BY ID_TOPIC
		LIMIT 1", __FILE__, __LINE__);
	list ($split2_firstMsg, $split2_lastMsg, $split2_replies) = mysql_fetch_row($result);
	mysql_free_result($result);
	$split2_firstMem = getMsgMemberID($split2_firstMsg);
	$split2_lastMem = getMsgMemberID($split2_lastMsg);

	// No database changes yet, so let's double check to see if everything makes at least a little sense.
	if ($split1_firstMsg <= 0 || $split1_lastMsg <= 0 || $split2_firstMsg <= 0 || $split2_lastMsg <= 0 || $split1_replies < 0 || $split2_replies < 0)
		fatal_lang_error('smf272');

	// We're off to insert the new topic!  Use 0 for now to avoid UNIQUE errors.
	db_query("
		INSERT INTO {$db_prefix}topics
			(ID_BOARD, ID_MEMBER_STARTED, ID_MEMBER_UPDATED, ID_FIRST_MSG, ID_LAST_MSG, numReplies, isSticky)
		VALUES ($board, $split2_firstMem, $split2_lastMem, 0, 0, $split2_replies, $isSticky)", __FILE__, __LINE__);
	$split2_ID_TOPIC = db_insert_id();
	if ($split2_ID_TOPIC <= 0)
		fatal_lang_error('smf273');

	// Move the messages over to the other topic.
	db_query("
		UPDATE {$db_prefix}messages
		SET ID_TOPIC = $split2_ID_TOPIC, subject = '" . preg_replace('~&amp;#(\d{4,5}|[3-9]\d{2,4}|2[6-9]\d);~', '&#$1;', htmlspecialchars($_POST['subname'])) . "'
		WHERE ID_MSG IN ($postList)
		LIMIT " . count($_POST['selpost']), __FILE__, __LINE__);

	// Mess with the old topic's first, last, and number of messages.
	db_query("
		UPDATE {$db_prefix}topics
		SET numReplies = $split1_replies, ID_FIRST_MSG = $split1_firstMsg, ID_LAST_MSG = $split1_lastMsg,
			ID_MEMBER_STARTED = $split1_firstMem, ID_MEMBER_UPDATED = $split1_lastMem
		WHERE ID_TOPIC = $split1_ID_TOPIC
		LIMIT 1", __FILE__, __LINE__);

	// Now, put the first/last message back to what they should be.
	db_query("
		UPDATE {$db_prefix}topics
		SET ID_FIRST_MSG = $split2_firstMsg, ID_LAST_MSG = $split2_lastMsg
		WHERE ID_TOPIC = $split2_ID_TOPIC
		LIMIT 1", __FILE__, __LINE__);

	// The board has more topics now.
	db_query("
		UPDATE {$db_prefix}boards
		SET numTopics = numTopics + 1
		WHERE ID_BOARD = $board
		LIMIT 1", __FILE__, __LINE__);

	// The user has to have read it, hopefully, because they split it!
	db_query("
		REPLACE INTO {$db_prefix}log_topics
			(logTime, ID_MEMBER, ID_TOPIC)
		VALUES (" . time() . ", $ID_MEMBER, $split2_ID_TOPIC)", __FILE__, __LINE__);

	// Housekeeping.
	updateStats('topic');
	updateLastMessages($board);

	$context['old_topic'] = $split1_ID_TOPIC;
	$context['new_topic'] = $split2_ID_TOPIC;
	$context['page_title'] = $txt['smf251'];

	logAction('split', array('topic' => $topic, 'new_topic' => $split2_ID_TOPIC));
	// Notify people that this topic has been split?
	sendNotifications($topic, 'split');
}

// Merge two topics into one topic... useful if they have the same basic subject.
function MergeTopics()
{
	// Load the template....
	loadTemplate('SplitTopics');

	// ?action=splittopics;sa=LETSBREAKIT won't work, sorry.
	if (!isset($_REQUEST['sa']) || !is_numeric($_REQUEST['sa']) || $_REQUEST['sa'] < 1 || $_REQUEST['sa'] > 4)
		$subAction = 'MergeTopics1';
	else
		$subAction = 'MergeTopics' . (int) $_REQUEST['sa'];

	$subAction();
}

// Merge two topics together.
function MergeTopics1()
{
	global $txt, $board, $context;
	global $scripturl, $topic, $db_prefix, $user_info, $modSettings;

	$_REQUEST['targetboard'] = isset($_REQUEST['targetboard']) ? (int) $_REQUEST['targetboard'] : $board;
	$context['target_board'] = $_REQUEST['targetboard'];

	// How many topics are on this board?  (used for paging.)
	$request = db_query("
		SELECT COUNT(ID_TOPIC)
		FROM {$db_prefix}topics
		WHERE ID_BOARD = $_REQUEST[targetboard]", __FILE__, __LINE__);
	list ($topiccount) = mysql_fetch_row($request);
	mysql_free_result($request);

	// Make the page list.
	$context['page_index'] = constructPageIndex($scripturl . '?action=mergetopics;from=' . $_GET['from'] . ';targetboard=' . $_REQUEST['targetboard'] . ';board=' . $board, $_REQUEST['start'], $topiccount, $modSettings['defaultMaxTopics'], true);

	// Get the topic's subject.
	$request = db_query("
		SELECT m.subject
		FROM {$db_prefix}messages AS m, {$db_prefix}topics AS t
		WHERE m.ID_MSG = t.ID_FIRST_MSG
			AND t.ID_TOPIC = $_GET[from]
			AND t.ID_BOARD = $board
		LIMIT 1", __FILE__, __LINE__);
	if (mysql_num_rows($request) == 0)
		fatal_lang_error('smf232');
	list ($subject) = mysql_fetch_row($request);
	mysql_free_result($request);

	// Tell the template a few things..
	$context['origin_topic'] = $_GET['from'];
	$context['origin_subject'] = $subject;
	$context['origin_js_subject'] = addcslashes(addslashes($subject), '/');
	$context['page_title'] = $txt['smf252'];

	// Check which boards you have merge permissions on.
	$merge_boards = boardsAllowedTo('merge_any');

	if (empty($merge_boards))
		fatal_lang_error('cannot_merge_any');

	// Get a list of boards they can navigate to to merge.
	$request = db_query("
		SELECT b.ID_BOARD, b.name AS bName, c.name AS cName
		FROM {$db_prefix}boards AS b, {$db_prefix}categories AS c
		WHERE b.ID_CAT = c.ID_CAT
			AND $user_info[query_see_board]" . (!in_array(0, $merge_boards) ? "
			AND b.ID_BOARD IN (" . implode(', ', $merge_boards) . ")" : '') . "
		ORDER BY c.catOrder, b.boardOrder", __FILE__, __LINE__);
	$context['boards'] = array();
	while ($row = mysql_fetch_assoc($request))
		$context['boards'][] = array(
			'id' => $row['ID_BOARD'],
			'name' => $row['bName'],
			'category' => $row['cName']
		);
	mysql_free_result($request);

	// Get some topics to merge it with.
	$request = db_query("
		SELECT t.ID_TOPIC, m.subject, m.ID_MEMBER, IFNULL(mem.realName, m.posterName) AS posterName
		FROM {$db_prefix}topics AS t, {$db_prefix}messages AS m, {$db_prefix}messages AS m2
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = m.ID_MEMBER)
		WHERE m.ID_MSG = t.ID_FIRST_MSG
			AND m2.ID_MSG = t.ID_LAST_MSG
			AND t.ID_BOARD = $_REQUEST[targetboard]
			AND t.ID_TOPIC != $_GET[from]
		ORDER BY " . (!empty($modSettings['enableStickyTopics']) ? 't.isSticky DESC, ' : '') . "m2.posterTime DESC
		LIMIT $_REQUEST[start], $modSettings[defaultMaxTopics]", __FILE__, __LINE__);
	$context['topics'] = array();
	while ($row = mysql_fetch_assoc($request))
	{
		censorText($row['subject']);

		$context['topics'][] = array(
			'id' => $row['ID_TOPIC'],
			'poster' => array(
				'id' => $row['ID_MEMBER'],
				'name' => $row['posterName'],
				'href' => empty($row['ID_MEMBER']) ? '' : $scripturl . '?action=profile;u=' . $row['ID_MEMBER'],
				'link' => empty($row['ID_MEMBER']) ? $row['posterName'] : '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '" target="_blank">' . $row['posterName'] . '</a>'
			),
			'subject' => $row['subject'],
			'js_subject' => addcslashes(addslashes($row['subject']), '/')
		);
	}

	$context['sub_template'] = 'merge';
}

// Now that the topic IDs are known, do the proper merging.
function MergeTopics2($topics = array())
{
	global $db_prefix, $user_info, $txt, $context, $scripturl, $sourcedir;

	// The parameters of MergeTopics2 were set, so this must've been an internal call.
	if (!empty($topics))
	{
		isAllowedTo('merge_any');
		loadTemplate('SplitTopics');
	}

	// Handle URLs from MergeTopics1.
	if (!empty($_GET['from']) && !empty($_GET['to']))
		$topics = array($_GET['from'], $_GET['to']);

	// If we came from a form, the topic IDs came by post.
	if (!empty($_POST['topics']) && is_array($_POST['topics']))
		$topics = $_POST['topics'];

	// There's nothing to merge with just one topic...
	if (empty($topics) || !is_array($topics) || count($topics) == 1)
		fatal_lang_error('merge_need_more_topics');

	// Make sure every topic is numeric, or some nasty things could be done with the DB.
	foreach ($topics as $id => $topic)
		$topics[$id] = (int) $topic;

	// Get info about the topics and polls that will be merged.
	$request = db_query("
		SELECT
			t.ID_TOPIC, t.ID_BOARD, t.ID_POLL, t.numViews, t.isSticky,
			m1.subject, m1.posterTime AS time_started, IFNULL(mem1.ID_MEMBER, 0) AS ID_MEMBER_STARTED, IFNULL(mem1.realName, m1.posterName) AS name_started,
			m2.posterTime AS time_updated, IFNULL(mem2.ID_MEMBER, 0) AS ID_MEMBER_UPDATED, IFNULL(mem2.realName, m2.posterName) AS name_updated
		FROM {$db_prefix}topics AS t, {$db_prefix}messages AS m1, {$db_prefix}messages AS m2
			LEFT JOIN {$db_prefix}members AS mem1 ON (mem1.ID_MEMBER = m1.ID_MEMBER)
			LEFT JOIN {$db_prefix}members AS mem2 ON (mem2.ID_MEMBER = m2.ID_MEMBER)
		WHERE t.ID_TOPIC IN (" . implode(', ', $topics) . ")
			AND m1.ID_MSG = t.ID_FIRST_MSG
			AND m2.ID_MSG = t.ID_LAST_MSG
		ORDER BY t.ID_FIRST_MSG
		LIMIT " . count($topics), __FILE__, __LINE__);
	if (mysql_num_rows($request) < 2)
		fatal_lang_error('smf263');
	$num_views = 0;
	$isSticky = 0;
	$boards = array();
	$polls = array();
	while ($row = mysql_fetch_assoc($request))
	{
		$topic_data[$row['ID_TOPIC']] = array(
			'id' => $row['ID_TOPIC'],
			'board' => $row['ID_BOARD'],
			'poll' => $row['ID_POLL'],
			'numViews' => $row['numViews'],
			'subject' => $row['subject'],
			'started' => array(
				'time' => timeformat($row['time_started']),
				'timestamp' => $row['time_started'],
				'href' => empty($row['ID_MEMBER_STARTED']) ? '' : $scripturl . '?action=profile;u=' . $row['ID_MEMBER_STARTED'],
				'link' => empty($row['ID_MEMBER_STARTED']) ? $row['name_started'] : '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER_STARTED'] . '">' . $row['name_started'] . '</a>'
			),
			'updated' => array(
				'time' => timeformat($row['time_updated']),
				'timestamp' => $row['time_updated'],
				'href' => empty($row['ID_MEMBER_UPDATED']) ? '' : $scripturl . '?action=profile;u=' . $row['ID_MEMBER_UPDATED'],
				'link' => empty($row['ID_MEMBER_UPDATED']) ? $row['name_updated'] : '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER_UPDATED'] . '">' . $row['name_updated'] . '</a>'
			)
		);
		$num_views += $row['numViews'];
		$boards[] = $row['ID_BOARD'];
		// If there's no poll, ID_POLL == 0.
		if ($row['ID_POLL'] > 0)
			$polls[] = $row['ID_POLL'];
		// Store the ID_TOPIC with the lowest ID_FIRST_MSG.
		if (empty($firstTopic))
			$firstTopic = $row['ID_TOPIC'];

		$isSticky = max($isSticky, $row['isSticky']);
	}
	mysql_free_result($request);

	$boards = array_values(array_unique($boards));

	// Get the boards a user is allowed to merge in.
	$merge_boards = boardsAllowedTo('merge_any');
	if (empty($merge_boards))
		fatal_lang_error('cannot_merge_any');

	// Make sure they can see all boards....
	$request = db_query("
		SELECT b.ID_BOARD
		FROM {$db_prefix}boards AS b
		WHERE b.ID_BOARD IN (" . implode(', ', $boards) . ")
			AND $user_info[query_see_board]" . (!in_array(0, $merge_boards) ? "
			AND b.ID_BOARD IN (" . implode(', ', $merge_boards) . ")" : '') . "
		LIMIT " . count($boards), __FILE__, __LINE__);
	// If the number of boards that's in the output isn't exactly the same as we've put in there, you're in trouble.
	if (mysql_num_rows($request) != count($boards))
		fatal_lang_error('smf232');
	mysql_free_result($request);

	if (empty($_POST['sa']) || (int) $_POST['sa'] != 2)
	{
		if (count($polls) > 1)
		{
			$request = db_query("
				SELECT t.ID_TOPIC, t.ID_POLL, m.subject, p.question
				FROM {$db_prefix}polls AS p, {$db_prefix}topics AS t, {$db_prefix}messages AS m
				WHERE p.ID_POLL IN (" . implode(', ', $polls) . ")
					AND t.ID_POLL = p.ID_POLL
					AND m.ID_MSG = t.ID_FIRST_MSG
				LIMIT " . count($polls), __FILE__, __LINE__);
			while ($row = mysql_fetch_assoc($request))
				$context['polls'][] = array(
					'id' => $row['ID_POLL'],
					'topic' => array(
						'id' => $row['ID_TOPIC'],
						'subject' => $row['subject']
					),
					'question' => $row['question'],
					'selected' => $row['ID_TOPIC'] == $firstTopic
				);
			mysql_free_result($request);
		}
		if (count($boards) > 1)
		{
			$request = db_query("
				SELECT ID_BOARD, name
				FROM {$db_prefix}boards
				WHERE ID_BOARD IN (" . implode(', ', $boards) . ")
				ORDER BY name
				LIMIT " . count($boards), __FILE__, __LINE__);
			while ($row = mysql_fetch_assoc($request))
				$context['boards'][] = array(
					'id' => $row['ID_BOARD'],
					'name' => $row['name'],
					'selected' => $row['ID_BOARD'] == $topic_data[$firstTopic]['board']
				);
		}

		$context['topics'] = $topic_data;
		foreach ($topic_data as $id => $topic)
			$context['topics'][$id]['selected'] = $topic['id'] == $firstTopic;

		$context['page_title'] = $txt['smf252'];
		$context['sub_template'] = 'merge_extra_options';
		return;
	}

	// Determine target board.
	$target_board = count($boards) > 1 ? (int) $_POST['board'] : $boards[0];
	if (!in_array($target_board, $boards))
		fatal_lang_error('smf232');

	// Determine which poll will survive and which polls won't.
	$target_poll = count($polls) > 1 ? (int) $_POST['poll'] : (count($polls) == 1 ? $polls[0] : -1);
	if ($target_poll > 0 && !in_array($target_poll, $polls))
		fatal_lang_error(1, false);
	$deleted_polls = empty($target_poll) ? $polls : array_diff($polls, array($target_poll));

	// Determine the subject of the newly merged topic - was a custom subject was filled in?
	if (empty($_POST['subject']) && isset($_POST['custom_subject']) && $_POST['custom_subject'] != '')
		$target_subject = preg_replace('~&amp;#(\d{4,5}|[3-9]\d{2,4}|2[6-9]\d);~', '&#$1;', htmlspecialchars($_POST['custom_subject']));
	// A subject was selected...
	elseif (!empty($topic_data[(int) $_POST['subject']]['subject']))
		$target_subject = addslashes($topic_data[(int) $_POST['subject']]['subject']);
	// Nothing worked? Just take the subject of the first message.
	else
		$target_subject = addslashes($topic_data[$firstTopic]['subject']);

	// Get the first and last message and the number of messages....
	$request = db_query("
		SELECT MIN(ID_MSG), MAX(ID_MSG), COUNT(ID_MSG) - 1
		FROM {$db_prefix}messages
		WHERE ID_TOPIC IN (" . implode(', ', $topics) . ")", __FILE__, __LINE__);
	list ($first_msg, $last_msg, $num_replies) = mysql_fetch_row($request);
	mysql_free_result($request);

	// Get the member ID of the first and last message.
	$request = db_query("
		SELECT ID_MEMBER
		FROM {$db_prefix}messages
		WHERE ID_MSG IN ($first_msg, $last_msg)
		ORDER BY ID_MSG
		LIMIT 2", __FILE__, __LINE__);
	list ($member_started) = mysql_fetch_row($request);
	list ($member_updated) = mysql_fetch_row($request);
	mysql_free_result($request);

	// Delete the old topics....
	db_query("
		DELETE FROM {$db_prefix}topics
		WHERE ID_TOPIC IN (" . implode(', ', $topics) . ")
		LIMIT " . count($topics), __FILE__, __LINE__);

	// Insert a new topic.
	db_query("
		INSERT INTO {$db_prefix}topics
			(ID_BOARD, ID_MEMBER_STARTED, ID_MEMBER_UPDATED, ID_FIRST_MSG, ID_LAST_MSG, ID_POLL, numReplies, numViews, isSticky)
		VALUES ($target_board, $member_started, $member_updated, $first_msg, $last_msg, $target_poll, $num_replies, $num_views, $isSticky)", __FILE__, __LINE__);
	$ID_TOPIC = db_insert_id();

	// Something went wrong creating the new topic.
	if (empty($ID_TOPIC))
		fatal_lang_error('merge_create_topic_failed');

	// Change the topic IDs of all messages that will be merged.  Also adjust subjects if 'enforce subject' was checked.
	db_query("
		UPDATE {$db_prefix}messages
		SET ID_TOPIC = $ID_TOPIC, ID_BOARD = $target_board" . (!empty($_POST['enforce_subject']) ? ", subject = '$txt[response_prefix]$target_subject'" : '') . "
		WHERE ID_TOPIC IN (" . implode(', ', $topics) . ")", __FILE__, __LINE__);

	// Change the subject of the first message.
	db_query("
		UPDATE {$db_prefix}messages
		SET subject = '$target_subject'
		WHERE ID_MSG = $first_msg
		LIMIT 1", __FILE__, __LINE__);

	// Adjust all calendar events to point to the new topic.
	db_query("
		UPDATE {$db_prefix}calendar
		SET ID_TOPIC = $ID_TOPIC, ID_BOARD = $target_board
		WHERE ID_TOPIC IN (" . implode(', ', $topics) . ")", __FILE__, __LINE__);

	// Merge log topic entries.
	$request = db_query("
		SELECT ID_MEMBER, MIN(logTime) AS newLogTime
		FROM {$db_prefix}log_topics
		WHERE ID_TOPIC IN (" . implode(', ', $topics) . ")
		GROUP BY ID_MEMBER", __FILE__, __LINE__);
	if (mysql_num_rows($request) > 0)
	{
		$insertEntries = array();
		while ($row = mysql_fetch_assoc($request))
			$insertEntries[] = "($row[ID_MEMBER], $ID_TOPIC, $row[newLogTime])";

		db_query("
			REPLACE INTO {$db_prefix}log_topics
				(ID_MEMBER, ID_TOPIC, logTime)
			VALUES " . implode(', ', $insertEntries), __FILE__, __LINE__);
		unset($insertEntries);

		// Get rid of the old log entries.
		db_query("
			DELETE FROM {$db_prefix}log_topics
			WHERE ID_TOPIC IN (" . implode(', ', $topics) . ")", __FILE__, __LINE__);
	}
	mysql_free_result($request);

	// Merge topic notifications.
	if (!empty($_POST['notifications']) && is_array($_POST['notifications']))
	{
		// Check if the notification array contains valid topics.
		if (count(array_diff($_POST['notifications'], $topics)) > 0)
			fatal_lang_error('smf232');
		$request = db_query("
			SELECT ID_MEMBER, MAX(sent) AS sent
			FROM {$db_prefix}log_notify
			WHERE ID_TOPIC IN (" . implode(', ', $_POST['notifications']) . ")
			GROUP BY ID_MEMBER", __FILE__, __LINE__);
		if (mysql_num_rows($request) > 0)
		{
			$insertEntries = array();
			while ($row = mysql_fetch_assoc($request))
				$insertEntries[] = "($row[ID_MEMBER], $ID_TOPIC, 0, $row[sent])";

			db_query("
				INSERT INTO {$db_prefix}log_notify
					(ID_MEMBER, ID_TOPIC, ID_BOARD, sent)
				VALUES " . implode(', ', $insertEntries), __FILE__, __LINE__);
			unset($insertEntries);

			db_query("
				DELETE FROM {$db_prefix}log_topics
				WHERE ID_TOPIC IN (" . implode(', ', $topics) . ")", __FILE__, __LINE__);
		}
		mysql_free_result($request);
	}

	// Get rid of the redundant polls.
	if (!empty($deleted_polls))
	{
		db_query("
			DELETE FROM {$db_prefix}polls
			WHERE ID_POLL IN (" . implode(', ', $deleted_polls) . ")
			LIMIT 1", __FILE__, __LINE__);
		db_query("
			DELETE FROM {$db_prefix}poll_choices
			WHERE ID_POLL IN (" . implode(', ', $deleted_polls) . ")", __FILE__, __LINE__);
		db_query("
			DELETE FROM {$db_prefix}log_polls
			WHERE ID_POLL IN (" . implode(', ', $deleted_polls) . ")", __FILE__, __LINE__);
	}

	// Fix the board totals.
	if (count($boards) > 1)
	{
		$request = db_query("
			SELECT ID_BOARD, COUNT(ID_TOPIC) AS numTopics, SUM(numReplies) + COUNT(ID_TOPIC) AS numPosts
			FROM {$db_prefix}topics
			WHERE ID_BOARD IN (" . implode(', ', $boards) . ")
			GROUP BY ID_BOARD
			LIMIT " . count($boards), __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
			db_query("
				UPDATE {$db_prefix}boards
				SET numPosts = $row[numPosts], numTopics = $row[numTopics]
				WHERE ID_BOARD = $row[ID_BOARD]
				LIMIT 1", __FILE__, __LINE__);
		mysql_free_result($request);
	}
	else
		db_query("
			UPDATE {$db_prefix}boards
			SET numTopics = numTopics - " . (count($topics) - 1) . "
			WHERE ID_BOARD = $target_board
			LIMIT 1", __FILE__, __LINE__);

	// Update all the statistics.
	updateStats('topic');
	updateLastMessages($boards);

	logAction('merge', array('topic' => $ID_TOPIC));
	// Notify people that these topics have been merged?
	require_once($sourcedir . '/Subs-Post.php');
	sendNotifications($ID_TOPIC, 'merge');

	// Send them to the all done page.
	redirectexit('action=mergetopics;sa=3;to=' . $ID_TOPIC . ';targetboard=' . $target_board);
}

// Tell the user the move was done properly.
function MergeTopics3()
{
	global $txt, $context;

	// Make sure the template knows everything...
	$context['target_board'] = $_GET['targetboard'];
	$context['target_topic'] = $_GET['to'];

	$context['page_title'] = $txt['smf252'];
	$context['sub_template'] = 'merge_done';
}

?>