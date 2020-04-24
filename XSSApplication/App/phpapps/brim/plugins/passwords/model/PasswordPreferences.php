<?php

require_once ('framework/model/PreferenceServices.php');

/**
 * The Password preferences.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2004
 * @package org.brim-project.plugins.passwords
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class PasswordPreferences extends PreferenceServices
{
	var $VIEW = 'passwordTree';
	var $YAHOOTREECOLUMNCOUNT = 'passwordYahooTreeColumnCount';

	function PasswordPreferences ()
	{
		parent::PreferenceServices ();
	}


	function getAllPreferences ($loginName)
	{
		$result = array ();
		$result [$this->VIEW]
			=$this->getPreferenceValue ($loginName, $this->VIEW);
		$result [$this->YAHOOTREECOLUMNCOUNT]
			=$this->getPreferenceValue ($loginName, $this->YAHOOTREECOLUMNCOUNT);
		return $result;
	}
}
?>