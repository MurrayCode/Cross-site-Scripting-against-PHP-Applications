<?php 
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
if (!defined('GEKKO_VERSION')) die();
global $gekko_current_user;

$pagination = getStartAndEndForItemPagination($pg, $max_posts_perpage,$total_item_count);
$info_array = array ('app'=>$this->app_name,'mode'=>'main','display'=>'item','id'=>0,'url'=>'');
?>
<h1 class="blog_title"><?php echo htmlspecialchars($blog_title); ?></h1>
<?php for ($i = 0; $i < $total_item_count;$i++ ): ?>
<?php $post=$latestposts[$i];
	if ($post['status'] == 1 && $gekko_current_user->hasReadPermission($post['permission_read']) && ( $post['date_expiry'] == NULL_DATE || daysDifferenceFromToday($post['date_expiry']) > 0 ) ): 
	// you can customize your own stuff here
	$link = $this->createFriendlyURL("action=viewitem&id={$post['id']}");
	$entrydate = date("D, d M Y H:i:s O",strtotime($post['date_created']));
        $info_array['id'] = $post['id'];
        $info_array['url'] = $link;
?>
    <h2 class="blog_item_title"><a href="<?php echo $link; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h2>
    <div class="blog_dates" ><?php echo $entrydate;echo daysDifferenceFromToday($post['date_expiry']); ?></div>
    <?php echo $this->processOutputWithFilter($post['summary'],$current_method,$info_array); ?>
    <div class="post_separator"></div>
<?php endif;endfor; ?>
<!-- pagination -->
<?php $pagination_str = $this->displayItemPagination($pg,$pagination['total'],"action=main"); ?>
<?php if ($pagination_str): ?>
	<div class="pagination"><?php echo $pagination_str; ?></div>
<?php endif?>
<!-- end pagination -->