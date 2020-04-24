<?php
/******************************************************************************
* Memberlist.php                                                              *
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

/*	This file contains the functions for displaying and searching in the
	members list.  It does so with these functions:

	void MemberList()
		- shows a list of registered members.
		- if a subaction is not specified, lists all registered members.
		- allows searching for members with the 'search' sub action.
		- calls MLAll or MLSearch depending on the sub action.
		- uses the Memberlist template with the main sub template.
		- requires the view_mlist permission.
		- is accessed via ?action=mlist.

	void MLAll()
		- used to display all members on a page by page basis with sorting.
		- called from MemberList().
		- can be passed a sort parameter, to order the display of members.
		- calls printMemberListRows to retrieve the results of the query.

	void MLSearch()
		- used to search for members or display search results.
		- called by MemberList().
		- if variable 'search' is empty displays search dialog box, using the
		  search sub template.
		- calls printMemberListRows to retrieve the results of the query.

	void printMemberListRows(resource request)
		- retrieves results of the request passed to it
		- puts results of request into the context for the sub template.
*/

// Show a listing of the registered members.
function Memberlist()
{
	global $scripturl, $txt, $modSettings, $context, $settings;

	// Make sure they can view the memberlist.
	isAllowedTo('view_mlist');

	loadTemplate('Memberlist');

	$context['listing_by'] = !empty($_GET['sa']) ? $_GET['sa'] : 'all';

	// $subActions array format:
	// 'subaction' => array('label', 'function', 'is_selected')
	$subActions = array(
		'all' => array(&$txt[303], 'MLAll', $context['listing_by'] == 'all'),
		'search' => array(&$txt['mlist_search'], 'MLSearch', $context['listing_by'] == 'search'),
	);

	// Set up the sort links.
	$context['sort_links'] = array();
	foreach ($subActions as $act => $text)
			$context['sort_links'][] = ($text[2] ? '<img src="' . $settings['images_url'] . '/selected.gif" alt="&gt;" /> ' : '') . '<a href="' . $scripturl . '?action=mlist' . (!empty($act) ? ';sa=' . $act : '') . '">' . $text[0] . '</a>';
	$context['sort_links'] = implode(' | ', $context['sort_links']);

	$context['num_members'] = $modSettings['memberCount'];

	// Set up the columns...
	$context['columns'] = array(
		'isOnline' => array(
			'label' => $txt['online8'],
			'width' => '20'
		),
		'realName' => array(
			'label' => $txt[35]
		),
		'emailAddress' => array(
			'label' => $txt[307],
			'width' => '25'
		),
		'websiteUrl' => array(
			'label' => $txt[96],
			'width' => '25'
		),
		'ICQ' => array(
			'label' => $txt[513],
			'width' => '25'
		),
		'AIM' => array(
			'label' => $txt[603],
			'width' => '25'
		),
		'YIM' => array(
			'label' => $txt[604],
			'width' => '25'
		),
		'MSN' => array(
			'label' => $txt['MSN'],
			'width' => '25'
		),
		'ID_GROUP' => array(
			'label' => $txt[87]
		),
		'registered' => array(
			'label' => $txt[233]
		),
		'posts' => array(
			'label' => $txt[21],
			'width' => '115',
			'colspan' => '2'
		)
	);

	$context['linktree'][] = array(
		'url' => $scripturl . '?action=mlist',
		'name' => &$txt[332]
	);

	$context['can_send_pm'] = allowedTo('pm_send');

	// Jump to the sub action.
	if (isset($subActions[$context['listing_by']]))
		$subActions[$context['listing_by']][1]();
	else
		$subActions['all'][1]();
}

