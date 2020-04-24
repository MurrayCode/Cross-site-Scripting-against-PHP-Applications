<?php

include_once ("framework/util/StringUtils.php");

/**
 * Abstract base class used in combination with the Tree, YahooTree or
 * LineBasedTree
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - February 2004
 * @package org.brim-project.framework
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class TreeDelegate
{
	/**
	 * String utilities
	 *
	 * @private
	 * @var object stringUtils
	 */
	var $stringUtils;

	/**
	 * The configuration (containing the dictionary, callback url etc)
	 * that is used
	 *
	 * @var array configuration
	 */
	var $configuration;

	/**
	 * Default constructor
	 * @param array theConfiguration. This configuration must
	 * contain the following items:
	 * <ul>
	 * <li>icons. Icons in an hashtable.
	 * </li>
	 * <li>callback. The URL to the callback,
	 * typically the controller</li>
	 * <li>dictionairy. The dictionary that is used</li>
	 * </ul>
	 */
	function TreeDelegate ($theConfiguration)
	{
		$this->configuration = $theConfiguration;
		$this->stringUtils = new StringUtils ();
	}

	/**
	 * Renders the sort arrows
	 *
	 * @param string sortField the field on which we would like
	 * to sort
	 * @param integer parentId the current parentId
	 */
	function sortArrows ($sortField, $parentId, $action='sort')
	{
		$resultString = '&nbsp;';
		$resultString .= '<a href="'.$this->configuration['callback'];
		if (stristr ($this->configuration['callback'],'?'))
		{
			$resultString .= '&amp;action='.$action;
		}
		else
		{
			// TODO, this part should be obsoleted
			$resultString .= '?action='.$action;
		}
		$resultString .= '&amp;order=ASC&amp;field='.$sortField.'&amp;parentId='.$parentId;
		if (isset ($_GET['order']) && $_GET['order'] == 'ASC' && $_GET['field'] == $sortField)
		{
			$resultString .= '">'.$this->configuration['icons']['up_arrow_shaded'].'</a>';
		}
		else
		{
			$resultString .= '">'.$this->configuration['icons']['up_arrow'].'</a>';
		}
		$resultString .= '<a href="'.$this->configuration['callback'];
		if (stristr ($this->configuration['callback'],'?'))
		{
			$resultString .= '&amp;action='.$action;
		}
		else
		{
			// TODO, this part should be obsoleted
			$resultString .= '?action='.$action;
		}
		$resultString .= '&amp;order=DESC&amp;field='.$sortField.'&amp;parentId='.$parentId;
		if (isset ($_GET['order']) && $_GET['order'] == 'DESC' && $_GET['field'] == $sortField)
		{
			$resultString .= '">'.$this->configuration['icons']['down_arrow_shaded'].'</a>';
		}
		else
		{
			$resultString .= '">'.$this->configuration['icons']['down_arrow'].'</a>';
		}
		return $resultString;
	}


	/**
	 * Returns the overlib javascript
	 *
	 * @param string title the title of the popup
	 * @param string text the text of the popup
	 * @param string optional additional comma-seperated parameters
	 * (like STICKY, MOUSEOUT etc)
	 */
	function overLib ($title, $text, $params='')
	{
		$string = addslashes ($text);
		$string = ereg_replace ('"', '\'', $string);
		$string = $this->stringUtils->newlinesToHtml ($string);
		$resultString  = '
				onmouseover="return overlib(\'';
		$resultString .= $string;
		$resultString .= "', CAPTION, '";
		$resultString .= $this->stringUtils->urlEncodeQuotes
			(addslashes ($title)) . '\'';
		if (isset ($params) && $params != '')
		{
			$resultString .= ', '.$params;
		}
		$resultString .= ');" ';
		$resultString .= 'onmouseout="return nd();" ';
		return $resultString;
	}
}
?>
