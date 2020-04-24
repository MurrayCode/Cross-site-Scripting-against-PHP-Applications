<?php
include ("config.php");

if ((table($pre."blogs") == FALSE) && (table($pre."users") == FALSE)) {
header('location: install.php');
die;
}

$searchentry = "";
if ($_GET['search']) {
$searchentry = htmlentities($_GET['search']);
eval (" ?> ".headera()." <?php ");
$temp = mysql_fetch_row(mysql_query("SELECT template FROM ".$pre."templates WHERE name = 'search_results'"));
$results = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."blogs WHERE subject LIKE '%" . $_GET['search'] . "%' AND status = '' OR blog LIKE '%" . $_GET['search'] . "%' AND status = ''"));

echo "<title>".$sitename." :: ".$results." Search Results For - ".$_GET['search']."</title><br /><center>".$results." Results For :: ".$_GET['search']."</center><br /><br /><table cellspacing='0' cellpadding='3' border='0' align='center' width='90%'>";

$search = mysql_query("SELECT * FROM ".$pre."blogs WHERE subject LIKE '%" . $_GET['search'] . "%' AND status = '' OR blog LIKE '%" . $_GET['search'] . "%' AND status = '' ORDER BY `id` DESC");
for($i = 1; $r = mysql_fetch_assoc($search); $i++) {
$n1 = "/".$searchentry."/";
$n2 = hilite($searchentry);
$name = preg_replace($n1, $n2, $r[subject]);
similar_text($searchentry, $r[subject], $q);
similar_text($searchentry, $r[blog], $s);
//$q = str_replace(".", "", $p);
//$s = str_replace(".", "", $s);

if (($s) && ($q)) {
$p = $q * $s;
} else {
if ($s) {
$p = $s;
}
if ($q) {
$p = $q;
}
}

$p = explode(".", $p);
$p = substr($p[0],0,3);

$pab[0] = "/{link}/";
$pab[1] = "/{relevance}/";
$pab[2] = "/{date}/";
$pab[3] = "/{author}/";
$rab[0] = "<a href='blog-".$r[id].".html'>".$name."</a>";
$rab[1] = $p;
$rab[2] = date("F d, Y", $r[date]);
$rab[3] = "<a href='user-".$r[author].".html'>".$r[author]."</a>";

eval (" ?>" . preg_replace($pab, $rab, stripslashes($temp[0])) . " <?php ");
}
echo "</table>";
} else {

if ($_GET['act'] == "user3") {
eval (" ?>" . headera() . " <?php ");

$bday = $_POST["bday1"]."|".$_POST["bday2"]."|".$_POST["bday3"];

$pquery = sprintf("UPDATE ".$pre."users SET aim = '".$_POST["aim"]."', msn = '".$_POST["msn"]."', url = '".$_POST["url"]."', name = '".$_POST["name"]."', avatar = '".$_POST["avatar"]."', yahoo = '".$_POST["yahoo"]."', icq = '".$_POST["icq"]."', gtalk = '".$_POST["gtalk"]."', bday = '".$bday."' WHERE username = '%s' AND password = '%s'",
mysql_real_escape_string($_COOKIE[blogphp_username]),
mysql_real_escape_string($_COOKIE[blogphp_password]));
mysql_query($pquery);

echo "Profile Updated. <a href='user-".htmlentities($_COOKIE[blogphp_username]).".html'>Return</a>";
}

if ($_GET['act'] == "subscribe") {
eval (" ?>" . headera() . " <?php ");

echo "<center><b>Subscribe</b></center><br><form action='index.php?act=subscribe2' method='post'><input type='hidden' name='url' value='".$fpage."'><input type='text' name='email' value='".$email."'>&nbsp;&nbsp;<input type='submit' value='Subcribe'><br><input type='checkbox' name='unsub' value='yes'>Un-Subscribe?</form><br><br>";
}

if ($_GET['act'] == "subscribe2") {
eval (" ?>" . headera() . " <?php ");

if (validate_email($_POST['email'])) { 
if ($_POST['unsub']) {
$add = mysql_query("DELETE FROM ".$pre."subscriptions WHERE email = '".$_POST['email']."'");
} else {
$add = mysql_query("INSERT INTO ".$pre."subscriptions VALUES ('null', '".$_POST['email']."')");
}

if ($add == TRUE) {
echo re_direct("1500", $_POST['url']);
echo "You have ";
if ($_POST['unsub']) {
echo "un-";
}
echo "subscribed to ".$sitename.". <a href='".$_POST['url']."'>Return to the blog</a>";
}
} else {
echo "Invalid e-mail, go back";
}
}

if ($_GET['act'] == "memberslist") {
eval (" ?>" . headera() . " <?php ");

echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\" width=\"90%\"><tr><td align='center'><tr><td><b>Username</b></td><td><b>Level</b></td><td><b>Join Date</b></td></tr>";

$sql = mysql_query("SELECT * FROM ".$pre."users ORDER BY `username` ASC");
while($r = mysql_fetch_array($sql)) {
echo "<tr><td><a href='user-".stripslashes($r[username]).".html'>".stripslashes($r[username])."</a></td><td>".$r[level]."</td><td>".date("F d, Y", $r[date])."</td></tr>";
}

echo "</table>";
}

if ($_GET['act'] == "user2") {
eval (" ?>" . headera() . " <?php ");

	$sql = sprintf("SELECT * FROM ".$pre."users WHERE username = '%s' AND password = '%s' LIMIT 1",
    mysql_real_escape_string($_COOKIE[blogphp_username]),
    mysql_real_escape_string($_COOKIE[blogphp_password]));
    $thesql = mysql_query($sql);
	while($r = mysql_fetch_array($thesql)) {
		$aim = stripslashes($r[aim]);
		$msn = stripslashes($r[msn]);
		$url = stripslashes($r[url]);
		$name = stripslashes($r[name]);
		$avatar = stripslashes($r[avatar]);
		$yahoo = stripslashes($r[yahoo]);
		$icq = stripslashes($r[icq]);
		$gtalk = stripslashes($r[gtalk]);
		$bday = explode("|", $r[bday]);
		$email = $r[email];
	
		echo "<center><a href='user-".htmlentities($_COOKIE[blogphp_username]).".html'>View Profile</a></center><br><form action='index.php?act=user3' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>E-mail</b></td><td><input type='text' name='email' value='".$email."'></td></tr><tr><td><b>AIM</b></td><td><input type='text' name='aim' value='".$aim."'></td></tr><tr><td><b>MSN</b></td><td><input type='text' name='msn' value='".$msn."'></td></tr><tr><td><b>YIM</b></td><td><input type='text' name='yahoo' value='".$yahoo."'></td></tr><tr><td><b>ICQ</b></td><td><input type='text' name='icq' value='".$icq."'></td></tr><tr><td><b>Google Talk</b></td><td><input type='text' name='gtalk' value='".$gtalk."'></td></tr><tr><td><b>Birthday (m/d/yyyy)</b></td><td><select name='bday1'><option value=''></option>";
		
        if ($bday[0]) {
		echo "<option value='".$bday[0]."' selected>".$bday[0]."</option>";
		}

		echo "<option value='January'>January</option><option value='February'>February</option><option value='March'>March</option><option value='April'>April</option><option value='May'>May</option><option value='June'>June</option><option value='July'>July</option><option value='August'>August</option><option value='September'>September</option><option value='October'>October</option><option value='November'>November</option><option value='December'>December</option>";
		
		echo "</select>&nbsp;&nbsp;<select name='bday2'><option value=''></option>";

		for ($i = 1; $i <= 30; $i++) {
		if ($i == $bday[1]) {
		echo "<option value='".$i."' selected>".$i."</option>";
		} else {
		echo "<option value='".$i."'>".$i."</option>";
		}
		}

        echo "</select>&nbsp;&nbsp;<input type='text' name='bday3' value='".$bday[2]."' size='4' limit='4'></td></tr><tr><td><b>Website</b></td><td><input type='text' name='url' value='".$url."'></td></tr><tr><td><b>Name</b></td><td><input type='text' name='name' value='".$name."'></td></tr><tr><td><b>Avatar</b></td><td><input type='text' name='avatar' value='".$avatar."' size='36'></td><td>";
		if ($avatar) {
		echo "<script language='javascript'>function awindow(towhere, newwinname, properties) {window.open(towhere,newwinname,properties);}</script>";
		
		list($widtha, $heighta) = getimagesize($avatar);

		$heighta2 = $heighta + 16;
		$widtha2 = $widtha + 16;

		echo "<a href='javascript:awindow(\"".$avatar."\", \"\", \"width=".$widtha2.",height=".$heighta2.",scroll=yes\")'><b>View Current Avatar</b></a>";
		}
		echo "</td></tr>";
	}
	echo "<tr><td><input type='submit' name='submit' value='Update Profile'></td></tr></table></form>";

}

if (($_GET['act'] == "user") && ($_GET['id'])) {
eval (" ?>" . headera() . " <?php ");
$dt = mysql_fetch_row(mysql_query("SELECT email,level,name,aim,msn,yahoo,icq,gtalk,url,avatar,bday FROM ".$pre."users WHERE username = '".$_GET['id']."'")) or die(mysql_error());
$bday = explode("|", $dt[10]);

echo "<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" align=\"center\" width=\"100%\"><tr><td align='center'>";
echo "<b><a href='mailto:".$dt[0]."'>".$_GET['id']."</a></b><br>";
if ($dt[9]) {
echo "<img src='".$dt[9]."' style='border: 1px solid black'>";
}
echo "<br>".$dt[1];
echo "</td><td align='left'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td>".$dt[2]."</td></tr><tr><tr><td><b>Birthday</b></td><td>";
if ($bday[0]) {
echo $bday[0]."&nbsp;".$bday[1].",&nbsp;".$bday[2];
}
echo "</td></tr><tr><td><b>Website</b></td><td><a href='".$dt[8]."' target='popup'>".$dt[8]."</a></td></tr><td><b>AIM</b></td><td><a href='aim:goim?screenname=".$dt[3]."&message=Hello+Are+you+there?'>".$dt[3]."</a></td></tr><tr><td><b>MSN</b></td><td>".$dt[4]."</td></tr><tr><td><b>YIM</b></td><td><a href='http://edit.yahoo.com/config/send_webmesg?.target=".$dt[5]."&.src=pg'>".$dt[5]."</a></td></tr><tr><td><b>ICQ</b></td><td><a href='http://www.icq.com/whitepages/cmd.php?uin=".$dt[6]."&action=message'>".$dt[6]."</a></td></tr><tr><td><b>Google Talk</b></td><td>".$dt[7]."</td></tr></table></td>";
if (($dt[1] == "Admin") or ($dt[2] == "Author")) {
echo "<td align='right'><b>Blogs Posted</b><br>";
$sql = mysql_query("SELECT * FROM ".$pre."blogs WHERE status = '' ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
echo "<br><a href='blog-".$r[id].".html'>".stripslashes($r[subject])."</a>";
}
echo "</td>";
}
echo "</tr></table>";
if (htmlentities($_COOKIE[blogphp_username]) == $_GET['id']) {
echo "<br><br><center><a href='index.php?act=user2'>Edit your Profile</a> - <a href='index.php?act=messages'>View Messages</a></center>";
} else {
if ($theuser) {
echo "<br><br><center><a href='index.php?act=sendmessage&user=".$_GET['id']."'>Send Message</a></center>";
}
}
}

if ($_GET['act'] == "files") {
eval (" ?>" . headera() . " <?php ");

if(!isset($_GET['p'])){
    $page = 1;
} else {
    $page = $_GET['p'];
}

$albpage = "15";
$albrow = "3";
$albsep = "</tr><tr>";

$from = (($page * $albpage) - $albpage);

echo "<table align='center' border='0' cellpadding='2' cellspacing='1' width='90%'><tr>";
$sql = mysql_query("SELECT * FROM ".$pre."files ORDER BY `id` DESC LIMIT $from, $albpage");
for($i = 1; $r = mysql_fetch_assoc($sql); $i++) {

if (list($width, $height, $type, $attr) = getimagesize($upload.$r[file]) == FALSE) {
echo "<td align='center'><a href='".$upload.$r[file]."'>".$r[file]."</a></td>";
} else {
list($width1, $height1, $type, $attr) = getimagesize($upload.$r[file]);
echo "<td align='center'><a href='".$upload.stripslashes($r[file])."'><img src='".$upload.stripslashes($r[file])."' border='0'";
if ($width1 > "100") {
echo " width='100'";
}
if ($height1 > "100") {
echo " height='100'";
}
echo "></a><br>".$r[file]."</td>";

if (!$i == "0") {
if (($i % $albrow) === 0) {
print $albsep;
}
}
}
}
echo "</tr></table>";

$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."files"),0);
$total_pages = ceil($total_results / $albpage);

