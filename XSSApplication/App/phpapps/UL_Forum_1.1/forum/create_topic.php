<?php
session_start();
include("config.php");
error_reporting (0);
if(!isset($_SESSION["id"]))
{
	header("Location:".$baseurl."getout.htm");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php
include("config.php");
print "<title>".$title." - Create Topic</title>";
?>
<style type="text/css">
<!--
body {
	background-color: #3A7CD0;
}
.style1 {
	color: #666666;
	font-size: x-small;
}
.style2 {
	color: #333333;
	font-weight: bold;
}
body,td,th {
	color: #FFFFFF;
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
<script language="javascript">
function count()
{
 var txt;
 txt = document.frm.scrap.value;
 document.frm.counter.value = 500 - txt.length;
if (document.frm.counter.value<0)
	document.frm.scrap.value = txt.substring(0,499);
}

function fx2()
{
for(i=0;i<document.frm.cat2.length;i++)
{
if (document.frm.cat2[i].selected)
	vtym=document.frm.cat2.options[i].value;
}
document.frm.cat.value=vtym;
}

function fx()
{
 flag=1;
 var txt,txt2,txt3;
 txt = document.frm.scrap.value;
 txt2 = document.frm.name.value;
 txt3 = document.frm.code.value;

 var occurences = txt2.match(/\./g);
 if (occurences == null)
	occurences=0;

 var vldChars="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ.+-!#"; 
	for (var j = 0; j <txt2.length; j++)
{
	if (vldChars.indexOf(txt2.charAt(j)) == -1)
	{
		flag=0;		
	}
}

 if (txt.length <6 )
	alert ('Minimum view length 6 letters');
 else if(txt2.length<4)
	alert ('Minimum Topic name length is 4 letters.');
 else if(txt3.length!=6)
	alert ('Security code length must be of 6 letters.');
 else if (occurences.length >2)
	alert('Topic name can contain maximum 2 dots.');
 else if(flag==0)
	alert("You are using non-allowed charecters.\nUse 0-9\nA-Z\na-z\n.+-!#");
 else
	document.frm.submit();
}
</script>
</head>

<body>
<div id="container" >
<form method="post" action="<?php print $baseurl; ?>create_topic2.php" name="frm">
<table width="797" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td><span class="style2">Topic Name </span></td>
    <td><center>
      <input name="name" type="text" size="60" maxlength="60" title="Topic Name"/>
	  <input type="button" name="check" title="Check if Topic Name is available" value="Check" onclick="javascript:var check=window.open('<?php print $baseurl; ?>check2.php?subID='+frm.name.value,'check','maximize=null,scrollbars=yes, width=180, height=125,left=400,top=400');">
    </center>    </td>
  </tr>
  <tr>
    <td><span class="style2">Created By </span></td>
    <td><center><?php echo '<input type="text" name="author" size="60" readonly="true" value="'.$_SESSION["id"].'">'; ?></center></td>
  </tr>
  <tr>
    <td><span class="style2">Your View </span></td>
    <td><p align="center">
      <textarea name="scrap" title="Your view" cols="60" rows="4" onkeydown="count()" onkeyup="count()"></textarea>
      </p>
      <p align="center"><span class="style1">Letters Remaining </span>
          <input name="counter" type="text" title="Letters Remaining" id="counter" value="500" size="3" maxlength="3" readonly="true" />
        </p></td>
  </tr>
  <tr>
    <td class="style2">Security Image </td>
    <td><center>
      <img name="img" src="<?php print $baseurl; ?>captcha/captcha.php" alt="Enter this letters carefully. To change hit refresh." width="200" height="70" border="1" /> &nbsp; 
      <span class="style1">To change image, hit refresh.      </span>
    </center>    </td>
  </tr>
  <tr>
    <td><span class="style2">Enter Letters from the Image</span></td>
    <td><center>
      <input name="code" type="text" id="code" size="6" title="Are you a HUMAN ?" maxlength="6"/>
    </center>    </td>
  </tr>
  <tr>
    <td class="style2">Select Category </td>
    <td>
	<center>
	  <select name="cat2" id="cat2" onclick="fx2()">
	    <option value="sw" selected="selected">Software</option>
	    <option value="hw">Hardware</option>
	    <option value="os">Operating System</option>
	    <option value="oth">Other / Chit-Chat</option>
	    </select>
	  <input name="cat" type="hidden" id="cat" value="sw" />
	</center>		</td>
  </tr>
  
  <tr>
    <td colspan="2"><center>
      <input type="button" name="Submit" value="Submit" onclick="fx()" title="Create Topic"/>
      <input type="reset" name="Submit2" value="Reset" title="Clear"/>
    </center></td>
  </tr>
</table>
</form>
</div>
</body>
</html>
