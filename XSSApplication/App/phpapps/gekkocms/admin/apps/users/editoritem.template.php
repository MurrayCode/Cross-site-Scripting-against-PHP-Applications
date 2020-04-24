<?php INIT_REGULAR_EDITOR(); ?>

<div class="gekko_editor">
  <div id="gekko_dualpane_editor_sidebar">
    <h3>User Editor</h3>
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
      </table>
      <br />
    </div>
  </div>
  <!-- end sidebar -->
  <div id="gekko_dualpane_editor_main">
<div id="gekko_dualpane_editor_main_content" class="yui-navset">

    <ul class="yui-nav">
        <li class="selected"><a href="#tab1"><em>User Information</em></a></li>
        <li><a href="#tab2"><em>User Groups</em></a></li>
        
    </ul>      

    <div class="yui-content">
        <div id="tab1">
      <?php 
   if ($this->lastSaveStatus != SAVE_OK) $this->displayError($this->lastSaveStatus);
   ?>
      <form id="frm_item_editor" method="post" action="index.php?app=<?php echo $this->app_name; ?>&action=saveitem" autocomplete="off"  enctype="multipart/form-data" >
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
        <input type="hidden" name="category_id" value="<?php echo $item['category_id']; ?>" />
        <div class="gekko_editor_main">
          <label><br>
            <?php echo TXT_USERNAME; ?><br>
            <input name="username" type="text" value="<?php echo $item['username']; ?>" class="gekko_editor_input" />
          </label>
          <br />
          <label><?php echo TXT_PASSWORD; ?><br>
            <input name="password" type="password" value="" class="gekko_editor_input <?php if ($id=='new') echo 'required'; ?>" />
          </label>
          <br />
          <label><?php echo USERS_EMAIL_ADDRESS; ?> <br>
            <input name="email_address" type="text" value="<?php echo $item['email_address']; ?>" class="gekko_editor_input required validate-email" />
          </label>
          <br />
          <label><?php echo USERS_FIRST_NAME; ?> <br>
            <input name="firstname" type="text" value="<?php echo $item['firstname']; ?>" class="gekko_editor_input" />
          </label>
          <br />
          <label><?php echo USERS_LAST_NAME; ?> <br>
            <input name="lastname" type="text" value="<?php echo $item['lastname']; ?>" class="gekko_editor_input" />
          </label>
          <label>
            <input name="status" type="checkbox" value="1" <?php if ($item['status']==1 || $item['id']=='new') echo 'checked'; if ($item['id']==1) echo ' readonly '; ?>/>
            <?php echo TXT_ACTIVE; ?></label>
          <br />
          <br />
          <?php editor_button_save(); ?>
          <?php editor_button_cancel(true); ?>
        </div>
      </form>
      
      </div>

        <div id="tab2">
        <?php if ($id != 'new'): ?>
        	<p>Please choose one or more user groups that you would like to associate with this user:</p>
        	<div id="gekko_multiple_categories_checkboxes"></div>
        <?php else: ?>
        <p>Since this is a new item, only the current category will be saved for this item. You may choose multiple categories once the item has been saved.</p>
        <?php endif; ?>    
        </div>
    </div>
      
    </div>
  </div>
</div>
<script type="text/javascript" >
(function() {
    var tabView = new YAHOO.widget.TabView('gekko_dualpane_editor_main_content');
})();
</script>
    

