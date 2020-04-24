<?php

require_once 'framework/util/StringUtils.php';
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
 * @version $Id: StringUtilsTestCase.php 1176 2006-03-16 10:00:44Z barrel $
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class StringUtilsTestCase extends UnitTestCase
{
	/**
	 * The stringutilities, the class that will be tested
	 * @var StringUtils the class that will be tested
	 */
	var $stringUtils;

	/**
	 * Default constructor.
	 */
	function StringUtilsTestCase ()
	{
			$this->UnitTestCase('StringUtils test');
	}

	/**
	 * Setup phase. Instantiates the StringUtils class
	 */
	function setUp ()
	{
			$this->stringUtils = new StringUtils ();
	}

	/**
	 * Teardown. Does nothing
	 */
	function tearDown ()
	{
	}

	/**
	 * Tests the urlEncodeQuotes function
	 * the input used is a string without quotes
	 */
	function testUrlEncodeQuotes1()
	{
			$input = 'This is a test without quotes';
			$expectedOutput = 'This is a test without quotes';
			$this->assertEqual (
					$expectedOutput,
					$this->stringUtils->urlEncodeQuotes($input)
			);
	}

	/**
	 * Tests the urlEncodeQuotes function
	 * the input used is a string with a single quote
	 */
	function testUrlEncodeQuotes2()
	{
			$input = "This is a test with a 'single quote";
			$expectedOutput = "This is a test with a &#39;single quote";
			$this->assertEqual (
					$expectedOutput,
					$this->stringUtils->urlEncodeQuotes($input)
			);
	}

	/**
	 * Tests the urlEncodeQuotes function
	 * the input used is a string with a double quote
	 */
	function testUrlEncodeQuotes3()
	{
			$input = 'This is a test with a "double quote';
			$expectedOutput = "This is a test with a &quot;double quote";
			$this->assertEqual (
					$expectedOutput,
					$this->stringUtils->urlEncodeQuotes($input)
			);
	}

	/**
	 * Tests the urlEncodeQuotes function
	 * the input used is a string with multiple single and double
	 * quotes
	 */
	function testUrlEncodeQuotes4()
	{
			$input = 'This \'is "a "test "with lot\'s of quote\'s';
			$expectedOutput = "This &#39;is &quot;a &quot;test &quot;with lot&#39;s of quote&#39;s";
			$this->assertEqual (
					$expectedOutput,
					$this->stringUtils->urlEncodeQuotes($input)
			);
	}

	/**
	 * Tests the startsWith function. This test tests a valid call
	 */
	function testStartsWith1 ()
	{
			$input1 = "Barry";
			$input2 = "Barry is a programmer";
			$this->assertTrue (
					$this->stringUtils->startsWith ($input2, $input1));
	}

	/**
	 * Tests the startsWith function. This test tests a call with
	 * will result in failure (i.e. the function returns false)
	 */
	function testStartsWith2 ()
	{
			$input1 = "Genevieve";
			$input2 = "Barry is a programmer";
			$this->assertFalse (
					$this->stringUtils->startsWith ($input2, $input1));
	}

	/**
	 * Tests the startsWith function. This test tests a call with
	 * null as first input parameter
	 */
	function testStartsWith3 ()
	{
			$input1 = null;
			$input2 = "Barry is a programmer";
			$this->assertFalse (
					$this->stringUtils->startsWith ($input2, $input1));
	}

	/**
	 * Tests the startsWith function. This test tests a call with
	 * null as second input parameter
	 */
	function testStartsWith4 ()
	{
			$input1 = 'Barry';
			$input2 = null;
			$this->assertFalse (
					$this->stringUtils->startsWith ($input2, $input1));
	}

	/**
	 * Tests the startsWith function. This test tests a call with
	 * null as both first and second input parameter
	 */
	function testStartsWith5 ()
	{
			$input1 = null;
			$input2 = null;
			$this->assertFalse (
					$this->stringUtils->startsWith ($input2, $input1));
	}

	/**
	 * Tests the startsWith function. This test tests a call with
	 * both input strings equal (and not null).
	 */
	function testStartsWith6 ()
	{
			$input1 = "Barry is a programmer";
			$input2 = "Barry is a programmer";
			$this->assertTrue (
					$this->stringUtils->startsWith ($input2, $input1));
	}

	/**
	 * Test for the get property function. Basic functionality is tested
	 * (getProperty ('name=value', 'name') should return 'value'
	 */
	function testGetProperty1 ()
	{
			$input = "name=value";
			$property = "name";
			$expectedOutput = "value";
			$this->assertEqual (
					$this->stringUtils->getProperty ($input, $property),
					$expectedOutput
			);
	}

	/**
	 * Test for the get property function.
	 * Test for null input
	 */
	function testGetProperty2 ()
	{
			$input = null;
			$property = "name";
			$this->assertNull (
					$this->stringUtils->getProperty ($input, $property)
			);
	}

	/**
	 * Test for the get property function.
	 * Test for null input string
	 */
	function testGetProperty3 ()
	{
			$input = "Barry";
			$property = null;
			$this->assertNull (
					$this->stringUtils->getProperty ($input, $property)
			);
	}

	/**
	 * Test for the get property function.
	 * Test with multiple equal (=) signs
	 */
	function testGetProperty4 ()
	{
			$input = "Barry=a programmer and=good";
			$property = "Barry";
			$expectedOutput = "a programmer and=good";
			$this->assertEqual (
					$this->stringUtils->getProperty ($input, $property),
					$expectedOutput
			);
	}

	/**
	 * Test the truncating functionality
	 * Basic input with inputString length bigger than requested
	 * length (by default, dots are added)
	 */
	function testTruncate1 ()
	{
			$input = "Barry is a programmer";
			$expectedOutput = "Barry...";
			$this->assertEqual (
					$this->stringUtils->truncate ($input, 8),
					$expectedOutput
			);
	}

	/**
	 * Test the truncating functionality
	 * Basic input with inputString length bigger than requested
	 * length (ask for no dots added)
	 */
	function testTruncate2 ()
	{
			$input = "Barry is a programmer";
			$expectedOutput = "Barry";
			$this->assertEqual (
					$this->stringUtils->truncate ($input, 5, ''),
					$expectedOutput
			);
	}

	/**
	 * Test the truncating functionality
	 * Basic input with inputString length bigger than requested
	 * length (ask for no dots added)
	 */
	function testTruncate3 ()
	{
			$input = "Barry is a programmer";
			$expectedOutput = "Barry is a programmer";
			$this->assertEqual (
					$this->stringUtils->truncate ($input, 100),
					$expectedOutput
			);
	}

	/**
	 * Test the truncating functionality
	 * Basic input with inputString length bigger than requested
	 * length (ask for no dots added)
	 */
	function testTruncate4 ()
	{
			$input = "Barry is a programmer";
			$expectedOutput = "Barry is a programmer";
			$this->assertEqual (
					$this->stringUtils->truncate ($input, 21, ''),
					$expectedOutput
			);
	}

	/**
	 * Test the truncating functionality
	 * Basic input with inputString length bigger than requested
	 * length (ask for no dots added)
	 */
	function testTruncate5 ()
	{
			$input = "Barry is a programmer";
			$expectedOutput = "Barry is a programmer";
			$this->assertEqual (
					$this->stringUtils->truncate ($input, 30, ''),
					$expectedOutput
			);
	}

}
?>
