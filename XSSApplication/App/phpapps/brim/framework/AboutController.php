<?php

require_once ('framework/Controller.php');

/**
 * This controller regulates the about information, nothing more ;-)
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
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
class AboutController extends Controller
{
	/**
	 * Default constructor.
	 */
	function AboutController ()
	{
		parent::Controller ();
		$this->title = 'Brim - About';
		$this->pluginName = 'about';
		$this->itemName = 'About';
	}

	/**
	 * Get the actions for the about page. Since this is
	 * a static information page, no actions are defined
	 */
	function getActions ()
	{
	}

	/**
	 * Activates this page which in this case means retrieving
	 * the about information from the dictionary and passing
	 * it on as renderObject to the renderEngine
	 */
	function activate ()
	{
		$dictionary = $this->getDictionary ();
		$this->renderer = 'show'.$this->getItemName();
		$this->renderObjects = '<h1>Brim - '.$dictionary['version'].'</h1>';
		$this->renderObjects .= $dictionary['about_page'];
	}

	/**
	 * Returns the filename that will be given to the template for
	 * display
	 *
	 * @return string the filename of the template file to show.
	 */
	function getTemplateFile ()
	{
		//
		// Allow the template to override a specific file.
		// If this file exist (the file in the template directory),
		// it will be loaded, otherwise the default (in the plugin
		// directory) will be loaded
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
	 * Retrieves the dictionary file by first tryin the language specific
	 * dictionary file, defaulting to the english version if the language
	 * specific file for the item does not exist and returns the contents
	 * as an array
	 *
	 * @return array the dictionary
	 */
	function getDictionary ()
	{
		include ('framework/i18n/dictionary_en.php');
		if (file_exists ('framework/i18n/dictionary_'.
			$_SESSION['brimLanguage'] . '.php'))
		{
			include ('framework/i18n/dictionary_'.
				$_SESSION['brimLanguage'].'.php');
		}
		$dictionary['item_title']=$dictionary['about'];
		return $dictionary;
	}
}
?>
