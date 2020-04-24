<?php
$db="ULforum";
$server="localhost";
$user="root";
$pass="hacklab2019";

$conn=mysql_connect($server,$user,$pass) or die("Connection Error [Connection Refused] !");
$mydb=mysql_select_db($db,$conn) or die("Database Error [Database not found] !");   
?>