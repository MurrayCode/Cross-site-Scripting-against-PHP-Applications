<?php

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
class settings extends basicApplicationLinearData
{
    public function __construct()
    {
 		$data_items = createDataArray ('id','title','status');
		parent::__construct('settings', 'Settings','', '', $data_items);
    }
	
 	public function displayMainPage()
	{
		
		return false;
	}
	
	public function getAllItems($fields='*', $extra_criteria = '', $start=0,$end=0,$sortby='', $sortdirection='ASC', $from_cache = false)
	{
		$forbidden_listing = array('.','..','settings','blocks','main','help','html','menus','bots','users','__MACOSX');
		
		$admin_path = SITE_PATH.'/admin/apps/';
		$app_path = SITE_PATH.'/apps/';
		$app_array =  array();
		$dir_handle = @opendir($admin_path);
		while ($file = readdir($dir_handle)) 
		{
		    if (!in_array($file,$forbidden_listing) && file_exists($app_path.$file)) $app_array[]['title'] = $file;
		}
		closedir($dir_handle);
		return $app_array;
	}
}

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
class settingsAdmin extends basicAdministration {


//_________________________________________________________________________//    
    public function __construct()
    {
		// Data: Item
		parent::__construct ('settings');
		$this->config_file = SITE_PATH.'/config.inc.php';
		$this->app = new settings();
		

    }
//_________________________________________________________________________//    
	public function displayPageHeader()
	{
		parent::displayPageHeader();
		if ($_GET['action'] != 'edititem' && $_GET['action'] != 'newitem' && $_GET['action'] != 'editcategory' && $_GET['action'] != 'newcategory') 
		{
				
				echo JAVASCRIPT_GEKKO();				
				echo JAVASCRIPT("/admin/apps/{$this->app_name}/{$this->app_name}.js");
		}
	}
 	
//_________________________________________________________________________//  
	public function saveSettings()
	{
		checkInvalidCSRFAndHaltOnError();
		if (!is_writable($this->config_file)) $errormsg = H4('WARNING: Cannot write to configuration file '.$this->config_file);
		else
		{
			$data = '<?php'."\n";
			$data.= "/* generated ".date('Y-m-d H:i:s')." */\n";
			$data.="define('SITE_NAME','{$_POST['site_name']}');\n";
			$data.="define('SITE_HTTP_URL','{$_POST['site_http_url']}'); /* Format: http://sitename.com:portnumber .. if port number = 80 then ignore the number */ \n";			
			$data.="define('SITE_HTTPS_URL','{$_POST['site_https_url']}'); /* Format: https://sitename.com:portnumber .. if port number = 443 then ignore the number */\n";
	
			
			if (!empty($_SERVER['WINDIR']))
				$data.="define('SITE_PATH',str_replace('\\\','/',dirname(__FILE__)));\n";
			else 
				$data.="define('SITE_PATH',dirname(__FILE__));\n";
		//	$data.="define('SITE_TEMPLATE','{$_POST['site_template']}');\n";
			$data.="define('SITE_HTTPBASE','{$_POST['site_httpbase']}');\n";
			if ($_POST['site_online'])
				$data.="define('SITE_OFFLINE',false);\n";
			else
				$data.="define('SITE_OFFLINE',true);\n";
			if ($_POST['sef_enabled'])
				$data.="define('SEF_ENABLED', true); /* Search Engine Friendly URL only works on Linux and Win2008/IIS7.5, not Win2003 I was told */ \n";
			else
				$data.="define('SEF_ENABLED', false); /* Don't enable Search Engine Friendly URL if you don't have URL-Rewrite (Apache/IIS) */ \n";	
			if ($_POST['ssl_enabled'])
				$data.="define('SSL_ENABLED', true); /* Please ensure that you have a valid SSL certificate or this will result in an error */ \n";
			else
				$data.="define('SSL_ENABLED', false); /* If you have SSL and want to enable it here */ \n";
			if ($_POST['ssl_enabled'])
				$data.="define('FORCE_SSL_ADMIN_LOGIN', true); /* For backend login. Please ensure that you have a valid SSL certificate or this will result in an error */ \n";
			else
				$data.="define('FORCE_SSL_ADMIN_LOGIN', false); /* If you have SSL and want to enable it here */ \n";
				
			$data.="define('DEFAULT_USER_CLASS','{$_POST['default_user_class']}');\n";
			
			$data.="define('DEFAULT_XMLRPC_CLASS','{$_POST['default_xmlrpc_class']}');\n";	  //TODO	
			$data.="define('DEFAULT_COMMENT_CLASS','{$_POST['default_comment_class']}');\n";  //TODO
			
			$data.="define('DEFAULT_LANGUAGE','en_us');\n";
			$data.="define('ADMIN_LANGUAGE','en_us');\n";							
			$data.="define('DB_HOST','{$_POST['db_host']}');\n";
			$data.="define('DB_DATABASE','{$_POST['db_database']}');\n";
			$data.="define('DB_USERNAME','{$_POST['db_username']}');\n";
			$data.="define('DB_PASSWORD','{$_POST['db_password']}');\n";
			if ($_POST['enable_page_cache'])
				$data.="define('PAGE_CACHE_ENABLED', 1);\n"; //TODO
			else
				$data.="define('PAGE_CACHE_ENABLED', 0);\n";			
			if ($_POST['enable_sql_cache'])
				$data.="define('SQL_CACHE_ENABLED', 1);\n";
			else
				$data.="define('SQL_CACHE_ENABLED', 0);\n";
			if ($_POST['enforce_sql_row_limit'])
				$data.="define('SQL_ENFORCE_ROW_LIMIT', 1);\n";
			else
				$data.="define('SQL_ENFORCE_ROW_LIMIT', 0);\n";
				
			$data.="define('SQL_CACHE_TIME', {$_POST['sql_cache_time']});\n";					
			$data.="define('DEFAULT_ADMIN_GROUP','{$_POST['default_admin_group']}');\n";			
			$data.="define('ADMIN_LOGIN_TIME',{$_POST['admin_login_time']});\n";
			$data.="define('ADMIN_TEMPLATE','babygekko');\n";
			$data.="define('USER_IMAGE_DIRECTORY',SITE_PATH.'/images');\n";
			if (empty($_POST['site_meta_description'])) $_POST['site_meta_description'] = $_POST['site_name'];
			$_POST['site_meta_key'] = SAFE_HTML($_POST['site_meta_key']);
			$_POST['site_meta_description'] = SAFE_HTML($_POST['site_meta_description']);	
			$_POST['mail_default_sender'] = SAFE_HTML($_POST['mail_default_sender']);			
			$data.="define('SITE_META_KEYWORDS','{$_POST['site_meta_key']}'); /* Default meta keywords if there is none defined in the app/item/category */\n";
			$data.="define('SITE_META_DESCRIPTION','{$_POST['site_meta_description']}'); /* Default meta keywords if there is none defined in the app/item/category */\n";
			$data.="define('MAIL_DEFAULT_SENDER','{$_POST['mail_default_sender']}');\n";
			$data.="define('MAIL_DEFAULT_EMAIL','{$_POST['mail_default_email']}');\n";
			
			if ($_POST['ssl_enabled'] && $_POST['site_https_url'])
			{
				$data.="/**********************************************************************/\n";
				$data.="/* Do not modify below this line - this is for auto http/https switch */\n";
				$data.='define(\'SITE_URL\', (defined(\'SITE_HTTPS_URL\') && SSL_ENABLED && SITE_HTTPS_URL !=\'\' && ($_SERVER[\'HTTPS\']===\'on\' || $_SERVER[\'HTTPS\']===1 || $_SERVER[\'SERVER_PORT\']===443)) ? SITE_HTTPS_URL : SITE_HTTP_URL);';	
				$data.="/**********************************************************************/\n";
			}
			else $data.="define('SITE_URL','{$_SESSION['site_url']}');\n";
			$data.='?>';

			$confighandle = @fopen($this->config_file, 'w') or die("Cannot save to configuration file");
 			fwrite($confighandle, $data);
 			fclose($confighandle);
			$_SESSION['settings_save_ok'] = 1;
			// Force Refresh Menu Links
			include_admin_class('menus');
			$menuadm = new menusAdmin;
			$menuadm->refreshMenuLinks(); // prana - may 24, 2010
			
		}

	}
//_________________________________________________________________________//    	
	public function deleteCache($dir)
	{
		$forbidden_listing = array('.','..');
		
		if ($dir && $_POST['confirm'] == 1)
		{
			$cache_path = SITE_PATH."/cache/{$dir}/";
			$cache_array =  array();

			$dir_handle = @opendir($cache_path);
	
			while ($file = readdir($dir_handle)) 
			{
				if (!in_array($file,$forbidden_listing) && strpos($file,'.array')!==false)
				{
					unlink($cache_path.$file);
				}
			}
			closedir($dir_handle);
		}
		ajaxReply(200,'OK');
	}
	
//_________________________________________________________________________//    
	public function getUserGroupsArray()
	{
		global $gekko_current_admin_user;
		
		$all_groups = $gekko_current_admin_user->getAllCategories();
		$result = array();
		foreach ($all_groups as $group)
		{
			$result[] = array('label'=>$group['groupname'],'value'=>$group['groupname']);
		}
		return $result;
	}
//_________________________________________________________________________//    
	public function getUsersClassArray()
	{
		
		$forbidden_listing = array('.','..');
		
		$template_path = SITE_PATH.'/apps/';
		$template_array =  array();
		$dir_handle = @opendir($template_path);

		while ($file = readdir($dir_handle)) 
		{
		    if (!in_array($file,$forbidden_listing) && file_exists($template_path.$file."/{$file}.class.php") && strpos($file,'users')!==false)
			{
				$template_array[] = $file;
			}
		}
		closedir($dir_handle);
		return $template_array;
	}
//_________________________________________________________________________//  	
	public function Run()
	{
		switch ($_GET['action'])
		{
			case 'deletesqlcache': $this->deleteCache('sql');break;			
			case 'savesettings': $this->saveSettings();$this->returnToMainAdminApplication();break;
			default: parent::Run();
		}
	}

}
?>