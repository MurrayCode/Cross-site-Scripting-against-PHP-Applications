<?php 
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

abstract class basicFilter implements interfaceFilter
{
//_______________________________________________________________________________________________________________//

    public function __construct($config)
    {
		$this->config = $config;
    }
	//_______________________________________________________________________________________________________________//
	public function loadTemplateFile($script_path,$vars)
	{
		$classes = get_class_ancestors($this);
		
		foreach ($classes as $gekko_class)
		{
			$theclass = strtolower($gekko_class);
			$script_dir = SITE_PATH."/filters/{$theclass}/";
			$filename = $script_dir.$script_path.'.template.php';
			if (is_file($filename))
			{
				if (is_array($vars)) extract($vars, EXTR_REFS);
				include_once($filename);
				return true;
			} 
		}
		return false;
	}

//_______________________________________________________________________________________________________________//
 	public function getConfiguration()
	{
		global $gekko_config;
		$config = $gekko_config->get(get_class($this));
		return $config;
	}
//_______________________________________________________________________________________________________________//
	public function Run($text,$obj,$caller_function,$extra_info=false)
	{
		echo 'Please implement this function';
		return false;
	}
//_______________________________________________________________________________________________________________//
	
}

abstract class basicBlock implements interfaceBlock
{
	protected $block_name;
//_______________________________________________________________________________________________________________//

    public function __construct($block_name,$config)
    {
		$this->block_name = $block_name;
		$this->config = $config;
    }
	//_______________________________________________________________________________________________________________//
	public function loadTemplateFile($script_path,$vars)
	{
		$classes = get_class_ancestors($this);
		
		foreach ($classes as $gekko_class)
		{
			$theclass = strtolower($gekko_class);
			$script_dir = SITE_PATH."/blocks/{$theclass}/";
			$filename = $script_dir.$script_path.'.template.php';
			if (is_file($filename))
			{
				if (is_array($vars)) extract($vars, EXTR_REFS);
				include_once($filename);

				return true;
			} 
		}
		return false;
	}

//_______________________________________________________________________________________________________________//
 	public function getConfiguration()
	{
		global $gekko_config;
		$config = $gekko_config->get($this->block_name);
		return $config;
	}

//_______________________________________________________________________________________________________________//
	public function Run()
	{
		echo 'Please implement this function';
		return false;
	}
//_______________________________________________________________________________________________________________//
	
}


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//


abstract class basicApplication  implements interfaceApplication
{
	protected $app_name;
	protected $app_description;
	protected $cache;
//_______________________________________________________________________________________________________________//

    public function __construct($app_name, $app_description)
    {
		$this->app_name = $app_name;
		$this->app_description = $app_description;
		$this->cache = true; // enable by default, disable it in your custom class
		$this->paginationStringFormat = TXT_PAGINATION_STRING;		
		$this->resetBreadCrumbs();
    }
	//_______________________________________________________________________________________________________________//
	public function loadTemplateFile($script_path,$vars)
	{
		// yes, they have to be declared in basicApplication, basicBlock, and basicFilter.. cannot be separated to util.inc.php because of $this var during include
		$classes = get_class_ancestors($this);
		
		foreach ($classes as $gekko_class)
		{
			$theclass = strtolower($gekko_class);
			$script_dir = SITE_PATH."/apps/{$theclass}/";
			$filename = $script_dir.$script_path.'.template.php';
			if (is_file($filename))
			{
				if (is_array($vars)) extract($vars, EXTR_REFS);
				include_once($filename);

				return true;
			} 
		}
		return false;
	}

	//_________________________________________________________________________//    	
	public function redirectToOtherAction($action,$protocol=OPT_REDIRECT_DEFAULT)
	{
		
		$qry = $this->createFriendlyURL($action);
		switch ($protocol)
		{
			case OPT_REDIRECT_HTTPS: $qry = force_HTTPS_url().$qry; break; 
			case OPT_REDIRECT_HTTP: $qry = (defined('SITE_HTTP_URL') ? SITE_HTTP_URL : SITE_URL).$qry; break;	
		}
		ob_end_clean();
		ob_start();
		header("Location: {$qry}"); 
	}
//_______________________________________________________________________________________________________________//	
	protected function resetBreadCrumbs()
	{
		if ($this->app_name != 'html')
			$this->bread_crumbs[] = array('title' => $this->app_description,'link' => $this->createFriendlyURL(''));
	}
//_______________________________________________________________________________________________________________//
	public function declarePageLastModified($date)
	{
		if (!is_numeric($date)) $date = strtotime($date);
		$last_modified = gmdate("D, d M Y H:i:s",$date)." GMT";
		header("Expires: {$last_modified}");			
		header("Last-Modified: {$last_modified}");
	}
	
//_______________________________________________________________________________________________________________//	
	protected function addToBreadCrumbs($name,$link)
	{
		$this->bread_crumbs[] = array('title' => $name,'link' => $link);
	}
//_______________________________________________________________________________________________________________//
	public function displayBreadCrumbs($separator = ' &raquo; ')
	{
		//print_r($this->bread_crumbs);
		echo A('Home',SITE_HTTPBASE).$separator;
		$total = count($this->bread_crumbs);
		for ($i = 0; $i < $total; $i++)
		{
			$crumb = $this->bread_crumbs[$i];
			echo A($crumb['title'],$crumb['link']);
			if ($i < $total) echo $separator;
		}
	
	}
//_________________________________________________________________________//    		
    public function getConfig($key)
    {
		global $gekko_config;
		
		return $gekko_config->get($this->app_name,$key);
    }
//_________________________________________________________________________//    		
    public function setConfig($key, $value)
    {
		global $gekko_config;
		
		return $gekko_config->set($this->app_name,$key,$value);
    }
		
//_______________________________________________________________________________________________________________//
 	public function getApplicationDescription()
	{
		return $this->app_description;
	}
//_______________________________________________________________________________________________________________//
 	public function processOutputWithFilter($text, $the_function,$extra_info = false)
	{
		global $Filters;
		// TODO: Last worked on - May 6, 2010
		/*$prev = debug_backtrace();
		$total = count($prev);
		for ($i=0;$i < $total;$i++)
		{
			echo $prev[$i]['class'].$prev[$i]['function'].'<BR>';
		}*/
 		return $Filters->modifyText($text,get_called_class(),$the_function, $extra_info); // yes, will process even if text = ''
 		return $text;
	}
//_______________________________________________________________________________________________________________//
 	public function displayPageTitle()
	{
		echo htmlspecialchars($this->getApplicationDescription());
	}
//_______________________________________________________________________________________________________________//
 	public function displayPageMetaDescription()
	{
		echo htmlspecialchars(trim($this->getApplicationDescription()));
	}
//_______________________________________________________________________________________________________________//
 	public function displayPageMetaKeywords()
	{
		echo htmlspecialchars(trim($this->getApplicationDescription()));
	}		
//_______________________________________________________________________________________________________________//
	
