<?php

require_once ('framework/Controller.php');
require_once ('framework/model/PluginServices.php');

/**
 * The Dashboard controller: shows info per activated plugin
 * on the index page
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
class DashboardController extends Controller
{
	/**
	 * Theplugin services, used to ask for the activated
	 * plugins for the current user
	 *
	 * @var object the services that provide plugin information
	 */
	var $pluginServices;

	/**
	 * Default constructor
	 */
	function DashboardController ()
	{
		parent::Controller ();
		$this->title = 'Brim';
		$this->itemName = 'Dashboard';
		$this->pluginServices = new PluginServices ();
	}

	/**
	 * Activates the controller
	 */
	function activate ()
	{
		$languages = array ();
		$dictionary = $this->getDictionary ();
		$this->renderer = 'index';
		$this->renderObjects = sprintf ($dictionary['welcome_page'],
			$this->getUserName());
		if ($_SESSION['brimUserIsAdmin'] == 'true' && $this->installerExists ())
		{
			$this->renderEngine->assign	('about',
				$dictionary['installer_exists'].$dictionary['about_page']);
		}
		else
		{
			$this->renderEngine->assign	('about', $dictionary['about_page']);
		}
		$this->renderEngine->assign ('dashboard', $this->getDashboard());
		if (!isset ($_SESSION['brimShowTips'])
			|| (isset ($_SESSION['brimShowTips'])
				&& ($_SESSION['brimShowTips']=='1')))
		{
			$this->renderEngine->assign ('tip',	$this->getTip());
		}
		include ('framework/configuration/languages.php');
		$this->renderEngine->assign('languages', $languages);
	}

	/**
	 * Loop over all activated plugins and retrieve dashboard
	 * (overview) information for the specific plugin
	 *
	 * @return array an array with dashboard information per plugin
	 */
	function getDashboard ()
	{
		$plugins = array ();
		//
		// This function heavily uses the
		// framework/configuration/plugins.php file
		//
		include ('framework/configuration/plugins.php');
		$result = array ();
		$configuredPlugins = $this->pluginServices->getPluginSettingsAsArray
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
				if (isset ($configuredPlugins[$name]) &&
					$configuredPlugins[$name] == 'true')
				{
					if (isset ($plugin['dashboardContent'])
						&& isset ($plugin['dashboardSort'])
						&& isset ($plugin['dashboardAction'])
					)
					{
						include('plugins/'.$name.'/'.$plugin['controller']);
						//
						// reinitialize the contents
						//
						$contents = array ();
						$controller = new $plugin['controllerName'];
						//
						// Retrieve the dashboard information for this
						// plugin
						//
						$contents = $controller->getDashboard
							($plugin['dashboardContent'], 5,
								$plugin['dashboardSort']);
						//
						// If this plugin has dashboard fuctionality (the
						// action is set), add the plugin's parameters to
						// the result
						//
						if (isset ($plugin['dashboardAction'])
							&& isset ($plugin['dashboardSort'])
						)
						{
							$result[$name]= array (
								'name'=>$name,
								'content'=>(isset($plugin['dashboardContent']))
									? $plugin['dashboardContent']: NULL,
								'contents'=>$contents,
								'action'=>$plugin['dashboardAction'],
								'title'=>$plugin['dashboardTitle'],
								'controller'=>'index.php?plugin='.$plugin['name']
							);
							if (isset ($plugin['dashboardAdditionalLinkParameters']))
							{
								$result[$name]['dashboardAdditionalLinkParameters']=
									$plugin['dashboardAdditionalLinkParameters'];
							}
						}
					}
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
		if (file_exists ('framework/i18n/dictionary_'.
			$_SESSION['brimLanguage'] . '.php'))
		{
			include ('framework/i18n/dictionary_'.
				$_SESSION['brimLanguage'].'.php');
		}
		//
		// Overrule the title for this page
		//
		$dictionary['title']='Brim';
		$dictionary['item_title']='Brim';
		return $dictionary;
	}

	/**
	 * Reads the tip directory and selects one tip
	 *
	 * @return string a random tip
	 */
	function getTip ()
	{
		include ('framework/i18n/tips_en.php');
		if (file_exists ('framework/i18n/tips_'.
			$_SESSION['brimLanguage'] . '.php'))
		{
			include ('framework/i18n/tips_'.
				$_SESSION['brimLanguage'].'.php');
		}
		$random = rand (1, count($dictionary));
		if ($random < 10)
		{
			$random = '0'.$random;
		}
		return $dictionary['tip'.$random];
	}

	/**
	 * Checks if the installation file still exists. This is used
	 * to display a message to the admin user in case it is still there
	 *
	 * @return boolean <code>true</code>, if the installation
	 * file still exists, <code>false</code> otherwise
	 */
	function installerExists ()
	{
		return file_exists ('install.php');
	}
}
?>
