<!--

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

-->
<?php INIT_REGULAR_EDITOR(); ?>
<?php $filtered_app_name = convert_into_sef_friendly_title($_GET['name'])?>
<div id="gekko_admin_sidebar"> 
</div>
<!-- end sidebar -->
<div id="gekko_admin_main">
  <h3><?php echo TXT_FILTER; ?>: <?php echo $filtered_app_name; ?></h3>
  <?php if (!isset($_POST['sure'])): ?>
   <form method="post" action="index.php?app=<?php echo $this->app_name; ?>&action=uninstall&name=<?php echo $filtered_app_name; ?>" enctype="multipart/form-data" >

   <label><input type="checkbox" name="sure" onclick="javascript:document.getElementById('ok_uninstall_button').disabled=!this.checked;" /><?php echo FILTERS_CONFIRM_UNINSTALL; ?></label><br/>
   <label><input type="checkbox" name="database" /><?php echo FILTERS_CONFIRM_DELETE_DATA; ?></label><br/>
   <label><input type="checkbox" name="everything" /><?php echo FILTERS_CONFIRM_DELETE_EVERYTHING; ?></label><br/>
   <br/>
    <button type="submit" name="submit" class="gekko_larger_editor_button" id="ok_uninstall_button" value="Submit" disabled="disabled">
    	<img src="<?php echo TRANS_SPRITE_IMAGE; ?>" class="img_buttons48 imgsprite48_bigbutton_ok" width="48" height="48" align="absmiddle" /><?php echo TXT_OK; ?></button> 
    <button type="reset" name="clear" class="gekko_larger_editor_button" value="Clear" onclick="javascript:gekko_editor_cancel_return_to_main_app(1);">
    	<img src="<?php echo TRANS_SPRITE_IMAGE; ?>" class="img_buttons48 imgsprite48_bigbutton_cancel" width="48" height="48" align="absmiddle" /><?php echo TXT_CANCEL; ?></button>
		   
   </form>
   <?php else: ?>
   <?php if ($uninstall_result===true): ?>
   <p><?php echo FILTERS_NOTIFIY_UNINSTALLED; ?></p>
   <?php else: ?>
   <p><?php echo TXT_UNINSTALL_FAILED; ?></p>
   <?php endif; ?>
   <?php endif; ?>
  <p id="gekko_admin_current_path"></p><input type="hidden" id="gekko_admin_path_info"></div>
</div>
