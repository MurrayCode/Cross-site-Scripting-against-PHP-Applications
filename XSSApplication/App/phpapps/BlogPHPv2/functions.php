<?php
function wysiwyg() {
return '<script language="javascript" type="text/javascript" src="wysiwyg/jscripts/tiny_mce/tiny_mce.js"></script><script language="javascript" type="text/javascript">tinyMCE.init({
mode : "textareas",
		theme : "advanced",
		plugins : "save,emotions,preview,searchreplace",
		theme_advanced_buttons2_add : "preview,forecolor,backcolor,search,replace,emotions",
		theme_advanced_toolbar_location : "bottom",
		theme_advanced_toolbar_align : "center"});</script>';
}

function re_direct($time, $url) {
return "<SCRIPT LANGUAGE='JavaScript'>
redirTime = '".$time."';
redirURL = '".$url."';
function redirTimer() { self.setTimeout('self.location.href = redirURL;',redirTime); }
</script>
<BODY onLoad='redirTimer()'>";
}

function validate_email($email) {
   $regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";
   $valid = 0;
   if (eregi($regexp, $email)) {
   list($username,$domaintld) = split("@",$email);
   if (getmxrr($domaintld,$mxrecords))
   $valid = 1;
   } else {
   $valid = 0;
   }
   return $valid;
}

function profile($user) {
global $pre;

$dt = mysql_fetch_row(mysql_query("SELECT email,level,avatar,username FROM ".$pre."users WHERE id = '".$user."'")) or die(mysql_error());
$var .= "<b><a href='mailto:".$dt[0]."'>".$dt[3]."</a></b><br>";
if ($dt[2]) {
$var .= "<img src='".$dt[2]."' style='border: 1px solid black'>";
}
$var .= "<br>".$dt[1];
return $var;
}

function table($tablename) {
global $dbname;

   $result = mysql_list_tables($dbname);
   $rcount = mysql_num_rows($result);

   for ($i=0;$i<$rcount;$i++) {
       if (mysql_tablename($result, $i)==$tablename) return true;
   }
   return false;
}

function hilite($var) {
return "<span style='background-color: #FFFF00'>".$var."</span>";
}

