<?php
/******************************************************************************
* Errors.php                                                                  *
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

/*	The purpose of this file is... errors. (hard to guess, huh?)  It takes
	care of logging, error messages, error handling, database errors, and
	error log administration.  It does this with:

	string log_error(string error_message, string filename = none,
			int line = none)
		- logs an error, if error logging is enabled.
		- depends on the enableErrorLogging setting.
		- filename and line should be __FILE__ and __LINE__, respectively.
		- returns the error message. (ie. die(log_error($msg));)

	resource db_error(string database_query, string filename, int line)
		- logs and handles a database error, and tries to fix any broken
		  tables if it's enabled.
		- used by db_query() from Subs.php... takes its same parameters.
		- should not be used except by db_query().
		- returns a query result if it was able to recover.

	void fatal_error(string error_message, bool log = true)
		- stops execution and displays an error message.
		- logs the error message if log is missing or true.
		- uses the fatal_error sub template of the Errors template - or the
		  error sub template in the Wireless template.

	void fatal_lang_error(string error_message_key, bool log = false,
			array sprintf = array())
		- stops execution and displays an error message by key.
		- uses the string with the error_message_key key.
		- loads the Errors language file.
		- applies the sprintf information if specified.
		- the information is logged if log is true or missing.
		- uses the Errors template with the fatal_error sub template, or the
		  proper error sub template in the Wirless template.

	void error_handler(int error_level, string error_string, string filename,
			int line)
		- this is a standard PHP error handler replacement.
		- dies with fatal_error() if the error_level matches with
		  error_reporting.

	bool db_fatal_error()
		- loads Subs-Auth.php and calls show_db_error().
		- this is used for database connection error handling.

	void ViewErrorLog()
		- sets all the context up to show the error log for maintenance.
		- uses the Errors template and error_log sub template.
		- requires the maintain_forum permission.
		- uses the 'view_errors' administration area.
		- accessed from ?action=viewErrorLog.

	void deleteErrors()
		- deletes all or some of the errors in the error log.
		- applies any necessary filters to deletion.
		- should only be called by ViewErrorLog().
		- attempts to TRUNCATE the table to reset the auto_increment.
		- redirects back to the error log when done.
*/

