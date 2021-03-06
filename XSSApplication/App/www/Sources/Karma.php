<?php
/******************************************************************************
* Karma.php                                                                   *
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

/*	This file contains one humble function, which applauds or smites a user.

	void ModifyKarma()
		- gives or takes karma from a user.
		- redirects back to the referrer afterward, whether by javascript or
		  the passed parameters.
		- requires the karma_edit permission, and that the user isn't a guest.
		- depends on the karmaMode, karmaWaitTime, and karmaTimeRestrictAdmins
		  settings.
		- is accessed via ?action=modifykarma.
*/

// Modify a user's karma.
function ModifyKarma()
{
	global $modSettings, $db_prefix, $txt, $ID_MEMBER, $user_info, $topic;

	// If the mod is disabled, show an error.
	if (empty($modSettings['karmaMode']))
		fatal_lang_error('smf63');

	// If you're a guest or can't do this, blow you off...
	is_not_guest();
	isAllowedTo('karma_edit');

	// If you don't have enough posts, tough luck.
	if ($user_info['posts'] < $modSettings['karmaMinPosts'])
		fatal_error($txt['smf60'] . $modSettings['karmaMinPosts'] . '.');

	// And you can't modify your own, punk! (use the profile if you need to.)
	if (empty($_REQUEST['uid']) || (int) $_REQUEST['uid'] == $ID_MEMBER)
		fatal_lang_error('smf61', false);

	// The user ID _must_ be a number, no matter what.
	$_REQUEST['uid'] = (int) $_REQUEST['uid'];

	// Applauding or smiting?
	$dir = $_REQUEST['sa'] != 'applaud' ? -1 : 1;

	// Delete any older items from the log. (karmaWaitTime is by hour.)
	db_query("
		DELETE FROM {$db_prefix}log_karma
		WHERE " . time() . " - logTime > " . (int) ($modSettings['karmaWaitTime'] * 3600), __FILE__, __LINE__);

	// Start off with no change in karma.
	$action = 0;

	// Not an administrator... or one who is restricted as well.
	if (!empty($modSettings['karmaTimeRestrictAdmins']) || !allowedTo('moderate_forum'))
	{
		// Find out if this user has done this recently...
		$request = db_query("
			SELECT action
			FROM {$db_prefix}log_karma
			WHERE ID_TARGET = $_REQUEST[uid]
				AND ID_EXECUTOR = $ID_MEMBER
			LIMIT 1", __FILE__, __LINE__);
		if (mysql_num_rows($request) > 0)
			list ($action) = mysql_fetch_row($request);
		mysql_free_result($request);
	}

	// They haven't, not before now, anyhow.
	if (empty($action))
	{
		// Put it in the log.
		db_query("
			REPLACE INTO {$db_prefix}log_karma
				(action, ID_TARGET, ID_EXECUTOR, logTime)
			VALUES ($dir, $_REQUEST[uid], $ID_MEMBER, " . time() . ')', __FILE__, __LINE__);

		// Change by one.
		updateMemberData($_REQUEST['uid'], array($dir == 1 ? 'karmaGood' : 'karmaBad' => '+'));
	}
	else
	{
		// If you are gonna try to repeat.... don't allow it.
		if ($action == $dir)
			fatal_error($txt['smf62'] . ' ' . $modSettings['karmaWaitTime'] . ' ' . $txt[578] . '.', false);

		// You decided to go back on your previous choice?
		db_query("
			UPDATE {$db_prefix}log_karma
			SET action = $dir, logTime = " . time() . "
			WHERE ID_TARGET = $_REQUEST[uid]
				AND ID_EXECUTOR = $ID_MEMBER
			LIMIT 1", __FILE__, __LINE__);

		// It was recently changed the OTHER way... so... reverse it!
		if ($dir == 1)
			updateMemberData($_REQUEST['uid'], array('karmaGood' => '+', 'karmaBad' => '-'));
		else
			updateMemberData($_REQUEST['uid'], array('karmaBad' => '+', 'karmaGood' => '-'));
	}

	// Figure out where to go back to.... the topic?
	if (isset($topic))
		redirectexit('topic=' . $topic . '.' . $_REQUEST['start'] . '#msg' . $_REQUEST['m']);
	// Hrm... maybe a personal message?
	elseif (isset($_REQUEST['f']))
		redirectexit('action=pm;f=' . $_REQUEST['f'] . ';start=' . $_REQUEST['start']);
	// JavaScript as a last resort.
	else
	{
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>...</title>
		<script language="JavaScript" type="text/javascript"><!--
			history.go(-1);
		// --></script>
	</head>
	<body>&laquo;</body>
</html>';

		obExit(false);
	}
}

// What's this?  I dunno, what are you talking about?  Never seen this before, nope.  No siree.
function BookOfUnknown()
{
	global $context;

	if (strpos($_GET['action'], 'mozilla') !== false && !$context['browser']['is_gecko'])
		redirectexit('http://www.getfirefox.com/', false);
	elseif (strpos($_GET['action'], 'mozilla') !== false)
		redirectexit('about:mozilla', false);

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>The Book of Unknown, 2:18</title>
		<style type="text/css">
			em
			{
				font-size: 1.3em;
				line-height: 0;
			}
		</style>
	</head>
	<body style="background-color: #444455; color: white; font-style: italic; font-family: serif;">
		<div style="margin-top: 12%; font-size: 1.1em; line-height: 1.4; text-align: center;">
			Woe, it was that his name wasn\'t <em>known</em>, that he came in mystery, and was recognized by none.&nbsp;And it became to be in those days <em>something</em>.&nbsp; Something not yet <em id="unknown" name="[Unknown]">unknown</em> to mankind.&nbsp; And thus what was to be known the <em>secret project</em> began into its existence.&nbsp; Henceforth the opposition was only <em>weary</em> and <em>fearful</em>, for now their match was at arms against them.
		</div>
		<div style="margin-top: 2ex; font-size: 2em; text-align: right;">
			from <span style="font-family: Georgia, serif;"><strong><a href="http://www.unknownbrackets.com/about:unknown" style="color: white; text-decoration: none; cursor: text;">The Book of Unknown</a></strong>, 2:18</span>
		</div>
	</body>
</html>';

	obExit(false);
}

?>