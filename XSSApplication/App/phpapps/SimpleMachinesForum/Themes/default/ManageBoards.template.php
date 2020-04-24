<?php
// Version: 1.0; ManageBoards

// Template for listing all the current categories and boards.
function template_main()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Table header.
	echo '
		<table border="0" align="center" cellspacing="1" cellpadding="4" class="bordercolor" width="100%">
			<tr class="titlebg">
				<td colspan="3">
					<a href="' . $scripturl . '?action=helpadmin;help=1" onclick="return reqWin(this.href);" class="help"><img src="' . $settings['images_url'] . '/helptopics.gif" alt="' . $txt[119] . '" border="0" align="top" /></a>
					' . $txt[41] . '
				</td>
			</tr><tr class="windowbg">
				<td colspan="3" class="smalltext" style="padding: 2ex;">' . $txt[677] . '</td>
			</tr><tr class="titlebg">
				<td width="100%">
					' . $txt['boardsEdit'] . '
				</td>
			</tr>';

	// Button for creating a new category.
	echo '
			<tr>
				<td colspan="3" class="windowbg2" align="right">
					<form action="' . $scripturl . '?action=manageboards;sa=newcat" method="post" style="margin: 0 0 0 0;">
						<input type="submit" value="', $txt['mboards_new_cat'], '" />
						<input type="hidden" name="sc" value="', $context['session_id'], '" />
					</form>
				</td>
			</tr>';

	// Loop through every categories, listing the boards in each as we go.
	foreach ($context['categories'] as $category)
	{
		// Link to modify the category.
		echo '
			<tr>
				<td class="catbg" height="18">
					<a href="' . $scripturl . '?action=manageboards;sa=cat;ID_CAT=' . $category['id'] . '">', $category['name'], '</a> <a href="' . $scripturl . '?action=manageboards;sa=cat;ID_CAT=' . $category['id'] . '">', $txt['catModify'], '</a>
				</td>
			</tr>';

		// Boards table header.
		echo '
			<tr>
				<td class="windowbg2" width="100%" valign="top">
					<form action="', $scripturl, '?action=manageboards;sa=newboard;ID_CAT=', $category['id'], '" method="post">
						<table width="100%" border="0" cellpadding="1" cellspacing="0">
							<tr>
								<td style="padding-left: 1ex;" colspan="3"><b>', $txt['mboards_name'], '</b></td>
							</tr>';

		$alternate = false;

		// List through every board in the category, printing its name and link to modify the board.
		foreach ($category['boards'] as $board)
		{
			$alternate = !$alternate;

			echo '
							<tr class="windowbg', $alternate ? '' : '2', '">
								<td style="padding-left: ', 1 + 3 * $board['child_level'], 'ex;">', $board['name'], '</td>
								<td width="10%" align="right">', $context['can_manage_permissions'] ? '<a href="' . $scripturl . '?action=permissions;sa=switch;to=local;boardid=' . $board['id'] . ';sesc=' . $context['session_id'] . '">' . $txt['mboards_permissions'] . '</a>' : '', '</td>
								<td width="10%" style="padding-right: 1ex;" align="right"><a href="', $scripturl, '?action=manageboards;sa=board;ID_BOARD=', $board['id'], '">', $txt['mboards_modify'], '</a></td>
							</tr>';
		}

		// Button to add a new board.
		echo '
							<tr>
								<td colspan="3" align="right"><br /><input type="submit" value="', $txt['mboards_new_board'], '" /></td>
							</tr>
						</table>
						<input type="hidden" name="sc" value="', $context['session_id'], '" />
					</form>
				</td>
			</tr>';
	}
	echo '
		</table>';
}

// Template for editing/adding a category on the forum.
function template_modify_category()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Print table header.
	echo '
