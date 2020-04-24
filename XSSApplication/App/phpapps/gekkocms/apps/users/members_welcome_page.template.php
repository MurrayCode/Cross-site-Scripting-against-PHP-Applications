<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
	if (!defined('GEKKO_VERSION')) die();
?>
<?php $user = $gekko_current_user->getCurrentUserInfo(); ?>

<h1>Welcome back, <?php echo $user['username']; ?></h1>
<p>Please choose from the following action:</p>
<p><a href="<?php echo $this->createFriendlyURL('action=myprofile'); ?>">Edit Profile</a></p>
<p><a href="<?php echo $this->createFriendlyURL('action=logout'); ?>">Log Out</a></p>
