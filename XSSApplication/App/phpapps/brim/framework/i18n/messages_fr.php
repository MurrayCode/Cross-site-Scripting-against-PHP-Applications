<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Thibaut Cousin
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
$dictionary['msg_cookieValidationFailed']='La validation du cookie a &#233;chou&#233;';
$dictionary['msg_copyright']='La page d\'accueil du projet '.$dictionary['programname'].' se trouve &#224; l\'adresse suivante&nbsp;:
<a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a>.<br />'.$dictionary['copyright'].' par '.$dictionary['authorname'].' (<a href="'.$dictionary['authorurl'].'">'.$dictionary['authorurl'].'</a>) Logiciel plac&#233; sous licence GPL.<br/> Une copie de cette licence est fournie <a href="documentation/gpl.html">ici</a>.';
$dictionary['msg_emailAddress']='Adresse &#233;lectronique';
$dictionary['msg_illegalAccess']='Acc&#232;s non autoris&#233;';
$dictionary['msg_illegalLoginName']='Nom d\'utilisateur non valable, il ne doit pas contenir les caract&#232;res suivants&nbsp;: \', #, $, %, &';
$dictionary['msg_incorrectPassword']='Mot de passe incorrect';
$dictionary['msg_loginName']='Nom d\'utilisateur';
$dictionary['msg_lostPassword']='Mot de passe oubli&#233; ?';
$dictionary['msg_name']='Nom';
$dictionary['msg_notAllowedForTestUser']='Cette op&#233;ration n\'est pas autoris&#233;e pour l\'utilisateur test';
$dictionary['msg_password']='Mot de passe';
$dictionary['msg_passwordMismatch']='Le mot de passe ne correspond pas';
$dictionary['msg_provideEmailAndPassword']='Veuillez saisir une adresse &#233;lectronique et un mot de passe';
$dictionary['msg_provideUsernameAndPassword']='Veuillez saisir un nom d\'utilisateur et un mot de passe';
$dictionary['msg_rememberMe']='Se rappeler de moi';
$dictionary['msg_signUp']='S\'inscrire';
$dictionary['msg_submit']='Envoyer';
$dictionary['msg_tempPasswordSent']='Votre mot de passe temporaire vient d\'&#234;tre exp&#233;di&#233; par courrier &#233;lectronique.<br />Vous pourrez l\'utiliser pour vous connecter.';
$dictionary['msg_unknownError']='Erreur inconnue';
$dictionary['msg_unknownUser']='Utilisateur inconnu';
$dictionary['msg_userAlreadyExists']='L\'utilisateur existe d&#233;j&#224;';
$dictionary['msg_usernamePasswordMismatch']='Cette combinaison nom d\'utilisateur/adresse &#233;lectronique n\'est pas valable.';

?>
