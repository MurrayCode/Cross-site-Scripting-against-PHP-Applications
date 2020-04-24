<?php

require_once ('framework/model/PreferenceServices.php');

/**
 * The Task preferences.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - February 2004
 * @package org.brim-project.plugins.tasks
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class TaskPreferences extends PreferenceServices
{
	var $VIEW = 'taskTree';
	var $OVERLIB = 'taskOverlib';
	var $YAHOOTREECOLUMNCOUNT = 'taskYaskahooTreeColumnCount';
	var $HIDECOMPLETED = 'taskHideCompleted';

	function TaskPreferences ()
	{
		parent::PreferenceServices ();
	}


	function getAllPreferences ($loginName)
	{
		$result = array ();
		$result [$this->VIEW]
			=$this->getPreferenceValue ($loginName, $this->VIEW);
		$result [$this->OVERLIB]
			=$this->getPreferenceValue ($loginName, $this->OVERLIB);
		$result [$this->YAHOOTREECOLUMNCOUNT]
			=$this->getPreferenceValue ($loginName, $this->YAHOOTREECOLUMNCOUNT);
		$result [$this->HIDECOMPLETED]
			=$this->getPreferenceValue ($loginName, $this->HIDECOMPLETED);

		return $result;
	}
}
?>