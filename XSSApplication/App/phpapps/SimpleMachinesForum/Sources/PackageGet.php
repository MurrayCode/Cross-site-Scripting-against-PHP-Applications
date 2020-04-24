<?php
/******************************************************************************
* PackageGet.php                                                              *
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

// Browse the list of package servers, add servers...
function PackageGet()
{
	global $txt, $scripturl, $context, $boarddir, $sourcedir, $modSettings;

	isAllowedTo('admin_forum');
	require_once($sourcedir . '/Subs-Package.php');

	// Still managing packages...
	adminIndex('manage_packages');

	// Use the Packages template... no reason to separate.
	loadLanguage('Packages');
	loadTemplate('Packages');

	// Add the package_above/below layer and use the template_servers template.
	$context['template_layers'][] = 'package';
	$context['sub_template'] = 'servers';

	// Add the appropriate items to the link tree.
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=packages',
		'name' => &$txt['package1']
	);
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=packageget',
		'name' => &$txt['smf182']
	);
	$context['page_title'] = $txt['package1'] . ' - ' . $txt['smf182'];

	// Load the list of servers.
	if (!file_exists($boarddir . '/Packages/server.list'))
	{
		@touch($boarddir . '/Packages/server.list');
		@chmod($boarddir . '/Packages/server.list', 0777);
	}
	$servers = file($boarddir . '/Packages/server.list');

	$context['servers'] = array();
	for ($i = 0, $n = count($servers); $i < $n; $i++)
	{
		list ($name, $url) = explode('|^|', $servers[$i]);

		// Not too shabby, huh?
		$context['servers'][] = array(
			'name' => stripslashes($name),
			'url' => $url,
			'id' => $i
		);
	}

	$context['package_download_broken'] = !is_writable($boarddir . '/Packages') || !is_writable($boarddir . '/Packages/server.list') || !is_writable($boarddir . '/Packages/installed.list');
	if ($context['package_download_broken'])
	{
		if (isset($_POST['ftp_username']))
		{
			$ftp = new ftp_connection($_POST['ftp_server'], $_POST['ftp_port'], $_POST['ftp_username'], $_POST['ftp_password']);
			if ($ftp->error === false)
			{
				// I know, I know... but a lot of people want to type /home/xyz/... which is wrong, but logical.
				if (!$ftp->chdir($_POST['ftp_path']))
					$ftp->chdir(preg_replace('~^/home/[^/]+?~', '', $_POST['ftp_path']));
			}
		}

		if (!isset($ftp) || $ftp->error !== false)
		{
			if (!isset($_POST['ftp_path']))
			{
				if (preg_match('~^/home/([^/]+?)/public_html~', $_SERVER['DOCUMENT_ROOT'], $match))
				{
					if (!isset($_POST['ftp_username']) && empty($modSettings['package_server']))
						$_POST['ftp_username'] = $match[1];

					$_POST['ftp_path'] = strtr($_SERVER['DOCUMENT_ROOT'], array('/home/' . $match[1] => ''));

					if (substr($_POST['ftp_path'], -1) == '/')
						$_POST['ftp_path'] = substr($_POST['ftp_path'], 0, -1);

					if (strlen(dirname($_SERVER['PHP_SELF'])) > 1)
						$_POST['ftp_path'] .= dirname($_SERVER['PHP_SELF']);
				}
				elseif (substr($boarddir, 0, 9) == '/var/www/')
					$_POST['ftp_path'] = substr($boarddir, 8);
				else
					$_POST['ftp_path'] = strtr($boarddir, array($_SERVER['DOCUMENT_ROOT'] => ''));
			}

			$context['package_ftp'] = array(
				'server' => isset($_POST['ftp_server']) ? $_POST['ftp_server'] : (isset($modSettings['package_server']) ? $modSettings['package_server'] : 'localhost'),
				'port' => isset($_POST['ftp_port']) ? $_POST['ftp_port'] : (isset($modSettings['package_port']) ? $modSettings['package_port'] : '21'),
				'username' => isset($_POST['ftp_username']) ? $_POST['ftp_username'] : (isset($modSettings['package_username']) ? $modSettings['package_username'] : ''),
				'path' => $_POST['ftp_path'],
			);
		}
		else
		{
			$context['package_download_broken'] = false;

			$ftp->chmod('Packages', 0777);
			$ftp->chmod('Packages/server.list', 0777);
			$ftp->chmod('Packages/installed.list', 0777);

			$ftp->close();
		}
	}
}

// Browse a server's list of packages.
function PackageGBrowse()
{
	global $txt, $boardurl, $context, $scripturl, $boarddir, $sourcedir, $forum_version, $context;

	isAllowedTo('admin_forum');
	require_once($sourcedir . '/Subs-Package.php');

	// Managing packages....
	adminIndex('manage_packages');

	loadLanguage('Packages');
	loadTemplate('Packages');

	if (isset($_GET['server']))
	{
		if ($_GET['server'] == '')
			redirectexit('action=packageget');

		// Get the server list and find the current server.
		$servers = file($boarddir . '/Packages/server.list');
		$server = $_REQUEST['server'];

		// If server does not exist in list, dump out.
		if (!isset($servers[$server]))
			fatal_lang_error('smf191', false);

		list ($name, $url) = explode('|^|', chop($servers[$server]));

		// If there is a relative link, append to the stored server url.
		if (isset($_GET['relative']))
			$url = $url . (substr($url, -1) == '/' ? '' : '/') . $_GET['relative'];

		// Clear any "absolute" URL.  Since "server" is present, "absolute" is garbage.
		unset($_GET['absolute']);
	}
	elseif (isset($_GET['absolute']) && $_GET['absolute'] != '')
	{
		// Initialize the requried variables.
		$server = '';
		$url = $_GET['absolute'];
		$name = '';
		$_GET['package'] = $url . '/packages.xml?language=' . $context['user']['language'];

		// Clear any "relative" URL.  Since "server" is not present, "relative" is garbage.
		unset($_GET['relative']);
	}
	// Minimum required parameter did not exist so dump out.
	else
		fatal_lang_error('smf191', false);

	// In safe mode or on lycos?  Try this URL. (includes package-list for informational purposes ;).)
	if (@get_cfg_var('safe_mode') || @ini_get('safe_mode'))
		redirectexit($url . '/index.php?package-list&language=' . $context['user']['language'] . '&ref=' . $boardurl, false);

	// Attempt to connect.  If unsuccessful... try the URL.
	if (!isset($_GET['package']) || file_exists($_GET['package']))
		$_GET['package'] = $url . '/packages.xml?language=' . $context['user']['language'];

	// Check to be sure the packages.xml file actually exists where it is should be... or dump out.
	if ((isset($_GET['absolute']) && !url_exists($_GET['package'])) || (isset($_GET['relative']) && !url_exists($_GET['package'])))
		fatal_lang_error('packageget_unable', false, array($url . '/index.php'));

	// Read packages.xml and parse into xmlArray. (the true tells it to trim things ;).)
	$listing = new xmlArray(@file($_GET['package']), true);

	// Errm.... empty file?  Try the URL....
	if (!$listing->exists('package-list'))
		fatal_lang_error('packageget_unable', false, array($url . '/index.php'));

	// List out the packages...
	$context['package_list'] = array();

	$listing = $listing->path('package-list[0]');

	// Use the package list's name if it exists.
	if ($listing->exists('list-title'))
		$name = $listing->fetch('list-title');

	// Add the package_above/below layer and use the template_package_list template.
	$context['template_layers'][] = 'package';
	$context['sub_template'] = 'package_list';

	$context['page_title'] = $txt['smf183'] . ($name != '' ? ' - ' . stripslashes($name) : '');
	$context['server'] = $server;

	$instmods = loadInstalledPackages();

	// Look through the list of installed mods...
	foreach ($instmods as $installed_mod)
		$installed_mods[$installed_mod['id']] = $installed_mod['version'];

	// Get default author and email if they exist.
	if ($listing->exists('default-author'))
	{
		$default_author = $listing->fetch('default-author');
		if ($listing->exists('default-author/@email'))
			$default_email = $listing->fetch('default-author/@email');
	}

	// Get default web site if it exists.
	if ($listing->exists('default-website'))
	{
		$default_website = $listing->fetch('default-website');
		if ($listing->exists('default-website/@title'))
			$default_title = $listing->fetch('default-website/@title');
	}

	$the_version = strtr($forum_version, array('SMF ' => ''));

	$packageNum = 0;

	$sections = $listing->set('section');
	foreach ($sections as $i => $section)
	{
		$packages = $section->set('title|heading|text|remote|rule|modification|language|avatar-pack|theme|smiley-set');
		foreach ($packages as $thisPackage)
		{
			$package = &$context['package_list'][];
			$package['type'] = $thisPackage->name();

			// It's a Title, Heading, Rule or Text.
			if (in_array($package['type'], array('title', 'heading', 'text', 'rule')))
				$package['name'] = $thisPackage->fetch('.');
			// It's a Remote link.
			elseif ($package['type'] == 'remote')
			{
				$remote_type = $thisPackage->exists('@type') ? $thisPackage->fetch('@type') : 'relative';

				if ($remote_type == 'relative' && substr($thisPackage->fetch('@href'), 0, 7) != 'http://')
				{
					if (isset($_GET['absolute']))
						$current_url = $_GET['absolute'] . '/';
					elseif (isset($_GET['relative']))
						$current_url = $_GET['relative'] . '/';
					else
						$current_url = '';

					$current_url .= $thisPackage->fetch('@href');
					if (isset($_GET['absolute']))
						$package['href'] = $scripturl . '?action=pgbrowse;absolute=' . $current_url;
					else
						$package['href'] = $scripturl . '?action=pgbrowse;server=' . $context['server'] . ';relative=' . $current_url;
				}
				else
				{
					$current_url = $thisPackage->fetch('@href');
					$package['href'] = $scripturl . '?action=pgbrowse;absolute=' . $current_url;
				}

				$package['name'] = $thisPackage->fetch('.');
				$package['link'] = '<a href="' . $package['href'] . '">' . $package['name'] . '</a>';
			}
			// It's a package...
			else
			{
				if (isset($_GET['absolute']))
					$current_url = $_GET['absolute'] . '/';
				elseif (isset($_GET['relative']))
					$current_url = $_GET['relative'] . '/';
				else
					$current_url = '';

				$server_att = $server != '' ? ';server=' . $server : '';

				$package += $thisPackage->to_array();

				if (isset($package['website']))
					unset($package['website']);
				$package['author'] = array('name' => '');

				if ($package['description'] == '')
					$package['description'] = $txt['pacman8'];

				$package['is_installed'] = isset($installed_mods[$package['id']]);
				$package['is_current'] = $package['is_installed'] && ($installed_mods[$package['id']] == $package['version']);
				$package['is_newer'] = $package['is_installed'] && ($installed_mods[$packageInfo['id']] > $packageInfo['version']);

				// This package is either not installed, or installed but old.  Is it supported on this version of SMF?
				if (!$package['is_installed'] || (!$package['is_current'] && !$package['is_newer']))
					$package['can_install'] = !$thisPackage->exists('version/@for') || matchPackageVersion($the_version, $thisPackage->fetch('version/@for'));
				// Okay, it's already installed AND up to date.
				else
					$package['can_install'] = false;

				$already_exists = getPackageInfo($package['filename']);
				$package['download_conflict'] = !empty($already_exists) && $already_exists['id'] == $package['id'] && $already_exists['version'] != $package['version'];

				$package['href'] = $url . '/' . $package['filename'];
				$package['link'] = '<a href="' . $package['href'] . '">' . $package['name'] . '</a>';
				$package['download']['href'] = $scripturl . '?action=pgdownload' . $server_att . ';package=' . $current_url . $package['filename'] . ($package['download_conflict'] ? ';conflict' : '') . ';sesc=' . $context['session_id'];
				$package['download']['link'] = '<a href="' . $package['download']['href'] . '">' . $package['name'] . '</a>';

				if ($thisPackage->exists('author') || isset($default_author))
				{
					if ($thisPackage->exists('author/@email'))
						$package['author']['email'] = $thisPackage->fetch('author/@email');
					elseif (isset($default_email))
						$package['author']['email'] = $default_email;

					if ($thisPackage->exists('author') && $thisPackage->fetch('author') != '')
						$package['author']['name'] = $thisPackage->fetch('author');
					else
						$package['author']['name'] = $default_author;

					if ($package['author']['email'] != '')
					{
						// Only put the "mailto:" if it looks like a valid email address.  Some may wish to put a link to an SMF IM Form or other web mail form.
						$package['author']['href'] = preg_match('~^[\w\.\-]+@[\w][\w\-\.]+[\w]$~', $package['author']['email']) != 0 ? 'mailto:' . $package['author']['email'] : $package['author']['email'];
						$package['author']['link'] = '<a href="' . $package['author']['href'] . '">' . $package['author']['name'] . '</a>';
					}
				}

				if ($thisPackage->exists('website') || isset($default_website))
				{
					if ($thisPackage->exists('website') && $thisPackage->exists('website/@title'))
						$package['author']['website']['name'] = $thisPackage->fetch('website/@title');
					elseif (isset($default_title))
						$package['author']['website']['name'] = $default_title;
					elseif ($thisPackage->exists('website'))
						$package['author']['website']['name'] = $thisPackage->fetch('website');
					else
						$package['author']['website']['name'] = $default_website;

					if ($thisPackage->exists('website') && $thisPackage->fetch('website') != '')
						$authorhompage = $thisPackage->fetch('website');
					else
						$authorhompage = $default_website;

					if (strpos(strtolower($authorhompage), 'a href') === false)
					{
						$package['author']['website']['href'] = $authorhompage;
						$package['author']['website']['link'] = '<a href="' . $authorhompage . '">' . $package['author']['website']['name'] . '</a>';
					}
					else
					{
						if (preg_match('/a href="(.+?)"/', $authorhompage, $match) == 1)
							$package['author']['website']['href'] = $match[1];
						else
							$package['author']['website']['href'] = '';
						$package['author']['website']['link'] = $authorhompage;
					}
				}
				else
				{
					$package['author']['website']['href'] = '';
					$package['author']['website']['link'] = '';
				}
			}

			$package['is_remote'] = $package['type'] == 'remote';
			$package['is_title'] = $package['type'] == 'title';
			$package['is_heading'] = $package['type'] == 'heading';
			$package['is_text'] = $package['type'] == 'text';
			$package['is_line'] = $package['type'] == 'rule';

			$packageNum = in_array($package['type'], array('title', 'heading', 'text', 'remote', 'rule')) ? 0 : $packageNum + 1;
			$package['count'] = $packageNum;
		}
	}
}

// Download a package.
function PackageDownload()
{
	global $txt, $scripturl, $boarddir, $context, $sourcedir;

	isAllowedTo('admin_forum');
	require_once($sourcedir . '/Subs-Package.php');

	// Yet 'gain.... we're managing the packages still.
	adminIndex('manage_packages');
	loadLanguage('Packages');
	loadTemplate('Packages');

	// Add the package_above/below layer and use the template_downloaded template.
	$context['template_layers'][] = 'package';
	$context['sub_template'] = 'downloaded';

	if (isset($_GET['server']))
	{
		// Get the server list and find the current server.
		$servers = file($boarddir . '/Packages/server.list');
		$server = $_REQUEST['server'];

		// If server does not exist in list, dump out.
		if (!isset($servers[$server]))
			fatal_lang_error('smf191', false);

		list ($name, $url) = explode('|^|', chop($servers[$server]));
		$url = $url . '/';
	}
	else
	{
		checkSession('get');

		// Initialize the requried variables.
		$server = '';
		$url = '';
	}

	$package_name = basename($_REQUEST['package']);
	if (isset($_REQUEST['conflict']) || (isset($_REQUEST['auto']) && file_exists($boarddir . '/Packages/' . $package_name)))
	{
		// Find the extension, change abc.tar.gz to abc_1.tar.gz.
		if (strrpos(substr($package_name, 0, -3), '.') !== false)
		{
			$ext = substr($package_name, strrpos(substr($package_name, 0, -3), '.'));
			$package_name = substr($package_name, 0, strrpos(substr($package_name, 0, -3), '.')) . '_';
		}
		else
			$ext = '';

		// Find the first available.
		$i = 1;
		while (file_exists($boarddir . '/Packages/' . $package_name . $i . $ext))
			$i++;

		$package_name = $package_name . $i . $ext;
	}

	// First make sure it's a package.
	if (getPackageInfo($url . $_REQUEST['package']) == false)
		fatal_lang_error('package45', false);

	// Open both files and stream!
	$rf = @fopen($url . $_REQUEST['package'], 'rb') or fatal_lang_error('smf191', false);
	$fp = @fopen($boarddir . '/Packages/' . $package_name, 'wb');

	if ($fp == false)
	{
		// Try to fix it, maybe?
		@chmod($boarddir . '/Packages', 0777);
		$fp = @fopen($boarddir . '/Packages/' . $package_name, 'wb');

		if ($fp == false)
			fatal_lang_error('package_cant_download', false);
	}

	$buffer = '';
	while (!feof($rf))
	{
		$buffer = fread($rf, 1024);
		fwrite($fp, $buffer);
	}
	fclose($fp);
	fclose($rf);

	if (preg_match('~^http://[\w_\-]+\.simplemachines\.org/~', $_REQUEST['package']) == 1 && isset($_REQUEST['auto']))
		redirectexit('action=packages;sa=install;package=' . $package_name);

	// You just downloaded a mod from SERVER_NAME_GOES_HERE.
	$context['server'] = $server;

	$context['package'] = getPackageInfo($package_name);

	if (empty($context['package']))
		fatal_lang_error('package_cant_download', false);

	if ($context['package']['type'] == 'modification')
		$context['package']['install']['link'] = '<a href="' . $scripturl . '?action=packages;sa=install;package=' . $context['package']['filename'] . '">[ ' . $txt['package11'] . ' ]</a>';
	elseif ($context['package']['type'] == 'avatar')
		$context['package']['install']['link'] = '<a href="' . $scripturl . '?action=packages;sa=install;package=' . $context['package']['filename'] . '">[ ' . $txt['package12'] . ' ]</a>';
	elseif ($context['package']['type'] == 'language')
		$context['package']['install']['link'] = '<a href="' . $scripturl . '?action=packages;sa=install;package=' . $context['package']['filename'] . '">[ ' . $txt['package13'] . ' ]</a>';
	else
		$context['package']['install']['link'] = '';

	$context['package']['list_files']['link'] = '<a href="' . $scripturl . '?action=packages;sa=list;package=' . $context['package']['filename'] . '">[ ' . $txt['package14'] . ' ]</a>';

	// Free a little bit of memory...
	unset($context['package']['xml']);

	$context['page_title'] = $txt['smf192'];
}

// Upload a new package to the directory.
function PackageUpload()
{
	global $txt, $scripturl, $boarddir, $context, $sourcedir;

	isAllowedTo('admin_forum');
	require_once($sourcedir . '/Subs-Package.php');

	// That's correct... package manager yet again.
	adminIndex('manage_packages');
	loadLanguage('Packages');
	loadTemplate('Packages');

	// Add the package_above/below layer and use the template_downloaded template (yes... I know we actually uploaded it).
	$context['template_layers'][] = 'package';
	$context['sub_template'] = 'downloaded';

	// Check the file was even sent!
	if (!isset($_FILES['package']['name']) || $_FILES['package']['name'] == '' || !is_uploaded_file($_FILES['package']['tmp_name']))
		fatal_lang_error('package_upload_error');

	// Make sure it has a sane filename.
	$_FILES['package']['name'] = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $_FILES['package']['name']);

	if (strtolower(substr($_FILES['package']['name'], -4)) != '.zip' && strtolower(substr($_FILES['package']['name'], -7)) != '.tar.gz')
		fatal_error($txt['package_upload_error_supports'] . 'zip, tar.gz.', false);

	// We only need the filename...
	$packageName = basename($_FILES['package']['name']);

	// Setup the destination and throw an error if the file is already there!
	$destination = $boarddir . '/Packages/' . $packageName;
	if (file_exists($destination))
		fatal_lang_error('package_upload_error_exists');

	// Now move the file.
	move_uploaded_file($_FILES['package']['tmp_name'], $destination);
	@chmod($destination, 0777);

	// If we got this far that should mean it's available.
	$context['package'] = getPackageInfo($packageName);
	$context['server'] = '';

	// Not really a package, you lazy bum!
	if (empty($context['package']))
	{
		@unlink($destination);
		fatal_lang_error('package_upload_error_broken', false);
	}

	if ($context['package']['type'] == 'modification')
		$context['package']['install']['link'] = '<a href="' . $scripturl . '?action=packages;sa=install;package=' . $context['package']['filename'] . '">[ ' . $txt['package11'] . ' ]</a>';
	elseif ($context['package']['type'] == 'avatar')
		$context['package']['install']['link'] = '<a href="' . $scripturl . '?action=packages;sa=install;package=' . $context['package']['filename'] . '">[ ' . $txt['package12'] . ' ]</a>';
	elseif ($context['package']['type'] == 'language')
		$context['package']['install']['link'] = '<a href="' . $scripturl . '?action=packages;sa=install;package=' . $context['package']['filename'] . '">[ ' . $txt['package13'] . ' ]</a>';
	else
		$context['package']['install']['link'] = '';

	$context['package']['list_files']['link'] = '<a href="' . $scripturl . '?action=packages;sa=list;package=' . $context['package']['filename'] . '">[ ' . $txt['package14'] . ' ]</a>';

	unset($context['package']['xml']);

	$context['page_title'] = $txt['package_uploaded_success'];
}

// Add a package server to the list.
function PackageServerAdd()
{
	global $boarddir;

	// Validate the user.
	checkSession();
	isAllowedTo('admin_forum');

	// If they put a slash on the end, get rid of it.
	if (substr($_POST['serverurl'], -1) == '/')
		$_POST['serverurl'] = substr($_POST['serverurl'], 0, -1);

	// Just append to the file.
	if (!file_exists($boarddir . '/Packages/server.list'))
	{
		@touch($boarddir . '/Packages/server.list');
		@chmod($boarddir . '/Packages/server.list', 0777);
	}
	$fp = fopen($boarddir . '/Packages/server.list', 'a');
	fputs($fp, $_POST['servername'] . '|^|' . $_POST['serverurl'] . "\n");
	fclose($fp);

	redirectexit('action=packageget');
}

// Remove a server from the list.
function PackageServerRemove()
{
	global $boarddir;

	// Administrators only... as always.
	isAllowedTo('admin_forum');

	// Get the current server list.
	if (!file_exists($boarddir . '/Packages/server.list'))
	{
		@touch($boarddir . '/Packages/server.list');
		@chmod($boarddir . '/Packages/server.list', 0777);
	}
	$servers = file($boarddir . '/Packages/server.list');

	// Write out a new one, skipping the deleted one.
	$fp = fopen($boarddir . '/Packages/server.list', 'w');
	for ($i = 0, $n = count($servers); $i < $n; $i++)
		if ($i != $_GET['server'])
			fputs($fp, chop($servers[$i]) . "\n");

	redirectexit('action=packageget');
}

?>