	public function displayMainPage()
	{
		echo 'Please implement this function';
	}
//_______________________________________________________________________________________________________________//
	protected function probeFriendlyURLDestination($urlpath)
	{
		$site_httpbase_length = strlen(SITE_HTTPBASE);
		$urlpath = removeMultipleSlashes( $urlpath);
		if ($site_httpbase_length > 0)
		{
			$site_httpbase_pos = strpos($urlpath,SITE_HTTPBASE);
			if ($site_httpbase_pos !== false && $site_httpbase_pos == 0)
				$urlpath = substr($urlpath,$site_httpbase_length,strlen($urlpath)-$site_httpbase_length);
		}
		$app_alias = getApplicationAlias($this->app_name);
		if (SEF_ENABLED && $urlpath=='/'.$app_alias)
		{
			redirectURL(SITE_URL.SITE_HTTPBASE."/{$app_alias}/");
			exit;
		}
		$url_info = parse_url($urlpath);
		$url = $url_info['path'];
		$url_array = explode('/',$url);
		array_splice($url_array,0,2);
		$url = implode('/',$url_array);
		return array('url' => trim($url), 'url_array'=> $url_array);
	}
	
//_______________________________________________________________________________________________________________//
 	public function interpretFriendlyURL($urlpath)
	{
		
	//	$url = str_replace(SITE_HTTPBASE,'',$url); // must
		// TODO: Optimize with  http_build_str  
		if (SEF_ENABLED && !$_GET['app'])
		{
			$parsedurl = $this->probeFriendlyURLDestination($urlpath);
			$url = $parsedurl['url'];
			$url_array = $parsedurl['url_array'];			
			if (empty($url)) $command['action'] = 'main';
			else
			if ($url_array[0]=='action' &&  $url_array[1])
			{
				//$command['action'] = $url_array[1];
				$count = count($url_array);
				for ($i =0; $i < $count; $i++)
				{
					$key = $url_array[$i];
					$val = $url_array[$i+1];
					$i++;
					if ($key && $val)
						$command[$key] = $val;
				}
			}
		}
		else 
		{
			$commands = $_GET;

		}
		return $command;
	}
	
//_______________________________________________________________________________________________________________//
 	public function createFriendlyURL($str)
	{
		if (SEF_ENABLED)
		{	
			$param_array = explode('&',$str);
/*			$command_array = array();
			foreach ($param_array as $param)
			{
				list ($xparam, $xvalue) = explode('=', $param);
				$command_array[$xparam] = $xvalue;
			} */
			parse_str($str, $command_array); 
			if ($command_array['action'] !== '') 
			{
				$keys = array_keys($command_array);
				foreach ($keys as $key)
				{
					$final_url.= "/{$key}/{$command_array[$key]}";
				}
			} else $final_url = '/';  // Feb 4, 2012 fix
			$final_url = SITE_HTTPBASE.'/'.getApplicationAlias($this->app_name).$final_url;
		} else $final_url =  SITE_HTTPBASE.'/'."index.php?app={$this->app_name}&{$str}";
		$final_url = removeMultipleSlashes( $final_url);
		return $final_url;
	}
//_______________________________________________________________________________________________________________//
	public function displayItemPagination($pg,$pages,$new_url_query_string,$friendly=true)
	{
		$prev = $pg - 1;
		$next = $pg + 1;
		$pg = intval($pg);
		$pages = intval($pages);
		$str = '';
		if ($pg <= 0) $pg = 1;
		if ($pg > $pages) return false; // page outside of range
		$itemsperpage = $this->getNumberOfListingsPerPage();
		if ($pages > 1)
		{
			if (strpos($new_url_query_string,'?')!==false)
			{
				$urlcomp = parse_url($new_url_query_string);			
				$urlcomp = parse_url($new_url_query_string);
				parse_str($urlcomp['query'], $new_url_query_array);
				if (array_key_exists('pg',$new_url_query_array)) unset($new_url_query_array['pg']);
				$new_url_query_string = $urlcomp['path'].'?'.http_build_query($new_url_query_array);
			}
			$str = sprintf($this->paginationStringFormat.' ', $pg, $pages);
			//FIRST
			$urlprev = "{$new_url_query_string}";//&pg=1";
			if ($friendly) $urlprev = $this->createFriendlyURL($urlprev);
			$str.= A(TXT_PAGINATION_FIRST,$urlprev,'','pagination_first').' &nbsp; ';
			
			//PREV
			$urlprev = "{$new_url_query_string}&pg=$prev";
			if ($friendly) $urlprev = $this->createFriendlyURL($urlprev);
			if ($prev > 0) $str.= A(TXT_PAGINATION_PREV,$urlprev,'','pagination_prev').' &nbsp;';
			$beginning =  ($itemsperpage*intval($pg / $itemsperpage))+1;
			
			if ($beginning <= 0) $beginning = 1;
			if (($pg % $itemsperpage) == 0) $beginning -= $itemsperpage;
			$end = $beginning + $itemsperpage;
 			for ($i=$beginning;$i < $end && $i <= $pages; $i++)
			{
				$urlpg = "{$new_url_query_string}&pg=$i";
				if ($friendly) $urlpg = $this->createFriendlyURL($urlpg);
				if ($i != $pg) $str.= A($i,$urlpg,'','pagination_number').'&nbsp;';
				else $str.= SPAN(" $i ",'','pagination_current');
			}
			//NEXT
			$urlnext = "{$new_url_query_string}&pg=$next";
			if ($friendly) $urlnext = $this->createFriendlyURL($urlnext);
			if ($next < $pages) $str.= A(TXT_PAGINATION_NEXT,$urlnext,'','pagination_next')." &nbsp;";
			//LAST
			$urllast = "{$new_url_query_string}&pg={$pages}";
			if ($friendly) $urllast = $this->createFriendlyURL($urllast);
			$str.= A(TXT_PAGINATION_LAST,$urllast,'','pagination_last')." &nbsp;";
			
		}
		return $str;
	}
//_______________________________________________________________________________________________________________//	
	public function setPageTitle($title)
	{
		$this->page_title = $title;
	}
//_______________________________________________________________________________________________________________//	
	public function getPageTitle()
	{
		return $this->page_title;
	}
//_______________________________________________________________________________________________________________//	
	public function getPageMetaDescription()
	{
		return $this->page_meta_description;
	}
//_______________________________________________________________________________________________________________//	
	public function getPageMetaKeywords()
	{
		return $this->page_meta_keywords;
	}
	
//_______________________________________________________________________________________________________________//	
	public function setPageMetaDescription($meta_description)
	{
		$this->page_meta_description = $meta_description;
	}
//_______________________________________________________________________________________________________________//	
	public function setPageMetaKeywords($meta_keywords)
	{
		$this->page_meta_keywords = $meta_keywords;
	}
//_______________________________________________________________________________________________________________//
	
	public function displayHTTPError($error_number,$display_default_error = true)
	{
		global $defaultHTTPErrorMessages;
		
		if ($defaultHTTPErrorMessages[$error_number])
		{
				header($defaultHTTPErrorMessages[$error_number]['header']);
				$this->setPageTitle($defaultHTTPErrorMessages[$error_number]['pagetitle']);
				if ($display_default_error && file_exists(SITE_PATH."/includes/errors/{$error_number}.php")) include (SITE_PATH."/includes/errors/{$error_number}.php");
		}
	}
//_______________________________________________________________________________________________________________//		
	protected function translateMetaOptions($the_options)
	{
		if ($the_options)
		{
			$options = array();
			$options_array = unserialize ($the_options);
			$array_keys = array_keys($options_array);
			if (is_array($options_array))
			foreach ($array_keys as $opt) 
				if (is_numeric($opt)) $options[$options_array[$opt]] = 1;else $options[$opt] = $options_array[$opt];
			return $options;
		} else return false;
	}
//_______________________________________________________________________________________________________________//
	public function Run($command)
	{
		switch ($command['action'])
		{
	 		case 'main':$this->displayMainPage();break;
			case '404error': 
			default: $this->displayHTTPError(404);break;
		}
		return true;
	}
//_______________________________________________________________________________________________________________//
	
}
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
abstract class basicApplicationLinearData extends basicApplication  implements interfaceApplicationLinearData
{
	protected $data_items;
	protected $table_items;
	protected $field_id;
	protected $number_of_listings_per_page;
		
