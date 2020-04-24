<?php

require_once ('framework/model/PreferenceServices.php');

/**
 * The Bookmark preferences.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - February 2004
 * @package org.brim-project.plugins.bookmarks
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class BookmarkPreferences extends PreferenceServices
{
	/**
	 * The indicator for the view used for bookmarks
	 * This indicator will be used as identifier on the SESSION
	 *
	 * @var string VIEW -> bookmarkTree
	 */
	var $VIEW = 'bookmarkTree';

	/**
	 * The indicator for the setting whether we use overlib
	 * javascript popups
	 * This indicator will be used as identifier on the SESSION
	 *
	 * @var string OVERLIB -> bookmarkOverlib
	 */
	var $OVERLIB = 'bookmarkOverlib';

	/**
	 * The indicator for the directory columncount setting
	 * This indicator will be used as identifier on the SESSION
	 *
	 * @var string YAHOOTREECOLUMNCOUNT -> bookmarkYahooTreeColumnCount
	 */
	var $YAHOOTREECOLUMNCOUNT = 'bookmarkYahooTreeColumnCount';

	/**
	 * The indicator for the setting whether we open a link in the same
	 * or in a new window
	 * This indicator will be used as identifier on the SESSION
	 *
	 * @var string NEWWINDOWTARGET -> bookmarkNewWindowTarget
	 */
	var $NEWWINDOWTARGET = 'bookmarkNewWindowTarget';

	/**
	 * The indicator for the setting whether we want to see favicons
	 * (if available)
	 * This indicator will be used as identifier on the SESSION
	 *
	 * @var string FAVICON -> bookmarkFavicon
	 */
	var $FAVICON = 'bookmarkFavicon';

	/**
	 * Autoprepend 'http://' if no protocol is found in the url
	 *
	 * @var unknown_type
	 */
	var $AUTOPREPEND = 'bookmarkAutoPrependProtocol';

	/**
	 * Default empty constructor
	 */
	function BookmarkPreferences ()
	{
		parent::PreferenceServices ();
	}

	/**
	 * Returns the specified user's preferences for bookmarks
	 *
	 * @return array an array containing the users preferences
	 */
	function getAllPreferences ($loginName)
	{
		$result = array ();
		$result [$this->VIEW]
			=$this->getPreferenceValue
				($loginName, $this->VIEW);
		$result [$this->OVERLIB]
			=$this->getPreferenceValue
				($loginName, $this->OVERLIB);
		$result [$this->YAHOOTREECOLUMNCOUNT]
			=$this->getPreferenceValue
				($loginName, $this->YAHOOTREECOLUMNCOUNT);
		$result [$this->NEWWINDOWTARGET]
			=$this->getPreferenceValue
				($loginName, $this->NEWWINDOWTARGET);
		$result [$this->FAVICON]
			=$this->getPreferenceValue
				($loginName, $this->FAVICON);
		$result [$this->SHOWDETAILS]
			=$this->getPreferenceValue
				($loginName, $this->SHOWDETAILS);
		$result [$this->AUTOPREPEND]
			=$this->getPreferenceValue
				($loginName, $this->AUTOPREPEND);
		return $result;
	}
}
?>