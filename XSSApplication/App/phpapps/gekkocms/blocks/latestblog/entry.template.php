<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
 if (!defined('GEKKO_VERSION')) die();

 global $gekko_current_user; ?>
<div id="<?php echo $this->block_name; ?>">
<h3><?php echo $block_title; ?></h3>
<ul class="latestnews">
  <?php for ($i=0; $i < $max_entries;$i++ ): ?>
  <?php 
  		$post=$latestposts[$i];
		$entry = $post['date_created'];
		// you can customize your own stuff here
		$link = $myblog->createFriendlyURL("action=viewitem&id={$post['id']}");
		$entrydate = date("j.M.Y",strtotime($post['date_created']));
		$entrytitle = $post['title'];
		if ($post['status'] == 1 && $gekko_current_user->hasReadPermission($post['permission_read']) && ( $post['date_expiry'] == '0000-00-00 00:00:00' || daysDifferenceFromToday($post['date_expiry']) > 0 )):
?>
  <li><?php echo $entrydate; ?><br /><a href="<?php echo $link; ?>"><?php echo SAFE_HTML($entrytitle); ?></a></li>
  
  <?php endif; endfor; ?>
</ul>
</div>