<?php

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 * @subpackage configuration
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
include_once ('framework/model/PreferenceServices.php');
include 'framework/configuration/languages.php';

if (!isset ($_SESSION ['brimTemplate']))
{
	$services = new PreferenceServices ();
	$preferences=$services->getPreferencesAsArray ($_SESSION ['brimUsername']);

	foreach ($preferences as $key=>$value)
	{
		$_SESSION[$key]=$value;
	}
	if (isset ($preferences['brimTemplate']) &&
		file_exists ('templates/'.$preferences ['brimTemplate']))
	{
		// check if the stored template actually exists
		$_SESSION ['brimTemplate'] = $preferences ['brimTemplate'];
	}
	else
	{
		// else default to the first template found
		include 'framework/configuration/templates.php';
		$templates = getTemplates ();
		$_SESSION ['brimTemplate'] = 'barrel';
	}
	$lang = array();
	foreach ($languages as $language)
	{
		array_push ($lang, $language[0]);
	}
	if (in_array ($preferences ['brimLanguage'], $lang))
	{
		//
		// We have the new (ISO) language code in the DB
		//
		$_SESSION ['brimLanguage'] = $preferences ['brimLanguage'];
	}
	else
	{
		//
		// Unknown language code
		// This is not supposed to happen, default to english
		//
		$_SESSION ['brimLanguage'] = 'en';
	}
	if (isset ($preferences ['brimPreferedIconSize']))
	{
		$_SESSION ['brimPreferedIconSize'] = $preferences ['brimPreferedIconSize'];
	}
	else
	{
		$_SESSION ['brimPreferedIconSize'] = '';
	}
	if (isset ($preferences ['brimDateFormat']))
	{
		$_SESSION ['brimDateFormat'] = $preferences ['brimDateFormat'];
	}
	else
	{
		$_SESSION ['brimDateFormat'] = 'dd/mm/yyyy';
	}
	//
	// Test user defaults to English and 'barrel' theme.
	// This shouldn't be here, but then again....
	//
	if ($_SESSION ['brimUsername'] == 'test')
	{
		$_SESSION ['brimLanguage'] = 'en';
		$_SESSION ['brimTemplate'] = 'barrel';
	}
	if (isset ($preferences['brimDefaultShowShared']) &&
		$preferences['brimDefaultShowShared'] == 1)
	{
		$_SESSION['navigationMode']='public';
	}
	else
	{
		$_SESSION['navigationMode']='private';
	}
	session_register ();
}

//
// Debugging...
//
$debug=false;
if ($debug)
{
	$_SESSION ['debug'] = 'true';
}
else
{
	$_SESSION ['debug'] = 'false';
	unset ($_SESSION ['debug']);
}
?>
