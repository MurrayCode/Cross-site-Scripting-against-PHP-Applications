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
$current_user = new $user_class_name(true);

$groups_allowed_for_backend_access = $gekko_config->get('bbgkmediamanager','groups_allowed_for_backend_access');
if (!$current_user->hasPermission($groups_allowed_for_backend_access)) die('Access not allowed');

header('Content-type: text/javascript; charset=UTF-8'); 
 
//__________________________________________________________________//
function read_folder_directory($dir = ".", $valid_exts)
    {
        $listDir = array();
        if($handler = opendir($dir)) {
            while (($sub = readdir($handler)) !== FALSE) {
				$path_parts = pathinfo($sub);
                if ($sub != "." && $sub != ".." && $sub != "Thumb.db" && $sub != "Thumbs.db") {
                    if(is_file($dir."/".$sub)) {
                    if (in_array($path_parts['extension'],$valid_exts)) 
					{
						$listDir[] = str_replace('..',SITE_HTTPBASE,$dir."/".$sub);
					}
                    }elseif(is_dir($dir."/".$sub))
					{
                        $last_op = read_folder_directory($dir."/".$sub,$valid_exts);
						if (is_array($last_op)) $listDir = array_merge($listDir,$last_op);
                    }
                }
            }
            closedir($handler);
        }
        return $listDir;
    } 
//__________________________________________________________________//	
	
function getDescription($file)
{
	$arr_path = explode('/',$file);
	$arr_path = array_splice($arr_path,2);
	$count = count ($arr_path);
	for ($i=0;$i<$count;$i++)
	{
		if ($i == $count-1)
		{
			$arr_path[$i] = substr($arr_path[$i],0,strpos($arr_path[$i],'.'));	
		}
		$arr_path[$i] = ucwords($arr_path[$i]);	
		
	}
	return implode(' - ',$arr_path);
}
//__________________________________________________________________//	
	$arr_files =read_folder_directory('../images',array('jpg','png','bmp','gif'));
	echo 'var tinyMCEImageList = new Array(';
	$total = count($arr_files);
	for ($i=0; $i < $total; $i++)
	{
		$file = $arr_files[$i];
		echo '["'.getDescription($file).'", "'.$file.'"]';
		if ($i != $total -1 ) echo ',';
	}
	echo ');';
?>
