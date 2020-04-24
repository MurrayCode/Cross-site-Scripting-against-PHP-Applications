<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
if (!defined('GEKKO_VERSION')) die();

class users extends basicApplicationMultipleCategories
{
	public $time, $currentUserID;
	var $failed = false;
	var $backend_mode = false;
	protected $field_password = 'password'; // TODO
	protected $field_username = 'username';	 // TODO
	protected $field_groupname = 'groupname';
	//_________________________________________________________________________// 	
    public function __construct($backend_mode = false)
    {
 		$data_items = createDataArray ('id','status',$this->field_username,$this->field_password,'firstname','lastname','email_address','status','date_available','date_expiry','date_created','date_modified','sort_order', 'cookie','session','ip','date_last_failed_login_attempt','date_last_logged_in','total_failed_login_attempt','activation_string','additional_info');
		$data_categories = createDataArray ('cid','status',$this->field_groupname);
		$data_categories_items = createDataArray ('cid','id');
		parent::__construct('users', 'Users', 'gk_user_items', 'id', $data_items, 'gk_user_categories', 'cid', $data_categories,'gk_user_categories_items',$data_categories_items);
		$this->time = time();
		$this->backend_mode = $backend_mode;
		$_SESSION['authenticated'] = $this->verifyCurrentSession();		
	}
	//_________________________________________________________________________//	
	public function getPasswordFieldName()
	{
		return $this->field_password;	
	}
	//_________________________________________________________________________//	
	public function getUsernameFieldName()
	{
		return $this->field_username;	
	}	
	//_________________________________________________________________________//	
	public function performAuthentication($username, $password, $rememberpassword)
	{
		global $gekko_db;
		
		$user = $this->verifyUserNamePassword($username,$password);
		if ($user)
		{
			if ($user['status'] == 1)
			{
				$this->setUserSessionInformation($user, $rememberpassword);
				$_SESSION['login_error'] = '';
			} else $_SESSION['login_error'] = 'ERROR: User is inactive';
		}
		 else
		{
			header('HTTP/1.0 401 Unauthorized');
			
			$_SESSION['login_error'] = 'Invalid username/password';
		}
	}	
	
	//_________________________________________________________________________// 	
	public function setUserSessionInformation($user, $rememberpassword)
	{
		global $gekko_db;
	
 		$this->currentUserID = $user[$this->field_id];
		$_SESSION['userid'] = $user[$this->field_id];
		$_SESSION['username'] = $user[$this->field_username];
		$_SESSION['authenticated'] = true;
	//	$_SESSION['groupname'] = $this->getGroupNameByGroupID($user[$this->field_id]);	
		$_SESSION['groupids'] = $this->getCurrentUserGroupIDs();		
		$_SESSION['login_error'] = '';
		$cookiestr = "''"; // no cookie
		if ($rememberpassword) 
		{
			srand((double) microtime() * 1000000);
			$cvx = sha1(uniqid(rand(), true));
			setcookie('cvx', $cvx, time() + ADMIN_LOGIN_TIME);
			setcookie('uid', sha1($_SESSION['username']), time() + ADMIN_LOGIN_TIME);
			$cookiestr = "'{$cvx}'";
		}
		$session =sanitizeString(session_id());
		$ip =sanitizeString($_SERVER['REMOTE_ADDR']);
 		$sql = "UPDATE {$this->table_items} SET date_last_logged_in= NOW(), total_failed_login_attempt=0 WHERE id = {$user[$this->field_id]}";
		$gekko_db->query($sql);
		
		$sql = "INSERT IGNORE INTO gk_session_items (user_id, session_time, ip_address, session_string, session_cookie) VALUES ({$user[$this->field_id]}, NOW(),$ip,{$session},{$cookiestr})";
		
		$gekko_db->query($sql);
	} 	
	//_______________________________________________________________________________________________________________//			
	protected function _internalCreateUser($datavalues,$creategroup=true)
	{
		global $gekko_db, $gekko_config;
		
		$default_group_id = $gekko_config->get($this->app_name,'int_default_newuser_group_id');
		$sql_set_cmd = InsertSQL($datavalues);
		$gekko_db->query("LOCK TABLES {$this->table_items} WRITE");
		$sql =  "INSERT INTO {$this->table_items} ".$sql_set_cmd;
		$gekko_db->query($sql);
		$new_user_id = $gekko_db->last_insert_id();
		$gekko_db->query("UNLOCK TABLES");
		if ($creategroup)
		{
			$this->setItemCategory($new_user_id,$default_group_id,true);
		}
	}	
	//_______________________________________________________________________________________________________________//
	public function getItemsByCategoryID($id,$fields='*',$extra_criteria='',$start=0,$end=0,$sortby='', $sortdirection='ASC',$from_cache=false)
	{
		global $gekko_db;
		if ($field=='*') $fieldnames = array_keys($this->getItemFieldNames()); else  $fieldnames = explode(',',$fields);
		$total_fieldname_count = count($fieldnames);
		for ($i=0;$i < $total_fieldname_count; $i++) $fieldnames[$i]= $this->table_items.'.'.$fieldnames[$i];
		$fields_tobe_selected = implode(',',$fieldnames);
		
		if (!empty($extra_criteria)) $criteria_txt = " AND {$extra_criteria}"; else $criteria_txt = '';
		
		if ($id ==0) /*$the_criteria = "RIGHT OUTER JOIN {$this->table_categories_items} ON {$this->table_categories_items}.{$this->field_id} != {$this->table_items}.{$this->field_id} {$criteria_txt}";*/
		$the_criteria = " WHERE {$this->table_items}.{$this->field_id} NOT IN (SELECT {$this->field_id} FROM {$this->table_categories_items})";
			else 
		$the_criteria = "LEFT JOIN {$this->table_categories_items} ON {$this->table_categories_items}.{$this->field_id} = {$this->table_items}.{$this->field_id} WHERE {$this->field_category_id} = '".intval($id)."' {$criteria_txt}";		
		
		$sql = selectSQL($this->table_items,$fields_tobe_selected,$the_criteria,$start,$end,$sortby, $sortdirection, false,SQL_ENFORCE_ROW_LIMIT);	
 		$items = $gekko_db->get_query_result($sql, $from_cache);
		
		return $items;		
	}
	
