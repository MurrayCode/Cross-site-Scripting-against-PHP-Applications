<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko. Coded by Prana.
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
	if (!defined('GEKKO_VERSION')) die();
	
	global $Application, $gekko_current_user; 
	
	$appname = $Application->app_name;
    $info_array = array ('app'=>$this->app_name,'mode'=>'list');

	//if ($category['status'] != 1) {echo H3(SAFE_HTML('This category is inactive'));return false;} 	
	//if (!( $category['date_expiry'] == NULL_DATE || daysDifferenceFromToday($category['date_expiry']) > 0 )) { echo H3('This category has expired.');return false;}
//	if (daysDifferenceFromToday($category['date_available']) > 0 ) { echo H3(SAFE_HTML('This category is not available yet at this time.'));return false;}
	
// 	if (!$gekko_current_user->hasReadPermission($category['permission_read'])) { echo H3('This category is not available for your user group.');return false;}
//	if ($category[$this->getFieldCategoryID()] == 0) $category['title'] = $Application->app_description; 
  echo H1(SAFE_HTML($category['title']),'','html_category_pagetitle');
	
	$author_title = ''; $author = $gekko_current_user->getItemByID($item['created_by_id']); 
	if ($author && $opt['display_items_author']) $author_title = ' by '.$author['username'];
	$info_array['display'] = 'category';
	$info_array['cid'] = $category['cid'];
	$combined_text = $this->processOutputWithFilter($category['summary'].$category['description'],$current_method,$info_array);
	if ($error_message) echo P($error_message);
	echo $combined_text;
	if ($opt['display_category_date_created'] &&  $category['date_created'] != NULL_DATE) echo DIV_start('','html_category_datecreated').'Created on '.str_replace(' ',' at ',$category['date_created']).$author_title.'. '.DIV_end();
	if ($opt['display_category_date_modified'] &&  $category['date_modified'] != NULL_DATE) echo DIV_start('','html_category_datemodified').'Last updated on '.str_replace(' ',' at ',$category['date_modified']).'. '.DIV_end();
	
?>


<?php if ($items):  ?>
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

