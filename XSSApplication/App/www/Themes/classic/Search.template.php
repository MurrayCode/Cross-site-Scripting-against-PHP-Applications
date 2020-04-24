<?php
// Version: 1.0; Search

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<script language="JavaScript" type="text/javascript"><!--
			function checkAll(onOff)
			{
				for (var i = 0; i < document.searchform.elements.length; i++)
				{
					if (document.searchform.elements[i].name.substr(0, 3) == "brd")
						document.searchform.elements[i].checked = onOff;
				}
			}
		// --></script>
		<form action="', $scripturl, '?action=search2" method="post" name="searchform" id="searchform">
		<table width="80%" border="0" cellspacing="0" cellpadding="3" align="center">
			<tr>
				<td>', theme_linktree(), '</td>
			</tr>
		</table>

		<table width="80%" border="0" cellspacing="0" cellpadding="4" align="center" class="tborder">
			<tr class="titlebg">
				<td>' . $txt[183] . '</td>
			</tr><tr>
				<td class="windowbg">';

	if ($context['simple_search'])
	{
		echo '
					<b>' . $txt[582] . ':</b><br />
					<input type="text" name="search"', !empty($context['search_params']['search']) ? ' value="' . $context['search_params']['search'] . '"' : '', ' size="40" />&nbsp;
					<input type="submit" name="submit" value="' . $txt[182] . '" /><br />
					<a href="' . $scripturl . '?action=search;advanced">' . $txt['smf298'] . '</a>
					<input type="hidden" name="advanced" value="0" />';
	}
	else
	{
		echo '
					<input type="hidden" name="advanced" value="1" />
					<table><tr>
						<td>
							<b>', $txt[582], ':</b>
						</td>
						<td></td>
						<td>
							<b>', $txt[583], ':</b>
						</td>
					</tr><tr>
						<td>
							<input type="text" name="search"', !empty($context['search_params']['search']) ? ' value="' . $context['search_params']['search'] . '"' : '', ' size="40" />
						</td><td>
							<select name="searchtype">
								<option value="1"', empty($context['search_params']['searchtype']) ? ' selected="selected"' : '', '>', $txt[343], '</option>
								<option value="2"', !empty($context['search_params']['searchtype']) ? ' selected="selected"' : '', '>', $txt[344], '</option>
							</select>&nbsp;&nbsp;&nbsp;
						</td><td>
							<input type="text" name="userspec" value="', empty($context['search_params']['userspec']) ? '*' : $context['search_params']['userspec'], '" size="40" />&nbsp;
						</td>
					</tr><tr>
						<td colspan="3">&nbsp;</td>
					</tr><tr>
						<td colspan="2"><b>', $txt['search_options'], ':</b></td>
						<td><b>', $txt['search_post_age'], ': </b></td>
					</tr><tr>
						<td colspan="2">
							<label for="show_complete"><input type="checkbox" name="show_complete" id="show_complete" value="1"', !empty($context['search_params']['show_complete']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['search_show_complete_messages'], '</label><br />
							<label for="subject_only"><input type="checkbox" name="subject_only" id="subject_only" value="1"', !empty($context['search_params']['subject_only']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['search_subject_only'], '</label>
						</td>
						<td>
							', $txt['search_between'], '&nbsp;<input type="text" name="minage" value="', empty($context['search_params']['minage']) ? '0' : $context['search_params']['minage'], '" size="5" maxlength="5" />&nbsp;', $txt['search_and'], '&nbsp;<input type="text" name="maxage" value="', empty($context['search_params']['maxage']) ? '9999' : $context['search_params']['maxage'], '" size="5" maxlength="5" /> ', $txt[579], '.
						</td>
					</tr><tr>
						<td colspan="3">&nbsp;</td>
					</tr><tr>
						<td colspan="2"><b>', $txt['search_order'], ':</b></td>
						<td>&nbsp;</td>
					</tr><tr>
						<td colspan="2">
							<select name="sort">
								<option value="relevance|desc">', $txt['search_orderby_relevant_first'], '</option>
								<option value="numReplies|desc">', $txt['search_orderby_large_first'], '</option>
								<option value="numReplies|asc">', $txt['search_orderby_small_first'], '</option>
								<option value="ID_MSG|desc">', $txt['search_orderby_recent_first'], '</option>
								<option value="ID_MSG|asc">', $txt['search_orderby_old_first'], '</option>
							</select>
						</td>
						<td>&nbsp;</td>
					</tr></table>
					<br /><br />
					<b>', $txt[189], ':</b><br /><br />
					<table width="80%" border="0" cellpadding="1" cellspacing="0">';

		$temp_boards = array();
		foreach ($context['categories'] as $category)
		{
			$temp_boards[] = array(
				'name' => $category['name']
			);
			$temp_boards = array_merge($temp_boards, array_values($category['boards']));
		}

		$max_boards = ceil(count($temp_boards) / 2);
		if ($max_boards < 2)
			$max_boards = 2;
		for ($i = 0; $i < $max_boards; $i++)
		{
			echo '
						<tr>
							<td width="50%">';
			if (isset($temp_boards[$i]['id']))
				echo '
								', str_repeat('&nbsp; ', $temp_boards[$i]['child_level']), '<input type="checkbox" id="brd' . $temp_boards[$i]['id'] . '" name="brd[' . $temp_boards[$i]['id'] . ']" value="' . $temp_boards[$i]['id'] . '"', empty($context['search_params']['brd']) || in_array($temp_boards[$i]['id'], $context['search_params']['brd']) ? ' checked="checked"' : '', ' class="check" />
								<label for="brd' . $temp_boards[$i]['id'] . '">' . $temp_boards[$i]['name'] . '</label>';
			else
				echo '<span style="text-decoration: underline;">', $temp_boards[$i]['name'], '</span>';
			echo '
							</td>';
			if (isset($temp_boards[$i + $max_boards]))
			{
				echo '
							<td width="50%">';
				if (isset($temp_boards[$i + $max_boards]['id']))
					echo '
								', str_repeat('&nbsp; ', $temp_boards[$i + $max_boards]['child_level']), '<input type="checkbox" id="brd' . $temp_boards[$i + $max_boards]['id'] . '" name="brd[' . $temp_boards[$i + $max_boards]['id'] . ']" value="' . $temp_boards[$i + $max_boards]['id'] . '"', empty($context['search_params']['brd']) || in_array($temp_boards[$i + $max_boards]['id'], $context['search_params']['brd']) ? ' checked="checked"' : '', ' class="check" />
								<label for="brd' . $temp_boards[$i + $max_boards]['id'] . '">' . $temp_boards[$i + $max_boards]['name'] . '</label>';
				else
					echo '<span style="text-decoration: underline;">', $temp_boards[$i + $max_boards]['name'], '</span>';
				echo '
							</td>';
			}
			echo '
						</tr>';
		}

		echo '
					</table><br />
					<input type="checkbox" name="all" id="check_all" value="" checked="checked" onclick="invertAll(this, this.form, \'brd\');" class="check" /><i> <label for="check_all">', $txt[737], '</label></i>
					<br />
					<br />
					<table border="0" cellpadding="2" cellspacing="0" align="left">
						<tr>
							<td valign="bottom">
								<input type="submit" name="submit" value="', $txt[182], '" />
							</td>
						</tr>
					</table>';
	}
	echo '
				</td>
			</tr>
		</table>
		</form>';
}

