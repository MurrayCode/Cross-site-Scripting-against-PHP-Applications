<?php
/******************************************************************************
* Display.php                                                                 *
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

/*	This is perhaps the most important and probably most accessed files in all
	of SMF.  This file controls topic, message, and attachment display.  It
	does so with the following functions:

	void Display()
		- loads the posts in a topic up so they can be displayed.
		- supports wireless, using wap/wap2/imode and the Wireless templates.
		- uses the main sub template of the Display template.
		- requires a topic, and can go to the previous or next topic from it.
		- jumps to the correct post depending on a number/time/IS_MSG passed.
		- depends on the defaultMaxMessages and enableAllMessages settings.
		- is accessed by ?topic=ID_TOPIC.START.

	array prepareDisplayContext(bool reset = false)
		- actually gets and prepares the message context.
		- starts over from the beginning if reset is set to true, which is
		  useful for showing an index before or after the posts.

	void Download()
		- downloads an attachment or avatar, and increments the downloads.
		- requires the view_attachments permission. (not for avatars!)
		- disables the session parser, and clears any previous output.
		- depends on the attachmentUploadDir setting being correct.
		- is accessed via the query string ?action=dlattach.
		- views to attachments and avatars do not increase hits and are not
		  logged in the "Who's Online" log.

	array loadAttachmentContext(int ID_MSG)
		- loads an attachment's contextual data including, most importantly,
		  its size if it is an image.
		- expects the $attachments array to have been filled with the proper
		  attachment data, as Display() does.
		- requires the view_attachments permission to calculate image size.
		- attempts to keep the "aspect ratio" of the posted image in line,
		  even if it has to be resized by the maxwidth and maxheight settings.
*/

