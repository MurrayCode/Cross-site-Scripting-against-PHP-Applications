<?php
/******************************************************************************
* Subs-Auth.php                                                               *
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

/*	This file has functions in it to do with authentication, user handling,
	and the like.  It provides these functions:

	void setLoginCookie(int cookie_length, int ID_MEMBER, string password = '')
		- sets the SMF-style login cookie and session based on the ID_MEMBER
		  and password passed.
		- logs the user out if ID_MEMBER is zero.
		- sets the cookie and session to last the number of seconds specified
		  by cookie_length.
		- when logging out, if the globalCookies setting is enabled, attempts
		  to clear the subdomain's cookie too.

	array url_parts()
		- returns the path and domain to set the cookie on.
		- depends on the localCookies and globalCookies settings.
		- uses boardurl to determine these two things.
		- returns an array with domain and path in it, in that order.

	void KickGuest()
		- throws guests out to the login screen when guest access is off
		- sets $_SESSION['login_url'] to $_SERVER['REQUEST_URI']
		- uses the 'kick_guest' sub template found in Login.template.php

	void InMaintenance()
		- display a message about being in maintenance mode
		- display a login screen with sub template 'maintenance'

	void adminLogin()
		- double check the verity of the admin by asking for his password
		- loads Login.template.php and uses the admin_login sub template
		- sends data to template so the admin is sent on to the page they
		wanted if their password was correct, if not they try again.

	string adminLogin_outputPostVars(string key, string value)
		// !!!

	void show_db_error()
		// !!!

	array findMembers(array names, bool use_wildcards = false)
		// !!!

	void JSMembers()
		// !!!

	void resetPassword(int ID_MEMBER, string username)
		// !!!
*/

// Actually set the login cookie...
function setLoginCookie($cookie_length, $id, $password = '')
{
	global $cookiename;

	// Get the data and path to set it on.
	$data = serialize(empty($id) ? array(0, '', 0) : array($id, md5_hmac($password, 'ys'), time() + $cookie_length));
	$cookie_url = url_parts();

	// Set the cookie, $_COOKIE, and session variable.
	setcookie($cookiename, $data, time() + $cookie_length, $cookie_url[1], $cookie_url[0], 0);

	// If subdomain-independent cookies are on, unset the subdomain-dependent cookie too.
	if (empty($id) && !empty($modSettings['globalCookies']))
		setcookie($cookiename, $data, time() + $cookie_length, $cookie_url[1], '', 0);

	$_COOKIE[$cookiename] = $data;
	$_SESSION['login_' . $cookiename] = $data;
}

// Get the domain and path for the cookie...
function url_parts()
{
	global $boardurl, $modSettings;

	// Parse the URL with PHP to make life easier.
	$parsed_url = parse_url($boardurl);
	if (isset($parsed_url['port']))
		$parsed_url['host'] .= ':' . $parsed_url['port'];

	// Is local cookies off?
	if (empty($parsed_url['path']) || empty($modSettings['localCookies']))
		$parsed_url['path'] = '';

	// Globalize cookies across domains?
	if (!empty($modSettings['globalCookies']))
	{
		// If we can't figure it out, it's probably an IP so just skip it.
		if (preg_match('~(?:[^\.]+\.)?([^\.]{3,}\..+)\z~i', $parsed_url['host'], $parts) == 1)
			$parsed_url['host'] = '.' . $parts[1];
	}
	// We shouldn't use a host at all if both options are off.
	elseif (empty($modSettings['localCookies']))
		$parsed_url['host'] = '';

	return array($parsed_url['host'], $parsed_url['path'] . '/');
}

// Kick out a guest when guest access is off...
function KickGuest()
{
	global $txt, $context;

	loadTemplate('Login');
	loadLanguage('Login');

	$_SESSION['login_url'] = $_SERVER['REQUEST_URI'];

	$context['sub_template'] = 'kick_guest';
	$context['page_title'] = $txt[34];
}

// Display a message about the forum being in maintenance mode, etc.
function InMaintenance()
{
	global $txt, $mtitle, $mmessage, $context;

	loadTemplate('Login');
	loadLanguage('Login');

	// Basic template stuff..
	$context['sub_template'] = 'maintenance';
	$context['title'] = &$mtitle;
	$context['description'] = &$mmessage;
	$context['page_title'] = &$txt[155];
}

