<!--
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
-->
<?php
$userclass_selections = $this->getUsersClassArray();
$usergroup_selections = $this->getUserGroupsArray();
$errormsg = '';
if (!is_writable($this->config_file)) $errormsg = H4('WARNING: Cannot write to configuration file '.$this->config_file);

if (!defined('SITE_HTTP_URL') || SITE_HTTP_URL == '') 
{
	$site_http_url = 'http://'.$_SERVER['SERVER_NAME'];
	if ($_SERVER['SERVER_PORT'] != 80) $site_http_url.=":{$_SERVER['SERVER_PORT']}";
} else $site_http_url = SITE_HTTP_URL;


?>
<div id="gekko_admin_sidebar">
  <div id="gekko_admin_sidebar_content"></div>
</div>
<!-- end sidebar -->
<div id="gekko_admin_main">
  <h3><?php echo SETTINGS_SETTINGS; ?></h3>
  <div id="gekko_admin_main_content">
  <div class="gekko_admin_panel">
  
  <?php toolbar_button('config','config',TXT_PERMISSION,"index.php?app={$this->app_name}&action=editconfig"); ?>                
</div>  
  <?php echo $errormsg; if ($_SESSION['settings_save_ok']==1) echo H4('Saved. Please ensure that the configuration file config.inc.php is not world-writable now.'); $_SESSION['settings_save_ok'] = 0;?>
  <br/><br/><br/>
   <button type="button" name="btn_save" class="gekko_nobreak_button" value="click" onclick="javascript:gekko_app.deleteSQLCache();">
 <img class="img_buttons48 imgsprite48_bigbutton_refresh" src="<?php echo TRANS_SPRITE_IMAGE; ?>"  border="0"  align="absmiddle" />Delete SQL Cache</button>     
  <br/><br/><br/> 
    <form class="gekko_settings" method="post" action="index.php?app=settings&action=savesettings" id="editor" name="settingsEditor"  enctype="multipart/form-data">
    <?php displayFormSecretTokenHiddenField(); /* This particular module has CSRF Protection */ ?>
      <input type="hidden" name="save" value="1" />
      <table border="0" >
        <tr>
          <td><h2><?php echo SETTINGS_SITE; ?></h2></td>
          <td width="200"><label>
              <input type="checkbox" name="site_online" id="site_online" value="1" <?php if (SITE_OFFLINE == false) echo 'checked'; ?>  />
              <?php echo TXT_ACTIVE; ?></label></td>
          <td><h2><?php echo SETTINGS_MAIL; ?></h2></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><?php echo SETTINGS_SITE_NAME; ?></td>
          <td><input name="site_name" id='site_name' class="gekko_editor_input" type="text" value="<?php echo SITE_NAME; ?>"  /></td>
          <td><?php echo SETTINGS_FROM; ?></td>
          <td><input name="mail_default_sender" id='mail_default_sender' class="gekko_editor_input" type="text" value="<?php echo MAIL_DEFAULT_SENDER; ?>"  /></td>
        </tr>
        <tr>
          <td><?php echo SETTINGS_SITE_URL; ?></td>
          <td><input name="site_http_url" id='site_http_url' class="gekko_editor_input validate-http-domain required" type="text" value="<?php echo $site_http_url; ?>"  /></td>
          <td><?php echo SETTINGS_EMAIL; ?></td>
          <td><input name="mail_default_email" id='mail_default_email' class="gekko_editor_input validate-email" type="text" value="<?php echo MAIL_DEFAULT_EMAIL; ?>"  /></td>
        </tr>
        <tr>
          <td><?php echo SETTINGS_BASE_PATH; ?></td>
          <td><input name="site_httpbase" id='site_httpbase' class="gekko_editor_input" type="text" value="<?php echo SITE_HTTPBASE; ?>"  /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><?php echo SETTINGS_ADMIN_LOGIN_VALID_FOR; ?></td>
          <td><input name="admin_login_time" id='admin_login_time' class="gekko_editor_input" type="text" value="<?php echo ADMIN_LOGIN_TIME; ?>"  /></td>
          <td><h2><?php echo SETTINGS_USERS_DEFAULTS; ?></h2></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><h2><?php echo SETTINGS_SSL; ?></h2></td>
          <td><label>
            <input type="checkbox" name="ssl_enabled" id="ssl_enabled"  value="1" <?php if (SSL_ENABLED) echo 'checked'; ?> />
            <?php echo SETTINGS_ENABLED; ?></label></td>
          <td><?php echo SETTINGS_DEFAULT_USER_CLASS; ?></td>
           <td><?php if ($userclass_selections): ?>
             <label>
               <select name="default_user_class" id="default_user_class">
                 <?php foreach ($userclass_selections as $userclass): ?>
                 <option value="<?php echo $userclass; ?>" <?php if ($userclass==DEFAULT_USER_CLASS) echo ' selected '; ?>><?php echo $userclass; ?></option>
                 <?php endforeach; ?>
               </select>
             </label>
             <?php endif; ?></td>
        </tr>
        <tr>
          <td><?php echo SETTINGS_HTTPS; ?></td>
          <td><input name="site_https_url" id='SITE_HTTPS_URL' class="gekko_editor_input validate-https-domain" type="text" value="<?php echo SITE_HTTPS_URL; ?>"  /></td>
          <td><?php echo SETTINGS_DEFAULT_ADMIN_GROUP; ?></td>
           <td><?php if ($usergroup_selections): ?>
             <label>
               <select name="default_admin_group" id="default_admin_group">
                 <?php foreach ($usergroup_selections as $group): ?>
                 <option value="<?php echo $group['label']; ?>" <?php if ($userclass==DEFAULT_ADMIN_GROUP) echo ' selected '; ?>><?php echo $group['value']; ?></option>
                 <?php endforeach; ?>
               </select>
             </label>
             <?php endif; ?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><label>
            <input type="checkbox" name="force_ssl_admin_login" id="force_ssl_admin_login"  value="1" <?php if (FORCE_SSL_ADMIN_LOGIN==1) echo 'checked'; ?> />
            <?php echo SETTINGS_FORCE_SSL_ADMIN_LOGIN; ?></label></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><h2><?php echo SETTINGS_DATABASE; ?></h2></td>
          <td>&nbsp;</td>
          <td><h2><?php echo SETTINGS_SEO_DEFAULT ?></h2></td>
          <td><label><input type="checkbox" name="sef_enabled" id="sef_enabled" value="1" <?php if (SEF_ENABLED == true) echo 'checked'; ?> />
            <?php echo SETTINGS_ENABLE_SEO; ?></label></td>
        </tr>
        <tr>
          <td><?php echo SETTINGS_HOST; ?></td>
          <td><input name="db_host" id='db_host' class="gekko_editor_input required" type="text" value="<?php echo DB_HOST; ?>"  /></td>
          <td><?php echo SETTINGS_META_KEY; ?></td>
          <td><input name="site_meta_key" id='site_meta_key' class="gekko_editor_input" type="text" value="<?php echo SITE_META_KEYWORDS; ?>"  /></td>
        </tr>
        <tr>
          <td><?php echo SETTINGS_DATABASE; ?></td>
          <td><input name="db_database" id='db_database' class="gekko_editor_input required" type="text" value="<?php echo DB_DATABASE; ?>"  /></td>
          <td><?php echo SETTINGS_META_DESCRIPTION; ?></td>
          <td><textarea name="site_meta_description" class="gekko_editor_input" id="site_meta_description"><?php echo SITE_META_DESCRIPTION; ?></textarea></td>
        </tr>
        <tr>
          <td><?php echo TXT_USERNAME; ?></td>
          <td><input name="db_username" id='db_username' class="gekko_editor_input required" type="text" value="<?php echo DB_USERNAME; ?>"  /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><?php echo TXT_PASSWORD; ?></td>
          <td><input name="db_password" id='db_password' class="gekko_editor_input" type="password" value="<?php echo DB_PASSWORD; ?>"  /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><?php echo SETTINGS_ENABLE_SQL_CACHE; ?></td>
          <td><input name="enable_sql_cache" id='enable_sql_cache' class="gekko_editor_input" type="checkbox" value="1" <?php if (SQL_CACHE_ENABLED) echo "checked"; ?>  /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><?php echo SETTINGS_ENFORCE_SQL_ROW_LIMIT; ?></td>
          <td><input name="enforce_sql_row_limit" id='enforce_sql_row_limit' class="gekko_editor_input" type="checkbox" value="1" <?php if (SQL_ENFORCE_ROW_LIMIT) echo "checked"; ?>  /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><?php echo SETTINGS_SQL_CACHE_TIME; ?></td>
          <td><input name="sql_cache_time" id='sql_cache_time' class="gekko_editor_input validate-number" type="text" value="<?php echo SQL_CACHE_TIME; ?>"  /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        
      </table>
      <br />
      <br />
      <br />
      <?php editor_button_save(); ?>
      <?php editor_button_cancel(true); ?>
    </form>
  </div>
</div>
