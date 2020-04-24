<?php

require_once ('framework/util/databaseConnection.php');
require_once ('framework/util/StringUtils.php');
include_once ('framework/sql/authQueries.php');
include_once ('framework/sql/userQueries.php');
require_once ('framework/model/AdminServices.php');
require_once ('framework/model/User.php');
require_once ('framework/model/UserFactory.php');
require_once ('framework/model/Preference.php');

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

$stringUtils = new StringUtils ();
$userFactory = new UserFactory ();
if (isset($_POST['submit']))
{
	if ($_POST['user'] == 'test')
	{
		$message='notAllowedForTestUser';
		Header ("Location: lostPassword.php?message=".$message);
		exit;
	}
	//
	// Check for username and email on POST.
	//
	if (!(isset ($_POST['user'])) && !(isset($_POST['password'])))
	{
		$message='provideEmailAndPassword';
		Header ("Location: lostPassword.php?message=".$message);
		exit;
	}
	//
	// Now retrieve the email adres for the user and see
	// whether they match
	//
	$query = sprintf ($queries['getEmail'], $_POST['user']);
	$result = $db->Execute($query) or die ($db->ErrorMsg ());
	$dbEmail = $result->fields[0];
	if ($dbEmail == $_POST['email'])
	{
		$adminServices = new AdminServices ();
		$adminEmail = $adminServices->getAdminConfig ('admin_email');
		//
		// Ok, credentials appear to be correct
		// Generate a (pseudo) random password
		//
		$random =  $stringUtils->randomString (10);
		$randomPassword = MD5($random);
		//
		// Set the users password
		//
		$query = sprintf ($queries ['setPassword'],
			$randomPassword, $_POST['user']);
		$db -> Execute ($query) or die ($db->ErrorMsg ());
		//
		// And send the user a mail
		//
		$message = "Hello ".$_POST['user'].",";
		$message .= "\r\n";
		$message .= "\r\n";
		$message .= "You (or someone pretending to be you) ";
		$message .= "requested a new password. ";
		$message .= "\r\n";
		$message .= "Server name: [".$_SERVER['SERVER_NAME']."] ";
		$message .= "Remote address: [".$_SERVER['REMOTE_ADDR']."] ";
		$message .= "Remote host: [".$_SERVER['REMOTE_HOST']."] ";
		$message .= "\r\n";
		$message .= "Please go to your Brim site and login ";
		$message .= "(username: ".$_POST['user'].") ";
		$message .= "using the following password: ".$random." .";
		$message .= "\r\n";
		$message .= "\r\n";
		$message .= "You can change your temporary password to a simpler one through the ";
		$message .= "Preferences once you log in.";
		$message .= "\r\n";
		$message .= "\r\n";
		$message .= "Sincerely,";
		$message .= "\r\n";
		$message .= "Brim administrator";
		$subject = "Lost Brim password";
		$headers = "MIME-Version: 1.0\r\n";
    		$headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
		$headers .= "From: Brim administrator <".$adminEmail.">\r\n";
		$headers .= "To: Brim user <".$_POST['email'].">\r\n";
		$headers .= "X-Mailer: Brim";
		//
		// Now mail it
		//
		mail($dbEmail, $subject, $message, $headers);
		$message = 'tempPasswordSent';
		Header ("Location: login.php?message=".$message);
		exit;
	}
	else
	{
		$message = 'usernamePasswordMismatch';
		Header ("Location: lostPassword.php?message=".$message);
		exit;
	}
}
?>
