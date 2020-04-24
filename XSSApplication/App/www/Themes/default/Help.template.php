<?php
// Version: 1.0; Help

function template_popup()
{
	global $context, $settings, $options, $txt;

	// Since this is a popup of its own we need to start the html, etc.
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html', $context['right_to_left'] ? ' dir="rtl"' : '', '>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
		<title>', $context['page_title'], '</title>
		<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/style.css" />
		<style type="text/css"><!--';

	// Internet Explorer 4/5 and Opera 6 just don't do font sizes properly. (they are bigger...)
	if ($context['browser']['needs_size_fix'])
		echo '
			@import(', $settings['default_theme_url'], '/fonts-compat.css);';

	// Just show the help text and a "close window" link.
	echo '
		--></style>
	</head>
	<body style="margin: 1ex;">
		', $context['help_text'], '<br />
		<br />
		<div align="center"><a href="javascript:self.close();">', $txt[1006], '</a></div>
	</body>
</html>';
}

function template_find_members()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html', $context['right_to_left'] ? ' dir="rtl"' : '', '>
	<head>
		<title>', $txt['find_members'], '</title>
		<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
		<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/style.css" />
		<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/script.js"></script>
		<script language="JavaScript" type="text/javascript"><!--
			var membersAdded = [];
			function addMember(name)
			{
				var theTextBox = window.opener.document.getElementById("', $context['input_box_name'], '");

				if (typeof(membersAdded[name]) != "undefined")
					return;
				membersAdded[name] = true;

				if (theTextBox.value.length < 1)
					theTextBox.value = name;
				else
					theTextBox.value += "', $context['delimiter'], '" + ', $context['quote_results'] ? '"\"" + name + "\""' : 'name', ';

				window.focus();
			}
		// --></script>
	</head>
	<body>
		<form action="', $scripturl, '?action=findmember;sesc=', $context['session_id'], '" method="post">
			<table border="0" width="100%" cellpadding="4" cellspacing="0" class="tborder">
				<tr class="titlebg">
					<td align="center" colspan="2">', $txt['find_members'], '</td>
				</tr>
				<tr class="windowbg">
					<td align="left" colspan="2">
						<b>', $txt['find_username'], ':</b><br />
						<input type="text" name="search" id="search" value="', isset($context['last_search']) ? $context['last_search'] : '', '" style="margin-top: 4px; width: 96%;" /><br />
						<span class=smalltext><i>', $txt['find_wildcards'], '</i></span>
					</td>
				</tr>
				<tr class="windowbg">
					<td align="right" colspan="2">
						<input type="button" value="', $txt['find_close'], '" onclick="window.close();" />
						<input type="submit" value="', $txt[182], '" />
					</td>
				</tr>
			</table>

			<br />

			<table border="0" width="100%" cellpadding="4" cellspacing="0" class="tborder">
				<tr class="titlebg">
					<td align="center">', $txt['find_results'], '</td>
				</tr>';

	if (empty($context['results']))
		echo '
				<tr class="windowbg">
					<td align="center">', $txt['find_no_results'], '</td>
				</tr>';
	else
	{
		$alternate = true;
		foreach ($context['results'] as $result)
		{
			echo '
				<tr class="', $alternate ? 'windowbg2' : 'windowbg', '" valign="middle">
					<td align="left">
						<a href="', $result['href'], '" target="_blank"><img src="' . $settings['images_url'] . '/icons/profile_sm.gif" alt="' . $txt[27] . '" title="' . $txt[27] . '" border="0" /></a>
						<a href="#" onclick="addMember(this.title); return false;" title="', $result['username'], '">', $result['name'], '</a>
					</td>
				</tr>';

			$alternate = !$alternate;
		}

		echo '
				<tr class="titlebg">
					<td align="left">', $txt[139], ': ', $context['page_index'], '</td>
				</tr>';
	}

	echo '
			</table>
			<input type="hidden" name="input" value="', $context['input_box_name'], '" />
			<input type="hidden" name="delim" value="', $context['delimiter'], '" />
			<input type="hidden" name="quote" value="', $context['quote_results'] ? '1' : '0', '" />
		</form>';

	if (empty($context['results']))
		echo '
		<script language="JavaScript" type="text/javascript"><!--
			document.getElementById("search").focus();
		// --></script>';

	echo '
	</body>
</html>';
}

?>