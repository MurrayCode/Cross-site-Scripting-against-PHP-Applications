<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
class DynamicConfiguration
{
	protected $table_name;
	
    public function __construct($table_name)
    {
		
		$this->table_name =  $table_name;
	}
	//_________________________________________________________________________//
    public function exists($section, $key,$from_cache=false)
	{
		global $gekko_db;
		
		$sql = "SELECT * FROM {$this->table_name} WHERE section='{$section}' AND `key`='{$key}'";
		$results_array = $gekko_db->get_query_result($sql,$from_cache);
		return (count ($results_array) > 0);
	}
	
	//_________________________________________________________________________//
    public function get($section, $key='',$from_cache=false)
    {
		global $gekko_db;

		$sql = "SELECT * FROM {$this->table_name} WHERE section='{$section}' ";
		if (!empty($key)) $sql.= " AND `key`='{$key}'";
		$results_array = $gekko_db->get_query_result($sql,$from_cache);
		$config = array();
		foreach ($results_array as $result) 
		{
			$value = unserialize($result['value']);
			if (is_string($value)) $value = stripslashes ($value);
			$config[$result['key']] = $value;
		}
		if (!empty($key)) return $config[$key]; else return $config;
    }
	
	//_________________________________________________________________________//
    public function get_section_by_value($value, $key,$from_cache=false)
    {
		global $gekko_db;
		
		//$searchvalue = sanitizeString  ("%{$value}%");
		$searchvalue = sanitizeString(serialize ($value));
		$sql = "SELECT * FROM {$this->table_name} WHERE `value` = {$searchvalue} AND `key`='{$key}'";
		$results_array = $gekko_db->get_query_result($sql,$from_cache);
		$result_count = count ($results_array);
		
		if ($result_count == 0 ) return false;
		else if ($result_count == 1) return $results_array[0]['section'];
		else
		{
			foreach ($results_array as $result) 
			{
				$thevalue = unserialize($result['value']);
				if (is_string($thevalue)) $thevalue = stripslashes ($thevalue);
				
				$strvalue = $thevalue;//unserialize(stripslashes($result['value']));
				if ($strvalue == $value) return $result['section'];
			}
			return false;
		}
    }
	
	//_________________________________________________________________________//
    public function displayConfigAsTextBoxWithLabel($section, $key, $label, $linebreak=false)
	{
		$value = $this->get($section,$key);
		$s = INPUT_TEXT ($key, $key, 'gekko_config', $value);
		if ($label)
		{
			if ($linebreak) $label.=BR();			
			$s = LABEL($label,$s);

		}
		return $s;
	}
	//_________________________________________________________________________//
    public function displayConfigAsTextBox($section, $key, $label='',$extra_class='')
	{
		$value = $this->get($section,$key);
		$class = 'gekko_config';
		if ($extra_class) $class.= " {$extra_class}";
		$s = INPUT_TEXT ($key, $key, $class, $value);
		if ($label)
		{
			if ($linebreak) $label.=BR();			
			$s = LABEL($label,$s);

		}
		return $s;
	}
	//_________________________________________________________________________//
    public function displayConfigAsTextArea($section, $key, $label)
	{
		$value = $this->get($section,$key);
		return LABEL($label,INPUT_TEXTAREA ($key, $key, 'gekko_config', $value));
	}
	
	//_________________________________________________________________________//
    public function displayConfigAsSingleCheckbox($section, $key, $label)
	{
		$checked = $this->get($section,$key);
		return LABEL($label,INPUT_SINGLECHECKBOX ($key, '1', $checked),false);
	}
	//_________________________________________________________________________//
    public function displayConfigAsMultipleCheckbox($section, $key, $label, $choices_array)
	{
		$answer = $this->get($section,$key);
		$str = INPUT_MULTIPLECHECKBOX($key,$choices_array,$answer, $label);
		return $str;
	}	
	//_________________________________________________________________________//
    public function displayConfigAsRadiobox($section, $key, $label, $choices_array)
	{
		$answer = $this->get($section,$key);
		$str = INPUT_RADIOBOX($key,$choices_array,$answer, $label);
		return $str;
	}	
	//_________________________________________________________________________//
    public function displayConfigAsDropDownSelection($section, $key, $label, $choices_array)
	{
		$answer = $this->get($section,$key);
		$empty_choices_array  = array ( 0 => array ( 'value' => '', 'label' => 'Please select...', ));
		$choices_array = array_merge($empty_choices_array,$choices_array);
		$str = INPUT_DROPDOWN($key,$choices_array,$answer, $label);
		return $str;
	}	
	//_________________________________________________________________________//		
    public function preventFaultyAliasName($section, $key, $value)
	{
		global $gekko_db;
		
		$all_apps = getFolderContent('/apps','dir',array($section));
		foreach ($all_apps as $app) $existing_items_with_the_same_name[] = $app['filename'];
		$id = intval($id);
		$key = sanitizeString($key);
		$section = sanitizeString($section);
		$str_value = sanitizeString($value);
		$sql = "SELECT * FROM {$this->table_name} WHERE section <> {$section} AND `key`={$key} AND value = {$str_value}";
		//echo $sql;
 		$results = $gekko_db->get_query_result($sql);
		foreach ($results as $result) $existing_items_with_the_same_name[] = unserialize(stripslashes($result['value']));
		if ($existing_items_with_the_same_name)
		{
			foreach ($existing_items_with_the_same_name as $name)
			{
				foreach ($results as $result) $existing_items_with_the_same_name[] = unserialize(stripslashes($result['value']));
				$name = unserialize(stripslashes($value));			
				$suggested =  unserialize(stripslashes($value));
				$i = 1;
				while (in_array($suggested,$existing_items_with_the_same_name))
				{
					$suggested = $name.'-'.$i;
					$i++;
				}
			}return serialize(convert_into_sef_friendly_title($suggested));
		}
		else return $value;
	}
	
	//_________________________________________________________________________//	
    public function set($section, $key, $value)
    {
		global $gekko_db;
		
		$datavalues = array();
		if ($key == 'btn_save' || $key == 'btn_apply') return false; // 
		$value = serialize($value);
		if (get_magic_quotes_gpc()) $value = addslashes($value);
		if ($key=='alias')
		{
			$value = $this->preventFaultyAliasName($section,$key,$value);	
		}
		if (!$this->exists($section,$key))
		{
		
			// create new data							
			$datavalues['section'] = $section;
			$datavalues['key'] = $key;	
			$datavalues['value'] = $value;
			$sql_set_cmd = InsertSQL($datavalues);
			$sql =  "INSERT INTO `{$this->table_name}` ".$sql_set_cmd;
			$gekko_db->query($sql);
			return true;
		} else
		{
			// update existing data	
			$newValue = sanitizeString($value);
			$sql =  "UPDATE {$this->table_name} SET `value` = {$newValue} WHERE `section`='{$section}' AND `key`='{$key}'";
			$gekko_db->query($sql);	
			return true;		
		}
    }
	//_________________________________________________________________________//
	//_________________________________________________________________________//
}


?>