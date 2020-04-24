<?php
// Version: 1.0; Profile

// Template for the profile side bar - goes before any other profile template.
function template_profile_above()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	// Assuming there are actually some areas the user can visit...
	if (!empty($context['profile_areas']))
	{
		echo '
		<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding-top: 1ex;">
			<tr>
				<td width="180" valign="top">
					<table border="0" cellpadding="4" cellspacing="1" class="bordercolor" width="170">';

		// Loop through every area, displaying its name as a header.
		foreach ($context['profile_areas'] as $section)
		{
			echo '
						<tr>
							<td class="catbg">', $section['title'], '</td>
						</tr>
						<tr class="windowbg2">
							<td class="smalltext">';

			// For every section of the area display it, and bold it if it's the current area.
			foreach ($section['areas'] as $i => $area)
				if ($i == $context['menu_item_selected'])
					echo '
								<b>', $area, '</b><br />';
				else
					echo '
								', $area, '<br />';
			echo '
								<br />
							</td>
						</tr>';
		}
		echo '
					</table>
				</td>
				<td width="100%" valign="top">';
	}
	// If no areas exist just open up a containing table.
	else
	{
		echo '
		<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding-top: 1ex;">
			<tr>
				<td width="100%" valign="top">';
	}
}

// Template for closing off table started in profile_above.
function template_profile_below()
{
	global $context, $settings, $options;

	echo '
				</td>
			</tr>
		</table>';
}

// This template displays users details without any option to edit them.
function template_summary()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt, $user_info;

	// First do the containing table and table header.
	echo '
<table border="0" cellpadding="4" cellspacing="1" align="center" class="bordercolor">
	<tr class="titlebg">
		<td align="left" width="420" height="26">
			<img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" border="0" align="top" />&nbsp;
			', $txt[35], ': ', $context['member']['username'], '
		</td>
		<td align="center" width="150">', $txt[232], '</td>
	</tr>';

	// Do the left hand column - where all the important user info is displayed.
	echo '
	<tr>
		<td class="windowbg" width="420" align="left">
			<table border="0" cellspacing="0" cellpadding="2" width="100%">
				<tr>
					<td><b>', $txt[68], ': </b></td>
					<td>', $context['member']['name'], '</td>
				</tr>';
	if (!empty($modSettings['titlesEnable']) && $context['member']['title'] != '')
	{
		echo '
				<tr>
					<td><b>', $txt['title1'], ': </b></td>
					<td>', $context['member']['title'], '</td>
				</tr>';
	}
	echo '
				<tr>
					<td><b>', $txt[86], ': </b></td>
					<td>', $context['member']['posts'], ' (', $context['member']['posts_per_day'], ' ', $txt['posts_per_day'], ')</td>
				</tr><tr>
					<td><b>', $txt[87], ': </b></td>
					<td>', (!empty($context['member']['group']) ? $context['member']['group'] : $context['member']['post_group']), '</td>
				</tr>';

	// If the person looking is an admin they can check the members IP address and hostname.
	if ($user_info['is_admin'])
	{
		echo '
				<tr>
					<td width="40%">
						<b>', $txt[512], ': </b>
					</td><td>
						<a href="', $scripturl, '?action=trackip;searchip=', $context['member']['ip'], '" target="_blank">', $context['member']['ip'], '</a>
					</td>
				</tr><tr>
					<td width="40%">
						<b>', $txt['hostname'], ': </b>
					</td><td width="55%">
						<div title="', $context['member']['hostname'], '" style="width: 100%; overflow: hidden; font-style: italic;">', $context['member']['hostname'], '</div>
					</td>
				</tr>';
	}

	// If karma enabled show the members karma.
	if ($modSettings['karmaMode'] == '1')
		echo '
				<tr>
					<td>
						<b>', $modSettings['karmaLabel'], ' </b>
					</td><td>
						', ($context['member']['karma']['good'] - $context['member']['karma']['bad']), '
					</td>
				</tr>';
	elseif ($modSettings['karmaMode'] == '2')
		echo '
				<tr>
					<td>
						<b>', $modSettings['karmaLabel'], ' </b>
					</td><td>
						+', $context['member']['karma']['good'], '/-', $context['member']['karma']['bad'], '
					</td>
				</tr>';
	echo '
				<tr>
					<td><b>', $txt[233], ': </b></td>
					<td>', $context['member']['registered'], '</td>
				</tr><tr>
					<td><b>', $txt['lastLoggedIn'], ': </b></td>
					<td>', $context['member']['last_login'], '</td>
				</tr>';

	// If the person looking at the summary is an admin the the account isn't activated, give the admin the ability to do it themselves.
	if ($user_info['is_admin'] && !$context['member']['is_activated'])
		echo '
				<tr>
					<td colspan="2"><hr size="1" width="100%" class="hrcolor" /></td>
				</tr><tr>
					<td colspan="2">
						<span style="color: red;">' . $txt['account_not_activated'] . '</span>&nbsp;(<a href="' . $scripturl . '?action=profile2;sa=activateAccount;userID=' . $context['member']['id'] . ';sesc=' . $context['session_id'] . '">' . $txt['account_activate'] . '</a>)
					</td>
				</tr>';

	// Messenger type information.
	echo '
				<tr>
					<td colspan="2"><hr size="1" width="100%" class="hrcolor" /></td>
				</tr><tr>
					<td><b>', $txt[513], ':</b></td>
					<td>', $context['member']['icq']['link_text'], '</td>
				</tr><tr>
					<td><b>', $txt[603], ': </b></td>
					<td>', $context['member']['aim']['link_text'], '</td>
				</tr><tr>
					<td><b>', $txt['MSN'], ': </b></td>
					<td>', $context['member']['msn']['link_text'], '</td>
				</tr><tr>
					<td><b>', $txt[604], ': </b></td>
					<td>', $context['member']['yim']['link_text'], '</td>
				</tr><tr>
					<td><b>', $txt[69], ': </b></td>
					<td>';

	// Only show the email address if it's not hidden.
	if ($context['member']['email_public'])
		echo '
						<a href="mailto:', $context['member']['email'], '">', $context['member']['email'], '</a>';
	// ... Or if the one looking at the profile is an admin they can see it anyway.
	elseif (!$context['member']['hide_email'])
		echo '
						<i><a href="mailto:', $context['member']['email'], '">', $context['member']['email'], '</a></i>';
	else
		echo '
						<i>', $txt[722], '</i>';

	// Some more information.
	echo '
					</td>
				</tr><tr>
					<td><b>', $txt[96], ': </b></td>
					<td><a href="', $context['member']['website']['url'], '" target="_blank">', $context['member']['website']['title'], '</a></td>
				</tr><tr>
					<td><b>', $txt[113], ' </b></td>
					<td>
						<i>', $context['can_send_pm'] ? '<a href="' . $context['member']['online']['href'] . '" title="' . $context['member']['online']['label'] . '">' : '', $settings['use_image_buttons'] ? '<img src="' . $context['member']['online']['image_href'] . '" alt="' . $context['member']['online']['text'] . '" border="0" align="middle" />' : $context['member']['online']['text'], $context['can_send_pm'] ? '</a>' : '', $settings['use_image_buttons'] ? '<span class="smalltext"> ' . $context['member']['online']['text'] . '</span>' : '', '</i>
					</td>
				</tr><tr>
					<td colspan="2"><hr size="1" width="100%" class="hrcolor" /></td>
				</tr><tr>
					<td><b>', $txt[231], ': </b></td>
					<td>', $context['member']['gender']['name'], '</td>
				</tr><tr>
					<td><b>', $txt[420], ':</b></td>
					<td>', $context['member']['age'] . ($context['member']['today_is_birthday'] ? ' &nbsp; <img src="' . $settings['images_url'] . '/bdaycake.gif" width="40" alt="" />' : ''), '</td>
				</tr><tr>
					<td><b>', $txt[227], ':</b></td>
					<td>', $context['member']['location'], '</td>
				</tr><tr>
					<td><b>', $txt['local_time'], ':</b></td>
					<td>', $context['member']['local_time'], '</td>
				</tr><tr>';

	if (!empty($modSettings['userLanguage']))
		echo '
					<td><b>', $txt['smf225'], ':</b></td>
					<td>', $context['member']['language'], '</td>
				</tr><tr>';

	echo '
					<td colspan="2"><hr size="1" width="100%" class="hrcolor" /></td>
				</tr>';

	// Show the users signature.
	echo '
				<tr>
					<td colspan="2" height="25">
						<table width="100%" cellpadding="0" cellspacing="0" border="0" style="table-layout: fixed;">
							<tr>
								<td style="padding-bottom: 0.5ex;"><b>', $txt[85], ':</b></td>
							</tr><tr>
								<td colspan="2" width="100%" class="smalltext"><div style="overflow: auto; width: 100%; padding-bottom: 3px;" class="signature">', $context['member']['signature'], '</div></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>';

	// Now print the second column where the members avatar/text is shown.
	echo '
		<td class="windowbg" valign="middle" align="center" width="150">
			', $context['member']['avatar']['image'], '<br /><br />
			', $context['member']['blurb'], '
		</td>
	</tr>';

	// Finally, if applicable, span the bottom of the table with links to other useful member functions.
	echo '
	<tr class="titlebg">
		<td colspan="2" align="left">', $txt[597], ':</td>
	</tr>
	<tr>
		<td class="windowbg2" colspan="2" align="left">';
	if (!$context['user']['is_owner'] && $context['can_send_pm'])
		echo '
			<a href="', $scripturl, '?action=pm;sa=send;u=', $context['member']['id'], '">', $txt[688], '.</a><br />
			<br />';
	echo '
			<a href="', $scripturl, '?action=profile;u=', $context['member']['id'], ';sa=showPosts">', $txt[460], ' ', $txt[461], '.</a><br />
			<a href="', $scripturl, '?action=profile;u=', $context['member']['id'], ';sa=statPanel">', $txt['statPanel_show'], '.</a><br />
			<br />
		</td>
	</tr>
