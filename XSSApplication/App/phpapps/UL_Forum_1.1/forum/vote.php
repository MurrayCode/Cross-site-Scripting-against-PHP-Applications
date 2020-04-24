<?php
session_start();
include_once("config.php");
$id=$_SESSION["id"];
$vid=$_GET["id"];
?>
<html>
<head>
<title><?php print $title; ?></title>
<script>
function fx2()
{
radioObj=document.vote.poll;
	for(var i = 0; i < document.vote.poll.length; i++) 
	{
		if(radioObj[i].checked) 
		{
			m = radioObj[i].value;
		}
	}
document.vote.svid.value=m;
}

function fx3()
{
	if (document.vote.svid.value == null || document.vote.svid.value == "")
		alert ("Select an option");
	else
		window.location="<?php print $baseurl; ?>submit_vote.php?id=<?php print $vid ?>&svid="+document.vote.svid.value;
}
</script>
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
</style>

</head>
<body>
<form name="vote">
<?php
include("connect.php");

$query="select vtext from votes where vid ='$vid'";
$rs=mysql_query($query,$conn);
$flag=mysql_num_rows($rs);

if ($flag > 0)
{
while($row=mysql_fetch_array($rs))
{
	print "<b>".$row[0]."</b><br>";
}

$query="select opt_id,opt_text from options where vid='$vid'";
$rs=@mysql_query($query,$conn);
$flag=mysql_num_rows($rs);

	while($row=mysql_fetch_array($rs))
	{
		print '<input type="radio" name="poll" value="'.$row[0].'" onclick="fx2()"/>'.$row[1]."<br>";
	}
}
else
	print "Invalid poll.";
mysql_close();
?>
<input type="hidden" value="" name="svid"/>
<br>
<input type="button" onclick="fx3()" value="Vote !"/>
</form>
<a href="poll_board.php" title="Poll Menu" class="tab">Back to Poll menu</a><br><br>
</body>
</html>