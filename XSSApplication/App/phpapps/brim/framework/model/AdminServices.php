<?php

require_once ('framework/model/Services.php');

/**
 * Operations on preferences
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - February 2004
 * @package org.brim-project.framework
 * @subpackage model
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class AdminServices extends Services
{
	/**
 	 * Default constructor
	 */
	function AdminServices ()
	{
		parent::Services();
		$queries = array ();
		include ('framework/sql/adminQueries.php');
		$this->queries = $queries;
	}

	/**
	 * Modifies an admin configi (name/value combination).
	 *
	 * @param string name the name of the parameter we would like to change
	 * @param string value the value of the parameter we would like to change
	 */
	function modifyItem ($name, $value)
	{
		$existing = $this->getAdminConfig ($name);
		if (!isset ($existing))
		{
			$query  = sprintf ($this->queries['addItem'],
				$name, $value);
		}
		else
		{
			$query  = sprintf ($this->queries['modifyItem'],
				$value, $name);
		}
		$result = $this->db->Execute($query)
			or die("ModifyAdminConfig: " . $this->db->ErrorMsg() . " " . $query);
	}

	/**
	 * Retrieves the value of the parameter identified by the specified name
	 *
	 * @param string name the name of the parameter
	 * @return string the value of the parameter
	 */
	function getAdminConfig ($name)
	{
		$query  = sprintf ($this->queries['getAdminConfig'],$name);
		$result = $this->db->Execute($query)
			or die("GetAdminConfig: " . $this->db->ErrorMsg() . " " . $query);
		return $result->fields['value'];
	}
}
?>