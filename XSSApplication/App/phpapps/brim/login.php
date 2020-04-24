<?php

require_once 'framework/model/AdminServices.php';
require_once 'framework/util/BrowserUtils.php';
//
// If you wish another language on your login screen, change the following two
// lines to include the appropriate dictionaries (and optionally translate them ;-)
//
include 'framework/i18n/messages_en.php';
include 'framework/i18n/dictionary_en.php';

/**
 * The login screen
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
$adminServices = new AdminServices ();
$browserUtils = new BrowserUtils ();

$allowAnonymous = $adminServices->getAdminConfig ('allow_account_creation');
$allowAnonymousSignon = false;
if (!is_bool ($allowAnonymous))
{
	if ($allowAnonymous == 1)
	{
		$allowAnonymousSignon = TRUE;
	}
}
else
{
	$allowAnonymousSignon = $allowAnonymous;
}
if ($browserUtils->browserIsPDA ())
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
        "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>Brim - login</title>
	<style type="text/css" media="screen"
		>@import "framework/view/css/loginpda.css";</style>
</head>
<body>
<div id="content">
	<h1>
	<?php
   		if (isset ($_GET['message']))
   		{
   			$messageId = $_GET['message'];
   			$message = $dictionary[$messageId];
			if (isset ($message))
			{
				echo ($message);
			}
			else
			{
				echo ($dictionary['msg_unknownError']);
			}
		}
		else
		{
			echo ($dictionary['msg_provideUsernameAndPassword']);
		}
		?>
	</h1>
	<form action="loginAction.php" method="post">
		<table class="login">
		<?php
			if ($allowAnonymousSignon)
			{
		?>
			<tr>
				<td>
					<?php echo ($dictionary['msg_name']); ?>:
				</td>
			</tr>
			<tr>
	   			<td> <input type="text" name="username" tabindex="1" id="username"
				/> </td>
			</tr>
			<tr>
				<td> <input type="Checkbox" name="signUp" /> </td>
			</tr>
			<tr>
				<td>
					<?php echo ($dictionary['msg_signUp']); ?>:
				</td>
			</tr>
			<tr>
				<td>
					<?php echo ($dictionary['msg_password']); ?>:
				</td>
			</tr>
			<tr>
				<td> <input type="password" name="password" tabindex="2"
				/> </td>
			</tr>
			<tr>
				<td> <input type="Checkbox" name="rememberMe" /> </td>
			</tr>
			<tr>
				<td>
					<?php echo ($dictionary['msg_rememberMe']); ?>:
				</td>
			</tr>
			<tr>
				<td> &nbsp; </td>
			</tr>
			<tr>
				<td colspan="2"> <input type="submit" 
					value="<?php echo $dictionary['msg_submit'] ?>" 
					name="submit" /> </td>
			</tr>
			<tr>
				<td> <a href="lostPassword.php"
					><?php echo ($dictionary['msg_lostPassword']); ?></a> </td>
			</tr>
		<?php
			} else {
		?>
			<tr>
				<td>
					<?php echo ($dictionary['msg_name']); ?>:
					<input type="text" id="username" name="username" tabindex="1" /> </td>
			</tr>
			<tr>
				<td>
					<?php echo ($dictionary['msg_password']); ?>:
					<input type="password" name="password" tabindex="2" /> </td>
			</tr>
			<tr>
				<td>
					<?php echo ($dictionary['msg_rememberMe']); ?>:
					<input type="Checkbox" name="rememberMe" />
					<input type="submit" value="Submit" name="submit" />
				</td>
			</tr>
			<tr>
				<td>
					<a href="lostPassword.php"
						><?php echo ($dictionary['msg_lostPassword']); ?></a>
				</td>
			</tr>
		<?php
			}
		?>
		</table>
	</form>
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
<script type="text/javascript">
	document.getElementById ("username").focus();
</script>
</body>
</html>
<?php
}
else
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
        "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>Brim - login</title>
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
	<img src="framework/view/pics/login/logo.gif" 
		alt="[brim-project]" width="571" height="76"><br />
	<h1>
	<?php
   		if (isset ($_GET['message']))
   		{
   			$messageId = $_GET['message'];
   			$message = $dictionary[$messageId];
			if (isset ($message))
			{
				echo ($message);
			}
			else
			{
				echo ($dictionary['msg_unknownError']);
			}
		}
		else
		{
			echo ($dictionary['msg_provideUsernameAndPassword']);
		}
		?>
	</h1>
	<!--
		Welcome to the demo-site of brim. 
		You can login using username 'test' and password 'test'.
	-->
	<form action="loginAction.php" method="post">
		<table class="login">
		<?php
			if ($allowAnonymousSignon)
			{
		?>
			<tr>
				<td>
					<?php echo ($dictionary['msg_name']); ?>:
				</td>
	   			<td> <input type="text" name="username" tabindex="1" id="username"
				/> </td>
				<td> <input type="Checkbox" name="signUp" /> </td>
				<td>
					<?php echo ($dictionary['msg_signUp']); ?>:
				</td>
			</tr>
			<tr>
				<td>
					<?php echo ($dictionary['msg_password']); ?>:
				</td>
				<td> <input type="password" name="password" tabindex="2"
				/> </td>
				<td> <input type="Checkbox" name="rememberMe" /> </td>
				<td>
					<?php echo ($dictionary['msg_rememberMe']); ?>:
				</td>
			</tr>
			<tr>
				<td> &nbsp; </td>
				<td colspan="2"> <input type="submit" 
					value="<?php echo $dictionary['msg_submit'] ?>" 
					name="submit" /> </td>
				<td> <a href="lostPassword.php"
					><?php echo ($dictionary['msg_lostPassword']); ?></a> </td>
			</tr>
		<?php
			} else {
		?>
			<tr>
				<td>
					<?php echo ($dictionary['msg_name']); ?>:
					<input type="text" id="username" name="username" tabindex="1" /> </td>
				<td>
					<?php echo ($dictionary['msg_password']); ?>:
					<input type="password" name="password" tabindex="2" /> </td>
			</tr>
			<tr>
				<td>
					<?php echo ($dictionary['msg_rememberMe']); ?>:
					<input type="Checkbox" name="rememberMe" />
					<input type="submit" value="Submit" name="submit" />
				</td>
				<td>
					<a href="lostPassword.php"
						><?php echo ($dictionary['msg_lostPassword']); ?></a>
				</td>
			</tr>
		<?php
			}
		?>
		</table>
	</form>
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
<script type="text/javascript">
	document.getElementById ("username").focus();
</script>
</body>
</html>
<?php } ?>
