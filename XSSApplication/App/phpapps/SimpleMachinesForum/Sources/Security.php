<?php
/******************************************************************************
* Security.php                                                                *
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

/*	This file has the very important job of insuring forum security.  This
	task includes banning and permissions, namely.  It does this by providing
	the following functions:

	void is_admin()
		- checks if the current user is an administrator.  If he or she is
		  not, it immediately ends execution with an error message.
		- takes care of verifying the administrator's password.
		- should be used to restrict access, not to check access.

	void validateSession()
		- makes sure the user is who they claim to be by requiring a
		  password to be typed in every hour.
		- is turned on and off by the securityDisable setting.
		- uses the adminLogin() function of Subs-Auth.php if they need to
		  login, which saves all request (post and get) data.

	void is_not_guest(string message = '')
		- checks if the user is currently a guest, and if so asks them to
		  login with a message telling them why.
		- message is what to tell them when asking them to login.

	void is_not_banned()
		- checks if the user is banned, and if so dies with an error.
		- caches this information for optimization purposes.

	void banPermissions()
		- applies any states of banning by removing permissions the user
		  cannot have.

	bool isReservedName(string name, int ID_MEMBER = 0, bool is_name = true)
		- checks if name is a reserved name or username.
		- if is_name is false, the name is assumed to be a username.
		- the ID_MEMBER variable is used to ignore duplicate matches with the
		  current member.

	string checkSession(string type = 'post', string from_action = none,
			is_fatal = true)
		- checks the current session, verifying that the person is who he or
		  she should be.
		- also checks the referrer to make sure they didn't get sent here.
		- depends on the disableCheckUA setting, which is usually missing.
		- will check GET, POST, or REQUEST depending on the passed type.
		- also optionally checks the referring action if passed. (note that
		  the referring action must be by GET.)
		- returns the error message if is_fatal is false.

	bool checkSubmitOnce(string action, bool is_fatal = true)
		- registers a sequence number for a form.
		- checks whether a submitted sequence number is registered in the
		  current session.
		- depending on the value of is_fatal shows an error or returns true or
		  false.
		- frees a sequence number from the stack after it's been checked.
		- frees a sequence number without checking if action == 'free'.

	void setBit(string &string, int position, int value)
		- store a bit in a string at the given position fo string.

	bool getBit(string &string, int position)
		- read the bit stored in the string at a given position.
		- return true/false depending on the value of the bit.

	bool allowedTo(string permission, array boards = current)
		- checks whether the user is allowed to do permission. (ie. post_new.)
		- if boards is specified, checks those boards instead of the current.
		- always returns true if the user is an administrator.
		- returns true if he or she can do it, false otherwise.

	void isAllowedTo(string permission, array boards = current)
		- uses allowedTo() to check if the user is allowed to do permission.
		- checks the passed boards or current board for the permission.
		- if they are not, it loads the Errors language file and shows an
		  error using $txt['cannot_' . $permission].
		- if they are a guest and cannot do it, this calls is_not_guest().

	array boardsAllowedTo(string permission)
		- returns a list of boards on which the user is allowed to do the
		  specified permission.
		- returns an array with only a 0 in it if the user has permission
		  to do this on every board.
		- returns an empty array if he or she cannot do this on any board.
*/

// Make sure the user is an administrator, otherwise quit.
function is_admin()
{
	global $user_info;

	// Check if this user is an administrator.
	if (!$user_info['is_admin'])
	{
		// Make it so people won't worry about their online logs ;).
		$_GET['action'] = '';
		writeLog(true);

		fatal_lang_error(1);
	}

	// Make sure the administrator has a valid session.
	validateSession();
}

// Check if the user is who he/she sais he is
function validateSession()
{
	global $modSettings, $sourcedir, $user_info;

	// We don't care if the option is off, because Guests should NEVER get past here.
	is_not_guest();

	// Is the security option off?  Or are they already logged in?
	if (!empty($modSettings['securityDisable']) || (!empty($_SESSION['admin_time']) && $_SESSION['admin_time'] + 3600 >= time()))
		return;

	require_once($sourcedir . '/Subs-Auth.php');

	// Posting the password... check it.
	if (isset($_POST['admin_pass']))
	{
		checkSession();

		// Password correct?
		if (md5_hmac($_POST['admin_pass'], strtolower($user_info['username'])) == $user_info['passwd'])
			$_SESSION['admin_time'] = time();
		// That password is wrong, sonny.
		else
			adminLogin();
	}
	// Need to type in a password for that, man.
	else
		adminLogin();
}

