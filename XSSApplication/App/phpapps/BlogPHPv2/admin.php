<?php
$protect = "yes";
include ("config.php");
echo headera();
?>

<?php
if ($_GET['act'] == "convertonecms") {
echo "Before we can convert, we need the field name used for the blog textbox on OneCMS:<br><br><form action='admin.php?act=convertonecms2' method='post'><b>Field Name:</b><select name='fieldname'>";
$abca = mysql_query("SELECT * FROM onecms_fields WHERE type = 'textarea' AND cat = ''");
for($i = 1; $r = mysql_fetch_assoc($abca); $i++) {
echo "<option value='".$r[name]."'>".$r[name]."</option>";
}
echo "</select><br><input type='submit' value='Convert'></form>";
}

if ($_GET['act'] == "convertonecms2") {
echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm Conversion?");
if (agree)
document.write("");
else
history.go(-1);
</SCRIPT>';

$sql = mysql_query("SELECT * FROM onecms_cat");
while($r = mysql_fetch_array($sql)) {
if ($r[name] == "media") {
} else {
mysql_query("INSERT INTO ".$pre."cat VALUES ('null', '".$r[name]."')");
}
}
echo "Categories transferred successfully<br>";

$sql2 = mysql_query("SELECT * FROM af_manager");
while($s = mysql_fetch_array($sql2)) {
mysql_query("INSERT INTO ".$pre."links VALUES ('null', '".$s[sitename]."', '".$s[siteurl]."', '".$s[sitename]."', '".$s[date]."')");
}
echo "Affiliates transferred successfully<br>";

$sql3 = mysql_query("SELECT * FROM onecms_pages WHERE type = 'backend'");
while($q = mysql_fetch_array($sql3)) {
if ((($q[url] == "contact") or ($q[url] == "contactus") or ($q[url] == "contactme"))) {
} else {
mysql_query("INSERT INTO ".$pre."pages VALUES ('null', '".$q[name]."', '".$q[content]."', '".$q[url]."', '".$_COOKIE[blogphp_username]."')");
}
}
echo "Pages transferred successfully<br>";

$sql4 = mysql_query("SELECT * FROM onecms_users");
while($p = mysql_fetch_array($sql4)) {
$usernum = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."users WHERE username = '".$p[username]."'"));
if ($usernum == "0") {
$ft = mysql_fetch_row(mysql_query("SELECT nickname,aim,msn,yahoo,icq,gtalk,website,avatar,birthday FROM onecms_profile WHERE username = '".$p[username]."'"));
mysql_query("INSERT INTO ".$pre."users VALUES ('null', '".$p[username]."', '".$p[password]."', '".$p[email]."', '".$p[level]."', '".$ft[0]."', '".$ft[1]."', '".$ft[2]."', '".$ft[3]."', '".$ft[4]."', '".$ft[5]."', '".$ft[6]."', '".$ft[7]."', '".$ft[8]."', '".$p[logged]."', '".time()."', '')") or die (mysql_error());
}
}
echo "Users transferred successfully<br>";

$sql5 = mysql_query("SELECT * FROM onecms_content");
while($t = mysql_fetch_array($sql5)) {
$data = mysql_fetch_row(mysql_query("SELECT data FROM onecms_fielddata WHERE name = '".$_POST['fieldname']."' AND id2 = '".$t[id]."' AND cat = 'content'"));
mysql_query("INSERT INTO ".$pre."blogs VALUES ('null', '".$t[name]."', '".$t[username]."', '".$t[cat]."', '".$data[0]."', '".$t[date]."', '".date("m", $t[date])."', '".date("Y", $t[date])."')");
}
echo "Content transferred successfully<br>";

echo "<br>OneCMS Data transferred successfully to BlogPHP! <a href='admin.php'>Return to the admin panel</a>";
}

if ($_GET['act'] == "convert") {
echo "What is this convert page? Basically if you use wordpress or onecms, you can easily transfer data from the system to BlogPHP. For OneCMS users, categories, affiliates (links in blogphp), pages and content, for Wordpress users, categories, comments, links and content. <u>Please remember in order to convert, a copy of OneCMS or Wordpress has to be in the <b>same</b> database as the one running BlogPHP</u><br><br><a href='admin.php?act=convertonecms'>OneCMS</a> | <a href='admin.php?act=convertwp'>Wordpress</a>";
}

if ($_GET['act'] == "convertwp") {
echo "Before we can convert, we need the table prefix for wordpress:<br><br><form action='admin.php?act=convertwp2' method='post'><b>Prefix:</b><input type='text' name='prefix' value='wp_'><br><input type='submit' value='Convert'></form>";
}

