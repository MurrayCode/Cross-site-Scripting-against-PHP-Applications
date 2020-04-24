<?php

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.contacts
 * @subpackage i18n
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * This file is part of the Booby project.
 * The GNU Public License
 */
if (!isset ($dictionary))
{
	$dictionary=array();
}

$dictionary['address']='Address (home)';
$dictionary['alias']='Nickname';
$dictionary['birthday']='Birthday';
$dictionary['email']='Email';
$dictionary['email1']='Email (home)';
$dictionary['email2']='Email (work)';
$dictionary['email3']='Email (other)';
$dictionary['faximile']='Fax (work)';
$dictionary['item_title']='Contacts';
$dictionary['job']='Job title';
$dictionary['mobile']='Tel (mobile)';
$dictionary['modifyContactPreferences']='Modify contact preferences';
$dictionary['org_address']='Address (work)';
$dictionary['organization']='Organization';
$dictionary['tel_home']='Tel (home)';
$dictionary['tel_work']='Tel (work)';
$dictionary['webaddress']='Web';
$dictionary['item_help']='
<p>
	The contacts plugin allows you to manage
	your contacts online. The following parameters
	of a contact can be set:
</p>
<ul>
	<li><em>Name</em>:
		The name of the contact person.
	</li>
	<li><em>Folder/Contact</em>:
		Indicator whether the item to add is a
		folder or a contact.  Note that once this
		option is set, it cannot be changed anymore.
	</li>
	<li><em>Public/private</em>:
		Indicator whether this item is public or
		for your eyes only.
		<br />
		Note that if you want a specific item to
		be public, its parents need to be public as
		well!!! (The root of the structure is public
		by default)
	</li>
	<li><em>Tel.Home</em>:
		The contacts\' home telephone number.
	</li>
	<li><em>Tel.Work</em>:
		The contacts\' work telephone number.
	</li>
	<li><em>Fax.</em>:
		The contacts\' fax number.
	</li>
	<li><em>Email (home)</em>:
		You can add up to three email addresses per
		contact.
		This is the contacts\' home, or first email address.
	</li>
	<li><em>Email (work)</em>:
		You can add up to three email addresses per
		contact.
		This is the contacts\' work, or second email address.
	</li>
	<li><em>Email (other)</em>:
		You can add up to three email addresses per
		contact.
		This is the contacts\' third email address.
	</li>
	<li><em>Web address (homepage)</em>:
		You can add up to three web addresses per
		contact.
		This is the contacts\' homepage, or first web address.
	</li>
	<li><em>Web address (work)</em>:
		You can add up to three web addresses per
		contact.
		This is the contacts\' work, or second web address.
	</li>
	<li><em>Web address (home)</em>:
		You can add up to three web addresses per
		contact.
		This is the contacts\' home (i.e. family), or third web address.
	</li>
	<li><em>Job title</em>:
		The job title for this person
	</li>
	<li><em>Nickname</em>:
		The alias/nickname for this person (can be
		used in combination with search)
	</li>
	<li><em>Organization</em>:
		The organization/company for which the
		person is working
	</li>
	<li><em>Address (home)</em>:
		The address of this person
	</li>
	<li><em>Address (work)</em>:
		The address of the organization/company
		of this person
	</li>
	<li><em>Description</em>:
		A description for this person
	</li>
</ul>
<p>
	The submenus that are available for the contact
	plugin are Actions, View, Sort and Preferences.
</p>
<h3>Actions</h3>
<ul>
	<li><em>Add</em>:
		This action presents the user with a input
		form in which the contacts\' parameters
		can be entered.
		<br />
		Note that web addresses must start with a
		valid protocol indicator
		(i.e. http:// or ftp://)
	</li>
	<li><em>Multiple select</em>:
		This action allows the user to select
		multiple contacts at the same time (folders
		are NOT selectable using this option) and
		either delete them all at once or move them
		all to a specific folder.
	</li>
	<li><em>Import</em>:
		This action allows the user to import
		contacts.  Currently the Opera format and
		vCards are supported.
		<br />
		Importing also allows the user to specify
		the visibility
		flag: private or public.  All contacts will
		be imported with the specific flag.
		<br />
		Importing from within a specific folder is
		possible, just enter the specific directory
		and click the import action.
	</li>
	<li><em>Export</em>:
		This action allows the user to export contacts
		to either Opera format or to vCards (which
		can be imported in many
		other mail-clients/address books).
	<li><em>Search</em>:
		This action allows to user to search for
		contacts based on name, alias, description or
		address.
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
		items (either folders or contacts) of the
		current selected folder.
	</li>
	<li><em>Directory structure</em>:
		This action tells the system to switch to
		the directory structure overview. This view
		shows the contacts in a way that is similar
		to the way Yahoo! shows its directory
		structure.
		<br />
		The number of columns can for this view can
		be set in the contact specific preferences.
	</li>
	<li><em>Tree structure</em>:
		This action tells the system to switch to
		an overview that is similar to the way
		Explorer and many other file managers show
		the layout of a filesystem.
	</li>
	<li><em>Line based</em>:
		Another way of displaying contacts. This
		overview shows contacts with lots of detail.
	<li><em>See shared</em>:
		Display all public contacts of all users
		mixed with your contacts (regardless whether
		they are public or private).
	</li>
	<li><em>See owned</em>:
		Show only your contacts (as opposed to
		"see shared")
	</li>
</ul>
<h3>Sort</h3>
<ul>
	<li><em>Alias</em>:
		Sort on the alias of the contact.
	</li>
	<li><em>Email 1</em>:
		Sort on the contacts primary email address.
	</li>
	<li><em>Organization</em>:
		Sort on the contacts organization.
	</li>
</ul>
<h3>Preferences</h3>
<ul>
	<li><em>Modify</em>:
		Modifies the users preferences related to
		contacts.  You can modify the column count
		for contacts when they are shown in directory
		overview structure, you can modify whether
		you want javascript popups containing more
		information when you hover over the contacts
		and you can modify the default view for
		contacts (either directory structure, tree
		based or line based).
	</li>
</ul>
';
$dictionary['email_home']='Email&nbsp;(home)';
$dictionary['email_other']='Email&nbsp;(other)';
$dictionary['email_work']='Email&nbsp;(work)';
$dictionary['webaddress_home']='Webaddress&nbsp;(home)';
$dictionary['webaddress_homepage']='Webaddress&nbsp;(homepage)';
$dictionary['webaddress_work']='Webaddress&nbsp;(work)';
$dictionary['clickHere']='Click here';
?>
