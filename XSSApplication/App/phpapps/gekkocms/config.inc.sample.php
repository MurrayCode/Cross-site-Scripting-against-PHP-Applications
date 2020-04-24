<?php
define('SITE_NAME', 'Your Site Name');
/* IF YOU DON'T KNOW WHAT THESE MEANS, DO NOT MODIFY THE 4 LINES BELOW, IT WILL AUTO-DETECT. INSTEAD, YOU CAN CHANGE IT FROM THE SETTINGS PAGE IN THE ADMIN BACKEND */
define('SITE_HTTP_URL', 'http://'.$_SERVER['SERVER_NAME'].(($_SERVER['SERVER_PORT'] != 80) ? ':'.$_SERVER['SERVER_PORT'] : ''));
define('SITE_HTTPS_URL',''); /* Format: https://sitename.com:portnumber .. if port number = 443 then ignore the number */
define('SITE_PATH',str_replace('\\','/',dirname(__FILE__)));
define('SITE_HTTPBASE', str_replace(str_replace('\\','/', $_SERVER['DOCUMENT_ROOT']),'',SITE_PATH)); // auto path (but it's slower ... change this when you're in production mode
/****************/

/**** Database - MySQL ****/
define('DB_HOST', 'localhost');
define('DB_DATABASE', 'gekkocms');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'password');
define('MAIL_DEFAULT_EMAIL', 'info@yourhost.com');

/***** Mail *****/
define('MAIL_DEFAULT_SENDER', 'Webmaster');

/***** Default Site Meta Keywords and Description ****/
define('SITE_META_KEYWORDS','babygekko, website, unitialized, please, change, this'); /* Default meta keywords if there is none defined in the app/item/category */
define('SITE_META_DESCRIPTION','Site Meta Description has not been set yet. Please change this'); /* Default meta keywords if there is none defined in the app/item/category */

define('SITE_OFFLINE',false); // If you enable this, site will go offline
define('SEF_ENABLED', true); // This is for Search Engine Friendly URL - must be disabled if you don't have url_rewrite (Apache/Linux) or in Windows 2003.
define('SSL_ENABLED', false); // Set Secure Socket Layer (SSL) to enabled

/***** Advanced Options - do not modify if you don't know ****/
define('DEFAULT_ADMIN_GROUP','Administrators'); // Set this for now - you can actually change this
define('DEFAULT_USER_CLASS','users'); // Set this for now by default - you can change to many differnet types of user class (must download from extensions - ldap (active directory), smf, etc
define('DEFAULT_XMLRPC_CLASS',''); // TODO - please ignore for now
define('DEFAULT_COMMENT_CLASS','');// TODO - please ignore for now
define('DEFAULT_LANGUAGE','en_us');
define('ADMIN_LANGUAGE','en_us');
define('PAGE_CACHE_ENABLED', 0);// TODO - please ignore for now
define('SQL_CACHE_ENABLED', 0); // If enabled, sql queries will be cached
define('SQL_ENFORCE_ROW_LIMIT', 1); // If enabled, SQL queries may not exceed HARDCODE_MAX_ROWLIMIT as defined in includes/definitions.inc.php
define('SQL_CACHE_TIME', 0); // how long to keep the sql cache
define('FORCE_SSL_ADMIN_LOGIN', false); /* For backend login. Please ensure that you have a valid SSL certificate or this will result in an error */ 
define('ADMIN_LOGIN_TIME',31104000); // How long to remember the login
define('ADMIN_TEMPLATE','babygekko');
define('USER_IMAGE_DIRECTORY',SITE_PATH.'/images');
/***************** DO NOT MODIFY BELOW THIS LINE ****************/
define('SITE_URL', (defined('SITE_HTTPS_URL') && SSL_ENABLED && SITE_HTTPS_URL !='' && ($_SERVER['HTTPS']==='on' || $_SERVER['HTTPS']===1 || $_SERVER['SERVER_PORT']===443)) ? SITE_HTTPS_URL : SITE_HTTP_URL);

?>