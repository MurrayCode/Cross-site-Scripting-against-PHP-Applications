<?php
/******************************************************************************
* Register.php                                                                *
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

/*	This file has two main jobs, but they really are one.  It registers new
	members, and it helps the administrator moderate member registrations.
	Similarly, it handles account activation as well.
*/

// Begin the registration process.
function Register()
{
	global $txt, $boarddir, $context, $modSettings, $user_info, $db_prefix;

	// Check if the administrator has it disabled.
	if (!empty($modSettings['registration_method']) && $modSettings['registration_method'] == 3)
		fatal_lang_error('registration_disabled', false);

	// If this user is an admin - redirect them to the admin registration page.
	if ($user_info['is_admin'])
		redirectexit('action=regcenter;sa=register');
	// You are not a guest so you are a member - and members don't get to register twice!
	if (empty($user_info['is_guest']))
		redirectexit();

	loadTemplate('Register');
	loadLanguage('Login');

	// All the basic template information...
	$context['sub_template'] = 'before';
	$context['allow_hide_email'] = !empty($modSettings['allow_hideEmail']);
	$context['require_agreement'] = !empty($modSettings['requireAgreement']);

	$context['page_title'] = $txt[97];

	// If you have to agree to the agreement, it needs to be fetched from the file.
	if ($context['require_agreement'])
		$context['agreement'] = file_exists($boarddir . '/agreement.txt') ? nl2br(implode('', file($boarddir . '/agreement.txt'))) : '';
}

