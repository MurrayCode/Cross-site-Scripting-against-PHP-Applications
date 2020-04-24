<?php

require_once ('framework/model/PreferenceServices.php');

/**
 * The Calendar preferences.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - June 2004
 * @package org.brim-project.plugins.calendar
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class CalendarPreferences extends PreferenceServices
{
	var $START_OF_WEEK = 'calendarStartOfWeek';
	var $OVERLIB = 'calendarOverlib';
	var $DEFAULT_VIEW = 'calendarDefaultView';

	function CalendarPreferences ()
	{
		parent::PreferenceServices ();
	}

	function getAllPreferences ($loginName)
	{
		$result = array ();
		$result [$this->OVERLIB]
			=$this->getPreferenceValue ($loginName, $this->OVERLIB);
		$result [$this->START_OF_WEEK]
			=$this->getPreferenceValue ($loginName, $this->START_OF_WEEK);
		$result [$this->DEFAULT_VIEW]
			=$this->getPreferenceValue ($loginName, $this->DEFAULT_VIEW);
		return $result;
	}
}
?>