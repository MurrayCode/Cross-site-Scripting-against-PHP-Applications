<?php
/**
 * The template file that draws the layout to add a contact.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.contacts
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
	include ('templates/'.$_SESSION['brimTemplate'].'/icons.inc');

	include_once "framework/view/Tree.php";
	include_once "framework/view/MoveItemExplorerTreeDelegate.php";

	$configuration = array ();
	$configuration['dictionary']=$dictionary;
	$configuration['icons']=$icons;
	$configuration['callback']='index.php?plugin=contacts';

	$delegate = new MoveItemExplorerTreeDelegate
		($configuration);
	$tree = new Tree ($delegate, $configuration);
	// we would like to show all items
	$tree -> setExpanded ('*');
	echo ($tree -> toHtml ($item, $renderObjects));
?>