    public function __construct($app_name, $app_description, $table_items, $field_id, $data_items)
    {
		parent::__construct($app_name, $app_description);
		$this->table_items = $table_items;
		$this->data_items = $data_items;
		$this->field_id = $field_id;
		$this->number_of_listings_per_page = DEFAULT_FRONTEND_ITEMS_PERPAGE;
    }
//_______________________________________________________________________________________________________________//		
	public function getMaxDateFromArray($items)
	{
		$last_mod_date = 0;
		foreach ($items as $item) if (strtotime($item['date_modified']) > $last_mod_date) $last_mod_date = $item['date_modified'];
		return $last_mod_date;
	}
//_______________________________________________________________________________________________________________//	
	public function getNumberOfListingsPerPage()
	{
		return $this->number_of_listings_per_page;
	}
//_______________________________________________________________________________________________________________//	
	public function setNumberOfListingsPerPage($number)
	{
		if ($number <= 0) $number = DEFAULT_FRONTEND_ITEMS_PERPAGE;
		$this->number_of_listings_per_page = intval ($number);
	}

//_______________________________________________________________________________________________________________//	
	public function getItemTableName()
	{
		return $this->table_items;
	}
//_______________________________________________________________________________________________________________//	
	public function increaseItemPageView($id)
	{
		pageCounterIncreaseImpression($id,$this->app_name,$this->data_items,$this->table_items,'pageview',$this->field_id);
	}
//_______________________________________________________________________________________________________________//	
	public function resetItemPageView($id)
	{
		pageCounterIncreaseImpression($id,$this->app_name,$this->data_items,$this->table_items,'pageview',$this->field_id,true);
	}

//_______________________________________________________________________________________________________________//	
	public function getItemFieldNames()
	{
		return $this->data_items;
	}
//_______________________________________________________________________________________________________________//	
	public function getFieldID()
	{
		return $this->field_id;
	}
//_______________________________________________________________________________________________________________//	
	
//_______________________________________________________________________________________________________________//	
	function delete($mixed_items_to_delete)
	{ 
		global $gekko_db;
		$id = 1;
		$mixed_items_array = explode(',', $mixed_items_to_delete); // e.g: i4,i14
		// Process sub-folders first
		foreach ($mixed_items_array as $mixed_item)
		{
			 $item_id = substr($mixed_item,1); 
			 $items_to_delete[] = $item_id;
		}
		
		$items_to_delete_str = implode(",", $items_to_delete);
		if ($items_to_delete_str)
		{
			$items_to_delete_str = implode(",", $items_to_delete);
			$sql1 = "DELETE FROM {$this->table_items} WHERE {$this->field_id} in ({$items_to_delete_str})";
			$gekko_db->query($sql1);
		}
	}
//_______________________________________________________________________________________________________________//	
	public function getTotalItemCount($criteria='',$cache=false)
	{
		global $gekko_db;
		
		$criteria_txt = '';
		if (!empty($criteria)) $criteria_txt = " WHERE {$criteria}";
		
 		$sql = "SELECT count({$this->field_id}) as total_item_count FROM {$this->table_items} {$criteria_txt}";
		$total = $gekko_db->get_query_singleresult($sql,$cache);
		return $total['total_item_count'];
	}
//_______________________________________________________________________________________________________________//
	public function getAllItems($fields='*', $extra_criteria = '', $start=0,$end=0,$sortby='', $sortdirection='ASC', $from_cache = false)
	{
		global $gekko_db;
		
		if($sortby!='')	if (!array_key_exists($sortby,$this->data_items)) $sortby=$this->getFieldID();
		$sql = selectSQL($this->table_items,$fields,$extra_criteria,$start,$end,$sortby, $sortdirection,true,SQL_ENFORCE_ROW_LIMIT);
		
 		$search_result = $gekko_db->get_query_result($sql,$from_cache);
		return $search_result;		
	}
 //_______________________________________________________________________________________________________________//
	public function getDefaultItemID()
	{
		$sql = "SELECT {$this->field_id} FROM {$this->table_items} ORDER BY {$this->field_id} ASC";
		$allitems = $gekko_db->get_query_result($sql,true);
		if ($allitems)
		{
			$itemid = $allitems[0][$this->field_id];
			return $itemid;
		} else return 0;
	}
//_______________________________________________________________________________________________________________//
	// internal functions
	public function getItemByID($id,$from_cache=false)
	{
		global $gekko_db;
 		if ($id > 0)
	 		$item = $gekko_db->get_query_singleresult("SELECT * FROM {$this->table_items} WHERE {$this->field_id} = '".intval($id)."'",$from_cache);
		else
			$item = false;
		return $item;
	}
//_______________________________________________________________________________________________________________//
	public function getItemByVirtualFilename($input_filename, $category_id=-1)
	{
		global $gekko_db;
		
 		if (!empty($input_filename))
		{
		
			$str = "";
			$category_id = intval ($category_id);
			$filename = sanitizeString($input_filename);
			if ($category_id >= 0) $str = " AND category_id = {$category_id}";
			$sql = "SELECT * FROM {$this->table_items} WHERE virtual_filename = {$filename}{$str}";
 			$items = $gekko_db->get_query_result($sql,true);
			return $items;
		} else return false;
	}

//_______________________________________________________________________________________________________________//	
	public function getBreadCrumbsByItemID($item_id)
	{
		global $gekko_db;
		
		$item = $this->getItemByID(intval($item_id));
		if (!$item) return false;

		if (!($this->app_name == 'html' && $item['virtual_filename'] == 'home')) 
			$this->addToBreadCrumbs($item['title'],$this->createFriendlyURL("action=viewitem&id={$item['id']}"));
	}	
//_______________________________________________________________________________________________________________//
 	public function displayPageMetaDescription()
	{
		$default_app_meta_desc = $this->getConfig('meta_description');
		echo trim(SAFE_HTML((empty($this->page_meta_description) ? (empty($default_app_meta_desc) ? SITE_META_DESCRIPTION : $default_app_meta_desc) : $this->page_meta_description)));
	}
//_______________________________________________________________________________________________________________//
 	public function displayPageMetaKeywords()
	{
		$default_app_meta_keywords = $this->getConfig('meta_keywords');
		echo trim(SAFE_HTML((empty($this->page_meta_key) ? (empty($default_app_meta_keywords) ? SITE_META_KEYWORDS : $default_app_meta_keywords) : $this->page_meta_key)));
	}		
//_______________________________________________________________________________________________________________//
 	public function getPageMetaDescription()
	{
		return $this->page_meta_description;
	}
//_______________________________________________________________________________________________________________//
 	public function getPageMetaKeywords()
	{
		return $this->page_meta_keywords;
	}
//_______________________________________________________________________________________________________________//
 	public function setPageMetaDescription($meta)
	{
		$this->page_meta_description = $meta;
	}
//_______________________________________________________________________________________________________________//
 	public function setPageMetaKeywords($meta)
	{
		$this->page_meta_keywords = $meta;
	}	
//_______________________________________________________________________________________________________________//
	public function displayItemByID($id=1,$from_cache=false)
	{
		
		$item = $this->getItemByID($id,$from_cache);	
		$this->page_title = $item['title'];
		if ($this->itemColumnExists('meta_description')) $this->page_meta_description = $item['meta_description'];
		if ($this->itemColumnExists('meta_key')) $this->page_meta_keywords = $item['meta_key'];		
		$this->getBreadCrumbsByItemID($item['id']);
		$item_meta_options = $this->translateMetaOptions($item['options']); 
		if ($this->itemColumnExists('date_modified') && $item['date_modified'] != NULL_DATE && !empty($item['date_modified'])) 
			$this->declarePageLastModified($item['date_modified']);
//		$this->resetBreadCrumbs();
		//$this->addToBreadCrumbs($item['title'],$this->createFriendlyURL("action=viewitem&id={$item['id']}"));
		$template_file = SITE_PATH."/apps/{$this->app_name}/view_itemdetails.template.php";
		$current_method = __FUNCTION__;
		if (!file_exists($template_file)) $template_file = SITE_PATH."/includes/view_itemdetails.template.php";
		$this->increaseItemPageView($item[$this->field_id]);
		include_once ($template_file);
	}
	// internal functions
//_______________________________________________________________________________________________________________//
	public function displayPageTitle()
	{
		echo htmlspecialchars($this->page_title);
	}

//_______________________________________________________________________________________________________________//	
	public function genericSearch($fieldname, $keyword, $fields_tobe_selected = '*',$start=0,$end=0,$sortby='', $sortdirection='ASC')
	{
		global $gekko_db;
 
 		$cleankeyword = sanitizeString  ("%{$keyword}%");
	/*	if(empty($sortby) || !array_key_exists($sortby,$this->data_items)) $sortby='';
		$sql = selectSQL($this->table_items,$fields_tobe_selected,$start,$end,$sortby, $sortdirection, "{$fieldname} LIKE {$cleankeyword}",true,SQL_ENFORCE_ROW_LIMIT);
		
 		$search_result = $gekko_db->get_query_result($sql);
		*/
		
		$result_array_files = $this->getAllItems($fields_tobe_selected,"{$fieldname} LIKE {$cleankeyword}",$start,$end,$sortby, $sortdirection,true,true);

		return $search_result;		
	}
	
//_______________________________________________________________________________________________________________//	
	
	public function preventDuplicateItemByFieldName($fieldname,$id, $name)
	{
		global $gekko_db;
		
		$id = intval($id);
		$sql = "SELECT {$fieldname} FROM {$this->table_items} WHERE ({$this->field_id} <> $id) ";
 		$results = $gekko_db->get_query_result($sql);
	
		if ($results)
		{
			foreach ($results as $result) $existing_items_with_the_same_name[] = $result[$fieldname];
			$suggested = $name;
			$i = 1;
			while (in_array($suggested,$existing_items_with_the_same_name))
			{
				$suggested = $name.'_'.$i;
				$i++;
			}
			return $suggested;
		} else return $name;
	}

	
//_______________________________________________________________________________________________________________//
	public function findDuplicateItems($data)
	{
		return false;
	}
//_______________________________________________________________________________________________________________//
	public function validateSaveItem($data)
	{
		return true;
	}
//_______________________________________________________________________________________________________________//		
	public function itemColumnExists($fieldname)
	{
		return array_key_exists($fieldname, $this->data_items);
	}
	
//_______________________________________________________________________________________________________________//	
	public function saveItem($id)
	{
		global $gekko_db;
		
		$retval = array('status','id');
		$data = $this->data_items;
		$datavalues = getVarFromPOST($data);
		//print_r($datavalues);die;
		$current_date_time = date ('Y-m-d H:i:s');
 		if (array_key_exists('date_created', $datavalues))
		{		
			if (($datavalues[$this->field_id] =='new') || (strtotime  ($datavalues['date_created']) == 0)) $datavalues['date_created'] = $current_date_time;
		}
		if (array_key_exists('status', $this->data_items)) $datavalues['status'] = $_POST['status'];

 	    if ($datavalues[$this->field_id] =='new')
		{
			$data = createNewInsertData($data);
			if (array_key_exists('date_modified',$datavalues)) if( strtotime  ($datavalues['date_modified']) == 0) $datavalues['date_modified'] = $current_date_time;	
			if (array_key_exists('date_available',$datavalues)) if(strtotime  ($datavalues['date_available']) == 0) $datavalues['date_available'] = $current_date_time;	
			if (!$this->findDuplicateItems($datavalues))
			{
				if ($this->validateSaveItem($datavalues))
				{
					$sql_set_cmd = InsertSQL($datavalues);
					$sql =  "INSERT INTO `{$this->table_items}` ".$sql_set_cmd;
					$gekko_db->query($sql);
					$retval['status'] = SAVE_OK; // Nov 16, 2011
					$retval['id'] = $gekko_db->last_insert_id();
				} else $reval['status'] = SAVE_INVALID_DATA;
			} else $reval['status'] = SAVE_DUPLICATE;
			return $retval;
		}
		else if (intval($datavalues[$this->field_id]) > 0)
		{
			// Feb 18, 2012
			$previous_item = $this->getItemByID($datavalues[$this->field_id]);

			if ($this->itemColumnExists('date_modified') &&  $datavalues['date_modified'] == $previous_item['date_modified'])
			{
				$datavalues['date_modified'] = $current_date_time;	
			}
			if (!$this->findDuplicateItems($datavalues))
			{
				if ($this->validateSaveItem($datavalues))
				{
					$sql_set_cmd = UpdateSQL($datavalues);
					$id = $datavalues[$this->field_id];	
					$sql =  "UPDATE {$this->table_items} SET ".$sql_set_cmd." WHERE {$this->field_id} = '{$id}';";
					$gekko_db->query($sql);
					$retval['status'] = SAVE_OK; // Nov 16, 2011
					$retval['id'] = intval($id);
				} else $reval['status'] = SAVE_INVALID_DATA;
			} else $reval['status'] = SAVE_DUPLICATE;
			return $retval;
			
		} else
		{
			$reval['status'] = SAVE_INVALID_DATA;	
		}
  	}
	
//_______________________________________________________________________________________________________________//
	public function Run($command)
	{

		switch ($command['action'])
		{
			case 'viewitem': $this->displayItemByID(intval($command[$this->field_id]),$this->cache);break;
			default: return parent::Run($command);break;
		}
		return true;
	}
//_______________________________________________________________________________________________________________//
	
}
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
abstract class basicApplicationSimpleCategories extends basicApplicationLinearData implements interfaceApplicationSimpleCategories 
{
	protected $data_categories;
	protected $table_categories;
	protected $field_category_id;	
	protected $view_template_item_file = 'view_itemdetails.template.php';
	protected $view_template_category_file = 'view_simplecategorylist.template.php';	
	
