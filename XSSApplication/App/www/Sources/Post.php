<?php
/******************************************************************************
* Post.php                                                                    *
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

/*	The job of this file is to handle everything related to posting replies,
	new topics, quotes, and modifications to existing posts.  It also handles
	quoting posts by way of a popup.

	void Post()
		- handles showing the post screen, loading the post to be modified, and
		  loading any post quoted.
		- additionally handles previews of posts.
		- uses the Post template and language file, main sub template.
		- allows wireless access using the protocol_post sub template.
		- requires different permissions depending on the actions, but most
		  notably post_new, post_reply_own, and post_reply_any.
		- shows options for the editing and posting of calendar events and
		  attachments, as well as the posting of polls.
		- accessed from ?action=post.

	void Post2()
		- actually posts or saves the message composed with Post().
		- requires various permissions depending on the action.
		- handles attachment, post, and calendar saving.
		- sends off notifications, and allows for announcements and moderation.
		- accessed from ?action=post2.

	void AnnounceTopic()
		- handle the announce topic function (action=announce).
		- checks the topic announcement permissions and loads the announcement
		  template.
		- requires the announce_topic permission.
		- uses the ManageMembers template and Post language file.
		- call the right function based on the sub-action.

	void AnnouncementSelectMembergroup()
		- lets the user select the membergroups that will receive the topic
		  announcement.

	void AnnouncementSend()
		- splits the members to be sent a topic announcement into chunks.
		- composes notification messages in all languages needed.
		- does the actual sending of the topic announcements in chunks.
		- calculates a rough estimate of the percentage items sent.

	void notifyUsersBoard()
		- notifies members who have requested notification for new topics
		  posted on a board of said posts.
		- only sends notifications to those who can *currently* see the topic
		  (it doesn't matter if they could when they requested notification.)
		- loads the Post language file multiple times for each language if the
		  userLanguage setting is set.

	void getTopic()
		- gets a summary of the most recent posts in a topic.
		- depends on the topicSummaryPosts setting.
		- if you are editing a post, only shows posts previous to that post.

	void QuoteFast()
		- loads a post an inserts it into the current editing text box.
		- uses the Post language file.
		- uses special (sadly browser dependent) javascript to parse entities
		  for internationalization reasons.
		- accessed with ?action=quotefast.
*/

