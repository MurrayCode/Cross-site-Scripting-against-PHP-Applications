<!--
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="<?php echo SITE_HTTPBASE; ?>/admin/templates/babygekko/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITE_HTTPBASE; ?>/admin/templates/babygekko/yui.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="<?php echo SITE_HTTPBASE; ?>/admin/templates/babygekko/images/favicon.ico" />
<?php echo JAVASCRIPT ('/js/tiny_mce/tiny_mce_popup.js'); ?>
<!--
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko. 
// This content management system is coded by Prana.
// This is a free software, do not remove this copyright.
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
-->
<?php displayHeader(); ?>
<?php echo JAVASCRIPT_YUI2_COMBO(); ?>

</head>
<body>
<div id="header"> </div>
<div id="gekko_admin_sidebar">
  <h3><?php echo BBGKFM_TITLE; ?></h3>
  <div id="gekko_admin_sidebar_content"></div>
</div>
<!-- end sidebar -->
<div id="gekko_admin_main">
  <div class="gekko_admin_panel">
    <form id="gekko_admin_searchform" name"gekko_admin_searchform" method="GET" action="#" onsubmit="return false" >
         <?php toolbar_button('button_new_folder','new_folder',TXT_NEW_FOLDER); ?>
	  <?php toolbar_button('button_delete','button_delete',TXT_DELETE,'#',TXT_DELETE_HINT); ?>       
       <?php toolbar_button('button_file_upload','go-top',TXT_UPLOAD); ?>
      <?php toolbar_button('config','config',TXT_CONFIGURATION,"index.php?app={$this->app_name}&action=editconfig"); ?>
	<?php /* TODO 	<input name="searchword" id="searchbox" alt="Search" type="text" size="20" value="search..."  onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value=='search...') this.value='';" /> */ ?>
    </form>
    
    
    <p id="gekko_admin_current_path"></p>
    <input type="hidden" id="gekko_admin_path_info">
  </div>
  <div class="clearboth"></div>
     <div id="upload_warning" style="display:none">
         <img src="<?php echo SITE_HTTPBASE; ?>/images/default/busywait1.gif" border="0" align="left" /><h3><?php echo BBGKFM_NOTICE_DONT_REFRESH; ?></h3>
     </div>
  <div class="clearboth"></div>
  
  <div id="gekko_admin_main_content"> </div>
  
  <!-- new folder -->
  <div id="new_folder_dialog" class="yui-pe-content">
    <div class="hd"><?php echo BBGKFM_NEW_FOLDER; ?></div>
    <div class="bd">
      <form method="POST" action="index.php?app=<?php echo $this->app_name ?>&action=newfolder" enctype="multipart/form-data">
        <label for="foldername"><?php echo BBGKFM_FOLDER_NAME; ?>:</label>
        <input type="textbox" name="foldername" />
        <input type="hidden" name="newfolderstartpath" id="newfolderstartpath" />
      </form>
    </div>
  </div>
  <!-- file upload -->
  <div id="file_upload_dialog" class="yui-pe-content">
    <div class="hd"><?php echo BBGKFM_FILE_UPLOAD; ?></div>
    <div class="bd">
   <form method="POST" id="gekko_upload_form" action="index.php?app=<?php echo $this->app_name ?>&action=upload" enctype="multipart/form-data">
        <label for="foldername"><?php echo BBGKFM_SELECT_FILE; ?></label>
        <input type="file" id="fileselector" name="filedata[]" multiple="" onchange="javascript:gekko_app.updateFileListToUpload();" />
        <input type="hidden" name="fileuploadpath" id="fileuploadpath" />
      </form>
	<p><?php echo BBGKFM_NOTICE_IE_WARNING; ?></p>
    <ul id="filelist"></ul>
    </div>
  </div>

</div>
<div id="footer">
  <p><?php echo TXT_COPY; ?>right &copy; <a href="http://www.babygekko.com" target="_blank">Baby Gekko IT Consulting</a>. Baby Gekko v<?php echo GEKKO_VERSION; ?>.<br />
    Memory usage: <?php echo round(memory_get_usage()/1024.00); ?> kb. Peak memory usage: <?php echo round( memory_get_peak_usage()/1024.00); ?> kb. </p>
  <!-- end #footer -->
</div>
</body>
</html>