if ($_GET['act'] == "convertwp2") {
echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm Conversion?");
if (agree)
document.write("");
else
history.go(-1);
</SCRIPT>';
$pcat = $_POST['prefix'];

$sql = mysql_query("SELECT * FROM ".$pcat."categories");
while($r = mysql_fetch_array($sql)) {
if ($r[name] == "media") {
} else {
mysql_query("INSERT INTO ".$pre."cat VALUES ('null', '".$r[cat_name]."')");
}
}
echo "Categories transferred successfully<br>";

$sql = mysql_query("SELECT * FROM ".$pcat."comments");
while($q = mysql_fetch_array($sql)) {
mysql_query("INSERT INTO ".$pre."comments VALUES ('null', '".$q[comment_post_ID]."', '".$q[comment_content]."', '".$q[comment_author]."', '".$q[comment_author_email]."', '".$q[comment_author_url]."', '".$q[comment_author_IP]."', '".time()."')");
}
echo "Comments transferred successfully<br>";

$sql2 = mysql_query("SELECT * FROM ".$pcat."links");
while($s = mysql_fetch_array($sql2)) {
mysql_query("INSERT INTO ".$pre."links VALUES ('null', '".$s[link_name]."', '".$s[link_url]."', 'Visit ".$s[link_name]."', '".time()."')");
}
echo "Links transferred successfully<br>";

$sql4 = mysql_query("SELECT * FROM ".$pcat."users");
while($p = mysql_fetch_array($sql4)) {
$usernum = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."users WHERE username = '".$p[username]."'"));
if ($usernum == "0") {
if (($p[user_level] == "10") or ($p[user_level] == "0")) {
if ($p[user_level] == "10") {
$levelt = "Admin";
}
if ($p[user_level] == "0") {
$levelt = "Member";
}
} else {
$levelt = "Staff";
}
mysql_query("INSERT INTO ".$pre."users VALUES ('null', '".$p[user_login]."', '".$p[user_password]."', '".$p[user_email]."', '".$levelt."', '".$p[user_firstname].$p[user_lastname]."', '".$p[user_aim]."', '".$p[user_msn]."', '".$p[user_yim]."', '".$p[user_icq]."', '', '".$p[user_url]."', '', '', '".$p[user_status]."', '".time()."', '')") or die (mysql_error());
}
}
echo "Users transferred successfully<br>";

$sql5 = mysql_query("SELECT * FROM ".$pcat."posts");
while($t = mysql_fetch_array($sql5)) {
$fetch = mysql_fetch_row(mysql_query("SELECT cat_name FROM ".$pcat."categories WHERE id = '".$p[post_category]."'"));
$tcat = $fetch[0];
mysql_query("INSERT INTO ".$pre."blogs VALUES ('null', '".$t[post_title]."', '".$t[post_author]."', '".$tcat."', '".$t[post_content]."', '".time()."', '".date("m")."', '".date("Y")."')");
}
echo "Content transferred successfully<br>";

echo "<br>WordPress Data transferred successfully to BlogPHP! <a href='admin.php'>Return to the admin panel</a>";
}

if ($_GET['act'] == "denied") {
echo "Sorry but it looks like you are not an admin or author, meaning you cannot access this area of the site";
}

if ($_GET['act'] == "plugins") {
echo "<iframe src='http://www.insanevisions.com/bplugins.php' width='100%' height='100%' frameborder='0'></iframe>";
}

if ($_GET['act'] == "support") {
echo "<center><a href='admin.php?act=support1'><b>Support</b></a> | <a href='admin.php?act=support2'><b>Help Files</b></a> | <a href='admin.php?act=support3'><b>FAQ</b></a></center>";
}

if ($_GET['act'] == "support1") {
echo "<iframe src='http://www.insanevisions.com/support3.php?domain=".$domain."' width='100%' height='100%' frameborder='0'></iframe>";
}

if ($_GET['act'] == "support2") {
echo "<iframe src='http://www.insanevisions.com/help2.php' width='100%' height='100%' frameborder='0'></iframe>";
}

if ($_GET['act'] == "support3") {
echo "<iframe src='http://www.insanevisions.com/faq2.php' width='100%' height='100%' frameborder='0'></iframe>";
}

if ($_GET['act'] == "") {
echo "<iframe src='http://www.insanevisions.com/blogphp-updates.php?version=".$version."&domain=".$domain."&siteurl=".$siteurl."&sitename=".$sitename."' width='100%' height='20%' frameborder='0'></iframe><table cellpadding='5' cellspacing='0' border='0' width='100%'><tr><td><b>#</b></td><td><b>Subject</b></td><td><b>Category</b></td><td><b>Date</b></td><td><b>Actions</b></td></tr>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$limit = "10";

$from = (($page * $limit) - $limit);

$sql = mysql_query("SELECT * FROM ".$pre."blogs ORDER BY `id` DESC LIMIT $from, $limit");
while($r = mysql_fetch_array($sql)) {
echo "<tr><td>".$r[id]."</td><td><a href='blog-".$r[id].".html'>".stripslashes($r[subject])."</a></td><td>".$r[cat]."</td><td>".date("F d, Y", $r[date])."</td><td><a href='admin.php?act=editblogs&id=".$r[id]."'>Edit</a> | <a href='admin.php?act=delblogs&id=".$r[id]."'>Delete</a></td></tr>";
}
echo "</table>";

$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."blogs"),0);
$total_pages = ceil($total_results / $limit);

if ($total_pages > "1") {

echo "<center>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"admin.php?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"admin.php?page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"admin.php?page=$next\">Next>></a>";
}
echo "</center>";
}

echo "<br><form action='admin.php?act=addblogs2' method='post' onsubmit='return submitForm();'><table cellpadding='3' cellspacing='0'><tr><td><b>Subject:</b></td><td><input type='text' name='subject'></td></tr><tr><td><b>Category:</b></td><td><select name='cat'>";
$sql = mysql_query("SELECT * FROM ".$pre."cat ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
echo "<option value='".stripslashes($r[name])."'>".stripslashes($r[name])."</option>";
}
echo "</select></td></tr><tr><td><b>Blog:</b></td><td><textarea name='blog' cols='50' rows='15'></textarea><input type='hidden' name='url' value='".$fpage."'></td></tr><tr><td><input type='submit' value='Add' name='type'></td><td><input type='submit' value='Save' name='type'></td></tr></table></form><br><br><br><br><br><br><br>";
}

