<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 * @subpackage i18n
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
include 'framework/i18n/common.php';
if (!isset ($dictionary))
{
	$dictionary=array();
}
$dictionary['activate']='Activate';

$dictionary['about']='About';
$dictionary['about_page']=' <h2>About</h2>
<p>
	<b>'.$dictionary['programname'].' '.$dictionary['version'].'</b> This application is written
	by '.$dictionary['authorname'].' (email:
	<a href="mailto:'.$dictionary['authoremail'].'"
	>'.$dictionary['authoremail'].'</a>)
	'.$dictionary['copyright'].' </p> <p> The
	purpose is to provide an open-source
	single login remote desktop application
	(i.e. your bookmarks, tasks etc
	integrated in one environment)
</p>
<p>
	This program ('.$dictionary['programname'].') is released under
	the GNU General Public License. Click
	<a href="documentation/gpl.html">here</a>
	for the full version of the license.
	The homepage of the application can be
	found at the following address: <a
	href="'.$dictionary['programurl'].'"
	>'.$dictionary['programurl'].'</a> </p>';
$dictionary['actions']="Actions";
$dictionary['add']='Add';
$dictionary['addFolder'] = "Add a folder";
$dictionary['addNode'] = "Add an Item";
$dictionary['adduser']='Add user';
$dictionary['admin']='Admin';
$dictionary['adminConfig']='Configuration';
$dictionary['admin_email']='Admin email';
$dictionary['allow_account_creation']="Allow user account creation";
$dictionary['back']='Back';
$dictionary['bookmark']='Bookmark';
$dictionary['bookmarks']='Bookmarks';
$dictionary['cancel']='Cancel';
$dictionary['calendar']='Calendar';
$dictionary['collapse']='Collapse';
$dictionary['confirm']='Confirm';
$dictionary['confirm_delete']='Are you sure you want to delete?';
$dictionary['contact']='Contact';
$dictionary['contacts']='Contacts';
$dictionary['contents']='Contents';
$dictionary['dashboard']='Dashboard';
$dictionary['database']='Database';
$dictionary['deactivate']='Deactivate';
$dictionary['deleteTxt']='Delete';
$dictionary['delete_not_owner']="You are not allowed to delete an
item which you do not own.";
$dictionary['description']='Description';
$dictionary['down']='Down';
$dictionary['email']='Email';
$dictionary['expand']='Expand';
$dictionary['explorerTree']='Tree structure';
$dictionary['exportTxt']='Export';
$dictionary['exportusers']='Export users';
$dictionary['file']='File';
$dictionary['findDoubles']='Find doubles';
$dictionary['folder']='Folder';
$dictionary['forward']='Forward';
$dictionary['genealogy']='Genealogy';
$dictionary['help']='Help';
$dictionary['home']='Home';
$dictionary['importTxt']='Import';
$dictionary['importusers']='Import users';
$dictionary['input']='Input';
$dictionary['input_error'] = "Please check the input fields";
$dictionary['installation_path']="Installation path";
$dictionary['installer_exists']='<h2><font color="red">
Install file still exist! Please remove it</font></h2>';
$dictionary['item_count']='Number of items';
$dictionary['item_private'] = "Private item";
$dictionary['item_public'] = "Share this item";
//$dictionary['item_title']='';
$dictionary['inverseAll']='Inverse all';
$dictionary['javascript_popups']="Javascript popups";
$dictionary['language']='Language';
$dictionary['last_created']='Last created';
$dictionary['last_modified']='Last modified';
$dictionary['last_visited']='Last visited';
$dictionary['license_disclaimer']='
	The homepage of the '.$dictionary['programname'].' application
	can be found at the following address:
	<a href="'.$dictionary['programurl'].'"
	>'.$dictionary['programurl'].'</a>
	<br />
	'.$dictionary['copyright'].' '.$dictionary['authorname'].'
	(<a href="'.$dictionary['authorurl'].'"
	>'.$dictionary['authorurl'].'</a>).
	You can contact me at <a
	href="mailto:'.$dictionary['authoremail'].'"
	>'.$dictionary['authoremail'].'</a>.  <br />
	This program ('.$dictionary['programname'].') is free software;
	you can redistribute it and/or modify
	it under the terms of the GNU General
	Public License as published by the
	Free Software Foundation; either
	version 2 of the License, or
	(at your option) any later version.
	Click <a href="documentation/gpl.html"
	>here</a> for the full version of the
	license.  ';
$dictionary['lineBasedTree']='Line based';
$dictionary['link']='link';
$dictionary['loginName']='Login name';
$dictionary['logout']='Logout';
$dictionary['mail']='Mail';
$dictionary['message']="Message";
$dictionary['modify']='Modify';
$dictionary['modify_not_owner']="You are not allowed to modify an item
which you do not own.";
$dictionary['month01']='January';
$dictionary['month02']='February';
$dictionary['month03']='March';
$dictionary['month04']='April';
$dictionary['month05']='May';
$dictionary['month06']='June';
$dictionary['month07']='July';
$dictionary['month08']='August';
$dictionary['month09']='September';
$dictionary['month10']='October';
$dictionary['month11']='November';
$dictionary['month12']='December';
$dictionary['most_visited']='Most visited';
$dictionary['move']='Move';
$dictionary['multipleSelect']='Multiple select';
$dictionary['mysqlAdmin']='MySQL';
$dictionary['nameMissing'] = "Name has to be defined";
$dictionary['name']='Name';
$dictionary['news']='News';
$dictionary['new_window_target']='Where does the new window open';
$dictionary['no']='No';
$dictionary['note']='Note';
$dictionary['notes']='Notes';
$dictionary['overviewTree']='Overview Tree';
$dictionary['password']='Password';
$dictionary['passwords']='Passwords';
$dictionary['pluginSettings']='Plugins';
$dictionary['plugins']='Plugins';
$dictionary['preferences']='Preferences';
$dictionary['priority']='Priority';
$dictionary['private']='Private';
$dictionary['public']='Public';
$dictionary['quickmark']='
	RIGHT-CLICK on the following link to
	add it to Bookmarks/Favorites in your
	<b>browser</b>. <br />Each time you use
	this bookmark from your browser\'s
	bookmarks, the page you are on will
	be automatically added to your '.$dictionary['programname'].'
	bookmarks.
	<br />
	<br />
	<font size="-2">Please click "OK" if
	asked about adding the bookmark - code
	that "picks up" the address of the
	page you want to bookmark makes some
	browsers nervous.</font><br />';
