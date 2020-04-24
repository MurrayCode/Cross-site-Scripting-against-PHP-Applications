<?php
// Version: 1.0; ManageMembers

function template_main()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
		<div class="tborder">
			<div style="padding: 1px;">
				<table width="100%" cellspacing="0" cellpadding="4" border="0">
					<tr class="titlebg">
						<td><a href="' . $scripturl . '?action=helpadmin;help=membergroups" onclick="return reqWin(this.href);" class="help"><img src="' . $settings['images_url'] . '/helptopics.gif" alt="' . $txt[119] . '" border="0" align="top" /></a> ' . $txt['membergroups_title'] . '</td>
					</tr><tr class="windowbg">
						<td class="smalltext" style="padding: 2ex;">', $txt['membergroups_description'], '</td>
					</tr>
				</table>
			</div>

			<form action="' . $scripturl . '?action=membergroups;sa=add" method="post" name="memberForm" style="margin: 0;">
				<table width="100%" cellpadding="2" cellspacing="1" border="0">
					<tr class="catbg"><td colspan="4" style="padding: 4px;">', $txt['membergroups_regular'], '</td></tr>
					<tr class="titlebg">
						<td width="42%">', $txt['membergroups_name'], '</td>
						<td width="12%" align="center">', $txt['membergroups_stars'], '</td>
						<td width="10%" align="center">', $txt['membergroups_members_top'], '</td>
						<td width="10%" align="center">', $txt[17], '</td>
					</tr>';
	foreach ($context['groups']['regular'] as $group)
	{
		echo '
					<tr>
						<td class="windowbg2">', empty($group['color']) ? $group['name'] : '<span style="color: ' . $group['color'] . '">' . $group['name'] . '</span>', '</td>
						<td class="windowbg2" align="left">', $group['stars'], '</td>
						<td class="windowbg" align="center">', $group['can_search'] ? $group['link'] : $group['num_members'], '</td>
						<td class="windowbg2" align="center"><a href="' . $scripturl . '?action=membergroups;sa=edit;id=' . $group['id'] . '">' . $txt['membergroups_modify'] . '</a></td>
					</tr>';
	}

	echo '
					<tr class="windowbg">
						<td colspan="4" align="right" style="padding-top: 1ex; padding-bottom: 2ex;">
							<input type="submit" value="', $txt['membergroups_add_group'], '" style="margin: 4px;" />
						</td>
					</tr>
				</table>
				<input type="hidden" name="sc" value="' . $context['session_id'] . '" />
				<input type="hidden" name="postgroup" value="0" />
			</form><form action="' . $scripturl . '?action=membergroups;sa=add" method="post" name="memberForm" style="margin: 0;">
				<table width="100%" border="0" cellpadding="2" cellspacing="1">
					<tr class="catbg"><td colspan="5" style="padding: 4px;">', $txt['membergroups_post'], '</td></tr>
					<tr class="titlebg">
						<td width="42%">', $txt['membergroups_name'], '</td>
						<td width="12%" align="center">', $txt['membergroups_stars'], '</td>
						<td width="10%" align="center">', $txt['membergroups_members_top'], '</td>
						<td width="12%" align="center">', $txt['membergroups_min_posts'], '</td>
						<td width="10%" align="center">', $txt[17], '</td>
					</tr>';
	foreach ($context['groups']['post'] as $group)
	{
		echo '
					<tr>
						<td class="windowbg2">', empty($group['color']) ? $group['name'] : '<span style="color: ' . $group['color'] . '">' . $group['name'] . '</span>', '</td>
						<td class="windowbg2" align="left">', $group['stars'], '</td>
						<td class="windowbg" align="center">', $group['can_search'] ? $group['link'] : $group['num_members'], '</td>
						<td class="windowbg" align="center">', $group['min_posts'], '</td>
						<td class="windowbg2" align="center"><a href="' . $scripturl . '?action=membergroups;sa=edit;id=' . $group['id'] . '">' . $txt['membergroups_modify'] . '</a></td>
					</tr>';
	}

	echo '
					<tr class="windowbg">
						<td colspan="5" align="right" style="padding-top: 1ex; padding-bottom: 2ex;">
							<input type="submit" value="', $txt['membergroups_add_group'], '" style="margin: 4px;" />
						</td>
					</tr>
				</table>
				<input type="hidden" name="sc" value="' . $context['session_id'] . '" />
				<input type="hidden" name="postgroup" value="1" />
			</form>
		</div>';
}

function template_new_group()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
		<form action="', $scripturl, '?action=membergroups;sa=add" method="post">
			<table width="90%" cellpadding="4" cellspacing="0" border="0" class="tborder" align="center">
				<tr class="titlebg">
					<td colspan="2" align="center">', $txt['membergroups_new_group'], '</td>
				</tr><tr class="windowbg2">
					<td>', $txt['membergroups_group_name'], ':</td>
					<td><input type="text" name="group_name" size="30" /></td>
				</tr>', $context['postgroup'] ? '<tr class="windowbg2">
					<td>' . $txt['membergroups_min_posts'] . ':</td>
					<td><input type="text" name="min_posts" size="5" /></td>
				</tr>' : '', '<tr class="windowbg2">
					<td>', $txt['membergroups_permissions'], ':</td>
					<td>', $txt['membergroups_new_as_type'], ': <select name="level">
						<option value="restrict">', $txt['permitgroups_restrict'], '</option>
						<option value="standard" selected="selected">', $txt['permitgroups_standard'], '</option>
						<option value="moderator">', $txt['permitgroups_moderator'], '</option>
						<option value="maintenance">', $txt['permitgroups_maintenance'], '</option>
					</select> &nbsp; ', $txt['membergroups_can_edit_later'], '</td>
				</tr><tr class="windowbg2">
					<td></td>
					<td>', $txt['membergroups_new_as_copy'], ': <select name="copyperm">
						<option value="1">', $txt['membergroups_new_copy_none'], '</option>
						<option value="-1">', $txt['membergroups_guests'], '</option>
						<option value="0">', $txt['membergroups_members'], '</option>';
	foreach ($context['groups'] as $group)
		echo '
						<option value="', $group['id'], '">', $group['name'], '</option>';
	echo '
					</select></td>
				</tr><tr class="windowbg2">
					<td valign="top">', $txt['membergroups_new_board'], ':
						<div class="smalltext">', $txt['membergroups_new_board_desc'], '</div>
					</td>
					<td>';

	foreach ($context['boards'] as $board)
		echo '
						<div style="margin-left: ', $board['child_level'], 'em;"><label for="boardaccess[', $board['id'], ']"><input type="checkbox" name="boardaccess[', $board['id'], ']" id="boardaccess[', $board['id'], ']"', $board['selected'] ? ' checked="checked" disabled="disabled"' : '', '> ', $board['name'], '</label></div>';

	echo '
						<br />
						<label for="checkall"><input type="checkbox" id="checkall" class="check" onclick="invertAll(this, this.form);" /> <i>', $txt[737], '</i></label>
					</td>
				</tr><tr class="windowbg2">
					<td colspan="2" align="center"><br /><input type="submit" value="', $txt['membergroups_add_group'], '" /></td>
				</tr>
			</table>
			<input type="hidden" name="sc" value="', $context['session_id'], '" />
		</form>';
}

