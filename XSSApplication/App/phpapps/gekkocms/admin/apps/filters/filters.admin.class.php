<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
include_admin_inc('admin_basic_manager.class.php');
include_inc ('filters.inc.php');
include_inc('dbconfig.inc.php');

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
class filtersAdmin extends basicAdminManager {

//_________________________________________________________________________//    
    public function __construct()
    {
		// Data: Item
		parent::__construct ('filters', true, array());
		
    } 
	//_________________________________________________________________________//    
	public function displayPageHeader()
	{
		
		basicAdministration::displayPageHeader();
		
		echo JAVASCRIPT("/admin/apps/{$this->app_name}/{$this->app_name}.js");
	}
	
	//_________________________________________________________________________//	
	public function findInstalledItem($title)
	{
		global $gekko_db;
		
		$current_id = $data[$this->field_id];
		$sql =  "SELECT * from {$this->table_items} WHERE (title = '{$title}')";
		$gekko_db->query($sql);
		$result  = $gekko_db->get_result_as_array();
		return $result;
	}    
//_________________________________________________________________________//    
	public function getAllItems($start=0, $end=0,$sortby='', $sortdirection='ASC')
	{
		$this->checkForUnregisteredItems();
		return parent::getAllItems($sortby, $sortdirection);
	}

	//_________________________________________________________________________//	
	public function saveItem($id)
	{
		$_POST['enabled_apps'] = serialize($_POST['enabled_apps']);
		$_POST['enabled_blocks'] = serialize($_POST['enabled_blocks']);
		if ($_POST['display_in_apps'] == 1) $_POST['enabled_apps'] = '';
		if ($_POST['display_in_blocks'] == 1) $_POST['enabled_blocks'] = '';
		
		$retval = $this->app->saveItem($id);
		
  		return $retval;
	}
	//_________________________________________________________________________//	

	public function getListofApplicationsOrBlocks($type)
	{
		$forbidden_listing = array('.','..','.svn','.cvs');
		if ($type != 'apps') $type = 'blocks'; // simplify verification
		$item_path = SITE_PATH."/{$type}/"; /* REVIEW */
		$item_array =  array();
		$dir_handle = @opendir($item_path);
		$i = 0;
		while ($file = readdir($dir_handle)) 
		{
		    if (!in_array($file,$forbidden_listing) && (strpos($file,'uninstalled_')===false) && file_exists($item_path.$file)) 
			{
				$item_array[$i]['value'] = $file;
				$item_array[$i]['label'] = $file;
				$i++;
			}
		}
		if ($dir_handle) closedir($dir_handle);

		return $item_array;
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
		    if (!in_array($file,$forbidden_listing)  && (strpos($file,'uninstalled_')===false) && is_dir($item_path.$file)) $item_array[] = $file;
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
		$result1 = true;
 		
		//TODO: Sanitize $name string for uninstall
		if (in_array($name, $default_apps,TRUE)) return false;
		if ($_POST['sure']) 
		{
			$item_admin_path = SITE_PATH."/admin/{$this->app_name}/".$name;
			$item_admin_path_newname = SITE_PATH."/admin/{$this->app_name}/uninstalled_".$name;
			$item_path = SITE_PATH."/{$this->app_name}/".$name;
			$item_path_newname = SITE_PATH."/{$this->app_name}/uninstalled_".$name;
			// April 10, 2010
			
			if (file_exists($item_admin_path)) $result1 = rename($item_admin_path, $item_admin_path_newname);
			if (file_exists($item_path)) $result2 = rename($item_path, $item_path_newname);
			if ($_POST['everything'])
			{
				$this->delTree($item_admin_path_newname);
				$this->delTree($item_path_newname);
			}
			return ($result1 && $result2);
		}
		return false;
	}	
	
}
?>