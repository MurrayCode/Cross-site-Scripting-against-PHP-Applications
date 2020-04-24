<?php

/**
 * Connects to the database using the variables defined the the file
 * <code>framework/configuration/databaseConfiguration.php</code>
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 * @subpackage util
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
if (!file_exists ('framework/configuration/databaseConfiguration.php'))
{
/*
		die ('Please run the <a href="install.php">installation script</a><br/><br/>
			If you run into this message when embedding brim, edit the
			file "framework/util/databaseConnection.php" and remove the lines
			that contain this specific message.');
*/
}
require_once ('ext/adodb/adodb.inc.php');
include ('framework/configuration/databaseConfiguration.php');

$db = NewADOConnection ($engine);
$db->Connect($host, $user, $password, $database) or die ($db->ErrorMsg());
//$db->debug=true;
?>