function upgrade() {
global $pre;

mysql_query("CREATE TABLE `".$pre."subscriptions` (
`id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
`email` TEXT NOT NULL ,
PRIMARY KEY ( `id` ))");
mysql_query("ALTER TABLE `".$pre."stats` ADD `tday` TEXT NOT NULL AFTER `day`");
mysql_query("ALTER TABLE `".$pre."stats` ADD `info` TEXT NOT NULL AFTER `ip`");
mysql_query("ALTER TABLE `".$pre."stats` ADD `referral` TEXT NOT NULL AFTER `page`");
mysql_query("ALTER TABLE `".$pre."comments` CHANGE `aid` `aid` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL");
mysql_query("ALTER TABLE `".$pre."blogs` ADD `status` TEXT NOT NULL");

$sql = mysql_query("SELECT * FROM ".$pre."stats WHERE tday = ''");
while($r = mysql_fetch_array($sql)) {
$var = date("z", mktime(0, 0, 0, $r[month], $r[day], $r[year]));
mysql_query("UPDATE ".$pre."stats SET tday = '".$var."' WHERE id = '".$r[id]."'");
}
mysql_query("UPDATE ".$pre."stats SET info = ' | ' WHERE info = ''");
}

function install() {
global $pre;
global $theuser;

mysql_query("CREATE TABLE `".$pre."subscriptions` (
`id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
`email` TEXT NOT NULL ,
PRIMARY KEY ( `id` ))");

mysql_query("CREATE TABLE `".$pre."blogs` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `subject` text NOT NULL,
  `author` text NOT NULL,
  `cat` text NOT NULL,
  `blog` text NOT NULL,
  `date` text NOT NULL,
  `mdate` text NOT NULL,
  `ydate` text NOT NULL,
  `status` text NOT NULL,
  PRIMARY KEY  (`id`))");

mysql_query("CREATE TABLE `".$pre."cat` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  PRIMARY KEY  (`id`))");

mysql_query("CREATE TABLE `".$pre."comments` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `aid` varchar(11) NOT NULL default '',
  `comment` text NOT NULL,
  `author` text NOT NULL,
  `email` text NOT NULL,
  `url` text NOT NULL,
  `ip` text NOT NULL,
  `date` text NOT NULL,
  PRIMARY KEY  (`id`))");

mysql_query("CREATE TABLE `".$pre."files` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `file` text NOT NULL,
  `type` text NOT NULL,
  `author` text NOT NULL,
  `date` text NOT NULL,
  PRIMARY KEY  (`id`))");

mysql_query("CREATE TABLE `".$pre."links` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `url` text NOT NULL,
  `alt` text NOT NULL,
  `date` text NOT NULL,
  PRIMARY KEY  (`id`))");

mysql_query("CREATE TABLE `".$pre."pages` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `url` text NOT NULL,
  `author` text NOT NULL,
  PRIMARY KEY  (`id`))");


mysql_query("CREATE TABLE `".$pre."stats` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `page` text NOT NULL,
  `referral` text NOT NULL,
  `type` text NOT NULL,
  `ptype` text NOT NULL,
  `username` text NOT NULL,
  `ip` text NOT NULL,
  `info` text NOT NULL,
  `day` text NOT NULL,
  `tday` text NOT NULL,
  `month` text NOT NULL,
  `week` text NOT NULL,
  `year` text NOT NULL,
  PRIMARY KEY  (`id`))");

mysql_query("CREATE TABLE `".$pre."templates` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `template` text NOT NULL,
  PRIMARY KEY  (`id`))");

mysql_query("CREATE TABLE `".$pre."users` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  `level` text NOT NULL,
  `name` text NOT NULL,
  `aim` text NOT NULL,
  `msn` text NOT NULL,
  `yahoo` text NOT NULL,
  `icq` text NOT NULL,
  `gtalk` text NOT NULL,
  `url` text NOT NULL,
  `avatar` text NOT NULL,
  `bday` text NOT NULL,
  `logged` text NOT NULL,
  `date` text NOT NULL,
  `mlist` text NOT NULL,
  PRIMARY KEY  (`id`))");

mysql_query("INSERT INTO `".$pre."blogs` VALUES (null, 'Welcome to BlogPHP', '".$theuser."', 'General', 'On behalf of the <a href=\'http://www.insanevisions.com\'>Insane Visions</a> staff, we hope you enjoy your free copy of BlogPHP. Feel free to delete this and good luck on your new blog site!', '1136254658', '01', '2006', '')");

mysql_query("INSERT INTO `".$pre."cat` VALUES (null, 'General')");

mysql_query("INSERT INTO `".$pre."links` VALUES (null, 'InsaneVisions', 'http://www.insanevisions.com', 'BlogPHP Official Site', '1135369369')");

mysql_query("INSERT INTO `".$pre."pages` VALUES (null, 'Contact Us', 'Here you can fill in your contact information', 'contactus', '".$theuser."')");

mysql_query("INSERT INTO `".$pre."templates` VALUES (null, 'blog', '<table cellpadding=\'0\' cellspacing=\'0\' border=\'0\' align=\'center\' width=\'394\'><tr><td><font face=\'Tahoma\' size=\'4\' color=\'#3A3A3A\'><b>{subject}</b></font><br />Posted: {date}</td></tr><tr><td><br />{blog}<br /><br /></td></tr><tr><td align=\'right\'>{comments}<br /><br />{commentsform}</td></tr></table>')");

mysql_query("INSERT INTO `".$pre."templates` VALUES (null, 'homepage', '')");

mysql_query("UPDATE ".$pre."templates SET template = '<table cellpadding=\'0\' cellspacing=\'0\' border=\'0\' align=\'center\' width=\'394\'><tr><td><font face=\'Tahoma\' size=\'4\' color=\'#3A3A3A\'><b>{link}</b></font><br />Posted: {date}</td></tr><tr><td><br />{blog}<br /><br /></td></tr><tr><td align=\'right\'>{comments} (<font color=\'red\'><b>{cnum}</b></font>) <b>| {pcomment}</b><br /><br /></td></tr><tr><td align=\'center\'>------------------------------<br /><br /></td></tr></table>' WHERE name = 'homepage'");

mysql_query("INSERT INTO `".$pre."templates` VALUES (null, 'comments', '<br /><div align=\'left\'><b>Date</b> {date}<br />\r\n<b>Author</b> <a href=\'{url}\' target=\'popup\'>{author}</a> {ip}<br />\r\n<b>E-Mail</b> {email}<br /><br />\r\n{comment}</div><br /><center><hr /></center><br />')");

mysql_query("INSERT INTO `".$pre."templates` VALUES (null, 'login', '{startform}<b>Username:</b> {username}<br/>\r\n<b>Password:</b> {password}<br />\r\n{submit}{endform}')");

mysql_query("INSERT INTO `".$pre."templates` VALUES (null, 'register', '{startform}<b>Username:</b> {username}<br />\r\n<b>Password:</b> {password}<br />\r\n<b>E-Mail:</b> {email}<br />\r\n{submit}{endform}')");

mysql_query("INSERT INTO `".$pre."templates` VALUES (null, 'search_results', '<tr><td>{link}</td><td>{relevance}%</td><td>{date}</td></tr>')");

mysql_query("INSERT INTO `".$pre."templates` VALUES (null, 'pages', '{title}<br /><br />{content}')");

mysql_query("INSERT INTO `".$pre."templates` VALUES (null, 'header', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<head>

<link rel=\'stylesheet\' type=\'text/css\' href=\'style.css\' />
<title></title>
</head>

<body bgcolor=\'white\' text=\'black\'>

<a name=\'top\'></a><table cellpadding=\'0\' cellspacing=\'0\' border=\'0\' align=\'center\' width=\'700\'><tr><td height=\'66\' width=\'549\' bgcolor=\'#F7DE87\' style=\'border-bottom: 2px solid #F4D361\'>&nbsp;</td><td height=\'66\' width=\'151\' bgcolor=\'#F7DE87\' style=\'border-bottom: 2px solid #F4D361\'><p align=\'right\'><font color=\'white\' size=\'5\' face=\'Tahoma\'>{sitename}&nbsp;</font></p></td></tr><tr><td width=\'540\' valign=\'top\'>

<br /><table cellpadding=\'0\' cellspacing=\'0\' border=\'0\' align=\'left\' width=\'539\'><tr><td height=\'21\' width=\'539\' bgcolor=\'#C5E6FF\' style=\'border-bottom: 2px solid #99D4FF\'>&nbsp;&nbsp;<b><a href=\'#\' onclick=\'this.check=\"true\";cell.style.visibility=\"visible\"\'>+</a>&nbsp;&nbsp;Latest Blog Entries&nbsp;&nbsp;<a href=\'#\' onclick=\'this.check=\"true\";cell.style.visibility=\"hidden\"\'>-</a></b></td></tr><tr><td class=\'formtext\' id=\'cell\' style=\'visibility:visible\'><br /><br />

<table cellpadding=\'0\' cellspacing=\'0\' border=\'0\' align=\'center\' width=\'394\'><tr><td>')");

mysql_query("INSERT INTO `".$pre."templates` VALUES (null, 'footer', '</td></tr></table><br /><br /></td></tr></table></td><td width=\'151\' valign=\'top\' bgcolor=\'#F7F7F7\'><br /><table cellpadding=\'0\' cellspacing=\'0\' border=\'0\' width=\'151\'><tr><td height=\'21\' width=\'151\' bgcolor=\'#D9F773\' style=\'border-bottom: 2px solid #BEE82F\'>&nbsp;&nbsp;<b><a href=\'#\' onclick=\'this.check=\"true\";cell2.style.visibility=\"visible\"\'>+</a>&nbsp;&nbsp;Navigation&nbsp;&nbsp;<a href=\'#\' onclick=\'this.check=\"true\";cell2.style.visibility=\"hidden\"\'>-</a></b></td></tr><tr><td class=\'formtext\' id=\'cell2\' style=\'visibility:visible\'>&nbsp;&nbsp;<a href=\'index.php\'>Home</a><br />&nbsp;&nbsp;<a href=\'members.html\'>Members</a><br />&nbsp;&nbsp;<a href=\'blog-stats.html\'>Stats</a><br />&nbsp;&nbsp;<a href=\'blog-files.html\'>Files</a><br />&nbsp;&nbsp;<a href=\'blog-archive.html\'>Archive</a><br />&nbsp;&nbsp;<a href=\'subscribe.html\'>Subscribe</a>{pages}<br /></td></tr><tr><td height=\'21\' width=\'151\' bgcolor=\'#D9F773\' style=\'border-bottom: 2px solid #BEE82F\'>&nbsp;&nbsp;<b><a href=\'#\' onclick=\'this.check=\"true\";cell3.style.visibility=\"visible\"\'>+</a>&nbsp;&nbsp;Search&nbsp;&nbsp;<a href=\'#\' onclick=\'this.check=\"true\";cell3.style.visibility=\"hidden\"\'>-</a></b></td></tr><tr><td class=\'formtext\' id=\'cell3\' style=\'visibility:visible\'><form action=\'index.php?act=search\' method=\'get\'><br />&nbsp;&nbsp;<input type=\'text\' size=\'20\' name=\'search\' style=\'font-size: 10px; font-family: Tahoma\' />&nbsp;&nbsp;<input type=\'submit\' value=\'Go\' style=\'font-size: 10px; font-family: Tahoma;\' /><br /></form><br /></td></tr><tr><td height=\'21\' width=\'151\' bgcolor=\'#D9F773\' style=\'border-bottom: 2px solid #BEE82F\'>&nbsp;&nbsp;<b><a href=\'#\' onclick=\'this.check=\"true\";cell4.style.visibility=\"visible\"\'>+</a>&nbsp;&nbsp;Categories&nbsp;&nbsp;<a href=\'#\' onclick=\'this.check=\"true\";cell4.style.visibility=\"hidden\"\'>-</a></b></td></tr><tr><td class=\'formtext\' id=\'cell4\' style=\'visibility:visible\'>{catlist}</td></tr><tr><td height=\'21\' width=\'151\' bgcolor=\'#D9F773\' style=\'border-bottom: 2px solid #BEE82F\'>&nbsp;&nbsp;<b><a href=\'#\' onclick=\'this.check=\"true\";cell5.style.visibility=\"visible\"\'>+</a>&nbsp;&nbsp;Links&nbsp;&nbsp;<a href=\'#\' onclick=\'this.check=\"true\";cell5.style.visibility=\"hidden\"\'>-</a></b></td></tr><tr><td class=\'formtext\' id=\'cell5\' style=\'visibility:visible\'>{links}</td></tr>
{admin}
{welcomeguest}
</table></td></tr><tr><td height=\'23\' width=\'549\' bgcolor=\'#F7DE87\' style=\'border-bottom: 2px solid #F4D361\'>&nbsp;&nbsp;<a href=\'#\' onclick=\'this.check=\"true\";cell.style.visibility=\"visible\";cell2.style.visibility=\"visible\";cell3.style.visibility=\"visible\";cell4.style.visibility=\"visible\";cell5.style.visibility=\"visible\";cell6.style.visibility=\"visible\"\'><font color=\'black\'>+</font></a>&nbsp;&nbsp;Copyright &copy;2006 Powered by <a href=\'http://www.blogphp.net\'><font color=\'black\'>www.blogphp.net</font></a></td><td height=\'23\' width=\'151\' bgcolor=\'#F7DE87\' style=\'border-bottom: 2px solid #F4D361\' align=\'right\'><a href=\'#top\'><font color=\'black\'>Back to Top</font></a>&nbsp;&nbsp;<a href=\'#\' onclick=\'this.check=\"true\";cell.style.visibility=\"hidden\";cell2.style.visibility=\"hidden\";cell3.style.visibility=\"hidden\";cell4.style.visibility=\"hidden\";cell5.style.visibility=\"hidden\";cell6.style.visibility=\"hidden\"\'><font color=\'black\'>-</font></a>&nbsp;&nbsp;</td></tr></table></body></html>')");
}

