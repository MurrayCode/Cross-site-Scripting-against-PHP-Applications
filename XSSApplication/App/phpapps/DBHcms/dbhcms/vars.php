<?php

#############################################################################################
#                                                                                           #
#  DBHCMS - Web Content Management System                                                   #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  COPYRIGHT NOTICE                                                                         #
#  =============================                                                            #
#                                                                                           #
#  Copyright (C) 2005-2007 Kai-Sven Bunk (kaisven@drbenhur.com)                             #
#  All rights reserved                                                                      #
#                                                                                           #
#  This file is part of DBHcms.                                                             #
#                                                                                           #
#  DBHcms is free software; you can redistribute it and/or modify it under the terms of     #
#  the GNU General Public License as published by the Free Software Foundation; either      #
#  version 2 of the License, or (at your option) any later version.                         #
#                                                                                           #
#  The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html      #
#  A copy is found in the textfile GPL.TXT                                                  #
#                                                                                           #
#  DBHcms is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;      #
#  without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR         #
#  PURPOSE. See the GNU General Public License for more details.                            #
#                                                                                           #
#  This copyright notice MUST APPEAR in ALL copies of the script!                           #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  FILENAME                                                                                 #
#  =============================                                                            #
#  vars.php                                                                                 #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Declares and sets initial values of important variables and constants                    #
#  Format for constants: DBHCMS_C_XXXX                                                      #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  CHANGES                                                                                  #
#  =============================                                                            #
#                                                                                           #
#  28.10.2005:                                                                              #
#  -----------                                                                              #
#  File created                                                                             #
#                                                                                           #
#############################################################################################
# $Id: vars.php 59 2007-02-01 13:05:33Z kaisven $                                           #
#############################################################################################

