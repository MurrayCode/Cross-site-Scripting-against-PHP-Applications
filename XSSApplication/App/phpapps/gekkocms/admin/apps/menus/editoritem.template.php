<?php 
global $gekko_current_admin_user;

$array_ssl_states = array(  array('label'=>MENUS_CONNECTION_TYPE_AUTO,'value'=>'0'), 
							array('label'=>MENUS_CONNECTION_TYPE_REGULAR,'value'=>'1'), 
							array('label'=>MENUS_CONNECTION_TYPE_SSL,'value'=>'2') );
?>

<div class="gekko_editor">
  <form name="menuitemform" id="frm_item_editor"  method="post" action="index.php?app=<?php echo $this->app_name ?>&action=saveitem" enctype="multipart/form-data"  >
    <div id="gekko_dualpane_editor_sidebar">
      <h3>Menu Editor</h3>
      <br />
      <div id="gekko_dualpane_editor_sidebar_content"> <?php echo INPUT_RADIOBOX('ssl_state',$array_ssl_states,strval($item['ssl_state']),MENUS_CONNECTION_TYPE,false); ?>
        <fieldset id="fieldset_permission_read">
          <legend><?php echo MENU_PERMISSION_WHO_CAN_READ_ITEM; ?></legend>
          <?php $gekko_current_admin_user->draw_permission_read_checkboxgroup($item['permission_read']); ?>
        </fieldset>
        <fieldset id="fieldset_permission_write">
          <legend><?php echo MENU_PERMISSION_WHO_CAN_WRITE_ITEM; ?></legend>
          <?php $gekko_current_admin_user->draw_permission_write_checkboxgroup($item['permission_write'],$gekko_current_admin_user->getCurrentUserGroupIDs(),true);  ?>
        </fieldset>
      </div>
    </div>
    <!-- end sidebar -->
    <div id="gekko_dualpane_editor_main">
    <div id="gekko_dualpane_editor_content">
      <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
      <input type="hidden" name="category_id" value="<?php echo $item['category_id']; ?>" />
      <input type="hidden" name="existing_application" id="existing_application" value="<?php echo $item['application']; ?>" />
      <input type="hidden" name="existing_menuaction" id="existing_menuaction" value="<?php echo $item['menuaction']; ?>" />
      <input type="hidden" name="existing_menuitem" id="existing_menuitem" value="<?php echo $item['menuitem']; ?>" />
      <input type="hidden" name="existing_customurl" id="existing_customurl" value="<?php echo $item['customurl']; ?>" />
      <input type="hidden" name="user_changed_item_or_category" id="user_changed_item_or_category" value="0" />
      <?php if ($item['menuaction'] == 'standard_browse'):
	  		$existing_menu_parent_category = $this->getMenuItemParentCategoryForMenuItemSelection($item['application'],$item['menuitem']); ?>
      <input type="hidden" name="existing_menu_parent_category" id="existing_menu_parent_category" value="<?php echo $existing_menu_parent_category; ?>" />
      <?php endif; ?>
      <div class="gekko_editor_main">
        <h3><?php echo MENUS_EDITOR_INSTRUCTION_STEP_1; ?></h3>
        <label><?php echo TXT_TITLE; ?><br>
          <input name="title" id='pagetitle' class="gekko_editor_input required" type="text" value="<?php echo htmlspecialchars($item['title']); ?>" size="50" maxlength="200" />
        </label>
        <?php
		
		
		if (SEF_ENABLED) 
		{
			$url = SITE_URL.$item['sefurl'];
		} else $url = SITE_URL.'/index.php?'.$item['internalurl'];
			
		if ($item['application'] == 'external_link') $url = $item['customurl'];
		
?>
        currently links to <?php echo $item['application']; ?>:
        <?php if ($item['id']=='new') echo 'undefined (new item)'; else echo $url; ?>
        <br />
        <label>
          <input name="status" type="checkbox" value="1" <?php if ($item['status']==1 || $item['id']=='new') echo 'checked'; ?> />
          <?php echo TXT_ACTIVE; ?></label>
        <br />
        <label>
          <input name="open_in_new_window" type="checkbox" value="1" <?php if ($item['open_in_new_window']==1 ) echo 'checked'; ?> />
          <?php echo MENUS_ITEM_OPEN_IN_NEW_WINDOW; ?></label>
        <br />
        <table width="100%" border="0">
          <tr>
            <td width="50%" valign="top"><?php echo $this->drawApplicationChoices($item['application']); ?></td>
            <td width="50%" valign="top"><div id="menu_application_methods_container"></div></td>
          </tr>
        </table>
        <!-- menu thingy -->
        <table width="100%" border="0">
          <tr>
            <td><div id="gekko_menu_layout">
                <h3><?php echo MENUS_EDITOR_INSTRUCTION_STEP_4; ?></h3>
                <div id="gekko_menu_sidebar">
                  <div id="gekko_menu_sidebar_content"></div>
                </div>
                <!-- end sidebar -->
                <div id="gekko_menu_main">
                  <div class="gekko_menu_panel">
                    <p id="gekko_menu_current_path"></p>
                  </div>
                  <div id="gekko_menu_main_content"></div>
                </div>
              </div></td>
          </tr>
        </table>
      </div>
    </div>
    <?php editor_button_save(); ?>
    <?php editor_button_cancel(true); ?>
  </form>
  <input type="hidden" id="gekko_admin_path_info">
</div>