function headera() {
global $pre;
global $level;
global $sitename;
global $color;
$temp = mysql_fetch_row(mysql_query("SELECT template FROM ".$pre."templates WHERE name = 'header'"));

$pa[0] = "/{pages}/";
$pa[1] = "/{catlist}/";
$pa[2] = "/{links}/";
$pa[3] = "/{admin}/";
$pa[4] = "/{welcomeguest}/";
$pa[5] = "/{sitename}/";

$ra[0] = "";
$ra[1] = "";
$ra[2] = "";
$sql2 = mysql_query("SELECT * FROM ".$pre."pages ORDER BY `title` ASC");
while($c = mysql_fetch_array($sql2)) {
$ra[0] .= "<br />&nbsp;&nbsp;<a href='page-".$c[url].".html'>".$c[title]."</a>";
}
$sql1 = mysql_query("SELECT * FROM ".$pre."cat ORDER BY `name` ASC");
while($a = mysql_fetch_array($sql1)) {
$ra[1] .= "&nbsp;&nbsp;<a href='cat-".$a[name].".html'>".$a[name]."</a><br />";
}
$sql2 = mysql_query("SELECT * FROM ".$pre."links ORDER BY `name` ASC");
while($b = mysql_fetch_array($sql2)) {
$ra[2] .= "&nbsp;&nbsp;<a href='".$b[url]."' title='".$b[alt]."'>".$b[name]."</a><br />";
}
if (($level == "Admin") or ($level == "Author")) {
$ra[3] .= "<tr><td height=\"21\" width=\"151\" bgcolor=\"".$color."\">&nbsp;&nbsp;<b><a href=\"#\" onclick=\"this.checked='true';cell6.style.visibility='visible'\">+</a>&nbsp;&nbsp;<a href='admin.php'><font color='black'>Admin</font></a>&nbsp;&nbsp;<a href=\"#\" onclick=\"this.checked='true';cell6.style.visibility='hidden'\">-</a></b></td></tr><tr><td class='formtext' id='cell6' style='visibility:visible'>&nbsp;&nbsp;<a href=\"admin.php\">Blogs</a><br />&nbsp;&nbsp;<a href=\"admin.php?act=cat\">Categories</a><br />&nbsp;&nbsp;<a href=\"admin.php?act=comments\">Comments</a><br />&nbsp;&nbsp;<a href=\"admin.php?act=files\">Files</a><br />&nbsp;&nbsp;<a href=\"admin.php?act=pages\">Pages</a><br />&nbsp;&nbsp;<a href=\"admin.php?act=links\">Links</a><br />";
if ($level == "Admin") {
$ra[3] .= "&nbsp;&nbsp;<a href=\"admin.php?act=users\">Users</a><br />&nbsp;&nbsp;<a href=\"admin.php?act=templates\">Templates</a><br />";
}
$ra[3] .= "<br />&nbsp;&nbsp;<a href=\"admin.php?act=support\">Support</a><br />&nbsp;&nbsp;<a href=\"admin.php?act=plugins\">Plugins</a><br />&nbsp;&nbsp;<a href=\"admin.php?act=convert\">Converters</a><br />&nbsp;&nbsp;<a href='blog-stats.html'>Stats</a><br /><br /><br /><p align='center'><a href='rss.php'><img src='rss.gif' border='0' style='border: 1px solid black'></a></p></td></tr>";
}
if (($level == "") or ($level == "Member")) {
$ra[3] = "";
}
if ($level == "") {
$ra[4] .= "<tr><td><br /><br /><center>Welcome <b>guest</b>! Please <a href='login.html'>login</a> or <a href='register.html'>register</a></center><br /><br /><br /><p align='center'><a href='rss.php'><img src='rss.gif' border='0' style='border: 1px solid black'></a></p></td></tr>";
} else {
$ra[4] = "";
}
$ra[5] = $sitename;
return preg_replace($pa, $ra, stripslashes($temp[0]));
}

function install_header() {
return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<!DOCTYPE html 
    PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" 
    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">
<head>

<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\" />
<title></title>
</head>

<body bgcolor=\"white\" text=\"black\">

<a name=\"top\"></a><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"700\"><tr><td height=\"66\" width=\"549\" bgcolor=\"#F7DE87\" style=\"border-bottom: 2px solid #F4D361\">&nbsp;</td><td height=\"66\" width=\"151\" bgcolor=\"#F7DE87\" style=\"border-bottom: 2px solid #F4D361\"><div align=\"right\"><font color=\"white\" size=\"5\" face=\"Tahoma\" />Blog<b>PHP</b>&nbsp;</div></td></tr><tr><td width=\"540\" valign=\"top\"><br /><br />

<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"394\"><tr><td>";
}

function footer() {
global $pre;
global $level;
global $sitename;
global $color;
$temp = mysql_fetch_row(mysql_query("SELECT template FROM ".$pre."templates WHERE name = 'footer'"));

$pa[0] = "/{pages}/";
$pa[1] = "/{catlist}/";
$pa[2] = "/{links}/";
$pa[3] = "/{admin}/";
$pa[4] = "/{welcomeguest}/";
$pa[5] = "/{sitename}/";

$ra[0] = "";
$ra[1] = "";
$ra[2] = "";
$sql2 = mysql_query("SELECT * FROM ".$pre."pages ORDER BY `title` ASC");
while($c = mysql_fetch_array($sql2)) {
$ra[0] .= "<br />&nbsp;&nbsp;<a href='page-".$c[url].".html'>".$c[title]."</a>";
}
$sql1 = mysql_query("SELECT * FROM ".$pre."cat ORDER BY `name` ASC");
while($a = mysql_fetch_array($sql1)) {
$ra[1] .= "&nbsp;&nbsp;<a href='cat-".$a[name].".html'>".$a[name]."</a><br />";
}
$sql2 = mysql_query("SELECT * FROM ".$pre."links ORDER BY `name` ASC");
while($b = mysql_fetch_array($sql2)) {
$ra[2] .= "&nbsp;&nbsp;<a href='".$b[url]."' title='".$b[alt]."'>".$b[name]."</a><br />";
}
if (($level == "Admin") or ($level == "Author")) {
$ra[3] .= "<tr><td height=\"21\" width=\"151\" bgcolor=\"".$color."\">&nbsp;&nbsp;<b><a href=\"#\" onclick=\"this.checked='true';cell6.style.visibility='visible'\">+</a>&nbsp;&nbsp;<a href='admin.php'><font color='black'>Admin</font></a>&nbsp;&nbsp;<a href=\"#\" onclick=\"this.checked='true';cell6.style.visibility='hidden'\">-</a></b></td></tr><tr><td class='formtext' id='cell6' style='visibility:visible'>&nbsp;&nbsp;<a href=\"admin.php\">Blogs</a><br />&nbsp;&nbsp;<a href=\"admin.php?act=cat\">Categories</a><br />&nbsp;&nbsp;<a href=\"admin.php?act=comments\">Comments</a><br />&nbsp;&nbsp;<a href=\"admin.php?act=files\">Files</a><br />&nbsp;&nbsp;<a href=\"admin.php?act=pages\">Pages</a><br />&nbsp;&nbsp;<a href=\"admin.php?act=links\">Links</a><br />";
if ($level == "Admin") {
$ra[3] .= "&nbsp;&nbsp;<a href=\"admin.php?act=users\">Users</a><br />&nbsp;&nbsp;<a href=\"admin.php?act=templates\">Templates</a><br />";
}
$ra[3] .= "<br />&nbsp;&nbsp;<a href=\"admin.php?act=support\">Support</a><br />&nbsp;&nbsp;<a href=\"admin.php?act=plugins\">Plugins</a><br />&nbsp;&nbsp;<a href=\"admin.php?act=convert\">Converters</a><br />&nbsp;&nbsp;<a href='blog-stats.html'>Stats</a><br /><br /><br /><p align='center'><a href='rss.php'><img src='rss.gif' border='0' style='border: 1px solid black'></a></p></td></tr>";
}
if (($level == "") or ($level == "Member")) {
$ra[3] = "";
}
if ($level == "") {
$ra[4] = "<tr><td><br /><br /><center>Welcome <b>guest</b>! Please <a href='login.html'>login</a> or <a href='register.html'>register</a></center><br /><br /><br /><p align='center'><a href='rss.php'><img src='rss.gif' border='0' style='border: 1px solid black'></a></p></td></tr>";
} else {
$ra[4] = "";
}
$ra[5] .= $sitename;
return preg_replace($pa, $ra, stripslashes($temp[0]));
}

