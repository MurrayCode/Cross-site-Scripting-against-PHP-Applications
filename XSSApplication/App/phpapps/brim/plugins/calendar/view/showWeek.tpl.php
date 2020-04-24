<?php

require_once ('plugins/calendar/view/CalendarRenderUtils.php');

/**
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
if (is_string ($requestedDate))
{
	die ('ShowWeek receives requestedDate in invalid format');
}
//echo ('<pre> ');
//die (print_r ($renderObjects));

$day = date ('d', $requestedDate);
$month = date ('m', $requestedDate);
$year = date ('Y', $requestedDate);
$startDay = $_SESSION['calendarStartOfWeek'];

//
// Last day previous week is firstDayOfWeek minus one day
//
$lastDayPreviousWeek = mktime (0, 0, 0, date ('m', $firstDayOfWeek), date ('d', $firstDayOfWeek)-1, date ('Y', $firstDayOfWeek));
//
// First day next week is firstDayOfWeek plus seven days
//
$firstDayNextWeek = mktime (0, 0, 0, date ('m', $firstDayOfWeek), date ('d', $firstDayOfWeek)+7, date ('Y', $firstDayOfWeek));



include ('templates/'.$_SESSION['brimTemplate'].'/icons.inc');
//
// Build up a proper configuration for the tree display.
//
$configuration['icons']=$icons;
$configuration['dictionary']=$dictionary;
$configuration['callback']='index.php?plugin=calendar';

if (isset ($_SESSION['calendarOverlib']))
{
	$configuration ['overlib'] = $_SESSION['calendarOverlib'];
}
else
{
	$configuration ['overlib'] = true;
}


$calendarRenderUtils = new CalendarRenderUtils ($configuration);

if ($startDay == 0) 
{
	echo ('<h1>'.$dictionary['week'].'&nbsp;'.date ('W',
		$requestedDate + 86400).'</h2>');
} 
else 
{
	echo ('<h2>'.$dictionary['week'].'&nbsp;'.date ('W',
		$requestedDate).'</h2>');
}
echo ('
	<div id="calendarShowWeek">
	<table width="100%">
	<tr>
		<td align="left"><a href="index.php?plugin=calendar&amp;action=showWeek&date='.$lastDayPreviousWeek.'">&lt;&lt;</a></td>
		<td align="right"><a href="index.php?plugin=calendar&amp;action=showWeek&date='.$firstDayNextWeek.'">&gt&gt;</a></td>
	</tr>
	<tr>
		<td colspan="2">
		');
			echo ($calendarRenderUtils->getWeekOverview ($requestedDate, $renderObjects, $firstDayOfWeek, $startDay));
		echo ('
		</td>
	</tr>
	</table>
	</div>		');
?>
