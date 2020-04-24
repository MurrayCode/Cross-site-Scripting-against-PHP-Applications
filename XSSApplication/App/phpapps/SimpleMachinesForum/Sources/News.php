<?php
/******************************************************************************
* News.php                                                                    *
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

/*	This file contains the files necessary to display news as an XML feed.

	void ShowXmlFeed()
		- is called to output xml information.
		- can be passed four subactions which decide what is output: 'recent'
		  for recent posts, 'news' for news topics, 'members' for recently
		  registered members, and 'profile' for a member's profile.
		- To display a member's profile, a user id has to be given. (;u=1)
		- uses the Stats language file.
		- outputs an rss feed instead of a proprietary one if the 'type' get
		  parameter is 'rss' or 'rss2'.
		- does not use any templates, sub templates, or template layers.
		- is accessed via ?action=.xml.

	void dumpTags(array data, int indentation, string tag = use_array)
		- formats data retrieved in other functions into xml format.
		- the data parameter is the array to output as xml data.
		- indentation is the amount of indentation to use.
		- if a tag is specified, it will be used instead of the keys of data.
		- this function is recursively called to handle sub arrays of data.

	array getXmlMembers(bool rss)
		- is called to retrieve list of members from database.
		- if rss is true, the array will be done in the RSS standard.
		- returns array of data.

	array getXmlNews(bool rss)
		- is called to retrieve news topics from database.
		- takes one argument, rss, to decide if data should be RSS standard.
		- returns array of topics.

	array getXmlRecent(bool rss)
		- is called to retrieve list of recent topics.
		- takes one argument, rss, to decide if data should be RSS standard.
		- returns an array of recent posts.

	array getXmlProfile(bool rss)
		- is called to retrieve profile information for member into array.
		- takes one argument, rss, to decide if data should be RSS standard.
		- returns an array of data.
*/

// Show an xml file representing recent information or a profile.
function ShowXmlFeed()
{
	global $context, $scripturl, $txt, $modSettings;

	// If it's not enabled, die.
	if (empty($modSettings['xmlnews_enable']))
		obExit(false);

	loadLanguage('Stats');

	// Default to latest 5.  No more than 255, please.
	$_GET['limit'] = empty($_GET['limit']) ? 5 : min((int) $_GET['limit'], 255);

	// Show in rss or proprietary format?
	$xml_format = isset($_GET['type']) && in_array($_GET['type'], array('smf', 'rss', 'rss2')) ? $_GET['type'] : 'smf';

	// List all the different types of data they can pull.
	$subActions = array(
		'recent' => array('getXmlRecent', 'recent-post'),
		'news' => array('getXmlNews', 'article'),
		'members' => array('getXmlMembers', 'member'),
		'profile' => array('getXmlProfile', null),
	);
	if (empty($_GET['sa']) || !isset($subActions[$_GET['sa']]))
		$_GET['sa'] = 'recent';

	// Get the associative array representing the xml.
	$xml = $subActions[$_GET['sa']][0]($xml_format != 'smf');

	// This is an xml file....
	ob_end_clean();
	if (!empty($modSettings['enableCompressedOutput']))
		@ob_start('ob_gzhandler');
	else
		ob_start();
	header('Content-Type: ' . ($xml_format != 'smf' ? 'application/rss+xml' : 'text/xml'));

	// First, output the xml header.
	echo '<?xml version="1.0" encoding="', $context['character_set'], '"?>';

	// Are we outputting an rss feed or one with more information?
	if ($xml_format != 'smf')
	{
		// Start with an RSS 2.0 header.
		echo '
<rss version=', $xml_format == 'rss2' ? '"2.0" xmlns="http://backend.userland.com/rss2"' : '"0.92"', ' xml:lang="', strtr($txt['lang_locale'], '_', '-'), '">
	<channel>
		<title>', strip_tags($context['forum_name']), '</title>
		<link>', $scripturl, '</link>
		<description>', strip_tags($txt['xml_rss_desc']), '</description>';

		// Output all of the associative array, start indenting with 2 tabs, and name everything "item".
		dumpTags($xml, 2, 'item');

		// Output the footer of the xml.
		echo '
	</channel>
</rss>';
	}
	// Otherwise, we're using our proprietary formats - they give more data, though.
	else
	{
		echo '
<smf:xml-feed xmlns:smf="http://www.simplemachines.org/" xmlns="http://www.simplemachines.org/xml/', $_GET['sa'], '" xml:lang="', strtr($txt['lang_locale'], '_', '-'), '">';

		// Dump out that associative array.  Indent properly.... and use the right names for the base elements.
		dumpTags($xml, 1, $subActions[$_GET['sa']][1]);

		echo '
</smf:xml-feed>';
}

	obExit(false);
}

