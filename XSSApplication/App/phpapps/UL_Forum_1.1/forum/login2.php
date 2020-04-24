<?php
session_start();
error_reporting(0);
$id=$_POST["username"];
$pwd=$_POST["password"];

include_once("config.php");
include("connect.php");
$query="select * from login where id='$id' and pass='$pwd'";
if (isset($id) && isset($pwd))
{
	$rs=mysql_query($query,$conn);
	$flag=mysql_num_rows($rs);

	if ($flag>0)
	{
	while($row=mysql_fetch_array($rs))
	{
		$_SESSION["name"]=$row["name"];
		$_SESSION["msg"]=$row["msg"];
	}
		$_SESSION["id"]=$id;
}

		if (!headers_sent())
{
		header("Location:".$baseurl."forum.php");
		exit;
	}
	mysql_close($conn);
}
else
{
	$_SESSION["strmsg"]="Incorrect ID or Password.";
	$_SESSION["strmsg"]=$pass;
	   	if (!headers_sent())
	{
		header("Location:".$baseurl."login.php");
		exit;
	}
}

if ($id == "" && $pass == "")
{
	$_SESSION["strmsg"]="Invalid ID or Password.";
	if (!headers_sent())
{
    header("Location:".$baseurl."login.php");
    exit;
}
}
print "query -> ".$query;
print "<br>".$_SESSION["name"]." - ".$_SESSION["msg"];
?>
<html>
<style type="text/css">
<!--
body {
	background-color: #66CCFF;
}
-->
</style>
</html>