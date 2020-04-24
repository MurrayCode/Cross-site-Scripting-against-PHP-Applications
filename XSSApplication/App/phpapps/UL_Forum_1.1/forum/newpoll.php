<?php
session_start();
$id=$_SESSION["id"];
include_once("config.php");
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
<?php
print '<form name="frm" method="post" action="'.$baseurl.'createpoll.php">';
print "Poll Heading.<br>";
print '<input name="name" type="text" size="80"/>';
print "<br>";
print "How many options ?";
print "<br>";
print '<input name="num" type="text" size="1"/><br>';
print "<br>";
print '<input type="submit" value="Next">';
print "</form>";
?>
<a href="poll_board.php" title="Poll Menu" class="tab">Back to Poll menu</a><br><br>
</html>