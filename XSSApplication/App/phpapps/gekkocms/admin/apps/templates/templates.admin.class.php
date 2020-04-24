<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
include_admin_inc('admin_basic_manager.class.php');
include_inc ('templates.inc.php');

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
class templatesAdmin extends basicAdminManager {

//_________________________________________________________________________//    
    public function __construct()
    {
		// Data: Item
		parent::__construct ('templates', true, null);
    }
    
//_________________________________________________________________________//    
	public function getAllItems($start=0, $end=0,$sortby='', $sortdirection='ASC')
	{
		$this->checkForUnregisteredItems();
		return parent::getAllItems($start,$end,$sortby, $sortdirection);
	}
	//_________________________________________________________________________//	
	public function checkForUnregisteredItems()
	{	
		$forbidden_listing = array('.','..','.svn','.cvs');
		
		$item_path = SITE_PATH.'/'.$this->managed_class_name.'/';
		$item_array =  array();
		$dir_handle = @opendir($item_path);
		while ($file = readdir($dir_handle)) 
		{
		    if (!in_array($file,$forbidden_listing)  && (strpos($file,'uninstalled_')===false) &&  file_exists($item_path.$file.'/index.php')) $item_array[] = $file;
		}
		closedir($dir_handle);
		if ($item_array)
		{
			$this->cleanUpOrphanedItems($item_array);
			foreach ($item_array as $item)
			{
				$dups = $this->findInstalledItem($item);
				if (count($dups) == 0) $this->installItem($item);	
			}
		}
	}
//_________________________________________________________________________//
	public function processUninstall($name)
	{
		$default_apps = array();
		$result = false;
		if (in_array($name, $default_apps,TRUE)) return false;
		if ($_POST['sure']) 
		{
			$item_path = SITE_PATH."/{$this->app_name}/".$name;
			$item_path_newname = SITE_PATH."/{$this->app_name}/uninstalled_".$name;
			if (file_exists($item_path)) $result = rename($item_path, $item_path_newname);
			if ($_POST['everything'])
			{
				$this->delTree($item_path_newname);
			}
			
			return ($result);
		}
		return false;
	}	
	
	//_________________________________________________________________________//	
 	public function setTemplate($mode, $id)
 	{
 		$retval = $this->app->setTemplate($mode,$id);
 		ajaxReply(200,$retval);
 	}
	//_________________________________________________________________________// 	
	public function Install()
	{
		include_inc('pclzip/pclzip.lib.php');		
		$zipfile = $_FILES['zipfileupload'];
		if (is_array($zipfile))
		{
 			if ($zipfile['error'] == 0 && $zipfile['size'] > 0)
			{
				$zip = new PclZip($zipfile['tmp_name']);
				if ($zip->extract(PCLZIP_OPT_PATH, SITE_PATH.'/templates/') == 0) {
					die("There was a problem. Please try again!");
				} else {
				   $this->returnToMainAdminApplication();
				}
			}
 		}
		return false;
	}
	//_________________________________________________________________________//	
 	public function getDefaultTemplates()
 	{
 		$response = array();
 		$response['default'] = $this->app->getTemplate('default');
 		$response['mobile'] = $this->app->getTemplate('mobile');
 		//$response['iphone'] = $this->app->getTemplate('iphone');
 		ajaxReply(200,$response);
 	} 	
	//_________________________________________________________________________//	
	public function editItem($id)
	{
		$item = $this->getItemByID($id);
		$filename = SITE_PATH."/admin/apps/{$this->app_name}/editoritem.template.php";
		$authorfile = SITE_PATH."/{$this->app_name}/{$item['title']}/{$item['title']}.php";
		$preview = "/{$this->app_name}/{$item['title']}/images/{$item['title']}_preview.jpg";
		include_once ($filename);
 	}
	//_________________________________________________________________________//	
 	public function Run()
	{
		switch ($_GET['action'])
		{
			case 'settemplate': $this->setTemplate($_POST['mode'], $_POST['id']);break;
			case 'getdefaulttemplates':$this->getDefaultTemplates();break;
			default: parent::Run();
		}
	}
	
}
?>