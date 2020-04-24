<?php
// Version: 1.0; Packages

function template_main()
{
	global $context, $settings, $options;
}

function template_package_above()
{
	global $context, $settings, $options, $txt;
}

function template_package_below()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
		<br />
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
			<tr>
				<td class="catbg">', $txt['package2'], '</td>
			</tr><tr>
				<td class="windowbg2">
					<a href="', $scripturl, '?action=packages;sa=browse">[ ', $txt['package3'], ' ]</a><br />
					<a href="', $scripturl, '?action=packageget">[ ', $txt['package5'], ' ]</a><br />
					<a href="', $scripturl, '?action=packages;sa=installed">[ ', $txt['package6'], ' ]</a><br />
					<a href="', $scripturl, '?action=packages;sa=options">[ ', $txt['package_install_options'], ' ]</a><br />
				</td>
			</tr>
		</table>';
}

function template_view_package()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
			<tr class="titlebg">
				<td>', $txt['smf159b'], '</td>
			</tr><tr>
				<td class="windowbg2">';

	if ($context['is_installed'])
		echo '
					<b>', $txt['package_installed_warning1'], '</b><br />
					<br />
					', $txt['package_installed_warning2'], '<br />
					<br />';

	echo '
					', $txt['package_installed_warning3'], '
				</td>
			</tr>
		</table>
		<br />';

	if (isset($context['package_readme']))
		echo '
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
			<tr class="titlebg">
				<td>', $txt['package_install_readme'], '</td>
			</tr><tr>
				<td class="windowbg2">', $context['package_readme'], '</td>
			</tr>
		</table>
		<br />';

	echo '
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
			<tr class="titlebg">
				<td>', $context['page_title'], '</td>
			</tr>
			<tr>
				<td class="catbg">', $context['uninstalling'] ? $txt['package_uninstall_actions'] : $txt['package42'], ' ', $txt['package43'], ' ', $context['filename'], ':</td>
			</tr><tr>
				<td class="windowbg2">';

	if (empty($context['actions']))
		echo '
					<b>', $txt['package45'], '</b>';
	else
	{
		echo '
					', $txt['package44'], '
					<table border="0" cellpadding="1" cellspacing="0" width="100%" style="margin-top: 1ex;">
						<tr>
							<td width="30"></td>
							<td><b>', $txt['package_install_type'], '</b></td>
							<td width="30%"><b>', $txt['package_install_action'], '</b></td>
							<td width="45%"><b>', $txt['package_install_desc'], '</b></td>
						</tr>';

		$alternate = true;
		foreach ($context['actions'] as $i => $packageaction)
		{
			echo '
						<tr class="windowbg', $alternate ? '' : '2', '">
							<td style="padding-right: 2ex;">', $i + 1, '.</td>
							<td style="padding-right: 2ex;">', $packageaction['type'], '</td>
							<td style="padding-right: 2ex;">', $packageaction['action'], '</td>
							<td style="padding-right: 2ex;">', $packageaction['description'], '</td>
						</tr>';
			$alternate = !$alternate;
		}

		echo '
					</table>
					<br />';

		if (!$context['ftp_needed'])
			echo '
					<a href="', $scripturl, '?action=packages;sa=', $context['uninstalling'] ? 'uninstall2' : 'install2', ';package=', $context['filename'], '">[ ', $txt['smf154'], ' ]</a>';
		else
		{
			echo '
				</td>
			</tr>';

			// Yes, this looks strange, but it's here because of a typo near the 1.0 release.  It should be necessary, but we can't break all the language files for that this late in the game.
			echo '
			<tr>
				<td class="catbg">', isset($txt['package_ftp_necessary']) ? $txt['package_ftp_necessary'] : $txt['package_ftp_neccessary'], '</td>
			</tr><tr>
				<td class="windowbg2">
					', $txt['package_ftp_why'], '

					<form action="', $scripturl, '?action=packages;sa=', $context['uninstalling'] ? 'uninstall' : 'install', ';package=', $context['filename'], '" method="post">
						<table width="520" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-bottom: 1ex; margin-top: 2ex;">
							<tr>
								<td width="26%" valign="top" style="padding-top: 2px; padding-right: 2ex;"><label for="ftp_server">', $txt['package_ftp_server'], ':</label></td>
								<td style="padding-bottom: 1ex;">
									<div style="float: right; margin-right: 1px;"><label for="ftp_port" style="padding-top: 2px; padding-right: 2ex;">', $txt['package_ftp_port'], ':&nbsp;</label> <input type="text" size="3" name="ftp_port" id="ftp_port" value="', $context['package_ftp']['port'], '" /></div>
									<input type="text" size="30" name="ftp_server" id="ftp_server" value="', $context['package_ftp']['server'], '" style="width: 70%;" />
								</td>
							</tr><tr>
								<td width="26%" valign="top" style="padding-top: 2px; padding-right: 2ex;"><label for="ftp_username">', $txt['package_ftp_username'], ':</label></td>
								<td style="padding-bottom: 1ex;">
									<input type="text" size="50" name="ftp_username" id="ftp_username" value="', $context['package_ftp']['username'], '" style="width: 99%;" />
								</td>
							</tr><tr>
								<td width="26%" valign="top" style="padding-top: 2px; padding-right: 2ex;"><label for="ftp_password">', $txt['package_ftp_password'], ':</label></td>
								<td style="padding-bottom: 1ex;">
									<input type="password" size="50" name="ftp_password" id="ftp_password" style="width: 99%;" />
								</td>
							</tr><tr>
								<td width="26%" valign="top" style="padding-top: 2px; padding-right: 2ex;"><label for="ftp_path">', $txt['package_ftp_path'], ':</label></td>
								<td style="padding-bottom: 1ex;">
									<input type="text" size="50" name="ftp_path" id="ftp_path" value="', $context['package_ftp']['path'], '" style="width: 99%;" />
								</td>
							</tr>
						</table>
						<div align="right" style="margin: 1ex;"><input type="submit" value="', $txt['smf154'], '" /></div>
					</form>';
		}

		echo '
				</td>
			</tr>';
	}

	echo '
		</table>';
}

