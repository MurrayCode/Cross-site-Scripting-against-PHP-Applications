<?php 
session_start();
error_reporting(0);
include("config.php");
if(!isset($_SESSION["id"]))
{
	header("Location:".$baseurl."getout.htm");
}

$change="";
$abc="";

define ("MAX_SIZE","100");
 function getExtension($str) 
{
    $i = strrpos($str,".");
    if (!$i) { return ""; }
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
}
 $errors=0;
  
 if($_SERVER["REQUEST_METHOD"] == "POST")
 {
 	$image =$_FILES["file"]["name"];
	$uploadedfile = $_FILES['file']['tmp_name'];
 
if ($image) 
{
 		$filename = stripslashes($_FILES['file']['name']); 	
  		$extension = getExtension($filename);
 		$extension = strtolower($extension);
 
 if (($extension != "jpg") && ($extension != "jpeg")) 
	{
		$change='<div class="msgdiv">Unknown Image extension </div> ';
		$errors=1;
	}
else
	{
		$size=filesize($_FILES['file']['tmp_name']);
		if ($size > MAX_SIZE*1024)
		{
			$change='<div class="msgdiv">You have exceeded the size limit!</div> ';
			$errors=1;
		}
		if($extension=="jpg" || $extension=="jpeg" )
		{
			$uploadedfile = $_FILES['file']['tmp_name'];
			$src = imagecreatefromjpeg($uploadedfile);
		}
	list($width,$height)=getimagesize($uploadedfile);

		if($height >100)
	{
		$newheight=100;
		$newwidth=($width/$height)*$newheight;
		$tmp=imagecreatetruecolor($newwidth,$newheight);
		imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
	}
	$newwidth=100;
	$newheight=($height/$width)*$newwidth;
	$tmp=imagecreatetruecolor($newwidth,$newheight);
	imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);

	$filename = "profile_pic/". $_FILES['file']['name'];

	imagejpeg($tmp,$filename,100);

	imagedestroy($src);
	imagedestroy($tmp);	

	unlink("profile_pic/".$_SESSION["id"].".jpg");
	rename($filename, "profile_pic/".$_SESSION["id"].".jpg");
	$filename=$baseurl."profile_pic/".$_SESSION["id"].".jpg";
	}
}
}

 if(isset($_POST['Submit']) && !$errors) 
{
 if($filename == "" or $image == "" or $uploadedfile == "")
	{
		$change='<div class="msgdiv">Nothing to Upload ! </div> ';
		$errors=1;
	}
 else
 	$change=' <div class="msgdiv">Image Uploaded Successfully!. Hit refresh (F5) to see.</div>';
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<meta content="en-us" http-equiv="Content-Language">
<title>Web-Shine Profile Settings</title>
      
	 
<style type="text/css">
  .help
{
font-size:11px; color:#006600;
}
body 
{
	background-color:#3A7CD0;
	font-family:"Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, Helvetica, sans-serif; 
}

.msgdiv
{
width:759px;
padding-top:8px;
padding-bottom:8px;
background-color: #fff;
font-weight:bold;
font-size:11px;-moz-border-radius: 6px;-webkit-border-radius: 6px;
}

#container
{
width:650px;
margin:0 auto;
padding:3px 0;
text-align:left;
position:relative;
-moz-border-radius: 15px;
-webkit-border-radius: 6px;
background-color:#6BACE6;
}
.style1 {
	color: #333333;
	font-size: x-small;
}
.style3 {color: #333333; font-size: small; font-weight: bold; }
.style4 {
	font-size:10px;
	font-weight: bold;
}
</style>
</head>

<body>
<div align="center" id="err"><?php echo $change; ?>

</div>

<div id="space"></div>
 
<div id="container" >
    
  <div id="con">

    <table width="650" cellpadding="0" cellspacing="0" id="main">
        <tbody>
        <tr>
            <td width="608" valign="top" id="main_right">
			<div id="posts">
			  <form method="post" action="<?php print $baseurl; ?>save_account4.php" enctype="multipart/form-data" name="form1">
			    <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <Td width="169" rowspan="3">
			  <?php
			  if ($filename != "")
			  {
			   print '<img src="'.$filename.'" border="1" alt="Preview of Uploaded Image"/>';
			  }
			  ?>			   
			   </Td>
              <Td>&nbsp;</Td></tr>
			<tr>
			  <td width="81"><div align="right" class="titles"><span class="style3">Picture : &nbsp;</span>  </div></td>
			<td width="450" align="left">
            <div align="left">
              <input size="25" name="file" title="Select a valid image file [JPEG Only]" type="file" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10pt"; class="box"/>
			  
              <input type="submit" id="mybut" value="Update" name="Submit" title="Upload Image"/>
            </div></td></tr>
		<tr>
		  <Td></Td>
		<Td valign="top" class="help style1"><p>Maximum image size <b>100 </b>kb</span></p>
		 </Td></tr>
		 <tr>
		 <br />
    <span class="style4">&nbsp; Hit F5 (refresh) button to see the changes after uploading image.</span></tr>
		 </table>
		</form>
</div></td>
</tr>
</tbody>
</table>
</div>
</div>

</body></html>