	//_________________________________________________________________________//    	
	public function getUserByUsernameAndPassword($input_username, $input_password,$input_groups=null)
	{
		global $gekko_db;
		
		$username =sanitizeString($input_username);
		$password =sanitizeString(sha1 ($input_username.$input_password));
		
		$group_str = '';
	
		if (is_array($input_groups))
		{
			$group_ids = implode(', ',$input_groups);
			$group_str = " AND {$this->table_categories_items}.{$this->field_category_id} IN ({$group_ids})";
		}
		$selected_fields = $this->getNonAmbiguousFieldNames($this->table_items);
		$sql = "SELECT {$selected_fields} FROM {$this->table_items} INNER JOIN {$this->table_categories_items} ON {$this->table_items}.{$this->field_id} = {$this->table_categories_items}.{$this->field_id} WHERE {$this->field_username} = {$username} AND {$this->field_password} = {$password} {$group_str}";
		$result  = $gekko_db->get_query_singleresult($sql);
		return $result;
	}
	
	//_________________________________________________________________________// 
 	public function verifyUserNamePassword($input_username, $input_password)
	{
		global $gekko_db, $gekko_config;
		
		$admin_group_check = '';
		if ($this->backend_mode == 1) 
		{
			$admin_group = $this->getGroupIDByGroupName(DEFAULT_ADMIN_GROUP);
			$groups_allowed_for_backend_login = $gekko_config->get($this->app_name,'groups_allowed_for_backend_login');
			if (!is_array($groups_allowed_for_backend_login)) $groups_allowed_for_backend_login = array($admin_group);
		}
		$result = $this->getUserByUsernameAndPassword($input_username, $input_password,$groups_allowed_for_backend_login);
		if (!$result)
			if ($this->userNameExists($input_username))
				$this->recordFailedLoginAttempt($input_username);
		return $result;
	}
	//_________________________________________________________________________// 		
	public function userNameExists($input_username)
	{
		global $gekko_db;
		
		$username =sanitizeString($input_username);		
		$sql =  "SELECT * from {$this->table_items} where {$this->field_username} = {$username}";
		$result  = $gekko_db->get_query_singleresult($sql);
		$thecount = count($result);
		return ($thecount > 0);
	}
	//_________________________________________________________________________// 
	public function getAllUserGroupNames($guest_included=true)
	{
		global $gekko_db;
		
		$sql =  "SELECT {$this->field_category_id}, {$this->field_groupname} from {$this->table_categories} ORDER by {$this->field_category_id}";
		$gekko_db->query($sql);
		$result  = $gekko_db->get_result_as_array();
		if ($guest_included)
		{
			$guest_user[] = array ("{$this->field_category_id}" => 0, groupname => "Everyone");
			if ($result) $result = array_merge($guest_user, $result);
		}
		return $result;			
	}
	//_________________________________________________________________________// 			
	public function getFailedLoginAttemptCount($input_username)
	{
		global $gekko_db;
		
		$username =sanitizeString($input_username);		
		$sql =  "SELECT total_failed_login_attempt from {$this->table_items} where {$this->field_username} = {$username}";
		$result  = $gekko_db->get_query_singleresult($sql);
		if ($result) $thecount = $result['total_failed_login_attempt']; else $result = 0;
		return $thecount;
	}	
	//_________________________________________________________________________// 		
	
