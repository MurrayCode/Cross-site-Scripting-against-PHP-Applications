<?php
// Version: 1.0; Admin

// This contains the html for the side bar of the admin center, which is used for all admin pages.
function template_admin_above()
{
	global $context, $settings, $options, $scripturl;

	// This is the main table - we need it so we can keep the content to the right of it.
	echo '
		<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding-top: 1ex;"><tr>
			<td width="180" valign="top" style="width: 26ex; padding-right: 10px; padding-bottom: 10px;">
				<table width="100%" cellpadding="4" cellspacing="1" border="0" class="bordercolor">';

	// For every section that appears on the sidebar...
	foreach ($context['admin_areas'] as $section)
	{
		// Show the section header - and pump up the line spacing for readability.
		echo '
					<tr>
						<td class="catbg">', $section['title'], '</td>
					</tr>
					<tr class="windowbg2">
						<td class="smalltext" style="line-height: 1.3; padding-bottom: 3ex;">';

		// For every area of this section show a link to that area (bold if it's currently selected.)
		foreach ($section['areas'] as $i => $area)
		{
			// Is this the current area, or just some area?
			if ($i == $context['admin_area'])
				echo '
							<b>', $area, '</b><br />';
			else
				echo '
							', $area, '<br />';
		}

		echo '
						</td>
					</tr>';
	}

	// This is where the actual "main content" area for the admin section starts.
	echo '
				</table>
			</td>
			<td valign="top">';
}

// Part of the admin layer - used with admin_above to close the table started in it.
function template_admin_below()
{
	global $context, $settings, $options;

	echo '
			</td>
		</tr>
	</table>';
}

