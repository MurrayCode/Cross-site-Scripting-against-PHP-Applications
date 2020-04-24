<?php

include 'framework/i18n/messages_en.php';
/**
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
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
	"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>Brim - Sign up</title>
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
  	<h1>Signup</h1>
  	<p>
  		After signup a mail will be sent to the administrator
		of the application!
  	</p>
  	<form method="post" action="signupAction.php">
  	<table>
  	<tr>
  		<td>Username:</td>
  		<td><input type="text" name="loginName"
  			value="" /></td>
  	</tr>
  	<tr>
  		<td>Password:</td>
  		<td><input type="password" name="password"
  			value="" /></td>
  	</tr>
  	<tr>
  		<td>Confirm:</td>
  		<td><input type="password" name="password2"/></td>
  	</tr>
  	<tr>
  		<td>Full name:</td>
  		<td><input type="text" name="name"></td>
  	</tr>
  	<tr>
  		<td>Email:</td>
  		<td><input type="text" name="email"/></td>
  	</tr>
  	<tr>
  		<td>&nbsp;</td>
  		<td><input type="submit" value="Submit" name="submit" /></td>
  	</tr>
   	</table>
  	</form>
	</div>
	<div id="copyright">
		Brim <?php echo ($dictionary['version']) ?>.
		<?php echo ($dictionary['msg_copyright']); ?>
	</div>
</div>
</body>
</html>
