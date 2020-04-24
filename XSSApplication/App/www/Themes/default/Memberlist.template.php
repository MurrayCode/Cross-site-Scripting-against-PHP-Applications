<?php
// Version: 1.0; Memberlist

// Displays a sortable listing of all members registered on the forum.
function template_main()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Show the link tree.
	echo '
		<table width="100%" cellpadding="3" cellspacing="0">
			<tr>
				<td>', theme_linktree(), '</td>
			</tr>
		</table>';

	// Display links to view all/search.
	echo '
		<table border="0" cellspacing="1" cellpadding="4" align="center" width="100%" class="bordercolor">
			<tr class="titlebg">
				<td colspan="12">
					', $context['sort_links'], '
				</td>
			</tr>
			<tr>
				<td colspan="12" class="catbg">';

	// Display page numbers and the a-z links for sorting by name if not a result of a search.
	if (!isset($context['old_search']))
		echo '
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td>', $txt[139], ': ', $context['page_index'], '</td>
							<td align="right">', $context['letter_links'] . '</td>
						</tr>
					</table>';
	// If this is a result of a search then just show the page numbers.
	else
		echo '
					', $txt[139], ': ', $context['page_index'];

	echo '
				</td>
			</tr>
			<tr class="titlebg">';

	// Display each of the column headers of the table.
	foreach ($context['columns'] as $column)
	{
		// We're not able (through the template) to sort the search results right now...
		if (isset($context['old_search']))
			echo '
				<td', isset($column['width']) ? ' width="' . $column['width'] . '"' : '', isset($column['colspan']) ? ' colspan="' . $column['colspan'] . '"' : '', '>
					', $column['label'], '</td>';
		// This is a selected solumn, so underline it or some such.
		elseif ($column['selected'])
			echo '
				<td style="width: auto;"' . (isset($column['colspan']) ? ' colspan="' . $column['colspan'] . '"' : '') . ' nowrap="nowrap">
					<a href="' . $column['href'] . '">' . $column['label'] . ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" /></a></td>';
		// This is just some column... show the link and be done with it.
		else
			echo '
				<td', isset($column['width']) ? ' width="' . $column['width'] . '"' : '', isset($column['colspan']) ? ' colspan="' . $column['colspan'] . '"' : '', '>
					', $column['link'], '</td>';
	}

	echo '
			</tr>';

	// Assuming there are members loop through each one displaying their data.
	if (!empty($context['members']))
	{
		foreach ($context['members'] as $member)
			echo '
			<tr style="text-align: center;">
				<td class="windowbg2">
					', $context['can_send_pm'] ? '<a href="' . $member['online']['href'] . '" title="' . $member['online']['text'] . '">' : '', $settings['use_image_buttons'] ? '<img src="' . $member['online']['image_href'] . '" alt="' . $member['online']['text'] . '" border="0" align="middle" />' : $member['online']['label'], $context['can_send_pm'] ? '</a>' : '', '
				</td>
				<td class="windowbg" align="left">', $member['link'], '</td>
				<td class="windowbg2">', $member['email'], '</td>
				<td class="windowbg">', $member['website']['link'], '</td>
				<td class="windowbg2">', $member['icq']['link'], '</td>
				<td class="windowbg2">', $member['aim']['link'], '</td>
				<td class="windowbg2">', $member['yim']['link'], '</td>
				<td class="windowbg2">', $member['msn']['link'], '</td>
				<td class="windowbg" align="left">', $member['group'], '</td>
				<td class="windowbg" align="left">', $member['registered'], '</td>
				<td class="windowbg2" width="15">', $member['posts'], '</td>
				<td class="windowbg" width="100" align="left">
					', $member['posts'] > 0 ? '<img src="' . $settings['images_url'] . '/bar.gif" width="' . $member['post_percent'] . '" height="15" alt="" border="0" />' : '', '
				</td>
			</tr>';
	}
	// No members?
	else
		echo '
			<tr>
				<td colspan="12" class="windowbg">', $txt[170], '</td>
			</tr>';

	// Show the page numbers again. (makes 'em easier to find!)
	echo '
			<tr>
				<td class="catbg" colspan="12">', $txt[139], ': ', $context['page_index'], '</td>
			</tr>
		</table>';

	// If it is displaying the result of a search show a "search again" link to edit their criteria.
	if (isset($context['old_search']))
		echo '
		<br />
		<a href="', $scripturl, '?action=mlist;sa=search;search=', $context['old_search_value'], '">', $txt['mlist_search2'], '</a>';
}

// A page allowing people to search the member list.
function template_search()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Start the submission form for the search!
	echo '
	<form action="', $scripturl, '?action=mlist;sa=search" method="post">';

	// Display that link tree...
	echo '
		<table width="100%" cellpadding="3" cellspacing="0">
			<tr>
				<td>', theme_linktree(), '</td>
			</tr>
		</table>';

	// Display links to view all/search.
	echo '
		<table border="0" width="100%" cellspacing="1" cellpadding="4" class="bordercolor" align="center">
			<tr class="titlebg">
				<td>
					', $context['sort_links'] . '
				</td>
			</tr>';

	// Display the input boxes for the form.
	echo '
			<tr>
				<td class="windowbg" align="center" style="padding-bottom: 1ex;">
					<table width="440" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td colspan="2" align="left">
								<br />
								<b>', $txt[582], ':</b> <input type="text" name="search" value="', $context['old_search'], '" size="35" /> <input type="submit" name="submit" value="' . $txt[182] . '" style="margin-left: 20px;" /><br />
								<br />
							</td>
						</tr>
						<tr>
							<td align="left">
								<input type="checkbox" name="fields[]" value="email" checked="checked" class="check" /> ', $txt['mlist_search_email'], '<br />
								<input type="checkbox" name="fields[]" value="messenger" class="check" /> ', $txt['mlist_search_messenger'], '<br />
								<input type="checkbox" name="fields[]" value="group" class="check" /> ', $txt['mlist_search_group'], '
							</td>
							<td align="left" valign="top">
								<input type="checkbox" name="fields[]" value="name" checked="checked" class="check" /> ', $txt['mlist_search_name'], '<br />
								<input type="checkbox" name="fields[]" value="website" class="check" /> ', $txt['mlist_search_website'], '
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</form>';
}

?>