// This is the administration center home.
function template_admin()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Welcome message for the admin.
	echo '
		<table width="100%" cellpadding="5" cellspacing="1" border="0" class="bordercolor">
			<tr class="titlebg">
				<td align="center" colspan="2" class="largetext">', $txt[208], '</td>
			</tr><tr>
				<td class="windowbg" valign="top" style="padding: 7px;">
					<b>', $txt['hello_guest'], ' ', $context['user']['name'], '!</b>
					<div style="font-size: 0.85em; padding-top: 1ex;">', $txt[644], '</div>
				</td>
			</tr>
		</table>';

	echo '
		<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 1.5ex;"><tr>';

	// Display the "live news" from simplemachines.org.
	echo '
			<td valign="top">
				<table width="100%" cellpadding="5" cellspacing="1" border="0" class="bordercolor">
					<tr>
						<td class="catbg">
							<a href="', $scripturl, '?action=helpadmin;help=13" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt[119], '" border="0" align="top" /></a> ', $txt['smf217'], '
						</td>
					</tr><tr>
						<td class="windowbg2" valign="top" style="height: 18ex; padding: 0;">
							<div id="smfAnnouncements" style="height: 18ex; overflow: auto; padding-right: 1ex;"><div style="margin: 4px; font-size: 0.85em;">', $txt['lfyi'], '</div></div>
						</td>
					</tr>
				</table>
			</td>
			<td style="width: 1ex;">&nbsp;</td>';

	// Show the user version information from their server.
	echo '
			<td valign="top" style="width: 40%;">
				<table width="100%" cellpadding="5" cellspacing="1" border="0" class="bordercolor" id="supportVersionsTable">
					<tr>
						<td class="catbg"><a href="', $scripturl, '?action=admin;credits">', $txt['support_title'], '</a></td>
					</tr><tr>
						<td class="windowbg2" valign="top" style="height: 18ex;">
							<b>', $txt['support_versions'], ':</b><br />
							', $txt['support_versions_forum'], ':
							<i id="yourVersion" style="white-space: nowrap;">', $context['forum_version'], '</i><br />
							', $txt['support_versions_current'], ':
							<i id="smfVersion" style="white-space: nowrap;">??</i><br />
							', $context['can_admin'] ? '<a href="' . $scripturl . '?action=detailedversion">' . $txt['dvc_more'] . '</a>' : '', '<br />';

	// Display all the members who can administrate the forum.
	echo '
							<br />
							<b>', $txt[684] . ':</b>
							', implode(', ', $context['administrators']), '
						</td>
					</tr>
				</table>
			</td>
		</tr></table>';

	echo '
		<table width="100%" cellpadding="5" cellspacing="0" border="0" class="tborder" style="margin-top: 1.5ex;">
			<tr valign="top" class="windowbg2">';

	$row = false;
	foreach ($context['quick_admin_tasks'] as $task)
	{
		echo '
				<td style="padding-bottom: 2ex;" width="50%">
					<div style="font-weight: bold; font-size: 1.1em;">', $task['link'], '</div>
					', $task['description'], '
				</td>';

		if ($row && !$task['is_last'])
			echo '
			</tr>
			<tr valign="top" class="windowbg2">';

		$row = !$row;
	}

	echo '
			</tr>
		</table>';

	// The below functions include all the scripts needed from the simplemachines.org site.  The language and format are passed for internationalization.
	echo '
		<script language="JavaScript" type="text/javascript" src="http://www.simplemachines.org/smf/current-version.js?version=', $context['forum_version'], '"></script>
		<script language="JavaScript" type="text/javascript" src="http://www.simplemachines.org/smf/latest-news.js?language=', $context['user']['language'], '&amp;format=', $context['time_format'], '"></script>';

	// This sets the announcements and current versions themselves ;).
	echo '
		<script language="JavaScript" type="text/javascript"><!--
			function smfSetAnnouncements()
			{
				if (typeof(window.smfAnnouncements) == "undefined" || typeof(window.smfAnnouncements.length) == "undefined")
					return;

				var str = "<div style=\"margin: 4px; font-size: 0.85em;\">";

				for (var i = 0; i < window.smfAnnouncements.length; i++)
				{
					str += "\n	<div style=\"padding-bottom: 2px;\"><a hre" + "f=\"" + window.smfAnnouncements[i].href + "\">" + window.smfAnnouncements[i].subject + "</a> ', $txt[30], ' " + window.smfAnnouncements[i].time + "</div>";
					str += "\n	<div style=\"padding-left: 2ex; margin-bottom: 1.5ex; border-top: 1px dashed;\">"
					str += "\n		" + window.smfAnnouncements[i].message;
					str += "\n	</div>";
				}

				setInnerHTML(document.getElementById("smfAnnouncements"), str + "</div>");
			}

			function smfAnnouncementsFixHeight()
			{
				if (document.getElementById("supportVersionsTable").offsetHeight)
					document.getElementById("smfAnnouncements").style.height = (document.getElementById("supportVersionsTable").offsetHeight - 10) + "px";
			}

			function smfCurrentVersion()
			{
				var smfVer, yourVer;

				if (typeof(window.smfVersion) != "string")
					return;

				smfVer = document.getElementById("smfVersion");
				yourVer = document.getElementById("yourVersion");

				setInnerHTML(smfVer, window.smfVersion);

				var currentVersion = getInnerHTML(yourVer);
				if (currentVersion != window.smfVersion)
					setInnerHTML(yourVer, "<span style=\"color: #FF0000;\">" + currentVersion + "</span>");
			}';

	// IE 4 won't like it if you try to change the innerHTML before load...
	echo '

			var oldonload;
			if (typeof(window.onload) != "undefined")
				oldonload = window.onload;

			window.onload = function ()
			{
				smfSetAnnouncements();
				smfCurrentVersion();';

	if ($context['browser']['is_ie'] && !$context['browser']['is_ie4'])
		echo '
				if (typeof(smf_codeFix) != "undefined")
					window.detachEvent("onload", smf_codeFix);
				window.attachEvent("onload",
					function ()
					{
						with (document.all.supportVersionsTable)
							style.height = parentNode.offsetHeight;
					}
				);
				if (typeof(smf_codeFix) != "undefined")
					window.attachEvent("onload", smf_codeFix);';

	echo '

				if (oldonload)
					eval(oldonload);
			}
		// --></script>';
}

