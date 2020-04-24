<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

include_app_class('html');

class blog extends html
{
	public $item_options =  	array   (0 => array ('value' => 'display_pagetitle','label' => 'Display page title' ),
										 1 => array ('value' => 'display_items_author','label' => 'Display item\'s author'),
										 2 => array ('value' => 'display_items_date_created','label' => 'Display item\'s date created'),
										 3 => array ('value' => 'display_items_date_modified','label' => 'Display item\'s date modified'),
										 );	
	
    public function __construct()
    {
 		$data_items = createDataArray ('id','status','category_id','title','summary','description','virtual_filename','date_available','date_expiry','date_created','date_modified','sort_order','permission_read','permission_write','options','created_by_id','modified_by_id','meta_key','meta_description','pageview');
		$data_categories = createDataArray ('cid','status','items_per_page','parent_id','title','summary','sort_order','virtual_filename','date_available','date_expiry','date_created','date_modified','permission_read','permission_write','options','created_by_id','modified_by_id','meta_key','meta_description');
		basicApplicationNestedCategories::__construct('blog','Blog','gk_blog_items', 'id', $data_items, 'gk_blog_categories', 'cid', $data_categories);
    }
	//_______________________________________________________________________________________________________________//	
	
	public function RSS()
	{
		global $gekko_config, $SiteTemplate;
		
		header('Content-Type: application/rss+xml; charset=UTF-8');
		
	     $rss_blog_title= $this->getConfig('str_title');
         $rss_author_name = $this->getConfig('str_author_name');     
         $rss_author_email = $this->getConfig('str_author_email');
		 $rss_desc =  $this->getConfig('str_description');  
         $rss_max_entrychar= $this->getConfig('int_max_entry_char'); // Number of items to be displayed in the blog (recommended: 200 to 250)',true); 
         $rss_maxposts= $this->getConfig('int_max_entries_in_rss'); // Maximum number of entries to be displayed in the RSS file',true);
		
		$total_item_count = $this->getTotalItemCount(true);
		// The limit is introduced to prevent memory exhaustion
		 // hardcode all item limit if there's no limit specified
		if ($rss_maxposts == 0 || $rss_maxposts > $total_item_count) $rssmax_posts = $total_item_count;
		if ($rss_max_posts > 500) $rss_maxposts = 500;
		$latestposts = $this->getAllItems('*','status > 0', 0,$rss_maxposts,'date_created','DESC'); 
		$css_path = '/templates/'.$SiteTemplate->getDefaultTemplateName().'/rss.css';

		$template_file = SITE_PATH."/apps/{$this->app_name}/rss.template.php";
		// for inherited classes
		if (!file_exists($template_file)) $template_file =  SITE_PATH."/apps/blog/rss.template.php";
		include_once ($template_file);
 	}
	
	//_______________________________________________________________________________________________________________//	
 	public function displayMainPage($pg=1)
	{
		global $gekko_config;
		
		$blog_title= $this->getConfig('str_title',true);
		$this->page_title = $blog_title;
		$pg = intval($pg);
		if ($pg > 1) $this->page_title.=" - Page {$pg}";		
		$current_method = __FUNCTION__;
		$max_posts_perpage = $this->getConfig('int_max_entries_in_frontpage');
		if ($max_posts_perpage == 0) $max_posts_perpage = 10;

		$total_item_count = $this->getTotalItemCount('status > 0',$from_cache);
		if ($max_posts_perpage * $pg > $total_item_count+$max_posts_perpage)
		{
			$this->displayHTTPError(404);return false;
		}
		$pagination = getStartAndEndForItemPagination($pg, $max_posts_perpage,$total_item_count) ;
		$latestposts = $this->getAllItems('*','status > 0', $pagination['start'],$pagination['end'],'date_created','DESC'); 
		$this->declarePageLastModified($this->getMaxDateFromArray($latestposts));
		include (SITE_PATH.'/apps/'.$this->app_name.'/mainpage.template.php');
	}
	//_______________________________________________________________________________________________________________//	
	
	public function getDefaultCategoryID()
	{
		return basicApplicationSimpleCategories::getDefaultCategoryID();
	}
	//_______________________________________________________________________________________________________________//	
	
	public function fullTextSearch($keyword,$sortby='',$sortdirection='ASC')
	{
		global $gekko_db;
 
 		$cleankeyword = sanitizeString  ("%{$keyword}%");
		$sql = "SELECT * FROM {$this->table_items} WHERE title LIKE {$cleankeyword} OR summary LIKE {$cleankeyword}";
		if(!empty($sortby) && in_array($sortby,$this->data_items)) $sql.= " ORDER BY {$sortby} {$sortdirection}";
 		$search_result = $gekko_db->get_query_result($sql);
		return $search_result;		
	}
	//_______________________________________________________________________________________________________________//
 	public function createFriendlyURL($str)
	{ 

		if (SEF_ENABLED)
		{
			$param_array = explode('&',$str);
			$command_array = array();
			foreach ($param_array as $param)
			{
				list ($xparam, $xvalue) = explode('=', $param);
				$command_array[$xparam] = $xvalue;
			}
			if ($command_array['action'] == 'main')
			{
				if ($command_array['pg']) $final_url.="/pg{$command_array['pg']}.html";
				else $final_url.='/';
			} else return parent::createFriendlyURL($str);
			$final_url = SITE_HTTPBASE.'/'.$this->app_name.$final_url;
		} else return parent::createFriendlyURL($str);
		return removeMultipleSlashes($final_url);
	}

	//_______________________________________________________________________________________________________________//
 	public function interpretFriendlyURL($url)
	{
	
		$url = str_replace(SITE_HTTPBASE,'',$url); // must
		if (SEF_ENABLED && !$_GET['app'])
		{			
			$url_array = explode('/',$url);
			$appname = $url_array[1];
			array_splice($url_array,0,2);
			if ($url_array[0]=='rss') $command['action'] = 'rss'; else
			if($c=preg_match_all ("/(pg)(\d+).*?(html)/is",$url_array[0], $x))
			{
				$command = array();
				$folder_requestpage=$x[2][0];
				$command['pg'] = $folder_requestpage;
				$command['action'] = 'main';
			} else return parent::interpretFriendlyURL($url);
		} else 
		{
			return parent::interpretFriendlyURL($url);
		}
		return $command;
	}
	//_______________________________________________________________________________________________________________//
	public function Run($command)
	{
		//$this->insertTest();
		switch ($command['action'])
		{
			case 'rss': $this->RSS(); return false;break; // yayaya
			case 'viewcategory':$this->displayItemsInCategoryByID($command['cid'],$command['pg'],'date_created','DESC');return true;break;
 			case 'main': $this->displayMainPage($command['pg']);return true;break;
			default: return parent::Run($command);
		}
	}

}
	
?>