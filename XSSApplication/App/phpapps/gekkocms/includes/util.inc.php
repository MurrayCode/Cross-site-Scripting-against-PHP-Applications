<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
	//_______________________________________________________________________________________________________________//	

	function load_external_page($source, $cachetime = 172800)
	{
		$md5filename = SITE_PATH.'/cache/page/page_'.md5($source).'.page';
		if (file_exists($md5filename) && (time()- filemtime($md5filename) < $cachetime))
		{
			$fcontent = file_get_contents($md5filename);
		} else
		{
			$fcontent = file_get_contents($source);
			$md5handle = @fopen($md5filename, 'w') or die("Cannot save to cache");
			fwrite($md5handle, $fcontent);
			fclose($md5handle);
		}
		return $fcontent;
		
	}
	//_______________________________________________________________________________________________________________//	
	function get_class_ancestors ($class)
	{
		$classes[] = get_class($class);
		while($class = get_parent_class($class)) { $classes[] = $class; }
		return $classes;
	}
 //_________________________________________________________________________//   
	function getApplicationRealNameByAlias($alias)
	{
		global $gekko_config;
		
		return  $gekko_config->get_section_by_value($alias,'alias',true);
	}
//_______________________________________________________________________________________________________________//	
	function displayMessage($title,$message,$type=MSG_NOTICE,$heading='h2')
	{
		echo "<{$heading} class=\"system-{$type}\">".SAFE_HTML($title)."</{$heading}>\n<p class=\"system-{$type}\">".SAFE_HTML($message)."</p>";
	}
 //_________________________________________________________________________//   	
	function force_HTTPS_url()
	{
		return (defined('SITE_HTTPS_URL') && SSL_ENABLED && SITE_HTTPS_URL != '') ? SITE_HTTPS_URL : SITE_HTTP_URL;
	}
	//_________________________________________________________________________//	
	function setFormSecretToken()
	{
		$_SESSION['csrf'] = (empty ($_SESSION['csrf'])) ? sha1(SITE_NAME.uniqid(mt_rand(), TRUE)) : $_SESSION['csrf'];
		return $_SESSION['csrf'];
	}
	//_________________________________________________________________________//	
	function getFormSecretToken()
	{
		return ($_SESSION['csrf']);
	}
	 //_________________________________________________________________________//   
	function validCSRFVerification($the_array=NULL)
	{
		if ($the_array == null || !is_array($the_array) || empty($the_array)) $the_array = $_POST;
		return ($_SESSION['csrf'] === $the_array['_csrftoken']);
	}
	 //_________________________________________________________________________//   
	
	function getFormSecretTokenHiddenField()
	{
		return '<input name="_csrftoken" type="hidden" value="'.getFormSecretToken().'" />';
	}
	 //_________________________________________________________________________//   
	
	function displayFormSecretTokenHiddenField()
	{
		echo getFormSecretTokenHiddenField();
	}
	
	 //_________________________________________________________________________//   
	
	function getJavascriptFormSecretTokenHiddenField($varname = '_csrftoken', $echo = true)
	{
		$s = 'var _csrftoken="'.getFormSecretToken().'";';
		if ($echo) echo $s;
		return $s;
	}
	
 //_________________________________________________________________________//   
	function getApplicationAlias($app)
	{
		global $gekko_config;
		
		$alias = $gekko_config->get($app,'alias',true);
		if (!$alias) $alias = $app;
		return $alias;
	}
	
//_________________________________________________________________________//    	
	function redirectURL($url)
	{
		 ob_end_clean();
		 ob_start();
		 header("Location: {$url}");
	}	
 //_________________________________________________________________________//    	
	
	function pageCounterIncreaseImpression($id,$app_name,$data_fields,$table,$fieldname,$field_id,$reset=false)
	{
		
		global $gekko_config, $gekko_db;
		
		if ($gekko_config->get($app_name,'chk_enable_pageview_stats')==1)
		{
			if (array_key_exists($fieldname, $data_fields))
			{
				$id = intval($id);
				if ($id > 0)
				{
					if ($reset)
						$sql =  "UPDATE {$table} SET {$fieldname}=0 WHERE {$field_id} = '{$id}';";
					else
						$sql =  "UPDATE {$table} SET {$fieldname}=({$fieldname} + 1) WHERE {$field_id} = '{$id}';";
					$gekko_db->query($sql);
				}
			}
		}
	}
	