function bbcode($string) {
//$string = phpa($string);

$patterns = array(
'`\[b\](.+?)\[/b\]`is',
'`\[i\](.+?)\[/i\]`is',
'`\[u\](.+?)\[/u\]`is',
'`\[strike\](.+?)\[/strike\]`is',
'`\[color=#([0-9a-fA-F]{6})](.+?)[/color]`is',
'`\[email\](.+?)\[/email\]`is',
'`\[img\](.+?)\[/img\]`is',
'`\[url=([a-z0-9]+://)([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*?)?)\](.*?)\[/url\]`si',
'`\[url\]([a-z0-9]+?://){1}([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)\[/url\]`si',
'`\[url\]((www|ftp)\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*?)?)\[/url\]`si',
'`\[flash=([0-9]+),([0-9]+)\](.+?)\[/flash\]`is',
'`\[quote\](.+?)\[/quote\]`is',
'`\[indent](.+?)\[/indent\]`is',
'`\[code=([a-z]+)](.*?)[/code]`si',
'`\[size=([1-6]+)\](.+?)\[/size\]`is'
);

$replaces = array(
'<strong>\\1</strong>',
'<em>\\1</em>',
'<span style="border-bottom: 1px dotted">\\1</span>',
'<strike>\\1</strike>',
'<span style="color:#\1;">\2</span>',
'<a href="mailto:\1">\1</a>',
'<img src="\1" alt="" style="border:0px;" />',
'<a href="\1\2">\6</a>',
'<a href="\1\2">\1\2</a>',
'<a href="http://\1">\1</a>',
'<object width="\1" height="\2"><param name="movie" value="\3" /><embed src="\3" width="\1" height="\2"></embed></object>',
'<table cellspacing="0" cellpadding="3" border="0" align="center" width="90%"><tr><td class="quote1"><b>Quote:</b></td></tr><tr><td class="quote">\1</td></tr></table>',
'<pre>\\1</pre>',
'<ul><li class=”code”>\1<br/>\2</li></ul>',
'<h\1>\2</h\1>'                               
);

$prev_string = “”;
while ($prev_string != $string) {
  $prev_string = $string;
  $string = preg_replace($patterns, $replaces, $string);
}
return $string;
}

