<?php
 session_start();
 session_unset();
 session_destroy();
 include("config.php");
 echo "<html><head><script>";
 echo "function fx() { window.parent.location='".$baseurl."login.php'; } ";
 echo "</script></head>";
 echo "<body onload='fx()'>"; 
 echo "See you soon ! <br>";
 echo "Redirecting ...";
 echo "</body></html>";
?>