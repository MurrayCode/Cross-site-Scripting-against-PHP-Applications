<!doctype html>
<head>
<meta charset="UTF-8">
<title><?php echo SITE_NAME;?> - <?php displayPageTitle(); ?>
</title>
<meta name="description" content="<?php displayPageMetaDescription(); ?>" />
<meta name="keywords" content="<?php displayPageMetaKeywords(); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<link rel="icon" href="<?php echo SITE_HTTPBASE; ?>/favicon.ico" type="image/x-icon"/>
<?php displayHeader(); ?>
<!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<link rel="shortcut icon" href="<?php echo SITE_HTTPBASE; ?>/favicon.ico" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_HTTPBASE; ?>/templates/html5demo/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_HTTPBASE; ?>/templates/html5demo/editor.css" />
</head>
<body>

<!--start container-->
<div id="container"> 
  <!--start header-->
  <header> 
    <!--change this with your logo --> 
    <a href="<?php echo SITE_URL.SITE_HTTPBASE; ?>" id="your_logo"><img src="<?php echo SITE_HTTPBASE; ?>/templates/html5demo/images/sample_logo.jpg" alt="Your Logo"/></a> 
    <!--end logo--> 
    
    <!--navigation menu-->
    <nav>
      <?php displayBlockByPosition('top'); ?>
    </nav>
    <!--end navigation menu--> 
    <!--end header--> 
  </header>
  <?php displayBlockByPosition('customblurb'); ?>
  
  <!--start holder-->
  
  <div class="contentholder">
    <section class="page_column">
      <?php displayPage(); ?>
    </section>
    <aside class="sidebar_column">
      <?php displayBlockByPosition('left'); ?>
    </aside>
  </div>
  <!--end holder--> 
  
</div>
<!--end container--> 

<!--start footer-->
<footer>
  <div class="footer_container">
    <div id="footer_left"> &copy; 2011 Your Site Name Here - <?php echo SAFE_HTML(SITE_NAME); ?> </div>
    <div id="footer_right">
      <?php displayBlockByPosition('bottom'); ?>
    </div>
  </div>
</footer>
<!--end footer-->
</body>
</html>