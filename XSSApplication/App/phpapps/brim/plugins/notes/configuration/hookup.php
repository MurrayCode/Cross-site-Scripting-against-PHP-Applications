<?php
/**
 * The Notes hookup
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.notes
 * @subpackage configuration
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

$plugins['notes']['name']='notes';
$plugins['notes']['controller']='NoteController.php';
$plugins['notes']['controllerName']='NoteController';
$plugins['notes']['ajaxController']='AjaxController.php';
$plugins['notes']['ajaxControllerName']='AjaxController';
$plugins['notes']['serviceLocation']=
        'plugins/notes/model/NoteServices.php';
$plugins['notes']['serviceName']= 'NoteServices';
$plugins['notes']['dashboardContent']='when_created';
$plugins['notes']['dashboardAction']='showItem';
$plugins['notes']['dashboardTitle']='last_created';
$plugins['notes']['dashboardSort']='DESC';
$plugins['notes']['searchFields']=array('name','description');
?>