</table>';
}

// Template for showing all the posts of the user, in chronological order.
function template_showPosts()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	echo '
		<table border="0" width="85%" cellspacing="1" cellpadding="4" class="bordercolor" align="center">
			<tr class="titlebg">
				<td colspan="3" height="26">
					&nbsp;<img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" border="0" align="top" />&nbsp;', $txt['showPosts'], '
				</td>
			</tr>';

	// Only show posts if they have made some!
	if (!empty($context['posts']))
	{
		// Page numbers.
		echo '
			<tr class="catbg">
				<td align="left" colspan="3">
					<b>', $txt[139], ':</b> ', $context['page_index'], '
				</td>
			</tr>
		</table>';

		// For every post to be displayed, give it its own subtable, and show the important details of the post.
		foreach ($context['posts'] as $post)
		{
			echo '
		<table border="0" width="85%" cellspacing="1" cellpadding="0" class="bordercolor" align="center">
			<tr>
				<td width="100%">
					<table border="0" width="100%" cellspacing="0" cellpadding="4" class="bordercolor" align="center">
						<tr class="titlebg">
							<td align="left" style="padding: 0 1ex;">
								', $post['counter'], '
							</td>
							<td width="75%" align="left">
								&nbsp;<a href="', $scripturl, '#', $post['category']['id'], '">', $post['category']['name'], '</a> / <a href="', $scripturl, '?board=', $post['board']['id'], '.0">', $post['board']['name'], '</a> / <a href="', $scripturl, '?topic=', $post['topic'], '.', $post['start'], '#msg', $post['id'], '">', $post['subject'], '</a>
							</td>
							<td align="right" style="padding: 0 1ex; white-space: nowrap;">
								', $txt[30], ': ', $post['time'], '
							</td>
						</tr>
						<tr>
							<td height="50" colspan="3" valign="top" class="windowbg2" align="left">', $post['body'], '</td>
						</tr>
						<tr>
							<td colspan="3" class="windowbg2" align="right">';

			if ($post['can_delete'])
				echo '
								<a href="', $scripturl, '?action=profile;u=', $context['current_member'], ';sa=showPosts;start=', $context['start'], ';delete=', $post['id'], ';sesc=', $context['session_id'], '" onclick="return confirm(\'', $txt[154], '?\');">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/delete.gif" alt="' . $txt[121] . '" border="0" />' : $txt[31]), '</a>';
			if ($post['can_delete'] && ($post['can_mark_notify'] || $post['can_mark_notify']))
				echo '
								', $context['menu_separator'];
			if ($post['can_reply'])
				echo '
								<a href="', $scripturl, '?action=post;topic=', $post['topic'], '.', $post['start'], '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/reply_sm.gif" alt="' . $txt[146] . '" border="0" />' : $txt[146]), '</a>', $context['menu_separator'], '
								<a href="', $scripturl, '?action=post;topic=', $post['topic'], '.', $post['start'], ';quote=', $post['id'], ';sesc=', $context['session_id'], '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/quote.gif" alt="' . $txt[145] . '" border="0" />' : $txt[145]), '</a>';
			if ($post['can_reply'] && $post['can_mark_notify'])
				echo '
								', $context['menu_separator'];
			if ($post['can_mark_notify'])
				echo '
								<a href="' . $scripturl . '?action=notify;topic=' . $post['topic'] . '.' . $post['start'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/notify_sm.gif" alt="' . $txt[131] . '" border="0" />' : $txt[131]) . '</a>';

			echo '
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>';
		}

		// Show more page numbers.
		echo '
		<table border="0" width="85%" cellspacing="1" cellpadding="4" class="bordercolor" align="center">
			<tr>
				<td align="left" colspan="3" class="catbg">
					<b>', $txt[139], ':</b> ', $context['page_index'], '
				</td>
			</tr>
		</table>';
	}
	// No posts? Just end the table with a informative message.
	else
		echo '
			<tr class="windowbg2">
				<td>
					', $txt[170], '
				</td>
			</tr>
		</table>';
}