// Show some support information and credits to those who helped make this.
function template_credits()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Show the user version information from their server.
	echo '
		<table width="100%" cellpadding="5" cellspacing="0" border="0" class="tborder">
			<tr class="titlebg">
				<td>', $txt['support_title'], '</td>
			</tr><tr>
				<td class="windowbg2">
					<b>', $txt['support_versions'], ':</b><br />
					', $txt['support_versions_forum'], ':
					<i id="yourVersion" style="white-space: nowrap;">', $context['forum_version'], '</i>', $context['can_admin'] ? ' <a href="' . $scripturl . '?action=detailedversion">' . $txt['dvc_more'] . '</a>' : '', '<br />
					', $txt['support_versions_current'], ':
					<i id="smfVersion" style="white-space: nowrap;">??</i><br />';

	// Display all the variables we have server information for.
	foreach ($context['current_versions'] as $version)
		echo '
					', $version['title'], ':
					<i>', $version['version'], '</i><br />';

	echo '

				</td>
			</tr>
		</table>';

	// Display latest support questions from simplemachines.org.
	echo '
		<table width="100%" cellpadding="5" cellspacing="0" border="0" class="tborder" style="margin-top: 2ex;">
			<tr class="titlebg">
				<td><a href="', $scripturl, '?action=helpadmin;help=latest_support" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt[119], '" border="0" align="top" /></a> ', $txt['support_latest'], '</td>
			</tr><tr>
				<td class="windowbg2">
					<div id="latestSupport">', $txt['support_latest_fetch'], '</div>
				</td>
			</tr>
		</table>';

	// The most important part - the credits :P.
	echo '
		<table width="100%" cellpadding="5" cellspacing="0" border="0" class="tborder" style="margin-top: 2ex;">
			<tr class="titlebg">
				<td>', $txt[571], '</td>
			</tr><tr>
				<td class="windowbg2"><span style="font-size: 0.85em;" id="credits">', $context['credits'], '</span></td>
			</tr>
		</table>';

	// This makes all the support information available to the support script...
	echo '
		<script language="JavaScript" type="text/javascript"><!--
			var smfSupportVersions = {};

			smfSupportVersions.forum = "', $context['forum_version'], '";';

	// Don't worry, none of this is logged, it's just used to give information that might be of use.
	foreach ($context['current_versions'] as $variable => $version)
		echo '
			smfSupportVersions.', $variable, ' = "', $version['version'], '";';

	// Now we just have to include the script and wait ;).
	echo '
		// --></script>
		<script language="JavaScript" type="text/javascript" src="http://www.simplemachines.org/smf/current-version.js?version=', $context['forum_version'], '"></script>
		<script language="JavaScript" type="text/javascript" src="http://www.simplemachines.org/smf/latest-news.js?language=', $context['user']['language'], '&amp;format=', $context['time_format'], '"></script>
		<script language="JavaScript" type="text/javascript" src="http://www.simplemachines.org/smf/latest-support.js?language=', $context['user']['language'], '"></script>';

	// This setsthe latest support stuff.
	echo '
		<script language="JavaScript" type="text/javascript"><!--
			function smfSetLatestSupport()
			{
				if (window.smfLatestSupport)
					setInnerHTML(document.getElementById("latestSupport"), window.smfLatestSupport);
			}

			function smfCurrentVersion()
			{
				var smfVer, yourVer;

				if (!window.smfVersion)
					return;

				smfVer = document.getElementById("smfVersion");
				yourVer = document.getElementById("yourVersion");

				setInnerHTML(smfVer, window.smfVersion);

				var currentVersion = getInnerHTML(yourVer);
				if (currentVersion != window.smfVersion)
					setInnerHTML(yourVer, "<span style=\"color: #FF0000;\">" + currentVersion + "</span>");
			}';

	// IE 4 is rather annoying, this wouldn't be necessary...
	echo '

			var oldonload;
			if (typeof(window.onload) != "undefined")
				oldonload = window.onload;

			window.onload = function ()
			{
				smfSetLatestSupport();
				smfCurrentVersion()

				if (oldonload)
					eval(oldonload);
			}
		// --></script>';
}

