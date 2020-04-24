<?php

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.templates
 * @subpackage penguin
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2005 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
require_once ('framework/util/BrowserUtils.php');
$browserUtils = new BrowserUtils ();

$icons = array ();
//$icons['up']="[...up...]";
$icons['root']='<h2>Root</h2>';
$icons['bar'] ='<img src="framework/view/pics/tree/empty_bar.gif" border="0">';
$icons['minus']='<img src="framework/view/pics/tree/shaded_minus.gif" border="0">';
$icons['corner']='<img src="framework/view/pics/tree/empty_corner.gif" border="0">';
$icons['plus']="<img src='framework/view/pics/tree/shaded_plus.gif' border='0'>";
$icons['tee']="<img src='framework/view/pics/tree/empty_tee.gif' border='0'>";
$icons['node']="<img src='framework/view/pics/tree/everaldo_item.gif' border='0'>";

$imgExtension = ".png";
if ($browserUtils->browserIsExplorer ())
{
	// Explorer does not support transparent PNGs
	$imgExtension = ".jpg";
}
$icons['up']='<img src="framework/view/pics/arrows/everaldo_up'.$imgExtension.'" border="0">[...up...]';
$icons['folder_closed']='<img src="framework/view/pics/tree/everaldo_folder_closed'.$imgExtension.'" border="0">';
$icons['open_new_window']='&nbsp<img src="framework/view/pics/tree/bm_new_window'.$imgExtension.'" border="0">';
$icons['delete']='<img src="framework/view/pics/everaldo_delete'.$imgExtension.'" border="0">';
$icons['edit']='<img src="framework/view/pics/everaldo_edit'.$imgExtension.'" border="0">';
$icons['folder_open']='<img src="framework/view/pics/tree/everaldo_folder_open'.$imgExtension.'" border="0">';

$icons['message']='<img src="framework/view/pics/everaldo_info'.$imgExtension.'" border="0">';
$icons['warning']='<img src="framework/view/pics/everaldo_error'.$imgExtension.'" border="0">';

$icons['up_arrow']='<img src="framework/view/pics/arrows/everaldo_uparrow'.$imgExtension.'" border="0">';
$icons['down_arrow']='<img src="framework/view/pics/arrows/everaldo_downarrow'.$imgExtension.'" border="0">';
$icons['up_arrow_shaded']='<img src="framework/view/pics/arrows/everaldo_uparrowgray'.$imgExtension.'" border="0">';
$icons['down_arrow_shaded']='<img src="framework/view/pics/arrows/everaldo_downarrowgray'.$imgExtension.'" border="0">';
$icons['locked']='<img src="framework/view/pics/locked'.$imgExtension.'" border="0" alt="locked">';
$icons['unlocked']='<img src="framework/view/pics/unlocked'.$imgExtension.'" border="0" alt="unlocked">';
$icons['overviewexpand']='<img src="framework/view/pics/arrows/everaldo_overviewexpand'.$imgExtension.'" border="0" alt="expand">';
$icons['overviewcollapse']='<img src="framework/view/pics/arrows/everaldo_overviewcollapse'.$imgExtension.'" border="0" alt="collapse">';
$icons['busy']=
	'<img src="framework/view/pics/loading/loading_glassblock.gif" border="0" alt="busy">';
$icons['refresh']='<img src="framework/view/pics/everaldo_refresh'.$imgExtension.'" border="0" alt="refresh">';
	
$icons['refresh']='<img src="framework/view/pics/refresh.png" border="0" alt="refresh">';
$icons['fullTrash']="templates/penguin/pics/trashcan_full.png";
$icons['emptyTrash']="templates/penguin/pics/trashcan_empty.png";
$icons['completedTasks']="templates/penguin/plugins/pics/tasks/folder_green.png";
$icons['uncompletedTasks']="templates/penguin/plugins/pics/tasks/folder_red.png";

$icons['closedFolder']=array (
	'location'=>'framework/view/pics/tree/everaldo_folder_closed.gif', 
	'width'=>'22',
	'height'=>'22',
	'alt'=>'Folder');
$icons['openFolder']=array (
	'location'=>'framework/view/pics/tree/everaldo_folder_open.gif', 
	'width'=>'22',
	'height'=>'22',
	'alt'=>'Folder');
$icons['item']=array (
	'location'=>'framework/view/pics/tree/everaldo_item.gif',
	'width'=>'22',
	'height'=>'22',
	'alt'=>'Item');
?>