// List all members, page by page.
function MLAll()
{
	global $txt, $scripturl, $db_prefix, $user_info;
	global $modSettings, $context;

	// Set defaults for sort (realName) and start. (0)
	if (!isset($_REQUEST['sort']) || !array_key_exists($_REQUEST['sort'], $context['columns']))
		$_REQUEST['sort'] = 'realName';

	if (!is_numeric($_REQUEST['start']))
	{
		$request = db_query("
			SELECT COUNT(ID_MEMBER)
			FROM {$db_prefix}members
			WHERE LOWER(SUBSTRING(realName, 1, 1)) < '" . strtolower($_REQUEST['start']) . "'", __FILE__, __LINE__);
		list ($_REQUEST['start']) = mysql_fetch_row($request);
		mysql_free_result($request);
	}

	$context['letter_links'] = '';
	for ($i = 97; $i < 123; $i++)
		$context['letter_links'] .= '<a href="' . $scripturl . '?action=mlist;sa=all;start=' . chr($i) . '">' . strtoupper(chr($i)) . '</a> ';

	// Sort out the column information.
	foreach ($context['columns'] as $col => $dummy)
	{
		$context['columns'][$col]['href'] = $scripturl . '?action=mlist;sort=' . $col . ';start=0';

		if (!isset($_REQUEST['desc']) && $col == $_REQUEST['sort'])
			$context['columns'][$col]['href'] .= ';desc';

		$context['columns'][$col]['link'] = '<a href="' . $context['columns'][$col]['href'] . '">' . $context['columns'][$col]['label'] . '</a>';
		$context['columns'][$col]['selected'] = $_REQUEST['sort'] == $col;
	}

	$context['sort_by'] = $_REQUEST['sort'];
	$context['sort_direction'] = !isset($_REQUEST['desc']) ? 'down' : 'up';

	// Construct the page index.
	$context['page_index'] = constructPageIndex($scripturl . '?action=mlist;sort=' . $_REQUEST['sort'] . (isset($_REQUEST['desc']) ? ';desc' : ''), $_REQUEST['start'], $modSettings['memberCount'], $modSettings['defaultMaxMembers']);

	// Send the data to the template.
	$context['start'] = $_REQUEST['start'] + 1;
	$context['end'] = min($_REQUEST['start'] + $modSettings['defaultMaxMembers'], $modSettings['memberCount']);

	$context['page_title'] = $txt[308] . ' ' . $context['start'] . ' ' . $txt[311] . ' ' . $context['end'];
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=mlist;sort=' . $_REQUEST['sort'] . ';start=' . $_REQUEST['start'],
		'name' => &$context['page_title'],
		'extra_after' => ' (' . $txt[309] . ' ' . $context['num_members'] . ' ' . $txt[310] . ')'
	);

	// List out the different sorting methods...
	$sort_methods = array(
		'isOnline' => array(
			'down' => '(ISNULL(lo.logTime)' . (!allowedTo('moderate_forum') ? ' OR NOT mem.showOnline' : '') . ') ASC, realName ASC',
			'up' => '(ISNULL(lo.logTime)' . (!allowedTo('moderate_forum') ? ' OR NOT mem.showOnline' : '') . ') DESC, realName DESC'
		),
		'realName' => array(
			'down' => 'mem.realName ASC',
			'up' => 'mem.realName DESC'
		),
		'emailAddress' => array(
			'down' => (allowedTo('moderate_forum') || empty($modSettings['allow_hideEmail'])) ? 'mem.emailAddress ASC' : 'mem.hideEmail ASC, mem.emailAddress ASC',
			'up' => (allowedTo('moderate_forum') || empty($modSettings['allow_hideEmail'])) ? 'mem.emailAddress DESC' : 'mem.hideEmail DESC, mem.emailAddress DESC'
		),
		'websiteUrl' => array(
			'down' => 'LENGTH(mem.websiteURL) > 0 DESC, ISNULL(mem.websiteURL) ASC, mem.websiteURL ASC',
			'up' => 'LENGTH(mem.websiteURL) > 0 ASC, ISNULL(mem.websiteURL) DESC, mem.websiteURL DESC'
		),
		'ICQ' => array(
			'down' => 'LENGTH(mem.ICQ) > 0 DESC, ISNULL(mem.ICQ) OR mem.ICQ = 0 ASC, mem.ICQ ASC',
			'up' => 'LENGTH(mem.ICQ) > 0 ASC, ISNULL(mem.ICQ) OR mem.ICQ = 0 DESC, mem.ICQ DESC'
		),
		'AIM' => array(
			'down' => 'LENGTH(mem.AIM) > 0 DESC, ISNULL(mem.AIM) ASC, mem.AIM ASC',
			'up' => 'LENGTH(mem.AIM) > 0 ASC, ISNULL(mem.AIM) DESC, mem.AIM DESC'
		),
		'YIM' => array(
			'down' => 'LENGTH(mem.YIM) > 0 DESC, ISNULL(mem.YIM) ASC, mem.YIM ASC',
			'up' => 'LENGTH(mem.YIM) > 0 ASC, ISNULL(mem.YIM) DESC, mem.YIM DESC'
		),
		'MSN' => array(
			'down' => 'LENGTH(mem.MSN) > 0 DESC, ISNULL(mem.MSN) ASC, mem.MSN ASC',
			'up' => 'LENGTH(mem.MSN) > 0 ASC, ISNULL(mem.MSN) DESC, mem.MSN DESC'
		),
		'registered' => array(
			'down' => 'mem.dateRegistered ASC',
			'up' => 'mem.dateRegistered DESC'
		),
		'ID_GROUP' => array(
			'down' => 'ISNULL(mg.groupName) ASC, mg.groupName ASC',
			'up' => 'ISNULL(mg.groupName) DESC, mg.groupName DESC'
		),
		'posts' => array(
			'down' => 'mem.posts DESC',
			'up' => 'mem.posts ASC'
		)
	);

	// Select the members from the database.
	$request = db_query("
		SELECT
			mem.memberName, mem.realName, mem.websiteTitle, mem.websiteUrl, mem.posts,
			mem.ID_GROUP, mem.ICQ, mem.AIM, mem.YIM, mem.MSN, mem.emailAddress,
			mem.hideEmail, mem.ID_MEMBER, IFNULL(lo.logTime, 0) AS isOnline,
			IFNULL(mg.groupName, '') AS groupName, mem.showOnline, mem.dateRegistered
		FROM {$db_prefix}members AS mem
			LEFT JOIN {$db_prefix}log_online AS lo ON (lo.ID_MEMBER = mem.ID_MEMBER)
			LEFT JOIN {$db_prefix}membergroups AS mg ON (mg.ID_GROUP = IF(mem.ID_GROUP = 0, mem.ID_POST_GROUP, mem.ID_GROUP))
		ORDER BY " . $sort_methods[$_REQUEST['sort']][$context['sort_direction']] . "
		LIMIT $_REQUEST[start], $modSettings[defaultMaxMembers]", __FILE__, __LINE__);

	printMemberListRows($request);
}

// Search for members...
function MLSearch()
{
	global $txt, $scripturl, $db_prefix, $context, $user_info, $modSettings;

	$context['page_title'] = $txt['mlist_search'];

	// They're searching..
	if (isset($_POST['search']) || isset($_REQUEST['fields']))
	{
		$_POST['search'] = isset($_GET['search']) ? $_GET['search'] : $_POST['search'];
		$_POST['fields'] = isset($_GET['fields']) ? explode(',', $_GET['fields']) : $_POST['fields'];

		$context['old_search'] = $_REQUEST['search'];
		$context['old_search_value'] = urlencode($_REQUEST['search']);

		// No fields?  Use default...
		if (empty($_POST['fields']))
			$_POST['fields'] = array('name');

		// Search for a name?
		if (in_array('name', $_POST['fields']))
			$fields = array('memberName', 'realName');
		else
			$fields = array();
		// Search for messengers...
		if (in_array('messenger', $_POST['fields']) && (!$user_info['is_guest'] || empty($modSettings['guest_hideContacts'])))
			$fields += array(3 => 'MSN', 'AIM', 'ICQ', 'YIM');
		// Search for websites.
		if (in_array('website', $_POST['fields']))
			$fields += array(7 => 'websiteTitle', 'websiteUrl');
		// Search for groups.
		if (in_array('group', $_POST['fields']))
			$fields += array(9 => 'IFNULL(groupName, \'\')');
		// Search for an email address?
		if (in_array('email', $_POST['fields']))
		{
			$fields += array(2 => allowedTo('moderate_forum') ? 'emailAddress' : '(hideEmail = 0 AND emailAddress');
			$condition = allowedTo('moderate_forum') ? '' : ')';
		}
		else
			$condition = '';

		$query = $_POST['search'] == '' ? "= ''" : "LIKE '%" . $_POST['search'] . "%'";

		$request = db_query("
			SELECT COUNT(ID_MEMBER)
			FROM {$db_prefix}members AS mem
				LEFT JOIN {$db_prefix}membergroups AS mg ON (mg.ID_GROUP = IF(mem.ID_GROUP = 0, mem.ID_POST_GROUP, mem.ID_GROUP))
			WHERE " . implode(" $query OR ", $fields) . " $query$condition", __FILE__, __LINE__);
		list ($numResults) = mysql_fetch_row($request);
		mysql_free_result($request);

		$context['page_index'] = constructPageIndex($scripturl . '?action=mlist;sa=search;search=' . $_POST['search'] . ';fields=' . implode(',', $_POST['fields']), $_REQUEST['start'], $numResults, $modSettings['defaultMaxMembers']);

		// Find the members from the database.
		$request = db_query("
			SELECT
				memberName, realName, websiteTitle, websiteUrl, posts, mem.ID_GROUP, ICQ, AIM, YIM, MSN, emailAddress,
				hideEmail, mem.ID_MEMBER, IFNULL(lo.logTime, 0) AS isOnline, IFNULL(mg.groupName, '') AS groupName,
				mem.dateRegistered
			FROM {$db_prefix}members AS mem
				LEFT JOIN {$db_prefix}log_online AS lo ON (lo.ID_MEMBER = mem.ID_MEMBER)
				LEFT JOIN {$db_prefix}membergroups AS mg ON (mg.ID_GROUP = IF(mem.ID_GROUP = 0, mem.ID_POST_GROUP, mem.ID_GROUP))
			WHERE " . implode(" $query OR ", $fields) . " $query$condition
			LIMIT $_REQUEST[start], $modSettings[defaultMaxMembers]", __FILE__, __LINE__);
		printMemberListRows($request);
		mysql_free_result($request);
	}
	else
	{
		$context['sub_template'] = 'search';
		$context['old_search'] = isset($_REQUEST['search']) ? htmlspecialchars($_REQUEST['search']) : '';
	}

	$context['linktree'][] = array(
		'url' => $scripturl . '?action=mlist;sa=search',
		'name' => &$context['page_title']
	);
}

function printMemberListRows($request)
{
	global $scripturl, $txt, $db_prefix, $user_info, $modSettings;
	global $context, $settings, $months;

	// Get the most posts.
	$result = db_query("
		SELECT MAX(posts)
		FROM {$db_prefix}members", __FILE__, __LINE__);
	list ($MOST_POSTS) = mysql_fetch_row($result);
	mysql_free_result($result);

	// Avoid division by zero...
	if ($MOST_POSTS == 0)
		$MOST_POSTS = 1;

	// Load all the members for display.
	$context['members'] = array();
	while ($row = mysql_fetch_assoc($request))
	{
		$is_online = (!empty($row['showOnline']) || allowedTo('moderate_forum')) && $row['isOnline'] > 0;

		// If contact details are hidden... respect them.
		if (!empty($modSettings['guest_hideContacts']) && $user_info['is_guest'])
		{
			$row['ICQ'] = '';
			$row['MSN'] = '';
			$row['YIM'] = '';
			$row['AOL'] = '';
		}

		$context['members'][$row['ID_MEMBER']] = array(
			'username' => $row['memberName'],
			'name' => $row['realName'],
			'id' => $row['ID_MEMBER'],
			'href' => $scripturl . '?action=profile;u=' . $row['ID_MEMBER'],
			'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['realName'] . '</a>',
			'online' => array(
				'is_online' => $is_online,
				'text' => &$txt[$is_online ? 'online2' : 'online3'],
				'href' => $scripturl . '?action=pm;sa=send;u=' . $row['ID_MEMBER'],
				'link' => '<a href="' . $scripturl . '?action=pm;sa=send;u=' . $row['ID_MEMBER'] . '">' . $txt[$is_online ? 'online2' : 'online3'] . '</a>',
				'image_href' => $settings['images_url'] . ($is_online ? '/useron.gif' : '/useroff.gif'),
				'label' => &$txt[$is_online ? 'online4' : 'online5']
			),
			'email' => (!empty($modSettings['guest_hideContacts']) && $user_info['is_guest']) || ($row['hideEmail'] && !allowedTo('moderate_forum') && !empty($modSettings['allow_hideEmail'])) ? '' : '<a href="mailto:' . $row['emailAddress'] . '"><img src="' . $settings['images_url'] . '/email_sm.gif" alt="' . $txt[69] . '" title="' . $txt[69] . ' ' . $row['realName'] . '" border="0" /></a>',
			'website' => array(
				'title' => !isset($row['websiteTitle']) || $row['websiteTitle'] == '' ? $txt[96] : $row['websiteTitle'],
				'href' => !isset($row['websiteUrl']) || $row['websiteUrl'] == '' ? '' : $row['websiteUrl'],
			),
			'icq' => array(
				'name' => $row['ICQ'],
				'href' => !empty($row['ICQ']) ? 'http://web.icq.com/whitepages/about_me/1,,,00.html?Uin=' . $row['ICQ'] : '',
				'link' => !empty($row['ICQ']) ? '<a href="http://web.icq.com/whitepages/about_me/1,,,00.html?Uin=' . $row['ICQ'] . '" target="_blank"><img src="http://web.icq.com/whitepages/online?icq=' . $row['ICQ'] . '&amp;img=5" alt="' . $row['ICQ'] . '" border="0" /></a>' : ''
			),
			'aim' => array(
				'name' => $row['AIM'],
				'href' => !empty($row['AIM']) ? 'aim:goim?screenname=' . $row['AIM'] . '&amp;message=' . $txt['aim_default_message'] : '',
				'link' => !empty($row['AIM']) ? '<a href="aim:goim?screenname=' . $row['AIM'] . '&amp;message=' . $txt['aim_default_message'] . '" target="_blank"><img src="' . $settings['images_url'] . '/aim.gif" alt="' . $row['AIM'] . '" border="0" /></a>' : ''
			),
			'msn' => array(
				'name' => $row['MSN'],
				'href' => !empty($row['MSN']) ? 'http://members.msn.com/' . $row['MSN'] : '',
				'link' => !empty($row['MSN']) ? '<a href="http://members.msn.com/' . $row['MSN'] . '" target="_blank"><img src="' . $settings['images_url'] . '/msntalk.gif" alt="' . $row['MSN'] . '" border="0" /></a>' : ''
			),
			'yim' => array(
				'name' => $row['YIM'],
				'href' => !empty($row['YIM']) ? 'http://opi.yahoo.com/online?u=' . $row['YIM'] . '&amp;m=g&amp;t=0' : '',
				'link' => !empty($row['YIM']) ? '<a href="http://opi.yahoo.com/online?u=' . $row['YIM'] . '&amp;m=g&amp;t=0" target="_blank"><img src="http://opi.yahoo.com/online?u=' . $row['YIM'] . '&amp;m=g&amp;t=0" alt="' . $row['YIM'] . '" border="0" /></a>' : ''
			),
			'group' => $row['groupName'],
			'registered' => strftime('%d %b %Y ' . (strpos($user_info['time_format'], '%H') !== false ? '%I:%M:%S %p' : '%T'), $row['dateRegistered'] + ($user_info['time_offset'] + $modSettings['time_offset']) * 3600),
			'posts' => $row['posts'] > 100000 ? $txt[683] : ($row['posts'] == 1337 ? 'leet' : $row['posts']),
			'post_percent' => round(($row['posts'] * 100) / $MOST_POSTS)
		);
		$context['members'][$row['ID_MEMBER']]['website']['link'] = $row['websiteUrl'] != '' ? '<a href="' . $row['websiteUrl'] . '" target="_blank"><img src="' . $settings['images_url'] . '/www.gif" alt="' . htmlspecialchars($context['members'][$row['ID_MEMBER']]['website']['title']) . '" title="' . htmlspecialchars($context['members'][$row['ID_MEMBER']]['website']['title']) . '" border="0" /></a>' : '';
	}
}

?>