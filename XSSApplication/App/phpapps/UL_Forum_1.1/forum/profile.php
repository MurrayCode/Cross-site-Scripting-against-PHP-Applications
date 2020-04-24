<?php
session_start();
error_reporting (0);
include("config.php");
if(!isset($_SESSION["id"]))
{
	header("Location:".$baseurl."getout.htm");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php print $title; ?> Profile</title><BASE TARGET="mainFrame">
<style type="text/css">
<!--
body {
	background-color: #5F9BE1;
}
.style1 {font-weight: bold}

.style2 {color: #B9E0FF; font-weight: bold}

a:link {
	color: #333333;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #333333;
}
a:hover {
	text-decoration: underline;
	color: #000033;
}
a:active {
	text-decoration: none;
	color: #000000;
}
-->
</style></head>

<body>
<table width="118" cellspacing="1" height="150%" cellpadding="1" bordercolor="#335185" border="1">
  <tr bgcolor="#376FC4">
    <td width="114" align="center">
<?php
print "<span class='style2'>".$_SESSION["name"]."'s Profile.<br/></span></td></tr><tr bgcolor='#FFFFFF'><td>";
print "<h5>".$_SESSION["msg"]."</h5>";
if (file_exists("profile_pic/".$_SESSION["id"].".jpg")==1)
	print '<a href="'.$baseurl.'profile_details.php?proid='.$_SESSION["id"].'"><img src="'.$baseurl.'profile_pic/'.$_SESSION["id"].'.jpg" alt="'.$_SESSION["name"].'" border="2"/></a>';
else
	print '<a href="'.$baseurl.'profile_details.php?proid='.$_SESSION["id"].'"><img src="'.$baseurl.'profile_pic/nopic.png" alt="'.$_SESSION["name"].'" border="2" /></a>';
?>
<br />

      <h4><span class="style1">	  <p align="center">
<!--  <a href="<?php print $baseurl;?>create_topic.php" title="Create a new topic">Create Topic</a><BR /><br /> -->
  <a href="<?php print $baseurl; ?>topic.php" title="Show topics list">View Topics</a><BR /><br />
<!--  <a href="<?php print $baseurl; ?>newpoll.php" title="Show poll list">Create Poll</a><BR /><br /> -->
  <a href="<?php print $baseurl; ?>poll_board.php" title="Show poll list">View Polls</a><BR /><br />
  <a href="<?php print $baseurl; ?>inbox.php" title="View messages">Inbox</a><BR /><br />
  <a href="<?php print $baseurl; ?>friend_list.php" title="View Friends">View Friends</a>
    </p>  </span>      </h4>

    </td>
  </tr>
</table>
</body>
</html>