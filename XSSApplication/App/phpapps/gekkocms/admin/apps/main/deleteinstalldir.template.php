<!--
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
-->
<?php global $gekko_current_admin_user; ?>

<div id="gekko_admin_sidebar"> 
</div>
<!-- end sidebar -->
<div id="gekko_admin_main">
<div id="gekko_admin_main_content">

    <h3><?php echo SITE_NAME.' '.MAIN_CMS_ADMINISTRATION ?></h3>
    <?php if ($this->errormsg) echo H3($this->errormsg,'','general-error'); ?>
    <?php if (is_dir(SITE_PATH.'/install')): ?>
    <h2>Delete Installation Directory?</h2>
   <form id="gekko_admin_deleteinstalldir" method="POST" action="index.php?app=<?php echo $this->app_name;?>&action=deleteinstalldir">
   <input type="hidden" name="deleteinstalldir" value="1" />
    <?php editor_button_ok(); editor_button_cancel();  ?>
    </form>
    <?php elseif (!$result): ?>
    <h3>Installation directory no longer exists or has been renamed or deleted. You're good to go!</h3>
    <?php endif; ?>
  </div>
</div>
