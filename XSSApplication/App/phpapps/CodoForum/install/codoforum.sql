SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


DROP TABLE IF EXISTS `codo_categories`;
CREATE TABLE IF NOT EXISTS `codo_categories` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id of category',
  `cat_pid` int(11) NOT NULL DEFAULT '0' COMMENT 'category parent id',
  `cat_name` varchar(255) NOT NULL COMMENT 'name of the category',
  `cat_alias` varchar(300) NOT NULL COMMENT 'Name that will appear in the url',
  `cat_description` varchar(400) DEFAULT NULL COMMENT 'Description of this category',
  `cat_img` varchar(200) NOT NULL COMMENT 'name of image',
  `no_topics` int(11) NOT NULL COMMENT 'No of topics in this ategory',
  `no_posts` int(11) NOT NULL COMMENT 'No of posts in this category',
  `cat_order` int(11) NOT NULL DEFAULT '0' COMMENT 'order in which category is displayed',
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Contains forum categories' AUTO_INCREMENT=10 ;

INSERT INTO `codo_categories` (`cat_id`, `cat_pid`, `cat_name`, `cat_alias`, `cat_description`, `cat_img`, `no_topics`, `no_posts`, `cat_order`) VALUES
(3, 0, 'General Discussions', 'general-discussions', 'For anything and everything that doesn''t fit in other categories.', 'bubbles.png', 1, 1, 0),
(10, 0, 'News and Announcements', 'news-and-announcements', 'this is where all the latest news will be posted', 'bullhorn.png', 0, 0, 0),
(11, 0, 'Support Forums', 'support-forums', 'Have any problem? Report it here and we will be glad to help.', 'support.png', 0, 0, 2),
(12, 0, 'Let us know', 'let-us-know', 'We encourage new members to post a short description about themselves', 'envelope.png', 0, 0, 2),
(13, 0, 'Bug Reports', 'bug-reports', 'Found a bug? why not report it here?', 'bug.png', 0, 0, 2),
(14, 0, 'Feature Requests', 'feature-requests', 'You have a cool idea? post them here!', 'wand.png', 0, 0, 2);

DROP TABLE IF EXISTS `codo_config`;
CREATE TABLE IF NOT EXISTS `codo_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(50) NOT NULL,
  `option_value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='contains site information' AUTO_INCREMENT=32 ;

