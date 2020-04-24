<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
	if (!defined('GEKKO_VERSION')) die();
?>
<h1 class='mainheading'>User Registration </h1>
<?php
global $HTMLHeader;

$HTMLHeader->JAVASCRIPT_YUI_MINIUTIL();
$HTMLHeader->JAVASCRIPT_GEKKO();

$captchaurl = SITE_HTTPBASE.'/captcha.php';
if ($registration_error_string) echo $registration_error_string; ?>

<form method="post" action="<?php echo $this->createFriendlyURL('action=register');?>" name="registeruser" id="registeruser" enctype="multipart/form-data" autocomplete="off">
<?php /*******/ displayFormSecretTokenHiddenField() ; /*******/ ?>
  <fieldset>
    <legend>Login Information</legend>
    <table width="100%"  border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td width="200" align="right">Desired Username</td>
        <td>
          <input id="username" name="username" type="text" class="validate-alpha" value="<?php echo $_POST['username']; ?>"/>
          </td>
      </tr>
    
      <tr>
        <td width="200" align="right">E-mail address </td>
        <td>
          <input id="email_address" name="email_address" type="text" class="validate-email required" value="<?php echo $_POST['email_address']; ?>"/>
          </td>
      </tr>
      <tr>
        <td width="200" align="right">Password</td>
        <td>
          <input id="password" name="password" type="password" class="required" value="<?php echo $_POST['password']; ?>"/>
          </td>
      </tr>
      <tr>
        <td width="200" align="right">Verify Password </td>
        <td>
          <input id="password_verify" name="password_verify" type="password" class="required" value="<?php echo $_POST['password_verify']; ?>"/>
          </td>
      </tr>
    </table>
  </fieldset>
  <fieldset>
    <legend>Name</legend>
    <table width="100%"  border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td width="200" align="right">First Name </td>
        <td>
          <input id="firstname" name="firstname" type="text" class="required input_register_txt" value="<?php echo $_POST['firstname']; ?>"/>
          </td>
      </tr>
      <tr>
        <td width="200" align="right">Last Name</td>
        <td>
          <input id="lastname" name="lastname" type="text" class="required input_register_txt" value="<?php echo $_POST['lastname']; ?>"/>
          </td>
      </tr>
    </table>
  </fieldset>
  <br />
  <div align="center" style="margin-left:-20px">
  <?php if ($enable_captcha_user_registration): ?>
 <p><img src="<?php echo $captchaurl;?>" alt="Captcha" id="siimage" style="padding-right: 5px; border: 0" /></p>
 <p>Please type the random text above to verify that you are not a robot:
          <input name="verification_code" class="required" id='verification_code' type="text" value="<?php echo $_POST['verification_code']; ?>"  />
        </p> 
        <?php endif; ?>
<button type="submit" name="submit" class="standard_form_button" value="Submit"><img src="<?php echo SITE_HTTPBASE; ?>/images/icons/ok.png" alt="Submit" border="0" align="absmiddle" />Submit</button>
  </div>
</form>

<div class="loginform_infotext">
<p>Or choose from the following action:</p>
<ul>
  <li><a href="<?php echo $this->createFriendlyURL("action=register"); ?>"><img src="<?php echo SITE_HTTPBASE; ?>/images/icons/blue_arrow_right.png" alt="Action" width="24" height="24" border="0" align="absmiddle" /></a><a href="<?php echo $this->createFriendlyURL("action=login"); ?>"> I already have an account and would like to sign in</a></li>
  <li><a href="<?php echo $this->createFriendlyURL("action=forgotpassword"); ?>"><img src="<?php echo SITE_HTTPBASE; ?>/images/icons/blue_arrow_right.png" alt="" width="24" height="24" border="0" align="absmiddle" /> I already have an accoutn but I forgot my password</a></li>
</ul>
</div>