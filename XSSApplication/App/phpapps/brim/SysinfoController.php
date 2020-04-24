<?php

require_once ('plugins/sysinfo/SysInfoController.php');

/**
 * The SysInfo entry point, this is a admin-only plugin.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.sysinfo
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

if ($_SESSION['brimUsername'] != 'admin')
{
	die ('The SysInfo page can only be accessed by the administrator!');
}
$controller = new SysInfoController ();
$controller -> activate ();
$controller -> display ();
?>