<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
	if (!defined('GEKKO_VERSION')) die();
	
$captchaurl = SITE_HTTPBASE.'/captcha.php';
global $HTMLHeader;
$HTMLHeader->JAVASCRIPT_YUI_MINIUTIL();
$HTMLHeader->JAVASCRIPT_GEKKO();
	
?>

<h1>Login</h1>
<p>Existing users, please authenticate yourself to login</p>
<form name="loginform" method="post" action="<?php echo $login_url; ?>" enctype="multipart/form-data">
  <input name="login" type="hidden" value="login" >
 <?php /*******/ displayFormSecretTokenHiddenField() ; /*******/ ?>
  <table>
    <tr>
      <td>Username:</td>
      <td><input id="login_username" type="text" name="username" class="required user_form_input" value=""></td>
    </tr>
    <tr>
      <td>Password:</td>
      <td><input id="login_password" type="password" name="password" class="required user_form_input" value=""></td>
    </tr>
    <tr>
      <td></td>
      <td><label class="rememberlabel">
          <input name="remember" type="checkbox" id="chkremember" value="9999999" />
          Remember Me</label></td>
    </tr>
    <?php $invalid_retries_max = $this->getConfig('int_number_of_login_retry_before_captcha'); if ($invalid_retries_max == 0) $invalid_retries_max = 9999; ?>
    <?php if ($_SESSION['invalid_password_retry'] > $invalid_retries_max): ?>
     <tr>
      <td></td>
      <td><p><img src="<?php echo $captchaurl;?>" alt="Captcha" id="siimage" style="padding-right: 5px; border: 0" /></p>
        <p>Please type the random text above to verify that you are not a robot:
          <input name="verification_code" class="required" id='verification_code' type="text" value="<?php echo $_POST['verification_code']; ?>"  />
        </p></td>
    </tr>
    <?php endif; ?>
    <tr>
      <td></td>
      <td><button type="submit" name="submit" class="standard_form_button" value="Submit"><img src="<?php echo SITE_HTTPBASE; ?>/images/icons/ok.png" alt="OK" border="0" align="absmiddle" />Submit</button></td>
    </tr>
  </table>
</form>
<div class="loginform_infotext">
  <p>Or choose from the following action:</p>
  <ul>
    <li><a href="<?php echo $this->createFriendlyURL("action=forgotpassword"); ?>"><img alt="Forgot Password" src="<?php echo SITE_HTTPBASE; ?>/images/icons/blue_arrow_right.png" width="24" height="24" border="0" align="absmiddle" /> I forgot my password</a></li>
<?php if ($this->getConfig('chk_enable_registration')): ?>  
    
    <li><a href="<?php echo $this->createFriendlyURL("action=register"); ?>"><img alt="Create a new account" src="<?php echo SITE_HTTPBASE; ?>/images/icons/blue_arrow_right.png" width="24" height="24" border="0" align="absmiddle" /></a><a href="<?php echo $this->createFriendlyURL("action=register"); ?>"> I don't have an account yet and would like to create an account</a></li>
 <?php endif; ?>
    
  </ul>
</div>
