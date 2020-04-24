<?php

require_once('framework/view/MultipleSelectExplorerTreeDelegate.php');

/**
 * The template file that draws the layout to select multiple items.
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

//
// Add a 'select all' action
//
echo writeSelectJavascript ($dictionary);
$renderActions[0]['contents'][]=
    array ('href'=>'javascript:selectAll();',
		'name'=>'selectAll');
$renderActions[0]['contents'][]=
	array ('href'=>'javascript:inverseAll();',
		'name'=>'inverseAll');

include ('templates/'.$_SESSION['brimTemplate'].'/icons.inc');

include_once "framework/view/Tree.php";
include_once "framework/view/MultipleSelectExplorerTreeDelegate.php";

$configuration = array ();
$configuration['dictionary']=$dictionary;
$configuration['icons']=$icons;
$delegate = new MultipleSelectExplorerTreeDelegate ($configuration);
$tree = new Tree ($delegate, $configuration);
//
// we would like to show all items
//
$tree -> setExpanded ('*');
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
	echo ('<td><a href="?parentId=0" class="ancestor">'.$dictionary['root'].'</a></td>');
	//
	// all ancestors other than root
	//
	foreach($parameters['ancestors'] as $ancestor)
	{
		echo ('<td>&nbsp;/&nbsp;');
		echo ('<a href="?parentId='.$ancestor->itemId.'" class="ancestor">');
		echo ($ancestor->name);
		echo ('</a>');
		echo ('</td>');
	}
	echo ('</tr></table>');
}
?>
<form method="POST" action="index.php">
<?php
	echo ($tree -> toHtml ($item, $renderObjects));
?>
<input type="hidden" name="plugin" value="passwords" />
<input type="hidden" name="action" value="multipleSelectPost" />
<input type="hidden" name="parentId" value="<?php echo $parentId ?>" />
<input type="submit" value="<?php echo $dictionary['move'] ?>"
		name="move" />
<input type="submit" value="<?php echo $dictionary['delete'] ?>"
		name="delete" />

</form>