// Require a user who is logged in. (not a guest.)
function is_not_guest($message = '')
{
	global $user_info, $sourcedir, $txt, $context;

	// Luckily, this person isn't a guest.
	if (!$user_info['is_guest'])
		return;

	// People always worry when they see people doing things they aren't actually doing...
	$_GET['action'] = '';
	writeLog(true);

	$_SESSION['login_url'] = $_SERVER['REQUEST_URI'];

	// Load the Login template and language file.
	loadTemplate('Login');
	loadLanguage('Login');

	// Use the kick_guest sub template...
	$context['kick_message'] = $message;
	$context['sub_template'] = 'kick_guest';
	$context['page_title'] = $txt[34];

	obExit();
}

// Do banning related stuff.  (ie. disallow access....)
function is_not_banned()
{
	global $txt, $db_prefix, $ID_MEMBER, $modSettings, $context, $user_info;

	// You cannot be banned if you are an admin - doesn't help if you log out.
	if ($user_info['is_admin'])
		return;

	// Only check the ban every so often. (to reduce load.)
	if (!isset($_SESSION['ban']['last_checked']) || ($_SESSION['ban']['last_checked'] < $modSettings['banLastUpdated']) || !isset($_SESSION['ban']['ID_MEMBER']) || $_SESSION['ban']['ID_MEMBER'] != $ID_MEMBER || !isset($_SESSION['ban']['ip']) || $_SESSION['ban']['ip'] != $user_info['ip'])
	{
		// Innocent until proven guilty.  (but we know you are! :P)
		$_SESSION['ban'] = array(
			'full_ban' => array('is_banned' => false),
			'cannot_register' => array('is_banned' => false),
			'cannot_post' => array('is_banned' => false),
			'last_checked' => time(),
			'ID_MEMBER' => $ID_MEMBER,
			'ip' => $user_info['ip'],
		);

		$ban_query = array();

		// Check if we have a valid IP address.
		if (preg_match('/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/', $user_info['ip'], $ip_parts) == 1)
		{
			$ban_query[] = "(ban_type = 'ip_ban'
					AND ($ip_parts[1] BETWEEN ip_low1 AND ip_high1)
					AND ($ip_parts[2] BETWEEN ip_low2 AND ip_high2)
					AND ($ip_parts[3] BETWEEN ip_low3 AND ip_high3)
					AND ($ip_parts[4] BETWEEN ip_low4 AND ip_high4))";

			// IP was valid, maybe there's also a hostname...
			if (empty($modSettings['disableHostnameLookup']))
			{
				$hostname = @gethostbyaddr($user_info['ip']);
				if (strlen($hostname) > 0)
					$ban_query[] = "(ban_type = 'hostname_ban' AND ('$hostname' LIKE hostname))";
			}
		}
		// We use '255.255.255.255' for 'unknown' since it's not valid anyway.
		elseif ($user_info['ip'] == 'unknown')
			$ban_query[] = "(ban_type = 'ip_ban'
					AND ip_low1 = 255 AND ip_high1 = 255
					AND ip_low2 = 255 AND ip_high2 = 255
					AND ip_low3 = 255 AND ip_high3 = 255
					AND ip_low4 = 255 AND ip_high4 = 255)";

		// Is their email address banned?
		if (strlen($user_info['email']) != 0)
			$ban_query[] = "(ban_type = 'email_ban' AND ('" . addslashes($user_info['email']) . "' LIKE email_address))";

		// How about this user?
		if (!$user_info['is_guest'])
			$ban_query[] = "(ban_type = 'user_ban' AND ID_MEMBER = $ID_MEMBER)";

		// Check the ban, if there's information.
		if (!empty($ban_query))
		{
			$request = db_query("
				SELECT ban_type, restriction_type, reason
				FROM {$db_prefix}banned
				WHERE (expire_time IS NULL OR expire_time > " . time() . ")
					AND (" . implode(' OR ', $ban_query) . ')', __FILE__, __LINE__);
			// Store every type of ban that applies to you in your session.
			while ($row = mysql_fetch_assoc($request))
				$_SESSION['ban'][$row['restriction_type']] = array(
					'is_banned' => true,
					'reason' => empty($row['reason']) ? '' : '<br /><br /><b>' . $txt['ban_reason'] . ':</b> ' . $row['reason'],
					'type' => $row['ban_type']
				);
		}
	}

	// If you're fully banned, it's end of the story for you.
	if ($_SESSION['ban']['full_ban']['is_banned'])
	{
		db_query("
			INSERT INTO {$db_prefix}log_banned
				(ID_MEMBER, ip, email, logTime)
			VALUES ($ID_MEMBER, '$user_info[ip]', " . (!$user_info['is_guest'] ? "'$user_info[email]'" : 'NULL') . ', ' . time() . ')', __FILE__, __LINE__);

		// 'Log' the user out.  Can't have any funny business... (save the name!)
		$old_name = isset($user_info['name']) && $user_info['name'] != '' ? $user_info['name'] : $txt[28];
		$user_info['name'] = '';
		$user_info['username'] = '';
		$user_info['is_guest'] = true;
		$user_info['is_admin'] = false;
		$user_info['permissions'] = array();
		$ID_MEMBER = 0;
		$context['user'] = array(
			'id' => 0,
			'username' => '',
			'name' => $txt[28],
			'is_guest' => true,
			'is_logged' => false,
			'is_admin' => false,
			'is_mod' => false,
			'language' => $user_info['language']
		);

		// You banned, sucka!
		fatal_error(sprintf($txt[430], $old_name) . $_SESSION['ban']['full_ban']['reason']);
	}

	// Fix up the banning permissions.
	if (isset($user_info['permissions']))
		banPermissions();
}

// Fix permissions according to ban status.
function banPermissions()
{
	global $user_info;

	// Somehow they got here, at least take away all permissions...
	if (isset($_SESSION['ban']) && $_SESSION['ban']['full_ban']['is_banned'])
		$user_info['permissions'] = array();
	// Okay, well, you can watch, but don't touch a thing.
	elseif (isset($_SESSION['ban']) && $_SESSION['ban']['cannot_post']['is_banned'])
	{
		$user_info['permissions'] = array_diff(
			$user_info['permissions'],
			array(
				'pm_send',
				'calendar_post', 'calendar_edit_own', 'calendar_edit_any',
				'poll_post',
				'poll_add_own', 'poll_add_any',
				'poll_edit_own', 'poll_edit_any',
				'poll_lock_own', 'poll_lock_any',
				'poll_remove_own', 'poll_remove_any',
				'manage_attachments', 'manage_smileys', 'manage_boards', 'admin_forum', 'manage_permissions',
				'moderate_forum', 'manage_membergroups', 'manage_bans', 'send_mail', 'edit_news',
				'profile_identity_any', 'profile_extra_any', 'profile_title_any',
				'post_new', 'post_reply_own', 'post_reply_any',
				'remove_own', 'remove_any', 'remove_replies',
				'make_sticky',
				'merge_any', 'split_any',
				'modify_own', 'modify_any', 'modify_replies',
				'move_any',
				'send_topic',
				'lock_own', 'lock_any',
				'delete_own', 'delete_any',
			)
		);
	}
}

// Check if a name is in the reserved words list. (name, current member id, name/username?.)
function isReservedName($name, $current_ID_MEMBER = 0, $is_name = true)
{
	global $user_info, $modSettings, $db_prefix;

	$checkName = strtolower($name);

	// Administrators are never restricted ;).
	if (!allowedTo('moderate_forum') && ((!empty($modSettings['reserveName']) && $is_name) || !empty($modSettings['reserveUser']) && !$is_name))
	{
		$reservedNames = explode("\n", $modSettings['reserveNames']);
		// Case sensitive check?
		$checkName = empty($modSettings['reserveCase']) ? strtolower($checkName) : $checkName;

		// Check each name in the list...
		foreach ($reservedNames as $reserved)
		{
			if ($reserved == '')
				continue;

			// Case sensitive name?
			$reservedCheck = empty($modSettings['reserveCase']) ? strtolower($reserved) : $reserved;
			// If it's not just entire word, check for it in there somewhere...
			if ($checkName == $reservedCheck || (strpos($checkName, $reservedCheck) !== false && empty($modSettings['reserveWord'])))
				fatal_lang_error(244, true, array($reserved));
		}
	}

	// Get rid of any SQL parts of the reserved name and make it lowercase.
	$checkName = strtr($name, array('_' => '\\_', '%' => '\\%'));

	// Make sure they don't want someone else's name.
	$request = db_query("
		SELECT ID_MEMBER
		FROM {$db_prefix}members
		WHERE " . (empty($current_ID_MEMBER) ? '' : "ID_MEMBER != $current_ID_MEMBER
			AND ") . "(realName LIKE '$checkName' OR memberName LIKE '$checkName')
		LIMIT 1", __FILE__, __LINE__);
	if (mysql_num_rows($request) > 0)
		return true;
	mysql_free_result($request);

	// Does name case insensitive match a member group name?
	$request = db_query("
		SELECT ID_GROUP
		FROM {$db_prefix}membergroups
		WHERE groupName LIKE '$checkName'
		LIMIT 1", __FILE__, __LINE__);
	if (mysql_num_rows($request) > 0)
		return true;
	mysql_free_result($request);

	// Okay, they passed.
	return false;
}

// Make sure the user's correct session was passed, and they came from here. (type can be post, get, or request.)
function checkSession($type = 'post', $from_action = '', $is_fatal = true)
{
	global $sc, $modSettings, $boardurl;

	// Is it in as $_POST['sc']?
	if ($type == 'post' && (!isset($_POST['sc']) || $_POST['sc'] != $sc))
		$error = 'smf304';
	// How about $_GET['sesc']?
	elseif ($type == 'get' && (!isset($_GET['sesc']) || $_GET['sesc'] != $sc))
		$error = 'smf305';
	// Or can it be in either?
	elseif ($type == 'request' && (!isset($_GET['sesc']) || $_GET['sesc'] != $sc) && (!isset($_POST['sc']) || $_POST['sc'] != $sc))
		$error = 'smf305';

	// Verify that they aren't changing user agents on us - that could be bad.
	if ((!isset($_SESSION['USER_AGENT']) || $_SESSION['USER_AGENT'] != $_SERVER['HTTP_USER_AGENT']) && empty($modSettings['disableCheckUA']))
		$error = 'smf305';

	// Check the referring site - it should be the same server at least!
	$referrer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER']) : array();
	if (!empty($referrer['host']))
	{
		if (strpos($_SERVER['HTTP_HOST'], ':') !== false)
			$real_host = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], ':'));
		else
			$real_host = $_SERVER['HTTP_HOST'];

		$parsed_url = parse_url($boardurl);

		// Are global cookies on?  If so, let's check them ;).
		if (!empty($modSettings['globalCookies']))
		{
			if (preg_match('~(?:[^\.]+\.)?([^\.]{3,}\..+)\z~i', $parsed_url['host'], $parts) == 1)
				$parsed_url['host'] = $parts[1];

			if (preg_match('~(?:[^\.]+\.)?([^\.]{3,}\..+)\z~i', $referrer['host'], $parts) == 1)
				$referrer['host'] = $parts[1];

			if (preg_match('~(?:[^\.]+\.)?([^\.]{3,}\..+)\z~i', $real_host, $parts) == 1)
				$real_host = $parts[1];
		}

		// Okay: referrer must either match parsed_url or real_host.
		if (strtolower($referrer['host']) != strtolower($parsed_url['host']) && strtolower($referrer['host']) != strtolower($real_host))
		{
			$error = 'smf306';
			$log_error = true;
		}
	}

	// Well, first of all, if a from_action is specified you'd better have an old_url.
	if (!empty($from_action) && (!isset($_SESSION['old_url']) || preg_match('~[?;&]action=' . $from_action . '([;&]|$)~', $_SESSION['old_url']) == 0))
	{
		$error = 'smf306';
		$log_error = true;
	}

	// Everything is ok, return an empty string.
	if (!isset($error))
		return '';
	// A session error occurred, show the error.
	elseif ($is_fatal)
		fatal_lang_error($error, isset($log_error));
	// A session error occurred, return the error to the calling function.
	else
		return $error;
}

// Check whether a form has been submitted twice.
function checkSubmitOnce($action, $is_fatal = true)
{
	global $context;

	// Register a form number and store it in the session stack. (use this on the page that has the form.)
	if ($action == 'register')
	{
		// The stack hasn't been created yet.  Start it off with a single bit in it and move the pointer to 0.
		if (!isset($_SESSION['form_stack']) || !isset($_SESSION['form_stack_pointer']))
		{
			$_SESSION['form_stack'] = chr(1);
			$_SESSION['form_stack_pointer'] = 0;
		}
		// We've already got one, so set the next bit in it.
		else
			setBit($_SESSION['form_stack'], $_SESSION['form_stack_pointer'], 1);

		// Get the current value, and then increment the pointer.
		$context['form_sequence_number'] = $_SESSION['form_stack_pointer']++;
	}
	// Check whether the submitted number can be found in the session.
	elseif ($action == 'check')
	{
		// If the variable is indeed set, we have a stack, AND the bit isn't set on the stack....
		if (isset($_REQUEST['seqnum']) && isset($_SESSION['form_stack']) && !getBit($_SESSION['form_stack'], $_REQUEST['seqnum']))
		{
			// You've already submitted.
			if ($is_fatal)
				fatal_lang_error('error_form_already_submitted', false);
			else
				return false;
		}
		// Otherwise, let's set the seqnum stack.
		elseif (!isset($_REQUEST['seqnum']))
			$_REQUEST['seqnum'] = 0;

		// Release the number, never to be used again during this session.
		setBit($_SESSION['form_stack'], $_REQUEST['seqnum'], 0);

		return true;
	}
	// Don't check, just free the stack number.
	elseif ($action == 'free' && !empty($_REQUEST['seqnum']) && getBit($_SESSION['form_stack'], $_REQUEST['seqnum']))
		setBit($_SESSION['form_stack'], $_REQUEST['seqnum'], 0);
}

// Store a bit in a string at the given position (used by checkSubmitOnce).
function setBit(&$string, $position, $value)
{
	// Get the character position of the character carying the bit.
	$charPos = floor($position / 8);

	// Get the bit number of the character.
	$bitPos = $position % 8;

	// Enhance the string if its length is not sufficient.
	if (strlen($string) < $charPos + 1)
		$string .= str_repeat(chr(0), $charPos + 1 - strlen($string));

	// Set or unset the bit depending on $value.
	if (empty($value))
		$string{$charPos} = chr(ord($string{$charPos}) & ~pow(2, $bitPos));
	else
		$string{$charPos} = chr(ord($string{$charPos}) | pow(2, $bitPos));
}

// Check whether a bit at the given position is set (used by checkSubmitOnce).
function getBit(&$string, $position)
{
	// Get the position of the character this bit is in.
	$charPos = floor($position / 8);

	// If the string isn't even that long, obviously it's 0/false.
	if (strlen($string) < $charPos + 1)
		return false;

	// If the character's bits coincide with 2 ^ position, we got it! (assuming position is the size of the character, 8.)
	return (ord($string{$charPos}) & pow(2, $position % 8)) > 0;
}

// Check the user's permissions.
function allowedTo($permission, $boards = null)
{
	global $user_info, $db_prefix;

	// You're always allowed to do nothing. (unless you're a working man, MR. LAZY :P!)
	if (empty($permission))
		return true;

	// Administrators are supermen :P.
	if ($user_info['is_admin'])
		return true;

	// Are we checking the _current_ board, or some other boards?
	if ($boards === null)
	{
		// Check if they can do it.
		if (!is_array($permission) && in_array($permission, $user_info['permissions']))
			return true;
		// Search for any of a list of permissions.
		elseif (is_array($permission) && count(array_intersect($permission, $user_info['permissions'])) != 0)
			return true;
		// You aren't allowed, by default.
		else
			return false;
	}
	elseif (!is_array($boards))
		$boards = array($boards);

	$request = db_query("
		SELECT MIN(bp.addDeny) AS addDeny
		FROM {$db_prefix}boards AS b, {$db_prefix}board_permissions AS bp
		WHERE b.ID_BOARD IN (" . implode(', ', $boards) . ")
			AND bp.ID_BOARD = IF(b.use_local_permissions = 1, b.ID_BOARD, 0)
			AND bp.ID_GROUP IN (" . implode(', ', array_diff($user_info['groups'], array(3))) . ")
			AND bp.permission " . (is_array($permission) ? "IN (" . implode("', '", $permission) . ")" : " = '$permission'") . "
		GROUP BY b.ID_BOARD", __FILE__, __LINE__);

	// Make sure they can do it on all of the boards.
	if (mysql_num_rows($request) != count($boards))
		return false;

	$result = true;
	while ($row = mysql_fetch_assoc($request))
		$result &= !empty($row['addDeny']);
	mysql_free_result($request);

	// If the query returned 1, they can do it... otherwise, they can't.
	return $result;
}

// Fatal error if they cannot...
function isAllowedTo($permission, $boards = null)
{
	global $user_info, $txt;

	static $heavy_permissions = array(
		'admin_forum',
		'manage_attachments',
		'manage_smileys',
		'manage_boards',
		'edit_news',
		'moderate_forum',
		'manage_bans',
		'manage_membergroups',
		'manage_permissions',
	);

	// Make it an array, even if a string was passed.
	$permission = is_array($permission) ? $permission : array($permission);

	// Check the permission and return an error...
	if (!allowedTo($permission, $boards))
	{
		// Pick the last array entry as the permission shown as the error.
		$error_permission = array_pop($permission);

		// If they are a guest, show a login. (because the error might be gone if they do!)
		if ($user_info['is_guest'])
		{
			loadLanguage('Errors');
			is_not_guest($txt['cannot_' . $error_permission]);
		}

		// Clear the action because they aren't really doing that!
		$_GET['action'] = '';
		writeLog(true);

		fatal_lang_error('cannot_' . $error_permission, false);
	}

	// If you're doing something on behalf of some "heavy" permissions, validate your session.
	// (take out the heavy permissions, and if you can't do anything but those, you need a validated session.)
	if (!allowedTo(array_diff($permission, $heavy_permissions), $boards))
		validateSession();
}

// Return the boards a user has a certain (board) permission on. (array(0) if all.)
function boardsAllowedTo($permission)
{
	global $db_prefix, $ID_MEMBER, $user_info;

	// Administrators are all powerful, sorry.
	if ($user_info['is_admin'])
		return array(0);
	$boards = array();
	$deny_boards = array();

	// All groups the user is in except 'moderator'.
	$groups = array_diff($user_info['groups'], array(3));

	// Fetch boards that have the permission globally.
	$request = db_query("
		SELECT b.ID_BOARD, IFNULL(bp.addDeny, mod_bp.addDeny) AS addDeny
		FROM {$db_prefix}boards AS b
			LEFT JOIN {$db_prefix}board_permissions AS bp ON (bp.ID_BOARD = 0 AND bp.ID_GROUP IN (" . implode(', ', $groups) . ") AND bp.permission = '$permission')
			LEFT JOIN {$db_prefix}moderators AS mods ON (mods.ID_MEMBER = $ID_MEMBER AND mods.ID_BOARD = b.ID_BOARD)
			LEFT JOIN {$db_prefix}board_permissions AS mod_bp ON (mod_bp.ID_BOARD = 0 AND mod_bp.ID_GROUP = 3 AND mod_bp.permission = '$permission')
		WHERE b.use_local_permissions = 0
			AND (!ISNULL(bp.ID_BOARD) OR (!ISNULL(mod_bp.ID_BOARD) AND !ISNULL(mods.ID_MEMBER)))", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
	{
		if (empty($row['addDeny']))
			$deny_boards[] = $row['ID_BOARD'];
		else
			$boards[] = $row['ID_BOARD'];
	}
	mysql_free_result($request);

	// Fetch boards that have the permission locally.
	$request = db_query("
		SELECT b.ID_BOARD, IFNULL(bp.addDeny, mod_bp.addDeny) AS addDeny
		FROM {$db_prefix}boards AS b
			LEFT JOIN {$db_prefix}board_permissions AS bp ON (bp.ID_BOARD = b.ID_BOARD AND bp.ID_GROUP IN (" . implode(', ', $groups) . ") AND bp.permission = '$permission')
			LEFT JOIN {$db_prefix}moderators AS mods ON (mods.ID_MEMBER = $ID_MEMBER AND mods.ID_BOARD = b.ID_BOARD)
			LEFT JOIN {$db_prefix}board_permissions AS mod_bp ON (mod_bp.ID_BOARD = mods.ID_BOARD AND mod_bp.ID_GROUP = 3 AND mod_bp.permission = '$permission')
		WHERE b.use_local_permissions = 1
			AND (!ISNULL(bp.ID_BOARD) OR !ISNULL(mod_bp.ID_BOARD))", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
	{
		if (empty($row['addDeny']))
			$deny_boards[] = $row['ID_BOARD'];
		else
			$boards[] = $row['ID_BOARD'];
	}
	mysql_free_result($request);

	return array_values(array_diff($boards, $deny_boards));
}

?>