// Form for editing current news on the site.
function template_edit_news()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
		<form action="', $scripturl, '?action=editnews" method="post" name="postmodify">
			<table width="85%" cellpadding="3" cellspacing="0" border="0" align="center" class="tborder">
				<tr class="titlebg">
					<td colspan="3">
						<a href="', $scripturl, '?action=helpadmin;help=2" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt[119], '" border="0" align="top" /></a> ', $txt[7], '
					</td>
				</tr><tr class="windowbg">
					<td colspan="3" class="smalltext" style="padding: 2ex;">', $txt[670], '</td>
				</tr><tr class="titlebg">
					<th width="50%"></th>
					<th align="left" width="45%">', $txt[507], '</th>
					<th align="center" width="5%"><input type="checkbox" class="check" onclick="invertAll(this, this.form);" /></th>
				</tr>';

	// Loop through all the current news items so you can edit/remove them.
	foreach ($context['admin_current_news'] as $admin_news)
		echo '
				<tr class="windowbg2">
					<td align="center">
						<div style="margin-bottom: 2ex;"><textarea rows="3" cols="65" name="news[]" style="width: 85%;">', $admin_news['unparsed'], '</textarea></div>
					</td><td align="left" valign="top">
						<div style="overflow: auto; width: 100%; height: 10ex;">', $admin_news['parsed'], '</div>
					</td><td align="center">
						<input type="checkbox" name="remove[]" value="', $admin_news['id'], '" class="check" />
					</td>
				</tr>';

	// This provides an empty text box to add a news item to the site.
	echo '
				<tr class="windowbg2">
					<td align="center">
						<script language="JavaScript" type="text/javascript"><!--
							document.write(\'<div id="moreNewsItems"></div><a href="#" onclick="addNewsItem(); return false;">', $txt['editnews_clickadd'], '</a><br />\');

							function addNewsItem()
							{
								setOuterHTML(document.getElementById("moreNewsItems"), \'<div style="margin-bottom: 2ex;"><textarea rows="3" cols="65" name="news[]" style="width: 85%;"></textarea></div><div id="moreNewsItems"></div>\');
							}
						// --></script>
						<noscript>
							<div style="margin-bottom: 2ex;"><textarea rows="3" cols="65" style="width: 85%;" name="news[]"></textarea></div>
						</noscript>
					</td>
					<td colspan="2" valign="bottom" align="right" style="padding: 1ex;">
						<input type="submit" value="', $txt[10], '" /> <input type="submit" name="delete_selection" value="', $txt['editnews_remove_selected'], '" onclick="return confirm(\'', $txt['editnews_remove_confirm'], '\');" />
					</td>
				</tr>
			</table>
			<input type="hidden" name="sc" value="', $context['session_id'], '" />
		</form>';
}

// Form for editing the agreement shown for people registering to the forum.
function template_edit_agreement()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Just a big box to edit the text file ;).
	echo '
		<form action="', $scripturl, '?action=editagreement" method="post">
			<table width="600" cellpadding="3" cellspacing="1" border="0" align="center" class="bordercolor">
				<tr class="titlebg">
					<td>
						<a href="', $scripturl, '?action=helpadmin;help=3" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt[119], '" border="0" align="top" /></a> ', $txt['smf11'], '
					</td>
				</tr><tr class="windowbg">
					<td class="smalltext" style="padding: 2ex;">';

	// Warning for if the file isn't writable.
	if (!empty($context['warning']))
		echo '
						<div style="color: red; font-weight: bold;">', $context['warning'], '</div>';

	echo '
						', $txt['smf12'], '
					</td>
				</tr><tr>
					<td class="windowbg2" align="center" style="padding-bottom: 1ex; padding-top: 2ex;">';

	// Show the actual agreement in an oversized text box.
	echo '
						<textarea cols="70" rows="20" name="agreement" style="width: 94%; margin-bottom: 1ex;">', $context['agreement'], '</textarea><br />
						<label for="requireAgreement"><input type="checkbox" name="requireAgreement" id="requireAgreement"', $context['require_agreement'] ? ' checked="checked"' : '', ' value="1" /> ', $txt[584], '.</label><br />
						<br />
						<input type="submit" value="', $txt[10], '" />
					</td>
				</tr>
			</table>
			<input type="hidden" name="sc" value="', $context['session_id'], '" />
		</form>';
}