// CLASS BELOW - FOR OPERATING SYSTEM AND BROWSER INFO
    /****************************************************************************************
    *                Trieda na pracu s identifikatorom HTTP_USER_AGENT                      *
    *               (c)2003 TOTH Richard, riso.toth@seznam.cz, Slovakia                     *
    *                                    rev 1.1                                            *
    *****************************************************************************************
    *   Operating systems: Win3.1, Win3.11, Win95, Win98, WinME, WinNT, Win2000, WinXP,     *
    *                      Win.NET, WinCE,                                                  *
    *                      MacOSX, MacPPC, Mac68K,                                          *
    *                      Linux, FreeBSD, NetBSD, Unix, HP-UX, SunOS, IRIX, OSF1,          *
    *                      QNX Photon, OS/2, Amiga, Symbian, Palm,                          *
    *                      Liberate, Sega Dreamcast, WebTV, PowerTV, Prodigy                *
    *   Browsers: Amaya, AOL, AWeb, Beonex, Camino, Cyberdog, Dillo, Doris, ELinks, Emacs,  *
    *             Firebird, FrontPage, Galeon, Chimera, iCab, Internet Explorer, Konqueror, *
    *             Liberate, Links, Lycoris Desktop/LX, Lynx, Netcaptor, Netpliance,         *
    *             Netscape, Mozzila, OffByOne, Opera, Pocket Inetrnet Explorer,             *
    *             PowerBrowser, Phoenix, PlanetWeb, PowerTV, Prodigy, Voyager, QuickTime,   *
    *             Safari, Tango, WebExplorer, WebTV, Yandex                                 *
    ****************************************************************************************/

    class BrowserInfo
    {
        var $USER_AGENT = ""; // STRING - USER_AGENT_STRING
        var $OS = ""; // STRING - operating system
        var $OS_Version = ""; // STRING - operating system version
        var $Browser = "" ;// STRING - Browser name
        var $Browser_Version = ""; // STRING - Browser version
        var $NET_CLR = false; // BOOL - .NET Common Language Runtime
        var $Resolved = false; // BOOL - resolving proceeded
        
        
        
        // CONSTRUCTOR - Main function to resolving user agents
        function BrowserInfo($UA) // PUBLIC - BrowserInfo((string) USER_AGENT_STRING)
        {
            $this->USER_AGENT = $UA;
            $this->Resolve();
            $this->Resolved = true;
        }
        
        
        // FUNCTION - Resolving user agents
        function Resolve() // PUBLIC - Resolve()
        {
            $this->Resolved = false;
            $this->OS = "";
            $this->OS_Version = "";
            $this->NET_CLR = false;
            
            $this->_GetOperatingSystem();
            $this->_GetBrowser();
            $this->_GetNET_CLR();
        }
        
        /***********************************************************************************/
        
        // PROTECTED - _GetNET_CLR()
        function _GetNET_CLR()
        {
            if (eregi("NET CLR",$this->USER_AGENT)) {$this->NET_CLR = true;}
        }
        
        
        // PROTECTED - _GetOperatingSystem()
        function _GetOperatingSystem()
        {
            if (eregi("win",$this->USER_AGENT))
            {
                $this->OS = "Windows";
                if ((eregi("Windows 95",$this->USER_AGENT)) || (eregi("Win95",$this->USER_AGENT))) {$this->OS_Version = "95";}
                elseif (eregi("Windows ME",$this->USER_AGENT) || (eregi("Win 9x 4.90",$this->USER_AGENT))) {$this->OS_Version = "ME";}
                elseif ((eregi("Windows 98",$this->USER_AGENT)) || (eregi("Win98",$this->USER_AGENT))) {$this->OS_Version = "98";}
                elseif ((eregi("Windows NT 5.0",$this->USER_AGENT)) || (eregi("WinNT5.0",$this->USER_AGENT)) || (eregi("Windows 2000",$this->USER_AGENT)) || (eregi("Win2000",$this->USER_AGENT))) {$this->OS_Version = "2000";}
                elseif ((eregi("Windows NT 5.1",$this->USER_AGENT)) || (eregi("WinNT5.1",$this->USER_AGENT)) || (eregi("Windows XP",$this->USER_AGENT))) {$this->OS_Version = "XP";}
                elseif ((eregi("Windows NT 5.2",$this->USER_AGENT)) || (eregi("WinNT5.2",$this->USER_AGENT))) {$this->OS_Version = ".NET 2003";}
                elseif (eregi("Windows CE",$this->USER_AGENT)) {$this->OS_Version = "CE";}
                elseif (eregi("Win3.11",$this->USER_AGENT)) {$this->OS_Version = "3.11";}
                elseif (eregi("Win3.1",$this->USER_AGENT)) {$this->OS_Version = "3.1";}
                elseif ((eregi("Windows NT",$this->USER_AGENT)) || (eregi("WinNT",$this->USER_AGENT))) {$this->OS_Version = "NT";}
            }
            elseif (eregi("mac",$this->USER_AGENT))
            {
                $this->OS = "MacIntosh";
                if ((eregi("Mac OS X",$this->USER_AGENT)) || (eregi("Mac 10",$this->USER_AGENT))) {$this->OS_Version = "OS X";}
                elseif ((eregi("PowerPC",$this->USER_AGENT)) || (eregi("PPC",$this->USER_AGENT))) {$this->OS_Version = "PPC";}
                elseif ((eregi("68000",$this->USER_AGENT)) || (eregi("68k",$this->USER_AGENT))) {$this->OS_Version = "68K";}
            }
            elseif (eregi("linux",$this->USER_AGENT))
            {
                $this->OS = "Linux";
                if (eregi("i686",$this->USER_AGENT)) {$this->OS_Version = "i686";}
                elseif (eregi("i586",$this->USER_AGENT)) {$this->OS_Version = "i586";}
                elseif (eregi("i486",$this->USER_AGENT)) {$this->OS_Version = "i486";}
                elseif (eregi("i386",$this->USER_AGENT)) {$this->OS_Version = "i386";}
            }
            elseif (eregi("sunos",$this->USER_AGENT))
            {
                $this->OS = "SunOS";
            }
            elseif (eregi("hp-ux",$this->USER_AGENT))
            {
                $this->OS = "HP-UX";
            }
            elseif (eregi("osf1",$this->USER_AGENT))
            {
                $this->OS = "OSF1";
            }
            elseif (eregi("freebsd",$this->USER_AGENT))
            {
                $this->OS = "FreeBSD";
                if (eregi("i686",$this->USER_AGENT)) {$this->OS_Version = "i686";}
                elseif (eregi("i586",$this->USER_AGENT)) {$this->OS_Version = "i586";}
                elseif (eregi("i486",$this->USER_AGENT)) {$this->OS_Version = "i486";}
                elseif (eregi("i386",$this->USER_AGENT)) {$this->OS_Version = "i386";}
            }
            elseif (eregi("netbsd",$this->USER_AGENT))
            {
                $this->OS = "NetBSD";
                if (eregi("i686",$this->USER_AGENT)) {$this->OS_Version = "i686";}
                elseif (eregi("i586",$this->USER_AGENT)) {$this->OS_Version = "i586";}
                elseif (eregi("i486",$this->USER_AGENT)) {$this->OS_Version = "i486";}
                elseif (eregi("i386",$this->USER_AGENT)) {$this->OS_Version = "i386";}
            }
            elseif (eregi("irix",$this->USER_AGENT))
            {
                $this->OS = "IRIX";
            }
            elseif (eregi("os/2",$this->USER_AGENT))
            {
                $this->OS = "OS/2";
            }
            elseif (eregi("amiga",$this->USER_AGENT))
            {
                $this->OS = "Amiga";
            }
            elseif (eregi("liberate",$this->USER_AGENT))
            {
                $this->OS = "Liberate";
            }
            elseif (eregi("qnx",$this->USER_AGENT))
            {
                $this->OS = "QNX";
                if (eregi("photon",$this->USER_AGENT)) {$this->OS_Version = "Photon";}
            }
            elseif (eregi("dreamcast",$this->USER_AGENT))
            {
                $this->OS = "Sega Dreamcast";
            }
            elseif (eregi("palm",$this->USER_AGENT))
            {
                $this->OS = "Palm";
            }
            elseif (eregi("powertv",$this->USER_AGENT))
            {
                $this->OS = "PowerTV";
            }
            elseif (eregi("prodigy",$this->USER_AGENT))
            {
                $this->OS = "Prodigy";
            }
            elseif (eregi("symbian",$this->USER_AGENT))
            {
                $this->OS = "Symbian";
            }
            elseif (eregi("unix",$this->USER_AGENT))
            {
                $this->OS = "Unix";
            }
            elseif (eregi("webtv",$this->USER_AGENT))
            {
                $this->OS = "WebTV";
            }
        }
        
        
        // PROTECTED - _GetBrowser()
        function _GetBrowser()
        {
            if (eregi("amaya",$this->USER_AGENT))
            {
                $this->Browser = "amaya";
                if (eregi("amaya/5.0",$this->USER_AGENT)) {$this->Browser_Version = "5.0";}
                elseif (eregi("amaya/5.1",$this->USER_AGENT)) {$this->Browser_Version = "5.1";}
                elseif (eregi("amaya/5.2",$this->USER_AGENT)) {$this->Browser_Version = "5.2";}
                elseif (eregi("amaya/5.3",$this->USER_AGENT)) {$this->Browser_Version = "5.3";}
                elseif (eregi("amaya/6.0",$this->USER_AGENT)) {$this->Browser_Version = "6.0";}
                elseif (eregi("amaya/6.1",$this->USER_AGENT)) {$this->Browser_Version = "6.1";}
                elseif (eregi("amaya/6.2",$this->USER_AGENT)) {$this->Browser_Version = "6.2";}
                elseif (eregi("amaya/6.3",$this->USER_AGENT)) {$this->Browser_Version = "6.3";}
                elseif (eregi("amaya/6.4",$this->USER_AGENT)) {$this->Browser_Version = "6.4";}
                elseif (eregi("amaya/7.0",$this->USER_AGENT)) {$this->Browser_Version = "7.0";}
                elseif (eregi("amaya/7.1",$this->USER_AGENT)) {$this->Browser_Version = "7.1";}
                elseif (eregi("amaya/7.2",$this->USER_AGENT)) {$this->Browser_Version = "7.2";}
                elseif (eregi("amaya/8.0",$this->USER_AGENT)) {$this->Browser_Version = "8.0";}
            }
            elseif ((eregi("aol",$this->USER_AGENT)) && !(eregi("msie",$this->USER_AGENT)))
            {
                $this->Browser = "AOL";
                if ((eregi("aol 7.0",$this->USER_AGENT)) || (eregi("aol/7.0",$this->USER_AGENT))) {$this->Browser_Version = "7.0";}
            }
            elseif ((eregi("aweb",$this->USER_AGENT)) || (eregi("amigavoyager",$this->USER_AGENT)))
            {
                $this->Browser = "AWeb";
                if (eregi("voyager/1.0",$this->USER_AGENT)) {$this->Browser_Version = "1.0";}
                elseif (eregi("voyager/2.95",$this->USER_AGENT)) {$this->Browser_Version = "2.95";}
                elseif ((eregi("voyager/3",$this->USER_AGENT)) || (eregi("aweb/3.0",$this->USER_AGENT))) {$this->Browser_Version = "3.0";}
                elseif (eregi("aweb/3.1",$this->USER_AGENT)) {$this->Browser_Version = "3.1";}
                elseif (eregi("aweb/3.2",$this->USER_AGENT)) {$this->Browser_Version = "3.2";}
                elseif (eregi("aweb/3.3",$this->USER_AGENT)) {$this->Browser_Version = "3.3";}
                elseif (eregi("aweb/3.4",$this->USER_AGENT)) {$this->Browser_Version = "3.4";}
                elseif (eregi("aweb/3.9",$this->USER_AGENT)) {$this->Browser_Version = "3.9";}
            }
            elseif (eregi("beonex",$this->USER_AGENT))
            {
                $this->Browser = "Beonex";
                if (eregi("beonex/0.8.2",$this->USER_AGENT)) {$this->Browser_Version = "0.8.2";}
                elseif (eregi("beonex/0.8.1",$this->USER_AGENT)) {$this->Browser_Version = "0.8.1";}
                elseif (eregi("beonex/0.8",$this->USER_AGENT)) {$this->Browser_Version = "0.8";}
            }
            elseif (eregi("camino",$this->USER_AGENT))
            {
                $this->Browser = "Camino";
                if (eregi("camino/0.7",$this->USER_AGENT)) {$this->Browser_Version = "0.7";}
            }
            elseif (eregi("cyberdog",$this->USER_AGENT))
            {
                $this->Browser = "Cyberdog";
                if (eregi("cybergog/1.2",$this->USER_AGENT)) {$this->Browser_Version = "1.2";}
                elseif (eregi("cyberdog/2.0",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
                elseif (eregi("cyberdog/2.0b1",$this->USER_AGENT)) {$this->Browser_Version = "2.0b1";}
            }
            elseif (eregi("dillo",$this->USER_AGENT))
            {
                $this->Browser = "Dillo";
                if (eregi("dillo/0.6.6",$this->USER_AGENT)) {$this->Browser_Version = "0.6.6";}
                elseif (eregi("dillo/0.7.2",$this->USER_AGENT)) {$this->Browser_Version = "0.7.2";}
                elseif (eregi("dillo/0.7.3",$this->USER_AGENT)) {$this->Browser_Version = "0.7.3";}
            }
            elseif (eregi("doris",$this->USER_AGENT))
            {
                $this->Browser = "Doris";
                if (eregi("doris/1.10",$this->USER_AGENT)) {$this->Browser_Version = "1.10";}
            }
            elseif (eregi("emacs",$this->USER_AGENT))
            {
                $this->Browser = "Emacs";
                if (eregi("emacs/w3/2",$this->USER_AGENT)) {$this->Browser_Version = "2";}
                elseif (eregi("emacs/w3/3",$this->USER_AGENT)) {$this->Browser_Version = "3";}
                elseif (eregi("emacs/w3/4",$this->USER_AGENT)) {$this->Browser_Version = "4";}
            }
            elseif (eregi("firebird",$this->USER_AGENT))
            {
                $this->Browser = "Firebird";
                if ((eregi("firebird/0.6",$this->USER_AGENT)) || (eregi("browser/0.6",$this->USER_AGENT))) {$this->Browser_Version = "0.6";}
                elseif (eregi("firebird/0.7",$this->USER_AGENT)) {$this->Browser_Version = "0.7";}
            }
            elseif (eregi("frontpage",$this->USER_AGENT))
            {
                $this->Browser = "FrontPage";
                if ((eregi("express 2",$this->USER_AGENT)) || (eregi("frontpage 2",$this->USER_AGENT))) {$this->Browser_Version = "2";}
                elseif (eregi("frontpage 3",$this->USER_AGENT)) {$this->Browser_Version = "3";}
                elseif (eregi("frontpage 4",$this->USER_AGENT)) {$this->Browser_Version = "4";}
                elseif (eregi("frontpage 5",$this->USER_AGENT)) {$this->Browser_Version = "5";}
                elseif (eregi("frontpage 6",$this->USER_AGENT)) {$this->Browser_Version = "6";}
            }
            elseif (eregi("galeon",$this->USER_AGENT))
            {
                $this->Browser = "Galeon";
                if (eregi("galeon 0.1",$this->USER_AGENT)) {$this->Browser_Version = "0.1";}
                elseif (eregi("galeon/0.11.1",$this->USER_AGENT)) {$this->Browser_Version = "0.11.1";}
                elseif (eregi("galeon/0.11.2",$this->USER_AGENT)) {$this->Browser_Version = "0.11.2";}
                elseif (eregi("galeon/0.11.3",$this->USER_AGENT)) {$this->Browser_Version = "0.11.3";}
                elseif (eregi("galeon/0.11.5",$this->USER_AGENT)) {$this->Browser_Version = "0.11.5";}
                elseif (eregi("galeon/0.12.8",$this->USER_AGENT)) {$this->Browser_Version = "0.12.8";}
                elseif (eregi("galeon/0.12.7",$this->USER_AGENT)) {$this->Browser_Version = "0.12.7";}
                elseif (eregi("galeon/0.12.6",$this->USER_AGENT)) {$this->Browser_Version = "0.12.6";}
                elseif (eregi("galeon/0.12.5",$this->USER_AGENT)) {$this->Browser_Version = "0.12.5";}
                elseif (eregi("galeon/0.12.4",$this->USER_AGENT)) {$this->Browser_Version = "0.12.4";}
                elseif (eregi("galeon/0.12.3",$this->USER_AGENT)) {$this->Browser_Version = "0.12.3";}
                elseif (eregi("galeon/0.12.2",$this->USER_AGENT)) {$this->Browser_Version = "0.12.2";}
                elseif (eregi("galeon/0.12.1",$this->USER_AGENT)) {$this->Browser_Version = "0.12.1";}
                elseif (eregi("galeon/0.12",$this->USER_AGENT)) {$this->Browser_Version = "0.12";}
                elseif ((eregi("galeon/1",$this->USER_AGENT)) || (eregi("galeon 1.0",$this->USER_AGENT))) {$this->Browser_Version = "1.0";}
            }
            elseif (eregi("chimera",$this->USER_AGENT))
            {
                $this->Browser = "Chimera";
                if (eregi("chimera/0.7",$this->USER_AGENT)) {$this->Browser_Version = "0.7";}
                elseif (eregi("chimera/0.6",$this->USER_AGENT)) {$this->Browser_Version = "0.6";}
                elseif (eregi("chimera/0.5",$this->USER_AGENT)) {$this->Browser_Version = "0.5";}
                elseif (eregi("chimera/0.4",$this->USER_AGENT)) {$this->Browser_Version = "0.4";}
            }
            elseif (eregi("icab",$this->USER_AGENT))
            {
                $this->Browser = "iCab";
                if (eregi("icab/2.7.1",$this->USER_AGENT)) {$this->Browser_Version = "2.7.1";}
                elseif (eregi("icab/2.8.1",$this->USER_AGENT)) {$this->Browser_Version = "2.8.1";}
                elseif (eregi("icab/2.8.2",$this->USER_AGENT)) {$this->Browser_Version = "2.8.2";}
                elseif (eregi("icab 2.9",$this->USER_AGENT)) {$this->Browser_Version = "2.9";}
                elseif (eregi("icab 2.0",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
            }
            elseif (eregi("konqueror",$this->USER_AGENT))
            {
                $this->Browser = "Konqueror";
                if (eregi("konqueror/3.1",$this->USER_AGENT)) {$this->Browser_Version = "3.1";}
                elseif (eregi("konqueror/3",$this->USER_AGENT)) {$this->Browser_Version = "3.0";}
                elseif (eregi("konqueror/2.2",$this->USER_AGENT)) {$this->Browser_Version = "2.2";}
                elseif (eregi("konqueror/2.1",$this->USER_AGENT)) {$this->Browser_Version = "2.1";}
                elseif (eregi("konqueror/1.1",$this->USER_AGENT)) {$this->Browser_Version = "1.1";}
            }
            elseif (eregi("liberate",$this->USER_AGENT))
            {
                $this->Browser = "Liberate";
                if (eregi("dtv 1.2",$this->USER_AGENT)) {$this->Browser_Version = "1.2";}
                elseif (eregi("dtv 1.1",$this->USER_AGENT)) {$this->Browser_Version = "1.1";}
            }
            elseif (eregi("desktop/lx",$this->USER_AGENT))
            {
                $this->Browser = "Lycoris Desktop/LX";
            }
            elseif (eregi("netcaptor",$this->USER_AGENT))
            {
                $this->Browser = "Netcaptor";
                if (eregi("netcaptor 7.0",$this->USER_AGENT)) {$this->Browser_Version = "7.0";}
                elseif (eregi("netcaptor 7.1",$this->USER_AGENT)) {$this->Browser_Version = "7.1";}
                elseif (eregi("netcaptor 7.2",$this->USER_AGENT)) {$this->Browser_Version = "7.2";}
            }
            elseif (eregi("netpliance",$this->USER_AGENT))
            {
                $this->Browser = "Netpliance";
            }
            elseif (eregi("netscape",$this->USER_AGENT)) // (1) netscape nie je prilis detekovatelny....
            {
                $this->Browser = "Netscape";
                if (eregi("netscape/7.1",$this->USER_AGENT)) {$this->Browser_Version = "7.1";}
                elseif (eregi("netscape/7.0",$this->USER_AGENT)) {$this->Browser_Version = "7.0";}
                elseif (eregi("netscape6/6.2",$this->USER_AGENT)) {$this->Browser_Version = "6.2";}
                elseif (eregi("netscape6/6.1",$this->USER_AGENT)) {$this->Browser_Version = "6.1";}
                elseif (eregi("netscape6/6.0",$this->USER_AGENT)) {$this->Browser_Version = "6.0";}
            }
            elseif ((eregi("mozilla/5.0",$this->USER_AGENT)) && (eregi("rv:",$this->USER_AGENT)) && (eregi("gecko/",$this->USER_AGENT))) // mozilla je troschu zlozitejsia na detekciu
            {
                $this->Browser = "Mozilla";
                if (eregi("rv:1.0",$this->USER_AGENT)) {$this->Browser_Version = "1.0";}
                elseif (eregi("rv:1.1",$this->USER_AGENT)) {$this->Browser_Version = "1.1";}
                elseif (eregi("rv:1.2",$this->USER_AGENT)) {$this->Browser_Version = "1.2";}
                elseif (eregi("rv:1.3",$this->USER_AGENT)) {$this->Browser_Version = "1.3";}
                elseif (eregi("rv:1.4",$this->USER_AGENT)) {$this->Browser_Version = "1.4";}
                elseif (eregi("rv:1.5",$this->USER_AGENT)) {$this->Browser_Version = "1.5";}
                elseif (eregi("rv:1.6",$this->USER_AGENT)) {$this->Browser_Version = "1.6";}
            }
            elseif (eregi("offbyone",$this->USER_AGENT))
            {
                $this->Browser = "OffByOne";
                if (eregi("mozilla/4.7",$this->USER_AGENT)) {$this->Browser_Version = "3.4";}
            }
            elseif (eregi("omniweb",$this->USER_AGENT))
            {
                $this->Browser = "OmniWeb";
                if (eregi("omniweb/4.5",$this->USER_AGENT)) {$this->Browser_Version = "4.5";}
                elseif (eregi("omniweb/4.4",$this->USER_AGENT)) {$this->Browser_Version = "4.4";}
                elseif (eregi("omniweb/4.3",$this->USER_AGENT)) {$this->Browser_Version = "4.3";}
                elseif (eregi("omniweb/4.2",$this->USER_AGENT)) {$this->Browser_Version = "4.2";}
                elseif (eregi("omniweb/4.1",$this->USER_AGENT)) {$this->Browser_Version = "4.1";}
            }
            elseif (eregi("opera",$this->USER_AGENT))
            {
                $this->Browser = "Opera";
                if ((eregi("opera/7.21",$this->USER_AGENT)) || (eregi("opera 7.21",$this->USER_AGENT))) {$this->Browser_Version = "7.21";}
                elseif ((eregi("opera/7.20",$this->USER_AGENT)) || (eregi("opera 7.20",$this->USER_AGENT))) {$this->Browser_Version = "7.20";}
                elseif ((eregi("opera/7.11",$this->USER_AGENT)) || (eregi("opera 7.11",$this->USER_AGENT))) {$this->Browser_Version = "7.11";}
                elseif ((eregi("opera/7.10",$this->USER_AGENT)) || (eregi("opera 7.10",$this->USER_AGENT))) {$this->Browser_Version = "7.10";}
                elseif ((eregi("opera/7.03",$this->USER_AGENT)) || (eregi("opera 7.03",$this->USER_AGENT))) {$this->Browser_Version = "7.03";}
                elseif ((eregi("opera/7.02",$this->USER_AGENT)) || (eregi("opera 7.02",$this->USER_AGENT))) {$this->Browser_Version = "7.02";}
                elseif ((eregi("opera/7.01",$this->USER_AGENT)) || (eregi("opera 7.01",$this->USER_AGENT))) {$this->Browser_Version = "7.01";}
                elseif ((eregi("opera/7.0",$this->USER_AGENT)) || (eregi("opera 7.0",$this->USER_AGENT))) {$this->Browser_Version = "7.0";}
                elseif ((eregi("opera/6.12",$this->USER_AGENT)) || (eregi("opera 6.12",$this->USER_AGENT))) {$this->Browser_Version = "6.12";}
                elseif ((eregi("opera/6.11",$this->USER_AGENT)) || (eregi("opera 6.11",$this->USER_AGENT))) {$this->Browser_Version = "6.11";}
                elseif ((eregi("opera/6.1",$this->USER_AGENT)) || (eregi("opera 6.1",$this->USER_AGENT))) {$this->Browser_Version = "6.1";}
                elseif ((eregi("opera/6.0",$this->USER_AGENT)) || (eregi("opera 6.0",$this->USER_AGENT))) {$this->Browser_Version = "6.0";}
                elseif ((eregi("opera/5.12",$this->USER_AGENT)) || (eregi("opera 5.12",$this->USER_AGENT))) {$this->Browser_Version = "5.12";}
                elseif ((eregi("opera/5.0",$this->USER_AGENT)) || (eregi("opera 5.0",$this->USER_AGENT))) {$this->Browser_Version = "5.0";}
                elseif ((eregi("opera/4",$this->USER_AGENT)) || (eregi("opera 4",$this->USER_AGENT))) {$this->Browser_Version = "4";}
            }
            elseif (eregi("oracle",$this->USER_AGENT))
            {
                $this->Browser = "Oracle PowerBrowser";
                if (eregi("(tm)/1.0a",$this->USER_AGENT)) {$this->Browser_Version = "1.0a";}
                elseif (eregi("oracle 1.5",$this->USER_AGENT)) {$this->Browser_Version = "1.5";}
            }
            elseif (eregi("phoenix",$this->USER_AGENT))
            {
                $this->Browser = "Phoenix";
                if (eregi("phoenix/0.4",$this->USER_AGENT)) {$this->Browser_Version = "0.4";}
                elseif (eregi("phoenix/0.5",$this->USER_AGENT)) {$this->Browser_Version = "0.5";}
            }
            elseif (eregi("planetweb",$this->USER_AGENT))
            {
                $this->Browser = "PlanetWeb";
                if (eregi("planetweb/2.606",$this->USER_AGENT)) {$this->Browser_Version = "2.6";}
                elseif (eregi("planetweb/1.125",$this->USER_AGENT)) {$this->Browser_Version = "3";}
            }
            elseif (eregi("powertv",$this->USER_AGENT))
            {
                $this->Browser = "PowerTV";
                if (eregi("powertv/1.5",$this->USER_AGENT)) {$this->Browser_Version = "1.5";}
            }
            elseif (eregi("prodigy",$this->USER_AGENT))
            {
                $this->Browser = "Prodigy";
                if (eregi("wb/3.2e",$this->USER_AGENT)) {$this->Browser_Version = "3.2e";}
                elseif (eregi("rv: 1.",$this->USER_AGENT)) {$this->Browser_Version = "1.0";}
            }
            elseif ((eregi("voyager",$this->USER_AGENT)) || ((eregi("qnx",$this->USER_AGENT))) && (eregi("rv: 1.",$this->USER_AGENT))) // aj voyager je trosku zlozitejsi na detekciu
            {
                $this->Browser = "Voyager";
                if (eregi("2.03b",$this->USER_AGENT)) {$this->Browser_Version = "2.03b";}
                elseif (eregi("wb/win32/3.4g",$this->USER_AGENT)) {$this->Browser_Version = "3.4g";}
            }
            elseif (eregi("quicktime",$this->USER_AGENT))
            {
                $this->Browser = "QuickTime";
                if (eregi("qtver=5",$this->USER_AGENT)) {$this->Browser_Version = "5.0";}
                elseif (eregi("qtver=6.0",$this->USER_AGENT)) {$this->Browser_Version = "6.0";}
                elseif (eregi("qtver=6.1",$this->USER_AGENT)) {$this->Browser_Version = "6.1";}
                elseif (eregi("qtver=6.2",$this->USER_AGENT)) {$this->Browser_Version = "6.2";}
                elseif (eregi("qtver=6.3",$this->USER_AGENT)) {$this->Browser_Version = "6.3";}
                elseif (eregi("qtver=6.4",$this->USER_AGENT)) {$this->Browser_Version = "6.4";}
                elseif (eregi("qtver=6.5",$this->USER_AGENT)) {$this->Browser_Version = "6.5";}
            }
            elseif (eregi("safari",$this->USER_AGENT))
            {
                $this->Browser = "Safari";
                if (eregi("safari/48",$this->USER_AGENT)) {$this->Browser_Version = "0.48";}
                elseif (eregi("safari/49",$this->USER_AGENT)) {$this->Browser_Version = "0.49";}
                elseif (eregi("safari/51",$this->USER_AGENT)) {$this->Browser_Version = "0.51";}
                elseif (eregi("safari/60",$this->USER_AGENT)) {$this->Browser_Version = "0.60";}
                elseif (eregi("safari/61",$this->USER_AGENT)) {$this->Browser_Version = "0.61";}
                elseif (eregi("safari/62",$this->USER_AGENT)) {$this->Browser_Version = "0.62";}
                elseif (eregi("safari/63",$this->USER_AGENT)) {$this->Browser_Version = "0.63";}
                elseif (eregi("safari/64",$this->USER_AGENT)) {$this->Browser_Version = "0.64";}
                elseif (eregi("safari/65",$this->USER_AGENT)) {$this->Browser_Version = "0.65";}
                elseif (eregi("safari/66",$this->USER_AGENT)) {$this->Browser_Version = "0.66";}
                elseif (eregi("safari/67",$this->USER_AGENT)) {$this->Browser_Version = "0.67";}
                elseif (eregi("safari/68",$this->USER_AGENT)) {$this->Browser_Version = "0.68";}
                elseif (eregi("safari/69",$this->USER_AGENT)) {$this->Browser_Version = "0.69";}
                elseif (eregi("safari/70",$this->USER_AGENT)) {$this->Browser_Version = "0.70";}
                elseif (eregi("safari/71",$this->USER_AGENT)) {$this->Browser_Version = "0.71";}
                elseif (eregi("safari/72",$this->USER_AGENT)) {$this->Browser_Version = "0.72";}
                elseif (eregi("safari/73",$this->USER_AGENT)) {$this->Browser_Version = "0.73";}
                elseif (eregi("safari/74",$this->USER_AGENT)) {$this->Browser_Version = "0.74";}
                elseif (eregi("safari/80",$this->USER_AGENT)) {$this->Browser_Version = "0.80";}
                elseif (eregi("safari/83",$this->USER_AGENT)) {$this->Browser_Version = "0.83";}
                elseif (eregi("safari/84",$this->USER_AGENT)) {$this->Browser_Version = "0.84";}
                elseif (eregi("safari/90",$this->USER_AGENT)) {$this->Browser_Version = "0.90";}
                elseif (eregi("safari/92",$this->USER_AGENT)) {$this->Browser_Version = "0.92";}
                elseif (eregi("safari/93",$this->USER_AGENT)) {$this->Browser_Version = "0.93";}
                elseif (eregi("safari/94",$this->USER_AGENT)) {$this->Browser_Version = "0.94";}
                elseif (eregi("safari/95",$this->USER_AGENT)) {$this->Browser_Version = "0.95";}
                elseif (eregi("safari/96",$this->USER_AGENT)) {$this->Browser_Version = "0.96";}
                elseif (eregi("safari/97",$this->USER_AGENT)) {$this->Browser_Version = "0.97";}
            }
            elseif (eregi("sextatnt",$this->USER_AGENT))
            {
                $this->Browser = "Tango";
                if (eregi("sextant v3.0",$this->USER_AGENT)) {$this->Browser_Version = "3.0";}
            }
            elseif (eregi("elinks",$this->USER_AGENT))
            {
                $this->Browser = "ELinks";
                if (eregi("0.3",$this->USER_AGENT)) {$this->Browser_Version = "0.3";}
                elseif (eregi("0.4",$this->USER_AGENT)) {$this->Browser_Version = "0.4";}
            }
            elseif (eregi("links",$this->USER_AGENT))
            {
                $this->Browser = "Links";
                if (eregi("0.9",$this->USER_AGENT)) {$this->Browser_Version = "0.9";}
                elseif (eregi("2.0",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
                elseif (eregi("2.1",$this->USER_AGENT)) {$this->Browser_Version = "2.1";}
            }
            elseif (eregi("lynx",$this->USER_AGENT))
            {
                $this->Browser = "Lynx";
                if (eregi("lynx/2.3",$this->USER_AGENT)) {$this->Browser_Version = "2.3";}
                elseif (eregi("lynx/2.4",$this->USER_AGENT)) {$this->Browser_Version = "2.4";}
                elseif (eregi("lynx/2.5",$this->USER_AGENT)) {$this->Browser_Version = "2.5";}
                elseif (eregi("lynx/2.6",$this->USER_AGENT)) {$this->Browser_Version = "2.6";}
                elseif (eregi("lynx/2.7",$this->USER_AGENT)) {$this->Browser_Version = "2.7";}
                elseif (eregi("lynx/2.8",$this->USER_AGENT)) {$this->Browser_Version = "2.8";}
            }
            elseif (eregi("webexplorer",$this->USER_AGENT))
            {
                $this->Browser = "WebExplorer";
                if (eregi("dll/v1.1",$this->USER_AGENT)) {$this->Browser_Version = "1.1";}
            }
            elseif (eregi("webtv",$this->USER_AGENT))
            {
                $this->Browser = "WebTV";
                if (eregi("webtv/1.0",$this->USER_AGENT)) {$this->Browser_Version = "1.0";}
                elseif (eregi("webtv/1.1",$this->USER_AGENT)) {$this->Browser_Version = "1.1";}
                elseif (eregi("webtv/1.2",$this->USER_AGENT)) {$this->Browser_Version = "1.2";}
                elseif (eregi("webtv/2.2",$this->USER_AGENT)) {$this->Browser_Version = "2.2";}
                elseif (eregi("webtv/2.5",$this->USER_AGENT)) {$this->Browser_Version = "2.5";}
                elseif (eregi("webtv/2.6",$this->USER_AGENT)) {$this->Browser_Version = "2.6";}
                elseif (eregi("webtv/2.7",$this->USER_AGENT)) {$this->Browser_Version = "2.7";}
            }
            elseif (eregi("yandex",$this->USER_AGENT))
            {
                $this->Browser = "Yandex";
                if (eregi("/1.01",$this->USER_AGENT)) {$this->Browser_Version = "1.01";}
                elseif (eregi("/1.03",$this->USER_AGENT)) {$this->Browser_Version = "1.03";}
            }
            elseif ((eregi("mspie",$this->USER_AGENT)) || ((eregi("msie",$this->USER_AGENT))) && (eregi("windows ce",$this->USER_AGENT)))
            {
                $this->Browser = "Pocket Inetrnet Explorer";
                if (eregi("mspie 1.1",$this->USER_AGENT)) {$this->Browser_Version = "1.1";}
                elseif (eregi("mspie 2.0",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
                elseif (eregi("msie 3.02",$this->USER_AGENT)) {$this->Browser_Version = "3.02";}
            }
            elseif (eregi("msie",$this->USER_AGENT))
            {
                $this->Browser = "Internet Explorer";
                if (eregi("msie 6.0",$this->USER_AGENT)) {$this->Browser_Version = "6.0";}
                elseif (eregi("msie 5.5",$this->USER_AGENT)) {$this->Browser_Version = "5.5";}
                elseif (eregi("msie 5.01",$this->USER_AGENT)) {$this->Browser_Version = "5.01";}
                elseif (eregi("msie 5.23",$this->USER_AGENT)) {$this->Browser_Version = "5.23";}
                elseif (eregi("msie 5.22",$this->USER_AGENT)) {$this->Browser_Version = "5.22";}
                elseif (eregi("msie 5.2.2",$this->USER_AGENT)) {$this->Browser_Version = "5.2.2";}
                elseif (eregi("msie 5.1b1",$this->USER_AGENT)) {$this->Browser_Version = "5.1b1";}
                elseif (eregi("msie 5.17",$this->USER_AGENT)) {$this->Browser_Version = "5.17";}
                elseif (eregi("msie 5.16",$this->USER_AGENT)) {$this->Browser_Version = "5.16";}
                elseif (eregi("msie 5.12",$this->USER_AGENT)) {$this->Browser_Version = "5.12";}
                elseif (eregi("msie 5.0b1",$this->USER_AGENT)) {$this->Browser_Version = "5.0b1";}
                elseif (eregi("msie 5.0",$this->USER_AGENT)) {$this->Browser_Version = "5.0";}
                elseif (eregi("msie 5.21",$this->USER_AGENT)) {$this->Browser_Version = "5.21";}
                elseif (eregi("msie 5.2",$this->USER_AGENT)) {$this->Browser_Version = "5.2";}
                elseif (eregi("msie 5.15",$this->USER_AGENT)) {$this->Browser_Version = "5.15";}
                elseif (eregi("msie 5.14",$this->USER_AGENT)) {$this->Browser_Version = "5.14";}
                elseif (eregi("msie 5.13",$this->USER_AGENT)) {$this->Browser_Version = "5.13";}
                elseif (eregi("msie 4.5",$this->USER_AGENT)) {$this->Browser_Version = "4.5";}
                elseif (eregi("msie 4.01",$this->USER_AGENT)) {$this->Browser_Version = "4.01";}
                elseif (eregi("msie 4.0b2",$this->USER_AGENT)) {$this->Browser_Version = "4.0b2";}
                elseif (eregi("msie 4.0b1",$this->USER_AGENT)) {$this->Browser_Version = "4.0b1";}
                elseif (eregi("msie 4",$this->USER_AGENT)) {$this->Browser_Version = "4.0";}
                elseif (eregi("msie 3",$this->USER_AGENT)) {$this->Browser_Version = "3.0";}
                elseif (eregi("msie 2",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
                elseif (eregi("msie 1.5",$this->USER_AGENT)) {$this->Browser_Version = "1.5";}
            }
            elseif (eregi("mozilla",$this->USER_AGENT)) // (2) netscape nie je prilis detekovatelny....
            {
                $this->Browser = "Netscape";
                if (eregi("mozilla/4.8",$this->USER_AGENT)) {$this->Browser_Version = "4.8";}
                elseif (eregi("mozilla/4.7",$this->USER_AGENT)) {$this->Browser_Version = "4.7";}
                elseif (eregi("mozilla/4.6",$this->USER_AGENT)) {$this->Browser_Version = "4.6";}
                elseif (eregi("mozilla/4.5",$this->USER_AGENT)) {$this->Browser_Version = "4.5";}
                elseif (eregi("mozilla/4.0",$this->USER_AGENT)) {$this->Browser_Version = "4.0";}
                elseif (eregi("mozilla/3.0",$this->USER_AGENT)) {$this->Browser_Version = "3.0";}
                elseif (eregi("mozilla/2.0",$this->USER_AGENT)) {$this->Browser_Version = "2.0";}
            }
        }
    }
// END OF CLASS - BEGIN FUNCTIONS
?>