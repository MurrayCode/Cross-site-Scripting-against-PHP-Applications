<?php

include_app_class('blog');

class blogcategoriesBlock extends basicBlock
{
	public function Run()
	{
		$block_title = $this->config['str_block_title'];

		$myblog = new blog();
		$allcategories = $myblog->getAllCategories($myblog->getFieldCategoryID().", title",'status > 0',0,0,'title','ASC');
		include('blogcategories.template.php');
  	}
}

?>