//_______________________________________________________________________________________________________________//	
	function checkPageOutputDatesAndStatus($item,$opt=null)
	{
		global $gekko_current_user;
		// 1. Is item active ?
		if ($item['status'] < 1) {displayMessage('Inactive Page','This page is inactive');return false;}
		// 2. Is item expired?
		if (!( $item['date_expiry'] == NULL_DATE || daysDifferenceFromToday($item['date_expiry']) > 0 )) {displayMessage('Expired Page','This page has expired.');return false;}
		// 3. Is publish date in the future?
		if (daysDifferenceFromToday($item['date_available']) > 0 ) { displayMessage('Published Date is in the Future','This page is not available yet at this time.');return false;}
		// 4. Does current user have permission to read this item?
	//	if (!$gekko_current_user->hasReadPermission($item['permission_read'])) { displayMessage('No Access','This page is not available for your user group.');return false;}
		$can_read = $gekko_current_user->hasReadPermission($item['permission_read']);
		
		if (!$can_read && !$opt['display_items_summary_noread']) { displayMessage('No Access','This item is not available for your user group.');return false;}
		
		// deprecated --- if ($item[$this->getFielditemID()] == 0) $item['title'] = $Application->app_description; 
		return true;
	}
	
//______________________________________________________________________________________//	

	function displayCaptcha($use_wordlist=true)
	{
		include_once('securimage/securimage.php');
		
		$img = new securimage();
//		$img->ttf_file = str_replace('\\','/',__DIR__).'/securimage/DAYROM__.ttf';
	//	$img->ttf_file = 'http://gekkocms/AHGBold.ttf';
		$img->ttf =  SITE_PATH.'/includes/securimage/DAYROM__.ttf';
	//	echo $img->ttf_file;
		$img->image_width = 275;
		$img->image_height = 90;
		//$img->perturbation = 0.9; // 1.0 = high distortion, higher numbers = more distortion
		//$img->image_bg_color = new Securimage_Color("#0099CC");
		$img->text_color =new Securimage_Color(rand(0, 64), rand(64, 128), rand(128, 255));
		//$img->text_transparency_percentage = 65; // 100 = completely transparent
		$img->num_lines = 6;
		//$img->line_color = new Securimage_Color("#0000CC");
		$img->image_type = SI_IMAGE_PNG;
		$img->use_wordlist = $use_wordlist; 
		
		$img->show();

		
	}
//______________________________________________________________________________________//	
	
  function convert_pasted_png_images_from_html_text ($txt,$upload_path,$url_path)
  {
		if (get_magic_quotes_gpc()) $txt = stripslashes($txt);	  
	  
		preg_match_all('/src="data:image\\/png;base64[^>]+>/i',$txt, $pasted_images_array); 
		$i = 1;
		$start_tag = 'src="data:image/png;base64,';
		$end_tag = '" alt="" />';
		$new_start_tag = '<img src="';
		foreach ($pasted_images_array[0] as $img_tag)
		{
			$outputfile = date('Y-m-d')."-{$i}.png";
			while (file_exists($upload_path.$outputfile))
			{
				$outputfile = date('Y-m-d')."-{$i}.png";
				$i++;
			}
			$imageData = str_replace($start_tag,'',$img_tag);
			$imageData = str_replace($end_tag,'',$imageData);
			$ifp = fopen( $upload_path.$outputfile, "wb" );
			if ($ifp)
			{
				fwrite( $ifp, base64_decode( $imageData ) );		
				fclose( $ifp );
				
				$txt = str_replace($start_tag.$imageData.$end_tag, $new_start_tag.$url_path.$outputfile.$end_tag, $txt);
			}
		} 
		return $txt;
  }