// Template for user statistics, showing graphs and the like.
function template_statPanel()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	echo '
		<table border="0" width="85%" cellspacing="1" cellpadding="4" class="bordercolor" align="center">
			<tr class="titlebg">
				<td colspan="4" height="26">&nbsp;<img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" border="0" align="top" />&nbsp;', $txt['statPanel_generalStats'], '</td>
			</tr>';

	// First, show a few text statistics such as post/topic count.
	echo '
		<tr>
				<td class="windowbg" width="20" valign="middle" align="center"><img src="', $settings['images_url'], '/stats_info.gif" border="0" width="20" height="20" alt="" /></td>
				<td class="windowbg2" valign="top" colspan="3">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">
						<tr>
							<td nowrap="nowrap">', $txt['statPanel_total_time_online'], ':</td>
							<td align="right">', $context['time_logged_in'], '</td>
						</tr><tr>
							<td nowrap="nowrap">', $txt[489], ':</td>
							<td align="right">', $context['num_posts'], ' ', $txt['statPanel_posts'], '</td>
						</tr><tr>
							<td nowrap="nowrap">', $txt[490], ':</td>
							<td align="right">', $context['num_topics'], ' ', $txt['statPanel_topics'], '</td>
						</tr><tr>
							<td nowrap="nowrap">', $txt['statPanel_users_polls'], ':</td>
							<td align="right">', $context['num_polls'], ' ', $txt['statPanel_polls'], '</td>
						</tr><tr>
							<td nowrap="nowrap">', $txt['statPanel_users_votes'], ':</td>
							<td align="right">', $context['num_votes'], ' ', $txt['statPanel_votes'], '</td>
						</tr>
					</table>
				</td>
			</tr>';

	// This next section draws a graph showing what times of day they post the most.
	echo '
			<tr class="titlebg">
				<td colspan="4" width="100%">', $txt['statPanel_activityTime'], '</td>
			</tr><tr>
				<td class="windowbg" width="20" valign="middle" align="center"><img src="', $settings['images_url'], '/stats_views.gif" border="0" width="20" height="20" alt="" /></td>
				<td colspan="3" class="windowbg2" width="100%" valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">';

	// If they haven't post at all, don't draw the graph.
	if (empty($context['posts_by_time']))
		echo '
						<tr>
							<td width="100%" valign="top" align="center">', $txt['statPanel_noPosts'], '</td>
						</tr>';
	// Otherwise do!
	else
	{
		echo '
						<tr>
							<td width="2%" valign="bottom"></td>';

		// Loops through each hour drawing the bar to the correct height.
		foreach ($context['posts_by_time'] as $time_of_day)
			echo '
							<td width="4%" valign="bottom" align="center"><img src="', $settings['images_url'], '/bar.gif" width="12" height="', $time_of_day['posts_percent'], '" alt="" border="0" /></td>';
		echo '
							<td width="2%" valign="bottom"></td>
						</tr><tr>
							<td width="2%" valign="bottom"></td>';
		// The labels.
		foreach ($context['posts_by_time'] as $time_of_day)
			echo '
							<td width="4%" valign="bottom" align="center" style="border-color: black; border-style: solid; border-width: 1px ', $time_of_day['hour'] != 23 ? '1px' : '0px', ' 0px 0px">', $time_of_day['hour'], '</td>';
		echo '
							<td width="2%" valign="bottom"></td>
						</tr><tr>
							<td width="100%" colspan="26" align="center"><b>', $txt['statPanel_timeOfDay'], '</b></td>
						</tr>';
	}
	echo '
					</table>
				</td>
			</tr>';

	// The final section is two columns with the most popular boards by posts and activity (activity = users posts / total posts).
	echo '
			<tr class="titlebg">
				<td colspan="2" width="50%">', $txt['statPanel_topBoards'], '</td>
				<td colspan="2" width="50%">', $txt['statPanel_topBoardsActivity'], '</td>
			</tr><tr>
				<td class="windowbg" width="20" valign="middle" align="center"><img src="', $settings['images_url'], '/stats_replies.gif" border="0" width="20" height="20" alt="" /></td>
				<td class="windowbg2" width="50%" valign="top">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">';
	if (empty($context['popular_boards']))
		echo '
						<tr>
							<td width="100%" valign="top" align="center">', $txt['statPanel_noPosts'], '</td>
						</tr>';
	else
	{
		// Draw a bar for every board.
		foreach ($context['popular_boards'] as $board)
		{
			echo '
						<tr>
							<td width="60%" valign="top">', $board['link'], '</td>
							<td width="20%" align="left" valign="top">', $board['posts'] > 0 ? '<img src="' . $settings['images_url'] . '/bar.gif" width="' . $board['posts_percent'] . '" height="15" alt="" border="0" />' : '&nbsp;', '</td>
							<td width="20%" align="right" valign="top">', $board['posts'], '</td>
						</tr>';
		}
	}
	echo '
					</table>
				</td>
				<td class="windowbg" width="20" valign="middle" align="center"><img src="', $settings['images_url'], '/stats_replies.gif" border="0" width="20" height="20" alt="" /></td>
				<td class="windowbg2" width="100%" valign="top">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">';
	if (empty($context['board_activity']))
		echo '
						<tr>
							<td width="100%" valign="top" align="center">', $txt['statPanel_noPosts'], '</td>
						</tr>';
	else
	{
		// Draw a bar for every board.
		foreach ($context['board_activity'] as $activity)
		{
			echo '
						<tr>
							<td width="60%" valign="top">', $activity['link'], '</td>
							<td width="20%" align="left" valign="top">', $activity['percent'] > 0 ? '<img src="' . $settings['images_url'] . '/bar.gif" width="' . $activity['percent'] . '" height="15" alt="" border="0" />' : '&nbsp;', '</td>
							<td width="20%" align="right" valign="top">', $activity['percent'], '%</td>
						</tr>';
		}
	}
	echo '
					</table>
				</td>
			</tr>
		</table>';
}

// Template for changing user account information.
function template_account()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	// Javascript for checking if password has been entered / taking admin powers away from themselves.
	echo '
		<script language="JavaScript1.2" type="text/javascript"><!--
			function checkProfileSubmit()
			{';

	// If this part requires a password, make sure to give a warning.
	if ($context['user']['is_owner'] && $context['require_password'])
		echo '
				// Did you forget to type your password?
				if (document.creator.oldpasswrd.value == "")
				{
					alert("', $txt['smf244'], '");
					return false;
				}';

	// This part checks if they are removing themselves from administrative power on accident.
	if ($context['allow_edit_membergroups'] && $context['user']['is_owner'] && $context['member']['group'] == 1)
		echo '
				if (typeof(document.creator.ID_GROUP) != "undefined" && document.creator.ID_GROUP.value != "1")
					return confirm("', $txt['deadmin_confirm'], '");';

	echo '
				return true;
			}
		// --></script>';

	// The main containing header.
	echo '
		<form action="', $scripturl, '?action=profile2" method="post" name="creator" onsubmit="return checkProfileSubmit();">
			<table border="0" width="85%" cellspacing="1" cellpadding="4" align="center" class="bordercolor">
				<tr class="titlebg">
					<td height="26" align="left">
						&nbsp;<img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" border="0" align="top" />&nbsp;
						', $txt[79], '
					</td>
				</tr>';

	// Display Name, language and date user registered.
	echo '
				<tr class="windowbg">
					<td class="smalltext" height="25" align="left" style="padding: 2ex;">
						', $txt['account_info'], '
					</td>
				</tr>
				<tr>
					<td class="windowbg2" align="left" style="padding-bottom: 2ex;">
						<table width="100%" cellpadding="3" cellspacing="0" border="0">';

	// Only show these settings if you're allowed to edit the account itself (not just the membergroups).
	if ($context['allow_edit_account'])
	{
		if ($context['user']['is_admin'] && !empty($context['allow_edit_username']))
			echo '
							<tr>
								<td colspan="2" align="center" style="color: red">', $txt['username_warning'], '</td>
							</tr>
							<tr>
								<td width="40%">
									<b>', $txt[35], ': </b>
								</td>
								<td>
									<input type="text" name="memberName" size="30" value="', $context['member']['username'], '" />
								</td>
							</tr>';
		else
			echo '
							<tr>
								<td width="40%">
									<b>', $txt[35], ': </b>', $context['user']['is_admin'] ? '
									<div class="smalltext">(<a href="' . $scripturl . '?action=profile;u=' . $context['member']['id'] . ';sa=account;changeusername" style="font-style: italic;">' . $txt['username_change'] . '</a>)</div>' : '', '
								</td>
								<td>
									', $context['member']['username'], '
								</td>
							</tr>';

		echo '
							<tr>
								<td>
									<b', (isset($context['modify_error']['no_name']) || isset($context['modify_error']['name_taken']) ? ' style="color: #FF0000;"' : ''), '>', $txt[68], ': </b>
									<div class="smalltext">', $txt[518], '</div>
								</td>
								<td>', ($context['allow_edit_name'] ? '<input type="text" name="realName" size="30" value="' . $context['member']['name'] . '" />' : $context['member']['name']), '</td>
							</tr>';

		// Allow the administrator to change the date they registered on and their post count.
		if ($context['user']['is_admin'])
			echo '
							<tr>
								<td><b>', $txt[233], ':</b></td>
								<td><input type="text" name="dateRegistered" size="30" value="', $context['member']['registered'], '" /></td>
							</tr>
							<tr>
								<td><b>', $txt[86], ': </b></td>
								<td><input type="text" name="posts" size="4" value="', $context['member']['posts'], '" /></td>
							</tr>';

		// Only display if admin has enabled "user selectable language".
		if (!empty($modSettings['userLanguage']) && count($context['languages']) > 1)
		{
			echo '
							<tr>
								<td width="40%"><b>', $txt[349], ':</b></td>
								<td>
									<select name="lngfile">';

			// Fill a select box with all the languages installed.
			foreach ($context['languages'] as $language)
				echo '
										<option value="', $language['filename'], '"', $language['selected'] ? ' selected="selected"' : '', '>', $language['name'], '</option>';
			echo '
									</select>
								</td>
							</tr>';
		}
	}
	// Only display member group information/editing with the proper permissions.
	if ($context['allow_edit_membergroups'])
	{
		echo '
							<tr>
								<td colspan="2"><hr width="100%" size="1" class="hrcolor" /></td>
							</tr><tr>
								<td valign="top"><b>', $txt['primary_membergroup'], ': </b></td>
								<td>
									<select name="ID_GROUP">';
		// Fill the select box with all primary member groups that can be assigned to a member.
		foreach ($context['member_groups'] as $member_group)
			echo '
										<option value="', $member_group['id'], '"', $member_group['is_primary'] ? ' selected="selected"' : '', '>
											', $member_group['name'], '
										</option>';
		echo '
									</select>
									<div class="smalltext"><a href="', $scripturl, '?action=helpadmin;help=moderator_why_missing" onclick="return reqWin(this.href);">', $txt['moderator_why_missing'], '</a></div>
								</td>
							</tr><tr>
								<td valign="top"><b>', $txt['additional_membergroups'], ':</b></td>
								<td>
									<div id="additionalGroupsList">
										<input type="hidden" name="additionalGroups[]" value="0" />';
		// For each membergroup show a checkbox so members can be assigned to more than one group.
		foreach ($context['member_groups'] as $member_group)
			if ($member_group['can_be_additional'])
				echo '
										<input type="checkbox" name="additionalGroups[]" value="', $member_group['id'], '"', $member_group['is_additional'] ? ' checked="checked"' : '', ' class="check" /> ', $member_group['name'], '<br />';
		echo '
									</div>
									<script language="JavaScript" type="text/javascript"><!--
										document.getElementById("additionalGroupsList").style.display = "none";
										document.write("<a id=\"additionalGroupsLink\" href=\"#\" onclick=\"document.getElementById(\'additionalGroupsList\').style.display = \'block\'; document.getElementById(\'additionalGroupsLink\').style.display = \'none\'; return false;\">', $txt['additional_membergroups_show'], '</a>");
									// --></script>
								</td>
							</tr>';
	}

	// Show this part if you're not only here for assigning membergroups.
	if ($context['allow_edit_account'])
	{
		// Show email address box.
		echo '
							<tr>
								<td colspan="2"><hr width="100%" size="1" class="hrcolor" /></td>
							</tr><tr>
								<td width="40%"><b', (isset($context['modify_error']['bad_email']) || isset($context['modify_error']['no_email']) || isset($context['modify_error']['email_taken']) ? ' style="color: #FF0000;"' : ''), '>', $txt[69], ': </b><div class="smalltext">', $txt[679], '</div></td>
								<td><input type="text" name="emailAddress" size="30" value="', $context['member']['email'], '" /></td>
							</tr>';

		// If the user is allowed to hide their email address from the public give them the option to here.
		if ($context['allow_hide_email'])
		{
			echo '
							<tr>
								<td width="40%"><b>', $txt[721], '</b></td>
								<td><input type="hidden" name="hideEmail" value="0" /><input type="checkbox" name="hideEmail"', $context['member']['hide_email'] ? ' checked="checked"' : '', ' value="1" class="check" /></td>
							</tr>';
	}

		// Option to show online status - if they are allowed to.
		if ($context['allow_hide_online'])
		{
			echo '
							<tr>
								<td width="40%"><b>', $txt['show_online'], '</b></td>
								<td><input type="hidden" name="showOnline" value="0" /><input type="checkbox" name="showOnline"', $context['member']['show_online'] ? ' checked="checked"' : '', ' value="1" class="check" /></td>
							</tr>';
		}

		// Show boxes so that the user may change his or her password.
		echo '
							<tr>
								<td colspan="2"><hr width="100%" size="1" class="hrcolor" /></td>
							</tr><tr>
								<td width="40%"><b', (isset($context['modify_error']['bad_new_password']) ? ' style="color: #FF0000;"' : ''), '>', $txt[81], ': </b><div class="smalltext">', $txt[596], '</div></td>
								<td><input type="password" name="passwrd1" size="20" /></td>
							</tr><tr>
								<td width="40%"><b>', $txt[82], ': </b></td>
								<td><input type="password" name="passwrd2" size="20" /></td>
							</tr>';

		// This section allows the user to enter secret question/answer so they can reset a forgotten password.
		echo '
							<tr>
								<td colspan="2"><hr width="100%" size="1" class="hrcolor" /></td>
							</tr><tr>
								<td width="40%"><b>', $txt['pswd1'], ':</b><div class="smalltext">', $txt['secret_desc'], '</div></td>
								<td><input type="text" name="secretQuestion" size="50" value="', $context['member']['secret_question'], '" /></td>
							</tr><tr>
								<td width="40%"><b>', $txt['pswd2'], ':</b><div class="smalltext">', $txt['secret_desc2'], '</div></td>
								<td><input type="text" name="secretAnswer" size="20" /><span class="smalltext" style="margin-left: 4ex;"><a href="', $scripturl, '?action=helpadmin;help=secret_why_blank" onclick="return reqWin(this.href);">', $txt['secret_why_blank'], '</a></span></td>
							</tr>';
	}
	// Show the standard "Save Settings" profile button.
	template_profile_save();

	echo '
						</table>
					</td>
				</tr>
			</table>
		</form>';
}