    public function __construct($app_name, $app_description, $table_items, $field_id, $data_items, $table_categories, $field_category_id, $data_categories)	
    {
		$this->field_category_id = $field_category_id;
		$this->table_categories = $table_categories;
		$this->data_categories = $data_categories;
		parent::__construct($app_name, $app_description, $table_items, $field_id, $data_items);
    }
//_______________________________________________________________________________________________________________//	
	public function getBreadCrumbsByCategoryID($cat_id)
	{
		global $gekko_db;
		
		$current_id = intval($cat_id);
		$last_id = -1;
		$path = '';
		if ($current_id)
		{
			$category = $this->getCategoryByID($current_id);
			$cat_id = $category[$this->field_category_id];
 			$title = $category['title'];
			$this->addToBreadCrumbs($title,$this->createFriendlyURL("action=viewcategory&cid={$cat_id}"));
		}
	}	
//_______________________________________________________________________________________________________________//	
	public function increaseCategoryPageView($cid)
	{
		pageCounterIncreaseImpression($cid,$this->app_name,$this->data_categories,$this->table_categories,'pageview',$this->field_category_id);
	}
//_______________________________________________________________________________________________________________//	
	public function resetCategoryPageView($cid)
	{
		pageCounterIncreaseImpression($cid,$this->app_name,$this->data_categories,$this->table_categories,'pageview',$this->field_category_id,true);
	}
//_______________________________________________________________________________________________________________//	
	public function getTotalItemCountByCategoryID($category_id, $criteria='',$cache=false)
	{
		global $gekko_db;
				
		$cid = intval($category_id);
		$criteria_txt = '';
		if (!empty($criteria)) $criteria_txt = " AND {$criteria}";
		
 		$sql = "SELECT count({$this->field_id}) as total_item_count FROM {$this->table_items} WHERE category_id = {$cid} {$criteria_txt}";
		$total = $gekko_db->get_query_singleresult($sql,$cache);
		return  $total['total_item_count'];
	}
//_______________________________________________________________________________________________________________//	
	public function getTotalCategoryCount($criteria='',$cache=false)
	{
		global $gekko_db;
				
		$cid = intval($category_id);
		$criteria_txt = '';
		if (!empty($criteria)) $criteria_txt = " WHERE {$criteria}";
		
 		$sql = "SELECT count({$this->field_category_id}) as total_category_count FROM {$this->table_categories} {$criteria_txt}";
		$total = $gekko_db->get_query_singleresult($sql,$cache);
		return  $total['total_category_count'];
	}

//_______________________________________________________________________________________________________________//	
	public function getBreadCrumbsByItemID($item_id)
	{
		global $gekko_db;
		
		$item = $this->getItemByID(intval($item_id));
		if (!$item) return false;
		
		$item_id = $item[$this->field_id];
		$current_cat_id = $item[$this->field_category_id];
		$last_id = -1;
		$path = '';

		if ($current_cat_id)
		{
			$category = $this->getCategoryByID(intval($current_cat_id));
			$category_cid = $category[$this->field_category_id];
			$this->addToBreadCrumbs($title,$this->createFriendlyURL("action=viewcategory&cid={$category_cid}"));
			$current_id = $last_id;
		}
		if (!($this->app_name == 'html' && $item['virtual_filename'] == 'home')) 
			$this->addToBreadCrumbs($item['title'],$this->createFriendlyURL("action=viewitem&id={$item_id}"));
	}	

//_______________________________________________________________________________________________________________//
 	public function interpretFriendlyURL($urlpath)
	{/*
		
		input: http://gekkocms/directory/category/nested/listing.page5.html
		input: http://gekkocms/html/testcategory/welcome.html
		input: http://gekkocms/html/category/nested/
		input: http://gekkocms/contacts/balioffice.html
		input: http://gekkocms/users/action/register.html
		input: http://gekkocms/users/action/login.html
		input: http://gekkocms/users/action/logout
		input: http://gekkocms/store/action/checkout
	*/

		if (SEF_ENABLED && !$_GET['app'])
		{

			$parsedurl = $this->probeFriendlyURLDestination($urlpath);
			$url = $parsedurl['url'];
			$url_array = $parsedurl['url_array'];

			$command = $this->getItemOrCategoryToViewFromFullVirtualFilename($url,true);

			if ($command['action'] == '404error') 
			{
			 	// let's try passing this to child
				if ($url_array[0]=='action' &&  $url_array[1])
				{
					//$command['action'] = $url_array[1];
					$count = count($url_array);
					for ($i =0; $i < $count; $i++)
					{
						$key = $url_array[$i];
						$val = $url_array[$i+1];
						$i++;
						if ($key && $val)
							$command[$key] = $val;
					}
				}
			}
		} else 
		{
			if (count($_GET)==0 || empty($_GET['action'])) $command['action']='main'; else $command = $_GET;
		}
		return $command;
	}
	
//_______________________________________________________________________________________________________________//
 	public function createFriendlyURL($str)
	{ 
		/*
			input: index.php?app=html&action=view&id=c1
			input: index.php?app=html&action=view&id=i10
			input: index.php?app=users&action=register
			
			action=view&id=c5&page=10
			action=checkout    
			action=login
		*/
		if (SEF_ENABLED)
		{
			$param_array = explode('&',$str);
			$command_array = array();
			parse_str($str, $command_array); // replaced with this- May 24, 2010
			switch ($command_array['action'])
			{
				case 'viewcategory':$final_url = $this->getFullPathByCategoryID($command_array[$this->field_category_id]);
									if ($command_array['pg']) $final_url.="pg{$command_array['pg']}.html";
									
									break;
				case 'viewitem': $final_url= $this->getFullPathByItemID($command_array[$this->field_id]);break;
				default:if (!empty($command_array) && $command_array['action'] != '') 
						{
							if (array_key_exists ('app',$command_array)) unset ($command_array['app']);
							$keys = array_keys($command_array);
							foreach ($keys as $key)
							{
								$final_url.= "/{$key}/{$command_array[$key]}";
							}
							//$final_url.='/';	
							//$final_url = '/'.$command_array['action'].'.ghtml';
						} else $final_url = '/'; // For the main app - Feb 4, 2012
			}
			$app_alias = getApplicationAlias($this->app_name); // May 24, 2010
			$final_url = SITE_HTTPBASE.'/'.$app_alias.$final_url; // delete - Oct 1, 2010

//			$final_url = '/'.$app_alias.$final_url;
		} else 
		{
			parse_str("app={$this->app_name}&{$str}",$command_array);
			$array_keys = array_keys($command_array); // separate it 
			foreach ($array_keys as $key)
				if (empty($command_array[$key])) unset ($command_array[$key]);
			$final_url =  SITE_HTTPBASE.'/'."index.php?".http_build_query($command_array);
		}
		$final_url = removeMultipleSlashes( $final_url);
//		echo $final_url;
		return $final_url;
	}

//_______________________________________________________________________________________________________________//
	public function getItemOrCategoryToViewFromFullVirtualFilename($url, $enable_redirect_no_trailingslash_folder=false)
	{


		
		define('SLASH_INDEX_DOT_HTML_LENGTH',10); // '/index.html'
		$url_array = explode('/',$url);
//		DEBUG_ARRAY($url_array);
		$command = array();
		$folder_requestpage=0;
		$found = false;
		$filetype = ''; // category = c, item = i
		$depth = count($url_array);
		$url_length = strlen($url);
		$request_filename = basename($url);
		$file_path_info = null;
		if ($request_filename) $file_path_info = pathinfo($request_filename);
		if (empty($url) || $url == 'index.html')
		{
			$command['action'] = 'main';
			return $command;
		}
		// Step 0 - Reset - clean up possible directory view mode with "/index.html"
		// possible /html/folder1/folder2 (but no trailing slash)

		if ( $enable_redirect_no_trailingslash_folder && ( $file_path_info['extension'] != 'html' ) && ( $file_path_info['extension'] != 'do' ) && ( strrpos($url, '/') != $url_length-1 ) )
		{
			$url.= '/';
			$url_array = explode('/',$url);
			array_splice($url_array,0,2);
			$depth = count($url_array);
			$url_length = strlen($url);
			if ($this->getCategoryByVirtualFilename($request_filename))
			{
				$app_alias = getApplicationAlias($this->app_name);
				redirectURL(SITE_URL.SITE_HTTPBASE."/{$app_alias}/{$url}");exit;
			}else return array('action'=>'404error');
		}
		if ($url_array[$depth-1] == 'index.html')
		{
			// $filetype = 'c';
			$url_array[$depth-1] = '';
			$url = substr ($url,0,$url_length - SLASH_INDEX_DOT_HTML_LENGTH);
			$depth = count($url_array); // reset again
			$url_length = strlen($url); // reset again
		}

		if($c=preg_match_all ("/(pg)(\d+).*?(html)/is", $url_array[$depth-1], $x))
		{
			$folder_requestpage=$x[2][0];
			$pg_length =  strlen($url_array[$depth-1]);
 			$url_array[$depth-1] = '';
			$url = substr ($url,0,$url_length - $pg_length);
			$depth = count($url_array); // reset again
			$url_length = strlen($url); // reset again
		}
		// 1 - check for '/' in the end of url, or '/index.html'
		$pos1a = strrpos($url, '/');
 		if ($pos1a == $url_length-1)
		{
			$last_item_name = $url_array[$depth-2];
			$cats = $this->getCategoryByVirtualFilename($last_item_name);
			$user_request_url = '/'.implode('/',$url_array);
			if ($cats)
			foreach ($cats as $cat)
			{
				$possible_cat_id = $cat['cid'];
				$possible_cat_path = $this->getFullPathByCategoryID($possible_cat_id);
				if ($possible_cat_path == $user_request_url)
				{
					$command['action'] = 'viewcategory';
					$command['cid'] = $possible_cat_id;
					if ($folder_requestpage > 0 ) $command['pg'] = $folder_requestpage;
					return $command;
				}
			}
			// End of find folder section
		} else
		//
		{ //  FIND FILE


			$items = $this->getItemByVirtualFilename($file_path_info['filename']);
			if ($items)
			foreach ($items as $item)
			{
				$catpath = $this->getFullPathByCategoryID($item['category_id']);
				$compare1 = $catpath.$file_path_info['filename'].'.'.$file_path_info['extension'];
				$compare1 = removeMultipleSlashes( $compare1);
				$compare2 = '/'.$url;
				if ($compare1 == $compare2)
				{
					$command['action'] = 'viewitem';
					$command['id'] = $item['id'];
					return $command;
				}
			}
		}
		$command['action'] = '404error';
		return $command;
	}

//_______________________________________________________________________________________________________________//	
	public function getFullPathByCategoryID($cat_id)
	{
		global $gekko_db;
		
		$current_id = intval($cat_id);
		
		$sql = "SELECT virtual_filename FROM {$this->table_categories} where {$this->field_category_id} = '{$current_id}'";
 		$id_r =$gekko_db->get_query_result($sql,true);
 		$dirname = $id_r [0]['virtual_filename'];
		$path=$dirname.'/'.$path;			
		
		return '/'.$path;
	}	
//_______________________________________________________________________________________________________________//	
	public function getFullPathByItemID($item_id)
	{
		global $gekko_db;
		
		$item_id = intval($item_id);		
		$sql = "SELECT virtual_filename,category_id FROM {$this->table_items} where {$this->field_id} = '{$item_id}'";
 		$item = $gekko_db->get_query_singleresult($sql,true);
		if (!$item) return false;
		
		$current_id = $item['category_id'];
		$last_id = -1;
		$path = '';
		while ($last_id != 0)
		{
			$sql = "SELECT virtual_filename FROM {$this->table_categories} where {$this->field_category_id} = '{$current_id}'";
 			$id_r = $gekko_db->get_query_result($sql,true);
			$last_id = $id_r [0]['parent_id'];
			$dirname = $id_r [0]['virtual_filename'];
			$path=$dirname.'/'.$path;			
			$current_id = $last_id;
		}
		return '/'.$path.$item['virtual_filename'].'.html';
	}	
	
//_______________________________________________________________________________________________________________//
	
