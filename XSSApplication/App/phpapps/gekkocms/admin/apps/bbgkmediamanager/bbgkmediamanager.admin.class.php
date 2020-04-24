<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
$_GET['ajax'] = 1;
include_admin_inc('admin_basic_manager.class.php');


class bbgkMediaManager extends basicApplicationSimpleCategories
{
    public function __construct()
    {
 		$data_items = createDataArray ('id','title','original_block','description','sort_order','status','date_modified','display_in_menu');
		$data_categories = createDataArray ('cid','title','sort_order');		
		parent::__construct('bbgkmediamanager','File Manager', '', 'id', $data_items, '', 'cid', $data_categories);
    }
	//_______________________________________________________________________________________________________________//	
 	public function displayMainPage()
	{
		return false;
	}
}
	

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
class bbgkmediamanagerAdmin extends basicAdminManager {

//_________________________________________________________________________//    
    public function __construct()
    {
		// Data: Item
		parent::__construct ('bbgkmediamanager', true,array());
		$this->default_folders = array('images','media');
    }
	//_________________________________________________________________________//	
	public function findInstalledItem($title)
	{
		global $gekko_db;
		
		$current_id = $data[$this->field_id];
		$sql =  "SELECT * from {$this->table_items} WHERE (original_block = '{$title}')";
		$gekko_db->query($sql);
		$result  = $gekko_db->get_result_as_array();
		return $result;
	}
	//_________________________________________________________________________//    
	public function displayPageHeader()
	{
		
		basicAdministration::displayPageHeader();
		
		echo JAVASCRIPT("/admin/apps/{$this->app_name}/{$this->app_name}.js");
	}	
	//_________________________________________________________________________//	
    function recursiveDelete($str){
        if(is_file($str)) return @unlink($str);
        elseif(is_dir($str))
		{
            $scan = glob(rtrim($str,'/').'/*');
            foreach($scan as $index=>$path){
                recursiveDelete($path);
            }
            return @rmdir($str);
        }
    }	
//_______________________________________________________________________________________________________________//
	function delete($mixed_items_to_delete)
	{ // mixed item = categories + items
	//test case:
	//http://gekkocms/admin/index.php?page=html&ajax=1&action=ajax_delete&items=c3
	//http://gekkocms/admin/index.php?page=html&ajax=1&action=ajax_delete&items=c5,c6,c9,i4,i14
		global $gekko_db;
		$id = 1;
		$mixed_items_array = explode(',', $mixed_items_to_delete); // e.g: c5,c6,c9,i4,i14

		$cats_to_delete = NULL;
		$all_cats_to_delete = NULL;

		// Process sub-folders first

		foreach ($mixed_items_array as $mixed_item)
		{
			$current_id = substr($mixed_item,1);
			$thepos = strpos ($mixed_item, 'c');
			if ($thepos !== false && $thepos === 0)
			{
					$all_cats_to_delete[] = $current_id;

			} else // else if it's an item instead
			{
 					 $items_to_delete[] = $current_id;
			}
		}
		foreach ($items_to_delete as $f) if(is_file(SITE_PATH.$f)) unlink(SITE_PATH.$f);
		ajaxReply(200,'OK');
	}
	
