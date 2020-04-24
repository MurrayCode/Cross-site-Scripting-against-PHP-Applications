<?php 
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

interface interfaceAdministration 
{
	// display html
	public function displayPageHeader();
	public function displayMainPage();
	public function getMenuInfo();
	public function editConfig();
	public function saveConfig();
	public function validateSaveConfig($datavalues);
	public function RunInstallScript();
	public function RunUninstallScript($uninstall_db, $uninstall_everything);
	public function Run();
}

interface interfaceAdministrationLinearData extends interfaceAdministration 
{
	public function getItemByID($id);
	public function getAllItems($start=0, $end=0,$sortby='', $sortdirection='ASC');
	//public function genericSearch($fieldname, $keyword, $sortby='', $sortdirection='ASC');
	public function getItemFieldNamesForAjaxListing();	
	public function Copy($mixed_items_to_copy, $destination='');
	public function searchString($keyword='',$start=0,$end=0,$sortby='', $sortdirection='ASC');
	public function editItem($id);	
	public function saveItem($id);	
	public function Hide($str);
	public function Delete($str);
	public function updateField();
}

interface interfaceAdministrationSimpleCategories extends interfaceAdministrationLinearData 
{
	// display html
 	public function getFullPathByCategoryID($cat_id);
	public function Move($mixed_items_to_move, $destination);
  	public function getAllCategories($start=0, $end=0,$sortby='', $sortdirection='ASC');
	public function getCategoryByID($id);
	public function getCategoryFieldNamesForAjaxListing();	
	public function getItemsByCategoryID($id,$start=0, $end=0, $sortby='', $sortdirection='ASC');	
	public function editCategory($id);	
	public function saveCategory($id);
}

interface interfaceAdministrationNestedCategories extends interfaceAdministrationSimpleCategories 
{
 	public function getChildCategoriesByParentID($id,$start=0, $end=0, $sortby='', $sortdirection='ASC');
}

interface interfaceAdministrationMultipleCategories extends interfaceAdministrationNestedCategories 
{
}
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++//
class basicAdministration implements interfaceAdministration {
protected $app_name;
public $data_type;
public $public_methods;
	//_________________________________________________________________________//    
    public function __construct($app_name,$data_type=null, $public_methods=null)
    {
		
		$this->app_name = $app_name;
		$this->data_type = $data_type;
		$this->public_methods = array();
		$this->loadLanguageFile();
		if ($public_methods != null)
			foreach (array_keys($public_methods) as $key)
				$this->public_methods[]=array('action'=>$key,'description'=>$public_methods[$key]);
	}
	//_______________________________________________________________________________________________________________//
	public function loadGenericFile($script_path,$vars, $return_once_found = true)
	{
		$classes = get_class_ancestors($this);
		$script_path = cleanInput($script_path);
		foreach ($classes as $gekko_class)
		{
			$pos = strripos ($gekko_class,'admin');
			
			$theclass = strtolower(substr($gekko_class,0, $pos));
			$script_dir = SITE_PATH."/admin/apps/{$theclass}/";
			$filename = $script_dir.$script_path;
			if (is_file($filename))
			{
				if (is_array($vars)) extract($vars, EXTR_REFS);
				@include_once($filename);
				if ($return_once_found) return true;
			} 
		}
		return false;
	}
	//_________________________________________________________________________//    
	public function loadTemplateFile($script_path,$vars)
	{
		return $this->loadGenericFile($script_path.'.template.php', $vars, true);
	}
	//_________________________________________________________________________//    
	public function loadLanguageFile()
	{
		return $this->loadGenericFile("lang_".ADMIN_LANGUAGE.".php", '',false);
	}
	//_________________________________________________________________________//    
	public function CheckIfCurrentUserAllowedAccess()
	{
		global $gekko_config, $gekko_current_admin_user;

		$groups_allowed_for_backend_access = $gekko_config->get($this->app_name,'groups_allowed_for_backend_access');
		return ($gekko_current_admin_user->hasPermission($groups_allowed_for_backend_access));
	}
	//_________________________________________________________________________//    
	public function displayPageHeader()
	{
		$str = ($this->app) ?  $this->app->getApplicationDescription() : '';
		//$extra_title = ($this->page_title) ? $this->page_title.' ' : '';
		echo TITLE('babygekko - '.$str.' '.TXT_ADMINISTRATION);
		echo JAVASCRIPT_YUI2_COMBO();  
		echo JAVASCRIPT_GEKKO();
		echo JAVASCRIPT_GEKKO_ADMIN();
	}
	//_________________________________________________________________________//		
	public function getMenuInfo()
	{
		$answer['app_name'] = $this->app_name;
		$answer['data_type'] = $this->data_type;
		if ($this->app_description) $answer['app_description'] = $this->app_description;
/*		$answer['field_id'] = 
		$answer['field_category_id'] =  */
		$answer['public_methods'] = $this->public_methods;
		ajaxReply(200,$answer);
	}
	//_________________________________________________________________________//	
	public function editConfig()
	{
		if (!$this->loadTemplateFile('config', true)) include ('config.skeleton.php');
 	}
	//_________________________________________________________________________//	
	public function validateSaveConfig($datavalues)
	{
		return SAVE_OK;
	}
	//_________________________________________________________________________//	
	public function saveConfig()
	{
		global $gekko_config;
		$save_status = $this->validateSaveConfig($_POST);

		/* COMMENTED OUT - FOR BACKWARD COMPATIBILITY - FEB 25, 2012 - if (!validCSRFVerification()) die('Invalid CSRF Verification Token'); */
		if ($save_status==SAVE_OK)
		{
			if ($this->data_config_keys)
			{
				$new_config_keys = array_keys($this->data_config_keys);
				foreach ($new_config_keys as $key) $_POST[$key] = $_POST[$key];
			}
			else
				$new_config_keys = array_keys($_POST);
			//print_r($new_config_keys);die;
			foreach ($new_config_keys as $key)
			{
				$gekko_config->set($this->app_name,$key,$_POST[$key]);
			}
		}
		if (method_exists($this,'forceRefreshMenuLinks'))
			$this->forceRefreshMenuLinks();
		return $save_status;
 	}
	