function template_edit_group()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
		<form action="', $scripturl, '?action=membergroups;sa=edit;id=', $context['group']['id'], '" method="post" name="groupForm">
			<table width="95%" border="0" cellspacing="0" cellpadding="3" class="tborder" align="center">
				<tr class="titlebg">
					<td colspan="2" align="center">', $txt['membergroups_edit_group'], ' - ', $context['group']['name'], '</td>
				</tr>
				<tr class="windowbg2">
					<td><b>', $txt['membergroups_edit_name'], ':</b></td>
					<td><input type="text" name="group_name" value="', $context['group']['editable_name'], '" size="30" /></td>
				</tr>';
	if ($context['group']['allow_post_group'])
		echo '
				<tr class="windowbg2">
					<td colspan="2" style="padding-bottom: 0;"><input type="checkbox" name="post_group" value="1" id="post_group"', $context['group']['is_post_group'] ? ' checked="checked"' : '', ' onclick="swapPostGroup(this.checked);" class="check" /> <b><label for="post_group">', $txt['membergroups_edit_post_group'], '</label></b></td>
				</tr>
				<tr class="windowbg2">
					<td><b id="min_posts_text">', $txt['membergroups_min_posts'], ':</b></td>
					<td><input type="text" name="min_posts"', $context['group']['is_post_group'] ? ' value="' . $context['group']['min_posts'] . '"' : '', ' size="6" /></td>
				</tr>';
	echo '
				<tr class="windowbg2">
					<td><b>', $txt['membergroups_online_color'], ':</b></td>
					<td><input type="text" name="online_color" value="', $context['group']['color'], '" size="20" /></td>
				</tr>
				<tr class="windowbg2">
					<td style="padding-bottom: 0;"><b>', $txt['membergroups_star_count'], ':</b></td>
					<td style="padding-bottom: 0;"><input type="text" name="star_count" value="', $context['group']['star_count'], '" size="4" onkeyup="if (this.value.length > 2) this.value = 99;" onkeydown="this.onkeyup();" onchange="this.form.star_image.onchange();" /></td>
				</tr>
				<tr class="windowbg2">
					<td><b>', $txt['membergroups_star_image'], ':</b><div class="smalltext"><i>', $txt['membergroups_star_image_note'], '</i></div></td>
					<td><input type="text" name="star_image" value="', $context['group']['star_image'], '" onchange="if (this.value && this.form.star_count.value == 0) this.form.star_count.value = 1; document.getElementById(\'star_preview\').src = smf_images_url + \'/\' + (this.value && this.form.star_count.value > 0 ? this.value : \'blank.gif\');" size="20" /> <img id="star_preview" src="', $settings['images_url'], '/', $context['group']['star_image'] == '' ? 'blank.gif' : $context['group']['star_image'], '" alt="*" /></td>
				</tr>
				<tr class="windowbg2">
					<td>
						<b>', $txt['membergroups_max_messages'], ':</b>
						<div class="smalltext"><i>', $txt['membergroups_max_messages_note'], '</i></div>
					</td>
					<td><input type="text" name="max_messages" value="', $context['group']['max_messages'], '" size="6" /></td>
				</tr>
				<tr class="windowbg2">
					<td colspan="2" align="center">
						<hr width="100%" class="hrcolor" height="1" />
						<input type="submit" name="submit" value="', $txt['membergroups_edit_save'], '" />', $context['group']['allow_delete'] ? '
						<input type="submit" name="delete" value="' . $txt['membergroups_delete'] . '" onclick="return confirm(\'' . $txt['membergroups_confirm_delete'] . '\');" />' : '', '
					</td>
				</tr>
			</table>
			<input type="hidden" name="sc" value="', $context['session_id'], '" />
		</form>';

	if ($context['group']['allow_post_group'])
		echo '
		<script language="JavaScript" type="text/javascript"><!--
			function swapPostGroup(isChecked)
			{
				var min_posts_text = document.getElementById(\'min_posts_text\');
				document.groupForm.min_posts.disabled = !isChecked;
				min_posts_text.style.color = isChecked ? "" : "#888888";
			}
			swapPostGroup(', $context['group']['is_post_group'] ? 'true' : 'false', ');
		// --></script>';
}

function template_group_members()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
		<form action="', $scripturl, '?action=membergroups;sa=members;id=', $context['group']['id'], '" method="post">
			<table width="90%" cellpadding="4" cellspacing="1" border="0" class="bordercolor" align="center">
				<tr class="titlebg">
					<td colspan="6" align="left">', $context['page_title'], '</td>
				</tr>
				<tr class="windowbg">
					<td colspan="6" align="left" class="smalltext" style="padding: 2ex;">', $txt['membergroups_members_all_current_desc'], '</td>
				</tr>
				<tr class="catbg">
					<td colspan="6" align="left">', $txt[139], ': ', $context['page_index'], '</td>
				</tr>
				<tr class="titlebg">
					<td><a href="', $scripturl, '?action=membergroups;sa=members;start=', $context['start'], ';sort=name', $context['sort_by'] == 'name' && $context['sort_direction'] == 'up' ? ';desc' : '', ';id=', $context['group']['id'], '">', $txt[68], $context['sort_by'] == 'name' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a></td>
					<td><a href="', $scripturl, '?action=membergroups;sa=members;start=', $context['start'], ';sort=email', $context['sort_by'] == 'email' && $context['sort_direction'] == 'up' ? ';desc' : '', ';id=', $context['group']['id'], '">', $txt[69], $context['sort_by'] == 'email' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a></td>
					<td><a href="', $scripturl, '?action=membergroups;sa=members;start=', $context['start'], ';sort=active', $context['sort_by'] == 'active' && $context['sort_direction'] == 'up' ? ';desc' : '', ';id=', $context['group']['id'], '">', $txt['attachment_manager_last_active'], $context['sort_by'] == 'active' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a></td>
					<td><a href="', $scripturl, '?action=membergroups;sa=members;start=', $context['start'], ';sort=registered', $context['sort_by'] == 'registered' && $context['sort_direction'] == 'up' ? ';desc' : '', ';id=', $context['group']['id'], '">', $txt[233], $context['sort_by'] == 'registered' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a></td>
					<td><a href="', $scripturl, '?action=membergroups;sa=members;start=', $context['start'], ';sort=posts', $context['sort_by'] == 'posts' && $context['sort_direction'] == 'up' ? ';desc' : '', ';id=', $context['group']['id'], '">', $txt[21], $context['sort_by'] == 'posts' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a></td>
					<td width="4%" align="center"><input type="checkbox" class="check" onclick="invertAll(this, this.form);" ', !empty($context['group']['assignable']) ? '' : 'disabled="disabled"', '/></td>
				</tr>';

	if (empty($context['members']))
		echo '
				<tr class="windowbg2">
					<td colspan="6" align="center">', $txt['membergroups_members_no_members'], '</td>
				</tr>';

	$alternate = false;
	foreach ($context['members'] as $member)
	{
		echo '
				<tr class="', $alternate ? 'windowbg2' : 'windowbg', '">
					<td>', $member['name'], '</td>
					<td>', $member['email'], '</td>
					<td>', $member['last_online'], '</td>
					<td>', $member['registered'], '</td>
					<td>', $member['posts'], '</td>
					<td align="center" width="4%"><input type="checkbox" name="rem[', $member['id'], ']" class="check" ', !empty($context['group']['assignable']) ? '' : 'disabled="disabled"', '/></td>
				</tr>';
		$alternate = !$alternate;
	}

	echo '
				<tr class="titlebg">
					<td colspan="6" align="right">
						<input type="submit" name="remove" value="', $txt['membergroups_members_remove'], '" ', !empty($context['group']['assignable']) ? '' : 'disabled="disabled"', ' style="font-weight: normal;" />
					</td>
				</tr>
			</table><br />';

	if (!empty($context['group']['assignable']))
	{
		echo '
			<table width="90%" cellpadding="4" cellspacing="0" border="0" class="tborder" align="center">
				<tr class="titlebg">
					<td align="left" colspan="2">', $txt['membergroups_members_add_title'], '</td>
				</tr><tr class="windowbg2">
					<td align="right" width="50%"><b>', $txt['membergroups_members_add_desc'], ':</b></td>
					<td align="left">
						<input type="text" name="toAdd" id="toAdd" size="30" />
						<a href="', $scripturl, '?action=findmember;input=toAdd;quote;sesc=', $context['session_id'], '" onclick="return reqWin(this.href, 350, 400);"><img src="', $settings['images_url'], '/icons/assist.gif" border="0" alt="', $txt['find_members'], '" /></a>
					</td>
				</tr><tr class="windowbg2">
					<td colspan="2" align="center">
						<input type="submit" name="add" value="', $txt['membergroups_members_add'], '" />
					</td>
				</tr>
			</table>';
	}

	echo '
			<input type="hidden" name="sc" value="', $context['session_id'], '" />
		</form>';
}

