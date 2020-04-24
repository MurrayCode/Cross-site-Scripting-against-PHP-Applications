<?php
/******************************************************************************
* Search.php                                                                  *
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

/*	These functions are here for searching, and they are:

	void PlushSearch1()
		- shows the screen to search forum posts (action=search), and uses the
		  simple version if the simpleSearch setting is enabled.
		- uses the main sub template of the Search template.
		- requires the search_posts permission.
		- decodes and loads search parameters given in the URL (if any).
		- the form redirects to index.php?action=search2.

	void PlushSearch2()
		- checks user input and searches the messages table for messages
		  matching the query.
		- requires the search_posts permission.
		- stores the results into the search cache (if enabled).
		- show the results of the search query.
		- uses the results sub template of the Search template.

	array prepareSearchContext(bool reset = false)
		- callback function for the results sub template.
		- loads the necessary contextual data to show a search result.
*/

// Ask the user what they want to search for.
function PlushSearch1()
{
	global $txt, $scripturl, $db_prefix, $modSettings, $user_info, $context;

	loadTemplate('Search');

	// Check the user's permissions.
	isAllowedTo('search_posts');

	// Link tree....
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=search',
		'name' => $txt[182]
	);

	// If you got back from search2 by using the linktree, you get your original search parameters back.
	if (isset($_REQUEST['params']))
	{
		$temp_params = explode('|"|', base64_decode($_REQUEST['params']));
		$context['search_params'] = array();
		foreach ($temp_params as $i => $data)
		{
			list ($k, $v) = explode('|\'|', $data);
			$context['search_params'][$k] = stripslashes($v);
		}
		if (isset($context['search_params']['brd']))
			$context['search_params']['brd'] = $context['search_params']['brd'] == '' ? array() : explode(',', $context['search_params']['brd']);
	}

	// Find all the boards this user is allowed to see.
	$request = db_query("
		SELECT b.ID_CAT, c.name AS catName, b.ID_BOARD, b.name, b.childLevel
		FROM {$db_prefix}boards AS b
			LEFT JOIN {$db_prefix}categories AS c ON (c.ID_CAT = b.ID_CAT)
		WHERE $user_info[query_see_board]
		ORDER BY c.catOrder, b.boardOrder", __FILE__, __LINE__);
	$context['num_boards'] = mysql_num_rows($request);
	$context['categories'] = array();
	while ($row = mysql_fetch_assoc($request))
	{
		// This category hasn't been set up yet..
		if (!isset($context['categories'][$row['ID_CAT']]))
			$context['categories'][$row['ID_CAT']] = array(
				'id' => $row['ID_CAT'],
				'name' => $row['catName'],
				'boards' => array()
			);

		// Set this board up, and let the template know when it's a child.  (indent them..)
		$context['categories'][$row['ID_CAT']]['boards'][$row['ID_BOARD']] = array(
			'id' => $row['ID_BOARD'],
			'name' => $row['name'],
			'child_level' => $row['childLevel']
		);
	}
	mysql_free_result($request);

	// Simple or not?
	$context['simple_search'] = !empty($modSettings['simpleSearch']) && !isset($_GET['advanced']);
	$context['page_title'] = $txt[183];
}

