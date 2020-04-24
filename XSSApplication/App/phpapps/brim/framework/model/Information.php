<?php

require_once ('framework/model/Item.php');

/**
 * The Information item
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
class Information extends Item
{
	/**
	 * The identifier of the referring item
	 * @var integer
	 */
	var $referingId;
	
	/**
	 * The referring type
	 * @var string
	 */
	var $referingType;
	
	/**
	 * How reliable is this information?
	 * @var string
	 */
	var $reliability;
	
	/**
	 * Is this information complete?
	 * @var boolean
	 */
	var $complete;
	
	/**
	 * Is there an external URL that points to information?
	 * @var string
	 */
	var $informationURL;
	
	/**
	 * Is there an external URL that points to an image for this informations?
	 * @var string
	 */
	var $imageURL;
	
	/**
	 * Additional comments
	 * @var string
	 */
	var $comments;
	
	/**
	 * Constructor
	 */
	function Information  ($theID, $theOwner, $theParentId, $parent,
	                $theName, $theDescription, $theVisibility, $theCategory,
			$isDeleted, $theCreation, $theModified,
			$theReferingId, $theReferingType,
			$theReliability, $isComplete,
			$theInformationURL, $theImageURL, $theComments
	)
	{
		parent :: Item ($theID, $theOwner, $theParentId, $parent,
			$theName, $theDescription, $theVisibility,
			$theCategory, $isDeleted, $theCreation, $theModified);
		$this->referingId = $theReferingId;
		$this->referingType = $theReferingType;
		$this->reliability = $theReliability;
		$this->complete = $isComplete;
		$this->informationURL = $theInformationURL;
		$this->imageURL = $theImageURL;
		$this->comments = $theComments;
	}

	/**
	 * Is this information valid?
	 * @todo provide implementation
	 */
	function isValid ()
	{
		return true;
	}
}
?>