<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
include_admin_inc('admin_basic_manager.class.php');

class applications extends basicApplicationSimpleCategories
{
    public function __construct()
    {
 		$data_items = createDataArray ('id','title','description','sort_order','status','date_modified');
		parent::__construct('apps','Applications', 'gk_app_items', 'id', $data_items, '', '',null);		
    }

    public function displayMainPage()
	{
		return false;
	}
}


//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
class applicationsAdmin extends basicAdminManager
{


//_________________________________________________________________________//    
    public function __construct()
    {
		// Data: Item
		parent::__construct ('applications', true, array('blog','main','help','html','users'));
		$this->checkForUnregisteredItems(); // Experimental
    }
    
//_________________________________________________________________________//    
 
	public function installItem($name)
	{
		parent::installItem($name);
		include_admin_class($name);
		$adminapp_tobe_installed = $name.'Admin'; 
		$the_app = new $adminapp_tobe_installed;
		$install_result = $the_app->RunInstallScript();
		
		return $install_result;
	}
	
//_________________________________________________________________________//
	public function processUninstall($name)
	{
		$default_apps = array('blogs','html','settings','users','applications','blocks','filters','main','menus','help');
		if (in_array($name, $default_apps,TRUE)) return false;
		if ($_POST['sure']) 
		{
			$item_admin_path = SITE_PATH.'/admin/apps/'.$name;
			$item_admin_path_newname = SITE_PATH.'/admin/apps/uninstalled_'.$name;
			$item_path = SITE_PATH.'/apps/'.$name;
			$item_path_newname = SITE_PATH.'/apps/uninstalled_'.$name;
			
			include_admin_class($name);
			$adminapp_tobe_uninstalled = $name.'Admin'; 
			$the_app = new $adminapp_tobe_uninstalled;
			$uninstall_result = $the_app->RunUninstallScript($_POST['database'], $_POST['everything']);
			// April 10, 2010
			if ($uninstall_result)
			{
				$result1 = rename($item_admin_path, $item_admin_path_newname);
				$result2 = rename($item_path, $item_path_newname);
				if ($_POST['everything'])
				{
					$this->delTree($item_admin_path_newname);
					$this->delTree($item_path_newname);
				}
				return ($result1 && $result2);
			} else return false;
		}
		return false;
	}	
//_________________________________________________________________________//    
	public function getAllItems($start=0, $end=0,$sortby='', $sortdirection='ASC')
	{
		//$this->checkForUnregisteredItems();
		return parent::getAllItems($start,$end,$sortby, $sortdirection);
	}
//_________________________________________________________________________//    
	public function checkForUnregisteredItems()
	{
 		// note - this is not the most elegant code but it's a workaround for now to circumvent the mechanism
 		// because this class is named "applications" ... there's a lengthier explanation in the upcoming doc
		$this->managed_class_name = 'apps';
		parent::checkForUnregisteredItems();
		$this->managed_class_name = 'applications';		
	}
	
}
?>