	//_________________________________________________________________________//    
	public function displayMainPage()
	{
	
		$filename = SITE_PATH."/admin/apps/{$this->app_name}/mainpage.template.php";
		if (file_exists($filename) ) include_once ($filename); else include ('mainpage.skeleton.php');
	}
	//_________________________________________________________________________// 	
	public function displayError($errorcode)
	{
		switch ($errorcode)
		{
			case ACCESS_NOT_ALLOWED: $error_txt = 'Access not allowed. Please request access permission from your Website Administrator';break;
			case SAVE_DUPLICATE	  : $error_txt = 'Duplicate item found';break;
			case SAVE_INVALID_DATA: $error_txt = 'Invalid Data';break;
			default: $error_txt = "Save Item Error";break;
		}
		if ($_GET['ajax']==1) ajaxReply(400, 'Error: '.$errorcode.' '.$error_txt); else 
		include ('inline_error.template.php');
	}
	//_________________________________________________________________________// 	
	public function RunInstallScript()
	{
		$install_sql_script = SITE_PATH.'/admin/apps/'.$this->app_name.'/install.sql';
		
		if (file_exists($install_sql_script)) restoreMySQLBackupFromFile($install_sql_script);
	}
	//_________________________________________________________________________//	
	public function RunUninstallScript($uninstall_db, $uninstall_everything)
	{
		if ($uninstall_db)
		{
			$uninstall_sql_script = SITE_PATH.'/admin/apps/'.$this->app_name.'/uninstall.sql';
			restoreMySQLBackupFromFile($uninstall_sql_script);
		}
		if ($uninstall_everything)
		{
			// TODO: delete all files in this folder. Not very important at this stage
		}
		return true;
	}
	//_________________________________________________________________________// 	
	public function Run()
	{
		$this->CheckIfCurrentUserAllowedAccess();
		switch ($_GET['action'])
		{
			case 'getmenuinfo': $this->getMenuInfo();break;
			case 'editconfig': $this->lastSaveStatus = SAVE_OK;$this->editConfig();break;
			case 'saveconfig': $this->lastSaveStatus = $this->saveConfig(); if ($this->lastSaveStatus==SAVE_OK) $this->returnToMainAdminApplication();else $this->editConfig();break;
			case 'help':$this->Help();break;
			default:$this->displayMainPage();	
		}
	}
	//_________________________________________________________________________//    	
	public function returnToMainAdminApplication()
	{
		$url = SITE_HTTPBASE.'/admin/index.php?app='.$this->app_name;
		ob_end_clean();
		ob_start();
		header("Location: {$url}");
	}	
	//_________________________________________________________________________//	
	public function Help()
	{
		$filename = SITE_PATH."/admin/apps/{$this->app_name}/help.template.php";
		if (file_exists($filename)) include_once ($filename); else include ('help.skeleton.php');
	}
}
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++//
abstract class basicAdministrationLinearData extends basicAdministration  implements interfaceAdministrationLinearData
{
	protected $data_items;
	protected $table_items;
	protected $field_id;
	protected $app;
	//_________________________________________________________________________//	
    public function __construct($app_name,$data_type=null, $public_methods=null)
    {
		parent::__construct($app_name,$data_type, $public_methods);
		$this->app = new $app_name;
		$this->table_items = $this->app->getItemTableName();
		$this->data_items = $this->app->getItemFieldNames();
		$this->field_id =  $this->app->getFieldID(); 
		
    }
	//_________________________________________________________________________//    
	
	public function getItemFieldNamesForAjaxListing()
	{
		$default_field_name_for_listing = createDataArray('id', 'status', 'title', 'virtual_filename', 'date_available', 'date_created', 'date_modified', 'sort_order');	

 		$item_field_names_for_ajax_listing = array_intersect_key($this->data_items,$default_field_name_for_listing);
		return array_keys($item_field_names_for_ajax_listing);
	}
	//_________________________________________________________________________//    
	public function displayPageHeader()
	{
		parent::displayPageHeader();
		
		if ($_GET['action'] != 'edititem' && $_GET['action'] != 'newitem' && $_GET['action'] != 'editcategory' && $_GET['action'] != 'newcategory') 
		{
				//
				$admin_file = "/admin/apps/{$this->app_name}/{$this->app_name}.js";
				if (file_exists(SITE_PATH.$admin_file))
				echo JAVASCRIPT($admin_file);
		}
		else
		{
			
			$editor_js_file = "/admin/apps/{$this->app_name}/{$this->app_name}_editor.js";
			//			
			if (file_exists(SITE_PATH.$editor_js_file))
			{
				echo JAVASCRIPT("/admin/apps/{$this->app_name}/{$this->app_name}_editor.js");
			} else
			{
			
				echo JAVASCRIPT_TEXT("var app_name = '{$this->app_name}';");
				echo JAVASCRIPT("/admin/js/generic_editor.js");
			}
		}
		
	}
	//_________________________________________________________________________//
	public function getItemByID($id)
	{
		return $this->app->getItemByID($id);		
	}
	//_________________________________________________________________________//	
	public function searchString($keyword='',$start=0,$end=0,$sortby='', $sortdirection='ASC')
	{
		$cleankeyword = sanitizeString("%{$keyword}%");
		$criteria = "title LIKE {$cleankeyword}";
		
		$item_searchresult = $category_searchresult = array();
		$normalized_item_fields = implode(',',quote_array_of_field_names_for_query($this->getItemFieldNamesForAjaxListing()));
		
		$total_item_count = $this->app->getTotalItemCount($criteria);
		$items_per_page = min(HARDCODE_MAX_ROWLIMIT,$end - $start);
		$item_searchresult = $this->app->getAllItems($normalized_item_fields,$criteria,$start,$end,$sortby, $sortdirection,true,false);
		YUIDataSourceReply(200,$searchresult, $start, $end, $items_per_page, $total_category_count + $total_item_count, $sortby, $sortdirection);

	}
	//_________________________________________________________________________//    
	public function ajaxSaveItem()
	{
 		$status = 0; // set to false
		$retval = $this->saveItem($id);
		$answer = array ('newid'=>$retval['id'],'status'=>$retval['status']);
		echo ajaxReply('200',$answer);
	}
	
	
	//_________________________________________________________________________//
	public function Copy($mixed_items_to_copy, $destination='')
	{ // mixed item =  items
		global $gekko_db;
		
		$mixed_items_array = explode(',', $mixed_items_to_copy); 
		foreach ($mixed_items_array as $mixed_item)
		{
			$current_id = substr($mixed_item,1); // 11 is the next string after 
			$source_itemnumbers[] = $current_id;
		} // end foreach
		foreach ($source_itemnumbers as $source_item_id)
		{
			$item = $this->app->getItemByID($source_item_id);
			unset ($item['id']);
			$item['title'].= '_copy';
			$sql_set_cmd = InsertSQL($item);
			$sql =  "INSERT INTO `{$this->table_items}` ".$sql_set_cmd;
			$gekko_db->query($sql);
		}
		ajaxReply(200,count($source_itemnumbers));
	} 
	//_________________________________________________________________________//	
	
