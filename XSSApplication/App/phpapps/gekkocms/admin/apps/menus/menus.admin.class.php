<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

include_block_class('menus');

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
class menusAdmin extends basicAdministrationSimpleCategories {

//_________________________________________________________________________//    
    public function __construct()
    {
		parent::__construct('menus');
    }
	
	//_________________________________________________________________________//    
	public function getItemsByCategoryID($id,$start=0, $end=0, $sortby='', $sortdirection='ASC')
	{
		global $gekko_db;
		
		//echo json_encode($this->displayMenuByCategoryID($id));
		if ($id == 0) parent::getItemsByCategoryID($id,$start, $end, $sortby, $sortdirection);
		else echo ajaxReply('200',$this->app->displayMenuByCategoryID($id,'menuAdminCustomdisplayMenuItem',true));
	}
	
	
	public function displayPageHeader()
	{
		echo CSS('/admin/apps/menus/menus.css');
		parent::displayPageHeader();
 	}
	//_________________________________________________________________________//	
	public function saveItem($id)
	{
		global $gekko_current_admin_user;
		
		$current_admin_id =  $gekko_current_admin_user->getCurrentUserID();
		if ($_POST['permission_read_everyone']) $_POST['permission_read'] = 'everyone';				
		$_POST['permission_read']  = serialize($_POST['permission_read']);		
		$_POST['permission_write']  = serialize($_POST['permission_write']);		
 		return parent::saveItem($id);
	}
	
//_________________________________________________________________________//
	
	protected function getMenuItemParentCategoryForMenuItemSelection($appname,$item_or_cat_id)
	{
		if ($appname != 'external_link' && $appname != 'home')
		{
			include_app_class($appname);
			$temp_app = new $appname;
			$current_id = substr($item_or_cat_id,1); // e.g: c1, i1
			if (strpos ($item_or_cat_id, 'c') !== false)
			{
				if (method_exists($temp_app,'getCategoryByID'))
				{				
					$temp_cat = $temp_app->getCategoryByID($current_id);
					$parent_id = $temp_cat['parent_id'];
				}
			} else if (strpos ($item_or_cat_id, 'i') !== false)
			{
				if (method_exists($temp_app,'getItemByID'))
				{
					$temp_item = $temp_app->getItemByID($current_id);
					$parent_id = $temp_item['category_id'];			
				}
			}
			return $parent_id;
		}
	}

//_________________________________________________________________________//
	public function SortMenus($parent_id)
	{
		global $gekko_db;
		
		// Step 1 - determine which menu block this thing belongs to
		$current_cat_id = intval($_COOKIE[$this->app_name.'_currentCategory']);
		if ($current_cat_id == 0) return false;


		$sql = "SELECT {$this->field_id},sort_order FROM {$this->table_items} where category_id = {$current_cat_id} and parent_id = {$parent_id} order by parent_id, sort_order";
		//echo $sql;	
		$gekko_db->query($sql);
		$menu_items  = $gekko_db->get_result_as_array();
		$max_menu_items = sizeof($menu_items);
		for ($i = 0; $i < $max_menu_items; $i++)
		{
			$current_id = $menu_items[$i]['id'];
			$new_sort_order =  $i+1;
			$sql = "UPDATE {$this->table_items} SET sort_order = {$new_sort_order} WHERE id = {$current_id}";
			$gekko_db->query($sql);
		}

//		OLD CODE - don't delete - maybe useful again in different case
	/*	$sql = "SELECT DISTINCT parent_id from {$this->table_items} order by parent_id";	
		$gekko_db->query($sql);
		$array_parent_ids  = $gekko_db->get_result_as_array();
		foreach ($array_parent_ids as $parent_id) $parent_ids[] = $parent_id['parent_id'];
		// parents : 0, 3, 5, etc...
		*/
		//echo 'Sorting '.$catid;
		/*
			$current_parent_id = intval($catid);
			$sql = "SELECT id,sort_order FROM {$this->table_items} where category_id = {$catid} and parent_id = {$current_parent_id}";	
			$gekko_db->query($sql);
			$categories  = $gekko_db->get_result_as_array();
			$i=0;
			$maxcat = sizeof($categories);
			for ($i = 1; $i <= $maxcat; $i++)
			{
				$current_id = $categories[$i-1]['id'];
				$specified_sort_order = $_POST['sort_c'.$current_id];
				$categories[$i-1]['specified'] = $specified_sort_order;
			}
			
			foreach ($categories as $key => $row) {
				$cat_id[$key]  = $row['id'];
				$cat_sort_order[$key] = $row['sort_order'];
				$cat_specified[$key] = $row['specified'];
			}
			if ($cat_specified)
				array_multisort ($cat_specified, SORT_NUMERIC, SORT_ASC, $cat_id, SORT_NUMERIC, SORT_ASC, $cat_sort_order, SORT_NUMERIC, SORT_ASC);
			
			// Done, sort by the specified sort order, but set the sort order to the sort ordering instead of the number specified 
			for ($i = 0; $i < $maxcat; $i++)
			{
				$sortnumber = $i+1;
				if ($cat_sort_order[$i] != $sortnumber)
				{	
					$sql = "UPDATE {$this->table_items} SET sort_order={$sortnumber} WHERE id = {$cat_id[$i]}";
					$gekko_db->query($sql);
				}
			}*/
	}

