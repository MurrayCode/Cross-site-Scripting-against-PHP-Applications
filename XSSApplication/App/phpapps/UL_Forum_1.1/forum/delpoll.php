<?php
session_start();
error_reporting(0);
$id=$_SESSION["id"];
$vid=$_GET["vid"];
include("connect.php");

if (isset($vid))
{
	$query="select vid from votes where vid='$vid' and user='$id'";
	$rs=mysql_query($query,$conn);
	$flag=mysql_num_rows($rs);
if ($flag>0)
{
	$query="delete from votes where vid='$vid'";
	$rs=mysql_query($query,$conn);
	$query="delete from options where vid='$vid'";
	$rs=mysql_query($query,$conn);
	$query="delete from flag where vid='$vid'";
	$rs=mysql_query($query,$conn);
}
}
mysql_close();

if (!headers_sent())
{
	header("Location:".$baseurl."poll_board.php");
	exit;
}
?>