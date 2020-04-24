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
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
session_start();
//
// Clean up the session...
//
unset($_SESSION['brimUsername']);
session_destroy();
//
// Clear the Cookie information by setting its expiration date in the past
//
setCookie ("Brim", "", time () -3600);
//
// Forward to login page
//
header('Location: login.php');
exit;
?>