<?php

require_once ('framework/model/PluginSetting.php');
require_once ('framework/model/PluginSettingFactory.php');
require_once ('framework/model/Services.php');

/**
 * Operations on plugin settings
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2004
 * @package org.brim-project.framework
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class PluginServices extends Services
{
	/**
 	 * Default constructor
	 */
	function PluginServices ()
	{
		parent::Services();
		$this->itemFactory = new PluginSettingFactory ();
		$queries = array ();
		include ('framework/sql/pluginQueries.php');
		$this->queries = $queries;
	}

	/**
	 * Adds a plugin setting for a user
	 *
	 * @param integer userId the identifier for the user
	 * @param object item the item to be added
	 */
	function addItem ($userId, $item)
	{
		$now = date ("Y-m-d H:i:s");
		$query  = sprintf ($this->queries['addItem'],
			$userId,
			$item->parent_id,
			addslashes ($item->name),
			addslashes ($item->description),
			$now,
			$item->value);
		$result = $this->db->Execute($query)
			or die("AddPluginSetting: " . $this->db->ErrorMsg() . " " . $query);
	}

	/**
	 * Modifies a plugin setting.
	 *
 	 * @param integer userId the identifier for the user who
	 * modifies a setting
	 * @param object item the modified setting
	 */
	function modifyItem ($userId, $item)
	{
		$now = date ("Y-m-d H:i:s");
		$query  = sprintf ($this->queries['modifyItem'],
			$now,
			addslashes ($item->name),
			addslashes ($item->description),
			$item->parentId,
			$item->isDeleted,
			$item->value,
			$item->itemId) ;
		$result = $this->db->Execute($query)
			or die("ModifyPluginSetting: " . $this->db->ErrorMsg() . " " . $query);
	}

	/**
	 * Returns all plugins of a specific user in an array
	 *
	 * @param integer userId the identifier of the user for which we
	 * would like to have the preferences
	 * @return array an array of all the preferences of this user
	 */
	function getPluginSettingsAsArray ($userId)
	{
		$result = array ();
		$settings = parent::getItems ($userId);
		if (isset ($settings))
		{
			foreach ($settings as $setting)
			{
				$result [$setting->name]=$setting->value.'';
			}
		}
		//$result['owner']=$userId;
		return $result;
	}

	/**
	 * Returns the id of a specific setting (lookup on the name of the
	 * setting, not the object!) for a specific user
	 *
	 * @param integer userId the identifier of the user
	 * @param string name the name of the setting for which we would
	 * like to know its identifier
	 * @return integer the identifier of the setting for the specific
	 * user
	 */
	function getPluginSettingId ($userId, $name)
	{
		$query = sprintf ($this->queries ['getPluginSettingId'],
			$userId, $name);
		$result = $this->db->Execute($query)
			or die("GetPluginSettingId: " . $this->db->ErrorMsg() . " " . $query);
		return $result->fields[0];
	}

	/**
	 * Gets the pluginsetting-value for a specific user
	 *
	 * @param integer userId the identifier of the user
	 * @param string name the name of the setting for which we would
	 * like to  know its value
	 * @return string the value of the setting for the specific user
	 */
	function getPluginSettingValue ($userId, $name)
	{
		$query = sprintf ($this->queries ['getPluginSetting'],
			$userId, $name);
		$result = $this->db->Execute($query)
			or die("GetPluginSetting: " . $this->db->ErrorMsg() . " " . $query);
		return $result->fields[0];
	}

	/**
	 * Sets a pluginsetting (name/value pair) for a specific user
	 *
	 * @param integer userId the identifier of the user
	 * @param string name the name of the setting to set
	 * @param string value the value of the setting to set
	 */
	function setPluginSetting ($userId, $name, $value)
	{
		$setting = $this->getPluginSettingValue ($userId, $name);
		if ($setting == null)
		{
			$setting = $this->itemFactory->getPluginSetting
				($userId, $name, $value);
			$this->addItem ($userId, $setting);
		}
		else
		{
			$settingId = $this->getPluginSettingId ($userId, $name);
			$setting = $this->getItem ($userId, $settingId);
			$setting->value = $value;
			$this->modifyItem ($userId, $setting);
		}
	}

	/**
	 * Returns all known plugins as an array by reading them from the
	 * config file
	 *
	 * @return array the plugins
	 * @todo fetch from database
	 */
	function getPlugins ()
	{
		$plugins = array ();
		include 'framework/configuration/plugins.php';
		$result = $plugins;
		return $result;
	}

	/**
	 * Deletes all plugin settings for the specified user
	 *
	 * @param string $userId
	 */
	function deleteAllPluginSettings ($userId)
	{
		$query = sprintf ($this->queries ['deleteAllPluginSettings'],
			$userId);
		$result = $this->db->Execute($query)
			or die("DeleteAllPluginSettings: " . $this->db->ErrorMsg() . " " . $query);
	}
}
?>