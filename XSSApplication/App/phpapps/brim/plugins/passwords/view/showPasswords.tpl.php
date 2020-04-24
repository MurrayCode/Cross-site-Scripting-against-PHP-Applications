<?php
/**
 * The template file to show passwords
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2004
 * @package org.brim-project.plugins.passwords
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
?>
<script type="text/javascript">
<!--
function passPhrase (action, name, itemId)
{
popup = window.open('','Brim','');
popup.document.open();
popup.document.write("<html><head><title>Brim</title></head><body>");
popup.document.write('<h1>');
popup.document.write(name);
popup.document.write('</h1>');
popup.document.write("Passphrase: <br />");
popup.document.write ('<form method="POST" method="inedx.php">');
popup.document.write ('<input type="password" name="passPhrase" />');
popup.document.write ('<input type="hidden" name="plugin" value="passwords" />');
popup.document.write ('<input type="hidden" name="itemId" value="'+itemId+'" />');
popup.document.write ('<input type="hidden" name="action" value="'+action+'" />');
popup.document.write ('<input type="submit" value="Submit" name="submit" />');
popup.document.write ("</form>");
popup.document.write("</body></html>");
popup.document.close();
}
-->
</script>
<?php
		/*
		 * Show the ancestor path. Contributed by Michael
		 */
		if(isset($parameters['ancestors']) && $parentId != 0)
		{
			echo ('<!-- Ancestors -->');
			echo ('<table><tr>');

			// The root link
			echo '<td><a href="?plugin=passwords&amp;parentId=0" class="ancestor">';
			echo $dictionary['root'];
			echo '</a></td>';

			// all ancestors other than root
			foreach($parameters['ancestors'] as $ancestor)
			{
				echo ('<td>&nbsp;/&nbsp;');
				echo ('<a href="?plugin=passwords&amp;parentId='.$ancestor->itemId.'" class="ancestor">');
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
		$configuration['callback']='index.php?plugin=passwords';

		/*
		 * Check for conditional overlib
		 */
		if (isset ($_SESSION['passwordOverlib']))
		{
			$configuration ['overlib'] =$_SESSION['passwordOverlib'];
		}
		else
		{
			$configuration ['overlib'] = true;
		}

		/*
		 * Explorer like tree
		 */
		if (isset ($_SESSION['passwordTree']) && $_SESSION['passwordTree'] == 'Explorer')
		{
			include_once "framework/view/Tree.php";
			include_once "plugins/passwords/view/PasswordExplorerTreeDelegate.php";
			$delegate = new PasswordExplorerTreeDelegate ($configuration);
			$tree = new Tree ($delegate, $configuration);
			/*
			 * Do we show expanded items? (This is not a useful option in the yahoo tree)
			 */
			if (isset ($_SESSION['passwordExpand']))
			{
				$tree -> setExpanded ($_SESSION['passwordExpand']);
			}
		}
		/*
		 * Yahoo like tree
		 */
		else
		{
			/*
			 * Build up a proper configuration for the tree display, this makes only sense for
			 * the yahoo-like tree
			 */
			if (isset ($_SESSION['passwordYahooTreeColumnCount']))
			{
				$configuration ['numberOfColumns'] =$_SESSION['passwordYahooTreeColumnCount'];
			}
			else
			{
				$configuration ['numberOfColumns'] = 2;
			}
			include_once "framework/view/YahooTree.php";
			include_once "plugins/passwords/view/PasswordYahooTreeDelegate.php";
			$delegate = new PasswordYahooTreeDelegate ($configuration);
			$tree = new YahooTree ($delegate, $configuration);
		}

		/*
		 * Now actually show the layout
		 */
		echo ($tree -> toHtml ($parent, $renderObjects));
?>