if ($total_pages > "1") {

echo "<br><br><center>Page:<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"index.php?act=files&p=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"index.php?act=files&p=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"index.php?act=files&p=$next\">Next>></a>";
}
echo "</center>";
}
}

if ($_GET['act'] == "sendmessage") {
if ($theuser == "") {
echo "Sorry but you are not a member and cannot send messages, please <a href='register.html'>register</a> or <a href='login.html'>login</a>.";
} else {
eval (" ?>" . headera() . " <?php ");
echo wysiwyg();
echo "<form action='index.php?act=sendmessage2' method='post'><table cellspacing='0' cellpadding='3' border='0' align='center'><tr><td><b>Recipient</b></td><td>";
if ($_GET['user']) {
echo $_GET['user']."<input type='hidden' name='user' value='".$_GET['user']."'>";
} else {
echo "<select name='user'>";
$sql = mysql_query("SELECT * FROM ".$pre."users ORDER BY `username` ASC");
while($r = mysql_fetch_array($sql)) {
if ($r[username] == $theuser) {
} else {
echo "<option value='".$r[username]."'>".$r[username]."</option>";
}
}
echo "</select>";
}
echo "</td></tr><tr><td><b>IP</b></td><td>".$_SERVER['REMOTE_ADDR']."<input type='hidden' name='ip' value='".$_SERVER['REMOTE_ADDR']."'></td></tr><tr><td><b>Message</b></td><td><textarea name='comment' cols='45' rows='10'></textarea><input type='hidden' name='url' value='".$_SERVER['HTTP_REFERER']."'></td></tr><tr><td>&nbsp;</td><td><input type='submit' value='Send Message'></td></tr></table></form>";
}
}

