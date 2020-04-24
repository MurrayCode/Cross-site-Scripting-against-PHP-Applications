<?php
/**
 * The Calendar hookup
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - August 2006
 * @package org.brim-project.plugins.calendar
 * @subpackage configuration
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

$plugins['calendar']['name']='calendar';
$plugins['calendar']['controller']= 'CalendarController.php';
$plugins['calendar']['controllerName']= 'CalendarController';
$plugins['calendar']['ajaxController']='AjaxController.php';
$plugins['calendar']['ajaxControllerName']='AjaxController';
$plugins['calendar']['serviceLocation']=
        'plugins/calendar/model/CalendarServices.php';
$plugins['calendar']['serviceName']= 'CalendarServices';
$plugins['calendar']['dashboardAction']='showDay';
$plugins['calendar']['dashboardContent']='today';
$plugins['calendar']['dashboardTitle']='today';
$plugins['calendar']['dashboardSort']='DESC';
$plugins['calendar']['searchFields']=array('name','location','description');
?>