function template_results()
{
	global $context, $settings, $options, $txt, $scripturl;

	if ($context['compact'])
	{
		echo '
', theme_linktree(), '
<table width="100%" cellpadding="3" cellspacing="0" border="0" class="tborder" style="border-width: 1px 1px 0 1px;">
	<tr>
		<td align="left" class="catbg" width="100%" height="30">
			<b>' . $txt[139] . ':</b> ' . $context['page_index'] . '
		</td>
	</tr>
</table>
<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
	<tr class="titlebg">';
		if (!empty($context['topics']))
		{
			echo '
		<td width="4%"></td>
		<td width="4%"></td>
		<td width="56%">', $txt[70], '</td>
		<td width="12%">', $txt[109], '</td>
		<td width="6%" align="center">', $txt['search_relevance'], '</td>
		<td width="18%" align="center">', $txt['search_date_posted'], '</td>';
		}
		else
			echo '
		<td width="100%" colspan="5"><b>', $txt['search_no_results'], '</b></td>';
		echo '
	</tr>';

		while ($topic = $context['get_topics']())
		{
			echo '
	<tr>
		<td class="windowbg2" valign="top" align="center" width="4%">
			<img src="' . $settings['images_url'] . '/topic/' . $topic['class'] . '.gif" alt="" /></td>
		<td class="windowbg2" valign="top" align="center" width="4%">
			<img src="' . $settings['images_url'] . '/post/' . $topic['first_post']['icon'] . '.gif" alt="" border="0" align="middle" /></td>
		<td class="windowbg" valign="middle" width="56%">
			<b>' . $topic['first_post']['link'] . '</b>
			<div class="smalltext"><i>' . $txt['smf88'] . ' ' . $topic['board']['link'] . '</i></div>';

			foreach ($topic['matches'] as $message)
			{
				echo '
			<br />
			<div class="quoteheader" style="margin-left: 20px;"><a href="' . $scripturl . '?topic=' . $topic['id'] . '.msg' . $message['id'] . '#msg' . $message['id'] . '">' . $message['subject_highlighted'] . '</a> ', $txt[525], ' ' . $message['member']['link'] . '</div>';

				if ($message['body_highlighted'] != '')
					echo '
			<div class="quote" style="margin-left: 20px;">' . $message['body_highlighted'] . '</div>';
			}

			echo '
		<td class="windowbg2" valign="top" width="12%">
			' . $topic['first_post']['member']['link'] . '</td>
		<td class="windowbg" valign="top" width="6%" align="center">
			' . $topic['relevance'] . '</td>
		<td class="windowbg" valign="top" width="18%" align="center">
			' . $topic['first_post']['time'] . '</td>
	</tr>';
		}
		echo '
</table>
<table width="100%" cellpadding="3" cellspacing="0" border="0" class="tborder" style="border-width: 0 1px 1px 1px;">
	<tr>
		<td align="left" class="catbg" width="100%" height="30">
			<table cellpadding="3" cellspacing="0" width="100%">
				<tr>
					<td><a name="bot"></a><b>', $txt[139], ':</b> ', $context['page_index'], '</td>
					<td align="right" nowrap="nowrap" style="font-size: smaller;">&nbsp;</td>
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
		<td class="smalltext" align="right" valign="middle">
			<form action="', $scripturl, '" method="get" name="jumptoForm">
				<label for="jumpto">' . $txt[160] . ':
				<select name="jumpto" id="jumpto" onchange="if (this.options[this.selectedIndex].value) window.location.href=\'', $scripturl, '\' + this.options[this.selectedIndex].value;">
					<option value="">' . $txt[251] . ':</option>';
		foreach ($context['jump_to'] as $category)
		{
			echo '
					<option value="" disabled="disabled">-----------------------------</option>
					<option value="#', $category['id'], '">', $category['name'], '</option>
					<option value="" disabled="disabled">-----------------------------</option>';
			foreach ($category['boards'] as $board)
				echo '
					<option value="?board=', $board['id'], '.0"> ' . str_repeat('==', $board['child_level']) . '=> ' . $board['name'] . '</option>';
		}
		echo '
				</select></label>&nbsp;
				<input type="button" value="', $txt[161], '" onclick="if (document.jumptoForm.jumpto.options[document.jumptoForm.jumpto.selectedIndex].value) window.location.href = \'', $scripturl, '\' + document.jumptoForm.jumpto.options[document.jumptoForm.jumpto.selectedIndex].value;" />
			</form>
		</td>
	</tr>
</table>';
	}
	else
	{
		echo '
			', theme_linktree(), '
			<table width="100%" cellpadding="3" cellspacing="0" border="0" class="tborder">
				<tr>
					<td align="left" class="catbg" width="100%" height="30">
						<b>' . $txt[139] . ':</b> ' . $context['page_index'] . '
					</td>
				</tr>
			</table>
			<br />';
		if (empty($context['topics']))
			echo '
			<table border="0" width="100%" cellspacing="0" cellpadding="0" class="bordercolor"><tr><td>
				<table border="0" width="100%" cellpadding="2" cellspacing="1" class="bordercolor"><tr class="windowbg2"><td><br />
					<b>(', $txt['search_no_results'], ')</b><br /><br />
				</td></tr></table>
			</td></tr></table>';

		while ($topic = $context['get_topics']())
		{
			foreach ($topic['matches'] as $message)
			{
				// Create buttons row.
				$buttonArray = array();
				if ($topic['can_reply'])
				{
					$buttonArray[] = '<a href="' . $scripturl . '?action=post;topic=' . $topic['id'] . '.' . $message['start'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/reply_sm.gif" alt="' . $txt[146] . '" border="0" />' : $txt[146]) . '</a>';
					$buttonArray[] = '<a href="' . $scripturl . '?action=post;topic=' . $topic['id'] . '.0;quote=' . $message['id'] . '/' . $message['start'] . ';sesc=' . $context['session_id'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/quote.gif" alt="' . $txt[145] . '" border="0" />' : $txt[145]) . '</a>';
				}
				if ($topic['can_mark_notify'] && $context['user']['is_logged'])
					$buttonArray[] = '<a href="' . $scripturl . '?action=notify;topic=' . $topic['id'] . '.' . $message['start'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/notify_sm.gif" alt="' . $txt[131] . '" border="0" />' : $txt[131]) . '</a>';

				echo '
			<table border="0" width="100%" cellspacing="0" cellpadding="0" class="bordercolor">
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="2" cellspacing="1" class="bordercolor">
							<tr class="titlebg">
								<td align="left">&nbsp;', $message['counter'], '&nbsp;</td>
								<td>&nbsp;
									<a href="', $scripturl, '#', $topic['category']['id'], '">', $topic['category']['name'], '</a> /
									<a href="', $scripturl, '?board=', $topic['board']['id'], '.0">', $topic['board']['name'], '</a> /
									<a href="', $scripturl, '?topic=', $topic['id'], '.', $message['start'], ';topicseen#msg', $message['id'], '">', $message['subject_highlighted'], '</a>
								</td>
								<td align="right" width="30%">&nbsp;', $txt[30], ': ', $message['time'], '&nbsp;</td>
							</tr><tr class="catbg">
								<td colspan="2">', $txt[109], ' ', $topic['first_post']['member']['link'], ', ', $txt[72], ' ', $txt[525], ' ', $message['member']['link'], '</td>
								<td align="right">', $txt['search_relevance'], ': ', $topic['relevance'], '</td>
							</tr><tr>
								<td colspan="3" valign="top" class="windowbg2">', $message['body_highlighted'], '</td>
							</tr><tr>
								<td colspan="3" class="catbg" align="right">
									&nbsp;', implode($context['menu_separator'], $buttonArray), '</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br />';
			}
		}

		echo '
			<table width="100%" cellpadding="3" cellspacing="0" border="0" class="tborder">
				<tr>
					<td align="left" class="catbg" width="100%" height="30">
						<b>' . $txt[139] . ':</b> ' . $context['page_index'] . '
					</td>
				</tr>
			</table>';
		if ($settings['linktree_inline'])
			echo theme_linktree();
	}
}

?>