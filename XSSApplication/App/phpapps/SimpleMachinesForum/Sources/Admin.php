<?php
/******************************************************************************
* Admin.php                                                                   *
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

/*	This file, unpredictable as this might be, handles basic administration.
	The most important function in this file for mod makers happens to be the
	updateSettingsFile() function, but it shouldn't be used often anyway.

	void Admin()
		- prepares all the data necessary for the administration front page.
		- uses the Admin template along with the admin sub template.
		- requires the moderate_forum, manage_membergroups, manage_bans,
		  admin_forum, manage_permissions, manage_attachments, manage_smileys,
		  manage_boards, edit_news, or send_mail permission.
		- uses the index administrative area.
		- can be found by going to ?action=admin.

	void EditNews()
		- changes the current news items for the forum.
		- uses the Admin template and edit_news sub template.
		- requires the edit_news permission.
		- writes an entry into the moderation log.
		- uses the edit_news administration area.
		- can be accessed with ?action=editnews.

	void EditAgreement()
		- allows the administrator to edit the registration agreement, and
		  choose whether it should be shown or not.
		- uses the Admin template and the edit_agreement sub template.
		- requires the moderate_forum permission.
		- uses the edit_agreement administration area.
		- writes and saves the agreement to the agreement.txt file.
		- accessed by ?action=editagreement.

	void ModifySettings()
		- shows an interface for the settings in Settings.php to be changed.
		- uses the rawdata sub template (not theme-able.)
		- requires the admin_forum permission.
		- uses the edit_settings administration area.
		- contains the actual array of settings to show from Settings.php.
		- accessed from ?action=modsettings.

	void ModifySettings2()
		- saves those settings set from ?action=modsettings to the
		  Settings.php file.
		- requires the admin_forum permission.
		- contains arrays of the types of data to save into Settings.php.
		- redirects back to ?action=modsettings.
		- accessed from ?action=modsettings2.

	void SetCensor()
		- shows an interface to set and test word censoring.
		- requires the moderate_forum permission.
		- uses the Admin template and the edit_censored sub template.
		- tests the censored word in $_SESSION['test_censor'] if available.
		- uses the censor_vulgar, censor_proper, censorWholeWord, and
		  censorIgnoreCase settings.
		- accessed from ?action=setcensor.

	void SetCensor2()
		- saves those censored words entered from ?action=setcensor.
		- requires the moderate_forum permission.
		- can take either a string - x=y\na=b, or two sets of strings
		  (x\na, y\nb), or even arrays (x, a and y, b.)
		- sets $_SESSION['test_censor'] if necessary.
		- accessed by ?action=setcensor2.

	void OptimizeTables()
		- optimizes all tables in the database and lists how much was saved.
		- requires the admin_forum permission.
		- uses the rawdata sub template (built in.)
		- shows as the maintain_forum admin area.
		- updates the autoOptLastOpt setting such that the tables are not
		  automatically optimized again too soon.
		- accessed from ?action=optimizetables.

	void Maintenance()
		- shows a listing of maintenance options - including repair, recount,
		  optimize, database dump, clear logs, and remove old posts.
		- handles directly the tasks of clearing logs.
		- requires the admin_forum permission.
		- uses the maintain_forum admin area.
		- shows the maintain sub template of the Admin template.
		- accessed by ?action=maintain.

	void AdminBoardRecount()
		- recounts many forum totals that can be recounted automatically
		  without harm.
		- requires the admin_forum permission.
		- shows the maintain_forum admin area.
		- fixes topics with wrong numReplies.
		- updates the numPosts and numTopics of all boards.
		- recounts instantMessages but not unreadMessages.
		- repairs messages pointing to boards with topics pointing to
		  other boards.
		- updates the last message posted in boards and children.
		- updates member count, latest member, topic count, and message count.
		- redirects back to ?action=maintain when complete.
		- accessed via ?action=boardrecount.

	void VersionDetail()
		- parses the comment headers in all files for their version information
		  and outputs that for some javascript to check with simplemacines.org.
		- does not connect directly with simplemachines.org, but rather
		  expects the client to.
		- requires the admin_forum permission.
		- uses the view_versions admin area.
		- loads the view_versions sub template (in the Admin template.)
		- accessed through ?action=detailedversion.

	void updateSettingsFile(array config_vars)
		- updates the Settings.php file with the changes in config_vars.
		- expects config_vars to be an associative array, with the keys as the
		  variable names in Settings.php, and the values the varaible values.
		- does not escape or quote values.
		- preserves case, formatting, and additional options in file.
		- writes nothing if the resulting file would be less than 10 lines
		  in length (sanity check for read lock.)
*/