	public function getAllItems($start=0, $end=0,$sortby='', $sortdirection='ASC')
	{
		global $gekko_db;

		if (intval($start) > intval($end)) return ajaxReply(400,'Invalid Request');
		$items_per_page = min(HARDCODE_MAX_ROWLIMIT,$end - $start);
		$x = implode(',',quote_array_of_field_names_for_query ( $this->getItemFieldNamesForAjaxListing()));
		$total_item_count = $this->app->getTotalItemCount();
		$result_array_files = $this->app->getAllItems($fields,'',$start,$end,$sortby, $sortdirection, false);
		/*		
		if (!empty($sortby) && !empty($sortdirection))
		{
			$sortby = quote_field_name_for_query($sortby);
			$sortdirection = (strtoupper($sortdirection) == 'DESC') ? 'DESC' : 'ASC';
			$sort_txt = " ORDER BY {$sortby} {$sortdirection}"; // add comma because there's a parent_id
		}
		$items_per_page = min(HARDCODE_MAX_ROWLIMIT,$end - $start); // must not be over 1500 per page to prevent out of memory for servers with low mem
		if ($items_per_page == 0) $items_per_page = DATATABLE_MAX_ROW_PERPAGE; // default 15, prevent division by zero

		$x = implode(',',quote_array_of_field_names_for_query ( $this->getItemFieldNamesForAjaxListing()));
		$result_array_files = array();
		$item_start = max(0,$start);
		$item_limit = min($end - $start, $items_per_page);	
		$sql = "SELECT {$x} FROM {$this->table_items} {$sort_txt} limit {$item_start}, {$item_limit}";
		$gekko_db->query($sql);
		$result_array_files  = $gekko_db->get_result_as_array();*/
		
		YUIDataSourceReply(200,$result_array_files, $start, $end, $items_per_page,  $total_item_count, $sortby, $sortdirection);
	}
	//_________________________________________________________________________//	
	public function editItem($id)
	{
		global $gekko_current_admin_user;
		
		$hasWritePermission = true;
		$id = ($id == 'new') ? 'new' : intval ($id); 
		if ($id == 'new')
		{
			$item ['id'] = 'new';
			$item ['category_id'] = intval($_COOKIE[$this->app_name.'_currentCategory']);
		    $item['date_created'] = date ('Y-m-d H:i:s');
			$item['date_available'] = $item['date_created'];
			$item['date_modified'] =  $item['date_created'];
		} else
		{
 			$item = $this->getItemByID($id);
		}
		
		if ($id == 'new' || $item)
		{
			
			if ($item['id'] != 'new' && $this->app->itemColumnExists('permission_write'))
			{
				$hasWritePermission = $gekko_current_admin_user->hasWritePermission($item['permission_write']);
				if (!$hasWritePermission) echo H3('You do not have a write access to this category');
			}
			$filename = SITE_PATH."/admin/apps/{$this->app_name}/editoritem.template.php";
			if (file_exists($filename)) 
			{
				if ($hasWritePermission) include_once ($filename);
			} else echo ('Please provide an editor function');
		} else echo H3('Item does not exist');
		
 	}
	
	//_________________________________________________________________________//	
	
	public function duplicateItem($source_item_id, $destination_catnumber)
	{
		global $gekko_db;
		
		$item = $this->app->getItemByID($source_item_id);
		unset ($item['id']);
		$item['title'].= '_copy';
		$sql_set_cmd = InsertSQL($item);
		$sql =  "INSERT INTO `{$this->table_items}` ".$sql_set_cmd;
		$gekko_db->query($sql);
	}
	
	//_________________________________________________________________________//	
	public function saveItem($id)
	{
		
		$retval = $this->app->saveItem($id);
		if (method_exists($this,'forceRefreshMenuLinks')) $this->forceRefreshMenuLinks();		
		return $retval;
	}

	//_________________________________________________________________________//	
	protected function forceRefreshMenuLinks()
	{
		include_admin_class('menus');
		$menuadm = new menusAdmin;
		$menuadm->refreshMenuLinks(); // prana - may 24, 2010
	}

	//_________________________________________________________________________//
	function Delete($mixed_items_to_delete)
	{
		$this->app->delete($mixed_items_to_delete);
		ajaxReply(200,'OK');
	}

	//_________________________________________________________________________//
	public function Hide($str)
	{
		
	}
	//_________________________________________________________________________//	
	public function sortAll()
	{
			
	}
	//_________________________________________________________________________//
	public function updateField()
	{
		global $gekko_db;
		
		$thefield = $_POST['field'];
		$value = sanitizeString($_POST['value']);
		$record_id =  $_POST['id'];
		$record_id_num = intval(substr($record_id,1,strlen($record_id)-1));
		if (strpos ($value,'Time)')) 
		{
			$the_end = strpos($value,'00:00:00');
			$value = substr($value,5,$the_end-5);
			$value = strtotime($value);
			$value = sanitizeString(date('Y-m-d', $value));
		}		
		if (strpos($record_id,'i') !== false) 
		{
			$thetable = $this->table_items;
			$thecolumn = $this->field_id;
		} else
		{
			$thetable = $this->table_categories;
			$thecolumn = $this->field_category_id;			
		}
		$sql = "UPDATE {$thetable} SET {$thefield} = {$value} WHERE {$thecolumn} = {$record_id_num} ";
		$gekko_db->query( $sql); // 
		ajaxReply(200,'OK');
	}	
	//_________________________________________________________________________//
 	public function Run()
	{
		switch ($_GET['action'])
		{
 		 	case 'newitem':  $this->lastSaveStatus = SAVE_OK;$this->editItem('new');break;
			case 'edititem': $this->lastSaveStatus = SAVE_OK;$this->editItem($_GET['id']);break;
			case 'ajaxsaveitem':$this->ajaxSaveItem();break;			
			case 'sort': $this->SortDirectory($_GET['catid']);$this->displayMainPage();break;
			case 'delete': $this->Delete($_POST['items']);break;
			case 'updatefield': $this->updateField();break;
			case 'hide': $this->Hide($_POST['items']);break;
			case 'sort':$this->sortAll();break;
			case 'saveitem': $retval = $this->saveItem($_POST['id']);
							 $this->lastSaveStatus = $retval['status'];
							 if ($this->lastSaveStatus==SAVE_OK) $this->returnToMainAdminApplication();else $this->editItem($_POST['id']);break;
			case 'getallitems': $this->getAllItems($_GET['start'],$_GET['end'],$_GET['sortby'],$_GET['sortdirection']);break;
			case 'search': $this->searchString($_GET['keyword'],$_GET['start'],$_GET['end'],$_GET['sortby'],$_GET['sortdirection']);break;
			case 'copy': $this->Copy($_POST['items'], $_POST['destination']);break;				
			default: parent::Run();
		}
	}
	//_________________________________________________________________________//
	
}

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
abstract class basicAdministrationSimpleCategories extends basicAdministrationLinearData  implements interfaceAdministrationSimpleCategories
{
	//_________________________________________________________________________//
    public function __construct($app_name,$data_type=null, $public_methods=null)	
    {
		parent::__construct($app_name,$data_type, $public_methods);
		$this->table_categories = $this->app->getCategoryTableName();
		$this->data_categories = $this->app->getCategoryFieldNames();
		$this->field_category_id =  $this->app->getFieldCategoryID();
		$this->_ajax_treenode_prefix = $app_name.DEFAULT_AJAX_TREENODEID_PREFIX;
    }
	
