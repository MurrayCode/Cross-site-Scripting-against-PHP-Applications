<?php
// Version: 1.0; index

/*	This template is, perhaps, the most important template in the theme.  It
	contains the main template layer that displays the header and footer of
	the forum, namely with main_above and main_below.  It also contains the
	menu sub template, which appropriately displays the menu; the init sub
	template, which is there to set the theme up; (init can be missing.) and
	the linktree sub template, which sorts out the link tree.

	The init sub template should load any data and set any hardcoded options.

	The main_above sub template is what is shown above the main content, and
	should contain anything that should be shown up there.

	The main_below sub template, conversely, is shown after the main content.
	It should probably contain the copyright statement and some other things.

	The linktree sub template should display the link tree, using the data
	in the $context['linktree'] variable.

	The menu sub template should display all the relevant buttons the user
	wants and or needs.

	For more information on the templating system, please see the site at:
	http://www.simplemachines.org/
*/

// Initialize the template... mainly little settings.
function template_init()
{
	global $context, $settings, $options, $txt;

	/* Use images from default theme when using templates from the default theme?
		if this is 'always', images from the default theme will be used.
		if this is 'defaults', images from the default theme will only be used with default templates.
		if this is 'never' or isn't set at all, images from the default theme will not be used. */
	$settings['use_default_images'] = 'never';

	/* What document type definition is being used? (for font size and other issues.)
		'xhtml' for an XHTML 1.0 document type definition.
		'html' for an HTML 4.01 document type definition. */
	$settings['doctype'] = 'xhtml';
}

// The main sub template above the content.
function template_main_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Show right to left and the character set for ease of translating.
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html', $context['right_to_left'] ? ' dir="rtl"' : '', '><head>
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<meta name="description" content="', $context['page_title'], '" />
	<meta name="keywords" content="PHP, MySQL, bulletin, board, free, open, source, smf, simple, machines, forum" />
	<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/script.js"></script>
	<script language="JavaScript" type="text/javascript"><!--
		var smf_theme_url = "', $settings['theme_url'], '";
		var smf_images_url = "', $settings['images_url'], '";
	// --></script>
	<title>', $context['page_title'], '</title>';

	// This is here because Gecko browsers properly support white-space....
	if ($context['browser']['is_gecko'])
		echo '
	<style type="text/css"><!--
		.code
		{
			white-space: pre;
		}
	--></style>';

	echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/style.css" />';

	/* Internet Explorer 4/5 and Opera 6 just don't do font sizes properly. (they are big...)
		Thus, in Internet Explorer 4, 5, and Opera 6 this will show fonts one size smaller than usual.
		Note that this is affected by whether IE 6 is in standards compliance mode.. if not, it will also be big.
		Standards compliance mode happens when you use xhtml... */
	if ($context['browser']['needs_size_fix'])
		echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/fonts-compat.css" />';

	// Show all the relative links, such as help, search, contents, and the like.
	echo '
	<link rel="help" href="', $scripturl, '?action=help" target="_blank" />
	<link rel="search" href="' . $scripturl . '?action=search" />
	<link rel="contents" href="', $scripturl, '" />';

	// If we're viewing a topic, these should be the previous and next topics, respectively.
	if (!empty($context['current_topic']))
		echo '
	<link rel="prev" href="' . $scripturl . '?action=;topic=' . $context['current_topic'] . '.0;prev_next=prev" />
	<link rel="next" href="' . $scripturl . '?action=;topic=' . $context['current_topic'] . '.0;prev_next=next" />';

	// If we're in a board, or a topic for that matter, the index will be the board's index.
	if (!empty($context['current_board']))
		echo '
	<link rel="index" href="' . $scripturl . '?action=;board=' . $context['current_board'] . '.0" />';

	// We'll have to use the cookie to remember the header...
	if ($context['user']['is_guest'])
		$options['collapse_header'] = !empty($_COOKIE['upshrink']);

	// Output any remaining HTML headers. (from mods, maybe?)
	echo $context['html_headers'], '

	<script language="JavaScript" type="text/javascript"><!--
		var current_header = ', empty($options['collapse_header']) ? 'false' : 'true', ';

		function shrinkHeader(mode)
		{';

	// Guests don't have theme options!!
	if ($context['user']['is_guest'])
		echo '
			document.cookie = "upshrink=" + (mode ? 1 : 0);';
	else
		echo '
			document.getElementById("upshrinkTemp").src = "', $scripturl, '?action=jsoption;var=collapse_header;val=" + (mode ? 1 : 0) + ";sesc=', $context['session_id'], ';" + (new Date().getTime());';

	echo '
			document.getElementById("upshrink").src = smf_images_url + (mode ? "/upshrink2.gif" : "/upshrink.gif");

			document.getElementById("upshrinkHeader").style.display = mode ? "none" : "";

			current_header = mode;
		}
	// --></script>
