<?php
// Version: 1.0; Recent

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl;

	foreach ($context['posts'] as $post)
	{
		echo '
			<table border="0" width="100%" cellspacing="1" class="bordercolor">
				<tr class="titlebg">
					<td align="left">
						&nbsp;' . $post['counter'] . '&nbsp;
					</td>
					<td width="75%">
						&nbsp;<a href="' . $scripturl . '#' . $post['category']['id'] . '">' . $post['category']['name'] . '</a> / <a href="' . $scripturl . '?board=' . $post['board']['id'] . '.0">' . $post['board']['name'] . '</a> / <a href="' . $scripturl . '?board=' . $post['board']['id'] . ';topic=' . $post['topic'] . '.' . $post['start'] . '#msg' . $post['id'] . '">' . $post['subject'] . '</a>
					</td>
					<td align="right" nowrap="nowrap">
						&nbsp;' . $txt[30] . ': ' . $post['time'] . '&nbsp;
					</td>
				</tr>
				<tr>
					<td class="catbg" colspan="3">
						' . $txt[109] . ' ' . $post['first_poster']['link'] . ' | ' . $txt[22] . ' ' . $txt[525] . ' ' . $post['poster']['link'] . '
					</td>
				</tr>
				<tr>
					<td class="windowbg2" colspan="3" valign="top" height="40">
						' . $post['message'] . '
					</td>
				</tr>
				<tr>
					<td class="windowbg" colspan="3" align="right">';

		if ($post['can_delete'])
			echo '
								<a href="', $scripturl, '?action=recent;delete=', $post['id'], ';sesc=', $context['session_id'], '" onclick="return confirm(\'', $txt[154], '?\');">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/delete.gif" alt="' . $txt[121] . '" border="0" />' : $txt[31]), '</a>';
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

		echo '</td>
				</tr>
			</table>
			<br />';
	}

	echo '
			<span class="smalltext"><a href="' . $scripturl . '">' . $txt[236] . '</a> ' . $txt[237] . '<br /></span>';
}

function template_unread()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
<table width="100%" border="0" cellspacing="0" cellpadding="3">
	<tr>
		<td>', theme_linktree(), '</td>
	</tr>
</table>

<table width="100%" cellpadding="3" cellspacing="0" border="0" class="tborder" style="margin-bottom: 1ex;">
	<tr>
		<td align="left" class="catbg" height="30">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td valign="middle"><b>' . $txt[139] . ':</b> ' . $context['page_index'] . '</td>', $settings['show_mark_read'] ? '
					<td align="right" nowrap="nowrap" style="font-size: smaller;"><a href="' . $scripturl . '?action=markasread;sa=all">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/markread.gif" alt="' . $txt[452] . '" border="0" />' : $txt[452]) . '</a></td>' : '', '
				</tr>
			</table>
		</td>
	</tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="0" class="bordercolor" style="margin-bottom: 1ex;">
	<tr><td>
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
			<tr class="titlebg">';
	if (!empty($context['topics']))
		echo '
				<td width="10%" colspan="2">&nbsp;</td>
				<td width="48%">' . $txt[70] . '</td>
				<td width="14%">' . $txt[109] . '</td>
				<td width="4%" align="center">' . $txt[110] . '</td>
				<td width="4%" align="center">' . $txt[301] . '</td>
				<td width="27%">' . $txt[111] . '</td>';
	else
		echo '
				<td width="100%" colspan="7">', $context['showing_all_topics'] ? $txt[151] : $txt['unread_topics_visit_none'], '</td>';
	echo '
			</tr>';

	foreach ($context['topics'] as $topic)
	{
		echo '
			<tr>
				<td class="windowbg2" valign="middle" align="center" width="6%">
					<img src="' . $settings['images_url'] . '/topic/' . $topic['class'] . '.gif" alt="" /></td>
				<td class="windowbg2" valign="middle" align="center" width="4%">
					<img src="' . $settings['images_url'] . '/post/' . $topic['first_post']['icon'] . '.gif" alt="" border="0" align="middle" /></td>
				<td class="windowbg" valign="middle" width="48%">
					' . $topic['first_post']['link'] . ' <a href="' . $scripturl . '?topic=' . $topic['id'] . '.from' . $topic['newtime'] . ';topicseen#new"><img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/new.gif" alt="' . $txt[302] . '" border="0" /></a> <span class="smalltext">' . $topic['pages'] . '</span>
					<div class="smalltext"><i>' . $txt['smf88'] . ' ' . $topic['board']['link'] . '</i></div></td>
				<td class="windowbg2" valign="middle" width="14%">
					' . $topic['first_post']['member']['link'] . '</td>
				<td class="windowbg" valign="middle" width="4%" align="center">
					' . $topic['replies'] . '</td>
				<td class="windowbg" valign="middle" width="4%" align="center">
					' . $topic['views'] . '</td>
				<td class="windowbg2" valign="middle" width="22%">';
		if ($settings['images_url'] != $settings['theme_url'] . '/images' || file_exists($settings['theme_dir'] . '/images/icons/last_post.gif'))
			echo '
					<a href="', $topic['last_post']['href'], '"><img src="', $settings['images_url'], '/icons/last_post.gif" alt="', $txt[111], '" title="', $txt[111], '" border="0" style="float: right;" /></a>';
		echo '
					<span class="smalltext">
						', $topic['last_post']['time'], '<br />
						', $txt[525], ' ', $topic['last_post']['member']['link'], '
					</span></td>
			</tr>';
	}

	if (!empty($context['topics']) && !$context['showing_all_topics'])
		echo '
			<tr class="titlebg">
				<td colspan="7" align="right"><a href="', $scripturl, '?action=unread;all">', $txt['unread_topics_all'], '</a></td>
			</tr>';

	echo '
		</table>
	</td></tr>
