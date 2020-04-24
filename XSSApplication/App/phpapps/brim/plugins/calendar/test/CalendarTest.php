<?php
/**
 * Entry point for the calendar test
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - September 2006
 * @package org.brim-project.plugins.calendar
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
//
// Setup test browser
//
if (!defined ('SIMPLE_TEST'))
{
    define ('SIMPLE_TEST', 'ext/simpletest/');
}
require_once(SIMPLE_TEST . 'browser.php');
$browser = &new SimpleBrowser ();
//
// User creation and login
//
$username = 'BrimUnitTest';
$password = 'BrimUnitTest';
$template = 'mylook';
$language = 'EN';
//
// Start the session
//
unset ($_SESSION);
session_start ();
//$_SESSION['brimUsername']=$username;
//
// Create the user
//
createUser ($username, $password, $language, $template);
//
// And login
//
// link will be set from the calling class. The calling class should
// INCLUDE this file
//
connect($link, $username, $password, $browser);
echo '
<p>
	User <code>'.$username.'</code> is now logged in.
</p>';
	//
	// Now start the actual tests
	//
	deleteAllEvents ($username);
	echo '<h2>Create global event for today</h2>
<p>
	You should see a test with the name \'test1\' showing up
	as a global event. This means no start time, no end time,
	no dates are set. The event should show up only once at the
	top of the current day
</p>';
	test1 ($browser);
	showWeek ($browser);
	echo '<h2>Create an event from 00h00 with duration of 1 hour</h2>
<p>
	This test shows that one-hour events starting at midnight are
	properly displayed. The previous event (test1) is still there.
</p>';
	test2 ($browser);
	showWeek ($browser);
	echo '<h2>Add an event at 07h30 with duration of 3 hours</h2>
<p>
	This event shows events that have a duration. It spans mulitple
	rows in the overview. The previous events are still there.
</p>';
	test3 ($browser);
	showWeek ($browser);
	echo '<h2>Add an event that crosses the previous event</h2>
<p>
	The events overlap. The application should now indicate one big
	block with both events in it, smallest starttime is taken as start
	and the biggest endtime is taken as end.
</p>';
	test4 ($browser);
	showWeek ($browser);
	echo '<h2>Same events, now the month view</h2>';
	showMonth ($browser);
	echo '<h2>Same events, now the day view</h2>';
	showDay ($browser);
	deleteAllEvents ($username);
	echo '<h2>Create an event that spans mulitple days</h2>
<p>
	An event that starts today at 19h00 and lasts 16 hour. It should
	be visible on two days in the weekview (except if it starts on the
	last day of the week ;-).
</p>
<p>
	All previous events are deleted.
</p>';
	test5 ($browser);
	showWeek ($browser);
	echo '<h2>Add a new event that spans more days and crosses the previous event</h2>
<p>
	The events should be combined into one block
</p>';
	test6 ($browser);
	showWeek ($browser);
	deleteAllEvents ($username);
	echo '<h2>Create a daily event with no ending</h2>
<p>
	This event should show up on every day. All previous events are
	deleted.
</p>';
	test7 ($browser);
	showMonth ($browser);
	echo '<h2>Same event, week view</h2>';
	showWeek ($browser);

function connect ($link, $username, $password, &$browser)
{
	$browser->get ($link);
	echo ('<h2>Login at: '.$link.'</h2>');
	$browser->setField ('username', $username);
	$browser->setField ('password', $password);
	echo ($browser->getContent());
	$browser->clickSubmitByName ('submit');
	//echo ($browser->getContent());
}

function createUser ($username, $thePassword, $language, &$template)
{
	require_once 'framework/model/User.php';
	require_once 'framework/model/UserServices.php';
	require_once 'framework/model/PreferenceServices.php';

	$userServices = new UserServices ();
	$preferenceServices = new PreferenceServices ();

	$user = new User (0, $username, $thePassword,
					'Brim Test', 'brim@test.user', 'Description',
					null, null);
	$languagePreferences = new Preference (0, $username, 0, 0,
		'language', null, 'private', null, 0, null, null, $language);
	$templatePreferences = new Preference (0, $username, 0, 0,
		'brimTemplate',null, 'private', null, 0, null, null, $template);

	$existingUser = $userServices->getUser (0, $username);
	if (!isset ($existingUser))
	{
		$userId = $userServices->addUser ($username, $user);
		$preferenceServices->addItem
			($username, $languagePreferences);
		$preferenceServices->addItem
			($username, $templatePreferences);
	}
}

function deleteAllEvents ($username)
{
	$engine = null;
	$host = '';
	$user = '';
	$password = '';
	$database = '';
	require_once('ext/adodb/adodb.inc.php');
	include('framework/configuration/databaseConfiguration.php');
	$db = NewADOConnection ($engine);
	$db->Connect ($host, $user, $password, $database)
    	or die ($db->ErrorMsg());
	$query  = "DELETE FROM brim_calendar_event ";
	$query .= "WHERE owner='".$username."'";
	$db->Execute ($query) or die ($db->ErrorMsg ().' '.$query);
}

function showMonth (&$browser)
{
	$browser->clickLinkById('calendar');
	$browser->clickLinkById('monthView');
	echo ($browser->getContent());
}

function showWeek (&$browser)
{
	$browser->clickLinkById('calendar');
	$browser->clickLinkById('weekView');
	echo ($browser->getContent());
}

function showDay (&$browser)
{
	$browser->clickLinkById('calendar');
	$browser->clickLinkById('dayView');
	echo ($browser->getContent());
}

function test1 (&$browser)
{
	$browser->clickLinkById('calendar');
	$browser->clickLinkById('add');
	$browser->setField ('name', 'Test1');
	//
	// Hmm.... how to make this language independant?
	// ClickSubmitById doesn't seem to work...
	//
	$browser->clickSubmit ('Add');
}

function test2 (&$browser)
{
	$browser->clickLinkById('calendar');
	$browser->clickLinkById('add');
	$browser->setField ('name', 'Test2');
	$browser->setField ('durationHours', '1');
	//
	// Hmm.... how to make this language independant?
	// ClickSubmitById doesn't seem to work...
	//
	$browser->clickSubmit ('Add');
}

function test3 (&$browser)
{
	$browser->clickLinkById('calendar');
	$browser->clickLinkById('add');
	$browser->setField ('name', 'Test3');
	$browser->setField ('start_time_hours', '7');
	$browser->setField ('start_time_minutes', '30');
	$browser->setField ('durationHours', '3');
	//
	// Hmm.... how to make this language independant?
	// ClickSubmitById doesn't seem to work...
	//
	$browser->clickSubmit ('Add');
}

function test4 (&$browser)
{
	$browser->clickLinkById('calendar');
	$browser->clickLinkById('add');
	$browser->setField ('name', 'Test4');
	$browser->setField ('start_time_hours', '9');
	$browser->setField ('durationHours', '6');
	//
	// Hmm.... how to make this language independant?
	// ClickSubmitById doesn't seem to work...
	//
	$browser->clickSubmit ('Add');
}

function test5 (&$browser)
{
	$browser->clickLinkById('calendar');
	$browser->clickLinkById('add');
	$browser->setField ('name', 'Test5');
	$browser->setField ('start_time_hours', '19');
	$browser->setField ('durationHours', '16');
	//
	// Hmm.... how to make this language independant?
	// ClickSubmitById doesn't seem to work...
	//
	$browser->clickSubmit ('Add');
}

function test6 (&$browser)
{
	$browser->clickLinkById('calendar');
	$browser->clickLinkById('add');
	$browser->setField ('name', 'Test6');
	$browser->setField ('start_time_hours', '12');
	$browser->setField ('durationHours', '32');
	//
	// Hmm.... how to make this language independant?
	// ClickSubmitById doesn't seem to work...
	//
	$browser->clickSubmit ('Add');
}

function test7 (&$browser)
{
	$browser->clickLinkById('calendar');
	$browser->clickLinkById('add');
	$browser->setField ('name', 'Test7');
	$browser->setField ('toggleTheEndDate', '0');
	$browser->setField ('useEndDate', 'true');
	$browser->setField ('end_month', 'December');
	//
	// Hmm.... how to make this language independant?
	// ClickSubmitById doesn't seem to work...
	//
	$browser->clickSubmit ('Add');
}


?>