// Displays information about file versions installed, and compares them to current version.
function template_view_versions()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
		<table width="94%" cellpadding="3" cellspacing="1" border="0" align="center" class="bordercolor">
			<tr class="titlebg">
				<td>', $txt[429], '</td>
			</tr><tr class="windowbg">
				<td class="smalltext" style="padding: 2ex;">', $txt['dvc1'], '</td>
			</tr><tr>
				<td class="windowbg2" style="padding: 1ex 0 1ex 0;">
					<table width="88%" cellpadding="2" cellspacing="0" border="0" align="center">
						<tr>
							<td width="50%"><b>', $txt[495], '</b></td><td width="25%"><b>', $txt['dvc_your'], '</b></td><td width="25%"><b>', $txt['dvc_current'], '</b></td>
						</tr>';

	// The current version of the core SMF package.
	echo '
						<tr>
							<td>', $txt[496], '</td><td><i id="yourSMF">', $context['forum_version'], '</i></td><td><i id="currentSMF">??</i></td>
						</tr>';

	// Now list all the source file versions, starting with the overall version (if all match!).
	echo '
						<tr>
							<td><a href="#" onclick="return swapOption(this, \'Sources\');">', $txt['dvc_sources'], '</a></td><td><i id="yourSources">??</i></td><td><i id="currentSources">??</i></td>
						</tr>
					</table>
					<table id="Sources" width="88%" cellpadding="2" cellspacing="0" border="0" align="center">';

	// Loop through every source file displaying its version - using javascript.
	foreach ($context['file_versions'] as $filename => $version)
		echo '
						<tr>
							<td width="50%" style="padding-left: 3ex;">', $filename, '</td><td width="25%"><i id="yourSources', $filename, '">', $version, '</i></td><td width="25%"><i id="currentSources', $filename, '">??</i></td>
						</tr>';

	// Default template files.
	echo '
					</table>
					<table width="88%" cellpadding="2" cellspacing="0" border="0" align="center">
						<tr>
							<td width="50%"><a href="#" onclick="return swapOption(this, \'Default\');">', $txt['dvc_default'], '</a></td><td width="25%"><i id="yourDefault">??</i></td><td width="25%"><i id="currentDefault">??</i></td>
						</tr>
					</table>
					<table id="Default" width="88%" cellpadding="2" cellspacing="0" border="0" align="center">';

	foreach ($context['default_template_versions'] as $filename => $version)
		echo '
						<tr>
							<td width="50%" style="padding-left: 3ex;">', $filename, '</td><td width="25%"><i id="yourDefault', $filename, '">', $version, '</i></td><td width="25%"><i id="currentDefault', $filename, '">??</i></td>
						</tr>';

	// Now the language files...
	echo '
					</table>
					<table width="88%" cellpadding="2" cellspacing="0" border="0" align="center">
						<tr>
							<td width="50%"><a href="#" onclick="return swapOption(this, \'Languages\');">', $txt['dvc_languages'], '</a></td><td width="25%"><i id="yourLanguages">??</i></td><td width="25%"><i id="currentLanguages">??</i></td>
						</tr>
					</table>
					<table id="Languages" width="88%" cellpadding="2" cellspacing="0" border="0" align="center">';

	foreach ($context['default_language_versions'] as $language => $files)
	{
		foreach ($files as $filename => $version)
			echo '
						<tr>
							<td width="50%" style="padding-left: 3ex;">', $filename, '.<i>', $language, '</i>.php</td><td width="25%"><i id="your', $filename, '.', $language, '">', $version, '</i></td><td width="25%"><i id="current', $filename, '.', $language, '">??</i></td>
						</tr>';
	}

	echo '
					</table>';

	// Finally, display the version information for the currently selected theme - if it is not the default one.
	if (!empty($context['template_versions']))
	{
		echo '
					<table width="88%" cellpadding="2" cellspacing="0" border="0" align="center">
						<tr>
							<td width="50%"><a href="#" onclick="return swapOption(this, \'Templates\');">', $txt['dvc_templates'], '</a></td><td width="25%"><i id="yourTemplates">??</i></td><td width="25%"><i id="currentTemplates">??</i></td>
						</tr>
					</table>
					<table id="Templates" width="88%" cellpadding="2" cellspacing="0" border="0" align="center">';

		foreach ($context['template_versions'] as $filename => $version)
			echo '
						<tr>
							<td width="50%" style="padding-left: 3ex;">', $filename, '</td><td width="25%"><i id="yourTemplates', $filename, '">', $version, '</i></td><td width="25%"><i id="currentTemplates', $filename, '">??</i></td>
						</tr>';

		echo '
					</table>';
	}

	echo '
				</td>
			</tr>
		</table>';

	/* Below is the hefty javascript for this.  Upon opening the page it checks the current file versions with ones
	   held at simplemachines.org and works out if they are up to date.  If they aren't it colors that files number
	   red.  It also contains the function, swapOption, that toggles showing the detailed information for each of the
	   file catorgories. (sources, languages, and templates.) */
	echo '
		<script language="JavaScript" type="text/javascript" src="http://www.simplemachines.org/smf/detailed-version.js"></script>
		<script language="JavaScript" type="text/javascript"><!--
			var swaps = {};

			function swapOption(sendingElement, name)
			{
				// If it is undefined, or currently off, turn it on - otherwise off.
				swaps[name] = typeof(swaps[name]) == "undefined" || !swaps[name];
				document.getElementById(name).style.display = swaps[name] ? "" : "none";

				// Unselect the link and return false.
				sendingElement.blur();
				return false;
			}

			function smfDetermineVersions()
			{
				var highYour = {"Sources": "??", "Default" : "??", "Languages": "??", "Templates": "??"};
				var highCurrent = {"Sources": "??", "Default" : "??", "Languages": "??", "Templates": "??"};
				var lowVersion = {"Sources": false, "Default": false, "Languages" : false, "Templates": false};
				var knownLanguages = [".', implode('", ".', $context['default_known_languages']), '"];

				document.getElementById("Sources").style.display = "none";
				document.getElementById("Languages").style.display = "none";
				document.getElementById("Default").style.display = "none";
				if (document.getElementById("Templates"))
					document.getElementById("Templates").style.display = "none";

				if (typeof(window.smfVersions) == "undefined")
					window.smfVersions = {};

				for (var filename in window.smfVersions)
				{
					if (!document.getElementById("current" + filename))
						continue;

					var yourVersion = getInnerHTML(document.getElementById("your" + filename));

					var versionType;
					for (var verType in lowVersion)
						if (filename.substr(0, verType.length) == verType)
						{
							versionType = verType;
							break;
						}

					if (typeof(versionType) != "undefined")
					{
						if ((highYour[versionType] < yourVersion || highYour[versionType] == "??") && !lowVersion[versionType])
							highYour[versionType] = yourVersion;
						if (highCurrent[versionType] < smfVersions[filename] || highCurrent[versionType] == "??")
							highCurrent[versionType] = smfVersions[filename];

						if (yourVersion < smfVersions[filename])
						{
							lowVersion[versionType] = yourVersion;
							document.getElementById("your" + filename).style.color = "red";
						}
					}
					else if (yourVersion < smfVersions[filename])
						lowVersion[versionType] = yourVersion;

					setInnerHTML(document.getElementById("current" + filename), smfVersions[filename]);
					setInnerHTML(document.getElementById("your" + filename), yourVersion);
				}

				if (typeof(window.smfLanguageVersions) == "undefined")
					window.smfLanguageVersions = {};

				for (filename in window.smfLanguageVersions)
				{
					for (var i = 0; i < knownLanguages.length; i++)
					{
						if (!document.getElementById("current" + filename + knownLanguages[i]))
							continue;

						setInnerHTML(document.getElementById("current" + filename + knownLanguages[i]), smfLanguageVersions[filename]);

						yourVersion = getInnerHTML(document.getElementById("your" + filename + knownLanguages[i]));
						setInnerHTML(document.getElementById("your" + filename + knownLanguages[i]), yourVersion);

						if ((highYour["Languages"] < yourVersion || highYour["Languages"] == "??") && !lowVersion["Languages"])
							highYour["Languages"] = yourVersion;
						if (highCurrent["Languages"] < smfLanguageVersions[filename] || highCurrent["Languages"] == "??")
							highCurrent["Languages"] = smfLanguageVersions[filename];

						if (yourVersion < smfLanguageVersions[filename])
						{
							lowVersion["Languages"] = yourVersion;
							document.getElementById("your" + filename + knownLanguages[i]).style.color = "red";
						}
					}
				}

				setInnerHTML(document.getElementById("yourSources"), lowVersion["Sources"] ? lowVersion["Sources"] : highYour["Sources"]);
				setInnerHTML(document.getElementById("currentSources"), highCurrent["Sources"]);
				if (lowVersion["Sources"])
					document.getElementById("yourSources").style.color = "red";

				setInnerHTML(document.getElementById("yourDefault"), lowVersion["Default"] ? lowVersion["Default"] : highYour["Default"]);
				setInnerHTML(document.getElementById("currentDefault"), highCurrent["Default"]);
				if (lowVersion["Default"])
					document.getElementById("yourDefault").style.color = "red";

				if (document.getElementById("Templates"))
				{
					setInnerHTML(document.getElementById("yourTemplates"), lowVersion["Templates"] ? lowVersion["Templates"] : highYour["Templates"]);
					setInnerHTML(document.getElementById("currentTemplates"), highCurrent["Templates"]);

					if (lowVersion["Templates"])
						document.getElementById("yourTemplates").style.color = "red";
				}

				setInnerHTML(document.getElementById("yourLanguages"), lowVersion["Languages"] ? lowVersion["Languages"] : highYour["Languages"]);
				setInnerHTML(document.getElementById("currentLanguages"), highCurrent["Languages"]);
				if (lowVersion["Languages"])
					document.getElementById("yourLanguages").style.color = "red";
			}
		// --></script>';

	// Internet Explorer 4 is tricky, it won't set any innerHTML until after load.
	if ($context['browser']['is_ie4'])
		echo '
		<script language="JavaScript" type="text/javascript"><!--
			window.onload = smfDetermineVersions;
		// --></script>';
	else
		echo '
		<script language="JavaScript" type="text/javascript"><!--
			smfDetermineVersions();
		// --></script>';
}