#############################################################################################
#  SECURITY                                                                                 #
#############################################################################################

	if ((realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))||(!defined('DBHCMS'))) {
		die('	<div style="color: #872626; font-weight: bold;">
						DBHCMS FATAL ERROR - Access denied!
					</div>');
	}
	
#############################################################################################
#  INIT CONSTANTS                                                                           #
#############################################################################################

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# TABLE NAMES                                                              #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	
	define('DBHCMS_C_TBL_DOMAINS'			, 	'cms_domains'		);
	define('DBHCMS_C_TBL_DICTIONARY'		, 	'cms_dictionary'	);
	define('DBHCMS_C_TBL_CONFIG'			,	'cms_config'		);
	define('DBHCMS_C_TBL_PAGEPARAMS'		,	'cms_pageprms'		);
	define('DBHCMS_C_TBL_PAGES'				,	'cms_pages'			);
	define('DBHCMS_C_TBL_PAGEVALS'			,	'cms_pagevals'		);
	define('DBHCMS_C_TBL_USERS'				,	'cms_users'			);
	define('DBHCMS_C_TBL_VISITS'			,	'cms_visits'		);
	define('DBHCMS_C_TBL_MENUS'				,	'cms_menus'			);
	define('DBHCMS_C_TBL_ACCESSLOG'			,	'cms_accesslog'		);
	define('DBHCMS_C_TBL_SESSIONS'			,	'cms_sessions'		);
	define('DBHCMS_C_TBL_ERRORLOG'			,	'cms_errorlog'		);
	define('DBHCMS_C_TBL_CACHE'				,	'cms_cache'			);

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# PAGE PARAMETERS                                                          #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	
	define('DBHCMS_C_PAGEVAL_TEMPLATES'		,	'templates'		);
	define('DBHCMS_C_PAGEVAL_STYLESHEETS'	,	'stylesheets'	);
	define('DBHCMS_C_PAGEVAL_JAVASCRIPTS'	,	'javascripts'	);
	define('DBHCMS_C_PAGEVAL_PHPMODULES'	,	'modules'		);
	define('DBHCMS_C_PAGEVAL_URL'			,	'urlPrefix'		);
	define('DBHCMS_C_PAGEVAL_NAME'			,	'name'			);
	define('DBHCMS_C_PAGEVAL_CONTENT'		,	'content'		);

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# DATA TYPES                                                               #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	
	### BASIC DATA TYPES ###
	define('DBHCMS_C_DT_STRING'			,	'DT_STRING');
	define('DBHCMS_C_DT_STRARRAY'		,	'DT_STRARRAY');
	define('DBHCMS_C_DT_INTEGER'		,	'DT_INTEGER');
	define('DBHCMS_C_DT_INTARRAY'		,	'DT_INTARRAY');
	define('DBHCMS_C_DT_DATE'			,	'DT_DATE');
	define('DBHCMS_C_DT_TIME'			,	'DT_TIME');
	define('DBHCMS_C_DT_DATETIME'		,	'DT_DATETIME');
	define('DBHCMS_C_DT_TEXT'			,	'DT_TEXT');
	define('DBHCMS_C_DT_HTML'			,	'DT_HTML');
	define('DBHCMS_C_DT_BOOLEAN'		,	'DT_BOOLEAN');
	
	### SRTUCT DATA TYPES ###
	define('DBHCMS_C_DT_MODULE'			,	'DT_MODULE');
	define('DBHCMS_C_DT_MODARRAY'		,	'DT_MODARRAY');
	define('DBHCMS_C_DT_TEMPLATE'		,	'DT_TEMPLATE');
	define('DBHCMS_C_DT_TPLARRAY'		,	'DT_TPLARRAY');
	define('DBHCMS_C_DT_JAVASCRIPT'		,	'DT_JAVASCRIPT');
	define('DBHCMS_C_DT_JSARRAY'		,	'DT_JSARRAY');
	define('DBHCMS_C_DT_STYLESHEET'		,	'DT_STYLESHEET');
	define('DBHCMS_C_DT_CSSARRAY'		,	'DT_CSSARRAY');
	define('DBHCMS_C_DT_EXTENSION'		,	'DT_EXTENSION');
	define('DBHCMS_C_DT_EXTARRAY'		,	'DT_EXTARRAY');
	
	### FILE DATA TYPES ###
	define('DBHCMS_C_DT_DIRECTORY'		,	'DT_DIRECTORY');
	define('DBHCMS_C_DT_DIRARRAY'		,	'DT_DIRARRAY');
	define('DBHCMS_C_DT_FILE'			,	'DT_FILE');
	define('DBHCMS_C_DT_FILEARRAY'		,	'DT_FILEARRAY');
	define('DBHCMS_C_DT_IMAGE'			,	'DT_IMAGE');
	define('DBHCMS_C_DT_IMGARRAY'		,	'DT_IMGARRAY');
	
	### ADVANCED DATA TYPES ###
	define('DBHCMS_C_DT_CONTENT'		,	'DT_CONTENT');
	define('DBHCMS_C_DT_PASSWORD'		,	'DT_PASSWORD');
	
	### OBJECT DATA TYPES ###
	define('DBHCMS_C_DT_USER'			,	'DT_USER');
	define('DBHCMS_C_DT_USERARRAY'		,	'DT_USERARRAY');
	define('DBHCMS_C_DT_PAGE'			,	'DT_PAGE');
	define('DBHCMS_C_DT_PAGEARRAY'		,	'DT_PAGEARRAY');
	define('DBHCMS_C_DT_DOMAIN'			,	'DT_DOMAIN');
	define('DBHCMS_C_DT_DOMAINARRAY'	,	'DT_DOMAINARRAY');
	
	### PROPERTY DATA TYPES ###
	define('DBHCMS_C_DT_SEX'			,	'DT_SEX');
	define('DBHCMS_C_DT_LANGUAGE'		,	'DT_LANGUAGE');
	define('DBHCMS_C_DT_LANGARRAY'		,	'DT_LANGARRAY');
	define('DBHCMS_C_DT_USERLEVEL'		,	'DT_USERLEVEL');
	define('DBHCMS_C_DT_ULARRAY'		,	'DT_ULARRAY');
	define('DBHCMS_C_DT_DATATYPE'		,	'DT_DATATYPE');
	define('DBHCMS_C_DT_MENUTYPE'		,	'DT_MENUTYPE');
	define('DBHCMS_C_DT_HIERARCHY'		,	'DT_HIERARCHY');

	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	# OTHER TYPES                                                              #
	#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
	
	### HIERARCHY TYPES ###
	define('DBHCMS_C_HT_ROOT'			,	'HT_ROOT');
	define('DBHCMS_C_HT_HEREDITARY'		,	'HT_HEREDITARY');
	define('DBHCMS_C_HT_SINGLE'			,	'HT_SINGLE');
	
	### MENU TYPES ###
	define('DBHCMS_C_MT_TREE'			,	'MT_TREE');
	define('DBHCMS_C_MT_ACTIVETREE'		,	'MT_ACTIVETREE');
	define('DBHCMS_C_MT_LOCATION'		,	'MT_LOCATION');
	
	### SEX TYPES ###
	define('DBHCMS_C_ST_MALE'			,	'ST_MALE');
	define('DBHCMS_C_ST_FEMALE'			,	'ST_FEMALE');
	define('DBHCMS_C_ST_NONE'			,	'ST_NONE');
	
	### SUBMIT METHODS ###
	define('DBHCMS_C_SM_POST'			,	'post');
	define('DBHCMS_C_SM_GET'			,	'get');
	
	### CACHE TYPES ###
	define('DBHCMS_C_CT_ON'				,	'CT_ON');
	define('DBHCMS_C_CT_OFF'			,	'CT_OFF');
	define('DBHCMS_C_CT_REFRESH'		,	'CT_REFRESH');
	define('DBHCMS_C_CT_EMPTYPAGE'		,	'CT_EMPTYPAGE');
	define('DBHCMS_C_CT_EMPTYALL'		,	'CT_EMPTYALL');

#############################################################################################
#  INIT VARIABLES                                                                           #
#############################################################################################

	$GLOBALS['DBHCMS'] = array(
						'PID' => 0,
						'DID' => 0,
						
						'TYPES' => array (
						
											'DT' => array	(
																DBHCMS_C_DT_STRING,
																DBHCMS_C_DT_STRARRAY,
																DBHCMS_C_DT_INTEGER,
																DBHCMS_C_DT_INTARRAY,
																DBHCMS_C_DT_DATE,
																DBHCMS_C_DT_TIME,
																DBHCMS_C_DT_DATETIME,
																DBHCMS_C_DT_TEXT,
																DBHCMS_C_DT_HTML,
																DBHCMS_C_DT_BOOLEAN,
																DBHCMS_C_DT_MODULE,
																DBHCMS_C_DT_MODARRAY,
																DBHCMS_C_DT_TEMPLATE,
																DBHCMS_C_DT_TPLARRAY,
																DBHCMS_C_DT_JAVASCRIPT,
																DBHCMS_C_DT_JSARRAY,
																DBHCMS_C_DT_STYLESHEET,
																DBHCMS_C_DT_CSSARRAY,
																DBHCMS_C_DT_EXTENSION,
																DBHCMS_C_DT_EXTARRAY,
																DBHCMS_C_DT_DIRECTORY,
																DBHCMS_C_DT_DIRARRAY,
																DBHCMS_C_DT_FILE,
																DBHCMS_C_DT_FILEARRAY,
																DBHCMS_C_DT_IMAGE,
																DBHCMS_C_DT_IMGARRAY,
																DBHCMS_C_DT_CONTENT,
																DBHCMS_C_DT_PASSWORD,
																DBHCMS_C_DT_USER,
																DBHCMS_C_DT_USERARRAY,
																DBHCMS_C_DT_PAGE,
																DBHCMS_C_DT_PAGEARRAY,
																DBHCMS_C_DT_DOMAIN,
																DBHCMS_C_DT_DOMAINARRAY,
																DBHCMS_C_DT_SEX,
																DBHCMS_C_DT_LANGUAGE,
																DBHCMS_C_DT_LANGARRAY,
																DBHCMS_C_DT_USERLEVEL,
																DBHCMS_C_DT_ULARRAY,
																DBHCMS_C_DT_DATATYPE,
																DBHCMS_C_DT_MENUTYPE,
																DBHCMS_C_DT_HIERARCHY
															),
											
											'HT' => array	(
																DBHCMS_C_HT_ROOT,
																DBHCMS_C_HT_HEREDITARY,
																DBHCMS_C_HT_SINGLE
															),
											
											'MT' => array	(
																DBHCMS_C_MT_TREE,
																DBHCMS_C_MT_ACTIVETREE,
																DBHCMS_C_MT_LOCATION
															),
											
											'ST' => array	(
																DBHCMS_C_ST_MALE,
																DBHCMS_C_ST_FEMALE,
																DBHCMS_C_ST_NONE
															),
											
											'FL' => array 	(
																'A', 
																'B', 
																'C', 
																'D', 
																'E', 
																'F', 
																'G', 
																'H', 
																'I', 
																'J', 
																'K', 
																'L', 
																'M', 
																'N', 
																'O', 
																'P', 
																'Q', 
																'R', 
																'S', 
																'T', 
																'U', 
																'V', 
																'W', 
																'X', 
																'Y', 
																'Z'
															),
											
											'BL' => array 	(
																'0', 
																'1', 
																'2', 
																'3', 
																'4', 
																'5', 
																'6', 
																'7', 
																'8', 
																'9'
															)
											
											),
						
						'CONFIG' => array(
											'DB'	 => array(),
											'CORE'	 => array(),
											'PARAMS' => array('paramDataTypes'=>array()),
											'EXT' 	 => array()
										),
						
						'DOMAIN' => array(),
						
						'PAGES' => array(),
						
						'PTREE' => array('complete'=>array(), 'single'=>array(), 'location'=>array()),
						
						
						'STRUCT' => array(
											'EXT'	=> array(),
											'PHP'	=> array(),
											'TPL'	=> array(),
											'BLK'	=> array(),
											'CSS'	=> array(),
											'JS'	=> array(),
											'STR'	=> array(),
											'MEN'	=> array(),
										),
						
						'DICT' => array('FE'=>array(), 'BE'=>array()),
						
						'LANGS' => array (
							"af" 		=> "af",	//Afrikaans
							"sq" 		=> "sq",	//Albanian
							"eu" 		=> "eu",	//Basque
							"bg" 		=> "bg",	//Bulgarian
							"be" 		=> "be",	//Byelorussian
							"ca" 		=> "ca",	//Catalan
							"zh" 		=> "zh",	//Chinese
							"zh-cn" 	=> "zh",	//Chinese/China
							"zh-tw" 	=> "zh",	//Chinese/Taiwan
							"zh-hk" 	=> "zh",	//Chinese/Hong Kong
							"zh-sg" 	=> "zh",	//Chinese/singapore
							"hr" 		=> "hr",	//Croatian
							"cs" 		=> "cs",	//Czech
							"da" 		=> "da",	//Danish
							"nl" 		=> "nl",	//Dutch
							"nl-be" 	=> "nl",	//Dutch/Belgium
							"en" 		=> "en",	//English
							"en-gb" 	=> "en",	//English/United Kingdom
							"en-us" 	=> "en",	//English/United Satates
							"en-au" 	=> "en",	//English/Australian
							"en-ca" 	=> "en",	//English/Canada
							"en-nz" 	=> "en",	//English/New Zealand
							"en-ie" 	=> "en",	//English/Ireland
							"en-za" 	=> "en",	//English/South Africa
							"en-jm" 	=> "en",	//English/Jamaica
							"en-bz" 	=> "en",	//English/Belize
							"en-tt" 	=> "en",	//English/Trinidad
							"et" 		=> "et",	//Estonian
							"fo" 		=> "fo",	//Faeroese
							"fa" 		=> "fa",	//Farsi
							"fi" 		=> "fi",	//Finnish
							"fr" 		=> "fr",	//French
							"fr-be" 	=> "fr",	//French/Belgium
							"fr-fr" 	=> "fr",	//French/France
							"fr-ch" 	=> "fr",	//French/Switzerland
							"fr-ca" 	=> "fr",	//French/Canada
							"fr-lu" 	=> "fr",	//French/Luxembourg
							"gd" 		=> "gd",	//Gaelic
							"gl"		=> "gl",	//Galician
							"de" 		=> "de",	//German
							"de-at" 	=> "de",	//German/Austria
							"de-de" 	=> "de",	//German/Germany
							"de-ch" 	=> "de",	//German/Switzerland
							"de-lu" 	=> "de",	//German/Luxembourg
							"de-li" 	=> "de",	//German/Liechtenstein
							"el" 		=> "el",	//Greek
							"hi" 		=> "hi",	//Hindi
							"hu"		=> "hu",	//Hungarian
							"is" 		=> "is",	//Icelandic
							"id" 		=> "id",	//Indonesian
							"in" 		=> "id",	//Indonesian
							"ga" 		=> "ga",	//Irish
							"it" 		=> "it",	//Italian
							"it-ch" 	=> "it",	//Italian/ Switzerland
							"ja" 		=> "ja",	//Japanese
							"ko" 		=> "ko",	//Korean
							"lv" 		=> "lv",	//Latvian
							"lt" 		=> "lt",	//Lithuanian
							"mk" 		=> "mk",	//Macedonian
							"ms" 		=> "ms",	//Malaysian
							"mt" 		=> "mt",	//Maltese
							"no" 		=> "no",	//Norwegian
							"pl" 		=> "pl",	//Polish
							"pt"		=> "pt",	//Portuguese
							"pt-br" 	=> "pt",	//Portuguese/Brazil
							"rm" 		=> "rm",	//Rhaeto-Romanic
							"ro" 		=> "ro",	//Romanian
							"ro-mo" 	=> "ro",	//Romanian/Moldavia
							"ru" 		=> "ru",	//Russian
							"ru-mo" 	=> "ru",	//Russian /Moldavia
							"sr" 		=> "sr",	//Serbian
							"sk" 		=> "sk",	//Slovak
							"sl" 		=> "sl",	//Slovenian
							"sb" 		=> "sb",	//Sorbian
							"es" 		=> "es",	//Spanish
							"es-do" 	=> "es",	//Spanish/Dominican Republic
							"es-ar" 	=> "es",	//Spanish/Argentina
							"es-co" 	=> "es",	//Spanish/Colombia
							"es-mx" 	=> "es",	//Spanish/Mexico
							"es-es" 	=> "es",	//Spanish/Spain
							"es-gt" 	=> "es",	//Spanish/Guatemala
							"es-cr" 	=> "es",	//Spanish/Costa Rica
							"es-pa" 	=> "es",	//Spanish/Panama
							"es-ve" 	=> "es",	//Spanish/Venezuela
							"es-pe" 	=> "es",	//Spanish/Peru
							"es-ec" 	=> "es",	//Spanish/Ecuador
							"es-cl" 	=> "es",	//Spanish/Chile
							"es-uy" 	=> "es",	//Spanish/Uruguay
							"es-py" 	=> "es",	//Spanish/Paraguay
							"es-bo" 	=> "es",	//Spanish/Bolivia
							"es-sv" 	=> "es",	//Spanish/El salvador
							"es-hn" 	=> "es",	//Spanish/Honduras
							"es-ni" 	=> "es",	//Spanish/Nicaragua
							"es-pr" 	=> "es",	//Spanish/Puerto Rico
							"sv" 		=> "sv",	//Swedish
							"sv-fi" 	=> "sv",	//Swedish/Findland
							"ts" 		=> "ts",	//Thai
							"tn" 		=> "tn",	//Tswana
							"tr" 		=> "tr",	//Turkish
							"uk" 		=> "uk",	//Ukrainian
							"ur" 		=> "ur",	//Urdu
							"vi" 		=> "vi",	//Vietnamese
							"zu" 		=> "zu"		//Zulu
						),
						
						'RESULTS' => array (),
						
						'TEMP' => array('PARAMS' => array())
						
					);

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>