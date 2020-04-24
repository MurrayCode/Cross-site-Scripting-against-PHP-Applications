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
if (document.frm.pass2.value != document.frm.pass3.value)
	alert ("New password did not matched !");
else
{
var t;
t=document.frm.pass.value;
if (t.length <6)
{
	alert ("Minimum Password Length 6");
	return;
}
else if (t.length >8)
{
	alert ("Maximum Password Length 8");
	return;
}
else
	flag=1;

t=document.frm.pass2.value;
if (t.length <6)
{
	alert ("Minimum Password Length 6");
	return;
}
else if (t.length >8)
{
	alert ("Maximum Password Length 8");
	return;
}
else
	flag=1;

t=document.frm.pass3.value;
if (t.length <6)
{
	alert ("Minimum Password Length 6");
	return;
}
else if (t.length >8)
{
	alert ("Maximum Password Length 8");
	return;
}
else
	flag=1;
}
if (flag == 1 )
	document.frm.submit();
}
</script>
</head>

<body>
<center>
<div id="container" >
  <table width="650" border="0" align="center" cellpadding="1" cellspacing="1">
<form method="post" action="<?php print $baseurl; ?>saved_account.php?qid=2" name="frm">
    <tr>
      <td colspan="2"><center class="style13">
          <em><strong> Want to change password ? </strong></em>
      </center></td>
    </tr>
    <tr>
      <td width="320"><span class="style13"><strong>Current Password </strong></span></td>
      <td width="323"><center>
          <input name="pass" type="password" id="pass" size="40" maxlength="8" title="Old password"/>
      </center></td>
    </tr>
    <tr>
      <td><span class="style13"><strong>New Password </strong></span></td>
      <td><center>
          <input name="pass2" type="password" id="pass2" size="40" maxlength="8" title="New Password [Min 6, Max 8]"/>
      </center></td>
    </tr>
    <tr>
      <td><span class="style13"><strong>New Password </strong></span></td>
      <td><center>
          <input name="pass3" type="password" id="pass3" size="40" maxlength="8" title="Retype New Password [Min 6, Max 8]"/>
      </center></td>
    </tr>
    <tr>
      <td colspan="2"><center>
          <input type="button" name="Submit5" value="Update" title="Update Password" onclick="fx()"/>
      </center></td>
    </tr>
</form>
  </table>
  </div>
</center>
</body>
</html>
