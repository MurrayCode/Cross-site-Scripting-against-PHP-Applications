<?php
/******************************************************************************
* BoardIndex.php                                                              *
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

/*	The single function this file contains is used to display the main
	board index.  It uses just the following functions:

	void BoardIndex()
		- shows the board index.
		- uses the BoardIndex template, and main sub template.
		- may use the boardindex subtemplate for wireless support.
		- is accessed by ?action=boardindex.

	bool calendarDoIndex()
		- prepares the calendar data for the board index.
		- takes care of caching it for speed.
		- depends upon these settings: cal_showeventsonindex,
		  cal_showbdaysonindex, cal_showholidaysonindex.
		- returns whether there is anything to display.
*/

// Show the board index!
function BoardIndex()
{
	global $txt, $scripturl, $db_prefix, $ID_MEMBER, $user_info, $sourcedir;
	global $modSettings, $context, $settings;

	// For wireless, we use the Wireless template...
	if (WIRELESS)
		$context['sub_template'] = WIRELESS_PROTOCOL . '_boardindex';
	else
		loadTemplate('BoardIndex');

	// Remember the most recent topic for optimizing the recent posts feature.
	$most_recent_topic = array(
		'timestamp' => 0,
		'ref' => null
	);

	// Find all boards and categories, as well as related information.
	$result_boards = db_query("
		SELECT
			c.name AS catName, c.ID_CAT, b.ID_BOARD, b.name AS boardName, b.description,
			b.numPosts, b.numTopics, b.ID_PARENT,
			IFNULL(mem.memberName, m.posterName) AS posterName, m.posterTime, m.subject, m.ID_TOPIC,
			IFNULL(mem.realName, m.posterName) AS realName," . (!$user_info['is_guest'] ? "
			(IFNULL(lb.logTime, 0) >= b.lastUpdated) AS isRead, c.canCollapse,
			IFNULL(cc.ID_MEMBER, 0) AS isCollapsed" : ' 1 AS isRead') . ",
			IFNULL(mem.ID_MEMBER, 0) AS ID_MEMBER, m.ID_MSG,
			IFNULL(mem2.ID_MEMBER, 0) AS ID_MODERATOR, mem2.realName AS modRealName
		FROM {$db_prefix}categories AS c, {$db_prefix}boards AS b
			LEFT JOIN {$db_prefix}messages AS m ON (m.ID_MSG = b.ID_LAST_MSG)
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = m.ID_MEMBER)" . (!$user_info['is_guest'] ? "
			LEFT JOIN {$db_prefix}log_boards AS lb ON (lb.ID_BOARD = b.ID_BOARD AND lb.ID_MEMBER = $ID_MEMBER)
			LEFT JOIN {$db_prefix}collapsed_categories AS cc ON (cc.ID_CAT = c.ID_CAT AND cc.ID_MEMBER = $ID_MEMBER)" : '') . "
			LEFT JOIN {$db_prefix}moderators AS mods ON (mods.ID_BOARD = b.ID_BOARD)
			LEFT JOIN {$db_prefix}members AS mem2 ON (mem2.ID_MEMBER = mods.ID_MEMBER)
		WHERE $user_info[query_see_board]
			AND b.ID_CAT = c.ID_CAT
			AND b.childLevel <= 1
		ORDER BY c.catOrder, b.childLevel, b.boardOrder", __FILE__, __LINE__);

	// Run through the categories and boards....
	$context['categories'] = array();
	while ($row_board = mysql_fetch_assoc($result_boards))
	{
		// Haven't set this category yet.
		if (empty($context['categories'][$row_board['ID_CAT']]))
		{
			$context['categories'][$row_board['ID_CAT']] = array(
				'id' => $row_board['ID_CAT'],
				'name' => $row_board['catName'],
				'is_collapsed' => isset($row_board['canCollapse']) && $row_board['canCollapse'] == 1 && $row_board['isCollapsed'] > 0,
				'can_collapse' => isset($row_board['canCollapse']) && $row_board['canCollapse'] == 1,
				'collapse_href' => isset($row_board['canCollapse']) ? $scripturl . '?action=collapse;c=' . $row_board['ID_CAT'] . ';sa=' . ($row_board['isCollapsed'] > 0 ? 'expand' : 'collapse;') . '#' . $row_board['ID_CAT'] : '',
				'collapse_image' => isset($row_board['canCollapse']) ? '<img src="' . $settings['images_url'] . '/' . ($row_board['isCollapsed'] > 0 ? 'expand.gif" alt="+"' : 'collapse.gif" alt="-"') . ' border="0" />' : '',
				'href' => $scripturl . '#' . $row_board['ID_CAT'],
				'boards' => array(),
				'new' => false
			);
			$context['categories'][$row_board['ID_CAT']]['link'] = '<a name="' . $row_board['ID_CAT'] . '" href="' . (isset($row_board['canCollapse']) ? $context['categories'][$row_board['ID_CAT']]['collapse_href'] : $context['categories'][$row_board['ID_CAT']]['href']) . '">' . $row_board['catName'] . '</a>';
		}

		// Does this category have new posts in it?
		$context['categories'][$row_board['ID_CAT']]['new'] |= empty($row_board['isRead']) && $row_board['posterName'] != '';

		// Collapsed category - don't do any of this.
		if ($context['categories'][$row_board['ID_CAT']]['is_collapsed'])
			continue;

		// Let's save some typing.  Climbing the array might be slower, anyhow.
		$this_category = &$context['categories'][$row_board['ID_CAT']]['boards'];

		// This is a parent board.
		if (empty($row_board['ID_PARENT']))
		{
			// Is this a new board, or just another moderator?
			if (!isset($this_category[$row_board['ID_BOARD']]))
			{
				// Not a child.
				$isChild = false;

				$this_category[$row_board['ID_BOARD']] = array(
					'new' => empty($row_board['isRead']),
					'id' => $row_board['ID_BOARD'],
					'name' => $row_board['boardName'],
					'description' => $row_board['description'],
					'moderators' => array(),
					'link_moderators' => array(),
					'children' => array(),
					'link_children' => array(),
					'children_new' => false,
					'topics' => $row_board['numTopics'],
					'posts' => $row_board['numPosts'],
					'href' => $scripturl . '?board=' . $row_board['ID_BOARD'] . '.0',
					'link' => '<a href="' . $scripturl . '?board=' . $row_board['ID_BOARD'] . '.0">' . $row_board['boardName'] . '</a>'
				);
			}
			if (!empty($row_board['ID_MODERATOR']))
			{
				$this_category[$row_board['ID_BOARD']]['moderators'][$row_board['ID_MODERATOR']] = array(
					'id' => $row_board['ID_MODERATOR'],
					'name' => $row_board['modRealName'],
					'href' => $scripturl . '?action=profile;u=' . $row_board['ID_MODERATOR'],
					'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row_board['ID_MODERATOR'] . '" title="' . $txt[62] . '">' . $row_board['modRealName'] . '</a>'
				);
				$this_category[$row_board['ID_BOARD']]['link_moderators'][] = '<a href="' . $scripturl . '?action=profile;u=' . $row_board['ID_MODERATOR'] . '" title="' . $txt[62] . '">' . $row_board['modRealName'] . '</a>';
			}
		}
		// Found a child board.... make sure we've found its parent and the child hasn't been set already.
		elseif (isset($this_category[$row_board['ID_PARENT']]['children']) && !isset($this_category[$row_board['ID_PARENT']]['children'][$row_board['ID_BOARD']]))
		{
			// A valid child!
			$isChild = true;

			$this_category[$row_board['ID_PARENT']]['children'][$row_board['ID_BOARD']] = array(
				'id' => $row_board['ID_BOARD'],
				'name' => $row_board['boardName'],
				'description' => $row_board['description'],
				'new' => empty($row_board['isRead']) && $row_board['posterName'] != '',
				'topics' => $row_board['numTopics'],
				'posts' => $row_board['numPosts'],
				'href' => $scripturl . '?board=' . $row_board['ID_BOARD'] . '.0',
				'link' => '<a href="' . $scripturl . '?board=' . $row_board['ID_BOARD'] . '.0">' . $row_board['boardName'] . '</a>'
			);

			// Does this board contain new boards?
			$this_category[$row_board['ID_PARENT']]['children_new'] |= empty($row_board['isRead']);

			// This is easier to use in many cases for the theme....
			$this_category[$row_board['ID_PARENT']]['link_children'][] = &$this_category[$row_board['ID_PARENT']]['children'][$row_board['ID_BOARD']]['link'];
		}
		// Found a child of a child - skip.
		else
			continue;

		// Prepare the subject, and make sure it's not too long.
		censorText($row_board['subject']);
		$row_board['short_subject'] = strlen(un_htmlspecialchars($row_board['subject'])) > 24 ? strtr(substr(strtr($row_board['subject'], array('&lt;' => '<', '&gt;' => '>', '&quot;' => '"')), 0, 24) . '...', array('<' => '&lt;', '>' => '&gt;', '"' => '&quot;', '&...' => '...', '&#...' => '...')) : $row_board['subject'];
		$this_last_post = array(
			'id' => $row_board['ID_MSG'],
			'time' => $row_board['posterTime'] > 0 ? timeformat($row_board['posterTime']) : $txt[470],
			'timestamp' => $row_board['posterTime'],
			'subject' => $row_board['short_subject'],
			'member' => array(
				'id' => $row_board['ID_MEMBER'],
				'username' => $row_board['posterName'] != '' ? $row_board['posterName'] : $txt[470],
				'name' => $row_board['realName'],
				'href' => $row_board['posterName'] != '' && !empty($row_board['ID_MEMBER']) ? $scripturl . '?action=profile;u=' . $row_board['ID_MEMBER'] : '',
				'link' => $row_board['posterName'] != '' ? (!empty($row_board['ID_MEMBER']) ? '<a href="' . $scripturl . '?action=profile;u=' . $row_board['ID_MEMBER'] . '">' . $row_board['realName'] . '</a>' : $row_board['realName']) : $txt[470],
			),
			'start' => 'new',
			'topic' => $row_board['ID_TOPIC']
		);

		// Provide the href and link.
		if ($row_board['subject'] != '')
		{
			$this_last_post['href'] = $scripturl . '?topic=' . $row_board['ID_TOPIC'] . '.new' . (empty($row_board['isRead']) ? ';boardseen' : '') . '#new';
			$this_last_post['link'] = '<a href="' . $this_last_post['href'] . '" title="' . $row_board['subject'] . '">' . $row_board['short_subject'] . '</a>';
		}
		else
		{
			$this_last_post['href'] = '';
			$this_last_post['link'] = $txt[470];
		}

		// Set the last post in the parent board.
		if (empty($row_board['ID_PARENT']) || ($isChild && !empty($row_board['posterTime']) && $this_category[$row_board['ID_PARENT']]['last_post']['timestamp'] < $row_board['posterTime']))
			$this_category[$isChild ? $row_board['ID_PARENT'] : $row_board['ID_BOARD']]['last_post'] = $this_last_post;
		// Just in the child...?
		if ($isChild)
		{
			$this_category[$row_board['ID_PARENT']]['children'][$row_board['ID_BOARD']]['last_post'] = $this_last_post;

			// If there are no posts in this board, it really can't be new...
			$this_category[$row_board['ID_PARENT']]['children'][$row_board['ID_BOARD']]['new'] &= $row_board['posterName'] != '';
		}
		// No last post for this board?  It's not new then, is it..?
		elseif ($row_board['posterName'] == '')
			$this_category[$row_board['ID_BOARD']]['new'] = false;

		// Determine a global most recent topic.
		if ($row_board['posterTime'] > $most_recent_topic['timestamp'])
			$most_recent_topic = array(
				'timestamp' => $row_board['posterTime'],
				'ref' => &$this_category[$isChild ? $row_board['ID_PARENT'] : $row_board['ID_BOARD']]['last_post'],
			);
	}
	mysql_free_result($result_boards);

	// Load the users online right now.
	$result = db_query("
		SELECT
			lo.ID_MEMBER, lo.logTime, mem.realName, mem.memberName, mem.showOnline,
			mg.onlineColor, mg.ID_GROUP, mg.groupName
		FROM {$db_prefix}log_online AS lo
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = lo.ID_MEMBER)
			LEFT JOIN {$db_prefix}membergroups AS mg ON (mg.ID_GROUP = IF(mem.ID_GROUP = 0, mem.ID_POST_GROUP, mem.ID_GROUP))", __FILE__, __LINE__);

	$context['users_online'] = array();
	$context['list_users_online'] = array();
	$context['online_groups'] = array();
	$context['num_guests'] = 0;
	$context['num_users_hidden'] = 0;
	while ($row = mysql_fetch_assoc($result))
	{
		if (!isset($row['realName']))
		{
			$context['num_guests']++;
			continue;
		}
		elseif (!empty($row['showOnline']) || allowedTo('moderate_forum'))
		{
			// Some basic color coding...
			if (!empty($row['onlineColor']))
				$link = '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '" style="color: ' . $row['onlineColor'] . ';">' . $row['realName'] . '</a>';
			else
				$link = '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['realName'] . '</a>';

			$context['users_online'][$row['logTime'] . $row['memberName']] = array(
				'id' => $row['ID_MEMBER'],
				'username' => $row['memberName'],
				'name' => $row['realName'],
				'group' => $row['ID_GROUP'],
				'href' => $scripturl . '?action=profile;u=' . $row['ID_MEMBER'],
				'link' => $link,
				'hidden' => empty($row['showOnline']),
			);

			$context['list_users_online'][$row['logTime'] . $row['memberName']] = empty($row['showOnline']) ? '<i>' . $link . '</i>' : $link;

			if (!isset($context['online_groups'][$row['ID_GROUP']]))
				$context['online_groups'][$row['ID_GROUP']] = array(
					'id' => $row['ID_GROUP'],
					'name' => $row['groupName'],
					'color' => $row['onlineColor']
				);
		}
		else
			$context['num_users_hidden']++;
	}
	mysql_free_result($result);

	krsort($context['users_online']);
	krsort($context['list_users_online']);
	ksort($context['online_groups']);

	$context['num_users_online'] = count($context['users_online']) + $context['num_users_hidden'];

	// Track most online statistics?
	if (!empty($modSettings['trackStats']))
	{
		// Determine the most users online - both all time and per day.
		$total_users = $context['num_guests'] + count($context['users_online']) + $context['num_users_hidden'];
		if (!isset($modSettings['mostOnline']) || $total_users >= $modSettings['mostOnline'])
			updateSettings(array('mostOnline' => $total_users, 'mostDate' => time()));

		$date = strftime('%Y%m%d', forum_time(false));

		// One or more stats are not up-to-date?
		if (!isset($modSettings['mostOnlineUpdated']) || $modSettings['mostOnlineUpdated'] != $date)
		{
			$request = db_query("
				SELECT mostOn
				FROM {$db_prefix}log_activity
				WHERE date = $date", __FILE__, __LINE__);

			// The log_activity hasn't got an entry for today?
			if (mysql_num_rows($request) == 0)
			{
				db_query("
					INSERT IGNORE INTO {$db_prefix}log_activity
						(date, mostOn)
					VALUES ($date, $total_users)", __FILE__, __LINE__);
				updateSettings(array('mostOnlineUpdated' => $date, 'mostOnlineToday' => $total_users));
			}
			// There's an entry in log_activity on today...
			else
			{
				list ($modSettings['mostOnlineToday']) = mysql_fetch_row($request);

				if ($total_users > $modSettings['mostOnlineToday'])
					trackStats(array('mostOn' => $total_users));

				updateSettings(array('mostOnlineUpdated' => $date, 'mostOnlineToday' => max($total_users, $modSettings['mostOnlineToday'])));
			}
			mysql_free_result($request);
		}
		// Highest number of users online today?
		elseif ($total_users > $modSettings['mostOnlineToday'])
		{
			trackStats(array('mostOn' => $total_users));
			updateSettings(array('mostOnlineUpdated' => $date, 'mostOnlineToday' => $total_users));
		}
	}

	// Set the latest member.
	$context['latest_member'] = array(
		'name' => $modSettings['latestRealName'],
		'id' => $modSettings['latestMember'],
		'href' => $scripturl . '?action=profile;u=' . $modSettings['latestMember'],
		'link' => '<a href="' . $scripturl . '?action=profile;u=' . $modSettings['latestMember'] . '">' . $modSettings['latestRealName'] . '</a>'
	);

	// Load the most recent post?
	if ((!empty($settings['number_recent_posts']) && $settings['number_recent_posts'] == 1) || $settings['show_sp1_info'])
		$context['latest_post'] = $most_recent_topic['ref'];

	if (!empty($settings['number_recent_posts']) && $settings['number_recent_posts'] > 1)
	{
		require_once($sourcedir . '/Recent.php');
		$context['latest_posts'] = getLastPosts($settings['number_recent_posts']);
	}

	$settings['display_recent_bar'] = !empty($settings['number_recent_posts']) ? $settings['number_recent_posts'] : 0;
	$settings['show_member_bar'] &= allowedTo('view_mlist');
	$context['show_stats'] = allowedTo('view_stats') && !empty($modSettings['trackStats']);
	$context['show_member_list'] = allowedTo('view_mlist');
	$context['show_who'] = allowedTo('who_view') && !empty($modSettings['who_enabled']);

	// Set some permission related settings.
	$context['show_login_bar'] = $user_info['is_guest'] && empty($modSettings['enableVBStyleLogin']);
	$context['show_calendar'] = allowedTo('calendar_view') && !empty($modSettings['cal_enabled']);

	// Load the calendar?
	if ($context['show_calendar'])
		$context['show_calendar'] = calendarDoIndex();

	$context['page_title'] = $txt[18];
}

// Called from the BoardIndex to display the current day's events on the board index.
function calendarDoIndex()
{
	global $modSettings, $context, $user_info, $scripturl, $sc, $ID_MEMBER;

	// Make sure at least one of the options is checked.
	if (empty($modSettings['cal_showeventsonindex']) && empty($modSettings['cal_showbdaysonindex']) && empty($modSettings['cal_showholidaysonindex']))
		return false;

	// Get the current forum time and check whether the statistics are up to date.
	if (empty($modSettings['cal_today_updated']) || $modSettings['cal_today_updated'] != strftime('%Y%m%d', forum_time(false)))
		updateStats('calendar');

	// Get the current member time/date.
	$day = (int) strftime('%d', forum_time());

	// Load the holidays for today...
	if (!empty($modSettings['cal_showholidaysonindex']) && isset($modSettings['cal_today_holiday']))
		$holidays = unserialize($modSettings['cal_today_holiday']);
	// ... the birthdays for today...
	if (!empty($modSettings['cal_showbdaysonindex']) && isset($modSettings['cal_today_birthday']))
		$bday = unserialize($modSettings['cal_today_birthday']);
	// ... and the events for today.
	if (!empty($modSettings['cal_showeventsonindex']) && isset($modSettings['cal_today_event']))
		$events = unserialize($modSettings['cal_today_event']);

	// No events, birthdays, or holidays... don't show anything.
	if (empty($holidays) && empty($bday) && empty($events))
		return false;

	// This shouldn't be less than one!
	if (empty($modSettings['cal_days_for_index']) || $modSettings['cal_days_for_index'] < 1)
		$modSettings['cal_days_for_index'] = 1;

	$context['calendar_only_today'] = $modSettings['cal_days_for_index'] == 1;

	// Get the last day of the month.
	$nLastDay = (int) strftime('%d', mktime(0, 0, 0, strftime('%m') == 12 ? 1 : strftime('%m') + 1, 0, strftime('%m') == 12 ? strftime('%Y') + 1 : strftime('%Y')));

	// This is used to show the "how-do-I-edit" help.
	$context['calendar_can_edit'] = allowedTo('calendar_edit_any');

	// Holidays between now and now + days.
	$context['calendar_holidays'] = array();
	for ($i = $day; $i < $day + $modSettings['cal_days_for_index']; $i++)
	{
		if (isset($holidays[$i % $nLastDay == 0 ? $i : $i % $nLastDay]))
			$context['calendar_holidays'] = array_merge($context['calendar_holidays'], $holidays[$i % $nLastDay == 0 ? $i : $i % $nLastDay]);
	}

	// Happy Birthday, guys and gals!
	$context['calendar_birthdays'] = array();
	for ($i = $day; $i < $day + $modSettings['cal_days_for_index']; $i++)
	{
		if (isset($bday[$i % $nLastDay == 0 ? $i : $i % $nLastDay]))
		{
			foreach ($bday[$i % $nLastDay == 0 ? $i : $i % $nLastDay] as $index => $dummy)
				$bday[$i % $nLastDay == 0 ? $i : $i % $nLastDay][$index]['is_today'] = ($i % $nLastDay == 0 ? $i : $i % $nLastDay) == $day;
			$context['calendar_birthdays'] = array_merge($context['calendar_birthdays'], $bday[$i % $nLastDay == 0 ? $i : $i % $nLastDay]);
		}
	}

	$context['calendar_events'] = array();
	$duplicates = array();
	for ($i = $day; $i < $day + $modSettings['cal_days_for_index']; $i++)
	{
		if (isset($events[$i % $nLastDay == 0 ? $i : $i % $nLastDay]))
			foreach ($events[$i % $nLastDay == 0 ? $i : $i % $nLastDay] as $ev => $event)
			{
				if ((count(array_intersect($user_info['groups'], $event['allowed_groups'])) != 0 || allowedTo('admin_forum')))
				{
					if (isset($duplicates[$events[$i % $nLastDay == 0 ? $i : $i % $nLastDay][$ev]['topic'] . $events[$i % $nLastDay == 0 ? $i : $i % $nLastDay][$ev]['title']]))
					{
						unset($events[$i % $nLastDay == 0 ? $i : $i % $nLastDay][$ev]);
						continue;
					}

					$this_event = &$events[$i % $nLastDay == 0 ? $i : $i % $nLastDay][$ev];
					$this_event['href'] = $scripturl . '?topic=' . $this_event['topic'] . '.0';
					$this_event['modify_href'] = $scripturl . '?action=post;msg=' . $this_event['msg'] . ';topic=' . $this_event['topic'] . '.0;calendar;eventid=' . $this_event['id'] . ';sesc=' . $sc;
					$this_event['can_edit'] = allowedTo('calendar_edit_any') || ($this_event['poster'] == $ID_MEMBER && allowedTo('calendar_edit_own'));
					$this_event['is_today'] = ($i % $nLastDay == 0 ? $i : $i % $nLastDay) == $day;

					$duplicates[$this_event['topic'] . $this_event['title']] = true;
				}
				else
					unset($events[$i % $nLastDay == 0 ? $i : $i % $nLastDay][$ev]);
			}

		if (isset($events[$i % $nLastDay == 0 ? $i : $i % $nLastDay]))
			$context['calendar_events'] = array_merge($context['calendar_events'], $events[$i % $nLastDay == 0 ? $i : $i % $nLastDay]);
	}

	for ($i = 0, $n = count($context['calendar_birthdays']); $i < $n; $i++)
		$context['calendar_birthdays'][$i]['is_last'] = !isset($context['calendar_birthdays'][$i + 1]);
	for ($i = 0, $n = count($context['calendar_events']); $i < $n; $i++)
		$context['calendar_events'][$i]['is_last'] = !isset($context['calendar_events'][$i + 1]);

	// This is used to make sure the header should be displayed.
	return !empty($context['calendar_holidays']) || !empty($context['calendar_birthdays']) || !empty($context['calendar_events']);
}

?>