<?php
error_reporting(E_ERROR);
include('../config.inc.php');
include('../connector.inc.php');
include_inc('definitions.inc.php');
include_inc('db.inc.php');
include_inc('dbconfig.inc.php');
include_inc('util.inc.php');
include_inc('app_interface.php');
include_inc('app_basic.class.php');
include_inc('init.inc.php');
$user_class_name = DEFAULT_USER_CLASS;
session_name(GEKKO_ADMIN_SESSION_NAME);
session_start();
include_app_class($user_class_name);
include_app_class('html');
$current_user = new $user_class_name(true);

$groups_allowed_for_backend_access = $gekko_config->get('bbgkmediamanager','groups_allowed_for_backend_access');
if (!$current_user->hasPermission($groups_allowed_for_backend_access)) die('Access not allowed');

header('Content-type: text/javascript; charset=UTF-8'); 

function generateSitemap()
{
	global $gekko_db, $gekko_config;
	
	$default_app_array = array('html','blog');
	$enabled_apps_array = $gekko_config->get('bbgkmediamanager','enabled_apps');
	if (count($enabled_apps_array)==0 || !is_array($enabled_apps_array)) $enabled_apps_array = $default_app_array;
	$forbidden_listing = array('.','..','.svn','.cvs');
	
	$item_path = SITE_PATH.'/apps/';
	$item_array =  array();
	$dir_handle = @opendir($item_path);
	if ($dir_handle) while ($file = readdir($dir_handle)) 
	{
		if (!in_array($file,$forbidden_listing) && (strpos($file,'uninstalled_')===false) && file_exists($item_path.$file)) $item_array[] = $file;
	}
	if ($dir_handle) closedir($dir_handle);
	if ($item_array)
	{
		foreach ($item_array as $appname)
		{
			if (in_array($appname,$enabled_apps_array) && $appname !=DEFAULT_USER_CLASS)
			{
				$alias = getApplicationAlias($appname);

				include_app_class($appname);
				$temp_app = new $appname;
				if (method_exists($temp_app,'getAllItems'))
				{					
					$all_items = $temp_app->getAllItems('*','status > 0',0,0,'id', 'ASC');
					foreach ($all_items as $the_item) 
					{
						$link = $temp_app->createFriendlyURL("action=viewitem&id={$the_item['id']}");
						$title = "Item {$the_item['id']}";
						if ($the_item['title']) $title = "{$appname} - item - ".$the_item['title'];
						$urls[] = array('title'=>$title,'link'=>$link);
					}
				}
				if (method_exists($temp_app,'getAllCategories'))
				{					
					$all_cats = $temp_app->getAllCategories('*','status = 1',0,0,'id', 'ASC');
					if (method_exists($temp_app,'getCategoryFieldName'))						
						$field_c = $temp_app->getCategoryFieldName(); else $field_c = 'cid';
						foreach ($all_cats as $the_cat) 
						{
							$cid = $the_cat["cid"];
							$title = "Category {$the_cat['cid']}";
							if ($the_cat['title']) $title = "{$appname} - category - ".$the_cat['title'];
							
							$link = $temp_app->createFriendlyURL("action=viewcategory&cid={$cid}");
							$urls[] = array('title'=>$title,'link'=>$link);
							
						}
					}
				
			}
		}
	}
	return $urls;
	//echo $output;
/*	$filename = SITE_PATH.'/cache/sitemap.xml';
	if(is_writable(SITE_PATH.'/cache/')) 
	{
		file_put_contents($filename,$output);
		echo "Sitemap: ".SITE_URL.'/cache/sitemap.xml';
	} */
}


function read_folder_directory($dir = ".")
    {
        $listDir = array();
        if($handler = opendir($dir)) {
            while (($sub = readdir($handler)) !== FALSE) {
				$path_parts = pathinfo($sub);
                if ($sub != "." && $sub != ".." && $sub != "Thumb.db" && $sub != "Thumbs.db") {
                    if(is_file($dir."/".$sub)) {
						$listDir[] = str_replace('..',SITE_HTTPBASE,$dir."/".$sub);
                    }elseif(is_dir($dir."/".$sub))
					{
                        $last_op = read_folder_directory($dir."/".$sub);
						if (is_array($last_op)) $listDir = array_merge($listDir,$last_op);
                    }
                }
            }
            closedir($handler);
        }
        return $listDir;
    } 
	$arr_links = generateSitemap();
	
	$arr_files =read_folder_directory('../downloads');
	echo 'var tinyMCELinkList = new Array(';
	$total = count($arr_files);
	for ($i=0; $i < $total; $i++)
	{
		$file = $arr_files[$i];
		$path_parts = pathinfo($file);
		
		echo '["'.ucwords($path_parts['filename']).'", "'.$file.'"]';
		if ($i != $total -1 ) echo ',';
	}
	
	$total = count($arr_links);
	if ($total > 0) echo ',';
	for ($i=0; $i < $total; $i++)
	{
		$file = $arr_links[$i];
		$path_parts = pathinfo($file);
		
		echo '["'.($file['title']).'", "'.$file['link'].'"]'."\n";
		if ($i != $total -1 ) echo ',';
	}
	
	echo ');';
?>
