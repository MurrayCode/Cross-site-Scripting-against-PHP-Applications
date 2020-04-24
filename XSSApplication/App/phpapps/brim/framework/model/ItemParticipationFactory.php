<?php

require_once ('framework/util/StringUtils.php');
/**
 * ItemarticipationFactory
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - May 2006
 * @package org.brim-project.framework
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class ItemParticipationFactory
{
	var $stringUtils;
	
	function ItemParticipationFactory ()
	{
		$this->stringUtils = new StringUtils();	
	}
	
	function resultSetToItemParticipation ($result)
	{
		while (!$result->EOF)
		{
			$item = new ItemParticipation 
			(
				$result->fields['item_id'],
				$result->fields['owner'],
				$result->fields['participator'],
				$result->fields['plugin'],
				$result->fields['participation_rights'],
				$result->fields['activation_code']
			);
			$items [] = $item;
			$result->MoveNext();
		}
		return $result [0];
	}
	
	/**
	 * Iterates over the resultset and returns an array
	 * containing only the participator names
	 *
	 * @param array $result
	 * @return array
	 */
	function getNamesFromParticipationList ($result)
	{
		$names = array ();
		while (!$result->EOF)
		{
			$names [] = $result -> fields ['participator'];
			$result->MoveNext();
		}
		return $names;
	}
	
	function requestToItemParticipation ()
	{
		$item = new ItemParticipation 
		(
			$this->getFromPost ('itemId', 0),
			$this->getFromPost ('owner', null),
			$this->getFromPost ('participator', null),
			$this->getFromPost ('plugin', null),
			$this->getFromPost ('participation_rights', null),
			$this->getFromPost ('activation_code', null)
		);
		return $item;
	}
	
	/**
	 * Gets a value from the POST or returns the default value is the
	 * requested value is not found.
	 * The <code>$this->stringUtils->gpcStripSlashes</code> function
	 * will be performed on the incoming array.
	 *
	 * @param string value the requested value
	 * @param object default the default value if the requested value
	 * cannot be found
	 * @return string the value from the post or the default value if
	 * the requested value cannot be found
	 */
	function getFromPost ($value, $default)
	{
		if (isset ($_POST[$value]))
		{
			return $this->stringUtils->gpcStripSlashes ($_POST[$value]);
		}
		else
		{
			return $default;
		}
	}

}
?>