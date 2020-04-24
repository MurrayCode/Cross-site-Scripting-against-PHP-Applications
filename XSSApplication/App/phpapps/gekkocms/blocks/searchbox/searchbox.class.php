<?php

class searchboxBlock extends basicBlock
{
	public function Run()
	{
		$custom_id = $this->config['str_css_id'];
		include ('searchform.template.php');
	}
}
?>