INSERT INTO `codo_config` (`id`, `option_name`, `option_value`) VALUES
(1, 'site_url', ''),
(2, 'site_title', 'CODOLOGIC'),
(3, 'site_description', 'codoforum - Enhancing your forum experience with next generation technology!'),
(4, 'admin_email', 'admin@codologic.com'),
(5, 'theme', 'blue'),
(6, 'captcha_public_key', '6LcMnOkSAAAAALO3jLKIIAwuhdcq34PZ1rXi0-pZ'),
(7, 'captcha_private_key', '6LcMnOkSAAAAAGzpSKY79uIjFybQ4C0_PnmNe2US'),
(8, 'register_pass_min', '8'),
(9, 'num_posts_all_topics', '30'),
(10, 'num_posts_cat_topics', '20'),
(11, 'num_posts_per_topic', '20'),
(12, 'forum_attachments_path', 'assets/img/attachments'),
(13, 'forum_attachments_exts', 'jpg,jpeg,png,gif,pjpeg,bmp,txt'),
(14, 'forum_attachments_size', '3'),
(15, 'forum_attachments_mimetypes', 'image/*,text/plain'),
(16, 'forum_attachments_multiple', 'true'),
(17, 'forum_attachments_parallel', '4'),
(18, 'forum_attachments_max', '10'),
(19, 'reply_min_chars', '10'),
(20, 'subcategory_dropdown', 'hidden'),
(21, 'captcha', 'disabled'),
(22, 'await_approval_message', 'Dear [user:username],\n\nThank you for registering at [option:site_title]. Before we can activate your account one last step must be taken to complete your registration.\n\nTo complete your registration, please visit this URL: [this:confirm_url]\n\nYour Username is: [user:username] \n\nIf you are still having problems signing up please contact a member of our support staff at [option:admin_email]\n\nRegards,\n[option:site_title]'),
(23, 'await_approval_subject', 'Confirm your email for [user:username] at [option:site_title]'),
(24, 'mail_type', 'mail'),
(25, 'smtp_protocol', 'ssl'),
(26, 'smtp_server', 'smtp.gmail.com'),
(27, 'smtp_port', '465'),
(28, 'smtp_username', 'admin@codologic.com'),
(29, 'smtp_password', 'your_smtp_pass'),
(30, 'register_username_min', '3'),
(31, 'signature_char_lim', '255'),
(32, 'sso_client_id', 'codoforum'),
(33, 'sso_secret', 'Xe24!rf'),
(34, 'sso_get_user_path', 'http://localhost/page/codoforum_sso/user'),
(35, 'sso_login_user_path', 'http://localhost/page/user?codoforum=sso'),
(36, 'sso_logout_user_path', 'http://localhost/page/user/logout'),
(37, 'sso_register_user_path', 'http://localhost/page/user/lot'),
(38, 'sso_name', 'Codologic'),
(39, 'post_notify_message', 'Hi,\n\n[user:username] has replied to the topic: [post:title]\n\n----\n[post:imessage]\n----\n\nYou can view the reply at the following url\n[post:url]\n\nRegards,\n[option:site_title] team\n'),
(40, 'post_notify_subject', '[post:title] - new reply'),
(41, 'password_reset_message', 'Hi,\r\n\r\nYour password has been reset . \r\n\r\nNew password: [user:password]\r\n\r\nNote: Please change your password immediately after you login.\r\n\r\nRegards,\r\n[option:site_title] team\r\n'),
(42, 'password_reset_subject', 'Your password has been reset -[option:site_title]'),
(43, 'topic_notify_message', 'Hi,[post:username]\r\n\r\n[user:username] has created a new topic: [post:title]\r\nin category [post:category]\r\n\r\nYou can view the topic by clicking [post:url]\r\n\r\nRegards,\r\n[option:site_title] team'),
(44, 'topic_notify_subject', '[post:category] - new topic\r\n'),
(45, 'brand_img', 'http://codoforum.com/img/favicon-32x32.png');

DROP TABLE IF EXISTS `codo_logs`;
CREATE TABLE IF NOT EXISTS `codo_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'primary id of each log',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT 'userid of the user',
  `log_type` varchar(64) NOT NULL COMMENT 'type of log',
  `message` text NOT NULL COMMENT 'log message',
  `severity` int(11) NOT NULL COMMENT 'severity level from emergency(0) to n',
  `location` varchar(200) NOT NULL COMMENT 'method name or page location',
  `log_time` int(11) NOT NULL COMMENT 'time of log',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Contains logs of events/errors/warnings etc' AUTO_INCREMENT=20 ;

DROP TABLE IF EXISTS `codo_plugins`;
CREATE TABLE IF NOT EXISTS `codo_plugins` (
  `plg_name` varchar(255) NOT NULL COMMENT 'path of filename relative to codoforum root',
  `plg_type` varchar(10) NOT NULL DEFAULT '',
  `plg_status` int(11) NOT NULL DEFAULT '0' COMMENT 'boolean indicating plugin is enabled or not',
  `plg_weight` int(11) NOT NULL DEFAULT '0' COMMENT 'order in which plugin is invoked for a hook',
  `plg_schema_ver` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `filename` (`plg_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contains list of all plugins';


INSERT INTO `codo_plugins` (`plg_name`, `plg_type`, `plg_status`, `plg_weight`, `plg_schema_ver`) VALUES
('post_notify','plugin', 1, 0, 1),
('sso','plugin', 0, 0, 1),
('uni_login','plugin', 0, 0, 1);

