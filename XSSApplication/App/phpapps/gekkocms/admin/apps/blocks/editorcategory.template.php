<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

?>
<?php INIT_REGULAR_EDITOR(); ?>
  <div id="gekko_editor_main">
    <div id="gekko_editor_main_editor">
     <h3><?php echo BLOCKS_POSITION_EDITOR; ?></h3>
   <?php 
//   if ($this->lastSaveStatus != SAVE_OK) echo 'Error';
   ?>
<form method="post" action="index.php?app=<?php echo $this->app_name ?>&action=savecategory" id="editor" name="documentEditor"  enctype="multipart/form-data">
<?php displayFormSecretTokenHiddenField(); /* This particular module has CSRF Protection */ ?>
  <input type="hidden" name="cid" id="cid" value="<?php echo $category['cid']; ?>" />
      
         <label><?php echo BLOCKS_BLOCK_CATEGORY_NAME; ?><br>
          <input name="title" id='pagetitle' class="gekko_editor_input required" type="text" value="<?php echo $category['title']; ?>"  />
        </label>
        <br />
        <br />
        <br /><br />
        <?php editor_button_save(); ?>
        <?php editor_button_cancel(true); ?>
 </form>

    </div>
  </div>
