<?php
// Version: 1.0; Calendar

// The main calendar - January, for example.
function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $months, $months_short, $days;

	echo '
		<form action="', $scripturl, '?action=calendar" method="post">
			<div style="padding: 3px;">', theme_linktree(), '</div>
			<table cellspacing="1" cellpadding="1" width="100%" class="bordercolor">
				<caption class="titlebg"><span style="font-size: x-large;">' . $months[$context['current_month']] . ' ' . $context['current_year'] . '</span></caption>
				<tr>';

	// Show each day of the week.
	foreach ($context['week_days'] as $day)
		echo '
					<td class="windowbg" width="14%" align="center">' . $days[$day] . '</td>';
	echo '
				</tr>';

	/* Each week in weeks contains the following:
		days (a list of days), number (week # in the year.) */
	foreach ($context['weeks'] as $week)
	{
		echo '
				<tr>';

		/* Every day has the following:
			day (# in month), is_today (is this day *today*?), is_first_day (first day of the week?),
			holidays, events, birthdays. (last three are lists.) */
		foreach ($week['days'] as $day)
		{
			// If this is today, make it a different color and show a border.
			echo '
					<td class="windowbg" valign="top" style="', $day['is_today'] ? 'height: 96px; border: 2px outset; background-color: #C1E5FF;' : 'height: 100px;', ' padding: 2px;">';

			// Skip it if it should be blank - it's not a day if it has no number.
			if (!empty($day['day']))
			{
				// Should the day number be a link?
				if (!empty($modSettings['cal_daysaslink']) && $context['can_post'])
						echo '
						<a href="', $scripturl,'?action=post;calendar;month=', $context['current_month'], ';year=', $context['current_year'], ';day=', $day['day'], '">', $day['day'], '</a><span class="smalltext">';
					else
						echo '
						', $day['day'], '<span class="smalltext">';

				// Is this the first day of the week? (and are we showing week numbers?)
				if ($day['is_first_day'])
					echo ' - ', $txt['calendar51'], ' ', $week['number'];

				// Are there any holidays?
				if (!empty($day['holidays']))
					echo '
						<br />
						<span style="color: #', $modSettings['cal_holidaycolor'], ';">', $txt['calendar5'], ' ', implode(', ', $day['holidays']), '</span><br />';

				// Show any birthdays...
				if (!empty($day['birthdays']))
				{
					echo '
						<br />
						<span style="color: #', $modSettings['cal_bdaycolor'], ';">', $txt['calendar3'], '</span> ';

					/* Each of the birthdays has:
						id, name (person), age (if they have one set?), and is_last. (last in list?) */
					foreach ($day['birthdays'] as $member)
						echo '
							<a href="', $scripturl, '?action=profile;u=', $member['id'], '">', $member['name'], isset($member['age']) ? ' (' . $member['age'] . ')' : '', '</a>', $member['is_last'] ? '' : ', ';
					echo '
						<br />';
				}

				// Any special posted events?
				if (!empty($day['events']))
				{
					echo '
						<br />
						<span style="color: #', $modSettings['cal_eventcolor'], ';">', $txt['calendar4'], '</span> ';
					/* The events are made up of:
						title, href, is_last, can_edit (are they allowed to?), and modify_href. */
					foreach ($day['events'] as $event)
					{
						// If they can edit the event, show a star they can click on....
						if ($event['can_edit'])
							echo '
							<a href="', $event['modify_href'], '" style="color: #FF0000;">*</a> ';

						echo '
							<a href="', $event['href'], '">', $event['title'], '</a>', $event['is_last'] ? '' : ', ';
					}
					echo '
						<br />';
				}
				echo '</span>';
			}

			echo '
					</td>';
		}

		echo '
				</tr>';
	}

	echo '
			</table>
			<table cellspacing="0" cellpadding="3" width="100%" class="tborder" style="border-top: 0;">
				<tr class="windowbg2">
					<td>';

	// Is there a calendar for last month to look at?
	if (isset($context['previous_calendar']))
		echo '
						&nbsp;<a href="', $context['previous_calendar']['href'], '">&#171; ', $months_short[$context['previous_calendar']['month']], ' ', $context['previous_calendar']['year'], '</a>';
	echo '
					</td>
					<td align="center">';
	// Show a little "post event" button?
	if ($context['can_post'])
		echo '
						<a href="', $scripturl, '?action=post;calendar;month=', $context['current_month'], ';year=', $context['current_year'], '">', $settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/calendarpe.gif" alt="' . $txt['calendar23'] . '" border="0" />' : $txt['calendar23'], '</a>';
	echo '
					</td>
					<td align="center">
						<select name="month">';
	// Show a select box with all the months.
	foreach ($months as $number => $month)
		echo '
							<option value="', $number, '"', $number == $context['current_month'] ? ' selected="selected"' : '', '>', $month, '</option>';
	echo '
						</select>&nbsp;
						<select name="year">';
	// Show a link for every year.....
	for ($year = $modSettings['cal_minyear']; $year <= $modSettings['cal_maxyear']; $year++)
		echo '
							<option value="', $year, '"', $year == $context['current_year'] ? ' selected="selected"' : '', '>', $year, '</option>';
	echo '
						</select>&nbsp;
						<input type="submit" value="', $txt[305], '" />
					</td>
					<td align="center">';
	// Show another post button just for symmetry.
	if ($context['can_post'])
		echo '
						<a href="' . $scripturl . '?action=post;calendar;month=' . $context['current_month'] . ';year=' . $context['current_year'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/calendarpe.gif" alt="' . $txt['calendar23'] . '" border="0" />' : $txt['calendar23']) . '</a>';
	echo '
					</td>
					<td align="right">';

	// Is there a calendar for next month?
	if (isset($context['next_calendar']))
		echo '
						<a href="', $context['next_calendar']['href'], '">' . $months_short[$context['next_calendar']['month']] . ' ' . $context['next_calendar']['year'] . ' &#187;&#160;</a>';
	echo '
					</td>
				</tr>
			</table>
		</form>';
}

?>