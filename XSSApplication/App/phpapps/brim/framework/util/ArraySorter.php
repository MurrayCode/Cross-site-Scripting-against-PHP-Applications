<?php

/**
 * Array-sorter, based on an article at the php website:
 * http://www.php.net/manual/en/function.uksort.php
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Oliwier Ptak <aleczapka at gmx dot net>
 * @package org.brim-project.framework
 * @subpackage util
 *
 * @copyright Oliwier Ptak <aleczapka at gmx dot net>
 	<pre>
	Sample $input_array:

	Array
	(
		[0] => Array
			(
				[id] => 961
				[uid] => 29
				[gid] => 12
				[parent_id] => 147
				[created] => 20041206105350
				[modified] => 20041206110702
			)

		[1] => Array
			(
				[id] => 41
				[uid] => 29
				[gid] => 12
				[parent_id] => 153
				[created] => 20041025154009
				[modified] => 20041206105532
			)

		[2] => Array
			(
				[id] => 703
				[uid] => 29
				[gid] => 12
				[parent_id] => 419
				[created] => 20041025154132
				[modified] => 20041027150259
			)

	Example of usage:
		&lt;?php
			function multi_sort(&$array, $key, $asc=true)
			{
				$sorter = new array_sorter($array, $key, $asc);
				return $sorter->sort();
			}
			//sort by parent_id in descending order
			$my_array = multi_sort($input_array, "parent_id", false);
		?&gt;

	The result array will be:
	Array
	(

		[0] => Array
			(
				[id] => 703
				[uid] => 29
				[gid] => 12
				[parent_id] => 419
				[created] => 20041025154132
				[modified] => 20041027150259
			)

		[1] => Array
			(
				[id] => 41
				[uid] => 29
				[gid] => 12
				[parent_id] => 153
				[created] => 20041025154009
				[modified] => 20041206105532
			)

		[2] => Array
			(
				[id] => 961
				[uid] => 29
				[gid] => 12
				[parent_id] => 147
				[created] => 20041206105350
				[modified] => 20041206110702
			)
	</pre>
 */
class ArraySorter
{
	var $skey = false;
	var $sarray = false;
	var $sasc = true;

	/**
	 * Constructor
	 *
	 * @access public
	 * @param mixed $array array to sort
	 * @param string $key array key to sort by
	 * @param boolean $asc sort order (ascending or descending)
	 */
	function ArraySorter (&$array, $key, $asc=true)
	{
		$this->sarray = $array;
		$this->skey = $key;
		$this->sasc = $asc;
	}

	/**
	 * Sort method
	 *
	 * @access public
	 * @param boolean $remap if true reindex the array to rewrite indexes
	 */
	function sort($remap=true)
	{
		$array = &$this->sarray;
		uksort($array, array($this, "_as_cmp"));
		if ($remap)
		{
			$tmp = array();
			while (list($id, $data) = each($array))
			$tmp[] = $data;
			return $tmp;
		}
		return $array;
	}

	/**
	 * Custom sort function
	 *
	 * @access private
	 * @param mixed $a an array entry
	 * @param mixed $b an array entry
	 */
	function _as_cmp($a, $b)
	{
		//since uksort will pass here only indexes
		// get real values from our array
		if (!is_array($a) && !is_array($b))
		{
			$a = $this->sarray[$a][$this->skey];
			$b = $this->sarray[$b][$this->skey];
		}

		//if string - use string comparision
		if (!ctype_digit($a) && !ctype_digit($b))
		{
			if ($this->sasc)
				return strcasecmp($a, $b);
			else
				return strcasecmp($b, $a);
		}
		else
		{
			if (intval($a) == intval($b))
				return 0;

			if ($this->sasc)
				return ($a > $b) ? -1 : 1;
			else
				return ($a > $b) ? 1 : -1;
		}
	}
}
?>