<?php

require_once ('plugins/notes/model/Note.php');
require_once ('plugins/notes/model/NoteFactory.php');
require_once ('framework/model/Services.php');

/**
 * Operations on notes
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - March 2003
 * @package org.brim-project.plugins.notes
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class NoteServices extends Services
{
	/**
 	 * Default constructor
	 */
	function NoteServices ()
	{
		parent::Services();
		$this->itemFactory = new NoteFactory ();

		$queries = array ();
		include ('plugins/notes/sql/noteQueries.php');
		$this->queries = $queries;
	}

	/**
	 * Adds a note for a user
	 *
	 * @param integer userId the identifier for the user
	 * @param object item the item to be added
	 *
     * @return integer last know id. This is the id of the item
	 * that was newly inserted and for which an id has automatically
	 * been assigned.
	 */
	function addItem ($userId, $item)
	{
		$now = date ("Y-m-d H:i:s");
		$query  = sprintf ($this->queries['addItem'],
			$userId,
			addslashes ($item->parentId),
			addslashes ($item->isParent),
			addslashes ($item->name),
			addslashes ($item->description),
			addslashes ($item->visibility),
			addslashes ($item->category),
			$now,
			addslashes ($item->position));
		$result = $this->db->Execute($query)
			or die("AddNote: " . $this->db->ErrorMsg() . " " . $query);
	}

	/**
	 * Modifies a note.
	 *
 	 * @param integer userId the identifier for the user who modifies a note
	 * @param object item the modified note
	 */
	function modifyItem ($userId, $item)
	{
		$now = date ("Y-m-d H:i:s");

		$query  = sprintf ($this->queries['modifyItem'],
			$now,
			addslashes ($item->name),
			addslashes ($item->visibility),
			addslashes ($item->description),
			addslashes ($item->parentId),
			$item->isDeleted,
			$item->position,
			$item->itemId) ;

		$result = $this->db->Execute($query)
			or die("ModifyNote: " . $this->db->ErrorMsg() . " " . $query);
	}

	function getNotePositions ($userId)
	{
		$query  = sprintf ($this->queries['getNotePositions'], $userId);
		$result = $this->db->Execute($query)
			or die("AddNote: " . $this->db->ErrorMsg() . " " . $query);
		$returnArray = array ();
		while (!$result->EOF)
 		{
			if (isset ($result->fields['position']) && trim ($result->fields['position']) != "")
			{
				$returnArray [$result->fields['item_id']] = $result->fields['position'];
			}
			else	
			{
				$returnArray [$result->fields['item_id']] = "0;0;0;0;0";
			}
			$result->MoveNext ();
		}
		return $returnArray;
	}
}
?>
