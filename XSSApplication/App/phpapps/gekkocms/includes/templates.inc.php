<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

class templates extends basicApplicationSimpleCategories
{	
    public function __construct()
    {
 		$data_items = createDataArray ('id','title','description','sort_order','status','date_modified');
		parent::__construct('templates','Templates', 'gk_template_items', 'id', $data_items, '', '', null);
		$this->valid_template_mode = array('default','mobile','iphone');
    }
	//_______________________________________________________________________________________________________________//	
 	public function displayMainPage()
	{
		return false;
	}
	//_______________________________________________________________________________________________________________//	
	public function saveItem($id)
	{
		//$_POST['title'] = convert_into_sef_friendly_title($_POST['title']);
		$savestatus = parent::saveItem($id);
		return $savestatus;
  	}
  	
//_______________________________________________________________________________________________________________//	
	public function findDuplicateItems($data)
	{
		global $gekko_db;
		
		$current_id = $data[$this->field_id];
		$sql =  "SELECT * from {$this->table_items} WHERE (title = '{$data['title']}')";

		if (intval($current_id) != 0) $sql.= " AND (id != '{$current_id}')";
		
		$gekko_db->query($sql);
		$result  = $gekko_db->get_result_as_array();
		
		return $result;
	}
//_______________________________________________________________________________________________________________//	
	public function setTemplate ($mode,$value)
	{
		
		$template_config = new DynamicConfiguration('gk_config');
		if (in_array($mode,$this->valid_template_mode))
		{
			$template_config->set($this->app_name,$mode,intval($value));
		}
	}
//_______________________________________________________________________________________________________________//	
	public function getTemplate ($mode)
	{
		$template_config = new DynamicConfiguration('gk_config');
		if (in_array($mode,$this->valid_template_mode))
		{
			return $template_config->get($this->app_name,$mode,intval($value),true);
		}
	}
//_______________________________________________________________________________________________________________//		
	public function getDefaultTemplateName ()
	{
		$template_id =  $this->getTemplate('default');
		$template = $this->getItemByID($template_id);
		if (empty($template['title'])) return 'html5demo'; else return $template['title'];		
	}
//_______________________________________________________________________________________________________________//	
	public function getCurrentTemplate ()
	{
		$mobile_regex = "/(nokia|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|".
		"htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|".
		"blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|".	
		"symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|".
		"jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220".
		")/i";
		// default/iphone/mobile
		if (!isset($_SESSION['template_type']))
		{
			$is_iphone = preg_match("/(iphone)/i", strtolower($_SERVER['HTTP_USER_AGENT'])); 
			$is_mobile = isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE']) || preg_match($mobile_regex, strtolower($_SERVER['HTTP_USER_AGENT']));
			if ($is_iphone) $_SESSION['template_type'] = 'default';
			else if ($is_mobile) $_SESSION['template_type'] = 'mobile';
			else $_SESSION['template_type'] = 'default';
		}
		$template_id =  $this->getTemplate($_SESSION['template_type']);
		$template = $this->getItemByID($template_id);
		return $template['title'];
	}	
//_______________________________________________________________________________________________________________//	
	public function Run($command)
	{
		$template_name = $this->getCurrentTemplate();
		 if (file_exists(SITE_PATH.'/templates/'.$template_name.'/index.php'))
			 include ('templates/'.$template_name.'/index.php');
		 else
		 	 echo 'Template file '.$template_name.' cannot be found. Please check your settings and try again.';		
	}
}

//_______________________________________________________________________________________________________________//	

class HTMLPageHeader
{
	private $header;

//_______________________________________________________________________________________________________________//	
	public function add($str)
	{
		$txt =$str;	
		if (strpos($this->header,$txt)===false)
			$this->header.=$txt."\n";
		
	}
//_______________________________________________________________________________________________________________//	
	public function CSS($str,$media='')
	{
		if ($media) $themedia = "media=\"{$media}\"";
		$csstext = "<link type=\"text/css\" href=\"".SITE_HTTPBASE."{$str}\" rel=\"stylesheet\" {$themedia} />\n";
		if (strpos($this->header,$csstext)===false)
			$this->header.=$csstext."\n";
	}
//_______________________________________________________________________________________________________________//	
	public function JAVASCRIPT($str)
	{
		$jstext = JAVASCRIPT($str);
		if (strpos($this->header,$jstext)===false)
			$this->header.=$jstext."\n";
		
	}
//_______________________________________________________________________________________________________________//	
	public function JAVASCRIPT_TEXT($str)
	{
		$jstext = JAVASCRIPT_TEXT($str);
		if (strpos($this->header,$jstext)===false)
			$this->header.=$jstext."\n";
	}	
//_______________________________________________________________________________________________________________//	
	public function JAVASCRIPT_EXTERNAL($str)
	{
		$jstext = JAVASCRIPT_EXTERNAL($str);
		if (strpos($this->header,$jstext)===false)
			$this->header.=$jstext."\n";
	}
//_______________________________________________________________________________________________________________//	
	public function JAVASCRIPT_GEKKO()
	{
		$jstext = JAVASCRIPT_GEKKO();
		if (strpos($this->header,$jstext)===false)
			$this->header.=$jstext."\n";
	}
	
//_______________________________________________________________________________________________________________//	
	public function JAVASCRIPT_YUI()
	{
		$jstext = JAVASCRIPT_YUI2_COMBO();
		if (strpos($this->header,$jstext)===false)
			$this->header.=$jstext."\n";
	}
//_______________________________________________________________________________________________________________//	
	public function JAVASCRIPT_YUI_MINIUTIL()
	{
		$jstext = JAVASCRIPT_YUI2_MINIUTIL();
		if (strpos($this->header,$jstext)===false)
			$this->header.=$jstext."\n";
	}

//_______________________________________________________________________________________________________________//	
	
	public function getAll()
	{
		
		return $this->header;	
	}
//_______________________________________________________________________________________________________________//	
	
}

?>