//______________________________________________________________________________________//	
	
  function move_static_external_images_from_html_text ($txt,$upload_path,$url_path)
  {
		if (get_magic_quotes_gpc()) $txt = stripslashes($txt);	  
		preg_match_all('/<img[^>]+>/i',$txt, $images_array); 
		$imgs = array();
		foreach( $images_array[0] as $img_tag)
		{
			//echo $img_tag;
			preg_match_all('/(alt|title|src)=("[^"]*")/i',$img_tag, $imgs[$img_tag]);
		}
		foreach (array_keys($imgs) as $x)
		{

			$src = $imgs[$x][2][0];
			$cleanurl = str_replace('"','',$src);
			$cleanurl = str_replace("'",'',$cleanurl);			
			$prs = parse_url($cleanurl);
			if (($prs['host']!='') && strpos(SITE_URL,$prs['host'])===false && ($prs['host']!=''))
			{			
				$basename = basename($cleanurl);
				$outputfile = $basename;
				if (!file_exists($upload_path.$outputfile))
				{
					$filecontent = file_get_contents($cleanurl);
					file_put_contents($upload_path.$outputfile, $filecontent);
				}
				$txt = str_replace($cleanurl,$url_path.$outputfile,$txt);
			}
		}
		return $txt;
  }
//______________________________________________________________________________________//	
  function quote_field_name_for_query($item_field)
  {
	if ($item_field)
	{
		$item_field = "`{$item_field}`";
	} else $item_field = '';
	return $item_field;
  }
  
//______________________________________________________________________________________//	
  function quote_array_of_field_names_for_query($item_fields)
  {
	$total_field_count = count ($item_fields);
	for ($i = 0; $i < $total_field_count; $i++) $item_fields[$i] = quote_field_name_for_query($item_fields[$i]);
 	return $item_fields;
  }
//______________________________________________________________________________________//	
  function convert_into_sef_friendly_title($str)
  {
	/*  $str = htmlentities($str, ENT_QUOTES, 'UTF-8');
	  $str = preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $str);
	  $str = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
	  */
	  $str = strtolower(trim($str));
 //   $str = preg_replace(array('~[^0-9a-z]~i', '~[ -]+~'), ' ', $str);

	  
	  $str = preg_replace('/[^a-z0-9-]/', '_', $str);
	  $str = preg_replace('/_+/', "_", $str);
	  
	  return trim($str,' -');
  }	
//______________________________________________________________________________________//	
	function daysDifference($date_available, $date_expiry)
	{
		$x = strtotime($date_expiry) -  strtotime($date_available);
		$number_of_days = floor($x/(60*60*24));
		return $number_of_days;
	}
