<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

class filters extends basicApplicationSimpleCategories
{
    public function __construct()
    {
 		$data_items = createDataArray ('id','title','description','sort_order','status','date_modified','display_in_apps','display_in_blocks','enabled_apps','enabled_blocks');
		parent::__construct('filters','Filters', 'gk_filter_items', 'id', $data_items, '', '', null);
    }
	//_______________________________________________________________________________________________________________//    
 	public function modifyText($text, $caller, $caller_function, $extra_info=false)
	{
		global $gekko_db;
		$catname = sanitizeString($category_name);
		
 		if ($extra_info['app'] != false)
		{
			$str = serialize ($extra_info['app']);
			//$str = $caller;
			$additional_criteria = " OR (display_in_apps = 2 AND enabled_apps LIKE '%{$str}%')";
		} else if ($extra_info['block'] != false)
		{
			$str = serialize ($extra_info['block']);
			//$str = $caller;
			$additional_criteria = " OR (display_in_blocks = 2 AND enabled_blocks LIKE '%{$str}%')";
		}
		$criteria = " `status`= 1 AND (display_in_apps = 1 {$additional_criteria}) ";
	//	echo $caller;
	//	echo $criteria; 
		$filters = $this->getAllItems('*',$criteria,0,0,'sort_order','ASC',true);
		if ($filters)
		{
			$filter_config = new DynamicConfiguration('gk_filter_config');
			foreach ($filters as $filter)
			{
 					include_filter_class ($filter['title']); // there should be some error checking
					$instance_config = $filter_config->get($filter['title']);
					$bot_name = $filter['title'];
					$bot_name.='Filter';			
					$the_bot = new $bot_name($instance_config);
 					$text = $the_bot->Run($text,$caller, $caller_function,$extra_info);
			}
		}
		return $text;
	}
	//_______________________________________________________________________________________________________________//	
 	public function displayMainPage()
	{
		return false;
	}
	
	//_______________________________________________________________________________________________________________//	
	public function saveItem($id)
	{
		include_inc('dbconfig.inc.php');
		$data_for_filter = $_POST;
		$x = $this->getItemByID(intval($id));
		$data_filter_name = $x['title'];
 		$filter_config = new DynamicConfiguration('gk_filter_config');

		foreach (array_keys($this->data_items) as $key) unset ($data_for_filter[$key]);		
		
		foreach (array_keys($data_for_filter) as $key) $filter_config->set($data_filter_name,$key,$data_for_filter[$key]);

		$savestatus = basicApplicationLinearData::saveItem($id);
		
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

}
	
?>