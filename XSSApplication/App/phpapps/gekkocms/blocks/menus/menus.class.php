<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
	if (!defined('GEKKO_VERSION')) die();

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++//

function frontendCustomDisplayMenu($item)
{
	if (SEF_ENABLED) $menu_link = $item['sefurl'];
		else
	{
		switch ($item['application'])
		{
			case 'home':$menu_link = SITE_HTTPBASE.$item['internalurl'];break;
			case 'external_link':	$menu_link = $item['internalurl'];break;
			default: $menu_link = "index.php?{$item['internalurl']}";
		}

	}
	$menu_title = SAFE_HTML($item['title']);
	$the_target = '';
	if ($item['open_in_new_window'] ==1) $the_target = '_blank';
	if (SSL_ENABLED && $item['ssl_state']==2) $menu_link =SITE_HTTPS_URL.$menu_link; else
	if ($item['ssl_state'] == 1) $menu_link = SITE_HTTP_URL.$menu_link;
	$display = A($menu_title,$menu_link,"menu_id-{$item['id']}",'',$the_target,$menu_title);
	
	return $display;
}
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++//

class menusBlock extends basicBlock
{
	public function Run()
	{
		
		 $my_menu = new menus();
		 $menu_id = intval($this->config['int_menu_id']);
		 $css_prefix = $this->config['str_custom_class'];
		 $output = $my_menu->displayMenuByCategoryID($menu_id,'frontendCustomDisplayMenu',false);	
		 echo $output;
  }
}
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++//

class menus extends basicApplicationSimpleCategories
{
    public function __construct()
    {
		global $gekko_current_admin_user;
		
		$data_categories = createDataArray ('cid','title','sort_order');		
		$data_items = createDataArray ('id','status','category_id','parent_id','open_in_new_window','title','sort_order','application','menuaction','menuitem','internalurl','sefurl','customurl','permission_read','permission_write','ssl_state');
		parent::__construct('menus','Menus', 'gk_menu_items', 'id', $data_items, 'gk_menu_categories', 'cid', $data_categories );
		$this->backend_mode = ($gekko_current_admin_user != null);
		$this->setOutputHTMLElements();
		$this->refreshAllMenuLinks(false);
    }
	//_______________________________________________________________________________________________________________//	
	protected function setOutputHTMLElements()
	{
		if ($this->getConfig('int_menu_html_element') == 1 && !$this->backend_mode)
		{
			$this->element_ul = 'div';
			$this->element_li = 'div';
			
		} else
		{
			$this->element_ul = 'ul';
			$this->element_li = 'li';
		}
	}
//_______________________________________________________________________________________________________________//		

