<?php

/**
 * The application entry point. Defaults to dashboard if no plugin is found
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - January 2004
 * @package org.brim-project.framework
 * @subpackage install
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

if (isset ($_REQUEST['ajax']))
{
	//
	// AJAX!
	//
	$plugin = $_REQUEST['plugin'];
	// 
	// Remove all path references to avoid hacking
	//
	$plugin = str_replace("..", "", $plugin);
	$function = $_REQUEST['function'];
	//
	// Register the session id
	//
	if (isset ($_REQUEST['PHPSESSID']))
	{
		session_register ($_REQUEST['PHPSESSID']);
		unset ($_REQUEST['PHPSESSID']);
	}
	unset ($_REQUEST['ajax']);
	unset ($_REQUEST['plugin']);
	unset ($_REQUEST['function']);
	//
	// Ok, we now have a plugin, a function and the session id.
	// Ask for a specific ajax controller (some sort of security,
	// we can only execute the functions in those controllers
	// using an async callback
	//
	if ($plugin != 'framework')
	{
		//
		// Plugin specifc ajaxcontroller, read from the config
		//
		include 'plugins/'.$plugin.'/configuration/hookup.php';
		$controllerClass = $plugins [$plugin]['ajaxController'];
		$controllerName = $plugins [$plugin]['ajaxControllerName'];
		require_once 'plugins/'.$plugin.'/'.$controllerClass;
	}
	else
	{
		//
		// Framework ajaxcontroller
		//
		$controllerClass = 'AjaxController.php';
		$controllerName = 'AjaxController';
		require_once 'framework/AjaxController.php';
	}
	//
	// And execute
	//
	$controller = new $controllerName ();
	if (method_exists ($controller, $function))
	{
		$result = $controller->$function ($_REQUEST);
		echo $result;
	}
	else
	{
		//
		// This should be handled a bit better
		//
		require_once ('ext/JSON.php');
 		$json = new Services_JSON();
		$status ['error'] = 'Invalid access '.$function;
		echo $json->encode ($status); 
	}
	return;
	
}
else if (!isset ($_GET['plugin']) && !isset ($_POST['plugin']))
{
	//
	// No plugin speficified? Default to dashboard
	//
	require_once ('framework/admincontroller.php');
	$controller = new DashboardController ();
	$controller -> activate ();
	$controller -> display ();
}
else
{
	//
	// No ajax
	//
	// Execute the plugin specific controller
	//
	$plugin = isset ($_GET['plugin'])?$_GET['plugin']:$_POST['plugin'];
	// 
	// Remove all path references to avoid hacking
	//
	$plugin = str_replace("..", "", $plugin);
	include 'plugins/'.$plugin.'/configuration/hookup.php';
	$controllerClass = $plugins[$plugin]['controller'];
	$controllerName = $plugins[$plugin]['controllerName'];
	require_once 'plugins/'.$plugin.'/'.$controllerClass;
	//
	// Execute the specific controller
	//
	$controller = new $controllerName();
	$controller -> activate ();
	$controller -> display ();
}
?>
