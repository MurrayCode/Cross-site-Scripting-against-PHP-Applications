<?php
/**
 * The installation script
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - January 2004
 * @package org.brim-project.framework
 * @subpackage install
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
define ("NEWVERSION", "1.2.3");

if (!isset($_SERVER) && isset($HTTP_SERVER_VARS))
	define('_SERVER', 'HTTP_SERVER_VARS');


/*******************************************************************
 *  D A T A B A S E
 *******************************************************************/
function getDatabaseConnection ()
{
	$engine = null;
	$host = '';
	$user = '';
	$password = '';
	$database = '';
	@include('ext/adodb/adodb.inc.php');
	include ('framework/configuration/databaseConfiguration.php');
	$db = NewADOConnection ($engine);
	$db->Connect($host, $user, $password, $database);
	return $db;
}

function createDatabase ()
{
	$engine = null;
	$host = '';
	$user = '';
	$password = '';
	$database = '';
	include('ext/adodb/adodb.inc.php');
	include ('framework/configuration/databaseConfiguration.php');
	$db = NewADOConnection ($engine);
	$db->Connect($host, $user, $password);
	$db->Execute ("create database ".$database)
		or die ($db->ErrorMsg ());
}

function createDatabaseConfigurationFile
	($databaseEngine, $databaseUser, $databasePassword,
		$databaseHost, $databaseName)
{
	$fileName = 'framework/configuration/databaseConfiguration.php';
	$result = '<?php
		$engine=\''.$databaseEngine.'\';
		$user=\''.$databaseUser.'\';
		$password=\''.$databasePassword.'\';
		$host=\''.$databaseHost.'\';
		$database=\''.$databaseName.'\';
	?>';
	if (file_exists ($fileName))
	{
		die (print_r ('Database configuration file already exist, cannot create'));
	}
	$resource = fopen ($fileName,'xt');
	if (fwrite ($resource, $result))
	{
		step_connect_adodb ();
	}
	else
	{
		die ('Failure writing databaseConfig file');
	}

	//die (print_r ('*'.$result));


}
function checkAdodb ()
{
	return @include('ext/adodb/adodb.inc.php');
}

function checkDatabaseConfigurationFile ()
{
	return file_exists ('framework/configuration/databaseConfiguration.php');
}

function connectAdodb ()
{
	$engine = null;
	$host = '';
	$user = '';
	$password = '';
	$database = '';
	@include('ext/adodb/adodb.inc.php');
	include ('framework/configuration/databaseConfiguration.php');
	$db = NewADOConnection ($engine);
	$result = $db->Connect($host, $user, $password, $database);
	return $result;
}

function step_check_adodb ()
{
	echo ('<h2>Database connection</h2>');
	$state_messages['adodb_found']='<h3>Adodb found</h3>';
	$state_messages['adodb_not_found']='<h3>Adodb NOT found</h3>
	<p>
		Please install adodb as described in the
		<a href="documentation/installation_guide.html">Installation guide</a>
	</p>';
	if (checkAdodb ())
	{
		echo ($state_messages['adodb_found']);
		step_connect_adodb ();
	}
	else
	{
		echo ($state_messages['adodb_not_found']);
	}
}

