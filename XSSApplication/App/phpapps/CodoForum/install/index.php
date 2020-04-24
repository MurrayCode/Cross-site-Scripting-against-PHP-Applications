<?php

/*
 * @CODOLICENSE
 */

define('IN_CODOF', 'installer');

error_reporting(-1);

date_default_timezone_set('Europe/London'); 
require 'load.php';

global $installed;

if (!isset($_REQUEST['step'])) {

    $step = 1;
} else {

    $step = (int) $_REQUEST['step'];
}

$already_installed = 'no';

if ($installed) {

    if($step != 1) {
        
        header("Location: " . RURI . "index.php?step=1");
        exit;
    }
    $already_installed = 'yes';
}

require "step$step.php";

$url = str_replace('index.php?u=/', '', RURI);
define('HOME' , str_replace("install/", "", $url));

if (!isset($_POST['post_req'])) {
     require "templates/step$step.php";
}
