<?php 
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_COMPILE_ERROR  | E_CORE_ERROR );
include ('../connector.inc.php');
if (!file_exists('../config.inc.php')) die ('Configuration file cannot be found. Please <a href="../install/">re-install</a> or configure config.inc.php.');

include_general('config.inc.php');
include_inc('definitions.inc.php');
require ('languages/'.ADMIN_LANGUAGE.'.php');

session_name(GEKKO_ADMIN_SESSION_NAME);
session_start();

include_inc('html.inc.php');
include_inc('app_interface.php');
include_inc('app_basic.class.php');

require ('includes/admin_basic.class.php');

require ("../includes/db.inc.php");

require ("../includes/dbconfig.inc.php");
require ("../includes/util.inc.php");
require ("includes/ui.inc.php");

require ("../includes/init.inc.php");

$user_class_name = DEFAULT_USER_CLASS;

include_app_class($user_class_name);

global $Administration, $gekko_current_admin_user;

$gekko_current_admin_user =  new $user_class_name(true);
 //_________________________________________________________________________//   
function Initialize ()
{
	global $Administration, $html_output;		
	
	$errormsg = '';
	ob_end_clean();
	ob_start();
	if (empty($_GET['app'])) $_GET['app'] = 'main'; // default application
	 // prevent XSS
	$app = $_GET['app'] = cleanInput($_GET['app']);
	$appmodule = $_GET['appmodule'] = cleanInput ($_GET['appmodule']);
	
	if (empty($appmodule))
	{
		$appmodule = $app;
		$classname=$app.'Admin';			
	} else
	{
		$classname=$app.$appmodule.'Admin';	
	}
	$include_filename = "apps/{$app}/{$appmodule}.admin.class.php";
	if (!file_exists(SITE_PATH.'/admin/'.$include_filename))
	{
		$errormsg = 'Administration class file cannot be found - '.SITE_PATH.'/admin/'.$include_filename;
		$include_filename = "apps/main/main.admin.class.php";
		$classname = 'mainAdmin';		
	}
	include_once ($include_filename);
	$Administration = new $classname($errormsg);
	if ($Administration->CheckIfCurrentUserAllowedAccess()) 
		$Administration->Run();
	else
		$Administration->displayError(ACCESS_NOT_ALLOWED);
	$html_output = ob_get_contents();
	ob_end_clean();
}
 //_________________________________________________________________________//   

function checkIfInstallationDirectoryStillExists()
{
	if (is_dir(SITE_PATH.'/install')) echo DIV_start('','clearboth').DIV_end().h3(TXT_WARNING_INSTALLDIR_EXISTS,'','general-error');
}
 //_________________________________________________________________________//   
function get3rdPartyApplicationList()
{
	include_admin_class('applications');
	$app_manager = new applications;
	//$app_manager->checkForUnregisteredItems();
	return $app_manager->getAllItems();
}
//_________________________________________________________________________//   
function displayAppModuleToolbar()
{
	global $Administration;
	if (method_exists($Administration,'displayAppModuleToolbar')) $Administration->displayAppModuleToolbar();
}

 //_________________________________________________________________________//   
function displayPage()
{
 	global $html_output;
	
	echo $html_output;
}

//_________________________________________________________________________//   
function displayHeader()
{
	global $Administration;
	
	include_inc('templates.inc.php');
	$SiteTemplate = new templates();
	$current_template = $SiteTemplate->getCurrentTemplate();
	$default_js = getJavascriptFormSecretTokenHiddenField('_csrftoken',false)."\n";
	
	$default_js.= 'var site_httpbase = "'.SITE_HTTPBASE.'";'."\n";
	$default_js.= 'var site_template = "'.SITE_HTTPBASE.'/templates/'.$current_template.'";'."\n";		
	$default_js.= 'var datatable_max_row_perpage = "'.DATATABLE_MAX_ROW_PERPAGE.'";';
	echo JAVASCRIPT_TEXT($default_js);
	$Administration->displayPageHeader();
	
//	$default_js.= 'var _gekko_internal_message = "Test";';
//	echo JAVASCRIPT_TEXT('YAHOO.util.Event.addListener(window,\'load\',displayGekkoInternalMessage);');	
}
//_________________________________________________________________________//   
function checkInvalidCSRFAndHaltOnError()
{
	if (!validCSRFVerification()) die('Invalid CSRF Verification Token');	
}
//_________________________________________________________________________//   
function getIntendedAdminApplication()
{
	/* This is just for the convenience if admin session times out, then it will filter the request and forward it to the new location */
	/* Filtered against XSS attack */
	$allowable_actions = array('edititem','editcategory','newitem','newcategory');
	$the_url = (defined('SITE_HTTP_URL')) ? SITE_HTTP_URL : SITE_URL; // for backward compatibility
	//if (isset($_SESSION['admin_intention'])	)
	if (isset($_SESSION['admin_intention']) && $_SESSION['admin_intention'] != SITE_HTTPBASE.'/admin/index.php')	
	{
		$intended_url = $_SESSION['admin_intention'];
		$url_array = parse_url($intended_url);
		parse_str($url_array['query'], $command_array);
		$filtered_command_array['app'] = cleanInput($command_array['app']);
		if (!in_array($command_array['action'],$allowable_actions)) unset($command_array['action']); else $filtered_command_array['action'] = cleanInput($command_array['action']);
		if (array_key_exists('id',$command_array)) $filtered_command_array['id'] = $command_array['id'];
		if (array_key_exists('cid',$command_array)) $filtered_command_array['cid'] = $command_array['cid'];		
		$filtered_intended_url = http_build_query($filtered_command_array);
		$final_location = $the_url.SITE_HTTPBASE.'/admin/index.php?'.$filtered_intended_url;
		unset($_SESSION['admin_intention']);		
		header('Location: '.$final_location);
	}  else if(isset($_SESSION['admin_intention']) && $_SESSION['admin_intention'] == SITE_HTTPBASE.'/admin/index.php')
	{
		unset($_SESSION['admin_intention']);		
		header('Location: '.$the_url.SITE_HTTPBASE.'/admin/index.php');
	}
}
 //_________________________________________________________________________//   
setFormSecretToken(); // CSRF prevention (Beta)
if (!empty($_GET['logout']))
{
	$gekko_current_admin_user->logout();
	header("Location: ".SITE_HTTPBASE."/admin/index.php");
} 

else
{
	if (empty($_GET['app']) && $_POST['admin_login_form'])
	{
		if (!validCSRFVerification()) 
			$_SESSION['login_error'] = 'Invalid CSRF Verification Token';
		else
			$gekko_current_admin_user->performAuthentication($_POST['username'],$_POST['password'], $_POST['remember']);
	}
	if ($gekko_current_admin_user->authenticated() === true && $gekko_current_admin_user->getCurrentUserID() > 0)
	{
		getIntendedAdminApplication();
		header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
		header("Expires: Sat, 26 Jul 2000 05:00:00 GMT"); // Date in the past		
		
		Initialize();
		if (!empty($_GET['ajax'])) 
		{
		   displayPage(); 
		} else 
		{
			//
			include ('templates/'.ADMIN_TEMPLATE.'/index.php');
		}
	}
	else
	{
		if ($_SESSION['login_error']) sleep(1); // sleep, delay 3 seconds
		if (!empty($_GET['ajax'])) 
		{
		   ajaxReply('401','Logged Out');
		} else 
		{
			include ('login.php');
		}
	}
}

?>

