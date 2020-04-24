<?php 
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
	if (!defined('GEKKO_VERSION')) die();

global $gekko_current_user;

if ($gekko_current_user->authenticated() === false): 
?>

<div id="loginbox">
<h3>Login</h3>
  <form name="loginform" method="post" action="<?php echo $login_url; ?>" enctype="multipart/form-data" >
  <?php /*******/ displayFormSecretTokenHiddenField() ; /*******/ ?>
    <input name="login" type="hidden" value="login" />
    <input id="login_username" type="text" name="username" class="loginboxtxt" value="username..." onblur="if(this.value=='') this.value='username...';" onfocus="if(this.value=='username...') this.value='';" />
    &nbsp;
    <input id="login_password" type="password" name="password" class="loginboxtxt" value="password..." onblur="if(this.value=='') { this.type='text';this.value='password...'};" onfocus="if(this.value=='password...') {this.value='';this.type='password';};" />
    &nbsp;<label class="rememberlabel">
          <input name="remember" type="checkbox" id="chkremember" value="9999999" />
          Remember Me</label>
    <button type="submit" name="submit" class="standard_form_button" value="Submit"><img src="<?php echo SITE_HTTPBASE; ?>/images/icons/ok.png" alt="OK" border="0" align="absmiddle" />Submit</button>
  </form>  

  
</div>
<?php else: ?>
<div id="loginbox">
  <p>You are logged in as <a href="<?php echo $gekko_current_user->createFriendlyURL(''); ?>"><?php echo $_SESSION['username']; ?></a></p>
  <p><a href="<?php echo $gekko_current_user->createFriendlyURL('action=logout'); ?>">Log Out</a></p>
</div>
<?php endif; ?>
