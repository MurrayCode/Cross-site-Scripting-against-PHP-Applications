<?php
// Version: 1.0; MessageIndex

function template_main()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	echo '
<table width="100%" cellpadding="3" cellspacing="0">
	<tr>
		<td><a name="top"></a>', theme_linktree(), '</td>';
	if (!empty($settings['display_who_viewing']))
	{
		echo '
		<td class="smalltext" align="right">';
		if ($settings['display_who_viewing'] == 1)
			echo count($context['view_members']), ' ', count($context['view_members']) == 1 ? $txt['who_member'] : $txt[19];
		else
			echo empty($context['view_members_list']) ? '0 ' . $txt[19] : implode(', ', $context['view_members_list']) . (empty($context['view_num_hidden']) || $context['can_moderate_forum'] ? '' : ' (+ ' . $context['view_num_hidden'] . ' ' . $txt['hidden'] . ')');
		echo $txt['who_and'], $context['view_num_guests'], ' ', $context['view_num_guests'] == 1 ? $txt['guest'] : $txt['guests'], $txt['who_viewing_board'], '</td>';
	}
	echo '
	</tr>
</table>';

	if (isset($context['boards']) && (!empty($options['show_children']) || $context['start'] == 0))
	{
		echo '
<table border="0" width="100%" cellspacing="1" cellpadding="5" class="bordercolor">
	<tr class="titlebg">
		<td colspan="2">', $txt[20], '</td>
		<td width="6%" align="center">', $txt[330], '</td>
		<td width="6%" align="center">', $txt[21], '</td>
		<td width="22%" align="center">', $txt[22], '</td>
	</tr>';
		foreach ($context['boards'] as $board)
		{
			echo '
	<tr>
		<td class="windowbg" width="6%" align="center" valign="top">', $board['new'] ? '<img src="' . $settings['images_url'] . '/on.gif" alt="' . $txt[333] . '" title="' . $txt[333] . '" border="0" />' : '<img src="' . $settings['images_url'] . '/off.gif" alt="' . $txt[334] . '" title="' . $txt[334] . '" border="0" />', '</td>
		<td class="windowbg2" align="left" width="60%">
			<a name="b' . $board['id'] . '"></a>
			<b>' . $board['link'] . '</b><br />
			' . $board['description'];

			if (!empty($board['moderators']))
				echo '<br />
			<i class="smalltext">', count($board['moderators']) == 1 ? $txt[298] : $txt[299], ': ', implode(', ', $board['link_moderators']), '</i>';

			if (!empty($board['children']))
			{
				$children = array();
				foreach ($board['children'] as $child)
				{
					if ($child['new'])
						$children[] = '<b>' . $child['link'] . '</b>';
					else
						$children[] = $child['link'];
				}

				echo '
			<br />
			<br /><i class="smalltext">', $txt['parent_boards'], ': ', implode(', ', $children), '</i>';
			}

			echo '
		</td>
		<td class="windowbg" valign="middle" align="center" width="6%">
			', $board['topics'], '
		</td>
		<td class="windowbg" valign="middle" align="center" width="6%">
			', $board['posts'], '
		</td>
		<td class="windowbg2" valign="middle" width="22%">
			<span class="smalltext">
				', $board['last_post']['time'], '<br />
				', $txt['smf88'], ' ', $board['last_post']['link'], '<br />
				', $txt[525], ' ', $board['last_post']['member']['link'], '
			</span>
		</td>
	</tr>';
		}
		echo '
</table>
<br />';
	}

	if (!empty($options['show_board_desc']) && $context['description'] != '')
	{
		echo '
<table width="100%" cellpadding="6" cellspacing="0" border="0" class="tborder" style="border-width: 1px 1px 0 1px;">
	<tr>
		<td align="left" class="catbg" width="100%" height="24">
			<span class="smalltext">', $context['description'], '</span>
		</td>
	</tr>
</table>';
	}
	echo '