function template_email_members()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<form action="' . $scripturl . '?action=mailing;sa=send" method="post">
			<table width="600" cellpadding="5" cellspacing="0" border="0" align="center" class="tborder">
				<tr class="titlebg">
					<td><a href="' . $scripturl . '?action=helpadmin;help=6" onclick="return reqWin(this.href);" class="help"><img src="' . $settings['images_url'] . '/helptopics.gif" alt="' . $txt[119] . '" border="0" align="top" /></a> ', $txt[6], '</td>
				</tr><tr class="windowbg">
					<td class="smalltext" style="padding: 2ex;">' . $txt['smf250'] . '</td>
				</tr><tr>
					<td class="windowbg2">';

	foreach ($context['groups'] as $group)
				echo '
						<label for="who[', $group['id'], ']"><input type="checkbox" name="who[', $group['id'], ']" id="who[', $group['id'], ']" value="', $group['id'], '" checked="checked" class="check" /> ', $group['name'], '</label> <i>(', $group['member_count'], ')</i><br />';

	echo '
					</td>
				</tr><tr>
					<td class="windowbg2">';

	if ($context['can_send_pm'])
		echo '
					<label for="sendPM"><input type="checkbox" name="sendPM" id="sendPM" value="1" class="check" /> ', $txt['email_as_pms'], '</label><br />';

	echo '
						<label for="email_force"><input type="checkbox" name="email_force" id="email_force" value="1" class="check" /> ' . $txt['email_force'] . '</label>
					</td>
				</tr><tr>
					<td class="windowbg2" style="padding-bottom: 1ex;" align="center">
						<input type="submit" value="' . $txt[65] . '" />
					</td>
				</tr>
			</table>
			<input type="hidden" name="sc" value="' . $context['session_id'] . '" />
		</form>';
}

function template_email_members_compose()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<form action="' . $scripturl . '?action=mailing;sa=send2" method="post" name="emailForm">
			<table width="600" cellpadding="4" cellspacing="0" border="0" align="center" class="tborder">
				<tr class="titlebg">
					<td>
						<a href="' . $scripturl . '?action=helpadmin;help=6" onclick="return reqWin(this.href);" class="help"><img src="' . $settings['images_url'] . '/helptopics.gif" alt="' . $txt[119] . '" border="0" align="top" /></a> ' . $txt[6] . '
					</td>
				</tr><tr class="windowbg">
					<td class="smalltext" style="padding: 2ex;">' . $txt[735] . '</td>
				</tr><tr>
					<td class="windowbg2" align="center">
						<textarea cols="70" rows="7" name="emails" class="editor">', $context['addresses'], '</textarea>
					</td>
				</tr>
			</table>
			<br />
			<table width="600" cellpadding="5" cellspacing="0" border="0" align="center" class="tborder">
				<tr class="titlebg">
					<td>' . $txt[338] . '</td>
				</tr><tr class="windowbg">
					<td class="smalltext" style="padding: 2ex;">', $txt['email_variables'], '</td>
				</tr><tr>
					<td class="windowbg2">
						<input type="text" name="subject" size="60" value="', $context['default_subject'], '" /><br />
						<br />
						<textarea cols="70" rows="9" name="message" class="editor">', $context['default_message'], '</textarea><br />
						<br />
						<label for="send_html"><input type="checkbox" name="send_html" id="send_html" class="check" onclick="document.emailForm.parse_html.disabled = !this.checked;" /> ', $txt['email_as_html'], '</label><br />
						<label for="parse_html"><input type="checkbox" name="parse_html" id="parse_html" checked="checked" disabled="disabled" class="check" /> ', $txt['email_parsed_html'], '</label><br />
						<br />
						<div align="center"><input type="submit" value="' . $txt['sendtopic_send'] . '" /></div>
					</td>
				</tr>
			</table>
			<input type="hidden" name="sc" value="' . $context['session_id'] . '" />
		</form>';
}

