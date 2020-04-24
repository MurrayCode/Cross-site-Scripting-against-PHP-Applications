<?php
// Version: 1.0; Register

// Before registering - get their information.
function template_before()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Make sure they've agreed to the terms and conditions.
	echo '
<script language="JavaScript" type="text/javascript"><!--
	function agreesubmit(el)
	{
		document.creator.regSubmit.disabled = !el.checked;
	}
	function defaultagree()
	{';

	// If they haven't checked the "I agree" box, tell them and don't submit.
	if ($context['require_agreement'])
		echo '
		if (!document.creator.regagree.checked)
		{
			alert("', $txt['register_agree'], '");
			return false;
		}';

	// Otherwise, let it through.
	echo '
		return true;
	}
// --></script>
<form action="', $scripturl, '?action=register2" method="post" name="creator" onsubmit="return defaultagree();">
	<table border="0" width="100%" cellpadding="3" cellspacing="0" class="tborder">
		<tr class="titlebg">
			<td>', $txt[97], ' - ', $txt[517], '</td>
		</tr><tr class="windowbg">
			<td width="100%">
				<table cellpadding="3" cellspacing="0" border="0" width="100%">
					<tr>
						<td width="40%">
							<b>', $txt[98], ':</b>
							<div class="smalltext">', $txt[520], '</div>
						</td>
						<td>
							<input type="text" name="user" size="20" maxlength="18" />
						</td>
					</tr><tr>
						<td width="40%">
							<b>', $txt[69], ':</b>
							<div class="smalltext">', $txt[679], '</div>
						</td>
						<td>
							<input type="text" name="email" size="30" />';

	// Are they allowed to hide their email?
	if ($context['allow_hide_email'])
		echo '
							<input type="checkbox" name="hideEmail" class="check" id="hideEmail" /> <label for="hideEmail">', $txt[721], '</label>';

	echo '
						</td>
					</tr><tr>
						<td width="40%">
							<b>', $txt[81], ':</b>
						</td>
						<td>
							<input type="password" name="passwrd1" size="30" />
						</td>
					</tr><tr>
						<td width="40%">
							<b>', $txt[82], ':</b>
						</td>
						<td>
							<input type="password" name="passwrd2" size="30" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>';

	// Require them to agree here?
	if ($context['require_agreement'])
		echo '
	<table width="100%" align="center" border="0" cellspacing="0" cellpadding="5" class="tborder" style="border-top: 0;">
		<tr>
			<td class="windowbg2" style="padding-top: 8px; padding-bottom: 8px;">
				', $context['agreement'], '
			</td>
		</tr><tr>
			<td align="center" class="windowbg2">
				<label for="regagree"><input type="checkbox" name="regagree" onclick="agreesubmit(this);" class="check" id="regagree" /> <b>', $txt[585], '</b></label>
			</td>
		</tr>
	</table>';

	echo '
	<br />
	<div align="center">
		<input type="submit" name="regSubmit" value="', $txt[97], '" />
	</div>
</form>';

	// Uncheck the agreement thing....
	if ($context['require_agreement'])
		echo '
<script language="JavaScript" type="text/javascript">
	document.creator.regagree.checked = false;
	document.creator.regSubmit.disabled = true;
</script>';
}

// After registration... all done ;).
function template_after()
{
	global $context, $settings, $options, $txt, $scripturl;

	// Not much to see here, just a quick... "you're now registered!" or what have you.
	echo '
		<br />
		<table border="0" width="80%" cellpadding="3" cellspacing="0" class="tborder" align="center">
			<tr class="titlebg">
				<td>', $context['page_title'], '</td>
			</tr><tr class="windowbg">
				<td align="left">', $context['description'], '<br /><br /></td>
			</tr>
		</table>
		<br />';
}

