<?php

/**
 * QuickSort. This sorting algorithm uses a callback funtion (via a omparator,
 * so it can be implied to arrays, objects etc.
 *
 * This file is part of the Brim project. The brim- project is located at the
 * following location: {@link http: //www. brim- project. org/ http: //www.
 * brim- project. org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 * @subpackage util
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class QuickSort
{

	/**
	 * Default empty constructor
	 */
	function QuickSort ()
	{
		// nothing
	}

	/**
	 * The actual function
	 * @param array input object array containing objects to be sorted
	 * @param object comparator the comparator that compares two objects in
	 * the input array
	 */
	function sort (&$input, $comparator)
	{
		$this->internalSort ($input, $comparator, 0, count($input));
	}

	/**
	 * The partial sorting function. Private/internal use only
	 * @param array input object array containing objects to be sorted
	 * @param object comparator the comparator that compares two objects in
	 * the input array
	 * @param int low the starting offset
	 * @param int high the ending offset
	 * @private
	 */
	function internalSort (&$input, $comparator, $low, $high)
	{
		if ($low < $high)
		{
			$tmpLow = $low;
			$tmpHigh = $high + 1;
			$current = $input[$low];

			$done = false;
			while (!$done)
			{

				while (++$tmpLow <= $high &&
					$comparator->compare ($input[$tmpLow], $current) < 0);

				while ($comparator->compare ($input[--$tmpHigh], $current) >0);

				if ($tmpLow < $tmpHigh)
				{
					$this->swap ($input, $tmpLow, $tmpHigh);
				}
				else
				{
					$done = true;
				}
			}
			$this->swap ($input, $low, $tmpHigh);
			$this->internalSort ($input, $comparator, $low, $tmpHigh-1);
			$this->internalSort ($input, $comparator, $tmpHigh+1, $high);
		}
	}

	/**
	 * Swap two elements in an array
	 * @param array input the input array
	 * @param int i the first element to be swapped
	 * @param int j the second element to be swapped
	 * @private
	 */
	function swap (&$input, $i, $j)
	{
		$temp = $input[$i];
		$input[$i]=$input[$j];
		$input[$j]=$temp;
	}
}
?>