//______________________________________________________________________________________//	
	
	function daysDifferenceFromToday($date_expiry)
	{
		return daysDifference(date("Y-m-d H:i:s"), $date_expiry);
	}
	//_________________________________________________________________________//

	function verify_email_dns($email){
	
		// This will split the email into its front
		// and back (the domain) portions
 		
 		if ($email== '') return false;
 		
/*		list($name, $domain) = split('@',$email); // deprected as of PHP 5.3 and PHP 6 */
		$x = explode('@',$email);
		$name = $x[0];
		$domain = $x[1];

		if(empty ($domain) || !checkdnsrr($domain,'MX')){
	
			// No MX record found
			return false;
	
		} else {
	
			// MX record found, return email
			return $email;
	
		}
	}
	//_________________________________________________________________________//
	function getVarFromPOST($array)
	{
			return array_intersect_key($_POST,$array);
	}
	//_________________________________________________________________________//
	function createDataArray(/*..... */) 
	// Creates array key
	{
		$arg_list = func_get_args();
		$my_array = array();
		foreach ($arg_list as $arg) $my_array[$arg]=NULL;
		return $my_array;
	}
	//_________________________________________________________________________//	
	function removeMultipleSlashes($str)
	{
		if ($str) return preg_replace('#/{2,}#', '/', $str); else return '';
	}
	//_________________________________________________________________________//
	
	function getFolderContent($path,$filetype='both',$forbidden_listing = array())
	{
		$default_forbidden_listing = array('.','..','__MACOSX');
		if (is_array($forbidden_listing) && count ($forbidden_listing) > 0) $default_forbidden_listing = array_merge($default_forbidden_listing,$forbidden_listing);
		$file_array =  array();
		$dir_name = removeMultipleSlashes(SITE_PATH.'/'.$path);
		$dir_handle = @opendir($dir_name);
		while ($file = readdir($dir_handle)) 
		{
		    if (!in_array($file,$default_forbidden_listing) ) 
			{
				$type = is_dir($dir_name.'/'.$file) ? 'dir' : 'file';
				if ($filetype=='both' || ($filetype==$type))
					$file_array[] = array('filename'=>trim($file),'type'=>$type);
			}

		} 
		closedir($dir_handle);
		return $file_array;
	}
	
	//_________________________________________________________________________//
	function createDataArrayFromTable($tablename, $from_cache = true) 
	// Creates array key
	{
		global $gekko_db; 
		$table_columns = $gekko_db->getTableColumns($tablename,$from_cache);
		
		$data_array=array();
		if ($table_columns)
		{
			foreach ($table_columns as $column) $data_array[$column['Field']] = NULL;
			return $data_array;
		} else return false;
	}
	
	//_________________________________________________________________________//
	function createNewInsertData($var_array)
    { // Omits the ID for INSERT INTO SQL statement (autonumbering)
		return array_slice($var_array,1,sizeof($var_array)-1);
    }
	//_________________________________________________________________________//
	function sanitizeString($value)
	{
		
		if (get_magic_quotes_gpc()) $value = stripslashes($value);
		if (!is_array($value)) $value = mysql_real_escape_string($value);
		$value = "'" . $value. "'"; // Dec 2011
		//if (!is_numeric($value)) $value = "'" . str_replace("'","''",$value). "'";
		//if (!is_numeric($value)) $value = "'" . $value. "'"; // Nov 9, 2011 - Prana fix ' bug
		//if (!is_numeric($value)) $value = "'" . addslashes($value) . "'";
		return $value;
	}
	
	//_________________________________________________________________________//
	function cleanInput($value)
	  {
		$value = preg_replace("/[\'\")(;|`,<>]/", "", $value); //FIX - potential bug
		$value = mysql_real_escape_string(trim($value));
		return $value;
	  } 
	//_________________________________________________________________________//
	function InsertSQL($array)
	{

		$keys = array_keys($array);
		foreach ($keys as $key)
		{
			$temp_keys[] = 	 '`'.$key.'`';
			$temp_values[] = sanitizeString($array[$key]);
		}
		$str_keys = implode(', ',$temp_keys);
		$str_values = implode(', ',$temp_values);
		$string = "($str_keys) VALUES ($str_values)";
		return $string;
	}
	//_________________________________________________________________________//
	function UpdateSQL($array)
	{
		$string = '';
		$keys = array_keys($array);
		foreach($keys as $key)
		{
			if ($key != 'id') $string.= $key.'='.sanitizeString($array[$key]).', ';
		}
		$string = substr($string,0,strlen($string)-2); // take out the last comma

		return $string;
	}
//_______________________________________________________________________________________________________________//
	function getAllRecordsFromTable($table_name,$data_fields,$extra_criteria,$fields='*', $extra_criteria = '', $start=0,$end=0,$sortby='', $sortdirection='ASC', $from_cache = false)
	{
		global $gekko_db;
		
		if ($sortby!='')	
			if (strpos($sortby,',')===false)
				if (!array_key_exists($sortby,$data_fields)) $sortby=$primary_key;
		$sql = selectSQL($table_name,$fields,$extra_criteria,$start,$end,$sortby, $sortdirection,true,SQL_ENFORCE_ROW_LIMIT);

 		$search_result = $gekko_db->get_query_result($sql,$from_cache);
		return $search_result;		
	}
	
