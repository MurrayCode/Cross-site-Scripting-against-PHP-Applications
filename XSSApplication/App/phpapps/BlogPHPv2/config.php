<?php 
    $dbhost = "localhost";
    $dbname = "blogphp"; // mysql database name
    $dbuser = "root"; // mysql database username
    $dbpass = "hacklab2019"; // mysql database password
	$pre = "blogphp_"; // table prefix
    $upload = "upload/"; // upload folder
	$sitename = "BlogPHP"; // site name
	$guests = "yes"; // Allow guests to post comments? (yes or no)
	$wysiwyg = "yes"; // enable wysiwyg editor? (yes or no)
	$color = "#D9F773"; // this color code controls the bg of the admin box (great if you use a custom layout)


	// --------------------- DO NOT CHANGE ANYTHING BELOW UNLESS YOU KNOW WHAT YOU ARE DOING -------------------------//

    $exd = @explode(".", $_SERVER['HTTP_HOST']);
    if ($exd[2]) {
    $domain = $exd[1].".".$exd[2];
    } else {
    $domain = $exd[0].".".$exd[1];
    }
	$siteurl = "http://".$_SERVER['HTTP_HOST'];
	$cpage = basename($_SERVER['PHP_SELF']);
	$page4 = explode("/", $_SERVER['REQUEST_URI']);
	if ($subdomain == "yes") {
	$fpage = $page4[2];
	} else {
	$fpage = $page4[1];
	}

	$mysql = @mysql_select_db($dbname, @mysql_connect($dbhost, $dbuser, $dbpass)) or die (mysql_error());

	if ($install == "") {
	// temp variables
	$prf2 = sprintf("SELECT level,email,url FROM ".$pre."users WHERE username = '%s' AND password = '%s'",
    mysql_real_escape_string($_COOKIE[blogphp_username]),
    mysql_real_escape_string($_COOKIE[blogphp_password]));
	$prf = mysql_fetch_row(mysql_query($prf2));
	$level = $prf[0];
	$email = $prf[1];
	$url = $prf[2];

    if ($level) {
	$theuser = htmlentities($_COOKIE[blogphp_username]);
	} else {
	$theuser = "guest";
	}
	if ($cpage == "index.php") {
	$ptype = "frontend";
	} else {
	$ptype = "backend";
	}
    $stype2 = @mysql_num_rows(@mysql_query("SELECT * FROM ".$pre."stats WHERE ip = '".$_SERVER['REMOTE_ADDR']."'"));

	if ($protect == "yes") {
	if (($level == "Member") or ($level == "")) {
	if ($_GET['act'] == "denied") {
	} else {
	@header('location: admin.php?act=denied');
	}
	}
	}
	}

	$version = "2.0"; // version number, DO NOT CHANGE
	include ("functions.php");
	$info = new BrowserInfo($HTTP_USER_AGENT);

	if ($stype2 == "0") {
	@mysql_query("INSERT INTO ".$pre."stats VALUES ('null', '".$fpage."', '".$_SERVER['HTTP_REFERER']."', 'unique', '".$ptype."', '".$theuser."', '".$_SERVER['REMOTE_ADDR']."', '".$info->OS." ".$info->OS_Version."|".$info->Browser." ".$info->Browser_Version."', '".date("d")."', '".date("z")."', '".date("m")."', '".date("W")."', '".date("Y")."')");
	@mysql_query("INSERT INTO ".$pre."stats VALUES ('null', '".$fpage."', '".$_SERVER['HTTP_REFERER']."', 'view', '".$ptype."', '".$theuser."', '".$_SERVER['REMOTE_ADDR']."', '".$info->OS." ".$info->OS_Version."|".$info->Browser." ".$info->Browser_Version."', '".date("d")."', '".date("z")."', '".date("m")."', '".date("W")."', '".date("Y")."')");
	} else {
	@mysql_query("INSERT INTO ".$pre."stats VALUES ('null', '".$fpage."', '".$_SERVER['HTTP_REFERER']."', 'view', '".$ptype."', '".$theuser."', '".$_SERVER['REMOTE_ADDR']."', '".$info->OS." ".$info->OS_Version."|".$info->Browser." ".$info->Browser_Version."', '".date("d")."', '".date("z")."', '".date("m")."', '".date("W")."', '".date("Y")."')");
	}

	if (($wysiwyg == "yes") && ($cpage == "admin.php")) {
	if ((($_GET['act'] == "templates") or ($_GET['act'] == "edittemplates") or ($_GET['act'] == "edittemplates2"))) {
	} else {
	echo wysiwyg();
	}
	}
?>