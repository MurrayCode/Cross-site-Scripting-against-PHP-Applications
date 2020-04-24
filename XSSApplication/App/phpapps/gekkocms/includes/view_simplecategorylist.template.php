<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
?>
<?php global $Application; $appname = $Application->app_name; ?>

<h1><?php echo $category['title']; ?></h1>
<?php echo $category['summary'].$category['description']; ?>
<br/><br/>
<?php foreach ($childcategories as $child): ?>
<h2><A HREF="<?php echo $this->createFriendlyURL("action=viewcategory&cid={$child['cid']}"); ?>"><?php echo SAFE_HTML($child['title']); ?></A></h2>
<?php echo $item['summary']; ?>
<?php endforeach; ?>
<hr/>
<?php if ($items): ?>
<ul class="nested_listing">
  <?php foreach ($items as $item): ?>
  <li class="nested_listing">
    <h3 class="nested_listing"><?php echo SAFE_HTML($item['title']); ?></h3>
    <a class="nested_listing" href="<?php echo $this->createFriendlyURL("action=viewitem&id={$item['id']}");; ?>">Read More</a> </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>
