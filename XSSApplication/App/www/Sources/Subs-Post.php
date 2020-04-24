<?php
/******************************************************************************
* Subs-Post.php                                                               *
*******************************************************************************
* SMF: Simple Machines Forum                                                  *
* Open-Source Project Inspired by Zef Hemel (zef@zefhemel.com)                *
* =========================================================================== *
* Software Version:           SMF 1.0                                         *
* Software by:                Simple Machines (http://www.simplemachines.org) *
* Copyright 2001-2004 by:     Lewis Media (http://www.lewismedia.com)         *
* Support, News, Updates at:  http://www.simplemachines.org                   *
*******************************************************************************
* This program is free software; you may redistribute it and/or modify it     *
* under the terms of the provided license as published by Lewis Media.        *
*                                                                             *
* This program is distributed in the hope that it is and will be useful,      *
* but WITHOUT ANY WARRANTIES; without even any implied warranty of            *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                        *
*                                                                             *
* See the "license.txt" file for details of the Simple Machines license.      *
* The latest version can always be found at http://www.simplemachines.org.    *
******************************************************************************/
if (!defined('SMF'))
	die('Hacking attempt...');

/*	This file contains those functions pertaining to posting, and other such
	operations, including sending emails, ims, blocking spam, preparsing posts,
	spell checking, and the post box.  This is done with the following:

	void preparsecode(string &message, boolean breaks = true)
		- takes a message and parses it, returning nothing.
		- cleans up links (javascript, etc.) and code/quote sections.
		- won't convert \n's if breaks is false.

	void fixTags(string &message)
		- used by preparsecode, fixes links in message and returns nothing.

	void fixTag(string &message, string myTag, string protocol,
			bool embeddedUrl = false, bool hasEqualSign = false)
		- used by fixTags, fixes a specific tag's links.
		- myTag is the tag, protocol is http of ftp, embeddedUrl is whether
		  it *can* be set to something, and hasEqualSign is whether it *is*
		  set to something.

	bool sendmail(array to, string subject, string message,
			string from = webmaster, bool send_html = false)
		- sends an email to the specified recipient.
		- uses the mail_type setting and the webmaster_email global.
		- to is he email(s), string or array, to send to.
		- subject and message are those of the email - expected to have
		  slashes but not be parsed.
		- subject is expected to have entities, message is not.
		- from is a string which masks the address for use with replies.
		- send_html indicates whether or not the message is HTML vs. plain
		  text, and does not add any HTML.
		- returns whether or not the email was sent properly.

	array sendpm(array recipients, string subject, string message,
			bool store_outbox, array from = current_member)
		- sends an personal message from the specified person to the
		  specified people. (from defaults to the user.)
		- recipients should be an array containing the arrays 'to' and 'bcc',
		  both containing ID_MEMBERs.
		- subject and message should have no slashes and no html entities.
		- from is an array, with the id, name, and username of the member.
		- returns an array with log entries telling how many recipients were
		  successful and which recipients it failed to send to.

	bool smtp_mail(array mail_to_array, string subject, string message,
			string headers)
		- sends mail, like mail() but over SMTP.  Used internally.
		- takes email addresses, a subject and message, and any headers.
		- expects no slashes or entities.
		- returns whether it sent or not.

	bool server_parse(string message, resource socket, string response)
		- sends the specified message to the server, and checks for the
		  expected response. (used internally.)
		- takes the message to send, socket to send on, and the expected
		  response code.
		- returns whether it responded as such.

	void calendarValidatePost()
		- checks if the calendar post was valid.

	void theme_postbox(string message)
		- outputs a postbox from a template.
		- takes a default message as a parameter.

	void SpellCheck()
		- spell checks the post for typos ;).
		- uses the pspell library, which MUST be installed.
		- has problems with internationalization.
		- is accessed via ?action=spellcheck.

	void sendNotifications(int ID_TOPIC, string type)
		- sends a notification to members who have elected to receive emails
		  when things happen to a topic, such as replies are posted.
		- uses the Post langauge file.
		- ID_TOPIC represents the topic the action is happening to.
		- the type can be any of reply, sticky, lock, unlock, remove, move,
		  merge, and split.  An appropriate message will be sent for each.
		- automatically finds the subject and its board, and checks permissions
		  for each member who is "signed up" for notifications.
		- will not send 'reply' notifications more than once in a row.
*/