function template_admin_browse()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
	<script language="JavaScript" type="text/javascript"><!--
		function onSelectChange()
		{
			if (document.postForm.todo.value == "")
				return;

			var message = "";
			if (document.postForm.todo.value.indexOf("delete") != -1)
				message = "', $txt['admin_browse_w_delete'], '";
			else if (document.postForm.todo.value.indexOf("reject") != -1)
				message = "', $txt['admin_browse_w_reject'], '";
			else if (document.postForm.todo.value == "remind")
				message = "', $txt['admin_browse_w_remind'], '";
			else
				message = "', $context['browse_type'] == 'approve' ? $txt['admin_browse_w_approve'] : $txt['admin_browse_w_activate'], '";

			if (confirm(message + " ', $txt['admin_browse_warn'], '"))
				document.postForm.submit();
		}
	// --></script>

	<form action="', $scripturl, '?action=regcenter" method="post" name="postForm">
		<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%" class="bordercolor">
			<tr class="titlebg">
				<td colspan="6"><a href="' . $scripturl . '?action=helpadmin;help=14" onclick="return reqWin(this.href);" class="help"><img src="' . $settings['images_url'] . '/helptopics.gif" alt="' . $txt[119] . '" border="0" align="top" /></a> ', $txt['registration_center'], '</td>
			</tr>
			<tr class="catbg">
				<td align="left" colspan="6">';

	if (isset($context['types_enabled']))
	{
		if ($context['types_enabled'] == 'approve' || $context['types_enabled'] == 'both')
			echo '
						', $context['browse_type'] == 'approve' ? '<img src="' . $settings['images_url'] . '/selected.gif" alt="&gt;" />' . $txt['admin_browse_awaiting_approval'] : '<a href="' . $scripturl . '?action=regcenter;sa=browse;type=approve">' . $txt['admin_browse_awaiting_approval'] . '</a>';

		if ($context['types_enabled'] == 'both')
			echo '
						|';

		if ($context['types_enabled'] == 'activate' || $context['types_enabled'] == 'both')
			echo '
						', $context['browse_type'] == 'activate' ? '<img src="' . $settings['images_url'] . '/selected.gif" alt="&gt;" />' . $txt['admin_browse_awaiting_activate'] : '<a href="' . $scripturl . '?action=regcenter;sa=browse;type=activate">' . $txt['admin_browse_awaiting_activate'] . '</a>';
		echo '
					|';
	}

	echo '
					<a href="', $scripturl, '?action=regcenter;sa=register">', $txt['admin_browse_register_new'], '</a>
				</td>
			</tr>
			<tr class="windowbg">
				<td class="smalltext" colspan="6" style="padding: 2ex;">
					', $txt['admin_browse_' . $context['browse_type'] . '_desc'], '
				</td>
			</tr>
			<tr class="catbg">
				<td colspan="6">', $txt[139], ': ', $context['page_index'], '</td>
			</tr>
			<tr class="titlebg">';

	foreach ($context['columns'] as $column)
	{
		echo '
				<td valign="top">
					<a href="', $column['href'], '">';

		if ($column['selected'])
			echo $column['label'], ' <img src="', $settings['images_url'], '/sort_', $context['sort_direction'], '.gif" alt="" border="0" />';
		else
			echo $column['label'];

		echo '</a>
				</td>';
	}

	echo '
				<td><input type="checkbox" class="check" onclick="invertAll(this, this.form, \'todo\');" /></td>
			</tr>';

	if (empty($context['members']))
		echo '
			<tr class="windowbg2">
				<td colspan="6" align="center">', $txt['admin_browse_no_members'], ' ', ($context['browse_type'] == 'approve' ? $txt['admin_browse_awaiting_approval'] : $txt['admin_browse_awaiting_activate']), '</td>
			</tr>';
	else
	{
		foreach ($context['members'] as $member)
			echo '
			<tr>
				<td class="windowbg2" width="5%">', $member['id'], '</td>
				<td class="windowbg">
					<a href="', $member['href'], '">', $member['username'], '</a>
					<input type="hidden" name="username[', $member['id'], ']" value="', $member['username'], '" />
				</td>
				<td class="windowbg"><a href="mailto:', $member['email'], '">', $member['email'], '</a></td>
				<td class="windowbg2"><a href="', $scripturl, '?action=trackip;searchip=', $member['ip'], '">', $member['ip'], '</a></td>
				<td class="windowbg">', $member['dateRegistered'], '</td>
				<td class="windowbg" width="5%">
					<input type="checkbox" value="', $member['email'], '" name="todoAction[', $member['id'], ']" class="check" />
				</td>
			</tr>';

		echo '
			<tr>
				<td class="windowbg2" align="right" colspan="6">
					<select name="todo" onchange="onSelectChange();">
						<option selected="selected" value="">', $txt['admin_browse_with_selected'], ':</option>
						<option value="" disabled="disabled">-----------------------------</option>', $context['browse_type'] == 'activate' ? '
						<option value="ok">' . $txt['admin_browse_w_activate'] . '</option>' : '', '
						<option value="okemail">', $context['browse_type'] == 'approve' ? $txt['admin_browse_w_approve'] : $txt['admin_browse_w_activate'], ' ', $txt['admin_browse_w_email'], '</option>
						<option value="reject">', $txt['admin_browse_w_reject'], '</option>
						<option value="rejectemail">', $txt['admin_browse_w_reject'], ' ', $txt['admin_browse_w_email'], '</option>
						<option value="delete">', $txt['admin_browse_w_delete'], '</option>
						<option value="deleteemail">', $txt['admin_browse_w_delete'], ' ', $txt['admin_browse_w_email'], '</option>', $context['browse_type'] == 'activate' ? '
						<option value="remind">' . $txt['admin_browse_w_remind'] . '</option>' : '', '
					</select>
					<noscript><input type="submit" value="', $txt[161], '" /></noscript>
					<input type="hidden" name="type" value="', $context['browse_type'], '" />
					<input type="hidden" name="sort" value="', $context['sort_by'], '" />
					<input type="hidden" name="start" value="', $context['start'], '" />
					<input type="hidden" name="sa" value="approve" />', $context['sort_direction'] == 'up' ? '
					<input type="hidden" name="desc" value="1" />' : '', '
				</td>
			</tr>';
		}

	echo '
			<tr class="catbg">
				<td colspan="6">', $txt[139], ': ', $context['page_index'], '</td>
			</tr>
		</table>
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
	</form>';
}