	public function recordFailedLoginAttempt($input_username)
	{	
		global $gekko_db;
		
		$username =sanitizeString($input_username);	
		$x = $this->getFailedLoginAttemptCount($input_username);
		$x++;
		$sql = "UPDATE {$this->table_items} SET date_last_failed_login_attempt= NOW(), total_failed_login_attempt={$x} WHERE {$this->field_username} = {$username}";
		$gekko_db->query($sql);
	}
	//_________________________________________________________________________// 		
	public function verifyCurrentSession()
	{
		global $gekko_db;
		
		$valid_session = false;
		$username = sanitizeString($_SESSION['userid']);
		$username = sanitizeString($_SESSION['username']);
		$cookie   = sanitizeString($_SESSION['cookie']);
		$session  = sanitizeString(session_id());
		$ip = sanitizeString($_SERVER['REMOTE_ADDR']);
///		$sql = "SELECT * FROM {$this->table_items} WHERE (username = {$username}) AND (session = {$session}) AND (ip = {$ip}) AND (status=1)"; prana
		$sql = "SELECT {$this->table_items}.{$this->field_id}, status FROM  {$this->table_items} INNER JOIN gk_session_items ON user_id = {$this->table_items}.{$this->field_id} WHERE  (session_string = {$session}) AND (ip_address = {$ip})";
		//echo $sql;die;
		$user  = $gekko_db->get_query_singleresult($sql);
		if (!$user)
		{
			$user = $this->getUserByUserCookies();
			if ($user)
			{
				$this->setUserSessionInformation($user, 1);
				$valid_session = true;
			}
		} else $valid_session = true;
		if ($user && $user['status'] != 1) 
		{
			$valid_session = false;	
			$_SESSION['login_error'] = 'ERROR: User status is inactive';
		}
		if (!$valid_session) $this->resetSession();
		return $valid_session;
	}
	//_________________________________________________________________________//	
	public function getUserInfoByUserID($id)
	{
		global $gekko_db;
		$id = intval($id);
		$user = $gekko_db->get_query_singleresult( "SELECT * FROM {$this->table_items} WHERE id = {$id}");
		return $user;
	}
	//_________________________________________________________________________// 		
	public function getUserInfoByUserName($username)
	{
		global $gekko_db;
		$username =sanitizeString($username);	
		$sql =  "SELECT * FROM {$this->table_items} where {$this->field_username} = {$username}";
		$user = $gekko_db->get_query_singleresult($sql);
		return $user;
	}
	//_________________________________________________________________________// 		
	public function getUserByEmailAddress($input_email)
	{
		global $gekko_db;
		
		if (empty($input_email)) return false;
		$email =sanitizeString($input_email);
		$sql =  "SELECT * from {$this->table_items} where email_address <> '' AND  email_address = {$email}";

		$result  = $gekko_db->get_query_singleresult($sql);
		return $result;
	}

	//_________________________________________________________________________// 		
	public function displayMembersWelcomePage()
	{
		global $gekko_current_user;
		
		$this->page_title = 'Members Page';
		$this->loadTemplateFile('members_welcome_page',get_defined_vars());			
	}
	//_________________________________________________________________________// 		
	public function getCurrentUserInfo()
	{
		if ($_SESSION['userid'] > 0)
			$user = $this->getUserInfoByUserID($_SESSION['userid']);
		else
			$user = false;
		return $user;
	}
	//_________________________________________________________________________// 		
	
	public function getCurrentUserID()
	{
		if ($_SESSION['userid'] > 0)
			return $_SESSION['userid'];
		else
			return false;
	}

	//_________________________________________________________________________// 		
	public function getCurrentUserGroups()
	{
		$user = $this->getCurrentUserInfo();

		if ($user)
		{
			$group_infos = $this->getItemCategoriesByItemID($user[$this->field_id]);
			if ($group_infos)
			{
				return $group_infos;
			}
			else
				return false;
		}

 		return false;
	}	
	
	//_________________________________________________________________________// 		
	public function getCurrentUserGroupNames()
	{
		$user = $this->getCurrentUserInfo();
		if ($user)
		{
			$group_infos = $this->getItemCategoriesByItemID($user[$this->field_id]);
			if ($group_infos)
			{
				$group_names = array();
				foreach($group_infos as $info) $group_names[] = $info[$this->field_groupname];
				return $group_names;
			}
			else
				return false;
		}

 		return false;
	}	
	//_______________________________________________________________________________________________________________//		
	public function isCurrentUserMemberOfGroupName($groupname)
	{
		$groupnames = $this->getCurrentUserGroupNames();
		return in_array($groupname,$groupnames);
  	}	
	
	
	//_________________________________________________________________________// 		
	public function getCurrentUserGroupIDs()
	{
		$user = $this->getCurrentUserInfo();
		if ($user) return $this->getItemCategoryIDsByItemID($user[$this->field_id]);else return false;
	}	
	//_________________________________________________________________________// 		
	public function getCurrentUserName()
	{
		$user = $this->getCurrentUserInfo();
		if ($user) return $user[$this->field_username];else return false;
	}	
	
	
	//_________________________________________________________________________// 		
	public function displayUserInfoByID($user_id)
	{
		$user = $this->getUserInfoByUserID($user_id);
		$this->loadTemplateFile('userinfo',get_defined_vars());
	}	
	//_________________________________________________________________________// 	
	public function getGroupIDByGroupName($catname)
	{
		global $gekko_db;
		$catname =sanitizeString($catname);	
		$sql =  "SELECT {$this->field_category_id} from {$this->table_categories} where {$this->field_groupname} = {$catname}";
		$usergroup  = $gekko_db->get_query_singleresult($sql);
		if ($usergroup) return $usergroup[$this->field_category_id]; else return 0;
	}
	//_________________________________________________________________________// 	
	public function getGroupNameByGroupID($id)
	{
		global $gekko_db;
		$id = intval($id);
		$sql =  "SELECT {$this->field_groupname} from {$this->table_categories} where {$this->field_category_id} = {$id}";
		$usergroup  = $gekko_db->get_query_singleresult($sql);
		if ($usergroup) return $usergroup['groupname']; else return 0;
	}
	//_________________________________________________________________________// 	
	public function getUserByUserCookies()
	{
		global $gekko_db;
		
		$cvx = sanitizeString($_COOKIE['cvx']);
		$uid = sanitizeString($_COOKIE['uid']);
		$sql = "SELECT {$this->table_items}.{$this->field_id}, {$this->field_username}, status FROM  {$this->table_items} INNER JOIN gk_session_items ON user_id = {$this->table_items}.{$this->field_id} WHERE SHA1({$this->field_username}) = {$uid} AND session_cookie={$cvx}";

		$result  = $gekko_db->get_query_singleresult($sql);
		return $result;
	}
	//_________________________________________________________________________//  
	public function validateUserNameString($str) 
	{
		return preg_match('/^[a-zA-Z][\w\.-]*[a-zA-Z0-9]$/i',$str);
	}
	//_________________________________________________________________________//  
	public function validateEmailAddressString($str)
	{
		return preg_match("/^[a-zA-Z][\w\.-]*[a-zA-Z0-9]@[a-zA-Z][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/i", $str);
	}
	//_________________________________________________________________________//  
	public function resetSession()
	{
		global $gekko_db;
		
		$session  = sanitizeString(session_id());
		$sql = "DELETE FROM gk_session_items WHERE session_string = {$session}";
		$gekko_db->query($sql);		
		$_SESSION['authenticated'] = false;
		$_SESSION['userid'] = 0;
		$_SESSION['username'] = '';
	}
//_________________________________________________________________________//  	
	public function debugSession()
	{
		print "DEBUG Session ID ".session_id().": ";
		//debug_array($_SESSION);
		print "<br/>";
		print "DEBUG Cookies: ";
		debug_array($_COOKIE);
		print "<br/>";

	}
	
//_________________________________________________________________________// 
	public function logout()
	{
		setcookie("cvx", '', $this->time - 3600*3600);   // Sets the cookie username
		setcookie("uid", '', $this->time - 3600 * 3600);	// Sets the cookie password
	
		$this->resetSession();
		
	    session_unset();
		session_destroy();
	}
//_________________________________________________________________________// 
	public function adminAuthenticated ()
	{
		return $_SESSION['authenticated'];
	}	
//_________________________________________________________________________// 

