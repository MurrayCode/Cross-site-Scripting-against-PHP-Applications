<?php
	 $new_url_query_string = 'action=search';
	 if ($_GET['keyword']) $new_url_query_string.= "&keyword={$_GET['keyword']}";
	 $i = 0;
     if ($items) :
		foreach ($items as $item):
		$article = $item;
		$summary = mb_substr( strip_tags($article['summary']),0, 150).'...';
		$theobj = $item['object'];
		$methods = get_class_methods($theobj);
		if (in_array('createFriendlyURL',$methods))
			$articlelink =  $theobj->createFriendlyURL("action=viewitem&id={$article['id']}");
		$i++;
?>
<h3 class="search_result_title"><?php echo SAFE_HTML($article['title']); ?></h3>
<div class="search_result_text">
<p class="search_result_text">
<?php echo $summary; ?>
<br/><a class="search_result_link" href="<?php echo $articlelink; ?>">[Read More...]</a>
</p>
</div>
<?php endforeach; ?>
<?php else: ?>
<p>No result for keyword <?php echo $_GET['keyword']; ?></p>
<?php $this->displayMainPage(); ?>
<?php endif; ?>
<br />
<?php $pagination_str = $this->displayItemPagination($pg,$pagination['total'],$new_url_query_string); ?>
<?php if ($pagination_str): ?>
	<div class="pagination"><?php echo $pagination_str; ?></div>
<?php endif?>

 
  <!-- end pagination -->