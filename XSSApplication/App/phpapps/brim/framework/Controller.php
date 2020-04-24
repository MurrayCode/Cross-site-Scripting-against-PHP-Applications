<?php
if (!function_exists ('session_start'))
{
	die ('No session support in php, Brim cannot work without it. Please install the php-session extension.');
}
@session_start ();
include ('framework/checkLogin.php');
include ('framework/configuration/menuItems.php');
include ('ext/Savant/Savant.php');
include_once ('framework/configuration/preferenceConfiguration.php');
include_once ('framework/util/globalFunctions.php');// added by Michael
include_once ('framework/util/BrowserUtils.php');// added by Michael

/**
 * The Controller (abstract base class). This class provides the 'glue'
 * between the model and the view. Note that this controller is a simple
 * one, it has no knowledge of items. (For this, a subclass called
 * <code>ItemController</code> is provided).
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.framework
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class Controller
{
	/**
	 * Specific preferences which can contain user definable parameters
	 *
	 * @protected
	 * @var array preferences
	 */
	var $preferences;

	/**
	 * The template engine
	 *
	 * @private
	 * @var object renderEngine
	 */
	var $renderEngine;

	/**
	 * The current action that is being processed
	 *
	 * @var string action
	 */
	var $action;

	/**
	 * Parameters, placeholder for almost anything
	 *
	 * @var hashtable parameters
	 */
	var $parameters;

	/**
	 * The operations class
	 *
	 * @see Operations
	 * @var object operations
	 */
	var $operations;

	/**
	 * The name of the renderer that will be used for processing
	 *
	 * @var string renderer
	 */
	var $renderer;

	/**
	 * This name will be used for file lookup and equals the name of
	 * the subdirectory of the item under the plugins directory
	 *
	 * @var string pluginName the name of the plugin that is
	 * controlled
	 */
	var $pluginName;

	/**
	 * The name of the plugin that is controller by this controller.
	 * This name will be used to retrieve the appropriate view.
	 * For instance if the pluginName is 'Bookmark' and the current
	 * action is 'add', the file 'addBookmark.tpl.php' might be
	 * resolved.
	 *
	 * @var string itemName the name of the plugin that is
	 * controlled by this controller
	 * @todo shouldn't this move to the itemcontroller?
	 */
	var $itemName;

	/**
	 * The title to display in the browser title bar
	 *
	 * @var string title the title of the current plugin
	 * (usually preceded by 'Brim - ')
	 */
	var $title;

	/**
	 * The object responsable for access rights
	 *
	 * @var RightsManager rightsManager the rightsManager object
	 * used
	 */
	var $rightsManager;

	/**
	 * The BrowserUtils are used to detect a PDA connection
	 *
	 * @var object browserUtils the utility class
	 */
	var $browserUtils;

	/**
	 * Constructor. Fetches the action from the request and assigns this
	 * parameter to the controller
	 */
	function Controller ()
	{
		$this->renderObjects = null;
		$this->preferences = null;
		$this->parameters = array ();
		$this->browserUtils = new BrowserUtils ();
 		// Fetch the action parameter from the request
		if (isset ($_GET['action']))
		{
			$this->action = $_GET['action'];
		}
		// Can we safely assume that a POST overrules a GET?
		if (isset ($_POST['action']))
		{
			$this->action = $_POST['action'];
		}
		$this->renderEngine =& new Savant ();
	}


	/**
	 * Returns the specific URL for this Controller
	 *
	 * @return string URL of this controller
	 * @todo check obsoletion
	 * @obsolete ??
	 */
	function getURL ()
	{
		return $this->getItemName () . "Controller.php";
	}

	/**
	 * Returns the template that is currently in use
	 *
	 * @return string the template that is currently in use
	 */
	function getTemplate ()
	{
		if ($this->browserUtils->browserIsPDA ())
		{
			return 'pda';
		}
		return $_SESSION['brimTemplate'];
	}

	/**
	 * Returns the name of the renderer that will be used
	 * for further processing
	 *
	 * @return string the renderer
	 */
	function getRenderer ()
	{
		return $this->renderer.'.tpl.php';
	}

	/**
	 * Returns the renderobjects that will be displayed
	 *
	 * @return array the render objects
	 */
	function getRenderObjects ()
	{
		return $this->renderObjects;
	}

	/**
	 * Sets the action that is requested by the user
	 *
	 * @param string theAction the requested action
	 */
	function setAction ($theAction)
	{
		$this->action = $theAction;
	}

	/**
	 * Returns the action that is requested by the user
	 *
	 * @return string theAction the requested action
	 */
	function getAction ()
	{
		return $this->action;
	}

	/**
  	 * Returns the actions defined for this item only
	 * Abstract function.
 	 *
 	 * @return array an array of item specific actions (like search,
	 * import etc.)
	 * @abstract
	 * @todo Perhaps simply remove this function, no need for an empty function
 	 */
	function getActions ()
	{
		return null;
	}

	/**
	 * Add a parameter to the existing parameter hashTable
	 *
	 * @param string key the key for the parameter
	 * @param string value the value of the parameter
	 */
	function addParameter ($key, $value)
	{
		$this->parameters [$key] = $value;
	}

	/**
	 * Retrieves the dictionary file by first tryin the language
	 * specific dictionary file, defaulting to the english version
	 * if the language specific file for the item does not exist and
	 * returns the contents as an array
	 *
	 * @return array the dictionary
	 */
	function getDictionary ()
	{
		$dictionary = array ();
		//
		// First load the english BASE dictionary file.
		// This file MUST exist
		//
		include ('framework/i18n/dictionary_en.php');
		//
		// If a language specific BASE dictionary exist,
		// load this one as well.
		//
		if (file_exists ('framework/i18n/dictionary_'.$_SESSION['brimLanguage'].'.php'))
		{
			include ('framework/i18n/dictionary_'.$_SESSION['brimLanguage'].'.php');
		}
		//
		// Now load the english PLUGIN dictionary.
		// This file MUST exist as well.
		//
		include ('plugins/'.$this->pluginName.'/i18n/dictionary_en.php');
		//
		// If a language specific PLUGIN dictionary exist,
		// load this one as well.
		//
		if (file_exists ('plugins/'.$this->pluginName.
			'/i18n/dictionary_'.$_SESSION['brimLanguage'].'.php'))
		{
			include ('plugins/'.$this->pluginName.'/i18n/dictionary_'.
				$_SESSION['brimLanguage'].'.php');
		}
		//
		// Ok, we're done
		//
		return $dictionary;
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
		// If this file exist (the file in the
		// template directory), it will be loaded, otherwise the
		// default (in the plugin directory) will be loaded
		//
		$renderer = 'templates/'.$this->getTemplate ().'/'.$this->getRenderer();
		if (!(file_exists ($renderer)))
		{
			$renderer = 'plugins/'.$this->pluginName.'/view/'.
				$this->getRenderer ();
		}
		return $renderer;
	}

	/**
	 * Display which basically means that the template will be invoked
	 */
	function display ()
	{
		$menuItems = array ();
		include('framework/configuration/menuItems.php');
		global $menu;

		$this->renderEngine->assign('title', $this->getTitle ());
		$this->renderEngine->assign('menuItems', $menuItems);
		$this->renderEngine->assign('menu', $menu);
		$this->renderEngine->assign('dictionary',
			$this->getDictionary ());

		$this->renderEngine->assign('parameters', $this->getParameters ());
		$this->renderEngine->assign('action', $this->getAction ());
		$this->renderEngine->assign('renderObjects', $this->getRenderObjects ());
		$this->renderEngine->assign('renderActions', $this->getActions ());

		$this->renderEngine->assign ('pluginName', $this->pluginName);
		$this->renderEngine->assign ('renderer', $this->getTemplateFile ());
		if (isset ($_GET['debug']) ||
			(isset ($_SESSION['debug']) && $_SESSION['debug']=='true'))
		{
			error_reporting(E_ALL);
			$this->renderEngine->display('templates/'.
				$this->getTemplate().'/template.tpl.php');
		}
		else
		{
			error_reporting(E_ERROR);
			@$this->renderEngine->display('templates/'.
				$this->getTemplate().'/template.tpl.php');
		}
	}


	/**
	 * Returns this controllers parameters
	 *
	 * @return hashtable the parameters (key/value based)
	 */
	function getParameters ()
	{
		return $this->parameters;
	}

	/**
	 * Returns the title of this Controller
	 *
	 * @return string the title of this controller
	 */
	function getTitle ()
	{
		return $this->title;
	}

	/**
	 * Returns the name of the item that is controller by this
	 * controller
	 *
	 * @return string the name of the item that is controlled by this
	 * controller
	 * @todo shouldn't this move to the itemcontroller?
	 */
	function getItemName ()
	{
		return $this->itemName;
	}

	/**
	 * Returns the name of the plugin that is controlled by this
	 * controller. This name will be used for file lookup and equals
	 * the name of the subdirectory of the item under the plugins
	 * directory
	 *
	 * @return string the name of the plugin that is controlled by this
	 * controller
	 */
	function getPluginName ()
	{
		return $this->pluginName;
	}

	/**
	 * Return the username (retrieves it from the session)
	 *
	 * @return string username the current users username
	 */
	function getUserName ()
	{
		return $_SESSION['brimUsername'];
	}

	/**
	 * Abstract function to provide dashboard functionality. The dashboard
	 * function gives a short overview (for instance the 5 last added items)
	 * which can be used in an overview screen
	 *
	 * @abstract
	 * @todo Perhaps simply remove this function, no need for an empty function,
	 * or do we want defensive programming?
	 */
	function getDashboard ()
	{
		return null;
	}

	/**
	 * The help function is requested. Load the plugins help version
	 * in english, check for the language specific help file afterwards
	 * and load this one afterwards if it exists
	 */
	function helpAction ()
	{
		$this->renderer = 'help';
		include ('plugins/'.$this->pluginName.'/i18n/dictionary_en.php');
		if (file_exists('plugins/'.$this->pluginName.'/i18n/dictionary_'.
			$_SESSION['brimLanguage'].'.php'))
		{
			include ('plugins/'.$this->pluginName.'/i18n/dictionary_'.
				$_SESSION['brimLanguage'].'.php');
		}
		$help = $dictionary['item_help'];
		$this->renderEngine->assign ('pluginHelp', $help);
	}
}
?>