	//_________________________________________________________________________//	
 	public function getFullPathByCategoryID($cat_id)
	{
		// TODO: path
	}
	//_________________________________________________________________________//    
	
	public function getItemFieldNamesForAjaxListing()
	{
		$default_field_name_for_listing = createDataArray('id', 'status', 'category_id', 'title', 'virtual_filename', 'date_available', 'date_created', 'date_modified', 'sort_order');	
 		$item_field_names_for_ajax_listing = array_intersect_key($this->data_items,$default_field_name_for_listing);
		return array_keys($item_field_names_for_ajax_listing);
 	}	
	//_________________________________________________________________________//    
	
	public function getCategoryFieldNamesForAjaxListing()
	{
		$default_field_name_for_listing = createDataArray('cid', 'status', 'title', 'sort_order', 'virtual_filename', 'date_available', 'date_created', 'date_modified');;	
 		$category_field_names_for_ajax_listing = array_intersect_key($this->data_categories,$default_field_name_for_listing);
		return array_keys($category_field_names_for_ajax_listing);
 	}	
 	//_________________________________________________________________________//    
	public function getItemsByCategoryID($id,$start=0, $end=0, $sortby='', $sortdirection='ASC')
	{
		global $gekko_db;

		$start = intval($start);
		$end = intval($end);
		$id = intval($id);
		$category_sortby = '';
		$item_sortby = '';		
		if ($start > $end) return ajaxReply(400,'Invalid Request');
		$items_per_page = min(HARDCODE_MAX_ROWLIMIT,$end - $start);
		$total_item_count = $this->app->getTotalItemCountByCategoryID($id,false);
		$total_category_count = $this->app->getTotalCategoryCount();
		$normalized_item_fields = implode(',',quote_array_of_field_names_for_query($this->getItemFieldNamesForAjaxListing()));
		$normalized_category_fields = implode(',',quote_array_of_field_names_for_query($this->getCategoryFieldNamesForAjaxListing()));

		$result_array_directories = array();
		$result_array_files = array();
		$category_start = $start;
		// directory always at the top
 		if ($id== 0)  // just get the categories
			$result_array = $this->app->getAllCategories($normalized_category_fields,'',$start,$end,$this->field_category_id, $sortdirection);
		else
			$result_array = $this->app->getAllItems($normalized_item_fields,"category_id = {$id}",$start,$end,$sortby, $sortdirection,false);
		YUIDataSourceReply(200,$result_array, $start, $end, $items_per_page,  $total_category_count + $total_item_count, $sortby, $sortdirection);
	}
	
	//_________________________________________________________________________//    
	public function ajaxSaveCategory()
	{
		$retval = $this->saveCategory($id);
		$answer = array ('newcid'=>$retval['id'],'status'=>$retval['status']);
		echo ajaxReply('200',$answer);
	}
	
 	//_________________________________________________________________________//    
	public function searchString($keyword='',$start=0,$end=0,$sortby='', $sortdirection='ASC')
	{
		global $gekko_db;

		$cleankeyword = sanitizeString("%{$keyword}%");
		$criteria = "title LIKE {$cleankeyword}";
		
		$item_searchresult = $category_searchresult = array();
		$normalized_item_fields = implode(',',quote_array_of_field_names_for_query($this->getItemFieldNamesForAjaxListing()));
		$normalized_category_fields = implode(',',quote_array_of_field_names_for_query($this->getCategoryFieldNamesForAjaxListing()));
		
		$total_item_count = $this->app->getTotalItemCount($criteria);
		$total_category_count = $this->app->getTotalCategoryCount($criteria);
		$items_per_page = min(HARDCODE_MAX_ROWLIMIT,$end - $start);
 		if ($total_category_count > $start)
		{
			$category_end = $start + min($total_category_count - $start, $items_per_page);
			$category_searchresult = $this->app->getAllCategories($normalized_category_fields,$criteria,$start,$category_end,$sortby, $sortdirection,true,false);
		}
		if ($end - $total_category_count > 0)
		{	
			$item_start = max(0,$start-$total_category_count);
			$item_end = $item_start + min($end - $total_category_count, $items_per_page);	
			$item_searchresult = $this->app->getAllItems($normalized_item_fields,$criteria,$item_start,$item_end,$sortby, $sortdirection,true,false);
		}
 		$searchresult = array_merge($category_searchresult,$item_searchresult);
		YUIDataSourceReply(200,$searchresult, $start, $end, $items_per_page, $total_category_count + $total_item_count, $sortby, $sortdirection);
	}
	//_________________________________________________________________________//    
	
	public function getAllCategories($start=0, $end=0,$sortby='', $sortdirection='ASC')
	{
		global $gekko_db;
		$order_parent_id = '';
		
		$start = intval($start);
		$end = intval($end);
		if ($start > $end) return ajaxReply(400,'Invalid Request');		
		if (empty($sortby)) $sortby = $this->field_category_id;
		$field_names_for_category_listing = $this->getCategoryFieldNamesForAjaxListing();
		$normalized_field_names = implode(',',quote_array_of_field_names_for_query( $field_names_for_category_listing));
		
		$category_array = $this->app->getAllCategories($normalized_field_names,'',$start,$end,$sortby, $sortdirection, false);
		
		/*
		//$total_item_count = $this->app->getTotalItemCount(false);
		if (!empty($sortby) && !empty($sortdirection))
		{
			$sortby = quote_field_name_for_query($sortby);
			$sortdirection = (strtoupper($sortdirection) == 'DESC') ? 'DESC' : 'ASC';
			$sort_txt = ",{$sortby} {$sortdirection}"; // add comma because there's a parent_id
		}
		
		$field_names_for_category_listing = $this->getCategoryFieldNamesForAjaxListing();
		$y = implode(',',$field_names_for_category_listing);
		if ($field_names_for_category_listing)
 			if (array_key_exists('parent_id', $field_names_for_category_listing)) $order_parent_id = 'parent_id,';
 //		$sql = "SELECT {$y} FROM  {$this->table_categories} order by {$parent_id} {$this->field_category_id}";
		$sql = "SELECT {$y} FROM  {$this->table_categories} order by {$order_parent_id}{$this->field_category_id}{$sort_txt}";		
		$gekko_db->query($sql);
		$category_array  = $gekko_db->get_result_as_array();
*/
		echo ajaxReply('200',$category_array);
	}
	
