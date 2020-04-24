<?php
/******************************************************************************
* Themes.php                                                                  *
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

/*	This file concerns itself almost completely with theme administration.
	Its tasks include changing theme settings, installing and removing
	themes, choosing the current theme, and editing themes.  This is done in:

	void ThemesMain()
		- manages the action and delegates control to the proper sub action.
		- loads both the Themes and Settings language files.
		- checks the session by GET or POST to verify the sent data.
		- requires the user not be a guest.
		- is accessed via ?action=theme.

	void SetThemeSettings()
		- saves and requests global theme settings. ($settings)
		- loads the Admin language file.
		- calls ThemeAdmin() if no theme is specified. (the theme center.)
		- requires an administrator.
		- accessed with ?action=theme;sa=settings;id=xx.

	void SetThemeOptions()
		// !!!

	void ThemeAdmin()
		- administrates themes and their settings, as well as global theme
		  settings.
		- sets the settings theme_allow, theme_guests, and theme_default.
		- loads the template Themes.
		- requires the admin_forum permission.
		- accessed with ?action=theme;sa=admin.

	void RemoveTheme()
		- removes an installed theme.
		- requires an administrator.
		- accessed with ?action=theme;sa=remove.

	void PickTheme()
		- allows user or administrator to pick a new theme with an interface.
		- can edit everyone's (u = 0), guests' (u = -1), or a specific user's.
		- uses the Themes template. (pick sub template.)
		- accessed with ?action=theme;sa=pick.

	void ThemeInstall()
		- installs new themes, either from a gzip or copy of the default.
		- requires an administrator.
		- puts themes in $boardurl/Themes.
		- assumes the gzip has a root directory in it. (ie default.)
		- accessed with ?action=theme;sa=install.

	void WrapAction()
		- allows the theme to take care of actions.
		- happens if $settings['catch_action'] is set and action isn't found
		  in the action array.
		- can use a template, layers, sub_template, filename, and/or function.

	void SetJavaScript()
		- sets a theme option without outputting anything.
		- can be used with javascript, via a dummy image... (which doesn't
		  require the page to reload.)
		- requires someone who is logged in.
		- accessed via ?action=jsoption;var=variable;val=value;sesc=sess_id.
		- does not log access to the Who's Online log. (in index.php..)

	void EditTheme()
		- shows an interface for editing the templates.
		- uses the Themes template and edit_template/edit_style sub template.
		- accessed via ?action=theme;sa=edit

	function convert_template($output_dir, $old_template = '')
		// !!!

	function phpcodefix(string string)
		// !!!

	function makeStyleChanges(&$old_template)
		// !!!
*/

// Subaction handler.
function ThemesMain()
{
	global $txt, $context;

	// Load the important language files...
	loadLanguage('Themes');
	loadLanguage('Settings');

	// No funny business - guests only.
	is_not_guest();

	// Default the page title to Theme Administration by default.
	$context['page_title'] = &$txt['themeadmin_title'];

	// Theme administration, removal, choice, or installation...
	$subActions = array(
		'admin' => 'ThemeAdmin',
		'settings' => 'SetThemeSettings',
		'options' => 'SetThemeOptions',
		'remove' => 'RemoveTheme',
		'pick' => 'PickTheme',
		'install' => 'ThemeInstall',
		'edit' => 'EditTheme'
	);

	// Follow the sa or just go to administration.
	if (!empty($subActions[$_GET['sa']]))
		$subActions[$_GET['sa']]();
	else
		$subActions['admin']();
}

