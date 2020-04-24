<?php
session_start();
error_reporting (0);
include("config.php");
if(!isset($_SESSION["id"]))
{
	header("Location:".$baseurl."getout.htm");
}
?>
<html>
<head>
<title><?php print $title;?> Inbox</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style type="text/css">
<!--
body {
	background-color: #3A7CD0;
}
body,td,th {
	color: #000000;
}
-->
</style>
<script language="javascript">

function fx()
{
for(i=0;i<document.f1.select.length;i++)
{
if (document.f1.select[i].selected)
	vtym=document.f1.select.options[i].value;
}
document.f1.idx.value=vtym;
document.f1.submit();
}

function fx2()
{
for(i=0;i<document.f1.select.length;i++)
{
if (document.f1.select[i].selected)
	vtym=document.f1.select.options[i].value;
}
vtym="<?php print $baseurl;?>del_msg.php?msgid="+document.f1.idx.value;
window.location=vtym;
}
</script>
</head>

<body>
<form name="f1" method="post" action="<?php print $baseurl; ?>inbox.php">
<?php
$id=$_SESSION["id"];
if($id=="")
	echo "<center>Session Expired.<br>Please Login.";
else
{
$idx=$_POST["idx"];
?>
<center>

<?php
include("connect.php");
$query="select * from msg where dest='$id'";
$rs=mysql_query($query,$conn);
$flag=mysql_num_rows($rs);

if ($flag==0)
	echo "<br>You have no message.<br>";
else
{
	echo "<br>You have ".$flag." message(s).<br>";
?>
  <b>From - Time</b>   
  <hr>
  <select name="select" size="10" onclick="fx()" onDblclick="fx2()" title="Message List (Single Click - Open, Double Click - Delete)">
<?php
while($row=mysql_fetch_array($rs))
{
echo "<option value=\"".$row[4]."\">".$row[2]." - ".$row[3]."</option>";
}
?>
</select>
<p align="right">
Single click to Read message. Docuble click to delete message.
</p>

<?php
}
mysql_close();
?>
<br><br>
<input name="idx" type="hidden" id="idx">
</center>
  <?php
}
if ($idx != "")
{
include("connect.php");
$query="select * from msg where dest='$id' and idx='$idx'";
$rs=mysql_query($query,$conn);
$flag=mysql_num_rows($rs);

if ($flag==0 and $idx=="")
	echo "You have no mail.<br>";
else
{
while($row=mysql_fetch_array($rs))
{
echo "<hr><hr><b>&#8226;From  : </b>".$row[2]."<br><b>&#8226;Message : </b>".$row[1];
}
}
mysql_close();
$idx="";
}
?>
</form>

</body>
</html>