if ($_GET['act'] == "messages") {
if ($theuser == "") {
echo "Sorry but you are not a member and cannot receive messages, please <a href='register.html'>register</a> or <a href='login.html'>login</a>.";
} else {
eval (" ?>" . headera() . " <?php ");
$sql = mysql_query("SELECT * FROM ".$pre."comments WHERE aid = '".$theuser."' ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
echo "<a name='".$r[id]."'></a><table cellspacing='0' cellpadding='3' border='0' align='center'><tr><td><b>Sender</b></td><td><a href='user-".$r[author].".html'>".$r[author]."</a></td></tr><tr><td><b>Date</b></td><td>".date("F d, Y", $r[date])."</td></tr><tr><td><b>Message</b></td><td>".stripslashes($r[comment])."</td></tr></table><br><br>";
}
}
}

if ($_GET['act'] == "sendmessage2") {
if (($_POST['comment'] == "") or ($theuser == "")) {
echo "Sorry but you are not a member and cannot send messages or a message has not been entered, please <a href='register.html'>register</a> or <a href='login.html'>login</a>.";
} else {
eval (" ?>" . headera() . " <?php ");
$comment = addslashes(nl2br($_POST['comment']));
$add = mysql_query("INSERT INTO ".$pre."comments VALUES ('null', '".$_POST['user']."', '".$comment."', '".htmlentities($_COOKIE[blogphp_username])."', '".$email."', '".$url."', '".$_POST['ip']."', '".time()."')")or die(mysql_error());

if ($add == TRUE) {
echo re_direct("1500", $_POST['url']);
echo "You have sent a message. <a href='".$_POST['url']."'>Return back</a>";
}
}
}

