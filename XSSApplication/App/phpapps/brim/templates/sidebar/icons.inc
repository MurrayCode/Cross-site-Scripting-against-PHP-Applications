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
 * @subpackage sidebar
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2005 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
require_once ('framework/util/BrowserUtils.php');
$browserUtils = new BrowserUtils ();
$imgExtension = ".gif";
if (!($browserUtils->browserIsExplorer ()))
{
        // Explorer does not support transparent PNGs
        $imgExtension = ".png";
}
$icons = array ();
$icons['root']='<h2>Root</h2>';
$icons['up']=
	"[...up...]";

$icons['bar']=
	'<img src="framework/view/pics/tree/empty_bar.gif" border="0">';
$icons['minus']=
	'<img src="framework/view/pics/tree/shaded_minus.gif" border="0">';
$icons['folder_open']=
	'<img src="framework/view/pics/tree/gnome_folder_open.gif" border="0">';
$icons['corner']='&nbsp;';
$icons['plus']=
	"<img src='framework/view/pics/tree/shaded_plus.gif' border='0'>";
$icons['tee']='&nbsp';
$icons['folder_closed']=
	"<img src='framework/view/pics/tree/gnome_folder_closed.gif' border='0'>";
$icons['node']=
	"<img src='framework/view/pics/tree/shaded_item.gif' border='0'>";
$icons['open_new_window']=
	"&nbsp<img src='framework/view/pics/arrows/gnome_arrow.gif' border='0'>";
$icons['delete']=
	"<img src='framework/view/pics/delete.gif' border='0'>";
$icons['edit']=
	"<img src='framework/view/pics/edit.gif' border='0'>";
?>