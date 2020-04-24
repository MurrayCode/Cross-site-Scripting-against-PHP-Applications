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
<title><?php print $title; ?> Search</title>
<style type="text/css">
<!--
body {
	background-color: #3A7CD0;
}
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
<script language="javascript">
function fx()
{
var v=document.f1.pname.value;
if (v.length <3 || v.length>20)
	alert ("Insert 3-20 letters.");
else
{
var path = "<?php print $baseurl; ?>search_result.php?proid="+document.f1.pname.value;
window.location = path;
}
}

function fx2()
{
var v=document.f1.tname.value;
if (v.length <3 || v.length>20)
	alert ("Insert 3-20 letters.");
else
{
var path = "<?php print $baseurl; ?>search_result.php?subid="+document.f1.tname.value;
window.location = path;
}
}
</script>
</head>

<body>
<form name="f1">
<div id="container" >
<table width="650" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="320">Search with Topic Name </td>
    <td width="323"><input type="text" name="tname" size="20" maxlength="20" title="Topic Name"/></td>
    <td width="323"><input type="button" name="Submit" value="Submit" onclick="fx2()" title="Search with given Topic Name"/></td>
  </tr>
  <tr>
    <td>Search with Profile Name </td>
    <td><input type="text" name="pname"  size="20" maxlength="20" title="Profile Name"/></td>
    <td><input type="button" name="Submit2" value="Submit" onclick="fx()" title="Search with given Profile Name"/></td>
  </tr>
  <tr>
    <td colspan="3"><center>
      <input type="reset" name="Submit3" value="Reset" title="Clear all"/>
    </center></td>
  </tr>
</table>
</div>
</form>
</body>
</html>
