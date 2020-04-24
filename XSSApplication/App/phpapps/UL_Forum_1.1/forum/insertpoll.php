<?php
session_start();
$id=$_SESSION["id"];
include_once("config.php");
$htext=$_POST["name"];
$num=$_POST["num"];
$i2=0;
print "Welcome ".$id."<br>";

$temps = array();
$opts = array();
for($i=1;$i<=$num;$i++)
	$temps[$i-1]=$_POST["opt$i"];

print "Poll Heading <b> $htext </b><br>";
print "Option Texts<br>";
for($i=0;$i<count($temps);$i++)
{
	if (trim($temps[$i]) != "")
	{
		$opts[$i2]=$temps[$i];
		$i2=$i2+1;
	}
}

include("connect.php");

$query="INSERT INTO `votes`(`vtext`,`vopts`,`user`) VALUES ('$htext','$num','$id')";
$rs=mysql_query($query,$conn);
$query="select vid from votes where vtext='$htext'";
$rs=mysql_query($query,$conn);
while($row=mysql_fetch_array($rs))
{
	$vid=$row[0];
}

for($i=0;$i<count($opts);$i++)
{
	$query="INSERT INTO `options`(`vid`,`opt_id`,`opt_text`,`hits`) VALUES ('$vid','$i','$opts[$i]',0)";
	$rs=mysql_query($query,$conn);
}

mysql_close();
print "Poll Created";

if (!headers_sent())
{
	header("Location:".$baseurl."poll_board.php");
	exit;
}
?>
<html>
<head>
<style type="text/css">
body{padding: 10px;background-color: #3A7CD0; font: 100.01% "Trebuchet MS",Verdana,Arial,sans-serif}
body,td,th {
	color: #FFFFFF;
}
</style>
</head>
</html>