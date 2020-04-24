<?php

require_once 'framework/util/BrowserUtils.php';
$browserUtils = new BrowserUtils ();

/**
 * The template file to show notes
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.notes
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

?>
		<!--
			Set some custom colors for the popup notes
		-->
		<script type="text/javascript">
			var ol_textcolor="#000000";
			var ol_capcolor="#000000";
			var ol_bgcolor="#ffee00";
			var ol_fgcolor="#ffff00";
		</script>
<?php
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
			echo ('<td><a href="?plugin=notes&amp;parentId=0" class="ancestor">'.$dictionary['root'].'</a></td>');
			//
			// all ancestors other than root
			//
			foreach($parameters['ancestors'] as $ancestor)
			{
				echo ('<td>&nbsp;/&nbsp;');
				echo ('<a href="?plugin=notes&amp;parentId='.$ancestor->itemId.'" class="ancestor">');
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
		$configuration['trashCount']=$trashCount;
		$configuration['dictionary']=$dictionary;
		$configuration['callback']='index.php?plugin=notes';
		//
		// Check for optional overlib
		//
		if (isset ($_SESSION['noteOverlib']))
		{
			$configuration ['overlib'] =$_SESSION['noteOverlib'];
		}
		else
		{
			$configuration ['overlib'] = true;
		}
		if (!($browserUtils->browserIsPDA ()) && isset ($_SESSION['brimEnableAjax']) && ($_SESSION['brimEnableAjax'] == 1))
		{
			include_once "framework/view/AjaxPaneTree.php";
			include_once "plugins/notes/view/AjaxPaneTreeDelegate.php";
			$delegate = new AjaxPaneTreeDelegate ($configuration);
			$tree = new AjaxPaneTree ($delegate, $configuration);
		}
		//
		// Yahoo like tree
		//
		else
		{
			//
			// Build up a proper configuration for the tree display, this makes only sense for
			// the yahoo-like tree
			//
			if (isset ($_SESSION['noteYahooTreeColumnCount']))
			{
				$configuration ['numberOfColumns'] =$_SESSION['noteYahooTreeColumnCount'];
			}
			else
			{
				$configuration ['numberOfColumns'] = 2;
			}
			include_once "framework/view/YahooTree.php";
			include_once "plugins/notes/view/NoteYahooTreeDelegate.php";
			$delegate = new NoteYahooTreeDelegate ($configuration);
			$tree = new YahooTree ($delegate, $configuration);
		}
		//
		// Now actually show the tree
		//
		echo ($tree -> toHtml ($parent, $renderObjects));
?>
