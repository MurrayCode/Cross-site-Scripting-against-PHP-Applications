<?php

require_once ('framework/ItemController.php');
require_once ('framework/model/Preference.php');
require_once ('framework/model/PreferenceFactory.php');
require_once ('framework/model/PreferenceServices.php');

require_once ('framework/model/UserServices.php');
require_once ('framework/model/User.php');

/**
 * The Preference Controller, handles all user interaction w.r.t. preferences
 * (i.e. allows a user to set preferences for items etc)
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - January 2004
 * @package org.brim-project.framework
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class PreferenceController extends ItemController
{
	/**
	 * Constructor.
	 * Makes sure that the appropriate operations are instantiated.
	 */
	function PreferenceController ()
	{
		parent::ItemController ();
		$this->operations = new PreferenceServices ();
		$this->itemFactory = new PreferenceFactory ();
		$this->pluginName = 'user';
		$this->title = 'Brim - Preferences';
		$this->itemName = 'Preferences';
	}

	/**
	 * Activate. Basically this means that the appropriate actions are
	 * executed  and an optional result is returned to be processed/displayed
	 */
	function activate ()
	{
		$languages = array ();
		$templates = array ();
		include ('framework/configuration/languages.php');
		include ('framework/configuration/templates.php');
		$this->renderEngine->assign ('languages', $languages);
		$this->renderEngine->assign ('templates', $templates);

		switch ($this->getAction ())
		{
			case 'modifyPreferences':
				$preference = $this->itemFactory->requestToItem ();
				$preference->itemId = $this->operations->getPreferenceId
						($preference->owner, $preference->name);
				if (isset ($preference->itemId))
				{
					$this->operations->modifyItem ($this->getUserName(),
						$preference);
				}
				else
				{
					$this->operations->addItem ($this->getUserName(),
						$preference);
				}
				//
				// All preferences are placed on the session if we have
				// changed our own preferences
				// (admin can change other preferences as well)
				//
				if ($this->getUserName()==$preference->owner)
				{
					$_SESSION[$preference->name]=$preference->value;
				}
				$this->getShowItemsParameters ();
				Header ("Location: index.php");
				break;
			default:
				$userServices = new UserServices ();
				$userSettings= $userServices->getUser
					($this->getUserName(),$this->getUserName());
				$this->modifyAction ();
				$this->renderEngine->assign('userSettings', $userSettings);
				$this->renderObjects =
					$this->operations->getPreferencesAsArray
						($this->getUserName());
				$this->renderer = 'modifyPreferences';
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
		$renderer = 'templates/'.$this->getTemplate ().'/'.
			$this->getRenderer ();
		if (!(file_exists ($renderer)))
		{
			$renderer = 'framework/view/'.$this->getRenderer ();
		}
		return $renderer;
	}

	/**
	 * Retrieves the dictionary file by first trying the language
	 * specific dictionary file, defaulting to the english version
	 * if the language specific file for the item does not exist and
	 * returns the contents  as an array
	 *
	 * @return array the dictionary
	 */
	function getDictionary ()
	{
		include ('framework/i18n/dictionary_en.php');
		$file = 'framework/i18n/dictionary_'.$_SESSION['brimLanguage'].'.php';
		if (file_exists ($file))
		{
			include ($file);
		}
		$dictionary['item_title']=$dictionary['preferences'];
		return $dictionary;
	}
}
?>
