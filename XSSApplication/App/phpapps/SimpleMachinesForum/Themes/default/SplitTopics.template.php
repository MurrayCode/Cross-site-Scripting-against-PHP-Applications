<?php
// Version: 1.0; SplitTopics

function template_ask()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
	<form action="', $scripturl, '?action=splittopics;sa=2;topic=', $context['current_topic'], '.0" method="post">
		<input type="hidden" name="at" value="', $context['message']['id'], '" />
		<table border="0" width="400" cellspacing="0" cellpadding="3" align="center" class="tborder">
			<tr class="titlebg">
				<td>', $txt['smf251'], '</td>
			</tr><tr class="windowbg">
				<td align="center" style="padding-top: 2ex; padding-bottom: 1ex;">
					<b><label for="subname">', $txt['smf254'], '</label>:</b> <input type="text" name="subname" id="subname" value="', $context['message']['subject'], '" size="25" /><br />
					<br />
					<input type="radio" name="step2" value="onlythis" checked="checked" class="check" /> ', $txt['smf255'], '<br />
					<input type="radio" name="step2" value="afterthis" class="check" /> ', $txt['smf256'], '<br />
					<input type="radio" name="step2" value="selective" class="check" /> ', $txt['smf257'], '<br />
					<br />
					<input type="submit" value="', $txt['smf251'], '" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
	</form>';
}

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<table border="0" width="400" cellspacing="1" class="bordercolor" cellpadding="4" align="center">
			<tr class="titlebg">
				<td>' . $txt['smf251'] . '</td>
			</tr><tr>
				<td class="windowbg" valign="middle" align="center">
					' . $txt['smf259'] . '<br /><br />
					<a href="' . $scripturl . '?board=' . $context['current_board'] . '.0">' . $txt[101] . '</a><br />
					<a href="' . $scripturl . '?topic=' . $context['old_topic'] . '.0">' . $txt['smf260'] . '</a><br />
					<a href="' . $scripturl . '?topic=' . $context['new_topic'] . '.0">' . $txt['smf258'] . '</a>
				</td>
			</tr>
		</table>';
}

function template_select()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<form action="', $scripturl, '?action=splittopics;sa=4;board=' . $context['current_board'] . '.0" method="post"><input type="hidden" name="topic" value="' . $context['current_topic'] . '" />
			<table border="0" width="80%" cellspacing="1" class="bordercolor" cellpadding="4" align="center">
				<tr class="titlebg">
					<td>
						' . $txt['smf251'] . ' - ' . $txt['smf257'] . '
					</td>
				</tr>
				<tr>
					<td class="windowbg" valign="middle">
						' . $txt['smf261'] . '
					</td>
				</tr>
				<tr>
					<td class="catbg" height="18">
						<b>' . $txt[139] . ':</b> ' . $context['page_index'] . '
					</td>
				</tr>';
	foreach ($context['messages'] as $message)
		echo '
				<tr>
					<td class="windowbg" valign="middle">
						<span class="smalltext">
							<input type="checkbox" name="' . 'selpost[' . $message['id'] . ']" class="check" /> ' . $message['subject'] . ' - ' . $message['poster'] . '<br />
							' . $message['body'] . '
						</span>
					</td>
				</tr>';
	echo '
			</table>
			<p align="center">
				<input type="hidden" name="subname" value="' . $context['new_subject'] . '" />
				<input type="submit" value="' . $txt['smf251'] . '" />
			</p>
			<input type="hidden" name="sc" value="' . $context['session_id'] . '" />
		</form>';
}

function template_merge_done()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<table border="0" width="400" cellspacing="1" class="bordercolor" cellpadding="4" align="center">
			<tr class="titlebg">
				<td>' . $txt['smf252'] . '</td>
			</tr><tr>
				<td class="windowbg" valign="middle" align="center">
					<br />
					' . $txt['smf264'] . '<br />
					<br />
					<a href="' . $scripturl . '?board=' . $context['target_board'] . '.0">' . $txt[101] . '</a><br />
					<a href="' . $scripturl . '?topic=' . $context['target_topic'] . '.0">' . $txt['smf265'] . '</a>
				</td>
			</tr>
		</table>';
}

function template_merge()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<form action="' . $scripturl . '?action=mergetopics;from=' . $context['origin_topic'] . ';targetboard=' . $context['target_board'] . ';board=' . $context['current_board'] . '.0" method="post">
			<table border="0" width="540" cellspacing="1" class="bordercolor" cellpadding="4" align="center">
				<tr class="titlebg">
					<td>' . $txt['smf252'] . '</td>
				</tr>
				<tr>
					<td class="windowbg">' . $txt['smf276'] . '</td>
				</tr>
				<tr>
					<td colspan="2" class="catbg">
						<table cellpadding="0" cellspacing="0" border="0"><tr>
							<td><b>' . $txt[139] . ':</b> ' . $context['page_index'] . '</td>
						</tr></table>
					</td>
				</tr>
				<tr>
					<td class="windowbg" valign="middle" align="center">
						<table border="0">
							<tr>
								<td align="right"><br /><b>' . $txt['smf266'] . ':</b> <br /></td>
								<td align="left"><input type="hidden" name="from" value="' . $context['origin_topic'] . '" /><br />' . $context['origin_subject'] . '</td>
							</tr><tr>
								<td align="right"><br /><b>' . $txt['smf267'] . ':</b></td>
								<td align="left">
									<br />
									<select name="targetboard">';
	foreach ($context['boards'] as $board)
		echo '
										<option value="' . $board['id'] . '" ' . ($board['id'] == $context['target_board'] ? 'selected="selected"' : '') . '>' . $board['category'] . ' - ' . $board['name'] . '</option>';
	echo '
									</select> <input type="submit" value="' . $txt[462] . '" />
								</td>
							</tr><tr>
								<td align="right" valign="top"><br /><b>' .  $txt['smf269'] . ':</b></td>
								<td align="left" style="white-space: nowrap;">
									<br />
									<table>';
	foreach ($context['topics'] as $topic)
		echo '
										<tr>
											<td valign="middle">
												<a href="' . $scripturl . '?action=mergetopics;sa=2;board=' . $context['current_board'] . '.0;from=' . $context['origin_topic'] . ';to=' . $topic['id'] . ';sesc=' . $context['session_id'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/merge.gif" alt="' . $txt['smf252'] . '" border="0" />' : $txt['smf252']) . '</a>&nbsp;
											</td>
											<td valign="middle" style="white-space: nowrap;">
												<a href="' . $scripturl . '?topic=' . $topic['id'] . '.0" target="_blank">' . $topic['subject'] . '</a> ' . $txt[109] . ' ' . $topic['poster']['link'] . '
											</td>
										</tr>';
	echo '
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="catbg">
						<table cellpadding="0" cellspacing="0" border="0"><tr>
							<td><b>' . $txt[139] . ':</b> ' . $context['page_index'] . '</td>
						</tr></table>
					</td>
				</tr>
			</table>
		</form>';
}

