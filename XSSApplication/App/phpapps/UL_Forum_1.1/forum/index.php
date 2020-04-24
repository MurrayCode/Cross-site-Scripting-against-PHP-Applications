<?php
include("config.php");
session_start();
error_reporting (0);
if ($_SESSION["name"] != "" && $_SESSION["msg"] != "" && $_SESSION["id"] != "")
{
	if (!headers_sent())
	{
		header("Location:".$baseurl."/forum.php");
		exit;
	}
}
else
{
	if (!headers_sent())
	{
		header("Location:".$baseurl."login.php");
		exit;
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="Author" content="Web-Shine (webshine.in@gmail.com)"/>
<META NAME="Generator" CONTENT="Web-Shine"/>
<meta name="KeyWords" content="Web-Shine Blog, Web-Shine forum, Blog in kolkata"/>
<meta name="Description" content="Blog in kolkata, forum in kolkata"/>
<meta name="country" content="India"/>
<meta name="organization-Email" content="webshine.in@gmail.com"/>
<meta name="copyright" content="copyright 2010 - Web-Shine"/>
<meta name="coverage" content="Worldwide"/>
<meta name="title" content="Web-Shine Blog"/>
<meta name="identifier" content="http://www.web-shine.in"/>
<meta name="language" content="English"/>
<meta name="robots" content="follow"/>
<meta name="googlebot" content="index, follow"/>
<title>Welcome to <?php print $title; ?></title>
</head>
<body>
</body>
</html>