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
$dictionary['msg_notAllowedForTestUser']='De test gebruiker mag deze operatie niet uitvoeren';
$dictionary['msg_provideEmailAndPassword']='Geef zowel email als paswoord op';
$dictionary['msg_provideUsernameAndPassword']='Geef zowel gebruikersnaam als paswoord op';
$dictionary['msg_tempPasswordSent']='Je tijdelijk paswoord is je via email toegestuurd.<br />Gebruik dit paswoord om in te loggen';
$dictionary['msg_usernamePasswordMismatch']='Gebruikersnaam/email combinatie klopt niet';
$dictionary['msg_illegalAccess']='Illegal access';
$dictionary['msg_unknownError']='Onbekende fout';
$dictionary['msg_unknownUser']='Onbekende gebruiker';
$dictionary['msg_incorrectPassword']='Incorrect paswoord';
$dictionary['msg_passwordMismatch']='Paswoorden komen niet overeen';
$dictionary['msg_userAlreadyExists']='Gebruiker bestaat al';
$dictionary['msg_illegalLoginName']='Foutieve gebruikersnaam. De volgende karakters mogen niet gebruikt worden: \', #, $, %, &';
$dictionary['msg_name']='Naam';
$dictionary['msg_signUp']='Sign up';
$dictionary['msg_password']='Paswoord';
$dictionary['msg_rememberMe']='Onhoud me';
$dictionary['msg_lostPassword']='Paswoord verloren?';
$dictionary['msg_copyright']='De homepage het '.$dictionary['programname'].' project kan op hetvolgende adres gevonden worden: <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a>. '.$dictionary['copyright'].' '.$dictionary['authorname'].' (<a href="'.$dictionary['authorurl'].'">'.$dictionary['authorurl'].'</a>) Beschermd door de GPL. Een kopie van de licentie is <a href="documentation/gpl.html">hier</a> te vinden (in het engels)';
$dictionary['msg_submit']='Verstuur';
?>
