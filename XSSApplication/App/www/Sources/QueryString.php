<?php
/******************************************************************************
* QueryString.php                                                             *
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

/*	This file does a lot of important stuff.  Mainly, this means it handles
	the query string, request variables, and session management.  It contains
	the following functions:

	void cleanRequest()
		- cleans the request variables (ENV, GET, POST, COOKIE, SERVER) and
		  makes sure the query string was parsed correctly.
		- handles the URLs passed by the queryless URLs option.
		- makes sure, regardless of php.ini, everything has slashes.
		- sets up $board, $topic, and $scripturl and $_REQUEST['start'].
		- determines, or rather tries to determine, the client's IP.

	array addslashes__recursive(array var)
		- returns the var, as an array or string, with slashes.
		- importantly adds slashes to keys and values!
		- calls itself recursively if necessary.

	array htmlspecialchars__recursive(array var)
		- adds entities (&quot;, &lt;, &gt;) to the array or string var.
		- importantly, does not effect keys, only values.
		- calls itself recursively if necessary.

	array urldecode__recursive(array var)
		- takes off url encoding (%20, etc.) from the array or string var.
		- importantly, does it to keys too!
		- calls itself recursively if there are any sub arrays.

	array stripslashes__recursive(array var)
		- removes slashes, recursively, from the array or string var.
		- effects both keys and values of arrays.
		- calls itself recursively to handle arrays of arrays.

	array htmltrim__recursive(array var)
		- trims a string or an the var array using html characters as well.
		- does not effect keys, only values.
		- may call itself recursively if needed.

	string ob_sessrewrite(string buffer)
		- rewrites the URLs outputted to have the session ID, if the user
		  is not accepting cookies and is using a standard web browser.
		- handles rewriting URLs for the queryless URLs option.
		- can be turned off entirely by setting $scripturl to an empty
		  string, ''. (it wouldn't work well like that anyway.)
		- because of bugs in certain builds of PHP, does not function in
		  versions lower than 4.3.0 - please upgrade if this hurts you.
*/