if ($_GET['act'] == "users") {
if ($level == "Admin") {
echo "<table cellpadding='5' cellspacing='0' border='0' width='100%'><tr><td><b>#</b></td><td><b>Username</b></td><td><b>Level</b></td><td><b>Actions</b></td></tr>";
$sql = mysql_query("SELECT * FROM ".$pre."users ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
echo "<tr><td>".$r[id]."</td><td>".stripslashes($r[username])."</td><td>".$r[level]."</td><td><a href='admin.php?act=editusers&id=".$r[id]."'>Edit</a> | <a href='admin.php?act=delusers&id=".$r[id]."'>Delete</a></td></tr>";
}
echo "</table><br><br><br><br>";

echo "<br><br>";
echo "<form action='admin.php?act=addusers2' method='post' onsubmit='return submitForm();'><table cellpadding='5' cellspacing='0' border='0' width='100%'><tr><td><b>Username:</b></td><td><input type='text' name='username'></td></tr><tr><td><b>Password:</b></td><td><input type='text' name='password'></td></tr><tr><td><b>E-Mail:</b></td><td><input type='text' name='email'></td></tr><tr><td><b>Level:</b></td><td><select name='level'><option value='Admin'>Admin</option><option value='Author'>Author</option><option value='Member'>Member</option></td></tr><tr><td><input type='submit' value='Add User'><input type='hidden' name='url' value='".$fpage."'></td></tr></table></form>";
} else {
echo "Sorry but you do not have access, this is an admin-only area";
}
}

if ($_GET['act'] == "addusers2") {
if ($level == "Admin") {
$query = mysql_query("INSERT INTO ".$pre."users VALUES ('null', '".addslashes($_POST["username"])."', '".md5($_POST["password"])."', '".addslashes($_POST["email"])."', '".$_POST["level"]."', '', '', '', '', '', '', '', '', '', '0', '".time()."', '')");

if ($query == TRUE) {
echo re_direct("1500", $_POST['url']);
echo "User '<b>".$_POST["username"]."</b>' has been posted. <a href='".$_POST['url']."'>Return</a>";
} else {
echo "User could not be added. Please report this issue through the <a href='admin.php?act=support'><b>support area</b></a>.";
}
} else {
echo "Sorry but you do not have access, this is an admin-only area";
}
}

if ($_GET['act'] == "editusers") {
if ($level == "Admin") {
echo "<br><br>";

$data = mysql_fetch_row(mysql_query("SELECT username,email,level FROM ".$pre."users WHERE id = '".$_GET['id']."'"));
echo "<form action='admin.php?act=editusers2&id=".$_GET['id']."&name=".$data[0]."' method='post' onsubmit='return submitForm();'><table cellpadding='5' cellspacing='0' border='0' width='100%'><tr><td><b>Username:</b></td><td><input type='text' name='username' value='".$data[0]."'></td></tr><tr><td><b>New Password:</b></td><td><input type='text' name='password'></td></tr><tr><td><b>E-Mail:</b></td><td><input type='text' name='email' value='".$data[1]."'></td></tr><tr><td><b>Level:</b></td><td><select name='level'><option value='".$data[2]."' selected style='font-weight: bold'>&#8226;".$data[2]."</option><option value='Admin'>Admin</option><option value='Author'>Author</option><option value='Member'>Member</option></td></tr><tr><td><input type='submit' value='Update User'><input type='hidden' name='url' value='".$_SERVER['HTTP_REFERER']."'></td></tr></table></form>";
} else {
echo "Sorry but you do not have access, this is an admin-only area";
}
}

if ($_GET['act'] == "editusers2") {
if ($level == "Admin") {
if ($_POST['password']) {
$pass = ", password = '".md5($_POST["password"])."'";
}
$query = mysql_query("UPDATE ".$pre."users SET username = '".addslashes($_POST["username"])."', email = '".$_POST["email"]."', level = '".$_POST["level"]."'".$pass." WHERE id = '".$_GET['id']."'");

if ($query == TRUE) {
echo re_direct("1500", $_POST['url']);
echo "User '<b>".$_POST["username"]."</b>' has been updated. <a href='".$_POST['url']."'>Return</a>";
} else {
echo "User could not be updated. Please report this issue through the <a href='admin.php?act=support'><b>support area</b></a>.";
}
} else {
echo "Sorry but you do not have access, this is an admin-only area";
}
}

if ($_GET['act'] == "delusers") {
if ($level == "Admin") {
$query = mysql_query("DELETE FROM ".$pre."users WHERE id = '".$_GET['id']."'") or die(mysql_error());

if ($query == TRUE) {
echo re_direct("1500", $_SERVER['HTTP_REFERER']);
echo "User has been deleted. <a href='".$_SERVER['HTTP_REFERER']."'>Return</a>";
} else {
echo "User could not be deleted. Please report this issue through the <a href='admin.php?act=support'><b>support area</b></a>.";
}
} else {
echo "Sorry but you do not have access, this is an admin-only area";
}
}
?>