	public function refreshAllMenuLinks($force = false) // new, IIS fix only
	{
		global $gekko_db, $gekko_config;
		
		$httpbase = $gekko_config->get('system','httpbase'); // first time install only?
		if (($httpbase === null) || $force)
		{
			$sql = "SELECT * FROM {$this->table_items} order by id";	
			$gekko_db->query($sql);
			$menus =  $gekko_db->get_result_as_array();
			foreach ($menus as $menu)
			{
				switch ($menu['application'])
				{
					case 'home': if (SITE_HTTPBASE) $sefurl= SITE_HTTPBASE; else $sefurl='/';break;
					case 'external_link':$sefurl = $menu['customurl'];break;
					default:
						include_app_class($menu['application']);
						$temp_app = new $menu['application'];
						$sefurl = $temp_app->createFriendlyURL($menu['internalurl']);break;
				} 
				$sql = "UPDATE {$this->table_items} SET sefurl = '{$sefurl}' WHERE id = {$menu['id']}";
				$gekko_db->query($sql);
			}
			 $gekko_config->set('system','httpbase',SITE_HTTPBASE);
		}
	}
	
//_______________________________________________________________________________________________________________//	
	public function validateSaveItem($data)
	{
		return true;
	}
//_______________________________________________________________________________________________________________//	
	public function findDuplicateItems($data)
	{
		return false;
	}
//_________________________________________________________________________//
	public function traverseMenuItem($category_id, $item_parent_id,$display_menu_function,$display_invisible=false)
	{
		global $gekko_db, $gekko_current_user, $gekko_admin_current_user;

					
		$output = '';
		$category_id = intval($category_id);
		$item_parent_id = intval($item_parent_id);
		if ($item_parent_id > 0)
		{
		if ($display_invisible==false) $s = " AND status > 0"; else $s = '';

			$sql = "SELECT * FROM {$this->table_items} where parent_id = {$item_parent_id} and category_id = {$category_id} {$s} order by sort_order";	
			// Nov 29, 2011 - only enable cache if display invisible = true (meaning it's being accessed from the admin)
			$items = $gekko_db->get_query_result($sql,!$display_invisible); 
			if ($items)
			{
				$output.= UL_start();				
				foreach ($items as $item)
				{
				///	$can_read = $gekko_current_user->hasReadPermission($item['permission_read']);
 					$can_read = ($this->backend_mode) ? true : $gekko_current_user->hasReadPermission($item['permission_read']);
					if ($can_read)
					{
						$menulink_output = $display_menu_function($item);
						$child_output =$this->traverseMenuItem($category_id, $item[$this->field_id],$display_menu_function);
						$output.=LI($menulink_output.$child_output);
					}
				}
				$output.= UL_end();				
			}

		}
		return $output;
	}
	//_________________________________________________________________________//
	public function displayMenuByCategoryID($category_id, $display_menu_function, $display_invisible=false)
	{
		global $gekko_db, $gekko_current_user;
		// Get Menu Block
//					echo  $display_menu_function;die;

		if ($display_invisible==false) $s = " AND status > 0"; else $s = '';
		$sql = "SELECT * FROM {$this->table_items} where parent_id = 0 and category_id = {$category_id} {$s} order by sort_order";// fix sept 5, 2010
		// Nov 29, 2011 - only enable cache if display invisible = true (meaning it's being accessed from the admin)
		$main_menus = $gekko_db->get_query_result($sql,!$display_invisible); 
		if ($main_menus)
		{
			$output = "<{$this->element_ul} id=\"menublock_{$category_id}\" class=\"menublock\">";//UL_start('menublock_'.$category_id,'menublock');
			foreach ($main_menus as $menu)
			{
				$can_read = ($this->backend_mode) ? true : $gekko_current_user->hasReadPermission($menu['permission_read']);
				if ($can_read)
				{
					$menulink_output = $display_menu_function($menu);
					$child_output =$this->traverseMenuItem($category_id, $menu['id'],$display_menu_function,$display_invisible);
					$output.="<{$this->element_li}>{$menulink_output}{$child_output}</{$this->element_li}>";
				}
			}
			$output.=  "</{$this->element_ul}>"; //UL_end();
		} else $output = "This menu block is still empty.";
		return $output;
	}
//_______________________________________________________________________________________________________________//	
	public function saveItem($id)
	{
		global $gekko_db;
 		
		$data = $this->data_items;
		$datavalues = getVarFromPOST($data);

		$current_date_time = date ('Y-m-d H:i:s');
 		if (array_key_exists('date_created', $datavalues))
		{		
			if (($datavalues[$this->field_id] =='new') || (strtotime  ($datavalues['date_created']) == 0)) $datavalues['date_created'] = $current_date_time;
		}
		// Process menu types
		$datavalues['open_in_new_window'] = intval($_POST['open_in_new_window']);
		$datavalues['status'] = intval($_POST['status']); //Sept 5, 2010
		if ($datavalues['application'] == 'home')
		{
			$datavalues['internalurl'] = '/'.SITE_HTTPBASE;
			$datavalues['sefurl'] = '/'.SITE_HTTPBASE;
		} else
		if ( $datavalues['application'] == 'external_link')
		{
			$datavalues['internalurl'] = $datavalues['customurl'];
			$datavalues['sefurl'] = $datavalues['customurl'];
		} else		
		{
			switch ($datavalues['menuaction'])
			{
				case 'standard_main_app': 
							$datavalues['internalurl'] = "app={$datavalues['application']}";
				
				case 'standard_browse': 
							$itemid = intval(substr($datavalues['menuitem'],1,strlen($datavalues['menuitem'])-1));				
							if ($datavalues['menuitem'][0] == 'c') $action = "viewcategory&cid={$itemid}";
							else if ($datavalues['menuitem'][0] == 'i') $action = "viewitem&id={$itemid}";
							$datavalues['internalurl'] = "app={$datavalues['application']}&action={$action}";
							break;
				default:
				$datavalues['internalurl'] = "app={$datavalues['application']}&action={$datavalues['menuaction']}";break;
			}
			// load temp
			include_app_class($datavalues['application'] );
			$temp_app = new $datavalues['application'];
			
			// fix up quirks for non-SEF URL (internal)
			parse_str($datavalues['internalurl'],$tmp_url_array);
			$array_tmp_keys = array_keys($tmp_url_array); // separate it 
			foreach ($array_tmp_keys as $key)
			{
				$value = trim($tmp_url_array[$key]);
				if (empty($value)) unset ($tmp_url_array[$key]);
			}
			$datavalues['internalurl'] = http_build_query($tmp_url_array);
			////////////
			
			$datavalues['sefurl'] = $temp_app->createFriendlyURL($datavalues['internalurl']);
		}
		$datavalues['internalurl'] = removeMultipleSlashes($datavalues['internalurl']);
		$datavalues['sefurl'] = removeMultipleSlashes($datavalues['sefurl']);
		
 	    if ($datavalues[$this->field_id] =='new')
		{
			$data = createNewInsertData($data);
			if (!$this->findDuplicateItems($datavalues))
			{
				if ($this->validateSaveItem($datavalues))
				{
					$sql_set_cmd = InsertSQL($datavalues);
					$sql =  "INSERT INTO `{$this->table_items}` ".$sql_set_cmd;
					$gekko_db->query($sql);
					// set sort order
 					$last = $gekko_db->get_query_singleresult('SELECT LAST_INSERT_ID() as lastid');
					$sql =  "UPDATE {$this->table_items} SET sort_order=id WHERE {$this->field_id} = '{$last['lastid']}';";
					$gekko_db->query($sql);
					$retval['status'] = SAVE_OK; // Nov 16, 2011
					$retval['id'] = $gekko_db->last_insert_id();
				} else $reval['status'] = SAVE_INVALID_DATA;
			} else $reval['status'] = SAVE_DUPLICATE;
			return $retval;
		}
		else
		{
			if (!$this->findDuplicateItems($datavalues))
			{
				if ($this->validateSaveItem($datavalues))
				{
				
					$sql_set_cmd = UpdateSQL($datavalues);
					$id = $datavalues[$this->field_id];	
					$sql =  "UPDATE {$this->table_items} SET ".$sql_set_cmd." WHERE {$this->field_id} = '{$id}';";
					$gekko_db->query($sql);
					$retval['id'] = intval($id);					
					$retval['status'] = SAVE_OK; // Nov 16, 2011
				} else $reval['status'] = SAVE_INVALID_DATA;
			} else $reval['status'] = SAVE_DUPLICATE;
			return $retval;
		}
  	}
	
}
	
?>