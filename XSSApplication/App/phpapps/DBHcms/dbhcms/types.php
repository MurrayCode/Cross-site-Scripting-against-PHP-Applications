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
#  types.php                                                                                #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Standard functions and procedures for conversion of data types.                          #
#                                                                                           #
#  dbhcms_f_dbvalue_to_value:                                                               #
#  -----------------------------                                                            #
#  Turns a database value into a runtime value.                                             #
#                                                                                           #
#  dbhcms_f_value_to_dbvalue:                                                               #
#  -----------------------------                                                            #
#  Turns a runtime value into a database value.                                             #
#                                                                                           #
#  dbhcms_f_value_to_input                                                                  #
#  -----------------------------                                                            #
#  Creates an input html-tag from a runtime value with a given type.                        #
#                                                                                           #
#  dbhcms_f_dbvalue_to_input                                                                #
#  -----------------------------                                                            #
#  Creates an input html-tag from a database value with a given type.                       #
#                                                                                           #
#  dbhcms_f_input_to_value                                                                  #
#  -----------------------------                                                            #
#  Returns a runtime value of an input                                                      #
#                                                                                           #
#  dbhcms_f_input_to_dbvalue                                                                #
#  -----------------------------                                                            #
#  Returns a database value of an input                                                     #
#                                                                                           #
#  dbhcms_f_value_to_output                                                                 #
#  -----------------------------                                                            #
#  Returns a output value of a runtime value                                                #
#                                                                                           #
#  dbhcms_p_add_value                                                                       #
#  -----------------------------                                                            #
#  Adds a value to struct                                                                   #
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
# $Id: types.php 68 2007-05-31 20:28:17Z kaisven $                                          #
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
#  REGISTER FILE                                                                            #
#############################################################################################

	dbhcms_p_register_file(realpath(__FILE__), 'types', 0.1);

#############################################################################################
#                                                                                           #
#  FUNCTION: DBHCMS_F_DBVALUE_TO_VALUE                                                      #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#                                                                                           #
#  Turns a database value into a runtime value.                                             #
#                                                                                           #
#############################################################################################

	function dbhcms_f_dbvalue_to_value ($avalue, $atype) {
		
		$new_value = '';
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# BASIC TYPES                                                              #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### STRING ###
		if ($atype == DBHCMS_C_DT_STRING) {
			$new_value = trim(strval($avalue));
		
		### STRARRAY ###
		} else if ($atype == DBHCMS_C_DT_STRARRAY) {
			$new_value = array();
			$temp = explode(';', strval($avalue));
			foreach ($temp as $val) {
				if (trim($val) != '') {
					array_push($new_value, dbhcms_f_dbvalue_to_value($val, DBHCMS_C_DT_STRING));
				}
			}
		
		### INTEGER ###
		} else if ($atype == DBHCMS_C_DT_INTEGER) {
			$new_value = intval($avalue);
		
		### INTARRAY ###
		} else if ($atype == DBHCMS_C_DT_INTARRAY) {
			$new_value = array();
			$temp = explode(';', strval($avalue));
			foreach ($temp as $val) {
				if (trim($val) != '') {
					array_push($new_value, dbhcms_f_dbvalue_to_value($val, DBHCMS_C_DT_INTEGER));
				}
			}
		
		### DATE ###
		} else if ($atype == DBHCMS_C_DT_DATE) {
			$new_value = strtotime(' '.$avalue.' 00:00:00 ');
		
		### TIME ###
		} else if ($atype == DBHCMS_C_DT_TIME) {
			$new_value = strtotime(' 0000-00-00 '.$avalue.' ');
		
		### DATETIME ###
		} else if ($atype == DBHCMS_C_DT_DATETIME) {
			$new_value = strtotime(' '.$avalue.' ');
		
		### TEXT ###
		} else if ($atype == DBHCMS_C_DT_TEXT) {
			$new_value = trim(strval($avalue));
			
		### HTML ###
		} else if ($atype == DBHCMS_C_DT_HTML) {
			$new_value = trim(strval($avalue));
		
		### BOOLEAN ###
		} else if ($atype == DBHCMS_C_DT_BOOLEAN) {
			if (intval($avalue) == 1) {
				$new_value = true;
			} else {
				$new_value = false;
			}
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# STRUCT TYPES                                                             #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### MODULE ###
		} else if ($atype == DBHCMS_C_DT_MODULE) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRING);
		
		### MODARRAY ###
		} else if ($atype == DBHCMS_C_DT_MODARRAY) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRARRAY);
		
		### TEMPLATE ###
		} else if ($atype == DBHCMS_C_DT_TEMPLATE) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRING);
		
		### TPLARRAY ###
		} else if ($atype == DBHCMS_C_DT_TPLARRAY) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRARRAY);
		
		### JAVASCRIPT ###
		} else if ($atype == DBHCMS_C_DT_JAVASCRIPT) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRING);
		
		### JSARRAY ###
		} else if ($atype == DBHCMS_C_DT_JSARRAY) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRARRAY);
		
		### STYLESHEET ###
		} else if ($atype == DBHCMS_C_DT_STYLESHEET) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRING);
		
		### CSSARRAY ###
		} else if ($atype == DBHCMS_C_DT_CSSARRAY) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRARRAY);
		
		### EXTENSION ###
		} else if ($atype == DBHCMS_C_DT_EXTENSION) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRING);
		
		### EXTARRAY ###
		} else if ($atype == DBHCMS_C_DT_EXTARRAY) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRARRAY);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# FILE TYPES                                                               #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### DIRECTORY ###
		} else if ($atype == DBHCMS_C_DT_DIRECTORY) {
			$new_value = trim(strval($avalue));
			if (strlen($new_value) > 0) {
				$char = $new_value{0};
				if ($char == '/') {
					$new_value = substr($new_value , 1);
				}
				$char = $new_value{strlen($new_value)-1}; 
				if ($char != '/') {
					$new_value = $new_value.'/';
				}
			} else {
				$new_value = '/';
			}
		
		### DIRARRAY ###
		} else if ($atype == DBHCMS_C_DT_DIRARRAY) {
			$new_value = array();
			$temp = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRARRAY);
			foreach ($temp as $val) {
				if (trim($val) != '') {
					array_push($new_value, dbhcms_f_dbvalue_to_value($val, DBHCMS_C_DT_DIRECTORY));
				}
			}
		
		### FILE ###
		} else if ($atype == DBHCMS_C_DT_FILE) {
			$new_value = trim(strval($avalue));
			if (strlen($new_value) > 0) {
				$char = $new_value{0};
				if ($char == '/') {
					$new_value = substr($new_value , 1);
				}
			}
		
		### FILEARRAY ###
		} else if ($atype == DBHCMS_C_DT_FILEARRAY) {
			$new_value = array();
			$temp = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRARRAY);
			foreach ($temp as $val) {
				if (trim($val) != '') {
					array_push($new_value, dbhcms_f_dbvalue_to_value($val, DBHCMS_C_DT_FILE));
				}
			}
		
		### IMAGE ###
		} else if ($atype == DBHCMS_C_DT_IMAGE) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_FILE);
		
		### IMGARRAY ###
		} else if ($atype == DBHCMS_C_DT_IMGARRAY) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_FILEARRAY);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# ADVANCED TYPES                                                           #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### CONTENT ###
		} else if ($atype == DBHCMS_C_DT_CONTENT) {
			$new_value = strval($avalue);
		
		### PASSWORD ###
		} else if ($atype == DBHCMS_C_DT_PASSWORD) {
			$new_value = strval($avalue);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# OBJECT TYPES                                                             #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### USER ###
		} else if ($atype == DBHCMS_C_DT_USER) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRING);
		
		### USERARRAY ###
		} else if ($atype == DBHCMS_C_DT_USERARRAY) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRARRAY);
		
		### PAGE ###
		} else if ($atype == DBHCMS_C_DT_PAGE) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_INTEGER);
		
		### PAGEARRAY ###
		} else if ($atype == DBHCMS_C_DT_PAGEARRAY) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_INTARRAY);
		
		### DOMAIN ###
		} else if ($atype == DBHCMS_C_DT_DOMAIN) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_INTEGER);
		
		### DOMAINARRAY ###
		} else if ($atype == DBHCMS_C_DT_DOMAINARRAY) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_INTARRAY);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# PROPERTY TYPES                                                           #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### SEX ###
		} else if ($atype == DBHCMS_C_DT_SEX) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRING);
			if 	(($new_value != DBHCMS_C_ST_MALE) && ($new_value != DBHCMS_C_ST_FEMALE)) {
				$new_value = DBHCMS_C_ST_NONE;
			}
		
		### LANGUAGE ### 
		} else if ($atype == DBHCMS_C_DT_LANGUAGE) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRING);
		
		### LANGARRAY ###
		} else if ($atype == DBHCMS_C_DT_LANGARRAY) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRARRAY);
		
		### USERLEVEL ###
		} else if ($atype == DBHCMS_C_DT_USERLEVEL) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRING);
		
		### ULARRAY ###
		} else if ($atype == DBHCMS_C_DT_ULARRAY) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRARRAY);
		
		### DATATYPE ### 
		} else if ($atype == DBHCMS_C_DT_DATATYPE) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRING);
		
		### MENUTYPE ### 
		} else if ($atype == DBHCMS_C_DT_MENUTYPE) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRING);
			if 	(($new_value != DBHCMS_C_MT_ACTIVETREE) && ($new_value != DBHCMS_C_MT_LOCATION)) {
				$new_value = DBHCMS_C_MT_TREE;
			}
		
		### HIERARCHY ### 
		} else if ($atype == DBHCMS_C_DT_HIERARCHY) {
			$new_value = dbhcms_f_dbvalue_to_value($avalue, DBHCMS_C_DT_STRING);
			if 	(($new_value != DBHCMS_C_HT_ROOT) && ($new_value != DBHCMS_C_HT_SINGLE)) {
				$new_value = DBHCMS_C_HT_HEREDITARY;
			}
		
		### DATA TYPE UNKNOWN ###
		} else {
			dbhcms_p_error('Could not convert database value to runtime value. Wrong data type "'.strtoupper($atype).'".', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		}
		
		### OUTPUT ### 
		return $new_value;
	
	}