<?php
if ($_GET['act'] == "pages") {
echo "<table cellpadding='5' cellspacing='0' border='0' width='100%'><tr><td><b>#</b></td><td><b>Title</b></td><td><b>Author</b></td><td><b>Date</b></td><td><b>Actions</b></td></tr>";
$sql = mysql_query("SELECT * FROM ".$pre."pages ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
echo "<tr><td>".$r[id]."</td><td><a href='page-".stripslashes($r[url]).".html'>".stripslashes($r[title])."</a></td><td><a href='user-".$r[author].".html'>".$r[author]."</a></td><td>".date("F d, Y", $r[date])."</td><td><a href='admin.php?act=editpages&id=".$r[id]."'>Edit</a> | <a href='admin.php?act=delpages&id=".$r[id]."'>Delete</a></td></tr>";
}
echo "</table><br><br><br><br>";

echo "<br><br>";
echo "<form action='admin.php?act=addpages2' method='post' onsubmit='return submitForm();'><table cellpadding='3' cellspacing='0'><tr><td><b>Title:</b></td><td><input type='text' name='title'></td></tr><tr><td><b>URL:</b></td><td><input type='text' name='url2'> (ex. advertising)</td></tr><tr><td><b>Content:</b></td><td><textarea name='content' cols='50' rows='12'></textarea><input type='hidden' name='url' value='".$fpage."'></td></tr><tr><td><input type='submit' value='Add Page'></td></tr></table></form>";
}

if ($_GET['act'] == "addpages2") {
$autobr = preg_replace("/<br>\n/","\n",addslashes($_POST['content']));
$autobr = preg_replace("/<br \/>\n/","\n",addslashes($_POST['content']));
$autobr = preg_replace("/(\015\012)|(\015)|(\012)/","<br />\n",addslashes($_POST['content']));

$query = mysql_query("INSERT INTO ".$pre."pages VALUES ('null', '".addslashes($_POST["title"])."', '".$autobr."', '".$_POST["url2"]."', '".$_COOKIE[blogphp_username]."')");

if ($query == TRUE) {
echo re_direct("1500", $_POST['url']);
echo "Page '<b>".$_POST["title"]."</b>' has been posted. <a href='".$_POST['url']."'>Return</a>";
} else {
echo "Page could not be added. Please report this issue through the <a href='admin.php?act=support'><b>support area</b></a>.";
}
}

if ($_GET['act'] == "editpages") {
echo "<br><br>";

$data = mysql_fetch_row(mysql_query("SELECT title,url,content FROM ".$pre."pages WHERE id = '".$_GET['id']."'"));
$content = str_replace("<br />", "", $data[2]);
echo "<form action='admin.php?act=editpages2&id=".$_GET['id']."' method='post' onsubmit='return submitForm();'><table cellpadding='3' cellspacing='0'><tr><td><b>Title:</b></td><td><input type='text' name='title' value='".$data[0]."'></td></tr><tr><td><b>URL:</b></td><td><input type='text' name='url2' value='".$data[1]."'> (ex. advertising)</td></tr><tr><td><b>Content:</b></td><td><textarea name='content' cols='45' rows='12'>".$content."</textarea></td></tr><tr><td><input type='submit' value='Update Page'><input type='hidden' name='url' value='".$_SERVER['HTTP_REFERER']."'></td></tr></table></form>";
}

if ($_GET['act'] == "editpages2") {
$autobr = preg_replace("/<br>\n/","\n",addslashes($_POST['content']));
$autobr = preg_replace("/<br \/>\n/","\n",addslashes($_POST['content']));
$autobr = preg_replace("/(\015\012)|(\015)|(\012)/","<br />\n",addslashes($_POST['content']));

$query = mysql_query("UPDATE ".$pre."pages SET title = '".addslashes($_POST["title"])."', url = '".$_POST["url2"]."', content = '".$autobr."' WHERE id = '".$_GET['id']."'");

if ($query == TRUE) {
echo re_direct("1500", $_POST['url']);
echo "Page '<b>".addslashes($_POST["title"])."</b>' has been updated. <a href='".$_POST['url']."'>Return</a>";
} else {
echo "Page could not be updated. Please report this issue through the <a href='admin.php?act=support'><b>support area</b></a>.";
}
}

if ($_GET['act'] == "delpages") {
$query = mysql_query("DELETE FROM ".$pre."pages WHERE id = '".$_GET['id']."'") or die(mysql_error());

if ($query == TRUE) {
echo re_direct("1500", $_SERVER['HTTP_REFERER']);
echo "Page has been deleted. <a href='".$_SERVER['HTTP_REFERER']."'>Return</a>";
} else {
echo "Page could not be deleted. Please report this issue through the <a href='admin.php?act=support'><b>support area</b></a>.";
}
}
?>

<?php
if ($_GET['act'] == "comments") {
echo "<table cellpadding='5' cellspacing='0' border='0' width='100%'><tr><td><b>#</b></td><td><b>Blog</b></td><td><b>Date</b></td><td><b>Actions</b></td></tr>";
$sql = mysql_query("SELECT * FROM ".$pre."comments ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
$fetch = mysql_fetch_row(mysql_query("SELECT subject FROM ".$pre."blogs WHERE id = '".$r[aid]."'"));
echo "<tr><td>".$r[id]."</td><td><a href='blog-".stripslashes($r[aid]).".html'>".stripslashes($fetch[0])."</a></td><td>".date("F d, Y", $r[date])."</td><td><a href='admin.php?act=editcomments&id=".$r[id]."'>Edit</a> | <a href='admin.php?act=delcomments&id=".$r[id]."'>Delete</a></td></tr>";
}
echo "</table>";
}

if ($_GET['act'] == "delcomments") {
$query = mysql_query("DELETE FROM ".$pre."comments WHERE id = '".$_GET['id']."'") or die(mysql_error());

if ($query == TRUE) {
echo re_direct("1500", $_SERVER['HTTP_REFERER']);
echo "Comment has been deleted. <a href='".$_SERVER['HTTP_REFERER']."'>Return</a>";
} else {
echo "Comment could not be deleted. Please report this issue through the <a href='admin.php?act=comments'><b>support area</b></a>.";
}
}

