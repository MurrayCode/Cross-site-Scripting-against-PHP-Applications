<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
define ('JS_PATH', str_replace('\\','/',dirname(__FILE__)).'/');
error_reporting(0);

$filename = preg_replace("/[^a-z._\d]/i", "", $_GET['js']); // sanitize, prevent path traversal
$etag = sprintf('bbgk%u',crc32($filename));
header("Content-type: text/javascript; charset: UTF-8");	
if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) || isset($_SERVER['HTTP_IF_NONE_MATCH']))
{
	if ($_SERVER['HTTP_IF_MODIFIED_SINCE'] || str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == $etag)
	{
		header('HTTP/1.1 304 Not Modified');
		exit();
	}
} else
if (file_exists (JS_PATH.$filename.'.js.gz'))
{
	header("Vary: Accept-Encoding"); 
	header("Cache-Control: public, max-age=".(144000 * 24));
	header("Pragma: public");
	header("Expires: Tue, 30 Aug 2037 20:00:00 GMT");
	header("Content-Encoding: gzip");	
	header("ETag: \"{$etag}\"");
	readfile(JS_PATH.$filename.'.js.gz');
} else
{
	echo ("alert('{$filename} could not be loaded');");
}
?>