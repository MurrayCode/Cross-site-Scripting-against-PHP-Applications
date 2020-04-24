<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
?>
<?php global $gekko_config, $gekko_current_admin_user; ?>
<?php INIT_REGULAR_EDITOR(); ?>
<div id="gekko_admin_sidebar">
  <h3>Web Pages Configuration</h3>
  <div id="gekko_admin_sidebar_editor"></div>
</div>
<!-- end sidebar -->
<div id="gekko_admin_main">
  <div id="gekko_admin_main_editor">
    <form method="post" action="index.php?app=<?php echo $this->app_name ?>&action=saveconfig" autocomplete="off" >
      <?php echo $gekko_config->displayConfigAsTextBoxWithLabel($this->app_name,'alias','Application Alias',true); ?> <br />
      <?php echo $gekko_config->displayConfigAsTextBoxWithLabel($this->app_name,'str_meta_keywords','Default Meta Keywords',true); ?> <br />
      <?php echo $gekko_config->displayConfigAsTextBoxWithLabel($this->app_name,'str_meta_description','Default Meta Description',true);  ?> <br />
      <?php echo $gekko_config->displayConfigAsSingleCheckbox($this->app_name,'chk_convert_external_images','Fetch external images into local images (from pasted HTML codes)'); ?> <br />      
      <?php echo $gekko_config->displayConfigAsSingleCheckbox($this->app_name,'chk_enable_pageview_stats','Enable page impression stats (may slow down SQL)'); ?> <br />
      <br />
      <br />
      <?php echo H3(TXT_ALLOW_BACKEND_ACCESS_TO_EXTENSION); ?> <?php echo $gekko_config->displayConfigAsMultipleCheckbox($this->app_name,'groups_allowed_for_backend_access',TXT_ALLOW_BACKEND_ACCESS_USER_GROUPS,$gekko_current_admin_user->getGroupIDArrayForPermission()); ?> <?php echo P(TXT_ALLOW_BACKEND_ACCESS_NOTICE); ?>
      <?php editor_button_save(); ?>
      <?php editor_button_cancel(true); ?>
    </form>
  </div>
</div>
