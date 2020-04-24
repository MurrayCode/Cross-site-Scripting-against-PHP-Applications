<?php
session_start();
error_reporting(0);
$id=$_SESSION["id"];
$frndid=$_GET["proid"];
include("connect.php");

if (isset($frndid))
{
	$query="delete from frnd where id='$id' and fid='$frndid'";
	$rs=mysql_query($query,$conn);
}
mysql_close();

if (!headers_sent())
{
	header("Location:".$baseurl."friend_list.php");
	exit;
}
?>