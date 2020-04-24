<?php
/******************************************************************************
* index.php                                                                   *
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

/*	This, as you have probably guessed, is the crux on which SMF functions.
	Everything should start here, so all the setup and security is done
	properly.  The most interesting part of this file is the action array in
	the smf_main() function.  It is formatted as so:

		'action-in-url' => array('Source-File.php', 'FunctionToCall'),

	Then, you can access the FunctionToCall() function from Source-File.php
	with the URL index.php?action=action-in-url.  Relatively simple, no?
*/

$forum_version = 'SMF 1.0';

// Get everything started up...
define('SMF', 1);
@set_magic_quotes_runtime(0);
error_reporting(E_ALL);
$time_start = microtime();

// Load the settings...
require_once(dirname(__FILE__) . '/Settings.php');

// Just in case something happens to Settings.php, let's try to at least load an error screen.
if (!isset($sourcedir))
	$sourcedir = dirname(__FILE__) . '/Sources';

// And important includes.
require_once($sourcedir . '/QueryString.php');
require_once($sourcedir . '/Subs.php');
require_once($sourcedir . '/Errors.php');
require_once($sourcedir . '/Load.php');
require_once($sourcedir . '/Security.php');

// If $maintenance is set specifically to 2, then we're upgrading or something.
if ($maintenance == 2)
	db_fatal_error();

// Connect to the MySQL database.
if (empty($db_persist))
	$db_connection = @mysql_connect($db_server, $db_user, $db_passwd);
else
	$db_connection = @mysql_pconnect($db_server, $db_user, $db_passwd);

// Show an error if the connection couldn't be made.
if (!$db_connection || !@mysql_select_db($db_name, $db_connection))
	db_fatal_error();

// Load the settings from the settings table, and perform operations like optimizing.
reloadSettings();
// Clean the request variables, add slashes, etc.
cleanRequest();
$context = array();

// Determine if this is should be using WAP, WAP2, or imode.
define('WIRELESS', isset($_REQUEST['wap']) || isset($_REQUEST['wap2']) || isset($_REQUEST['imode']));

// Some settings and headers are different for wireless protocols.
if (WIRELESS)
{
	define('WIRELESS_PROTOCOL', isset($_REQUEST['wap']) ? 'wap' : (isset($_REQUEST['wap2']) ? 'wap2' : (isset($_REQUEST['imode']) ? 'imode' : '')));

	// Some cellphones can't handle output compression...
	$modSettings['enableCompressedOutput'] = '0';
	$modSettings['defaultMaxMessages'] = 5;
	$modSettings['defaultMaxTopics'] = 9;

	// Wireless protocol header.
	if (WIRELESS_PROTOCOL == 'wap')
		header('Content-Type: text/vnd.wap.wml');
}

// Check if compressed output is enabled, supported, and not already being done.
if (!empty($modSettings['enableCompressedOutput']) && !headers_sent() && ob_get_length() == 0)
{
	// If zlib is being used, turn off output compression.
	if (@ini_get('zlib.output_compression') == '1' || @ini_get('output_handler') == 'ob_gzhandler')
		$modSettings['enableCompressedOutput'] = '0';
	else
		ob_start('ob_gzhandler');
}
// This makes it so headers can be sent!
if (empty($modSettings['enableCompressedOutput']))
	ob_start();

// Register an error handler.
set_error_handler('error_handler');

// Start the session. (assuming it hasn't already been.)
loadSession();

// There's a strange bug in PHP 4.1.2 which makes $_SESSION not work unless you do this...
if (@version_compare(PHP_VERSION, '4.2.0') == -1)
	$HTTP_SESSION_VARS['php_412_bugfix'] = true;

// What function shall we execute? (done like this for memory's sake.)
call_user_func(smf_main());

// Call obExit specially; we're coming from the main area ;).
obExit(null, null, true);

