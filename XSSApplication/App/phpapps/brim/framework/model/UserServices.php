<?php

require_once ('framework/model/User.php');
require_once ('framework/model/UserFactory.php');
require_once ('framework/model/PluginServices.php');
require_once ('framework/model/PluginSettingFactory.php');
require_once ('framework/realm/Realm.php');

/**
 * Operations class for users
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
class UserServices
{
	/**
	 * The database connection
	 * @var object db the database connection
	 */
	var $db;

	/**
	 * The queries that are used for database interactions
	 * @var array queries
	 */
	var $queries;

	/**
	 * The factory for user objects
	 * @private
	 * @var UserFactory factory
	 */
	var $factory;

	/**
	 * The plugin services. Used to enable plugins when a user is
	 * created and delete plugin items from the database when
	 * a user is deleted
	 *
	 * @var PluginServices pluginServices
	 */
	var $pluginServices;

	/**
	 * The pluginSetting factory, user for user creation
	 *
	 * @var PluginSettingFactory pluginFactory
	 */
	var $pluginFactory;

	/**
	 * Default constructor
	 */
	function UserServices ()
	{
		$db = null;
		$queries = array ();
		include ('framework/sql/userQueries.php');
		include ('framework/util/databaseConnection.php');
		$this->db = $db;
		$this->queries = $queries;
		$this->factory = new UserFactory ();
		$this->pluginServices = new PluginServices ();
		$this->pluginFactory = new PluginSettingFactory ();
	}

	/**
	 * Adds a user to the system
	 *
	 * @param string userId the identifier fr the user who issues
	 * the request (should be admin, I guess???)
	 *
	 * @param object aUser the user to be added
	 * @return integer the database last insert id
	 */
	function addUser ($userId, $aUser)
	{
		$now = date ("Y-m-d H:i:s");
		//
		// First check whether the user which we want to add already
		// exists  (check basd  on login name)
		//
		$query = sprintf ($this->queries['getUser'],
			$aUser->loginName);
		$result = $this->db->Execute ($query)
			or die ("Add user: " . $this->db->ErrorMsg () . " " . $query);
		$existingUser = $this->factory->resultSetToUsers ($result);
		if ($existingUser != null)
		{
			die ("User already exists!");
		}
		//
		// If the user does not yet exist, add it
		//
		$query = sprintf ($this->queries['addUser'],
			$aUser->loginName,
			'', // the password will be set later
			$aUser->name,
			$aUser->email,
			$aUser->description,
			null);
		$result = $this->db->Execute ($query)
			or die ("Add user: " . $this->db->ErrorMsg () . " " . $query);
		//
		// And get the last insert id (which will be returned)
		//
		$query = $this->queries['lastUserInsertId'];
		$result = $this->db->Execute ($query)
			or die ("Add user: " . $this->db->ErrorMsg () . " " . $query);
                //
                // Set the password
                //
                $realm = Realm::getInstance();
                $realm->setPassword($aUser->loginName,$aUser->password);

		$lastInsertId = $result->fields[0];
		//
		// Get all plugins and enable them all for the new user
		//
		$plugins = $this->pluginServices->getPlugins ();
		foreach ($plugins as $plugin)
		{
			//
			// Create a plugin setting based on the global plugin
			// and the current user and add it to the system
			//
			$current = $this->pluginFactory->getPluginSetting
				($aUser->loginName, $plugin['name'], 'true');
			$this->pluginServices->addItem
				($aUser->loginName, $current);
		}
		//
		// The user last insert id
		//
		return $lastInsertId;
	}

	/**
	 * Adds a user to the system
	 *
	 * @param string userId the identifier fr the user who issues
	 * the request (should be admin, I guess???)
	 *
	 * @param object aUser the user to be added
	 * @return integer the database last insert id
	 */
	function addTempUser ($userId, $aUser, $tempPassword)
	{
		$now = date ("Y-m-d H:i:s");
		//
		// First check whether the user which we want to add already
		// exists  (check basd  on login name)
		//
		$query = sprintf ($this->queries['getUser'], $aUser->loginName);
		$result = $this->db->Execute ($query)
			or die ("Add user: " . $this->db->ErrorMsg () . " " . $query);
		$existingUser = $this->factory->resultSetToUsers ($result);
		if ($existingUser != null)
		{
			die ("User already exists!");
		}
		$query = sprintf ($this->queries['getTempUser'], $aUser->loginName);
		$result = $this->db->Execute ($query)
			or die ("Add temp user: " . $this->db->ErrorMsg () . " " . $query);
		$existingUser = $this->factory->resultSetToUsers ($result);
		if ($existingUser != null)
		{
			die ("Temp User already exists!");
		}
		//
		// If the user does not yet exist, add it
		//
		$query = sprintf ($this->queries['addTempUser'],
			$aUser->loginName,
			MD5($aUser->password), //'', // the password will be set later 
			// ??
			$aUser->name,
			$aUser->email,
			$aUser->description,
			$tempPassword);
		$result = $this->db->Execute ($query)
			or die ("Add temp user: " . $this->db->ErrorMsg () . " " . $query);
                //
                // Set the password
                //
                /*
                 * This doesn't work for tempusers????'
                $realm = Realm::getInstance();
                $realm->setPassword($aUser->loginName,$aUser->password);
                */
	}

	/**
 	 * Modifies a users parameters
	 *
	 * @param string userId the requestor
	 * @param object theUser the modified user
	 */
	function modifyUser ($userId, $theUser)
	{
		if ($this->getUserName() != $userId)
		{
			if ($_SESSION["brimUserIsAdmin"] != 'true')
			{
				return null;
			}
		}
		if (!isset ($theUser->lastLogin) || $theUser->lastLogin == '')
		{
			//
			// To have a valid date, lets pretend today
			//
			$theUser->lastLogin = date ("Y-m-d H:i:s");
		}
		$query = sprintf ($this->queries['modifyUser'],
			$theUser->name,
			'', // the password will be set later
			$theUser->description,
			$theUser->email,
			$theUser->lastLogin,
			$theUser->loginName);
		$this->db->Execute ($query)
			or die ("Error updating user: " .
				$this->db->ErrorMsg () . " " . $query);
		//
		// Set the password
		//
                $realm = Realm::getInstance();
                $realm->setPassword($theUser->loginName,$theUser->password);
	}

	/**
	 * Deletes a user from the system
	 *
	 * @param integer userId the identifier fr the user who issues
	 * the request (should be admin, I guess???)
	 * @param string loginName the id of user to be deleted
	 */
	function deleteUser ($userId, $loginName)
	{
		$query = sprintf ($this->queries['deleteUser'], $loginName);
		$this->db->Execute ($query)
			or die ("Error deleteing  user: " .
				$this->db->ErrorMsg () . " " . $query);
		//
		// Get all plugins and remove all items for this user
		// per plugin
		//
		$plugins = $this->pluginServices->getPlugins ();
		foreach ($plugins as $plugin)
		{
			if (isset ($plugin['serviceLocation']))
			{
				include ($plugin['serviceLocation']);
				$currentPluginServices = new $plugin['serviceName']();
				$currentPluginServices->deleteAllItemsForUser
					($loginName);
			}
		}
		require_once ('framework/model/PreferenceServices.php');
		require_once ('framework/model/PluginServices.php');
		$preferenceServices = new PreferenceServices ();
		$pluginServices = new PluginServices ();
		$preferenceServices->deleteAllPreferences ($loginName);
		$pluginServices->deleteAllPluginSettings ($loginName);
	}

	/**
	 * Retrieves all users
	 *
	 * @param integer userId the identifier of the user who issues
	 * the request
	 * @return array all users known to the system. If the requesting
	 * user is not the admin user, several fields will not be returned.
	 */
	function getAllUsers ($userId)
	{
		if ($_SESSION['brimUserIsAdmin'] != 'true')
		{
			return;
		}
		$query  = $this->queries['getAllUsers'];
		$result = $this->db->Execute ($query)
			or die ($this->db->ErrorMsg (). "GetAllUsers. " . $query);
		$users = $this->factory->resultsetToUsers ($result);
		if ($userId != 'admin')
		{
			for ($i=0; $i<count($users); $i++)
			{
				$user = $users[$i];
				unset ($user->password);
				unset ($user->when_created);
				unset ($user->lastLogin);
				$users[$i]=$user;
			}
		}
		return $users;
	}

	/**
	 * Retrieve all loginnames
	 * 
	 * @return array all usernames known to the system. 
	 */
	function getAllLoginNames ()
	{
		$query = $this->queries['getAllLoginNames'];
		$result = $this->db->execute ($query) or die 
			('GetAllLoginNames'.$db->ErrorMsg () .'->'.$query);
		$names = array ();
		while (!$result->EOF)
		{
			$names [] = $result->fields['loginname'];
			$result->MoveNext ();
		}
		return $names;
	}
	
	/**
	 * Gets a user
	 *
	 * @param integer userId the identifier for the user who issues
	 * the request
	 * @param string requestedUser the identification of the user for
	 * which we would like to know the parameters
	 *
	 * @return the requested user parameters
	 */
	function getUser ($userId, $requestedUser)
	{
		$query = sprintf ($this->queries ['getUser'], $requestedUser);
		$result = $this->db->Execute ($query)
			or die ('Error retrieving userinformation' .
				$this->db->ErrorMsg() . " " . $query);
		$users = $this->factory->resultsetToUsers ($result);
		return $users[0];
	}

	/**
 	 * Retrieves a user based on its Id
	 *
	 * @param string loginName the loginName for the user
	 * @return object a fully instantiated user object
	 */
	function getUserForLoginName ($loginName)
	{
		$query = sprintf ($this->queries ['getUserForLoginName'],
			$loginName);
		$result = $this->db->Execute ($query)
			or die ('Error retrieving userinformation' .
				$this->db->ErrorMsg() . " " . $query);
		$users = $this->factory->resultsetToUsers ($result);
		return $users[0];
	}

	/**
	 * Returns the current username
	 *
	 * @return string username
	 */
	function getUserName ()
	{
		return $_SESSION['brimUsername'];
	}

	/**
	 * Activate a temporary account, based on the provided password
	 * @return <code>true</code> if the account was succesfully activated,
	 * <code>false</code> otherwise
	 */
	function activateAccount ($tempPassword)
	{
		$query = sprintf ($this->queries['getTempUserByTempPassword'], $tempPassword);
		$result = $this->db->Execute ($query)
			or die ("Add temp user: " . $this->db->ErrorMsg () . " " . $query);
		$tempUsers = $this->factory->resultSetToUsers ($result);
		if (!isset ($tempUsers) || $tempUsers == null)
		{
			return false;
		}
		if (is_array ($tempUsers))
		{
			$tempUser = $tempUsers[0];
		}
		else
		{
			return false;
		}
		$now = date ("Y-m-d H:i:s");
		$query = sprintf ($this->queries['addUser'],
			$tempUser->loginName,
			$tempUser->password,
			$tempUser->name,
			$tempUser->email,
			$tempUser->description,
			$now,
			null);
		$result = $this->db->Execute ($query)
			or die ("Activate account: " . $this->db->ErrorMsg () . " " . $query);
		$query = sprintf ($this->queries['deleteTempUserByTempPassword'], $tempPassword);
		$result = $this->db->Execute ($query)
			or die ("Activate account: " . $this->db->ErrorMsg () . " " . $query);
		$plugins = $this->pluginServices->getPlugins ();
		foreach ($plugins as $plugin)
		{
			//
			// Create a plugin setting based on the global plugin
			// and the current user and add it to the system
			//
			$current = $this->pluginFactory->getPluginSetting
				($tempUser->loginName, $plugin['name'], 'true');
			$this->pluginServices->addItem
				($aUser->loginName, $current);
		}
		return true;
	}
	
	function getUsersForNames ($names)
	{
		$users = array ();
		foreach ($names as $name)
		{
			$user = $this->getUserForLoginName($name);
			$users [] = $user;
		}
		return $users;
	}
}
?>
