<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko, Inc.
// http://www.babygekko.com
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// This is for frontend only, if an app/block/filter needs it . Backend does not need this 
header('Content-type: text/javascript; charset=UTF-8'); 
header("Expires: " . gmdate("D, d M Y H:i:s", time() + (14400 * 24)) . " GMT");
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_COMPILE_ERROR  | E_CORE_ERROR );
include('../config.inc.php');
include('../connector.inc.php');
include_inc('definitions.inc.php');
include_inc('db.inc.php');
include_inc('dbconfig.inc.php');
include_inc('util.inc.php');
include_inc('app_interface.php');
include_inc('app_basic.class.php');
include_inc('init.inc.php');
include_inc('templates.inc.php');

$SiteTemplate = new templates();
$current_template = $SiteTemplate->getCurrentTemplate();
?>
var site_httpbase = "<?php echo SITE_HTTPBASE; ?>";
var site_template = "<?php echo SITE_HTTPBASE.'/templates/'.$current_template; ?>";
var datatable_max_row_perpage = <?php echo DATATABLE_MAX_ROW_PERPAGE; ?>;
<?php getJavascriptFormSecretTokenHiddenField('_csrftoken','true'); ?>


