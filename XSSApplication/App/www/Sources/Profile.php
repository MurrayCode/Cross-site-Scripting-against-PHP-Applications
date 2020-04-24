<?php
/******************************************************************************
* Profile.php                                                                 *
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

/*	This file has the primary job of showing and editing people's profiles.
	It also allows the user to change some of their or another's preferences,
	and such things.
*/

// Allow the change or view of profiles...
function ModifyProfile($post_errors = array())
{
	global $txt, $scripturl, $user_info, $context, $ID_MEMBER, $sourcedir;

	loadLanguage('Profile');
	loadTemplate('Profile');

	/* Set allowed sub-actions.

	 The format of $sa_allowed is as follows:

	$sa_allowed = array(
		'sub-action' => array(permission_array_for_editing_OWN_profile, permission_array_for_editing_ANY_profile[, require_validation]),
		...
	);

	*/

	$sa_allowed = array(
		'summary' => array(array('profile_view_any', 'profile_view_own'), array('profile_view_any')),
		'statPanel' => array(array('profile_view_any', 'profile_view_own'), array('profile_view_any')),
		'showPosts' => array(array('profile_view_any', 'profile_view_own'), array('profile_view_any')),
		'trackUser' => array(array('moderate_forum'), array('moderate_forum'), true),
		'trackIP' => array(array('moderate_forum'), array('moderate_forum'), true),
		'showPermissions' => array(array('manage_permissions'), array('manage_permissions')),
		'account' => array(array('manage_membergroups', 'profile_identity_any', 'profile_identity_own'), array('manage_membergroups', 'profile_identity_any')),
		'forumProfile' => array(array('profile_exta_any', 'profile_extra_own'), array('profile_extra_any')),
		'theme' => array(array('profile_exta_any', 'profile_extra_own'), array('profile_extra_any')),
		'notification' => array(array('profile_exta_any', 'profile_extra_own'), array('profile_extra_any')),
		'pmprefs' => array(array('profile_exta_any', 'profile_extra_own'), array('profile_extra_any')),
		'deleteAccount' => array(array('profile_remove_any', 'profile_remove_own'), array('profile_remove_any')),
	);

	// Set the profile layer to be displayed.
	$context['template_layers'][] = 'profile';

	// Did we get the user by name...
	if (isset($_REQUEST['user']))
		$memberResult = loadMemberData($_REQUEST['user'], true, 'profile');
	// ... or by ID_MEMBER?
	elseif (!empty($_REQUEST['u']))
		$memberResult = loadMemberData((int) $_REQUEST['u'], false, 'profile');
	// If it was just ?action=profile, edit your own profile.
	else
		$memberResult = loadMemberData($ID_MEMBER, false, 'profile');

	// Check if loadMemberData() has returned a valid result.
	if (!is_array($memberResult))
		fatal_error($txt[453], false);

	// If all went well, we have a valid member ID!
	list ($memID) = $memberResult;

	// Is this the profile of the user himself or herself?
	$context['user']['is_owner'] = $memID == $ID_MEMBER;

	// No Subaction?
	if (!isset($_REQUEST['sa']) || !isset($sa_allowed[$_REQUEST['sa']]))
	{
		// Pick the first subaction you're allowed to see.
		if ((allowedTo('profile_view_own') && $context['user']['is_owner']) || allowedTo('profile_view_any'))
			$_REQUEST['sa'] = 'summary';
		elseif (allowedTo('moderate_forum'))
			$_REQUEST['sa'] = 'trackUser';
		elseif (allowedTo('manage_permissions'))
			$_REQUEST['sa'] = 'showPermissions';
		elseif ((allowedTo('profile_identity_own') && $context['user']['is_owner']) || allowedTo('profile_identity_any') || allowedTo('manage_membergroups'))
			$_REQUEST['sa'] = 'account';
		elseif ((allowedTo('profile_extra_own') && $context['user']['is_owner']) || allowedTo('profile_extra_any'))
			$_REQUEST['sa'] = 'forumProfile';
		elseif ((allowedTo('profile_remove_own') && $context['user']['is_owner']) || allowedTo('profile_remove_any'))
			$_REQUEST['sa'] = 'deleteAccount';
		else
			isAllowedTo('profile_view_' . ($context['user']['is_owner'] ? 'own' : 'any'));
	}

	// Check the permissions for the given sub action.
	isAllowedTo($sa_allowed[$_REQUEST['sa']][$context['user']['is_owner'] ? 0 : 1]);

	// Make sure the user is who he claims to be, before any important account stuff is changed.
	if (!empty($sa_allowed[$_REQUEST['sa']][2]))
		validateSession();

	// No need for this anymore.
	unset($sa_allowed);

	$context['profile_areas'] = array();

	// Set the menu items in the left bar...
	if (!$user_info['is_guest'] && (($context['user']['is_owner'] && allowedTo('profile_view_own')) || allowedTo(array('profile_view_any', 'moderate_forum', 'manage_permissions'))))
	{
		$context['profile_areas']['info'] = array(
			'title' => $txt['profileInfo'],
			'areas' => array()
		);

		if (($context['user']['is_owner'] && allowedTo('profile_view_own')) || allowedTo('profile_view_any'))
		{
			$context['profile_areas']['info']['areas']['summary'] = '<a href="' . $scripturl . '?action=profile;u=' . $memID . ';sa=summary">' . $txt['summary'] . '</a>';
			$context['profile_areas']['info']['areas']['statPanel']	= '<a href="' . $scripturl . '?action=profile;u=' . $memID . ';sa=statPanel">' . $txt['statPanel'] . '</a>';
			$context['profile_areas']['info']['areas']['showPosts']	= '<a href="' . $scripturl . '?action=profile;u=' . $memID . ';sa=showPosts">' . $txt['showPosts'] . '</a>';
		}

		// Groups with moderator permissions can also....
		if (allowedTo('moderate_forum'))
		{
			$context['profile_areas']['info']['areas']['trackUser'] = '<a href="' . $scripturl . '?action=profile;u=' . $memID . ';sa=trackUser">' . $txt['trackUser'] . '</a>';
			$context['profile_areas']['info']['areas']['trackIP'] = '<a href="' . $scripturl . '?action=profile;u=' . $memID . ';sa=trackIP">' . $txt['trackIP'] . '</a>';
		}
		if (allowedTo('manage_permissions'))
			$context['profile_areas']['info']['areas']['showPermissions'] = '<a href="' . $scripturl . '?action=profile;u=' . $memID . ';sa=showPermissions">' . $txt['showPermissions'] . '</a>';
	}

	// Edit your/this person's profile?
	if (($context['user']['is_owner'] && (allowedTo(array('profile_identity_own', 'profile_extra_own')))) || allowedTo(array('profile_identity_any', 'profile_extra_any', 'manage_membergroups')))
	{
		$context['profile_areas']['edit_profile'] = array(
			'title' => $txt['profileEdit'],
			'areas' => array()
		);

		if (($context['user']['is_owner'] && allowedTo('profile_identity_own')) || allowedTo(array('profile_identity_any', 'manage_membergroups')))
			$context['profile_areas']['edit_profile']['areas']['account'] = '<a href="' . $scripturl . '?action=profile;u=' . $memID . ';sa=account">' . $txt['account'] . '</a>';

		if (($context['user']['is_owner'] && allowedTo('profile_extra_own')) || allowedTo('profile_extra_any'))
		{
			$context['profile_areas']['edit_profile']['areas']['forumProfile'] = '<a href="' . $scripturl . '?action=profile;u=' . $memID . ';sa=forumProfile">' . $txt['forumProfile'] . '</a>';
			$context['profile_areas']['edit_profile']['areas']['theme'] = '<a href="' . $scripturl . '?action=profile;u=' . $memID . ';sa=theme">' . $txt['theme'] . '</a>';
			$context['profile_areas']['edit_profile']['areas']['notification'] = '<a href="' . $scripturl . '?action=profile;u=' . $memID . ';sa=notification">' . $txt['notification'] . '</a>';
			$context['profile_areas']['edit_profile']['areas']['pmprefs'] = '<a href="' . $scripturl . '?action=profile;u=' . $memID . ';sa=pmprefs">' . $txt['pmprefs'] . '</a>';
		}
	}

	// If you have permission to do something with this profile, you'll see one or more actions.
	if (($context['user']['is_owner'] && allowedTo('profile_remove_own')) || allowedTo('profile_remove_any') || (!$context['user']['is_owner'] && allowedTo('pm_send')))
	{
		// Initialize the action menu group.
		$context['profile_areas']['profile_action'] = array(
			'title' => $txt['profileAction'],
			'areas' => array()
		);

		// You shouldn't PM (or ban really..) yourself!! (only administrators see this because it's not in the menu.)
		if (!$context['user']['is_owner'] && allowedTo('pm_send'))
			$context['profile_areas']['profile_action']['areas']['send_pm'] = '<a href="' . $scripturl . '?action=pm;sa=send;u=' . $memID . '">' . $txt['profileSendIm'] . '</a>';
		if (allowedTo('manage_bans'))
			$context['profile_areas']['profile_action']['areas']['banUser'] = '<a href="' . $scripturl . '?action=ban;sa=add;u=' . $memID . '">' . $txt['profileBanUser'] . '</a>';

		// You may remove your own account 'cuz it's yours or you're an admin.
		if (($context['user']['is_owner'] && allowedTo('profile_remove_own')) || allowedTo('profile_remove_any'))
			$context['profile_areas']['profile_action']['areas']['deleteAccount'] = '<a href="' . $scripturl . '?action=profile;u=' . $memID . ';sa=deleteAccount">' . $txt['deleteAccount'] . '</a>';
	}

	// This is here so the menu won't be shown unless it's actually needed.
	if (!isset($context['profile_areas']['info']['areas']['trackUser']) && !isset($context['profile_areas']['info']['areas']['showPermissions']) && !isset($context['profile_areas']['edit_profile']) && !isset($context['profile_areas']['profile_action']['areas']['banUser']) && !isset($context['profile_areas']['profile_action']['areas']['deleteAccount']))
		$context['profile_areas'] = array();

	// Set the selected items.
	$context['menu_item_selected'] = $_REQUEST['sa'];
	$context['sub_template'] = $_REQUEST['sa'];

	// All the subactions that require a user password in order to validate.
	$context['require_password'] = in_array($context['menu_item_selected'], array('account'));

	// If this is an administrative action, load ManageMembers.php for it!
	if (in_array($_REQUEST['sa'], array('trackUser', 'trackIP', 'showPermissions')))
		require_once($sourcedir . '/ManageMembers.php');

	// Call the appropriate subaction function.
	$_REQUEST['sa']($memID);

	if (!empty($post_errors))
	{
		// Set all the errors so the template knows what went wrong.
		foreach ($post_errors as $error_type)
			$context['modify_error'][$error_type] = true;
		rememberPostData();
	}

	// Set the page title if it's not already set...
	if (!isset($context['page_title']))
		$context['page_title'] = $txt[79] . ' - ' . $txt[$_REQUEST['sa']];
}