#############################################################################################
#                                                                                           #
#  FUNCTION: DBHCMS_F_VALUE_TO_DBVALUE                                                      #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#                                                                                           #
#  Turns a runtime value into a database value.                                             #
#                                                                                           #
#############################################################################################

	function dbhcms_f_value_to_dbvalue ($avalue, $atype) {
		
		$new_value = '';
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# BASIC TYPES                                                              #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### STRING ###
		if ($atype == DBHCMS_C_DT_STRING) {
			$new_value = str_replace("'", "''", trim(strval($avalue)));
		
		### STRARRAY ###
		} else if ($atype == DBHCMS_C_DT_STRARRAY) {
			if (is_array($avalue)) {
				foreach ($avalue as $val) {
					$val = dbhcms_f_value_to_dbvalue($val, DBHCMS_C_DT_STRING);
					if ($val != '') {
						$new_value .= $val.';';
					}
				}
				if (strlen($new_value) > 0) {
					$new_value = substr($new_value , 0, (strlen($new_value)-1));
				}
			} else {
				$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRING);
			}
		
		### INTEGER ###
		} else if ($atype == DBHCMS_C_DT_INTEGER) {
			$new_value = strval(intval($avalue));
		
		### INTARRAY ###
		} else if ($atype == DBHCMS_C_DT_INTARRAY) {
			if (is_array($avalue)) {
				foreach ($avalue as $val) {
					$val = dbhcms_f_value_to_dbvalue($val, DBHCMS_C_DT_INTEGER);
					if ($val != '') {
						$new_value .= $val.';';
					}
				}
				if (strlen($new_value) > 0) {
					$new_value = substr($new_value , 0, (strlen($new_value)-1));
				}
			} else {
				$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_INTEGER);
			}
		
		### DATE ###
		} else if ($atype == DBHCMS_C_DT_DATE) {
			$new_value = date($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['dateFormatDatabase'], $avalue);
		
		### TIME ###
		} else if ($atype == DBHCMS_C_DT_TIME) {
			$new_value = date($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['timeFormatDatabase'], $avalue);
		
		### DATETIME ###
		} else if ($atype == DBHCMS_C_DT_DATETIME) {
			$new_value = date($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['dateTimeFormatDatabase'], $avalue);
		
		### TEXT ###
		} else if ($atype == DBHCMS_C_DT_TEXT) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRING);
			
		### HTML ###
		} else if ($atype == DBHCMS_C_DT_HTML) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRING);
		
		### BOOLEAN ###
		} else if ($atype == DBHCMS_C_DT_BOOLEAN) {
			if ($avalue) {
				$new_value = '1';
			} else {
				$new_value = '0';
			}
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# STRUCT TYPES                                                             #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### MODULE ###
		} else if ($atype == DBHCMS_C_DT_MODULE) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRING);
		
		### MODARRAY ###
		} else if ($atype == DBHCMS_C_DT_MODARRAY) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRARRAY);
		
		### TEMPLATE ###
		} else if ($atype == DBHCMS_C_DT_TEMPLATE) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRING);
		
		### TPLARRAY ###
		} else if ($atype == DBHCMS_C_DT_TPLARRAY) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRARRAY);
		
		### JAVASCRIPT ###
		} else if ($atype == DBHCMS_C_DT_JAVASCRIPT) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRING);
		
		### JSARRAY ###
		} else if ($atype == DBHCMS_C_DT_JSARRAY) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRARRAY);
		
		### STYLESHEET ###
		} else if ($atype == DBHCMS_C_DT_STYLESHEET) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRING);
		
		### CSSARRAY ###
		} else if ($atype == DBHCMS_C_DT_CSSARRAY) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRARRAY);
		
		### EXTENSION ###
		} else if ($atype == DBHCMS_C_DT_EXTENSION) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRING);
		
		### EXTARRAY ###
		} else if ($atype == DBHCMS_C_DT_EXTARRAY) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRARRAY);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# FILE TYPES                                                               #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### DIRECTORY ###
		} else if ($atype == DBHCMS_C_DT_DIRECTORY) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRING);
			if (strlen($new_value) > 0) {
				$char = $new_value{0};
				if ($char == '/') {
					$new_value = substr($new_value , 1);
				}
				$char = $new_value{strlen($new_value)-1}; 
				if ($char != '/') {
					$new_value = $new_value.'/';
				}
			} else {
				$new_value = '/';
			}
		
		### DIRARRAY ###
		} else if ($atype == DBHCMS_C_DT_DIRARRAY) {
			if (is_array($avalue)) {
				foreach ($avalue as $val) {
					$val = dbhcms_f_value_to_dbvalue($val, DBHCMS_C_DT_DIRECTORY);
					if ($val != '') {
						$new_value .= $val.';';
					}
				}
				if (strlen($new_value) > 0) {
					$new_value = substr($new_value , 0, (strlen($new_value)-1));
				}
			} else {
				$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_DIRECTORY);
			}
		
		### FILE ###
		} else if ($atype == DBHCMS_C_DT_FILE) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRING);
			if (strlen($new_value) > 0) {
				$char = $new_value{0};
				if ($char == '/') {
					$new_value = substr($new_value , 1);
				}
			}
		
		### FILEARRAY ###
		} else if ($atype == DBHCMS_C_DT_FILEARRAY) {
			if (is_array($avalue)) {
				foreach ($avalue as $val) {
					$val = dbhcms_f_value_to_dbvalue($val, DBHCMS_C_DT_FILE);
					if ($val != '') {
						$new_value .= $val.';';
					}
				}
				if (strlen($new_value) > 0) {
					$new_value = substr($new_value , 0, (strlen($new_value)-1));
				}
			} else {
				$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_FILE);
			}
		
		### IMAGE ###
		} else if ($atype == DBHCMS_C_DT_IMAGE) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_FILE);
		
		### IMGARRAY ###
		} else if ($atype == DBHCMS_C_DT_IMGARRAY) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_FILEARRAY);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# ADVANCED TYPES                                                           #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### CONTENT ###
		} else if ($atype == DBHCMS_C_DT_CONTENT) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRING);
		
		### PASSWORD ###
		} else if ($atype == DBHCMS_C_DT_PASSWORD) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRING);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# OBJECT TYPES                                                             #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### USER ###
		} else if ($atype == DBHCMS_C_DT_USER) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRING);
		
		### USERARRAY ###
		} else if ($atype == DBHCMS_C_DT_USERARRAY) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRARRAY);
		
		### PAGE ###
		} else if ($atype == DBHCMS_C_DT_PAGE) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_INTEGER);
		
		### PAGEARRAY ###
		} else if ($atype == DBHCMS_C_DT_PAGEARRAY) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_INTARRAY);
		
		### DOMAIN ###
		} else if ($atype == DBHCMS_C_DT_DOMAIN) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_INTEGER);
		
		### DOMAINARRAY ###
		} else if ($atype == DBHCMS_C_DT_DOMAINARRAY) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_INTARRAY);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# PROPERTY TYPES                                                           #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### SEX ###
		} else if ($atype == DBHCMS_C_DT_SEX) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRING);
			if 	(($new_value != DBHCMS_C_ST_MALE) && ($new_value != DBHCMS_C_ST_FEMALE)) {
				$new_value = DBHCMS_C_ST_NONE;
			}
		
		### LANGUAGE ### 
		} else if ($atype == DBHCMS_C_DT_LANGUAGE) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRING);
		
		### LANGARRAY ###
		} else if ($atype == DBHCMS_C_DT_LANGARRAY) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRARRAY);
		
		### USERLEVEL ###
		} else if ($atype == DBHCMS_C_DT_USERLEVEL) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRING);
		
		### ULARRAY ###
		} else if ($atype == DBHCMS_C_DT_ULARRAY) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRARRAY);
		
		### DATATYPE ###
		} else if ($atype == DBHCMS_C_DT_DATATYPE) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRING);
		
		### MENUTYPE ###
		} else if ($atype == DBHCMS_C_DT_MENUTYPE) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRING);
			if 	(($new_value != DBHCMS_C_MT_ACTIVETREE) && ($new_value != DBHCMS_C_MT_LOCATION)) {
				$new_value = DBHCMS_C_MT_TREE;
			}
		
		### HIERARCHY ###
		} else if ($atype == DBHCMS_C_DT_HIERARCHY) {
			$new_value = dbhcms_f_value_to_dbvalue($avalue, DBHCMS_C_DT_STRING);
			if 	(($new_value != DBHCMS_C_HT_ROOT) && ($new_value != DBHCMS_C_HT_SINGLE)) {
				$new_value = DBHCMS_C_HT_HEREDITARY;
			}
		
		### DATA TYPE UNKNOWN ###
		} else {
			dbhcms_p_error('Could not convert runtime value to database value. Wrong data type "'.strtoupper($atype).'".', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		}
		
		### OUTPUT ### 
		return $new_value;
	
	}

