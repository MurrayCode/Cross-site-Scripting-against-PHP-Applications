<!--
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
-->
<div id="gekko_admin_sidebar">
  <h3><?php echo TXT_NOTICE; ?></h3>
  <div class="gekko_notice_text">
    <p><?php echo APPLICATIONS_INSTALL_WARNING; ?></p>    
  </div>
  <div id="gekko_admin_sidebar_content">
  <br />
 <form id="gekko_admin_installform" method="POST" action="index.php?app=<?php echo $this->app_name; ?>&action=install" enctype="multipart/form-data" onsubmit="javascript:return gekko_validate_zip_file(document.getElementById('zipfileupload'));" >
         <?php echo APPLICATIONS_INSTALL_FROM_ZIP; ?> <br/>
         <input type="file" name="zipfileupload" id="zipfileupload" style="width: 200px" onchange="javascript:return gekko_validate_zip_file(this);"  />
         
         <br />
        <button type="submit" name="submit"><?php echo get_css_sprite_img (16, 'go-bottom'); ?> <?php echo TXT_GO; ?></button>
   </form>  
  </div>
</div>
<!-- end sidebar -->
<div id="gekko_admin_main">
  <h3><?php echo APPLICATIONS_3RD_PARTY; ?></h3>
  <div class="gekko_admin_panel">
   <?php toolbar_button('config','config',TXT_CONFIGURATION,"index.php?app={$this->app_name}&action=editconfig"); ?> 
    <?php toolbar_button('get_more_extension','config',APPLICATIONS_GET_MORE_EXTENSIONS,'http://www.babygekko.com/site/extensions/',APPLICATIONS_GET_MORE_EXTENSIONS,'_blank'); ?>
   </div>
   <br/><br/>
  <form name="thirdparty">
  <table border="0" cellpadding="5" cellspacing="5"><tr>
    <?php $apps = $this->app->getAllItems(); foreach ($apps as $app): ?>
    <?php 
    	  $app_icon = 'apps/'.$app['title'].'/'.$app['title'].'_logo.png';
    	  if (!file_exists (SITE_PATH.'/admin/'.$app_icon)) $app_icon = 'images/icon_plugins.png'; ?>
    <td align="center">
      <a href="index.php?app=<?php echo $app['title']; ?>"><IMG SRC="<?php echo $app_icon; ?>" border="0" alt="<?php echo $app['title']; ?>" title="<?php echo $app['title']; ?>" /></a>
      <br/>
  <?php /*  <input type="radio" name="item_tobe_uninstalled" id="<?php echo $app['id']; ?>" value="<?php echo $app['title']; ?>" onclick="javascript:gekko_app.enable<?php echo TXT_UNINSTALL; ?>Button();" /> */ ?>
  <a href="index.php?app=<?php echo $app['title']; ?>"><?php  echo $app['title']; ?></a>
    </td>
    <?php endforeach;	?>
    </tr></table>
    </form>
  <div id="gekko_admin_main_content" style="display:none"></div>
</div>
