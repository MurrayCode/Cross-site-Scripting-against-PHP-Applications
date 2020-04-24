<?php

require_once ('framework/realm/RealmFactory.php');

/**
 * This file is part of the Brim project.
 *
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Joerg Zissel - July 2006
 * @package org.brim-project.framework
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class Realm
{
	/**
	 * The single instance
	 * 
	 * @var Realm instance
	 */
	var $instance = null;

	/**
	 * Constructor. Handles a single instance
	 * of a Realm.
	 */
	function Realm()
	{
	}

        /**
         * Returns the Realm instance.
         *
         * @return Realm the realm instance
         */
	function getInstance()
	{
                if ($this->instance == null)
                {
                        $this->instance = RealmFactory::createRealm();
                }
		return $this->instance;
	}


	/**
	 * Abstract method
	 * Authenticates a user by user ID and password.
	 *
	 * @param string user ID
	 * @param string password
	 * @return boolean <code>true</code> if user is authenticated, <code>false</code> otherwise
	 */
	function authenticate ($userid,$password)
	{}

        /**
         * Abstract method
         * Sets new password for a user.
         *
         * @param string user ID
         * @param string new password
	 */
	function setPassword ($userid,$newPassword)
	{}

        /**
         * Abstract method
         * Checks whether the given user is member of the given role.
         *
         * @param string user ID
	 * @param string role name
         * @return boolean <code>true</code> if the user is member of the role,
	 * <code>false</code> otherwise
         */
	function isMemberOf ($userid,$role)
	{}
}

?>
