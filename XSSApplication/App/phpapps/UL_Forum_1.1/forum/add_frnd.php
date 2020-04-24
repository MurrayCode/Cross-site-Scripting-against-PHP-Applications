<?php
session_start();
include("config.php");
?>
<style type="text/css">
<!--
body {
	background-color: #3A7CD0;
}
-->
</style>
<?php
error_reporting (0);
if(!isset($_SESSION["id"]))
{
	header("Location:".$baseurl."getout.htm");
}
else
{
$id=$_SESSION["id"];
$frndid=$_GET["addid"];
include_once("connect.php");

if ($frndid != "" && $frndid != $id)
{
$query="insert into frnd values('$id','$frndid')";
$rs=mysql_query($query,$conn);
mysql_close($conn);
print "Bond created.";
}
mysql_close($conn);

print "<html><head><script>";
print "function fx()";
print "{ window.location='".$baseurl."/friend_list.php'; }";
print '</script></head><body onload="fx()">Redirecting ...</body></html>';
}
?>