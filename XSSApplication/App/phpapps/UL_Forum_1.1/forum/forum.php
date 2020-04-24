<?php
include("config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php print $title; ?></title>
</head>


<frameset cols="13%,*" frameborder="no" border="0" framespacing="0">
<frame src="<?php print $baseurl; ?>profile.php" name="topFrame" scrolling="No" noresize="noresize" id="topFrame" title="topFrame" />
<frameset rows="11%,*" framespacing="0" frameborder="no" border="0">
    <frame src="<?php print $baseurl; ?>menu.htm" name="leftFrame" scrolling="No" noresize="noresize" id="leftFrame" title="leftFrame" />
    <frame src="<?php print $baseurl; ?>topic.php" name="mainFrame" id="mainFrame" title="mainFrame" />
</frameset>
</frameset>
<noframes><body>
</body>
</noframes></html>