if ($_GET['act'] == "addcomment") {
if ($_POST['comment'] == "") {
echo "Please go back and fill out a comment";
} else {
eval (" ?>" . headera() . " <?php ");
$comment = addslashes(nl2br($_POST['comment']));
$url = htmlspecialchars($_POST['url']);
$email = htmlspecialchars($_POST['email']);
if (validate_email($email)) { 
$add = mysql_query("INSERT INTO ".$pre."comments VALUES ('null', '".$_POST['id']."', '".$comment."', '".htmlentities($_COOKIE[blogphp_username])."', '".$email."', '".$url."', '".$_POST['ip']."', '".time()."')");

if ($add == TRUE) {
echo re_direct("1500", $_SERVER['HTTP_REFERER']);
echo "You have added a comment. <a href='".$_SERVER['HTTP_REFERER']."'>Return to the blog</a>";
}
} else {
echo "Invalid e-mail, go back";
}
}
}

if ($_GET['act'] == "page") {
eval (" ?>" . headera() . " <?php ");
$temp = mysql_fetch_row(mysql_query("SELECT template FROM ".$pre."templates WHERE name = 'pages'"));

$sql = mysql_query("SELECT * FROM ".$pre."pages WHERE url = '".$_GET['id']."'");
while($r = mysql_fetch_array($sql)) {
$cnum2 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."comments WHERE aid = '".$r[id]."'"));
$pab[0] = "/{title}/";
$pab[1] = "/{content}/";
$pab[2] = "/{author}/";
$rab[0] = "<a href='page-".$r[url].".html'>".stripslashes($r[title])."</a>";
$rab[1] = stripslashes($r[content]);
$rab[2] = "<a href='user-".$r[author].".html'>".$r[author]."</a>";
eval (" ?>" . preg_replace($pab, $rab, stripslashes($temp[0])) . " <?php ");
}
}