	//_________________________________________________________________________//	
	public function getItemsByCategoryID($id,$start=0, $end=0, $sortby='', $sortdirection='ASC')
	{
 		global $gekko_config;
		$valid_image_extension_array = array('png','jpg','jpeg','gif');
		$cache_content = $gekko_config->get($this->app_name,'cache_content');
		$folder_array = array();
		if ($cache_content) foreach ($cache_content as $folder)
		{
			$cid = strval($folder['cid']);
			if ($cid == strval($id))
			$path = $folder['full_path'];
 		}
 
		$forbidden_listing = array('.','..','.svn','.cvs');
 		$thumbnail_url = '/cache/thumbnails';
		$curpath = str_replace(SITE_PATH,'',$path);
		$urlpath = str_replace('\\','',$curpath);
		if (!file_exists(SITE_PATH.$thumbnail_url.$curpath)) mkdir(SITE_PATH.$thumbnail_url.$curpath, 755, true);
 		$item_array =  array();
		$dir_handle = @opendir($path);
		
		if ($dir_handle)
		while ($file = readdir($dir_handle)) 
		{
		    if (!in_array($file,$forbidden_listing))
			if (is_file ($path.'/'.$file))
			{
				$item_array[] = array('id' =>$file, 'title' => $file, 'thumbnail' => removeMultipleSlashes($thumbnail_url.$urlpath.'/'.$file), 
				'full_path' => removeMultipleSlashes($urlpath.'/'.$file), 'size' =>round(filesize($path.'/'.$file)/1024.0,1), 
				'date_modified' =>  date ("Y-m-d", filemtime($path.'/'.$file)));
				if (!file_exists(SITE_PATH.$thumbnail_url.$curpath.'/'.$file))
				{
					$fp = pathinfo($file);
					$extension = strtolower($fp['extension']);
					if (in_array($extension,$valid_image_extension_array))
						createImageThumbnail($path.'/'.$file,SITE_PATH.$thumbnail_url.$curpath.'/'.$file,48,48);
				}
			} else
			if (is_dir ($path.'/'.$file))
			{
			/*	DO NOTHING $item_array[] = array('cid' =>$file, 'title' => $file, 'thumbnail' => SITE_HTTPBASE.'/admin/images/icon_category.png', 
				'full_path' => str_replace('//','/',$urlpath.'/'.$file).'/', 'size' =>round(filesize($path.'/'.$file)/1024.0,1), 
				'date_modified' =>  date ("Y-m-d", filemtime($path.'/'.$file)));*/
			}
		}
		if ($dir_handle) closedir($dir_handle);
		
		echo ajaxReply(200,$item_array);
	}
	//_________________________________________________________________________//	
	public function saveItem($id)
	{
		include_inc('dbconfig.inc.php');
		$data_for_block = $_POST;
		unset($data_for_block['submit']);
		$data_block_name = $data_for_block['title'];
		unset($data_for_block['title']);		
		unset($data_for_block['id']);	
		// foreach (array_keys($this->data_items) as $key) unset ($data_for_filter[$key]); <-- this is more efficient - TOFIX - Aug 8, 2010
		$block_config = new DynamicConfiguration('gk_block_config');
		foreach (array_keys($data_for_block) as $key) $block_config->set($data_block_name,$key,$data_for_block[$key]);
 		return parent::saveItem($id);
	}
	//_________________________________________________________________________//    
	public function searchString($keyword='',$start=0,$end=0,$sortby='', $sortdirection='ASC')
	{
		
		$all_folders = $this->search_files($keyword);
		echo ajaxReply(200,$all_folders);
	}	
	//_________________________________________________________________________//    	
	private function search_files($searchstring)
	{
 		$i = 1;
		$all_folders = array();
		
		foreach ($this->default_folders as $path)
		{
			$item_array = $this->read_recursive_directory($i, SITE_PATH.'/'.$path.'/',false,$keyword);
			foreach ($item_array as $item) if ($item['is_file'] == 1 && strpos($item['title'],$searchstring)!== false) $new_array[]  = $item;
			if ($new_array) $all_folders = array_merge($all_folders,$new_array);
			$i++;
		}
		return $all_folders;		
	}
	//_________________________________________________________________________// 	
	private function read_recursive_directory($parent_id = 0, $firstpath,$directory_only = true,$searchstring = '')
    {	//	June 11, 2011
 		$thumbnail_url = '/cache/thumbnails';
		$curpath = str_replace(SITE_PATH,'',$firstpath);
		$urlpath = str_replace('\\','',$curpath);
		$forbidden_listing = array('.','..','.svn','.cvs');
		$forbidden_listing = array_merge($this->forbidden_names, $forbidden_listing);
		$item_array =  array();
		$file_array = array();
		$dir_handle = @opendir($firstpath);
		$i = 1;
		if ($dir_handle)
		{
			while ($file = readdir($dir_handle)) 
			{
				if (!in_array($file,$forbidden_listing) && !$directory_only) 
				{
					if ($searchstring=='')
						$file_array[] = array('title' => $file, 'parent_id' => $parent_id, 'is_file' => 1, 'thumbnail' => $thumbnail_url.$urlpath.'/'.$file, 'full_path' => $urlpath.$file, 'size' =>round(filesize($firstpath.'/'.$file)/1024.0,1), 'date_modified' =>  date ("Y-m-d", filemtime($firstpath.'/'.$file)));
					else
					{
						if(strpos($file,$searchstring,0)!==false)	
							$file_array[] = array('title' => $file, 'parent_id' => $parent_id, 'full_path' => $firstpath.$file,'is_file' => 1, 'thumbnail' => $thumbnail_url.$urlpath.'/'.$file, 'full_path' => $urlpath.'/'.$file, 'size' =>round(filesize($path.'/'.$file)/1024.0,1), 'date_modified' =>  date ("Y-m-d", filemtime($path.'/'.$file)));
					}
				}
				if (!in_array($file,$forbidden_listing) && is_dir($firstpath.$file)) 
				{
					$cid = $i + ($parent_id * 10000); // max 100000 file per folder - June 18, 2011
					$full_path = $firstpath.$file;
					$item_array[] = array('title' => $file, 'cid' => $cid, 'parent_id' => $parent_id, 'full_path' => $full_path,'is_file' => 0);
					$i++;
					$nextlevel = $this->read_recursive_directory($cid,$firstpath.$file.'/',$directory_only);
					if (!$directory_only && !empty($file_array)) $item_array = array_merge($item_array,$file_array);
					$item_array = array_merge($item_array,$nextlevel);
				}
			}
		}
		if ($dir_handle) closedir($dir_handle);
		return $item_array;
    } 
	//_________________________________________________________________________// 	
	public function getFolders()
	{
		global $gekko_config;
		
 		$i = 1;
		foreach ($this->default_folders as $path)
		{
			$this_folder =  array('id' => $path,'title' => $path, 'cid' => $i, 'parent_id' => 0,  'full_path' => SITE_PATH.'/'.$path.'/');
			$all_folders[] = $this_folder;
			$item_array = $this->read_recursive_directory($i, SITE_PATH.'/'.$path.'/',true);	
			$all_folders = array_merge($all_folders,$item_array);
			$i++;
		}
		
		if ($all_folders)
		{
			$cache_content = $gekko_config->set($this->app_name,'cache_content', $all_folders);
			$cache_time = $gekko_config->set($this->app_name,'cache_time',time());
			
			//print_r($item_array);die;
			echo ajaxReply(200,$all_folders);
		}
		return false;
	}	
	//_________________________________________________________________________// 	
	public function createNewFolder()
	{
		$foldername = SITE_PATH.'/'.$_POST['newfolderstartpath'].$_POST['foldername'];
		
		if (!file_exists($foldername))
		{
			echo $foldername;
			if (mkdir($foldername, 755, false))
				echo ajaxReply(200,'Folder has been created');
			else
				echo ajaxReply(100,'Failed');
		} else
		{
			echo ajaxReply(100,'Cannot create folder');
		}
	}
	//_________________________________________________________________________// 	
	public function uploadFiles()
	{

 		$placeholder = SITE_PATH.'/'.$_POST['fileuploadpath'];
 		$multiplefile_names = $_FILES['filedata']['name'];
		for ($i = 0; $i < sizeof($multiplefile_names);$i++)
		{
			move_uploaded_file($_FILES['filedata']['tmp_name'][$i], $placeholder.strip_tags(basename($multiplefile_names[$i])));
			//echo "Moving {$_FILES['filedata']['tmp_name'][$i]} to {$placeholder}{$multiplefile_names[$i]}\n";
		}
 		
 		echo ajaxReply(200,'Upload OK');
		
	}
	
	//_________________________________________________________________________// 	
	public function Run()
	{
		switch ($_GET['action'])
		{
			case 'getallcategories':
			case 'getfolders': $this->getFolders();break;
			case 'newfolder': $this->createNewFolder();break;
			case 'setvisibility':$this->setBlockVisibility($_POST['id'], $_POST['menu_id'], $_POST['state']);break;
			case 'getvisibility': $this->getBlockVisibility(intval($_GET['id']));break;
			case 'upload':$this->uploadFiles();return false;break;
			default:parent::Run();	
		}
 	}
	
}
?>