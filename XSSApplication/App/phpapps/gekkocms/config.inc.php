<?php
/* generated 2020-02-17 11:17:33 */
define('SITE_NAME','test');
define('SITE_HTTP_URL','http://localhost');
define('SITE_HTTPS_URL','');
define('SITE_URL','http://localhost'); /* Initial Setup only - this can be changed from the Settings page and will auto switch http/https if you enable SSL */ 
define('SITE_HTTPBASE','');
define('SITE_PATH',str_replace('\\','/',dirname(__FILE__)));
define('DEFAULT_USER_CLASS','users'); /* Default user class - e.g: LDAP, smf forum, opencart, etc */
define('DEFAULT_LANGUAGE','en_us'); /* Default Language */ 
define('DEFAULT_ADMIN_GROUP','Administrators');
define('ADMIN_LANGUAGE','en_us');
define('SITE_OFFLINE',false); /* Set this to yes if you want to make your site offline for visitors */ 
define('SEF_ENABLED', true);
define('SSL_ENABLED',false);
define('DB_HOST','localhost');
define('DB_DATABASE','babygekko');
define('DB_USERNAME','root');
define('DB_PASSWORD','hacklab2019');
define('PAGE_CACHE_ENABLED', false);
define('SQL_ENFORCE_ROW_LIMIT', true);
define('SQL_CACHE_ENABLED', false);
define('SQL_CACHE_TIME', 0);
define('ADMIN_LOGIN_TIME',31104000); /* in seconds */ 
define('ADMIN_TEMPLATE','babygekko');
define('USER_IMAGE_DIRECTORY',SITE_PATH.'/images');
define('MAIL_DEFAULT_SENDER','Webmaster');
define('MAIL_DEFAULT_EMAIL','admin@test.com');
define('SITE_META_KEYWORDS',''); /* Default meta keywords if there is none defined in the app/item/category */
define('SITE_META_DESCRIPTION','test'); /* Default meta keywords if there is none defined in the app/item/category */ 
?>