// The main administration section.
function Admin()
{
	global $sourcedir, $db_prefix, $forum_version, $txt, $scripturl, $context, $user_info;

	// You have to be able to do at least one of the below to see this page.
	isAllowedTo(array('admin_forum', 'manage_permissions', 'moderate_forum', 'manage_membergroups', 'manage_bans', 'send_mail', 'edit_news', 'manage_boards', 'manage_smileys', 'manage_attachments'));

	// Load the common admin stuff... select 'index'.
	adminIndex(isset($_GET['credits']) ? 'credits' : 'index');

	// Find all of this forum's administrators.
	$request = db_query("
		SELECT ID_MEMBER, realName
		FROM {$db_prefix}members
		WHERE ID_GROUP = 1 OR FIND_IN_SET(1, additionalGroups)", __FILE__, __LINE__);
	$context['administrators'] = array();
	while ($row = mysql_fetch_assoc($request))
		$context['administrators'][] = '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['realName'] . '</a>';
	mysql_free_result($request);

	// Some stuff.... :P.
	$context['credits'] = '
<i>Simple Machines wants to thank everyone who helped make SMF 1.0 what it is today; shaping and directing our project, all through the thick and the thin. It wouldn\'t have been possible without you.</i><br />
<div style="margin-top: 1ex;"><i>This includes our users and especially Charter Members - thanks for installing and using our software as well as providing valuable feedback, bug reports, and opinions.</i></div>
<div style="margin-top: 2ex;"><b>Project Managers:</b> Jeff Lewis, Joseph Fung, and David Recordon.</div>
<div style="margin-top: 1ex;"><b>Developers:</b> Unknown W. &quot;[Unknown]&quot; Brackets, Hendrik Jan &quot;Compuart&quot; Visser, Matt &quot;Grudge&quot; Wolf, and Philip &quot;Meriadoc&quot; Renich</div>
<div style="margin-top: 1ex;"><b>Support Specialists:</b>Andrea Hubacher, Alexandre &quot;Ap2&quot; Patenaude, A.M.A, Ben Scott, [darksteel], Douglas &quot;The Bear&quot; Hazard, Horseman, Killer Possum, Mediman, Methonis, Michael &quot;Oldiesmann&quot; Eshom, Omar Bazavilvazo, Osku &quot;Owdy&quot; Uusitupa, Pitti, and Tomer &quot;Lamper&quot; Dean.</div>
<div style="margin-top: 1ex;"><b>Mod Developers:</b> Jack.R.Abbit, Aliencowfarm, Big P., Chris Cromer, Cristi&aacute;n &quot;Anguz&quot; L&aacute;vaque, Daniel Diehl, groundup, James &quot;Cheschire&quot; Yarbro, Jesse &quot;Gobalopper&quot; Reid, Spaceman-Spiff.</div>
<div style="margin-top: 1ex;"><b>Coordinators:</b> Adam &quot;Bostasp&quot; Southall and Peter Duggan.</div>
<div style="margin-top: 2ex;">We would also like to give special thanks to not only these members of the Simple Machines Team, but also Babylonking and Alienine (aka. Adrian) for their work on the default theme!</div>
<div style="margin-top: 1ex;">And for anyone we may have missed, thank you!</div>';

	// This makes it easier to get the latest news with your time format.
	$context['time_format'] = urlencode($user_info['time_format']);

	$context['current_versions'] = array(
		'php' => array('title' => $txt['support_versions_php'], 'version' => PHP_VERSION),
		'mysql' => array('title' => $txt['support_versions_mysql'], 'version' => ''),
		'server' => array('title' => $txt['support_versions_server'], 'version' => $_SERVER['SERVER_SOFTWARE'])
	);
	$context['forum_version'] = $forum_version;

	if (function_exists('gd_info'))
	{
		$temp = gd_info();
		$context['current_versions']['gd'] = array('title' => $txt['support_versions_gd'], 'version' => $temp['GD Version']);
	}

	$request = db_query("
		SELECT VERSION()", __FILE__, __LINE__);
	list ($context['current_versions']['mysql']['version']) = mysql_fetch_row($request);
	mysql_free_result($request);

	$context['can_admin'] = allowedTo('admin_forum');

	$context['sub_template'] = isset($_GET['credits']) ? 'credits' : 'admin';
	$context['page_title'] = isset($_GET['credits']) ? $txt['support_credits_title'] : $txt[208];

	// The format of this array is: permission, action, title, description.
	$quick_admin_tasks = array(
		array('', 'admin;credits', 'support_credits_title', 'support_credits_info'),
		array('admin_forum', 'modifyModSettings', 'modSettings_title', 'modSettings_info'),
		array('admin_forum', 'maintain', 'maintain_title', 'maintain_info'),
		array('manage_permissions', 'permissions', 'edit_permissions', 'edit_permissions_info'),
		array('admin_forum', 'theme;sa=admin;sesc=' . $context['session_id'], 'theme_admin', 'theme_admin_info'),
		array('admin_forum', 'packages', 'package1', 'package_info'),
		array('manage_smileys', 'smileys', 'smileys_manage', 'smileys_manage_info'),
		array('moderate_forum', 'regcenter', 'registration_center', 'registration_center_info'),
	);

	$context['quick_admin_tasks'] = array();
	foreach ($quick_admin_tasks as $task)
	{
		if (!empty($task[0]) && !allowedTo($task[0]))
			continue;

		$context['quick_admin_tasks'][] = array(
			'href' => $scripturl . '?action=' . $task[1],
			'link' => '<a href="' . $scripturl . '?action=' . $task[1] . '">' . $txt[$task[2]] . '</a>',
			'title' => $txt[$task[2]],
			'description' => $txt[$task[3]],
			'is_last' => false
		);
	}

	if (count($context['quick_admin_tasks']) % 2 == 1)
	{
		$context['quick_admin_tasks'][] = array(
			'href' => '',
			'link' => '',
			'title' => '',
			'description' => '',
			'is_last' => true
		);
		$context['quick_admin_tasks'][count($context['quick_admin_tasks']) - 2]['is_last'] = true;
	}
	elseif (count($context['quick_admin_tasks']) != 0)
	{
		$context['quick_admin_tasks'][count($context['quick_admin_tasks']) - 1]['is_last'] = true;
		$context['quick_admin_tasks'][count($context['quick_admin_tasks']) - 2]['is_last'] = true;
	}
}

// Let the administrator(s) edit the news.
function EditNews()
{
	global $txt, $modSettings, $context, $db_prefix, $sourcedir, $user_info;

	// You must have the right session and permissions, or you get no news edit!
	isAllowedTo('edit_news');

	// Just browsing.
	if (!isset($_POST['news']))
	{
		// Load the edit news template and admin bar, etc.
		adminIndex('edit_news');

		// Ready the current news.
		foreach (explode("\n", $modSettings['news']) as $id => $line)
			$context['admin_current_news'][$id] = array(
				'id' => $id,
				'unparsed' => htmlspecialchars(str_replace('<br />', "\n", $line)),
				'parsed' => preg_replace('~<([/]?)form[>]*>~i', '<em class="smalltext">&lt;$1form&gt;</em>', doUBBC($line)),
			);

		$context['sub_template'] = 'edit_news';
		$context['page_title'] = $txt[7];
	}
	// Adding/changing/removing news items.
	else
	{
		checkSession();

		// The 'remove selected' button was pressed.
		if (!empty($_POST['delete_selection']))
		{
			// Store the news temporarily in this array.
			$temp_news = explode("\n", $modSettings['news']);

			// Remove the items that were selected.
			foreach ($temp_news as $i => $news)
				if (in_array($i, $_POST['remove']))
					unset($temp_news[$i]);

			// Update the database.
			updateSettings(array('news' => addslashes(implode("\n", $temp_news))));
		}
		// The 'Save' button was pressed.
		else
		{
			require_once($sourcedir . '/Subs-Post.php');
			foreach ($_POST['news'] as $i => $news)
			{
				if (trim($news) == '')
					unset($_POST['news'][$i]);
				else
					preparsecode($_POST['news'][$i]);
			}

			// Send the new news to the database.
			updateSettings(array('news' => implode("\n", $_POST['news'])));
		}

		// Log this into the moderation log.
		logAction('news');

		redirectexit('action=editnews');
	}
}

// I hereby agree not to be a lazy bum.
function EditAgreement()
{
	global $txt, $boarddir, $context, $modSettings;

	// Not everyone can edit the agreement!
	isAllowedTo('moderate_forum');

	if (isset($_POST['agreement']))
	{
		checkSession();

		// Off it goes to the agreement file.
		$fp = fopen($boarddir . '/agreement.txt', 'w');
		fwrite($fp, str_replace("\r", '', stripslashes($_POST['agreement'])));
		fclose($fp);

		updateSettings(array('requireAgreement' => !empty($_POST['requireAgreement'])));

		redirectexit('action=editagreement');
	}

	// Select 'Edit Agreement' on the admin bar.
	adminIndex('edit_agreement');

	// Get the current agreement.
	$context['agreement'] = file_exists($boarddir . '/agreement.txt') ? htmlspecialchars(implode('', file($boarddir . '/agreement.txt'))) : '';
	$context['warning'] = is_writable($boarddir . '/agreement.txt') ? '' : $txt['smf320'];
	$context['require_agreement'] = !empty($modSettings['requireAgreement']);

	$context['sub_template'] = 'edit_agreement';
	$context['page_title'] = $txt['smf11'];
}

// Basic forum settings - database name, host, etc.
function ModifySettings()
{
	global $scripturl, $context, $settings, $txt, $sc;
	global $language_dir, $boarddir;

	// This is just to keep the database password more secure.
	isAllowedTo('admin_forum');
	checkSession('get');

	// The administration bar......
	adminIndex('edit_settings');

	$context['page_title'] = $txt[222];
	$context['sub_template'] = 'rawdata';

	// Warn the user if the backup of Settings.php failed.
	$settings_not_writable = !is_writable($boarddir . '/Settings.php');
	$settings_backup_fail = !@is_writable($boarddir . '/Settings_bak.php') || !@copy($boarddir . '/Settings.php', $boarddir . '/Settings_bak.php');

	/* If you're writing a mod, it's a bad idea to add things here....
	For each option:
		variable name, description, type (constant), size/possible values, helptext.
	OR	an empty string for a horizontal rule.
	OR	a string for a titled section. */
	$config_vars = array(
		array('db_server', &$txt['smf5'], 'text'),
		array('db_user', &$txt['smf6'], 'text'),
		array('db_passwd', &$txt['smf7'], 'password'),
		array('db_name', &$txt['smf8'], 'text'),
		array('db_prefix', &$txt['smf54'], 'text'),
		array('db_persist', &$txt['db_persist'], 'check', null, 'db_persist'),
		array('db_error_send', &$txt['db_error_send'], 'check'),
		'',
		array('maintenance', &$txt[348], 'check'),
		array('mtitle', &$txt['maintenance1'], 'text', 36),
		array('mmessage', &$txt['maintenance2'], 'text', 36),
		'',
		array('mbname', &$txt[350], 'text', 30),
		array('webmaster_email', &$txt[355], 'text', 30),
		array('cookiename', &$txt[352], 'text', 20),
		'language' => array('language', &$txt['default_language'], 'select', array()),
		'',
		array('boardurl', &$txt[351], 'text', 36),
		array('boarddir', &$txt[356], 'text', 36),
		array('sourcedir', &$txt[360], 'text', 36),
		'',
	);

	// Find the available language files.
	$dir = dir($language_dir);
	while ($entry = $dir->read())
		if (substr($entry, 0, 6) == 'index.' && substr($entry, -4) == '.php' && strlen($entry) > 10)
			$config_vars['language'][3][] = array(substr($entry, 6, -4), ucwords(substr($entry, 6, -4)));
	$dir->close();

	$context['raw_data'] = '
		<form action="' . $scripturl . '?action=modsettings2" method="post" name="settingsForm">
			<table width="100%" border="0" cellspacing="1" cellpadding="0" class="bordercolor" align="center">
				<tr><td>
					<table border="0" cellspacing="0" cellpadding="4" align="center" width="100%">
						<tr class="titlebg">
							<td colspan="2"><img src="' . $settings['images_url'] . '/icons/config_sm.gif" alt="" border="0" align="top" /> ' . $txt[222] . '</td>
						</tr><tr class="windowbg">
							<td colspan="2" class="smalltext" style="padding: 2ex;">' . $txt[347] . '</td>';

	if ($settings_not_writable)
		$context['raw_data'] .= '
						</tr><tr>
							<td class="windowbg2" colspan="2"><div align="center"><b>' . $txt['settings_not_writable'] . '</b></div><br /></td>';
	elseif ($settings_backup_fail)
		$context['raw_data'] .= '
						</tr><tr>
							<td class="windowbg2" colspan="2"><div align="center"><b>' . $txt['smf1'] . '</b></div><br /></td>';

	// Display the options.
	foreach ($config_vars as $config_var)
	{
		$context['raw_data'] .= '
						</tr><tr class="windowbg2">';
		if (is_array($config_var))
		{
			// Global the variable and get its value.
			global $$config_var[0];
			$variable_name = $config_var[0];
			$variable_value = &$$config_var[0];

			$context['raw_data'] .= '
							<td valign="top" width="400"' . ($settings_not_writable ? ' style="color: #777777;"' : '') . '>' . $config_var[1] . ($config_var[2] == 'password' ? '<br /><i>' . $txt['admin_confirm_password'] . '</i>' : '') . '</td>
							<td>';

			// A text box....
			if ($config_var[2] == 'text')
				$context['raw_data'] .= '<input type="text"' . ($settings_not_writable ? ' disabled="disabled"' : '') . ' name="' . $variable_name . '" value="' . htmlspecialchars($variable_value) . '"' . (isset($config_var[3]) ? ' size="' . $config_var[3] . '"' : '') . ' />';
			// Show a check box.
			elseif ($config_var[2] == 'check')
				$context['raw_data'] .= '<input type="checkbox"' . ($settings_not_writable ? ' disabled="disabled"' : '') . ' name="' . $variable_name . '"' . ($variable_value ? ' checked="checked"' : '') . ' class="check" />';
			// Escape (via htmlspecialchars.) the text box.
			elseif ($config_var[2] == 'password')
				$context['raw_data'] .= '
								<input type="password"' . ($settings_not_writable ? ' disabled="disabled"' : '') . ' name="' . $variable_name . '[0]"' . (isset($config_var[3]) ? ' size="' . $config_var[3] . '"' : '') . ' value="*#fakepass#*" onfocus="this.value = \'\'; document.settingsForm.' . $variable_name . '.disabled = false;" /><br />
								<input type="password" disabled="disabled" id="' . $variable_name . '" name="' . $variable_name . '[1]"' . (isset($config_var[3]) ? ' size="' . $config_var[3] . '"' : '') . ' />
							';
			// Show a selection box.
			elseif ($config_var[2] == 'select')
			{
				$context['raw_data'] .= '<select name="' . $variable_name . '"' . ($settings_not_writable ? ' disabled="disabled"' : '') . '>';
				foreach ($config_var[3] as $option)
					$context['raw_data'] .= '
								<option value="' . $option[0] . '"' . ($option[0] == $variable_value ? ' selected="selected"' : '') . '>' . $option[1] . '</option>';
				$context['raw_data'] .= '
							</select>';
			}

			// Show the [?] button.
			if (isset($config_var[4]))
				$context['raw_data'] .= ' <a href="' . $scripturl . '?action=helpadmin;help=' . $config_var[4] . '" onclick="return reqWin(this.href);" class="help"><img src="' . $settings['images_url'] . '/helptopics.gif" alt="' . $txt[119] . '" border="0" align="top" /></a>';

			$context['raw_data'] .= '</td>';
		}
		else
		{
			// Just show a separator.
			if ($config_var == '')
				$context['raw_data'] .= '
							<td colspan="2" class="windowbg2"><hr size="1" width="100%" class="hrcolor" /></td>';
			else
				$context['raw_data'] .= '
							<td colspan="2" class="windowbg2" align="center"><b>' . $config_var . '</b></td>';
		}
	}

	$context['raw_data'] .= '
						</tr><tr>
							<td class="windowbg2" colspan="2" align="center" valign="middle"><input type="submit" value="' . $txt[10] . '"' . ($settings_not_writable ? ' disabled="disabled"' : '') . ' /></td>
						</tr>
					</table>
				</td></tr>
			</table>
			<input type="hidden" name="sc" value="' . $context['session_id'] . '" />
		</form>';
}

// Put the settings in Settings.php.
function ModifySettings2()
{
	global $boarddir, $sc;

	// Check for permissions.
	isAllowedTo('admin_forum');

	checkSession();

	// Strip the slashes off of the post vars.
	foreach ($_POST as $key => $val)
		$_POST[$key] = stripslashes__recursive($val);

	// Fix the darn stupid cookiename! (more may not be allowed, but these for sure!)
	if (isset($_POST['cookiename']))
		$_POST['cookiename'] = preg_replace('~[,;\s$]+~', '', $_POST['cookiename']);

	// Any passwords?
	$config_passwords = array(
		'db_passwd',
	);

	// All the strings to write.
	$config_strs = array(
		'mtitle', 'mmessage',
		'language', 'mbname', 'boardurl',
		'cookiename',
		'webmaster_email',
		'db_name', 'db_user', 'db_server', 'db_prefix',
		'boarddir', 'sourcedir',
	);
	// All the numeric variables.
	$config_ints = array(
	);
	// All the checkboxes.
	$config_bools = array(
		'db_persist', 'db_error_send',
		'maintenance',
	);

	// Now sort everything into a big array, and figure out arrays and etc.
	$config_vars = array();
	foreach ($config_passwords as $config_var)
	{
		if (isset($_POST[$config_var][1]) && $_POST[$config_var][0] == $_POST[$config_var][1])
			$config_vars[$config_var] = '\'' . addcslashes($_POST[$config_var][0], "'\\") . '\'';
	}
	foreach ($config_strs as $config_var)
	{
		if (isset($_POST[$config_var]))
			$config_vars[$config_var] = '\'' . addcslashes($_POST[$config_var], "'\\") . '\'';
	}
	foreach ($config_ints as $config_var)
	{
		if (isset($_POST[$config_var]))
			$config_vars[$config_var] = (int) $_POST[$config_var];
	}
	foreach ($config_bools as $key)
	{
		if (!empty($_POST[$key]))
			$config_vars[$key] = '1';
		else
			$config_vars[$key] = '0';
	}

	updateSettingsFile($config_vars);

	redirectexit('action=modsettings;sesc=' . $sc);
}

// Set the censored words.
function SetCensor()
{
	global $txt, $modSettings, $context;

	isAllowedTo('moderate_forum');

	// Make the administration bar say "Edit Censored Words".
	adminIndex('edit_censored');

	// Set everything up for the template to do its thang.
	$censor_vulgar = explode("\n", $modSettings['censor_vulgar']);
	$censor_proper = explode("\n", $modSettings['censor_proper']);

	$context['censored_words'] = array();
	for ($i = 0, $n = count($censor_vulgar); $i < $n; $i++)
	{
		if (empty($censor_vulgar[$i]))
			continue;

		// Skip it, it's either spaces or stars only.
		if (trim(strtr($censor_vulgar[$i], '*', ' ')) == '')
			continue;

		$context['censored_words'][htmlspecialchars(trim($censor_vulgar[$i]))] = htmlspecialchars(trim($censor_proper[$i]));
	}

	$context['sub_template'] = 'edit_censored';
	$context['page_title'] = $txt[135];

	if (isset($_SESSION['test_censor']))
	{
		$context['censor_test'] = censorText(htmlspecialchars($_SESSION['test_censor']));
		unset($_SESSION['test_censor']);
	}
	else
		$context['censor_test'] = '';

	$context['censor_whole_word'] = !empty($modSettings['censorWholeWord']);
	$context['censor_ignore_case'] = !empty($modSettings['censorIgnoreCase']);
}

// Commit the censored words to the database.
function SetCensor2()
{
	global $db_prefix;

	// Make sure censoring is something they can do.
	isAllowedTo('moderate_forum');
	checkSession();

	$censored_vulgar = array();
	$censored_proper = array();

	// Rip it apart, then split it into two arrays.
	if (isset($_POST['censortext']))
	{
		$_POST['censortext'] = explode("\n", strtr($_POST['censortext'], array("\r" => '')));

		foreach ($_POST['censortext'] as $c)
			list ($censored_vulgar[], $censored_proper[]) = array_pad(explode('=', trim($c)), 2, '');
	}
	elseif (isset($_POST['censor_vulgar']) && isset($_POST['censor_proper']))
	{
		if (is_array($_POST['censor_vulgar']))
		{
			foreach ($_POST['censor_vulgar'] as $i => $value)
				if ($value == '')
				{
					unset($_POST['censor_vulgar'][$i]);
					unset($_POST['censor_proper'][$i]);
				}

			$censored_vulgar = $_POST['censor_vulgar'];
			$censored_proper = $_POST['censor_proper'];
		}
		else
		{
			$censored_vulgar = explode("\n", strtr($_POST['censor_vulgar'], array("\r" => '')));
			$censored_proper = explode("\n", strtr($_POST['censor_proper'], array("\r" => '')));
		}
	}

	// Set the new arrays and settings in the database.
	$updates = array(
		'censor_vulgar' => implode("\n", $censored_vulgar),
		'censor_proper' => implode("\n", $censored_proper)
	);
	if (isset($_POST['censorWholeWord']))
		$updates['censorWholeWord'] = (int) $_POST['censorWholeWord'];
	if (isset($_POST['censorIgnoreCase']))
		$updates['censorIgnoreCase'] = (int) $_POST['censorIgnoreCase'];

	updateSettings($updates);

	if (isset($_POST['censortest']))
		$_SESSION['test_censor'] = stripslashes($_POST['censortest']);

	redirectexit('action=setcensor');
}

// Optimize the database's tables.
function OptimizeTables()
{
	global $db_name, $txt, $context;

	isAllowedTo('admin_forum');

	// Boldify "Maintain Forum".
	adminIndex('maintain_forum');

	// Start with no tables optimized.
	$opttab = 0;

	$context['page_title'] = $txt['smf281'];
	$context['sub_template'] = 'rawdata';

	$context['raw_data'] =  '
		<br />
		<br />';

	// Get a list of tables, as well as how many there are.
	$get_tables = db_query("
		SHOW TABLE STATUS
		FROM `$db_name`", __FILE__, __LINE__);
	$num_tabs = mysql_num_rows($get_tables);

	// Start the output!
	$context['raw_data'] .= sprintf($txt['smf282'], $num_tabs) . '<br />';

	// Do nothing if there are no tables.
	if ($num_tabs == 0)
		return;

	$context['raw_data'] .= $txt['smf283'] . '<br />';

	// Unoptimized tables.
	$already_optimized = 0;

	// For each table....
	$i = 0;
	while ($table = mysql_fetch_assoc($get_tables))
	{
		$tableName = mysql_tablename($get_tables, $i++);

		// Optimize the table!!!
		db_query("
			OPTIMIZE TABLE $tableName", __FILE__, __LINE__);

		if ($table['Data_free'] == 0)
			$already_optimized++;
		else
		{
			$context['raw_data'] .= sprintf($txt['smf284'], $tableName, $table['Data_free'] / 1024) . '<br />';
			$opttab++;
		}
	}

	// Add to the output array.
	if ($num_tabs == $already_optimized)
		$context['raw_data'] .= '<br />' . $txt['smf285'];
	else
		$context['raw_data'] .= '<br /> ' . $opttab . $txt['smf286'];

	mysql_free_result($get_tables);

	updateSettings(array('autoOptLastOpt' => time()));
}

// Miscellaneous maintenance..
function Maintenance()
{
	global $context, $txt, $db_prefix, $user_info, $db_connection;

	isAllowedTo('admin_forum');

	adminIndex('maintain_forum');

	if (isset($_GET['sa']) && $_GET['sa'] == 'logs')
	{
		// No one's online now.... MUHAHAHAHA :P.
		db_query("
			DELETE FROM {$db_prefix}log_online", __FILE__, __LINE__);

		// Dump the banning logs.
		db_query("
			DELETE FROM {$db_prefix}log_banned", __FILE__, __LINE__);

		// Attempt to start ID_ERROR back at 0.
		mysql_query("
			TRUNCATE {$db_prefix}log_errors", $db_connection);
		// Dump the error log.
		db_query("
			DELETE FROM {$db_prefix}log_errors", __FILE__, __LINE__);

		// Clear out the spam log.
		db_query("
			DELETE FROM {$db_prefix}log_floodcontrol", __FILE__, __LINE__);

		// Clear out the karma actions.
		db_query("
			DELETE FROM {$db_prefix}log_karma", __FILE__, __LINE__);

		// Last but not least, the search log!
		db_query("
			DELETE FROM {$db_prefix}log_search", __FILE__, __LINE__);

		updateSettings(array('search_pointer' => 0));

		$context['maintenance_finished'] = true;
	}
	elseif (isset($_GET['sa']) && $_GET['sa'] == 'destroy')
	{
		echo '<html><head><title>', $context['forum_name'], ' deleted!</title></head>
			<body style="background-color: orange; font-family: arial, sans-serif; text-align: center;">
			<div style="margin-top: 8%; font-size: 400%; color: black;">Oh my, you killed ', $context['forum_name'], '!</div>
			<div style="margin-top: 7%; font-size: 500%; color: red;"><b>You lazy bum!</b></div>
			</body></html>';
		obExit(false);
	}
	else
		$context['maintenance_finished'] = isset($_GET['done']);

	$result = db_query("
		SELECT b.ID_BOARD, b.name, b.childLevel, c.name AS catName, c.ID_CAT
		FROM {$db_prefix}boards AS b, {$db_prefix}categories AS c
		WHERE c.ID_CAT = b.ID_CAT
			AND $user_info[query_see_board]
		ORDER BY c.catOrder, b.boardOrder", __FILE__, __LINE__);
	$context['categories'] = array();
	while ($row = mysql_fetch_assoc($result))
	{
		if (!isset($context['categories'][$row['ID_CAT']]))
			$context['categories'][$row['ID_CAT']] = array(
				'name' => $row['catName'],
				'boards' => array()
			);

		$context['categories'][$row['ID_CAT']]['boards'][] = array(
			'id' => $row['ID_BOARD'],
			'name' => $row['name'],
			'child_level' => $row['childLevel']
		);
	}
	mysql_free_result($result);

	$context['sub_template'] = 'maintain';
	$context['page_title'] = $txt['maintain_title'];
}

// Recount all the important board totals.
function AdminBoardRecount()
{
	global $txt, $db_prefix, $context;

	isAllowedTo('admin_forum');

	// Select it on the left.
	adminIndex('maintain_forum');

	@set_time_limit(600);

	// Get each topic with a wrong reply count and fix it.
	$request = db_query("
		SELECT t.ID_TOPIC, t.numReplies, COUNT(m.ID_MSG) - 1 AS realNumReplies
		FROM {$db_prefix}topics AS t, {$db_prefix}messages AS m
		WHERE m.ID_TOPIC = t.ID_TOPIC
		GROUP BY t.ID_TOPIC
		HAVING realNumReplies != numReplies", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
		db_query("
			UPDATE {$db_prefix}topics
			SET numReplies = $row[realNumReplies]
			WHERE ID_TOPIC = $row[ID_TOPIC]
			LIMIT 1", __FILE__, __LINE__);
	mysql_free_result($request);

	// Update the post and topic count of each board.
	$request = db_query("
		SELECT b.ID_BOARD, b.numPosts, COUNT(m.ID_MSG) AS realNumPosts, b.numTopics, COUNT(DISTINCT t.ID_TOPIC) AS realNumTopics
		FROM {$db_prefix}boards AS b, {$db_prefix}topics AS t, {$db_prefix}messages AS m
		WHERE t.ID_TOPIC = m.ID_TOPIC
			AND b.ID_BOARD = t.ID_BOARD
		GROUP BY b.ID_BOARD
		HAVING realNumPosts != numPosts OR realNumTopics != numTopics", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
		db_query("
			UPDATE {$db_prefix}boards
			SET numPosts = $row[realNumPosts], numTopics = $row[realNumTopics]
			WHERE ID_BOARD = $row[ID_BOARD]
			LIMIT 1", __FILE__, __LINE__);
	mysql_free_result($request);

	// Get all members with wrong number of personal messages.
	$request = db_query("
		SELECT mem.ID_MEMBER, COUNT(pmr.ID_PM) AS realNum, mem.instantMessages
		FROM {$db_prefix}members AS mem
			LEFT JOIN {$db_prefix}im_recipients AS pmr ON (mem.ID_MEMBER = pmr.ID_MEMBER AND pmr.deleted = 0)
		GROUP BY mem.ID_MEMBER
		HAVING realNum != instantMessages", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
		updateMemberData($row['ID_MEMBER'], array('instantMessages' => $row['realNum']));
	mysql_free_result($request);

	// Any messages pointing to the wrong board?
	$request = db_query("
		SELECT t.ID_BOARD, m.ID_MSG
		FROM {$db_prefix}messages AS m, {$db_prefix}topics AS t
		WHERE t.ID_TOPIC = m.ID_TOPIC
			AND m.ID_BOARD != t.ID_BOARD", __FILE__, __LINE__);
	$boards = array();
	while ($row = mysql_fetch_assoc($request))
		$boards[$row['ID_BOARD']][] = $row['ID_MSG'];
	mysql_free_result($request);

	foreach ($boards as $board_id => $messages)
		db_query("
			UPDATE {$db_prefix}messages
			SET ID_BOARD = $board_id
			WHERE ID_MSG IN (" . implode(', ', $messages) . ")
			LIMIT " . count($messages), __FILE__, __LINE__);

	// Update the latest message of each board.
	$request = db_query("
		SELECT b.ID_BOARD, b.ID_PARENT, b.ID_LAST_MSG, MAX(m.ID_MSG) AS localLastMsg, b.childLevel
		FROM {$db_prefix}boards AS b, {$db_prefix}messages AS m
		WHERE b.ID_BOARD = m.ID_BOARD
		GROUP BY ID_BOARD", __FILE__, __LINE__);
	$resort_me = array();
	while ($row = mysql_fetch_assoc($request))
		$resort_me[$row['childLevel']] = $row;
	mysql_free_result($request);

	krsort($resort_me);

	$lastMsg = array();
	foreach ($resort_me as $row)
	{
		// The latest message is the latest of the current board and its children.
		if (isset($lastMsg[$row['ID_BOARD']]))
			$curLastMsg = max($row['localLastMsg'], $lastMsg[$row['ID_BOARD']]);
		else
			$curLastMsg = $row['localLastMsg'];

		// If what is and what should be the latest message differ, an update is necessary.
		if ($curLastMsg != $row['ID_LAST_MSG'])
			db_query("
				UPDATE {$db_prefix}boards
				SET ID_LAST_MSG = $curLastMsg
				WHERE ID_BOARD = $row[ID_BOARD]
				LIMIT 1", __FILE__, __LINE__);

		// Parent boards inherit the latest message of their children.
		if (isset($lastMsg[$row['ID_PARENT']]))
			$lastMsg[$row['ID_PARENT']] = max($row['localLastMsg'], $lastMsg[$row['ID_PARENT']]);
		else
			$lastMsg[$row['ID_PARENT']] = $row['localLastMsg'];
	}

	// Update all the basic statistics.
	updateStats('member');
	updateStats('message');
	updateStats('topic');

	redirectexit('action=maintain;done');
}

// Perform a detailed version check.  A very good thing ;).
function VersionDetail()
{
	global $forum_version;
	global $txt, $boarddir, $sourcedir, $context, $settings;

	isAllowedTo('admin_forum');

	// Set up the sidebar for version checking.
	adminIndex('view_versions');

	$context['file_versions'] = array();
	$context['default_template_versions'] = array();
	$context['template_versions'] = array();
	$context['default_language_versions'] = array();

	// Find the version in SSI.php's file header.
	$fp = fopen($boarddir . '/SSI.php', 'rb');
	$header = fread($fp, 4096);
	fclose($fp);

	// The comment looks rougly like... that.
	if (preg_match('~\*\s*Software\s+Version:\s+SMF\s+(.+?)[\s]{2}~i', $header, $match) == 1)
		$context['file_versions']['SSI.php'] = $match[1];
	// Not found!  This is bad.
	else
		$context['file_versions']['SSI.php'] = '??';

	// Load all the files in the Sources directory, except this file and the redirect.
	$Sources_dir = dir($sourcedir);
	while ($entry = $Sources_dir->read())
		if (substr($entry, -4) == '.php' && !is_dir($sourcedir . '/' . $entry) && $entry != 'index.php')
		{
			// Read the first 4k from the file.... enough for the header.
			$fp = fopen($sourcedir . '/' . $entry, 'rb');
			$header = fread($fp, 4096);
			fclose($fp);

			// Look for the version comment in the file header.
			if (preg_match('~\*\s*Software\s+Version:\s+SMF\s+(.+?)[\s]{2}~i', $header, $match) == 1)
				$context['file_versions'][$entry] = $match[1];
			// It wasn't found, but the file was... show a '??'.
			else
				$context['file_versions'][$entry] = '??';
		}
	$Sources_dir->close();

	// Load all the files in the default template directory - and the current theme if applicable.
	$directories = array('default_template_versions' => $settings['default_theme_dir']);
	if ($settings['theme_id'] != 1)
		$directories += array('template_versions' => $settings['theme_dir']);

	foreach ($directories as $type => $dirname)
	{
		$This_dir = dir($dirname);
		while ($entry = $This_dir->read())
			if (substr($entry, -12) == 'template.php' && !is_dir($dirname . '/' . $entry))
			{
				// Read the first 768 bytes from the file.... enough for the header.
				$fp = fopen($dirname . '/' . $entry, 'rb');
				$header = fread($fp, 768);
				fclose($fp);

				// Look for the version comment in the file header.
				if (preg_match('~(?://|/\*)\s*Version:\s+(.+?);\s*' . basename($entry, '.template.php') . '(?:[\s]{2}|\*/)~i', $header, $match) == 1)
					$context[$type][$entry] = $match[1];
				// It wasn't found, but the file was... show a '??'.
				else
					$context[$type][$entry] = '??';
			}
		$This_dir->close();
	}

	// Load up all the files in the default language directory and sort by language.
	$lang_dir = $settings['default_theme_dir'] . '/languages';
	$This_dir = dir($lang_dir);
	while ($entry = $This_dir->read())
		if (substr($entry, -4) == '.php' && $entry != 'index.php' && !is_dir($lang_dir . '/' . $entry))
		{
			// Read the first 768 bytes from the file.... enough for the header.
			$fp = fopen($lang_dir . '/' . $entry, 'rb');
			$header = fread($fp, 768);
			fclose($fp);

			// Split the file name off into useful bits.
			list ($name, $language) = explode('.', $entry);

			// Look for the version comment in the file header.
			if (preg_match('~(?://|/\*)\s*Version:\s+(.+?);\s*' . $name . '(?:[\s]{2}|\*/)~i', $header, $match) == 1)
				$context['default_language_versions'][$language][$name] = $match[1];
			// It wasn't found, but the file was... show a '??'.
			else
				$context['default_language_versions'][$language][$name] = '??';
		}
	$This_dir->close();

	// Sort the file versions by filename.
	ksort($context['file_versions']);
	ksort($context['default_template_versions']);
	ksort($context['template_versions']);
	ksort($context['default_language_versions']);

	// For languages sort each language too.
	foreach ($context['default_language_versions'] as $key => $dummy)
		ksort($context['default_language_versions'][$key]);

	$context['default_known_languages'] = array_keys($context['default_language_versions']);

	// Make it easier to manage for the template.
	$context['forum_version'] = $forum_version;

	$context['sub_template'] = 'view_versions';
	$context['page_title'] = $txt[429];
}

// Update the Settings.php file.
function updateSettingsFile($config_vars)
{
	global $boarddir;

	// Load the file.
	$settingsArray = file($boarddir . '/Settings.php');

	if (count($settingsArray) == 1)
		$settingsArray = preg_split('~[\r\n]~', $settingsArray[0]);

	for ($i = 0, $n = count($settingsArray); $i < $n; $i++)
	{
		$settingsArray[$i] = trim($settingsArray[$i]);

		// Look through the variables to set....
		foreach ($config_vars as $var => $val)
		{
			if (strncasecmp($settingsArray[$i], '$' . $var, 1 + strlen($var)) == 0)
			{
				$comment = strstr(substr($settingsArray[$i], strpos($settingsArray[$i], ';')), '#');
				$settingsArray[$i] = '$' . $var . ' = ' . $val . ';' . ($comment == '' ? '' : "\t\t" . $comment);

				// This one's been 'used', so to speak.
				unset($config_vars[$var]);
			}
		}

		if (trim(substr($settingsArray[$i], 0, 2)) == '?' . '>')
			$end = $i;
	}

	// This should never happen, but apparently it is happening.
	if (empty($end) || $end < 10)
		$end = count($settingsArray) - 1;

	// Still more?  Add them at the end.
	if (!empty($config_vars))
	{
		$settingsArray[$end++] = '';
		foreach ($config_vars as $var => $val)
			$settingsArray[$end++] = '$' . $var . ' = ' . $val . ';';
		$settingsArray[$end] = '?' . '>';
	}

	// Sanity error checking: the file needs to be at least 10 lines.
	if (count($settingsArray) < 10)
		return;

	// Blank out the file - done to fix a oddity with some servers.
	$fp = fopen($boarddir . '/Settings.php', 'w');
	fclose($fp);

	// Now actually write.
	$fp = fopen($boarddir . '/Settings.php', 'r+');
	$lines = count($settingsArray);
	for ($i = 0; $i < $lines - 1; $i++)
		fwrite($fp, $settingsArray[$i] . "\n");

	// The last line should have no \n.
	fwrite($fp, $settingsArray[$i]);
	fclose($fp);
}

?>