	public function getCategoryByID($id)
	{
		return $this->app->getCategoryByID($id);
	}
	
	//_________________________________________________________________________//	
	public function saveItem($id)
	{
		if ($_COOKIE[$this->app_name.'_currentCategory'] == 0)
		{
			setcookie($this->app_name.'_currentCategory',strval($this->app->getDefaultCategoryID()));
		}
		return parent::saveItem($id);
	}
	
	//_________________________________________________________________________//
	
	public function saveCategory($id)
	{
		$retval = $this->app->saveCategory($id);
		$this->forceRefreshMenuLinks();
		return $retval;
		
	}
	//_________________________________________________________________________//
	
	public function editCategory($id)
	{
		global $gekko_current_admin_user;
		
		$hasWritePermission = true;
		if ($id == 'new')
		{

			$category ['cid'] = 'new';
			$category ['parent_id'] = intval($_COOKIE[$this->app_name.'_currentCategory']);
		    $category['date_created'] = date ('Y-m-d H:i:s');
			$category['date_available'] =  $category['date_created'];
			$category['date_modified'] =  $category['date_created'];
			
		} else
		{
 			$category = $this->getCategoryByID($id);
 		}
		if ($id == 'new' || $category)
		{
			
			if ($category['cid'] != 'new' && array_key_exists('permission_write',$this->data_categories))
			{
				$hasWritePermission = $gekko_current_admin_user->hasWritePermission($category['permission_write']);
				if (!$hasWritePermission) echo H3('You do not have a write access to this category');
			}
			$filename = SITE_PATH."/admin/apps/{$this->app_name}/editorcategory.template.php";
			if (file_exists($filename)) 
			{
				if ($hasWritePermission) include_once ($filename);
			} else echo ('Please provide an editor function');
		} else echo H3('Category does not exist');
 	}
	
	//_________________________________________________________________________//
	protected function getParentIDs ($cat_id)
	{
		global $gekko_db;
		$current_id = $cat_id;
		$last_id = -1;
	
		while ($last_id != 0)
		{
			$sql = "SELECT parent_id FROM {$this->table_categories} where cid = '{$current_id}'";
		
			$gekko_db->query($sql);
			$id_r = $gekko_db->get_result_as_array();
			$last_id = $id_r [0]['parent_id'];
			$all_parent_id [] = $last_id;
			$current_id = $last_id;
		}
		return $all_parent_id;
	}	

	//_________________________________________________________________________//
	public function Move($mixed_items_to_move, $destination)
	{ // mixed item = categories + items
	//case 1: move file, case 2: move folder, case 3: move mixed files and folders
	//http://gekkocms/admin/index.php?page=html&ajax=1&action=ajax_move&item=c3&destination=c5
		global $gekko_db;
		
		$mixed_items_to_move = str_replace($this->_ajax_treenode_prefix,'c',$mixed_items_to_move);
		$mixed_items_to_move = str_replace(DEFAULT_AJAX_TREENODEID_PREFIX,'c',$mixed_items_to_move); // backward compatibility
		
		$destination = str_replace($this->_ajax_treenode_prefix,'c',$destination); // backward compatibility
		$destination = str_replace(DEFAULT_AJAX_TREENODEID_PREFIX,'c',$destination);
		
		/*if ($mixed_items_to_move[0] = 'i' && $destination[0] == 'i')
		{
			ajaxReply(400, "Invalid move operation - cannot move an item to another item" );
		}*/		
		// Validation 1: Is the person trying to copy a category to another category
		if  (empty ($mixed_items_to_move) || empty ($destination) )
		{
			ajaxReply(400,'Invalid move operation - empty source and/or destination');
			return false;
		}
		
		$pos = strrpos($destination, "_");
		$destination_cat_id=substr($destination,$pos+1, strlen($destination)- $pos  );
		
		// Validation 2: Is the person trying to move categories
		if ( (strpos ($mixed_items_to_move, 'c') !== false)  )
		{
			ajaxReply(400,'Invalid move operation - cannot move categories in Simple Categories');
			return false;
		}
		// Validation 3: Is the person trying to move a category to another category
		if ( (strpos ($mixed_items_to_move, 'i') !== false) && ($destination_cat_id == 0) )
		{
			ajaxReply(400,'Invalid move operation - cannot move an item to a non-existing category');
			return false;
		}
		
		$mixed_items_to_move_array = explode(',', $mixed_items_to_move); 
		foreach ($mixed_items_to_move_array as $mixed_item) $source_id_array[] = substr($mixed_item,1);
		// Case 1: If it's a duplicate category operation
		if( strpos ($mixed_items_to_move, 'i') !== false ) 
		{
			$source_itemstrs = implode(",", $source_id_array);
			$sql = "UPDATE  {$this->table_items} SET category_id = '{$destination_cat_id}' where id IN  ({$source_itemstrs})";
			if ($sql) $gekko_db->query($sql);
			ajaxReply('200','OK');			
		}
		
		
	/*	// Find parents of the destinations first to avoid relaps
		$pos = strrpos($destination, "_");
		$destination_catnumber=substr($destination,$pos+1, strlen($destination)- $pos  );
		$parent_id_array = $this->getParentIDs ($destination_catnumber);

		// Now process the source
		if (strpos($mixed_items_to_move,'folder') > 0)
		{
			$pos = strrpos($mixed_items_to_move, "_");
			$source_catnumber=substr($mixed_items_to_move,$pos+1, strlen($mixed_items_to_move)- $pos);
			if (in_array($source_catnumber, $parent_id_array))
				print "Incorrect user operation - cannot move this folder to its subfolder";
			else
				$sql = "UPDATE {$this->table_categories} SET parent_id = '{$destination_catnumber}' where cid = '{$source_catnumber}'";
			if ($sql) $gekko_db->query($sql);
			
		}
		else if (strpos($mixed_items_to_move, 'article') > 0)
		{
			$pos = strrpos($mixed_items_to_move, "_");
			$source_itemnumber=substr($mixed_items_to_move,$pos+1, strlen($mixed_items_to_move)- $pos );
			$sql = "UPDATE  {$this->table_items} SET category_id = '{$destination_catnumber}' where id = '{$source_itemnumber}'";
			if ($sql) $gekko_db->query($sql);
		}
		else 
		{
			// mixed stuff
			$mixed_items_array = explode(',', $mixed_items_to_move); 
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
			foreach ($source_catnumbers as $source_catnumber)
				if (in_array($source_catnumber, $parent_id_array)) $error = true;
			// now update parent_id

			if (!$error)
			{
				$source_catstrs = implode(",", $source_catnumbers);
				if ($source_catnumbers)
				{
					$sql = "UPDATE {$this->table_categories} SET parent_id = '{$destination_catnumber}' where cid in  ({$source_catstrs})";
					$gekko_db->query($sql);
				}
				// Now
				$source_itemstrs = implode(",", $source_itemnumbers);
				$sql = "UPDATE  {$this->table_items} SET category_id = '{$destination_catnumber}' where id in  ({$source_itemstrs})";
				$gekko_db->query($sql);

			} else print "Incorrect user operation - cannot move this folder to its subfolder";
		} // end mixed stuff*/
	}
	
