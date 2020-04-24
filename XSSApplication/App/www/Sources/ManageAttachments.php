<?php
/******************************************************************************
* ManageAttachments.php                                                       *
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

// The main attachment management function.
function ManageAttachments()
{
	global $txt, $db_prefix, $modSettings, $scripturl, $context, $options;

	// You have to be able to moderate the forum to do this.
	isAllowedTo('manage_attachments');

	// Show the administration bar, etc.
	adminIndex('manage_attachments');

	// If they want to delete attachment(s), delete them. (otherwise fall through..)
	$subActions = array(
		'byAge' => 'RemoveAttachmentByAge',
		'bySize' => 'RemoveAttachmentBySize',
		'maintain' => 'MaintainAttachments',
		'remove' => 'RemoveAttachment',
		'removeall' => 'RemoveAllAttachments'
	);
	if (isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]))
		$subActions[$_REQUEST['sa']]();

	loadTemplate('ManageAttachments');

	// Attachments or avatars?
	$context['browse_avatars'] = isset($_REQUEST['avatars']);

	// Get the number of attachments....
	$request = db_query("
		SELECT COUNT(ID_ATTACH)
		FROM {$db_prefix}attachments
		WHERE ID_MSG != 0", __FILE__, __LINE__);
	list ($context['num_attachments']) = mysql_fetch_row($request);
	mysql_free_result($request);

	// Also get the avatar amount....
	$request = db_query("
		SELECT COUNT(ID_ATTACH)
		FROM {$db_prefix}attachments
		WHERE ID_MEMBER != 0", __FILE__, __LINE__);
	list ($context['num_avatars']) = mysql_fetch_row($request);
	mysql_free_result($request);

	// Allow for sorting of each column...
	$sort_methods = array(
		'name' => 'a.filename',
		'date' => $context['browse_avatars'] ? 'mem.lastLogin' : 'm.ID_MSG',
		'size' => 'filesize',
		'member' => 'mem.realName'
	);

	// Set up the importantant sorting variables... if they picked one...
	if (!isset($_GET['sort']) || !isset($sort_methods[$_GET['sort']]))
	{
		$_GET['sort'] = 'date';
		$descending = !empty($options['view_newest_first']);
	}
	// ... and if they didn't...
	else
		$descending = isset($_GET['desc']);

	$context['sort_by'] = $_GET['sort'];
	$_GET['sort'] = $sort_methods[$_GET['sort']];
	$context['sort_direction'] = $descending ? 'down' : 'up';

	// Get the page index ready......
	if (!isset($_REQUEST['start']) || $_REQUEST['start'] < 0)
		$_REQUEST['start'] = 0;
	$context['start'] = $_REQUEST['start'];

	$context['page_index'] = constructPageIndex($scripturl . '?action=manageattachments;' . ($context['browse_avatars'] ? 'avatars;' : '') . 'sort=' . $context['sort_by'] . ($context['sort_direction'] == 'down' ? ';desc' : ''), $context['start'], $context['browse_avatars'] ? $context['num_avatars'] : $context['num_attachments'], $modSettings['defaultMaxMessages']);

	// Choose a query depending on what we are viewing.
	if (!$context['browse_avatars'])
		$request = db_query("
			SELECT
				m.ID_MSG, IFNULL(mem.realName, m.posterName) AS posterName, m.posterTime, m.ID_TOPIC, m.ID_MEMBER,
				a.filename, IFNULL(a.size, 0) AS filesize, a.ID_ATTACH, a.downloads, mf.subject, t.ID_BOARD
			FROM {$db_prefix}attachments AS a, {$db_prefix}messages AS m, {$db_prefix}topics AS t, {$db_prefix}messages AS mf
				LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = m.ID_MEMBER)
			WHERE a.ID_MSG = m.ID_MSG
				AND t.ID_TOPIC = m.ID_TOPIC
				AND mf.ID_MSG = t.ID_FIRST_MSG
			ORDER BY $_GET[sort] " . ($descending ? 'DESC' : 'ASC') . "
			LIMIT $context[start], $modSettings[defaultMaxMessages]", __FILE__, __LINE__);
	else
		$request = db_query("
			SELECT
				'' AS ID_MSG, IFNULL(mem.realName, '$txt[470]') AS posterName, mem.lastLogin AS posterTime, 0 AS ID_TOPIC, a.ID_MEMBER,
				a.filename, IFNULL(a.size, 0) AS filesize, a.ID_ATTACH, a.downloads, '' AS subject, 0 AS ID_BOARD
			FROM {$db_prefix}attachments AS a
				LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = a.ID_MEMBER)
			WHERE a.ID_MEMBER != 0
			ORDER BY $_GET[sort] " . ($descending ? 'DESC' : 'ASC') . "
			LIMIT $context[start], $modSettings[defaultMaxMessages]", __FILE__, __LINE__);
	$context['posts'] = array();
	while ($row = mysql_fetch_assoc($request))
		$context['posts'][] = array(
			'id' => $row['ID_MSG'],
			'poster' => array(
				'id' => $row['ID_MEMBER'],
				'name' => $row['posterName'],
				'href' => empty($row['ID_MEMBER']) ? '' : $scripturl . '?action=profile;u=' . $row['ID_MEMBER'],
				'link' => empty($row['ID_MEMBER']) ? $row['posterName'] : '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['posterName'] . '</a>'
			),
			'time' => empty($row['posterTime']) ? $txt['never'] : timeformat($row['posterTime']),
			'timestamp' => $row['posterTime'],
			'attachment' => array(
				'id' => $row['ID_ATTACH'],
				'size' => round($row['filesize'] / 1024, 2),
				'name' => $row['filename'],
				'downloads' => $row['downloads'],
				'href' => $scripturl . '?action=dlattach;' . ($context['browse_avatars'] ? 'type=avatar;' : 'topic=' . $row['ID_TOPIC'] . '.0;') . 'id=' . $row['ID_ATTACH'],
				'link' => '<a href="' . $scripturl . '?action=dlattach;' . ($context['browse_avatars'] ? 'type=avatar;' : 'topic=' . $row['ID_TOPIC'] . '.0;') . 'id=' . $row['ID_ATTACH'] . '">' . $row['filename'] . '</a>'
			),
			'topic' => $row['ID_TOPIC'],
			'subject' => $row['subject'],
			'link' => '<a href="' . $scripturl . '?topic=' . $row['ID_TOPIC'] . '.0">' . $row['subject'] . '</a>'
		);
	mysql_free_result($request);

	// Find out how big the directory is.
	$attachmentDirSize = 0;
	$dir = @opendir($modSettings['attachmentUploadDir']) or fatal_lang_error('smf115b');
	while ($file = readdir($dir))
		$attachmentDirSize += filesize($modSettings['attachmentUploadDir'] . '/' . $file);
	closedir($dir);
	// Divide it into kilobytes.
	$attachmentDirSize /= 1024;

	// If they specified a limit only....
	if (!empty($modSettings['attachmentDirSizeLimit']))
		$context['attachment_space'] = round($modSettings['attachmentDirSizeLimit'] - $attachmentDirSize, 2);
	$context['attachment_total_size'] = round($attachmentDirSize, 2);

	$context['page_title'] = $txt['smf201'];
}

function RemoveAttachmentByAge()
{
	global $db_prefix, $modSettings;

	checkSession('post', 'manageattachments');

	// Deleting an attachment?
	if ($_REQUEST['type'] != 'avatars')
	{
		// Get all the old attachments.
		$messages = removeAttachments('a.ID_MSG > 0 AND m.posterTime < ' . (time() - 24 * 60 * 60 * $_POST['age']), 'messages', true);

		// Update the messages to reflect the change.
		if (!empty($messages))
			db_query("
				UPDATE {$db_prefix}messages
				SET body = " . (!empty($_POST['notice']) ? "CONCAT(body, '\n\n$_POST[notice]')" : '') . "
				WHERE ID_MSG IN (" . implode(', ', $messages) . ")
				LIMIT " . count($messages), __FILE__, __LINE__);
	}
	else
	{
		// Remove all the old avatars.
		removeAttachments('a.ID_MEMBER != 0 AND mem.lastLogin < ' . (time() - 24 * 60 * 60 * $_POST['age']), 'members');
	}
	redirectexit('action=manageattachments' . (empty($_REQUEST['avatars']) ? '' : ';avatars'));
}

function RemoveAttachmentBySize()
{
	global $db_prefix, $modSettings;

	checkSession('post', 'manageattachments');

	// Find humungous attachments.
	$messages = removeAttachments('a.ID_MSG > 0 AND a.size > ' . (1024 * $_POST['size']), 'messages', true);

	// And make a note on the post.
	if (!empty($messages))
		db_query("
			UPDATE {$db_prefix}messages
			SET body = " . (!empty($_POST['notice']) ? "CONCAT(body, '\n\n$_POST[notice]')" : '') . "
			WHERE ID_MSG IN (" . implode(',', $messages) . ")
			LIMIT " . count($messages), __FILE__, __LINE__);

	redirectexit('action=manageattachments');
}

function RemoveAttachment()
{
	global $db_prefix, $modSettings, $txt;

	checkSession('post', 'manageattachments');

	if (!empty($_POST['remove']))
	{
		$attachments = array();
		// There must be a quicker way to pass this safety test??
		foreach ($_POST['remove'] as $removeID => $dummy)
			$attachments[] = (int) $removeID;

		if ($_REQUEST['type'] == 'avatars')
			removeAttachments('a.ID_ATTACH IN (' . implode(', ', $attachments) . ')');
		else
		{
			$messages = array_unique(removeAttachments('a.ID_ATTACH IN (' . implode(', ', $attachments) . ')', 'messages', true));

			// And change the message to reflect this.
			if (!empty($messages))
				db_query("
					UPDATE {$db_prefix}messages
					SET body = CONCAT(body, '\n\n" . addslashes($txt['smf216']) . "')
					WHERE ID_MSG IN (" . implode(', ', $messages) . ")
					LIMIT " . count($messages), __FILE__, __LINE__);
		}
	}

	redirectexit('action=manageattachments;' . ($_REQUEST['type'] == 'avatars' ? 'avatars;' : '') . 'start=' . $_REQUEST['start']);
}

function RemoveAllAttachments()
{
	global $db_prefix, $txt;

	checkSession('get', 'manageattachments');

	$messages = removeAttachments('a.ID_MSG > 0', '', true);

	if (!isset($_POST['notice']))
		$_POST['notice'] = $txt['smf216'];

	// Add the notice on the end of the changed messages.
	if (!empty($messages))
		db_query("
			UPDATE {$db_prefix}messages
			SET body = CONCAT(body, '\n\n$_POST[notice]')
			WHERE ID_MSG IN (" . implode(',', $messages) . ")
			LIMIT " . count($messages), __FILE__, __LINE__);

	redirectexit('action=manageattachments');
}

// Removes attachments - allowed query_types: '', 'messages', 'members'
function removeAttachments($condition, $query_type = '', $return_affected_messages = false)
{
	global $db_prefix, $modSettings;

	// Delete it only if it exists...
	$msgs = array();
	$attach = array();

	// Get all the attachment names and ID_MSGs.
	$request = db_query("
		SELECT a.filename, a.ID_ATTACH" . ($query_type == 'messages' ? ', m.ID_MSG' : ', a.ID_MSG') . "
		FROM {$db_prefix}attachments AS a" .
			($query_type == 'messages' ? ", {$db_prefix}messages AS m" : '') .
			($query_type == 'members' ? ", {$db_prefix}members AS mem" : '') . "
		WHERE $condition" . ($query_type == 'messages' ? '
			AND m.ID_MSG = a.ID_MSG' : '') . ($query_type == 'members' ? '
			AND mem.ID_MEMBER = a.ID_MEMBER' : ''), __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
	{
		// Figure out the "encrypted" filename and unlink it ;).
		@unlink(getAttachmentFilename($row['filename'], $row['ID_ATTACH']));

		// Make a list.
		if ($return_affected_messages)
			$msgs[] = $row['ID_MSG'];
		$attach[] = $row['ID_ATTACH'];
	}
	mysql_free_result($request);

	if (!empty($attach))
		db_query("
			DELETE FROM {$db_prefix}attachments
			WHERE ID_ATTACH IN (" . implode(', ', $attach) . ")
			LIMIT " . count($attach), __FILE__, __LINE__);

	if ($return_affected_messages)
		return $msgs;
}

// This function should find attachments in the database that no longer exist and clear them, and fix filesize issues.
function MaintainAttachments()
{
	global $db_prefix, $modSettings;

	$request = db_query("
		SELECT ID_ATTACH, ID_MSG, ID_MEMBER, filename, IFNULL(size, 0) AS size, downloads
		FROM {$db_prefix}attachments", __FILE__, __LINE__);
	$removals = array();
	$filesizes = array();
	while ($row = mysql_fetch_assoc($request))
	{
		$filename = getAttachmentFilename($row['filename'], $row['ID_ATTACH']);

		// Test if the file exists...
		if (!file_exists($filename))
		{
			$removals[] = $row['ID_ATTACH'];
			continue;
		}

		// Fetch the actual filesize.
		$realFileSize = filesize($filename);

		// If it's different that the previous one... we need to update the database.
		if ($realFileSize != $row['size'] && $realFileSize !== false && $realFileSize !== null)
			$filesizes[$row['ID_ATTACH']] = (int) $realFileSize;
	}
	mysql_free_result($request);

	// Remove the missing ones...
	if (!empty($removals))
	{
		removeAttachments('a.ID_ATTACH IN (' . implode(', ', $removals) . ')', 'messages');
		$context['attachments_removed'] = count($removals);
	}

	// Update the database with the filesize fixes.
	if (!empty($filesizes))
	{
		foreach ($filesizes as $attach => $filesize)
			db_query("
				UPDATE {$db_prefix}attachments
				SET size = $filesize
				WHERE ID_ATTACH = $attach
				LIMIT 1", __FILE__, __LINE__);
		$context['attachments_fixed'] = count($filesizes);
	}
}

?>