// The central part of the board - topic display.
function Display()
{
	global $scripturl, $txt, $db_prefix, $modSettings, $context, $settings, $options, $sourcedir;
	global $user_info, $ID_MEMBER, $board_info, $topic, $board, $attachments, $messages_request;

	// What are you gonna display if these are empty?!
	if (empty($topic))
		fatal_lang_error('smf232', false);

	// Load the proper template and/or sub template.
	if (WIRELESS)
		$context['sub_template'] = WIRELESS_PROTOCOL . '_display';
	else
		loadTemplate('Display');

	// Find the previous or next topic.  Make a fuss if there are no more.
	if (isset($_REQUEST['prev_next']) && ($_REQUEST['prev_next'] == 'prev' || $_REQUEST['prev_next'] == 'next'))
	{
		// Just prepare some variables that are used in the query.
		$gt_lt = $_REQUEST['prev_next'] == 'prev' ? '>' : '<';
		$order = $_REQUEST['prev_next'] == 'prev' ? '' : ' DESC';

		$request = db_query("
			SELECT t2.ID_TOPIC
			FROM {$db_prefix}topics AS t, {$db_prefix}topics AS t2, {$db_prefix}messages AS m, {$db_prefix}messages AS m2
			WHERE m.ID_MSG = t.ID_LAST_MSG
				AND t.ID_TOPIC = $topic" . (empty($modSettings['enableStickyTopics']) ? "
				AND m2.posterTime $gt_lt m.posterTime" : "
				AND ((m2.posterTime $gt_lt m.posterTime AND t2.isSticky $gt_lt= t.isSticky) OR t2.isSticky $gt_lt t.isSticky)") . "
				AND t2.ID_LAST_MSG = m2.ID_MSG
				AND t2.ID_BOARD = $board
			ORDER BY" . (empty($modSettings['enableStickyTopics']) ? '' : " t2.isSticky$order,") . " m2.posterTime$order
			LIMIT 1", __FILE__, __LINE__);

		// No more left.
		if (mysql_num_rows($request) == 0)
			fatal_lang_error('previous_next_end', false);

		// Now you can be sure $topic is the ID_TOPIC to view.
		list ($topic) = mysql_fetch_row($request);
		mysql_free_result($request);

		$context['current_topic'] = $topic;

		// Go to the newest message on this topic.
		$_REQUEST['start'] = 'new';
	}

	// Add 1 to the number of views of this topic.
	if (empty($_SESSION['last_read_topic']) || $_SESSION['last_read_topic'] != $topic)
	{
		db_query("
			UPDATE {$db_prefix}topics
			SET numViews = numViews + 1
			WHERE ID_TOPIC = $topic
			LIMIT 1", __FILE__, __LINE__);

		$_SESSION['last_read_topic'] = $topic;
	}

	// Get all the important topic info.
	$request = db_query("
		SELECT
			t.numReplies, t.numViews, t.locked, ms.subject, t.isSticky, t.ID_POLL, t.ID_MEMBER_STARTED,
			IFNULL(lt.logTime, 0) AS logTime, t.ID_FIRST_MSG
		FROM {$db_prefix}topics AS t, {$db_prefix}messages AS ms
			LEFT JOIN {$db_prefix}log_topics AS lt ON (lt.ID_TOPIC = $topic AND lt.ID_MEMBER = $ID_MEMBER)
		WHERE t.ID_TOPIC = $topic
			AND ms.ID_MSG = t.ID_FIRST_MSG
		LIMIT 1", __FILE__, __LINE__);
	if (mysql_num_rows($request) == 0)
		fatal_lang_error(472, false);
	$topicinfo = mysql_fetch_assoc($request);
	mysql_free_result($request);

	// The start isn't a number; it's information about what to do, where to go.
	if (!is_numeric($_REQUEST['start']))
	{
		// Redirect to the page and post with new messagesm, originally by Omar Bazavilvazo.
		if ($_REQUEST['start'] == 'new')
		{
			// Guests automatically go to the last topic.
			if ($user_info['is_guest'])
				$logTime = time();
			else
			{
				// Find the earliest unread message in the topic. (the use of topics here is just for both tables.)
				$request = db_query("
					SELECT IFNULL(lt.logTime, IFNULL(lmr.logTime, 0)) AS logTime
					FROM {$db_prefix}topics AS t
						LEFT JOIN {$db_prefix}log_topics AS lt ON (lt.ID_TOPIC = $topic AND lt.ID_MEMBER = $ID_MEMBER)
						LEFT JOIN {$db_prefix}log_mark_read AS lmr ON (lmr.ID_BOARD = $board AND lmr.ID_MEMBER = $ID_MEMBER)
					WHERE t.ID_TOPIC = $topic
					LIMIT 1", __FILE__, __LINE__);
				list ($logTime) = mysql_fetch_row($request);
				mysql_free_result($request);
			}

			// Fall through to the next if statement.
			$_REQUEST['start'] = 'from' . $logTime;
		}

		// Start from a certain time index, not a message.
		if (substr($_REQUEST['start'], 0, 4) == 'from')
		{
			// Find the number of messages posted before said time...
			$request = db_query("
				SELECT COUNT(ID_MSG)
				FROM {$db_prefix}messages
				WHERE posterTime < " . (int) substr($_REQUEST['start'], 4) . "
					AND ID_TOPIC = $topic", __FILE__, __LINE__);
			list ($context['start_from']) = mysql_fetch_row($request);
			mysql_free_result($request);

			// Handle view_newest_first options, and get the correct start value.
			$_REQUEST['start'] = empty($options['view_newest_first']) ? $context['start_from'] : $topicinfo['numReplies'] - $context['start_from'];
		}
		// Link to a message...
		elseif (substr($_REQUEST['start'], 0, 3) == 'msg')
		{
			// Find the start value for that message......
			$request = db_query("
				SELECT COUNT(ID_MSG)
				FROM {$db_prefix}messages
				WHERE ID_MSG < " . (int) substr($_REQUEST['start'], 3) . "
					AND ID_TOPIC = $topic", __FILE__, __LINE__);
			list ($_REQUEST['start']) = mysql_fetch_row($request);
			mysql_free_result($request);

			// We need to reverse the start as well in this case.
			if (!empty($options['view_newest_first']))
				$_REQUEST['start'] = $topicinfo['numReplies'] - $_REQUEST['start'];
		}
	}

	// Create a previous next string if the selected theme has it as a selected option.
	$context['previous_next'] = $modSettings['enablePreviousNext'] ? '<a href="' . $scripturl . '?topic=' . $topic . '.0;prev_next=prev">' . $txt['previous_next_back'] . '</a> <a href="' . $scripturl . '?topic=' . $topic . ';prev_next=next">' . $txt['previous_next_forward'] . '</a>' : '';

	// Check if spellchecking is both enabled and actually working. (for quick reply.)
	$context['show_spellchecking'] = $modSettings['enableSpellChecking'] && function_exists('pspell_new');

	// Censor the title...
	censorText($topicinfo['subject']);
	$context['page_title'] = $topicinfo['subject'];

	$context['num_replies'] = $topicinfo['numReplies'];
	$context['topic_first_message'] = $topicinfo['ID_FIRST_MSG'];

	// Is this topic sticky, or can it even be?
	$topicinfo['isSticky'] = empty($modSettings['enableStickyTopics']) ? '0' : $topicinfo['isSticky'];

	// Default this topic to not marked for notifications... of course...
	$context['is_marked_notify'] = false;

	// Guests can't mark topics read or for notifications, just can't sorry.
	if (!$user_info['is_guest'])
	{
		// Mark the topic as read :)
		db_query("
			REPLACE INTO {$db_prefix}log_topics
				(logTime, ID_MEMBER, ID_TOPIC)
			VALUES (" . time() . ", $ID_MEMBER, $topic)", __FILE__, __LINE__);

		// Check for notifications on this topic OR board.
		$request = db_query("
			SELECT sent, ID_TOPIC
			FROM {$db_prefix}log_notify
			WHERE (ID_TOPIC = $topic OR ID_BOARD = $board)
				AND ID_MEMBER = $ID_MEMBER
			LIMIT 2", __FILE__, __LINE__);
		$do_once = true;
		while ($row = mysql_fetch_assoc($request))
		{
			// Find if this topic is marked for notification...
			if (!empty($row['ID_TOPIC']))
				$context['is_marked_notify'] = true;

			// Only do this once, but mark the notifications as "not sent yet" for next time.
			if (!empty($row['sent']) && $do_once)
			{
				db_query("
					UPDATE {$db_prefix}log_notify
					SET sent = 0
					WHERE (ID_TOPIC = $topic OR ID_BOARD = $board)
						AND ID_MEMBER = $ID_MEMBER
					LIMIT 1", __FILE__, __LINE__);
				$do_once = false;
			}
		}

		// Mark board as seen if this is the only new topic.
		if (isset($_REQUEST['topicseen']))
		{
			// Use the mark read tables... and the last visit to figure out if this should be read or not.
			$request = db_query("
				SELECT COUNT(t.ID_TOPIC)
				FROM {$db_prefix}topics AS t, {$db_prefix}messages AS m
					LEFT JOIN {$db_prefix}log_boards AS lb ON (lb.ID_BOARD = $board AND lb.ID_MEMBER = $ID_MEMBER)
					LEFT JOIN {$db_prefix}log_topics AS lt ON (lt.ID_TOPIC = t.ID_TOPIC AND lt.ID_MEMBER = $ID_MEMBER)
				WHERE t.ID_BOARD = $board
					AND m.ID_MSG = t.ID_LAST_MSG
					AND m.posterTime > IFNULL(lt.logTime, IFNULL(lb.logTime, 0))" . (empty($_SESSION['ID_MSG_LAST_VISIT']) ? '' : "
					AND t.ID_LAST_MSG > $_SESSION[ID_MSG_LAST_VISIT]"), __FILE__, __LINE__);
			list ($numNewTopics) = mysql_fetch_row($request);
			mysql_free_result($request);

			// If there're no real new topics in this board, mark the board as seen.
			if (empty($numNewTopics))
				$_REQUEST['boardseen'] = true;
		}

		// Mark board as seen if we came using last post link from BoardIndex. (or other places...)
		if (isset($_REQUEST['boardseen']))
		{
			db_query("
				REPLACE INTO {$db_prefix}log_boards
					(logTime, ID_MEMBER, ID_BOARD)
				VALUES (" . time() . ", $ID_MEMBER, $board)", __FILE__, __LINE__);
		}
	}

	// Let's get nosey, who is viewing this topic?
	if (!empty($settings['display_who_viewing']))
	{
		// Start out with no one at all viewing it.
		$context['view_members'] = array();
		$context['view_members_list'] = array();
		$context['view_num_hidden'] = 0;

		// Search for members who have this topic set in their GET data.
		$request = db_query("
			SELECT mem.ID_MEMBER, IFNULL(mem.realName, 0) AS realName, mem.showOnline
			FROM {$db_prefix}log_online AS lo
				LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = lo.ID_MEMBER)
			WHERE lo.url LIKE '%s:5:\"topic\";i:$topic;%'", __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
			if (!empty($row['ID_MEMBER']))
			{
				// Add them both to the list and to the more detailed list.
				if (!empty($row['showOnline']) || allowedTo('moderate_forum'))
					$context['view_members_list'][] = empty($row['showOnline']) ? '<i><a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['realName'] . '</a></i>' : '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['realName'] . '</a>';
				$context['view_members'][] = array(
					'id' => $row['ID_MEMBER'],
					'name' => $row['realName'],
					'href' => $scripturl . '?action=profile;u=' . $row['ID_MEMBER'],
					'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['realName'] . '</a>',
					'hidden' => empty($row['showOnline']),
				);

				if (empty($row['showOnline']))
					$context['view_num_hidden']++;
			}

		// The number of guests is equal to the rows minus the ones we actually used ;).
		$context['view_num_guests'] = mysql_num_rows($request) - count($context['view_members']);
		mysql_free_result($request);
	}

	// If all is set, but not allowed... just unset it.
	if (isset($_REQUEST['all']) && empty($modSettings['enableAllMessages']))
		unset($_REQUEST['all']);
	// Otherwise, it must be allowed... so pretend start was -1.
	elseif (isset($_REQUEST['all']))
		$_REQUEST['start'] = -1;

	// Construct the page index, allowing for the .START method...
	$context['page_index'] = constructPageIndex($scripturl . '?topic=' . $topic, $_REQUEST['start'], $topicinfo['numReplies'] + 1, $modSettings['defaultMaxMessages'], true);
	$context['start'] = $_REQUEST['start'];

	// This is information about which page is current, and which page we're on - in case you don't like the constructed page index. (again, wireles..)
	$context['page_info'] = array(
		'current_page' => $_REQUEST['start'] / $modSettings['defaultMaxMessages'] + 1,
		'num_pages' => floor($topicinfo['numReplies'] / $modSettings['defaultMaxMessages']) + 1
	);

	// Figure out all the link to the next/prev/first/last/etc. for wireless mainly.
	$context['links'] = array(
		'first' => $_REQUEST['start'] >= $modSettings['defaultMaxMessages'] ? $scripturl . '?topic=' . $topic . '.0' : '',
		'prev' => $_REQUEST['start'] >= $modSettings['defaultMaxMessages'] ? $scripturl . '?topic=' . $topic . '.' . ($_REQUEST['start'] - $modSettings['defaultMaxMessages']) : '',
		'next' => $_REQUEST['start'] + $modSettings['defaultMaxMessages'] < $topicinfo['numReplies'] + 1 ? $scripturl . '?topic=' . $topic. '.' . ($_REQUEST['start'] + $modSettings['defaultMaxMessages']) : '',
		'last' => $_REQUEST['start'] + $modSettings['defaultMaxMessages'] < $topicinfo['numReplies'] + 1 ? $scripturl . '?topic=' . $topic. '.' . (floor($topicinfo['numReplies'] / $modSettings['defaultMaxMessages']) * $modSettings['defaultMaxMessages']) : '',
		'up' => $scripturl . '?board=' . $board . '.0'
	);

	// If they are viewing all the posts, show all the posts, otherwise limit the number.
	if (!empty($modSettings['enableAllMessages']) && $topicinfo['numReplies'] + 1 > $modSettings['defaultMaxMessages'] && $topicinfo['numReplies'] + 1 < $modSettings['enableAllMessages'])
	{
		if (isset($_REQUEST['all']))
		{
			// No limit! (actually, there is a limit, but...)
			$modSettings['defaultMaxMessages'] = -1;
			$context['page_index'] .= empty($modSettings['compactTopicPagesEnable']) ? '<b>' . $txt[190] . '</b> ' : '[<b>' . $txt[190] . '</b>] ';

			// Set start back to 0...
			$_REQUEST['start'] = 0;
		}
		// They aren't using it, but the *option* is there, at least.
		else
			$context['page_index'] .= '&nbsp;<a href="' . $scripturl . '?topic=' . $topic . '.0;all">' . $txt[190] . '</a> ';
	}

	// Build the link tree.
	$context['linktree'][] = array(
		'url' => $scripturl . '?topic=' . $topic . '.0',
		'name' => $topicinfo['subject'],
		'extra_before' => $settings['linktree_inline'] ? $txt[118] . ': ' : ''
	);

	// Build a list of this board's moderators.
	$context['moderators'] = &$board_info['moderators'];
	$context['link_moderators'] = array();
	if (!empty($board_info['moderators']))
	{
		// Add a link for each moderator...
		foreach ($board_info['moderators'] as $mod)
			$context['link_moderators'][] = '<a href="' . $scripturl . '?action=profile;u=' . $mod['id'] . '" title="' . $txt[62] . '">' . $mod['name'] . '</a>';

		// And show it after the board's name.
		$context['linktree'][count($context['linktree']) - 2]['extra_after'] = ' (' . (count($context['link_moderators']) == 1 ? $txt[298] : $txt[299]) . ': ' . implode(', ', $context['link_moderators']) . ')';
	}

	// Information about the current topic...
	$context['is_locked'] = $topicinfo['locked'];
	$context['is_sticky'] = $topicinfo['isSticky'];
	$context['is_very_hot'] = $topicinfo['numReplies'] >= $modSettings['hotTopicVeryPosts'];
	$context['is_hot'] = $topicinfo['numReplies'] >= $modSettings['hotTopicPosts'];

	// We don't want to show the poll icon in the topic class here, so pretend it's not one.
	$context['is_poll'] = false;
	determineTopicClass($context);

	$context['is_poll'] = $topicinfo['ID_POLL'] > 0 && $modSettings['pollMode'] == '1' && allowedTo('poll_view');

	// Did this user start the topic or not?
	$context['user']['started'] = $ID_MEMBER == $topicinfo['ID_MEMBER_STARTED'] && !$user_info['is_guest'];

	// Set the topic's information for the template.
	$context['subject'] = $topicinfo['subject'];
	$context['num_views'] = $topicinfo['numViews'];

	// Create the poll info if it exists.
	if ($context['is_poll'])
	{
		// Get the question and if it's locked.
		$request = db_query("
			SELECT
				p.question, p.votingLocked, p.hideResults, p.expireTime, p.maxVotes, p.changeVote,
				p.ID_MEMBER, IFNULL(mem.realName, p.posterName) AS posterName,
				COUNT(DISTINCT lp.ID_MEMBER) AS total, COUNT(lp2.ID_MEMBER) AS has_voted
			FROM {$db_prefix}polls AS p
				LEFT JOIN {$db_prefix}log_polls AS lp ON (lp.ID_POLL = p.ID_POLL)
				LEFT JOIN {$db_prefix}log_polls AS lp2 ON (lp2.ID_POLL = p.ID_POLL AND lp2.ID_MEMBER = $ID_MEMBER)
				LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = p.ID_MEMBER)
			WHERE p.ID_POLL = $topicinfo[ID_POLL]
			GROUP BY p.ID_POLL
			LIMIT 1", __FILE__, __LINE__);
		$pollinfo = mysql_fetch_assoc($request);
		mysql_free_result($request);

		// Get all the options, and calculate the total votes.
		$request = db_query("
			SELECT ID_CHOICE, label, votes
			FROM {$db_prefix}poll_choices
			WHERE ID_POLL = $topicinfo[ID_POLL]", __FILE__, __LINE__);
		$pollOptions = array();
		$realtotal = 0;
		while ($row = mysql_fetch_assoc($request))
		{
			$pollOptions[$row['ID_CHOICE']] = array($row['label'], $row['votes']);
			$realtotal += $row['votes'];
		}
		mysql_free_result($request);

		// Set up the basic poll information.
		$context['poll'] = array(
			'id' => $topicinfo['ID_POLL'],
			'image' => 'normal_' . (empty($pollinfo['votingLocked']) ? 'poll' : 'locked_poll'),
			'question' => doUBBC($pollinfo['question']),
			'total_votes' => $pollinfo['total'],
			'change_vote' => !empty($pollinfo['changeVote']),
			'is_locked' => !empty($pollinfo['votingLocked']),
			'options' => array(),
			'lock' => allowedTo('poll_lock_any') || ($context['user']['started'] && allowedTo('poll_lock_own')),
			'edit' => allowedTo('poll_edit_any') || ($context['user']['started'] && allowedTo('poll_edit_own')),
			'allowed_warning' => $pollinfo['maxVotes'] > 1 ? sprintf($txt['poll_options6'], $pollinfo['maxVotes']) : '',
			'is_expired' => !empty($pollinfo['expireTime']) && $pollinfo['expireTime'] < time(),
			'expire_time' => !empty($pollinfo['expireTime']) ? timeformat($pollinfo['expireTime']) : 0,
			'has_voted' => !empty($pollinfo['has_voted']),
			'starter' => array(
				'id' => $pollinfo['ID_MEMBER'],
				'name' => $row['posterName'],
				'href' => $pollinfo['ID_MEMBER'] == 0 ? '' : $scripturl . '?action=profile;u=' . $pollinfo['ID_MEMBER'],
				'link' => $pollinfo['ID_MEMBER'] == 0 ? $row['posterName'] : '<a href="' . $scripturl . '?action=profile;u=' . $pollinfo['ID_MEMBER'] . '">' . $row['posterName'] . '</a>'
			)
		);

		// You're allowed to vote if:
		// 1. the poll did not expire, and
		// 2. you're not a guest... and
		// 3. you're not trying to view the results, and
		// 4. the poll is not locked, and
		// 5. you have the proper permissions, and
		// 6. you haven't already voted before.
		$context['allow_vote'] = !$context['poll']['is_expired'] && !$user_info['is_guest'] && !isset($_REQUEST['viewResults']) && empty($pollinfo['votingLocked']) && allowedTo('poll_vote') && !$context['poll']['has_voted'];

		// You're allowed to view the results if:
		// 1. You're just a super-nice-guy, or
		// 2. Anyone can see them (hideResults == 0), or
		// 3. You can see them after you voted (hideResults == 1), or
		// 4. You've waited long enough for the poll to expire. (whether hideResults is 1 or 2.)
		$context['allow_poll_view'] = allowedTo('moderate_board') || $pollinfo['hideResults'] == 0 || ($pollinfo['hideResults'] == 1 && $context['poll']['has_voted']) || $context['poll']['is_expired'];

		// You're allowed to change your vote if:
		// 1. the poll did not expire, and
		// 2. you're not a guest... and
		// 3. the poll is not locked, and
		// 4. you have the proper permissions, and
		// 5. you have already voted.
		// 6. the poll creator has said you can!
		$context['allow_change_vote'] = !$context['poll']['is_expired'] && !$user_info['is_guest'] && empty($pollinfo['votingLocked']) && allowedTo('poll_vote') && $context['poll']['has_voted'] && $context['poll']['change_vote'];

		// Calculate the percentages and bar lengths...
		$divisor = $realtotal == 0 ? 1 : $realtotal;

		// Determine if a decimal point is needed in order for the options to add to 100%.
		$precision = $realtotal == 100 ? 0 : 1;

		// Now look through each option, and...
		foreach ($pollOptions as $i => $option)
		{
			// First calculate the percentage, and then the width of the bar...
			$bar = round(($option[1] * 100) / $divisor, $precision);
			$barWide = $bar == 0 ? 1 : floor(($bar * 8) / 3);

			// Now add it to the poll's contextual theme data.
			$context['poll']['options'][$i] = array(
				'percent' => $bar,
				'votes' => $option[1],
				'bar' => '<span style="white-space: nowrap;"><img src="' . $settings['images_url'] . '/poll_left.gif" alt="" /><img src="' . $settings['images_url'] . '/poll_middle.gif" width="' . $barWide . '" height="12" alt="-" /><img src="' . $settings['images_url'] . '/poll_right.gif" alt="" /></span>',
				'option' => doUBBC($option[0]),
				'vote_button' => '<input type="' . ($pollinfo['maxVotes'] > 1 ? 'checkbox' : 'radio') . '" name="options[]" value="' . $i . '" class="check" />'
			);
		}
	}

	// Calculate the fastest way to get the messages.
	$ascending = empty($options['view_newest_first']);
	$start = $_REQUEST['start'];
	$limit = $modSettings['defaultMaxMessages'];
	$firstIndex = 0;

	if ($start > $topicinfo['numReplies'] / 2 && $modSettings['defaultMaxMessages'] != -1)
	{
		$ascending = !$ascending;
		$limit = $topicinfo['numReplies'] < $start + $limit ? $topicinfo['numReplies'] - $start + 1 : $limit;
		$start = $topicinfo['numReplies'] < $start + $limit ? 0 : $topicinfo['numReplies'] - $start - $limit + 1;
		$firstIndex = $limit - 1;
	}

	// Get each post and poster in this topic.
	$request = db_query("
		SELECT ID_MSG, ID_MEMBER
		FROM {$db_prefix}messages
		WHERE ID_TOPIC = $topic
		ORDER BY ID_MSG " . ($ascending ? '' : 'DESC') . ($modSettings['defaultMaxMessages'] == -1 ? '' : "
		LIMIT $start, $limit"), __FILE__, __LINE__);

	$messages = array();
	$posters = array();
	while ($row = mysql_fetch_assoc($request))
	{
		if (!empty($row['ID_MEMBER']))
			$posters[] = $row['ID_MEMBER'];
		$messages[] = $row['ID_MSG'];
	}
	mysql_free_result($request);
	$posters = array_unique($posters);

	$attachments = array();

	// If there _are_ messages here... (probably an error otherwise :!)
	if (!empty($messages))
	{
		// Fetch attachments.
		if (!empty($modSettings['attachmentEnable']))
		{
			$request = db_query("
				SELECT ID_ATTACH, ID_MSG, filename, IFNULL(size, 0) AS filesize, downloads
				FROM {$db_prefix}attachments
				WHERE ID_MSG IN (" . implode(',', $messages) . ")", __FILE__, __LINE__);
			$temp = array();
			while ($row = mysql_fetch_assoc($request))
			{
				$temp[$row['ID_ATTACH']] = $row;

				if (!isset($attachments[$row['ID_MSG']]))
					$attachments[$row['ID_MSG']] = array();
			}
			mysql_free_result($request);

			// This is better than sorting it with the query...
			ksort($temp);

			foreach ($temp as $row)
				$attachments[$row['ID_MSG']][] = $row;
		}

		// What?  It's not like it *couldn't* be only guests in this topic...
		if (!empty($posters))
			loadMemberData($posters);
		$messages_request = db_query("
			SELECT
				ID_MSG, icon, subject, posterTime, posterIP, ID_MEMBER, modifiedTime, modifiedName, body,
				smileysEnabled, posterName, posterEmail,
				(GREATEST(posterTime, modifiedTime) > $topicinfo[logTime]) AS isRead
			FROM {$db_prefix}messages
			WHERE ID_MSG IN (" . implode(',', $messages) . ")
			ORDER BY ID_MSG" . (empty($options['view_newest_first']) ? '' : ' DESC'), __FILE__, __LINE__);

		// Go to the last message if the given time is beyond the time of the last message.
		if (isset($context['start_from']) && $context['start_from'] >= $topicinfo['numReplies'])
			$context['start_from'] = $topicinfo['numReplies'];

		// Since the anchor information is needed on the top of the page we load these variables beforehand.
		$context['first_message'] = isset($messages[$firstIndex]) ? $messages[$firstIndex] : $messages[0];
		if (empty($options['view_newest_first']))
			$context['first_new_message'] = isset($context['start_from']) && $_REQUEST['start'] == $context['start_from'];
		else
			$context['first_new_message'] = isset($context['start_from']) && $_REQUEST['start'] == $topicinfo['numReplies'] - $context['start_from'];
	}
	else
	{
		$messages_request = false;
		$context['first_message'] = 0;
		$context['first_new_message'] = false;
	}

	// Set the callback.  (do you REALIZE how much memory all the messages would take?!?)
	$context['get_message'] = 'prepareDisplayContext';

	// Basic settings.... may be converted over at some point.
	$context['allow_hide_email'] = !empty($modSettings['allow_hideEmail']) || ($user_info['is_guest'] && !empty($modSettings['guest_hideContacts']));

	// Now set all the wonderful, wonderful permissions... like moderation ones...
	$common_permissions = array(
		'can_sticky' => 'make_sticky',
		'can_merge' => 'merge_any',
		'can_split' => 'split_any',
		'calendar_post' => 'calendar_post',
		'can_mark_notify' => 'mark_any_notify',
		'can_send_topic' => 'send_topic',
		'can_send_pm' => 'pm_send',
		'can_report_moderator' => 'report_any',
		'can_moderate_forum' => 'moderate_forum'
	);
	foreach ($common_permissions as $contextual => $perm)
		$context[$contextual] = allowedTo($perm);

	// Permissions with _any/_own versions.  $context[YYY] => ZZZ_any/_own.
	$anyown_permissions = array(
		'can_move' => 'move',
		'can_lock' => 'lock',
		'can_delete' => 'delete',
		'can_add_poll' => 'poll_add',
		'can_remove_poll' => 'poll_remove',
		'can_reply' => 'post_reply',
	);
	foreach ($anyown_permissions as $contextual => $perm)
		$context[$contextual] = allowedTo($perm . '_any') || ($context['user']['started'] && allowedTo($perm . '_own'));

	// Cleanup all the permissions with extra stuff...
	$context['can_sticky'] &= !empty($modSettings['enableStickyTopics']);
	$context['calendar_post'] &= !empty($modSettings['cal_enabled']);
	$context['can_add_poll'] &= $modSettings['pollMode'] == '1' && $topicinfo['ID_POLL'] <= 0;
	$context['can_remove_poll'] &= $modSettings['pollMode'] == '1' && $topicinfo['ID_POLL'] > 0;
	$context['can_reply'] &= empty($topicinfo['locked']) || allowedTo('moderate_board');

	// Start this off for quick moderation - it will be or'd for each post.
	$context['can_remove_post'] = allowedTo('remove_any') || (allowedTo('remove_replies') && $context['user']['started']);

	// Load the "Jump to" list...
	loadJumpTo();

	// Load up the "double post" sequencing magic.
	if (!empty($options['display_quick_reply']))
		checkSubmitOnce('register');
}

// Callback for the message display.
function prepareDisplayContext($reset = false)
{
	global $settings, $txt, $modSettings, $scripturl, $options;
	global $themeUser, $context, $messages_request, $topic, $ID_MEMBER, $attachments;

	static $counter = null;

	// If the query returned false, bail.
	if ($messages_request == false)
		return false;

	// Remember which message this is.  (ie. reply #83)
	if ($counter === null || $reset)
		$counter = empty($options['view_newest_first']) ? $context['start'] : $context['num_replies'] - $context['start'];

	// Start from the beginning...
	if ($reset)
		return @mysql_data_seek($messages_request, 0);

	// Attempt to get the next message.
	$message = mysql_fetch_assoc($messages_request);
	if (!$message)
		return false;

	// If you're a lazy bum, you probably didn't give a subject...
	$message['subject'] = $message['subject'] != '' ? $message['subject'] : $txt[24];

	// Are you allowed to remove at least a single reply?
	$context['can_remove_post'] |= allowedTo('remove_own') && $message['ID_MEMBER'] == $ID_MEMBER;

	// If it couldn't load, or the user was a guest.... someday may be done with a guest table.
	if (!loadMemberContext($message['ID_MEMBER']))
	{
		// Notice this information isn't used anywhere else....
		$themeUser[$message['ID_MEMBER']]['name'] = $message['posterName'];
		$themeUser[$message['ID_MEMBER']]['id'] = 0;
		$themeUser[$message['ID_MEMBER']]['group'] = $txt[28];
		$themeUser[$message['ID_MEMBER']]['link'] = $message['posterName'];
		$themeUser[$message['ID_MEMBER']]['email'] = $message['posterEmail'];
		$themeUser[$message['ID_MEMBER']]['is_guest'] = true;
	}
	else
		$themeUser[$message['ID_MEMBER']]['can_view_profile'] = allowedTo('profile_view_any') || ($message['ID_MEMBER'] == $ID_MEMBER && allowedTo('profile_view_own'));

	$themeUser[$message['ID_MEMBER']]['ip'] = $message['posterIP'];

	// Do the censor thang.
	censorText($message['body']);
	censorText($message['subject']);

	// Run BBC interpreter on the message.
	$message['body'] = doUBBC($message['body'], $message['smileysEnabled']);

	// Compose the memory eat- I mean message array.
	$output = array(
		'attachment' => loadAttachmentContext($message['ID_MSG']),
		'alternate' => $counter % 2,
		'id' => $message['ID_MSG'],
		'href' => $scripturl . '?topic=' . $topic . '.msg' . $message['ID_MSG'] . '#msg' . $message['ID_MSG'],
		'link' => '<a href="' . $scripturl . '?topic=' . $topic . '.msg' . $message['ID_MSG'] . '#msg' . $message['ID_MSG'] . '">' . $message['subject'] . '</a>',
		'member' => &$themeUser[$message['ID_MEMBER']],
		'icon' => $message['icon'],
		'subject' => $message['subject'],
		'time' => timeformat($message['posterTime']),
		'timestamp' => $message['posterTime'],
		'counter' => $counter,
		'modified' => array(
			'time' => timeformat($message['modifiedTime']),
			'timestamp' => $message['modifiedTime'],
			'name' => $message['modifiedName']
		),
		'body' => $message['body'],
		'new' => empty($message['isRead']),
		'first_new' => isset($context['start_from']) && $context['start_from'] == $counter,
		'can_modify' => allowedTo('modify_any') || (allowedTo('modify_replies') && $context['user']['started']) || (allowedTo('modify_own') && $message['ID_MEMBER'] == $ID_MEMBER),
		'can_remove' => allowedTo('remove_any') || (allowedTo('remove_replies') && $context['user']['started']) || (allowedTo('remove_own') && $message['ID_MEMBER'] == $ID_MEMBER),
		'can_see_ip' => allowedTo('moderate_forum') || ($message['ID_MEMBER'] == $ID_MEMBER && !empty($ID_MEMBER)),
	);

	if (empty($options['view_newest_first']))
		$counter++;
	else
		$counter--;

	return $output;
}

// Download an attachment.
function Download()
{
	global $txt, $modSettings, $db_prefix, $user_info, $scripturl, $context;

	// Make sure some attachment was requested!
	if (!isset($_REQUEST['id']))
		fatal_lang_error(1, false);

	$_REQUEST['id'] = (int) $_REQUEST['id'];

	if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'avatar')
	{
		$request = db_query("
			SELECT filename, ID_ATTACH
			FROM {$db_prefix}attachments
			WHERE ID_ATTACH = $_REQUEST[id]
				AND ID_MEMBER > 0
			LIMIT 1", __FILE__, __LINE__);
		$_REQUEST['image'] = true;
	}
	// This is just a regular attachment...
	else
	{
		isAllowedTo('view_attachments');

		// Make sure this attachment is on this board.
		$request = db_query("
			SELECT a.filename, a.ID_ATTACH
			FROM {$db_prefix}boards AS b, {$db_prefix}messages AS m, {$db_prefix}attachments AS a
			WHERE b.ID_BOARD = m.ID_BOARD
				AND $user_info[query_see_board]
				AND m.ID_MSG = a.ID_MSG
				AND a.ID_ATTACH = $_REQUEST[id]
			LIMIT 1", __FILE__, __LINE__);
	}
	if (mysql_num_rows($request) == 0)
		fatal_lang_error(1, false);
	list ($real_filename, $ID_ATTACH) = mysql_fetch_row($request);
	mysql_free_result($request);

	// Update the download counter.
	db_query("
		UPDATE {$db_prefix}attachments
		SET downloads = downloads + 1
		WHERE ID_ATTACH = $ID_ATTACH", __FILE__, __LINE__);

	// This is done to clear any output that was made before now. (would use ob_clean(), but that's PHP 4.2.0+...)
	ob_end_clean();
	if (!empty($modSettings['enableCompressedOutput']) && @version_compare(PHP_VERSION, '4.2.0') >= 0)
		@ob_start('ob_gzhandler');
	else
		ob_start();

	$filename = getAttachmentFilename($real_filename, $_REQUEST['id']);

	// No point in a nicer message, because this is supposed to be an attachment anyway...
	if (!file_exists($filename))
	{
		loadLanguage('Errors');
		header('HTTP/1.0 404 ' . $txt['attachment_not_found']);
		header('Content-Type: text/plain');
		die('404 - ' . $txt['attachment_not_found']);
	}

	// Send the attachment headers.
	header('Pragma: ');
	header('Cache-Control: max-age=' . (525600 * 60) . ', private');
	if (!$context['browser']['is_gecko'])
		header('Content-Transfer-Encoding: binary');
	header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 525600 * 60) . ' GMT');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($filename)) . ' GMT');
	header('Accept-Ranges: bytes');
	header('Set-Cookie:');
	header('Connection: close');

	if (!isset($_REQUEST['image']))
	{
		header('Content-Disposition: attachment; filename="' . $real_filename . '"');
		header('Content-Type: application/octet-stream');
	}

	if (filesize($filename) != 0)
	{
		$size = @getimagesize($filename);
		if (!empty($size) && $size[2] > 0 && $size[2] < 4)
			header('Content-Type: image/' . ($size[2] != 1 ? ($size[2] != 2 ? 'png' : 'jpeg') : 'gif'));
	}

	if (empty($modSettings['enableCompressedOutput']))
		header('Content-Length: ' . filesize($filename));

	// Try to buy some time...
	@set_time_limit(0);
	@ini_set('memory_limit', '128M');

	// On some of the less-bright hosts, readfile() is disabled.  It's just a faster, more byte safe, version of what's in the if.
	if (@readfile($filename) === false)
		echo implode('', file($filename));

	obExit(false);
}

function loadAttachmentContext($ID_MSG)
{
	global $attachments, $modSettings, $txt, $scripturl, $topic;

	// Set up the attachment info - based on code by Meriadoc.
	$attachmentData = array();
	if (isset($attachments[$ID_MSG]) && !empty($modSettings['attachmentEnable']))
	{
		foreach ($attachments[$ID_MSG] as $i => $attachment)
		{
			$attachmentData[$i] = array(
				'name' => $attachment['filename'],
				'downloads' => $attachment['downloads'],
				'size' => round($attachment['filesize'] / 1024, 2) . ' ' . $txt['smf211'],
				'byte_size' => $attachment['filesize'],
				'href' => $scripturl . '?action=dlattach;topic=' . $topic . '.0;id=' . $attachment['ID_ATTACH'],
				'link' => '<a href="' . $scripturl . '?action=dlattach;topic=' . $topic . '.0;id=' . $attachment['ID_ATTACH'] . '">' . $attachment['filename'] . '</a>',
				'is_image' => false
			);

			if (empty($modSettings['attachmentShowImages']))
				continue;

			// Set up the image attachment info.
			$filename = getAttachmentFilename($attachment['filename'], $attachment['ID_ATTACH']);

			$imageTypes = array('gif' => 1, 'jpeg' => 2, 'png' => 3, 'bmp' => 6);
			if (file_exists($filename) && filesize($filename) > 0)
				list ($width, $height, $imageType) = @getimagesize($filename);
			else
			{
				$imageType = 0;
				$attachmentData[$i]['size'] = '0 ' . $txt['smf211'];
			}

			// If this isn't an image, we're done.
			if (!allowedTo('view_attachments') || !in_array($imageType, $imageTypes))
				continue;

			// Start resize/restrict posted images mod by Mostmaster.
			if (!(empty($modSettings['maxwidth']) && empty($modSettings['maxheight'])) && ($width > $modSettings['maxwidth'] || $height > $modSettings['maxheight']))
			{
				if ($width > $modSettings['maxwidth'] && !empty($modSettings['maxwidth']))
				{
					$height = floor($modSettings['maxwidth'] / $width * $height);
					$width = $modSettings['maxwidth'];
					if ($height > $modSettings['maxheight'] && !empty($modSettings['maxheight']))
					{
						$width = floor($modSettings['maxheight'] / $height * $width);
						$height = $modSettings['maxheight'];
					}
				}
				elseif ($height > $modSettings['maxheight'] && !empty($modSettings['maxheight']))
				{
					$width = floor($modSettings['maxheight'] / $height * $width);
					$height = $modSettings['maxheight'];
				}

				$attachmentData[$i]['image'] = '<img src="' . $attachmentData[$i]['href'] . ';image" alt="" width="' . $width . '" height="' . $height . '" />';
			}
			else
				$attachmentData[$i]['image'] = '<img src="' . $attachmentData[$i]['href'] . ';image" alt="" />';
			// End resize/restrict posted images mod by Mostmaster.

			$attachmentData[$i]['width'] = $width;
			$attachmentData[$i]['height'] = $height;
			$attachmentData[$i]['is_image'] = true;
			$attachmentData[$i]['downloads']++;
		}
	}

	return $attachmentData;
}

?>