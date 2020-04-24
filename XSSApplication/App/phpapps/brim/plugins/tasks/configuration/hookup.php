<?php
/**
 * The Tasks hookup
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - August 2006
 * @package org.brim-project.plugins.tasks
 * @subpackage configuration
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

$plugins['tasks']['name']='tasks';
$plugins['tasks']['controller']='TaskController.php';
$plugins['tasks']['controllerName']='TaskController';
$plugins['tasks']['ajaxController']='AjaxController.php';
$plugins['tasks']['ajaxControllerName']='AjaxController';
$plugins['tasks']['serviceLocation']=
        'plugins/tasks/model/TaskServices.php';
$plugins['tasks']['serviceName']= 'TaskServices';
$plugins['tasks']['dashboardContent']='priority';
$plugins['tasks']['dashboardAction']='showItem';
$plugins['tasks']['dashboardTitle']='priority';
$plugins['tasks']['dashboardSort']='ASC';
$plugins['tasks']['searchFields']=array('name','status','description');
?>