<table width="100%" cellpadding="3" cellspacing="0" border="0" class="tborder" style="border-width: 1px 1px 0 1px;">
	<tr>
		<td align="left" class="catbg" width="100%" height="30">
			<table cellpadding="3" cellspacing="0" width="100%">
				<tr>
					<td><b>', $txt[139], ':</b> ', $context['page_index'], $modSettings['topbottomEnable'] ? $context['menu_separator'] . '<a href="#bot">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/go_down.gif" alt="' . $txt['topbottom5'] . '" border="0" align="top" />' : $txt['topbottom5']) . '</a>' : '', '</td>
					<td align="right" nowrap="nowrap" style="font-size: smaller;">', theme_show_buttons(), '</td>
				</tr>
			</table>
		</td>
	</tr>
</table>';

	// If Quick Moderation is enabled (and set to checkboxes - 1) start the form.
	if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && !empty($context['topics']))
		echo '
<form action="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], '" method="post" name="topicForm" style="margin: 0;">';

	echo '
<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
	<tr class="titlebg">';
	if (!empty($context['topics']))
	{
		echo '
		<td width="9%" colspan="2"></td>
		<td width="42%"><a href="', $scripturl, '?board=', $context['current_board'], '.', $context['start'], ';sort=subject', $context['sort_by'] == 'subject' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt[70], $context['sort_by'] == 'subject' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a></td>
		<td width="14%"><a href="', $scripturl, '?board=', $context['current_board'], '.', $context['start'], ';sort=starter', $context['sort_by'] == 'starter' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt[109], $context['sort_by'] == 'starter' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a></td>
		<td width="4%" align="center"><a href="', $scripturl, '?board=', $context['current_board'], '.', $context['start'], ';sort=replies', $context['sort_by'] == 'replies' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt[110], $context['sort_by'] == 'replies' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a></td>
		<td width="4%" align="center"><a href="', $scripturl, '?board=', $context['current_board'], '.', $context['start'], ';sort=views', $context['sort_by'] == 'views' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt[301], $context['sort_by'] == 'views' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a></td>
		<td width="22%"><a href="', $scripturl, '?board=', $context['current_board'], '.', $context['start'], ';sort=last_post', $context['sort_by'] == 'last_post' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt[111], $context['sort_by'] == 'last_post' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a></td>';
		if (!empty($options['display_quick_mod']))
			echo '
		<td width="8%" valign="middle" align="center">', $options['display_quick_mod'] != 1 ? '&nbsp;' : '
			<input type="checkbox" onclick="invertAll(this, this.form, \'topics[]\');" class="check" />
		', '</td>';
	}
	else
		echo '
		<td width="100%" colspan="7"><b>', $txt[151], '</b></td>';
	echo '
	</tr>';

	foreach ($context['topics'] as $topic)
	{
		echo '
	<tr>
		<td class="windowbg2" valign="middle" align="center" width="5%">
			<img src="' . $settings['images_url'] . '/topic/' . $topic['class'] . '.gif" alt="" /></td>
		<td class="windowbg2" valign="middle" align="center" width="4%">
			<img src="' . $settings['images_url'] . '/post/' . $topic['first_post']['icon'] . '.gif" alt="" border="0" align="middle" /></td>
		<td class="windowbg" valign="middle" width="42%">
			' . $topic['first_post']['link'] . ' ' . ($topic['new'] && $context['user']['is_logged'] ? '<a href="' . $scripturl . '?topic=' . $topic['id'] . '.from' . $topic['newtime'] . '#new"><img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/new.gif" alt="' . $txt[302] . '" border="0" /></a>' : '') . ' <span class="smalltext">' . $topic['pages'] . '</span></td>
		<td class="windowbg2" valign="middle" width="14%">
			' . $topic['first_post']['member']['link'] . '</td>
		<td class="windowbg" valign="middle" width="4%" align="center">
			' . $topic['replies'] . '</td>
		<td class="windowbg" valign="middle" width="4%" align="center">
			' . $topic['views'] . '</td>
		<td class="windowbg2" valign="middle" width="22%">
			<span class="smalltext">' . $topic['last_post']['time'] . '<br />' . $txt[525] . ' ' . $topic['last_post']['member']['link'] . '</span></td>';

		// Show the quick moderation options?
		if (!empty($options['display_quick_mod']))
		{
			echo '
		<td class="windowbg" valign="middle" align="center" width="8%">';
			if ($options['display_quick_mod'] == 1 && ($topic['quick_mod']['remove'] || $topic['quick_mod']['lock'] || $topic['quick_mod']['sticky'] || $topic['quick_mod']['move']))
				echo '
			<input type="checkbox" name="topics[]" value="', $topic['id'], '" class="check" />';
			else
			{
				if ($topic['quick_mod']['remove'])
					echo '<a href="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], ';actions[', $topic['id'], ']=remove;sesc=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');"><img src="', $settings['images_url'], '/icons/quick_remove.gif" width="16" alt="', $txt[63], '" title="', $txt[63], '" border="0" /></a>';
				if ($topic['quick_mod']['lock'])
					echo '<a href="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], ';actions[', $topic['id'], ']=lock;sesc=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');"><img src="', $settings['images_url'], '/icons/quick_lock.gif" width="16" alt="', $txt['smf279'], '" title="', $txt['smf279'], '" border="0" /></a>';
				if ($topic['quick_mod']['lock'] || $topic['quick_mod']['remove'])
					echo '<br />';
				if ($topic['quick_mod']['sticky'])
					echo '<a href="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], ';actions[', $topic['id'], ']=sticky;sesc=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');"><img src="', $settings['images_url'], '/icons/quick_sticky.gif" width="16" alt="', $txt['smf277'], '" title="', $txt['smf277'], '" border="0" /></a>';
				if ($topic['quick_mod']['move'])
					echo '<a href="', $scripturl, '?action=movetopic;board=', $context['current_board'], '.', $context['start'], ';topic=', $topic['id'], '.0"><img src="', $settings['images_url'], '/icons/quick_move.gif" width="16" alt="', $txt[132], '" title="', $txt[132], '" border="0" /></a>';
			}
			echo '</td>';
		}
		echo '
	</tr>';
	}

	if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && !empty($context['topics']))
	{
		echo '
	<tr class="titlebg">
		<td colspan="8" align="right">
			<select name="qaction"', $context['can_move'] ? ' onchange="document.topicForm.moveItTo.disabled = (this.options[this.selectedIndex].value != \'move\');"' : '', '>
				<option value="">--------</option>
				', $context['can_remove'] ? '<option value="remove">' . $txt['quick_mod_remove'] . '</option>' : '', '
				', $context['can_lock'] ? '<option value="lock">' . $txt['quick_mod_lock'] . '</option>' : '', '
				', $context['can_sticky'] ? '<option value="sticky">' . $txt['quick_mod_sticky'] . '</option>' : '', '
				', $context['can_move'] ? '<option value="move">' . $txt['quick_mod_move'] . ': </option>' : '', '
				', $context['can_merge'] ? '<option value="merge">' . $txt['quick_mod_merge'] . '</option>' : '', '
			</select>';
		if ($context['can_move'])
		{
			echo '
			<select id="moveItTo" name="move_to" disabled="disabled">';
			foreach ($context['jump_to'] as $category)
				foreach ($category['boards'] as $board)
				{
					if (!$board['is_current'])
						echo '
				<option value="', $board['id'], '"', !empty($board['selected']) ? ' selected="selected"' : '', '>', str_repeat('-', $board['child_level'] + 1), ' ', $board['name'], '</option>';
				}
			echo '
			</select>';
		}
		echo '
			<input type="submit" value="', $txt['quick_mod_go'], '" onclick="return document.topicForm.qaction.value != \'\' &amp;&amp; confirm(\'', $txt['quickmod_confirm'], '\');" />
		</td>
	</tr>';
	}

	echo '