// Gather the results and show them.
function PlushSearch2()
{
	global $modSettings, $sourcedir;
	global $scripturl, $txt, $db_prefix, $user_info, $context, $messages_request, $attachments, $boards_can;

	$weight_factors = array(
		'frequency',
		'age',
		'length',
		'subject',
		'first_message',
	);

	$weight = array();
	$weight_total = 0;
	foreach ($weight_factors as $weight_factor)
	{
		$weight[$weight_factor] = empty($modSettings['search_weight_' . $weight_factor]) ? 0 : (int) $modSettings['search_weight_' . $weight_factor];
		$weight_total += $weight[$weight_factor];
	}

	// Zero weight. Weightless.
	if (empty($weight_total))
		fatal_lang_error('search_invalid_weights');

/*
	// Fine-tune the weight of each search factor.  These factors should add up to 100.
	$weight = array(
		// The more message within a topic match the search query, the higher the ranking.
		'frequency' => 30,
		// The more recent a message is, the higher its ranking.
		'age' => 25,
		// The larger the topic is, the higher its ranking.
		'length' => 20,
		// If a search string happens to be part of the topic subject, it's probably a better match.
		'subject' => 15,
		// If the first message of a topic is a match, then the whole topic might be more interesting.
		'first_message' => 10,
	);
*/

	// These vars don't require an interface, the're just here for tweaking.
	$recentPercentage = 0.30;
	$humungousTopicPosts = 200;
	$maxMembersToSearch = 500;

	loadTemplate('Search');

	// Are you allowed?
	isAllowedTo('search_posts');

	require_once($sourcedir . '/Display.php');

	// $search_params will carry all settings that differ from the default search parameters.
	// That way, the URLs involved in a search page will be kept as short as possible.
	$search_params = array();

	if (isset($_REQUEST['params']))
	{
		$temp_params = explode('|"|', base64_decode($_REQUEST['params']));
		foreach ($temp_params as $i => $data)
		{
			list ($k, $v) = explode('|\'|', $data);
			$search_params[$k] = stripslashes($v);
		}
		if (isset($search_params['brd']))
			$search_params['brd'] = $search_params['brd'] == '' ? array() : explode(',', $search_params['brd']);
	}

	// 1 => 'allwords' (default, don't set as param) / 2 => 'anywords'.
	if (!empty($search_params['searchtype']) || (!empty($_REQUEST['searchtype']) && $_REQUEST['searchtype'] == 2))
		$search_params['searchtype'] = 2;

	// Minimum age of messages. Default to zero (don't set param in that case).
	if (!empty($search_params['minage']) || (!empty($_REQUEST['minage']) && $_REQUEST['minage'] > 0))
		$search_params['minage'] = !empty($search_params['minage']) ? (int) $search_params['minage'] : (int) $_REQUEST['minage'];

	// Maximum age of messages. Default to infinite (9999 days: param not set).
	if (!empty($search_params['maxage']) || (!empty($_REQUEST['maxage']) && $_REQUEST['maxage'] != 9999))
		$search_params['maxage'] = !empty($search_params['maxage']) ? (int) $search_params['maxage'] : (int) $_REQUEST['maxage'];

	$timeAddition = '';
	$timeAddition .= !empty($search_params['minage']) ? ' AND m.posterTime <= ' . (time() - $search_params['minage'] * 86400) : '';
	$timeAddition .= !empty($search_params['maxage']) ? ' AND m.posterTime >= ' . (time() - $search_params['maxage'] * 86400) : '';

	// Default the user name to a wildcard matching every user (*).
	if (!empty($search_params['user_spec']) || (!empty($_REQUEST['userspec']) && $_REQUEST['userspec'] != '*'))
		$search_params['userspec'] = isset($search_params['userspec']) ? $search_params['userspec'] : $_REQUEST['userspec'];

	// If there's no specific user, then don't mention it in the main query.
	if (empty($search_params['userspec']))
		$userQuery = '';
	else
	{
		$userString = strtolower(addslashes(strtr($search_params['userspec'], array('%' => '\%', '_' => '\_', '*' => '%', '?' => '_'))));
		// Retrieve a list of possible members.
		$request = db_query("
			SELECT ID_MEMBER
			FROM {$db_prefix}members
			WHERE realName LIKE '$userString'", __FILE__, __LINE__);
		// Simply do nothing if there're too many members matching the criteria.
		if (mysql_num_rows($request) > $maxMembersToSearch)
			$userQuery = '';
		elseif (mysql_num_rows($request) == 0)
			$userQuery = "m.ID_MEMBER = 0 AND m.posterName LIKE '$userString'";
		else
		{
			$memberlist = array();
			while ($row = mysql_fetch_assoc($request))
				$memberlist[] = $row['ID_MEMBER'];
			$userQuery = "(m.ID_MEMBER IN (" . implode(', ', $memberlist) . ") OR (m.ID_MEMBER = 0 AND m.posterName LIKE '$userString'))";
		}
	}

	// If the boards were passed by URL (params=), temporarily put them back in $_REQUEST.
	if (!empty($search_params['brd']) && is_array($search_params['brd']))
		$_REQUEST['brd'] = $search_params['brd'];

	// Make sure all boards are integers.
	if (!empty($_REQUEST['brd']))
		foreach ($_REQUEST['brd'] as $id => $brd)
			$_REQUEST['brd'][$id] = (int) $brd;

	// Select all boards you've selected AND are allowed to see.
	if ($user_info['is_admin'])
		$search_params['brd'] = empty($_REQUEST['brd']) ? array() : $_REQUEST['brd'];
	else
	{
		$request = db_query("
			SELECT b.ID_BOARD
			FROM {$db_prefix}boards AS b
			WHERE $user_info[query_see_board]" . (empty($_REQUEST['brd']) ? '' : "
				AND b.ID_BOARD IN (" . implode(', ', $_REQUEST['brd']) . ")"), __FILE__, __LINE__);
		$search_params['brd'] = array();
		while ($row = mysql_fetch_assoc($request))
			$search_params['brd'][] = $row['ID_BOARD'];
		mysql_free_result($request);

		// This error should pro'bly only happen for hackers.
		if (empty($search_params['brd']))
			fatal_lang_error('search_no_boards');
	}

	// If we've selected all boards, this parameter can be left empty.
	$request = db_query("
		SELECT COUNT(ID_BOARD)
		FROM {$db_prefix}boards", __FILE__, __LINE__);
	list ($num_boards) = mysql_fetch_row($request);
	mysql_free_result($request);

	if ($num_boards == count($search_params['brd']))
		$search_params['brd'] = array();
	// Make sure these all boards are numbers.
	elseif (!empty($search_params['brd']))
	{
		foreach ($search_params['brd'] as $k => $v)
			$search_params['brd'][$k] = (int) $v;
	}

	$search_params['show_complete'] = !empty($search_params['show_complete']) || !empty($_REQUEST['show_complete']);
	$search_params['subject_only'] = !empty($search_params['subject_only']) || !empty($_REQUEST['subject_only']);

	$context['compact'] = !$search_params['show_complete'];

	// What are we searching for?
	$search_params['search'] = !empty($search_params['search']) ? $search_params['search'] : (isset($_REQUEST['search']) ? stripslashes($_REQUEST['search']) : '');
	// Nothing??
	if (!isset($search_params['search']) || $search_params['search'] == '')
		fatal_lang_error('no_valid_search_string', false);

	// Extract phrase parts first (e.g. some words "this is a phrase" some more words.)
	preg_match_all('/(?:^|\s)"([^"]+)"(?:$|\s)/', $search_params['search'], $matches, PREG_PATTERN_ORDER);
	$searchArray = $matches[1];

	// Remove the phrase parts and extract the words.
	$searchArray = array_merge($searchArray, explode(' ', preg_replace('/(?:^|\s)"([^"]+)"(?:$|\s)/', ' ', $search_params['search'])));

	// Trim everything and make sure there are no words that are the same.
	foreach ($searchArray as $index => $value)
	{
		$searchArray[$index] = addslashes(strtolower(trim($value)));
		if (!isset($searchArray[$index]) || $searchArray[$index] == '')
			unset($searchArray[$index]);
	}
	$searchArray = array_unique($searchArray);

	if (empty($searchArray))
		fatal_lang_error('no_valid_search_string', false);

	// Each word is matched against the body and the subject.
	$searchParts = array();
	foreach ($searchArray as $word)
	{
		if (empty($modSettings['search_match_complete_words']))
			$searchParts[] = " LIKE '%" . strtr($word, array('_' => '\\_', '%' => '\\%')) . "%'";
		else
			$searchParts[] = " RLIKE '[[:<:]]" . addcslashes(preg_replace(array('/([\[\]$.+?|{}])/', '/\*/'), array('[$1]', '.+'), $word), '') . "[[:>:]]'";
	}

	$searchQuery = 0;

	// Either all words must match (searchtype == 1) or any of the words (searchtype == 2).
	if (empty($search_params['searchtype']))
	{
		if (!$search_params['subject_only'])
			$searchQuery = 'm.body' . implode(' AND m.body', $searchParts) . $timeAddition;
		$topicQuery = 'm.subject' . implode(' AND m.subject', $searchParts) . $timeAddition;
	}
	else
	{
		if (!$search_params['subject_only'])
			$searchQuery = (count($searchParts) > 1 ? '(' : '') . 'm.body' . implode(' OR m.body', $searchParts) . (count($searchParts) > 1 ? ')' : '') . $timeAddition;
		$topicQuery = (count($searchParts) > 1 ? '(' : '') . 'm.subject' . implode(' OR m.subject', $searchParts) . (count($searchParts) > 1 ? ')' : '') . $timeAddition;
	}

	// Get the sorting parameters right. Default to sort by relevance descending.
	$sort_columns = array(
		'relevance',
		'numReplies',
		'ID_MSG',
	);
	if (empty($search_params['sort']) && !empty($_REQUEST['sort']))
		list ($search_params['sort'], $search_params['sort_dir']) = array_pad(explode('|', $_REQUEST['sort']), 2, '');
	$search_params['sort'] = !empty($search_params['sort']) && in_array($search_params['sort'], $sort_columns) ? $search_params['sort'] : 'relevance';
	$search_params['sort_dir'] = !empty($search_params['sort_dir']) && $search_params['sort_dir'] == 'asc' ? 'asc' : 'desc';

	$context['mark'] = array();
	foreach ($searchArray as $word)
		$context['mark'][$word] = '<b class="highlight">' . $word . '</b>';

	// All search params have been checked, let's compile them to a single string... made less simple by PHP 4.3.9 and below.
	$temp_params = $search_params;
	if (isset($temp_params['brd']))
		$temp_params['brd'] = implode(',', $temp_params['brd']);
	$context['params'] = array();
	foreach ($temp_params as $k => $v)
		$context['params'][] = $k . '|\'|' . addslashes($v);
	$context['params'] = base64_encode(implode('|"|', $context['params']));

	// ... and add the links to the link tree.
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=search;params=' . $context['params'],
		'name' => $txt[182]
	);
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=search2;params=' . $context['params'],
		'name' => $txt['search_results']
	);

	// Determine some values needed to calculate the relevance.
	$minMsg = (int) (1 - $recentPercentage) * $modSettings['maxMsgID'];
	$recentMsg = $modSettings['maxMsgID'] - $minMsg;

	$mainQuery = "
				t.ID_TOPIC,
				$weight[frequency] * IF(m.ID_MSG IS NOT NULL, COUNT(m.ID_MSG) / (t.numReplies + 1), 0) +
				$weight[age] * IF(m.ID_MSG IS NULL OR MAX(m.ID_MSG) < $minMsg, 0, (MAX(m.ID_MSG) - $minMsg) / $recentMsg) +
				$weight[length] * IF(t.numReplies < $humungousTopicPosts, t.numReplies / $humungousTopicPosts, 1) +
				$weight[subject] * t.is_subject +
				$weight[first_message] * IF(MIN(m.ID_MSG) = t.ID_FIRST_MSG, 1, 0) AS relevance,
				IF(COUNT(m.ID_MSG) = 0, t.ID_FIRST_MSG, MAX(m.ID_MSG)) AS ID_MSG, COUNT(m.ID_MSG) AS num_matches
			FROM {$db_prefix}matches AS t
				LEFT JOIN {$db_prefix}messages AS m ON (m.ID_TOPIC = t.ID_TOPIC AND $searchQuery)" . (empty($userQuery) ? '' : "
			WHERE $userQuery") ."
			GROUP BY t.ID_TOPIC";

	$context['topics'] = array();
	$use_cache = !empty($modSettings['search_cache_size']);

	// Either the results are not cached, or caching is disabled, so we need to create a temporary table.
	if (!$use_cache || empty($_SESSION['search_cache']) || $_SESSION['search_cache']['params'] != $context['params'])
	{
		// Temporary tables are preferrable, but require the right MySQL permissions.
		if (empty($modSettings['disableTemporaryTables']))
		{
			// Get rid of it if it already exists.
			mysql_query("
				DROP TABLE {$db_prefix}matches");

			$result = mysql_query("
				CREATE TEMPORARY TABLE {$db_prefix}matches (
					ID_TOPIC mediumint(8) unsigned NOT NULL default '0',
					ID_FIRST_MSG int(10) unsigned NOT NULL default '0',
					numReplies int(11) NOT NULL default '0',
					is_subject tinyint(3) unsigned NOT NULL default '0',
					PRIMARY KEY (ID_TOPIC)
				) TYPE=HEAP");

			if ($result === false)
			{
				updateSettings(array('disableTemporaryTables' => '1'));
				fatal_lang_error('unable_to_create_temporary');
			}

			if (!$search_params['subject_only'])
			{
				// Let's determine how many results we can expect.
				db_query("
					INSERT INTO {$db_prefix}matches
					SELECT DISTINCT t.ID_TOPIC, t.ID_FIRST_MSG, t.numReplies, 0 AS is_subject
					FROM {$db_prefix}topics AS t, {$db_prefix}messages AS m
					WHERE t.ID_TOPIC = m.ID_TOPIC" . (empty($search_params['brd']) ? '' : "
						AND m.ID_BOARD IN (" . implode(', ', $search_params['brd']) . ")") . (empty($userQuery) ? '' : "
						AND $userQuery") . "
						AND $searchQuery", __FILE__, __LINE__);
				$messageMatches = db_affected_rows();
			}

			// Select all topics that have subjects matching the search query.
			db_query("
				INSERT IGNORE INTO {$db_prefix}matches
				SELECT m.ID_TOPIC AS ID_TOPIC, m.ID_MSG, t.numReplies, 1 AS is_subject
				FROM {$db_prefix}topics AS t, {$db_prefix}messages AS m
				WHERE m.ID_MSG = t.ID_FIRST_MSG" . (empty($search_params['brd']) ? '' : "
					AND m.ID_BOARD IN (" . implode(', ', $search_params['brd']) . ")") . (empty($userQuery) ? '' : "
					AND $userQuery") . "
					AND $topicQuery", __FILE__, __LINE__);
			$subjectMatches = db_affected_rows();

			if (!$search_params['subject_only'])
			{
				$request = db_query("
					SELECT COUNT(ID_TOPIC)
					FROM {$db_prefix}matches", __FILE__, __LINE__);
				list ($numResults) = mysql_fetch_row($request);
				mysql_free_result($request);
			}
			else
				$numResults = $subjectMatches;

			if (empty($numResults))
				$use_cache = false;
			elseif (!$use_cache || $numResults <= $modSettings['search_results_per_page'])
			{
				$request = db_query("
					SELECT$mainQuery
					ORDER BY $search_params[sort] $search_params[sort_dir]
					LIMIT $_REQUEST[start], $modSettings[search_results_per_page]", __FILE__, __LINE__);
				while ($row = mysql_fetch_assoc($request))
					$context['topics'][$row['ID_MSG']] = array(
						'id' => $row['ID_TOPIC'],
						'relevance' => round(100 * $row['relevance'] / $weight_total, 1) . '%',
						'num_matches' => $row['num_matches'],
						'matches' => array(),
					);
				mysql_free_result($request);

				// We don't need cache, thank you.
				$use_cache = false;
			}
			// Search is not yet cached, let's cache it.
			elseif ($use_cache)
			{
				$modSettings['search_pointer'] = empty($modSettings['search_pointer']) ? 0 : (int) $modSettings['search_pointer'];

				// Increase the pointer.
				updateSettings(array('search_pointer' => $modSettings['search_pointer'] >= 255 ? 0 : $modSettings['search_pointer'] + 1));

				// Make sure this value isn't larger than 255 or the tinyint key field wouldn't be able to handle it.
				$modSettings['search_cache_size'] = empty($modSettings['search_cache_size']) || $modSettings['search_cache_size'] > 255 ? 255 : $modSettings['search_cache_size'];

				// Remove old cached results and (if set) the previous session cached result.
				db_query("
					DELETE FROM {$db_prefix}log_search
					WHERE (ID_SEARCH >= $modSettings[search_pointer]" . ($modSettings['search_pointer'] < $modSettings['search_cache_size'] ? '
						AND ID_SEARCH < ' . (256 + $modSettings['search_pointer'] - $modSettings['search_cache_size']) : '
						OR ID_SEARCH < ' . ($modSettings['search_pointer'] - $modSettings['search_cache_size'])) . ')' . (isset($_SESSION['search_cache']['ID_SEARCH']) ? "
						OR ID_SEARCH = " . $_SESSION['search_cache']['ID_SEARCH'] : ''), __FILE__, __LINE__);

				// Insert the new cached results.
				if (!empty($numResults))
					db_query("
						INSERT IGNORE INTO {$db_prefix}log_search
							(ID_SEARCH, ID_TOPIC, relevance, ID_MSG, num_matches)
						SELECT $modSettings[search_pointer], $mainQuery", __FILE__, __LINE__);
				$numResults = empty($numResults) ? 0 : db_affected_rows();

				// Store it for the session.
				$_SESSION['search_cache'] = array(
					'ID_SEARCH' => $modSettings['search_pointer'],
					'num_results' => $numResults,
					'params' => $context['params'],
				);
			}

			// Get rid of the temporary table.
			$request = db_query("
				DROP TABLE {$db_prefix}matches", __FILE__, __LINE__);
		}

		// Create temporary tables is disabled, we're gonna need to use PHP's memory and sorting.
		else
		{
			$matchingTopics = array();

			if (!$search_params['subject_only'])
			{
				// Get all the topics with a message match.
				$request = db_query("
					SELECT DISTINCT t.ID_TOPIC, t.ID_FIRST_MSG, t.numReplies, 0 AS is_subject
					FROM {$db_prefix}topics AS t, {$db_prefix}messages AS m
					WHERE t.ID_TOPIC = m.ID_TOPIC" . (empty($search_params['brd']) ? '' : "
						AND m.ID_BOARD IN (" . implode(', ', $search_params['brd']) . ")") . (empty($userQuery) ? '' : "
						AND $userQuery") . "
						AND $searchQuery", __FILE__, __LINE__);
				while ($row = mysql_fetch_assoc($request))
					$matchingTopics[$row['ID_TOPIC']] = $row;
				$messageMatches = count($matchingTopics);
			}

			// Get all the topics with a subject match.
			db_query("
				SELECT m.ID_TOPIC AS ID_TOPIC, m.ID_MSG, t.numReplies, 1 AS is_subject
				FROM {$db_prefix}topics AS t, {$db_prefix}messages AS m
				WHERE m.ID_MSG = t.ID_FIRST_MSG" . (empty($search_params['brd']) ? '' : "
					AND m.ID_BOARD IN (" . implode(', ', $search_params['brd']) . ")") . (empty($userQuery) ? '' : "
					AND $userQuery") . "
					AND $topicQuery", __FILE__, __LINE__);
			while ($row = mysql_fetch_assoc($request))
				$matchingTopics[$row['ID_TOPIC']] = $row;
			$subjectMatches = db_affected_rows();

			$numResults = $search_params['subject_only'] ? $subjectMatches : count($matchingTopics);

			if (empty($numResults))
				$use_cache = false;
			elseif (!$use_cache || $numResults <= $modSettings['search_results_per_page'])
			{
				$sort = array();
				$request = db_query("
					SELECT
						t.ID_TOPIC, COUNT(m.ID_MSG) AS numMsg, MAX(m.ID_MSG) AS lastMatch, MIN(m.ID_MSG) AS firstMatch
					FROM {$db_prefix}topics AS t
						LEFT JOIN {$db_prefix}messages AS m ON (m.ID_TOPIC = t.ID_TOPIC AND $searchQuery)" . (empty($userQuery) ? '
					WHERE t.ID_TOPIC IN (' . implode(', ', array_keys($matchingTopics)) . ')' : "
					WHERE $userQuery
						AND t.ID_TOPIC IN (" . implode(', ', array_keys($matchingTopics)) . ")") . "
					GROUP BY t.ID_TOPIC
					LIMIT $_REQUEST[start], $modSettings[search_results_per_page]", __FILE__, __LINE__);
				while ($row = mysql_fetch_assoc($request))
				{
					$relevance =
						$weight['frequency'] * ($row['numMsg'] == 0 ? 0 : $row['numMsg'] / ($matchingTopics[$row['ID_TOPIC']]['numReplies'] + 1)) +
						$weight['age'] * ($row['ID_MSG'] === null || $row['lastMatch'] < $minMsg ? 0 : ($row['lastMatch'] - $minMsg) / $recentMsg) +
						$weight['length'] * ($matchingTopics[$row['ID_TOPIC']]['numReplies'] < $humungousTopicPosts ? $matchingTopics[$row['ID_TOPIC']]['numReplies'] / $humungousTopicPosts : 1) +
						$weight['subject'] * $matchingTopics[$row['ID_TOPIC']]['is_subject'] +
						$weight['first_message'] * ($row['firstMatch'] == $matchingTopics[$row['ID_TOPIC']]['ID_FIRST_MSG'] ? 1 : 0);
					$ID_MSG = $row['numMsg'] == 0 ? $matchingTopics[$row['ID_TOPIC']]['ID_FIRST_MSG'] : $row['lastMatch'];
					$sort[$ID_MSG] = $search_params['sort'] == 'relevance' ? $relevance : ($search_params['sort'] == 'numReplies' ? $matchingTopics[$row['ID_TOPIC']]['numReplies'] : $ID_MSG);
					$tmp[$ID_MSG] = array(
						'id' => $row['ID_TOPIC'],
						'relevance' => round(100 * $relevance / $weight_total, 1) . '%',
						'num_matches' => $row['num_matches'],
						'matches' => array(),
					);
				}
				mysql_free_result($request);

				// Do the manual sorting.
				if ($search_params['sort_dir'] == 'desc')
					krsort($sort);
				else
					ksort($sort);
				foreach ($sort as $ID_MSG => $value)
					$context['topics'][$ID_MSG] = $tmp[$ID_MSG];
				unset($tmp);

				// We don't need cache, thank you.
				$use_cache = false;
			}
			// Search is not yet cached, let's do that now.
			elseif ($use_cache)
			{
				$modSettings['search_pointer'] = empty($modSettings['search_pointer']) ? 0 : (int) $modSettings['search_pointer'];

				$modSettings['search_cache_size'] = empty($modSettings['search_cache_size']) || $modSettings['search_cache_size'] > 255 ? 255 : $modSettings['search_cache_size'];

				// Remove old cached results.
				db_query("
					DELETE FROM {$db_prefix}log_search
					WHERE (ID_SEARCH >= $modSettings[search_pointer]" . ($modSettings['search_pointer'] < $modSettings['search_cache_size'] ? '
						AND ID_SEARCH < ' . (256 + $modSettings['search_pointer'] - $modSettings['search_cache_size']) : '
						OR ID_SEARCH < ' . ($modSettings['search_pointer'] - $modSettings['search_cache_size'])) . ')' . (isset($_SESSION['search_cache']['ID_SEARCH']) ? "
						OR ID_SEARCH = " . $_SESSION['search_cache']['ID_SEARCH'] : ''), __FILE__, __LINE__);

				// Insert the new results into cache.
				if (!empty($numResults))
				{
					$insertRows = array();
					$sort = array();

					// Get all topics that match the search query.
					$request = db_query("
						SELECT
							t.ID_TOPIC, COUNT(m.ID_MSG) AS numMsg, MAX(m.ID_MSG) AS lastMatch, MIN(m.ID_MSG) AS firstMatch
						FROM {$db_prefix}topics AS t
							LEFT JOIN {$db_prefix}messages AS m ON (m.ID_TOPIC = t.ID_TOPIC AND $searchQuery)" . (empty($userQuery) ? '
						WHERE t.ID_TOPIC IN (' . implode(', ', array_keys($matchingTopics)) . ')' : "
						WHERE $userQuery
							AND t.ID_TOPIC IN (" . implode(', ', array_keys($matchingTopics)) . ")") . "
						GROUP BY t.ID_TOPIC", __FILE__, __LINE__);
					while ($row = mysql_fetch_assoc($request))
					{
						$relevance =
							$weight['frequency'] * ($row['numMsg'] == 0 ? 0 : $row['numMsg'] / ($matchingTopics[$row['ID_TOPIC']]['numReplies'] + 1)) +
							$weight['age'] * ($row['ID_MSG'] === null || $row['lastMatch'] < $minMsg ? 0 : ($row['lastMatch'] - $minMsg) / $recentMsg) +
							$weight['length'] * ($matchingTopics[$row['ID_TOPIC']]['numReplies'] < $humungousTopicPosts ? $matchingTopics[$row['ID_TOPIC']]['numReplies'] / $humungousTopicPosts : 1) +
							$weight['subject'] * $matchingTopics[$row['ID_TOPIC']]['is_subject'] +
							$weight['first_message'] * ($row['firstMatch'] == $matchingTopics[$row['ID_TOPIC']]['ID_FIRST_MSG'] ? 1 : 0);
						$ID_MSG = $row['numMsg'] == 0 ? $matchingTopics[$row['ID_TOPIC']]['ID_FIRST_MSG'] : $row['lastMatch'];
						$sort[$ID_MSG] = $search_params['sort'] == 'relevance' ? $relevance : ($search_params['sort'] == 'numReplies' ? $matchingTopics[$row['ID_TOPIC']]['numReplies'] : $ID_MSG);
						$tmp[$ID_MSG] .= "($modSettings[search_pointer], $row[ID_TOPIC], $relevance, $ID_MSG, $row[numMsg])";
					}
					mysql_free_result($request);

					// Do the sorting of the rows.
					if ($search_params['sort_dir'] == 'desc')
						krsort($sort);
					else
						ksort($sort);
					foreach ($sort as $ID_MSG => $value)
						$insertRows[$ID_MSG] = $tmp[$ID_MSG];
					unset($tmp);

					// Insert the matching topics into the cache.
					db_query("
						INSERT INTO {$db_prefix}log_search
							(ID_SEARCH, ID_TOPIC, relevance, ID_MSG, num_matches)
						VALUES " . implode(', ', $insertRows), __FILE__, __LINE__);
				}
				$numResults = empty($numResults) ? 0 : db_affected_rows();

				// Store the cache information into the session.
				$_SESSION['search_cache'] = array(
					'ID_SEARCH' => $modSettings['search_pointer'],
					'num_results' => $numResults,
					'params' => $context['params'],
				);

				// Increase the cache pointer.
				updateSettings(array('search_pointer' => $modSettings['search_pointer'] >= 255 ? 0 : $modSettings['search_pointer'] + 1));
			}
		}
	}

	// Current search should be cached by now, grab it.
	if ($use_cache)
	{
		$request = db_query("
			SELECT ls.ID_TOPIC, ls.ID_MSG, ls.relevance, ls.num_matches
			FROM {$db_prefix}log_search AS ls" . ($search_params['sort'] == 'numReplies' ? ", {$db_prefix}topics AS t" : '') . "
			WHERE ID_SEARCH = " . $_SESSION['search_cache']['ID_SEARCH'] . ($search_params['sort'] == 'numReplies' ? "
				AND t.ID_TOPIC = ls.ID_TOPIC" : '') . "
			ORDER BY $search_params[sort] $search_params[sort_dir]
			LIMIT $_REQUEST[start], $modSettings[search_results_per_page]", __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
			$context['topics'][$row['ID_MSG']] = array(
				'id' => $row['ID_TOPIC'],
				'relevance' => round($row['relevance'] / 10, 1) . '%',
				'num_matches' => $row['num_matches'],
				'matches' => array(),
			);
		mysql_free_result($request);

		$numResults = $_SESSION['search_cache']['num_results'];
	}

	// Now that we know how many results to expect we can start calculating the page numbers.
	$context['page_index'] = constructPageIndex($scripturl . '?action=search2;params=' . $context['params'], $_REQUEST['start'], $numResults, $modSettings['search_results_per_page'], false);

	if (!empty($context['topics']))
	{
		// Create an array for the permissions.
		$boards_can = array(
			'post_reply_own' => boardsAllowedTo('post_reply_own'),
			'post_reply_any' => boardsAllowedTo('post_reply_any'),
			'mark_any_notify' => boardsAllowedTo('mark_any_notify')
		);

		// Load the posters...
		$request = db_query("
			SELECT ID_MEMBER
			FROM {$db_prefix}messages
			WHERE ID_MEMBER != 0
				AND ID_MSG IN (" . implode(', ', array_keys($context['topics'])) . ')', __FILE__, __LINE__);
		$posters = array();
		while ($row = mysql_fetch_assoc($request))
			$posters[] = $row['ID_MEMBER'];
		mysql_free_result($request);

		if (!empty($posters))
			loadMemberData(array_unique($posters));

		// Get the messages out for the callback - select enough that it can be made to look just like Display.
		$messages_request = db_query("
			SELECT
				m.ID_MSG, m.subject, m.posterName, m.posterEmail, m.posterTime, m.ID_MEMBER,
				m.icon, m.posterIP, m.body, m.smileysEnabled, m.modifiedTime, m.modifiedName,
				a.filename, IFNULL(a.size, 0) AS filesize, a.ID_ATTACH, a.downloads,
				first_m.ID_MSG AS first_msg, first_m.subject AS first_subject, first_m.icon, first_m.posterTime AS first_posterTime,
				first_mem.ID_MEMBER AS first_member_id, IFNULL(first_mem.realName, first_m.posterName) AS first_member_name,
				last_m.ID_MSG AS last_msg, last_m.posterTime AS last_posterTime, last_mem.ID_MEMBER AS last_member_id,
				IFNULL(last_mem.realName, last_m.posterName) AS last_member_name,
				t.ID_TOPIC, t.isSticky, t.locked, t.ID_POLL, t.numReplies, t.numViews,
				b.ID_BOARD, b.name AS bName, c.ID_CAT, c.name AS cName
			FROM {$db_prefix}messages AS m, {$db_prefix}topics AS t, {$db_prefix}boards AS b, {$db_prefix}categories AS c, {$db_prefix}messages AS first_m, {$db_prefix}messages AS last_m
				LEFT JOIN {$db_prefix}members AS first_mem ON (first_mem.ID_MEMBER = first_m.ID_MEMBER)
				LEFT JOIN {$db_prefix}members AS last_mem ON (last_mem.ID_MEMBER = first_m.ID_MEMBER)
				LEFT JOIN {$db_prefix}attachments AS a ON (a.ID_MSG = m.ID_MSG)
			WHERE m.ID_MSG IN (" . implode(', ', array_keys($context['topics'])) . ")
				AND t.ID_TOPIC = m.ID_TOPIC
				AND b.ID_BOARD = t.ID_BOARD
				AND c.ID_CAT = b.ID_CAT
				AND first_m.ID_MSG = t.ID_FIRST_MSG
				AND last_m.ID_MSG = t.ID_LAST_MSG
			ORDER BY FIND_IN_SET(m.ID_MSG, '" . implode(',', array_keys($context['topics'])) . "')
			LIMIT " . count($context['topics']), __FILE__, __LINE__);
		// Note that the reg-exp slows things alot, but makes things make a lot more sense.
	}

	$context['key_words'] = &$searchArray;

	// Set the basic stuff for the template.
	$context['allow_hide_email'] = !empty($modSettings['allow_hideEmail']);

	$context['sub_template'] = 'results';
	$context['page_title'] = $txt[166];
	$context['get_topics'] = 'prepareSearchContext';
	$context['can_send_pm'] = allowedTo('pm_send');

	loadJumpTo();
}

// Callback to return messages - saves memory.
function prepareSearchContext($reset = false)
{
	global $txt, $modSettings, $scripturl, $ID_MEMBER;
	global $themeUser, $context, $messages_request, $db_prefix, $attachments, $boards_can;

	// Remember which message this is.  (ie. reply #83)
	static $counter = null;
	if ($counter == null || $reset)
		$counter = $_REQUEST['start'] + 1;

	// If the query returned false, bail.
	if ($messages_request == false)
		return false;

	// Start from the beginning...
	if ($reset)
		return @mysql_data_seek($messages_request, 0);

	// Attempt to get the next message.
	$message = mysql_fetch_assoc($messages_request);
	if (!$message)
		return false;

	// Can't have an empty subject can we?
	$message['subject'] = $message['subject'] != '' ? $message['subject'] : $txt[24];

	// If it couldn't load, or the user was a guest.... someday may be done with a guest table.
	if (!loadMemberContext($message['ID_MEMBER']))
	{
		// Notice this information isn't used anywhere else.... *cough guest table cough*
		$themeUser[$message['ID_MEMBER']]['name'] = $message['posterName'];
		$themeUser[$message['ID_MEMBER']]['id'] = 0;
		$themeUser[$message['ID_MEMBER']]['group'] = $txt[28];
		$themeUser[$message['ID_MEMBER']]['link'] = $message['posterName'];
		$themeUser[$message['ID_MEMBER']]['email'] = $message['posterEmail'];
	}
	$themeUser[$message['ID_MEMBER']]['ip'] = $message['posterIP'];

	// Do the censor thang...
	censorText($message['body']);
	censorText($message['subject']);

	// Shorten this message if necessary.
	if ($context['compact'])
	{
		// Set the number of characters before and after the searched keyword.
		$charLimit = 40;

		$message['body'] = strtr($message['body'], array("\n" => ' ', '<br />' => "\n"));
		$message['body'] = doUBBC($message['body'], $message['smileysEnabled']);
		$message['body'] = strip_tags($message['body']);

		if (strlen($message['body']) > $charLimit)
		{
			if (empty($context['key_words']))
				$message['body'] = htmlspecialchars(substr(un_htmlspecialchars($message['body']), 0, $charLimit) . (strlen($message['body']) > $charLimit ? '<b>...</b>' : ''), ENT_QUOTES);
			else
			{
				$matchString = '';
				foreach ($context['key_words'] as $keyword)
					$matchString .= strtr(preg_quote($keyword, '/'), array('\*' => '.+?')) . '|';
				$matchString = substr($matchString, 0, -1);
				$message['body'] = un_htmlspecialchars(str_replace('&nbsp;', ' ', $message['body']));
				if (empty($modSettings['search_match_complete_words']))
					preg_match_all('/([^\s\W]{' . $charLimit . '}[\s\W]|[\s\W].{0,' . $charLimit . '}?|^)(' . $matchString . ')(.{0,' . $charLimit . '}[\s\W]|[^\s\W]{' . $charLimit . '})/is', $message['body'], $matches);
				else
					preg_match_all('/([^\s\W]{' . $charLimit . '}[\s\W]|[\s\W].{0,' . $charLimit . '}?[\s\W]|^)(' . $matchString . ')([\s\W].{0,' . $charLimit . '}[\s\W]|[\s\W][^\s\W]{' . $charLimit . '})/is', $message['body'], $matches);
				$message['body'] = '';
				foreach ($matches[0] as $index => $match)
					$message['body'] .= '<b>...</b>&nbsp;' . htmlspecialchars($match, ENT_QUOTES) . '&nbsp;<b>...</b><br />';
			}

			// Re-fix the international characters.
			$message['body'] = preg_replace('~&amp;#(\d{4,5}|[3-9]\d{2,4}|2[6-9]\d);~', '&#$1;', $message['body']);
		}
	}
	else
	{
		// Run UBBC interpreter on the message.
		$message['body'] = doUBBC($message['body'], $message['smileysEnabled']);
	}

	$output = array_merge($context['topics'][$message['ID_MSG']], array(
		'is_sticky' => !empty($modSettings['enableStickyTopics']) && !empty($message['isSticky']),
		'is_locked' => !empty($message['locked']),
		'is_poll' => $modSettings['pollMode'] == '1' && $message['ID_POLL'] > 0,
		'is_hot' => $message['numReplies'] >= $modSettings['hotTopicPosts'],
		'is_very_hot' => $message['numReplies'] >= $modSettings['hotTopicVeryPosts'],
		'views' => $message['numViews'],
		'replies' => $message['numReplies'],
		'can_reply' => in_array($message['ID_BOARD'], $boards_can['post_reply_any']) || in_array(0, $boards_can['post_reply_any']),
		'can_mark_notify' => in_array($message['ID_BOARD'], $boards_can['mark_any_notify']) || in_array(0, $boards_can['mark_any_notify']),
		'first_post' => array(
			'id' => $message['first_msg'],
			'time' => timeformat($message['first_posterTime']),
			'subject' => $message['first_subject'],
			'href' => $scripturl . '?topic=' . $message['ID_TOPIC'] . '.0',
			'link' => '<a href="' . $scripturl . '?topic=' . $message['ID_TOPIC'] . '.0">' . $message['subject'] . '</a>',
			'icon' => $message['icon'],
			'member' => array(
				'id' => $message['first_member_id'],
				'name' => $message['first_member_name'],
				'href' => !empty($message['first_member_id']) ? $scripturl . '?action=profile;u=' . $message['first_member_id'] : '',
				'link' => !empty($message['first_member_id']) ? '<a href="' . $scripturl . '?action=profile;u=' . $message['first_member_id'] . '" title="' . $txt[92] . ' ' . $message['first_member_name'] . '">' . $message['first_member_name'] . '</a>' : $message['first_member_name']
			)
		),
		'last_post' => array(
			'id' => $message['last_msg'],
			'time' => timeformat($message['last_posterTime']),
			'timestamp' => $message['last_posterTime'],
			'member' => array(
				'id' => $message['last_member_id'],
				'name' => $message['last_member_name'],
				'href' => !empty($message['last_member_id']) ? $scripturl . '?action=profile;u=' . $message['last_member_id'] : '',
				'link' => !empty($message['last_member_id']) ? '<a href="' . $scripturl . '?action=profile;u=' . $message['last_member_id'] . '" title="' . $txt[92] . ' ' . $message['last_member_name'] . '">' . $message['last_member_name'] . '</a>' : $message['last_member_name']
			)
		),
		'board' => array(
			'id' => $message['ID_BOARD'],
			'name' => $message['bName'],
			'href' => $scripturl . '?board=' . $message['ID_BOARD'] . '.0',
			'link' => '<a href="' . $scripturl . '?board=' . $message['ID_BOARD'] . '.0">' . $message['bName'] . '</a>'
		),
		'category' => array(
			'id' => $message['ID_CAT'],
			'name' => $message['cName'],
			'href' => $scripturl . '#' . $message['ID_CAT'],
			'link' => '<a href="' . $scripturl . '#' . $message['ID_CAT'] . '">' . $message['cName'] . '</a>'
		)
	));
	determineTopicClass($output);

	$body_highlighted = $message['body'];
	$subject_highlighted = $message['subject'];

	foreach ($context['key_words'] as $query)
	{
		// Fix the international characters in the keyword too.
		$query = preg_replace('~&amp;#(\d{4,5}|[3-9]\d{2,4}|2[6-9]\d);~', '&#$1;', strtr(preg_quote($query, '/'), array('&' => '&amp;')));

		$body_highlighted = preg_replace('/((<[^>]*)|' . $query . ')/ie', "'\$2' == '\$1' ? stripslashes('\$1') : '<b class=\"highlight\">\$1</b>'", $body_highlighted);
		$subject_highlighted = preg_replace('/((<[^>]*)|' . $query . ')/ie', "'$2' == '$1' ? stripslashes('$1') : '<b class=\"highlight\">$1</b>'", $subject_highlighted);
	}

	$output['matches'][] = array(
		'id' => $message['ID_MSG'],
		'attachment' => loadAttachmentContext($message['ID_MSG']),
		'alternate' => $counter % 2,
		'member' => &$themeUser[$message['ID_MEMBER']],
		'icon' => $message['icon'],
		'subject' => $message['subject'],
		'subject_highlighted' => $subject_highlighted,
		'time' => timeformat($message['posterTime']),
		'timestamp' => $message['posterTime'],
		'counter' => $counter,
		'modified' => array(
			'time' => timeformat($message['modifiedTime']),
			'timestamp' => $message['modifiedTime'],
			'name' => $message['modifiedName']
		),
		'body' => $message['body'],
		'body_highlighted' => $body_highlighted,
		'start' => 'msg' . $message['ID_MSG']
	);
	$counter++;

	return $output;
}

?>