function template_view_members()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
	<form action="', $scripturl, '?action=viewmembers" method="post">
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor" align="center">
			<tr class="titlebg">
				<td colspan="8">
					<a href="' . $scripturl . '?action=helpadmin;help=4" onclick="return reqWin(this.href);" class="help"><img src="' . $settings['images_url'] . '/helptopics.gif" alt="' . $txt[119] . '" border="0" align="top" /></a> ' . $txt[9] . '
				</td>
			</tr>
			<tr>
				<td class="catbg" align="left" colspan="8">
					', $context['sub_action'] == 'all' ? '<img src="' . $settings['images_url'] . '/selected.gif" alt="&gt;" />' : '', '<a href="', $scripturl, '?action=viewmembers;sa=all">', $txt[303], '</a> | ', $context['sub_action'] == 'search' || $context['sub_action'] == 'query' ? '<img src="' . $settings['images_url'] . '/selected.gif" alt="&gt;" />' : '', $context['sub_action'] == 'search' || $context['sub_action'] == 'all' ? '<a href="' . $scripturl . '?action=viewmembers;sa=search">' . $txt['mlist_search'] . '</a>' : $txt['search_results'] . ' (<a href="' . $scripturl . '?action=viewmembers;sa=search">' . $txt['mlist_search2'] . '</a>)', '
				</td>
			</tr>';
	if ($context['sub_action'] == 'all' || $context['sub_action'] == 'query')
	{
		echo '
			<tr class="windowbg">
				<td class="smalltext" align="left" colspan="8" style="padding: 2ex;">
					', $txt[11], '
				</td>
			</tr>
			<tr class="catbg">
				<td align="left" colspan="8">
					<b>', $txt[139], ':</b> ' . $context['page_index'] . '
				</td>
			</tr>
			<tr class="titlebg">';
		foreach ($context['columns'] as $column)
		{
			echo '
				<td valign="top">
					<a href="' . $column['href'] . '">';
			if ($column['selected'])
				echo $column['label'] . ' <img src="' . $settings['images_url'] . '/sort_', $context['sort_direction'], '.gif" alt="" border="0" />';
			else
				echo $column['label'];
			echo '</a>
				</td>';
		}
		if ($context['can_delete_members'])
			echo '
				<td>
					<input type="checkbox" class="check" onclick="invertAll(this, this.form);" />
				</td>';
		else
			echo '
				<td></td>';
		echo '
			</tr>';
		if (empty($context['members']))
			echo '
			<tr>
				<td class="windowbg" colspan="8">(', $txt['search_no_results'], ')</td>
			</tr>';
		else
		{
			foreach ($context['members'] as $member)
			{
				echo '
			<tr>
				<td class="windowbg2" width="5%">
					' . $member['id'] . '
				</td>
				<td class="windowbg">
					<a href="' . $member['href'] . '">' . $member['username'] . '</a>
				</td>
				<td class="windowbg2">
					<a href="' . $member['href'] . '">' . $member['name'] . '</a>
				</td>
				<td class="windowbg">
					<a href="mailto:', $member['email'], '">' . $member['email'] . '</a>
				</td>
				<td class="windowbg2">
					<a href="' . $scripturl . '?action=trackip;searchip=' . $member['ip'] . '">' . $member['ip'] . '</a>
				</td>
				<td class="windowbg">
					' . $member['last_active'] . '
				</td>
				<td class="windowbg2">
					' . $member['posts'] . '
				</td>';
			if ($context['can_delete_members'])
				echo '
				<td class="windowbg" width="5%">
					<input type="checkbox" name="delete[' . $member['id'] . ']" class="check" />
				</td>';
			else
				echo '
				<td class="windowbg"></td>';
			echo '
			</tr>';
			}
			echo '
			<tr>
				<td class="windowbg2" align="right" colspan="8">', $context['can_delete_members'] ? '
					<input type="submit" value="' . $txt[608] . '" onclick="return confirm(\'' . $txt['confirm_delete_members'] . '\');" />' : '', '
					<input type="hidden" name="sa" value="delete" />
					<input type="hidden" name="sc" value="' . $context['session_id'] . '" />
					<input type="hidden" name="sort" value="', $context['sort_by'], '" />
					<input type="hidden" name="start" value="', $context['start'], '" />', $context['sort_direction'] == 'up' ? '
					<input type="hidden" name="desc" value="1" />' : '', '
				</td>
			</tr>';
		}
	}
	else
	{
		echo '
			<tr class="windowbg">
				<td colspan="8">
					<table border="0" cellspacing="1" cellpadding="4">
						<tr>
							<td colspan="5" align="left"><b>', $txt['search_for'], ':</b></td>
						</tr><tr>
							<td colspan="5" align="right"><span class="smalltext">(', $txt['wild_cards_allowed'], ')</span></td>
						</tr><tr>
							<th align="right">', $txt['member_id'], ':</th>
							<td align="center">
								<select name="types[mem_id]">
									<option value="--">&lt;</option>
									<option value="-">&lt;=</option>
									<option value="=" selected="selected">=</option>
									<option value="+">&gt;=</option>
									<option value="++">&gt;</option>
								</select>
							</td>
							<td><input type="text" name="mem_id" value="" size="6" /></td>
							<th align="right">', $txt[35], ':</th>
							<td align="left"><input type="text" name="membername" value="" /> </td>
						</tr><tr>
							<th align="right">', $txt['age'], ':</th>
							<td align="center">
								<select name="types[age]">
									<option value="--">&lt;</option>
									<option value="-">&lt;=</option>
									<option value="=" selected="selected">=</option>
									<option value="+">&gt;=</option>
									<option value="++">&gt;</option>
								</select>
							</td>
							<td align="left"><input type="text" name="age" value="" size="6" /></td>
							<th align="right">', $txt['email_address'], ':</th>
							<td align="left"><input type="text" name="email" value="" /></td>
						</tr><tr>
							<th align="right">', $txt[26], ':</th>
							<td align="center">
								<select name="types[posts]">
									<option value="--">&lt;</option>
									<option value="-">&lt;=</option>
									<option value="=" selected="selected">=</option>
									<option value="+">&gt;=</option>
									<option value="++">&gt;</option>
								</select>
							</td>
							<td align="left"><input type="text" name="posts" value="" size="6" /></td>
							<th align="right">', $txt[96], ':</th>
							<td align="left"><input type="text" name="website" value="" /></td>
						</tr><tr>
							<th align="right">', $txt[233], ':</th>
							<td align="center">
								<select name="types[reg_date]">
									<option value="--">&lt;</option>
									<option value="-">&lt;=</option>
									<option value="=" selected="selected">=</option>
									<option value="+">&gt;=</option>
									<option value="++">&gt;</option>
								</select>
							</td>
							<td align="left"><input type="text" name="reg_date" value="" /> <span class="smalltext">', $txt['date_format'], '</span></td>
							<th align="right">', $txt[227], ':</th>
							<td align="left"><input type="text" name="location" value="" /></td>
						</tr><tr>
							<th align="right">', $txt['viewmembers_online'], ':</th>
							<td align="center">
								<select name="types[last_online]">
									<option value="--">&lt;</option>
									<option value="-">&lt;=</option>
									<option value="=" selected="selected">=</option>
									<option value="+">&gt;=</option>
									<option value="++">&gt;</option>
								</select>
							</td>
							<td align="left"><input type="text" name="last_online" value="" /> <span class="smalltext">', $txt['date_format'], '</span></td>
							<th align="right">', $txt['ip_address'], ':</th>
							<td align="left"><input type="text" name="ip" value="" /></td>
						</tr><tr>
							<th align="right">', $txt[231], ':</th>
							<td align="left" colspan="2">
								<input type="checkbox" name="gender[]" value="0" checked="checked" class="check" />', $txt['undefined_gender'], '&nbsp;&nbsp;
								<input type="checkbox" name="gender[]" value="1" checked="checked" class="check" />', $txt[238], '&nbsp;&nbsp;
								<input type="checkbox" name="gender[]" value="2" checked="checked" class="check" />', $txt[239], '&nbsp;&nbsp;
							</td>
							<th align="right">', $txt['messenger_address'], ':</th>
							<td align="left"><input type="text" name="messenger" value="" /></td>
						</tr><tr>
							<th align="right">', $txt['activation_status'], ':</th>
							<td align="left" colspan="2">
								<input type="checkbox" name="activated[]" value="1" checked="checked" class="check" />', $txt['activated'], '&nbsp;&nbsp;
								<input type="checkbox" name="activated[]" value="0" checked="checked" class="check" />', $txt['not_activated'], '
							</td>
						</tr><tr>
							<td colspan="5">
								&nbsp;<br /><br />
								<b>', $txt['member_part_of_these_membergroups'], ':</b><br /><br />
							</td>
						</tr><tr>
							<td align="center" colspan="3" valign="top">
								<table border="0" cellspacing="1" cellpadding="4" class="bordercolor">
									<tr class="titlebg">
										<th>', $txt['membergroups'], '</th>
										<th>', $txt['primary'], '</th>
										<th>', $txt['additional'], '</th>
									</tr>';
			foreach ($context['membergroups'] as $membergroup)
				echo '
									<tr class="windowbg2">
										<td>
											', $membergroup['name'], '
										</td>
										<td align="center">
											<input type="checkbox" name="membergroups[1][]" value="', $membergroup['id'], '" checked="checked" class="check" />
										</td>
										<td align="center">
											', $membergroup['can_be_additional'] ? '<input type="checkbox" name="membergroups[2][]" value="' . $membergroup['id'] . '" checked="checked" class="check" />' : '&nbsp;', '
										</td>
									</tr>';
			echo '
								</table>
							</td>
							<td align="center" colspan="2" valign="top">
								<table border="0" cellspacing="1" cellpadding="4" class="bordercolor">
									<tr class="titlebg">
										<th>', $txt['membergroups_postgroups'], '</th>
										<th></th>
									</tr>';
			foreach ($context['postgroups'] as $postgroup)
				echo '
									<tr class="windowbg2">
										<td>
											', $postgroup['name'], '
										</td>
										<td align="center">
											<input type="checkbox" name="postgroups[]" value="', $postgroup['id'], '" checked="checked" class="check" />
										</td>
									</tr>';
			echo '
								</table>
							</td>
						</tr><tr>
							<td colspan="5" align="right">
								<input type="submit" value="', $txt['182'], '" />
								<input type="hidden" name="sa" value="query" />
							</td>
						</tr>
					</table>
				</td>
			</tr>';
	}
	echo '
		</table>
	</form>';
}

function template_ban_list()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
	<form action="', $scripturl, '?action=ban;sa=list" method="post">
		<table border="0" align="center" cellspacing="1" cellpadding="4" class="bordercolor" width="100%">
			<tr class="titlebg">
				<td>
					<a href="', $scripturl, '?action=helpadmin;help=7" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt[119], '" border="0" align="top" /></a>
					', $txt['ban_title'], '
				</td>
			</tr><tr class="catbg">
				<td align="left">
					<img src="', $settings['images_url'], '/selected.gif" alt="&gt;" /> <b>', $txt['ban_edit_list'], '</b> |
					<a href="', $scripturl, '?action=ban;sa=add">', $txt['ban_add_new'], '</a> |
					<a href="', $scripturl, '?action=ban;sa=log">', $txt['ban_log'], '</a>
				</td>
			</tr><tr class="windowbg">
				<td class="smalltext" style="padding: 2ex;">', $txt['ban_description'], '</td>
			</tr>
		</table>
		<br />
		<table border="0" align="center" cellspacing="1" cellpadding="4" class="bordercolor" width="100%">
			<tr class="catbg">
				<td colspan="7"><b>', $txt[139], ':</b> ', $context['page_index'], '</td>
			</tr><tr class="titlebg">';
	foreach ($context['columns'] as $column)
	{
		if ($column['selected'])
			echo '
				<th', isset($column['width']) ? ' width="' . $column['width'] . '"' : '', '>
					<a href="', $column['href'], '">', $column['label'], '&nbsp;<img src="', $settings['images_url'], '/sort_', $context['sort_direction'], '.gif" alt="" border="0" /></a>
				</th>';
		elseif ($column['sortable'])
			echo '
				<th', isset($column['width']) ? ' width="' . $column['width'] . '"' : '', '>
					', $column['link'], '
				</th>';
		else
			echo '
				<th', isset($column['width']) ? ' width="' . $column['width'] . '"' : '', '>
					', $column['label'], '
				</th>';
	}
	echo '
				<th><input type="checkbox" class="check" onclick="invertAll(this, this.form);" /></th>
			</tr>';

	while ($ban = $context['get_ban']())
	{
		echo '
			<tr>
				<td align="left" valign="top" class="windowbg2">';
			if ($ban['type'] == 'ip_ban')
				echo '<b>', $txt[512], ':</b>&nbsp;', $ban['ip'];
			elseif ($ban['type'] == 'hostname_ban')
				echo '<b>', $txt['hostname'], ':</b>&nbsp;', $ban['hostname'];
			elseif ($ban['type'] == 'email_ban')
				echo '<b>', $txt[69], ':</b>&nbsp;', $ban['email'];
			elseif ($ban['type'] == 'user_ban')
				echo '<b>', $txt[35], ':</b>&nbsp;', $ban['user']['link'];
			echo '
				</td>
				<td align="left" valign="top" class="windowbg">', $ban['reason'], '</td>
				<td align="left" valign="top" class="windowbg">', $ban['notes'], '</td>
				<td align="left" valign="top" class="windowbg2">', $ban['restriction'], '</td>
				<td align="left" valign="top" class="windowbg">', $ban['expires'], '</td>
				<td align="left" valign="top" class="windowbg2">
					&nbsp;<a href="', $scripturl, '?action=ban;sa=edit;sort=', $context['sort_by'], $context['sort_direction'] == 'up' ? ';desc' : '',';bid=', $ban['id'], '">', $txt[17], '</a>
				</td>
				<td align="left" valign="top" class="windowbg2"><input type="checkbox" name="remove[]" value="', $ban['id'], '" class="check" /></td>
			</tr>';
	}
	echo '
			<tr class="windowbg2">
				<td colspan="7" align="right"><input type="submit" name="removeBans" value="', $txt['ban_remove_selected'], '" onclick="return confirm(\'', $txt['ban_remove_selected_confirm'], '\');" /></td>
			</tr>
		</table>
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
	</form>';
}

