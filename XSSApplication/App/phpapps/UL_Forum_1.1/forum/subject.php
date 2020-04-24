<?php
session_start();
error_reporting(0);
$time = round(microtime(),5);
include("config.php");
if(!isset($_SESSION["id"]))
{
	header("Location:".$baseurl."getout.htm");
}
?>
<html>
<head>
<title><?php print $title; ?> Subject Details</title>

<script language="javascript">
function count()
{
 var txt;
 txt = document.form1.scrap.value;
 document.form1.counter.value = 500 - txt.length;
if (document.form1.counter.value<0)
	document.form1.scrap.value = txt.substring(0,499);
}

function fx()
{
 var txt;
 txt = document.form1.scrap.value;
 if (txt.length <=5 )
	alert ('Minimum view length 6 letters');
 else
	form1.submit();
}
</script>

<style type="text/css">
<!--
body{padding: 10px;background-color: #3A7CD0; font: 100.01% "Trebuchet MS",Verdana,Arial,sans-serif}
body,td,th {
	color: #FFFFFF;
}
h1,h2,p{margin: 0 10px}
h1{font-size: 250%;color: #FFF}
h2{font-size: 200%;color: #f0f0f0}
p{padding-bottom:1em}
h2{padding-top: 0.3em}

div#nifty{ margin: 0 5%;background: #3156B1}
b.rtop, b.rbottom{display:block;background: #3A7CD0}
b.rtop b, b.rbottom b{display:block;height: 1px; overflow: hidden; background: #0B3C77}
b.r1{margin: 0 5px}
b.r2{margin: 0 3px}
b.r3{margin: 0 2px}
b.rtop b.r4, b.rbottom b.r4{margin: 0 1px;height: 5px}

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

.style1 {color: #666666}
.style2 {color: #333333}
.style3 {color: #000000}

a:link {
	text-decoration: none;
	color: #000033;
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body>
<?php
$id=$_SESSION["id"];
$subid=$_GET["subid"];
if ($subid != "")
	$_SESSION["subid"]=$subid;
else
	$subid=$_SESSION["subid"];

$DelId=$_GET["DelMsgId"];
$msg=$_POST["scrap"];

$time2=date("Y-m-d H:i:s");

$err="";
include("connect.php");

if($id == "")
	$err = "Please Login.<br>";
if($msg == "")
	$err = "No message ?<br>";

if (isset($DelId))
{
	$query="select tid from topic where idx='$DelId' and user='$id'";
	$rs=mysql_query($query,$conn);
	$flag=mysql_num_rows($rs);
if ($flag==0)
	print "<b>Sorry Can't Delete.</b>";
else
{
	$query="delete from topic where idx='$DelId'";
	$rs=mysql_query($query,$conn);
}
	if (!headers_sent())
	{
		header("Location:".$baseurl."subject.php?subid=".$_SESSION["subid"]);
		exit;
	}
}

if ($msg == $_SESSION["prev_msg"] && $msg != "")
	print " ";
else
{
	if ($msg != "" && $_SESSION["prev_msg"] != $msg)
{
$_SESSION["prev_msg"]=$msg;
$subid=$_SESSION["subid"];
$msg = str_replace ('"', '&quot;', $msg);
$msg = str_replace ("'", "&#8216;", $msg);
$msg = str_replace ("<", "&lt;", $msg);
$msg = str_replace (">", "&gt;", $msg);

$msg = strip_tags($msg);
$query="insert into topic(tid,msg,user) values('$subid','$msg','$id')";
$rs=mysql_query($query,$conn);

$query="update topic2 set up_time='$time2' where tid='$subid'";
$rs=mysql_query($query,$conn);
mysql_close($conn);
}
}

include("connect.php");
$query="select * from topic where tid='$subid'";
$rs=mysql_query($query,$conn);
$flag=mysql_num_rows($rs);

if ($flag==0)
	print "<b>No details available.</b>";	
else
{
while($row=mysql_fetch_array($rs))
{
?>
<div id="nifty">
<b class="rtop">
<b class="r1"></b>
<b class="r2"></b>
<b class="r3"></b>
<b class="r4"></b>
</b>
<?php
if (file_exists("profile_pic/".$row[4].".jpg")==1)
	print '<a href="'.$baseurl.'profile_details.php?proid='.$row[4].'" title="View this person"> <img src="'.$baseurl.'profile_pic/'.$row[4].'.jpg" title="'.$row[4].'" border="2" style="border-color:#26458C"/></a><br>';
else
	print '<a href="'.$baseurl.'profile_details.php?proid='.$row[4].'" title="View this person"> <img src="'.$baseurl.'profile_pic/nopic.png" title="'.$row[4].'"/></a><br>';

$query2="select name from login where id='".$row[4]."'";
$rs2=mysql_query($query2,$conn);
while($row2=mysql_fetch_array($rs2))
{
	$author=$row2[0];
}
print "Posted By -> <b><a href='".$baseurl."profile_details.php?proid=".$row[4]."' title='View this person'>".$author."</a></b><br>";
$tempmsg = $row[0];

//				PROCESS MARK UP
if (strstr($tempmsg, "[b]")) 
  if (!strstr($tempmsg, "[/b]"))
	$tempmsg=$tempmsg."[/b]";
$tempmsg = str_replace("[b]","<b>",$tempmsg);
$tempmsg = str_replace("[/b]","</b>",$tempmsg);

if (strstr($tempmsg, "[i]")) 
  if (!strstr($tempmsg, "[/i]"))
	$tempmsg=$tempmsg."[/i]";
$tempmsg = str_replace("[i]","<i>",$tempmsg);
$tempmsg = str_replace("[/i]","</i>",$tempmsg);

if (strstr($tempmsg, "[u]")) 
  if (!strstr($tempmsg, "[/u]"))
	$tempmsg=$tempmsg."[/u]";
$tempmsg = str_replace("[u]","<u>",$tempmsg);
$tempmsg = str_replace("[/u]","</u>",$tempmsg);

if (strstr($tempmsg, "[m]")) 
  if (!strstr($tempmsg, "[/m]"))
	$tempmsg=$tempmsg."[/m]";
$tempmsg = str_replace("[m]","<marquee behavior='alternate'>",$tempmsg);
$tempmsg = str_replace("[/m]","</marquee>",$tempmsg);

if (strstr($tempmsg, "[s]")) 
  if (!strstr($tempmsg, "[/s]"))
	$tempmsg=$tempmsg."[/s]";
$tempmsg = str_replace("[s]","<s>",$tempmsg);
$tempmsg = str_replace("[/s]","</s>",$tempmsg);

if (strstr($tempmsg, "[c]"))
  if (!strstr($tempmsg, "[/c]"))
	$tempmsg=$tempmsg."[/c]";
$tempmsg = str_replace("[c]","<tt>",$tempmsg);
$tempmsg = str_replace("[/c]","</tt>",$tempmsg);

//				PROCESS SMILEY
$tempmsg = str_replace("[:)]","<img src='img/smiley0.png' title='Smile' height='100' width='100'>",$tempmsg);
$tempmsg = str_replace("[;)]","<img src='img/smiley1.png' title='Winky Smile' height='100' width='100'>",$tempmsg);
$tempmsg = str_replace("[:D]","<img src='img/smiley2.png' title='Wide Smile' height='100' width='100'>",$tempmsg);
$tempmsg = str_replace("[:>]","<img src='img/smiley3.png' title='Toothy Smile' height='100' width='100'>",$tempmsg);
$tempmsg = str_replace("[:B]","<img src='img/smiley4.png' title='Smart Smile' height='100' width='100'>",$tempmsg);


$tempmsg = str_replace("[:|]","<img src='img/smiley5.png' title='Dump' height='100' width='100'>",$tempmsg);
$tempmsg = str_replace("[8|]","<img src='img/smiley6.png' title='Puzzled' height='100' width='100'>",$tempmsg);
$tempmsg = str_replace("[:!]","<img src='img/smiley7.png' title='Dont Know' height='100' width='100'>",$tempmsg);
$tempmsg = str_replace("[:P]","<img src='img/smiley8.png' title='Ashamed' height='100' width='100'>",$tempmsg);
$tempmsg = str_replace("[:O]","<img src='img/smiley9.png' title='Wow' height='100' width='100'>",$tempmsg);


$tempmsg = str_replace("[:o]","<img src='img/smiley10.png' title='Cool' height='100' width='100'>",$tempmsg);
$tempmsg = str_replace("[:{]","<img src='img/smiley11.png' title='What' height='100' width='100'>",$tempmsg);
$tempmsg = str_replace("[:(]","<img src='img/smiley12.png' title='Sad' height='100' width='100'>",$tempmsg);
$tempmsg = str_replace("[:x]","<img src='img/smiley13.png' title='Angry' height='100' width='100'>",$tempmsg);
$tempmsg = str_replace("[:<]","<img src='img/smiley14.png' title='Crying' height='100' width='100'>",$tempmsg);

print "Message -> ".nl2br($tempmsg)."<br>";
$tempmsg="";
print "On -><i> ".$row[1]."</i><br>";
if ($_SESSION["id"]==$row[4])
{
	print "<p align='right'>";
	print '<a href="'.$baseurl.'subject.php?DelMsgId='.$row[3].'" title="Delete this post" class="tab">Delete</a>';
	print "</p>";
}
?>
<b class="rbottom">
<b class="r4"></b>
<b class="r3"></b>
<b class="r2"></b>
<b class="r1"></b>
</b>
</div>
<br>
<?php
}
}
$_SESSION["url"]="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

$query="select name from topic2 where tid='$subid'";
$rs=mysql_query($query,$conn);
$flag=mysql_num_rows($rs);
if ($flag==1)
{
?>
<div id="container" >
<form id="form1" name="form1" method="post" action="<?php print $baseurl ?>subject.php?subid=<?php print $subid; ?>">
  <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr align="center"><td>
        Write your views !           <br/>
        <textarea name="scrap" cols="60" rows="4" onKeyDown="count()" title="Write your view" onKeyUp="count()"></textarea>        
        <br/><span class="style1">Letters Remaining </span>
        <input name="counter" title="Number of Letters you can type." type="text" id="counter" value="500" size="3" maxlength="3" readonly="true" />
		<br/><font size="2">HTML Tag is off.</font><br/>
        <input type="button" name="Submit" value="Submit" title="Submit your view" onClick="fx()"/>
        <input type="reset" name="Submit3" value="Reset" title="Clear your view" />    
	</td></tr>
    <tr align="center"><td><table width="650" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td colspan="5"><center>
            <strong>Markup Tips:            </strong>
          </center></td>
          </tr>
        <tr>
          <td colspan="2"><h5 align="center"><span class="style2"><span class="style3">[b]</span> <strong>bold text</strong> <span class="style3">[/b]</span></span></h5></td>
          <td width="210"><h5 align="center" class="style2"><span class="style3">[u]</span> <u>underline text</u> <span class="style3">[/u]</span></h5></td>
          <td colspan="2"><h5 align="center" class="style2"><span class="style3">[i]</span> <em>italic text</em><span class="style3"> [/i]</span></h5></td>
          </tr>
        <tr>
          <td width="192"><h5 align="center" class="style2"><span class="style3">[s]</span><s> striked text</s><span class="style3"> [/s]</span></h5></td>
          <td width="25"><h5 class="style3">[m]
            <marquee>
              </marquee>
           </h5></td>
          <td><h5 align="center" class="style2"><marquee>marquee text</marquee></h5></td>
          <td width="26"><h5 class="style3">[/m]</h5></td>
          <td width="181"><h5 align="center" class="style2"><span class="style3">[c]  </span><tt>code text </tt><span class="style3">[/c] </span></h5></td>
        </tr>
      </table>
	  <br>
	  <table width="200" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td colspan="5"><center>
            <span class="style2"><strong>Smiley            </strong></span>
          </center>          </td>
          </tr>
        <tr align="center" valign="middle">
          <td><center>
                  <strong>            <img src="img/thumb/smiley.png" width="25" title="Smile"" height="25">
[<span class="style3">:)</span>]            </strong>            
          </center>          </td>
          <td><center>
                  <strong>            <img src="img/thumb/smiley(1).png" title="Winky Smile" width="25" height="25"> [<span class="style3">;)</span>]            </strong>            
          </center>          </td>
          <td><center>
                  <strong>            <img src="img/thumb/smiley(2).png" title="Wide Smile" width="25" height="25">[<span class="style3">:D</span>]            </strong>            
          </center>          </td>
          <td><center>
                  <strong>            <img src="img/thumb/smiley(3).png" title="Toothy Smile" width="25" height="25">[<span class="style3">:&gt;</span>]            </strong>            
          </center>          </td>
          <td><center>
                  <strong>            <img src="img/thumb/smiley(4).png" title="Smart Smile" width="25" height="25">[<span class="style3">:B</span>]            </strong>            
          </center>          </td>
        </tr>
        <tr>
          <td><center>
                  <strong>            <img src="img/thumb/smiley(5).png" title="Dumb" width="25" height="25">
[<span class="style3">:|</span>]            </strong>            
          </center>          </td>
          <td><center>
                  <strong>            <img src="img/thumb/smiley(6).png" title="Puzzled" width="25" height="25">[<span class="style3">8|</span>]            </strong>            
          </center>          </td>
          <td><center>
                  <strong>            <img src="img/thumb/smiley(7).png" title="Don't Know" width="25" height="25"> [<span class="style3">:!</span>]            </strong>            
          </center>          </td>
          <td><center>
                  <strong>            <img src="img/thumb/smiley(8).png" title="Ashamed" width="25" height="25"> [<span class="style3">:P</span>]            </strong>            
          </center>          </td>
          <td><center>
                  <strong>            <img src="img/thumb/smiley(9).png" title="Wow" width="25" height="25">[<span class="style3">:O</span>]            </strong>            
          </center>          </td>
        </tr>
        <tr>
          <td><center>
                  <strong>            <img src="img/thumb/smiley(10).png" title="Cool" width="25" height="25">
[<span class="style3">:o</span>]            </strong>            
          </center>          </td>
          <td><center>
                  <strong>            <img src="img/thumb/smiley(11).png" title="What" width="25" height="25"> [<span class="style3">:{</span>]            </strong>            
          </center>          </td>
          <td><center>
                  <strong>            <img src="img/thumb/smiley(12).png" title="Sad" width="25" height="25">
          [<span class="style3">:(</span>]            </strong>            
          </center>          </td>
          <td><center>
                  <strong>            <img src="img/thumb/smiley(13).png" title="Angry" width="25" height="25"> [<span class="style3">:x</span>]            </strong>            
          </center>          </td>
          <td><center>
                  <strong>            <img src="img/thumb/smiley(14).png" title="Crying" width="25" height="25">
          [<span class="style3">:&lt;</span>]            </strong>            
          </center>          </td>
        </tr>
      </table>
	  </td>
    </tr>
  </table>
</form>
</div>
<?php
}
mysql_close($conn);
$time2 = round(microtime(),5);
$generation = $time2 - $time;
echo "<font size='1'>This page took $generation seconds to render </font>";
?>
</body>
</html>