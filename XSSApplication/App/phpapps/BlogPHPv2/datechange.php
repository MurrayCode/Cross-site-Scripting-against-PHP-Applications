<?php
$protect = "yes";
include ("config.php");
echo headera();

if ($_GET['update'] == "") {
echo "<form action='datechange.php?update=yes' method='post'><b>Date:</b> <select name='day'>";
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
echo "</select><br><b>Blog:</b> <select name='id'>";

$search = mysql_query("SELECT * FROM ".$pre."blogs ORDER BY `id` DESC");
for($i = 1; $r = mysql_fetch_assoc($search); $i++) {
echo "<option value='".$r[id]."'>".stripslashes($r[subject])."</option>";
}

echo "</select>&nbsp;&nbsp;<input type='submit' value='Submit'></form>";
}

if ($_GET['update']) {

$mktime = date("U", mktime(0, 0, 0, $_POST['month'], $_POST['day'], $_POST['year']));
$q = mysql_query("UPDATE ".$pre."blogs SET date = '".$mktime."', mdate = '".$_POST['month']."', ydate = '".$_POST['year']."' WHERE id = '".$_POST['id']."'");

if ($q == TRUE) {
echo "Done done and DONE";
}
}
?>