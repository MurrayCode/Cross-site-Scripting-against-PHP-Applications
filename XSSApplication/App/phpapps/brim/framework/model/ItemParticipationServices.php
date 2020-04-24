<?php

require_once ('framework/model/ItemParticipation.php');
require_once ('framework/model/ItemParticipationFactory.php');
require_once ('framework/model/Services.php');
require_once ('framework/model/UserServices.php');
/**
 * Operations on item participation
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - 29 May 2006
 * @package org.brim-project.framework
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class ItemParticipationServices extends Services
{
	var $logger;
	var $userServices;
	/**
 	 * Default constructor
	 */
	function ItemParticipationServices ()
	{
		parent::Services();
		$this->itemFactory = new ItemParticipationFactory ();
		$queries = array ();
		include ('framework/sql/itemParticipationQueries.php');
		$this->queries = $queries;
		$this->userServices = new UserServices ();
	}
	
	function getItemParticipation ($itemId, $owner, $participator, $plugin)
	{
		$query = sprintf ($this->queries['getItemParticipation'],
			$itemId, $owner, $participator, $plugin);
		$result = $this->db->Execute ($query) or die
			($this->db-ErrorMsg().'-'.$query);
		return $this->itemFactory->resultsetToItemParticipation ($result);	
	}

	function deleteItemParticipation ($itemId, $owner, $participator, $plugin)
	{
		$query = sprintf ($this->queries['deleteItemParticipation'],
			$itemId, $owner, $participator, $plugin);
		$result = $this->db->Execute ($query) or die
			($this->db->ErrorMsg().'-'.$query);
	}
	
	function addItemParticipation ($itemId, $owner, $participator, $plugin)
	{
		$query = sprintf ($this->queries['addItemParticipation'],
			$itemId, $owner, $participator, $plugin);
		$result = $this->db->Execute ($query) or die
			($this->db->ErrorMsg().'-'.$query);
	}
	
	function getParticipantsStatus ($itemId, $plugin)
	{
		$participatorNames = $this->getParticipatorNames ($itemId, $plugin);
		//die (print_r ($participatorNames));
		$tempParticipatorNames = $this->getTempParticipatorNames ($itemId, $plugin);
		$allUsers = $this->userServices->getAllLoginNames ($_SESSION['brimUsername']);
		//
		// Remove the current username from the list, if we own this item
		//
		if ($this->getItemOwner ($itemId, $plugin) == $_SESSION['brimUsername'])
		{
			$allUsers = array_diff ($allUsers, array ($_SESSION['brimUsername']));
		}
		//
		// Now remove the participator names from the allUsers list
		//
		$allUsers = array_diff ($allUsers, $participatorNames);
		//
		// And the same for the temp users
		//
		$nonParticipatorNames = array_diff ($allUsers, $tempParticipatorNames);
		$participators = array ();
		$tempParticipators = array ();
		$nonParticipators = array ();
		foreach ($participatorNames as $name)
		{
			$participators [] = 
				$this->userServices->getUserForLoginName ($name);
		}
		foreach ($tempParticipatorNames as $name)
		{
			$tempParticipators [] = 
				$this->userServices->getUserForLoginName ($name);
		}
		foreach ($nonParticipatorNames as $name)
		{
			$nonParticipators [] = 
				$this->userServices->getUserForLoginName ($name);
		}
		$result = array ();
		$result ['participators'] = $participators;
		$result ['tempParticipators'] = $tempParticipators;
		$result ['nonParticipators'] = $nonParticipators;
		
		return $result;
	}
	
	function getParticipatorNames ($itemId, $plugin)
	{
		$query = sprintf ($this->queries['getParticipators'],
			$itemId, $plugin);
		$result = $this->db->Execute ($query)
			or die ($this->db->ErrorMsg ().'-'.$query);	
		return $this->itemFactory->getNamesFromParticipationList ($result);
	}

	function getTempParticipatorNames ($itemId, $plugin)
	{
		$query = sprintf ($this->queries['getTempParticipators'],
			$itemId, $plugin);
		$result = $this->db->Execute ($query)
			or die ($this->db->ErrorMsg ().'-'.$query);			
		return $this->itemFactory->getNamesFromParticipationList ($result);
	}
	
	function getItemOwner ($itemId, $plugin)
	{
		$query = sprintf ($this->queries['getItemOwner'],
			$itemId, $plugin);
		$result = $this->db->Execute ($query)
			or die ($this->db->ErrorMsg ().'-'.$query);	
		return $result->fields['owner'];	
	}
}
?>