</table>';

	// Finish off the form - again, if Quick Moderation is being done with checkboxes. (1)
	if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && !empty($context['topics']))
		echo '
	<input type="hidden" name="sc" value="' . $context['session_id'] . '" />
</form>';

	echo '
<table width="100%" cellpadding="3" cellspacing="0" border="0" class="tborder" style="border-width: 0 1px 1px 1px;">
	<tr>
		<td align="left" class="catbg" width="100%" height="30">
			<table cellpadding="3" cellspacing="0" width="100%">
				<tr>
					<td><a name="bot"></a><b>', $txt[139], ':</b> ', $context['page_index'], $modSettings['topbottomEnable'] ? $context['menu_separator'] . '<a href="#top">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/go_up.gif" alt="' . $txt['topbottom4'] . '" border="0" align="top" />' : $txt['topbottom4']) . '</a>' : '', '</td>
					<td align="right" nowrap="nowrap" style="font-size: smaller;">', theme_show_buttons(), '</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0" width="100%">';
	if ($settings['linktree_inline'])
		echo '
	<tr>
		<td colspan="3" valign="bottom">', theme_linktree(), '<br /><br /></td>
	</tr>';
	echo '
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
		<td class="smalltext" align="right" valign="middle">
			<form action="', $scripturl, '" method="get" name="jumptoForm">
				<label for="jumpto">' . $txt[160] . '</label>:
				<select name="jumpto" id="jumpto" onchange="if (this.options[this.selectedIndex].value) window.location.href=\'', $scripturl, '\' + this.options[this.selectedIndex].value;">
					<option value="">' . $txt[251] . ':</option>';

	// Show each category - they all have an id, name, and the boards in them.
	foreach ($context['jump_to'] as $category)
	{
		// Show the category name with a link to the category. (index.php#id)
		echo '
					<option value="" disabled="disabled">-----------------------------</option>
					<option value="#', $category['id'], '">', $category['name'], '</option>
					<option value="" disabled="disabled">-----------------------------</option>';

		/* Now go through each board - they all have:
			id, name, child_level (how many parents they have, basically...), and is_current. (is this the current board?) */
		foreach ($category['boards'] as $board)
		{
			// Show some more =='s if this is a child, so as to make it look nice.
			echo '
					<option value="?board=', $board['id'], '.0"', $board['is_current'] ? ' selected="selected"' : '', '> ', str_repeat('==', $board['child_level']), '=> ', $board['name'], '</option>';
		}
	}

	echo '
				</select>&nbsp;
				<input type="button" value="', $txt[161], '" onclick="if (document.jumptoForm.jumpto.options[document.jumptoForm.jumpto.selectedIndex].value) window.location.href = \'', $scripturl, '\' + document.jumptoForm.jumpto.options[document.jumptoForm.jumpto.selectedIndex].value;" />
			</form>
		</td>
	</tr>
