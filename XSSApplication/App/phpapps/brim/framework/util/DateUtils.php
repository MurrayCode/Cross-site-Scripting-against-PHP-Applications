<?php

/**
 * Generic date utilities.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - December 2003
 * @package org.brim-project.framework
 * @subpackage util
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 * @todo These functions need to be throughly tested
 */
class DateUtils
{
	/**
	 * Default empty constructor
	 */
	function DateUtils ()
	{
	}

	/**
	 * This function calculates the difference between two dates in seconds
	 *
	 * @param string firstDate the first date used for comparision
	 * @param string secondDate the second date used for comparision
	 * @return int the difference between the two dates in seconds
	 */
	function diffInSeconds ($firstDate, $secondDate)
	{
		$diff = $firstDate  - $secondDate;
		return $diff;
	}

	/**
	 * This function calculates the difference between two dates in minutes
	 *
	 * @param string firstDate the first date used for comparision
	 * @param string secondDate the second date used for comparision
	 * @return int the difference between the two dates in minutes
	 */
	function diffInMinutes ($firstDate, $secondDate)
	{
		$diff = $firstDate  - $secondDate;
		$minuteInSecs = 60;
		return ($diff / $minuteInSecs);
	}

	/**
	 * This function calculates the difference between two dates in hours
	 *
	 * @param string firstDate the first date used for comparision
	 * @param string secondDate the second date used for comparision
	 * @return int the difference between the two dates in hours
	 */
	function diffInHours ($firstDate, $secondDate)
	{
		$diff = $firstDate  - $secondDate;
		$hoursInSecs = 60 * 60;
		return ($diff / $hoursInSecs);
	}

	/**
	 * This function calculates the difference between two dates in days
	 *
	 * @param string firstDate the first date used for comparision
	 * @param string secondDate the second date used for comparision
	 * @return int the difference between the two dates in days
	 */
	function diffInDays ($firstDate, $secondDate)
	{
		$diff = $firstDate  - $secondDate;
		$dayInSecs = 60 * 60 * 24;
		return ($diff/$dayInSecs);
	}

	/**
	 * This function calculates the difference between two dates in weeks
	 *
	 * @param string firstDate the first date used for comparision
	 * @param string secondDate the second date used for comparision
	 * @return int the difference between the two dates in weeks
	 */
	function diffInWeeks ($firstDate, $secondDate)
	{
		$diff = $firstDate  - $secondDate;
		$weekInSecs = 60 * 60 * 24 * 7;
		return ($diff/$weekInSecs);
	}

	/**
	 * Returns the number of the current month; i.e.: january ->1
	 * and april -> 4
	 * @return int the number of the current month
	 */
	function getCurrentMonthNumber ()
	{
		return date('m');
	}

	/**
	 * Gets the number of the current day in the month (without leading zero's).
	 * @return int a number between 1 and 31
	 */
	function getCurrentDayInMonthNumber ()
	{
		return date ('j');
	}

	/**
	 * Returns the current year
	 * @return int the current year
	 */
	function getCurrentYear ()
	{
		return date ('Y');
	}

	/**
	 * Returns the day as a number between 1 and 31 from the given date
	 *
	 * @param string $theDate the input date in string format
	 * @return int the day in the month
	 */
	function getDayInMonthFromDate ($theDate)
	{
		if (gettype($theDate) == 'string')
		{
			$theDate = strtotime ($theDate);
		}
		$dateArray = getdate ($theDate);
		return $dateArray ['mday'];
	}

	/**
	 * Returns the year of the specified date
	 * @param string $theDate the input date in string format
	 * @return int the year of the specified input date
	 */
	function getYearFromDate ($theDate)
	{
		if (gettype($theDate) == 'string')
		{
			$theDate = strtotime ($theDate);
		}
		$dateArray = getdate ($theDate);
		return $dateArray ['year'];
	}

	/**
	 * Returns the month (as a number between 1 and 12) of the current
	 * date
	 *
	 * @param string $theDate the input date in string format
	 * @return int the month as number between 1 and 12 of
	 * the specified input date
	 */
	function getMonthFromDate ($theDate)
	{
		if (gettype($theDate) == 'string')
		{
			$theDate = strtotime ($theDate);
		}
		$dateArray = getdate ($theDate);
		return $dateArray ['mon'];
	}

	/**
	 * Returns the hours (as a number between 1 and 24) of the current
	 * date
	 *
	 * @param string $theDate the input date in string format
	 * @return int the hour as number between 1 and 24 of
	 * the specified input date
	 */
	function getHoursFromDate ($theDate)
	{
		if (gettype($theDate) == 'string')
		{
			$theDate = strtotime ($theDate);
		}
		$dateArray = getdate ($theDate);
		return $dateArray ['hours'];
	}

	/**
	 * Returns the minutes (as a number between 01 and 60) of the
	 * current date
	 *
	 * @param string $theDate the input date in string format
	 * @return int the minutes as number between 01 and 60 of
	 * the specified input date
	 */
	function getMinutesFromDate ($theDate)
	{
		if (gettype($theDate) == 'string')
		{
			$theDate = strtotime ($theDate);
		}
		$dateArray = getdate ($theDate);
		$result = $dateArray ['minutes'];
		if ($result < 10)
		{
			$result = '0'.$result;
		}
		return $result;
	}

	/**
	 * Adds hours to a date
	 *
	 * @param date $date the date to which we would like to add the hours
	 * @param hours $hours the number of hours we would like to add
	 * @return date a new date with the number of hours added
	 */
	function addHours ($date, $hours)
	{
		return $this->addMinutes ($date, $hours*60);
	}

	/**
	 * Adds minutes to a date
	 *
	 * @param date $date the date to which we would like to add the minutes
	 * @param integer $minutes the number of minutes we would like to add
	 * @return date a new date with the number of minutes added
	 */
	function addMinutes ($date, $minutes)
	{
		return $this->addSeconds ($date, $minutes*60);
	}

	/**
	 * Adds seconds to a date
	 *
	 * @param date $date the date to which we would like to add the seconds
	 * @param integer $seconds the number of seconds we would like to add
	 * @return date a new date with the number of seconds added
	 */
	function addSeconds ($date, $seconds)
	{
		return new date ("Y-m-d H:i:s", strtotime ($date) + $seconds);
	}

}
?>