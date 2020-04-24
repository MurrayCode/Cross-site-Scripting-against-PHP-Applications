<?php global $Application; $appname = $Application->app_name; ?>

<h1>CAT <?php echo $category['title']; ?></h1>
<?php echo $category['summary'].$category['description']; ?>
<?php foreach ($items as $item): ?>
<h2><?php echo $item['title']; ?></h2>
<?php echo $item['summary']; ?>
<A HREF="<?php echo "index.php?app={$appname}&action=view&id={$item['id']}"; ?>">Read More</A>
<?php endforeach; ?>