function template_extract_package()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
			<tr class="titlebg">
				<td>', $context['page_title'], '</td>
			</tr>
			<tr>
				<td class="catbg">', $txt['package_installed_extract'], '</td>
			</tr><tr>
				<td class="windowbg2" width="100%">';

	if ($context['uninstalling'])
		echo '
					', $txt['package_uninstall_done'];
	elseif ($context['install_finished'])
	{
		if ($context['extract_type'] == 'avatar')
			echo '
					', $txt['package39'];
		elseif ($context['extract_type'] == 'language')
			echo '
					', $txt['package41'];
		else
			echo '
					', $txt['package_installed_done'];
	}
	else
		echo '
					', $txt['package45'];

	echo '
				</td>
			</tr>
		</table>';
}

function template_list()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
			<tr class="titlebg">
				<td>', $context['page_title'], '</td>
			</tr>
			<tr>
				<td class="catbg">', $txt['smf181'], ' ', $context['filename'], ':</td>
			</tr><tr>
				<td class="windowbg2" width="100%">
					<ol>';

	foreach ($context['files'] as $fileinfo)
		echo '
						<li><a href="', $scripturl, '?action=packages;sa=examine;package=', $context['filename'], ';file=', $fileinfo['filename'], '" title="', $txt[305], '">', $fileinfo['filename'], '</a> (', $fileinfo['size'], ' ', $txt['package_bytes'], ')</li>';

	echo '
					</ol>
					<a href="', $scripturl, '?action=packages">[ ', $txt[193], ' ]</a>
				</td>
			</tr>
		</table>';
}

function template_examine()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor" style="table-layout: fixed;">
			<tr class="titlebg">
				<td>', $context['page_title'], '</td>
			</tr>
			<tr>
				<td class="catbg">', $txt['package_file_contents'], ' ', $context['filename'], ':</td>
			</tr><tr>
				<td class="windowbg2" style="width: 100%;">
					<pre style="overflow: auto; width: 100%; padding-bottom: 1ex;">', $context['filedata'], '</pre>

					<a href="', $scripturl, '?action=packages;sa=list;package=', $context['package'], '">[ ', $txt['package14'], ' ]</a>
				</td>
			</tr>
		</table>';
}