function dumpTags($data, $i, $tag = null)
{
	// For every array...
	foreach ($data as $key => $val)
	{
		// Skip it, it's been set to null.
		if ($val == null)
			continue;

		// If a tag was passed, use it instead of the key.
		$key = isset($tag) ? $tag : $key;

		// First let's indent!
		echo "\n", str_repeat("\t", $i);

		// If it's empty/0/nothing simply output an empty tag.
		if ($val == '')
			echo '<', $key, ' />';
		else
		{
			// Beginning tag.
			echo '<', $key, '>';

			if (is_array($val))
			{
				// An array.  Dump it, and then indent the tag.
				dumpTags($val, $i + 1);
				echo "\n", str_repeat("\t", $i), '</', $key, '>';
			}
			// A string with returns in it.... show this as a multiline element.
			elseif (strpos($val, "\n") !== false || strpos($val, '<br />') !== false)
				echo "\n", $val, "\n", str_repeat("\t", $i), '</', $key, '>';
			// A simple string.
			else
				echo $val, '</', $key, '>';
		}
	}
}

function getXmlMembers($rss)
{
	global $db_prefix, $scripturl;

	// Find the most recent members.
	$request = db_query("
		SELECT ID_MEMBER, memberName, realName, dateRegistered
		FROM {$db_prefix}members
		ORDER BY ID_MEMBER DESC
		LIMIT $_GET[limit]", __FILE__, __LINE__);
	$data = array();
	while ($row = mysql_fetch_assoc($request))
	{
		// Make the data look rss-ish.
		if ($rss)
			$data['item'] = array(
				'title' => '<![CDATA[' . $row['realName'] . ']]>',
				'link' => $scripturl . '?action=profile;u=' . $row['ID_MEMBER'],
				'comments' => $scripturl . '?action=pm;sa=send;u=' . $row['ID_MEMBER'],
				'pubDate' => gmdate('D, d M Y H:i:s T', $row['dateRegistered']),
				'guid' => $scripturl . '?action=profile;u=' . $row['ID_MEMBER'],
			);
		// More logical format for the data, but harder to apply.
		else
			$data[] = array(
				'name' => '<![CDATA[' . $row['realName'] . ']]>',
				'time' => strip_tags(timeformat($row['dateRegistered'])),
				'id' => $row['ID_MEMBER'],
				'link' => $scripturl . '?action=profile;u=' . $row['ID_MEMBER']
			);
	}
	mysql_free_result($request);

	return $data;
}

function getXmlNews($rss)
{
	global $db_prefix, $user_info, $scripturl, $modSettings, $board;

	/* Find the latest posts that:
		- are the first post in their topic.
		- are on an any board OR in a specified board.
		- can be seen by this user.
		- are actually the latest posts. */
	$request = db_query("
		SELECT
			m.smileysEnabled, m.posterTime, m.ID_MSG, m.subject, m.body, t.ID_TOPIC, t.ID_BOARD,
			b.name AS bname, t.numReplies, m.ID_MEMBER, IFNULL(mem.realName, m.posterName) AS posterName,
			mem.hideEmail, IFNULL(mem.emailAddress, m.posterEmail) AS posterEmail
		FROM {$db_prefix}topics AS t, {$db_prefix}messages AS m, {$db_prefix}boards AS b
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = m.ID_MEMBER)
		WHERE b.ID_BOARD = " . (empty($board) ? 't.ID_BOARD' : "$board
			AND t.ID_BOARD = $board") . "
			AND m.ID_MSG = t.ID_FIRST_MSG
			AND $user_info[query_see_board]
		ORDER BY m.ID_MSG DESC
		LIMIT $_GET[limit]", __FILE__, __LINE__);
	$data = array();
	while ($row = mysql_fetch_assoc($request))
	{
		// Limit the length of the message, if the option is set.
		if (!empty($modSettings['xmlnews_maxlen']) && strlen(str_replace('<br />', "\n", $row['body'])) > $modSettings['xmlnews_maxlen'])
			$row['body'] = strtr(substr(str_replace('<br />', "\n", $row['body']), 0, $modSettings['xmlnews_maxlen'] - 3), array("\n" => '<br />')) . '...';

		$row['body'] = doUBBC($row['body'], $row['smileysEnabled']);

		censorText($row['body']);
		censorText($row['subject']);

		// Being news, this actually makes sense in rss format.
		if ($rss)
			$data[] = array(
				'title' => '<![CDATA[' . $row['subject'] . ']]>',
				'link' => $scripturl . '?topic=' . $row['ID_TOPIC'] . '.0',
				'description' => '<![CDATA[' . $row['body'] . ']]>',
				'author' => empty($row['hideEmail']) ? $row['posterEmail'] : null,
				'category' => '<![CDATA[' . $row['bname'] . ']]>',
				'comments' => $scripturl . '?action=post;topic=' . $row['ID_TOPIC'] . '.0',
				'pubDate' => gmdate('D, d M Y H:i:s T', $row['posterTime']),
				'guid' => $scripturl . '?topic=' . $row['ID_TOPIC'] . '.msg' . $row['ID_MSG'] . '#msg' . $row['ID_MSG']
			);
		// The biggest difference here is more information.
		else
			$data[] = array(
				'time' => strip_tags(timeformat($row['posterTime'])),
				'id' => $row['ID_MSG'],
				'subject' => '<![CDATA[' . $row['subject'] . ']]>',
				'body' => '<![CDATA[' . $row['body'] . ']]>',
				'poster' => array(
					'name' => '<![CDATA[' . $row['posterName'] . ']]>',
					'id' => $row['ID_MEMBER'],
					'link' => !empty($row['ID_MEMBER']) ? $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] : ''
				),
				'topic' => $row['ID_TOPIC'],
				'board' => array(
					'name' => '<![CDATA[' . $row['bname'] . ']]>',
					'id' => $row['ID_BOARD'],
					'link' => $scripturl . '?board=' . $row['ID_BOARD'] . '.0'
				),
				'link' => $scripturl . '?topic=' . $row['ID_TOPIC'] . '.0'
			);
	}
	mysql_free_result($request);

	return $data;
}

