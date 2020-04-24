<?php
session_start();
error_reporting (0);
include("config.php");
if(!isset($_SESSION["id"]))
{
	header("Location:".$baseurl."getout.htm");
}
?>
<style type="text/css">
<!--
body {
	background-color: #3A7CD0;
}
-->
</style>
<?php
$tempid=$_GET["msgid"];
$id=$_SESSION["id"];

if ($tempid != "")
{
	include_once("connect.php");
	$query="delete from msg where dest='$id' and idx='$tempid'";
	$rs=mysql_query($query,$conn);
	print "Deleted.<br>";
}
?>
<html><head><script>
function fx()
{
 window.location='<?php print $baseurl; ?>inbox.php';
}
</script></head>
<body onLoad="fx()">Redirecting ...</body>
</html>