<?php

include_app_class('blog');

class latestblogBlock extends basicBlock
{
	public function Run()
	{
		$max_entries = $this->config['int_max_entries'];
		$block_title = $this->config['str_block_title'];

		$myblog = new blog();
		($max_entries == 0) ? $max_entries = 15 : $max_entries=$max_entries;
		
		$latestposts = $myblog->getAllItems('*','',0,$max_entries,'date_created','DESC',' status = 1 ',true);
		
		include('entry.template.php');
  	}
}

?>