	//_________________________________________________________________________//
	function duplicateCategory($catid, $destination_catnumber)
	{
		global $gekko_db;
					
		if ($catid > 0)
		{
			// 1. duplicate main category
			$category = $this->getCategoryByID($catid);
			unset ($category['cid']);
			$category['title'].= '_copy';
			$sql =  "INSERT INTO `{$this->table_categories}` ".InsertSQL($category);
			//echo $sql;
			$gekko_db->query($sql);
			$last_inserted_id = mysql_insert_id();
			$new_category_name = $this->app->preventDuplicateCategoryInThisCategoryByFieldName('title',$last_inserted_id, $category['title']);
			if ($new_category_name != $category['title'])
			{
				$new_category_name = sanitizeString($new_category_name);
				$sql =  "UPDATE `{$this->table_categories}` SET `title`={$new_category_name} WHERE `{$this->field_category_id}` = {$last_inserted_id}";
				$gekko_db->query($sql);
			}
			// 2. copy items - 0,0 means all
			$items_in_this_category = $this->app->getItemsByCategoryID($catid,'*','',0,0,$this->app->getFieldID());
			foreach ($items_in_this_category as $item)
			{
				$this->duplicateItem($item['id'], $last_inserted_id);
			}
		}
	}
	//_________________________________________________________________________//
	
	public function duplicateItem($source_item_id, $destination_catnumber)
	{
		global $gekko_db;
		
		//echo "Copy $source_item_id to $destination_catnumber\n";
		$item = $this->app->getItemByID($source_item_id);
		unset ($item['id']);
		$item['category_id']= $destination_catnumber;
		$item['title'].= '_copy';
		$sql_set_cmd = InsertSQL($item);
		$sql =  "INSERT INTO `{$this->table_items}` ".$sql_set_cmd;
	//	echo $sql;
		$gekko_db->query($sql);
	}
	
	//_________________________________________________________________________//
	public function Copy($mixed_items_to_copy, $destination='')
	{ // mixed item = categories + items
		global $gekko_db;
		
		// Validation 1: Is the person trying to copy a category to another category
		if  (empty ($mixed_items_to_copy) || empty ($destination) )
		{
			ajaxReply(400,'Invalid copy operation - empty source and/or destination');
			return false;
		}
		
		$pos = strrpos($destination, "_");
		$destination_cat_id=substr($destination,$pos+1, strlen($destination)- $pos  );
		
		// Validation 2: Is the person trying to copy *both* items & categories
		if ( (strpos ($mixed_items_to_copy, 'c') !== false) && (strpos ($mixed_items_to_copy, 'i') !== false) )
		{
			ajaxReply(400,'Invalid copy operation - cannot copy multiple items and categories in Simple Categories');
			return false;
		}
		// Validation 3: Is the person trying to copy an item to root directory
		if ( (strpos ($mixed_items_to_copy, 'c') !== false) && ($destination_cat_id > 0) )
		{
			ajaxReply(400,'Invalid copy operation - cannot copy a category to another category in Simple Categories');
			return false;
		}
		// Validation 4: Is the person trying to copy a category to another category
		if ( (strpos ($mixed_items_to_copy, 'i') !== false) && ($destination_cat_id == 0) )
		{
			ajaxReply(400,'Invalid copy operation - cannot copy an item to a non-existing category');
			return false;
		}
		
		$mixed_items_to_copy_array = explode(',', $mixed_items_to_copy); 
		foreach ($mixed_items_to_copy_array as $mixed_item) $source_id_array[] = substr($mixed_item,1);
		// Case 1: If it's a duplicate category operation
		if ( (strpos ($mixed_items_to_copy, 'c') !== false) && (strpos ($mixed_items_to_copy, 'i') === false) )
		{
			foreach ($source_id_array as $source_cat_id)
			{
				$this->duplicateCategory($source_cat_id, $destination_cat_id);
			}
			ajaxReply('200','OK');
		}
		// Case 2: If it's copying items another category or to this category
		else if( (strpos ($mixed_items_to_copy, 'c') === false) && (strpos ($mixed_items_to_copy, 'i') !== false) )
		{
			foreach ($source_id_array as $source_item_id)
			{
				$this->duplicateItem($source_item_id, $destination_cat_id);
			}
			ajaxReply('200','OK');			
		}
	} 

 	
	//_________________________________________________________________________//
	
 	public function Run()

	{
		switch ($_GET['action'])
		{
			case 'newcategory': $this->editCategory('new');break;			
			case 'editcategory': $this->editCategory($_GET['id']);break;
			case 'savecategory': $this->saveCategory($_POST['cid']);$this->returnToMainAdminApplication();break;
			case 'ajaxsavecategory':$this->ajaxSaveCategory();break;			
			case 'getallcategories': $this->GetAllCategories($_GET['start'],$_GET['end'],$_GET['sortby'],$_GET['sortdirection']);break;
			case 'search': $this->searchString($_GET['keyword'],$_GET['start'],$_GET['end'],$_GET['sortby'],$_GET['sortdirection']);break;
							// OLD    $this->searchString($_GET['keyword'], $_GET['detail']);break; 
			case 'getitemsbycategory': $this->getItemsByCategoryID($_GET['id'],$_GET['start'],$_GET['end'],$_GET['sortby'],$_GET['sortdirection']);break;
			case 'move': $this->Move($_POST['items'], $_POST['destination']);break;					
			case 'setcurrentcategory': $this->setCurrentCategoryID($_GET['id']);
			case 'getcurrentcategory': $this->getCurrentCategoryID();break;
			default: parent::Run();
		}
	}
}

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//


class basicAdministrationNestedCategories extends basicAdministrationSimpleCategories implements interfaceAdministrationNestedCategories 
{
	//_________________________________________________________________________//    
	