	//_________________________________________________________________________//
	protected function getMultipleItemParentIDs ($cat_id)
	{
		global $gekko_db;
		$current_id = $cat_id;
		$last_id = -1;
	
		while ($last_id != 0)
		{
			$sql = "SELECT parent_id FROM {$this->table_items} where id = '{$current_id}'";
		
			$gekko_db->query($sql);
			$id_r = $gekko_db->get_result_as_array();
			$last_id = $id_r [0]['parent_id'];
			$all_parent_id [] = $last_id;
			$current_id = $last_id;
		}
		return $all_parent_id;
	}	

//_________________________________________________________________________//
	protected function setAllChildrenCategory($parentid, $new_category_id)
	{
		global $gekko_db;
					
		$sql = "SELECT * FROM {$this->table_items} where parent_id = {$parentid}";	
		$gekko_db->query($sql);
		$children  = $gekko_db->get_result_as_array();
		if ($children)
		{
			foreach ($children as $child)
			{
				$sql = "UPDATE {$this->table_items} SET category_id = {$new_category_id} where id = '{$child['id']}'";
				$gekko_db->query($sql);
				$this->setAllChildrenCategory($child['id'], $new_category_id);
			}
		}
	}
	//_________________________________________________________________________//
	public function getApplicationChoices()
	{ // mixed item = categories + items but doesn't apply in this case
		global $gekko_db;

		$forbidden_listing = array('.','..');
		
		$admin_path = SITE_PATH.'/admin/apps/';
		$app_path = SITE_PATH.'/apps/';
		$app_array =  array();
		$dir_handle = @opendir($app_path);
		$app_array[0]['value'] = 'home';
		$app_array[0]['label'] = 'Home';
		$i = 1;	
		while ($file = readdir($dir_handle)) 
		{
		    if (!in_array($file,$forbidden_listing) && file_exists($app_path.$file.'/'.$file.'.class.php') && file_exists($admin_path.$file.'/'.$file.'.admin.class.php'))
			{
				 $app_array[$i]['value'] = $file;
				 $app_array[$i]['label'] = ucwords($file);
				 // quick hack - sorry.
				 if ($file == 'html') $app_array[$i]['label'] = 'Web Pages';
				 $i++;
			}
		}
		 $app_array[$i]['value'] = 'external_link';
		 $app_array[$i]['label'] = 'External Link';
		 $i++;

		closedir($dir_handle);
		return $app_array;
	}

	//_________________________________________________________________________//	
	public function drawApplicationChoices($answer)
	{
		$question = $this->getApplicationChoices();
		//
		$inputname = 'application';
		$total_count = count ($question);
		$str = '';
		for ($i = 0; $i < $total_count; $i++)
		{
			$checked = ( ($answer) && ($answer == $question[$i]['value']));
			$value = $question[$i]['value'];
		//	$checkbox = INPUT_SINGLERADIOBOX ($inputname,$question[$i]['value'],$checked);
			/////
			$check_str  = " value=\"{$value}\" ";
			$jslink = "onclick=\"javascript:gekko_app.getMenuInformation(this.value,false)\"";
			if ($checked == true) $check_str.=' checked';
			if ($i == 0) $jslink.= " class=\"validate-one-required\" title=\"Please select an application\"";
			$checkbox = "<INPUT name=\"{$inputname}\" type=\"radio\"{$check_str} {$jslink}/>\n";
			/////
			$str.=  LABEL($question[$i]['label'],$checkbox,false);
			$str.= '<BR/>';
		}
		 $str = BR().H3 (MENUS_EDITOR_INSTRUCTION_STEP_2).$str;
		return $str;
	}	