function template_ban_edit()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
	<form action="', $scripturl, '?action=ban;sa=save" method="post">
		<table border="0" align="center" cellspacing="1" cellpadding="4" class="bordercolor" width="100%">
			<tr class="titlebg">
				<td>
					<a href="', $scripturl, '?action=helpadmin;help=7" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt[119], '" border="0" align="top" /></a>
					', $txt['ban_title'], '
				</td>
			</tr><tr class="catbg">
				<td align="left">';
	if ($context['sub_action'] == 'add')
		echo '
					<a href="', $scripturl, '?action=ban;sa=list">', $txt['ban_edit_list'], '</a> |
					<img src="', $settings['images_url'], '/selected.gif" alt="&gt;" /> <b>', $txt['ban_add_new'], '</b> | ';
	else
		echo '
					<img src="', $settings['images_url'], '/selected.gif" alt="&gt;" /> <a href="', $scripturl, '?action=ban;sa=list"><b>', $txt['ban_edit_list'], '</b></a> |
					<a href="', $scripturl, '?action=ban;sa=add">', $txt['ban_add_new'], '</a> | ';
	echo '
					<a href="', $scripturl, '?action=ban;sa=log">', $txt['ban_log'], '</a>
				</td>
			</tr><tr class="windowbg">
				<td class="smalltext" style="padding: 2ex;">', $txt['ban_description'], '</td>
			</tr>
		</table>
		<br />
		<table border="0" align="center" cellspacing="0" cellpadding="4" class="tborder" width="80%">
			<tr class="windowbg2">
				<td valign="top" align="left">
					<b>', $txt['ban_banned_entity'], ':</b>
					<table cellpadding="4">
						<tr>
							<td valign="middle">
								<input type="radio" name="bantype" value="ip_ban"', $context['ban']['ip']['selected'] ? ' checked="checked"' : '', ' class="check" />
							</td><td valign="top">
								', $txt['ban_on_ip'], ':<br />
								<input type="text" name="ip" value="', $context['ban']['ip']['value'], '" size="50" onfocus="selectRadioByName(this.form.bantype, \'ip_ban\');" />
							</td>
						</tr><tr>';
	if (empty($modSettings['disableHostnameLookup']))
		echo '
							<td valign="middle">
								<input type="radio" name="bantype" value="hostname_ban"', $context['ban']['hostname']['selected'] ? ' checked="checked"' : '', ' class="check" />
							</td><td valign="top">
								', $txt['ban_on_hostname'], ':<br />
								<input type="text" name="hostname" value="', $context['ban']['hostname']['value'], '" size="50" onfocus="selectRadioByName(this.form.bantype, \'hostname_ban\');" />
							</td>
						</tr><tr>';
	echo '
							<td valign="middle">
								<input type="radio" name="bantype" value="email_ban"', $context['ban']['email']['selected'] ? ' checked="checked"' : '', ' class="check" />
							</td><td valign="top">
								', $txt['ban_on_email'], ':<br />
								<input type="text" name="email" value="', $context['ban']['email']['value'], '" size="50" onfocus="selectRadioByName(this.form.bantype, \'email_ban\');" />
							</td>
						</tr><tr>
							<td valign="middle">
								<input type="radio" name="bantype" value="user_ban"', $context['ban']['banneduser']['selected'] ? ' checked="checked"' : '', ' class="check" />
							</td><td valign="top">
								', $txt['ban_on_username'], ':<br />
								<input type="text" name="banneduser" value="', $context['ban']['banneduser']['value'], '" size="50" onfocus="selectRadioByName(this.form.bantype, \'user_ban\');" />
							</td>
						</tr>
					</table>
					<br />
					<b>', $txt['ban_expiration'], ':</b><br />
					<table cellpadding="4">
						<tr>
							<td valign="middle">
								<input type="radio" name="expiration" value="never"', $context['ban']['expiration']['never'] ? ' checked="checked"' : '', ' class="check" />
							</td><td valign="top">
								', $txt['never'], '
							</td>
						</tr><tr>
						<td valign="middle">
								<input type="radio" name="expiration" value="one_day"', $context['ban']['expiration']['never'] ? '' : ' checked="checked"', ' class="check" />
							</td><td valign="top">
									', $txt['ban_will_expire_within'], ':<br />
							<input type="text" name="expire_date" size="3" value="', $context['ban']['ban_days'], '" /> ', $txt['ban_days'], '<br />
								</td>
						</tr>
					</table>
				</td><td valign="top" align="left" style="padding-left: 15px;">
					<b>', $txt['ban_reason'], ':</b>
					<div class="smalltext">', $txt['ban_reason_desc'], '</div>
					<input type="text" name="reason" value="', $context['ban']['reason'], '" size="50" />
					<br />
					<br />
					<b>', $txt['ban_notes'], ':</b>
					<div class="smalltext">', $txt['ban_notes_desc'], '</div>
					<textarea name="notes" cols="50" rows="3">', $context['ban']['notes'], '</textarea>
					<br />
					<br />
					<b>', $txt['ban_restriction'], ':</b><br />
					<input type="radio" name="restriction" value="full_ban"', $context['ban']['restriction'] == 'full_ban' ? ' checked="checked"' : '', ' class="check" /> ', $txt['ban_full_ban'], '<br />
					<input type="radio" name="restriction" value="cannot_post"', $context['ban']['restriction'] == 'cannot_post' ? ' checked="checked"' : '', ' class="check" /> ', $txt['ban_cannot_post'], '<br />
					<input type="radio" name="restriction" value="cannot_register"', $context['ban']['restriction'] == 'cannot_register' ? ' checked="checked"' : '', ' class="check" /> ', $txt['ban_cannot_register'], '<br />
				</td>
			</tr><tr class="windowbg2">
				<td colspan="2" align="right">
					<input type="submit" value="', $context['sub_action'] == 'add' ? $txt['ban_add'] : $txt['ban_modify'], '" />&nbsp;&nbsp;&nbsp;
				</td>
			</tr>
		</table>', $context['sub_action'] == 'add' ? '' : '
		<input type="hidden" name="bid" value="' . $context['ban']['id'] . '" />', '
		<input type="hidden" name="sc" value="' . $context['session_id'] . '" />
	</form>';
}

