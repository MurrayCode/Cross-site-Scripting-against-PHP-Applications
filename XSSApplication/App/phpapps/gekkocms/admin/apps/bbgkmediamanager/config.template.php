<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="<?php echo SITE_HTTPBASE; ?>/admin/templates/babygekko/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITE_HTTPBASE; ?>/admin/templates/babygekko/yui.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="<?php echo SITE_HTTPBASE; ?>/admin/templates/babygekko/images/favicon.ico" />

<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
	global $gekko_config, $gekko_current_admin_user;
?>
<?php displayHeader(); ?>
<?php echo JAVASCRIPT_YUI2_COMBO(); ?>
<?php INIT_REGULAR_EDITOR(); ?>

</head>
<body>
<div id="header"> </div>

<div id="gekko_admin_sidebar">
  <h3><?php echo ucwords($this->app_name); ?> Configuration</h3>
  <div id="gekko_admin_sidebar_editor"></div>
</div>
<!-- end sidebar -->
<div id="gekko_admin_main">
  <div id="gekko_admin_main_editor">
    <form method="post" action="index.php?app=<?php echo $this->app_name; ?>&action=saveconfig" autocomplete="off" >
<?php displayFormSecretTokenHiddenField();  /* CSRF Protection, not activated yet for backward compatibility. */  ?>
      <?php echo H3(TXT_ALLOW_BACKEND_ACCESS_TO_EXTENSION); ?> <?php echo $gekko_config->displayConfigAsMultipleCheckbox($this->app_name,'groups_allowed_for_backend_access',TXT_ALLOW_BACKEND_ACCESS_USER_GROUPS,$gekko_current_admin_user->getGroupIDArrayForPermission()); ?> <?php echo P(TXT_ALLOW_BACKEND_ACCESS_NOTICE); ?>
      <?php editor_button_save(); ?>
      <?php editor_button_cancel(true); ?>
    </form>
  </div>
</div>
<div id="footer">
  <p><?php echo TXT_COPY; ?>right &copy; <a href="http://www.babygekko.com" target="_blank">Baby Gekko IT Consulting</a>. Baby Gekko v<?php echo GEKKO_VERSION; ?>.<br />
    Memory usage: <?php echo round(memory_get_usage()/1024.00); ?> kb. Peak memory usage: <?php echo round( memory_get_peak_usage()/1024.00); ?> kb. </p>
  <!-- end #footer -->
</div>

</body></html>