if ($_GET['act'] == "stats") {
eval (" ?>" . headera() . " <?php ");

echo "<form action='index.php?act=stats' method='get'><center><input type='hidden' name='act' value='stats'><b>Check:</b>&nbsp;<select name='day'>";
for($i = 1; $i <= 31; $i++){
if ($i < "10") {
$y = "0".$i;
} else {
$y = $i;
}
echo "<option value='".$y."'";
if ($_GET['day'] == $y) {
echo " selected";
} else {
if ($y == date("d")) {
echo " selected";
}
}
echo ">".$y."</option>";
}
echo "</select>&nbsp;<select name='month'><option value='01'";
if (date("F") == "January") {
echo " selected";
}
echo ">January</option><option value='02'";
if (date("F") == "February") {
echo " selected";
}
echo ">February</option><option value='03'";
if (date("F") == "March") {
echo " selected";
}
echo ">March</option><option value='04'";
if (date("F") == "April") {
echo " selected";
}
echo ">April</option><option value='05'";
if (date("F") == "May") {
echo " selected";
}
echo ">May</option><option value='06'";
if (date("F") == "June") {
echo " selected";
}
echo ">June</option><option value='07'";
if (date("F") == "July") {
echo " selected";
}
echo ">July</option><option value='08'";
if (date("F") == "August") {
echo " selected";
}
echo ">August</option><option value='09'";
if (date("F") == "September") {
echo " selected";
}
echo ">September</option><option value='10'";
if (date("F") == "October") {
echo " selected";
}
echo ">October</option><option value='11'";
if (date("F") == "November") {
echo " selected";
}
echo ">November</option><option value='12'";
if (date("F") == "December") {
echo " selected";
}
echo ">December</option></select>&nbsp;<select name='year'>";
for($i = 2006; $i <= 2020; $i++){
echo "<option value='".$i."'";
if ($i == date("Y")) {
echo " selected";
}
echo ">".$i."</option>";
}
echo "</select>&nbsp;&nbsp;<input type='submit' value='Search'></form></center><br>";

if (date("m") == "01") {
$lmonth = "12";
} else {
$lmonth = date("m") - 1;
}

if (date("W") == "01") {
$lweek = "52";
} else {
$lweek = date("W") - 1;
}
if (date("z") == "0") {
$lday = "365";
$lyear = date("Y") -1;
} else {
$lday = date("z") - 1;
$lyear = date("Y");
}

if ($lweek < "10") {
$lweek = "0".$lweek;
} else {
$lweek = $lweek;
}

if ($lmonth < "10") {
$lmonth = "0".$lmonth;
} else {
$lmonth = $lmonth;
}

if ($_GET['year']) {
$views = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'view' AND year = '".$_GET['year']."' AND month = '".$_GET['month']."' AND day = '".$_GET['day']."'"));
$uniques = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'unique' AND year = '".$_GET['year']."' AND month = '".$_GET['month']."' AND day = '".$_GET['day']."'"));
echo "<table cellspacing='0' cellpadding='3' border='0' align='left'><tr><td><b>Unique Visitors:</b> ".$uniques."<br><b>Views:</b> ".$views."<br><br><br></td></tr>";
$var[] = "";
$var2[] = "";

$sql = mysql_query("SELECT * FROM ".$pre."stats WHERE year = '".$_GET['year']."' AND month = '".$_GET['month']."' AND day = '".$_GET['day']."' AND type = 'unique'");
while($r = mysql_fetch_array($sql)) {
$ex = explode("|", $r[info]);

$tvar1[] .= $ex[0];
$tvar2[] .= $ex[1];
$avar .= $ex[0];
$bvar .= $ex[1];

if (array_search($ex[0], $var)) {
} else {
if ($ex[0] == " ") {
} else {
$var[] .= $ex[0];
}
}
if (array_search($ex[1], $var2)) {
} else {
if ($ex[1] == " ") {
} else {
$var2[] .= $ex[1];
}
}
}
$total1 = count($tvar1);
$total2 = count($tvar2);
$count1 = count($var);
$count2 = count($var2);
sort($var);
sort($var2);
echo "<tr><td><b>Operating Systems:</b><br>";

while (list($i) = each($var)) {
if (strlen($var[$i]) < "5") {
$char = " ";
foreach(count_chars($avar, 1) as $chr => $hit) {
$tcount = $hit;
}
} else {
$tcount = substr_count($avar, $var[$i]);
}
if (($tcount == "0") && ($total1 == "0")) {
$total_1 = "0";
} else {
$total_1 = $tcount / $total1;
}
$total_1 = substr($total_1, 2, 2);
$total_1 = round($total_1, 1);

if ($total_1{0} == "0") {
$total_1 = str_replace("0", "", $total_1);
}

if (strlen($total_1) == "3") {
$total_1 = substr($total_1, 0, 2);
}

if (strlen($var[$i]) < "5") {
echo "Unknown ($total_1%)<br>";
} else {
echo $var[$i]." ($total_1%)<br>";
}
}
echo "<br></td></tr><tr><td><b>Browsers:</b><br>";
while (list($ia) = each($var2)) {
if (strlen($var2[$ia]) < "5") {
$char = " ";
foreach(count_chars($bvar, 1) as $chr => $hit) {
$tcount2 = $hit;
}
} else {
$tcount2 = substr_count($bvar, $var2[$ia]);
}
if (($tcount2 == "0") && ($total2 == "0")) {
$total_2 = "0";
} else {
$total_2 = $tcount2 / $total2;
}
$total_2 = substr($total_2, 2, 2);
$total_2 = round($total_2, 1);

if ($total_2{0} == "0") {
$total_2 = str_replace("0", "", $total_2);
}

if (strlen($total_2) == "3") {
$total_2 = substr($total_2, 0, 2);
}

if (strlen($var2[$ia]) < "5") {
echo "Unknown ($total_2%)<br>";
} else {
echo $var2[$ia]." ($total_2%)<br>";
}
}
echo "</td></tr></table><br><br>";
} else {
$views = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'view'"));
$uniques = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'unique'"));
$viewsa9 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'view' AND year = '".date("Y")."'"));
$uniquesa9 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'unique' AND year = '".date("Y")."'"));
$viewsb9 = mysql_fetch_row(mysql_query("SELECT tday FROM ".$pre."stats WHERE type = 'view' AND year = '".date("Y")."' ORDER BY `id` DESC LIMIT 1"));
$uniquesb9 = mysql_fetch_row(mysql_query("SELECT tday FROM ".$pre."stats WHERE type = 'unique' AND year = '".date("Y")."' ORDER BY `id` DESC LIMIT 1"));
$viewsc9 = mysql_fetch_row(mysql_query("SELECT tday FROM ".$pre."stats WHERE type = 'view' AND year = '".date("Y")."' ORDER BY `id` ASC LIMIT 1"));
$uniquesc9 = mysql_fetch_row(mysql_query("SELECT tday FROM ".$pre."stats WHERE type = 'unique' AND year = '".date("Y")."' ORDER BY `id` ASC LIMIT 1"));//total/avg this year
$abcv9 = $viewsb9[0] - $viewsc9[0] + 1;
$abcu9 = $uniquesb9[0] - $uniquesc9[0] + 1;
$views9 = $viewsa9 / $abcv9;
$uniques9 = $uniquesa9 / $abcu9;

$views1 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'view' AND day = '".date("d")."' AND month = '".date("m")."' AND week = '".date("W")."' AND year = '".date("Y")."'"));
$uniques1 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'unique' AND day = '".date("d")."' AND month = '".date("m")."' AND week = '".date("W")."' AND year = '".date("Y")."'"));
$views8 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'view' AND tday = '".$lday."' AND year = '".$lyear."'"));
$uniques8 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'unique' AND tday = '".$lday."' AND year = '".$lyear."'"));//today/yesterday

$views2 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'view' AND week = '".date("W")."' AND year = '".date("Y")."'"));
$uniques2 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'unique' AND week = '".date("W")."' AND year = '".date("Y")."'"));
$views7 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'view' AND week = '".$lweek."' AND year = '".$lyear."'"));
$uniques7 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'unique' AND week = '".$lweek."' AND year = '".$lyear."'"));//week

$views3 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'view' AND month = '".date("m")."' AND year = '".date("Y")."'"));
$uniques3 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'unique' AND month = '".date("m")."' AND year = '".date("Y")."'"));
$views4 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'view' AND month = '".$lmonth."' AND year = '".$lyear."'"));
$uniques4 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'unique' AND month = '".$lmonth."' AND year = '".$lyear."'"));//month

$views5 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'view' AND year = '".date("Y")."'"));
$uniques5 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'unique' AND year = '".date("Y")."'"));
$views6 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'view' AND year = '".$lyear."'"));
$uniques6 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE type = 'unique' AND year = '".$lyear."'"));//year

echo "<SCRIPT LANGUAGE='JavaScript'>
function donline () {
alert ('Days site has been online - ".$abcv9."');
}
</SCRIPT>";

echo "<table cellspacing='0' cellpadding='0' border='0' align='center' width='98%'><tr><td>&nbsp;</td><td><b>Uniques</b></td><td><b>Views</b></td></tr>
<tr><td><b>Average:</b></td><td>".$uniques9."</td><td>".$views9."</td></tr>	
<tr><td><b>Today:</b></td><td>".$uniques1."</td><td>".$views1."</td></tr>
<tr><td><b>Yesterday:</b></td><td>".$uniques8."</td><td>".$views8."</td></tr>

<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>

<tr><td><b>This Week:</b></td><td>".$uniques2."</td><td>".$views2."</td></tr>
<tr><td><b>This Month:</b></td><td>".$uniques3."</td><td>".$views3."</td></tr>
<tr><td><b>Last Week:</b></td><td>".$uniques7."</td><td>".$views7."</td></tr>
<tr><td><b>Last Month:</b></td><td>".$uniques4."</td><td>".$views4."</td></tr>

<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>

<tr><td><b>Total:</b></td><td><a href='javascript:donline()'>".$uniques."</a></td><td><a href='javascript:donline()'>".$views."</a></td></tr>
<tr><td><b>This Year:</b></td><td>".$uniques5."</td><td>".$views5."</td></tr>
<tr><td><b>Last Year:</b></td><td>".$uniques6."</td><td>".$views6."</td></tr></table><br><br>";
}
}

