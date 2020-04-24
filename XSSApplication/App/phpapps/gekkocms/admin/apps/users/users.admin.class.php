<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

include_app_class('users');

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
class usersAdmin extends basicAdministrationMultipleCategories {

//_________________________________________________________________________//    
    public function __construct()
    {
		// Data: Item
		$datatype = 'basicmultiplecategory';
		$methods = array ('standard_main_app' => 'Main User Page',
						  'register' => 'Show Registration Form',
						  'login' => 'Login',
						  'logout' => 'Logout'
						 );
		
		$this->data_config_keys = createDataArray('chk_enable_adminfrontend_login','chk_enable_registration','chk_enable_captcha_user_registration','int_default_newuser_group_id','int_number_of_login_retry_before_captcha','groups_allowed_for_backend_login','groups_allowed_for_backend_access','force_ssl_authentication','force_ssl_admin_authentication');
		parent::__construct ('users',$datatype,$methods);
    }
	//_________________________________________________________________________//    
	public function displayPageHeader()
	{
		
		basicAdministration::displayPageHeader();
		echo JAVASCRIPT("/admin/apps/{$this->app_name}/{$this->app_name}.js");
	}
	
	
	//_________________________________________________________________________//    
	
	public function getItemFieldNamesForAjaxListing()
	{
		$default_field_name_for_listing = array('id', 'username', 'date_created', 'date_modified', 'date_last_logged_in','status');	
		return $default_field_name_for_listing;
 	}		
	//_________________________________________________________________________//    
	
	public function getCategoryFieldNamesForAjaxListing()
	{
		$default_field_name_for_listing = array('cid', 'groupname','status');	
		return $default_field_name_for_listing;
 	}		
	
	//_________________________________________________________________________//		
	public function getMenuInfo()
	{
		$answer['app_name'] = $this->app_name;
		$answer['data_type'] = $this->data_type;
		$answer['field_item_title'] = 'username';
		$answer['field_category_title'] = 'groupname';
		$answer['public_methods'] = $this->public_methods;
		ajaxReply(200,$answer);
	}
	
	protected function getUsersGroupArray()
	{
		$groups = $this->app->getAllCategories();
		foreach ($groups as $group)
		{
			$group_array[] = array('label'=>$group['groupname'],'value'=>$group['groupname']);
		}
		return $group_array;
	}
	//_________________________________________________________________________//	
	public function saveItem($id)
	{
		global $gekko_current_admin_user;
 		
		if (empty($_POST['username']) || ($_POST['username']=='index')) $_POST['username'] = 'item'.$_POST[$this->field_id];
		$_POST['username'] = $this->app->preventDuplicateItemInThisCategoryByFieldName('username',$_POST[$this->field_id],$_POST['username']);
 		$z = parent::saveItem($id);
		if ($id=='new')
		{
			$last_inserted = $z['id'];
			$cur_cat_id = intval($_COOKIE[$this->app_name.'_currentCategory']);
			if ($cur_cat_id == 0) 
			{
				$cur_cat_id = 1;
				$_COOKIE[$this->app_name.'_currentCategory'] = 1;
			}
 			$this->app->setItemCategory($last_inserted,$cur_cat_id,true);			
		}
		return $z;
	}
	
	//_________________________________________________________________________//    
	public function searchString($keyword='',$start=0,$end=0,$sortby='', $sortdirection='ASC')
	{
		global $gekko_db;

		$x = implode(',',$this->getItemFieldNamesForAjaxListing());
 		$y = implode(',',$this->getCategoryFieldNamesForAjaxListing());
 		$sql = "SELECT {$x} FROM {$this->table_items} WHERE username LIKE '%$keyword%' order by sort_order";
		$gekko_db->query($sql);
	
		$result_array  = $gekko_db->get_result_as_array();
		echo ajaxReply(200,$result_array);
	}
}
?>