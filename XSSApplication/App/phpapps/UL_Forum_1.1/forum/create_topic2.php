<?php
session_start();
error_reporting(0);
include("config.php");
if(!isset($_SESSION["id"]))
{
	header("Location:".$baseurl."getout.htm");
}
$id=$_SESSION["id"];
$key=$_SESSION["hash"];
$name=trim($_POST['name']);
$msg=trim($_POST['scrap']);
$key2=$_POST['code'];
$cat=$_POST['cat'];
$err="";

if (strlen($name)<4)
	$err = "Topic name should be atleast 4 letters.<br>";
if (strlen($name)>60)
	$err = $err + "Topic name should be less than 60 letters.<br>";
if (strlen($msg)<6)
	$err = $err + "Minimum view length should be 6 letters.<br>";
	
if($id == "")
	$err = $err + "Please Login.<br>";
if($name == "")
	$err = "No topic name ?<br>";
if($msg == "")
	$err = "No view ?<br>";

if (strcasecmp ($key,$key2) != 0)
	$err = "Failed to pass CAPTCHA TEST.<br>Are you a machine ?";

if($err == "")
{
include_once("connect.php");

$timestamp = date("Y-m-d H:i:s");
$query="INSERT INTO topic2 (`name`,`user`,`cat`,`cr_time`) VALUES ('$name','$id','$cat','$timestamp')";
$rs=mysql_query($query,$conn);
$flag=mysql_affected_rows();

$query="select tid from topic2 where user='$id' and cat='$cat' and cr_time='$timestamp'";
$rs=mysql_query($query,$conn);
while($row=mysql_fetch_array($rs))
{
	$tid=$row[0];
}

if($flag)
{
	$query="insert into topic(msg,tid,user) values('$msg',$tid,'$id')";
	$rs=mysql_query($query,$conn);

	if (!headers_sent())
	{
		header("Location:".$baseurl."topic.php");
		exit;
	}
}
else
{
	$er="Failed to create topic !";
	$er = "<font color='red'><b>Error Log:<br></b><font color='orange'>" . $er;
	echo $er;
}
mysql_close($conn);
}
else
	print $err;
?>
<style type="text/css">
<!--
body {
	background-color: #3A7CD0;
}
-->
</style>