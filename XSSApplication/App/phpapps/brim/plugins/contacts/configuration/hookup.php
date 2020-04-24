<?php
/**
 * The Contacts hookup
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - August 2006
 * @package org.brim-project.plugins.contacts
 * @subpackage configuration
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

$plugins['contacts']['name']='contacts';
$plugins['contacts']['controller']='ContactController.php';
$plugins['contacts']['controllerName']='ContactController';
$plugins['contacts']['serviceLocation']=
        'plugins/contacts/model/ContactServices.php';
$plugins['contacts']['ajaxController']='AjaxController.php';
$plugins['contacts']['ajaxControllerName']='AjaxController';
$plugins['contacts']['serviceName']= 'ContactServices';
$plugins['contacts']['dashboardContent']='when_modified';
$plugins['contacts']['dashboardAction']='showItem';
$plugins['contacts']['dashboardTitle']='last_modified';
$plugins['contacts']['dashboardSort']='DESC';
// $plugins['contacts']['searchFields']=array('name','alias','email1','webaddress1','description');
$plugins['contacts']['searchFields']=array('name','email1','mobile','tel_work','tel_home');
?>
