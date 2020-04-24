<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//


	$uninstall_location = SITE_URL.SITE_HTTPBASE.'/admin/index.php?app='.$this->app_name.'&action=uninstall&name='.$item['original_block'];
 	INIT_TEXTAREA_EDITOR();
	
?>

<div class="gekko_editor"> 
  <form method="post" name="block_item_editor" id="frm_item_editor" action="index.php?app=<?php echo $this->app_name; ?>&action=saveitem" enctype="multipart/form-data" autocomplete="off" >
  <?php displayFormSecretTokenHiddenField(); /* This particular module has CSRF Protection */ ?>
    <?php global $gekko_config; ?>
    <div id="gekko_dualpane_editor_sidebar">
      <h3><?php echo BLOCKS_BLOCK_EDITOR; ?></h3>
      <div id="gekko_dualpane_editor_content">
        <label><br>
          <?php echo BLOCKS_BLOCK_NAME; ?><br>
          <input name="title" type="text" class="gekko_editor_input" value="<?php echo htmlspecialchars($item['title']); ?>"  />
        </label>
        <label>
          <input name="status" type="checkbox" value="1"<?php if ($item['status']==1 || $item['id']=='new') echo 'checked'; ?> />
          <?php echo TXT_ACTIVE; ?></label>
        <br />
        <?php echo BLOCKS_INSTANCE_OF; ?> <strong><?php echo $item['original_block']; ?></strong> <br />
        <br/>
        <p><a href="<?php echo $uninstall_location; ?>"><?php echo get_css_sprite_img(16,'uninstall'); ?> <?php echo TXT_UNINSTALL; ?></a></p>
      </div>
    </div>
    <!-- end sidebar -->
    <div id="gekko_dualpane_editor_main">
      <div id="gekko_dualpane_editor_content">
        <ul class="yui-nav">
          <li class="selected"><a href="#tab1"><em><?php echo BLOCKS_BLOCK_CONFIGURATION; ?></em></a></li>
          <li><a href="#tab2"><em><?php echo BLOCKS_VISIBILITY; ?></em></a></li>
        </ul>
        <div class="yui-content">
          <div id="tab1">
            <?php 
   if ($this->lastSaveStatus != SAVE_OK) $this->displayError($this->lastSaveStatus);
   ?>
            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
            <input type="hidden" name="display_in_menu_workaround" id="display_in_menu_workaround" value="<?php echo $item['display_in_menu']; ?>" />
            <div class="gekko_editor_main">
              <?php
		$filename = SITE_PATH."/admin/blocks/{$item['original_block']}/config.template.php";
		if (file_exists($filename) ) 
		{
			$block_config = new DynamicConfiguration('gk_block_config');
			$config = $block_config->get($this->app_name);
			include_once ($filename);
		} else echo P("There is no configuration file found for {$item['original_block']}");
		?>
              <br />
              <br />
            </div>
          </div>
          <div id="tab2">
          <p><?php echo BLOCKS_CHOOSE_WHERE_TO_DISPLAY; ?></p>
	     <?php $checked1 = ($item['display_in_menu'] == 1);$checked2 = ($item['display_in_menu'] == 2); ?>
          <label><?php echo INPUT_SINGLERADIOBOX ('display_in_menu',1,$checked1, false,'menu_select_everywhere'); ?><?php echo BLOCKS_VISIBILITY_CHOICE_EVERY_PAGE; ?></label><br />
          <label><?php echo INPUT_SINGLERADIOBOX ('display_in_menu',2,$checked2, false,'menu_select_some'); ?><?php echo BLOCKS_VISIBILITY_CHOICE_SPECIFIC; ?></label><br /><br />
            <div style="margin-left:1em"><div id="gekko_multiple_categories_checkboxes"></div></div>
          </div>
        </div>
      </div>
      <br />
        <fieldset id="fieldset_permission_read">
          <legend><?php echo BLOCKS_PERMISSION_WHO_CAN_READ_ITEM; ?></legend>
          <?php $gekko_current_admin_user->draw_permission_read_checkboxgroup($item['permission_read']); ?>
        </fieldset>
    <?php /*    <fieldset id="fieldset_permission_write">
          <legend><?php echo BLOCKS_PERMISSION_WHO_CAN_WRITE_ITEM; ?></legend>
          <?php $gekko_current_admin_user->draw_permission_write_checkboxgroup($item['permission_write'],$gekko_current_admin_user->getCurrentUserGroupIDs(),true);  ?>
        </fieldset> */ ?>
      
      <br />
              <?php editor_button_save(); ?>
       			 <?php editor_button_apply('item'); ?>
              
              <?php editor_button_cancel(true); ?>
      
    </div>
    
  </form>
</div>
<script type="text/javascript" >
(function() {
    var tabView = new YAHOO.widget.TabView('gekko_dualpane_editor_main');
})();
</script>
