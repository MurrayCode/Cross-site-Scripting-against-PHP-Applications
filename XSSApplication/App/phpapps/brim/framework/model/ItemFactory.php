<?php

require_once ('framework/util/request/RequestCast.class.php');
require_once ('framework/util/ClassHelper.class.php');
require_once ('framework/util/StringUtils.php');

/**
 * ItemFactory
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.framework
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class ItemFactory
{
	/**
	 * Common string utilities
	 *
	 * @var StringUtils
	 */
	var $stringUtils;

	/**
	 * The tool to cast a request to an Item
	 *
	 * @access protected
	 * @var RequestCast object of type RequestCast
	 */
	var $requestCast;

	/**
	 * Default constructor
	 */
	function ItemFactory ()
	{
		$this->requestCast = new RequestCast ($this->getType ());
		$this->stringUtils = new StringUtils ();
	}

	/**
	 * Returns the type of this object
	 * @abstract
	 */
	function getType ()
	{
		return "null";
	}

	/**
	 * Factory method: Returns a database result into an item
	 *
	 * @param object result the result retrieved from the database
	 * @return array the items constructed from the database resultset
	 */
	function resultsetToItems ($result)
	{
		//
		// Changed by Michael. Precondition is that every field of the
		// resultset equals a attribute name. This is done with SQL
		// aliases in the request.
		// We gain flexibility (adding / removing fields in the config)
		//
		$items = array ();

		while ($object = $result->FetchNextObj())
		{
			$object->type = $this->getType ();
			$items [] =
				ClassHelper::typecast($object, $this->getType ());
		}
		return $items;
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

	/**
	 * Factory method. Return an HTTP request into an item by fecthing
	 * the appropriate parameters from the POST request
	 *
	 * @param object Item or null. If Item is provided, the request
	 * only updates the given item.
	 * @return object the item constructed from the POST request
	 * @uses the POST request
	 * @author Michael Haussmann
	 */
	function requestToItem ($item = null)
	{
		if (!isset ($parentId))
		{
			$parentId = 0;
		}
		if(isset ($item))
		{
			$resultItem = $this->requestCast->castUpdate($item);
		}
		else
		{
			$resultItem = $this->requestCast->cast();
			$resultItem->owner = $_SESSION['brimUsername'];
		}
		//$this->type is done via config
		return $resultItem;
	}

	/**
	 * Factory method. Return an array of the error messages raised
	 * during the cast from the POST request
	 * The messages may be empty strings, or keys to the language
	 * dictionary.
	 * They are specified in the RequestCastConfiguration.
	 *
	 * @return array the error messages raised during the cast from
	 * the POST request
	 * @uses the POST request
	 * @author Michael Haussmann
	 */
	function requestToItemErrors ()
	{
		if ($this->requestCast == null)
		{
			echo ("ItemFactory->requestToItems. RequestCast is null");
		}
		else
		{
			return $this->requestCast->getErrorMessages();
		}
	}
}
?>