<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

class html extends basicApplicationNestedCategories // basicApplicationNestedCategories
{


    public function __construct()
    {
 		$data_items = createDataArray ('id','status','category_id','title','summary','description','virtual_filename','date_available','date_expiry','date_created','date_modified','sort_order','permission_write','permission_read','options','meta_key','meta_description','created_by_id','modified_by_id','pageview');
		$data_categories = createDataArray ('cid','status','items_per_page','parent_id','title','summary','description','sort_order','virtual_filename','date_available','date_expiry','date_created','date_modified','permission_write','permission_read','options','meta_key','meta_description');
		

		$this->user_editable_item_fields = array('id','status','category_id','title','summary','description','virtual_filename','date_available','date_expiry','date_created','date_modified','sort_order','meta_key','meta_description');
		
		parent::__construct('html','Web Pages','gk_html_items', 'id', $data_items, 'gk_html_categories', 'cid', $data_categories,'multiple_categories_items');
		
    }
	//_______________________________________________________________________________________________________________//		

 	public function displayMainPage()
	{
		$item = $this->getItemByVirtualFileName('home');
		if ($item)
			$this->displayItemByID($item[0]['id'],$this->cache);
	}
	//_______________________________________________________________________________________________________________//		
	public function getDefaultCategoryID()
	{
		return 0;
	}
	//_______________________________________________________________________________________________________________//		
	public function setItemStatusByID($id,$status)
	{
            global $gekko_db;
            
            $id = (int) $id;
            $status = (int) $status;
            if ($id > 0)
            {
                $sql = "UPDATE {$this->table_items} SET `status` = {$status} WHERE id={$id}";
                $gekko_db->query($sql);
            }
	}        
	
	//_______________________________________________________________________________________________________________//		
	public function setItemCategoryIDByItemID($id,$catid)
	{
            global $gekko_db;
            $id = (int) $id;
            $catid = (int) $catid;
            if ($id > 0)
            {            
                $sql = "UPDATE {$this->table_items} SET `category_id` = {$catid} WHERE id={$id}";
                $gekko_db->query($sql);
            }
	}        
        
	//_______________________________________________________________________________________________________________//	
        
	public function fullTextSearch($keyword,$sortby='',$sortdirection='ASC')
	{
		global $gekko_db;
 
 		$cleankeyword = sanitizeString  ("%{$keyword}%");
		$sql = "SELECT * FROM {$this->table_items} WHERE title LIKE {$cleankeyword} OR summary LIKE {$cleankeyword} OR description LIKE {$cleankeyword}";
		if(!empty($sortby) && in_array($sortby,$this->data_items)) $sql.= " ORDER BY {$sortby} {$sortdirection}";
 		$search_result = $gekko_db->get_query_result($sql,true);
		return $search_result;		
	}
	//_______________________________________________________________________________________________________________//	
	
	public function getSortableItemFields()
	{
		return array(
										array('value'=>'id', 'label'=>'Item ID'),
										array('value'=>'title','label'=>'Title'),
										array('value'=>'date_created','label'=>'Date Created'),
										array('value'=>'date_modified','label'=>'Date Modified'),
										array('value'=>'sort_order','label'=>'Sort Order')
										);
	}
	//_______________________________________________________________________________________________________________//	
	
	public function getSortableCategoryFields()
	{
		return  array(
										array('value'=>'cid', 'label'=>'Category ID'),
										array('value'=>'title','label'=>'Title'),
										array('value'=>'date_created','label'=>'Date Created'),
										array('value'=>'date_modified','label'=>'Date Modified'),
										array('value'=>'sort_order','label'=>'Sort Order')
										);
	}
	//_______________________________________________________________________________________________________________//	
	

