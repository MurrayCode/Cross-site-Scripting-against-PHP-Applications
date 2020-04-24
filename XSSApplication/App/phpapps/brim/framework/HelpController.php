<?php

require_once ('framework/Controller.php');
require_once ('framework/model/PluginServices.php');

/**
 * This controller regulates the request for help, nothing more ;-)
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class HelpController extends Controller
{
	/**
	 * The plugin services, used to ask for the activated
	 * plugins for the current user
	 *
	 * @var object the services that provide plugin information
	 */
	var $pluginServices;

	/**
	 * Default constructor
	 */
	function HelpController ()
	{
		parent::Controller ();
		$this->title = 'Brim';
		$this->pluginName = 'help';
		$this->itemName = 'Help';

		$this->pluginServices = new PluginServices ();
	}

	/**
	 * Activates the controller
	 */
	function activate ()
	{
		$languages = array ();
		$dictionary = $this->getDictionary ();
		$this->renderer = 'help';
		$this->renderObjects = sprintf ($dictionary['welcome_page'],
			$this->getUserName());
		$this->renderEngine->assign ('applicationHelp',
			$this->getApplicationHelp());
		$this->renderEngine->assign ('helpItems',
			$this->getHelpItems());
		include ('framework/configuration/languages.php');
		$this->renderEngine->assign('languages', $languages);
	}

	/**
	 * Returns the help function for the application as a whole
	 *
	 * @return string the help message that explains how to use
	 * the application
	 */
	function getApplicationHelp ()
	{
		include ('framework/i18n/dictionary_en.php');
		$file = 'framework/i18n/dictionary_'.$_SESSION['brimLanguage'].'.php';
		if (file_exists ($file))
		{
			include ($file);
		}
		return $dictionary['item_help'] ;
	}

	/**
	 * Loop over all activated plugins and retrieve help
	 * information for the specific plugin
	 *
	 * @return array an array with help information per plugin
	 */
	function getHelpItems ()
	{
		$plugins = array ();
		$dictionary = $this->getDictionary();
		//
		// This function heavily uses the
		// framework/configuration/plugins.php file
		//
		include ('framework/configuration/plugins.php');
		$result = array ();
		$configuredPlugins =
			$this->pluginServices->getPluginSettingsAsArray
				($this->getUserName());

		//
		// Loop over all plusgins
		//
		foreach ($plugins as $plugin)
		{
			$name = $plugin['name'];
			//
			// Check if the current plugin is activated for this
			// user
			//
			// Weird....=='true'
			if (array_key_exists ($name,$configuredPlugins)
					&& $configuredPlugins[$name] == 'true')
			{
				$translated = 'plugins/'.$name.'/i18n/dictionary_'.
					$_SESSION['brimLanguage'].'.php';
				$english = 'plugins/'.$name.'/i18n/dictionary_en.php';
				include $english;
				if (file_exists ($translated))
				{
					include $translated;
				}
				//
				// Put the in the array, based on the name of the
				// item. This eases sorting afterwards
				//
				$result[$dictionary[$name]] = $dictionary['item_help'];
			}
		}
		return $result;
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
		// it will be loaded, otherwise the default (in the plugin
		// directory) will be loaded
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
	 * specific dictionary file, defaulting to the english version if
	 * the language specific file for the item does not exist and
	 * returns the contents as an array
	 *
	 * @return array the dictionary
	 */
	function getDictionary ()
	{
		include ('framework/i18n/dictionary_en.php');
		$file = 'framework/i18n/dictionary_'.$_SESSION['brimLanguage'].'.php';
		if (file_exists ($file))
		{
			include ($file);
		}
		//
		// Overrule the title for this page
		//
		$dictionary['title']='Brim - Help';
		$dictionary['item_title']='Brim Help';
		return $dictionary;
	}
}
?>
