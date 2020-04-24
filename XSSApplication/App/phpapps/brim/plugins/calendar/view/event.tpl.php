<?php

include 'framework/view/globalFunctions.php';
require_once 'framework/util/DateUtils.php';
/**
 * The template file that draws the layout to add a tasks.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.calendar
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

$remindersEnabled = 
	(isset ($_SESSION['calendarEmailReminder']) &&
	($_SESSION['calendarEmailReminder'] == 1));
$participationEnabled = 
	(isset ($_SESSION['calendarParticipation']) &&
	($_SESSION['calendarParticipation'] == 1));
$showTabs = $remindersEnabled || $participationEnabled;

$isItemOwner = $renderObjects->owner == $_SESSION['brimUsername'];
$dateUtils = new DateUtils ();
if (!isset ($requestedDate))
{
	$requestedDate = date ('Y-m-d');
}
$recurringEndDate = $requestedDate;

//die (print_r ($renderObjects));
//
// Default is to use duration and not the enddate
//
$useStartTime = true;
$useDuration = true;
$useEndDate = false;
$durationHours = '00';
$durationMinutes = '00';
$useRecurringEndDate = false;
if (!isset ($viewAction))
{
	$viewAction = 'show';
}
if ($viewAction == 'modify')
{
	$startDate = date ('Y-m-d', $renderObjects->eventStartDate);
	$startHours = date ('H', $renderObjects->eventStartDate);
	$startMinutes = date ('i', $renderObjects->eventStartDate);

	if (isset ($renderObjects->eventEndDate))
	{
		$endDate = date ('Y-m-d', $renderObjects->eventEndDate);
		$endHours = date ('H', $renderObjects->eventEndDate);
		$endMinutes = date ('i', $renderObjects->eventEndDate);
	}
	

	$recurringEndDate =
		date ('Y-m-d', $renderObjects->eventRecurringEndDate);
	if ($recurringEndDate == '1970-01-01')
	{
		$recurringEndDate = date ('Y-m-d');
		$useRecurringEndDate = false;
	}
	else
	{
		$useRecurringEndDate = true;
	}
	if (!isset ($renderObjects->eventEndDate))
	{
		$useDuration = false;
		$useEndDate = false;
	}
	else if ($startDate < $endDate)
	{
		$useDuration = false;
		$useEndDate = true;
	}
	else
	{
		$diff = ($renderObjects->eventEndDate - $renderObjects->eventStartDate);
		$durationHours = floor ($diff/60/60);
		if ($durationHours < 10 && strlen
			($durationHours) < 2) 
		{
			$durationHours = "0".$durationHours;
		}
		$durationMinutes = date ('i', $diff);
		if ($diff == 0 && date ('H:i',$renderObjects->eventEndDate)=='00:00')
		{
			$useDuration = false;
			$useStartTime = false;
		}
	}
}
else
{
	$startDate = date ('Y-m-d', $requestedDate);
	$endDate = date ('Y-m-d', $requestedDate);
	$startHours = '00';
	$startMinutes = '00';
	$endHours = '00';
	$endMinutes = '00';
}

$startDateYear = $dateUtils->getYearFromDate ($startDate);
$startDateMonth = $dateUtils->getMonthFromDate ($startDate);
$startDateDay = $dateUtils->getDayInMonthFromDate ($startDate);
$startDateHour = $dateUtils->getHoursFromDate ($startDate);
$startDateMinutes = $dateUtils->getMinutesFromDate ($startDate);

if (isset ($renderObjects->eventEndDate))
{
	$endDateYear = $dateUtils->getYearFromDate ($endDate);
	$endDateMonth = $dateUtils->getMonthFromDate ($endDate);
	$endDateDay = $dateUtils->getDayInMonthFromDate ($endDate);
	$endDateHour = $dateUtils->getHoursFromDate ($endDate);
	$endDateMinutes = $dateUtils->getMinutesFromDate ($endDate);
}
else
{
	$now = date ('Y-m-d');
	$endDateYear = $dateUtils->getYearFromDate ($now);
	$endDateMonth = $dateUtils->getMonthFromDate ($now);
	$endDateDay = $dateUtils->getDayInMonthFromDate ($now);
	$endDateHour = '00';
	$endDateMinutes = '00';
	
}

$useRecurrence=false;
if (isset ($renderObjects->frequency) &&
	$renderObjects->frequency!='repeat_type_none')
{
	$useRecurrence=true;
}
?>
<script src="ext/javascript/datePicker.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="ext/javascript/datePicker.css" />
<?php
	$other = 'templates/'.$_SESSION['brimTemplate'].'/datePicker.css';
	if (file_exists ($other))
	{
?>
	<link rel="stylesheet" type="text/css" href="<?php echo $other ?>" />
<?php
	}
?>

<script type="text/javascript">
	function selectColour (value)
	{
		nd ();
		document.getElementById('eventColour').value = value;
	}

	/**
	 * This function 'pops up' the color picker
	 */
	function overlibColourPicker (inputValue)
	{
		var value = document.getElementById (inputValue).value;
		var table = '<table border="1">';
		table += '<tr><td colspan="18" bgcolor="'+value+'"><font color="#ffffff">'+value+'</font></td>';
		table += '<td colspan="18" bgcolor="'+value+'"><font color="#000000">'+value+'</font></td></tr>';
		var colours = new Array ('00','33','66','99','cc','ff');
		for (var i=0; i<colours.length; i++)
		{
			table += '<tr>';
			for (var j=0; j<colours.length; j++)
			{
				for (var k=0; k<colours.length; k++)
				{
					var colour = '#'+colours[i]+colours[j]+colours[k];
					table += '<td bgcolor="'+colour+'">';
					table += '<a href="javascript:selectColour(\''+colour+'\');" ';
					table += 'class="overlibColourPicker">&nbsp;&nbsp;</a>';
					table += '</td>';
				}
			}
			table += '</tr>';
		}
		table += '</table>';
		return table;
	}
	
	/**
	 * The hidden input field (selectingDate) is used to keep track of which field
	 * we are actually selecting with the datePicker tool. A selection
	 * on the value of this field is done in the datePickerClosed
	 * function which updates the appropriate date fields
	 */
	function selectDateValue (value)
	{
		document.getElementById ("selectingDate").value = value;
	}

	/**
	 * The datePicker has been closed (and a date has been selected)
	 * so update the appropriate date fields based on the value in the
	 * hidden input field (selectingDate)
	 */
	function datePickerClosed (date)
	{
		var theDate = getFieldDate (date.value);
		var year = theDate.getFullYear ();
		var month = theDate.getMonth ()+1;
		var day = theDate.getDate ();
		if (day < 10)
		{
			day = '0' + day;
		}
		switch (document.getElementById ("selectingDate").value)
		{

			case "startDate":
				document.getElementById ("start_year").value = year;
				document.getElementById ("start_month").value = month;
				document.getElementById ("start_day").value = day;
				break;
			case "endDate":
				document.getElementById ("end_year").value = year;
				document.getElementById ("end_month").value = month;
				document.getElementById ("end_day").value = day;
				break;
			case "recurringEndDate":
				document.getElementById ("recurringEndYear").value = year;
				document.getElementById ("recurringEndMonth").value = month;
				document.getElementById ("recurringEndDay").value = day;
				break;
		}
	}
	
	/**
	 * This function is called when the enable-startime checkbox is
	 * checked on the webpage. It shows the start-time input fields
	 */
	function enableStartTime ()
	{
		document.getElementById ('start_time_div').style.display='';
	}

	/**
	 * This function is called when the 'enable starttime' checkbox
	 * is unchecked. It disables the enable startime input fields and
	 * additionally sets the start-time to '00:00:00'
	 */
	function disableStartTime ()
	{
		document.getElementById ('start_time_div').style.display='none';
		document.getElementById ('duration_div').style.display='none';
		document.getElementById ("durationHours").value = '00';
		document.getElementById ("durationMinutes").value = '00';
		document.getElementById ("start_time_hours").value = '00';
		document.getElementById ("start_time_minutes").value = '00';
	}
	
	function disableEndDate ()
	{
		document.getElementById ('end_time_div').style.display='none';
		document.getElementById ('duration_div').style.display='';
		document.getElementById ('useEndDate').value='false';
		document.getElementById ("end_time_hours").value = '00';
		document.getElementById ("end_time_minutes").value = '00';
	}
	
	function enableEndDate ()
	{
		document.getElementById ('end_time_div').style.display='';
		document.getElementById ('start_time_div').style.display='';
		document.getElementById ('duration_div').style.display='none';
		document.getElementById ('useEndDate').value='true';
		document.getElementById ("durationHours").value = '00';
		document.getElementById ("durationMinutes").value = '00';
	}
	
	function toggleStartTime ()
	{
		if (document.getElementById ("toggleTheStartTime").checked == "")
		{
			// Use a start time
			enableStartTime ();
			if (document.getElementById ("toggleTheEndDate").checked == "")
			{
				// Use an end-date
				enableEndDate ();
			}
			else
			{
				disableEndDate ();
			}
		}
		else
		{
			disableStartTime ();
		}
	}

	function toggleEndDate ()
	{
		if (document.getElementById ("toggleTheEndDate").checked == "")
		{
			// Use an end-date
			enableEndDate ();
		}
		else
		{
			// Don't use an end date
			disableEndDate ();
			if (document.getElementById ("toggleTheStartTime").checked == "")
			{
				// Use a start time
				enableStartTime ();
			}
			else
			{
				disableStartTime ();
			}
		}
	}

	function toggleRecurrence ()
	{
		if (document.getElementById ("recurrenceCheckbox").checked == "")
		{
			// No recurring
			document.getElementById ("frequency").value="repeat_type_none";
			document.getElementById ('recurrence').style.display='none';
			// Activate reminders
			//document.getElementById ('tab2').style.display = '';

		}
		else
		{
			// Enable recurring
			document.getElementById ('recurrence').style.display='';
			//document.getElementById ('tab2').style.display = 'none';
		}
	}
