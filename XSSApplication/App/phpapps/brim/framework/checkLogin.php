<?php
/**
 *
 * Checks whether the user is already logged in, forwards to the
 * login page if this is not the case
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
@session_start();
require_once ('framework/realm/Realm.php');

//
// Do not let an illegal user continue. This is a very basic check...
//
if (isset ($_GET['username']) || isset ($_POST['username']))
{
	die ("Illegal access. This seems like a hacking attempt!");
}

//
// Check for username in either session or cookie. Exit if not found
//
if (!isset($_SESSION['brimUsername']) && !isset ($_COOKIE['Brim']))
{
	Header ('Location: logout.php');
	exit;
}

//
// So we have a username in cookie
//
if (!isset ($_SESSION['brimUsername']))
{
	//
	// Information is stored in the following way:
	// username=<username>&password=MD5(<password>)
	//
	$credentials = $_COOKIE['Brim'];
	$temp = explode ('&', $credentials);

	$u = split ('=', $temp[0]);
	$p = split ('=', $temp[1]);
	$username = $u[1];
	$password = $p[1];

	$theRealm = new Realm ();
	$realm = $theRealm->getInstance();
	$authenticated = $realm->authenticate($username,$password);
	
	if (!authenticated)
	{
		$errorMessage = 'cookieValidationFailed';
		Header ('Location: login.php?errorMessage='.$errorMessage);
		exit;
	}

	$_SESSION['brimUsername'] = $username;

	//
	// Find out if I am an admin
	///
	$isAdmin = $realm->isMemberOf($username,'admin');
	if ($isAdmin)
	{
		$_SESSION['brimUserIsAdmin'] = 'true';
	}
	else
	{
		$_SESSION['brimUserIsAdmin'] = 'false';
	}
}
?>
