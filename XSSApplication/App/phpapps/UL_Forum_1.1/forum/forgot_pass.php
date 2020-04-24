<?php
session_start();
include("config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php print $title; ?> - Forgot Password</title>
<style type="text/css">
<!--
body {
	background-color: #3A7CD0;
}
.style4 {font-size: small; color: #333333; font-weight: bold; }
-->
</style>
<script>
function getpass()
{
var url="<?php print $baseurl; ?>forgot_pass.php?id="+document.frm.id.value+"&code="+document.frm.code.value;
window.location=url;
}
function clear()
{
document.frm.id.value="";
document.frm.code.value="";
document.frm.pass.value="";
}
</script>
</head>

<body>

<center>
  <table width="295" height="79" border="0" align="right" cellpadding="1" cellspacing="1">
    <form name="frm">
      <tr>
        <td width="206"><span class="style4">Login ID </span></td>
        <td width="82"><input name="id" type="text" size="10" maxlength="10" title="Login ID"/></td>
      </tr>
      <tr>
        <td class="style4">Security Code </td>
        <td><input name="code" type="password" size="10" maxlength="8" title="Security Code" /></td>
      </tr>
      <tr>
          
  <?php 
	error_reporting (0);
	$id = $_GET["id"];
	$pass = $_GET["code"];

	if ($id != "" and $pass != "")
{	
	include_once("connect.php");
	$query="select pass from login where id='$id' and pass2='$pass'";
	$rs=mysql_query($query,$conn);
	$flag=mysql_num_rows($rs);
	
	if($flag)
	{
	while($row=mysql_fetch_array($rs))
	{		
		$password=$row["pass"];		
	}
		print '<tr><td class="style4">Your Password Is </td>';
        print '<td><input name="pass" type="text" size="10" maxlength="10" value="'.$password.'" /></td></tr>';
		print '<br><a href="'.$baseurl.'login.php"><b>To Sign In Click Here</b></a>';
	}
	else
	{
		print "No Account exist with that ID [".$id."] or ID, Code is wrong.";
		print '<br><a href="'.$baseurl.'register.htm"><b>To Sign Up Click Here</b></a>';
	}
	mysql_close($conn);	
}
?>	
        <td colspan="2">          <center>        
            <input type="button" name="Submit" value="Submit" onclick="getpass()" title="Show password"/>
            <input type="reset" name="Submit2" value="Reset" onclick="clear()" title="Clear"/>            
        </center>
        </td>
      </tr>      
    </form>
  </table>
</center>
</body>
</html>
