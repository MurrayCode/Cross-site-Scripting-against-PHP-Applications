<?php
include ("../../../../../config.php");
echo "<script language='javascript' type='text/javascript' src='../../tiny_mce_popup.js'></script><script language='javascript' type='text/javascript' src='jscripts/functions.js'></script><title>".$sitename." - Smilies :: View All</title>";
echo "<table cellpadding='1' cellspacing='0' border='0' width='150' height='100%'><tr><td width='150' align='left' valign='top'>";
if ($handle = opendir("../../../../../smilies")) {
while (false !== ($file = readdir($handle))) {
if ($file != "." && $file != "..") {
$file2 = explode(".", $file);
echo "<a href=\"javascript:insertEmotion('".$siteurl."/smilies/".$file."','lang_emotions_".$file2[0]."');\"><img src='".$siteurl."/smilies/".$file."' border='0'></a>&nbsp;";
}
}
}
echo "</td></tr></table>";
?>