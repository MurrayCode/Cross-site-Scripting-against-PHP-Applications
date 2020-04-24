<?php
include("config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php print $title; ?> Profile Settings </title>
<style type="text/css">
<!--
body {
	background-color: #66CCFF;
}
-->
</style></head>
<frameset rows="11%,15%,15%,12%" frameborder="no" border="0" framespacing="0">
<frame src="<?php print $baseurl; ?>save_account.php" name="topFrame" scrolling="No" noresize="noresize" id="topFrame" title="Web-Shine Forum Profile Settings" />
<frame src="<?php print $baseurl; ?>save_account4.php" name="mainFrame" id="mainFrame" title="Web-Shine Forum Profile Settings" noresize="noresize" scrolling="No"/>
<frame src="<?php print $baseurl; ?>save_account2.php" name="bottomFrame1" scrolling="No" noresize="noresize" id="bottomFrame1" title="Web-Shine Forum Profile Settings" />
<frame src="<?php print $baseurl; ?>save_account3.php" name="bottomFrame" scrolling="No" noresize="noresize" id="bottomFrame" title="Web-Shine Forum Profile Settings" />
</frameset>
<noframes><body>
</body>
</noframes>
</html>
