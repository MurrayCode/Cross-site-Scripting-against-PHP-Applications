<?php

require_once ('framework/ItemController.php');
require_once ('framework/model/PluginSetting.php');
require_once ('framework/model/PluginSettingFactory.php');
require_once ('framework/model/PluginServices.php');

require_class ('RightsManagerImpl',
	'framework/RightsManagerImpl.php'); // added by Michael
require_class ('RequestCast',
	'framework/util/request/RequestCast.class.php'); // added by Michael

/**
 * The Plugin Controller, this class allows a user to enable/disable
 * plugins
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2004
 * @package org.brim-project.framework
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class PluginController extends ItemController
{
	/**
	 * Constructor.
	 * Makes sure that the appropriate operations are instantiated.
	 */
	function PluginController ()
	{
		parent::ItemController ();
		$this->operations = new PluginServices ();
		$this->itemFactory = new PluginSettingFactory ();

		$this->rightsManager = new RightsManagerImpl();

		$this->pluginName = 'plugin';
		$this->title = 'Brim - Plugin Settings';
		$this->itemName = 'PluginSetting';
	}

	/**
	 * Activate. Basically this means that the appropriate actions
	 * are executed and an optional result is returned
	 * to be processed/displayed
	 */
	function activate ()
	{
		$languages = array ();
		$templates = array ();
		$menuItems = array ();
		include ('framework/configuration/languages.php');
		include ('framework/configuration/templates.php');
		$this->renderEngine->assign ('languages', $languages);
		$this->renderEngine->assign ('templates', $templates);

		switch ($this->getAction ())
		{
			case 'modifyPluginSetting':
				$setting = $this->itemFactory->requestToItem ();
				$currentValue = $this->operations->getPluginSettingValue
					($this->getUserName(), $setting->name);
				if (!isset ($currentValue))
				{
					$this->operations->addItem
						($this->getUserName (), $setting);
				}
				else
				{
					$setting->itemId = $this->operations->getPluginSettingId
						($setting->owner, $setting->name);
					$this->operations->modifyItem
						($this->getUserName(), $setting);
				}
				include ('framework/configuration/menuItems.php');
				$this->renderEngine->assign ('menuItems', $menuItems);
			default:
				$plugins = $this->operations->getPlugins ();
			 	$this->renderEngine->assign
					('plugins', $plugins);
				$this->renderObjects =
					$this->operations->getPluginSettingsAsArray
						($this->getUserName());
				$this->renderer = 'modifyPluginSettings';
				break;
		}
	}

	/**
	 * Returns the filename that will be given to the template for
	 * display
	 *
	 * @return string the filename of the template file to show.
	 */
	function getTemplateFile ()
	{
		//
		// Allow the template to override a specific file.
		// If this file exist (the file in the template directory),
		// it will be loaded, otherwise the default
		// (in the plugin directory) will be loaded
		//
		$renderer = 'templates/'.$this->getTemplate ().'/'.
			$this->getRenderer ();
		if (!(file_exists ($renderer)))
		{
			$renderer = 'framework/view/'.$this->getRenderer ();
		}
		return $renderer;
	}

	/**
	 * Retrieves the dictionary file by first tryin the language
	 * specific dictionary file, defaulting to the english version
	 * if the language specific file for
	 * the item does not exist and returns the contents as an array
	 *
	 * @return array the dictionary
	 */
	function getDictionary ()
	{
		include ('framework/i18n/dictionary_en.php');
		if (file_exists ('framework/i18n/dictionary_'.$_SESSION['brimLanguage'].'.php'))
		{
			include ('framework/i18n/dictionary_'.$_SESSION['brimLanguage'].'.php');
		}
		$dictionary['item_title']=$dictionary['plugins'];
		return $dictionary;
	}
}
?>
