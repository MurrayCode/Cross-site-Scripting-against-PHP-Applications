<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
	if (!defined('GEKKO_VERSION')) die();
?>
<?php global $Application; $appname = $Application->app_name; ?>

<h1><?php echo $category['title']; ?></h1>
<?php echo $category['summary'].$category['description']; ?>
<?php foreach ($childcategories as $child): ?>
<h2><A HREF="<?php echo "index.php?app={$appname}&action=list&id={$child['id']}"; ?>"><?php echo $child['title']; ?></A></h2>
<?php echo $item['summary']; ?>
<?php endforeach; ?>
<?php foreach ($items as $item): ?>
<h3><?php echo $item['title']; ?></h3>
<?php echo $item['summary']; ?>
<br />
<A HREF="<?php echo "index.php?app={$appname}&action=displayitem&id={$item['id']}"; ?>">Read More</A>
<?php endforeach; ?>
