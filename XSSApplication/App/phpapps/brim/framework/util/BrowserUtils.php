<?php

/**
 * Browser utilities.
 *
 * Based on an article by Tim Perdue, located at
 * http://www.phpbuilder.com/columns/tim20000821.php3
 * Copyright 1999-2000 (c) The SourceForge Crew
 *
 * Modified version is embedded in a class and adapted to reflect the
 * new PHP standards.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 * @subpackage util
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class BrowserUtils
{
	/**
	 * Which browser are we dealing with?
	 *
	 * @access private
	 * @vare string
	 */
	var $agent;

	/**
	 * What is the browser version
	 *
	 * @access private
	 * @var string
	 */
	var $version;

	/**
	 * What is the browsers platform
	 *
	 * @access private
	 * @var string
	 */
	var $platform;

	/**
	 * Constructor.
	 */
	function BrowserUtils ()
	{
		$log_version = array ();
		//
	    // Determine browser and version
		//
		if (ereg ('MSIE ([0-9].[0-9]{1,2})',$_SERVER['HTTP_USER_AGENT'],
			$log_version))
		{
		    $this->version=$log_version[1];
	    	$this->agent='IE';
		}
		elseif (ereg ('Opera ([0-9].[0-9]{1,2})',$_SERVER['HTTP_USER_AGENT'],
			$log_version))
		{
		    $this->version=$log_version[1];
		    $this->agent='Opera';
		}
		elseif (ereg ('Mozilla/([0-9].[0-9]{1,2})',$_SERVER['HTTP_USER_AGENT'],
			$log_version))
		{
		    $this->version=$log_version[1];
		    $this->agent='Mozilla';
		}
		else
		{
		    $this->version=0;
			$this->agent='Unknown ';
		}
		//
	    	// Determine platform
		//
		if (strstr($_SERVER['HTTP_USER_AGENT'],'Win'))
		{
		    $this->platform='Win';
		}
		else if (strstr($_SERVER['HTTP_USER_AGENT'],'Mac'))
		{
		    $this->platform='Mac';
		}
		else if (strstr($_SERVER['HTTP_USER_AGENT'],'Linux'))
		{
		    $this->platform='Linux';
		}
		else if (strstr($_SERVER['HTTP_USER_AGENT'],'Unix'))
		{
		    $this->platform='Unix';
		}
		else
		{
		    $this->platform='Unknown';
		}
	}

	/**
	 * Returns the current used browser
	 *
	 * @return string the browser that is used
	 */
	function getBrowserAgent ()
	{
	    return $this->agent;
	}

	/**
	 * Returns the version of the browser
	 *
	 * @return string the version
	 */
	function getBrowserVersion ()
	{
	    return $this->version;
	}

	/**
	 * Returns the browsers platform
	 *
	 * @return string the platform
	 */
	function getBrowserPlatform ()
	{
	    return $this->platform;
	}


	/**
	 * Is the browser Internet Explorer?
	 *
	 * @return boolean <code>true</code> if the platform is IE,
	 * <code>false</code> otherwise
	 */
	function browserIsExplorer ()
	{
		return ($this->getBrowserAgent () == 'IE');
	}

	/**
	 * Is the browser Mozilla/Netscape?
	 *
	 * @return boolean <code>true</code> if the platform is Mozilla/Netscape,
	 * <code>false</code> otherwise
	 */
	function browserIsMozilla ()
	{
		return ($this->getBrowserAgent () == 'Mozilla');
	}

	/**
	 * Is the browser Mozilla/Netscape?
	 *
	 * @return boolean <code>true</code> if the platform is Mozilla/Netscape,
	 * </code>false</code> otherwise
	 */
	function browserIsNetscape ()
	{
		return $this->browserIsMozilla ();
	}

	/**
	 * Is the browser Opera?
	 *
	 * @return boolean <code>true</code> if the platform is Opera,
	 * <code>false</code> otherwise
	 */
	function browserIsOpera ()
	{
		return $this->getBrowserAgent () == 'Opera';
	}

	/**
	 * Do we have a PDA connection?
	 *
	 * @return boolean <code>true</code> if the platform is a PDA,
	 * <code>false</code> otherwise
	 */
	function browserIsPDA ()
	{
		// Perhaps add check for PPC and Windows CE?
		return (
			strstr($_SERVER['HTTP_USER_AGENT'],'BlackBerry') ||
			strstr($_SERVER['HTTP_USER_AGENT'],'Smartphone') ||
			strstr($_SERVER['HTTP_USER_AGENT'],'Palm') ||
			strstr($_SERVER['HTTP_USER_AGENT'],'Symbian') ||
			strstr($_SERVER['HTTP_USER_AGENT'],'SonyEricsson') ||
			strstr($_SERVER['HTTP_USER_AGENT'],'SHARP') ||
			strstr($_SERVER['HTTP_USER_AGENT'],'240x320')
		);
	}
}
?>