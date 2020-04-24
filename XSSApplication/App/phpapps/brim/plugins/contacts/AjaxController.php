<?php

require_once ('plugins/contacts/model/ContactServices.php');
require_once ('ext/JSON.php');
require_once ('framework/util/StringUtils.php');


/**
 * The  Ajax Controller. This class is some sort of guardian, since
 * only the functions in this class can be called...
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2006
 * @package org.brim-project.plugins.contacts
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class AjaxController
{
	/**
 	 * StringUtilities
	 *
	 * @var object
	 */
	var $stringUtils;

	/**
	 * The services
	 *
	 * @var object
	 */
	var $services;
	
	
	/**
	 * The JSON libary
	 *
	 * @var object
	 */
	var $json;

	/**
	 * Default constructor, instantiates the services and the JSON library
	 *
	 * @return AjaxController
	 */
	function AjaxController ()
	{
		$this->services = new ContactServices();
		$this->json = new Services_JSON();
		$this->stringUtils = new StringUtils ();
	}

	
	/**
	 * Change an item.
	 * 
	 * @param array args, an attay containing a key called 'xxx_id', where
	 * 'xxx' indicates the field to change (i.e. 'name_12' indicates that we 
	 * would like to change the name for item with id 12) and 'value', with the new
	 * value.
	 * 
	 * @return  string the value that has changed
	 * @todo check the result (JSON is not possible?(
	 * @todo at present the values are retrieved from the request and not from the arguments
	 */
	function change ($args)
	{
		$toChange = split ("_", $_REQUEST ['id']);
		$itemId = $toChange [1];
        $item = $this->services->getItem ($_SESSION['brimUsername'], $itemId);
		// 
		// Check if we have the right to change
		//
        if ($item->owner != $_SESSION['brimUsername'])
        {
            $status ['error']='Invalid access';
        }
		else
		{
			$value = $this->stringUtils->gpcStripSlashes ($_REQUEST['value']);
			// 
			// We can either change name, mobile, telhome or telwork
			//
			switch ($toChange [0])
			{
				case 'name':
					$item->name = $value;
					$status['itemId'] = $itemId;
					$status['result'] = $item->name;
					break;
				case 'mobile':
					$item->mobile = $value;
					$status['itemId'] = $itemId;
					$status['result'] = $item->mobile;
					break;
				case 'telhome':
					$item->tel_home = $value;
					$status['itemId'] = $itemId;
					$status['result'] = $item->tel_home;
					break;
				case 'telwork':
					$item->tel_work = $value;
					$status['itemId'] = $itemId;
					$status['result'] = $item->tel_work;
					break;
				default:
            		$status ['error']='Invalid access';
			}
			$this->services->modifyItem ($_SESSION['brimUsername'], $item);
		}
		//return ($this->json->encode ($status));
		return $value;
	}

	/**
	 * Trash an item.
	 * 
	 * @param array args, the arguments that must contain an itemId
	 * @return string a JSON encoded status message (either contains
	 * an error or a message)
	 */
	function trash ($args)
	{
		$itemId = $args['itemId'];
        $item = $this->services->getItem ($_SESSION['brimUsername'], $itemId);
		//
		// Do we have the right to trash this item?
		//
        if ($item->owner != $_SESSION['brimUsername'])
        {
            $status ['error']='Invalid access';
        }
		else
		{
			$this->services->trash ($_SESSION['brimUsername'], $itemId);
            $status ['msg']='Item trashed saved';
		}
		return ($this->json->encode ($status));
	}


	/**
	 * Move an item to a new parent
	 * 
	 * @param array args, arguments containing at least an itemId  and a parentId
	 *
	 * @return string a JSON encoded status message (either contains
	 * an error or a message)
	 */
	function moveItem ($args)
	{
		$itemId = $args['itemId'];
		$parentId = $args['parentId'];
        $item = $this->services->getItem ($_SESSION['brimUsername'], $itemId);
        if ($item->owner != $_SESSION['brimUsername'])
        {
            $status ['error']='Invalid access';
        }
		else
		{
			$item->parentId = $parentId;
			$this->services->modifyItem ($_SESSION['brimUsername'], $item);
			$status ['message']='Ok';
		}
		return ($this->json->encode ($status));
	}
}
?>