	public function getDefaultCategoryID()
	{
		global $gekko_db;
		
		$sql = "SELECT {$this->field_category_id} FROM {$this->table_categories} ORDER BY {$this->field_category_id} ASC";
		$allcats = $gekko_db->get_query_result($sql,true);
		if ($allcats)
		{
			$catid = $allcats[0][$this->field_category_id];		
			return $catid;
		} else return 0;
	}

//_______________________________________________________________________________________________________________//	
	public function getCategoryTableName()
	{
		return $this->table_categories;
	}
	
//_______________________________________________________________________________________________________________//	
	public function getCategoryFieldNames()
	{
		return $this->data_categories;
	}
//_______________________________________________________________________________________________________________//	
	public function getFieldCategoryID()
	{
		return $this->field_category_id;
	}
	
//_________________________________________________________________________//
	public function getAllChildItemsInMultipleCategories($multiple_category_ids)
	{
		global $gekko_db;
		
		$refined_result = '';
		$str_sql = implode(",", $multiple_category_ids);
		if ($str_sql)
		{
			$sql = "SELECT {$this->field_id} FROM {$this->table_items} WHERE category_id in ({$str_sql})";
 			$result_array_items_in_these_categories = $gekko_db->get_query_result($sql);
			foreach ($result_array_items_in_these_categories as $individual_item)
			$refined_result[] = $individual_item[$this->field_id];
		}
		return  $refined_result;
	}

//_______________________________________________________________________________________________________________//
	public function getAllCategories($fields='*',$extra_criteria='',$start=0,$end=0,$sortby='', $sortdirection='ASC')
	{
		global $gekko_db;
		
		if(!empty($sortby) && strpos($sortby,',')===false)	if (!array_key_exists($sortby,$this->data_categories))
		{
			 $sortby=$this->getFieldCategoryID();
		}
		$sql = selectSQL($this->table_categories,$fields,$extra_criteria,$start,$end,$sortby, $sortdirection, true,SQL_ENFORCE_ROW_LIMIT);	
//		echo $sql;die;
 		$categories = $gekko_db->get_query_result($sql);
		return $categories;
	}
//_______________________________________________________________________________________________________________//
	
	public function getCategoryByID($id,$from_cache=false)
	{
		global $gekko_db;

		$sql = "SELECT * FROM {$this->table_categories} WHERE {$this->field_category_id} = '".intval($id)."'";
  		$category = $gekko_db->get_query_singleresult($sql,$from_cache);
		return $category;
	}
//_______________________________________________________________________________________________________________//
	public function getCategoryByVirtualFilename($input_filename, $parent_id=-1)
	{
		global $gekko_db;

 		if (!empty($input_filename))
		{
			$filename = sanitizeString($input_filename);
			$str = "";
			$parent_id = intval($parent_id);
			if ($parent_id >= 0) $str = " AND parent_id = {$parent_id}";
			$sql = "SELECT * FROM {$this->table_categories} WHERE virtual_filename = {$filename}{$str}";
 			$categories = $gekko_db->get_query_result($sql,true);
			return $categories;
		} else return false;
	}
//_______________________________________________________________________________________________________________//
	public function getItemsByCategoryID($id,$fields='*',$extra_criteria='',$start=0,$end=0,$sortby='', $sortdirection='ASC',$from_cache=false)
	{
		global $gekko_db;
	
		if (!empty($extra_criteria)) $criteria_txt = " AND {$extra_criteria}"; else $criteria_txt = '';
		
		return $this->getAllItems($fields, "category_id = '".intval($id)."' {$criteria_txt}",  $start,$end,$sortby, $sortdirection, $from_cache);
		
	}

//_______________________________________________________________________________________________________________//
	public function displayItemsInCategoryByID($id=1, $pg=1,  $sortby='', $sortdirection='ASC',$from_cache=false,$standard_criteria = 'status > 0')
	{
		$perpage = $category['items_per_page'];
		if ($perpage == 0) $perpage = $this->getNumberOfListingsPerPage();
		$category = $this->getCategoryByID($id,$from_cache);
		$total_item_count = $this->getTotalItemCountByCategoryID($id,$standard_criteria,$from_cache);
		$pagination = getStartAndEndForItemPagination($pg, $perpage,$total_item_count);
		$category_meta_options = $this->translateMetaOptions($category['options']); 
		$items = $this->getItemsByCategoryID($id,'*',$standard_criteria, $item_start,$item_end, $sortby, $sortdirection,$from_cache);
		$this->page_title = $category['title'];
		if ($pg > 1) $this->page_title.=" - Page {$pg}";
		if (array_key_exists('meta_description',$category)) $this->page_meta_description = $category['meta_description'];
		if (array_key_exists('meta_key',$category)) $this->page_meta_key = $category['meta_key'];		
		
		$childitems_sortby = (array_key_exists('items_sortby',$category_meta_options)) ? $category_meta_options['items_sortby'] : $sortby;
		$childitems_sortdirection = (array_key_exists('items_sortdirection',$category_meta_options)) ?  $category_meta_options['items_sortdirection'] : $sortdirection;
 
		$this->getBreadCrumbsByCategoryID($category[$this->field_category_id]);
		
		$current_method = __FUNCTION__;
		$template_file = SITE_PATH."/apps/{$this->app_name}/".$this->view_template_category_file;
		$this->increaseCategoryPageView($category[$this->field_category_id]);
		if (!file_exists($template_file)) $template_file = SITE_PATH."/includes/".$view_template_category_file;
		include_once($template_file);
	}
	
//_______________________________________________________________________________________________________________//
	function delete($mixed_items_to_delete)
	{ // mixed item = categories + items
	//test case:
	
	//http://gekkocms/admin/index.php?page=html&ajax=1&action=ajax_delete&items=c3
	//http://gekkocms/admin/index.php?page=html&ajax=1&action=ajax_delete&items=c5,c6,c9,i4,i14
		global $gekko_db;
		$id = 1;
		$mixed_items_array = explode(',', $mixed_items_to_delete); // e.g: c5,c6,c9,i4,i14

		$cats_to_delete = NULL;
		$all_cats_to_delete = NULL;

		// Process sub-folders first

		foreach ($mixed_items_array as $mixed_item)
		{
			$current_id = substr($mixed_item,1);
			if (strpos ($mixed_item, 'c') > -1)
			{
				/*// Get the sub-child for each cats
				$cats_to_delete = $this->TraverseCategories($current_cat_id);
				// Now Process
				if ($all_cats_to_delete)
					$all_cats_to_delete = array_merge($all_cats_to_delete, $cats_to_delete);
				else
					$all_cats_to_delete = $cats_to_delete;*/
					$all_cats_to_delete[] = $current_id;

			} else // else if it's an item instead
			{
 					 $items_to_delete[] = $current_id;
			}
		}
		// Process files
		if ($all_cats_to_delete)
		{
			foreach($all_cats_to_delete as $a_cat_to_delete) $cat_id_filler_for_sql[] = $a_cat_to_delete[$this->field_category_id];
			$result_array_items_in_these_categories = $this->getAllChildItemsInMultipleCategories($cat_id_filler_for_sql);
		}
		// Did the user select any items to delete?
		if ($items_to_delete)
		{
			if ($result_array_items_in_these_categories)
				$items_to_delete = array_merge( $items_to_delete, $result_array_items_in_these_categories);
		}
		else
			$items_to_delete = $result_array_items_in_these_categories;

		// now delete all of them - $all_cats_to_delete + $items_to_delete
		if ($items_to_delete) $items_to_delete_str = implode(",", $items_to_delete);
		if ($cat_id_filler_for_sql) $cats_to_delete_str = implode(",", $cat_id_filler_for_sql);
		if ($items_to_delete_str)
		{
			$items_to_delete_str = implode(",", $items_to_delete);
			$sql1 = "DELETE FROM {$this->table_items} WHERE {$this->field_id} in ({$items_to_delete_str})";
			$gekko_db->query($sql1);
		}
		if ($cats_to_delete_str)
		{
			$cats_to_delete_str = implode(",", $cat_id_filler_for_sql);
			$sql2 = "DELETE FROM {$this->table_categories} WHERE {$this->field_category_id} in ({$cats_to_delete_str})";

			$gekko_db->query($sql2);
		}
	}
//_______________________________________________________________________________________________________________//

