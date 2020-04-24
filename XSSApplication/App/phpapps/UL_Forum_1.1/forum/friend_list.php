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
<title><?php print $title; ?> Friend List</title><style type="text/css">
<!--
body {
	background-color: #3A7CD0;
}
.style1 {
	color: #333333;
	font-weight: bold;
}
a:link {
	color: #000000;
	text-decoration: none;
}
a:hover {
	color: #999999;
	text-decoration: underline;
}
a:visited {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
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
-->
</style>
<table width="650" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="4"><center>
      <span class="style1">My Friends List      </span>
    </center>    </td>
  </tr>
<?php
$id=$_SESSION["id"];
$cr=0;
include_once("connect.php");
$query="select fid from frnd where id='$id'";
$rs=mysql_query($query,$conn);
while($row=mysql_fetch_array($rs))
{
$query2="select name from login where id='$row[0]'";
$rs2=mysql_query($query2,$conn);
while($row2=mysql_fetch_array($rs2))
{
$temp=$row2[0];
}
 if ($cr==0)
 	print '<tr>';
 $cr=$cr+1;
 print '<td><center>'.$row[0].'<br>';
 if (file_exists("profile_pic/".$row[0].".jpg")==1)
	print '<a href="'.$baseurl.'profile_details.php?proid='.$row[0].'" title="View this person"><img src="'.$baseurl.'profile_pic/'.$row[0].'.jpg" alt="'.$temp.'" border="2" /></a>';
 else
	print '<a href="'.$baseurl.'profile_details.php?proid='.$row[0].'" title="View this person"><img src="'.$baseurl.'profile_pic/nopic.png" alt="'.$temp.'" border="2"/></a>';
	
 print '<br><a href="'.$baseurl.'del_frnd.php?proid='.$row[0].'" class="tab" title="Frndz no more !">Delete</a>';
 
 print '</center></td>';
 if ($cr==3)
 {
 	print '</tr>';
 	$cr=0;
 }
}
mysql_close($conn);
?>
</table>
</html>