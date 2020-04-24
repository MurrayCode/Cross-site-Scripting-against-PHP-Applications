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
  <h3><?php echo MAIN_LAST_5_UPDATED_ARTICLES; ?></h3>
  <ul>
    <?php foreach ($contents as $content): ?>
    <li><a href="index.php?app=html&action=edititem&id=<?php echo $content['id']; ?>"><?php echo $content['title']; ?></a></li>
    <?php endforeach; ?>
  </ul>
</div>
<!-- end sidebar -->
<div id="gekko_admin_main">
  <div id="gekko_admin_main_content">
    <div id="gekko_dualpane_editor_main_content" class="yui-navset">
      <ul class="yui-nav">
        <li class="selected"><a href="#tab1"><em><?php echo SITE_NAME.' '.MAIN_CMS_ADMINISTRATION ?></em></a></li>
        <li><a href="#tab2"><em><?php echo MAIN_BBGK_NEWS; ?></em></a></li>
        <li><a href="#tab3"><em><?php echo MAIN_BBGK_LATEST_EXTTHEMES; ?></em></a></li>
      </ul>
      <div class="yui-content">
        <div id="tab1">
          <?php if ($this->errormsg) echo H2($this->errormsg); ?>
          <p><?php echo MAIN_USE_LINKS; ?>:</p>
          <ul class="gekko_admin_gfx_menu">
            <li><a href="index.php?app=html&action=edititem&id=1"><?php echo get_css_sprite_img(48,'home','',MAIN_EDIT_WELCOME_PAGE,'vertical-align:middle'); ?> <?php echo MAIN_EDIT_WELCOME_PAGE ?></a></li>
            <li><a href="index.php?app=menus"><?php echo get_css_sprite_img(48,'icon_edit_menu','',MAIN_ARRANGE_SITE_MENU,'vertical-align:middle'); ?> <?php echo MAIN_ARRANGE_SITE_MENU ?></a></li>
            <li><a href="index.php?app=html"><?php echo get_css_sprite_img(48,'icon_write_html','',MAIN_MANAGE_HTML_PAGE,'vertical-align:middle'); ?> <?php echo MAIN_MANAGE_HTML_PAGE ?></a></li>
            <li><a href="index.php?app=blocks"><?php echo get_css_sprite_img(48,'icon_blockedit','',MAIN_ORGANIZE_SITE_BLOCKS,'vertical-align:middle'); ?> <?php echo MAIN_ORGANIZE_SITE_BLOCKS ?></a></li>
            <li><a href="<?php echo "index.php?app={$this->app_name}&action=editconfig"; ?>"><?php echo get_css_sprite_img(48,'icon_wrench','',MAIN_CONFIGURE_ACCESS_PERMISSION,'vertical-align:middle'); ?> <?php echo MAIN_CONFIGURE_ACCESS_PERMISSION ?></a></li>
          </ul>
        </div>
        <div id="tab2"> 
        
        </div>
        <div id="tab3">
        
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