function getXmlRecent($rss)
{
	global $db_prefix, $user_info, $scripturl, $modSettings, $board;

	$request = db_query("
		SELECT m.ID_MSG
		FROM {$db_prefix}messages AS m, {$db_prefix}boards AS b
		WHERE m.ID_MSG >= " . ($modSettings['maxMsgID'] - 4 * $_GET['limit']) . "
			AND m.ID_BOARD = " . (empty($board) ? "b.ID_BOARD" : "$board
			AND b.ID_BOARD = $board") . "
			AND $user_info[query_see_board]
		ORDER BY m.ID_MSG DESC
		LIMIT $_GET[limit]", __FILE__, __LINE__);
	$messages = array();
	while ($row = mysql_fetch_assoc($request))
		$messages[] = $row['ID_MSG'];
	mysql_free_result($request);

	if (empty($messages))
		return array();

	// Find the most recent posts this user can see.
	$request = db_query("
		SELECT
			m.smileysEnabled, m.posterTime, m.ID_MSG, m.subject, m.body, m.ID_TOPIC, t.ID_BOARD,
			b.name AS bname, t.numReplies, m.ID_MEMBER, mf.ID_MEMBER AS ID_FIRST_MEMBER,
			IFNULL(mem.realName, m.posterName) AS posterName, mf.subject AS firstSubject,
			IFNULL(memf.realName, mf.posterName) AS firstPosterName, mem.hideEmail,
			IFNULL(mem.emailAddress, m.posterEmail) AS posterEmail
		FROM {$db_prefix}messages AS m, {$db_prefix}messages AS mf, {$db_prefix}topics AS t, {$db_prefix}boards AS b
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = m.ID_MEMBER)
			LEFT JOIN {$db_prefix}members AS memf ON (memf.ID_MEMBER = mf.ID_MEMBER)
		WHERE t.ID_TOPIC = m.ID_TOPIC
			AND b.ID_BOARD = " . (empty($board) ? 't.ID_BOARD' : "$board
			AND t.ID_BOARD = $board") . "
			AND mf.ID_MSG = t.ID_FIRST_MSG
			AND m.ID_MSG IN (" . implode(', ', $messages) . ")
		ORDER BY m.ID_MSG DESC
		LIMIT $_GET[limit]", __FILE__, __LINE__);
	$data = array();
	while ($row = mysql_fetch_assoc($request))
	{
		// Limit the length of the message, if the option is set.
		if (!empty($modSettings['xmlnews_maxlen']) && strlen(str_replace('<br />', "\n", $row['body'])) > $modSettings['xmlnews_maxlen'])
			$row['body'] = strtr(substr(str_replace('<br />', "\n", $row['body']), 0, $modSettings['xmlnews_maxlen'] - 3), array("\n" => '<br />')) . '...';

		$row['body'] = doUBBC($row['body'], $row['smileysEnabled']);

		censorText($row['body']);
		censorText($row['subject']);

		// Doesn't work as well as news, but it kinda does..
		if ($rss)
			$data[] = array(
				'title' => '<![CDATA[' . $row['subject'] . ']]>',
				'link' => $scripturl . '?topic=' . $row['ID_TOPIC'] . '.' . $row['numReplies'] . '#msg' . $row['ID_MSG'],
				'description' => "<![CDATA[\n" . $row['body'] . ']]>',
				'author' => empty($row['hideEmail']) ? $row['posterEmail'] : null,
				'category' => '<![CDATA[' . $row['bname'] . ']]>',
				'comments' => $scripturl . '?action=post;topic=' . $row['ID_TOPIC'] . '.0',
				'pubDate' => gmdate('D, d M Y H:i:s T', $row['posterTime']),
				'guid' => $scripturl . '?topic=' . $row['ID_TOPIC'] . '.msg' . $row['ID_MSG'] . '#msg' . $row['ID_MSG']
			);
		// A lot of information here.  Should be enough to please the rss-ers.
		else
			$data[] = array(
				'time' => strip_tags(timeformat($row['posterTime'])),
				'id' => $row['ID_MSG'],
				'subject' => '<![CDATA[' . $row['subject'] . ']]>',
				'body' => '<![CDATA[' . $row['body'] . ']]>',
				'starter' => array(
					'name' => '<![CDATA[' . $row['firstPosterName'] . ']]>',
					'id' => $row['ID_FIRST_MEMBER'],
					'link' => !empty($row['ID_FIRST_MEMBER']) ? $scripturl . '?action=profile;u=' . $row['ID_FIRST_MEMBER'] : ''
				),
				'poster' => array(
					'name' => '<![CDATA[' . $row['posterName'] . ']]>',
					'id' => $row['ID_MEMBER'],
					'link' => !empty($row['ID_MEMBER']) ? $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] : ''
				),
				'topic' => array(
					'subject' => '<![CDATA[' . $row['firstSubject'] . ']]>',
					'id' => $row['ID_TOPIC'],
					'link' => $scripturl . '?topic=' . $row['ID_TOPIC'] . '.new#new'
				),
				'board' => array(
					'name' => '<![CDATA[' . $row['bname'] . ']]>',
					'id' => $row['ID_BOARD'],
					'link' => $scripturl . '?board=' . $row['ID_BOARD'] . '.0'
				),
				'link' => $scripturl . '?topic=' . $row['ID_TOPIC'] . '.' . $row['numReplies'] . '#msg' . $row['ID_MSG']
			);
	}
	mysql_free_result($request);

	return $data;
}