// Template for forum specific options - avatar, signature etc.
function template_forumProfile()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt, $user_info;

	if (!empty($modSettings['avatar_allow_server_stored']))
		echo '
			<script language="JavaScript1.2" type="text/javascript"><!--
				function ready()
				{
					if (avatar.src.indexOf("blank.gif") > -1 && cat.selectedIndex != 0)
						changeSel(selavatar);
				}
			// --></script>';

	// The main containing header.
	echo '
		<form action="', $scripturl, '?action=profile2" method="post" name="creator" enctype="multipart/form-data">
			<table border="0" width="85%" cellspacing="1" cellpadding="4" align="center" class="bordercolor">
				<tr class="titlebg">
					<td height="26" align="left">
						&nbsp;<img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" border="0" align="top" />&nbsp;
						', $txt[79], '
					</td>
				</tr><tr class="windowbg">
					<td class="smalltext" height="25" align="left" style="padding: 2ex;">
						', $txt['forumProfile_info'], '
					</td>
				</tr><tr>
					<td class="windowbg2" align="left" style="padding-bottom: 2ex;">
						<table border="0" width="100%" cellpadding="5" cellspacing="0">';

	// This is the avatar selection table that is only displayed if avatars are enabled!
	if (!empty($modSettings['avatar_allow_server_stored']) || !empty($modSettings['avatar_allow_external_url']) || !empty($modSettings['avatar_allow_upload']))
	{
		// If users are allowed to choose avatars stored on the server show selection boxes to choice them from.
		if (!empty($modSettings['avatar_allow_server_stored']))
		{
			echo '
							<tr>
								<td width="40%" valign="top" style="padding: 0 2px;">
									<table width="100%" cellpadding="5" cellspacing="0" border="0" style="height: 25ex;"><tr>
										<td valign="top" width="20" class="windowbg"><input type="radio" name="avatar_choice" value="server_stored"', ($context['member']['avatar']['choice'] == 'server_stored' ? ' checked="checked"' : ''), ' class="check" /></td>
										<td valign="top" style="padding-left: 1ex;">
											<b', (isset($context['modify_error']['bad_avatar']) ? ' style="color: #FF0000;"' : ''), '>', $txt[229], ':</b>
											<div style="margin: 2ex;"><img name="avatar" id="avatar" src="', $modSettings['avatar_url'], '/blank.gif" alt="Do Nothing" /></div>
										</td>
									</tr></table>
								</td>
								<td>
									<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
										<td style="width: 20ex;">
											<select name="cat" id="cat" size="10" onchange="changeSel(\'\');" onfocus="selectRadioByName(document.creator.avatar_choice, \'server_stored\');">';
			// This lists all the file catergories.
			foreach ($context['avatars'] as $avatar)
				echo '
												<option value="', $avatar['filename'] . ($avatar['is_dir'] ? '/' : ''), '"', ($avatar['checked'] ? ' selected="selected"' : ''), '>', $avatar['name'], '</option>';
			echo '
											</select>
										</td>
										<td>
											<select name="file" id="file" size="10" style="display: none;" onchange="showAvatar()" onfocus="selectRadioByName(document.creator.avatar_choice, \'server_stored\');" disabled="disabled"><option></option></select>
										</td>
									</tr></table>
								</td>
							</tr>';
		}

		// If the user can link to an off server avatar, show them a box to input the address.
		if (!empty($context['member']['avatar']['allow_external']))
		{
			echo '
							<tr>
								<td valign="top" style="padding: 0 2px;">
									<table width="100%" cellpadding="5" cellspacing="0" border="0"><tr>
										<td valign="top" width="20" class="windowbg"><input type="radio" name="avatar_choice" value="external"', ($context['member']['avatar']['choice'] == 'external' ? ' checked="checked"' : ''), ' class="check" /></td>
										<td valign="top" style="padding-left: 1ex;"><b>', $txt[475], ':</b><div class="smalltext">', $txt[474], '</div></td>
									</tr></table>
								</td>
								<td valign="top">
									<input type="text" name="userpicpersonal" size="45" value="', $context['member']['avatar']['external'], '" onfocus="selectRadioByName(document.creator.avatar_choice, \'external\');" />
								</td>
							</tr>';
		}

		// If the user is able to upload avatars to the server show them an upload box.
		if (!empty($modSettings['avatar_allow_upload']))
			echo '
							<tr>
								<td valign="top" style="padding: 0 2px;">
									<table width="100%" cellpadding="5" cellspacing="0" border="0"><tr>
										<td valign="top" width="20" class="windowbg"><input type="radio" name="avatar_choice" value="upload"', ($context['member']['avatar']['choice'] == 'upload' ? 'checked="checked"' : ''), ' class="check" /></td>
										<td valign="top" style="padding-left: 1ex;"><b>', $txt['avatar_will_upload'], ':</b></td>
									</tr></table>
								</td>
								<td valign="top">
									', ($context['member']['avatar']['ID_ATTACH'] > 0 ? '<img src="' . $scripturl . '?action=dlattach;id=' . $context['member']['avatar']['ID_ATTACH'] . ';type=avatar" /><input type="hidden" name="ID_ATTACH" value="' . $context['member']['avatar']['ID_ATTACH'] . '" /><br /><br />' : ''), '
									<input type="file" size="48" name="attachment" value="" onfocus="selectRadioByName(document.creator.avatar_choice, \'upload\');" />
								</td>
							</tr>';
	}

	// Personal text...
	echo '
							<tr>
								<td width="40%"><b>', $txt[228], ': </b></td>
								<td><input type="text" name="personalText" size="50" maxlength="50" value="', $context['member']['blurb'], '" /></td>
							</tr>
							<tr>
								<td colspan="2"><hr width="100%" size="1" class="hrcolor" /></td>
							</tr>';

	// Gender, birthdate and location.
	echo '
							<tr>
								<td width="40%">
									<b>', $txt[563], ':</b>
									<div class="smalltext">', $txt[566], ' - ', $txt[564], ' - ', $txt[565], '</div>
								</td>
								<td class="smalltext">
									<input type="text" name="bday3" size="4" maxlength="4" value="', $context['member']['birth_date']['year'], '" /> -
									<input type="text" name="bday1" size="2" maxlength="2" value="', $context['member']['birth_date']['month'], '" /> -
									<input type="text" name="bday2" size="2" maxlength="2" value="', $context['member']['birth_date']['day'], '" />
								</td>
							</tr><tr>
								<td width="40%"><b>', $txt[227], ': </b></td>
								<td><input type="text" name="location" size="50" value="', $context['member']['location'], '" /></td>
							</tr>
							<tr>
								<td width="40%"><b>', $txt[231], ': </b></td>
								<td>
									<select name="gender" size="1">
										<option value="0"></option>
										<option value="1"', ($context['member']['gender']['name'] == 'm' ? ' selected="selected"' : ''), '>', $txt[238], '</option>
										<option value="2"', ($context['member']['gender']['name'] == 'f' ? ' selected="selected"' : ''), '>', $txt[239], '</option>
									</select>
								</td>
							</tr><tr>
								<td colspan="2"><hr width="100%" size="1" class="hrcolor" /></td>
							</tr>';

	// All the messenger type contact info.
	echo '
							<tr>
								<td width="40%"><b>', $txt[513], ': </b><div class="smalltext">', $txt[600], '</div></td>
								<td><input type="text" name="ICQ" size="24" value="', $context['member']['icq']['name'], '" /></td>
							</tr><tr>
								<td width="40%"><b>', $txt[603], ': </b><div class="smalltext">', $txt[601], '</div></td>
								<td><input type="text" name="AIM" maxlength="16" size="24" value="', $context['member']['aim']['name'], '" /></td>
							</tr><tr>
								<td width="40%"><b>', $txt['MSN'], ': </b><div class="smalltext">', $txt['smf237'], '.</div></td>
								<td><input type="text" name="MSN" size="24" value="', $context['member']['msn']['name'], '" /></td>
							</tr><tr>
								<td width="40%"><b>', $txt[604], ': </b><div class="smalltext">', $txt[602], '</div></td>
								<td><input type="text" name="YIM" maxlength="32" size="24" value="', $context['member']['yim']['name'], '" /></td>
							</tr><tr>
								<td colspan="2"><hr width="100%" size="1" class="hrcolor" /></td>
							</tr>';

	// Input box for custom titles, if they can edit it...
	if (!empty($modSettings['titlesEnable']) && $context['allow_edit_title'])
		echo '
							<tr>
								<td width="40%"><b>' . $txt['title1'] . ': </b></td>
								<td><input type="text" name="usertitle" size="50" value="' . $context['member']['title'] . '" /></td>
							</tr>';

	// Show the signature box.
	echo '
							<tr>
								<td width="40%" valign="top">
									<b>', $txt[85], ':</b>
									<div class="smalltext">', $txt[606], '</div><br />
									<br />';

	if ($context['show_spellchecking'])
		echo '
									<input type="button" value="', $txt['spell_check'], '" onclick="spellCheck(\'creator\', \'signature\');" />';

	echo '
								</td>
								<td>
									<textarea class="editor" onkeyup="calcCharLeft();" name="signature" rows="5" cols="50">', $context['member']['signature'], '</textarea><br />';

	// If there is a limit at all!
	if (!empty($context['max_signature_length']))
		echo '
									<span class="smalltext">', $txt[664], ' <span id="signatureLeft">', $context['max_signature_length'], '</span></span>';

	// Load the spell checker?
	if ($context['show_spellchecking'])
		echo '
									<script language="JavaScript1.2" type="text/javascript" src="', $settings['default_theme_url'], '/spellcheck.js"></script>';

	// Some javascript used to count how many characters have been used so far in the signature.
	echo '
									<script language="JavaScript" type="text/javascript"><!--
										function tick()
										{
											if (typeof(document.creator) != "undefined")
												calcCharLeft();
											setTimeout("tick()", 1000);
										}

										function calcCharLeft()
										{
											var maxLength = ', $context['max_signature_length'], ';
											var oldSignature = "", currentSignature = document.creator.signature.value;

											if (!document.getElementById("signatureLeft"))
												return;

											if (oldSignature != currentSignature)
											{
												oldSignature = currentSignature;

												if (currentSignature.replace(/\r/, "").length > maxLength)
													document.creator.signature.value = currentSignature.replace(/\r/, "").substring(0, maxLength);
												currentSignature = document.creator.signature.value.replace(/\r/, "");
											}

											setInnerHTML(document.getElementById("signatureLeft"), maxLength - currentSignature.length);
										}

										setTimeout("tick()", 1000);
									// --></script>
								</td>
							</tr>';

	// Website details.
	echo '
							<tr>
								<td colspan="2"><hr width="100%" size="1" class="hrcolor" /></td>
							</tr>
							<tr>
								<td width="40%"><b>', $txt[83], ': </b><div class="smalltext">', $txt[598], '</div></td>
								<td><input type="text" name="websiteTitle" size="50" value="', $context['member']['website']['title'], '" /></td>
							</tr><tr>
								<td width="40%"><b>', $txt[84], ': </b><div class="smalltext">', $txt[599], '</div></td>
								<td><input type="text" name="websiteUrl" size="50" value="', $context['member']['website']['url'], '" /></td>
							</tr>';

	// If karma is enabled let the admin edit it...
	if ($user_info['is_admin'] && !empty($modSettings['karmaMode']))
	{
		echo '
							<tr>
								<td colspan="2"><hr width="100%" size="1" class="hrcolor" /></td>
							</tr><tr>
								<td valign="top"><b>', $modSettings['karmaLabel'], '</b></td>
								<td>
									', $modSettings['karmaApplaudLabel'], ' <input type="text" name="karmaGood" size="4" value="', $context['member']['karma']['good'], '" onchange="setInnerHTML(document.getElementById(\'karmaTotal\'), this.value - this.form.karmaBad.value);" style="margin-right: 2ex;" /> ', $modSettings['karmaSmiteLabel'], ' <input type="text" name="karmaBad" size="4" value="', $context['member']['karma']['bad'], '" onchange="this.form.karmaGood.onchange();" /><br />
									(', $txt[94], ': <span id="karmaTotal">', ($context['member']['karma']['good'] - $context['member']['karma']['bad']), '</span>)
								</td>
							</tr>';
	}

	// Show the standard "Save Settings" profile button.
	template_profile_save();

	echo '
						</table>
					</td>
				</tr>
			</table>';

	/* If the admin has enabled choosing avatars stored on the server, the below javascript is used to update the
	   file listing of avatars as the user changes catergory. It also updates the preview image as they choose
	   different files on the select box. */
	if (!empty($modSettings['avatar_allow_server_stored']))
		echo '
			<script language="JavaScript1.2" type="text/javascript"><!--
				var files = ["' . implode('", "', $context['avatar_list']) . '"];
				var avatar = document.getElementById("avatar");
				var cat = document.getElementById("cat");
				var selavatar = "' . $context['avatar_selected'] . '";
				var avatardir = "' . $modSettings['avatar_url'] . '/";
				var size = avatar.alt.substr(3, 2) + " " + avatar.alt.substr(0, 2) + String.fromCharCode(117, 98, 116);
				var file = document.getElementById("file");

				changeSel(selavatar);

				function changeSel(selected)
				{
					if (cat.selectedIndex == -1)
						return;

					if (cat.options[cat.selectedIndex].value.indexOf("/") > 0)
					{
						var i;
						var count = 0;

						file.style.display = "inline";
						file.disabled = false;

						for (i = file.length; i >= 0; i = i - 1)
							file.options[i] = null;

						for (i = 0; i < files.length; i++)
							if (files[i].indexOf(cat.options[cat.selectedIndex].value) > -1)
							{
								var filename = files[i].substr(files[i].indexOf("/") + 1);
								var showFilename = filename.substr(0, filename.lastIndexOf("."));
								showFilename = showFilename.replace(/[_]/g, " ");

								file.options[count] = new Option(showFilename, files[i]);

								if (filename == selected)
								{
									if (file.options.defaultSelected)
										file.options[count].defaultSelected = true;
									else
										file.options[count].selected = true;
								}

								count++;
							}

						if (file.selectedIndex == -1 && file.options[0])
							file.options[0].selected = true;

						showAvatar();
					}
					else
					{
						file.style.display = "none";
						file.disabled = true;
						avatar.src = avatardir + cat.options[cat.selectedIndex].value;
					}
				}
				function showAvatar()
				{
					if (file.selectedIndex == -1)
						return;

					avatar.src = avatardir + file.options[file.selectedIndex].value;
					avatar.alt = file.options[file.selectedIndex].text;
					avatar.alt += file.options[file.selectedIndex].text == size ? "!" : "";
				}
			// --></script>';
	echo '
		</form>';

	if ($context['show_spellchecking'])
		echo '
		<form name="spell_form" id="spell_form" method="post" target="spellWindow" action="', $scripturl, '?action=spellcheck"><input type="hidden" name="spell_formname" value="" /><input type="hidden" name="spell_fieldname" value="" /><input type="hidden" name="spellstring" value="" /></form>';
}

