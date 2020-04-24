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
#  EXTENSION                                                                                #
#  =============================                                                            #
#  smilies                                                                                  #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Small implementation for smilies                                                         #
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
# $Id: ext.smilies.gl.php 69 2007-06-03 16:50:53Z kaisven $                                 #
#############################################################################################

	define('DBHCMS_C_EXT_SMILIES', 'smilies');

#############################################################################################
#  SETTINGS                                                                                 #
#############################################################################################

	$ext_name 		= DBHCMS_C_EXT_SMILIES;
	$ext_title 		= 'Smilies';
	$ext_descr		= 'Inserts smilies in contents.';
	$ext_inmenu		= false;
	$ext_version	= '1.0';

	dbhcms_p_configure_extension($ext_name, $ext_title, $ext_descr, $ext_inmenu, $ext_version);

#############################################################################################
#  GLOBAL IMPLEMENTATION                                                                    #
#############################################################################################

	if (in_array(DBHCMS_C_EXT_SMILIES, $GLOBALS['DBHCMS']['CONFIG']['PARAMS']['availableExtensions'])) {
	
		dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_SMILIES], 'smilies');
		$GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_SMILIES]['smilies'] = array();
		
		$result = mysql_query("SELECT * from ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_smilies");
		while ($row = mysql_fetch_array($result)) {
			dbhcms_f_array_push_assoc($GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_SMILIES]['smilies'], $row['smilie_kz']);
			$GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_SMILIES]['smilies'][$row['smilie_kz']] = $row['smilie_image'];
		}
		
		# Replace everything in HTML and CONTENT parameters
		foreach ($GLOBALS['DBHCMS']['PAGES'] as $pid => $page) {
			foreach ($page['params'] as $pname => $pvalue) {
				if ($pname != 'paramDataTypes') {
					if ( 
							($page['params']['paramDataTypes'][$pname] == DBHCMS_C_DT_HTML) 	||
							($page['params']['paramDataTypes'][$pname] == DBHCMS_C_DT_CONTENT) 
						) 
					{ 
						$GLOBALS['DBHCMS']['PAGES'][$pid]['params'][$pname] = smilies_f_replace_smilies($pvalue);
					} 
				}
			}
		}
	
	}

	#--------------------------------------------------------------------------#
	# SMILIES_F_CREATE_SMILIES_BAR                                             #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Returns a table with all the smilies and its codes to insert in an input #
	#                                                                          #
	# PARAMETERS:                                                              #
	# ==============                                                           #
	# @a_form_name : Name of the form of the input to place the smilies.       #
	# @a_input_name : Name of the of the input to place the smilies.           #
	#--------------------------------------------------------------------------#
	function smilies_f_create_smilies_bar($a_form_name, $a_input_name) {
		$sml_bar = '<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td>';
		$result = mysql_query("SELECT distinct(smilie_image) from ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_smilies");
		while ($row = mysql_fetch_array($result)) {
			$result_kz = mysql_query("SELECT smilie_kz from ".$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix']."ext_smilies where smilie_image like '".$row['smilie_image']."'");
			$row_kz = mysql_fetch_array($result_kz);
			$sml_bar .= "<a style=\"cursor:pointer\" onclick=\"document.".$a_form_name.".".$a_input_name.".value  += '".str_replace('\'', '\\\'', $row_kz['smilie_kz'])."';\"><img src=\"".$GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory']."smilies/ext.images/".$row['smilie_image']."\" width=\"15\" height=\"15\" alt=\"\" border=\"0\" align=\"absmiddle\"></a> ";
		}
		$sml_bar .= '</td></tr></table>';
		return $sml_bar;
	}

	#--------------------------------------------------------------------------#
	# SMILIES_F_REPLACE_SMILIES                                                #
	#--------------------------------------------------------------------------#
	# DESCRIPTION:                                                             #
	# ==============                                                           #
	# Places a smilie image weherever a smilie-code is                         #
	#                                                                          #
	# PARAMETERS:                                                              #
	# ==============                                                           #
	# @a_str : String to replace the smilies.                                  #
	#--------------------------------------------------------------------------#
	function smilies_f_replace_smilies ($a_str){
		$new_str = $a_str;
		foreach ($GLOBALS['DBHCMS']['CONFIG']['EXT'][DBHCMS_C_EXT_SMILIES]['smilies'] as $smilie_kz => $smilie_image) {
			$new_str = str_replace($smilie_kz, " <img src=\"".$GLOBALS['DBHCMS']['CONFIG']['CORE']['extensionDirectory']."smilies/ext.images/".$smilie_image."\" width=\"15\" height=\"15\" alt=\"\" border=\"0\" align=\"absmiddle\"> ", $new_str);
		}
		return $new_str;
	}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>