// Parses some bbc before sending into the database...
function preparsecode(&$message, $breaks = true)
{
	global $user_info;

	// This line makes all languages *theoretically* work even with the wrong charset ;).
	$message = preg_replace('~&amp;#(\d{4,5}|[3-9]\d{2,4}|2[6-9]\d);~', '&#$1;', $message);

	// Replace /me.+?\n with [me=name]dsf[/me]\n.
	if (strstr($user_info['name'], '[') || strstr($user_info['name'], ']') || strstr($user_info['name'], '\'') || strstr($user_info['name'], '"'))
		$message = preg_replace('~(\A|\n|<br />)/me(?: |&nbsp;)(.*?)([\r\n]|<br />|\z)~i', '$1[me=&quot;' . $user_info['name'] . '&quot;]$2[/me]$3', $message);
	else
		$message = preg_replace('~(\A|\n|<br />)/me(?: |&nbsp;)(.*?)([\r\n]|<br />|\z)~i', '$1[me=' . $user_info['name'] . ']$2[/me]$3', $message);

	// Remove \r's, replace tabs with spaces, two spaces with hard spaces, and \n's with breaks. (last is optional...)
	$message = strtr($message, array("\r" => '', '  ' => '&nbsp; '));
	if ($breaks)
		$message = strtr($message, array("\n" => '<br />'));

	// Check if all quotes are closed...
	$parts = preg_split('~(\[/quote\])~i', $message, -1, PREG_SPLIT_DELIM_CAPTURE);

	$level = 0;
	for ($i = 0, $n = count($parts); $i < $n; $i++)
	{
		if (preg_match('~\[/quote\]~i',$parts[$i]) != 0)
			$level--;
		preg_match_all('~(\[quote=.+?\])|(\[quote author=.+?\])|(\[quote author=(.+?) link=(.+?) date=(.+?)\])|(\[quote\])~i', $parts[$i], $regs);
		$level += count($regs[0]);

		// Add on extra [quote]s...
		if ($level < 0)
		{
			$parts[$i] = str_repeat('[quote]', 0 - $level) . $parts[$i];
			$level = 0;
		}
	}
	$message = implode('', $parts);

	// Add additional [/quote]s to the end.
	if ($level > 0)
		$message .= str_repeat('[/quote]', $level);

	// Check if all code tags are closed.
	$codeopen = preg_match_all('~(?!\[)(\[code\])~i', $message, $dummy);
	$codeclose = preg_match_all('~(?!\[)(\[/code\])~i', $message, $dummy);

	// Close/open all code tags...
	if ($codeopen > $codeclose)
		$message .= str_repeat('[/code]', $codeopen - $codeclose);
	elseif ($codeclose > $codeopen)
		$message = str_repeat('[code]', $codeclose - $codeopen) . $message;

	// Now that we've fixed all the code tags, let's fix the img and url tags...
	$parts = preg_split('~\[/?code\]~i', $message);

	// Only mess with stuff outside [code] tags.
	for ($i = 0, $n = count($parts); $i < $n; $i++)
	{
		// 1 (odd) means a code section, not post text.
		if ($i % 2 == 1)
			$parts[$i] = '[code]' . $parts[$i] . '[/code]';
		// Mess with the tags outside [code].
		else
			fixTags($parts[$i]);
	}

	// Put it back together and remove that first space.
	$message = implode('', $parts);
}

// Fix any URLs posted - ie. remove 'javascript:'.
function fixTags(&$message)
{
	global $modSettings;

	$fixArray = array
	(
		// [img]http://...[/img] or [img width=1]http://...[/img]
		array('tag' => 'img', 'protocol' => 'http', 'embeddedUrl' => false, 'hasEqualSign' => false, 'hasExtra' => true),
		// [url]http://...[/url]
		array('tag' => 'url', 'protocol' => 'http', 'embeddedUrl' => true, 'hasEqualSign' => false),
		// [url=http://...]name[/url]
		array('tag' => 'url', 'protocol' => 'http', 'embeddedUrl' => true, 'hasEqualSign' => true),
		// [iurl]http://...[/iurl]
		array('tag' => 'iurl', 'protocol' => 'http', 'embeddedUrl' => true, 'hasEqualSign' => false),
		// [iurl=http://...]name[/iurl]
		array('tag' => 'iurl', 'protocol' => 'http', 'embeddedUrl' => true, 'hasEqualSign' => true),
		// [ftp]ftp://...[/ftp]
		array('tag' => 'ftp', 'protocol' => 'ftp', 'embeddedUrl' => true, 'hasEqualSign' => false),
		// [ftp=ftp://...]name[/ftp]
		array('tag' => 'ftp', 'protocol' => 'ftp', 'embeddedUrl' => true, 'hasEqualSign' => true),
		// [flash]http://...[/flash]
		array('tag' => 'flash', 'protocol' => 'http', 'embeddedUrl' => false, 'hasEqualSign' => true, 'hasExtra' => true)
	);

	// Fix each type of tag.
	foreach ($fixArray as $param)
		fixTag($message, $param['tag'], $param['protocol'], $param['embeddedUrl'], $param['hasEqualSign'], isset($param['hasExtra']));

	// Now fix possible security problems with images loading links automatically...
	$message = preg_replace('~(\[img.*?\])(.+?)\[/img\]~eis', '\'$1\' . preg_replace(\'~action(=|%3d)(?!dlattach)~i\', \'action-\', \'$2\') . \'[/img]\'', $message);

	// Limit the size of images posted?
	if (!empty($modSettings['maxwidth']) || !empty($modSettings['maxheight']))
	{
		// Find all the img tags - with or without width and height.
		preg_match_all('~\[img(\s+width=\d+)?(\s+height=\d+)?(\s+width=\d+)?\](.+?)\[/img\]~is', $message, $matches, PREG_PATTERN_ORDER);

		$replaces = array();
		foreach ($matches[0] as $match => $dummy)
		{
			// If the width was after the height, handle it.
			$matches[1][$match] = !empty($matches[3][$match]) ? $matches[3][$match] : $matches[1][$match];

			// Now figure out if they had a desired height or width...
			$desired_width = !empty($matches[1][$match]) ? (int) substr(trim($matches[1][$match]), 6) : 0;
			$desired_height = !empty($matches[2][$match]) ? (int) substr(trim($matches[2][$match]), 7) : 0;

			// One was omitted, or both.  We'll have to find its real size...
			if (empty($desired_width) || empty($desired_height))
			{
				list ($width, $height) = url_image_size($matches[4][$match]);

				// They don't have any desired width or height!
				if (empty($desired_width) && empty($desired_height))
				{
					$desired_width = $width;
					$desired_height = $height;
				}
				// Scale it to the width...
				elseif (empty($desired_width))
					$desired_width = (int) (($desired_height * $width) / $height);
				// Scale if to the height.
				else
					$desired_height = (int) (($desired_width * $height) / $width);
			}

			// If the width and height are fine, just continue along...
			if ($desired_width <= $modSettings['maxwidth'] && $desired_height <= $modSettings['maxheight'])
				continue;

			// Too bad, it's too wide.  Make it as wide as the maximum.
			if ($desired_width > $modSettings['maxwidth'] && !empty($modSettings['maxwidth']))
			{
				$desired_height = (int) (($modSettings['maxwidth'] * $desired_height) / $desired_width);
				$desired_width = $modSettings['maxwidth'];
			}

			// Now check the height, as well.  Might have to scale twice, even...
			if ($desired_height > $modSettings['maxheight'] && !empty($modSettings['maxheight']))
			{
				$desired_width = (int) (($modSettings['maxheight'] * $desired_width) / $desired_height);
				$desired_height = $modSettings['maxheight'];
			}

			$replaces[$matches[0][$match]] = '[img width=' . $desired_width . ' height=' . $desired_height . ']' . $matches[4][$match] . '[/img]';
		}

		// If any img tags were actually changed...
		if (!empty($replaces))
			$message = strtr($message, $replaces);
	}
}