</table>

<table width="100%" cellpadding="3" cellspacing="0" border="0" class="tborder" style="margin-bottom: 1ex;">
	<tr>
		<td align="left" class="catbg" height="30">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td valign="middle"><b>' . $txt[139] . ':</b> ' . $context['page_index'] . '</td>', $settings['show_mark_read'] ? '
					<td align="right" nowrap="nowrap" style="font-size: smaller;"><a href="' . $scripturl . '?action=markasread;sa=all">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/markread.gif" alt="' . $txt[452] . '" border="0" />' : $txt[452]) . '</a></td>' : '', '
				</tr>
			</table>
		</td>
	</tr>
</table>

<table cellpadding="0" cellspacing="0" width="55%">
	<tr>
		<td class="smalltext" align="left" style="padding-top: 1ex;">', !empty($modSettings['enableParticipation']) ? '
			<img src="' . $settings['images_url'] . '/topic/my_normal_post.gif" alt="" align="middle" /> ' . $txt['participation_caption'] . '<br />' : '', '
			<img src="' . $settings['images_url'] . '/topic/normal_post.gif" alt="" align="middle" /> ' . $txt[457] . '<br />
			<img src="' . $settings['images_url'] . '/topic/hot_post.gif" alt="" align="middle" /> ' . $txt[454] . '<br />
			<img src="' . $settings['images_url'] . '/topic/veryhot_post.gif" alt="" align="middle" /> ' . $txt[455] . '
		</td>
		<td class="smalltext" align="left" valign="top" style="padding-top: 1ex;">
			<img src="' . $settings['images_url'] . '/topic/normal_post_locked.gif" alt="" align="middle" /> ' . $txt[456] . '<br />' . ($modSettings['enableStickyTopics'] == '1' ? '
			<img src="' . $settings['images_url'] . '/topic/normal_post_sticky.gif" alt="" align="middle" /> ' . $txt['smf96'] . '<br />' : '') . ($modSettings['pollMode'] == '1' ? '
			<img src="' . $settings['images_url'] . '/topic/normal_poll.gif" alt="" align="middle" /> ' . $txt['smf43'] : '') . '
		</td>
	</tr>
</table>';
}

function template_replies()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
<table width="100%" border="0" cellspacing="0" cellpadding="3" align="center">
	<tr>
		<td>', theme_linktree(), '</td>
	</tr>
</table>

