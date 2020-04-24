<?php

require_once ('framework/RightsManager.php');

/**
 * The RightsManager implementation for this plugin.
 * Contribution to the Brim project.
 * Encapsulates the RightsManager class (adaptor/facade pattern)
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Michael Haussmann - February 2004
 * @package org.brim-project.framework
 *
 * @copyright (c) 2004 Michael Haussmann
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class RightsManagerImpl extends RightsManager
{

	/**
	 * Returns whether we are allowed to perform a certain action on a certain
	 * object.
	 * "phpolymorphism" : if $item is an int, the itemId is evaluated, not the
	 * item object
	 *
	 * @param object the object on which we would like to perform an action
	 * @param string actionString the action we would like to perform
	 * @return boolean <code>true</code> if the action is granted,
	 *		<code>false</code> otherwise
	 * @todo shouldn't the username be part of the methods signature?
	 */
	function isGranted ($item, $actionString = "")
	{
		switch ($actionString)
		{
			case "":
			case "add":
			case "modify":
			case "preferences":
			case "search":
			case "view":
			case "sort":
			default:
				return true;
		}
	}
}
?>