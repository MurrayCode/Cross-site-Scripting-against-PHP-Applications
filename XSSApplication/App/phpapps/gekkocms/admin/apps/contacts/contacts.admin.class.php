<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

include_app_class('contacts');
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
class contactsAdmin extends basicAdministrationSimpleCategories {

//_________________________________________________________________________//    
    public function __construct()
    {
		$datatype = 'basicsimplecategory';
		$methods = array ('standard_main_app' => 'Main Application Page',
						  'standard_browse' => 'View a specific item/category'//,
						  //'sendmail' => 'Send Mail'
						 );
		$this->data_config_keys = createDataArray('alias','str_meta_keywords','str_meta_description','chk_enable_captcha');
		parent::__construct('contacts', $datatype, $methods);
		
    }
    
}
?>