function template_ban_log()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
	<form action="', $scripturl, '?action=ban;sa=log" method="post">
		<table border="0" align="center" cellspacing="1" cellpadding="4" class="bordercolor" width="100%">
			<tr class="titlebg">
				<td>
					<a href="', $scripturl, '?action=helpadmin;help=7" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt[119], '" border="0" align="top" /></a>
					', $txt['ban_title'], '
				</td>
			</tr><tr class="catbg">
				<td align="left">
					<a href="', $scripturl, '?action=ban;sa=list"><b>', $txt['ban_edit_list'], '</b></a> |
					<a href="', $scripturl, '?action=ban;sa=add">', $txt['ban_add_new'], '</a> |
					<img src="', $settings['images_url'], '/selected.gif" alt="&gt;" /> <a href="', $scripturl, '?action=ban;sa=log"><b>', $txt['ban_log'], '</b></a>
				</td>
			</tr><tr class="windowbg">
				<td class="smalltext" style="padding: 2ex;">', $txt['ban_log_description'], '</td>
			</tr>
		</table>
		<br />
		<table border="0" align="center" cellspacing="1" cellpadding="4" class="bordercolor" width="100%">
			<tr class="catbg">
				<td colspan="7"><b>', $txt[139], ':</b> ', $context['page_index'], '</td>
			</tr><tr class="titlebg">
				<th>
					<a href="', $scripturl, '?action=ban;sa=log;sort=ip', $context['sort_direction'] == 'up' ? ';desc' : '', ';start=', $context['start'], '">' . $txt['ban_log_ip'], $context['sort'] == 'ip' ? '&nbsp;<img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a>
				</th>
				<th>
					<a href="', $scripturl, '?action=ban;sa=log;sort=email', $context['sort_direction'] == 'up' ? ';desc' : '', ';start=', $context['start'], '">' . $txt['ban_log_email'], $context['sort'] == 'email' ? '&nbsp;<img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a>
				</th>
				<th>
					<a href="', $scripturl, '?action=ban;sa=log;sort=name', $context['sort_direction'] == 'up' ? ';desc' : '', ';start=', $context['start'], '">' . $txt['ban_log_member'], $context['sort'] == 'name' ? '&nbsp;<img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a>
				</th>
				<th>
					<a href="', $scripturl, '?action=ban;sa=log;sort=date', $context['sort_direction'] == 'up' ? ';desc' : '', ';start=', $context['start'], '">' . $txt['ban_log_date'], $context['sort'] == 'date' ? '&nbsp;<img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a>
				</th>
				<th><input type="checkbox" class="check" onclick="invertAll(this, this.form);" /></th>
			</tr>';
	if (empty($context['log_entries']))
		echo '
			<tr class="windowbg2">
				<td colspan="5">(', $txt['ban_log_no_entries'], ')</td>
			</tr>';
	else
	{
		foreach ($context['log_entries'] as $log)
			echo '
			<tr>
				<td class="windowbg">', $log['ip'], '</td>
				<td class="windowbg2">', $log['email'], '</td>
				<td class="windowbg">', empty($log['member']['id']) ? '<i>' . $txt[470] . '</i>' : $log['member']['link'], '</td>
				<td class="windowbg2">', $log['date'], '</td>
				<td class="windowbg" align="center"><input type="checkbox" name="remove[]" value="', $log['id'], '" class="check" /></td>
			</tr>';
		echo '
			<tr class="windowbg2">
				<td colspan="5" align="right">
					<input type="submit" name="removeAll" value="', $txt['ban_log_remove_all'], '" onclick="return confirm(\'', $txt['ban_log_remove_all_confirm'], '\');" />
					<input type="submit" name="removeSelected" value="', $txt['ban_log_remove_selected'], '" onclick="return confirm(\'', $txt['ban_log_remove_selected_confirm'], '\');" />
				</td>
			</tr>';
	}
	echo '
		</table>
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
	</form>';
}

function template_edit_reserved_words()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
		<form action="' . $scripturl . '?action=setreserve2" method="post">
			<table border="0" cellspacing="1" class="bordercolor" align="center" cellpadding="4" width="600">
				<tr class="titlebg">
					<td>
						<a href="' . $scripturl . '?action=helpadmin;help=8" onclick="return reqWin(this.href);" class="help"><img src="' . $settings['images_url'] . '/helptopics.gif" alt="' . $txt[119] . '" border="0" align="top" /></a> ' . $txt[341] . '
					</td>
				</tr><tr class="windowbg">
					<td class="smalltext" style="padding: 2ex;">' . $txt[699] . '</td>
				</tr><tr>
					<td class="windowbg2" align="center">
						<div style="width: 80%;">
							<div style="margin-bottom: 2ex;">' . $txt[342] . '</div>
							<textarea cols="30" rows="6" name="reserved" style="width: 98%;">' . implode("\n", $context['reserved_words']) . '</textarea><br />

							<div align="left" style="margin-top: 2ex;">
								<label for="matchword"><input type="checkbox" name="matchword" id="matchword" ', $context['reserved_word_options']['match_word'] ? 'checked="checked"' : '', ' class="check" /> ', $txt[726], '</label><br />
								<label for="matchcase"><input type="checkbox" name="matchcase" id="matchcase" ', $context['reserved_word_options']['match_case'] ? 'checked="checked"' : '', ' class="check" /> ', $txt[727], '</label><br />
								<label for="matchuser"><input type="checkbox" name="matchuser" id="matchuser" ', $context['reserved_word_options']['match_user'] ? 'checked="checked"' : '', ' class="check" /> ', $txt[728], '</label><br />
								<label for="matchname"><input type="checkbox" name="matchname" id="matchname" ', $context['reserved_word_options']['match_name'] ? 'checked="checked"' : '', ' class="check" /> ', $txt[729], '</label><br />
							</div>

							<input type="submit" value="' . $txt[10] . '" style="margin: 1ex;" />
						</div>
					</td>
				</tr>
			</table>
			<input type="hidden" name="sc" value="' . $context['session_id'] . '" />
		</form>';
}

// This template shows an admin information on a users IP addresses used and errors attributed to them.
function template_trackUser()
{
	global $context, $settings, $options, $scripturl, $txt;

	// The first table shows IP information about the user.
	echo '
		<table cellpadding="0" cellspacing="0" border="0" class="bordercolor" align="center" width="90%"><tr><td>
			<table border="0" cellspacing="1" cellpadding="4" align="left" width="100%">
				<tr class="titlebg">
					<td colspan="2">
						', $txt['view_ips_by'], ' ', $context['member']['name'], '
					</td>
				</tr>';

	// The last IP the user used.
	echo '
				<tr>
					<td class="windowbg2" align="left" width="200">', $txt['most_recent_ip'], ':</td>
					<td class="windowbg2" align="left">
						<a href="', $scripturl, '?action=trackip;searchip=', $context['last_ip'], ';">', $context['last_ip'], '
					</td>
				</tr>';

	// Lists of IP addresses used in messages / error messages.
	echo '
				<tr>
					<td class="windowbg2" align="left">', $txt['ips_in_messages'], ':</td>
					<td class="windowbg2" align="left">
						', (count($context['ips']) > 0 ? implode(', ', $context['ips']) : '(' . $txt['none'] . ')'), '
					</td>
				</tr><tr>
					<td class="windowbg2" align="left">', $txt['ips_in_errors'], ':</td>
					<td class="windowbg2" align="left">
						', (count($context['ips']) > 0 ? implode(', ', $context['error_ips']) : '(' . $txt['none'] . ')'), '
					</td>
				</tr>';

	// List any members that have used the same IP addresses as the current member.
	echo '
				<tr>
					<td class="windowbg2" align="left">', $txt['members_in_range'], ':</td>
					<td class="windowbg2" align="left">
						', (count($context['members_in_range']) > 0 ? implode(', ', $context['members_in_range']) : '(' . $txt['none'] . ')'), '
					</td>
				</tr>
			</table>
		</td></tr></table>
		<br />';

	// The second table lists all the error messages the user has caused/received.
	echo '
		<table cellpadding="0" cellspacing="0" border="0" class="bordercolor" align="center" width="90%"><tr><td>
			<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
				<tr class="titlebg">
					<td colspan="4">
						', $txt['errors_by'], ' ', $context['member']['name'], '
					</td>
				</tr><tr class="windowbg">
					<td class="smalltext" colspan="4" style="padding: 2ex;">
						', $txt['errors_desc'], '
					</td>
				</tr><tr class="titlebg">
					<td colspan="4">
						<b>', $txt[139], ':</b> ', $context['page_index'], '
					</td>
				</tr><tr class="catbg">
					<td>', $txt['ip_address'], '</td>
					<td>', $txt[72], '</td>
					<td>', $txt[317], '</td>
				</tr>';

	// If there arn't any messages just give a message stating this.
	if (empty($context['error_messages']))
		echo '
				<tr><td class="windowbg2" colspan="4"><i>', $txt['no_errors_from_user'], '</i></td></tr>';

	// Otherwise print every error message out.
	else
		// For every error message print the IP address that caused it, the message displayed and the date it occurred.
		foreach ($context['error_messages'] as $error)
			echo '
				<tr>
					<td class="windowbg2">
						<a href="', $scripturl, '?action=trackip;searchip=', $error['ip'], ';">', $error['ip'], '</a>
					</td>
					<td class="windowbg2">
						', $error['message'], '<br />
						<a href="', $error['url'], '">', $error['url'], '</a>
					</td>
					<td class="windowbg2">', $error['time'], '</td>
				</tr>';
	echo '
			</table>
		</td></tr></table>';
}

