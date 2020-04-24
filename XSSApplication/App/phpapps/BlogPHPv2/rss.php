<?php
include ("config.php");
header("Content-type: text/xml");

if ($_GET['lim'] == "") {
$rlimit = "10";
} else {
$rlimit = $_GET['lim'];
}

echo "<?xml version=\"1.0\" encoding=\"US-ASCII\"?><rss version=\"2.0\"><channel>";

echo "<title>".$sitename." - Latest Blogs</title><link>".$siteurl."</link><description>".$sitename." - ".$siteurl."</description><language>en-us</language><copyright>Copyright 2006, ".$sitename.".</copyright><webMaster>webmaster@".$domain."</webMaster>";

$sql = mysql_query("SELECT * FROM ".$pre."blogs ORDER BY `id` DESC LIMIT ".$rlimit); 
while($r = mysql_fetch_array($sql)) {
echo "<item><title>".$sitename." - ".stripslashes($r[subject])." :: ".$r[cat]."</title><link>".$siteurl."/blog-".$r[id].".html</link><pubDate>".date("D, d M Y h:i:s T", $r[date])."</pubDate><description>".htmlspecialchars(substr($r[blog],0,300))."</description></item>";
}
echo "</channel></rss>";
?>