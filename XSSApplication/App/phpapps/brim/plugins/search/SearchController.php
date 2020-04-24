<?php

require_once ('framework/Controller.php');
include ('framework/configuration/plugins.php');
require_once ('framework/util/StringUtils.php');
require_once ('framework/model/PluginServices.php');

/**
 * The Search Controller
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - August 2006
 * @package org.brim-project.plugins.search
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class SearchController extends Controller
{
	var $stringUtils;

	/**
	 * Constructor.
	 * Makes sure that the appropriate operations are instantiated.
	 */
	function SearchController ()
	{
		parent::Controller ();

		$this->pluginName = 'search';
		$this->title = 'Brim - Search';
		$this->stringUtils = new StringUtils ();
	}

	/**
	 * Activate. Basically this means that the appropriate actions
	 * are executed and an optional result is returned
	 * to be processed/displayed
	 */
	function activate ()
	{
		$plugins = getPlugins ();
		$services = new PluginServices ();
		$pluginSettings = $services->getPluginSettingsAsArray  ($_SESSION['brimUsername']);
		$this->renderEngine->assign ('plugins', $plugins);
		switch ($this->getAction ())
		{
			case "search":
				//
				// The result items
				//
				$result = array ();
				$keys = array_keys ($_REQUEST);
				foreach ($keys as $key)
				{
					//
					// fetch all parameters on post starting with 'search_'
					// (the selected plugins on which we would like to search)
					//
					if ($this->stringUtils->startsWith ($key, 'search_'))
					{
						$name = substr ($key, strlen ('search_'));
						//
						// check if the plugin is enabled
						//
						if (isset ($pluginSettings[$name]) && $pluginSettings[$name] == 'true')
						{
							//
							// check if the plugin defines a services location
							//
							if (isset ($plugins[$name]['serviceLocation']))
							{
								//
								// Dynamically searching only makes sense if the
								// searchFields are set
								//
								if (isset ($plugins[$name]['searchFields']))
								{
									//
									// instantiate the plugin services
									//
									require_once ($plugins[$name]['serviceLocation']);
									$service = new $plugins[$name]['serviceName'] ();
									$fields = $plugins[$name]['searchFields'];
									//
									// Search on each field
									//
									foreach ($fields as $field)
									{
										//
										// We are searching on mulitple fields,
										// the results needs to be merged
										//
										if (isset ($result[$name]))
										{
											$oldResult = $result[$name];
										}
										else
										{
											$oldResult = array ();
										}
										$newResult =
											$service->searchItems
												($this->getUsername (),
													$field,trim($_REQUEST['value']));
										//
										// But remove duplicate values
										//
										$result[$name] =
											array_unique_itemId (array_merge ($oldResult, $newResult));
									}
								}
							}
						}
					}
				}
				$this->renderEngine->assign ('searchResult', $result);
				$this->renderer = 'searchResult';
				break;
			default:
				$this->renderer = 'search';
				break;
		}
	}

}
?>