	public function authenticated ()
	{
		return $_SESSION['authenticated'];
	}
//_______________________________________________________________________________________________________________//	
 	public function displayMainPage()
	{
		if ($this->authenticated())
		{
			$this->displayMembersWelcomePage();
		}
		else
			$this->processLoginRequest();
	}
//_______________________________________________________________________________________________________________//	
	
	public function saveItem($id)
	{
		if ($_POST['id'] == 'new') $_POST['date_created'] =  date('Y-m-d'); 
		$_POST['date_modified'] =  date('Y-m-d'); 
		$_POST['username'] = convert_into_sef_friendly_title($_POST['username']);
		if (array_key_exists('password', $_POST))
		{
			if ($_POST['password'] != '') 
			{
				 $_POST['password'] = sha1($_POST['username'].trim($_POST['password']));
			} else
			{
				unset ($_POST['password']); 	
			}
		}
		
		return parent::saveItem($id);
	}
//_______________________________________________________________________________________________________________//	
	public function validateSaveItem($data)
	{
		if ($data['username']=='') return false;
		return true;
	}
	
	//_______________________________________________________________________________________________________________//	
	public function findDuplicateUsers($data)
	{
		global $gekko_db;
		
		$current_id = $data[$this->field_id];
		$sql =  "SELECT * from {$this->table_items} WHERE ({$this->field_username} = '{$data['{$this->field_username}']}')";
		if (intval($current_id) != 0) $sql.= " AND (id != '{$current_id}')";
		$gekko_db->query($sql);
		$resultx  = $gekko_db->get_result_as_array();
		return $resultx;
	}
	//______________________________________________________________________________________________________________//
	public function validateNewUserRegistration()
	{// custom function, can be inherited and changed
		global $gekko_config, $gekko_db;
		
		include_inc('securimage/securimage.php');
		
		$securimage = new securimage();
		$securimage->session_name = GEKKO_SESSION_NAME;
		
		$errorstr = '';
		$enable_captcha_user_registration = $gekko_config->get($this->app_name,'chk_enable_captcha_user_registration');
		if ($enable_captcha_user_registration && $securimage->check($_POST['verification_code']) == false) {$errorstr.= '* Invalid verification code'.BR();}
		if (empty($_POST['username'])) {$errorstr.= '* Please fill in the username'.BR();}
		if (empty($_POST['email_address'])) {$errorstr.= '* Please fill in the email address'.BR();} // only return for these 2 checks only
		// 1. Check if username is valid
		if (!$this->validateUserNameString($_POST['username'])) $errorstr.='* Username contains an invalid character. Please use alphanumeric and underscore characters only.'.BR();
		// 2. Check if email address is valid
		if (!$this->validateEmailAddressString($_POST['email_address'])) $errorstr.='* Your e-mail address is invalid'.BR();			
		// 3. Check if email is registered
		$the_email  = sanitizeString($_POST['email_address']);
		$the_username = sanitizeString($_POST['username']);
		$sql =  "SELECT {$this->field_id} FROM {$this->table_items} WHERE email_address = {$the_email} AND (email_address <> '') ";
		$gekko_db->query($sql);
		$results  = $gekko_db->get_result_as_array();
		$thecount = count($results);
		if ($thecount > 0) $errorstr.='* The email address has already been registered';
		// 3. Check if username is taken
		$sql =  "SELECT {$this->field_id} FROM {$this->table_items} WHERE username = {$the_username}  AND (username <> '')";
		$gekko_db->query($sql);
		$results  = $gekko_db->get_result_as_array();
		$thecount = count($results);
		if ($thecount > 0) $errorstr.='* The username has already been taken';
		// 4. Check if password = password_verify
		if ($_POST['password'] != $_POST['password_verify']) $errorstr.='* Your password does not match its verification'.BR();		

		return $errorstr;
	}
	//_________________________________________________________________________// 		
	//_________________________________________________________________________// 		
	public function newUserRegistration()
	{
		global $gekko_config, $gekko_db;
		
		$enable_user_registration = $gekko_config->get($this->app_name,'chk_enable_registration');
		$enable_captcha_user_registration = $gekko_config->get($this->app_name,'chk_enable_captcha_user_registration');
		$default_group_id = $gekko_config->get($this->app_name,'int_default_newuser_group_id');
		if (!$default_group_id)
		{
			$this->page_title = 'User Registration - Webmaster Error';
			echo H1('Error').P('Default group for new user registration has not been set in the Administration area.');
			return false;
		}		
 		if ($enable_user_registration != 1)
		{
			$this->page_title = 'User Registration - Disabled';
			$this->loadTemplateFile('registration_disabled',get_defined_vars());
			return false;	
		}
		if ($_POST['submit'])
		{
			$current_time = date('Y/m/j h:i:s A');
			
			foreach (array_keys($_POST) as $input) $_POST[$input] = trim($_POST[$input]);
			$allowable_fields_for_registration = createDataArray ('username','password','password_verify','firstname','lastname','email_address');
			$datavalues = getVarFromPOST($allowable_fields_for_registration);
			$datavalues[$this->field_username] = $_POST['username'];
			$datavalues[$this->field_password] = $_POST['password'];			
			$registration_error_string = $this->validateNewUserRegistration();
			if (!empty($registration_error_string))
			{
				$this->page_title = 'User Registration';
				$introtext =  $gekko_config->get($this->app_name,'txt_registration_intro');
				$this->loadTemplateFile('registration',get_defined_vars());
				return false;
			} else
			{	// Register User
 			//	$datavalues['category_id'] = $default_group_id;
				$plaintextpasswd = $datavalues['password'];
				$datavalues[$this->field_password] = sha1($datavalues[$this->field_username].$datavalues[$this->field_password]);	
				$datavalues['date_created'] =  date ('Y-m-d H:i:s');
				$datavalues['ip'] = $_SERVER['REMOTE_ADDR'];
				$datavalues['source'] = 'normalregistration';
				unset($datavalues['password_verify']);
				
				$this->_internalCreateUser($datavalues,true);
				//
				$last_created_user = $this->getUserInfoByUserName ($datavalues[$this->field_username]);
				$last_created_user_id = $last_created_user['id'];				
				//
				$this->sendEmailRegistrationSuccess($datavalues['email_address'], $datavalues[$this->field_username], $plaintextpasswd);
				$this->page_title = 'User Registration Process Completed';
				$this->loadTemplateFile('registration_success',get_defined_vars());			
				return true;
			}
		}
		else
		{
			$this->page_title = 'User Registration';
			$introtext =  $gekko_config->get($this->app_name,'txt_registration_intro');
			$this->loadTemplateFile('registration',get_defined_vars());
		}
	}	
	//_______________________________________________________________________________________________________________//	
	public function sendEmailRegistrationSuccess($email, $username, $plaintextpasswd)
	{
		global $site_name, $site_infomail, $site_template, $site_url;
				
		$destination = $email;
		$subject = SITE_NAME."New Registration";
		$message = "Thank you for registering with ".SITE_NAME.". Below is your username and password.\n";
		$message.= "User name: {$username}\n";
		$message.= "Password: {$plaintextpasswd}\n";
		$message.= "\nPlease visit ".SITE_URL." to log in\n";
		$message.="\nIf you have any questions, please don't hesitate to contact us at ".MAIL_DEFAULT_EMAIL;
		$header = "From: ".MAIL_DEFAULT_EMAIL;
		if ($_SERVER['SERVER_ADDR'] != '127.0.0.1') mail ($destination, $subject, $message, $header);
		else 
		{
			echo 'DEBUG - The following will be emailed:'.br();
			echo nl2br ($message);
		}
	}	
	