// Log an error, if the option is on.
function log_error($error_message, $file = null, $line = null)
{
	global $db_prefix, $txt, $modSettings, $ID_MEMBER, $sc, $db_connection, $user_info;

	// Check if error logging is actually on.
	if (empty($modSettings['enableErrorLogging']))
		return $error_message;

	// Basically, htmlspecialchars it minus &. (for entities!)
	$error_message = strtr($error_message, array('<' => '&lt;', '>' => '&gt;', '"' => '&quot;'));
	$error_message = strtr($error_message, array('&lt;br /&gt;' => '<br />', '&lt;b&gt;' => '<b>', '&lt;/b&gt;' => '</b>', "\n" => '<br />'));

	// Add a file and line to the error message?  (remember, $txt may not exist yet!!)
	if ($file != null)
		$error_message .= '<br />' . (isset($txt[1003]) ? $txt[1003] . ': ' : '') . $file;
	if ($line != null)
		$error_message .= '<br />' . (isset($txt[1004]) ? $txt[1004] . ': ' : '') . $line;

	// Just in case there's no ID_MEMBER or IP set yet.
	if (empty($ID_MEMBER))
		$ID_MEMBER = 0;
	if (empty($user_info['ip']))
		$user_info['ip'] = '';

	// Don't log the session hash in the url twice, it's a waste.
	$query_string = empty($_SERVER['QUERY_STRING']) ? '' : addslashes(htmlspecialchars('?' . preg_replace('~;sesc=[^&;]+~', ';sesc', $_SERVER['QUERY_STRING'])));

	// Insert the error into the database.
	mysql_query("
		INSERT INTO {$db_prefix}log_errors
			(ID_MEMBER, logTime, IP, url, message, session)
		VALUES ($ID_MEMBER, " . time() . ", '$user_info[ip]', '$query_string', '" . addslashes($error_message) . "', '$sc')", $db_connection) or die($error_message);

	// Return the message to make things simpler.
	return $error_message;
}

// Database error!
function db_error($db_string, $file, $line)
{
	global $txt, $context, $sourcedir, $db_connection, $db_last_error, $webmaster_email, $modSettings, $forum_version;

	// This is the error message...
	$query_error = mysql_error($db_connection);

	// Log the error.
	log_error($txt[1001] . ': ' . $query_error, $file, $line);

	// Database error auto fixing ;).
	if (!isset($modSettings['autoFixDatabase']) || $modSettings['autoFixDatabase'] == '1')
	{
		// Check for error 135... only fix it once every three days, and send an email. (can't use empty because it might not be set yet...)
		if ($db_last_error < time() - 3600 * 24 * 3 && strpos($query_error, 'Can\'t open file') !== false)
		{
			// Admin.php for updateSettingsFile(), Subs-Post.php for sendmail().
			require_once($sourcedir . '/Admin.php');
			require_once($sourcedir . '/Subs-Post.php');

			// Make a note of the REPAIR...
			updateSettingsFile(array('db_last_error' => time()));

			preg_match('/^Can\'t open file:\s*[\']?([^\.]+?)\./', $query_error, $match);
			if (!empty($match[1]))
				mysql_query("
					REPAIR TABLE $match[1]", $db_connection);

			// And send off an email!
			sendmail($webmaster_email, $txt[1001], $txt[1005]);

			// Try the query again...
			$ret = mysql_query($db_string, $db_connection);
			if ($ret !== false)
				return $ret;
		}
		// Check for the "lost connection" error - and try it just one more time.
		elseif (strpos($query_error, 'Lost connection to MySQL server during query') !== false || strpos($query_error, 'Deadlock found when trying to get lock') !== false)
		{
			$ret = mysql_query($db_string, $db_connection);

			// If it failed again, shucks to be you... we're not trying it over and over.
			if ($ret !== false)
				return $ret;
		}
	}

	// Nothing's defined yet... just die with it.
	if (empty($context) || empty($txt))
		die($query_error);

	// Show an error message, if possible.
	$context['error_title'] = $txt[1001];
	if (allowedTo('admin_forum'))
		$context['error_message'] = nl2br($query_error) . '<br />' . $txt[1003] . ': ' . $file . '<br />' . $txt[1004] . ': ' . $line;
	else
		$context['error_message'] = $txt[1002];

	// A database error is often the sign of a database in need of updgrade.  Check forum versions, and if not identical suggest an upgrade... (not for Demo/CVS versions!)
	if (allowedTo('admin_forum') && !empty($forum_version) && $forum_version != 'SMF ' . @$modSettings['smfVersion'] && strpos($forum_version, 'Demo') === false && strpos($forum_version, 'CVS') === false)
		$context['error_message'] .= '<br /><br />' . $txt['database_error_versions'];

	// It's already been logged... don't log it again.
	fatal_error($context['error_message'], false);
}

// An irrecoverable error.
function fatal_error($error, $log = true)
{
	global $txt, $context;

	// We don't have $txt yet, but that's okay...
	if (empty($txt))
		die($error);

	// Log the error and set up the template.
	if (!isset($context['error_title']))
	{
		$context['error_title'] = $txt[106];
		$context['error_message'] = $log ? log_error($error) : $error;
	}

	// If there's not a page title yet, set one.
	if (!isset($context['page_title']))
		$context['page_title'] = $context['error_title'];

	// Display the error message - wireless?
	if (WIRELESS)
		$context['sub_template'] = WIRELESS_PROTOCOL . '_error';
	// Load the template and set the sub template.
	else
	{
		loadTemplate('Errors');
		$context['sub_template'] = 'fatal_error';
	}

	// We want whatever for the header, and a footer. (footer includes sub template!)
	obExit(null, true);
}

// A fatal error with a message stored in the language file.
function fatal_lang_error($error, $log = true, $sprintf = array())
{
	global $txt;

	// Load the language file...
	loadLanguage('Errors');

	// Are we formatting anything?
	if (empty($sprintf))
		fatal_error($txt[$error], $log);
	else
		fatal_error(vsprintf($txt[$error], $sprintf), $log);
}

// Handler for standard error messages.
function error_handler($error_level, $error_string, $file, $line)
{
	global $settings;

	if (error_reporting() == 0)
		return;

	if (strpos($file, 'eval()') !== false && !empty($settings['current_include_filename']))
		$file = realpath($settings['current_include_filename']) . ' (eval?)';

	if (isset($GLOBALS['db_show_debug']) && $GLOBALS['db_show_debug'] === true)
	{
		// Debugging!  This should look like a PHP error message.
		echo '<br />
<b>', $error_level % 255 == E_ERROR ? 'Error' : ($error_level % 255 == E_WARNING ? 'Warning' : 'Notice'), '</b>: ', $error_string, ' in <b>', $file, '</b> on line <b>', $line, '</b><br />';
	}

	log_error($error_level . ': ' . $error_string, $file, $line);

	// If this is an E_ERROR, E_USER_ERROR, E_WARNING, or E_USER_WARNING.... die.  Violently so.
	if ($error_level % 255 == E_ERROR || $error_level % 255 == E_WARNING)
		fatal_error($error_level . ': ' . $error_string, false);
}

// Just wrap it so we don't take up time and space here in Errors.php.
function db_fatal_error()
{
	global $sourcedir;

	// Just load the other file and run it.
	require_once($sourcedir . '/Subs-Auth.php');
	show_db_error();

	// Since we use "or db_fatal_error();" this is needed...
	return false;
}

// View the forum's error log.
function ViewErrorLog()
{
	global $db_prefix, $scripturl, $txt, $context, $modSettings, $user_profile, $filter;

	// Check for the administrative permission to do this.
	isAllowedTo('admin_forum');

	// Administration bar, templates, etc...
	adminIndex('view_errors');
	loadTemplate('Errors');

	// You can filter by any of the following columns:
	$filters = array(
		'ID_MEMBER' => &$txt[35],
		'IP' => &$txt['ip_address'],
		'session' => &$txt['session'],
		'url' => &$txt['error_url'],
		'message' => &$txt['error_message']
	);

	// Set up the filtering...
	if (isset($_GET['value']) && isset($_GET['filter']) && isset($filters[$_GET['filter']]))
		$filter = array(
			'variable' => $_GET['filter'],
			'value' => array(
				'sql' => addslashes($_GET['filter'] == 'message' || $_GET['filter'] == 'url' ? base64_decode($_GET['value']) : $_GET['value'])
			),
			'href' => ';filter=' . $_GET['filter'] . ';value=' . $_GET['value'],
			'entity' => $filters[$_GET['filter']]
		);

	// Deleting, are we?
	if (isset($_POST['delall']) || isset($_POST['delete']))
		deleteErrors();

	// Just how many errors are there?
	$result = db_query("
		SELECT COUNT(ID_ERROR)
		FROM {$db_prefix}log_errors" . (isset($filter) ? "
		WHERE $filter[variable] = '{$filter['value']['sql']}'" : ''), __FILE__, __LINE__);
	list ($num_errors) = mysql_fetch_row($result);
	mysql_free_result($result);

	// If this filter is empty...
	if ($num_errors == 0 && isset($filter))
		redirectexit('action=viewErrorLog');

	// Clean up start.
	if (!isset($_GET['start']) || $_GET['start'] < 0)
		$_GET['start'] = 0;

	// Do we want to reverse error listing?
	$context['sort_direction'] = isset($_REQUEST['desc']) ? 'down' : 'up';

	// Set the page listing up.
	$context['page_index'] = constructPageIndex($scripturl . '?action=viewErrorLog' . ($context['sort_direction'] == 'down' ? ';desc' : '') . (isset($filter) ? $filter['href'] : ''), $_GET['start'], $num_errors, $modSettings['defaultMaxMessages']);
	$context['start'] = $_GET['start'];

	// Find and sort out the errors.
	$request = db_query("
		SELECT ID_ERROR, ID_MEMBER, IP, url, logTime, message, session
		FROM {$db_prefix}log_errors" . (isset($filter) ? "
		WHERE $filter[variable] = '{$filter['value']['sql']}'" : '') . "
		ORDER BY ID_ERROR " . ($context['sort_direction'] == 'down' ? 'DESC' : '') . "
		LIMIT $_GET[start], $modSettings[defaultMaxMessages]", __FILE__, __LINE__);
	$context['errors'] = array();
	$members = array();
	while ($row = mysql_fetch_assoc($request))
	{
		$context['errors'][] = array(
			'member' => array(
				'id' => $row['ID_MEMBER'],
				'ip' => $row['IP'],
				'session' => $row['session']
			),
			'time' => timeformat($row['logTime']),
			'timestamp' => $row['logTime'],
			'url' => array(
				'html' => htmlspecialchars($scripturl . $row['url']),
				'href' => base64_encode($row['url'])
			),
			'message' => array(
				'html' => str_replace("\n", '<br />', strtr($row['message'], array("\r" => '', '<br />' => "\n", '<' => '&lt;', '>' => '&gt;', '"' => '&quot;'))),
				'href' => base64_encode($row['message'])
			),
			'id' => $row['ID_ERROR']
		);

		// Make a list of members to load later.
		$members[$row['ID_MEMBER']] = $row['ID_MEMBER'];
	}
	mysql_free_result($request);

	// Load the member data.
	if (!empty($members))
	{
		// Get some additional member info.
		$request = db_query("
			SELECT ID_MEMBER, memberName, realName
			FROM {$db_prefix}members
			WHERE ID_MEMBER IN (" . implode(', ', $members) . ")
			LIMIT " . count($members), __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
			$members[$row['ID_MEMBER']] = $row;
		mysql_free_result($request);

		// This is a guest...
		$members[0] = array(
			'ID_MEMBER' => 0,
			'memberName' => '',
			'realName' => $txt[28]
		);

		// Go through each error and tack the data on.
		foreach ($context['errors'] as $id => $dummy)
		{
			$memID = $context['errors'][$id]['member']['id'];
			$context['errors'][$id]['member']['username'] = $members[$memID]['memberName'];
			$context['errors'][$id]['member']['name'] = $members[$memID]['realName'];
			$context['errors'][$id]['member']['href'] = empty($memID) ? '' : $scripturl . '?action=profile;u=' . $memID;
			$context['errors'][$id]['member']['link'] = empty($memID) ? $txt[28] : '<a href="' . $scripturl . '?action=profile;u=' . $memID . '">' . $context['errors'][$id]['member']['name'] . '</a>';
		}
	}

	// Filtering anything?
	if (isset($filter))
	{
		$context['filter'] = &$filter;

		// Set the filtering context.
		if ($filter['variable'] == 'ID_MEMBER')
		{
			$id = $filter['value']['sql'];
			loadMemberData($id, false, 'minimal');
			$context['filter']['value']['html'] = '<a href="' . $scripturl . '?action=profile;u=' . $id . '">' . $user_profile[$id]['realName'] . '</a>';
		}
		elseif ($filter['variable'] == 'url')
			$context['filter']['value']['html'] = "'" . htmlspecialchars($scripturl . stripslashes($filter['value']['sql'])) . "'";
		elseif ($filter['variable'] == 'message')
			$context['filter']['value']['html'] = "'" . strtr(htmlspecialchars(stripslashes($filter['value']['sql'])), array("\n" => '<br />', '&lt;br /&gt;' => '<br />', "\t" => '&nbsp;&nbsp;&nbsp;')) . "'";
		else
			$context['filter']['value']['html'] = &$filter['value']['sql'];
	}

	// And this is pretty basic ;).
	$context['page_title'] = $txt['errlog1'];
	$context['has_filter'] = isset($filter);
	$context['sub_template'] = 'error_log';
}

// Delete errors from the database.
function deleteErrors()
{
	global $db_prefix, $db_connection, $filter;

	// Make sure the session exists and is correct; otherwise, might be a hacker.
	checkSession();

	// Delete all or just some?
	if (isset($_POST['delall']) && !isset($filter))
	{
		// This is special: it only works in 3.23.28 and above, but.. it resets the increment. (it may fail in 3.23.4, etc.)
		mysql_query("
			TRUNCATE {$db_prefix}log_errors", $db_connection);

		db_query("
			DELETE FROM {$db_prefix}log_errors", __FILE__, __LINE__);
	}
	// Deleting all with a filter?
	elseif (isset($_POST['delall']) && isset($filter))
		db_query("
			DELETE FROM {$db_prefix}log_errors
			WHERE $filter[variable] = '" . $filter['value']['sql'] . "'", __FILE__, __LINE__);
	// Just specific errors?
	elseif (!empty($_POST['delete']))
	{
		db_query("
			DELETE FROM {$db_prefix}log_errors
			WHERE ID_ERROR IN (" . implode(',', array_unique($_POST['delete'])) . ')', __FILE__, __LINE__);

		// Go back to where we were.
		redirectexit('action=viewErrorLog' . (isset($_GET['desc']) ? ';desc' : '') . ';start=' . $_GET['start'] . (isset($filter) ? ';filter=' . $_GET['filter'] . ';value=' . $_GET['value'] : ''));
	}

	// Back to the error log!
	redirectexit('action=viewErrorLog' . (isset($_REQUEST['desc']) ? ';desc' : '') . '');
}

?>