<?php
// Version: 1.0; Poll

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl;

	// Some javascript for adding more options.
	echo '
		<script language="JavaScript1.2" type="text/javascript"><!--
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
			}
		// --></script>';

	// Start the main poll form.
	echo '
		<form action="' . $scripturl . '?action=editpoll2;topic=' . $context['current_topic'] . '.' . $context['start'] . '" method="post" onsubmit="submitonce(this);" name="postmodify">
			<table width="75%" align="center" cellpadding="3" cellspacing="0">
				<tr>
					<td valign="bottom" colspan="2">', theme_linktree(), '</td>
				</tr>
			</table>
			<table border="0"  width="75%" align="center" cellspacing="1" cellpadding="3" class="bordercolor">
				<tr class="titlebg">
					<td>' . $txt['smf39'] . '</td>
				</tr><tr>
					<td class="windowbg">
						<input type="hidden" name="poll" value="' . $context['poll']['id'] . '" />
						<table border="0" cellpadding="3" width="100%">
							<tr>
								<td align="right" ', (isset($context['poll_errors']['no_question']) ? ' style="color: red;"' : ''), '><b>' . $txt['smf21'] . ':</b></td>
								<td align="left"><input type="text" name="question" size="40" value="' . $context['poll']['question'] . '" /></td>
							</tr><tr>
								<td></td>
								<td>';

	foreach ($context['choices'] as $choice)
	{
		echo '
									<label for="options[', $choice['id'], ']">', $txt['smf22'], ' ', $choice['number'], '</label>: <input type="text" name="options[', $choice['id'], ']" id="options[', $choice['id'], ']" size="25" value="', $choice['label'], '" />';

		// Does this option have a vote count yet, or is it new?
		if ($choice['votes'] != -1)
			echo ' (', $choice['votes'], ' ', $txt['smf42'], ')';

		if (!$choice['is_last'])
			echo '<br />';
	}

	echo '
									<span id="pollMoreOptions"></span> <a href="javascript:addPollOption(); void(0);">(', $txt['poll_add_option'], ')</a>
								</td>
							</tr><tr>';

	if ($context['can_moderate_poll'])
		echo '
								<td align="right"><b>', $txt['poll_options'], ':</b></td>
								<td class="smalltext"><input type="text" name="poll_max_votes" size="2" value="', $context['poll']['max_votes'], '" /> ', $txt['poll_options5'], '</td>
							</tr><tr>
								<td align="right"></td>
								<td class="smalltext">', $txt['poll_options1a'], ' <input type="text" name="poll_expire" size="2" value="', $context['poll']['expiration'], '" onchange="document.postmodify.poll_hide[2].disabled = isEmptyText(this) || this.value == 0; if (document.postmodify.poll_hide[2].checked) document.postmodify.poll_hide[1].checked = true;" /> ', $txt['poll_options1b'], '</td>
							</tr><tr>
								<td align="right"></td>
								<td class="smalltext"><label for="poll_change_vote"><input type="checkbox" id="poll_change_vote" name="poll_change_vote" ', !empty($context['poll']['change_vote']) ? 'checked="checked"' : '', ' class="check" /> ', $txt['poll_options7'], '</label></td>
							</tr><tr>
								<td align="right"></td>';
	else
		echo '
								<td align="right" valign="top"><b>', $txt['poll_options'], ':</b></td>';

	echo '
								<td class="smalltext">
									<input type="radio" name="poll_hide" value="0" ', $context['poll']['hide_results'] == 0 ? 'checked="checked"' : '', ' class="check" /> ' . $txt['poll_options2'] . '<br />
									<input type="radio" name="poll_hide" value="1" ', $context['poll']['hide_results'] == 1 ? 'checked="checked"' : '', ' class="check" /> ' . $txt['poll_options3'] . '<br />
									<input type="radio" name="poll_hide" value="2" ', $context['poll']['hide_results'] == 2 ? 'checked="checked"' : '', empty($context['poll']['expiration']) ? 'disabled="disabled"' : '', ' class="check" /> ' . $txt['poll_options4'] . '<br />
									<br />
								</td>
							</tr><tr>
								<td align="right"><b>' . $txt['smf40'] . ':</b></td>
								<td class="smalltext"><input type="checkbox" name="resetVoteCount" value="on" class="check" /> ' . $txt['smf41'] . '</td>
							</tr><tr>
								<td align="center" colspan="2">
									<span class="smalltext"><br />' . $txt['smf16'] . '</span><br />
									<input type="submit" name="post" value="' . $txt[10] . '" onclick="return submitThisOnce(this);" accesskey="s" />
									<input type="submit" name="preview" value="' . $txt[507] . '" onclick="return submitThisOnce(this);" accesskey="p" />
								</td>
							</tr><tr>
								<td colspan="2"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<input type="hidden" name="seqnum" value="', $context['form_sequence_number'], '" />
			<input type="hidden" name="sc" value="' . $context['session_id'] . '" />
		</form>';
}

?>