// Clean the request variables - add html entities to GET and slashes if magic_quotes_gpc is Off.
function cleanRequest()
{
	global $board, $topic, $boardurl, $scripturl;

	// Makes it easier to refer to things this way.
	$scripturl = $boardurl . '/index.php';

	// Save some memory.. (since we don't use these anyway.)
	unset($GLOBALS['HTTP_POST_VARS']);
	unset($GLOBALS['HTTP_POST_FILES']);

	// Get the correct query string.  It may be in an environment variable...
	if (!isset($_SERVER['QUERY_STRING']))
		$_SERVER['QUERY_STRING'] = getenv('QUERY_STRING');

	// There's no query string, but there is a URL... try to get the data from there.
	if (empty($_SERVER['QUERY_STRING']) && !empty($_SERVER['REQUEST_URI']))
	{
		// We actually don't want slashes in $_GET... just entities - which serve the same purpose.
		if (get_magic_quotes_gpc() != 0)
			$_SERVER['REQUEST_URI'] = stripslashes($_SERVER['REQUEST_URI']);

		// Remove the .html, assuming there is one.
		if (substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '.'), 4) == '.htm')
			$_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '.'));

		// Replace 'index.php/a,b/c/d,e' with 'a=b&c=&d=e' and parse it into $_GET.
		parse_str(substr(preg_replace('/&(\w+)(&|\z)/', '&$1=$2', strtr(substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], basename($scripturl)) + strlen(basename($scripturl))), '/,', '&=')), 1), $_GET);
	}
	// Are we going to need to parse the ; out?
	elseif ((strpos(@ini_get('arg_separator.input'), ';') === false || @version_compare(PHP_VERSION, '4.2.0') == -1) && !empty($_SERVER['QUERY_STRING']))
	{
		// Get rid of the old one!  You don't know where it's been!
		$_GET = array();

		// Was this redirected?  If so, get the REDIRECT_QUERY_STRING.
		$_SERVER['QUERY_STRING'] = urldecode(substr($_SERVER['QUERY_STRING'], 0, 5) == 'url=/' ? $_SERVER['REDIRECT_QUERY_STRING'] : $_SERVER['QUERY_STRING']);

		// If magic_quotes_gpc isn't off, remove the slashes from the get variables.  (they're gonna be html'd anyway.)
		if (get_magic_quotes_gpc() != 0)
			$_SERVER['QUERY_STRING'] = stripslashes($_SERVER['QUERY_STRING']);

		// Replace ';' with '&' and '&something&' with '&something=&'.  (this is done for compatibility...)
		parse_str(preg_replace('/&(\w+)(&|$)/', '&$1=$2', strtr($_SERVER['QUERY_STRING'], ';', '&')), $_GET);
	}
	elseif (strpos(@ini_get('arg_separator.input'), ';') !== false)
	{
		$_GET = urldecode__recursive($_GET);

		if (get_magic_quotes_gpc() != 0)
			$_GET = stripslashes__recursive($_GET);
	}

	// Add entities to GET.  This is kinda like the slashes on everything else.
	$_GET = htmlspecialchars__recursive($_GET);

	// Clean up after annoying ini settings.  (magic_quotes_gpc might be off...)
	if (get_magic_quotes_gpc() == 0)
	{
		// E(G)PCS: ENV, (GET was already done), POST, COOKIE, SERVER.
		$_ENV = addslashes__recursive($_ENV);
		$_POST = addslashes__recursive($_POST);
		$_COOKIE = addslashes__recursive($_COOKIE);
		$_SERVER = addslashes__recursive($_SERVER);
	}

	// Let's not depend on the ini settings... why even have COOKIE in there, anyway?
	$_REQUEST = $_POST + $_GET;

	// Make sure $board and $topic are numbers.
	if (isset($_REQUEST['board']))
	{
		// If there's a slash in it, we've got a start value! (old, compatible links.)
		if (strpos($_REQUEST['board'], '/') !== false)
			list ($_REQUEST['board'], $_REQUEST['start']) = explode('/', $_REQUEST['board']);
		// Same idea, but dots.  This is the currently used format - ?board=1.0...
		elseif (strpos($_REQUEST['board'], '.') !== false)
			list ($_REQUEST['board'], $_REQUEST['start']) = explode('.', $_REQUEST['board']);
		// Now make absolutely sure it's a number.
		$board = (int) $_REQUEST['board'];

		// This is for "Who's Online" because it might come via POST - and it should be an int here.
		$_GET['board'] = $board;
	}
	// Well, $board is going to be a number no matter what.
	else
		$board = 0;

	// If there's a threadid, it's probably an old YaBB SE link.  Flow with it.
	if (isset($_REQUEST['threadid']) && !isset($_REQUEST['topic']))
		$_REQUEST['topic'] = $_REQUEST['threadid'];

	// We've got topic!
	if (isset($_REQUEST['topic']))
	{
		// Slash means old, beta style, formatting.  That's okay though, the link should still work.
		if (strpos($_REQUEST['topic'], '/') !== false)
			list ($_REQUEST['topic'], $_REQUEST['start']) = explode('/', $_REQUEST['topic']);
		// Dots are useful and fun ;).  This is ?topic=1.15.
		elseif (strpos($_REQUEST['topic'], '.') !== false)
			list ($_REQUEST['topic'], $_REQUEST['start']) = explode('.', $_REQUEST['topic']);

		$topic = (int) $_REQUEST['topic'];

		// Now make sure the online log gets the right number.
		$_GET['topic'] = $topic;
	}

	// There should be a $_REQUEST['start'], some at least.  If you need to default to other than 0, use $_GET['start'].
	if (empty($_REQUEST['start']) || $_REQUEST['start'] < 0)
		$_REQUEST['start'] = 0;

	// Find the user's IP address. (but don't let it give you 'unknown'!)
	if (!empty($_SERVER['HTTP_CLIENT_IP']) && preg_match('~^((0|10|172\.16|192\.168|255|127\.0)\.|unknown)~', $_SERVER['HTTP_CLIENT_IP']) == 0)
	{
		// Since they are in different blocks, it's probably reversed.
		if (strtok($_SERVER['REMOTE_ADDR'], '.') != strtok($_SERVER['HTTP_CLIENT_IP'], '.'))
			$_SERVER['REMOTE_ADDR'] = implode('.', array_reverse(explode('.', $_SERVER['HTTP_CLIENT_IP'])));
		else
			$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	{
		// If there are commas, get the last one.. probably.
		if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false)
		{
			$ips = array_reverse(explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']));

			// Go through each IP...
			foreach ($ips as $i => $ip)
			{
				// Make sure it's in a valid range...
				if (preg_match('~^((0|10|172\.16|192\.168|255|127\.0)\.|unknown)~', $ip) != 0)
					continue;

				// Otherwise, we've got an IP!
				$_SERVER['REMOTE_ADDR'] = trim($ip);
				break;
			}
		}
		// Otherwise just use the only one.
		elseif (preg_match('~^((0|10|172\.16|192\.168|255|127\.0)\.|unknown)~', $_SERVER['HTTP_X_FORWARDED_FOR']) == 0)
			$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}

	// Make sure REQUEST_URI is set.
	if (empty($_SERVER['REQUEST_URI']))
		$_SERVER['REQUEST_URI'] = $scripturl . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');

	// And make sure HTTP_USER_AGENT is set.
	if (empty($_SERVER['HTTP_USER_AGENT']))
		$_SERVER['HTTP_USER_AGENT'] = '';
}

