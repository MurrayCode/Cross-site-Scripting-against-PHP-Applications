<?php
// Version: 1.0; ManagePermissions

function template_main()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
		<form action="' . $scripturl . '?action=permissions;sa=quick" method="post" name="permissionForm">
			<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tborder">
				<tr class="titlebg">
					<td colspan="6" style="padding: 4px;"><a href="' . $scripturl . '?action=helpadmin;help=permissions" onclick="return reqWin(this.href);" class="help"><img src="' . $settings['images_url'] . '/helptopics.gif" alt="' . $txt[119] . '" border="0" align="top" /></a> ' . $txt['permissions_title'] . '</td>
				</tr><tr class="catbg">
					<td colspan="6" style="padding: 4px;">', empty($context['board']) ? $txt['permissions_groups'] : $txt['permissions_boards'] . ' (' . $context['board']['name'] . ')', '</td>
				</tr>

				<tr class="titlebg">
					<td valign="middle">', $txt['membergroups_name'], '</td>
					<td width="10%" align="center" valign="middle">', $txt['membergroups_members_top'], '</td>
					<td width="16%" align="center" class="smalltext">
						', $txt['membergroups_permissions'], '<br />
						<div style="float: left; width: 50%;">', $txt['permissions_allowed'], '</div> ', $txt['permissions_denied'], '
					</td>';

	if (!empty($context['board']))
		echo '
					<td width="6%" align="center" valign="middle">', $txt['permissions_access'], '</td>';

	echo '
					<td width="10%" align="center" valign="middle">', $txt['permissions_modify'], '</td>
					<td width="4%" align="center" valign="middle">
						<input type="checkbox" class="check" onclick="invertAll(this, this.form, \'group\');" /></td>
				</tr>';

	foreach ($context['groups'] as $group)
	{
		echo '
				<tr>
					<td class="windowbg2">', $group['name'], '</td>
					<td class="windowbg" align="center">', $group['can_search'] ? $group['link'] : $group['num_members'], '</td>
					<td class="windowbg2" align="center"', $group['id'] == 1 ? ' style="font-style: italic;"' : '', '>
						<div style="float: left; width: 50%;">', $group['num_permissions']['allowed'], '</div> ', empty($group['num_permissions']['denied']) || $group['id'] == 1 ? $group['num_permissions']['denied'] : '<span style="color: red;">' . $group['num_permissions']['denied'] . '</span>', '
					</td>';

	if (!empty($context['board']))
	{
		echo '
					<td class="windowbg" align="center">';

		// Don't show the checkbox for admins and moderators, doesn't make sense!
		if ($group['id'] != 1 && $group['id'] != 3)
			echo '
						<input type="checkbox" name="access[', $group['id'], ']" value="', $group['id'], '" ', $group['access'] ? ' checked="checked"' : '', ' />';

		echo '
					</td>';
	}

		echo '
					<td class="windowbg2" align="center">', $group['allow_modify'] ? '<a href="' . $scripturl . '?action=permissions;sa=modify;id=' . $group['id'] . (empty($context['board']) ? '' : ';boardid=' . $context['board']['id']) . '">' . $txt['permissions_modify'] . '</a>' : '', '</td>
					<td class="windowbg" align="center">', $group['allow_modify'] ? '<input type="checkbox" name="group[]" value="' . $group['id'] . '" class="check" />' : '', '</td>
				</tr>';
	}

	echo '
				<tr class="windowbg">
					<td colspan="6" style="padding-top: 1ex; padding-bottom: 1ex; text-align: right;">
						<table width="100%" cellspacing="0" cellpadding="3" border="0"><tr><td>
							<div style="margin-bottom: 1ex;"><b>', $txt['permissions_with_selection'], '...</b></div>
							', $txt['permissions_apply_pre_defined'], ' <a href="' . $scripturl . '?action=helpadmin;help=permissions_quickgroups" onclick="return reqWin(this.href);">(?)</a>:
							<select name="predefined">
								<option value="">(' . $txt['permissions_select_pre_defined'] . ')</option>
								<option value="restrict">' . $txt['permitgroups_restrict'] . '</option>
								<option value="standard">' . $txt['permitgroups_standard'] . '</option>
								<option value="moderator">' . $txt['permitgroups_moderator'] . '</option>
								<option value="maintenance">' . $txt['permitgroups_maintenance'] . '</option>
							</select><br /><br />';

	if (!empty($context['board']) && !empty($context['copy_boards']))
	{
		echo '
							', $txt['permissions_copy_from_board'], ':
							<select name="from_board">
								<option value="empty">(', $txt['permissions_select_board'], ')</option>';
		foreach ($context['copy_boards'] as $board)
			echo '
								<option value="', $board['id'], '">', $board['name'], '</option>';
		echo '
							</select><br /><br />';
	}

	echo '
							', $txt['permissions_like_group'], ':
							<select name="copy_from">
								<option value="empty">(', $txt['permissions_select_membergroup'], ')</option>';
	foreach ($context['groups'] as $group)
	{
		if ($group['id'] != 1)
			echo '
								<option value="', $group['id'], '">', $group['name'], '</option>';
	}

	echo '
							</select><br /><br />
							<select name="add_remove">
								<option value="add">', $txt['permissions_add'], '...</option>
								<option value="clear">', $txt['permissions_remove'], '...</option>
								<option value="deny">', $txt['permissions_deny'], '...</option>
							</select>&nbsp;<select name="permissions">
								<option value="">(', $txt['permissions_select_permission'], ')</option>';
	foreach ($context['permissions'] as $permissionType)
	{
		if ($permissionType['id'] == 'membergroup' && !empty($context['board']))
			continue;

		foreach ($permissionType['columns'] as $column)
		{
			foreach ($column as $permissionGroup)
			{
				echo '
								<option value="" disabled="disabled">[', $permissionGroup['name'], ']</option>';
				foreach ($permissionGroup['permissions'] as $perm)
					if ($perm['has_own_any'])
						echo '
								<option value="', $permissionType['id'], '/', $perm['own']['id'], '">&nbsp;&nbsp;&nbsp;', $perm['name'], ' (', $perm['own']['name'], ')</option>
								<option value="', $permissionType['id'], '/', $perm['any']['id'], '">&nbsp;&nbsp;&nbsp;', $perm['name'], ' (', $perm['any']['name'], ')</option>';
					else
						echo '
								<option value="', $permissionType['id'], '/', $perm['id'], '">&nbsp;&nbsp;&nbsp;', $perm['name'], '</option>';
			}
		}
	}
	echo '
							</select>
						</td><td valign="bottom" width="16%">
							<script language="JavaScript" type="text/javascript"><!--
								function checkSubmit()
								{
									if ((document.permissionForm.predefined.value != "" && (document.permissionForm.copy_from.value != "empty" || document.permissionForm.permissions.value != "")) || (document.permissionForm.copy_from.value != "empty" && document.permissionForm.permissions.value != ""))
									{
										alert("', $txt['permissions_only_one_option'], '");
										return false;
									}
									if (document.permissionForm.predefined.value == "" && document.permissionForm.copy_from.value == ""  && document.permissionForm.permissions.value == "")
									{
										alert("', $txt['permissions_no_action'], '");
										return false;
									}
									if (document.permissionForm.permissions.value != "" && document.permissionForm.add_remove.value == "deny")
										return confirm("', $txt['permissions_deny_dangerous'], '");

									return true;
								}
							//--></script>
							<input type="submit" value="', $txt['permissions_set_permissions'], '" onclick="return checkSubmit();" />
						</td></tr></table>
					</td>
				</tr>
			</table>';
	if (!empty($context['boards']))
	{
		echo '
			<table width="100%" border="0" cellpadding="3" cellspacing="1" class="tborder" style="margin-top: 2ex;">
				<tr class="catbg">
					<td colspan="3" style="padding: 4px;"><a href="' . $scripturl . '?action=helpadmin;help=permissions_board" onclick="return reqWin(this.href);" class="help"><img src="' . $settings['images_url'] . '/helptopics.gif" alt="' . $txt[119] . '" border="0" align="top" /></a> ', $txt['permissions_boards'], '</td>
				</tr><tr class="titlebg">
					<td>', $txt[20], '</td>
					<td colspan="2" style="width: 18ex; text-align: center;">', $txt['permissions_switch'], '</td>
				</tr>';
		foreach ($context['boards'] as $board)
		{
			echo '
				<tr class="windowbg2">
					<td align="left" class="windowbg">
						<b><a', $board['use_local_permissions'] ? ' href="' . $scripturl . '?action=permissions;boardid=' . $board['id'] . '"' : '', ' name="', $board['id'], '"> ', str_repeat('-', $board['child_level']), ' ' . $board['name'] . '</a></b> (', $board['use_local_permissions'] ? $txt['permissions_local'] : $txt['permissions_global'], ')
					</td>
					<td align="center" style="font-weight: ', $board['use_local_permissions'] ? 'normal' : 'bold', ';"><a href="', $scripturl, '?action=permissions;sa=switch;to=global;boardid=', $board['id'], ';sesc=', $context['session_id'], '#', $board['id'], '">', $txt['permissions_global'], '</a></td>
					<td align="center" style="font-weight: ', $board['use_local_permissions'] ? 'bold' : 'normal', ';"><a href="', $scripturl, '?action=permissions;sa=switch;to=local;boardid=', $board['id'], ';sesc=', $context['session_id'], '#', $board['id'], '">', $txt['permissions_local'], '</a></td>
				</tr>';
		}

		echo '
			</table>';
	}

	if (!empty($context['board']))
		echo '
			<input type="hidden" name="boardid" value="', $context['board']['id'], '" />';

	echo '
			<input type="hidden" name="sc" value="' . $context['session_id'] . '" />
		</form>';
}