#############################################################################################
#                                                                                           #
#  FUNCTION: DBHCMS_F_VALUE_TO_INPUT                                                        #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#                                                                                           #
#  Creates an input html-tag from a runtime value with a given type.                        #
#                                                                                           #
#############################################################################################

	function dbhcms_f_value_to_input($aname, $avalue, $atype, $aform, $astyle = "width: 180px;", $adomain = "x") {
		
		$input_html = '';
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# BASIC TYPES                                                              #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### STRING ###
		if ($atype == DBHCMS_C_DT_STRING) {
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<input type="text" name="'.$aname.'" value="'.htmlspecialchars($avalue).'" style="width: 98%">
										</td>
									</tr>
								</table>';
		
		### STRARRAY ###
		} else if ($atype == DBHCMS_C_DT_STRARRAY) {
			if (is_array($avalue)) { 
				$selectvals = '';
				$selectid = 0;
				foreach ($avalue as $aval) {
					if ($aval != '') {
						$selectvals .= '<option id="id'.$selectid.'" value="'.htmlspecialchars($aval).'">'.htmlspecialchars($aval).'</option>';
						$selectid++;
					}
				}
			} else {
				$selectvals = '';
			}
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<select name="'.$aname.'_sel" size="4" id="'.$aname.'_sel" style="width: 100%" >
												'.$selectvals.'
											</select>
										</td>
										<td width="15">
											<a href="#" onclick=" var myValue = prompt(\'Enter a string\',\'\'); if ((myValue != \'\') && (myValue != null)) { additem(\''.$aname.'_sel\', myValue, myValue, \'\',\'\',\'\',\'\',\'\',false); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); } return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a><br />
											<a href="#" onclick="up(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('selup', dbhcms_f_dict('up', true)).'</a><br />
											<a href="#" onclick="down(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldown', dbhcms_f_dict('down', true)).'</a><br />
											<a href="#" onclick="removeitem(\''.$aname.'_sel\', true); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldel', dbhcms_f_dict('delete', true)).'</a>
										</td>
										<input type="hidden" name="'.$aname.'" id="'.$aname.'" value="'.dbhcms_f_array_to_str($avalue, ';').'" />
									</tr>
								</table>';
		
		### INTEGER ###
		} else if ($atype == DBHCMS_C_DT_INTEGER) {
			if (!in_array('validate', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('validate', 'validate.js');
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<input type="text" name="'.$aname.'" value="'.intval($avalue).'" style="width: 98%" onblur=" return validateInteger(this, \''.strtoupper($aname).'\'); ">
										</td>
									</tr>
								</table>';
		
		### INTARRAY ###
		} else if ($atype == DBHCMS_C_DT_INTARRAY) {
			if (is_array($avalue)) { 
				$selectvals = '';
				$selectid = 0;
				foreach ($avalue as $aval) {
					if ($aval != '') {
						$selectvals .= '<option id="id'.$selectid.'" value="'.intval($aval).'">'.intval($aval).'</option>';
						$selectid++;
					}
				}
			} else {
				$selectvals = '';
			}
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'">
									<tr>
										<td>
											<select name="'.$aname.'_sel" size="4" id="'.$aname.'_sel" style="width: 100%">
												'.$selectvals.'
											</select>
										</td>
										<td width="15">
											<a href="#" onclick=" var myValue = prompt(\'Enter a number\',\'0\'); if (myValue != null) { if (myValue != \'\' && parseInt(myValue) != myValue) { alert(\'Invalid entry! Only numeric values allowed for the parameter &raquo;'.$aname.'&laquo; \'); } else { additem(\''.$aname.'_sel\', myValue, myValue, \'\',\'\',\'\',\'\',\'\',false); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); } } return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a><br />
											<a href="#" onclick="up(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('selup', dbhcms_f_dict('up', true)).'</a><br />
											<a href="#" onclick="down(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldown', dbhcms_f_dict('down', true)).'</a><br />
											<a href="#" onclick="removeitem(\''.$aname.'_sel\', true); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldel', dbhcms_f_dict('delete', true)).'</a>
										</td>
										<input type="hidden" name="'.$aname.'" id="'.$aname.'" value="'.dbhcms_f_array_to_str($avalue, ';').'" />
									</tr>
								</table>';
		
		
		### DATE ###
		} else if ($atype == DBHCMS_C_DT_DATE) {
			if (!in_array('dateSelector', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('dateSelector', 'datesel.js');
			}
			$year = intval(date('Y', $avalue));
			$month = intval(date('m', $avalue));
			$day = intval(date('d', $avalue));
			$year_options = '';
			for ($i = 1800; $i < 2290; $i++) {
				if ($i == $year) {
					$year_options .= '<option id="id'.$i.'" value="'.$i.'" selected="selected">'.$i.'</option>';
				} else {
					$year_options .= '<option id="id'.$i.'" value="'.$i.'">'.$i.'</option>';
				}
			}
			$month_options = '';
			for ($i = 1; $i < 13; $i++) {
				if ($i == $month) {
					$month_options .= '<option id="id'.$i.'" value="'.$i.'" selected="selected">'.$GLOBALS['DBHCMS']['DICT']['BE']['month_'.$i].'</option>';
				} else {
					$month_options .= '<option id="id'.$i.'" value="'.$i.'">'.$GLOBALS['DBHCMS']['DICT']['BE']['month_'.$i].'</option>';
				}
			}
			$day_options = '';
			for ($i = 1; $i < 32; $i++) {
				if ($i == $day) {
					$day_options .= '<option id="id'.$i.'" value="'.$i.'" selected="selected">'.$i.'</option>';
				} else {
					$day_options .= '<option id="id'.$i.'" value="'.$i.'">'.$i.'</option>';
				}
			}
			$input_html = 	'	<table style="'.$astyle.'">
									<tr>
										<td width="55">
											<select name="'.$aname.'_year" id="'.$aname.'_year" onchange="checkDate(\''.$aname.'\');" style="width: 100%">
												'.$year_options.'
											</select>
										</td>
										<td>
											<select name="'.$aname.'_month" id="'.$aname.'_month" onchange="checkDate(\''.$aname.'\');" style="width: 100%">
												'.$month_options.'
											</select>
										</td>
										<td width="40">
											<select name="'.$aname.'_day" id="'.$aname.'_day" onchange="checkDate(\''.$aname.'\');" style="width: 100%">
												'.$day_options.'
											</select>
										</td>
										<input type="hidden" name="'.$aname.'" id="'.$aname.'" value="dummy" />
									</tr>
								</table>';
		
		### TIME ###
		} else if ($atype == DBHCMS_C_DT_TIME) {
			$hour = intval(date('H', $avalue));
			$minute = intval(date('i', $avalue));
			$second = intval(date('s', $avalue));
			$hour_options = '';
			for ($i = 0; $i < 25; $i++) {
				if ($i < 10) {
					$cap = '0'.$i;
				} else {
					$cap = $i;
				}
				if ($i == $hour) {
					$hour_options .= '<option id="id'.$i.'" value="'.$i.'" selected="selected">'.$cap.'</option>';
				} else {
					$hour_options .= '<option id="id'.$i.'" value="'.$i.'">'.$cap.'</option>';
				}
			}
			$minute_options = '';
			for ($i = 0; $i < 60; $i++) {
				if ($i < 10) {
					$cap = '0'.$i;
				} else {
					$cap = $i;
				}
				if ($i == $minute) {
					$minute_options .= '<option id="id'.$i.'" value="'.$i.'" selected="selected">'.$cap.'</option>';
				} else {
					$minute_options .= '<option id="id'.$i.'" value="'.$i.'">'.$cap.'</option>';
				}
			}
			$second_options = '';
			for ($i = 0; $i < 60; $i++) {
				if ($i < 10) {
					$cap = '0'.$i;
				} else {
					$cap = $i;
				}
				if ($i == $second) {
					$second_options .= '<option id="id'.$i.'" value="'.$i.'" selected="selected">'.$cap.'</option>';
				} else {
					$second_options .= '<option id="id'.$i.'" value="'.$i.'">'.$cap.'</option>';
				}
			}
			
			$input_html = 	'	<table style="'.$astyle.'">
									<tr>
										<td>
											H:
											<select name="'.$aname.'_hour" id="'.$aname.'_hour" style="width: 40px;">
												'.$hour_options.'
											</select>
										</td>
										<td>
											M:
											<select name="'.$aname.'_minute" id="'.$aname.'_minute" style="width: 40px;">
												'.$minute_options.'
											</select>
										</td>
										<td>
											S:
											<select name="'.$aname.'_second" id="'.$aname.'_second" style="width: 40px;">
												'.$second_options.'
											</select>
										</td>
										<input type="hidden" name="'.$aname.'" id="'.$aname.'" value="dummy" />
									</tr>
								</table>';
		
		### DATETIME ###
		} else if ($atype == DBHCMS_C_DT_DATETIME) {
			if (!in_array('dateSelector', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('dateSelector', 'datesel.js');
			}
			$year = intval(date('Y', $avalue));
			$month = intval(date('m', $avalue));
			$day = intval(date('d', $avalue));
			$hour = intval(date('H', $avalue));
			$minute = intval(date('i', $avalue));
			$second = intval(date('s', $avalue));
			$year_options = '';
			for ($i = 1800; $i < 2290; $i++) {
				if ($i == $year) {
					$year_options .= '<option id="id'.$i.'" value="'.$i.'" selected="selected">'.$i.'</option>';
				} else {
					$year_options .= '<option id="id'.$i.'" value="'.$i.'">'.$i.'</option>';
				}
			}
			$month_options = '';
			for ($i = 1; $i < 13; $i++) {
				if ($i == $month) {
					$month_options .= '<option id="id'.$i.'" value="'.$i.'" selected="selected">'.$GLOBALS['DBHCMS']['DICT']['BE']['month_'.$i].'</option>';
				} else {
					$month_options .= '<option id="id'.$i.'" value="'.$i.'">'.$GLOBALS['DBHCMS']['DICT']['BE']['month_'.$i].'</option>';
				}
			}
			$day_options = '';
			for ($i = 1; $i < 32; $i++) {
				if ($i == $day) {
					$day_options .= '<option id="id'.$i.'" value="'.$i.'" selected="selected">'.$i.'</option>';
				} else {
					$day_options .= '<option id="id'.$i.'" value="'.$i.'">'.$i.'</option>';
				}
			}
			$hour_options = '';
			for ($i = 0; $i < 25; $i++) {
				if ($i < 10) {
					$cap = '0'.$i;
				} else {
					$cap = $i;
				}
				if ($i == $hour) {
					$hour_options .= '<option id="id'.$i.'" value="'.$i.'" selected="selected">'.$cap.'</option>';
				} else {
					$hour_options .= '<option id="id'.$i.'" value="'.$i.'">'.$cap.'</option>';
				}
			}
			$minute_options = '';
			for ($i = 0; $i < 60; $i++) {
				if ($i < 10) {
					$cap = '0'.$i;
				} else {
					$cap = $i;
				}
				if ($i == $minute) {
					$minute_options .= '<option id="id'.$i.'" value="'.$i.'" selected="selected">'.$cap.'</option>';
				} else {
					$minute_options .= '<option id="id'.$i.'" value="'.$i.'">'.$cap.'</option>';
				}
			}
			$second_options = '';
			for ($i = 0; $i < 60; $i++) {
				if ($i < 10) {
					$cap = '0'.$i;
				} else {
					$cap = $i;
				}
				if ($i == $second) {
					$second_options .= '<option id="id'.$i.'" value="'.$i.'" selected="selected">'.$cap.'</option>';
				} else {
					$second_options .= '<option id="id'.$i.'" value="'.$i.'">'.$cap.'</option>';
				}
			}
			
			$input_html = 	'	<table style="'.$astyle.'">
									<tr>
										<td>
											<table style="width: 100%">
												<tr>
													<td width="55">
														<select name="'.$aname.'_year" id="'.$aname.'_year" onchange="checkDate(\''.$aname.'\');" style="width: 100%">
															'.$year_options.'
														</select>
													</td>
													<td>
														<select name="'.$aname.'_month" id="'.$aname.'_month" onchange="checkDate(\''.$aname.'\');" style="width: 100%">
															'.$month_options.'
														</select>
													</td>
													<td width="40">
														<select name="'.$aname.'_day" id="'.$aname.'_day" onchange="checkDate(\''.$aname.'\');" style="width: 100%">
															'.$day_options.'
														</select>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<table style="width: 100%">
												<tr>
													<td>
														H:
														<select name="'.$aname.'_hour" id="'.$aname.'_hour" style="width: 40px;">
															'.$hour_options.'
														</select>
													</td>
													<td>
														M:
														<select name="'.$aname.'_minute" id="'.$aname.'_minute" style="width: 40px;">
															'.$minute_options.'
														</select>
													</td>
													<td>
														S:
														<select name="'.$aname.'_second" id="'.$aname.'_second" style="width: 40px;">
															'.$second_options.'
														</select>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<input type="hidden" name="'.$aname.'" id="'.$aname.'" value="dummy" />
								</table>';
		
		
		### TEXT ###
		} else if ($atype == DBHCMS_C_DT_TEXT) {
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<textarea rows="5" name="'.$aname.'" wrap="soft" style="width: 98%">'.htmlspecialchars($avalue).'</textarea>
										</td>
									</tr>
								</table>';
			
		### HTML ###
		} else if ($atype == DBHCMS_C_DT_HTML) {
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<textarea rows="5" name="'.$aname.'" wrap="off" style="width: 98%">'.htmlspecialchars($avalue).'</textarea>
										</td>
									</tr>
								</table>';
		
		### BOOLEAN ###
		} else if ($atype == DBHCMS_C_DT_BOOLEAN) {
			if ($avalue) {
				$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
										<tr>
											<td width="60">
												<input type="radio" name="'.$aname.'" value="1" style="border:0px; background-color: transparent;" checked="checked" /> True
											</td>
											<td>
												<input type="radio" name="'.$aname.'" value="0" style="border:0px; background-color: transparent;" /> False
											</td>
										</tr>
									</table>';
			} else {
				$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
										<tr>
											<td width="60">
												<input type="radio" name="'.$aname.'" value="1" style="border:0px; background-color: transparent;" /> True
											</td>
											<td>
												<input type="radio" name="'.$aname.'" value="0" style="border:0px; background-color: transparent;" checked="checked" /> False
											</td>
										</tr>
									</table>';
			}
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# STRUCT TYPES                                                             #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### MODULE ###
		} else if ($atype == DBHCMS_C_DT_MODULE) {
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<input readonly="readonly" type="text" name="'.$aname.'" id="'.$aname.'" value="'.htmlspecialchars($avalue).'" style="width: 98%">
										</td>
										<td width="15">
											 <a href="#" onclick="getNewValue(\''.$aname.'\', \''.DBHCMS_C_DT_FILE.'\', 600, 450, \'&root_dir='.urlencode('/'.substr($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['moduleDirectory'], 0, (strlen($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['moduleDirectory'])-1))).'\'); return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a>
										</td>
									</tr>
								</table>';
		
		### MODARRAY ###
		} else if ($atype == DBHCMS_C_DT_MODARRAY) {
			if (is_array($avalue)) { 
				$selectvals = '';
				$selectid = 0;
				foreach ($avalue as $aval) {
					if ($aval != '') {
						$selectvals .= '<option id="id'.$selectid.'" value="'.htmlspecialchars($aval).'">'.htmlspecialchars($aval).'</option>';
						$selectid++;
					}
				}
			} else {
				$selectvals = '';
			}
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<select name="'.$aname.'_sel" size="4" id="'.$aname.'_sel" style="width: 100%" >
												'.$selectvals.'
											</select>
										</td>
										<td width="15">
											<a href="#" onclick="getNewValue(\''.$aname.'\', \''.DBHCMS_C_DT_FILE.'\', 600, 450, \'&root_dir='.urlencode('/'.substr($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['moduleDirectory'], 0, (strlen($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['moduleDirectory'])-1))).'\'); return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a><br />
											<a href="#" onclick="up(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('selup', dbhcms_f_dict('up', true)).'</a><br />
											<a href="#" onclick="down(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldown', dbhcms_f_dict('down', true)).'</a><br />
											<a href="#" onclick="removeitem(\''.$aname.'_sel\', true); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldel', dbhcms_f_dict('delete', true)).'</a>
										</td>
										<input type="hidden" name="'.$aname.'" id="'.$aname.'" value="'.dbhcms_f_array_to_str($avalue, ';').'" />
									</tr>
								</table>';
		
		### TEMPLATE ###
		} else if ($atype == DBHCMS_C_DT_TEMPLATE) {
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<input readonly="readonly" type="text" name="'.$aname.'" id="'.$aname.'" value="'.htmlspecialchars($avalue).'" style="width: 98%">
										</td>
										<td width="15">
											 <a href="#" onclick="getNewValue(\''.$aname.'\', \''.DBHCMS_C_DT_FILE.'\', 600, 450, \'&root_dir='.urlencode('/'.substr($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['templateDirectory'], 0, (strlen($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['templateDirectory'])-1))).'\'); return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a>
										</td>
									</tr>
								</table>';
		
		### TPLARRAY ###
		} else if ($atype == DBHCMS_C_DT_TPLARRAY) {
			if (is_array($avalue)) { 
				$selectvals = '';
				$selectid = 0;
				foreach ($avalue as $aval) {
					if ($aval != '') {
						$selectvals .= '<option id="id'.$selectid.'" value="'.htmlspecialchars($aval).'">'.htmlspecialchars($aval).'</option>';
						$selectid++;
					}
				}
			} else {
				$selectvals = '';
			}
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'">
									<tr>
										<td>
											<select name="'.$aname.'_sel" size="4" id="'.$aname.'_sel" style="width: 100%" >
												'.$selectvals.'
											</select>
										</td>
										<td width="15">
											<a href="#" onclick="getNewValue(\''.$aname.'\', \''.DBHCMS_C_DT_FILE.'\', 600, 450, \'&root_dir='.urlencode('/'.substr($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['templateDirectory'], 0, (strlen($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['templateDirectory'])-1))).'\'); return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a><br />
											<a href="#" onclick="up(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('selup', dbhcms_f_dict('up', true)).'</a><br />
											<a href="#" onclick="down(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldown', dbhcms_f_dict('down', true)).'</a><br />
											<a href="#" onclick="removeitem(\''.$aname.'_sel\', true); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldel', dbhcms_f_dict('delete', true)).'</a>
										</td>
										<input type="hidden" name="'.$aname.'" id="'.$aname.'" value="'.dbhcms_f_array_to_str($avalue, ';').'" />
									</tr>
								</table>';
		
		### JAVASCRIPT ###
		} else if ($atype == DBHCMS_C_DT_JAVASCRIPT) {
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<input readonly="readonly" type="text" name="'.$aname.'" id="'.$aname.'" value="'.htmlspecialchars($avalue).'" style="width: 98%">
										</td>
										<td width="15">
											 <a href="#" onclick="getNewValue(\''.$aname.'\', \''.DBHCMS_C_DT_FILE.'\', 600, 450, \'&root_dir='.urlencode('/'.substr($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['javaDirectory'], 0, (strlen($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['javaDirectory'])-1))).'\'); return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a>
										</td>
									</tr>
								</table>';
		
		### JSARRAY ###
		} else if ($atype == DBHCMS_C_DT_JSARRAY) {
			if (is_array($avalue)) { 
				$selectvals = '';
				$selectid = 0;
				foreach ($avalue as $aval) {
					if ($aval != '') {
						$selectvals .= '<option id="id'.$selectid.'" value="'.htmlspecialchars($aval).'">'.htmlspecialchars($aval).'</option>';
						$selectid++;
					}
				}
			} else {
				$selectvals = '';
			}
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'">
									<tr>
										<td>
											<select name="'.$aname.'_sel" size="4" id="'.$aname.'_sel" style="width: 100%" >
												'.$selectvals.'
											</select>
										</td>
										<td width="15">
											<a href="#" onclick="getNewValue(\''.$aname.'\', \''.DBHCMS_C_DT_FILE.'\', 600, 450, \'&root_dir='.urlencode('/'.substr($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['javaDirectory'], 0, (strlen($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['javaDirectory'])-1))).'\'); return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a><br />
											<a href="#" onclick="up(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('selup', dbhcms_f_dict('up', true)).'</a><br />
											<a href="#" onclick="down(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldown', dbhcms_f_dict('down', true)).'</a><br />
											<a href="#" onclick="removeitem(\''.$aname.'_sel\', true); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldel', dbhcms_f_dict('delete', true)).'</a>
										</td>
										<input type="hidden" name="'.$aname.'" id="'.$aname.'" value="'.dbhcms_f_array_to_str($avalue, ';').'" />
									</tr>
								</table>';
		
		### STYLESHEET ###
		} else if ($atype == DBHCMS_C_DT_STYLESHEET) {
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<input readonly="readonly" type="text" name="'.$aname.'" id="'.$aname.'" value="'.htmlspecialchars($avalue).'" style="width: 98%">
										</td>
										<td width="15">
											 <a href="#" onclick="getNewValue(\''.$aname.'\', \''.DBHCMS_C_DT_FILE.'\', 600, 450, \'&root_dir='.urlencode('/'.substr($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['cssDirectory'], 0, (strlen($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['cssDirectory'])-1))).'\'); return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a>
										</td>
									</tr>
								</table>';
		
		### CSSARRAY ###
		} else if ($atype == DBHCMS_C_DT_CSSARRAY) {
			if (is_array($avalue)) { 
				$selectvals = '';
				$selectid = 0;
				foreach ($avalue as $aval) {
					if ($aval != '') {
						$selectvals .= '<option id="id'.$selectid.'" value="'.htmlspecialchars($aval).'">'.htmlspecialchars($aval).'</option>';
						$selectid++;
					}
				}
			} else {
				$selectvals = '';
			}
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<select name="'.$aname.'_sel" size="4" id="'.$aname.'_sel" style="width: 100%" >
												'.$selectvals.'
											</select>
										</td>
										<td width="15">
											<a href="#" onclick="getNewValue(\''.$aname.'\', \''.DBHCMS_C_DT_FILE.'\', 600, 450, \'&root_dir='.urlencode('/'.substr($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['cssDirectory'], 0, (strlen($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['cssDirectory'])-1))).'\'); return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a><br />
											<a href="#" onclick="up(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('selup', dbhcms_f_dict('up', true)).'</a><br />
											<a href="#" onclick="down(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldown', dbhcms_f_dict('down', true)).'</a><br />
											<a href="#" onclick="removeitem(\''.$aname.'_sel\', true); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldel', dbhcms_f_dict('delete', true)).'</a>
										</td>
										<input type="hidden" name="'.$aname.'" id="'.$aname.'" value="'.dbhcms_f_array_to_str($avalue, ';').'" />
									</tr>
								</table>';
		
		### EXTENSION ###
		} else if ($atype == DBHCMS_C_DT_EXTENSION) {
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<input readonly="readonly" type="text" name="'.$aname.'" id="'.$aname.'" value="'.htmlspecialchars($avalue).'" style="width: 98%">
										</td>
										<td width="15">
											 <a href="#" onclick="getNewValue(\''.$aname.'\', \''.DBHCMS_C_DT_EXTENSION.'\', 600, 450, \'\'); return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a>
										</td>
									</tr>
								</table>';
		
		### EXTARRAY ###
		} else if ($atype == DBHCMS_C_DT_EXTARRAY) {
			if (is_array($avalue)) { 
				$selectvals = '';
				$selectid = 0;
				foreach ($avalue as $aval) {
					if ($aval != '') {
						$selectvals .= '<option id="id'.$selectid.'" value="'.htmlspecialchars($aval).'">'.htmlspecialchars($aval).'</option>';
						$selectid++;
					}
				}
			} else {
				$selectvals = '';
			}
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<select name="'.$aname.'_sel" size="4" id="'.$aname.'_sel" style="width: 100%" >
												'.$selectvals.'
											</select>
										</td>
										<td width="15">
											<a href="#" onclick="getNewValue(\''.$aname.'\', \''.DBHCMS_C_DT_EXTENSION.'\', 600, 450, \'\'); return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a><br />
											<a href="#" onclick="up(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('selup', dbhcms_f_dict('up', true)).'</a><br />
											<a href="#" onclick="down(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldown', dbhcms_f_dict('down', true)).'</a><br />
											<a href="#" onclick="removeitem(\''.$aname.'_sel\', true); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldel', dbhcms_f_dict('delete', true)).'</a>
										</td>
										<input type="hidden" name="'.$aname.'" id="'.$aname.'" value="'.dbhcms_f_array_to_str($avalue, ';').'" />
									</tr>
								</table>';
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# FILE TYPES                                                               #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### DIRECTORY ###
		} else if ($atype == DBHCMS_C_DT_DIRECTORY) {
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<input readonly="readonly" type="text" name="'.$aname.'" id="'.$aname.'" value="'.htmlspecialchars($avalue).'" style="width: 98%">
										</td>
										<td width="15">
											 <a href="#" onclick="getNewValue(\''.$aname.'\', \''.DBHCMS_C_DT_DIRECTORY.'\', 600, 450, \'&root_dir=\'); return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a>
										</td>
									</tr>
								</table>';
		
		### DIRARRAY ###
		} else if ($atype == DBHCMS_C_DT_DIRARRAY) {
			if (is_array($avalue)) { 
				$selectvals = '';
				$selectid = 0;
				foreach ($avalue as $aval) {
					if ($aval != '') {
						$selectvals .= '<option id="id'.$selectid.'" value="'.htmlspecialchars($aval).'">'.htmlspecialchars($aval).'</option>';
						$selectid++;
					}
				}
			} else {
				$selectvals = '';
			}
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'">
									<tr>
										<td>
											<select name="'.$aname.'_sel" size="4" id="'.$aname.'_sel" style="width: 100%" >
												'.$selectvals.'
											</select>
										</td>
										<td width="15">
											<a href="#" onclick="getNewValue(\''.$aname.'\', \''.DBHCMS_C_DT_DIRECTORY.'\', 600, 450, \'&root_dir=\'); return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a><br />
											<a href="#" onclick="up(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('selup', dbhcms_f_dict('up', true)).'</a><br />
											<a href="#" onclick="down(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldown', dbhcms_f_dict('down', true)).'</a><br />
											<a href="#" onclick="removeitem(\''.$aname.'_sel\', true); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldel', dbhcms_f_dict('delete', true)).'</a>
										</td>
										<input type="hidden" name="'.$aname.'" id="'.$aname.'" value="'.dbhcms_f_array_to_str($avalue, ';').'" />
									</tr>
								</table>';
		
		### FILE ###
		} else if ($atype == DBHCMS_C_DT_FILE) {
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<input readonly="readonly" type="text" name="'.$aname.'" id="'.$aname.'" value="'.htmlspecialchars($avalue).'" style="width: 98%">
										</td>
										<td width="15">
											 <a href="#" onclick="getNewValue(\''.$aname.'\', \''.DBHCMS_C_DT_FILE.'\', 600, 450, \'&root_dir=\'); return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a>
										</td>
									</tr>
								</table>';
		
		### FILEARRAY ###
		} else if ($atype == DBHCMS_C_DT_FILEARRAY) {
			if (is_array($avalue)) { 
				$selectvals = '';
				$selectid = 0;
				foreach ($avalue as $aval) {
					if ($aval != '') {
						$selectvals .= '<option id="id'.$selectid.'" value="'.htmlspecialchars($aval).'">'.htmlspecialchars($aval).'</option>';
						$selectid++;
					}
				}
			} else {
				$selectvals = '';
			}
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'">
									<tr>
										<td>
											<select name="'.$aname.'_sel" size="4" id="'.$aname.'_sel" style="width: 100%" >
												'.$selectvals.'
											</select>
										</td>
										<td width="15">
											<a href="#" onclick="getNewValue(\''.$aname.'\', \''.DBHCMS_C_DT_FILE.'\', 600, 450, \'&root_dir=\'); return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a><br />
											<a href="#" onclick="up(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('selup', dbhcms_f_dict('up', true)).'</a><br />
											<a href="#" onclick="down(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldown', dbhcms_f_dict('down', true)).'</a><br />
											<a href="#" onclick="removeitem(\''.$aname.'_sel\', true); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldel', dbhcms_f_dict('delete', true)).'</a>
										</td>
										<input type="hidden" name="'.$aname.'" id="'.$aname.'" value="'.dbhcms_f_array_to_str($avalue, ';').'" />
									</tr>
								</table>';
		
		### IMAGE ###
		} else if ($atype == DBHCMS_C_DT_IMAGE) {
			$input_html = dbhcms_f_value_to_input($aname, $avalue, DBHCMS_C_DT_STRING, $aform, $astyle, $adomain);
		
		### IMGARRAY ###
		} else if ($atype == DBHCMS_C_DT_IMGARRAY) {
			$input_html = dbhcms_f_value_to_input($aname, $avalue, DBHCMS_C_DT_STRARRAY, $aform, $astyle, $adomain);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# ADVANCED TYPES                                                           #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### CONTENT ###
		} else if ($atype == DBHCMS_C_DT_CONTENT) {
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$temp_dir = $GLOBALS['DBHCMS']['CONFIG']['CORE']['tempDirectory'];
			$temp_file = "tmp.content.".$aname.".".$_SESSION['DBHCMSDATA']['SID'].".txt";
			$content_file = fopen($temp_dir.$temp_file, "w");
			fwrite($content_file, $avalue);
			fclose($content_file);
			if (strlen($avalue) > 140) {
				$input_html = substr(strip_tags($avalue), 0, 135)."...<br>".$input_html;
			} else { 
				$input_html = strip_tags($avalue).$input_html; 
			}
			$input_html = "	<input type=\"hidden\" name=\"".$aname."\" id=\"".$aname."\" value=\"".$aname."\" /> 
							<a style=\"cursor:pointer;\" onclick=\" openModalWindow('index.php?dbhcms_pid=-6&form=".$aform."&file=".$GLOBALS['DBHCMS']['CONFIG']['CORE']['tempDirectory']."tmp.content.".$aname.".".$_SESSION['DBHCMSDATA']['SID'].".txt&style=".$astyle."', 785, 665);\">
								".$input_html."
								<strong>[CONTENT]</strong>
								".dbhcms_f_get_icon('document-properties', dbhcms_f_dict('edit', true), 1)."
							</a>";
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											'.$input_html.'
										</td>
									</tr>
								</table>';
		
		### PASSWORD ###
		} else if ($atype == DBHCMS_C_DT_PASSWORD) {
			if ($avalue == '') {
				$input_html = '<input type="password" name="'.$aname.'" id="'.$aname.'" value="" style="width: 98%">';
			} else { 
				$input_html = '<input type="password" name="'.$aname.'" id="'.$aname.'" value="###NOCHANGE###" style="width: 98%">'; 
			}
			$_SESSION['DBHCMSDATA']['TEMP']['inputPasswd'][$aname.".".$_SESSION['DBHCMSDATA']['SID']] = $avalue;
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											'.$input_html.'
										</td>
									</tr>
								</table>';
		
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# OBJECT TYPES                                                             #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### USER ###
		} else if ($atype == DBHCMS_C_DT_USER) {
			$input_html = '';
			$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_USERS);
			while ($row = mysql_fetch_assoc($result)) {
				if ($avalue == $row['user_login']) {
					$input_html .= '<option value="'.$row['user_login'].'" selected="selected"  >'.$row['user_login'].' ('.$row['user_name'].')</option>';
				} else {
					$input_html .= '<option value="'.$row['user_login'].'" >'.$row['user_login'].' ('.$row['user_name'].')</option>';
				}
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<select name="'.$aname.'" id="'.$aname.'" style="width: 98%">'.$input_html.'</select>
										</td>
									</tr>
								</table>';
		
		### USERARRAY ###
		} else if ($atype == DBHCMS_C_DT_USERARRAY) {
			if (is_array($avalue)) { 
				$selectvals = '';
				$selectid = 0;
				foreach ($avalue as $aval) {
					if ($aval != '') {
						$selectvals .= '<option id="id'.$selectid.'" value="'.htmlspecialchars($aval).'">'.htmlspecialchars($aval).'</option>';
						$selectid++;
					}
				}
			} else {
				$selectvals = '';
			}
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'">
									<tr>
										<td>
											<select name="'.$aname.'_sel" size="4" id="'.$aname.'_sel" style="width: 100%" >
												'.$selectvals.'
											</select>
										</td>
										<td width="15">
											<a href="#" onclick="getNewValue(\''.$aname.'\', \''.DBHCMS_C_DT_USER.'\', 400, 450, \'\'); return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a><br />
											<a href="#" onclick="up(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('selup', dbhcms_f_dict('up', true)).'</a><br />
											<a href="#" onclick="down(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldown', dbhcms_f_dict('down', true)).'</a><br />
											<a href="#" onclick="removeitem(\''.$aname.'_sel\', true); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldel', dbhcms_f_dict('delete', true)).'</a>
										</td>
										<input type="hidden" name="'.$aname.'" id="'.$aname.'" value="'.dbhcms_f_array_to_str($avalue, ';').'" />
									</tr>
								</table>';
		
		### PAGE ###
		} else if ($atype == DBHCMS_C_DT_PAGE) {
			$input_html = '';
			if ($adomain == 'x') {
				$result = mysql_query("SELECT domn_id FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS);
				while ($row = mysql_fetch_assoc($result)) {
					$caps = dbhcms_f_get_page_select_options(array($avalue), dbhcms_f_create_page_tree($row['domn_id'], $_SESSION['DBHCMSDATA']['LANG']['coreUseLanguage']));
					foreach ($caps as $cap) {
						$input_html .= $cap;
					}
				}
			} else {
				$caps = dbhcms_f_get_page_select_options(array($avalue), dbhcms_f_create_page_tree($adomain, $_SESSION['DBHCMSDATA']['LANG']['coreUseLanguage']));
				foreach ($caps as $cap) {
					$input_html .= $cap;
				}
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<select name="'.$aname.'" id="'.$aname.'" style="width: 98%">'.$input_html.'</select>
										</td>
									</tr>
								</table>';
		
		### PAGEARRAY ###
		} else if ($atype == DBHCMS_C_DT_PAGEARRAY) {
			$input_html = '';
			if ($adomain == 'x') {
				$result = mysql_query("SELECT domn_id FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS);
				while ($row = mysql_fetch_assoc($result)) {
					$caps = dbhcms_f_get_page_select_options(array($avalue), dbhcms_f_create_page_tree($row['domn_id'], $_SESSION['DBHCMSDATA']['LANG']['coreUseLanguage']));
					foreach ($caps as $cap) {
						$input_html .= $cap;
					}
				}
			} else {
				$caps = dbhcms_f_get_page_select_options(array($avalue), dbhcms_f_create_page_tree($adomain, $_SESSION['DBHCMSDATA']['LANG']['coreUseLanguage']));
				foreach ($caps as $cap) {
					$input_html .= $cap;
				}
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<select name="'.$aname.'[]" id="'.$aname.'" size="6" style="width: 98%" multiple="multiple">'.$input_html.'</select>
										</td>
									</tr>
								</table>';
		
		### DOMAIN ###
		} else if ($atype == DBHCMS_C_DT_DOMAIN) {
			if (!is_array($avalue)) { $avalue = explode(';', $avalue); }
			$input_html = '';
			$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS);
			while ($row = mysql_fetch_assoc($result)){
				if (in_array($row['domn_id'], $avalue)) {
					$input_html .= '<option value="'.$row['domn_id'].'" selected="selected" > '.$row['domn_name'].' </option>';
				} else { $input_html .= '<option value="'.$row['domn_id'].'"> '.$row['domn_name'].' </option>'; }
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<select name="'.$aname.'" id="'.$aname.'" style="width: 98%;">'.$input_html.'</select>
										</td>
									</tr>
								</table>';
		
		### DOMAINARRAY ###
		} else if ($atype == DBHCMS_C_DT_DOMAINARRAY) {
			if (is_array($avalue)) { 
				$selectvals = '';
				
				$result = mysql_query("SELECT * FROM ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'].DBHCMS_C_TBL_DOMAINS);
				while ($row = mysql_fetch_assoc($result)){
					if (in_array($row['domn_id'], $avalue)) {
						$selectvals .= '<option value="'.$row['domn_id'].'"> '.$row['domn_name'].' </option>';
					}
				}
				
			} else {
				$selectvals = '';
			}
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<select name="'.$aname.'_sel" size="4" id="'.$aname.'_sel" style="width: 100%" >
												'.$selectvals.'
											</select>
										</td>
										<td width="15">
											<a href="#" onclick="getNewValue(\''.$aname.'\', \''.DBHCMS_C_DT_DOMAIN.'\', 400, 450, \'\'); return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a><br />
											<a href="#" onclick="up(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('selup', dbhcms_f_dict('up', true)).'</a><br />
											<a href="#" onclick="down(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldown', dbhcms_f_dict('down', true)).'</a><br />
											<a href="#" onclick="removeitem(\''.$aname.'_sel\', true); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldel', dbhcms_f_dict('delete', true)).'</a>
										</td>
										<input type="hidden" name="'.$aname.'" id="'.$aname.'" value="'.dbhcms_f_array_to_str($avalue, ';').'" />
									</tr>
								</table>';
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# PROPERTY TYPES                                                           #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### SEX ###
		} else if ($atype == DBHCMS_C_DT_SEX) {
			if ($avalue == DBHCMS_C_ST_MALE) {
				$input_html = '<select name="'.$aname.'" id="'.$aname.'" style="width: 98%;">
								<option value="'.DBHCMS_C_ST_NONE.'"> </option>
								<option value="'.DBHCMS_C_ST_FEMALE.'">'.$GLOBALS['DBHCMS']['DICT']['BE']['female'].'</option>
								<option value="'.DBHCMS_C_ST_MALE.'" selected="selected">'.$GLOBALS['DBHCMS']['DICT']['BE']['male'].'</option>
							  </select>';
			} else if ($avalue == DBHCMS_C_ST_FEMALE) {
				$input_html = '<select name="'.$aname.'" id="'.$aname.'" style="width: 98%;">
								<option value="'.DBHCMS_C_ST_NONE.'"> </option>
								<option value="'.DBHCMS_C_ST_FEMALE.'" selected="selected">'.$GLOBALS['DBHCMS']['DICT']['BE']['female'].'</option>
								<option value="'.DBHCMS_C_ST_MALE.'">'.$GLOBALS['DBHCMS']['DICT']['BE']['male'].'</option>
							  </select>';
			} else {
				$input_html = '<select name="'.$aname.'" id="'.$aname.'" style="width: 98%;">
								<option value="'.DBHCMS_C_ST_NONE.'" selected="selected"> </option>
								<option value="'.DBHCMS_C_ST_FEMALE.'">'.$GLOBALS['DBHCMS']['DICT']['BE']['female'].'</option>
								<option value="'.DBHCMS_C_ST_MALE.'">'.$GLOBALS['DBHCMS']['DICT']['BE']['male'].'</option>
							  </select>';
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											'.$input_html.'
										</td>
									</tr>
								</table>';
		
		### LANGUAGE ### 
		} else if ($atype == DBHCMS_C_DT_LANGUAGE) {
			$dbhcms_lang_array = array();
			$input_html = '';
			foreach ($GLOBALS['DBHCMS']['LANGS'] as $tmkey => $tmvalue) { array_push($dbhcms_lang_array, $tmvalue); }
			$dbhcms_lang_array = array_unique($dbhcms_lang_array);
			sort($dbhcms_lang_array);
			foreach ($dbhcms_lang_array as $tmvalue) {
				if ($atype == DBHCMS_C_DT_LANGUAGE) {
					if ($tmvalue == $avalue) {
						$input_html .= '<option value="'.$tmvalue.'" selected>'.$tmvalue.' ('.$GLOBALS['DBHCMS']['DICT']['BE'][$tmvalue].')</option>';
					} else { $input_html .= '<option value="'.$tmvalue.'">'.$tmvalue.' ('.$GLOBALS['DBHCMS']['DICT']['BE'][$tmvalue].')</option>'; }
				} else {
					if (!is_array($avalue)) { $avalue = explode(';', $avalue); }
					if (in_array($tmvalue, $avalue)) {
						$input_html .= '<option value="'.$tmvalue.'" selected>'.$tmvalue.' ('.$GLOBALS['DBHCMS']['DICT']['BE'][$tmvalue].')</option>';
					} else { $input_html .= '<option value="'.$tmvalue.'">'.$tmvalue.' ('.$GLOBALS['DBHCMS']['DICT']['BE'][$tmvalue].')</option>'; }
				}
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<select name="'.$aname.'" id="'.$aname.'" style="width: 98%;">'.$input_html.'</select>
										</td>
									</tr>
								</table>';
		
		### LANGARRAY ###
		} else if ($atype == DBHCMS_C_DT_LANGARRAY) {
			if (is_array($avalue)) { 
				$selectvals = '';
				$selectid = 0;
				foreach ($avalue as $aval) {
					if ($aval != '') {
						$selectvals .= '<option id="id'.$selectid.'" value="'.htmlspecialchars($aval).'">'.htmlspecialchars($aval).'</option>';
						$selectid++;
					}
				}
			} else {
				$selectvals = '';
			}
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'">
									<tr>
										<td>
											<select name="'.$aname.'_sel" size="4" id="'.$aname.'_sel" style="width: 100%" >
												'.$selectvals.'
											</select>
										</td>
										<td width="15">
											<a href="#" onclick="getNewValue(\''.$aname.'\', \''.DBHCMS_C_DT_LANGUAGE.'\', 400, 450, \'\'); return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a><br />
											<a href="#" onclick="up(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('selup', dbhcms_f_dict('up', true)).'</a><br />
											<a href="#" onclick="down(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldown', dbhcms_f_dict('down', true)).'</a><br />
											<a href="#" onclick="removeitem(\''.$aname.'_sel\', true); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldel', dbhcms_f_dict('delete', true)).'</a>
										</td>
										<input type="hidden" name="'.$aname.'" id="'.$aname.'" value="'.dbhcms_f_array_to_str($avalue, ';').'" />
									</tr>
								</table>';
		
		### USERLEVEL ###
		} else if ($atype == DBHCMS_C_DT_USERLEVEL) {
			$input_html = '';
			foreach ($GLOBALS['DBHCMS']['TYPES']['FL'] as $feul) {
				if ($feul == 'A') {
					$area = 'Standard';
				} else {
					$area = 'Front End';
				}
				if ($avalue == $feul) {
					$input_html .= '<option value="'.$feul.'" selected="selected">'.$feul.' ('.$area.')</option>';
				} else { $input_html .= '<option value="'.$feul.'">'.$feul.' ('.$area.')</option>'; }
			}
			foreach ($GLOBALS['DBHCMS']['TYPES']['BL'] as $beul) {
				if ($avalue == $beul) {
					$input_html .= '<option value="'.$beul.'" selected="selected">'.$beul.' (Back End)</option>';
				} else { $input_html .= '<option value="'.$beul.'">'.$beul.' (Back End)</option>'; }
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<select name="'.$aname.'" id="'.$aname.'" style="width: 98%;">'.$input_html.'</select>
										</td>
									</tr>
								</table>';
		
		### ULARRAY ###
		} else if ($atype == DBHCMS_C_DT_ULARRAY) {
			if (is_array($avalue)) { 
				$selectvals = '';
				$selectid = 0;
				foreach ($avalue as $aval) {
					if ($aval != '') {
						
						if ($aval == 'A') {
							$area = 'Standard';
						} elseif (in_array($aval, $GLOBALS['DBHCMS']['TYPES']['FL'])) {
							$area = 'Front End';
						} elseif (in_array($aval, $GLOBALS['DBHCMS']['TYPES']['BL'])) {
							$area = 'Back End';
						}
						
						$selectvals .= '<option id="id'.$selectid.'" value="'.htmlspecialchars($aval).'">'.htmlspecialchars($aval).' ('.$area.') </option>';
						$selectid++;
					}
				}
			} else {
				$selectvals = '';
			}
			if (!in_array('selectEdit', $GLOBALS['DBHCMS']['STRUCT']['JS'])) {
				dbhcms_p_add_javascript ('selectEdit', 'select.js');
			}
			$input_html = 	'	<table style="'.$astyle.'">
									<tr>
										<td>
											<select name="'.$aname.'_sel" size="4" id="'.$aname.'_sel" style="width: 100%" >
												'.$selectvals.'
											</select>
										</td>
										<td width="15">
											<a href="#" onclick="getNewValue(\''.$aname.'\', \''.DBHCMS_C_DT_USERLEVEL.'\', 200, 450, \'\'); return false;" >'.dbhcms_f_get_icon('seladd', dbhcms_f_dict('add', true)).'</a><br />
											<a href="#" onclick="up(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('selup', dbhcms_f_dict('up', true)).'</a><br />
											<a href="#" onclick="down(\''.$aname.'_sel\'); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldown', dbhcms_f_dict('down', true)).'</a><br />
											<a href="#" onclick="removeitem(\''.$aname.'_sel\', true); document.getElementById(\''.$aname.'\').value = getValues(\''.$aname.'_sel\'); return false;" >'.dbhcms_f_get_icon('seldel', dbhcms_f_dict('delete', true)).'</a>
										</td>
										<input type="hidden" name="'.$aname.'" id="'.$aname.'" value="'.dbhcms_f_array_to_str($avalue, ';').'" />
									</tr>
								</table>';
		
		### DATATYPE ### 
		} else if ($atype == DBHCMS_C_DT_DATATYPE) {
			$input_html = '';
			foreach ($GLOBALS['DBHCMS']['TYPES']['DT'] as $datatype) {
				if ($datatype == $avalue) {
					$input_html .= '<option value="'.$datatype.'" selected="selected">'.strtoupper($datatype).'</option>';
				} else {
					$input_html .= '<option value="'.$datatype.'">'.strtoupper($datatype).'</option>';
				}
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<select name="'.$aname.'" id="'.$aname.'" style="width: 98%;">'.$input_html.'</select>
										</td>
									</tr>
								</table>';
		
		### MENUTYPE ### 
		} else if ($atype == DBHCMS_C_DT_MENUTYPE) {
			$input_html = '';
			foreach ($GLOBALS['DBHCMS']['TYPES']['MT'] as $menutype) {
				if ($menutype == $avalue) {
					$input_html .= '<option value="'.$menutype.'" selected="selected">'.strtoupper($menutype).'</option>';
				} else {
					$input_html .= '<option value="'.$menutype.'">'.strtoupper($menutype).'</option>';
				}
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<select name="'.$aname.'" id="'.$aname.'" style="width: 98%;">'.$input_html.'</select>
										</td>
									</tr>
								</table>';
		
		### HIERARCHY ### 
		} else if ($atype == DBHCMS_C_DT_HIERARCHY) {
			$input_html = '';
			foreach ($GLOBALS['DBHCMS']['TYPES']['HT'] as $hierarchy) {
				if ($hierarchy == $avalue) {
					$input_html .= '<option value="'.$hierarchy.'" selected="selected">'.strtoupper($hierarchy).'</option>';
				} else {
					$input_html .= '<option value="'.$hierarchy.'">'.strtoupper($hierarchy).'</option>';
				}
			}
			$input_html = 	'	<table style="'.$astyle.'" border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td>
											<select name="'.$aname.'" id="'.$aname.'" style="width: 98%;">'.$input_html.'</select>
										</td>
									</tr>
								</table>';
		
		### DATA TYPE UNKNOWN ###
		} else {
			dbhcms_p_error('Could not create input for runtime value. Wrong data type "'.strtoupper($atype).'".', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		}
		
		### OUTPUT ### 
		return $input_html;
	
	}

#############################################################################################
#                                                                                           #
#  FUNCTION: DBHCMS_F_DBVALUE_TO_INPUT                                                      #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#                                                                                           #
#  Creates an input html-tag from a database value with a given type.                       #
#                                                                                           #
#############################################################################################

	function dbhcms_f_dbvalue_to_input($aname, $avalue, $atype, $aform, $astyle = "width: 180px;", $adomain = "x") {
		return dbhcms_f_value_to_input($aname, dbhcms_f_dbvalue_to_value($avalue, $atype), $atype, $aform, $astyle, $adomain);
	}

#############################################################################################
#                                                                                           #
#  FUNCTION: DBHCMS_F_INPUT_TO_VALUE                                                        #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#                                                                                           #
#  Returns a runtime value of an input                                                      #
#                                                                                           #
#############################################################################################

	function dbhcms_f_input_to_value($aname, $atype, $amethod = DBHCMS_C_SM_POST) {
	
		if ($amethod == DBHCMS_C_SM_POST) {
			if (isset($_POST[$aname])) {
				$avalue = $_POST[$aname];
			} else {
				$avalue = '';
				dbhcms_p_error('POST value not set. Input "'.strtoupper($aname).'" was not submitted.', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			}
		} else if ($amethod == DBHCMS_C_SM_GET) {
			if (isset($_GET[$aname])) {
				$avalue = $_GET[$aname];
			} else {
				$avalue = '';
				dbhcms_p_error('GET value not set. Input "'.strtoupper($aname).'" was not submitted.', false, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
			}
		}
		
		$result = '';
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# BASIC TYPES                                                              #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### STRING ###
		if ($atype == DBHCMS_C_DT_STRING) {
			$result = trim(strval($avalue));
		
		### STRARRAY ###
		} else if ($atype == DBHCMS_C_DT_STRARRAY) {
			$result = array();
			if (!is_array($avalue)) {
				$avalue = explode(';', $avalue);
			}
			foreach ($avalue as $val) {
				if (trim($val) != '') {
					array_push($result, trim(strval($val)));
				}
			}
		
		### INTEGER ###
		} else if ($atype == DBHCMS_C_DT_INTEGER) {
			$result = intval(trim($avalue));
		
		### INTARRAY ###
		} else if ($atype == DBHCMS_C_DT_INTARRAY) {
			$result = array();
			if (!is_array($avalue)) {
				$avalue = explode(';', $avalue);
			}
			foreach ($avalue as $val) {
				if (trim($val) != '') {
					array_push($result, intval(trim($val)));
				}
			}
		
		### DATE ###
		} else if ($atype == DBHCMS_C_DT_DATE) {
			if ($amethod == DBHCMS_C_SM_POST) {
				$year = $_POST[$aname.'_year'];
				$month = $_POST[$aname.'_month'];
				$day = $_POST[$aname.'_day'];
			} else if ($amethod == DBHCMS_C_SM_GET) {
				$year = $_GET[$aname.'_year'];
				$month = $_GET[$aname.'_month'];
				$day = $_GET[$aname.'_day'];
			}
			$result = mktime(0, 0, 0, intval($month), intval($day), intval($year));
		
		### TIME ###
		} else if ($atype == DBHCMS_C_DT_TIME) {
			if ($amethod == DBHCMS_C_SM_POST) {
				$hour =  $_POST[$aname.'_hour'];
				$minute =  $_POST[$aname.'_minute'];
				$second = $_POST[$aname.'_second'];
			} else if ($amethod == DBHCMS_C_SM_GET) {
				$hour =  $_GET[$aname.'_hour'];
				$minute =  $_GET[$aname.'_minute'];
				$second = $_GET[$aname.'_second'];
			}
			$result = mktime(intval($hour), intval($minute), intval($second), 11, 11, 1970);
		
		### DATETIME ###
		} else if ($atype == DBHCMS_C_DT_DATETIME) {
			if ($amethod == DBHCMS_C_SM_POST) {
				$year = $_POST[$aname.'_year'];
				$month = $_POST[$aname.'_month'];
				$day = $_POST[$aname.'_day'];
				$hour =  $_POST[$aname.'_hour'];
				$minute =  $_POST[$aname.'_minute'];
				$second = $_POST[$aname.'_second'];
			} else if ($amethod == DBHCMS_C_SM_GET) {
				$year = $_GET[$aname.'_year'];
				$month = $_GET[$aname.'_month'];
				$day = $_GET[$aname.'_day'];
				$hour =  $_GET[$aname.'_hour'];
				$minute =  $_GET[$aname.'_minute'];
				$second = $_GET[$aname.'_second'];
			}
			$result = mktime(intval($hour), intval($minute), intval($second), intval($month), intval($day), intval($year));
		
		### TEXT ###
		} else if ($atype == DBHCMS_C_DT_TEXT) {
			$result = trim(strval($avalue));
			
		### HTML ###
		} else if ($atype == DBHCMS_C_DT_HTML) {
			$result = strval($avalue);
		
		### BOOLEAN ###
		} else if ($atype == DBHCMS_C_DT_BOOLEAN) {
			if ($avalue == '1') {
				$result = true;
			} else {
				$result = false;
			}
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# STRUCT TYPES                                                             #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### MODULE ###
		} else if ($atype == DBHCMS_C_DT_MODULE) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRING, $amethod);
		
		### MODARRAY ###
		} else if ($atype == DBHCMS_C_DT_MODARRAY) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRARRAY, $amethod);
		
		### TEMPLATE ###
		} else if ($atype == DBHCMS_C_DT_TEMPLATE) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRING, $amethod);
		
		### TPLARRAY ###
		} else if ($atype == DBHCMS_C_DT_TPLARRAY) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRARRAY, $amethod);
		
		### JAVASCRIPT ###
		} else if ($atype == DBHCMS_C_DT_JAVASCRIPT) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRING, $amethod);
		
		### JSARRAY ###
		} else if ($atype == DBHCMS_C_DT_JSARRAY) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRARRAY, $amethod);
		
		### STYLESHEET ###
		} else if ($atype == DBHCMS_C_DT_STYLESHEET) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRING, $amethod);
		
		### CSSARRAY ###
		} else if ($atype == DBHCMS_C_DT_CSSARRAY) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRARRAY, $amethod);
		
		### EXTENSION ###
		} else if ($atype == DBHCMS_C_DT_EXTENSION) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRING, $amethod);
		
		### EXTARRAY ###
		} else if ($atype == DBHCMS_C_DT_EXTARRAY) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRARRAY, $amethod);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# FILE TYPES                                                               #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### DIRECTORY ###
		} else if ($atype == DBHCMS_C_DT_DIRECTORY) {
			$result = trim(strval($avalue));
			if (strlen($result) > 0) {
				$char = $result{0};
				if ($char == '/') {
					$result = substr($result , 1);
				}
				$char = $result{strlen($result)-1}; 
				if ($char != '/') {
					$result = $result.'/';
				}
			} else {
				$result = '/';
			}
		
		### DIRARRAY ###
		} else if ($atype == DBHCMS_C_DT_DIRARRAY) {
			$result = array();
			if (is_array($avalue)) {
				foreach ($avalue as $val) {
					if (trim($val) != '') {
						$val = trim(strval($val));
						if (strlen($val) > 0) {
							$char = $val{0};
							if ($char == '/') {
								$val = substr($val , 1);
							}
							$char = $val{strlen($val)-1}; 
							if ($char != '/') {
								$val = $val.'/';
							}
						} else {
							$val = '/';
						}
						array_push($result, trim(strval($val)));
					}
				}
			} else {
				if (trim($avalue) != '') {
					$avalue = trim(strval($avalue));
					if (strlen($avalue) > 0) {
						$char = $avalue{0};
						if ($char == '/') {
							$avalue = substr($avalue , 1);
						}
						$char = $avalue{strlen($avalue)-1}; 
						if ($char != '/') {
							$avalue = $avalue.'/';
						}
					} else {
						$avalue = '/';
					}
					$result = array(trim(strval($avalue)));
				}
			}
		
		### FILE ###
		} else if ($atype == DBHCMS_C_DT_FILE) {
			$result = trim(strval($avalue));
			if (strlen($result) > 0) {
				$char = $result{0};
				if ($char == '/') {
					$result = substr($result , 1);
				}
			} else {
				$result = '/';
			}
		
		### FILEARRAY ###
		} else if ($atype == DBHCMS_C_DT_FILEARRAY) {
			$result = array();
			if (is_array($avalue)) {
				foreach ($avalue as $val) {
					if (trim($val) != '') {
						$val = trim(strval($val));
						if (strlen($val) > 0) {
							$char = $val{0};
							if ($char == '/') {
								$val = substr($val , 1);
							}
						} else {
							$val = '/';
						}
						array_push($result, trim(strval($val)));
					}
				}
			} else {
				if (trim($avalue) != '') {
					$avalue = trim(strval($avalue));
					if (strlen($avalue) > 0) {
						$char = $avalue{0};
						if ($char == '/') {
							$avalue = substr($avalue , 1);
						}
					} else {
						$avalue = '/';
					}
					$result = array(trim(strval($avalue)));
				}
			}
		
		### IMAGE ###
		} else if ($atype == DBHCMS_C_DT_IMAGE) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_FILE, $amethod);
		
		### IMGARRAY ###
		} else if ($atype == DBHCMS_C_DT_IMGARRAY) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_FILEARRAY, $amethod);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# ADVANCED TYPES                                                           #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### CONTENT ###
		} else if ($atype == DBHCMS_C_DT_CONTENT) {
			if (filesize($GLOBALS['DBHCMS']['CONFIG']['CORE']['tempDirectory']."tmp.content.".$aname.".".$_SESSION['DBHCMSDATA']['SID'].".txt") > 0) {
				$contentfile = fopen($GLOBALS['DBHCMS']['CONFIG']['CORE']['tempDirectory']."tmp.content.".$aname.".".$_SESSION['DBHCMSDATA']['SID'].".txt", "r");
				$result = fread($contentfile, filesize($GLOBALS['DBHCMS']['CONFIG']['CORE']['tempDirectory']."tmp.content.".$aname.".".$_SESSION['DBHCMSDATA']['SID'].".txt"));
				fclose($contentfile);
			} else { 
				$result = ''; 
			}
		
		### PASSWORD ###
		} else if ($atype == DBHCMS_C_DT_PASSWORD) {
			if ($avalue == '###NOCHANGE###') {
				$result = $_SESSION['DBHCMSDATA']['TEMP']['inputPasswd'][$aname.".".$_SESSION['DBHCMSDATA']['SID']];
			} else { 
				$result = md5($avalue); 
			}
			unset($_SESSION['DBHCMSDATA']['TEMP']['inputPasswd'][$aname.".".$_SESSION['DBHCMSDATA']['SID']]);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# OBJECT TYPES                                                             #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### USER ###
		} else if ($atype == DBHCMS_C_DT_USER) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRING, $amethod);
		
		### USERARRAY ###
		} else if ($atype == DBHCMS_C_DT_USERARRAY) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRARRAY, $amethod);
		
		### PAGE ###
		} else if ($atype == DBHCMS_C_DT_PAGE) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_INTEGER, $amethod);
		
		### PAGEARRAY ###
		} else if ($atype == DBHCMS_C_DT_PAGEARRAY) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_INTARRAY, $amethod);
		
		### DOMAIN ###
		} else if ($atype == DBHCMS_C_DT_DOMAIN) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_INTEGER, $amethod);
		
		### DOMAINARRAY ###
		} else if ($atype == DBHCMS_C_DT_DOMAINARRAY) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_INTARRAY, $amethod);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# PROPERTY TYPES                                                           #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### SEX ###
		} else if ($atype == DBHCMS_C_DT_SEX) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRING, $amethod);
			if 	(($result != DBHCMS_C_ST_MALE) && ($result != DBHCMS_C_ST_FEMALE)) {
				$result = DBHCMS_C_ST_NONE;
			}
		
		### LANGUAGE ### 
		} else if ($atype == DBHCMS_C_DT_LANGUAGE) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRING, $amethod);
		
		### LANGARRAY ###
		} else if ($atype == DBHCMS_C_DT_LANGARRAY) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRARRAY, $amethod);
		
		### USERLEVEL ###
		} else if ($atype == DBHCMS_C_DT_USERLEVEL) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRING, $amethod);
		
		### ULARRAY ###
		} else if ($atype == DBHCMS_C_DT_ULARRAY) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRARRAY, $amethod);
		
		### DATATYPE ### 
		} else if ($atype == DBHCMS_C_DT_DATATYPE) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRING, $amethod);
		
		### MENUTYPE ### 
		} else if ($atype == DBHCMS_C_DT_MENUTYPE) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRING, $amethod);
			if 	(($result != DBHCMS_C_MT_ACTIVETREE	) && ($result != DBHCMS_C_MT_LOCATION)) {
				$result = DBHCMS_C_MT_TREE;
			}
		
		### HIERARCHY ### 
		} else if ($atype == DBHCMS_C_DT_HIERARCHY) {
			$result = dbhcms_f_input_to_value($aname, DBHCMS_C_DT_STRING, $amethod);
			if 	(($result != DBHCMS_C_HT_ROOT) && ($result != DBHCMS_C_HT_SINGLE)) {
				$result = DBHCMS_C_HT_HEREDITARY;
			}
		
		### DATA TYPE UNKNOWN ###
		} else {
			dbhcms_p_error('Could not convert input value to runtime value. Wrong data type "'.strtoupper($atype).'".', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		}
		
		### OUTPUT ### 
		return $result;
	
}

