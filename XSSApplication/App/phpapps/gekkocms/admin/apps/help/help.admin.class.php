<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//


include ('help.class.php');
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
class helpAdmin extends basicAdministration {


//_________________________________________________________________________//    
    public function __construct()
    {
		// Data: Item
		parent::__construct ('help');
		$this->app = new help();
		
    }
//_________________________________________________________________________//    
	
	public function displayPageHeader()
	{
		global $admin_template_path;		
		
		parent::displayPageHeader();
		if ($_GET['action'] != 'edititem' && $_GET['action'] != 'newitem' && $_GET['action'] != 'editcategory' && $_GET['action'] != 'newcategory') 
		{
				
				echo JAVASCRIPT("/admin/apps/{$this->app_name}/{$this->app_name}.js");
		}
	}
	
//_________________________________________________________________________//    
	public function getApplicationArray()
	{
		
		
		$forbidden_listing = array('.','..','help','blocks','main','help','html','menus','bots','users');
		
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
?>