// The main controlling function.
function smf_main()
{
	global $modSettings, $settings, $user_info, $board, $topic, $maintenance, $sourcedir;

	// Load the user's cookie (or set as guest) and load their settings.
	loadUserSettings();

	// Load the current board's information.
	loadBoard();

	// Load the current theme.  (note that ?theme=1 will also work, may be used for guest theming.)
	loadTheme();

	// Check if the user should be disallowed access.
	is_not_banned();

	// Load the current user's permissions.
	loadPermissions();

	// Do some logging if this is not an attachment/avatar or the setting of an option.
	if (empty($_REQUEST['action']) || !in_array($_REQUEST['action'], array('dlattach', 'jsoption')))
	{
		// Log this user as online.
		writeLog();

		// Track forum statistics and hits...?
		if (!empty($modSettings['hitStats']))
			trackStats(array('hits' => '+'));
	}

	// Is the forum in maintenance mode? (doesn't apply to administrators.)
	if (!empty($maintenance) && !allowedTo('admin_forum'))
	{
		// You can only login.... otherwise, you're getting the "maintenance mode" display.
		if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'login2')
		{
			require_once($sourcedir . '/LogInOut.php');
			return 'Login2';
		}
		// Don't even try it, sonny.
		else
		{
			require_once($sourcedir . '/Subs-Auth.php');
			return 'InMaintenance';
		}
	}
	// If guest access is off, a guest can only do one of the very few following actions.
	elseif (empty($modSettings['allow_guestAccess']) && $user_info['is_guest'] && (!isset($_REQUEST['action']) || !in_array($_REQUEST['action'], array('login', 'login2', 'register', 'register2', 'reminder', 'activate', 'help', '.xml'))))
	{
		require_once($sourcedir . '/Subs-Auth.php');
		return 'KickGuest';
	}
	elseif (empty($_REQUEST['action']))
	{
		// Action and board are both empty... BoardIndex!
		if (empty($board) && empty($topic))
		{
			require_once($sourcedir . '/BoardIndex.php');
			return 'BoardIndex';
		}
		// Topic is empty, and action is empty.... MessageIndex!
		elseif (empty($topic))
		{
			require_once($sourcedir . '/MessageIndex.php');
			return 'MessageIndex';
		}
		// Board is not empty... topic is not empty... action is empty.. Display!
		else
		{
			require_once($sourcedir . '/Display.php');
			return 'Display';
		}
	}

	// Here's the monstrous $_REQUEST['action'] array - $_REQUEST['action'] => array($file, $function).
	$actionArray = array(
		'activate' => array('Register.php', 'Activate'),
		'admin' => array('Admin.php', 'Admin'),
		'announce' => array('Post.php', 'AnnounceTopic'),
		'ban' => array('ManageMembers.php', 'Ban'),
		'boardrecount' => array('Admin.php', 'AdminBoardRecount'),
		'calendar' => array('Calendar.php', 'CalendarMain'),
		'collapse' => array('Subs-Boards.php', 'CollapseCategory'),
		'deletemsg' => array('RemoveTopic.php', 'DeleteMessage'),
		'detailedversion' => array('Admin.php', 'VersionDetail'),
		'display' => array('Display.php', 'Display'),
		'dlattach' => array('Display.php', 'Download'),
		'dumpdb' => array('DumpDatabase.php', 'DumpDatabase2'),
		'editagreement' => array('Admin.php', 'EditAgreement'),
		'editnews' => array('Admin.php', 'EditNews'),
		'editpoll' => array('Poll.php', 'EditPoll'),
		'editpoll2' => array('Poll.php', 'EditPoll2'),
		'findmember' => array('Subs-Auth.php', 'JSMembers'),
		'help' => array('Help.php', 'ShowHelp'),
		'helpadmin' => array('Help.php', 'ShowAdminHelp'),
		'im' => array('InstantMessage.php', 'MessageMain'),
		'jsoption' => array('Themes.php', 'SetJavaScript'),
		'lock' => array('LockTopic.php', 'LockTopic'),
		'lockVoting' => array('Poll.php', 'LockVoting'),
		'login' => array('LogInOut.php', 'Login'),
		'login2' => array('LogInOut.php', 'Login2'),
		'logout' => array('LogInOut.php', 'Logout'),
		'mailing' => array('ManageMembers.php', 'MailingList'),
		'maintain' => array('Admin.php', 'Maintenance'),
		'manageattachments' => array('ManageAttachments.php', 'ManageAttachments'),
		'manageboards' => array('ManageBoards.php', 'ManageBoards'),
		'markasread' => array('Subs-Boards.php', 'MarkRead'),
		'membergroups' => array('ManageMembers.php', 'ModifyMembergroups'),
		'mergetopics' => array('SplitTopics.php', 'MergeTopics'),
		'mlist' => array('Memberlist.php', 'Memberlist'),
		'modifycat' => array('ManageBoards.php', 'ModifyCat'),
		'modifykarma' => array('Karma.php', 'ModifyKarma'),
		'modifyModSettings' => array('ModSettings.php', 'ModifyModSettings'),
		'modifyModSettings2' => array('ModSettings.php', 'ModifyModSettings2'),
		'modlog' => array('Modlog.php', 'ViewModlog'),
		'modlog2' => array('Modlog.php', 'ViewModlog2'),
		'modsettings' => array('Admin.php', 'ModifySettings'),
		'modsettings2' => array('Admin.php', 'ModifySettings2'),
		'movetopic' => array('MoveTopic.php', 'MoveTopic'),
		'movetopic2' => array('MoveTopic.php', 'MoveTopic2'),
		'notify' => array('Notify.php', 'Notify'),
		'notifyboard' => array('Notify.php', 'BoardNotify'),
		'optimizetables' => array('Admin.php', 'OptimizeTables'),
		'packageget' => array('PackageGet.php', 'PackageGet'),
		'packages' => array('Packages.php', 'Packages'),
		'permissions' => array('ManagePermissions.php', 'ModifyPermissions'),
		'pgadd' => array('PackageGet.php', 'PackageServerAdd'),
		'pgremove' => array('PackageGet.php', 'PackageServerRemove'),
		'pgbrowse' => array('PackageGet.php', 'PackageGBrowse'),
		'pgdownload' => array('PackageGet.php', 'PackageDownload'),
		'pgupload' => array('PackageGet.php', 'PackageUpload'),
		'pm' => array('InstantMessage.php', 'MessageMain'),
		'post' => array('Post.php', 'Post'),
		'post2' => array('Post.php', 'Post2'),
		'printpage' => array('Printpage.php', 'PrintTopic'),
		'profile' => array('Profile.php', 'ModifyProfile'),
		'profile2' => array('Profile.php', 'ModifyProfile2'),
		'quotefast' => array('Post.php', 'QuoteFast'),
		'quickmod' => array('Subs-Boards.php', 'QuickModeration'),
		'quickmod2' => array('Subs-Boards.php', 'QuickModeration2'),
		'recent' => array('Recent.php', 'RecentPosts'),
		'regcenter' => array('Register.php', 'RegCenter'),
		'register' => array('Register.php', 'Register'),
		'register2' => array('Register.php', 'Register2'),
		'reminder' => array('Reminder.php', 'RemindMe'),
		'removetopic2' => array('RemoveTopic.php', 'RemoveTopic2'),
		'removeoldtopics2' => array('RemoveTopic.php', 'RemoveOldTopics2'),
		'removepoll' => array('Poll.php', 'RemovePoll'),
		'repairboards' => array('RepairBoards.php', 'RepairBoards'),
		'reporttm' => array('SendTopic.php', 'ReportToModerator'),
		'search' => array('Search.php', 'PlushSearch1'),
		'search2' => array('Search.php', 'PlushSearch2'),
		'sendtopic' => array('SendTopic.php', 'SendTopic'),
		'setcensor' => array('Admin.php', 'SetCensor'),
		'setcensor2' => array('Admin.php', 'SetCensor2'),
		'setreserve' => array('ManageMembers.php', 'SetReserve'),
		'setreserve2' => array('ManageMembers.php', 'SetReserve2'),
		'smileys' => array('ManageSmileys.php', 'ManageSmileys'),
		'spellcheck' => array('Subs-Post.php', 'SpellCheck'),
		'splittopics' => array('SplitTopics.php', 'SplitTopics'),
		'stats' => array('Stats.php', 'DisplayStats'),
		'sticky' => array('LockTopic.php', 'Sticky'),
		'theme' => array('Themes.php', 'ThemesMain'),
		'trackip' => array('ManageMembers.php', 'trackIP'),
		'about:mozilla' => array('Karma.php', 'BookOfUnknown'),
		'about:unknown' => array('Karma.php', 'BookOfUnknown'),
		'unread' => array('Recent.php', 'UnreadTopics'),
		'unreadreplies' => array('Recent.php', 'UnreadTopics'),
		'viewErrorLog' => array('Errors.php', 'ViewErrorLog'),
		'viewmembers' => array('ManageMembers.php', 'ViewMembers'),
		'viewprofile' => array('Profile.php', 'ModifyProfile'),
		'vote' => array('Poll.php', 'Vote'),
		'viewquery' => array('ViewQuery.php', 'ViewQuery'),
		'who' => array('Who.php', 'Who'),
		'.xml' => array('News.php', 'ShowXmlFeed'),
	);

	// Get the function and file to include - if it's not there, do the board index.
	if (!isset($_REQUEST['action']) || !isset($actionArray[$_REQUEST['action']]))
	{
		// Catch the action with the theme?
		if (!empty($settings['catch_action']))
		{
			require_once($sourcedir . '/Themes.php');
			return 'WrapAction';
		}

		// Fall through to the board index then...
		require_once($sourcedir . '/BoardIndex.php');
		return 'BoardIndex';
	}

	// Otherwise, it was set - so let's go to that action.
	require_once($sourcedir . '/' . $actionArray[$_REQUEST['action']][0]);
	return $actionArray[$_REQUEST['action']][1];
}

?>