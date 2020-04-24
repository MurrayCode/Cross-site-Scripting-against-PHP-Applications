<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

error_reporting(E_ERROR);
if (!file_exists('config.inc.php')) die ('Configuration file cannot be found. Please <a href="install/">re-install</a> or configure config.inc.php.');
require ("config.inc.php");
require ("includes/definitions.inc.php");
require ("includes/html.inc.php");
require ("connector.inc.php");
require ("includes/app_interface.php");
require_once("includes/app_basic.class.php");

//
define ('GEKKO_SESSION_NAME','gusr'.GEKKO_DEFAULT_SESSION_NAME);
session_name(GEKKO_SESSION_NAME);
session_start();
//

require ("includes/db.inc.php");
require ("includes/dbconfig.inc.php");

require ("includes/init.inc.php");
require ("includes/util.inc.php");
require ("includes/templates.inc.php");  
require ("includes/blocks.inc.php");  
require ("includes/filters.inc.php");  
  
global $Application, $Blocks, $Filters, $SiteTemplate, $HTMLHeader;
global $html_ouptut;

function getApplicationToLoad()
{
//	$gekko_request_url = $_SERVER['REQUEST_URI'];
	$app_to_load = 'html'; // set to html by default
	if (SEF_ENABLED && empty($_GET['app']))
	{

		//$s = str_replace(SITE_HTTPBASE,'',$_SERVER['REQUEST_URI']); bug found Nv 16, 2011
 		if (SITE_HTTPBASE)
		{
			$x = strpos($_SERVER['REQUEST_URI'],SITE_HTTPBASE);			
			if($x !== false && $x == 0)
			{
				$z = strlen(SITE_HTTPBASE);
				$s = substr($_SERVER['REQUEST_URI'],$z,strlen($_SERVER['REQUEST_URI'])-$z);				
			}
		}else $s = $_SERVER['REQUEST_URI'];
		$url_array = explode('/',$s);
		$appname = $url_array[1];
		$the_real_app_name = getApplicationRealNameByAlias($appname);
		if ($the_real_app_name) $appname = $the_real_app_name;
		$include_filename = "/apps/{$appname}/{$appname}.class.php";
 		if (file_exists(SITE_PATH.$include_filename)) $app_to_load = $appname;

	} else
	{
		if (!empty($_GET['app'])) $app_to_load = $_GET['app'];
	}
	return $app_to_load;
}
 //_________________________________________________________________________//   

function Initialize ()
{
	global $Application, $Blocks, $Filters, $SiteTemplate, $HTMLHeader, $gekko_html_output;
	ob_end_clean();	
	ob_start();
	$HTMLHeader =  new HTMLPageHeader();
	$Blocks = new blocks();
	$Filters = new filters();
	$SiteTemplate = new templates();
 	$classname=getApplicationToLoad();	
 	$filename = "apps/{$classname}/{$classname}.class.php";
 	include_once($filename);
	$Application = new $classname();
	$command = $Application->interpretFriendlyURL($_SERVER['REQUEST_URI']);
 	$display_partial_output = $Application->Run($command);
 	$gekko_html_output = ob_get_contents();
	ob_end_clean();
	$Blocks->Run();
	return $display_partial_output;
}
 //_________________________________________________________________________//   
function displayPage()
{
	global $gekko_html_output;
	echo $gekko_html_output;
}
//_________________________________________________________________________//   
function displayHeader()
{
	global $HTMLHeader;
	
	$basehref = SITE_URL.removeMultipleSlashes(SITE_HTTPBASE.'/');
	echo '<meta name="generator" content="Gekko Web Builder / Baby Gekko CMS / gekkocms" />'."\n";
	echo '<base href="'.$basehref.'" />'."\n";	
	echo $HTMLHeader->getAll();
}

//_________________________________________________________________________//   
function displayPageTitle()
{
	global $Application;
	$Application->displayPageTitle();
}
//_________________________________________________________________________//   
function displayPageMetaDescription()
{
	global $Application;
	$Application->displayPageMetaDescription();
}
//_________________________________________________________________________//   
function displayPageMetaKeywords()
{
	global $Application;
	$Application->displayPageMetaKeywords();
}
//_________________________________________________________________________//   
function displayBreadCrumbs()
{
	global $Application;
	
	$Application->displayBreadCrumbs();
}
//_________________________________________________________________________//   
function displayBlockByPosition($position)
{
	global $Blocks;
	
	$Blocks->displayBlockByPosition($position);
}
//_________________________________________________________________________//   
function getBlockCountByPosition($position)
{
	global $Blocks;
	
	$Blocks->getBlockCountByPosition($position);
}

//_________________________________________________________________________//   
function displayBlock($block_name)
{
	global $Blocks;

	$Blocks->displaySingleBlock($block_name);
}

//_________________________________________________________________________//   

	// This is so you can change the user class for integration with different types of authentication provider - e.g: LDAP, Active Directory, Facebook, OpenID, etc
	setFormSecretToken(); // CSRF prevention (Beta)
 	include_app_class(DEFAULT_USER_CLASS);
	$default_user_class = DEFAULT_USER_CLASS;
	$gekko_current_user =  new $default_user_class(false); // not admin mode
	
	$outputChoice = Initialize();
	if ($outputChoice==false) 
		displayPage(); 
	else 
		$SiteTemplate->Run(false);
?>