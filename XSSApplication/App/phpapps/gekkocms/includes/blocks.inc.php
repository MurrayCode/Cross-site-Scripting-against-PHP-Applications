<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

class blocks extends basicApplicationSimpleCategories
{
	private $block_output = array();
	
    public function __construct()
    {
 		$data_items = createDataArray ('id','title','original_block','description','sort_order','status','date_modified','display_in_menu','permission_read');
		$data_categories = createDataArray ('cid','title','sort_order');		
		parent::__construct('blocks','Blocks',  'gk_block_items', 'id', $data_items, 'gk_block_categories', 'cid', $data_categories);
    }
	//_______________________________________________________________________________________________________________//	
 	public function getCurrentMenuId()
	{
		// TODO: expand/include subchild - Prana - Oct 1, 2010
		global $gekko_db;
		
		$base = "'".SITE_HTTPBASE."'";
		if (SITE_HTTPBASE == '') $base = "'/'";
		$url = sanitizeString($_SERVER['REQUEST_URI']);
 
		if ($_SERVER['REQUEST_URI'] == SITE_HTTPBASE.'/') $url = $base; // special exception for base Oct 11, 2010		
		$sql =  "SELECT id FROM gk_menu_items WHERE sefurl = {$url} OR internalurl = {$url}";
		$menu  = $gekko_db->get_query_singleresult($sql,true);

		if (!$menu)
		{
			 // test
			if ($_SERVER['REQUEST_URI'] == '/') $sql ="SELECT id FROM gk_menu_items WHERE sefurl='/'"; else
			$sql = "SELECT id FROM gk_menu_items WHERE (INSTR({$url},sefurl) > 0) AND (sefurl <> {$base}) ";
	//		echo $sql;
			$menu  = $gekko_db->get_query_singleresult($sql,true);
			if ($menu) return $menu['id']; else return false;
 		} else return $menu['id'];
//		if ($menu) return $menu['id']; else return false;
	}
	//_______________________________________________________________________________________________________________//	
 	public function displayBlockByPosition($category_name)
	{
		if ($this->block_output[$category_name])
		{
			foreach ($this->block_output[$category_name] as $block_item)
			{
				echo $block_item;	
			}
		}
	}
	//_______________________________________________________________________________________________________________//	
 	public function displaySingleBlock($block_name)
	{
		global $gekko_db, $gekko_current_user;
		
		$gk_block_config = new DynamicConfiguration('gk_block_config');

		$s = sanitizeString($block_name);
		$sql =  "SELECT * from {$this->table_items} WHERE (title = $s) AND status > 0";
		$block  = $gekko_db->get_query_singleresult($sql,true);
		if ($block && $gekko_current_user->hasReadPermission($block['permission_read']))
		{
			$original_block = $block['original_block'];
			$instance_name = $block['title'];
			include_block_class ($original_block);
			$instance_config = $gk_block_config->get($instance_name,'',true);
			$original_block.='Block';
			$the_block = new  $original_block($instance_name, $instance_config);
			$the_block->Run();
		} else
		{
			echo "Block {$block_name} cannot be found";
		}
	}
	//_______________________________________________________________________________________________________________//	
 	public function displayMainPage()
	{
		return false;
	}
	
	//_______________________________________________________________________________________________________________//	
	public function saveCategory($id)
	{
		$_POST['title'] = convert_into_sef_friendly_title($_POST['title']);
		$savestatus = parent::saveCategory($id);
		return $savestatus;
  	}
	
	
	//_______________________________________________________________________________________________________________//	
	public function saveItem($id)
	{
		$_POST['title'] = convert_into_sef_friendly_title($_POST['title']);

		$savestatus = parent::saveItem($id);
		return $savestatus;
  	}
	
	//_________________________________________________________________________//
	public function getBlockVisibility($id, $from_cache = false)
	{
		global $gekko_db;
		
		$sql = "SELECT menu_id FROM gk_block_menu_association WHERE {$this->field_id} = '".intval($id)."'";
 		if(!empty($sortby) && array_key_exists($sortby,$this->data_items)) $sql.= " ORDER BY {$sortby} {$sortdirection}";		
 		$visible_in_these_menus = $gekko_db->get_query_result($sql,$from_cache);
		return $visible_in_these_menus;
	}
	//_________________________________________________________________________//
	public function isBlockVisibleInThisMenuId($id, $menu_id, $from_cache = false)
	{
		global $gekko_db;
		
		$id = intval($id);
		$menu_id = intval ($menu_id);
  
		$visible_in_every_page = true;
		$sql = "SELECT display_in_menu FROM {$this->table_items} WHERE {$this->field_id} = '{$id}'";
		
		$block_visibility_config  = $gekko_db->get_query_singleresult($sql, $from_cache);
		if ($block_visibility_config['display_in_menu'] == 2) $visible_in_every_page = false;
		if (!$visible_in_every_page)
		{
			if ($menu_id == 0) return false;
			$sql = "SELECT menu_id FROM gk_block_menu_association WHERE {$this->field_id} = '{$id}' AND menu_id = '{$menu_id}'";
			$visible_in_these_menus = $gekko_db->get_query_result($sql,$from_cache);
			return (count($visible_in_these_menus) > 0);
		} else return true;
	}
	
	
	//_______________________________________________________________________________________________________________//	
	public function findDuplicateItems($data)
	{
		global $gekko_db;
		
		$current_id = $data[$this->field_id];
		$sql =  "SELECT * FROM {$this->table_items} WHERE (title = '{$data['title']}')";

		if (intval($current_id) != 0) $sql.= " AND (id != '{$current_id}')";
		
		$gekko_db->query($sql);
		$result  = $gekko_db->get_result_as_array();
		
		return $result;
	}
	//_______________________________________________________________________________________________________________//		
	public function Run($command)
	{
		global $gekko_db, $gekko_current_user;
		$gk_block_config = new DynamicConfiguration('gk_block_config');
		
		$sql = "SELECT {$this->field_category_id}, title FROM {$this->table_categories} ORDER by  {$this->field_category_id}";
 		$all_categories = $gekko_db->get_query_result($sql);		
		foreach ($all_categories as $category)
		{
			//////////////
			$blocks = $this->getItemsByCategoryID($category['cid'],'*','',0,0,'sort_order','ASC',true);
			
			foreach ($blocks as $block)
			{
 				if ($block['status'] > 0 && $gekko_current_user->hasReadPermission($block['permission_read'])) // don't wanna call displaysingleblock as it will increase call to the DB
				{					
					$original_block = $block['original_block'];
					$instance_name = $block['title'];					
					include_block_class ($original_block);
					$instance_config = $gk_block_config->get($instance_name,'',true);
					$original_block.='Block';
					$current_menu_id = $this->getCurrentMenuId();
					
					if (/*$current_menu_id !== false && */ $this->isBlockVisibleInThisMenuId($block['id'],$current_menu_id))
					{
						ob_end_clean();
						ob_start();
						
						$the_block = new  $original_block($instance_name, $instance_config);
						$the_block->Run();
						
						$block_output = ob_get_contents();
						ob_end_clean();
						$this->block_output[$category['title']][$instance_name] = $block_output;
						
					} 
				}
			}
			//////////////
		}
	}
	

}
	
?>