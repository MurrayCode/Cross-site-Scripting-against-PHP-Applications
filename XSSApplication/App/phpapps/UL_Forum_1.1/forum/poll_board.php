<?php
session_start();
$id=$_SESSION["id"];
?>
<html>
<head>
<title><?php print $title; ?></title>
<style type="text/css">
<!--
body{padding: 10px;background-color: #3A7CD0; font: 100.01% "Trebuchet MS",Verdana,Arial,sans-serif}
body,td,th {
	color: #FFFFFF;
}

.del
{
    background-color:   red;
    border:             2pt solid;
    border-top-left-radius: 0.2em;
    border-top-right-radius: 0.2em;
	font-size: 8px;
	border-style: double;
	border-color:lime;
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
	text-decoration: none;
}

a.tab:hover
{
    padding:            0.1em 0.2em 0.1em 0.2em;
	background-color:   orange;
	font-wight: bold;
	border-style: dotted;
	text-decoration: none;
}

#container
{
width:700px;
margin:0 auto;
padding:3px 0;
position:relative;
-moz-border-radius: 15px;
-webkit-border-radius: 6px;
background-color:#6BACE6;
text-align:center;
}

a:link {
	color: white;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #CCCCCC;
}
a:hover {
	text-decoration: underline;
	color: #FFFFFF;
}
a:active {
	text-decoration: none;
	color: #CCCCCC;
}

text
{
font-size: 10;
}
-->
</style>
</head>
<table>
  <tr>
    <td><strong>Poll</strong></td>
    <td><strong>Created By </strong></td>
    <td>&nbsp;</td>
  </tr>
  <tr><td><hr></td><td><hr></td><td><hr></td></tr>
<?php
include_once("config.php");
include("connect.php");
$query="select vid from votes";
$rs=mysql_query($query,$conn);
$flag=mysql_num_rows($rs);

if ($flag > 0)
{
	$query="select vid,vtext,user from votes where vid not in (select distinct vid from flag where user='$id')";
	$rs=mysql_query($query,$conn);
	$flag=mysql_num_rows($rs);
	if ($flag > 0)
	{
	while($row=mysql_fetch_array($rs))
	{
	 print "<tr>";
     print '<td><b><a href="'.$baseurl.'vote.php?id='.$row[0].'" title="Click to vote">'.$row[1].'</a></b></td>';

	 $query2="select name from login where id='".$row[2]."'";
	 $rs2=mysql_query($query2,$conn);
	 while($row2=mysql_fetch_array($rs2))
	{
		$author=$row2[0];
	}
	 print '<td><a href="'.$baseurl.'profile_details.php?proid='.$row[2].'" title="View this profile">'.$author.'['.$row[2].']</td>';
	 if ($id==$row[2])
		print '<td><a href="'.$baseurl.'delpoll.php?vid='.$row[0].'" title="Click to Delete">Delete</a></td>';
	 else
		print "<td></td>";
	 print "</tr>";
	}
	}

		print "<tr></tr><tr></tr>";
		$query="select vid,vtext,user from votes where vid in (select distinct vid from flag where user='$id')";
		$rs=mysql_query($query,$conn);
		$flag=mysql_num_rows($rs);
		if ($flag > 0)
		{
		while($row=mysql_fetch_array($rs))
		{
		print "<tr>";
		print '<td><a href="'.$baseurl.'viewpoll.php?vid='.$row[0].'" title="View poll result">'.$row[1].'</a></td>';
		$query2="select name from login where id='".$row[2]."'";
		$rs2=mysql_query($query2,$conn);
		while($row2=mysql_fetch_array($rs2))
		{
			$author=$row2[0];
		}
		print '<td><a href="'.$baseurl.'profile_details.php?proid='.$row[2].'" title="View this profile">'.$author.'['.$row[2].']</td>';
		if 	($id==$row[2])
			print '<td><a href="'.$baseurl.'delpoll.php?vid='.$row[0].'" title="Click to Delete" class="tab">Delete</a></td>';
		else
			print "<td></td>";
		print "</tr>";
		}
		}
}
mysql_close();
?>
</table>
<?php
print '<br><a href="'.$baseurl.'newpoll.php" title="Create a poll" class="tab">Create a Poll</a> &nbsp; &nbsp; &nbsp; <a href="'.$baseurl.'viewpoll.php" title="View poll results" class="tab">View Polls Results</a><br><br>';
?>
</html>