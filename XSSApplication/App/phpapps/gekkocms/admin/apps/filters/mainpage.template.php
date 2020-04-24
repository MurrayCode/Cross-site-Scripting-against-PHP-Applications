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
    <p><?php echo FILTERS_NOTICE; ?>.</p>
  </div>
  <div id="gekko_admin_sidebar_content"><br/> 
 <form id="gekko_admin_installform" method="POST" action="index.php?app=<?php echo $this->app_name; ?>&action=install" enctype="multipart/form-data" onsubmit="javascript:return gekko_validate_zip_file(document.getElementById('zipfileupload'));" >
         <?php echo FILTERS_INSTALL_FROM_ZIP; ?>: <br/>
         <input type="file" name="zipfileupload" id="zipfileupload" style="width: 200px" onchange="javascript:return gekko_validate_zip_file(this);"  />
         <br />
        <button type="submit" name="submit"><?php echo get_css_sprite_img (16, 'go-bottom'); ?> Go</button>
   </form>  
  
  
  </div>
</div>
<!-- end sidebar -->
<div id="gekko_admin_main">
<h3><?php echo TXT_FILTERS; ?></h3>
	<div class="gekko_admin_panel">
   <?php toolbar_button('config','config',TXT_CONFIGURATION,"index.php?app={$this->app_name}&action=editconfig"); ?> 
   <br/>
    
  <p id="gekko_admin_current_path"></p></div>
<div id="gekko_admin_main_content"></div>
</div>
