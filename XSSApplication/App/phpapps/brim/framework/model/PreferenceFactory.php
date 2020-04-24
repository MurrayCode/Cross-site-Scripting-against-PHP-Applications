<?php

require_once ('framework/model/ItemFactory.php');
require_once ('framework/model/Preference.php');

/**
 * The PreferencesFactory is a class that is capable of creating preference
 * objects from different sources like database resultsets, http requests etc.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - January 2004
 * @package org.brim-project.framework
 * @subpackage model
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class PreferenceFactory  extends ItemFactory
{
	/**
	 * Default constructor, calls the parent constructor
	 */
	function PreferenceFactory ()
	{
		parent::ItemFactory();
	}

	/**
	 * Factory method. Return an HTTP request into a preference-item by fecthing
	 * the appropriate parameters from the POST request
	 *
	 * @return object the item constructed from the POST request
	 */
	function requestToItem ()
	{
		$visibility = $this->getFromPost ('visibility', 'private');
		$loginName = $this->getFromPost ('loginName', null);
		$parentId = $this->getFromPost ('parentId', 0);
		$isParent = $this->getFromPost ('isParent', 0);
		$when_created = $this->getFromPost ('when_created', null);
		$when_modified = $this->getFromPost ('when_modified', null);
		$itemId = $this->getFromPost ('itemId', 0);
		$category = $this->getFromPost ('category', null);
		$isDeleted = $this->getFromPost ('isDeleted', 0);
		$value = $this->getFromPost ('theValue', null);
		$name = $this->getFromPost ('name', null);
		$description = $this->getFromPost ('description', null);
		$item = new Preference
		(
			$itemId,
			$loginName,
			$parentId,
			$isParent,
			$name,
			$description,
			$visibility,
			$category,
			$isDeleted,
			$when_created,
			$when_modified,
			$value
		);
		return $item;
	}

	/**
	 * Factory method: Returns a database result into a preference-item
	 *
	 * @param object result the resultset retrieved from the database
	 * @return array the items constructed from the database resultset
	 */
	function resultsetToItems ($result)
	{
		$items = array ();
		while (!$result -> EOF)
		{
			$item = new Preference
			(
				$result->fields['item_id'],
				trim ($result->fields['owner']),
				$result->fields['parent_id'],
				$result->fields['is_parent'],
				trim ($result->fields['name']),
				trim ($result->fields['description']),
				trim ($result->fields['visibility']),
				trim ($result->fields['category']),
				$result->fields['is_deleted'],
				$result->fields['when_created'],
				$result->fields['when_modified'],
				trim ($result->fields['value'])
			);
			$items [] = $item;
			$result->MoveNext ();
		}
		return $items;
	}

	/**
	 * Creates a simple preference by only setting the default values
	 *
	 * @param string owner the owner of the preferences
	 * @param string name the name of the preference
	 * @param value the value of the preference
	 * @return object preference a default preference objects with only the
	 * owner, the name and the value set.
	 */
	function getPreference ($owner, $name, $value)
	{
		return new Preference
		(
			0,
			$owner,
			0,
			0,
			$name,
			null,
			'private',
			null,
			false,
			null,
			null,
			$value
		);
	}
}
?>
