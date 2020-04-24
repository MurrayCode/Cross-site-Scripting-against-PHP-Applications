<!--

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

-->
<?php INIT_REGULAR_EDITOR(); ?>
<?php $filtered_app_name = convert_into_sef_friendly_title($_GET['appname'])?>

<div id="gekko_admin_sidebar"> </div>
<!-- end sidebar -->
<div id="gekko_admin_main">
  <h3><?php echo $filtered_app_name; ?></h3>
  <?php if (!isset($_POST['sure'])): ?>
  <form method="post" action="index.php?app=<?php echo $this->app_name; ?>&action=uninstall" enctype="multipart/form-data" >
    <input type="hidden" name="appname" value="<?php echo $filtered_app_name; ?>" />
    <label>
      <input type="checkbox" name="sure" onclick="javascript:document.getElementById('ok_uninstall_button').disabled=!this.checked;" />
      Are you sure you want to uninstall this application?</label>
    <br/>
    <label>
      <input type="checkbox" name="database" />
      Delete all the data as well? You will not be able to restore the existing data later if you change your mind.</label>
    <br/>
    <label>
      <input type="checkbox" name="everything" />
      Delete everything in the directory?</label>
    <br/>
    <br/>
 <?php uninstall_button_ok(); uninstall_button_cancel(); // just a shortcut ?>
  </form>
  <?php else: ?>
  <?php if ($uninstall_result===true): ?>
  <p>This application has been uninstalled. Please <a href="index.php">click here</a> to continue.</p>
  <?php else: ?>
  <p>Uninstall process was not successful</p>
  <?php endif; ?>
  <?php endif; ?>
  <p id="gekko_admin_current_path"></p>
  <input type="hidden" id="gekko_admin_path_info">
</div>
</div>