// The template for trackIP, allowing the admin to see where/who a certain IP has been used.
function template_trackIP()
{
	global $context, $settings, $options, $scripturl, $txt;

	// This function always defaults to the last IP used by a member but can be set to track any IP.
	echo '
		<form action="', $scripturl, '?action=trackip" method="post">';

	// The first table in the template gives an input box to allow the admin to enter another IP to track.
	echo '
			<table cellpadding="0" cellspacing="0" border="0" class="bordercolor" align="center" width="90%"><tr><td>
				<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
					<tr class="titlebg">
						<td>', $txt['trackIP'], '</td>
					</tr><tr>
						<td class="windowbg2">
							', $txt['enter_ip'], ':&nbsp;&nbsp;<input type="text" name="searchip" value="', $context['ip'], '" />&nbsp;&nbsp;<input type="submit" value="', $txt['trackIP'], '" />
						</td>
					</tr>
				</table>
			</td></tr></table>
		</form>
		<br />';

	// The table inbetween the first and second table shows links to the whois server for every region.
	if ($context['single_ip'])
	{
		echo '
		<table cellpadding="0" cellspacing="0" border="0" class="bordercolor" align="center" width="90%"><tr><td>
			<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
				<tr class="titlebg">
					<td colspan="2">
						', $txt['whois_title'], ' ', $context['ip'], '
					</td>
				</tr><tr>
					<td class="windowbg2">';
		foreach ($context['whois_servers'] as $server)
			echo '
						<a href="', $server['url'], '" target="_blank">', $server['name'], '</a><br />';
		echo '
					</td>
				</tr>
			</table>
		</td></tr></table>
		<br />';
	}

	// The second table lists all the members who have been logged as using this IP address.
	echo '
		<table cellpadding="0" cellspacing="0" border="0" class="bordercolor" align="center" width="90%"><tr><td>
			<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
				<tr class="titlebg">
					<td colspan="2">
						', $txt['members_from_ip'], ' ', $context['ip'], '
					</td>
				</tr><tr class="catbg">
					<td>', $txt['ip_address'], '</td>
					<td>', $txt['display_name'], '</td>
				</tr>';
	if (empty($context['ips']))
		echo '
				<tr><td class="windowbg2" colspan="2"><i>', $txt['no_members_from_ip'], '</i></td></tr>';
	else
		// Loop through each of the members and display them.
		foreach ($context['ips'] as $ip => $memberlist)
			echo '
				<tr>
					<td class="windowbg2"><a href="', $scripturl, '?action=trackip;searchip=', $ip, ';">', $ip, '</a></td>
					<td class="windowbg2">', implode(', ', $memberlist), '</td>
				</tr>';
	echo '
			</table>
		</td></tr></table>
		<br />';

	// The third table in the template displays a list of all the messages sent using this IP (can be quite long).
	echo '
		<table cellpadding="0" cellspacing="0" border="0" class="bordercolor" align="center" width="90%"><tr><td>
			<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
				<tr class="titlebg">
					<td colspan="4">
						', $txt['messages_from_ip'], ' ', $context['ip'], '
					</td>
				</tr><tr class="windowbg">
					<td class="smalltext" colspan="4" style="padding: 2ex;">
						', $txt['messages_from_ip_desc'], '
					</td>
				</tr><tr class="titlebg">
					<td colspan="4">
						<b>', $txt[139], ':</b> ', $context['message_page_index'], '
					</td>
				</tr><tr class="catbg">
					<td>', $txt['ip_address'], '</td>
					<td>', $txt['rtm8'], '</td>
					<td>', $txt[319], '</td>
					<td>', $txt[317], '</td>
				</tr>';

	// No message means nothing to do!
	if (empty($context['messages']))
		echo '
				<tr><td class="windowbg2" colspan="4"><i>', $txt['no_messages_from_ip'], '</i></td></tr>';
	else
		// For every message print the IP, member who posts it, subject (with link) and date posted.
		foreach ($context['messages'] as $message)
			echo '
				<tr>
					<td class="windowbg2">
						<a href="', $scripturl, '?action=trackip;searchip=', $message['ip'], ';">', $message['ip'], '</a>
					</td>
					<td class="windowbg2">
						', $message['member']['link'], '
					</td>
					<td class="windowbg2">
						<a href="', $scripturl, '?topic=', $message['topic'], '.msg', $message['id'], '#msg', $message['id'], '">
							', $message['subject'], '
						</a>
					</td>
					<td class="windowbg2">', $message['time'], '</td>
				</tr>';
	echo '
			</table>
		</td></tr></table>
		<br />';

	// The final table in the template lists all the error messages caused/received by anyone using this IP address.
	echo '
		<table cellpadding="0" cellspacing="0" border="0" class="bordercolor" align="center" width="90%"><tr><td>
			<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%">
				<tr class="titlebg">
					<td colspan="4">
						', $txt['errors_from_ip'], ' ', $context['ip'], '
					</td>
				</tr><tr class="windowbg">
					<td class="smalltext" colspan="4" style="padding: 2ex;">
						', $txt['errors_from_ip_desc'], '
					</td>
				</tr><tr class="titlebg">
					<td colspan="4">
						<b>', $txt[139], ':</b> ', $context['error_page_index'], '
					</td>
				</tr><tr class="catbg">
					<td>', $txt['ip_address'], '</td>
					<td>', $txt['display_name'], '</td>
					<td>', $txt[72], '</td>
					<td>', $txt[317], '</td>
				</tr>';
	if (empty($context['error_messages']))
		echo '
				<tr><td class="windowbg2" colspan="4"><i>', $txt['no_errors_from_ip'], '</i></td></tr>';
	else
		// For each error print IP address, member, message received and date caused.
		foreach ($context['error_messages'] as $error)
			echo '
				<tr>
					<td class="windowbg2">
						<a href="', $scripturl, '?action=trackip;searchip=', $error['ip'], ';">', $error['ip'], '</a>
					</td>
					<td class="windowbg2">
						', $error['member']['link'], '
					</td>
					<td class="windowbg2">
						', $error['message'], '<br />
						<a href="', $error['url'], '">', $error['url'], '</a>
					</td>
					<td class="windowbg2">', $error['error_time'], '</td>
				</tr>';
	echo '
			</table>
		</td></tr></table>';
}

