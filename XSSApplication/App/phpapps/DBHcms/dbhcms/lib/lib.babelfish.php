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
# $Id: lib.babelfish.php 60 2007-02-01 13:34:54Z kaisven $                                  #
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

	dbhcms_p_register_file(realpath(__FILE__), 'babelfish', 0.1);

#############################################################################################

/*******************************************************************************
** Class: babelfish
** Purpose: Translate text using Altavista Babelfish
** Filename: babelfish.class.php
** Author: Vedanta Barooah
** Author Email: vedanta . barooah @ gmail . com
** Date: June 19 2005
********************************************************************************/

/* if ! PHP5 */
if (!function_exists('http_build_query')) {
   function http_build_query($formdata, $numeric_prefix = "")
   {
       $arr = array();
       foreach ($formdata as $key => $val)
         $arr[] = urlencode($numeric_prefix.$key)."=".urlencode($val);
       return implode($arr, "&");
   }
}
/* translate text using altavista babelfish */
class babelfish{
    /* array to store language names */
    var $languages      =   NULL;
    /* stores the altavista babelfish url*/
    var $babel_url      =   NULL;
    /* stores the search regex  (see readme for details) */
    var $search_regex   =   NULL;
    /* stores the data to be posted in an array (see readme for details) */
    var $post_data      =   NULL;
    /* stores the supported translation combination(s) */
    var $valid_translate   =   NULL;
    /* class constructor */
    function babelfish($url=NULL,$postdata=NULL,$regex=NULL){
        /* list of languages */
        $this->languages    =       array(
                                        'en'	=>	'english',
                                        'zh'	=>	'chinese',
                                        'zt'	=>	'chinese-traditional',
                                        'nl'	=>	'dutch',
                                        'fr'	=>	'french',
                                        'de'	=>	'german',
                                        'el'	=>	'greek',
                                        'it'	=>	'italian',
                                        'ja'	=>	'japanese',
                                        'ko'	=>	'korean',
                                        'pt'	=>	'portuguese',
                                        'ru'	=>	'russian',
                                        'es'	=>	'spanish'
                                );
        /* list of valid translations */
        $this->valid_translate=array(
			'zt_en','en_zh','en_zt','en_nl','en_fr',
			'en_de','en_el','en_it','en_ja','en_ko',
			'en_pt','en_ru','en_es','nl_en','nl_fr',
			'fr_en','fr_de','fr_el','fr_it','fr_pt',
			'fr_nl','fr_es','de_en','de_fr','el_en',
			'el_fr','it_en','it_fr','ja_en','ko_en',
			'pt_en','pt_fr','ru_en','es_en','es_fr'
		);

        /* babelfish service url */
        if($url!=NULL)
            $this->babel_url=$url;
        else
            $this->babel_url="http://babelfish.altavista.com/tr";
        /* data that is posted to the babelfish site */
        if($postdata!=NULL)
            $this->post_data=$postdata;
        else
            $this->post_data=array(
                        'doit'=>'done',
                    	'intl'=>'1',
                    	'tt'=>'urltext',
                    	'trtext'=>NULL,
                    	'lp'=>NULL
            );
        /* search for the translated text using this regex */
        if($regex!=NULL)
            $this->search_regex=$regex;
        else
            #$this->search_regex='/<td bgcolor=white class=s><div style=padding:10px;>(.*)<\/div><\/td>/';
             $this->search_regex='/<td bgcolor=white class=s><div style=padding:10px;>(.*)<\/div><\/td>/sm';
    }
    /* perform babelfish translation */
    function translate($text,$from_language,$to_language){
        $f=array_search(strtolower($from_language),$this->languages);
        if(!$f){die("***error: source language not found");}
        $t=array_search(strtolower($to_language),$this->languages);
        if(!$t){die("***error: result language not found");}
        $l=$f.'_'.$t;
        if(!in_array($l,$this->valid_translate)){die("***error: cant translate with given combination ($l)");}
        $this->post_data['trtext']=$text;
        $this->post_data['lp']=$l;
        $query=http_build_query($this->post_data);
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_URL, $this->babel_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        $output = curl_exec($ch);
        curl_close($ch);
        $result=preg_match($this->search_regex,$output,$match);
        return strip_tags($match[0]);
    }
}
/* end of class */

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>