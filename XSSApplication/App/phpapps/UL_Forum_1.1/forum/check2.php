<html>
<head>
<style type="text/css">
body {
	background-color: #3A7CD0;
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
$ID=trim($_GET["subID"]);

if ($ID == "" || strlen($ID)<4)
{
	print '<img src="img/error.png" alt="Invalid Topic Name"/>';
	print "&nbsp;&nbsp;You did not inserted a valid Topic Name !";
}
else
{
include_once("connect.php");
$query="select tid from topic2 where name='$ID'";
$rs=mysql_query($query,$conn);
$flag=mysql_num_rows($rs);
if ($flag==0)
{
	print '<img src="img/ok.png" alt="Topic Name Available"/>';
	print "&nbsp;&nbsp;Hooray, it's available";
}
else
{
	print '<img src="img/warning.png" alt="Same Topic Name already used"/>';
	print "&nbsp;&nbsp;Topic exist with same name.<br>It will be better to use a different name !";
}
}
?>
</html>