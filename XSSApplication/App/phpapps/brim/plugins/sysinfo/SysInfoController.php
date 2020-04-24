<?php

require_once ('framework/Controller.php');
require_once ('plugins/sysinfo/model/SysInfoServices.php');

/**
 * The SysInfo controller
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - June 2004
 * @package org.brim-project.plugins.sysinfo
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class SysInfoController extends Controller
{
	/**
	 * Default constructor
	 */
	function SysInfoController ()
	{
		parent::Controller ();
		$this->title = 'Brim - SysInfo';
		$this->pluginName = 'sysinfo';
		$this->itemName = 'SysInfo';
		$this->operations = new SysInfoServices ();
	}

	function getActions ()
	{
	}

	function activate ()
	{
		switch ($this->getAction ())
		{
			case 'databaseDump':
				$this->renderObjects= $this->operations->databaseDump();
				Header ('Content-type: text/plain');
				echo ($this->renderObjects);
				exit ();
				break;
			default:
				$this->renderObjects= $this->operations->getSysInfo();
				$this->renderer = 'sysinfo';
		}
	}
}
?>