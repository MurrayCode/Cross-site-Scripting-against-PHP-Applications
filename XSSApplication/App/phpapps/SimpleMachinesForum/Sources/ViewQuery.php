<?php
/******************************************************************************
* ViewQuery.php                                                               *
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

/*	This file is concerned with viewing queries, and is used for debugging.
	It contains only one function:

	void ViewQuery()
		- toggles the session variable 'view_queries'.
		- views a list of queries and analyzes them.
		- requires the admin_forum permission.
		- is accessed via ?action=viewquery.
		- strings in this function have not been internationalized.
*/

// See the queries....
function ViewQuery()
{
	global $scripturl, $user_info, $settings, $context, $db_connection;

	// Don't allow except for administrators.
	isAllowedTo('admin_forum');

	// If we're just hiding/showing, do it now.
	if (isset($_REQUEST['sa']) && $_REQUEST['sa'] == 'hide')
	{
		$_SESSION['view_queries'] = $_SESSION['view_queries'] == 1 ? 0 : 1;

		redirectexit($_SESSION['old_url'], false);
	}

	$qqj = isset($_REQUEST['qq']) ? (int) $_REQUEST['qq'] : 0;

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>', $context['forum_name'], '</title>
		<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/style.css" />
		<style type="text/css">
			body, td, .normaltext
			{
				font-size: x-small;
			}
			.smalltext
			{
				font-size: xx-small;
			}
		</style>
	</head>
	<body>';

	foreach ($_SESSION['debug'] as $q => $qq)
	{
		// Fix the indentation....
		$qq['q'] = ltrim(str_replace("\r", '', $qq['q']), "\n");
		$query = explode("\n", $qq['q']);
		$min_indent = 0;
		foreach ($query as $line)
		{
			preg_match('/^(\t*)/', $line, $qqi);
			if (strlen($qqi[0]) < $min_indent || $min_indent == 0)
				$min_indent = strlen($qqi[0]);
		}
		foreach ($query as $l => $dummy)
			$query[$l] = substr($dummy, $min_indent);
		$qq['q'] = implode("\n", $query);

		$is_select_query = substr(trim($qq['q']), 0, 6) == 'SELECT';

		echo '
		<a name="qq' . $q . '"', $is_select_query ? ' href="' . $scripturl . '?action=viewquery;qq=' . ($q + 1) . '#qq' . $q . '"' : '', ' style="font-weight: bold; color: black; text-decoration: none;">
			' . nl2br(str_replace("\t", '&nbsp;&nbsp;&nbsp;', htmlspecialchars($qq['q']))) . '
		</a><br />
		in <i>' . $qq['f'] . '</i> line <i>' . $qq['l'] . '</i>, which took ' . $qq['t'] . ' seconds.<br />
		<br />';

		// Explain the query.
		if ($qqj == $q + 1 && $is_select_query)
		{
			$result = mysql_query("
				EXPLAIN " . $qq['q'], $db_connection);
			if ($result === false)
				die(mysql_error($db_connection));

			echo '
		<table border="1" rules="all" cellpadding="4" cellspacing="0" style="empty-cells: show; font-family: serif;">';

			$row = mysql_fetch_assoc($result);
			mysql_data_seek($result, 0);

			echo '
			<tr>
				<th>' . implode('</th>
				<th>', array_keys($row)) . '</th>
			</tr>';

			while ($row = mysql_fetch_assoc($result))
			{
				echo '
			<tr>
				<td>' . implode('</td>
				<td>', $row) . '</td>
			</tr>';
			}

			echo '
		</table>
		<br />';
		}
	}

	echo '
	</body>
</html>';

	obExit(false);
}

?>