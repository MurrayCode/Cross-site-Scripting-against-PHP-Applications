<?php
/**
 * The Password hookup
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - August 2006
 * @package org.brim-project.plugins.passwords
 * @subpackage configuration
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

if (function_exists('mcrypt_encrypt') && function_exists ('mcrypt_decrypt'))
{
	$plugins['passwords']['name']='passwords';
	$plugins['passwords']['controller']='PasswordController.php';
	$plugins['passwords']['controllerName']='PasswordController';
	$plugins['passwords']['serviceLocation']=
			'plugins/passwords/model/PasswordServices.php';
	$plugins['passwords']['serviceName']= 'PasswordServices';
	$plugins['passwords']['searchFields']=array('name');
}
?>