function template_view_installed()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
			<tr class="titlebg">
				<td>' . $context['page_title'] . '</td>
			</tr>
			<tr>
				<td class="catbg">' . $txt['package6'] . '</td>
			</tr><tr>
				<td class="windowbg2">';

	if (empty($context['installed_mods']))
	{
		echo '
					<table border="0" cellpadding="1" cellspacing="0" width="100%">
						<tr>
							<td style="padding-bottom: 1ex;">', $txt['smf189b'], '</td>
						</tr>
					</table>';
	}
	else
	{
		echo '
					<table border="0" cellpadding="1" cellspacing="0" width="100%">
						<tr>
							<td>&nbsp;&nbsp;</td>
							<td>', $txt['pacman2'], '</td>
							<td>', $txt['pacman3'], '</td>
						</tr>';

		foreach ($context['installed_mods'] as $i => $file)
			echo '
						<tr>
							<td>', ++$i, '.</td>
							<td>', $file['name'], '</td>
							<td>', $file['version'], '</td>
							<td align="right"><a href="', $scripturl, '?action=packages;sa=uninstall;package=', $file['filename'], '">[ ', $txt['smf198b'], ' ]</a></td>
						</tr>';

		echo '
					</table>
					<br />
					<a href="', $scripturl, '?action=packages;sa=flush">[ ', $txt['smf198d'], ' ]</a>';
	}

	echo '
				</td>
			</tr>
		</table>';
}

