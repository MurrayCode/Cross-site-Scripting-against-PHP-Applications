<?php
/******************************************************************************
* RepairBoards.php                                                            *
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

function RepairBoards()
{
	global $db_prefix, $txt, $scripturl, $salvageCatID, $salvageBoardID, $sc, $context, $sourcedir;

	isAllowedTo('admin_forum');

	// Set up the administrative bar thing.
	adminIndex('maintain_forum');

	// Print out the top of the webpage.
	$context['page_title'] = $txt[610];
	$context['sub_template'] = 'rawdata';

	// Start displaying errors without fixing them.
	if (!empty($_GET['fixErrors']))
		checkSession('get');

	// Giant if/else. The first displays the forum errors if a variable is not set and asks
	// if you would like to continue, the other fixes the errors.
	if (!isset($_GET['fixErrors']))
	{
		$context['raw_data'] = '
			<table width="100%" cellspacing="0" cellpadding="10" align="center">
				<tr>
					<td width="100%" valign="top">
						<table width="100%" border="0" cellspacing="1" cellpadding="2" class="bordercolor">
							<tr class="titlebg">
								<td>' . $txt['smf73'] . '</td>
							</tr><tr>
								<td class="windowbg">
									' . $txt['smf74'] . ':<p>';

		// Make a last-ditch-effort check to get rid of topics with zeros..
		$resultTopicZeros = db_query("
			SELECT COUNT(ID_TOPIC)
			FROM {$db_prefix}topics
			WHERE ID_TOPIC = 0", __FILE__, __LINE__);
		list ($zeroTopics) = mysql_fetch_row($resultTopicZeros);
		mysql_free_result($resultTopicZeros);

		// This is only going to be 1 or 0, but...
		$resultMessageZeros = db_query("
			SELECT COUNT(ID_MSG)
			FROM {$db_prefix}messages
			WHERE ID_MSG = 0", __FILE__, __LINE__);
		list ($zeroMessages) = mysql_fetch_row($resultMessageZeros);
		mysql_free_result($resultMessageZeros);

		if (!empty($zeroTopics) || !empty($zeroMessages))
			$context['raw_data'] .= $txt['repair_zeroed_ids'] . '<br />';

		// Find messages that don't have existing topics.
		$resultMsg = db_query("
			SELECT m.ID_TOPIC, m.ID_MSG
			FROM {$db_prefix}messages AS m
				LEFT JOIN {$db_prefix}topics AS t ON (t.ID_TOPIC = m.ID_TOPIC)
			WHERE t.ID_TOPIC IS NULL
			ORDER BY m.ID_TOPIC, m.ID_MSG", __FILE__, __LINE__);
		while ($msgArray = mysql_fetch_assoc($resultMsg))
			$context['raw_data'] .= $txt[72] . ' ' . $msgArray['ID_MSG'] . ' ' . $txt['smf307'] . ': ' . $msgArray['ID_TOPIC'] . '<br />';
		mysql_free_result($resultMsg);

		// Find topics with no messages.
		$resultTopic = db_query("
			SELECT t.ID_TOPIC, COUNT(m.ID_MSG) AS numMsg
			FROM {$db_prefix}topics AS t
				LEFT JOIN {$db_prefix}messages AS m ON (m.ID_TOPIC = t.ID_TOPIC)
			GROUP BY t.ID_TOPIC
			HAVING numMsg = 0
			ORDER BY t.ID_TOPIC", __FILE__, __LINE__);
		while ($topicArray = mysql_fetch_assoc($resultTopic))
			$context['raw_data'] .= $txt[118] . ' ' . $topicArray['ID_TOPIC'] . ' ' . $txt['smf308'] . '<br />';
		mysql_free_result($resultTopic);

		// Find topics with incorrect ID_FIRST_MSG/ID_LAST_MSG/numReplies.
		$resultTopic = db_query("
			SELECT
				t.ID_TOPIC, MIN(m.ID_MSG) AS myID_FIRST_MSG, t.ID_FIRST_MSG, MAX(m.ID_MSG) AS myID_LAST_MSG,
				t.ID_LAST_MSG, COUNT(m.ID_MSG) - 1 AS myNumReplies, t.numReplies
			FROM {$db_prefix}topics AS t
				LEFT JOIN {$db_prefix}messages AS m ON (m.ID_TOPIC = t.ID_TOPIC)
			GROUP BY t.ID_TOPIC
			HAVING ID_FIRST_MSG != myID_FIRST_MSG OR ID_LAST_MSG != myID_LAST_MSG OR numReplies != myNumReplies
			ORDER BY t.ID_TOPIC", __FILE__, __LINE__);
		while ($topicArray = mysql_fetch_assoc($resultTopic))
		{
			if ($topicArray['ID_FIRST_MSG'] != $topicArray['myID_FIRST_MSG'])
				$context['raw_data'] .= $txt[118] . ' ' . $topicArray['ID_TOPIC'] . ' ' . $txt['smf75'] . ': ' . $topicArray['ID_FIRST_MSG'] . '<br />';
			if ($topicArray['ID_LAST_MSG'] != $topicArray['myID_LAST_MSG'])
				$context['raw_data'] .= $txt[118] . ' ' . $topicArray['ID_TOPIC'] . ' ' . $txt['smf76'] . ': ' . $topicArray['ID_LAST_MSG'] . '<br />';
			if ($topicArray['numReplies'] != $topicArray['myNumReplies'])
				$context['raw_data'] .= $txt[118] . ' ' . $topicArray['ID_TOPIC'] . ' ' . $txt['smf309'] . ': ' . $topicArray['numReplies'] . '<br />';
		}
		mysql_free_result($resultTopic);

		// Find topics with inexistent boards.
		$resultTopics = db_query("
			SELECT t.ID_TOPIC, t.ID_BOARD
			FROM {$db_prefix}topics AS t
				LEFT JOIN {$db_prefix}boards AS b ON (b.ID_BOARD = t.ID_BOARD)
			WHERE b.ID_BOARD IS NULL
			ORDER BY t.ID_BOARD, t.ID_TOPIC", __FILE__, __LINE__);
		while ($topicArray = mysql_fetch_assoc($resultTopics))
			$context['raw_data'] .= $txt[118] . ' ' . $topicArray['ID_TOPIC'] . ' ' . $txt['smf81'] . ' (' . $txt['smf82'] . ' ' . $topicArray['ID_BOARD'] . ').<br />';
		mysql_free_result($resultTopics);

		// Find boards with nonexistent categories.
		$resultBoards = db_query("
			SELECT b.ID_BOARD, b.ID_CAT
			FROM {$db_prefix}boards AS b
				LEFT JOIN {$db_prefix}categories AS c ON (c.ID_CAT = b.ID_CAT)
			WHERE c.ID_CAT IS NULL
			ORDER BY b.ID_CAT, b.ID_BOARD", __FILE__, __LINE__);
		while ($boardArray = mysql_fetch_assoc($resultBoards))
			$context['raw_data'] .= $txt['smf82'] . ' ' . $boardArray['ID_BOARD'] . ' ' . $txt['smf83'] . ' (' . $txt['smf84'] . ' ' . $boardArray['ID_CAT'] . ').<br />';
		mysql_free_result($resultBoards);

		// Find messages with nonexistent members.
		$resultMsg = db_query("
			SELECT m.ID_MSG
			FROM {$db_prefix}messages AS m
				LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = m.ID_MEMBER)
			WHERE m.ID_MEMBER != 0
				AND mem.ID_MEMBER IS NULL", __FILE__, __LINE__);
		while ($msgArray = mysql_fetch_assoc($resultMsg))
			$context['raw_data'] .= $txt[72] . ' ' . $msgArray['ID_MSG'] . ' ' . $txt['smf310'] . '.<br />';
		mysql_free_result($resultMsg);

		// Find boards with nonexistent parents.
		$resultParents = db_query("
			SELECT b.ID_BOARD, b.ID_PARENT
			FROM {$db_prefix}boards AS b
				LEFT JOIN {$db_prefix}boards AS p ON (p.ID_BOARD = b.ID_PARENT)
			WHERE b.ID_PARENT != 0
				AND (p.ID_BOARD IS NULL OR p.ID_BOARD = b.ID_BOARD)
			ORDER BY b.ID_PARENT, b.ID_BOARD", __FILE__, __LINE__);
		while ($parentArray = mysql_fetch_assoc($resultParents))
			$context['raw_data'] .= sprintf($txt['parent_repair_found'], $parentArray['ID_BOARD'], $parentArray['ID_PARENT']) . '<br />';
		mysql_free_result($resultParents);

		$context['raw_data'] .= '
									</p>
									<p>' . $txt['smf85'] . '<br />
									<b><a href="?action=repairboards;fixErrors;sesc=' . $sc . '">' . $txt[163] . '</a> - <a href="?action=maintain">' . $txt[164] . '</a></b></p>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>';
	}
	else
	{
		require_once($sourcedir . '/Subs-Boards.php');

		$context['raw_data'] = '
			<table width="100%" cellspacing="0" cellpadding="10" align="center">
				<tr>
					<td width="100%" valign="top">
						<table width="100%" border="0" cellspacing="1" cellpadding="2" class="bordercolor">
							<tr class="titlebg">
								<td>' . $txt['smf86'] . '</td>
							</tr><tr>
								<td class="windowbg">';

		// We don't allow 0's in the IDs...
		db_query("
			UPDATE {$db_prefix}topics
			SET ID_TOPIC = NULL
			WHERE ID_TOPIC = 0", __FILE__, __LINE__);

		db_query("
			UPDATE {$db_prefix}messages
			SET ID_MSG = NULL
			WHERE ID_MSG = 0", __FILE__, __LINE__);

		// Fix all messages that have a topic ID that cannot be found in the topics table.
		$resultMsg = db_query("
			SELECT
				m.ID_TOPIC, MIN(m.ID_MSG) AS myID_FIRST_MSG, MAX(m.ID_MSG) AS myID_LAST_MSG,
				COUNT(m.ID_MSG) - 1 AS myNumReplies
			FROM {$db_prefix}messages AS m
				LEFT JOIN {$db_prefix}topics AS t ON (t.ID_TOPIC = m.ID_TOPIC)
			WHERE t.ID_TOPIC IS NULL
			GROUP BY m.ID_TOPIC", __FILE__, __LINE__);
		if (mysql_num_rows($resultMsg) > 0)
		{
			createSalvageArea();

			while ($msgArray = mysql_fetch_assoc($resultMsg))
			{
				$memberStartedID = getMsgMemberID($msgArray['myID_FIRST_MSG']);
				$memberUpdatedID = getMsgMemberID($msgArray['myID_LAST_MSG']);

				db_query("
					INSERT INTO {$db_prefix}topics
						(ID_BOARD, ID_MEMBER_STARTED, ID_MEMBER_UPDATED, ID_FIRST_MSG, ID_LAST_MSG, numReplies)
					VALUES ($salvageBoardID, $memberStartedID, $memberUpdatedID,
						$msgArray[myID_FIRST_MSG], $msgArray[myID_LAST_MSG], $msgArray[myNumReplies])", __FILE__, __LINE__);
				$newTopicID = db_insert_id();

				db_query("
					UPDATE {$db_prefix}messages
					SET ID_TOPIC = $newTopicID, ID_BOARD = $salvageBoardID
					WHERE ID_TOPIC = $msgArray[ID_TOPIC]", __FILE__, __LINE__);
				$context['raw_data'] .= '<br />';
			}
		}
		mysql_free_result($resultMsg);
		$context['raw_data'] .= '<br />';

		// Remove all topics that have zero messages in the messages table.
		$resultTopic = db_query("
			SELECT t.ID_TOPIC, COUNT(m.ID_MSG) AS numMsg
			FROM {$db_prefix}topics AS t
				LEFT JOIN {$db_prefix}messages AS m ON (m.ID_TOPIC = t.ID_TOPIC)
			GROUP BY t.ID_TOPIC
			HAVING numMsg = 0", __FILE__, __LINE__);
		if (mysql_num_rows($resultTopic) > 0)
		{
			$stupidTopics = array();
			while ($topicArray = mysql_fetch_assoc($resultTopic))
				$stupidTopics[] = $topicArray['ID_TOPIC'];
			db_query("
				DELETE FROM {$db_prefix}topics
				WHERE ID_TOPIC IN (" . implode(',', $stupidTopics) . ')
				LIMIT ' . count($stupidTopics), __FILE__, __LINE__);
			$context['raw_data'] .= db_affected_rows() . ' ' . $txt['smf312'] . '<br />';
		}
		mysql_free_result($resultTopic);

		// Fix all ID_FIRST_MSG, ID_LAST_MSG and numReplies in the topic table.
		$resultTopic = db_query("
			SELECT
				t.ID_TOPIC, MIN(m.ID_MSG) AS myID_FIRST_MSG, t.ID_FIRST_MSG,
				MAX(m.ID_MSG) AS myID_LAST_MSG, t.ID_LAST_MSG, COUNT(m.ID_MSG) - 1 AS myNumReplies,
				t.numReplies
			FROM {$db_prefix}topics AS t
				LEFT JOIN {$db_prefix}messages AS m ON (m.ID_TOPIC = t.ID_TOPIC)
			GROUP BY t.ID_TOPIC
			HAVING ID_FIRST_MSG != myID_FIRST_MSG OR ID_LAST_MSG != myID_LAST_MSG OR numReplies != myNumReplies", __FILE__, __LINE__);
		if (mysql_num_rows($resultTopic) > 0)
		{
			while ($topicArray = mysql_fetch_assoc($resultTopic))
			{
				$memberStartedID = getMsgMemberID($topicArray['myID_FIRST_MSG']);
				$memberUpdatedID = getMsgMemberID($topicArray['myID_LAST_MSG']);
				$result = db_query("
					UPDATE {$db_prefix}topics
					SET ID_FIRST_MSG = '$topicArray[myID_FIRST_MSG]',
						ID_MEMBER_STARTED = '$memberStartedID', ID_LAST_MSG = $topicArray[myID_LAST_MSG],
						ID_MEMBER_UPDATED = '$memberUpdatedID', numReplies = '$topicArray[myNumReplies]'
					WHERE ID_TOPIC = $topicArray[ID_TOPIC]
					LIMIT 1", __FILE__, __LINE__);
			}
		}
		mysql_free_result($resultTopic);

		// Fix all topics that have a board ID that cannot be found in the boards table.
		$resultTopics = db_query("
			SELECT t.ID_BOARD, COUNT(t.ID_TOPIC) AS myNumTopics, COUNT(m.ID_MSG) AS myNumPosts
			FROM {$db_prefix}topics AS t
				LEFT JOIN {$db_prefix}boards AS b ON (b.ID_BOARD = t.ID_BOARD)
				LEFT JOIN {$db_prefix}messages AS m ON (m.ID_TOPIC = t.ID_TOPIC)
			WHERE b.ID_BOARD IS NULL
			GROUP BY t.ID_BOARD", __FILE__, __LINE__);
		if (mysql_num_rows($resultTopics) > 0)
		{
			createSalvageArea();

			while ($topicArray = mysql_fetch_assoc($resultTopics))
			{
				db_query("
					INSERT INTO {$db_prefix}boards
						(ID_CAT, name, numTopics, numPosts)
					VALUES ($salvageCatID, 'Salvaged board', $topicArray[myNumTopics],
						$topicArray[myNumPosts])", __FILE__, __LINE__);
				$newBoardID = db_insert_id();

				db_query("
					UPDATE {$db_prefix}topics
					SET ID_BOARD = $newBoardID
					WHERE ID_BOARD = $topicArray[ID_BOARD]", __FILE__, __LINE__);
				db_query("
					UPDATE {$db_prefix}messages
					SET ID_BOARD = $newBoardID
					WHERE ID_BOARD = $topicArray[ID_BOARD]", __FILE__, __LINE__);
				$context['raw_data'] .= $txt['smf311'] . '<br />';
			}
		}
		mysql_free_result($resultTopics);

		// Fix all boards that have a cat ID that cannot be found in the cats table.
		$resultBoards = db_query("
			SELECT b.ID_CAT
			FROM {$db_prefix}boards AS b
				LEFT JOIN {$db_prefix}categories AS c ON (c.ID_CAT = b.ID_CAT)
			WHERE c.ID_CAT IS NULL
			GROUP BY b.ID_CAT", __FILE__, __LINE__);
		if (mysql_num_rows($resultBoards) > 0)
		{
			createSalvageArea();

			while ($boardArray = mysql_fetch_assoc($resultBoards))
			{
				db_query("
					UPDATE {$db_prefix}boards
					SET ID_CAT = $salvageCatID
					WHERE ID_CAT = $boardArray[ID_CAT]", __FILE__, __LINE__);
				$context['raw_data'] .= $txt['smf311'] . '<br />';
			}
		}
		mysql_free_result($resultBoards);

		// Fix all boards that have a parent ID that cannot be found in the boards table.
		$resultParents = db_query("
			SELECT b.ID_PARENT
			FROM {$db_prefix}boards AS b
				LEFT JOIN {$db_prefix}boards AS p ON (p.ID_BOARD = b.ID_PARENT)
			WHERE b.ID_PARENT != 0
				AND (p.ID_BOARD IS NULL OR p.ID_BOARD = b.ID_BOARD)
			GROUP BY b.ID_PARENT", __FILE__, __LINE__);
		if (mysql_num_rows($resultParents) > 0)
		{
			createSalvageArea();

			while ($parentArray = mysql_fetch_assoc($resultParents))
			{
				db_query("
					UPDATE {$db_prefix}boards
					SET ID_PARENT = $salvageBoardID, ID_CAT = $salvageCatID, childLevel = 1
					WHERE ID_PARENT = $parentArray[ID_PARENT]", __FILE__, __LINE__);
				$context['raw_data'] .= $txt['parent_repair_fixed'] . '<br />';
			}
		}
		mysql_free_result($resultParents);

		// Last step-make sure all non-guest posters still exist.
		$resultMsg = db_query("
			SELECT m.ID_MSG
			FROM {$db_prefix}messages AS m
				LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = m.ID_MEMBER)
			WHERE m.ID_MEMBER != 0
				AND mem.ID_MEMBER IS NULL", __FILE__, __LINE__);
		if (mysql_num_rows($resultMsg) > 0)
		{
			$guestMessages = array();
			while ($msgArray = mysql_fetch_assoc($resultMsg))
				$guestMessages[] = $msgArray['ID_MSG'];
			db_query("
				UPDATE {$db_prefix}messages
				SET ID_MEMBER = 0
				WHERE ID_MSG IN (" . implode(',', $guestMessages) . ')
				LIMIT ' . count($guestMessages), __FILE__, __LINE__);
		}
		mysql_free_result($resultMsg);

		$resultPolls = db_query("
			SELECT t.ID_POLL
			FROM {$db_prefix}topics AS t
				LEFT JOIN {$db_prefix}polls AS p ON (p.ID_POLL = t.ID_POLL)
			WHERE p.ID_POLL IS NULL
			GROUP BY t.ID_POLL", __FILE__, __LINE__);
		$polls = array();
		while ($rowPolls = mysql_fetch_assoc($resultPolls))
			$polls[] = $rowPolls['ID_POLL'];
		mysql_free_result($resultPolls);

		if (!empty($polls))
			db_query("
				UPDATE {$db_prefix}topics
				SET ID_POLL = 0
				WHERE ID_POLL IN (" . implode(', ', $polls) . ")
				LIMIT " . count($polls), __FILE__, __LINE__);

		updateStats('message');
		updateStats('topic');
		updateStats('calendar');

		$context['raw_data'] .= '
									<p>' . $txt['smf92'] . '</p>
									<a href="?action=admin">' . $txt[137] . '</a><br />
									<a href="' . $scripturl . '">' . $txt[236] . ' ' . $txt[237] . '</a><br />
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>';
	}
}

// For now, this isn't internationalized because it could cause conflicts with other languages.
function createSalvageArea()
{
	global $createOnce, $db_prefix, $salvageBoardID, $salvageCatID;

	if (!empty($createOnce))
		return;
	$creatOnce = true;

	// Check to see if a 'Salvage Category' exists, if not => insert one.
	$result = db_query("
		SELECT ID_CAT
		FROM {$db_prefix}categories
		WHERE name = 'Salvage Area'
		LIMIT 1", __FILE__, __LINE__);
	if (mysql_num_rows($result) == 0)
	{
		db_query("
			INSERT INTO {$db_prefix}categories
				(name, catOrder)
			VALUES ('Salvage Area', -1)", __FILE__, __LINE__);

		if (db_affected_rows() <= 0)
			fatal_error($txt['smf89'] . ' ' . $txt['smf82'], false);

		$salvageCatID = db_insert_id();
	}
	else
		list ($salvageCatID) = mysql_fetch_row($result);
	mysql_free_result($result);

	// Check to see if a 'Salvage Board' exists, if not => insert one.
	$result = db_query("
		SELECT ID_BOARD
		FROM {$db_prefix}boards
		WHERE ID_CAT = $salvageCatID
			AND name = 'Salvaged Messages'
		LIMIT 1", __FILE__, __LINE__);
	if (mysql_num_rows($result) == 0)
	{
		db_query("
			INSERT INTO {$db_prefix}boards
				(name, description, ID_CAT, memberGroups)
			VALUES ('Salvaged Messages', 'Topics created for messages with non-existent topics', $salvageCatID, '1')", __FILE__, __LINE__);

		if (db_affected_rows() < 0)
			fatal_error($txt['smf89'] . ' ' . $txt['smf84'], false);

		$salvageBoardID = db_insert_id();
	}
	else
		list ($salvageBoardID) = mysql_fetch_row($result);
	mysql_free_result($result);
}

?>