$dictionary['refresh']='Refresh';
$dictionary['root']='Root';
$dictionary['search']='Search';
$dictionary['selectAll']='Select all';
$dictionary['deselectAll']='Deselect all';
$dictionary['setModePrivate'] = "See Owned";
$dictionary['setModePublic'] = "See Shared";
$dictionary['show']='Show';
$dictionary['sort']='Sort';
$dictionary['submit']='Submit';
$dictionary['sysinfo']='SysInfo';
$dictionary['theme']='Theme';
$dictionary['title']='Title';
$dictionary['today']='Today';
$dictionary['tasks']='Tasks';
$dictionary['task']='Task';
$dictionary['translate']='Translate';
$dictionary['tasks']='Tasks';
$dictionary['task']='Task';
$dictionary['up']='Up';
$dictionary['locator']='URL';
$dictionary['user']='User';
$dictionary['view']="View";
$dictionary['visibility']='Visibility';
$dictionary['webtools']='WebTools';
$dictionary['welcome_page']='<h1>Welcome %s </h1><h2>'.$dictionary['programname'].' -
a multithingy something </h2>';
$dictionary['yahoo_column_count']='Yahootree column count';
$dictionary['yahooTree']='Directory structure';
$dictionary['yes']='Yes';

// sterry
$dictionary['polardata'] 			= 'Polar Data';
$dictionary['textsource'] 			= 'Text Source';
$dictionary['banking'] 				= 'E-Banking';
$dictionary['synchronizer'] 		= 'Synchronizer';
$dictionary['spellcheck']='Check spelling';
$dictionary['item_help']='
<h1>'.$dictionary['programname'].' Help</h1>
<p>
	'.$dictionary['programname'].' has two menu-bars, one is called the
	application bar and contains application
	wide settings, the other one is called
	the plugin bar and contains the links to
	the different plugins. For plugin specific
	help, click <a href="#plugins">here</a>.
</p>
<p>
	The preferences link in the application bar
	takes you to a screen in which you can set
	your language, the theme you would like to
	use and your personal settings like password,
	email address etc. Note that language and
	theme cannot be set at the same time!
</p>
<p>
	The info link shows general application
	information, including the current
	version number
</p>
<p>
	Pressing the logout link will perform an
	application logout.  This link also destroys
	the cookie that was set when you use
	the "remember me" option when you login,
	so afterwards you need
	to re-login before using '.$dictionary['programname'].'.
</p>
<p>
	The plugins section allows you to
	activate/deactivate plugins.  If a plugin
	is deactivated, it will not show up in your
	plugin bar, nor in the help section.
</p>
';
$dictionary['collections']='Collections';
$dictionary['depot']='DepotTracker';
$dictionary['checkbook']='Checkbook';
$dictionary['gmail']='GMail';
$dictionary['dateFormat']='Date format';
$dictionary['select']='Select';
$dictionary['formError']='The submitted form contains an error';
$dictionary['defaultTxt']='Default';
$dictionary['preferedIconSize']='Prefered icon size (penguin and mylook theme)';
$dictionary['showTips']='Show tips';
$dictionary['tip']='Tip';
$dictionary['noSearchResult']='No search result';
$dictionary['recipes']='Recipes';
$dictionary['calendarEmailReminder']='Email reminders for events are activated (i.e. crontab)';
$dictionary['addToFolderNotOwned']='You cannot add an item to a folder you do not own';
$dictionary['attentionTemplate']='Your template can only support a maximum
number of items, afterwards the application menu will disappear. Click
<a href="PreferenceController.php">here</a> if you can not access the
application menu (which contains preferences, help, search,
logout, translate options etc)';
$dictionary['weather']='Weather';
$dictionary['addAndAddAnother']='Add and add another';
$dictionary['calendarParticipation']='Enable sharing of calendar with other users';
$dictionary['share']='Share';
$dictionary['participatingUsers']='Participating users';
$dictionary['nonParticipatingUsers']='Other users';
$dictionary['none']='None';
$dictionary['youAreNotOwnerButParticipator']='This item is not owned by you, you cannot modify it';
$dictionary['defaultExpandMenu']='Expand menu items by default (barrel theme)';
$dictionary['defaultShowShared']='Show shared items by default (effective after re-login)';
$dictionary['trash']='Trash';
$dictionary['deleteForever']='Delete forever';
$dictionary['undelete']='Undelete';
$dictionary['emailRequired']='Please provide your email address';
$dictionary['invalidEmail']='Your email appears to be invalid';
$dictionary['nameRequired']='Please provide your name';
$dictionary['passwordRequired']='Please provide your password';
$dictionary['password2Required']='Please confirm your password';
$dictionary['creationDateTime']='Creation';
$dictionary['lastLogin']='Last login';
$dictionary['loadingIndication']='Loading...';
$dictionary['toggleSelection']='Toggle selection';
$dictionary['enableAjax']='Interactive userinterface';
?>