function template_browse()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<table width="100%" cellspacing="0" cellpadding="4" border="0" class="tborder">
			<tr class="titlebg">
				<td><a href="', $scripturl, '?action=helpadmin;help=latest_packages" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt[119], '" border="0" align="top" /></a> ', $txt['packages_latest'], '</td>
			</tr>
			<tr>
				<td class="windowbg2" id="packagesLatest">', $txt['packages_latest_fetch'], '</td>
			</tr>
		</table>
		<script language="JavaScript" type="text/javascript"><!--
			window.smfForum_scripturl = "', $scripturl, '";
			window.smfForum_sessionid = "', $context['session_id'], '";';

	// Make a list of already installed mods so nothing is listed twice ;).
	echo '
			window.smfInstalledPackages = ["', implode('", "', $context['installed_mods']), '"];
			window.smfVersion = "', $context['forum_version'], '";
		// --></script>
		<script language="JavaScript" type="text/javascript" src="http://www.simplemachines.org/smf/latest-packages.js?language=', $context['user']['language'], '"></script>
		<script language="JavaScript" type="text/javascript"><!--
			var tempOldOnload;

			function smfSetLatestPackages()
			{
				if (typeof(window.smfLatestPackages) != "undefined")
					setInnerHTML(document.getElementById("packagesLatest"), window.smfLatestPackages);

				if (tempOldOnload)
					tempOldOnload();
			}
		// --></script>';

	// Gotta love IE4, and its hatefulness...
	if ($context['browser']['is_ie4'])
		echo '
		<script language="JavaScript" type="text/javascript"><!--
			tempOldOnload = window.onload;
			window.onload = smfSetLatestPackages;
		// --></script>';
	else
		echo '
		<script language="JavaScript" type="text/javascript"><!--
			smfSetLatestPackages();
		// --></script>';

	echo '
		<br />

		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
			<tr class="titlebg">
				<td>', $context['page_title'], '</td>
			</tr>';

	if (!empty($context['available_mods']))
	{
		echo '
			<tr>
				<td class="catbg">', $txt['package7'], '</td>
			</tr><tr>
				<td class="windowbg2">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">
						<tr>
							<td width="1%">&nbsp;&nbsp;</td>
							<td width="25%">', $txt['pacman2'], '</td>
							<td width="25%">', $txt['pacman3'], '</td>
							<td width="49%">&nbsp;&nbsp;</td>
						</tr>';

		foreach ($context['available_mods'] as $i => $package)
		{
			echo '
						<tr>
							<td>', ++$i, '.</td>
							<td>', $package['name'], '</td>
							<td>
								', $package['version'];

			if ($package['is_installed'] && !$package['is_newer'])
				echo '
								<img src="', $settings['images_url'], '/icons/package_', $package['is_current'] ? 'installed' : 'old', '.gif" alt="" width="12" height="11" border="0" align="middle" style="margin-left: 2ex;" />';

			echo '
							</td>
							<td align="right">';

	if ($package['can_uninstall'])
		echo '
								<a href="', $scripturl, '?action=packages;sa=uninstall;package=', $package['filename'], '">[ ', $txt['smf198b'], ' ]</a>';
	elseif ($package['can_upgrade'])
		echo '
								<a href="', $scripturl, '?action=packages;sa=install;package=', $package['filename'], '">[ ', $txt['package_upgrade'], ' ]</a>';
	elseif ($package['can_install'])
		echo '
								<a href="', $scripturl, '?action=packages;sa=install;package=', $package['filename'], '">[ ', $txt['package11'], ' ]</a>';

	echo '
								<a href="', $scripturl, '?action=packages;sa=list;package=', $package['filename'], '">[ ', $txt['package14'], ' ]</a>
								<a href="', $scripturl, '?action=packages;sa=remove;package=', $package['filename'], '"', $package['is_installed'] && $package['is_current'] ? ' onclick="return confirm(\'' . $txt['package_delete_bad'] . '\');"' : '', '>[ ', $txt['package52'], ' ]</a>
							</td>
						</tr>';
		}

		echo '
					</table>
				</td>
			</tr>';
	}

	if (!empty($context['available_avatars']))
	{
		echo '
			<tr>
				<td class="catbg">', $txt['package8'], '</td>
			</tr><tr>
				<td class="windowbg2">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">
						<tr>
							<td width="1%">&nbsp;&nbsp;</td>
							<td width="25%">', $txt['pacman2'], '</td>
							<td width="25%">', $txt['pacman3'], '</td>
							<td width="49%">&nbsp;&nbsp;</td>
						</tr>';

		foreach ($context['available_avatars'] as $i => $package)
		{
			echo '
						<tr>
							<td>', ++$i, '.</td>
							<td>', $package['name'], '</td>
							<td>', $package['version'];

			if ($package['is_installed'] && !$package['is_newer'])
				echo '
								<img src="', $settings['images_url'], '/icons/package_', $package['is_current'] ? 'installed' : 'old', '.gif" alt="" width="12" height="11" border="0" align="middle" style="margin-left: 2ex;" />';

			echo '
							</td>
							<td align="right">
								<a href="', $scripturl, '?action=packages;sa=install;package=', $package['filename'], '">[ ', $txt['package11'], ' ]</a>
								<a href="', $scripturl, '?action=packages;sa=list;package=', $package['filename'], '">[ ', $txt['package14'], ' ]</a>
								<a href="', $scripturl, '?action=packages;sa=remove;package=', $package['filename'], '">[ ', $txt['package52'], ' ]</a>
							</td>
						</tr>';
		}

		echo '
					</table>
				</td>
			</tr>';
	}

	if (!empty($context['available_languages']))
	{
		echo '
			<tr>
				<td class="catbg">' . $txt['package9'] . '</td>
			</tr><tr>
				<td class="windowbg2">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">
						<tr>
							<td width="1%">&nbsp;&nbsp;</td>
							<td width="25%">' . $txt['pacman2'] . '</td>
							<td width="25%">' . $txt['pacman3'] . '</td>
							<td width="49%">&nbsp;&nbsp;</td>
						</tr>';

		foreach ($context['available_languages'] as $i => $package)
		{
			echo '
						<tr>
							<td>' . ++$i . '.</td>
							<td>' . $package['name'] . '</td>
							<td>' . $package['version'];

			if ($package['is_installed'] && !$package['is_newer'])
				echo '
								<img src="', $settings['images_url'], '/icons/package_', $package['is_current'] ? 'installed' : 'old', '.gif" alt="" width="12" height="11" border="0" align="middle" style="margin-left: 2ex;" />';

			echo '
							</td>
							<td align="right">
								<a href="', $scripturl, '?action=packages;sa=install;package=', $package['filename'], '">[ ', $txt['package11'], ' ]</a>
								<a href="', $scripturl, '?action=packages;sa=list;package=', $package['filename'], '">[ ', $txt['package14'], ' ]</a>
								<a href="', $scripturl, '?action=packages;sa=remove;package=', $package['filename'], '">[ ', $txt['package52'], ' ]</a>
							</td>
						</tr>';
		}

		echo '
					</table>
				</td>
			</tr>';
	}

	if (!empty($context['available_other']))
	{
		echo '
			<tr>
				<td class="catbg">' . $txt['package10'] . '</td>
			</tr><tr>
				<td class="windowbg2">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">
						<tr>
							<td width="1%">&nbsp;&nbsp;</td>
							<td width="25%">' . $txt['pacman2'] . '</td>
							<td width="25%">' . $txt['pacman3'] . '</td>
							<td width="49%">&nbsp;&nbsp;</td>
						</tr>';

		foreach ($context['available_other'] as $i => $package)
		{
			echo '
						<tr>
							<td>' . ++$i . '.</td>
							<td>' . $package['name'] . '</td>
							<td>' . $package['version'];

			if ($package['is_installed'] && !$package['is_newer'])
				echo '
								<img src="', $settings['images_url'], '/icons/package_', $package['is_current'] ? 'installed' : 'old', '.gif" alt="" width="12" height="11" border="0" align="middle" style="margin-left: 2ex;" />';

			echo '
							</td>
							<td align="right">
								<a href="', $scripturl, '?action=packages;sa=install;package=', $package['filename'], '">[ ', $txt['package11'], ' ]</a>
								<a href="', $scripturl, '?action=packages;sa=list;package=', $package['filename'], '">[ ', $txt['package14'], ' ]</a>
								<a href="', $scripturl, '?action=packages;sa=remove;package=', $package['filename'], '"', $package['is_installed'] ? ' onclick="return confirm(\'' . $txt['package_delete_bad'] . '\');"' : '', '>[ ', $txt['package52'], ' ]</a>
							</td>
						</tr>';
		}

		echo '
					</table>
				</td>
			</tr>';
	}

	if (empty($context['available_mods']) && empty($context['available_avatars']) && empty($context['available_languages']) && empty($context['available_other']))
		echo '
			<tr>
				<td class="windowbg2">', $txt['smf189'], '</td>
			</tr>';

	echo '
		</table>
		<table border="0" width="100%" cellspacing="1" cellpadding="4">
			<tr>
				<td class="smalltext">
					', $txt['package_installed_key'], '
					<img src="', $settings['images_url'], '/icons/package_installed.gif" alt="" width="12" height="11" align="middle" style="margin-left: 1ex;" /> ', $txt['package_installed_current'], '
					<img src="', $settings['images_url'], '/icons/package_old.gif" alt="" width="12" height="11" align="middle" style="margin-left: 2ex;" /> ', $txt['package_installed_old'], '
				</td>
			</tr>
		</table>';
}