function Post()
{
	global $txt, $scripturl, $topic, $db_prefix, $modSettings, $board, $ID_MEMBER, $user_info;
	global $sc, $board_info, $context, $settings, $sourcedir;

	loadLanguage('Post');

	$context['show_spellchecking'] = $modSettings['enableSpellChecking'] && function_exists('pspell_new');

	// You can't reply with a poll... hacker.
	if (isset($_REQUEST['poll']) && !empty($topic) && !isset($_REQUEST['msg']))
		unset($_REQUEST['poll']);

	// Posting an event?
	$context['make_event'] = isset($_REQUEST['calendar']);

	// You must be posting to *some* board.
	if (empty($board) && !$context['make_event'])
		fatal_lang_error('smf232', false);

	require_once($sourcedir . '/Subs-Post.php');

	if (WIRELESS)
		$context['sub_template'] = WIRELESS_PROTOCOL . '_post';
	else
		loadTemplate('Post');

	// Check if it's locked.  It isn't locked if no topic is specified.
	if (!empty($topic))
	{
		$request = db_query("
			SELECT
				t.locked, IFNULL(ln.ID_TOPIC, 0) AS notify, t.isSticky, t.ID_POLL, t.numReplies, m.ID_MEMBER,
				t.ID_FIRST_MSG, m.subject
			FROM {$db_prefix}topics AS t
				LEFT JOIN {$db_prefix}log_notify AS ln ON (ln.ID_TOPIC = t.ID_TOPIC AND ln.ID_MEMBER = $ID_MEMBER)
				LEFT JOIN {$db_prefix}messages AS m ON (m.ID_MSG = t.ID_FIRST_MSG)
			WHERE t.ID_TOPIC = $topic
			LIMIT 1", __FILE__, __LINE__);
		list ($locked, $context['notify'], $sticky, $pollID, $context['num_replies'], $ID_MEMBER_POSTER, $ID_FIRST_MSG, $first_subject) = mysql_fetch_row($request);
		mysql_free_result($request);

		// If this topic already has a poll, they sure can't add another.
		if (isset($_REQUEST['poll']) && $pollID > 0)
			unset($_REQUEST['poll']);

		if (empty($_REQUEST['msg']))
		{
			if ($user_info['is_guest'] && !allowedTo('post_reply_any'))
				is_not_guest();

			if ($ID_MEMBER_POSTER != $ID_MEMBER)
				isAllowedTo('post_reply_any');
			elseif (!allowedTo('post_reply_any'))
				isAllowedTo('post_reply_own');
		}

		$context['can_lock'] = allowedTo('lock_any') || ($ID_MEMBER == $ID_MEMBER_POSTER && allowedTo('lock_own'));
		$context['can_sticky'] = allowedTo('make_sticky') && !empty($modSettings['enableStickyTopics']);

		$context['notify'] = !empty($context['notify']);
		$context['sticky'] = isset($_REQUEST['subject']) ? !empty($_REQUEST['sticky']) : $sticky;
	}
	else
	{
		if (!$context['make_event'])
			isAllowedTo('post_new');

		$locked = 0;
		$context['can_lock'] = allowedTo(array('lock_any', 'lock_own'));
		$context['can_sticky'] = allowedTo('make_sticky') && !empty($modSettings['enableStickyTopics']);

		$context['notify'] = !empty($context['notify']);
		$context['sticky'] = !empty($_REQUEST['sticky']);
	}
	$context['can_move'] = allowedTo('move_any');
	$context['can_notify'] = allowedTo('mark_any_notify');
	$context['can_announce'] = allowedTo('announce_topic');
	$context['locked'] = !empty($locked) || !empty($_REQUEST['lock']);

	// An array to hold all the attachments for this topic.
	$context['current_attachments'] = array();

	// Don't allow a post if it's locked and you aren't all powerful.
	if ($locked && !allowedTo('moderate_board'))
		fatal_lang_error(90, false);

	// Check the users permissions - is the user allowed to add or post a poll?
	if (isset($_REQUEST['poll']) && $modSettings['pollMode'] == '1')
	{
		// New topic, new poll.
		if (empty($topic))
			isAllowedTo('poll_post');
		// This is an old topic - but it is yours!  Can you add to it?
		elseif ($ID_MEMBER == $ID_MEMBER_POSTER && !allowedTo('poll_add_any'))
			isAllowedTo('poll_add_own');
		// If you're not the owner, can you add to any poll?
		else
			isAllowedTo('poll_add_any');

		// Set up the poll options.
		$context['poll_options'] = array(
			'max_votes' => empty($_POST['poll_max_votes']) ? '1' : $_POST['poll_max_votes'],
			'hide' => empty($_POST['poll_hide']) ? 0 : $_POST['poll_hide'],
			'expire' => !isset($_POST['poll_expire']) ? '' : $_POST['poll_expire'],
			'change_vote' => isset($_POST['poll_change_vote'])
		);

		// Make all five poll choices empty.
		$context['choices'] = array(
			array('id' => 0, 'number' => 1, 'label' => '', 'is_last' => false),
			array('id' => 1, 'number' => 2, 'label' => '', 'is_last' => false),
			array('id' => 2, 'number' => 3, 'label' => '', 'is_last' => false),
			array('id' => 3, 'number' => 4, 'label' => '', 'is_last' => false),
			array('id' => 4, 'number' => 5, 'label' => '', 'is_last' => true)
		);
	}

	if ($context['make_event'])
	{
		// They might want to pick a board.
		if (!isset($context['current_board']))
			$context['current_board'] = 0;

		// Start loading up the event info.
		$context['event'] = array();
		$context['event']['title'] = isset($_REQUEST['evtitle']) ? $_REQUEST['evtitle'] : '';

		$context['event']['id'] = isset($_REQUEST['eventid']) ? (int) $_REQUEST['eventid'] : -1;
		$context['event']['new'] = $context['event']['id'] == -1;

		// Permissions check!
		isAllowedTo('calendar_post');

		// Editing an event?  (but NOT previewing!?)
		if (!$context['event']['new'] && !isset($_REQUEST['subject']))
		{
			// Get the current event information.
			$request = db_query("
				SELECT
					title, MONTH(eventDate) AS month, DAYOFMONTH(eventDate) AS day,
					YEAR(eventDate) AS year, ID_MEMBER
				FROM {$db_prefix}calendar
				WHERE ID_EVENT = " . $context['event']['id'] . "
				LIMIT 1", __FILE__, __LINE__);
			$row = mysql_fetch_assoc($request);
			mysql_free_result($request);

			// Make sure the user is allowed to edit this event.
			if ($row['ID_MEMBER'] != $ID_MEMBER)
				isAllowedTo('calendar_edit_any');
			elseif (!allowedTo('calendar_edit_any'))
				isAllowedTo('calendar_edit_own');

			$context['event']['month'] = $row['month'];
			$context['event']['day'] = $row['day'];
			$context['event']['year'] = $row['year'];
			$context['event']['title'] = $row['title'];
		}
		else
		{
			$today = getdate();

			// You must have a month and year specified!
			if (!isset($_REQUEST['month']))
				$_REQUEST['month'] = $today['mon'];
			if (!isset($_REQUEST['year']))
				$_REQUEST['year'] = $today['year'];

			$context['event']['month'] = (int) $_REQUEST['month'];
			$context['event']['year'] = (int) $_REQUEST['year'];
			$context['event']['day'] = isset($_REQUEST['day']) ? $_REQUEST['day'] : ($_REQUEST['month'] == $today['mon'] ? $today['mday'] : 0);

			// Make sure the year and month are in the valid range.
			if ($context['event']['month'] < 1 || $context['event']['month'] > 12)
				fatal_lang_error('calendar1', false);
			if ($context['event']['year'] < $modSettings['cal_minyear'] || $context['event']['year'] > $modSettings['cal_maxyear'])
				fatal_lang_error('calendar2', false);

			// Get a list of boards they can post in.
			$boards = boardsAllowedTo('post_new');
			if (empty($boards))
				fatal_lang_error('cannot_post_new');
			$request = db_query("
				SELECT c.name as catName, c.ID_CAT, b.ID_BOARD, b.name AS boardName, b.childLevel
				FROM {$db_prefix}boards AS b, {$db_prefix}categories AS c
				WHERE c.ID_CAT = b.ID_CAT" . (in_array(0, $boards) ? '' : "
					AND b.ID_BOARD IN (" . implode(', ', $boards) . ")") . "
					AND $user_info[query_see_board]
				ORDER BY c.catOrder, b.boardOrder", __FILE__, __LINE__);
			$context['event']['boards'] = array();
			while ($row = mysql_fetch_assoc($request))
				$context['event']['boards'][] = array(
					'id' => $row['ID_BOARD'],
					'name' => $row['boardName'],
					'childLevel' => $row['childLevel'],
					'prefix' => str_repeat('&nbsp;', $row['childLevel'] * 3),
					'cat' => array(
						'id' => $row['ID_CAT'],
						'name' => $row['catName']
					)
				);
			mysql_free_result($request);
		}

		// Find the last day of the month.
		$context['event']['last_day'] = (int) strftime('%d', mktime(0, 0, 0, $context['event']['month'] == 12 ? 1 : $context['event']['month'] + 1, 0, $context['event']['month'] == 12 ? $context['event']['year'] + 1 : $context['event']['year']));

		$context['event']['board'] = !empty($board) ? $board : $modSettings['cal_defaultboard'];
		$context['event']['span'] = isset($_REQUEST['span']) ? $_REQUEST['span'] : 1;
	}

	if (empty($context['post_errors']))
		$context['post_errors'] = array();

	// See if any new replies have come along.
	if (empty($_REQUEST['msg']) && !empty($topic) && !empty($modSettings['enableNewReplyWarning']) && isset($_REQUEST['num_replies']))
	{
		$newReplies = $context['num_replies'] > $_REQUEST['num_replies'] ? $context['num_replies'] - $_REQUEST['num_replies'] : 0;

		if (!empty($newReplies))
		{
			if ($newReplies == 1)
				$txt['error_new_reply'] = isset($_GET['num_replies']) ? $txt['error_new_reply_reading'] : $txt['error_new_reply'];
			else
				$txt['error_new_replies'] = sprintf(isset($_GET['num_replies']) ? $txt['error_new_replies_reading'] : $txt['error_new_replies'], $newReplies);

			// If they've come from the display page then we treat the error differently....
			if (isset($_GET['num_replies']))
				$newRepliesError = $newReplies;
			else
				$context['post_error'][$newReplies == 1 ? 'new_reply' : 'new_replies'] = true;

			$modSettings['topicSummaryPosts'] = $newReplies > $modSettings['topicSummaryPosts'] ? max($modSettings['topicSummaryPosts'], 5) : $modSettings['topicSummaryPosts'];
		}
	}

	// Previewing, modifying, or posting?
	if (isset($_REQUEST['subject']) || !empty($context['post_error']))
	{
		// Validate inputs.
		if (empty($context['post_error']))
		{
			if (htmltrim__recursive($_REQUEST['subject']) == '')
				$context['post_error']['no_subject'] = true;
			if (htmltrim__recursive($_REQUEST['message']) == '')
				$context['post_error']['no_message'] = true;
			if (!empty($modSettings['max_messageLength']) && strlen($_REQUEST['message']) > $modSettings['max_messageLength'])
				$context['post_error']['long_message'] = true;

			// Are you... a guest?
			if ($user_info['is_guest'])
			{
				$_REQUEST['guestname'] = !isset($_REQUEST['guestname']) ? '' : trim($_REQUEST['guestname']);
				$_REQUEST['email'] = !isset($_REQUEST['email']) ? '' : trim($_REQUEST['email']);

				// Validate the name and email.
				if (!isset($_REQUEST['guestname']) || trim(strtr($_REQUEST['guestname'], '_', ' ')) == '')
					$context['post_error']['no_name'] = true;
				elseif (strlen($_REQUEST['guestname']) > 25)
					$context['post_error']['long_name'] = true;
				elseif (isReservedName(htmlspecialchars($_REQUEST['guestname'])))
					$context['post_error']['bad_name'] = true;

				if (!isset($_REQUEST['email']) || $_REQUEST['email'] == '')
					$context['post_error']['no_email'] = true;
				elseif (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]+@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_REQUEST['email'])) == 0)
					$context['post_error']['bad_email'] = true;
			}

			// This is self explanatory - got any questions?
			if (isset($_REQUEST['poll']) && (!isset($_REQUEST['question']) || trim($_REQUEST['question']) == ''))
				$context['post_error']['no_question'] = true;

			// This means they didn't click Post and get an error.
			$really_previewing = true;
		}
		else
		{
			if (!isset($_REQUEST['subject']))
				$_REQUEST['subject'] = '';
			if (!isset($_REQUEST['message']))
				$_REQUEST['message'] = '';
			if (!isset($_REQUEST['icon']))
				$_REQUEST['icon'] = 'xx';

			$really_previewing = false;
		}

		// Any errors occurred?
		if (!empty($context['post_error']))
		{
			loadLanguage('Errors');

			$context['error_type'] = 'minor';

			$context['post_error']['messages'] = array();
			foreach ($context['post_error'] as $post_error => $dummy)
			{
				if ($post_error == 'messages')
					continue;

				$context['post_error']['messages'][] = $txt['error_' . $post_error];

				// If it's not a minor error flag it as such.
				if ($post_error != 'new_reply' && $post_error != 'new_replies')
					$context['error_type'] = 'serious';
			}
		}

		// Set up the inputs for the form.
		$form_subject = htmlspecialchars(stripslashes($_REQUEST['subject']));
		$form_message = htmlspecialchars(stripslashes($_REQUEST['message']), ENT_QUOTES);

		// Cheating ;).
		$form_subject = preg_replace('~&amp;#(\d{4,5}|[3-9]\d{2,4}|2[6-9]\d);~', '&#$1;', $form_subject);

		// Make sure the subject isn't too long.
		if (strlen($form_subject) > 100)
			$form_subject = substr($form_subject, 0, 100);

		if (isset($_REQUEST['poll']))
		{
			$context['question'] = isset($_REQUEST['question']) ? htmlspecialchars(stripslashes(trim($_REQUEST['question']))) : '';
			$context['question'] = preg_replace('~&amp;#(\d{4,5}|[3-9]\d{2,4}|2[6-9]\d);~', '&#$1;', $context['question']);

			$context['choices'] = array();
			$choice_id = 0;

			$_POST['options'] = empty($_POST['options']) ? array() : htmlspecialchars__recursive(stripslashes__recursive($_POST['options']));
			foreach ($_POST['options'] as $option)
			{
				if ($option == '')
					continue;

				$option = preg_replace('~&amp;#(\d{4,5}|[3-9]\d{2,4}|2[6-9]\d);~', '&#$1;', $option);

				$context['choices'][] = array(
					'id' => $choice_id++,
					'number' => $choice_id,
					'label' => $option,
					'is_last' => false
				);
			}

			if (count($context['choices']) < 2)
			{
				$context['choices'][] = array(
					'id' => $choice_id++,
					'number' => $choice_id,
					'label' => '',
					'is_last' => false
				);
				$context['choices'][] = array(
					'id' => $choice_id++,
					'number' => $choice_id,
					'label' => '',
					'is_last' => false
				);
			}
			$context['choices'][count($context['choices']) - 1]['is_last'] = true;
		}

		// Are you... a guest?
		if ($user_info['is_guest'])
		{
			$_REQUEST['guestname'] = !isset($_REQUEST['guestname']) ? '' : trim($_REQUEST['guestname']);
			$_REQUEST['email'] = !isset($_REQUEST['email']) ? '' : trim($_REQUEST['email']);

			$_REQUEST['guestname'] = htmlspecialchars($_REQUEST['guestname']);
			$context['name'] = $_REQUEST['guestname'];
			$_REQUEST['email'] = htmlspecialchars($_REQUEST['email']);
			$context['email'] = $_REQUEST['email'];

			$user_info['name'] = $_REQUEST['guestname'];
		}

		// Only show the preview stuff if they hit Preview.
		if ($really_previewing == true)
		{
			// Set up the preview message and subject and censor them...
			preparsecode($form_message, false);
			$context['preview_message'] = $form_message;

			// Do all bulletin board code tags, with or without smileys.
			$context['preview_message'] = doUBBC($context['preview_message'], isset($_REQUEST['ns']) ? 0 : 1);

			if ($form_subject != '')
			{
				$context['preview_subject'] = $form_subject;

				censorText($context['preview_subject']);
				censorText($context['preview_message']);
			}
			else
				$context['preview_subject'] = '<i>' . $txt[24] . '</i>';
		}

		// Set up the checkboxes.
		$context['notify'] = !empty($_REQUEST['notify']);
		$context['use_smileys'] = !isset($_REQUEST['ns']);

		$context['icon'] = $_REQUEST['icon'];

		// Set the destination action for submission.
		$context['destination'] = 'post2;start=' . $_REQUEST['start'] . (isset($_REQUEST['msg']) ? ';msg=' . $_REQUEST['msg'] . ';sesc=' . $sc : '') . (isset($_REQUEST['poll']) ? ';poll' : '');
		$context['submit_label'] = isset($_REQUEST['msg']) ? $txt[10] : $txt[105];

		// Previewing an edit?
		if (isset($_REQUEST['msg']))
		{
			if (!empty($modSettings['attachmentEnable']))
			{
				$request = db_query("
					SELECT IFNULL(size, -1) AS filesize, filename, ID_ATTACH
					FROM {$db_prefix}attachments
					WHERE ID_MSG = " . (int) $_REQUEST['msg'], __FILE__, __LINE__);
				while ($row = mysql_fetch_assoc($request))
				{
					if ($row['filesize'] <= 0)
						continue;
					$context['current_attachments'][] = array(
						'name' => $row['filename'],
						'id' => $row['ID_ATTACH']
					);
				}
				mysql_free_result($request);
			}

			// Allow moderators to change names....
			if (allowedTo('moderate_forum'))
			{
				$request = db_query("
					SELECT ID_MEMBER, posterName, posterEmail
					FROM {$db_prefix}messages
					WHERE ID_MSG = " . (int) $_REQUEST['msg'] . "
						AND ID_TOPIC = $topic
					LIMIT 1", __FILE__, __LINE__);
				$row = mysql_fetch_assoc($request);
				mysql_free_result($request);

				if (empty($row['ID_MEMBER']))
				{
					$context['name'] = htmlspecialchars($row['posterName']);
					$context['email'] = htmlspecialchars($row['posterEmail']);
				}
			}
		}

		// No check is needed, since nothing is really posted.
		checkSubmitOnce('free');
	}
	// Editing a message...
	elseif (isset($_REQUEST['msg']))
	{
		checkSession('get');

		// Get the existing message.
		$request = db_query("
			SELECT
				m.ID_MEMBER, m.modifiedTime, m.smileysEnabled, m.body,
				m.posterName, m.posterEmail, m.subject, m.icon,
				IFNULL(a.size, -1) AS filesize, a.filename, a.ID_ATTACH,
				t.ID_MEMBER_STARTED AS ID_MEMBER_POSTER
			FROM {$db_prefix}messages AS m, {$db_prefix}topics AS t
				LEFT JOIN {$db_prefix}attachments AS a ON (a.ID_MSG = m.ID_MSG)
			WHERE m.ID_MSG = " . (int) $_REQUEST['msg'] . "
				AND m.ID_TOPIC = $topic
				AND t.ID_TOPIC = $topic", __FILE__, __LINE__);
		if (mysql_num_rows($request) == 0)
			fatal_lang_error('smf232');
		$row = mysql_fetch_assoc($request);

		$attachment_stuff = array($row);
		while ($row2 = mysql_fetch_assoc($request))
			$attachment_stuff[] = $row2;
		mysql_free_result($request);

		if ($row['ID_MEMBER'] == $ID_MEMBER && !allowedTo('modify_any'))
		{
			if ($row['ID_MEMBER_POSTER'] == $ID_MEMBER && !allowedTo('modify_own'))
				isAllowedTo('modify_replies');
			else
				isAllowedTo('modify_own');
		}
		elseif ($row['ID_MEMBER_POSTER'] == $ID_MEMBER && !allowedTo('modify_any'))
			isAllowedTo('modify_replies');
		else
			isAllowedTo('modify_any');

		// When was it last modified?
		if (!empty($row['modifiedTime']))
			$context['last_modified'] = timeformat($row['modifiedTime']);

		// Get the stuff ready for the form.
		$form_subject = $row['subject'];
		$form_message = preg_replace('|<br(?: /)?>|', "\n", $row['body']);
		censorText($form_message);
		censorText($form_subject);

		// Check the boxes that should be checked.
		$context['use_smileys'] = !empty($row['smileysEnabled']);
		$context['icon'] = $row['icon'];

		// Load up 'em attachments!
		foreach ($attachment_stuff as $attachment)
		{
			if ($attachment['filesize'] >= 0 && !empty($modSettings['attachmentEnable']))
				$context['current_attachments'][] = array(
					'name' => $attachment['filename'],
					'id' => $attachment['ID_ATTACH']
				);
		}

		// Allow moderators to change names....
		if (allowedTo('moderate_forum') && empty($row['ID_MEMBER']))
		{
			$context['name'] = htmlspecialchars($row['posterName']);
			$context['email'] = htmlspecialchars($row['posterEmail']);
		}

		// Set the destinaton.
		$context['destination'] = 'post2;start=' . $_REQUEST['start'] . ';msg=' . $_REQUEST['msg'] . ';sesc=' . $sc . (isset($_REQUEST['poll']) ? ';poll' : '');
		$context['submit_label'] = $txt[10];
	}
	// Posting...
	else
	{
		// By default....
		$context['use_smileys'] = true;
		$context['icon'] = 'xx';

		if ($user_info['is_guest'])
			$context['name'] = $context['email'] = '';
		$context['destination'] = 'post2;start=' . $_REQUEST['start'] . (isset($_REQUEST['poll']) ? ';poll' : '');

		$context['submit_label'] = $txt[105];

		// Posting a quoted reply?
		if (!empty($topic) && !empty($_REQUEST['quote']))
		{
			checkSession('get');

			// Make sure they _can_ quote this post, and if so get it.
			$request = db_query("
				SELECT m.subject, IFNULL(mem.realName, m.posterName) AS posterName, m.posterTime, m.body
				FROM {$db_prefix}messages AS m, {$db_prefix}boards AS b
					LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = m.ID_MEMBER)
				WHERE m.ID_MSG = " . (int) $_REQUEST['quote'] . "
					AND b.ID_BOARD = m.ID_BOARD
					AND $user_info[query_see_board]
				LIMIT 1", __FILE__, __LINE__);
			if (mysql_num_rows($request) == 0)
				fatal_lang_error('smf232');
			list ($form_subject, $mname, $mdate, $form_message) = mysql_fetch_row($request);
			mysql_free_result($request);

			// Add 'Re: ' to the front of the quoted subject.
			if (trim($txt['response_prefix']) != '' && strpos($form_subject, trim($txt['response_prefix'])) !== 0)
				$form_subject = $txt['response_prefix'] . $form_subject;

			// Censor the message and subject.
			censorText($form_message);
			censorText($form_subject);

			$form_message = preg_replace('~<br(?: /)?>~i', "\n", $form_message);

			// Remove any nested quotes, if necessary.
			if (!empty($modSettings['removeNestedQuotes']))
				$form_message = preg_replace(array('~\n?\[quote.*?\].+?\[/quote\]\n?~is', '~^\n~', '~\[/quote\]~'), '', $form_message);

			// Add a quote string on the front and end.
			$form_message = '[quote author=' . $mname . ' link=topic=' . $topic . '.msg' . (int) $_REQUEST['quote'] . '#msg' . (int) $_REQUEST['quote'] . ' date=' . $mdate . ']' . "\n" . $form_message . "\n" . '[/quote]';
		}
		// Posting a reply without a quote?
		elseif (!empty($topic) && empty($_REQUEST['quote']))
		{
			// Get the first message's subject.
			$form_subject = $first_subject;

			// Add 'Re: ' to the front of the subject.
			if (trim($txt['response_prefix']) != '' && $form_subject != '' && strpos($form_subject, trim($txt['response_prefix'])) !== 0)
				$form_subject = $txt['response_prefix'] . $form_subject;

			// Censor the subject.
			censorText($form_subject);

			$form_message = '';
		}
		else
			$form_message = $form_subject = '';
	}

	// If we are coming here to make a reply, and someone has already replied... make a special warning message.
	if (isset($newRepliesError))
	{
		$context['post_error']['messages'][] = $newRepliesError == 1 ? $txt['error_new_reply'] : $txt['error_new_replies'];
		$context['error_type'] = 'minor';
	}

	// What are you doing?  Posting a poll, modifying, previewing, new post, or reply...
	if (isset($_REQUEST['poll']))
		$context['page_title'] = $txt['smf20'];
	elseif ($context['make_event'])
		$context['page_title'] = $context['event']['id'] == -1 ? $txt['calendar23'] : $txt['calendar20'];
	elseif (isset($_REQUEST['msg']))
		$context['page_title'] = $txt[66];
	elseif (isset($_REQUEST['subject']) && isset($context['preview_subject']))
		$context['page_title'] = $txt[507] . ' - ' . strip_tags($context['preview_subject']);
	elseif (empty($topic))
		$context['page_title'] = $txt[33];
	else
		$context['page_title'] = $txt[25];

	// Build the link tree.
	if (empty($topic))
		$context['linktree'][] = array(
			'name' => '<i>' . $txt[33] . '</i>'
		);
	else
		$context['linktree'][] = array(
			'url' => $scripturl . '?topic=' . $topic . '.' . $_REQUEST['start'],
			'name' => $form_subject,
			'extra_before' => '<span' . ($settings['linktree_inline'] ? ' class="smalltext"' : '') . '><b class="nav">' . $context['page_title'] . ' ( </b></span>',
			'extra_after' => '<span' . ($settings['linktree_inline'] ? ' class="smalltext"' : '') . '><b class="nav"> )</b></span>'
		);

	$context['num_allowed_attachments'] = $modSettings['attachmentNumPerPostLimit'] - count($context['current_attachments']);
	$context['can_post_attachment'] = !empty($modSettings['attachmentEnable']) && $modSettings['attachmentEnable'] == 1 && allowedTo('post_attachment') && $context['num_allowed_attachments'] > 0;

	$context['subject'] = addcslashes($form_subject, '"');
	$context['message'] = str_replace(array('"', '<', '>', '  '), array('&quot;', '&lt;', '&gt;', ' &nbsp;'), $form_message);
	$context['attached'] = stripslashes(isset($_REQUEST['attachmentPreview']) ? $_REQUEST['attachmentPreview'] : '');
	$context['allowed_extensions'] = strtr($modSettings['attachmentExtensions'], array(',' => ', '));
	$context['make_poll'] = isset($_REQUEST['poll']);

	$context['icons'] = array(
		array('value' => 'xx', 'name' => $txt[281]),
		array('value' => 'thumbup', 'name' => $txt[282]),
		array('value' => 'thumbdown', 'name' => $txt[283]),
		array('value' => 'exclamation', 'name' => $txt[284]),
		array('value' => 'question', 'name' => $txt[285]),
		array('value' => 'lamp', 'name' => $txt[286]),
		array('value' => 'smiley', 'name' => $txt[287]),
		array('value' => 'angry', 'name' => $txt[288]),
		array('value' => 'cheesy', 'name' => $txt[289]),
		array('value' => 'grin', 'name' => $txt[293]),
		array('value' => 'sad', 'name' => $txt[291]),
		array('value' => 'wink', 'name' => $txt[292])
	);

	$found = false;
	for ($i = 0, $n = count($context['icons']); $i < $n; $i++)
	{
		$context['icons'][$i]['selected'] = $context['icon'] == $context['icons'][$i]['value'];
		if ($context['icons'][$i]['selected'])
			$found = true;
	}
	if (!$found)
		array_unshift($context['icons'], array('value' => $context['icon'], 'name' => $txt['current_icon'], 'selected' => true));

	if (isset($topic))
		getTopic();

	$context['back_to_topic'] = isset($_REQUEST['goback']) || (isset($_REQUEST['msg']) && !isset($_REQUEST['subject']));

	$context['is_new_topic'] = empty($topic);
	$context['is_new_post'] = !isset($_REQUEST['msg']);
	$context['is_first_post'] = $context['is_new_topic'] || (isset($_REQUEST['msg']) && $_REQUEST['msg'] == $ID_FIRST_MSG);

	// Register this form in the session variables.
	checkSubmitOnce('register');
}

function Post2()
{
	global $board, $topic, $txt, $db_prefix, $modSettings, $sourcedir, $context;
	global $ID_MEMBER, $user_info, $board_info;

	// No errors as yet.
	$post_errors = array();

	// If the session has timed out, let the user re-submit their form.
	if (checkSession('post', '', false) != '')
		$post_errors[] = 'session_timeout';

	require_once($sourcedir . '/Subs-Post.php');
	loadLanguage('Post');

	if (isset($_REQUEST['preview']))
		return Post();

	if (!empty($topic) && empty($_REQUEST['msg']))
	{
		$request = db_query("
			SELECT t.locked, t.ID_POLL, t.numReplies, m.ID_MEMBER
			FROM {$db_prefix}topics AS t, {$db_prefix}messages AS m
			WHERE t.ID_TOPIC = $topic
				AND m.ID_MSG = t.ID_FIRST_MSG
			LIMIT 1", __FILE__, __LINE__);
		list ($tmplocked, $pollID, $numReplies, $ID_MEMBER_POSTER) = mysql_fetch_row($request);
		mysql_free_result($request);
		// Don't allow a post if it's locked.
		if ($tmplocked != 0 && !allowedTo('moderate_board'))
			fatal_lang_error(90, false);

		// Sorry, multiple polls aren't allowed... yet.  You should stop giving me ideas :P.
		if (isset($_REQUEST['poll']) && $pollID > 0)
			unset($_REQUEST['poll']);

		if ($ID_MEMBER_POSTER != $ID_MEMBER)
			isAllowedTo('post_reply_any');
		elseif (!allowedTo('post_reply_any'))
			isAllowedTo('post_reply_own');

		if (isset($_POST['lock']) && !(allowedTo('lock_any') || ($ID_MEMBER == $ID_MEMBER_POSTER && allowedTo('lock_own'))))
			unset($_POST['lock']);
		if (isset($_POST['sticky']) && !allowedTo('make_sticky'))
			unset($_POST['sticky']);

		// If the number of replies has changed, if the setting is enabled, go back to Post() - which handles the error.
		$newReplies = isset($_POST['num_replies']) && $numReplies > $_POST['num_replies'] ? $numReplies - $_POST['num_replies'] : 0;
		if (!empty($modSettings['enableNewReplyWarning']) && !empty($newReplies))
		{
			$_REQUEST['preview'] = true;
			return Post();
		}
	}
	elseif (empty($topic))
	{
		isAllowedTo('post_new');

		if (isset($_POST['lock']) && (!allowedTo('lock_any') || !allowedTo('lock_own')))
			unset($_POST['lock']);
		if (isset($_POST['sticky']) && !allowedTo('make_sticky'))
			unset($_POST['sticky']);
	}

	/* Check to see whether topic and message match, as well as getting the message's
		current information and deleting it if necessary. */
	if (isset($_REQUEST['msg']) && !empty($topic))
	{
		$_REQUEST['msg'] = (int) $_REQUEST['msg'];

		$request = db_query("
			SELECT
				m.ID_MEMBER, m.posterName, m.posterEmail, m.posterTime, t.ID_FIRST_MSG, t.locked,
				t.ID_MEMBER_STARTED AS ID_MEMBER_POSTER
			FROM {$db_prefix}messages AS m, {$db_prefix}topics AS t
			WHERE m.ID_MSG = $_REQUEST[msg]
				AND t.ID_TOPIC = $topic
			LIMIT 1", __FILE__, __LINE__);
		if (mysql_num_rows($request) == 0)
			fatal_lang_error('smf232');
		$row = mysql_fetch_assoc($request);
		mysql_free_result($request);

		if (!empty($row['locked']) && !allowedTo('moderate_board'))
			fatal_lang_error(90, false);

		if (isset($_POST['lock']) && (!allowedTo('lock_any') || ($ID_MEMBER == $row['ID_MEMBER_POSTER'] && !allowedTo('lock_own'))))
			unset($_POST['lock']);
		if (isset($_POST['sticky']) && !allowedTo('make_sticky'))
			unset($_POST['sticky']);

		if ($row['ID_MEMBER'] == $ID_MEMBER && !allowedTo('modify_any'))
		{
			if ($row['ID_MEMBER_POSTER'] == $ID_MEMBER && !allowedTo('modify_own'))
				isAllowedTo('modify_replies');
			else
				isAllowedTo('modify_own');
		}
		elseif ($row['ID_MEMBER_POSTER'] == $ID_MEMBER && !allowedTo('modify_any'))
			isAllowedTo('modify_replies');
		else
		{
			isAllowedTo('modify_any');
			$moderationAction = true;
		}

		$posterIsGuest = empty($row['ID_MEMBER']);

		if (!allowedTo('moderate_forum') || !$posterIsGuest)
		{
			$_POST['guestname'] = $row['posterName'];
			$_POST['email'] = $row['posterEmail'];
		}
	}
	elseif (isset($_REQUEST['msg']))
		fatal_lang_error('smf232');
	else
		$posterIsGuest = $user_info['is_guest'];

	// If the poster is a guest evaluate the legality of name and email.
	if ($posterIsGuest)
	{
		$_POST['guestname'] = !isset($_POST['guestname']) ? '' : trim($_POST['guestname']);
		$_POST['email'] = !isset($_POST['email']) ? '' : trim($_POST['email']);

		if ($_POST['guestname'] == '' || $_POST['guestname'] == '_')
			$post_errors[] = 'no_name';
		if (strlen($_POST['guestname']) > 25)
			$post_errors[] = 'long_name';
		if (!isset($_POST['email']) || $_POST['email'] == '')
			$post_errors[] = 'no_email';
		if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]+@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['email'])) == 0)
			$post_errors[] = 'bad_email';
	}

	// Check the subject and message.
	if (!isset($_POST['subject']) || htmltrim__recursive($_POST['subject']) == '')
		$post_errors[] = 'no_subject';
	if (!isset($_POST['message']) || htmltrim__recursive($_POST['message']) == '')
		$post_errors[] = 'no_message';
	elseif (!empty($modSettings['max_messageLength']) && strlen($_POST['message']) > $modSettings['max_messageLength'])
		$post_errors[] = 'long_message';
	if (isset($_POST['calendar']) && !isset($_REQUEST['deleteevent']) && htmltrim__recursive($_POST['evtitle']) == '')
		$post_errors[] = 'no_event';

	// Validate the poll...
	if (isset($_REQUEST['poll']) && $modSettings['pollMode'] == '1')
	{
		if (isset($topic) && !isset($_REQUEST['msg']))
			fatal_lang_error(1, false);

		// This is a new topic... so it's a new poll.
		if (empty($topic))
			isAllowedTo('poll_post');
		// Can you add to your own topics?
		elseif ($ID_MEMBER == $row['ID_MEMBER_POSTER'] && !allowedTo('poll_add_any'))
			isAllowedTo('poll_add_own');
		// Can you add polls to any topic, then?
		else
			isAllowedTo('poll_add_any');

		if (!isset($_POST['question']) || trim($_POST['question']) == '')
			$post_errors[] = 'no_question';

		$_POST['options'] = empty($_POST['options']) ? array() : htmltrim__recursive($_POST['options']);

		// Get rid of empty ones.
		foreach ($_POST['options'] as $k => $option)
			if ($option == '')
				unset($_POST['options'][$k]);

		// What are you going to vote between with one choice?!?
		if (count($_POST['options']) < 2)
			$post_errors[] = 'poll_few';
	}

	if ($posterIsGuest)
	{
		// If user is a guest, make sure the chosen name isn't taken.
		if (isReservedName($_POST['guestname']) && $_POST['guestname'] != $row['posterName'])
			$post_errors[] = 'bad_name';
	}
	// If the user isn't a guest, get his or her name and email.
	elseif (!isset($_REQUEST['msg']))
	{
		$_POST['guestname'] = $user_info['username'];
		$_POST['email'] = $user_info['email'];
	}

	// Any mistakes?
	if (!empty($post_errors))
	{
		loadLanguage('Errors');
		// Previewing.
		$_REQUEST['preview'] = true;

		$context['post_error'] = array('messages' => array());
		foreach ($post_errors as $post_error)
		{
			$context['post_error'][$post_error] = true;
			$context['post_error']['messages'][] = $txt['error_' . $post_error];
		}

		return Post();
	}

	// Make sure the user isn't spamming the board.
	if (!isset($_REQUEST['msg']))
		spamProtection('spam');

	// Add special html entities to the subject, message, name, and email.
	$_POST['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);
	$_POST['subject'] = htmlspecialchars($_POST['subject']);
	$_POST['guestname'] = htmlspecialchars($_POST['guestname']);
	$_POST['email'] = htmlspecialchars($_POST['email']);

	// Preparse code. (Zef)
	if ($user_info['is_guest'])
		$user_info['name'] = $_POST['guestname'];
	preparsecode($_POST['message']);

	// Cheat and fix entities in the subject line.
	$_POST['subject'] = preg_replace('~&amp;#(\d{4,5}|[3-9]\d{2,4}|2[6-9]\d);~', '&#$1;', $_POST['subject']);

	// At this point, we want to make sure the subject isn't too long.  Stripslashes first to avoid a trailing slash.
	if (isset($_POST['subject']) && strlen(stripslashes($_POST['subject'])) > 100)
		$_POST['subject'] = addslashes(substr(stripslashes($_POST['subject']), 0, 100));

	// Hack to make it so &#324324... can't happen.
	$_POST['subject'] = preg_replace('~&#\d+$~', '', $_POST['subject']);

	// Make the poll...
	if (isset($_REQUEST['poll']))
	{
		// Make sure that the user has not entered a ridiculus amount of options..
		if (empty($_POST['poll_max_votes']) || $_POST['poll_max_votes'] <= 0)
			$_POST['poll_max_votes'] = 1;
		elseif ($_POST['poll_max_votes'] > count($_POST['options']))
			$_POST['poll_max_votes'] = count($_POST['options']);
		else
			$_POST['poll_max_votes'] = (int) $_POST['poll_max_votes'];

		// Just set it to zero if it's not there..
		if (!isset($_POST['poll_hide']))
			$_POST['poll_hide'] = 0;
		else
			$_POST['poll_hide'] = (int) $_POST['poll_hide'];
		$_POST['poll_change_vote'] = isset($_POST['poll_change_vote']) ? 1 : 0;

		// If the user tries to set the poll too far in advance, don't let them.
		if (!empty($_POST['poll_expire']) && $_POST['poll_expire'] < 1)
			fatal_lang_error('poll_range_error', false);
		// Don't allow them to select option 2 for hidden results if it's not time limited.
		elseif (empty($_POST['poll_expire']) && $_POST['poll_hide'] == 2)
			$_POST['poll_hide'] = 1;

		// Clean up the question and answers.
		$_POST['question'] = htmlspecialchars($_POST['question']);
		$_POST['question'] = preg_replace('~&amp;#(\d{4,5}|[3-9]\d{2,4}|2[6-9]\d);~', '&#$1;', $_POST['question']);
		$_POST['options'] = htmlspecialchars__recursive($_POST['options']);

		// Create the poll.
		db_query("
			INSERT INTO {$db_prefix}polls
				(question, hideResults, maxVotes, expireTime, ID_MEMBER, posterName, changeVote)
			VALUES ('$_POST[question]', $_POST[poll_hide], $_POST[poll_max_votes],
				" . (empty($_POST['poll_expire']) ? '0' : time() + $_POST['poll_expire'] * 3600 * 24) . ", $ID_MEMBER, '$_POST[guestname]', $_POST[poll_change_vote])", __FILE__, __LINE__);
		$ID_POLL = db_insert_id();

		// Create each answer choice.
		$i = 0;
		$setString = '';
		foreach ($_POST['options'] as $option)
		{
			$option = preg_replace('~&amp;#(\d{4,5}|[3-9]\d{2,4}|2[6-9]\d);~', '&#$1;', $option);

			$setString .= "
					($ID_POLL, $i, '$option'),";
			$i++;
		}
		$setString = substr($setString, 0, -1);
		db_query("
			INSERT INTO {$db_prefix}poll_choices
				(ID_POLL, ID_CHOICE, label)
			VALUES$setString", __FILE__, __LINE__);
	}
	else
		$ID_POLL = 0;

	// Check if they are trying to delete any current attachments....
	if (isset($_REQUEST['msg']) && isset($_POST['attach_del']) && allowedTo('post_attachment'))
	{
		foreach ($_POST['attach_del'] as $i => $dummy)
			$_POST['attach_del'][$i] = (int) $dummy;

		require_once($sourcedir . '/ManageAttachments.php');
		removeAttachments('a.ID_MSG = ' . $_REQUEST['msg'] . ' AND a.ID_ATTACH NOT IN (' . implode(', ', $_POST['attach_del']) . ')');
	}

	// ...or attach a new file...
	if (isset($_FILES['attachment']['name']) && $_FILES['attachment']['name'][0] != '')
	{
		isAllowedTo('post_attachment');

		// If this isn't a new post, check the current attachments.
		if (isset($_REQUEST['msg']))
		{
			$request = db_query("
					SELECT COUNT(ID_ATTACH), SUM(size)
					FROM {$db_prefix}attachments
					WHERE ID_MSG = $_REQUEST[msg]", __FILE__, __LINE__);
			list ($quantity, $total_size) = mysql_fetch_row($request);
			mysql_free_result($request);
		}
		else
		{
			$quantity = 0;
			$total_size = 0;
		}

		$attachIDs = array();
		foreach ($_FILES['attachment']['tmp_name'] as $n => $dummy)
		{
			if ($_FILES['attachment']['name'][$n] == '')
				continue;

			if (!is_uploaded_file($_FILES['attachment']['tmp_name'][$n]))
				fatal_lang_error('smf124');

			// Remove special foreign characters from the filename.
			if (empty($modSettings['attachmentEncryptFilenames']))
				$_FILES['attachment']['name'][$n] = getAttachmentFilename($_FILES['attachment']['name'][$n], false, true);

			// Is the file too big?
			if (!empty($modSettings['attachmentSizeLimit']) && $_FILES['attachment']['size'][$n] > $modSettings['attachmentSizeLimit'] * 1024)
				fatal_lang_error('smf122', false, array($modSettings['attachmentSizeLimit']));

			// Have we reached the maximum amount of files we are allowed?
			$quantity++;
			if (!empty($modSettings['attachmentNumPerPostLimit']) && $quantity > $modSettings['attachmentNumPerPostLimit'])
				fatal_lang_error('attachments_limit_per_post', false, array($modSettings['attachmentNumPerPostLimit']));

			// Check the total upload size for this post...
			$total_size += $_FILES['attachment']['size'][$n];
			if (!empty($modSettings['attachmentPostLimit']) && $total_size > $modSettings['attachmentPostLimit'] * 1024)
				fatal_lang_error('smf122', false, array($modSettings['attachmentPostLimit']));

			if (!empty($modSettings['attachmentCheckExtensions']))
			{
				if (!in_array(strtolower(substr(strrchr($_FILES['attachment']['name'][$n], '.'), 1)), explode(',', strtolower($modSettings['attachmentExtensions']))))
					fatal_error($_FILES['attachment']['name'][$n] . '.<br />' . $txt['smf123'] . ' ' . $modSettings['attachmentExtensions'] . '.', false);
			}

			if (!empty($modSettings['attachmentDirSizeLimit']))
			{
				// Make sure the directory isn't full.
				$dirSize = 0;
				$dir = @opendir($modSettings['attachmentUploadDir']) or fatal_lang_error('smf115b');
				while ($file = readdir($dir))
					$dirSize += filesize($modSettings['attachmentUploadDir'] . '/' . $file);
				closedir($dir);

				// Too big!  Maybe you could zip it or something...
				if ($_FILES['attachment']['size'][$n] + $dirSize > $modSettings['attachmentDirSizeLimit'] * 1024)
					fatal_lang_error('smf126');
			}

			// Find the filename, strip the dir.
			$destName = basename($_FILES['attachment']['name'][$n]);

			// Check if the file already exists.... (for those who do not encrypt their filenames...)
			if (empty($modSettings['attachmentEncryptFilenames']))
			{
				// Make sure they aren't trying to upload a nasty file.
				$disabledFiles = array('con', 'com1', 'com2', 'com3', 'com4', 'prn', 'aux', 'lpt1', '.htaccess', 'index.php');
				if (in_array(strtolower($destName), $disabledFiles))
					fatal_error($destName . '.<br />' . $txt['smf130b'] . '.');

				// Check if there's another file with that name...
				$request = db_query("
					SELECT ID_ATTACH
					FROM {$db_prefix}attachments
					WHERE filename = '" . strtolower($_FILES['attachment']['name'][$n]) . "'
					LIMIT 1", __FILE__, __LINE__);
				if (mysql_num_rows($request) > 0)
					fatal_lang_error('smf125');
				mysql_free_result($request);
			}

			if (!is_writable($modSettings['attachmentUploadDir']))
				fatal_lang_error('attachments_no_write');

			db_query("
				INSERT INTO {$db_prefix}attachments
					(" . (!empty($_REQUEST['msg']) ? 'ID_MSG, ' : '') . "filename, size)
				VALUES (" . (!empty($_REQUEST['msg']) ? $_REQUEST['msg'] . ', ' : '') . "'" . $_FILES['attachment']['name'][$n] . "', " . $_FILES['attachment']['size'][$n] . ')', __FILE__, __LINE__);
			$attachID = db_insert_id();
			$attachIDs[] = $attachID;

			$destName = $modSettings['attachmentUploadDir'] . '/' . getAttachmentFilename($destName, $attachID, true);

			if (!move_uploaded_file($_FILES['attachment']['tmp_name'][$n], $destName))
				fatal_lang_error('smf124');

			// Attempt to chmod it.
			@chmod($destName, 0644);
		}
	}

	// Prevent double submission of this form.
	checkSubmitOnce('check');

	// Fix the message icon.
	$_POST['icon'] = preg_replace('~[./\\*:]~', '', $_POST['icon']);

	if (!empty($_REQUEST['msg']))
	{
		// Have admins allowed people to hide their screwups?
		if (time() - $row['posterTime'] > $modSettings['edit_wait_time'] || $ID_MEMBER != $row['ID_MEMBER'])
			$modifiedTime = time();

		// Change the post.
		db_query("
			UPDATE {$db_prefix}messages
			SET
				posterName = '$_POST[guestname]', posterEmail = '$_POST[email]', subject = '$_POST[subject]',
				icon = '$_POST[icon]', body = '$_POST[message]'," . (!empty($modifiedTime) ? "
				modifiedTime = $modifiedTime, modifiedName = '" . addslashes($user_info['name']) . "'," : '') . "
				smileysEnabled = " . (isset($_POST['ns']) ? '0' : '1') . "
			WHERE ID_MSG = $_REQUEST[msg]
			LIMIT 1", __FILE__, __LINE__);

		// Lock and or sticky the post.
		if ((isset($_POST['sticky']) && !empty($modSettings['enableStickyTopics'])) || isset($_POST['lock']) || isset($_REQUEST['poll']))
		{
			if (isset($_POST['sticky']) && !empty($modSettings['enableStickyTopics']))
				$setString = 'isSticky = ' . (int) $_POST['sticky'];
			else
				$setString = '';

			if (isset($_POST['lock']) && $setString != '')
				$setString .= ', locked = ' . (int) $_POST['lock'];
			elseif (isset($_POST['lock']))
				$setString = 'locked = ' . (int) $_POST['lock'];

			if (isset($_REQUEST['poll']) && $setString != '')
				$setString .= ", ID_POLL = $ID_POLL";
			elseif (isset($_REQUEST['poll']))
				$setString = "ID_POLL = $ID_POLL";

			db_query("
				UPDATE {$db_prefix}topics
				SET $setString
				WHERE ID_TOPIC = $topic
				LIMIT 1", __FILE__, __LINE__);
		}

		// Might've changed the subject/poster.
		updateLastMessages($board);

		$newTopic = false;
	}
	// This is a new topic. Save it.
	elseif (empty($topic))
	{
		// Insert the post.
		db_query("
			INSERT INTO {$db_prefix}messages
				(ID_BOARD, ID_MEMBER, subject, posterName, posterEmail, posterTime,
				posterIP, smileysEnabled, body, icon)
			VALUES ($board, $ID_MEMBER, '$_POST[subject]', '$_POST[guestname]', '$_POST[email]', " . time() . ",
				'$user_info[ip]', " . (isset($_POST['ns']) ? '0' : '1') . ", '$_POST[message]', '$_POST[icon]')", __FILE__, __LINE__);
		$ID_MSG = db_insert_id();

		if ($ID_MSG > 0)
		{
			// Insert the new topic.
			db_query("
				INSERT INTO {$db_prefix}topics
					(ID_BOARD, ID_MEMBER_STARTED, ID_MEMBER_UPDATED, ID_FIRST_MSG, ID_LAST_MSG,
						" . (isset($_POST['lock']) ? 'locked, ' : '') .
						(isset($_POST['sticky']) && !empty($modSettings['enableStickyTopics']) ? ' isSticky, ' : '') . "numViews, ID_POLL)
				VALUES ($board, $ID_MEMBER, $ID_MEMBER, $ID_MSG, $ID_MSG,
					" . (isset($_POST['lock']) ? (int) $_POST['lock'] . ', ' : '') .
					(isset($_POST['sticky']) && !empty($modSettings['enableStickyTopics']) ? (int) $_POST['sticky'] . ', ' : '') . "0, $ID_POLL)", __FILE__, __LINE__);

			$topic = db_insert_id();
			if ($topic > 0)
			{
				// Fix the message with the topic.
				db_query("
					UPDATE {$db_prefix}messages
					SET ID_TOPIC = $topic
					WHERE ID_MSG = $ID_MSG
					LIMIT 1", __FILE__, __LINE__);

				// Also fix the attachments.
				if (isset($attachIDs))
					db_query("
						UPDATE {$db_prefix}attachments
						SET ID_MSG = $ID_MSG
						WHERE ID_ATTACH IN (" . implode(', ', $attachIDs) . ')', __FILE__, __LINE__);

				// Increase the number of posts and topics on the board.
				db_query("
					UPDATE {$db_prefix}boards
					SET numPosts = numPosts + 1, numTopics = numTopics + 1
					WHERE ID_BOARD = $board
					LIMIT 1", __FILE__, __LINE__);

				// There's been a new topic AND a new post today.
				trackStats(array('topics' => '+', 'posts' => '+'));

				// Update all the stats so everyone knows about this new topic and message.
				updateStats('topic');
				updateStats('message');
				updateLastMessages($board);
			}
		}

		$newTopic = true;
	}
	// Already existing topic, new post.
	else
	{
		db_query("
			INSERT INTO {$db_prefix}messages
				(ID_BOARD, ID_TOPIC, ID_MEMBER, subject, posterName, posterEmail, posterTime,
				posterIP, smileysEnabled, body, icon)
			VALUES ($board, $topic, $ID_MEMBER, '$_POST[subject]', '$_POST[guestname]', '$_POST[email]', " . time() . ",
				'$user_info[ip]', " . (isset($_POST['ns']) ? '0' : '1') . ", '$_POST[message]', '$_POST[icon]')", __FILE__, __LINE__);
		$ID_MSG = db_insert_id();

		if ($ID_MSG > 0)
		{
			// If attachments were added, update the table now we know the message ID.
			if (isset($attachIDs))
				db_query("
					UPDATE {$db_prefix}attachments
					SET ID_MSG = $ID_MSG
					WHERE ID_ATTACH IN (" . implode(', ', $attachIDs) . ')', __FILE__, __LINE__);

			// Update the number of replies and the lock/sticky status.
			db_query("
				UPDATE {$db_prefix}topics
				SET ID_MEMBER_UPDATED = $ID_MEMBER, ID_LAST_MSG = $ID_MSG,
					numReplies = numReplies + 1" . (isset($_POST['lock']) ? ',
					locked = ' . (int) $_POST['lock'] : '') . (isset($_POST['sticky']) && !empty($modSettings['enableStickyTopics']) ? ',
					isSticky = ' . (int) $_POST['sticky'] : '') . "
				WHERE ID_TOPIC = $topic
				LIMIT 1", __FILE__, __LINE__);

			// Update the post count.
			db_query("
				UPDATE {$db_prefix}boards
				SET numPosts = numPosts + 1
				WHERE ID_BOARD = $board
				LIMIT 1", __FILE__, __LINE__);

			// Statistics...
			trackStats(array('posts' => '+'));

			// Update the *other* stats.
			updateStats('message');
			updateLastMessages($board);
		}

		// They've posted, so they can make the view count go up one if they really want. (this is to keep views >= replies...)
		$_SESSION['last_read_topic'] = 0;

		$newTopic = false;
	}

	// Editing or posting an event?
	if (isset($_POST['calendar']) && (!isset($_REQUEST['eventid']) || $_REQUEST['eventid'] == -1))
	{
		require_once($sourcedir . '/Calendar.php');
		calendarCanLink();
		calendarInsertEvent($board, $topic, $_POST['evtitle'], $ID_MEMBER, $_POST['month'], $_POST['day'], $_POST['year'], isset($_POST['span']) ? $_POST['span'] : null);
	}
	elseif (isset($_POST['calendar']))
	{
		$_REQUEST['eventid'] = (int) $_REQUEST['eventid'];

		// Validate the post...
		require_once($sourcedir . '/Subs-Post.php');
		calendarValidatePost();

		// If you're not allowed to edit any events, you have to be the poster.
		if (!allowedTo('calendar_edit_any'))
		{
			// Get the event's poster.
			$request = db_query("
				SELECT ID_MEMBER
				FROM {$db_prefix}calendar
				WHERE ID_EVENT = $_REQUEST[eventid]", __FILE__, __LINE__);
			$row = mysql_fetch_assoc($request);
			mysql_free_result($request);

			// Silly hacker, Trix are for kids. ...probably trademarked somewhere, this is FAIR USE! (parody...)
			isAllowedTo('calendar_edit_' . ($row['ID_MEMBER'] == $ID_MEMBER ? 'own' : 'any'));
		}

		// Delete it?
		if (isset($_REQUEST['deleteevent']))
			db_query("
				DELETE FROM {$db_prefix}calendar
				WHERE ID_EVENT = $_REQUEST[eventid]
				LIMIT 1", __FILE__, __LINE__);
		// ... or just update it?
		else
			db_query("
				UPDATE {$db_prefix}calendar
				SET eventDate = '$_REQUEST[year]-$_REQUEST[month]-$_REQUEST[day]',
					title = '" . htmlspecialchars($_REQUEST['evtitle'], ENT_QUOTES) . "'
				WHERE ID_EVENT = $_REQUEST[eventid]
				LIMIT 1", __FILE__, __LINE__);

		updateStats('calendar');
	}

	if (!$user_info['is_guest'] && !isset($_REQUEST['msg']))
	{
		// Check if posts count on this board, and if so increase the post count.
		$request = db_query("
			SELECT countPosts
			FROM {$db_prefix}boards
			WHERE ID_BOARD = $board
			LIMIT 1", __FILE__, __LINE__);
		list ($pcounter) = mysql_fetch_row($request);
		mysql_free_result($request);

		if (empty($pcounter))
		{
			++$user_info['posts'];
			updateMemberData($ID_MEMBER, array('posts' => 'posts + 1'));
		}
	}

	// Marking read should be done even for editing messages....
	if (!$user_info['is_guest'])
	{
		// Mark topic as read for the member.  In the future to avoid == problems.
		db_query("
			REPLACE INTO {$db_prefix}log_topics
				(logTime, ID_MEMBER, ID_TOPIC)
			VALUES (" . (time() + 1) . ", $ID_MEMBER, $topic)", __FILE__, __LINE__);

		// Mark all the parents read.  (since you just posted and they will be unread.)
		if (!empty($board_info['parent_boards']))
		{
			db_query("
				UPDATE {$db_prefix}log_boards
				SET logTime = " . time() . "
				WHERE ID_MEMBER = $ID_MEMBER
					AND ID_BOARD IN (" . implode(',', array_keys($board_info['parent_boards'])) . ")", __FILE__, __LINE__);
		}
	}

	// Notify any members who have notification turned on for this topic.
	if ($newTopic)
	{
		// This is a new topic, so maybe we should send off notifications...
		notifyUsersBoard();
	}
	elseif (empty($_REQUEST['msg']))
		sendNotifications($topic, 'reply');

	// Turn notification on or off.  (note this just blows smoke if it's already on or off.)
	if (!empty($_POST['notify']))
	{
		if (allowedTo('mark_any_notify'))
			db_query("
				INSERT IGNORE INTO {$db_prefix}log_notify
					(ID_MEMBER, ID_TOPIC, ID_BOARD)
				VALUES ($ID_MEMBER, $topic, 0)", __FILE__, __LINE__);
	}
	elseif (!$newTopic)
		db_query("
			DELETE FROM {$db_prefix}log_notify
			WHERE ID_MEMBER = $ID_MEMBER
				AND ID_TOPIC = $topic
			LIMIT 1", __FILE__, __LINE__);

	// Log an act of moderation - modifying.
	if (!empty($moderationAction))
		logAction('modify', array('topic' => $topic, 'message' => $_REQUEST['msg'], 'member' => $row['ID_MEMBER_POSTER']));

	// Returning to the topic?
	if (!empty($_REQUEST['goback']))
	{
		// Mark the board as read.... because it might get confusing otherwise.
		db_query("
			UPDATE {$db_prefix}log_boards
			SET logTime = " . time() . "
			WHERE ID_MEMBER = $ID_MEMBER
				AND ID_BOARD = $board", __FILE__, __LINE__);
	}

	if (!empty($_POST['announce_topic']))
		redirectexit('action=announce;sa=selectgroup;topic=' . $topic . (!empty($_POST['move']) && allowedTo('move_any') ? ';move' : '') . (empty($_REQUEST['goback']) ? '' : ';goback'));

	if (!empty($_POST['move']) && allowedTo('move_any'))
		redirectexit('action=movetopic;topic=' . $topic . '.0' . (empty($_REQUEST['goback']) ? '' : ';goback'));

	// Return to post if the mod is on.
	if (isset($_REQUEST['msg']) && !empty($_REQUEST['goback']))
		redirectexit('topic=' . $topic . '.msg' . $_REQUEST['msg'] . '#msg' . $_REQUEST['msg'], true, $context['browser']['is_ie']);
	elseif (!empty($_REQUEST['goback']))
		redirectexit('topic=' . $topic . '.new#new', true, $context['browser']['is_ie']);
	// Dut-dut-duh-duh-DUH-duh-dut-duh-duh!  *dances to the Final Fantasy Fanfare...*
	else
		redirectexit('board=' . $board . '.0');
}

// General function for topic announcements.
function AnnounceTopic()
{
	global $context, $txt;

	if (!allowedTo('announce_topic'))
		fatal_lang_error(1, false);

	validateSession();

	loadTemplate('ManageMembers');
	loadLanguage('Post');

	$subActions = array(
		'selectgroup' => 'AnnouncementSelectMembergroup',
		'send' => 'AnnouncementSend',
	);

	$context['page_title'] = $txt['announce_topic'];

	// Call the function based on the sub-action.
	$subActions[isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'selectgroup']();
}

// Allow a user to chose the membergroups to send the announcement to.
function AnnouncementSelectMembergroup()
{
	global $db_prefix, $context, $topic, $board, $board_info;

	$groups = array_merge($board_info['groups'], array(1));
	foreach ($groups as $id => $group)
		$groups[$id] = (int) $group;

	// Get all membergroups that have access to the board the announcement was made on.
	$request = db_query("
		SELECT mg.ID_GROUP, mg.groupName, COUNT(mem.ID_MEMBER) AS num_members
		FROM {$db_prefix}membergroups AS mg
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_GROUP = mg.ID_GROUP OR FIND_IN_SET(mg.ID_GROUP, mem.additionalGroups) OR mg.ID_GROUP = mem.ID_POST_GROUP)
		WHERE mg.ID_GROUP IN (" . implode(', ', $groups) . ")
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

	// Get the subject of the topic we're about to announce.
	$request = db_query("
		SELECT m.subject
		FROM {$db_prefix}messages AS m, {$db_prefix}topics AS t
		WHERE t.ID_TOPIC = $topic
			AND m.ID_MSG = t.ID_FIRST_MSG", __FILE__, __LINE__);
	list ($context['topic_subject']) = mysql_fetch_row($request);
	mysql_free_result($request);

	censorText($context['announce_topic']['subject']);

	$context['move'] = isset($_REQUEST['move']) ? 1 : 0;
	$context['go_back'] = isset($_REQUEST['goback']) ? 1 : 0;

	$context['sub_template'] = 'announce';
}

// Send the announcement in chunks.
function AnnouncementSend()
{
	global $db_prefix, $topic, $board, $board_info, $context, $modSettings;
	global $language, $scripturl, $txt, $ID_MEMBER, $sourcedir;

	checkSession();

	$chunkSize = 75;
	$context['start'] = empty($_REQUEST['start']) ? 0 : (int) $_REQUEST['start'];
	$groups = array_merge($board_info['groups'], array(1));

	if (!empty($_POST['membergroups']))
		$_POST['who'] = explode(',', $_POST['membergroups']);

	// Check whether at least one membergroup was selected.
	if (empty($_POST['who']))
		fatal_lang_error('no_membergroup_selected');

	// Make sure all membergroups are integers and can access the board of the announcement.
	foreach ($_POST['who'] as $id => $mg)
		$_POST['who'][$id] = in_array((int) $mg, $groups) ? (int) $mg : 0;

	// Get the topic subject and censor it.
	$request = db_query("
		SELECT m.subject
		FROM {$db_prefix}messages AS m, {$db_prefix}topics AS t
		WHERE t.ID_TOPIC = $topic
			AND m.ID_MSG = t.ID_FIRST_MSG", __FILE__, __LINE__);
	list ($context['topic_subject']) = mysql_fetch_row($request);
	mysql_free_result($request);
	censorText($context['topic_subject']);

	// We need this in order to be able send emails.
	require_once($sourcedir . '/Subs-Post.php');

	// Select the email addresses for this batch.
	$request = db_query("
		SELECT mem.ID_MEMBER, mem.emailAddress, mem.lngfile
		FROM {$db_prefix}members AS mem
		WHERE mem.ID_MEMBER != $ID_MEMBER" . (!empty($modSettings['notifyAnncmnts_UserDisable']) ? '
			AND mem.notifyAnnouncements = 1' : '') . "
			AND (mem.ID_GROUP IN (" . implode(', ', $_POST['who']) . ") OR mem.ID_POST_GROUP IN (" . implode(', ', $_POST['who']) . ") OR FIND_IN_SET(" . implode(", mem.additionalGroups) OR FIND_IN_SET(", $_POST['who']) . ", mem.additionalGroups))
			AND mem.ID_MEMBER > $context[start]
		ORDER BY mem.ID_MEMBER
		LIMIT $chunkSize", __FILE__, __LINE__);

	// All members have received a mail. Go to the next screen.
	if (mysql_num_rows($request) == 0)
	{
		if (!empty($_REQUEST['move']) && allowedTo('move_any'))
			redirectexit('action=movetopic;topic=' . $topic . '.0' . (empty($_REQUEST['goback']) ? '' : ';goback'));
		elseif (!empty($_REQUEST['goback']))
			redirectexit('topic=' . $topic . '.new;boardseen#new', true, $context['browser']['is_ie']);
		else
			redirectexit('board=' . $board . '.0');
	}

	// Loop through all members that'll receive an announcement in this batch.
	while ($row = mysql_fetch_assoc($request))
	{
		$cur_language = empty($row['lngfile']) || empty($modSettings['userLanguage']) ? $language : $row['lngfile'];

		// If the language wasn't defined yet, load it and compose a notification message.
		if (!isset($announcements[$cur_language]))
		{
			loadLanguage('Post', $cur_language, false);
			$announcements[$cur_language] = array(
				'subject' => $txt['notifyXAnn2'] . ': ' . $context['topic_subject'],
				'body' => $txt['notifyXAnn3'] . ' ' . $scripturl . '?topic=' . $topic . ".new#new\n\n" . $txt[130],
				'recipients' => array(),
			);
		}

		$announcements[$cur_language]['recipients'][$row['ID_MEMBER']] = $row['emailAddress'];
		$context['start'] = $row['ID_MEMBER'];
	}
	mysql_free_result($request);

	// For each language send a different mail.
	foreach ($announcements as $lang => $mail)
		sendmail($mail['recipients'], $mail['subject'], $mail['body']);

	$context['percentage_done'] = round(100 * $context['start'] / $modSettings['latestMember'], 1);

	$context['move'] = empty($_REQUEST['move']) ? 0 : 1;
	$context['go_back'] = empty($_REQUEST['goback']) ? 0 : 1;
	$context['membergroups'] = implode(',', $_POST['who']);
	$context['sub_template'] = 'announcement_send';

	// Go back to the correct language for the user ;).
	if (!empty($modSettings['userLanguage']))
		loadLanguage('Post');
}

// Notify members of a new post.
function notifyUsersBoard()
{
	global $board, $topic, $txt, $scripturl, $db_prefix, $language, $user_info;
	global $ID_MEMBER, $modSettings, $sourcedir;

	// Can't do it if there's no board. (won't happen but let's check for safety and not sending a zillion email's sake.)
	if ($board == 0)
		return;

	require_once($sourcedir . '/Subs-Post.php');

	// Censor the subject...
	censorText($_POST['subject']);
	$_POST['subject'] = un_htmlspecialchars($_POST['subject']);

	// Find the members with notification on for this board.
	$members = db_query("
		SELECT
			mem.ID_MEMBER, mem.emailAddress, mem.notifyOnce, mem.lngfile, ln.sent, mem.ID_GROUP,
			mem.additionalGroups, b.memberGroups, mem.ID_POST_GROUP
		FROM {$db_prefix}log_notify AS ln, {$db_prefix}members AS mem, {$db_prefix}boards AS b
		WHERE ln.ID_BOARD = $board
			AND b.ID_BOARD = $board
			AND mem.ID_MEMBER != $ID_MEMBER
			AND ln.ID_MEMBER = mem.ID_MEMBER
		GROUP BY mem.ID_MEMBER
		ORDER BY mem.lngfile", __FILE__, __LINE__);
	while ($rowmember = mysql_fetch_assoc($members))
	{
		if ($rowmember['ID_GROUP'] != 1)
		{
			$allowed = explode(',', $rowmember['memberGroups']);
			$rowmember['additionalGroups'] = explode(',', $rowmember['additionalGroups']);
			$rowmember['additionalGroups'][] = $rowmember['ID_GROUP'];
			$rowmember['additionalGroups'][] = $rowmember['ID_POST_GROUP'];

			if (count(array_intersect($allowed, $rowmember['additionalGroups'])) == 0)
				continue;
		}

		loadLanguage('Post', empty($rowmember['lngfile']) || empty($modSettings['userLanguage']) ? $language : $rowmember['lngfile'], false);

		$send_subject = sprintf($txt['notify_boards_subject'], $_POST['subject']);

		// Send only if once is off or it's on and it hasn't been sent.
		if (!empty($rowmember['notifyOnce']) && empty($rowmember['sent']))
			sendmail($rowmember['emailAddress'], $send_subject,
				sprintf($txt['notify_boards'], $_POST['subject'], $scripturl . '?topic=' . $topic . '.new#new') .
				$txt['notify_boards_once'] . "\n\n" .
				$txt['notify_boardsUnsubscribe'] . ': ' . $scripturl . '?action=notifyboard;board=' . $board . ".0\n\n" .
				$txt[130]);
		elseif (empty($rowmember['notifyOnce']))
			sendmail($rowmember['emailAddress'], $send_subject,
				sprintf($txt['notify_boards'], $_POST['subject'], $scripturl . '?topic=' . $topic . '.new#new') .
				$txt['notify_boardsUnsubscribe'] . ': ' . $scripturl . '?action=notifyboard;board=' . $board . ".0\n\n" .
				$txt[130]);
	}
	mysql_free_result($members);

	// Sent!
	db_query("
		UPDATE {$db_prefix}log_notify
		SET sent = 1
		WHERE ID_BOARD = $board
			AND ID_MEMBER != $ID_MEMBER", __FILE__, __LINE__);
}

// Get the topic for display purposes.
function getTopic()
{
	global $topic, $db_prefix, $modSettings, $context;

	// If you're modifying, get only those posts before the current one. (otherwise get all.)
	$request = db_query("
		SELECT IFNULL(mem.realName, m.posterName) AS posterName, m.posterTime, m.body, m.smileysEnabled, m.ID_MSG
		FROM {$db_prefix}messages AS m
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = m.ID_MEMBER)
		WHERE m.ID_TOPIC = $topic" . (isset($_REQUEST['msg']) ? "
			AND m.ID_MSG < $_REQUEST[msg]" : '') . "
		ORDER BY m.ID_MSG DESC" . ($modSettings['topicSummaryPosts'] >= 0 ? '
		LIMIT ' . (int) $modSettings['topicSummaryPosts'] : ''), __FILE__, __LINE__);
	$context['previous_posts'] = array();
	while ($row = mysql_fetch_assoc($request))
	{
		// Censor, BBC, ...
		censorText($row['body']);
		$row['body'] = doUBBC($row['body'], $row['smileysEnabled']);

		// ...and store.
		$context['previous_posts'][] = array(
			'poster' => $row['posterName'],
			'message' => $row['body'],
			'time' => timeformat($row['posterTime']),
			'timestamp' => $row['posterTime'],
			'id' => $row['ID_MSG']
		);
	}
	mysql_free_result($request);
}

function QuoteFast()
{
	global $db_prefix, $modSettings, $user_info, $txt, $settings;

	loadLanguage('Post');

	checkSession('get');

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>', $txt['retrieving_quote'], '</title>
		<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/script.js"></script>
	</head>
	<body>
		', $txt['retrieving_quote'], '
		<div id="temporary_posting_area" style="display: none;"></div>
		<script language="JavaScript" type="text/javascript"><!--';

	$request = db_query("
		SELECT IFNULL(mem.realName, m.posterName) AS posterName, m.posterTime, m.body, m.ID_TOPIC
		FROM {$db_prefix}messages AS m, {$db_prefix}boards AS b
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = m.ID_MEMBER)
		WHERE m.ID_MSG = " . (int) $_REQUEST['quote'] . "
			AND b.ID_BOARD = m.ID_BOARD
			AND $user_info[query_see_board]
		LIMIT 1", __FILE__, __LINE__);
	if (mysql_num_rows($request) == 0)
		echo '
			window.close();';
	else
	{
		$row = mysql_fetch_assoc($request);

		// Censor the message!
		censorText($row['body']);

		$row['body'] = preg_replace('~<br(?: /)?>~i', "\n", $row['body']);

		// Remove any nested quotes.
		if (!empty($modSettings['removeNestedQuotes']))
			$row['body'] = preg_replace(array('~\n?\[quote.*?\].+?\[/quote\]\n?~is', '~^\n~', '~\[/quote\]~'), '', $row['body']);

		// Add a quote string on the fron and end.
		$quote = '[quote author=' . $row['posterName'] . ' link=topic=' . $row['ID_TOPIC'] . '.msg' . (int) $_REQUEST['quote'] . '#msg' . (int) $_REQUEST['quote'] . ' date=' . $row['posterTime'] . ']' . "\n" . $row['body'] . "\n" . '[/quote]';
		$quote = strtr(un_htmlspecialchars($quote), array('\'' => '\\\'', '\\' => '\\\\', "\n" => '\\n', '</script>' => '</\' + \'script>'));

		$quote_mozilla = strtr(preg_replace('~&amp;#(\d{4,5}|[3-9]\d{2,4}|2[6-9]\d);~', '&#$1;', htmlspecialchars($quote)), array('&quot;' => '"'));

		// Lucky for us, Internet Explorer has an "innerText" feature which basically converts entities <--> text.  Use it if possible ;).
		echo '
			var quote = \'', $quote, '\';
			var stage = document.getElementById("temporary_posting_area");

			if (typeof(DOMParser) != "undefined" && typeof(window.opera) == "undefined")
			{
				var xmldoc = new DOMParser().parseFromString("<temp>" + \'', $quote_mozilla, '\'.replace(/\n/g, "_SMF-BREAK_").replace(/\t/g, "_SMF-TAB_") + "</temp>", "text/xml");
				quote = xmldoc.childNodes[0].textContent.replace(/_SMF-BREAK_/g, "\n").replace(/_SMF-TAB_/g, "\t");
			}
			else if (typeof(stage.innerText) != "undefined")
			{
				setInnerHTML(stage, quote.replace(/\n/g, "_SMF-BREAK_").replace(/\t/g, "_SMF-TAB_").replace(/</g, "&lt;").replace(/>/g, "&gt;"));
				quote = stage.innerText.replace(/_SMF-BREAK_/g, "\n").replace(/_SMF-TAB_/g, "\t");
			}

			if (typeof(window.opera) != "undefined")
				quote = quote.replace(/&lt;/g, "<").replace(/&gt;/g, ">").replace(/&quot;/g, \'"\').replace(/&amp;/g, "&");

			window.opener.replaceText(quote, window.opener.document.postmodify.message);

			window.focus();
			setTimeout("window.close();", 400);';
	}
	echo '
		// --></script>
	</body>
</html>';

	obExit(false);
}

?>