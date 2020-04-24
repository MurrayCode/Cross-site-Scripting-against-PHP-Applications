<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
 INIT_TEXTAREA_EDITOR(); 
 global $gekko_current_admin_user;
 if ($id == 'new') $item['permission_read']=serialize('everyone');
?>
<div class="gekko_editor">
  <form id="frm_item_editor" method="post" action="index.php?app=<?php echo $this->app_name; ?>&action=saveitem" enctype="multipart/form-data" >
    <div id="gekko_dualpane_editor_sidebar">
      <h3><?php echo BLOG_POST_EDITOR; ?></h3>
      <div id="gekko_dualpane_editor_sidebar_content">
        <table class="gekko_date_attributes" border="0">
          <tr>
            <td><?php echo TXT_CREATED; ?></td>
            <td><?php date_editor_with_calendar_input('date_created',$item['date_created']); ?></td>
          </tr>
          <tr>
            <td><?php echo TXT_MODIFIED; ?></td>
            <td><?php date_editor_with_calendar_input('date_modified',$item['date_modified']); ?></td>
          </tr>
          <tr>
            <td><?php echo TXT_PUBLISHED; ?></td>
            <td><?php date_editor_with_calendar_input('date_available',$item['date_available']); ?></td>
          </tr>
          <tr>
            <td><?php echo TXT_EXPIRED; ?></td>
            <td><?php date_editor_with_calendar_input('date_expiry',$item['date_expiry']); ?></td>
          </tr>
        </table><br />
        <?php  $this->displayItemOptions($item['options']); ?>
        
        <fieldset id="fieldset_permission_read">
          <legend><?php echo TXT_PERMISSION_WHO_CAN_READ_ITEM; ?></legend>
       <?php $gekko_current_admin_user->draw_permission_read_checkboxgroup($item['permission_read']); ?>
        </fieldset>
        <fieldset id="fieldset_permission_write">
          <legend><?php echo TXT_PERMISSION_WHO_CAN_WRITE_ITEM; ?></legend>
          <?php $gekko_current_admin_user->draw_permission_write_checkboxgroup($item['permission_write'],$gekko_current_admin_user->getCurrentUserGroupIDs(),true);  ?>
        </fieldset>
        <p>
        <label><?php echo TXT_META_KEYWORD; ?><br />
          <input type="text" class="gekko_editor_input gekko_meta_inputs" name="meta_key" value="<?php echo $item['meta_key']; ?>" />
        </label>
        <br />
        <label><?php echo TXT_META_DESCRIPTION; ?><br />
          <input type="text" class="gekko_editor_input gekko_meta_inputs" name="meta_description" value="<?php echo $item['meta_description']; ?>" />
        </label>
        </p>
        <br /><br />        
		<?php editor_button_save(); ?>
        <?php editor_button_apply('item'); ?>
        <?php editor_button_cancel(); ?>
</div>
    </div>
    <!-- end sidebar -->
    <div id="gekko_dualpane_editor_main">
      <div id="gekko_dualpane_editor_content">
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
        <input type="hidden" name="category_id" value="<?php echo $item['category_id']; ?>" />
        
        <label><?php echo TXT_PAGE_TITLE; ?><br />
          <input name="title" id='pagetitle'  type="text" value="<?php echo htmlspecialchars($item['title']); ?>" size="49" maxlength="254" class="gekko_editor_title_input required" onchange="suggestShortcutFilenameToAnotherTextField(this.value,'virtual_filename')" />
        </label>
        <label>
          <input name="status" type="checkbox" value="1"<?php if ($item['status']==1 || $item['id']=='new') echo 'checked'; ?> />
          <?php echo TXT_ACTIVE; ?></label>
        <br />
        <br />
        <label><?php echo TXT_SEO_SHORTCUT; ?><br />
          <input name="virtual_filename" id="virtual_filename" class="gekko_editor_input validate-filename required" type="text" value="<?php echo $item['virtual_filename']; ?>" size="64" maxlength="254" <?php if ($item['virtual_filename']=='home' && $item['id'] == 1) echo 'readonly';?> />  <?php if ($item['virtual_filename']=='home' && $item['id'] == 1) echo BLOG_NOTICE_HOMESHORTCUT_CANNOT_BE_MODIFIED;?>
        </label>
        <br />
        <br />
        <!-- tinymce -->
        <!-- end tinymce -->
        <label><?php echo TXT_TEXT_SUMMARY; ?>
          <textarea name="summary" id="summary"  style="width:100%"><?php echo $item['summary']; ?></textarea>
          <br />
        </label>
        <label><?php echo TXT_TEXT_DETAILS; ?><br />
          <textarea name="description" id="description"  style="width:100%"><?php echo $item['description']; ?></textarea>
        </label>
        
        <br />
        <?php $gekko_current_admin_user->draw_username_selection_field('created_by_id',$item['created_by_id'],TXT_CREATED_BY_ID); ?>
        <?php $gekko_current_admin_user->draw_username_selection_field('modified_by_id',$item['modified_by_id'],TXT_MODIFIED_BY_ID); ?>        
        
        <br />

      </div>
    </div>
  </form>
</div>
