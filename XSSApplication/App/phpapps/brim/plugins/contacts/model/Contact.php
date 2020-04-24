<?php

require_once ('framework/model/Item.php');

/**
 * A contact item
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.contacts
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class Contact extends Item
{
	/**
	 * The alias for the contact
	 * @access private
	 * @var string
	 */
	var $alias;

	/**
	 * The address of the contact
	 * @access private
	 * @var string
	 */
	var $address;

	/**
	 * The birthday of the contact (not used yet)
	 * @access private
	 * @var string
	 */
	var $birthday;

	/**
	 * The mobile telephone number (GSM) of the contact
	 * @access private
	 * @var string
	 */
	var $mobile;

	/**
	 * The fax number of the contact
	 * @access private
	 * @var string
	 */
	var $faximile;

	/**
	 * Telephone number at work of the contact
	 * @access private
	 * @var string
	 */
	var $tel_work;

	/**
	 * Telephone number at home of the contact
	 * @access private
	 * @var string
	 */
	var $tel_home;

	/**
	 * The organization for which the contacts works
	 * @access private
	 * @var string
	 */
	var $organization;

	/**
	 * The address of the organization for which the contact works
	 * @access private
	 * @var string
	 */
	var $org_address;

	/**
	 * The contacts jobtitle
	 * @access private
	 * @var string
	 */
	var $job;

	/**
	 * The first email address of the contact
	 * @access private
	 * @var string
	 */
	var $email1;

	/**
	 * The second email address of the contact
	 * @access private
	 * @var string
	 */
	var $email2;

	/**
	 * The third email address of the contact
	 * @access private
	 * @var string
	 */
	var $email3;

	/**
	 * The first webaddress of the contact
	 * @access private
	 * @var string
	 */
	var $webaddress1;

	/**
	 * The 2nd webaddress of the contact
	 * @access private
	 * @var string
	 */
	var $webaddress2;

	/**
	 * The 3rd webaddress of the contact
	 * @access private
	 * @var string
	 */
	var $webaddress3;

	/**
	 * Full blown constructor with all parameters
	 *
	 * @param integer theItemId the id of the item
	 * @param string theOwner who owns this item?
	 * @param integer theParentId what is the id of the parent of this item?
	 * @param boolean parent is this a parent (true) or child (false)
	 * @param string theName the name of this item
	 * @param string theDescription the description of this item
	 * @param string theVisibility the visibility (private or public)
	 * @param string theCategory what is the category of this item?
	 * @param boolean deleted is this item deleted?
	 * @param string created When was this item created?
	 * @param string modified When was this item modified?
	 * @param string theAlias the alias of this contact
	 * @param string theAddress The address of the contact
	 * @param string theBirthday The birthday of the contact
	 * @param string theMobile mobile telephone number (GSM) of the contact
	 * @param string theFax the fax number of the contact
	 * @param string theTelHome Telephone number at home of the contact
	 * @param string theTelWork Telephone number at work of the contact
	 * @param string theOrganization organization for which the contacts works
	 * @param string theOrganizationalAddress address of the organization for
	 *			which the contact works
	 * @param string theJobTitle The contacts jobtitle
	 * @param string theEmail1 first email address of the contact
	 * @param string theEmail2 second email address of the contact
	 * @param string theEmail3 third email address of the contact
	 * @param string theWebAddress1 first webaddress
	 * @param string theWebAddress2 second webaddress
	 * @param string theWebAddress3 third webaddress
	 */
	function Contact ($theItemId, $theOwner, $theParentId,
		$parent, $theName, $theDescription, $theVisibility,
		$theCategory, $deleted, $theCreation, $theModified,
		$theAlias,
		$theAddress, $theBirthday, $theMobile, $theFax,
		$theTelHome, $theTelWork,
		$theOrganization, $theOrganizationalAddress,
		$theJobTitle,
		$theEmail1, $theEmail2, $theEmail3,
		$theWebaddress1, $theWebaddress2, $theWebaddress3)
	{
		parent :: Item ($theItemId, $theOwner, $theParentId, $parent,
				$theName, $theDescription, $theVisibility,
				$theCategory, $deleted, $theCreation, $theModified);

		$this->type="Contact";
		$this->job=$theJobTitle;
		$this->tel_home=$theTelHome;
		$this->tel_work=$theTelWork;
		$this->mobile=$theMobile;
		$this->faximile=$theFax;
		$this->address=$theAddress;
		$this->organization=$theOrganization;
		$this->org_address=$theOrganizationalAddress;
		$this->birthday=$theBirthday;
		$this->alias=$theAlias;
		$this->email1=$theEmail1;
		$this->email2=$theEmail2;
		$this->email3=$theEmail3;
		$this->webaddress1=$theWebaddress1;
		$this->webaddress2=$theWebaddress2;
		$this->webaddress3=$theWebaddress3;
	}


	function equals ($thisOne, $other)
	{
		if ($thisOne->name == $other->name 
			&& $thisOne->isParent == $other->isParent)
		{
			return 0;
		}
		return($thisOne->name < $other->name) ? -1 : 1;
	} 
}
?>
