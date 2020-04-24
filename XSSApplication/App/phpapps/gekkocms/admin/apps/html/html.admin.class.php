<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

include_app_class('html');
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
class htmlAdmin extends basicAdministrationNestedCategories {

//_________________________________________________________________________//    
    public function __construct()
    {
		$datatype = 'basicnestedcategory';
		$methods = array ('standard_main_app' => 'Main Application Page',
						  'standard_browse' => 'View a specific item/category'
						 );						 
		parent::__construct('html', $datatype, $methods);
		$this->data_config_keys = createDataArray('alias','str_title','chk_enable_pageview_stats','str_meta_keywords','str_meta_description','groups_allowed_for_backend_access');
		
    }
 	
	//_________________________________________________________________________//	
	public function saveItem($id)
	{
		global $gekko_current_admin_user;
		
		$current_admin_id =  $gekko_current_admin_user->getCurrentUserID();
		if ($id=='new') $field_admin_id = 'created_by_id'; else $field_admin_id = 'modified_by_id';
		$_POST[$field_admin_id] = intval($_POST[$field_admin_id]);
		if (!($_POST[$field_admin_id] > 0 && $_POST[$field_admin_id]!= $current_admin_id))
			$_POST[$field_admin_id] = $current_admin_id;
		$_POST['virtual_filename'] = convert_into_sef_friendly_title($_POST['virtual_filename']);
		if (empty($_POST['virtual_filename']) || ($_POST['virtual_filename']=='index')) $_POST['virtual_filename'] = 'item'.$_POST[$this->field_id];
		$_POST['virtual_filename'] = $this->app->preventDuplicateItemInThisCategoryByFieldName('virtual_filename',$_POST[$this->field_id],$_POST['virtual_filename']);
		if ($_POST['permission_read_everyone']) $_POST['permission_read'] = 'everyone';		
		$_POST['permission_read']  = serialize($_POST['permission_read']);		
		$_POST['permission_write']  = serialize($_POST['permission_write']);		
		$_POST['options'] = serialize($_POST['options']);
		$_POST['summary'] = convert_pasted_png_images_from_html_text($_POST['summary'],SITE_PATH.'/images/external/',SITE_HTTPBASE.'/images/external/');
		$_POST['description'] = convert_pasted_png_images_from_html_text($_POST['description'],SITE_PATH.'/images/external/',SITE_HTTPBASE.'/images/external/');		
		if ($this->app->getConfig('chk_convert_external_images'))
		{
			$_POST['summary'] = move_static_external_images_from_html_text($_POST['summary'],SITE_PATH.'/images/external/',SITE_HTTPBASE.'/images/external/');
			$_POST['description'] = move_static_external_images_from_html_text($_POST['description'],SITE_PATH.'/images/external/',SITE_HTTPBASE.'/images/external/');		
		}
 		return parent::saveItem($id);
	}
	
	//_________________________________________________________________________//	
	public function saveCategory($id)
	{
		global $gekko_current_admin_user;
		
		$current_admin_id =  $gekko_current_admin_user->getCurrentUserID();
		
		if ($id=='new') $field_admin_id = 'created_by_id'; else $field_admin_id = 'modified_by_id';		
		if (!($_POST[$field_admin_id] > 0 && $_POST[$field_admin_id]!= $current_admin_id))
			$_POST[$field_admin_id] = $current_admin_id;
		$_POST['virtual_filename'] = convert_into_sef_friendly_title($_POST['virtual_filename']);
		if (empty($_POST['virtual_filename']) || ($_POST['virtual_filename']=='index')) $_POST['virtual_filename'] = 'cat'.$_POST['cid'];
		$_POST['virtual_filename'] = $this->app->preventDuplicateCategoryInThisCategoryByFieldName('virtual_filename',$_POST[$this->field_category_id],$_POST['virtual_filename']);
		if ($_POST['permission_read_everyone']) $_POST['permission_read'] = 'everyone';		
		$_POST['permission_read']  = serialize($_POST['permission_read']);
		$_POST['permission_write']  = serialize($_POST['permission_write']);				
		$_POST['options'] = serialize($_POST['options']);
		$_POST['summary'] = convert_pasted_png_images_from_html_text($_POST['summary'],SITE_PATH.'/images/external/',SITE_HTTPBASE.'/images/external/');
		$_POST['description'] = convert_pasted_png_images_from_html_text($_POST['description'],SITE_PATH.'/images/external/',SITE_HTTPBASE.'/images/external/');		
		if ($this->app->getConfig('chk_convert_external_images'))
		{
			$_POST['summary'] = move_static_external_images_from_html_text($_POST['summary'],SITE_PATH.'/images/external/',SITE_HTTPBASE.'/images/external/');
			$_POST['description'] = move_static_external_images_from_html_text($_POST['description'],SITE_PATH.'/images/external/',SITE_HTTPBASE.'/images/external/');		
		}

		return parent::saveCategory($id);
	}
	//_________________________________________________________________________//			
	public function displayItemOptions($options)
	{
		if (empty($options)) $options = 'a:5:{i:0;s:17:"display_pagetitle";i:1;s:28:"display_items_summary_noread";i:2;s:20:"display_items_author";i:3;s:26:"display_items_date_created";i:4;s:27:"display_items_date_modified";}';
        $display_options = unserialize($options);
		$item_meta_options = $this->app->getItemMetaOptions();
		echo INPUT_MULTIPLECHECKBOX('options',$this->app->getItemMetaOptions(),$display_options, 'Display Options'); 
	}
	
	//_________________________________________________________________________//			
	public function getListOfUsers()
	{
		global $gekko_current_admin_user;
		
		$query = $_GET['query'];
		$all_users = $gekko_current_admin_user->getAllItems('*',"username LIKE '%{$query}%'");
		ajaxReply(200,$all_users);
	}
	
	//_________________________________________________________________________//			
	public function displayCategoryOptions($options)
	{
		if (empty($options)) $options = 'a:3:{i:0;s:17:"display_pagetitle";i:1;s:23:"display_childcategories";i:2;s:13:"display_items";}';
        $display_options = unserialize($options);

		$category_meta_options = $this->app->getCategoryMetaOptions();
		foreach ($category_meta_options as $option) if (!array_key_exists('options',$option)) $checkbox_options[] = $option; else $drop_down_options[] = $option;
		echo INPUT_MULTIPLECHECKBOX('options',$checkbox_options,$display_options, 'Display Options'); 		
		echo BR();		
		echo FIELDSET_start();
		echo LEGEND('Default Sort Options');
		foreach ($drop_down_options as $drop_down_option)
		{
		echo LABEL($drop_down_option['label'],INPUT_DROPDOWN("options[{$drop_down_option['value']}]",$drop_down_option['options'],$display_options[$drop_down_option['value']]));
		echo BR();
		}
		echo FIELDSET_end();
	}
	//_________________________________________________________________________//
	public function Run()
	{
		switch ($_GET['action'])
		{
			case 'getlistofusers':  $this->getListOfUsers();break;
			default:parent::Run();	
		}
	}
	//_________________________________________________________________________//			
	public function Uninstall()
	{
		return false;
	}
}
?>