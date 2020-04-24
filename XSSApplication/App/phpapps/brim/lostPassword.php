<?php

//
// If you wish another language on this screen, change the following
// line to include the appropriate dictionaries (and optionally translate them ;-)
//
include 'framework/i18n/messages_en.php';
session_start();

/**
 * The lost password recovery screen
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
 ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
        "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>Brim - Lost password recovery</title>
	<script type="text/javascript" src="ext/jQuery/jquery.js"></script>
    <script type="text/javascript" src="ext/jQuery/jq-corner.js"></script>
    <script type="text/javascript">
        window.onload=function()
        {
            $("div#content").corner("20px");
        }
    </script>
	<style type="text/css" media="screen"
		>@import "framework/view/css/login.css";</style>
</head>
<body>
<div id="content">
	
	<div id="content2">
		<img src="framework/view/pics/login/logo.gif"
			alt="[brim-project]" width="571" height="76"><br />
			<?php
				if (isset ($_GET['message']))
				{
					$messageId = $_GET['message'];
					$message = $messages[$messageId];
					if (isset ($message))
					{
						echo ("<h1>".$message."</h1>");
					}
					else
					{
						echo ('<h1>'.$dictionary['msg_unknownError'].'</h1>');
					}
				}
				else
				{
					echo ("<h1>".$dictionary['msg_provideEmailAndPassword']."</h1>");
				}
			?>
			<form method="POST" action="lostPasswordAction.php">
			<table>
				<tr>
					<td><?php echo $dictionary['msg_loginName']; ?>:</td>
					<td><input type="text" name="user"></td>
				</tr>
				<tr>
					<td><?php echo $dictionary['msg_emailAddress']; ?>:</td>
					<td><input type="text" name="email"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input type="submit" value="Submit" name="submit" />
					</td>
				</tr>
			</table>
			</form>
			</div>
			<!--
				The GPL license states that copyright information
				may not be removed. Please be kind and
				provide the copyright notice (you can of course modify it so it fits
				in your layout...)
			-->
			<div id="copyright">
				Brim <?php echo ($dictionary['version']) ?>.
				<?php echo ($dictionary['msg_copyright']); ?>
			</div>
</div>
</body>
</html>
