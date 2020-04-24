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

if (gettype ($requestedDate) == 'string')
{
	die ('ShowYear receives requestedDate as string, should be integer');
}
$day = date ('d', $requestedDate);
$month = date ('m', $requestedDate);
$year = date ('Y', $requestedDate);
$startDay = $_SESSION['calendarStartOfWeek'];
$requestedDate = date ('Y-m-d');

	echo ('<h1>'.$year.'</h1>');
	echo ('<table>');

	echo ('<tr valign="top">');
	echo ('<td align="left">');
	echo ('<a href="index.php?plugin=calendar&amp;action=showYear&year=');
	echo (($year-1).'">&lt;&lt;</a>');
	echo ('</td>');
	echo ('<td>');
	echo ('</td>');
	echo ('<td align="right">');
	echo ('<a href="index.php?plugin=calendar&amp;action=showYear&year=');
	echo (($year+1).'">&gt;&gt;</a>');
	echo ('</td>');
	echo ('</tr>');

	echo ('<tr valign="top">');
	echo ('<td>');
	echo ('<h2>');
	echo ($calendarRenderUtils->currentMonthHeader ('01', $year));
	echo ('</h2>');
	echo ($calendarRenderUtils->displaySmallTable ('01', $year,$renderObjects[0], $startDay, $requestedDate));
	echo ('</td>');
	echo ('<td>');
	echo ('<h2>');
	echo ($calendarRenderUtils->currentMonthHeader ('02', $year));
	echo ('</h2>');
	echo ($calendarRenderUtils->displaySmallTable ('02', $year, $renderObjects[1], $startDay, $requestedDate));
	echo ('</td>');
	echo ('<td>');
	echo ('<h2>');
	echo ($calendarRenderUtils->currentMonthHeader ('03', $year));
	echo ('</h2>');
	echo ($calendarRenderUtils->displaySmallTable ('03', $year, $renderObjects[2], $startDay, $requestedDate));
	echo ('</td>');
	echo ('</tr>');


	echo ('<tr valign="top">');
	echo ('<td>');
	echo ('<h2>');
	echo ($calendarRenderUtils->currentMonthHeader ('04', $year));
	echo ('</h2>');
	echo ($calendarRenderUtils->displaySmallTable ('04', $year, $renderObjects[3], $startDay, $requestedDate));
	echo ('</td>');
	echo ('<td>');
	echo ('<h2>');
	echo ($calendarRenderUtils->currentMonthHeader ('05', $year));
	echo ('</h2>');
	echo ($calendarRenderUtils->displaySmallTable ('05', $year, $renderObjects[4], $startDay, $requestedDate));
	echo ('</td>');
	echo ('<td>');
	echo ('<h2>');
	echo ($calendarRenderUtils->currentMonthHeader ('06', $year));
	echo ('</h2>');
	echo ($calendarRenderUtils->displaySmallTable ('06', $year, $renderObjects[5], $startDay, $requestedDate));
	echo ('</td>');
	echo ('</tr>');

	echo ('<tr valign="top">');
	echo ('<td>');
	echo ('<h2>');
	echo ($calendarRenderUtils->currentMonthHeader ('07', $year));
	echo ('</h2>');
	echo ($calendarRenderUtils->displaySmallTable ('07', $year, $renderObjects[6], $startDay, $requestedDate));
	echo ('</td>');
	echo ('<td>');
	echo ('<h2>');
	echo ($calendarRenderUtils->currentMonthHeader ('08', $year));
	echo ('</h2>');
	echo ($calendarRenderUtils->displaySmallTable ('08', $year, $renderObjects[7], $startDay, $requestedDate));
	echo ('</td>');
	echo ('<td>');
	echo ('<h2>');
	echo ($calendarRenderUtils->currentMonthHeader ('09', $year));
	echo ('</h2>');
	echo ($calendarRenderUtils->displaySmallTable ('09', $year, $renderObjects[8], $startDay, $requestedDate));
	echo ('</td>');
	echo ('</tr>');

	echo ('<tr valign="top">');
	echo ('<td>');
	echo ('<h2>');
	echo ($calendarRenderUtils->currentMonthHeader (10, $year));
	echo ('</h2>');
	echo ($calendarRenderUtils->displaySmallTable ('10', $year, $renderObjects[9], $startDay, $requestedDate));
	echo ('</td>');
	echo ('<td>');
	echo ('<h2>');
	echo ($calendarRenderUtils->currentMonthHeader (11, $year));
	echo ('</h2>');
	echo ($calendarRenderUtils->displaySmallTable ('11', $year, $renderObjects[10], $startDay, $requestedDate));
	echo ('</td>');
	echo ('<td>');
	echo ('<h2>');
	echo ($calendarRenderUtils->currentMonthHeader (12, $year));
	echo ('</h2>');
	echo ($calendarRenderUtils->displaySmallTable ('12', $year, $renderObjects[11], $startDay, $requestedDate));
	echo ('</td>');
	echo ('</tr>');

	echo ('</table>');
?>
