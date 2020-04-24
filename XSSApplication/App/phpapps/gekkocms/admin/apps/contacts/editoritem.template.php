<?php INIT_TEXTAREA_EDITOR(); global $gekko_current_admin_user; ?>

<div class="gekko_editor">  <form id="frm_item_editor" method="post" action="index.php?app=<?php echo $this->app_name; ?>&action=saveitem" enctype="multipart/form-data" >

    <div id="gekko_dualpane_editor_sidebar">
      <h3><?php echo CONTACTS_ITEM_EDITOR; ?></h3>
      <div id="gekko_dualpane_editor_sidebar_content">
        <br />        <?php editor_button_save(); ?>
        <?php editor_button_apply('item'); ?>
        <?php editor_button_cancel(); ?>
</div>
    </div>
    <!-- end sidebar -->
    <div id="gekko_dualpane_editor_main">
      <div id="gekko_dualpane_editor_content">
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
        <input type="hidden" name="category_id" value="<?php echo $item['category_id']; ?>" />
        <label><?php echo CONTACTS_TITLE; ?><br />
          <input name="title" id='pagetitle'  type="text" value="<?php echo htmlspecialchars($item['title']); ?>" size="49" maxlength="254" class="gekko_editor_title_input required" onchange="suggestShortcutFilenameToAnotherTextField(this.value,'virtual_filename')"  />
        </label>
        <label>
          <input name="status" type="checkbox" value="1"<?php if ($item['status']==1 || $item['id']=='new') echo 'checked'; ?> />
          <?php echo TXT_ACTIVE; ?></label>
        <br />
        <label><?php echo TXT_SEO_SHORTCUT; ?><br />
          <input name="virtual_filename" id="virtual_filename" class="gekko_editor_input required" type="text" value="<?php echo $item['virtual_filename']; ?>" size="64" maxlength="254" />
        </label>
        <br /><br /><br />
        <table border="0" cellpadding="5"><tr><td valign="top">
        <label><?php echo CONTACTS_ITEM_BRANCHBUILDING; ?><br /><input name="branch" type="text" value="<?php echo $item['branch']; ?>" size="49" class="gekko_editor_input" /></label><br />
        <label><?php echo CONTACTS_ITEM_CONTACT_PERSON; ?><br /><input name="contact_person" type="text" value="<?php echo $item['contact_person']; ?>" size="49" class="gekko_editor_input" /></label><br />
        <label><?php echo CONTACTS_ITEM_STREET_ADDRESS; ?><br /><input name="street" type="text" value="<?php echo $item['street']; ?>" size="49" class="gekko_editor_input" /></label><br />
        <label><?php echo CONTACTS_ITEM_CITY; ?><br /><input name="city" type="text" value="<?php echo $item['city']; ?>" size="49" class="gekko_editor_input" /></label><br />
        <label><?php echo CONTACTS_ITEM_PROVINCESTATE; ?><br /><input name="province" type="text" value="<?php echo $item['province']; ?>" size="49" class="gekko_editor_input" /></label><br />
        <label><?php echo CONTACTS_ITEM_POSTALZIP; ?><br /><input name="postal" type="text" value="<?php echo $item['postal']; ?>" size="49" class="gekko_editor_input" /></label><br />
        <label><?php echo CONTACTS_ITEM_COUNTRY; ?><br /><input name="country" type="text" value="<?php echo $item['country']; ?>" size="49" class="gekko_editor_input" /></label><br />
        </td><td valign="top">
        <label><?php echo CONTACTS_ITEM_TOLLFREE; ?><br /><input name="tollfree" type="text" value="<?php echo $item['tollfree']; ?>" size="49" class="gekko_editor_input" /></label><br />
        <label><?php echo CONTACTS_ITEM_PHONE; ?><br /><input name="phone" type="text" value="<?php echo $item['phone']; ?>" size="49" class="gekko_editor_input" /></label><br />
        <label><?php echo CONTACTS_ITEM_FAX; ?><br /><input name="fax" type="text" value="<?php echo $item['fax']; ?>" size="49" class="gekko_editor_input" /></label><br />
        <label><?php echo CONTACTS_ITEM_MOBILE; ?><br /><input name="mobile" type="text" value="<?php echo $item['mobile']; ?>" size="49" class="gekko_editor_input" /></label><br />
        <label><?php echo CONTACTS_ITEM_EMAIL; ?><br /><input name="email" type="text" value="<?php echo $item['email']; ?>" size="49" class="gekko_editor_input required validate-email" /></label>
        <p><?php echo CONTACTS_OPTION_DISPLAY_EMAIL_ADDRESS; ?>
        <label>
          <input type="radio" name="display_email" id="display_email_yes" class="validate-one-required" value="1" <?php if ($item['display_email']==1) echo ' checked '; ?> />
          <?php echo TXT_YES; ?></label>
           <label>
          <input type="radio" name="display_email" id="display_email_no" value="0" <?php if ($item['display_email'] ==0) echo ' checked '; ?> />
          <?php echo TXT_NO; ?></label></p>
          </td></tr></table>
        <br />
        <!-- tinymce -->
        <!-- end tinymce -->
        <label><?php echo CONTACTS_ADDITIONAL_INFO; ?><br />
          <textarea name="additional_info" id="additional_info"  ><?php echo $item['additional_info']; ?></textarea>
        </label>
        <br />
      </div>
    </div>
  </form>
</div>
