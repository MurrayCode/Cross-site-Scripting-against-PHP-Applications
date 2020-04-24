<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
	if (!defined('GEKKO_VERSION')) die();
	
	global $HTMLHeader;
	
	$HTMLHeader->JAVASCRIPT_YUI_MINIUTIL();
	$HTMLHeader->JAVASCRIPT_GEKKO();
	
?>
<h1>Edit Profile</h1>
<?php if ($editprofile_error_string) echo $editprofile_error_string; ?>
<?php if ($_SESSION['user_profile_update_status']) { echo $_SESSION['user_profile_update_status']; $_SESSION['user_profile_update_status']='';} ?>
<form method="post" action="<?php echo $this->createFriendlyURL('action=myprofile');?>" name="registeruser" id="registeruser" enctype="multipart/form-data" autocomplete="off">
  <fieldset>
    <legend>Profile</legend>
    <table width="100%"  border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td width="200" align="right"> Username</td>
        <td>
         <?php echo $userinfo['username']; ?>
          </td>
      </tr>
    
      <tr>
        <td width="200" align="right">E-mail address </td>
        <td>
          <input id="email_address" name="email_address" type="text" value="<?php echo $userinfo['email_address']; ?>"/>
          </td>
      </tr>
      <tr>
        <td align="right">First Name </td>
        <td><input id="firstname" name="firstname" type="text" class="input_register_txt" value="<?php echo $userinfo['firstname']; ?>"/></td>
      </tr>
      <tr>
        <td align="right">Last Name</td>
        <td><input id="lastname" name="lastname" type="text" class="input_register_txt" value="<?php echo $userinfo['lastname']; ?>"/></td>
      </tr>
    </table>
  </fieldset>
<p> * If you don't want to change your password, you can just leave the following fields blank</p>
  <fieldset>
    <legend>Change Password</legend>
    <table width="100%"  border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td align="right">Old Password</td>
        <td><input id="oldpassword" name="oldpassword" type="password" value=""/></td>
      </tr>
      <tr>
        <td width="200" align="right">New Password</td>
        <td><input id="newpassword" name="newpassword" type="password" value=""/></td>
      </tr>
      <tr>
        <td align="right">Verify New Password </td>
        <td><input id="newpassword_verify" name="newpassword_verify" type="password" value=""/></td>
      </tr>
    </table>
  </fieldset>
  <br />
  <div align="center" style="margin-left:-20px">
<button type="submit" name="submit" class="standard_form_button" value="Submit"><img src="<?php echo SITE_HTTPBASE; ?>/images/icons/ok.png" alt="Submit" border="0" align="absmiddle" />Submit</button>
<button type="reset" name="clear" class="standard_form_button" value="Clear" onclick="javascript:window.location='<?php echo SITE_URL.$this->createFriendlyURL(''); ?>';"><img src="<?php echo SITE_HTTPBASE; ?>/images/icons/cancel.png" alt="Cancel" border="0" align="absmiddle" />Cancel</button>
  </div>
</form>