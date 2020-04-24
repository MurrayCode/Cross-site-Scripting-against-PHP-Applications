<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
include_admin_inc('admin_basic_manager.class.php');
include_inc ('blocks.inc.php');
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
class blocksAdmin extends basicAdminManager {

//_________________________________________________________________________//    
    public function __construct()
    {
		// Data: Item
		parent::__construct ('blocks', true,array());
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
		$sql =  "SELECT * from {$this->table_items} WHERE (original_block = '{$title}')";
		$gekko_db->query($sql);
		$result  = $gekko_db->get_result_as_array();
		return $result;
	} 
	//_________________________________________________________________________//	
	public function cleanUpOrphanedItems($items_from_directory)
	{
		global $gekko_db; // yes this is a dup, but this is it for now
	
		for ($i=0;$i < $total_count = count($items_from_directory);$i++) $items_from_directory[$i] = "'".$items_from_directory[$i]."'";
		$existing_apps = implode(',',$items_from_directory);
		$sql =  "DELETE FROM `{$this->table_items}` WHERE original_block NOT IN ({$existing_apps})";
		$gekko_db->query($sql);
	}
	//_________________________________________________________________________//	
	public function installItem($filename)
	{
		global $gekko_db;
		
		$datavalues['title'] = $filename;
		$datavalues['original_block'] = $filename;
		$datavalues['category_id'] = $this->app->getDefaultCategoryID();			
		$sql_set_cmd = InsertSQL($datavalues);
		$sql =  "INSERT INTO `{$this->table_items}` ".$sql_set_cmd;
		$gekko_db->query($sql);
	}
//_________________________________________________________________________//
	public function processUninstall($name)
	{
		$default_apps = array('menus','customtext');
		if (in_array($name, $default_apps,TRUE)) return false;
		if ($_POST['sure']) 
		{
			$item_admin_path = SITE_PATH."/admin/{$this->app_name}/".$name;
			$item_admin_path_newname = SITE_PATH."/admin/{$this->app_name}/uninstalled_".$name;			
			$item_path = SITE_PATH."/{$this->app_name}/".$name;
			$item_path_newname = SITE_PATH."/{$this->app_name}/uninstalled_".$name;
			// April 10, 2010
			$result1 = rename($item_admin_path, $item_admin_path_newname);
			$result2 = rename($item_path, $item_path_newname);
			if ($_POST['everything'])
			{
				$this->delTree($item_admin_path_newname);
				$this->delTree($item_path_newname);
			}
			
			return ($result1 && $result2);
		}
		return false;
	}	
	//_________________________________________________________________________//	
	
	public function getItemFieldNamesForAjaxListing()
	{
		$default_field_name_for_listing = array('id', 'category_id', 'title',  'date_modified','status', 'original_block', 'sort_order');	
		return $default_field_name_for_listing;
 	}		
	
	//_________________________________________________________________________//	
	public function getItemsByCategoryID($id,$start=0, $end=0, $sortby='', $sortdirection='ASC')
	{
		$this->checkForUnregisteredItems();
		return parent::getItemsByCategoryID($id, $start, $end, $sortby, $sortdirection);
	}
	//_________________________________________________________________________//		
	public function saveCategory($id)
	{
		checkInvalidCSRFAndHaltOnError();
		parent::saveCategory($id);
	}
	//_________________________________________________________________________//	
	public function saveItem($id)
	{
		checkInvalidCSRFAndHaltOnError();
		include_inc('dbconfig.inc.php');
		$data_for_block = $_POST;
		unset($data_for_block['submit']);
		$data_block_name = $data_for_block['title'];
		unset($data_for_block['title']);		
		unset($data_for_block['id']);	
		// foreach (array_keys($this->data_items) as $key) unset ($data_for_filter[$key]); <-- this is more efficient - TOFIX - Aug 8, 2010
		$block_config = new DynamicConfiguration('gk_block_config');
		foreach (array_keys($data_for_block) as $key) $block_config->set($data_block_name,$key,$data_for_block[$key]);
		if ($_POST['permission_read_everyone']) $_POST['permission_read'] = 'everyone';				
		$_POST['permission_read']  = serialize($_POST['permission_read']);		
//		$_POST['permission_write']  = serialize($_POST['permission_write']);		
		
 		return parent::saveItem($id);
	}
	//_________________________________________________________________________// 	
	public function getMenus()
	{
		include_block_class('menus');
		$menu = new menus();
		$menu_blocks = $menu->getAllCategories();
		$total_menu_blocks = count($menu_blocks);
		for ($i = 0; $i < $total_menu_blocks;$i++)
		{
			$menu_blocks[$i]['id'] = 0;
			$menu_blocks[$i]['parent_id'] = 0;	
			$menu_blocks[$i]['category_id'] = 0;	
		}
		$menus = $menu_blocks;
		foreach ($menu_blocks as $menu_block)
		{
			$menu_items = $menu->getItemsByCategoryID($menu_block['cid']);
			$menus = array_merge($menus,$menu_items);
		}
		echo ajaxReply('200',$menus);
		return false;
	}
	//_________________________________________________________________________//
	public function getBlockVisibility($id)
	{
 		$visible_in_these_menus = $this->app->getBlockVisibility($id);
		echo ajaxReply('200',$visible_in_these_menus);
	}
	
	//_________________________________________________________________________//
	public function setBlockVisibility($id,$menu_id,$state)
	{
		//$status = $this->app->setItemCategory($id, $cid, $state == 'true');
		global $gekko_db;
		
		$id = intval ($id);
		$menu_id = intval ($menu_id);
		
		if ($id > 0 and $menu_id >= 0)
		{
			if ($state=='true')
			{
				//TODO: fix it by verifying menu id existance
			//	if ($this->getCategoryByID($menu_id) && $this->getItemByID($id))
					$sql = "INSERT IGNORE INTO gk_block_menu_association (id,menu_id) VALUES ($id, $menu_id)";
					$result = true;
					//echo $sql;
				//else
				//	$result =  false;
			}	
			else
				$sql = "DELETE FROM gk_block_menu_association WHERE id = {$id} AND menu_id = {$menu_id}";
			
			$gekko_db->query($sql);
			$result = true;
		}	
		$result = false;
		
		echo ajaxReply(200,$result);
	}
	//_________________________________________________________________________// 	
	public function Run()
	{
		switch ($_GET['action'])
		{
			case 'getmenus': $this->getMenus();break;
			case 'setvisibility':$this->setBlockVisibility($_POST['id'], $_POST['menu_id'], $_POST['state']);break;
			case 'getvisibility': $this->getBlockVisibility(intval($_GET['id']));break;
			default:parent::Run();	
		}
	}
	
}
?>