if ($_GET['act'] == "archive") {
eval (" ?>" . headera() . " <?php ");
echo "<table cellspacing='2' cellpadding='5' border='0' align='center' width='100%'><tr><td><b>Blog</b></td><td><b>Category</b></td><td><b>Date</b></td><td><b>Author</b></td>";
if ($_GET['cat']) {
$sql = mysql_query("SELECT * FROM ".$pre."blogs WHERE cat = '".$_GET['cat']."' AND status = '' ORDER BY `id` DESC");
} else {
$sql = mysql_query("SELECT * FROM ".$pre."blogs WHERE status = '' ORDER BY `id` DESC");
}
while($r = mysql_fetch_array($sql)) {
echo "<tr><td><a href='blog-".$r[id].".html'>".stripslashes($r[subject])."</a></td><td><a href='cat-".$r[cat].".html'>".$r[cat]."</td><td>".date("F d, Y", $r[date])."</td><td><a href='user-".$r[author].".html'>".$r[author]."</a></td></tr>";
}
echo "</table>";
}

if (($_GET['act'] == "blog") && (is_numeric($_GET['id']))) {
eval (" ?>" . headera() . " <?php ");
$temp = mysql_fetch_row(mysql_query("SELECT template FROM ".$pre."templates WHERE name = 'blog'"));
$temp2 = mysql_fetch_row(mysql_query("SELECT template FROM ".$pre."templates WHERE name = 'comments'"));
echo wysiwyg();
$sql = mysql_query("SELECT * FROM ".$pre."blogs WHERE id = '".$_GET['id']."'");
while($r = mysql_fetch_array($sql)) {
$cnum2 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."comments WHERE aid = '".$r[id]."'"));
$sql2 = mysql_query("SELECT * FROM ".$pre."comments WHERE aid = '".$_GET['id']."' ORDER BY `date` DESC");
while($s = mysql_fetch_array($sql2)) {

$comment = str_replace("&lt;br /&gt;", "<br>", stripslashes($s[comment]));
$pab2[0] = "/{date}/";
$pab2[1] = "/{comment}/";
$pab2[2] = "/{author}/";
$pab2[3] = "/{url}/";
$pab2[4] = "/{email}/";
$pab2[5] = "/{ip}/";
$rab2[0] = date("F d, Y", $s[date]);
$rab2[1] = $comment;
$rab2[2] = "<a href='user-".$s[author].".html'>".$s[author]."</a>";
$rab2[3] = $s[url];
$rab2[4] = $s[email];
if ($level == "Admin") {
$rab2[5] = "(IP: ".$s[ip].")";
} else {
$rab2[5] = "";
}
$comments .= preg_replace($pab2, $rab2, stripslashes($temp2[0]));
}
if ((htmlentities($_COOKIE[blogphp_username]) == "") && ($guests == "no")) {
$cform = "Sorry but guests cannot post comments! Please <a href='register.html'>register</a> or <a href='login.html'>login</a>.";
} else {
$cform = "<form action='index.php?act=addcomment' method='post'><table cellspacing='0' cellpadding='3' border='0' align='center'><tr><td><b>URL</b></td><td><input type='text' name='url' value='".$url."'></td></tr><tr><td><b>E-Mail</b></td><td><input type='text' name='email' value='".$email."'></td></tr><tr><td><b>IP</b></td><td>".$_SERVER['REMOTE_ADDR']."<input type='hidden' name='ip' value='".$_SERVER['REMOTE_ADDR']."'><input type='hidden' name='id' value='".$r[id]."'></td></tr><tr><td><b>Comment</b></td><td><textarea name='comment' cols='45' rows='10'></textarea></td></tr><tr><td>&nbsp;</td><td><input type='submit' value='Add Comment'></td></tr></table></form>";
}
$pab[0] = "/{subject}/";
$pab[1] = "/{date}/";
$pab[2] = "/{blog}/";
$pab[3] = "/{comments}/";
$pab[4] = "/{cnum}/";
$pab[5] = "/{commentsform}/";
$rab[0] = "<a href='blog-".$r[id].".html'>".stripslashes($r[subject])."</a>";
$rab[1] = date("F d, Y", $r[date]);
$rab[2] = stripslashes($r[blog])."<br /><br />";
$rab[3] = "<a name='comments'></a>".$comments;
$rab[4] = $cnum2;
$rab[5] = $cform;
eval (" ?>" . preg_replace($pab, $rab, stripslashes($temp[0])) . " <?php ");
}
}