if ($_GET['act'] == "editcomments") {
echo "<br><br>";

$data = mysql_fetch_row(mysql_query("SELECT comment FROM ".$pre."comments WHERE id = '".$_GET['id']."'"));
echo "<form action='admin.php?act=editcomments2&id=".$_GET['id']."' method='post' onsubmit='return submitForm();'><table cellpadding='3' cellspacing='0'><tr><td><b>Comment:</b></td><td><textarea name='comment' cols='45' rows='12'>".stripslashes($data[0])."</textarea></td></tr><tr><td><input type='submit' value='Update Comment'><input type='hidden' name='url' value='".$_SERVER['HTTP_REFERER']."'></td></tr></table></form>";
}

if ($_GET['act'] == "editcomments2") {
$autobr = preg_replace("/<br>\n/","\n",addslashes($_POST['comment']));
$autobr = preg_replace("/<br \/>\n/","\n",addslashes($_POST['comment']));
$autobr = preg_replace("/(\015\012)|(\015)|(\012)/","<br />\n",addslashes($_POST['comment']));

$query = mysql_query("UPDATE ".$pre."comments SET comment = '".$autobr."' WHERE id = '".$_GET['id']."'");

if ($query == TRUE) {
echo re_direct("1500", $_POST['url']);
echo "Comment has been updated. <a href='".$_POST['url']."'>Return</a>";
} else {
echo "Comment could not be updated. Please report this issue through the <a href='admin.php?act=support'><b>support area</b></a>.";
}
}
?>

<?php
if ($_GET['act'] == "blogs") {
echo "<table cellpadding='5' cellspacing='0' border='0' width='100%'><tr><td><b>#</b></td><td><b>Subject</b></td><td><b>Category</b></td><td><b>Date</b></td><td><b>Actions</b></td></tr>";
$sql = mysql_query("SELECT * FROM ".$pre."blogs ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
echo "<tr><td>".$r[id]."</td><td>".stripslashes($r[subject])."</td><td>".$r[cat]."</td><td>".date("F d, Y", $r[date])."</td><td><a href='admin.php?act=editblogs&id=".$r[id]."'>Edit</a> | <a href='admin.php?act=delblogs&id=".$r[id]."'>Delete</a></td></tr>";
}
echo "</table><br><br><br><br>";

echo "<br><br>";
echo "<form action='admin.php?act=addblogs2' method='post' onsubmit='return submitForm();'><table cellpadding='3' cellspacing='0'><tr><td><b>Subject:</b></td><td><input type='text' name='subject'></td></tr><tr><td><b>Category:</b></td><td><select name='cat'>";
$sql = mysql_query("SELECT * FROM ".$pre."cat ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
echo "<option value='".stripslashes($r[name])."'>".stripslashes($r[name])."</option>";
}
echo "</select></td></tr><tr><td><b>Blog:</b></td><td><textarea name='blog' cols='50' rows='15'></textarea><input type='hidden' name='url' value='".$fpage."'></td></tr><tr><td><input type='submit' value='Add' name='type'></td><td><input type='submit' value='Save' name='type'></td></tr></table></form>";
}

if ($_GET['act'] == "addblogs2") {
$autobr = preg_replace("/<br>\n/","\n",addslashes($_POST['blog']));
$autobr = preg_replace("/<br \/>\n/","\n",addslashes($_POST['blog']));
$autobr = preg_replace("/(\015\012)|(\015)|(\012)/","<br />\n",addslashes($_POST['blog']));

if ($_POST['type'] == "Save") {
$type = "save";
} else {
$type = "";
}

$query = mysql_query("INSERT INTO ".$pre."blogs VALUES ('null', '".addslashes($_POST["subject"])."', '".$_COOKIE[blogphp_username]."', '".addslashes($_POST['cat'])."', '".$autobr."', '".time()."', '".date("m")."', '".date("Y")."', '".$type."')");
$id = mysql_fetch_row(mysql_query("SELECT id FROM ".$pre."blogs WHERE date = '".time()."' AND subject = '".addslashes($_POST["subject"])."'"));

// subscription notifications
if ($type == "") {
$sql = mysql_query("SELECT * FROM ".$pre."subscriptions ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= "From: ".$sitename." <".$email.">"."\r\n";
$headers .= "Reply-To: ".$email."\r\n";

$message = "Hello,<br><br>I would like to inform you that the Blog website you subscribed too, '".$sitename."', has updated with a new blog post entitled '".$_POST["subject"]."''. You can view the new blog below:<br><br><a href='".$siteurl."/blog-".$id[0].".html'>".$_POST["subject"]."</a><br><br>Thank you for your interest in '".$sitename."' and the website.<br><br>Sincerely,<br>Team of ".$sitename;

mail($r[email], "Blog Subscription Notice - ".$sitename, $message, $headers);
}
}
// end subescription notifications

if ($query == TRUE) {
echo re_direct("1500", $_POST['url']);
echo "Blog '<b>".$_POST["subject"]."</b>' has been posted. <a href='".$_POST['url']."'>Return</a>";
} else {
echo "Blog could not be added. Please report this issue through the <a href='admin.php?act=support'><b>support area</b></a>.";
}
}

