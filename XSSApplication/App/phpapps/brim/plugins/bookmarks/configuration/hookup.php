<?php
/**
 * The Bookmarks hookup
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - August 2006
 * @package org.brim-project.plugins.bookmarks
 * @subpackage configuration
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

$plugins['bookmarks']['name']='bookmarks';
$plugins['bookmarks']['controller']='BookmarkController.php';
$plugins['bookmarks']['controllerName']='BookmarkController';
$plugins['bookmarks']['ajaxController']='AjaxController.php';
$plugins['bookmarks']['ajaxControllerName']='AjaxController';
$plugins['bookmarks']['serviceLocation']= 'plugins/bookmarks/model/BookmarkServices.php';
$plugins['bookmarks']['serviceName']= 'BookmarkServices';
$plugins['bookmarks']['dashboardContent']='visit_count';
$plugins['bookmarks']['dashboardAction']='showBookmark';
$plugins['bookmarks']['dashboardTitle']='most_visited';
$plugins['bookmarks']['dashboardSort']='DESC';
if (isset ($_SESSION['bookmarkNewWindowTarget'])
	&& ($_SESSION['bookmarkNewWindowTarget']))
{
	$plugins['bookmarks']['dashboardAdditionalLinkParameters']='target="_blank"';
}
$plugins['bookmarks']['searchFields']=array ('name', 'locator', 'description');
?>