//_______________________________________________________________________________________________________________//
	function generateSQLSelectParameters($tablename,$fields='*', $extra_criteria = '',$start=0,$end=0,$sortby='', $sortdirection='ASC', $force_limit = false)
	{
		$start = intval($start);
		$end = intval($end);
		$sort_criteria = '';
		$additional_criteria = '';
		$sortby = preg_replace("/[^a-z.,_\d]/i", '', $sortby); // further sanitize to preventy sql injection		
		if (empty($fields)) $fields = '*'; // double check
		if ($start > $end) $start = $end = 0; // autocorrect

		if (!empty($sortby) && !empty($sortdirection))
		{
			//$sortby = quote_field_name_for_query($sortby); if there's more than one sort, this is invalid - dec 8, 2011
			$sortdirection = (strtoupper($sortdirection) == 'DESC') ? 'DESC' : 'ASC';
			$sort_criteria = " ORDER BY {$sortby} {$sortdirection}"; 
		}
 		
		if ($force_limit) $limiter = HARDCODE_MAX_ROWLIMIT; else $limiter = 0;		
 		$item_start = max(0,$start);
		$item_limit = min($end - $start,$limiter);		
		if ($force_limit == true && $item_limit == 0 && $item_start == 0) $item_limit = HARDCODE_MAX_ROWLIMIT;
		if (!empty($extra_criteria)) $additional_criteria= " {$extra_criteria} ";
		
 		return array('fields'=>$fields,'tablename'=>$tablename,'criteria'=>$additional_criteria,'sort_criteria'=>$sort_criteria,'start'=>$item_start,'limit'=>$item_limit);
	}
	
 //_______________________________________________________________________________________________________________//
	function selectSQL($tablename,$fields='*', $extra_criteria = '', $start=0,$end=0,$sortby='', $sortdirection='ASC',$add_where_clause=true,$force_limit=false)
	{
		$limit_criteria = '';

		if ($add_where_clause && !empty($extra_criteria)) $criteria = " WHERE {$extra_criteria} "; else $criteria = $extra_criteria;
		$params = generateSQLSelectParameters($tablename,$fields, $criteria, $start,$end,$sortby, $sortdirection,$force_limit);
		if ($params)
		{
			if ($params['start'] >= 0 && $params['limit'] > 0)
				$limit_criteria = "	LIMIT {$params['start']}, {$params['limit']}";
			$sql = "SELECT {$params['fields']} FROM {$params['tablename']} {$params['criteria']} {$params['sort_criteria']} {$limit_criteria}";
			return $sql;
		} else return false;
	}
	
//______________________________________________________________________________________//	
// from php.net
	function real_strip_tags($i_html, $i_allowedtags = array(), $i_trimtext = FALSE) {
	  if (!is_array($i_allowedtags))
		$i_allowedtags = !empty($i_allowedtags) ? array($i_allowedtags) : array();
	  $tags = implode('|', $i_allowedtags);
	
	  if (empty($tags))
		$tags = '[a-z]+';
	
	  preg_match_all('@</?\s*(' . $tags . ')(\s+[a-z_]+=(\'[^\']+\'|"[^"]+"))*\s*/?>@i', $i_html, $matches);
	
	  $full_tags = $matches[0];
	  $tag_names = $matches[1];
	
	  foreach ($full_tags as $i => $full_tag) {
		if (!in_array($tag_names[$i], $i_allowedtags))
		  if ($i_trimtext)
			unset($full_tags[$i]);
		  else
			$i_html = str_replace($full_tag, '', $i_html);
	  }
	
	  return $i_trimtext ? implode('', $full_tags) : $i_html;
	}  
