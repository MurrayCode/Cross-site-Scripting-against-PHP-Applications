<?php
/******************************************************************************
* Help.php                                                                    *
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

/*	This file has the important job of taking care of help messages and the
	help center.  It does this with two simple functions:

	void ShowHelp()
		- finds the appropriate help html files for this theme and language,
		  and redirects to them.
		- looks for the user's language, but falls back to the forum default.
		- if one isn't found for the current theme, the default theme's is
		  used in its place.
		- accessed with ?action=help.

	void ShowAdminHelp()
		- shows a popup for administrative or user help.
		- uses the help parameter to decide what string to display and where
		  to get the string from. ($helptxt or $txt?)
		- loads the ManagePermissions language file if the help starts with
		  permissionhelp.
		- uses the Help template, popup sub template, no layers.
		- accessed via ?action=helpadmin;help=??.
*/

// Redirect to the user help ;).
function ShowHelp()
{
	global $settings, $user_info, $language;

	// Find the correct html file...
	if (file_exists($settings['theme_dir'] . '/help/index.' . $user_info['language'] . '.html'))
		redirectexit($settings['theme_url'] . '/help/index.' . $user_info['language'] . '.html', false);
	elseif (file_exists($settings['theme_dir'] . '/help/index.' . $language . '.html'))
		redirectexit($settings['theme_url'] . '/help/index.' . $language . '.html', false);
	elseif (file_exists($settings['default_theme_dir'] . '/help/index.' . $user_info['language'] . '.html'))
		redirectexit($settings['default_theme_url'] . '/help/index.' . $user_info['language'] . '.html', false);
	else
		redirectexit($settings['default_theme_url'] . '/help/index.' . $language . '.html', false);
}

// Show some of the more detailed help to give the admin an idea...
function ShowAdminHelp()
{
	global $txt, $helptxt, $context;

	// Load the admin help language file and template.
	loadLanguage('Help');
	loadTemplate('Help');

	// Permission specific help?
	if (isset($_GET['help']) && substr($_GET['help'], 0, 14) == 'permissionhelp')
		loadLanguage('ManagePermissions');

	// Set the page title to something relevant.
	$context['page_title'] = $context['forum_name'] . ' - ' . $txt[119];

	// Don't show any template layers, just the popup sub template.
	$context['template_layers'] = array();
	$context['sub_template'] = 'popup';

	// What help string should be used?
	if (isset($helptxt[$_GET['help']]))
		$context['help_text'] = &$helptxt[$_GET['help']];
	elseif (isset($txt[$_GET['help']]))
		$context['help_text'] = &$txt[$_GET['help']];
	else
		$context['help_text'] = $_GET['help'];
}

?>