	public function displayCategories($id=1, $pg=1, $sortby='', $sortdirection='ASC', $standard_criteria='status = 1')
	{
  		$category = $this->getCategoryByID($id,$from_cache);		
		$perpage = $category['items_per_page'];
		if ($perpage == 0) $perpage = $this->getNumberOfListingsPerPage();
		$total_category_count = $this->getTotalCategoryCount($id,$standard_criteria,$from_cache);
		$pagination = getStartAndEndForItemPagination($pg, $perpage,$total_category_count );
		$categories = array();
		$childcategories = $this->getAllCategories($id,'*',$standard_criteria,$pagination['start'],$pagination['end'],$sortby,$sortdirection);
		$this->page_title = $category['title'];
		if ($pg > 1) $this->page_title.=" - Page {$pg}";
		if (array_key_exists('meta_description',$category)) $this->page_meta_description = $category['meta_description'];
		if (array_key_exists('meta_key',$category)) $this->page_meta_key = $category['meta_key'];		
		
		$this->getBreadCrumbsByCategoryID($category[$this->field_category_id]);
		
		$current_method = __FUNCTION__;
		
		$template_file = SITE_PATH."/apps/{$this->app_name}/".$this->view_template_category_file;
		if (!file_exists($template_file)) $template_file = SITE_PATH."/includes/".$this->view_template_category_file;
		include_once ($template_file);
	}
//_______________________________________________________________________________________________________________//		
	public function categoryColumnExists($fieldname)
	{
		return array_key_exists($fieldname, $this->data_categories);
	}
	

//_______________________________________________________________________________________________________________//
	public function saveCategory($id)
	{
		global $gekko_db;
		global $current_directory_from_save;

		$data = $this->data_categories;
		$current_date_time = date ('Y-m-d H:i:s');

		$datavalues = getVarFromPOST($data);

 		if (array_key_exists('date_created', $data))
		{
			if (($datavalues[$this->field_category_id] =='new') || (strtotime  ($datavalues['date_created']) == 0)) $datavalues['date_created'] = $current_date_time;
		}

		if (array_key_exists('status', $this->data_categories)) $datavalues['status'] = $_POST['status'];
		if ($datavalues[$this->field_category_id] =='new')
		{
			$data = createNewInsertData($data);
			//if (strtotime  ($datavalues['date_modified']) == 0) $datavalues['date_modified'] = $current_date_time;
			//if (strtotime  ($datavalues['date_available']) == 0) $datavalues['date_available'] = $current_date_time;
	 		//if (array_key_exists('status', $datavalues)) $datavalues['status'] == 1;
			if (!$this->findDuplicateCategories($datavalues))
			{
				if ($this->validateSaveCategory($datavalues))
				{
					$sql_set_cmd = InsertSQL($datavalues);

					$sql =  "INSERT INTO `{$this->table_categories}` ".$sql_set_cmd;
					$gekko_db->query($sql);
					$retval['status'] = SAVE_OK; // Nov 16, 2011
					$retval['id'] = $gekko_db->last_insert_id();
				} else $reval['status'] = SAVE_INVALID_DATA;
			} else $reval['status'] = SAVE_DUPLICATE;
			return $retval;
		}
		else if (intval($datavalues[$this->field_category_id]) > 0)
		{
			// Feb 18, 2012
			$previous_category = $this->getCategoryByID($datavalues[$this->field_category_id]);

			if ($this->categoryColumnExists('date_modified') &&  ($datavalues['date_modified'] == $previous_category['date_modified'] || $datavalues['date_modified'] == NULL_DATE))
			{
				$datavalues['date_modified'] = $current_date_time;	
			}
			if (!$this->findDuplicateCategories($datavalues))
			{
				if ($this->validateSaveCategory($datavalues))
				{

					$sql_set_cmd = UpdateSQL($datavalues);
					$id = $datavalues[$this->field_category_id];

					$sql =  "UPDATE {$this->table_categories} SET ".$sql_set_cmd." WHERE {$this->field_category_id} = '{$id}';";
					$gekko_db->query($sql);
					$retval['id'] = intval($id);					
					$retval['status'] = SAVE_OK; // Nov 16, 2011
				} else $reval['status'] = SAVE_INVALID_DATA;
			} else $reval['status'] = SAVE_DUPLICATE;
			return $retval;
		}
		else
		{
			$reval['status'] = SAVE_INVALID_DATA;	
		}		
	}
//_______________________________________________________________________________________________________________//
	public function saveItem($id)
	{
		if ($_POST['category_id'] == 0)
		{
			$_POST['category_id'] = $this->getDefaultCategoryID();
		}

		$savestatus = parent::saveItem($id);
		return $savestatus;
  	}
//_______________________________________________________________________________________________________________//
	public function validateSaveCategory($data)
	{
		return true;
	}

//_______________________________________________________________________________________________________________//
	public function findDuplicateCategories($data)
	{
		return false;
	}
//_______________________________________________________________________________________________________________//	
	
	public function preventDuplicateItemInThisCategoryByFieldName($fieldname,$id, $name)
	{
		global $gekko_db;
		
		$id = intval($id);
		$catid = 0;
		if ($id != 0)
		{
			$item_info = $this->getItemByID($id);
			$catid = $item_info['category_id'];
		}
		if ($item_info)
                    if (array_key_exists('category_id',$item_info)) $sql_with_category = " (category_id = {$catid}) AND  "; // fix for those with multiple cats - Oct 11, 2010
		$sql = "SELECT {$fieldname} FROM {$this->table_items} WHERE {$sql_with_category} ({$this->field_id} <> $id) ";
		
 		$results = $gekko_db->get_query_result($sql);
		if ($results)
		{
			
			foreach ($results as $result) $existing_items_with_the_same_name[] = $result[$fieldname];
			$suggested = $name;
			$i = 1;
			while (in_array($suggested,$existing_items_with_the_same_name))
			{
				$suggested = $name.'_'.$i;
				$i++;
			}
			return $suggested;
		} else return $name;
	}
	
//_______________________________________________________________________________________________________________//	
	