//_______________________________________________________________________________________________________________//
	function getStartAndEndForItemPagination($pg=1, $perpage=DEFAULT_FRONTEND_ITEMS_PERPAGE,$itemscount) //
	{
		if (!$pg || $pg < 1) $pg = 1;
		if ($perpage == 0) $perpage = 10; // division by zero fix - Prana - Nov 11, 2011
		$startat = ($pg-1) * $perpage;
		$totalpages = ($itemscount + $perpage - 1) / $perpage;
		$endat = $startat + $perpage;
		if ($itemscount < ($startat + $perpage)) $endat = $itemscount;
		return array('start' => $startat,'end' => $endat,'total' => intval($totalpages));
	}
	
	//_________________________________________________________________________//
	function AjaxReply($status,$data)
	{
		header('Content-Type: text/html; charset=UTF-8');
		header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past		
		 $reply ['status'] = $status;
		 $reply ['data'] = $data;
		 echo json_encode($reply);
	}
	//_________________________________________________________________________//
	
	function YUIDataSourceReply($status,$allRecords, $start=0, $end=0, $item_per_page=0, $total_item_count=0, $sort=null, $dir='asc')
	{
		header('Content-Type: text/html; charset=UTF-8');
		header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past		

		if ($dir != 'desc') $dir = 'asc';
		$record_count = count($allRecords);
		if ($end==0) $end = $record_count;
		if ($total_item_count==0) $total_item_count = record_count;

 		// Create return value
		$returnValue = array(
			'status'=> intval($status),
			'recordsReturned'=>intval($record_count),
			'totalRecords'=>intval($total_item_count),
			'start'=> intval($start),
			'end'=> intval($end),
			'itemsperpage'=> intval($end - $start),
			'sortby'=>$sort,
			'sortdirection'=>$dir,
			'data'=>$allRecords
		);

		echo json_encode($returnValue);
	}
	
 	//_________________________________________________________________________//

	function restoreMySQLBackupFromFile($filename)
	{ 
		global $gekko_db;
		
		$templine = '';
		$lines = file($filename);
		foreach ($lines as $line)
		{
			//echo $line;
			if (substr($line, 0, 2) == '--' || empty($line)) continue;
			$templine .= $line;
			// If it has a semicolon at the end, it's the end of the query
			if (substr(trim($line), -1, 1) == ';')
			{
				$gekko_db->query($templine);
				$templine = '';
			}
		}
	}
    //_________________________________________________________________________//
	function createImageThumbnail ( $fileSrc, $thumbDest, $thumb_width = 120, $thumb_height = 120 )
	{
		$ext = strtolower( substr($fileSrc, strrpos($fileSrc, ".")) );
		if (function_exists('finfo_open'))
		{
			$finfo = finfo_open(FILEINFO_MIME_TYPE); 
			$mimetype = finfo_file($finfo, $fileSrc);
			switch ($mimetype)
			{
				case 'image/jpeg':$base_img = ImageCreateFromJPEG($fileSrc);break;
				case 'image/gif':$base_img = ImageCreateFromGIF($fileSrc);break;
				case 'image/png':$base_img = ImageCreateFromPNG($fileSrc);break;
				default:$base_img = null;break;
			}
			finfo_close($finfo);
		} else
		{
			switch ($ext)
			{
				case '.jpeg':
				case '.jpg':$base_img = ImageCreateFromJPEG($fileSrc);break;
				case '.gif':$base_img = ImageCreateFromGIF($fileSrc);break;
				case '.png':$base_img = ImageCreateFromPNG($fileSrc);break;
				default:$base_img = null;break;				
			}
		}
			
		if ($base_img == null) return false;
		// If the image is broken, skip it?
		if ( !$base_img)
			return false;
	
	
		// Get image sizes from the image object we just created
		$img_width = imagesx($base_img);
		$img_height = imagesy($base_img);
	
	
		// Work out which way it needs to be resized
		$img_width_per  = $thumb_width / $img_width;
		$img_height_per = $thumb_height / $img_height;
	
		if ($img_width_per <= $img_height_per || $img_width >  $thumb_width)
		{
			$thumb_width = $thumb_width;
			$thumb_height = intval($img_height * $img_width_per);
		}
		else
		{
			$thumb_width = intval($img_width * $img_height_per);
			$thumb_height = $thumb_height;
		}    
		if ( $img_width <=  $thumb_width &&  $img_height <=  $thumb_height)
		{
			
			$thumb_height = $img_height;
			$thumb_width = $img_width;
		}	
		if ($thumb_width == 0) $thumb_width++;
		if ($thumb_height == 0) $thumb_height++;
		$thumb_img = ImageCreateTrueColor($thumb_width, $thumb_height);
		if( $ext == ".png" )
		{
			$white = imagecolorallocate($thumb_img, 255, 255, 255);
			imagefill($thumb_img,0,0,$white);
		}
		
 		ImageCopyResampled($thumb_img, $base_img, 0, 0, 0, 0, $thumb_width, $thumb_height, $img_width, $img_height);
		

		if( $ext == ".png" )
		{
		//	$background = imagecolorallocate($base_img, 122, 122, 122);
		//	imagecolortransparent($thumb_img, $background);
			//imagefilledrectangle ($thumb_img, 0, 0, $thumb_width, $thumb_height, $whitecolor);
			ImagePNG($thumb_img, $thumbDest);
		}
		else if( ($ext == ".jpeg") || ($ext == ".jpg") )
		{
			ImageJPEG($thumb_img, $thumbDest);
		} else if ( $ext == ".gif" )
		{
			ImageGIF($thumb_img, $thumbDest);
		}
	
		// Clean up our images
		ImageDestroy($base_img);
		ImageDestroy($thumb_img);
	
 	}	
	    //_________________________________________________________________________//

	 function rijndael256_encrypt($key,$text){
	
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv));
	   
	}
    //_________________________________________________________________________//
	
	 function rijndael256_decrypt($key,$text){
	
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($text), MCRYPT_MODE_ECB, $iv));
	   
	}	
	
    //_________________________________________________________________________//
	function createImageThumbnailWithWatermark ( $fileSrc, $thumbDest,$watermarkSrc, $thumb_width = 120, $thumb_height = 120 )
	{
		$ext = strtolower( substr($fileSrc, strrpos($fileSrc, ".")) );
		if( $ext == ".png" )
		{
			$base_img = ImageCreateFromPNG($fileSrc);
		}
		else if( ($ext == ".jpeg") || ($ext == ".jpg") )
		{
			$base_img = ImageCreateFromJPEG($fileSrc);
		}
		else if( ($ext == ".gif") )
		{
			$base_img = ImageCreateFromGIF($fileSrc);
		}
	
		// If the image is broken, skip it?
		if ( !$base_img)
			return false;
	
		$watermark = ImageCreateFromPNG($watermarkSrc); 
		$watermark_width = imagesx($watermark);
		$watermark_height = imagesy($watermark);
		$image = imagecreatetruecolor($watermark_width, $watermark_height);
		 	
		// Get image sizes from the image object we just created
		$img_width = imagesx($base_img);
		$img_height = imagesy($base_img);
	
	
		// Work out which way it needs to be resized
		$img_width_per  = $thumb_width / $img_width;
		$img_height_per = $thumb_height / $img_height;
	
		if ($img_width_per <= $img_height_per || $img_width >  $thumb_width)
		{
			$thumb_width = $thumb_width;
			$thumb_height = intval($img_height * $img_width_per);
		}
		else
		{
			$thumb_width = intval($img_width * $img_height_per);
			$thumb_height = $thumb_height;
		}    
		if ( $img_width <=  $thumb_width &&  $img_height <=  $thumb_height)
		{
			
			$thumb_height = $img_height;
			$thumb_width = $img_width;
		}	
		$thumb_img = ImageCreateTrueColor($thumb_width, $thumb_height);
		if( $ext == ".png" )
		{
			$white = imagecolorallocate($thumb_img, 255, 255, 255);
			imagefill($thumb_img,0,0,$white);
		}
		
 		ImageCopyResampled($thumb_img, $base_img, 0, 0, 0, 0, $thumb_width, $thumb_height, $img_width, $img_height);
		
		$dest_x = ($thumb_width - $watermark_width)/2; 
		$dest_y = ($thumb_height - $watermark_height)/2; 
		imagecopy($thumb_img, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);  

		if( $ext == ".png" )
		{
		//	$background = imagecolorallocate($base_img, 122, 122, 122);
		//	imagecolortransparent($thumb_img, $background);
			//imagefilledrectangle ($thumb_img, 0, 0, $thumb_width, $thumb_height, $whitecolor);
			ImagePNG($thumb_img, $thumbDest);
		}
		else if( ($ext == ".jpeg") || ($ext == ".jpg") )
		{
			ImageJPEG($thumb_img, $thumbDest);
		}
	
		// Clean up our images
		ImageDestroy($base_img);
		ImageDestroy($thumb_img);
	
 	}	

