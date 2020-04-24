<?php

require_once ('framework/ItemController.php');
require_once ('framework/model/ItemParticipationServices.php');
require_once ('framework/model/ItemParticipationFactory.php');
require_once ('plugins/calendar/model/CalendarServices.php');
require_once ('plugins/calendar/model/CalendarPreferences.php');
require_once ('plugins/calendar/model/CalendarFactory.php');

/**
 * The Calendar Controller
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - April 2004
 * @package org.brim-project.plugins.calendar
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class CalendarController extends ItemController
{
	var $today;

	var $requestedDate;
	
	var $itemParticipationServices;
	
	var $itemParticipationFactory;
	/**
	 * Constructor.
	 * Makes sure that the appropriate operations are instantiated.
	 */
	function CalendarController ()
	{
		parent::ItemController ();
		$this->operations = new CalendarServices ();
		$this->preferences = new CalendarPreferences ();
		$this->itemParticipationServices = new ItemParticipationServices();
		$this->itemParticipationFactory = new ItemParticipationFactory();
		$this->pluginName = 'calendar';
		$this->title = 'Brim - Calendar';
		$this->itemName = 'Event';

		$this->itemFactory = new CalendarFactory ();

		$this->today = mktime (0,0,0, date('m'), date ('d'), date('y'));

		if (isset ($_GET['day']) && isset ($_GET['month'])
			&& isset ($_GET['year']))
		{
			$this->requestedDate= mktime (0, 0, 0,
					$_GET['month'], $_GET['day'], $_GET['year']);
		}
		else if (isset ($_GET['month']) && isset ($_GET['year']))
		{
			$this->requestedDate= mktime (0, 0, 0,
					$_GET['month'], 1, $_GET['year']);
		}
		else if (isset ($_GET['year']))
		{
			$this->requestedDate= mktime (0, 0, 0,
					1, 1, $_GET['year']);
		}
		else if (isset ($_REQUEST['date']))
		{
			$this->requestedDate= $_REQUEST['date'];
			if (is_string ($this->requestedDate))
			{
				$this->requestedDate = intval($this->requestedDate);
			}
		}
		else if (isset ($_REQUEST['start_day']) &&
				isset ($_REQUEST['start_month']) &&
				isset ($_REQUEST['start_year']))
		{
			$this->requestedDate= mktime (0, 0, 0,
					$_REQUEST['start_month'],
					$_REQUEST['start_day'],
					$_REQUEST['start_year']);
		}
		else
		{
			$this->requestedDate = $this->today;
		}
		if (!isset ($_SESSION['calendarStartOfWeek']))
		{
			$_SESSION['calendarStartOfWeek']=
					$this->preferences->getPreferenceValue
						($this->getUserName (),
							'calendarStartOfWeek');
			$_SESSION['calendarOverlib']=
					$this->preferences->getPreferenceValue
						($this->getUserName (), 'calendarOverlib');
			$_SESSION['calendarDefaultView']=
					$this->preferences->getPreferenceValue
						($this->getUserName(), 'calendarDefaultView');
		}

//		die (print_r (date ('Y-m-d', $this->requestedDate)));
		require_once 'framework/model/AdminServices.php';
		$adminServices = new AdminServices ();
		$_SESSION['calendarEmailReminder'] =
			$adminServices->getAdminConfig ('calendarEmailReminder');
		$_SESSION['calendarParticipation'] =
			$adminServices->getAdminConfig ('calendarParticipation');
	}

	/**
 	 * Returns the actions defined for this item only
 	 * Modified by Michael : added navigationMode and rightsmanagement
 	 *
 	 * @return array an array of item specific actions (like search,
	 * import etc.)
 	 */
	function getActions ()
	{
		/*
	 	 * Actions
		 */
		$actions[0]['name'] = 'actions';

			$actions[0]['contents'][] = array(
				'href' => 'index.php?plugin=calendar&amp;action=add&amp;parentId='.$this->getParentId ().'&date='.$this->requestedDate,
				'name' => 'add'
				);
			$actions[0]['contents'][] = array(
				'href' => 'index.php?plugin=calendar&amp;action=selectToday',
				'name' => 'today'
				);
		/*
		 * Views
		 */
		$actions[1]['name'] = 'view';
			$actions[1]['contents'][] = array('href' =>
				'index.php?plugin=calendar&amp;action=showDay&date='.$this->requestedDate,
				'name' => 'dayView');
			$actions[1]['contents'][] = array('href' =>
				'index.php?plugin=calendar&amp;action=showWeek&date='.$this->requestedDate,
				'name' => 'weekView');
			$actions[1]['contents'][] = array('href' =>
				'index.php?plugin=calendar&amp;action=showMonth&date='.$this->requestedDate,
				'name' => 'monthView');
			$actions[1]['contents'][] = array('href' =>
				'index.php?plugin=calendar&amp;action=showYear&date='.$this->requestedDate,
				'name' => 'yearView');
		/*
		 * Preferences
		 */
			$actions[3]['name'] = 'preferences';
			$actions[3]['contents'][] = array('href'=> 'index.php?plugin=calendar&amp;action=modifyPreferencesPre',
			'name' => 'modify');

		$actions[4]['name']='help';
		$actions[4]['contents'][]=
			array ('href'=>'index.php?plugin=calendar&amp;action=help',
				'name'=>'help'
			);
		return $actions;
	}

	/**
	 * Activate. Basically this means that the appropriate actions are
	 * executed and an optional result is returned
	 * to be processed/displayed
	 */
	function activate ()
	{
		// Asign 'today' and the 'requested date' (defaults to today)
		$this->renderEngine->assign ('today', $this->today);

		//die (print_r ($_GET));
		$this->renderEngine->assign ('requestedDate',
			$this->requestedDate);
		$this->renderEngine->assign ('dictionary',
			$this->getDictionary());

		// Now take a look at the action
		//die (print_r ($this->getAction()));
		switch ($this->getAction ())
		{
			case "help":
				$this->helpAction ();
				break;
			case "add":
				$this->addAction ();
				break;
			// this is to show an Item in a special showItem Page
			case "showItem":
				$this->directAction ($this->getAction (), true);
				$this->renderer = 'event';
				break;
                        case "addAndAddAnother":
                                $this->addItemAction ();
                                unset ($this->renderObjects);
                                $this->addAction ();
                                break;
			case "addItemPost":
				$this->addItemAction ();
				break;
			case "addItemAndContinue":
				$this->itemId = $this->addItemAction ();
				$this->modifyAction ();
				break;
			case "modify":
				$this->modifyAction ();
				break;
			case "modifyItemPost":
				$this->modifyItemAction ();
					$this->getShowItemsParameters ();
				break;
			case "move":
				$this->moveAction ();
				break;
			case "moveItem":
				$this->moveItemAction ();
				$this->getShowItemsParameters ();
				break;
			// Added by Michael. Default navigation mode.
			// If used, change in other plugins.
			case "setModePublic":
				$_SESSION['navigationMode']='public';
				$this->navigationMode ='public';
				$this->getShowItemsParameters ();
				break;
			case "setModePrivate":
				$_SESSION['navigationMode']='private';
				$this->navigationMode ='private';
				$this->getShowItemsParameters ();
				break;
			case "searchItems":
				$this->searchItemAction ();
				break;
			case "deleteItemPost":
				$this->deleteItemAction ();
				$this->getShowItemsParameters ();
				break;
			case "modifyPreferencesPre":
				$this->renderObjects =
					$this->preferences->getAllPreferences
						($this->getUserName ());
				$this->renderer = 'modifyPreferences';
				break;
			case "modifyPreferencesPost":
				$this->preferences->setPreference
					($this->getUserName (), $_POST['name'],
						$_POST['value']);
				$_SESSION['calendarStartOfWeek']=
						$this->preferences->getPreferenceValue
							($this->getUserName (),
								'calendarStartOfWeek');
				$_SESSION['calendarOverlib']=
						$this->preferences->getPreferenceValue
							($this->getUserName (), 'calendarOverlib');
				$_SESSION['calendarDefaultView']=
						$this->preferences->getPreferenceValue
							($this->getUserName(), 'calendarDefaultView');
				$this->renderer = 'modifyPreferences';
				$this->renderObjects=$_SESSION;
				break;
			case "selectToday":
				$this->getShowItemsParameters();
				break;
			case "showYear":
				$this->renderObjects =
					$this->operations->getMonthsAsArray
						($this->getUsername (),
							$this->requestedDate,
								$_SESSION['calendarStartOfWeek']);
				$this->renderer = 'showYear';
				break;
			case "showDay":
				$this->renderer = 'showDay';
				$this->renderEngine->assign ('currentMonthValues',
					$this->operations->getDaysInMonthAsArrayPerWeek
						($this->getUsername (), $this->requestedDate,
							false, $_SESSION['calendarStartOfWeek']));
				$this->renderObjects =
					$this->operations->getEvents
						($this->getUsername(),$this->requestedDate);
				break;
			case "showWeek":
				$this->renderer = 'showWeek';
				$firstDayOfWeek = $this->operations->getFirstDayOfWeek
					($this->requestedDate,
						$_SESSION['calendarStartOfWeek']);
				$this->renderObjects =
					$this->operations->getWeekForDay
						($this->getUsername(), $this->requestedDate,
								$_SESSION['calendarStartOfWeek']);
				$this->renderEngine->assign
					('firstDayOfWeek', $firstDayOfWeek);
				break;
			case "showMonth":
				$this->renderObjects =
					$this->operations->getDaysInMonthAsArrayPerWeek
						($this->getUsername (), $this->requestedDate,
							true, $_SESSION['calendarStartOfWeek']);
				$this->renderer = 'showMonth';
				$m = date('m', $this->requestedDate);
				$d = date('d', $this->requestedDate);
				$y = date('y', $this->requestedDate);
				$previousMonth = mktime (0, 0, 0, $m-1, $d, $y);
				$nextMonth = mktime (0, 0, 0, $m+1, $d, $y);
				$this->renderEngine->assign('previousMonthValues',
					$this->operations->getDaysInMonthAsArrayPerWeek
						($this->getUsername (), $previousMonth, false,
							$_SESSION['calendarStartOfWeek']));
				$this->renderEngine->assign('nextMonthValues',
					$this->operations->getDaysInMonthAsArrayPerWeek
						($this->getUsername (), $nextMonth, false,
							$_SESSION['calendarStartOfWeek']));
				break;
			case "addReminder":
				$reminder = $this->itemFactory->requestToReminder ();
				$this->operations->addReminder ($reminder);
				$this->itemId = $reminder->eventId;
				$this->modifyAction ();
				$this->renderEngine->assign ('editReminder', 'true');
				break;
			case "deleteReminder":
				$reminder = $this->operations->getReminder ($this->itemId);
				$this->operations->deleteReminder ($this->itemId);
				$this->itemId = $reminder->eventId;
				$this->modifyAction ();
				$this->renderEngine->assign ('editReminder', 'true');
				break;
			case "addParticipator":
				//die (print_r ($_REQUEST));
				$this->itemParticipationServices->addItemParticipation 
					($_REQUEST['eventId'], 
						$_SESSION['brimUsername'], 
						$_REQUEST['addParticipator'], 'calendar');
				$this->itemId = $_REQUEST['eventId'];
				$this->modifyAction ();
				$this->renderEngine->assign ('editParticipation', 'true');
				break;
			case "deleteParticipator":
				if ($this->itemParticipationServices->getItemOwner ($this->itemId, 'calendar') 
					!= $_SESSION['brimUsername'])
				{
					return;
				}
				$this->itemParticipationServices->deleteItemParticipation 
					($this->itemId, $_SESSION['brimUsername'], $_REQUEST['participator'], 'calendar');
				$this->modifyAction ();
				$this->renderEngine->assign ('editParticipation', 'true');
				break;
			default:
				$this->getShowItemsParameters ();
				break;
		}
	}

	function getShowItemsParameters ()
	{
		switch ($_SESSION['calendarDefaultView'])
		{
			case "year":
				$this->setAction ('showYear');
				break;
			case "month":
				$this->setAction ('showMonth');
				break;
			case "week":
				$this->setAction ('showWeek');
				break;
			default:
				$this->setAction ('showDay');
				break;
		}
		$this->activate ();
	}

	function getDashboard ($field, $number, $sort)
	{
			$now = mktime (0, 0, 0, date('m'), date('d'), date('y'));
			$result = $this->operations->getEvents
						($this->getUsername(),$now);
			return $result;
	}

	/**
	 * Modifies an item. Additionally, it sets up the participator list
	 * and the reminders (if applicable)
	 */
	function modifyAction ()
	{
		parent::modifyAction();
		//
		// Setup the reminders, if enabled by the administrator
		//
		if (isset ($_SESSION['calendarEmailReminder']) &&
			($_SESSION['calendarEmailReminder'] == 1))
		{
			$reminders = $this->operations->getReminders ($this->itemId);
			$this->renderEngine->assign ('reminders', $reminders);
		}
		//
		// Setup the participation, if enabled by the administrator
		//		
		if (isset ($_SESSION['calendarParticipation']) &&
			($_SESSION['calendarParticipation'] == 1))
		{
			$users = $this->itemParticipationServices->getParticipantsStatus($this->getItemId (),
				 'calendar');
			$this->renderEngine->assign ('participatingUsers', $users['participators']);
			$this->renderEngine->assign ('nonParticipatingUsers', $users['nonParticipators']);
			$this->renderEngine->assign ('tempParticipatingUsers', $users['tempParticipators']);
		}
	}
}
?>