</script>
<?php if ($showTabs) { ?>
<style>
	#tabmenu {
		color: rgb(234,242,255);;
		border-bottom: 1px solid black;
		margin: 12px 0px 20px 0px;
		padding: 0px;
		z-index: 1;
		padding-left: 10px }

	#tabmenu li {
		display: inline;
		overflow: hidden;
		list-style-type: none; }

	#tabmenu a, a.active {
		color: #aaaaaa;
		background: #cccccc;
		font: normal 1em verdana, Arial, sans-serif;
		border: 1px solid black;
		padding: 2px 5px 0px 5px;
		margin: 0px;
		text-decoration: none;
		cursor:default; }

	#tabmenu a.active {
		background: #ffffff;
		border-bottom: 1px solid #ffffff; }

	#tabmenu a:hover {
		color: #fff;
		background: #7aa6dc; }

	#tabmenu a:visited {
		color: #E8E9BE; }

	#tabmenu a.active:hover {
		background: #ffffff;
		color: #7aa6dc; }

</style>
<script type="text/javascript">
	function activate(tab)
	{
		document.getElementById("event").className = "";
		document.getElementById("eventDetail").style.display = "none";
		<?php if ($viewAction == 'modify' || $viewAction == 'show') { ?>
			document.getElementById("participation").className = "";
			document.getElementById("participationDetail").style.display = "none";
			<?php if ($remindersEnabled) { ?>
				document.getElementById("reminder").className = "";
				document.getElementById("reminderDetail").style.display= "none";
			<?php } ?>
		<?php } ?>

		document.getElementById(tab).className = "active";
		document.getElementById(tab+"Detail").style.display = "";
	}