	public function getCategoryMetaOptions()
	{
		return  array   (array ('value' => 'display_pagetitle','label' => 'Display category title' ),
										 array ('value' => 'display_category_author','label' => 'Display category\'s author'),
										 array ('value' => 'display_category_date_created','label' => 'Display category\'s date created'),
										 array ('value' => 'display_category_date_modified','label' => 'Display category\'s date modified'),
										 array ('value' => 'display_childcategories','label' => 'Display subcategories'),
										 array ('value' => 'display_items','label' => 'Display list of items'),
										 array ('value' => 'display_items_summary','label' => 'Display item\'s summary'),
										 array ('value' => 'display_items_author','label' => 'Display item\'s author'),
										 array ('value' => 'display_items_date_created','label' => 'Display item\'s date created'),
										 array ('value' => 'display_items_date_modified','label' => 'Display item\'s date modified'),
										 array ('value' => 'display_items_readmore_link','label' => 'Display "Read More" link'),
										 array ('value' => 'display_user_can_change_sort_options','label' => 'Visitors can change sort options'),
										 array ('value' => 'display_user_can_change_items_perpage','label' => 'Visitors can change items per page'),
										 array ('value' => 'display_show_page_impressions','label' => 'Show Page Impressions'),										 
										 array ('value' => 'categories_sortby','label' => 'Sort child categories by','options'=>
																											array(
																												array('value'=>'cid', 'label'=>'Category ID'),
																												array('value'=>'title','label'=>'Title'),
																												array('value'=>'date_created','label'=>'Date Created'),
																												array('value'=>'date_modified','label'=>'Date Modified'),
																												array('value'=>'sort_order','label'=>'Sort Order')
																											)										 
										 ),
										 array ('value' => 'categories_sortdirection','label' => 'Categories sort direction', 'options' => array( array('value'=>'desc','label'=>'Descending'), array('value'=>'asc','label'=>'Ascending')))	,
										 array ('value' => 'items_sortby','label' => 'Sort child items by','options'=>
																										 array(
																											array('value'=>'id', 'label'=>'Item ID'),
																											array('value'=>'title','label'=>'Title'),
																											array('value'=>'date_created','label'=>'Date Created'),
																											array('value'=>'date_modified','label'=>'Date Modified'),
																											array('value'=>'sort_order','label'=>'Sort Order')
																										)
										),
										 
										 array ('value' => 'items_sortdirection','label' => 'Items sort direction', 'options' => array( array('value'=>'desc','label'=>'Descending'), array('value'=>'asc','label'=>'Ascending'))),
										 


										 );	
		
	}
	
	public function getItemMetaOptions()
	{
		return 	array   (0 => array ('value' => 'display_pagetitle','label' => 'Display page title' ),
										 1 => array ('value' => 'display_items_summary_noread','label' => 'Display item\'s summary for users with no read access'),
										 2 => array ('value' => 'display_items_author','label' => 'Display item\'s author'),
										 3 => array ('value' => 'display_items_date_created','label' => 'Display item\'s date created'),
										 4 => array ('value' => 'display_items_date_modified','label' => 'Display item\'s date modified')
										 
										 );	

	}
//_______________________________________________________________________________________________________________//
 	public function interpretFriendlyURL($urlpath)
	{
		$default_indexes = array(SITE_HTTPBASE.'/',SITE_HTTPBASE.'/index.html',SITE_HTTPBASE.'/index.php');

		if ($this->app_name == 'html' && SEF_ENABLED && !$_GET['app']
			 && substr_count($urlpath,'/') == substr_count(SITE_HTTPBASE.'/','/')) // only for HTML. Do not derive this code.
		{
 			
			// && substr_count($urlpath,'/') == 1
			$parsedurl = $this->probeFriendlyURLDestination($urlpath);
			$url = $parsedurl['url'];
			$url_array = $parsedurl['url_array'];
			$pathonly = parse_url($urlpath, PHP_URL_PATH);

 			if (empty($parsedurl['url']) && empty($url_array) && !in_array( $urlpath, $default_indexes) && $pathonly[strlen($pathonly)-1]!= '/')
			{
				 $command['action'] == '404error';
				 return $command;
			} 
		}
		return parent::interpretFriendlyURL($urlpath);
	}
	//_______________________________________________________________________________________________________________//			
	protected function translateOptions($the_options)
	{
		echo 'WARNING - translateOptions in html.class.php has been moved to app_basic.class.php. Please use translateMetaOptions instead';
		parent::translateMetaOptions($the_options);
	}
	//_______________________________________________________________________________________________________________//		
/*	public function displayItemsInCategoryByID($id=1, $pg=1,$sortby='', $sortdirection='ASC', $from_cache = false,$standard_criteria = 'status=1')
	{
	} */
	//_______________________________________________________________________________________________________________//	
	public function Run($command)
	{		
		switch ($command['action'])
		{
			case 'viewitem': $this->displayItemByID($command['id'],$this->cache);break;
			case 'viewcategory': $this->displayItemsInCategoryByID($command['cid'],$command['pg'],'date_created','DESC',$this->cache);break;
		
			default: return parent::Run($command);	
		}
		return true;
	}

}
	
?>