#############################################################################################
#                                                                                           #
#  FUNCTION: DBHCMS_F_INPUT_TO_DBVALUE                                                      #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#                                                                                           #
#  Returns a database value of an input                                                     #
#                                                                                           #
#############################################################################################

	function dbhcms_f_input_to_dbvalue($aname, $atype, $amethod = DBHCMS_C_SM_POST) {
		return dbhcms_f_value_to_dbvalue(dbhcms_f_input_to_value($aname, $atype, $amethod), $atype);
	}


#############################################################################################
#                                                                                           #
#  FUNCTION: DBHCMS_F_VALUE_TO_OUTPUT                                                       #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#                                                                                           #
#  Turns a runtime value into an output value.                                              #
#                                                                                           #
#############################################################################################

	function dbhcms_f_value_to_output ($avalue, $atype) {
		
		$new_value = '';
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# BASIC TYPES                                                              #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### STRING ###
		if ($atype == DBHCMS_C_DT_STRING) {
			$new_value = htmlspecialchars(strval($avalue));
		
		### STRARRAY ###
		} else if ($atype == DBHCMS_C_DT_STRARRAY) {
			if (is_array($avalue)) {
				foreach ($avalue as $val) {
					$val = dbhcms_f_value_to_output($val, DBHCMS_C_DT_STRING);
					if ($val != '') {
						$new_value .= $val.' ';
					}
				}
				$new_value = trim($new_value);
			} else {
				$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRING);
			}
		
		### INTEGER ###
		} else if ($atype == DBHCMS_C_DT_INTEGER) {
			$new_value = htmlspecialchars(intval($avalue));
		
		### INTARRAY ###
		} else if ($atype == DBHCMS_C_DT_INTARRAY) {
			if (is_array($avalue)) {
				foreach ($avalue as $val) {
					$val = dbhcms_f_value_to_output($val, DBHCMS_C_DT_INTEGER);
					if ($val != '') {
						$new_value .= $val.' ';
					}
				}
				$new_value = trim($new_value);
			} else {
				$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_INTEGER);
			}
		
		### DATE ###
		} else if ($atype == DBHCMS_C_DT_DATE) {
			$new_value = htmlspecialchars(date($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['dateFormatOutput'], $avalue));
		
		### TIME ###
		} else if ($atype == DBHCMS_C_DT_TIME) {
			$new_value = htmlspecialchars(date($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['timeFormatOutput'], $avalue));
		
		### DATETIME ###
		} else if ($atype == DBHCMS_C_DT_DATETIME) {
			$new_value = htmlspecialchars(date($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['dateTimeFormatOutput'], $avalue));
		
		### TEXT ###
		} else if ($atype == DBHCMS_C_DT_TEXT) {
			$new_value = str_replace("\n", "<br />", dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRING));
			
		### HTML ###
		} else if ($atype == DBHCMS_C_DT_HTML) {
			$new_value = strval($avalue);
		
		### BOOLEAN ###
		} else if ($atype == DBHCMS_C_DT_BOOLEAN) {
			if ($avalue) {
				$new_value = '1';
			} else {
				$new_value = '0';
			}
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# STRUCT TYPES                                                             #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### MODULE ###
		} else if ($atype == DBHCMS_C_DT_MODULE) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRING);
		
		### MODARRAY ###
		} else if ($atype == DBHCMS_C_DT_MODARRAY) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRARRAY);
		
		### TEMPLATE ###
		} else if ($atype == DBHCMS_C_DT_TEMPLATE) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRING);
		
		### TPLARRAY ###
		} else if ($atype == DBHCMS_C_DT_TPLARRAY) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRARRAY);
		
		### JAVASCRIPT ###
		} else if ($atype == DBHCMS_C_DT_JAVASCRIPT) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRING);
		
		### JSARRAY ###
		} else if ($atype == DBHCMS_C_DT_JSARRAY) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRARRAY);
		
		### STYLESHEET ###
		} else if ($atype == DBHCMS_C_DT_STYLESHEET) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRING);
		
		### CSSARRAY ###
		} else if ($atype == DBHCMS_C_DT_CSSARRAY) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRARRAY);
		
		### EXTENSION ###
		} else if ($atype == DBHCMS_C_DT_EXTENSION) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRING);
		
		### EXTARRAY ###
		} else if ($atype == DBHCMS_C_DT_EXTARRAY) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRARRAY);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# FILE TYPES                                                               #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### DIRECTORY ###
		} else if ($atype == DBHCMS_C_DT_DIRECTORY) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRING);
			if (strlen($new_value) > 0) {
				$char = $new_value{0};
				if ($char == '/') {
					$new_value = substr($new_value , 1);
				}
				$char = $new_value{strlen($new_value)-1}; 
				if ($char != '/') {
					$new_value = $new_value.'/';
				}
			} else {
				$new_value = '/';
			}
		
		### DIRARRAY ###
		} else if ($atype == DBHCMS_C_DT_DIRARRAY) {
			if (is_array($avalue)) {
				foreach ($avalue as $val) {
					$val = dbhcms_f_value_to_output($val, DBHCMS_C_DT_DIRECTORY);
					if ($val != '') {
						$new_value .= $val.' ';
					}
				}
				$new_value = trim($new_value);
			} else {
				$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_DIRECTORY);
			}
		
		### FILE ###
		} else if ($atype == DBHCMS_C_DT_FILE) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRING);
			if (strlen($new_value) > 0) {
				$char = $new_value{0};
				if ($char == '/') {
					$new_value = substr($new_value , 1);
				}
			}
		
		### FILEARRAY ###
		} else if ($atype == DBHCMS_C_DT_FILEARRAY) {
			if (is_array($avalue)) {
				foreach ($avalue as $val) {
					$val = dbhcms_f_value_to_output($val, DBHCMS_C_DT_FILE);
					if ($val != '') {
						$new_value .= $val.' ';
					}
				}
				$new_value = trim($new_value);
			} else {
				$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_FILE);
			}
		
		### IMAGE ###
		} else if ($atype == DBHCMS_C_DT_IMAGE) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_FILE);
		
		### IMGARRAY ###
		} else if ($atype == DBHCMS_C_DT_IMGARRAY) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_FILEARRAY);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# ADVANCED TYPES                                                           #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### CONTENT ###
		} else if ($atype == DBHCMS_C_DT_CONTENT) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_HTML);
		
		### PASSWORD ###
		} else if ($atype == DBHCMS_C_DT_PASSWORD) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRING);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# OBJECT TYPES                                                             #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### USER ###
		} else if ($atype == DBHCMS_C_DT_USER) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRING);
		
		### USERARRAY ###
		} else if ($atype == DBHCMS_C_DT_USERARRAY) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRARRAY);
		
		### PAGE ###
		} else if ($atype == DBHCMS_C_DT_PAGE) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_INTEGER);
		
		### PAGEARRAY ###
		} else if ($atype == DBHCMS_C_DT_PAGEARRAY) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_INTARRAY);
		
		### DOMAIN ###
		} else if ($atype == DBHCMS_C_DT_DOMAIN) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_INTEGER);
		
		### DOMAINARRAY ###
		} else if ($atype == DBHCMS_C_DT_DOMAINARRAY) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_INTARRAY);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# PROPERTY TYPES                                                           #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### SEX ###
		} else if ($atype == DBHCMS_C_DT_SEX) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRING);
			if 	(($new_value != DBHCMS_C_ST_MALE) && ($new_value != DBHCMS_C_ST_FEMALE)) {
				$new_value = DBHCMS_C_ST_NONE;
			}
		
		### LANGUAGE ### 
		} else if ($atype == DBHCMS_C_DT_LANGUAGE) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRING);
		
		### LANGARRAY ###
		} else if ($atype == DBHCMS_C_DT_LANGARRAY) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRARRAY);
		
		### USERLEVEL ###
		} else if ($atype == DBHCMS_C_DT_USERLEVEL) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRING);
		
		### ULARRAY ###
		} else if ($atype == DBHCMS_C_DT_ULARRAY) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRARRAY);
		
		### DATATYPE ###
		} else if ($atype == DBHCMS_C_DT_DATATYPE) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRING);
		
		### MENUTYPE ###
		} else if ($atype == DBHCMS_C_DT_MENUTYPE) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRING);
			if 	(($new_value != DBHCMS_C_MT_ACTIVETREE) && ($new_value != DBHCMS_C_MT_LOCATION)) {
				$new_value = DBHCMS_C_MT_TREE;
			}
		
		### HIERARCHY ###
		} else if ($atype == DBHCMS_C_DT_HIERARCHY) {
			$new_value = dbhcms_f_value_to_output($avalue, DBHCMS_C_DT_STRING);
			if 	(($new_value != DBHCMS_C_HT_ROOT) && ($new_value != DBHCMS_C_HT_SINGLE)) {
				$new_value = DBHCMS_C_HT_HEREDITARY;
			}
		
		### DATA TYPE UNKNOWN ###
		} else {
			dbhcms_p_error('Could not convert runtime value to output value. Wrong data type "'.strtoupper($atype).'".', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		}
		
		### OUTPUT ### 
		return $new_value;
	
	}

	function dbhcms_f_dbvalue_to_output ($avalue, $atype) {
		return dbhcms_f_value_to_output (dbhcms_f_dbvalue_to_value($avalue, $atype), $atype);
	}

