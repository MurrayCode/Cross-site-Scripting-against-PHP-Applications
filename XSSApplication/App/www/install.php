<?php
/******************************************************************************
* install.php                                                                 *
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

$GLOBALS['current_smf_version'] = 'smf_1-0';

$GLOBALS['required_php_version'] = '4.1.0';
$GLOBALS['required_mysql_version'] = '3.23.4';

// Initialize everything and load the language files.
initialize_inputs();
load_lang_file();

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>', $txt['smf_installer'], '</title>
		<script language="JavaScript" type="text/javascript" src="Themes/default/script.js"></script>
		<style type="text/css"><!--
			body
			{
				font-family: Verdana, sans-serif;
				background-color: #D4D4D4;
				margin: 0;
			}
			body, td
			{
				font-size: 10pt;
			}
			div#header
			{
				background-color: white;
				padding: 22px 4% 12px 4%;
				font-family: Georgia, serif;
				font-size: xx-large;
				border-bottom: 1px solid black;
				height: 40px;
			}
			div#content
			{
				padding: 20px 30px;
			}
			div.error_message
			{
				border: 2px dashed red;
				background-color: #E1E1E1;
				margin: 1ex 4ex;
				padding: 1.5ex;
			}
			div.panel
			{
				border: 1px solid gray;
				background-color: #F0F0F0;
				margin: 1ex 0;
				padding: 1.2ex;
			}
			div.panel h2
			{
				margin: 0;
				margin-bottom: 0.5ex;
				padding-bottom: 3px;
				border-bottom: 1px dashed black;
				font-size: 14pt;
				font-weight: normal;
			}
			div.panel h4
			{
				margin: 0;
				margin-bottom: 2ex;
				font-size: 10pt;
				font-weight: normal;
			}
			form
			{
				margin: 0;
			}
			td.textbox
			{
				padding-top: 2px;
				font-weight: bold;
				white-space: nowrap;
				padding-right: 2ex;
			}
		--></style>
	</head>
	<body>
		<div id="header">
			<a href="http://www.simplemachines.org/" target="_blank"><img src="Themes/default/images/smflogo.gif" width="250" style="float: right;" alt="Simple Machines" border="0" /></a>
			<div title="Moogle Express!">', $txt['smf_installer'], '</div>
		</div>
		<div id="content">';

if (function_exists('doStep' . $_GET['step']))
	call_user_func('doStep' . $_GET['step']);

echo '
		</div>
	</body>
</html>';

function initialize_inputs()
{
	// Turn off magic quotes runtime and enable error reporting.
	@set_magic_quotes_runtime(0);
	error_reporting(E_ALL);

	// Fun.  Low PHP version...
	if (!isset($_GET))
	{
		$GLOBALS['_GET']['step'] = 0;
		return;
	}

	if (!isset($_GET['obgz']))
	{
		ob_start();
		@session_start();
	}
	else
	{
		ob_start('ob_gzhandler');
		session_start();

		if (!headers_sent())
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>', $_GET['pass_string'], '</title>
	</head>
	<body style="background-color: #D4D4D4; margin-top: 16%; text-align: center; font-size: 16pt;">
		<b>', $_GET['pass_string'], '</b>
	</body>
</html>';
		exit;
	}

	// Add slashes, as long as they aren't already being added.
	if (get_magic_quotes_gpc() == 0)
	{
		foreach ($_POST as $k => $v)
			$_POST[$k] = addslashes($v);
	}

	// This is really quite simple; if ?delete is on the URL, delete the installer...
	if (isset($_GET['delete']))
	{
		@unlink(__FILE__);

		if (isset($_SESSION['installer_temp_ftp']))
		{
			if (file_exists(__FILE__))
			{
				$ftp = new ftp_connection($_SESSION['installer_temp_ftp']['server'], $_SESSION['installer_temp_ftp']['port'], $_SESSION['installer_temp_ftp']['username'], $_SESSION['installer_temp_ftp']['password']);
				$ftp->chdir($_SESSION['installer_temp_ftp']['path']);

				$ftp->unlink('install.php');

				$ftp->close();
			}

			unset($_SESSION['installer_temp_ftp']);
		}

		// Now just redirect to a blank.gif...
		header('Location: http://' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT']) . dirname($_SERVER['PHP_SELF']) . '/Themes/default/images/blank.gif');
		exit;
	}

	// Force a step, defaulting to 0.
	$_GET['step'] = (int) @$_GET['step'];
}

// Load the list of language files, and the current language file.
function load_lang_file()
{
	global $txt;

	$found = array();

	// Make sure the languages directory actually exists.
	if (file_exists(dirname(__FILE__) . '/Themes/default/languages'))
	{
		// Find all the "Install" language files in the directory.
		$dir = dir(dirname(__FILE__) . '/Themes/default/languages');
		while ($entry = $dir->read())
		{
			if (substr($entry, 0, 8) == 'Install.')
				$found[$entry] = ucfirst(substr($entry, 8, strlen($entry) - 12));
		}
		$dir->close();
	}

	// Didn't find any, show an error message!
	if (empty($found))
	{
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-cache');

		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>SMF Installer: Error!</title>
	</head>
	<body style="font-family: sans-serif;"><div style="width: 600px;">
		<b>A critical error has occurred.</b><br />
		<br />
		This installer was unable to find the installer\'s language file or files.  They should be found under:<br />
		<div style="margin: 1ex; font-family: monospace; font-weight: bold;">', dirname($_SERVER['PHP_SELF']) != '/' ? dirname($_SERVER['PHP_SELF']) : '', '/Themes/default/languages</div>
		<br />
		In some cases, FTP clients do not properly upload files with this many folders.  Please double check to make sure you <span style="font-weight: 600;">have uploaded all the files in the distribution</span>.<br />
		<div style="margin-top: 1ex;">If that doesn\'t help, please make sure this install.php file is in the same place as the Themes folder.</div>
		<br />
		If you continue to get this error message, feel free to <a href="http://support.simplemachines.org/">look to us for support</a>.<br />
		<br />
		Simple Machines
	</div></body>
</html>';
		die;
	}

	// Override the language file?
	if (isset($_GET['lang_file']))
		$_SESSION['smf_install_lang'] = $_GET['lang_file'];

	// Make sure it exists, if it doesn't reset it.
	if (!isset($_SESSION['smf_install_lang']) || !file_exists(dirname(__FILE__) . '/Themes/default/languages/' . $_SESSION['smf_install_lang']))
	{
		// Use the first one...
		list ($_SESSION['smf_install_lang']) = array_keys($found);

		// If we have english and some other language, use the other language.  We Americans hate english :P.
		if ($_SESSION['smf_install_lang'] == 'Install.english.php' && count($found) > 1)
			list (, $_SESSION['smf_install_lang']) = array_keys($found);
	}

	// And now include the actual language file itself.
	require_once(dirname(__FILE__) . '/Themes/default/languages/' . $_SESSION['smf_install_lang']);

	$GLOBALS['detected_languages'] = $found;
}

// Step zero: Finding out how and where to install.
function doStep0()
{
	global $txt;

	// Show a language selection...
	if (count($GLOBALS['detected_languages']) > 1)
	{
		echo '
				<div align="right" style="padding-bottom: 3ex;">
					<label for="installer_language">', $txt['installer_language'], ':</label> <select id="installer_language" onchange="location.href = \'', isset($_SERVER) ? $_SERVER['PHP_SELF'] : 'install.php', '?lang_file=\' + this.options[this.selectedIndex].value">';

		foreach ($GLOBALS['detected_languages'] as $lang => $name)
			echo '
						<option', isset($_SESSION['smf_install_lang']) && $_SESSION['smf_install_lang'] == $lang ? ' selected="selected"' : '', ' value="', $lang, '">', $name, '</option>';

		echo '
					</select>
				</div>';
	}

	// Check the PHP version.
	if (!php_version_check() && !isset($_GET['overphp']))
	{
		echo '
				<div class="error_message">
					<div style="color: red;">', $txt['error_php_too_low'], '</div>
					<br />
					<a href="', $_SERVER['PHP_SELF'], '?step=0&amp;overphp=true">', $txt['error_message_click'], '</a> ', $txt['error_message_bad_try_again'], '
				</div>';

		return false;
	}

	// Is MySQL even compiled in?
	if (!function_exists('mysql_connect'))
	{
		echo '
				<div class="error_message">
					<div style="color: red;">', $txt['error_mysql_missing'], '</div>
					<br />
					<a href="', $_SERVER['PHP_SELF'], '?step=0&amp;overphp=true">', $txt['error_message_click'], '</a> ', $txt['error_message_try_again'], '
				</div>';

		return false;
	}

	// Make sure they uploaded all the files.
	$ftest = file_exists(dirname(__FILE__) . '/index.php')
		&& file_exists(dirname(__FILE__) . '/' . $GLOBALS['current_smf_version'] . '.sql');
	if (!$ftest)
	{
		echo '
				<div class="error_message">
					<div style="color: red;">', $txt['error_missing_files'], '</div>
					<br />
					<a href="', $_SERVER['PHP_SELF'], '?step=0&amp;overphp=true">', $txt['error_message_click'], '</a> ', $txt['error_message_try_again'], '
				</div>';

		return false;
	}

	// Very simple check on the session.save_path for Windows.
	if ((session_save_path() == '/tmp' && substr(__FILE__, 1, 2) == ':\\'))
	{
		echo '
				<div class="error_message">
					<div style="color: red;">', $txt['error_session_save_path'], '</div>
					<br />
					<a href="', $_SERVER['PHP_SELF'], '?step=0&amp;overphp=true">', $txt['error_message_click'], '</a> ', $txt['error_message_try_again'], '
				</div>';

		return false;
	}

	if (!make_files_writable())
		return false;

	// Set up the defaults.
	$db_server = @ini_get('mysql.default_host') or $db_server = 'localhost';
	$db_user = isset($_POST['ftp_username']) ? $_POST['ftp_username'] : @ini_get('mysql.default_user');
	$db_name = isset($_POST['ftp_username']) ? $_POST['ftp_username'] : @ini_get('mysql.default_user');
	$db_passwd = @ini_get('mysql.default_password');

	// This is just because it makes it easier for people on Tripod :P.
	if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'members.lycos.co.uk' && defined('LOGIN'))
	{
		$db_user = LOGIN;
		$db_name = LOGIN . '_uk_db';
	}

	// Should we use a non standard port?
	$db_port = @ini_get('mysql.default_port');
	if (!empty($db_port))
		$db_server .= ':' . $db_port;

	// What host and port are we on?
	$host = empty($_SERVER['HTTP_HOST']) ? $_SERVER['SERVER_NAME'] . (empty($_SERVER['SERVER_PORT']) || $_SERVER['SERVER_PORT'] == '80' ? '' : ':' . $_SERVER['SERVER_PORT']) : $_SERVER['HTTP_HOST'];

	// Now, to put what we've learned together... and add a path.
	$url = 'http://' . $host . substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));

	// Check if the database sessions will even work.
	$test_dbsession = @ini_get('session.auto_start') != 1;

	echo '
				<div class="panel">
					<form action="' . $_SERVER['PHP_SELF'] . '?step=1" method="post">
						<h2>', $txt['install_settings'], '</h2>
						<h4>', $txt['install_settings_info'], '</h4>

						<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 2ex;">
							<tr>
								<td width="20%" valign="top" class="textbox"><label for="mbname">', $txt['install_settings_name'], ':</label></td>
								<td>
									<input type="text" name="mbname" id="mbname" value="', $txt['install_settings_name_default'], '" size="65" />
									<div style="font-size: smaller; margin-bottom: 2ex;">', $txt['install_settings_name_info'], '</div>
								</td>
							</tr><tr>
								<td valign="top" class="textbox"><label for="boardurl">', $txt['install_settings_url'], ':</label></td>
								<td>
									<input type="text" name="boardurl" id="boardurl" value="', $url, '" size="65" /><br />
									<div style="font-size: smaller; margin-bottom: 2ex;">', $txt['install_settings_url_info'], '</div>
								</td>
							</tr><tr>
								<td valign="top" class="textbox"><label>', $txt['install_settings_compress'], ':</label></td>
								<td>
									<label for="compress"><input type="checkbox" name="compress" id="compress" checked="checked" /> ', $txt['install_settings_compress_title'], '</label><br />
									<div style="font-size: smaller; margin-bottom: 2ex;">', $txt['install_settings_compress_info'], '</div>
								</td>
							</tr><tr>
								<td valign="top" class="textbox"><label>', $txt['install_settings_dbsession'], ':</label></td>
								<td>
									<label for="dbsession"><input type="checkbox" name="dbsession" id="dbsession" checked="checked" /> ', $txt['install_settings_dbsession_title'], '</label><br />
									<div style="font-size: smaller; margin-bottom: 2ex;">', $test_dbsession ? $txt['install_settings_dbsession_info1'] : $txt['install_settings_dbsession_info2'], '</div>
								</td>
							</tr>
						</table>

						<h2>', $txt['mysql_settings'], '</h2>
						<h4>', $txt['mysql_settings_info'], '</h4>

						<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 2ex;">
							<tr>
								<td width="20%" valign="top" class="textbox"><label for="db_server">', $txt['mysql_settings_server'], ':</label></td>
								<td>
									<input type="text" name="db_server" id="db_server" value="', $db_server, '" size="30" /><br />
									<div style="font-size: smaller; margin-bottom: 2ex;">', $txt['mysql_settings_server_info'], '</div>
								</td>
							</tr><tr>
								<td valign="top" class="textbox"><label for="db_user">', $txt['mysql_settings_username'], ':</label></td>
								<td>
									<input type="text" name="db_user" id="db_user" value="', $db_user, '" size="30" /><br />
									<div style="font-size: smaller; margin-bottom: 2ex;">', $txt['mysql_settings_username_info'], '</div>
								</td>
							</tr><tr>
								<td valign="top" class="textbox"><label for="db_passwd">', $txt['mysql_settings_password'], ':</label></td>
								<td>
									<input type="password" name="db_passwd" id="db_passwd" value="', $db_passwd, '" size="30" /><br />
									<div style="font-size: smaller; margin-bottom: 2ex;">', $txt['mysql_settings_password_info'], '</div>
								</td>
							</tr><tr>
								<td valign="top" class="textbox"><label for="db_name">', $txt['mysql_settings_database'], ':</label></td>
								<td>
									<input type="text" name="db_name" id="db_name" value="', empty($db_name) ? 'smf' : $db_name, '" size="30" /><br />
									<div style="font-size: smaller; margin-bottom: 2ex;">', $txt['mysql_settings_database_info'], '</div>
								</td>
							</tr><tr>
								<td valign="top" class="textbox"><label for="db_prefix">', $txt['mysql_settings_prefix'], ':</label></td>
								<td>
									<input type="text" name="db_prefix" id="db_prefix" value="smf_" size="30" /><br />
									<div style="font-size: smaller; margin-bottom: 2ex;">', $txt['mysql_settings_prefix_info'], '</div>
								</td>
							</tr>
						</table>

						<div align="right" style="margin: 1ex;"><input type="submit" value="', $txt['install_settings_proceed'], '" /></div>
					</form>
				</div>';

	return true;
}

// Step one: Do the SQL thang.
function doStep1()
{
	global $txt, $db_connection;

	if (substr($_POST['boardurl'], -10) == '/index.php')
		$_POST['boardurl'] = substr($_POST['boardurl'], 0, -10);
	elseif (substr($_POST['boardurl'], -1) == '/')
		$_POST['boardurl'] = substr($_POST['boardurl'], 0, -1);

	// Take care of these vars.
	$vars = array(
		'boardurl' => $_POST['boardurl'],
		'boarddir' => addslashes(dirname(__FILE__)),
		'sourcedir' => addslashes(dirname(__FILE__)) . '/Sources',
		'db_name' => $_POST['db_name'],
		'db_user' => $_POST['db_user'],
		'db_passwd' => isset($_POST['db_passwd']) ? $_POST['db_passwd'] : '',
		'db_server' => $_POST['db_server'],
		'db_prefix' => preg_replace('~[^A-Za-z0-9_$]~', '', $_POST['db_prefix']),
		'mbname' => $_POST['mbname'],
		'language' => substr($_SESSION['smf_install_lang'], 8, -4)
	);

	if (!updateSettingsFile($vars) && substr(__FILE__, 1, 2) == ':\\')
	{
		echo '
				<div class="error_message">
					<div style="color: red;">', $txt['error_windows_chmod'], '</div>
					<br />
					<a href="', $_SERVER['PHP_SELF'], '?step=0&amp;overphp=true">', $txt['error_message_click'], '</a> ', $txt['error_message_try_again'], '
				</div>';

		return false;
	}

	// Make sure it works.
	require(dirname(__FILE__) . '/Settings.php');

	// Attempt a connection.
	$db_connection = @mysql_connect($db_server, $db_user, $db_passwd);

	// No dice?  Let's try adding the prefix they specified, just in case they misread the instructions ;).
	if (!$db_connection)
	{
		$mysql_error = mysql_error();

		$db_connection = @mysql_connect($db_server, $_POST['db_prefix'] . $db_user, $db_passwd);
		if ($db_connection != false)
		{
			$db_user = $_POST['db_prefix'] . $db_user;
			updateSettingsFile(array('db_user' => $db_user));
		}
	}

	// Still no connection?  Big fat error message :P.
	if (!$db_connection)
	{
		echo '
				<div class="error_message">
					<div style="color: red;">', $txt['error_mysql_connect'], '</div>

					<div style="margin: 2.5ex; font-family: monospace;"><b>', $mysql_error, '</b></div>

					<a href="', $_SERVER['PHP_SELF'], '?step=0&amp;overphp=true">', $txt['error_message_click'], '</a> ', $txt['error_message_try_again'], '
				</div>';
		return false;
	}

	// Do they meet the install requirements?
	if (!mysql_version_check())
	{
		echo '
				<div class="error_message">
					<div style="color: red;">', $txt['error_mysql_too_low'], '</div>
					<br />
					<a href="', $_SERVER['PHP_SELF'], '?step=0&amp;overphp=true">', $txt['error_message_click'], '</a> ', $txt['error_message_try_again'], '
				</div>';

		return false;
	}

	// Let's try that database on for size...
	if ($db_name != '')
		mysql_query("
			CREATE DATABASE IF NOT EXISTS `$db_name`", $db_connection);

	// Okay, let's try the prefix if it didn't work...
	if (!mysql_select_db($db_name, $db_connection) && $db_name != '')
	{
		mysql_query("
			CREATE DATABASE IF NOT EXISTS `$_POST[db_prefix]$db_name`", $db_connection);

		if (mysql_select_db($_POST['db_prefix'] . $db_name, $db_connection))
		{
			$db_name = $_POST['db_prefix'] . $db_name;
			updateSettingsFile(array('db_name' => $db_name));
		}
	}

	// Okay, now let's try to connect...
	if (!mysql_select_db($db_name, $db_connection))
	{
		echo '
				<div class="error_message">
					<div style="color: red;">', sprintf($txt['error_mysql_database'], $db_name), '</div>
					<br />
					<a href="', $_SERVER['PHP_SELF'], '?step=0&amp;overphp=true">', $txt['error_message_click'], '</a> ', $txt['error_message_try_again'], '
				</div>';

		return false;
	}

	// Read in the SQL.  Turn this on and that off... internationalize... etc.
	$sql_lines = strtr(implode(' ', file(dirname(__FILE__) . '/' . $GLOBALS['current_smf_version'] . '.sql')), array(
		'{$db_prefix}' => $db_prefix,
		'{$boarddir}' => addslashes(dirname(__FILE__)),
		'{$boardurl}' => $_POST['boardurl'],
		'{$enableCompressedOutput}' => isset($_POST['compress']) ? 1 : 0,
		'{$databaseSession_enable}' => isset($_POST['dbsession']) ? 1 : 0,
		'{$default_topic_subject}' => addslashes($txt['default_topic_subject']),
		'{$default_topic_message}' => addslashes($txt['default_topic_message']),
		'{$default_board_name}' => addslashes($txt['default_board_name']),
		'{$default_board_description}' => addslashes($txt['default_board_description']),
		'{$default_category_name}' => addslashes($txt['default_category_name']),
		'{$default_time_format}' => addslashes($txt['default_time_format']),
	));
	$sql_lines = explode("\n", $sql_lines);

	// Execute the SQL.
	$current_statement = '';
	$failures = array();
	$exists = array();
	foreach ($sql_lines as $count => $line)
	{
		// No comments allowed!
		if (substr(trim($line), 0, 1) != '#')
			$current_statement .= "\n" . rtrim($line);

		// Is this the end of the query string?
		if (empty($current_statement) || (preg_match('~;[\s]*$~s', $line) == 0 && $count != count($sql_lines)))
			continue;

		// Does this table already exist?  If so, don't insert more data into it!
		if (preg_match('~^\s*INSERT INTO ([^\s\n\r]+?)~', $current_statement, $match) != 0 && in_array($match[1], $exists))
		{
			$current_statement = '';
			continue;
		}

		if (!mysql_query($current_statement))
		{
			$error_message = mysql_error($db_connection);

			// Error 1050: Table already exists!
			if (strpos($error_message, 'already exists') === false)
				$failures[$count] = $error_message;
			elseif (preg_match('~^\s*CREATE TABLE ([^\s\n\r]+?)~', $current_statement, $match) != 0)
				$exists[] = $match[1];
		}

		$current_statement = '';
	}

	// Let's optimize those new tables.
	$tables = mysql_list_tables($db_name);
	$table_names = array();
	while ($table = mysql_fetch_row($tables))
		$table_names[] = $table[0];
	mysql_free_result($tables);

	mysql_query('
		OPTIMIZE TABLE `' . implode('`, `', $table_names) . '`') or $db_messed = true;
	if (!empty($db_messed))
		$failures[-1] = mysql_error($db_connection);

	if (!empty($failures))
	{
		echo '
				<div class="error_message">
					<div style="color: red;">', $txt['error_mysql_queries'], '</div>
					<div style="margin: 2.5ex;">';

		foreach ($failures as $line => $fail)
			echo '
						<b>', $txt['error_mysql_queries_line'], $line + 1, ':</b> ', nl2br(htmlspecialchars($fail)), '<br />';

		echo '
					</div>
					<a href="', $_SERVER['PHP_SELF'], '?step=0&amp;overphp=true">', $txt['error_message_click'], '</a> ', $txt['error_message_try_again'], '
				</div>';

		return false;
	}

	if (!empty($exists))
		echo '
				<div class="panel">
					<h2>', $txt['user_refresh_install'], '</h2>
					', $txt['user_refresh_install_desc'], '
				</div>';

	return doStep2a();
}

// Step two-A: Ask for the administrator login information.
function doStep2a()
{
	global $txt;

	if (!isset($_POST['username']))
		$_POST['username'] = '';
	if (!isset($_POST['email']))
		$_POST['email'] = '';

	echo '
				<div class="panel">
					<form action="' . $_SERVER['PHP_SELF'] . '?step=2" method="post" onsubmit="if (this.password1.value == this.password2.value) return true; else {alert(\'', $txt['error_user_settings_again_match'], '\'); return false;}">
						<h2>', $txt['user_settings'], '</h2>
						<h4>', $txt['user_settings_info'], '</h4>

						<table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 2ex;">
							<tr>
								<td width="18%" valign="top" class="textbox"><label for="username">', $txt['user_settings_username'], ':</label></td>
								<td>
									<input type="text" name="username" id="username" value="', $_POST['username'], '" size="40" />
									<div style="font-size: smaller; margin-bottom: 2ex;">', $txt['user_settings_username_info'], '</div>
								</td>
							</tr><tr>
								<td valign="top" class="textbox"><label for="password1">', $txt['user_settings_password'], ':</label></td>
								<td>
									<input type="password" name="password1" id="password1" size="40" />
									<div style="font-size: smaller; margin-bottom: 2ex;">', $txt['user_settings_password_info'], '</div>
								</td>
							</tr><tr>
								<td valign="top" class="textbox"><label for="password2">', $txt['user_settings_again'], ':</label></td>
								<td>
									<input type="password" name="password2" id="password2" size="40" />
									<div style="font-size: smaller; margin-bottom: 2ex;">', $txt['user_settings_again_info'], '</div>
								</td>
							</tr><tr>
								<td valign="top" class="textbox"><label for="email">', $txt['user_settings_email'], ':</label></td>
								<td>
									<input type="text" name="email" id="email" value="', $_POST['email'], '" size="40" />
									<div style="font-size: smaller; margin-bottom: 2ex;">', $txt['user_settings_email_info'], '</div>
								</td>
							</tr>
						</table>

						<h2>', $txt['user_settings_database'], '</h2>
						<h4>', $txt['user_settings_database_info'], '</h4>

						<div style="margin-bottom: 2ex; padding-left: 17%;">
							<input type="password" name="password3" size="30" />
						</div>

						<div align="right" style="margin: 1ex;"><input type="submit" value="', $txt['user_settings_proceed'], '" /></div>
					</form>
				</div>';

	return true;
}

// Step two: Create the administrator, and finish.
function doStep2()
{
	global $txt, $db_prefix, $db_connection, $HTTP_SESSION_VARS, $cookiename;

	// Load the SQL server login information.
	require_once(dirname(__FILE__) . '/Settings.php');

	if (!isset($_POST['password3']))
		return doStep2a();

	$db_connection = @mysql_connect($db_server, $db_user, $_POST['password3']);
	if (!$db_connection)
	{
		echo '
				<div class="error_message">
					<div style="color: red;">', $txt['error_mysql_connect'], '</div>
				</div>';

		return doStep2a();
	}
	if (!mysql_select_db($db_name, $db_connection))
	{
		echo '
				<div class="error_message">
					<div style="color: red;">', sprintf($txt['error_mysql_database'], $db_name), '</div>
				</div>
				<br />';

		return doStep2a();
	}

	// Let them try again...
	if ($_POST['password1'] != $_POST['password2'])
	{
		echo '
				<div class="error_message">
					<div style="color: red;">', $txt['error_user_settings_again_match'], '</div>
				</div>
				<br />';

		return doStep2a();
	}

	if (!file_exists($sourcedir . '/Subs.php'))
	{
		echo '
				<div class="error_message">
					<div style="color: red;">', $txt['error_subs_missing'], '</div>
				</div>
				<br />';

		return doStep2a();
	}

	updateSettingsFile(array('webmaster_email' => $_POST['email']));

	chdir(dirname(__FILE__));

	define('SMF', 1);
	require_once($sourcedir . '/Subs.php');
	require_once($sourcedir . '/Subs-Auth.php');

	$result = mysql_query("
		SELECT ID_MEMBER
		FROM {$db_prefix}members
		WHERE memberName = '$_POST[username]' OR emailAddress = '$_POST[email]'
		LIMIT 1");
	if (mysql_num_rows($result) != 0)
	{
		list ($id) = mysql_fetch_row($result);
		mysql_free_result($result);

		echo '
				<div class="error_message">
					<div style="color: red;">', $txt['error_user_settings_taken'], '</div>
				</div>
				<br />';
	}
	elseif ($_POST['username'] != '')
	{
		$result = mysql_query("
			INSERT INTO {$db_prefix}members
				(memberName, realName, passwd, emailAddress, ID_GROUP, posts, personalText, avatar, dateRegistered, hideEmail)
			VALUES ('$_POST[username]', '$_POST[username]', '" . md5_hmac($_POST['password1'], strtolower($_POST['username'])) . "', '$_POST[email]', 1, '0', '', '', '" . time() . "', '0')");

		// Awww, crud!
		if (!$result)
		{
			echo '
				<div class="error_message">
					<div style="color: red;">', $txt['error_user_settings_query'], '</div>

					<div style="margin: 2ex;">', nl2br(htmlspecialchars(mysql_error($db_connection))), '</div>

					<a href="', $_SERVER['PHP_SELF'], '?step=2">', $txt['error_message_click'], '</a> ', $txt['error_message_try_again'], '
				</div>';

			return false;
		}

		$id = mysql_insert_id();
	}

	// Automatically log them in ;).
	if (isset($id))
		setLoginCookie(3153600 * 60, $id, md5_hmac($_POST['password1'], strtolower($_POST['username'])));

	$result = mysql_query("
		SELECT value
		FROM {$db_prefix}settings
		WHERE variable = 'databaseSession_enable'");
	if (mysql_num_rows($result) != 0)
		list ($db_sessions) = mysql_fetch_row($result);
	mysql_free_result($result);

	if (empty($db_sessions))
	{
		if (@version_compare(PHP_VERSION, '4.2.0') == -1)
			$HTTP_SESSION_VARS['php_412_bugfix'] = true;
		$_SESSION['admin_time'] = time();
	}
	else
	{
		if (get_magic_quotes_gpc() == 0)
			$_SERVER['HTTP_USER_AGENT'] = addslashes($_SERVER['HTTP_USER_AGENT']);

		mysql_query("
			INSERT INTO {$db_prefix}sessions
				(session_id, last_update, data)
			VALUES ('" . session_id() . "', " . time() . ",
				'USER_AGENT|s:" . strlen($_SERVER['HTTP_USER_AGENT']) . ":\"$_SERVER[HTTP_USER_AGENT]\";admin_time|i:" . time() . ";')");
	}

	updateStats('member');
	updateStats('message');
	updateStats('topic');

	echo '
				<div class="panel">
					<h2>', $txt['congratulations'], '</h2>
					<br />
					', $txt['congratulations_help'], '<br />
					<br />';

	if (is_writable(dirname(__FILE__)) && substr(__FILE__, 1, 2) != ':\\')
		echo '
					<i>', $txt['still_writable'], '</i><br />
					<br />';

	if (is_writable(__FILE__))
		echo '
					<div style="margin: 1ex; font-weight: bold;">
						<label for="delete_self"><input type="checkbox" id="delete_self" onclick="doTheDelete();" /> ', $txt['delete_installer'], '</label>
					</div>
					<script language="JavaScript" type="text/javascript"><!--
						function doTheDelete()
						{
							var theImage = document.getElementById ? document.getElementById("delete_installer") : document.all.delete_installer;
							var theCheck = document.getElementById ? document.getElementById("delete_self") : document.all.delete_self;

							theImage.src = "', $_SERVER['PHP_SELF'], '?delete&" + (new Date().getTime());
							theCheck.disabled = true;
						}
					// --></script>
					<img src="', $boardurl, '/Themes/default/images/blank.gif" alt="" id="delete_installer" /><br />
					<br />';

	echo '
					', sprintf($txt['go_to_your_forum'], $boardurl . '/index.php'), '<br />
					<br />
					', $txt['good_luck'], '
				</div>';

	return true;
}

function php_version_check()
{
	$minver = explode('.', $GLOBALS['required_php_version']);
	$curver = explode('.', PHP_VERSION);

	return !(($curver[0] <= $minver[0]) && ($curver[1] <= $minver[1]) && ($curver[1] <= $minver[1]) && ($curver[2][0] < $minver[2][0]));
}

function mysql_version_check()
{
	$minver = explode('.', $GLOBALS['required_mysql_version']);
	$curver = mysql_get_server_info() < mysql_get_client_info() ? mysql_get_server_info() : mysql_get_client_info();

	if (strpos($curver, '-') !== false)
		$curver = substr($curver, 0, strpos($curver, '-'));
	$curver = explode('.', $curver);

	return !(($curver[0] <= $minver[0]) && ($curver[1] <= $minver[1]) && ($curver[1] <= $minver[1]) && ($curver[2] < $minver[2]));
}

// MD5 Encryption.
function md5_hmac($data, $key)
{
	if (strlen($key) > 64)
		$key = pack('H*', md5($key));
	$key  = str_pad($key, 64, chr(0x00));

	$k_ipad = $key ^ str_repeat(chr(0x36), 64);
	$k_opad = $key ^ str_repeat(chr(0x5c), 64);

	return md5($k_opad . pack('H*', md5($k_ipad . $data)));
}

// http://www.faqs.org/rfcs/rfc959.html
class ftp_connection
{
	var $connection = 'no_connection', $error = false;

	// Create a new FTP connection...
	function ftp_connection($ftp_server, $ftp_port, $ftp_user, $ftp_pass)
	{
		// Connect to the FTP server.
		$this->connection = @fsockopen($ftp_server, $ftp_port, $err, $err, 5);
		if (!$this->connection)
		{
			$this->error = 'bad_server';
			return;
		}

		// Get the welcome message...
		if (!$this->check_response(220))
		{
			echo $this->error = 'bad_response';
			return;
		}

		// Send the username, it should ask for a password.
		fwrite($this->connection, 'USER ' . $ftp_user . "\r\n");
		if (!$this->check_response(331))
		{
			$this->error = 'bad_username';
			return;
		}

		// Now send the password... and hope it goes okay.
		fwrite($this->connection, 'PASS ' . $ftp_pass . "\r\n");
		if (!$this->check_response(230))
		{
			$this->error = 'bad_password';
			return;
		}
	}

	function chdir($ftp_path)
	{
		if (!is_resource($this->connection))
			return false;

		// No slash on the end, please...
		if (substr($ftp_path, -1) == '/')
			$ftp_path = substr($ftp_path, 0, -1);

		fwrite($this->connection, 'CWD ' . $ftp_path . "\r\n");
		if (!$this->check_response(250))
		{
			$this->error = 'bad_path';
			return false;
		}

		return true;
	}

	function chmod($ftp_file, $chmod)
	{
		if (!is_resource($this->connection))
			return false;

		// Convert the chmod value from octal (0777) to text ("777").
		fwrite($this->connection, 'SITE CHMOD ' . decoct($chmod) . ' ' . $ftp_file . "\r\n");
		if (!$this->check_response(200))
		{
			$this->error = 'bad_file';
			return false;
		}

		return true;
	}

	function unlink($ftp_file)
	{
		// We are actually connected, right?
		if (!is_resource($this->connection))
			return false;

		// Delete file X.
		fwrite($this->connection, 'DELE ' . $ftp_file . "\r\n");
		if (!$this->check_response(250))
		{
			$this->error = 'bad_file';
			return false;
		}

		return true;
	}

	function check_response($desired)
	{
		// Wait for a response that isn't continued with -, but don't wait too long.
		$time = time();
		do
			$response = fgets($this->connection, 1024);
		while (substr($response, 3, 1) != ' ' && time() - $time < 5);

		// Was the desired response returned?
		return substr($response, 0, 3) == $desired;
	}

	function close()
	{
		// Goodbye!
		fwrite($this->connection, "QUIT\r\n");
		fclose($this->connection);

		return true;
	}
}

function make_files_writable()
{
	global $txt;

	$writable_files = array(
		'attachments',
		'avatars',
		'Packages',
		'Packages/installed.list',
		'Packages/server.list',
		'Smileys',
		'Themes',
		'agreement.txt',
		'Settings.php',
		'Settings_bak.php'
	);
	$extra_files = array(
		'Themes/classic/index.template.php',
		'Themes/classic/style.css'
	);
	foreach ($GLOBALS['detected_languages'] as $lang => $temp)
		$extra_files[] = 'Themes/default/languages/' . $lang;

	$failure = false;

	// On linux, it's easy - just use is_writable!
	if (substr(__FILE__, 1, 2) != ':\\')
	{
		foreach ($writable_files as $file)
			$failure |= !is_writable(dirname(__FILE__) . '/' . $file) && !@chmod(dirname(__FILE__) . '/' . $file, 0777);
		foreach ($extra_files as $file)
			@chmod(dirname(__FILE__) . '/' . $file, 0777);
	}
	// Windows is trickier.  Let's try opening for r+...
	else
	{
		foreach ($writable_files as $file)
		{
			// Folders can't be opened for write... but the index.php in them can ;).
			if (is_dir(dirname(__FILE__) . '/' . $file))
				$file .= '/index.php';

			// Funny enough, chmod actually does do something on windows - it removes the read only attribute.
			@chmod(dirname(__FILE__) . '/' . $file, 0777);
			$fp = @fopen(dirname(__FILE__) . '/' . $file, 'r+');

			// Hmm, okay, try just for write in that case...
			if (!$fp)
				$fp = @fopen(dirname(__FILE__) . '/' . $file, 'w');

			$failure |= !$fp;
			@fclose($fp);
		}
		foreach ($extra_files as $file)
			@chmod(dirname(__FILE__) . '/' . $file, 0777);
	}

	if (!isset($_SERVER))
		return !$failure;

	// It's not going to be possible to use FTP on windows to solve the problem...
	if ($failure && substr(__FILE__, 1, 2) == ':\\')
	{
		echo '
				<div class="error_message">
					<div style="color: red;">', $txt['error_windows_chmod'], '</div>
					<ul style="margin: 2.5ex; font-family: monospace;">
						<li>', implode('</li>
						<li>', $writable_files), '</li>
					</ul>
					<a href="', $_SERVER['PHP_SELF'], '?step=0&amp;overphp=true">', $txt['error_message_click'], '</a> ', $txt['error_message_try_again'], '
				</div>';

		return false;
	}
	// We're going to have to use... FTP!
	elseif ($failure)
	{
		if (isset($_POST['ftp_username']))
		{
			// Strip off the schema if it is given, we don't want/need it.
			if (substr($_POST['ftp_server'], 0, 6) == 'ftp://')
				$_POST['ftp_server'] = substr($_POST['ftp_server'], 6);
			// Okay, it doesn't work in most cases but we'll try.
			elseif (substr($_POST['ftp_server'], 0, 7) == 'ftps://')
				$_POST['ftp_server'] = 'ssl://' . substr($_POST['ftp_server'], 7);
			elseif (substr($_POST['ftp_server'], 0, 7) == 'http://')
				$_POST['ftp_server'] = substr($_POST['ftp_server'], 7);
			$_POST['ftp_server'] = strtr($_POST['ftp_server'], array('/' => '', ':' => '', '@' => ''));

			$ftp = new ftp_connection($_POST['ftp_server'], $_POST['ftp_port'], $_POST['ftp_username'], $_POST['ftp_password']);

			if ($ftp->error === false)
			{
				// Try it without /home/abc just in case they messed up.
				if (!$ftp->chdir($_POST['ftp_path']))
					$ftp->chdir(preg_replace('~^/home/[^/]+?~', '', $_POST['ftp_path']));

				$_SESSION['installer_temp_ftp'] = array(
					'server' => $_POST['ftp_server'],
					'port' => $_POST['ftp_port'],
					'username' => $_POST['ftp_username'],
					'password' => $_POST['ftp_password'],
					'path' => $_POST['ftp_path']
				);
			}
		}

		if (!isset($ftp) || $ftp->error !== false)
		{
			if (!isset($_POST['ftp_path']))
			{
				if (preg_match('~^/home/([^/]+?)/public_html~', $_SERVER['DOCUMENT_ROOT'], $match))
				{
					if (!isset($_POST['ftp_username']))
						$_POST['ftp_username'] = $match[1];

					$_POST['ftp_path'] = strtr($_SERVER['DOCUMENT_ROOT'], array('/home/' . $match[1] => ''));

					if (substr($_POST['ftp_path'], -1) == '/')
						$_POST['ftp_path'] = substr($_POST['ftp_path'], 0, -1);

					if (strlen(dirname($_SERVER['PHP_SELF'])) > 1)
						$_POST['ftp_path'] .= dirname($_SERVER['PHP_SELF']);
				}
				else
					$_POST['ftp_path'] = strtr(dirname(__FILE__), array($_SERVER['DOCUMENT_ROOT'] => ''));
			}

			echo '
				<div class="panel">
					<h2>', $txt['ftp_setup'], '</h2>
					<h4>', $txt['ftp_setup_info'], '</h4>';

			if (isset($ftp))
				echo '
					<div class="error_message">
						<div style="color: red;">', $txt['error_ftp_no_connect'], '</div>
					</div>
					<br />';

			echo '
					<form action="', $_SERVER['PHP_SELF'], '?step=0&amp;overphp=true" method="post">

						<table width="520" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 1ex;">
							<tr>
								<td width="26%" valign="top" class="textbox"><label for="ftp_server">', $txt['ftp_server'], ':</label></td>
								<td>
									<div style="float: right; margin-right: 1px;"><label for="ftp_port" class="textbox"><b>', $txt['ftp_port'], ':&nbsp;</b></label> <input type="text" size="3" name="ftp_port" id="ftp_port" value="', isset($_POST['ftp_port']) ? $_POST['ftp_port'] : '21', '" /></div>
									<input type="text" size="30" name="ftp_server" id="ftp_server" value="', isset($_POST['ftp_server']) ? $_POST['ftp_server'] : 'localhost', '" style="width: 70%;" />
									<div style="font-size: smaller; margin-bottom: 2ex;">', $txt['ftp_server_info'], '</div>
								</td>
							</tr><tr>
								<td width="26%" valign="top" class="textbox"><label for="ftp_username">', $txt['ftp_username'], ':</label></td>
								<td>
									<input type="text" size="50" name="ftp_username" id="ftp_username" value="', isset($_POST['ftp_username']) ? $_POST['ftp_username'] : '', '" style="width: 99%;" />
									<div style="font-size: smaller; margin-bottom: 2ex;">', $txt['ftp_username_info'], '</div>
								</td>
							</tr><tr>
								<td width="26%" valign="top" class="textbox"><label for="ftp_password">', $txt['ftp_password'], ':</label></td>
								<td>
									<input type="password" size="50" name="ftp_password" id="ftp_password" style="width: 99%;" />
									<div style="font-size: smaller; margin-bottom: 3ex;">', $txt['ftp_password_info'], '</div>
								</td>
							</tr><tr>
								<td width="26%" valign="top" class="textbox"><label for="ftp_path">', $txt['ftp_path'], ':</label></td>
								<td style="padding-bottom: 1ex;">
									<input type="text" size="50" name="ftp_path" id="ftp_path" value="', $_POST['ftp_path'], '" style="width: 99%;" />
									<div style="font-size: smaller; margin-bottom: 2ex;">', $txt['ftp_path_info'], '</div>
								</td>
							</tr>
						</table>

						<div align="right" style="margin: 1ex; margin-top: 2ex;"><input type="submit" value="', $txt['ftp_connect'], '" /></div>
					</form>

					<h2>', $txt['ftp_setup_why'], '</h2>
					<h4>', $txt['ftp_setup_why_info'], '</h4>

					<ul style="margin: 2.5ex; font-family: monospace;">
						<li>', implode('</li>
						<li>', $writable_files), '</li>
					</ul>
					<a href="', $_SERVER['PHP_SELF'], '?step=0&amp;overphp=true">', $txt['error_message_click'], '</a> ', $txt['ftp_setup_again'], '

				</div>';

			return false;
		}
		else
		{
			foreach ($writable_files as $file)
				$ftp->chmod($file, 0777);

			$ftp->close();
		}
	}

	return true;
}

function updateSettingsFile($vars)
{
	// We're modifying Settings.php!
	$settingsArray = file(dirname(__FILE__) . '/Settings.php');

	if (count($settingsArray) == 1)
		$settingsArray = preg_split('~[\r\n]~', $settingsArray[0]);

	for ($i = 0, $n = count($settingsArray); $i < $n; $i++)
	{
		$settingsArray[$i] = rtrim($settingsArray[$i]);

		// Remove the redirect...
		if ($settingsArray[$i] == 'if (file_exists(dirname(__FILE__) . \'/install.php\'))')
		{
			$settingsArray[$i] = '';
			$settingsArray[$i++] = '';
			$settingsArray[$i++] = '';
			continue;
		}
		elseif (substr(trim($settingsArray[$i]), -16) == '/install.php\');' && substr(trim($settingsArray[$i])) == 'header(\'Location: http://\'')
		{
			$settingsArray[$i] = '';
			continue;
		}

		if (trim($settingsArray[$i]) == '?' . '>')
			$settingsArray[$i] = '';

		foreach ($vars as $var => $val)
			if (strncasecmp($settingsArray[$i], '$' . $var, 1 + strlen($var)) == 0)
			{
				$comment = strstr($settingsArray[$i], '#');
				$settingsArray[$i] = '$' . $var . ' = \'' . $val . '\';' . ($comment != '' ? "\t\t" . $comment : '');
				unset($vars[$var]);
			}
	}

	// Uh oh... the file wasn't empty... was it?
	if (!empty($vars))
	{
		$settingsArray[$i++] = '';
		foreach ($vars as $var => $val)
			$settingsArray[$i++] = '$' . $var . ' = \'' . $val . '\';';
	}

	// Blank out the file - done to fix a oddity with some servers.
	$fp = @fopen(dirname(__FILE__) . '/Settings.php', 'w');
	if (!$fp)
		return false;
	fclose($fp);

	$fp = fopen(dirname(__FILE__) . '/Settings.php', 'r+');

	// Gotta have one of these ;).
	if (trim($settingsArray[0]) != '<?php')
		fwrite($fp, "<?php\n");

	$lines = count($settingsArray);
	for ($i = 0; $i < $lines - 1; $i++)
	{
		// Don't just write a bunch of blank lines.
		if ($settingsArray[$i] != '' || @$settingsArray[$i - 1] != '')
			fwrite($fp, $settingsArray[$i] . "\n");
	}
	fwrite($fp, $settingsArray[$i] . '?' . '>');
	fclose($fp);

	return true;
}

?>