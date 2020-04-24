<?php
// Version: 1.0; Post

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $months;

	if ($context['show_spellchecking'])
		echo '
		<script language="JavaScript1.2" type="text/javascript" src="' . $settings['default_theme_url'] . '/spellcheck.js"></script>';

	echo '
		<script language="JavaScript1.2" type="text/javascript"><!--';

	if (!$context['user']['is_guest'])
		echo '
			setTimeout("fetchSession();", 600000);
			function fetchSession()
			{
				document.getElementById("fetchSessionTemp").src = "', $scripturl, '?action=jsoption;sesc=', $context['session_id'], ';" + (new Date().getTime());
				setTimeout("fetchSession();", 600000);
			}';

	echo '
			function showimage()
			{
				document.images.icons.src = "' . $settings['images_url'] . '/post/" + document.postmodify.icon.options[document.postmodify.icon.selectedIndex].value + ".gif";
			}';

	// This function caches attachment paths so they can be used if "preview" is used.
	echo '
			function updateAttachmentCache()
			{
				if (typeof(document.postmodify.attachmentPreview) == "undefined")
					return;

				document.postmodify.attachmentPreview.value = "";

				if (typeof(document.postmodify["attachment[]"].length) != "undefined")
				{
					var tempArray = [];

					for (var i = 0; i < document.postmodify["attachment[]"].length; i++)
						tempArray[i] = document.postmodify["attachment[]"][i].value;

					document.postmodify.attachmentPreview.value = tempArray.join(", ");
				}
				else
					document.postmodify.attachmentPreview.value = document.postmodify["attachment[]"].value;
			}';

	if (!empty($settings['additional_options_collapsable']))
		echo '
			var currentSwap = false;
			function swapOptions()
			{
				document.getElementById("postMoreExpand").src = smf_images_url + "/" + (currentSwap ? "collapse.gif" : "expand.gif");
				document.getElementById("postMoreExpand").alt = currentSwap ? "-" : "+";

				document.getElementById("postMoreOptions").style.display = currentSwap ? "" : "none";

				if (document.getElementById("postAttachment"))
					document.getElementById("postAttachment").style.display = currentSwap ? "" : "none";
				if (document.getElementById("postAttachment2"))
					document.getElementById("postAttachment2").style.display = currentSwap ? "" : "none";

				currentSwap = !currentSwap;
			}';

	// If this is a poll - use some javascript to ensure the user doesn't create a poll with illegal option combinations.
	if ($context['make_poll'])
		echo '
			function pollOptions()
			{
				var expireTime = document.getElementById("poll_expire");

				if (isEmptyText(expireTime) || expireTime.value == 0)
				{
					document.postmodify.poll_hide[2].disabled = true;
					if (document.postmodify.poll_hide[2].checked)
						document.postmodify.poll_hide[1].checked = true;
				}
				else
					document.postmodify.poll_hide[2].disabled = false;
			}

			var pollOptionNum = 0;
			function addPollOption()
			{
				if (pollOptionNum == 0)
				{
					for (var i = 0; i < document.postmodify.elements.length; i++)
						if (document.postmodify.elements[i].id.substr(0, 8) == "options[")
							pollOptionNum++;
				}
				pollOptionNum++

				setOuterHTML(document.getElementById("pollMoreOptions"), \'<br /><label for="options[\' + pollOptionNum + \']">', $txt['smf22'], ' \' + pollOptionNum + \'</label>: <input type="text" name="options[\' + pollOptionNum + \']" id="options[\' + pollOptionNum + \']" value="" size="25" /><span id="pollMoreOptions"></span>\');
			}';

	if ($context['make_event'])
		echo '
			var monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

			function generateDays()
			{
				var days = 0, selected = 0;
				var dayElement = document.getElementById("day"), yearElement = document.getElementById("year"), monthElement = document.getElementById("month");

				monthLength[1] = 28;
				if (yearElement.options[yearElement.selectedIndex].value % 4 == 0)
					monthLength[1] = 29;

				selected = dayElement.selectedIndex;
				while (dayElement.options.length)
					dayElement.options[0] = null;

				days = monthLength[monthElement.value - 1];

				for (i = 1; i <= days; i++)
					dayElement.options[dayElement.length] = new Option(i, i);

				if (selected < days)
					dayElement.selectedIndex = selected;
			}';

	echo '
		// --></script>

		<form action="', $scripturl, '?action=', $context['destination'], ';', empty($context['current_board']) ? '' : 'board=' . $context['current_board'], '" method="post" name="postmodify" onsubmit="submitonce(this);" enctype="multipart/form-data">
			<table width="75%" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td valign="bottom" colspan="2">
						', theme_linktree(), '
					</td>
				</tr>
			</table>';

	if (isset($context['preview_message']))
		echo '

			<table border="0" width="75%" cellspacing="1" cellpadding="3" class="bordercolor" align="center">
				<tr class="titlebg">
					<td>' . $context['preview_subject'] . '</td>
				</tr>
				<tr>
					<td class="windowbg">
						' . $context['preview_message'] . '
					</td>
				</tr>
			</table><br />';

	if ($context['make_event'] && (!$context['event']['new'] || !empty($context['current_board'])))
		echo '
			<input type="hidden" name="eventid" value="', $context['event']['id'], '">';

	echo '
			<table border="0"  width="75%" align="center" cellspacing="1" cellpadding="3" class="bordercolor">
				<tr class="titlebg">
					<td>' . $context['page_title'] . '</td>
				</tr>
				<tr>
					<td class="windowbg">' . (isset($context['current_topic']) ? '
						<input type="hidden" name="topic" value="' . $context['current_topic'] . '" />' : '') . '
						<table border="0" cellpadding="3" width="100%">';

	// If an error occurred, explain what happened.
	if (!empty($context['post_error']['messages']))
	{
		echo '
							<tr>
								<td></td>
								<td align="left">
									', $context['error_type'] == 'serious' ? '<b>' . $txt['error_while_submitting'] . '</b>' : '', '
									<div style="color: red; margin: 1ex 0 2ex 3ex;">
										', implode('<br />', $context['post_error']['messages']), '
									</div>
								</td>
							</tr>';
	}

	// If it's locked, show a message to warn the replyer.
	if ($context['locked'])
		echo '
							<tr>
								<td></td>
								<td align="left">
									', $txt['smf287'], '
								</td>
							</tr>';

	// Guests have to put in their name and email...
	if (isset($context['name']) && isset($context['email']))
		echo '
							<tr>
								<td align="right">
									<b', isset($context['post_error']['long_name']) || isset($context['post_error']['no_name']) || isset($context['post_error']['bad_name']) ? ' style="color: #FF0000;"' : '', '>', $txt[68], ':</b>
								</td>
								<td>
									<input type="text" name="guestname" size="25" value="', $context['name'], '" />
								</td>
							</tr>
							<tr>
								<td align="right">
									<b', isset($context['post_error']['no_email']) || isset($context['post_error']['bad_email']) ? ' style="color: #FF0000;"' : '', '>', $txt[69], ':</b>
								</td>
								<td>
									<input type="text" name="email" size="25" value="', $context['email'], '" />
								</td>
							</tr>';

	if ($context['make_event'])
	{
		echo '
							<tr>
								<td align="right"><b', isset($context['post_error']['no_event']) ? ' style="color: #FF0000;"' : '', '>', $txt['calendar12'], '</b></td>
								<td class="smalltext"><input type="text" name="evtitle" maxlength="30" size="30" value="', $context['event']['title'], '" /></td>
							</tr><tr>
								<td></td>
								<td class="smalltext">
									<input type="hidden" name="calendar" value="1" />', $txt['calendar10'], '&nbsp;
									<select name="year" onchange="generateDays()">';

		for ($year = $modSettings['cal_minyear']; $year <= $modSettings['cal_maxyear']; $year++)
			echo '
										<option value="', $year, '"', $year == $context['event']['year'] ? ' selected="selected"' : '', '>', $year, '</option>';

		echo '
									</select>&nbsp;
									', $txt['calendar9'], '&nbsp;
									<select name="month" onchange="generateDays()">';

		for ($month = 1; $month <= 12; $month++)
			echo '
										<option value="', $month, '"', $month == $context['event']['month'] ? ' selected="selected"' : '', '>', $months[$month], '</option>';

		echo '
									</select>&nbsp;
									', $txt['calendar11'], '&nbsp;
									<select name="day">';

		for ($day = 1; $day <= $context['event']['last_day']; $day++)
			echo '
										<option value="', $day, '"', $day == $context['event']['day'] ? ' selected="selected"' : '', '>', $day, '</option>';

		echo '
									</select>
								</td>
							</tr>';

		if ($context['event']['new'] && !empty($modSettings['cal_allowspan']))
		{
			echo '
							<tr>
								<td align="right"><b>', $txt['calendar54'], '</b></td>
								<td class="smalltext">
									<select name="span">';

			for ($days = 1; $days <= $modSettings['cal_maxspan']; $days++)
				echo '
										<option value="', $days, '"', $days == $context['event']['span'] ? ' selected="selected"' : '', '>', $days, '</option>';

			echo '
									</select>
								</td>
							</tr>';
		}

		if ($context['event']['new'] && $context['is_new_topic'])
		{
			echo '
							<tr>
								<td align="right"><b>', $txt['calendar13'], '</b></td>
								<td class="smalltext">
									<select name="board">';

			foreach ($context['event']['boards'] as $board)
				echo '
										<option value="', $board['id'], '"', $board['id'] == $context['event']['board'] ? ' selected="selected"' : '', '>', $board['cat']['name'], ' - ', $board['prefix'], $board['name'], '</option>';

			echo '
									</select>
								</td>
							</tr>';
		}
	}

	echo '
							<tr>
								<td align="right">
									<b' . (isset($context['post_error']['no_subject']) ? ' style="color: #FF0000;"' : '') . '>' . $txt[70] . ':</b>
								</td>
								<td>
									<input type="text" name="subject"', $context['subject'] == '' ? '' : ' value="' . $context['subject'] . '"', ' size="40" maxlength="80" tabindex="1" />
								</td>
							</tr>
							<tr>
								<td align="right">
									<b>' . $txt[71] . ':</b>
								</td>
								<td>
									<select name="icon" onchange="showimage()">';
	foreach ($context['icons'] as $icon)
		echo '
										<option value="', $icon['value'], '"', $icon['value'] == $context['icon'] ? ' selected="selected"' : '', '>', $icon['name'], '</option>';
	echo '
									</select>
									<img src="' . $settings['images_url'] . '/post/' . $context['icon'] . '.gif" name="icons" border="0" hspace="15" alt="" />
								</td>
							</tr>';
	if ($context['make_poll'])
	{
		echo '
							<tr>
								<td align="right">
									<b' . (isset($context['post_error']['no_question']) ? ' style="color: #FF0000;"' : '') . '>' . $txt['smf21'] . ':</b>
								</td>
								<td align="left">
									<input type="text" name="question" value="' . (isset($context['question']) ? $context['question'] : '') . '" size="40" />
								</td>
							</tr>
							<tr>
								<td align="right"></td>
								<td>';

		// Loop through all the choices and print them out.
		foreach ($context['choices'] as $choice)
		{
			echo '
									<label for="options[', $choice['id'], ']">', $txt['smf22'], ' ', $choice['number'], '</label>: <input type="text" name="options[', $choice['id'], ']" id="options[', $choice['id'], ']" value="', $choice['label'], '" size="25" />';

			if (!$choice['is_last'])
				echo '<br />';
		}

		echo '
									<span id="pollMoreOptions"></span> <a href="javascript:addPollOption(); void(0);">(', $txt['poll_add_option'], ')</a>
								</td>
							</tr>
							<tr>
								<td align="right"><b>', $txt['poll_options'], ':</b></td>
								<td class="smalltext"><input type="text" name="poll_max_votes" size="2" value="', $context['poll_options']['max_votes'], '" /> ', $txt['poll_options5'], '</td>
							</tr>
							<tr>
								<td align="right"></td>
								<td class="smalltext">', $txt['poll_options1a'], ' <input type="text" name="poll_expire" size="2" value="', $context['poll_options']['expire'], '" onchange="pollOptions();" /> ', $txt['poll_options1b'], '</td>
							</tr>
							<tr>
								<td align="right"></td>
								<td class="smalltext">
									<input type="radio" name="poll_hide" value="0"', $context['poll_options']['hide'] == 0 ? ' checked="checked"' : '', ' class="check" /> ', $txt['poll_options2'], '<br />
									<input type="radio" name="poll_hide" value="1"', $context['poll_options']['hide'] == 1 ? ' checked="checked"' : '', ' class="check" /> ', $txt['poll_options3'], '<br />
									<input type="radio" name="poll_hide" value="2"', $context['poll_options']['hide'] == 2 ? ' checked="checked"' : '', empty($context['poll_options']['expire']) ? ' disabled="disabled"' : '', ' class="check" /> ', $txt['poll_options4'], '<br />
									<br />
								</td>
							</tr>';
	}

	theme_postbox($context['message']);

	if (isset($context['last_modified']))
		echo '
									<tr>
										<td valign="top" align="right">
											<b>' . $txt[211] . ':</b>
										</td>
										<td>
											' . $context['last_modified'] . '
										</td>
									</tr>';

	if (!empty($settings['additional_options_collapsable']))
		echo '
									<tr>
										<td colspan="2" style="padding-left: 5ex;">
											<a href="javascript:swapOptions();"><img src="', $settings['images_url'], '/expand.gif" alt="+" border="0" id="postMoreExpand" /></a> <a href="javascript:swapOptions();"><b>', $txt['post_additionalopt'], '</b></a>
										</td>
									</tr>';
	echo '
									<tr>
										<td></td>
										<td>
											<div id="postMoreOptions">
												<table width="80%" cellpadding="0" cellspacing="0" border="0">
													<tr>
														<td class="smalltext">', $context['can_notify'] ? '<input type="hidden" name="notify" value="0" /><input type="checkbox" name="notify"' . ($context['notify'] || !empty($options['auto_notify']) ? ' checked="checked"' : '') . ' value="1" class="check" /> ' . $txt['smf14'] : '', '</td>
														<td class="smalltext">', $context['can_lock'] ? '<input type="hidden" name="lock" value="0" /><input type="checkbox" name="lock"' . ($context['locked'] ? ' checked="checked"' : '') . ' value="1" class="check" /> ' . $txt['smf15'] : '', '</td>
													</tr>
													<tr>
														<td class="smalltext"><input type="checkbox" name="goback"' . ($context['back_to_topic'] || !empty($options['return_to_post']) ? ' checked="checked"' : '') . ' value="1" class="check" /> ' . $txt['back_to_topic'] . '</td>
														<td class="smalltext">', $context['can_sticky'] ? '<input type="hidden" name="sticky" value="0" /><input type="checkbox" name="sticky"' . ($context['sticky'] ? ' checked="checked"' : '') . ' value="1" class="check" /> ' . $txt['sticky_after2'] : '', '</td>
													</tr>
													<tr>
														<td class="smalltext"><input type="checkbox" name="ns"' . ($context['use_smileys'] ? '' : ' checked="checked"') . ' value="NS" class="check" /> ' . $txt[277] . '</td>', '
														<td class="smalltext">', $context['can_move'] ? '<input type="hidden" name="move" value="0" /><input type="checkbox" name="move" value="1" class="check" /> ' . $txt['move_after2'] : '', '</td>
													</tr>', $context['can_announce'] && $context['is_first_post'] ? '
													<tr>
														<td class="smalltext"><label for="check_announce"><input type="checkbox" name="announce_topic" id="check_announce" value="1" class="check" /> ' . $txt['announce_topic'] . '</label></td>
														<td class="smalltext"></td>
													</tr>' : '', '
												</table>
											</div>
										</td>
									</tr>';

	if (!empty($context['current_attachments']))
	{
		echo '
							<tr id="postAttachment">
								<td align="right" valign="top">
									<b>' . $txt['smf119b'] . ':</b>
								</td>
								<td class="smalltext">
									<input type="hidden" name="attach_del[]" value="0" />
									', $txt['smf130'], ':<br />';
		foreach ($context['current_attachments'] as $attachment)
			echo '
									<input type="checkbox" name="attach_del[]" value="', $attachment['id'], '" checked="checked" class="check" /> ' . $attachment['name'] . '<br />';
		echo '
									<br />
								</td>
							</tr>';
	}
	if ($context['can_post_attachment'])
		echo '
							<tr id="postAttachment2">
								<td align="right" valign="top">
									<b>' . $txt['smf119'] . ':</b>
								</td>
								<td class="smalltext">
									' . ($context['attached'] != '' ? $txt['attach_preview'] . ': ' . $context['attached'] . '<br />' : '') . '
									<input type="file" size="48" name="attachment[]" onchange="updateAttachmentCache();" /><br />
									<input type="file" size="48" name="attachment[]" onchange="updateAttachmentCache();" /><br />
									' . (!empty($modSettings['attachmentCheckExtensions']) ? $txt['smf120'] . ': ' . $context['allowed_extensions'] . '<br />' : '') . '
									' . $txt['smf121'] . ': ' . $modSettings['attachmentSizeLimit'] . ' ' . $txt['smf211'] . '
									<input type="hidden" name="attachmentPreview" value="" />
								</td>
							</tr>';

	echo '
							<tr>
								<td align="center" colspan="2">';
	if (!empty($settings['additional_options_collapsable']) && empty($context['attached']))
		echo '
									<script language="JavaScript" type="text/javascript"><!--
										swapOptions();
									// --></script>';
	echo '
									<span class="smalltext"><br />' . $txt['smf16'] . '</span><br />
									<input type="submit" name="post" value="' . $context['submit_label'] . '" onclick="return submitThisOnce(this);" accesskey="s" tabindex="3" />
									<input type="submit" name="preview" value="' . $txt[507] . '" onclick="return (typeof(document.postmodify.attachmentPreview) == &quot;undefined&quot; || !document.postmodify.attachmentPreview.value || confirm(\'' . $txt['attach_lose'] . '\')) &amp;&amp; submitThisOnce(this);" accesskey="p" tabindex="4" />';

	if ($context['make_event'] && !$context['event']['new'])
		echo '
									<input type="submit" name="deleteevent" value="', $txt['calendar22'], '" onclick="return confirm(\'', $txt['calendar21'], '\');" />';

	if ($context['show_spellchecking'])
		echo '
									<input type="button" value="', $txt['spell_check'], '" onclick="spellCheck(\'postmodify\', \'message\');" />';

	echo '<img src="', $settings['images_url'], '/blank.gif" alt="" id="fetchSessionTemp" />
								</td>
							</tr>
							<tr>
								<td colspan="2"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>';

	// Assuming this isn't a new topic pass across the number of replies when the topic was created.
	if (isset($context['num_replies']))
		echo '
			<input type="hidden" name="num_replies" value="', $context['num_replies'], '" />';

	echo '
			<input type="hidden" name="sc" value="' . $context['session_id'] . '" />
			<input type="hidden" name="seqnum" value="', $context['form_sequence_number'], '" />
		</form>';

	if ($context['show_spellchecking'])
		echo '
		<form name="spell_form" id="spell_form" method="post" target="spellWindow" action="', $scripturl, '?action=spellcheck"><input type="hidden" name="spell_formname" value="" /><input type="hidden" name="spell_fieldname" value="" /><input type="hidden" name="spellstring" value="" /></form>';

	if (isset($context['previous_posts']) && count($context['previous_posts']) > 0)
	{
		echo '
			<br /><br />
			<table cellspacing="1" cellpadding="0" width="75%" align="center" class="bordercolor">
				<tr>
					<td>
						<table class="windowbg" cellspacing="0" cellpadding="2" width="100%" align="center">
							<tr class="titlebg">
								<td colspan="2">' . $txt[468] . '</td>
							</tr>';
		foreach ($context['previous_posts'] as $post)
			echo '
							<tr class="catbg">
								<td align="left" class="smalltext">
									' . $txt[279] . ': ' . $post['poster'] . '
								</td>
								<td align="right" class="smalltext">
									' . $txt[280] . ': ' . $post['time'] . '
								</td>
							</tr><tr class="windowbg2">
								<td align="right" colspan="2" class="smalltext">
									<a href="#top" onclick="reqWin(\'' . $scripturl . '?action=quotefast;quote=' . $post['id'] . ';sesc=' . $context['session_id'] . '\', 240, 90);">' . $txt[260] . '</a>
								</td>
							</tr><tr class="windowbg2">
								<td colspan="2" class="smalltext" id="msg' . $post['id'] . '">
									' . $post['message'] . '
								</td>
							</tr>';
		echo '
						</table>
					</td>
				</tr>
			</table>';
	}
}