function step_connect_adodb ()
{
	$state_messages['adodb_connected']='<h3>Database connection successful!</h3>';
	$state_messages['adodb_not_connected']='<h2>Database connection failure!</h2>

		<p>
			This probably means that the database does not exist yet.
		</p>
		<p>
			Click the button
			to create the database indicated in the database
			configuration file.
		</p>
		<form method="POST" action="'.$_SERVER['PHP_SELF'].'">
			<input type="submit" value="Create database" name="createDatabase" />
		</form>
		<p>
			Alternatively, the file might contain the wrong credentials,
			click the button below to delete the existing database connection file
		</p>
		<form method="POST" action="'.$_SERVER['PHP_SELF'].'">
			<input type="submit" value="Delete database config" name="deleteDatabaseConfig" />
		</form>';
	$state_messages['provide_database_configuration_file']='
		<h3>Now is probably the time to configure your database.</h3>
		<p>
			The databaseConfiguration file is not found on your system,
			please make sure that there is a
			<code>databaseConfiguration.php</code>
			file in the directory <code>framework/configuration</code>.
			This script will try to create it for you, provided that
			the directory "<code>framework/configuration</code>" is
			writeable.
		</p>
	';
	if (checkDatabaseConfigurationFile ())
	{
		if (connectAdodb ())
		{
			echo ($state_messages['adodb_connected']);
		}
		else
		{
			echo ($state_messages['adodb_not_connected']);
			exit ();
		}
	}
	else
	{
		echo ($state_messages['provide_database_configuration_file']);
		if (is_writeable ('framework/configuration'))
		{
			echo '<h3>The directory <code>framework/configuration</code>
				is writeable</h3>
				The installation script can probably
				create the file for you.
				<form method="POST" action="'.$_SERVER['PHP_SELF'].'">
				<table>
					<tr>
						<td>Engine (i.e. mysql, postgres etc)
						</td>
						<td>
							<input type="text" name="databaseEngine" />
						</td>
					</tr>
					<tr>
						<td>Database username
						</td>
						<td>
							<input type="text" name="databaseUser" />
						</td>
					</tr>
					<tr>
						<td>Database password
						</td>
						<td>
							<input type="text"
								name="databasePassword" />
						</td>
					</tr>
					<tr>
						<td>Host (typically localhost)
						</td>
						<td>
							<input type="text" name="databaseHost" />
						</td>
					</tr>
					<tr>
						<td>Database name (typically brim)
						</td>
						<td>
							<input type="text" name="databaseName" />
						</td>
					</tr>
				</table>
				<input type="submit" value="Submit"
					name="createDatabaseConfigurationFile" />
				</form>';
		}
		else
		{
			echo '<h3>The directory <code>framework/configuration</code>
				is not writeable. </h3>
				You can either make this directory
				writeable (on unix: chmod 777 will do, although some webservers 
				dont like this, use 755 or sometimes 751 instead) and reexecute
				this installation script, or copy/move/rename the
				<code>framework/configuration/databaseConfiguration.example.php</code>
				to the file '."
				<code>framework/configuration/databaseConfiguration.php</code>
				edit the parameters
				(you need to provide your database-engine (i.e.  mysql
				or postgres), your database username, your database password, the host
				(the machine on which the application is installed, typically localohost) 
				and the name of the database in which you wish to
				install the application. (All tablenames start with
				'<code>brim_</code>' so don't worry about conflicts)
				and reexecute the installation
				script.</p>
		<p>
			Here is an example of how the
			<code>databaseConfiguration.php</code> should look like:
		<p>
		As an example:
		<pre>
&lt;?php
/*
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
\$engine = 'mysql';
\$user = 'root';
\$password = '';
\$host = 'localhost';
\$database = 'brim';
?&gt;
		</pre>";
		}
		exit ();
	}
}


/**
 * Check plugin activation. If the specific plugin is enabled
 * or disabled, leave it untouched. Otherwise it is a new
 * plugin and we can enable it
 */
function checkPluginActivation ($pluginName)
{
	$engine = null;
	include ('framework/configuration/databaseConfiguration.php');
	$db = getDatabaseConnection ();
	require_once ('framework/UserInstaller.php');
	require_once ('framework/PluginInstaller.php');
	$userInstaller = new UserInstaller ($engine, $db);
	$pluginInstaller = new PluginInstaller ($engine, $db);
	$users = $userInstaller->getAllUserNames ();
	for ($i=0; $i<count($users); $i++)
	{
		$currentUser = $users[$i];
		if (!$pluginInstaller->isPluginSet ($currentUser, $pluginName))
		{
			$pluginInstaller->activatePlugin($currentUser, $pluginName);
		}
	}
}

/**
 * Installs the framework for Brim
 */
function installFramework ()
{
	$engine = null;
	echo ('<h2>Framework installation</h2>');
	include ('framework/configuration/databaseConfiguration.php');
	$db = getDatabaseConnection ();

	require_once ('framework/AdminInstaller.php');
	require_once ('framework/UserInstaller.php');
	require_once ('framework/TempUserInstaller.php');
	require_once ('framework/PreferenceInstaller.php');
	require_once ('framework/PluginInstaller.php');
	require_once ('framework/ItemParticipationInstaller.php');

	echo '<h3>Administration</h3>';
	$installer = new AdminInstaller ($engine, $db);
	$installer->install ();
	$installer->set ('brim_version', NEWVERSION);

	echo '<h3>Users</h3>';
	$installer = new UserInstaller ($engine, $db);
	$installer->install ();
	if ($installer->getUserId ('admin') == null)
	{
		echo ('Installing admin user<br />');
		$installer->addUser ('admin', 'admin', 'Brim administrator');
	}
	$installer = new TempUserInstaller ($engine, $db);
	$installer->install ();

	echo '<h3>Preferences</h3>';
	$installer = new PreferenceInstaller ($engine, $db);
	$installer->install ();
	if ($installer->getPreference ('admin', 'brimLanguage') == null)
	{
		echo ('Setting language preferences for admin user<br />');
		$installer->insertPreference
			('admin', 'brimLanguage', 'EN');
	}
	if ($installer->getPreference ('admin', 'brimTemplate') == null)
	{
		echo ('Setting template preferences for admin user<br />');
		$installer->insertPreference
			('admin', 'brimTemplate', 'barrel');
	}

	echo '<h3>Plugin placeholder</h3>';
	$installer = new PluginInstaller ($engine, $db);
	$installer->install ();

	echo '<h3>Item participation</h3>';
	$installer = new ItemParticipationInstaller ($engine, $db);
	$installer->install ();
}

/**
 * PLUGIN installation
 */
function installPlugins ()
{
	$engine = null;
	echo ('<h2>Plugin installation</h2>');
	include ('framework/configuration/databaseConfiguration.php');
	$db = getDatabaseConnection ();

	$plugins = array ();
	$plugins [] = array ('name'=>'banking', 'installer'=>'BankingInstaller');
	$plugins [] = array ('name'=>'bookmarks', 'installer'=>'BookmarkInstaller');
	$plugins [] = array ('name'=>'calendar', 'installer'=>'CalendarInstaller');
	$plugins [] = array ('name'=>'contacts', 'installer'=>'ContactInstaller');
	$plugins [] = array ('name'=>'news', 'installer'=>'NewsInstaller');
	$plugins [] = array ('name'=>'notes', 'installer'=>'NoteInstaller');
	$plugins [] = array ('name'=>'passwords', 'installer'=>'PasswordInstaller');
	$plugins [] = array ('name'=>'collections', 'installer'=>'CollectionsInstaller');
	$plugins [] = array ('name'=>'tasks', 'installer'=>'TaskInstaller');
	$plugins [] = array ('name'=>'depot', 'installer'=>'DepotInstaller');
	$plugins [] = array ('name'=>'checkbook', 'installer'=>'CheckbookInstaller');
	$plugins [] = array ('name'=>'genealogy', 'installer'=>'GenealogyInstaller');
	$plugins [] = array ('name'=>'recipes', 'installer'=>'RecipeInstaller');
	$plugins [] = array ('name'=>'weather', 'installer'=>'WeatherInstaller');

	foreach ($plugins as $plugin)
	{
		$theInstaller = 'plugins/'.$plugin['name'].'/'.$plugin['installer'].'.php';
		if (file_exists ($theInstaller))
		{
			echo '<h3>'.$plugin['name'].'</h3>';
			require_once $theInstaller;
			$installer = new $plugin['installer'] ($engine, $db);
			$installer->install ();
			checkPluginActivation($plugin['name']);
		}
	}
	if (file_exists ('plugins/webtools'))
	{
		echo ('<h3>webtools</h3>');
		checkPluginActivation ('webtools');
	}
	echo '
	<h1>Done</h1>
	<p>
		Before you launch the application, please read the following:
	</p>
		<ul>
			<li>Remove the installation script (this script: install.php)</li>
			<li>If you used the unpack scripts, remove the according files 
				(Tar.php, unpack.php and brimfull-xxx.tar.gz)</li>
			<li>Change the installation path in the configuration
				otherwise the quickmark functionality will not work 
				(login as admin user and go to the configuration section of 
				the application. The path to set is typically something in the 
				form of <code>http://_your_webserver_address/brim/</code>).</li>
		</ul>
	<p>
	If this was a new installation,
	login with username/password \'admin\'
	<br />
		<font color="red">Do not forget to change your password
			via the settings!</font><br />
	Click <a href="index.php">here</a> to go to the application.
	';
}

function showWelcomeMessage ()
{
	echo '
<p>
	Welcome to the Brim installation/update script.
</p>
<p>
<font color="red">
	 If you UPGRADE the application, please make a database
	 backup before continuing!!!
	 <br />
	 Remove this script if the installation was succesful!
</font>
<h2>POST installation instructions</h2>
<p>
	Log in as admin user and go to the configuration section
	to finish the installation.
</p>
<ul>
	<li>Set the installation path to make the quickmark work</li>
	<li>Set the admin password to be notified in case of password loss</li>
</ul>
<p>
	<font size="+1" color="red">DO NOT forget to change
		the admin password!!!!!</font>
</p>
<p>
    	You can subscribe to new release announcements via either sourceforge
	or freshmeat.
</p>
<p>
	A script (email2brim.pl) is available in the tools subdirectory.
	This script fetches information from email and "slams" it into
	brim. If a subject starts with <code>[bookmark]</code>,
	<code>[task]</code>, <code>[contact]</code> or <code>[note]</code>
	it is automatically inserted in the appropriate plugin.
	Put this script in your crontab to use it to its full extend!
</p>
<p>
	Use the subscription services of either
</p>
<ul>
	<li><a href="http://freshmeat.net/projects/brim">freshmeat.net</a></li>
	<li><a href="http://sourceforge.net/projects/brim">sourceforge.net</a></li>
</ul>
<p>
	to stay up to date on new releases.
</p>
	';
}
?>
<html>
<head>
	<title>Brim - installation</title>
	<style type="text/css">
	body
	{
		background: #fff url(framework/view/pics/treeback.jpg) repeat-x top;
		font: 14px arial,helvetica,sans-serif
	}
	</style>
</head>

<body>
<h1>Brim installation</h1>
<?php

	if (isset ($_POST['createDatabaseConfigurationFile']))
	{
		//die (print_r ($_POST));
		createDatabaseConfigurationFile (
			$_POST['databaseEngine'],
			$_POST['databaseUser'],
			$_POST['databasePassword'],
			$_POST['databaseHost'],
			$_POST['databaseName']
		);
		step_check_adodb ();
	}
	else if (isset ($_POST['createDatabase']))
	{
		createDatabase ();
		// Fake POST parameters to indicate
		// that we have seen the welcomeMessage and we
		// wish to connect to the database
		$_POST['welcomeMessageShown']=true;
		$_POST['connectToDatabase']=true;
	}
	else if (isset ($_POST['deleteDatabaseConfig']))
	{
		$fileName = 'framework/configuration/databaseConfiguration.php';
		unlink ($fileName);
		die ('Press recall the same page (no refresh, that will only invoke the same action!!) 
			in the browser to restart the installation process');
	}
	// No else!!! Faked by previous step
	if (isset ($_POST['connectToDatabase']))
	{
		step_check_adodb ();
		echo '
		<form method="POST" action="'.$_SERVER['PHP_SELF'].'">
			<input type="hidden" name="welcomeMessageShown" />
			<input type="submit" value="Install framework" name="installFramework" />
		</form>
		';
		exit ();
	}
	else if (isset ($_POST['installFramework']))
	{
		installFramework ();
		echo '
		<form method="POST" action="'.$_SERVER['PHP_SELF'].'">
			<input type="hidden" name="welcomeMessageShown" />
			<input type="submit" value="Install plugins" name="installPlugins" />
		</form>
		';
		exit ();
	}
	else if (isset ($_POST['installPlugins']))
	{
		installPlugins ();
	}
	if (!isset ($_POST['welcomeMessageShown']))
	{
		showWelcomeMessage ();
		echo '
		<form method="POST" action="'.$_SERVER['PHP_SELF'].'">
			<input type="hidden" name="welcomeMessageShown" />
			<input type="submit" value="Continue" name="connectToDatabase" />
		</form>
		';
		exit ();
	}
?>
</body>
</html>
