<?php
$appname = 'search';

class search extends basicApplication
{
    public function __construct()
    {
		parent::__construct('search','Search');
    }
	
 	public function displayMainPage()
	{
		include('default.template.php');
	}

	public function InterpretFriendlyURL($url)
	{
		$commands = array();
//		print_r($_GET);
		if (strpos($url,'?')==true)
		{
			$comp = explode('?',$url);
			if ($comp[1])
			{
				$strquery = explode('&',$comp[1]);
				foreach ($strquery as $str)
				{
						$xcmd = explode('=',$str);
						$key = $xcmd[0];
						$val = $xcmd[1];
						$commands[$key] = $val;
				}
				
				if (array_key_exists('keyword',$commands))
				{
					$commands['action'] = 'search';
					$commands['keyword'] = preg_replace("/[^A-Za-z0-9\s\s+\.\:\-\/%+\(\)\*\&\$\#\!\@\"\';\n\t\r]/",'',$commands['keyword']);
					
					//// preg_replace("/[^A-Za-z0-9\s\s+]/",'',$commands['keyword']);
					return $commands;
				} else return parent::InterpretFriendlyURL($url);
			
			} else return parent::InterpretFriendlyURL($url);
		} else return parent::InterpretFriendlyURL($url);
	}
//_______________________________________________________________________________________________________________//	
	protected function refineFullTextSearchCriteria($obj,$keyword, $fields)
	{
 		if (empty($fields) || empty($keyword)) return false;
 		$cleankeyword = sanitizeString  ("%{$keyword}%");
		$search_criteria_array = array();
		$methods = get_class_methods($obj);
		if (!in_array('getItemFieldNames',$methods)) return false;
		
		$data_items = $obj->getItemFieldNames();
		if (strpos($fields,','))
		{
			$fields_tobe_selected = explode(',',$fields);
			$fields_count = count($fields_tobe_selected);
			$total_available_fields = 0;
			$i=0;
			foreach ($fields_tobe_selected as $field)  if (array_key_exists($field,$data_items)) $total_available_fields++;
			if ($total_available_fields == 0) return false;
			
			for ($i=0;$i < $fields_count;$i++)
			{
				 $field = $fields_tobe_selected[$i];
				 if (array_key_exists($field,$data_items))
				 {
					$search_criteria_array[]= "{$field} LIKE {$cleankeyword}"; 
					//if ($i!= $total_available_fields-1 && $total_available_fields != 1) $search_criteria.= ' OR ';
				 }
			}
			$search_criteria = implode (' OR ',$search_criteria_array);
		} else
		{
			$fields_tobe_selected = $fields;
			if($fields_tobe_selected!='')	if (!array_key_exists($fields_tobe_selected,$data_items)) return false;
			$search_criteria = "{$fields} LIKE {$cleankeyword}";		
		}
		return $search_criteria;	
	}
//_______________________________________________________________________________________________________________//	
	public function getItemTotalFullTextSearchResultCount($obj,$keyword, $fields='title,summary,description',$criteria='status > 0')
	{
		global $gekko_db;
		$criteria_txt = '';
		if (!empty($criteria)) $criteria_txt = " AND {$criteria}";
		$search_criteria = $this->refineFullTextSearchCriteria($obj,$keyword,$fields);
		if ($search_criteria)
			return $obj->getTotalItemCount($search_criteria.$criteria_txt);
		else
			return false;
	}
//_______________________________________________________________________________________________________________//	
	public function getItemFullTextSearchResult($obj,$keyword, $fields='title,summary,description',$start=0,$end=0,$sortby='', $sortdirection='ASC')
	{
		global $gekko_db;
		$criteria_txt = '';
		if (!empty($criteria)) $criteria_txt = " AND {$criteria}";
		$search_criteria = $this->refineFullTextSearchCriteria($obj,$keyword,$fields);
		
		if ($search_criteria)
			return $obj->getAllItems($fields_tobe_selected,$search_criteria,$start,$end,$sortby, $sortdirection,true,true);
		else 
			return false;		
	}
 	//_______________________________________________________________________________________________________________//	
	public function getObjectSearchResultsAndCount($keyword,$fieldnames='title,summary,description',$from_cache=true)
	{
		$forbidden_listing = array('.','..','search','users','.htaccess','__MACOSX');
		
		$app_path = SITE_PATH.'/apps/';
		$app_array =  array();
		$allcount = 0;
		$search_result_per_object = array();
		$dir_handle = @opendir($app_path);
		if ($dir_handle)		
		while ($filename = readdir($dir_handle)) 
		{
			$include_filename = "/apps/{$filename}/{$filename}.class.php";
			if (!in_array($filename,$forbidden_listing)  && file_exists(SITE_PATH.$include_filename))
			{
				$classname = $filename;
				include_app_class($filename);
				$obj = new $classname;
				$search_result_count = $this->getItemTotalFullTextSearchResultCount($obj,$keyword,$fieldnames,'status > 0');
				
				if ($search_result_count > 0)
				$search_result_per_object[] = array('classname' => $classname,'object'=>$obj, 'start'=>$allcount,'end'=>$allcount+$search_result_count, 'total'=>$search_result_count);
				$allcount+=$search_result_count;
			}
		}
		if ($dir_handle) closedir($dir_handle);	
		return $search_result_per_object;
	}
	//_______________________________________________________________________________________________________________//	
	private function getWhichObjectToLoad($object_results,$start)
	{
		if ($object_results)
		{
			$array_count = count($object_results);
			$position = 0;
			for($i=0;$i < $array_count;$i++)
			{
				$object_result = $object_results[$i];
				$position=$object_result['start'];
				//echo ' Position '.$position;
				if ($start >=$object_result['start'] && $start < $object_result['end'])
				{
				//	echo "returning ".$object_result['classname'].' for start = '.$start.br().br();
					return array('object' => $object_result['object'],'position' => $position);	
				}
			}
		}else return false;
	}
	//_______________________________________________________________________________________________________________//	
	
