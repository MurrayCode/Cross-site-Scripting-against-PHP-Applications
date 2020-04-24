<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

include_inc('securimage/securimage.php');

class contacts extends basicApplicationSimpleCategories
{
    public function __construct()
    {
 		$data_items = createDataArray ('id','status','category_id','virtual_filename','title','contact_person','branch','street','city','province','country','postal','tollfree','phone','fax','mobile','email','display_email','additional_info','additional_options','sort_order');
		$data_categories = createDataArray ('cid','status','parent_id','title','summary','sort_order','virtual_filename');
		parent::__construct('contacts','Contacts','gk_contact_items', 'id', $data_items, 'gk_contact_categories', 'cid', $data_categories);
		$this->enable_captcha = $this->getConfig('chk_enable_captcha');  		

    }

//_______________________________________________________________________________________________________________//

	public function displayItemByID($id=1,$from_cache=false)
	{
		if (isset($_POST['contact_id'])) $_POST['contact_id'] = intval ($_POST['contact_id']);
		if (isset($_POST['phone'])) $_POST['phone'] = cleanInput ($_POST['phone']);
		if (isset($_POST['email'])) $_POST['email'] = cleanInput ($_POST['email']);
		if (isset($_POST['name'])) $_POST['name'] = cleanInput ($_POST['name']);
		if (isset($_POST['verification_code'])) $_POST['verification_code'] = cleanInput ($_POST['verification_code']);		
		if (isset($_POST['subject'])) $_POST['subject'] = cleanInput ($_POST['subject']);
		if (isset($_POST['message'])) $_POST['message'] = cleanInput ($_POST['message']);								
		parent::displayItemByID($id,$from_cache);
	}

//_______________________________________________________________________________________________________________//
 	public function displayMainPage()
	{
		global $gekko_db;
		
		$item_count = $this->getTotalItemCount('',$this->cache);
		if ($item_count ==0)
		{
			echo P('Undefined contact items. Please insert a record from the backend');
		}
		if ($item_count == 1)
		{
			$sql = "SELECT id FROM {$this->table_items}";
			$reply = $gekko_db->get_query_result($sql,true);
			$this->displayItemByID($reply[0]['id']);
		} else if ($item_count > 1) 
		{
			$sql = "SELECT * FROM {$this->table_categories}";
			$categories = $gekko_db->get_query_result($sql,true);
			
			if (count($categories) == 1) $this->displayItemsInCategoryByID($categories[0]['cid'],$command['pg'],'date_created','DESC');
				else
			{
				$this->setPageTitle($this->app_description);
				include('mainpage.template.php');
			}
		}
	}
//_______________________________________________________________________________________________________________//
	public function sendMessageToContact($contactid, $phone,$email, $subject, $message)
	{
		if (!verify_email_dns ($email))
		{
			echo H4('Your e-mail address cannot be verified. Your IP address has been logged into this system');
			return false;
		}
		
		$user = $this->getItemByID(intval($contactid)); // yupe .. gotta be int
		if ($user)
		{
			$destination = $user['email'];
			$sender = $email;
			$subject = "Website Contact from ".$_SERVER['REMOTE_ADDR'];
			$header = "From: {$email}";
			mail ($destination, $subject, $message, $header);
			echo H1('Thank You');
			echo P('Your message has been sent');
		} else echo H4('User not found error. Mail cannot be sent');
	}
//_______________________________________________________________________________________________________________//	
	public function Run($command)
	{
		switch ($command['action'])
		{
			//case 'captcha': $this->displayCaptcha();return false;break;
			default: return parent::Run($command);
		}
		return false;
	}
//_______________________________________________________________________________________________________________//
	
}
	
?>