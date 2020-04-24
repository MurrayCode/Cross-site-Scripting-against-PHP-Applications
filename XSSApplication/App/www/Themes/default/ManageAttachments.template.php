<?php
// Version: 1.0; ManageAttachments

function template_main()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
		<table border="0" align="center" cellspacing="1" cellpadding="4" class="bordercolor" width="100%">
			<tr class="titlebg">
				<td colspan="3">', $txt['smf201'], '</td>
			</tr><tr class="windowbg">
				<td colspan="3" class="smalltext" style="padding: 2ex;">', $txt['smf202'], '</td>
			</tr><tr>
				<td class="catbg">', $txt['smf203'], '</td>
			</tr><tr>
				<td class="windowbg2" width="100%" valign="top" style="padding-bottom: 2ex;">
					<table border="0" cellspacing="0" cellpadding="3">
						<tr>
							<td>', $txt['smf204'], ':</td><td>', $context['num_attachments'], '</td>
						</tr><tr>
							<td>', $txt['attachment_manager_total_avatars'], ':</td><td>', $context['num_avatars'], '</td>
						</tr><tr>
							<td>', $txt['smf205'], ':</td><td>', $context['attachment_total_size'], ' ', $txt['smf211'], ' <a href="', $scripturl, '?action=manageattachments;sa=maintain">[', $txt['attachment_manager_recount'], ']</a></td>
						</tr><tr>
							<td>', $txt['smf206'], ':</td><td>', isset($context['attachment_space']) ? $context['attachment_space'] . ' ' . $txt['smf211'] : $txt['smf215'], '</td>
						</tr>
					</table>
				</td>
			</tr><tr>
				<td class="catbg">', $txt['smf207'], '</td>
			</tr><tr>
				<td class="windowbg2" width="100%" valign="top">
					<form action="', $scripturl, '?action=manageattachments;sa=byAge" method="post" onsubmit="return confirm(\'', $txt['confirm_delete_attachments'], '\');" style="margin: 0 0 2ex 0;">
						', $txt[72], ': <input type="text" name="notice" value="', $txt['smf216'], '" size="40" /><br />
						', $txt['smf209'], ' <input type="text" name="age" value="25" size="4" /> ', $txt[579], ' <input type="submit" name="submit" value="', $txt[31], '" />
						<input type="hidden" name="type" value="attachments" />
						<input type="hidden" name="sc" value="', $context['session_id'], '" />
					</form>
					<form action="', $scripturl, '?action=manageattachments;sa=bySize" method="post" onsubmit="return confirm(\'', $txt['confirm_delete_attachments'], '\');" style="margin: 0 0 2ex 0;">
						', $txt[72], ': <input type="text" name="notice" value="', $txt['smf216'], '" size="40" /><br />
						', $txt['smf210'], ' <input type="text" name="size" id="size" value="100" size="4" /> ', $txt['smf211'], ' <input type="submit" name="submit" value="', $txt[31], '" />
						<input type="hidden" name="type" value="attachments" />
						<input type="hidden" name="sc" value="', $context['session_id'], '" />
					</form>
					<form action="', $scripturl, '?action=manageattachments;sa=byAge" method="post" onsubmit="return confirm(\'', $txt['confirm_delete_attachments'], '\');" style="margin: 0 0 2ex 0;">
						', $txt['attachment_manager_avatars_older'], ' <input type="text" name="age" value="45" size="4" /> ', $txt[579], ' <input type="submit" name="submit" value="', $txt[31], '" />
						<input type="hidden" name="type" value="avatars" />
						<input type="hidden" name="sc" value="', $context['session_id'], '" />
					</form>
				</td>
			</tr>
		</table>
		<br />
		<form action="', $scripturl, '?action=manageattachments;sa=remove;start=', $context['start'], '" method="post" onsubmit="return confirm(\'', $txt['confirm_delete_attachments'], '\');">
			<table border="0" align="center" cellspacing="1" cellpadding="4" class="bordercolor" width="100%">
				<tr class="titlebg">
					<td colspan="5">', $txt['attachment_manager_browse_files'], '</td>
				</tr><tr class="catbg">
					<td colspan="5">';

	if (!$context['browse_avatars'])
		echo '
						<img src="' . $settings['images_url'] . '/selected.gif" alt="&gt;" /><b>', $txt['attachment_manager_attachments'], '</b>&nbsp;|
						<a href="', $scripturl, '?action=manageattachments;avatars">', $txt['attachment_manager_avatars'], '</a>';
	else
				echo '
						<a href="', $scripturl, '?action=manageattachments">', $txt['attachment_manager_attachments'], '</a>&nbsp;|
						<img src="' . $settings['images_url'] . '/selected.gif" alt="&gt;" /><b>', $txt['attachment_manager_avatars'], '</b>';

	echo '
					</td>
				</tr><tr class="titlebg">
					<td nowrap="nowrap"><a href="', $scripturl, '?action=manageattachments;', $context['browse_avatars'] ? 'avatars;' : '', 'start=', $context['start'], ';sort=name', $context['sort_by'] == 'name' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt['smf213'], $context['sort_by'] == 'name' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a></td>
					<td nowrap="nowrap"><a href="', $scripturl, '?action=manageattachments;', $context['browse_avatars'] ? 'avatars;' : '', 'start=', $context['start'], ';sort=size', $context['sort_by'] == 'size' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt['smf214'], $context['sort_by'] == 'size' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a></td>
					<td nowrap="nowrap"><a href="', $scripturl, '?action=manageattachments;', $context['browse_avatars'] ? 'avatars;' : '', 'start=', $context['start'], ';sort=member', $context['sort_by'] == 'member' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', empty($context['browse_avatars']) ? $txt[279] : $txt['attachment_manager_member'], $context['sort_by'] == 'member' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a></td>
					<td nowrap="nowrap"><a href="', $scripturl, '?action=manageattachments;', $context['browse_avatars'] ? 'avatars;' : '', 'start=', $context['start'], ';sort=date', $context['sort_by'] == 'date' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', empty($context['browse_avatars']) ? $txt[317] : $txt['attachment_manager_last_active'], $context['sort_by'] == 'date' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a></td>
					<td nowrap="nowrap" align="center"><input type="checkbox" onclick="invertAll(this, this.form);" class="check" /></td>
				</tr>';
	$alternate = false;
	foreach ($context['posts'] as $post)
	{
		echo '
				<tr class="', $alternate ? 'windowbg' : 'windowbg2', '">
					<td>', $post['attachment']['link'], '</td>
					<td align="right">', $post['attachment']['size'], $txt['smf211'], '</td>
					<td>', $post['poster']['link'], '</td>
					<td>', $post['time'], !$context['browse_avatars'] ? '<br />' . $txt['smf88'] . ' ' . $post['link'] : '', '</td>
					<td align="center"><input type="checkbox" name="remove[' ,$post['attachment']['id'], ']" class="check" /></td>
				</tr>';
		$alternate = !$alternate;
	}
	echo '
				<tr class="', $alternate ? 'windowbg' : 'windowbg2', '">
					<td align="right" colspan="5"><input type="submit" value="', $txt['smf138'], '" /></td>
				</tr>
				<tr class="catbg">
					<td align="left" colspan="5" style="padding: 5px;"><b>', $txt[139], ':</b> ', $context['page_index'], '</td>
				</tr>
			</table>

			<input type="hidden" name="sc" value="', $context['session_id'], '" />
			<input type="hidden" name="type" value="', $context['browse_avatars'] ? 'avatars' : 'attachments', '" />
		</form>';
}

?>