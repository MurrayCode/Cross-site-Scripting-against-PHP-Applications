<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

include_app_class('blog');
include_admin_class('html');
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
class blogAdmin extends htmlAdmin {

/*
      <?php echo $gekko_config->displayConfigAsTextBoxWithLabel($this->app_name,'alias','Application Alias',true); ?> <br />
      <?php echo $gekko_config->displayConfigAsTextBoxWithLabel($this->app_name,'str_title','Blog Title',true); ?> <br />
      <?php echo $gekko_config->displayConfigAsTextBoxWithLabel($this->app_name,'str_author_name','Author',true); ?> <br />
      <?php echo $gekko_config->displayConfigAsTextBoxWithLabel($this->app_name,'str_author_email','Author E-mail Address',true); ?> <br />
      <?php echo $gekko_config->displayConfigAsTextBoxWithLabel($this->app_name,'str_description','Blog Description',true);  ?> <br />
      <?php echo $gekko_config->displayConfigAsTextBoxWithLabel($this->app_name,'int_max_entries_in_frontpage','Number of characters to be displayed in the main blog',true);  ?> <br />
      <?php echo $gekko_config->displayConfigAsTextBoxWithLabel($this->app_name,'int_max_entries_in_rss','Maximum number of entries to be displayed in the RSS file',true);  ?> <br />
      <?php echo $gekko_config->displayConfigAsSingleCheckbox($this->app_name,'chk_enable_pageview_stats','Enable page impression stats (may slow down SQL)'); ?> <br />
      <?php echo $gekko_config->displayConfigAsTextBoxWithLabel($this->app_name,'str_meta_keywords','Default Meta Keywords',true); ?> <br />
      <?php echo $gekko_config->displayConfigAsTextBoxWithLabel($this->app_name,'str_meta_description','Default Meta Description',true);  ?> <br />
*/
//_________________________________________________________________________//    
    public function __construct()
    {
			global $gekko_db;		
		$datatype = 'basicnestedcategory';
		$methods = array ('standard_main_app' => 'Main Application Page',
						  'standard_browse' => 'View a specific item/category',
						  'rss' => 'View RSS 2.0'
						 );
						 
		basicAdministrationNestedCategories::__construct('blog', $datatype, $methods);
		$this->data_config_keys = createDataArray('alias','str_title','str_author_name','str_authoremail','str_description','int_max_entries_in_frontpage','int_max_entries_in_rss','chk_enable_pageview_stats','str_meta_keywords','str_meta_description','groups_allowed_for_backend_access');
		
    }
 	
	//_________________________________________________________________________//	
	public function saveItem($id)
	{
		// To be moved to main - TODO
 		return parent::saveItem($id);
	}

}
?>