function template_modify_group()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
		<script language="JavaScript" type="text/javascript"><!--
			window.smf_usedDeny = false;
			function warnAboutDeny()
			{
				if (window.smf_usedDeny)
					return confirm("', $txt['permissions_deny_dangerous'], '");
				else
					return true;
			}
		// --></script>
		<form action="', $scripturl, '?action=permissions;sa=modify2;id=', $context['group']['id'], ';boardid=', $context['board']['id'], '" method="post" name="permissionForm" onsubmit="return warnAboutDeny();">
			<table width="100%" cellpadding="4" cellspacing="0" border="0" class="tborder">
				<tr class="titlebg">
					<td colspan="2" align="center">', $context['local'] ? $txt['permissions_modify_local'] : $txt['permissions_modify_group'] . ' - ' . $context['group']['name'], '</td>';
	echo '
				</tr><tr class="windowbg">
					<td colspan="2" class="smalltext" style="padding: 2ex;">', $txt['permissions_option_desc'], '</td>
				</tr>';
	foreach ($context['permissions'] as $permission_type)
	{
		if ($permission_type['show'])
		{
			echo '
				<tr class="catbg">
					<td colspan="2" align="center">';
			if ($context['local'])
				echo '
						', $txt['permissions_local_for'], ' \'<span style="color: red;">', $context['group']['name'], '</span>\' ', $txt['permissions_on'], ' \'<span style="color: red;">', $context['board']['name'], '</span>\'';
			else
				echo '
						', $permission_type['id'] == 'membergroup' ? $txt['permissions_general'] : $txt['permissions_board'] . ' - ' . $context['group']['name'];
			echo '
					</td>
				</tr>
				<tr class="windowbg2">';
			foreach ($permission_type['columns'] as $column)
			{
				echo '
					<td valign="top" width="50%">
						<table width="100%" cellpadding="1" cellspacing="0" border="0">';
				foreach ($column as $permissionGroup)
				{
					echo '
							<tr class="windowbg2">
								<td colspan="2" width="100%" align="left"><div style="border-bottom: 1px solid; padding-bottom: 2px; margin-bottom: 2px;"><b>', $permissionGroup['name'], '</b></div></td>
								<td align="center"><div style="border-bottom: 1px solid; padding-bottom: 2px; margin-bottom: 2px;">', $txt['permissions_option_on'], '</div></td>
								<td align="center"><div style="border-bottom: 1px solid; padding-bottom: 2px; margin-bottom: 2px;">', $txt['permissions_option_off'], '</div></td>
								<td align="center"><div style="border-bottom: 1px solid; padding-bottom: 2px; margin-bottom: 2px; color: red;">', $txt['permissions_option_deny'], '</div></td>
							</tr>';

					if (!empty($permissionGroup['permissions']))
					{
						$alternate = false;
						foreach ($permissionGroup['permissions'] as $permission)
						{
							echo '
							<tr class="', $alternate ? 'windowbg' : 'windowbg2', '">
								<td valign="top" width="10" style="padding-right: 1ex;">
									', $permission['show_help'] ? '<a href="' . $scripturl . '?action=helpadmin;help=permissionhelp_' . $permission['id'] . '" onclick="return reqWin(this.href);" class="help"><img src="' . $settings['images_url'] . '/helptopics.gif" alt="' . $txt[119] . '" border="0" /></a>' : '', '
								</td>';
							if ($permission['has_own_any'])
								echo '
								<td colspan="4" width="100%" valign="top" align="left">', $permission['name'], '</td>
							</tr><tr class="', $alternate ? 'windowbg' : 'windowbg2', '">
								<td></td>
								<td width="100%" class="smalltext" align="right">', $permission['own']['name'], ':</td>
								<td valign="top" width="10"><input type="radio" name="perm[', $permission_type['id'], '][', $permission['own']['id'], ']"', $permission['own']['select'] == 'on' ? ' checked="checked"' : '', ' value="on" id="', $permission['own']['id'], '_on" /></td>
								<td valign="top" width="10"><input type="radio" name="perm[', $permission_type['id'], '][', $permission['own']['id'], ']"', $permission['own']['select'] == 'off' ? ' checked="checked"' : '', ' value="off" /></td>
								<td valign="top" width="10"><input type="radio" name="perm[', $permission_type['id'], '][', $permission['own']['id'], ']"', $permission['own']['select'] == 'denied' ? ' checked="checked"' : '', ' value="deny" onclick="document.forms.permissionForm.', $permission['any']['id'], '_deny.checked = true; window.smf_usedDeny = true;" /></td>
							</tr><tr class="', $alternate ? 'windowbg' : 'windowbg2', '">
								<td></td>
								<td width="100%" class="smalltext" align="right" style="padding-bottom: 1.5ex;">', $permission['any']['name'], ':</td>
								<td valign="top" width="10" style="padding-bottom: 1.5ex;"><input type="radio" name="perm[', $permission_type['id'], '][', $permission['any']['id'], ']"', $permission['any']['select'] == 'on' ? ' checked="checked"' : '', ' value="on" onclick="document.forms.permissionForm.', $permission['own']['id'], '_on.checked = true;" /></td>
								<td valign="top" width="10" style="padding-bottom: 1.5ex;"><input type="radio" name="perm[', $permission_type['id'], '][', $permission['any']['id'], ']"', $permission['any']['select'] == 'off' ? ' checked="checked"' : '', ' value="off" /></td>
								<td valign="top" width="10" style="padding-bottom: 1.5ex;"><input type="radio" name="perm[', $permission_type['id'], '][', $permission['any']['id'], ']"', $permission['any']['select']== 'denied' ? ' checked="checked"' : '', ' value="deny" id="', $permission['any']['id'], '_deny" onclick="window.smf_usedDeny = true;" /></td>
							</tr>';
							else
								echo '
								<td valign="top" width="100%" align="left" style="padding-bottom: 2px;">', $permission['name'], '</td>
								<td valign="top" width="10" style="padding-bottom: 2px;"><input type="radio" name="perm[', $permission_type['id'], '][', $permission['id'], ']"', $permission['select'] == 'on' ? ' checked="checked"' : '', ' value="on" /></td>
								<td valign="top" width="10" style="padding-bottom: 2px;"><input type="radio" name="perm[', $permission_type['id'], '][', $permission['id'], ']"', $permission['select'] == 'off' ? ' checked="checked"' : '', ' value="off" /></td>
								<td valign="top" width="10" style="padding-bottom: 2px;"><input type="radio" name="perm[', $permission_type['id'], '][', $permission['id'], ']"', $permission['select'] == 'denied' ? ' checked="checked"' : '', ' value="deny" onclick="window.smf_usedDeny = true;" /></td>
							</tr>';

							$alternate = !$alternate;
						}
					}

					echo '
							<tr class="windowbg2">
								<td colspan="5" width="100%"><div style="border-top: 1px solid; padding-bottom: 1.5ex; margin-top: 2px;">&nbsp;</div></td>
							</tr>';
				}

				echo '
						</table>
					</td>';
			}
		}
	}
	echo '
				</tr><tr class="windowbg2">
					<td colspan="2" align="right"><input type="submit" value="', $txt['permissions_commit'], '" />&nbsp;</td>
				</tr>
			</table>
			<input type="hidden" name="sc" value="', $context['session_id'], '" />
		</form>';
}

?>