	public function searchByKeyWord($keyword,$pg=1,$sortby='', $sortdirection='ASC', $from_cache = false,$standard_criteria = 'status=1')
	{
		$global_search_result_count = 0;
		
		if (strlen($keyword) < 4)
		{
			echo P('Keyword must be four characters or more');
			$this->displayMainPage();
			return false;
		}
        if ($keyword) $this->page_title = "Searching for {$keyword}... - ".SITE_NAME; else $this->page_title = 'Search';

		$perpage = $this->getNumberOfListingsPerPage();
		$object_results = $this->getObjectSearchResultsAndCount($keyword,'title,summary,description',true);
		if ($object_results)
		{
			foreach($object_results as $object_result)
			{
				$obj = $object_result['object'];
				$total = $object_result['total'];
				$global_search_result_count+=$total;
			//	echo $object_result['classname'].' start = '.$object_result['start'].' end = '.$object_result['end'].' total = '.$object_result['total'].br();
			}
			$pagination = getStartAndEndForItemPagination($pg,$perpage,$global_search_result_count);
			
			$added_count = 0;
			$obj_count = 0;
			$items = array();
			while ($added_count < $perpage)
			{
			//	echo "Pagination [START] = {$pagination['start']} [END] = {$pagination['end']} [PERPAGE] = {$perpage} [ADD] = {$added_count}<br/><br/>";
				$objinfo = $this->getWhichObjectToLoad($object_results,$added_count + $pagination['start']);
				$obj = $objinfo['object'];
				if ($obj)
				{
					$position = $objinfo['position'];
					$item_start = $pagination['start'] + $added_count - $position;
					$item_end = $pagination['end'] - $position;
					$search_obj_result = $this->getItemFullTextSearchResult($obj,$keyword,'title,summary,description',$item_start,$item_end,'date_created','DESC');	
					$search_obj_result_count = count($search_obj_result);
					for ($i = 0; $i < $search_obj_result_count; $i++) $search_obj_result[$i]['object'] = $obj;	
					
					if (count($items) > 0)
					   $items = array_merge($items,$search_obj_result);
					else
					   $items = $search_obj_result;
				//	$result_count = count($items);
					//if ($result_count < $item_start + $perpage) $item_end = $item_start + $result_count;
					//echo '---> LOADING '.$obj->app_name.' start = '.$item_start.' end = '.$item_end.br();
				///	for ($i = 0; $i < $total_count; $i++) $items[$i]['object'] = $obj;	
					$added_count+=$search_obj_result_count;
					$obj_count++;
				} else break; // No More Items
				//echo 'Total added = '.$added_count.' '.$obj->app_name.br();
			}
			/*while ($added_count < $perpage)
			{
				$items = $this->getItemFullTextSearchResult($obj,$keyword,'title,summary,description',$pagination['start'],$pagination['end'],'date_created','DESC');	
				$result_count = count($items);
				for ($i = 0; $i < $total_count; $i++) $items[$i]['obj'] = $obj;	
				
				$added_count+=$result_count;
			}*/
		}
		include('searchresult.template.php');
		/*
		$app_path = SITE_PATH.'/apps/';
		$app_array =  array();
		$dir_handle = @opendir($app_path);
		$items = null;
		$global_search_result_count = 0;
		$search_result_per_object = array();
		$object_results = $this->getObjectSearchResultsAndCount(true);
		while ($filename = readdir($dir_handle)) 
		{
			$include_filename = "/apps/{$filename}/{$filename}.class.php";
			if (!in_array($filename,$forbidden_listing)  && file_exists(SITE_PATH.$include_filename))
			{
				$classname = $filename;
				include_app_class($filename);
				$obj = new $classname;
				$search_result_count = $this->getItemTotalFullTextSearchResultCount($obj,$keyword);
				$search_result_per_object[] = array('classname' => $classname,'total'=>$search_result_count);
				$global_search_result_count+=$search_result_count;
				echo $classname.' '.$search_result_count.br();
				if ($search_result_count > 0)
				{
					$pagination = getStartAndEndForItemPagination($pg,$this->getNumberOfListingsPerPage(),$global_search_result_count);
					
					$search_resultx = $this->getItemFullTextSearchResult($obj,$keyword, 'title,summary,description',$pagination['start'],$pagination['end'],'date_created','DESC');	
					if ($search_resultx)
					{
						$search_result = $search_resultx;
						$total_count = count ($search_result);
						for ($i = 0; $i < $total_count; $i++)
							$search_result[$i]['obj'] = $obj;	
						if ($items)
							$items = array_merge($search_result,$items);
						else
							$items = $search_result;
					} 
				}
			}
		}
		closedir($dir_handle);
		// Now display the search result
		include('result.template.php');*/
	}
	//_______________________________________________________________________________________________________________//	
	
	public function getNumberOfListingsPerPage()
	{
		return 15;	
	}
	//_______________________________________________________________________________________________________________//	
	public function Run($command)
	{
		switch ($command['action'])
		{
			case 'search' : $_GET['keyword'] = $command['keyword']; $this->searchByKeyWord(urldecode($command['keyword']), $command['pg']);break;
			case 'viewitem': $this->displayItemByID($command[$this->field_id]);break;
			case 'viewcategory': $this->displayItemsInCategoryByID($command[$this->field_category_id]);break;
			default: return parent::Run($command);	
		}
		return true;
	}

}
	
?>