	//____________________________________________________________________//
	
	public function generatePassword($length=6, $strength=0) {
		$vowels = 'aieouy';
		$consonants = 'bdghjmnpqrstvz';
		if ($strength & 1) {
			$consonants .= 'BDGHJLMNPQRSTVWXZ';
		}
		if ($strength & 2) {
			$vowels .= "AEUY";
		}
		if ($strength & 4) {
			$consonants .= '23456789';
		}
		if ($strength & 8) {
			$consonants .= '@#$%';
		}
	
		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		return $password;
	}
	
	//_______________________________________________________________________________________________________________//	
	public function forgotPassword()
	{
		global $gekko_db, $site_infomail, $site_name;
		
		if ($_POST['submit'])
		{
			
			include_inc('securimage/securimage.php');
			$securimage = new securimage();
			$securimage->session_name = GEKKO_SESSION_NAME;
			if ($securimage->check($_POST['verification_code']) == false)
			{
				echo H1('Invalid verification code');
				$error=true;
				return false;
			}
			if (!$error) 
			{
				$error = !validCSRFVerification();
				if ($error) 
				{
					echo H1('Invalid CSRF Verification Token');
					return false;
				}
			}
			
			
			//$the_firstname = sanitizeString($_POST['firstname']);
			//$the_lastname = sanitizeString($_POST['lastname']);
			$the_email_address = sanitizeString($_POST['email_address']);
		//   $sql = "SELECT * FROM {$this->table_items} WHERE LOWER(firstname)=LOWER({$the_firstname}) and LOWER(lastname)=LOWER({$the_lastname}) and LOWER(email_address) =LOWER({$the_email_address})";
			$sql = "SELECT * FROM {$this->table_items} WHERE LOWER(email_address) =LOWER({$the_email_address})";
			$gekko_db->query($sql);
			$resultx  = $gekko_db->get_result_as_array();
			if (count ($resultx) > 0)
			{
				// sql query update usert
				$userid = intval($resultx[0][$this->field_id]);
				$username = $resultx[0][$this->field_username];
				$newpassword = $this->generatePassword();
				$newencryptedpassword = sha1($username.$newpassword);
				$sql = "UPDATE {$this->table_items} SET {$this->field_password} = '$newencryptedpassword' WHERE id=$userid";
				$gekko_db->query($sql);
				// mail the user the new password
				$destination = $_POST['email'];
				$subject = SITE_NAME." forgotten password";
				$message = "Here is your forgotten username and password.\n";
				$message.= "User name: {$username}\n";
				$message.= "Password: {$newpassword}\n";
				$message.= "\nRequested by {$_SERVER['REMOTE_ADDR']}\n";
				$message.="\nIf you have any questions, please don't hesitate to contact us at {$site_infomail}";
				$header = "From: {$site_infomail}";
				if ($_SERVER['SERVER_ADDR']!= '127.0.0.1') mail ($destination, $subject, $message, $header); else echo nl2br($message);
				mail ($destination, $subject, $message, $header);
				echo H1('New password').P('Your new password has been sent to your email address.');
			} else
			{
				echo H1('Cannot verify your information').P('Sorry, we cannot verify your information.');
			}
		} else
		{
			$this->page_title = 'Forgot Your Password?';
			$this->loadTemplateFile('forgotpassword',get_defined_vars());
		}
		return false;
	}
//_______________________________________________________________________________________________________________//			
	public function processLoginRequest()
	{
		
		global $gekko_current_user;
		
		$error = false;
		$force_ssl = $this->getConfig('force_ssl_authentication');
		$login_url = $this->createFriendlyURL("action=login");
		$invalid_retries_max = $this->getConfig('int_number_of_login_retry_before_captcha');
		if ($invalid_retries_max == 0) $invalid_retries_max = 9999;
		if ($force_ssl)
			$login_url = force_HTTPS_url().$login_url;
		if ($_POST['submit'])
		{
			// We didn't check $_POST['password'], it could be anything the user wanted! For example:
  			if ($_SESSION['invalid_password_retry'] > $invalid_retries_max)
			{
				include_inc('securimage/securimage.php');
				$securimage = new securimage();
				$securimage->session_name = GEKKO_SESSION_NAME;
				if ($securimage->check($_POST['verification_code']) == false) {echo H1('Invalid verification code');$error=true;}
			} 
			if (!$error) 
			{
				$error = !validCSRFVerification();
				if ($error) echo H1('Invalid CSRF Verification Token');
			}
			if (!$error) $this->performAuthentication($_POST['username'],$_POST['password'], $_POST['remember']);
			if (!$error)
			{
				if ($this->authenticated() === true)
				{
					 $_SESSION['invalid_password_retry']=0;
					 $url = $this->getLoginRedirectURL();
					 $this->setLoginRedirectURL('');
					 if (empty($url)) $this->redirectToOtherAction('',OPT_REDIRECT_HTTP); else redirectURL($url);
				} else
				{
					if (array_key_exists('invalid_password_retry',$_SESSION)) $_SESSION['invalid_password_retry']+=1; else $_SESSION['invalid_password_retry']=1;
					echo H1('Invalid username/password').P('Please verify that the information you specified is correct. Please ensure that CAPS Lock is turned off');	
				}
			}
		} 

		$this->page_title = 'Login';
		$this->loadTemplateFile('loginpage',get_defined_vars());		
	}
	//______________________________________________________________________________________________________________//
	public function setLoginRedirectURL($url)
	{
		$_SESSION['url_redirect_after_login'] = $url;
	}
	//______________________________________________________________________________________________________________//	
	public function getLoginRedirectURL()
	{
		return $_SESSION['url_redirect_after_login'];
	}
	
