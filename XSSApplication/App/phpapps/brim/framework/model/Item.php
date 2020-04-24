<?php

/**
 * Abstract base class Item.
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
 * @abstract
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class Item
{
	/**
	 * The type of the item
	 *
	 * @access protected
	 * @var string
	 */
	var $type;

	/**
	 * The owner of the item
	 *
	 * @access protected
	 * @var string
	 */
	var $owner;

	/**
	 * The id of the item
	 *
	 * @access protected
	 * @var integer
	 */
	var $itemId;

	/**
	 * The identifier of the item's parent
	 *
	 * @access protected
	 * @var integer
	 */
	var $parentId;

	/**
	 * The identifier that indicated whether the item is a parent itself
	 *
	 * @access protected
	 * @var bool
	 */
	var $isParent;

	/**
	 * The name of the item
	 *
	 * @access protected
	 * @var string
	 */
	var $name;

	/**
	 * The description of the item
	 *
	 * @access protected
	 * @var string
	 */
	var $description;

	/**
	 * The date when the item was created
	 *
	 * @access protected
	 * @var string
	 */
	var $when_created;

	/**
	 * The date when the item was last modified
	 *
	 * @access protected
	 * @var string
	 */
	var $when_modified;

	/**
	 * The visibility of the item
	 *
	 * @access protected
	 * @var string
	 */
	var $visibility;

	/**
	 * The category of the item
	 *
	 * @access protected
	 * @var string
	 */
	var $category;

	/**
	 * The children of this item
	 *
	 * @access private
	 * @var array
	 */
	var $children;

	/**
	 * Array used to be able to manipulate the object
	 * outside its member boundaries. Not very clean but
	 * very useful when we are, for instance, importing
	 * an item for which we have no exact mapping
	 *
	 * @access private
	 * @var array
	 */
	var $parameters;

	/**
	 * Indicator whether this item is deleted
	 *
	 * @access private
	 * @var boolean
	 */
	var $isDeleted;

	/**
	 * Default constructor, sets the creation date
	 *
	 * @param integer theID the itemId
	 * @param string theOwner the owner
	 * @param integer theParentId the identifier of the parent
	 * @param boolean parent is this a parent yes or no?
	 * @param string theName the name of this item
	 * @param string theDescription the decription
	 * @param string theVisibility private or public
	 * @param string theCategory the category
	 * @param string theCreation when was this item created?
	 * @param string theModified when was this item last modified?
	 */
	function Item ($theID, $theOwner, $theParentId, $parent, $theName,
		$theDescription, $theVisibility, $theCategory, $deleted,
		$theCreation, $theModified)
	{
		$this->itemId = $theID;
		$this->owner = $theOwner;
		$this->parentId = $theParentId;
		$this->isParent = $parent;
		$this->name = $theName;
		$this->description = $theDescription;
		$this->visibility = $theVisibility;
		$this->category = $theCategory;
		$this->isDeleted = $deleted;
		if ($theCreation == null)
		{
			$now = date ("Y-m-d H:i:s");
			$this->when_created = $now;
		}
		else
		{
			$this->when_created = $theCreation;
		}
		$this->when_modified = $theModified;
		$this->children = array ();
		$this->parameters = array ();
	}

	/**
	 * Checks whether the constructed item is a valid item (has all the
	 * required fields)
	 *
	 * @return boolean <code>true</code> if the item is valid,
	 * <code>false</code> otherwise
	 */
	function isValid ()
	{
		//
		// cannot be root and must have a name
		//
		if ($this->itemId == null)
		{
			return isset ($this->name);
		}
		else
		{
			return (isset ($this->name) && ($this->itemId != 0));
		}
	}


	/**
	 * Returns the children of this item
	 *
	 * @return array children the children (which are items!) of this item
	 */
	function getChildren ()
	{
		return $this->children;
	}

	/**
	 * Adds a child (which is an item!) to the list of children of this item
	 *
	 * @param object child the child to be added
	 */
	function addChild (&$child)
	{
		$this->children [] = $child;
	}

	/**
	 * Returns whether this item is a parent
	 *
	 * @return boolean <code>true</code> if this item is a parent,
	 * <code>false</code> otherwise
	 */
	function isParent ()
	{
		return $this->isParent;
	}

	/**
	 * Returns a HTML table representation of this object
	 * by creating a table which lists the item's vars and
	 * resp. values
	 *
	 * @return string an HTML representation of this object
	 */
	function toHTML ()
	{
		$result  = ('<table border="0">\n');
		$result .= ('<tr>\n');

		$vars = get_object_vars ($this);
		foreach ($vars as $name => $value)
		{
			echo ('<td>'.$name.'</td>');
			if ($name == 'url')
			{
				echo ('<td><a href="'.$value.'">'.$value.'"</a></td>');
			}
			else
			{
				echo ('<td>'.$value.'</td>');
			}
		}
		$result .= ('</tr>\n');
		$result .= ('</table>\n');
		return $result;
	}

	/**
	 * Adds a name/value pair to this item.
	 * @param sting name the name of the tupel
	 * @param object value the value of the tupel
	 */
	function addParameter ($name, $value)
	{
		$this->parameters[$name] = $value;
	}

	/**
	 * Retrieves the value from the parameter list,
	 * based on the name under which it is stored
	 *
	 * @param string name the parameter name
	 * @return object the value matching the name
	 * or <code>null</code> if no match is found
	 */
	function getParameter ($name)
	{
		if (isset ($this->parameters[$name]))
		{
			return $this->parameters[$name];
		}
		return null;
	}
	
	function equals ($thisOne, $other)
	{
		if ($thisOne->name == $other->name)
		{
			return 0;
		}
		return($thisOne->name < $other->name) ? -1 : 1;
	}
	
}
?>