function template_servers()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
			<tr class="titlebg">
				<td>', $context['page_title'], '</td>
			</tr>';

	if ($context['package_download_broken'])
	{
		// While this may look strange, it's here because of a typo near the 1.0 release.  It should be necessary, but we can't break all the language files for that this late in the game.
		echo '
			<tr>
				<td class="catbg">', isset($txt['package_ftp_necessary']) ? $txt['package_ftp_necessary'] : $txt['package_ftp_neccessary'], '</td>
			</tr><tr>
				<td class="windowbg2">
					', $txt['package_ftp_why_download'], '

					<form action="', $scripturl, '?action=packageget" method="post">
						<table width="520" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-bottom: 1ex; margin-top: 2ex;">
							<tr>
								<td width="26%" valign="top" style="padding-top: 2px; padding-right: 2ex;"><label for="ftp_server">', $txt['package_ftp_server'], ':</label></td>
								<td style="padding-bottom: 1ex;">
									<div style="float: right; margin-right: 1px;"><label for="ftp_port" style="padding-top: 2px; padding-right: 2ex;">', $txt['package_ftp_port'], ':&nbsp;</label> <input type="text" size="3" name="ftp_port" id="ftp_port" value="', $context['package_ftp']['port'], '" /></div>
									<input type="text" size="30" name="ftp_server" id="ftp_server" value="', $context['package_ftp']['server'], '" style="width: 70%;" />
								</td>
							</tr><tr>
								<td width="26%" valign="top" style="padding-top: 2px; padding-right: 2ex;"><label for="ftp_username">', $txt['package_ftp_username'], ':</label></td>
								<td style="padding-bottom: 1ex;">
									<input type="text" size="50" name="ftp_username" id="ftp_username" value="', $context['package_ftp']['username'], '" style="width: 99%;" />
								</td>
							</tr><tr>
								<td width="26%" valign="top" style="padding-top: 2px; padding-right: 2ex;"><label for="ftp_password">', $txt['package_ftp_password'], ':</label></td>
								<td style="padding-bottom: 1ex;">
									<input type="password" size="50" name="ftp_password" id="ftp_password" style="width: 99%;" />
								</td>
							</tr><tr>
								<td width="26%" valign="top" style="padding-top: 2px; padding-right: 2ex;"><label for="ftp_path">', $txt['package_ftp_path'], ':</label></td>
								<td style="padding-bottom: 1ex;">
									<input type="text" size="50" name="ftp_path" id="ftp_path" value="', $context['package_ftp']['path'], '" style="width: 99%;" />
								</td>
							</tr>
						</table>
						<div align="right" style="margin-right: 1ex;"><input type="submit" value="', $txt['smf154'], '" /></div>
					</form>
				</td>
			</tr>';
	}

	echo '
			<tr>
				<td class="catbg">' . $txt['smf183'] . '</td>
			</tr><tr>
				<td class="windowbg2">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">';
	foreach ($context['servers'] as $server)
		echo '
						<tr>
							<td>
								' . $server['name'] . '
							</td>
							<td>
								<a href="' . $scripturl . '?action=pgbrowse;server=' . $server['id'] . '">[ ' . $txt['smf184'] . ' ]</a>
							</td>
							<td>
								<a href="' . $scripturl . '?action=pgremove;server=' . $server['id'] . '">[ ' . $txt['smf138'] . ' ]</a>
							</td>
						</tr>';
	echo '
					</table>
					<br />
				</td>
			</tr><tr>
				<td class="catbg">' . $txt['smf185'] . '</td>
			</tr><tr>
				<td class="windowbg2">
					<form action="' . $scripturl . '?action=pgadd" method="post">
						<table border="0" cellspacing="0" cellpadding="4">
							<tr>
								<td valign="top"><b>' . $txt['smf186'] . ':</b></td>
								<td valign="top"><input type="text" name="servername" size="40" value="SMF" /></td>
							</tr><tr>
								<td valign="top"><b>' . $txt['smf187'] . ':</b></td>
								<td valign="top"><input type="text" name="serverurl" size="50" value="http://" /></td>
							</tr><tr>
								<td colspan="2"><input type="submit" value="' . $txt['smf185'] . '" /></td>
							</tr>
						</table>
						<input type="hidden" name="sc" value="' . $context['session_id'] . '" />
					</form>
				</td>
			</tr>
		</table>
		<br />
		<table width="100%" cellpadding="4" cellspacing="1" border="0" class="bordercolor">
			<tr class="titlebg">
				<td>' . $txt['package_upload_title'] . '</td>
			</tr><tr>
				<td class="windowbg2" style="padding: 8px;">
					<form action="' . $scripturl . '?action=pgupload" method="post" enctype="multipart/form-data" style="margin-bottom: 0;">
						<b>' . $txt['package_upload_select'] . ':</b> <input type="file" name="package" size="38" />
						<div style="margin: 1ex;" align="right"><input type="submit" value="' . $txt['package_upload'] . '" /></div>
						<input type="hidden" name="sc" value="' . $context['session_id'] . '" />
					</form>
				</td>
			</tr>
		</table>';
}