</head>
<body>';

	// Because of the way width/padding are calculated, we have to tell Internet Explorer 4 and 5 that the content should be 100% wide. (or else it will assume about 108%!)
	echo '
	<div id="headerarea" style="padding: 12px 30px 4px 30px;', $context['browser']['needs_size_fix'] && !$context['browser']['is_ie6'] ? ' width: 100%;' : '', '">';

	// The logo and the three info boxes.
	echo '
		<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="position: relative;">
			<tr>
				<td colspan="2" valign="bottom" style="padding: 5px; white-space: nowrap;">';

	// This part is the logo and forum name.  You should be able to change this to whatever you want...
	echo '
					<img src="', $settings['images_url'], '/smflogo.gif" width="250" style="float: right;" alt="" />
					<span style="font-family: Georgia, sans-serif; font-size: xx-large;">', $context['forum_name'], '</span>';

	echo '
				</td>
			</tr>
			<tr id="upshrinkHeader"', empty($options['collapse_header']) ? '' : ' style="display: none;"', '>
				<td valign="top">
					<div class="headertitles" style="margin-right: 5px; position: relative;"><img src="', $settings['images_url'], '/blank.gif" height="12" alt="" /></div>
					<div class="headerbodies" style="position: relative; margin-right: 5px; background-image: url(', $settings['images_url'], '/box_bg.gif);">
						<img src="', $settings['images_url'], '/', $context['user']['language'], '/userinfo.gif" style="position: absolute; left: ', $context['browser']['is_ie5'] || $context['browser']['is_ie4'] ? '0' : '-1px', '; top: -16px; clear: both;" alt="" />
						<table width="99%" cellpadding="0" cellspacing="5" border="0"><tr>';

	if (!empty($context['user']['avatar']))
		echo '<td valign="middle">', $context['user']['avatar']['image'], '</td>';

	echo '<td width="100%" valign="top" class="smalltext" style="font-family: verdana, arial, sans-serif;">';

	// If the user is logged in, display stuff like their name, new messages, etc.
	if ($context['user']['is_logged'])
	{
		echo '
							', $txt['hello_member'], ' <b>', $context['user']['name'], '</b>';

		// Only tell them about their messages if they can read their messages!
		if ($context['allow_pm'])
			echo ', ', $txt[152], ' <a href="', $scripturl, '?action=pm">', $context['user']['messages'], ' ', $context['user']['messages'] != 1 ? $txt[153] : $txt[471], '</a>', $txt['newmessages4'], ' ', $context['user']['unread_messages'], ' ', $context['user']['unread_messages'] == 1 ? $txt['newmessages0'] : $txt['newmessages1'];
		echo '.';

		// Is the forum in maintenance mode?
		if ($context['in_maintenance'] && $context['user']['is_admin'])
			echo '<br />
							<b>', $txt[616], '</b>';

		// Are there any members waiting for approval?
		if (!empty($context['unapproved_members']))
			echo '<br />
							', $context['unapproved_members'] == 1 ? $txt['approve_thereis'] : $txt['approve_thereare'], ' <a href="', $scripturl, '?action=regcenter">', $context['unapproved_members'] == 1 ? $txt['approve_member'] : $context['unapproved_members'] . ' ' . $txt['approve_members'], '</a> ', $txt['approve_members_waiting'];

		// Show the total time logged in?
		if (!empty($context['user']['total_time_logged_in']))
		{
			echo '
							<br />', $txt['totalTimeLogged1'];

			// If days is just zero, don't bother to show it.
			if ($context['user']['total_time_logged_in']['days'] > 0)
				echo $context['user']['total_time_logged_in']['days'] . $txt['totalTimeLogged2'];

			// Same with hours - only show it if it's above zero.
			if ($context['user']['total_time_logged_in']['hours'] > 0)
				echo $context['user']['total_time_logged_in']['hours'] . $txt['totalTimeLogged3'];

			// But, let's always show minutes - Time wasted here: 0 minutes ;).
			echo $context['user']['total_time_logged_in']['minutes'], $txt['totalTimeLogged4'];
		}

		echo '<br />
							<a href="', $scripturl, '?action=unread">', $txt['unread_since_visit'], '</a><br />
							<a href="', $scripturl, '?action=unreadreplies">', $txt['show_unread_replies'], '</a><br />
							', $context['current_time'];
	}
	// Otherwise they're a guest - so politely ask them to register or login.
	else
	{
		echo '
							', $txt['welcome_guest'], '<br />
							', $context['current_time'], '<br />

							<form action="', $scripturl, '?action=login2" method="post" style="margin: 3px 1ex 1px 0; text-align:right;">
								<input type="text" name="user" size="10" /> <input type="password" name="passwrd" size="10" />
								<select name="cookielength">
									<option value="60">', $txt['smf53'], '</option>
									<option value="1440">', $txt['smf47'], '</option>
									<option value="10080">', $txt['smf48'], '</option>
									<option value="302400">', $txt['smf49'], '</option>
									<option value="-1" selected="selected">', $txt['smf50'], '</option>
								</select>
								<input type="submit" value="', $txt[34], '" /><br />
								', $txt['smf52'], '
							</form>';
	}

	echo '
						</td></tr></table>
					</div>

					<form action="', $scripturl, '?action=search2" method="post" style="margin: 0; margin-top: 7px;">
						<b>', $txt[182], ': </b><input type="text" name="search" value="" style="width: 190px;" />&nbsp;
						<input type="submit" name="submit" value="', $txt[182], '" style="width: 8ex;" />&nbsp;
						<a href="', $scripturl, '?action=search;advanced">', $txt['smf298'], '</a>
						<input type="hidden" name="advanced" value="0" />';

	// If we're on a certain board, limit it to this board ;).
	if (!empty($context['current_board']))
		echo '
						<input type="hidden" name="brd[', $context['current_board'], ']" value="', $context['current_board'], '" />';

	echo '
					</form>

				</td>
				<td width="262" style="padding-left: 5px;" valign="top">';

	// Show a random news item? (or you could pick one from news_lines...)
	if (!empty($settings['enable_news']))
		echo '
					<div class="headertitles" style="width: 260px;"><img src="', $settings['images_url'], '/blank.gif" height="12" alt="" /></div>
					<div class="headerbodies" style="width: 260px; position: relative; background-image: url(', $settings['images_url'], '/box_bg.gif); margin-bottom: 8px;">
						<img src="', $settings['images_url'], '/', $context['user']['language'], '/newsbox.gif" style="position: absolute; left: -1px; top: -16px;" alt="" />
						<div style="height: 50px; overflow: auto; padding: 5px;" class="smalltext">', $context['random_news_line'], '</div>
					</div>';

	// The "key stats" box.
	echo '
					<div class="headertitles" style="width: 260px;"><img src="', $settings['images_url'], '/blank.gif" height="12" alt="" /></div>
					<div class="headerbodies" style="width: 260px; position: relative; background-image: url(', $settings['images_url'], '/box_bg.gif);">
						<img src="', $settings['images_url'], '/', $context['user']['language'], '/keystats.gif" style="position: absolute; left: -1px; top: -16px;" alt="" />
						<div style="height: 35px; padding: 5px;" class="smalltext">
							<b>', $modSettings['totalMessages'], '</b> ', $txt[95], ' ', $txt['smf88'], ' <b>', $modSettings['totalTopics'], '</b> ', $txt[64], ' ', $txt[525], ' <b>', $modSettings['memberCount'], '</b> ', $txt[19], '<br />
							', $txt[656], ': <b> <a href="', $scripturl, '?action=profile;u=', $modSettings['latestMember'], '">', $modSettings['latestRealName'], '</a></b>
						</div>
					</div>';

	echo '
				</td>
			</tr>
		</table>

		<a href="#" onclick="shrinkHeader(!current_header); return false;"><img id="upshrink" src="', $settings['images_url'], '/', empty($options['collapse_header']) ? 'upshrink.gif' : 'upshrink2.gif', '" alt="*" title="', $txt['upshrink_description'], '" style="margin: 2px 0;" border="0" /></a><img id="upshrinkTemp" src="', $settings['images_url'], '/blank.gif" alt="" style="margin-right: 2ex;" />';

		// Show the menu here, according to the menu sub template.
		template_menu();

	echo '
	</div>';

	// The main content should go here.  A table is used because IE 6 just can't handle a div.
	echo '
	<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
		<td id="bodyarea" style="padding: 1ex 20px 2ex 20px;">';
}

