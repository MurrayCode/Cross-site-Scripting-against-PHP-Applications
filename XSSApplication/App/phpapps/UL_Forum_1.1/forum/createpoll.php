<?php
session_start();
$id=$_SESSION["id"];
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
include_once("config.php");
$htext=$_POST["name"];
$num=$_POST["num"];
print '<form name="frm" method="post" action="'.$baseurl.'insertpoll.php">';
print "Poll Heading <b> $htext </b><br>";
print "Option Texts<br>";
for ($i=1;$i<=$num;$i++)
	print $i .'-> <input name="opt'.$i.'" type="text" size="40"/><br>';
print '<input type="submit">';
print '<input name="num" type="hidden" value="'.$num.'"/>';
print '<input name="name" type="hidden" value="'.$htext.'"/>';
print "</form>"
?>
<a href="poll_board.php" title="Poll Menu" class="tab">Back to Poll menu</a><br><br>
</html>