function template_package_list()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
			<tr class="titlebg">
				<td>' . $context['page_title'] . '</td>
			</tr>
			<tr>
				<td width="100%" align="left" valign="middle" class="windowbg2">';

	// No packages, as yet.
	if (empty($context['package_list']))
		echo '
					<ul>
						<li>', $txt['smf189'], '</li>
					</ul>';
	// List out the packages...
	else
	{
		foreach ($context['package_list'] as $package)
		{
			// A title.
			if ($package['is_title'])
				echo '
					<b style="font-size: larger;">', $package['name'], '</b><br /><br />';
			// A heading.
			elseif ($package['is_heading'])
				echo '
					<b style="font-size: larger;">', $package['name'], '</b><br /><br />';
			// Textual message.  Could be empty just for a blank line.
			elseif ($package['is_text'])
				echo '
					', $package['name'], '<br /><br />';
			// This is supposed to be a rule..
			elseif ($package['is_line'])
				echo '
					<hr width="100%" />';
			// A remote link.
			elseif ($package['is_remote'])
				echo '
					<b>', $package['link'], '</b><br /><br />';
			// Otherwise, it's a package.
			else
			{
				// 1. Some mod [ Download ].
				echo '
					', $package['count'], '. ', $package['can_install'] ? '<b>' : '', $package['name'], $package['can_install'] ? '</b>' : '', ' <a href="', $package['download']['href'], '">[ ', $txt['smf190'], ' ]</a>';

				// Mark as installed and current?
				if ($package['is_installed'] && !$package['is_newer'])
					echo '<img src="', $settings['images_url'], '/icons/package_', $package['is_current'] ? 'installed' : 'old', '.gif" width="12" height="11" border="0" align="middle" style="margin-left: 2ex;" alt="', $package['is_current'] ? $txt['package_installed_current'] : $txt['package_installed_old'], '" />';

				// Show the mod type?
				if ($package['type'] != '')
					echo '<br />
					', $txt['package24'], ':&nbsp; ', ucwords(strtolower($package['type']));
				// Show the version number?
				if ($package['version'] != '')
					echo '<br />
					', $txt['pacman3'], ':&nbsp; ', $package['version'];
				// How 'bout the author?
				if ($package['author']['name'] != '')
					echo '<br />
					', $txt['pacman4'], ':&nbsp; ', $package['author']['link'];
				// The homepage....
				if ($package['author']['website']['link'] != '')
					echo '<br />
					', $txt['pacman6'], ':&nbsp; ', $package['author']['website']['link'];

				// Desciption: bleh bleh!
				// Location of file: http://someplace/.
				echo '<br />
					', $txt['pacman9'], ':&nbsp; ', $package['description'], '<br />
					', $txt['pacman10'], ':&nbsp; <a href="', $package['href'], '">', $package['href'], '</a><br />
					<br />';
			}
		}
		echo '
					<br />';
	}

	echo '
					</td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="1" cellpadding="4">
				<tr>
					<td class="smalltext">
						', $txt['package_installed_key'], '
						<img src="', $settings['images_url'], '/icons/package_installed.gif" alt="" width="12" height="11" align="middle" style="margin-left: 1ex;" /> ', $txt['package_installed_current'], '
						<img src="', $settings['images_url'], '/icons/package_old.gif" alt="" width="12" height="11" align="middle" style="margin-left: 2ex;" /> ', $txt['package_installed_old'], '
					</td>
				</tr>
			</table>';
}

