<?php INIT_TEXTAREA_EDITOR(); ?>
<?php 
 global $gekko_db;
 $users_in_this_category  = $this->app->getItemsByCategoryID($category['cid']);

?>

<div class="gekko_editor">
  <form method="post" action="index.php?app=<?php echo $this->app_name ?>&action=savecategory" enctype="multipart/form-data" >
    <div id="gekko_dualpane_editor_sidebar">
      <h3><?php echo USER_CATEGORY_EDITOR; ?></h3>
      <div id="gekko_dualpane_editor_sidebar_content">
      <?php if ($users_in_this_category): ?>
      <h4><?php echo USER_LIST_OF_USERS_IN_THIS_CATEGORY; ?></h4>
        <ul>
          <?php foreach ($users_in_this_category as $user): ?>
          <li> <a href="index.php?app=users&action=edititem&id=<?php echo $user['id']; ?>"><?php echo $user['username']; ?></a></li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
      </div>
    </div>
    <!-- end sidebar -->
    <div id="gekko_dualpane_editor_main">
      <div id="gekko_dualpane_editor_content">
        <input type="hidden" name="cid" id="cid" value="<?php echo $category['cid']; ?>" />
        <label><?php echo USERS_GR0UP_NAME; ?><br />
          <input name="groupname" id='pagetitle'  type="text" value="<?php echo $category['groupname']; ?>" size="49" maxlength="254" class="required gekko_editor_title_input" 
		  <?php if ( $category['groupname'] == 'Administrators') echo 'readonly' ?> />
        </label>
        <label>
          <input name="status" type="checkbox" value="1"<?php if ($category['status']==1 || $category['cid']=='new') echo 'checked'; ?> />
          <?php echo TXT_ACTIVE; ?></label>
        <br /><br />
         <?php if ( $category['groupname'] != 'Administrators') editor_button_save(); ?>
        <?php editor_button_cancel(false); ?>
      </div>
    </div>
  </form>
</div>
