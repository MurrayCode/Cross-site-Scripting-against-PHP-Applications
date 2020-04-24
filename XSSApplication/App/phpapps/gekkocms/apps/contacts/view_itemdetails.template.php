<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

//$captchaurl = $this->createFriendlyURL("action=captcha&sid=".md5(time()));
if (!defined('GEKKO_VERSION')) die();
	
$captchaurl = SITE_HTTPBASE.'/captcha.php';
$location = array('city','state','province','postal','country');
foreach ($location as $element) if ($item[$element]) $place[] = $item[$element];
if ($place) $place_str = implode(', ', $place); else $place_str =  '';
global $HTMLHeader;
$HTMLHeader->JAVASCRIPT_YUI_MINIUTIL();
$HTMLHeader->JAVASCRIPT_GEKKO();
?>

<h1><?php echo $item['title']; ?></h1>
<div class="contactform">
<?php if ($item['contact_person']): ?>
<div class="contacts_info_label"><img class="contact-icons" alt="Contact Person" src="<?php echo SITE_HTTPBASE; ?>/images/contacts/contact_person.png"/><?php echo $item['contact_person']; ?></div>
<?php endif; ?>

<?php if ($item['branch']): ?>
<div class="contacts_info_label"><img class="contact-icons" alt="Building or Branch" src="<?php echo SITE_HTTPBASE; ?>/images/contacts/branch.png"/><?php echo $item['branch']; ?></div>
<?php endif; ?>
<?php if ($item['street']): ?>
<div class="contacts_info_label"><img class="contact-icons" alt="Street" src="<?php echo SITE_HTTPBASE; ?>/images/contacts/street.png"/><?php echo $item['street']; ?></div>
<?php endif; ?>
<?php if ($place_str): ?>
<div class="contacts_info_label"><img class="contact-icons" alt="Place" src="<?php echo SITE_HTTPBASE; ?>/images/contacts/place.png"/><?php echo $place_str; ?></div>
<?php endif; ?>
<?php if ($item['tollfree']): ?>
<div class="contacts_info_label"><img class="contact-icons" alt="Toll-free" src="<?php echo SITE_HTTPBASE; ?>/images/contacts/tollfree.png"/><?php echo $item['tollfree']; ?></div>
<?php endif; ?>
<?php if ($item['phone']): ?>
<div class="contacts_info_label"><img class="contact-icons" alt="Phone" src="<?php echo SITE_HTTPBASE; ?>/images/contacts/phone.png"/><?php echo $item['phone']; ?></div>
<?php endif; ?>
<?php if ($item['fax']): ?>
<div class="contacts_info_label"><img class="contact-icons" alt="Fax" src="<?php echo SITE_HTTPBASE; ?>/images/contacts/fax.png"/><?php echo $item['fax']; ?></div>
<?php endif; ?>
<?php if ($item['mobile']): ?>
<div class="contacts_info_label"><img class="contact-icons" alt="Mobile" src="<?php echo SITE_HTTPBASE; ?>/images/contacts/mobile.png"/><?php echo $item['mobile']; ?></div>
<?php endif; ?>
<?php if ($item['email'] && $item['display_email']): ?>
<div class="contacts_info_label"><img class="contact-icons" alt="Email" src="<?php echo SITE_HTTPBASE; ?>/images/contacts/email.png"/>
  <?php if ($item['display_email']) echo $item['email']; ?>
</div>
<?php endif; ?>
<?php if ($item['additional_info']): ?>
<?php  echo $item['additional_info']; ?>
<?php endif; ?>
<?php 

$codecheck = false;

if ($this->enable_captcha)
{
	$securimage = new securimage();
	$securimage->session_name = GEKKO_SESSION_NAME;
	if ($_POST['verification_code']) $codecheck = $securimage->check(cleanInput($_POST['verification_code']));
	//echo $result;
} else $codecheck = true;
 if ($_POST['name'] && $_POST['email'] && $_POST['message'] && $_POST['sendmail'] && $_POST['contact_id'] && ($codecheck !== false)):

	$this->sendMessageToContact($_POST['contact_id'],  $_POST['phone'],  $_POST['email'], $_POST['subject'], $_POST['message']);

?>
<?php else: ?>
<br />
<p>Please use this form below to contact us </p>
<?php if ($this->enable_captcha && $_POST['sendmail'] && !$codecheck)
{
	 echo H5('You have entered an invalid code. The correct code is: '.$codecheck,'','validation-failed'); 
}
?>
<form class="contact_form" method="post" action="<?php echo $this->createFriendlyURL("action=viewitem&id={$item['id']}"); ?>" id="editor" name="documentEditor"  enctype="multipart/form-data">
  <input type="hidden" name="contact_id" value="<?php echo $item ['id']; ?>" />
  <div class="contacts_form_label">Name</div>
  <div class="contacts_form_field">
    <input name="name" size="50"  id='contacts_name' class="required" type="text" value="<?php echo $_POST['name']; ?>"  />
  </div>
  <div class="contacts_form_label">E-mail</div>
  <div class="contacts_form_field">
    <input name="email" class="validate-email" size="50"  id='email' type="text" value="<?php echo $_POST['email']; ?>"  />
  </div>
  <div class="contacts_form_label">Phone</div>
  <div class="contacts_form_field">
    <input name="phone" size="50" id='phone' type="text" value="<?php echo $_POST['phone']; ?>"  />
  </div>
  <div class="contacts_form_label">Subject</div>
  <div class="contacts_form_field">
    <input name="subject" class="required" size="50" id='subject' type="text" value="<?php echo $_POST['subject']; ?>"  />
  </div>
  <div class="contacts_form_label">Message</div>
  <div class="contacts_form_field">
    <textarea name="message" class="required" cols="50" rows="10" id="message"><?php echo $_POST['message']; ?></textarea>
  </div>
  <div class="contacts_form_label">&nbsp;</div>
  <?php if ($this->enable_captcha): ?>
  <div class="contacts_form_field">
    <p><img src="<?php echo $captchaurl;?>" alt="Captcha" id="siimage" style="border: 0" /></p>
    <p>Please type the random text above to verify that you are not a robot.
      <input name="verification_code" size="50" class="required" id='verification_code' type="text" value="<?php echo $_POST['verification_code']; ?>"  />
    </p>
  </div>
  <?php endif; ?>
  <div class="contacts_form_label">&nbsp;</div>
  <div class="contacts_form_field">
    <button type="submit" name="sendmail" id="submitbutton" value="Submit">
   Send
    </button>
    <button  type="reset" name="reset" id="reset" value="reset">
    Reset
    </button>
  </div>
</form>
<?php endif; ?>
</div>