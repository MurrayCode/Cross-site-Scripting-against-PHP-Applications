<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.messages
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
	$dictionary = array ();
}
$dictionary['msg_cookieValidationFailed']='Cookie&#252;berpr&#252;fung fehlgeschlagen';
$dictionary['msg_copyright']='Die Seite des '.$dictionary['programname'].' Projekts kann unter folgenden Adressen gefunden werden: <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a>.<br />'.$dictionary['copyright'].' by '.$dictionary['authorname'].' (<a href="'.$dictionary['authorurl'].'">'.$dictionary['authorurl'].'</a>) Licensed under the GPL.<br/> A copy of the license is included <a href="documentation/gpl.html">here</a>

  	';
$dictionary['msg_emailAddress']='Emailadresse';
$dictionary['msg_illegalAccess']='Illegaler Zugriff';
$dictionary['msg_illegalLoginName']='Fehlerhafter Loginname, der Loginname darf folgende Zeichen nicht beinhalten \', #, $, %, &';
$dictionary['msg_incorrectPassword']='Falsches Passwort';
$dictionary['msg_loginName']='Benutzername';
$dictionary['msg_lostPassword']='Passwort vergessen?';
$dictionary['msg_name']='Name';
$dictionary['msg_notAllowedForTestUser']='Diese Einstellung ist f&#252;r Testbenutzer nicht erlaubt';
$dictionary['msg_password']='Passwort';
$dictionary['msg_passwordMismatch']='Passwort ist falsch';
$dictionary['msg_provideEmailAndPassword']='Bitte tragen Sie Email und Passwort ein';
$dictionary['msg_provideUsernameAndPassword']='Bitte tragen Sie Benutzername und Passwort ein';
$dictionary['msg_rememberMe']='Erinnere mich an Dich';
$dictionary['msg_signUp']='Anmelden';
$dictionary['msg_submit']='Abschicken';
$dictionary['msg_tempPasswordSent']='Ihr vor&#252;bergehendes Passwort wurde Ihnen soeben per Mail zugesendet. 
Bitte benutzen Sie dieses Passwort zum einloggen. ';
$dictionary['msg_unknownError']='Unbekannter Fehler';
$dictionary['msg_unknownUser']='Unbekannter Benutzer';
$dictionary['msg_userAlreadyExists']='Benutzer existiert bereits';
$dictionary['msg_usernamePasswordMismatch']='Benutzername/Email Kombination passt nicht';

?>