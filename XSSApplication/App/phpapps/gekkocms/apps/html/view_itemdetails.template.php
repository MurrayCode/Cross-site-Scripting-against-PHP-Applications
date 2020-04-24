<?php 
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
	if (!defined('GEKKO_VERSION')) die();

	global $gekko_current_user, $Filters;
	$opt = $item_meta_options; 

	$info_array = array ('app'=>$this->app_name,'mode'=>'details','display'=>'item','id'=>$item['id']);

/*	if (!( $item['date_expiry'] == NULL_DATE || daysDifferenceFromToday($item['date_expiry']) > 0 )) { echo H3('This item has expired.');return false;}
	if (daysDifferenceFromToday($item['date_available']) > 0 ) { echo H3('This item is not available yet at this time.');return false;}
	if ($item['status'] != 1) { echo H3('This item is inactive'); return false;}
	$can_read = $gekko_current_user->hasReadPermission($item['permission_read']);
	if (!$can_read && !$opt['display_items_summary_noread']) { echo H3('This item is not available for your user group.');return false;}*/
	if (!checkPageOutputDatesAndStatus($item, $opt)) return false;
	if ($opt['display_pagetitle']) echo H1(htmlspecialchars($item['title']),"html_item_pagetitle-{$item['id']}",'html_item_pagetitle');
	$author_title = ''; $author = $gekko_current_user->getItemByID($item['created_by_id']); if ($author && $opt['display_items_author']) $author_title = ' by '.$author['username']; 
	if ($opt['display_items_date_created'] &&  $category['date_modified'] != NULL_DATE) echo DIV_start('','html_item_datecreated').'Created on '.str_replace(' ',' at ',$item['date_created']).$author_title.'. '.DIV_end();
	if ($opt['display_items_date_modified'] &&  $category['date_modified'] != NULL_DATE) echo DIV_start('','html_item_datemodified').'Last updated on '.str_replace(' ',' at ',$item['date_modified']).'. '.DIV_end();
	if( $item['summary']) echo $this->processOutputWithFilter($item['summary'],$current_method,$info_array);
	if (!$can_read && $opt['display_items_summary_noread'] && $item['description']) echo H3('The rest of this content is not available for your user group...');
	else echo $this->processOutputWithFilter($item['description'],$current_method,$info_array);
?>
