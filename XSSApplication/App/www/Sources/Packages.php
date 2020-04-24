<?php
/******************************************************************************
* Packages.php                                                                *
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

// This is the notorious package manager..... :/.
function Packages()
{
	global $txt, $scripturl, $sourcedir, $context;

	isAllowedTo('admin_forum');

	// Managing packages!
	adminIndex('manage_packages');

	// Load all the basic stuff.
	require_once($sourcedir . '/Subs-Package.php');
	loadLanguage('Packages');
	loadTemplate('Packages');

	// The package manager requires gzinflate (zlib) to work properly.
	if (!function_exists('gzinflate'))
		fatal_lang_error('package_no_gzip');

	// Set up the linktree and title so it's already done.
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=packages',
		'name' => &$txt['package1']
	);
	$context['page_title'] = $txt['package1'];

	// Delegation makes the world... that is, the package manager go 'round.
	$subActions = array(
		'' => 'PackageBrowse',
		'browse' => 'PackageBrowse',
		'remove' => 'PackageRemove',
		'list' => 'PackageList',
		'install' => 'PackageInstallTest',
		'install2' => 'PackageInstall',
		'uninstall' => 'PackageInstallTest',
		'uninstall2' => 'PackageInstall',
		'installed' => 'InstalledList',
		'options' => 'PackageOptions',
		'flush' => 'FlushInstall',
		'examine' => 'ExamineFile'
	);
	if (isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]))
		$subActions[$_REQUEST['sa']]();
	else
		$subActions['']();

	// Add the package above/below layer to the output.
	$context['template_layers'][] = 'package';
}

// Test install a package.
function PackageInstallTest()
{
	global $boarddir, $txt, $context, $scripturl, $sourcedir, $modSettings;

	// You have to specify a file!!
	if (!isset($_REQUEST['package']) || $_REQUEST['package'] == '')
		redirectexit('action=packages');
	$context['filename'] = $_REQUEST['package'];

	require_once($sourcedir . '/Subs-Package.php');

	$context['uninstalling'] = $_REQUEST['sa'] == 'uninstall';

	// Set up the linktree...
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=packages;sa=browse',
		'name' => $context['uninstalling'] ? $txt['package_uninstall_actions'] : $txt['package42']
	);
	$context['page_title'] .= ' - ' . ($context['uninstalling'] ? $txt['package_uninstall_actions'] : $txt['package42']);

	$context['sub_template'] = 'view_package';

	if (!file_exists($boarddir . '/Packages/' . $context['filename']))
		fatal_lang_error('package_no_file', false);

	// Make sure temp directory exists and is empty.
	if (file_exists($boarddir . '/Packages/temp'))
		deltree($boarddir . '/Packages/temp', false);
	if (!mktree($boarddir . '/Packages/temp', 0777))
		fatal_lang_error('package_cant_download', false);

	// Extract the files so we can get things like the readme, etc.
	$context['extracted_files'] = read_tgz_file($boarddir . '/Packages/' . $context['filename'], $boarddir . '/Packages/temp');

	// Get the package info...
	$packageInfo = getPackageInfo($context['filename']);
	$packageInfo['filename'] = $context['filename'];

	// Set the type of extraction...
	$context['extract_type'] = isset($packageInfo['type']) ? $packageInfo['type'] : 'modification';

	$instmods = loadInstalledPackages();

	// The mod isn't installed.... unless proven otherwise.
	$context['is_installed'] = false;
	foreach ($instmods as $installed_mod)
		if ($installed_mod['id'] == $packageInfo['id'])
			$old_version = $installed_mod['version'];

	// Wait, it's not installed yet!
	if (!isset($old_version) && $context['uninstalling'])
	{
		deltree($boarddir . '/Packages/temp');
		fatal_lang_error('package_cant_uninstall', false);
	}
	// Uninstalling?
	elseif ($context['uninstalling'])
	{
		$actions = parsePackageInfo($packageInfo['xml'], true, 'uninstall');

		// Gadzooks!  There's no uninstaller at all!?
		if (empty($actions))
			fatal_lang_error('package_uninstall_cannot', false);
	}
	elseif (isset($old_version) && $old_version != $packageInfo['version'])
	{
		// Look for an upgrade...
		$actions = parsePackageInfo($packageInfo['xml'], true, 'upgrade', $old_version);

		// There was no upgrade....
		if (empty($actions))
			$context['is_installed'] = true;
	}
	elseif (isset($old_version) && $old_version == $packageInfo['version'])
		$context['is_installed'] = true;

	if (!isset($old_version) || $context['is_installed'])
		$actions = parsePackageInfo($packageInfo['xml'], true, 'install');

	$context['actions'] = array();
	$context['ftp_needed'] = false;
	$chmod_files = array();

	if (empty($actions))
		return;

	foreach ($actions as $action)
	{
		if ($action['type'] == 'chmod')
		{
			$context['ftp_needed'] = true;
			$chmod_files[] = $action['filename'];
			continue;
		}
		elseif ($action['type'] == 'readme')
		{
			if (file_exists($boarddir . '/Packages/temp/' . $action['filename']))
				$context['package_readme'] = nl2br(htmlspecialchars(implode('', file($boarddir . '/Packages/temp/' . $action['filename']))));
			continue;
		}
		elseif ($action['type'] == 'modification')
		{
			if ($action['boardmod'])
				$mod_actions = parseBoardMod(@implode('', @file($boarddir . '/Packages/temp/' . $action['filename'])), true, $action['reverse']);
			else
				$mod_actions = parseModification(@implode('', @file($boarddir . '/Packages/temp/' . $action['filename'])), true, $action['reverse']);

			foreach ($mod_actions as $mod_action)
			{
				if ($mod_action['type'] == 'opened')
					$failed = false;
				elseif ($mod_action['type'] == 'failure')
					$failed = true;
				elseif ($mod_action['type'] == 'chmod')
				{
					$context['ftp_needed'] = true;
					$chmod_files[] = $mod_action['filename'];
				}
				elseif ($mod_action['type'] == 'saved')
					$context['actions'][] = array(
						'type' => $txt['package56'],
						'action' => strtr($mod_action['filename'], array($boarddir => '.')),
						'description' => $failed ? $txt['package_action_failure'] : $txt['package_action_success']
					);
				elseif ($mod_action['type'] == 'missing')
					$context['actions'][] = array(
						'type' => $txt['package56'],
						'action' => strtr($mod_action['filename'], array($boarddir => '.')),
						'description' => $txt['package_action_missing']
					);
				elseif ($mod_action['type'] == 'error')
					$context['actions'][] = array(
						'type' => $txt['package56'],
						'action' => strtr($mod_action['filename'], array($boarddir => '.')),
						'description' => $txt['package_action_error']
					);
			}

			// Don't add anything else.
			$thisAction = array();
		}
		elseif ($action['type'] == 'code')
			$thisAction = array(
				'type' => $txt['package57'],
				'action' => $action['filename']
			);
		elseif (in_array($action['type'], array('create-dir', 'create-file')))
			$thisAction = array(
				'type' => $txt['package50'] . ' ' . ($action['type'] == 'create-dir' ? $txt['package55'] : $txt['package54']),
				'action' => strtr($action['destination'], array($boarddir => '.'))
			);
		elseif (in_array($action['type'], array('require-dir', 'require-file')))
			$thisAction = array(
				'type' => $txt['package53'] . ' ' . ($action['type'] == 'require-dir' ? $txt['package55'] : $txt['package54']),
				'action' => strtr($action['destination'], array($boarddir => '.'))
			);
		elseif (in_array($action['type'], array('move-dir', 'move-file')))
			$thisAction = array(
				'type' => $txt['package51'] . ' ' . ($action['type'] == 'move-dir' ? $txt['package55'] : $txt['package54']),
				'action' => strtr($action['source'], array($boarddir => '.')) . ' => ' . strtr($action['destination'], array($boarddir => '.'))
			);
		elseif (in_array($action['type'], array('remove-dir', 'remove-file')))
			$thisAction = array(
				'type' => $txt['package52'] . ' ' . ($action['type'] == 'remove-dir' ? $txt['package55'] : $txt['package54']),
				'action' => strtr($action['filename'], array($boarddir => '.'))
			);

		if (empty($thisAction))
			continue;

		$thisAction['description'] = isset($action['description']) ? $action['description'] : '';
		$context['actions'][] = $thisAction;
	}

	if (file_exists($boarddir . '/Packages/temp'))
		deltree($boarddir . '/Packages/temp');

	if ($context['ftp_needed'])
	{
		if (isset($_POST['ftp_username']))
		{
			$ftp = new ftp_connection($_POST['ftp_server'], $_POST['ftp_port'], $_POST['ftp_username'], $_POST['ftp_password']);
			if ($ftp->error === false)
			{
				// Common mistake, so let's try to remedy it...
				if (!$ftp->chdir($_POST['ftp_path']))
					$ftp->chdir(preg_replace('~^/home/[^/]+?~', '', $_POST['ftp_path']));
			}
		}

		if (!isset($ftp) || $ftp->error !== false)
		{
			if (!isset($_POST['ftp_path']))
			{
				if (preg_match('~^/home/([^/]+?)/public_html~', $_SERVER['DOCUMENT_ROOT'], $match) == 1)
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
			$context['ftp_needed'] = false;

			if (substr($_POST['ftp_path'], -1) == '/')
				$_POST['ftp_path'] = substr($_POST['ftp_path'], 0, -1);
			$ftp_root = strtr($boarddir, array($_POST['ftp_path'] => ''));

			foreach ($chmod_files as $file)
			{
				$ftp->chmod(strtr($file, array($ftp_root => '')), 0777);
				$ftp->chmod(dirname(strtr($file, array($ftp_root => ''))), 0777);
			}
			$ftp->close();
		}
	}
}

// Apply another type of (avatar, language, etc.) package.
function PackageInstall()
{
	global $boarddir, $txt, $context, $scripturl, $sourcedir;

	// If there's no file, what are we installing?
	if (!isset($_REQUEST['package']) || $_REQUEST['package'] == '')
		redirectexit('action=packages');
	$context['filename'] = $_REQUEST['package'];

	require_once($sourcedir . '/Subs-Package.php');

	$context['uninstalling'] = $_REQUEST['sa'] == 'uninstall2';

	// Set up the linktree for other.
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=packages;sa=browse',
		'name' => $context['uninstalling'] ? $txt['smf198b'] : $txt['package37']
	);
	$context['page_title'] .= ' - ' . ($context['uninstalling'] ? $txt['smf198b'] : $txt['package37']);

	$context['sub_template'] = 'extract_package';

	if (!file_exists($boarddir . '/Packages/' . $context['filename']))
		fatal_lang_error('package_no_file', false);

	// Make sure temp directory exists and is empty!
	if (file_exists($boarddir . '/Packages/temp'))
		deltree($boarddir . '/Packages/temp', false);
	else
		mktree($boarddir . '/Packages/temp', 0777);

	// Let the unpacker do the work.
	$context['extracted_files'] = read_tgz_file($boarddir . '/Packages/' . $context['filename'], $boarddir . '/Packages/temp');

	// Get the package info...
	$packageInfo = getPackageInfo($context['filename']);
	$packageInfo['filename'] = $context['filename'];

	// Set the type of extraction...
	$context['extract_type'] = isset($packageInfo['type']) ? $packageInfo['type'] : 'modification';

	$instmods = loadInstalledPackages();

	// The mod isn't installed.... unless proven otherwise.
	$context['is_installed'] = false;
	foreach ($instmods as $installed_mod)
		if ($installed_mod['id'] == $packageInfo['id'])
			$old_version = $installed_mod['version'];

	// Wait, it's not installed yet!
	if (!isset($old_version) && $context['uninstalling'])
	{
		deltree($boarddir . '/Packages/temp');
		fatal_lang_error(1, false);
	}
	// Uninstalling?
	elseif ($context['uninstalling'])
	{
		$install_log = parsePackageInfo($packageInfo['xml'], false, 'uninstall');

		// Gadzooks!  There's no uninstaller at all!?
		if (empty($install_log))
			fatal_lang_error('package_uninstall_cannot', false);
	}
	elseif (isset($old_version) && $old_version != $packageInfo['version'])
	{
		// Look for an upgrade...
		$install_log = parsePackageInfo($packageInfo['xml'], false, 'upgrade', $old_version);

		// There was no upgrade....
		if (empty($install_log))
			$context['is_installed'] = true;
	}
	elseif (isset($old_version) && $old_version == $packageInfo['version'])
		$context['is_installed'] = true;

	if (!isset($old_version) || $context['is_installed'])
		$install_log = parsePackageInfo($packageInfo['xml'], false, 'install');

	$context['install_finished'] = false;

	if (!empty($install_log))
	{
		foreach ($install_log as $action)
		{
			if ($action['type'] == 'modification' && !empty($action['filename']))
			{
				if ($action['boardmod'])
					parseBoardMod(implode('', file($boarddir . '/Packages/temp/' . $action['filename'])), false, $action['reverse']);
				else
					parseModification(implode('', file($boarddir . '/Packages/temp/' . $action['filename'])), false, $action['reverse']);
			}
			elseif ($action['type'] == 'code' && !empty($action['filename']))
			{
				// This is just here as reference for what is available.
				global $txt, $boarddir, $sourcedir, $modSettings, $context, $settings, $db_prefix, $forum_version;

				// Now include the file and be done with it ;).
				require($boarddir . '/Packages/temp/' . $action['filename']);
			}
		}

		// Check if the mod has been installed.
		$seen = false;

		// Look through the list of installed mods...
		foreach ($instmods as $i => $installed_mod)
			if ($installed_mod['id'] == $packageInfo['id'])
			{
				if ($context['uninstalling'])
					$instmods[$i] = array();
				else
				{
					$instmods[$i]['version'] = $packageInfo['version'];
					$seen = true;
				}
				break;
			}

		// Hasn't.... make it show as installed.
		if (!$seen && !$context['uninstalling'])
			$instmods[] = $packageInfo;

		saveInstalledPackages($instmods);
		$context['install_finished'] = true;
	}

	// Clean house... get rid of the evidence ;).
	if (file_exists($boarddir . '/Packages/temp'))
		deltree($boarddir . '/Packages/temp');
}

// List the files in a package.
function PackageList()
{
	global $txt, $scripturl, $boarddir, $context, $sourcedir;

	require_once($sourcedir . '/Subs-Package.php');

	// No package?  Show him or her the door.
	if (!isset($_REQUEST['package']) || $_REQUEST['package'] == '')
		redirectexit('action=packages');

	$context['linktree'][] = array(
		'url' => $scripturl . '?action=packages;sa=list;package=' . $_REQUEST['package'],
		'name' => &$txt['smf180']
	);
	$context['page_title'] = $txt['smf180'];
	$context['sub_template'] = 'list';

	// The filename...
	$context['filename'] = $_REQUEST['package'];

	// Let the unpacker do the work.
	$context['files'] = read_tgz_file($boarddir . '/Packages/' . $_REQUEST['package'], null);
}

// List the files in a package.
function ExamineFile()
{
	global $txt, $scripturl, $boarddir, $context, $sourcedir;

	require_once($sourcedir . '/Subs-Package.php');

	// No package?  Show him or her the door.
	if (!isset($_REQUEST['package']) || $_REQUEST['package'] == '')
		redirectexit('action=packages');

	// No file?  Show him or her the door.
	if (!isset($_REQUEST['file']) || $_REQUEST['file'] == '')
		redirectexit('action=packages');

	if (isset($_REQUEST['raw']))
	{
		echo read_tgz_file($boarddir . '/Packages/' . $_REQUEST['package'], $_REQUEST['file'], true);
		obExit(false);
	}

	$context['linktree'][] = array(
		'url' => $scripturl . '?action=packages;sa=list;package=' . $_REQUEST['package'],
		'name' => &$txt['package_examine_file']
	);
	$context['page_title'] = $txt['package_examine_file'];
	$context['sub_template'] = 'examine';

	// The filename...
	$context['package'] = $_REQUEST['package'];
	$context['filename'] = $_REQUEST['file'];

	// Let the unpacker do the work.... but make sure we handle images properly.
	if (in_array(strtolower(strrchr($_REQUEST['file'], '.')), array('.bmp', '.gif', '.jpeg', '.jpg', '.png')))
		$context['filedata'] = '<img src="' . $scripturl . '?action=packages;sa=examine;package=' . $_REQUEST['package'] . ';file=' . $_REQUEST['file'] . ';raw" alt="' . $_REQUEST['file'] . '" />';
	elseif (strtolower(strrchr($_REQUEST['file'], '.')) == '.php')
		$context['filedata'] = highlight_php_code(htmlspecialchars(read_tgz_file($boarddir . '/Packages/' . $_REQUEST['package'], $_REQUEST['file'], true)));
	else
		$context['filedata'] = htmlspecialchars(read_tgz_file($boarddir . '/Packages/' . $_REQUEST['package'], $_REQUEST['file'], true));
}

// List the installed packages.
function InstalledList()
{
	global $txt, $scripturl, $context;

	// Set up the linktree so things are purdy.
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=packages;sa=installed',
		'name' => &$txt['package6']
	);
	$context['page_title'] .= ' - ' . $txt['package6'];
	$context['sub_template'] = 'view_installed';

	// Load the installed mods and send them to the template.
	$context['installed_mods'] = loadInstalledPackages();
}

// Empty out the installed list.
function FlushInstall()
{
	global $boarddir;

	// Open the file and write nothing to it.
	file_put_contents($boarddir . '/Packages/installed.list', '');

	redirectexit('action=packages;sa=installed');
}

// Delete a package.
function PackageRemove()
{
	global $scripturl, $boarddir;

	// Ack, don't allow deletion of arbitrary files here, could become a security hole somehow!
	if (!isset($_GET['package']) || $_GET['package'] == 'index.php' || $_GET['package'] == 'installed.list' || $_GET['package'] == 'server.list')
		redirectexit('action=packages;sa=browse');
	$_GET['package'] = strtr($_GET['package'], '/', '_');

	// Can't delete what's not there.
	if (file_exists($boarddir . '/Packages/' . $_GET['package']))
	{
		@chmod($boarddir . '/Packages/' . $_GET['package'], 0777);
		unlink($boarddir . '/Packages/' . $_GET['package']);
	}

	redirectexit('action=packages;sa=browse');
}

// Browse a list of installed packages.
function PackageBrowse()
{
	global $txt, $boarddir, $scripturl, $context, $forum_version;

	$context['linktree'][] = array(
		'url' => $scripturl . '?action=packages;sa=browse',
		'name' => &$txt['package3']
	);
	$context['page_title'] .= ' - ' . $txt['package3'];
	$context['sub_template'] = 'browse';

	$context['forum_version'] = $forum_version;

	$instmods = loadInstalledPackages();

	// Look through the list of installed mods...
	$installed_mods = array();
	foreach ($instmods as $installed_mod)
		$installed_mods[$installed_mod['id']] = $installed_mod['version'];

	$the_version = strtr($forum_version, array('SMF ' => ''));

	// Get a list of all the ids installed, so the latest packages won't include already installed ones.
	$context['installed_mods'] = array_keys($installed_mods);

	// Empty lists for now.
	$context['available_mods'] = array();
	$context['available_avatars'] = array();
	$context['available_languages'] = array();
	$context['available_other'] = array();
	$context['available_all'] = array();

	if ($dir = @opendir($boarddir . '/Packages'))
	{
		while ($package = readdir($dir))
		{
			if (substr($package, -7) != '.tar.gz' && substr($package, -4) != '.zip')
				continue;

			$packageInfo = getPackageInfo($package);
			if ($packageInfo === false)
				continue;

			$packageInfo['is_installed'] = isset($installed_mods[$packageInfo['id']]);
			$packageInfo['is_current'] = $packageInfo['is_installed'] && ($installed_mods[$packageInfo['id']] == $packageInfo['version']);
			$packageInfo['is_newer'] = $packageInfo['is_installed'] && ($installed_mods[$packageInfo['id']] > $packageInfo['version']);

			$packageInfo['can_install'] = false;
			$packageInfo['can_uninstall'] = false;
			$packageInfo['can_upgrade'] = false;

			// This package is currently NOT installed.  Check if it can be.
			if (!$packageInfo['is_installed'] && $packageInfo['xml']->exists('install'))
			{
				// Check if there's an install for *THIS* version of SMF.
				$installs = $packageInfo['xml']->set('install');
				foreach ($installs as $install)
					if (!$install->exists('@for') || matchPackageVersion($the_version, $install->fetch('@for')))
					{
						// Okay, this one is good to go.
						$packageInfo['can_install'] = true;
						break;
					}
			}
			// An already installed, but old, package.  Can we upgrade it?
			elseif ($packageInfo['is_installed'] && !$packageInfo['is_current'] && $packageInfo['xml']->exists('upgrade'))
			{
				$upgrades = $packageInfo['xml']->set('upgrade');

				// First go through, and check against the current version of SMF.
				foreach ($upgrades as $upgrade)
				{
					// Even if it is for this SMF, is it for the installed version of the mod?
					if (!$upgrade->exists('@for') || matchPackageVersion($the_version, $upgrade->fetch('@for')))
						if (!$upgrade->exists('@from') || matchPackageVersion($installed_mods[$packageInfo['id']], $upgrade->fetch('@from')))
						{
							$packageInfo['can_upgrade'] = true;
							break;
						}
				}
			}
			// Note that it has to be the current version to be uninstallable.  Shucks.
			elseif ($packageInfo['is_installed'] && $packageInfo['is_current'] && $packageInfo['xml']->exists('uninstall'))
			{
				$uninstalls = $packageInfo['xml']->set('uninstall');

				// Can we find any uninstallation methods that work for this SMF version?
				foreach ($uninstalls as $uninstall)
					if (!$uninstall->exists('@for') || matchPackageVersion($the_version, $uninstall->fetch('@for')))
					{
						$packageInfo['can_uninstall'] = true;
						break;
					}
			}

			// Store a complete list.
			$context['available_all'][] = $packageInfo;

			// Modification.
			if ($packageInfo['type'] == 'modification' || $packageInfo['type'] == 'mod')
				$context['available_mods'][] = $packageInfo;
			// Avatar package.
			elseif ($packageInfo['type'] == 'avatar')
				$context['available_avatars'][] = $packageInfo;
			// Language package.
			elseif ($packageInfo['type'] == 'language')
				$context['available_languages'][] = $packageInfo;
			// Other stuff.
			else
				$context['available_other'][] = $packageInfo;
		}
		closedir($dir);
	}
}

function PackageOptions()
{
	global $txt, $scripturl, $context, $sourcedir, $modSettings;

	if (isset($_POST['submit']))
	{
		updateSettings(array(
			'package_server' => $_POST['pack_server'],
			'package_port' => $_POST['pack_port'],
			'package_username' => $_POST['pack_user'],
			'package_make_backups' => !empty($_POST['package_make_backups'])
		));

		redirectexit('action=packages;sa=options');
	}

	if (preg_match('~^/home/([^/]+?)/public_html~', $_SERVER['DOCUMENT_ROOT'], $match))
		$default_username = $match[1];
	else
		$default_username = '';

	$context['linktree'][] = array(
		'url' => $scripturl . '?action=packages;sa=options',
		'name' => &$txt['package_install_options']
	);
	$context['page_title'] = $txt['package_install_options'];
	$context['sub_template'] = 'install_options';

	$context['package_ftp_server'] = isset($modSettings['package_server']) ? $modSettings['package_server'] : 'localhost';
	$context['package_ftp_port'] = isset($modSettings['package_port']) ? $modSettings['package_port'] : '21';
	$context['package_ftp_username'] = isset($modSettings['package_username']) ? $modSettings['package_username'] : $default_username;
	$context['package_make_backups'] = !empty($modSettings['package_make_backups']);
}

?>