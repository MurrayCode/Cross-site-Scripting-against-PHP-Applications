<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
 	
	$uninstall_location = SITE_URL.SITE_HTTPBASE.'/admin/index.php?app='.$this->app_name.'&action=uninstall&name='.$item['title']; 
	$app_list = $this->getListofApplicationsOrBlocks('apps');
	$block_list = $this->getListofApplicationsOrBlocks('blocks');	
	$array_enabled_apps = unserialize ($item['enabled_apps']);
	$array_enabled_blocks = unserialize ($item['enabled_blocks']);	
 	INIT_TEXTAREA_EDITOR();
 	
?>

<div class="gekko_editor">
  <form method="post" name="filter_item_editor" action="index.php?app=<?php echo $this->app_name; ?>&action=saveitem" enctype="multipart/form-data" autocomplete="off" >
    <?php global $gekko_config; ?>
    <div id="gekko_dualpane_editor_sidebar">
      <h3><?php echo FILTERS_ITEM_EDITOR; ?></h3>
      <div id="gekko_dualpane_editor_content">
        <label><br>
          <?php echo FILTERS_ITEM_NAME; ?><br>
          <strong><?php echo htmlspecialchars($item['title']); ?></strong></label>
        <label>
          <input name="status" type="checkbox" value="1"<?php if ($item['status']==1 || $item['id']=='new') echo 'checked'; ?> />
          <?php echo TXT_ACTIVE; ?></label>
        <br />
   <p><a href="<?php echo $uninstall_location; ?>"><?php echo get_css_sprite_img(16,'uninstall'); ?> Uninstall</a></p>

      </div>
    </div>
    <!-- end sidebar -->
    <div id="gekko_dualpane_editor_main">
      <div id="gekko_dualpane_editor_content">
        <ul class="yui-nav">
          <li class="selected"><a href="#tab1"><em><?php echo FILTERS_CONFIGURATION; ?></em></a></li>
          <li><a href="#tab2"><em><?php echo FILTERS_APPLICATION_VISIBILITY; ?></em></a></li>
          <li><a href="#tab3"><em><?php echo FILTERS_BLOCK_VISIBILITY; ?></em></a></li>
        </ul>
        <div class="yui-content">
          <div id="tab1">
            <?php 
   if ($this->lastSaveStatus != SAVE_OK) $this->displayError($this->lastSaveStatus);
   ?>
            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
            <input type="hidden" name="display_in_apps_workaround" id="display_in_apps_workaround" value="<?php echo $item['display_in_apps']; ?>" />
            <input type="hidden" name="display_in_blocks_workaround" id="display_in_blocks_workaround" value="<?php echo $item['display_in_blocks']; ?>" />
            <div class="gekko_editor_main">
              <?php
		$filename = SITE_PATH."/admin/filters/{$item['title']}/config.template.php";
		if (file_exists($filename) ) 
		{
			$filter_config = new DynamicConfiguration('gk_filter_config');
			$config = $filter_config->get($this->app_name);
			include_once ($filename);
		} else echo P("There is no configuration file found for {$item['title']}");
		?>
              <br />
              <br />
            </div>
          </div> <!-- end tab -->
          
          <div id="tab2">
          <p><?php echo FILTERS_CHOOSE_WHICH_APP; ?></p>
	     <?php $checked1 = ($item['display_in_apps'] == 1);$checked2 = ($item['display_in_apps'] == 2); ?>
          <label><?php echo INPUT_SINGLERADIOBOX ('display_in_apps',1,$checked1, false,'apps_select_all'); ?><?php echo FILTERS_CHOICE_APP_ALL; ?></label><br />
          <label><?php echo INPUT_SINGLERADIOBOX ('display_in_apps',2,$checked2, false,'apps_select_some'); ?><?php echo FILTERS_CHOICE_APP_SOME; ?>:</label><br /><br />
          <div id="app_selections">
          <?php echo INPUT_MULTIPLECHECKBOX('enabled_apps',$app_list,$array_enabled_apps, FILTERS_APPLICATIONS_TO_BE_ENABLED);  ?>
          </div>
          </div> <!-- end tab -->
          
          <div id="tab3">
          <p>Please choose which block(s) should process this filter:</p>
	     <?php $checked1 = ($item['display_in_blocks'] == 1);$checked2 = ($item['display_in_apps'] == 2); ?>
          <label><?php echo INPUT_SINGLERADIOBOX ('display_in_blocks',1,$checked1, false,'blocks_select_all'); ?><?php echo FILTERS_CHOICE_BLOCK_ALL; ?></label><br />
          <label><?php echo INPUT_SINGLERADIOBOX ('display_in_blocks',2,$checked2, false,'blocks_select_some'); ?><?php echo FILTERS_CHOICE_BLOCK_SOME; ?>:</label><br /><br />
            <div id="block_selections">
				<?php echo INPUT_MULTIPLECHECKBOX('enabled_blocks',$block_list,$array_enabled_blocks, FILTERS_BLOCKS_TO_BE_ENABLED);  ?>
            </div>
          </div><!-- end tab -->
          
        </div>
      </div>
      <br />
              <?php editor_button_save(); ?>
              <?php editor_button_cancel(true); ?>
      
    </div>
    
  </form>
</div>
<script type="text/javascript" >
(function() {
    var tabView = new YAHOO.widget.TabView('gekko_dualpane_editor_main');
})();
</script>

    
    <?php /*
    
    
    <div id="gekko_dualpane_editor_main">
      <div id="gekko_dualpane_editor_content">
        <h3>Filter Configuration</h3>
        <?php 
   if ($this->lastSaveStatus != SAVE_OK) $this->displayError($this->lastSaveStatus);
   ?>
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
        <div class="gekko_editor_main">
          <?php
		$filename = SITE_PATH."/admin/{$this->app_name}/{$item['original_block']}/config.template.php";
		if (file_exists($filename) ) 
		{
			$filter_config = new DynamicConfiguration('filter_config');
			$config = $filter_config->get($this->app_name);
			include_once ($filename);
		} else echo P("There is no configuration file found for {$item['original_block']}");
		?>
          <br />
          <br />
          <?php editor_button_save(); ?>
          <?php editor_button_cancel(true); ?>
        </div><!-- end gekko_editor_main -->
        
      </div><!-- end gekko_dualpane_editor_content -->
    </div><!-- end gekko_dualpane_editor_main -->
  </form>
</div>
*/ ?>