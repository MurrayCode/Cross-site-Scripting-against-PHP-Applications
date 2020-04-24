<?php
$install = "yes";
include ("config.php");

if ($_GET['step'] == "") {
echo install_header();
$var = "";
$var2 = "0";
echo "<div align='left'>";

echo "MySQL Connection Status successfull?: ";
if (@mysql_select_db($dbname, mysql_connect($dbhost, $dbuser, $dbpass)) == TRUE) {
echo "<font color='blue'>YES</font>";
$var .= "Step1=true,";
$var2 = $var2 + 1;
} else {
echo "<font color='red'>NO</font>";
$var .= "Step1=false,";
}

echo "<br>PHP >= 4.0: ";
if (@phpversion() >= "4.0") {
echo "<font color='blue'>YES</font>";
$var .= "Step2=true,";
$var2 = $var2 + 1;
} else {
echo "<font color='red'>NO</font>";
$var .= "Step2=false,";
}

echo "<br>MySQL Enabled: ";
if (@function_exists("mysql_connect")) {
echo "<font color='blue'>YES</font>";
$var .= "Step3=true,";
$var2 = $var2 + 1;
} else {
echo "<font color='red'>NO</font>";
$var .= "Step3=false,";
}

$ex = @explode(",", $var);

echo "</div><br>";
if ((($var2 >= "2") && ($ex[0] == "Step1=true") && ($ex[2] == "Step3=true"))) {
echo "&nbsp;&nbsp;&nbsp;&nbsp;Proceed to the next step - <a href='install.php?step=1'><b>Proceed to - SQL Installation</b></a> (please set the table prefix, if any, in config.php before continuing)<br><br>";
}

if ($ex[0] == "Step1=false") {
echo "Please check the database information in config.php<br><br>";
}

if ($ex[1] == "Step2=false") {
echo "Your PHP is only version <b>".@phpversion()."</b> which is out of date. If you are running anything below 3.7.0 you may have many problems running BlogPHP<br><br>";
}

if ($ex[2] == "Step3=false") {
echo "Sorry but MySQL appears to not be enabled on your server, you cannot use BlogPHP without MySQL. Please ask your host to add MySQL<br><br>";
}
}

if ($_GET['step'] == "1") {
@mysql_select_db($dbname, @mysql_connect($dbhost, $dbuser, $dbpass));

if (table($pre."blogs") == TRUE) {
echo install_header();
echo "Looks like you have BlogPHP installed on this database, you can upgrade or install fresh (pick a different table prefix!):<br><br><center><a href='install.php?step=upgrade'>Upgrade</a> :: <a href='install.php?step=2'>Fresh Install</a></b></center>";
} else {
echo install_header();
echo "Looks like you don't have BlogPHP installed on this database, you only have the option to install:<br><br><center><b><a href='install.php?step=2'>Install</a></b></center>";
}
}

if ($_GET['step'] == "upgrade") {
@mysql_select_db($dbname, @mysql_connect($dbhost, $dbuser, $dbpass));

echo upgrade();

$query = @mysql_num_rows(mysql_query("SELECT * FROM ".$pre."subscriptions"));

if ($query > "0") {
echo install_header();
echo "SQL Uploaded to Database?: ";
echo "<font color='blue'>YES</font>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;The upgrade has gone through successfully! You can now go to your site and check out the new features.";
} else {
echo install_header();
echo "SQL Uploaded to Database?: ";
echo "<font color='red'>NO</font>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;Please make sure the database information is correct and then try again";
}
}

if ($_GET['step'] == "2") {
@mysql_select_db($dbname, @mysql_connect($dbhost, $dbuser, $dbpass));

echo install();

$query = @mysql_num_rows(mysql_query("SELECT * FROM ".$pre."pages"));

if ($query > "0") {
echo install_header();
echo "SQL Uploaded to Database?: ";
echo "<font color='blue'>YES</font>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;Proceed to the next step - <a href='install.php?step=3'><b>Proceed to - Admin Info</b></a>";
} else {
echo install_header();
echo "SQL Uploaded to Database?: ";
echo "<font color='red'>NO</font>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;Please make sure the database information is correct and then try again";
}
}

if ($_GET['step'] == "3") {
@mysql_select_db($dbname, @mysql_connect($dbhost, $dbuser, $dbpass));
echo install_header();

echo "<form action='install.php?step=4' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Username</td><td><input type=\"text\" name='username'></td></tr><tr><td>Password</td><td><input type=\"text\" name='password'></td></tr><tr><td>E-Mail</td><td><input type=\"text\" name='email'></td></tr><tr><td><input type=\"submit\" value=\"Proceed to - User Creation\"></td></tr></form></table>";
}

if ($_GET['step'] == "4") {
@mysql_select_db($dbname, @mysql_connect($dbhost, $dbuser, $dbpass));
echo install_header();

$sql = mysql_query("INSERT INTO ".$pre."users VALUES ('null', '".$_POST['username']."', '".md5($_POST['password'])."', '".$_POST['email']."', 'Admin', '', '', '', '', '', '', '', '', '', '0', '".time()."', '')");
mysql_query("UPDATE ".$pre."blogs SET author = '".$_POST['username']."' WHERE id = '1'");
mysql_query("UPDATE ".$pre."links SET author = '".$_POST['username']."' WHERE id = '1'");
mysql_query("UPDATE ".$pre."pages SET author = '".$_POST['username']."' WHERE id = '1'");

if ($sql == TRUE) {
echo "User account added successfully?: ";
echo "<font color='blue'>YES</font>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;You are now finished! You can now login - <a href='login.html'><b>Login here</b></a><br><br>Thank you and enjoy using BlogPHP ".$version."! (<b>don't forget to delete install.php!</b>)";
} else {
echo "User account added successfully?: ";
echo "<font color='red'>NO</font>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;Please make sure the SQL is uploaded, database information is correct and then try again";
}
}
?>