	public function getCategoryFieldNamesForAjaxListing()
	{
		$default_field_name_for_listing = createDataArray('cid', 'status', 'parent_id', 'title', 'sort_order', 'virtual_filename', 'date_available', 'date_created', 'date_modified');;	
		
 		$category_field_names_for_ajax_listing = array_intersect_key($default_field_name_for_listing,$this->data_categories);
		return array_keys($category_field_names_for_ajax_listing);
 	}	
	
	//_________________________________________________________________________//    
	public function getItemsByCategoryID($id,$start=0, $end=0, $sortby='', $sortdirection='ASC')
	{
		global $gekko_db;

		$id = intval($id);
		$start = intval($start);
		$end = intval($end);
		if ($start > $end) return ajaxReply(400,'Invalid Request');
		if (!empty($sortby) && !empty($sortdirection))
		{
			if ($sortby == $this->field_id || $sortby == $this->field_category_id) 
			{
				$category_sortby =  $this->field_category_id; // to correct cid/id merge in listing				
				$item_sortby =  $this->field_id; // to correct cid/id merge in listing								
			} else $item_sortby = $category_sortby = $sortby;
		}
		
		$items_per_page = min(HARDCODE_MAX_ROWLIMIT,$end - $start); // must not be over 1500 per page .. lol
		if ($items_per_page == 0) $items_per_page = DATATABLE_MAX_ROW_PERPAGE; // default 15, prevent division by zero
		$total_item_count = $this->app->getTotalItemCountByCategoryID($id);
		$total_category_count = $this->app->getTotalChildCategoryCountByCategoryID($id,false);
		$normalized_item_fields = implode(',',quote_array_of_field_names_for_query($this->getItemFieldNamesForAjaxListing()));
		$normalized_category_fields = implode(',',quote_array_of_field_names_for_query($this->getCategoryFieldNamesForAjaxListing()));

		$result_array_directories = array();
		$result_array_files = array();
		$category_start = $start;
		// directory always at the top
//		echo $item_sortby;die;
//		echo $category_sortby;die;
 		if ($total_category_count > $start)
		{
			$cat_end_limit = $category_start + min($total_category_count - $category_start, $items_per_page);	
			$result_array_directories = $this->app->getChildCategoriesByParentID($id,$normalized_category_fields,'',$category_start,$cat_end_limit,$category_sortby, $sortdirection);
		}
		if ($end - $total_category_count > 0)
		{
			$item_start = max(0,$start-$total_category_count);
			$item_limit = $item_start + min($end - $total_category_count, $items_per_page);	
			$result_array_files  = $this->app->getItemsByCategoryID($id,$normalized_item_fields,'',$item_start,$item_limit,$item_sortby, $sortdirection );
			
		}
 		$result_array = array_merge($result_array_directories,$result_array_files);
		
		YUIDataSourceReply(200,$result_array, $start, $end, $items_per_page,  $total_category_count + $total_item_count, $sortby, $sortdirection);
	}
	//_________________________________________________________________________//
	protected function getParentIDs ($cat_id)
	{
		global $gekko_db;
		$current_id = $cat_id;
		$last_id = -1;
	
		while ($last_id != 0)
		{
			$sql = "SELECT parent_id FROM {$this->table_categories} where cid = '{$current_id}'";
		
			$gekko_db->query($sql);
			$id_r = $gekko_db->get_result_as_array();
			$last_id = $id_r [0]['parent_id'];
			$all_parent_id [] = $last_id;
			$current_id = $last_id;
		}
		return $all_parent_id;
	}	

	//_________________________________________________________________________//
	function duplicateCategory($catid, $destination_catnumber)
	{
		global $gekko_db;
					
		if ($catid > 0)
		{
			// 1. duplicate main category
			$category = $this->getCategoryByID($catid);
			unset ($category['cid']);
			$category['parent_id']= $destination_catnumber;
			$category['title'].= '_copy';
			$sql_set_cmd = InsertSQL($category);
			$sql =  "INSERT INTO `{$this->table_categories}` ".$sql_set_cmd;
			$gekko_db->query($sql);
			$last_inserted_id = mysql_insert_id();

			// 2. copy items
			$items_in_this_category = $this->app->getItemsByCategoryID($catid);

			foreach ($items_in_this_category as $item)
			{
//				echo "Copying {$item['title']} to {$last_inserted_id}";
				$this->duplicateItem($item['id'], $last_inserted_id);
			}
			// 3. find children
 			$child_categories = $this->app->getChildCategoriesByParentID($catid);
			$i=0;
			while ($i < sizeof($child_categories))
			{
				$current_id = $child_categories[$i]['cid'];$i++;
				$this->duplicateCategory($current_id, $last_inserted_id);
 			}  
		}
	//		return $categories;
	}

	//_________________________________________________________________________//	
 	public function getChildCategoriesByParentID($id,$start=0, $end=0, $sortby='', $sortdirection='ASC')
	{
		return $this->app->getChildCategoriesByParentID($id,'*','',$start, $end, $sortby, $sortdirection);
	}
	
	//_________________________________________________________________________//
	public function Copy($mixed_items_to_copy, $destination='')
	{ // mixed item = categories + items
		global $gekko_db;
		
		// Find parents of the destinations first to avoid relaps
		$pos = strrpos($destination, "_");
		$destination_catnumber=substr($destination,$pos+1, strlen($destination)- $pos  );
		$parent_id_array = $this->getParentIDs ($destination_catnumber);

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
		if ($source_catnumbers)
		foreach ($source_catnumbers as $source_catnumber)
			if (in_array($source_catnumber, $parent_id_array)) $error = true;
		// now update parent_id

		if (!$error)
		{
			// Copy folders		
			
			if ($source_catnumbers)
			{
				foreach ($source_catnumbers as $source_cat_id)
				{
					$this->duplicateCategory($source_cat_id, $destination_catnumber);
				}
			}
			// Copy items
 			if ($source_itemnumbers)
			{
				foreach ($source_itemnumbers as $source_item_id)
				{
					$this->duplicateItem($source_item_id, $destination_catnumber);
				}
			}
//			$sql = "UPDATE  {$this->table_items} SET category_id = '{$destination_catnumber}' where id in  ({$source_itemstrs})";
			//$gekko_db->query($sql);
			ajaxReply(200,count($source_itemnumbers));
		} else ajaxReply(400,"Incorrect user operation - cannot move this folder to its subfolder");
	} 
	
