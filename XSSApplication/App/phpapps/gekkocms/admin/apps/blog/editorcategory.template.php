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
 if ($id == 'new') 
 {
	 $category['permission_read']=serialize('everyone');
	 $category['items_per_page'] = 10;
 }
?>

<div class="gekko_editor">
  <form id="frm_category_editor" method="post" action="index.php?app=<?php echo $this->app_name ?>&action=savecategory" enctype="multipart/form-data" >
    <div id="gekko_dualpane_editor_sidebar">
      <h3><?php echo BLOG_CATEGORY_EDITOR; ?></h3>
      <div id="gekko_dualpane_editor_sidebar_content">
      <p><input type="text" name="items_per_page" id="items_per_page"  value="<?php echo $category['items_per_page']; ?>" size="2" maxlength="5" /> <?php echo BLOG_CATEGORY_DISPLAY_ITEMS_PERPAGE_IN_CATEGORY; ?></p>
      
       <table class="gekko_date_attributes" border="0">
          <tr>
            <td><?php echo TXT_CREATED; ?></td>
            <td><?php date_editor_with_calendar_input('date_created',$category['date_created']); ?></td>
          </tr>
          <tr>
            <td><?php echo TXT_MODIFIED; ?></td>
            <td><?php date_editor_with_calendar_input('date_modified',$category['date_modified']); ?></td>
          </tr>
          <tr>
            <td><?php echo TXT_PUBLISHED; ?></td>
            <td><?php date_editor_with_calendar_input('date_available',$item['date_available']); ?></td>
          </tr>
          <tr>
            <td><?php echo TXT_EXPIRED; ?></td>
            <td><?php date_editor_with_calendar_input('date_expiry',$item['date_expiry']); ?></td>
          </tr>
        </table>
        <br />
		<?php  $this->displayCategoryOptions($category['options']); ?>
        <fieldset id="fieldset_permission_read">
          <legend><?php echo TXT_PERMISSION_WHO_CAN_READ_CATEGORY; ?></label></legend>
       <?php $gekko_current_admin_user->draw_permission_read_checkboxgroup($category['permission_read']); ?>
        </fieldset>
        <fieldset id="fieldset_permission_write">
          <legend><?php echo TXT_PERMISSION_WHO_CAN_WRITE_CATEGORY; ?></legend>
          <?php $gekko_current_admin_user->draw_permission_write_checkboxgroup($category['permission_write'],$gekko_current_admin_user->getCurrentUserGroupIDs(),true);  ?>
        </fieldset>
        
         <p>
          <label><?php echo TXT_META_KEYWORD; ?><br />
            <input type="text" class="gekko_editor_input gekko_meta_inputs" name="meta_key" value="<?php echo $category['meta_key']; ?>" />
          </label>
          <br />
          <label><?php echo TXT_META_DESCRIPTION; ?><br />
            <input type="text" class="gekko_editor_input gekko_meta_inputs" name="meta_description" value="<?php echo $category['meta_description']; ?>" />
          </label>
        </p>
        <br />
        <br />        <?php editor_button_save(); ?>
        <?php editor_button_apply('category'); ?>
        <?php editor_button_cancel(); ?>
</div>
    </div>
    <!-- end sidebar -->
    <div id="gekko_dualpane_editor_main">
      <div id="gekko_dualpane_editor_content">
        <input type="hidden" name="cid" id="cid" value="<?php echo $category['cid']; ?>" />
         <input type="hidden" name="parent_id" value="<?php echo $category['parent_id']; ?>" />
        <label><?php echo TXT_CATEGORY_TITLE; ?><br />
          <input name="title" id='pagetitle'  type="text" value="<?php echo $category['title']; ?>" size="49" maxlength="254" class="gekko_editor_title_input required"  onchange="suggestShortcutFilenameToAnotherTextField(this.value,'virtual_filename');" />
        </label>
        <label>
          <input name="status" type="checkbox" value="1"<?php if ($category['status']==1 || $category['cid']=='new') echo 'checked'; ?> />
          <?php echo TXT_ACTIVE; ?></label>
        <br />
        <br />
        <label><?php echo TXT_SEO_SHORTCUT; ?><br />
          <input name="virtual_filename" id="virtual_filename" class="gekko_editor_input validate-filename required"  type="text" value="<?php echo $category['virtual_filename']; ?>" size="64" maxlength="254" />
        </label>
        <br />
        <br />
        <!-- tinymce -->
        <!-- end tinymce -->
        <label><?php echo TXT_TEXT_SUMMARY; ?>
          <textarea name="summary" id="summary"  style="width:100%"><?php echo $category['summary']; ?></textarea>
          <br />
        </label>
        <br />
        <?php $gekko_current_admin_user->draw_username_selection_field('created_by_id',$item['created_by_id'],TXT_CREATED_BY_ID); ?>
        <?php $gekko_current_admin_user->draw_username_selection_field('modified_by_id',$item['modified_by_id'],TXT_MODIFIED_BY_ID); ?>        
        
      </div>
    </div>
  </form>
</div>