	//______________________________________________________________________________________________________________//
	public function validateEditProfile()
	{// custom function, can be inherited and changed
		global $gekko_db;
		
		$errorstr = '';
		$current_user_id = intval($_SESSION['userid']);
		// 2. Check if email address is valid
		if (empty($_POST['email_address'])) {$errorstr.= '* Please fill in the email address'.BR();} // only return for these 2 checks only		
		if (!$this->validateEmailAddressString($_POST['email_address'])) $errorstr.='* Your e-mail address is invalid'.BR();			
		// 3. Check if email is registered
		$the_email  = sanitizeString($_POST['email_address']);
		$the_username = sanitizeString($_POST['username']);
		// prevent overwriting other people's e-mail address
		$sql =  "SELECT {$this->field_id} FROM {$this->table_items} WHERE (email_address = {$the_email}) AND (email_address <> '') AND {$this->field_id} <> {$current_user_id} ";
		$gekko_db->query($sql);
		$results  = $gekko_db->get_result_as_array();
		$thecount = count($results);
		if ($thecount > 0) $errorstr.='* The email address you entered belong to someone else';
		// 4. Check if password = password_verify
		if ($_POST['newpassword'] || $_POST['oldpassword'])
		{
			if (empty($_POST['oldpassword'])) $errorstr.= '* Please fill in the old password'.BR();						
			if (empty($_POST['newpassword'])) $errorstr.= '* Please fill in the new password'.BR();
			if (empty($_POST['newpassword_verify'])) $errorstr.= '* Please verify the new password'.BR();
			if ($_POST['newpassword'] != $_POST['newpassword_verify']) $errorstr.='* Your new password does not match its verification'.BR();
			$user = $this->verifyUserNamePassword ($_SESSION['username'],$_POST['oldpassword']);
			if (!$user) $errorstr.='* You have entered an invalid old password';
		}

		return $errorstr;
	}
	
//_______________________________________________________________________________________________________________//			
	public function editMyProfile()
	{
		
		global $gekko_db,$gekko_current_user;
		
		$userinfo = $gekko_current_user->getCurrentUserInfo();
		if ($_POST['submit'])
		{
			$editprofile_error_string = $this->validateEditProfile();
			if (empty($editprofile_error_string))
			{
				$allowable_fields_for_edit = createDataArray ('password','firstname','lastname','email_address');
				$datavalues = getVarFromPOST($allowable_fields_for_edit);
				if ($_POST['newpassword']) $datavalues['password'] = sha1($userinfo['username'].trim($_POST['newpassword']));	
				$datavalues['date_modified'] =  date ('Y-m-d H:i:s');
				$datavalues['ip'] = $_SERVER['REMOTE_ADDR'];
				$sql_set_cmd = UpdateSQL($datavalues);
				$userid = intval($_SESSION['userid']);
				$sql =  "UPDATE {$this->table_items} SET ".$sql_set_cmd." WHERE {$this->field_id} = '{$userid}';";;
 				$gekko_db->query($sql);
				//$editprofile_error_string = P('Your profile has been updated.');
				 $_SESSION['user_profile_update_status'] = P('Your profile has been updated.');
				 $this->redirectToOtherAction('action=myprofile');break;
			} 
		}
		$this->page_title = 'Edit Profile';
		$this->loadTemplateFile('myprofile',get_defined_vars());		
	}
	
