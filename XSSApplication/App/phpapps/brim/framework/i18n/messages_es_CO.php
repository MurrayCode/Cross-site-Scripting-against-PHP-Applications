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
$dictionary['msg_notAllowedForTestUser']='Esta operaci�n no es permitida al usuario de prueba';
$dictionary['msg_provideEmailAndPassword']='Por favor indique: correo electr�nico y contrase�a';
$dictionary['msg_provideUsernameAndPassword']='Por favor indique: nombre de usuario y contrase�a';
$dictionary['msg_tempPasswordSent']='Su contrase�a temporal ha sido enviada por correo electr�nico.<br />Por favor �tilice esta contrase�a para entrar';
$dictionary['msg_usernamePasswordMismatch']='Combinaci�n incorrecta de nombre de usuario y contrase�a';
$dictionary['msg_illegalAccess']='Acceso no permitido';
$dictionary['msg_unknownError']='Error Desconocido';
$dictionary['msg_unknownUser']='Usuario Desconocido';
$dictionary['msg_incorrectPassword']='Contrase�a Incorrecta';
$dictionary['msg_passwordMismatch']='Contrase�a No apareada';
$dictionary['msg_userAlreadyExists']='El nombre de usuario ya existe';
?>
