<?php

require_once ('framework/model/ItemFactory.php');
require_once ('plugins/notes/model/Note.php');

/**
 * NoteFactory
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.plugins.notes
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class NoteFactory  extends ItemFactory
{
	/**
	 * Default constructor
	 */
	function NoteFactory ()
	{
		parent::ItemFactory ();
	}

	/**
	 * Returns the type of this specific item
	 * @return string the type of this specific
	 * item: <code>Note</code>
	 */
	function getType ()
	{
		return "Note";
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
		while (!$result->EOF)
		{
			$item = new Note (
				$result->fields['item_id'],
				trim ($result->fields['owner']),
				$result->fields['parent_id'],
				$result->fields['is_parent'],
				trim ($this->stringUtils->gpcStripSlashes
					($result->fields['name'])),
				trim ($this->stringUtils->gpcStripSlashes
					($result->fields['description'])),
				trim ($result->fields['visibility']),
				trim ($result->fields['category']),
				$result->fields['is_deleted'],
				$result->fields['when_created'],
				$result->fields['when_modified']);
			$items [] = $item;
			$result->MoveNext();
		}
		return $items;
	}

    /**
	 * Returns an empty item
	 * @return object an empty item, all values
	 * set to -null-
	 */
	function getEmptyItem ()
	{
		$item = new Note
			(null, null, null, null,
			null, null, null, null,
			null, null, null
		);
		return $item;
	}

	function requestToItem ()
	{
		$itemId = $this->getFromPost ('itemId', 0);
		$owner = $_SESSION['brimUsername'];
		$parentId = $this->getFromPost ('parentId', 0);
		$isParent = $this->getFromPost ('isParent', 0);
		$name =
			$this->stringUtils->gpcStripSlashes ($_POST['name']);
		$description = $this->getFromPost ('description', null);
		$visibility = $this->getFromPost ('visibility', 'private');
		$category = $this->getFromPost ('category', null);
		$isDeleted = $this->getFromPost ('isDeleted', 0);
		$when_created = $this->getFromPost ('when_created', null);
		$when_modified = $this->getFromPost ('when_modified', null);
		$item = new Note
			(
				$itemId,
				$owner,
				$parentId,
				$isParent,
				$name,
				$description,
				$visibility,
				$category,
				$isDeleted,
				$when_created,
				$when_modified
			);
		return $item;
	}
}
?>