function template_merge_extra_options()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<form action="' . $scripturl . '?action=mergetopics;sa=2;" method="post" name="mergeForm">
			<table border="0" width="100%" cellspacing="1" class="bordercolor" cellpadding="4" align="center">
				<tr class="titlebg">
					<td>' . $txt['smf252'] . '</td>
				</tr><tr>
					<td class="catbg">' . $txt['merge_topic_list'] . '</td>
				</tr><tr>
					<td class="windowbg" style="padding: 15px;">
						<table border="0" cellspacing="1" cellpadding="2" width="100%" align="center" class="bordercolor">
							<tr class="titlebg">
								<td>' . $txt['merge_check'] . '</td>
								<td>' . $txt[70] . '</td>
								<td>' . $txt[109] . '</td>
								<td>' . $txt[111] . '</td>
								<td width="70">' . $txt['merge_include_notifications'] . '</td>
							</tr>';
	foreach ($context['topics'] as $topic)
		echo '
							<tr>
								<td class="windowbg2" valign="middle">
									<input type="checkbox" class="check" name="topics[]" value="' . $topic['id'] . '" checked="checked" />
								</td>
								<td class="windowbg2" valign="middle">
									<a href="' . $scripturl . '?topic=' . $topic['id'] . '.0" target="_blank">' . $topic['subject'] . '</a>
								</td>
								<td class="windowbg2" valign="middle">
									' . $topic['started']['link'] . '<br />
									<span class="smalltext">' . $topic['started']['time'] . '</span>
								</td>
								<td class="windowbg2" valign="middle">
									' . $topic['updated']['link'] . '<br />
									<span class="smalltext">' . $topic['updated']['time'] . '</span>
								</td>
								<td class="windowbg2" valign="middle">
									<input type="checkbox" class="check" name="notifications[]" value="' . $topic['id'] . '" checked="checked" />
								</td>
							</tr>';
	echo '
						</table>
						<br />
						<br />';

	echo '
						', $txt['merge_select_subject'], ': <select name="subject" onchange="document.mergeForm.customSubject.disabled = this.options[this.selectedIndex].value != 0;">';
	foreach ($context['topics'] as $topic)
		echo '
							<option value="', $topic['id'], '"' . ($topic['selected'] ? ' selected="selected"' : '') . '>', $topic['subject'], '</option>';
	echo '
							<option value="0">', $txt['merge_custom_subject'], ':</option>
						</select> <input type="text" name="custom_subject" size="60" disabled="disabled" id="customSubject" /><br />
						<br />
						<input type="checkbox" class="check" name="enforce_subject" value="1" /> ', $txt['merge_enforce_subject'], '
					</td>
				</tr>';

	if (!empty($context['boards']))
	{
		echo '
				<tr>
					<td class="catbg">' . $txt['merge_select_target_board'] . '</td>
				</tr><tr>
					<td class="windowbg"><table border="0" cellspacing="0" cellpadding="0">';
		foreach ($context['boards'] as $board)
			echo '
						<tr>
							<td>
								<input type="radio" name="board" value="' . $board['id'] . '"' . ($board['selected'] ? 'checked="checked"' : '') . ' class="check" /> ' . $board['name'] . '
							</td>
						</tr>';
		echo '
					</table></td>
				</tr>';
	}
	if (!empty($context['polls']))
	{
		echo '
				<tr>
					<td class="catbg">' . $txt['merge_select_poll'] . '</td>
				</tr><tr>
					<td class="windowbg"><table border="0" cellspacing="0" cellpadding="3">';
		foreach ($context['polls'] as $poll)
			echo '
						<tr>
							<td>
								<input type="radio" name="poll" value="' . $poll['id'] . '"' . ($poll['selected'] ? 'checked="checked"' : '') . ' class="check" /> ' . $poll['question'] . ' (' . $txt[118] . ': <a href="' . $scripturl . '?topic=' . $poll['topic']['id'] . '.0" target="_blank">' .  $poll['topic']['subject'] . '</a>)
							</td>
						</tr>';
		echo '
						<tr>
							<td>
								<input type="radio" name="poll" value="-1" class="check" /> (' . $txt['merge_no_poll'] . ')
							</td>
						</tr>
					</table></td>
				</tr>';
	}
	echo '
				<tr>
					<td class="windowbg" align="right">
						<input type="submit" value="' . $txt['smf252'] . '" />
						<input type="hidden" name="sa" value="2" />
					</td>
				</tr>
			</table>
		</form>';
}

?>