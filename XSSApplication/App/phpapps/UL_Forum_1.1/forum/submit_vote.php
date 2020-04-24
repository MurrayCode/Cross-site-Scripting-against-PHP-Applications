<?php
session_start();
$id=$_SESSION["id"];
$vid=$_GET["id"];
$svid=$_GET["svid"];
$hit=0;
include("connect.php");
include_once("config.php");

$query="select vid from flag where vid='$vid' and user='$id'";
$rs=mysql_query($query,$conn);
$flag=mysql_num_rows($rs);
if ($flag == 0)
{
$query="INSERT INTO `flag`(`vid`,`user`) VALUES ('$vid', '$id')";
$rs=mysql_query($query,$conn);

$query="select hits from options where vid='$vid' and opt_id='$svid'";
$rs=mysql_query($query,$conn);
while($row=mysql_fetch_array($rs))
{
	$hit=$row[0];
}
$hit2=$hit+1;
print "previous vote $hit now vote $hit2";

$query="UPDATE `options` SET `hits` = '$hit2' WHERE `opt_id` ='$svid' and vid='$vid'";
$rs=mysql_query($query,$conn);

mysql_close();
}
else
	print "Please, DON'T VOTE AGAIN.<br>";
if (!headers_sent())
{
	header("Location:".$baseurl."poll_board.php");
	exit;
}
?>