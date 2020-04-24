<?php

require_once ('framework/util/databaseConnection.php');
include_once ('framework/sql/authQueries.php');
require_once ('framework/model/User.php');
require_once ('framework/model/UserFactory.php');
require_once ('framework/model/Preference.php');
require_once ('framework/model/UserServices.php');
require_once ('framework/model/PreferenceServices.php');
require_once ('framework/model/AdminServices.php');
require_once ('framework/util/StringUtils.php');

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
$userServices = new UserServices ();
if (isset ($_GET['activation']))
{
	if ($userServices->activateAccount ($_GET['activation']))
	{
		echo '<html><head><title>Account activated</title></head>';
		echo '<body>Account activated!<br />Click <a href="login.php">here</a> to continue</body></head>';
	}
	else
	{
		die (print_r ('There was a problem activating your account. Please contact the system administrator'));
	}
	exit;
}
$userFactory = new UserFactory ();
$adminServices = new AdminServices ();
$allowSignon = $adminServices->getAdminConfig ('allow_account_creation');
if (!$allowSignon)
{
	//
	// We can only request this is allowed by admin
	//
	if(!$_POST['loginName'] | !$_POST['password'])
	{
		$message='illegalAccess';
		Header ("Location: login.php?message=".$message);
		exit;
	}
}
if (isset($_POST['submit']))
{
	//
	// Check for username and password on POST. If this is not set,
	// there was an  attempt -> illegal access?
	//
	if(!$_POST['loginName'] | !$_POST['password'])
	{
		$message='illegalAccess';
		Header ("Location: login.php?message=".$message);
		exit;
	}
	//
	// Check for illegal characters (', &, %, $, #) in the loginName
	//
	if (
			strstr ($_POST['loginName'], "'") ||
			strstr ($_POST['loginName'], "&") ||
			strstr ($_POST['loginName'], "%") ||
			strstr ($_POST['loginName'], "$") ||
			strstr ($_POST['loginName'], "#")
		)
	{
		$message='illegalLoginName';
		Header ("Location: login.php?message=".$message);
		exit;
	}
	//
	// Check whether the two provided passwords match
	//
	if ($_POST['password'] != $_POST['password2'])
	{
		$message = 'passwordMismatch';
		Header ("Location: login.php?message=".$message);
		exit;
	}
	//
	// Query the database to see whether the requested id for the user
	// does not already exist in the database
	//
	$query = sprintf
		($queries ['getUsernamePassword'], $_POST['loginName']);
	$result = $db->Execute($query) or die ($db->ErrorMsg ());
	$dbUsername = $result->fields[0];
	//
	// Check whether we have received a username from the database.
	// If this is the case, the username already exists and cannot
	// be added
	//
	if (isset ($dbUsername) || $dbUsername != "")
	{
		$message='userAlreadyExists';
		Header ("Location: login.php?message=".$message);
		exit;
	}

	if (!isset($_SERVER) && isset($HTTP_SERVER_VARS))
       define('_SERVER', 'HTTP_SERVER_VARS');
	//
	// Now physically add the user and its preferences.
	// We can now also savely set the username on the session so the
	// user is directly logged in.
	///
/*
	$preferencesServices = new PreferenceServices ();
	session_start();
	$_SESSION['brimUsername'] = $userSettings->loginName;
	$languagePreference = new Preference
			(null, $_SESSION['brimUsername'], 0, 0,
				'brimLanguage', null, 'private', null, 0, null,
					null, $defaultLanguage);
	$templatePreference = new Preference
			(null, $_SESSION['brimUsername'], 0, 0,
				'brimTemplate', null, 'private', null, 0, null,
					null, $defaultTemplate);
	$preferencesServices->addItem
		($_SESSION['brimUsername'], $languagePreference);
	$preferencesServices->addItem
		($_SESSION['brimUsername'], $templatePreference);
*/
	$stringUtils = new StringUtils ();
	$tempPassword = $stringUtils->randomString (20);
	$checkPasswd = true;
	$userSettings = $userFactory->requestToUser ($checkPasswd);
	$userServices->addTempUser ($_SESSION['brimUsername'], $userSettings, $tempPassword);
	//
	// Ok, username does not exist and credentials (passwords) appear
	// to be valid. Send a mail to the site admin
	//
	$adminEmail = $adminServices->getAdminConfig ('admin_email');
	if (isset ($adminEmail) && ($adminEmail != ''))
	{
		$adminHeaders  = "MIME-Version: 1.0\r\n";
		$adminHeaders .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$adminHeaders .= "From: Brim administrator <".$adminEmail.">\r\n";
		$adminHeaders .= "To: Brim administrator <".$adminEmail.">\r\n";
		$adminHeaders .= "X-Mailer: Brim";
		$adminSubject  = "New user (".$_POST['loginName'].") addition";
		$adminMessage  = "A new user (".$_POST['loginName'].") just signed in";
		$adminMessage .= "
			Address: ".$_SERVER['REMOTE_ADDR'];
		mail($adminEmail, $adminSubject, $adminMessage, $adminHeaders);
	}
	else
	{
		$adminEmail = '';
	}
	$newUserHeaders  = "MIME-Version: 1.0\r\n";
	$newUserHeaders .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$newUserHeaders .= "From: Brim administrator <".$adminEmail.">\r\n";
	if (isset ($userSettings->name))
	{
		$to = $userSettings->name;
	}
	else
	{
		$to = $userSettings->loginName;
	}
	$newUserHeaders .= "To: ".$to." <".$userSettings->email.">\r\n";
	$newUserHeaders .= "X-Mailer: Brim";
	$newUserSubject  = "Activate your Brim account";
	$url = 'http://'.$_SERVER['SERVER_NAME'];
	if (isset ($_SERVER['PORT']) && ($_SERVER['PORT'] != '80'))
	{
		//
		// Remove trailing slash
		//
		$url = substr ($url, 0, strlen ($url) -1);
		$url .= ':'.$_SERVER['PORT'].'/';
	}
	$url .= $_SERVER['PHP_SELF'];
	$url .= '?activation='.$tempPassword;
	$newUserMessage  = 'Please activate your brim account by going to the following url:<br />
		<a href="'.$url.'">'.$url.'</a><br /><br />Enjoy ;-)<br />The Brim administrator';
	if (mail ($userSettings->email, $newUserSubject, $newUserMessage, $newUserHeaders))
	{
		echo ('A mail has been sent. Please check your mailbox for activation instructions');
		exit;
	}
	else
	{
		die (print_r ('Could not send mail, please contact your system administrator'));
	}
}
?>
