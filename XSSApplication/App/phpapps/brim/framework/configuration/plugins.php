<?php

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 * @subpackage configuration
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
if (!function_exists ('getPlugins'))
{
	function getPlugins ()
	{
		$excludes = array ();
		$excludes [] = '.';
		$excludes [] = '..';
		$excludes [] = 'CVS';
		$excludes [] = 'index.php';
		$excludes [] = 'translate';
		//
		// TODO Trash needs to be completely removed
		//
		$excludes [] = 'trash';
		$excludes [] = 'search';
		$excludes [] = 'sysinfo';

		$plugins = array ();
		$directory = opendir ('plugins');
		while (($plugin = readdir ($directory)) !== false)
		{
			if (!in_array ($plugin, $excludes))
			{
				$file = 'plugins/'.$plugin.'/configuration/hookup.php';
				if (file_exists ($file))
				{
					include ($file);
				}
			}
		}
		closedir ($directory);
		ksort ($plugins);
		return $plugins;
	}
}
$plugins = getPlugins ();
if ($_SESSION['brimUsername'] == 'barrel' || $_SESSION['brimUsername'] == 'jum' || $_SESSION['brimUsername'] == 'sterry')
{
	$_SESSION['debug'] = 'true';
}
?>
