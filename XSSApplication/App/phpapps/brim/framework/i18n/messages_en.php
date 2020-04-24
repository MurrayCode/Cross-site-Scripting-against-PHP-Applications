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
 * @subpackage i18n
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
include 'framework/i18n/common.php';
if (!isset ($dictionary))
{
	$dictionary=array();
}
$dictionary['msg_notAllowedForTestUser']='This operation is not allowed for the test user';
$dictionary['msg_provideEmailAndPassword']='Please provide email and password';
$dictionary['msg_provideUsernameAndPassword']='Please provide username and password';
$dictionary['msg_tempPasswordSent']='Your temporary password has been
sent by e-mail.<br />Please use this password to login';
$dictionary['msg_usernamePasswordMismatch']='Username/email combination
does not match';
$dictionary['msg_illegalAccess']='Illegal access';
$dictionary['msg_unknownError']='Unknown error';
$dictionary['msg_unknownUser']='Unknown user';
$dictionary['msg_incorrectPassword']='Incorrect password';
$dictionary['msg_passwordMismatch']='Password mismatch';
$dictionary['msg_userAlreadyExists']='User already exists';
$dictionary['msg_illegalLoginName']='Illegal loginname, name may not contain the following characters: \', #, $, %, &';
$dictionary['msg_name']='Name';
$dictionary['msg_signUp']='Sign up';
$dictionary['msg_password']='Password';
$dictionary['msg_rememberMe']='Remember me';
$dictionary['msg_lostPassword']='Lost your password?';
$dictionary['msg_copyright']='The homepage of the '.$dictionary['programname'].' project can be found at the following address: <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a>.<br />'.$dictionary['copyright'].' by '.$dictionary['authorname'].' (<a href="'.$dictionary['authorurl'].'">'.$dictionary['authorurl'].'</a>) Licensed under the GPL.<br/> A copy of the license is included <a href="documentation/gpl.html">here</a>';
$dictionary['msg_emailAddress']='Email address';
$dictionary['msg_loginName']='Login name';
$dictionary['msg_cookieValidationFailed']='Cookie validation failed';
$dictionary['msg_submit']='Submit';
?>
