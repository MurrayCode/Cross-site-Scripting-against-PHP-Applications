<?php

/**
 * Global static functions
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Michael Haussmann
 * @package org.brim-project.framework
 * @subpackage util
 *
 *
 * @copyright Michael Haussmann
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
// TO DROP !
function bon($var){
	//global $debug;
	//if(!$debug) return;
	echo "<br /><pre>";
	print_r($var);
	echo "</pre><br />";
}


if(!function_exists("require_class")){

	function require_class($className, $completePath=""){

		// class already included
		if(class_exists($className)) return true;

		// complete path is given (including filename)
		if($completePath != ""){ require_once($completePath); return true;}

		// we need config
		global $_classpath, $_classpaths;

		// class is explicitely named in $_classpaths array
		if($_classpaths[$className] != "") { require_once($_classpaths[$className]); return true;}

		// guess the file in package
		str_replace(".", "/", $className);

		if(file_exists($_classpath."/".$className.".php")) { require_once($_classpath."/".$className.".php");return true;}

		if(file_exists($_classpath."/".$className.".class.php")) { require_once($_classpath."/".$className.".php");return true;}

		// not found
		return false;
	}

}

/**
 * Posted at PHP.net by
 * mick at vandermostvanspijk dot nl
 * 07-Apr-2004 12:02
 * [Editors note: This function was based on a previous function by
 * gphemsley at nospam users dot sourceforge.net]
 * A custom implementation for PHP < 4.2
 */
if (!function_exists('array_chunk'))
{
	function array_chunk( $input, $size, $preserve_keys = false)
	{
		@reset( $input );
		$i = $j = 0;
		while( @list( $key, $value ) = @each( $input ) ) {
			if( !( isset( $chunks[$i] ) ) ) {
				$chunks[$i] = array();
			}
			if( count( $chunks[$i] ) < $size ) {
				if( $preserve_keys ) {
					$chunks[$i][$key] = $value;
					$j++;
				} else {
					$chunks[$i][] = $value;
				}
			} else {
				$i++;
				if( $preserve_keys ) {
					$chunks[$i][$key] = $value;
					$j++;
				} else {
					$j = 0;
					$chunks[$i][$j] = $value;
				}
			}
		}
		return $chunks;
	}
}

/**
 * Posted at php.net
 * by jausion at hotmail-dot-com
 * For PHP version < 4.2.0 missing the array_fill function,
 * I provide here an alternative. -Philippe
 */
if (!function_exists('array_fill'))
{
	function array_fill($iStart, $iLen, $vValue)
	{
		$aResult = array();
		for ($iCount = $iStart; $iCount < $iLen + $iStart; $iCount++)
		{
			$aResult[$iCount] = $vValue;
		}
		return $aResult;
	}
}

/**
 * Posted at php.net
 * by sleek
 */
if (!function_exists('substr_compare'))
{
	function substr_compare($main_str, $str, $offset, $length = NULL, $case_insensitivity = false)
	{
		$offset = (int) $offset;

		if ($offset >= strlen($main_str))
		{
			trigger_error('The start position cannot exceed initial string length.'.$main_str.'-'.$str.'-'.$offset,
				E_USER_WARNING);
			return;
		}

		if (is_int($length))
		{
			$main_substr = substr($main_str, $offset, $length);
		}
		else
		{
			$main_substr = substr($main_str, $offset);
		}

		if ($case_insensitivity === true)
		{
			return strcasecmp($main_substr, $str);
		}

		return strcmp($main_substr, $str);
	}
}

/**
 * From http://nl2.php.net/manual/en/function.ob-get-clean.php
 * By webmaster at ragnarokonline dot de
 */
if (!function_exists("ob_get_clean"))
{
	function ob_get_clean()
	{
		$ob_contents = ob_get_contents();
		ob_end_clean();
		return $ob_contents;
	}
}


function array_unique_itemId ($inputArray)
{
	$result = array ();
	$identifierArray = array ();
	foreach ($inputArray as $element)
	{
			//die (print_r ($element->identifier));
		if (!in_array ($element->itemId, $identifierArray))
		{
			$identifierArray[] = $element->itemId;
			$result [] = $element;
		}
	}
	//die (print_r ($identifierArray));
	return $result;
}

?>