</table>';
}

function theme_show_buttons()
{
	global $context, $settings, $options, $txt, $scripturl;

	$buttonArray = array();

	// If they are logged in, and the mark read buttons are enabled..
	if ($context['user']['is_logged'] && $settings['show_mark_read'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=markasread;sa=board;board=' . $context['current_board'] . '.0">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/markread.gif" alt="' . $txt[300] . '" border="0" />' : $txt[300]) . '</a>';

	// If the user has permission to show the notification button... ask them if they're sure, though.
	if ($context['can_mark_notify'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=notifyboard;sa=' . ($context['is_marked_notify'] ? 'off' : 'on') . ';board=' . $context['current_board'] . '.' . $context['start'] . ';sesc=' . $context['session_id'] . '" onclick="return confirm(\'' . ($context['is_marked_notify'] ? $txt['notification_disable_board'] : $txt['notification_enable_board']) . '\');">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/notify.gif" alt="' . $txt[131] . '" border="0" />' : $txt[131]) . '</a>';

	// Are they allowed to post new topics?
	if ($context['can_post_new'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=post;board=' . $context['current_board'] . '.0">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/new_topic.gif" alt="' . $txt[33] . '" border="0" />' : $txt[33]) . '</a>';

	// How about new polls, can the user post those?
	if ($context['can_post_poll'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=post;board=' . $context['current_board'] . '.0;poll">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/new_poll.gif" alt="' . $txt['smf20'] . '" border="0" />' : $txt['smf20']) . '</a>';

	return implode($context['menu_separator'], $buttonArray);
}

?>