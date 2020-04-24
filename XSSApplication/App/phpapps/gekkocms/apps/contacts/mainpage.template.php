<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko. Coded by Prana.
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
	if (!defined('GEKKO_VERSION')) die();
	
	global $Application, $gekko_current_user; 
	
	$appname = $Application->app_name;
    $info_array = array ('app'=>$this->app_name,'mode'=>'list');

  echo H1(SAFE_HTML('Contacts'),'','html_category_pagetitle');
	
	$author_title = ''; $author = $gekko_current_user->getItemByID($item['created_by_id']); 
	if ($author && $opt['display_items_author']) $author_title = ' by '.$author['username'];
	$info_array['display'] = 'category';
	$info_array['cid'] = $category['cid'];
	$combined_text = $this->processOutputWithFilter($category['summary'].$category['description'],$current_method,$info_array);
	if ($error_message) echo P($error_message);
	echo $combined_text;
	if ($opt['display_category_date_created'] &&  $category['date_created'] != '0000-00-00 00:00:00') echo DIV_start('','html_category_datecreated').'Created on '.str_replace(' ',' at ',$category['date_created']).$author_title.'. '.DIV_end();
	if ($opt['display_category_date_modified'] &&  $category['date_modified'] != '0000-00-00 00:00:00') echo DIV_start('','html_category_datemodified').'Last updated on '.str_replace(' ',' at ',$category['date_modified']).'. '.DIV_end();
	
?>



<?php if ($categories ): ?>
  <?php foreach ($categories as $category): ?>

  <?php if ($category['status'] == 1): ?>
  <?php  
  	$link = $this->createFriendlyURL("action=viewcategory&cid={$category['cid']}");
   ?>
    <h2 class="blog_child_category"> <a class="blog_child_category" href="<?php echo $link; ?>"><?php echo htmlspecialchars($category['title']); ?></a></h2>
    <?php if ($opt['display_items_summary']) echo $category['summary']; ?>
    <?php else: ?>
	<h2 class="blog_child_category"><?php echo htmlspecialchars($category['title']); ?></h2>  

    
<?php
	if ($opt['display_items_date_created'] || $opt['display_items_date_modified'])
	{					
		$author_title = ''; $author = $gekko_current_user->getItemByID($category['created_by_id']); if ($author && $opt['display_items_author']) $author_title = ' by '.$author['username']; 
		echo DIV_start('','blog_dates');
		if ($opt['display_items_date_created'] &&  $category['date_created'] != '0000-00-00 00:00:00') echo 'Created on '.str_replace(' ',' at ',$category['date_created']).$author_title.'. ';
		if ($opt['display_items_date_modified'] &&  $category['date_modified'] != '0000-00-00 00:00:00') echo 'Last updated on '.str_replace(' ',' at ',$category['date_modified']).'. ';
		echo DIV_end();
	}
?>
     
    <?php if ($opt['display_items_summary']) echo $category['summary']; ?> 
    <a class="readmore" href="<?php echo $link; ?>">Read More</a>
    <?php endif; ?>
  <?php endforeach; ?>
<?php endif; ?>
<!-- pagination -->
<?php $pagination_str = $this->displayItemPagination($pg,$pagination['total'],"action=viewcategory&cid={$category['cid']}"); ?>
<?php if ($pagination_str): ?>
	<div class="pagination"><?php echo $pagination_str; ?></div>
<?php endif?>

