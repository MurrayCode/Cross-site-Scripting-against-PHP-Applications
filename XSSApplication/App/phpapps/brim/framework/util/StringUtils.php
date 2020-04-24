<?php

/**
 * String utilities
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.framework
 * @subpackage util
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class StringUtils
{
	/**
	 * Empty constructor
	 */
	function StringUtils ()
	{
	}

	/**
	 * Replaces quotes by the tag &amp;quote;
	 *
	 * @param string string the input string
	 * @return string the url encoded string
	 */
	function urlEncodeQuotes ($string)
	{
		$s1 = ereg_replace ("\"", "&quot;", $string);
		$s2 = ereg_replace ("\'", "&#39;", $s1);
		return $s2;
	}

	/**
	 * Replaces ampersands by the tag &amp;amp;
	 *
	 * @param string string the input string
	 * @return string the url encoded string
	 */
	function urlEncodeAmpersands ($string)
	{
		$s1 = ereg_replace ("&", "&amp;", $string);
		return $s1;
	}

	/**
	 * Checks whether the input starts with a given string
	 *
	 * @param string inputString the total String
	 * @param string startsWith a partial String
	 * @return true if 'inputString' starts with 'startsWith', false
	 * otherwise or if 'startsWith' is <code>null</code>
	 */
	function startsWith ($inputString, $startsWith)
	{
		if ($startsWith == null)
		{
				return false;
		}
	  	return (substr ($inputString, 0, strlen($startsWith))
			== $startsWith);
	}

	/**
	 * Removes newlines characters from the input string
	 *
	 * @param string string the input string
	 * @return string the input string without the newlines
	 * @deprecated
	 */
	function stripNewlines ($string)
	{
		$s1 = ereg_replace ("\n", "", $string);
		$s2 = ereg_replace ("\r", "", $s1);
		return $s2;
	}


	/**
	 * Replaces newline characters by br tages
	 *
	 * @param string string the input string
	 * @return string the input string without the replaced newlines
	 */
	function newlinesToHtml ($string)
	{
		$s1 = ereg_replace ("\n", "<br />", $string);
		$s2 = ereg_replace ("\r", "", $s1);
		return $s2;
	}

	/**
	 * Returns the property of the inputString. This assumes that the
	 * inputString is in the format of property=value.
	 *
	 * The function will take the length of the property + 1
	 * (for the '=' sign) and returns the inputString without the
	 * first characters, found by the length
	 *
	 * Calling the function getProperty ("property=value", "property")
	 * will return "value"
	 *
	 * @return string the property as specified, <code>null</code>
	 * 		if either inputString or property is null
	 */
	function getProperty ($inputString, $property)
	{
		if ($inputString == null || $property == null)
		{
			return null;
		}
	  	return (substr ($inputString,
	  		strlen($property)+1,
			strlen($inputString)));
	}

	/**
	 * Truncates the inputstring at the given length and appands the
	 * optional string.
	 *
	 * @param string inputString the inputstring
	 * @param integer length
	 * @param string append optional append characters, default to '...'
	 */
	function truncate ($inputString, $length, $append="...")
	{
		$leninput =  strlen ($inputString);
		$lenappend = strlen ($append);
		if (($leninput > $length) && ($leninput > $lenappend))
		{
			return substr ($inputString, 0, $length-$lenappend).$append;
		}
		else
		{
			return $inputString;
		}
	}

	/**
	 * Call the addslashes on the input string, only if get_magic_quotes
	 * is not set
	 *
	 * @param string input the string we would like to evaluate
	 * @return string the optionally modified input string
	 */
	function gpcAddSlashes ($input)
	{
		 return(get_magic_quotes_gpc() == 1 ?
		 	$input : addslashes($input));
	}


	/**
	 * Call the stripslashes on the input string, only if
	 * get_magic_quotes is not set
	 *
	 * @param string input the string we would like to evaluate
	 * @return string the optionally modified input string
	 */
	function gpcStripSlashes ($input)
	{
		 return(get_magic_quotes_gpc() == 1 ?
		 	stripslashes($input) : $input);
	}

	/**
	 * Call the stripslashes on the input string or input array,
	 * only if get_magic_quotes is not set. Calling this function
	 * when the input is an array recursively calls the gpcStripSlashes
	 * on each member of the array. Calling this function on a
	 * string results in a call to the gpcStripSlashes
	 *
	 * @param object input either string or array we would like to evaluate
	 * @return string or array the optionally modified input
	 */
	function gpcStripSlashesDeep ($input)
       	{
	        $input = is_array($input) ?
	            array_map('gpcStripSlashesDeep', $input) :
	                gpcStripSlashes ($input);
	        return $input;
	}

	/**
	 * Returns a (almost) random string with with given length
	 *
	 * @param integer len thelength of the random string we would
	 * like to have
	 * @return string the random string with requested length
	 */
	 function randomString($len)
	 {
	 	$i = 0;
		srand(date("s"));
		while($i<$len)
		{
			$str.=chr((rand()%26)+97);
			$i++;
		}
		$str=$str.substr(uniqid (""),0,22);
		return $str;
	}

	/**
	 * This function returns any UTF-8 encoded text as a list of
	 * Unicode values. Modified by Barry Nauta to not unicode the html tags.
	 *
	 * @author Scott Michael Reynen <scott@randomchaos.com>
	 * @link   http://www.randomchaos.com/document.php?source=php_and_unicode
	 * @see    unicode_to_utf8()
	 */
	function utf8_to_unicode_skip_html ($str)
	{
		$unicode = array();
		$unicodeResult = '';
		$values = array();
		$lookingFor = 1;
		$parsingHtml = false;

		for ($i = 0; $i < strlen( $str ); $i++ )
		{
			$thisValue = ord ($str[$i]);
			if ($str[$i] == '<')
			{
				$parsingHtml = true;
			}
			else if ($str[$i] == '>')
			{
				$parsingHtml = false;
			}

			if ( $thisValue < 128 || $parsingHtml)
			{
				$unicode[] = $thisValue;
			}
			else
			{
				if (count ($values) == 0)
				{
					$lookingFor = ( $thisValue < 224 ) ? 2 : 3;
				}
				$values[] = $thisValue;
				if (count ($values) == $lookingFor)
				{
					$number = ( $lookingFor == 3 ) ?
						(($values[0] % 16) * 4096) + (($values[1] % 64) * 64) + ($values[2] % 64):
						(($values[0] % 32) * 64) + ($values[1] % 64);
					$unicode[] = $number;
					$values = array();
					$lookingFor = 1;
				}
			}
		}
		foreach ($unicode as $c)
		{
			if ($c < 127)
			{
				$unicodeResult .= chr ($c);
			}
			else
			{
				$unicodeResult .= '&#'.$c.';';
			}
		}
		return $unicodeResult;
	}


	/**
	 * This function returns any UTF-8 encoded text as a list of
	 * Unicode values. Modified by Barry Nauta to not unicode the html tags.
	 *
	 * @author Scott Michael Reynen <scott@randomchaos.com>
	 * @link   http://www.randomchaos.com/document.php?source=php_and_unicode
	 * @see    unicode_to_utf8()
	 */
	function utf8_to_unicode ($str)
	{
		$unicode = array();
		$unicodeResult = '';
		$values = array();
		$lookingFor = 1;

		for ($i = 0; $i < strlen ($str); $i++)
		{
			$thisValue = ord ($str [$i]);

			if ($thisValue < 128)
			{
				$unicode[] = $thisValue;
			}
			else
			{
				if (count ($values) == 0)
				{
					$lookingFor = ($thisValue < 224) ? 2 : 3;
				}
				$values[] = $thisValue;
				if (count ($values) == $lookingFor)
				{
					$number = ($lookingFor == 3) ?
						(($values[0] % 16) * 4096) + (($values[1] % 64) * 64) + ($values[2] % 64):
						(($values[0] % 32) * 64) + ($values[1] % 64);
					$unicode[] = $number;
					$values = array();
					$lookingFor = 1;
				}
			}
		}
		foreach ($unicode as $c)
		{
			if ($c < 127)
			{
				$unicodeResult .= chr ($c);
			}
			else
			{
				$unicodeResult .= '&#'.$c.';';
			}
		}
		return $unicodeResult;
	}

	/**
	 * This function converts a Unicode array back to its UTF-8 representation
	 *
	 * @author Scott Michael Reynen <scott@randomchaos.com>
	 * @link   http://www.randomchaos.com/document.php?source=php_and_unicode
	 * @see    utf8_to_unicode()
	 */
	function unicode_to_utf8 ($str)
	{
		if (!is_array ($str))
		{
			return '';
		}
		$utf8 = '';
		foreach ($str as $unicode)
		{
			if ($unicode < 128)
			{
	  			$utf8 .= chr ($unicode);
			}
			elseif ($unicode < 2048)
			{
	  			$utf8 .= chr (192 + (($unicode - ($unicode % 64)) / 64));
	  			$utf8 .= chr (128 + ($unicode % 64));
			}
			else
			{
	  			$utf8 .= chr (224 + (($unicode - ($unicode % 4096)) / 4096));
	  			$utf8.= chr (128 + ((($unicode % 4096) - ($unicode % 64)) / 64));
	  			$utf8.= chr (128 + ($unicode % 64));
			}
		}
		return $utf8;
	}
}
?>