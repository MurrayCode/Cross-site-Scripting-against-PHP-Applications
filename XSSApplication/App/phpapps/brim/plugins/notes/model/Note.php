<?php

require_once ('framework/util/StringUtils.php');
require_once ('framework/model/Item.php');

/**
 * A note item
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
class Note extends Item
{

	/**
 	 * Full blown constructor
	 *
	 * @param integer theItemId the itemId
	 * @param string theOwner the owner
	 * @param integer theParentId the identifier of the parent
	 * @param boolean parent is this a parent yes or no?
	 * @param string theName the name of this item
	 * @param string theDescription the decription
	 * @param string theVisibility private or public
	 * @param string theCategory the category
	 * @param boolean deleted is this item marked as deleted?
	 * @param string theCreation when was this item created?
	 * @param string theModified when was this item last modified?
	 */
	function Note ($theItemId, $theOwner, $theParentId, $parent,
		$theName, $theDescription,
		$theVisibility, $theCategory, $deleted,
		$theCreation, $theModified)
	{
		parent :: Item ($theItemId, $theOwner, $theParentId, $parent,
				$theName, $theDescription, $theVisibility,
				$theCategory, $deleted, $theCreation, $theModified);

		$this->type="Note";
	}
}
?>