<?php

require_once 'framework/util/StringUtils.php';

/**
 * The comparator used by QuickSort
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
class Comparator
{
	var $fieldToCompare;
	var $stringUtils;

	/**
	 * A constructor with the name of the field on which we
	 * would like to compare objects
	 * @param string theFieldToCompare the name of the field
	 */
	function Comparator ($theFieldToCompare)
	{
		$this->fieldToCompare = $theFieldToCompare;
		$this->stringUtils = new StringUtils ();
	}

	/**
	 * Compares two objects
	 *
	 * @param first the first object to compare
	 * @param object second the second object to compare
	 * @return <0 when first < second || first != null && second == null
	 * @return 0  when first == second
	 * @return >0 when first > second || first == null && second != null
	 */
	function compare ($first, $second)
	{
		if (!isset ($first))
		{
			return 1;
		}
		if (!isset ($second))
		{
			return -1;
		}
		$fieldToCompare = $this->fieldToCompare;
		$fst = strtolower ($first->$fieldToCompare);
		if (strlen ($fst) == 0)
		{
			$fst = ' ';
		}
		$snd = strtolower ($second->$fieldToCompare);
		if (strlen ($snd) == 0)
		{
			$snd = ' ';
		}
		return substr_compare ($fst, $snd, 0);
	}
}
?>