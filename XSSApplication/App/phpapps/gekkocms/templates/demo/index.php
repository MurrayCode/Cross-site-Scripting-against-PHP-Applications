<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
	if (!defined('GEKKO_VERSION')) die();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="alternate" type="application/rss+xml" href="/blog/rss" title="<?php echo SITE_NAME.' RSS'; ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?> - <?php displayPageTitle(); ?></title>
<meta name="description" content="<?php displayPageMetaDescription(); ?>" /> 
<meta name="keywords" content="<?php displayPageMetaKeywords(); ?>" /> 
<link rel="stylesheet" type="text/css" href="<?php echo SITE_HTTPBASE; ?>/templates/demo/style.css" />
<?php displayHeader(); ?>
</head>

<body>

<div id="container">
  <div id="header">
  <a href="<?php echo SITE_URL.SITE_HTTPBASE; ?>">  <img src="<?php echo SITE_HTTPBASE; ?>/templates/demo/images/demo_logo.jpg" width="140" height="65" alt="Demo" align="left" id="demo_logo" /></a>
    <p id="site_title"><span class="bluetext">Baby Gekko </span> <span class="greytext">Template Demo</span></p>
    <span class="greentext" id="site_tag">content management system demo</span>
  <div id="header_right"><?php displayBlockByPosition('search'); ?></div>
  <br/><br />  
  
  <!-- end #header --></div>
  <div id="main">
    <div id="navbar">
      <?php displayBlockByPosition('top'); ?>
    </div>
    <div class="clearboth"></div>
<div id="template_left">
<?php displayBlockByPosition('left'); ?>
</div>
<div id="template_content"><?php displayPage(); ?>    <?php displayBlockByPosition('bottom'); ?> 
</div>  
<div class="clearboth"></div>
</div>
  <div id="footer">
  
  <div id="footer_left">

   </div>
   <div id="footer_right">
   <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ultrices metus vel ante pretium vitae dapibus enim mattis.</p>
   </div>
   <div class="clearboth"></div>
  <!-- end #footer --></div>
<!-- end #container --></div>
</body>
</html>
