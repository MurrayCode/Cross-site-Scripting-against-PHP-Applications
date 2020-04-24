<?php

require_once 'framework/util/Mime.php';

/**
 * MimeTypes
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
class MimeTypes
{
	/**
	 * Array of the mimes that are known (see populateMimes)
	 */
	var $mimes;

	/**
	 * Constructor.
	 */
	function MimeTypes ()
	{
		$this->mimes = array ();
		$this->populateMimes ();
	}

	/**
	 * Populate the known mimetypes
	 */
	function populateMimes ()
	{
		$this->mimes[] =
			new Mime ('image', 'jpeg', array ('jpeg', 'jpg', 'jpe'), '');
		$this->mimes[] =
			new Mime ('image', 'gif', array ('gif'), '');
		$this->mimes[] =
			new Mime ('image', 'png', array ('png'), '');
	}

	/**
	 * Returns the mime for an extension, or <code>null</code>
	 * if the extension is unknown to any of the mimes
	 *
	 * @param string extension the mime extension
	 * @return object a mime object the matches the extension
	 * or <code>null</code> if the extension is unknown to this class
	 */
	function getMimeForExtension ($extension)
	{
		foreach ($this->mimes as $mime)
		{
			if ($mime->knowsExtension ($extension))
			{
				return $mime;
			}
		}
		return null;
	}
}
?>