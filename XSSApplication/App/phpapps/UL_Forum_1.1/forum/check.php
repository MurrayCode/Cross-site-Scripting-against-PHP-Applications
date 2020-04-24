<html>
<head>
<style type="text/css">
body {
	background-color: #3A7CD0;
.style1 {color: #FFFFFF}
body,td,th {
	color: #FFFFFF;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
include("config.php");
print "<title>".$title."</title>";
?>
</head>
<?php
error_reporting (0);
$ID=trim($_GET["ID"]);

if ($ID == "" || strlen($ID)<4 || strlen($ID)>20)
{
	print '<img src="img/error.png" alt="Invalid User Name"/>';
	print "You did not inserted a valid Login ID !";
}
else if ($ID == "user" || $ID == "admin" || $ID == "system" || $ID == "webshine" || $ID == "web-shine")
{
	print '<img src="img/error.png" alt="Using Reserver Name"/>';
	$err = "&nbsp;&nbsp;Sorry, you have typed a Reserved ID.</br>";
}
else
{
include_once("connect.php");
$query="select name from login where id='$ID'";
$rs=mysql_query($query,$conn);
$flag=mysql_num_rows($rs);
if ($flag==0)
{
	print '<img src="img/ok.png" alt="User Name Available"/>';
	print "&nbsp;&nbsp;Hooray, it's available.";
}
else
{
	print '<img src="img/error.png" alt="User Name already used"/>';
	print "&nbsp;&nbsp;Try another !<br>This ID is taken.";
}
}
mysql_close($conn);
?>
</html>