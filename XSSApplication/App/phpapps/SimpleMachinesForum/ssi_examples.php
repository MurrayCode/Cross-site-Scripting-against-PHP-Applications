<?php

require(dirname(__FILE__) . '/SSI.php');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title> << :: SMF SSI.php 1.0 :: >> </title><?php

	echo '
		<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/style.css" />
		<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/script.js"></script>';

	if ($context['browser']['needs_size_fix'])
		echo '
		<style type="text/css"><!--
			@import(', $settings['default_theme_url'], '/fonts-compat.css);
		--></style>';

?>
	</head>
	<body>
			<h1>SMF SSI.php Functions</h1>
			Current Version 1.0<br />
			<br />
			This file is used to demonstrate the capabilities of SSI.php using PHP include functions.<br />
			The examples show the include tag, then the results of it. Examples are separated by horizontal rules.<br />

		<hr />

			<br />
			To use SSI.php in your page add at the very top of your page before the &lt;html&gt; tag on line 1:<br />
			<div style="font-family: monospace;">
				&lt;?php require(&quot;<?php echo addslashes(realpath($boarddir . '/SSI.php')); ?>&quot;); ?&gt;
			</div>
			<br />

		<hr />

			<h3>Recent Topics Function: &lt;?php ssi_recentTopics(); ?&gt;</h3>
			<?php ssi_recentTopics(); ?>

		<hr />

			<h3>Recent Posts Function: &lt;?php ssi_recentPosts(); ?&gt;</h3>
			<?php ssi_recentPosts(); ?>

		<hr />

			<h3>Recent Poll Function: &lt;?php ssi_recentPoll(); ?&gt;</h3>
			<?php ssi_recentPoll(); ?>

		<hr />

			<h3>Top Boards Function: &lt;?php ssi_topBoards(); ?&gt;</h3>
			<?php ssi_topBoards(); ?>

		<hr />

			<h3>Top Topics by View Function: &lt;?php ssi_topTopicsViews(); ?&gt;</h3>
			<?php ssi_topTopicsViews(); ?>

		<hr />

			<h3>Top Topics by Replies Function: &lt;?php ssi_topTopicsReplies(); ?&gt;</h3>
			<?php ssi_topTopicsReplies(); ?>

		<hr />

			<h3>Top Poll Function: &lt;?php ssi_topPoll(); ?&gt;</h3>
			<?php ssi_topPoll(); ?>

		<hr />

			<h3>Top Poster Function: &lt;?php ssi_topPoster(); ?&gt;</h3>
			<?php ssi_topPoster(); ?>

		<hr />

			<h3>Topic's Poll Function: &lt;?php ssi_showPoll($topic); ?&gt;</h3>
			<?php ssi_showPoll(); ?>

		<hr />

			<h3>Latest Member Function: &lt;?php ssi_latestMember(); ?&gt;</h3>
			<?php ssi_latestMember(); ?>

		<hr />

			<h3>Board Stats: &lt;?php ssi_boardStats(); ?&gt;</h3>
			<?php ssi_boardStats(); ?>

		<hr />

			<h3>Who's Online Function: &lt;?php ssi_whosOnline(); ?&gt;</h3>
			<?php ssi_whosOnline(); ?>

		<hr />

			<h3>Log Online Presence + Who's Online Function: &lt;?php ssi_logOnline(); ?&gt;</h3>
			<?php ssi_logOnline(); ?>

		<hr />

			<h3>Welcome Function: &lt;?php ssi_welcome(); ?&gt;</h3>
			<?php ssi_welcome(); ?>

		<hr />

			<h3>News Function: &lt;?php ssi_news(); ?&gt;</h3>
			<?php ssi_news(); ?>

		<hr />

			<h3>Board News Function: &lt;?php ssi_boardNews(); ?&gt;</h3>
			<?php ssi_boardNews(); ?>

		<hr />

			<h3>Menubar Function: &lt;?php ssi_menubar(); ?&gt;</h3>
			<?php ssi_menubar(); ?>

		<hr />

			<h3>Quick Search Function: &lt;?php ssi_quickSearch(); ?&gt;</h3>
			<?php ssi_quickSearch(); ?>

		<hr />

			<h3>Login Function: &lt;?php ssi_login(); ?&gt;</h3>
			<?php ssi_login(); ?>

		<hr />

			<h3>Log Out Function: &lt;?php ssi_logout(); ?&gt;</h3>
			<?php ssi_logout(); ?>

		<hr />

			<h3>Today's Birthdays Function: &lt;?php ssi_todaysBirthdays(); ?&gt;</h3>
			<?php ssi_todaysBirthdays(); ?>

		<hr />

			<h3>Today's Holidays Function: &lt;?php ssi_todaysHolidays(); ?&gt;</h3>
			<?php ssi_todaysHolidays(); ?>

		<hr />

			<h3>Today's Events Function: &lt;?php ssi_todaysEvents(); ?&gt;</h3>
			<?php ssi_todaysEvents(); ?>

		<hr />

			<h3>Today's Calendar Function: &lt;?php ssi_todaysCalendar(); ?&gt;</h3>
			<?php ssi_todaysCalendar(); ?>

		<hr />

			<h3>Recent Calendar Events Function: &lt;?php ssi_recentEvents(); ?&gt;</h3>
			<?php ssi_recentEvents(); ?>

		<hr />

			<h3>Some notes on usage</h3>
			All the functions have an output method parameter.  This can either be &quot;echo&quot; (the default) or &quot;array&quot;.<br />
			If it is &quot;echo&quot;, the function will act normally - otherwise, it will return an array containing information about the requested task.<br />
			For example, it might return a list of topics for ssi_recentTopics.<br />
			<br />
			<span onclick="if (getInnerHTML(this).indexOf('Bird') == -1) setInnerHTML(this, getInnerHTML(this) + '<br /><img src=&quot;http://www.simplemachines.org/images/chocobo.jpg&quot; title=&quot;Bird-san&quot; alt=&quot;Chocobo!&quot; />'); return false;">This functionality can be used to allow you to present the information in any way you wish.</span>

		<hr />

		<br />
		<br />
		<span style="color: #CCCCCC; font-size: smaller;">
			<?php
				$then = explode(' ', $time_start);
				$now = explode(' ', microtime());
				echo 'This page took ', round($now[0] + $now[1] - $then[0] - $then[1], 4), ' seconds to load.<br />';
			?>
			*ssi_examples.php last modified on <?php echo date('m/j/y', filemtime(__FILE__)); ?>
		</span>
	</body>
</html>