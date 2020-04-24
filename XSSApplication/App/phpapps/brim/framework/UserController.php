<?php

require_once ('framework/ItemController.php');
require_once ('framework/model/User.php');
require_once ('framework/model/UserFactory.php');
require_once ('framework/model/UserServices.php');

require_class ('RightsManagerImpl',
	'framework/RightsManagerImpl.php'); // added by Michael
require_class ('RequestCast',
	'framework/util/request/RequestCast.class.php'); // added by Michael

/**
 * The User Controller
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - February 2004
 * @package org.brim-project.framework
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class UserController extends ItemController
{
	/**
	 * Constructor.
	 * Makes sure that the appropriate operations are instantiated.
	 */
	function UserController ()
	{
		parent::ItemController ();
		$this->operations = new UserServices ();
		$this->itemFactory = new UserFactory ();
		$this->rightsManager = new RightsManagerImpl();
		$this->pluginName = 'user';
		$this->title = 'Brim - Users';
		$this->itemName = 'User';
	}

	/**
	 * Activate. Basically this means that the appropriate actions are executed
	 * and an optional result is returned to be processed/displayed
	 */
	function activate ()
	{
		switch ($this->getAction ())
		{
			case 'modifyUser':
				if ($this->getUserName () == 'test')
				{
					die ('not allowed for test user');
				}
				$checkPasswd = true;
				$user = $this->itemFactory->requestToUser ($checkPasswd);
				$this->operations->modifyUser ($this->getUserName(), $user);
				Header ("Location: index.php");
				break;
			default:
				Header ("Location: index.php");
				break;
		}
	}

	/**
	 * Returns the filename that will be given to the template for display
	 *
	 * @return string the filename of the template file to show.
	 */
	function getTemplateFile ()
	{
		//
		// Allow the template to override a specific file. If this file exist
		// (the file in the template directory), it will be loaded, otherwise
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
	 * Retrieves the dictionary file by first tryin the language specific
	 * dictionary file, defaulting to the english version if the language
	 * specific file for the item does not exist and returns the contents
	 * as an array
	 *
	 * @return array the dictionary
	 */
	function getDictionary ()
	{
		$dictionary = array ();
		include ('framework/i18n/dictionary_en.php');
		$file = 'framework/i18n/dictionary_'.$_SESSION['brimLanguage'].'.php';
		if (file_exists ($file))
		{
			include ($file);
		}
		return $dictionary;
	}
}
?>
