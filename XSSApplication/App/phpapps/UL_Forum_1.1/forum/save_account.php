<?php
session_start();
error_reporting (0); //Hide all error
include("config.php");
if(!isset($_SESSION["id"]))
{
	header("Location:".$baseurl."getout.htm");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Profile Settings</title>
<style type="text/css">
<!--
body {
	background-color: #3A7CD0;
}
.style12 {color: #333333; font-weight: bold; font-size: small; }
#container
{
width:650px;
margin:0 auto;
padding:3px 0;
text-align:left;
position:relative;
-moz-border-radius: 15px;
-webkit-border-radius: 6px;
background-color:#6BACE6;
}
-->
</style></head>

<body>
<div id="container" >
<table width="650" border="0" align="center" cellpadding="1" cellspacing="1">
<form id="form1" name="form1" enctype="multipart/form-data" method="post" action="<?php print $baseurl; ?>saved_account.php?qid=1">
  <tr>
    <td width="320"><span class="style12">Name</span></td>
    <td width="323"><center>
      <input name="name" type="text" id="name" size="40" maxlength="20" title="Profile Name [Max 20]" value="<?php print $_SESSION["name"]; ?>"/>
    </center></td>
  </tr>
  <tr>
    <td><span class="style12">Message</span></td>
    <td><center>
      <input name="msg" type="text" id="msg" size="40" maxlength="160" title="Profile Message [Max 160]" value="<?php print $_SESSION["msg"]; ?>"/>
    </center></td>
  </tr>
  <tr>
    <td colspan="2"><center>
      <input type="submit" name="Submit4" value="Update" title="Update Profile" />
    </center></td>
  </tr>
</form>
</table>
</div>
</body>
</html>