DROP TABLE IF EXISTS `codo_posts`;
CREATE TABLE IF NOT EXISTS `codo_posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id of post',
  `topic_id` int(11) NOT NULL COMMENT 'corresponding id of topic',
  `cat_id` int(11) NOT NULL COMMENT 'corresponding id of category',
  `uid` int(11) NOT NULL COMMENT 'userid creating this post',
  `imessage` text NOT NULL COMMENT 'message in bbcode/markdown format',
  `omessage` text NOT NULL COMMENT 'message in html format',
  `post_created` int(11) NOT NULL COMMENT 'time at which this post was created',
  `post_modified` int(11) DEFAULT NULL COMMENT 'time at which this post was modified',
  `post_status` int(11) NOT NULL DEFAULT '1' COMMENT '1=active, 0=deleted',
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Contains forum posts' AUTO_INCREMENT=1 ;

INSERT INTO `codo_posts` (`post_id`, `topic_id`, `cat_id`, `uid`, `imessage`, `omessage`, `post_created`, `post_modified`, `post_status`) VALUES
(1, 1, 3, 1, 'Hi,  \n  \nThis is an example post in your codoforum installation.   \nYou can create/modify/delete all forum categories from the forum backend.  \n  \nPlease edit the forum title and description from the backend.   \n  \nThe only user available to login in the front-end is admin with the password that you set during the installation.\n \nYou may delete this post . \n  \nRegards,   \nCodologic Team', '<p>Hi,  </p>\n<p>This is an example post in your codoforum installation.<br>You can create/modify/delete all forum categories from the forum backend.  </p>\n<p>Please edit the forum title and description from the backend.   </p>\n<p>The only user available to login in the front-end is admin with the password that you set during the installation.</p>\n<p>You may delete this post . </p>\n<p>Regards,<br>Codologic Team</p>', 1401549322, NULL, 1);


DROP TABLE IF EXISTS `codo_roles`;
CREATE TABLE IF NOT EXISTS `codo_roles` (
  `rid` int(11) NOT NULL,
  `rname` varchar(40) NOT NULL COMMENT 'role name of user',
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contains different roles and permissions of users';

INSERT INTO `codo_roles` (`rid`, `rname`) VALUES
(1, 'guest'),
(5, 'unverified user'),
(2, 'user'),
(3, 'moderator'),
(4, 'administrator');

DROP TABLE IF EXISTS `codo_role_permissions`;
CREATE TABLE IF NOT EXISTS `codo_role_permissions` (
  `rid` int(11) NOT NULL COMMENT 'role id',
  `permission` varchar(128) NOT NULL COMMENT 'permission name',
  `module` varchar(100) NOT NULL DEFAULT 'core' COMMENT 'module name , default is core',
  PRIMARY KEY (`rid`,`permission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contains permissions for user roles';

INSERT INTO `codo_role_permissions` (`rid`, `permission`, `module`) VALUES
(1, 'view profile', 'core'),
(5, 'view profile', 'core'),
(2, 'create new topic', 'core'),
(2, 'create topic', 'core'),
(2, 'edit my post', 'core'),
(2, 'edit my profile', 'core'),
(2, 'edit my topic', 'core'),
(2, 'reply to topic', 'core'),
(2, 'view profile', 'core'),
(3, 'create new topic', 'core'),
(3, 'create topic', 'core'),
(3, 'delete all posts', 'core'),
(3, 'delete all topics', 'core'),
(3, 'edit all posts', 'core'),
(3, 'edit all topics', 'core'),
(3, 'edit my profile', 'core'),
(3, 'reply to topic', 'core'),
(3, 'view profile', 'core'),
(4, 'create category', 'core'),
(4, 'create new topic', 'core'),
(4, 'create topic', 'core'),
(4, 'delete all posts', 'core'),
(4, 'delete all topics', 'core'),
(4, 'edit all posts', 'core'),
(4, 'edit all profiles', 'core'),
(4, 'edit all topics', 'core'),
(4, 'edit my profile', 'core'),
(4, 'reply to topic', 'core'),
(4, 'view profile', 'core');