if ($_GET['act'] == "") {
eval (" ?>" . headera() . " <?php ");
$temp = @mysql_fetch_row(@mysql_query("SELECT template FROM ".$pre."templates WHERE name = 'homepage'"));

if ($_GET['cat']) {
$sql = @mysql_query("SELECT * FROM ".$pre."blogs WHERE cat = '".$_GET['cat']."' AND status = '' ORDER BY `id` DESC LIMIT 5");
} else {
$sql = @mysql_query("SELECT * FROM ".$pre."blogs WHERE status = '' ORDER BY `id` DESC LIMIT 5");
}
while($r = @mysql_fetch_array($sql)) {
$cnum2 = @mysql_num_rows(@mysql_query("SELECT * FROM ".$pre."comments WHERE aid = '".$r[id]."'"));
$pab[0] = "/{link}/";
$pab[1] = "/{date}/";
$pab[2] = "/{blog}/";
$pab[3] = "/{comments}/";
$pab[4] = "/{cnum}/";
$pab[5] = "/{pcomment}/";
$pab[6] = "/{author}/";
$rab[0] = "<a href='blog-".$r[id].".html'>".stripslashes($r[subject])."</a>";
$rab[1] = date("F d, Y", $r[date]);
$rab[2] = stripslashes($r[blog]);
$rab[3] = "<a href='blog-".$r[id].".html#comments'>Comments</a>";
$rab[4] = $cnum2;
$rab[5] = "<a href='blog-".$r[id].".html#comments'>Post a Comment</a>";
$rab[6] = "<a href='user-".$r[author].".html'>".$r[author]."</a>";
eval (" ?>" . preg_replace($pab, $rab, stripslashes($temp[0])) . " <?php ");
}
echo "<br /><br /><center><a href='blog-archive";
if ($_GET['cat']) {
echo "-".$_GET['cat'];
}
echo ".html'>[ <u>Archive</u> ]</a></center>";
}

