<?php
session_start();
error_reporting(0);
$id=$_SESSION["id"];
$vid=$_GET["vid"];
?>
<a href="poll_board.php" title="Poll Menu" class="tab">Back to Poll menu</a><br><br>
<?php
include_once("config.php");
$i=0;
$i2=0;
$votes=0;
$temps = array();
include("connect.php");

if (!isset($vid))
{
$query="select vid from flag where user='$id'";
$rs=mysql_query($query,$conn);
while($row=mysql_fetch_array($rs))
{
	$temps[$i]=$row[0];
	$i=$i+1;
}

for($i=0;$i<count($temps);$i++)
{
	$query="select vopts,vtext from votes where vid='$temps[$i]'";
	$rs=mysql_query($query,$conn);
	while($row=mysql_fetch_array($rs))
	{
		$opt=$row[0];
		$title=$row[1];
	}
print "Vote Title <b>".$title."</b><br>";
	for($i2=0;$i2<$opt;$i2++)
{
	$query2="select hits,opt_text from options where vid='$temps[$i]' and opt_id='$i2'";
	$rs2=mysql_query($query2,$conn);
	while($row2=mysql_fetch_array($rs2))
	{
		print $row2[1]." -> ".$row2[0]."<br>";
		$votes=$votes+$row2[0];
	}
}
	print "<i>Total votes submitted <b>".$votes."</b></i><br>";
	print "<hr>";
	$votes=0;
}
}
else
{
	$query="select vopts,vtext from votes where vid='$vid'";
	$rs=mysql_query($query,$conn);
	while($row=mysql_fetch_array($rs))
	{
		$opt=$row[0];
		$title=$row[1];
	}
	print "Vote Title <b>".$title."</b><br>";
	for($i2=0;$i2<$opt;$i2++)
{
	$query2="select hits,opt_text from options where vid='$vid' and opt_id='$i2'";
	$rs2=mysql_query($query2,$conn);
	while($row2=mysql_fetch_array($rs2))
	{
		print $row2[1]." -> ".$row2[0]."<br>";
		$votes=$votes+$row2[0];
	}
}
	print "<i>Total votes submitted <b>".$votes."</b></i><br>";
	print "<hr>";
}
mysql_close();
?>
<html>
<head>
<title><?php print $title; ?></title>
<style type="text/css">
body{padding: 10px;background-color: #3A7CD0; font: 100.01% "Trebuchet MS",Verdana,Arial,sans-serif}
body,td,th {
	color: #FFFFFF;
}
.tab
{
    background-color:   #FF9900;
    border:             2pt solid #D5D5D5;
    border-top-left-radius: 0.2em;
    border-top-right-radius: 0.2em;
	font-size: 12px;
	border-style: dashed;
	border-color:black;
}

a.tab:hover
{
    padding:            0.1em 0.2em 0.1em 0.2em;
	background-color:   orange;
	font-wight: bold;
	border-style: dotted;
}
</style>
</head>
</html>