</script>
<?php } ?>

<script type="text/javascript"/">
	function setName ()
	{
		name = document.getElementById('nameProxy').value;
		document.getElementById('name').value = name;
		return false;
	}
</script>
<?php
	include 'ext/javascript/datePicker.css';
	if (file_exists('templates/'.$_SESSION['brimTemplate'].'/datePicker.css'))
	{
		include 'templates/'.$_SESSION['brimTemplate'].'/datePicker.css';
	}
?>

<?php 
	if (isset ($renderObjects) && !$isItemOwner)
	{
		echo '<h2>'.$dictionary['youAreNotOwnerButParticipator'].'</h2>';
	}
?>
<table>
<tr>
	<td>
		<table cellpadding="0" cellspacing="0" border="0" class="nospacing">
			<tr>
				<td><img src="plugins/calendar/view/pics/months/<?php
				echo date ('n', $requestedDate) ?>.gif"></td>
			</tr>
			<tr>
				<td><img src="plugins/calendar/view/pics/days/<?php
				echo date ('j', $requestedDate) ?>.gif"></td>
			</tr>
		</table>
	</td>
	<td valign="top"><h2><?php if (isset ($pageTitle)) { echo $pageTitle; } ?></h2></td>
</tr>
<?php
	if(!empty($parameters['errors']))
	{
		echo standardTableRowErrorMessages
			($dictionary, $parameters['errors'], $icons);
	}
	if (isset ($renderObjects) || !($renderObjects->isParent))
	{
?>
	<tr>
		<td class="inputParamName">
			<?php echo $dictionary['name']; ?>:&nbsp;
		</td>
		<td class="inputParamValue">
			<input type="text" id="nameProxy"
				class="text" onkeyup="javascript:setName (); return false;"
				<?php if (isset ($renderObjects) && isset ($renderObjects->name))
				{
					echo ' value="'.$renderObjects->name.'" ';
				}
				if ($viewAction == 'show')
				{
					echo ' readonly="true" ';
				} ?>
			/>
		</td>
	</tr>
<?php } ?>
</table>
<?php 
	if ($showTabs) { 
?>
	<ul id="tabmenu" >
		<li onclick="activate('event')"><a class=""
      		id="event"><?php echo $dictionary['event']; ?></a></li>
		
		<?php 
			//
			// Now only show the other tabs if we are modifying 
			// or viewing the item (i.e. the item is already added,
			// already in the db and already has properties like
			// an id etc
			//
			if ($viewAction == 'modify' || $viewAction == 'show') 
			{ 
		?>
		<li onclick="activate('participation')"><a class=""
      		id="participation"><?php echo $dictionary['share']; ?></a></li>
      		<?php if ($remindersEnabled) { ?>
		<li onclick="activate('reminder')"><a class=""
      		id="reminder"><?php echo $dictionary['reminder']; ?></a></li>
      		<?php } ?>
		<?php } ?>
	</ul>
<?php } ?>
	<?php   include 'plugins/calendar/view/eventDetail.tpl.php';  ?>
<?php if ($showTabs) { ?>
	<?php 
		include 'plugins/calendar/view/shareDetail.tpl.php';
		include 'plugins/calendar/view/reminderDetail.tpl.php';
	?>
<?php } ?>

<?php
	if ($viewAction == 'add' || 'viewAction' == 'modify')
	{
?>
	<script type="text/javascript">
	<!--
		document.getElementById ('nameProxy').focus ();
	// -->
	</script>
<?php
	}
?>
<?php if ($showTabs)
{
?>
<script type="text/javascript">
<!--
<?php
	if ((isset ($_REQUEST['editReminder']) &&
		$_REQUEST['editReminder']=='true') && $remindersEnabled
		||
		isset ($editReminder) && $editReminder == 'true')
	{
		// Activate the reminder tab 
		echo 'activate (\'reminder\');';
	}
	else if ((isset ($_REQUEST['editParticipation']) &&
		$_REQUEST['editParticipation']=='true') && $participationEnabled
		||
		isset ($editParticipation) && $editParticipation == 'true')
	{
		// Activate the participation tab
		echo 'activate (\'participation\');';
	}
	else 
	{
		// Activate the default (event) tab 
		echo 'activate (\'event\');';
 } ?>
// -->
</script>
<?php } ?>
