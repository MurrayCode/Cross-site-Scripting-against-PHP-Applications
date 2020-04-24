<?php

/**
 * Mimes
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - June 2006
 * @package org.brim-project.framework
 * @subpackage util
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class Mime
{
	/**
	 * The maintype of this mime (for example: image)
	 */
	var $mainType;
	/**
	 * The subtype of this mime (for example: jpeg)
	 */
	var $subtype;
	/**
	 * An array of known extensions for this mime
	 * (for example: jpeg, jpg, jpe)
	 */
	var $extensions;
	/**
	 * The description of this mime
	 */
	var $description;

	/**
	 * Constructor.
	 *
	 * @param string type the type of the mime
	 * @param string sybtype the subtype of the mime
	 * @param array extenstions an array containing the possible
	 * mime extensions
	 * @description string the description of the mime
	 *
	 * @example: Mime ('image', 'jpeg', array('jpg','jpeg','jpe'),
	 * 'JPEG is another image compression format. Although it is a
	 * popular and fairly common format JPEG is not supported
	 * internally by as many browsers as GIF. [RFC1521, Borenstein]')
	 */
	function Mime ($theType, $theSubType, $theExtensions, $theDescription)
	{
		$this->mainType = $theType;
		$this->subType = $theSubType;
		$this->extensions = $theExtensions;
		$this->description = $theDescription;
	}

	/**
	 * Checks whether the input extension is a known extension
	 * to this mime.
	 * @param string anExtension a fileextension
	 * @return boolean <code>true</code> if this mime knows this
	 * extension, <code>false</code> otherwise
	 */
	function knowsExtension ($anExtension)
	{
		$result =  in_array ($anExtension, $this->extensions);
		return $result;
	}

	/**
	 * Returns the mimetype for this mime in the form
	 * of 'main'/'sub'
	 * @return string this mime mimeType (for example 'image/jpeg')
	 */
	function getMimeType ()
	{
		return $this->mainType.'/'.$this->subType;
	}

	/**
	 * Returns the main mimetype for this mime
	 * @return string this mime mainType (for example 'image')
	 */
	function getMainType ()
	{
		return $this->mainType;
	}
}
?>