<?php

require_once ('framework/model/ItemFactory.php');
require_once ('framework/model/Information.php');

/**
 * InformationFactory
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
class InformationFactory extends ItemFactory
{
	/**
	 * Default constructor
	 */
	function InformationFactory ()
	{
		parent::ItemFactory ();
	}


	/**
	 * Factory method: Returns a database result into an item
	 *
	 * @param object result the result retrieved from the database
	 * @return array the items constructed from the database resultset
	 */
	function resultsetToItems ($result)
	{
		$items = array ();
		while (!$result -> EOF)
		{
			$item = new Information
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
				$result->fields['refering_id'],
				trim ($result->fields['refering_type']),
				trim ($result->fields['reliability']),
				trim ($result->fields['complete']),
				trim ($result->fields['information_url']),
				trim ($result->fields['image_url']),
				trim ($result->fields['comments'])
			);
			$items [] = $item;
			$result->MoveNext ();
		}
		return $items;
	}

	/**
	 * Factory method. Return an HTTP request into an item by
	 * fecthing the appropriate parameters from the POST request
	 *
	 * @return object the item constructed from the POST request
	 * @uses the POST request
	 */
	function requestToItem ()
	{
		$visibility = $this->getFromPost ('visibility', 'private');
		$parentId = $this->getFromPost ('parentId', 0);
		$isParent = $this->getFromPost ('isParent', 0);
		$when_created = $this->getFromPost ('when_created', null);
		$when_modified = $this->getFromPost ('when_modified', null);
		$itemId = $this->getFromPost ('itemId', 0);
		$category = $this->getFromPost ('category', null);
		$isDeleted = $this->getFromPost ('isDeleted', 0);
		$name = $this->getFromPost ('name', null);
		$description = $this->getFromPost ('description', null);

		$referingId = $this->getFromPost ('referingId', 0);
		$referingType = $this->getFromPost ('referingType', null);
		$imageURL = $this->getFromPost ('imageURL', null);
		$informationURL = $this->getFromPost ('informationURL', null);
		$complete = $this->getFromPost ('complete', null);
		$reliability = $this->getFromPost ('reliability', null);
		$comments = $this->getFromPost ('comments', null);

		$item = new Information
		(
			$itemId,
			$_SESSION['brimUsername'],
			$parentId,
			$isParent,
			$name,
			$description,
			$visibility,
			$category,
			$isDeleted,
			$when_created,
			$when_modified,
			$referingId,
			$referingType,
			$reliability,
			$complete,
			$informationURL,
			$imageURL,
			$comments
		);
		return $item;
	}

	function requestToItemErrors ()
	{
		return null;
	}
}
?>