<?php

/**
 * A user
 *
 * Considering a users' parameters, the following should be taken
 * into account:
 * <ul>
 * <li>userId is the identifier (autoincrement integer) in the
 * 	database</li>
 * <li>user will be used for the username (to avoid
 * 	conflicts/confusion with the session parameter username</li>
 * <li>name will be used for the real name of the user</li>
 * </ul>
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - March 2003
 * @package org.brim-project.framework
 * @subpackage model
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class User
{
	/**
	 * The identifier for the user
	 *
	 * @access private
	 * @var integer
	 */
	var $userId;

	/**
	 * The email address of the user
	 *
	 * @access private
	 * @var string
	 */
	var $email;

	/**
	 * The password of the user (MD5)
	 *
	 * @access private
	 * @var string
	 */
	var $password;

	/**
	 * The username (aka loginname)
	 *
	 * @access private
	 * @var string
	 */
	var $loginName;

	/**
	 * The description for this user
	 *
	 * @access private
	 * @var string
	 */
	var $description;

	/**
	 * The user's name
	 *
	 * @access private
	 * @var string
	 */
	var $name;

	/**
	 * When is this user created?
	 *
	 * @access private
	 * @var string
	 */
	var $when_created;

	/**
	 * What was the last time this user logged in?
	 *
	 * @var string
	 */
	var $lastLogin;

	/**
 	 * Full blown constructor with all parameters
 	 *
 	 * @param string theUserId the user's identifier
 	 * @param string theUserName the username
 	 * @param string thePassword the password
 	 * @param string theName the name of the user
 	 * @param string theEmail the users email address
 	 * @param string theDescription a description
 	 * @param string theCreation when this user was created
 	 * @param string theLastLogin when this user logged in for the last time
 	 */
	function User ($theUserId,
		$theUsername, $thePassword, $theName, $theEmail,
		$theDescription, $theCreation, $theLastLogin)
	{
		$this->userId = $theUserId;
		$this->loginName = $theUsername;
		$this->password = $thePassword;
		$this->name = $theName;
		$this->email = $theEmail;
		$this->description = $theDescription;
		$this->when_created=$theCreation;
		$this->lastLogin = $theLastLogin;
	}

	/**
	 * Presents a human readable presentation of this class
	 * @return  a human readable presentation of this class
	 */
	function toString ()
	{
		$result  = "[User]: ";
		$result .= "UserId: " . $this->userId . " ";
		$result .= "Username: " . $this->username . " ";
		$result .= "Name: " . $this->name . " ";
		$result .= "Description: " . $this->description . " ";
		$result .= "Email: " . $this->email;
		return $result;
	}

	/**
	 * Checks whether the constructed item is a valid item (has all the
	 * required fields)
	 *
	 * @return boolean <code>true</code> if the item is valid,
	 * 		<code>false</code> otherwise
	 */
	function isValid ()
	{
		return (isset ($this->userId) && isset ($this->password)
			&& isset ($this->email));
	}
}
?>