	//_______________________________________________________________________________________________________________//	
	public function getItemByVirtualFilename($input_filename, $category_id=-1)
	{
		global $gekko_db;
		
 		if (!empty($input_filename))
		{
		
			$str = "";
			$filename = sanitizeString($input_filename);
			if ($category_id >= 0) $str = " AND category_id = {$category_id}";
			$fields = $this->getNonAmbiguousFieldNames($this->table_items);
			
			$sql = "SELECT {$fields} FROM {$this->table_items} WHERE {$this->field_username} = {$filename}{$str}";
			$gekko_db->query($sql);
			$items = $gekko_db->get_result_as_array();
			return $items;
		} else return false;
	}

	//_______________________________________________________________________________________________________________//	
	public function getCategoryByVirtualFilename($input_filename, $parent_id=-1)
	{
		global $gekko_db;
 
 		if (!empty($input_filename))
		{
			$filename = sanitizeString($input_filename);
			$str = "";
			if ($parent_id >= 0) $str = " AND parent_id = {$parent_id}";
			$sql = "SELECT * FROM {$this->table_categories} WHERE {$this->field_groupname} = {$filename}{$str}";
			$gekko_db->query($sql);
			$categories = $gekko_db->get_result_as_array();
			return $categories;
		} else return false;
	}
	//_______________________________________________________________________________________________________________//	
	
	public function hasAdministrationPermission()
	{
		global $gekko_db;
		
		$userid = intval($_SESSION['userid']);
		$admingroupname = sanitizeString(DEFAULT_ADMIN_GROUP);
		$sql = "SELECT {$this->table_categories}.{$this->field_category_id} FROM {$this->table_categories} INNER JOIN {$this->table_categories_items} ON {$this->table_categories}.{$this->field_category_id} = {$this->table_categories_items}.{$this->field_category_id} WHERE {$this->table_categories_items}.{$this->field_id} = {$userid} AND {$this->table_categories}.{$this->field_groupname} = {$admingroupname}";
		$result  = $gekko_db->get_query_singleresult($sql);
		return ($result != null);		
	}
	
	//_______________________________________________________________________________________________________________//		
	public function hasPermission($permission_str)
	{
		global $gekko_db;
		
		$userid = intval($_SESSION['userid']);
		$admingroupname = sanitizeString(DEFAULT_ADMIN_GROUP);
		$extra_criteria = '';
		$extra_bracket = '';
		if (!empty($permission_str))
		{
			if (is_string($permission_str))	$permission_array = unserialize($permission_str); else $permission_array = $permission_str;
			if (!is_array($permission_array)) return false;
			$permission_list = implode(', ', $permission_array); 
			$extra_bracket = '(';
			$extra_criteria = " OR {$this->table_categories}.{$this->field_category_id} in ({$permission_list}))";
		}

		$sql = "SELECT {$this->table_categories}.{$this->field_category_id} FROM {$this->table_categories} INNER JOIN {$this->table_categories_items} ON {$this->table_categories}.{$this->field_category_id} = {$this->table_categories_items}.{$this->field_category_id} WHERE {$this->table_categories_items}.{$this->field_id} = {$userid} AND {$extra_bracket} {$this->table_categories}.{$this->field_groupname} = {$admingroupname} {$extra_criteria}";
		$result  = $gekko_db->get_query_singleresult($sql);
		return ($result != null);		
	}
	
