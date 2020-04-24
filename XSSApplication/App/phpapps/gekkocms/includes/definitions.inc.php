<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

define ('SAVE_OK',"Save_OK");
define ('SAVE_DUPLICATE',"Save_Duplicate");
define ('ACCESS_NOT_ALLOWED','Access_Not_Allowed');
define ('SAVE_INVALID_DATA',"Save_Invalid_Data");
define ('SAVE_INCOMPLETE_DATA',"Save_Incomplete_Data");
define ('TXT_PAGINATION_STRING','Page %10d of %10d');
define ('TXT_PAGINATION_FIRST','&laquo;&laquo;');
define ('TXT_PAGINATION_PREV','&laquo;');
define ('TXT_PAGINATION_LAST','&raquo;&raquo;');
define ('TXT_PAGINATION_NEXT','&raquo;');
define ('NULL_DATE','0000-00-00 00:00:00');
define ('DEFAULT_FRONTEND_ITEMS_PERPAGE',10);
define ('HARDCODE_MAX_ROWLIMIT',10000); // generous for small servers - 10,000 rows max
define ('DATATABLE_MAX_ROW_PERPAGE',15);
define ('TYPEZIP_INVALID',-1);
define ('TYPEZIP_TEMPLATES',0);
define ('TYPEZIP_APP',1);
define ('TYPEZIP_BLOCK',2);
define ('TYPEZIP_FILTER',3);

define ('MSG_INFO','info');
define ('MSG_NOTICE','notice');
define ('MSG_WARNING','warning');
define ('MSG_ERROR','error');
define ('MSG_UNAUTHORIZED','unauthorized');

define('TRANS_SPRITE_IMAGE',SITE_HTTPBASE.'/images/default/trans.png');

define ('OPT_REDIRECT_DEFAULT',0);
define ('OPT_REDIRECT_HTTPS',1);
define ('OPT_REDIRECT_HTTP',2);

define ('DEFAULT_AJAX_TREENODEID_PREFIX','gwp_leftfolder_');
define ('GEKKO_DEFAULT_SESSION_NAME', md5(SITE_HTTP_URL.SITE_NAME.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']));
define ('GEKKO_ADMIN_SESSION_NAME','gadms'.GEKKO_DEFAULT_SESSION_NAME);
define ('GEKKO_SESSION_NAME','gusr'.GEKKO_DEFAULT_SESSION_NAME);

if (!defined('DEFAULT_ADMIN_GROUP')) define ('DEFAULT_ADMIN_GROUP','Administrators');
 
?>