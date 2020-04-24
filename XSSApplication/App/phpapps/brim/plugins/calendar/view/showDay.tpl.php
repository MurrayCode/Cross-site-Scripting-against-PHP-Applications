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
if (gettype ($requestedDate) == 'string')
{
	die ('showDay receives requestedDate in string, should be integer');
}
$day = date ('d', $requestedDate);
$month = date ('m', $requestedDate);
$year = date ('Y', $requestedDate);
$startDay = $_SESSION['calendarStartOfWeek'];

$previousMonth = mktime(0, 0, 0, $month-1, $day, $year);
$nextMonth = mktime(0, 0, 0, $month+1, $day, $year);

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


	//$calendarRenderUtils = new CalendarRenderUtils ($dictionary);
	$calendarRenderUtils = new CalendarRenderUtils ($configuration);

	//
	// Display the current day as header
	//
	echo ('<h1>'.$calendarRenderUtils->currentDayHeader
		($day, $month, $year).'</h1>');

	echo ('
	<table width="100%" cellspacing="5" cellpadding="5">
	<tr>
		<td width="80%">
			<table>
				<tr>
					<td align="left">
						<a href="index.php?plugin=calendar&amp;action=showDay&date='.($requestedDate-(60*60*24)).'">&lt;&lt;</a>
					</td>
					<td width="100%">&nbsp;</td>
					<td align="right">
						<a href="index.php?plugin=calendar&amp;action=showDay&date='.($requestedDate+(60*60*24)).'">&gt;&gt;</a>
					</td>
				</tr>
			</table>
		</td>
		<td>
			<table>
				<tr>
					<td align="left">
						<a href="index.php?plugin=calendar&amp;action=showDay&date='.$previousMonth.'">&lt;&lt;</a>
					</td>
					<td width="100%">&nbsp;</td>
					<td align="right">
						<a href="index.php?plugin=calendar&amp;action=showDay&date='.$nextMonth.'">&gt;&gt;</a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="100%">
			<table width="100%" cellspacing="0" cellpadding="0">
			'.$calendarRenderUtils->getDayOverview($requestedDate, $renderObjects).'
			</table>
		</td>
		<td align="right" valign="top">
			'.$calendarRenderUtils->displaySmallTable
				($month, $year, $currentMonthValues, $startDay, $requestedDate).'
		</td>
	</tr>
	</table>');
?>