	public function preventDuplicateCategoryInThisCategoryByFieldName($fieldname,$id, $name)
	{
		global $gekko_db;
		
		$id = intval($id);

		$sql = "SELECT {$fieldname} FROM {$this->table_categories} WHERE ({$this->field_category_id} <> $id) ";
 		$results = $gekko_db->get_query_result($sql);
	
		if ($results)
		{
			foreach ($results as $result) $existing_cats_with_the_same_name[] = $result[$fieldname];
			$suggested = $name;
			$i = 1;
			while (in_array($suggested,$existing_cats_with_the_same_name))
			{
				$suggested = $name.'_'.$i;
				$i++;
			}
			return $suggested;
		} else return $name;
	}
	
//_______________________________________________________________________________________________________________//
	public function Run($command)
	{
		
		switch ($command['action'])
		{
			case 'viewcategory':$this->displayItemsInCategoryByID($command['cid'],$command['pg'],'','ASC',$this->cache);break;
 			default: return parent::Run($command);break;
		}

		return true;
	}
//_______________________________________________________________________________________________________________//
	
}

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
abstract class basicApplicationNestedCategories extends basicApplicationSimpleCategories implements interfaceApplicationNestedCategories 
{
	protected $mode;
	protected $field_parent_id;
	protected $view_template_category_file = 'view_nestedcategorylist.template.php';	
	
//_______________________________________________________________________________________________________________//	
    public function __construct($app_name, $app_description, $table_items, $field_id, $data_items, $table_categories, $field_category_id, $data_categories)	
	{
		parent::__construct($app_name, $app_description, $table_items, $field_id, $data_items, $table_categories, $field_category_id, $data_categories);
		$this->mode = 'nested';
	}
//_______________________________________________________________________________________________________________//	
	public function getTotalChildCategoryCountByCategoryID($parent_cid,$criteria='',$cache=false)
	{
		global $gekko_db;
				
		$pid = intval($parent_cid);
		$criteria_txt = '';
		if (!empty($criteria)) $criteria_txt = " AND {$criteria}";
 		$sql = "SELECT count({$this->field_category_id}) as total_category_count FROM {$this->table_categories} WHERE parent_id = {$pid} {$criteria_txt} ";
		$total = $gekko_db->get_query_singleresult($sql,$cache);
		$total_category_count = $total['total_category_count'];
		return $total_category_count;
	}

//_______________________________________________________________________________________________________________//	
	public function getBreadCrumbsByItemID($item_id)
	{
		global $gekko_db;
		
		$item = $this->getItemByID(intval($item_id));
		if (!$item) return false;
		
		$current_id = $item['category_id'];
		$last_id = -1;
		$path = '';

		while ($last_id != 0 && $current_id != 0)
		{
			$category = $this->getCategoryByID($current_id);
			$cat_id = $category[$this->field_category_id];
 			$last_id = $category['parent_id'];
			$title = $category['title'];

			$this->addToBreadCrumbs($title,$this->createFriendlyURL("action=viewcategory&cid={$cat_id}"));
			$current_id = $last_id;
		}
		if (!($this->app_name == 'html' && $item['virtual_filename'] == 'home')) 
			$this->addToBreadCrumbs($item['title'],$this->createFriendlyURL("action=viewitem&id={$item['id']}"));
		
		//return '/'.$path.$item['virtual_filename'].'.html';
	}	
//_______________________________________________________________________________________________________________//	
	public function getFullPathByItemID($item_id)
	{
		global $gekko_db;
		
		$item_id = intval($item_id);		
		$sql = "SELECT virtual_filename,category_id FROM {$this->table_items} where {$this->field_id} = '{$item_id}'";
 		$item = $gekko_db->get_query_singleresult($sql,true);
		if (!$item) return false;
		
		$current_id = $item['category_id'];
		$last_id = -1;
		$path = '';
		while ($last_id != 0)
		{
			$sql = "SELECT parent_id, virtual_filename FROM {$this->table_categories} where {$this->field_category_id} = '{$current_id}'";
	 		$id_r = $gekko_db->get_query_result($sql,true);
			
			$last_id = $id_r [0]['parent_id'];
			$dirname = $id_r [0]['virtual_filename'];
			$path=$dirname.'/'.$path;			
			$current_id = $last_id;
		}
		return '/'.$path.$item['virtual_filename'].'.html';
	}	
	
//_______________________________________________________________________________________________________________//	
	public function getFullPathByCategoryID($cat_id)
	{
		global $gekko_db;
		
		$current_id = intval($cat_id);
		$last_id = -1;
		while ($last_id != 0)
		{
			$sql = "SELECT parent_id, virtual_filename FROM {$this->table_categories} where {$this->field_category_id} = '{$current_id}'";
	 		$id_r = $gekko_db->get_query_result($sql,true);
			$last_id = $id_r [0]['parent_id'];
			$dirname = $id_r [0]['virtual_filename'];
			$path=$dirname.'/'.$path;			
			$current_id = $last_id;
		}
		return '/'.$path;
	}	
	
//_______________________________________________________________________________________________________________//
	public function getChildCategoriesByParentID($id,$fields='*',$extra_criteria='',$start=0,$end=0,$sortby='', $sortdirection='ASC',$from_cache=false)
	{
		if (!empty($extra_criteria)) $criteria_txt = " AND {$extra_criteria}"; else $criteria_txt = '';
		return $this->getAllCategories($fields, "parent_id='".intval($id)."' {$criteria_txt}", $start,$end,$sortby, $sortdirection, $from_cache);
	}
	
//_______________________________________________________________________________________________________________//
	public function displayItemsInCategoryByID($id=1, $pg=1,$sortby='', $sortdirection='ASC', $from_cache = false,$standard_criteria = 'status > 0')
	{
 		$category = $this->getCategoryByID($id,$from_cache);	
		if (!$category) 
		{
			$this->displayHTTPError(404);
			return false;
		}
		$perpage = $category['items_per_page'];
		$category_meta_options = $this->translateMetaOptions($category['options']); 
		
		if ($perpage == 0) $perpage = $this->getNumberOfListingsPerPage();
		$total_item_count = $this->getTotalItemCountByCategoryID($id,$standard_criteria,$from_cache);
		$total_child_category_count = $this->getTotalChildCategoryCountByCategoryID($id,$standard_criteria,$from_cache);
		$pagination = getStartAndEndForItemPagination($pg, $perpage,$total_child_category_count + $total_item_count);
		$this->setPageTitle(($pg > 1) ? $category['title']." - Page {$pg}" : $category['title']);		
		if (array_key_exists('meta_description',$category)) $this->setPageMetaDescription($category['meta_description']);
		if (array_key_exists('meta_key',$category)) $this->setPageMetaKeywords($category['meta_key']);
		$this->getBreadCrumbsByCategoryID($category[$this->field_category_id]);
		$this->increaseCategoryPageView($category[$this->field_category_id]);
		$childcategories = array();
		$items = array();
		$childcategories_sortby = (array_key_exists('categories_sortby',$category_meta_options)) ? $category_meta_options['categories_sortby'] : $sortby;
		$childcategories_sortdirection = (array_key_exists('categories_sortdirection',$category_meta_options)) ?  $category_meta_options['categories_sortdirection'] : $sortdirection;
		$childitems_sortby = (array_key_exists('items_sortby',$category_meta_options)) ? $category_meta_options['items_sortby'] : $sortby;
		$childitems_sortdirection = (array_key_exists('items_sortdirection',$category_meta_options)) ?  $category_meta_options['items_sortdirection'] : $sortdirection;
		
		/*
			Variables to be passed to the viewer:
			$categories, $childcategories, $items, $pg, $perpage, $pagination, $category_meta_options, $total_item_count, $total_child_category_count, $error_message
		*/
		
		/* Don't enable it just yet (Feb 25, 2012) .. pending testing
 		if ($this->itemColumnExists('date_modified')) 
			$this->declarePageLastModified($this->getMaxDateFromArray($categories));
		*/

		if (($pg-1)*$perpage <= $total_item_count + $total_child_category_count)
		{
			if ($total_child_category_count > 0 && $total_child_category_count > $start)
			{
				$category_end = $pagination['start'] + min($total_category_count - $pagination['start'], $perpage);
				$childcategories = $this->getChildCategoriesByParentID($id,'*',$standard_criteria,$pagination['start'],$pagination['end'],$childcategories_sortby,$childcategories_sortdirection, $from_cache);
			}

			if ($total_item_count > 0 && $pagination['end'] - $total_child_category_count > 0)
			{   
				$item_start = max(0,$pagination['start']-$total_child_category_count);
				$item_end = $item_start + min($pagination['end'] - $total_child_category_count, $perpage);	
			
				$items = $this->getItemsByCategoryID($id,'*',$standard_criteria, $item_start,$item_end, $childitems_sortby, $childitems_sortdirection,$from_cache);
			}
		} else $error_message = "Page is outside of valid range";

		$current_method = __FUNCTION__;
		$template_file = SITE_PATH."/apps/{$this->app_name}/".$this->view_template_category_file;
		if (!file_exists($template_file)) $template_file = SITE_PATH."/includes/".$this->view_template_category_file;
		include_once ($template_file);
	}	
//_________________________________________________________________________//
	public function TraverseCategories($catid)
	{
		global $gekko_db;

		if ($catid > 0)
		{
			$sql = "SELECT {$this->field_category_id} FROM {$this->table_categories}  WHERE  parent_id = {$catid}";	
 			$categories  = $gekko_db->get_query_result($sql);
			$i=0;
			while ($i < sizeof($categories))
			{
				$current_id = $categories[$i]['cid'];$i++;
				$sql = "SELECT {$this->field_category_id} FROM  {$this->table_categories}  WHERE  parent_id = {$current_id}";	
 				$categories_tmp  = $gekko_db->get_query_result($sql);
				$categories = array_merge($categories, $categories_tmp);
			} // end while
			
			$sql = "SELECT {$this->field_category_id} FROM {$this->table_categories} WHERE cid = {$catid}";	
 			$my_own_cat  = $gekko_db->get_query_result($sql);
			$categories = array_merge($categories, $my_own_cat);
		}
		return $categories;
	}
	
//_______________________________________________________________________________________________________________//	
	
	public function preventDuplicateCategoryInThisCategoryByFieldName($fieldname,$id, $name)
	{
		global $gekko_db;
		
		$id = intval($id);
		$catid = 0;
		if ($id != 0)
		{
			$cat_info = $this->getCategoryByID($id);
			$catid = $cat_info['parent_id'];
		}
		$sql = "SELECT {$fieldname} FROM {$this->table_categories} WHERE (parent_id = {$catid}) AND ({$this->field_category_id} <> $id) ";
 		$results = $gekko_db->get_query_result($sql);
		if ($results)
		{
			foreach ($results as $result) $existing_cats_with_the_same_name[] = $result[$fieldname];
			$suggested = $name;
			$i = 1;
			while (in_array($suggested,$existing_cats_with_the_same_name))
			{
				$suggested = $name.'_'.$i;
				$i++;
			}
			return $suggested;
		} else return $name;
	}
	
//_______________________________________________________________________________________________________________//	
	function delete($mixed_items_to_delete)
	{ // mixed item = categories + items
	//test case:
	//http://gekkocms/admin/index.php?page=html&ajax=1&action=ajax_delete&items=c3
	//http://gekkocms/admin/index.php?page=html&ajax=1&action=ajax_delete&items=c5,c6,c9,i4,i14
		global $gekko_db;
		$id = 1;
		$mixed_items_array = explode(',', $mixed_items_to_delete); // e.g: c5,c6,c9,i4,i14

		$cats_to_delete = NULL;
		$all_cats_to_delete = NULL;
		
		// Process sub-folders first

		foreach ($mixed_items_array as $mixed_item)
		{
			$current_id = substr($mixed_item,1); // 11 is the next string after 
			if (strpos ($mixed_item, 'c') > -1)
			{
				// Get the sub-child for each cats
				$cats_to_delete = $this->TraverseCategories($current_id);
				// Now Process
				if ($all_cats_to_delete)
					$all_cats_to_delete = array_merge($all_cats_to_delete, $cats_to_delete);
				else
					$all_cats_to_delete = $cats_to_delete;
			} else // else if it's an item instead
			{
					 $items_to_delete[] = $current_id;
			}
		}
		// Process files
		if ($all_cats_to_delete)
		{
			foreach($all_cats_to_delete as $a_cat_to_delete) $cat_id_filler_for_sql[] = $a_cat_to_delete[$this->field_category_id];
			$result_array_items_in_these_categories = $this->getAllChildItemsInMultipleCategories($cat_id_filler_for_sql);
		}
		// Did the user select any items to delete?
		if ($items_to_delete)
		{
			if ($result_array_items_in_these_categories)
				$items_to_delete = array_merge( $items_to_delete, $result_array_items_in_these_categories);
		}
		else
			$items_to_delete = $result_array_items_in_these_categories;
		
		// now delete all of them - $all_cats_to_delete + $items_to_delete
		if ($items_to_delete) $items_to_delete_str = implode(",", $items_to_delete);
		if ($cat_id_filler_for_sql) $cats_to_delete_str = implode(",", $cat_id_filler_for_sql);
		
		if ($items_to_delete_str)
		{
			$items_to_delete_str = implode(",", $items_to_delete);
			$sql1 = "DELETE FROM {$this->table_items} WHERE {$this->field_id} in ({$items_to_delete_str})";
			$gekko_db->query($sql1);
		}
		if ($cats_to_delete_str)
		{
			$cats_to_delete_str = implode(",", $cat_id_filler_for_sql);
			$sql2 = "DELETE FROM {$this->table_categories} WHERE {$this->field_category_id} in ({$cats_to_delete_str})";
			$gekko_db->query($sql2);
		}
	}


}
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
abstract class basicApplicationMultipleCategories extends  basicApplicationNestedCategories implements interfaceApplicationNestedCategories 
{
	protected $data_categories_items;
	protected $table_categories_items;// $table_items_multiple_categories;
	