<form action="', $scripturl, '?action=manageboards;sa=cat2" method="post" name="catForm">
	<input type="hidden" name="ID_CAT" value="', $context['category']['id'], '" />
	<table border="0" width="500" cellspacing="0" cellpadding="0" class="bordercolor" align="center">
		<tr><td>
			<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
				<tr class="titlebg">
					<td>', isset($context['category']['is_new']) ? $txt['mboards_new_cat_name'] : $txt['catEdit'], '</td>
				</tr><tr>
					<td class="windowbg" valign="top">
						<table border="0" width="100%" cellspacing="0" cellpadding="2">
							<tr>';
	// If this isn't the only category, let the user choose where this category should be positioned down the board index.
	if (count($context['category_order']) > 1)
	{
		echo '
								<td>
									<b>', $txt[43], '</b><br />
									<br /><br />
								</td>
								<td valign="top" align="right">
									<select name="cat_order">';
		// Print every existing category into a select box.
		foreach ($context['category_order'] as $order)
			echo '
										<option', $order['selected'] ? ' selected="selected"' : '', ' value="', $order['id'], '">', $order['name'], '</option>';
		echo '
									</select>
								</td>
							</tr><tr>';
	}
	// Allow the user to edit the category name and/or choose whether you can collapse the category.
	echo '
								<td>
									<b>', $txt[44], ':</b><br />
									', $txt[672], '<br /><br />
								</td>
								<td valign="top" align="right">
									<input type="text" name="cat_name" value="', $context['category']['editable_name'], '" size="30" tabindex="1" />
								</td>
							</tr><tr>
								<td>
									<b>' . $txt['collapse_enable'] . '</b><br />
									' . $txt['collapse_desc'] . '<br /><br />
								</td>
								<td valign="top" align="right">
									<input type="checkbox" name="collapse"', $context['category']['can_collapse'] ? ' checked="checked"' : '', ' tabindex="2" class="check" />
								</td>
							</tr>';

	// Table footer.
	echo '
							<tr>
								<td colspan="2" align="right">
									<br />';
	if (isset($context['category']['is_new']))
		echo '
									<input type="submit" name="add" value="', $txt['mboards_add_cat_button'], '" onclick="return !isEmptyText(document.catForm.cat_name);" tabindex="3" />';
	else
		echo '
									<input type="submit" name="edit" value="', $txt[17], '" onclick="return !isEmptyText(document.catForm.cat_name);" tabindex="3" />
									<input type="submit" name="delete" value="', $txt['mboards_delete_cat'], '" onclick="return confirm(\'', $txt['catConfirm'], '\');" />';
	echo '
								</td>
							</tr>
						</table>
						<input type="hidden" name="sc" value="', $context['session_id'], '" />';
	// If this category is empty we don't bother with the next confirmation screen.
	if ($context['category']['is_empty'])
		echo '
						<input type="hidden" name="empty" value="1" />';
	echo '
					</td>
				</tr>
			</table>
		</td></tr>
	</table>
</form>';
}

// A template to confirm if a user wishes to delete a category - and whether they want to save the boards.
function template_confirm_category_delete()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Print table header.
	echo '
<form action="', $scripturl, '?action=manageboards;sa=cat2" method="post" name="catForm">
	<input type="hidden" name="ID_CAT" value="', $context['category']['id'], '" />

	<table width="600" cellpadding="4" cellspacing="0" border="0" align="center" class="tborder">
		<tr class="titlebg">
			<td>', $txt['mboards_delete_cat'], '</td>
		</tr><tr class="windowbg">
			<td class="windowbg" valign="top">
				', $txt['mboards_delete_cat_contains'], ':
				<ul>';

	foreach ($context['category']['children'] as $child)
		echo '
					<li>', $child['node']['name'], '</li>';

	echo '
				</ul>
			</td>
		</tr>
	</table>
	<br />
	<table width="600" cellpadding="4" cellspacing="0" border="0" align="center" class="tborder">
		<tr class="titlebg">
			<td>', $txt['mboards_delete_what_do'], ':</td>
		</tr>
		<tr>
			<td class="windowbg2">
				<label for="delete_action0"><input type="radio" id="delete_action0" name="delete_action" value="0" class="check" checked="checked" />', $txt['mboards_delete_option1'], '</label><br />
				<label for="delete_action1"><input type="radio" id="delete_action1" name="delete_action" value="1" class="check"', count($context['category_order']) == 1 ? ' disabled="disabled"' : '', ' />', $txt['mboards_delete_option2'], '</label>:
				<select name="cat_to" ', count($context['category_order']) == 1 ? 'disabled="disabled"' : '', '>';

	foreach ($context['category_order'] as $cat)
		if ($cat['id'] != 0)
			echo '
					<option value="', $cat['id'], '">', $cat['true_name'], '</option>';

	echo '
				</select>
			</td>
		</tr>
		<tr>
			<td align="center" class="windowbg2">
				<input type="submit" name="delete" value="', $txt['mboards_delete_confirm'], '" />
				<input type="submit" name="cancel" value="', $txt['mboards_delete_cancel'], '" />
			</td>
		</tr>
	</table>

	<input type="hidden" name="confirmation" value="1" />
	<input type="hidden" name="sc" value="', $context['session_id'], '" />
