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
<H1 class='mainheading'>Forgot your password?</H1>
<div class="webpage_content"> 
  <div class="whitebox"> 
    <div> 
      <div> 
        <div> 
<p>Please enter your first name, last name, and e-mail address. We will give
  you a new password and it will be sent to the email address registered
  on this site. </p>

 <form method="post" action="<?php echo $this->createFriendlyURL ('action=forgotpassword'); ?>"> 
<?php /*******/ displayFormSecretTokenHiddenField() ; /*******/ ?>
<table width="500" border="0" cellspacing="5" cellpadding="0">
	  
      <tr>
        <td nowrap><strong>*E-mail</strong></td>
        <td><input id="email" class="required" name="email" type="text" /></td>
      </tr>
      <tr>
        <td nowrap>&nbsp;</td>
        <td><p><img src="<?php echo $captchaurl;?>" alt="Captcha" id="siimage" style="padding-right: 5px; border: 0" /></p>
          <p>Please type the random text above to verify that you are not a robot:
            <input name="verification_code" class="required" id='verification_code' type="text" value="<?php echo $_POST['verification_code']; ?>"  />
            </p></td>
      </tr>
      <tr>
        <td nowrap>&nbsp;</td>
        <td><button type="submit" name="submit" class="standard_form_button" value="Submit"><img src="<?php echo SITE_HTTPBASE; ?>/images/icons/ok.png" alt="OK" border="0" align="absmiddle" />OK</button>
          <button type="reset" name="clear" class="standard_form_button" value="Clear" onclick="javascript:frontend_cancel_button(true);"><img src="<?php echo SITE_HTTPBASE; ?>/images/icons/cancel.png" alt="Cancel" border="0" align="absmiddle" />Cancel</button></td>
      </tr>
  </table>
<br />
  <div align="center"></div> 
</form> 
        </div> 
      </div> 
    </div> 
  </div> 
</div>
