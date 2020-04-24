<?php
/******************************************************************************
* Load.php                                                                    *
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

/*	This file has the hefty job of loading information for the forum.
*/

// Load the $modSettings array.
function reloadSettings()
{
	global $modSettings, $db_prefix, $txt;

	// Clear it out first.
	$modSettings = array();

	$request = db_query("
		SELECT variable, value
		FROM {$db_prefix}settings", __FILE__, __LINE__);
	while ($row = mysql_fetch_row($request))
		$modSettings[$row[0]] = $row[1];
	mysql_free_result($request);

	// Is it time again to optimize the database?
	if (empty($modSettings['autoOptDatabase']) || $modSettings['autoOptLastOpt'] + $modSettings['autoOptDatabase'] * 3600 * 24 >= time())
		return;

	if (!empty($modSettings['autoOptMaxOnline']))
	{
		$request = db_query("
			SELECT COUNT(session)
			FROM {$db_prefix}log_online", __FILE__, __LINE__);
		list ($dont_do_it) = mysql_fetch_row($request);
		mysql_free_result($request);

		if ($dont_do_it > $modSettings['autoOptMaxOnline'])
			return;
	}

	$request = db_query("
		SHOW TABLES LIKE '" . str_replace('_', '\_', $db_prefix) . "%'", __FILE__, __LINE__);
	$tables = array();
	while ($row = mysql_fetch_row($request))
		$tables[] = $row[0];
	mysql_free_result($request);

	updateSettings(array('autoOptLastOpt' => time()));

	db_query("
		OPTIMIZE TABLE `" . implode('`, `', $tables) . '`', __FILE__, __LINE__);
}

// Load all the important user information...
function loadUserSettings()
{
	global $modSettings, $user_settings;
	global $ID_MEMBER, $db_prefix, $cookiename, $user_info, $language;

	// Check first the cookie, then the session.
	if (isset($_COOKIE[$cookiename]))
	{
		$_COOKIE[$cookiename] = stripslashes($_COOKIE[$cookiename]);

		// Fix a security hole in PHP 4.3.9 and below...
		if (preg_match('~^a:3:\{i:0;(i:\d{1,6}|s:[1-6]:"\d{1,6}");i:1;s:(0|32):"([a-fA-F0-9]{32})?";i:2;i:\d{1,12};\}$~', $_COOKIE[$cookiename]) == 1)
			list ($ID_MEMBER, $password) = @unserialize($_COOKIE[$cookiename]);
		$ID_MEMBER = !empty($ID_MEMBER) ? (int) $ID_MEMBER : 0;
	}
	elseif (isset($_SESSION['login_' . $cookiename]) && ($_SESSION['USER_AGENT'] == $_SERVER['HTTP_USER_AGENT'] || !empty($modSettings['disableCheckUA'])))
	{
		list ($ID_MEMBER, $password, $login_span) = @unserialize(stripslashes($_SESSION['login_' . $cookiename]));
		$ID_MEMBER = !empty($ID_MEMBER) && $login_span > time() ? (int) $ID_MEMBER : 0;
	}
	else
		$ID_MEMBER = 0;

	// Only load this stuff if the user isn't a guest.
	if ($ID_MEMBER != 0)
	{
		$request = db_query("
			SELECT mem.*, IFNULL(a.ID_ATTACH, 0) AS ID_ATTACH
			FROM {$db_prefix}members AS mem
				LEFT JOIN {$db_prefix}attachments AS a ON (a.ID_MEMBER = $ID_MEMBER)
			WHERE mem.ID_MEMBER = $ID_MEMBER
			LIMIT 1", __FILE__, __LINE__);
		// Did we find 'im?  If not, junk it.
		if (mysql_num_rows($request) != 0)
		{
			// The base settings array.
			$user_settings = mysql_fetch_assoc($request);

			// Wrong password or not activated - either way, you're going nowhere.
			$ID_MEMBER = md5_hmac($user_settings['passwd'], 'ys') != $password || empty($user_settings['is_activated']) ? 0 : $user_settings['ID_MEMBER'];
		}
		else
			$ID_MEMBER = 0;
		mysql_free_result($request);
	}

	// Found 'im, let's set up the variables.
	if ($ID_MEMBER != 0)
	{
		if (empty($_SESSION['ID_MSG_LAST_VISIT']))
		{
			$_SESSION['ID_MSG_LAST_VISIT'] = $user_settings['ID_MSG_LAST_VISIT'];
			unset($user_settings['ID_MSG_LAST_VISIT']);

			updateMemberData($ID_MEMBER, array('ID_MSG_LAST_VISIT' => (int) $modSettings['maxMsgID'], 'lastLogin' => time(), 'memberIP' => '\'' . $_SERVER['REMOTE_ADDR'] . '\''));

			$user_settings['lastLogin'] = time();
		}

		$username = $user_settings['memberName'];

		if (empty($user_settings['additionalGroups']))
			$user_info = array(
				'groups' => array($user_settings['ID_GROUP'], $user_settings['ID_POST_GROUP'])
			);
		else
			$user_info = array(
				'groups' => array_merge(
					array($user_settings['ID_GROUP'], $user_settings['ID_POST_GROUP']),
					explode(',', $user_settings['additionalGroups'])
				)
			);
	}
	// If the user is a guest, initialize all the critial user settings.
	else
	{
		// This is what a guest's variables should be.
		$username = '';
		$user_info = array('groups' => array(-1));
		$user_settings = array();

		if (isset($_COOKIE[$cookiename]))
			$_COOKIE[$cookiename] = '';
	}

	// Set up the $user_info array.
	$user_info += array(
		'username' => $username,
		'name' => isset($user_settings['realName']) ? $user_settings['realName'] : '',
		'email' => isset($user_settings['emailAddress']) ? $user_settings['emailAddress'] : '',
		'passwd' => isset($user_settings['passwd']) ? $user_settings['passwd'] : '',
		'language' => empty($user_settings['lngfile']) || empty($modSettings['userLanguage']) ? $language : $user_settings['lngfile'],
		'is_guest' => $ID_MEMBER == 0,
		'is_admin' => in_array(1, $user_info['groups']),
		'theme' => empty($user_settings['ID_THEME']) ? 0 : $user_settings['ID_THEME'],
		'last_login' => empty($user_settings['lastLogin']) ? 0 : $user_settings['lastLogin'],
		'ip' => $_SERVER['REMOTE_ADDR'],
		'posts' => empty($user_settings['posts']) ? 0 : $user_settings['posts'],
		'time_format' => empty($user_settings['timeFormat']) ? $modSettings['time_format'] : $user_settings['timeFormat'],
		'time_offset' => empty($user_settings['timeOffset']) ? 0 : $user_settings['timeOffset'],
		'avatar' => array(
			'url' => isset($user_settings['avatar']) ? $user_settings['avatar'] : '',
			'ID_ATTACH' => isset($user_settings['ID_ATTACH']) ? $user_settings['ID_ATTACH'] : 0
		),
		'smiley_set' => isset($user_settings['smileySet']) ? $user_settings['smileySet'] : '',
		'messages' => empty($user_settings['instantMessages']) ? 0 : $user_settings['instantMessages'],
		'unread_messages' => empty($user_settings['unreadMessages']) ? 0 : $user_settings['unreadMessages'],
		'total_time_logged_in' => empty($user_settings['totalTimeLoggedIn']) ? 0 : $user_settings['totalTimeLoggedIn'],
		'permissions' => array()
	);
	$user_info['groups'] = array_unique($user_info['groups']);

	if (!empty($modSettings['userLanguage']) && !empty($_REQUEST['language']))
	{
		$user_info['language'] = strtr($_REQUEST['language'], './\\:', '____');
		$_SESSION['language'] = $user_info['language'];
	}
	elseif (!empty($modSettings['userLanguage']) && !empty($_SESSION['language']))
		$user_info['language'] = strtr($_SESSION['language'], './\\:', '____');

	// Just build this here, it makes it easier to change/use.
	if ($user_info['is_guest'])
		$user_info['query_see_board'] = 'FIND_IN_SET(-1, b.memberGroups)';
	// Administrators can see all boards.
	elseif ($user_info['is_admin'])
		$user_info['query_see_board'] = '1';
	// Registered user.... just the groups in $user_info['groups'].
	else
		$user_info['query_see_board'] = '(FIND_IN_SET(' . implode(', b.memberGroups) OR FIND_IN_SET(', $user_info['groups']) . ', b.memberGroups))';
}

// MD5 Encryption used for passwords.
function md5_hmac($data, $key)
{
	$key = str_pad(strlen($key) <= 64 ? $key : pack('H*', md5($key)), 64, chr(0x00));
	return md5(($key ^ str_repeat(chr(0x5c), 64)) . pack('H*', md5(($key ^ str_repeat(chr(0x36), 64)). $data)));
}

// Check for moderators and see if they have access to the board.
function loadBoard()
{
	global $txt, $db_prefix, $scripturl, $context;
	global $board_info, $board, $topic, $ID_MEMBER, $user_info;

	// Assume they are not a moderator.
	$user_info['is_mod'] = false;
	$context['user']['is_mod'] = &$user_info['is_mod'];

	// Start the linktree off empty..
	$context['linktree'] = array();

	// Load this board only if the it is specified.
	if (empty($board) && empty($topic))
	{
		$board_info = array('moderators' => array());
		return;
	}

	$request = db_query("
		SELECT
			c.ID_CAT, b.name AS bname, b.description, b.numTopics, b.memberGroups,
			b.ID_PARENT, c.name AS cname, IFNULL(mem.ID_MEMBER, 0) AS ID_MODERATOR,
			mem.realName" . (!empty($topic) ? ", b.ID_BOARD" : '') . ", b.childLevel,
			b.ID_THEME, b.override_theme, b.use_local_permissions
		FROM {$db_prefix}boards AS b" . (!empty($topic) ? ", {$db_prefix}topics AS t" : '') . "
			LEFT JOIN {$db_prefix}categories AS c ON (c.ID_CAT = b.ID_CAT)
			LEFT JOIN {$db_prefix}moderators AS mods ON (mods.ID_BOARD = " . (empty($topic) ? $board : 't.ID_BOARD') . ")
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = mods.ID_MEMBER)
		WHERE b.ID_BOARD = " . (empty($topic) ? $board : "t.ID_BOARD
			AND t.ID_TOPIC = $topic"), __FILE__, __LINE__);
	// If there aren't any, skip.
	if (mysql_num_rows($request) > 0)
	{
		$row = mysql_fetch_assoc($request);

		// Set the current board.
		if (!empty($row['ID_BOARD']))
			$board = $row['ID_BOARD'];

		// Basic operating information. (globals... :/)
		$board_info = array(
			'moderators' => array(),
			'cat' => array(
				'id' => $row['ID_CAT'],
				'name' => $row['cname']
			),
			'name' => $row['bname'],
			'description' => $row['description'],
			'num_topics' => $row['numTopics'],
			'parent_boards' => getBoardParents($row['ID_PARENT']),
			'parent' => $row['ID_PARENT'],
			'child_level' => $row['childLevel'],
			'theme' => $row['ID_THEME'],
			'override_theme' => !empty($row['override_theme']),
			'use_local_permissions' => $row['use_local_permissions'] == 1
		);

		// Load the membergroups allowed, and check permissions.
		$board_info['groups'] = $row['memberGroups'] == '' ? array() : explode(',', $row['memberGroups']);
		if (count(array_intersect($user_info['groups'], $board_info['groups'])) == 0 && !$user_info['is_admin'])
			$board_info['error'] = 'access';

		do
		{
			if (!empty($row['ID_MODERATOR']))
				$board_info['moderators'][$row['ID_MODERATOR']] = array(
					'id' => $row['ID_MODERATOR'],
					'name' => $row['realName'],
					'href' => $scripturl . '?action=profile;u=' . $row['ID_MODERATOR'],
					'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MODERATOR'] . '" title="' . $txt[62] . '">' . $row['realName'] . '</a>'
				);
		}
		while ($row = mysql_fetch_assoc($request));

		// Now check if the user is a moderator.
		$user_info['is_mod'] = isset($board_info['moderators'][$ID_MEMBER]);

		// Build up the linktree...
		$context['linktree'] = array_merge(
			$context['linktree'],
			array(array(
				'url' => $scripturl . '#' . $board_info['cat']['id'],
				'name' => $board_info['cat']['name']
			)),
			array_reverse($board_info['parent_boards']),
			array(array(
				'url' => $scripturl . '?board=' . $board . '.0',
				'name' => $board_info['name']
			))
		);
	}
	else
	{
		// Otherwise the topic is invalid, there are no moderators, etc.
		$board_info = array(
			'moderators' => array(),
			'error' => 'exist'
		);
		$topic = null;
		$board = 0;
	}
	mysql_free_result($request);

	if (!empty($topic))
		$_GET['board'] = (int) $board;

	// Set the template contextual information.
	$context['user']['is_mod'] = &$user_info['is_mod'];
	$context['current_topic'] = $topic;
	$context['current_board'] = $board;

	// Hacker... you can't see this topic, I'll tell you that. (but moderators can!)
	if (!empty($board_info['error']) && !($board_info['error'] == 'access' && $user_info['is_mod']))
	{
		// The permissions and theme need loading, just to make sure everything goes smoothly.
		loadPermissions();
		loadTheme();
		fatal_lang_error('topic_gone', false);
	}

	if ($user_info['is_mod'])
		$user_info['groups'][] = 3;
}

// Load this user's permissions.
function loadPermissions()
{
	global $user_info, $db_prefix, $board, $board_info;

	$user_info['permissions'] = array();

	if ($user_info['is_admin'])
		return;

	$removals = array();

	// Get the general permissions.
	$request = db_query("
		SELECT permission, addDeny
		FROM {$db_prefix}permissions
		WHERE ID_GROUP IN (" . implode(', ', $user_info['groups']) . ')', __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
	{
		if (empty($row['addDeny']))
			$removals[] = $row['permission'];
		else
			$user_info['permissions'][] = $row['permission'];
	}
	mysql_free_result($request);

	// Get the board permissions.
	if (!empty($board))
	{
		// Make sure the board (if any) has been loaded by loadBoard().
		if (!isset($board_info['use_local_permissions']))
			fatal_lang_error('smf232');

		$request = db_query("
			SELECT permission, addDeny
			FROM {$db_prefix}board_permissions
			WHERE ID_GROUP IN (" . implode(', ', $user_info['groups']) . ")
				AND ID_BOARD = " . ($board_info['use_local_permissions'] ? $board : '0'), __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
		{
			if (empty($row['addDeny']))
				$removals[] = $row['permission'];
			else
				$user_info['permissions'][] = $row['permission'];
		}
		mysql_free_result($request);
	}

	// Remove all the permissions they shouldn't have ;).
	$user_info['permissions'] = array_diff($user_info['permissions'], $removals);

	// Banned?  Watch, don't touch..
	banPermissions();
}

// Loads an array of users' data by ID or memberName.
function loadMemberData($users, $is_name = false, $set = 'normal')
{
	global $user_profile, $db_prefix, $modSettings, $board_info;

	// Can't just look for no users :P.
	if (empty($users))
		return false;

	// Make sure it's an array.
	$users = !is_array($users) ? array($users) : array_unique($users);

	if ($set == 'normal')
	{
		// No need for moderator fixes if there are none or none among the requested users.
		if (empty($board_info['moderators']) || (!$is_name && count(array_intersect($users, array_keys($board_info['moderators']))) == 0))
			$moderator_fix = array('pg' => '', 'mg' => 'mem.ID_GROUP');
		// Overwrite your primary group to moderator, if a user happens to be one.
		else
			$moderator_fix = array(
				'pg' => 'AND mem.ID_MEMBER NOT IN (' . implode(', ', array_keys($board_info['moderators'])) . ')',
				'mg' => 'IF(mem.ID_MEMBER IN (' . implode(', ', array_keys($board_info['moderators'])) . '), 3, mem.ID_GROUP)'
			);

		$select_columns = "
			IFNULL(lo.logTime, 0) AS isOnline, IFNULL(a.ID_ATTACH, 0) AS ID_ATTACH, a.filename, mem.signature,
			mem.personalText, mem.location, mem.gender, mem.avatar, mem.ID_MEMBER, mem.memberName, mem.realName,
			mem.emailAddress, mem.hideEmail, mem.dateRegistered, mem.websiteTitle, mem.websiteUrl, mem.birthdate,
			mem.memberIP, mem.location, mem.ICQ, mem.AIM, mem.YIM, mem.MSN, mem.posts, mem.lastLogin, mem.karmaGood,
			mem.ID_POST_GROUP, mem.karmaBad, mem.lngfile, mem.ID_GROUP, mem.timeOffset, mem.showOnline,
			mg.onlineColor AS member_group_color, IFNULL(mg.groupName, '') AS member_group,
			pg.onlineColor AS post_group_color, IFNULL(pg.groupName, '') AS post_group,
			IF((mem.ID_GROUP = 0 OR mg.stars = '')$moderator_fix[pg], pg.stars, mg.stars) AS stars" . (!empty($modSettings['titlesEnable']) ? ',
			mem.usertitle' : '');
		$select_tables = "
			LEFT JOIN {$db_prefix}log_online AS lo ON (lo.ID_MEMBER = mem.ID_MEMBER)
			LEFT JOIN {$db_prefix}attachments AS a ON (a.ID_MEMBER = mem.ID_MEMBER)
			LEFT JOIN {$db_prefix}membergroups AS pg ON (pg.ID_GROUP = mem.ID_POST_GROUP)
			LEFT JOIN {$db_prefix}membergroups AS mg ON (mg.ID_GROUP = $moderator_fix[mg])";
	}
	elseif ($set == 'profile')
	{
		$select_columns = "
			IFNULL(lo.logTime, 0) AS isOnline, IFNULL(a.ID_ATTACH, 0) AS ID_ATTACH, a.filename, mem.signature,
			mem.personalText, mem.location, mem.gender, mem.avatar, mem.ID_MEMBER, mem.memberName, mem.realName,
			mem.emailAddress, mem.hideEmail, mem.dateRegistered, mem.websiteTitle, mem.websiteUrl, mem.birthdate,
			mem.location, mem.ICQ, mem.AIM, mem.YIM, mem.MSN, mem.posts, mem.lastLogin, mem.karmaGood, mem.karmaBad,
			mem.memberIP, mem.lngfile, mem.ID_GROUP, mem.ID_THEME, mem.im_ignore_list, mem.im_email_notify,
			mem.timeOffset" . (!empty($modSettings['titlesEnable']) ? ', mem.usertitle' : '') . ", mem.timeFormat,
			mem.secretQuestion, mem.is_activated, mem.additionalGroups, mem.smileySet, mem.showOnline,
			mem.totalTimeLoggedIn, mem.ID_POST_GROUP, mem.notifyAnnouncements, mem.notifyOnce,
			mg.onlineColor AS member_group_color, IFNULL(mg.groupName, '') AS member_group,
			pg.onlineColor AS post_group_color, IFNULL(pg.groupName, '') AS post_group,
			IF((mem.ID_GROUP = 0 OR mg.stars = ''), pg.stars, mg.stars) AS stars";
		$select_tables = "
			LEFT JOIN {$db_prefix}log_online AS lo ON (lo.ID_MEMBER = mem.ID_MEMBER)
			LEFT JOIN {$db_prefix}attachments AS a ON (a.ID_MEMBER = mem.ID_MEMBER)
			LEFT JOIN {$db_prefix}membergroups AS pg ON (pg.ID_GROUP = mem.ID_POST_GROUP)
			LEFT JOIN {$db_prefix}membergroups AS mg ON (mg.ID_GROUP = mem.ID_GROUP)";
	}
	elseif ($set == 'minimal')
	{
		$select_columns = '
			mem.ID_MEMBER, mem.memberName, mem.realName, mem.emailAddress, mem.hideEmail, mem.dateRegistered,
			mem.posts, mem.lastLogin, mem.memberIP, mem.lngfile, mem.ID_GROUP';
		$select_tables = '';
	}

	// Load the data.
	$request = db_query("
		SELECT$select_columns
		FROM {$db_prefix}members AS mem$select_tables
		WHERE mem." . ($is_name ? 'memberName' : 'ID_MEMBER') . (count($users) == 1 ? " = '" . current($users) . "'" : " IN ('" . implode("', '", $users) . "')"), __FILE__, __LINE__);
	$loaded_ids = array();
	while ($row = mysql_fetch_assoc($request))
	{
		$loaded_ids[] = $row['ID_MEMBER'];
		$row['options'] = array();
		$user_profile[$row['ID_MEMBER']] = $row;
	}
	mysql_free_result($request);

	if (!empty($loaded_ids) && $set != 'minimal')
	{
		$request = db_query("
			SELECT *
			FROM {$db_prefix}themes
			WHERE ID_MEMBER" . (count($loaded_ids) == 1 ? ' = ' . $loaded_ids[0] : ' IN (' . implode(', ', $loaded_ids) . ')'), __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
			$user_profile[$row['ID_MEMBER']]['options'][$row['variable']] = $row['value'];
		mysql_free_result($request);
	}

	return empty($loaded_ids) ? false : $loaded_ids;
}

// Loads the user's basic values... meant for template/theme usage.
function loadMemberContext($user)
{
	global $themeUser, $user_profile, $txt, $scripturl, $user_info;
	global $context, $modSettings, $ID_MEMBER;
	global $board_info, $settings, $db_prefix;
	static $dataLoaded = array();

	// If this person's data is already loaded, skip it.
	if (isset($dataLoaded[$user]))
		return true;

	// We can't load guests or members not loaded by loadMemberData()!
	if ($user == 0 || !isset($user_profile[$user]))
		return false;

	// Well, it's loaded now anyhow.
	$dataLoaded[$user] = true;
	$profile = $user_profile[$user];

	// Censor everything.
	censorText($profile['signature']);
	censorText($profile['personalText']);
	censorText($profile['location']);

	// Set things up to be used before hand.
	$gendertxt = $profile['gender'] == 2 ? $txt[239] : ($profile['gender'] == 1 ? $txt[238] : '');
	$profile['signature'] = str_replace(array("\n", "\r"), array('<br />', ''), $profile['signature']);
	$profile['signature'] = doUBBC($profile['signature']);

	$profile['is_online'] = (!empty($profile['showOnline']) || allowedTo('moderate_forum')) && $profile['isOnline'] > 0;
	$profile['stars'] = empty($profile['stars']) ? array('', '') : explode('#', $profile['stars']);

	if (stristr($profile['avatar'], 'http://') && !empty($modSettings['avatar_check_size']))
	{
		$sizes = url_image_size($profile['avatar']);

		// Does your avatar still fit the maximum size?
		if ($modSettings['avatar_action_too_large'] == 'option_refuse' && is_array($sizes) && (($sizes[0] > $modSettings['avatar_max_width_external'] && !empty($modSettings['avatar_max_width_external'])) || ($sizes[1] > $modSettings['avatar_max_height_external'] && !empty($modSettings['avatar_max_height_external']))))
		{
			// Fix it permanently!
			$profile['avatar'] = '';
			updateMemberData($profile['ID_MEMBER'], array('avatar' => '\'\''));
		}
	}

	// What a monstrous array...
	$themeUser[$user] = array(
		'username' => &$profile['memberName'],
		'name' => &$profile['realName'],
		'id' => &$profile['ID_MEMBER'],
		'is_guest' => $profile['ID_MEMBER'] == 0,
		'title' => !empty($modSettings['titlesEnable']) ? $profile['usertitle'] : '',
		'href' => $scripturl . '?action=profile;u=' . $profile['ID_MEMBER'],
		'link' => '<a href="' . $scripturl . '?action=profile;u=' . $profile['ID_MEMBER'] . '" title="' . $txt[92] . ' ' . $profile['realName'] . '">' . $profile['realName'] . '</a>',
		'email' => &$profile['emailAddress'],
		'hide_email' => (!empty($modSettings['guest_hideContacts']) && $user_info['is_guest']) || (!empty($profile['hideEmail']) && !empty($modSettings['allow_hideEmail']) && !allowedTo('moderate_forum')),
		'email_public' => empty($profile['hideEmail']) || empty($modSettings['allow_hideEmail']),
		'registered' => empty($profile['dateRegistered']) ? $txt[470] : timeformat($profile['dateRegistered']),
		'blurb' => &$profile['personalText'],
		'gender' => array(
			'name' => $gendertxt,
			'image' => !empty($profile['gender']) ? '<img src="' . $settings['images_url'] . '/' . ($profile['gender'] == 1 ? 'Male' : 'Female') . '.gif" alt="' . $gendertxt . '" border="0" />' : ''
		),
		'website' => array(
			'title' => &$profile['websiteTitle'],
			'url' => &$profile['websiteUrl'],
		),
		'birth_date' => &$profile['birthdate'],
		'signature' => &$profile['signature'],
		'location' => &$profile['location'],
		'icq' => $profile['ICQ'] != '' && (empty($modSettings['guest_hideContacts']) || !$user_info['is_guest']) ? array(
			'name' => &$profile['ICQ'],
			'href' => 'http://web.icq.com/whitepages/about_me/1,,,00.html?Uin=' . $profile['ICQ'],
			'link' => '<a href="http://web.icq.com/whitepages/about_me/1,,,00.html?Uin=' . $profile['ICQ'] . '" target="_blank"><img src="http://web.icq.com/whitepages/online?icq=' . $profile['ICQ'] . '&amp;img=5" alt="' . $profile['ICQ'] . '" width="18" height="18" border="0" /></a>',
			'link_text' => '<a href="http://web.icq.com/whitepages/about_me/1,,,00.html?Uin=' . $profile['ICQ'] . '" target="_blank">' . $profile['ICQ'] . '</a>',
		) : array('name' => '', 'add' => '', 'href' => '', 'link' => '', 'link_text' => ''),
		'aim' => $profile['AIM'] != '' && (empty($modSettings['guest_hideContacts']) || !$user_info['is_guest']) ? array(
			'name' => &$profile['AIM'],
			'href' => 'aim:goim?screenname=' . urlencode($profile['AIM']) . '&amp;message=' . $txt['aim_default_message'],
			'link' => '<a href="aim:goim?screenname=' . urlencode($profile['AIM']) . '&amp;message=' . $txt['aim_default_message'] . '"><img src="' . $settings['images_url'] . '/aim.gif" alt="' . $profile['AIM'] . '" border="0" /></a>',
			'link_text' => '<a href="aim:goim?screenname=' . urlencode($profile['AIM']) . '&amp;message=' . $txt['aim_default_message'] . '">' . $profile['AIM'] . '</a>'
		) : array('name' => '', 'href' => '', 'link' => '', 'link_text' => ''),
		'yim' => $profile['YIM'] != '' && (empty($modSettings['guest_hideContacts']) || !$user_info['is_guest']) ? array(
			'name' => &$profile['YIM'],
			'href' => 'http://edit.yahoo.com/config/send_webmesg?.target=' . urlencode($profile['YIM']),
			'link' => '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . urlencode($profile['YIM']) . '"><img src="http://opi.yahoo.com/online?u=' . urlencode($profile['YIM']) . '&amp;m=g&amp;t=0" alt="' . $profile['YIM'] . '" border="0" /></a>',
			'link_text' => '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . urlencode($profile['YIM']) . '">' . $profile['YIM'] . '</a>'
		) : array('name' => '', 'href' => '', 'link' => '', 'link_text' => ''),
		'msn' => $profile['MSN'] !='' && (empty($modSettings['guest_hideContacts']) || !$user_info['is_guest']) ? array(
			'name' => &$profile['MSN'],
			'href' => 'http://members.msn.com/' . $profile['MSN'],
			'link' => '<a href="http://members.msn.com/' . $profile['MSN'] . '" target="_blank"><img src="' . $settings['images_url'] . '/msntalk.gif" alt="' . $profile['MSN'] . '" border="0" /></a>',
			'link_text' => '<a href="http://members.msn.com/' . $profile['MSN'] . '" target="_blank">' . $profile['MSN'] . '</a>'
		) : array('name' => '', 'href' => '', 'link' => '', 'link_text' => ''),
		'real_posts' => $profile['posts'],
		'posts' => $profile['posts'] > 100000 ? $txt[683] : ($profile['posts'] == 1337 ? 'leet' : comma_format($profile['posts'])),
		'avatar' => array(
			'name' => &$profile['avatar'],
			'image' => $profile['avatar'] == '' ? ($profile['ID_ATTACH'] > 0 && !empty($modSettings['avatar_allow_upload']) ? '<img src="' . $scripturl . '?action=dlattach;id=' . $profile['ID_ATTACH'] . ';type=avatar" alt="" />' : '') : (stristr($profile['avatar'], 'http://') && !empty($modSettings['avatar_allow_external_url']) ? '<img src="' . $profile['avatar'] . '"' . ($modSettings['avatar_action_too_large'] == 'option_html_resize' ? (!empty($modSettings['avatar_max_width_external']) ? ' width="' . $modSettings['avatar_max_width_external'] . '"' : '') . (!empty($modSettings['avatar_max_height_external']) ? ' height="' . $modSettings['avatar_max_height_external'] . '"' : '') : '') . ' alt="" border="0" />' : (!empty($modSettings['avatar_allow_server_stored']) ? '<img src="' . $modSettings['avatar_url'] . '/' . htmlspecialchars($profile['avatar']) . '" alt="" border="0" />' : '')),
			'href' => $profile['avatar'] == '' ? ($profile['ID_ATTACH'] > 0 && !empty($modSettings['avatar_allow_upload']) ? $scripturl . '?action=dlattach;id=' . $profile['ID_ATTACH'] . ';type=avatar' : '') : (stristr($profile['avatar'], 'http://') ? $profile['avatar'] : $modSettings['avatar_url'] . '/' . $profile['avatar']),
			'url' => $profile['avatar'] == '' ? '' : (stristr($profile['avatar'], 'http://') ? $profile['avatar'] : $modSettings['avatar_url'] . '/' . $profile['avatar'])
		),
		'last_login' => empty($profile['lastLogin']) ? $txt['never'] : timeformat($profile['lastLogin']),
		'karma' => array(
			'good' => &$profile['karmaGood'],
			'bad' => &$profile['karmaBad'],
			'allow' => !$user_info['is_guest'] && $user_info['posts'] >= $modSettings['karmaMinPosts'] && allowedTo('karma_edit') && !empty($modSettings['karmaMode']) && $ID_MEMBER != $user
		),
		'ip' => htmlspecialchars($profile['memberIP']),
		'online' => array(
			'is_online' => $profile['is_online'],
			'text' => &$txt[$profile['is_online'] ? 'online2' : 'online3'],
			'href' => $scripturl . '?action=pm;sa=send;u=' . $profile['ID_MEMBER'],
			'link' => '<a href="' . $scripturl . '?action=pm;sa=send;u=' . $profile['ID_MEMBER'] . '">' . $txt[$profile['is_online'] ? 'online2' : 'online3'] . '</a>',
			'image_href' => $settings['images_url'] . ($profile['is_online'] ? '/useron' : '/useroff') . '.gif',
			'label' => &$txt[$profile['is_online'] ? 'online4' : 'online5']
		),
		'language' => ucfirst($profile['lngfile']),
		'is_activated' => !empty($profile['is_activated']),
		'options' => $profile['options'],
		'is_guest' => false,
		'group' => $profile['member_group'],
		'group_color' => $profile['member_group_color'],
		'group_id' => $profile['ID_GROUP'],
		'post_group' => $profile['post_group'],
		'post_group_color' => $profile['post_group_color'],
		'group_stars' => str_repeat('<img src="' . str_replace('$language', $context['user']['language'], isset($profile['stars'][1]) ? $settings['images_url'] . '/' . $profile['stars'][1] : '') . '" alt="*" border="0" />', empty($profile['stars'][0]) ? 0 : $profile['stars'][0]),
		'local_time' => timeformat(time() + ($profile['timeOffset'] - $user_info['time_offset']) * 3600, false),
	);

	return true;
}

// Load a theme, by ID.
function loadTheme($ID_THEME = 0, $initialize = true)
{
	global $ID_MEMBER, $user_info, $board_info, $sc;
	global $db_prefix, $txt, $scripturl, $mbname, $modSettings;
	global $context, $settings, $options;

	// The theme was specified by parameter.
	if (!empty($ID_THEME))
		$ID_THEME = (int) $ID_THEME;
	// Use the board's specific theme.
	elseif (!empty($board_info['theme']) && $board_info['override_theme'])
		$ID_THEME = $board_info['theme'];
	// The theme was specified by REQUEST.
	elseif (!empty($_REQUEST['theme']) && (!empty($modSettings['theme_allow']) || allowedTo('admin_forum')))
	{
		$ID_THEME = (int) $_REQUEST['theme'];
		$_SESSION['ID_THEME'] = $ID_THEME;
	}
	// The theme was specified by REQUEST... previously.
	elseif (!empty($_SESSION['ID_THEME']) && (!empty($modSettings['theme_allow']) || allowedTo('admin_forum')))
		$ID_THEME = (int) $_SESSION['ID_THEME'];
	// The theme is just the user's choice. (might use ?board=1;theme=0 to force board theme.)
	elseif (!empty($user_info['theme']) && !isset($_REQUEST['theme']) && (!empty($modSettings['theme_allow']) || allowedTo('admin_forum')))
		$ID_THEME = $user_info['theme'];
	// The theme was specified by the board.
	elseif (!empty($board_info['theme']))
		$ID_THEME = $board_info['theme'];
	// The theme is the forum's default.
	else
		$ID_THEME = $modSettings['theme_guests'];

	// Verify the ID_THEME... no foul play.
	if (empty($modSettings['theme_default']) && $ID_THEME == 1 && !allowedTo('admin_forum'))
		$ID_THEME = $modSettings['theme_guests'];
	elseif (!empty($modSettings['knownThemes']) && !empty($modSettings['theme_allow']) && !allowedTo('admin_forum'))
	{
		$themes = explode(',', $modSettings['knownThemes']);
		if (!in_array($ID_THEME, $themes))
			$ID_THEME = $modSettings['theme_guests'];
		else
			$ID_THEME = (int) $ID_THEME;
	}
	else
		$ID_THEME = (int) $ID_THEME;

	$member = empty($ID_MEMBER) ? -1 : $ID_MEMBER;

	// Load variables from the current or default theme, global or this user's.
	$result = db_query("
		SELECT variable, value, ID_MEMBER, ID_THEME
		FROM {$db_prefix}themes
		WHERE ID_MEMBER IN (0, $member)
			AND ID_THEME" . ($ID_THEME == 1 ? ' = 1' : " IN ($ID_THEME, 1)"), __FILE__, __LINE__);
	// Pick between $settings and $options depending on whose data it is.
	$themeData = array(0 => array(), $member => array());
	while ($row = mysql_fetch_assoc($result))
	{
		// If this is the theme_dir of the default theme, store it.
		if (in_array($row['variable'], array('theme_dir', 'theme_url', 'images_url')) && $row['ID_THEME'] == '1' && empty($row['ID_MEMBER']))
			$themeData[0]['default_' . $row['variable']] = $row['value'];

		// If this isn't set yet, is a theme option, or is not the default theme..
		if (!isset($themeData[$row['ID_MEMBER']][$row['variable']]) || $row['ID_THEME'] != '1')
			$themeData[$row['ID_MEMBER']][$row['variable']] = substr($row['variable'], 0, 5) == 'show_' ? $row['value'] == '1' : $row['value'];
	}
	mysql_free_result($result);

	$settings = $themeData[0];
	$options = $themeData[$member];

	$settings['theme_id'] = $ID_THEME;

	$settings['actual_theme_url'] = $settings['theme_url'];
	$settings['actual_images_url'] = $settings['images_url'];
	$settings['actual_theme_dir'] = $settings['theme_dir'];

	if (!$initialize)
		return;

	// Set up the contextual user array.
	$context['user'] = array(
		'id' => &$ID_MEMBER,
		'is_logged' => !$user_info['is_guest'],
		'is_guest' => &$user_info['is_guest'],
		'is_admin' => &$user_info['is_admin'],
		'is_mod' => false,
		'username' => &$user_info['username'],
		'name' => $user_info['is_guest'] ? $txt[28] : $user_info['name'],
		'language' => &$user_info['language'],
		'email' => &$user_info['email']
	);

	// Determine the current smiley set.
	$user_info['smiley_set'] = (!in_array($user_info['smiley_set'], explode(',', $modSettings['smiley_sets_known'])) && $user_info['smiley_set'] != 'none') || empty($modSettings['smiley_sets_enable']) ? (!empty($settings['smiley_sets_default']) ? $settings['smiley_sets_default'] : $modSettings['smiley_sets_default']) : $user_info['smiley_set'];

	// Some basic information...
	if (!isset($context['html_headers']))
		$context['html_headers'] = '';
	$context['menu_separator'] = !empty($settings['use_image_buttons']) ? ' ' : ' | ';
	$context['session_id'] = &$sc;
	$context['forum_name'] = &$mbname;

	// This determines the server... not used in many places, except for login fixing.
	$context['server'] = array(
		'is_iis' => strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== false,
		'is_apache' => strpos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false,
		'is_cgi' => php_sapi_name() == 'cgi'
	);
	// A bug in some versions of IIS under CGI (older ones) makes cookie setting not work with Location: headers.
	$context['server']['needs_login_fix'] = $context['server']['is_cgi'];

	// The following determines the user agent. (browser)
	$context['browser'] = array(
		'is_opera' => strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false,
		'is_opera6' => strpos($_SERVER['HTTP_USER_AGENT'], 'Opera 6') !== false,
		'is_ie4' => strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 4') !== false,
		'is_safari' => strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== false,
		'is_mac_ie' => strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 5.') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'Mac') !== false
	);

	$context['browser']['is_gecko'] = strpos($_SERVER['HTTP_USER_AGENT'], 'Gecko') !== false && !$context['browser']['is_safari'];

	// Internet Explorer 5 and 6 are often "emulated".
	$context['browser']['is_ie6'] = strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6') !== false && !$context['browser']['is_opera'] && !$context['browser']['is_gecko'];
	$context['browser']['is_ie5.5'] = strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 5.5') !== false && !$context['browser']['is_opera'] && !$context['browser']['is_gecko'];
	$context['browser']['is_ie5'] = strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 5.0') !== false && !$context['browser']['is_opera'] && !$context['browser']['is_gecko'];

	$context['browser']['is_ie'] = $context['browser']['is_ie4'] || $context['browser']['is_ie5'] || $context['browser']['is_ie5.5'] || $context['browser']['is_ie6'];
	$context['browser']['needs_size_fix'] = ($context['browser']['is_ie5'] || $context['browser']['is_ie5.5'] || $context['browser']['is_ie4'] || $context['browser']['is_opera6']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Mac') === false;

	// Set the top level linktree up.
	array_unshift($context['linktree'], array(
		'url' => &$scripturl,
		'name' => &$context['forum_name']
	));

	// Wireless mode?  Load up the wireless stuff.
	if (WIRELESS)
	{
		$context['template_layers'] = array(WIRELESS_PROTOCOL);
		$templates = array('Wireless', 'index');
	}
	else
	{
		// Custom templates to load, or just default?
		if (isset($settings['theme_templates']))
			$templates = explode(',', $settings['theme_templates']);
		else
			$templates = array('index');

		// Custom template layers?
		if (isset($settings['theme_layers']))
			$context['template_layers'] = explode(',', $settings['theme_layers']);
		else
			$context['template_layers'] = array('main');
	}

	$txt = array();

	// Load each template.... and attempt to load its associated language file.
	foreach ($templates as $template)
	{
		loadTemplate($template);
		loadLanguage($template, '', false);
	}

	// Load the Modifications language file, always ;). (but don't sweat it if it doesn't exist.)
	loadLanguage('Modifications', '', false);

	// Initialize the theme.
	loadSubTemplate('init', 'ignore');

	if (isset($settings['use_default_images']) && $settings['use_default_images'] == 'always')
	{
		$settings['theme_url'] = $settings['default_theme_url'];
		$settings['images_url'] = $settings['default_images_url'];
		$settings['theme_dir'] = $settings['default_theme_dir'];
	}

	// Set the character set from the template.
	$context['character_set'] = $txt['lang_character_set'];
	$context['right_to_left'] = !empty($txt['lang_rtl']);

	// Fix font size with HTML 4.01, etc.
	if (isset($settings['doctype']))
		$context['browser']['needs_size_fix'] |= $settings['doctype'] == 'html' && $context['browser']['is_ie6'];
}

// Load a template - if the theme doesn't include it, use the default.
function loadTemplate($template_name)
{
	global $context, $settings, $txt, $scripturl, $boarddir;

	// Try the current theme's first.
	if (file_exists($settings['theme_dir'] . '/' . $template_name . '.template.php'))
		template_include($settings['theme_dir'] . '/' . $template_name . '.template.php', true);
	elseif (file_exists($settings['default_theme_dir'] . '/' . $template_name . '.template.php'))
	{
		// Make it known that this template uses different directories...
		$settings['default_template'] = true;
		template_include($settings['default_theme_dir'] . '/' . $template_name . '.template.php', true);
	}
	// Hmmm... doesn't exist?!  I don't suppose the directory is wrong, is it?
	elseif (!file_exists($settings['default_theme_dir']) && file_exists($boarddir . '/Themes/default'))
	{
		$settings['default_theme_dir'] = $boarddir . '/Themes/default';

		if (!empty($context['user']['is_admin']) && !isset($_GET['id']))
		{
			loadLanguage('Errors');
			echo '
<div style="color: red; padding: 2ex; background-color: white; border: 2px dashed;">
	<a href="', $scripturl . '?action=theme;sa=settings;id=1;sesc=' . $context['session_id'], '" style="color: red;">', $txt['theme_dir_wrong'], '</a>
</div>';
		}

		loadTemplate($template_name);
	}
	// Cause an error otherwise.
	elseif ($template_name != 'Errors' && $template_name != 'index')
		fatal_lang_error('theme_template_error', true, array((string) $template_name));
	else
		die(log_error(sprintf(isset($txt['theme_template_error']) ? $txt['theme_template_error'] : '%s', (string) $template_name)));
}

// Load a sub template... fatal is for templates that shouldn't get a 'pretty' error screen.
function loadSubTemplate($sub_template_name, $fatal = false)
{
	global $context, $settings, $options, $txt;

	// Figure out what the template function is named.
	$theme_function = 'template_' . $sub_template_name;
	if (function_exists($theme_function))
		$theme_function();
	elseif ($fatal === false)
		fatal_lang_error('theme_template_error', true, array((string) $sub_template_name));
	elseif ($fatal !== 'ignore')
		die(log_error(sprintf(isset($txt['theme_template_error']) ? $txt['theme_template_error'] : '%s', (string) $sub_template_name)));
}

// Load a language file.  Tries the current and default themes as well as the user and global languages.
function loadLanguage($template_name, $lang = '', $fatal = true)
{
	global $boarddir, $boardurl, $user_info, $language_dir, $language, $settings, $txt;
	static $already_loaded = array();

	// Default to the user's language.
	if ($lang == '')
		$lang = $user_info['language'];

	// Fallback on the default theme if necessary.
	$attempts = array(
		array($settings['theme_dir'], $template_name, $lang, $settings['theme_url']),
		array($settings['theme_dir'], $template_name, $language, $settings['theme_url']),
		array($settings['default_theme_dir'], $template_name, $lang, $settings['default_theme_url']),
		array($settings['default_theme_dir'], $template_name, $language, $settings['default_theme_url'])
	);

	// Try to include the language file.
	foreach ($attempts as $k => $file)
		if (file_exists($file[0] . '/languages/' . $file[1] . '.' . $file[2] . '.php'))
		{
			$language_dir = $file[0] . '/languages';
			$lang = $file[2];
			// Hmmm... do we really still need this?
			$language_url = $file[3];
			template_include($file[0] . '/languages/' . $file[1] . '.' . $file[2] . '.php');

			break;
		}

	// That couldn't be found!  Log the error, but try to continue normally.
	if (!isset($language_url) && $fatal)
		log_error(sprintf($txt['theme_language_error'], $template_name . '.' . $lang));

	// Return the language actually loaded.
	return $lang;
}

// Get all parent boards (requires first parent as parameter)
function getBoardParents($id_parent)
{
	global $db_prefix, $scripturl, $txt;

	$boards = array();

	// Loop while the parent is non-zero.
	while ($id_parent != 0)
	{
		$result = db_query("
			SELECT
				b.ID_PARENT, b.name, $id_parent AS ID_BOARD, IFNULL(mem.ID_MEMBER, 0) AS ID_MODERATOR,
				mem.realName
			FROM {$db_prefix}boards AS b
				LEFT JOIN {$db_prefix}moderators AS mods ON (mods.ID_BOARD = b.ID_BOARD)
				LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = mods.ID_MEMBER)
			WHERE b.ID_BOARD = $id_parent", __FILE__, __LINE__);
		// In the EXTREMELY unlikely event this happens, give an error message.
		if (mysql_num_rows($result) == 0)
			fatal_lang_error('parent_not_found');
		while ($row = mysql_fetch_assoc($result))
		{
			if (!isset($boards[$row['ID_BOARD']]))
			{
				$id_parent = $row['ID_PARENT'];
				$boards[$row['ID_BOARD']] = array(
					'url' => $scripturl . '?board=' . $row['ID_BOARD'] . '.0',
					'name' => $row['name'],
					'moderators' => array()
				);
			}
			// If a moderator exists for this board, add that moderator for all children too.
			if (!empty($row['ID_MODERATOR']))
				foreach ($boards as $id => $dummy)
				{
					$boards[$id]['moderators'][$row['ID_MODERATOR']] = array(
						'id' => $row['ID_MODERATOR'],
						'name' => $row['realName'],
						'href' => $scripturl . '?action=profile;u=' . $row['ID_MODERATOR'],
						'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MODERATOR'] . '" title="' . $txt[62] . '">' . $row['realName'] . '</a>'
					);
				}
		}
		mysql_free_result($result);
	}

	return $boards;
}

// Replace all vulgar words with respective proper words. (substring or whole words..)
function &censorText(&$text)
{
	global $modSettings, $options, $settings;
	static $censor_vulgar = null, $censor_proper;

	if ((!empty($options['show_no_censored']) && $settings['allow_no_censored']) || empty($modSettings['censor_vulgar']))
		return $text;

	// If they haven't yet been loaded, load them.
	if ($censor_vulgar == null)
	{
		$censor_vulgar = explode("\n", $modSettings['censor_vulgar']);
		$censor_proper = explode("\n", $modSettings['censor_proper']);

		// Quote them for use in regular expressions.
		for ($i = 0, $n = count($censor_vulgar); $i < $n; $i++)
		{
			$censor_vulgar[$i] = strtr(preg_quote($censor_vulgar[$i], '/'), array('\\\\\\*' => '[*]', '\\*' => '[^\s]*?', '&' => '&amp;'));
			$censor_vulgar[$i] = (empty($modSettings['censorWholeWord']) ? '/' . $censor_vulgar[$i] . '/' : '/\b' . $censor_vulgar[$i] . '\b/') . (empty($modSettings['censorIgnoreCase']) ? '' : 'i');

			$censor_proper[$i] = $censor_proper[$i];
		}
	}

	$text = preg_replace($censor_vulgar, $censor_proper, $text);
	return $text;
}

// Create a little jumpto box.
function loadJumpTo()
{
	global $db_prefix, $context, $user_info;

	if (isset($context['jump_to']))
		return;

	// Find the boards/cateogories they can see.
	$request = db_query("
		SELECT c.name as catName, c.ID_CAT, b.ID_BOARD, b.name AS boardName, b.childLevel
		FROM {$db_prefix}boards AS b, {$db_prefix}categories AS c
		WHERE c.ID_CAT = b.ID_CAT
			AND $user_info[query_see_board]
		ORDER BY c.catOrder, b.boardOrder", __FILE__, __LINE__);
	$context['jump_to'] = array();
	$this_cat = array('id' => -1);
	while ($row = mysql_fetch_assoc($request))
	{
		if ($this_cat['id'] != $row['ID_CAT'])
		{
			$this_cat = &$context['jump_to'][];
			$this_cat['id'] = $row['ID_CAT'];
			$this_cat['name'] = $row['catName'];
			$this_cat['boards'] = array();
		}

		$this_cat['boards'][] = array(
			'id' => $row['ID_BOARD'],
			'name' => $row['boardName'],
			'child_level' => $row['childLevel'],
			'is_current' => isset($context['current_board']) && $row['ID_BOARD'] == $context['current_board']
		);
	}
	mysql_free_result($request);
}

// Load the template/language file using eval or require? (with eval we can show an error message!)
function template_include($filename, $once = false)
{
	global $txt, $context, $settings, $options, $scripturl, $modSettings, $language_dir, $user_info, $boardurl, $boarddir;
	global $maintenance, $mtitle, $mmessage;
	static $templates = array();

	// Don't include the file more than once, if $once is true.
	if ($once && in_array($filename, $templates))
		return;
	// Add this file to the include list, whether $once is true or not.
	else
		$templates[] = $filename;

	// Are we going to use eval?
	if (empty($modSettings['disableTemplateEval']))
	{
		$file_found = file_exists($filename) && eval('?' . '>' . implode('', file($filename))) !== false;
		$settings['current_include_filename'] = $filename;
	}
	else
	{
		$file_found = file_exists($filename);

		if ($once && $file_found)
			require_once($filename);
		elseif ($file_found)
			require($filename);
	}

	if (!$file_found)
	{
		ob_end_clean();
		if (!empty($modSettings['enableCompressedOutput']))
			@ob_start('ob_gzhandler');
		else
			ob_start();

		// Don't cache error pages!!
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-cache');

		if (!isset($txt['template_parse_error']))
		{
			$txt['template_parse_error'] = 'Template Parse Error!';
			$txt['template_parse_error_message'] = 'It seems something has gone sour on the forum with the template system.  This problem should only be temporary, so please come back later and try again.  If you continue to see this message, please contact the administrator.<br /><br />You can also try <a href="javascript:location.reload();">refreshing this page</a>.';
			$txt['template_parse_error_details'] = 'There was a problem loading the <tt><b>%1$s</b></tt> template or language file.  Please check the syntax and try again - remember, single quotes (<tt>\'</tt>) often have to be escaped with a slash (<tt>\\</tt>).  To see more specific error information from PHP, try <a href="' . $boardurl . '%1$s">accessing the file directly</a>.<br /><br />You may want to try to <a href="javascript:location.reload();">refresh this page</a> or <a href="' . $scripturl . '?theme=1">use the default theme</a>.';
		}

		if (!empty($maintenance) && !allowedTo('admin_forum'))
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>', $mtitle, '</title>
	</head>
	<body>
		<h3>', $mtitle, '</h3>
		', $mmessage, '
	</body>
</html>';
		elseif (!allowedTo('admin_forum'))
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>', $txt['template_parse_error'], '</title>
	</head>
	<body>
		<h3>', $txt['template_parse_error'], '</h3>
		', $txt['template_parse_error_message'], '
	</body>
</html>';
		else
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>', $txt['template_parse_error'], '</title>
	</head>
	<body>
		<h3>', $txt['template_parse_error'], '</h3>
		', sprintf($txt['template_parse_error_details'], strtr($filename, array($boarddir => '', strtr($boarddir, '\\', '/') => ''))), '
	</body>
</html>';

		die;
	}
}

// Attempt to start the session, unless it already has been.
function loadSession()
{
	global $HTTP_SESSION_VARS, $modSettings;

	// Attempt to change a few PHP settings.
	@ini_set('session.use_cookies', true);
	@ini_set('session.use_only_cookies', false);
	@ini_set('arg_separator.output', '&amp;');

	// It's already been started - there's really nothing we can do.
	if (session_id() == '')
	{
		// This is here to stop people from using bad junky PHPSESSIDs.
		if (isset($_REQUEST[session_name()]) && preg_match('~^[A-Za-z0-9]{32}$~', $_REQUEST[session_name()]) == 0 && !isset($_COOKIE[session_name()]))
			$_COOKIE[session_name()] = md5(md5('smf_sess_' . time()) . rand());

		// Use database sessions?
		if (!empty($modSettings['databaseSession_enable']))
			session_set_save_handler('sessionOpen', 'sessionClose', 'sessionRead', 'sessionWrite', 'sessionDestroy', 'sessionGC');
		elseif (@ini_get('session.gc_maxlifetime') <= 1440 && !empty($modSettings['databaseSession_lifetime']))
			@ini_set('session.gc_maxlifetime', max($modSettings['databaseSession_lifetime'], 60));

		session_start();

		// Change it so the cache settings are a little looser than default.
		if (!empty($modSettings['databaseSession_loose']))
			header('Cache-Control: private');
	}

	// While PHP 4.1.x should use $_SESSION, it seems to need this to do it right.
	if (@version_compare(PHP_VERSION, '4.2.0') == -1)
		$HTTP_SESSION_VARS['php_412_bugfix'] = true;

	// Set the randomly generated code.
	if (!isset($_SESSION['rand_code']))
		$_SESSION['rand_code'] = md5(session_id() . rand());
	$GLOBALS['sc'] = &$_SESSION['rand_code'];
}

function sessionOpen($save_path, $session_name)
{
	return true;
}

function sessionClose()
{
	return true;
}

function sessionRead($session_id)
{
	global $db_prefix;

	if (preg_match('~^[A-Za-z0-9]{16,32}$~', $session_id) == 0)
		return false;

	// Look for it in the database.
	$result = db_query("
		SELECT data
		FROM {$db_prefix}sessions
		WHERE session_id = '" . addslashes($session_id) . "'
		LIMIT 1", __FILE__, __LINE__);
	list ($sess_data) = mysql_fetch_row($result);
	mysql_free_result($result);

	return $sess_data;
}

function sessionWrite($session_id, $data)
{
	global $db_prefix;

	if (preg_match('~^[A-Za-z0-9]{16,32}$~', $session_id) == 0)
		return false;

	// First try to update an existing row...
	$result = db_query("
		UPDATE {$db_prefix}sessions
		SET data = '" . addslashes($data) . "', last_update = " . time() . "
		WHERE session_id = '" . addslashes($session_id) . "'
		LIMIT 1", __FILE__, __LINE__);

	// If that didn't work, try inserting a new one.
	if (db_affected_rows() == 0)
		$result = db_query("
			INSERT IGNORE INTO {$db_prefix}sessions
				(session_id, data, last_update)
			VALUES ('" . addslashes($session_id) . "', '" . addslashes($data) . "', " . time() . ")", __FILE__, __LINE__);

	return $result;
}

function sessionDestroy($session_id)
{
	global $db_prefix;

	if (preg_match('~^[A-Za-z0-9]{16,32}$~', $session_id) == 0)
		return false;

	// Just delete the row...
	return db_query("
		DELETE FROM {$db_prefix}sessions
		WHERE session_id = '" . addslashes($session_id) . "'
		LIMIT 1", __FILE__, __LINE__);
}

function sessionGC($max_lifetime)
{
	global $db_prefix, $modSettings;

	// Just set to the default or lower?  Ignore it for a higher value. (hopefully)
	if ($max_lifetime <= 1440 && !empty($modSettings['databaseSession_lifetime']))
		$max_lifetime = max($modSettings['databaseSession_lifetime'], 60);

	// Clean up ;).
	return db_query("
		DELETE FROM {$db_prefix}sessions
		WHERE last_update < " . (time() - $max_lifetime), __FILE__, __LINE__);
}

?>