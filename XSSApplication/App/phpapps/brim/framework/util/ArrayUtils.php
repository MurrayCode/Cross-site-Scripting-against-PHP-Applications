<?php

/**
 * Array utilities
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - June 2004
 * @package org.brim-project.framework
 * @subpackage util
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class ArrayUtils
{
	/**
	 * Empty constructor
	 */
	function ArrayUtils ()
	{
	}

	/**
	 * From the php implode function.
	 * http://www.php.net/implode
	 * @author static
	 */
	function implode_with_keys($glue, $array)
	{
		$output = array();
		foreach( $array as $key => $item )
		{
			$output[] = $key . "=" . $item;
		}
		return implode($glue, $output);
	}

	/**
	 * From the php implode function.
	 * http://www.php.net/implode
	 * @author james at globalmegacorp dot org
	 */
	function explode_with_keys($seperator, $string)
	{
		$output=array();
		$array=explode($seperator, $string);
		foreach ($array as $value)
		{
			$row=explode("=",$value);
			$output[$row[0]]=$row[1];
		}
		return $output;
	}
	
	function uniqueArray ($input)
	{
        	if (!isset ($input) || $input == null)
        	{
            		return null;
        	} 
		sort ($input);
		reset ($input);
		$result = array ();
		$i = 0;

		$current = current ($input);
		for ($j=0; $j<sizeof ($input); $j++)
		{
			if (next ($input) != $current)
			{
				$result[$i]=$current;
				$current = current ($input);
				$i++;
			}
		}
		return $result;
	}

}
?>
