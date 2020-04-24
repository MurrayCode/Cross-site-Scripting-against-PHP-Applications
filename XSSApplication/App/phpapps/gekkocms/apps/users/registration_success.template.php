<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
	if (!defined('GEKKO_VERSION')) die();
?>
<H1 class="page_title">Registration Result</H1>
<div class="page_content">
  <p>Thank you, you have been registered. A copy of confirmation has
    been sent to your e-mail address.</p>
  <p>Please print this information for your records. <a href="<?php echo $this->createFriendlyURL('action=login'); ?>">Click here to continue</a>.</p>
  <br />
  <p>You can login any time at <a href="<?php echo SITE_URL; ?>"><?php echo SITE_URL; ?></a> with the following credentials:<br />
    Username:
    <?php echo $datavalues['username']; ?>
    <br />
    Password:
    <?php echo $plaintextpasswd; ?>
  </p>
  <br />
  <br />
</div>