// Execute the modifications!
function ModifyProfile2()
{
	global $txt, $modSettings;
	global $cookiename, $context;
	global $sourcedir, $scripturl, $db_prefix;
	global $ID_MEMBER, $user_info;
	global $context, $newpassemail, $user_profile, $validationCode;

	loadLanguage('Profile');

	/* Set allowed sub-actions.

	 The format of $sa_allowed is as follows:

	$sa_allowed = array(
		'sub-action' => array(permission_array_for_editing_OWN_profile, permission_array_for_editing_ANY_profile, session_validation_method[, require_password]),
		...
	);

	*/

	$sa_allowed = array(
		'account' => array(array('manage_membergroups', 'profile_identity_any', 'profile_identity_own'), array('manage_membergroups', 'profile_identity_any'), 'post', true),
		'forumProfile' => array(array('profile_exta_any', 'profile_extra_own'), array('profile_extra_any'), 'post'),
		'theme' => array(array('profile_exta_any', 'profile_extra_own'), array('profile_extra_any'), 'post'),
		'notification' => array(array('profile_exta_any', 'profile_extra_own'), array('profile_extra_any'), 'post'),
		'pmprefs' => array(array('profile_exta_any', 'profile_extra_own'), array('profile_extra_any'), 'post'),
		'deleteAccount' => array(array('profile_remove_any', 'profile_remove_own'), array('profile_remove_any'), 'post', true),
		'activateAccount' => array(array(), array('moderate_forum'), 'get'),
	);

	// Is the current sub-action allowed?
	if (empty($_REQUEST['sa']) || !isset($sa_allowed[$_REQUEST['sa']]))
		fatal_error($txt[453]);

	checkSession($sa_allowed[$_REQUEST['sa']][2]);

	// Start with no updates and no errors.
	$profile_vars = array();
	$post_errors = array();

	// Normally, don't send an email.
	$newpassemail = false;

	// Clean up the POST variables.
	$_POST = htmltrim__recursive($_POST);
	$_POST = stripslashes__recursive($_POST);
	$_POST = htmlspecialchars__recursive($_POST);
	$_POST = addslashes__recursive($_POST);

	// Search for the member being edited and put the information in $user_profile.
	$memberResult = loadMemberData((int) $_REQUEST['userID'], false, 'profile');

	if (!is_array($memberResult))
		fatal_error($txt[453], false);

	list ($memID) = $memberResult;

	// Are you modifying your own, or someone else's?
	if ($ID_MEMBER == $memID)
		$context['user']['is_owner'] = true;
	else
	{
		$context['user']['is_owner'] = false;
		validateSession();
	}

	// Check profile editing permissions.
	isAllowedTo($sa_allowed[$_REQUEST['sa']][$context['user']['is_owner'] ? 0 : 1]);

	// If this is yours, check the password.
	if ($context['user']['is_owner'] && !empty($sa_allowed[$_REQUEST['sa']][3]))
	{
		// You didn't even enter a password!
		if (!trim($_POST['oldpasswrd']))
			$post_errors[] = 'no_password';

		// Bad password!!!
		if ($user_info['passwd'] != md5_hmac($_POST['oldpasswrd'], strtolower($user_profile[$memID]['memberName'])))
			$post_errors[] = 'bad_password';
	}

	// No need for the sub action array.
	unset($sa_allowed);

	// If the user is an admin - see if they are resetting someones username.
	if ($user_info['is_admin'] && isset($_POST['memberName']))
	{
		// We'll need this...
		require_once($sourcedir . '/Subs-Auth.php');

		// Do the reset... this will send them an email too.
		resetPassword($memID, $_POST['memberName']);
	}

	// Change the IP address in the database.
	if ($context['user']['is_owner'])
		$profile_vars['memberIP'] = "'$user_info[ip]'";

	// Now call the sub-action function...
	if (isset($_POST['sa']) && $_POST['sa'] == 'deleteAccount')
	{
		deleteAccount2($profile_vars, $post_errors, $memID);

		if (empty($post_errors))
			redirectexit();
	}
	else
		saveProfileChanges($profile_vars, $post_errors, $memID);

	// There was a problem, let them try to re-enter.
	if (!empty($post_errors))
	{
		$_REQUEST['sa'] = $_POST['sa'];
		$_REQUEST['u'] = $memID;
		return ModifyProfile($post_errors);
	}

	if (!empty($profile_vars))
		updateMemberData($memID, $profile_vars);

	// What if this is the newest member?
	updateStats('member');

	// If the member changed his/her birthdate, update calendar statistics.
	if (isset($profile_vars['birthdate']) || isset($profile_vars['realName']))
		updateStats('calendar');

	// Send an email?
	if ($newpassemail)
	{
		require_once($sourcedir . '/Subs-Post.php');

		// Send off the email.
		sendmail($_POST['emailAddress'], $txt['activate_reactivate_title'] . ' ' . $context['forum_name'],
			"$txt[activate_reactivate_mail]\n\n" .
			"$scripturl?action=activate;u=$memID;code=$validationCode\n\n" .
			"$txt[activate_code]: $validationCode\n\n" .
			$txt[130]);

		// Log the user out.
		db_query("
			DELETE FROM {$db_prefix}log_online
			WHERE ID_MEMBER = $memID", __FILE__, __LINE__);
		$_SESSION['log_time'] = 0;
		$_SESSION['login_' . $cookiename] = serialize(array(0, '', 0));

		if (isset($_COOKIE[$cookiename]))
			$_COOKIE[$cookiename] = '';

		loadUserSettings();

		$context['user']['is_logged'] = false;
		$context['user']['is_guest'] = true;

		// Send them to the done-with-registration-login screen.
		loadTemplate('Register');
		$context += array(
			'page_title' => &$txt[79],
			'sub_template' => 'after',
			'description' => &$txt['activate_changed_email']
		);
		return;
	}
	elseif ($context['user']['is_owner'])
	{
		// Log them back in.
		if (isset($_POST['passwrd1']) && $_POST['passwrd1'] != '')
		{
			require_once($sourcedir . '/Subs-Auth.php');

			$password = md5_hmac($_POST['passwrd1'], strtolower($user_profile[$memID]['memberName']));
			setLoginCookie(60 * $modSettings['cookieTime'], $memID, $password);
		}

		loadUserSettings();
		writeLog();
	}

	// Back to same subaction page..
	redirectexit('action=profile;u=' . $memID . ';sa=' . $_REQUEST['sa'], true, $context['server']['needs_login_fix']);
}

// Save the profile changes....
function saveProfileChanges(&$profile_vars, &$post_errors, $memID)
{
	global $db_prefix, $user_info, $txt, $modSettings, $user_profile, $newpassemail, $validationCode, $context, $sourcedir, $language_dir;

	// These make life easier....
	$old_profile = &$user_profile[$memID];

	// Permissions...
	if ($context['user']['is_owner'])
	{
		$changeIdentity = allowedTo(array('profile_identity_any', 'profile_identity_own'));
		$changeOther = allowedTo(array('profile_extra_any', 'profile_extra_own'));
	}
	else
	{
		$changeIdentity = allowedTo('profile_identity_any');
		$changeOther = allowedTo('profile_extra_any');
	}

	// Arrays of all the changes - makes things easier.
	$profile_bools = array(
		'im_email_notify',
		'notifyAnnouncements', 'notifyOnce',
	);
	$profile_ints = array(
		'ICQ',
		'gender',
		'ID_THEME',
	);
	$profile_floats = array(
		'timeOffset',
	);
	$profile_strings = array(
		'websiteUrl', 'websiteTitle',
		'MSN', 'AIM', 'YIM',
		'location', 'birthdate',
		'timeFormat',
		'im_ignore_list',
		'smileySet',
		'signature', 'personalText', 'avatar',
	);

	// Fix the spaces in messenger screennames...
	$fix_spaces = array('MSN', 'AIM', 'YIM');
	foreach ($fix_spaces as $var)
	{
		if (isset($_POST[$var]))
			$_POST[$var] = strtr($_POST[$var], ' ', '+');
	}

	// Validate the title...
	if (!empty($modSettings['titlesEnable']) && (allowedTo('profile_title_any') || (allowedTo('profile_title_own') && $context['user']['is_owner'])))
		$profile_strings[] = 'usertitle';

	// Validate the timeOffset...
	if (isset($_POST['timeOffset']))
	{
		$_POST['timeOffset'] = strtr($_POST['timeOffset'], ',', '.');

		if ($_POST['timeOffset'] < -23.5 || $_POST['timeOffset'] > 23.5)
			$post_errors[] = 'bad_offset';
	}

	// Fix the URL...
	if (isset($_POST['websiteUrl']))
	{
		if (strlen(trim($_POST['websiteUrl'])) > 0 && strpos($_POST['websiteUrl'], '://') === false)
			$_POST['websiteUrl'] = 'http://' . $_POST['websiteUrl'];
		if (strlen($_POST['websiteUrl']) < 8)
			$_POST['websiteUrl'] = '';
	}

	if (isset($_POST['birthdate']))
	{
		if (preg_match('/(\d{4})[\-\., ](\d{2})[\-\., ](\d{2})/', $_POST['birthdate'], $dates) == 1)
			$_POST['birthdate'] = sprintf('%04d-%02d-%02d', $dates[1], $dates[2], $dates[3]);
		else
			unset($_POST['birthdate']);
	}
	elseif (!empty($_POST['bday1']) && !empty($_POST['bday2']))
		$_POST['birthdate'] = sprintf('%04d-%02d-%02d', empty($_POST['bday3']) ? 0 : (int) $_POST['bday3'], (int) $_POST['bday1'], (int) $_POST['bday2']);
	elseif (isset($_POST['bday1']) || isset($_POST['bday2']) || isset($_POST['bday3']))
		$_POST['birthdate'] = '0000-00-00';

	// Validate and set the ignorelist...
	if (isset($_POST['im_ignore_list']))
	{
		$_POST['im_ignore_list'] = strtr(trim($_POST['im_ignore_list']), array("\n" => "', '", "\r" => '', '&quot;' => ''));

		if (preg_match('~(\A|,)\*(\Z|,)~s', $_POST['im_ignore_list']) == 0)
		{
			$result = db_query("
				SELECT ID_MEMBER
				FROM {$db_prefix}members
				WHERE memberName IN ('$_POST[im_ignore_list]')
				LIMIT " . (substr_count($_POST['im_ignore_list'], ',') + 1), __FILE__, __LINE__);
			$_POST['im_ignore_list'] = '';
			while ($row = mysql_fetch_assoc($result))
				$_POST['im_ignore_list'] .= $row['ID_MEMBER'] . ',';
			mysql_free_result($result);

			$_POST['im_ignore_list'] = substr($_POST['im_ignore_list'], 0, -1);
		}
		else
			$_POST['im_ignore_list'] = '*';
	}

	// Validate the smiley set.
	if (isset($_POST['smileySet']))
	{
		$smiley_sets = explode(',', $modSettings['smiley_sets_known']);
		if (!in_array($_POST['smileySet'], $smiley_sets) && $_POST['smileySet'] != 'none')
			unset($_POST['smileySet']);
	}

	// Make sure the signature isn't too long.
	if (isset($_POST['signature']))
	{
		require_once($sourcedir . '/Subs-Post.php');

		$unparsed_signature = strtr(un_htmlspecialchars($_POST['signature']), array("\r" => ''));
		if (!empty($modSettings['max_signatureLength']) && strlen($unparsed_signature) > $modSettings['max_signatureLength'])
			$_POST['signature'] = htmlspecialchars(substr($unparsed_signature, 0, $modSettings['max_signatureLength']), ENT_QUOTES);
		preparsecode($_POST['signature']);
	}

	// Identity-only changes...
	if ($changeIdentity)
	{
		// This block is only concerned with display name validation.
		if (isset($_POST['realName']) && (!empty($modSettings['allow_editDisplayName']) || allowedTo('moderate_forum')) && trim($_POST['realName']) != $old_profile['realName'])
		{
			$_POST['realName'] = trim(preg_replace('/[\s]/', ' ', $_POST['realName']));
			if (trim($_POST['realName']) == '')
				$post_errors[] = 'no_name';
			elseif (isReservedName($_POST['realName'], $memID))
				$post_errors[] = 'name_taken';

			if (isset($_POST['realName']))
				$profile_vars['realName'] = '\'' . $_POST['realName'] . '\'';
		}

		// Change the registration date.
		if (!empty($_POST['dateRegistered']) && allowedTo('moderate_forum'))
		{
			// Bad date!  Go try again - please?
			if (($_POST['dateRegistered'] = strtotime($_POST['dateRegistered'])) === -1)
				fatal_error($txt['smf233'] . ' ' . strftime('%d %b %Y ' . (strpos($user_info['time_format'], '%H') !== false ? '%I:%M:%S %p' : '%H:%M:%S'), forum_time(false)), false);
			// As long as it doesn't equal 'N/A'...
			elseif ($_POST['dateRegistered'] != $txt[470] && $_POST['dateRegistered'] != strtotime(strftime('%Y-%m-%d', $user_profile[$memID]['dateRegistered'] + ($user_info['time_offset'] + $modSettings['time_offset']) * 3600)))
				$profile_vars['dateRegistered'] = $_POST['dateRegistered'] - ($user_info['time_offset'] + $modSettings['time_offset']) * 3600;
		}

		// Change the number of posts.
		if (isset($_POST['posts']) && allowedTo('moderate_forum'))
			$profile_vars['posts'] = $_POST['posts'] != '' ? (int) $_POST['posts'] : '\'\'';

		// Validate the language file...
		if (isset($_POST['lngfile']) && !empty($modSettings['userLanguage']))
		{
			$dir = dir($language_dir);
			while ($entry = $dir->read())
				if (substr($entry, 0, 6) == 'index.' && strlen($entry) > 10 && substr($entry, 6, -4) == $_POST['lngfile'])
				{
					$profile_vars['lngfile'] = "'$_POST[lngfile]'";

					// If they are the owner, make this persist even after they log out.
					if ($context['user']['is_owner'])
						$_SESSION['language'] = $_POST['lngfile'];
				}
			$dir->close();
		}

		// This block is only concerned with email address validation..
		if (isset($_POST['emailAddress']) && strtolower($_POST['emailAddress']) != strtolower($old_profile['emailAddress']))
		{
			// Prepare the new password, or check if they want to change their own.
			if (!empty($modSettings['send_validation_onChange']) && !allowedTo('moderate_forum'))
			{
				$validationCode = substr(preg_replace('/\W/', '', md5(rand())), 0, 10);
				$profile_vars['validation_code'] = '\'' . $validationCode . '\'';
				$profile_vars['is_activated'] = '0';
				$newpassemail = true;
			}

			// Check the name and email for validity.
			if (trim($_POST['emailAddress']) == '')
				$post_errors[] = 'no_email';
			if (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]+@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', stripslashes($_POST['emailAddress'])) == 0)
				$post_errors[] = 'bad_email';

			// Email addresses should be and stay unique.
			$request = db_query("
				SELECT ID_MEMBER
				FROM {$db_prefix}members
				WHERE ID_MEMBER != $memID
					AND emailAddress = '$_POST[emailAddress]'
				LIMIT 1", __FILE__, __LINE__);
			if (mysql_num_rows($request) > 0)
				$post_errors[] = 'email_taken';
			mysql_free_result($request);

			$profile_vars['emailAddress'] = '\'' . $_POST['emailAddress'] . '\'';
		}

		// Hide email address?
		if (isset($_POST['hideEmail']) && (!empty($modSettings['allow_hideEmail']) || allowedTo('moderate_forum')))
			$profile_vars['hideEmail'] = empty($_POST['hideEmail']) ? '0' : '1';

		// Are they allowed to change their hide status?
		if (isset($_POST['showOnline']) && (!empty($modSettings['allow_hideOnline']) || allowedTo('moderate_forum')))
			$profile_vars['showOnline'] = empty($_POST['showOnline']) ? '0' : '1';

		// Uhhh.... you better make sure you know what you're changing it to...
		if (isset($_POST['passwrd1']) && $_POST['passwrd1'] != $_POST['passwrd2'])
			$post_errors[] = 'bad_new_password';

		// If they are set, they want to change the password.
		if (isset($_POST['passwrd1']) && $_POST['passwrd1'] != '')
			$profile_vars['passwd'] = '\'' . md5_hmac(addslashes(un_htmlspecialchars(stripslashes($_POST['passwrd1']))), strtolower($old_profile['memberName'])) . '\'';

		if (isset($_POST['secretQuestion']))
			$profile_vars['secretQuestion'] = '\'' . $_POST['secretQuestion'] . '\'';

		// Do you have a *secret* password?
		if (isset($_POST['secretAnswer']) && $_POST['secretAnswer'] != '')
			$profile_vars['secretAnswer'] = '\'' . md5($_POST['secretAnswer']) . '\'';
	}

	// Things they can do if they are a forum moderator.
	if (allowedTo('moderate_forum'))
	{
		if ($_REQUEST['sa'] == 'activateAccount' || !empty($_POST['is_activated']))
			$profile_vars['is_activated'] = '1';

		if (isset($_POST['karmaGood']))
			$profile_vars['karmaGood'] = $_POST['karmaGood'] != '' ? (int) $_POST['karmaGood'] : '\'\'';
		if (isset($_POST['karmaBad']))
			$profile_vars['karmaBad'] = $_POST['karmaBad'] != '' ? (int) $_POST['karmaBad'] : '\'\'';
	}

	// Assigning membergroups (you need admin_forum permissions to change an admins' membergroups).
	if (allowedTo('manage_membergroups'))
	{
		// The account page allows the change of your ID_GROUP - but not to admin!.
		if (isset($_POST['ID_GROUP']) && (allowedTo('admin_forum') || ((int) $_POST['ID_GROUP'] != 1 && $old_profile['ID_GROUP'] != 1)))
			$profile_vars['ID_GROUP'] = (int) $_POST['ID_GROUP'];

		// Find the additional membergroups (if any)
		if (isset($_POST['additionalGroups']) && is_array($_POST['additionalGroups']))
		{
			foreach ($_POST['additionalGroups'] as $i => $group_id)
			{
				if ((int) $group_id == 0 || (!allowedTo('admin_forum') && (int) $group_id == 1))
					unset($_POST['additionalGroups'][$i]);
				else
					$_POST['additionalGroups'][$i] = (int) $group_id;
			}

			// Put admin back in there if you don't have permission to take it away.
			if (!allowedTo('admin_forum') && in_array(1, explode(',', $old_profile['additionalGroups'])))
				$_POST['additionalGroups'][] = 1;

			$profile_vars['additionalGroups'] = '\'' . implode(',', $_POST['additionalGroups']) . '\'';
		}
	}

	// Here's where we sort out all the 'other' values...
	if ($changeOther)
	{
		makeThemeChanges($memID, isset($_POST['ID_THEME']) ? (int) $_POST['ID_THEME'] : $old_profile['ID_THEME']);
		makeAvatarChanges($memID, $post_errors);
		makeNotificationChanges($memID);

		// Validate the language file...
		if (isset($_POST['lngfile']) && !empty($modSettings['userLanguage']))
		{
			$dir = dir($language_dir);
			while ($entry = $dir->read())
				if (substr($entry, 0, 6) == 'index.' && substr($entry, -4) == '.php' && strlen($entry) > 10 && substr($entry, 6, -4) == $_POST['lngfile'])
				{
					$profile_vars['lngfile'] = "'$_POST[lngfile]'";

					// If they are the owner, make this persist even after they log out.
					if ($context['user']['is_owner'])
						$_SESSION['language'] = $_POST['lngfile'];
				}
			$dir->close();
		}

		foreach ($profile_bools as $var)
			if (isset($_POST[$var]))
				$profile_vars[$var] = empty($_POST[$var]) ? '0' : '1';
		foreach ($profile_ints as $var)
			if (isset($_POST[$var]))
				$profile_vars[$var] = $_POST[$var] != '' ? (int) $_POST[$var] : '\'\'';
		foreach ($profile_floats as $var)
			if (isset($_POST[$var]))
				$profile_vars[$var] = (float) $_POST[$var];
		foreach ($profile_strings as $var)
			if (isset($_POST[$var]))
				$profile_vars[$var] = '\'' . $_POST[$var] . '\'';
	}

	if (isset($profile_vars['ICQ']) && $profile_vars['ICQ'] == '0')
		$profile_vars['ICQ'] = '\'\'';
}

// Make any theme changes that are sent with the profile..
function makeThemeChanges($memID, $ID_THEME)
{
	global $db_prefix;

	// These are the theme changes...
	$themeSetArray = array();
	if (isset($_POST['options']) && is_array($_POST['options']))
	{
		foreach ($_POST['options'] as $opt => $val)
			$themeSetArray[] = '(' . $memID . ', ' . $ID_THEME . ", '" . addslashes($opt) . "', '" . (is_array($val) ? implode(',', $val) : $val) . "')";
	}

	$erase_options = array();
	if (isset($_POST['default_options']) && is_array($_POST['default_options']))
		foreach ($_POST['default_options'] as $opt => $val)
		{
			$themeSetArray[] = "($memID, 1, '" . addslashes($opt) . "', '" . (is_array($val) ? implode(',', $val) : $val) . "')";
			$erase_options[] = addslashes($opt);
		}

	// If themeSetArray isn't still empty, send it to the database.
	if (!empty($themeSetArray))
	{
		db_query("
			REPLACE INTO {$db_prefix}themes
				(ID_MEMBER, ID_THEME, variable, value)
			VALUES " . implode(",
				", $themeSetArray), __FILE__, __LINE__);
	}

	if (!empty($erase_options))
	{
		db_query("
			DELETE FROM {$db_prefix}themes
			WHERE ID_THEME != 1
				AND variable IN ('" . implode("', '", $erase_options) . "')", __FILE__, __LINE__);
	}
}

// Make any notification changes that need to be made.
function makeNotificationChanges($memID)
{
	global $db_prefix;

	// Update the boards they are being notified on.
	if (isset($_POST['edit_notify_boards']) && !empty($_POST['notify_boards']))
	{
		// Make sure only integers are deleted.
		foreach ($_POST['notify_boards'] as $index => $id)
			$_POST['notify_boards'][$index] = (int) $id;

		// ID_BOARD = 0 is reserved for topic notifications.
		$_POST['notify_boards'] = array_diff($_POST['notify_boards'], array(0));

		db_query("
			DELETE FROM {$db_prefix}log_notify
			WHERE ID_BOARD IN (" . implode(', ', $_POST['notify_boards']) . ")
				AND ID_MEMBER = $memID", __FILE__, __LINE__);
	}

	// We are editing topic notifications......
	elseif (isset($_POST['edit_notify_topics']) && !empty($_POST['notify_topics']))
	{
		foreach ($_POST['notify_topics'] as $index => $id)
			$_POST['notify_topics'][$index] = (int) $id;

		// Make sure there are no zeros left.
		$_POST['notify_topics'] = array_diff($_POST['notify_topics'], array(0));

		db_query("
			DELETE FROM {$db_prefix}log_notify
			WHERE ID_TOPIC IN (" . implode(', ', $_POST['notify_topics']) . ")
				AND ID_MEMBER = $memID", __FILE__, __LINE__);
	}
}

// The avatar is incredibly complicated, what with the options... and what not.
function makeAvatarChanges($memID, &$post_errors)
{
	global $modSettings, $sourcedir, $db_prefix;

	if (!isset($_POST['avatar_choice']))
		return;

	require_once($sourcedir . '/ManageAttachments.php');

	if ($_POST['avatar_choice'] == 'server_stored' && !empty($modSettings['avatar_allow_server_stored']))
	{
		$_POST['avatar'] = strtr(empty($_POST['file']) ? (empty($_POST['cat']) ? '' : $_POST['cat']) : $_POST['file'], array('&amp;' => '&'));
		$_POST['avatar'] = preg_match('~^([\w _!@%*=\-#()\[\]&.,]+/)?[\w _!@%*=\-#()\[\]&.,]+$~', $_POST['avatar']) != 0 && preg_match('/\.\./', $_POST['avatar']) == 0 && file_exists($modSettings['avatar_directory'] . '/' . $_POST['avatar']) ? ($_POST['avatar'] == 'blank.gif' ? '' : $_POST['avatar']) : '';

		// Get rid of their old avatar. (if uploaded.)
		removeAttachments('a.ID_MEMBER = ' . $memID);
	}
	elseif ($_POST['avatar_choice'] == 'external' && !empty($modSettings['avatar_allow_external_url']) && allowedTo('profile_remote_avatar') && strtolower(substr($_POST['userpicpersonal'], 0, 7)) == 'http://')
	{
		// Remove any attached avatar...
		removeAttachments('a.ID_MEMBER = ' . $memID);

		$_POST['avatar'] = preg_replace('~action(=|%3d)(?!dlattach)~i', 'action-', $_POST['userpicpersonal']);

		if ($_POST['avatar'] == 'http://' || $_POST['avatar'] == 'http:///')
			$_POST['avatar'] = '';
		// Should we check dimensions?
		elseif (!empty($modSettings['avatar_max_height_external']) || !empty($modSettings['avatar_max_width_external']))
		{
			// Now let's validate the avatar...
			$sizes = url_image_size($_POST['avatar']);

			if (is_array($sizes) && (($sizes[0] > $modSettings['avatar_max_width_external'] && !empty($modSettings['avatar_max_width_external'])) || ($sizes[1] > $modSettings['avatar_max_height_external'] && !empty($modSettings['avatar_max_height_external']))))
			{
				// Houston, we have a problem. The avatar is too large!!
				if ($modSettings['avatar_action_too_large'] == 'option_refuse')
					$post_errors[] = 'bad_avatar';
				elseif ($modSettings['avatar_action_too_large'] == 'option_download_and_resize')
				{
					require_once($sourcedir . '/Subs-Graphics.php');
					if (downloadAvatar($_POST['avatar'], $memID, $modSettings['avatar_max_width_external'], $modSettings['avatar_max_height_external']))
						$_POST['avatar'] = '';
					else
						$post_errors[] = 'bad_avatar';
				}
			}
		}
	}
	elseif ($_POST['avatar_choice'] == 'upload' && $modSettings['avatar_allow_upload'])
	{
		if (isset($_FILES['attachment']['name']) && $_FILES['attachment']['name'] != '')
		{
			$sizes = @getimagesize($_FILES['attachment']['tmp_name']);
			if (is_array($sizes) && (($sizes[0] > $modSettings['avatar_max_width_upload'] && !empty($modSettings['avatar_max_width_upload'])) || ($sizes[1] > $modSettings['avatar_max_height_upload'] && !empty($modSettings['avatar_max_height_upload']))))
			{
				if (!empty($modSettings['avatar_resize_upload']))
				{
					if (!is_writable($modSettings['attachmentUploadDir']))
						fatal_lang_error('attachments_no_write');

					if (!move_uploaded_file($_FILES['attachment']['tmp_name'], $modSettings['attachmentUploadDir'] . '/' . 'avatar_tmp_' . $memID))
						fatal_lang_error('smf124');

					// Attempt to chmod it.
					@chmod($modSettings['attachmentUploadDir'] . '/' . 'avatar_tmp_' . $memID, 0644);

					require_once($sourcedir . '/Subs-Graphics.php');
					downloadAvatar($modSettings['attachmentUploadDir'] . '/' . 'avatar_tmp_' . $memID, $memID, $modSettings['avatar_max_width_upload'], $modSettings['avatar_max_height_upload']);
					@unlink($modSettings['attachmentUploadDir'] . '/' . 'avatar_tmp_' . $memID);
				}
				else
					$post_errors[] = 'bad_avatar';
			}
			else
			{
				$extensions = array(
					'1' => '.gif',
					'2' => '.jpg',
					'3' => '.png',
					'6' => '.bmp'
				);
				$extension = isset($extensions[$sizes[2]]) ? $extensions[$sizes[2]] : (strpos($_FILES['attachment']['name'], '.') ? strrchr($_FILES['attachment']['name'], '.') : '.bmp');

				$destName = 'avatar_' . $memID . $extension;

				// Remove previous attachments this member might have had.
				removeAttachments('a.ID_MEMBER = ' . $memID);

				if (!is_uploaded_file($_FILES['attachment']['tmp_name']))
					fatal_lang_error('smf124');

				if (!is_writable($modSettings['attachmentUploadDir']))
					fatal_lang_error('attachments_no_write');

				db_query("
					INSERT INTO {$db_prefix}attachments
						(ID_MEMBER, filename, size)
					VALUES ($memID, '$destName', " . filesize($_FILES['attachment']['tmp_name']) . ")", __FILE__, __LINE__);
				$attachID = db_insert_id();
				$destName = $modSettings['attachmentUploadDir'] . '/' . $destName;

				if (!move_uploaded_file($_FILES['attachment']['tmp_name'], $destName))
					fatal_lang_error('smf124');

				// Attempt to chmod it.
				@chmod($destName, 0644);
			}
			$_POST['avatar'] = '';
		}
		// Selected the upload avatar option and had one already uploaded before or didn't upload one.
		else
			$_POST['avatar'] = '';
	}
	else
		$_POST['avatar'] = '';
}

// View a summary.
function summary($memID)
{
	global $context, $themeUser, $txt, $modSettings, $user_info, $user_profile;

	// Attempt to load the member's profile data.
	if (!loadMemberContext($memID) || !isset($themeUser[$memID]))
		fatal_error($txt[453] . ' - ' . $memID, false);

	// Set up the stuff and load the user.
	$context += array(
		'allow_hide_email' => !empty($modSettings['allow_hideEmail']),
		'page_title' => $txt[92] . ' ' . $themeUser[$memID]['username'],
		'member' => &$themeUser[$memID],
		'can_send_pm' => allowedTo('pm_send'),
	);

	// They haven't even been registered for a full day!?
	$days_registered = (int) ((time() - $user_profile[$memID]['dateRegistered']) / (3600 * 24));
	if (empty($user_profile[$memID]['dateRegistered']) || $days_registered < 1)
		$context['member']['posts_per_day'] = $txt[470];
	else
		$context['member']['posts_per_day'] = number_format($context['member']['real_posts'] / $days_registered, 3);

	// Set the age...
	if (empty($context['member']['birth_date']))
	{
		$context['member'] +=  array(
			'age' => &$txt[470],
			'today_is_birthday' => false
		);
	}
	else
	{
		list ($birth_year, $birth_month, $birth_day) = sscanf($context['member']['birth_date'], '%d-%d-%d');
		$datearray = getdate(forum_time());
		$context['member'] += array(
			'age' => empty($birth_year) ? $txt[470] : $datearray['year'] - $birth_year - (($datearray['mon'] > $birth_month || ($datearray['mon'] == $birth_month && $datearray['mday'] >= $birth_day)) ? 0 : 1),
			'today_is_birthday' => $datearray['mon'] == $birth_month && $datearray['mday'] == $birth_day
		);
	}
	if (allowedTo('moderate_forum'))
		$context['member']['hostname'] = preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $themeUser[$memID]['ip']) == 1 && empty($modSettings['disableHostnameLookup']) ? @gethostbyaddr($themeUser[$memID]['ip']) : '';
}

// Show all posts by the current user
function showPosts($memID)
{
	global $txt, $user_info, $scripturl, $modSettings, $db_prefix;
	global $context, $user_profile, $ID_MEMBER, $sourcedir;

	// If just deleting a message, do it and then redirect back.
	if (isset($_GET['delete']))
	{
		checkSession('get');

		// We can be lazy, since removeMessage() will check the permissions for us.
		require_once($sourcedir . '/RemoveTopic.php');
		removeMessage((int) $_GET['delete']);

		// Back to... where we are now ;).
		redirectexit('action=profile;u=' . $memID . ';sa=showPosts;start=' . $_GET['start']);
	}

	// Default to 10.
	if (empty($_REQUEST['viewscount']) || !is_numeric($_REQUEST['viewscount']))
		$_REQUEST['viewscount'] = '10';

	$request = db_query("
		SELECT COUNT(m.ID_MSG)
		FROM {$db_prefix}messages AS m, {$db_prefix}boards AS b
		WHERE m.ID_MEMBER = $memID
			AND b.ID_BOARD = m.ID_BOARD
			AND $user_info[query_see_board]", __FILE__, __LINE__);
	list ($msgCount) = mysql_fetch_row($request);
	mysql_free_result($request);

	// View all the topics, or just a few?
	$maxIndex = isset($_REQUEST['view']) && $_REQUEST['view'] == 'all' ? $msgCount : $modSettings['defaultMaxMessages'];

	// Make sure the starting place makes sense and construct our friend the page index.
	$context['page_index'] = constructPageIndex($scripturl . '?action=profile;u=' . $memID . ';sa=showPosts', $_REQUEST['start'], $msgCount, $maxIndex);
	$context['start'] = $_REQUEST['start'];
	$context['current_page'] = $context['start'] / $maxIndex;
	$context['current_member'] = $memID;

	$context['page_title'] = $txt[458] . ' ' . $user_profile[$memID]['realName'];

	// Find this user's posts.
	$request = db_query("
		SELECT
			t.numReplies, c.name as cname, b.name AS bname, b.ID_BOARD, m.body, c.ID_CAT,
			m.smileysEnabled, m.subject, m.posterTime, m.ID_TOPIC, m.ID_MSG, t.ID_MEMBER_STARTED,
			t.ID_FIRST_MSG, t.ID_LAST_MSG
		FROM {$db_prefix}messages AS m, {$db_prefix}topics AS t, {$db_prefix}boards AS b, {$db_prefix}categories AS c, {$db_prefix}members AS mem
		WHERE m.ID_MEMBER = $memID
			AND m.ID_TOPIC = t.ID_TOPIC
			AND t.ID_BOARD = b.ID_BOARD
			AND b.ID_CAT = c.ID_CAT
			AND $user_info[query_see_board]
			AND mem.ID_MEMBER = m.ID_MEMBER
		ORDER BY m.ID_MSG DESC
		LIMIT $_REQUEST[start], $maxIndex", __FILE__, __LINE__);
	// Start counting at the number of the first message displayed.
	$counter = $_REQUEST['start'];
	$context['posts'] = array();
	$board_ids = array('own' => array(), 'any' => array());
	while ($row = mysql_fetch_assoc($request))
	{
		// Censor....
		censorText($row['body']);
		censorText($row['subject']);

		// Do the code.
		$row['body'] = doUBBC($row['body'], $row['smileysEnabled']);

		// And the array...
		$context['posts'][++$counter] = array(
			'body' => $row['body'],
			'counter' => $counter,
			'category' => array(
				'name' => $row['cname'],
				'id' => $row['ID_CAT']
			),
			'board' => array(
				'name' => $row['bname'],
				'id' => $row['ID_BOARD']
			),
			'topic' => $row['ID_TOPIC'],
			'subject' => $row['subject'],
			'start' => 'msg' . $row['ID_MSG'],
			'time' => timeformat($row['posterTime']),
			'timestamp' => $row['posterTime'],
			'id' => $row['ID_MSG'],
			'can_reply' => false,
			'can_mark_notify' => false,
			'can_delete' => false,
			'delete_possible' => $row['ID_FIRST_MSG'] != $row['ID_MSG'] || $row['ID_LAST_MSG'] == $row['ID_MSG']
		);

		if ($ID_MEMBER == $row['ID_MEMBER_STARTED'])
			$board_ids['own'][$row['ID_BOARD']][] = $counter;
		$board_ids['any'][$row['ID_BOARD']][] = $counter;
	}
	mysql_free_result($request);

	// These are all the permissions that are different from board to board..
	$permissions = array(
		'own' => array(
			'post_reply_own' => 'can_reply',
			'remove_own' => 'can_delete',
		),
		'any' => array(
			'post_reply_any' => 'can_reply',
			'mark_any_notify' => 'can_mark_notify',
			'remove_any' => 'can_delete',
		)
	);

	// For every permission in the own/any lists...
	foreach ($permissions as $type => $list)
		foreach ($list as $permission => $allowed)
		{
			// Get the boards they can do this on...
			$boards = boardsAllowedTo($permission);

			// Hmm, they can do it on all boards, can they?
			if (!empty($boards) && $boards[0] == 0)
				$boards = array_keys($board_ids[$type]);

			// Now go through each board they can do the permission on.
			foreach ($boards as $board_id)
			{
				// There aren't any posts displayed from this board.
				if (!isset($board_ids[$type][$board_id]))
					continue;

				// Set the permission to true ;).
				foreach ($board_ids[$type][$board_id] as $counter)
					$context['posts'][$counter][$allowed] = true;
			}
		}

	// Clean up after posts that cannot be deleted.
	foreach ($context['posts'] as $counter => $dummy)
		$context['posts'][$counter]['can_delete'] &= $context['posts'][$counter]['delete_possible'];
}

function statPanel($memID)
{
	global $txt, $scripturl, $db_prefix, $context, $user_profile, $user_info, $modSettings;

	$context['page_title'] = $txt['statPanel_showStats'] . ' ' . $user_profile[$memID]['realName'];

	// General user statistics.
	$timeDays = floor($user_profile[$memID]['totalTimeLoggedIn'] / 86400);
	$timeHours = floor(($user_profile[$memID]['totalTimeLoggedIn'] % 86400) / 3600);
	$context['time_logged_in'] = ($timeDays > 0 ? $timeDays . $txt['totalTimeLogged2'] : '') . ($timeHours > 0 ? $timeHours . $txt['totalTimeLogged3'] : '') . floor(($user_profile[$memID]['totalTimeLoggedIn'] % 3600) / 60) . $txt['totalTimeLogged4'];
	$context['num_posts'] = comma_format($user_profile[$memID]['posts']);

	// Number of topics started.
	$result = db_query("
		SELECT COUNT(ID_TOPIC)
		FROM {$db_prefix}topics
		WHERE ID_MEMBER_STARTED = $memID" . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? "
			AND ID_BOARD != $modSettings[recycle_board]" : ''), __FILE__, __LINE__);
	list ($context['num_topics']) = mysql_fetch_row($result);
	mysql_free_result($result);

	// Number polls started.
	$result = db_query("
		SELECT COUNT(ID_POLL)
		FROM {$db_prefix}topics
		WHERE ID_MEMBER_STARTED = $memID" . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? "
			AND ID_BOARD != $modSettings[recycle_board]" : '') . "
			AND ID_POLL != 0", __FILE__, __LINE__);
	list ($context['num_polls']) = mysql_fetch_row($result);
	mysql_free_result($result);

	// Number polls voted in.
	$result = db_query("
		SELECT COUNT(DISTINCT ID_POLL)
		FROM {$db_prefix}log_polls
		WHERE ID_MEMBER = $memID", __FILE__, __LINE__);
	list ($context['num_votes']) = mysql_fetch_row($result);
	mysql_free_result($result);

	// Format the numbers...
	$context['num_topics'] = comma_format($context['num_topics']);
	$context['num_polls'] = comma_format($context['num_polls']);
	$context['num_votes'] = comma_format($context['num_votes']);

	// Most popular boards by posts / activity.
	$result = db_query("
		SELECT b.ID_BOARD, b.name, COUNT(m.ID_MSG) AS messageCount, b.numPosts
		FROM {$db_prefix}messages AS m, {$db_prefix}boards AS b, {$db_prefix}topics AS t
		WHERE m.ID_MEMBER = $memID
			AND b.ID_BOARD = t.ID_BOARD
			AND t.ID_TOPIC = m.ID_TOPIC
			AND $user_info[query_see_board]
		GROUP BY b.ID_BOARD
		ORDER BY messageCount DESC
		LIMIT 10", __FILE__, __LINE__);
	$context['popular_boards'] = array();
	$context['board_activity'] = array();
	$maxPosts = 0;
	while ($row = mysql_fetch_assoc($result))
	{
		if ($row['messageCount'] > $maxPosts)
			$maxPosts = $row['messageCount'];

		$context['popular_boards'][$row['ID_BOARD']] = array(
			'id' => $row['ID_BOARD'],
			'posts' => $row['messageCount'],
			'href' => $scripturl . '?board=' . $row['ID_BOARD'] . '.0',
			'link' => '<a href="' . $scripturl . '?board=' . $row['ID_BOARD'] . '.0">' . $row['name'] . '</a>',
			'posts_percent' => 0,
		);

		// This should be quicker as it's an easier sort.
		$context['board_activity'][$row['ID_BOARD']] = $row['numPosts'] != 0 ? number_format(($row['messageCount'] * 100) / $row['numPosts'], 2) : 0;
	}
	mysql_free_result($result);

	// Sort the boards out...
	arsort($context['board_activity']);

	foreach ($context['board_activity'] as $ID_BOARD => $dummy)
	{
		$context['board_activity'][$ID_BOARD] = array(
			'id' => $ID_BOARD,
			'href' => $context['popular_boards'][$ID_BOARD]['href'],
			'link' => $context['popular_boards'][$ID_BOARD]['link'],
			'percent' => $dummy
		);

		if ($maxPosts > 0)
			$context['popular_boards'][$ID_BOARD]['posts_percent'] = round(($context['popular_boards'][$ID_BOARD]['posts'] * 100) / $maxPosts, 2);
	}

	// Posting activity by time.
	$result = db_query("
		SELECT
			HOUR(FROM_UNIXTIME(posterTime + " . (($user_info['time_offset'] + $modSettings['time_offset']) * 3600) . ")) AS hour,
			COUNT(ID_MSG) AS postCount
		FROM {$db_prefix}messages
		WHERE ID_MEMBER = $memID
		GROUP BY hour", __FILE__, __LINE__);
	$maxPosts = 0;
	$context['posts_by_time'] = array();
	while ($row = mysql_fetch_assoc($result))
	{
		if ($row['postCount'] > $maxPosts)
			$maxPosts = $row['postCount'];

		$context['posts_by_time'][$row['hour']] = array(
			'hour' => $row['hour'],
			'posts_percent' => $row['postCount']
		);
	}
	mysql_free_result($result);

	if ($maxPosts > 0)
		for ($hour = 0; $hour < 24; $hour++)
		{
			if (!isset($context['posts_by_time'][$hour]))
				$context['posts_by_time'][$hour] = array(
					'hour' => $hour,
					'posts_percent' => 0,
				);
			else
				$context['posts_by_time'][$hour]['posts_percent'] = round(($context['posts_by_time'][$hour]['posts_percent'] * 100) / $maxPosts);
		}

	// Put it in the right order.
	ksort($context['posts_by_time']);
}

function account($memID)
{
	global $context, $user_profile, $txt, $db_prefix;
	global $scripturl, $membergroups, $modSettings, $language_dir;
	global $language, $user_info;

	// Allow an administrator to edit the username?
	$context['allow_edit_username'] = isset($_GET['changeusername']) && allowedTo('admin_forum');

	// You might be allowed to only assign the membergroups, so let's check.
	$context['allow_edit_membergroups'] = allowedTo('manage_membergroups');
	$context['allow_edit_account'] = ($context['user']['is_owner'] && allowedTo('profile_identity_own')) || allowedTo('profile_identity_any');

	// How about their email address... online status, and name?
	$context['allow_hide_email'] = !empty($modSettings['allow_hideEmail']) || allowedTo('moderate_forum');
	$context['allow_hide_online'] = !empty($modSettings['allow_hideOnline']) || allowedTo('moderate_forum');
	$context['allow_edit_name'] = !empty($modSettings['allow_editDisplayName']) || allowedTo('moderate_forum');

	// Load up the existing contextual data.
	$context['member'] = array(
		'id' => $memID,
		'username' => $user_profile[$memID]['memberName'],
		'name' => !isset($user_profile[$memID]['realName']) || $user_profile[$memID]['realName'] == '' ? '' : $user_profile[$memID]['realName'],
		'email' => $user_profile[$memID]['emailAddress'],
		'posts' => empty($user_profile[$memID]['posts']) ? 0: (int) $user_profile[$memID]['posts'],
		'hide_email' => empty($user_profile[$memID]['hideEmail']) ? 0 : $user_profile[$memID]['hideEmail'],
		'show_online' => empty($user_profile[$memID]['showOnline']) ? 0 : $user_profile[$memID]['showOnline'],
		'secret_question' => !isset($user_profile[$memID]['secretQuestion']) ? '' : $user_profile[$memID]['secretQuestion'],
		'is_admin' => !empty($user_profile[$memID]['ID_GROUP']) && $user_profile[$memID]['ID_GROUP'] == 1 ? true : false,
		'registered' => empty($user_profile[$memID]['dateRegistered']) || $user_profile[$memID]['dateRegistered'] == '0000-00-00' ? $txt[470] : strftime('%Y-%m-%d', $user_profile[$memID]['dateRegistered'] + ($user_info['time_offset'] + $modSettings['time_offset']) * 3600),
		'group' => $user_profile[$memID]['ID_GROUP']
	);

	// You need 'manage membergroups' permission for this.
	if ($context['allow_edit_membergroups'])
	{
		$context['member_groups'] = array(
			0 => array(
				'id' => 0,
				'name' => &$txt['no_primary_membergroup'],
				'is_primary' => $user_profile[$memID]['ID_GROUP'] == 0,
				'can_be_additional' => false,
			)
		);
		$curGroups = explode(',', $user_profile[$memID]['additionalGroups']);

		// Load membergroups, but only those groups the user can assign.
		$request = db_query("
			SELECT groupName, ID_GROUP
			FROM {$db_prefix}membergroups
			WHERE ID_GROUP != 3
				AND minPosts = -1
			ORDER BY minPosts, IF(ID_GROUP < 4, ID_GROUP, 4), groupName", __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
		{
			// We should skip the administrator group if they don't have the admin_forum permission!
			if ($row['ID_GROUP'] == 1 && !allowedTo('admin_forum'))
				continue;

			$context['member_groups'][$row['ID_GROUP']] = array(
				'id' => $row['ID_GROUP'],
				'name' => $row['groupName'],
				'is_primary' => $user_profile[$memID]['ID_GROUP'] == $row['ID_GROUP'],
				'is_additional' => in_array($row['ID_GROUP'], $curGroups),
				'can_be_additional' => true,
			);
		}
		mysql_free_result($request);
	}

	$context['languages'] = array();
	if ($context['allow_edit_account'])
	{
		// Are languages user selectable?  If so, get a list.
		if (!empty($modSettings['userLanguage']))
		{
			// Select the default language if the user has no language selected yet.
			$selectedLanguage = empty($user_profile[$memID]['lngfile']) ? $language : $user_profile[$memID]['lngfile'];

			$dir = dir($language_dir);
			while ($entry = $dir->read())
			{
				// Each language file must *at least* have a 'index.LANGUAGENAME.php' file.
				if (substr($entry, 0, 6) == 'index.' && substr($entry, -4) == '.php' && strlen($entry) > 10)
				{
					$context['languages'][] = array(
						'name' => ucfirst(substr($entry, 6, -4)),
						'selected' => $entry == 'index.' . $selectedLanguage . '.php',
						'filename' => substr($entry, 6, -4)
					);
				}
			}
			$dir->close();
		}
	}

	loadThemeOptions($memID);
}

function forumProfile($memID)
{
	global $context, $user_profile;
	global $user_info, $txt, $ID_MEMBER, $modSettings;

	$context['avatar_url'] = $modSettings['avatar_url'];
	$context['max_signature_length'] = $modSettings['max_signatureLength'];
	$context['allow_edit_title'] = allowedTo('profile_title_any') || (allowedTo('profile_title_own') && $context['user']['is_owner']);

	$context['show_spellchecking'] = $modSettings['enableSpellChecking'] && function_exists('pspell_new');

	$context['member'] = array(
		'id' => $memID,
		'gender' => array('name' => empty($user_profile[$memID]['gender']) ? '' : ($user_profile[$memID]['gender'] == 2 ? 'f' : 'm')),
		'birth_date' => !isset($user_profile[$memID]['birthdate']) || $user_profile[$memID]['birthdate'] == '' ? '0000-00-00' : $user_profile[$memID]['birthdate'],
		'location' => !isset($user_profile[$memID]['location']) ? '' : $user_profile[$memID]['location'],
		'title' => !isset($user_profile[$memID]['usertitle']) || $user_profile[$memID]['usertitle'] == '' ? '' : $user_profile[$memID]['usertitle'],
		'blurb' => !isset($user_profile[$memID]['personalText']) ? '' : str_replace(array('<', '>', '&amp;#039;'), array('&lt;', '&gt;', '&#039;'), $user_profile[$memID]['personalText']),
		'signature' => !isset($user_profile[$memID]['signature']) ? '' : str_replace(array('<br />', '<', '>', '"', '\''), array("\n", '&lt;', '&gt;', '$quot;', '&#039;'), $user_profile[$memID]['signature']),
		'karma' => array(
			'good' => empty($user_profile[$memID]['karmaGood']) ? '0' : $user_profile[$memID]['karmaGood'],
			'bad' => empty($user_profile[$memID]['karmaBad']) ? '0' : $user_profile[$memID]['karmaBad'],
		),
		'avatar' => array(
			'name' => &$user_profile[$memID]['avatar'],
			'custom' => stristr($user_profile[$memID]['avatar'], 'http://') ? $user_profile[$memID]['avatar'] : 'http://',
			'selection' => $user_profile[$memID]['avatar'] == '' || stristr($user_profile[$memID]['avatar'], 'http://') ? '' : $user_profile[$memID]['avatar'],
			'ID_ATTACH' => &$user_profile[$memID]['ID_ATTACH'],
			'filename' => &$user_profile[$memID]['filename'],
			'allow_external' => !empty($modSettings['avatar_allow_external_url']) && (allowedTo('profile_remote_avatar') || !$context['user']['is_owner'])
		),
		'icq' => array('name' => !isset($user_profile[$memID]['ICQ']) ? '' : $user_profile[$memID]['ICQ']),
		'aim' => array('name' => empty($user_profile[$memID]['AIM']) ? '' : str_replace('+', ' ', $user_profile[$memID]['AIM'])),
		'yim' => array('name' => empty($user_profile[$memID]['YIM']) ? '' : $user_profile[$memID]['YIM']),
		'msn' => array('name' => empty($user_profile[$memID]['MSN']) ? '' : $user_profile[$memID]['MSN']),
		'website' => array(
			'title' => !isset($user_profile[$memID]['websiteTitle']) ? '' : $user_profile[$memID]['websiteTitle'],
			'url' => !isset($user_profile[$memID]['websiteUrl']) ? '' : $user_profile[$memID]['websiteUrl'],
		),
	);

	// Split up the birthdate....
	list ($uyear, $umonth, $uday) = explode('-', $context['member']['birth_date']);
	$context['member']['birth_date'] = array(
		'year' => $uyear,
		'month' => $umonth,
		'day' => $uday
	);

	if ($user_profile[$memID]['avatar'] == '' && $user_profile[$memID]['ID_ATTACH'] > 0 && !empty($modSettings['avatar_allow_upload']))
		$context['member']['avatar'] += array(
			'choice' => 'upload',
			'server_pic' => 'blank.gif',
			'external' => 'http://'
		);
	elseif (stristr($user_profile[$memID]['avatar'], 'http://') && $context['member']['avatar']['allow_external'])
		$context['member']['avatar'] += array(
			'choice' => 'external',
			'server_pic' => 'blank.gif',
			'external' => $user_profile[$memID]['avatar']
		);
	elseif (file_exists($modSettings['avatar_directory'] . '/' . $user_profile[$memID]['avatar']) && !empty($modSettings['avatar_allow_server_stored']))
		$context['member']['avatar'] += array(
			'choice' => 'server_stored',
			'server_pic' => $user_profile[$memID]['avatar'] == '' ? 'blank.gif' : $user_profile[$memID]['avatar'],
			'external' => 'http://'
		);
	else
		$context['member']['avatar'] += array(
			'choice' => 'server_stored',
			'server_pic' => 'blank.gif',
			'external' => 'http://'
		);

	// Get a list of all the avatars.
	if (!empty($modSettings['avatar_allow_server_stored']))
	{
		$context['avatar_list'] = array();
		$context['avatars'] = is_dir($modSettings['avatar_directory']) ? getAvatars('', 0) : array();
	}
	else
		$context['avatars'] = array();

	// Second level selected avatar.
	$context['avatar_selected'] = substr(strrchr($context['member']['avatar']['server_pic'], '/'), 1);

	loadThemeOptions($memID);
}

// Recursive function to retrieve avatar files
function getAvatars($directory, $level)
{
	global $context, $txt, $modSettings;

	$result = array();

	// Open the directory..
	$dir = dir($modSettings['avatar_directory'] . (!empty($directory) ? '/' : '') . $directory);
	$dirs = array();
	$files = array();

	if (!$dir)
		return array();

	while ($line = $dir->read())
	{
		if (in_array($line, array('.', '..', 'blank.gif', 'index.php')))
			continue;

		if (is_dir($modSettings['avatar_directory'] . '/' . $directory . (!empty($directory) ? '/' : '') . $line))
			$dirs[] = $line;
		else
			$files[] = $line;
	}
	$dir->close();

	// Sort the results...
	natcasesort($dirs);
	natcasesort($files);

	if ($level == 0)
	{
		$result[] = array(
			'filename' => 'blank.gif',
			'checked' => in_array($context['member']['avatar']['server_pic'], array('', 'blank.gif')),
			'name' => &$txt[422],
			'is_dir' => false
		);
	}

	foreach ($dirs as $line)
	{
		$tmp = getAvatars($directory . (!empty($directory) ? '/' : '') . $line, $level + 1);
		if (!empty($tmp))
			$result[] = array(
				'filename' => htmlspecialchars($line),
				'checked' => strpos($context['member']['avatar']['server_pic'], $line . '/') !== false,
				'name' => '[' . htmlspecialchars(str_replace('_', ' ', $line)) . ']',
				'is_dir' => true,
				'files' => $tmp
		);
		unset($tmp);
	}

	foreach ($files as $line)
	{
		$filename = substr($line, 0, (strlen($line) - strlen(strrchr($line, '.'))));
		$extension = substr(strrchr($line, '.'), 1);

		// Make sure it is an image.
		if (strcasecmp($extension, 'gif') != 0 && strcasecmp($extension, 'jpg') != 0 && strcasecmp($extension, 'jpeg') != 0 && strcasecmp($extension, 'png') != 0 && strcasecmp($extension, 'bmp') != 0)
			continue;

		$result[] = array(
			'filename' => htmlspecialchars($line),
			'checked' => $line == $context['member']['avatar']['server_pic'],
			'name' => htmlspecialchars(str_replace('_', ' ', $filename)),
			'is_dir' => false
		);
		if ($level == 1)
			$context['avatar_list'][] = $directory . '/' . $line;
	}

	return $result;
}

function theme($memID)
{
	global $txt, $context, $user_profile, $db_prefix, $modSettings, $settings, $user_info;

	$request = db_query("
		SELECT value
		FROM {$db_prefix}themes
		WHERE ID_THEME = " . (int) $user_profile[$memID]['ID_THEME'] . "
			AND variable = 'name'
		LIMIT 1", __FILE__, __LINE__);
	list ($name) = mysql_fetch_row($request);
	mysql_free_result($request);

	$context['member'] = array(
		'id' => (int) $user_profile[$memID]['ID_MEMBER'],
		'theme' => array(
			'id' => $user_profile[$memID]['ID_THEME'],
			'name' => empty($user_profile[$memID]['ID_THEME']) ? $txt['theme_forum_default'] : $name
		),
		'smiley_set' => array(
			'id' => empty($user_profile[$memID]['smileySet']) ? (!empty($settings['smiley_sets_default']) ? $settings['smiley_sets_default'] : $modSettings['smiley_sets_default']) : $user_profile[$memID]['smileySet']
		),
		'time_format' => !isset($user_profile[$memID]['timeFormat']) ? '' : $user_profile[$memID]['timeFormat'],
		'time_offset' => empty($user_profile[$memID]['timeOffset']) ? '0' : $user_profile[$memID]['timeOffset'],
	);

	$context['easy_timeformats'] = array(
		array('format' => '', 'title' => $txt['timeformat_easy0']),
		array('format' => '%B %d, %Y, %I:%M:%S %p', 'title' => $txt['timeformat_easy1']),
		array('format' => '%B %d, %Y, %H:%M:%S', 'title' => $txt['timeformat_easy2']),
		array('format' => '%Y-%m-%d, %H:%M:%S', 'title' => $txt['timeformat_easy3']),
		array('format' => '%d %B %Y, %H:%M:%S', 'title' => $txt['timeformat_easy4']),
		array('format' => '%d-%m-%Y, %H:%M:%S', 'title' => $txt['timeformat_easy5'])
	);

	$context['current_forum_time'] = timeformat(time() - $user_info['time_offset'] * 3600, false);

	$context['smiley_sets'] = explode(',', 'none,' . $modSettings['smiley_sets_known']);
	$set_names = explode("\n", $txt['smileys_none'] . "\n" . $modSettings['smiley_sets_names']);
	foreach ($context['smiley_sets'] as $i => $set)
	{
		$context['smiley_sets'][$i] = array(
			'id' => $set,
			'name' => $set_names[$i],
			'selected' => $set == $context['member']['smiley_set']['id']
		);

		if ($context['smiley_sets'][$i]['selected'])
			$context['member']['smiley_set']['name'] = $set_names[$i];
	}

	loadThemeOptions($memID);

	loadLanguage('Settings');
}

// Display the notifications and settings for changes.
function notification($memID)
{
	global $txt, $db_prefix, $scripturl, $user_profile, $user_info, $context, $ID_MEMBER, $modSettings;

	// All the boards with noficiation on..
	$request = db_query("
		SELECT b.ID_BOARD, b.name, lb.logTime AS boardRead, b.lastUpdated
		FROM {$db_prefix}log_notify AS ln, {$db_prefix}boards AS b
			LEFT JOIN {$db_prefix}log_boards AS lb ON (lb.ID_BOARD = b.ID_BOARD AND lb.ID_MEMBER = $ID_MEMBER)
		WHERE ln.ID_MEMBER = $memID
			AND b.ID_BOARD = ln.ID_BOARD
			AND $user_info[query_see_board]
		ORDER BY b.boardOrder", __FILE__, __LINE__);
	$context['board_notifications'] = array();
	while ($row = mysql_fetch_assoc($request))
	{
		$context['board_notifications'][] = array(
			'id' => $row['ID_BOARD'],
			'name' => $row['name'],
			'href' => $scripturl . '?board=' . $row['ID_BOARD'] . '.0',
			'link' => '<a href="' . $scripturl . '?board=' . $row['ID_BOARD'] . '.0">' . $row['name'] . '</a>',
			'new' => $row['boardRead'] < $row['lastUpdated']
		);
	}

	$request = db_query("
		SELECT COUNT(t.ID_TOPIC)
		FROM {$db_prefix}log_notify AS ln, {$db_prefix}boards AS b, {$db_prefix}topics AS t
		WHERE ln.ID_MEMBER = $memID
			AND t.ID_TOPIC = ln.ID_TOPIC
			AND b.ID_BOARD = t.ID_BOARD
			AND $user_info[query_see_board]", __FILE__, __LINE__);
	list ($num_topics) = mysql_fetch_row($request);
	mysql_free_result($request);

	$context['page_index'] = constructPageIndex($scripturl . '?action=profile;u=' . $memID . ';sa=notification', $_REQUEST['start'], $num_topics, $modSettings['defaultMaxMessages']);

	// All the topics with notification on...
	$request = db_query("
		SELECT
			IFNULL(lt.logTime, IFNULL(lmr.logTime, 0)) AS isRead, b.ID_BOARD, b.name,
			t.ID_TOPIC, ms.subject, ms.ID_MEMBER, IFNULL(mem.realName, ms.posterName) AS realName,
			GREATEST(ml.posterTime, ml.modifiedTime) AS topicTime
		FROM {$db_prefix}log_notify AS ln, {$db_prefix}boards AS b, {$db_prefix}topics AS t, {$db_prefix}messages AS ms, {$db_prefix}messages AS ml
			LEFT JOIN {$db_prefix}members AS mem ON (mem.ID_MEMBER = ms.ID_MEMBER)
			LEFT JOIN {$db_prefix}log_topics AS lt ON (lt.ID_TOPIC = t.ID_TOPIC AND lt.ID_MEMBER = $ID_MEMBER)
			LEFT JOIN {$db_prefix}log_mark_read AS lmr ON (lmr.ID_BOARD = b.ID_BOARD AND lmr.ID_MEMBER = $ID_MEMBER)
		WHERE ln.ID_MEMBER = $memID
			AND t.ID_TOPIC = ln.ID_TOPIC
			AND ms.ID_MSG = t.ID_FIRST_MSG
			AND ml.ID_MSG = t.ID_LAST_MSG
			AND b.ID_BOARD = t.ID_BOARD
			AND $user_info[query_see_board]
		ORDER BY ms.ID_MSG DESC
		LIMIT $_REQUEST[start], $modSettings[defaultMaxMessages]", __FILE__, __LINE__);
	$context['topic_notifications'] = array();
	while ($row = mysql_fetch_assoc($request))
	{
		censorText($row['subject']);

		$context['topic_notifications'][] = array(
			'id' => $row['ID_TOPIC'],
			'poster' => array(
				'id' => $row['ID_MEMBER'],
				'name' => $row['realName'],
				'href' => empty($row['ID_MEMBER']) ? '' : $scripturl . '?action=profile;u=' . $row['ID_MEMBER'],
				'link' => empty($row['ID_MEMBER']) ? $row['realName'] : '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['realName'] . '</a>'
			),
			'subject' => $row['subject'],
			'href' => $scripturl . '?topic=' . $row['ID_TOPIC'] . '.0',
			'link' => '<a href="' . $scripturl . '?topic=' . $row['ID_TOPIC'] . '.0">' . $row['subject'] . '</a>',
			'new' => $row['isRead'] < $row['topicTime'],
			'newtime' => $row['isRead'],
			'new_href' => $scripturl . '?topic=' . $row['ID_TOPIC'] . '.from' . $row['isRead'] . '#new',
			'new_link' => '<a href="' . $scripturl . '?topic=' . $row['ID_TOPIC'] . '.from' . $row['isRead'] . '#new">' . $row['subject'] . '</a>',
			'board' => array(
				'id' => $row['ID_BOARD'],
				'name' => $row['name'],
				'href' => $scripturl . '?board=' . $row['ID_BOARD'] . '.0',
				'link' => '<a href="' . $scripturl . '?board=' . $row['ID_BOARD'] . '.0">' . $row['name'] . '</a>'
			)
		);
	}
	mysql_free_result($request);

	// What options are set?
	$context['member'] = array(
		'id' => $memID,
		'notify_announcements' => $user_profile[$memID]['notifyAnnouncements'],
		'notify_once' => $user_profile[$memID]['notifyOnce']
	);

	// How many rows can we expect?
	$context['num_rows'] = array(
		'topic' => count($context['topic_notifications']) + 3,
		'board' => count($context['board_notifications']) + 2
	);

	loadThemeOptions($memID);
}

function pmprefs($memID)
{
	global $txt, $user_profile, $db_prefix, $context, $db_prefix;

	// Tell the template what they are....
	$context['send_email'] = $user_profile[$memID]['im_email_notify'];

	if ($user_profile[$memID]['im_ignore_list'] != '*')
	{
		$result = db_query("
			SELECT memberName
			FROM {$db_prefix}members
			WHERE FIND_IN_SET(ID_MEMBER, '" . $user_profile[$memID]['im_ignore_list']. "')
			LIMIT " . (substr_count($user_profile[$memID]['im_ignore_list'], ',') + 1), __FILE__, __LINE__);
		$im_ignore_list = '';
		while ($row = mysql_fetch_assoc($result))
			$im_ignore_list .= "\n" . $row['memberName'];
		mysql_free_result($result);

		$im_ignore_list = substr($im_ignore_list, 1);
	}
	else
		$im_ignore_list = '*';

	$context['ignore_list'] = $im_ignore_list;
	$context['member']['id'] = $memID;
	$context['page_title'] = $txt['pmprefs'] . ': ' . $txt[144];

	loadThemeOptions($memID);
}

// Present a screen to make sure the user wants to be deleted
function deleteAccount($memID)
{
	global $txt, $context, $ID_MEMBER;

	if (!$context['user']['is_owner'])
		isAllowedTo('profile_remove_any');
	elseif (!allowedTo('profile_remove_any'))
		isAllowedTo('profile_remove_own');

	$context['member']['id'] = $memID;
	$context['member']['is_owner'] = $memID == $ID_MEMBER;
	$context['page_title'] = $txt['deleteAccount'] . ': ' . $txt[144];
}

function deleteAccount2($profile_vars, $post_errors, $memID)
{
	global $ID_MEMBER, $user_info, $sourcedir, $context, $db_prefix;

	if (!$context['user']['is_owner'])
		isAllowedTo('profile_remove_any');
	elseif (!allowedTo('profile_remove_any'))
		isAllowedTo('profile_remove_own');

	checkSession();

	// This file is needed for the deleteMembers function.
	require_once($sourcedir . '/ManageMembers.php');

	// Do you have permission to delete others profiles, or is that your profile you wanna delete?
	if ($memID != $ID_MEMBER)
	{
		isAllowedTo('profile_remove_any');

		// Now, have you been naughty and need your posts deleting?
		if ($_POST['remove_type'] != 'none')
		{
			// Include RemoveTopics - essential for this type of work!
			require_once($sourcedir . '/RemoveTopic.php');

			// First off we delete any topics the member has started - if they wanted topics being done.
			if ($_POST['remove_type'] == 'topics')
			{
				// Fetch all topics started by this user within the time period.
				$request = db_query("
					SELECT t.ID_TOPIC
					FROM {$db_prefix}topics AS t
					WHERE t.ID_MEMBER_STARTED = $memID", __FILE__, __LINE__);
				$topicIDs = array();
				while ($row = mysql_fetch_assoc($request))
					$topicIDs[] = $row['ID_TOPIC'];
				mysql_free_result($request);

				// Actually remove the topics.
				removeTopics($topicIDs);
			}

			// Now delete the remaining messages.
			$request = db_query("
				SELECT m.ID_MSG
				FROM {$db_prefix}messages AS m, {$db_prefix}topics AS t
				WHERE m.ID_MEMBER = $memID
					AND m.ID_TOPIC = t.ID_TOPIC
					AND t.ID_FIRST_MSG != m.ID_MSG", __FILE__, __LINE__);
			// This could take a while... but ya know it's gonna be worth it in the end.
			while ($row = mysql_fetch_assoc($request))
				removeMessage($row['ID_MSG']);
			mysql_free_result($request);
		}

		// Only delete this poor members account if they are actually being booted out of camp.
		if (isset($_POST['deleteAccount']))
			deleteMembers($memID);
	}
	// Also check if you typed your password correctly.
	elseif (empty($post_errors))
		deleteMembers($memID);
}

// This function 'remembers' the profile changes a user made after erronious input.
function rememberPostData()
{
	global $context, $scripturl, $txt, $modSettings, $ID_MEMBER, $user_profile, $user_info;

	// Overwrite member settings with the ones you selected.
	$context['member'] = array(
		'is_owner' => $_REQUEST['userID'] == $ID_MEMBER,
		'username' => $user_profile[$_REQUEST['userID']]['memberName'],
		'name' => !isset($_POST['realName']) || $_POST['realName'] == '' ? $user_profile[$_REQUEST['userID']]['memberName'] : stripslashes($_POST['realName']),
		'id' => (int) $_REQUEST['userID'],
		'title' => !isset($_POST['usertitle']) || $_POST['usertitle'] == '' ? '' : stripslashes($_POST['usertitle']),
		'email' => isset($_POST['emailAddress']) ? $_POST['emailAddress'] : '',
		'hide_email' => empty($_POST['hideEmail']) ? 0 : 1,
		'show_online' => empty($_POST['showOnline']) ? 0 : 1,
		'registered' => empty($_POST['dateRegistered']) || $_POST['dateRegistered'] == '0000-00-00' ? $txt[470] : strftime('%Y-%m-%d', $_POST['dateRegistered']),
		'blurb' => !isset($_POST['personalText']) ? '' : str_replace(array('<', '>', '&amp;#039;'), array('&lt;', '&gt;', '&#039;'), stripslashes($_POST['personalText'])),
		'gender' => array(
			'name' => empty($_POST['gender']) ? '' : ($_POST['gender'] == 2 ? 'f' : 'm')
		),
		'website' => array(
			'title' => !isset($_POST['websiteTitle']) ? '' : stripslashes($_POST['websiteTitle']),
			'url' => !isset($_POST['websiteUrl']) ? '' : stripslashes($_POST['websiteUrl']),
		),
		'birth_date' => array(
			'month' => empty($_POST['bday1']) ? '00' : (int) $_POST['bday1'],
			'day' => empty($_POST['bday2']) ? '00' : (int) $_POST['bday2'],
			'year' => empty($_POST['bday3']) ? '0000' : (int) $_POST['bday3']
		),
		'signature' => !isset($_POST['signature']) ? '' : str_replace(array('<', '>'), array('&lt;', '&gt;'), $_POST['signature']),
		'location' => !isset($_POST['location']) ? '' : stripslashes($_POST['location']),
		'icq' => array(
			'name' => !isset($_POST['icq']) ? '' : stripslashes($_POST['ICQ'])
		),
		'aim' => array(
			'name' => empty($_POST['aim']) ? '' : str_replace('+', ' ', $_POST['AIM'])
		),
		'yim' => array(
			'name' => empty($_POST['yim']) ? '' : stripslashes($_POST['YIM'])
		),
		'msn' => array(
			'name' => empty($_POST['msn']) ? '' : stripslashes($_POST['MSN'])
		),
		'posts' => empty($_POST['posts']) ? 0 : (int) $_POST['posts'],
		'avatar' => array(
			'name' => &$_POST['avatar'],
			'custom' => stristr($_POST['avatar'], 'http://') ? $_POST['avatar'] : 'http://',
			'selection' => $_POST['avatar'] == '' || stristr($_POST['avatar'], 'http://') ? '' : $_POST['avatar']
		),
		'karma' => array(
			'good' => empty($_POST['karmaGood']) ? '0' : $_POST['karmaGood'],
			'bad' => empty($_POST['karmaBad']) ? '0' : $_POST['karmaBad'],
		),
		'time_format' => !isset($_POST['timeFormat']) ? '' : stripslashes($_POST['timeFormat']),
		'time_offset' => empty($_POST['timeOffset']) ? '0' : $_POST['timeOffset'],
		'secret_question' => !isset($_POST['secretQuestion']) ? '' : stripslashes($_POST['secretQuestion']),
		'theme' => array(
			'id' => isset($context['member']['theme']['id']) ? $context['member']['theme']['id'] : 0,
			'name' => isset($context['member']['theme']['name']) ? $context['member']['theme']['name'] : '',
		),
		'notify_announcements' => empty($_POST['notifyAnnouncements']) ? 0 : 1,
		'notify_once' => empty($_POST['notifyOnce']) ? 0 : 1,
		'avatar' => array(
			'choice' => empty($_POST['avatar_choice']) ? 'server_stored' : $_POST['avatar_choice'],
			'external' => empty($_POST['userpicpersonal']) ? 'http://' : $_POST['userpicpersonal'],
			'ID_ATTACH' => empty($_POST['ID_ATTACH']) ? '0' : $_POST['ID_ATTACH']
		),
		'group' => isset($_POST['ID_GROUP']) ? $_POST['ID_GROUP'] : 0,
		'smiley_set' => array(
			'id' => isset($_POST['smileySet']) ? $_POST['smileySet'] : $context['member']['smiley_set'],
			'name' => isset($context['member']['smiley_set']['name']) ? $context['member']['smiley_set']['name'] : ''
		),
	);

	// Overwrite the currently set membergroups with those you just selected.
	if (allowedTo('manage_membergroups') && isset($_POST['ID_GROUP']))
	{
		foreach ($context['member_groups'] as $ID_GROUP => $dummy)
		{
			$context['member_groups'][$ID_GROUP]['is_primary'] = $ID_GROUP == $_POST['ID_GROUP'];
			$context['member_groups'][$ID_GROUP]['is_additional'] = !empty($_POST['additionalGroups']) && in_array($ID_GROUP, $_POST['additionalGroups']);
		}
	}

	loadThemeOptions($_REQUEST['userID']);
}

function loadThemeOptions($memID)
{
	global $context, $options, $db_prefix, $user_profile;

	if (isset($_POST['options']) && isset($_POST['default_options']))
		$_POST['options'] += $_POST['default_options'];

	if ($context['user']['is_owner'])
		$context['member']['options'] = $options;
	else
	{
		$request = db_query("
			SELECT variable, value
			FROM {$db_prefix}themes
			WHERE ID_THEME IN (1, " . (int) $user_profile[$memID]['ID_THEME'] . ")
				AND ID_MEMBER = $memID", __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
		{
			if (isset($_POST['options'][$row['variable']]))
				$row['value'] = $_POST['options'][$row['variable']];
			$context['member']['options'][$row['variable']] = $row['value'];
		}
		mysql_free_result($request);
	}
}

?>