// Adds slashes to the array/variable.  Uses two underscores to guard against overloading.
function addslashes__recursive($var)
{
	if (!is_array($var))
		return addslashes($var);

	// Reindex the array with slashes.
	$new_var = array();

	// Add slashes to every element, even the indexes!
	foreach ($var as $k => $v)
		$new_var[addslashes($k)] = addslashes__recursive($v);

	return $new_var;
}

// Adds html entities to the array/variable.  Uses two underscores to guard against overloading.
function htmlspecialchars__recursive($var)
{
	if (!is_array($var))
		return htmlspecialchars($var, ENT_QUOTES);

	// Add the htmlspecialchars to every element.
	foreach ($var as $k => $v)
		$var[$k] = htmlspecialchars__recursive($v);

	return $var;
}

// Removes url stuff from the array/variable.  Uses two underscores to guard against overloading.
function urldecode__recursive($var)
{
	if (!is_array($var))
		return urldecode($var);

	// Reindex the array...
	$new_var = array();

	// Add the htmlspecialchars to every element.
	foreach ($var as $k => $v)
		$new_var[urldecode($k)] = urldecode__recursive($v);

	return $new_var;
}
// Strips the slashes off any array or variable.  Two underscores for the normal reason.
function stripslashes__recursive($var)
{
	if (!is_array($var))
		return stripslashes($var);

	// Reindex the array without slashes, this time.
	$new_var = array();

	// Strip the slashes from every element.
	foreach ($var as $k => $v)
		$new_var[stripslashes($k)] = stripslashes__recursive($v);

	return $new_var;
}

// Trim a string including the HTML space, character 160.
function htmltrim__recursive($var)
{
	// Remove spaces (32), tabs (9), returns (13, 10, and 11), nulls (0), and hard spaces. (160)
	if (!is_array($var))
		return trim($var, " \t\n\r\x0B\0\xA0");

	// Go through all the elements and remove the whitespace.
	foreach ($var as $k => $v)
		$var[$k] = htmltrim__recursive($v);

	return $var;
}

// Rewrite URLs to include the session ID.
function ob_sessrewrite($buffer)
{
	global $scripturl, $modSettings, $user_info, $context;

	// If $scripturl is set to nothing, or the SID is not defined (SSI?) just quit.
	if ($scripturl == '' || !defined('SID'))
		return $buffer;

	// Do nothing if the session is cookied, or they are a crawler - guests are caught by redirectexit().  This doesn't work below PHP 4.3.0, because it makes the output buffer bigger.
	if (empty($_COOKIE) && SID != '' && (!$user_info['is_guest'] || (strpos($_SERVER['HTTP_USER_AGENT'], 'Mozilla') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false)) && @version_compare(PHP_VERSION, '4.3.0') != -1)
		$buffer = preg_replace('/"' . preg_quote($scripturl, '/') . '(?!\?' . preg_quote(SID, '/') . ')(\?)?/', '"' . $scripturl . '?' . SID . '&amp;', $buffer);
	// You can't do both, because session_start() won't catch the session if you do.  But this should work even in 4.2.x, just not CGI.
	elseif (!empty($modSettings['queryless_urls']) && !$context['server']['is_cgi'] && $context['server']['is_apache'])
		$buffer = preg_replace('/"' . preg_quote($scripturl, '/') . '\?((?:board|topic)=[^#"]+)(#[^"]*)?"/e', "'\"' . \$scripturl . '/' . strtr('\$1', '&;=', '//,') . '.html\$2\"'", $buffer);

	// Return the changed buffer.
	return $buffer;
}

?>