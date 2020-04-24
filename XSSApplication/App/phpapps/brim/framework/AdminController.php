<?php

require_once ('framework/ItemController.php');
require_once ('framework/model/AdminServices.php');
require_once ('framework/model/UserServices.php');
require_once ('framework/model/UserFactory.php');
require_once ('framework/model/PreferenceFactory.php');
require_once ('framework/model/PreferenceServices.php');

/**
 * This class takes care of administrative preferences like the
 * brim_installation_path, whether users are allowed to anonymousely signup etc.
 *
 * This file is part of the Brim project. The brim-project is located at the
 * following location: {@link http://www.brim-project.org/ http://www.brim-
 * project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - March 2003
 * @package org.brim-project.framework
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class AdminController extends ItemController
{
	/**
	 * Specific administration services
	 * (additional services, the default are the user services)
	 *
	 * @var object the administration services
	 */
	var $adminServices;

	/**
	 * Default constructor, calls the parent constructor, instantiates
	 * userservices, admin services and the userfactory
	 */
	function AdminController ()
	{
		parent::ItemController ();
		$this->operations = new UserServices ();
		$this->itemFactory = new UserFactory ();
		$this->adminServices = new AdminServices ();
		$this->title = 'Brim - Admin';
		$this->pluginName = 'admin';
		$this->itemName = 'Admin';
	}

	/**
	 * Defines the actions that are possible in admin mode.
	 *
	 * @return array an array of arrays containing two name/value pairs
	 * (href and name) for the url of the specific action and its name
	 */
	function getActions ()
	{
		$dictionary = $this->getDictionary ();
		$actions=array(
			array (
				'name'=> 'actions',
				'contents'=> array (
					array(
						'href'=>'AdminController.php?action=addAUser',
						'name'=>'adduser')
				)
			)
		);
		return $actions;
	}

	/**
	 * Ok, we have requested an action. Evaluate it, prepare the necessary
	 * information and forward the appropriate information to the template
	 */
	function activate ()
	{
		$dictionary = $this->getDictionary ();
		switch ($this->getAction ())
		{
			case "deleteUser":
				//
				// Delete a user
				//
				if ($_SESSION['brimUserIsAdmin'] == 'true')
				{
					$loginName = $_GET['loginName'];
					$this->operations->deleteUser
						($_SESSION['brimUsername'], $loginName);
					//
					// todo all other tables must be cleared as well.
					//
					Header ("Location: AdminController.php");
				}
				else
				{
					//
					// todo make sure that a user can delete its account
					//
					die ("Only the administrator can delete users.");
				}
				break;
			case 'addAUser':
				//
				// Display the adduser form
				//
				$this->renderer = 'addUser';
				break;
			case 'addUser':
				//
				// Add a user. The credentials are provided via the form
				//
				$preferenceFactory = new PreferenceFactory ();
				$preferenceOperations = new PreferenceServices ();


				include "framework/configuration/realmConfiguration.php";
				$checkPasswd = true;
				if ($realm == "ldap")
				{
					//
					// password is not provided via the form
					//
					$checkPasswd = false;
				}
				$usersettings=$this->itemFactory->requestToUser ($checkPasswd);
				if ($checkPasswd)
				{
					//
					// Check for password
					//
					if ($usersettings->password == null)
					{
						die ("You must provide at least a password");
					}
				}
				//
				// Add the user
				//
				$this->operations->addUser
					($_SESSION['brimUsername'], $usersettings);
				//
				// Get and add the language preferences
				//
				$userPrefs = new Preference
					(0, $usersettings->loginName, 0, 0, "brimLanguage",
					null, 'private', null, 0, null, null, $_POST['language']);
				$preferenceOperations->addItem
					($usersettings->loginName, $userPrefs);
				//
				// Get and add the template preferences
				//
				$userPrefs = new Preference
					(0, $usersettings->loginName, 0, 0, "brimTemplate",
					null, 'private', null, 0, null, null, $_POST['template']);
				$preferenceOperations->addItem
					($usersettings->loginName, $userPrefs);
				//
				// Back to the admin page
				//
				Header ("Location: AdminController.php");
				break;
			case 'modify':
				//
				// Retrieve a users credentials and forward these to a
				// form so the user can modify them
				//
				$userSettings =
					$this->operations->getUserForLoginName
						($_GET['loginName']);
				$preferenceOperations = new PreferenceServices ();
				$this->renderObjects =
					$preferenceOperations->getPreferencesAsArray
						($userSettings->loginName);
				$this->renderEngine->assign('userSettings', $userSettings);
				$this->renderer = 'modifyPreferences';
				break;
			case 'modifyUser':
				//
				// New credentials are provided via a form, update them
				//
				if ($_SESSION['brimUsername'] == 'test')
				{
					die ("Not allowed for test user");
				}
				$checkPasswd = true;
				$userSettings = $this->itemFactory->requestToUser ($checkPasswd);
				$this->operations->modifyUser
					($_SESSION['brimUsername'], $userSettings);
				Header("Location: AdminController.php");
				break;
			case 'modifyAdminConfigPost':
				//
				// Modify the new settings which are provided via a form
				//
				$this->adminServices->modifyItem
					($_POST['name'],$_POST['value']);
				//
				// No break
				//
			case 'modifyAdminConfigPre':
				//
				// Retrieve the current settings and provide the admin
				// user with a form so they can be modified
				//
				$this->renderEngine->assign ('admin_email',
					$this->adminServices->getAdminConfig ('admin_email'));
				$this->renderEngine->assign ('allow_account_creation',
					$this->adminServices->getAdminConfig
						('allow_account_creation'));
				$this->renderEngine->assign ('installation_path',
					$this->adminServices->getAdminConfig
						('installation_path'));
				$this->renderEngine->assign ('calendarEmailReminder',
					$this->adminServices->getAdminConfig
						('calendarEmailReminder'));
				$this->renderEngine->assign ('calendarParticipation',
					$this->adminServices->getAdminConfig
						('calendarParticipation'));
				$this->renderer = 'modifyAdminConfig';
				break;
			default:
				//
				// Present the admin user with a list of all users
				//
				$this->renderEngine->assign ('pluginName', $this->pluginName);
				$this->renderer = 'administration';
				$this->renderObjects= $this->operations->getAllUsers
					($_SESSION['brimUsername']);
		}
	}

	/**
	 * Display which basically means that the template will be invoked
	 */
	function display ()
	{
		$languages = array ();
		$templates = array ();
		include ('framework/configuration/languages.php');
		include ('framework/configuration/templates.php');
		global $menuItems, $menu;

		$this->renderEngine->assign('pluginName', $this->getTitle ());
		$this->renderEngine->assign('title', $this->getTitle ());
		$this->renderEngine->assign('menuItems', $menuItems);
		$this->renderEngine->assign('menu', $menu);
		$this->renderEngine->assign('dictionary', $this->getDictionary ());

		$this->renderEngine->assign('itemId', $this->getItemId ());
		$this->renderEngine->assign('parentId', $this->getParentId ());

		$this->renderEngine->assign('parameters', $this->getParameters ());
		$this->renderEngine->assign('action', $this->getAction ());
		$this->renderEngine->assign('renderObjects', $this->getRenderObjects ());
		$this->renderEngine->assign('renderActions', $this->getActions ());
		$this->renderEngine->assign ('renderer', $this->getTemplateFile ());

		$this->renderEngine->assign ('languages', $languages);
		$this->renderEngine->assign ('templates', $templates);
		if (isset ($_GET['debug']))
		{
			//
			// todo add more
			//
			error_reporting(E_ALL);
			$this->renderEngine->display
				('templates/'.$this->getTemplate().'/template.tpl.php');
		}
		else
		{
			error_reporting(E_ERROR);
			@$this->renderEngine->display
				('templates/'.$this->getTemplate().'/template.tpl.php');
		}
	}

	/**
	 * Returns the filename that will be given to the template for
	 * display
	 * @return string the filename of the template file to show.
	 */
	function getTemplateFile ()
	{
		//
		// Allow the template to override a specific file.
		// If this file exist (the file in the template directory),
		// it will be loaded, otherwise
		// the default (in the plugin directory) will be loaded
		//
		$renderer = 'templates/'.$this->getTemplate ().'/'.$this->getRenderer ();
		if (!(file_exists ($renderer)))
		{
			$renderer = 'framework/view/'.$this->getRenderer ();
		}
		return $renderer;
	}

	/**
	 * Retrieves the dictionary file by first tryin the language
	 * specific dictionary file, defaulting to the english version if
	 * the language specific file for the item does not exist and
	 * returns the contents as an array
	 *
	 * @return array the dictionary
	 */
	function getDictionary ()
	{
		$dictionary = array ();
		include ('framework/i18n/dictionary_en.php');
		$file = 'framework/i18n/dictionary_'.$_SESSION['brimLanguage'] . '.php';
		if (file_exists ($file))
		{
			include ($file);
		}
		return $dictionary;
	}
}
?>
