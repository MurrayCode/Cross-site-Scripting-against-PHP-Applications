<?php

require_once ('framework/realm/Realm.php');
require_once "framework/util/databaseConnection.php";
include ('framework/sql/authQueries.php');

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.plugins.framework
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

if (isset($_POST['submit']))
{
	//
	// Check whether we would like to signup
	//
	if (isset ($_POST['signUp']))
	{
		global $db, $queries;
		session_start ();
		$_SESSION['signupUsername'] = $_POST['username'];
		$_SESSION['signupPassword'] = $_POST['password'];
		header ("Location: signup.php");
		exit;
	}
	//
	// Check for username and password on POST
	//
	if(!$_POST['username'] | !$_POST['password'])
	{
		$message='msg_provideUsernameAndPassword';
		Header ("Location: login.php?message=".$message);
		exit;
	}
	//
	// Query the database
	//
	$query = sprintf ($queries ['getUsernamePassword'], $_POST['username']);
	$result = $db->Execute($query);
	$dbUsername = trim ($result->fields[0]);
	//
	// Check username
	//
	if ($result == null || $dbUsername == null || $dbUsername == ""
		|| $dbUsername != $_POST['username'])
	{
		$message='msg_unknownUser';
		Header ("Location: login.php?message=".$message);
		exit;
	}
        //
        // Check password
        //
	$theRealm = new Realm ();
	$realm = $theRealm->getInstance();
	$authenticated = $realm->authenticate($_POST['username'],$_POST['password']);
        if (!$authenticated)
        {
		$message='msg_incorrectPassword';
		Header ("Location: login.php?message=".$message);
		exit;
	}
	//
	// Update last login
	///
	$date = date('Y-m-d H:i:s');
	$query = sprintf ($queries['updateLogin'], $date, $_POST['username']);
	$result = $db->Execute($query)
		or die ("Oops, could not update last login information");
	$db->Close ();
	//
	// Start the session
	///
session_start ();
	$_SESSION['brimUsername'] = $_POST['username'];

        $isAdmin = $realm->isMemberOf($_POST['username'],'admin');
        if ($isAdmin)
        {
                $_SESSION['brimUserIsAdmin'] = 'true';
        }
	else
        {
                $_SESSION['brimUserIsAdmin'] = 'false';
        }

	//
	// Only set a cookie if the user has requested it
	///
	if (isset ($_POST['rememberMe']))
	{
		//
		// Build up cookie information
		///
		$cookieId = 'Brim';
		$cookieValue = "username=".$_SESSION['brimUsername'].
			"&password=".$_POST['password'];
		$cookieExpire = time() + 365*24*3600; //365 days
		$cookiePath = "";
		$cookieDomain = "";
		$cookieSecure = 0;
		//
		// And actually set the cookie
		///
		if (!setCookie ($cookieId, $cookieValue, $cookieExpire,
			$cookiePath, $cookieDomain, $cookieSecure))
		{
			die ("Problem setting cookie");
		}
	}
	//
	// Forward to the index page
	//
	Header ('Location: index.php');
	exit;
}
?>
