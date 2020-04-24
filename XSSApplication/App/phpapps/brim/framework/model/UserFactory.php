<?php

/**
 * UserFactory
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.framework
 * @subpackage model
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class UserFactory
{
	/**
	 * Factory method: Returns a database result into an item
	 *
	 * @param object result the result retrieved from the database
	 * @return array the items constructed from the database resultset
	 */
	function resultsetToUsers ($result)
	{
		$users = array ();
		while (!$result->EOF)
		{
			$aUser = new User (
				$result -> fields ['user_id'],
				trim ($result -> fields ['loginname']),
				trim ($result -> fields ['password']),
				trim ($result -> fields ['name']),
				trim ($result -> fields ['email']),
				trim ($result -> fields ['description']),
				$result -> fields ['when_created'],
				$result -> fields ['last_login']
			);
			$users [] = $aUser;
			$result->MoveNext ();
		}
		return $users;
	}

	/**
	 * Factory method. Return an HTTP request into a user by fecthing
	 * the appropriate parameters from the POST request
	 *
	 * @return object the user constructed from the POST request
	 */
	function requestToUser ($checkPasswd)
	{
		$passwd = "*";
		if ($checkPasswd)
		{
			if ($_POST['password'] != $_POST['password2'])
			{
				die ("Password mismatch");
			}
			$pwd1 = $_POST['password'];
			$pwd2 = $_POST['password2'];

			if ($pwd1 == null || $pwd2 == null || ($pwd1 == "" && $pwd2 == ""))
			{
				die ("Empty password not allowed");
			}
			$passwd = $_POST['password'];
		}
 
		if (isset ($_POST['userId']))
		{
			$userId = $_POST['userId'];
		}
		else
		{
			$userId = null;
		}
		if (isset ($_POST['when_created']))
		{
			$when_created=$_POST['when_created'];
		}
		else
		{
			$when_created=null;
		}
		if (isset ($_POST['lastLogin']))
		{
			$lastLogin=$_POST['lastLogin'];
		}
		else
		{
			$when_created=null;
			$lastLogin=null;
		}
		$userSettings = new User
	    	(
	        	$userId,
	        	$_POST['loginName'],
	        	$passwd,
	        	$_POST['name'],
	        	$_POST['email'],
	        	$_POST['description'],
			$when_created,
			$lastLogin
	    	);
		return $userSettings;
	}
}
?>
