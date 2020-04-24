<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
	if (!defined('GEKKO_VERSION')) die();
?>
<div class="searchbox" <?php if ($custom_id) echo 'id="'.$custom_id.'"';?>>
  <form action="<?php echo SITE_HTTPBASE; ?>/search/" method="get">
    <input alt="search" class="inputbox" type="text" name="keyword" size="20" value="search..."  onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value=='search...') this.value='';" />
    <input type="hidden" name="page" value="1" />
  </form>
</div>