function adminLogin()
{
	global $context, $txt;

	loadTemplate('Login');
	loadLanguage('Admin');

	// Start with nothing for get data and post data.
	$context['get_data'] = '?';
	$context['post_data'] = '';

	// Add up all the data from $_GET into get_data.
	foreach ($_GET as $k => $v)
		$context['get_data'] .= $k . '=' . $v . ';';
	$context['get_data'] = substr($context['get_data'], 0, -1);

	// They used a wrong password, log it and unset that.
	if (isset($_POST['admin_pass']))
	{
		log_error($txt['security_wrong']);
		unset($_POST['admin_pass']);
	}

	// Now go through $_POST.  Make sure the session hash is sent.
	$_POST['sc'] = $context['session_id'];
	foreach ($_POST as $k => $v)
		$context['post_data'] .= adminLogin_outputPostVars($k, $v);

	// Now we'll use the admin_login sub template of the Login template.
	$context['sub_template'] = 'admin_login';

	// And title the page something like "Login".
	if (!isset($context['page_title']))
		$context['page_title'] = $txt[34];

	obExit();
}

function adminLogin_outputPostVars($k, $v)
{
	if (!is_array($v))
		return '
<input type="hidden" name="' . $k . '" value="' . htmlspecialchars(stripslashes($v)) . '" />';
	else
	{
		$ret = '';
		foreach ($v as $k2 => $v2)
			$ret .= adminLogin_outputPostVars($k . '[' . $k2 . ']', $v2);

		return $ret;
	}
}

// Show an error message for the connection problems.
function show_db_error()
{
	global $db_last_error, $sourcedir, $mbname, $maintenance, $mtitle, $mmessage, $db_error_send, $db_connection;

	// Don't cache this page!
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header('Cache-Control: no-cache');

	if ($db_last_error < time() - 3600 * 24 * 3 && empty($maintenance) && !empty($db_error_send))
	{
		require_once($sourcedir . '/Admin.php');
		updateSettingsFile(array('db_last_error' => time()));

		// Languages files aren't loaded yet :(.
		@mail($webmaster_email, $mbname . ': SMF Database Error!', "There has been a problem with the database!\nMySQL reported:\n" . mysql_error($db_connection));
	}

	if (!empty($maintenance))
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
	// What to do?  Language files haven't and can't be loaded yet...
	else
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Connection Problems.</title>
	</head>
	<body>
		<h3>Connection Problems</h3>
		Sorry, SMF was unable to connect to the database.  This may be caused by the server being busy.  Please try again later.
	</body>
</html>';

	die;
}