DROP TABLE IF EXISTS `codo_sessions`;
CREATE TABLE IF NOT EXISTS `codo_sessions` (
  `sid` varchar(255) NOT NULL COMMENT 'php session id',
  `last_active` int(11) NOT NULL COMMENT 'last active time',
  `session_data` text NOT NULL COMMENT 'session data',
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contains user sessions';


DROP TABLE IF EXISTS `codo_signups`;
CREATE TABLE IF NOT EXISTS `codo_signups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `token` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='stores temporary sign up attempts for account activation' AUTO_INCREMENT=13 ;


DROP TABLE IF EXISTS `codo_smileys`;
CREATE TABLE IF NOT EXISTS `codo_smileys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol` varchar(50) NOT NULL,
  `image_name` varchar(50) NOT NULL,
  `weight` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='stores paths to smileys available while posting' AUTO_INCREMENT=20 ;

INSERT INTO `codo_smileys` (`id`, `symbol`, `image_name`) VALUES
(1, ':S', 'worried.gif'),
(2, '(wasntme)', 'itwasntme.gif'),
(3, 'x(', 'angry.gif'),
(4, '(doh)', 'doh.gif'),
(5, '|-()', 'yawn.gif'),
(6, ']:)', 'evilgrin.gif'),
(7, '|(', 'dull.gif'),
(8, '|-)', 'sleepy.gif'),
(9, '(blush)', 'blush.gif'),
(10, ':P', 'tongueout.gif'),
(11, '(:|', 'sweat.gif'),
(12, ';(', 'crying.gif'),
(13, ':)', 'smile.gif'),
(14, ':(', 'sad.gif'),
(15, ':D', 'bigsmile.gif'),
(16, '8)', 'cool.gif'),
(17, ';)', 'wink.gif'),
(18, '(mm)', 'mmm.gif'),
(19, ':x', 'lipssealed.gif');

DROP TABLE IF EXISTS `codo_topics`;
CREATE TABLE IF NOT EXISTS `codo_topics` (
  `topic_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'topic id',
  `title` varchar(255) NOT NULL COMMENT 'title of the topic',
  `cat_id` smallint(6) NOT NULL COMMENT 'category id to which the topic belongs',
  `post_id` int(11) DEFAULT NULL COMMENT 'Contains postid of parent post',
  `uid` int(11) NOT NULL COMMENT 'userid creating this topic',
  `last_post_id` int(11) NOT NULL COMMENT 'Contains id of the last post',
  `last_post_uid` varchar(200) DEFAULT NULL COMMENT 'userid making last reply',
  `last_post_name` varchar(200) DEFAULT NULL COMMENT 'username making last reply',
  `topic_created` int(11) NOT NULL COMMENT 'time at which topic was created',
  `topic_updated` int(11) NOT NULL COMMENT 'time at which topic was last edited',
  `last_post_time` int(11) NOT NULL COMMENT 'time at which last reply was made',
  `no_posts` int(11) NOT NULL DEFAULT '0' COMMENT 'No. of replies for the topic',
  `no_views` int(10) NOT NULL DEFAULT '0' COMMENT 'No. of views for the topic',
  `topic_status` int(11) NOT NULL DEFAULT '1' COMMENT '0=deleted;1=active;2=sticky;3=locked',
  PRIMARY KEY (`topic_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Caontains forum topics' AUTO_INCREMENT=1 ;

ALTER TABLE  `codo_topics` ADD INDEX (  `last_post_time` ) ;
ALTER TABLE  `codo_topics` ADD INDEX (  `cat_id` ,  `uid` ,  `topic_created` ) ;

INSERT INTO `codo_topics` (`topic_id`, `title`, `cat_id`, `post_id`, `uid`, `last_post_id`, `last_post_uid`, `last_post_name`, `topic_created`, `topic_updated`, `last_post_time`, `no_posts`, `no_views`, `topic_status`) VALUES
(1, 'Welcome to Codoforum', 3, 1, 1, 0, NULL, NULL, 1401549322, 0, 1401549322, 1, 0, 1);


DROP TABLE IF EXISTS `codo_users`;
CREATE TABLE IF NOT EXISTS `codo_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'user id of user',
  `username` varchar(60) NOT NULL COMMENT 'unique username of user',
  `name` varchar(100) DEFAULT NULL COMMENT 'display name of user',
  `pass` varchar(128) NOT NULL COMMENT 'salted password',
  `token` varchar(64) NOT NULL COMMENT 'Contains the cookie token',
  `mail` varchar(200) DEFAULT NULL COMMENT 'email id of user',
  `created` int(11) NOT NULL COMMENT 'php time when user was created',
  `last_access` int(11) NOT NULL DEFAULT '0' COMMENT 'php time when user last logged in',
  `read_time` int(11) NOT NULL DEFAULT '0' COMMENT 'last user read mark time',
  `user_status` int(11) NOT NULL DEFAULT '1' COMMENT '1 = active , 0 = pending',
  `avatar` varchar(200) DEFAULT NULL COMMENT 'full path to avatar',
  `signature` text COMMENT 'users signature displayed after each post',
  `no_posts` int(11) NOT NULL DEFAULT '0' COMMENT 'No of posts created by the user',
  `profile_views` int(11) NOT NULL COMMENT 'no of times users other than me viewed my profile',
  `rid` int(11) NOT NULL COMMENT 'role id of the user as described in codo_roles table',
  `oauth_id` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Contains user information' AUTO_INCREMENT=1 ;

ALTER TABLE  `codo_users` ADD UNIQUE (`mail`);

--
-- Table structure for table `codo_notify`
--

DROP TABLE IF EXISTS `codo_notify`;
CREATE TABLE IF NOT EXISTS `codo_notify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `uid` int(11) NOT NULL,
  `nid` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `is_read` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `is_read` (`is_read`),
  KEY `nid` (`nid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

DROP TABLE IF EXISTS `codo_notify_queue`;
CREATE TABLE IF NOT EXISTS `codo_notify_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(200) NOT NULL,
  `nid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `codo_notify_subscribers`;
CREATE TABLE IF NOT EXISTS `codo_notify_subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL,
  `uid` int(11) NOT NULL COMMENT 'subscriber''s user id ',
  `type` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`),
  KEY `tid` (`tid`),
  KEY `uid` (`uid`),
  KEY `channel` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

DROP TABLE IF EXISTS `codo_notify_text`;
CREATE TABLE IF NOT EXISTS `codo_notify_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=138 ;

DROP TABLE IF EXISTS `codo_user_preferences`;
CREATE TABLE IF NOT EXISTS `codo_user_preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `preference` varchar(100) NOT NULL,
  `value` varchar(400) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `preference` (`preference`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT INTO `codo_user_preferences` (`uid`, `preference`, `value`) VALUES
( 0, 'notification_frequency', 'immediate'),
( 0, 'real_time_notifications', 'yes'),
( 0, 'desktop_notifications', 'yes'),
( 0, 'send_emails_when_online', 'no'),
( 0, 'notification_type_on_create_topic', '4'),
( 0, 'notification_type_on_reply_topic', '3');


DROP TABLE IF EXISTS `codo_views`;
CREATE TABLE IF NOT EXISTS `codo_views` (
  `date` date NOT NULL,
  `views` int(11) NOT NULL,
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `codo_mail_queue`;
CREATE TABLE IF NOT EXISTS `codo_mail_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_type` int(11) DEFAULT '1' COMMENT '1: notification',
  `mail_status` int(11) NOT NULL DEFAULT '0' COMMENT '0: not sent , 1: sending, 2: sent, 3: error',
  `from_address` varchar(200) DEFAULT NULL,
  `to_address` varchar(200) NOT NULL,
  `mail_subject` varchar(400) NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='queues emails to be sent later' AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `codo_crons`;
CREATE TABLE IF NOT EXISTS `codo_crons` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'cron id',
  `cron_name` varchar(100) NOT NULL COMMENT 'cron name',
  `cron_type` enum('once','recurrence') NOT NULL COMMENT 'can be once or recurrence',
  `cron_interval` int(11) NOT NULL COMMENT 'interval time ',
  `cron_started` int(11) NOT NULL COMMENT 'time at which cron started',
  `cron_last_run` int(11) NOT NULL COMMENT 'time when cron last run',
  `cron_status` tinyint(1) NOT NULL COMMENT '1 = running , 0 = not running',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cron_name` (`cron_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='cron schedules' AUTO_INCREMENT=1 ;

INSERT INTO `codo_crons` (`id`, `cron_name`, `cron_type`, `cron_interval`, `cron_started`, `cron_last_run`, `cron_status`) VALUES
(1, 'core', 'recurrence', 86400, 0, 0, 0);

CREATE TABLE IF NOT EXISTS `codo_bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(100) NOT NULL COMMENT 'name/email/ip of item',
  `ban_type` enum('name','mail','ip') NOT NULL,
  `ban_by` varchar(100) NOT NULL COMMENT 'who created this ban',
  `ban_on` int(11) NOT NULL COMMENT 'when was this created',
  `ban_reason` varchar(300) NOT NULL COMMENT 'why was this created',
  `ban_expires` int(11) NOT NULL COMMENT 'when will this expire ',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='stores ban list' AUTO_INCREMENT=2 ;

DROP TABLE IF EXISTS `codo_blocks`;
CREATE TABLE IF NOT EXISTS `codo_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(64) NOT NULL DEFAULT '',
  `theme` varchar(64) NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT '0',
  `weight` int(11) NOT NULL DEFAULT '0',
  `region` varchar(64) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `visibility` int(11) NOT NULL DEFAULT '0',
  `pages` text NOT NULL,
  `title` varchar(64) NOT NULL DEFAULT '',
  `cache` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

INSERT INTO `codo_blocks` (`id`, `module`, `theme`, `status`, `weight`, `region`, `content`, `visibility`, `pages`, `title`, `cache`) VALUES
(1, 'html', 'blue', 0, 0, 'block_main_menu', '<li><a href="#">Link</a></li>\r\n        <li class="dropdown">\r\n          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>\r\n          <ul class="dropdown-menu" role="menu">\r\n            <li><a href="#">Action</a></li>\r\n            <li><a href="#">Another action</a></li>\r\n            <li><a href="#">Something else here</a></li>\r\n            <li class="divider"></li>\r\n            <li><a href="#">Separated link</a></li>\r\n            <li class="divider"></li>\r\n            <li><a href="#">One more separated link</a></li>\r\n          </ul>\r\n</li>', 0, '', 'Main Menu', 1);

DROP TABLE IF EXISTS `codo_block_roles`;
CREATE TABLE IF NOT EXISTS `codo_block_roles` (
  `bid` int(11) NOT NULL COMMENT 'block id',
  `rid` int(11) NOT NULL,
  UNIQUE KEY `bid` (`bid`,`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `codo_unread_categories`;
CREATE TABLE IF NOT EXISTS `codo_unread_categories` (
  `cat_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `read_time` int(11) NOT NULL COMMENT 'last mark as read',
  PRIMARY KEY (`cat_id`,`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `codo_unread_topics`;
CREATE TABLE IF NOT EXISTS `codo_unread_topics` (
  `cat_id` int(11) NOT NULL COMMENT 'category id',
  `topic_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `read_time` int(11) NOT NULL,
  PRIMARY KEY (`topic_id`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='stores last visited category/topic time';

DROP TABLE IF EXISTS `codo_tags`;
CREATE TABLE IF NOT EXISTS `codo_tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(50) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `codo_tags_allowed`;
CREATE TABLE IF NOT EXISTS `codo_tags_allowed` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_text` text NOT NULL,
  `cat_id` int(11) NOT NULL,
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

