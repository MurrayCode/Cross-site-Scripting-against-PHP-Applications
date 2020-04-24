<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
	global $gekko_config, $gekko_current_admin_user;
$display_choices = array(  array('label'=>MENUS_DISPLAY_LI,'value'=>'0'), 
							array('label'=>MENUS_DISPLAY_DIV,'value'=>'1') );
	
?>
<?php INIT_REGULAR_EDITOR(); ?>

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
  <?php echo $gekko_config->displayConfigAsRadiobox($this->app_name,'int_menu_html_element', 'Select which HTML element to display the menu item', $display_choices); ?>
      <?php editor_button_save(); ?>
      <?php editor_button_cancel(true); ?>
    </form>
  </div>
</div>
