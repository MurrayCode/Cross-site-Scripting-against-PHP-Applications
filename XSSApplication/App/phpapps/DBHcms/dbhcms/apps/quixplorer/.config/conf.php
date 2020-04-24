<?php

#############################################################################################
#																							#
#	DBHCMS CONNECTION																		#
#																							#
#############################################################################################

	# security - Important!
	define('DBHCMS', true);

	# Authentication level in the DBHcms. The options are:
	# 	-> trust ......: everyone can access
	# 	-> A-Z,0-9 ....: only users that have the userlevel can access. Example: 'F' All users with level 'F' can access
	#	-> superuser ..: only the DBHcms Superuser can access
	$dbhcms_app_authlevel = 'superuser';

	# Include authentication of the DBHcms
	$bckdir = '';
	$bckcnt = 0;
	while (!is_file($bckdir.'apps.php')) {
		if ($bckcnt > 10) {
			die('<div style="color: #872626; font-weight: bold;">
					FATAL ERROR - Could find root directory of the DBHcms core. Access denied.
			 	</div>');
		} else {
			$bckdir .= '../'; 
			$bckcnt++;
		}
	}
	
	if (is_file($bckdir.'apps.php')) {
		$dbhcms_app_dir = $bckdir;
		include($bckdir.'apps.php');
	} else {
		die('<div style="color: #872626; font-weight: bold;">
				FATAL ERROR - Access denied!
			 </div>');
	}

#############################################################################################

//------------------------------------------------------------------------------
// Configuration Variables
	
	// login to use QuiXplorer: (true/false)
	$GLOBALS["require_login"] = false;
	
	// language: (en, de, es, fr, nl, ru)
	$GLOBALS["language"] = "en";
	
	// the filename of the QuiXplorer script: (you rarely need to change this)
	$GLOBALS["script_name"] = "http://".$GLOBALS['__SERVER']['HTTP_HOST'].$GLOBALS['__SERVER']["PHP_SELF"];
	
	// allow Zip, Tar, TGz -> Only (experimental) Zip-support
	$GLOBALS["zip"] = false;	//function_exists("gzcompress");
	$GLOBALS["tar"] = false;
	$GLOBALS["tgz"] = false;
	
	// QuiXplorer version:
	$GLOBALS["version"] = "2.3";
	
//------------------------------------------------------------------------------
// Global User Variables (used when $require_login==false)
	
	// the home directory for the filemanager: (use '/', not '\' or '\\', no trailing '/')
	$GLOBALS["home_dir"] = $bckdir;
	
	// the url corresponding with the home directory: (no trailing '/')
	$GLOBALS["home_url"] = substr($_SESSION['DBHCMSDATA']['CFG']['absoluteUrl'], 0, (strlen($_SESSION['DBHCMSDATA']['CFG']['absoluteUrl']) - 1));
	
	// show hidden files in QuiXplorer: (hide files starting with '.', as in Linux/UNIX)
	$GLOBALS["show_hidden"] = true;
	
	// filenames not allowed to access: (uses PCRE regex syntax)
	$GLOBALS["no_access"] = "^\.ht";
	
	// user permissions bitfield: (1=modify, 2=password, 4=admin, add the numbers)
	$GLOBALS["permissions"] = 7;
	
//------------------------------------------------------------------------------
/* NOTE:
	Users can be defined by using the Admin-section,
	or in the file ".config/.htusers.php".
	For more information about PCRE Regex Syntax,
	go to http://www.php.net/pcre.pattern.syntax
*/
//------------------------------------------------------------------------------
?>
