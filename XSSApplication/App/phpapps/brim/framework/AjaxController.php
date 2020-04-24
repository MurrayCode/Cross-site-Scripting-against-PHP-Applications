<?php

/**
 * This is a dedicated class that takes care of handling the asynchronous
 * calls.
 *
 * This file is part of the Brim project. The brim-project is located at the
 * following location: {@link http://www.brim-project.org/ http://www.brim-
 * project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - February 2006
 * @package org.brim-project.framework
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class AjaxController
{
	/**
 	 * Default constructor
	 */
	function AjaxController ()
	{
		// nothing (for the moment?)
	}

	/**
	 * Activate plugins.
	 *
	 * @param array $args the object array containing the arguments needed to
	 * activate a plugin. The args must at least have the plugin name set.
	 * @return string a csv string containing <b>all</b> the current plugins
	 * and their activation status
	 */
	function activatePlugin ($args)
	{
		return $this->modifyPluginActivation ($args, 'true');
	}

	/**
	 * Deactivate plugins.
	 *
	 * @param array $args the object array containing the arguments needed to
	 * deactivate a plugin. The args must at least have the plugin name set.
	 * @return string a csv string containing <b>all</b> the current plugins
	 * and their activation status
	 */
	function deactivatePlugin ($args)
	{
		return $this->modifyPluginActivation ($args, 'false');
	}

	/**
	 * Modifies a plugin activation status
	 *
	 * @access private
	 * @param array $args the object array containing the arguments needed to
	 * activate a plugin. The args must at least have the plugin name set.
	 * @return string a csv string containing the current plugins and their
	 * activation status
	 */
	function modifyPluginActivation ($args, $isActivated)
	{
		require_once 'framework/model/PluginServices.php';
		$services = new PluginServices ();
		$services->setPluginSetting ($_SESSION['brimUsername'],
			$args['pluginName'], $isActivated);
		$pluginSettings =
			$services->getPluginSettingsAsArray ($_SESSION['brimUsername']);
		$result = array ();
       	foreach($pluginSettings as $key => $value)
		{
           $result [] = $key."=".$value;
       	}
		return (implode (',', $result));
	}
}
?>