	//_________________________________________________________________________//
	public function Move($mixed_items_to_move, $destination)
	{ // mixed item = categories + items but doesn't apply in this case
		global $gekko_db;
		
		// compatibility
				
		if (strpos($mixed_items_to_move,'i')!==false)
		{
			$mixed_items_to_move = str_replace('i','menu_',$mixed_items_to_move);
			$destination = str_replace('c',$this->_ajax_treenode_prefix,$destination);
		}
		$pos = strrpos($destination, "_");
		$destination_id=intval(substr($destination,$pos+1, strlen($destination)- $pos  ));
		$destination_position = substr ($destination,4, $pos-4);
		//Feb 18, 2012
		if (strpos($destination,$this->_ajax_treenode_prefix)!==false )
		{
			$strfolderpos = strlen($this->app_name.'gwp_');
			$destination_position = substr ($destination,$strfolderpos, $pos-$strfolderpos);
		}

		if (strpos($mixed_items_to_move,'folder') > 0)
		{
			ajaxReply(400, 'Cannot move menu block');
			return false;
		} else if ($destination_id == 0)
		{
			ajaxReply(400, 'Cannot move menu item to a non-existing menu block');
			return false;
			
		}
		{
			$mixed_items_array = explode(',', $mixed_items_to_move); 
			foreach ($mixed_items_array as $mixed_item)
			{ 
				$item = $mixed_item;
				$pos = strrpos($item, "_");
				$source_id=substr($item,$pos+1, strlen($item)- $pos  );
				$source_itemnumbers[] = $source_id;
			} // end foreach
		} //endif
		$error = false;
		$parent_id_array = $this->getMultipleItemParentIDs ($destination_id);
		foreach ($source_itemnumbers as $source_id)
			if (in_array($source_id, $parent_id_array)) $error = true;
		
		if (!$error)
		{
			$source_itemstrs = implode(",", $source_itemnumbers);
			$destination_item = $this->app->getItemByID($destination_id);
			//echo "moving {$source_itemstrs} to *{$destination_position}* of {$destination_id}";
			switch ($destination_position)
			{
				case 'top': $prev_sort_order = $destination_item['sort_order']-1;
							$sql = "UPDATE  {$this->table_items} SET parent_id = '{$destination_item['parent_id']}',sort_order = {$prev_sort_order} WHERE id IN  ({$source_itemstrs})";
							break;
				case 'bottom': $next_sort_order = $destination_item['sort_order']+1;
							   $sql = "UPDATE  {$this->table_items} SET parent_id = '{$destination_item['parent_id']}',sort_order = {$next_sort_order} WHERE id IN  ({$source_itemstrs})";
							   break;
				case 'leftfolder': $sql = "UPDATE  {$this->table_items} SET category_id = '{$destination_id}', parent_id = 0 WHERE id IN  ({$source_itemstrs})";
									foreach ($source_itemnumbers as $source_id) $this->setAllChildrenCategory($source_id, $destination_id);
								    break;
				case 'next':
				default: 
						if (array_search($destination_id,$source_itemnumbers,true)) return false;
						$sql = "UPDATE  {$this->table_items} SET parent_id = '{$destination_id}' WHERE id IN ({$source_itemstrs}) AND {$destination_id} NOT IN ({$source_itemstrs})";
						$this->SortMenus($destination_item['id']);break;	  
			}
			 $gekko_db->query($sql);
			 $this->SortMenus($destination_item['parent_id']);
			 ajaxReply(200, 'OK');
		} else ajaxReply(400, 'Your move operation request cannot be completed');
		
	}
	