function getXmlProfile($rss)
{
	global $scripturl, $themeUser, $user_profile, $modSettings;

	// You must input a valid user....
	if (empty($_GET['u']) || loadMemberData((int) $_GET['u']) === false)
		return array();

	// Make sure the id is a number and not "I like trying to hack the database".
	$_GET['u'] = (int) $_GET['u'];

	// Load the member's contextual information!
	if (!loadMemberContext($_GET['u']))
		return array();

	// Okay, I admit it, I'm lazy.  Stupid $_GET['u'] is long and hard to type.
	$profile = &$themeUser[$_GET['u']];

	if ($rss)
		$data = array(array(
			'title' => '<![CDATA[' . $profile['name'] . ']]>',
			'link' => $scripturl  . '?action=profile;u=' . $profile['id'],
			'description' => '<![CDATA[' . (isset($profile['group']) ? $profile['group'] : $profile['post_group']) . ']]>',
			'comments' => $scripturl . '?action=pm;sa=send;u=' . $profile['id'],
			'pubDate' => gmdate('D, d M Y H:i:s T', $user_profile[$profile['id']]['dateRegistered']),
			'guid' => $scripturl  . '?action=profile;u=' . $profile['id'],
		));
	else
	{
		$data = array(
			'username' => '<![CDATA[' . $profile['username'] . ']]>',
			'name' => '<![CDATA[' . $profile['name'] . ']]>',
			'link' => $scripturl  . '?action=profile;u=' . $profile['id'],
			'posts' => $profile['posts'],
			'post-group' => '<![CDATA[' . $profile['post_group'] . ']]>',
			'language' => '<![CDATA[' . $profile['language'] . ']]>',
			'last-login' => gmdate('D, d M Y H:i:s T', $user_profile[$profile['id']]['lastLogin']),
			'registered' => gmdate('D, d M Y H:i:s T', $user_profile[$profile['id']]['dateRegistered'])
		);

		// Everything below here might not be set, and thus maybe shouldn't be displayed.
		if ($profile['gender']['name'] != '')
			$data['gender'] = '<![CDATA[' . $profile['gender']['name'] . ']]>';

		if ($profile['avatar']['name'] != '')
			$data['avatar'] = $profile['avatar']['url'];

		// If they are online, show an empty tag... no reason to put anything inside it.
		if ($profile['online']['is_online'])
			$data['online'] = '';

		if ($profile['signature'] != '')
			$data['signature'] = '<![CDATA[' . $profile['signature'] . ']]>';
		if ($profile['blurb'] != '')
			$data['blurb'] = '<![CDATA[' . $profile['blurb'] . ']]>';
		if ($profile['location'] != '')
			$data['location'] = '<![CDATA[' . $profile['location'] . ']]>';
		if ($profile['title'] != '')
			$data['title'] = '<![CDATA[' . $profile['title'] . ']]>';

		if (!empty($profile['icq']['name']))
			$data['icq'] = $profile['icq']['name'];
		if ($profile['aim']['name'] != '')
			$data['aim'] = $profile['aim']['name'];
		if ($profile['msn']['name'] != '')
			$data['msn'] = $profile['msn']['name'];
		if ($profile['yim']['name'] != '')
			$data['yim'] = $profile['yim']['name'];

		if ($profile['website']['title'] != '')
			$data['website'] = array(
				'title' => '<![CDATA[' . $profile['website']['title'] . ']]>',
				'link' => $profile['website']['url']
			);

		if ($profile['group'] != '')
			$data['postition'] = '<![CDATA[' . $profile['group'] . ']]>';

		if (!empty($modSettings['karmaMode']))
			$data['karma'] = array(
				'good' => $profile['karma']['good'],
				'bad' => $profile['karma']['bad']
			);

		if (empty($profile['hide_email']) || empty($modSettings['allow_hideEmail']))
			$data['email'] = $profile['email'];

		if (!empty($profile['birth_date']) && substr($profile['birth_date'], 0, 4) != '0000')
		{
			list ($birth_year, $birth_month, $birth_day) = sscanf($profile['birth_date'], '%d-%d-%d');
			$datearray = getdate(forum_time());
			$data['age'] = $datearray['year'] - $birth_year - (($datearray['mon'] > $birth_month || ($datearray['mon'] == $birth_month && $datearray['mday'] >= $birth_day)) ? 0 : 1);
		}
	}

	// Save some memory.
	unset($profile);
	unset($themeUser[$_GET['u']]);

	return $data;
}

?>