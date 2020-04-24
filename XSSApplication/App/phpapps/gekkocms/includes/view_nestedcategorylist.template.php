<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
?>
<?php global $Application; $appname = $Application->app_name; ?>
<?php if ($category['cid'] == 0) $category['title'] = $Application->app_description; ?>
<h1><?php echo SAFE_HTML($category['title']); ?></h1>
<?php echo $category['summary'].$category['description']; ?>

<?php if ($childcategories): ?>
<ul class="nested_child_category">
  <?php foreach ($childcategories as $childcategory): ?>
  <li class="nested_child_category">
    <h3 class="nested_child_category"><?php echo SAFE_HTML($childcategory['title']); ?></h3>
    <a class="nested_child_category" href="<?php echo $this->createFriendlyURL("action=viewcategory&cid={$childcategory['cid']}");; ?>">Read More</a> </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if ($items): ?>
<ul class="nested_listing_item">
  <?php foreach ($items as $item): ?>
  <li class="nested_listing_item">
    <h4 class="nested_listing_item"><?php echo SAFE_HTML($item['title']); ?></h4>
    <a class="nested_listing_item" href="<?php echo $this->createFriendlyURL("action=viewitem&id={$item['id']}");; ?>">Read More</a> </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>