	public function duplicateItem($source_item_id, $destination_catnumber)
	{
		global $gekko_db;
		
		//echo "Copy $source_item_id to $destination_catnumber\n";
		$item = $this->app->getItemByID($source_item_id);
		unset ($item['id']);
		$item['category_id']= $destination_catnumber;
		$item['title'].= '_copy';
		$item['parent_id'] = 0; // Dec 10, 2011 - reset to 0 and let the user reorder - if it's not set to zero, sub-items won't appear
		$sql_set_cmd = InsertSQL($item);
		$sql =  "INSERT INTO `{$this->table_items}` ".$sql_set_cmd;
	//	echo $sql;
		$gekko_db->query($sql);
	}

/*
	//_________________________________________________________________________//
	public function Copy($mixed_items_to_copy, $destination='')
	{ // mixed item = categories + items
		global $gekko_db;
	
		
		// Find parents of the destinations first to avoid relaps
		$pos = strrpos($destination, "_");
		$destination_catnumber=substr($destination,$pos+1, strlen($destination)- $pos  );
	//	$parent_id_array = $this->getParentIDs ($destination_catnumber);

		// mixed stuff
		$mixed_items_array = explode(',', $mixed_items_to_copy); 
		foreach ($mixed_items_array as $mixed_item)
		{
			$current_id = substr($mixed_item,1); // 11 is the next string after 
			if (strpos ($mixed_item, 'c') > -1)
			{
				$source_catnumbers[] = $current_id;
			} else // else if it's an item instead
			{
				$source_itemnumbers[] = $current_id;
			}
		} // end foreach
		// verify if there's a conflict
		$error = false;
		if ($source_itemnumbers)
		{
			foreach ($source_itemnumbers as $source_item_id)
			{
				$this->duplicateItem($source_item_id, $destination_catnumber);
			//	$this->recursiveCopyMenuItems($source_item_id, $destination_catnumber);
			}
		}
		ajaxReply(200,count($source_itemnumbers).' categories and '.count($source_itemnumbers).' items copied'); 
	} */
//_________________________________________________________________________//
	public function refreshMenuLinks()
	{
		$this->app->refreshAllMenuLinks(true);
	}
	
//_________________________________________________________________________//
	public function getMenuBlocks()
	{
		global $gekko_db;
		$sql = "SELECT * FROM {$this->table_categories} order by sort_order, cid";	
		$gekko_db->query($sql);
		$blocks  = $gekko_db->get_result_as_array();
		echo ajaxReply(200,$blocks);
	}
//_________________________________________________________________________//

 	public function Run()
	{
		switch ($_GET['action'])
		{
			case 'getmenublocks': $this->getMenuBlocks();break;			
			default: parent::Run();
		}
	}
}

//_________________________________________________________________________//
	function menuAdminCustomdisplayMenuItem($item)
	{
		$nbsp = "&nbsp;";
		$itemid = $item['id'];
		$chkbox = INPUT_SINGLECHECKBOX('chkselections[]',"i{$itemid}",false,false,'chkselections'.$itemid,"i{$itemid}");
		if (SEF_ENABLED) 
		{
			$url = $item['sefurl'];
		} else $url = $item['internalurl'];
		if ($item['application'] == 'external_link') $url = $item['customurl'];
		if ($item['status'] > 0)
		{
			$status_img_name = 'status_active';
			$status_action = '0';
		} else
		{
			$status_img_name = 'status_inactive';
			$status_action = '1';			
		}
		$title = SAFE_HTML($item['title']);
		
		$button_activate = A(get_css_sprite_img(16,$status_img_name,'','Activate/Deactivate'),"javascript:gekko_app.setMenuStatus('i{$item['id']}','{$status_action}')",'','');
		$button_preview  = A(get_css_sprite_img(16,'preview','','Preview '.$title),$url,"menu-{$id}",'menuitem','_blank',$title);
		
		$buttons = $button_activate.$nbsp.$button_preview;
		$top    = DIV_start("menutop_{$itemid}",'menutop').IMG(SITE_HTTPBASE."/admin/images/blank2px.gif",'top').DIV_end();
		$next   = DIV_start("menunext_{$itemid}",'menunext').$buttons.DIV_end();
		$bottom = DIV_start("menubottom_{$itemid}",'menubottom').IMG(SITE_HTTPBASE."/admin/images/blank2px.gif",'bottom').DIV_end();
		$clearboth = DIV_start('','clearboth').DIV_end();
		$editlink = A($item['title'],"index.php?app=menus&action=edititem&id={$item['id']}");
		$themenu =  DIV_start("menu_{$itemid}",'menu" title="Drag to rearrange the menu"').$editlink.DIV_end();
		$menudiv = $top.$chkbox.$themenu.$next.$clearboth.$bottom;
		return $menudiv;
	}
	

?>