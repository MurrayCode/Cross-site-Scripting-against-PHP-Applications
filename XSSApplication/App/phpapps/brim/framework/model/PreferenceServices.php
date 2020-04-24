<?php

require_once ('framework/model/Preference.php');
require_once ('framework/model/PreferenceFactory.php');
require_once ('framework/model/Services.php');

/**
 * Operations on preferences, embedding the so-called business logig concerning
 * preferences. Typical operations are getPreferences, setPreference,
 * deleteAllPreferences etc.
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
class PreferenceServices extends Services
{
	/**
 	 * Default constructor, it calls the parent constructor, sets the
 	 * preference factory and loads the sql queries.
	 */
	function PreferenceServices ()
	{
		parent::Services();
		$this->itemFactory = new PreferenceFactory ();
		$queries = array ();
		include ('framework/sql/preferenceQueries.php');
		$this->queries = $queries;
	}

	/**
	 * Adds preferences for a user
	 *
	 * @param integer userId the identifier for the user
	 * @param object item the item to be added
	 */
	function addItem ($userId, $item)
	{
		if ($this->getUserName() != $userId)
		{
			if ($_SESSION['brimUserIsAdmin'] != 'true')
			{
				return null;
			}
		}
		$now = date ("Y-m-d H:i:s");
		$query  = sprintf ($this->queries ['addItem'],
			$userId,
			$item->parentId,
			addslashes ($item->name),
			addslashes ($item->description),
			$now,
			$item->value);
		$result = $this->db->Execute($query)
			or die("AddPreferences: " . $this->db->ErrorMsg() . " " . $query);
	}

	/**
	 * Modifies a preference.
	 *
 	 * @param string userId the identifier (username) for the user who modifies
 	 * a preference
	 * @param object item the modified preference.
	 */
	function modifyItem ($userId, $item)
	{
		$now = date ("Y-m-d H:i:s");
		$query  = sprintf ($this->queries['modifyItem'],
			$now,
			addslashes ($item->name),
			addslashes ($item->description),
			$item->parentId,
			$item->value,
			$item->itemId) ;
		$result = $this->db->Execute($query)
			or die("ModifyPreference: " . $this->db->ErrorMsg() . " " . $query);
	}

	/**
	 * Returns all preferences of a specific user in an array
	 *
	 * @param string userId the identifier (username) of the user for which we
	 * would like to have the preferences
	 * @return array an array of all the preferences of this user
	 * @todo should we really include the owner in the result?
	 */
	function getPreferencesAsArray ($userId)
	{
		$result = array ();
		$preferences = parent::getItems ($userId);
		if ($preferences != null)
		{
			foreach ($preferences as $preference)
			{
				$result [$preference->name]=$preference->value;
			}
		}
		$result['owner']=$userId;
		return $result;
	}

	/**
	 * Returns the id of a specific preference (lookup on the name of the
	 * preferene, not the object!) for a specific user
	 *
	 * @param integer userId the identifier of the user
	 * @param string preferenceName the name of the preference for which we
	 * would like to know its identifier
	 * @return integer the identifier of the preference for the specific user
	 */
	function getPreferenceId ($userId, $preferenceName)
	{
		$query = sprintf
			($this->queries ['getPreferenceId'],
				$userId, $preferenceName);
		$result = $this->db->Execute($query)
			or die("GetPreferenceId: " .
				$this->db->ErrorMsg() . " " . $query);
		return $result->fields[0];
	}

	/**
	 * Gets the preference-value for a specific user
	 *
	 * @param string userId the identifier (username) of the user
	 * @param string preferenceName the name of the preference for which we
	 * would like to  know its value
	 * @return string the value of the preference for the specific user
	 */
	function getPreferenceValue ($userId, $preferenceName)
	{
		$query = sprintf
			($this->queries ['getPreference'],
				$userId, $preferenceName);
		$result = $this->db->Execute($query)
			or die("GetPreference: ".
				$this->db->ErrorMsg()." ".$query);
		return trim ($result->fields[0]);
	}

	/**
	 * Sets a preferences (name/value pair) for a specific user
	 *
	 * @param string userId the identifier (username) of the user
	 * @param string name the name of the preference to set
	 * @param string value the value of the preference to set
	 */
	function setPreference ($userId, $name, $value)
	{
		$preference = $this->getPreferenceValue ($userId, $name);
		if (!isset ($preference) || $preference == null)
		{
			$preference = $this->itemFactory->getPreference
				($userId, $name, $value);
			$this->addItem ($userId, $preference);
		}
		else
		{
			$prefId = $this->getPreferenceId ($userId, $name);
			$pref = $this->getItem ($userId, $prefId);
			$pref->value = $value;
			$this->modifyItem ($userId, $pref);
		}
	}

	/**
	 * Delete all preferences for a specific user (owner).
	 *
	 * @param string owner the owner of the preferences
	 */
	function deleteAllPreferences ($owner)
	{
		$query = sprintf ($this->queries ['deleteAllPreferences'], $owner);
		$result = $this->db->Execute($query)
			or die("DeleteAllPreferences: ".  $this->db->ErrorMsg()." ".$query);
	}
}
?>
