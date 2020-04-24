<?php global $gekko_config, $gekko_current_admin_user; ?>
<?php INIT_REGULAR_EDITOR(); ?>

<?php $group_choices_array = $this->app->getGroupIDArrayForPermission(); ?>
<div id="gekko_admin_sidebar">
  <h3><?php echo USERS_CONFIGURATION; ?></h3>
  <div id="gekko_admin_sidebar_editor"></div>
</div>
<!-- end sidebar -->
<div id="gekko_admin_main">
  <div id="gekko_admin_main_editor">
    <?php 
   if ($this->lastSaveStatus != SAVE_OK) $this->displayError($this->lastSaveStatus);
   ?>
    <form method="post" action="index.php?app=<?php echo $this->app_name ?>&action=saveconfig" autocomplete="off" >
      <div class="gekko_editor_main">
      <h3>General Options</h3>
        <fieldset>
          <?php //echo $gekko_config->displayConfigAsTextBoxWithLabel($this->app_name,'str_frontend_url_alias',USERS_URL_ALIAS); ?>
          <?php echo $gekko_config->displayConfigAsSingleCheckbox($this->app_name,'force_ssl_authentication',USERS_FORCE_SSL_AUTHENTICATION); ?> <br />          
          <?php // echo $gekko_config->displayConfigAsSingleCheckbox($this->app_name,'force_ssl_admin_authentication',USERS_FORCE_SSL_ADMIN_AUTHENTICATION); ?>
          <?php echo $gekko_config->displayConfigAsSingleCheckbox($this->app_name,'chk_enable_adminfrontend_login',USERS_ENABLE_ADMINISTRATORS_TO_LOG_IN_TO_THE_FRONT_END); ?> <br />
          <?php echo $gekko_config->displayConfigAsSingleCheckbox($this->app_name,'chk_enable_registration',USERS_ENABLE_REGISTRATION); ?> <br />

          <?php echo $gekko_config->displayConfigAsSingleCheckbox($this->app_name,'chk_enable_captcha_user_registration',USERS_ENABLE_CAPTCHA_FOR_USER_REGISTRATION); ?> <br />
          <?php echo $gekko_config->displayConfigAsTextBoxWithLabel($this->app_name,'int_number_of_login_retry_before_captcha',USERS_NUMBER_OF_LOGIN_RETRY_BEFORE_CAPTCHA); ?><br/>          
          <?php			
			echo $gekko_config->displayConfigAsDropDownSelection($this->app_name,'int_default_newuser_group_id','Default Group for New User Registration',$group_choices_array);
		  ?>
          
        </fieldset><br/>
      <?php echo H3(TXT_ALLOW_BACKEND_ACCESS_TO_EXTENSION); ?> <?php echo $gekko_config->displayConfigAsMultipleCheckbox($this->app_name,'groups_allowed_for_backend_access',TXT_ALLOW_BACKEND_ACCESS_USER_GROUPS,$gekko_current_admin_user->getGroupIDArrayForPermission()); ?> <?php echo P(TXT_ALLOW_BACKEND_ACCESS_NOTICE); ?>
        
          <h3> Allow backend login for the following users: </h3>
 	    <?php echo $gekko_config->displayConfigAsMultipleCheckbox($this->app_name,'groups_allowed_for_backend_login','User Groups',$group_choices_array); ?>
        <p>Administrators will always be allowed to login to the backend, regardless of the settings above</p>
        <br/>
        <?php editor_button_save(); ?>
        <?php editor_button_cancel(true); ?>
      </div>
    </form>
  </div>
</div>