if ($_GET['act'] == "editblogs") {
echo "<br><br>";

$data = mysql_fetch_row(mysql_query("SELECT subject,cat,blog,status FROM ".$pre."blogs WHERE id = '".$_GET['id']."'"));
echo "<form action='admin.php?act=editblogs2&id=".$_GET['id']."' method='post' onsubmit='return submitForm();'><input type='hidden' name='url' value='".$_SERVER['HTTP_REFERER']."'><input type='hidden' name='type1' value='".$data[3]."'><table cellpadding='3' cellspacing='0'><tr><td><b>Subject:</b></td><td><input type='text' name='subject' value='".$data[0]."'></td></tr><tr><td><b>Category:</b></td><td><select name='cat'><option value='".$data[1]."' selected style='font-weight: bold'>&#8226;".$data[1]."</option>";
$sql = mysql_query("SELECT * FROM ".$pre."cat ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
echo "<option value='".stripslashes($r[name])."'>".stripslashes($r[name])."</option>";
}
echo "</select></td></tr><tr><td><b>Blog:</b></td><td><textarea name='blog' cols='45' rows='12'>".stripslashes($data[2])."</textarea></td></tr><tr><td><input type='submit' name='type' value='Update Blog'></td>";
if ($data[3] == "save") {
echo "<td><input type='submit' name='type' value='Update and Un-Save'></td>";
}
echo "</tr></table></form>";
}

if ($_GET['act'] == "editblogs2") {
$autobr = preg_replace("/<br>\n/","\n",addslashes($_POST['blog']));
$autobr = preg_replace("/<br \/>\n/","\n",addslashes($_POST['blog']));
$autobr = preg_replace("/(\015\012)|(\015)|(\012)/","<br />\n",addslashes($_POST['blog']));

if ($_POST['type'] == "Update and Un-Save") {
$type = "";
} else {
$type = $_POST['type1'];
}

$query = mysql_query("UPDATE ".$pre."blogs SET subject = '".addslashes($_POST["subject"])."', cat = '".$_POST["cat"]."', blog = '".$autobr."', status = '".$type."' WHERE id = '".$_GET['id']."'");

// subscription notifications
if ($_POST['type'] == "Update and Un-Save") {
$sql = mysql_query("SELECT * FROM ".$pre."subscriptions ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= "From: ".$sitename." <".$email.">"."\r\n";
$headers .= "Reply-To: ".$email."\r\n";

$message = "Hello,<br><br>I would like to inform you that the Blog website you subscribed too, '".$sitename."', has updated with a new blog post entitled '".$_POST["subject"]."''. You can view the new blog below:<br><br><a href='".$siteurl."/blog-".$id[0].".html'>".$_POST["subject"]."</a><br><br>Thank you for your interest in '".$sitename."' and the website.<br><br>Sincerely,<br>Team of ".$sitename;

mail($r[email], "Blog Subscription Notice - ".$sitename, $message, $headers);
}
}
// end subescription notifications

if ($query == TRUE) {
echo re_direct("1500", $_POST['url']);
echo "Blog '<b>".addslashes($_POST["subject"])."</b>' has been updated. <a href='".$_POST['url']."'>Return</a>";
} else {
echo "Blog could not be updated. Please report this issue through the <a href='admin.php?act=support'><b>support area</b></a>.";
}
}

if ($_GET['act'] == "delblogs") {
$query = mysql_query("DELETE FROM ".$pre."blogs WHERE id = '".$_GET['id']."'") or die(mysql_error());
$query2 = mysql_query("DELETE FROM ".$pre."comments WHERE aid = '".$_GET['id']."'") or die(mysql_error());

if (($query == TRUE) && ($query2 == TRUE)) {
echo "Blog has been deleted. <a href='".$_SERVER['HTTP_REFERER']."'>Return</a>";
} else {
echo "Blog could not be deleted. Please report this issue through the <a href='admin.php?act=support'><b>support area</b></a>.";
}
}
?>

<?php
if ($_GET['act'] == "cat") {
echo "<table cellpadding='5' cellspacing='0' border='0' width='100%'><tr><td><b>#</b></td><td><b>Name</b></td><td><b># of Blogs</b></td><td><b>Actions</b></td></tr>";
$sql = mysql_query("SELECT * FROM ".$pre."cat ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
$cnum = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."blogs WHERE cat = '".$r[name]."'"));
echo "<tr><td>".$r[id]."</td><td>".stripslashes($r[name])."</td><td>".$cnum."</td><td><a href='admin.php?act=editcat&id=".$r[id]."'>Edit</a> | <a href='admin.php?act=delcat&id=".$r[id]."'>Delete</a></td></tr>";
}
echo "</table><br><br><br><br>";

echo "<br><br>";
echo "<form action='admin.php?act=addcat2' method='post' onsubmit='return submitForm();'><table cellpadding='5' cellspacing='0' border='0' width='100%'><tr><td><b>Name:</b></td><td><input type='text' name='name'></td></tr><tr><td><input type='submit' value='Add Category'><input type='hidden' name='url' value='".$fpage."'></td></tr></table></form>";
}

if ($_GET['act'] == "addcat2") {
$query = mysql_query("INSERT INTO ".$pre."cat VALUES ('null', '".addslashes($_POST["name"])."')");

if ($query == TRUE) {
echo re_direct("1500", $_POST['url']);
echo "Category '<b>".$_POST["name"]."</b>' has been posted. <a href='".$_POST['url']."'>Return</a>";
} else {
echo "Category could not be added. Please report this issue through the <a href='admin.php?act=support'><b>support area</b></a>.";
}
}

