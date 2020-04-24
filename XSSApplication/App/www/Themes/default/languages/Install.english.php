<?php
// Version: 1.0; Install

$txt['smf_installer'] = 'SMF Installer';
$txt['installer_language'] = 'Language';
$txt['congratulations'] = 'Congratulations, the installation process is complete!';
$txt['congratulations_help'] = 'If at any time you need support, or SMF fails to work properly, please remember that <a href="http://www.simplemachines.org/community/index.php">help is available</a> if you need it.';
$txt['still_writable'] = 'Your installation directory is still writable.  It\'s a good idea to chmod it so that it is not writable for security reasons.';
$txt['delete_installer'] = 'Click here to delete this install.php file now. <i>(doesn\'t work on all servers.)</i>';
$txt['go_to_your_forum'] = 'Now you can see <a href="%s">your newly installed forum</a> and begin to use it.  You should first make sure you are logged in, after which you will be able to access the administration center.';
$txt['good_luck'] = 'Good luck!<br />Simple Machines';

$txt['user_refresh_install'] = 'Forum Refreshed';
$txt['user_refresh_install_desc'] = 'While installing, the installer found that (with the details you provided) one or more of the tables this installer might create already existed.<br />Any missing tables in your installation have been recreated with the default data, but no data was deleted from existing tables.';

$txt['default_topic_subject'] = 'Welcome to SMF!';
$txt['default_topic_message'] = 'Welcome to Simple Machines Forum!<br /><br />We hope you enjoy using your forum.&nbsp; If you have any problems, please feel free to [url=http://www.simplemachines.org/community/index.php]ask us for assistance[/url].<br /><br />Thanks!<br />Simple Machines';
$txt['default_board_name'] = 'General Discussion';
$txt['default_board_description'] = 'Feel free to talk about anything and everything in this board.';
$txt['default_category_name'] = 'General Category';
$txt['default_time_format'] = '%B %d, %Y, %I:%M:%S %p';

$txt['error_message_click'] = 'Click here';
$txt['error_message_try_again'] = 'to try this step again.';
$txt['error_message_bad_try_again'] = 'to try installing anyway, but note that this is <i>strongly</i> discouraged.';

$txt['install_settings'] = 'Basic Settings';
$txt['install_settings_info'] = 'Just a few things for you to setup ;).';
$txt['install_settings_name'] = 'Forum name';
$txt['install_settings_name_info'] = 'This is the name of your forum, ie. &quot;The Testing Forum&quot;.';
$txt['install_settings_name_default'] = 'My Community';
$txt['install_settings_url'] = 'Forum URL';
$txt['install_settings_url_info'] = 'This is the URL to your forum <b>without the trailing \'/\'!</b>.<br />In most cases, you can leave the default value in this box alone - it is usually right.';
$txt['install_settings_compress'] = 'Gzip Output';
$txt['install_settings_compress_title'] = 'Compress output to save bandwidth.';
// In this string, you can translate the word "PASS" to change what it says when the test passes.
$txt['install_settings_compress_info'] = 'This function does not work properly on all servers, but can save you a lot of bandwidth.<br />Click <a href="install.php?obgz=1&amp;pass_string=PASS" onclick="return reqWin(this.href, 200, 60);">here</a> to test it. (it should just say "PASS".)';
$txt['install_settings_dbsession'] = 'Database Sessions';
$txt['install_settings_dbsession_title'] = 'Use the database for sessions instead of using files.';
$txt['install_settings_dbsession_info1'] = 'This feature is almost always for the best, as it makes sessions more dependable.';
$txt['install_settings_dbsession_info2'] = 'It doesn\'t seem like this feature will work on your server, but you can try it.';
$txt['install_settings_proceed'] = 'Proceed';

$txt['mysql_settings'] = 'MySQL Server Settings';
$txt['mysql_settings_info'] = 'These are the settings to use for your MySQL server.  If you don\'t know the values, you should ask your host what they are.';
$txt['mysql_settings_server'] = 'MySQL server name';
$txt['mysql_settings_server_info'] = 'This is nearly always localhost - so if you don\'t know, try localhost.';
$txt['mysql_settings_username'] = 'MySQL username';
$txt['mysql_settings_username_info'] = 'Fill in the username you need to connect to your MySQL database here.<br />If you don\'t know what it is, try the username of your ftp account, most of the time they are the same.';
$txt['mysql_settings_password'] = 'MySQL password';
$txt['mysql_settings_password_info'] = 'Here, put the password you need to connect to your MySQL database.<br />If you don\'t know this, you should try the password to your ftp account.';
$txt['mysql_settings_database'] = 'MySQL database name';
$txt['mysql_settings_database_info'] = 'Fill in the name of the database you want to use for SMF to store its data in.<br />If this database does not exist, this installer will try to create it.';
$txt['mysql_settings_prefix'] = 'MySQL table prefix';
$txt['mysql_settings_prefix_info'] = 'The prefix for every table in the database.  <b>Do not install two forums with the same prefix!</b><br />This value allows for multiple installations in one database.';

