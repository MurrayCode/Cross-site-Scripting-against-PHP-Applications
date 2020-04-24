<?php

require_once ('plugins/contacts/model/Contact.php');
require_once ('plugins/contacts/model/ContactFactory.php');
require_once ('framework/model/Services.php');

/**
 * Services for contacts
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - March 2003
 * @package org.brim-project.plugins.contacts
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class ContactServices extends Services
{

	/**
	 * Default constructor
	 */
	function ContactServices ()
	{
		parent::Services();
		$this->itemFactory = new ContactFactory ();

		$queries = array ();
		include ('plugins/contacts/sql/contactQueries.php');
		$this->queries = $queries;
	}

	/**
	 * Adds a contact for a user
	 *
	 * @param integer userId the identifier for the user
	 * @param object item the contact to be added
	 *
     	 * @return integer last know id. This is the id of the contact
	 * that was newly inserted and for which an id has automatically
	 * been assigned.
	 */
	function addItem ($userId, $item)
	{
		$now = date ("Y-m-d H:i:s");

		// execute the query
		$query = sprintf ($this->queries['addItem'],
			$userId,
			addslashes ($item->parentId),
			addslashes ($item->isParent),
			addslashes ($item->name),
			addslashes (trim ($item->description)),
			addslashes ($item->visibility),
			addslashes ($item->category),
			addslashes ($item->when_created),
			addslashes ($item->alias),
			addslashes (trim ($item->address)),
			addslashes ($item->mobile),
			addslashes ($item->faximile),
			addslashes ($item->tel_home),
			addslashes ($item->tel_work),
			addslashes ($item->organization),
			addslashes (trim ($item->org_address)),
			addslashes ($item->job),
			addslashes ($item->email1),
			addslashes ($item->email2),
			addslashes ($item->email3),
			addslashes ($item->webaddress1),
			addslashes ($item->webaddress2),
			addslashes ($item->webaddress3));
//		die ($query);

		$this->db->Execute($query) or
				die ("Could not add contact.".
				$this->db->ErrorMsg () . " " . $query);

		// fetch the last known id
		// todo this is generic code and is candidate to move to the base Services class
		$query = $this->queries['lastItemInsertId'];
		$result = $this->db->Execute ($query) or
			die ("Could not retrieve last insert id for contact. ".
			$this->db->ErrorMsg () . " " . $query);

        return $result->fields[0];
	}

	/**
	 * Modifies a contact.
	 *
 	 * @param integer userId the identifier for the user who modifies a contact
	 * @param object item the modified contact
	 */
	function modifyItem ($userId, $item)
	{
		//die ("ContactService:: modifyItem ".print_r ($item));
		$now = date ("Y-m-d H:i:s");

		$query = sprintf ($this->queries['modifyItem'],
			$now,
			addslashes ($item->name),
			addslashes ($item->parentId),
			addslashes ($item->visibility),
			$item->isDeleted,
			addslashes ($item->job),
			addslashes ($item->alias),
			addslashes ($item->organization),
			addslashes (trim ($item->org_address)),
			addslashes ($item->tel_home),
			addslashes ($item->tel_work),
			addslashes ($item->faximile),
			addslashes ($item->mobile),
			addslashes (trim ($item->address)),
			addslashes (trim ($item->description)),
			addslashes ($item->email1),
			addslashes ($item->email2),
			addslashes ($item->email3),
			addslashes ($item->webaddress1),
			addslashes ($item->webaddress2),
			addslashes ($item->webaddress3),
			$item->itemId
		);
		$this->db->Execute($query)
			or die ("Error modifying item: " .
	 		$this->db->ErrorMsg() . " " . $query);
	}

}
?>