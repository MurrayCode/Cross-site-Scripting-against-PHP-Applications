<?php
if (!defined('GEKKO_VERSION')) die('No Access');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

class mainAdmin extends basicAdministration {

//_________________________________________________________________________//    
    public function __construct($errormsg)
    {
		parent::__construct('main');
		if ($errormsg) $this->errormsg = $errormsg;
	}
	//_________________________________________________________________________//    
	public function displayPageHeader()
	{
		
		parent::displayPageHeader();
		
		$admin_file = "/admin/apps/{$this->app_name}/{$this->app_name}.js";
		if (file_exists(SITE_PATH.$admin_file)) echo JAVASCRIPT($admin_file);
	}
	public function getApplicationDescription()
	{
		return 'Main';	
	}
 //_________________________________________________________________________//   

	function deleteInstallDirectory($ok)
	{
		if ($ok == 1 && is_dir(SITE_PATH.'/install')) 
		{
			$newname = SITE_PATH.'/install'.mt_rand();
			$result = $this->delTree(SITE_PATH.'/install');// @rename (SITE_PATH.DIRECTORY_SEPARATOR.'install',$newname);
			
			if ($result) $this->errormsg = 'Installation directory has been removed completely'; else $this->errormsg = 'Cannot rename installation directory. Please check the permission on /install and try again or delete it manually - '.SITE_PATH.'/install';
		}include ('deleteinstalldir.template.php');
	}
 //_________________________________________________________________________//   
	
    protected function delTree($dir) {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) return @unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!$this->delTree($dir.DIRECTORY_SEPARATOR.$item)) return false;
        }
        return @rmdir($dir);
    }	
 //_________________________________________________________________________//   
	
	public function loadExternalRSS($source)
	{
		$_GET['ajax'] = 1;
		$source = cleanInput($source);
		switch ($source)
		{
			case 'bbgkext': $rss_url = 'http://feeds.feedburner.com/babygekko-extensions';break;
			case 'bbgknews': 
			default: $rss_url = 'http://feeds.feedburner.com/babygekko';break;
			
		}
		$rss_content = load_external_page($rss_url);
		$simplerss = simplexml_load_string($rss_content);
		
		include('external_rss.template.php');
		exit;
	}
 //_________________________________________________________________________//   
	public function displayMainPage()
	{
		global $gekko_db;
		
		if ($this->errormsg && $_GET['ajax'] == 1)
		{
			ajaxReply(404, $this->errormsg);
		} else
		{
			$sql = "SELECT id, title FROM gk_html_items order by date_modified DESC LIMIT 0,5";
			$gekko_db->query($sql);
			$contents = $gekko_db->get_result_as_array();
			$gekko_db->query($sql);		
			include ('mainpage.template.php');
		}
	}
	//_________________________________________________________________________// 	
	public function Run()
	{
		switch ($_GET['action'])
		{
			case 'externalrss': $this->loadExternalRSS($_GET['source']);
			case 'deleteinstalldir': $this->deleteInstallDirectory($_POST['deleteinstalldir']);break;
			default:parent::Run();	
		}
	}

}
?>