$txt['user_settings'] = 'Create Your Account';
$txt['user_settings_info'] = 'The installer will now create a new administrator account for you.';
$txt['user_settings_username'] = 'Your username';
$txt['user_settings_username_info'] = 'Choose the name you want to login with.<br />This can\'t be changed later, but your display name can be.';
$txt['user_settings_password'] = 'Password';
$txt['user_settings_password_info'] = 'Fill in your preferred password here, and remember it well!';
$txt['user_settings_again'] = 'Password';
$txt['user_settings_again_info'] = '(just for verification.)';
$txt['user_settings_email'] = 'Email Address';
$txt['user_settings_email_info'] = 'Provide your email address as well.  <b>This must be a valid email address.</b>';
$txt['user_settings_database'] = 'MySQL Database Password';
$txt['user_settings_database_info'] = 'The installer requires that you supply the database password to create an administrator account, for security reasons.';
$txt['user_settings_proceed'] = 'Finish';

$txt['ftp_setup'] = 'FTP Connection Information';
$txt['ftp_setup_info'] = 'This installer can connect via FTP to fix the files that need to be writable and are not.  If this doesn\'t work for you, you will have to go in manually and make the files writable.  Please note that this doesn\'t support SSL right now.';
$txt['ftp_server'] = 'Server';
$txt['ftp_server_info'] = 'This should be the server and port for your FTP server.';
$txt['ftp_port'] = 'Port';
$txt['ftp_username'] = 'Username';
$txt['ftp_username_info'] = 'The username to login with. <i>This will not be saved anywhere.</i>';
$txt['ftp_password'] = 'Password';
$txt['ftp_password_info'] = 'The password to login with. <i>This will not be saved anywhere.</i>';
$txt['ftp_path'] = 'Install Path';
$txt['ftp_path_info'] = 'This is the <i>relative</i> path you use in your FTP server.';
$txt['ftp_connect'] = 'Connect';
$txt['ftp_setup_why'] = 'What is this step for?';
$txt['ftp_setup_why_info'] = 'Some files need to be writable for SMF to work properly.  This step allows you to let the installer make them writable for you.  However, in some cases it won\'t work - in that case, please make the following files 777 (writable):';
$txt['ftp_setup_again'] = 'to test if these files are writable again.';

$txt['error_php_too_low'] = 'Warning!  You do not appear to have a version of PHP installed on your webserver that meets SMF\'s <b>minimum installations requirements</b>.<br />If you are not the host, you will need to ask your host to upgrade, or use a different host - otherwise, please upgrade PHP to a recent version.<br /><br />If you know for a fact that your PHP version is high enough you may continue, although this is strongly discouraged.';
$txt['error_missing_files'] = 'Unable to find crucial installation files in the directory of this script!<br /><br />Please make sure you uploaded the entire installation package, including the sql file, and then try again.';
$txt['error_session_save_path'] = 'Please inform your host that the <b>session.save_path specified in php.ini</b> is not valid!  It needs to be changed to a directory that <b>exists</b>, and is <b>writable</b> by the user PHP is running under.<br />';
$txt['error_windows_chmod'] = 'You\'re on a windows server, and some crucial files are not writable.  Please ask your host to give <b>write permissions</b> to the user PHP is running under for the files in your SMF installation.  The following files or directories need to be writable:';
$txt['error_ftp_no_connect'] = 'Unable to connect to FTP server with this combination of details.';
$txt['error_mysql_connect'] = 'Cannot connect to the MySQL database server with the supplied data.<br /><br />If you are not sure about what to type in, please contact your host.';
$txt['error_mysql_too_low'] = 'The version of MySQL that your database server is using is very old, and does not meet SMF\'s minimum requirements.<br /><br />Please ask your host to either upgrade it or supply a new one, and if they won\'t, please try a different host.';
$txt['error_mysql_database'] = 'The installer was unable to access the &quot;<i>%s</i>&quot; database.  With some hosts, you have to create the database in your administration panel before SMF can use it.  Some also add prefixes - like your username - to your database names.';
$txt['error_mysql_queries'] = 'Some of the queries were not executed properly.  This could be caused by an unsupported (development or old) version of MySQL.<br /><br />Technical information about the queries:';
$txt['error_mysql_queries_line'] = 'Line #';
$txt['error_mysql_missing'] = 'The installer was unable to detect MySQL support in PHP.  Please ask your host to ensure that PHP was compiled with MySQL, or that the proper extension is being loaded.';
$txt['error_user_settings_again_match'] = 'You typed in two completely different passwords!';
$txt['error_user_settings_taken'] = 'Sorry, a member is already registered with that username and/or password.<br /><br />A new account has not been created.';
$txt['error_user_settings_query'] = 'A database error occurred while trying to create an administrator.  This error was:';
$txt['error_subs_missing'] = 'Unable to find the Sources/Subs.php file.  Please make sure it was uploaded properly, and then try again.';

?>