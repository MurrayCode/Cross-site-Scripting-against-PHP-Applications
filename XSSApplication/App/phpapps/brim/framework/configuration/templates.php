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
function getTemplates ()
{
	$excludes = array ();
	$excludes [] = '.';
	$excludes [] = '..';
	$excludes [] = 'CVS';
	$excludes [] = '.svn';
	$excludes [] = 'pda';
	$excludes [] = 'sidebar';
	//
	// TODO text-only should be completely removed
	//
	$excludes [] = 'text-only';
	$excludes [] = 'index.php';

	$templates=array ();
	$directory = opendir ('templates');
	while (($file = readdir ($directory)) !== false)
	{
		if (!in_array ($file, $excludes))
		{
			$templates[]=$file;
		}
	}
	closedir ($directory);
	return $templates;
}
$templates = getTemplates ();
?>
