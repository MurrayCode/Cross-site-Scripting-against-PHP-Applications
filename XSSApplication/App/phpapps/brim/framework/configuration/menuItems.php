<?php

include ('framework/configuration/databaseConfiguration.php');
include ('framework/configuration/preferenceConfiguration.php');
include ('framework/i18n/dictionary_en.php');

/**
 * This file is part of the Brim project. The brim-project is located at the
 * following location: {@link http://www.brim-project.org/ http://www.brim-
 * project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - January 2004
 * @package org.brim-project.framework
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
//
// The application specific breadcrumbs
//
$menu = array();
$menu [] =
	array('href' => 'TranslateController.php',
			'name' => 'translate',
			'icon' => 'translate');
$menu [] =
	array('href' => 'PreferenceController.php',
			'name' => 'preferences',
			'icon' => 'preferences');
$menu [] =
	array('href' => 'PluginController.php',
			'name' => 'plugins',
			'icon' => 'plugins');
$menu [] =
	array('href' => 'AboutController.php',
			'name' => 'about',
			'icon' => 'info');
$menu [] =
	array('href' => 'HelpController.php',
			'name' => 'help',
			'icon' => 'help');
//
// only provide the admin page if we are the admin user
//
if (isset ($_SESSION['brimUserIsAdmin']) && $_SESSION['brimUserIsAdmin'] == 'true')
{
	$menu [] = array('href' => 'AdminController.php',
			'name' => 'admin',
			'icon' => 'Admin');
	$menu [] = array('href' => 'AdminController.php?action=modifyAdminConfigPre',
			'name' => 'adminConfig',
			'icon' => 'AdminConfig');
	$menu [] = array('href' => 'SysinfoController.php',
			'name' => 'sysinfo',
			'icon' => 'SysInfo');
}
//
// Logout is the last menuItem. Always.
//
$menu [] =
	array('href' => 'logout.php',
			'name' => 'logout',
			'icon' => 'logout');


//
// Setup the activated plugins
//
require_once ('framework/model/PluginServices.php');
$services = new PluginServices ();
$plugins=$services->getPlugins ();
$pluginSettings = $services->getPluginSettingsAsArray
	($_SESSION['brimUsername']);
$menuItems = array ();
foreach ($plugins as $plugin)
{
	$pluginName = $plugin['name'];
	if (isset ($pluginSettings[$pluginName])
		&& $pluginSettings[$pluginName] == 'true')
	{
		$menuItems [] =
			array('href' => 'index.php?plugin='.$pluginName,
				'name' =>  $pluginName,
				'icon' => $pluginName);
	}
}
?>
