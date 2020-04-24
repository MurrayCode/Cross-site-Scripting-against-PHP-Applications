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


//$calendarRenderUtils = new CalendarRenderUtils ($dictionary);
$calendarRenderUtils = new CalendarRenderUtils ($configuration);
if (gettype ($requestedDate) == 'string')
{
	die ('showMonth receives requestedDate in string, should be integer');
}
$day = date ('d', $requestedDate);
$month = date ('m', $requestedDate);
$year = date ('Y', $requestedDate);
$startDay = $_SESSION['calendarStartOfWeek'];

	//echo ('<h1>'.$calendarRenderUtils->currentMonthHeader ($month, $year).'</h1>');
	echo ('<table width="100%">');

	echo ('<tr>');
	echo ('<td colpspan="3">');

	echo ('</tr>');
	echo ('<tr>');
	echo ('<td>');
	if ($month == 1)
	{
		echo ('<a href="index.php?plugin=calendar&amp;action=showMonth&month=12&year='.
			($year-1).'">'.$dictionary['month12'].'</a>');
		echo $calendarRenderUtils->displaySmallTable (12, $year-1, $previousMonthValues, $startDay, $requestedDate);
	}
	else
	{
		if (($month-1) < 10)
		{
			$name ='month0'.($month -1);
		}
		else
		{
			$name ='month'.($month -1);
		}
		echo ('<a href="index.php?plugin=calendar&amp;action=showMonth&month='.
			($month-1).'&year='.$year.'">'.
			$dictionary[$name].'</a>');
		echo $calendarRenderUtils->displaySmallTable ($month-1, $year, $previousMonthValues, $startDay , $requestedDate);
	}
	echo ('</td>');
	echo ('<td width="80%" align="center" valign="center">');
	echo ('<h1>'.$calendarRenderUtils->currentMonthHeader ($month, $year).'</h1>');
	echo ('</td>');
	//echo ('<td width="80%">&nbsp;</td>');
	echo ('<td align="right">');
	if ($month == 12)
	{
		echo ('<a href="index.php?plugin=calendar&amp;action=showMonth&month=1&year='.
			($year+1).'">'.$dictionary['month01'].'</a>');
	echo $calendarRenderUtils->displaySmallTable (1, $year+1, $nextMonthValues, $startDay, $requestedDate);
	}
	else
	{
		if (($month+1) < 10)
		{
			$name ='month0'.($month +1);
		}
		else
		{
			$name ='month'.($month +1);
		}
		echo ('<a href="index.php?plugin=calendar&amp;action=showMonth&month='.
			($month+1).'&year='.$year.'">'.
			$dictionary[$name].'</a>');
		echo $calendarRenderUtils->displaySmallTable ($month+1, $year, $nextMonthValues, $startDay, $requestedDate);
	}
	echo ('</td>');
	echo ('</tr>');
	echo ('<tr>');
	echo ('<td colspan="3">');
	echo $calendarRenderUtils->displayBigTable ($month, $year, $renderObjects, $startDay);
	echo ('</td>');
	echo ('</tr>');
	echo ('</table>');
?>
