<?php
session_start();
error_reporting(0);
require("config.php");

if (isset($_SESSION["name"]) && isset($_SESSION["msg"]) && isset($_SESSION["id"]))
{
$name = $_SESSION["name"];
$msg = $_SESSION["msg"];
$id = $_SESSION["id"];
include_once("connect.php");
$query="select id,name from login where id = '$id' and name = '$name'";
$rs=mysql_query($query,$conn);
$flag=mysql_num_rows($rs);

if($flag)
{
 mysql_close($conn);
	if (!headers_sent())
	{
		header("Location:".$baseurl."forum.php");
		exit;
	}
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php print $title; ?> Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="Author" content="Web-Shine (webshine.in@gmail.com)"/>
<META NAME="Generator" CONTENT="Web-Shine"/>
<meta name="KeyWords" content="Web-Shine Blog, Web-Shine forum, Blog in kolkata"/>
<meta name="Description" content="Blog in kolkata, forum in kolkata, Ultra Light Blog"/>
<meta name="country" content="India"/>
<meta name="organization-Email" content="webshine.in@gmail.com"/>
<meta name="copyright" content="copyright 2010 - Web-Shine"/>
<meta name="coverage" content="Worldwide"/>
<meta name="title" content="Web-Shine Blog"/>
<meta name="identifier" content="http://www.web-shine.in"/>
<meta name="language" content="English"/>
<meta name="robots" content="follow"/>
<meta name="googlebot" content="index, follow"/>

<style type="text/css">
<!--
body {
	background-color: #66CCFF;
}
.style1 {color: #333333}
.style2 {
	color: #000000;
	font-weight: bold;
}
.style3 {color: #666666}
.style4 {
	color: #CC0000;
	font-weight: bold;
}
-->
</style>
<script>
function fx()
{
	window.location="<?php print $baseurl; ?>forgot_pass.php";
}

function check()
{
vid = document.frm.username.value;
vp = document.frm.password.value;
if (vid.length <6 || vid.length>10)
{
	alert("Invalid Login ID.");
	return;
}
else if (vp.length <6 || vp.length>8)
{
	alert("Incorrect Login Password.");
	return;
}
else
	document.frm.submit();
}
</script>
</head>

<body>
<form name="frm" method="POST" action="<?php print $baseurl; ?>login2.php">
<table width="976" border="0" align="center" cellpadding="1" cellspacing="1" bordercolor="#FFFFFF">
  <tr>
    <td width="733" bordercolor="#F2F2F2"><center>
      <img src="blog.jpg" alt="Enjoy Ultra Light Blog" width="188" height="250" />
    </center>
    </td>
    <td width="228" bordercolor="#CCCCCC"><h2 align="center"><strong>Sign In</strong></h2>
      <p align="left">
      <label for="username"><strong>User Name</strong></label>
      &nbsp;<br />
      <input name="username" type="text" id="username" size="10" maxlength="10" />
</p>
    <p align="left"><strong>Password</strong><br />
        <input name="password" type="password" id="password" size="10" maxlength="8" />
        </p>
    <p align="left">
      <input type="button" title="Signin to Web-Shine forum" onclick="check()" name="signin" value="Sign In" />
    </p></td>
  </tr>
  <tr bordercolor="#F2F2F2">
    <td height="45" colspan="2">
	  <div align="right">
	    <p>
	      <input type="button" name="Button" value="Want to start   ???" title="Sign Up" onclick="window.location='<?php print $baseurl;?>register.htm'"/> 
	      <br />
	      <input type="button" name="Submit2" value="Forgot Password ?" title="Retrieve forgotten password by giving id and security code" onclick="window.location='<?php print $baseurl;?>forgot_pass.php'" />
	    </p>
	    <h5 align="left" class="style4">
		<?php
		if (isset($_SESSION["strmsg"]))
			print $_SESSION["strmsg"];
		$_SESSION["strmsg"]="";
		?>
		</h5>
	  </div></td>
  </tr>
</table>
</form>

<h6 align="right"><span class="style1"><strong>Copyright &copy; 2010</strong></span><br />
<span class="style3">Powered</span><span class="style1"> <span class="style3"> by Ultra Light Blog/Forum [Beta ] (Licensed under Sourceforge). </span><br />
  All rights reserved.
</span></h6>
</body>
</html>