#############################################################################################
#                                                                                           #
#  FUNCTION: DBHCMS_P_ADD_VALUE                                                             #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#                                                                                           #
#  Adds a value to struct                                                                   #
#                                                                                           #
#############################################################################################

	function dbhcms_p_add_value($aname, $avalue, $atype){
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# BASIC TYPES                                                              #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### STRING ###
		if ($atype == DBHCMS_C_DT_STRING) {
			dbhcms_p_add_string(trim($aname), htmlspecialchars(strval($avalue)));
		
		### STRARRAY ###
		} else if ($atype == DBHCMS_C_DT_STRARRAY) {
			if (is_array($avalue)) {
				$nr = 0;
				foreach ($avalue as $val) {
					dbhcms_p_add_string(trim($aname).'_nr'.$nr, htmlspecialchars(strval($val)));
					$nr++;
				}
			} else {
				dbhcms_p_add_string(trim($aname), htmlspecialchars(strval($avalue)));
			}
		
		### INTEGER ###
		} else if ($atype == DBHCMS_C_DT_INTEGER) {
			dbhcms_p_add_string(trim($aname), intval($avalue));
		
		### INTARRAY ###
		} else if ($atype == DBHCMS_C_DT_INTARRAY) {
			if (is_array($avalue)) {
				$nr = 0;
				foreach ($avalue as $val) {
					dbhcms_p_add_string(trim($aname).'_nr'.$nr, intval($val));
					$nr++;
				}
			} else {
				dbhcms_p_add_string(trim($aname), intval($avalue));
			}
		
		### DATE ###
		} else if ($atype == DBHCMS_C_DT_DATE) {
			dbhcms_p_add_string(trim($aname), date($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['dateFormatOutput'], $avalue));
		
		### TIME ###
		} else if ($atype == DBHCMS_C_DT_TIME) {
			dbhcms_p_add_string(trim($aname), date($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['timeFormatOutput'], $avalue));
		
		### DATETIME ###
		} else if ($atype == DBHCMS_C_DT_DATETIME) {
			dbhcms_p_add_string(trim($aname).'_date', date($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['dateFormatOutput'], $avalue));
			dbhcms_p_add_string(trim($aname).'_time', date($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['timeFormatOutput'], $avalue));
			dbhcms_p_add_string(trim($aname), date($GLOBALS['DBHCMS']['CONFIG']['PARAMS']['dateTimeFormatOutput'], $avalue));
		
		### TEXT ###
		} else if ($atype == DBHCMS_C_DT_TEXT) {
			dbhcms_p_add_string(trim($aname), htmlspecialchars(strval($avalue)));
			
		### HTML ###
		} else if ($atype == DBHCMS_C_DT_HTML) {
			dbhcms_p_add_string(trim($aname), strval($avalue));
		
		### BOOLEAN ###
		} else if ($atype == DBHCMS_C_DT_BOOLEAN) {
			if ($avalue == true) {
				dbhcms_p_add_string(trim($aname), '1');
			} else {
				dbhcms_p_add_string(trim($aname), '0');
			}
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# STRUCT TYPES                                                             #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### MODULE ###
		} else if ($atype == DBHCMS_C_DT_MODULE) {
			dbhcms_p_add_php_module(trim($aname), strval($avalue));
		
		### MODARRAY ###
		} else if ($atype == DBHCMS_C_DT_MODARRAY) {
			if (is_array($avalue)) {
				$nr = 0;
				foreach ($avalue as $val) {
					dbhcms_p_add_php_module(trim($aname).'_nr'.$nr, strval($val));
					$nr++;
				}
			} else {
				dbhcms_p_add_php_module(trim($aname), strval($avalue));
			}
		
		### TEMPLATE ###
		} else if ($atype == DBHCMS_C_DT_TEMPLATE) {
			dbhcms_p_add_template(trim($aname), strval($avalue));
		
		### TPLARRAY ###
		} else if ($atype == DBHCMS_C_DT_TPLARRAY) {
			if (is_array($avalue)) {
				$nr = 0;
				foreach ($avalue as $val) {
					dbhcms_p_add_template(trim($aname).'_nr'.$nr, strval($val));
					$nr++;
				}
			} else {
				dbhcms_p_add_template(trim($aname), strval($avalue));
			}
		
		### JAVASCRIPT ###
		} else if ($atype == DBHCMS_C_DT_JAVASCRIPT) {
			dbhcms_p_add_javascript(trim($aname), strval($avalue));
		
		### JSARRAY ###
		} else if ($atype == DBHCMS_C_DT_JSARRAY) {
			if (is_array($avalue)) {
				$nr = 0;
				foreach ($avalue as $val) {
					dbhcms_p_add_javascript(trim($aname).'_nr'.$nr, strval($val));
					$nr++;
				}
			} else {
				dbhcms_p_add_javascript(trim($aname), strval($avalue));
			}
		
		### STYLESHEET ###
		} else if ($atype == DBHCMS_C_DT_STYLESHEET) {
			dbhcms_p_add_stylesheet(trim($aname), strval($avalue));
		
		### CSSARRAY ###
		} else if ($atype == DBHCMS_C_DT_CSSARRAY) {
			if (is_array($avalue)) {
				$nr = 0;
				foreach ($avalue as $val) {
					dbhcms_p_add_stylesheet(trim($aname).'_nr'.$nr, strval($val));
					$nr++;
				}
			} else {
				dbhcms_p_add_stylesheet(trim($aname), strval($avalue));
			}
		
		### EXTENSION ###
		} else if ($atype == DBHCMS_C_DT_EXTENSION) {
			dbhcms_p_add_extension(strval($avalue));
		
		### EXTARRAY ###
		} else if ($atype == DBHCMS_C_DT_EXTARRAY) {
			if (is_array($avalue)) {
				$nr = 0;
				foreach ($avalue as $val) {
					dbhcms_p_add_extension(strval($val));
					$nr++;
				}
			} else {
				dbhcms_p_add_extension(strval($avalue));
			}
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# FILE TYPES                                                               #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### DIRECTORY ###
		} else if ($atype == DBHCMS_C_DT_DIRECTORY) {
			dbhcms_p_add_value(trim($aname), $avalue, DBHCMS_C_DT_STRING);
		
		### DIRARRAY ###
		} else if ($atype == DBHCMS_C_DT_DIRARRAY) {
			dbhcms_p_add_value(trim($aname), $avalue, DBHCMS_C_DT_STRARRAY);
		
		### FILE ###
		} else if ($atype == DBHCMS_C_DT_FILE) {
			dbhcms_p_add_value(trim($aname), $avalue, DBHCMS_C_DT_STRING);
		
		### FILEARRAY ###
		} else if ($atype == DBHCMS_C_DT_FILEARRAY) {
			dbhcms_p_add_value(trim($aname), $avalue, DBHCMS_C_DT_STRARRAY);
		
		### IMAGE ###
		} else if ($atype == DBHCMS_C_DT_IMAGE) {
			dbhcms_p_add_value(trim($aname), $avalue, DBHCMS_C_DT_STRING);
		
		### IMGARRAY ###
		} else if ($atype == DBHCMS_C_DT_IMGARRAY) {
			dbhcms_p_add_value(trim($aname), $avalue, DBHCMS_C_DT_STRARRAY);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# ADVANCED TYPES                                                           #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### CONTENT ###
		} else if ($atype == DBHCMS_C_DT_CONTENT) {
			dbhcms_p_add_value(trim($aname), $avalue, DBHCMS_C_DT_HTML);
		
		### PASSWORD ###
		} else if ($atype == DBHCMS_C_DT_PASSWORD) {
			dbhcms_p_add_value(trim($aname), $avalue, DBHCMS_C_DT_STRING);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# OBJECT TYPES                                                             #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### USER ###
		} else if ($atype == DBHCMS_C_DT_USER) {
			dbhcms_p_add_value(trim($aname), $avalue, DBHCMS_C_DT_STRING);
		
		### USERARRAY ###
		} else if ($atype == DBHCMS_C_DT_USERARRAY) {
			dbhcms_p_add_value(trim($aname), $avalue, DBHCMS_C_DT_STRARRAY);
		
		### PAGE ###
		} else if ($atype == DBHCMS_C_DT_PAGE) {
			$avalue = intval($avalue);
			$aname = trim($aname);
			if (isset($GLOBALS['DBHCMS']['PAGES'][$avalue])) {
				$url = dbhcms_f_get_url_from_pid($avalue, $GLOBALS['DBHCMS']['PAGES'][$avalue]['domainId'], $GLOBALS['DBHCMS']['PAGES'][$avalue]['params']['urlPrefix'], $GLOBALS['DBHCMS']['PAGES'][$avalue]['shortcut'], $GLOBALS['DBHCMS']['PAGES'][$avalue]['link']);
				dbhcms_p_add_string($aname.'PageId', $avalue);
				dbhcms_p_add_string($aname.'PageUrl', $url);
				dbhcms_p_add_string($aname.'PageName', $GLOBALS['DBHCMS']['PAGES'][$avalue]['params']['name']);
				dbhcms_p_add_string($aname.'PageTarget', $GLOBALS['DBHCMS']['PAGES'][$avalue]['target']);
				dbhcms_p_add_string($aname.'PageLinkTag', '<a href="'.$url.'" target="'.$GLOBALS['DBHCMS']['PAGES'][$avalue]['target'].'" title="'.$GLOBALS['DBHCMS']['PAGES'][$avalue]['params']['name'].'" > '.$GLOBALS['DBHCMS']['PAGES'][$avalue]['params']['name'].' </a>');
			}
		
		### PAGEARRAY ###
		} else if ($atype == DBHCMS_C_DT_PAGEARRAY) {
			if (is_array($avalue)) {
				$nr = 0;
				foreach ($avalue as $val) {
					dbhcms_p_add_value(trim($aname).'_nr'.$nr, intval($val), DBHCMS_C_DT_PAGE);
					$nr++;
				}
			} else {
				dbhcms_p_add_value(trim($aname), intval($avalue), DBHCMS_C_DT_PAGE);
			}
		
		### DOMAIN ###
		} else if ($atype == DBHCMS_C_DT_DOMAIN) {
			dbhcms_p_add_value(trim($aname), $avalue, DBHCMS_C_DT_INTEGER);
		
		### DOMAINARRAY ###
		} else if ($atype == DBHCMS_C_DT_DOMAINARRAY) {
			dbhcms_p_add_value(trim($aname), $avalue, DBHCMS_C_DT_INTARRAY);
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# PROPERTY TYPES                                                           #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		### SEX ###
		} else if ($atype == DBHCMS_C_DT_SEX) {
			if (in_array($avalue, array(DBHCMS_C_ST_MALE, DBHCMS_C_ST_FEMALE))) {
				if ($avalue == DBHCMS_C_ST_MALE) {
					if ($GLOBALS['DBHCMS']['PID'] > 0) {
						dbhcms_p_add_string(trim($aname), $GLOBALS['DBHCMS']['DICT']['FE']['male']);
					} else {
						dbhcms_p_add_string(trim($aname), $GLOBALS['DBHCMS']['DICT']['BE']['male']);
					}
				} else if ($avalue == DBHCMS_C_ST_FEMALE) {
					if ($GLOBALS['DBHCMS']['PID'] > 0) {
						dbhcms_p_add_string(trim($aname), $GLOBALS['DBHCMS']['DICT']['FE']['female']);
					} else {
						dbhcms_p_add_string(trim($aname), $GLOBALS['DBHCMS']['DICT']['BE']['female']);
					}
				}
			} else {
				dbhcms_p_add_string(trim($aname), '');
			}
		
		### LANGUAGE ### 
		} else if ($atype == DBHCMS_C_DT_LANGUAGE) {
			dbhcms_p_add_string(trim($aname), trim($avalue));
			if ($GLOBALS['DBHCMS']['PID'] > 0) {
				if (isset($GLOBALS['DBHCMS']['DICT']['FE'][trim($avalue)])) {
					dbhcms_p_add_string(trim($aname).'Name', $GLOBALS['DBHCMS']['DICT']['FE'][trim($avalue)]);
				}
			} else {
				if (isset($GLOBALS['DBHCMS']['DICT']['BE'][trim($avalue)])) {
					dbhcms_p_add_string(trim($aname).'Name', $GLOBALS['DBHCMS']['DICT']['BE'][trim($avalue)]);
				}
			}
		
		### LANGARRAY ###
		} else if ($atype == DBHCMS_C_DT_LANGARRAY) {
			if (is_array($avalue)) {
				$nr = 0;
				foreach ($avalue as $val) {
					dbhcms_p_add_value(trim($aname).'_nr'.$nr, strval($val), DBHCMS_C_DT_LANGUAGE);
					$nr++;
				}
			} else {
				dbhcms_p_add_value(trim($aname), strval($avalue), DBHCMS_C_DT_LANGUAGE);
			}
		
		### USERLEVEL ###
		} else if ($atype == DBHCMS_C_DT_USERLEVEL) {
			dbhcms_p_add_value(trim($aname), $avalue, DBHCMS_C_DT_STRING);
		
		### ULARRAY ###
		} else if ($atype == DBHCMS_C_DT_ULARRAY) {
			dbhcms_p_add_value(trim($aname), $avalue, DBHCMS_C_DT_STRARRAY);
		
		### DATATYPE ### 
		} else if ($atype == DBHCMS_C_DT_DATATYPE) {
			dbhcms_p_add_value(trim($aname), $avalue, DBHCMS_C_DT_STRING);
		
		### MENUTYPE ### 
		} else if ($atype == DBHCMS_C_DT_MENUTYPE) {
			if (in_array($avalue, array(DBHCMS_C_MT_TREE, DBHCMS_C_MT_ACTIVETREE, DBHCMS_C_MT_LOCATION))) {
				dbhcms_p_add_string(trim($aname), $avalue);
			} else {
				dbhcms_p_add_string(trim($aname), '');
			}
		
		### HIERARCHY ### 
		} else if ($atype == DBHCMS_C_DT_HIERARCHY) {
			if (in_array($avalue, array(DBHCMS_C_HT_HEREDITARY, DBHCMS_C_HT_ROOT, DBHCMS_C_HT_SINGLE))) {
				dbhcms_p_add_string(trim($aname), $avalue);
			} else {
				dbhcms_p_add_string(trim($aname), '');
			}
		
		### DATA TYPE UNKNOWN ###
		} else {
			dbhcms_p_error('Could add runtime value to struct. Wrong data type "'.strtoupper($atype).'".', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		}
	
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>