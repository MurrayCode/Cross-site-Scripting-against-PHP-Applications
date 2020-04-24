<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.bookmarks
 * @subpackage i18n
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
if (!isset ($dictionary))
{
	$dictionary=array();
}


$dictionary['item_quick_help']='Click on the Folder/Item
icon in front of the line to move/delete/edit a link.
<br /><br />To move a link to another folder or Root,
<br />click on Edit  => Move => click on the folder you
want to move the link to.';
$dictionary['item_title']='Bookmarks';
$dictionary['locatorMissing'] = 'Link locator has to be defined';
$dictionary['modifyBookmarkPreferences']='Modify bookmark preferences';
$dictionary['quickmark']='QuickMark';
$dictionary['quickmarkExplanation']='
<p>
	RIGHT-CLICK on the following link to add it to
	Bookmarks/Favorites in your <b>browser</b>.
	<br />Each time you use this bookmark from your
	browser\'s bookmarks, the page you are on will
	be automatically added to your bookmarks.
	<br /><br />
	<font size="-2">Please click "OK" if asked about
	adding the bookmark - code that "picks up" the
	address of the page you want to bookmark makes
	some browsers nervous.</font><br />
</p>';
$dictionary['showBookmarkDetails'] = 'Show link details';
$dictionary['sidebar']='Sidebar';
$dictionary['yourPublicBookmarks']='Your public bookmarks';
$dictionary['installationPathNotSet']='
<p>
	Your installation path is not set, this
	path is needed for the quickmark
	functionality. Please ask your administrator
	to correct this.
</p>';
$dictionary['item_help']='
<p>
	The Bookmark plugin allows you to manage your
	bookmarks/favorites online.
</p>
<p>
	Click on the Folder/Item icon in front of
	the line to move/delete/edit a link.
</p>
<p>
	To move a link to another folder or Root,
	click on Edit => Move => click on the folder
	you want to move the link to.
</p>
<p>
	The following parameters of a Bookmark can be set:
