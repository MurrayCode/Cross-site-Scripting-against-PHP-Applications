<?php

class loginboxBlock extends basicBlock
{
	public function Run()
	{
		global $gekko_config, $gekko_current_user;
		
		$force_ssl = $gekko_config->get(DEFAULT_USER_CLASS,'force_ssl_authentication');
		$login_url = $gekko_current_user->createFriendlyURL("action=login");
		if ($force_ssl)
			$login_url = force_HTTPS_url().$login_url;
		
		include ('loginbox.template.php');	
  	}
}

?>