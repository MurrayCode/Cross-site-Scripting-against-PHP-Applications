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
<title><?php print $title; ?> Profile Details</title>
<script language="javascript">
function count()
{
 var txt;
 txt = document.form1.uscrap.value;
 document.form1.counter.value = 160 - txt.length;
if (document.form1.counter.value<0)
	document.form1.uscrap.value = txt.substring(0,159);
}

function fx()
{
 var txt;
 txt = document.form1.uscrap.value;
 if (txt.length <=5 )
	alert ('Minimum view length 6 letters');
 else
	document.form1.submit();
}
</script>
<style type="text/css">
<!--
body {
	background-color: #3A7CD0;
}
.style1 {
	color: #333333;
	font-size: x-small;
}
.style3 {color: #333333; font-size: small; font-weight: bold; }
a:link {
	color: #000000;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: underline;
	color: #999999;
}
a:active {
	text-decoration: none;
}
-->
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body>
<?php
$id=$_SESSION["id"];
$tempid=$_GET["proid"];
$_SESSION["visit_id"]=$tempid;
$msg=trim($_POST['uscrap']);
$time2=date("d-m-Y  H:i:s");

include_once("connect.php");
if ($msg == $_SESSION["prev_msg"] && $msg != "")
	$_SESSION["umsg"] = " ";

if ($msg != "" && $_SESSION["prev_msg"] != $msg && $tempid != "" && $tempid != $_SESSION["id"])
{
$_SESSION["prev_msg"]=$msg;
$tempid=$_SESSION["visit_id"];
$msg = str_replace ("\"", "&quot;", $msg);
$msg = str_replace ("'", "&#8216;", $msg);
$query="insert into msg(dest,sms,src,tym) values('$tempid','$msg','$id','$time2')";
$rs=mysql_query($query,$conn);
$_SESSION["umsg"]="Message sent :)";
}

if ($tempid == "")
	$tempid = $id;
$query="select name,msg,tym from login where id='$tempid'";
$rs=mysql_query($query,$conn);
$flag=mysql_num_rows($rs);

if ($flag==0)
	print "<b>User does not exist.<br></b>";
else
{
while($row=mysql_fetch_array($rs))
{
print "Showing <u>".$tempid."</u>'s Profile.<br>";
print 'Name -><b> '.$row[0]."</b><br>";
if (file_exists("profile_pic/".$tempid.".jpg")==1)
	print '<img src="'.$baseurl.'profile_pic/'.$tempid.'.jpg" alt="'.$row[0].'" border="2" /><br>';
else
	print '<img src="'.$baseurl.'profile_pic/nopic.png" alt="'.$row[0].'" border="2"/><br>';
print 'Message -><b> '.nl2br($row[1]).'</b><br>';
print 'Member since -><b> '.$row[2].'</b><br>';
}
if($tempid != $id)
{
	print '<form name="form1" method="post" action="'.$baseurl.'profile_details.php?proid='.$tempid.'">';
	print '<hr width="40%" align="left">';
	print '<span class="style3">Send a Message.</span><br>';
	print '<textarea name="uscrap" cols="40" rows="4" onKeyDown="count()" onKeyUp="count()"></textarea><br>';
	print '<input type="button" name="sendmsg" value="Send Message" title="Send a message to this user" onclick="fx()"/>';
	print '<span class="style1">Letters Remaining</span>';
    print '<input name="counter" type="text" title="Remaining Letters" id="counter" value="160" size="3" maxlength="3" readonly="true" />';
	print '<input name="dest" type="hidden" value="'.$tempid.'"/>';	
	$_SESSION["url"]="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	if ($_SESSION["umsg"] != "")
		print '<span class="style3">'.$_SESSION["umsg"].'</span>';
	$_SESSION["umsg"]="";
	
	$query="select fid from frnd where fid='$tempid' and id='$id'";
	$rs=mysql_query($query,$conn);
	$flag=mysql_num_rows($rs);
	$addfrndurl="window.location='".$baseurl."add_frnd.php?addid=".$tempid."'";

	if ($flag == 0)
		print '<input type="button" name="addthis" value="Make a Bond" title="Stick this person to my supporter list" onclick="'.$addfrndurl.'"/>';

	print '<hr width="40%" align="left">';
	print '</form>';
}

print '<br>';
print '<table width="855" border="1" cellspacing="0" cellpadding="1">';
print '<tr><td colspan="4"><center>';
print 'Topic(s) created by <b>'.$tempid.'</b>';
print '</center></td></tr>';

print '<tr><td><center> Topic Name </center></td>';
print '<td><center> Created On </center></td>';
print '<td><center> Last reply received on </center></td>';
print '<td><center> Replies </center></td></tr>';
}

$query="select name,cr_time,up_time,tid from topic2 where user='$tempid'";
$rs=mysql_query($query,$conn);
$flag=mysql_num_rows($rs);

if ($flag==0)
{
	print '<br>';
	print '<table width="855" border="1" cellspacing="0" cellpadding="1">';
	print '<tr><td><center><b>User has not created any topic.<b></center></td></tr>';
}
else
{

while($row=mysql_fetch_array($rs))
{
print '<tr><td><center><b><a href="'.$baseurl.'subject.php?subid='.$row[3].'" title="View this topic">'.$row[0].'</a></b></center></td>';
print '<td><center><b>'.$row[1].'</b></center></td>';
print '<td><center><b>'.$row[2].'</b></center></td>';

$query2="select idx from topic where tid='".$row[3]."'";
$rs2=mysql_query($query2,$conn);
$flag2=mysql_num_rows($rs2);
print '<td><center><b>'.$flag2.'</b></center></td></tr>';
}
}
print '</table>';
mysql_close($conn);
?>
</body>
</html>