if ($_GET['act'] == "editcat") {
echo "<br><br>";

$data = mysql_fetch_row(mysql_query("SELECT name FROM ".$pre."cat WHERE id = '".$_GET['id']."'"));
echo "<form action='admin.php?act=editcat2&id=".$_GET['id']."&name=".$data[0]."' method='post' onsubmit='return submitForm();'><table cellpadding='5' cellspacing='0' border='0' width='100%'><tr><td><b>Name:</b></td><td><input type='text' name='name' value='".$data[0]."'></td></tr><tr><td><input type='submit' value='Update Category'><input type='hidden' name='url' value='".$_SERVER['HTTP_REFERER']."'></td></tr></table></form>";
}

if ($_GET['act'] == "editcat2") {
$query = mysql_query("UPDATE ".$pre."cat SET name = '".addslashes($_POST["name"])."' WHERE id = '".$_GET['id']."'");
$query2 = mysql_query("UPDATE ".$pre."blogs SET cat = '".addslashes($_POST["name"])."' WHERE cat = '".$_GET['name']."'");

if (($query == TRUE) && ($query2 == TRUE)) {
echo "Category '<b>".$_POST["name"]."</b>' has been updated. <a href='".$_POST['url']."'>Return</a>";
} else {
echo "Category could not be updated. Please report this issue through the <a href='admin.php?act=support'><b>support area</b></a>.";
}
}

if ($_GET['act'] == "delcat") {
$query = mysql_query("DELETE FROM ".$pre."cat WHERE id = '".$_GET['id']."'") or die(mysql_error());

if ($query == TRUE) {
echo re_direct("1500", $_SERVER['HTTP_REFERER']);
echo "Category has been deleted. <a href='".$_SERVER['HTTP_REFERER']."'>Return</a>";
} else {
echo "Category could not be deleted. Please report this issue through the <a href='admin.php?act=support'><b>support area</b></a>.";
}
}
?>

<?php
if ($_GET['act'] == "links") {
echo "<table cellpadding='5' cellspacing='0' border='0' width='100%'><tr><td><b>#</b></td><td><b>Link</b></td><td><b>Date</b></td><td><b>Actions</b></td></tr>";
$sql = mysql_query("SELECT * FROM ".$pre."links ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
echo "<tr><td>".$r[id]."</td><td><a href='".stripslashes($r[url])."' alt='".$r[alt]."' title='".$r[alt]."'>".stripslashes($r[name])."</a></td><td>".date("F d, Y", $r[date])."</td><td><a href='admin.php?act=editlinks&id=".$r[id]."'>Edit</a> | <a href='admin.php?act=dellinks&id=".$r[id]."'>Delete</a></td></tr>";
}
echo "</table><br><br><br><br>";

echo "<br><br>";
echo "<form action='admin.php?act=addlinks2' method='post' onsubmit='return submitForm();'><table cellpadding='5' cellspacing='0' border='0' width='100%'><tr><td><b>Name:</b></td><td><input type='text' name='name'></td></tr><tr><td><b>URL:</b></td><td><input type='text' name='url2'></td></tr><tr><td><b>Alt Text:</b></td><td><input type='text' name='alt'></td></tr><tr><td><input type='submit' value='Add Link'><input type='hidden' name='url' value='".$fpage."'></td></tr></table></form>";
}

if ($_GET['act'] == "addlinks2") {
$query = mysql_query("INSERT INTO ".$pre."links VALUES ('null', '".addslashes($_POST["name"])."', '".addslashes($_POST["url2"])."', '".addslashes($_POST["alt"])."', '".time()."')");

if ($query == TRUE) {
echo re_direct("1500", $_POST['url']);
echo "Link '<b>".$_POST["name"]."</b>' has been posted. <a href='".$_POST['url']."'>Return</a>";
} else {
echo "Link could not be added. Please report this issue through the <a href='admin.php?act=support'><b>support area</b></a>.";
}
}

if ($_GET['act'] == "editlinks") {
echo "<br><br>";

$data = mysql_fetch_row(mysql_query("SELECT name,url,alt FROM ".$pre."links WHERE id = '".$_GET['id']."'"));
echo "<form action='admin.php?act=editlinks2&id=".$_GET['id']."&name=".$data[0]."' method='post' onsubmit='return submitForm();'><table cellpadding='5' cellspacing='0' border='0' width='100%'><tr><td><b>Name:</b></td><td><input type='text' name='name' value='".$data[0]."'></td></tr><tr><td><b>URL:</b></td><td><input type='text' name='url2' value='".$data[1]."'></td></tr><tr><td><b>Alt Text:</b></td><td><input type='text' name='alt' value='".$data[2]."'></td></tr><tr><td><input type='submit' value='Update Link'><input type='hidden' name='url' value='".$_SERVER['HTTP_REFERER']."'></td></tr></table></form>";
}

if ($_GET['act'] == "editlinks2") {
$query = mysql_query("UPDATE ".$pre."links SET name = '".addslashes($_POST["name"])."', url = '".addslashes($_POST["url2"])."', alt = '".addslashes($_POST["alt"])."' WHERE id = '".$_GET['id']."'");

if ($query == TRUE) {
echo re_direct("1500", $_POST['url']);
echo "Link '<b>".$_POST["name"]."</b>' has been updated. <a href='".$_POST['url']."'>Return</a>";
} else {
echo "Link could not be updated. Please report this issue through the <a href='admin.php?act=support'><b>support area</b></a>.";
}
}