	//_______________________________________________________________________________________________________________//	
	public function hasReadPermission($permission_str)
	{
		$permission = unserialize($permission_str);		
		if ($permission_str == '' || $permission == 'everyone')
			return true;
		else
			return $this->hasPermission($permission_str);
  	}
	//_______________________________________________________________________________________________________________//		
	public function hasWritePermission($permission_str)
	{
		return $this->hasPermission($permission_str) || $this->isCurrentUserMemberOfGroupName(DEFAULT_ADMIN_GROUP);
  	}	
	//_______________________________________________________________________________________________________________//	
	public function draw_permission_read_checkboxgroup($permission_str)
	{
		global $gekko_db;
		
		$read_permission_groups  =  $this->getAllCategories("`{$this->field_category_id}`,`{$this->field_groupname}`",0,0,$this->field_category_id,'ASC',"{$this->field_groupname} <> '".DEFAULT_ADMIN_GROUP."'");
		
		$permission = unserialize($permission_str);
		if ($permission_str == '' || $permission == 'everyone') $checkstr = ' checked'; else $checkstr = '';
		if ($permission_str == '') $permission = array(); // Aug 23, 2010 - bugfix warning
  echo LABEL('Everyone','<input type="checkbox" name="permission_read_everyone" id="chk_permission_read_everyone" value="everyone" onclick="javascript:gekko_toggle_readpermission_check_everyone();"'.$checkstr.' />','','',false).BR();

        foreach ($read_permission_groups as $group)
		{ 
		if ((empty($permission) || $permission == 'everyone' || in_array($group['cid'],$permission))) $checkstr = ' checked'; else $checkstr = '';			
		echo LABEL($group['groupname'],'<input type="checkbox" name="permission_read[]" class="validate-one-required" id="chk_permission_read_'.$group['cid'].'" value="'.$group['cid'].'" onclick="javascript:gekko_toggle_readpermission_check(this.id);"'.$checkstr.' />','','',false).BR();
		}
	}
	//_______________________________________________________________________________________________________________//	
	public function getGroupIDArrayForPermission()
	{
		$choices_array = array();
		$groups = $this->getAllCategories();
		foreach ($groups as $group)
			$choices_array[]=  array('value' => $group['cid'], 'label'=>$group[$this->field_groupname]);
		return $choices_array;
	}
	//_______________________________________________________________________________________________________________//		
	public function draw_username_selection_field($fieldname, $value, $label='', $allow_empty=false)
	{
		 $all_users = $this->getAllItems();			
		 if ($label) echo "<label>{$label}";
         echo "<select name=\"{$fieldname}\" id=\"{$fieldname}\">";
		 if ($allow_empty) echo ('<option value="0">N/A</option>');
		 foreach ($all_users as $user)
		 {
          if ($user['id'] == $value) $selected = 'selected'; else $selected = '';
		  $username = $user[$this->field_username];
          echo "<option value=\"{$user['id']}\" {$selected}>{$username}</option>";
        }
         echo '</select>';
        if ($label) echo '</label>';
		 
	}
	//_______________________________________________________________________________________________________________//	
	
	public function draw_permission_write_checkboxgroup($permission_str,$current_user_groups,$enable_current_user_group=true)
	{
		
		$permission = unserialize($permission_str);
		if ($permission == null) $permission = array(); // to prevent PHP warning (June 12, 2010)
		$write_permission_groups  = $this->getAllCategories("`{$this->field_category_id}`,`{$this->field_groupname}`");
        foreach ($write_permission_groups as $group)
		{
			$same_group = false;
			foreach ($current_user_groups as $user_group) if ($group['cid']==$user_group['cid']) {$same_group = true; break;}
			if (($enable_current_user_group && $same_group) || $group['groupname'] == DEFAULT_ADMIN_GROUP || in_array($group['cid'],$permission)) $checkstr = ' checked'; else $checkstr = '';
			if ($group['groupname'] == DEFAULT_ADMIN_GROUP) $checkstr.= ' onclick="javascript:return false" ';
	 
			echo LABEL($group['groupname'],'<input type="checkbox" name="permission_write[]" class="validate-one-required" id="chk_permission_write_'.$group['cid'].'" value="'.$group['cid'].'"'.$checkstr.' />','','',false).BR();
		}
	}	
	//_______________________________________________________________________________________________________________//	
	public function Run($command)
	{
		global $gekko_db;
		
 		if ($this->backend_mode != 1)
		{
			if ($this->authenticated() === true)
			{
				switch ($command['action'])
				{
					case 'myprofile': $this->editMyProfile();break;
					case 'logout': $this->logout();$this->redirectToOtherAction('');break;
					default: $this->displayMainPage();break;
				}
			}
			else 
			{
				switch ($command['action'])
				{
					case 'forgotpassword': $this->forgotPassword();break;
					case 'register':  $this->newUserRegistration(); break;					
					case 'login': $this->processLoginRequest();break;
					default: $this->displayMainPage();break;
				}
			}
		}
		return true;
	}

}
	
?>