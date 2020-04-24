<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
?>
<h1><?php echo SAFE_HTML($item['title']); ?></h1>
<?php if( $item['summary']) echo $item['summary']; ?>
<?php if( $item['description']) echo $item['description']; ?>