function template_main_below()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '</td>
	</tr></table>';

	// Show the "Powered by" and "Valid" logos, as well as the copyright.  Remember, the copyright must be somewhere!
	echo '

	<div id="footerarea" style="text-align: center; padding-bottom: 1ex;', $context['browser']['needs_size_fix'] && !$context['browser']['is_ie6'] ? ' width: 100%;' : '', '">
		<script language="JavaScript" type="text/javascript"><!--
			function smfFooterHighlight(element, value)
			{
				element.src = smf_images_url + "/" + (value ? "h_" : "") + element.id + ".gif";
			}
		// --></script>
		<table cellspacing="0" cellpadding="3" border="0" align="center" width="100%">
			<tr>
				<td width="28%" valign="middle" align="right">
					<a href="http://www.mysql.com/" target="_blank"><img id="powered-mysql" src="', $settings['images_url'], '/powered-mysql.gif" alt="', $txt['powered_by_mysql'], '" width="54" height="20" style="margin: 5px 16px;" border="0" onmouseover="smfFooterHighlight(this, true);" onmouseout="smfFooterHighlight(this, false);" /></a>
					<a href="http://www.php.net/" target="_blank"><img id="powered-php" src="', $settings['images_url'], '/powered-php.gif" alt="', $txt['powered_by_php'], '" width="54" height="20" style="margin: 5px 16px;" border="0" onmouseover="smfFooterHighlight(this, true);" onmouseout="smfFooterHighlight(this, false);" /></a>
				</td>
				<td valign="middle" align="center" style="white-space: nowrap;">
					', theme_copyright(), '
				</td>
				<td width="28%" valign="middle" align="left">
					<a href="http://validator.w3.org/check/referer" target="_blank"><img id="valid-xhtml10" src="', $settings['images_url'], '/valid-xhtml10.gif" alt="', $txt['valid_xhtml'], '" width="54" height="20" style="margin: 5px 16px;" border="0" onmouseover="smfFooterHighlight(this, true);" onmouseout="smfFooterHighlight(this, false);" /></a>
					<a href="http://jigsaw.w3.org/css-validator/check/referer" target="_blank"><img id="valid-css" src="', $settings['images_url'], '/valid-css.gif" alt="', $txt['valid_css'], '" width="54" height="20" style="margin: 5px 16px;" border="0" onmouseover="smfFooterHighlight(this, true);" onmouseout="smfFooterHighlight(this, false);" /></a>
				</td>
			</tr>
		</table>';

	// Show the load time?
	if ($context['show_load_time'])
		echo '
		<span class="smalltext">', $txt['smf301'], $context['load_time'], $txt['smf302'], $context['load_queries'], $txt['smf302b'], '</span>';

	echo '
		</div>';

	// This is an interesting bug in Internet Explorer AND Safari.  Rather annoying, it makes overflows just not tall enough.
	if ($context['browser']['is_ie'] && !$context['browser']['is_ie4'] || $context['browser']['is_mac_ie'] || $context['browser']['is_safari'])
	{
		// The purpose of this code is to fix the height of overflow: auto div blocks, because IE can't figure it out for itself.
		echo '
		<script language="JavaScript" type="text/javascript"><!--';

		// Unfortunately, Safari does not have a "getComputedStyle" implementation yet, so we have to just do it to code...
		if ($context['browser']['is_safari'])
			echo '
			window.addEventListener("load", smf_codeFix, false);

			function smf_codeFix()
			{
				var codeFix = document.getElementsByTagName ? document.getElementsByTagName("div") : document.all.tags("div");

				for (var i = 0; i < codeFix.length; i++)
				{
					if (codeFix[i].className == "code" && codeFix[i].offsetHeight < 20)
						codeFix[i].style.height = (codeFix[i].offsetHeight + 20) + "px";
				}
			}';
		else
		{
			echo '
			var window_oldOnload = window.onload;
			window.onload = smf_codeFix;

			function smf_codeFix()
			{
				var codeFix = document.getElementsByTagName ? document.getElementsByTagName("div") : document.all.tags("div");

				for (var i = 0; i < codeFix.length; i++)
				{
					if (codeFix[i].currentStyle.overflow == "auto" && (codeFix[i].currentStyle.height == "" || codeFix[i].currentStyle.height == "auto") && (codeFix[i].scrollWidth > codeFix[i].clientWidth || codeFix[i].clientWidth == 0))
						codeFix[i].style.height = (codeFix[i].offsetHeight + 36) + "px";
				}

				if (window_oldOnload)
					window_oldOnload();
			}';
		}

		echo '
		// --></script>';
	}

	echo '
	</body>
