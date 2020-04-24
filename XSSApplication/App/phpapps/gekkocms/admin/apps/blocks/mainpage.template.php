<!--
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
-->
<div id="gekko_admin_sidebar"> 
<h3><?php echo BLOCKS_POSITIONS; ?></h3>
<div id="gekko_admin_sidebar_content"></div>
<div id="install_block">
  <h3><?php echo TXT_NOTICE; ?></h3>
  <div class="gekko_notice_text">
    <p><?php echo BLOCKS_INSTALL_WARNING; ?></p>    
  </div>
 <form id="gekko_admin_installform" method="POST" action="index.php?app=<?php echo $this->app_name; ?>&action=install" enctype="multipart/form-data" onsubmit="javascript:return gekko_validate_zip_file(document.getElementById('zipfileupload'));" >
         <?php echo BLOCK_INSTALL_FROM_ZIP; ?>: <br/>
         <input type="file" name="zipfileupload" id="zipfileupload" style="width: 200px" onchange="javascript:return gekko_validate_zip_file(this);"  />
         <br />
         
        <button type="submit" name="submit"><?php echo get_css_sprite_img (16, 'go-bottom'); ?> <?php echo TXT_GO; ?></button>
   </form>  
</div>
</div>
<!-- end sidebar -->
<div id="gekko_admin_main">
<h3><?php echo BLOCKS_BLOCK; ?>s</h3>
	<div class="gekko_admin_panel">
   <form id="gekko_admin_searchform" method="GET" action="#" onsubmit="return false" >
         <?php toolbar_button('new_folder','new_folder',TXT_NEW_CATEGORY,"index.php?app={$this->app_name}&action=newcategory"); ?>
   
          <?php toolbar_button('button_cut','button_cut',TXT_CUT, '#',TXT_CUT_HINT); ?>
         <?php toolbar_button('button_copy','button_copy',TXT_COPY,'#',TXT_COPY_HINT); ?>
         <?php toolbar_button('button_paste','button_paste',TXT_PASTE,'#',TXT_PASTE_HINT); ?>
         <?php toolbar_button('button_delete','button_delete',TXT_DELETE,'#',TXT_DELETE_HINT); ?> 
		<?php toolbar_searchbox() ?>
         <?php toolbar_button('config','config',TXT_CONFIGURATION,"index.php?app={$this->app_name}&action=editconfig"); ?>                
        
   
     </form>
  <p id="gekko_admin_current_path"></p></div>
<div id="gekko_admin_main_content"></div>
</div>