// Fix a specific class of tag - ie. url with =.
function fixTag(&$message, $myTag, $protocol, $embeddedUrl = false, $hasEqualSign = false, $hasExtra = false)
{
	while (preg_match('/\[(' . $myTag . ($hasExtra ? '(?:[^\]]*?)' : '') . ')' . ($hasEqualSign ? '(=(.+?))' : '(())') . '\](.+?)\[\/(' . $myTag . ')\]/is', $message, $matches))
	{
		// All the different information from the match.
		$leftTag = $matches[1];
		$equalTo = $matches[3];
		$searchfor = $matches[4];
		$rightTag = $matches[5];
		$replace = $hasEqualSign && $embeddedUrl ? $equalTo : $searchfor;

		// Remove all leading and trailing whitespace.
		$replace = trim($replace);
		if (!stristr($replace, $protocol . '://'))
		{
			if ($protocol != 'http' || !stristr($replace, 'https://'))
				$replace = $protocol . '://' . $replace;
			else
				$replace = stristr($replace, 'https://');
		}
		else
			$replace = stristr($replace, $protocol . '://');

		// Put the tag back together.
		if ($hasEqualSign && $embeddedUrl)
			$message = str_replace(
				'[' . $leftTag . '=' . $equalTo . ']' . $searchfor . '[/' . $rightTag . ']',
				'<' . $myTag . '=' . $replace . ']' . $searchfor . '</' . $myTag . '>', $message);
		elseif ($hasEqualSign && !$embeddedUrl)
			$message = str_replace(
				'[' . $leftTag . '=' . $equalTo . ']' . $searchfor . '[/' . $rightTag . ']',
				'<' . $myTag . '=' . $equalTo . ']' . $replace . '</' . $myTag . '>', $message);
		elseif ($embeddedUrl && $replace != $searchfor)
			$message = str_replace(
				'[' . $leftTag . ']' . $searchfor . '[/' . $rightTag . ']',
				'<' . $myTag . '=' . $replace . ']' . $searchfor . '</' . $myTag . '>', $message);
		else
			$message = str_replace(
				'[' . $leftTag . ']' . $searchfor . '[/' . $rightTag . ']',
				'<' . preg_replace('~' . preg_quote($myTag) . '~i', $myTag, $leftTag) . '>' . $replace . '</' . $myTag . '>', $message);
	}

	// Replace the braces with brackets.
	$message = str_replace(
		array('<' . $myTag . '>', '<' . $myTag . '=', '</' . $myTag . '>'),
		array('[' . $myTag . ']', '[' . $myTag . '=', '[/' . $myTag . ']'), $message);

	// If there is extra stuff we also need to do this. (flash, img.)
	if ($hasExtra)
		$message = preg_replace('~<(' . $myTag . '.+?)>~i', '[$1]', $message);
}

