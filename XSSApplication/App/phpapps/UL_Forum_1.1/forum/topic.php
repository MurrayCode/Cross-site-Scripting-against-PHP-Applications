<?php
session_start();
$time = round(microtime(),5);
error_reporting(0);
include("config.php");
if(!isset($_SESSION["id"]))
{
	header("Location:".$baseurl."getout.htm");
}
$id=$_SESSION["id"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php print $title; ?> Topic List</title>
<style type="text/css">
<!--
body {
	background-color: #3A7CD0;
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

a:link {
	color: #000000;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #000000;
}
a:hover {
	text-decoration: underline;
	color: #000066;
}
a:active {
	text-decoration: none;
}
.style1 {
	color: #333333;
	font-size: x-small;
	font-weight: bold;
}
.style2 {
	color: white;
	font-size: 10px;
	font-style: italic;
}
#container
{
width:850px;
margin:0 auto;
padding:3px 0;
text-align:left;
position:relative;
-moz-border-radius: 15px;
-webkit-border-radius: 6px;
background-color:#6BACE6;
}
-->
</style>
<script>
function reload()
{
for(i=0;i<document.frm.select.length;i++)
{
if (document.frm.select[i].selected)
	vtym=document.frm.select.options[i].value;
}
window.location="<?php print $baseurl; ?>topic.php?catID="+vtym;
}
</script>
</head>

<body>
<form name="frm">
<p align="right"><span class="style1">Select Topic Category</span>
<select name="select" onchange="reload()">
<?php
$dtid=$_GET["DelTId"];
$catID=$_GET["catID"];

if (!isset($catID))
	$catID="all";

if ($catID=="sw")
	print '<option value="sw" selected="selected">Software</option>';
else
	print '<option value="sw">Software</option>';

if ($catID=="hw")
	print '<option value="hw" selected="selected">Hardware</option>';
else
	print '<option value="hw">Hardware</option>';

if ($catID=="os")
	print '<option value="os" selected="selected">Operating System</option>';
else
	print '<option value="os">Operating System</option>';

if ($catID=="oth")
	print '<option value="oth" selected="selected">Other / Chit-Chat</option>';
else
	print '<option value="oth">Other / Chit-Chat</option>';

if ($catID=="all")
	print '<option value="all" selected="selected">All</option>';
else
	print '<option value="all">All</option>';
?>
</select></p>
</form>


<div id="container">
<table width="836" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="218"><center>
      <em> <strong> Topic </strong> </em>
    </center></td>
    <td width="182"><center>
      <em> <strong> Created By </strong> </em>
    </center></td>
    <td width="175"><center>
      <em> <strong> Creation Time </strong> </em>
    </center></td>
    <td width="183"><center>
      <em> <strong> Last Reply On </strong> </em>
    </center></td>
    <td width="50"><center>
      <em> <strong> Posts </strong> </em>
    </center></td>
  </tr>

<?php
include_once("connect.php");

if (isset($dtid))
{
	$query="select name from topic2 where tid='$dtid' and user='$id'";
	$rs=mysql_query($query,$conn);
	$flag=mysql_num_rows($rs);
if ($flag>0)
{
	$query="delete from topic2 where tid='$dtid'";
	$rs=mysql_query($query,$conn);
	$query="delete from topic where tid='$dtid'";
	$rs=mysql_query($query,$conn);
	if (!headers_sent())
	{
		header("Location:".$baseurl."topic.php");
		exit;
	}
	print '<a href="'.$baseurl.'topic.php">Back to Topic</a>';
}
else
	print "<b>You can not delete a post, which you have not created ! </b><br>";
}

if ($catID == "sw")
{
	print '<img src="'.$baseurl.'img/sw.png" alt="Category Software"/>';
	$query="select * from topic2 where cat='sw' order by cr_time desc";
	$rs=mysql_query($query,$conn);
}
if ($catID == "hw")
{
	print '<img src="'.$baseurl.'img/hw.png" alt="Category Hardware"/>';
	$query="select * from topic2 where cat='hw' order by cr_time desc";
	$rs=mysql_query($query,$conn);
}
if ($catID == "os")
{
	print '<img src="'.$baseurl.'img/os.jpg" alt="Category Operating System"/>';
	$query="select * from topic2 where cat='os' order by cr_time desc";
	$rs=mysql_query($query,$conn);
}
if ($catID == "oth")
{
	print '<img src="'.$baseurl.'img/oth.png" alt="Category Others"/>';
	$query="select * from topic2 where cat='oth' order by cr_time desc";
	$rs=mysql_query($query,$conn);
}
if ($catID == "all")
{
	print '<img src="'.$baseurl.'img/all.png" alt="All Categories"/>';
	$query="select * from topic2 order by cr_time desc";
	$rs=mysql_query($query,$conn);
}

$flag=mysql_num_rows($rs);

if ($flag==0)
	print "<tr><td>No Topic available.</td><td></td><td></td><td></td><td></td></tr>";
else
{
while($row=mysql_fetch_array($rs))
{
print '<tr>';
print '<td width="218"><center><a href="'.$baseurl.'subject.php?subid='.$row[4].'" title="View this topic"><b>'.$row[0].'</b></a>';
if ($row[1] == $_SESSION["id"])
{
print '<br><a class="tab" title="Delete this topic" href="'.$baseurl.'topic.php?DelTId='.$row[4].'">Delete</a>';
}
print '</center></td>';

$query2="select name from login where id='".$row[1]."'";
$rs2=mysql_query($query2,$conn);
while($row2=mysql_fetch_array($rs2))
{
	$author=$row2[0];
}
print '<td width="182"><center><a href="'.$baseurl.'profile_details.php?proid='.$row[1].'" title="View this profile">'.$author.'  ['.$row[1].']</center></td>';

print '<td width="182"><center>'.$row[2].'</center></td>';

print '<td width="175"><center>'.$row[5].'</center></td>';

$query2="select idx from topic where tid='".$row[4]."'";
$rs2=mysql_query($query2,$conn);
$flag2=mysql_num_rows($rs2);
print '<td width="75"><center>'.$flag2.'</center></td>';
print '<tr>';
}
}
mysql_close($conn);
?>
</table>
</div>
<?php
print '<p align="right"><a class="tab" title="Create new topic" href="'.$baseurl.'create_topic.php">Create Topic</a></p>';
print '<p align="right" class="style2">';
$time2 = round(microtime(),5);
$generation = $time2 - $time;
echo "This page took $generation seconds to render";
?>
</p>
</body>
</html>