<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
	if (!defined('GEKKO_VERSION')) die();
?>
<div id="<?php echo $this->block_name; ?>">
<h3><?php echo $block_title; ?></h3>
<ul class="blogcategories">
  <?php foreach ($allcategories as $category): ?>
  <?php 
   		$link = $myblog->createFriendlyURL("action=viewcategory&cid={$category['cid']}");
 ?>
  <li><A HREF="<?php echo $link; ?>"><?php echo SAFE_HTML($category['title']); ?></a></li>
  <?php endforeach; ?>
</ul>
</div>