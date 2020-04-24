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
# $Id: lib.captcha.php 71 2007-10-15 10:07:42Z kaisven $                                    #
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

	dbhcms_p_register_file(realpath(__FILE__), 'captcha', 0.1);

#############################################################################################
#  CLASS CAPTCHADIGIT                                                                       #
#############################################################################################

class captchaDigit {

  var $bits = array(1,2,4,8,16,32,64,128,256,512,1024,2048,4096,8192,16384);
  var $matrix  = array();
  var $bitmasks = array(31599, 18740, 29607, 31143, 18921, 31183, 31695, 18855, 31727, 31215);

  function captchaDigit( $dig ) {
    $this->matrix[] = array(0, 0, 0); // 2^0, 2^1, 2^2 ... usw.
    $this->matrix[] = array(0, 0, 0);
    $this->matrix[] = array(0, 0, 0);
    $this->matrix[] = array(0, 0, 0);
    $this->matrix[] = array(0, 0, 0); // ..., ..., 2^14

    ((int)$dig >= 0 && (int)$dig <= 9) && $this->setMatrix( $this->bitmasks[(int)$dig] );
  }

  function setMatrix( $bitmask ) {
    $bitsset = array();

    for ($i=0; $i<count($this->bits); ++$i)
      (($bitmask & $this->bits[$i]) != 0) && $bitsset[] = $this->bits[$i];

    foreach($this->matrix AS $row=>$col)
      foreach($col AS $cellnr => $bit)
        in_array( pow(2,($row*3+$cellnr)), $bitsset) && $this->matrix[$row][$cellnr] = 1;
  }
}

#############################################################################################
#  CLASS CAPTCHANUMBER                                                                      #
#############################################################################################

class captchaNumber {

  var $num = 0;
  var $digits = array();

  function captchaNumber( $num ) {
    $this->num = (int)$num;

    $r = "{$this->num}";
    for( $i=0; $i<strlen($r); $i++ )
      $this->digits[] = new captchaDigit((int)$r[$i]);
  }

  function getNum() { return $this->num; }

  function htmlNumber($size = 2) {
  	$res = '';
    for($row=0; $row<count($this->digits[0]->matrix); $row++) {
      foreach( $this->digits AS $digit ) {
        foreach($digit->matrix[$row] AS $cell)
          if($cell === 1) {
          	$res .= "<span style=\"color: black; background-color: black;\">&nbsp;&nbsp;&nbsp;</span>";
          } else {
          	$res .= "<span style=\"color: white; background-color: transparent;\">&nbsp;&nbsp;&nbsp;</span>";
          }
        $res .="<span style=\"color: white; background-color: transparent;\">&nbsp;&nbsp;&nbsp;</span>";
      }
      $res .="<br />";
    }
    return '<font style="font-size: '.$size.'px;">'.$res.'</font>';
  }
}

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>