	//_________________________________________________________________________//
	public function Move($mixed_items_to_move, $destination)
	{ // mixed item = categories + items
	//case 1: move file, case 2: move folder, case 3: move mixed files and folders
	//http://gekkocms/admin/index.php?page=html&ajax=1&action=ajax_move&item=c3&destination=c5
	
		global $gekko_db;
		
		//tofix
		// Find parents of the destinations first to avoid relaps
		$pos = strrpos($destination, "_");
		$destination_catnumber=substr($destination,$pos+1, strlen($destination)- $pos  );
		$parent_id_array = $this->getParentIDs ($destination_catnumber);

		// Now process the source
	
		if ($destination[0] == 'i')
		{
			ajaxReply(400, "Incorrect user operation - an item cannot be set as a destination for move operation" );
		} elseif (strpos($mixed_items_to_move,'folder') > 0)
		{
			$pos = strrpos($mixed_items_to_move, "_");
			$source_catnumber=substr($mixed_items_to_move,$pos+1, strlen($mixed_items_to_move)- $pos);
			if ($source_catnumber == $destination_catnumber)
			{
				ajaxReply(400, "Incorrect user operation - cannot move this folder to the same folder" );
			} else
			
			if (array_search($source_catnumber, $parent_id_array, TRUE) !== false) // April 1, 2010
				ajaxReply(400, "Incorrect user operation - cannot move this folder to its subfolder" );
			else
				$sql = "UPDATE {$this->table_categories} SET parent_id = '{$destination_catnumber}' where cid = '{$source_catnumber}'";
			if ($sql)
			{
				$gekko_db->query($sql);
				ajaxReply(200,'OK');
			}
		}
		else if (strpos($mixed_items_to_move, 'article') > 0)
		{
			$pos = strrpos($mixed_items_to_move, "_");
			$source_itemnumber=substr($mixed_items_to_move,$pos+1, strlen($mixed_items_to_move)- $pos );
			$sql = "UPDATE  {$this->table_items} SET category_id = '{$destination_catnumber}' where id = '{$source_itemnumber}'";
			if ($sql) $gekko_db->query($sql);
		}
		else 
		{
			// mixed stuff
			$mixed_items_array = explode(',', $mixed_items_to_move); 
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
			if ($source_catnumbers)
			
				foreach ($source_catnumbers as $source_catnumber)
					if (in_array($source_catnumber, $parent_id_array) || $source_catnumber == $destination_catnumber) $error = true;
			// now update parent_id

			if (!$error)
			{
				if ($source_catnumbers)
				{
					$source_catstrs = implode(",", $source_catnumbers);
					$sql = "UPDATE {$this->table_categories} SET parent_id = '{$destination_catnumber}' where cid in  ({$source_catstrs})";
					$gekko_db->query($sql);
				}
				// Fix - Dec 6, 2011 - forgot to add if
				if ($source_itemnumbers)
				{
					$source_itemstrs = implode(",", $source_itemnumbers);
					$sql = "UPDATE  {$this->table_items} SET category_id = '{$destination_catnumber}' where id in  ({$source_itemstrs})";
					$gekko_db->query($sql);
				}
				
				ajaxReply(200,'OK');

			} else ajaxReply(400, "Incorrect user operation - cannot move this folder to its subfolder");
		} // end mixed stuff
	}
	//_________________________________________________________________________//    
	
	public function getAllCategories($start=0, $end=0,$sortby='', $sortdirection='ASC')
	{
		global $gekko_db;
		$order_parent_id = '';
		
		$start = intval($start);
		$end = intval($end);
		if ($start > $end) return ajaxReply(400,'Invalid Request');		
		if (empty($sortby)) $sortby = $this->field_category_id;
		$field_names_for_category_listing = $this->getCategoryFieldNamesForAjaxListing();
		$normalized_field_names = implode(',',quote_array_of_field_names_for_query( $field_names_for_category_listing));
		
		$category_array = $this->app->getAllCategories($normalized_field_names,'',$start,$end,$sortby, 'ASC', false);
		echo ajaxReply('200',$category_array);
	}

	//_________________________________________________________________________//
	
 	public function Run()

	{
		switch ($_GET['action'])
		{
			case 'getallcategories': $this->GetAllCategories($_GET['start'],$_GET['end'],"parent_id,{$this->field_category_id}",'ASC');break;
			default: parent::Run();
		}
	}
	//_________________________________________________________________________//
	
}

class basicAdministrationMultipleCategories extends basicAdministrationNestedCategories implements interfaceAdministrationMultipleCategories 
{
	//_________________________________________________________________________//
    public function __construct($app_name,$data_type=null, $public_methods=null)	
    {
    	parent::__construct($app_name,$data_type, $public_methods);
		$this->data_categories_items = $this->app->getItemsToCategoryFieldNames();
		$this->table_categories_items =  $this->app->getItemsToCategoryTableName();
    }

	//_________________________________________________________________________//    
	
	public function getItemFieldNamesForAjaxListing()
	{
		$default_field_name_for_listing = createDataArray('id', 'status', 'title', 'virtual_filename', 'date_available', 'date_created', 'date_modified', 'sort_order');	
 		$item_field_names_for_ajax_listing = array_intersect_key($this->data_items,$default_field_name_for_listing);
		return array_keys($item_field_names_for_ajax_listing);
	} 
	//_________________________________________________________________________//
	
	public function duplicateItem($source_item_id, $destination_catnumber)
	{
		global $gekko_db;
		$source_item_id = intval($source_item_id);
		$item = $this->app->getItemByID($source_item_id);
		unset ($item['id']);
		$item['title'].= '_copy';
		$sql_set_cmd = InsertSQL($item);
		$sql =  "INSERT INTO `{$this->table_items}` ".$sql_set_cmd;
		$gekko_db->query($sql);
		$new_id = $gekko_db->last_insert_id();
		if (!empty($destination_catnumber) && $destination_catnumber > 0) 
			 $this->app->setItemCategory($new_id, $destination_catnumber,true);				
 	}
		
	//_________________________________________________________________________//
	public function Move($mixed_items_to_move, $destination)
	{ 
		ajaxReply(400, "Items under multiple category class cannot be moved. Please use the item editor");
	}
	
	//_________________________________________________________________________//
	public function getItemCategoriesByID($id)
	{
		$cats_array = $this->app->getItemCategoryIDsByItemID($id);	
		echo ajaxReply(200,$cats_array);
	}
	
	//_________________________________________________________________________//
	public function setItemCategory($id,$cid,$state)
	{
		$status = $this->app->setItemCategory($id, $cid, $state == 'true');
		echo ajaxReply(200,$status);
	}
	
	//_________________________________________________________________________//	
 	public function Run()
	{
		switch ($_GET['action'])
		{
			case 'setcategory': $this->setItemCategory($_POST['id'], $_POST['cid'], $_POST['state']);break;
			case 'getitemcategories': $this->getItemCategoriesByID(intval($_GET['id']));break;				
			default: parent::Run();
		}
	}
	//_________________________________________________________________________//
	
}
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

?>