if ($_GET['act'] == "dellinks") {
$query = mysql_query("DELETE FROM ".$pre."links WHERE id = '".$_GET['id']."'") or die(mysql_error());

if ($query == TRUE) {
echo re_direct("1500", $_SERVER['HTTP_REFERER']);
echo "Link has been deleted. <a href='".$_SERVER['HTTP_REFERER']."'>Return</a>";
} else {
echo "Link could not be deleted. Please report this issue through the <a href='admin.php?act=support'><b>support area</b></a>.";
}
}
?>

<?php
if ($_GET['act'] == "files") {
echo "<table cellpadding='5' cellspacing='0' border='0' width='100%'><tr><td><b>#</b></td><td><b>File</b></td><td><b>Author</b></td><td><b>Date</b></td><td><b>Actions</b></td></tr>";
$sql = mysql_query("SELECT * FROM ".$pre."files ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
echo "<tr><td>".$r[id]."</td><td><a href='".$upload.stripslashes($r[file])."'>".stripslashes($r[file])."</a></td><td><a href='user-".$r[author].".html'>".$r[author]."</a></td><td>".date("F d, Y", $r[date])."</td><td><a href='admin.php?act=delfiles&id=".$r[id]."'>Delete</a></td></tr>";
}
echo "</table><br><br><br><br>";

echo "<br><br>";
echo "<form action='admin.php?act=addfiles2' method='post' onsubmit='return submitForm();' enctype='multipart/form-data'><table cellpadding='5' cellspacing='0' border='0' width='100%'><tr><td><b>File:</b></td><td><input type='file' name='file'></td></tr><tr><td><input type='submit' value='Upload File'><input type='hidden' name='url' value='".$fpage."'></td></tr></table></form>";
}

if ($_GET['act'] == "addfiles2") {
$copy = copy ($_FILES["file"]["tmp_name"], $upload.$_FILES["file"]["name"]);
$type = explode("/", $_FILES["file"]["type"]);
if ($copy == TRUE) {
$query = mysql_query("INSERT INTO ".$pre."files VALUES ('null', '".$_FILES["file"]["name"]."', '".$type[1]."', '".$_COOKIE[blogphp_username]."', '".time()."')");

if ($query == TRUE) {
echo re_direct("1500", $_POST['url']);
echo "Files '<b>".$_FILES["file"]["name"]."</b>' has been uploaded. <a href='".$_POST['url']."'>Return</a>";
} else {
echo "Files could not be added. Please report this issue through the <a href='admin.php?act=support'><b>support area</b></a>.";
}
} else {
echo "<font color='red'>Sorry but file could not be uploaded. Exiting script. (please check path settings)</font>";
die;
}
}

if ($_GET['act'] == "delfiles") {
$val = mysql_fetch_row(mysql_query("SELECT file FROM ".$pre."files WHERE id = '".$_GET['id']."'"));
if (file_exists($upload.$val[0])) {
unlink($upload.$val[0]);
}

$query = mysql_query("DELETE FROM ".$pre."files WHERE id = '".$_GET['id']."'") or die(mysql_error());

if ($query == TRUE) {
echo re_direct("1500", $_SERVER['HTTP_REFERER']);
echo "File has been deleted. <a href='".$_SERVER['HTTP_REFERER']."'>Return</a>";
} else {
echo "File could not be deleted. Please report this issue through the <a href='admin.php?act=support'><b>support area</b></a>.";
}
}
?>

<?php
if ($_GET['act'] == "templates") {
if ($level == "Admin") {
echo "<table cellpadding='5' cellspacing='0' border='0' width='100%'><tr><td><b>#</b></td><td><b>Name</b></td><td><b>Actions</b></td></tr>";
$sql = mysql_query("SELECT * FROM ".$pre."templates ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
echo "<tr><td>".$r[id]."</td><td>".$r[name]."</td><td><a href='admin.php?act=edittemplates&id=".$r[id]."'>Edit</a></td></tr>";
}
echo "</table>";
} else {
echo "Sorry but you do not have access, this is an admin-only area";
}
}

if ($_GET['act'] == "edittemplates") {
if ($level == "Admin") {
echo "<br><br>";

$data = mysql_fetch_row(mysql_query("SELECT name,template FROM ".$pre."templates WHERE id = '".$_GET['id']."'"));
echo "<form action='admin.php?act=edittemplates2&id=".$_GET['id']."' method='post' onsubmit='return submitForm();'><table cellpadding='3' cellspacing='0'><tr><td><b>Name:</b></td><td><input type='hidden' name='name' value='".$data[0]."'>".$data[0]."</td></tr><tr><td><b>Template:</b></td><td><textarea name='template' cols='45' rows='12'>".stripslashes($data[1])."</textarea></td></tr><tr><td><input type='submit' value='Update Template'><input type='hidden' name='url' value='".$_SERVER['HTTP_REFERER']."'></td></tr></table></form>";
} else {
echo "Sorry but you do not have access, this is an admin-only area";
}
}

if ($_GET['act'] == "edittemplates2") {
if ($level == "Admin") {
$autobr = addslashes($_POST['template']);

$query = mysql_query("UPDATE ".$pre."templates SET template = '".$autobr."' WHERE id = '".$_GET['id']."'");

if ($query == TRUE) {
echo re_direct("1500", $_POST['url']);
echo "Template '<b>".addslashes($_POST["name"])."</b>' has been updated. <a href='".$_POST['url']."'>Return</a>";
} else {
echo "Template could not be updated. Please report this issue through the <a href='admin.php?act=support'><b>support area</b></a>.";
}
} else {
echo "Sorry but you do not have access, this is an admin-only area";
}
}
?>

<?php
echo footer();
?>