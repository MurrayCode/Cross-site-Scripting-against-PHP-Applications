<?php

require_once 'framework/util/ArrayUtils.php';
require_once 'ext/simpletest/unit_tester.php';

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following 
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 * 
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 * @subpackage util.test
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php 
 * The GNU Public License
 */
class ArrayUtilsTestCase extends UnitTestCase
{
	/**
	 * The arrayutilities, the class that will be tested
	 * @var arrayUtils the class that will be tested
	 */
	var $arrayUtils;

	/**
	 * Default constructor. 
	 */
	function ArrayUtilsTestCase ()
	{
			$this->UnitTestCase('ArrayUtils test');
	}

	/**
	 * Setup phase. Instantiates the StringUtils class
	 */
	function setUp ()
	{
			$this->arrayUtils = new ArrayUtils ();
	}

	/**
	 * Teardown. Does nothing
	 */
	function tearDown ()
	{
	}

	function testImplodeWithKeys1 ()
	{
		$glue=', ';
		$inputArray = array (
				'firstName'=>'firstValue', 
				'secondName'=>'secondValue', 
				'thirdName'=>'thirdValue', 
				'fourthName'=>'fourthValue'
		);
		$expectedOutput = 'firstName=firstValue, secondName=secondValue, thirdName=thirdValue, fourthName=fourthValue';
		$this->assertEqual (
				$this->arrayUtils->implode_with_keys 
					($glue, $inputArray),
				$expectedOutput
		);
	}

	function testExplodeWithKeys1 ()
	{
		$separator = ', ';
		$inputString= 'firstName=firstValue, secondName=secondValue, thirdName=thirdValue, fourthName=fourthValue';
		$expectedOutput = array (
				'firstName'=>'firstValue', 
				'secondName'=>'secondValue', 
				'thirdName'=>'thirdValue', 
				'fourthName'=>'fourthValue'
		);
		$this->assertEqual (
				$this->arrayUtils->explode_with_keys 
					($separator, $inputString),
				$expectedOutput
		);
	}
}
?>
