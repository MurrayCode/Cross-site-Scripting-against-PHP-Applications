<?php

require_once ('framework/model/ItemParticipationServices.php');
require_once ('plugins/notes/model/NoteServices.php');
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
 * @author Barry Nauta - 21 Nov 2006
 * @package org.brim-project.plugins.notes
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
		$this->services = new NoteServices();
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
		// Do we have the right to change this item>
		//
		if ($item->owner != $_SESSION['brimUsername'])
		{
			$status ['error']='Invalid access';
		}
		else
		{
			$value = $this->stringUtils->gpcStripSlashes ($_REQUEST['value']);
			// 
			// We can either change the name or the description
			//	
			switch ($toChange [0])
			{
				case 'name':
					$item->name = $value;
					$status['itemId'] = $itemId;
					$status['result'] = $item->name;
					break;
				case 'description':
					$item->description= $value;
					$status['itemId'] = $itemId;
					$status['result'] = $item->description;
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
		//
		// Do we have the right to move this item?
		//
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

	/**
	 * Load this notes' position
	 * 
	 * @param array args unused
	 * @return string a JSON encoded string containing this notes itemIs, its 
	 * top, left, zIndex, width and height
	 */
	function loadNotePositions ($args)
	{
		$result = $this->services->getNotePositions ($_SESSION['brimUsername']);
		$resultArray = array ();
		foreach ($result as $key=>$value)
		{
			$coordinates = explode (";", $value);
			$resultArray [] = array (
				"itemId"=>$key,
				"top"=>$coordinates[0],	
				"left"=>$coordinates[1],	
				"zIndex"=>$coordinates[2],	
				"width"=>$coordinates[3],	
				"height"=>$coordinates[4]	
			);
		}
		return ($this->json->encode ($resultArray));
	}

	/**
	 * Set the position of the item
	 *
	 * @param array args containing the following keys:
	 * 	top, left, zIndex, width and height
	 */	
	function setPosition ($args)
	{
		$itemId = $args ['itemId'];
		$item = $this->services->getItem ($_SESSION['brimUsername'], $itemId);
		if ($item->owner != $_SESSION['brimUsername'])
		{
			$status ['error']='Invalid access';
		}
		else 
		{
			$coordinates = array($args['top'], $args['left'], $z = $args['zIndex'],
				$args['width'], $args['height']);
			$position = implode (';',$coordinates);
			$item->position=$position;
			$this->services->modifyItem ($_SESSION['brimUsername'], $item);
		}
	}
}
?>
