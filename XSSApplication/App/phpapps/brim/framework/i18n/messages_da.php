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
$dictionary['msg_notAllowedForTestUser']='Denne handling er ikke tilladt for test brugeren';
$dictionary['msg_provideEmailAndPassword']='Angiv venligst b&aring;de e-mail og kodeord';
$dictionary['msg_provideUsernameAndPassword']='Angiv venligst b&aring;de brugernavn og kodeord';
$dictionary['msg_provideUsernameAndEmail']='Angiv venligst b&aring;de brugernavn og E-mail adresse';
$dictionary['msg_tempPasswordSent']='Dit midlertidige kodeord er sendt med e-mail.<br />Benyt venligst dette kodeord ved login';
$dictionary['msg_usernamePasswordMismatch']='Brugernavn/e-mail kombination passer ikke';
$dictionary['msg_illegalAccess']='Ulovlig adgang';
$dictionary['msg_unknownError']='Ukendt fejl';
$dictionary['msg_unknownUser']='Ukendt bruger';
$dictionary['msg_incorrectPassword']='Ukorrekt kodeord';
$dictionary['msg_passwordMismatch']='Kodeord ikke ens';
$dictionary['msg_userAlreadyExists']='Bruger eksisterer allerede';
$dictionary['msg_illegalLoginName']='Illegal loginnavn, navn m&aring; ikke indeholde: \', #, $, %, &';
$dictionary['msg_name']='Brugernavn';
$dictionary['msg_signUp']='Tilmeld';
$dictionary['msg_password']='Kodeord';
$dictionary['msg_rememberMe']='Husk mig';
$dictionary['msg_lostPassword']='Glemt kodeord?';
$dictionary['msg_copyright']=''.$dictionary['programname'].' projektets hjemmeside kan findes p&aring; adressen: <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a>.<br />'.$dictionary['copyright'].' by '.$dictionary['authorname'].' (<a href="'.$dictionary['authorurl'].'">'.$dictionary['authorurl'].'</a>) Licensieret under GPL.<br/> En kopi af licensen er inkluderet <a href="documentation/gpl.html">here</a>';
$dictionary['msg_emailAddress']='Email adresse';
$dictionary['msg_loginName']='Bruger navn';
$dictionary['msg_cookieValidationFailed']='Cookie validering fejlede';
$dictionary['msg_submit']='Send';
?>
