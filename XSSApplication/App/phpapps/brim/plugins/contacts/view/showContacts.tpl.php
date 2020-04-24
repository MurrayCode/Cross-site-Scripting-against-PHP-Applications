<?php

require_once 'framework/util/BrowserUtils.php';
$browserUtils = new BrowserUtils ();

/**
 * The template file to show contacts, instantiates a proper
 * tree-delegate and tree renderer
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
	/*
	 * Show the ancestor path. Contributed by Michael
	 */
	if(isset($parameters['ancestors']) && $parentId != 0)
	{
		echo ('<!-- Ancestors -->');
		echo ('<table><tr>');

		// The root link
		echo ('<td><a href="index.php?plugin=contacts&amp;parentId=0" class="ancestor">'.$dictionary['root'].'</a></td>');

		// all ancestors other than root
		foreach($parameters['ancestors'] as $ancestor)
		{
			echo ('<td>&nbsp;/&nbsp;');
			echo ('<a href="index.php?plugin=contacts&amp;parentId='.$ancestor->itemId.'" class="ancestor">');
			echo ($ancestor->name);
			echo ('</a>');
			echo ('</td>');
		}
		echo ('</tr></table>');
	}

	include ('templates/'.$_SESSION['brimTemplate'].'/icons.inc');
	$configuration = array ();
	/*
	 * Build up a proper configuration for the tree display.
	 */
	$configuration['icons']=$icons;
	$configuration['dictionary']=$dictionary;
	$configuration['trashCount']=$trashCount;
	$configuration['callback']='index.php?plugin=contacts';

	/*
	 * Check for conditional overlib
	 */
	if (isset ($_SESSION['contactOverlib']))
	{
		$configuration ['overlib'] =$_SESSION['contactOverlib'];
	}
	else
	{
		$configuration ['overlib'] = true;
	}
	//
	// Enhanced AJAX view? Not possible on PDA!
	//
	if (!($browserUtils->browserIsPDA ()) && isset ($_SESSION['brimEnableAjax']) && ($_SESSION['brimEnableAjax'] != 0))
	{
			include_once "framework/view/AjaxLineBasedTree.php";
			include_once "plugins/contacts/view/AjaxLineBasedTreeDelegate.php";
			$delegate = new AjaxLineBasedTreeDelegate	($configuration);
			$tree = new AjaxLineBasedTree ($delegate, $configuration);
	}
	//
	// Else Line based view
	//
	else
	{
			include_once "framework/view/LineBasedTree.php";
			include_once "plugins/contacts/view/ContactLineBasedTreeDelegate.php";
			$delegate = new ContactLineBasedTreeDelegate	($configuration);
			$tree = new LineBasedTree ($delegate, $configuration);
	}
	//
	// Now actually show the layout
	//
	echo ($tree -> toHtml ($parent, $renderObjects));
?>