// Template for showing theme settings.  Note: template_options() actually adds the theme specific options.
function template_theme()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt, $days;

	// The main containing header.
	echo '
		<form action="', $scripturl, '?action=profile2" method="post" name="creator">
			<table border="0" width="85%" cellspacing="1" cellpadding="4" align="center" class="bordercolor">
				<tr class="titlebg">
					<td height="26" align="left">
						&nbsp;<img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" border="0" align="top" />&nbsp;
						', $txt[79], '
					</td>
				</tr><tr class="windowbg">
					<td class="smalltext" height="25" align="left" style="padding: 2ex;">
						', $txt['theme_info'], '
					</td>
				</tr><tr>
					<td class="windowbg2" align="left" style="padding-bottom: 2ex;">
						<table border="0" width="100%" cellpadding="3">';

	// Are they allowed to change their theme?
	if ($modSettings['theme_allow'] || $context['user']['is_admin'])
	{
		echo '
							<tr>
								<td colspan="2" width="40%"><b>', $txt['theme1a'], ':</b> ', $context['member']['theme']['name'], ' <a href="', $scripturl, '?action=theme;sa=pick;u=', $context['member']['id'], ';sesc=', $context['session_id'], '">', $txt['theme1b'], '</a></td>
							</tr>';
	}

	// Are multiple smiley sets enabled?
	if (!empty($modSettings['smiley_sets_enable']))
	{
		echo '
							<tr>
								<td colspan="2" width="40%">
									<b>', $txt['smileys_current'], ':</b>
									<select name="smileySet" onchange="document.getElementById(\'smileypr\').src = this.selectedIndex == 0 ? \'', $settings['images_url'], '/blank.gif\' : \'', $modSettings['smileys_url'], '/\' + this.options[this.selectedIndex].value + \'/smiley.gif\';">';
		foreach ($context['smiley_sets'] as $set)
			echo '
										<option value="', $set['id'], '"', $set['selected'] ? ' selected="selected"' : '', '>', $set['name'], '</option>';
		echo '
									</select> <img id="smileypr" src="', $context['member']['smiley_set']['id'] != 'none' ? $modSettings['smileys_url'] . '/' . $context['member']['smiley_set']['id'] . '/smiley.gif' : $settings['images_url'] . '/blank.gif', '" alt=":)" align="top" style="padding-left: 20px;" />
								</td>
							</tr>';
	}

	if ($modSettings['theme_allow'] || $context['user']['is_admin'] || !empty($modSettings['smiley_sets_enable']))
		echo '
							<tr>
								<td colspan="2"><hr width="100%" size="1" class="hrcolor" /></td>
							</tr>';

	// Allow the user to change the way the time is displayed.
	echo '
							<tr>
								<td width="40%">
									<b>', $txt[486], ':</b><br />
									<a href="', $scripturl, '?action=helpadmin;help=time_format" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt[119], '" border="0" align="left" style="padding-right: 1ex;" /></a>
									<span class="smalltext">', $txt[479], '</span>
								</td>
								<td>
									<select name="easyformat" onchange="document.creator.timeFormat.value = this.options[this.selectedIndex].value;" style="margin-bottom: 4px;">';
	// Help the user by showing a list of common time formats.
	foreach ($context['easy_timeformats'] as $time_format)
		echo '
										<option value="', $time_format['format'], '"', $time_format['format'] == $context['member']['time_format'] ? ' selected="selected"' : '', '>', $time_format['title'], '</option>';
	echo '
									</select><br />
									<input type="text" name="timeFormat" value="', $context['member']['time_format'], '" size="30" />
								</td>
							</tr><tr>
								<td width="40%"><b', (isset($context['modify_error']['bad_offset']) ? ' style="color: #FF0000;"' : ''), '>', $txt[371], ':</b><div class="smalltext">', $txt[519], '</div></td>
								<td class="smalltext"><input type="text" name="timeOffset" size="5" maxlength="5" value="', $context['member']['time_offset'], '" /><br />', $txt[741], ': <i>', $context['current_forum_time'], '</i></td>
							</tr><tr>
								<td colspan="2"><hr width="100%" size="1" class="hrcolor" /></td>
							</tr>';

	echo '
							<tr>
								<td colspan="2">
									<table width="100%" cellspacing="0" cellpadding="3">
										<tr>
											<td colspan="2">
												<input type="hidden" name="default_options[show_board_desc]" value="0" />
												<label for="show_board_desc"><input type="checkbox" name="default_options[show_board_desc]" id="show_board_desc" value="1"', !empty($context['member']['options']['show_board_desc']) ? ' checked="checked"' : '', ' class="check" /> ', $txt[732], '</label>
											</td>
										</tr><tr>
											<td colspan="2">
												<input type="hidden" name="default_options[show_children]" value="0" />
												<label for="show_children"><input type="checkbox" name="default_options[show_children]" id="show_children" value="1"', !empty($context['member']['options']['show_children']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['show_children'], '</label>
											</td>
										</tr><tr>
											<td colspan="2">
												<input type="hidden" name="default_options[show_no_avatars]" value="0" />
												<label for="show_no_avatars"><input type="checkbox" name="default_options[show_no_avatars]" id="show_no_avatars" value="1"', !empty($context['member']['options']['show_no_avatars']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['show_no_avatars'], '</label>
											</td>
										</tr><tr>
											<td colspan="2">
												<input type="hidden" name="default_options[show_no_signatures]" value="0" />
												<label for="show_no_signatures"><input type="checkbox" name="default_options[show_no_signatures]" id="show_no_signatures" value="1"', !empty($context['member']['options']['show_no_signatures']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['show_no_signatures'], '</label>
											</td>
										</tr>';

	if ($settings['allow_no_censored'])
		echo '
										<tr>
											<td colspan="2">
												<input type="hidden" name="default_options[show_no_censored]" value="0" />
												<label for="show_no_censored"><input type="checkbox" name="default_options[show_no_censored]" id="show_no_censored" value="1"' . (!empty($context['member']['options']['show_no_censored']) ? ' checked="checked"' : '') . ' class="check" /> ' . $txt['show_no_censored'] . '</label>
											</td>
										</tr>';

	echo '
										<tr>
											<td colspan="2">
												<input type="hidden" name="default_options[return_to_post]" value="0" />
												<label for="return_to_post"><input type="checkbox" name="default_options[return_to_post]" id="return_to_post" value="1"', !empty($context['member']['options']['return_to_post']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['return_to_post'], '</label>
											</td>
										</tr><tr>
											<td colspan="2">
												<input type="hidden" name="default_options[view_newest_first]" value="0" />
												<label for="view_newest_first"><input type="checkbox" name="default_options[view_newest_first]" id="view_newest_first" value="1"', !empty($context['member']['options']['view_newest_first']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['recent_posts_at_top'], '</label>
											</td>
										</tr><tr>
											<td colspan="2">
												<input type="hidden" name="default_options[view_newest_pm_first]" value="0" />
												<label for="view_newest_pm_first"><input type="checkbox" name="default_options[view_newest_pm_first]" id="view_newest_pm_first" value="1"', !empty($context['member']['options']['view_newest_pm_first']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['recent_pms_at_top'], '</label>
											</td>
										</tr><tr>
											<td colspan="2"><label for="calendar_start_day">', $txt['calendar_start_day'], ':</label>
												<select name="default_options[calendar_start_day]" id="calendar_start_day">
													<option value="0"', empty($context['member']['options']['calendar_start_day']) ? ' selected="selected"' : '', '>', $days[0], '</option>
													<option value="1"', !empty($context['member']['options']['calendar_start_day']) ? ' selected="selected"' : '', '>', $days[1], '</option>
												</select>
											</td>
										</tr><tr>
											<td colspan="2"><label for="display_quick_reply">', $txt['display_quick_reply'], '</label>
												<select name="default_options[display_quick_reply]" id="display_quick_reply">
													<option value="0"', empty($context['member']['options']['display_quick_reply']) ? ' selected="selected"' : '', '>', $txt['display_quick_reply1'], '</option>
													<option value="1"', !empty($context['member']['options']['display_quick_reply']) && $context['member']['options']['display_quick_reply'] == 1 ? ' selected="selected"' : '', '>', $txt['display_quick_reply2'], '</option>
													<option value="2"', !empty($context['member']['options']['display_quick_reply']) && $context['member']['options']['display_quick_reply'] == 2 ? ' selected="selected"' : '', '>', $txt['display_quick_reply3'], '</option>
												</select>
											</td>
										</tr><tr>
											<td colspan="2"><label for="display_quick_mod">', $txt['display_quick_mod'], '</label>
												<select name="default_options[display_quick_mod]" id="display_quick_mod">
													<option value="0"', empty($context['member']['options']['display_quick_mod']) ? ' selected="selected"' : '', '>', $txt['display_quick_mod_none'], '</option>
													<option value="1"', !empty($context['member']['options']['display_quick_mod']) && $context['member']['options']['display_quick_mod'] == 1 ? ' selected="selected"' : '', '>', $txt['display_quick_mod_check'], '</option>
													<option value="2"', !empty($context['member']['options']['display_quick_mod']) && $context['member']['options']['display_quick_mod'] != 1 ? ' selected="selected"' : '', '>', $txt['display_quick_mod_image'], '</option>
												</select>
											</td>
										</tr>
									</table>
								</td>
							</tr>';

	// Show the standard "Save Settings" profile button.
	template_profile_save();

	echo '
						</table>
					</td>
				</tr>
			</table>
		</form>';
}

