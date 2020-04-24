<?php

require_once ('framework/util/StringUtils.php');
require_once ('framework/model/Item.php');

/**
 * A password item
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
class Password extends Item
{
	/**
	 * Loginname
	 */
	var $login;

	/**
 	 * The password
	 */
	var $password;

	/**
	 * Url
	 */
	var $url;

	/**
 	 * Full blown constructor
	 *
	 * @param integer theItemId the itemId
	 * @param string theOwner the owner
	 * @param integer theParentId the identifier of the parent
	 * @param boolean parent is this a parent yes or no?
	 * @param string theName the name of this item
	 * @param string theDescription the decription. This is an encrypted
	 * string
	 * @param string theVisibility private or public
	 * @param string theCategory the category
	 * @param boolean deleted is this item marked as deleted?
	 * @param string theCreation when was this item created?
	 * @param string theModified when was this item last modified?
	 * @param string theLogin the login name
	 * @param string theUrl the url
	 */
	function Password ($theItemId, $theOwner, $theParentId, $parent,
		$theName, $theDescription,
		$theVisibility, $theCategory, $deleted,
		$theCreation, $theModified, $theLogin, $thePassword, $theUrl)
	{
		parent :: Item ($theItemId, $theOwner, $theParentId, $parent,
				$theName, $theDescription, $theVisibility,
				$theCategory, $deleted, $theCreation, $theModified);
		$this->login = $theLogin;
		$this->password = $thePassword;
		$this->url = $theUrl;
		$this->type="Password";
	}
}
?>