// Send off an email.
function sendmail($to, $subject, $message, $from = null, $send_html = false)
{
	global $webmaster_email, $context, $modSettings, $txt, $scripturl;

	// So far so good.
	$mail_result = true;

	// If the recipient list isn't an array, make it one.
	$to_array = is_array($to) ? $to : array($to);

	// Get rid of slashes and entities.
	$subject = un_htmlspecialchars(stripslashes($subject));
	// Make the message use \r\n's only.
	$message = str_replace(array("\r", "\n"), array('', "\r\n"), stripslashes($message));

	// Construct the mail headers...
	$headers = 'From: "' . addcslashes($from !== null ? $from : $context['forum_name'], '<>[]()\'\\"') . '" <' . $webmaster_email . ">\r\n";
	$headers .= $from !== null ? 'Reply-To: <' . $from . ">\r\n" : '';
	$headers .= 'Return-Path: ' . $webmaster_email . "\r\n";
	$headers .= 'Date: ' . gmdate('D, d M Y H:i:s') . ' +0000' . "\r\n";

	// Sending HTML?  Let's plop in some basic stuff, then.
	if ($send_html)
		$headers .= 'Content-Type: text/html; charset=' . $txt['lang_character_set'];
	// Text is good too.
	else
		$headers .= 'Content-Type: text/plain; charset=' . $txt['lang_character_set'];

	// MIME encode the subject - this is tricksy.
	for ($i = 0; $i < strlen($subject); $i++)
		if (ord($subject{$i}) > 128 || $subject{$i} == '=' || $subject{$i} == '?' || $subject{$i} == '_')
		{
			// Add on to the string whenever we find a special character.
			$subject = substr($subject, 0, $i) . '=' . strtoupper(dechex(ord($subject{$i}))) . substr($subject, $i + 1);
			$i18n_char = true;
		}

	// We don't need to mess with the subject line if no special characters were in it..
	if (!empty($i18n_char))
		$subject = '=?' . $txt['lang_character_set'] . '?Q?' . $subject . '?=';

	// SMTP or sendmail?
	if ($modSettings['mail_type'] == 'sendmail')
	{
		$subject = strtr($subject, array("\r" => '', "\n" => ''));
		$message = strtr($message, array("\r" => ''));
		$headers = strtr($headers, array("\r" => ''));

		foreach ($to_array as $to)
		{
			if (!mail(strtr($to, array("\r" => '', "\n" => '')), $subject, $message, $headers))
			{
				log_error(sprintf($txt['mail_send_unable'], $to));
				$mail_result = false;
			}
		}
	}
	else
		$mail_result = smtp_mail($to_array, $subject, $message, "MIME-Version: 1.0\r\n" . $headers);

	// Everything go smoothly?
	return $mail_result;
}

