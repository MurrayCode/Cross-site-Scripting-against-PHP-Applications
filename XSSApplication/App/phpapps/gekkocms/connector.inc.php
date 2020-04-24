<?php

function convert_to_safe_filename($s)
{
	return preg_replace('/[^a-zA-Z0-9-_\.\/]/','', $s);	
}

function include_general($s)
{
	include_once (convert_to_safe_filename($s));
}

function include_block($s)
{
	$s = convert_to_safe_filename($s);
	include_once ("blocks/{$s}");
}

function include_block_class($s)
{ 
	$s = convert_to_safe_filename($s);
	include_once ("blocks/{$s}/{$s}.class.php");	
}

function include_filter_class($s)
{
	$s = convert_to_safe_filename($s);	
	$filter_class_file = "filters/{$s}/{$s}.class.php";
	if (file_exists(SITE_PATH.'/'.$filter_class_file))
		include_once ($filter_class_file);	
}


function include_admin_inc($s)
{
	$s = convert_to_safe_filename($s);	
	include_once ("admin/includes/{$s}");
}

function include_inc($s)
{
	$s = convert_to_safe_filename($s);	
	include_once ("includes/{$s}");
}

function include_admin_class($s)
{
	$s = convert_to_safe_filename($s);	
	include_once ("admin/apps/{$s}/{$s}.admin.class.php");	
}

function include_app_class($s)
{
	$s = convert_to_safe_filename($s);	
	include_once ("apps/{$s}/{$s}.class.php");	
}

function include_app_subclass($s,$sub)
{
	$s = convert_to_safe_filename($s);	
	$sub = convert_to_safe_filename($sub);		
	include_once ("apps/{$s}/{$sub}.class.php");	
}

function include_app_template($s,$file)
{
	$s = convert_to_safe_filename($s);	
	include_once ("apps/{$s}/{$file}.template.php");	
}

function require_general($s)
{
	$s = convert_to_safe_filename($s);	
	require ($s);
}


function require_block($s)
{
	$s = convert_to_safe_filename($s);	
	require_once ("blocks/{$s}");
}

function require_inc($s)
{
	$s = convert_to_safe_filename($s);	
	require_once ("includes/{$s}");
}

function require_app_class($s)
{
	$s = convert_to_safe_filename($s);	
	require_once ("apps/{$s}.class.php");	
}
?>