/***********************************************************/
if(!function_exists('get_called_class')) {
function get_called_class($bt = false,$l = 1) {
    if (!$bt) $bt = debug_backtrace();
    if (!isset($bt[$l])) throw new Exception("Cannot find called class -> stack level too deep.");
    if (!isset($bt[$l]['type'])) {
        throw new Exception ('type not set');
    }
    else switch ($bt[$l]['type']) {
        case '::':
            $lines = file($bt[$l]['file']);
            $i = 0;
            $callerLine = '';
            do {
                $i++;
                $callerLine = $lines[$bt[$l]['line']-$i] . $callerLine;
            } while (stripos($callerLine,$bt[$l]['function']) === false);
            preg_match('/([a-zA-Z0-9\_]+)::'.$bt[$l]['function'].'/',
                        $callerLine,
                        $matches);
            if (!isset($matches[1])) {
                // must be an edge case.
                throw new Exception ("Could not find caller class: originating method call is obscured.");
            }
            switch ($matches[1]) {
                case 'self':
                case 'parent':
                    return get_called_class($bt,$l+1);
                default:
                    return $matches[1];
            }
            // won't get here.
        case '->': switch ($bt[$l]['function']) {
                case '__get':
                    // edge case -> get class of calling object
                    if (!is_object($bt[$l]['object'])) throw new Exception ("Edge case fail. __get called on non object.");
                    return get_class($bt[$l]['object']);
                default: return $bt[$l]['class'];
            }

        default: throw new Exception ("Unknown backtrace method type");
    }
}
} 


?>