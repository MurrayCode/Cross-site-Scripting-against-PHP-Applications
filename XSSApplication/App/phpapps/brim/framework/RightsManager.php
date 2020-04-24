<?php

/**
 * The RightsManager (abstract base class, or interface).
 * Contribution to the Brim project.
 * May be implemented in 2 ways : by the plugin or by a facade to
 * the adaptor application.
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
 * @copyright Michael Haussmann
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class RightsManager
{
	/**
	 * Empty constructor
	 */
	function RightsManager ()
	{}

	/**
	 * Abstract method
	 * "phpolymorphism" : if $item is an int, the id is evaluated, not the object
	 * @todo shouldn't the username be part of this methods signature?
	 */
	function isGranted ($item, $action = "")
	{}
}
?>