    public function __construct($app_name, $app_description, $table_items, $field_id, $data_items, $table_categories, $field_category_id, $data_categories, $table_categories_items, $data_categories_items)	
	{
		parent::__construct($app_name, $app_description, $table_items, $field_id, $data_items, $table_categories, $field_category_id, $data_categories);
		$this->table_categories_items = $table_categories_items;
		$this->data_categories_items = $data_categories_items;
		$this->mode = 'multiplecategories';
	}
	//_________________________________________________________________________//    
	public function getItemFieldNamesForAjaxListing()
	{
		$default_field_name_for_listing = createDataArray('id', 'status', 'title', 'virtual_filename', 'date_available', 'date_created', 'date_modified', 'sort_order');	
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
//_______________________________________________________________________________________________________________//	
	public function getTotalItemCountByCategoryID($category_id, $criteria='',$cache=false)
	{
		global $gekko_db;
				
		$cid = intval($category_id);
		$criteria_txt = '';
		if (!empty($criteria)) $criteria_txt = " AND {$criteria}";
		
 		$sql = "SELECT count({$this->table_items}.{$this->field_id}) as total_item_count FROM {$this->table_items} INNER JOIN {$this->table_categories_items} ON {$this->table_items}.{$this->field_id}= {$this->table_categories_items}.{$this->field_id} WHERE {$this->table_categories_items}.{$this->field_category_id} = {$cid} {$criteria_txt}";
		$total = $gekko_db->get_query_singleresult($sql,$cache);
		return $total['total_item_count'];
	}

//_______________________________________________________________________________________________________________//	
	public function getItemsToCategoryFieldNames()
	{
		return $this->data_categories_items;
	}
//_______________________________________________________________________________________________________________//	
	public function getItemsToCategoryTableName()
	{
		return $this->table_categories_items;
	}
//_______________________________________________________________________________________________________________//	
	public function getFullPathByItemID($item_id)
	{
		global $gekko_db;
		
		if (empty($this->table_categories_items)) return parent::getFullPathByItemID($item_id);
		$sql = "SELECT virtual_filename,{$this->table_categories_items}.{$this->field_category_id} FROM {$this->table_items} 
		INNER JOIN {$this->table_categories_items} ON {$this->table_categories_items}.{$this->field_id} 
		WHERE {$this->table_items}.{$this->field_id} = '{$item_id}'";
 		$item = $gekko_db->get_query_singleresult($sql,true);
		if (!$item) return false;
		
		$current_id = $item['category_id'];
		$last_id = -1;
		$path = '';
		while ($last_id != 0)
		{
			$sql = "SELECT parent_id, virtual_filename FROM {$this->table_categories} where {$this->field_category_id} = '{$current_id}'";
 			$id_r = $gekko_db->get_query_result($sql,true);
			$last_id = $id_r [0]['parent_id'];
			$dirname = $id_r [0]['virtual_filename'];
			$path=$dirname.'/'.$path;			
			$current_id = $last_id;
		}
		return '/'.$path.$item['virtual_filename'].'.html';
	}	
//_______________________________________________________________________________________________________________//
	public function getItemsByCategoryID($id,$fields='*',$extra_criteria='',$start=0,$end=0,$sortby='', $sortdirection='ASC',$from_cache=false)
	{
		global $gekko_db;
		if ($field=='*') $fieldnames = array_keys($this->getItemFieldNames()); else  $fieldnames = explode(',',$fields);
		$total_fieldname_count = count($fieldnames);
		for ($i=0;$i < $total_fieldname_count; $i++) $fieldnames[$i]= $this->table_items.'.'.$fieldnames[$i];
		$fields_tobe_selected = implode(',',$fieldnames);
		
		if (!empty($extra_criteria)) $criteria_txt = " AND {$extra_criteria}"; else $criteria_txt = '';
		
//		if ($id ==0) $the_criteria = "WHERE {$this->field_category_id} = 0 {$criteria_txt}"; else // bug
		$the_criteria = "INNER JOIN {$this->table_categories_items} ON {$this->table_categories_items}.{$this->field_id} = {$this->table_items}.{$this->field_id} WHERE {$this->field_category_id} = '".intval($id)."' {$criteria_txt}";		
		
		$sql = selectSQL($this->table_items,$fields_tobe_selected,$the_criteria,$start,$end,$sortby, $sortdirection, false,SQL_ENFORCE_ROW_LIMIT);	
 		$items = $gekko_db->get_query_result($sql, $from_cache);
		
		return $items;		
	}
	
//_______________________________________________________________________________________________________________//
	public function setItemCategory($id,$cid,$state)
	{
		global $gekko_db;
		
		$id = intval ($id);
		$cid = intval ($cid);
		if ($id > 0 and $cid >= 0)
		{
			if ($state)
			{
				if ($this->getCategoryByID($cid) && $this->getItemByID($id))
					$sql = "INSERT IGNORE INTO {$this->table_categories_items} ({$this->field_id},{$this->field_category_id}) VALUES ($id, $cid)";
				else
					return false;
			}	
			else
				$sql = "DELETE FROM {$this->table_categories_items} WHERE {$this->field_id} = {$id} AND {$this->field_category_id} = {$cid}";
			$gekko_db->query($sql);
			return true;
		}	
		return false;
	}
		
//_______________________________________________________________________________________________________________//
	public function getItemCategoryIDsByItemID($id,$sortby='', $sortdirection='ASC',$from_cache=false)
	{
		global $gekko_db;
		
		//$sql = "SELECT {$this->table_items}.{$this->field_id}, {$this->table_categories_items}.{$this->field_category_id} FROM {$this->table_items} INNER JOIN {$this->table_categories_items} ON {$this->table_categories_items}.{$this->field_id} = {$this->table_items}.{$this->field_id} WHERE {$this->field_category_id} = '".intval($id)."'";
		 $sql = "SELECT {$this->field_category_id} FROM {$this->table_categories_items} WHERE {$this->field_id} = '".intval($id)."'";
 		if(!empty($sortby) && array_key_exists($sortby,$this->data_items)) $sql.= " ORDER BY {$sortby} {$sortdirection}";		
 		$category = $gekko_db->get_query_result($sql,$from_cache);
		
		return $category;
	}
	//_______________________________________________________________________________________________________________//
	
	protected function getNonAmbiguousFieldNames($tablename)
	{
		switch ($tablename)
		{
			case $this->table_items: $datavalues = $this->data_items;break;
		    case $this->table_categories: $datavalues = $this->data_categories;break;
		    case $this->table_categories_items: $datavalues = $this->data_categories_items;break;
			default: return false;
		}
		$array_fields = array();
		foreach(array_keys($datavalues) as $field)
			$array_fields[] = $tablename.'.'.$field;
		$selected_fields = implode(', ', $array_fields);	
		return $selected_fields;	
	}
	
//_________________________________________________________________________//
	public function getAllChildItemsInMultipleCategories($multiple_category_ids)
	{
		global $gekko_db;
		
		$refined_result = '';
		$str_sql = implode(",", $multiple_category_ids);
		if ($str_sql)
		{
			$sql = "SELECT {$this->field_id} FROM {$this->table_categories_items} WHERE {$this->field_category_id} in ({$str_sql})";
 			$result_array_items_in_these_categories = $gekko_db->get_query_result($sql);
			foreach ($result_array_items_in_these_categories as $individual_item)
			$refined_result[] = $individual_item[$this->field_id];
		}
		return  $refined_result;
	}
	
	
	//_______________________________________________________________________________________________________________//
	public function getItemCategoriesByItemID($id,$sortby='', $sortdirection='ASC',$from_cache=false)
	{
		global $gekko_db;
		
		$fields = $this->getNonAmbiguousFieldNames($this->table_categories);
		 $sql = "SELECT {$fields} FROM {$this->table_categories} INNER JOIN {$this->table_categories_items} ON {$this->table_categories}.{$this->field_category_id} = {$this->table_categories_items}.{$this->field_category_id}  WHERE {$this->table_categories_items}.{$this->field_id} = '".intval($id)."'";
 		if(!empty($sortby) && array_key_exists($sortby,$this->data_items)) $sql.= " ORDER BY {$sortby} {$sortdirection}";		
 		$category = $gekko_db->get_query_result($sql,$from_cache);
		
		return $category;
	}
	
}


 ?>
