<?php
/******************************************************************************
* Printpage.php                                                               *
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

/*	This file contains just one function that formats a topic to be printer
	friendly.

	void PrintTopic()
		- is called to format a topic to be printer friendly.
		- must be called with a topic specified.
		- uses the Printpage (main sub template.) template.
		- uses the print_above/print_below later without the main layer.
		- is accessed via ?action=printpage.
*/

function PrintTopic()
{
	global $db_prefix, $topic, $txt, $scripturl, $context;
	global $board_info;

	if (empty($topic))
		fatal_lang_error(472, false);

	// Get the topic starter information.
	$request = db_query("
		SELECT m.posterTime, IFNULL(mem.realName, m.posterName) AS posterName
		FROM {$db_prefix}messages AS m
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = m.ID_MEMBER)
		WHERE m.ID_TOPIC = $topic
		ORDER BY ID_MSG
		LIMIT 1", __FILE__, __LINE__);
	if (mysql_num_rows($request) == 0)
		fatal_lang_error('smf232');
	$row = mysql_fetch_assoc($request);
	mysql_free_result($request);

	// Lets "output" all that info.
	loadTemplate('Printpage');
	$context['template_layers'] = array('print');
	$context['board_name'] = $board_info['name'];
	$context['category_name'] = $board_info['cat']['name'];
	$context['poster_name'] = $row['posterName'];
	$context['post_time'] = timeformat($row['posterTime'], false);

	// Split the topics up so we can print them.
	$request = db_query("
		SELECT subject, posterTime, body, IFNULL(mem.realName, posterName) AS posterName
		FROM {$db_prefix}messages AS m
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = m.ID_MEMBER)
		WHERE ID_TOPIC = $topic
		ORDER BY ID_MSG", __FILE__, __LINE__);
	$context['posts'] = array();
	while ($row = mysql_fetch_assoc($request))
	{
		// Do all the "direct" replaces first.
		$row['body'] = strtr($row['body'], array(
			// [code]
			'[code]<br />' => '<div class="codeheader">' . $txt['smf238'] . ':</div><div class="code">',
			'[/code]<br />' => '</div>',
			'[code]' => '<div class="codeheader">' . $txt['smf238'] . ':</div><div class="code">',
			'[/code]' => '</div>',
			// [php]
			'[php]<br />' => '',
			'[/php]<br />' => '',
			'[php]' => '',
			'[/php]' => '',
			// [b], [i], [u], [s]
			'[b]' => '<b>',
			'[/b]' => '</b>',
			'[i]' => '<i>',
			'[/i]' => '</i>',
			'[u]' => '<span style="text-decoration: underline;">',
			'[/u]' => '</span>',
			'[s]' => '<s>',
			'[/s]' => '</s>',
			// [move] can't really be printed.
			'[move]' => '',
			'[/move]' => '',
			// Colors can't well be displayed... supposed to be black and white.
			'[black]' => '',
			'[/black]' => '',
			'[white]' => '',
			'[/white]' => '',
			'[red]' => '',
			'[/red]' => '',
			'[green]' => '',
			'[/green]' => '',
			'[blue]' => '',
			'[/blue]' => '',
			// Aligning text passes..
			'[left]' => '<div align="left">',
			'[/left]' => '</div>',
			'[right]' => '<div align="right">',
			'[/right]' => '</div>',
			'[center]' => '<div align="center">',
			'[/center]' => '</div>',
			// Some of the more basic textual 'effects' are fine as well.
			'[tt]' => '<tt>',
			'[/tt]' => '</tt>',
			'[sub]' => '<sub>',
			'[/sub]' => '</sub>',
			'[sup]' => '<sup>',
			'[/sup]' => '</sup>',
			// Horizontal rules and breaks..
			'[hr]' => '<hr width="40%" align="left" size="1" />',
			'[hr /]' => '<hr width="40%" align="left" size="1" />',
			'[hr/]' => '<hr width="40%" align="left" size="1" />',
			'[br]' => '<br />',
			'[br /]' => '<br />',
			'[br/]' => '<br />',
			// Links are useless on paper... just show the link.
			'[ftp]' => '',
			'[/ftp]' => '',
			// Preformatted is arguably the best.
			'[pre]' => '<pre>',
			'[/pre]' => '</pre>',
			// [list], [*], [li], etc.
			'[list]' => '<ul>',
			'[/list]' => '</ul>',
			'[*]' => '<li>',
			'[@]' => '<li>',
			'[+]' => '<li>',
			'[x]' => '<li>',
			'[#]' => '<li>',
			'[o]' => '<li>',
			'[O]' => '<li>',
			'[0]' => '<li>',
			'[li]' => '<li>',
			'[/li]' => '</li>',
		));

		// [glow] and [shadow] can't be printed either.
		$row['body'] = preg_replace(array('/\[glow(.+?)\](.+?)\[\/glow\]/is', '/\[shadow(.+?)\](.+?)\[\/shadow\]/is'), '$2', $row['body']);

		// Removing colors is good too.
		$row['body'] = preg_replace('~\[color=(#[\da-fA-F]{3}|#[\da-fA-F]{6}|[\w]{1,12})\](.*?)\[/color\]~i', '$2', $row['body']);

		// [font] and [size] are okay..
		$row['body'] = preg_replace('~\[font=([\w,\-\s]+?)\](.+?)\[/font\]~i', '<span style="font-family: $1;">$2</span>', $row['body']);
		$row['body'] = preg_replace('~\[size=([\d]{1,2}p[xt]|(?:x-)?small(?:er)?|(?:x-)?large[r]?)\](.+?)\[/size\]~i', '<span style="font-size: $1;">$2</span>', $row['body']);
		$row['body'] = preg_replace('~\[size=([\d])\](.+?)\[/size\]~i', '<font size="$1">$2</font>', $row['body']);

		// Images are not printed - unless the user specifically requests it.
		if (isset($_GET['images']))
			$row['body'] = preg_replace('~\[img(\s+width=([\d]+))?(\s+height=([\d]+))?\s*\](?:<br />)*(.+?)(?:<br />)*\[/img\]~ei', '\'<img src="\' . preg_replace(\'~action(=|%3d)(?!dlattach)~i\', \'action-\', \'$5\') . \'" alt=""\' . (\'$2\' != \'\' ? \' width="$2"\' : \'\') . (\'$4\' != \'\' ? \' height="$4"\' : \'\') . \' border="0" />\'', $row['body']);
		else
			$row['body'] = preg_replace(array('~\[img=(.*?)\](.+?)\[/img\]~', '~\[img\](.+?)\[/img\]~'), '($1)', $row['body']);

		// URLs and emails just get parenthesized.
		$row['body'] = preg_replace(array('/\[url=(.+?)\]([^\]]+)\s*\[\/url\]/', '/\[email=(.*)\](.*)\[\/email\]/'), '$2 ($1)', $row['body']);
		$row['body'] = preg_replace(array('/\[url\](.+)\[\/url\]/', '/\[email\](.*)\[\/email\]/'), '$1', $row['body']);

		// Quotes are okay.  Important, actually.
		$row['body'] = preg_replace(
			array(
				'/(?:<br \/>)?\[quote(?: author)?=&quot;(.+?)&quot;\](?:<br \/>)*/i',
				'/(?:<br \/>)?\[quote author=(.{1,80}?) link=(.+?) date=(.+?)\](?:<br \/>)*/ei',
				'/(?:<br \/>)?\[quote author=(.{1,80}?)\](?:<br \/>)*/i',
				'/(?:<br \/>)?\[quote\](?:<br \/>)*/i',
				'/\[\/quote\]/i'
			),
			array(
				'<div class="quoteheader">' . $txt['smf239'] . ': $1</div><div class="quote">',
				"'<div class=\"quoteheader\">' . \$txt['smf239'] . ': \$1 ' . \$txt[176] . ' ' . timeformat('\$3', false) . '</div><div class=\"quote\">'",
				'<div class="quoteheader">' . $txt['smf239'] . ': $1</div><div class="quote">',
				'<div class="smalltext">' . $txt['smf240'] . ':</div><div class="quote">',
				'</div>'
			),
			$row['body']
		);

		// [me=...]say something[/me]
		$row['body'] = preg_replace(array('/\[me=&quot;(.+?)&quot;\]/', '/\[me=([^\]]+)\]/', '/\[\/me\]/is'), array('* $1 ', '* $1 ', ''), $row['body']);

		// Oh sure, let's print some flash.
		$row['body'] = preg_replace('/\[flash=(.*)\](.*)\[\/flash\]/', '$2', $row['body']);

		// [table], [tr], [td]... they go together.
		$row['body'] = preg_replace('/\[table\]/', '<table>', $row['body']);
		$row['body'] = str_replace('[/table]', '</table>', $row['body']);
		$row['body'] = str_replace(array('[tr]', '[td]'), array('<tr>', '<td>'), $row['body']);
		$row['body'] = str_replace(array('[/tr]', '[/td]'), array('</tr>', '</td>'), $row['body']);

		// Censor the subject and message.
		censorText($row['subject']);
		censorText($row['body']);

		$context['posts'][] = array(
			'subject' => $row['subject'],
			'member' => $row['posterName'],
			'time' =>  timeformat($row['posterTime'], false),
			'timestamp' => $row['posterTime'],
			'body' => $row['body']
		);

		if (!isset($context['topic_subject']))
			$context['topic_subject'] = $row['subject'];
	}
	mysql_free_result($request);
}

?>