function template_admin_register()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
	<script language="JavaScript" type="text/javascript"><!--
		function onCheckChange()
		{
			if (document.creator.emailActivate.checked)
			{
				document.creator.emailPassword.disabled = 1;
				document.creator.emailPassword.checked = 1;
			}
			else
				document.creator.emailPassword.disabled = 0;
		}
	// --></script>

	<form action="', $scripturl, '?action=regcenter;sa=register2" method="post" name="creator">
		<table border="0" cellspacing="0" cellpadding="4" align="center" width="100%" class="tborder">
			<tr class="titlebg">
				<td colspan="2">
					<a href="' . $scripturl . '?action=helpadmin;help=14" onclick="return reqWin(this.href);" class="help"><img src="' . $settings['images_url'] . '/helptopics.gif" alt="' . $txt[119] . '" border="0" align="top" /></a> ', $txt['registration_center'], '
				</td>
			</tr>
			<tr class="catbg">
				<td align="left" colspan="2">';

	if (isset($context['types_enabled']))
	{
		if ($context['types_enabled'] == 'approve' || $context['types_enabled'] == 'both')
			echo '
					<a href="', $scripturl, '?action=regcenter;sa=browse;type=approve">', $txt['admin_browse_awaiting_approval'], '</a>';

		if ($context['types_enabled'] == 'both')
			echo '
					|';

		if ($context['types_enabled'] == 'activate' || $context['types_enabled'] == 'both')
			echo '
					<a href="', $scripturl, '?action=regcenter;sa=browse;type=activate">', $txt['admin_browse_awaiting_activate'], '</a>';

		echo '
					|';
	}

	echo '
					<img src="' . $settings['images_url'] . '/selected.gif" alt="&gt;" />', $txt['admin_browse_register_new'], '
				</td>
			</tr>
			<tr class="windowbg">
				<td class="smalltext" colspan="6" style="padding: 2ex;">
					', $txt['admin_register_desc'], '
				</td>
			</tr>
			<tr class="windowbg2">
				<td width="50%" align="right">
					<b>', $txt['admin_register_username'], ':</b>
					<div class="smalltext">', $txt['admin_register_username_desc'], '</div>
				</td>
				<td width="50%" align="left">
					<input type="text" name="user" size="20" maxlength="18" />
				</td>
			</tr><tr class="windowbg2">
				<td width="50%" align="right">
					<b>', $txt['admin_register_email'], ':</b>
					<div class="smalltext">', $txt['admin_register_email_desc'], '</div>
				</td>
				<td width="50%" align="left">
					<input type="text" name="email" size="30" />
				</td>
			</tr><tr class="windowbg2">
				<td width="50%" align="right">
					<b>', $txt['admin_register_password'], ':</b>
					<div class="smalltext">', $txt['admin_register_password_desc'], '</div>
				</td>
				<td width="50%" align="left">
					<input type="password" name="password" size="30" /><br />
				</td>
			</tr><tr class="windowbg2">
				<td width="50%" align="right">
					<b>', $txt['admin_register_group'], ':</b>
					<div class="smalltext">', $txt['admin_register_group_desc'], '</div>
				</td>
				<td width="50%" align="left">
					<select name="group">';

	foreach ($context['member_groups'] as $id => $name)
		echo '
						<option value="', $id, '">', $name, '</option>';
	echo '
					</select><br />
				</td>
			</tr><tr class="windowbg2">
				<td width="50%" align="right">
					<b>', $txt['admin_register_email_detail'], ':</b>
					<div class="smalltext">', $txt['admin_register_email_detail_desc'], '</div>
				</td>
				<td width="50%" align="left">
					<input type="checkbox" name="emailPassword" checked="checked"', !empty($modSettings['registration_method']) && $modSettings['registration_method'] == 1 ? ' disabled="disabled"' : '', ' class="check" /><br />
				</td>
			</tr><tr class="windowbg2">
				<td width="50%" align="right">
					<b>', $txt['admin_register_email_activate'], ':</b>
				</td>
				<td width="50%" align="left">
					<input type="checkbox" name="emailActivate"', !empty($modSettings['registration_method']) && $modSettings['registration_method'] == 1 ? ' checked="checked"' : '', ' onclick="onCheckChange();" class="check" /><br />
				</td>
			</tr><tr class="windowbg2">
				<td width="100%" colspan="2" align="center">
					<input type="submit" name="regSubmit" value="', $txt[97], '" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
	</form>';
}

?>