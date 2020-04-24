<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// parameter: action, command, pg
	if (!defined('GEKKO_VERSION')) die();

	global $Application, $gekko_current_user; $appname = $Application->app_name;
	$opt = $category_meta_options;
    $info_array = array ('app'=>$this->app_name,'mode'=>'list','display'=>'category','cid'=>$category[$this->field_category_id]);
	if (!checkPageOutputDatesAndStatus($category, $opt)) return false;
	
	if ($category['cid'] == 0) $category['title'] = $Application->app_description; 
	if ($opt['display_pagetitle']) echo H1(SAFE_HTML($category['title']),"blog_category_pagetitle-{$category['cid']}",'blog_category_pagetitle');
	$author_title = ''; $author = $gekko_current_user->getItemByID($item['created_by_id']); if ($author && $opt['display_items_author']) $author_title = ' by '.$author['username'];
        $info_array['display'] = 'category';
        $info_array['cid'] = $category['cid'];

	echo $this->processOutputWithFilter($category['summary'].$category['description'],$current_method, $info_array);
	if ($opt['display_items_date_created'] || $opt['display_items_date_modified'])
	{					
		echo DIV_start('','blog_dates');
	
	if ($opt['display_category_date_created'] &&  $category['date_created'] != NULL_DATE) echo 'Created on '.str_replace(' ',' at ',$category['date_created']).$author_title.'. ';
	if ($opt['display_category_date_modified'] &&  $category['date_modified'] != NULL_DATE) echo 'Last updated on '.str_replace(' ',' at ',$category['date_modified']).'. ';
		echo DIV_end();
	
	}
?>

<?php if ($childcategories && $opt['display_childcategories']): ?>
  <?php foreach ($childcategories as $childcategory): ?>
  <?php if ($childcategory['status'] == 1 && $gekko_current_user->hasReadPermission($childcategory['permission_read']) && ( $childcategory['date_expiry'] == NULL_DATE || daysDifferenceFromToday($childcategory['date_expiry']) > 0 )): ?>
  <?php  
  	$link = $this->createFriendlyURL("action=viewcategory&cid={$childcategory['cid']}");
  	if (!$opt['display_items_readmore_link']): 
   ?>
    <h2 class="blog_child_category"> <a class="blog_child_category" href="<?php echo $link; ?>"><?php echo htmlspecialchars($childcategory['title']); ?></a></h2>
    <?php if ($opt['display_items_summary']) echo $childcategory['summary']; ?>
    <?php else: ?>
	<h2 class="blog_child_category"><?php echo htmlspecialchars($childcategory['title']); ?></h2>  

    
<?php
	if ($opt['display_items_date_created'] || $opt['display_items_date_modified'])
	{					
		$author_title = ''; $author = $gekko_current_user->getItemByID($childcategory['created_by_id']); if ($author && $opt['display_items_author']) $author_title = ' by '.$author['username']; 
		echo DIV_start('','blog_dates');
		if ($opt['display_items_date_created'] &&  $childcategory['date_created'] != NULL_DATE) echo 'Created on '.str_replace(' ',' at ',$childcategory['date_created']).$author_title.'. ';
		if ($opt['display_items_date_modified'] &&  $childcategory['date_modified'] != NULL_DATE) echo 'Last updated on '.str_replace(' ',' at ',$childcategory['date_modified']).'. ';
		echo DIV_end();
	}
?>
     
    <?php if ($opt['display_items_summary']) echo $childcategory['summary']; ?> 
    <a class="readmore" href="<?php echo $link; ?>">Read More</a>
    <?php endif; ?>
    <?php endif; ?>
  <?php endforeach; ?>
<?php endif; ?>
 <?php if ($items && $opt['display_items']):
		$info_array['display'] = 'item';
        $info_array['id'] = $item['id'];
 	
 ////////////////////////////////////////////
	foreach ($items as $item):
	if ($item['status'] == 1 && $gekko_current_user->hasReadPermission($item['permission_read']) && ( $item['date_expiry'] == NULL_DATE || daysDifferenceFromToday($item['date_expiry']) > 0 )): 
   
  	$link = $this->createFriendlyURL("action=viewitem&id={$item['id']}");
	$info_array['url'] = $link;
  	if (!$opt['display_items_readmore_link']): 
   ?>
    <h2 class="blog_category_item"><a class="blog_category_item" href="<?php echo $link; ?>"><?php echo htmlspecialchars($item['title']); ?></a></h2>
    <?php if ($opt['display_items_summary']) echo $this->processOutputWithFilter($item['summary'],$current_method, $info_array); ?>
   <?php else: ?>
   <h2 class="blog_category_item"><?php echo htmlspecialchars($item['title']); ?></h2>
<?php
	if ($opt['display_items_date_created'] || $opt['display_items_date_modified'])
	{								
		$author_title = ''; $author = $gekko_current_user->getItemByID($item['created_by_id']); if ($author && $opt['display_items_author']) $author_title = ' by '.$author['username']; 	
		echo DIV_start('','blog_dates');
		if ($opt['display_items_date_created'] &&  $item['date_created'] != NULL_DATE) echo 'Created on '.str_replace(' ',' at ',$item['date_created']).$author_title.'. ';
		if ($opt['display_items_date_modified'] &&  $item['date_modified'] != NULL_DATE) echo 'Last updated on '.str_replace(' ',' at ',$item['date_modified']).'. ';
		echo DIV_end();
	}
        
?>

   <?php if ($opt['display_items_summary']) echo $this->processOutputWithFilter($item['summary'],$current_method, $info_array); ?>
    <a class="readmore" href="<?php echo $this->createFriendlyURL("action=viewitem&id={$item['id']}");; ?>">Read More</a> 
    <div class="post_separator"></div>
    <?php endif; ?>
   <?php endif; ?>
  <?php endforeach; ?>
<?php $pagination_str = $this->displayItemPagination($pg,$pagination['total'],"action=viewcategory&cid={$category['cid']}"); ?>
<?php if ($pagination_str): ?>
<div class="pagination">    
<?php echo $pagination_str; ?>
</div>
<?php endif?>

 <?php endif; ?>