if ($_GET['act'] == "register2") {
$amount1 = sprintf("SELECT * FROM ".$pre."users WHERE username = '%s'",
    mysql_real_escape_string($_POST[username]));
$amount = mysql_num_rows(mysql_query($amount1));

if ($amount > "0") {
echo "Sorry but that username is already taken!";
} else {
$sql = mysql_query("INSERT INTO ".$pre."users VALUES ('null', '".addslashes($_POST['username'])."', '".md5($_POST['password'])."', '".$_POST['email']."', 'Member', '', '', '', '', '', '', '', '', '', '".time()."', '".time()."', '')") or die (mysql_error());

if ($_POST['url']) {
$url = $_POST['url'];
} else {
$url = $_SERVER['HTTP_REFERER'];
}

if ($sql == TRUE) {
setcookie("blogphp_username", $_POST['username'], time()+3600*60*24*14);
setcookie("blogphp_password", md5($_POST['password']), time()+3600*60*24*14);
eval (" ?>" . headera() . " <?php ");
echo re_direct("1500", $url);

echo "Your now registered and logged in! <a href='index.php'>Return to where you were</a>";
}
}
}

if ($_GET['act'] == "register") {
eval (" ?>" . headera() . " <?php ");
$temp = mysql_fetch_row(mysql_query("SELECT template FROM ".$pre."templates WHERE name = 'register'"));

$p[] = "{startform}";
$p[] = "{endform}";
$p[] = "{username}";
$p[] = "{password}";
$p[] = "{email}";
$p[] = "{submit}";
$r[] = "<form action='index.php?act=register2' method='post'>";
$r[] = "</form>";
$r[] = "<input type='text' name='username'>";
$r[] = "<input type='password' name='password'>";
$r[] = "<input type='register' name='email'>";
$r[] = "<input type='submit' value='Register'>";

echo str_replace($p, $r, stripslashes($temp[0]));
}

if ($_GET['act'] == "login") {
eval (" ?>" . headera() . " <?php ");
$temp = mysql_fetch_row(mysql_query("SELECT template FROM ".$pre."templates WHERE name = 'login'"));

$p[] = "{startform}";
$p[] = "{endform}";
$p[] = "{username}";
$p[] = "{password}";
$p[] = "{submit}";
$r[] = "<form action='index.php?act=login2' method='post'>";
$r[] = "</form>";
$r[] = "<input type='text' name='username'>";
$r[] = "<input type='password' name='password'>";
$r[] = "<input type='submit' value='Login'>";

echo str_replace($p, $r, stripslashes($temp[0]));
}

if ((($_POST['username']) && ($_POST['password']) && ($_GET['act'] == "login2"))) {

$prf = sprintf("SELECT * FROM ".$pre."users WHERE username = '%s' AND password = '%s' LIMIT 1",
mysql_real_escape_string($_POST['username']),
mysql_real_escape_string(md5($_POST['password'])));

$login_check = mysql_num_rows(mysql_query($prf));

if ($login_check == "1") {
setcookie("blogphp_username", $_POST['username'], time()+3600*60*24*14);
setcookie("blogphp_password", md5($_POST['password']), time()+3600*60*24*14);

eval (" ?>" . headera() . " <?php ");

if ($_POST['url']) {
$url = $_POST['url'];
} else {
$url = $_SERVER['HTTP_REFERER'];
}

echo "Welcome back ".$_POST[username]."! Enjoy your stay. <a href='index.php'>Return</a>";
} else {
eval (" ?>" . headera() . " <?php ");
echo "Sorry but that username and password combo is incorrect. Please try again.";
}
}

if ($_GET['act'] == "logout") {
if (htmlentities($_COOKIE[blogphp_username]) == "") {
eval (" ?>" . headera() . " <?php ");
echo "You are already logged out";
} else {
setcookie("blogphp_username", "", time()-3600*60*24*14, "/");
setcookie("blogphp_password", "", time()-3600*60*24*14, "/");

eval (" ?>" . headera() . " <?php ");
echo "You have now successfully logged out<br>";
}
}
}

eval (" ?>" . footer() . " <?php ");
?>