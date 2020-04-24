<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

	$uninstall_location = SITE_URL.SITE_HTTPBASE.'/admin/index.php?app='.$this->app_name.'&action=uninstall&name='.$item['title']; 
?>

<div class="gekko_editor">
  <form id="frm_item_editor" method="post" action="index.php?app=<?php echo $this->app_name; ?>&action=saveitem" enctype="multipart/form-data" >
    <?php global $gekko_config; ?>
    <div id="gekko_dualpane_editor_sidebar">
      <div id="gekko_dualpane_editor_content">
   <p><a href="<?php echo $uninstall_location; ?>"><?php echo get_css_sprite_img(16,'uninstall'); ?> <?php echo TEMPLATES_UNINSTALL; ?></a></p>
        
      </div>
    </div>
    <!-- end sidebar -->
    <div id="gekko_dualpane_editor_main">
      <div id="gekko_dualpane_editor_content">
        <h3><?php echo TEMPLATES_PREVIEW; ?></h3>
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
        <div class="gekko_editor_main">
        <?php
        	if (file_exists($authorfile)): 
            include_once ($authorfile);
            echo IMG(SITE_HTTPBASE.$preview,TEMPLATES_PREVIEW); 
            ?>
         
          <br />
          <table width="300" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="80"><strong><?php echo TEMPLATES_TXT_AUTHOR; ?></strong></td>
              <td><?php echo TEMPLATE_AUTHOR; ?></td>
            </tr>
            <tr>
              <td width="80"><strong><?php echo TEMPLATES_TXT_URL; ?></strong></td>
              <td><?php echo A(TEMPLATE_URL, TEMPLATE_URL); ?></td>
            </tr>
            <tr>
              <td width="80"><strong><?php echo TEMPLATES_TXT_SUPPORT; ?></strong></td>
              <td><?php echo TEMPLATE_SUPPORT; ?></td>
            </tr>
            <tr>
              <td width="80"><strong><?php echo TEMPLATES_TXT_PURPOSE; ?></strong></td>
              <td><?php echo TEMPLATE_PURPOSE; ?></td>
            </tr>
          </table>
       <?php else: ?>
       <?php echo P(TEMPLATES_NO_INFORMATION_IS_AVAILABLE_FOR_THIS_TEMPLATE); ?>
       <?php endif; ?>
<?php editor_button_cancel(true); ?>
        </div>
      </div>
    </div>
  </form>
</div>
