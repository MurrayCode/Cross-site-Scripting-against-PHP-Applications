<?php
// Version: 1.0; SendTopic

// This is where we get information about who they want to send the topic to, etc.
function template_main()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<form action="', $scripturl, '?action=sendtopic;topic=', $context['current_topic'], '.', $context['start'], '" method="post">
			<table width="400" cellpadding="3" cellspacing="0" border="0" class="tborder" align="center">
				<tr class="titlebg">
					<td align="left" colspan="2">
						<img src="', $settings['images_url'], '/email_sm.gif" alt="" border="0" />
						', $context['page_title'], '
					</td>
				</tr>';

	// Just show all the input boxes, in a line...
	echo '
				<tr class="windowbg">
					<td align="right"><b>', $txt['sendtopic_sender_name'], ':</b></td>
					<td align="left"><input type="text" name="y_name" size="24" maxlength="40" value="', $context['user']['name'], '" /></td>
				</tr>
				<tr class="windowbg">
					<td align="right"><b>', $txt['sendtopic_sender_email'], ':</b></td>
					<td align="left"><input type="text" name="y_email" size="24" maxlength="50" value="', $context['user']['email'], '" /></td>
				</tr>
				<tr class="windowbg">
					<td align="right"><b>', $txt['sendtopic_comment'], ':</b></td>
					<td align="left"><input type="text" name="comment" size="24" maxlength="100" /></td>
				</tr>
				<tr class="windowbg">
					<td align="center" colspan="2"><hr width="100%" size="1" class="hrcolor" /></td>
				</tr>
				<tr class="windowbg">
					<td align="right"><b>', $txt['sendtopic_receiver_name'], ':</b></td>
					<td align="left"><input type="text" name="r_name" size="24" maxlength="40" /></td>
				</tr>
				<tr class="windowbg">
					<td align="right"><b>', $txt['sendtopic_receiver_email'], ':</b></td>
					<td align="left"><input type="text" name="r_email" size="24" maxlength="50" /></td>
				</tr>
				<tr class="windowbg">
					<td align="center" colspan="2"><br /><input type="submit" name="send" value="', $txt['sendtopic_send'], '" /></td>
				</tr>
			</table>
			<input type="hidden" name="sc" value="', $context['session_id'], '" />
		</form>';
}

function template_report()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
	<form action="', $scripturl, '?action=reporttm;topic=', $context['current_topic'], '.', $context['start'], '" method="post">
		<input type="hidden" name="msg" value="' . $context['message_id'] . '" />
		<table border="0" width="80%" cellspacing="0" class="tborder" align="center" cellpadding="4">
			<tr class="titlebg">
				<td>', $txt['rtm1'], '</td>
			</tr><tr class="windowbg">
				<td style="padding-bottom: 3ex;" align="center">
					<div style="margin-top: 1ex; margin-bottom: 3ex;" align="left">', $txt['smf315'], '</div>
					', $txt['rtm2'], ': <input type="text" name="comment" size="50" />
					<input type="submit" name="submit" value="', $txt['rtm10'], '" style="margin-left: 1ex;" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
	</form>';
}

?>