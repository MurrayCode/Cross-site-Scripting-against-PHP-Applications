<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// manager for apps, blocks, filters, and templates
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

class basicAdminManager extends basicAdministrationSimpleCategories {

//_________________________________________________________________________//    
    public function __construct($managed_class_name, $has_categories, $forbidden_names)
    {
		parent::__construct ($managed_class_name);
		$this->managed_class_name = $managed_class_name;
		$this->forbidden_names = $forbidden_names;
    }
	//_________________________________________________________________________//	
	public function findInstalledItem($title)
	{
		global $gekko_db;
		
		$sql =  "SELECT * from {$this->table_items} WHERE (title = '{$title}')";
		$gekko_db->query($sql);
		$result  = $gekko_db->get_result_as_array();
		return $result;
	}
	//_________________________________________________________________________//	
	public function installItem($filename)
	{
		global $gekko_db;
		
		$datavalues['title'] = $filename;
		$sql_set_cmd = InsertSQL($datavalues);
		$sql =  "INSERT INTO `{$this->table_items}` ".$sql_set_cmd;
		$gekko_db->query($sql);
		// Child classes (app, blocks, filters, templates) install inherit here
	}
	//_________________________________________________________________________//	
	public function installItemFromUploadedFile()
	{
		// TODO: install from a ZIP file
	}
	//_________________________________________________________________________//	
	public function cleanUpOrphanedItems($items_from_directory)
	{
		global $gekko_db;
	
		for ($i=0;$i < $total_count = count($items_from_directory);$i++) $items_from_directory[$i] = "'".$items_from_directory[$i]."'";
		$existing_apps = implode(',',$items_from_directory);
		$sql =  "DELETE FROM `{$this->table_items}` WHERE title NOT IN ({$existing_apps})";
		$gekko_db->query($sql);
	}
	//_________________________________________________________________________//	
	public function checkForUnregisteredItems()
	{	
		$forbidden_listing = array('.','..','.svn','.cvs');
		$forbidden_listing = array_merge($this->forbidden_names, $forbidden_listing);
		
		$item_admin_path = SITE_PATH.'/admin/'.$this->managed_class_name.'/';
		$item_path = SITE_PATH.'/'.$this->managed_class_name.'/';
		$item_array =  array();
		$dir_handle = @opendir($item_admin_path);
		if ($dir_handle) while ($file = readdir($dir_handle)) 
		{
		    if (!in_array($file,$forbidden_listing) && is_dir($item_admin_path.$file) && (strpos($file,'uninstalled_')===false) && file_exists($item_path.$file)) $item_array[] = $file;
		}
		if ($dir_handle) closedir($dir_handle);

		if ($item_array)
		{
			$this->cleanUpOrphanedItems($item_array); // delete non-existant apps from db
			foreach ($item_array as $item)
			{
				$dups = $this->findInstalledItem($item);
				if (count($dups) == 0) $this->installItem($item);	
			}
		}
	}
	//_________________________________________________________________________//	
	public function saveItem($id)
	{
		/*include_inc('dbconfig.inc.php');
		$data_for_block = $_POST;
		unset($data_for_block['submit']);
		$data_block_name = $data_for_block['title'];
		unset($data_for_block['title']);		
		unset($data_for_block['id']);	
		$block_config = new DynamicConfiguration('gk_block_config');
		foreach (array_keys($data_for_block) as $key) $block_config->set($data_block_name,$key,$data_for_block[$key]);*/
 		return parent::saveItem($id);
	}
	//_________________________________________________________________________// 	
	public function Install()
	{
		//TODO: error checking and validation
		include_inc('pclzip/pclzip.lib.php');
 
		$targetdir = array('templates', 'apps', 'blocks', 'filters');
		$targetprefix = array('', 'app', 'block', 'filter');
	//	$file_to_open = 'C:/Users/prana/Sites/gekkocms_components/simplecomments.zip';
  		$filter_container = 

		$zipfile = $_FILES['zipfileupload'];
		if (is_array($zipfile))
		{
 			if ($zipfile['error'] == 0 && $zipfile['size'] > 0)
			{
				$zip = new PclZip($zipfile['tmp_name']);
			/*	if ($zip->extract(PCLZIP_OPT_PATH, SITE_PATH.'/templates/') == 0) {
					die("There was a problem. Please try again!");
				} else {
				   $this->returnToMainAdminApplication();
				}*/
			} else return false;
 		} else return false;
		//$zip = new PclZip($file_to_open);

		// 1. Determine the app name
		  if (($list = $zip->listContent()) == 0) {
			die("Error : ".$zip->errorInfo(true));
		  }
		  $array_app_name = array();
		  $array_directories = array();
		  $count = sizeof($list);
		  for ($i=0; $i < $count; $i++)
		  {
			if ($list[$i]['folder'] == true) 
			{
				$filename = $list[$i]['filename'];
				$dirnamestack = explode('/',$filename);
				$array_directories[] = $filename;
				$app_name = $dirnamestack[0];
				$array_app_name[$app_name]++;
			}
		  }
		  $maxcount = max ($array_app_name);
		  $the_app_name = array_search  ($maxcount,$array_app_name);
		//  $target.=$the_app_name.'/';
		// 2. Determine if all directory structure is correct  - TODO: fix inefficient detection
		  $str_filter = $the_app_name.'/filter_files/'. $the_app_name.'/';
		  $str_block = $the_app_name.'/block_files/'. $the_app_name.'/';
		  $str_app = $the_app_name.'/app_files/'. $the_app_name.'/';
		  if (in_array($str_app,$array_directories)) $ziptype = TYPEZIP_APP;
		  elseif (in_array($str_block,$array_directories)) $ziptype = TYPEZIP_BLOCK;
		  elseif (in_array($str_filter,$array_directories)) $ziptype = TYPEZIP_FILTER;
		  else $ziptype = TYPEZIP_TEMPLATES;
		 // 3. Extract
		  $targetpath = SITE_PATH.'/'.$targetdir[$ziptype].'/';
		  $prefix = $targetprefix[$ziptype];
		  $targetadminpath = SITE_PATH.'/admin/'.$targetdir[$ziptype].'/';
		// 4. Move directories to destination  
 		if ($ziptype != TYPEZIP_TEMPLATES  && $ziptype != TYPEZIP_INVALID)
		{
			if ($zip->extract(PCLZIP_OPT_PATH, $targetpath,
							  PCLZIP_OPT_BY_NAME, $the_app_name."/{$prefix}_files/",
							  PCLZIP_OPT_REMOVE_PATH, $the_app_name."/{$prefix}_files/") == 0) $errorstr = $zip->errorInfo(true);
			if ($zip->extract(PCLZIP_OPT_PATH, $targetadminpath,
							  PCLZIP_OPT_BY_NAME, $the_app_name."/{$prefix}admin_files/",
							  PCLZIP_OPT_REMOVE_PATH, $the_app_name."/{$prefix}admin_files/") == 0) $errorstr.= $zip->errorInfo(true);
		}
		$this->returnToMainAdminApplication();			 
		return true;
  }
	//_________________________________________________________________________// 	
	public function Upgrade()
	{
		return false;
	}
	//_________________________________________________________________________//
	public function processUninstall($name)
	{
		return false;
	}
	//_________________________________________________________________________//
    protected function delTree($dir) {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!$this->delTree($dir.DIRECTORY_SEPARATOR.$item)) return false;
        }
        return rmdir($dir);
    }	
	//_________________________________________________________________________//	
	public function Uninstall()
	{
		global $gekko_db;
				
		$title = convert_into_sef_friendly_title($_GET['name']);
		if ($this->findInstalledItem($title))
		{
			if ($_POST['sure']) $uninstall_result = $this->processUninstall($title);
	 		$filename = SITE_PATH."/admin/apps/{$this->app_name}/uninstall.template.php";
			if (file_exists($filename) )	include_once ($filename);
			
			if ($uninstall_result)
			{
				$sql =  "DELETE FROM `{$this->table_items}` WHERE title = '{$title}'";
				$gekko_db->query($sql);
			}
			
		} else
		{
			$error_title = $title.' is not a valid installed item';
			$error_description = 'Cannot proceed with uninstall process';
			include('error.template.php');
		}
	}
	//_________________________________________________________________________// 	
	public function Run()
	{
		switch ($_GET['action'])
		{
			case 'install': $this->Install();break;
			case 'upgrade': $this->Upgrade;break;
			case 'uninstall':  $this->Uninstall();break;
			default:parent::Run();	
		}
	}
	
}
?>