function template_showPermissions()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
		<table width="90%" border="0" cellspacing="1" cellpadding="4" align="center" class="bordercolor">
			<tr class="titlebg">
				<td colspan="2" height="26">
					&nbsp;<img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" border="0" align="top" />&nbsp;', $txt['showPermissions'], '
					</td>
			</tr>';
	if ($context['member']['has_all_permissions'])
	{
		echo '
			<tr class="windowbg2">
				<td colspan="2">', $txt['showPermissions_all'], '</td>
			</tr>';
	}
	else
	{
		// General Permissions section.
		echo '
			<tr class="catbg">
				<td align="left" colspan="2">', $txt['showPermissions_general'], '</td>
			</tr>';
		if (!empty($context['member']['permissions']['general']))
		{
			echo '
			<tr class="titlebg">
				<td width="50%">', $txt['showPermissions_permission'], '</td>
				<td width="50%"></td>
			</tr>';

			foreach ($context['member']['permissions']['general'] as $permission)
			{
				echo '
			<tr>
				<td class="windowbg" valign="top">
					', $permission['is_denied'] ? '<del>' . $permission['id'] . '</del>' : $permission['id'], '<br />
					<span class="smalltext">', $permission['name'], '</span>
				</td>
				<td class="windowbg2" valign="top"><span class="smalltext">';
				if ($permission['is_denied'])
					echo '
					<span style="color: red;font-weight: bold;">', $txt['showPermissions_denied'], ': </span>', implode(', ', $permission['groups']['denied']);
				else
					echo '
					<span style="font-weight: bold;">', $txt['showPermissions_given'], ': </span>', implode(', ', $permission['groups']['allowed']);
				echo '
				</span></td>
			</tr>';
			}
		}
		else
			echo '
			<tr class="windowbg2">
				<td colspan="2">', $txt['showPermissions_none_general'], '</td>
			</tr>';

		// Board permission section.
		echo '
			<tr class="catbg">
				<td align="left" colspan="2">
					<a name="board_permissions"></a>
					<form action="' . $scripturl . '?action=profile;u=', $context['member']['id'], ';sa=showPermissions#board_permissions" method="post" name="board_select">
						', $txt['showPermissions_select'], ':
						<select name="board" onchange="if (this.options[this.selectedIndex].value) this.form.submit();">
							<option value="0" ', $context['board'] == 0 ? 'selected="selected"' : '', '>', $txt['showPermissions_global'], '</option>';
		if (!empty($context['boards']))
			echo '
							<option value="" disabled="disabled">---------------------------</option>';

		// Fill the box with any local permission boards.
		foreach ($context['boards'] as $board)
			echo '
							<option value="', $board['id'], '"', $board['selected'] ? 'selected="selected"' : '', '>', $board['name'], '</option>';

		echo '
						</select>
					</form>
				</td>
			</tr>';
		if (!empty($context['member']['permissions']['board']))
		{
			echo '
			<tr class="titlebg">
				<td>', $txt['showPermissions_permission'], '</td>
				<td></td>
			</tr>';
			foreach ($context['member']['permissions']['board'] as $permission)
			{
				echo '
			<tr>
				<td class="windowbg" valign="top">
					', $permission['is_denied'] || !$permission['is_global'] ? '<del>' . $permission['id'] . '</del>' : $permission['id'], '<br />
					<span class="smalltext">', $permission['name'], '</span>
				</td>
				<td class="windowbg2" valign="top"><span class="smalltext">';
				if (!$permission['is_global'])
					echo '
					<i>', $txt['showPermissions_local_only'], '</i><br />
					<b>', $txt['showPermissions_boards'], ': </b><br />&nbsp;&nbsp;&nbsp;', implode('<br />&nbsp;&nbsp;&nbsp;', $permission['boards']['allowed']);
				elseif ($permission['is_global'] && $permission['is_denied'])
				{
					echo '
					<span style="color: red;font-weight: bold;">', $txt['showPermissions_denied'], ': </span>', implode(', ', $permission['groups']['denied']), '<br />';
					if (empty($context['current_board']))
						echo '
					<b>', $txt['showPermissions_boards_denied'], ': </b>', empty($permission['boards']['allowed']) ? $txt['showPermissions_all_boards'] : $txt['showPermissions_all_boards_except'] . ': <br />&nbsp;&nbsp;&nbsp;' . implode('<br />&nbsp;&nbsp;&nbsp;', $permission['boards']['allowed']);
				}
				elseif ($permission['is_global'])
				{
					echo '
					<span style="font-weight: bold;">', $txt['showPermissions_given'], ': </span>', implode(', ', $permission['groups']['allowed']), '<br />';
					if (empty($context['current_board']))
						echo '
					<b>', $txt['showPermissions_boards'], ': </b>', empty($permission['boards']['denied']) ? $txt['showPermissions_all_boards'] : $txt['showPermissions_all_boards_except'] . ': <br />&nbsp;&nbsp;&nbsp;' . implode('<br />&nbsp;&nbsp;&nbsp;', $permission['boards']['denied']);
				}
				echo '
				</span></td>
			</tr>';
			}
		}
		else
			echo '
			<tr class="windowbg2">
				<td colspan="2">', $txt['showPermissions_none_board'], '</td>
			</tr>';
	}
	echo '
		</table><br />';
}

function template_announce()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<form action="', $scripturl, '?action=announce;sa=send" method="post">
			<table width="600" cellpadding="5" cellspacing="0" border="0" align="center" class="tborder">
				<tr class="titlebg">
					<td>', $txt['announce_title'], '</td>
				</tr><tr class="windowbg">
					<td class="smalltext" style="padding: 2ex;">', $txt['announce_desc'], '</td>
				</tr><tr>
					<td class="windowbg2">
						', $txt['announce_this_topic'], ' <a href="', $scripturl, '?topic=', $context['current_topic'], '.0">', $context['topic_subject'], '</a><br />
					</td>
				</tr><tr>
					<td class="windowbg2">';

	foreach ($context['groups'] as $group)
				echo '
						<label for="who[', $group['id'], ']"><input type="checkbox" name="who[', $group['id'], ']" id="who[', $group['id'], ']" value="', $group['id'], '" checked="checked" class="check" /> ', $group['name'] , '</label> <i>(', $group['member_count'], ')</i><br />';

	echo '
						<br />
						<label for="checkall"><input type="checkbox" id="checkall" class="check" onclick="invertAll(this, this.form);" checked="checked" /> <i>', $txt[737], '</i></label>
					</td>
				</tr><tr>
					<td class="windowbg2" style="padding-bottom: 1ex;" align="center">
						<input type="submit" value="', $txt[105], '" />
					</td>
				</tr>
			</table>
			<input type="hidden" name="sc" value="', $context['session_id'], '" />
			<input type="hidden" name="topic" value="', $context['current_topic'], '" />
			<input type="hidden" name="move" value="', $context['move'], '" />
			<input type="hidden" name="goback" value="', $context['go_back'], '" />
		</form>';
}

function template_announcement_send()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<form action="' . $scripturl . '?action=announce;sa=send" method="post" name="autoSubmit">
			<table width="600" cellpadding="5" cellspacing="0" border="0" align="center" class="tborder">
				<tr class="titlebg">
					<td>
						', $txt['announce_sending'], ' <a href="', $scripturl, '?topic=', $context['current_topic'], '.0" target="_blank">', $context['topic_subject'], '</a>
					</td>
				</tr><tr>
					<td class="windowbg2"><b>', $context['percentage_done'], '% ', $txt['announce_done'], '</b></td>
				</tr><tr>
					<td class="windowbg2" style="padding-bottom: 1ex;" align="center">
						<input type="submit" name="b" value="', $txt['announce_continue'], '" />
					</td>
				</tr>
			</table>
			<input type="hidden" name="sc" value="', $context['session_id'], '" />
			<input type="hidden" name="topic" value="', $context['current_topic'], '" />
			<input type="hidden" name="move" value="', $context['move'], '" />
			<input type="hidden" name="goback" value="', $context['go_back'], '" />
			<input type="hidden" name="start" value="', $context['start'], '" />
			<input type="hidden" name="membergroups" value="', $context['membergroups'], '" />
		</form>
		<script language="JavaScript" type="text/javascript"><!--
			var countdown = 2;
			doAutoSubmit();

			function doAutoSubmit()
			{
				if (countdown == 0)
					document.autoSubmit.submit();
				else if (countdown == -1)
					return;

				document.autoSubmit.b.value = "', $txt['announce_continue'], ' (" + countdown + ")";
				countdown--;

				setTimeout("doAutoSubmit();", 1000);
			}
		// --></script>';
}

?>