</html>';
}

// Show a linktree.  This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree()
{
	global $context, $settings, $options;

	// Folder style or inline?  Inline has a smaller font.
	echo '<span class="nav"', $settings['linktree_inline'] ? ' style="font-size: smaller;"' : '', '>';

	// Each tree item has a URL and name.  Some may have extra_before and extra_after.
	foreach ($context['linktree'] as $link_num => $tree)
	{
		// Show the | | |-[] Folders.
		if (!$settings['linktree_inline'])
		{
			if ($link_num > 0)
				echo str_repeat('<img src="' . $settings['images_url'] . '/icons/linktree_main.gif" alt="| " border="0" />', $link_num - 1), '<img src="' . $settings['images_url'] . '/icons/linktree_side.gif" alt="|-" border="0" />';
			echo '<img src="' . $settings['images_url'] . '/icons/folder_open.gif" alt="+" border="0" />&nbsp; ';
		}

		// Show something before the link?
		if (isset($tree['extra_before']))
			echo $tree['extra_before'];

		// Show the link, including a URL if it should have one.
		echo '<b>', $settings['linktree_link'] && isset($tree['url']) ? '<a href="' . $tree['url'] . '" class="nav">' . $tree['name'] . '</a>' : $tree['name'], '</b>';

		// Show something after the link...?
		if (isset($tree['extra_after']))
			echo $tree['extra_after'];

		// Don't show a separator for the last one.
		if ($link_num != count($context['linktree']) - 1)
			echo $settings['linktree_inline'] ? ' &nbsp;|&nbsp; ' : '<br />';
	}

	echo '</span>';
}

