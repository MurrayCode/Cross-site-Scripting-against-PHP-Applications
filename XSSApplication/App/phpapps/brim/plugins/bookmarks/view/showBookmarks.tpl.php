<?php

require_once 'framework/util/BrowserUtils.php';
$browserUtils = new BrowserUtils ();

/**
 * The template file to show bookmarks, instantiates a proper
 * tree-delegate and tree renderer
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

//
// Show the ancestor path. Contributed by Michael
//
if(isset($parameters['ancestors']) && $parentId != 0)
{
	echo ('<!-- Ancestors -->');
	echo ('<table><tr>');
	//
	// The root link
	//
	echo ('<td><a href="?plugin=bookmarks&amp;parentId=0" class="ancestor">'.$dictionary['root'].'</a></td>');
	//
	// all ancestors other than root
	//
	foreach($parameters['ancestors'] as $ancestor)
	{
		echo ('<td>&nbsp;/&nbsp;');
		echo ('<a href="?plugin=bookmarks&amp;parentId='.$ancestor->itemId.'" class="ancestor">');
		echo ($ancestor->name);
		echo ('</a>');
		echo ('</td>');
	}
	echo ('</tr></table>');
}
include ('templates/'.$_SESSION['brimTemplate'].'/icons.inc');
$configuration = array ();
//
// Build up a proper configuration for the tree display.
//
$configuration['icons']=$icons;
$configuration['dictionary']=$dictionary;
$configuration['callback']='index.php?plugin=bookmarks';
//
// Check for conditional overlib
//
if (isset ($_SESSION['bookmarkOverlib']))
{
	$configuration ['overlib'] =$_SESSION['bookmarkOverlib'];
}
else
{
	$configuration ['overlib'] = true;
}
//
// Check where the link should be opened (same window or new window)
//
if (isset ($_SESSION['bookmarkNewWindowTarget']))
{
	$configuration ['bookmarkNewWindowTarget'] =
		$_SESSION['bookmarkNewWindowTarget'];
}

// 
// Enhanced AJAX view? Not possible on PDA!
//
if (!($browserUtils->browserIsPDA ()) && isset ($_SESSION['brimEnableAjax']) && ($_SESSION['brimEnableAjax'] != 0))
{
	include_once "framework/view/AjaxListTree.php";
	include_once "framework/view/AjaxListTreeDelegate.php";
	$delegate = new AjaxListTreeDelegate ($configuration);
	$tree = new AjaxListTree ($delegate, $configuration);
}
//
//  If not, render using default
//
else
{
	include_once "framework/view/Tree.php";
	include_once "plugins/bookmarks/view/BookmarkExplorerTreeDelegate.php";
	$delegate = new BookmarkExplorerTreeDelegate ($configuration);
	$tree = new Tree ($delegate, $configuration);
	//
	// Do we show expanded items? 
	//
	if (isset ($_SESSION['bookmarkExpand']))
	{
		$tree -> setExpanded ($_SESSION['bookmarkExpand']);
	}
}
//
// Now actually show the layout
//
echo ($tree -> toHtml ($parent, $renderObjects));
?>
