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
.style13 {color: #333333; font-size: small; }

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
</style>
<script>
function fx()
{
var flag=0;
var t;
t=document.frm.code.value;
if (t.length <3)
{
	alert ("Minimum Code Length 3");
	return;
}
else if (t.length >4)
{
	alert ("Maximum Code Length 4");
	return;
}
else
	flag=1;

t=document.frm.code2.value;
if (t.length <3)
{
	alert ("Minimum Code Length 3");
	return;
}
else if (t.length >4)
{
	alert ("Maximum Code Length 4");
	return;
}
else
	flag=1;

if (flag == 1 )
	document.frm.submit();
else
	alert ("Recheck !");
}
</script>
</head>

<body>
<center>
<div id="container" >
  <table width="650" border="0" align="center" cellpadding="1" cellspacing="1">
<form name="frm" method="post" action="<?php print $baseurl; ?>saved_account.php?qid=3">
    <tr>
      <td colspan="2"><center class="style13">
          <em><strong> Want to change secret code ? </strong></em>
      </center></td>
    </tr>
    <tr>
      <td width="320"><span class="style13"><strong>Current Code </strong></span></td>
      <td width="323"><center>
          <input name="code" type="password" id="code" size="40" maxlength="4" title="Current Password Retrival Code [Min 3, Max 4]" />
      </center></td>
    </tr>
    <tr>
      <td><span class="style13"><strong>New Code </strong></span></td>
      <td><center>
          <input name="code2" type="password" id="code2" size="40" maxlength="4" title="New Password Retrival Code [Min 3, Max 4]"/>
      </center></td>
    </tr>
    
    <tr>
      <td colspan="2"><center>
          <input type="button" name="Submit" value="Update" title="Update Security Code" onclick="fx()"/>
      </center></td>
    </tr>
</form>
  </table>
  </div>
</center>
</body>
</html>