function template_postbox(&$message)
{
	global $context, $settings, $options, $txt;

	if ($context['show_bbc'])
	{
		echo '
			<tr>
				<td align="right" valign="top">
					<b>' . $txt[252] . ':</b>
				</td>
				<td align="left" valign="middle">';

		$context['bbc_tags'] = array();
		$context['bbc_tags'][] = array(
			'bold' => array('code' => 'b', 'before' => '[b]', 'after' => '[/b]', 'description' => $txt[253]),
			'italicize' => array('code' => 'i', 'before' => '[i]', 'after' => '[/i]', 'description' => $txt[254]),
			'underline' => array('code' => 'u', 'before' => '[u]', 'after' => '[/u]', 'description' => $txt[255]),
			'strike' => array('code' => 's', 'before' => '[s]', 'after' => '[/s]', 'description' => $txt[441]),
			array(),
			'glow' => array('code' => 'glow', 'before' => '[glow=red,2,300]', 'after' => '[/glow]', 'description' => $txt[442]),
			'shadow' => array('code' => 'shadow', 'before' => '[shadow=red,left]', 'after' => '[/shadow]', 'description' => $txt[443]),
			'move' => array('code' => 'move', 'before' => '[move]', 'after' => '[/move]', 'description' => $txt[439]),
			array(),
			'pre' => array('code' => 'pre', 'before' => '[pre]', 'after' => '[/pre]', 'description' => $txt[444]),
			'left' => array('code' => 'left', 'before' => '[left]', 'after' => '[/left]', 'description' => $txt[445]),
			'center' => array('code' => 'center', 'before' => '[center]', 'after' => '[/center]', 'description' => $txt[256]),
			'right' => array('code' => 'right', 'before' => '[right]', 'after' => '[/right]', 'description' => $txt[446]),
			array(),
			'hr' => array('code' => 'hr', 'before' => '[hr]', 'description' => $txt[531]),
			array(),
			'size' => array('code' => 'size', 'before' => '[size=10pt]', 'after' => '[/size]', 'description' => $txt[532]),
			'face' => array('code' => 'font', 'before' => '[font=Verdana]', 'after' => '[/font]', 'description' => $txt[533]),
		);
		$context['bbc_tags'][] = array(
			'flash' => array('code' => 'flash', 'before' => '[flash=200,200]', 'after' => '[/flash]', 'description' => $txt[433]),
			'img' => array('code' => 'img', 'before' => '[img]', 'after' => '[/img]', 'description' => $txt[435]),
			'url' => array('code' => 'url', 'before' => '[url]', 'after' => '[/url]', 'description' => $txt[257]),
			'email' => array('code' => 'email', 'before' => '[email]', 'after' => '[/email]', 'description' => $txt[258]),
			'ftp' => array('code' => 'ftp', 'before' => '[ftp]', 'after' => '[/ftp]', 'description' => $txt[434]),
			array(),
			'table' => array('code' => 'table', 'before' => '[table]', 'after' => '[/table]', 'description' => $txt[436]),
			'tr' => array('code' => 'td', 'before' => '[tr]', 'after' => '[/tr]', 'description' => $txt[449]),
			'td' => array('code' => 'td', 'before' => '[td]', 'after' => '[/td]', 'description' => $txt[437]),
			array(),
			'sup' => array('code' => 'sup', 'before' => '[sup]', 'after' => '[/sup]', 'description' => $txt[447]),
			'sub' => array('code' => 'sub', 'before' => '[sub]', 'after' => '[/sub]', 'description' => $txt[448]),
			'tele' => array('code' => 'tt', 'before' => '[tt]', 'after' => '[/tt]', 'description' => $txt[440]),
			array(),
			'code' => array('code' => 'code', 'before' => '[code]', 'after' => '[/code]', 'description' => $txt[259]),
			'quote' => array('code' => 'quote', 'before' => '[quote]', 'after' => '[/quote]', 'description' => $txt[260]),
			array(),
			'list' => array('code' => 'list', 'before' => '[list]\n[li]', 'after' => '[/li]\n[li][/li]\n[/list]', 'description' => $txt[261]),
		);

		foreach ($context['bbc_tags'] as $i => $row)
		{
			foreach ($row as $image => $tag)
			{
				// Is this tag disabled?
				if (!empty($tag['code']) && !empty($context['disabled_tags'][$tag['code']]))
					continue;

				if (isset($tag['before']))
					echo '<a href="javascript:' . (isset($tag['after']) ? 'surround' : 'replace') . 'Text(\'' . $tag['before'] . '\'' . (isset($tag['after']) ? ', \'' . $tag['after'] . '\'' : '') . ', document.' . $context['post_form'] . '.' . $context['post_box_name'] . ');"><img src="' . $settings['images_url'] . '/bbc/' . $image . '.gif" align="bottom" width="23" height="22" alt="' . $tag['description'] . '" border="0" /></a>';
			}

			if ($i != count($context['bbc_tags']) - 1)
				echo '<br />';
		}

		echo '
					<select onchange="surroundText(\'[color=\'+this.options[this.selectedIndex].value+\']\',\'[/color]\', document.' . $context['post_form'] . '.' . $context['post_box_name'] . '); this.selectedIndex = 0;">
						<option value="" selected="selected">', $txt['change_color'], '</option>
						<option value="Black">' . $txt[262] . '</option>
						<option value="Red">' . $txt[263] . '</option>
						<option value="Yellow">' . $txt[264] . '</option>
						<option value="Pink">' . $txt[265] . '</option>
						<option value="Green">' . $txt[266] . '</option>
						<option value="Orange">' . $txt[267] . '</option>
						<option value="Purple">' . $txt[268] . '</option>
						<option value="Blue">' . $txt[269] . '</option>
						<option value="Beige">' . $txt[270] . '</option>
						<option value="Brown">' . $txt[271] . '</option>
						<option value="Teal">' . $txt[272] . '</option>
						<option value="Navy">' . $txt[273] . '</option>
						<option value="Maroon">' . $txt[274] . '</option>
						<option value="LimeGreen">' . $txt[275] . '</option>
					</select>
				</td>
			</tr>';
	}
	// Now start printing all of the smileys.
	if (!empty($context['smileys']['postform']))
	{
		echo '
			<tr>
				<td align="right"></td>
				<td valign="middle">';

		// Show each row of smileys ;).
		foreach ($context['smileys']['postform'] as $smiley_row)
		{
			foreach ($smiley_row['smileys'] as $smiley)
				echo '
					<a href="javascript:replaceText(\' ', $smiley['code'], '\', document.', $context['post_form'], '.', $context['post_box_name'], ');"><img src="', $settings['smileys_url'], '/', $smiley['filename'], '" align="bottom" alt="', $smiley['description'], '" title="', $smiley['description'], '" border="0" /></a>';

			// If this isn't the last row, show a break.
			if (empty($smiley_row['last']))
				echo '<br />';
		}

		// If the smileys popup is to be shown... show it!
		if (!empty($context['smileys']['popup']))
			echo '
					<a href="javascript:moreSmileys();">[', $txt['more_smileys'], ']</a>';

		echo '
				</td>
			</tr>';
	}

	// Show an extra link for addionial smileys (if there are any).
	if (!empty($context['smileys']['popup']))
	{
		echo '
			<script language="JavaScript" type="text/javascript"><!--
				var smileys = [';

		foreach ($context['smileys']['popup'] as $smiley_row)
		{
			echo '
					[';
			foreach ($smiley_row['smileys'] as $smiley)
			{
				echo '
						["', $smiley['code'], '","', $smiley['filename'], '","', $smiley['description'], '"]';
				if (empty($smiley['last']))
					echo ',';
			}

			echo ']';
			if (empty($smiley_row['last']))
				echo ',';
		}

		echo '];
				var smileyPopupWindow;

				function moreSmileys()
				{
					var row, i;

					if (smileyPopupWindow)
						smileyPopupWindow.close();

					smileyPopupWindow = window.open("", "add_smileys", "toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,width=480,height=200,resizable=no");
					smileyPopupWindow.document.write(\'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\n<html>\');
					smileyPopupWindow.document.write(\'\n\t<head>\n\t\t<title>', $txt['more_smileys_title'], '</title>\n\t\t<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/style.css" />\n\t</head>\');
					smileyPopupWindow.document.write(\'\n\t<body style="margin: 1ex;">\n\t\t<table width="100%" cellpadding="5" cellspacing="0" border="0" class="tborder">\n\t\t\t<tr class="titlebg"><td align="left">', $txt['more_smileys_pick'], '</td></tr>\n\t\t\t<tr class="windowbg"><td align="left">\');

					for (row = 0; row < smileys.length; row++)
					{
						for (i = 0; i < smileys[row].length; i++)
						{
							smileys[row][i][2] = smileys[row][i][2].replace(/"/g, \'&quot;\');
							smileyPopupWindow.document.write(\'<a href="javascript:void(0);" onclick="window.opener.replaceText(&quot; \' + smileys[row][i][0] + \'&quot;, window.opener.document.', $context['post_form'], '.', $context['post_box_name'], '); window.focus(); return false;"><img src="', $settings['smileys_url'], '/\' + smileys[row][i][1] + \'" alt="\' + smileys[row][i][2] + \'" title="\' + smileys[row][i][2] + \'" style="padding: 4px;" border="0" /></a>\');
						}
						smileyPopupWindow.document.write("<br />");
					}

					smileyPopupWindow.document.write(\'</td></tr>\n\t\t\t<tr><td align="center" class="windowbg"><a href="javascript:window.close();\\">', $txt['more_smileys_close_window'], '</a></td></tr>\n\t\t</table>\n\t</body>\n</html>\');
					smileyPopupWindow.document.close();
				}
			// --></script>';
	}

	echo '
			<tr>
				<td valign="top" align="right">
					<b' . (isset($context['post_error']['no_message']) || isset($context['post_error']['long_message']) ? ' style="color: #FF0000;"' : '') . '>' . $txt[72] . ':</b>
				</td>
				<td>
					<textarea class="editor" name="' . $context['post_box_name'] . '" rows="' . $context['post_box_rows'] . '" cols="' . $context['post_box_columns'] . '" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onchange="storeCaret(this);" tabindex="2">' . $message . '</textarea>
				</td>
			</tr>';
}

function template_spellcheck()
{
	global $context, $settings, $options, $txt;

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html', $context['right_to_left'] ? ' dir="rtl"' : '', '>
	<head>
		<title>', $txt['spell_check'], '</title>
		<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
		<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/style.css" />
		<style type="text/css">
			body, td
			{
				font-size: small;
				margin: 0;
			}
			.highlight
			{
				color: #FF0000;
				font-weight: bold;
			}
			#spellview
			{
				border-style: outset;
				border: 1px solid #000000;
				padding: 5px;
				width: 98%;
				height: 344px;
				overflow: auto;
			}';

	if ($context['browser']['needs_size_fix'])
		echo '
			@import(', $settings['default_theme_url'], '/fonts-compat.css);';

	echo '
		</style>
		<script language="JavaScript" type="text/javascript"><!--
			var spell_formname = "', $_POST['spell_formname'], '";
			var spell_fieldname = "', $_POST['spell_fieldname'], '";
		// --></script>
		<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/spellcheck.js"></script>
		<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/script.js"></script>
		<script language="JavaScript" type="text/javascript"><!--
			', $context['spell_js'], '
		// --></script>
	</head>
	<body onload="startsp();">
		<form name="fm1" onsubmit="return false;" style="margin: 0;">
			<div id="spellview">&nbsp;</div>
			<table border="0" cellpadding="4" cellspacing="0" width="100%"><tr class="windowbg">
				<td width="50%" valign="top">
					', $txt['spellcheck_change_to'], '<br />
					<input type="text" name="changeto" style="width: 98%;" />
				</td>
				<td width="50%">
					', $txt['spellcheck_suggest'], '<br />
					<select name="suggestions" style="width: 98%;" size="5" onclick="if (this.selectedIndex != -1) this.form.changeto.value = this.options[this.selectedIndex].text">
					</select>
				</td>
			</tr></table>
			<div class="titlebg" align="right" style="padding: 4px;">
				<input type="button" name="change" value="', $txt['spellcheck_change'], '" onclick="replaceWord()" />
				<input type="button" name="changeall" value="', $txt['spellcheck_change_all'], '" onclick="replaceAll()" />
				<input type="button" name="ignore" value="', $txt['spellcheck_ignore'], '" onclick="nextWord(false)" />
				<input type="button" name="ignoreall" value="', $txt['spellcheck_ignore_all'], '" onclick="nextWord(true)" />
			</div>
		</form>
	</body>
</html>';
}

?>