function template_notification()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	// The main containing header.
	echo '
			<table border="0" width="85%" cellspacing="1" cellpadding="4" align="center" class="bordercolor">
				<tr class="titlebg">
					<td height="26" align="left">
						&nbsp;<img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" border="0" align="top" />&nbsp;
						', $txt[79], '
					</td>
				</tr><tr class="windowbg">
					<td class="smalltext" height="25" align="left" style="padding: 2ex;">
						', $txt['notification_info'], '
					</td>
				</tr><tr>
					<td class="windowbg2" width="100%">
						<form action="', $scripturl, '?action=profile2" method="post" style="margin: 0;">';

	// Allow notification on announcements to be disabled?
	if (!empty($modSettings['notifyAnncmnts_UserDisable']))
		echo '
							<input type="hidden" name="notifyAnnouncements" value="0" /><input type="checkbox" id="notifyAnnouncements" name="notifyAnnouncements"', !empty($context['member']['notify_announcements']) ? ' checked="checked"' : '', ' class="check" />&nbsp;
							<label for="notifyAnnouncements">', $txt['notifyXAnn4'], '</label><br />';

	// More notification options.
	echo '
							<input type="hidden" name="notifyOnce" value="0" /><input type="checkbox" id="notifyOnce" name="notifyOnce"', !empty($context['member']['notify_once']) ? ' checked="checked"' : '', ' class="check" />&nbsp;
							<label for="notifyOnce">', $txt['notifyXOnce1'], '</label><br />

							<input type="hidden" name="default_options[auto_notify]" value="0" /><input type="checkbox" id="auto_notify" name="default_options[auto_notify]" value="1"', !empty($context['member']['options']['auto_notify']) ? ' checked="checked"' : '', ' class="check" />&nbsp;
							<label for="auto_notify">', $txt['auto_notify'], '</label><br />

							<div align="right">
								<input type="submit" style="margin: 0 1ex 1ex 1ex;" value="', $txt['notifyX1'], '" />
								<input type="hidden" name="sc" value="', $context['session_id'], '" />
								<input type="hidden" name="userID" value="', $context['member']['id'], '" />
								<input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
							</div>
						</form>
					</td>
				</tr>
			</table>
			<br />
			<table border="0" width="85%" cellspacing="0" cellpadding="0" align="center" class="bordercolor"><tr><td>
				<form action="', $scripturl, '?action=profile2" method="post" style="margin: 0;">
					<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
						<tr><td class="catbg" width="100%">', $txt['notifications_topics'], '</td></tr>
					</table>
					<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
						<tr>
							<td class="windowbg" width="20" valign="middle" align="center" rowspan="', $context['num_rows']['topic'], '">
								<img src="', $settings['images_url'], '/icons/notify_sm.gif" border="0" width="20" height="20" alt="" />
							</td>';
	if (!empty($context['topic_notifications']))
	{
		echo '
							<td class="titlebg" width="71%">' . $txt[70] . '</td>
							<td class="titlebg" width="24%">' . $txt[109] . '</td>
							<td class="titlebg" width="5%"><input type="checkbox" class="check" onclick="invertAll(this, this.form);" /></td>
						</tr>';
		foreach ($context['topic_notifications'] as $topic)
		{
			echo '
						<tr>
							<td class="windowbg" valign="middle" width="48%">
								', $topic['link'];

			if ($topic['new'])
				echo ' <a href="', $topic['new_href'], '"><img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/new.gif" alt="', $txt[302], '" border="0" /></a>';

			echo '<br />
								<span class="smalltext"><i>' . $txt['smf88'] . ' ' . $topic['board']['link'] . '</i></span>
							</td>
							<td class="windowbg2" valign="middle" width="14%">' . $topic['poster']['link'] . '</td>
							<td class="windowbg2" valign="middle" width="5%">
								<input type="checkbox" name="notify_topics[]" value="', $topic['id'], '" class="check" />
							</td>
						</tr>';
		}

		echo '
						<tr class="catbg">
							<td colspan="3">
								<b>', $txt[139], ':</b> ', $context['page_index'], '
							</td>
						</tr>
						<tr>
							<td colspan="3" class="windowbg2" align="right">
								<input type="submit" name="edit_notify_topics" value="', $txt['notifications_update'], '" />
							</td>
						</tr>';
	}
	else
		echo '
							<td width="100%" colspan="3" class="windowbg2">
								', $txt['notifications_topics_none'], '<br />
								<br />', $txt['notifications_topics_howto'], '<br />
								<br />
							</td>
						</tr>';
	echo '
					</table>
					<input type="hidden" name="sc" value="', $context['session_id'], '" />
					<input type="hidden" name="userID" value="', $context['member']['id'], '" />
					<input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
				</form>
			</td></tr></table><br />
			<table border="0" width="85%" cellspacing="0" cellpadding="0" align="center" class="bordercolor"><tr><td>
				<form action="', $scripturl, '?action=profile2" method="post" style="margin: 0;">
					<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
						<tr><td class="catbg" width="100%">', $txt['notifications_boards'], '</td></tr>
					</table>
					<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
						<tr>
							<td class="windowbg" width="20" valign="middle" align="center" rowspan="', $context['num_rows']['board'], '">
								<img src="', $settings['images_url'], '/icons/notify_sm.gif" border="0" width="20" height="20" alt="" />
							</td>';
	if (!empty($context['board_notifications']))
	{
		echo '
							<td class="titlebg" width="95%">' . $txt['smf82'] . '</td>
							<td class="titlebg" width="5%"><input type="checkbox" class="check" onclick="invertAll(this, this.form);" /></td>';
		foreach ($context['board_notifications'] as $board)
		{
			echo '
						<tr>
							<td class="windowbg" valign="middle" width="48%">', $board['link'];

		if ($board['new'])
			echo ' <a href="', $board['href'], '"><img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/new.gif" alt="', $txt[302], '" border="0" /></a>';

		echo '</td>
							<td class="windowbg2" valign="middle" width="5%">
								<input type="checkbox" name="notify_boards[]" value="', $board['id'], '" />
							</td>
						</tr>';
		}

		echo '
						<tr>
							<td colspan="2" class="windowbg2" align="right">
								<input type="submit" name="edit_notify_boards" value="', $txt['notifications_update'], '" />
							</td>
						</tr>';
	}
	else
		echo '
							<td width="100%" colspan="2" class="windowbg2">
								', $txt['notifications_boards_none'], '<br />
								<br />', $txt['notifications_boards_howto'], '<br />
								<br />
							</td>';
	echo '
						</tr>
					</table>
					<input type="hidden" name="sc" value="', $context['session_id'], '" />
					<input type="hidden" name="userID" value="', $context['member']['id'], '" />
					<input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
				</form>
			</td></tr></table><br />';
}

