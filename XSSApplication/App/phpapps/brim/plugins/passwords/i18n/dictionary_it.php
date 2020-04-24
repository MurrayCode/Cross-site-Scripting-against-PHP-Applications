<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.passwords
 * @subpackage i18n
 * @tradotto in italiano da Luigi Garella
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

$dictionary['item_title']='Password';
$dictionary['modifyPasswordPreferences']='Modifica le preferenze per le Password';
$dictionary['passPhrase']='Pass phrase (frase di sicurezza)';
$dictionary['login']='Login';
$dictionary['url']='Link';
$dictionary['generate']='Genera';
$dictionary['siteUrl']='URL del sito';
$dictionary['masterPassword']='Master Password';
$dictionary['generatePassword']='Genera password';
$dictionary['generatedPassword']='Password Generata';
$dictionary['credits']='
<p>
        Based on:
</p>
<ul>
	<li><a href="http://pajhome.org.uk/crypt/md5"
		>Paul Johnston</a>\'s MD5 javascript implementation</li>
	<li><a href="http://angel.net/~nic/passwdlet.html"
		>Nic Wolff</a>\'s password generator</li>
	<li><a href="http://chris.zarate.org/passwd.txt"
		>Chris Zarate</a>\'s modification to ignore subdomains</a></li>
</ul>';
$dictionary['item_help']='
<p>
	The Password plugin allows you to
	manage your passwords online.
	Actually, the plugin is called password
	manager, since this is typically the
	kind of data you would like to store
	<em>encrypted</em> in a database, but
	any kind of text can be safely stored
	using this plugin.
</p>
<p>
	<font color="red">
		It is important to realize that
		the passwords are encrypted in the
		database (so they are even unreadable
		to database administrators), they
		are en/decrypted at the server and
		thus are still sent in clear text to
		the user, if the server is using
		simple http as protocol!!
	</font>
</p>
<p>
	The following parameters of a password
	can be set:
</p>
<ul>
	<li><em>Name</em>:
		The name of the password.
	</li>
	<li><em>Folder/Password</em>:
		Indicator whether the item to add is
		a folder or a password.  Note that
		once this option is set, it cannot
		be changed anymore.
	</li>
	<li><em>Pass phrase</em>:
		The password/phrase that is used to
		encrypt the data. Once you request to
		view a password, this same passphrase
		need to be provided again, so the
		textual data can be decrypted.
	</li>
	<li><em>Description</em>:
		The description for this password.
		This field will be encrypted with a
		passphrase you enter when adding a
		password and stored encrypted in
		the database.
	</li>
</ul>
<p>
	The submenus that are available for the
	password plugin are Actions, View,
	Preferences and Help.
</p>
<h3>Actions</h3>
<ul>
	<li><em>Add</em>:
		This action presents the user with a
		input form in which the passwords\'
		parameters can be entered.
	</li>
	<li><em>Multiple select</em>:
		This action allows the user
		to select multiple passwords at the
		same time (folders are NOT selectable
		using this option) and either
		delete them all at once or move them
		all to a specific folder.
	</li>
	<li><em>Search</em>:
		This action allows to user to search
		for passwords based on their name.
	</li>
</ul>
<h3>View</h3>
<ul>
	<li><em>Expand</em>:
		This action tells the system to open
		all folders and show all items
		available. This is only
		applicable for the Tree structure view.
	</li>
	<li><em>Collapse</em>:
		This action tell the system to show
		only the items (either folders or
		passwords) of the current selected
		folder.
	</li>
	<li><em>Directory structure</em>:
		This action tells the system to switch
		to the directory structure overview.
		This view shows the passwords in a way
		that is similar to the way Yahoo! shows
		its directory structure.
		<br />
		The number of columns can for this view
		can be set in the passwords specific
		preferences.
	</li>
	<li><em>Tree structure</em>:
		This action tells the system to switch
		to an overview that is similar to the
		way Explorer and many other file managers
		show the layout of a filesystem.
	</li>
</ul>
<h3>Preferences</h3>
<ul>
	<li><em>Modify</em>:
		Modifies your password specific
		preferences. You can modify the column
		count for passwords when the are
		displayed in the directory overview
		structure, you can modify whether you
		wish javascript popups when you hover
		over the links and you can modify what
		the default view for passwords
	   	should be (either directory or tree based).
	</li>
</ul>
';
$dictionary['insecureConnection']='You are using this plugin over an
insecure connection. Beware that the physical
communication might be intercepted!';
$dictionary['noServerCommunicationUsed']='Generate password calculates your password
at the client side (javascript), no server based communication.
This tool is perfectly safe to use
regardless the connection with the server';
$dictionary['passPhraseMissing']='Passphrase is missing!';
?>
