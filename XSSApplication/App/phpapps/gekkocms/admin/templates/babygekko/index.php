<?php
	$third_party_apps = get3rdPartyApplicationList();
	global $gekko_current_admin_user;
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="<?php echo SITE_HTTPBASE; ?>/admin/templates/babygekko/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITE_HTTPBASE; ?>/admin/templates/babygekko/yui.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITE_HTTPBASE; ?>/admin/templates/babygekko/custom.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="<?php echo SITE_HTTPBASE; ?>/admin/templates/babygekko/images/favicon.ico" />
<!--
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko. 
// This content management system is coded by Prana.
// This is a free software, do not remove this copyright.
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
-->
<?php displayHeader(); ?>
</head>
<body>
<div id="container">
  <div id="header">
    <div id="headerlogo"><a href="index.php"><img class="img_gekko_sprite" id="img_gekko_headerlogo" src="<?php echo TRANS_SPRITE_IMAGE; ?>" alt="Main" title="Main" /></a></div>
    <div id="headercontent">
      <div id="headermenu">
        <!-- menu -->
        <ul id="gekkonav">
          <li><a href="index.php?app=menus">Menus</a></li>
          <li><a href="index.php?app=blog">Blog</a></li>
          <li><a href="index.php?app=html">Web Pages</a></li>
          <li>
          	<a href="index.php?app=applications">Applications</a>
          	<?php if ($third_party_apps): ?>
          	<ul>
	          	<?php foreach ($third_party_apps as $app): ?>
	          	 <li><a href="index.php?app=<?php echo $app['title']; ?>"><?php echo $app['title']; ?></a></li>
	          	<?php endforeach; ?>
          	</ul>
          	<?php endif; ?>
          </li>
          <li><a href="index.php?app=blocks">Blocks</a></li>
          <li><a href="index.php?app=filters">Filters</a></li> 
          <li><a href="index.php?app=<?php echo DEFAULT_USER_CLASS; ?>">Users</a></li>
          <li><a href="index.php?app=settings">Settings</a>
          <ul> 
            <li><a href="index.php?app=settings">Site Settings</a></li>
          	<li><a href="index.php?app=templates">Templates</a></li>
          	
          </ul>
          </li>
          <li><a href="index.php?app=help">Help</a></li>
          <li><a href="index.php?logout=1">Logout</a></li>
        </ul>
        <!-- end menu -->
      </div>
      <div id="headermenuright"></div>
    </div>
    <?php displayAppModuleToolbar(); ?>
    <!-- end #header -->
  </div>
  <div class="clearboth"></div>
  <div id="admin_main" >
    <?php displayPage(); ?>
  </div>
  <div class="clearboth"></div>
  <div id="footer">
    <?php checkIfInstallationDirectoryStillExists(); ?>
  
    <p>Logged in as : <?php echo $gekko_current_admin_user->getCurrentUserName(); ?>. Copyright &copy; <a href="http://www.babygekko.com" target="_blank">Baby Gekko, Inc.</a>. Baby Gekko v<?php echo GEKKO_VERSION; ?>.<br />
    Memory usage: <?php echo round(memory_get_usage()/1024.00); ?> kb. Peak memory usage: <?php echo round( memory_get_peak_usage()/1024.00); ?> kb. </p>
    <!-- end #footer -->
  </div>
  <!-- end #container -->
</div>
</body>
</html>