</form>';
}

// Below is the template for adding/editing an board on the forum.
function template_modify_board()
{
	global $context, $settings, $options, $scripturl, $txt;

	// The main table header.
	echo '
<form action="', $scripturl, '?action=manageboards;sa=board2" method="post" name="boardForm">
	<input type="hidden" name="ID_BOARD" value="', $context['board']['id'], '" />
	<table border="0" width="540" cellspacing="0" cellpadding="0" class="bordercolor" align="center">
		<tr><td>
			<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor">
				<tr class="titlebg">
					<td>', isset($context['board']['is_new']) ? $txt['mboards_new_board_name'] : $txt['boardsEdit'], '</td>
				</tr><tr>
					<td class="windowbg" valign="top">
						<table border="0" width="100%" cellspacing="0" cellpadding="2">';

	// Option for choosing the category the board lives in.
	echo '
							<tr>
								<td>
									<b>', $txt['mboards_category'], '</b><br />
									<br /><br />
								</td>
								<td valign="top" align="right">
									<select name="new_cat" onchange="if (document.boardForm.order) document.boardForm.order.disabled = (this.options[this.selectedIndex].value != 0);">';
		foreach ($context['categories'] as $category)
			echo '
										<option', $category['selected'] ? ' selected="selected"' : '', ' value="', $category['id'], '">', $category['name'], '</option>';
		echo '
									</select>
								</td>
							</tr><tr>';

	// If this isn't the only board in this category let the user choose where the board is to live.
	if ((isset($context['board']['is_new']) && count($context['board_order']) > 0) || count($context['board_order']) > 1)
	{
		echo '
								<td>
									<b>', $txt[43], '</b><br />
									<br /><br />
								</td>
								<td valign="top" align="right">';

	// The first select box gives the user the option to position it before, after or as a child of another board.
	echo '
									<select id="order" name="placement" onchange="document.boardForm.boardOrder.disabled = (this.options[this.selectedIndex].value == \'\')">
										', !isset($context['board']['is_new']) ? '<option value="">(' . $txt['mboards_unchanged'] . ')</option>' : '', '
										<option value="before">' . $txt['mboards_order_before'] . '...</option>
										<option value="child">' . $txt['mboards_order_child_of'] . '...</option>
										<option value="after">' . $txt['mboards_order_after'] . '...</option>
									</select>&nbsp;&nbsp;';

	// The second select box lists all the boards in the category.
	echo '
									<select id="boardOrder" name="board_order" ', isset($context['board']['is_new']) ? '' : 'disabled="disabled"', '>
										', !isset($context['board']['is_new']) ? '<option value="">(' . $txt['mboards_unchanged'] . ')</option>' : '';
		foreach ($context['board_order'] as $order)
			echo '
										<option', $order['selected'] ? ' selected="selected"' : '', ' value="', $order['id'], '">', $order['name'], '</option>';
		echo '
									</select>
								</td>
							</tr><tr>';
	}

	// Options for board name and description.
	echo '
								<td>
									<b>', $txt[44], ':</b><br />
									', $txt[672], '<br /><br />
								</td>
								<td valign="top" align="right">
									<input type="text" name="board_name" value="', $context['board']['name'], '" size="30" />
								</td>
							</tr><tr>
								<td>
									<b>', $txt['mboards_description'], '</b><br />
									', $txt['mboards_description_desc'], '<br /><br />
								</td>
								<td valign="top" align="right">
									<textarea name="desc" rows="2" cols="29">', $context['board']['description'], '</textarea>
								</td>
							</tr><tr>
								<td valign="top">
									<b>', $txt['mboards_groups'], '</b><br />
									', $txt['mboards_groups_desc'], '<br /><br />
								</td>
								<td valign="top" align="right">';

	// List all the membergroups so the user can choose who may access this board.
	foreach ($context['groups'] as $group)
		echo '
									' . $group['name'] . '&nbsp;&nbsp;<input type="checkbox" name="groups[]" value="' . $group['id'] . '"' . ($group['checked'] ? ' checked="checked"' : '') . ' /><br />';
	echo '
									<i>', $txt[737], '</i>&nbsp;&nbsp;<input type="checkbox" onclick="invertAll(this, this.form, \'groups[]\');" /><br />
									<br />
								</td>
							</tr>';

	// Options to choose moderators, specifiy as announcement board and choose whether to count posts here.
	echo '
							<tr>
								<td>
									<b>', $txt['mboards_moderators'], '</b><br />
									', $txt['mboards_moderators_desc'], '<br /><br />
								</td>
								<td valign="top" align="right" style="white-space: nowrap;">
									<input type="text" name="moderators" id="moderators" value="', implode(',', $context['board']['moderators']), '" size="30" />
									<a href="', $scripturl, '?action=findmember;input=moderators;quote;sesc=', $context['session_id'], '" onclick="return reqWin(this.href, 350, 400);"><img src="', $settings['images_url'], '/icons/assist.gif" border="0" alt="', $txt['find_members'], '" /></a>
								</td>
							</tr><tr>
								<td>
									<b>', $txt['mboards_count_posts'], '</b><br />
									', $txt['mboards_count_posts_desc'], '<br /><br />
								</td>
								<td valign="top" align="right">
									<input type="checkbox" name="count" ', $context['board']['count_posts'] ? ' checked="checked"' : '', ' class="check" />
								</td>
							</tr>';

	// Here the user can choose to force this board to use a theme other than the default theme for the forum.
	echo '
							<tr>
								<td>
									<b>', $txt['mboards_theme'], '</b><br />
									', $txt['mboards_theme_desc'], '<br /><br />
								</td>
								<td valign="top" align="right">
									<select name="boardtheme">
										<option value="0"', $context['board']['theme'] == 0 ? 'selected="selected"' : '', '>', $txt['mboards_theme_default'], '</option>';

	foreach ($context['themes'] as $theme)
		echo '
										<option value="', $theme['id'], '"', $context['board']['theme'] == $theme['id'] ? 'selected="selected"' : '', '>', $theme['name'], '</option>';

	echo '
									</select>
								</td>
							</tr><tr>
								<td>
									<b>', $txt['mboards_override_theme'], '</b><br />
									', $txt['mboards_override_theme_desc'], '<br /><br />
								</td>
								<td valign="top" align="right">
									<input type="checkbox" name="override_theme"', $context['board']['override_theme'] ? ' checked="checked"' : '', ' class="check" />
								</td>
							</tr>';

	// Finish off the table.
	echo '
							<tr>
								<td colspan="2" align="right">
									<br />';
	if (isset($context['board']['is_new']))
		echo '
									<input type="hidden" name="cur_cat" value="', $context['board']['category'], '">
									<input type="submit" name="add" value="', $txt['mboards_new_board'], '" onclick="return !isEmptyText(document.boardForm.board_name);" />';
	else
		echo '
									<input type="submit" value="', $txt[17], '" onclick="return !isEmptyText(document.boardForm.board_name);" />
									<input type="submit" name="delete" value="', $txt['mboards_delete_board'], '" onclick="return confirm(\'', $txt['boardConfirm'], '\');" />';
	echo '
								</td>
							</tr>
						</table>
						<input type="hidden" name="sc" value="', $context['session_id'], '" />
					</td>
				</tr>
			</table>
		</td></tr>
	</table>
</form>';
}

?>