// Template for options related to personal messages.
function template_pmprefs()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt, $user_info;

	// The main containing header.
	echo '
		<form action="', $scripturl, '?action=profile2" method="post" name="creator">
			<table border="0" width="85%" cellspacing="0" cellpadding="4" align="center" class="tborder">
				<tr class="titlebg">
					<td height="26" align="left">
						&nbsp;<img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" border="0" align="top" />&nbsp;
						', $txt[79], '
					</td>
				</tr><tr class="windowbg">
					<td class="smalltext" align="left" style="padding: 2ex;">
						', $txt['pmprefs_info'], '
					</td>
				</tr>';

	// A text box for the user to input usernames of everyone they want to ignore personal messages from.
	echo '
				<tr>
					<td class="windowbg2" align="left" style="padding-bottom: 2ex;">
						<table border="0" width="100%" cellpadding="3">
							<tr>
								<td valign="top" align="left">
									<b>', $txt[325], ':</b>
									<div class="smalltext">
										', $txt[326], '<br />
										<br />
										<a href="', $scripturl, '?action=findmember;input=im_ignore_list;delim=\\\\n;sesc=', $context['session_id'], '" onclick="return reqWin(this.href, 350, 400);"><img src="', $settings['images_url'], '/icons/assist.gif" border="0" alt="', $txt['find_members'], '" align="middle" /> ', $txt['find_members'], '</a>
									</div>
								</td>
								<td>
									<textarea name="im_ignore_list" id="im_ignore_list" rows="10" cols="50">', $context['ignore_list'], '</textarea>
								</td>
							</tr>';

	// Extra options available to the user for personal messages.
	echo '
							<tr>
								<td colspan="2">
									<input type="hidden" name="im_email_notify" value="0" />
									<label for="im_email_notify"><input type="checkbox" name="im_email_notify" id="im_email_notify" value="1"', $context['send_email'] ? ' checked="checked"' : '', ' class="check" /> ', $txt[327], '</label><br />
									<input type="hidden" name="default_options[copy_to_outbox]" value="0" />
									<label for="copy_to_outbox"><input type="checkbox" name="default_options[copy_to_outbox]" id="copy_to_outbox" value="1"', !empty($context['member']['options']['copy_to_outbox']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['copy_to_outbox'], '</label><br />
									<input type="hidden" name="default_options[popup_messages]" value="0" />
									<label for="popup_messages"><input type="checkbox" name="default_options[popup_messages]" id="popup_messages" value="1"', !empty($context['member']['options']['popup_messages']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['popup_messages'], '</label><br />
								</td>
							</tr>';

	// Show the standard "Save Settings" profile button.
	template_profile_save();

	echo '
						</table>
					</td>
				</tr>
			</table>
		</form>';
}

