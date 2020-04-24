<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
	if (!defined('GEKKO_VERSION')) die();
	
	global $Application, $gekko_current_user; 
	
	$appname = $Application->app_name;
	$opt = $category_meta_options;
    $info_array = array ('app'=>$this->app_name,'mode'=>'list','display'=>'category','cid'=>$category[$this->field_category_id]);
//	checkPageOutputDatesAndStatus($category);
	if (!checkPageOutputDatesAndStatus($category, $opt)) return false;

	if ($opt['display_pagetitle']) echo H1(SAFE_HTML($category['title']),"html_category_pagetitle-{$category['cid']}",'html_category_pagetitle');
	
	$author_title = ''; $author = $gekko_current_user->getItemByID($item['created_by_id']); 
	if ($author && $opt['display_items_author']) $author_title = ' by '.$author['username'];
	
	$combined_text = $this->processOutputWithFilter($category['summary'].$category['description'],$current_method,$info_array);
	if ($error_message) echo P($error_message,'','display-error-message');
	echo $combined_text;
	if ($opt['display_category_date_created'] &&  $category['date_created'] != NULL_DATE) echo DIV_start('','html_category_datecreated').'Created on '.str_replace(' ',' at ',$category['date_created']).$author_title.'. '.DIV_end();
	if ($opt['display_category_date_modified'] &&  $category['date_modified'] != NULL_DATE) echo DIV_start('','html_category_datemodified').'Last updated on '.str_replace(' ',' at ',$category['date_modified']).'. '.DIV_end();
	
?>

<?php if ($childcategories && $opt['display_childcategories']): ?>
<ul class="html_child_category">
  <?php foreach ($childcategories as $childcategory): ?>
  <?php if ($childcategory['status'] == 1): ?>
  <li class="html_child_category">
  <?php  
  	$link = $this->createFriendlyURL("action=viewcategory&cid={$childcategory['cid']}");
  	if (!$opt['display_items_readmore_link']): 
   ?>
    <h2 class="html_child_category"> <a class="html_child_category" href="<?php echo $link; ?>"><?php echo htmlspecialchars($childcategory['title']); ?></a></h2>
    <?php if ($opt['display_items_summary']) echo $childcategory['summary']; ?>
    <?php else: ?>
	<h2 class="html_child_category"><?php echo htmlspecialchars($childcategory['title']); ?></h2>  

    
<?php
	if ($opt['display_items_date_created'] || $opt['display_items_date_modified'])
	{					
		$author_title = ''; $author = $gekko_current_user->getItemByID($childcategory['created_by_id']); if ($author && $opt['display_items_author']) $author_title = ' by '.$author['username']; 
		echo DIV_start('','html_category_item_date');
		if ($opt['display_items_date_created'] &&  $childcategory['date_created'] != NULL_DATE) echo 'Created on '.str_replace(' ',' at ',$childcategory['date_created']).$author_title.'. ';
		if ($opt['display_items_date_modified'] &&  $childcategory['date_modified'] != NULL_DATE) echo 'Last updated on '.str_replace(' ',' at ',$childcategory['date_modified']).'. ';
		echo DIV_end();
	}
?>
     
    <?php if ($opt['display_items_summary']) echo $childcategory['summary']; ?> 
    <a class="html_child_category" href="<?php echo $link; ?>">Read More</a> </li>
    <?php endif; ?>
    <?php endif; ?>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if ($items && $opt['display_items']):  ?>
    <ul class="html_category_item">
      <?php foreach ($items as $item): ?>
      <?php if ($item['status'] == 1): ?>
      <li class="html_category_item">
      <?php 
      
        $link = $this->createFriendlyURL("action=viewitem&id={$item['id']}");
        if (!$opt['display_items_readmore_link']): 
       ?>
        <h3 class="html_category_item"><a class="html_category_item" href="<?php echo $link; ?>"><?php echo htmlspecialchars($item['title']); ?></a></h3>
        <?php if ($opt['display_items_summary']) echo $item['summary']; ?>
       <?php else: ?>
       <h3 class="html_category_item"><?php echo htmlspecialchars($item['title']); ?></h3>
    <?php
        if ($opt['display_items_date_created'] || $opt['display_items_date_modified'])
        {								
            $author_title = ''; $author = $gekko_current_user->getItemByID($item['created_by_id']); if ($author && $opt['display_items_author']) $author_title = ' by '.$author['username']; 	
            echo DIV_start('','html_category_item_date');
            if ($opt['display_items_date_created'] &&  $item['date_created'] != NULL_DATE) echo 'Created on '.str_replace(' ',' at ',$item['date_created']).$author_title.'. ';
            if ($opt['display_items_date_modified'] &&  $item['date_modified'] != NULL_DATE) echo 'Last updated on '.str_replace(' ',' at ',$item['date_modified']).'. ';
            echo DIV_end();
        }
    
            $info_array['display'] = 'item';
            $info_array['id'] = $item['id'];
    
    ?>
       <?php if ($opt['display_items_summary']) echo $this->processOutputWithFilter($item['summary'],$current_method,$info_array); ?>
        <a class="html_category_item" href="<?php echo $this->createFriendlyURL("action=viewitem&id={$item['id']}");; ?>">Read More</a> </li>
        <?php endif; ?>
       <?php endif; ?>
      <?php endforeach; ?>
    </ul>

<?php endif; ?>
<!-- pagination -->
<?php $pagination_str = $this->displayItemPagination($pg,$pagination['total'],"action=viewcategory&cid={$category['cid']}"); ?>
<?php if ($pagination_str): ?>
	<div class="pagination"><?php echo $pagination_str; ?></div>
<?php endif?>

