<?php INIT_TEXTAREA_EDITOR(); ?>
<?php 
 global $gekko_db;
 $menus_in_this_category  = $this->app->getItemsByCategoryID($category['cid']);

?>

<div class="gekko_editor">
  <form method="post" action="index.php?app=<?php echo $this->app_name; ?>&action=savecategory" enctype="multipart/form-data" >
    <div id="gekko_dualpane_editor_sidebar">
      <h3><?php echo MENUS_BLOCK_EDITOR; ?></h3>
      <div id="gekko_dualpane_editor_sidebar_content">
      <?php if ($menus_in_this_category): ?>
      <h4><?php echo MENUS_LIST_IN_CATEGORY; ?></h4>
        <ul>
          <?php foreach ($menus_in_this_category as $menu): ?>
          <li> <a href="index.php?app=<?php echo $this->app_name; ?>&action=edititem&id=<?php echo $menu['id']; ?>"><?php echo $menu['title']; ?></a></li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
      </div>
    </div>
    <!-- end sidebar -->
    <div id="gekko_dualpane_editor_main">
      <div id="gekko_dualpane_editor_content">
        <input type="hidden" name="cid" id="cid" value="<?php echo $category['cid']; ?>" />
        <label><?php echo MENUS_MENU_BLOCK_NAME; ?><br />
          <input name="title" id='pagetitle'  type="text" value="<?php echo $category['title']; ?>" size="49" maxlength="254" class="gekko_editor_title_input required"  />
        </label>
        <label>
          <input name="status" type="checkbox" value="1"<?php if ($category['status']==1 || $category['cid']=='new') echo 'checked'; ?> />
          <?php echo TXT_ACTIVE; ?></label>
        <br /><br />
         <?php editor_button_save(); ?>
        <?php editor_button_cancel(false); ?>
      </div>
    </div>
  </form>
</div>