// Actually register the member.
function Register2()
{
	global $scripturl, $txt, $modSettings, $db_prefix, $context, $sourcedir, $user_info, $options, $settings;

	// If you're an admin, you're special ;).
	if (!$user_info['is_admin'])
	{
		spamProtection('register');

		// You can't register if it's disabled.
		if (!empty($modSettings['registration_method']) && $modSettings['registration_method'] == 3)
			fatal_lang_error('registration_disabled', false);

		// Well, if you don't agree, you can't register.
		if (!empty($modSettings['requireAgreement']) && (empty($_POST['regagree']) || $_POST['regagree'] == 'no'))
			redirectexit();

		// You cannot register twice...
		if (empty($user_info['is_guest']))
			redirectexit();

		// Make sure they came from *somewhere*, have a session, and didn't just register with this session.
		if (!isset($_SESSION['old_url']))
			redirectexit('action=register');
		if (!empty($_SESSION['just_registered']))
			fatal_lang_error(1, false);
	}

	require_once($sourcedir . '/Subs-Post.php');
	loadLanguage('Login');

	foreach ($_POST as $key => $value)
	{
		if (!is_array($_POST[$key]))
			$_POST[$key] = str_replace(array("\n", "\r"), '', trim($_POST[$key]));
	}

	// No name?!  How can you register with no name?
	if (!isset($_POST['user']) || trim($_POST['user']) == '')
		fatal_lang_error(37, false);

	// Trim any whitespace from the username.
	$_POST['user'] = trim($_POST['user']);

	// Don't use too long a name.
	if (strlen($_POST['user']) > 25)
		$_POST['user'] = substr($_POST['user'], 0, 25);

	// Only these characters are permitted.
	if (preg_match('~[<>&"\'=\\\]~', $_REQUEST['user']) != 0 || $_POST['user'] == '_' || $_POST['user'] == '|' || strpos($_POST['user'], '[code]') !== false || strpos($_POST['user'], '[/code]') !== false)
		fatal_lang_error(240, false);

	if (stristr($_POST['user'], $txt[28]) !== false)
		fatal_lang_error(244, true, array($txt[28]));

	if (empty($_POST['email']) || preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]+@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['email'])) == 0)
		fatal_error(sprintf($txt[500], $_POST['user']), false);

	if (isReservedName($_POST['user'], 0, false))
		fatal_error('(' . htmlspecialchars($_POST['user']) . ') ' . $txt[473], false);

	// Generate a password if it's supposed to be emailed.
	$password = '';
	if ((!empty($modSettings['registration_method']) && $modSettings['registration_method'] == 1 && !$user_info['is_admin']) || (isset($_POST['emailActivate']) && $user_info['is_admin']))
	{
		// Randomly generate a password and remove all non alpha-numeric characters.
		$password = substr(preg_replace('/\W/', '', md5(rand())), 0, 10);
	}

	// If you haven't put in a password generate one.
	if ($user_info['is_admin'] && $_POST['password'] == '')
	{
		srand(time() + 1277);
		$_POST['passwrd1'] = substr(preg_replace('/\W/', '', md5(rand())), 0, 10);
		$_POST['passwrd2'] = $_POST['passwrd1'];
	}
	elseif ($user_info['is_admin'])
	{
		$_POST['passwrd1'] = $_POST['password'];
		$_POST['passwrd2'] = $_POST['passwrd1'];
	}

	if ($_POST['passwrd1'] !=  $_POST['passwrd2'])
		fatal_lang_error(213, false);

	if ($_POST['passwrd1'] == '')
		fatal_lang_error(91, false);

	// Clear ban on email address, the user might come up with a better address.
	if (!empty($_SESSION['ban']['cannot_register']['type']) && $_SESSION['ban']['cannot_register']['type'] == 'email_ban')
		$_SESSION['ban']['cannot_register'] = array(
			'is_banned' => false
		);
	if (!empty($_SESSION['ban']['full_ban']['type']) && $_SESSION['ban']['full_ban']['type'] == 'email_ban')
		$_SESSION['ban']['full_ban'] = array(
			'is_banned' => false
		);

	// Is this email address banned?
	$request = db_query("
		SELECT restriction_type, reason
		FROM {$db_prefix}banned
		WHERE ban_type = 'email_ban'
			AND '$_POST[email]' LIKE email_address
			AND (restriction_type = 'cannot_register' OR restriction_type = 'full_ban')", __FILE__, __LINE__);
	if (mysql_num_rows($request) > 0)
		while ($row = mysql_fetch_assoc($request))
		{
			$_SESSION['ban'][$row['restriction_type']] = array(
				'is_banned' => true,
				'reason' => empty($row['reason']) ? '' : '<br /><br /><b>' . $txt['ban_reason'] . ':</b> ' . $row['reason'],
				'type' => 'email_ban'
			);
		}
	mysql_free_result($request);

	// This email address must be registered as banned.
	if (isset($_SESSION['ban']) && ($_SESSION['ban']['full_ban']['is_banned'] || $_SESSION['ban']['cannot_register']['is_banned']))
	{
		// Log this ban for future reference.
		db_query("
			INSERT INTO {$db_prefix}log_banned
				(ID_MEMBER, ip, email, logTime)
			VALUES (0, '$user_info[ip]', '$_POST[email]', " . time() . ')', __FILE__, __LINE__);

		// Full ban. Get the default ban error.
		if ($_SESSION['ban']['full_ban']['is_banned'])
			fatal_error(sprintf($txt[430], $txt[28]) . $_SESSION['ban']['full_ban']['reason']);

		// 'Cannot register' ban.
		if ($_SESSION['ban']['cannot_register']['is_banned'])
			fatal_error($txt['ban_register_prohibited'] . '!' . $_SESSION['ban']['cannot_register']['reason']);
	}

	// Check if the email address is in use.
	$request = db_query("
		SELECT ID_MEMBER
		FROM {$db_prefix}members
		WHERE emailAddress = '$_POST[email]'
			OR emailAddress = '$_POST[user]'
		LIMIT 1", __FILE__, __LINE__);
	if (mysql_num_rows($request) != 0)
		fatal_error(sprintf($txt[730], htmlspecialchars($_POST['email'])), false);
	mysql_free_result($request);

	// Some of these might be overwritten. (the lower ones that are in the arrays below.)
	$register_vars = array(
		'memberName' => "'$_POST[user]'",
		'emailAddress' => "'$_POST[email]'",
		'passwd' => '\'' . md5_hmac($_POST['passwrd1'], strtolower($_POST['user'])) . '\'',
		'posts' => 0,
		'dateRegistered' => time(),
		'memberIP' => "'$user_info[ip]'",
		'is_activated' => empty($modSettings['registration_method']) || (!isset($_POST['emailActivate']) && $user_info['is_admin']) ? 1 : 0,
		'validation_code' => !empty($modSettings['registration_method']) && $modSettings['registration_method'] == 1 ? "'$password'" : "''",
		'realName' => "'$_POST[user]'",
		'personalText' => '\'' . addslashes($modSettings['default_personalText']) . '\'',
		'im_email_notify' => 1,
		'ID_THEME' => 0,
		'ID_POST_GROUP' => 4,
	);

	// Make sure the ID_GROUP will be valid, if this is an administator.
	if ($user_info['is_admin'])
		$register_vars['ID_GROUP'] = empty($_POST['group']) ? 0 : (int) $_POST['group'];

	$possible_strings = array(
		'realName',
		'lngfile',
		'personalText', 'signature', 'avatar',
		'location',
		'websiteTitle', 'websiteUrl',
		'gender',
		'timeFormat',
		'secretQuestion', 'secretAnswer',
		'smileySet',
		'birthdate',
	);
	$possible_ints = array(
		'ICQ', 'AIM', 'YIM', 'MSN',
		'ID_THEME',
	);
	$possible_floats = array(
		'timeOffset',
	);
	$possible_bools = array(
		'hideEmail', 'showOnline',
		'im_email_notify',
		'notifyAnnouncements', 'notifyOnce',
	);

	// Handle a string as a birthdate...
	if (isset($_POST['birthdate']) && $_POST['birthdate'] != '')
		$_POST['birthdate'] = strftime('%Y-%m-%d', strtotime($_POST['birthdate']));
	// Or birthdate parts...
	elseif (!empty($_POST['bday1']) && !empty($_POST['bday2']))
		$_POST['birthdate'] = sprintf('%04d-%02d-%02d', empty($_POST['bday3']) ? 0 : (int) $_POST['bday3'], (int) $_POST['bday1'], (int) $_POST['bday2']);

	foreach ($possible_strings as $var)
		if (isset($_POST[$var]))
			$register_vars[$var] = '\'' . $_POST[$var] . '\'';
	foreach ($possible_ints as $var)
		if (isset($_POST[$var]))
			$register_vars[$var] = (int) $_POST[$var];
	foreach ($possible_floats as $var)
		if (isset($_POST[$var]))
			$register_vars[$var] = (float) $_POST[$var];
	foreach ($possible_bools as $var)
		if (isset($_POST[$var]))
			$register_vars[$var] = empty($_POST[$var]) ? 0 : 1;

	// Register options are always default options...
	if (isset($_POST['default_options']))
		$_POST['options'] = isset($_POST['options']) ? $_POST['options'] + $_POST['default_options'] : $_POST['default_options'];

	// Administrator?  We'll need to fetch the default theme options for the guest, then.
	if ($user_info['is_admin'])
	{
		$result = db_query("
			SELECT variable, value
			FROM {$db_prefix}themes
			WHERE ID_MEMBER = -1
				AND ID_THEME" . ($settings['theme_id'] == 1 ? ' = 1' : " IN ($settings[theme_id], 1)"), __FILE__, __LINE__);
		$options2 = array();
		while ($row = mysql_fetch_assoc($result))
		{
			if (!isset($options2[$row['variable']]) || $row['ID_THEME'] != '1')
				$options2[$row['variable']] = $row['value'];
		}
		mysql_free_result($result);

		$theme_vars = (isset($_POST['options']) && is_array($_POST['options']) ? $_POST['options'] : array()) + $options2;
	}
	// Set up the theme variables.... then add $options for the defaults.
	else
		$theme_vars = (isset($_POST['options']) && is_array($_POST['options']) ? $_POST['options'] : array()) + $options;

	// Register them into the database.
	db_query("
		INSERT INTO {$db_prefix}members
			(" . implode(', ', array_keys($register_vars)) . ")
		VALUES (" . implode(', ', $register_vars) . ')', __FILE__, __LINE__);
	$memberID = db_insert_id();
	updateStats('member');

	// Theme variables too?
	if (!empty($theme_vars))
	{
		$setString = '';
		foreach ($theme_vars as $var => $val)
			$setString .= "
				($memberID, '$var', '$val'),";
		db_query("
			INSERT INTO {$db_prefix}themes
				(ID_MEMBER, variable, value)
			VALUES " . substr($setString, 0, -1), __FILE__, __LINE__);
	}

	// If it's enabled, increase the registrations for today.
	trackStats(array('registers' => '+'));

	// Administrative registrations are a bit different...
	if ($context['user']['is_admin'])
	{
		if (isset($_POST['emailActivate']))
			sendmail($_POST['email'], $txt[700] . ' ' . $context['forum_name'],
				"$txt[hello_guest] $_POST[user]!\n\n" .
				"$txt[719] $_POST[user], $txt[492] $_POST[password]\n\n" .
				"$txt[activate_mail]:\n\n" .
				"$scripturl?action=activate;u=$memberID;code=$password\n\n" .
				"$txt[activate_code]: $password\n\n" .
				$txt[130]);
		elseif (isset($_POST['emailPassword']))
			sendmail($_POST['email'], $txt[700] . ' ' . $context['forum_name'],
				"$txt[hello_guest] $_POST[user]!\n\n" .
				"$txt[719] $_POST[user], $txt[492] $_POST[password]\n\n" .
				"$txt[701]\n" .
				"$scripturl?action=profile\n\n" .
				$txt[130]);

		redirectexit('action=regcenter');
	}

	// Can post straight away - welcome them to your fantastic community...
	if (empty($modSettings['registration_method']))
	{
		if (!empty($modSettings['send_welcomeEmail']))
			sendmail($_POST['email'], $txt[700] . ' ' . $context['forum_name'],
				"$txt[hello_guest] $_POST[user]!\n\n" .
				"$txt[719] $_POST[user], $txt[492] $_POST[passwrd1]\n\n" .
				"$txt[701]\n" .
				"$scripturl?action=profile\n\n" .
				$txt[130]);
		// Send admin their notification.
		adminNotify('standard', $memberID, $_POST['user']);
	}
	// Need to activate their account.
	elseif ($modSettings['registration_method'] == 1)
	{
		sendmail($_POST['email'], $txt[700] . ' ' . $context['forum_name'],
			"$txt[hello_guest] $_POST[user]!\n\n" .
			"$txt[719] $_POST[user], $txt[492] $_POST[passwrd1]\n\n" .
			"$txt[activate_mail]:\n\n" .
			"$scripturl?action=activate;u=$memberID;code=$password\n\n" .
			"$txt[activate_code]: $password\n\n" .
			$txt[130]);
	}
	// Must be awaiting approval.
	else
	{
		sendmail($_POST['email'], $txt[700] . ' ' . $context['forum_name'],
			"$txt[hello_guest] $_POST[user]!\n\n" .
			"$txt[719] $_POST[user], $txt[492] $_POST[passwrd1]\n\n" .
			"$txt[approval_email]\n\n" .
			$txt[130]);
		// Admin gets informed here...
		adminNotify('approval', $memberID, $_POST['user']);
	}

	// Okay, they're for sure registered... make sure the session is aware of this for security. (Just married :P!)
	$_SESSION['just_registered'] = 1;

	// Basic template variable setup.
	if (!empty($modSettings['registration_method']))
	{
		loadTemplate('Register');

		$context += array(
			'page_title' => &$txt[97],
			'sub_template' => 'after',
			'description' => $modSettings['registration_method'] == 2 ? $txt['approval_after_registration'] : $txt['activate_after_registration']
		);
	}
	else
	{
		require_once($sourcedir . '/Subs-Auth.php');

		setLoginCookie(60 * $modSettings['cookieTime'], $memberID, md5_hmac($_POST['passwrd1'], strtolower($_POST['user'])));

		redirectexit('action=login2;sa=check;id=' . $memberID, true, $context['server']['needs_login_fix']);
	}
}

function Activate()
{
	global $db_prefix, $context, $txt, $modSettings, $scripturl, $sourcedir;

	loadTemplate('Login');
	loadLanguage('Login');

	if (empty($_REQUEST['u']) && empty($_POST['user']))
	{
		if (empty($modSettings['registration_method']) || $modSettings['registration_method'] == 3)
			fatal_lang_error(1);

		$context['member_id'] = 0;
		$context['sub_template'] = 'resend';
		$context['page_title'] = $txt['invalid_activation_resend'];
		$context['can_activate'] = empty($modSettings['registration_method']) || $modSettings['registration_method'] == 1;
		$context['default_username'] = isset($_GET['user']) ? $_GET['user'] : '';

		return;
	}

	// Get the code from the database...
	$request = db_query("
		SELECT ID_MEMBER, validation_code, memberName, emailAddress, is_activated, passwd
		FROM {$db_prefix}members" . (empty($_REQUEST['u']) ? "
		WHERE memberName = '$_POST[user]' OR emailAddress = '$_POST[user]'" : "
		WHERE ID_MEMBER = " . (int) $_REQUEST['u']) . "
		LIMIT 1", __FILE__, __LINE__);

	// Does this user exist at all?
	if (mysql_num_rows($request) == 0)
	{
		$context['sub_template'] = 'retry_activate';
		$context['page_title'] = $txt['invalid_userid'];
		$context['member_id'] = 0;

		return;
	}

	$row = mysql_fetch_assoc($request);
	mysql_free_result($request);

	// Change their email address? (they probably tried a fake one first :P.)
	if (isset($_POST['new_email']) && isset($_REQUEST['passwd']) && md5_hmac($_REQUEST['passwd'], strtolower($row['memberName'])) == $row['passwd'])
	{
		if (empty($modSettings['registration_method']) || $modSettings['registration_method'] == 3)
			fatal_lang_error(1);

		if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]+@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['new_email'])) == 0)
			fatal_error(sprintf($txt[500], htmlspecialchars($_POST['new_email'])), false);

		// Maybe they'll have a better email address for us this time?
		if (!empty($_SESSION['ban']['cannot_register']['type']) && $_SESSION['ban']['cannot_register']['type'] == 'email_ban')
			$_SESSION['ban']['cannot_register'] = array(
				'is_banned' => false
			);
		if (!empty($_SESSION['ban']['full_ban']['type']) && $_SESSION['ban']['full_ban']['type'] == 'email_ban')
			$_SESSION['ban']['full_ban'] = array(
				'is_banned' => false
			);

		// Okay, boy, you banned?
		$request = db_query("
			SELECT restriction_type, reason
			FROM {$db_prefix}banned
			WHERE ban_type = 'email_ban'
				AND '$_POST[new_email]' LIKE email_address
				AND (restriction_type = 'cannot_register' OR restriction_type = 'full_ban')", __FILE__, __LINE__);
		while ($row2 = mysql_fetch_assoc($request))
		{
			$_SESSION['ban'][$row2['restriction_type']] = array(
				'is_banned' => true,
				'reason' => empty($row2['reason']) ? '' : '<br /><br /><b>' . $txt['ban_reason'] . ':</b> ' . $row2['reason'],
				'type' => 'email_ban'
			);
		}
		mysql_free_result($request);

		// Alright... seems that email is banned.  Punk?  Thought you could get through, eh?
		if ($_SESSION['ban']['full_ban']['is_banned'] || $_SESSION['ban']['cannot_register']['is_banned'])
		{
			// Make a note of this punk.
			db_query("
				INSERT INTO {$db_prefix}log_banned
					(ID_MEMBER, ip, email, logTime)
				VALUES ($row[ID_MEMBER], '$user_info[ip]', '$_POST[new_email]', " . time() . ')', __FILE__, __LINE__);

			// Wow, you're heavy-duty banned.... shucks to be you!
			if ($_SESSION['ban']['full_ban']['is_banned'])
				fatal_error(sprintf($txt[430], $txt[28]) . $_SESSION['ban']['full_ban']['reason']);

			// Probably just the email host is blocked..
			if ($_SESSION['ban']['cannot_register']['is_banned'])
				fatal_error($txt['ban_register_prohibited'] . '!' . $_SESSION['ban']['cannot_register']['reason']);
		}

		// Ummm... don't even dare try to take someone else's email!!
		$request = db_query("
			SELECT ID_MEMBER
			FROM {$db_prefix}members
			WHERE emailAddress = '$_POST[new_email]'
			LIMIT 1", __FILE__, __LINE__);
		if (mysql_num_rows($request) != 0)
			fatal_error(sprintf($txt[730], htmlspecialchars($_POST['new_email'])), false);
		mysql_free_result($request);

		updateMemberData($row['ID_MEMBER'], array('emailAddress' => "'$_POST[new_email]'"));
		$row['emailAddress'] = $_POST['new_email'];

		$email_change = true;
	}

	// Resend the password, but only if the account wasn't activated yet.
	if (!empty($_REQUEST['sa']) && $_REQUEST['sa'] == 'resend' && empty($row['is_activated']) && (!isset($_REQUEST['code']) || $_REQUEST['code'] == ''))
	{
		require_once($sourcedir . '/Subs-Post.php');

		sendmail($row['emailAddress'], $txt[700] . ' ' . $context['forum_name'],
			"$txt[hello_guest] $row[memberName]!\n\n" .
			"$txt[719] $row[memberName]\n\n" . (empty($modSettings['registration_method']) || $modSettings['registration_method'] == 1 ?
			"$txt[activate_mail]:\n\n" .
			"$scripturl?action=activate;u=$row[ID_MEMBER];code=$row[validation_code]\n\n" .
			"$txt[activate_code]: $row[validation_code]\n\n" :
			"$txt[approval_email]\n\n") .
			$txt[130]);

		$context['page_title'] = $txt['invalid_activation_resend'];
		fatal_error(!empty($email_change) ? $txt['change_email_success'] : $txt['resend_email_success'], false);
	}

	// Quit if this code is not right.
	if (empty($_REQUEST['code']) || $row['validation_code'] != $_REQUEST['code'])
	{
		if (!empty($row['is_activated']))
			fatal_lang_error('already_activated', false);
		elseif ($row['validation_code'] == '')
		{
			loadLanguage('Profile');
			fatal_error($txt['registration_not_approved'] . ' <a href="' . $scripturl . '?action=activate;user=' . $row['memberName'] . '">' . $txt[662] . '</a>.', false);
		}

		$context['sub_template'] = 'retry_activate';
		$context['page_title'] = $txt['invalid_activation_code'];
		$context['member_id'] = $row['ID_MEMBER'];

		return;
	}

	// Validation complete!
	updateMemberData($row['ID_MEMBER'], array('is_activated' => 1, 'validation_code' => '\'\''));

	if (!isset($_POST['new_email']))
		adminNotify('activation', $row['ID_MEMBER'], $row['memberName']);

	$context += array(
		'page_title' => &$txt[245],
		'sub_template' => 'login',
		'default_username' => $row['memberName'],
		'default_password' => '',
		'never_expire' => false,
		'description' => &$txt['activate_success']
	);
}

// Main handling function for the admin approval center
function RegCenter()
{
	global $modSettings, $context, $txt, $db_prefix;

	// Must have sufficient permissions.
	isAllowedTo('moderate_forum');

	loadTemplate('Register');
	loadLanguage('Login');

	// Set the admin area...
	adminIndex('registration_center');
	$context['page_title'] = $txt['registration_center'];

	$subActions = array(
		'register' => 'AdminRegister',
		'register2' => 'Register2',
		'browse' => 'AdminBrowse',
		'approve' => 'AdminApprove'
	);

	// This is just a safety check - if the admin changes a registration setting they can still activate/approve remaining accounts.
	$request = db_query("
		SELECT COUNT(ID_MEMBER)
		FROM {$db_prefix}members
		WHERE " . (!empty($modSettings['registration_method']) ? ("validation_code " . ($modSettings['registration_method'] == 2 ? '=' : '!=') . " ''") : "1") . "
			AND is_activated = 0", __FILE__, __LINE__);
	list ($membersExist) = mysql_fetch_row($request);
	mysql_free_result($request);

	// For the page header.
	if (!empty($modSettings['registration_method']) && $modSettings['registration_method'] != 3)
		$context['types_enabled'] = $modSettings['registration_method'] == 1 ? 'activate' : 'approve';

	if ($membersExist)
		$context['types_enabled'] = 'both';

	if (isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]))
		$subActions[$_REQUEST['sa']]();
	elseif (!empty($modSettings['registration_method']) && $modSettings['registration_method'] != 3)
		AdminBrowse();
	else
		AdminRegister();
}

// Do the approve/activate/delete stuff
function AdminApprove()
{
	global $txt, $context, $db_prefix, $scripturl, $modSettings, $sourcedir;

	require_once($sourcedir . '/Subs-Post.php');

	// Nothing to do?
	if (!isset($_POST['todoAction']))
		redirectexit('action=regcenter;sa=browse;type=' . $_REQUEST['type'] . ';sort=' . $_REQUEST['sort'] . ';start=' . $_REQUEST['start']);

	// Cycle through each checked member.
	foreach ($_POST['todoAction'] as $id => $email)
	{
		$username = $_POST['username'][$id];
		if ($_POST['todo'] == 'ok' || $_POST['todo'] == 'okemail')
		{
			// Approve/activate this member.
			db_query("
				UPDATE {$db_prefix}members
				SET validation_code = '', is_activated = 1
				WHERE ID_MEMBER = $id
				LIMIT 1", __FILE__, __LINE__);

			// Check for email.
			if ($_POST['todo'] == 'okemail')
				sendmail($email, $txt[700] . ' ' . $context['forum_name'],
						"$txt[hello_guest] $username!\n\n" .
						"$txt[admin_approve_accept_desc] $txt[719] $username\n\n" .
						"$txt[701]\n" .
						"$scripturl?action=profile\n\n" .
						$txt[130]);
		}
		elseif ($_POST['todo'] == 'reject' || $_POST['todo'] == 'rejectemail')
		{
			require_once($sourcedir . '/ManageMembers.php');
			deleteMembers($id);

			// Send email telling them they aren't welcome?
			if ($_POST['todo'] == 'rejectemail')
				sendmail($email, $txt['admin_approve_reject'],
					"$username,\n\n" .
					"$txt[admin_approve_reject_desc]\n\n" .
					$txt[130]);
		}
		elseif ($_POST['todo'] == 'delete' || $_POST['todo'] == 'deleteemail')
		{
			require_once($sourcedir . '/ManageMembers.php');
			deleteMembers($id);

			// Send email telling them they aren't welcome?
			if ($_POST['todo'] == 'deleteemail')
				sendmail($email, $txt['admin_approve_delete'],
					"$username,\n\n" .
					"$txt[admin_approve_delete_desc]\n\n" .
					$txt[130]);
		}
		elseif ($_POST['todo'] == 'remind')
		{
			$request = db_query("
				SELECT validation_code
				FROM {$db_prefix}members
				WHERE ID_MEMBER = $id
				LIMIT 1", __FILE__, __LINE__);
			list ($actpass) = mysql_fetch_row($request);
			mysql_free_result($request);

			sendmail($email, $txt['admin_approve_remind'],
				"$username,\n\n" .
				"$txt[admin_approve_remind_desc] $context[forum_name].\n\n$txt[admin_approve_remind_desc2]\n\n" .
				"$scripturl?action=activate;u=$id;code=$actpass\n\n" .
				$txt[130]);
		}
	}

	// Update the member's stats.
	updateStats('member');

	redirectexit('action=regcenter;sa=browse;type=' . $_REQUEST['type'] . ';sort=' . $_REQUEST['sort'] . ';start=' . $_REQUEST['start']);
}

// List all members who are awaiting approval / activation
function AdminBrowse()
{
	global $txt, $context, $db_prefix, $scripturl, $modSettings;

	// Not a lot here!
	$context['sub_template'] = 'admin_browse';
	$context['browse_type'] = isset($_REQUEST['type']) ? $_REQUEST['type'] : (!empty($modSettings['registration_method']) && $modSettings['registration_method'] == 1 ? 'activate' : 'approve');

	// The columns that can be sorted.
	$context['columns'] = array(
		'ID_MEMBER' => array('label' => $txt['admin_browse_id']),
		'memberName' => array('label' => $txt['admin_browse_username']),
		'emailAddress' => array('label' => $txt['admin_browse_email']),
		'memberIP' => array('label' => $txt['admin_browse_ip']),
		'dateRegistered' => array('label' => $txt['admin_browse_registered']),
	);

	// Default sort column to 'dateRegistered' if the current one is unknown or not set.
	if (!isset($_REQUEST['sort']) || !array_key_exists($_REQUEST['sort'], $context['columns']))
		$_REQUEST['sort'] = 'dateRegistered';

	// Provide extra information about each column - the link, whether it's selected, etc.
	foreach ($context['columns'] as $col => $dummy)
	{
		$context['columns'][$col]['href'] = $scripturl . '?action=regcenter;sa=browse;type=' . $context['browse_type'] . ';sort=' . $col . ';start=0';
		if (!isset($_REQUEST['desc']) && $col == $_REQUEST['sort'])
			$context['columns'][$col]['href'] .= ';desc';

		$context['columns'][$col]['link'] = '<a href="' . $context['columns'][$col]['href'] . '">' . $context['columns'][$col]['label'] . '</a>';
		$context['columns'][$col]['selected'] = $_REQUEST['sort'] == $col;
	}

	$context['sort_by'] = $_REQUEST['sort'];
	$context['sort_direction'] = !isset($_REQUEST['desc']) ? 'down' : 'up';

	// Calculate the number of results.
	$request = db_query("
		SELECT COUNT(ID_MEMBER)
		FROM {$db_prefix}members
		WHERE validation_code " . ($context['browse_type'] == 'approve' ? '=' : '!=') . " ''
			AND is_activated = 0", __FILE__, __LINE__);
	list ($num_members) = mysql_fetch_row($request);
	mysql_free_result($request);

	// Construct the page links.
	$context['page_index'] = constructPageIndex($scripturl . '?action=regcenter;sa=browse;type=' . $context['browse_type'] . ';sort=' . $_REQUEST['sort'] . (isset($_REQUEST['desc']) ? ';desc' : ''), $_REQUEST['start'], $num_members, $modSettings['defaultMaxMembers']);
	$context['start'] = $_REQUEST['start'];

	$request = db_query("
		SELECT ID_MEMBER, memberName, emailAddress, memberIP, dateRegistered
		FROM {$db_prefix}members
		WHERE is_activated = 0
			AND validation_code " . ($context['browse_type'] == 'approve' ? '=' : '!=') . " ''
		ORDER BY $_REQUEST[sort]" . (!isset($_REQUEST['desc']) ? '' : ' DESC') . "
		LIMIT $_REQUEST[start], $modSettings[defaultMaxMembers]", __FILE__, __LINE__);

	while ($row = mysql_fetch_assoc($request))
		$context['members'][] = array(
			'id' => $row['ID_MEMBER'],
			'username' => $row['memberName'],
			'href' => $scripturl . '?action=profile;u=' . $row['ID_MEMBER'],
			'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['memberName'] . '</a>',
			'email' => $row['emailAddress'],
			'ip' => $row['memberIP'],
			'dateRegistered' => timeformat($row['dateRegistered']),
		);
	mysql_free_result($request);
}

// This function allows the admin to register a new member by hand.
function AdminRegister()
{
	global $txt, $context, $db_prefix;

	// Basic stuff.
	$context['sub_template'] = 'admin_register';

	// Load the assignable member groups.
	$request = db_query("
		SELECT groupName, ID_GROUP
		FROM {$db_prefix}membergroups
		WHERE ID_GROUP != 3
			AND minPosts = -1" . (allowedTo('admin_forum') ? '' : "
			AND ID_GROUP != 1") . "
		ORDER BY minPosts, IF(ID_GROUP < 4, ID_GROUP, 4), groupName", __FILE__, __LINE__);
	$context['member_groups'] = array(0 => &$txt['admin_register_group_none']);
	while ($row = mysql_fetch_assoc($request))
		$context['member_groups'][$row['ID_GROUP']] = $row['groupName'];
	mysql_free_result($request);
}

// This simple function gets a list of all administrators and sends them an email to let them know a new member has joined.
function adminNotify($type, $memberID, $memberName = null)
{
	global $txt, $db_prefix, $modSettings, $language, $scripturl, $sourcedir;

	// If the setting isn't enabled then just exit.
	if (empty($modSettings['notify_on_new_registration']))
		return;

	require_once($sourcedir . '/Subs-Post.php');

	if ($memberName == null)
	{
		// Get the new user's name...
		$request = db_query("
			SELECT realName
			FROM {$db_prefix}members
			WHERE ID_MEMBER = $memberID", __FILE__, __LINE__);
		list ($memberName) = mysql_fetch_row($request);
		mysql_free_result($request);
	}

	$toNotify = array();
	$groups = array();

	// All membergroups who can approve members.
	$request = db_query("
		SELECT ID_GROUP
		FROM {$db_prefix}permissions
		WHERE permission = 'moderate_forum'
			AND addDeny = 1
			AND ID_GROUP != 0", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
		$groups[] = $row['ID_GROUP'];
	mysql_free_result($request);

	// Add administrators too...
	$groups[] = 1;
	$groups = array_unique($groups);

	// Get a list of all members who have ability to approve accounts - these are the people who we inform.
	$request = db_query("
		SELECT ID_MEMBER, lngfile, emailAddress
		FROM {$db_prefix}members
		WHERE ID_GROUP IN (" . implode(', ', $groups) . ") OR FIND_IN_SET(" . implode(', additionalGroups) OR FIND_IN_SET(', $groups) . ", additionalGroups)
		ORDER BY lngfile", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
	{
		// Post it in this members language.
		loadLanguage('Login', empty($row['lngfile']) || empty($modSettings['userLanguage']) ? $language : $row['lngfile'], false);

		// Construct the message based on what they are being told.
		$message = sprintf($txt['admin_notify_profile'], $memberName) . "\n\n" .
			"$scripturl?action=profile;u=$memberID\n\n";

		// If they need to be approved add more info...
		if ($type == 'approval')
			$message .= $txt['admin_notify_approval'] . "\n\n" .
				"$scripturl?action=regcenter;sa=browse;type=approve\n\n";

		// And do the actual sending...
		sendmail($row['emailAddress'], $txt['admin_notify_subject'], $message . $txt[130]);
	}
	mysql_free_result($request);
}

?>