<?php
/******************************************************************************
* Stats.php                                                                   *
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

/*	This function has only one job: providing a display for forum statistics.
	As such, it has only one function:

	void DisplayStats()
		- gets all the statistics in order and puts them in.
		- uses the Stats template and language file. (and main sub template.)
		- requires the view_stats permission.
		- accessed from ?action=stats.
*/

// Display some useful/interesting board statistics.
function DisplayStats()
{
	global $txt, $months, $scripturl, $db_prefix, $modSettings, $user_info, $context;

	loadTemplate('Stats');
	loadLanguage('Stats');

	isAllowedTo('view_stats');

	// Build the link tree......
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=stats',
		'name' => $txt['smf_stats_1']
	);
	$context['page_title'] = $context['forum_name'] . ' - ' . $txt['smf_stats_1'];

	$context['show_member_list'] = allowedTo('view_mlist');

	// Get averages...
	$result = db_query("
		SELECT
			SUM(posts) AS posts, SUM(topics) AS topics, SUM(registers) AS registers,
			SUM(mostOn) AS mostOn, MIN(date) AS date
		FROM {$db_prefix}log_activity", __FILE__, __LINE__);
	$row = mysql_fetch_assoc($result);
	mysql_free_result($result);

	// This would be the amount of time the forum has been up... in days...
	$total_days_up = ceil((time() - strtotime($row['date'])) / (60 * 60 * 24));

	$context['average_posts'] = round($row['posts'] / $total_days_up, 2);
	$context['average_topics'] = round($row['topics'] / $total_days_up, 2);
	$context['average_members'] = round($row['registers'] / $total_days_up, 2);
	$context['average_online'] = round($row['mostOn'] / $total_days_up, 2);

	// How many users are online now.
	$result = db_query("
		SELECT COUNT(session)
		FROM {$db_prefix}log_online", __FILE__, __LINE__);
	list ($context['users_online']) = mysql_fetch_row($result);
	mysql_free_result($result);

	// Statistics such as number of boards, categories, etc.
	$result = db_query("
		SELECT COUNT(b.ID_BOARD)
		FROM {$db_prefix}boards AS b", __FILE__, __LINE__);
	list ($context['num_boards']) = mysql_fetch_row($result);
	mysql_free_result($result);

	$result = db_query("
		SELECT COUNT(c.ID_CAT)
		FROM {$db_prefix}categories AS c", __FILE__, __LINE__);
	list ($context['num_categories']) = mysql_fetch_row($result);
	mysql_free_result($result);

	$context['num_members'] = &$modSettings['memberCount'];
	$context['num_posts'] = &$modSettings['totalMessages'];
	$context['num_topics'] = &$modSettings['totalTopics'];
	$context['most_members_online'] = array(
		'number' => &$modSettings['mostOnline'],
		'date' => timeformat($modSettings['mostDate'])
	);
	$context['latest_member'] = array(
		'name' => $modSettings['latestRealName'],
		'id' => $modSettings['latestMember'],
		'href' => $scripturl . '?action=profile;u=' . $modSettings['latestMember'],
		'link' => '<a href="' . $scripturl . '?action=profile;u=' . $modSettings['latestMember'] . '"><b>' . $modSettings['latestRealName'] . '</b></a>'
	);

	// Male vs. female ratio.
	$result = db_query("
		SELECT COUNT(ID_MEMBER) AS memberCount, gender
		FROM {$db_prefix}members
		GROUP BY gender", __FILE__, __LINE__);
	$context['gender'] = array();
	while ($row = mysql_fetch_assoc($result))
	{
		// Assuming we're telling... male or female?
		if (!empty($row['gender']))
			$context['gender'][$row['gender'] == 2 ? 'females' : 'males'] = $row['memberCount'];
	}
	mysql_free_result($result);

	if (empty($context['gender']['males']))
		$context['gender']['males'] = 0;
	if (empty($context['gender']['females']))
		$context['gender']['females'] = 0;

	// Try and come up with some "sensible" default states in case of a non-mixed board.
	if ($context['gender']['males'] == $context['gender']['females'])
		$context['gender']['ratio'] = '1:1';
	elseif ($context['gender']['males'] == 0)
		$context['gender']['ratio'] = '0:1';
	elseif ($context['gender']['females'] == 0)
		$context['gender']['ratio'] = '1:0';
	elseif ($context['gender']['males'] > $context['gender']['females'])
		$context['gender']['ratio'] = round($context['gender']['males'] / $context['gender']['females'], 1) . ':1';
	elseif ($context['gender']['females'] > $context['gender']['males'])
		$context['gender']['ratio'] = '1:' . round($context['gender']['females'] / $context['gender']['males'], 1);

	$date = strftime('%Y%m%d', forum_time(false));

	// Members online so far today.
	$result = db_query("
		SELECT mostOn
		FROM {$db_prefix}log_activity
		WHERE date = $date
		LIMIT 1", __FILE__, __LINE__);
	list ($context['online_today']) = mysql_fetch_row($result);
	mysql_free_result($result);

	// Poster top 10.
	$members_result = db_query("
		SELECT ID_MEMBER, realName, posts
		FROM {$db_prefix}members
		WHERE posts > 0
		ORDER BY posts DESC
		LIMIT 10", __FILE__, __LINE__);
	$context['top_posters'] = array();
	$max_num_posts = 1;
	while ($row_members = mysql_fetch_assoc($members_result))
	{
		$context['top_posters'][] = array(
			'name' => $row_members['realName'],
			'id' => $row_members['ID_MEMBER'],
			'num_posts' => $row_members['posts'],
			'href' => $scripturl . '?action=profile;u=' . $row_members['ID_MEMBER'],
			'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row_members['ID_MEMBER'] . '">' . $row_members['realName'] . '</a>'
		);

		if ($max_num_posts < $row_members['posts'])
			$max_num_posts = $row_members['posts'];
	}
	foreach ($context['top_posters'] as $i => $poster)
		$context['top_posters'][$i]['post_percent'] = round(($poster['num_posts'] * 100) / $max_num_posts);

	// Board top 10.
	$boards_result = db_query("
		SELECT ID_BOARD, name, numPosts
		FROM {$db_prefix}boards AS b
		WHERE $user_info[query_see_board]" . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? "
			AND b.ID_BOARD != $modSettings[recycle_board]" : '') . "
		ORDER BY numPosts DESC
		LIMIT 10", __FILE__, __LINE__);
	$context['top_boards'] = array();
	$max_num_posts = 1;
	while ($row_board = mysql_fetch_assoc($boards_result))
	{
		$context['top_boards'][] = array(
			'id' => $row_board['ID_BOARD'],
			'name' => $row_board['name'],
			'num_posts' => $row_board['numPosts'],
			'href' => $scripturl . '?board=' . $row_board['ID_BOARD'] . '.0',
			'link' => '<a href="' . $scripturl . '?board=' . $row_board['ID_BOARD'] . '.0">' . $row_board['name'] . '</a>'
		);

		if ($max_num_posts < $row_board['numPosts'])
			$max_num_posts = $row_board['numPosts'];
	}
	foreach ($context['top_boards'] as $i => $board)
		$context['top_boards'][$i]['post_percent'] = round(($board['num_posts'] * 100) / $max_num_posts);

	// Topic replies top 10.
	$topic_reply_result = db_query("
		SELECT m.subject, t.numReplies, t.ID_BOARD, t.ID_TOPIC, b.name
		FROM {$db_prefix}topics AS t, {$db_prefix}messages AS m, {$db_prefix}boards AS b
		WHERE m.ID_MSG = t.ID_FIRST_MSG
			AND $user_info[query_see_board]" . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? "
			AND b.ID_BOARD != $modSettings[recycle_board]" : '') . "
			AND t.ID_BOARD = b.ID_BOARD
		ORDER BY t.numReplies DESC
		LIMIT 10", __FILE__, __LINE__);
	$context['top_topics_replies'] = array();
	$max_num_replies = 1;
	while ($row_topic_reply = mysql_fetch_assoc($topic_reply_result))
	{
		censorText($row_topic_reply['subject']);

		$context['top_topics_replies'][] = array(
			'id' => $row_topic_reply['ID_TOPIC'],
			'board' => array(
				'id' => $row_topic_reply['ID_BOARD'],
				'name' => $row_topic_reply['name'],
				'href' => $scripturl . '?board=' . $row_topic_reply['ID_BOARD'] . '.0',
				'link' => '<a href="' . $scripturl . '?board=' . $row_topic_reply['ID_BOARD'] . '.0">' . $row_topic_reply['name'] . '</a>'
			),
			'subject' => $row_topic_reply['subject'],
			'num_replies' => $row_topic_reply['numReplies'],
			'href' => $scripturl . '?topic=' . $row_topic_reply['ID_TOPIC'] . '.0',
			'link' => '<a href="' . $scripturl . '?topic=' . $row_topic_reply['ID_TOPIC'] . '.0">' . $row_topic_reply['subject'] . '</a>'
		);

		if ($max_num_replies < $row_topic_reply['numReplies'])
			$max_num_replies = $row_topic_reply['numReplies'];
	}
	foreach ($context['top_topics_replies'] as $i => $topic)
		$context['top_topics_replies'][$i]['post_percent'] = round(($topic['num_replies'] * 100) / $max_num_replies);

	// Topic views top 10.
	$topic_view_result = db_query("
		SELECT m.subject, t.numViews, t.ID_BOARD, t.ID_TOPIC, b.name
		FROM {$db_prefix}topics AS t, {$db_prefix}messages AS m, {$db_prefix}boards AS b
		WHERE m.ID_MSG = t.ID_FIRST_MSG
			AND $user_info[query_see_board]" . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? "
			AND b.ID_BOARD != $modSettings[recycle_board]" : '') . "
			AND t.ID_BOARD = b.ID_BOARD
		ORDER BY t.numViews DESC
		LIMIT 10", __FILE__, __LINE__);
	$context['top_topics_views'] = array();
	$max_num_views = 1;
	while ($row_topic_views = mysql_fetch_assoc($topic_view_result))
	{
		censorText($row_topic_views['subject']);

		$context['top_topics_views'][] = array(
			'id' => $row_topic_views['ID_TOPIC'],
			'board' => array(
				'id' => $row_topic_views['ID_BOARD'],
				'name' => $row_topic_views['name'],
				'href' => $scripturl . '?board=' . $row_topic_views['ID_BOARD'] . '.0',
				'link' => '<a href="' . $scripturl . '?board=' . $row_topic_views['ID_BOARD'] . '.0">' . $row_topic_views['name'] . '</a>'
			),
			'subject' => $row_topic_views['subject'],
			'num_views' => $row_topic_views['numViews'],
			'href' => $scripturl . '?topic=' . $row_topic_views['ID_TOPIC'] . '.0',
			'link' => '<a href="' . $scripturl . '?topic=' . $row_topic_views['ID_TOPIC'] . '.0">' . $row_topic_views['subject'] . '</a>'
		);

		if ($max_num_views < $row_topic_views['numViews'])
			$max_num_views = $row_topic_views['numViews'];
	}
	foreach ($context['top_topics_views'] as $i => $topic)
		$context['top_topics_views'][$i]['post_percent'] = round(($topic['num_views'] * 100) / $max_num_views);

	// Topic poster top 10.
	$members_result = db_query("
		SELECT t.ID_MEMBER_STARTED, COUNT(t.ID_TOPIC) AS hits, mem.realName
		FROM {$db_prefix}topics AS t, {$db_prefix}members AS mem
		WHERE t.ID_MEMBER_STARTED = mem.ID_MEMBER" . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? "
			AND t.ID_BOARD != $modSettings[recycle_board]" : '') . "
		GROUP BY t.ID_MEMBER_STARTED
		ORDER BY hits DESC
		LIMIT 10", __FILE__, __LINE__);
	$context['top_starters'] = array();
	$max_num_topics = 1;
	while ($row_members = mysql_fetch_assoc($members_result))
	{
		$context['top_starters'][] = array(
			'name' => $row_members['realName'],
			'id' => $row_members['ID_MEMBER_STARTED'],
			'num_topics' => $row_members['hits'],
			'href' => $scripturl . '?action=profile;u=' . $row_members['ID_MEMBER_STARTED'],
			'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row_members['ID_MEMBER_STARTED'] . '">' . $row_members['realName'] . '</a>'
		);

		if ($max_num_topics < $row_members['hits'])
			$max_num_topics = $row_members['hits'];
	}
	foreach ($context['top_starters'] as $i => $topic)
		$context['top_starters'][$i]['post_percent'] = round(($topic['num_topics'] * 100) / $max_num_topics);

	// Time online top 10.
	$members_result = db_query("
		SELECT ID_MEMBER, realName, totalTimeLoggedIn
		FROM {$db_prefix}members
		ORDER BY totalTimeLoggedIn DESC
		LIMIT 10", __FILE__, __LINE__);
	$context['top_time_online'] = array();
	$max_time_online = 1;
	while ($row_members = mysql_fetch_assoc($members_result))
	{
		// Figure out the days, hours and minutes.
		$timeDays = floor($row_members['totalTimeLoggedIn'] / 86400);
		$timeHours = floor(($row_members['totalTimeLoggedIn'] % 86400) / 3600);

		// Figure out which things to show... (days, hours, minutes, etc.)
		$timelogged = '';
		if ($timeDays > 0)
			$timelogged .= $timeDays . $txt['totalTimeLogged5'];
		if ($timeHours > 0)
			$timelogged .= $timeHours . $txt['totalTimeLogged6'];
		$timelogged .= floor(($row_members['totalTimeLoggedIn'] % 3600) / 60) . $txt['totalTimeLogged7'];

		$context['top_time_online'][] = array(
			'id' => $row_members['ID_MEMBER'],
			'name' => $row_members['realName'],
			'time_online' => $timelogged,
			'seconds_online' => $row_members['totalTimeLoggedIn'],
			'href' => $scripturl . '?action=profile;u=' . $row_members['ID_MEMBER'],
			'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row_members['ID_MEMBER'] . '">' . $row_members['realName'] . '</a>'
		);

		if ($max_time_online < $row_members['totalTimeLoggedIn'])
			$max_time_online = $row_members['totalTimeLoggedIn'];
	}
	foreach ($context['top_time_online'] as $i => $member)
		$context['top_time_online'][$i]['time_percent'] = round(($member['seconds_online'] * 100) / $max_time_online);

	if (!empty($_REQUEST['expand']))
	{
		$month = (int) substr($_REQUEST['expand'], 4);
		$year = (int) substr($_REQUEST['expand'], 0, 4);
		if ($year > 1900 && $year < 2200 && $month >= 1 && $month <= 12)
			$_SESSION['expanded_stats'][$year][] = $month;
	}
	elseif (!empty($_REQUEST['collapse']))
	{
		$month = (int) substr($_REQUEST['collapse'], 4);
		$year = (int) substr($_REQUEST['collapse'], 0, 4);
		if (!empty($_SESSION['expanded_stats'][$year]))
			$_SESSION['expanded_stats'][$year] = array_diff($_SESSION['expanded_stats'][$year], array($month));
	}

	// Activity by month.
	$months_result = db_query("
		SELECT
			YEAR(date) AS stats_year, MONTH(date) AS stats_month, SUM(hits) AS hits, SUM(registers) AS registers, SUM(topics) AS topics, SUM(posts) AS posts,
			MAX(mostOn) AS mostOn
		FROM {$db_prefix}log_activity
		GROUP BY stats_year, stats_month", __FILE__, __LINE__);
	$context['monthly'] = array();
	while ($row_months = mysql_fetch_assoc($months_result))
	{
		$ID_MONTH = $row_months['stats_year'] . str_pad($row_months['stats_month'], 2, '0', STR_PAD_LEFT);
		$expanded = !empty($_SESSION['expanded_stats'][$row_months['stats_year']]) && in_array($row_months['stats_month'], $_SESSION['expanded_stats'][$row_months['stats_year']]);

		$context['monthly'][$ID_MONTH] = array(
			'date' => array(
				'month' => str_pad($row_months['stats_month'], 2, '0', STR_PAD_LEFT),
				'year' => $row_months['stats_year']
			),
			'href' => $scripturl . '?action=stats;' . ($expanded ? 'collapse' : 'expand') . '=' . $ID_MONTH . '#' . $ID_MONTH,
			'link' => '<a href="' . $scripturl . '?action=stats;' . ($expanded ? 'collapse' : 'expand') . '=' . $ID_MONTH . '#' . $ID_MONTH . '">' . $months[$row_months['stats_month']] . ' ' . $row_months['stats_year'] . '</a>',
			'month' => $months[$row_months['stats_month']],
			'new_topics' => $row_months['topics'],
			'new_posts' => $row_months['posts'],
			'new_members' => $row_months['registers'],
			'most_members_online' => $row_months['mostOn'],
			'hits' => $row_months['hits'],
			'days' => array(),
			'expanded' => $expanded
		);
	}

	// This gets rid of the filesort on the query ;).
	krsort($context['monthly']);

	if (empty($_SESSION['expanded_stats']))
		return;

	$condition = array();
	foreach ($_SESSION['expanded_stats'] as $year => $months)
		if (!empty($months))
			$condition[] = "YEAR(date) = $year AND MONTH(date) IN (" . implode(', ', $months) . ')';

	if (empty($condition))
		return;

	// Activity by day.
	$days_result = db_query("
		SELECT YEAR(date) AS stats_year, MONTH(date) AS stats_month, DAYOFMONTH(date) AS stats_day, topics, posts, registers, mostOn, hits
		FROM {$db_prefix}log_activity
		WHERE " . implode('
			OR ', $condition) ."
		ORDER BY stats_day ASC", __FILE__, __LINE__);
	while ($row_days = mysql_fetch_assoc($days_result))
		$context['monthly'][$row_days['stats_year'] . str_pad($row_days['stats_month'], 2, '0', STR_PAD_LEFT)]['days'][] = array(
			'day' => str_pad($row_days['stats_day'], 2, '0', STR_PAD_LEFT),
			'month' => str_pad($row_days['stats_month'], 2, '0', STR_PAD_LEFT),
			'year' => $row_days['stats_year'],
			'new_topics' => $row_days['topics'],
			'new_posts' => $row_days['posts'],
			'new_members' => $row_days['registers'],
			'most_members_online' => $row_days['mostOn'],
			'hits' => $row_days['hits']
		);
}

?>