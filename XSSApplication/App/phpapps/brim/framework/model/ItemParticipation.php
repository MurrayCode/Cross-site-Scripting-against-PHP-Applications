<?php

/**
 * ItemParticipation 
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - May 2006
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
class ItemParticipation
{
	var $itemId;
	var $owner;
	var $participator;
	var $plugin;
	
	/**
	 * Unused at the moment
	 *
	 * @var string
	 */
	var $participationRights;
	
	/**
	 * The activation code. When filled in, the participant
	 * has been invited, but has not accepted yet.
	 * If empty, the invitation is accepted
	 *
	 * @var string
	 */
	var $activationCode;
	
	function ItemParticipation($theItemId, $theOwner, 
		$theParticipator, $thePlugin, $theRights, $theActicationCode)
	{
		$this->itemId = $theItemId;
		$this->owner = $theOwner;
		$this->participator = $theParticipator;
		$this->plugin = $thePlugin;
		$this->participationRights = $theRights;		
		$this->activationCode = $theActicationCode;
	}
}
?>