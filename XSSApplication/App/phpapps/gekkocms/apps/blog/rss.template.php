<?php if (!defined('GEKKO_VERSION')) die(); ?>
<?php global $gekko_current_user; ?>
<?php echo '<?xml version="1.0" encoding="utf-8"?>' ;?>
<?php if (file_exists(SITE_PATH.$css_path)): ?>
<?php echo '<?xml-stylesheet type="text/css" href="'; ?><?php echo SITE_URL.SITE_HTTPBASE.$css_path; ?><?php echo '"?>'; ?>
<?php endif; ?>
<rss version="2.0">
<channel>
<title><?php echo $rss_blog_title; ?></title>
<link><?php echo SITE_URL.SITE_HTTPBASE; ?></link>
<description><?php echo $rss_desc; ?></description>
<generator>Baby Gekko</generator>
<lastBuildDate><?php echo date("D, d M Y H:i:s O",strtotime($latestposts[0]['date_created'])); ?></lastBuildDate>
<language>en-us</language>    
<?php for ($i=0; $i < $rss_maxposts;$i++ ): ?>
<?php $post=$latestposts[$i]; 
if ($post['status'] == 1  && /*$gekko_current_user->hasReadPermission($post['permission_read']) &&*/ ( $post['date_expiry'] == NULL_DATE || daysDifferenceFromToday($post['date_expiry']) > 0 )): ?>
<?php 
		// you can customize your own stuff here
		$link = SITE_URL.$this->createFriendlyURL("action=viewitem&id={$post['id']}");
		$entrydate = date("D, d M Y H:i:s O",strtotime($post['date_created']));
		$entrytitle = $post['title'];
		
		// Must be absolute path - Prana - Feb 3, 2011
        $post['summary'] = preg_replace('/(?<=href=")(?!http:\/\/|\/\/)(\/.*?)(?=")/i', SITE_URL.'\\1', $post['summary']);
        $post['summary'] = preg_replace('/(?<=src=")(?!http:\/\/|\/\/)(\/.*?)(?=")/i', SITE_URL.'\\1',  $post['summary']);
        		
?>
<item>
<pubDate><?php echo $entrydate; ?></pubDate>
<title><?php echo htmlspecialchars($entrytitle); ?></title>
<link><?php echo $link; ?></link>
<guid><?php echo $link; ?></guid>
<description><![CDATA[ <?php echo ($post['summary']); ?>]]></description>
</item>
<?php endif; ?>
<?php endfor; ?>
  </channel>
</rss>

