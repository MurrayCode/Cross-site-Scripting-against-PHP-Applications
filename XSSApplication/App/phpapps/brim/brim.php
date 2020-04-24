<?php

/**
 * Public bookmarks which can be emedded in other files or used as it
 * is currently provided.
 *
 * Create another PHP file with the following content as a start.
 * This other file will call this file (brim.php) to ask for
 * the public bookmarks:
 *
 * <pre>
 * &lt;?php
 *     ob_start ();
 *     //
 *     // Set the username and plugin
 *     //
 *     $_GET ['username']= 'my_username';
 *     $_GET ['plugin']='bookmarks';
 * ?&gt;
 *
 * &lt;--
 *     Now place your html code here, along with the code to display your bookmarks
 *     The code looks like this:
 * --&gt;
 *
 * &lt;php
 *     include ('brim.php');
 * &gt;
 *
 * &lt;--
 *     Finish the rest of your html code
 * --&gt;
 *
 * &lt;php
 *     ob_end_flush ();
 * &gt;
 *
 * </pre>
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.plugins.bookmarks
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */


if (!isset($_SERVER) && isset($HTTP_SERVER_VARS))
	define('_SERVER', 'HTTP_SERVER_VARS');

 /**
  * This function adds children to the current item, If the current
  * item is expanded and applies this fucntion recursively to the
  * children of the current item
  *
  * @param object item the item for which we would like to add children
  * 	if this item is expanded
  * @param services the services (model) used to retrieve the public
  *  	items for the given user
  * @param string username the name of the user
  */
function addExpandedChildren (&$item, $services, $username)
{
	//
	// Only look for this items children if we have an item that
	// is a parent and expanded
	//
	if ($item->isParent () && isExpanded ($item->itemId))
	{
		$items = $services->getPublicChildrenForUser
			($username ,$item->itemId);
		//
		// Apply the same function recursively on this item's children
		//
		for ($i=0; $i<count($items); $i++)
		{
			$child =& $items[$i];
			addExpandedChildren ($child, $services, $username);
			$item->addChild ($child);
		}
	}
}


/**
 * Is this itemId in the expanded list?
 *
 * @param integer itemId the id of the item
 * @return boolean <code>true</code> if this item is expanded,
 * 		<code>false</code> otherwise
 */
function isExpanded ($itemId)
{
	if (!isset ($_GET['expand']))
	{
		return false;
	}
	$expanded = explode (",", $_GET['expand']);
	while (list ($key, $val) = each($expanded))
	{
		if ($val == $itemId)
		{
			//
			// yep, in the expanded list
			//
			return true;
		}
	}
	return false;
}

function sendReminders ()
{
	require_once 'plugins/calendar/model/CalendarServices.php';
	$calendarServices = new CalendarServices();
	$reminders = $calendarServices->getAllReminders ();
	foreach ($reminders as $reminder)
	{
		$event = $calendarServices->getEventForId($reminder->eventId);
		if ($calendarServices->shouldSendReminder ($event, $reminder))
		{
			require_once 'framework/model/UserServices.php';
			$userServices = new UserServices ();
			$userSettings = $userServices->getUserForLoginName ($reminder->owner);

			if ($calendarServices->sendReminder ($reminder, $userSettings))
			{
				$calendarServices->reminderSent ($reminder->itemId);
				require_once 'framework/model/ItemParticipationServices.php';
				$itemParticipationServices = new ItemParticipationServices();
				$userNames = 
					$itemParticipationServices->getParticipatorNames
						($reminder->eventId, 'calendar');
				foreach ($userNames as $userName)
				{
					$userSettings = $userServices->getUserForLoginName ($userName);
					$calendarServices->sendReminder ($reminder, $userSettings);
				}
			}
			else
			{
				//
				// Crons sometimes provide feedback. In case of an error,
				// echo something.
				//
				echo 'Mail not sent (problems related to '.
					var_export ($reminder, true).')';
			}
		}
	}
}


