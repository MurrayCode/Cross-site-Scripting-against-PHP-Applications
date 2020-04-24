<?php

/**
 * This class provides BASIC logging functionalities
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - March 2003
 * @package org.brim-project.framework
 * @subpackage util
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class Logger
{
	/**
	 * The file (name) to which we log
	 * @var string
	 */
	var $file;

	/**
	 * The format for the timestamp
	 * @var string
	 */
	var $timeFormat = "%H:%M:%S";

	/**
 	 * Constructor with a filename
 	 * @param string aFilename the filename to which we want to log
	 */
	function Logger ($aFileName)
	{
		$this->file = $aFileName;
	}

	/**
 	 * Log the message (timestamp will be prepended)
	 *
	 * @param string message the message to log
 	 */
	function log ($message)
	{
		if (is_object ($message))
		{
			if (method_exists ($message, 'toString'))
			{
				$message = $message->toString ();
			}
			else 
			{
				$message = var_export ($message, true);	
			}
		}
		$fd = fopen ($this->file, "a");
		if ((!$fd))
		{
			die ("Could not open: " + $this->file);
		}
		if (empty($fd))
		{
			die ("Empty");
		}
		if (is_array ($message) || is_object ($message))
		{
			$msg = var_export ($message, true).'\n';
		}
		else
		{
			$msg = $message . "\n";
		}

		$timeStamp = strftime($this->timeFormat, time());
		fwrite ($fd, $timeStamp . " " . $msg);
		fclose ($fd);
	}
}
?>