// Find members by email address, username, or real name.
function findMembers($names, $use_wildcards = false)
{
	global $db_prefix, $scripturl, $user_info, $modSettings;

	// If it's not already an array, make it one.
	if (!is_array($names))
		$names = explode(',', $names);

	foreach ($names as $i => $name)
	{
		// Add slashes, trim, and fix wildcards for each name.
		$names[$i] = addslashes(trim(strtolower($name)));

		// Make it so standard wildcards will work. (* and ?)
		if ($use_wildcards)
			$names[$i] = strtr($names[$i],array('%' => '\%', '_' => '\_', '*' => '%', '?' => '_'));
	}

	// Nothing found yet.
	$results = array();

	// This ensures you can't search someones email address if you can't see it.
	$condition = $user_info['is_admin'] || empty($modSettings['allow_hideEmail']) ? '' : 'hideEmail = 0 AND';

	// Search by username, display name, and email address.
	$request = db_query("
		SELECT ID_MEMBER, memberName, realName, emailAddress, hideEmail
		FROM {$db_prefix}members" . ($use_wildcards ? "
		WHERE memberName LIKE '" . implode("'
			OR memberName LIKE '", $names) . "'
			OR realName LIKE '" . implode("'
			OR realName LIKE '", $names) . "'
			OR ($condition emailAddress LIKE '" . implode("')
			OR ($condition emailAddress LIKE '", $names) . "')" : "
		WHERE memberName IN ('" . implode("', '", $names) . "')
			OR realName IN ('" . implode("', '", $names) . "')
			OR ($condition emailAddress IN ('" . implode("', '", $names) . "'))"), __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
	{
		$results[$row['ID_MEMBER']] = array(
			'id' => $row['ID_MEMBER'],
			'name' => $row['realName'],
			'username' => $row['memberName'],
			'email' => empty($row['hideEmail']) || empty($modSettings['allow_hideEmail']) || $user_info['is_admin'] ? $row['emailAddress'] : '',
			'href' => $scripturl . '?action=profile;u=' . $row['ID_MEMBER'],
			'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['realName'] . '</a>'
		);
	}
	mysql_free_result($request);

	// Return all the results.
	return $results;
}

function JSMembers()
{
	global $db_prefix, $context, $scripturl;

	checkSession('get');

	// Why is this in the Help template, you ask?  Well, erm... it helps you.  Does that work?
	loadTemplate('Help');

	$context['template_layers'] = array();
	$context['sub_template'] = 'find_members';

	if (isset($_REQUEST['search']))
		$context['last_search'] = htmlspecialchars(stripslashes($_REQUEST['search']), ENT_QUOTES);
	else
		$_REQUEST['start'] = 0;

	// Allow the user to pass the input to be added to to the box.
	$context['input_box_name'] = isset($_REQUEST['input']) ? $_REQUEST['input'] : 'to';

	// Take the delimiter over GET in case it's \n or something.
	$context['delimiter'] = isset($_REQUEST['delim']) ? stripslashes($_REQUEST['delim']) : ', ';
	$context['quote_results'] = !empty($_REQUEST['quote']);

	// List all the results.
	$context['results'] = array();

	// If the user has done a search, well - search.
	if (isset($_REQUEST['search']))
	{
		$_REQUEST['search'] = addslashes(htmlspecialchars(stripslashes($_REQUEST['search']), ENT_QUOTES));
		$context['results'] = findMembers(array($_REQUEST['search']), true);
		$total_results = count($context['results']);

		$context['page_index'] = constructPageIndex($scripturl . '?action=findmember;search=' . $context['last_search'] . ';sesc=' . $context['session_id'] . ';input=' . $context['input_box_name'] . ($context['quote_results'] ? ';quote' : ''), $_REQUEST['start'], $total_results, 7);

		$context['results'] = array_slice($context['results'], $_REQUEST['start'], 7);
	}
}

// This function generates a random password for a user and emails it to them.
function resetPassword($memID, $username = null)
{
	global $db_prefix, $scripturl, $context, $txt, $sourcedir;

	// Language... and a required file.
	loadLanguage('Login');
	require_once($sourcedir . '/Subs-Post.php');

	// Get some important details.
	$request = db_query("
		SELECT memberName, emailAddress
		FROM {$db_prefix}members
		WHERE ID_MEMBER = $memID", __FILE__, __LINE__);
	list ($user, $email) = mysql_fetch_row($request);
	mysql_free_result($request);

	if ($username !== null)
		$user = trim($username);

	// Generate a random password.
	$newPassword = substr(preg_replace('/\W/', '', md5(rand())), 0, 10);
	$newPassword_md5 = md5_hmac($newPassword, strtolower($user));

	// Do some checks on the username if needed.
	if ($username !== null)
	{
		// No name?!  How can you register with no name?
		if ($user == '')
			fatal_lang_error(37, false);

		// Only these characters are permitted.
		if (in_array($user, array('_', '|')) || preg_match('~[<>&"\'=\\\]~', $user) != 0 || strpos($user, '[code]') !== false || strpos($user, '[/code]') !== false)
			fatal_lang_error(240, false);

		if (stristr($user, $txt[28]) !== false)
			fatal_lang_error(244, true, array($txt[28]));

		if (isReservedName($user, $memID, false))
			fatal_error('(' . htmlspecialchars($user) . ') ' . $txt[473], false);

		// Update the database...
		updateMemberData($memID, array('memberName' => '\'' . $user . '\'', 'passwd' => '\'' . $newPassword_md5 . '\''));
	}
	else
		updateMemberData($memID, array('passwd' => '\'' . $newPassword_md5 . '\''));

	// Send them the email informing them of the change - then we're done!
	sendmail($email, $txt['change_password'],
		"$txt[hello_member] $user!\n\n" .
		"$txt[change_password_1] $context[forum_name] $txt[change_password_2]\n\n" .
		"$txt[719]$user, $txt[492] $newPassword\n\n" .
		"$txt[701]\n" .
		"$scripturl?action=profile\n\n" .
		$txt[130]);
}

?>