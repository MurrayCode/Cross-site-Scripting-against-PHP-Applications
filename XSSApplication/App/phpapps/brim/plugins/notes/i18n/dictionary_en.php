<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.notes
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

$dictionary['item_title']='Notes';
$dictionary['modifyNotePreferences']='Modify note preferences';
$dictionary['item_help']='
<p>
	The Notes plugin allows you to manage your
	notes online.
	The following parameters of a Note can be set:
</p>
<ul>
	<li><em>Name</em>:
		The name of the note.
	</li>
	<li><em>Folder/Note</em>:
		Indicator whether the item to add is a
		folder or a note.  Note that once this
		option is set, it cannot be changed
		anymore.
	</li>
	<li><em>Public/private</em>:
		Indicator whether this item is public
		or for your eyes only.
		<br />
		Note that if you want a specific item
		to be public, its parents need to be
		public as well!!!  (The root of the
		structure is public by default)
	</li>
	<li><em>Description</em>:
		The description for this note (if any)
	</li>
</ul>
<p>
	The submenus that are available for the
	notes plugin are Actions, View, Preferences
	and Help.
</p>
<h3>Actions</h3>
<ul>
	<li><em>Add</em>:
		This action presents the user with a
		input form in which the notes\'
		parameters can be entered.
	</li>
	<li><em>Multiple select</em>:
		This action allows the user to select
		multiple notes at the same time (folders
		are NOT selectable using this option)
		and either delete them all at once or
		move them all to a specific folder.
	</li>
	<li><em>Search</em>:
		This action allows to user to search
		for notes based on name or description.
	</li>
</ul>
<h3>View</h3>
<ul>
	<li><em>Expand</em>:
		This action tells the system to open
		all folders and show all items
		available. This is only applicable for
		the Tree structure view.
	</li>
	<li><em>Collapse</em>:
		This action tell the system to show
		only the items (either folders or
		notes) of the current selected folder.
	</li>
	<li><em>Directory structure</em>:
		This action tells the system to switch
		to the directory structure overview.
		This view shows the notes in a way
		that is similar to the way Yahoo! shows
		its directory structure.
		<br />
		The number of columns can for this view
		can be set in the notes specific
		preferences.
	</li>
	<li><em>Tree structure</em>:
		This action tells the system to switch
		to an overview that is similar to the
		way Explorer and many other file managers
		show the layout of a filesystem.
	</li>
	<li><em>See shared</em>:
		Display all public notes of all users
		mixed with your notes (regardless
		whether they are public or private).
	</li>
	<li><em>See owned</em>:
		Show only your notes (as opposed to
		"see shared")
	</li>
</ul>
<h3>Preferences</h3>
<ul>
	<li><em>Modify</em>:
		Modifies your note specific preferences.
		You can modify the column count for
		notes when the are displayed in the
		directory overview structure, you can
		modify whether you wish javascript
		popups when you hover over the links
		and you can modify what the default
		view for notes should be (either
		directory or tree based).
	</li>
</ul>
';
?>