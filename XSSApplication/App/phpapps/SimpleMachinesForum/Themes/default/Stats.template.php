<?php
// Version: 1.0; Stats

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
		<table width="100%" cellpadding="3" cellspacing="0">
			<tr>
				<td>', theme_linktree(), '</td>
			</tr>
		</table>
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
			<tr class="titlebg">
				<td align="center" colspan="4">', $context['page_title'], '</td>
			</tr>
			<tr>
				<td class="catbg" colspan="4"><b>', $txt['smf_stats_2'], '</b></td>
			</tr><tr>
				<td class="windowbg" width="20" valign="middle" align="center"><img src="', $settings['images_url'], '/stats_info.gif" border="0" width="20" height="20" alt="" /></td>
				<td class="windowbg2" valign="top">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">
						<tr>
							<td nowrap="nowrap">', $txt[488], ':</td>
							<td align="right">', $context['show_member_list'] ? '<a href="' . $scripturl . '?action=mlist">' . $context['num_members'] . '</a>' : $context['num_members'], '</td>
						</tr><tr>
							<td nowrap="nowrap">', $txt[489], ':</td>
							<td align="right">', $context['num_posts'], '</td>
						</tr><tr>
							<td nowrap="nowrap">', $txt[490], ':</td>
							<td align="right">', $context['num_topics'], '</td>
						</tr><tr>
							<td nowrap="nowrap">', $txt[658], ':</td>
							<td align="right">', $context['num_categories'], '</td>
						</tr><tr>
							<td nowrap="nowrap">', $txt['users_online'], ':</td>
							<td align="right">', $context['users_online'], '</td>
						</tr><tr>
							<td nowrap="nowrap" valign="top">', $txt[888], ':</td>
							<td align="right">', $context['most_members_online']['number'], ' - ', $context['most_members_online']['date'], '</td>
						</tr><tr>
							<td nowrap="nowrap">', $txt['users_online_today'], ':</td>
							<td align="right">', $context['online_today'], '</td>
						</tr>
					</table>
				</td>
				<td class="windowbg" width="20" valign="middle" align="center"><img src="', $settings['images_url'], '/stats_info.gif" border="0" width="20" height="20" alt="" /></td>
				<td class="windowbg2" valign="top">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">
						<tr>
							<td nowrap="nowrap">', $txt['average_members'], ':</td>
							<td align="right">', $context['average_members'], '</td>
						</tr><tr>
							<td nowrap="nowrap">', $txt['average_posts'], ':</td>
							<td align="right">', $context['average_posts'], '</td>
						</tr><tr>
							<td nowrap="nowrap">', $txt['average_topics'], ':</td>
							<td align="right">', $context['average_topics'], '</td>
						</tr><tr>
							<td nowrap="nowrap">', $txt[665], ':</td>
							<td align="right">', $context['num_boards'], '</td>
						</tr><tr>
							<td nowrap="nowrap">', $txt[656], ':</td>
							<td align="right">', $context['latest_member']['link'], '</td>
						</tr><tr>
							<td nowrap="nowrap">', $txt['average_online'], ':</td>
							<td align="right">', $context['average_online'], '</td>
						</tr><tr>
							<td nowrap="nowrap">', $txt['gender_ratio'], ':</td>
							<td align="right">', $context['gender']['ratio'], '</td>
						</tr>
					</table>
				</td>
			</tr><tr>
				<td class="catbg" colspan="2" width="50%"><b>', $txt['smf_stats_3'], '</b></td>
				<td class="catbg" colspan="2" width="50%"><b>', $txt['smf_stats_4'], '</b></td>
			</tr><tr>
				<td class="windowbg" width="20" valign="middle" align="center"><img src="', $settings['images_url'], '/stats_posters.gif" border="0" width="20" height="20" alt="" /></td>
				<td class="windowbg2" width="50%" valign="top">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">';
	foreach ($context['top_posters'] as $poster)
		echo '
						<tr>
							<td width="60%" valign="top">', $poster['link'], '</td>
							<td width="20%" align="left" valign="top">', $poster['num_posts'] > 0 ? '<img src="' . $settings['images_url'] . '/bar.gif" width="' . $poster['post_percent'] . '" height="15" alt="" border="0" />' : '&nbsp;', '</td>
							<td width="20%" align="right" valign="top">', $poster['num_posts'], '</td>
						</tr>';
	echo '
					</table>
				</td>
				<td class="windowbg" width="20" valign="middle" align="center"><img src="', $settings['images_url'], '/stats_board.gif" width="20" height="20" alt="" border="0" /></td>
				<td class="windowbg2" width="50%" valign="top">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">';
	foreach ($context['top_boards'] as $board)
		echo '
						<tr>
							<td width="60%" valign="top">', $board['link'], '</td>
							<td width="20%" align="left" valign="top">', $board['num_posts'] > 0 ? '<img src="' . $settings['images_url'] . '/bar.gif" width="' . $board['post_percent'] . '" height="15" alt="" border="0" />' : '&nbsp;', '</td>
							<td width="20%" align="right" valign="top">', $board['num_posts'], '</td>
						</tr>';
	echo '
					</table>
				</td>
			</tr><tr>
				<td class="catbg" colspan="2" width="50%"><b>', $txt['smf_stats_11'], '</b></td>
				<td class="catbg" colspan="2" width="50%"><b>', $txt['smf_stats_12'], '</b></td>
			</tr><tr>
				<td class="windowbg" width="20" valign="middle" align="center"><img src="', $settings['images_url'], '/stats_replies.gif" border="0" width="20" height="20" alt="" /></td>
				<td class="windowbg2" width="50%" valign="top">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">';
	foreach ($context['top_topics_replies'] as $topic)
		echo '
						<tr>
							<td width="60%" valign="top">', $topic['link'], '</td>
							<td width="20%" align="left" valign="top">', $topic['num_replies'] > 0 ? '<img src="' . $settings['images_url'] . '/bar.gif" width="' . $topic['post_percent'] . '" height="15" alt="" border="0" />' : '&nbsp;', '</td>
							<td width="20%" align="right" valign="top">', $topic['num_replies'], '</td>
						</tr>';
	echo '
					</table>
				</td>
				<td class="windowbg" width="20" valign="middle" align="center"><img src="', $settings['images_url'], '/stats_views.gif" width="20" height="20" alt="" border="0" /></td>
				<td class="windowbg2" width="50%" valign="top">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">';
	foreach ($context['top_topics_views'] as $topic)
		echo '
						<tr>
							<td width="60%" valign="top">', $topic['link'], '</td>
							<td width="20%" align="left" valign="top">', $topic['num_views'] > 0 ? '<img src="' . $settings['images_url'] . '/bar.gif" width="' . $topic['post_percent'] . '" height="15" alt="" border="0" />' : '&nbsp;', '</td>
							<td width="20%" align="right" valign="top">', $topic['num_views'], '</td>
						</tr>';
	echo '
					</table>
				</td>
			</tr><tr>
				<td class="catbg" colspan="2" width="50%"><b>', $txt['smf_stats_15'], '</b></td>
				<td class="catbg" colspan="2" width="50%"><b>', $txt['smf_stats_16'], '</b></td>
			</tr><tr>
				<td class="windowbg" width="20" valign="middle" align="center"><img src="', $settings['images_url'], '/stats_replies.gif" border="0" width="20" height="20" alt="" /></td>
				<td class="windowbg2" width="50%" valign="top">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">';
	foreach ($context['top_starters'] as $poster)
		echo '
						<tr>
							<td width="60%" valign="top">', $poster['link'], '</td>
							<td width="20%" align="left" valign="top">', $poster['num_topics'] > 0 ? '<img src="' . $settings['images_url'] . '/bar.gif" width="' . $poster['post_percent'] . '" height="15" alt="" border="0" />' : '&nbsp;', '</td>
							<td width="20%" align="right" valign="top">', $poster['num_topics'], '</td>
						</tr>';
	echo '
					</table>
				</td>
				<td class="windowbg" width="20" valign="middle" align="center" nowrap="nowrap"><img src="', $settings['images_url'], '/stats_views.gif" width="20" height="20" alt="" border="0" /></td>
				<td class="windowbg2" width="50%" valign="top">
					<table border="0" cellpadding="1" cellspacing="0" width="100%">';
	foreach ($context['top_time_online'] as $poster)
		echo '
						<tr>
							<td width="60%" valign="top">', $poster['link'], '</td>
							<td width="20%" align="left" valign="top">', $poster['time_online'] > 0 ? '<img src="' . $settings['images_url'] . '/bar.gif" width="' . $poster['time_percent'] . '" height="15" alt="" border="0" />' : '&nbsp;', '</td>
							<td width="20%" align="right" valign="top" nowrap="nowrap">', $poster['time_online'], '</td>
						</tr>';
	echo '
					</table>
				</td>
			</tr><tr>
				<td class="catbg" colspan="4"><b>', $txt['smf_stats_5'], '</b></td>
			</tr><tr>
				<td class="windowbg" width="20" valign="middle" align="center"><img src="', $settings['images_url'], '/stats_history.gif" border="0" width="20" height="20" alt="" /></td>
				<td class="windowbg2" colspan="4">';

	if (!empty($context['monthly']))
	{
		echo '
					<table border="0" width="100%" cellspacing="1" cellpadding="4" class="tborder" style="margin-bottom: 1ex;">
						<tr class="titlebg" valign="middle" align="center">
							<td>', $txt['smf_stats_13'], '</td>
							<td>', $txt['smf_stats_7'], '</td>
							<td>', $txt['smf_stats_8'], '</td>
							<td>', $txt['smf_stats_9'], '</td>
							<td>', $txt['smf_stats_14'], '</td>';
		if (!empty($modSettings['hitStats']))
			echo '
							<td>', $txt['smf_stats_10'], '</td>';
		echo '
						</tr>';

		foreach ($context['monthly'] as $month)
		{
			echo '
						<tr class="windowbg2" valign="middle">
							<th align="left">
								<a name="', $month['date']['year'], $month['date']['month'], '" href="', $month['href'], '"><img src="', $settings['images_url'], '/', $month['expanded'] ? 'collapse.gif' : 'expand.gif', '" alt="" border="0" /></a> ', $month['link'], '
							</th>
							<th align="center">', $month['new_topics'], '</th>
							<th align="center">', $month['new_posts'], '</th>
							<th align="center">', $month['new_members'], '</th>
							<th align="center">', $month['most_members_online'], '</th>';
			if (!empty($modSettings['hitStats']))
				echo '
							<th align="center">', $month['hits'], '</th>';
			echo '
						</tr>';

			if ($month['expanded'])
			{
				foreach ($month['days'] as $day)
				{
					echo '
						<tr class="windowbg2" valign="middle" align="left">
							<td align="left" style="padding-left: 3ex;">', $day['year'], '-', $day['month'], '-', $day['day'], '</td>
							<td align="center">', $day['new_topics'], '</td>
							<td align="center">', $day['new_posts'], '</td>
							<td align="center">', $day['new_members'], '</td>
							<td align="center">', $day['most_members_online'], '</td>';
					if (!empty($modSettings['hitStats']))
						echo '
							<td align="center">', $day['hits'], '</td>';
					echo '
						</tr>';
				}
			}
		}
		echo '
					</table>';
	}

	echo '
				</td>
			</tr>
		</table>';
}

?>