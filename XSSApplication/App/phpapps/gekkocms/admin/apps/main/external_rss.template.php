<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>External RSS</title>
<link href="<?php echo SITE_HTTPBASE; ?>/admin/apps/main/rss.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php
$simplerss = simplexml_load_file($rss_url);
$rss_count = count ($simplerss->channel->item);
$last_build_date=date("Y-M-d",strtotime($simplerss->channel->lastBuildDate));
echo "<div class=\"rss_build_date\">Last Update: {$last_build_date}</div>";

echo '<ul>';
for($i=0;$i<$rss_count;$i++)
   {
	   $link = $simplerss->channel->item[$i]->link;
	   $title = $simplerss->channel->item[$i]->title;
	   $pubdate = $simplerss->channel->item[$i]->pubDate;
	   $date=date("Y-M-d",strtotime($pubdate));
  echo "<li><span class=\"rss_date\">{$date}</span>: <a href=\"{$link}\" target=\"_blank\">{$title}</a></li>\n";
   }
echo '</ul>';   
?>
</body>
</html>
