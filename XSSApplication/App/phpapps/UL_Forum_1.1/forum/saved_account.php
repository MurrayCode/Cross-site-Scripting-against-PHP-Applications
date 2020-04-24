<?php
session_start();
error_reporting(0);
include("config.php");
$id=$_SESSION["id"];
if(!isset($_SESSION["id"]))
{
	header("Location:".$baseurl."getout.htm");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Profile Saved</title>
<style type="text/css">
<!--
body {
	background-color: #3A7CD0;
}
-->
</style></head>

<body>
<center>
<?php
$qid=$_GET["qid"];
include_once("connect.php");

if ($qid == 1)
{
	$newname=$_POST['name'];
	$newmsg=$_POST['msg'];	

	$query="update login set name='$newname' where id='$id'";
	$rs=mysql_query($query,$conn);

	$query="update login set msg='$newmsg' where id='$id'";
	$rs=mysql_query($query,$conn);
	mysql_close($conn);
	
	$_SESSION["name"]=$newname;
	$_SESSION["msg"]=$newmsg;
	
	print "<h1><strong>S</strong>ettings<strong> S</strong>aved <strong>.</strong></h1>";
}

if ($qid == 2)
{
$oldpass=$_POST['pass'];
$newpass=$_POST['pass2'];

$query="update login set pass='$newpass' where id='$id' and pass='$oldpass'";
$rs=mysql_query($query,$conn);

$query="select name from login where pass='$newpass' and id='$id'";
$rs=mysql_query($query,$conn);
$flag=mysql_num_rows($rs);
mysql_close($conn);

if($flag)
{
 print "<h1><strong>S</strong>ettings<strong> S</strong>aved <strong>.</strong></h1>";
}
else
{
	print "<h1><strong>U</strong>pdate<strong> F</strong>iled <strong>.</strong></h1></br>";
	print "Have you inserted correct password ?";
}
}

if ($qid == 3)
{
$oldpass=$_POST['code'];
$newpass=$_POST['code2'];

$query="update login set pass2 = '$newpass' where id = '$id' and pass2 = '$oldpass'";
$rs=mysql_query($query,$conn);

$query="select name from login where pass2='$newpass' and id='$id'";
$rs=mysql_query($query,$conn);

$flag=mysql_num_rows($rs);
mysql_close($conn);

if($flag)
{
 print "<h1><strong>S</strong>ettings<strong> S</strong>aved <strong>.</strong></h1>";
}
else
{
 print "<h1><strong>U</strong>pdate<strong> F</strong>iled <strong>.</strong></h1>";
 print "Have you inserted correct code ?";
}
}
?>  
</center>
</body>
</html>