</p>
<ul>
	<li><em>Name</em>:
		The name of the link. For instance: [nauta.be]
		for my personal homepage.
	</li>
	<li><em>Folder/Bookmark</em>:
		Indicator whether the item to add is a folder
		or a bookmark.  Note that
		once this option is set, it cannot be changed
		anymore.
	</li>
	<li><em>Public/private</em>:
		Indicator whether this item is public or for your
		eyes only.
		<br />
		Note that if you want a specific item to be
		public, its parents need to be public as well!!!
		(The root of the structure is public by default)
	</li>
	<li><em>URL</em>:
		The URL of this bookmark. This URL must start
		with a protocol indicator (i.e. http:// or
		ftp:// ) to be properly handled by Brim.
	</li>
	<li><em>Description</em>:
		The description for this bookmark (if any)
	</li>
	</ul>
	<p>
		The submenus that are available for the bookmarks
		plugin are Actions, View, Sort, Preferences and Help.
	</p>
	<h3>Actions</h3>
	<ul>
	<li><em>Add</em>:
		This action presents the user with a input form
		in which the bookmarks\' parameters can be
		entered.  Note that URL must start with a valid
		protocol indicator (i.e. http:// or ftp://)
	</li>
	<li><em>Multiple select</em>:
		This action allows the user
		to select multiple bookmarks at the same time
		(folders are NOT selectable using this option)
		and either delete them all at once or move them
		all to a specific folder.
	</li>
	<li><em>Import</em>:
		This action allows the user to import bookmarks.
		Currently the Opera browser and the
		Netscape/Mozilla/Firefox family are supported.
		If you would like to import your bookmarks
		from Internet Explorer, you need to export them
		first. This will give you a Netscape bookmark
		file which can be imported into Brim.
		<br />
		Importing also allows the user to specify the
		visibility flag: private or public.  All bookmarks
		will be imported with the specific flag.
		<br />
		Importing from within a specific folder is
		possible, just enter the specific directory and
		click the import action.
	</li>
	<li><em>Export</em>:
		This action allows the user to export bookmarks
		to either Opera format or the Netscape format
		(which is compatible with Mozilla/Firefox).
		If you would like to have your bookmarks exported
		to Internet Explorer, you need to export the
		bookmarks to the Netscape format and import them
		in Internet Explorer afterwards.
	</li>
	<li><em>Search</em>:
		This action allows to user to search for bookmarks
		based on name, URL or description.
	</li>
	</ul>
	<h3>View</h3>
	<ul>
	<li><em>Expand</em>:
		This action tells the system to open all
		folders and show all items available. This is
		only applicable for the Tree structure view.
	</li>
	<li><em>Collapse</em>:
		This action tell the system to show only the
		items (either folders or bookmarks) of the
		current selected folder.
	</li>
	<li><em>Directory structure</em>:
		This action tells the system to switch to the
		directory structure overview. This view shows
		the bookmarks in a way that is similar to the
		way Yahoo! shows its directory structure.
		<br />
		The number of columns can for this view can be
		set in the bookmark specific preferences.
	</li>
	<li><em>Tree structure</em>:
		This action tells the system to switch to an
		overview that is similar to the way Explorer
		and many other file managers show the layout
		of a filesystem.
	</li>
	<li><em>See shared</em>:
		Display all public bookmarks of all users mixed
		with your bookmarks (regardless whether they
		are public or private).
	</li>
	<li><em>See owned</em>:
		Show only your bookmarks (as opposed to
		"see shared")
	</li>
	</ul>
	<h3>Sort</h3>
	<ul>
	<li><em>Last visited</em>:
		Shows the bookmarks based on which bookmarks
		have been the last visited.
	</li>
	<li><em>Most visited</em>:
		Shows the bookmarks based on which bookmarks
		have been the most visited.
	</li>
	<li><em>Last created</em>:
		Shows the bookmarks based on which bookmarks
		have been the last created.
	</li>
	<li><em>Last modified</em>:
		Shows the bookmarks based on which bookmarks
		have been the last modified.
	</li>
	</ul>
	<h3>Preferences</h3>
	<ul>
	<li><em>Modify</em>:
		Modifies your bookmark specific preferences.
		You can modify the column count for bookmarks
		when the are displayed in the directory
		overview structure, you can modify whether you
		wish javascript popups when you hover over the
		links, you can modify what the default view for
		bookmarks should be (either directory or tree
		based) and you can tell whether clicking a link
		should open this link in the current or in
		a new window.
	</li>
	<li><em>Your public bookmarks</em>:
		Clicking this link will show all your public
		bookmarks.  The link that will be opened is
		publicly available, you can send it to anyone
		you like and share your bookmarks this way.
		This link can also be integrated in another
		webpage allowing you spice up your webpage
		with your bookmarks. Brim powered!
		<br />
		Note that if you want a specific item to be
		public, its parents need to be public as
		well!!!
	</li>
	<li><em>Sidebar</em>:
		This link will take you to a new page where
		you can enable Brim to integrate in your
		browser (Opera, Mozilla, Firefox and Netscape
		only).
	</li>
	<li><em>Quickmark</em>:
		RIGHT-CLICK on the following quickmark link
		to add it to Bookmarks/Favorites in your
		<b>browser</b>.  Each time you use this
		bookmark from your browser\'s bookmarks,
		the page you are on will be automatically added
		to your bookmarks (in the root).
		<br />
		Please click "OK" if asked about adding the
		bookmark - code that "picks up" the address
		of the page you want to bookmark makes some
		browsers nervous.
	</li>
</ul>
';
$dictionary['showFavicons']='Show favicons';
$dictionary['favicon']='Favicon';
$dictionary['loadAllFaviconsWarning']='<p><b>Warning</b>! Trying to fetch
favicons for all bookmarks (that have no favicon yet) may take a long time!
If you do not wish this, you can modify individual bookmarks/favorites and
try to download their icons one at the time</p><p>Partial favicon retrieval
is also possible, you can decend into a subdirectory first and try to load
the favicons of the items in this specific directory. This will take much
less time ;-)</p><p>If you find that the tree renders slowly afterwards, try
to either disable favicon rendering (via the preferences) or use the
"ExplorerTree" instead of the "JavascriptTree"</p>';
$dictionary['javascriptTree']='Javascript tree';
$dictionary['fetchingFavicon']='Fetching favicon!';
$dictionary['faviconFetched']='Icon retrieved. Press modify to save the result.';
$dictionary['noFaviconFound']='No favicon found';
$dictionary['faviconDeleted']='Icon deleted. Press modify to save the result.';
$dictionary['deleteFavicon']='Delete Favicon';
$dictionary['autoAppendProtocol']='Automatically prepend \'http://\' if the url does not contain a protocol';
?>