function template_downloaded()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
			<tr class="titlebg">
				<td>' . $context['page_title'] . '</td>
			</tr>
			<tr>
				<td width="100%" align="left" valign="middle" class="windowbg2">
					' . (!isset($context['server']) ? $txt['package_uploaded_successfully'] : $txt['smf193']) . '<br /><br />
					<table border="0" cellpadding="1" cellspacing="0" width="100%">
						<tr>
							<td valign="middle">' . $context['package']['name'] . '</td>
							<td align="right" valign="middle">
								' . $context['package']['install']['link'] . '
								' . $context['package']['list_files']['link'] . '
							</td>
						</tr>
					</table>
					<br />
					<a href="' . $scripturl . '?action=' . (isset($context['server']) ? 'pgbrowse;server=' . $context['server'] : 'packageget') . '">[ ' . $txt[193] . ' ]</a>
				</td>
			</tr>
		</table>';
}

function template_install_options()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
			<tr class="titlebg">
				<td>', $context['page_title'], '</td>
			</tr>
			<tr class="windowbg" style="padding: 1ex;">
				<td class="smalltext">', $txt['package_install_options_ftp_why'], '</td>
			</tr>
			<tr class="windowbg2">
				<td>
					<form action="', $scripturl, '?action=packages;sa=options" method="post">
						<div style="margin-top: 1ex;"><label for="pack_server" style="padding: 2px 0 0 4pt; float: left; width: 20ex; font-weight: bold;">', $txt['package_install_options_ftp_server'], ':</label> <input type="text" name="pack_server" id="pack_server" value="', $context['package_ftp_server'], '" /> <label for="pack_port" style="padding-left: 4pt; font-weight: bold;">', $txt['package_install_options_ftp_port'], ':</label> <input type="text" name="pack_port" id="pack_port" size="3" value="', $context['package_ftp_port'], '" /></div>
						<div style="margin-top: 1ex;"><label for="pack_user" style="padding: 2px 0 0 4pt; float: left; width: 20ex; font-weight: bold;">', $txt['package_install_options_ftp_user'], ':</label> <input type="text" name="pack_user" id="pack_user" value="', $context['package_ftp_username'], '" /></div>
						<br />

						<label for="package_make_backups"><input type="checkbox" name="package_make_backups" id="package_make_backups" value="1" class="check"', $context['package_make_backups'] ? ' checked="checked"' : '', ' /> ', $txt['package_install_options_make_backups'], '</label><br />
						<div align="center" style="padding-top: 2ex; padding-bottom: 1ex;"><input type="submit" name="submit" value="', $txt[10], '" /></div>
						<input type="hidden" name="sc" value="', $context['session_id'], '" />
					</form>
				</td>
			</tr>
		</table>';
}

?>