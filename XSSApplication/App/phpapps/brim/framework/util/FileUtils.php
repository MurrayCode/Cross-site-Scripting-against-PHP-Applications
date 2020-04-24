<?php

/**
 * File utilities.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - June 2006
 * @package org.brim-project.framework
 * @subpackage util
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class FileUtils
{
	/**
	 * Constructor.
	 */
	function FileUtils ()
	{
	}

	/**
	 * Get the extension of a file, based on its filename
	 * @param string filename the name of the file for which
	 * we like to know the extension
	 * @return string the extension of the file
	 */
	function getExtension ($fileName)
	{
		$ext = explode (".", $fileName);
		$forLast = (count($ext)-1);
		return $ext[$forLast];
	}
}
?>