// Form for stopping people using naughty words, etc.
function template_edit_censored()
{
	global $context, $settings, $options, $scripturl, $txt;

	// First section is for adding/removing words from the censored list.
	echo '
		<form action="', $scripturl, '?action=setcensor2" method="post">
			<table width="600" cellpadding="4" cellspacing="1" border="0" align="center" class="bordercolor">
				<tr class="titlebg">
					<td>
						<a href="', $scripturl, '?action=helpadmin;help=11" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt[119], '" border="0" align="top" /></a> ', $txt[135], '
					</td>
				</tr><tr class="windowbg">
					<td class="smalltext" style="padding: 2ex;">', $txt[141], '</td>
				</tr><tr>
					<td class="windowbg2" align="center">
						', $txt[136], '<br />';

	// Show text boxes for censoring [bad   ] => [good  ].
	foreach ($context['censored_words'] as $vulgar => $proper)
		echo '
						<div style="margin-top: 1ex;"><input type="text" name="censor_vulgar[]" value="', $vulgar, '" size="20" /> => <input type="text" name="censor_proper[]" value="', $proper, '" size="20" /></div>';

	// Now provide a way to censor more words.
	echo '
						<noscript>
							<div style="margin-top: 1ex;"><input type="text" name="censor_vulgar[]" size="20" /> => <input type="text" name="censor_proper[]" size="20" /></div>
						</noscript>
						<script language="JavaScript" type="text/javascript"><!--
							document.write(\'<div id="moreCensoredWords"></div><div style="margin-top: 1ex;"><a href="#;" onclick="addNewWord(); return false;">', $txt['censor_clickadd'], '</a></div>\');

							function addNewWord()
							{
								setOuterHTML(document.getElementById("moreCensoredWords"), \'<div style="margin-top: 1ex;"><input type="text" name="censor_vulgar[]" size="20" /> => <input type="text" name="censor_proper[]" size="20" /></div><div id="moreCensoredWords"></div>\');
							}
						// --></script>
						<br />
						<div align="left">
							<input type="hidden" name="censorWholeWord" value="0" /><input type="checkbox" name="censorWholeWord" value="1" id="censorWholeWord"', $context['censor_whole_word'] ? ' checked="checked"' : '', ' class="check" /> <label for="censorWholeWord">', $txt['smf231'], '</label><br />
							<input type="hidden" name="censorIgnoreCase" value="0" /><label for="censorIgnoreCase"><input type="checkbox" name="censorIgnoreCase" value="1" id="censorIgnoreCase"', $context['censor_ignore_case'] ? ' checked="checked"' : '', ' class="check" /> ', $txt['censor_case'], '</label><br />
						</div>
						<br />
						<input type="submit" value="', $txt[10], '" />
					</td>
				</tr>
			</table>
			<input type="hidden" name="sc" value="', $context['session_id'], '" />

			<br />';

	// This table lets you test out your filters by typing in rude words and seeing what comes out.
	echo '
			<table width="600" cellpadding="4" cellspacing="1" border="0" align="center" class="bordercolor">
				<tr class="titlebg">
					<td>', $txt['censor_test'], '</td>
				</tr><tr>
					<td class="windowbg2" align="center">
						<input type="text" name="censortest" width="15" value="', $context['censor_test'], '" />
						<input type="submit" value="', $txt['censor_test_save'], '" />
					</td>
				</tr>
			</table>
			<input type="hidden" name="sc" value="', $context['session_id'], '" />
		</form>';
}

// Template for forum maintenance page.
function template_maintain()
{
	global $context, $settings, $options, $txt, $scripturl;

	// Starts off with general maintenance procedures.
	echo '
		<table width="100%" cellpadding="4" cellspacing="1" border="0" class="bordercolor">
			<tr class="titlebg">
				<td><a href="', $scripturl, '?action=helpadmin;help=maintenance_general" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt[119], '" border="0" align="top" /></a> ', $txt['maintain_title'], ' - ', $txt['maintain_general'], '</td>
			</tr>
			<tr>
				<td class="windowbg2" style="line-height: 1.3; padding-bottom: 2ex;">
					<a href="', $scripturl, '?action=optimizetables">', $txt['maintain_optimize'], '</a><br />
					<a href="', $scripturl, '?action=detailedversion">', $txt['maintain_version'], '</a><br />
					<a href="', $scripturl, '?action=repairboards">', $txt['maintain_errors'], '</a><br />
					<a href="', $scripturl, '?action=boardrecount">', $txt['maintain_recount'], '</a><br />
					<a href="', $scripturl, '?action=maintain;sa=logs">', $txt['maintain_logs'], '</a><br />
				</td>
			</tr>';

	// Backing up the database...?  Good idea!
	echo '
			<tr class="titlebg">
				<td><a href="', $scripturl, '?action=helpadmin;help=maintenance_backup" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt[119], '" border="0" align="top" /></a> ', $txt['maintain_title'], ' - ', $txt['maintain_backup'], '</td>
			</tr>
			<tr>
				<td class="windowbg2" style="padding-bottom: 1ex;">
					<form action="', $scripturl, '" method="get" onsubmit="return this.struct.checked || this.data.checked;">
						<label for="struct"><input type="checkbox" name="struct" id="struct" onclick="this.form.submitDump.disabled = !this.form.struct.checked && !this.form.data.checked;" class="check" /> ', $txt['maintain_backup_struct'], '</label><br />
						<label for="data"><input type="checkbox" name="data" id="data" onclick="this.form.submitDump.disabled = !this.form.struct.checked && !this.form.data.checked;" checked="checked" class="check" /> ', $txt['maintain_backup_data'], '</label><br />
						<br />
						<label for="compress"><input type="checkbox" name="compress" id="compress" value="gzip" checked="checked" class="check" /> ', $txt['maintain_backup_gz'], '</label>
						<div align="right" style="margin: 1ex;"><input type="submit" id="submitDump" value="', $txt['maintain_backup_save'], '" /></div>
						<input type="hidden" name="action" value="dumpdb" />
						<input type="hidden" name="sesc" value="', $context['session_id'], '" />
					</form>
				</td>
			</tr>';

	// Pruning any older posts.
	echo '
			<tr class="titlebg">
				<td><a href="', $scripturl, '?action=helpadmin;help=maintenance_rot" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt[119], '" border="0" align="top" /></a> ', $txt['maintain_title'], ' - ', $txt['maintain_old'], '</td>
			</tr>
			<tr>
				<td class="windowbg2">
					<a name="rotLink"></a>';

	// Bit of javascript for showing which boards to prune in an otherwise hidden list.
	echo '
					<script language="JavaScript" type="text/javascript"><!--
						var rotSwap = false;
						function swapRot()
						{
							rotSwap = !rotSwap;

							document.getElementById("rotIcon").src = smf_images_url + (rotSwap ? "/collapse.gif" : "/expand.gif");
							setInnerHTML(document.getElementById("rotText"), rotSwap ? "', $txt['maintain_old_choose'], '" : "', $txt['maintain_old_all'], '");
							document.getElementById("rotPanel").style.display = (rotSwap ? "block" : "none");

							for (var i = 0; i < document.rotForm.length; i++)
							{
								if (document.rotForm.elements[i].type.toLowerCase() == "checkbox")
									document.rotForm.elements[i].checked = !rotSwap;
							}
						}
					// --></script>';

	// The otherwise hidden "choose which boards to prune".
	echo '
					<form action="', $scripturl, '?action=removeoldtopics2" method="post" name="rotForm">
						', $txt['maintain_old1'], '<input type="text" name="maxdays" value="30" size="3" />', $txt['maintain_old2'], '<br />
						<br />
						<a href="#rotLink" onclick="swapRot();"><img src="', $settings['images_url'], '/expand.gif" alt="+" id="rotIcon" border="0" /></a> <a href="#rotLink" onclick="swapRot();"><span id="rotText" style="font-weight: bold;">', $txt['maintain_old_all'], '</span></a>
						<div style="display: none;" id="rotPanel">
							<table width="100%" cellpadding="3" cellspacing="0" border="0">
								<tr>
									<td valign="top">';

	// This is the "middle" of the list.
	$middle = count($context['categories']) / 2;

	$i = 0;
	foreach ($context['categories'] as $category)
	{
		echo '
										<span style="text-decoration: underline;">', $category['name'], '</span><br />';

		// Display a checkbox with every board.
		foreach ($category['boards'] as $board)
			echo '
										<label for="boards[', $board['id'], ']"><input type="checkbox" name="boards[', $board['id'], ']" id="boards[', $board['id'], ']" checked="checked" class="check" /> ', str_repeat('&nbsp; ', $board['child_level']), $board['name'], '</label><br />';
		echo '
										<br />';

		// Increase $i, and check if we're at the middle yet.
		if (++$i == $middle)
			echo '
									</td>
									<td valign="top">';
	}

	echo '
									</td>
								</tr>
							</table>
						</div>

						<div align="right" style="margin: 1ex;"><input type="submit" value="', $txt['maintain_old_remove'], '" onclick="return confirm(\'', $txt['maintain_old_confirm'], '\');" /></div>
						<input type="hidden" name="sc" value="', $context['session_id'], '" />
					</form>
				</td>
			</tr>
		</table>';

	// Pop up a box to say function completed if the user has been redirected back here from a function they ran.
	if ($context['maintenance_finished'])
		echo '
	<script language="JavaScript" type="text/javascript"><!--
		setTimeout("alert(\"', $txt['maintain_done'], '\")", 120);
	// --></script>';
}

?>