<!--

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

-->
<div id="gekko_admin_sidebar"> 
<h3><?php echo $this->app_name; ?></h3>
<div id="gekko_admin_sidebar_content"></div>
</div>
<!-- end sidebar -->
<div id="gekko_admin_main">
	<div class="gekko_admin_panel">
   <form id="gekko_admin_searchform" method="GET" action="#" onsubmit="return false" >
         <?php toolbar_button('new_document','new_document',TXT_NEW_ITEM,"index.php?app={$this->app_name}&action=newitem"); ?>
          <?php toolbar_button('new_folder','new_folder',TXT_NEW_CATEGORY,"index.php?app={$this->app_name}&action=newcategory"); ?>
		 <?php toolbar_button('button_cut','button_cut',TXT_CUT, '#',TXT_CUT_HINT); ?>
         <?php toolbar_button('button_copy','button_copy',TXT_COPY,'#',TXT_COPY_HINT); ?>
         <?php toolbar_button('button_paste','button_paste',TXT_PASTE,'#',TXT_PASTE_HINT); ?>
         <?php toolbar_button('button_delete','button_delete',TXT_DELETE,'#',TXT_DELETE_HINT); ?> 
		<?php toolbar_searchbox() ?>
        <?php toolbar_button('config','config',TXT_CONFIGURATION,"index.php?app={$this->app_name}&action=editconfig"); ?>		
         <?php toolbar_button('info','info',TXT_HELP,"index.php?app={$this->app_name}&action=help"); ?>
     </form>
  <p id="gekko_admin_current_path"></p><input type="hidden" id="gekko_admin_path_info"></div>
<div id="gekko_admin_main_content"></div>
</div>
