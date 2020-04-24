<?php

require_once 'ext/simpletest/unit_tester.php';
require_once 'plugins/calendar/model/Event.php';
require_once 'plugins/calendar/model/CalendarServices.php';

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.calendar
 * @subpackage model.test
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class CalendarServicesTestCase extends UnitTestCase
{
	/**
	 * The calendar services
	 */
	var $services;

	/**
	 * now
	 */
	var $now;

	/**
	 * yesterday
	 */
	var $yesterday;

	/**
	 * Two days ago
	 */
	var $twoDaysAgo;

	/**
	 * tomorrow
	 */
	var $tomorrow;

	/**
	 * The owner
	 */
	var $owner;

	/**
	 * Default constructor.
	 */
	function CalendarServicesTestCase ()
	{
			$this->UnitTestCase('CalendarServices test');
	}

	/**
	 * Setup phase. Instantiates the StringUtils class
	 */
	function setUp ()
	{
			$this->owner = 'BrimUnitTest';
			$_SESSION['brimUsername'] = $this->owner;
			$this->services = new CalendarServices ();
			$this->now = strtotime (date ("Y-m-d"));
			$this->yesterday = $this->now - (60*60*24);
			$this->twoDaysAgo = $this->now - (2*60*60*24);
			$this->tomorrow =  $this->now + (60*60*24);
	}

	/**
	 * Teardown. Does nothing
	 */
	function tearDown ()
	{
			unset ($_SESSION['brimUsername']);
	}

	function testAddOneOnToday ()
	{
		$event = $this->createDummyEvent ('_unitTest_1',
			$this->now, $this->now);
		$lastInsertId = $this->services->addItem ($this->owner, $event);
		$result = $this->services->getDayEvents ($this->owner, $this->now);
		$this->assertTrue (count($result) == 1);
		$this->services->deleteItem ($this->owner, $lastInsertId);
	}

	function testAddTwoOnToday ()
	{
		$event1 = $this->createDummyEvent ('_unitTest_1',
				$this->now, $this->now);
		$lastInsertId1 = $this->services->addItem
			($this->owner, $event1);
		$event2 = $this->createDummyEvent ('_unitTest_2',
				$this->now, $this->now);
		$lastInsertId2 = $this->services->addItem
			($this->owner, $event2);
		$result = $this->services->getEvents ($this->owner, $this->now);
		$this->assertTrue (count($result) == 2);
		$this->services->deleteItem ($this->owner, $lastInsertId1);
		$this->services->deleteItem ($this->owner, $lastInsertId2);
	}

	function testAddEventFromYesterdayToTomorrow ()
	{
		$event = $this->createDummyEvent ('_unitTest_1',
			$this->yesterday, $this->tomorrow);
		$lastInsertId = $this->services->addItem ($this->owner, $event);
		$result = $this->services->getEvents ($this->owner, $this->now);
		$this->assertTrue (count($result) == 1);
		$this->services->deleteItem ($this->owner, $lastInsertId);
	}

	function testAddEventForTwoDaysAgaoToYesterday()
	{
		$event = $this->createDummyEvent ('_unitTest_1',
				$this->twoDaysAgo, $this->yesterday);
		$lastInsertId = $this->services->addItem ($this->owner, $event);
		$result = $this->services->getEvents ($this->owner, $this->now);
		$this->assertTrue (count($result) == 0);
		$this->services->deleteItem ($this->owner, $lastInsertId);
	}

	function testAddEventFromTwoDaysAgoCheckForDayBeforeYesterday()
	{
		$event = $this->createDummyEvent ('_unitTest_1',
			$this->twoDaysAgo, $this->twoDaysAgo);
		$lastInsertId = $this->services->addItem ($this->owner, $event);
		$result = $this->services->getEvents ($this->owner, $this->twoDaysAgo);
		$this->assertTrue (count($result) == 1);
		$this->services->deleteItem ($this->owner, $lastInsertId);
	}

	function testAddEventFromTwoDaysAgoCheckForYesterday()
	{
		$event = $this->createDummyEvent ('_unitTest_1',
			$this->twoDaysAgo, $this->twoDaysAgo);
		$lastInsertId = $this->services->addItem ($this->owner, $event);
		$result = $this->services->getEvents ($this->owner, $this->yesterday);
		$this->assertTrue (count($result) == 0);
		$this->services->deleteItem ($this->owner, $lastInsertId);
	}

	function _testAddEventFromTwoDaysAgoToYesterdayCheckForDayBeforeYesterday()
	{
		$event = $this->createDummyEvent ($this->owner,
			'_unitTest_1', $this->twoDaysAgo, $this->yesterday);
		$lastInsertId = $this->services->addItem ($this->owner, $event);
		$result = $this->services->getEvents ($this->owner, $this->twoDaysAgo);
		$this->assertTrue (count($result) == 1);
		$this->services->deleteItem ($this->owner, $lastInsertId);
	}

	function _testAddEventFromTwoDaysAgoToYesterdayCheckForYesterday()
	{
		$event = $this->createDummyEvent ('_unitTest_1',
			$this->twoDaysAgo, $this->yesterday);
		$lastInsertId = $this->services->addItem ($this->owner, $event);
		$result = $this->services->getEvents ($this->owner, $this->yesterday);
		$this->assertTrue (count($result) == 1);
		$this->services->deleteItem ($this->owner, $lastInsertId);
	}

	function testAddEventFromTwoDaysAgaoToYesterdayCheckForToday()
	{
		$event = $this->createDummyEvent ('_unitTest_1',
			$this->twoDaysAgo, $this->yesterday);
		$lastInsertId = $this->services->addItem ($this->owner, $event);
		$result = $this->services->getEvents ($this->owner, $this->now);
		$this->assertTrue (count($result) == 0);
		$this->services->deleteItem ($this->owner, $lastInsertId);
	}

	function createDummyEvent ($name, $eventStartDate, $eventEndDate)
	{
			//die ($eventStartDate);
		$itemId = null;
		$parentId = 0;
		$isParent = false;
		$description = 'Description';
		$visibility = 'private';
		$category = null;
		$deleted = false;
		$created = null;
		$modified = null;
		$location = 'Here';
		$organizer = 'Me';
		$priority = 3;
		$frequency = 'repeat_type_none';
		$eventInterval = null;
		$byWhat = null;
		$byWhatValue = null;
		$recurringEndDate = null;
		$colour = '#ffffff';
		$event = new Event (
				$itemId,
				$this->owner,
				$parentId,
				$isParent,
				$name,
				$description,
				$visibility,
				$category,
				$deleted,
				$created,
				$modified,
				$location,
				$organizer,
				$priority,
				$frequency,
				$eventInterval,
				$byWhat,
				$byWhatValue,
				$eventStartDate,
				$eventEndDate,
				$recurringEndDate,
				$colour
				);
		return $event;
	}
}
?>