// Show the menu up top.  Something like [home] [help] [profile] [logout]...
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Show the [home] and [help] buttons.
	echo '
				<a href="', $scripturl, '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/home.gif" alt="' . $txt[103] . '" style="margin: 2px 0;" border="0" />' : $txt[103]), '</a>', $context['menu_separator'], '
				<a href="', $scripturl, '?action=help" target="_blank">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/help.gif" alt="' . $txt[119] . '" style="margin: 2px 0;" border="0" />' : $txt[119]), '</a>', $context['menu_separator'];

	// How about the [search] button?
	if ($context['allow_search'])
		echo '
				<a href="', $scripturl, '?action=search">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/search.gif" alt="' . $txt[182] . '" style="margin: 2px 0;" border="0" />' : $txt[182]), '</a>', $context['menu_separator'];

	// Is the user allowed to administrate at all? ([admin])
	if ($context['allow_admin'])
		echo '
				<a href="', $scripturl, '?action=admin">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/admin.gif" alt="' . $txt[2] . '" style="margin: 2px 0;" border="0" />' : $txt[2]), '</a>', $context['menu_separator'];

	// Edit Profile... [profile]
	if ($context['allow_edit_profile'])
		echo '
				<a href="', $scripturl, '?action=profile">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/profile.gif" alt="' . $txt[79] . '" style="margin: 2px 0;" border="0" />' : $txt[467]), '</a>', $context['menu_separator'];

	// The [calendar]!
	if ($context['allow_calendar'])
		echo '
				<a href="', $scripturl, '?action=calendar">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/calendar.gif" alt="' . $txt['calendar24'] . '" style="margin: 2px 0;" border="0" />' : $txt['calendar24']), '</a>', $context['menu_separator'];

	// If the user is a guest, show [login] and [register] buttons.
	if ($context['user']['is_guest'])
	{
		echo '
				<a href="', $scripturl, '?action=login">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/login.gif" alt="' . $txt[34] . '" style="margin: 2px 0;" border="0" />' : $txt[34]), '</a>', $context['menu_separator'], '
				<a href="', $scripturl, '?action=register">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/register.gif" alt="' . $txt[97] . '" style="margin: 2px 0;" border="0" />' : $txt[97]), '</a>';
	}
	// Otherwise, they might want to [logout]...
	else
		echo '
				<a href="', $scripturl, '?action=logout;sesc=', $context['session_id'], '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/logout.gif" alt="' . $txt[108] . '" style="margin: 2px 0;" border="0" />' : $txt[108]), '</a>';
}

?>