<table width="100%" cellpadding="3" cellspacing="0" border="0" class="tborder" style="margin-bottom: 1ex;">
	<tr>
		<td align="left" class="catbg" height="30">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td valign="middle"><b>' . $txt[139] . ':</b> ' . $context['page_index'] . '</td>
					<td align="right" nowrap="nowrap" style="font-size: smaller;">';

	if (isset($context['topics_to_mark']) && !empty($settings['show_mark_read']))
		echo '
							<a href="' . $scripturl . '?action=markasread;sa=unreadreplies;topics=' . $context['topics_to_mark'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/markread.gif" alt="' . $txt[452] . '" border="0" />' : $txt[452]) . '</a>';

	echo '</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="0" class="bordercolor" style="margin-bottom: 1ex;">
	<tr><td>
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
			<tr class="titlebg">';
	if (!empty($context['topics']))
		echo '
				<td width="10%" colspan="2">&nbsp;</td>
				<td width="48%">' . $txt[70] . '</td>
				<td width="14%">' . $txt[109] . '</td>
				<td width="4%" align="center">' . $txt[110] . '</td>
				<td width="4%" align="center">' . $txt[301] . '</td>
				<td width="27%">' . $txt[111] . '</td>';
	else
		echo '
				<td width="100%" colspan="7">' . $txt[151] . '</td>';
	echo '
			</tr>';

	foreach ($context['topics'] as $topic)
	{
		echo '
			<tr>
				<td class="windowbg2" valign="middle" align="center" width="6%">
					<img src="' . $settings['images_url'] . '/topic/' . $topic['class'] . '.gif" alt="" /></td>
				<td class="windowbg2" valign="middle" align="center" width="4%">
					<img src="' . $settings['images_url'] . '/post/' . $topic['first_post']['icon'] . '.gif" alt="" border="0" align="middle" /></td>
				<td class="windowbg" valign="middle" width="48%">
					' . $topic['first_post']['link'] . ' <a href="' . $scripturl . '?topic=' . $topic['id'] . '.from' . $topic['newtime'] . ';topicseen#new"><img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/new.gif" alt="' . $txt[302] . '" border="0" /></a> <span class="smalltext">' . $topic['pages'] . '</span>
					<div class="smalltext"><i>' . $txt['smf88'] . ' ' . $topic['board']['link'] . '</i></div></td>
				<td class="windowbg2" valign="middle" width="14%">
					' . $topic['first_post']['member']['link'] . '</td>
				<td class="windowbg" valign="middle" width="4%" align="center">
					' . $topic['replies'] . '</td>
				<td class="windowbg" valign="middle" width="4%" align="center">
					' . $topic['views'] . '</td>
				<td class="windowbg2" valign="middle" width="22%">';
		if ($settings['images_url'] != $settings['theme_url'] . '/images' || file_exists($settings['theme_dir'] . '/images/icons/last_post.gif'))
			echo '
					<a href="', $topic['last_post']['href'], '"><img src="', $settings['images_url'], '/icons/last_post.gif" alt="', $txt[111], '" title="', $txt[111], '" border="0" style="float: right;" /></a>';
		echo '
					<span class="smalltext">
						', $topic['last_post']['time'], '<br />
						', $txt[525], ' ', $topic['last_post']['member']['link'], '
					</span></td>
			</tr>';
	}

	echo '
		</table>
	</td></tr>
</table>

<table width="100%" cellpadding="3" cellspacing="0" border="0" class="tborder" style="margin-bottom: 1ex;">
	<tr>
		<td align="left" class="catbg" height="30">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td valign="middle"><b>' . $txt[139] . ':</b> ' . $context['page_index'] . '</td>
					<td align="right" nowrap="nowrap" style="font-size: smaller;">';

	if (isset($context['topics_to_mark']) && !empty($settings['show_mark_read']))
		echo '
							<a href="' . $scripturl . '?action=markasread;sa=unreadreplies;topics=' . $context['topics_to_mark'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/markread.gif" alt="' . $txt[452] . '" border="0" />' : $txt[452]) . '</a>';

	echo '</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<table cellpadding="0" cellspacing="0" width="55%">
	<tr>
		<td class="smalltext" align="left" style="padding-top: 1ex;">
			<img src="' . $settings['images_url'] . '/topic/my_normal_post.gif" alt="" align="middle" /> ' . $txt[457] . '<br />
			<img src="' . $settings['images_url'] . '/topic/my_hot_post.gif" alt="" align="middle" /> ' . $txt[454] . '<br />
			<img src="' . $settings['images_url'] . '/topic/my_veryhot_post.gif" alt="" align="middle" /> ' . $txt[455] . '
		</td>
		<td class="smalltext" align="left" valign="top" style="padding-top: 1ex;">
			<img src="' . $settings['images_url'] . '/topic/my_normal_post_locked.gif" alt="" align="middle" /> ' . $txt[456] . '<br />' . ($modSettings['enableStickyTopics'] == '1' ? '
			<img src="' . $settings['images_url'] . '/topic/my_normal_post_sticky.gif" alt="" align="middle" /> ' . $txt['smf96'] . '<br />' : '') . ($modSettings['pollMode'] == '1' ? '
			<img src="' . $settings['images_url'] . '/topic/my_normal_poll.gif" alt="" align="middle" /> ' . $txt['smf43'] : '') . '
		</td>
	</tr>
</table>';
}

?>