function doit ()
{
//
// Procedural part. Retrieve the username, the plugin and optionally
// the parentId or itemId from the request
//
$username = $_GET['username'];
$plugin = $_GET['plugin'];
if (isset ($username) && isset ($plugin))
{
	//
	// Configuration used by the tree renderer. This configuration
	// is used by the treedelegate and since eachplugin uses a
	// different (hardcoded) delegate (like ExplorerTreeDelegate,
	// YahooTreeDelegate etc), this configuration needs to be set
	// before instantiating the delegate
	//
	$configuration = array ();
	//
	// Callback is the executing script, this allows a user
	// to include this script in an embedding page
	//
	$configuration['callback']=$_SERVER['PHP_SELF'];

	include ('templates/barry/icons.inc');
	include ('framework/i18n/dictionary_en.php');
	$configuration['icons']=$icons;
	$configuration['username']=$username;
	$configuration['plugin']=$plugin;


	$parentId = 0;

	//
	// Check the which plugin is requested
	//
	if ($plugin == 'bookmarks')
	{
		include ('plugins/bookmarks/model/BookmarkServices.php');
		include ('plugins/bookmarks/i18n/dictionary_en.php');
		$configuration['dictionary']=$dictionary;
		$services = new BookmarkServices ();
		require_once ('framework/view/PublicExplorerTreeDelegate.php');
		$delegate = new PublicExplorerTreeDelegate ($configuration);
	}
	else if ($plugin == 'calendar')
	{
		if (!isset ($_GET['action']))
		{
			exit;
		}
		$action = $_GET['action'];
		if ($action == 'sendReminders')
		{
			sendReminders();
		}
		exit;
	}
	/*
	else if ($plugin == 'contacts')
	{
		include ('plugins/contacts/model/ContactServices.php');
		include ('plugins/contacts/lang/dictionary_EN.php');
		$configuration['dictionary']=$dictionary;
		$services = new ContactServices ();
		require_once ('plugins/contacts/view/PublicLineBasedTreeDelegate.php');
		$delegate = new PublicLineBasedTreeDelegate ($configuration);
	}
	else if ($plugin == 'news')
	{
		include ('plugins/news/model/NewsServices.php');
		include ('plugins/news/lang/dictionary_EN.php');
		$configuration['dictionary']=$dictionary;
		$services = new NewsServices ();
		require_once ('base/view/PublicYahooTreeDelegate.php');
		$delegate = new PublicLineYahooTreeDelegate ($configuration);
	}
	else if ($plugin == 'notes')
	{
		include ('plugins/notes/model/NoteServices.php');
		include ('plugins/notes/lang/dictionary_EN.php');
		$configuration['dictionary']=$dictionary;
		$services = new NoteServices ();
		require_once ('base/view/PublicYahooTreeDelegate.php');
		$delegate = new PublicLineYahooTreeDelegate ($configuration);
	}
	else if ($plugin == 'todos')
	{
		include ('plugins/todos/model/TodoServices.php');
		include ('plugins/todos/lang/dictionary_EN.php');
		$configuration['dictionary']=$dictionary;
		$services = new TodoServices ();
		require_once ('plugins/todos/view/PublicTodoOverviewDelegate.php');
		$delegate = new PublicTodoOverviewTreeDelegate ($configuration);
	}
	*/
	else
	{
		die ('Public accessible items for plugin '.$plugin.' are note yet available');
	}

	//
	// If we are asking for an item Id, forward directly to this URL
	//
	if (isset ($_GET['itemId']))
	{
		$item = $services->getItem ($username, $_GET['itemId']);
		if ($item->visibility == 'public')
		{
			Header ('location: '.$item->locator);
		}
		else
		{
			Header ('location: '.$_SERVER['PHP_SELF'].'?username='.$username.'&amp;plugin=bookmarks');
		}
		exit;
	}

	//
	// Check if we clicked on a folders link. Open this folder if this
	// is  the case
	//
	if (isset ($_GET['parentId']))
	{
		$parentId = $_GET['parentId'];
	}

	//
	// Get the public items
	//
	$rootItems = $services->getPublicChildrenForUser
		($username, $parentId);
	$root = $services->getItem ($username, $parentId);
	for ($i=0; $i<count($rootItems); $i++)
	{
		$item =& $rootItems[$i];
		addExpandedChildren ($item, $services, $username);
	}

	//
	// Create the tree and show the items
	//
	require_once ('framework/view/Tree.php');
	$tree = new Tree ($delegate, $configuration);
	if (isset ($_GET['expand']))
	{
		$tree->setExpanded ($_GET['expand']);
	}
	$result = '';
	if (substr ($_SERVER['PHP_SELF'], -8) == 'brim.php')
	{
		$result .= '<html><head>';
		$result .= '<title>Brim</title>';
		$result .= '<style type="text/css" media="screen">';
		$result .= '@import "templates/penguin/template.css";';
		$result .= '</style>';
		$result .= '</head>';
		$result .= '<body>';
		$result .= $tree->toHtml ($root, $rootItems);
		$result .= '</body>';
		$result .= '</html>';
	}
	else
	{
		$result .= $tree->toHtml ($root, $rootItems, true);
	}
	return $result;
}
else
{
	die ("Please provide both username AND plugin in the following form: http://.../brim/brim.php?username=the_username&amp;plugin=the_plugin");
}
}

if (!isset ($_GET['return']))
{
	echo doit ();
}
?>
