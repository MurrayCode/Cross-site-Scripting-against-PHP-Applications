<?php

require_once ('plugins/passwords/model/Password.php');
require_once ('plugins/passwords/model/PasswordFactory.php');
require_once ('framework/model/Services.php');

/**
 * Operations on passwords
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2004
 * @package org.brim-project.plugins.passwords
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class PasswordServices extends Services
{
	/**
 	 * Default constructor
	 */
	function PasswordServices ()
	{
		parent::Services();
		$this->itemFactory = new PasswordFactory ();

		$queries = array ();
		include ('plugins/passwords/sql/passwordQueries.php');
		$this->queries = $queries;
	}

	/**
	 * Adds a password for a user
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
			addslashes ($item->login),
			addslashes ($item->password),
			addslashes ($item->url));
		$result = $this->db->Execute($query)
			or die("AddItem: " . $this->db->ErrorMsg() . " " . $query);
	}

	/**
	 * Modifies a item.
	 *
 	 * @param integer userId the identifier for the user who modifies
	 * the item
	 * @param object item the modified item
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
			addslashes ($item->login),
			addslashes ($item->password),
			addslashes ($item->url),
			$item->itemId) ;

		$result = $this->db->Execute($query)
			or die("ModifyItem: " . $this->db->ErrorMsg() . " " . $query);
	}

   function getItem ($userId, $itemId, $passPhrase=null)
    {
        $query = sprintf ($this->queries['getItem'], $userId, $itemId);

        $result = $this->db->Execute($query) or
            die("GetItem: ". $this->db->ErrorMsg()." query: -". $query . "-");

        $items = $this->itemFactory->resultsetToItems ($result, $passPhrase);
        if (count ($items) == 0)
        {
            return null;
        }
        else
        {
            return $items[0];
        }
    }

}
?>
