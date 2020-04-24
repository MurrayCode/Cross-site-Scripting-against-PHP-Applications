<?php
// Version: 1.0; Login

$txt[37] = 'You should fill in a username.';
$txt[38] = 'You didn\'t enter your password.';
$txt[39] = 'Password incorrect';
$txt[98] = 'Choose username';
$txt[155] = 'Maintenance Mode';
$txt[245] = 'Registration successful';
$txt[431] = 'Success! You are now a member of the Forum.';
$txt[492] = 'and your password is';
$txt[500] = 'Please enter a valid email address, %s.';
$txt[517] = 'Required Information';
$txt[520] = 'Used only for identification by SMF. You can use special characters after logging in, by changing your displayed name in your profile.';
$txt[585] = 'I Agree';
$txt[586] = 'I Do Not Agree';
$txt[633] = 'Warning!';
$txt[634] = 'Only registered members are allowed to access this section.';
$txt[635] = 'Please login below or';
$txt[636] = 'register an account';
$txt[637] = 'with ' . $context['forum_name'] . '.';
$txt[700] = 'Welcome to';
$txt[701] = 'You may change it after you login by going to the profile page, or by visiting this page after you login:';
$txt[719] = 'Your username is: ';
$txt[730] = 'That email address (%s) is being used by a registered member already. If you feel this is a mistake, go to the login page and use the password reminder with that address.';

$txt['ban_register_prohibited'] = 'Sorry, you are not allowed to register on this forum';

$txt['activate_mail'] = 'In order to login, you need to activate your account first. Please follow this link to do so';
$txt['activate_account'] = 'Account activation';
$txt['activate_success'] = 'Your account has been successfully activated. You can now proceed to login.';
$txt['activate_not_completed1'] = 'Your email address needs to be validated before you can login.';
$txt['activate_not_completed2'] = 'Need another activation email?';
$txt['activate_after_registration'] = 'Thank you for registering. You will receive an email soon with a link to activate your account.';
$txt['invalid_userid'] = 'User does not exist';
$txt['invalid_activation_code'] = 'Invalid activation code';
$txt['invalid_activation_username'] = 'Username or email';
$txt['invalid_activation_new'] = 'If you registered with the wrong email address, type a new one and your password here.';
$txt['invalid_activation_new_email'] = 'New email address';
$txt['invalid_activation_password'] = 'Old password';
$txt['invalid_activation_resend'] = 'Resend activation code';
$txt['invalid_activation_known'] = 'If you already know your activation code, please type it here.';
$txt['invalid_activation_retry'] = 'Activation code';
$txt['invalid_activation_submit'] = 'Activate';

$txt['change_email_success'] = 'Your email address has been changed, and a new activation email has been sent to it.';
$txt['resend_email_success'] = 'A new activation email has successfully been sent.';
$txt['change_password'] = 'New Password Details';
$txt['change_password_1'] = 'Your login details at';
$txt['change_password_2'] = 'have been changed and your password reset. Below are your new login details.';

$txt['maintenance3'] = 'This board is in Maintenance Mode.';

$txt['register_agree'] = 'Please read/accept terms to submit form.';

$txt['approval_after_registration'] = 'Thank you for registering. The admin must approve your registration before you may begin to use your account, you will receive an email shortly advising you of the admin\'s decision.';
$txt['approval_email'] = 'Your account must first be approved before you may use your account.  You will receive another email shortly informing you of the admin\'s decision.';

$txt['admin_register'] = 'Registration of new member';
$txt['admin_register_desc'] = 'From here you can register new members into the forum, and if desired, email them their details.';
$txt['admin_register_username'] = 'New Username';
$txt['admin_register_email'] = 'Email Address';
$txt['admin_register_password'] = 'Password';
$txt['admin_register_username_desc'] = 'Username for the new member';
$txt['admin_register_email_desc'] = 'Email address of the member';
$txt['admin_register_password_desc'] = 'Password for new member';
$txt['admin_register_email_detail'] = 'Email new password to user';
$txt['admin_register_email_detail_desc'] = 'Email address required even if unchecked';
$txt['admin_register_email_activate'] = 'Require user to activate the account';
$txt['admin_register_group'] = 'Primary Membergroup';
$txt['admin_register_group_desc'] = 'Primary membergroup new member will belong to';
$txt['admin_register_group_none'] = '(no primary membergroup)';

$txt['admin_browse_approve'] = 'Members whose accounts are awaiting approval';
$txt['admin_browse_approve_desc'] = 'From here you can manage all members who are waiting to have their accounts approved.';
$txt['admin_browse_activate'] = 'Members whose accounts are awaiting activation';
$txt['admin_browse_activate_desc'] = 'This screen lists all the members who have still not activated their accounts at your forum.';
$txt['admin_browse_register_new'] = 'Register new member';
$txt['admin_browse_awaiting_approval'] = 'Awaiting Approval';
$txt['admin_browse_awaiting_activate'] = 'Awaiting Activation';
$txt['admin_browse_username'] = 'Username';
$txt['admin_browse_email'] = 'Email Address';
$txt['admin_browse_ip'] = 'IP Address';
$txt['admin_browse_registered'] = 'Registered';
$txt['admin_browse_id'] = 'ID';
$txt['admin_browse_with_selected'] = 'With Selected';
$txt['admin_browse_no_members'] = 'No Members Currently';
// Don't use entities in the below strings, except the main ones. (lt, gt, quot.)
$txt['admin_browse_warn'] = 'all selected members?';
$txt['admin_browse_w_approve'] = 'Approve';
$txt['admin_browse_w_activate'] = 'Activate';
$txt['admin_browse_w_delete'] = 'Delete';
$txt['admin_browse_w_reject'] = 'Reject';
$txt['admin_browse_w_remind'] = 'Remind';
$txt['admin_browse_w_email'] = 'and send email';

$txt['admin_approve_reject'] = 'Registration Rejected';
$txt['admin_approve_reject_desc'] = 'Regrettably, your application to join ' . $context['forum_name'] . ' has been rejected.';
$txt['admin_approve_delete'] = 'Account Deleted';
$txt['admin_approve_delete_desc'] = 'Your account on ' . $context['forum_name'] . ' has been deleted.  This may be because you never activated your account, in which case you should be able to register again.';
$txt['admin_approve_remind'] = 'Registration Reminder';
$txt['admin_approve_remind_desc'] = 'You still have not activated your account at';
$txt['admin_approve_remind_desc2'] = 'Please click the link below to activate your account:';
$txt['admin_approve_accept_desc'] = 'Your account has been activated manually by the admin and you can now login and post.';

$txt['admin_notify_subject'] = 'A new member has joined';
$txt['admin_notify_profile'] = '%s has just signed up as a new member of your forum. Click the link below to view their profile.';
$txt['admin_notify_approval'] = 'Before this member can begin posting they must first have their account approved. Click the link below to go to the approval screen.';

?>