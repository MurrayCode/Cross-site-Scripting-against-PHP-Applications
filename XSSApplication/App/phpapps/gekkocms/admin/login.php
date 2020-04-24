<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
if (!defined('GEKKO_VERSION')) 
{
	header('Location: http://'.$_SERVER['SERVER_NAME'].dirname( $_SERVER['PHP_SELF']).'/');
	exit;
}

$admin_template_path = SITE_HTTPBASE."/admin/templates/".ADMIN_TEMPLATE;
include_once('../config.inc.php');
include_once('../connector.inc.php');
include_inc('util.inc.php');
if (FORCE_SSL_ADMIN_LOGIN == 1)
{
	$post_location = force_HTTPS_url().SITE_HTTPBASE.'/admin/';
}
else
	$post_location = $_SERVER['PHP_SELF'];
setFormSecretToken();
$_SESSION['admin_intention'] = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="<?php echo $admin_template_path; ?>/style.css" />
<title>Login to Gekko CMS Administration</title>
<meta name="description" content="BabyGekko CMS" /> 
<meta name="robots" content="noindex,nofollow">
</head>
<body OnLoad="document.gekko_form_admin_login.username.focus();">
<div id="bbgkwrapper">
  <div id="bbgkloginform">
  <?php if ($_SESSION['login_error']):?>
 <h3> <?php echo $_SESSION['login_error'];  $_SESSION['login_error'] = ''; ?></h3>
  <?php else: ?>
    <h3>Content Management Administration</h3>
    <?php endif; ?>
    <form id="gekko_form_admin_login" name="gekko_form_admin_login" method="post" action="<?php echo $post_location; ?>">
    <?php displayFormSecretTokenHiddenField();  /* CSRF Protection, not activated yet for backward compatibility. */  ?>
    
      <table cellspacing="3" border="0" cellpadding="0" width="100%">
        <tr>
          <td rowspan="2"><img class="img_gekko_sprite" id="img_gekko_bigleaf" src="<?php echo TRANS_SPRITE_IMAGE; ?>" alt="Login" width="140" height="122" align="right" /></td>
          <td><input type="text" class="bigtextbox" name="username" id="username" /></td>
        </tr>
        <tr>
          <td><input type="password" class="bigtextbox"  name="password" id="password" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><label class="rememberlabel">
              <input name="remember" type="checkbox" id="chkremember" value="9999999" />
              Remember Me</label>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="hidden" name="admin_login_form" value="1" />
            <button type="submit" name="login" id="loginbutton" value="login"><img src="<?php echo SITE_HTTPBASE; ?>/admin/images/arrow_right.png" border="0" align="absmiddle" />Login</button></td>
        </tr>
      </table>
    </form>
    
  </div>
</div>
<p align="center">&nbsp;</p>
</body>
</html>
<?php // endif; ?>
