<?php
include_once ('framework/util/globalFunctions.php');
include_once ('framework/util/StringUtils.php');
require_class("VariableValidator", 
	"framework/util/request/VariableValidator.class.php");
require_class("RequestCastConfiguration", 
	"framework/util/request/RequestCastConfiguration.class.php");

/**
 * The RequestCast class is used in combination with a factory to 
 * instantiate an object from a request.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following 
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 * 
 * <pre> Enjoy :-) </pre>
 *
 * @author Michael Haussmann - 12/02/2004
 * @package org.brim-project.framework
 * @subpackage util.request
 *
 * @copyright Michael Haussmann
 *
 * @license http://opensource.org/licenses/gpl-license.php 
 * The GNU Public License
 * @todo Needs a slashHandler to be plugged before !
 */
class RequestCast 
{
	
	/**
	 * The configuration for mapping a request to an object
	 *
	 * @access protected
	 * @var object configuration of the type RequestCastConfiguration
	 */
	var $configuration;
	
	/**
	 * The classname to cast to
	 *
	 * @access private
	 * @var string
	 */
	var $className;
	
	/**
	 * The method that lead to this request (either <code>GET</code> or 
	 * <code>POST</code>)
	 *
	 * @access private
	 * @var string
	 */
	var $method;
	
	/**
	 * Validation flag
	 *
	 * @todo null? Shouldn't this be false?
	 */
	var $valid = null;
	
	/**
	 * The validator is a delegate class
	 *
	 * @var object validator
	 */
	var $validator;
	
	/**
	 * the stringutilities
	 */
	var $stringUtils;

	/**
	 * Default constructor
	 *
	 * @param string classname the classname for the request
	 * @param string method the methos (either <code>GET</code> or 
	 * <code>POST</code>)
	 */
	function RequestCast($className, $method = "POST")
	{
		$this->className = $className;
		$this->method = "_".$method;
		$this->validator = new VariableValidator();
		$configurater = new RequestCastConfiguration();
		$this->configuration = 
			$configurater->getFieldMapping($this->className);
		$this->stringUtils = new StringUtils ();
	}
	
	/**
	 * Returns whether the request is valid
	 *
	 * @return <code>true</code> is valid, <code>false</code> otherwise
	 * @see validate 
	 */
	function isValid()
	{
		if(isset ($this->valid))
		{ 
			return $this->valid;	
		}
		$this->validate();
		return $this->valid;
	}
	
	/**
	 * Validate the request
	 */
	function validate()
	{
		
		$method = $this->method;
		if (isset ($this->configuration))
		{
			foreach ($this->configuration as $mapping)
			{
				/*
				if($method == "POST")
				{
					$this->validator->validate
						($_POST[$mapping["name"]], 
						$mapping["min"], 
						$mapping["max"], 
						$mapping["type"], 
						$mapping["message"]);
				}
				elseif($method == "GET")
				{
					$validator->validate
						($_GET[$mapping["name"]], 
						$mapping["min"], 
						$mapping["max"], 
						$mapping["type"], 
						$mapping["message"]);
				}
				else
				{
				}
				*/
				// FIXME : variable variable approach does not work 
				// $this->validator->validate
				//	(${${method}}[$mapping["name"]], 
				//		$mapping["min"], 
				//		$mapping["max"], 
				//		$mapping["type"], 
				//		$mapping["message"]);
				if (isset ($_REQUEST[$mapping['name']]))
				{
					$this->validator->validate
						($_REQUEST[$mapping["name"]], 
						$mapping["min"], 
						$mapping["max"], 
						$mapping["type"], 
						$mapping["message"]);
				}
			}
			if (isset ($this->validator->isOk))
			{
				$this->valid = $this->validator->isOk;
			}
		}
	}
	
	/**
	 * An error has occured, add it to the list
	 *
	 * @param string message the error message
	 */
	function addError($message = "ERROR")
	{
		$this->valid = false;
		$this->validator->set_error($message);
	}

	/**
	 * Retrieve the error messages that have occured during the cast
	 *
	 * @return the error messages
	 */	
	function getErrorMessages()
	{
		if ($this->isValid()) 
		{
			return array();
		}
		else 
		{
			return $this->validator->get_error_msg();
		}
	}
	
	
	/**
	 * cast means both validation and cast
	 *
	 * @param force means that we cast even if there are errors
	 * @return object the instantiated object
	 */
	function cast($force=true)
	{
		$object = null;
		if(!$this->isValid() && !$force) 
		{
				return false;
		}
		$method = $this->method; // FIXME 
		foreach ($this->configuration as $mapping)
		{
			// If the value is not in the REQUEST, we apply the default
			//echo "checking".bon(${${method}}[$mapping["name"]]);
			if(isset ($_REQUEST[$mapping["field"]]))
			{
				// very variable variables...
				$object->{$mapping["name"]} = 
					$this->stringUtils->gpcStripSlashes (
							$_REQUEST[$mapping["field"]]);
			}
			else
			{
				$object->{$mapping["name"]} = $mapping["default"];
			}
		}	
		//$item = ClassHelper::typecast($object, $this->className);
		//die (print_r ($item));
		return ClassHelper::typecast($object, $this->className);
	}
	
	/**
	 * Updates an item by handling the request, 
  	 * cast means both validation and cast
   	 *
	 * @param item the item to be updated
	 * @param force means that we cast even if there are errors
	 * @return object the updated item
	 */
	function castUpdate($item, $force=true)
	{
		//die (print_r ($item));
		if(!$this->isValid() && !$force) 
		{
			return false;
		}
		$method = $this->method; // FIXME 
		foreach ($this->configuration as $mapping)
		{
			// If the value is not in the REQUEST, we do not apply 
			// anything
			if(isset ($_REQUEST[$mapping["field"]]))
			{
				$item->{$mapping["name"]} = 
					$this->stringUtils->gpcStripSlashes (
						$_REQUEST[$mapping["field"]]);
			}
		}	
		return $item;
	}
}
?>