// Send off a personal message.
function sendpm($recipients, $subject, $message, $store_outbox, $from = null)
{
	global $db_prefix, $ID_MEMBER, $scripturl, $txt, $user_info, $language;

	// Initialize log array.
	$log = array(
		'failed' => array(),
		'sent' => array()
	);

	if ($from === null)
		$from = array(
			'id' => $ID_MEMBER,
			'name' => $user_info['name'],
			'username' => $user_info['username']
		);
	// Probably not needed.  /me something should be of the typer.
	else
		$user_info['name'] = $from['name'];

	// This is the one that will go in their inbox.
	$htmlmessage = htmlspecialchars($message, ENT_QUOTES);
	$htmlsubject = htmlspecialchars($subject);
	preparsecode($htmlmessage);

	// Get a list of usernames and convert them to IDs.
	$usernames = array();
	foreach ($recipients as $rec_type => $rec)
	{
		foreach ($rec as $id => $member)
		{
			if (!is_numeric($recipients[$rec_type][$id]))
			{
				$recipients[$rec_type][$id] = strtolower(trim(preg_replace('/[<>&"\'=\\\]/', '', $recipients[$rec_type][$id])));
				$usernames[$recipients[$rec_type][$id]] = 0;
			}
		}
	}
	if (!empty($usernames))
	{
		$request = db_query("
			SELECT ID_MEMBER, memberName
			FROM {$db_prefix}members
			WHERE memberName IN ('" . explode("', '", array_keys($usernames)) . "')", __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
			if (isset($usernames[strtolower($row['memberName'])]))
				$usernames[strtolower($row['memberName'])] = $row['ID_MEMBER'];

		// Replace the usernames with IDs. Drop usernames that couldn't be found.
		foreach ($recipients as $rec_type => $rec)
			foreach ($rec as $id => $member)
			{
				if (is_numeric($recipients[$rec_type][$id]))
					continue;

				if (!empty($usernames[$member]))
					$recipients[$rec_type][$id] = $usernames[$member];
				else
				{
					$log['failed'][] = sprintf($txt['pm_error_user_not_found'], $recipients[$rec_type][$id]);
					unset($recipients[$rec_type][$id]);
				}
			}
	}

	// Make sure there are no duplicate 'to' members.
	$recipients['to'] = array_unique($recipients['to']);

	// Only 'bcc' members that aren't already in 'to'.
	$recipients['bcc'] = array_diff(array_unique($recipients['bcc']), $recipients['to']);

	// Combine 'to' and 'bcc' recipients.
	$all_to = array_merge($recipients['to'], $recipients['bcc']);

	$request = db_query("
		SELECT
			mem.memberName, mem.realName, mem.ID_MEMBER, mem.emailAddress, mem.lngfile, mg.maxMessages,
			mem.im_email_notify, mem.instantMessages," . (allowedTo('moderate_forum') ? ' 0' : "
			(mem.im_ignore_list = '*' OR FIND_IN_SET($from[id], mem.im_ignore_list))") . " AS ignored
		FROM {$db_prefix}members AS mem
			LEFT JOIN {$db_prefix}membergroups AS mg ON (mg.ID_GROUP = IF(mem.ID_GROUP = 0, mem.ID_POST_GROUP, mem.ID_GROUP))
		WHERE mem.ID_MEMBER IN (" . implode(", ", $all_to) . ")
		ORDER BY mem.lngfile
		LIMIT " . count($all_to), __FILE__, __LINE__);
	$notifications = array();
	while ($row = mysql_fetch_assoc($request))
	{
		if (!empty($row['maxMessages']) && $row['maxMessages'] <= $row['instantMessages'] && !allowedTo('moderate_forum'))
		{
			$log['failed'][] = sprintf($txt['pm_error_data_limit_reached'], $row['realName']);
			unset($all_to[array_search($row['ID_MEMBER'], $all_to)]);
			continue;
		}

		if (!empty($row['ignored']))
		{
			$log['failed'][] = sprintf($txt['pm_error_ignored_by_user'], $row['realName']);
			unset($all_to[array_search($row['ID_MEMBER'], $all_to)]);
			continue;
		}

		// Send a notification, if enabled.
		if (!empty($row['im_email_notify']) && !empty($row['emailAddress']))
			$notifications[empty($row['lngfile']) || empty($modSettings['userLanguage']) ? $language : $row['lngfile']][] = $row['emailAddress'];

		$log['sent'][] = sprintf($txt['pm_successfully_sent'], $row['realName']);
	}
	mysql_free_result($request);

	// Only 'send' the message if there are any recipients left.
	if (empty($all_to))
		return $log;

	// Insert the message itself and then grab the last insert id.
	db_query("
		INSERT INTO {$db_prefix}instant_messages
			(ID_MEMBER_FROM, deletedBySender, fromName, msgtime, subject, body)
		VALUES ($from[id], " . ($store_outbox ? '0' : '1') . ", '$from[username]', " . time() . ", '$htmlsubject', '$htmlmessage')", __FILE__, __LINE__);
	$ID_PM = db_insert_id();

	// Some people think manually deleting instant_messages is fun... it's not. We protect against it though :)
	db_query("
		DELETE FROM {$db_prefix}im_recipients
		WHERE ID_PM = $ID_PM", __FILE__, __LINE__);

	// Add the recipients.
	if (!empty($ID_PM))
	{
		$insertRows = array();
		foreach ($all_to as $to)
			$insertRows[] = "($ID_PM, $to, " . (in_array($to, $recipients['bcc']) ? '1' : '0') . ')';
		db_query("
			INSERT INTO {$db_prefix}im_recipients
				(ID_PM, ID_MEMBER, bcc)
			VALUES " . implode(',
				', $insertRows), __FILE__, __LINE__);
	}

	foreach ($notifications as $lang => $notification_list)
	{
		// Make sure to use the right language.
		loadLanguage('InstantMessage', $lang, false);

		// Replace the right things in the message strings.
		$mailsubject = str_replace(array('SUBJECT', 'SENDER'), array($subject, $from['name']), $txt[561]);
		$mailmessage = str_replace(array('SUBJECT', 'MESSAGE', 'SENDER'), array($subject, $message, $from['name']), $txt[562]);
		$mailmessage .= "\n\n" . $txt['instant_reply'] . ' ' . $scripturl . '?action=pm;sa=send;f=inbox;pmsg=' . $ID_PM . ';quote;u=' . $from['id'];

		// Off the notification email goes!
		sendmail($notification_list, $mailsubject, $mailmessage);
	}

	// Add one to their unread and read message counts.
	updateMemberData($all_to, array('instantMessages' => '+', 'unreadMessages' => '+'));

	return $log;
}

// Send an email via SMTP.
function smtp_mail($mail_to_array, $subject, $message, $headers)
{
	global $modSettings, $webmaster_email, $txt;

	// Try to connect to the SMTP server... if it doesn't exist, only wait five seconds.
	if (!$socket = fsockopen($modSettings['smtp_host'], empty($modSettings['smtp_port']) ? 25 : $modSettings['smtp_port'], $errno, $errstr, 5))
	{
		// Unable to connect!  Don't show any error message, but just log one and try to continue anyway.
		log_error($txt['smtp_no_connect'] . ' : ' . $errno . ' : ' . $errstr);
		return false;
	}

	// Wait for a response of 220, without "-" continuer.
	if (!server_parse(null, $socket, '220'))
		return false;

	if ($modSettings['smtp_username'] != '' && $modSettings['smtp_password'] != '')
	{
		// EHLO could be understood to mean encrypted hello...
		if (!server_parse('EHLO ' . $modSettings['smtp_host'], $socket, '250'))
			return false;
		if (!server_parse('AUTH LOGIN', $socket, '334'))
			return false;
		// Send the username ans password, encoded.
		if (!server_parse(base64_encode($modSettings['smtp_username']), $socket, '334'))
			return false;
		if (!server_parse(base64_encode($modSettings['smtp_password']), $socket, '235'))
			return false;
	}
	else
	{
		// Just say "helo".
		if (!server_parse('HELO ' . $modSettings['smtp_host'], $socket, '250'))
			return false;
	}

	foreach ($mail_to_array as $mail_to)
	{
		// From, to, and then start the data...
		if (!server_parse('MAIL FROM: <' . $webmaster_email . '>', $socket, '250'))
			return false;
		if (!server_parse('RCPT TO: <' . $mail_to . '>', $socket, '250'))
			return false;
		if (!server_parse('DATA', $socket, '354'))
			return false;
		fputs($socket, 'Subject: ' . $subject . "\r\n");
		if (strlen($mail_to) > 0)
			fputs($socket, 'To: <' . $mail_to . ">\r\n");
		fputs($socket, $headers . "\r\n\r\n");
		fputs($socket, $message . "\r\n");

		// Send a ., or in other words "end of data".
		if (!server_parse('.', $socket, '250'))
			return false;
		// Reset the connection to send another email.
		if (!server_parse('RSET', $socket, '250'))
			return false;
	}
	fputs($socket, "QUIT\r\n");
	fclose($socket);

	return true;
}

// Parse a message to the SMTP server.
function server_parse($message, $socket, $response)
{
	global $txt;

	if ($message !== null)
		fputs($socket, $message . "\r\n");

	// No response yet.
	$server_response = '';

	while (substr($server_response, 3, 1) != ' ')
		if (!($server_response = fgets($socket, 256)))
		{
			log_error($txt['smtp_bad_response']);
			return false;
		}

	if (substr($server_response, 0, 3) != $response)
	{
		log_error($txt['smtp_error'] . $server_response);
		return false;
	}

	return true;
}

// Makes sure the calendar post is valid.
function calendarValidatePost()
{
	global $modSettings, $txt, $sourcedir;

	if (!isset($_POST['deleteevent']))
	{
		// No month?  No year?
		if (!isset($_POST['month']))
			fatal_lang_error('calendar7', false);
		if (!isset($_POST['year']))
			fatal_lang_error('calendar8', false);

		// Check the month and year...
		if ($_POST['month'] < 1 || $_POST['month'] > 12)
			fatal_lang_error('calendar1', false);
		if ($_POST['year'] < $modSettings['cal_minyear'] || $_POST['year'] > $modSettings['cal_maxyear'])
			fatal_lang_error('calendar2', false);
	}

	// Make sure they're allowed to post...
	isAllowedTo('calendar_post');

	if (isset($_POST['span']))
	{
		// Make sure it's turned on and not some fool trying to trick it.
		if ($modSettings['cal_allowspan'] != 1)
			fatal_lang_error('calendar55', false);
		if ($_POST['span'] < 1 || $_POST['span'] > $modSettings['cal_maxspan'])
			fatal_lang_error('calendar56', false);
	}

	// There is no need to validate the following values if we are just deleting the event.
	if (!isset($_POST['deleteevent']))
	{
		// No day?
		if (!isset($_POST['day']))
			fatal_lang_error('calendar14', false);
		if (!isset($_POST['evtitle']) && !isset($_POST['subject']))
			fatal_lang_error('calendar15', false);
		elseif (!isset($_POST['evtitle']))
			$_POST['evtitle'] = $_POST['subject'];

		// Bad day?
		if (!checkdate($_POST['month'], $_POST['day'], $_POST['year']))
			fatal_lang_error('calendar16', false);

		// No title?
		if (trim($_POST['evtitle']) == '')
			fatal_lang_error('calendar17', false);
		if (strlen($_POST['evtitle']) > 30)
			$_POST['evtitle'] = substr($_POST['evtitle'], 0, 30);
		$_POST['evtitle'] = str_replace(';', '', $_POST['evtitle']);
	}
}

// Prints a post box.  Used everywhere you post or send.
function theme_postbox($msg)
{
	global $txt, $modSettings, $db_prefix;
	global $context, $settings, $user_info;

	// Switch between default images and back... mostly in case you don't have an InstantMessage template, but do ahve a Post template.
	if (isset($settings['use_default_images']) && $settings['use_default_images'] == 'defaults' && isset($settings['default_template']))
	{
		$temp1 = $settings['theme_url'];
		$settings['theme_url'] = $settings['default_theme_url'];

		$temp2 = $settings['images_url'];
		$settings['images_url'] = $settings['default_images_url'];

		$temp3 = $settings['theme_dir'];
		$settings['theme_dir'] = $settings['default_theme_dir'];
	}

	// Load the Post template and language file.
	loadTemplate('Post');
	loadLanguage('Post');

	// Initialize smiley array...
	$context['smileys'] = array(
		'postform' => array(),
		'popup' => array(),
	);

	// Load smileys - don't bother to run a query if we're not using the database's ones anyhow.
	if (empty($modSettings['smiley_enable']) && $user_info['smiley_set'] != 'none')
		$context['smileys']['postform'][] = array(
			'smileys' => array(
				array('code' => ':)', 'filename' => 'smiley.gif', 'description' => $txt[287]),
				array('code' => ';)', 'filename' => 'wink.gif', 'description' => $txt[292]),
				array('code' => ':D', 'filename' => 'cheesy.gif', 'description' => $txt[289]),
				array('code' => ';D', 'filename' => 'grin.gif', 'description' => $txt[293]),
				array('code' => '>:(', 'filename' => 'angry.gif', 'description' => $txt[288]),
				array('code' => ':(', 'filename' => 'sad.gif', 'description' => $txt[291]),
				array('code' => ':o', 'filename' => 'shocked.gif', 'description' => $txt[294]),
				array('code' => '8)', 'filename' => 'cool.gif', 'description' => $txt[295]),
				array('code' => '???', 'filename' => 'huh.gif', 'description' => $txt[296]),
				array('code' => '::)', 'filename' => 'rolleyes.gif', 'description' => $txt[450]),
				array('code' => ':P', 'filename' => 'tongue.gif', 'description' => $txt[451]),
				array('code' => ':-[', 'filename' => 'embarassed.gif', 'description' => $txt[526]),
				array('code' => ':-X', 'filename' => 'lipsrsealed.gif', 'description' => $txt[527]),
				array('code' => ':-\\', 'filename' => 'undecided.gif', 'description' => $txt[528]),
				array('code' => ':-*', 'filename' => 'kiss.gif', 'description' => $txt[529]),
				array('code' => ':\'(', 'filename' => 'cry.gif', 'description' => $txt[530])
			),
			'last' => true,
		);
	elseif ($user_info['smiley_set'] != 'none')
	{
		$request = db_query("
			SELECT code, filename, description, smileyRow, hidden
			FROM {$db_prefix}smileys
			WHERE hidden IN (0, 2)
			ORDER BY smileyRow, smileyOrder", __FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($request))
			$context['smileys'][empty($row['hidden']) ? 'postform' : 'popup'][$row['smileyRow']]['smileys'][] = $row;
		mysql_free_result($request);
	}

	// Clean house... add slashes to the code for javascript.
	foreach (array_keys($context['smileys']) as $location)
	{
		foreach ($context['smileys'][$location] as $j => $row)
		{
			$n = count($context['smileys'][$location][$j]['smileys']);
			for ($i = 0; $i < $n; $i++)
			{
				$context['smileys'][$location][$j]['smileys'][$i]['code'] = addslashes($context['smileys'][$location][$j]['smileys'][$i]['code']);
				$context['smileys'][$location][$j]['smileys'][$i]['js_description'] = addslashes($context['smileys'][$location][$j]['smileys'][$i]['description']);
			}

			$context['smileys'][$location][$j]['smileys'][$n - 1]['last'] = true;
		}
		if (!empty($context['smileys'][$location]))
			$context['smileys'][$location][count($context['smileys'][$location]) - 1]['last'] = true;
	}
	$settings['smileys_url'] = $modSettings['smileys_url'] . '/' . $user_info['smiley_set'];

	// Allow for things to be overridden.
	if (!isset($context['post_box_columns']))
		$context['post_box_columns'] = 60;
	if (!isset($context['post_box_rows']))
		$context['post_box_rows'] = 12;
	if (!isset($context['post_box_name']))
		$context['post_box_name'] = 'message';
	if (!isset($context['post_form']))
		$context['post_form'] = 'postmodify';

	// Set a flag so the sub template knows what to do...
	$context['show_bbc'] = !empty($modSettings['enableBBC']) && !empty($settings['show_bbc']);

	// Generate a list of buttons that shouldn't be shown - this should be the fastest way to do this.
	if (!empty($modSettings['disabledBBC']))
	{
		$disabled_tags = explode(',', $modSettings['disabledBBC']);
		foreach ($disabled_tags as $tag)
			$context['disabled_tags'][trim($tag)] = true;
	}

	// Go!  Supa-sub-template-smash!
	template_postbox($msg);

	// Switch the URLs back... now we're back to whatever the main sub template is.  (like folder in InstantMessage.)
	if (isset($settings['use_default_images']) && $settings['use_default_images'] == 'defaults' && isset($settings['default_template']))
	{
		$settings['theme_url'] = $temp1;
		$settings['images_url'] = $temp2;
		$settings['theme_dir'] = $temp3;
	}
}

function SpellCheck()
{
	global $txt, $context;

	// A list of "words" we know about but pspell doesn't.
	$known_words = array('smf', 'php', 'mysql', 'www', 'http', 'smfisawesome', 'grandia', 'terranigma');

	loadTemplate('Post');
	loadLanguage('Post');

	// Okay, this looks funny, but it actually fixes a weird bug.
	ob_start();
	$old = error_reporting(0);

	// See, first, some windows machines don't load pspell properly on the first try.  Dumb, but this is a workaround.
	pspell_new('en');

	// Next, the dictionary in question may not exist.  So, we try it... but...
	$pspell_link = pspell_new($txt['lang_dictionary'], $txt['lang_spelling'], '', strtr($txt['lang_character_set'], array('iso-' => 'iso', 'ISO-' => 'iso')), PSPELL_FAST | PSPELL_RUN_TOGETHER);
	error_reporting($old);
	ob_end_clean();

	// Most people don't have anything but english installed... so we use english as a last resort.
	if (!$pspell_link)
		$pspell_link = pspell_new('en', '', '', '', PSPELL_FAST | PSPELL_RUN_TOGETHER);

	if (!isset($_POST['spellstring']) || !isset($_POST['spell_formname']) || !isset($_POST['spell_fieldname']) || !$pspell_link)
		die;

	// Can't have any \n's or \r's in javascript strings.
	$mystr = trim(str_replace(array("\r", "\n"), array('', '_|_'), stripslashes($_POST['spellstring'])));

	preg_match_all('/(?:<[^>]+>)|(?:\[[^ ][^\]]*\])|(?:&[^;\ ]+;)|(?<=^|[^[:alpha:]\'])([[:alpha:]\']+)/is', $mystr, $alphas, PREG_PATTERN_ORDER);

	// Do this after because the js doesn't count '\"' as two, but PHP does.
	$context['spell_js'] = '
		var txt = {"done": "' . $txt['spellcheck_done'] . '"};
		var mispstr = "' . str_replace(array('\\', '"', '</script>'), array('\\\\', '\\"', '<" + "/script>'), $mystr) . '";
		var misps = Array(';

	// This is some sanity checking: they should be chronological.
	$last_occurance = 0;

	$found_words = false;
	$code_block = false;
	for ($i = 0, $n = count($alphas[0]); $i < $n; $i++)
	{
		// Check if we're inside a code block...
		if (preg_match('~\[/?code\]~i', $alphas[0][$i]))
			$code_block = !$code_block;

		// If the word is an html tag, an entity, a bbc tag, inside [code], a known word, or spelled right...
		if (empty($alphas[1][$i]) || $code_block || in_array(strtolower($alphas[1][$i]), $known_words) || pspell_check($pspell_link, $alphas[1][$i]))
		{
			// Add on this word's length, and continue.
			$last_occurance += strlen($alphas[0][$i]);
			continue;
		}

		// Find the word, and move up the "last occurance" to here.
		$last_occurance = strpos($mystr, $alphas[0][$i], $last_occurance + 1);
		$found_words = true;

		// Add on the javascript for this misspelling.
		$context['spell_js'] .= '
			new misp("' . $alphas[1][$i] . '", ' . (int) $last_occurance . ', ' . ($last_occurance + strlen($alphas[1][$i]) - 1) . ', [';

		// If there are suggestions, add them in...
		$suggestions = pspell_suggest($pspell_link, $alphas[1][$i]);
		if (!empty($suggestions))
			$context['spell_js'] .= '"' . join('", "', $suggestions) . '"';

		$context['spell_js'] .= ']),';
	}

	// If words were found, take off the last comma.
	if ($found_words)
		$context['spell_js'] = substr($context['spell_js'], 0, -1);

	$context['spell_js'] .= '
		);';

	// And instruct the template system to just show the spellcheck sub template.
	$context['template_layers'] = array();
	$context['sub_template'] = 'spellcheck';
}

// Notify members that something has happened to a topic  they marked!
function sendNotifications($ID_TOPIC, $type)
{
	global $txt, $scripturl, $db_prefix, $language, $user_info;
	global $ID_MEMBER, $modSettings, $sourcedir;

	$notification_types = array(
		'reply' => array('subject' => 'notification_reply_subject', 'message' => 'notification_reply'),
		'sticky' => array('subject' => 'notification_sticky_subject', 'message' => 'notification_sticky'),
		'lock' => array('subject' => 'notification_lock_subject', 'message' => 'notification_lock'),
		'unlock' => array('subject' => 'notification_unlock_subject', 'message' => 'notification_unlock'),
		'remove' => array('subject' => 'notification_remove_subject', 'message' => 'notification_remove'),
		'move' => array('subject' => 'notification_move_subject', 'message' => 'notification_move'),
		'merge' => array('subject' => 'notification_merge_subject', 'message' => 'notification_merge'),
		'split' => array('subject' => 'notification_split_subject', 'message' => 'notification_split'),
	);
	$current_type = $notification_types[$type];

	// Can't do it if there's no topic.
	if (empty($ID_TOPIC))
		return;

	// Get the board and subject...
	$result = db_query("
		SELECT ID_BOARD, subject
		FROM {$db_prefix}messages
		WHERE ID_TOPIC = $ID_TOPIC
		ORDER BY ID_MSG
		LIMIT 1", __FILE__, __LINE__);
	list ($ID_BOARD, $subject) = mysql_fetch_row($result);
	mysql_free_result($result);

	// Censor...
	censorText($subject);
	$subject = un_htmlspecialchars($subject);

	// Find the members with notification on for this topic.
	$members = db_query("
		SELECT
			mem.ID_MEMBER, mem.emailAddress, mem.notifyOnce, mem.lngfile, ln.sent, mem.ID_GROUP,
			mem.additionalGroups, b.memberGroups, mem.ID_POST_GROUP
		FROM {$db_prefix}log_notify AS ln, {$db_prefix}members AS mem, {$db_prefix}topics AS t, {$db_prefix}boards AS b
		WHERE ln.ID_TOPIC = $ID_TOPIC
			AND t.ID_TOPIC = $ID_TOPIC
			AND b.ID_BOARD = $ID_BOARD
			AND mem.ID_MEMBER != $ID_MEMBER
			AND ln.ID_MEMBER = mem.ID_MEMBER
		GROUP BY mem.ID_MEMBER
		ORDER BY mem.lngfile", __FILE__, __LINE__);
	while ($row = mysql_fetch_assoc($members))
	{
		if ($row['ID_GROUP'] != 1)
		{
			$allowed = explode(',', $row['memberGroups']);
			$row['additionalGroups'] = explode(',', $row['additionalGroups']);
			$row['additionalGroups'][] = $row['ID_GROUP'];
			$row['additionalGroups'][] = $row['ID_POST_GROUP'];

			if (count(array_intersect($allowed, $row['additionalGroups'])) == 0)
				continue;
		}

		loadLanguage('Post', empty($row['lngfile']) || empty($modSettings['userLanguage']) ? $language : $row['lngfile'], false);

		$message = sprintf($txt[$current_type['message']], un_htmlspecialchars($user_info['name']));
		if ($type != 'remove')
			$message .=
				$scripturl . '?topic=' . $ID_TOPIC . '.new;topicseen#new' . "\n\n" .
				$txt['notifyUnsubscribe'] . ': ' . $scripturl . '?action=notify;topic=' . $ID_TOPIC . '.0';
		if (!empty($row['notifyOnce']) && $type == 'reply')
			$message .= "\n\n" . $txt['notifyXOnce2'];

		// Send only if once is off or it's on and it hasn't been sent.
		if ($type != 'reply' || empty($row['notifyOnce']) || empty($row['sent']))
		{
			sendmail($row['emailAddress'], sprintf($txt[$current_type['subject']], $subject),
				$message . "\n\n" .
				$txt[130]);
		}
	}
	mysql_free_result($members);

	// Sent!
	if ($type == 'reply')
		db_query("
			UPDATE {$db_prefix}log_notify
			SET sent = 1
			WHERE ID_TOPIC = $ID_TOPIC
				AND ID_MEMBER != $ID_MEMBER", __FILE__, __LINE__);
}

?>