// Administrative global settings.
function SetThemeSettings()
{
	global $txt, $sc, $context, $settings, $db_prefix, $modSettings;

	if (empty($_GET['id']))
		return ThemeAdmin();
	$_GET['id'] = (int) $_GET['id'];

	loadLanguage('Admin');
	isAllowedTo('admin_forum');

	// Just for navigation, show some nice bar on the left.
	adminIndex($settings['theme_id'] == $_GET['id'] ? 'edit_theme_settings' : 'manage_themes');

	// Validate inputs/user.
	if (empty($_GET['id']))
		fatal_lang_error('theme3', false);

	// Submitting!
	if (isset($_POST['submit']))
	{
		checkSession();

		if (empty($_POST['options']))
			$_POST['options'] = array();
		if (empty($_POST['default_options']))
			$_POST['default_options'] = array();

		// Set up the sql query.
		$setString = '';
		foreach ($_POST['options'] as $opt => $val)
			$setString .= "
				(0, $_GET[id], '$opt', '" . (is_array($val) ? implode(',', $val) : $val) . "'),";
		foreach ($_POST['default_options'] as $opt => $val)
			$setString .= "
				(0, 1, '$opt', '" . (is_array($val) ? implode(',', $val) : $val) . "'),";
		// If we're actually inserting something..
		if ($setString != '')
		{
			// Get rid of the last comma.
			$setString = substr($setString, 0, strlen($setString) - 1);

			db_query("
				REPLACE INTO {$db_prefix}themes
					(ID_MEMBER, ID_THEME, variable, value)
				VALUES $setString", __FILE__, __LINE__);
		}

		redirectexit('action=theme;sa=settings;id=' . $_GET['id'] . ';sesc=' . $sc);
	}

	checkSession('get');

	// Fetch the smiley sets...
	$sets = explode(',', 'none,' . $modSettings['smiley_sets_known']);
	$set_names = explode("\n", $txt['smileys_none'] . "\n" . $modSettings['smiley_sets_names']);
	$context['smiley_sets'] = array(
		'' => $txt['smileys_no_default']
	);
	foreach ($sets as $i => $set)
		$context['smiley_sets'][$set] = $set_names[$i];

	$old_id = $settings['theme_id'];
	loadTheme($_GET['id'], false);

	// Let the theme take care of the settings.
	loadTemplate('Settings');
	loadSubTemplate('settings');

	$context['sub_template'] = 'set_settings';
	$context['page_title'] = $txt['theme4'];

	foreach ($settings as $setting => $dummy)
	{
		if (!in_array($setting, array('theme_url', 'theme_dir', 'images_url')))
			$settings[$setting] = htmlspecialchars($settings[$setting]);
	}

	$context['settings'] = $context['theme_settings'];
	$context['theme_settings'] = $settings;

	foreach ($context['settings'] as $i => $setting)
	{
		if (!isset($setting['type']) || $setting['type'] == 'bool')
			$context['settings'][$i]['type'] = 'checkbox';
		elseif ($setting['type'] == 'int' || $setting['type'] == 'integer')
			$context['settings'][$i]['type'] = 'number';
		elseif ($setting['type'] == 'string')
			$context['settings'][$i]['type'] = 'text';

		if (isset($setting['options']))
			$context['settings'][$i]['type'] = 'list';

		$context['settings'][$i]['value'] = !isset($settings[$setting['id']]) ? '' : $settings[$setting['id']];
	}

	loadTheme($old_id, false);

	loadTemplate('Themes');
}

// Administrative global settings.
function SetThemeOptions()
{
	global $txt, $sc, $context, $settings, $db_prefix, $modSettings;

	if (empty($_GET['id']))
		return ThemeAdmin();
	$_GET['id'] = (int) $_GET['id'];
	if (empty($_GET['id']))
		fatal_lang_error('theme3', false);

	loadLanguage('Profile');
	isAllowedTo('admin_forum');

	adminIndex('manage_themes');

	// Submit?
	if (isset($_POST['submit']) && empty($_POST['who']))
	{
		checkSession();

		if (empty($_POST['options']))
			$_POST['options'] = array();
		if (empty($_POST['default_options']))
			$_POST['default_options'] = array();

		// Set up the sql query.
		$setString = '';

		foreach ($_POST['options'] as $opt => $val)
			$setString .= "
				(-1, $_GET[id], '$opt', '" . (is_array($val) ? implode(',', $val) : $val) . "'),";

		$old_settings = array();
		foreach ($_POST['default_options'] as $opt => $val)
		{
			$old_settings[] = $opt;

			$setString .= "
				(-1, 1, '$opt', '" . (is_array($val) ? implode(',', $val) : $val) . "'),";
		}

		// If we're actually inserting something..
		if ($setString != '')
		{
			// Get rid of the last comma.
			$setString = substr($setString, 0, strlen($setString) - 1);

			// Are there options in non-default themes set that should be cleared?
			if (!empty($old_settings))
				db_query("
					DELETE FROM {$db_prefix}themes
					WHERE ID_THEME != 1
						AND ID_MEMBER = -1
						AND variable IN ('" . implode("', '", $old_settings) . "')", __FILE__, __LINE__);

			db_query("
				REPLACE INTO {$db_prefix}themes
					(ID_MEMBER, ID_THEME, variable, value)
				VALUES $setString", __FILE__, __LINE__);
		}

		redirectexit('action=theme;sa=options;id=' . $_GET['id'] . ';sesc=' . $sc);
	}
	elseif (isset($_POST['submit']) && !empty($_POST['who']))
	{
		checkSession();

		if (empty($_POST['options']))
			$_POST['options'] = array();
		if (empty($_POST['default_options']))
			$_POST['default_options'] = array();

		$old_settings = array();
		foreach ($_POST['default_options'] as $opt => $val)
		{
			db_query("
				REPLACE INTO {$db_prefix}themes
					(ID_MEMBER, ID_THEME, variable, value)
				SELECT ID_MEMBER, 1, '$opt', '" . (is_array($val) ? implode(',', $val) : $val) . "'
				FROM {$db_prefix}members", __FILE__, __LINE__);

			db_query("
				REPLACE INTO {$db_prefix}themes
					(ID_MEMBER, ID_THEME, variable, value)
				VALUES (-1, $_GET[id], '$opt', '" . (is_array($val) ? implode(',', $val) : $val) . "')", __FILE__, __LINE__);

			$old_settings[] = $opt;
		}

		// Delete options from other themes.
		if (!empty($old_settings))
			db_query("
				DELETE FROM {$db_prefix}themes
				WHERE ID_THEME != 1
					AND ID_MEMBER != 0
					AND variable IN ('" . implode("', '", $old_settings) . "')", __FILE__, __LINE__);

		foreach ($_POST['options'] as $opt => $val)
		{
			db_query("
				REPLACE INTO {$db_prefix}themes
					(ID_MEMBER, ID_THEME, variable, value)
				SELECT ID_MEMBER, $_GET[id], '$opt', '" . (is_array($val) ? implode(',', $val) : $val) . "'
				FROM {$db_prefix}members", __FILE__, __LINE__);

			db_query("
				REPLACE INTO {$db_prefix}themes
					(ID_MEMBER, ID_THEME, variable, value)
				VALUES (-1, $_GET[id], '$opt', '" . (is_array($val) ? implode(',', $val) : $val) . "')", __FILE__, __LINE__);
		}

		redirectexit('action=theme;sa=options;id=' . $_GET['id'] . ';sesc=' . $sc . ';who=1');
	}

	checkSession('get');

	$old_id = $settings['theme_id'];
	loadTheme($_GET['id'], false);

	// Let the theme take care of the settings.
	loadTemplate('Settings');
	loadSubTemplate('options');

	$context['sub_template'] = 'set_options';
	$context['page_title'] = $txt['theme4'];

	$context['options'] = $context['theme_options'];
	$context['theme_settings'] = $settings;

	if (empty($_REQUEST['who']))
	{
		$request = db_query("
			SELECT variable, value
			FROM {$db_prefix}themes
			WHERE ID_THEME IN (1, " . $_GET['id'] . ")
				AND ID_MEMBER = -1", __FILE__, __LINE__);
		$context['theme_options'] = array();
		while ($row = mysql_fetch_assoc($request))
			$context['theme_options'][$row['variable']] = $row['value'];
		mysql_free_result($request);

		$context['theme_options_reset'] = false;
	}
	else
	{
		$context['theme_options'] = array();
		$context['theme_options_reset'] = true;
	}

	foreach ($context['options'] as $i => $setting)
	{
		if (!isset($setting['type']) || $setting['type'] == 'bool')
			$context['options'][$i]['type'] = 'checkbox';
		elseif ($setting['type'] == 'int' || $setting['type'] == 'integer')
			$context['options'][$i]['type'] = 'number';
		elseif ($setting['type'] == 'string')
			$context['options'][$i]['type'] = 'text';

		if (isset($setting['options']))
			$context['options'][$i]['type'] = 'list';

		$context['options'][$i]['value'] = !isset($context['theme_options'][$setting['id']]) ? '' : $context['theme_options'][$setting['id']];
	}

	loadTheme($old_id, false);

	loadTemplate('Themes');
}

function ThemeAdmin()
{
	global $txt, $context, $db_prefix, $sc, $boarddir;

	loadLanguage('Admin');
	isAllowedTo('admin_forum');

	adminIndex('manage_themes');

	// If we aren't submitting - that is, if we are about to...
	if (!isset($_POST['submit']))
	{
		checkSession('get');

		loadTemplate('Themes');

		// Load up all the themes.
		$context['themes'] = array();
		$request = db_query("
			SELECT value AS name, ID_THEME
			FROM {$db_prefix}themes
			WHERE variable = 'name'
				AND ID_MEMBER = 0
			ORDER BY ID_THEME", __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
		{
			$context['themes'][] = array(
				'id' => $row['ID_THEME'],
				'name' => $row['name']
			);
		}
		mysql_free_result($request);

		// Can we create a new theme?
		$context['can_create_new'] = is_writable($boarddir . '/Themes');
		$context['new_theme_dir'] = $boarddir . '/Themes/';

		// Look for a non existent theme directory. (ie theme87.)
		$theme_dir = $boarddir . '/Themes/theme';
		$i = 1;
		while (file_exists($theme_dir . $i))
			$i++;
		$context['new_theme_name'] = 'theme' . $i;
	}
	else
	{
		checkSession();

		// Commit the new settings.
		updateSettings(array(
			'theme_allow' => $_POST['options']['theme_allow'],
			'theme_default' => $_POST['options']['theme_default'],
			'theme_guests' => $_POST['options']['theme_guests']
		));

		if ((int) $_POST['theme_reset'] != -1)
			updateMemberData(null, array('ID_THEME' => (int) $_POST['theme_reset']));

		redirectexit('action=theme;sa=admin;sesc=' . $sc);
	}
}

// Remove a theme from the database.
function RemoveTheme()
{
	global $db_prefix, $modSettings, $sc;

	checkSession('get');

	isAllowedTo('admin_forum');

	// You can't delete the default theme!
	if ($_GET['th'] == 1)
		fatal_lang_error(1, false);

	// The theme's ID must be an integer.
	$_GET['th'] = (int) $_GET['th'];

	$known = explode(',', $modSettings['knownThemes']);
	for ($i = 0, $n = count($known); $i < $n; $i++)
	{
		if ($known[$i] == $_GET['th'])
			unset($known[$i]);
	}

	db_query("
		DELETE FROM {$db_prefix}themes
		WHERE ID_THEME = $_GET[th]", __FILE__, __LINE__);

	db_query("
		UPDATE {$db_prefix}members
		SET ID_THEME = 0
		WHERE ID_THEME = $_GET[th]", __FILE__, __LINE__);

	$known = strtr(implode(',', $known), array(',,' => ','));

	// Fix it if the theme was the overall default theme.
	if ($modSettings['theme_guests'] == $_GET['th'])
		updateSettings(array('theme_guests' => '1', 'knownThemes' => $known));
	else
		updateSettings(array('knownThemes' => $known));

	redirectexit('action=theme;sa=admin;sesc=' . $sc);
}

// Choose a theme from a list.
function PickTheme()
{
	global $txt, $db_prefix, $sc, $context, $modSettings, $user_info, $ID_MEMBER, $language;

	checkSession('get');

	loadTemplate('Themes');
	loadLanguage('Profile');

	$_SESSION['ID_THEME'] = 0;

	// Have we made a desicion, or are we just browsing?
	if (isset($_GET['id']))
	{
		// Save for this user.
		if (!isset($_REQUEST['u']) || !allowedTo('admin_forum'))
		{
			updateMemberData($ID_MEMBER, array('ID_THEME' => (int) $_GET['id']));

			redirectexit('action=profile;sa=theme');
		}
		// For everyone.
		elseif ($_REQUEST['u'] == '0')
		{
			updateMemberData(null, array('ID_THEME' => (int) $_GET['id']));

			redirectexit('action=theme;sa=admin;sesc=' . $sc);
		}
		// Change the default/guest theme.
		elseif ($_REQUEST['u'] == '-1')
		{
			updateSettings(array('theme_guests' => (int) $_GET['id']));

			redirectexit('action=theme;sa=admin;sesc=' . $sc);
		}
		// Change a specific member's theme.
		else
		{
			updateMemberData((int) $_REQUEST['u'], array('ID_THEME' => (int) $_GET['id']));

			redirectexit('action=profile;u=' . (int) $_REQUEST['u'] . ';sa=theme');
		}
	}

	// Figure out who the member of the minute is, and what theme they've chosen.
	if (!isset($_REQUEST['u']) || !allowedTo('admin_forum'))
	{
		$context['current_member'] = $ID_MEMBER;
		$context['current_theme'] = $user_info['theme'];
	}
	// Everyone can't chose just one.
	elseif ($_REQUEST['u'] == '0')
	{
		$context['current_member'] = 0;
		$context['current_theme'] = 0;
	}
	// Guests and such...
	elseif ($_REQUEST['u'] == '-1')
	{
		$context['current_member'] = -1;
		$context['current_theme'] = $modSettings['theme_guests'];
	}
	// Someones else :P.
	else
	{
		$context['current_member'] = (int) $_REQUEST['u'];

		$request = db_query("
			SELECT ID_THEME
			FROM {$db_prefix}members
			WHERE ID_MEMBER = $context[current_member]
			LIMIT 1", __FILE__, __LINE__);
		list ($context['current_theme']) = mysql_fetch_row($request);
		mysql_free_result($request);
	}
	// Get the theme name and descriptions.
	$context['available_themes'] = array();
	if (!empty($modSettings['knownThemes']))
	{
		$knownThemes = implode("', '", explode(',', $modSettings['knownThemes']));

		$request = db_query("
			SELECT ID_THEME, variable, value
			FROM {$db_prefix}themes
			WHERE variable IN ('name', 'theme_url', 'theme_dir', 'images_url')" . (empty($modSettings['theme_default']) && !allowedTo('admin_forum') ? "
				AND ID_THEME IN ('$knownThemes')
				AND ID_THEME != 1" : '') . "
				AND ID_THEME != 0
			LIMIT " . count(explode(',', $modSettings['knownThemes'])) * 4, __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
		{
			if (!isset($context['available_themes'][$row['ID_THEME']]))
				$context['available_themes'][$row['ID_THEME']] = array(
					'id' => $row['ID_THEME'],
					'selected' => $context['current_theme'] == $row['ID_THEME'],
					'num_users' => 0
				);
			$context['available_themes'][$row['ID_THEME']][$row['variable']] = $row['value'];
		}
		mysql_free_result($request);
	}

	// Okay, this is a complicated problem: the default theme is 1, but they aren't allowed to access 1!
	if (!isset($context['available_themes'][$modSettings['theme_guests']]))
	{
		$context['available_themes'][0] = array(
			'num_users' => 0
		);
		$guest_theme = 0;
	}
	else
		$guest_theme = $modSettings['theme_guests'];

	$request = db_query("
		SELECT COUNT(ID_MEMBER) AS theCount, ID_THEME
		FROM {$db_prefix}members
		GROUP BY ID_THEME DESC", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($request))
	{
		// Figure out which theme it is they are REALLY using.
		if ($row['ID_THEME'] == 1 && empty($modSettings['theme_default']))
			$row['ID_THEME'] = $guest_theme;
		elseif (empty($modSettings['theme_allow']))
			$row['ID_THEME'] = $guest_theme;

		if (isset($context['available_themes'][$row['ID_THEME']]))
			$context['available_themes'][$row['ID_THEME']]['num_users'] += $row['theCount'];
		else
			$context['available_themes'][$guest_theme]['num_users'] += $row['theCount'];
	}

	foreach ($context['available_themes'] as $ID_THEME => $theme_data)
	{
		// Don't try to load the forum or board default theme's data... it doesn't have any!
		if ($ID_THEME == 0)
			continue;

		$settings = $theme_data;
		$settings['theme_id'] = $ID_THEME;

		if (file_exists($settings['theme_dir'] . '/languages/Settings.' . $user_info['language'] . '.php'))
			include($settings['theme_dir'] . '/languages/Settings.' . $user_info['language'] . '.php');
		elseif (file_exists($settings['theme_dir'] . '/languages/Settings.' . $language . '.php'))
			include($settings['theme_dir'] . '/languages/Settings.' . $language . '.php');
		else
		{
			$txt['theme_thumbnail_href'] = $settings['images_url'] . '/thumbnail.gif';
			$txt['theme_description'] = '';
		}

		$context['available_themes'][$ID_THEME]['thumbnail_href'] = $txt['theme_thumbnail_href'];
		$context['available_themes'][$ID_THEME]['description'] = $txt['theme_description'];
	}

	// As long as we're not doing the default theme...
	if (!isset($_REQUEST['u']) || $_REQUEST['u'] >= 0)
	{
		if ($guest_theme != 0)
			$context['available_themes'][0] = $context['available_themes'][$guest_theme];
		$context['available_themes'][0]['id'] = 0;
		$context['available_themes'][0]['name'] = $txt['theme_forum_default'];
		$context['available_themes'][0]['selected'] = $context['current_theme'] == 0;
		$context['available_themes'][0]['description'] = $txt['theme_global_description'];
	}

	ksort($context['available_themes']);

	$context['page_title'] = &$txt['theme_pick'];
	$context['sub_template'] = 'pick';
}

function ThemeInstall()
{
	global $sourcedir, $boarddir, $boardurl, $db_prefix, $txt, $context, $settings, $modSettings;

	checkSession('request');

	isAllowedTo('admin_forum');
	checkSession('request');

	loadTemplate('Themes');

	if (isset($_GET['theme_id']))
	{
		adminIndex('manage_themes');

		$context['sub_template'] = 'installed';
		$context['page_title'] = $txt['theme_installed'];
		$context['installed_theme'] = array(
			'id' => (int) $_GET['theme_id'],
			'name' => $_GET['theme_name']
		);

		return;
	}

	if (!empty($_REQUEST['copy']) && (!isset($_REQUEST['theme_dir']) || stripslashes($_REQUEST['theme_dir']) == $boarddir . '/Themes/' || !file_exists($_REQUEST['theme_dir'])) && (empty($_FILES['theme_gz']) || $_FILES['theme_gz']['error'] == 4) && empty($_REQUEST['theme_gz']))
	{
		// Hopefully the themes directory is writable, or we might have a problem.
		if (!is_writable($boarddir . '/Themes'))
			fatal_lang_error('theme_install_write_error');

		$theme_dir = $boarddir . '/Themes/' . preg_replace('~[^A-Za-z0-9_\- ]~', '', $_REQUEST['copy']);

		umask(0);
		mkdir($theme_dir, 0777);

		// Copy over the default non-theme files.
		$to_copy = array('/style.css', '/index.php', '/index.template.php');
		foreach ($to_copy as $file)
		{
			copy($settings['default_theme_dir'] . $file, $theme_dir . $file);
			@chmod($theme_dir . $file, 0777);
		}

		$theme_name = $_REQUEST['copy'];
		$images_url = $settings['default_images_url'];
	}
	elseif (isset($_REQUEST['theme_dir']) && (empty($_FILES['theme_gz']) || $_FILES['theme_gz']['error'] == 4) && empty($_REQUEST['theme_gz']))
	{
		if (!is_dir($_REQUEST['theme_dir']) || !file_exists($_REQUEST['theme_dir'] . '/theme_info.xml'))
			fatal_lang_error('theme_install_error', false);

		$theme_name = basename($_REQUEST['theme_dir']);
		$theme_dir = $_REQUEST['theme_dir'];
	}
	else
	{
		// Hopefully the themes directory is writable, or we might have a problem.
		if (!is_writable($boarddir . '/Themes'))
			fatal_lang_error('theme_install_write_error');

		require_once($sourcedir . '/Subs-Package.php');

		// Set the default settings...
		$theme_name = strtok(basename(isset($_FILES['theme_gz']) ? $_FILES['theme_gz']['name'] : $_REQUEST['theme_gz']), '.');
		$theme_dir = $boarddir . '/Themes/' . $theme_name;

		if (isset($_FILES['theme_gz']) && is_uploaded_file($_FILES['theme_gz']['tmp_name']))
			$extracted = read_tgz_file($_FILES['theme_gz']['tmp_name'], $boarddir . '/Themes/' . $theme_name);
		elseif (isset($_REQUEST['theme_gz']))
		{
			// Check that the theme is from simplemachines.org, for now... maybe add mirroring later.
			if (preg_match('~^http://[\w_\-]+\.simplemachines\.org/~', $_REQUEST['theme_gz']) == 0)
				fatal_lang_error('not_on_simplemachines');

			$extracted = read_tgz_file($_REQUEST['theme_gz'], $boarddir . '/Themes/' . $theme_name);
		}
		else
			redirectexit('action=theme;sa=admin;sesc=' . $context['session_id']);
	}

	// Something go wrong?
	if ($theme_dir != '' && basename($theme_dir) != 'Themes')
	{
		// Defaults.
		$install_info = array(
			'theme_url' => $boardurl . '/Themes/' . basename($theme_dir),
			'images_url' => isset($images_url) ? $images_url : $boardurl . '/Themes/' . basename($theme_dir) . '/images',
			'theme_dir' => $theme_dir,
			'name' => $theme_name
		);

		if (file_exists($theme_dir . '/theme_info.xml'))
		{
			$theme_info = implode('', file($theme_dir . '/theme_info.xml'));

			$xml_elements = array(
				'name' => 'name',
				'theme_layers' => 'layers',
				'theme_templates' => 'templates',
				'based_on' => 'based-on',
			);
			foreach ($xml_elements as $var => $name)
			{
				if (preg_match('~<' . $name . '>(?:<!\[CDATA\[)?(.+?)(?:\]\]>)?</' . $name . '>~', $theme_info, $match) == 1)
					$install_info[$var] = $match[1];
			}

			if (preg_match('~<images>(?:<!\[CDATA\[)?(.+?)(?:\]\]>)?</images>~', $theme_info, $match) == 1)
				$install_info['images_url'] = $install_info['theme_url'] . '/' . $match[1];
			if (preg_match('~<extra>(?:<!\[CDATA\[)?(.+?)(?:\]\]>)?</extra>~', $theme_info, $match) == 1)
				$install_info += unserialize($match[1]);
		}

		if (isset($install_info['based_on']))
		{
			if ($install_info['based_on'] == 'default')
			{
				$install_info['theme_url'] = $settings['default_theme_url'];
				$install_info['images_url'] = $settings['default_images_url'];
			}
			unset($install_info['based_on']);
		}

		// Find the newest ID_THEME.
		$result = db_query("
			SELECT MAX(ID_THEME)
			FROM {$db_prefix}themes", __FILE__, __LINE__);
		list ($ID_THEME) = mysql_fetch_row($result);
		mysql_free_result($result);

		// This will be theme number...
		$ID_THEME++;

		$setString = '';
		foreach ($install_info as $var => $val)
			$setString .= "
				($ID_THEME, '" . addslashes($var) . "', '" . addslashes($val) . "'),";
		$setString = substr($setString, 0, -1);

		db_query("
			INSERT INTO {$db_prefix}themes
				(ID_THEME, variable, value)
			VALUES$setString", __FILE__, __LINE__);

		updateSettings(array('knownThemes' => strtr($modSettings['knownThemes'] . ',' . $ID_THEME, array(',,' => ','))));
	}

	redirectexit('action=theme;sa=install;theme_id=' . $ID_THEME . ';theme_name=' . urlencode(stripslashes($install_info['name'])) . ';sesc=' . $context['session_id']);
}

// Possibly the simplest and best example of how to ues the template system.
function WrapAction()
{
	global $context, $settings, $sourcedir;

	// Load any necessary template(s)?
	if (isset($settings['catch_action']['template']))
	{
		// Load both the template and language file. (but don't fret if the language file isn't there...)
		loadTemplate($settings['catch_action']['template']);
		loadLanguage($settings['catch_action']['template'], '', false);
	}

	// Any special layers?
	if (isset($settings['catch_action']['layers']))
		$context['template_layers'] = $settings['catch_action']['layers'];

	// Just call a function?
	if (isset($settings['catch_action']['function']))
	{
		if (isset($settings['catch_action']['filename']))
			template_include($sourcedir . '/' . $settings['catch_action']['filename'], true);

		$settings['catch_action']['function']();
	}
	// And finally, the main sub template ;).
	else
		$context['sub_template'] = $settings['catch_action']['sub_template'];
}

// Set an option via javascript.
function SetJavaScript()
{
	global $db_prefix, $ID_MEMBER, $settings, $user_info;

	// Sorry, guests can't do this.
	if ($user_info['is_guest'])
		obExit(false);

	// Check the session id.
	checkSession('get');

	// This good-for-nothing pixel is being used to keep the session alive.
	if (empty($_GET['var']) || !isset($_GET['val']))
		redirectexit($settings['images_url'] . '/blank.gif', false);

	// Use a specific theme?
	if (isset($_GET['id']))
		$settings['theme_id'] = (int) $_GET['id'];

	// Update the option.
	db_query("
		REPLACE INTO {$db_prefix}themes
			(ID_THEME, ID_MEMBER, variable, value)
		VALUES ($settings[theme_id], $ID_MEMBER, '$_GET[var]', '" . (is_array($_GET['val']) ? implode(',', $_GET['val']) : $_GET['val']) . "')", __FILE__, __LINE__);

	// Don't output anything...
	redirectexit($settings['images_url'] . '/blank.gif', false);
}

function EditTheme()
{
	global $context, $settings, $db_prefix, $boarddir;

	isAllowedTo('admin_forum');
	loadTemplate('Themes');

	adminIndex('manage_themes');

	$_GET['id'] = (int) $_GET['id'];
	$context['session_error'] = false;

	// Get the directory of the theme we are editing.
	$request = db_query("
		SELECT value, ID_THEME
		FROM {$db_prefix}themes
		WHERE variable = 'theme_dir'
			AND ID_THEME = $_GET[id]", __FILE__, __LINE__);
	list ($theme_dir, $context['theme_id']) = mysql_fetch_row($request);
	mysql_free_result($request);

	if (isset($_POST['submit']))
	{
		if (checkSession('post', '', false) == '')
		{
			$_POST['entire_file'] = rtrim(strtr(stripslashes($_POST['entire_file']), array("\r" => '', '   ' => "\t")));

			if (isset($_REQUEST['style']))
			{
				$fp = fopen($theme_dir . '/style.css', 'w');
				fwrite($fp, $_POST['entire_file']);
				fclose($fp);
			}
			else
			{
				$fp = fopen($theme_dir . '/index.template.php', 'w');
				fwrite($fp, $_POST['entire_file']);
				fclose($fp);
			}
		}
		// Session timed out.
		else
		{
			loadLanguage('Errors');

			$context['session_error'] = true;
			$context['sub_template'] = isset($_REQUEST['style']) ? 'edit_style' : 'edit_template';

			// Recycle the submitted data.
			$context['entire_file'] = htmlspecialchars(stripslashes($_POST['entire_file']));

			// You were able to submit it, so it's reasonable to assume you are allowed to save.
			$context['allow_save'] = true;

			return;
		}
	}
	else
		checkSession('get');

	if (isset($_REQUEST['style']) && file_exists($theme_dir . '/style.css'))
	{
		$context['allow_save'] = is_writable($theme_dir . '/style.css');
		$context['allow_save_filename'] = strtr($theme_dir . '/style.css', array($boarddir => '...'));
		$context['entire_file'] = implode('', file($theme_dir . '/style.css'));

		$context['sub_template'] = 'edit_style';
	}
	elseif (!isset($_REQUEST['style']) && file_exists($theme_dir . '/index.template.php'))
	{
		$context['allow_save'] = is_writable($theme_dir . '/index.template.php');
		$context['allow_save_filename'] = strtr($theme_dir . '/index.template.php', array($boarddir => '...'));
		$context['entire_file'] = implode('', file($theme_dir . '/index.template.php'));

		$context['sub_template'] = 'edit_template';
	}
	else
		fatal_lang_error('theme_edit_missing', false);

	$context['entire_file'] = htmlspecialchars(strtr($context['entire_file'], array("\t" => '   ')));
}

function convert_template($output_dir, $old_template = '')
{
	global $boarddir;

	if ($old_template == '')
	{
		// Step 1: Get the template.php file.
		if (file_exists($boarddir . '/template.php'))
			$old_template = implode('', file($boarddir . '/template.php'));
		elseif (file_exists($boarddir . '/template.html'))
			$old_template = implode('', file($boarddir . '/template.html'));
		else
			fatal_lang_error('theme_convert_error');
	}

	// Step 2: Change any single quotes to \'.
	$old_template = strtr($old_template, array('\'' => '\\\''));

	// Step 3: Parse out any existing PHP code.
	$old_template = preg_replace('~\<\?php(.*)\?\>~es', "phpcodefix('\$1')", $old_template);

	// Step 4: Now we add the beginning and end...
	$old_template = '<?php
// Version: 1.0; index

// Initialize the template... mainly little settings.
function template_init()
{
	global $context, $settings, $options, $txt;

	/* Use images from default theme when using templates from the default theme?
		if this is always, images from the default theme will be used.
		if this is defaults, images from the default theme will only be used with default templates.
		if this is never, images from the default theme will not be used. */
	$settings[\'use_default_images\'] = \'never\';
}

// The main sub template above the content.
function template_main_above()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Show right to left and the character set for ease of translating.
	echo ' . "'" . $old_template . "'" . ';
}

// Show a linktree.  This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree()
{
	global $context, $settings, $options;

	// Folder style or inline?  Inline has a smaller font.
	echo \'<span class="nav"\', $settings[\'linktree_inline\'] ? \' style="font-size: smaller;"\' : \'\', \'>\';

	// Each tree item has a URL and name.  Some may have extra_before and extra_after.
	foreach ($context[\'linktree\'] as $k => $tree)
	{
		// Show the | | |-[] Folders.
		if (!$settings[\'linktree_inline\'])
		{
			if ($k > 0)
				echo str_repeat(\'<img src="\' . $settings[\'images_url\'] . \'/icons/linktree_main.gif" alt="| " border="0" />\', $k - 1), \'<img src="\' . $settings[\'images_url\'] . \'/icons/linktree_side.gif" alt="|-" border="0" />\';
			echo \'<img src="\' . $settings[\'images_url\'] . \'/icons/folder_open.gif" alt="+" border="0" />&nbsp; \';
		}

		if (isset($tree[\'extra_before\']))
			echo $tree[\'extra_before\'];
		echo \'<b>\', $settings[\'linktree_link\'] && isset($tree[\'url\']) ? \'<a href="\' . $tree[\'url\'] . \'" class="nav">\' . $tree[\'name\'] . \'</a>\' : $tree[\'name\'], \'</b>\';
		if (isset($tree[\'extra_after\']))
			echo $tree[\'extra_after\'];

		// Don\'t show a separator for the last one.
		if ($k != count($context[\'linktree\']) - 1)
			echo $settings[\'linktree_inline\'] ? \' &nbsp;|&nbsp; \' : \'<br />\';
	}

	echo \'</span>\';
}

// Show the menu up top.  Something like [home] [help] [profile] [logout]...
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Show the [home] and [help] buttons.
	echo \'
				<a href="\', $scripturl, \'">\', ($settings[\'use_image_buttons\'] ? \'<img src="\' . $settings[\'images_url\'] . \'/\' . $context[\'user\'][\'language\'] . \'/home.gif" alt="\' . $txt[103] . \'" border="0" />\' : $txt[103]), \'</a>\', $context[\'menu_separator\'], \'
				<a href="\', $scripturl, \'?action=help" target="_blank">\', ($settings[\'use_image_buttons\'] ? \'<img src="\' . $settings[\'images_url\'] . \'/\' . $context[\'user\'][\'language\'] . \'/help.gif" alt="\' . $txt[119] . \'" border="0" />\' : $txt[119]), \'</a>\', $context[\'menu_separator\'];

	// How about the [search] button?
	if ($context[\'allow_search\'])
		echo \'
				<a href="\', $scripturl, \'?action=search">\', ($settings[\'use_image_buttons\'] ? \'<img src="\' . $settings[\'images_url\'] . \'/\' . $context[\'user\'][\'language\'] . \'/search.gif" alt="\' . $txt[182] . \'" border="0" />\' : $txt[182]), \'</a>\', $context[\'menu_separator\'];

	// Is the user allowed to administrate at all? ([admin])
	if ($context[\'allow_admin\'])
		echo \'
				<a href="\', $scripturl, \'?action=admin">\', ($settings[\'use_image_buttons\'] ? \'<img src="\' . $settings[\'images_url\'] . \'/\' . $context[\'user\'][\'language\'] . \'/admin.gif" alt="\' . $txt[2] . \'" border="0" />\' : $txt[2]), \'</a>\', $context[\'menu_separator\'];

	// Edit Profile... [profile]
	if ($context[\'allow_edit_profile\'])
		echo \'
				<a href="\', $scripturl, \'?action=profile">\', ($settings[\'use_image_buttons\'] ? \'<img src="\' . $settings[\'images_url\'] . \'/\' . $context[\'user\'][\'language\'] . \'/profile.gif" alt="\' . $txt[79] . \'" border="0" />\' : $txt[467]), \'</a>\', $context[\'menu_separator\'];

	// The [calendar]!
	if ($context[\'allow_calendar\'])
		echo \'
				<a href="\', $scripturl, \'?action=calendar">\', ($settings[\'use_image_buttons\'] ? \'<img src="\' . $settings[\'images_url\'] . \'/\' . $context[\'user\'][\'language\'] . \'/calendar.gif" alt="\' . $txt[\'calendar24\'] . \'" border="0" />\' : $txt[\'calendar24\']), \'</a>\', $context[\'menu_separator\'];

	// If the user is a guest, show [login] and [register] buttons.
	if ($context[\'user\'][\'is_guest\'])
	{
		echo \'
				<a href="\', $scripturl, \'?action=login">\', ($settings[\'use_image_buttons\'] ? \'<img src="\' . $settings[\'images_url\'] . \'/\' . $context[\'user\'][\'language\'] . \'/login.gif" alt="\' . $txt[34] . \'" border="0" />\' : $txt[34]), \'</a>\', $context[\'menu_separator\'], \'
				<a href="\', $scripturl, \'?action=register">\', ($settings[\'use_image_buttons\'] ? \'<img src="\' . $settings[\'images_url\'] . \'/\' . $context[\'user\'][\'language\'] . \'/register.gif" alt="\' . $txt[97] . \'" border="0" />\' : $txt[97]), \'</a>\';
	}
	// Otherwise, they might want to [logout]...
	else
		echo \'
				<a href="\', $scripturl, \'?action=logout;sesc=\', $context[\'session_id\'], \'">\', ($settings[\'use_image_buttons\'] ? \'<img src="\' . $settings[\'images_url\'] . \'/\' . $context[\'user\'][\'language\'] . \'/logout.gif" alt="\' . $txt[108] . \'" border="0" />\' : $txt[108]), \'</a>\';
}

?>';

	// Step 5: Do the html tag.
	$old_template = preg_replace('~\<html\>~i', '<html\', $context[\'right_to_left\'] ? \' dir="rtl"\' : \'\', \'>', $old_template);

	// Step 6: The javascript stuff.
	$old_template = preg_replace('~\<head\>~i', '<head>
	<script language="JavaScript" type="text/javascript" src="\', $settings[\'default_theme_url\'], \'/script.js"></script>
	<script language="JavaScript" type="text/javascript"><!--
		var smf_theme_url = "\', $settings[\'theme_url\'], \'";
		var smf_images_url = "\', $settings[\'images_url\'], \'";
	// --></script>
	\' . $context[\'html_headers\'] . \'', $old_template);

	// Step 7: The character set.
	$old_template = preg_replace('~\<meta[^>]+http-equiv=["]?Content-Type["]?[^>]*?\>~i', '<meta http-equiv="Content-Type" content="text/html; charset=\', $context[\'character_set\'], \'" />', $old_template);

	// Step 8: The wonderous <yabb ...> tags.
	$tags = array(
		// <yabb title>
		'title' => '\' . $context[\'page_title\'] . \'',
		// <yabb boardname>
		'boardname' => '\' . $context[\'forum_name\'] . \'',
		// <yabb uname>
		'uname' => '\';

	// If the user is logged in, display stuff like their name, new messages, etc.
	if ($context[\'user\'][\'is_logged\'])
	{
		echo \'
				\', $txt[\'hello_member\'], \' <b>\', $context[\'user\'][\'name\'], \'</b>, \';

		// Are there any members waiting for approval?
		if (!empty($context[\'unapproved_members\']))
			echo \'<br />
				\', $context[\'unapproved_members\'] == 1 ? $txt[\'approve_thereis\'] : $txt[\'approve_thereare\'], \' <a href="\', $scripturl, \'?action=regcenter">\', $context[\'unapproved_members\'] == 1 ? $txt[\'approve_member\'] : $context[\'unapproved_members\'] . \' \' . $txt[\'approve_members\'], \'</a> \', $txt[\'approve_members_waiting\'];

		// Is the forum in maintenance mode?
		if ($context[\'in_maintenance\'] && $context[\'user\'][\'is_admin\'])
			echo \'<br />
				<b>\', $txt[616], \'</b>\';
	}
	// Otherwise they\'re a guest - so politely ask them to register or login.
	else
		echo \'
				\', $txt[\'welcome_guest\'];

	echo ' . "'",
		// <yabb im>
		'im' => '\';
	if ($context[\'user\'][\'is_logged\'] && $context[\'allow_pm\'])
		echo $txt[152], \' <a href="\', $scripturl, \'?action=pm">\', $context[\'user\'][\'messages\'], \' \', ($context[\'user\'][\'messages\'] != 1 ? $txt[153] : $txt[471]), \'</a>\', $txt[\'newmessages4\'], \'  \', $context[\'user\'][\'unread_messages\'], \' \', ($context[\'user\'][\'unread_messages\'] == 1 ? $txt[\'newmessages0\'] : $txt[\'newmessages1\']), \'.\';
	echo ' . "'",
		// <yabb time>
		'time' => '\' . $context[\'current_time\'] . \'',
		// <yabb menu>
		'menu' => '\';

	// Show the menu here, according to the menu sub template.
	template_menu();

	echo ' . "'",
		// <yabb position>
		'position' => '\' . $context[\'page_title\'] . \'',
		// <yabb news>
		'news' => '\';

	// Show a random news item? (or you could pick one from news_lines...)
	if (!empty($settings[\'enable_news\']))
		echo \'<b>\', $txt[102], \':</b> \', $context[\'random_news_line\'];

	echo ' . "'",
		// <yabb main>
		'main' => '\';
}

function template_main_below()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo ' ."'",
		// <yabb vbStyleLogin>
		'vbstylelogin' => '\';

	// Show a vB style login for quick login?
	if ($context[\'show_vBlogin\'])
		echo \'
	<table cellspacing="0" cellpadding="0" border="0" align="center" width="90%">
		<tr><td nowrap="nowrap" align="right">
			<form action="\', $scripturl, \'?action=login2" method="post"><br />
				<input type="text" name="user" size="7" />
				<input type="password" name="passwrd" size="7" />
				<select name="cookielength">
					<option value="60">\', $txt[\'smf53\'], \'</option>
					<option value="1440">\', $txt[\'smf47\'], \'</option>
					<option value="10080">\', $txt[\'smf48\'], \'</option>
					<option value="302400">\', $txt[\'smf49\'], \'</option>
					<option value="-1" selected="selected">\', $txt[\'smf50\'], \'</option>
				</select>
				<input type="submit" value="\', $txt[34], \'" /><br />
				\', $txt[\'smf52\'], \'
			</form>
		</td></tr>
	</table>\';
	else
		echo \'<br />\';

	echo ' . "'",
		// <yabb copyright>
		'copyright' => '\', theme_copyright(), \'',
	);

	foreach ($tags as $yy => $val)
		$old_template = preg_replace('~\<yabb\s+' . $yy . '\>~i', $val, $old_template);

	// Step 9: Add the time creation code.
	$old_template = preg_replace('~\</body\>~i', '\';

	// Show the load time?
	if ($context[\'show_load_time\'])
		echo \'
	<div align="center" class="smalltext">
		\', $txt[\'smf301\'], $context[\'load_time\'], $txt[\'smf302\'], $context[\'load_queries\'], $txt[\'smf302b\'], \'
	</div>\';

	echo \'</body>', $old_template);

	// Step 10: Try to make the style changes.  (function because it's a lot of work...)
	$style = makeStyleChanges($old_template);

	$fp = @fopen($output_dir . '/index.template.php', 'w');
	fwrite($fp, $old_template);
	fclose($fp);
}

// This is here because it's sorta complex.
function phpcodefix($string)
{
	// First remove the slashes from the single quotes.
	$string = strtr($string, array('\\\'' => '\''));

	// Now add on an end echo and begin echo ;).
	$string = "';
$string
	echo '";

	return $string;
}

function makeStyleChanges(&$old_template)
{
	if (preg_match('~</style>~i', $old_template) == 0)
		return false;

	preg_match('~(<style[^<]+)(</style>)~is', $old_template, $style);

	if (empty($style[1]))
		return false;

	$new_style = $style[1];

	// Add some extra stuff...
	$new_style .= '
.quoteheader, .codeheader {color: #000000; text-decoration: none; font-style: normal; font-weight: bold;}
.smalltext {font-size: 8pt;}
.normaltext {font-size: 10pt;}
.largetext {font-size: 12pt;}
input.check {background-color: transparent;}';

	// Get rid of the old .windowbg3.
	$new_style = preg_replace('~\.windowbg3~i', '.hrcolor', $new_style);

	// Add some stuff to .code and .quote...
	$new_style = preg_replace('~(\.code\s*[{][^}]+)}~is', '$1; border: 1px solid black; margin: 1px; padding: 1px;}', $new_style);
	$new_style = preg_replace('~(\.quote\s*[{][^}]+)}~is', '$1; border: 1px solid black; margin: 1px; padding: 1px;}', $new_style);
	$new_style = preg_replace('~(\.code,\s*\.quote\s*[{][^}]+)}~is', '$1; border: 1px solid black; margin: 1px; padding: 1px;}', $new_style);

	// Copy from .text1 => .titlebg.
	preg_match('~\.text1\s*[{]([^}]+)}~is', $new_style, $temp);
	if (isset($temp[1]))
	{
		$new_style = preg_replace('~\.titlebg(\s*[{])([^}]+)}~is', '.titlebg, tr.titlebg td, .titlebg a:link, .titlebg a:visited, .titlebg a:hover$1' . $temp[1] . ';$2}', $new_style);
		$new_style = preg_replace('~\.text1\s*[{]([^}]+)}~is', '', $new_style);
	}
	else
		$new_style = preg_replace('~\.titlebg(\s*[{][^}]+)}~is', '.titlebg, tr.titlebg td, .titlebg a:link, .titlebg a:visited, .titlebg a:hover$1}', $new_style);

	// Look for the background-color of bordercolor... if it's not found, try black. (dumb guess!)
	preg_match('~\.bordercolor\s*[{]([^}]+)}~is', $new_style, $temp);
	if (!empty($temp[1]))
		preg_match('~background(?:-color)?:\s*([^;}\s]+)~is', $temp[1], $temp);
	if (empty($temp[1]))
		$temp[1] = 'black';

	$new_style .= '
.tborder {border: 1px solid ' . $temp[1] . ';}';

	$old_template = str_replace($style[0], $new_style . "\n" . $style[2], $old_template);

	return true;
}

?>