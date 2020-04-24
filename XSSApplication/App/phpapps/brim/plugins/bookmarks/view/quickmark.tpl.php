<?php
/**
 * The template file that draws the layout to search for a bookmark.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.bookmarks
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
echo ('<h1>Brim - Quickmark</h1>');
echo $dictionary['quickmark'];
echo $dictionary['quickmarkExplanation'];

if (!isset ($installationPath) || $installationPath == '')
{
	echo ($dictionary['installationPathNotSet']);
}
else
{
	$lastChar = $installationPath{strlen($installationPath)-1};
	if ($lastChar != '/')
	{
		$installationPath .= '/';
	}
	$quickmark  = "javascript:void(open('" . $installationPath;
	$quickmark .= "index.php?plugin=bookmarks&amp;action=quickmark";
	$quickmark .= "&locator='+escape(document.location)+'";
	$quickmark .= "&name='+escape(document.title),'Brim',";
	$quickmark .= "'width=600,height=400,scrollbars=1,resizable=1'));";
	echo ('<p><a href="'.$quickmark.'">Brim - Quickmark</a>');
}
?>