// Template to show for deleting a users account - now with added delete post capability!
function template_deleteAccount()
{
	global $context, $settings, $options, $scripturl, $txt, $scripturl;

	// The main containing header.
	echo '
		<form action="', $scripturl, '?action=profile2" method="post" name="creator">
			<table border="0" width="85%" cellspacing="1" cellpadding="4" align="center" class="bordercolor">
				<tr class="titlebg">
					<td height="26" align="left">
						&nbsp;<img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" border="0" align="top" />&nbsp;
						', $txt['deleteAccount'], '
					</td>
				</tr>';
	// If deleting another account give them a lovely info box.
	if (!$context['member']['is_owner'])
	echo '
					<tr class="windowbg">
						<td class="smalltext" align="left" colspan="2" style="padding-top: 2ex; padding-bottom: 2ex;">
							', $txt['deleteAccount_desc'], '
						</td>
					</tr>';
	echo '
				<tr>
					<td class="windowbg2">
						<table width="100%" cellspacing="0" cellpadding="3"><tr>
							<td align="center" colspan="2">';

	// If the user is deleting their own account warn them first - and require a password!
	if ($context['member']['is_owner'])
	{
		echo '
								<span style="color: red;">', $txt['own_profile_confirm'], '</span><br /><br />
							</td>
						</tr><tr>
							<td class="windowbg2" align="right">
								<b', (isset($context['modify_error']['bad_password']) || isset($context['modify_error']['no_password']) ? ' style="color: #FF0000;"' : ''), '>', $txt['smf241'], ': </b>
							</td>
							<td class="windowbg2" align="left">
								<input type="password" name="oldpasswrd" size="20" />&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="submit" value="', $txt[163], '" />
								<input type="hidden" name="sc" value="', $context['session_id'], '" />
								<input type="hidden" name="userID" value="', $context['member']['id'], '" />
								<input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
							</td>';
	}
	// Otherwise an admin doesn't need to enter a password - but they still get a warning - plus the option to delete lovely posts!
	else
	{
		echo '						<div style="color: red; margin-bottom: 2ex;">', $txt['deleteAccount_warning'], '</span>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="center">
								', $txt['deleteAccount_posts'], ': <select name="remove_type">
									<option value="none">', $txt['deleteAccount_none'], '</option>
									<option value="posts">', $txt['deleteAccount_all_posts'], '</option>
									<option value="topics">', $txt['deleteAccount_topics'], '</option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="center">
								<label for="deleteAccount"><input type="checkbox" name="deleteAccount" id="deleteAccount" value="1" class="check" onclick="if (this.checked) return confirm(\'', $txt['deleteAccount_confirm'], '\');" /> ', $txt['deleteAccount_member'], '.</label>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="windowbg2" align="center" style="padding-top: 2ex;">
								<input type="submit" value="', $txt['smf138'], '" />
								<input type="hidden" name="sc" value="', $context['session_id'], '" />
								<input type="hidden" name="userID" value="', $context['member']['id'], '" />
								<input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
							</td>';
	}
	echo '
						</tr></table>
					</td>
				</tr>
			</table>
		</form>';
}

// Template for the password box/save button stuck at the bottom of every profile page.
function template_profile_save()
{
	global $context, $settings, $options, $txt;

	echo '
							<tr>
								<td colspan="2"><hr width="100%" size="1" class="hrcolor" /></td>
							</tr><tr>';

	// Only show the password box if it's actually needed.
	if ($context['user']['is_owner'] && $context['require_password'])
		echo '
								<td width="40%">
									<b', isset($context['modify_error']['bad_password']) || isset($context['modify_error']['no_password']) ? ' style="color: #FF0000;"' : '', '>', $txt['smf241'], ': </b>
									<div class="smalltext">', $txt['smf244'], '</div>
								</td>
								<td>
									<input type="password" name="oldpasswrd" size="20" style="margin-right: 4ex;" />';
	else
		echo '
								<td align="right" colspan="2">';

	echo '
									<input type="submit" value="', $txt[88], '" />
									<input type="hidden" name="sc" value="', $context['session_id'], '" />
									<input type="hidden" name="userID" value="', $context['member']['id'], '" />
									<input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
								</td>
							</tr>';
}

?>