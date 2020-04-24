<?php
/******************************************************************************
* Reminder.php                                                                *
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

// Forgot 'yer password?
function RemindMe()
{
	global $txt, $context;

	loadTemplate('Reminder');
	loadLanguage('Profile');

	$context['page_title'] = $context['forum_name'] . ' ' . $txt[669];

	// Delegation can be useful sometimes.
	$subActions = array(
		'mail' => 'RemindMail',
		'secret' => 'secretAnswerInput',
		'secret2' => 'secretAnswer2',
		'setpassword' =>'setPassword',
		'setpassword2' =>'setPassword2'
	);

	// Any subaction?  If none, fall through to the main template, which will ask for one.
	if (isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]))
		$subActions[$_REQUEST['sa']]();
}

// Email a reminder.
function RemindMail()
{
	global $db_prefix, $context, $txt, $scripturl, $sourcedir, $user_info;

	checkSession();

	// You must enter a username/email address.
	if (!isset($_POST['user']) || $_POST['user'] == '')
		fatal_lang_error(40, false);

	// Username or Email?
	$searchField = !isset($_POST['searchtype']) || $_POST['searchtype'] == 'usearch' ? 'memberName' : 'emailAddress';

	// Find the user.
	$request = db_query("
		SELECT ID_MEMBER, realName, memberName, emailAddress, is_activated, validation_code
		FROM {$db_prefix}members
		WHERE $searchField = '$_POST[user]'
		LIMIT 1", __FILE__, __LINE__);
	if (mysql_num_rows($request) == 0)
		fatal_lang_error(40, false);
	$row = mysql_fetch_assoc($request);
	mysql_free_result($request);

	// If the user isn't activated/approved, give them some feedback on what to do next.
	if (empty($row['is_activated']))
	{
		// Awaiting approval...
		if (trim($row['validation_code']) == '')
			fatal_error($txt['registration_not_approved'] . ' <a href="' . $scripturl . '?action=activate;user=' . $_POST['user'] . '">' . $txt[662] . '</a>.', false);
		else
			fatal_error($txt['registration_not_activated'] . ' <a href="' . $scripturl . '?action=activate;user=' . $_POST['user'] . '">' . $txt[662] . '</a>.', false);
	}

	// You can't get emailed if you have no email address.
	$row['emailAddress'] = trim($row['emailAddress']);
	if ($row['emailAddress'] == '')
		mailprog_error();

	// Randomly generate a new password, with only alpha numeric characters that is a max length of 10 chars.
	$password = substr(preg_replace('/\W/', '', md5(rand())), 0, 10);

	// Set the password in the database.
	db_query("
		UPDATE {$db_prefix}members
		SET validation_code = '" . md5($password) . "'
		WHERE $searchField = '$_POST[user]'
		LIMIT 1", __FILE__, __LINE__);

	require_once($sourcedir . '/Subs-Post.php');

	sendmail($row['emailAddress'], $txt['reminder_subject'],
		sprintf($txt['sendtopic_dear'], $row['realName']) . "\n\n" .
		"$txt[reminder_mail]:\n\n" .
		"$scripturl?action=reminder;sa=setpassword;u=$row[ID_MEMBER];code=$password\n\n" .
		"$txt[512]: $user_info[ip]\n\n" .
		$txt[35] . ': ' . $row['memberName'] . "\n\n" .
		$txt[130]);

	// Set up the template.
	$context += array(
		'page_title' => &$txt[194],
		'sub_template' => 'sent',
		'description' => &$txt['reminder_sent']
	);
}

// Set your new password
function setPassword()
{
	global $txt, $context;

	// You need a code!
	if (!isset($_REQUEST['code']))
		fatal_lang_error(1);

	// Fill the context array.
	$context += array(
		'page_title' => &$txt['reminder_set_password'],
		'sub_template' => 'set_password',
		'code' => $_REQUEST['code'],
		'memID' => (int) $_REQUEST['u']
	);
}

function setPassword2()
{
	global $db_prefix, $context, $txt;

	if (empty($_POST['u']) || !isset($_POST['passwrd1']) || !isset($_POST['passwrd2']))
		fatal_lang_error(1, false);

	$_POST['u'] = (int) $_POST['u'];

	if ($_POST['passwrd1'] !=  $_POST['passwrd2'])
		fatal_lang_error(213, false);

	if ($_POST['passwrd1'] == '')
		fatal_lang_error(91, false);

	loadLanguage('Login');

	// Get the code as it should be from the database.
	$request = db_query("
		SELECT validation_code, memberName
		FROM {$db_prefix}members
		WHERE ID_MEMBER = $_POST[u]
			AND is_activated = 1
			AND validation_code != ''
		LIMIT 1", __FILE__, __LINE__);

	// Does this user exist at all?
	if (mysql_num_rows($request) == 0)
		fatal_lang_error('invalid_userid', false);

	list ($realCode, $username) = mysql_fetch_row($request);

	// Quit if this code is not right.
	if (empty($_POST['code']) || substr($realCode, 0, 10) != substr(md5($_POST['code']), 0, 10))
		fatal_error($txt['invalid_activation_code'], false);

	// User validated.  Update the database!
	updateMemberData($_POST['u'], array('validation_code' => '\'\'', 'passwd' => '\'' . md5_hmac($_POST['passwrd1'], strtolower($username)) . '\''));

	loadTemplate('Login');
	$context += array(
		'page_title' => &$txt['reminder_password_set'],
		'sub_template' => 'login',
		'default_username' => $username,
		'default_password' => $_POST['passwrd1'],
		'never_expire' => false,
		'description' => &$txt['reminder_password_set']
	);
}

// Get the secret answer.
function secretAnswerInput()
{
	global $txt, $db_prefix, $context;

	checkSession();

	// Please provide an email or user....
	if (!isset($_POST['user']) || $_POST['user'] == '')
		fatal_lang_error(40, false);

	// Which of these are we doing?
	$searchField = !isset($_POST['searchtype']) || $_POST['searchtype'] == 'usearch' ? 'memberName' : 'emailAddress';

	// Get the stuff....
	$request = db_query("
		SELECT realName, memberName, secretQuestion
		FROM {$db_prefix}members
		WHERE $searchField = '$_POST[user]'
		LIMIT 1", __FILE__, __LINE__);
	if (mysql_num_rows($request) == 0)
		fatal_lang_error(40, false);
	$row = mysql_fetch_assoc($request);
	mysql_free_result($request);

	// If there is NO secret question - then throw an error.
	if (trim($row['secretQuestion']) == '')
		fatal_lang_error('registration_no_secret_question', false);

	// Ask for the answer...
	$context['remind_user'] = $_POST['user'];
	$context['remind_type'] = $_POST['searchtype'];
	$context['secret_question'] = $row['secretQuestion'];

	$context['sub_template'] = 'ask';
}

function secretAnswer2()
{
	global $txt, $db_prefix, $context, $modSettings;

	checkSession();

	// Hacker?  How did you get this far without an email or username?
	if (!isset($_POST['user']) || $_POST['user'] == '')
		fatal_lang_error(40, false);

	loadLanguage('Login');

	// Figure out which field to use...
	$searchField = !isset($_POST['searchtype']) || $_POST['searchtype'] == 'usearch' ? 'memberName' : 'emailAddress';

	// Get the information from the database.
	$request = db_query("
		SELECT memberName, secretAnswer, secretQuestion
		FROM {$db_prefix}members
		WHERE $searchField = '$_POST[user]'
		LIMIT 1", __FILE__, __LINE__);
	if (mysql_num_rows($request) == 0)
		fatal_lang_error(40, false);
	$row = mysql_fetch_assoc($request);
	mysql_free_result($request);

	// Check if the secret answer is correct.
	if ($row['secretQuestion'] == '' || $row['secretAnswer'] == '' || md5(stripslashes($_POST['secretAnswer'])) != $row['secretAnswer'])
	{
		log_error(sprintf($txt['reminder_error'], $row['memberName']));
		fatal_lang_error('pswd7', false);
	}

	// You can't use a blank one!
	if (!strlen(trim($_POST['passwrd1'])))
		fatal_lang_error(38, false);

	// They have to be the same too.
	if ($_POST['passwrd1'] != $_POST['passwrd2'])
		fatal_lang_error(213, false);

	// Alright, so long as 'yer sure.
	db_query("
		UPDATE {$db_prefix}members
		SET passwd = '" . md5_hmac($_POST['passwrd1'], strtolower($row['memberName'])) . "'
		WHERE $searchField = '$_POST[user]'
		LIMIT 1", __FILE__, __LINE__);

	// Tell them it went fine.
	loadTemplate('Login');
	$context += array(
		'page_title' => &$txt['reminder_password_set'],
		'sub_template' => 'login',
		'default_username' => $row['memberName'],
		'default_password' => $_POST['passwrd1'],
		'never_expire' => false,
		'description' => &$txt['reminder_password_set']
	);
}

// You don't have an email, where do you want it sent?!?
function mailprog_error()
{
	global $txt, $webmaster_email;

	fatal_error('
		<br />
		<div align="center">
			<b>' . $txt[394] . '<br />
			' . $txt[395] . ' <a href="mailto:' . $webmaster_email . '">webmaster</a> ' . $txt[396] . '.</b><br />
		</div>');
}

?>