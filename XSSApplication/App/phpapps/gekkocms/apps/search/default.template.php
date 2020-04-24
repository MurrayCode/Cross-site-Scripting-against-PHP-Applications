
<br /><br />
<div align="center"><form id="form1" name="form1" method="get" action="<?php echo $this->createFriendlyURL('action=search'); ?>" enctype="multipart/form-data" >
<label><input type="text" name="keyword" id="keyword" value="<?php echo $_GET['keyword']; ?>" /></label>
  <button type="submit" name="page" id="submit" value="1"><img src="<?php echo SITE_HTTPBASE; ?>/images/search.png" />Search</button>
</form></div>