<?php

/**
 * This class offers utility methods for manipulation of classes and
 * objects.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Michael Haussmann 05/11/2003
 * @package org.brim-project.framework
 * @subpackage util
 *
 *
 * @copyright Michael Haussmann
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 * @access static
 */
class ClassHelper
{
	/**
 	 * This method casts one object into another.
	 *
	 * Some behaviours are a little bit unexpected :
	 * the object may end up with attributs that are not defined in
	 * it's class.
	 *
	 * This is a usefull workaround, but has to be re-written at some
	 * time.
	 *
	 * @author post_at_henribeige_dot_de sur le manuel PHP
	 * "All variables that equal name in both classes will be copied."
	 * (PHP Manual note by post_at_henribeige_dot_de, 03-May-2003 06:37)
	 *
	 *
	 * @static
	 * @return object a new object of type $new_classname
	 */
	function typecast($old_object, $new_classname)
	{

		/* TODO :

		Certainement � r��crire � terme.

		PB exceptions
		tester les effets de bords
		v�rifier la performance
		probl�me : cr�e des variables membres qui n'existent pas dans la classe, et qui r�sultent de la requ�te!

		*/
		if(class_exists(strtolower($new_classname))) {
			$old_serialized_object = serialize($old_object);
			$old_classname = get_class($old_object);
			$oldObjectBodySize = strlen("O:".strlen($old_classname).":".$old_classname.":");
			$objectBody = substr($old_serialized_object, $oldObjectBodySize+1);
			$new_serialized_object = 'O:' . strlen($new_classname) . ':"' . $new_classname . "\"" . $objectBody;
			return unserialize($new_serialized_object);
		}else {
			return false;
		}
	}

	/**
	 * Gets into an array all the ancestors of the given class,
	 * starting with the class itself and upwards.
	 *
	 * Example result :
	 * <pre>
	 * Array
	 * (
	 * 	[0] => me
	 * 	[1] => father
	 *	    [2] => grandfather
	 * )
	 * </pre>
	 *
	 * @author tim at correctclick dot com
	 * (PHP Manual) 05-Apr-2003 07:48
	 * @return array the ancestors of a base class
	 * @todo should 'me' be in the list of ancestors? Seems a bit odd :-)
	 */
	function get_ancestors ($class) {

		for ($classes[] = $class; $class = get_parent_class ($class); $classes[] = $class);
		return $classes;
	}
}
?>